<?php
$relPath='../pinc/';
include_once($relPath.'base.inc');
include_once($relPath.'pg.inc');
include_once($relPath.'theme.inc');
include_once($relPath.'site_news.inc');
include_once($relPath.'misc.inc'); // undo_all_magic_quotes()

undo_all_magic_quotes();

$no_stats=1;
$theme_args = array("css_data" => "
table.faqs td {font-family: Tahoma, sans-serif;}
td.faqheader {background: $theme[color_headerbar_bg];
              color: $theme[color_headerbar_font];
              font-weight: bold;
              vertical-align: middle;}
table#faqtable table {align: left;}
");
theme('FAQ Central','header', $theme_args);
?>

<h1>FAQ Central</h1>

<p>This page contains links to all the Documentation and FAQ (Frequently Asked Questions)
   files about the <?php echo "$site_name"; ?> website.</p>
<p><small>Updated August 2, 2008</small></p>

<?php
show_news_for_page("FAQ");
?>

<table width='100%' border='0' cellspacing='0' cellpadding='0' class='faqs' id='faqtable'>
<tr>
<td width='5'>&nbsp;</td>
  <td width='49%' valign='top'>
  <table width='100%' cellpadding='4'>
  <tr>
   <td class='faqheader'>&nbsp;Introductory Information</td>
  </tr>
  <tr>
    <td>
   <p><a href="ProoferFAQ.php">Beginning Proofreader's FAQ </a><br>
   <font size="-1">Introduction to the site, general overview, beginner's questions.</font></p>

  <p><a href="longabsent.php">Welcome Back, Alumni</a><br>
  <font size="-1">An overview of site changes from the last few years,
  including the distinction between proofreading and formatting.</font></p>

  <p><a href="InterfaceDiagram.png">Proofreading Interface Diagram</a><br>
  <font size="-1">An overview flowchart which outlines the operation of the
  proofreading interface.</font></p>

  <p><a href="privacy.php"><?php echo "$site_abbreviation"; ?> Privacy Policy</a><br>
  <font size="-1">The <?php echo "$site_name"; ?> Privacy Policy
   that you saw when you first registered at this site.</font></p>
    </td>
  </tr>
 </table>
 <br />
  <table width='100%' cellpadding='4'>
  <tr>
   <td class='faqheader'>&nbsp;Proofreading</td>
  </tr>
  <tr>
    <td>
  <p><a href="proofreading_guidelines.php">Proofreading Guidelines</a><br>
  <span style='font-size: smaller; color: #335;'>Also available in 
     <a href='proofreading_guidelines_francaises.php'>French</a>,
     <a href='proofreading_guidelines_portuguese.php'>Portuguese</a>,
     <a href='proofreading_guidelines_spanish.php'>Spanish</a>,
     <a href='proofreading_guidelines_dutch.php'>Dutch</a>,
     <a href='proofreading_guidelines_german.php'>German</a>, and
     <a href='proofreading_guidelines_italian.php'>Italian</a>.</span><br>
  <font size="-1">The details of the guidelines we use for proofreading documents.</font></p>

  <p><a href="proofing_summary.pdf">Proofreading Summary</a><br>
  <span style='font-size: smaller; color: #335;'>Also available in 
     <a href='proofing_summary_french.pdf'>French</a>.</span><br>
  <font size="-1">Printable (.pdf) two-page summary of the most commonly needed
  proofreading standards from the Proofreading Guidelines, done as a big example! </font></p>

  <p><a href="../quiz/start.php?show_only=PQ">Proofreading Quizzes &amp; Tutorials</a><br>
  <font size="-1">Try the proofreading quizzes and tutorials.
  They are a great walk through the basic Guidelines for beginners and an excellent refresher for old hands.</font></p>

    </td>
  </tr>
 </table>
 <br />
  <table width='100%' cellpadding='4'>
  <tr>
   <td class='faqheader'>&nbsp;Formatting</td>
  </tr>
    <tr>
     <td>
  <p><a href="document.php">Formatting Guidelines</a><br>
  <span style='font-size: smaller; color: #335;'>Also available in 
     <a href='formatting_guidelines_francaises.php'>French</a>,
     <a href='formatting_guidelines_portuguese.php'>Portuguese</a>,
     <a href='formatting_guidelines_dutch.php'>Dutch</a>,
     <a href='formatting_guidelines_german.php'>German</a>, and
     <a href='formatting_guidelines_italian.php'>Italian</a>.</span><br>
  <font size="-1">The full details of the guidelines we use for formatting documents.</font></p>

  <p><a href="formatting_summary.pdf">Formatting Summary</a><br>
  <font size="-1">Printable (.pdf) two-page summary of the most commonly needed
  formatting standards from the Formatting Guidelines, done as a big example! </font></p>

  <p><a href="../quiz/start.php?show_only=FQ">Formatting Quiz</a><br>
  <font size="-1">Try the formatting quiz.
  It is a great walk through the basic Guidelines for beginners and an excellent refresher for old hands.</font></p>

  </td>
  </tr>
 </table>
 <br />
  <table width='100%' cellpadding='4'>
  <tr>
   <td class='faqheader'>&nbsp;<?php echo "$site_abbreviation"; ?> Tools</td>
  </tr>
  
  <!-- As well as the font, this section should also hold any PP 
       tools, &c. that are stored on site -->

  <tr>
  <td>
  <p><a href="font_sample.php">DP Custom Proofreading Font</a><br>
  <font size="-1">Sample and download the custom DP font.</font></p>

  <p><a href="wordcheck-faq.php">WordCheck FAQ</a><br>
  <font size="-1">Information on the WordCheck engine and interface.</font></p>

  <p><a href="prooffacehelp.php?i_type=0">Standard Proofreading Interface Help</a><br>
  <font size="-1">Help for the Standard Proofreading Interface.</font></p>

  <p><a href="prooffacehelp.php?i_type=1">Enhanced Proofreading Interface Help</a><br>
  <font size="-1">Help for the Enhanced Proofreading Interface.</font></p>
  </td>
  </tr>
  </table>

</td>
<td width='5'>&nbsp;</td>
<td width='49%' valign='top' align='left'>



 <table width='100%' cellpadding='4'>
  <tr>
   <td class='faqheader'>&nbsp;Creating and Managing Projects</td>
  </tr>
  <tr>
    <td>

  <p><a href="pm-faq.php">Project Managers' FAQ</a><br>
  <font size="-1">Information for new or aspiring Project Managers.  (Project Managers are people who
      manage the progress of a particular project ("book") through this site.)</font></p>

  <p><a href="cp.php">Content Providers' FAQ</a><br>
  <font size="-1">Overview for people who want to contribute material to be proofread on this site.</font></p>

  <p><a href="scanning.php">Scanning FAQ</a><br>
  <font size="-1">Basic information on scanners and how to use them, based on our experiences.</font></p>

  </td>
  </tr>
 </table>
 <br />
 <table width='100%' cellpadding='4'>
  <tr>
   <td class='faqheader'>&nbsp;Mentoring</td>
  </tr>
  <tr>
    <td>
  <p><a href="<?php echo $code_url; ?>/tools/proofers/for_mentors.php">Mentors' Page</a><br>
  <font size="-1">A page detailing currently available mentor projects.</font></p>

  </tr>
 </table>
<br />
  <table width='100%' cellpadding='4'>
  <tr>
   <td class='faqheader'>&nbsp;Post-Processing and Verification</td>
  </tr>
  <tr>
    <td>

  <p><a href="post_proof.php">Post-Processing FAQ</a><br>
  <font size="-1">Information for new and aspiring Post-Processors.  (Post-Processors
      process a particular project after it has been proofread and formatted
      on this site (combining all the pages, making them consistent, fixing problems,
      and submitting it to Project Gutenberg).)</font></p>

  <p><a href="ppv.php">Post-Processing Verification Guidelines</a><br>
  <font size="-1">Information for Post-Processing Verifiers.</font></p>
  </tr>
 </table>
 
 <br />
 <table width='100%' cellpadding='4'>
  <tr>
   <td class='faqheader'>&nbsp;Suggestions, Bugs, and Development</td>
  </tr>
  <tr>
    <td>

  <p><a href="<?php echo $code_url; ?>/tasks.php">Task Center</a><br>
  <font size="-1">Here you will find a list of feature requests and bugs. 
  You may add tasks after searching to see that the issue isn't already there.</font></p>

  <p><a href="dev_process.php">Development Process</a><br>
  <font size="-1">Information and guidelines for developers.</font></p>
  </tr>
 </table>

<br />
  <table width='100%' cellpadding='4'>
  <tr>
   <td class='faqheader'>&nbsp;Mailing Lists</td>
  </tr>
  <tr>
  <td>
  <p><a href="http://www.pgdp.net/mailman/listinfo">Mailing Lists for Users</a><br>
  <font size="-1">Information about the email lists <?php echo "$site_abbreviation"; ?> has available for users.</font></p>
  </td>
  </tr>
 </table>

<br /><br />
  <table width='100%' cellpadding='4'>
  <tr>
   <td class='faqheader'>&nbsp;Project Gutenberg</td>
  </tr>
  <tr>
  <td>
  <p><a href="<?php echo $PG_faq_url; ?>">Project Gutenberg FAQ</a><br>
  <font size="-1">The <i>massive</i> FAQ from 
      <a href="<?php echo $PG_home_url; ?>">Project Gutenberg</a>.</font></p>
  </td>
  </tr>
 </table>
</td>
<td width='5'>&nbsp;</td>
</tr>
</table>
<br><br>

<table border='0' cellpadding='0' cellspacing='0' width='100%' bgcolor='silver'>
<tr><td width='10'>&nbsp;</td>
    <td width='100%' align="center"><font face='verdana, helvetica, sans-serif' size='1'>
        Return to:
        <a href=".."><?php echo "$site_name"; ?> home page</a>,
        &nbsp;&nbsp;&nbsp;
        <a href="faq_central.php"><?php echo "$site_abbreviation"; ?> FAQ Central page</a>,
        &nbsp;&nbsp;&nbsp;
        <a href="<?php echo $PG_home_url; ?>">Project Gutenberg home page</a>.
        </font>

    </td>
</tr>
</table>

<?php
theme('','footer');
// vim: sw=4 ts=4 expandtab
