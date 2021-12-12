<?php
include_once($relPath.'iso_lang_list.inc');
include_once($relPath.'genres.inc'); // load_genre_translation_array
include_once($relPath.'site_vars.php');
include_once($relPath.'wordcheck_engine.inc'); // get_project_word_file
include_once($relPath.'links.inc'); // new_window_link, new_help_window_link
include_once($relPath.'user_is.inc'); // user_is_a_sitemanager
include_once($relPath.'misc.inc'); // attr_safe(), html_safe(), endswith()
include_once($relPath.'special_colors.inc'); // load_special_days()
include_once($relPath.'User.inc');
include_once($relPath.'CharSuites.inc');
include_once($relPath.'Project.inc'); // load_image_sources()

function just_echo($field_value)
{
    echo html_safe($field_value);
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function text_field($field_value, $field_name, $args = [])
{
    $maxlength = array_get($args, "maxlength", null);
    $maxlength_attr = $maxlength ? "maxlength='$maxlength'" : '';
    $required = array_get($args, "required", false);
    $required_attr = $required ? " required" : "";
    $field_type = array_get($args, "type", "text");

    $enc_field_value = attr_safe($field_value);
    echo "<input type='$field_type' style='width: 30em;' name='$field_name' value='$enc_field_value' $maxlength_attr$required_attr>";
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function DP_user_field($field_value, $field_name, $args = [])
{
    $required = array_get($args, "required", false);
    $required_attr = $required ? " required" : "";

    $enc_field_value = attr_safe($field_value);
    echo "<input type='text' size='25' name='$field_name' value='$enc_field_value'$required_attr>";
}


// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function language_list($language)
{
    if (strpos($language, "with") > 0) {
        $pri_language = trim(substr($language, 0, strpos($language, "with")));
        $sec_language = trim(substr($language, (strpos($language, "with") + 5)));
    } else {
        $pri_language = $language;
        $sec_language = '';
    }

    echo html_safe(_("Primary")), ": <select name='pri_language' required>\n";
    maybe_echo_placeholder_option($pri_language);
    echo_language_options($pri_language);
    echo "</select><br>\n";
    echo html_safe(_("Secondary")), ": <select name='sec_language'>\n";
    echo "<option value=''>&nbsp;</option>\n";
    echo_language_options($sec_language);
    echo "</select>\n";
}

function echo_language_options($default)
{
    foreach (get_iso_language_list() as $language) {
        $lang_name = $language['lang_name'];
        $selected_string = ($default == $lang_name) ? " selected" : "";
        echo "<option value='", attr_safe($lang_name), "'$selected_string>", html_safe($lang_name), "</option>\n";
    }
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function genre_list($genre)
{
    $genres = load_genre_translation_array();
    echo "<select name='genre' required>\n";
    maybe_echo_placeholder_option($genre);
    foreach ($genres as $k => $v) {
        echo "<option value='".attr_safe($k)."'";
        if ($genre == $k) {
            echo " SELECTED";
        }
        echo ">" . html_safe($v) . "</option>";
        echo "\n";
    }
    echo "</select>";
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function charsuite_list($charsuites, $project_charsuites)
{
    $enabled_charsuites = CharSuites::get_enabled();

    $all_charsuites = [];
    foreach ($enabled_charsuites as $enabled_charsuite) {
        $all_charsuites[$enabled_charsuite->name] = $enabled_charsuite->title;
    }
    foreach ($project_charsuites as $project_charsuite) {
        $all_charsuites[$project_charsuite->name] = $project_charsuite->title;
    }
    $existing_charsuites = [];
    foreach ($charsuites as $charsuite) {
        $existing_charsuites[$charsuite] = true;
    }

    ksort($all_charsuites);
    foreach ($all_charsuites as $k => $v) {
        echo "<label><input type='checkbox' name='charsuites[]' value='".attr_safe($k)."'";
        if (@$existing_charsuites[$k]) {
            echo " checked";
        }
        echo ">" . html_safe($v) . "</label><br>";
        echo "\n";
    }
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function difficulty_list($difficulty_level)
{
    global $pguser;

    $difficulty_list = [
        'beginner' => _("Beginner"),
        'easy' => _("Easy"),
        'average' => _("Average"),
        'hard' => _("Hard"),
    ];

    // only show the beginner level to the BEGIN PM or SiteAdmins
    if (($pguser != "BEGIN") && (!user_is_a_sitemanager())) {
        unset($difficulty_list['beginner']);
    }

    foreach ($difficulty_list as $name => $label) {
        echo "<label><input type='radio' name='difficulty_level' value='".attr_safe($name)."'";
        if (strtolower($difficulty_level) == $name) {
            echo " CHECKED";
        }
        echo ">" . html_safe($label) . "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>";
    }
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function special_list($special)
{
    $special_days = load_special_days();
    sort_special_days($special_days, "open_month,open_day");

    // create an array for populating the <select> options
    $specials_array = [];
    foreach ($special_days as $spec_code => $special_day) {
        if ($special_day['enable'] || $spec_code == $special) {
            $specials_array[$spec_code] = $special_day["option_label"];
        }
    }

    $bdaymonth = 0;
    $bdayday = 0;

    // drop down select box for which special day
    echo "<select name='special_code'>";

    // add special case values first
    echo "<option value=''>&nbsp;</option>";
    echo "\n";

    echo "<option value='Birthday'";
    if (strncmp($special, 'Birthday', 8) == 0) {
        echo " SELECTED";
        $bdaymonth = substr($special, 9, 2);
        $bdayday = substr($special, 11, 2);
    }
    echo ">", html_safe(_("Birthday")), "</option>";
    echo "\n";

    echo "<option value='Otherday'";
    if (strncmp($special, 'Otherday', 8) == 0) {
        echo " SELECTED";
        $bdaymonth = substr($special, 9, 2);
        $bdayday = substr($special, 11, 2);
    }
    echo ">", html_safe(_("Otherday")), "</option>";
    echo "\n";

    // add the rest of the special days (the "ordinary" special days ;) )
    foreach ($specials_array as $k => $v) {
        echo "<option value='".attr_safe($k)."'";
        if ($special == $k) {
            echo " SELECTED";
        }
        echo ">" . html_safe($v) . "</option>";
        echo "\n";
    }
    echo "</select>";

    echo " " . new_window_link("show_specials.php", _("Special Days Info"));
    echo "<br>";

    // drop down selects for month and date, used for Birthday and Otherday specials
    echo " ", html_safe(_("Birthday/Otherday: (month)")), " <select name='bdaymonth'>";
    echo "<option value=''>&nbsp;</option>";
    echo "\n";
    $i = 1;
    while ($i <= 12) {
        $v = sprintf("%02d", $i);
        echo "<option value='$v'";
        if ($v == $bdaymonth) {
            echo " SELECTED";
        }
        echo ">$v</option>";
        echo "\n";
        $i++;
    }
    echo "</select>";

    echo " ", html_safe(_("(day)")), " <select name='bdayday'>";
    echo "<option value=''>&nbsp;</option>";
    echo "\n";
    $i = 1;
    while ($i <= 31) {
        $v = sprintf("%02d", $i);
        echo "<option value='$v'";
        if ($v == $bdayday) {
            echo " SELECTED";
        }
        echo ">$v</option>";
        echo "\n";
        $i++;
    }
    echo "</select>";
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function image_source_list($image_source)
{
    global $site_abbreviation;

    $imso_array = [
        // TRANSLATORS: %s is the site abbreviation
        "_internal" => sprintf(_("%s Internal"), $site_abbreviation),
    ];
    foreach (load_image_sources() as $code => $source) {
        if (($source["is_active"] == 1 && can_user_see_image_source($source)) ||
            $code == $image_source) {
            // $code can be a numeric value so we cast it to a string
            $imso_array[(string)$code] = $source['display_name'];
        }
    }

    // drop down select box for which image source
    echo "<select name='image_source' required>\n";
    maybe_echo_placeholder_option($image_source);
    // add the pre-defined image_sources
    foreach ($imso_array as $k => $v) {
        echo "<option value='".attr_safe($k)."'";
        if ($image_source === $k) {
            echo " SELECTED";
        }
        echo ">" . html_safe($v) . "</option>";
        echo "\n";
    }

    echo "</select> ";
    echo "<span class='small'>". html_safe(sprintf(_("'%1\$s Internal' if scanned by or for a %1\$s user."), $site_abbreviation))."</span>";
    echo " " . new_window_link("show_image_sources.php", _("Details of Image Sources"));
    echo "<br>";
}

function maybe_echo_placeholder_option($initial)
{
    if ("" == $initial) {
        // none of the real options can have this value
        echo "<option value=''>", html_safe(_("Select One")), "</option>\n";
    }
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function proj_comments_format($comment_format)
{
    $comment_format_list = [
        'markdown' => new_window_link('https://www.pgdp.net/wiki/Markdown', 'Markdown'),
        'html' => "HTML",
    ];

    foreach ($comment_format_list as $name => $label) {
        echo "<label><input type='radio'  name='comment_format' value='$name'";
        if (strtolower($comment_format) == $name) {
            echo " CHECKED";
        }
        echo ">";
        echo "$label&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>";
    }
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function proj_comments_field($comments, $field_name)
{
    $enc_comments = html_safe($comments);

    echo "<textarea name='comments' cols='74' rows='16' style='width: 100%'>$enc_comments</textarea>";
    echo "<br>";
    echo "<p class='bold center-align' style='margin: 0'>";
    echo "[";
    echo new_help_window_link('biography.html', _("How To Use A Biography"));
    echo "]";
    echo "</p>";
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function word_lists($value, $fieldname, $projectid)
{
    echo new_window_link(
        "edit_project_word_lists.php?projectid=$projectid",
        _("Edit project word lists")
    );
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function extra_credits_field($extra_credits)
{
    $enc_credits = html_safe($extra_credits);

    echo "<textarea name='extra_credits' cols='74' rows='4' style='width: 100%'>$enc_credits</textarea>";
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX



function description_field($description, $field_name)
{
    $enc_description = html_safe($description);

    echo "<textarea name='$field_name' cols='74' rows='6'>$enc_description</textarea>";
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

// returns an empty string if the possible user exists,
// otherwise an error message
function check_user_exists($possible_user, $description)
{
    $result = '';

    if (!User::is_valid_user($possible_user)) {
        $result = sprintf(_("%s must be an existing user - check case and spelling and ensure there is no trailing whitespace."),
            $description) . "<br>";
    }
    return $result;
}
