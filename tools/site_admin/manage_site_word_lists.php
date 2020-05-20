<?php
$relPath="./../../pinc/";
include_once($relPath.'base.inc');
include_once($relPath.'theme.inc');
include_once($relPath.'user_is.inc');
include_once($relPath.'misc.inc'); // attr_safe(), html_safe()
include_once($relPath.'wordcheck_engine.inc');
include_once($relPath.'links.inc');
include_once($relPath.'misc.inc'); // array_get()

require_login();

// check to see if the user is authorized to be here
if ( !(user_is_a_sitemanager()) )
{
    die("You are not authorized to use this form.");
}

// fetch any data sent our way. word_string will only
// come in via a POST but the others may come in
// via a GET as well so we use $_REQUEST for them
$action = array_get($_REQUEST, "action", "list");
$list_type = array_get($_REQUEST, "list_type", "");
$language = array_get($_REQUEST, "language", "");
$word_string = array_get($_POST, "word_string", "");

$title = _("Manage Site Word Lists");

output_header($title, NO_STATSBAR);

echo "<h1>$title</h1>";

$display_list = _handle_action($action, $list_type, $language, $word_string);

if($display_list)
{
    echo "<h2>" . _("Create a new site word list") . "</h2>";

    // show create form
    echo "<form action='manage_site_word_lists.php' method='post'>";
    echo "<input type='hidden' name='action' value='create'>";
    echo "<table>";
    echo "<tr>";
    echo "<td>" . _("Language") . ":</td>";
    echo "<td><select name='language'>";
    foreach($lang_list as $langArray)
    {
        $language = $langArray["lang_name"];
        echo "<option value='$language'>$language</option>";
    }
    echo "</select>";
    echo "</td>";
    echo "</tr>";

    echo "<tr>";
    echo "<td>" . _("Word list type") . ":</td>";
    echo "<td><select name='list_type'>";
    echo "<option value='good'>" . _("Good") . "</option>";
    echo "<option value='bad'>" . _("Bad") . "</option>";
    echo "<option value='possible_bad'>" . _("Possible Bad") . "</option>";
    echo "</select>";
    echo "</td>";
    echo "</tr>";
    echo "</table>";

    echo "<input type='submit' value='" ._("Create") . "'>";
    echo "</form>";

    echo "<hr>";

    echo "<h2>" . _("Current site-level word lists") . "</h2>";
    echo "<form action='manage_site_word_lists.php' method='get'>";
    echo "<input type='hidden' name='action' value='list'>";
    echo "<input type='submit' value='" . _("Refresh List") . "'>";
    echo "</form>";
    echo "<table class='basic striped'>";
    echo "<tr>";
    echo "<th>" . _("Action") . "</th>";
    echo "<th>" . _("List Type") . "</th>";
    echo "<th>" . _("Language") . "</th>";
    echo "<th>" . _("Filename") . "</th>";
    echo "<th>" . _("Number of Words") . "</th>";
    echo "</tr>";

    $site_lists = get_site_word_files("/txt$/");
    asort($site_lists);
    foreach($site_lists as $full_filename => $url)
    {
        $filename = basename($full_filename);
        if(!preg_match("/^(\w+)_words\.(\w+)\.txt$/",$filename,$matches))
        {
            // found a file we don't recognize so skip it
            continue;
        }
        list($null,$list_type,$langcode3) = $matches;
        $list_words = load_word_list($full_filename);
        $word_count = count($list_words);
        $language = langname_for_langcode3($langcode3);

        echo "<tr>";
        echo "<td><a href='manage_site_word_lists.php?action=edit&amp;list_type=$list_type&amp;language=$language'>" . _("Edit") . "</a> | <a href='manage_site_word_lists.php?action=delete&amp;list_type=$list_type&amp;language=$language'>" . _("Delete") . "</a></td>";
        echo "<td>$list_type</td>";
        echo "<td>$language</td>";
        echo "<td>" . new_window_link($url, $filename) . "</td>";
        echo "<td class='right-align'>$word_count</td>";
        echo "</tr>";
    }
    echo "</table>";
}

// Everything else is just function declarations.

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

// handle any actionable request
// arguments:
//        $action - action to take
//                  one of: delete, deleteconfirmed, create, edit, save,
//                  saveconfirmed, list
//     $list_type - list type to operate on
//                  one of: good, bad, possible_bad
//      $language - the list language
//   $word_string - string of words to save
// return codes:
//   TRUE - request was handled (don't display default list)
//   FALSE - request wasn't handled (display list)
function _handle_action($action, $list_type, $language, $word_string)
{
    // look up the langcode3 for the language passed-in
    $langcode3 = langcode3_for_langname($language);

    $display_list = FALSE;

    switch($action)
    {
        case "delete":
            // they want to delete a list, prompt for confirmation first
            echo "<p>" . sprintf(_('Are you sure you want to delete the <b>%1$s</b> site word list for language <b>%2$s</b>?'), $list_type, $language) . "<p>";
            echo "<form action='manage_site_word_lists.php' method='post'>";
            echo "<input type='hidden' name='action' value='deleteconfirmed'>";
            echo "<input type='hidden' name='list_type' value='$list_type'>";
            echo "<input type='hidden' name='language' value='$language'>";
            echo "<input type='submit' value='" . _("Yes") . "'>";
            echo "</form>";
            echo "<form action='manage_site_word_lists.php' method='post'>";
            echo "<input type='hidden' name='action' value='list'>";
            echo "<input type='submit' value='" . _("No") . "'>";
            echo "</form>";
            break;

        case "deleteconfirmed":
            // they've confirmed a deletion, delete the file
            $fileObject = get_site_word_file( $langcode3, $list_type );
            unlink($fileObject->abs_path);
            echo "<p>" . sprintf(_("File <b>%s</b> has been deleted."),$fileObject->abs_path) . "</p>";
            $display_list = TRUE;
            break;

        case "create":
            // they want to create a word list
            // see if the word list has words already
            $fileObject = get_site_word_file($langcode3, $list_type);
            $list_words = load_word_list($fileObject->abs_path);

            if(count($list_words))
            // it does so give an error message
            {
                echo "<p class='error'>" . sprintf(_('Can\'t create a <b>%1$s</b> list for <b>%2$s</b> (<b>%3$s</b>) as it already exists! Edit the list below instead.'), $list_type, $language, $langcode3) . "</p>";
                $display_list = TRUE;
            }
            else
            // it doesn't so lets let them create one
            {
                echo "<p>" . sprintf(_('Creating a <b>%1$s</b> list for language <b>%2$s</b>.'), $list_type, $language) . "</p>";
                _echo_input_form($list_type, $langcode3, $language);
            }
            break;

        case "edit":
            // we're editing the list
            echo "<p>" . sprintf(_('Editing a <b>%1$s</b> list for language <b>%2$s</b>.'), $list_type, $language) . "</p>";
            _echo_input_form($list_type, $langcode3, $language);
            break;

        case "save":
            // they want to save a list, prompt for validation first

            // calculate the changes
            // load existing word list
            $fileObject = get_site_word_file( $langcode3, $list_type);
            $old_words = load_word_list($fileObject->abs_path);
            // clean up the new words
            $new_words = explode("\n",$word_string);
            $new_words = array_map('rtrim',$new_words);
            $new_words = array_unique($new_words);
            // the above set mirrors the clean-up code in save_word_list
            // TODO: other good checks might be ensuring that the words are
            // recognized by WordCheck, that they don't have spaces within
            // them, that they are all in $charset, etc

            // calculate the differences
            $unchanged = count(array_intersect($old_words,$new_words));
            $additions = count(array_diff($new_words,$old_words));
            $deletions = count(array_diff($old_words,$new_words));
            
            echo "<p>" . sprintf(_('Are you sure you want to save the <b>%1$s</b> site word list for language <b>%2$s</b>?'), $list_type, $language) . "<p>";
            echo "<p>" . sprintf(_('You made %1$d additions and %2$d deletions from the list (with %3$d words unchanged).'), $additions, $deletions, $unchanged) . "</p>";
            echo "<form action='manage_site_word_lists.php' method='post'>";
            echo "<input type='hidden' name='action' value='saveconfirmed'>";
            echo "<input type='hidden' name='list_type' value='$list_type'>";
            echo "<input type='hidden' name='language' value='$language'>";
            echo "<input type='hidden' name='word_string' value='" . attr_safe($word_string) . "'>";
            echo "<input type='submit' value='" . _("Yes") . "'>";
            echo "</form>";
            echo "<form action='manage_site_word_lists.php' method='post'>";
            echo "<input type='hidden' name='action' value='list'>";
            echo "<input type='submit' value='" . _("No") . "'>";
            echo "</form>";
            break;

        case "saveconfirmed":
            // save the list if requested
            $fileObject = get_site_word_file( $langcode3, $list_type);
            $words = explode("\n",$word_string);
            save_word_list($fileObject->abs_path, $words, "\n");
            echo "<p>" . sprintf(_('The <b>%1$s</b> list for language <b>%2$s</b> has been saved.'), $list_type, $language) . "</p>";
            $display_list = TRUE;
            break;

        case "list":
            $display_list = TRUE;
            break;

        default:
            die("Invalid action encountered.");
    }

    return $display_list;
}

// common code to print out an editing form
function _echo_input_form($list_type, $langcode3, $language) {
    $fileObject = get_site_word_file( $langcode3, $list_type);
    $words = load_word_list($fileObject->abs_path);
    $word_string = implode("\n",$words);
    echo "<form action='manage_site_word_lists.php' method='post'>";
    echo "<input type='hidden' name='action' value='save'>";
    echo "<input type='hidden' name='list_type' value='$list_type'>";
    echo "<input type='hidden' name='language' value='$language'>";
    echo "<textarea name='word_string' cols='30' rows='30'>" . html_safe($word_string) . "</textarea><br>";
    echo "<input type='submit' value='" . _("Save") . "'>";
    echo "</form>";
    echo "<form action='manage_site_word_lists.php' method='post'>";
    echo "<input type='hidden' name='action' value='list'>";
    echo "<input type='submit' value='" . _("Cancel") . "'>";
    echo "</form>";
}

// vim: sw=4 ts=4 expandtab
