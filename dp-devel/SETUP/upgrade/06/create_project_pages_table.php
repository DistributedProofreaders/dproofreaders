<?PHP
$relPath = './pinc/';
include_once($relPath.'connect.inc');
new dbConnect();

$sql = "
    CREATE TABLE project_pages (
        projectid     varchar( 25 ) NOT NULL default '',
        fileid        varchar( 20 ) NOT NULL default '',
        image         varchar( 8 )  NOT NULL default '',
        master_text   longtext      NOT NULL,
        round1_text   longtext      NOT NULL,
        round2_text   longtext      NOT NULL,
        round1_user   varchar( 25 ) NOT NULL default '',
        round2_user   varchar( 25 ) NOT NULL default '',
        round1_time   int( 20 )     NOT NULL default '0',
        round2_time   int( 20 )     NOT NULL default '0',
        state         varchar( 50 ) NOT NULL default '',
        b_user        varchar( 25 ) NOT NULL default '',
        b_code        int( 1 )      NOT NULL default '0',
        metadata      set( 'frontmatter', 'backmatter', 'division', 'verse', 'poetry', 'letter', 'toc', 'footnote', 'sidenote', 'epigraph', 'table', 'list', 'math', 'drawing', 'badscan', 'blank', 'illustration', 'missing', 'drawing', 'math' ) NOT NULL default '',
        orig_page_num varchar( 6 ) NOT NULL default '',

        PRIMARY KEY ( projectid, fileid ),
        KEY round1_user ( round1_user ),
        KEY round2_user ( round2_user ),
        KEY round1_time ( round1_time ),
        KEY round2_time ( round2_time ) 
    )
    TYPE = MYISAM
";

mysql_query($sql) or die(mysql_error());
echo "<center>Table project_pages created successfully.</center>";

?>
