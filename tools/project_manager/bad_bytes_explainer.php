<?php
$relPath = '../../pinc/';
include_once($relPath.'base.inc');
include_once($relPath.'theme.inc');
include_once($relPath.'misc.inc'); // startswith str_contains
include_once($relPath.'bad_bytes.inc'); // $_bad_byte_sequences string_to_hex
include_once($relPath.'project_quick_check.inc'); // $css_for_bad_bytes_tables tds_for_bad_bytes

output_header(_("Bad Bytes Explainer"), NO_STATSBAR);

?>

<h1> Explainer for Bad Bytes Reports </h1>

<p>
    (Note that we use "bad" to mean "possibly bad" or "questionable".
    There may be circumstances in which a "bad" character is justifiable.)
</p>

<h2 id='columns'> Columns in the "Details" of a Bad Bytes Report</h2>

<ul>

    <li><b>Image</b>:
        The image-name of page where the bad bytes were found.
        The link will take you to the "Display Image" screen for that page.
    </li>

    <li><b>Text</b>:
        The link will take you to the text for the round where
        page-text was taken and the bad byte-sequences were found.
    </li>

    <li><b>#</b>:
        The number of occurrences (on this page) of the byte-sequence identified on this row.
    </li>

    <li><b>Raw</b>:
        The raw bytes in question.
        Generally, your browser should render them the same here as in the page-text,
        except that:
        (1) the page-text is typically presented in a monospace font,
        which we don't use in the 'raw' column because it makes some glyphs harder to distinguish,
        and
        (2) HTML entity-references appear literally in the linked page-text
        (because it's presented as text/plain)
        whereas they will be rendered as the referenced character in the 'raw' column.
        Note that the cell will typically appear empty
        if the bytes represent a non-printing character,
        e.g., tab, no-break space, or soft hyphen.
    </li>

    <li><b>Bytes</b>:
        If the byte-sequence is an HTML character-reference,
        this column just shows the reference.
        Otherwise, it shows the values of the bytes, written in hexadecimal.
    </li>

    <li><b>Likely intended character</b>:
        An identification of the original character that probably resulted in the byte-sequence.
        (The character's Unicode code point and description.)
    </li>

    <li><b>Why bad</b>:
        A brief comment indicating why the byte-sequence is considered bad.
        For a fuller explanation, see below.
    </li>
</ul>

<h2>Explanation of 'Why Bad'</h2>

<ul>
<li><a href='#control_characters'>ISO-8859-1 control character</a></li>
<li><a href='#windows-1252'>Windows-1252-encoded</a></li>
<li><a href='#utf8-encoded'>UTF8-encoded</a></li>
<li><a href='#double-utf8-encoded'>Double-UTF8-encoded</a></li>
<li><a href='#html-char-entity-refs'>HTML character entity references</a></li>
<li><a href='#html-numeric-char-refs'>HTML numeric character references</a></li>
</ul>

<?php

echo "<h3 id='control_characters'>ISO-8859-1 control character</h3>\n";

echo "
    <p>
    Any non-printable character other than Space is considered bad.
    At pgdp.net, the most common of these are
    U+0009 'character tabulation' [TAB]
    and
    U+00a0 'no-break space' [NBSP]
    </p>
";
show_a_table_of_bads("ISO-8859-1 control character");

echo "
    <p>
    Technically, 'delete', 'no-break space', and 'soft hyphen' aren't control characters,
    but the distinction is not important for this explainer.
    Also, this table omits most of the C1 control characters
    (those in the range x80-x9f),
    because those byte values are better explained as Windows-1252 encodings
    (see the next section).
    </p>
";

// -----------------------------------------------------------------------------

echo "<h3 id='windows-1252'>Windows-1252-encoded</h3>\n";

echo "
    <p>
    <a href='http://en.wikipedia.org/wiki/Windows-1252'>Windows code page 1252</a>
    is largely the same as ISO-8859-1,
    but it assigns some non-ISO-8859-1 characters
    to most of the bytes in the range x80-x9f
    (which are control characters in ISO-8859-1).
    Any byte in that range is considered bad.
    At pgdp.net, the most common of these is x97,
    which is Windows-1252's encoding of U+2014 'em dash'.
    </p>
";

show_a_table_of_bads("Windows-1252");

// -----------------------------------------------------------------------------

echo "<h3 id='utf8-encoded'>UTF8-encoded</h3>\n";

echo "
    <p>
    <a href='http://en.wikipedia.org/wiki/UTF-8'>UTF-8</a>
    encodes all Unicode code points into sequences of 1 to 6 bytes.
    Theoretically, any multi-byte UTF-8 encoding would be considered bad,
    but that would cause too many false positives
    (where the bytes in question
    were actually intended as the ISO-8859-1 encoding of 2 to 6 characters,
    and just happen to look like a UTF-8 encoding).
    To lessen the chance of false positives,
    we restrict attention to the characters in the Windows-1252 repertoire
    (which includes the ISO-8859-1 repertoire),
    plus a few more that show up in practice.
    Any multi-byte sequence that is the UTF-8 encoding of such a character
    is considered bad.
    </p>
";

show_a_table_of_bads("/(?<!double-)UTF8-encoded/");

// -----------------------------------------------------------------------------

echo "<h3 id='double-utf8-encoded'>Double-UTF8-encoded</h3>\n";

echo "
    <p>
    Sometimes, it appears that a page text has undergone UTF-8 encoding twice.
    For instance, consider Em Dash:
    in Unicode, it is code point U+2014,
    which UTF-8 encodes as the three bytes <code>e2 80 94</code>.
    If these bytes are interpreted as code point values
    (e.g., by a process that assumes it's reading text encoded as ISO-8859-1),
    and then encoded in UTF-8 again,
    the result is <code>c3a2 c280 c294</code>,
    which appears as \xc3\xa2\xc2\x80\xc2\x94 in a page-text.
    </p>

    <p>
    Occasionally, it appears that some conversion process
    is applied to the text between the two encodings
    (e.g., changing 'curly quotes' to 'plain quotes'),
    which alters the resulting sequence of bytes.
    This is referred to as a 'midway conversion' below.
    </p>
";

show_a_table_of_bads("double-UTF8-encoded");

echo "
    <p>
    (A triple-encoding has even been observed in the wild,
    but only once,
    so it's not really worth looking for specifically.
    Such occurrences will still be flagged as bad,
    because they are UTF-8 encodings.)
    </p>
";

// -----------------------------------------------------------------------------

echo "<h3 id='html-char-entity-refs'>HTML character entity references</h3>\n";

echo "
    <p>
    Any HTML character entity reference is considered bad.
    </p>
";

show_a_table_of_bads("HTML character entity reference");

echo "
    <p>
    (This is an odd collection of characters,
    but they are the ones that occur in practice.)
    </p>
";

// -----------------------------------------------------------------------------

echo "<h3 id='html-numeric-char-refs'>HTML numeric character references</h3>";

echo "
    <p>
    Any HTML numeric character reference is considered bad.
    There are hundreds of different ones that occur in practice,
    too many to list in a table,
    including references for Hebrew letters and CJK ideographs.
    The commonest at pgdp.net is '&amp;#8212;',
    which encodes U+2014 'em dash'.
    </p>
";

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function show_a_table_of_bads($desired_badness)
{
    echo "
        <table class='basic striped'>
        <tr>
            <th>" . _("Raw") . "</th>
            <th>" . _("Bytes") . "</th>
            <th>" . _("Likely intended character") . "</th>
            <th>" . _("Why bad") . "</th>
        </tr>
    ";
    global $_bad_byte_sequences;
    foreach ($_bad_byte_sequences as $raw => $remarks)
    {
        list($code_point, $why_bad) = $remarks;
        if (startswith($desired_badness, '/'))
        {
            $matches = preg_match($desired_badness, $why_bad);
        }
        else
        {
            $matches = str_contains($why_bad, $desired_badness);
        }
        if ($matches)
        {
            echo "<tr>", tds_for_bad_bytes($raw), "</tr>\n";
        }
    }
    echo "</table>\n";
}

# vim: sw=4 ts=4 expandtab
