<?
$relPath="./../../pinc/";
include($relPath.'echo_project_info.inc');
include($relPath.'theme.inc');

theme('Project Information', 'header');

/* $_GET $project, $proofstate, $proofing */

    echo_project_info( $project, 'proj_post', 0 );
    echo "<BR>";

theme('', 'footer');

?>
