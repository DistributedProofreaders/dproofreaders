<html>
<head>
    <title>DP Catalog</title>
</head>
<body>
<h1>Distributed Proofreaders Catalog</h1>

<p>
This page lists all DP projects, grouped by state.
(We could have other ways of grouping/ordering.
We could also have a search interface.)
Click on a project title to go to its home page.
</p>

<?PHP
$relPath = '../../pinc/';
include($relPath.'dp_main.inc');
include($relPath.'project_states.inc');
include($relPath.'f_project_states.inc');

$proj_states=array(
    PROJ_NEW,
    PROJ_PROOF_FIRST_UNAVAILABLE,
    PROJ_PROOF_FIRST_WAITING_FOR_RELEASE,
    PROJ_PROOF_FIRST_AVAILABLE,
    PROJ_PROOF_FIRST_BAD_PROJECT,
    PROJ_PROOF_FIRST_VERIFY,
    PROJ_PROOF_FIRST_COMPLETE,

    PROJ_PROOF_SECOND_UNAVAILABLE,
    PROJ_PROOF_SECOND_WAITING_FOR_RELEASE,
    PROJ_PROOF_SECOND_AVAILABLE,
    PROJ_PROOF_SECOND_BAD_PROJECT,
    PROJ_PROOF_SECOND_VERIFY,
    PROJ_PROOF_SECOND_COMPLETE,

    PROJ_POST_UNAVAILABLE,
    PROJ_POST_AVAILABLE,
    PROJ_POST_CHECKED_OUT,
    PROJ_POST_VERIFY,
    PROJ_POST_VERIFYING,
    PROJ_POST_COMPLETE,

    PROJ_SUBMIT_PG_UNAVAILABLE,
    PROJ_SUBMIT_PG_AVAILABLE,
    PROJ_SUBMIT_PG_POSTING,
    PROJ_SUBMIT_PG_POSTED,

    PROJ_DELETE,
    PROJ_COMPLETE );

foreach ( $proj_states as $proj_state )
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
            echo "<a href='project_home.php?project=$projectid'>\"$nameofwork\"</a> by $authorsname (in $language)<br>\n";
        }
    }
}

?>

</body>
</html>
