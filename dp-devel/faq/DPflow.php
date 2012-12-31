<?php
$relPath='../pinc/';
include_once($relPath.'base.inc');
include_once($relPath.'theme.inc');
include_once($relPath.'misc.inc'); // undo_all_magic_quotes()

undo_all_magic_quotes();

output_header('Workflow Diagram', NO_STATSBAR);
?>

<h1>Distributed Proofreaders General Workflow Diagram</h1>

<img SRC="project_workflow.png"></p><p>
<a href="ProoferFAQ.php">Back</a> to Proofreaders FAQ.</p>

<?php
// vim: sw=4 ts=4 expandtab
