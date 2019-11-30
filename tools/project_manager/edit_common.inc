<?php
include_once($relPath.'iso_lang_list.inc');
include_once($relPath.'genres.inc'); // load_genre_translation_array
include_once($relPath.'site_vars.php');
include_once($relPath.'wordcheck_engine.inc'); // get_project_word_file
include_once($relPath.'links.inc'); // new_window_link, new_help_window_link
include_once($relPath.'user_is.inc'); // user_is_a_sitemanager
include_once($relPath.'misc.inc'); // attr_safe(), html_safe(), endswith()
include_once($relPath.'User.inc');

function just_echo( $field_value )
{
    echo html_safe($field_value);
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function text_field( $field_value, $field_name, $args=array())
{
    $maxlength = array_get($args, "maxlength", NULL);
    $maxlength_attr = $maxlength ? "maxlength='$maxlength'" : '';
    $required = array_get($args, "required", false);
    $required_attr = $required ? " required" : "";

    $enc_field_value = attr_safe($field_value);
    echo "<input type='text' size='67' name='$field_name' value='$enc_field_value' $maxlength_attr$required_attr>";
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function DP_user_field( $field_value, $field_name, $args=array())
{
    $required = array_get($args, "required", false);
    $required_attr = $required ? " required" : "";

    $enc_field_value = attr_safe($field_value);
    echo "<input type='text' size='25' name='$field_name' value='$enc_field_value'$required_attr>";
}


// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function language_list($language)
{
    if (strpos($language, "with") > 0)
    {
        $pri_language = trim(substr($language, 0, strpos($language, "with")));
        $sec_language = trim(substr($language, (strpos($language, "with")+5)));
    }
    else
    {
        $pri_language = $language;
        $sec_language = '';
    }

    echo _("Primary"), ": <select name='pri_language' required>\n";
    maybe_echo_placeholder_option($pri_language);
    echo_language_options($pri_language);
    echo "</select><br>\n";
    echo _("Secondary"), ": <select name='sec_language'>\n";
    echo "<option value=''>&nbsp;</option>\n";
    echo_language_options($sec_language);
    echo "</select>\n";
}

function echo_language_options($default)
{
    global $lang_list;

    foreach($lang_list as $language)
    {
        $lang_name = $language['lang_name'];
        $selected_string = ($default == $lang_name) ? " selected" : "";
        echo "<option value='", attr_safe($lang_name), "'$selected_string>$lang_name</option>\n";
    }
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function genre_list($genre)
{
    $genres = load_genre_translation_array();
    echo "<select name='genre' required>\n";
    maybe_echo_placeholder_option($genre);
    foreach($genres as $k=>$v)
    {
        echo "<option value='".attr_safe($k)."'";
        if ($genre == $k) { echo " SELECTED"; }
        echo ">$v</option>";
        echo "\n";
    }
    echo "</select>";
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function difficulty_list($difficulty_level)
{
    global $pguser;

    $difficulty_list = array(
        'beginner' => _("Beginner"), 
        'easy'     => _("Easy"), 
        'average'  => _("Average"), 
        'hard'     => _("Hard")
    );

    // only show the beginner level to the BEGIN PM or SiteAdmins
    if (($pguser != "BEGIN") && (!user_is_a_sitemanager()))
        unset($difficulty_list['beginner']);
        
    foreach ($difficulty_list as $name => $label)
    {
        echo "<input type='radio' name='difficulty_level' value='".attr_safe($name)."'";
        if (strtolower($difficulty_level) == $name)
        {
            echo " CHECKED";
        }
        echo ">$label&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
    }
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function special_list($special)
{
    // get info on special days
    $specs_result = mysqli_query(DPDatabase::get_connection(), "
        SELECT
            spec_code,
            display_name,
            DATE_FORMAT(concat('2000-',open_month,'-',open_day),'%e %b') as 'Start Date'
        FROM special_days
        WHERE enable = 1
        ORDER BY open_month, open_day
    ");

    // it'd be nice to make this static, or something, so it only was loaded once
    $specials_array = array();

    // put list into array
    while ($s_row = mysqli_fetch_assoc($specs_result))
    {
        $show = $s_row['display_name']." (".$s_row['Start Date'].")";
        $code = $s_row['spec_code'];
        $specials_array["$code"] = $show;
    }

    $bdaymonth = 0;
    $bdayday = 0;

    // drop down select box for which special day
    echo "<select name='special_code'>";

    // add special case values first
    echo "<option value=''>&nbsp;</option>";
    echo "\n";

    echo "<option value='Birthday'";
    if (strncmp ( $special, 'Birthday', 8) == 0)
    {
        echo " SELECTED";
        $bdaymonth = substr($special, 9, 2);
        $bdayday = substr($special, 11, 2);
    }
    echo ">", _("Birthday"), "</option>";
    echo "\n";

    echo "<option value='Otherday'";
    if (strncmp ( $special, 'Otherday', 8) == 0)
    {
        echo " SELECTED";
        $bdaymonth = substr($special, 9, 2);
        $bdayday = substr($special, 11, 2);
    }
    echo ">", _("Otherday"), "</option>";
    echo "\n";


    // add the rest of the special days (the "ordinary" special days ;) )
    foreach($specials_array as $k=>$v)
    {
        echo "<option value='".attr_safe($k)."'";
        if ($special == $k) { echo " SELECTED"; }
        echo ">$v</option>";
        echo "\n";
    }
    echo "</select>";

    echo " " . new_window_link( "show_specials.php", _("Special Days Info") );
    echo "<br>";

    // drop down selects for month and date, used for Birthday and Otherday specials
    echo " ", _("Birthday/Otherday: (month)"), " <select name='bdaymonth'>";
    echo "<option value=''>&nbsp;</option>";
    echo "\n";
    $i = 1;
    while ($i <= 12)
    {
        $v = sprintf("%02d", $i);
        echo "<option value='$v'";
        if ($v == $bdaymonth) { echo " SELECTED"; }
        echo ">$v</option>";
        echo "\n";
        $i++;
    }
    echo "</select>";

    echo " ", _("(day)"), " <select name='bdayday'>";
    echo "<option value=''>&nbsp;</option>";
    echo "\n";
    $i = 1;
    while ($i <= 31)
    {
        $v = sprintf("%02d", $i);
        echo "<option value='$v'";
        if ($v == $bdayday) { echo " SELECTED"; }
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
    // get info on image_sources
    $imso_result = mysqli_query(DPDatabase::get_connection(), "
        SELECT code_name, display_name
        FROM image_sources
        WHERE is_active = 1
        ORDER BY display_name
    ") or die(mysqli_error(DPDatabase::get_connection()));

    $imso_array = array(
        // TRANSLATORS: %s is the site abbreviation
        "_internal" => sprintf(_("%s Internal"), $site_abbreviation),
    );

    // put list into array
    while ($i_row = mysqli_fetch_assoc($imso_result))
    {
        $code = $i_row['code_name'];
        // unfortunately, $code can be a numeric value which confuses the php array
        // e.g. 1.5 and 1.6 would both be cast to 1
        // so we append a colon at the end here to force it to be a string and then
        // strip it again later
        $imso_array["$code:"] = $i_row['display_name'];
    }

    // drop down select box for which image source
    echo "<select name='image_source' required>\n";
    maybe_echo_placeholder_option($image_source);
    // add the pre-defined image_sources
    foreach($imso_array as $k=>$v)
    {
        // strip off the string-forcing trailing colon if it exists
        if(endswith($k, ':'))
            $k = substr($k, 0, strlen($k) - 1);
        echo "<option value='".attr_safe($k)."'";
        if ($image_source === $k) { echo " SELECTED"; }
        echo ">$v</option>";
        echo "\n";
    }

    echo "</select> ";
    echo "<span class='small'>". sprintf(_("'%1\$s Internal' if scanned by or for a %1\$s user."),$site_abbreviation)."</span>";
    echo " " . new_window_link( "show_image_sources.php", _("Details of Image Sources") );
    echo "<br>";
}

function maybe_echo_placeholder_option($initial)
{
    if("" == $initial)
    // none of the real options can have this value
    {
        echo "<option value=''>", _("Select One"), "</option>\n";
    }
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function proj_comments_field($comments)
{
    $enc_comments = html_safe($comments);

    echo "<textarea name='comments' cols='74' rows='16' style='width: 100%'>$enc_comments</textarea>";
    echo "<br>";
    echo "<p class='bold center-align' style='margin: 0'>";
    echo "[";
    echo new_help_window_link('template.html', _("How To Use A Template"));
    echo "]";
    echo " ";
    echo "[";
    echo new_help_window_link('biography.html', _("How To Use A Biography"));
    echo "]";
    echo "</p>";
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function word_lists($value,$fieldname,$projectid) {
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



function description_field( $description, $field_name )
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

    if(!User::is_valid_user($possible_user))
    {
        $result = sprintf(_("%s must be an existing user - check case and spelling and ensure there is no trailing whitespace."),
            $description) . "<br>";
    }
    return $result;
}

// vim: sw=4 ts=4 expandtab
