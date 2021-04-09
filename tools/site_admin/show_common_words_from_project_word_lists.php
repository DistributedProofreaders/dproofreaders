<?php
$relPath="./../../pinc/";
include_once($relPath.'base.inc');
include_once($relPath.'theme.inc');
include_once($relPath.'user_is.inc');
include_once($relPath.'wordcheck_engine.inc');
include_once($relPath.'links.inc');
include_once($relPath.'misc.inc'); // array_get()

require_login();

// check to see if the user is authorized to be here
if (!( user_is_a_sitemanager() || user_is_proj_facilitator() ))
{
    die("You are not authorized to use this form.");
}


// fetch any data sent our way.
$action = array_get($_REQUEST, "action", "list");
$language = array_get($_REQUEST, "language", "");
$language = urldecode($language);
$list_type = array_get($_REQUEST, "list_type", "");
$cutoff = array_get($_REQUEST, "cutoff", 25);
$lang_match = array_get($_REQUEST, "lang_match", "primary");

$title = _("Show common words from project word lists");

output_header($title, NO_STATSBAR);

echo "<h1>$title</h1>";

$display_list = _handle_action($action, $list_type, $language, $cutoff, $lang_match);

if($display_list)
{
    echo "<p>" . _("This page can assist building site-wide Good and Bad word lists. The concept is that if a word appears on a significant portion of project word lists it is a good candidate for a site-wide word list. Given a project language and word list type it will create a combined list of words from all projects for that language. The results list indicates out of the projects with word lists, what percentage of those contain the word.") . "</p>";

    echo "<p>" ._("The results list is just a recommendation and should be vetted by subject matter experts (native language speakers, PFs, etc) before being added to a site-wide word list.") . "</p>";

    // show create form
    echo "<form action='show_common_words_from_project_word_lists.php' method='post'>";
    echo "<input type='hidden' name='action' value='show'>";
    echo "<table>";
    echo "<tr>";
    echo "<td>" . _("Project Languages:") . "</td>";
    echo "<td><select name='language'>";
    // load all project languages
    $res = mysqli_query(DPDatabase::get_connection(), "
        SELECT language, count(language)
        FROM projects
        GROUP BY language
    ");
    $used_languages = array();
    while( list($language,$language_count) = mysqli_fetch_row($res) )
    {
        foreach(Project::decode_language($language) as $lang)
        {
            @$used_languages[$lang] += $language_count;
        }
    }
    ksort($used_languages);
    foreach( $used_languages as $language => $language_count )
    {
        $option_string = sprintf(_('%1$s (%2$d projects)'), $language, $language_count);
        $option_value = urlencode($language);
        echo "<option value='$option_value'>$option_string</option>";
    }
    mysqli_free_result($res);
    echo "</select>";
    echo "</td>";
    echo "</tr>";

    echo "<tr>";
    echo "<td>" . _("Word list type:") . "</td>";
    echo "<td><select name='list_type'>";
    echo "<option value='good'>" . _("Good") . "</option>";
    echo "<option value='bad'>" . _("Bad") . "</option>";
    echo "</select> - ";
    echo _("type of word list to analyze");
    echo "</td>";
    echo "</tr>";

    echo "<tr>";
    echo "<td>" . _("Percentage cutoff:") . "</td>";
    echo "<td><input type='number' style='width: 4em;' name='cutoff' value='$cutoff' min='0'>% - ";
    echo _("words that occur less frequently than this in the word lists will not be shown in the results");
    echo "</td>";
    echo "</tr>";

    echo "<tr>";
    echo "<td></td>";
    echo "<td>";
    echo "<input type='radio' name='lang_match' value='any'> " . _("Use any language match (matches primary or secondary languages)") . "<br>";
    echo "<input type='radio' name='lang_match' value='primary' checked> " . _("Use primary language match (matches primary language only)") . "<br>";
    echo "<input type='radio' name='lang_match' value='exact'> " . _("Use exact language match (secondary languages won't be used)") . "<br>";
    echo "</td>";
    echo "</tr>";

    echo "</table>";

    echo "<input type='submit' value='" ._("Show") . "'>";
    echo "</form>";

    echo "</table>";
}

// Everything else is just function declarations.

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

// handle any actionable request
// arguments:
//        $action - action to take
//                  one of: show, list
//     $list_type - list type to show
//                  one of: good, bad
//      $language - the list language
//        $cutoff - words that occur less than $cutoff percent are not shown
//    $lang_match - how to match the language
//                  one of: exact, primary, any
// return codes:
//   TRUE - request was handled (don't display default list)
//   FALSE - request wasn't handled (display list)
function _handle_action($action, $list_type, $language, $cutoff, $lang_match)
{
    $display_list = FALSE;

    switch($action)
    {
        case "show":
            $word_freq = array();
            $total_projects = 0;
            $total_projects_with_words = 0;

            // figure out what kind of language matching we're going to use
            $where_clause = "";
            switch($lang_match)
            {
                case "exact":
                    $where_clause = "language = '$language'";
                    break;

                case "primary":
                    $where_clause = "language like '$language%'";
                    break;

                case "any":
                    $where_clause = "language like '%$language%'";
                    break;

                default:
                    die("Unknown language match used: $lang_match");
            }

            // loop through all projects that use $language
            $res = mysqli_query(DPDatabase::get_connection(), "
                SELECT projectid
                FROM projects
                WHERE $where_clause
            ");
            while( list($projectid) = mysqli_fetch_row($res) )
            {
                if($list_type == "good")
                    $words = load_project_good_words($projectid);
                elseif($list_type == "bad")
                    $words = load_project_bad_words($projectid);
                else
                    die("Unknown list type: $list_type");

                foreach( $words as $word )
                    @$word_freq[$word]++;

                if(count($words))
                    $total_projects_with_words++;

                $total_projects++;
            }
            mysqli_free_result($res);

            // sort the results
            arsort($word_freq);

            // show the results
            echo "<pre>";
            echo _("Language") . ": $language<br>";
            echo sprintf(_("Language match type: %s"), $lang_match) . "<br>";
            echo sprintf(_("Word list type: %s"), $list_type) . "<br>";
            echo sprintf(_("Cutoff percentage: %d%%"), $cutoff) . "<br>";
            echo sprintf(_("Total projects matching language: %d"), $total_projects) . "<br>";
            echo sprintf(_("Total projects with word lists: %d"), $total_projects_with_words) . "<br>";
            echo "<br>";
            echo _("Note: Percentages are calculated as frequency over the total number of projects with word lists.") . "<br>";
            echo "<br>";
            echo sprintf('%20s  %5s  %s', _("Word"), _("Count"), _("Frequency")) . "<br>";
            foreach($word_freq as $word => $freq)
            {
                $percentage = ($freq/$total_projects_with_words)*100;

                if($percentage < $cutoff)
                    break;

                echo sprintf("%20s  %5d  (%-3.2f%%)", $word, $freq, $percentage) . "<br>";
            }
            echo "</pre>";
            break;

        case "list":
            $display_list = TRUE;
            break;

        default:
            die("Invalid action encountered.");
    }

    return $display_list;
}

