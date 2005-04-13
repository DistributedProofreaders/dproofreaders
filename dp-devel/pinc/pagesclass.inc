<?

    // Class to handle a collection of pages,
    // with an eye to supplementing page_ops.inc

    // $Id$


function Say($str = "")
{
    print "<br>$str\n";
}

function Query($sql)
{
    $result = mysql_query($sql);
    CheckQueryError();
    mysql_free_result($result);
}

function CheckQueryError()
{
    if( mysql_errno())
    {
        echo "<br>" . mysql_error();
        return True;
    }
    else
    {
        return False;
    }
}

function QueryErrorMessage()
{
    return mysql_error();
}

function QueryRow($sql)
{
    $result = mysql_query($sql);
    if(! CheckQueryError())
        $row = mysql_fetch_object($result); 
    else
        $row = 0;
    mysql_free_result($result);
    return $row;
}

function QueryArray($sql, $key = 0)
{
    // Say($sql);
    $ary = array();
    $result = mysql_query($sql);

    if(!CheckQueryError()) 
    {
        while($row = mysql_fetch_object($result)) 
        {
            if($key)
                $ary[$row->$key] = $row;
            else
                $ary[] = $row;
        }
    }
    else
        say($sql);
    mysql_free_result($result);
    return $ary;
}

function QueryExecute($sql)
{
    // Say($sql);
    mysql_query($sql);
    CheckQueryError();
}

class Pages 
{
    var $projectid;
    var $pagerows;
    var $_filterstate;

    function Pages($projid) {
        $this->projectid = $projid;
    }

    function Page($pagecode){
        return new Page($this->pagerows[$pagecode]);
    }

    function Rows() {
        if(!isset($this->pagerows)) {
            $sql = $this->_selectProjectPagesSql();
            $this->pagerows = QueryArray($sql);
        }
        return $this->pagerows;
    }

    function _selectProjectPagesSql()
    {
        return "
        SELECT p.pageId,
               p.pageCode,
               p.stateCode,
               p.insertTime,
               pt.sequenceNumber,
               pt.userName,
               pt.saveTempTime,
               pt.checkoutTime,
               pt.saveCompleteTime,
               pt.turnBackTime,
               pt.taskStateCode
        FROM pages as p
            LEFT OUTER JOIN pageTasks AS pt ON p.pageId = pt.pageId
        WHERE p.projectid = '$this->projectid'
        ORDER BY pt.sequenceNumber DESC";
/*
            LEFT OUTER JOIN users AS u2
                ON nullif(p.round2_user,'') = u2.username
            LEFT OUTER JOIN phpbb_users AS bb1
                ON nullif(p.round1_user,'') = bb1.username
            LEFT OUTER JOIN phpbb_users AS bb2
                ON nullif(p.round2_user,'') = bb2.username
        ";
*/
    }

    // create first round tasks for all pages, and set to available
    function InitPages($roundCode)
    {
        $this->AddUpdateTasks('P1');
    }

    // Add or update a task for all pages
    function AddUpdateTasks($taskCode, $seq = 1, $state = "AVL")
    {
        $sql = "
            REPLACE pageTasks
            SET
                    pageid = pageid,
                    taskCode = '$taskCode',
                    sequenceNumber = $seq,
                    taskState = $state
            FROM
                    pages
            WHERE
                    projectid = '$this->projectId'
        ";
        QueryExecute($sql);
    }

    function UpdateTask($pageCode, $taskCode, $stateCode, $userName)
    {
        $sql = "
            UPDATE pageTasks
            SET
                    stateCode = '$stateCode',
                    userName = '$userName'
        WHERE projectid = $this->projectid
            AND pageCode = '$pageCode'
            AND taskCode = '$taskCode'
        ";
        QueryExecute($sql);
    }

    function CountForState($code)
    {
        global $_filter_state;
        $_filter_state = $code;
        return count(array_filter($this->Rows(), "_state_filter"));
    }

    function TotalPageCount()
    {
        return count($this->Rows());
    }

    function AddUpdateTask($pageId, $taskCode, $seq = NULL, $username = NULL, $state = "AVL")
    {
        $sql = "
            REPLACE INTO pageTasks
            SET
                    pageId          =  $pageId,
                    taskCode        = '$taskCode',
                    sequenceNumber  =  $seq,
                    username        = '$username',
                    stateCode       = '$state'
        ";
        QueryExecute($sql);
    }

    function PageTextPath($pagecode)
    {
        global $projects_dir;
        return "$projects_dir/$this->projectid/$pagecode.txt";
    }

    function PageImagePath($pagecode)
    {
        global $projects_dir;
        return "$projects_dir/$this->projectid/$pagecode.png";
    }

    function Drop()
    {
        QueryExecute("DELETE FROM pages WHERE projectid='$this->projectid'");
    }

    function Delete($pagecode)
    {
        QueryExecute("DELETE FROM pages
                      WHERE projectid='$this->projectid'
                      AND pagecode='$pagecode'");
    }

    // A page can be cleared from any task state.
    // Need a check here for possibly matching the current task for the project
    // if the PM wants to enforce task homogeneity. Only the most recent task
    // can be cleared, and then only by the user who owns that task on that page.
    // It can only be cleared by the username associated with the open task.
    function Clear($pagecode)
    {
        $page = $this->pagerows;

        if($page->taskUserName != $pguser){
            return _("You are not the current owner of that page.");
        }
        QueryExecute("UPDATE pageTasks SET stateCode = 'AVL'
                      WHERE projectid = $page->projectid
                          AND pageCode = $page->pageCode
                          AND taskCode = '$page->taskCode'");
    }

    // calling proc in page_ops doesn't validate so we won't at this point.
    function CheckOut($pageCode, $username, $taskCode)
    {
        queryExecute("
            UPDATE pageTasks
            SET stateCode = 'OUT',
                userName = $username,
                checkoutTime = UNIX_TIMESTAMP()
            WHERE projectid = $this->projectid
                AND pageCode = '$pageCode'
                AND taskCode = '$this->_activeTaskCode()´");
    }
    
    function SaveTextTemp($pageCode, $pageText)
    {
        _writeText($pageCode, $pageText);
        queryExecute("
            UPDATE pageTasks
                  SET saveTempTime = UNIX_TIMESTAMP()
            WHERE projectid = $this->projectid
                AND pageCode = '$pageCode'
                AND taskCode = '$this->_activeTaskCode()´");
        $savepath = $this->ActiveTaskDir();
        if(is_file($savepath))
        {
        delete($savepath);
        }
    }
    
    function SaveTextComplete($pageCode, $pageText)
    {
        _writeText($pageCode, $pageText);
        queryExecute("UPDATE pageTasks
                      SET stateCode = 'CMP',
                          saveCompleteTime = UNIX_TIMESTAMP()
            WHERE projectid = $this->projectid
                AND pageCode = '$pageCode'
                AND taskCode = '$this->_activeTaskCode()´");
        $savepath = $this->ActiveTaskDir();
        if(is_file($savepath))
        {
            delete($savepath);
        }
    }

    // handle reopen as savetemp, with the completed text file
    // as the temp version and the temp timestamp set.

    function ReOpen($fileid)
    {
        queryExecute("
            UPDATE pageTasks
                  SET saveTempTime = UNIX_TIMESTAMP(),
                  taskState = ´OUT'
            WHERE projectid = $this->projectid
                AND pageCode = '$pageCode'
                AND taskCode = '$this->_activeTaskCode()´");
    }
    
    function UnCheckout($fileid)
    {
        queryExecute("
            UPDATE pageTasks
                  SET saveTempTime = UNIX_TIMESTAMP(),
                  taskState = ´OUT'
            WHERE projectid = $this->projectid
                AND pageCode = '$pageCode'
                AND taskCode = '$this->_activeTaskCode()´");
    }

    function _writeText($pageCode, $pageText)
    {
        if(!is_dir($this->_activeTaskDir()))
        {
            mkdir($this->activeTaksDir(), 0777);
        }
        $handle = fopen($this->_activeTextPath()."/$pageCode.txt", "wb");
        fputs($handle, $pageText);
        fclose($handle);
    }

    function _activeProjectDir()
    {
        global $projects_dir;
        return "$projects_dir/$projectid";
    }

    function _activeTaskDir()
    {
        return "$this->_activeProjectDir()/$this->_activeTaskCode()";
    }
}


class Page
{
    var $pageCode;

    function Page($pageCode)
    {
        $this->pageCode = $pageCode;
    }

    function Load()
    {
        $round = get_Round_for_round_number(1);
        $state = $round->page_avail_state;

        echo "Adding page for ($this->pagerow->pageCode\n";
        $sql = "
            INSERT INTO pages
            SET
            pageCode        = $this->pageCode,
            insertTime      = UNIX_TIMESTAMP(),
            stateCode       = '$state'";
        QueryExecute($sql);
        return QueryErrorMessage();
    }
}

function _state_filter($row)
{
    global $_filter_state;
    return ($row->stateCode == $_filter_state);
}

?>
