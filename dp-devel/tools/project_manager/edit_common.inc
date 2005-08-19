<?PHP
include_once($relPath.'iso_lang_list.inc');

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
    echo "<option value=''>Primary Language</option>";
    for ($i=0;$i<count($array_list);$i++)
    {
        echo "<option value='".encodeFormValue($array_list[$i]['lang_name'])."'";
        if ($pri_language == $array_list[$i]['lang_name']) { echo " SELECTED"; }
        echo ">".$array_list[$i]['lang_name']."</option>";
    }
    echo "</select>&nbsp;&nbsp;<select name='sec_language'>";
    echo "<option value=''>Secondary Language</option>";
    for ($i=0;$i<count($array_list);$i++)
    {
        echo "<option value='".encodeFormValue($array_list[$i]['lang_name'])."'";
        if ($sec_language == $array_list[$i]['lang_name']) { echo " SELECTED"; }
        echo ">".$array_list[$i]['lang_name']."</option>";
    }
    echo "</select>";
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function genre_list($genre)
{
    $array_list = array(
        'Other'=>_('Other'),
        'Adventure'=>_('Adventure'),
        'Agriculture'=>_('Agriculture'),
        'Archaeology'=>_('Archaeology'),
        'Art'=>_('Art'),
        'Animals'=>_('Animals'),
        'Anthropology'=>_('Anthropology'),
        'Architecture'=>_('Architecture'),
        'Astronomy'=>_('Astronomy'),
        'Autobiography'=>_('Autobiography'),
        'Bibliography'=>_('Bibliography'),
        'Biography'=>_('Biography'),
        'Biology'=>_('Biology'),
        'Business'=>_('Business'),
        'Chemistry'=>_('Chemistry'),
        'Collection'=>_('Collection'),
        'Comics'=>_('Comics'),
        'Cooking'=>_('Cooking'),
        'Correspondence'=>_('Correspondence'),
        'Crafts'=>_('Crafts'),
        'Diary'=>_('Diary'),
        'Dictionary'=>_('Dictionary'),
        'Drama'=>_('Drama'),
        'Economics'=>_('Economics'),
        'Education'=>_('Education'),
        'Encyclopedia'=>_('Encyclopedia'),
        'Essay'=>_('Essay'),
        'Folklore'=>_('Folklore'),
        'General Fiction'=>_('General Fiction'),
        'Geology'=>_('Geology'),
        'Grammar'=>_('Grammar'),
        'Health'=>_('Health'),
        'History'=>_('History'),
        'Historical Fiction'=>_('Historical Fiction'),
        'Horror'=>_('Horror'),
        'Horticulture'=>_('Horticulture'),
        'Humor'=>_('Humor'),
        'Instructional'=>_('Instructional'),
        'Juvenile'=>_('Juvenile'),
        'Law'=>_('Law'),
        'Linguistics'=>_('Linguistics'),
        'Literature'=>_('Literature'),
        'Mathematics'=>_('Mathematics'),
        'Medicine'=>_('Medicine'),
        'Military'=>_('Military'),
        'Mixed Form'=>_('Mixed Form'),
        'Music'=>_('Music'),
        'Musicology'=>_('Musicology'),
        'Mystery'=>_('Mystery'),
        'Mythology'=>_('Mythology'),
        'Nature'=>_('Nature'),
        'Natural Science'=>_('Natural Science'),
        'Non-Fiction'=>_('Non-Fiction'),
        'Periodical'=>_('Periodical'),
        'Philosophy'=>_('Philosophy'),
        'Physics'=>_('Physics'),
        'Poetry'=>_('Poetry'),
        'Political Science'=>_('Political Science'),
        'Psychology'=>_('Psychology'),
        'Recreation'=>_('Recreation'),
        'Religious'=>_('Religious'),
        'Reference'=>_('Reference'),
        'Romance'=>_('Romance'),
        'Satire'=>_('Satire'),
        'Science'=>_('Science'),
        'Science Fiction'=>_('Science Fiction'),
        'Short Story'=>_('Short Story'),
        'Sociology'=>_('Sociology'),
        'Speech'=>_('Speech'),
        'Spirituality'=>_('Spirituality'),
        'Sports'=>_('Sports'),
        'Technology'=>_('Technology'),
        'Travel'=>_('Travel'),
        'Veterinary'=>_('Veterinary'),
        'Western'=>_('Western'),
        'Zoology'=>_('Zoology'),
    );

    echo "<select name='genre'>";
    foreach($array_list as $k=>$v)
    {
        echo "<option value='".encodeFormValue($k)."'";
        if ($genre == $k) { echo " SELECTED"; }
        echo ">$v</option>";
    }
    echo "</select>";
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function difficulty_list($difficulty_level)
{
    global $pguser;
    $result = mysql_query("SELECT * FROM users WHERE username = '$pguser'");
    if (mysql_result($result,0,"sitemanager") == "yes") $sa = 1; else $sa = 0;

    $array_list = array('Beginner', 'Easy', 'Average', 'Hard');

    // only show the beginner level to the BEGIN PM or SiteAdmins
    // don't let garvint create EASY projects
    for ($i=0;$i<count($array_list);$i++)
    {
        if (($i > 0) || ($pguser == "BEGIN") || ($sa))
        {
            if (!($pguser == "garvint" && $i == 1))
            {
                echo "<input type='radio' name='difficulty_level' value='".encodeFormValue(strtolower($array_list[$i]))."'";
                if (strtolower($difficulty_level) == strtolower($array_list[$i]))
                {
                    echo " CHECKED";
                }
                echo ">$array_list[$i]&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
            }
        }
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
    echo "<option value=''>NONE</option>";

    echo "<option value='Birthday'";
    if (strncmp ( $special, 'Birthday', 8) == 0)
    {
        echo " SELECTED";
        $bdaymonth = substr($special, 9, 2);
        $bdayday = substr($special, 11, 2);
    }
    echo ">Birthday</option>";

    echo "<option value='Otherday'";
    if (strncmp ( $special, 'Otherday', 8) == 0)
    {
        echo " SELECTED";
        $bdaymonth = substr($special, 9, 2);
        $bdayday = substr($special, 11, 2);
    }
    echo ">Otherday</option>";


    // add the rest of the special days (the "ordinary" special days ;) )
    foreach($specials_array as $k=>$v)
    {
        echo "<option value='".encodeFormValue($k)."'";
        if ($special == $k) { echo " SELECTED"; }
        echo ">$v</option>";
    }
    echo "</select>";

    echo " <a href='show_specials.php'>Special Days Info</a><br>";

    // drop down selects for month and date, used for Birthday and Otherday specials
    echo " Birthday/Otherday: (month) <select name='bdaymonth'>";
    echo "<option value=''></option>";
    $i = 1;
    while ($i <= 12)
    {
        $v = sprintf("%02d", $i);
        echo "<option value='$v'";
        if ($v == $bdaymonth) { echo " SELECTED"; }
        echo ">$v</option>";
        $i++;
    }
    echo "</select>";

    echo " (day) <select name='bdayday'>";
    echo "<option value=''></option>";
    $i = 1;
    while ($i <= 31)
    {
        $v = sprintf("%02d", $i);
        echo "<option value='$v'";
        if ($v == $bdayday) { echo " SELECTED"; }
        echo ">$v</option>";
        $i++;
    }
    echo "</select>";
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function image_provider_list($image_provider)
{
    // get info on image_sources
    $imp_result = mysql_query("
        SELECT code_name, display_name
        FROM image_sources
        WHERE enable = 1
        ORDER BY display_name
    ") or die(mysql_error());

    // it'd be nice to make this static, or something, so it only was loaded once
    $imp_array = array();

    // put list into array
    while ($i_row = mysql_fetch_assoc($imp_result))
    {
        $show = $i_row['display_name'];
        $code = $i_row['code_name'];
        $imp_array["$code"] = $show;
    }

    // drop down select box for which image provider
    echo "<select name='image_provider'>";

    // add special case value "DP User"
    echo "<option value='DP User' ";
    if (strcmp ( $image_provider, 'DP User') == 0) { echo " SELECTED"; }
    echo ">"._("DP User")."</option>";

    // add the pre-defined image_sources
    foreach($imp_array as $k=>$v)
    {
        echo "<option value='".encodeFormValue($k)."'";
        if ($image_provider == $k) { echo " SELECTED"; }
        echo ">$v</option>";
    }

    // add special case value "Other"
    $imp_other_val = '';
    echo "<option value='OTHER' ";
    if (strncmp ( $image_provider, 'O:',2) == 0)
    {
        echo " SELECTED";
        $imp_other_val = substr($image_provider,2);
    }
    echo ">"._("OTHER")."</option>";

    echo "</select>";

    echo " "._("Details for OTHER: ")
        . "<input type='text' size='18' name='imp_other' value='"
        . encodeFormValue($imp_other_val)."'>";

    echo " <a href='show_image_sources.php'>Details of Image Providers</a><br>";
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function proj_comments_field($comments)
{
    $enc_comments = encodeFormValue($comments);
    $template_blurb  = _("How To Use A Template");
    $biography_blurb = _("How To Use A Biography");

    echo "<center>";
    echo "<textarea name='comments' cols='74' rows='16'>$enc_comments</textarea>";
    echo "<br>";
    echo "<b>";
    echo "[<a href=\"JavaScript:newHelpWin('template');\">$template_blurb</a>]";
    echo " ";
    echo "[<a href=\"JavaScript:newHelpWin('biography');\">$biography_blurb</a>]";
    echo "</b>";
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
