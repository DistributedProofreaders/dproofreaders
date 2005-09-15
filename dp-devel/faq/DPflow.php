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

<p><b>1. </b>Project Manager acquires and pre-processes material.</p>
<p><b>2. </b>Project Manager loads project on DP web site and 
          proofreaders give it two rounds of proofreading. Formatters give it two rounds of formatting.</p>
<p><b>3. </b>The Post-Processor assembles the book and uploads it to DP for verification.</p>
<p><b>4. </b>A Post-Processing Verifier checks the uploaded book and posts it to PG.</p>
<p>
<img SRC="DPflow.gif" height=461 width=640></p><p>
<a href="ProoferFAQ.php">Back</a> to Proofreaders FAQ.</p>

<?
theme('','footer');
?>
