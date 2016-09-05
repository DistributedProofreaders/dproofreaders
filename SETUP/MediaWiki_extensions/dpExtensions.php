<?php
/**
 * DP Extensions - this extension provides the projectinfo tag for listing
 * project information at Distributed Proofreaders.
 *
 * To activate this extension, add the following into your LocalSettings.php file:
 * require_once('$IP/extensions/dpExtensions.php');
 */
/**
 * Protect against register_globals vulnerabilities.
 * This line must be present before any global variable is referenced.
 */
if( !defined( 'MEDIAWIKI' ) ) {
        echo( "This is an extension to the MediaWiki package and cannot be run standalone.\n" );
        die( -1 );
}

// NOTE: Update this to reflect your installation's path to the c/pinc directory
$relPath = '/data/htdocs/c/pinc/';
 
// Extension credits that will show up on Special:Version    
$wgExtensionCredits['parserhook'][] = array(
        'path'           => __FILE__,
        'name'           => 'DP Extensions',
        'version'        => '1.2',
        'author'         => 'Distributed Proofreaders', 
        'url'            => '',
        'descriptionmsg' => '',
        'description'    => 'Provides custom tags for listing DP projects and PG titles'
);

$wgExtensionFunctions[] = "wfPgFormats";
$wgExtensionFunctions[] = "wfProjectInfo";

function wfPgFormats() 
{
    global $wgParser;
    $wgParser->setHook( "pg_formats", "getPgFormats" );
}

function getPgFormats( $input, $argv ) 
{
    $err = "<strong style='color: red;'>[Error: getPgFormats: %s]</strong>";

    $etext = mysql_real_escape_string(@$argv['etext']);

    if (empty($etext) || !is_numeric($etext))
        return sprintf($err,"invalid etext number");

    $result = mysql_query("
        SELECT formats 
        FROM dproofreaders.pg_books 
        WHERE 
            etext_number = '$etext' 
        LIMIT 1
    ");

    if (!$result || mysql_num_rows($result) == 0)
        return sprintf($err,"invalid etext number; possibly not yet posted");

    $formats = '[' . mysql_result($result,0,'formats') . ']';

    if (empty($input))
        return $formats;

    @preg_match('/([^,]+),(.*)/',$input,$matches);

    if (empty($matches[1]) || empty($matches[2]))
        return sprintf($err,"No comma in input text. Use short tag instead.");

    return "<a href='http://www.gutenberg.org/ebooks/$etext' class='extiw'>" .
        "$matches[1]</a>, $matches[2] -- $formats";
}

// ----------------------------------------------------------------------------

function wfProjectInfo() {
    global $wgParser;
    $wgParser->setHook("projectinfo","showProjectInfo");
}

function showProjectInfo($input, $argv, $parser)
{
    $err = "<strong style='color: red;'>[Error: showProjectInfo: %s]</strong>";
    $pid = mysql_real_escape_string(@$argv['id']);

    $result = mysql_query("SELECT * 
        FROM dproofreaders.projects 
        WHERE 
            projectid = '$pid' 
        LIMIT 1");

    if (!$result || (mysql_num_rows($result) == 0))
        return sprintf($err,"Invalid projectID: $pid");

    $project = mysql_fetch_assoc($result);

    global $relPath;
    include_once($relPath.'project_states.inc');

    $project['raw_state'] = $project['state'];
    $project['state'] = project_states_text($project['state']);

    $project['title'] = $project['nameofwork'];
    $project['author'] = $project['authorsname'];

    $project['n_completed_pages'] = 
        ($project['n_pages'] - $project['n_available_pages']);

    $matches = array();
    preg_match('/(.).+(.): (.+)/',$project['state'],$matches);
    @$project['short_state'] = $matches[1] . $matches[2];

    $search = array("Available","Waiting for Release","Unavailable");
    $replace = array("","*","-Unavail");

    @$project['short_state'] = $project['short_state'] . 
        str_replace($search,$replace,$matches[3]);

    $project['uri'] = $project['url'] = "http://www.pgdp.net/c/project.php?" .
        "id=$project[projectid]&expected_state=$project[raw_state]";

    $project['link']="<a href='$project[uri]' class='extiw'>$project[title]</a>";

    unset($project['clearance']);    // email
    unset($project['comments']);     // long
    unset($project['postcomments']); // long

    if (empty($project['checkedoutby']))
    	$project['checkedoutby'] = '(none)';

    foreach ($project as $a => $b)
    {
    	$project[$a] = iconv('ISO-8859-1','UTF-8',$b);
    }

    if (isset ($argv['summary']) || empty($input))
    {
        $output = "<table class='projectinfo plainlinks'>".
        " <tr><td class='pi_a'>Title</td><td>$project[link]</td></tr>".
        " <tr><td class='pi_a'>Author</td><td>$project[authorsname]</td></tr>".
        " <tr><td class='pi_a'>State</td><td>$project[state]</td></tr>".
        " <tr><td class='pi_a'><a href='/wiki/PM'>PM</a>/<a href='/wiki/PPer'>".
        "PPer</a> </td><td>$project[username]/$project[checkedoutby]</td></tr>";

        if (substr($project['raw_state'],-5) == 'avail')
        	$output .= " <tr><td class='pi_a'>Pages (left/total)</td>" . 
                "<td>$project[n_available_pages]/$project[n_pages]</td></tr>";

        $output .= "</table>";
    }
    else
    {
	$intermediate = $parser->parse( $input, $parser->mTitle, $parser->mOptions, false, false );
	$output = $intermediate->getText();
    	foreach ($project as $field => $value)
    	{
    		$output = str_replace('%'.$field.'%',$value,$output);
    	}
    }

    return $output;
}

?>
