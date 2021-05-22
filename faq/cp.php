<?php
$relPath = '../pinc/';
include_once($relPath.'base.inc');
include_once($relPath.'faq.inc');
include_once($relPath.'forum_interface.inc');
include_once($relPath.'theme.inc');

maybe_redirect_to_external_faq();

output_header("Content Provider's FAQ", NO_STATSBAR);

$content_providing_forum_url = get_url_to_view_forum($content_providing_forum_idx);
?>
  <meta name="Author" content="Stephen Schulze">
  <meta name="author" content="thundergnat">

<h1>Content Provider's FAQ</h1>
<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;See also: <a href="./scanning.php">Scanning
FAQ</a></p>
<hr size="3" width="100%" align="left">
<p class='warning'>The content for this FAQ document has been moved into the wiki and is no longer maintained in the code.</p>
<hr size="3" width="100%" align="left">
<p>So you've reached the rank of "Proofreader Extraordinaire" and figured
that you would branch out into different arenas.<br>
Or perhaps proofreading just isn't really your cup of tea but you still
 want to help out the site.<br>
Maybe there's a book that you just can't wait to get on to Project
Gutenberg.<br>
Whatever the reason, sooner or later there comes a time when you
ask:&nbsp;&nbsp;"Just how do I provide content to Distributed Proofreaders
 anyway?" </p>
<p>These Guidelines are here to try to help you through the process.</p>

<p>
Note that you don't necessarily have to do all these steps yourself.
It's quite possible to do some steps and hand off your results
to someone else.
<!--
[But we need to describe how you find that "someone else".]
-->
You can also elect to manage the project once the necessary files have
been uploaded to the DP server.
See the <a href='./pm-faq.php'>Project's Manager's FAQ</a> for details.
</p>

<hr size="3" width="100%" align="left"> <a href="./scanning.php">Frequently
asked Questions</a>&nbsp; -&nbsp; (separate page) Some common questions
that are related to scanning, OCR, etc. that are not covered here.<br>
<p><br>
<a href="#what">What kinds of books do you want for Distributed
Proofreaders?</a><br>
<a href="#where">Where do I get a book to process?</a><br>
<a href="#clearance">How do I ensure that the book is eligible?</a><br>
<a href="#scan">OK, I have my book, I have my clearance line; Now what?</a><br>
<a href="#ocr">Whew! I've got the image files done. What's next?</a><br>
<a href="#process">You're kidding me! I'm not done yet?</a><br>
<a href="#upload">OK, I'm finished, how do I upload the project?</a><br>
<a href="#software">Useful Software</a><br>
<br>
</p>
<p> </p>
<hr size="3" width="100%" align="left">
<p><big><a name="what"><b>What kinds of books do you want for
Distributed Proofreaders?</b></a></big></p>
<p>What kinds of books do you have? :-)</p>
<p>Seriously, there are really few restrictions on what kind of text
you can contribute to DP. The biggest, and probably most important is: The
book MUST be in the public domain (i.e., the copyright must have
expired). In general this tends to mean books that were written before
1923. There <i>are</i> exceptions to the 1923 rule, but a lot of times
it is troublesome to try to prove them. There is a good detailed
discussion of what is and isn't eligible at the Project Gutenberg site
on <a href="https://www.gutenberg.org/help/copyright.html">this</a> page.&nbsp;
For a discussion of copyright terms in other countries, check out <a
 href="http://digital.library.upenn.edu/books/okbooks.html">this</a>
page.<br>
<a href="http://catalog.loc.gov">The Library of Congress Online Catalog</a> and
<a href="http://blpc.bl.uk/">The British Library Public Catalogue</a>
are great places to check publishing dates of books.</p>

<p>The book should not already be on the Project Gutenberg site. This
site exists as a feeder site to Project Gutenberg, and it makes little
sense to spend all the time and effort on a text that is already there.
A different <span class="italic">version</span> of an
existing book is OK though. You can check the <a
 href="https://www.gutenberg.org/ebooks/">Project Gutenberg online catalog</a> to
see if a book is already on there.</p>
<p>There is also a site called <a
 href="https://pglaf.org/~ccx074/gutip.html">David's
In-Progress List</a> listing all of the books that people are presently
working on. Again, this is helpful to avoid duplication of effort.
If you find your book listed but the clearance date is over a few years old
then it is probably OK to go ahead and do it.
</p>
<p>You might want to stick with a shorter fictional work for the first
project you contribute.
It is probably better to avoid books which contain a lot of illustrations,
maps, charts, tables and pictures for your first project.
</p>

<p>Non-English language texts are fine too,
though keep in mind that at the moment PGDP uses Latin-1, not Unicode.
Texts in most western European languages and a few others
(e.g. English, French, German, Latin (sans length marks),
Italian, Spanish, Swedish, Dutch, Swahilli)
are usually appropriate for PGDP.
However, texts with many characters outside Latin-1
are probably better handled at
<a href="http://dp.rastko.net/">DP-EU</a>,
which uses Unicode. The procedures for preparing texts are the same
for both sites, but permission to be a PM must be obtained separately
from the administrators of each site.
If you have a query about whether a text in a non-English language is appropriate for DP,
please post a question to the
<a href="<?php echo $content_providing_forum_url; ?>">Providing Content Forum</a>.
</p>

<p> It is helpful, though not strictly necessary that you understand the
 language that the book is written in. It will theoretically make it
easier during post-processing to be able to tell from context whether
paragraph breaks should be at page breaks, however, checking back
against the original book can get you through too. </p>
<hr size="3" width="100%" align="left">
<p><big><a name="where"><b>Where do I get a book to process?</b></a></big></p>
<p>Libraries, flea markets, yard sales, auctions, estate sales, your
parents/grandparents, the trash, (you'd be AMAZED at what people throw
away!) used book shops, friends, schools, you name it, there's books
there. It is better to have a book that you will have access to for the
whole time the project is being worked on so you can refer back to it if
 problems turn up in the scans. (Happens depressingly often)</p>
<p>
You may find many eligible books
in the circulating (borrowable) collection of your local library,
but do be careful,
because the scanning process can be a little rough on books
and they may get damaged.
</p>
<p>There are also many on-line sites devoted to used books if you are
trying to find a particular one:</p>
<p><a href="http://www.abebooks.com/">Advanced Book Exchange</a><br>
<a href="http://www.alibris.com/">Alibris</a><br>
<a href="http://www.elephantbooks.com/">Elephant Books</a><br>
<a href="http://www.trussel.com/f_books.htm">Trussel BookSearch </a><br>
and that old standby;<br>
<a href="http://half.ebay.com/products/books/">Half.com / Ebay</a><br>
to name just a few.<br>
</p>
<p>There are also many sites which have books available online as PDF
or image files which can be downloaded and OCRed.
(Note that some PDF files do not contain actual page images,
but instead contain text resulting from OCR or retyping.
Since DP needs page images, we can't use those PDF files for DP.)
University libraries
and historical societies seem to be rich sources. This is especially
helpful if you don't have access to a scanner or physical books. There <span
 class="italic">are</span> drawbacks: they are usually a
fairly intensive download, especially over dial-up; you don't have
access to the actual book to check against if there are later problems,
and the selection is limited. Not having to do the scanning is a big
plus though.<br>
</p>
<p>There is a large list of possible scan source sites in the Content
Providers Forum under the topic <a class="maintitle"
 href="<?php echo $Online_sources_URL; ?>">"Online sources of scanned book images"</a></p>
Please follow the individual site guidelines regarding acceptable use
and protocol. We don't want to be bad neighbors.<br>
If you <i>do</i> go this route, it is considered good form to credit the
source of the scan when the text is submitted to Project Gutenberg.<br>
<br>

<hr size="3" width="100%" align="left">
<p><big><a name="clearance"><b>How do I ensure that the book is eligible?</b></a></big></p>
<p> </p>
<p>Once you have found a book that you think might be a good candidate,
 the first thing you should do is get a <span class="bold">clearance
line</span>. This is an approval, if you will, of the book for the
Project Gutenberg site, and also registers the book as being a work in
progress to let other people know it is reserved so as not to duplicate
effort.<br>
</p>
<p>The preferred method&nbsp; for requesting a copyright clearance is the
web interface at <a href="http://copy.pglaf.org">this page</a>
(http://copy.pglaf.org). There are quite a few handy tips
and links there also.<br>
</p>
<p>You probably should not invest too much time until you've received
your clearance line. </p>
<hr size="3" width="100%" align="left">
<p class="bold"><big><a name="scan">OK, I have my book, I
have my clearance line; Now what?</a></big></p>
<p>Now you need to scan it.<br>
</p>
<p>There are too many scanners and scanning packages to give specific
instructions here. In general, good all-purpose parameters for scanning:
 300dpi, black and white (not grayscale), and average brightness unless
the paper is very yellow. Higher dpi doesn't necessarily make for better
 OCR unless the text is <i>extremely</i> small. You want to end up with
good, reasonably clean images that the OCR software won't choke on.</p>
<p> The following examples and explanations assume that you are using
ABBYY FineReader. This FAQ tends to concentrate on using ABBYY FineReader
 Pro because: </p>
<ul>
  <li>It is one of the more popular OCR packages used by the DP
administrators. </li>
  <li>It is very accurate on pretty lousy images and, let's face it,
100 year old books are not typically in the best of      shape. </li>
  <li>It is pretty easy to automate for most of the process. </li>
  <li>It is free to try for 30 days or 15 hours of use.</li>
</ul>
<p> ABBYY FineReader Pro 5.0 or higher (and most other high end OCR
programs) have built in scanning functionality and will allow you to
automate the process to a great extent. In FineReader, to open a new
batch. Click on File-&gt;New Batch, (Ctrl+N) and give it an appropriate
name. (The title of the book, abbreviated, is a good choice) This is
where FineReader stores all of the interim files for the project. It is
probably a good idea to make a separate batch directory in which to put
all of your individual batches. </p>
<p>As a matter of fact, while we're on the subject, let's talk about
directory structure a little bit. It is a good idea to use a logical
directory structure to help keep track of things.&nbsp; There is no
"right" or "wrong" way to do this, it mostly depends on personal
preference. However, in order to use some of the features of the tools
that have been written to make things easier, a certain structure must
be followed.<br>
</p>
<p>&nbsp;Starting at the appropriate place in your directory structure
(Shown as "C:\" &nbsp;in this example, choose a place comfortable for
you.) Make two directories: "Batch" and "Projects".<br>
</p>
<p>Every time you start a new batch in FineReader, it automatically
generates a directory where it stores raw image and text data, named
with a batch name that you specify. Save this under the "Batch"
directory <br>
</p>
<p>Under the "Projects" directory make another directory. Name this
with the same name as the "Batch" name used in FineReader. Under that
directory make several more directories: "pngs", "textw" and "textwo" .
These are where you will save the images and text files from FineReader.
"Textw" stands for text with line breaks and "textwo" stands for text
without line breaks. These will be explained more later.<br>
</p>
<p>Here's a little graphic to demonstrate. Assuming a book named Book1:<br>
</p>
<blockquote> <img src="FineReader_folders.png"
 style="border: 3px solid ; width: 234px; height: 165px;"
 alt="Directory Structure" title=""><br>
  <br>
  <br>
</blockquote>
Some people like to put the batch from FineReader in the same directory as
the png and text directories to keep track of them easier. That is fine
too if you prefer it that way. Personal preference and comfort comes
into this a lot.<br>
<p>&nbsp;When your batch directory is set up, in FineReader, Select
File-&gt;Scan Multiple images (Ctrl+Shift+K) to start scanning the book.
From here the procedure will vary greatly depending on what features
your scanner has, (automatic document feeder or not) and your personal
preferences, (acknowledge each scan or have a timed pause between.)
Obviously, other packages will be different; your best bet is to check
the help files that came with your specific package.</p>
<p>
If the scanner bed will accommodate it,
scan 'two-up' images (two book pages per image),
as this will speed up the scanning process.
Try to keep the book in the same place on the scanner for each scan
(say, tight into a corner).
That will make it easier to do the cropping and splitting.
</p>

<p>
Crop the images, if necessary, to minimize black borders around the
page image. If you are ending up with LARGE black borders around your
page image, you should probably adjust your scanning "window" smaller to
 avoid scanning outside where the page lays on the scanner bed. Doing
this will save you both time-the scanner doesn't have to scan such a
large area-and space on your drive-smaller files. Don't crop the image
down till there is no or very little margin around the text, this can
affect recognition and can cause difficulties during the proofreading
process. Ideally, what you want is some white space around the text, but
 no black.
</p>

<p>
If you have two-up images, split them into individual (one-up) page images.
Generally there are two easy ways to get one-up images from two-up images:
<ul>
<li>If your scanning program has an option to automatically split images
    as they are scanned, set this option.
    (FineReader can do this as long as
    there is some white space between the pages.)
<li>Use an external program.
</ul>
If there are any questions, it's best to test a few scans.
</p>

<p>
When you save your image files, save them as black and white
images, not color or grayscale;&nbsp; you probably want ".tif" or ".png"
format image files. Later you'll NEED ".png" format files, so if your
OCR software can handle them it might be better to use them now. Avoid
saving them as jpegs (lossy format) or .bmp bitmaps (huge files). Under
FineReader, to save all the image files at once, select them all
first,(click in the thumbnail window and press Ctrl-A) then choose
File-&gt;Save Images (F12), and be sure to give the images a name since
it doesn't insert the batch name automatically. It will save them in a
series with the specified name, a hyphen, and a four digit counter.
(Book1 - 0001.png, Book1 - 0002.png... etc.) Save them to the
"Projects\Book1\pngs" directory.<br>
<br>
<b>VERY IMPORTANT! </b>- Make sure the files are named in an order
that is sequential and alphabetically ordered. (Automatic under
FineReader-as long as they were loaded in the correct order.) If your
package allows it, your best bet is to name the files "001.png (or
.tif), 002.png, 003.png, etc". (FineReader doesn't, you'll need to
rename them later in the preprocessing section. It will name them
sequentially but not in the exact format we need.) This will make it
easier to keep the order straight and avoid gaps and holes in the naming
 system. (And besides, you'll need to get them into this format later
anyway.)<br>
<p>For e-texts/.pdf files, you want to end up in the same place. If the
page images are available as single page .tifs, .gifs, or .pngs you'll
need to download them, convert  them to .pngs, and make sure the
filenames follow the correct format. If you have multi page images, you
may need to split them first. With .pdf files you'll need to use one of
the software utilities to extract the .tif (usually) images from the .pdf</p>
<p><b>Note:</b> ABBYY FineReader OCR 6.0 is capable of working directly
with .pdf files. You don't need to extract the images first. If you set
up a batch, it will extract .tif images to the batch directory
automatically as it is loading the .pdf files. These can then be
converted to .pngs for later use.<br>
</p>

<p>
For more help with ABBYY FineReader,
please see our
<a href="<?php echo $FineReader_tips_URL; ?>">FineReader Tips and Tricks forum topic</a>.
</p>

<div><br>
</div>
<hr size="3" width="100%" align="left">
<p class="bold"><big><a name="ocr">Whew! I've got the
image files done. What's next?</a></big></p>
<p>Now you've got to run the images through an OCR (Optical Character
Recognition) program. Again, there are too many programs out there to
give useful specific directions for them all. You will need to wind up
at the same place though the path you take may be different.<br>
</p>
<p>If you don't have an OCR package,
you can take advantage of the DP
<a href="<?php echo $OCR_Pool_URL; ?>">OCR Pool</a>.
Other DP volunteers who do have OCR packages
are more than happy to OCR images on your behalf.
</p>

<!--
[Per JulietS: Add paragraph about the OCR pool and how to use it.]
-->

<p>Assuming you DO have OCR software...</p>
<p>If you used FineReader for the scanning, you've already set up a
batch and the images are already there.</p>
<p> If not, open up FineReader. Click on File-&gt;New Batch, (Ctrl+N).
 and name it appropriately. Click File-&gt;Open Image,(Ctrl+O). Select
all of the images and click on&nbsp; "Open". You might want to open just
 one or two at first to be sure everything is working, then do the rest.
 Try to make sure that you select them in the order that they belong. If
 they are named so that they will sort correctly in alphabetical order,
you can select them all at once. <br>
Depending on how many files you have, the format the images are in and
how fast your computer is, it will take several seconds to several hours
 to load all of the images. </p>
When all of the image files are read, check the images in the batch
window. If they are out of order, under FineReader 6.0 you can renumber the
images under the "Batch Processing" menu. In FineReader 5.0 this is
non-trivial. Better to start in the right order.
<p> Check settings under "Tools--&gt;Options". Select the correct
language for the text. Hit (Ctrl-shift-R) or the "read all" icon, to
initiate the OCR sequence, then go away for another (usually shorter)
break. There is also an option under the "Process" menu to perform
background processing, which allows you to minimize the window and do
other things while waiting.<br>
</p>
<div>For complex or "busy" pages of text and illustrations, some extra
work may be necessary. ABBYY FineReader tries to analyze the layout of a
page as it does the OCR. For simple, two-column pages it usually gets
the layout right, but if the columns are broken up by illustrations,
tables, etc, it will almost certainly get the layout wrong.<br>
&nbsp;</div>
<div>It is possible to draw boxes on the scanned image to show
FineReader which pieces of text to group together. Once the boxes are
drawn, you can tell FineReader how to order them in the OCR'd text. In
order to draw the boxes, click on the little box icon at the top of the
icons along the left-hand side of the window. This is usually the
default, so clicking on that icon may not be necessary. Find your
starting point, hold down the mouse button and drag until the box is the
right size. You can adjust the box in fine detail in the zoomed image
at the bottom of the window. If you draw the boxes in the order that
you want them processed then you don't have to do anything else. Just
hit Cntrl-R and let FineReader OCR the page. Sometimes, however, it's
not convenient to draw the boxes in the correct order. You can tell
FineReader what order you want by clicking on the 123 icon on the left
side of the window. Then click on the text/illustration boxes in the
order that you want them. The numbers on the boxes will change to
reflect the final output order. Note that when FineReader is actually
doing the OCR, it may not process the boxes in the order you specified,
but the result will come out in the correct order.</div>
<div>&nbsp;</div>
<div>When doing OCR on a long, complicated project, it works well to
let FineReader OCR all the pages, then go through and look briefly at
each page to see if it needs manual tuning. You can move from page to
page quite quickly by using Alt-down arrow. When you see a page that
FineReader didn't get right, you can delete the OCR'd text only or the
OCR'd text AND the text boxes, depending on how badly it got things
wrong. Fix or redraw the boxes and fix the order as necessary, then move
on to the next page. If you have Background Processing turned on, it
will do the OCR while you are looking for the next problem page.</div>
<div>&nbsp;</div>
<div>Note also that you can specify different recognition languages for
different text boxes, but, at least in FineReader 5.0, you must
manually change the language, and read each box in the correct order,
making this quite time consuming.</div>
<div>&nbsp;</div>
For complicated projects, getting FineReader to group the text
correctly and present it in a sensible order saves the proofreaders A LOT of
time. It is WELL worth the extra time to get it right at the OCR stage.
<p>When that is done, you'll need to save the text files to do further
processing on them. Depending what tools you will use in preprocessing,
the formats and locations you save them in will vary. To use the guiprep
script (highly recommended) you will
need to do something like the following :<br>
</p>
An excerpt from the <a
 href="http://mywebpages.comcast.net/thundergnat/guiprep.html">guiprep.pl
manual</a>. (Included with the guiprep script)<br>
<p style="margin-left: 40px;"><big><span class="bold">Setting
up the text files:</span></big><br>
<br>
<span class="bold"> RTF Markup Extraction:</span><br>
<br>
In order to use the dehyphenization features of this script, you NEED
to save the text from ABBYY FineReader (or possibly other OCR packages,
should work as long as they produce standard well formed rtf files) two
times in two different directories. Assuming you have a project
directory named "PROJECT", under the project directory you will need two
directories "textw" and "textwo". "textw" stands for "text with line
breaks" and "textwo" stands for "text without line breaks". <br>
<br>
In FineReader after all of your images are loaded and OCRed, select&nbsp;
File =&gt; Save Text As;&nbsp; A dialog box will pop up. <br>
</p>
<div style="margin-left: 40px;"> <br>
In the "textw" directory, save the text with the settings: Save as type <span
 class="bold">Rich text Format</span>, <span
 class="bold">Create a separate file for each page</span>,<span
 class="bold"> Retain font and font size</span>. On the
RTF tab of the Formats Settings, check <span class="bold">Keep
page breaks</span> and <span class="bold">Keep line breaks</span>
and uncheck everything else. It doesn't matter what the File name is set
to. The name of your batch is probably fine.<br>
<br>
In the "textwo" directory, save the text with the settings: Save as
type <span class="bold">Rich text Format</span>, <span
 class="bold">Create a separate file for each page</span>,<span
 class="bold">Retain font and font size</span>. On the
RTF tab of the Formats Settings, check <span class="bold">Keep
page breaks</span> and <span
 class="bold underlined">Remove optional
hyphens</span> and uncheck everything else. Make sure the File name is
set the same as in the textw directory.<br>
<br>
<br>
<span class="bold"> Using the script <span
 class="italic">without</span> RTF Markup Extraction:</span><br>
<br>
If you don't want to do markup extraction, (or your OCR package won't
support RTF files)&nbsp; you can skip saving the files as RTFs and just
save them as plain text files. Again, to do dehyphenization, you will
need to save the files in two directories, textw and textwo.<br>
<br>
Save the text with line breaks in textw. The ISO Latin-1 code page will
give you pretty good results for English and most European languages.
The site works with ISO Latin-1 so that will be least problematic to fit
into the character space used. If necessary, you can try other code
pages but be aware that they may not be as easy to use on the site and
may not yield satisfactory results with some of the script functions.<br>
<br>
The textwo directory should use all of the same settings except that <span
 class="bold">Keep line breaks</span> needs to be
unchecked. Be sure to use the same code page and file names in both the
textw and textwo directories.<br>
<br>
At this point the script is used exactly the same way except you'll
skip the Extract Markup routine.<br>
<br>
<br>
<span class="bold"> Using the script <span
 class="italic">without</span> RTF Markup Extraction <span
 class="italic">or</span> Dehyphenization:</span><br>
<span class="bold"> </span><br>
If you are using a different OCR package that can't save as rtf or do
automatic line rejoining, you may need to skip those two functions. Save
the files in a directory named "text" using the same settings as for
textw without RTF extraction above. Uncheck both Extract and&nbsp;
Dehyphenate under the Process Text tab. It won't hurt to leave them
checked but the script will complain that it can't find the other
directories and/or files.<span class="bold"></span><br>
<span class="bold"> </span></div>
<p>If you aren't using guiprep 
just save the files into the "text" directory. Save as plain text, keep
line breaks, use blank line as paragraph separator.
</p>
<hr size="3" width="100%" align="left">
<p class="bold"><big><a name="process">You're kidding me!
I'm not done yet?</a></big></p>
<p>Now you are going to need to do a little preprocessing on those text
 files.&nbsp; The tools you use will dictate how you proceed. The
major tool (Guiprep) is covered here.<br>
</p>
<p><span class="bold"><a
 href="http://mywebpages.comcast.net/thundergnat/guiprep.html">Guiprep / Winprep</a>
:</span><br>
</p>
<p>Guiprep is capable of extracting italic and bold markup from the
OCRed text. (save lots of time for proofreaders), removing the end-of-line
hyphens and rejoining the broken words, filtering out many, many
scanning errors, renaming the files in the format need by Distributed
Proofreaders and checking for zero byte files, all automatically. It
also provides an interactive mechanism for header removal which is very
stable and user friendly. The
manual
included with the script is quite comprehensive and should be consulted
for any detailed questions.<br>
</p>
<p>&nbsp;A general overview of how to use it: <br>
</p>
<p>Open the script, a graphical user interface will pop up.
Guiprep
uses a tabbed screen scheme, similar functions are grouped on different
tabs. <br>
Step 1. Goto the Change Directory tab. Navigate to the directory
containing the textw and textwo (or text) directories. <br>
Step 2. Goto the Select options tab. Select the appropriate options for
your text. The defaults are probably fine for most texts. Exceptions and
caveats are discussed in the manual. <br>
Step 3. Go to the Process Text tab. Select which functions you want to
perform; extract, dehyphenate...etc. Press the Do All Selected button or
just press the buttons to run each function individually. A status box
will display progress and error messages if they occur.<br>
Step 4. Go to the header removal page. Press Get Headers to get a list
of the headers from the files. Select the headers you want to be
deleted. Press remove headers. Repeat as necessary.<br>
</p>
<p>The finished files will be in a directory named "text".<br>
</p>
<p>Guiprep also can automatically rename your .png files and provides a
front end to pngcrush to losslessly reduce the size of your png file and
reduce your upload. It also has a FTP client built in which will
automate a lot of the upload.<br>
</p>
<p>Pre-processing complete.</p>

<hr size="3" width="100%" align="left">
<p><big><a name="upload"><b>OK, I'm finished, how do I upload the
project?</b></a></big></p>
<p>If this is your first time contributing a project and/or you are not a
 project manager,&nbsp; send an email to <?php echo $clearance_verifier_contact; ?>, that includes
the author, title, etc and, ideally, the clearance line and any comments
you may want included on the project page. &nbsp;Make sure you include
your name and a contact email address (if different from the sending
address). She will contact you with an FTP address and directoryname
where you can upload  the image and text files. Use an FTP client to
upload all of the .png and .txt files you generated earlier into that
directory.
You can also upload a single .zip file of all the .png &amp; .txt files.
(There are a few free FTP clients listed in the software
section, or,&nbsp; the guiprep toolkit has an FTP client built in that
will automate some of the process.)
Alternately, if you anticipate having several
projects, you may want to send a message to <?php echo $PM_rights_giver_contact; ?> and ask
to be made a project manager. This will open up access to some of the
project creation and control features. The same general procedures are
used once you are a project manager, you just need to create your own
project pages and set up your own upload directories, details are given
on the project managers page.<br>
<br>
At this point it is probably safe to delete the batch directory used by
 FineReader under the "Batch" directory. You could always regenerate it
from the image files again if necessary. Keep the text and image files
around at least until the book is done post-processing and has been
submitted to Project Gutenberg so you can refer back to them, if
necessary, especially if you are going to do the post-processing
yourself.
(See the <a href="./post_proof.php">Post-Processing FAQ</a> for more details.)
</p>
<p>Wow! That was fun, let's do another! :-)</p>
<br>
</p>
<hr size="3" width="100%" align="left">

<p><big><a name="software"><b>Useful Software</b></a></big><br>
<br>
</p>

<p><span class="bold italic">Scanning / OCR
software:</span><br>
</p>

<p><a href="http://www.abbyyusa.com/">ABBYY FineReader</a> <i>Commercial</i>
[Win32, Mac]  - <i>Current Version 7.0</i> - OCR
software.&nbsp;&nbsp;&nbsp; Very nice. :-)&nbsp;&nbsp;&nbsp;Quite
expensive. :-(&nbsp;&nbsp;&nbsp;Free Trial. :-)
&nbsp;&nbsp;&nbsp;
See our <a href="<?php echo $FineReader_tips_URL; ?>">FineReader Tips and Tricks forum topic</a>.
</p>
<blockquote> <font size="-1">5.0 Pro is <i>much</i> cheaper than 6.0
and is still available (though not directly from ABBYY software) and
does what is needed. If possible, stick with the Pro version though; the
 Home and Sprint versions don't have necessary features. Good for
scanning, but a little finicky about which scanners it supports.<br>
  <br>
  </font> </blockquote>
<span class="bold italic">Text file
processing tools:</span><br>

<p><br>
<a href="http://mywebpages.comcast.net/thundergnat/guiprep.html">Guiprep</a>
<span
 class="italic">Free</span> [Win32, Unix] A tool set
specifically geared toward pre-processing text
files for use by Distributed Proofreaders. Automatic markup extraction,
dehyphenization, filtering, renaming &amp; more.
See also <a href="<?php echo $Guiprep_topic_URL; ?>">this forum topic</a>.
Guiprep requires perl.
Please see the guiprep manual for more infomation
about obtaining a perl interpreter for your system.
</p>

<span class="bold italic"><br>
Image viewing and manipulation:</span><br>

<p><a href="http://www.irfanview.com/">Irfanview32</a> <i>Free</i>
[Win32] - Nice general purpose image manipulation and conversion
software.<br>
</p>

<p><a href="http://www.xnview.com/">XnView</a> <span
 class="italic">Free</span> [Win32] - Nice general purpose
image manipulation and conversion software. </p>

<p><a href="http://www.firehand.com/Ember/index.html">Firehand Ember</a> <span
 class="italic">Shareware</span> [Win32] - Another nice
image viewing and conversion program. <br>
</p>

<p><a href="http://netpbm.sourceforge.net/">netpbm</a>
<i>Free</i>
[Win32, Unix] -
A toolkit for manipulation of graphic images,
including conversion of images between a variety of different formats.
</p>

<p><br>
<span class="bold italic">Batch file
renamers:</span><br>
</p>

<p><a href="http://www.1-4a.com/rename">1-4a-Rename</a> <i>Freeware</i>
[Win32] Nice very configurable utility for batch renaming files. Very
point 'n click.<br>
</p>
<br>
<span class="bold italic">File Archiving and
Compression tools:</span><br>

<p> <a href="http://www.7-zip.org/">7.zip</a> <i>Free-GPL</i> [win32
Unix] Free utility to uncompress .zip archives.<br>
</p>

<p><a href="http://www.iceows.com/HomePageUS.html">ICEOWS</a> &nbsp;<span
 class="italic">Freeware</span> [Win32] Compress files in
ICE and ZIP formats and uncompress nearly any common format. Many
language interfaces available.<br>
</p>

<p><a href="http://www.info-zip.org/pub/infozip/">Info-ZIP</a> <span
 class="italic">Free-BSD</span> [Nearly all OS's and
Platforms] A collection of utilities for working with zip format
compressed files. Support for a large number of platforms and OS's. <br>
</p>

<p><a href="http://www.filzip.com/en/index.html">FILZIP</a> <span
 class="italic">Freeware</span> [Win32] Point and click
manipulation of compressed files. GUI interface. Multiple file
extraction. Lots of nice features.<br>
</p>

<p> <a href="http://www.winzip.com">WinZip</a> <i> Shareware</i> [Win32]
Utility to create and extract .zip archives. Free trial.<br>
<br>
</p>

<p><span class="bold italic">FTP tools:</span><br>
</p>

<p><a href="http://www.ftpplanet.com/download.htm">WS_FTP LE</a> <i>Shareware</i>
[Win32] Easy to use FTP client. Free for non-commercial use.<br>
</p>

<p><a href="http://www.smartftp.com/">Smart FTP</a> <span
 class="italic">Shareware</span> [Win32] Another easy to
use FTP client. Free for-non commercial use.<br>
<br>
</p>

<p><span class="bold italic">Other utilities:</span><br>
</p>

<p><a href="http://www.foolabs.com/xpdf/">Xpdf</a> <i>Free-GPL</i>
[Dos/Win Unix] Utilities to extract images or text from .pdf files among
 other things. </p>

<hr style="width: 100%; height: 2px;"><br>

<?php
