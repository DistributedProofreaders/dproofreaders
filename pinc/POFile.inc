<?php

class POFile
{
    private $po_filename;
    private $num_messages = NULL;
    private $num_messages_translated = NULL;

    public function __construct($po_filename=NULL)
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
        if($this->num_messages === NULL)
            $this->count_translated_strings();
        return $this->num_messages;
    }

    private function get_messages_translated_count()
    {
        if($this->num_messages_translated === NULL)
            $this->count_translated_strings();
        return $this->num_messages_translated;
    }

    // Count the total number of strings and the number of translated strings.
    // Returns: array($count, $translated);
    private function count_translated_strings()
    {
        $fh = fopen($this->po_filename, "rt");
        if(!$fh)
        {
            $this->num_messages = 0;
            $this->num_messages_translated = 0;
            return;
        }

        $block = "";
        $count = 0;
        $translated = 0;
        while (($line = fgets($fh, 4096)) !== false) {
            // skip all comment lines
            if(strpos($line, '#') === 0)
                continue;

            $line = trim($line);
            if($line)
            {
                $block .= "$line\n";
            }
            elseif($block)
            {
                $count++;
                if($this->is_block_translated($block))
                    $translated++;
                $block = "";
            }
        }
        if($block)
        {
            $count++;
            if($this->is_block_translated($block))
                $translated++;
        }
        fclose($fh);

        // The first record is always metadata and flags as translated
        // so our count starts at -1 if there was anything in the file.
        if($count > 0)
        {
            $count = $count - 1;
            $translated = $translated - 1;
        }

        $this->num_messages = $count;
        $this->num_messages_translated = $translated;
    }

    // Given a msgid/msgstr block from a PO file, determine if the block
    // is translated. This is slightly complicated because of multiline
    // strings, hence the gymnastics.
    private function is_block_translated($block)
    {
        list($msgid, $msgstr) = @explode("msgstr", $block);
        $msgstr = "msgstr$msgstr";
        if(strpos($msgstr, 'msgstr ""') === 0 &&
            substr_count($msgstr, "\n") == 1)
            return false;

        return true;
    }

    // Get the full Content-Type from the comment block at the beginning of a PO
    // file (without any trailing newline) or NULL on error.
    private function get_content_type()
    {
        $fh = fopen($this->po_filename, "rt");
        if(!$fh)
            return NULL;

        $content_type = NULL;
        while (($line = fgets($fh, 4096)) !== false) {
            if(stripos($line, "Content-type")) {
                $content_type = str_replace(array('\n', '\r', '"'), "", $line);
                break;
            }
        }
        fclose($fh);

        return $content_type;
    }
}

// vim: sw=4 ts=4 expandtab
