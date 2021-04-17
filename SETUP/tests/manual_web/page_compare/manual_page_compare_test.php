<?php
$relPath = "../../../../pinc/";
include_once($relPath.'base.inc'); // require_login()
include_once($relPath.'misc.inc'); // array_get()
include_once($relPath.'theme.inc'); // output_header()
include_once($relPath."PageUnformatter.inc"); // PageUnformatter()

// This page is used to test the remove_formatting() function.
// It is difficult to test otherwise because it requires test-text in two
// different rounds.

require_login();

$L_input = array_get($_POST, "Ltex", "");
$R_input = array_get($_POST, "Rtex", "");
$unwrap = isset($_POST['Unwrap']); // can only be 'on'
$ignore_case = isset($_POST['IgnoreCase']);

$title = _('Test Compare pages with formatting removed');
output_header("$title", NO_STATSBAR);

echo "<h1>$title</h1>\n";

$check_unwrap = $unwrap ? " checked" : "";
$check_igc = $ignore_case ? " checked" : "";
echo "<form method='POST'>
    <p>Paste the texts from the two rounds to compare into the upper two boxes and press 'Go'.
    The results will appear in the two lower boxes and a message will indicate if there are any differences.
    To find the differences the result texts can be copied into a text editor which can compare texts.</p>
    <textarea name='Ltex' rows='10' cols='80'>$L_input</textarea>
    <textarea name='Rtex' rows='10' cols='80'>$R_input</textarea>
    <br>
    Unwrap <input type='checkbox' name='Unwrap'$check_unwrap>
    Ignore case <input type='checkbox' name='IgnoreCase'$check_igc>
    <input type='submit' value='Go'></form>\n";

$un_formatter = new PageUnformatter();
$L_text = $un_formatter->remove_formatting($L_input, $unwrap);
$R_text = $un_formatter->remove_formatting($R_input, $unwrap);

echo "
    <p>Output</p>
    <textarea readonly rows='10' cols='80'>$L_text</textarea>
    <textarea readonly rows='10' cols='80'>$R_text</textarea>
    \n";

if ($ignore_case) {
    $diff = strcasecmp($L_text, $R_text);
} else {
    $diff = strcmp($L_text, $R_text);
}
$no = (0 === $diff) ? " no " : " ";
echo "<p>There are{$no}differences</p>";
