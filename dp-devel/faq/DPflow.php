<?
$relPath='../pinc/';
include($relPath.'v_site.inc');
include($relPath.'connect.inc');
include($relPath.'theme.inc');
new dbConnect();
$no_stats=1;
theme('Workflow Diagram','header');
?>

<h1>Distributed Proofreaders General Workflow Diagram</h1>

<b>1. </b>Project Manager acquires and pre-processes material.
<p>
<b>2. </b>Project Manager loads project on DP web site and Proofers give it two rounds of proofreading
<p>
<b>3. </b>Project Manager performs final assembly/proofreading on output from DP website and submits e-text to Project Gutenberg for posting.
<p>
<p>
<img SRC="DPflow.gif" height=461 width=640>

<p>
<a href="ProoferFAQ.php">Back</a> to Proofreaders FAQ.

<?
theme('','footer');
?>
