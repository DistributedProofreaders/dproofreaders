<?
$relPath='../pinc/';
include_once($relPath.'v_site.inc');
include_once($relPath.'pg.inc');
include_once($relPath.'connect.inc');
include_once($relPath.'theme.inc');
include_once($relPath.'site_news.inc');
new dbConnect();
$no_stats=1;
theme('FAQ Central','header');
?>

<h1>FAQ Central</h1>
<h3 align="right">Version 1.8, released August 17, 2004 </h3>


<p>This page contains links to all the Documentation and FAQ (Frequently Asked Questions)
   files about the Distributed Proofreaders website.</p>

<?
show_site_news_for_page("faq_central.php");
random_news_item_for_page("faq_central.php");
?>

<table border="0" cellspacing="0" width="100%">
  <tbody>

    <tr>
      <td bgcolor="silver">&nbsp;</td>
    </tr>
  </tbody>
</table>

<dl compact>
  <dt><a href="ProoferFAQ.php">Beginning Proofreader's FAQ </a>
  <dd>Introduction to the site, general overview, beginner's questions.

  <dt><a href="../quiz/start.php">Proofreading Quiz &amp; Tutorial </a>
  <dd>Try the proofreading quiz and tutorial. It is a great walk through the basic Guidelines for beginners and an excellent refresher for old hands.

  <dt><a href="summary.pdf">Handy Proofreading Guide</a>

  <dd>(aka "Summary Guidelines") A printable (.pdf) two-page summary of the most commonly needed
      proofreading standards from the Proofreading Guidelines, done as one big example! You may need to download and install a .pdf reader to print or view the summary. You can get one free from Adobe&reg; <a href="http://www.adobe.com/products/acrobat/readstep2.html">here</a>.

  <dt><a href="document.php">Proofreading Guidelines</a>
  <dd>The full details of the guidelines we use for proofreading documents.

  <dt><a href="prooffacehelp.php?i_type=0">Standard Proofreading Interface Help</a>
  <dd>A help-file for the Standard Proofreading Interface.

  <dt><a href="prooffacehelp.php?i_type=1">Enhanced Proofreading Interface Help</a>
  <dd>A help-file for the Enhanced Proofreading Interface.

  <dt><a href="font_sample.php">DP Custom Proofreading Font Page</a>
  <dd>Home of the DP Custom Proofreading Font. Font Samples and download and installation instructions.

  <dt><a href="<? echo $code_url ?>/tools/proofers/for_mentors.php">Mentors Page</a>
  <dd>A page detailing currently available mentor projects.

  <dt><a href="<? echo $forums_url ?>/faq.php">Forums FAQ</a>
  <dd>Information on using the Forum software (our "bulletin board" system for
      communication between all the people working on a book: the proofreaders, the
      Project Manager, and the Post-Processor.  This is the place to ask your questions
      about a book.)

  <dt> &nbsp;
  <dd> &nbsp;

  <dt><a href="pm-faq.php">Project Manager's FAQ</a>
  <dd>Information for new or aspiring Project Managers.  (Project Managers are people who
      manage the progress of a particular project ("book") through this site.)

  <dt><a href="scan/submitting.php">Content Provider's FAQ</a>
  <dd>Overview for people who want to contribute material to be proofread on this site.

  <dt><a href="scan/scanfaq.php">Scanning FAQ</a>
  <dd>Basic information on scanners and how to use them, based on our experiences.

  <dt><a href="post_proof.php">Post-Processing FAQ</a>
  <dd>Information for new and aspiring Post-Processors.  (Post-Processors are people who do
      all the processing  of a particular project ("book") after it has been proofread
      on this site (combining all the pages, making them consistent, fixing problems,
      and submitting it to the Project Gutenberg archive.)

  <dt> &nbsp;
  <dd> &nbsp;

  <dt><a href="<? echo $code_url ?>/tasks.php">Task Center</a>
  <dd>Here you will find a list of feature requests and bugs. You may add tasks after searching to see that the issue isn't already there.

  <dt><a href="http://www.pgdp.net/mailman/listinfo">Mailing Lists for Users</a>
  <dd>Here you will find information about the email lists DP has available for users.

  <dt> &nbsp;
  <dd> &nbsp;

  <dt><a href="<? echo $PG_faq_url; ?>">Project Gutenberg FAQ</a>
  <dd>The <i>massive</i> FAQ from our parent site,
      <a href="<? echo $PG_home_url; ?>">Project Gutenberg</a>.

  <dt><a href="privacy.php">DP Privacy Policy</a>
  <dd>A link to the Distributed Proofreading Privacy Policy that you saw when you
      first registered at this site.


</dl>
<table border='0' cellpadding='0' cellspacing='0' width='100%' bgcolor='silver'>
<tr><td width='10'>&nbsp;</td>
    <td width='100%' align="center"><font face='verdana, helvetica, sans-serif' size='1'>
        Return to:
        <a href="..">Distributed Proofreaders home page</a>,
        &nbsp;&nbsp;&nbsp;
        <a href="faq_central.php">DP FAQ Central page</a>,
        &nbsp;&nbsp;&nbsp;
        <a href="<? echo $PG_home_url; ?>">Project Gutenberg home page</a>.
        </font>

    </td>
</tr>
</table>

<?
theme('','footer');
?>
