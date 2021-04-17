<?php
$relPath = '../pinc/';
include_once($relPath.'base.inc');
include_once($relPath.'faq.inc');
include_once($relPath.'pg.inc');
include_once($relPath.'forum_interface.inc');
include_once($relPath.'theme.inc');

maybe_redirect_to_external_faq();

$theme_args["css_data"] = "div.note {padding: .75em; background: #f9f9f9; border: 3px #aaa double; width: 90%; margin: 1em 1em 1em 3em;}
.spaced li {margin-bottom: .5em;}";

output_header('Post-Processing FAQ', 'header', NO_STATSBAR, $theme_args);

$post_processing_forum_url = get_url_to_view_forum($post_processing_forum_idx);
$teams_forum_url = get_url_to_view_forum($teams_forum_idx);

?>

<h1>Post-Processing FAQ</h1>
<h5>(Version 3.8; last updated May 20, 2010)</h5>

<p>
Note that there are additional references at the end of this document under
<a href="#also">See also ...</a>
</p>

<ul>
<li><a href="#what">What is post-processing?</a></li>
<li><a href="#who">Who can post-process?</a></li>
<li><a href="#helpavail">What help is available?</a></li>
<li><a href="#tools">What tools can I use?</a></li>
<li><a href="#choose">How do I choose a book to process?</a></li>
<li><a href="#long">How long will it take?</a></li>
<li><a href="#change">What if I change my mind, or don't have time?</a></li>
<li><a href="#do">So what do I have to do?</a>
    <ul>
        <li><a href="#research">Do some research</a></li>
        <li><a href="#list">Keep a "To Do" list</a></li>
        <li><a href="#first">First pass check</a></li>
        <li><a href="#asterisk">Check for asterisks * left by proofreaders,
            making you aware of questions/problems/markup</a></li>
        <li><a href="#markup">Check the markup</a></li>
        <li><a href="#title">Straighten up the title page, table of contents,
            and list of illustrations</a></li>
        <li><a href="#foot">Footnotes</a></li>
        <li><a href="#problems">Check the text for problems</a></li>
        <li><a href="#illus">Handle any illustrations</a></li>
        <li><a href="#join">Rejoin pages</a></li>
        <li><a href="#spell">Spellcheck</a></li>
        <li><a href="#paranoid">Paranoid text checks (stealth scannos, etc.)</a></li>
        <li><a href="#rewrap">Rewrap the text</a></li>
        <li><a href="#gut">Gutcheck</a>
            <ul>
                <li><a href="#common">Some common things to watch for</a></li>
            </ul></li>
        <li><a href="#tn">Create a Transcriber's Note</a></li>
        <li><a href="#backup">Back-up including HTML tags</a></li>
        <li><a href="#plain">Creating a plain text version</a>
            <ul>
                 <li><a href="#formatting">Check formatting&mdash;text version</a></li>
            </ul></li>
        <li><a href="#smooth">Smoothreading</a></li>
        <li><a href="#html">Creating an HTML version</a>
            <ul>
                <li><a href="#main">Main issues to check</a></li>
            </ul></li>
    </ul></li>
<li><a href="#finished">I've finished&mdash;now what...?</a>
    <ul>
        <li><a href="#ppv">Uploading for verification (PPV)</a></li>
        <li><a href="#then">What happens to my book now?</a></li>
    </ul></li>
<li><a href="#du">I've been granted direct upload access. What do I do?</a></li>
<li><a href="#help">Help! I've got a problem with ... </a>
    <ul>
        <li><a href="#missing">Missing or problem images or pages</a></li>
        <li><a href="#multiple">Projects with multiple parts</a></li>
        <li><a href="#other">Other formats</a>
            <ul>
                <li><a href="#html2">HTML</a></li>
                <li><a href="#lilypond">Lilypond</a></li>
                <li><a href="#latex">LaTeX</a></li>
                <li><a href="#pdf">PDF</a></li>
                <li><a href="#proprietary">Proprietary text formats</a></li>
                <li><a href="#unicode">Unicode, UTF-8, UTF-16, etc.</a></li>
            </ul></li>
        <li><a href="#symbols">Symbols and scripts, non-ASCII characters,
            non-Latin scripts, and downright weird things</a></li>
        <li><a href="#footnotes">Footnotes</a></li>
        <li><a href="#sidenotes">Sidenotes</a></li>
        <li><a href="#illustrations">Illustrations</a></li>
        <li><a href="#poetry">Poetry</a></li>
        <li><a href="#tables">Tables</a></li>
        <li><a href="#greek">Greek</a></li>
        <li><a href="#minorlote">Occasional use of other languages</a></li>
        <li><a href="#index">Indexes</a></li>
        <li><a href="#errata">Errata pages</a></li>
        <li><a href="#after">A problem after the project has posted!</a></li>
    </ul></li>
<li><a href="#different">What's different about ... </a>
    <ul>
        <li><a href="#periodicals">Periodicals and &Uuml;berprojects</a></li>
        <li><a href="#drama">Drama</a></li>
        <li><a href="#music">Music</a></li>
        <li><a href="#maths">Maths (LaTeX)</a></li>
        <li><a href="#lote">LOTE (Languages Other Than English)</a></li>
    </ul></li>
<li><a href="#also">See also ...</a></li>
</ul>


<h2><a name="what" id="what"></a>What is post-processing?</h2>

<p>
The purpose of post-processing is to massage the pages worked on in the
rounds into a final <a href="<?php echo $wiki_url; ?>/Etext">etext</a>
for uploading to <a href="<?php echo $PG_home_url; ?>">Project
Gutenberg</a> (PG). On its journey through multiple proofreading and formatting
rounds, the text may have been worked on by hundreds of volunteers. The
post-processor must standardize the formatting of the book and adjust it
to comply with Project Gutenberg's requirements. They must also deal
with any detectable mistakes that have survived all proofreading and
formatting rounds. The ultimate goal of post-processing is to create a
consistently formatted etext, which contains as few errors as possible
and which accurately reflects the intentions of the author. A plain-text
version is always needed (a .txt file), but many projects now also
require other formats. Don't be put off by this&mdash;there are people
who can help with them if you don't want to do them yourself.
</p>


<h2><a name="who" id="who"></a>Who can post-process?</h2>

<p>
Post-processors require more experience than ordinary proofreaders.
Since they are preparing the text for uploading to Project Gutenberg,
they make choices and decisions about the layout and look of the text.
Because of this, post-processing is usually only available for volunteers
who have completed a number of pages in F1.
Refer to the chart on your <a href="../activity_hub.php">Activity Hub</a>
to see if you already have access. If you do not, clicking on the icon
will take you to a statement of whether you meet the requirements, and,
if so, a button to request access (this is automatically granted).
</p>

<p>
If you are not yet eligible, but have a reason for wanting to post-process
(special language skills are a common basis for exceptions),
please request access by sending an email to
<a href="mailto:<?php echo $db_requests_email_addr; ?>"><?php echo $db_requests_email_addr; ?></a>.
</p>


<h2><a name="helpavail" id="helpavail"></a>What help is available?</h2>

<p>
This FAQ contains a <strong>lot</strong> of information, particularly in the
<a href="#help">Help</a> section.
The <a href="<?php echo $post_processing_forum_url; ?>">Post-Processing Forum</a>
also has some helpful sticky threads, especially
the <a href="<?php echo make_forum_url('t', 15019, ''); ?>">No Dumb Questions</a>
topic which is the best place to post new questions. For faster help, try using
<a href="<?php echo $wiki_url; ?>/Jabber_instructions">Jabber</a> to visit
the <?php echo $site_abbreviation; ?> chat room&mdash;there's usually someone around
who can point you in the right direction (all time zones covered!).
</p>

<p>
You can also get <a href="<?php echo $wiki_url; ?>/PP_Mentoring">PP mentoring</a> help.
PG produces its own <a href="<?php echo $PG_faq_url; ?>">guidelines</a>.
Finally, a <strong>strongly</strong> recommended step is to look at existing PG books,
especially if you can find a book similar to the one you are about to post-process
(same author, related topic, etc.).
</p>


<h2><a name="tools" id="tools"></a>What tools can I use?</h2>

<p>
Post-processors use a variety of
<a href="<?php echo make_forum_url('t', 15775, ''); ?>">operating systems</a>
and <a href="<?php echo $wiki_url; ?>/PPTools">PPing Tools</a>
to do the post-processing work. Which ones you use is your choice, but
the minimum software you will need includes:
</p>

<ul>
<li>a text editor (with monospaced font for <a href="#plain">plain text</a> files)</li>
<li>a program capable of opening images</li>
<li>spellchecker</li>
<li><a href="#gut">Gutcheck</a></li>
<li>capability to create and handle zip folders</li>
</ul>

<p>
To create and check an <a href="<?php echo $wiki_url; ?>/HTML">HTML</a> version
you will also need to use an online <a href="http://validator.w3.org/">HTML
validator</a> and <a href="http://jigsaw.w3.org/css-validator/">CSS validator</a>,
<a href="http://infohound.net/tidy/">Tidy</a>, and a link checker.
If your project contains illustrations, you will need an image editor.
</p>

<p>
There are other <a href="<?php echo $wiki_url; ?>/PPTools">useful programs</a>
available which are not essential, but which can be extremely useful and
will usually save you a lot of time. Some have Gutcheck built in,
such as <a href="<?php echo $wiki_url; ?>/Guiguts">Guiguts</a>, for which
there is a lot of <a href="<?php echo make_forum_url('t', 3075, ''); ?>">support</a>,
a <a href="<?php echo $wiki_url; ?>/Guiguts_PP_Process_Checklist">PP Checklist using Guiguts</a>,
and a <a href="<?php echo make_forum_url('t', 6304, ''); ?>">PPing tutorial</a>.
</p>

<p>
If using a Mac, see
<a href="<?php echo $wiki_url; ?>/Post-Processing_on_the_Mac">Post-Processing on the Mac</a>.
</p>


<h2><a name="choose" id="choose"></a>How do I choose a book to process?</h2>

<p>
For your first project, it's best to pick a fiction book with a
relatively small number of pages (less than 200 or so). Here's why:
</p>

<ul>
<li>A low page count makes the work go faster and is easier to handle.</li>
<li>
Fiction usually has fewer words per page and a simpler format than non-fiction,
so it scans more clearly and is less likely to result in OCR errors and
inconsistent formatting.
</li>
<li>
Fiction generally lacks complicating features such as footnotes,
tables, illustrations, poetry, and/or other items that could be
difficult for a new post-processor to deal with.
</li>
</ul>

<p>
Many post-processors did not follow this rule to start with,
and have turned out all right anyway. But it's a reasonable guide.
</p>

<p>There are three good ways to find a book for post-processing:</p>

<ol>
<li>
Check the <a href="<?php echo make_forum_url('t', 15937, ''); ?>">Projects
for new PPers</a> thread for books that have completed the rounds and
would make good starting points. Or post there that you are in the market
for a good book!
</li>

<li>
If you are proofreading or formatting a particularly enjoyable book,
or one that seems quite straightforward, ask the project manager
if a post-processor has been assigned already (or look for that section
on the project page). If there is none, (or sometimes if the project manager
is listed as post-processing it themselves,) ask if you can post-process the book.
You'll need to wait for it to finish the rounds first. An alternative is
reviewing the <a href="../../noncvs/no_PPer_yet.php">Books with no PPer yet</a>
list and contacting project managers directly. Be careful what you ask for,
though, as you may get it.
</li>

<li>
Contact a <a href="<?php echo $wiki_url; ?>/PP_Mentors">PP Mentor</a>.
Not only can they help if you have questions about the process, but
they can occasionally provide more suitable projects, or suggest
alternatives to the above methods.
</li>
</ol>

<div class="note">
Note: There is no commitment in volunteering for a book. If you wish
to stop post-processing it for any reason, it's best to contact
the person who assigned it to you, so they can pass it on appropriately.
They will <strong>not</strong> twist your arm or criticize you for not finishing.
Think of it as allowing someone else the chance to work on it, and
freeing up your own valuable time for another project!
</div>

<p>
Download your chosen text by going to its project page,
scrolling to the bottom of the page, and selecting "Download Zipped Text".
</p>

<p>
Scroll through the whole text to see if there are any difficulties,
like footnotes, poetry, foreign languages, dialects, and tables in it.
This way, you will know what you will be dealing with
before you commit to the project. If you see any of these items,
you might want to pick a different project. But if you think
you can handle it, give it a try! There's always lots of help available.
</p>

<p>
Check the project thread for your book's title to see what proofreaders
have been saying about it. Again, this can alert you to issues
that might make the work more difficult than you had realized.
</p>

<p>
If you decide you want to work on the book, and the book was not
already checked out to you by the project manager, then go to the project page,
scroll down and select the "Check Out Book" button.
Make sure that the book appears as checked out to you, or
you might end up working for hours on it only to find that someone else
has checked it out and submitted it! Your post-processing choice will appear
near the middle of the
<a href="../tools/pool.php?pool_id=PP">post-processing page</a>.
</p>

<div class="note">
Note: Occasionally, perfectly lovely books appear in the PP Pool
(at the bottom of the post-processing page). If you wish
to take one of these to work on, click on its title on that page,
perform the above checks (downloading text, reviewing the
project discussion, etc.) and if you still want to go ahead, scroll to
the bottom of the project page to the "Check Out Book" button.
Click it, double-check that the book has been assigned to you,
and start work.
</div>


<h2><a name="long" id="long"></a>How long will it take?</h2>

<p>
It's very difficult to answer this question in advance.
The time that a book will take to complete depends on three factors:
</p>
<ul>
<li>the difficulty and length of the work itself</li>
<li>the tools being used</li>
<li>the amount of experience the post-processor has</li>
</ul>

<p>
It can vary from several hours to several days.
Some especially difficult works can take weeks (or more!) to complete.
Remember to save your work often, using a new filename each time,
so that if you make a mistake, you can easily recover.
Take it at your own pace&mdash;you will be the last person going through
this book in detail before its posting (although two other people
will verify your work).
</p>

<p>
Try not to feel discouraged if it seems like it takes a long time
to complete an "easy" book. Concentrate on learning the process of
post-processing, familiarizing yourself with any tools you might be using,
and doing a quality job, rather than on working quickly. You will speed up
naturally with practice.
</p>


<h2><a name="change" id="change"></a>What if I change my mind, or don't have time?</h2>

<p>
If you realize that the project you've chosen is too complicated for you,
or if you find yourself short of time, it is perfectly okay to return
the project to the PP pool using the "Return to Available" button
on the project page. You can look for an easier project straight away
or take another one when you have more time.
</p>

<p>
To return a project, find the title of the book you are working on
on your <a href="../tools/pool.php?pool_id=PP">PP page</a> and
click on its title. That shows you the specific project page for that text
and, scrolling to the bottom, you will find an option for Return to Available.
Click that button to go to the upload page. To put the project
back into the Post-Processing Pool, leave everything blank and
click the Return project button.
</p>

<p>
If you have done considerable work on the project, you can upload
a partially finished project to the PP pool for another post-processor to complete.
After choosing the Return to Available option, when the upload page opens,
you can upload a zipped folder and leave a comment for the next person.
</p>


<h2><a name="do" id="do"></a>So what do I have to do?</h2>

<p>
Many post-processing tasks can be automated using tools
designed to minimize the complexity and repetition of such jobs.
Please refer to the software-specific tutorials or user guides
to find out how to use these utilities effectively.
</p>


<h3><a name="research" id="research"></a>Do some research</h3>

<p>
Read the project comments and project thread for your post-processing project.
If the proofreaders found anything of concern, make a note of it
for special attention while processing the text.
You will also need to make sure you follow the project manager's instructions
for the text. Many request an HTML version as well as the text version.
</p>


<h3><a name="list" id="list"></a>Keep a "To Do" list</h3>

<p>
You may like to put notes about your progress in the "Post-Processor's
Comments" box which can be found on the project page of your project,
towards the bottom. These comments are visible to those who need to see them.
They can act as a "To Do" list, or notes on points to watch out for from proofreader
comments, and are particularly useful if you have to take a break from post-processing
for a short while, so you can start working with your project right where
you left off. Also, if you leave <?php echo $site_abbreviation; ?> your notes
could help another volunteer to take over where you have left off.
</p>

<p>
There is a <a href="<?php echo $wiki_url; ?>/Guiguts_PP_Process_Checklist">Guiguts
PP Process Checklist</a> that may be useful if you use Guiguts.
Some post-processors make their own checklist, some in a spreadsheet format,
some at the top of the text they are working with (but if you use this option
remember to remove your list before uploading the project for verification).
</p>


<h3><a name="first" id="first"></a>First pass check</h3>

<p>
Check through the text page by page, opening the corresponding page scan
in your image viewer. You'll quickly notice unmarked poems or
block quotes this way. Check for missing pages (rare, but it does happen)
and illustrations. If the project has problems like missing pages,
it would be nice if you could go to the project page and state the issue
in the comments in order to make others aware of it.
</p>


<h3><a name="asterisk" id="asterisk"></a>Check for asterisks * left by proofreaders,
making you aware of questions/problems/markup</h3>

<p>
Run a search for * to find notes left by proofreaders/formatters
to make you aware of their questions/solutions and potential problems.
</p>


<h3><a name="markup" id="markup"></a>Check the markup</h3>

<p>
Make sure that the /* */, /# #/, etc. tags are balanced.
Be sure that any poetry is in the correct markup to save messes later.
This is a good time to check each poem is indented correctly or has
the relative indents correctly added. Every &lt;i&gt; tag needs
a closing and properly placed &lt;/i&gt; tag and so on. You may wish to change
some formatting tags to markup specific for your post-processing tools
(e.g. /p p/, /f f/); check your tool's manual for details. Some PMs
may request particular markup in the rounds. Also, check any markup
that ranges over a page break and make sure it will still result in
the desired formatting (usually by deleting all but the first and
last markers for a particular section).
</p>

<table summary="key to formatting tags" cellpadding="1" cellspacing="0" border="1">
<tr>
  <th>&nbsp;</th>
  <th><a href="#rewrap">Rewrap?</a></th>
  <th>Indent?</th>
</tr>
<tr>
  <td>No special markup, the default</td>
  <td>yes</td>
  <td>no</td>
</tr>
<tr>
  <td>/*&nbsp;*/ poetry, etc.</td>
  <td>no</td>
  <td>no</td>
</tr>
<tr>
  <td>/#&nbsp;#/ block quotes, etc.</td>
  <td>yes</td>
  <td>yes</td>
</tr>
<tr>
  <td colspan="3">Not officially adopted, but in use by various post-processing tools</td>
</tr>
<tr>
  <td>/$&nbsp;$/ tables, etc.</td>
  <td>no</td>
  <td>no</td>
</tr>
<tr>
  <td>/p&nbsp;p/ poetry, etc.</td>
  <td>no</td>
  <td>yes</td>
</tr>
</table>

<p>
All of these markups normally should have a blank line before the opening tag
and a blank line after the closing tag. They should be on a new line
with no other text, unless your post-processing tool allows it.
</p>


<h3><a name="title" id="title"></a>Straighten up the title page,
table of contents, and list of illustrations</h3>

<p>
When formatting the title page, you have a bit of leeway.
You can adjust the pieces a bit if you like: for example,
you could move the author's name directly under the "by".
Relative indenting is not required, but can be added if you wish.
</p>

<p>
Do block indent a consistent amount (from one to four spaces) if there are
consecutive lines that should not be rejoined later in the process.
The space is a flag in many text readers to preserve the given line endings
should the general text need to be rewrapped to different margins.
</p>

<p>
For the table of contents and list of illustrations, please retain
the page numbers. Line up the chapter titles and page numbers
to make it look neat and easy to read. Copying the original format
of the table of contents usually works fairly well. Leave all
the original information on the title page, including the edition,
year of publication and any copyright notice (unless this is
a reprint&mdash;check with the project manager if in doubt).
It is better to keep as much information as possible than to try to find it
once the book has been posted for years.
</p>


<h3><a name="foot" id="foot"></a>Footnotes</h3>

<p>
You will need to rejoin footnotes split across pages.
Then, in the plain-text version, you can put the footnote
after the paragraph it refers to or at the end of the chapter or section.
Make sure that the number/letter/symbol in the text matches the tag in
the note itself. In-line footnotes (footnote within a line of text)
are discouraged even when extremely short.
</p>

<p>
Consider using end-of-paragraph footnotes if the footnotes are short,
unique, and not common. Use end-of-section or chapter footnotes
for longer footnotes (such as those that have poetry or block quotes),
or those that have multiple references in the text for one footnote.
Whichever you choose, be consistent within the work. Use all end-of-paragraph
footnotes or end-of-section/chapter footnotes within one work.
Don't switch back and forth.
</p>

<p>
For the HTML version, they can be moved to the end of the chapter or section
or to the end of the project. They also need to be hyperlinked.
Most of the post-processing tools will do this automatically.
Refer to the tutorials, guides, or manual for your software, to find out how.
</p>

<p>
The preferred method is to renumber the footnotes so that each one in the book
has a unique number, alphabetic letter, or Roman numeral to make it easier
for the reader to search the text. Alphabetic letters and Roman numerals
are not recommended for more than 20&ndash;30 footnotes as they become hard
to read/distinguish. There may be some projects where you may prefer
to retain the numbering as in the original publication.
</p>

<h3><a name="problems" id="problems"></a>Check the text for problems</h3>

<ul>
<li>end-of-line spaces need to be removed to prevent double spaces
    when text is rewrapped</li>
<li>inconsistent line spacing around chapter and section headings</li>
<li>spaces around hyphens</li>
<li>spaces before punctuation . ! ? ; : ,</li>
<li>spaces around quotes in English and/or LOTE</li>
<li>mis-matched quotation marks</li>
<li>he/be errors</li>
<li>spaces around (&nbsp;){&nbsp;}[&nbsp;]</li>
<li>spaces within abbreviations</li>
<li>multiple spaces in non-marked text (skip /* poetry */ )</li>
<li>incorrectly formatted thought breaks</li>
<li>incorrectly formatted ellipses (according to the rules of the text's language,
    or ensuring they all match the original if that is what you prefer)</li>
<li>dashes with three hyphens (---) instead of two (--) for an em-dash</li>
<li>appropriate spacing of em-dashes (-- and ----)</li>
<li>incorrect paragraph breaks</li>
<li>sort out any asterisks/stars/daisies/comments left in the text by
    proofreaders and formatters</li>
<li>
compare hyphenated words throughout the text and decide whether to standardize,
and whether to mention in a transcriber's note&mdash;for example,
if there are 20 occurrences of "to-morrow", but only one of "tomorrow"
you can decide whether to change the irregular one, but if there is not
a clear majority, you will have to decide whether to leave them as proofread,
or make a judgment on which way to change the odd one out (and then,
whether you note this in a <a href="#tn">Transcriber's Note</a> or not)
</li>
<li>
As the post-processor, you are responsible for resolving problems noted
by the proofreaders. If you need advice or a second opinion, try any
of the methods listed in the <a href="#help">Help</a> section of this document.
</li>
</ul>


<h3><a name="illus" id="illus"></a>Handle any illustrations</h3>

<p>
Move each illustration tag to an appropriate paragraph break.
Some post-processors like to have them just before, or after,
the text they illustrate. Others prefer to place them at the end
of the chapter, not wishing to interrupt the flow of the text.
Do whatever you think is right for your book.
</p>

<p>
Note: Keep illustration markers in the plain-text version, in case
people want to refer to the HTML version later. Please do not
delete them unless requested in the project comments and/or discussion.
</p>

<p>
If you do not want to produce an HTML version, but your book has pictures,
post in the <a href="<?php echo make_forum_url('t', 12240, ''); ?>">HTML pool</a>,
where you can enlist someone to generate the HTML and pass it back to you
for uploading. HTML versions are required for every book produced
at <?php echo $site_abbreviation; ?> with pictures (even if
the project manager does not request it).
</p>


<h3><a name="join" id="join"></a>Rejoin pages</h3>

<p>
Remove page separators, checking either side of them to see
if the next page requires a blank line, is a section or chapter,
or needs to be continuous text. You can rejoin words split across pages
at this point if you haven't done so earlier.
</p>


<h3><a name="spell" id="spell"></a>Spellcheck</h3>

<p>
Even if it looks like it's going to be a pain, spellchecking is always needed.
Texts written before spelling was regularized might be the only reasonable
exception, but even for those spellchecking is often useful. Even books with
dialect or other deliberate non-standard spelling can be spellchecked.
You may want to leave this step until later in your checklist, and/or repeat
the spellcheck whenever you type in new information, including a transcriber's note.
</p>

<h3><a name="paranoid" id="paranoid"></a>Paranoid text checks (stealth scannos, etc.)</h3>

<p>
These may be run by separate tools or by your main post-processing program.
Refer to the manual or tutorial for the toolset you are using, or ask
in the <a href="<?php echo $post_processing_forum_url; ?>">Post-Processing Forum</a>.
</p>

<p>
Examples include "smart" programs which can check for he/be irregularities,
or regexes (a form of search) which flag unusual letter combinations,
such as "tb" (possible scanno for "th") or "rn" (for "m").
</p>

<p>
Various regex-searches are available and some tools will run these as a set,
through your usual search-and-replace box&mdash;again, check the manual/tutorial
for the software you're using. Otherwise, have a look at
the <a href="<?php echo make_forum_url('t', 4381, ''); ?>">Regular Expression
Clinic</a> for more information and help.
</p>

<p>
A great formatting check to run is the regex <strong>\n\n\n</strong>
which catches all chapter and section spacing allowing you to confirm
their consistency, as well as finding any extra line breaks between
paragraphs&mdash;especially common after block quotes or poetry.
It's a good idea to run this again on the text version,
after you've removed markup such as /**/ and /##/. (See <a href="#remove">below</a>.)
</p>


<h3><a name="rewrap" id="rewrap"></a>Rewrap the text</h3>

<p>
Time to rewrap. PG advises to keep the HTML version as close as possible
to the text version, so some post-processors will use the rewrapped
text version for the basis of their HTML version of the ebook.
Some however prefer to use the text before rewrapping to avoid having
to adjust the text version a second time after formatting tags have been
converted&mdash;see <a href="#plain">Creating a plain text version</a>.

<p>
Did you see any poetry, tables, etc.? If not, rewrapping the lines
should be easy. You will need to rewrap the lines to around 65&ndash;75
characters in length. (See <a href="#linelength">PG's recommendation
for line length</a>.) Each program has a different way of doing this,
and you will have to find the way that works best for you. Read the manual
or instruction book for your utility.
</p>

<p>
If you found poetry, tables, etc. care needs to be taken when
rewrapping that line endings are preserved as intended, and that they
are block indented from at least one to four character spaces to prevent
rewrapping in future versions of your texts.
</p>

<p>
If worst comes to worst and you cannot find an easy way to rewrap the lines,
find and replace all line breaks with spaces, count any line to find approximately
where 60&ndash;72 characters falls, and insert line breaks manually at this point.
It's painful, but it works. (Be grateful that you chose a book with a low page count!)
Alternatively, type a line like this:
</p>
<pre><kbd>123456789012345678901234567890123456789012345678901234567890123456789012</kbd></pre>
<p>
at the top of your text and use this as your guide. However, manually rewrapping
in this way should not be necessary.
</p>

<p>
Once your text is suitably rewrapped, remove any end of line spaces.
(Again, use the post-processing software wherever possible!
All current tools include this task.)
</p>


<h3><a name="gut" id="gut"></a>Gutcheck</h3>

<p>
The Gutcheck tool was written
specifically to pick out many of the most common problems with PG texts.
It is probably the single most important check you will perform.
Follow the instructions with your post-processing software.
If you are not using a post-processing-specific tool, you can download
Gutcheck from <a href="http://gutcheck.sourceforge.net/">here</a>,
and run it according to the instructions given there. Either run the check
initially with all options turned on, or run each check individually,
but make sure not to skip any. Check every potential problem that
it brings to your attention. Not all Gutcheck "flags" are genuine errors
(for example, it may report short lines where the text contains poetry
or a table), but each must be looked into and corrected if necessary.
Continue to run Gutcheck after each series of corrections until
it doesn't flag any more "true" errors.
</p>

<p>
If you do not want to download Gutcheck, use Project Gutenberg's
<a href="http://upload.pglaf.org/gutcheck.php">online gutcheck</a>.
</p>

<h4><a name="common" id="common"></a>Some common things to watch for</h4>

<ul class="spaced">
<li>
Footnote markers are falsely flagged as "Wrongly spaced brackets".
Check them anyway.
</li>

<li>
Lengthy hyphenated words often cause short lines above or below.
Try rewrapping just that paragraph a few spaces shorter to rearrange
the words sufficiently to cure this error. Short lines for the
table of contents, lines of poetry, etc. are okay.
</li>

<li>
<a name="linelength" id="linelength"></a>PG's standard line length is
60&ndash;70 characters for regular text and should be no more than
75 characters wide. If a threshold of 72 is used, with the random lengths
of words, most lines will end up being 70 or less. The PG posting team also makes
allowance for Chinese text, which has double-wide characters, to have line lengths
around 40 characters. There may be justification for 80 characters for tables or
other essentials (long line poetry might be another example). If there's absolutely
no way to shorten a feature such as a family tree, you can leave it as is. It is often
worth posting in the <a href="<?php echo $post_processing_forum_url; ?>">Post-Processing Forum</a>
though as others may see a sensible way to condense or reformat the feature. 
</li>

<li>
Unless you are checking a deliberately-ASCII version of your text,
you do not need to worry about characters flagged by "Non-ASCII character".
</li>

<li>
Wrongly spaced/missing quotes often appear where characters' quoted speech
runs through several paragraphs. Check these, but if they are right according
to the proofreading guidelines, that's good enough for Gutenberg posting.
</li>
</ul>


<h3><a name="tn" id="tn"></a>Create a Transcriber's Note</h3>

<p>
If you make any changes to the text it is a good idea to include a
Transcriber's Note. Sometimes these are quite simple:
</p>
<pre><kbd>Transcriber's Note: Punctuation has been normalized.</kbd></pre>

<p>
Sometimes the writing of the Transcriber's Note is not always as straightforward
as it might seem. Some suggest using wording such as "obvious errors
have been corrected" but others say that what is obvious to one person
may not be obvious to another. Also, correcting what someone might think
is an obvious error, may in fact be correct spelling/phrasing for the time
the book was written.
</p>

<p>A useful general one, especially for older, less regular texts, is:</p>
<pre><kbd>Transcriber's Note: All apparent printer's errors retained.</kbd></pre>
<p>
This one stops the <a href="<?php echo $wiki_url; ?>/PG_Posting_Team">PG
whitewashers</a> from getting long errata requests to "fix" your text.
It is not, however, an excuse for leaving in bad OCR, scannos, or
similar detectable problems that are wrong in comparison to the page scan.
</p>

<p>Sometimes the notes can be quite lengthy.</p>
<pre><kbd>Transcriber's Notes:

Page 13, "10,00 troops" changed to "10,000 troops." (We fought 10,000 troops at St Germaine.)

Page 27, "Faw-cett" changed to "Fawcett". (Major Fawcett dictated the memo.)

etc. etc.</kbd></pre>

<p>
While we don't retain the individual page numbers in the text version,
this gives the reader an idea of where it is in the book. The reader can search
for the text you have included in the parentheses to find the exact location of your edit.
</p>

<p>
In the HTML version, the use of "hover" or "inserted" tags is a good way
to shrink your list of changes while still maintaining the integrity
of the original. Check the post-processing forum for ways of doing this,
or follow the instructions here:
<a href="<?php echo $wiki_url; ?>/CSS_Cookbook/Basic_Text#Corrections">CSS Cookbook&mdash;Corrections</a>.
</p>

<p>
Many post-processors do fix what appear to be printer's "errers"
(such as changing "errers" to "errors"). <strong>Do not</strong> modernize
or switch the spelling from British English to American English or
the other way around however. We are preserving history, not improving it.
</p>

<p>
Some put shorter notes, or ones that apply to the whole text
in a general way, at the start of the book (before the title page),
and longer lists at the end of the book (after any index or footnotes).
</p>

<p>
Transcriber's Notes are optional, but can help the reader's understanding
of how you've processed the text. It's up to you how much or how little
you note. If in doubt, talk to other post-processors in the forums, Jabber,
or by PM about how they've handled various situations.
</p>


<h3><a name="backup" id="backup"></a>Back-up including HTML tags</h3>

<p>
At the end of the above process, you have a processed book which contains
HTML markup, as well as <?php echo $site_abbreviation; ?> tags like
[Footnote] or &lt;tb&gt;. <strong>Save a copy of this "dual" purpose file,
calling it something like &lt;name-backup.txt&gt;</strong>.
</p>


<h3><a name="plain" id="plain"></a>Creating a plain text version</h3>

<ul class="spaced">
<li>
Take the file you've been working on and name it something like:
&lt;funnyname.txt&gt; Make sure you still have a version of the file
containing the markup and call that &lt;funnyname.html&gt;.
If you are going to produce different types of text files,
call another copy &lt;funnyname-ltn1.txt&gt; and &lt;funnyname-utf8.txt&gt;.
</li>

<li>
Note that all file and folder names need to be in lower-case characters
to ensure there is no upper/lower-case conflict later in the process
at post-processing verification (PPV) and/or at PG. PG does ask for
lower case and though it is technically not necessary this practice
does prevent potential future linking problems. Also helpful if you
are going to be working on many books is to give the file a name
that can easily be associated with the book you are working on,
and keep the name short but at least four characters long
(files of three characters have been known to cause PG problems).
</li>

<li>
Use a monospaced font to enable alignment in display items such as
tables and verse.
</li>

<li>
<strong>Now that you have various copies of your master file, you need
to change them all if you find and correct any further errors.</strong>
</li>

<li>
For the plain text version(s), &lt;i&gt; and &lt;/i&gt; need to be changed to _ and
&lt;b&gt;/&lt;/b&gt; to your preferred bold markup such as =; see
the <a href="<?php echo make_forum_url('t', 14131, ''); ?>">bold-markup thread</a>
for discussion of options. &lt;f&gt; and &lt;g&gt; tags can be handled similarly to bold,
or stripped out if you prefer.
</li>

<li>
If your project contains any &lt;sc&gt; markup, refer to the
<a href="<?php echo $wiki_url; ?>/Guide_to_smallcaps">Guide to Small Caps</a>
to find out how to handle them.
</li>

<li>
Do a quick search for the &lt; and &gt; characters to make sure
none have slipped through.
</li>

<li>
Determine how you want to handle [oe] ligatures. Some post-processors
will convert them to just oe in the plain text version. If the brackets
are retained, mention this in a transcriber's note.
</li>

<li>
If you want to tidy your footnotes, (that is, make them read [1] text,
rather than [Footnote 1: text] do it now).
</li>

<li>
If you haven't yet rewrapped your text to around 72 characters,
do that now&mdash;see <a href="#rewrap">time to rewrap</a>.
</li>

<li>
<a name="remove" id="remove">Remove markup</a> from the funnyname.txt file.
Rewrap markers and [Blank Page] tags need to go. Make sure there are no queries
or notes left in the text.
</li>
</ul>

<h4><a name="formatting" id="formatting"></a>Check formatting&mdash;text version</h4>

<p>
PG will accept <a href="#other">alternatives</a> to the following.
The important thing is to be consistent throughout your book.
</p>

<ul class="spaced">
<li>
Chapters should have four blank lines above them, one between lines
of the chapter heading, and two blank lines after, but before
the main text of the chapter starts.
</li>

<li>
Sections should have two blank lines above, and one blank line after.
This is all as per 
<?php echo $site_abbreviation; ?> <a href="formatting_guidelines.php">Formatting Guidelines</a>.
</li>

<li>
&lt;tb&gt; should be replaced with a line of asterisks&mdash;that is,
7 spaces, followed by 5 stars, each spaced by 7 from the next, like this:<br>
<pre><kbd>       *       *       *       *       *</kbd></pre>
</li>

<li>
Poetry should be indented from one to four spaces (this is a PG requirement,
to prevent rewrapping in future versions of your text). Indents within the poem,
i.e. relative indents, should be added on to your chosen indent.
(For example, if a line is indented by 2 spaces from the line above, and you are using
a 4-space indent for poetry, in your final version this line will be indented
6 spaces altogether.)
</li>

<li>
Block quotes should also be indented to show their separation from the rest of the text.
If blocks in the book are not separated from the rest of the text, i.e. they appear as
regular paragraphs, there is no need to indent them.
</li>

<li>
Tables, including tables of contents and lists of illustrations,
also need to be indented to avoid rewrap/respacing.
</li>

<li>
Unusual features&mdash;tables, Greek, poetry, etc.
see the <a href="#help">Help! section</a>.
</li>

<li>
Do a final <a href="#gut">Gutcheck</a>, to make sure that there are no
remaining problems, and that no issues have been introduced during the
tidy-up process (such as short lines being left after the removal of HTML markup).
</li>
</ul>

<p>
If you want to make your book available for
<a href="<?php echo $wiki_url; ?>/Smooth-reading_FAQ">smoothreading</a>,
now's the time.
</p>

<p>Mac and *nix users need to change line endings to CR/LF.</p>


<h3><a name="smooth" id="smooth"></a>Smoothreading</h3>

<p>
An extra pair of eyes is always helpful in finding things you might have overlooked
in the text. <a href="<?php echo $wiki_url; ?>/Smooth-reading_FAQ">Smoothreading</a>
is an option available to all post-processors and is generally
done on a text version.
</p>

<p>
Save a new version of your book (such as &lt;funnyname-smooth.txt&gt;),
then place this file into a zip folder.
</p>

<p>
Make sure that the file name contains some combination of
a-z, 0-9, -, _ and one . separating the filename from the extension
(no capital letters, no spaces, no special characters other than
those mentioned above). For example:<br>
<b>Correct</b>: funnyname-smooth.txt<br>
<b>Incorrect</b>: Smooth.txt
</p>

<p>
Go to the project page for your book. At the bottom of the page,
you have three options: make the project available for smoothreading for
one week, two weeks, or four weeks. Select the desired duration and
upload the zip folder with the text file for smoothreading.
You can provide comments about what to look for during proofreading,
or to ask for attention in a particular section (this is very helpful
in long texts). You might also like to advertise the availability of your book
in the project thread, or in relevant team threads (see the
<a href="<?php echo $wiki_url; ?>/Teams_List">Teams List</a> for ideas).
</p>

<p>
Smoothreaders will mark possible errors in the text with [**description of query].
This is a standard format and should not be altered in your comments.
When they finish, they will upload the smoothread project back to the project.
At the end of the smoothreading period, you can download the smoothread versions
from the bottom of the project page and search the text for [**.
Not all [**comments] will be valid, just correct those that are.
Make your corrections in the master file which still contains &lt;i&gt; markup
(or else make each change in every version of the text that you have,
e.g. plaintext and HTML).
</p>

<p>
While your book is being smoothread, why not start work on any other formats
that are required, or begin fixing up any illustrations?
</p>


<h3><a name="html" id="html"></a>Creating an HTML version</h3>

<p>
Go back to your <a href="#backup">marked-up copy</a>.
Use a <strong>copy</strong> of the marked-up file, named something like:
&lt;funnyname-htm.html&gt;. Make sure you keep a version of
the marked-up file for backup and reference.
</p>

<p>
See <a href="#html2">HTML</a> in the Help section of this document and
<a href="<?php echo $wiki_url; ?>/Philosophy_Guide_to_creating_HTML_versions">Creating
HTML versions</a>.
</p>

<p>
Also see PG's <a href="<?php echo $PG_faq_url; ?>">guidelines</a>.
</p>

<h4><a name="main" id="main"></a>Main issues to check</h4>

<ol class="spaced">
<li>
Ensure the HTML header title contains the line &lt;title&gt;The Project Gutenberg
eBook of Name of Book, by Name of Author&lt;/title&gt;
</li>

<li>
Page numbers are correct and appear on the first line of the page
of the original publication. Sometimes page numbers are not used
by post-processors, but many feel they add value and do retain them.
</li>

<li>
Images must be in a folder called &lt;images&gt;, file names must all
be in lower-case characters, and the path of the links must go to
the images folder within the same folder that contains the HTML file.
</li>

<li>
<a href="http://jigsaw.w3.org/css-validator/">Validate the CSS</a>&mdash;the
validator allows the full HTML file to be uploaded from your own computer.
If you have web space, upload the file and check from there instead
if you prefer, or, if you have direct upload access to PG you can
validate when uploading.
</li>

<li><a href="http://validator.w3.org/">Validate the HTML markup</a>.</li>

<li>
<a href="http://infohound.net/tidy/">Check HTML with Tidy</a>&mdash;note,
don't let Tidy change your file, instead view the flags and make the appropriate
changes yourself, otherwise the code will be difficult for anyone else to read
or troubleshoot. Various tools including Guiguts have an inbuilt Tidy check.
</li>

<li>
Check links&mdash;if you have web space you can use the online
<a href="http://validator.w3.org/checklink">link checker</a>;
if not, various tools including Guiguts have inbuilt link checkers.
</li>

<li>
With the clear expectation from PG and the DPF Board
that our projects should look good in e-reader versions,
the <a href="http://www.pgdp.net/wiki/Easy_Epub">Easy Epub</a> wiki pages
are intended to help with making some simple changes
to improve them if necessary - see below for more details.
</li>
</ol>

<p>
The <a href="http://www.pgdp.net/wiki/Easy_Epub">Easy Epub</a> wiki pages
mentioned above include
<a href="http://www.pgdp.net/wiki/Easy_Epub/Viewing">Viewing</a>
(How to use epubmaker to view your project in e-reader format,
even if you don't have an e-reader)
and just four things to check:
</p>
<ul>
<li><a href="http://www.pgdp.net/wiki/Easy_Epub/Headings">Headings</a>
- How to use headings correctly.
</li>
<li><a href="http://www.pgdp.net/wiki/Easy_Epub/Cover">Cover Pages</a>
- How to include a coverpage, whether one was supplied with the project or not.
</li>
<li><a href="http://www.pgdp.net/wiki/Easy_Epub/HRs">Horizontal Rules</a>
- Horizontal rules do not center automatically in epub versions,
but a very easy edit fixes this.
</li>
<li><a href="http://www.pgdp.net/wiki/Easy_Epub/Dropcaps">Dropcaps</a>
- A couple of ideas to help with dropcaps if you have them, at least until
the Best Practices method is accepted by PG.
</li>
</ul>

<p>These few simple changes will make our books look good on e-readers,
without having to understand all the principles described in the Best
Practices document. </p>

<p>
Note that external links are generally not permitted, except for
links to other ebooks within the PG site. If used, there must be a disclaimer
at the beginning of the file to explain that links going outside of
the document may not work for various reasons, for various people,
at various times.
</p>


<h2><a name="finished" id="finished"></a>I've finished&mdash;now what...?</h2>

<h3><a name="ppv" id="ppv"></a>Uploading for verification (PPV)</h3>
<p>
Create a new zip folder. Keep the filenames short, with only letters, numbers,
hyphens, and/or underscores&mdash;no spaces or special characters like ?, #, $,
etc. Filenames and directory names must be all lower case. Add into this archive
your plaintext file, and any other formats that you've made. Any illustrations
should already be stored inside an "images" folder, and this entire folder
should be added to the zip archive. If you've been post-processing with Guiguts,
add into the archive the .bin files for the plaintext version and HTML version
if they are available&mdash;this is incredibly helpful for post-processing verifiers
(PPVers) who also use Guiguts. They won't be uploaded to PG. Be sure
<strong>not to include the page scans</strong> in your zip archive.
</p>

<p>Your zip folder should look something like this:</p>

<ul>
<li>projectname.txt (this is the Latin-1 text version)</li>
<li>projectname.html</li>
<li>projectname.txt.bin (if you have it from Guiguts)</li>
<li>projectname.html.bin (if you have it from Guiguts)</li>
<li>images (a folder)&mdash;check there are no thumbs.db or system files in this folder
    <ul>
        <li>image1.png</li>
        <li>image2.png</li>
        <li>image3.png</li>
    </ul></li>
<li>projectname-utf8.txt (ONLY if you are including a UTF-8 file
    as well as or instead of projectname.txt)</li>
<li>projectname-ascii.txt (ONLY if you are including an ASCII file
    as well as or instead of projectname.txt)</li>
</ul>

<p>Check there are no system files such as a thumbs.db in any of the folders.</p>

<p>
Depending on your zip software, you may have to adjust its settings to
"Save Relative Paths". This prevents the PPVer from getting extra
(undesired) folders on their computers.
</p>

<p>
If you are using a Mac, you may need to "omit Finder files" too
(leaves out invisible files).
</p>

<p>
Go to the project page for your book, and select Upload for Verification
from the buttons at the bottom of the page. Include an email address
in your post-processing comments section if you want email notification
when the book is Posted! You can also use these comments to note any checks
you've done or point out special features of the work which the PPVer should
be alert to. Ensure that your <a href="../userprefs.php">site preferences</a>
with regard to post-processing credits are correct, as they are what will
be used to credit you in the finished book. If you do not wish to be credited,
or would like a different name to be used, please note this in the comments.
</p>

<p>
Your email address will not be displayed in the credits line, but can be used
by the PPVer to give you feedback (if you request that option). If you do not
request this, feedback will be sent via a private message on the site.
</p>


<h3><a name="then" id="then"></a>What happens to my book now?</h3>

<p>
First, your book goes to an experienced post-processor for Post-Processing
Verification (PPV). This person will carefully go over your work making sure
that all of the requirements have been met, i.e., spellcheck has been done,
images are correctly sized and formatted, it passes Gutcheck, the HTML is
valid, etc. Sometimes a PPVer will request that a project be returned to you
for further work. This does not mean you are a horrible post-processor.
It probably just means that you missed a step or two of the process.
An email or private message will accompany a return explaining why
and what steps you can take to repair your file and usually offering
assistance or suggesting where assistance can be obtained.
</p>

<p>
After your work has completed PPV, the PPVer uploads it to Project Gutenberg.
There a friendly <a href="<?php echo $wiki_url; ?>/PG_Posting_Team">Gutenberg
whitewasher</a> (WWer) will make a final check of your work (and the PPVer's work)
and add the Project Gutenberg boilerplate of names and legal information.
Sometimes a WWer will have a question for you and that question may come
through your PPVer.
</p>

<p>
Finally your project is posted on Project Gutenberg for the world to enjoy!
Congratulations! After your project posts, you can expect to receive feedback
if you have not already received feedback from your PPVer. This feedback
will tell you the great things that you did along with any suggestions
for improvements in future work. Feel free to contact your PPVer with
any questions that you have about your project. If you do not receive feedback
and your project has posted, please drop a line in the
<a href="<?php echo make_forum_url('t', 19095, ''); ?>">PPVer help requested
thread</a> and someone will look into it.
</p>

<p>
If you find an error after the book has posted, (really, it sometimes happens),
send a note to your PPVer with the details. The PPVer will contact the WWer.
If it has been posted more than a week or two, you can send an
<a href="http://www.gutenberg.org/wiki/Gutenberg:Contact_Information">errata</a>
note to PG.
</p>


<h2><a name="du" id="du"></a>I've been granted direct upload access. What do I do?</h2>

<p>
Once you've had several projects run through the PPV process, and have been
granted the ability to upload your work directly to PG, you will be sent
instructions by the PPV coordinator on how to proceed. Please also see
the <a href="<?php echo make_forum_url('t', 27097, ''); ?>">Guide to
Direct Uploading (DU) and Posting to PG</a> for more details.
</p>


<h2><a name="help" id="help"></a>Help! I've got a problem with ... </h2>

<h3><a name="missing" id="missing"></a>Missing or problem images or pages</h3>

<p>
Sometimes the content provider accidentally skips one or more pages when scanning.
They usually check through afterwards for any missing pages, but don't rely
on this&mdash;check for yourself, too. Occasionally the scan is present,
but part or all of it is unreadable. First, attempt to contact the PM
to get a better scan. If the PM is for some reason unable to get a good scan
for you, there are other people who can try to get these pages for you.
Find them on the <a href="<?php echo $wiki_url; ?>/Missing_pages">Missing Pages
Wiki</a>. You can research here, looking at various library catalogues for
Missing Page Finders and contacting them by PM if you find a copy of your book
in their library. If no catalogues seem to have the book, log into the wiki
and post the book's details and your username in the "Missing Pages" list.
</p>

<p>
If you do obtain additional pages, illustrations or just replacement images,
please email <a href="mailto:<?php echo $db_requests_email_addr; ?>"><?php echo $db_requests_email_addr; ?></a>.
Include the location of the images on <?php echo $uploads_account; ?> (your 
page-finder can tell you this), the title and projectID of the project,
and the project will be fixed for you. Please wait for confirmation before 
uploading your project to PPV or PG. <strong>This is very important for
archiving purposes.</strong>
</p>


<h3><a name="multiple" id="multiple"></a>Projects with multiple parts</h3>

<p>
If you have multiple sections of a single book and you would like
to have them "stitched together" for ease of post-processing, please email
<a href="mailto:<?php echo $db_requests_email_addr; ?>"><?php echo $db_requests_email_addr; ?></a>.
Working on one file is easier than doing your own "stitching" or working on
separate files, and is especially helpful if you wish to retain the original
page numbers in HTML, or if the png image names overlap.
</p>

<p>
NOTE: Multiple volumes of a book can be posted to PG separately if
appropriate&mdash;they do not need this treatment. If in doubt, ask the PM or
email <a href="mailto:<?php echo $db_requests_email_addr; ?>"><?php echo $db_requests_email_addr; ?></a>.
</p>


<h3><a name="other" id="other"></a>Other formats</h3>

<p>
Every project <strong>must</strong> have a plaintext file (unless the project
absolutely will not work in a plaintext form&mdash;e.g. a musical score.)
However, there are other formats, which add information and value to the basic text.
See <a href="http://www.gutenberg.org/wiki/Gutenberg:File_Formats_FAQ">PG's
File Format FAQ</a>.
</p>

<h4><a name="html2" id="html2"></a>HTML</h4>

<p>
This is the most common non-plaintext format requested or required for projects.
If you are working on a text which is part of an &uuml;berproject or
is a periodical, you may find a style guide defined for you&mdash;check
the <a href="<?php echo make_forum_url('f', 14, ''); ?>">&Uuml;berProjects
forum</a>. Otherwise it is up to you to make the project consistent and readable.
To produce HTML, you may wish to use tools that you are familiar with
if you have done web-editing previously. The major post-processing tools
such as <a href="<?php echo $wiki_url; ?>/Guiguts">Guiguts</a> will also
produce basic HTML which will just need some polishing to be valid and
look attractive. There is also
<a href="<?php echo make_forum_url('t', 16219, ''); ?>">PG2HTML</a>
which will generate a very basic HTML version for you to work with.
</p>

<p>
Ask in the <a href="<?php echo make_forum_url('t', 15019, ''); ?>">No Dumb
Questions</a> thread for post-processors, and/or
<a href="<?php echo make_forum_url('t', 23805, ''); ?>">HELP! HTML thread</a>
if you'd like more help with this. Many people have learned HTML for
the first time here, as part of their post-processing, and it doesn't
have to be terribly difficult!
</p>

<p>
Alternatively, you can post in the
<a href="<?php echo make_forum_url('t', 12240, ''); ?>">PPing HTML pool</a>,
giving a little information about your project. Other
<?php echo $site_abbreviation; ?> volunteers enjoy the process of making HTML
and will be happy to produce an HTML file for you.
</p>

<p>
HTML is essential for projects with illustrations. It is also very useful
for projects with many footnotes (because they can be hyperlinked back
and forth, making the text more usable) or with different letters used
from the Latin alphabet (such as Greek, which can be encoded so that any reader
with an adequate font will see the Greek letters). Even if your project has
none of these, many readers will find an HTML file more readable than
the plaintext, and if possible, it's always worth producing one.
</p>

<p>
If you do an HTML version, make sure that the &lt;title&gt; tags contain
the phrase The Project Gutenberg eBook of &lt;put the name of the book here&gt;,
by &lt;and the name of the author here&gt;. So for example, if the project
you were doing was "A Christmas Carol" by Charles Dickens, you would make
sure the title tags looked like:<br>
&lt;title&gt;The Project Gutenberg eBook of A Christmas Carol, by Charles Dickens&lt;/title&gt;
</p>

<p>
If you are not sure what CSS to use, feel free to copy and paste
the <a href="<?php echo make_forum_url('p', 592349, ''); ?>">HTML header from Guiguts</a>
at the top of your document and adjust as required for your needs, or refer
to the <a href="<?php echo $wiki_url; ?>/CSS_Cookbook">CSS Cookbook</a>.
</p>

<h4><a name="lilypond" id="lilypond"></a>Lilypond</h4>

<p>
Lilypond is used for representing music&mdash;see the
<a href="<?php echo $wiki_url; ?>/Music_Guidelines">Music Guidelines</a>
and ask for help or information from the
<a href="<?php echo make_forum_url('t', 9413, ''); ?>">Music Team</a>.
</p>

<h4><a name="latex" id="latex"></a>LaTeX</h4>

<p>
LaTeX is a markup language, entirely distinct from other types of DP formatting,
but normally confined to projects containing a lot of math. See the
<a href="#maths">section on LaTeX</a> below, or post in the
<a href="<?php echo make_forum_url('t', 34532, ''); ?>">LaTeX Forum for
Post-Processors</a> for more help.
</p>

<h4><a name="pdf" id="pdf"></a>PDF</h4>

<p>
PDF versions are usually accepted by PG only if an original master file is
supplied so that making any changes if necessary is not troublesome. It is very useful
for certain projects (e.g. those involving LaTeX) which benefit from a fixed
paginated layout and embedded specialized fonts, but is less helpful for
other projects because it is difficult to make changes to the PDF once
it's complete. Some PG readers do like this format.
</p>

<h4><a name="proprietary" id="proprietary"></a>Proprietary text formats</h4>

<p>
Project Gutenberg will accept proprietary text formats such as .doc,
but prefers not to. Issues of software compatibility and conversion arise
more frequently with these formats than with simple plaintext or HTML.
If you are considering an unusual format, you may wish to discuss it
with a <a href="<?php echo $wiki_url; ?>/PP_Mentoring">PP Mentor</a>
or the project manager.
</p>

<h4><a name="unicode" id="unicode"></a>Unicode, UTF-8, UTF-16, etc.</h4>

<p>
A UTF-8 file can be produced if you require characters which are not in Latin-1
(the "usual" character-set used by <?php echo $site_abbreviation; ?> in the
proofreading interface and dropdowns.) This probably isn't useful if you have
a single word with an &oelig;, but if your project has a fair amount of Greek
or other characters, a UTF-8 version will preserve the text most faithfully.
Most post-processing tools have support for this&mdash;check the user guide
or manual for more information. Ask in the post-processing forum for more help
with this. Also ask if you think you will need to use UTF-16 ...
it's not common and there may be a good alternative.
</p>

<p>
If you produce a UTF-8 text file, name it something like: projectname-utf8.txt
when you upload for PPV. This will help the PPVer and is how the Whitewashers
have requested such files be labelled. The WWers have a script to automagically
convert UTF-8 characters to a reasonable ASCII equivalent, so that both formats
will be posted in the final PG archive. If you think that this conversion process
will not produce a readable or useful ASCII file, you can produce one of your own
with all UTF-8 characters translated. Name that file projectname-ascii.txt.
Make sure it really is ASCII and that no UTF-8 characters have slipped in!
Remember to include both files when you upload for PPV.
</p>


<h3><a name="symbols" id="symbols"></a>Symbols and scripts, non-ASCII characters,
non-Latin scripts, and downright weird things</h3>

<p>
If you are unsure how to represent a symbol, post, with a link to the page image
containing the symbol, in the
<a href="<?php echo $post_processing_forum_url; ?>">Post-Processing Forum</a>.
Asking there will net you a varied range of ideas about whether the problematic
ink blob is in Latin-1, Unicode, or can be improvised using ASCII-art or
represented in another fashion.
</p>


<h3><a name="footnotes" id="footnotes"></a>Footnotes</h3>

<p>Some things to look out for when handling footnotes:</p>

<ol class="spaced">
<li>
In the text version, renumber the footnotes consecutively so that the reader
does not find many anchors when searching for say [1].
</li>

<li>
Sometimes many tags reference the same footnote&mdash;for example, 18 anchors
for 1 in the text and only one footnote. This is not a problem;
just make sure that all 1s go to the right Footnote in the HTML version.
</li>

<li>
There is no anchor-text for this tag and/or I have no tag for this footnote text.
If you can make out where the tag should go in the text, then it is probably
best to insert it with a Transcriber's Note. If there is a tag without a footnote,
then just a Transcriber's Note is probably best. See the section on
<a href="#tn">Transcriber's Notes</a> for details on how to word this and
where to place it.
</li>

<li>
I can't read this tiny text! If neither you, nor the proofreaders,
can figure out the footnote, contact the PM or see
<a href="<?php echo $wiki_url; ?>/Missing_pages">Missing Pages</a>
to obtain a clearer scan.</li>
</ol>


<h3><a name="sidenotes" id="sidenotes"></a>Sidenotes</h3>

<p>
Many post-processors panic when they see sidenotes.
This is usually the wrong reaction (though is sometimes justified).
</p>

<p>
The simplest case is when there are few sidenotes, usually only one
per paragraph, and usually at the start of the paragraph.
In this case you can just put them before the paragraph they refer to.
In the plain text it is probably best to leave them inside the
[Sidenote: Text of sidenote.] markup, so the reader can tell what they are.
Some people like to have them as headings, leaving a blank line between
the sidenote and the paragraph.
</p>

<p>
In HTML, it is probably best to float them off to the side. You can choose
whether to put them in the margin, so that they don't interrupt the flow
of the text, or whether you want them to stick into the text which will then
flow around them. It's probably best to follow the original as much as you can.
If you want them in the margin, you'll probably want to use a larger margin
than usual in order to make room for them. It is probably easier to read
the HTML version if you put all the sidenotes on the same side.
</p>

<p>
If there are lots of sidenotes, with many sidenotes per paragraph,
the situation gets more complicated. Putting them all at the start
of the paragraph will lose information. In most cases there is a definite place
in the text that the sidenotes are connected to, and that's roughly where
the sidenote should go.
</p>

<p>
In the plain text there are at least a couple of options. The first is
to put each sidenote (still in its [Sidenote: Text of sidenote.] markup)
at the start of the sentence to which it refers. This has the advantage that
the text stays easy to read, but, if the sentences are long, the sidenotes
may end up quite far from their referents. The second is to try to place
the sidenotes more exactly, by putting them in the middle of sentences.
This makes for a text that is much harder to read.
</p>

<p>
Sidenote placement is easier in the HTML version in that it is easy to get them
closer to their referents. However, you should check them in several different
browsers and at different browser window sizes, as it is very easy to get them
overlaying each other so that they are illegible.
</p>


<h3><a name="illustrations" id="illustrations"></a>Illustrations</h3>

<p>
Many post-processors have a hard time with images at first. Images are
the most common reason for PMs to require an HTML version, but if you don't
feel comfortable with HTML or dealing with images, it's okay! Make use of the
<a href="<?php echo make_forum_url('t', 12240, ''); ?>">HTML pool</a>&mdash;there
are many people who don't like to do the text portion of a project.
</p>

<p>Not frightened off? Good!</p>

<p>
Some projects have only one or two images, like a frontispiece or
an author portrait. The image files should be resized, so you can then
display them at their actual resolution within the HTML.  For example,
if you want an image to appear 400px wide in the HTML you should save it
at that size, rather than saving a larger image and using HTML code
to display it smaller.
</p>

<p>
Other projects are heavily illustrated, and often the point of posting them
is for making the illustrations available to a wide audience. Image-heavy projects
should use small copies at lower resolution and color-depth (commonly called
thumbnails) that link to larger, better quality illustrations.
This allows people on dial-up connection to the internet to get a feel
for the project without having to wait hours for the HTML to load.
Check to see if your PM included high-resolution illustrations along with the project pngs.
These are usually located at the very end of the images and
often have the extension .jpg.
</p>

<p>
A good rule of thumb is a maximum of 400px in width or 600px in height
for thumbnails and full page illustrations, and a maximum of 1200px
in the larger direction for linked-to images. The illustrations should scale
accordingly. If your largest image is a full page illustration
that you have made 400px, then your emblems that show up above the chapter header
should only be a fraction of that. Imagine that you are holding the book,
about what fraction of the page is the illustration taking up and adjust
your px number accordingly. If you need to decrease the file size further,
or touch up the pictures in some way, please see the
<a href="<?php echo $wiki_url; ?>/Guide_to_Image_Processing">Guide to
Image Processing</a>.
</p>

<p>
If even the high-resolution images are too dark or corrupted, contact the PM
for replacements. Sometimes the PM has given you the best the book had to offer.
Your next option is to try the
<a href="<?php echo $wiki_url; ?>/Missing_pages">Missing Pages Wiki</a>
to see if someone can provide better scans of the images.
</p>

<p>
All images for use in your final HTML should be stored inside a folder
called images within the project directory. Do a final check, when you've
completed work on the HTML, to make sure that all images are used correctly
within your page, and that you haven't included any temporary or redundant files.
</p>

<p>
For any questions or advice about any illustration related matter&mdash;contact
the <a href="<?php echo make_forum_url('t', 17712, ''); ?>">Illustrators Team</a>.
</p>


<h3><a name="poetry" id="poetry"></a>Poetry</h3>

<p>
As long as you make sure your rewrap markers are set correctly, post-processing
poetry shouldn't be any different from producing a prose book. Make sure you save
backup copies of your file regularly as you work&mdash;it will be much easier
to recover from a formatting decision gone terribly wrong. Have a look at
recently posted poetry books at PG for layout ideas.
Some post-processing software has extra features for
handling poetry&mdash;refer to the user guide or manual for more information.
</p>


<h3><a name="tables" id="tables"></a>Tables</h3>

<p>
Sometimes tables in your text will already have received the careful attention
of a member of the <a href="<?php echo make_forum_url('t', 11960, ''); ?>">Turn
the Tables</a> team, and be sized to fit within PG guidelines (ideally,
less than 75 characters wide, or 80 if desperate). If you need help with a table,
or have questions about the HTML formatting, post in the team topic or
in the post-processing forum.
</p>


<h3><a name="greek" id="greek"></a>Greek</h3>

<p>
Greek will usually have been
<a href="<?php echo $wiki_url; ?>/Transliterating_Greek">transliterated</a>
(converted to Latin letters) during proofreading.
There are various ways to handle this.
</p>

<p>
In the plaintext you can leave the transliteration, commonly removing
the [Greek: Transliterated text.] markup although you may wish to use
another markup of your own, such as a +, and mention its use to indicate Greek
in a Transcriber's Note. Or, if you have a significant amount of Greek or
other unusual letters, you can produce a UTF-8 version, which will contain
the original letters. Post in the forums if you'd like help with this.
Faster help can often be obtained for short phrases via the
<?php echo $site_abbreviation; ?> 
 <a href="<?php echo $wiki_url; ?>/Jabber_instructions">Jabber</a> chat room.
</p>

<p>
In the HTML, you can always use Greek letters (whether your file is saved as UTF-8 or not);
if it's not UTF-8, then you'll need to encode the Greek letters using
<a href="http://www.w3schools.com/tags/ref_symbols.asp">HTML entities</a>.
Either way it will display for the reader if they have a relevant font installed.
It's a nice idea to enclose the Greek in a &lt;span&gt; which uses
the transliteration as a "title" attribute&mdash;that way non-Greek readers
can still access the word. Again, ask for help if you need it.
There really are people who enjoy doing this!
</p>


<h3><a name="minorlote" id="minorlote"></a>Occasional use of other languages</h3>

<p>
Check the <a href="<?php echo $wiki_url; ?>/Language_Skills_List">Language
Skills List</a> to ask for help or advice. If a native speaker hasn't been
at <?php echo $site_abbreviation; ?> for some weeks, or can't help with
your particular problem, have a look at the
<a href="<?php echo $teams_forum_url; ?>">Teams Forum</a> or on
the <a href="<?php echo $wiki_url; ?>/Teams_List">wiki teams list</a>
to see if there's a team for the language or relevant country or countries.
Don't worry if there are few members or the forum hasn't been posted to
in a while&mdash;your question might be all it takes to create a lively
and helpful community discussion.
</p>


<h3><a name="index" id="index"></a>Indexes</h3>

<p>
Keep the page numbers in the index, even in the plain text version.
In the HTML version it's very easy to link numbers to page anchors&mdash;see
the user guide or manual for your post-processing software. If you need a way
to do this linking semi-automatically (you'll need to check that non-index
numbers aren't being included!), then just ask in the
<a href="<?php echo make_forum_url('t', 4381, ''); ?>">Regular Expression Clinic</a>.
For further help, or formatting queries, try the
<a href="<?php echo make_forum_url('t', 10794, ''); ?>">Junkies, Index</a> team.
</p>


<h3><a name="errata" id="errata"></a>Errata pages</h3>

<p>
These should be included as printed. There are two ways to handle these:
you can leave the amendments up to the reader, or you can make corrections
in the text, adding a Transcriber's Note that you've done so.
Whichever you choose depends on the project and on you as post-processor.
Just don't make silent corrections and don't leave the pages out.
</p>

<p>
A possible middle ground would be to include the page's content as printed
in the plaintext version, and use a correction &lt;span&gt; in the HTML version
to indicate that a change was made to the text&mdash;making the erratum amendment,
but including the original text to pop-up when the change is highlighted/hovered
over by the reader's mouse pointer. See the
<a href="<?php echo $post_processing_forum_url; ?>">PP forum</a> for more on this.
</p>


<h3><a name="after" id="after"></a>A problem after the project has posted!</h3>

<p>
<strong>Don't Panic!</strong> Everyone who post-processes has done this.
If they haven't, they will eventually.
</p>

<p>
If the book is quite recently posted to PG (in the last week or two),
contact your PPVer and let them know the problem. They'll pass it on to
the WhiteWasher who archived your book and can most easily fix it.
</p>

<p>
If the book posted a longer time ago, contact PG's
<a href="http://www.gutenberg.org/wiki/Gutenberg:Contact_Information">errata</a>.
Say that you are the post-processor of the book, and include the PG text number,
title, and author with a clear description of the problem and how to fix it.
If you've checked the problem against the page images, mention that too.
</p>


<h2><a name="different" id="different"></a>What's different about ... </h2>

<h3><a name="periodicals" id="periodicals"></a>Periodicals and
&Uuml;berprojects</h3>

<p>
See the <a href="<?php echo make_forum_url('f', 14, ''); ?>">&Uuml;berProjects
Forum</a> for a list of the large multivolume projects that are likely
to be seen on <?php echo $site_abbreviation; ?> for quite some time.
</p>

<p>
See also the <a href="<?php echo make_forum_url('t', 22556, ''); ?>">Proofreading
Periodicals</a> team.
</p>

<p>
Many periodicals have a standard style for the text and HTML versions
that ensures a consistent look for the whole project. If the periodical is part
of an &uuml;berproject, check the
<a href="<?php echo make_forum_url('f', 14, ''); ?>">&Uuml;berProjects Forum</a>
for details.
</p>

<p>
Many people are put off proofreading, formatting, or post-processing periodicals
because they are perceived as "hard" in some way. Canny post-processors will
therefore quickly realize that mastering a periodical style will give them access
to many entertaining projects with little competition. A Style Guide puts an end
to those hours spent mulling over whether a heading should be marked with
&lt;h2&gt; or &lt;h3&gt;. Periodicals often have longer pages than usual and
may have adverts or other unusual formatting issues.
These will all have been encountered previously, and recommended handling
should be explained in the &Uuml;berproject thread. If not,
or if the explanation is unclear, post there for help.
</p>

<p>
An excellent source of inspiration will be the most recently posted issues
of that periodical at PG&mdash;refer to these to help. Make sure you select ones
which have also been posted by <?php echo $site_abbreviation; ?> to ensure 
absolute consistency of style.
</p>


<h3><a name="drama" id="drama"></a>Drama</h3>

<p>
Many people are put off proofreading, formatting, or post-processing drama
because it is perceived as "hard" in some way. Sometimes it actually
is quite hard, for example, when written in sixteenth-century English
with little attention paid to spelling or grammatical niceties.
Mostly though drama is quite straightforward.
</p>

<p>
For all plays, check the <a href="formatting_guidelines.php#play_n">Formatting Guidelines</a>,
and ensure your plain text version is in line with these.
</p>

<p>
Format character names as similarly as possible to the original text.
If the text is metrical (written like poetry where line breaks are significant),
check the /* */ markers carefully before doing any rewrapping (or consider
checking through for rewrap sections, such as stage directions, by hand.)
</p>

<p>
If the project contains unclosed brackets, be aware that Gutcheck will have
many false positives.
</p>

<p>
There are various ways to format plays in HTML; searching PG for
recent postings may give some ideas, as will posting in the
<a href="<?php echo $post_processing_forum_url; ?>">Post-Processing Forum</a>.
The <a href="<?php echo make_forum_url('t', 15089, ''); ?>">Plays The Thing</a>
team can also offer help and advice. Ideally, make it look as much like
the original text as is sensible.
</p>


<h3><a name="music" id="music"></a>Music</h3>

<p>
Books with sections of music, or a short tune for a song sung in the text,
or entirely about music, are regularly put into PG by <?php echo $site_abbreviation; ?>.
A simple way to post-process a book containing music is to include all scores
as illustrations in the HTML. However, much more value can be added to the book
by transcribing the music into a common notation format.
This has three great advantages:
</p>

<ul>
<li>a clear musical score for HTML</li>
<li>a midi file for HTML, so the PG reader can actually listen to the music</li>
<li>the reader can edit the music</li>
</ul>

<p>
The <a href="<?php echo $wiki_url; ?>/Music_Guidelines">wiki music guidelines</a>
contain a detailed discussion of the different music transcription programs,
such as Lilypond, Finale, Sibelius, and so forth, all of which can produce
sound and image files, as well as editable source files. The guidelines
also contain information for PPers about different ways to present music
in the HTML, along with a list of sample e-books containing music.
</p>

<p>
To obtain help with music transcription, simply post a message to the
<a href="<?php echo make_forum_url('t', 9413, ''); ?>">Music team</a> thread,
or send a private message to one of the volunteer music transcribers
listed at the end of the Music Guidelines page.
</p>


<h3><a name="maths" id="maths"></a>Maths (LaTeX)</h3>

<p>
<strong>LaTeX</strong> is an integrated collection of typesetting software.
Though oriented toward mathematical and scientific content not easily
representable in plain text or HTML, LaTeX can create beautifully formatted
printed works of all types, featuring complex page layout, camera-quality printed
output, and auto-generated indexes, tables of contents, cross-references
(including hyper-linked PDF), and bibliographies.
</p>

<p>
LaTeX projects submitted to PG must include the LaTeX source as a single file,
together with any illustrations (in an "images" subdirectory,
as for non-LaTeX projects). Projects must be compilable with
<a href="http://www.tug.org/texlive/">TeX Live</a>, which the PG whitewasher
will use to generate the uploaded PDF. The <a href="http://miktex.org/">MiKTeX</a>
distribution for Windows may be assumed equivalent to TeX Live for formatting
and post-processing.
</p>

<p>
The primary repositories of LaTeX information and advice at
<?php echo $site_abbreviation; ?> are:
</p>

<ul>
<li><a href="<?php echo $wiki_url; ?>/LaTeX_resources">LaTeX resources</a></li>
<li><a href="<?php echo $wiki_url; ?>/Category:LaTeX">LaTeX Category</a> Wiki pages</li>
<li><a href="<?php echo make_forum_url('t', 34532, ''); ?>">LaTeX Forum for Post-Processors</a></li>
</ul>


<h3><a name="lote" id="lote"></a>LOTE (Languages Other Than English)</h3>

<p>
You probably want to speak the language, or have a native speaker
spellcheck/smoothread it. However, for some languages, there are few or no
native speakers available on the site. These projects can be taken by people who
are willing to put in the extra effort involved in dealing with a language that
they do not speak. Check the
<a href="<?php echo $wiki_url; ?>/Language_Skills_List">Language Skills List</a>
to find who to ask for help or advice.
</p>

<p>
When you submit a Latin-1 version of your text there is no need to also produce
an ASCII version as PG has the tools to easily make an ASCII version based on
the Latin-1 text. However, if the ideal ASCII-version would be different from the
result you get by making standard replacements like &uuml; -&gt; ue, &eacute;
-&gt; e, etc., you should produce an ASCII version yourself. Explain your reason
for doing so when uploading for PPV, so they can pass that message on to PG.
</p>

<p>
As a post-processor you have a bit of freedom in choosing the best format
for your text. For LOTE texts this may sometimes lead to decisions which would
be unusual or even plainly incorrect in English. If you make such a decision,
you might get lots of Gutcheck errors. <strong>If</strong> you have a good reason
for your decision and if you have applied it consistently, you can ignore
<strong>those</strong> errors. You might want to mention this decision
in your upload notes. Example: For many languages it looks more natural
to have spaces around em-dashes. It's perfectly fine to leave or insert them.
</p>

<p>
You should replace English markup words which appear in the final text
with translations of those words in the main language of the text. e.g.
Footnote/Fu&szlig;note/Apostille/Ootnotefay/&Upsilon;&pi;&omicron;&sigma;&eta;&mu;&epsilon;&iota;&omega;&sigma;&eta;/Voetnoot/Nota de rodap&eacute;.
</p>


<h2><a name="also" id="also"></a>See also ...</h2>

<ul>
<li><a href="<?php echo $post_processing_forum_url; ?>">Post-Processing Forum</a>,
    which contains a <a href="<?php echo make_forum_url('t', 15019, ''); ?>">"No
    Dumb Questions" for PPers</a> thread</li>
<li><a href="<?php echo $wiki_url; ?>/PPTools/FAQ">PP Tools&mdash;FAQ</a></li>
<li><a href="<?php echo $wiki_url; ?>/Post-Processing_Advice">Post-processing Advice</a></li>
<li><a href="<?php echo $wiki_url; ?>/Guiguts_PP_Process_Checklist">Guiguts PP Process Checklist</a></li>
<li><a href="<?php echo $wiki_url; ?>/User:Miller">Miller's PP walkthrough using Guiguts</a></li>
<li><a href="<?php echo $wiki_url; ?>/Post-Processing_German_books">Post-processing German Books</a></li>
<li><a href="<?php echo $wiki_url; ?>/French/FAQ_post-processing">Post-processing FAQ en fran&ccedil;ais</a></li>
</ul>

<?php
