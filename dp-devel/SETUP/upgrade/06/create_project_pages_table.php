<?PHP
$relPath = './pinc/';
include_once($relPath.'connect.inc');
include_once($relPath.'RoundDescriptor.inc');
new dbConnect();

$items_for_rounds = "";
for ($rn = 1; $rn <= MAX_NUM_PAGE_EDITING_ROUNDS; $rn++ )
{
    $prd = get_PRD_for_round($rn);
    $items_for_rounds .= "
        {$prd->time_column_name}   int( 20 )     NOT NULL default '0' KEY,
        {$prd->user_column_name}   varchar( 25 ) NOT NULL default ''  KEY,
        {$prd->text_column_name}   longtext      NOT NULL,
        KEY {$prd->time_column_name} ( {$prd->time_column_name} ),
        KEY {$prd->user_column_name} ( {$prd->user_column_name} ),
    ";
}

$sql = "
    CREATE TABLE project_pages_test (
        projectid     varchar( 25 ) NOT NULL default '',
        fileid        varchar( 20 ) NOT NULL default '',
        image         varchar( 8 )  NOT NULL default '',
        master_text   longtext      NOT NULL,
        $items_for_rounds
        state         varchar( 50 ) NOT NULL default '',
        b_user        varchar( 25 ) NOT NULL default '',
        b_code        int( 1 )      NOT NULL default '0',
        metadata      set( 'frontmatter', 'backmatter', 'division', 'verse', 'poetry', 'letter', 'toc', 'footnote', 'sidenote', 'epigraph', 'table', 'list', 'math', 'drawing', 'badscan', 'blank', 'illustration', 'missing', 'drawing', 'math' ) NOT NULL default '',
        orig_page_num varchar( 6 ) NOT NULL default '',

        PRIMARY KEY ( projectid, fileid )
    )
    TYPE = MYISAM
";

mysql_query($sql) or die(mysql_error());
echo "<center>Table project_pages created successfully.</center>";

?>
