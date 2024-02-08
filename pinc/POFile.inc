<?php

class POFile
{
    private $po_filename;
    private $num_messages = null;
    private $num_messages_translated = null;

    public function __construct($po_filename = null)
    {
        $this->po_filename = $po_filename;
    }

    public function __get($name)
    {
        $func = "get_$name";
        return $this->$func();
    }

    private function get_exists()
    {
        return file_exists($this->po_filename);
    }

    private function get_last_modified()
    {
        return filemtime($this->po_filename);
    }

    private function get_messages_count()
    {
        if ($this->num_messages === null) {
            $this->count_translated_strings();
        }
        return $this->num_messages;
    }

    private function get_messages_translated_count()
    {
        if ($this->num_messages_translated === null) {
            $this->count_translated_strings();
        }
        return $this->num_messages_translated;
    }

    /**
     * Count the total number of strings and the number of translated strings.
     */
    private function count_translated_strings()
    {
        $iterator = new POFileIterator($this->po_filename);
        $count = 0;
        $translated = 0;
        foreach ($iterator as $block) {
            // if the block is a saved (but not used) fuzzy translation,
            // skip it
            if (startswith($block, "#, fuzzy") or
                startswith($block, "#~ ")) {
                continue;
            }

            $count++;
            if ($this->is_block_translated($block)) {
                $translated++;
            }
        }

        // The first record is always metadata and flags as translated
        // so our count starts at -1 if there was anything in the file.
        if ($count > 0) {
            $count = $count - 1;
            $translated = $translated - 1;
        }

        $this->num_messages = $count;
        $this->num_messages_translated = $translated;
    }

    /**
     * Given a msgid/msgstr block from a PO file, determine if the block
     * is translated.
     *
     * This is slightly complicated because of multiline strings, hence the gymnastics.
     */
    private function is_block_translated($block)
    {
        [$msgid, $msgstr] = @explode("msgstr", $block);
        $msgstr = "msgstr$msgstr";
        if (strpos($msgstr, 'msgstr ""') === 0 &&
            substr_count($msgstr, "\n") == 1) {
            return false;
        }

        return true;
    }

    /**
     * Get the full Content-Type from the comment block at the beginning of a PO
     * file (without any trailing newline) or NULL on error.
     */
    private function get_content_type()
    {
        $fh = fopen($this->po_filename, "rt");
        if (!$fh) {
            return null;
        }

        $content_type = null;
        while (($line = fgets($fh, 4096)) !== false) {
            if (stripos($line, "Content-type")) {
                $content_type = str_replace(['\n', '\r', '"'], "", $line);
                break;
            }
        }
        fclose($fh);

        return $content_type;
    }

    public function create_from_template($pot_filename, $locale)
    {
        $cmd = join(" ", [
            "msginit",
            "--no-translator",
            "--input=" . escapeshellarg($pot_filename),
            "--output-file=" . escapeshellarg($this->po_filename),
            "--locale=" . escapeshellarg($locale),
        ]);
        exec($cmd, $exec_out, $return_value);

        if ($return_value != 0) {
            throw new Exception(implode("\n", $exec_out));
        }
    }

    public function merge_from_template($template, $fuzzy = false)
    {
        $cmd = join(" ", [
            "msgmerge",
            "--sort-by-file",
            "-U ",
            ($fuzzy ? "" : "-N"),
            escapeshellarg($this->po_filename),
            escapeshellarg($template),
            "2>&1",
        ]);
        exec($cmd, $exec_out, $return_value);

        if ($return_value != 0) {
            throw new Exception(implode("\n", $exec_out));
        }
    }

    public function compile()
    {
        $compiled_filename = str_replace(".po", ".mo", $this->po_filename);

        $cmd = join(" ", [
            "msgfmt",
            escapeshellarg($this->po_filename),
            "-o " . escapeshellarg($compiled_filename),
            "2>&1",
        ]);
        exec($cmd, $exec_out, $return_value);

        if ($return_value != 0) {
            throw new Exception(implode("\n", $exec_out));
        }
    }
}


class POFileIterator implements Iterator
{
    private $po_filename;
    private $file_handle = null;
    private $current_block = null;
    private $block_count = -1;

    public function __construct($po_filename)
    {
        $this->po_filename = $po_filename;
    }

    public function rewind(): void
    {
        if ($this->file_handle) {
            fseek($this->file_handle, 0);
        } else {
            $this->file_handle = fopen($this->po_filename, "rt");
        }

        $this->next();
    }

    public function current(): string
    {
        return $this->current_block;
    }

    public function key(): mixed
    {
        return $this->block_count;
    }

    public function next(): void
    {
        if (!$this->file_handle) {
            return;
        }

        $block = "";
        while (($line = fgets($this->file_handle, 4096)) !== false) {
            $line = trim($line);
            if ($line) {
                $block .= "$line\n";
            } elseif ($block) {
                $this->current_block = $block;
                $this->block_count++;
                return;
            }
        }
        if ($block) {
            $this->current_block = $block;
            $this->block_count++;
            return;
        }
        $this->current_block = null;
        fclose($this->file_handle);
        $this->file_handle = null;
    }

    public function valid(): bool
    {
        return ($this->current_block != null);
    }

    public function __destruct()
    {
        if ($this->file_handle) {
            fclose($this->file_handle);
        }
    }
}
