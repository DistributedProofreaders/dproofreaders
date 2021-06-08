<?php
// This is a PROD hack. Changes to this file will be overwritten upon the
// next deploy unless you also change the file in
// /home/dpadmin/bin/c_hacks.newfiles/pinc too.

function output_qual_projects($round)
{
    global $code_url;

    if (in_array($round, ["P2", "P3"])) {
        $qual_type = "P3";
    } elseif (in_array($round, ["F1", "F2"])) {
        $qual_type = "F2";
    } else {
        return;
    }

    $sql = sprintf(
        "
        SELECT projectid
        FROM projects
        WHERE state = '%s.proj_avail' AND
            nameofwork like '%%%s Qual%%'
        ",
        DPDatabase::escape($round),
        DPDatabase::escape($qual_type)
    );
    $results = DPDatabase::query($sql);

    $projectids = [];
    while ([$projectid] = mysqli_fetch_row($results)) {
        $projectids[] = $projectid;
    }

    if (!$projectids) {
        return;
    }

    echo "<h2>" . sprintf(_("%s Qualifying Projects"), $qual_type) . "</h2>";

    switch ($round) {
        case 'P2':
        case 'F1':
            echo "<p>" . _("The following projects are currently available:") . "</p>";
            break;
        case 'P3':
        case 'F2':
            echo "<p>" . sprintf(_("Your help in proofreading these %s Qualifier projects would be greatly appreciated:"), $round) . "</p>";
            break;
    }

    echo "<ul>";
    foreach ($projectids as $projectid) {
        $project = new Project($projectid);
        $proj_url = "";
        echo "<li>";
        echo "<a href='$code_url/project.php?id={$project->projectid}'>";
        echo html_safe($project->nameofwork);
        echo "</a> &mdash; ({$project->language}) {$project->n_available_pages} pp. available.";
        echo "</li>";
    }
    echo "</ul>";

    // Post list caveats, etc. can be inserted here
    switch ($round) {
        case 'P2':
        case 'F1':
            echo "<p>" . sprintf(_("%1\$s Qualifying projects are for volunteers who are preparing to apply for %1\$s access."), $qual_type) . "</p>";
            echo "<p>" . sprintf(_("Please make sure you have satisfied the <a href='%1\$s'>%2\$s entrance requirements</a> <i>before</i> applying.</p>"), "$code_url/tools/proofers/round.php?round_id=$qual_type#Entrance_Requirements", $qual_type) . "</p>";
            break;
        case 'P3':
        case 'F2':
            echo "<p>" . sprintf(_("Thank you very much for your help with the %s Quals!"), $qual_type) . "</p>";
            break;
    }
}
