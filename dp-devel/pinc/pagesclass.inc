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
        SELECT
            p.*,
            '$this->projectid' AS projectid,
            u1.pagescompleted AS r1pages,
            u2.pagescompleted AS r2pages,
            bb1.user_id AS r1uid,
            bb2.user_id as r2uid
        FROM pages as p
            INNER JOIN pages AS pg
                ON p.projectid = pg.projectid
            LEFT OUTER JOIN pageTasks AS pt1 ON pg.pageId = pt1.pageId
        ORDER BY pt1.pageTaskId DESC";
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

    function LoadPage($pagecode)
    {
       $prd = get_PRD_for_round(1);
       $state = $prd->page_avail_state;

        echo "Adding page for ($this->projectid)($pagecode)\n";
        $sql = "
            INSERT INTO pages
            SET
                    projectid       = '$this->projectid',
                    pageCode        = '$pagecode',
                    insertTime      = UNIX_TIMESTAMP(),
                    stateCode       = '$state'";
        QueryExecute($sql);
    }

    function ReleaseProject($roundCode)
    {
        $this->AddPageTasks($roundCode);
    }

    // Add or update a task for all pages
    function AddUpdateTasks($taskCode, $seq = 1, $state = "AVL")
    {
        $sql = "
            REPLACE INTO pageTasks
            SELECT
                    projectid,
                    pageCode,
                    '$taskCode',
                    $seq,
                    $state
            FROM
                    pages
            WHERE
                    projectid = $pages->projectId()
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

    function AddUpdateTask($pageCode, $taskCode, $seq = NULL, $username = NULL, $state = "AVL")
    {
        $sql = "
            REPLACE INTO pageTasks
            SET
                    projectid       = '$this->projectid',
                    pageCode        = '$pagecode.txt',
                    taskCode        = '$taskCode',
                    sequenceNumber  =  $seq,
                    username        = '$username',
                    stateCode       = 'AVL'
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
    function Page($row)
    {
        $this->pagerow = $row;
    }
}

?>
