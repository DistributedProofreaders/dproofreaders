<?php
$relPath = '../../../pinc/';
include_once($relPath.'base.inc');
include_once($relPath.'theme.inc');

require_login();

output_header(_('Quiz Wizard'));

function ssqs($x)
{
    return str_replace('\\\'', '\'', $x);
}

function enl($x)
{
    $out = str_replace("\r\n", '\n', $x);
    return str_replace("\n", '\n', $out);
}

function sdbsn($x)
{
    return str_replace('\\\\n', '\\n', $x);
}


function make_output()
{
    $out .= '$' . ssqs($_SESSION['quiz_data']['quiz_id']) . " = new Quiz(\n";
    $out .= ' "' . ssqs($_SESSION['quiz_data']['quiz_id']) . "\",\n";
    $out .= ' _("' . ssqs($_SESSION['quiz_data']['quiz_name']) . "\"),\n";
    $out .= ' _("' . ssqs($_SESSION['quiz_data']['short_quiz_name']) . "\"),\n";
    $out .= ' _("' . ssqs($_SESSION['quiz_data']['description']) . "\"),\n";
    $out .= ' "' . ssqs($_SESSION['quiz_data']['thread']) . "\",\n";

    $out .= " array(\n";

    $pages = [];
    foreach ($_SESSION['quiz_data']['pages'] as $quiz_page_id => $details) {
        $pages[] = '  _("' . ssqs($details) . '") => "' . ssqs($quiz_page_id) . "\"";
    }
    $out .= implode(",\n", $pages);
    $out .= "),\n";

    $out .= " array(\n  'maximum_age' => 15778463) // 6 months\n);\n";
    return html_safe($out);
}

echo "<h2>" . _("Completed Quiz Code") . "</h2>";

echo "<p>" . _("Below you will find some prepared php code. Copy the code into an editor and save it for later use. When the quiz pages are uploaded to a sandbox for testing, this code will need to be added to the file /pinc/quizzes.inc.") . "</p>";

echo "<textarea rows='10' cols='60' wrap='off'>";
echo make_output();
echo "</textarea>\n";

echo "<p>" . sprintf(_("Now that you've set up the quiz information and page listing, you can <a href='%s'>start entering the data</a> for individual quiz pages."), "./general.php?start=true") . "</p>";

$_SESSION['quiz_data']['lastpage'] = 'output_pages';
