<?php
$relPath='../pinc/';
include_once($relPath.'base.inc');
include_once($relPath.'theme.inc');
include_once($relPath.'metarefresh.inc');
include_once($relPath.'misc.inc'); // undo_all_magic_quotes()

undo_all_magic_quotes();

# SITE-SPECIFIC
# Redirect users to this document in the wiki
if(strpos($code_url, '://www.pgdp.'))
{
    $url = 'http://www.pgdp.org/wiki/DP_Official_Documentation:General/General_Workflow_Diagram';
    metarefresh(0, $url);
}

output_header('Workflow Diagram', NO_STATSBAR);
?>

<h1>Distributed Proofreaders General Workflow Diagram</h1>

<img SRC="project_workflow.png"></p><p>
<a href="ProoferFAQ.php">Back</a> to Proofreaders FAQ.</p>

<?php
// vim: sw=4 ts=4 expandtab
