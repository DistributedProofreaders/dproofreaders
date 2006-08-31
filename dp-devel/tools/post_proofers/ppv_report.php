<?
$relPath="../../pinc/";
include_once($relPath.'site_vars.php');
include_once($relPath.'dp_main.inc');
include_once($relPath.'theme.inc');
include_once($relPath.'maybe_mail.inc');
include_once($relPath.'Project.inc'); //user_can_work_in_stage()
include_once($relPath.'projectinfo.inc');

$theme_args['js_data'] = "
function set_html(sw)
{
document.ppvform.html_desc.disabled = sw;
document.ppvform.html_markup.disabled = sw;
document.ppvform.html_css.disabled = sw;
document.ppvform.html_links.disabled = sw;
document.ppvform.html_image_size.disabled = sw;
document.ppvform.html_image_links.disabled = sw;
document.ppvform.html_header.disabled = sw;
return true;
}

function grow_textarea(textarea_id)
{
    textarea = document.getElementById(textarea_id);
    textarea.rows = textarea.rows+2;
}

function shrink_textarea(textarea_id)
{
    textarea = document.getElementById(textarea_id);
    if (textarea.rows > 2)
    {
        textarea.rows = textarea.rows-2;
    }
}\n";

$theme_args['css_data'] = "
div.shrinker {float: right;}
div.shrinker a {
    font-size:200%; 
	font-weight: 900;
	text-decoration: none!important;
	color: #888;
	cursor: pointer;}
";

theme(_('Post-Processing Verification Reporting'),'header', $theme_args);


if (empty($_REQUEST['project'])) {
   	echo _("No project specified. Supply a 'project' parameter.");
   	theme('','footer');
   	die;
}


// To make PPVer collaboration easier, allow any PPVer to fill in the report card.
// (The link is still only shown to the PPVer with the project checked-out.)
// All report cards are sent to the PPVers' list, signed by the person filling
// out the card, so a mischievous PPVer couldn't get away with anything, anyway.
if (!user_can_work_in_stage($pguser, 'PPV')) {
	echo _("You're not recorded as a Post-Processing Verifier.
            If you feel this is an error, please contact a Site Administrator.");
  theme('','footer');
 	exit();
}

$projectid = mysql_real_escape_string($_REQUEST['project']);

$project = mysql_fetch_object(mysql_query("SELECT * FROM projects WHERE projectid = '$projectid'"));
$ppver = mysql_fetch_object(mysql_query("SELECT * FROM users WHERE username = '$pguser'"));
$pper = mysql_fetch_object(mysql_query("SELECT * FROM users WHERE username = '$project->postproofer'"));

$nameofwork = $project->nameofwork;
$authorsname = $project->authorsname;
$language = $project->language;
$difficulty_level = $project->difficulty;
$pages = Project_getNumPages($projectid);
$subdate = date("jS of F, Y");




if ($_GET['send']) {

// The Spanish PPer shouldn't get a French email because that's the PPVer's
// language, so temporarily change the current locale.
setlocale(LC_ALL,$pper->u_intlang);
$ppbita = _("Hello %1\$s,

This is a message that your Post-Processing Verifier, %2\$s,
requested you receive from the Distributed Proofreaders site.

Thank you for your Post-Processing work on \"%3\$s\".
A copy of the report card submitted by %2\$s is below.
If you have any questions about it, please contact him or her.");
$ppbit = sprintf($ppbita , $pper->username, $ppver->username, $project->nameofwork);

setlocale(LC_ALL,$ppver->u_intlang);
$ppvbita = _("Hello %1\$s,

This is a message that you requested you receive from the Distributed
Proofreaders site.

Thank you for your Post-Processing Verification work on \"%2\$s\".
A copy of the report you submitted is below. If you see an important error,
please email %3\$s.");

$ppvbit = sprintf($ppvbita, $ppver->username, $nameofwork, $general_help_email_addr);

$signoff = "\n\n"._("Thank you!")."\nDistributed Proofreaders";

$additional_headers = "";

// Wrap any long input from textareas.
$_POST['reason_returned'] = wordwrap($_POST['reason_returned'], 78, "\n    ");
$_POST['general_comments'] = wordwrap($_POST['general_comments'], 78, "\n    ");
$_POST['html_desc'] = wordwrap($_POST['html_desc'], 78, "\n    ");
$_POST['unusual_formatting'] = wordwrap($_POST['unusual_formatting'], 78, "\n    ");
$_POST['promotions'] = wordwrap($_POST['promotions'], 78, "\n    ");

if(!empty($_POST['promotions']))
     $promotions =  "*    *    *\nPromotions comments:\n  $_POST[promotions]\n*    *    *\n";

$reportcard = "
\n\nPPV Report Card for $pper->username


Project Information

  projectID: $projectid
  Title: $nameofwork
  Author: $authorsname
  Language: $language
  Proofreading Difficulty: $difficulty_level
  Number of pages: $pages
  Post-processed by: $pper->username
  Verified by: $ppver->username
  Verified on: $subdate

General Post-Processing Information

  PPing Difficulty: $_POST[difficulty_level_pp]
  Overall evaluation of PPer's work: $_POST[eval]";

if(!empty($_POST['general_comments']))
    $reportcard .= "\n  General comments:  \n    $_POST[general_comments]";
if(!empty($_POST['reason_returned']))
    $reportcard .=  "\n\n  Reason project was returned to PPer: \n    $_POST[reason_returned]";

if ($_POST['html_sub'] == "yes") {
  $reportcard .= "\n\n\nHTML Version: submitted.\n\n  Issues with HTML version, if any:";
  if($_POST['html_markup'])      $reportcard .= "\n    Markup";
  if($_POST['html_css'])         $reportcard .= "\n    CSS";
  if($_POST['html_links'])       $reportcard .= "\n    Internal links";
  if($_POST['html_image_links']) $reportcard .= "\n    Links to images";
  if($_POST['html_image_size'])  $reportcard .= "\n    Image size/quality";
  if($_POST['html_header'])      $reportcard .= "\n    Header";
  if($_POST['html_desc'])        $reportcard .= "\n\n  General comments on HTML:\n    $_POST[html_desc]";
}
else {
    $reportcard .= "\n\nNo HTML version submitted\n";
}

$reportcard .= "\n\nComplexity Details\n";
$reportcard .= "  Present in the text:";

if($_POST['tables'])        $reportcard .= "\n    Tables";
if($_POST['poetry'])        $reportcard .= "\n    Poetry";
if($_POST['footnotes'])     $reportcard .= "\n    Footnotes";
if($_POST['sidenotes'])     $reportcard .= "\n    Sidenotes";
if($_POST['index'])         $reportcard .= "\n    Index";
if($_POST['blockquotes'])   $reportcard .= "\n    Blockquotes";
if($_POST['multilang'])     $reportcard .= "\n    Multiple languages";
if($_POST['illustrations']) $reportcard .= "\n    $_POST[illus_num] Illustrations";

if(!empty($_POST['unusual_formatting']))
    $reportcard .= "\n\n  Unusual formatting:\n    $_POST[unusual_formatting]";

if ($_POST['e_spellcheck_num'] || $_POST['e_hyph_num'] || $_POST['e_gutcheck_num'] ||
    $_POST['e_other_num'] || $_POST['e_comma_num'] ||  $_POST['e_html_num'])
{
    $reportcard .= "\n\nApproximate error numbers:";
    if($_POST['e_comma_num'])      $reportcard .= "\n  Comma/period: $_POST[e_comma_num]";
    if($_POST['e_spellcheck_num']) $reportcard .= "\n  Spellcheck: $_POST[e_spellcheck_num]";
    if($_POST['e_hyph_num'])       $reportcard .= "\n  Hyphens: $_POST[e_hyph_num]";
    if($_POST['e_gutcheck_num'])   $reportcard .= "\n  Gutcheck: $_POST[e_gutcheck_num]";
    if($_POST['e_html_num'])       $reportcard .= "\n  HTML: $_POST[e_html_num]";
    if($_POST['e_other_num'])      $reportcard .= "\n  $_POST[other_error_type]: $_POST[e_other_num]";
}


if (get_magic_quotes_gpc())
{
    $reportcard = stripslashes($reportcard);
    $promotions = stripslashes($promotions);
}


if ($_POST['cc_pp'])
{
   	$to = $pper->email;
   	$subject = "DP PP: $nameofwork";
   	$message = $ppbit.$reportcard.$signoff;
    maybe_mail($to, $subject, $message, $additional_headers);
}

if ($_POST['cc_ppv']) {
   	$to = $ppver->email;
   	$subject = "DP PPV: $nameofwork";
   	$message = $ppvbit.$reportcard.$signoff;
    maybe_mail($to, $subject, $message, $additional_headers);
}

$to = $ppv_reporting_email_addr;
$subject = "PPV Report Card - $pper->username ($_POST[eval])";
$message = $promotions.$reportcard.$signoff;
maybe_mail($to, $subject, $message, "From: $ppver->username <$ppver->email>\r\n");
echo ("Thanks for PPVing! <br />
       Return to <a href='../../project.php?id=$projectid'>the Project Page</a>.");
theme('','footer');
exit();
}


else {

function textarea_size_control($id, $br = true)
{
    return ($br?'<br />':'') . "<div class='shrinker'>".
	    "<a onclick='grow_textarea(\"$id\")'>+</a>&nbsp;".
		"<a onclick='shrink_textarea(\"$id\")'>&minus;</a></div>";
}

echo "<br />
      <form action='{$code_url}/tools/post_proofers/ppv_report.php?project=$projectid&send=1'
			 name='ppvform' method='post'>
      <table border='1' id='report_card' style='width: 95%';>

      <tr><td colspan='2' style='text-align: center; font-weight: bold; background: $theme[color_logobar_bg];'>
      "._("Project Information")."</td></tr>
      <tr>
        <td bgcolor='#CCCCCC' style='width: 40%;'><b>"._("Project ID")."</b></td>
        <td><input type='hidden' name='projectid' value='$projectid'>$projectid</td>
      </tr>
      <tr>
        <td bgcolor='#CCCCCC' style='width: 40%;'><b>"._("Name of Work")."</b></td>
        <td>$nameofwork</td>
      </tr>
      <tr>
        <td bgcolor='#CCCCCC' style='width: 40%;'><b>"._("Author's Name")."</b></td>
        <td>$authorsname</td>
      </tr>
      <tr>
        <td bgcolor='#CCCCCC' style='width: 40%;'><b>"._("Language")."</b></td>
        <td>$language</td></tr>\n
      <tr>
        <td bgcolor='#CCCCCC' style='width: 40%;'><b>"._("Difficulty")."</b></td>
        <td>$difficulty_level</td>
      </tr>
      <tr>
        <td bgcolor='#CCCCCC' style='width: 40%;'><b>"._("Pages")."</b></td>
        <td>$pages</td>
      </tr>
      <tr>
        <td bgcolor='#CCCCCC' style='width: 40%;'><b>"._("Post-Processed by")."</b></td>
        <td>$pper->username</td>
      </tr>


      <tr>
        <td colspan='2' style='text-align: center; font-weight: bold; background: $theme[color_logobar_bg];'>
          "._("General Information")."
        </td>
      </tr>
      <tr>
        <td bgcolor='#CCCCCC' style='width: 40%;'><b>"._("Estimated Difficulty to PP")."</b></td>
        <td>
            <label for='difficulty_level_pp_easy'>
			    <input type='radio' name='difficulty_level_pp' id='difficulty_level_pp_easy' value='Easy'>"._("Easy")."&nbsp;&nbsp;&nbsp;&nbsp;</label>
            <label for='difficulty_level_pp_average'>
                <input type='radio' name='difficulty_level_pp' id='difficulty_level_pp_average' value='Average'>"._("Average")."&nbsp;&nbsp;&nbsp;&nbsp;</label>
            <label for='difficulty_level_pp_difficult'>
                <input type='radio' name='difficulty_level_pp' id='difficulty_level_pp_difficult' value='Difficult'>"._("Difficult")."</label>
        </td>
      </tr>
      <tr>
        <td bgcolor='#CCCCCC' style='width: 40%;'><b>"._("Overall evaluation of PPer's work on this project")."</b></td>
        <td>
            <label for='eval_excellent'>
			    <input type='radio' name='eval' id='eval_excellent' value='Excellent'>"._("Excellent")."&nbsp;&nbsp;&nbsp;&nbsp;</label>
            <label for='eval_good'>
			    <input type='radio' name='eval' id='eval_good' value='Good'>"._("Good")."&nbsp;&nbsp;&nbsp;&nbsp;</label>
            <label for='eval_fair'>
			    <input type='radio' name='eval' id='eval_fair' value='Fair'>"._("Fair")."&nbsp;&nbsp;&nbsp;&nbsp;</label>
            <label for='eval_poor'>
			    <input type='radio' name='eval' id='eval_poor' value='Poor'>"._("Poor")."</label>
        </td>
      </tr>
      <tr>
        <td bgcolor='#CCCCCC' style='width: 40%;'>
          <b>"._("If you had to return the project to the PPer for revisions, what was the reason?")."</b>
        </td>
        <td><textarea rows='4' cols='67' name='reason_returned' id='reason_returned' wrap='physical'></textarea>".textarea_size_control('reason_returned')."</td>
      </tr>
      <tr>
        <td bgcolor='#CCCCCC' style='width: 40%;'><b>"._("General comments on this project
        or your experience with working with this PPer.")."</b></td>
        <td><textarea rows='4' cols='67' name='general_comments' id='general_comments'  wrap='physical'></textarea>".textarea_size_control('general_comments')."</td>
      </tr>

      <tr>
      <td colspan='2' style='text-align: center; font-weight: bold; background: $theme[color_logobar_bg];'>
         "._("HTML Version")."
         </td>
      </tr>
      <tr>
        <td bgcolor='#CCCCCC' style='width: 40%;'><b>"._("HTML submitted")."</b></td>
        <td>
            <label for='html_sub_yes'>
                <input type='radio' name='html_sub' id='html_sub_yes' value='yes' onclick='set_html(false)'>"._("Yes")."&nbsp;&nbsp;&nbsp;</label>
            <label for='html_sub_no'>
			    <input type='radio' name='html_sub' id='html_sub_no' value='no' onclick='set_html(true)'>"._("No")."</label>
      <tr>
        <td bgcolor='#CCCCCC' style='width: 40%;'><b>"._("Issues with the HTML version")."</b></td>
        <td>
          <input type='checkbox' name='html_markup' id='html_markup' disabled><label for='html_markup'>"._("Markup")."</label><br />\n
          <input type='checkbox' name='html_css' id='html_css' disabled><label for='html_css'>"._("CSS")."</label><br />\n
          <input type='checkbox' name='html_links' id='html_links' disabled><label for='html_links'>"._("Internal links")."</label><br />\n
          <input type='checkbox' name='html_image_links' id='html_image_links' disabled><label for='html_image_links'>"._("Links to images")."</label><br />\n
          <input type='checkbox' name='html_image_size' id='html_image_size' disabled><label for='html_image_size'>"._("Image quality/size")."</label><br />\n
          <input type='checkbox' name='html_header' id='html_header' disabled><label for='html_header'>"._("Header")."</label><br />\n
        </td>
      </tr>
      <tr>
        <td bgcolor='#CCCCCC' style='width: 40%;'><b>"._("General comments on HTML version")."</b></td>
        <td><textarea rows='4' cols='67' name='html_desc' id='html_desc' wrap='physical' disabled></textarea>".textarea_size_control('html_desc')."</td>
      </tr>
      <tr>
        <td colspan='2' style='text-align: center; font-weight: bold; background: $theme[color_logobar_bg];'>
          "._("Complexity Details (optional)")."
        </td>
      </tr>
      <tr>
        <td bgcolor='#CCCCCC' style='width: 40%;'><b>"._("Present in the text")."</b></td>
        <td>
          <input type='checkbox' name='tables' id='tables'><label for='tables'>"._("Tables")."</label><br />\n
          <input type='checkbox' name='poetry' id='poetry'><label for='poetry'>"._("Poetry")."</label><br />\n
          <input type='checkbox' name='index' id='index'><label for='index'>"._("Index")."</label><br />\n
          <input type='checkbox' name='footnotes' id='footnotes'><label for='footnotes'>"._("Footnotes")."</label><br />\n
          <input type='checkbox' name='sidenotes' id='sidenotes'><label for='sidenotes'>"._("Sidenotes")."</label><br />\n
          <input type='checkbox' name='blockquotes' id='blockquotes'><label for='blockquotes'>"._("Blockquotes")."</label><br />\n
          <input type='checkbox' name='illustrations' id='illustrations'><label for='illustrations'>"._("Illustrations;")."</label><label for='illus_num'>"._(" approx. number:")."</label>
            <input type='text' size='3' name='illus_num' id='illus_num'><br />\n
          <input type='checkbox' name='multilang' id='multilang'><label for='multilang'>"._("Multiple Languages")."</label><br />\n
        </td>
      </tr>
      <tr>
        <td bgcolor='#CCCCCC' style='width: 40%;'><b>"._("Any other unusual formatting")."</b></td>
        <td><textarea rows='3' cols='67' name='unusual_formatting' id='unusual_formatting' wrap='physical'></textarea>".textarea_size_control('unusual_formatting')."</td>
      </tr>
      <tr>
        <td bgcolor='#CCCCCC' style='width: 40%;'><b>"._("Approximate number of errors")."</b></td><td>
          <input type='text' size='3' name='e_comma_num' id='e_comma_num'> "._("Comma/Period Error")."<br />
          <input type='text' size='3' name='e_spellcheck_num' id='e_spellcheck_num'> "._("Spellcheck/Scannos")."<br />
          <input type='text' size='3' name='e_hyph_num' id='e_hyph_num'> "._("Hyphens/Em-dashes")."<br />
          <input type='text' size='3' name='e_gutcheck_num' id='e_gutcheck_num'> "._("Gutcheck")."<br />
          <input type='text' size='3' name='e_html_num' id='e_html_num'> HTML<br />
          <input type='text' size='3' name='e_other_num' id='e_other_num'> 
					  <input type='text' size='12' name='other_error_type' id='other_error_type' value='"._("Other (specify)")."'><br />
        </td>
      </tr>
      <tr>
        <td colspan='2' style='text-align: center; font-weight: bold; background: $theme[color_logobar_bg];'>"._("Promotions")."</td>
      </tr>
      <tr>
        <td bgcolor='#CCCCCC' style='width: 40%;'>
          "._("<b>Comments regarding the promotion of the PPer</b> <small>(This section will not be sent to the PPer,
               even if you request they be sent a copy of the report card)</small>")."<b>:</b>
        </td>
        <td><textarea rows='4' cols='67' name='promotions' id='promotions' wrap='physical'></textarea>".textarea_size_control('promotions')."</td>
      </tr>

      <tr><td colspan='2' style='text-align: center; font-weight: bold; background: $theme[color_logobar_bg];'>
        "._("Copies")."
      </td></tr>
      <tr>
        <td bgcolor='#CCCCCC' style='width: 40%;'><b>"._("Send to")."</b></td>
        <td><input type='checkbox' name='cc_ppv' id='cc_ppv' /><label for='cc_ppv'>"._("Me")."</label><br />
            <input type='checkbox' name='cc_pp'  id='cc_pp' /><label for='cc_pp'>$pper->username</label><br />
            <input type='checkbox' name='foo' checked disabled />"._("PPV Reports")."
        </td>
      </tr>
          <tr><td colspan='2' style='text-align: center'>
          <input type='submit' value='"._("Send")."'></td></tr>
</table>
</form>";
}

theme('','footer');
?>
