<?php
$relPath='../pinc/';
include_once($relPath.'base.inc');
include_once($relPath.'theme.inc');

output_header("Beginners' Simple Proofreading Rules", NO_STATSBAR);
echo "<br><br>";
include_once($relPath.'simple_proof_text.inc');
echo "<br><br>";
// vim: sw=4 ts=4 expandtab
