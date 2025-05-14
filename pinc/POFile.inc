<?php

use Symfony\Component\Process\Process;

/**
 * @property-read bool $exists
 * @property-read int|bool $last_modified
 * @property-read string|null $content_type
 * @property-read int|null $messages_count
 * @property-read int|null $messages_translated_count
 */
class POFile
{
    private ?string $po_filename;
    private ?int $num_messages = null;
    private ?int $num_messages_translated = null;

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
            if (str_starts_with($block, "#, fuzzy") or
                str_starts_with($block, "#~ ")) {
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

    private function create_pot_from_php(string $basedir): string
    {
        // create the new template in a temporary file
        if (!$tempfile = tempnam(sys_get_temp_dir(), "xtext")) {
            throw new RuntimeException("Unable to create temporary file");
        }

        // run from the base directory so the filenames in the POT
        // don't have it prefixed to them and write output to temporary file
        $process = new Process([
            "./vendor/bin/xtext",
            "-x", "./vendor",
            "-x", "./SETUP",
            "-x", "./node_modules",
            "-x", "./pinc/3rdparty",
            "-i", "*.php",
            "-i", "*.inc",
            "-c", "TRANSLATORS",
            ".",
        ], $basedir);
        $process->run(function ($type, $buffer) use ($tempfile) {
            if (Process::OUT === $type) {
                file_put_contents($tempfile, $buffer, FILE_APPEND);
            }
        });
        if (!$process->isSuccessful()) {
            unlink($tempfile);
            throw new RuntimeException($process->getErrorOutput());
        }

        // the file we return needs a .pot extension to make xgettext happy
        rename($tempfile, "$tempfile.pot");
        return "$tempfile.pot";
    }

    private function create_pot_from_js(string $basedir): string
    {
        // create the new template in a temporary file
        if (!$pot_tempfile = tempnam(sys_get_temp_dir(), "xgettext-pot")) {
            throw new RuntimeException("Unable to create temporary file");
        }

        // and we need an input file for xgettext
        if (!$input_tempfile = tempnam(sys_get_temp_dir(), "xgettext-input")) {
            throw new RuntimeException("Unable to create temporary file");
        }

        // Find all the JS files we want to translate
        $dir_iter = new RecursiveDirectoryIterator($basedir, RecursiveDirectoryIterator::SKIP_DOTS);

        // Walk through our iterator writing JS filenames to our input file
        if (!$input_fh = fopen($input_tempfile, "w")) {
            throw new RuntimeException("Error opening temporary file for writing");
        }

        foreach (new RecursiveIteratorIterator($dir_iter) as $filename) {
            $filename = str_replace($basedir, ".", $filename);
            if (str_starts_with($filename, "./SETUP") or
                str_starts_with($filename, "./vendor") or
                str_starts_with($filename, "./node_modules") or
                str_starts_with($filename, "./pinc/3rdparty")) {
                continue;
            }
            if (str_ends_with($filename, ".js")) {
                if ($filename == "./eslint.config.js") {
                    continue;
                }
                fwrite($input_fh, "$filename\n");
            }
        }
        fclose($input_fh);

        $process = new Process([
            "xgettext",
            "--language", "javascript",
            "--from-code", "UTF-8",
            "--add-comments", "TRANSLATORS",
            "--sort-by-file",
            "--files-from", $input_tempfile,
            "--output", "-",
        ], $basedir);
        $process->run(function ($type, $buffer) use ($pot_tempfile) {
            if (Process::OUT === $type) {
                file_put_contents($pot_tempfile, $buffer, FILE_APPEND);
            }
        });
        unlink($input_tempfile);
        if (!$process->isSuccessful()) {
            unlink($pot_tempfile);
            throw new RuntimeException($process->getErrorOutput());
        }

        // the file we return needs a .pot extension to make xgettext happy
        rename($pot_tempfile, "$pot_tempfile.pot");
        return "$pot_tempfile.pot";
    }

    public function create_template($basedir, $pot_filename)
    {
        $php_pot = $this->create_pot_from_php($basedir);
        $js_pot = $this->create_pot_from_js($basedir);

        // now merge the two POTs together into a temporary file
        if (!$tempfile = tempnam(sys_get_temp_dir(), "xgettext-merge")) {
            throw new RuntimeException("Unable to create temporary file");
        }

        $process = new Process([
            "xgettext",
            "--sort-by-file",
            "--join-existing",
            "--output", $tempfile,
            $php_pot,
            $js_pot,
        ], $basedir);
        $process->run(function ($type, $buffer) use ($tempfile) {
            if (Process::OUT === $type) {
                file_put_contents($tempfile, $buffer, FILE_APPEND);
            }
        });
        unlink($php_pot);
        unlink($js_pot);
        if (!$process->isSuccessful()) {
            unlink($tempfile);
            throw new RuntimeException($process->getErrorOutput());
        }

        // move our temporary file in place and update the permissions
        if (!rename($tempfile, $pot_filename)) {
            throw new RuntimeException("Unable to rename $tempfile to $pot_filename");
        }
        if (!chmod($pot_filename, 0644)) {
            throw new RuntimeException("Unable to chmod $pot_filename");
        }
    }

    public function create_from_template($pot_filename, $locale)
    {
        $process = new Process([
            "msginit",
            "--no-translator",
            "--input", $pot_filename,
            "--output-file", $this->po_filename,
            "--locale", $locale,
        ]);
        $process->run();
        if (!$process->isSuccessful()) {
            throw new RuntimeException($process->getErrorOutput());
        }
    }

    public function merge_from_template($template, $fuzzy = false)
    {
        $args = [
            "msgmerge",
            "--sort-by-file",
            "--update",
        ];
        if (!$fuzzy) {
            $args[] = "--no-fuzzy-matching";
        }
        $process = new Process(array_merge($args, [$this->po_filename, $template]));
        $process->run();
        if (!$process->isSuccessful()) {
            throw new RuntimeException($process->getErrorOutput());
        }
    }

    public function compile()
    {
        $compiled_filename = str_replace(".po", ".mo", $this->po_filename);

        $process = new Process([
            "msgfmt",
            $this->po_filename,
            "-o", $compiled_filename,
        ]);
        $process->run();
        if (!$process->isSuccessful()) {
            throw new RuntimeException($process->getErrorOutput());
        }
    }

    public function convert_to_json($json_filename)
    {
        global $code_dir;

        $process = new Process([
            "node",
            "node_modules/gettext.js/bin/po2json",
            $this->po_filename,
            $json_filename,
        ], $code_dir);
        $process->run();
        if (!$process->isSuccessful()) {
            throw new RuntimeException($process->getErrorOutput());
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
