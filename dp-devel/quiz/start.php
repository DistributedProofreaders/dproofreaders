<?php
$relPath='../pinc/';
include_once($relPath.'base.inc');
include_once($relPath.'theme.inc');
include_once($relPath.'quizzes.inc');

$theme_args["css_data"] = "th.q {background:$theme[color_headerbar_bg]; color:$theme[color_headerbar_font];}
td.info {background:$theme[color_navbar_bg]; color:$theme[color_navbar_font];}";


// Introductory information to be displayed when showing all proofing or
// all formatting quizzes.
$quiz_type_intro = array(
    "proof" => array(
        "title" => _("Proofreading Quizzes and Tutorials"),
        "head" => "<h1>" . _("Interactive Proofreading Quizzes and Tutorials") . "</h1>\n" .
                  "<p>" . sprintf(_("Welcome to %s's interactive proofreading quizzes and tutorials! These quizzes cover corrections you should make in the proofreading rounds."), $site_abbreviation) . "</p>\n" .
                  "<p>" . _("You can use these pages in two different ways. If you are not yet familiar with the proofreading guidelines you should use the tutorial mode by clicking on the 'Tutorial' links.  If you already know the proofreading guidelines you can use them as quizzes only by clicking the 'Quiz' links.") . "</p>\n"
    ),
    
    "format" => array(
        "title" => _("Formatting Quizzes"),
        "head" => "<h1>" . _("Formatting Quizzes") . "</h1>\n"
    ),
);

// show a level of quizzes
if (!empty($_GET['show_level']) && (array_search($_GET['show_level'],$defined_quiz_levels) !== false))
{
    $quiz_level = $_GET['show_level'];
    output_header(${$quiz_level}->level_name, SHOW_STATSBAR, $theme_args);
    echo "<h1>".${$quiz_level}->level_name."</h1>\n";
    echo ${$quiz_level}->info;
    foreach (${$quiz_level}->quizzes as $quiz)
    {
        ${$quiz}->show_results_table($pguser);
    }
    if (array_search($quiz_level,$defined_quiz_levels) !== false)
    {
        echo "<p>" . sprintf(_("This page is currently displaying only the %s."), ${$quiz_level}->level_name);
        if (${$quiz_level}->activity_type == "proof")
        {
            echo " <a href='start.php?show_only=proof'>" . _("View all proofreading quizzes and tutorials.") . "</a></p>";
        }
        elseif (${$quiz_level}->activity_type == "format")
        {
            echo " <a href='start.php?show_only=format'>" . _("View all formatting quizzes and tutorials.") . "</a></p>";
        }
    }
}

// show a whole category of quizzes (proofing or formatting)
elseif (!empty($_GET['show_only']) &&
    array_search($_GET['show_only'], array_keys($quiz_type_intro)) !== false)
{
    $intro = $quiz_type_intro[$_GET['show_only']];

    output_header($intro['title'], SHOW_STATSBAR, $theme_args);
    echo $intro['head'];

    $levels_for_current_type = array();
    foreach ($defined_quiz_levels as $quiz_level)
    {
        if (${$quiz_level}->activity_type == $_GET['show_only'])
        {
            array_push ($levels_for_current_type, $quiz_level);
        }
    }
    foreach ($levels_for_current_type as $quiz_level)
    {
        if (count($levels_for_current_type) > 1)
        {
            echo "<h2>".${$quiz_level}->level_name."</h2>\n";
        }
        echo ${$quiz_level}->info;
        foreach (${$quiz_level}->quizzes as $quiz)
        {
            ${$quiz}->show_results_table($pguser);
        }
    }
}

// otherwise just give links to P and F quizzes separately, so that the page isn't too long
else
{
    output_header(_('Interactive Quizzes and Tutorials'), SHOW_STATSBAR, $theme_args);
    echo "<h1>" . _("Interactive Quizzes and Tutorials") . "</h1>\n";
    echo "<p>" . sprintf(_("Welcome to %s's interactive quizzes! The following quizzes are available:"), $site_abbreviation) . "</p>\n<p>";
    foreach ($quiz_type_intro as $key=>$intro)
    {
        echo "<a href='start.php?show_only=$key'>" . $intro['title'] ."</a><br>";
    }
    echo "</p>\n";
}

// vim: sw=4 ts=4 expandtab
