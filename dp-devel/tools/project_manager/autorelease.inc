<?
$relPath="./../../pinc/";
include_once($relPath.'v_site.inc');
include_once($relPath.'maybe_mail.inc');
include_once($relPath.'bookpages.inc');

function numpages($project) {
        $level0rows = mysql_query("SELECT COUNT(fileid) FROM $project");
        if ($level0rows != "") { return (mysql_result($level0rows,0,0)); } else return 0;
}

function numavailpages($project, $roundavailstate) {
        $level0rows = mysql_query("SELECT COUNT(fileid) FROM $project WHERE state = '$roundavailstate'");
        if ($level0rows != "") { return (mysql_result($level0rows,0,0)); } else return 0;
}

function verifynew($project, $author, $nameofwork, $language, $clearance, $email) {
        global $projects_dir;
        global $auto_email_addr;
        global $code_url;

    $currstate = PROJ_PROOF_FIRST_VERIFY;
    $result = mysql_query("UPDATE $project SET state = '".AVAIL_FIRST."' WHERE state = '".UNAVAIL_FIRST."'");
    $projectinfo = new projectinfo();

    $projectinfo->update($project, $state);

	//Update the total and available pages in the HEAP table
	update_total_pages($project, 1);

    $page_num = 0;

    $errors = '';

    if ($nameofwork == '') $errors .= "  The 'Title' field is blank.\n";
    if ($author == '')     $errors .= "  The 'Author' field is blank.\n";
    if ($language == '')   $errors .= "  The 'Language' field is blank.\n";
    if ($clearance == '')  $errors .= "  The 'Clearance' field is blank.\n";

    if ($errors) $errors .= "\n";
       
    $path = "$projects_dir/$project/";

    while ($page_num < $projectinfo->avail1_pages) {
        $fileid = mysql_result($projectinfo->avail1_rows, $page_num, "fileid");
        $text = mysql_result($projectinfo->avail1_rows, $page_num, "master_text");
        $filename = mysql_result($projectinfo->avail1_rows, $page_num, "image");

        if (!file_exists($path.$filename)) {
            $errors .= "  $filename: image file is missing\n";
        } else if (filesize($path.$filename) < 100) {
            $errors .= "  $filename: image file is small (probably bad)\n";
        }
        if ($text == "") {
            $errors .= "  $filename: text is empty\n";
        }

        $page_num++;
    }

    if ( $errors )
    {
        maybe_mail("$email", "DP: \"$nameofwork\"",
            "This is an automated message from the Distributed Proofreaders site.\n\n".
            "Regarding:\n".
            "  \"$nameofwork\"\n".
            "  ($project)\n".
            "  $code_url/tools/project_manager/project_detail.php?project=$project\n".
            "\n".
            "Some errors have been found:\n".
            "$errors\n".
            "Please correct the errors and put the project back into Waiting for Release.",
            "From: $auto_email_addr\r\nReply-To: $auto_email_addr\r\n");
        $currstate = PROJ_PROOF_FIRST_BAD_PROJECT;
    }


    $error_msg = project_transition( $project, $currstate );
    if ($error_msg)
    {
        echo "$error_msg<br>\n";
        return 0;
    }

    if ($currstate == PROJ_PROOF_FIRST_VERIFY) {
         print "Prepared to release project = $project<BR>";
         return numpages($project);

    } else return 0;

}


// Release available projects of a given type (defined by $projtype_sql) until a target number of
// PROJECTS or PAGES (depending on $track_by) of that type is available for proofing

// $track_by            what units do we measure by, "project" or "page"?
// $projtype_desc       human readable string describing this type of project
// $projtype_sql        portion of WHERE clause that defines this type of project
// $target              total number of units of this type we want available for proofing
// $released            total number of units of this type already released before we start
// $count2ndRnd         if "yes", and tracking by "page", consider contribution towards target of second round

function release_projects($track_by, $projtype_desc, $projtype_sql, $target, $released, $count2ndRnd, $todaysdate)
{
        $i = 0;
        $done = 0;
        $newpagesreleased = 0;
        $totalnewreleased = 0;
        $numReleased = 0;

        // if there's enough out there already, don't bother
        if ($target < $released) return 0;

        // set of candidates for release
        $avail_result = mysql_query("SELECT * FROM projects WHERE state = '".PROJ_PROOF_FIRST_WAITING_FOR_RELEASE."' AND $projtype_sql ORDER BY modifieddate ASC");
        $numAvailable = mysql_num_rows($avail_result);

        // Keep releasing these projects (SP) until:
        //      we meet our target for them ($released >= $target),
        // or   we run out of SP ready to release ($numAvailable),
        // or   subject to the "no doubling up of authors" rule ($done)
        // NB No other restrictions; specifically, $pagesneeded cannot influence SPs;
        // this is so a huge book ariving from another queue won't stop the release of needed SPs

        while ($numAvailable > 0 && $released < $target && $done != 1) {
                // check the date
                $modifieddate = mysql_result($avail_result, $i, "modifieddate");

                // if we haven't already earlier today rejected (under the "no doubling up of authors" rule),
                // this candidate for early release, consider it now
                if ($modifieddate != $todaysdate) {
                        $author = mysql_result($avail_result, $i, "authorsname");
                        $project = mysql_result($avail_result, $i, "projectid");


                        // "no doubling up of authors"


                        // corrected to include books already marked for release

                        $author_q = "SELECT projectid FROM projects WHERE
                                (state = '".PROJ_PROOF_FIRST_AVAILABLE."'
                                        OR
                                state = '".PROJ_PROOF_FIRST_VERIFY."') AND authorsname = '$author'";

                        if (mysql_num_rows(mysql_query($author_q)) > 0) {
                                // fails the author restriction,
				// push this back two day's worth (in terms of order, not delay!) in the queue
                                $temp = mysql_query("UPDATE projects SET modifieddate = modifieddate + 172800 WHERE projectid = '$project'");
                                $numAvailable--;
                        } else {
                                // OK to release
                                // get the other data
                                $nameofwork = mysql_result($avail_result, $i, "nameofwork");
                                $language = mysql_result($avail_result, $i, "language");
                                $clearance = mysql_result($avail_result, $i, "clearance");
                                $pm = mysql_result($avail_result, $i, "username");
                                $temp = mysql_query("SELECT email FROM users WHERE username = '$pm'");
                                $email = mysql_result($temp, 0, "email");

                                // release the project for proofing, and keep track of
                                // its contribution to reducing our total page shortfall
                                $newpagesreleased = verifynew($project, $author, $nameofwork, $language, $clearance, $email);
                                $totalnewreleased = $totalnewreleased + (2 * $newpagesreleased);
                                $numAvailable--;
                                $numReleased++;

                                // update our progress towards the target,
                                // depending on whether we are tracking by project or page
                                if ($track_by == "project") {
                                        $released = $numReleased;
                                } else {
                                        $released = $released + $newpagesreleased;
                                        if ($count2ndRnd == "yes") $released = $released + $newpagesreleased;
                                }
                        }
                }  else {
                        // since projects are sorted by date, if we reach here we have already
                        // considered and rejected all remaining candidates under the author rule
                        // and can stop checking
                        $done = 1;
                }
                $i++;
        }

        // if $numavailable == 0, email someone!
        if ($numavailable == 0)
		echo "<BR>Would like to have released some $projtype_desc projects, but none were waiting!<br>";
	else
	        echo "<br>Prepared <b>$numReleased</b> $projtype_desc projects for release<br>";
        return $totalnewreleased;
}


function autorelease($pagesleft) {
        $todaysdate = time();
        $pagesneeded = $GLOBALS['pagesneeded'] - $pagesleft;
        $noneng_pagesneeded = $GLOBALS['noneng_pagesneeded'];
        $eng_pagesneeded = $GLOBALS['eng_pagesneeded'];
        $beginners_pagesneeded = $GLOBALS['beginners_pagesneeded'];
        $easy_projects = $GLOBALS['easy_projects'];


	echo "Starting autorelease<BR>Current thresholds/targets are:<BR>";
	echo "$beginners_pagesneeded pages for Beginners Only<BR>";
	echo "$easy_projects EASY projects<BR>";
	echo "$noneng_pagesneeded nonEnglish pages minimum<BR>";
	echo "$eng_pagesneeded English pages minimum<BR>";
	//echo "$GLOBALS['pagesneeded'] total pages minimum<BR><BR><BR>";


        // Release of Different types of Projects from various logical queues
        // NB The arguments for these calls can come out of a table LATER (stage two)...
        // For now, let's get it out and working, even with hardwired arguments

        //Beginners Projects - always have a certain number of pages available in R1
	$numReleased = 0;
        $projtype_sql = "username = 'BEGIN'";
        $rel_qry = mysql_query("SELECT projectid FROM projects WHERE state = '".PROJ_PROOF_FIRST_AVAILABLE."'  AND $projtype_sql ORDER BY modifieddate ASC");
        $numProjects = mysql_num_rows($rel_qry);
	$i = 0;
        // how many page still available for proofing, in R1, from each BEGIN project?
        while ($i < $numProjects) {
                $proj = mysql_result($rel_qry,$i,0);
                $numReleased = $numReleased + numavailpages($proj, AVAIL_FIRST);
                $i++;
        }
        if ($beginners_pagesneeded > $numReleased) {
                $pagesneeded = $pagesneeded -
                        release_projects("pages", "Beginners Only", $projtype_sql , $beginners_pagesneeded, $numReleased, "", $todaysdate);
        }   else echo "Enough Beginners Only pages ($numReleased) in R1 already, no releases through the BEGIN Queue<BR>";


        //Easy English Projects - always have a certain number of projects in first round
        $projtype_sql = "genre LIKE 'EASY%' && language LIKE 'English%'";
        $rel_qry = mysql_query("SELECT COUNT(projectid) FROM projects WHERE state = '".PROJ_PROOF_FIRST_AVAILABLE."' AND $projtype_sql ORDER BY modifieddate ASC");
        $numReleased = mysql_result($rel_qry,0);

        if ($easy_projects > $numReleased) {
                $pagesneeded = $pagesneeded -
                        release_projects("project", "Easy English", $projtype_sql , $easy_projects, $numReleased, "", $todaysdate);
        }   else echo "Enough EASY English projects  ($numReleased) already, no releases through the EASY English Queue<BR>";


        //Non-English Projects - always have a certain number of pages spread over the two rounds
        $numReleased = 0;
        $projtype_sql = "language NOT LIKE 'English%'";
        $rel_qry = mysql_query("SELECT projectid, state FROM projects WHERE (state = '".PROJ_PROOF_FIRST_AVAILABLE."' OR state = '".PROJ_PROOF_SECOND_AVAILABLE."') AND $projtype_sql ORDER BY modifieddate ASC");
        $numProjects = mysql_num_rows($rel_qry);
        $i = 0;
        // how many page still available for proofing, total, from each (nonEng) project?
        while ($i < $numProjects) {
                $proj = mysql_result($rel_qry,$i,0);
                if (mysql_result($rel_qry,$i,1) == PROJ_PROOF_FIRST_AVAILABLE) {
                        // first round, add contribution of second round too
                        $numReleased = $numReleased + numpages($proj) + numavailpages($proj, AVAIL_FIRST);
                } else {
                        $numReleased = $numReleased + numavailpages($proj, AVAIL_SECOND);
                }
                $i++;
        }
        if ($noneng_pagesneeded > $numReleased) {
                $pagesneeded = $pagesneeded -
                        release_projects("pages", "Non English", $projtype_sql , $noneng_pagesneeded, $numReleased, "yes", $todaysdate);
        }   else echo "Enough Non English pages ($numReleased) already, no releases through the Non English Queue<BR>";


        //English Projects - always have a certain number of pages spread over the two rounds
        $numReleased = 0;
        $projtype_sql = "language LIKE 'English%'";
        $rel_qry = mysql_query("SELECT projectid,state FROM projects WHERE (state = '".PROJ_PROOF_FIRST_AVAILABLE."' OR state = '".PROJ_PROOF_SECOND_AVAILABLE."') AND $projtype_sql ORDER BY modifieddate ASC");
        $numProjects = mysql_num_rows($rel_qry);
        $i = 0;
        // how many page still available for proofing, total, from each (Eng) project?
        while ($i < $numProjects) {
                $proj = mysql_result($rel_qry,$i,0);
                if (mysql_result($rel_qry,$i,1) == PROJ_PROOF_FIRST_AVAILABLE) {
                        $numReleased = $numReleased + numpages($proj) + numavailpages($proj, AVAIL_FIRST);
                } else {
                        $numReleased = $numReleased + numavailpages($proj, AVAIL_SECOND);
                }
                $i++;
        }
        if ($eng_pagesneeded > $numReleased) {
                $pagesneeded = $pagesneeded -
                        release_projects("pages", "English", $projtype_sql , $eng_pagesneeded, $numReleased, "yes", $todaysdate);
        }   else echo "Enough English pages ($numReleased) already, no releases through the English Queue<BR>";


        //Extra Projects - make sure we have a total of at least X pages spread out over the two rounds;
        // if there's any room left, just release projects from the front of the queue until we have
        // enough pages out

        if ($pagesneeded > 0) {
                $numReleased = 0;
                $rel_qry = mysql_query("SELECT projectid, state FROM projects WHERE (state = '".PROJ_PROOF_FIRST_AVAILABLE."' OR state = '".PROJ_PROOF_SECOND_AVAILABLE."') ORDER BY modifieddate ASC");
                $numProjects = mysql_num_rows($rel_qry);
                $i = 0;
                // how many page still available for proofing, total, from each project?
                while ($i < $numProjects) {
                        $proj = mysql_result($rel_qry,$i,0);
                        if (mysql_result($rel_qry,$i,1) == PROJ_PROOF_FIRST_AVAILABLE) {
                                $numReleased = $numReleased + numpages($proj) + numavailpages($proj, AVAIL_FIRST);
                        } else {
                                $numReleased = $numReleased + numavailpages($proj, AVAIL_SECOND);
                        }
                        $i++;
                }
                release_projects("pages", "Extra", $projtype_sql , $pagesneeded, $numReleased, "yes", $todaysdate);
        }   else {
               echo "Enough total pages  already, no extra releases<BR>";
       }
}
?>
