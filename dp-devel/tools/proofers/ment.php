<?
/*
   Test version of for_mentors.php.  Displays information useful to Mentors
   (i.e. those who are second-round proofreading projects with difficulty = "BEGINNER")

   BEGIN projects active:
   BEGIN pages queued:                      Oldest since:
   Newbie proofers queued:                  Oldest since:
   Unmentored proofers queued:              Oldest since:

   BEGIN projects open:

   Available   Bad    MIA
   BEGIN pages


   FIRST QUEUED PROOFER

   proofer registered date/time
   number of proofed pages previously second-proofed:       most recent: mm/dd hh:nn
   number of pages currently available for proofing:        first: mm/dd/yy   last: mm/dd/yy

 */

$relPath='../../pinc/';
// to establish logon
include_once($relPath.'dp_main.inc');
include_once($relPath.'theme.inc');
include_once('mentorclasses.inc');

// put mainline logic in braces for personal comfort only.
{
    error_reporting (E_ALL);
    ini_set("display_errors", "on");
    ment_main();
    return;
}

function UnMentored($newbie)
{
    return ($newbie->mentored_page_count == 0)
        && ($newbie->total_page_count == $newbie->active_page_count) ;
}

function ment_main()
{
    global $projects;
    // instantiate a set containing all R1 or R1 BEGIN projects
    $projects = new BeginProjects();
    // say("<br>New: ".count($projects->ProjectRecords())." projects");

    // create an empty PageSet collection to hold the results
    $pgset = new PageSet();

    // for each BEGIN project, add its pages to the PageSet
    SaySubhead("Active Mentor Projects");
    Say();
    Say( "BEGIN projects active: " . count($projects->ProjectRecords()));
    foreach($projects->ProjectRecords() AS $proj) {
        $pgs = new Pages($proj->projectid);
        say("**** Project "
            . $projects->NameOfWork($proj->projectid)
            . " has " . " ".count($pgs->Rows())." pages"
            . "  (".$projects->Language($proj->projectid).")");
        $pgset->Add($pgs->Rows());
    }
    Say( "Total page count = " . $pgset->Count());

    Say( "BEGIN proofer count: " . count($projects->Newbies()));

    // create a list of the newbies who have no R2+ pages - they are unmentored.
    $rawnewbies = array_filter($projects->Newbies(),"UnMentored");
    Say("Unmentored proofer count: " . count($rawnewbies));
    Say();
    SaySubhead("Unmentored Proofers:");

    $i = 0;
    foreach($rawnewbies AS $newbie) {
        Say();
        Say("Name---->    proofer" . ++$i); // $newbie->username);
        Say("  oldest page: " . mdy($newbie->earliest_time));
        Say("  newest page: " . mdy($newbie->latest_time));
        Say("  joined: " . $newbie->date_joined_string);
        Say("  BEGIN pgs: " . $newbie->active_page_count);
        Say("  Prior Mentored pages (R2, in process or completed:) "
                . $newbie->mentored_page_count);
        Say( "  Total proofed pgs: " . $newbie->total_page_count);
        Say("Active BEGIN Project Pages---- " 
            . ($newbie->active_page_count - $newbie->mentored_page_count));
        EchoPageList($pgset->Pages(), $newbie);
    }
    theme("","footer");
}

function nuid($pg)
{
    global $newbiename;

    return $pg->round1_user == $newbiename;
}

function EchoLanguageList($pages, $newbie)
{
}

function EchoPageList($pages, $newbie)
{
    global $projects;
    global $newbiename;
    $newbiename = $newbie->username;
    $pgs = array_filter($pages, "nuid");
    // Say( "Newbie pagelist: " 
        // . $newbie->username . "  Pages: " 
        // . count($pgs) ."/". count($pages));
    foreach($pgs AS $pg) {
        Say($projects->NameOfWork($pg->projectid)
            . "   Page: $pg->fileid  Checked In: ".mdyhm($pg->round1_time));
        // print_r($pg);
        // Say( "  $pg[0]   $pg->fileid   mdyhm($pg->round1_time)";
    }
}

function BeginPages()
{
    // get a set of rows from a project
    $projects = new BeginProjects;

    foreach($projects AS $proj) {
    }
}

?>
