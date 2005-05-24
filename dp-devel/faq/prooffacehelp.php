<?
$relPath='../pinc/';
include($relPath.'v_site.inc');
include($relPath.'connect.inc');
include($relPath.'theme.inc');
new dbConnect();

if ( isset($_GET['i_type']) )
{
    $i_type = $_GET['i_type'];
}
else
{
    $i_type = $userP['i_type'];
}

// -----------------------------------------------------------------------------

if ( $i_type == 0 )
{
    $no_stats=1;
    theme('Standard Proofreading Interface Help','header');
    echo "
    <center>
    <h2>Standard Proofreading Interface Help</h2>
    </center>
    ";
}
else
{
    $no_stats=1;
    theme( 'Enhanced Proofreading Interface Help','header');
    echo "
    <center>
    <h2>Enhanced Proofreading Interface Help</h2>
    Version 1.2<br>
    <br>
    <table>
    <tr><td>Created</td><td>12/03/2002</td><td>Author: Carel Lyn Miske</td></tr>
    <tr><td>Updated</td><td>12/15/2002</td><td>Tim Bonham</td></tr>
    <tr><td>Updated</td><td>06/17/2003</td><td>Bill Keir</td></tr>
    <tr><td>Updated</td><td>06/23/2003</td><td>Bill Keir</td></tr>
    <tr><td>Updated</td><td>05/24/2005</td><td>pourlean</td></tr>
    </table>
    </center>
    ";
}

// -----------------------------------------------------------------------------

?>

<h3>Introduction</h3>

<p>
To understand the interface,
you need to understand the following Q &amp; A.

<p>
<b>Q</b>:
Where do my pages go?

<p>
<b>A</b>:
There are four possible destinations in the system
for the pages that you proofread.
They are:

<ul>
    <li>
    <b><a name=done>"DONE"</a></b>:
    All final changes are saved, and proofreading is completed.
    These pages are ready to go to the next round.
    There are links to the five most recent pages in this category
    on the Project Comments page, under the heading "DONE".
    These pages can be re-opened for corrections if necessary
    by clicking on the individual links.
    Do not mark a page "DONE" unless you have really finished correcting it,
    or else this partially-proofread page may move to the next round
    when the rest of the project does!
    </li>
    <br>

    <li>
    <b><a name=progr>"IN PROGRESS"</a></b>:
    Changes made so far are saved, but proofreading is not yet completed.
    These pages are not yet ready to go the next round.
    There are links to the five most recent pages in this category
    on the Project Comments page, under the heading "IN PROGRESS".
    These pages are waiting for you to complete them;
    you can do so by clicking on the individual links
    to re-open them and finish the proofreading.
    If you do not complete them,
    eventually the system will reclaim them for someone else to proofread.
    </li>
    <br>

    <li>
    <b><a name=return>"RETURN TO SENDER"</a></b>
    All changes are abandoned,
    original version of page made available for someone else to proofread.
    This is for pages that, once you saw them,
    you decided you didn't want to or couldn't proofread,
    but that someone else might be able to.
    Whoever next clicks on the "Start Proofing" link for this project
    will get the page to proofread.
    </li>
    <br>

    <li>
    <b><a name=bad>"BAD"</a></b>
    All changes abandoned, page is unproofably damaged or flawed
    and is made unavailable until it can be repaired by the Project Manager.
    </li>

</ul>

<p>
When you open a page for proofreading,
it is automatically <a href="#progr">"IN PROGRESS"</a>
</p>

<p>
Now you will understand the following explanations of what the buttons do.
</p>

<hr>

<?

// -----------------------------------------------------------------------------

$help = Array();

$help["Save as 'Done'"] = "
<p>
    <i>\"I have finished proofreading this page,
    it is as correct as I can make it,
    so I want to save it as <a href='#done'>DONE</a>,
    and stop proofreading for now.\"</i>
</p>
<p>
    Save all changes, and finish proofreading the current page.
    The page is now <a href='#done'>DONE</a>.
    Stop proofreading.
    The proofreading browser window will close.
</p>
";

$help["Save as 'Done' & Proof Next"] = "
<p>
    <i>\"I have finished proofreading this page,
    it is as correct as I can make it,
    so I want to save it as <a href='#done'>DONE</a>,
    and start proofreading the next available page.\"</i>
</p>
<p>
    Save all changes, and finish proofreading the current page.
    The page is now <a href='#done'>DONE</a>.
    The next available page within the project, if any,
    will be opened for proofreading.
</p>
";

$help["Save as 'In Progress'"] = "
<p>
    <i>\"I haven't finished proofreading this page,
    but I want to save my work on it so far.\"</i>
</p>
<p>
    Save changes to the current page.
    The page is now <a href='#progr'>IN PROGRESS</a>.
    This button is intended to temporarily save your work so far
    on a page you plan to finish later,
    perhaps because it is too long or you are interrupted.
    You will be repositioned to the start of the page.
</p>
";

$help['Stop Proofing'] = "
<p>
    <i>\"I haven't finished proofreading this page,
    but I want to stop proofreading for now.
    I will finish proofreading this page later.\"</i>
</p>
<p>
    Closes the proofreading interface without saving the current page.
    The page will be <a href='#progr'>IN PROGRESS</a>.
    To save your most recent changes before quitting,
    use the \"Save as 'In Progress'\" button first.
    Note that \"Save as 'In Progress'\" followed by 'Stop Proofing'
    (page is left <a href='#progr'>IN PROGRESS</a>)
    is NOT equivalent to \"Save as 'Done'\"
    (page is left <a href='#done'>DONE</a>).
</p>
";

$help['Report Bad Page'] = "
<p>
    <i>\"This page is damaged or flawed so badly no one could proofread it.\"</i>
</p>
<p>
    Loads the Report Bad Page form.
</p>
<p>
    Rarely, some damaged pages are unproofable.
    For instance, the image may be incomplete or unreadable,
    or the OCR text may be from a different image.
    In these cases, where some repairs have to be made to the files
    by the Project Manager,
    the page can be marked 'Bad' and removed from proofreading until fixed.
    Further information
    (including how to tell a truly bad page from a false alarm)
    is available on the Report Bad Page form itself.
    If you press the 'Submit Report' button on the Bad Page Report form,
    the page is now <a href='#bad'>BAD</a>;
    if you press the 'Cancel' button instead,
    the page is <a href='#progr'>IN PROGRESS</a>.
</p>
";

$help['Return Page to Round'] =
$help['Return Page to Current Round'] = "
<p>
    <i>\"This page is more than I can (or want to) proofread at the moment,
    but someone else may have better luck.\"</i>
</p>
<p>
    Abandons any changes you have made to the current page,
    and returns the original version
    to the top of the pile of available pages for this project,
    waiting for the next proofreader
    who requests a new page to proofread from this project,
    to whom it will go for proofreading.
    (See <a href='#return'>RETURN TO SENDER</a>.)
</p>
<p>
    If a page seems too long or complex for you,
    you can return it to round for someone else to do.
    (Note if you then immediately request a new page to proofread,
    the 'someone else' may be you!
    If you don't want to go proofread a different project instead,
    you can \"Save as 'In Progress'\" the page, 'Stop Proofing' and follow the 'Start Proofing' link.
    This will load the next available page,
    leaving the one you wanted to skip in your 'IN PROGRESS' section.
    When you have finished proofreading for the day,
    you can re-open it from there and press 'Return Page to Current Round'
    to immediately make it available for someone else to proofread.)
</p>
";

$help['Spell Check'] =
$help['Run Spelling Check'] = "
<p>
    <i>\"I want to check this page with the automatic spell checker.\"</i>
</p>
<p>
    Loads the Spelling Check form.
    The OCR text is run through a spell-checker and then displayed,
    with doubtful words rendered as
    a drop down list of suggested corrected spellings from which you can select.
</p>
<p>
    When done, the corrections made can be submitted (applied) or cancelled.
</p>
<p>
    In either case the page is <a href='#progr'>IN PROGRESS</a>.
</p>
";

$help['Switch to Vertical/Horizontal'] = "
<p>
    <i>\"I'd rather the image was to the left of / above the text.\"</i>
</p>
<p>
    Toggles your interface layout between
    vertical mode
    (scanned image of page appears to the LEFT of the OCR text you are correcting)
    and
    horizontal mode
    (scanned image of page appears ABOVE the OCR text you are correcting).
    On the way, it performs a 'SAVE'.
    The page is <a href='#progr'>IN PROGRESS</a>.
</p>
";

$help['Help'] = "
<p>
    Opens this page in a new window.
</p>
";

$help['Change Interface Layout'] = "
<p>
    <i>\"I'd rather the image was to the left of / above the text.\"</i>
</p>
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

$help['Check for Common Errors'] = "
<p>
    Interactively searches the current text for common errors.
<p>
    To <b>start</b> the error checking sequence,
    click the Check for Common Errors Button.
    The error check messages will replace the text in the text editing area.
<p>
    To <b>stop</b> the error checking sequence,
    click the Undo Revert button
    to restore to the last edit before starting the common errors check.
    Any changes made during the check will be lost.
<p>
    To <b>continue</b> the error checking process
    after any prompt message in the text editing area,
    click the Check for Common Errors button.
<p>
    If an error is found, the discovery of the error and type of error
    will be displayed in the text editing area.
    Click the check for common errors button to show the location of the error.
    The error will be located at the end of the text editing area and,
    depending upon the location of the error in the text,
    may require you to scroll to end of the text editing area
    to see the potential error.
    Once you have fixed the error or determined that it is not an error,
    click the Check for Common Errors button
    to continue the error checking sequence.
<p>
    When the error checking sequence is complete,
    click the Check for Common Errors button
    to replace the error check messages with the text.
    Changes made during the error checking process will be included in the text.
    If you wish to undo any changes made during the process,
    click the Undo Revert button
    to restore to your last edit before starting
    the Check for Common Errors sequence.
<p>
    The Check for Common Errors sequence
    <em>does not</em> replace the need for manual proofreading.
";

$help['View Project Comments'] = "
<p>
    Opens a copy of the Project Manager's Project Comments
    (NOT the full 'Project Comments' page)
    in a new browser window for reference.
";

$help['Show All Text'] = "
<p>
    Displays the currently edited text from the text area in a new window.
";

$help['Undo Revert'] = "
<p>
    Undoes the Revert to Original Document function
    by restoring to the last edit before Reverting to Original Document.
<!--
<p>
    Also stops the Check for Common Errors cycle
    and reverts back to the last edit
    before initiating the Check for Common Errors.
-->
";

$help['Revert to Original Document'] = "
<p>
    When working with a new page,
    this reverts to the original, unedited document.
    After saving an edit via the save button,
    this will revert to the last save.
";

$help['Refresh Image'] = "
<p>
    Reloads the image file of the scanned page.
    Useful if the image 'gets stuck'
    and doesn't load completely the first time;
    sometimes it makes an apparently 'missing' image 'reappear'.
";

$help['Set Image Zoom Percent'] = "
<p>
    Type a number as a percent into the box to the left of this button
    and then click this button to zoom the scanned image width
    to the percent indicated.
<p>
    All percentages are calculated using 1000 pixels=100% width.
<p>
    Please, <B>do not</B> include the percent (%) sign in your number.
";

// -----------------------------------------------------------------------------

if ( $i_type == 0 )
{
    echo "<form>\n";
    echo "<dl>\n";
    foreach(
	Array(
	    "Save as 'In Progress'",
	    "Save as 'Done' & Proof Next",
	    "Save as 'Done'",
	    'Stop Proofing',
	    'Switch to Vertical/Horizontal',
	    'Return Page to Round',
	    'Report Bad Page',
	    'Spell Check'
	)
	as $name )
    {
	echo "<dt>";
	if ( $name == 'Switch to Vertical/Horizontal' )
	{
	    echo "<input type='button' value='Switch to Vertical'> / ";
	    echo "<input type='button' value='Switch to Horizontal'>";
	}
	else
	{
	    echo "<input type='button' value=\"$name\">";
	}
	echo "</dt>\n";
	echo "<dd>$help[$name]</dd>\n";
    }

    echo "
    <dt>Page number</dt>
    <dd>
    <p>
	This shows the index number
	of the files on our site that contain the information
	(scanned image and OCR text)
	for the page in the book you are proofreading.
	It may vary from the printed page number of the book,
	since some of the pages that get scanned
	(such as introduction pages, some illustration pages)
	have no ordinary page numbers in the book,
	but still count as another page to be proofread on the site.
	Also some books have numbered pages that are otherwise blank,
	and sometimes these are not scanned,
	further throwing out the correspondence
	between the 'on site' page number and the 'printed' page number.
	If the OCR text matches the text in the image,
	then this is not a case of 'mismatched image/text',
	even if the page that was scanned
	was numbered, say, 10 in the book and
	is numbered, say, 21 on our site.
    </p>
    </dd>

    <dt>Proofed by:</dt>
    <dd>
    <p>
	This appears only in the second round.
	The name of the first-round proofreader
	is a link to send them a private message
	through the site's forum system.
	It is shown for your convenience
	should you wish to send the first-round proofreader
	a comment or question,
	(polite, constructive) criticism
	or praise
	on their proofreading of this page in the first round.
    </p>
    </dd>

    <dt>View Project Comments</dt>
    <dd>
    {$help['View Project Comments']}
    </dd>

    <dt>View Image</dt>
    <dd>
    <p>
	Opens a copy of the png image file
	of the page you are proofreading in a new browser window.
	In Internet Explorer,
	if you hover your mouse over the image in this new window,
	a 'show actual size' icon will appear in the lower right corner.
	Clicking this will display the image in extreme close-up,
	which can be useful sometimes.
    </p>
    </dd>

    <dt>Image Resize:
	<input type='button' value='50%'>
	<input type='button' value='100%'>
	<input type='button' value='200%'>
    </dt>
    <dd>
    <p>
	These three buttons change the zoom of
	the image already loaded inside the main proofreading browser window.
	They can be useful in making out small, faded or blurry type
	in the scanned images.
    </p>
    </dd>
    ";
    echo "</dl>\n";
    echo "</form>\n";
}

else
{
?>

<CENTER><DIV ALIGN="CENTER"><TABLE
BORDER="1" WIDTH="630" CELLPADDING="6">

<TR><TD
COLSPAN="2">
<P><A HREF="#ibtns"><B>Button and Selection Menu</B></A>
<P><A HREF="#ikeys"><B>Accelerator Keys (accesskeys)</B></A>
<P><A HREF="#proofing_toolbar"><B>Help for Proofreading Toolbar</B></A>
<P><B>Additional Help Files</B>
<BR>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <A HREF="ProoferFAQ.php">Proofreader's Frequently Asked Questions</A>
<BR>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <A HREF="document.php">Proofreading Guidelines</A><P>
</TD></TR>
<TR><TD ALIGN="CENTER" COLSPAN="2">
<P><A NAME="ibtns"> </A> <P><FONT SIZE="+1">Button and Selection Menu</FONT><P></TD></TR>

<?

function echo_row( $name, $tooltip, $button_image_base, $accelerator )
{
    global $help;
    echo "<TR><TD VALIGN='TOP'>\n";
    foreach( explode('+', $button_image_base) as $bib )
    {
	echo "<IMG SRC='../tools/proofers/gfx/{$bib}.png'
	    ALT='$tooltip' TITLE='$tooltip'
	    WIDTH='26' HEIGHT='26' BORDER='0'>\n";
    }
    echo "</TD><TD><B>$name</B><BR>\n";
    if ( $accelerator != '' )
    {
	echo "Accelerator key: <B>$accelerator</B><br>\n";
    }
    echo "$help[$name]</TD></TR>\n";
}

echo_row( 'Help', 'Help', 'bt11', '1' );
echo_row( 'Stop Proofing', 'Stop Proofing without Saving', 'bt1', '9' );
echo_row( "Save as 'Done'", "Save as 'Done'", 'bt13', '' );
echo_row( "Save as 'Done' & Proof Next", "Save as 'Done' & Proof Next", 'bt2', '8' );
echo_row( "Save as 'In Progress'", "Save as 'In Progress'", 'bt3', '7' );
echo_row( 'Report Bad Page', 'Report Bad Page', 'bt14', '' );
echo_row( 'Return Page to Current Round', 'Return Page to Current Round', 'bt15', '' );
echo_row( 'Change Interface Layout', 'Change Interface Layout', 'bt4+bt5', '6' );
// echo_row( 'Check for Common Errors', 'Check for Common Errors', 'bt6', '' );
echo_row( 'Run Spelling Check', 'Run Spelling Check', 'bt16', '' );
echo_row( 'View Project Comments', 'View Project Comments', 'bt12', '' );
echo_row( 'Show All Text', 'Show All Text', 'bt9', '' );
echo_row( 'Undo Revert', 'Undo Revert', 'bt7', '' );
echo_row( 'Revert to Original Document', 'Revert to Original Document', 'bt8', '' );
echo_row( 'Refresh Image', 'Refresh Image', 'bt6', '' );
echo_row( 'Set Image Zoom Percent', 'Set Image Zoom Percent', 'bt10', '' );

?>

<TR><TD><B>Font Size</B>
<BR>Selection Menu</TD><TD>Select a point size (pt) for the current font
from the dropdown menu to change the font size of the proofreading text.
</TD></TR>

<TR><TD VALIGN="TOP"><B>Font Face</B>
<BR>Selection Menu</TD><TD>Select a named font from the dropdown menu.  If the font is
installed on your system, the proofreading text will change to the selected font.  If the
font is not installed on your system, your system will either automatically select a
font in the same family or leave the current font unchanged.
</TD></TR>



<TR><TD ALIGN="CENTER" COLSPAN="2">
<P><A NAME="ikeys"> </A> <P><FONT SIZE="+1">Accelerator Keys (accesskeys)</FONT><P></TD></TR>
<TR>
<TD VALIGN="TOP" COLSPAN="2">
Several of the commonly used interface buttons have been assigned an accesskey value.
On browsers that support accelerator keys, pressing ALT+ the accesskey assigned to the
button will commit the same action as clicking on the button.

<P>If a button has an accelerator key, the key assigned to it will be listed in the function
description in the <A HREF="#ibtns"><B>Button and Selection Menu</B></A> area.


<P><B>Internet Explorer Only</B><BR>
The currently displayed image may be scrolled using the following accelerator keys:<BR>
<FONT SIZE="+2"><B>'</B></FONT> up<BR>
<FONT SIZE="+2"><B>/</B></FONT> down<BR>
<FONT SIZE="+2"><B>,</B></FONT> left<BR>
<FONT SIZE="+2"><B>.</B></FONT> right<BR>
Currently, this is the only method for scrolling the image.<BR>

</TD></TR>
</TABLE></DIV></CENTER>

<?
}
// -----------------------------------------------------------------------------
?>

<hr>

<a name='proofing_toolbar'>
<center><h3>Help for Proofreading Toolbar</h3></center>
</a>


<h4>Special Character Dropdowns</h4>

<p>
    Many non-English texts have characters
    that can be difficult to enter from the keyboard.
    There are six drop-down lists of non-ASCII characters
    to assist in these cases.
</p>
<p>
    The ones labelled A,E,I,O,U contain various accented versions,
    upper and lower case,
    of those respective characters.
    The final drop-down list contains other special symbols,
    and accented versions of some consonants like Y, C, D, N etc.
</p>
<p>
    For any of the dropdown lists, select the character you want.
    It should appear at the insertion point in the text area of the
    proofreading interface.
</p>
<p>
    Some people experience problems with these lists,
    such as not being able to select the same character twice in a row.
    We are aware of these difficulties and are working to solve them.
    A workaround is to select a different character in between,
    or copy-and-paste it from the earlier position (if still on the same page).
</p>
<p>
    Also see the Proofreading Guidelines
    for other ways of entering special characters.
</p>


<h4>Greek transliteration popup</h4>

<p>
    The most common non-Latin alphabet we encounter is Greek.
    We usually wish to transliterate Greek letters into Latin ones,
    and wrap the result in tags [Greek: ].
    So
	<blockquote>
	&beta;&iota;&beta;&lambda;&omicron;&sigmaf;
	</blockquote>
    in the image is rendered
    <pre>
    [Greek: biblos]
    </pre>
    in our proofread page.
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
    and the latin transliterations appear in the text box,
    from whence they can be cut-and-pasted into the proofread text
    and surrounded with [Greek: ] tags.
</p>
<p>
    For more information please see the Proofreading Guidelines.
</p>


<h4>Common Tags</h4>

<p>
    &lt;i&gt;,
    &lt;/i&gt; (<i>italic</i> text),
    &lt;b&gt;,
    &lt;/b&gt; (<b>bold</b> text),
    &lt;sc&gt;,
    &lt;/sc&gt; (<span style='font-variant: small-caps'>Small Caps</span> text),
    Sidenote,
    Illustration,
    *,
    [],
    Footnote,
    /*,
    */,
    /#,
    #/,
    *&nbsp;*&nbsp;*&nbsp;*&nbsp;* (thought break),
    Blank Page,
</p>
<p>
    Along the lower line of the proofreading toolbar
    in the lower pane of the proofreading interface
    are controls labelled with the common tags listed above.
    You can use these buttons to place tags into the proofreading text area.
    If you select text before you click on a button in the toolbar, the text will be
    surrounded by the respective tag.
    The tags will also appear in the text boxes to the left of the Italic button. You 
    can also copy and paste the tags from the text box if the select and surround feature does not
    work in your browser.
    These features are provided as a convenience;
    if you'd really rather type the tags in by hand you are welcome to.
</p>
<p>
    For example, if the caption for an illustration is in your page,
    you can select the caption in the text box
    (by clicking your mouse at the start of the caption,
    and dragging the mouse to the end of the caption
    while keeping the button depressed),
    then click on the "Illustration" link in the proofreading toolbar,
    to have the desired tags inserted at the beginning and end of the caption.
</p>
<p>
    In cases where a common tag is singular
    (such as the 'thought break' row of asterisks),
    you can select, say, a space character in the text
    where you want the tag to be added,
    and it will be positioned there when you click the tag link.
</p>
<p>
    The "select and surround" feature may not work in all browsers.
</p>
<p>
    Note that [Blank Page] will clear any existing text in the proofreading window.
</p>

<dl>
<dt>
    <img src='../tools/proofers/gfx/tags/help.png'
	width='30' height='30' border='0'
	alt='Help' title='Help'>
</dt>
<dd>
    <p>
    Opens this help page.
    </p>
</dd>

</dl>

<!--
<B>Open Tags</B><br>
Tags which have an opening and a closing tag, such as
    [Footnote: ],
    &lt;i&gt;&lt;/i&gt;, and
    [Illustration: ].

<P><B>Internet Explorer for Windows Only</B><BR>
With the mouse or keyboard,
select the area of text that should be enclosed by the opening and closing tag.
Click the opening tag (example: [Footnote: )
and the opening tag will be placed at the start of the selected text
and the closing tag will be placed at the end of the selected text.
Opening and closing tags may also be inserted separately as listed below
in the Single Characters and Tags description.

<P><B>All Other Browsers</B><BR>
Open and closing tags must be inserted into text
using the copy and paste method,
as described below for Single Characters and Tags.


<B>Single Characters and Tags</B><br>
Extended Characters such as &#161;,&#162;, and &#163;

<P>Tags which stand alone such as * * * * *
or when using either an opening tag such as [Illustration:
or a closing tag such as &lt;/i&gt;
for separate insertions.

<P><B>Note:</B> [Blank Page] will replace all text in the proofreading text area.

<P><B>Internet Explorer for Windows Only</B><BR>
Place the cursor caret in the text area
at the location in the text where you would like to insert
either the opening or the closing tag.
Click the tag you would like to insert.
The opening or closing tag you clicked will be inserted into the document
at the location of the cursor.

<P><B>All Other Browsers</B><BR>
Click the tag or character you would like to insert.
The clicked tag will display in the small text box and should be pre-selected.
Copy the text to the system clipboard (using ctrl-c/cmd-c).
Position the cursor in the text area
where you would like the tag or character to appear
and then paste the text from the clipboard (using ctrl-v/cmd-v).
-->

<hr>

<p>
    If you have suggestions for how this documentation can be improved,
    or find an error in it,
    or can make a clarification,
    please post a message in
    <a href='<? echo $Proofing_Interface_Help_URL; ?>'>this forum topic</a>.
</p>
<br>
<?
theme('','footer');
?>
