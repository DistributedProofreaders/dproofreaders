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

$page_completed = "
    The page you were proofing is considered 'completed',
    and will automatically proceed to the next round
    when all other pages have also been completed.
    A link to it will appear in the 'My Recently Completed' section
    of the project comments page,
    whence it can be opened for corrections if necessary.
";

$page_not_completed = "
    A link to it will appear in
    the 'My Recently Proofread' section of the project comments page,
    whence it can be opened for more proofing later.
    If not completed, eventually the system will reclaim it
    for someone else to proof,
    and it will vanish from your 'My Recently Proofread' list.
";

$truly_finish = "
    Do NOT complete a page you haven't truly finished,
    as the book may automatically proceed to the next round
    with the unfinished page still undone!
";

// ----------------------------------

$help = Array();

$help['Save and Quit'] = "
<p>
    Saves the current page and then quit proofing.
    This browser window will close.
    $page_completed
    $truly_finish
</p>
";

$help['Save and Do Another'] = 
$help['Save and Proof Next Page'] = "
<p>
    Saves the current page and
    obtains the next available page for proofing within the project.
    $page_completed
    $truly_finish
</p>
";

$help['Save'] = "
<p>
    Saves your work so far on the current page.
    You will be repositioned to the start of the page.
    The current page is not considered 'completed'
    until you press either 'Save and Quit' or 'Save and Do Another',
    and will not automatically proceed to the next round.
    $page_not_completed
</p>
<p>
    'Save' is intended to temporarily save your work so far
    on a page you plan to finish later,
    perhaps because it is so long or you are interrupted.
    To complete a page, use the 'Save and Quit' or 'Save and Do Another'
    buttons instead.
    $truly_finish
</p>
";

$help['Quit'] = "
<p>
    Closes the proofing interface <b>without saving</b> the current page.
    To save before quitting, use the 'Save' button.
    Note that 'Save' followed by 'Quit' is NOT equivalent to 'Save and  Quit'.
    When you press 'Quit',
    the page you were proofing is not considered 'completed',
    and will not automatically proceed to the next round.
    $page_not_completed
    To complete a page,
    use the 'Save and Quit' or 'Save and Do Another' buttons.
</p>
";

$help['Report Bad Page'] = "
<p>
    Loads the Report Bad Page form.
    Rarely, some damaged pages are unproofable.
    For instance, the image may be incomplete or unreadable,
    or the OCR text may be from a different image.
    In these cases, where some repairs have to be made to the files
    by the Project Manager,
    the page can be marked 'Bad' and removed from proofing until fixed.
    Further information
    (including how to tell a truly bad page from a false alarm)
    is available on the Report Bad Page form itself.
</p>
";

$help['Return Page to Round'] =
$help['Return Page to Current Round'] = "
<p>
    Abandons any changes you have made to the current page,
    and returns it to the top of the pile of available pages for this project,
    waiting for the next proofer
    who requests a new page to proof from this project,
    to whom it will go for proofing.
    If a page seems too long or complex for you,
    you can return it to round for someone else to do.
    (Note if you then immediately request a new page to proof,
    the 'someone else' may be you!
    If you don't want to go proof a different project instead,
    you can 'Save' the page, 'Quit' and follow the 'Start Proofing' link.
    This will load the next available page, leaving the one you
    wanted to skip in your 'My Recently Proofread' section.
    When you have finished proofing for the day,
    you can re-open it from there and press 'Return Page to Current Round'
    to immediately make it available for someone else to proof.)
</p>
";

$help['Spell Check'] =
$help['Run Spelling Check'] = "
<p>
    Loads the Spelling Check form.
    The OCR text is run through a spell-checker and then displayed,
    with doubtful words rendered as
    a drop down list of suggested corrected spellings from which you can select.
    When done, the corrections made can be submitted (applied) or cancelled.
</p>
";

$help['Switch to Vertical/Horizontal'] = "
<p>
    [Saves current page, changes interface....]
</p>
";

$help['Help'] = "
<p>
    Opens this page in a new window.
</p>
";

$help['Change Interface Layout'] = "
<p>
    <IMG SRC='../tools/proofers/gfx/bt4.png'
	ALT='Change Interface Layout'
	TITLE='Change Interface Layout'
	WIDTH='26' HEIGHT='26' BORDER='0' ALIGN='LEFT'>
    When in horizontal mode,
    clicking this button will switch you to vertical mode.
    The document is saved during the switch.
</p>
<p>
    <IMG SRC='../tools/proofers/gfx/bt5.png'
	ALT='Change Interface Layout'
	TITLE='Change Interface Layout'
	WIDTH='26' HEIGHT='26' BORDER='0' ALIGN='LEFT'>
    When in vertical mode,
    clicking this button will switch you to horizontal mode.
    The document is saved during the switch.
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
    <em>does not</em> replace the need for manual proofing.
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
    ?
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
    $no_stats=1;
    theme('Standard Proofing Interface Help','header');
    echo "<h2>Standard Proofing Interface Help</h2>\n";
    echo "<form>\n";
    echo "<dl>\n";
    foreach(
	Array(
	    'Save',
	    'Save and Do Another',
	    'Save and Quit',
	    'Quit',
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
	    echo "<input type='button' value='$name'>";
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
	for the page in the book you are proofing.
	It may vary from the printed page number of the book,
	since some of the pages that get scanned
	(such as introduction pages, some illustration pages)
	have no ordinary page numbers in the book,
	but still count as another page to be proofed on the site.
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
	The name of the first-round proofer
	is a link to send them a private message
	through the site's forum system.
	It is shown for your convenience
	should you wish to send the first-round proofer
	a comment or question,
	(polite, constructive) criticism
	or praise
	on their proofing of this page in the first round.
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
	of the page you are proofing in a new browser window.
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
	the image already loaded inside the main proofing browser window.
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
$no_stats=1;
theme( 'Enhanced Proofing Interface Help','header');
?>

<CENTER><DIV ALIGN="CENTER"><TABLE
BORDER="1" WIDTH="630" CELLPADDING="6"><TR><TD COLSPAN="2" ALIGN="CENTER"><FONT SIZE="+2">Enhanced Proofing Interface Help</FONT><BR>Version 1.1<BR></TD></TR>
<TR><TD
COLSPAN="2"><FONT SIZE="-1">
Created: 12/03/2002 &nbsp;&nbsp;&nbsp; Author: Carel Lyn Miske
<BR>Updated: 12/15/2002 &nbsp;&nbsp;&nbsp; Tim Bonham
<BR>Updated: 06/17/2003 &nbsp;&nbsp;&nbsp; Bill Keir
</FONT></TD></TR>
<TR><TD
COLSPAN="2">
<P><A HREF="#ibtns"><B>Button and Selection Menu</B></A>
<P><A HREF="#ikeys"><B>Accelerator Keys (accesskeys)</B></A>
<P><A HREF="#itags"><B>Tags and Extended Character Displays</B></A>
<P><B>Additional Help Files</B>
<BR>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <A HREF="ProoferFAQ.php">Proofer's Frequently Asked Questions</A>
<BR>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <A HREF="document.php">Proofing Guidelines</A><P>
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
echo_row( 'Quit', 'Quit without Saving', 'bt1', '9' );
echo_row( 'Save and Quit', 'Save and Quit', 'bt13', '' );
echo_row( 'Save and Proof Next Page', 'Save and Proof Next Page', 'bt2', '8' );
echo_row( 'Save', 'Save', 'bt3', '7' );
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
from the dropdown menu to change the font size of the proofing text.
</TD></TR>

<TR><TD VALIGN="TOP"><B>Font Face</B>
<BR>Selection Menu</TD><TD>Select a named font from the dropdown menu.  If the font is
installed on your system, the proofing text will change to the selected font.  If the
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
The currently displayed mage may be scrolled using the following accelerator keys:<BR>
<FONT SIZE="+2"><B>'</B></FONT> up<BR>
<FONT SIZE="+2"><B>/</B></FONT> down<BR>
<FONT SIZE="+2"><B>,</B></FONT> left<BR>
<FONT SIZE="+2"><B>.</B></FONT> right<BR>
Currently, this is the only method for scrolling the image.<BR>

</TD></TR>

<TR><TD ALIGN="CENTER" COLSPAN="2">
<P><A NAME="itags"> </A><P><FONT SIZE="+1">Tags and Extended Character Displays</FONT><P></TD></TR><TR>
<TD VALIGN="TOP"><B>Open Tags</B></TD><TD>
Tags which have an opening and a closing tag such as [Footnote: ], &lt;i&gt;&lt;/i&gt;,
and [Illustration: ].

<P><B>Internet Explorer for Windows Only</B><BR>
With the mouse or keyboard, select the area of text that should be enclosed by the opening
and closing tag.  Click the opening tag (example: [Footnote: ) and the opening tag will be
placed at the start of the selected text and the closing tag will be placed at the end of
the selected text.  Opening and closing tags may also be inserted separately as listed
below in the Single Characters and Tags description.

<P><B>All Other Browsers</B><BR>
Open and closing tags must be inserted into text using the copy and paste method, as
described below for Single Characters and Tags.

</TD></TR><TR><TD VALIGN="TOP"><B>Single Characters and Tags</B></TD><TD>
Extended Characters such as &#161;,&#162;, and &#163;

<P>Tags which stand alone such as * * * * * or when using either an opening tag such as
[Illustration:  or a closing tag such as &lt;/i&gt; for separate insertions.

<P><B>Note:</B> [Blank Page] will replace all text in the proofing text area.

<P><B>Internet Explorer for Windows Only</B><BR>
Place the cursor caret in the text area at the location in the text where you would like
to insert either the opening or the closing tag.  Click the tag you would like to insert.
The opening or closing tag you clicked will be inserted into the document at the location
of the cursor.

<P><B>All Other Browsers</B><BR>
Click the tag or character you would like to insert.  The clicked tag will display in the
small text box and should be pre-selected.  Copy the text to the system clipboard
(using ctrl-c/cmd-c).  Position the cursor in the text area where you would
like the tag or character to appear and then paste the text from the clipboard
(using ctrl-v/cmd-v).

</TD></TR></TABLE></DIV></CENTER>
<?
}

// -----------------------------------------------------------------------------

echo "
    <hr>

    <h3>Bottom Pane</h3>

    <p>
    This is where we should describe the controls on the bottom pane:
    <ul>
    <li>pop-up menus for non-ASCII characters
    <li>button to open the 'Greek-to-ASCII Transliteration' dialog
    <li>italic and bold
    <li>Sidenote, Illustration, Footnote, /**/, thought break, Blank Page
    </ul>
    </p>
";

theme('','footer');
?>
