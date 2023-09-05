<?php
/**
 * hospitalExtension - this extension provides a single-tag listing of projects
 * which require repair before continuing processing at Distributed Proofreaders.
 *
 * To activate this extension, add the following into your LocalSettings.php file:
 * require_once('$IP/extensions/hospitalExtensions.php');
 */
/**
 * Protect against register_globals vulnerabilities.
 * This line must be present before any global variable is referenced.
 */
if (!defined('MEDIAWIKI')) {
    echo("This is an extension to the MediaWiki package and cannot be run standalone.\n");
    die(-1);
}

// NOTE: Update this to reflect your installation's path to the c/pinc directory
$relPath = '/var/www/htdocs/c/pinc/';

// Extension credits that will show up on Special:Version
$wgExtensionCredits['parserhook'][] = [
    'path' => __FILE__,
    'name' => 'DP Hospital Extension',
    'version' => '1.3',
    'author' => 'Distributed Proofreaders',
    'url' => '',
    'descriptionmsg' => '',
    'description' => 'Generates a listing of projects needing repair',
];

$wgExtensionFunctions[] = "wfHospitalInfo";

function wfHospitalInfo()
{
    $parser = \MediaWiki\MediaWikiServices::getInstance()->getParser();
    $parser->setHook("hospital_info", "listHospitalProjects");
}

function listHospitalProjects($input, $argv)
{
    global $relPath, $code_url;
    include_once($relPath.'site_vars.php');
    include_once($relPath.'DPDatabase.inc');
    include_once($relPath.'project_states.inc');

    DPDatabase::connect();

    $result = DPDatabase::query("
        SELECT *
        FROM projects
        WHERE nameofwork LIKE '%needs fixing%'
            AND state != 'project_delete'
        ORDER BY nameofwork ASC
    ");

    $output = "";
    while ($project = mysqli_fetch_object($result)) {
        // Get the preformatted remarks from PCs
        $matches = [];
        $problems = preg_match('/<pre>.*?<\/pre>/s', $project->comments, $matches);
        $pstate = project_states_text($project->state);
        $puri = "$code_url/project.php?id=$project->projectid";
        $plink = "<a href='$puri'>$project->nameofwork</a>";

        $output .= "<table style='border: 1px solid red; padding: 2px; width: 90%; margin: .75em;'>\n";
        $output .= "<tr><th width='12%' style='vertical-align: top;'>Title:</th><td>$plink</td></tr>\n";
        $output .= "<tr><th>Author:</th><td>$project->authorsname</td></tr>\n";
        $output .= "<tr><th>PM/State:</th><td>$project->username: <u>$pstate</u></td></tr>\n";
        $output .= "<tr><th style='vertical-align: top;'>Issues:</th><td style='font-size:90%;'>";
        if ($problems) {
            $output .= $matches ? wordwrap($matches[0], 80) : '&nbsp;';
        }
        $output .= "</td></tr>\n";
        $output .= "</table>\n";
    }

    mysqli_free_result($result);
    return $output;
}
