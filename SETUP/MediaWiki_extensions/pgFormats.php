<?php
/**
 * PG formats extension- this extension provides a  custom tag for listing titles
 * from PG and the available formats.
 *
 * To activate this extension, add the following into your LocalSettings.php file:
 * require_once('$IP/extensions/pgFormats.php');
 */
/**
 * Protect against register_globals vulnerabilities.
 * This line must be present before any global variable is referenced.
 */
if( !defined( 'MEDIAWIKI' ) ) {
        echo( "This is an extension to the MediaWiki package and cannot be run standalone.\n" );
        die( -1 );
}
 
// Extension credits that will show up on Special:Version    
$wgExtensionCredits['parserhook'][] = array(
        'path'           => __FILE__,
        'name'           => 'PG Formats Extension',
        'version'        => '1.1',
        'author'         => 'Distributed Proofreaders', 
        'url'            => '',
        'descriptionmsg' => '',
        'description'    => 'Provides a tag for listing titles at PG and their formats.'
);

$wgExtensionFunctions[] = "wfPgFormats";

function wfPgFormats() {
    global $wgParser;
    # register the extension with the WikiText parser
    # the first parameter is the name of the new tag.
    # In this case it defines the tag <example> ... </example>
    # the second parameter is the callback function for
    # processing the text between the tags
    $wgParser->setHook( "pg_formats", "getPgFormats" );
}

# The callback function for converting the input text to HTML output
function getPgFormats( $input, $argv ) {
    # $argv is an array containing any arguments passed to the
    # extension like <example argument="foo" bar>..
    $error_inv = "<strong style='color:red;'>[Error: getPgFormats: invalid etext number]</strong>";
    $error_inv2 = "<strong style='color:red;'>[Error: getPgFormats: invalid etext number; possibly not yet posted]</strong>";

    $etext = mysql_real_escape_string(@$argv['etext']);

    if (empty($etext) || !is_numeric($etext))
        return $error_inv;

    $result = mysql_query("
        SELECT formats
        FROM pg_books
        WHERE etext_number = '$etext'
        LIMIT 1
    ");

    if (!$result || mysql_num_rows($result) == 0)
        return $error_inv2; 

    $formats = '[' . mysql_result($result,0,'formats') . ']';

    if (empty($input))
        return $formats;

     @preg_match('/([^,]+),(.*)/',$input,$matches);

    if (empty($matches[1]) || empty($matches[2]))
        return "<strong style='color:red'>[Error: getPgFormats: No comma in input text. Use XML short form instead.]</strong>";

    return "<a href='http://www.gutenberg.org/ebooks/$etext' class='extiw'> $matches[1]</a>, $matches[2] -- $formats";

}
?>
