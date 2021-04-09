<?php
$relPath='../pinc/';
include_once($relPath.'base.inc');
include_once($relPath.'faq.inc');
include_once($relPath.'theme.inc');

maybe_redirect_to_external_faq();

output_header('Workflow Diagram', NO_STATSBAR);
?>

<h1>Distributed Proofreaders General Workflow Diagram</h1>

<img SRC="project_workflow.png"></p><p>
<a href="ProoferFAQ.php">Back</a> to Proofreaders FAQ.</p>

<?php
