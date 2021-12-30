<?php
$relPath = '../pinc/';
include_once($relPath.'base.inc');
include_once($relPath.'theme.inc');
include_once($relPath.'quizzes.inc');

require_login();

$username = $pguser;
if (user_is_a_sitemanager() || user_is_proj_facilitator()) {
    $username = array_get($_GET, 'username', $pguser);
    if (!User::is_valid_user($username)) {
        die("Invalid username.");
    }
}

// Introductory information to be displayed when showing all proofing or
// all formatting quizzes.
$quiz_type_intro = [
    "proof" => [
        "title" => _("Proofreading Quizzes and Tutorials"),
        "head" => "<h1>" . _("Interactive Proofreading Quizzes and Tutorials") . "</h1>\n" .
                  "<p>" . sprintf(_("Welcome to %s's interactive proofreading quizzes and tutorials! These quizzes cover corrections you should make in the proofreading rounds."), $site_abbreviation) . "</p>\n" .
                  "<p>" . sprintf(_("You can use these pages in two different ways. If you are not yet familiar with the proofreading guidelines you should use the tutorial mode by clicking on the '%1\$s' links.  If you already know the proofreading guidelines you can use them as quizzes only by clicking the '%2\$s' links."), _("Tutorial"), _("Quiz Page")) . "</p>\n",
    ],

    "format" => [
        "title" => _("Formatting Quizzes"),
        "head" => "<h1>" . _("Formatting Quizzes") . "</h1>\n",
    ],
];

// show a level of quizzes
if (
    ($quiz_level_id = @$_GET['show_level'])
    &&
    ($quiz_level = @QuizLevel::$map_quiz_level_id_to_QuizLevel[$quiz_level_id])
) {
    output_header($quiz_level->level_name, SHOW_STATSBAR);
    echo "<h1>".$quiz_level->level_name."</h1>\n";
    echo $quiz_level->info;
    foreach ($quiz_level->quizzes as $quiz) {
        $quiz->show_results_table($username);
    }

    echo "<p>" . sprintf(_("This page is currently displaying only the %s."), $quiz_level->level_name);
    if ($quiz_level->activity_type == "proof") {
        echo " <a href='start.php?show_only=proof'>" . _("View all proofreading quizzes and tutorials.") . "</a></p>";
    } elseif ($quiz_level->activity_type == "format") {
        echo " <a href='start.php?show_only=format'>" . _("View all formatting quizzes and tutorials.") . "</a></p>";
    }
}

// show a whole category of quizzes (proofing or formatting)
elseif (
    ($so = @$_GET['show_only'])
    &&
    ($so == 'PQ' || $so == 'FQ' || isset($quiz_type_intro[$so]))
) {
    if ($so == 'PQ') {
        $activity_type = 'proof';
    } elseif ($so == 'FQ') {
        $activity_type = 'format';
    } else {
        $activity_type = $so;
    }

    $intro = $quiz_type_intro[$activity_type];

    output_header($intro['title'], SHOW_STATSBAR);

    if (user_is_a_sitemanager() || user_is_proj_facilitator()) {
        echo "<div id='linkbox'>";
        echo "<form action='#' method='get'><p>";
        echo _("See results for another user") . "<br>";
        echo "<input type='hidden' name='show_only' value='$activity_type' required>";
        echo "<input type='text' name='username' value='$username' required>";
        echo "<input type='submit' value='" . attr_safe(_("Refresh")) . "'>";
        echo "</p></form>\n";
        echo "</div>";
    }

    echo $intro['head'];

    if ($username != $pguser) {
        echo "<p>" . sprintf(_("Showing quiz results for user <b>%s</b>."), $username) . "</p>";
    }

    $levels_for_current_type = [];
    foreach (QuizLevel::$map_quiz_level_id_to_QuizLevel as $quiz_level_id => $quiz_level) {
        if ($quiz_level->activity_type == $activity_type) {
            array_push($levels_for_current_type, $quiz_level);
        }
    }
    foreach ($levels_for_current_type as $quiz_level) {
        if (count($levels_for_current_type) > 1) {
            echo "<h2>".$quiz_level->level_name."</h2>\n";
        }
        echo $quiz_level->info;
        foreach ($quiz_level->quizzes as $quiz) {
            $quiz->show_results_table($username);
        }
    }
}

// otherwise just give links to P and F quizzes separately, so that the page isn't too long
else {
    output_header(_('Interactive Quizzes and Tutorials'), SHOW_STATSBAR);
    echo "<h1>" . _("Interactive Quizzes and Tutorials") . "</h1>\n";
    echo "<p>" . sprintf(_("Welcome to %s's interactive quizzes! The following quizzes are available:"), $site_abbreviation) . "</p>\n<p>";
    foreach ($quiz_type_intro as $key => $intro) {
        echo "<a href='start.php?show_only=$key'>" . $intro['title'] ."</a><br>";
    }
    echo "</p>\n";
}
