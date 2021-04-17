<?php

// stdin is assumed to be the output of mysqldump.
// Rearrange its per-table chunks so that they
// are in alphabetical order by table name.
//
// (In MySQL 4, mysqldump would itself order the per-table chunks,
// but in MySQL 5, the order is indeterminate.  This makes
// comparing two dumps difficult, hence the need for this script.)

// You could almost do this with a one-line shell pipeline:
// sed 's/-- Table structure/\x00-- Table structure/' | sort --zero-terminated | sed 's/\x00//'
// However that has a few problems:
// a) It assumes/requires a sed that can handle \x00.
// b) The postamble stays attached to the input's last table,
//    so in general is misplaced in the output.
// c) It's somewhat fragile on where the preamble sorts to.

$lines = file('php://stdin');
if ($lines === false) {
    die('file() returned FALSE');
}
$text = implode('', $lines);

$chunks = preg_split('/(?m)^(?=--\n-- Table structure )/', $text);

// Pull out the preamble.
$preamble = array_shift($chunks);

// Pull out the postamble.
$i = count($chunks) - 1;
if (preg_match('#(?ms)(.*?)^(/\*.*)#', $chunks[$i], $matches)) {
    $chunks[$i] = $matches[1] . "\n";
    $postamble = $matches[2];
} else {
    $postamble = '';
}

// For each table-chunk, extract the table-name.
$named_chunks = [];
foreach ($chunks as $chunk) {
    preg_match('/^--\n-- Table structure for table `(.*)`/', $chunk, $matches);
    $table_name = $matches[1];
    $named_chunks[$table_name] = $chunk;
}

// Sort all the table-chunks by table-name.
ksort($named_chunks);

// And now output everything.
echo $preamble;
foreach ($named_chunks as $chunk) {
    echo $chunk;
}
echo $postamble;
