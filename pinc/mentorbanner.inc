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
    $sql = sprintf(
        "
        SELECT max(round((unix_timestamp() - modifieddate)/(24 * 60 * 60)))
            FROM projects
            WHERE
                difficulty = 'beginner'
                AND state = '%s'
                AND language = '%s'",
        DPDatabase::escape($r_avail_state),
        DPDatabase::escape($eng_lang_name)
    );
    $result = DPDatabase::query($sql);
    [$oldest] = mysqli_fetch_row($result);

    if (is_null($oldest)) {
        return;
    }

    switch ($oldest) {
        case 0:
        case 1:
        case 2:
            $class = 'mentor-recent';
            break;
        case 3:
        case 4:
            $class = 'mentor-older';
            break;
        default:
            $class = 'mentor-oldest';
            break;
    }
    echo "<p class='$class'>";
    printf(
        ngettext(
            /* TRANSLATORS: %4 is the name of the user's UI language in their language;
            %1 is a URL; %2 is the round ID; %3 is the number of days. */
            _("Oldest %4\$s <a href='%1\$s'>MENTORS ONLY</a> book in %2\$s is %3\$d day old."),
            _("Oldest %4\$s <a href='%1\$s'>MENTORS ONLY</a> book in %2\$s is %3\$d days old."),
            $oldest
        ),
        "$code_url/tools/proofers/for_mentors.php",
        $round_id,
        $oldest,
        $lang_name
    );
    echo "</p>";
}
