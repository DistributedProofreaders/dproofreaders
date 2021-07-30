<?php
$relPath = '../pinc/';
include_once($relPath.'base.inc');
include_once($relPath.'theme.inc');
include_once($relPath.'faq.inc');

$i_type = 0;
if (isset($_GET['i_type'])) {
    $i_type = $_GET['i_type'];
} else {
    $user = User::load_current();
    if ($user) {
        $i_type = $user->profile->i_type;
    }
}

// -----------------------------------------------------------------------------

if ($i_type == 0) {
    output_header('Standard Proofreading Interface Help', NO_STATSBAR);
    echo "<h1>Standard Proofreading Interface Help</h1>";
} else {
    output_header('Enhanced Proofreading Interface Help', NO_STATSBAR);
    echo "<h1>Enhanced Proofreading Interface Help</h1>";
}

// -----------------------------------------------------------------------------

?>

<h2>Introduction</h2>

<p>
To understand the interface, you need to understand the following:
</p>

<p>
There are four possible destinations in the system
for the pages that you proofread. They are:
</p>

<ul>
    <li>
    <b><a name=done>"DONE"</a></b>:
    <p>
    Save a page as "DONE" once you have finished making all of your
    corrections. All final changes are saved, and proofreading is completed.
    These pages are ready to go to the next round.
    </p>

    <p>
    There are links to the five most recent pages in this category
    on the Project Comments page, under the heading "DONE".
    These pages can be re-opened for corrections if necessary
    by clicking on the individual links.
    </p>
    </li>

    <li>
    <b><a name=progr>"IN PROGRESS"</a></b>:
    <p>
    Changes made so far are saved, but proofreading is not yet completed.
    These pages are not yet ready to go the next round.
    </p>
    <p>
    There are links to the five most recent pages in this category
    on the Project Comments page, under the heading "IN PROGRESS".
    These pages are waiting for you to complete them;
    you can do so by clicking on the individual links
    to re-open them, finish proofreading, and save as "DONE".
    If you do not complete them,
    eventually the system will reclaim them for someone else to proofread.
    </p>
    </li>

    <li>
    <b><a name=return>"RETURN TO ROUND"</a></b>
    <p>
    All changes are abandoned, and the
    original version of the page is made available for the next proofreader.
    This is for pages that
    you decided you didn't want to or couldn't proofread,
    but that someone else might be able to.
    Whoever next clicks on the "Start Proofreading" link for this project
    will get the page to proofread.
    </p>
    </li>

    <li>
    <b><a name=bad>"BAD"</a></b>
    <p>
    All changes are abandoned, page cannot be proofread due to damage or flaws
    and is made unavailable until it can be repaired by the Project Manager.
    </p>
    </li>
</ul>

<p>
When you open a page for proofreading,
it is automatically <a href="#progr">"IN PROGRESS"</a>
</p>

<hr>

<?php

// -----------------------------------------------------------------------------

$help = [];

$help["Save as 'Done'"] = "
<p>
    Saves all changes, and finishes proofreading for the current page.
    The page is now <a href='#done'>DONE</a>.
    Stop proofreading. You will be returned to the Project Page.
</p>
";

$help["Save as 'Done' & Proofread Next Page"] = "
<p>
    Saves all changes, and finishes proofreading for the current page.
    The page is now <a href='#done'>DONE</a>.
    The next available page within the project, if any,
    will be opened for proofreading.
</p>
";

$help["Save as 'In Progress'"] = "
<p>
    Save changes to the current page.
    The page is now <a href='#progr'>IN PROGRESS</a>.
    This button allows you to temporarily save your work so far
    on a page you plan to finish later,
    perhaps because it is too long or you are interrupted.
    You will be repositioned to the start of the page.
</p>
<p>
    If you don't return to the page within 4 hours, the page
    may be reclaimed by the site software if all other pages
    have been proofread.
</p>
";

$help['Stop Proofreading'] = "
<p>
    Closes the proofreading interface without saving the current page.
    The page will be <a href='#progr'>IN PROGRESS</a>.
    To save your most recent changes before quitting,
    use the \"Save as 'In Progress'\" button first.
    Note that \"Save as 'In Progress'\" followed by 'Stop Proofreading'
    (page is left <a href='#progr'>IN PROGRESS</a>)
    is NOT equivalent to \"Save as 'Done'\"
    (page is left <a href='#done'>DONE</a>).
</p>
<p>
    As with \"Save as 'In Progress\", if you do not return to the
    page within 4 hours, and all other pages are saved as 'Done',
    any pages that are 'In Progress' may be reclaimed.
</p>
";

$help['Report Bad Page'] = "
<p>
    Loads the Report Bad Page form.
</p>
<p>
    Rarely, some damaged pages cannot be proofread.
    For instance, the image may be incomplete or unreadable,
    or the OCR text may be from a different image.
    In these cases, where some repairs have to be made to the files
    by the Project Manager,
    the page can be marked 'Bad' and removed from proofreading until fixed.
</p>
<p>
    Further information
    (including how to tell a truly bad page from a false alarm)
    is available on the Report Bad Page form itself.
    If you press the 'Submit Report' button on the Bad Page Report form,
    the page is now <a href='#bad'>BAD</a>;
    if you press the 'Cancel' button instead, you are returned to the
    proofreading interface for that page.
</p>
";


$help['Show All Text'] = "
<p>
    Clicking this button opens up a new browser window and displays
    the proofread text as it would appear on an HTML-formatted page
    complete with italics, bolding, etc.
</p>
<p>
    Although this function is helpful to people working in the
    formatting rounds, it also helps proofreaders because it
    displays the text in a different font and slightly different format.
    Sometimes that's all it takes for a sneaky scanning error (scanno)
    to suddenly jump off the page at you!
</p>
";

$help['Preview'] = "
<p>
    Clicking this button displays the proofread text in a manner
    that allows the proofreader or formatter to control whether
    or how the various tags display and whether the text is
    wrapped or not.
</p>
<p>
    This feature is especially valuable to formatters but can
    also assist proofreaders in locating errors. Text cannot
    be edited in this frame. To return to the proofreading
    interface, click on the \"Quit\" button.
</p>
";

$help['Return Page to Round'] = "
<p>
    If, after you start proofreading a page, you find that it's too
    long or complex for you, or you simply run out of time,
    you can return it to round for someone else to do. This
    abandons any changes you have made to the page and returns
    the original version to the pile of available
    pages for the project. If it's the earliest abandoned page,
    it will be served to the next proofreader who requests a page
    for proofreading in the project.
    (See <a href='#return'>RETURN TO ROUND</a>.)
</p>
<p>
    Note if you then immediately request a new page to proofread,
    the 'someone else' may be you!
    If you want to continue proofreading in the same project,
    you can \"Save as 'In Progress'\" the page, 'Stop Proofreading'
    and follow the 'Start Proofreading' link.
    This will load the next available page,
    leaving the one you wanted to skip in your 'IN PROGRESS' section.
    When you have finished proofreading for the day,
    you can re-open it from there and press 'Return Page to Round'
    to immediately make it available for someone else to proofread.)
</p>
";

$help['WordCheck'] =
$help['Run WordCheck'] = "
<p>
    Loads the WordCheck interface.
    The text is checked for possible problems (misspelled words, scannos, etc).
    Problem words are presented in text boxes. If the word in the text box
    appears correct when compared with the scan, you may be able to suggest
    that it be added to the project's Good Word List. If it's not correct,
    you can make corrections directly in the text box.

    See also the <a href=\"wordcheck-faq.php\">WordCheck FAQ</a>.
</p>
<p>
    When done, if you have made any corrections, you will need to either
    submit (apply) or cancel those corrections. In either case the page is
    <a href='#progr'>IN PROGRESS</a>. If you have only suggested words as
    possible good words, you can \"Save as 'Done' & Proofread Next Page\"
    from the WordCheck interface.
</p>
";

$help['Switch to Vertical/Horizontal'] = "
<p>
    Toggles your interface layout between vertical mode
    (scanned image of page appears to the LEFT of the OCR text you are
    correcting) and horizontal mode
    (scanned image of page appears ABOVE the OCR text you are correcting).
    On the way, it performs a 'SAVE'.
    The page is <a href='#progr'>IN PROGRESS</a>.
</p>
";

$help['Change Interface Layout'] = "
<p>
    <IMG SRC='../tools/proofers/gfx/bt4.png'
    ALT='Change Interface Layout'
    TITLE='Change Interface Layout'
    WIDTH='26' HEIGHT='26' BORDER='0' ALIGN='LEFT'>
    When in horizontal mode,
    clicking this button will switch you to vertical mode
    (scanned image of page appears to the LEFT of the OCR text you are correcting).
</p>
<p>
    <IMG SRC='../tools/proofers/gfx/bt5.png'
    ALT='Change Interface Layout'
    TITLE='Change Interface Layout'
    WIDTH='26' HEIGHT='26' BORDER='0' ALIGN='LEFT'>
    When in vertical mode,
    clicking this button will switch you to horizontal mode
    (scanned image of page appears ABOVE the OCR text you are correcting).
</p>
<p>
    On the way, it performs a 'SAVE'.
    The page is <a href='#progr'>IN PROGRESS</a>.
</p>
";

$help['View Project Comments'] = "
<p>
    Opens a copy of the Project Manager's Project Comments
    in a new browser window for reference.
</p>
";

$help['Undo Revert'] = "
<p>
    Undoes the Revert to Original Document function
    by restoring to the last edit before Reverting to Original Document.
</p>
";

$help['Revert to Original Document'] = "
<p>
    When working with a new page,
    this reverts to the original, unedited document.
    After saving an edit via the save button,
    this will revert to the last save.
</p>
";

// -----------------------------------------------------------------------------

if ($i_type == 0) {
    echo "<form>\n";
    echo "<dl>\n";
    foreach (
    [
        "Save as 'In Progress'",
        "Save as 'Done' & Proofread Next Page",
        "Save as 'Done'",
        'Stop Proofreading',
        'Switch to Vertical/Horizontal',
        'Show All Text',
        'Return Page to Round',
        'Report Bad Page',
        'WordCheck',
        'Preview',
    ]
    as $name) {
        echo "<dt>";
        if ($name == 'Switch to Vertical/Horizontal') {
            echo "<input type='button' value='Switch to Vertical'> / ";
            echo "<input type='button' value='Switch to Horizontal'>";
        } else {
            echo "<input type='button' value=\"$name\">";
        }
        echo "</dt>\n";
        echo "<dd>$help[$name]</dd>\n";
    }

    echo "
    <dt>Page:</dt>
    <dd>
    <p>
    This shows the image number
    of the files on our site that contain the information
    (scanned image and text)
    for the page in the book you are proofreading.
    It may vary from the printed page number of the book,
    since some of the pages that get scanned
    (such as introduction pages, some illustration pages)
    have no ordinary page numbers in the book,
    but still count as another page to be proofread on the site.
    </p>
    <p>
    If the OCR text matches the text in the image,
    then this is not a case of 'mismatched image/text',
    even if the page that was scanned
    was numbered, say, 10 in the book and
    is numbered, say, 21 on our site.
    </p>
    </dd>

    <dt>[Proofread by:]</dt>
    <dd>
    <p>
    This appears only after the first round, and is only prefixed
    by the round number(s). It is positioned immediately following
    the page number (as described above). The name of the proofreader
    is a link to send them a private message through the site's
    forum system. It is shown for your convenience should you wish
    to send the previous round's proofreader a comment or question,
    (polite, constructive) criticism or praise on their proofreading
    of this page in the previous round. P1 and F1 will not see a link
    to previous proofreaders.
    </p>
    </dd>

    <dt>View Project Comments</dt>
    <dd>
    {$help['View Project Comments']}
    </dd>

    <dt>View Image</dt>
    <dd>
    <p>
    Opens a copy of the proofing image file of the page you are
    proofreading in the page browser in a new browser window.
    </p>
    </dd>
    ";
    echo "</dl>\n";
    echo "</form>\n";
} else {
    ?>

<DIV ALIGN="CENTER"><TABLE
BORDER="1" WIDTH="98%" CELLPADDING="6">

<TR><TD
COLSPAN="2">
<p><A HREF="#ibtns"><B>Button and Selection Menu</B></A></p>
<p><A HREF="#ikeys"><B>Accelerator Keys (accesskeys)</B></A></p>
<p><A HREF="#proofing_toolbox"><B>Help for Proofreading Toolbox</B></A></p>
<p><B>Additional Help Files</B>
<BR>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <A HREF="ProoferFAQ.php">Proofreader's Frequently Asked Questions</A>
<BR>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <A HREF="proofreading_guidelines.php">Proofreading Guidelines</A></p>
</TD></TR>
<TR><TD ALIGN="CENTER" COLSPAN="2">
<p class='large'><A NAME="ibtns"> </A>Button and Selection Menu</p></TD></TR>

<?php

function echo_row($name, $tooltip, $button_image_base, $accelerator)
{
    global $help;
    echo "<TR><TD class='top-align'>\n";
    $tooltip_esc = attr_safe($tooltip);
    foreach (explode('+', $button_image_base) as $bib) {
        echo "<IMG SRC='../tools/proofers/gfx/{$bib}.png'
        ALT='$tooltip_esc' TITLE='$tooltip_esc'
        WIDTH='26' HEIGHT='26' BORDER='0'>\n";
    }
    echo "</TD><TD class='top-align'><B>$name</B><BR>\n";
    if ($accelerator != '') {
        echo "Accelerator key: <B>$accelerator</B><br>\n";
    }
    echo "$help[$name]</TD></TR>\n";
}

    echo_row('Stop Proofreading', 'Stop Proofreading without Saving', 'bt1', '9');
    echo_row("Save as 'Done'", "Save as 'Done'", 'bt13', '');
    echo_row("Save as 'Done' & Proofread Next Page", "Save as 'Done' & Proofread Next Page", 'bt2', '8');
    echo_row("Save as 'In Progress'", "Save as 'In Progress'", 'bt3', '7');
    echo_row('Report Bad Page', 'Report Bad Page', 'bt14', '');
    echo_row('Return Page to Round', 'Return Page to Round', 'bt15', '');
    echo_row('Change Interface Layout', 'Change Interface Layout', 'bt4+bt5', '6');
    echo_row('Run WordCheck', 'Run WordCheck', 'bt16', '');
    echo_row('View Project Comments', 'View Project Comments', 'bt12', '');
    echo_row('Preview', 'Format preview', 'bt20', '');
    echo_row('Show All Text', 'Show All Text', 'bt9', '');
    echo_row('Undo Revert', 'Undo Revert', 'bt7', '');
    echo_row('Revert to Original Document', 'Revert to Original Document', 'bt8', '');
?>

<TR><TD><B>Font Size</B>
<BR>Selection Menu</TD><TD class='top-align'>Select a point size (pt) for the current font
from the dropdown menu to change the font size of the proofreading text.
</TD></TR>

<TR><TD VALIGN="TOP"><B>Font Face</B>
<BR>Selection Menu</TD><TD class='top-align'>Select a font from the dropdown menu.
</TD></TR>

<TR><TD ALIGN="CENTER" COLSPAN="2">
<P><A NAME="ikeys"> </A> <P><FONT SIZE="+1">Accelerator Keys (accesskeys)</FONT><P></TD></TR>
<TR>
<TD VALIGN="TOP" COLSPAN="2">
<p>These accelerator keys may not work for everyone, and the modifier key
(shown as Alt below) may be different depending on your Operating System and browser.</p>

<p>Several of the commonly used interface buttons have been assigned an accesskey value.
On browsers that support accelerator keys, pressing ALT+ the accesskey assigned to the
button will commit the same action as clicking on the button.</p>

<P>If a button has an accelerator key, the key assigned to it will be listed in the function
description in the <A HREF="#ibtns"><B>Button and Selection Menu</B></A> area.</p>

</TD></TR>
</TABLE></DIV>

<?php
}
// -----------------------------------------------------------------------------
?>

<hr>

<h2 id='proofing_toolbox'>Help for the Proofreading Toolbox</h2>

<p>
The Proofreading Toolbox is common to both the Standard and Enhanced
proofreading interfaces.
</p>

<h3>Special Character Selection</h3>

<p>
Most keyboards do not have enough keys to cover all characters needed in some
of our projects. Depending on your Operating System, you may be able to use
alternate methods to insert the special characters directly (see the
Proofreading Guidelines for suggestions).
</p>

<p>
As an alternate option, the toolbox for the Proofreading interfaces has a
tiled character picker at the top left of the toolbox. At the top of the
picker is a row of menu tiles. Hovering over a menu tile will show a tooltip
containing a short description of what characters that menu contains.
</p>
<p>
Clicking a menu tile will show a grid of characters below it. Hovering
over a character will show an enlarged view of it in the box on the left
and a tooltip text will appear describing the character. Clicking
on a character will insert it into the text.
</p>

<h3>Common Tags</h3>

<p>
At the top right of the Proofreading Toolbox are several rows of buttons
that you can use to insert various tags into the text. The buttons that
you see depend on whether you are working in a proofreading or a formatting
round.
</p>
<h4>Proofreading Rounds</h4>
<p>
If you are working in a proofreading round, do not add or correct any
formatting you may find. You can remove it if it interferes with your
proofreading, but remember to read the Project Comments carefully to
see if there are exceptions to the general guidelines.
</p>
<ul>
    <li><input type='button' value='&lt;x&gt;'> -- removes inline markup from selected text. It will not remove block-level formatting.</li>
    <li><input type='button' value='ABC'> — changes selected characters to ALL CAPS</li>
    <li><input type='button' value='Abc'> — changes selected characters to Title Case</li>
    <li><input type='button' value='abc'> — changes selected characters to all lower case</li>
    <li><input type='button' value='[Greek: ]'> — used to identify text transliterated from Greek. Selected text will be preceded by '[Greek: ' and followed by ']'.</li>
    <li><input type='button' value='[** ]'> — Proofreader's note.</li>
    <li><input type='button' value='[ ]'> — square brackets. Used to identify footnote markers where they appear in the body of the text.</li>
    <li><input type='button' value='{ }'> — curly braces. Used to identify subscripts and multi-character superscripts.</li>
    <li><input type='button' value='[Blank Page]'> — Removes any text from the page and adds the '[Blank Page]' tag at the top.</li>
</ul>
<p>
Except for the [Blank Page] tag, all of the buttons listed above will operate on selected text. [Greek: ], [** ], [] and {} can also be entered standalone and the text added.
</p>

<h4>
Formatting rounds
</h4>
<p>
All of the buttons mentioned above for Proofreading rounds are also available
for the formatting rounds. In addition, there are several buttons that are
only available in the formatting rounds. Formatting markup should only be
added during the formatting rounds.
</p>
<p>
Below are some shortcuts to insert common tags. The shortcuts do not work
for everyone.
</p>
<table border='1'>
  <tr><td><input type='button' value='i'></td><td>
    Italics
  </td><td>
    Alt-i
  </td></tr>
  <tr><td><input type='button' value='b'></td><td>
    Bold
  </td><td>
    Alt-b
  </td></tr>
  <tr><td><input type='button' value='sc'></td><td>
    Small caps
  </td><td>
    Alt-s
  </td></tr>
</table>
<p>
In addition, there are several buttons that do not have shortcuts that are available only to formatters:
</p>
<ul>
    <li><input type='button' value='g'> — gesperrt, or spaced out text</li>
    <li><input type='button' value='f'> — a general catch-all that PMs may request be used to cover formatting that does not have a pre-defined button. It may mean different things in different projects.</li>
    <li><input type='button' value='[Sidenote: ]'></li>
    <li><input type='button' value='[Illustration: ]'></li>
    <li><input type='button' value='[Footnote #: ]'></li>
    <li><input type='button' value='/**/'> — Used to identify blocks of text that should not be rewrapped: line endings are important, and must be retained. Examples: poetry, indices.</li>
    <li><input type='button' value='/##/'> — Used to identify blocks of text that are different from the main body text, but that can be rewrapped. Example: correspondence.</li>
    <li><input type='button' value='&lt;tb&gt;'> — Thought break. Position the cursor where the tags should appear, and select the button.</li>
</ul>

<h3>Popup Tools and Documentation</h3>

<p>
Below the tags are several links to both popup tools and documentation.
</p>

<h4 id="srchrep">Search/Replace Popup</h4>

<p>
    This tool makes it easier to make repetitive changes.
    Click on the Search/Replace button and a small window pops up,
    containing a search value and a replace value.
</p>
<p>
    Clicking <input type='button' value='Replace all'> will replace all
    matched instances of the search text with the value from the replace text.
    You may undo this change by clicking <input type='button' value='Undo'>, but only
    the most recent replace operation may be reverted.
</p>
<p>
    For more complex search matching, regular expressions may be used.
    <blockquote>
        . &mdash; any character, excluding new line characters<br>
        [a-z0-9] &mdash; lowercase letters and numbers<br>
        a{4} &mdash; four lowercase As<br>
        [Aa]{6} &mdash; six As of either case<br>
        A{2,8} &mdash; between 2 and 8 capital As<br>
        [hb]e &mdash; 'he' or 'be'<br>
    </blockquote>
</p>
<p>
    To replace matched text with a new line, \n may be used in the replace field.
</p>

<h4>Greek transliteration popup</h4>

<p>
    The most common non-Latin alphabet we encounter is Greek.
    Even though Project Managers have the option of enabling one
    of the Greek character suites, some may wish to request that
    Greek be transliterated, especially if there are only a few
    words of Greek in a book. The transliterated Greek should be
    wrapped in a tag [Greek: ].
    So
    <blockquote>
    βιβλος
    </blockquote>
    in the image is rendered as
    <pre>
    [Greek: biblos]
    </pre>
    in the proofread page.
</p>
<p>
    To make it easier to select the correct transliterated characters,
    this tool has been provided.
    Click on the Greek button and a small window pops up,
    containing upper and lower case Greek alphabets and a text box.
</p>
<p>
    All of the Greek letters in the popup box are clickable.
    Click the ones that appear in the Greek word in the image,
    and the Latin transliterations appear in the text box,
    from whence they can be cut-and-pasted into the proofread text
    and surrounded with [Greek: ] tags.
</p>
<p>
    For more information please see the Proofreading Guidelines.
</p>

<h4>Help</h4>
<p>
Opens this help page.
</p>

<hr>

<p>
    If you have suggestions for how this documentation can be improved,
    or find an error in it,
    or can make a clarification,
    please post a message in
    <a href='<?php echo $Proofing_Interface_Help_URL; ?>'>this forum topic</a>.
</p>
