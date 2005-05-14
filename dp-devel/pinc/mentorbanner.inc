<?
function mentor_banner($round)
{
    $r_avail_state = $round->project_available_state;
    $round_id = $round->id;
    
    $result = (mysql_query("
        SELECT max(round((unix_timestamp() - modifieddate)/(24 * 60 * 60)))
            FROM projects
            WHERE
                difficulty = 'beginner'
                AND state = '$r_avail_state'
                AND language = 'English'
        "));

    $oldest = mysql_result($result, 0);

    if(is_null($oldest))
        return;

    switch ($oldest) {
        case 0:
        case 1:
        case 2:
            $font_boost = 1;
            $font_col = '#339933';
            break;
        case 3:
        case 4:
            $font_boost = 2;
            $font_col = "#FF6600";
        break;
        default:
            $font_boost = 3;
            $font_col = "#FF0000";
            break;
    }

    echo "<br><center>";
    echo "<b><font size=+$font_boost color=$font_col>";
    printf(_("Oldest English %sMENTORS ONLY%s book in %s is %d days old."),
         "<a href='http://www.pgdp.net/phpBB2/viewtopic.php?p=54813#54813'>",
         "</a>",
         $round_id,
         $oldest);
    echo "</font></b>";
    echo "</center><br>";
}
?>