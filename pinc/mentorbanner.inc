<?php
function mentor_banner($round)
{
    global $code_url;

    $r_avail_state = $round->project_available_state;
    $round_id = $round->id;

    // Get the English language name for the user's UI language
    $two_letter_lang_code = substr(get_desired_language(), 0, 2);
    $eng_lang_name = eng_name($two_letter_lang_code);
    $lang_name = lang_name($two_letter_lang_code);

    // We store the name of the project's language in the 'language' column
    // in its English form, hence the use of $eng_lang_name below.
    $result = mysqli_query(DPDatabase::get_connection(), sprintf("
        SELECT max(round((unix_timestamp() - modifieddate)/(24 * 60 * 60)))
            FROM projects
            WHERE
                difficulty = 'beginner'
                AND state = '$r_avail_state'
                AND language = '$eng_lang_name'
        "));

    $row = mysqli_fetch_row($result);
    $oldest = $row[0];

    if(is_null($oldest))
        return;

    switch ($oldest) {
        case 0:
        case 1:
        case 2:
            $font_boost = "1em";
            $font_col = '#339933';
            break;
        case 3:
        case 4:
            $font_boost = "large";
            $font_col = "#FF6600";
            break;
        default:
            $font_boost = "x-large";
            $font_col = "#FF0000";
            break;
    }

    echo "<p style='color: $font_col; font-weight: bold; font-size: $font_boost; text-align: center'>";
    printf(ngettext(
            /* TRANSLATORS: %4 is the name of the user's UI language in their language;
            %1 is a URL; %2 is the round ID; %3 is the number of days. */
            _("Oldest %4\$s <a href='%1\$s'>MENTORS ONLY</a> book in %2\$s is %3\$d day old."),
            _("Oldest %4\$s <a href='%1\$s'>MENTORS ONLY</a> book in %2\$s is %3\$d days old."),
            $oldest),
        "$code_url/tools/proofers/for_mentors.php",
        $round_id,
        $oldest,
        $lang_name);
    echo "</p>";
}
