<?php
$relPath='../pinc/';
include_once($relPath.'base.inc');
include_once($relPath.'theme.inc');
include_once($relPath.'quizzes.inc');

require_login();

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
if (
    ($quiz_level_id = @$_GET['show_level'])
    &&
    ($quiz_level = @$map_quiz_level_id_to_QuizLevel[$quiz_level_id])
)
{
    output_header($quiz_level->level_name, SHOW_STATSBAR);
    echo "<h1>".$quiz_level->level_name."</h1>\n";
    echo $quiz_level->info;
    foreach ($quiz_level->quizzes as $quiz)
    {
        $quiz->show_results_table($pguser);
    }

    echo "<p>" . sprintf(_("This page is currently displaying only the %s."), $quiz_level->level_name);
    if ($quiz_level->activity_type == "proof")
    {
        echo " <a href='start.php?show_only=proof'>" . _("View all proofreading quizzes and tutorials.") . "</a></p>";
    }
    elseif ($quiz_level->activity_type == "format")
    {
        echo " <a href='start.php?show_only=format'>" . _("View all formatting quizzes and tutorials.") . "</a></p>";
    }
}

// show a whole category of quizzes (proofing or formatting)
elseif (
    ($so = @$_GET['show_only'])
    &&
    ($so == 'PQ' || $so == 'FQ' || isset($quiz_type_intro[$so]))
)
{
    if ($so == 'PQ')
        $activity_type = 'proof';
    elseif ($so == 'FQ')
        $activity_type = 'format';
    else
        $activity_type = $so;

    $intro = $quiz_type_intro[$activity_type];

    output_header($intro['title'], SHOW_STATSBAR);
    echo $intro['head'];

    $levels_for_current_type = array();
    foreach ($map_quiz_level_id_to_QuizLevel as $quiz_level_id => $quiz_level)
    {
        if ($quiz_level->activity_type == $activity_type)
        {
            array_push ($levels_for_current_type, $quiz_level);
        }
    }
    foreach ($levels_for_current_type as $quiz_level)
    {
        if (count($levels_for_current_type) > 1)
        {
            echo "<h2>".$quiz_level->level_name."</h2>\n";
        }
        echo $quiz_level->info;
        foreach ($quiz_level->quizzes as $quiz)
        {
            $quiz->show_results_table($pguser);
        }
    }
}

// otherwise just give links to P and F quizzes separately, so that the page isn't too long
else
{
    output_header(_('Interactive Quizzes and Tutorials'), SHOW_STATSBAR);
    echo "<h1>" . _("Interactive Quizzes and Tutorials") . "</h1>\n";
    echo "<p>" . sprintf(_("Welcome to %s's interactive quizzes! The following quizzes are available:"), $site_abbreviation) . "</p>\n<p>";
    foreach ($quiz_type_intro as $key=>$intro)
    {
        echo "<a href='start.php?show_only=$key'>" . $intro['title'] ."</a><br>";
    }
    echo "</p>\n";
}

// vim: sw=4 ts=4 expandtab
