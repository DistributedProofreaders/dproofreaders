<?php
include_once($relPath.'html_page_common.inc'); // output_html_header()
include_once($relPath.'theme.inc'); // headerbar_text_array()
include_once($relPath.'faq.inc');
include_once($relPath.'quizzes.inc'); // get_quiz_id_param get_Quiz_with_id

function output_small_header($quiz)
{
    global $code_url, $site_name, $site_abbreviation;

    output_html_header('');

    $quiz_name = $quiz->short_name;

    echo "\n<div id='header'>\n";
    echo "<div id='logo'>\n";
    echo "<a href='$code_url'><img src='$code_url/graphics/dp-logo-360x68px.png' width='360' height='68' alt='$site_name' border='0'></a>\n";
    echo "</div>";
    echo "</div>\n";

    // XXX localization bug:
    // $guide_url should point to the locale-appropriate version of the guidelines.
    if ($quiz->activity_type == "format")
    {
        $guide_url = get_faq_url("formatting_guidelines.php");
        $guide_text = _("Formatting Guidelines");
    }
    elseif ($quiz->activity_type == "proof")
    {
        $guide_url = get_faq_url("proofreading_guidelines.php");
        $guide_text = _("Proofreading Guidelines");
    }
    else
    {
        $guide_url = get_faq_url("faq_central.php");
        $guide_text = _("Help");
    }

    $links = array(
        array('text' => $site_abbreviation, 'url' => "$code_url/default.php", 'target' => '_top'),
        array('text' => "<span class='currentpage'>$quiz_name</span>"),
    );

    echo "<div id='navbar-outer'>";
    echo "<div id='navbar'>";

    echo "<span id='navbar-left'>";
    echo headerbar_text_array($links);
    echo "</span>";

    $links = array(
        array('text' => _("Activity Hub"), 'url' => "$code_url/activity_hub.php", 'target' => '_top'),
        array('text' => $guide_text, 'url' => $guide_url, 'target' => '_top'),
    );

    echo "<span id='navbar-right'>";
    echo headerbar_text_array($links);
    echo "</span>";
    echo "</div>";
    echo "</div>";

    echo "<div id='content-container' style='width: 100%'>";

    // Call output_small_footer when the main page content has been emitted so
    // we can emit the footer
    register_shutdown_function('output_small_footer');
}

function output_small_footer()
{
    echo "</div>\n";
    output_html_footer();
}

// vim: sw=4 ts=4 expandtab
