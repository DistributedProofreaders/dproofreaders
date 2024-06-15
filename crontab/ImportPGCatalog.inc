<?php
include_once($relPath.'pg.inc');

// Timing requirements: This script can run anytime, but probably daily.

/*
 * Download the XML version of the Project Gutenberg catalog,
 * extract desired data from it, and put that into a MySQL table.
 *
 * The extracted data itself is transient and deleted after being processed.
 */
class ImportPGCatalog extends BackgroundJob
{
    private string $local_catalog_dir;

    private array $display_mapping = [
        'application/epub+zip' => 'EPUB',
        'application/msword' => 'MS Word',
        'application/ogg' => 'Ogg Audio',
        'application/pdf' => 'PDF',
        'application/postscript' => 'Postscript',
        'application/prs.plucker' => 'Plucker',
        'application/prs.tei' => 'TEI',
        'application/prs.tex' => 'TeX',
        'application/vnd.palm' => 'Palm',
        'application/x-iso9660-image' => 'ISO CD/DVD Image',
        'application/x-mobipocket-ebook' => 'Mobipocket',
        'application/x-mslit-ebook' => 'MS Lit for PocketPC',
        'application/x-qioo-ebook' => 'QiOO',
        'application/x-tomeraider-ebook' => 'TomeRaider eBook',
        'application/xml' => 'XML',
        'application/rdf+xml' => 'RDF',
        'audio/midi' => 'MIDI',
        'audio/mp4' => 'MP4 Audio',
        'audio/mpeg' => 'MPEG Audio',
        'audio/ogg' => 'Ogg Audio',
        'audio/x-ms-wma' => 'MS Audio',
        'audio/x-wav' => 'MS Wave Audio',
        'image/gif' => 'GIF',
        'image/jpeg' => 'JPEG',
        'image/png' => 'PNG',
        'image/svg+xml' => 'SVG Image',
        'image/tiff' => 'TIFF',
        'text/css' => 'CSS Stylesheet',
        'text/html' => 'HTML',
        'text/plain' => 'Text',
        'text/rtf' => 'RTF',
        'text/x-rst' => 'reStructuredText',
        'text/xml' => 'XML',
        'video/mpeg' => 'MPEG Video',
        'video/quicktime' => 'Quicktime Video',
        'video/x-msvideo' => 'MS Video',
    ];

    public function work()
    {
        set_time_limit(300);

        // determine temporary directory for download and extraction
        $this->local_catalog_dir = sys_get_temp_dir() . "/pg-catalog-files";
        if (!is_dir($this->local_catalog_dir)) {
            if (!mkdir($this->local_catalog_dir, 0777, true /* recursive */)) {
                throw new RuntimeException("Unable to create {$this->local_catalog_dir}");
            }
        }

        $this->download_and_extract_catalog();
        $this->process_catalog();

        // now delete $local_catalog_dir
        array_map('unlink', glob("{$this->local_catalog_dir}/*"));
        if (!rmdir($this->local_catalog_dir)) {
            throw new RuntimeException("Unable to remove {$this->local_catalog_dir}");
        }
    }

    private function download_and_extract_catalog()
    {
        global $PG_home_url;

        $remote_catalog_url = "$PG_home_url/cache/epub/feeds/rdf-files.tar.bz2";
        $local_compressed_file = "{$this->local_catalog_dir}/rdf-files.tar.bz2";

        echo "Downloading $remote_catalog_url to $local_compressed_file...\n";
        if (!copy($remote_catalog_url, $local_compressed_file)) {
            throw new RuntimeException("Unable to download $remote_catalog_url to $local_compressed_file");
        }

        echo "Extracting files from $local_compressed_file to {$this->local_catalog_dir}...\n";
        $cmd = join(" ", [
            "tar",
            "--extract",
            "--bzip2",
            "--file=" . escapeshellarg($local_compressed_file),
            "--strip-components=3",
            "--directory=" . escapeshellarg($this->local_catalog_dir),
            "--overwrite",
        ]);
        system($cmd, $ret);
        // Each file in the tar archive describes one ebook in the PG collection,
        // and has a path of the form:
        //     cache/epub/NNN/pgNNN.rdf
        // where "NNN" is the etext-number of the file's ebook.
        // We use --strip-components=3 to strip off the
        //     cache/epub/NNN
        // part, and --directory=$local_catalog_dir so that we end up with
        //     $local_catalog_dir/pgNNN.rdf
        if ($ret) {
            throw new RuntimeException("Unable to extract files from $local_compressed_file to {$this->local_catalog_dir}");
        }

        // We're done with the compressed file so delete it
        unlink($local_compressed_file);
    }

    private function process_catalog()
    {
        $etexts = [];
        $mime_types_not_in_display_mapping = [];

        echo "Scanning files in {$this->local_catalog_dir}...\n";

        $n_rdf_files_processed = 0;
        $n_rdf_files_skipped = 0;
        foreach (scandir($this->local_catalog_dir) as $filename) {
            if ($filename == '.' || $filename == '..') {
                continue;
            }

            if (! preg_match('/^pg(\d+)\.rdf$/', $filename, $matches)) {
                echo "Skipping file not matching expected PG RDF format: $filename\n";
                continue;
            }

            $etext_number = $matches[1];

            $display_formats = [];

            $path = "{$this->local_catalog_dir}/$filename";
            $root = simplexml_load_file($path);
            if ($root === false) {
                // Something went wrong in simplexml_load_file.
                // Likely the content of the file at $path is not well-formed XML.
                // In particular, it might be empty or otherwise incomplete
                // due to a problem with the downloading or unpacking.

                // Just skip the file.
                $n_rdf_files_skipped += 1;
                continue;
            }

            // $etext_num_xpath = "/rdf:RDF/pgterms:ebook/@rdf:about";
            $format_xpath = "
                /rdf:RDF
                    /pgterms:ebook
                        /dcterms:hasFormat
                            /pgterms:file
                                /dcterms:format
                                    /rdf:Description
                                        /rdf:value";
            $nodes = $root->xpath($format_xpath);
            if ($nodes === false) {
                // The docs say that SimpleXML::path() returns FALSE "in case of an error",
                // but it doesn't say what constitutes an error.
                // And there doesn't appear to be a programmatic way
                // to find out what the specific error is.
                //
                // Annoyingly, it looks like some versions of SimpleXML::path()
                // return FALSE when there's no error per se,
                // but the XPath expression simply doesn't match any nodes.
                // (In other versions, xpath() more sensibly returns an empty array.)
                //
                // We'll just assume that that's what's happened here.
                // That is, no nodes in this file match $format_xpath.
                // This is almost certainly because the file is a placeholder,
                // i.e. $etext_number has not yet been assigned to an ebook
                // (or perhaps once *was* assigned, but got de-assigned).
                //
                // In such a case, it shouldn't really matter whether or not
                // we put an entry for $etext_number in the pg_books table.
                // For simplicity of coding, we just skip the file here.

                $n_rdf_files_skipped += 1;
                continue;

                // But note that, with a more sensible version of SimpleXML::path(),
                // such a case instead iterates over the empty array,
                // and *does* end up putting an entry in the pg_books table,
                // albeit one with an empty 'formats' value
            }
            foreach ($nodes as $format_value_element) {
                $format = (string)$format_value_element;

                if ($format == 'image/jpeg') {
                    // This pgterms:file is a cover image or some such
                    // (which isn't distinguished from files
                    // that actually convey the content of the book).
                    continue;
                }

                if ($format == 'application/zip') {
                    // This dcterms:format just conveys that the file is compressed.
                    // A different dcterms:format in the same pgterms:file
                    // gives the format of the uncompressed file.
                    continue;
                }

                if (preg_match('#^([^;]+);\s*[a-z]+=([^"]+)$#', $format, $format_groups)) {
                    $mime_type = $format_groups[1];
                    $sub_type = $format_groups[2];
                } else {
                    $mime_type = $format;
                    $sub_type = "";
                }

                if ($mime_type == 'application/octet-stream') {
                    // If there is no subtype, continue instead of returning
                    // an empty display format
                    if (empty($sub_type)) {
                        continue;
                    }
                    $display_format = $sub_type;
                } else {
                    $display_format = $this->display_mapping[$mime_type] ?? null;
                    if (empty($display_format)) {
                        @$mime_types_not_in_display_mapping[$mime_type] += 1;
                        $display_format = $mime_type;
                    }

                    if ($sub_type) {
                        $display_format .= " ($sub_type)";
                    }
                }
                $display_formats[$display_format] = 1;
            }

            if (isset($etexts[$etext_number])) {
                echo "Error: Found duplicate etext_number $etext_number, skipping duplicate record\n";
                continue;
            }
            $etexts[$etext_number] = $display_formats;
            $n_rdf_files_processed += 1;
        }

        echo "Finished processing $n_rdf_files_processed RDF files.\n";
        if ($n_rdf_files_skipped) {
            echo "Skipped $n_rdf_files_skipped RDF files, probably just placeholders.\n";
        }

        if (count($mime_types_not_in_display_mapping) > 0) {
            echo "Warning: The following MIME types do not have entries in \$display_mapping:\n";
            foreach ($mime_types_not_in_display_mapping as $mime_type => $count) {
                echo sprintf("    %3d %s\n", $count, $mime_type);
            }
        }

        echo "Putting the data into the table...\n";

        ksort($etexts); // sort numerically by $etext_number
        foreach ($etexts as $etext_number => $formats) {
            ksort($formats); // sort alphabetically by format string
            $formats_string = implode('; ', array_keys($formats));

            $sql = sprintf("
                REPLACE INTO pg_books
                SET etext_number = %d, formats='%s'
            ", $etext_number, DPDatabase::escape($formats_string));
            DPDatabase::query($sql);
        }

        $this->stop_message = sprintf("Processed %d etexts", count($etexts));
    }
}
