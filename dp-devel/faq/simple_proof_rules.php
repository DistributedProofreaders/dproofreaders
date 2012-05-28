<?php
$relPath='../pinc/';
include_once($relPath.'base.inc');
include_once($relPath.'theme.inc');
include_once($relPath.'misc.inc'); // undo_all_magic_quotes()

undo_all_magic_quotes();

$no_stats=1;
theme("Beginners' Simple Proofreading Rules",'header');
echo "<br><br>";
include_once($relPath.'simple_proof_text.inc');
echo "<br><br>";
theme('','footer');
// vim: sw=4 ts=4 expandtab
