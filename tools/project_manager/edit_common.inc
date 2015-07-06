<?php
include_once($relPath.'iso_lang_list.inc');
include_once($relPath.'genres.inc');
include_once($relPath.'site_vars.php');
include_once($relPath.'wordcheck_engine.inc'); // get_project_word_file
include_once($relPath.'links.inc'); // new_window_link, new_help_window_link

function encodeFormValue($value)
{
  return htmlspecialchars($value,ENT_QUOTES);
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function just_echo( $field_value )
{
    echo encodeFormValue($field_value);
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function text_field( $field_value, $field_name )
{
    $enc_field_value = encodeFormValue($field_value);
    echo "<input type='text' size='67' name='$field_name' value='$enc_field_value'>";
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function DP_user_field( $field_value, $field_name )
{
    $enc_field_value = encodeFormValue($field_value);
    echo "<input type='text' size='25' name='$field_name' value='$enc_field_value'>";
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

    $array_list = $GLOBALS['lang_list'];

    echo "<select name='pri_language'>";
    echo "<option value=''>" . _("Primary Language") . "</option>";
    echo "\n";
    for ($i=0;$i<count($array_list);$i++)
    {
        echo "<option value='".encodeFormValue($array_list[$i]['lang_name'])."'";
        if ($pri_language == $array_list[$i]['lang_name']) { echo " SELECTED"; }
        echo ">".$array_list[$i]['lang_name']."</option>";
        echo "\n";
    }
    echo "</select>&nbsp;&nbsp;<select name='sec_language'>";
    echo "<option value=''>" . _("Secondary Language") . "</option>";
    echo "\n";
    for ($i=0;$i<count($array_list);$i++)
    {
        echo "<option value='".encodeFormValue($array_list[$i]['lang_name'])."'";
        if ($sec_language == $array_list[$i]['lang_name']) { echo " SELECTED"; }
        echo ">".$array_list[$i]['lang_name']."</option>";
        echo "\n";
    }
    echo "</select>";
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function genre_list($genre)
{
    global $GENRES;
    echo "<select name='genre'>";
    foreach($GENRES as $k=>$v)
    {
        echo "<option value='".encodeFormValue($k)."'";
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
    $result = mysql_query("SELECT * FROM users WHERE username = '$pguser'");
    if (mysql_result($result,0,"sitemanager") == "yes") $sa = 1; else $sa = 0;

    $difficulty_list = array(
        'beginner' => _("Beginner"), 
        'easy'     => _("Easy"), 
        'average'  => _("Average"), 
        'hard'     => _("Hard")
    );

    // only show the beginner level to the BEGIN PM or SiteAdmins
    if (($pguser != "BEGIN") && (!$sa))
        unset($difficulty_list['beginner']);
        
    foreach ($difficulty_list as $name => $label)
    {
        echo "<input type='radio' name='difficulty_level' value='".encodeFormValue($name)."'";
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
    $specs_result = mysql_query("
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
    while ($s_row = mysql_fetch_assoc($specs_result))
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
    echo "<option value=''>", _("NONE"), "</option>";
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
        echo "<option value='".encodeFormValue($k)."'";
        if ($special == $k) { echo " SELECTED"; }
        echo ">$v</option>";
        echo "\n";
    }
    echo "</select>";

    echo " " . new_window_link( "show_specials.php", _("Special Days Info") );
    echo "<br>";

    // drop down selects for month and date, used for Birthday and Otherday specials
    echo " ", _("Birthday/Otherday: (month)"), " <select name='bdaymonth'>";
    echo "<option value=''></option>";
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
    echo "<option value=''></option>";
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
    $imso_result = mysql_query("
        SELECT code_name, display_name
        FROM image_sources
        WHERE is_active = 1
        ORDER BY display_name
    ") or die(mysql_error());

    // it'd be nice to make this static, or something, so it only was loaded once
    $imso_array = array();

    // put list into array
    while ($i_row = mysql_fetch_assoc($imso_result))
    {
        $show = $i_row['display_name'];
        $code = $i_row['code_name'];
        $imso_array["$code"] = $show;
    }

    // drop down select box for which image source
    echo "<select name='image_source'>";

    // add special case value "x Internal"
    echo "<option value='_internal' ";
    if (strcmp ( $image_source, '_internal') == 0) { echo " SELECTED"; }
    // TRANSLATORS: %s is the site abbreviation
    echo ">".sprintf(_("%s Internal"),$site_abbreviation)."</option>";
    echo "\n";

    // add the pre-defined image_sources
    foreach($imso_array as $k=>$v)
    {
        echo "<option value='".encodeFormValue($k)."'";
        if ($image_source == $k) { echo " SELECTED"; }
        echo ">$v</option>";
        echo "\n";
    }

    echo "</select> ";
    echo "<font size=-1>". sprintf(_("'%1\$s Internal' if scanned by or for a %1\$s user."),$site_abbreviation)."</font>";
    echo " " . new_window_link( "show_image_sources.php", _("Details of Image Sources") );
    echo "<br>";
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function proj_comments_field($comments)
{
    $enc_comments = encodeFormValue($comments);

    echo "<center>";
    echo "<textarea name='comments' cols='74' rows='16' style='width: 100%'>$enc_comments</textarea>";
    echo "<br>";
    echo "<b>";
    echo "[";
    echo new_help_window_link( 'template', _("How To Use A Template") );
    echo "]";
    echo " ";
    echo "[";
    echo new_help_window_link( 'biography', _("How To Use A Biography") );
    echo "]";
    echo "</b>";
    echo "</center>";
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
    $enc_credits = encodeFormValue($extra_credits);

    echo "<center>";
    echo "<textarea name='extra_credits' cols='74' rows='4' style='width: 100%'>$enc_credits</textarea>";
    echo "</center>";
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX



function description_field( $description, $field_name )
{
    $enc_description = encodeFormValue($description);

    echo "<center>";
    echo "<textarea name='$field_name' cols='74' rows='6'>$enc_description</textarea>";
    echo "</center>";
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

// vim: sw=4 ts=4 expandtab

?>
