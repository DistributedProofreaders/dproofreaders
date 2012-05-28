<?php
$relPath='../pinc/';
include_once($relPath.'base.inc');
include_once($relPath.'theme.inc');
include_once($relPath.'misc.inc'); // undo_all_magic_quotes()

undo_all_magic_quotes();

$no_stats=1;
theme('Workflow Diagram','header');
?>

<h1>Distributed Proofreaders General Workflow Diagram</h1>

<img SRC="project_workflow.png"></p><p>
<a href="ProoferFAQ.php">Back</a> to Proofreaders FAQ.</p>

<?php
theme('','footer');
// vim: sw=4 ts=4 expandtab
