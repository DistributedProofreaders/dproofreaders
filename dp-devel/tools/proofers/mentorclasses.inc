<?
// The following class provides all the info about a user required for project_detail.php.
// It uses 3 completely optimized sql calls, and eliminates dozens, mostly in user_is.inc.

function Say($str = "") {
    print "<br>$str\n";
}

function SaySubhead($str = "") {
    Say("<b><em>$str</em></b>");
}

function SayMDYHM($val) {
    Say(mdyhm($val));
}

function SayMDY($val) {
    Say(mdy($val));
}

function mdy($date) {
    return date("M jS, Y", $date);
}

function mdyhm($time) {
    return date("M jS, Y h:i", $time);
}

function Query($sql) {
    $result = mysql_query($sql);
    CheckQueryError();
    mysql_free_result($result);
}


function CheckQueryError() {
    if( mysql_errno()) {
        echo "<br>" . mysql_error();
        return True;
    }
    else {
        return False;
    }
}

function QueryRow($sql) {
    $result = mysql_query($sql);
    if(! CheckQueryError())
        $row = mysql_fetch_object($result); 
    else
        $row = 0;
    mysql_free_result($result);
    return $row;
}

function QueryArray($sql, $key = 0) {
    // Say($sql);
    $ary = array();
    $result = mysql_query($sql);

    if(!CheckQueryError()) {
        while($row = mysql_fetch_object($result)) {
            if($key)
                $ary[$row->$key] = $row;
            else
                $ary[] = $row;
        }
    }
    mysql_free_result($result);
    return $ary;
}

function QueryExecute($sql) {
    // Say($sql);
    mysql_query($sql);
    CheckQueryError();
}

class User
{
    var $userrec;
    var $settings;

    function User() {
        global $pguser;

        $sql = "SELECT u.*
                FROM dproofreaders.users AS u
                INNER JOIN dproofreaders.phpbb_users AS bb ON u.username = bb.username
                WHERE u.username = '$pguser'";
        $this->userrec = QueryRow($sql);

        $sql = "SELECT *
            FROM dproofreaders.usersettings
            WHERE username = '$pguser'";

        $this->settings = QueryArray($sql, "setting");
    }

    function IsSiteManager() {
        return ($this->userrec['sitemanager'] == 'yes');
    }

    function IsPM() {
        return $this->manager == 'yes';
    }

    function IsProjectFacilitator() {
        return isset($this->settings['proj_facilitator'])
            && ($this->settings['proj_facilitator'] == 'yes');
    }

    function is_a_sitemanager() {
        return $this->IsSiteManager();
    }

    function is_proj_facilitator() {
        return $this->IsSiteManager()
                || $this->IsProjFacilitator();
    }

    function is_PM() {
        return $this->is_a_sitemanager()
                || $this->is_proj_facilitator()
                || $this->IsPM();
    }

    function UserName() {
        global $pguser;
        return $pguser;
    }
}

class Project
{
    var $pid;
    var $rec;

    function Project($projectid) {
        $this->pid = $projectid;

        $sql = "SELECT p.projectid AS projectid,
                      u1.real_name AS managername,
                      u2.real_name AS scannername,
                      u3.real_name AS postproofername
                FROM dproofreaders.projects AS p
                LEFT OUTER JOIN dproofreaders.users AS u1 ON p.username=u1.username
                LEFT OUTER JOIN dproofreaders.users AS u2 ON p.scannercredit=u1.username
                LEFT OUTER JOIN dproofreaders.users AS u3 ON p.postproofer=u1.username
                WHERE projectid='" . $this->pid . "'";
        $projs = QueryArray($sql);
        if(count($projs) > 0) {
            $this->rec = $projs[0];
        }
    }
        
    function ProjectID() {
        return $this->pid;
    }

    function State() {
        return $this->rec->state;
    }

    function ManagerRealName() {
        return $this->rec->managername;
    }

    function ScannerRealName() {
        return $this->rec->scannername;
    }

    function PostProoferRealName() {
        return $this->rec->postproofername;
    }

    function Manager() {
        return $this->rec-manager;
    }

}

class ProjectForRow EXTENDS Project
{
    // encapsulate a row from a Projects object
    function ProjectForRow($rec)
    {
        $this->rec = $rec;   
    }
}

class Projects
{
    var $projrecs;
    var $pagerecs;

    function Projects($sql) {
        $this->projrecs = QueryArray($sql, "projectid");
    }

    function ProjectRecords() {
        return $this->projrecs;
    }

    function manager() {
        return $this->projrec->username;
    }

    function managercredit() {
        return $this->projrec->manager_real_name;
    }

    function scannercredit() {
        return $this->projrec->scanner_real_name;
    }

    function postproofername() {
        return $this->projrec->scanner_real_name;
    }

	function InRound() {
        return projectStateRound($this->projrec->state);
    }

    function pages() {
        return $this->pagerecs;
    }

    function ShowDelete() {
        return $this->CanEdit() &&
            (  $this->InRound() == 'NEW'
            || $this->InRound() == 'PR'
            || $this->InRound() == 'FIRST'
            || $this->InRound() =='SECOND') ;
    }

    function Path() {
        global $projects_dir; 
        return $projects_dir . "/" . $this->projectid . "/";
    }
} 

/*
    PageSet - double-indexed, on project for an array of pages
*/

class PageSet
{
    var $pages;

    function PageSet(){
        $this->pages = array();
    }

    function Add($addpages){
        // Say("Add: " . count($addpages));
        $ary = array_merge($this->pages, $addpages);
        $this->pages = $ary;
        // Say("New total: " . count($this->pages));
    }
    
    // find a page based on a two-part key: fileid, and projectid
    function Page($projid, $pageid) {
        return $this->pages[$projid][$pageid];
    }

    function Pages() {
        return $this->pages;
    }

    function Count(){
        return count($this->pages);
    }
        
}

class Page
{
    function Page($row) {
        $this->pagerow = $row;
    }
}

class Pages 
{
    var $pagerows;

    function Pages($projectid) {
        $sql = $this->_selectProjectPagesSql($projectid);
        $this->pagerows = QueryArray($sql);
    }

    function Page($fileid){
        return new Page($this->pagerows[$fileid]);
    }

    function Rows() {
        return $this->pagerows;
    }

    function _selectProjectPagesSql($projectid) {
        return "
        SELECT
            p.*,
            '$projectid' AS projectid,
            u1.pagescompleted AS r1pages,
            u2.pagescompleted AS r2pages,
            bb1.user_id AS r1uid,
            bb2.user_id as r2uid
        FROM dproofreaders.$projectid AS p
            LEFT OUTER JOIN dproofreaders.users AS u1
                ON nullif(p.round1_user,'') = u1.username
            LEFT OUTER JOIN dproofreaders.users AS u2
                ON nullif(p.round2_user,'') = u2.username
            LEFT OUTER JOIN dproofreaders.phpbb_users AS bb1
                ON nullif(p.round1_user,'') = bb1.username
            LEFT OUTER JOIN dproofreaders.phpbb_users AS bb2
                ON nullif(p.round2_user,'') = bb2.username";
    }
}

class BeginProjects EXTENDS Projects
{
    var $projectrows;
    var $projrecs;
    var $begpages;
    var $newbies;

    function BeginProjects() {
        $this->projrecs = QueryArray($this->_beginProjectsSql(), "projectid");

        $sql = $this->_CreateBeginPagesTable();
        QueryExecute($sql);

        foreach($this->projrecs AS $proj) {
            // add a project's pages to the big pages table
            $sql = _insertProjectBeginPages($proj->projectid);
            QueryExecute($sql);
        }
        $this->beginpages = QueryArray("SELECT * FROM beginpages");

        // create a temp newbies table
        mysql_query(_createNewbiesTable());
        // fill it
        QueryExecute(_insertNewbies());

        $this->newbies = queryArray("SELECT * FROM newbies", "username");
    }

    function Language($projectid) {
        return $this->projrecs[$projectid]->language;
    }

    function NameOfWork($projectid) {
        return $this->projrecs[$projectid]->nameofwork;
    }

    function ProjectRecords() {
        return $this->projrecs;
    }

    function Newbies() {
        return $this->newbies;
    }

    function _beginProjectsSql() {
        return  "SELECT projectid,
                        nameofwork,
                        language,
                        authorsname,
                        username AS PM,
                        state,
                        modifieddate AS releasedate
                FROM dproofreaders.projects
                WHERE difficulty = 'BEGINNER'
                    AND ( state='".PROJ_P2_AVAILABLE."' )";
    }

    function _CreateBeginPagesTable() {
        return "
            CREATE TEMPORARY TABLE beginpages
            (
             projectid varchar( 25 ) NOT NULL ,
             nameofwork varchar(128) NOT NULL ,
             language varchar(64) NOT NULL ,
             fileid varchar( 20 ) NULL ,
             image varchar( 8 ) NULL ,
             master_text longtext NULL ,
             round1_text longtext NULL ,
             round2_text longtext NULL ,
             round1_user varchar( 25 ) NULL ,
             round2_user varchar( 25 ) NULL ,
             round1_time int( 20 ) NULL ,
             round2_time int( 20 ) NULL ,
             state varchar( 50 ) NOT  NULL ,
             b_user varchar( 25 ) NULL ,
             b_code int( 1 ) NULL 
            ) TYPE  =  MYISAM";
    }
}

function pages($project) {
    // create the temp table we need
    mysql_query($createBeginPagesSql());
    CheckQueryError();

    // enumerate the projects and insert their pages into a common table
    foreach($this->projrecs AS $proj) {
        mysql_query($this->_insertProjectBeginPages($proj->projectid));
        CheckQueryError();
    }

    if(! count($this->pagerecs))
    return;

    // create table of newbies
    mysql_query($this->_createNewbiesTable());
    CheckQueryError();

    // and fill it
    mysql_query($this->_insertNewbies());
    CheckQueryError();

    $result = mysql_query("SELECT * FROM newbies ORDER BY round1_time");
    CheckQueryError();
    while($row = mysql_fetch_object($result)) {
        $this->newbies[$rec->username] = $row;
    }
    mysql_free_result($result);
}

function _insertProjectBeginPages($projectid) {
    return " INSERT INTO beginpages
            (projectid, nameofwork, fileid, image, master_text,
            round1_text, round2_text, round1_user,
            round2_user, round1_time, round2_time,
            state, b_user, b_code )
        SELECT '$projectid',
            p.nameofwork,
            fileid,
            image,
            master_text,
            round1_text,
            round2_text,
            round1_user,
            round2_user,
            round1_time,
            CASE WHEN pp.state = 'P2.page_saved' THEN round2_time ELSE 0 END AS round2_time,
            pp.state AS pagestate,
            b_user,
            b_code
        FROM dproofreaders.$projectid AS pp, dproofreaders.projects AS p
        WHERE p.projectid = '$projectid'" ;
}


// Newbies 
function _createNewbiesTable() {
    return "
        CREATE TEMPORARY TABLE newbies
        (
             username varchar( 25 ) NOT NULL ,
             earliest_time int(20) NOT NULL ,
             latest_time int(20) NOT NULL ,
             date_joined_string varchar(20) NOT NULL ,
             active_page_count mediumint(8) NOT NULL ,
             mentored_page_count mediumint(8) NOT NULL,
             total_page_count mediumint(8) NOT NULL
        )";
}

function _insertNewbies() {
    return "INSERT INTO newbies
        SELECT b.round1_user,
            MIN(b.round1_time) AS earliest_time,
            MAX(b.round1_time) AS latest_time,
            DATE_FORMAT(FROM_UNIXTIME(u.date_created),'%M-%d-%y')
            AS date_joined_string,
            COUNT(1) AS active_page_count,
            SUM(CASE WHEN round2_time=0 THEN 0 ELSE 1 END) AS mentored_page_count,
            u.pagescompleted AS total_page_count
       FROM beginpages AS b
            INNER JOIN dproofreaders.users AS u ON b.round1_user=u.username
            INNER JOIN dproofreaders.phpbb_users AS bbu ON u.u_id = bbu.user_id
       GROUP BY b.round1_user
       ORDER BY earliest_time";
} 
?>
