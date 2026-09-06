<?php
include_once($relPath.'iso_lang_list.inc');
include_once($relPath.'genres.inc'); // load_genre_translation_array
include_once($relPath.'wordcheck_engine.inc'); // get_project_word_file
include_once($relPath.'links.inc'); // new_window_link
include_once($relPath.'user_is.inc'); // user_is_a_sitemanager
include_once($relPath.'special_colors.inc'); // load_special_days()
include_once($relPath.'User.inc');
include_once($relPath.'CharSuites.inc');
include_once($relPath.'Project.inc'); // load_image_sources()

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function format_text_field(
    ?string $field_value,
    string $field_name,
    ?int $maxlength = null,
    bool $required = false,
    string $type = "text",
    ?int $min = null
): string {
    $maxlength_attr = $maxlength ? "maxlength='$maxlength'" : '';
    $required_attr = $required ? " required" : "";
    $min_attr = isset($min) ? " min='$min'" : "";

    $enc_field_value = attr_safe($field_value);
    return "<input type='$type' style='width: 30em;' name='$field_name' value='$enc_field_value' $maxlength_attr$required_attr$min_attr>"  ;
}


// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function format_DP_user_field(
    string $field_value,
    string $field_name,
    bool $required = false,
): string {
    $required_attr = $required ? " required" : "";

    $enc_field_value = attr_safe($field_value);
    return "<input type='text' size='25' name='$field_name' value='$enc_field_value'$required_attr>";
}


// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function format_language_list(string $language): string
{
    $languages = Project::decode_language($language);
    $pri_language = $languages[0];
    $sec_language = $languages[1] ?? '';

    return join("", [
        html_safe(_("Languages are both shown to the user to select a project and used as dictionaries for WordCheck.")),
        "<br>",
        html_safe(_("Languages without dictionaries will give the proofreader a warning that no dictionary exists in WordCheck.")),
        "<br>",
        new_window_link("../../faq/wordcheck_data.php", _("WordCheck Site Data")),
        "<br><br>",
        html_safe(_("Primary")), ": <select name='pri_language' required>\n",
        format_placeholder_option($pri_language),
        format_language_options($pri_language),
        "</select><br>\n",
        html_safe(_("Secondary")), ": <select name='sec_language'>\n",
        "<option value=''>&nbsp;</option>\n",
        format_language_options($sec_language),
        "</select>\n",
    ]);
}

function format_language_options(string $default): string
{
    $langs_with_dicts = array_flip(get_languages_with_dictionaries());
    ksort($langs_with_dicts);

    $out = [];
    // output the languages with dictionaries first
    $out[] = "<optgroup label='" . attr_safe(_("Languages with dictionaries")) . "'>";
    foreach ($langs_with_dicts as $lang_name => $lang_code) {
        $selected_string = ($default == $lang_name) ? " selected" : "";
        $out[] = "<option value='" . attr_safe($lang_name) . "'$selected_string>" . html_safe($lang_name) . "</option>\n";
    }
    $out[] = "</optgroup>";

    // then everything else
    $out[] = "<optgroup label='" . attr_safe(_("Languages without dictionaries")) . "'>";
    foreach (get_iso_language_list() as $language) {
        $lang_name = $language['lang_name'];
        // skip the language if it was output in the group above
        if (isset($langs_with_dicts[$lang_name])) {
            continue;
        }
        $selected_string = ($default == $lang_name) ? " selected" : "";
        $out[] = "<option value='" . attr_safe($lang_name) . "'$selected_string>" . html_safe($lang_name) . "</option>\n";
    }
    $out[] = "</optgroup>";
    return join("", $out);
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function format_genre_list(string $genre): string
{
    $genres = load_genre_translation_array();
    $out = [];
    $out[] = "<select name='genre' required>\n";
    $out[] = format_placeholder_option($genre);
    foreach ($genres as $k => $v) {
        $out[] = "<option value='".attr_safe($k)."'";
        if ($genre == $k) {
            $out[] = " SELECTED";
        }
        $out[] = ">" . html_safe($v) . "</option>";
        $out[] = "\n";
    }
    $out[] = "</select>";
    return join("", $out);
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

/**
 * @param string[] $charsuites
 * @param CharSuite[] $project_charsuites
 */
function format_charsuite_list(array $charsuites, array $project_charsuites): string
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
    $out = [];
    foreach ($all_charsuites as $k => $v) {
        $out[] = "<label><input type='checkbox' name='charsuites[]' value='".attr_safe($k)."'";
        if (array_key_exists($k, $existing_charsuites)) {
            $out[] = " checked";
        }
        $out[] = ">" . html_safe($v) . "</label><br>";
        $out[] = "\n";
    }
    return join("", $out);
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function format_difficulty_list(string $difficulty_level): string
{
    global $pguser;

    $difficulty_list = get_project_difficulties();

    // only show the beginner level to the BEGIN PM or SiteAdmins
    if (($pguser != "BEGIN") && (!user_is_a_sitemanager())) {
        unset($difficulty_list['beginner']);
    }

    $out = [];
    foreach ($difficulty_list as $name => $label) {
        $out[] = "<label><input type='radio' name='difficulty' value='".attr_safe($name)."'";
        if (strtolower($difficulty_level) == $name) {
            $out[] = " CHECKED";
        }
        $out[] = ">" . html_safe($label) . "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>";
    }
    return join("", $out);
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function format_special_list(string $special): string
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

    $out = [];

    // drop down select box for which special day
    $out[] = "<select name='special_code'>";

    // add special case values first
    $out[] = "<option value=''>&nbsp;</option>\n";

    $out[] = "<option value='Birthday'";
    if (strncmp($special, 'Birthday', 8) == 0) {
        $out[] = " SELECTED";
        $bdaymonth = substr($special, 9, 2);
        $bdayday = substr($special, 11, 2);
    }
    $out[] = ">" . html_safe(_("Birthday")) . "</option>\n";

    $out[] = "<option value='Otherday'";
    if (strncmp($special, 'Otherday', 8) == 0) {
        $out[] = " SELECTED";
        $bdaymonth = substr($special, 9, 2);
        $bdayday = substr($special, 11, 2);
    }
    $out[] = ">" . html_safe(_("Otherday")) . "</option>\n";

    // add the rest of the special days (the "ordinary" special days ;) )
    foreach ($specials_array as $k => $v) {
        $out[] = "<option value='".attr_safe($k)."'";
        if ($special == $k) {
            $out[] = " SELECTED";
        }
        $out[] = ">" . html_safe($v) . "</option>\n";
    }
    $out[] = "</select>";

    $out[] = " " . new_window_link("show_specials.php", _("Special Days Info"));
    $out[] = "<br>";

    // drop down selects for month and date, used for Birthday and Otherday specials
    $out[] = " " . html_safe(_("Birthday/Otherday: (month)")) . " <select name='bdaymonth'>";
    $out[] = "<option value=''>&nbsp;</option>\n";
    $i = 1;
    while ($i <= 12) {
        $v = sprintf("%02d", $i);
        $out[] = "<option value='$v'";
        if ($v == $bdaymonth) {
            $out[] = " SELECTED";
        }
        $out[] = ">$v</option>\n";
        $i++;
    }
    $out[] = "</select>";

    $out[] = " " . html_safe(_("(day)")) . " <select name='bdayday'>";
    $out[] = "<option value=''>&nbsp;</option>\n";
    $i = 1;
    while ($i <= 31) {
        $v = sprintf("%02d", $i);
        $out[] = "<option value='$v'";
        if ($v == $bdayday) {
            $out[] = " SELECTED";
        }
        $out[] = ">$v</option>\n";
        $i++;
    }
    $out[] = "</select>";
    return join("", $out);
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function format_image_source_list(string $image_source): string
{
    $imso_array = [];
    foreach (load_image_sources() as $code => $source) {
        if (($source["is_active"] == 1 && can_user_see_image_source($source)) ||
            $code == $image_source) {
            // $code can be a numeric value so we cast it to a string
            $imso_array[(string)$code] = $source['display_name'];
        }
    }

    $out = [];
    // drop down select box for which image source
    $out[] = "<select name='image_source' required>\n";
    $out[] = format_placeholder_option($image_source);
    // add the pre-defined image_sources
    foreach ($imso_array as $k => $v) {
        $out[] = "<option value='".attr_safe($k)."'";
        if ($image_source === $k) {
            $out[] = " SELECTED";
        }
        $out[] = ">" . html_safe($v) . "</option>";
        $out[] = "\n";
    }

    $out[] = "</select> ";
    $out[] = "<span class='small'>". html_safe(sprintf(_("'%1\$s Internal' if scanned by or for a %1\$s user."), SiteConfig::get()->site_abbreviation))."</span>";
    $out[] = " " . new_window_link("show_image_sources.php", _("Details of Image Sources"));
    $out[] = "<br>";
    return join("", $out);
}

function format_placeholder_option(string $initial): string
{
    if ("" == $initial) {
        // none of the real options can have this value
        return "<option value=''>" . html_safe(_("Select One")) . "</option>\n";
    } else {
        return "";
    }
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX
/** @param 'markdown'|'html' $comment_format */
function format_proj_comments_format(string $comment_format): string
{
    $comment_format_list = [
        'markdown' => new_window_link('https://www.pgdp.net/wiki/Markdown', 'Markdown'),
        'html' => "HTML",
    ];

    $out = [];
    foreach ($comment_format_list as $name => $label) {
        $out[] = "<label><input type='radio'  name='comment_format' value='$name'";
        if (strtolower($comment_format) == $name) {
            $out[] = " CHECKED";
        }
        $out[] = ">";
        $out[] = "$label&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>";
    }
    return join("", $out);
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function format_proj_comments_field(string $comments): string
{
    $enc_comments = html_safe($comments);

    return "<textarea name='comments' cols='74' rows='16' style='width: 100%'>$enc_comments</textarea>";
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function format_word_lists(string $projectid): string
{
    return new_window_link(
        "edit_project_word_lists.php?projectid=$projectid",
        _("Edit project word lists")
    );
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function format_extra_credits_field(string $extra_credits): string
{
    $enc_credits = html_safe($extra_credits);

    return "<textarea name='extra_credits' cols='74' rows='4' style='width: 100%'>$enc_credits</textarea>";
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX
