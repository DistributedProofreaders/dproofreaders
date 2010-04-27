<?php

$relPath = '../../pinc/';

include_once($relPath.'dp_main.inc');
include_once($relPath.'project_states.inc');
include_once($relPath.'slim_header.inc');
include_once($relPath.'site_vars.php');

slim_header("$site_abbreviation Catalog");

?>

<h1><?php echo $site_name; ?> Catalog</h1>



<p>

This page lists all projects, grouped by state.

(We could have other ways of grouping/ordering.

We could also have a search interface.)

Click on a project title to go to its home page.

</p>



<?php

foreach ( $PROJECT_STATES_IN_ORDER as $proj_state )

{

    echo "<h3>";

    echo project_states_text($proj_state);

    echo ":</h3>\n";



    $rows = mysql_query( "SELECT projectid, nameofwork, authorsname, language FROM projects WHERE state='$proj_state'") or die(mysql_error());



    if ( mysql_numrows($rows) == 0 )

    {

        echo "none<br>\n";

    }

    else

    {

        while( $row = mysql_fetch_array( $rows ) )

        {

            $projectid   = $row['projectid'];

            $nameofwork  = $row['nameofwork'];

            $authorsname = $row['authorsname'];

            $language    = $row['language'];

            echo "\"<a href='$code_url/project.php?id=$projectid'>" . htmlspecialchars($nameofwork) . "</a>\" by " . htmlspecialchars($authorsname) . " (in " . htmlspecialchars($language) .")<br>\n";

        }

    }

}



?>



</body>

</html>

