<?
$relPath='../pinc/';
include($relPath.'v_site.inc');
include($relPath.'connect.inc');
include($relPath.'theme.inc');
new dbConnect();
theme('FAQ Central','header');
?>

<h1>FAQ Central</h1>
<h3 align="right">Version 1.0, released Apr 15, 2003 </h3>

<p>This page contains links to all the Documentation and FAQ (Frequently Asked Questions)
   files about the Distributed Proofreaders website.</p>
<table border="0" cellspacing="0" width="100%">
  <tbody>
    <tr>
      <td bgcolor="silver">&nbsp;</td>
    </tr>
  </tbody>
</table>

<dl compact>
  <dt><a href="ProoferFAQ.php">Beginning Proofer's FAQ </a>
  <dd>Introduction to the site, general overview, beginner's questions.

  <dt><a href="summary.pdf">Guidelines Summary</a>
  <dd>(aka "Handy Guide") A printable two-page summary of the most commonly needed
      proofing standards from the Document Guidelines, done as one big example!

  <dt><a href="document.php">Document Guidelines</a>
  <dd>Details of the guidelines we use for proofing.

  <dt><a href="<? echo $forums_url ?>/faq.php">Forums FAQ</a>
  <dd>Information on using the Forum software (our "bulletin board" system for
      communication between all the people working on a book: the proofreaders, the
      Project Manager, and the Post-Processor.  The place to ask your questions about a
      book.)

  <dt> &nbsp;
  <dd> &nbsp;

  <dt><a href="pm-faq.php">Project Manager's FAQ</a>
  <dd>Information for new or aspiring Project Managers.  (Project Managers are people who
      manage the progress of a particular project ("book") through this site.)

  <dt><a href="scan/submitting.php">Submitting Material FAQ</a>
  <dd>Overview for people who want to contribute material to be proofed on this site.

  <dt><a href="scan/scanfaq.php">Scanning FAQ</a>
  <dd>Basic information on scanners and how to use them, based on our experiences.

  <dt> &nbsp;
  <dd> &nbsp;

  <dt><a href="post_proof.php">Post-Proofing FAQ</a>
  <dd>Information for new and aspiring Post-Proofers.  (Post-Proofers are people who do
      all the processing  of a particular project ("book") after it has been proofread
      on this site (combining all the pages, making them consistent, fixing problems,
      and submitting it to the Project Gutenberg archive.)

  <dt><a href =" http://beryl.ils.unc.edu/~jtinsley/gutfaq.htm">Project Gutenberg FAQ</a>
  <dd>The <i>massive</i> FAQ from our parent site,
      <a href="http://www.gutenberg.net/">Project Gutenberg</a>
      (draft of new version).
      (This url is "unofficial" and "temporary" -- it points to Jim Tinsley's own site
      rather than anywhere on the official PG site -- but useful anyway.)

</dl>
<?
theme('','footer');
?>
