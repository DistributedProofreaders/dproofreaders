<?php
$relPath='../pinc/';
include_once($relPath.'misc.inc');
include_once($relPath.'v_site.inc');
include_once($relPath.'connect.inc');
new dbConnect();

// Download the XML version of the Project Gutenberg catalog,
// extract desired data from it, and put that into a MySQL table.
//
// So far, the only desired data is the list of formats
// in which each etext is available.
//
// This script should probably be invoked nightly via crontab.

header('Content-type: text/plain');

$display_mapping = array(
    'application/pdf'          => 'PDF',
    'application/postscript'   => 'Postscript',
    'application/vnd.palm'     => 'Palm',
    'audio/mpeg'               => 'MPEG Audio',
    'image/gif'                => 'GIF',
    'image/jpeg'               => 'JPEG',
    'image/png'                => 'PNG',
    'image/tiff'               => 'TIFF',
    'text/html'                => 'HTML',
    'text/plain'               => 'Text',
    'text/rtf'                 => 'RTF',
    'text/xml'                 => 'XML',
    'video/mpeg'               => 'MPEG Video',
    'video/quicktime'          => 'Quicktime Video'
);

$trace = FALSE;
$start_from_scratch = TRUE;

$remote_catalog_url = "http://www.gutenberg.org/browse/rdf/catalog.rdf.bz2";
$local_temp_file    = "/tmp/catalog.rdf.bz2";
$local_catalog_file = "$dyn_dir/pg/catalog.rdf";

if ($start_from_scratch)
{
    if ($trace) echo "Downloading $remote_catalog_url to $local_temp_file...\n";
    copy( $remote_catalog_url, $local_temp_file ) or
        die( "Unable to download $remote_catalog_url to $local_temp_file" );

    $local_catalog_dir = dirname($local_catalog_file);
    mkdir_recursive( $local_catalog_dir, 0777 );

    if ($trace) echo "Decompressing $local_temp_file to $local_catalog_file...\n";
    system( "bzip2 --decompress --stdout $local_temp_file > $local_catalog_file", $ret );
    if ($ret)
    {
        die( "Unable to decompress $local_temp_file to $local_catalog_file" );
    }
}

$etexts = array();

if ($trace) echo "Reading $local_catalog_file...\n";
$fp = fopen($local_catalog_file, "r");
if ( !$fp )
{
    die( "Unable to open $local_catalog_file" );
}

$display_format = null;
while( $line = fgets( $fp ) )
{
    if ( preg_match( '#<dcterms:format>(.*)</dcterms:format>#', $line, $line_groups ) )
    {
        $format = $line_groups[1];
        if ( $format != 'application/zip' )
        {
            assert( is_null($display_format) );

            // echo $format, "\n";
            if ( preg_match( '#^([^;]+);\s*[a-z]+="([^"]+)"$#', $format, $format_groups ) )
            {
                $mime_type = $format_groups[1];
                $sub_type  = $format_groups[2];
            }
            else
            {
                $mime_type = $format;
                $sub_type  = "";
            }

            if ( $mime_type == 'application/octet-stream' )
            {
                $display_format = $sub_type;
            }
            else
            {
                $display_format = $display_mapping[$mime_type];
                if ( $sub_type )
                {
                    $display_format .= " ($sub_type)";
                }
            }
            // echo $display_format, "\n";
        }
    }
    else if ( preg_match( '@<dcterms:isFormatOf rdf:resource="#etext(\d+)" />@', $line, $line_groups ) )
    {
        $etext_number = $line_groups[1];
        assert( !is_null($display_format) );
        // echo "$etext_number $display_format \n";
        if (!isset($etexts[$etext_number]))
        {
            $etexts[$etext_number] = array();
        }
        $etexts[$etext_number][$display_format] = 1;
        $display_format = null;
    }
}
fclose($fp);

if ($trace) echo "Putting the data into the table...\n";

ksort($etexts); // sort numerically by $etext_number
foreach ($etexts as $etext_number => $formats )
{
    ksort($formats); // sort alphabetically by format string
    $formats_string = implode('; ', array_keys($formats));
    // echo $etext_number, ": ", $formats_string, "\n";
    $formats_string = mysql_escape_string($formats_string);
    mysql_query( "REPLACE INTO pg_books SET etext_number='$etext_number', formats='$formats_string'" )
        or die( mysql_error() );
}

if ($trace) echo "Done\n";

// vim: sw=4 ts=4 expandtab
?>
