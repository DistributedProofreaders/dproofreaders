<?php
include_once($relPath."misc.inc"); // html_safe()

$months = [_('Unknown'),
    _('January'), _('February'), _('March'),
    _('April'), _('May'), _('June'),
    _('July'), _('August'), _('September'),
    _('October'), _('November'), _('December'), ];

// Arguments: the data to be displayed. If $author_id is FALSE, no biographies will be displayed.
// If $author_id is not FALSE, all biographies that apply to that author id will be listed
// and a link to the xml data will be provided.
function echo_author($last_name, $other_names, $born, $dead, $author_id)
{
    $name = $last_name . ($other_names != '' ? ", $other_names" : '');

    echo "<h2>" . html_safe($name);
    if ($author_id) {
        echo " <a href='{$GLOBALS['code_url']}/tools/authors/authorxml.php?author_id=$author_id'><img src='{$GLOBALS['code_url']}/graphics/xml.gif' border='0' width='36' height='14' style='vertical-align:middle'></a>\n";
    }
    echo "</h2>";

    echo "<table class='themed' style='width: auto;'>
      <tr><th>Born</th><td>$born</td></tr>
      <tr><th>Deceased</th><td>$dead</td></tr>";

    if ($author_id) {
        // this author actually exists - not only Preview before adding
        // list biographies (if any)
        $sql = sprintf("SELECT bio_id FROM biographies WHERE author_id = %d;", $author_id);
        $result = DPDatabase::query($sql);
        $numrow = mysqli_num_rows($result);
        $row = mysqli_fetch_assoc($result);
        $bio_id = $row["bio_id"];
        if ($numrow == 1) {
            echo "<tr><th>" . _('Biography') . "</th><td><a href=\"bio.php?bio_id=$bio_id\">" . sprintf(_('Biography %d'), $bio_id) . "</a></td></tr>\n";
        } elseif ($numrow > 1) {
            echo "<tr><th rowspan=\"$numrow\" class=\"top-align\">" . _('Biographies') . "</th><td><a href=\"bio.php?bio_id=$bio_id\">" . _('Biography') . " $bio_id</a></td></tr>\n";
            while ($row = mysqli_fetch_assoc($result)) {
                $bio_id = $row["bio_id"];
                echo "<tr><td><a href=\"bio.php?bio_id=$bio_id\">" . sprintf(_("Biography %d"), $bio_id) . "</a></td></tr>\n";
            }
        }
    }
    echo "</table>\n";
}
// end echo_author

// $bd is 'b' or 'd'
function format_date_from_sqlset($row, $bd)
{
    $date = format_ymd($row[$bd.'year'], $row[$bd.'month'], $row[$bd.'day']);
    $comments = $row[$bd."comments"];

    if ($date == null) {
        if ($comments == '') {
            return _('Unknown');
        } else {
            return $comments;
        }
    } else {
        if ($comments == '') {
            return $date;
        } else {
            return "$date $comments";
        }
    }
}

// $bd is 'b' or 'd'
function format_date_from_array($row, $bd)
{
    return format_date($row[$bd.'year'],
                       $row[$bd.'month'],
                       $row[$bd.'day'],
                       $row[$bd.'comments']);
}

function format_date($year, $month, $day, $comments)
{
    $date = format_ymd($year, $month, $day);

    if ($date == null) {
        if ($comments == '') {
            return _('Unknown');
        } else {
            return $comments;
        }
    } else {
        if ($comments == '') {
            return $date;
        } else {
            return "$date $comments";
        }
    }
}

// Possible output:
//   January 28, 1930
//   April, 1954
//   1917
//   123 A. D.
//   17 B. C.
//   null
// Years < 0 : Add B. C.
// Years > 0 : Add A. D. if it's year 150 or earlier (this limit is very subjective).
function format_ymd($y, $m, $d)
{
    global $months;
    // one-letter variables contain database data
    // multi-letters variables contain formatted data
    if ($y > 150) {
        $year = $y;
    } elseif ($y > 0) {
        // TRANSLATORS: Abbreviation for 'anno Domini' (years > 0)
        $year = $y . ' ' . _('A. D.');
    } elseif ($y < 0) {
        // TRANSLATORS: Abbreviation for 'before Christ' (years < 0)
        $year = -$y . ' ' . _('B. C.');
    } else {
        // Year unknown = Everything unknown
        return null;
    }

    if ($m > 0) {
        $month = $months[$m];
    } else {
        // Month unknown = Only year known
        return $year;
    }

    if ($d > 0) {
        $day = $d;
    } else {
        // Day unknown = Year & Month known
        return $month . ', ' . $year;
    }

    // Everything known
    return $month . ' ' . $day . ', ' . $year;
}

// convenience methods to politely throw out a user if s/he's not a manager/editor

// $echo_header-argument should be true if the calling script has not yet output a header.

function abort_if_not_authors_db_manager($echo_header = false)
{
    if (!user_is_authors_db_manager()) {
        if ($echo_header) {
            output_header(_('Sorry!'));
        }
        echo "<h2 class='center-align'>" . _('Sorry!') . "</h2>";
        echo _('You do not have permission to access this page.
                If you believe you should, please contact a system administrator.');
        exit();
    }
}

function abort_if_not_authors_db_editor($echo_header = false)
{
    if (!user_is_authors_db_editor()) {
        if ($echo_header) {
            output_header(_('Sorry!'));
        }
        echo "<h2 class='center-align'>" . _('Sorry!') . "</h2>";
        echo _('You do not have permission to access this page.
                If you believe you should, please contact a system administrator.');
        exit();
    }
}
