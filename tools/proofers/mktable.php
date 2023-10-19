<?php
$relPath = "../../pinc/";
include_once($relPath."base.inc");
include_once($relPath."misc.inc");

require_login();

$charset = "UTF-8";
header("Content-Type: text/html; charset=$charset");

define("ARRAY_PAD_FRONT", -1);
define("ARRAY_PAD_BACK", 1);
define("ARRAY_PAD_BOTH", 0);

mb_internal_encoding($charset);

$row = array_get($_POST, 'row', 1);
$col = array_get($_POST, 'col', 1);
$bord = array_get($_POST, 'border', 1);
$trim = (array_get($_POST, 'trim', 'off') == 'on');
$clear = array_get($_POST, 'clear', 0);
$vert_align = array_get($_POST, 'vert_align', []);
$horiz_align = array_get($_POST, 'horiz_align', []);
$table_contents = array_get($_POST, 'table_contents', []);

if ($clear) {
    $table_contents = [];
    $vert_align = [];
    $horiz_align = [];
}

// We always need to pad the following fields to ensure
// they are big enough if the table has been resized or cleared.
$table_contents = table_pad($table_contents, $row, $col, "");
$vert_align = array_pad($vert_align, $row, ARRAY_PAD_BACK);
$horiz_align = array_pad($horiz_align, $col, STR_PAD_RIGHT);

?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $charset; ?>">
<title>Table Maker</title>
</head>
<body>
<form method="POST">
<input type="submit" value="Create">
<input type="number" name="row" value="<?php echo $row; ?>" min='1' style='width: 3em;' required> rows and
<input type="number" name="col" value="<?php echo $col; ?>" min='1' style='width: 3em;' required> columns table
<select name="border">
<option value="1"<?php echo $bord ? " selected" : ""; ?>>with</option>
<option value="0"<?php echo $bord ? "" : " selected"; ?>>without</option>
</select> border;
<input type="checkbox" name="trim"<?php echo $trim ? " checked" : ""; ?>> trim spaces.<br>
<table>
<?php

// Output horizontal alignment header row
echo "<tr>";
echo "<td>&nbsp;</td>";
for ($j = 0; $j < $col; $j++) {
    $right_align_checked = ($horiz_align[$j] == STR_PAD_RIGHT) ? " checked" : "";
    $both_align_checked = ($horiz_align[$j] == STR_PAD_BOTH) ? " checked" : "";
    $left_align_checked = ($horiz_align[$j] == STR_PAD_LEFT) ? " checked" : "";
    $pad_right = STR_PAD_RIGHT;
    $pad_both = STR_PAD_BOTH;
    $pad_left = STR_PAD_LEFT;
    echo <<<HORIZ_ALIGN
        <td style='text-align: center;'>
            <input type="radio" name="horiz_align[$j]" value="$pad_right" $right_align_checked>
                <img src="./../../graphics/left.gif" alt="left">
            <input type="radio" name="horiz_align[$j]" value="$pad_both" $both_align_checked>
                <img src="./../../graphics/center.gif" alt="center">
            <input type="radio" name="horiz_align[$j]" value="$pad_left" $left_align_checked>
                <img src="./../../graphics/right.gif" alt="right">
        </td>
        HORIZ_ALIGN;
}
echo "</tr>";

// Output additional rows
$a = [];
$lng = [];
$tll = [];
for ($i = 0; $i < $row; $i++) {
    echo "<tr>";

    // First column is the vertical alignment
    $back_align_checked = ($vert_align[$i] == ARRAY_PAD_BACK) ? " checked" : "";
    $both_align_checked = ($vert_align[$i] == ARRAY_PAD_BOTH) ? " checked" : "";
    $front_align_checked = ($vert_align[$i] == ARRAY_PAD_FRONT) ? " checked" : "";
    $pad_back = ARRAY_PAD_BACK;
    $pad_both = ARRAY_PAD_BOTH;
    $pad_front = ARRAY_PAD_FRONT;
    echo <<<VERT_ALIGN
        <td style='vertical-align: middle;'>
            <input type="radio" name="vert_align[$i]" value="$pad_back" $back_align_checked>
                <img src="./../../graphics/top.gif" alt="top"><br>
            <input type="radio" name="vert_align[$i]" value="$pad_both" $both_align_checked>
                <img src="./../../graphics/middle.gif" alt="middle"><br>
            <input type="radio" name="vert_align[$i]" value="$pad_front" $front_align_checked>
                <img src="./../../graphics/bottom.gif" alt="bottom"><br>
        </td>
        VERT_ALIGN;

    $a[$i] = [];
    for ($j = 0; $j < $col; $j++) {
        $name = "table_contents[$i][$j]";
        $a[$i][$j] = explode("\n", str_replace("\r\n", "\n", $table_contents[$i][$j]));
        if ($trim) {
            $a[$i][$j] = array_values(array_filter($a[$i][$j], "str_not_empty"));
        }
        foreach ($a[$i][$j] as $k => $v) {
            if ($trim) {
                $a[$i][$j][$k] = $v = trim($v);
            }
            $t = mb_strlen($v);
            if (!isset($lng[$j]) || $t > $lng[$j]) {
                $lng[$j] = $t;
            }
        }
        $t = count($a[$i][$j]);
        if (!isset($tll[$i]) || $t > $tll[$i]) {
            $tll[$i] = $t;
        }

        $cell_contents = htmlspecialchars($table_contents[$i][$j], ENT_NOQUOTES, $charset);
        echo "<td>";
        echo "<textarea style='height: 6em; width: 15em; white-space: pre;' ",
        "name='$name'>$cell_contents</textarea>";
        echo "</td>";
    }
    echo "</tr>";
}
?>
</table>
<input type="submit" value="Draw">
<input type="submit" name="clear" value="Clear">
</form>
<form>
<br>
<textarea style='white-space: pre; overflow-x: scroll; font-family: monospace;' rows="20" cols="80">
<?php generate_ascii_table($row, $col, $a, $horiz_align, $tll, $vert_align, $lng, $bord); ?>
</textarea>
</form>
</body>
</html>
<?php

//----------------------------------------------------------------------------

function generate_ascii_table($row, $col, $a, $al, $tll, $val, $lng, $bord)
{
    global $charset;

    if ($bord) {
        hline($col, $lng, $bord);
    }

    for ($i = 0; $i < $row; $i++) {
        for ($j = 0; $j < $col; $j++) {
            $a[$i][$j] = array_pad_internal($a[$i][$j], $tll[$i], $val[$i]);
        }
        for ($k = 0; $k < $tll[$i]; $k++) {
            if ($bord) {
                echo "|";
            }
            for ($j = 0; $j < $col; $j++) {
                echo htmlspecialchars(mb_str_pad($a[$i][$j][$k], @$lng[$j], " ", $al[$j]), ENT_QUOTES, $charset);
                if ($j < $col - 1) {
                    echo "|";
                }
            }
            if ($bord) {
                echo "|";
            }
            echo "\n";
        }

        if ($bord || $i < ($row - 1)) {
            hline($col, $lng, $bord);
        }
    }
}

function hline($col, $lng, $bord)
{
    if ($bord) {
        echo "+";
    }
    for ($j = 0; $j < $col - 1; $j++) {
        echo mb_str_pad("", @$lng[$j], "-")."+";
    }
    echo mb_str_pad("", @$lng[$col - 1], "-");
    if ($bord) {
        echo "+";
    }
    echo "\n";
}

function str_not_empty($str)
{
    return $str !== "";
}

function mb_str_pad($input, $pad_length, $pad_string = " ", $pad_type = STR_PAD_RIGHT, $encoding = null)
{
    if ($encoding == null) {
        $encoding = mb_internal_encoding();
    }
    $l = mb_strlen($input, $encoding);
    if ($pad_length <= $l) {
        return $input;
    }

    switch ($pad_type) {
        case STR_PAD_LEFT:
            for ($i = 0; $i < $pad_length - $l; $i++) {
                $input = $pad_string.$input;
            }
            break;
        case STR_PAD_RIGHT:
            for ($i = 0; $i < $pad_length - $l; $i++) {
                $input .= $pad_string;
            }
            break;
        case STR_PAD_BOTH:
            for ($i = 0; $i < floor(($pad_length - $l) / 2); $i++) {
                $input = $pad_string.$input;
            }
            for ($i = 0; $i < ceil(($pad_length - $l) / 2); $i++) {
                $input .= $pad_string;
            }
            break;
        default:
            break;
    }
    return $input;
}

function array_pad_internal($input, $pad_size, $pad_type)
{
    if ($pad_type == ARRAY_PAD_BOTH) {
        return array_pad(
            array_pad(
                $input,
                ($pad_size - count($input)) / 2 - $pad_size,
                ""
            ),
            $pad_size,
            ""
        );
    } else {
        return array_pad($input, $pad_type * $pad_size, "");
    }
}

function table_pad($table, $rows, $columns, $value = "")
{
    for ($i = 0; $i < $rows; $i ++) {
        if (!isset($table[$i])) {
            $table[$i] = [];
        }
        for ($j = 0; $j < $columns; $j++) {
            if (!isset($table[$i][$j])) {
                $table[$i][$j] = $value;
            }
        }
    }

    return $table;
}
