<?php
include_once($relPath.'misc.inc'); // startswith()

function parse_po($messages_po_filename) {
    $p = 0;
    $translation['comments'] = "";
    $comments_done = 0;

    // open file for reading
    $fh = fopen($messages_po_filename, "rt");

    // if we're unable to open the file, return NULL
    if(!$fh)
        return NULL;

    // parse the file one line at a time
    while(!feof($fh))
    {
        $line = fgets($fh);

        if (!$comments_done && !startswith($line,"# "))
            $comments_done = 1;

        if (startswith($line, "# ") && $comments_done != 1) {
            $translation['comments'] .= trim(preg_replace("/^#/", "", $line))."\n";
        }
        if (startswith($line, "#:")) {
            while (!empty($line) && (startswith($line, "#:") || startswith($line, "#,"))) {
                if (!isset($translation['location'][$p])) { $translation['location'][$p] = ""; }
                $formatted_location = trim($line);
                $translation['location'][$p] .= $formatted_location."\n";
                $line = fgets($fh);
            }

            while (!empty($line) && (startswith($line, "msgid") || startswith($line, "\""))) {
                if (!isset($translation['msgid'][$p])) { $translation['msgid'][$p] = ""; }
                $formatted_msgid = trim(preg_replace("/^msgid/", "", $line));
                $formatted_msgid = substr($formatted_msgid, 1, (strlen($formatted_msgid)-2));
                $translation['msgid'][$p] .= $formatted_msgid;
                $line = fgets($fh);
            }

            while (!empty($line) && (startswith($line, "msgstr") || startswith($line, "\""))) {
                if (!isset($translation['msgstr'][$p])) { $translation['msgstr'][$p] = ""; }
                $formatted_msgstr = trim(preg_replace("/^msgstr/", "", $line));
                $formatted_msgstr = substr($formatted_msgstr, 1, (strlen($formatted_msgstr)-2));
                $translation['msgstr'][$p] .= $formatted_msgstr;
                $line = fgets($fh);
            }
            $p++;
        }
    }
    return $translation;
}

// vim: sw=4 ts=4 expandtab
