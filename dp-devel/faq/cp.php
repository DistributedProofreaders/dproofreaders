<?
$relPath='../../pinc/';
include($relPath.'v_site.inc');
include($relPath.'connect.inc');
include($relPath.'theme.inc');
new dbConnect();
$no_stats=1;
theme('Scanning and Submitting Guidelines','header');
?>
<meta name="Author" content="Stephen Schulze">
<meta name="author" content="thubdergnat">

<h1>Scanning, Preprocessing &amp; Submitting Guidelines</h1>

<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a
 href="./scanfaq.php">Scanning FAQ</a></p>
<hr size="3" width="100%" align="left">
<p>So you've reached the rank of "Proofer Extraordinaire" and figured
that you would branch out into different arenas.<br>
Or perhaps proofreading just isn't really your cup of tea but you still
want to help out the site.<br>
Maybe there's a book that you just can't wait to get on to Project
Gutenberg.<br>
Whatever the reason, sooner or later there comes a time when you
ask:&nbsp;&nbsp;"Just how do I submit a text to Distributed Proofreaders
anyway?" </p>
<p>These Guidelines are here to try to help you through the process.</p>
<hr size="3" width="100%" align="left"> <a href="./scanfaq.php">Frequently
asked Questions</a><br>
<p><a href="#software">Useful &amp; Necessary Software</a><br>
<a href="#what">What kinds of books do you want for Distributed
Proofreaders?</a><br>
<a href="#where">Where do I get a book to process?</a><br>
<a href="#scan">OK, I have my book, I have my clearance line; Now what?</a><br>
<a href="#ocr">Whew! I've got the image files done. What's next?</a><br>
<a href="#process">You're kidding me! I'm not done yet?</a><br>
<a href="#submit">OK, I'm finished, how do I submit the project?</a></p>
<hr size="3" width="100%" align="left">
<p><a name="software"><b>Useful &amp; Necessary Software</b></a></p>
<p><a href="http://www.abbyyusa.com/">Abbyy Finereader</a> <i>Commercial</i>
[Win32, Mac]  - <i>Current Version 6.0</i> - OCR
software.&nbsp;&nbsp;&nbsp; Very nice. :-)&nbsp;&nbsp;&nbsp;Quite
expensive. :-(&nbsp;&nbsp;&nbsp;Free Trial. :-) <br>
</p>
<blockquote> <font size="-1">5.0 Pro is <i>much</i> cheaper than 6.0
and is still available (though not directly from Abbyy software) and
does what is needed. If possible, stick with the Pro version though; the
Home and Sprint versions don&#8217;t have necessary features. Good for
scanning, but a little finicky about which ones it supports. </font> </blockquote>
<p><a
 href="http://texts01.archive.org/%7Echarlz/public/PRTK-1-0-134.zip">Proofreaders
Toolkit</a> or PRTK. <i>Freeware</i> [Win32] A nice collection of tools
specifically geared towards proofreading, bundled into one package.</p>
<blockquote><font size="-1"> Get it. Install it. Use it. You NEED this
software. You CAN do everything <i>it</i> does manually or using other
separate software packages, but the convenience of having them all
together is nice. </font></blockquote>
<p><a href="http://gutcheck.sourceforge.net/">Gutcheck</a> <i>Free-GPL</i>
[Dos/Win, Unix] Gutenberg text checking program. Needed to check
compliance of text to Gutenberg standards. </p>
<blockquote><font size="-1"> Gutcheck is integrated into PRTK but the
version that comes with it is rather old. You should download the latest
version of Gutcheck and extract it into the PRTK directory, overwriting
the existing files. (Default directory = "C:\Program files\ProofReader's
Toolkit") </font></blockquote>
<p><a href="http://www.irfanview.com/english.htm">Irfanview32</a> <i>Free</i>
[Win32] - Nice general purpose image manipulation and conversion
software.</p>
<p><a href="http://www.foolabs.com/xpdf/">Xpdf</a> <i>Free-GPL</i>
[Dos/Win Unix] Utilities to extract images or text from .pdf files among
other things.</p>
<p><a href="http://www.1-4a.com/rename">1-4a-Rename</a> <i>Freeware</i>
[Win32] Nice very configurable utility for batch renaming files. Very
point 'n click.<br>
</p>
<p><a
 href="http://www.albert.nu/common.asp?sub=programs/default.asp?sub=renamer/main.htm">Renamer</a> <i>Freeware</i>
[Win32] Another useful utility for batch renaming of files.&nbsp; </p>
<p><a href="http://ole.tange.dk/projekter/dp/">convert-to-dp</a> <i>Free-GPL</i>
[Perl] Utility to unpack .tif or .pdf files into .png files, crop images
and also split dual page scans into two separate files.</p>
<p> <a
 href="http://sourceforge.net/project/showfiles.php?group_id=14481">7.zip</a> <i>Free-GPL</i>
[win32 Unix] Free utility to uncompress .zip archives.<br>
</p>
<p><a href="http://www.iceows.com/HomePageUS.html">ICEOWS</a> &nbsp;<span
 style="font-style: italic;">Freeware</span> [Win32] Compress files in
ICE and ZIP formats and uncompress nearly any common format. Many
language interfaces available.<br>
</p>
<p><a href="http://www.info-zip.org/pub/infozip/">Info-ZIP</a> <span
 style="font-style: italic;">Free-BSD</span> [Nearly all OS's and
Platforms] A collection of utilities for working with zip format
compressed files. Support for a large number of platforms and OS's. <br>
</p>
<p><a href="http://www.filzip.com/en/index.html">FILZIP</a> <span
 style="font-style: italic;">Freeware</span> [Win32] Point and click
manipulation of compressed files. GUI interface. Multiple file
extraction. Lots of nice features.<br>
</p>
<p> <a href="http://www.winzip.com">WinZip</a> <i> Shareware</i> [Win32]
Utility to create and extract .zip archives. Free trial.</p>
<p><a href="http://www.ftpplanet.com/download.htm">WS_FTP LE</a> <i>Shareware</i>
[Win32] Easy to use FTP client. Free for non-commercial use.<br>
</p>
<p><a href="http://www.smartftp.com/">Smart FTP</a> <span
 style="font-style: italic;">Shareware</span> [Win32] Another easy to
use FTP client. Free for-non commercial use.<br>
</p>
<hr size="3" width="100%" align="left">
<p><a name="what"><b>What kinds of books do you want for Distributed
Proofreaders?</b></a></p>
<p>What kinds of books do you have? :-)</p>
<p>Seriously, there are really few restrictions on what kind of text
you can submit to DP. The biggest, and probably most important is: The
book MUST be either in the Public Domain OR the copyright must have
expired. In general this tends to mean books that were written before
1923. There <i>are</i> exceptions to the 1923 rule, but a lot of times
it is troublesome to try to try to prove them. There is a good detailed
discussion of what is and isn't eligible at the Project Gutenberg site
on <a href="http://www.promo.net/pg/vol/pd.html">this</a> page.&nbsp;
For a discussion of copyright terms in other countries, check out <a
 href="http://digital.library.upenn.edu/books/okbooks.html">this</a>
page.<br>
<a href="http://catalog.loc.gov">The Library of Congress Catalog</a> is
a great place to check publishing dates of books published in the U.S.</p>
<p>The book should not already be on the Project Gutenberg site. This
site exists as a feeder site to Project Gutenberg, and it makes little
sense to spend all the time and effort on a text that is already there.
A different <span style="font-style: italic;">version</span> of an
existing book is OK though. You can check the <a
 href="http://www.promo.net/pg/">Project Gutenberg search page</a> to
see if a book is already on there.</p>
<p>There is also a site called <a
 href="http://www.dprice48.freeserve.co.uk/GutIP.html">David's
In-Progress List</a> listing all of the books that people are presently
working on. Again, this is helpful to avoid duplication of effort. </p>
<p>You might want to stick with a shorter fictional work for the first
project you submit. Remember, after the book goes through the proofing
cycle, with few exceptions, the person who <i>submitted</i> the book is
expected to <i>post-process</i> the book as well. If you are unable or
unwilling to do post-processing, you need to make that clear when you
submit the book. (Or even better, BEFORE you submit it.) <br>
It is probably better to avoid books which contain a <i>lot</i> of
illustrations, maps, charts, tables and pictures. While they make for an
interesting text, they are nearly or completely impossible to render in
ASCII, and the text loses a large amount of usefulness. (Something like
that might be better off as a scanned image file on "The Million Book
Project"... but that's another FAQ.)</p>
<p>Non-English language texts are fine too, within reason. Keep in mind
that the text will need to be renderable in ASCII, not Unicode, so if
the language contains a LOT of non-ASCII characters, it will make both
proofing and post-processing more difficult, and somewhat defeats the
purpose of using the DP site. European languages seem to be OK. Greek,
Sanskrit, Arabic, etc, are a problem.</p>
<p> It is helpful, though not strictly necessary that you understand the
language that the book is written in. It will theoretically make it
easier during post-proofing to be able to tell from context whether
paragraph breaks should be at page breaks, however, checking back
against the original book can get you through too. </p>
<hr size="3" width="100%" align="left">
<p><a name="where"><b>Where do I get a book to process?</b></a></p>
<p>Libraries, flea markets, yard sales, auctions, estate sales, your
parents/grandparents, the trash, (you'd be AMAZED at what people throw
away!) used book shops, friends, schools, you name it, there's books
there. It is better to have a book that you will have access to for the
whole time the project is being worked on so you can refer back to it if
problems turn up in the scans. (Happens depressingly often)</p>
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
<p>There are also many sites which have books available online as .pdf
or image files which can be downloaded and OCRed. University libraries
and historical societies seem to be rich sources. This is especially
helpful if you don't have access to a scanner or physical books. There <span
 style="font-style: italic;">are</span> drawbacks: they are usually a
fairly intensive download, especially over dial-up; you don't have
access to the actual book to check against if there are later problems,
and the selection is limited. Not having to do the scanning is a big
plus though.<br>
</p>
<p>Some of these, but by no means all are:<br>
<a href="http://www.cwru.edu/UL/preserve/digitized.htm"> Case Western
Reserve University</a> - Digital collection<br>
<a
 href="http://www.archive.org/texts/textslisting-browse.php?collection=millionbooks&amp;cat=Titles">The
Million Book Project</a><br>
<a href="http://gallica.bnf.fr">Gallica</a>&nbsp; French texts.<br>
<a href="http://www.cta.dmu.ac.uk/projects/Hockliffe/">The Hockliffe
Project</a> British children's books<br>
<a href="http://digital.lib.msu.edu/onlinecolls/">MSU Digital collection</a>
Or for direct access to scans, go <a
 href="http://lib0131.lib.msu.edu/dmc/">here</a><br>
<a
 href="http://www.ling.upenn.edu/%7Ekurisuto/germanic/language_resources.html">Sean
Crist's</a> Germanic language texts<br>
<a href="http://www.canadiana.org" target="_blank">http://www.canadiana.org</a>&nbsp;
Specifically request that they <span class="postbody">(Canadian
Institute for Historical Microreproductions) </span>are credited as the
source of the images<br>
</p>
<p>There is a larger list of possible scan source sites in the Content
Providers Forum under the topic <a class="maintitle"
 href="<? echo $forums_url ?>/viewtopic.php?t=798&amp;start=0&amp;postdays=0&amp;postorder=asc&amp;highlight=">"Where
to find scanned images online"</a></p>
Please follow the individual site guidelines regarding acceptable use
and protocol. We don't want to be bad neighbors.<br>
If you <i>do</i> go this route, is considered good form to credit the
source of the scan when the text is submitted to Project Gutenberg.
<p> </p>
<p>Once you have found a book that you think might be a good candidate,
the first thing you should do is get a <span style="font-weight: bold;">clearance
line</span>. This is an approval, if you will, of the book for the
Project Gutenberg site, and also registers the book as being a work in
progress to let other people know it is reserved so as not to duplicate
effort. </p>
<p>To quote the <a
 href="http://www.gutenberg.net/vol/wannabe.html#Copyright_research">Project
Gutenberg FAQ</a></p>
<blockquote>
  <table border="5" cellspacing="0" cellpadding="5" width="400">
    <tbody>
      <tr>
        <td>
        <h3>How do I get copyright clearance?</h3>
        <p>As of January, 2002, there are two ways to submit a text for
clearance. </p>
        <p><strong>To submit by paper mail,</strong> photocopy the front
and back of   the title page, even if the back is blank, write your
e-mail address on it,   and send the photocopies to:</p>
        <p>MICHAEL STERN HART<br>
405 WEST ELM STREET<br>
URBANA, IL 61801-3231 USA</p>
        <p>This is called Title Page &amp; Verso, or TP&amp;V for
short, and is   needed for copyright research. A colored envelope is
best, to make sure your   letter is easily recognized as TP&amp;V.</p>
        <p>E-mail Michael <a href="mailto:hart@pobox.com">hart@pobox.com</a>
when you   send them, so he knows they're on the way. It's a good idea
to check back   with him by e-mail after a week or so if you haven't
heard from him.</p>
        <p><strong>To submit by e-mail,</strong> scan the front and back
of the title   page, even if the back is blank, make sure that the print
is legible, and   e-mail the images to <a
 href="mailto:gbnewby@ils.unc.edu">Greg Newby</a> as   TIFF, JPEG or GIF
in medium resolution. </p>
        <p>Whichever method you use, you should expect to get an e-mail
back after   about a week, with one line containing the Author, Title,
your name and date   with the word "OK" at the end. This means that your
text has been   cleared. If you don't get any response, e-mail to check
that your TP&amp;V   was received OK. If the word at the end of the line
is not "OK",   then your text is not eligible. </p>
        <p>Now you start scanning</p>
        </td>
      </tr>
    </tbody>
  </table>
</blockquote>
<p>There is a new Web interface for submitting clearance requests to
Greg.<br>
Go to <a href="http://beryl.ils.unc.edu/copy.html">this page</a> to
give it a try. There are quite a few handy tips and links there also.<br>
</p>
<p>You probably should not invest too much time until you've received
your clearance line. </p>
<hr size="3" width="100%" align="left">
<p><a name="scan"><b>OK, I have my book, I have my clearance line; Now
what?</b></a></p>
<p>Now you need to scan it.<br>
</p>
<p>There are too many scanners and scanning packages to give specific
instructions here. In general, good all-purpose parameters for scanning:
300dpi, black and white (not grayscale), and average brightness unless
the paper is very yellow. Higher dpi doesn&#8217;t necessarily make for better
OCR unless the text is <i>extremely</i> small. You want to end up with
good, reasonably clean images that the OCR software won't choke on.</p>
<p> The following examples and explanations assume that you are using
Abby Finereader. This FAQ tends to concentrate on using Abbyy Finereader
Pro because: </p>
<ul>
  <li>It is one of the more popular OCR packages used by the DP
administrators. </li>
  <li>It is very accurate on pretty lousy images and, let's face it,
100 year old books are not typically in the best of      shape. </li>
  <li>It is pretty easy to automate for most of the process. </li>
  <li>It is free to try for 30 days or 15 hours of use.</li>
</ul>
<p> Abbyy Finereader Pro 5.0 or higher (and most other high end OCR
programs) have built in scanning functionality and will allow you to
automate the process to a great extent. In Finereader, to open a new
batch. Click on File-&gt;New Batch, (Ctrl+N) and give it an appropriate
name. (The title of the book, abbreviated, is a good choice) This is
where Finereader stores all of the interim files for the project. It is
probably a good idea to make a separate batch directory in which to put
all of your individual batches. </p>
<p>As a matter of fact, while we're on the subject, let's talk about
directory structure a little bit. It is a good idea to use a logical
directory structure to help keep track of things.&nbsp; There is no
"right" or "wrong" way to do this, it mostly depends on personal
preference. However, using the following structure may save headaches
later, especially if you plan to do several projects.<br>
</p>
<p>&nbsp;Starting at the appropriate place in your directory structure
(Shown as "C:\" &nbsp;in this example, choose a place comfortable for
you.) Make two directories: "Batch" and "Projects".<br>
</p>
<p>Every time you start a new batch in Abbyy, it automatically
generates a directory where it stores raw image and text data, named
with a batch name that you specify. Save this under the "Batch"
directory <br>
</p>
<p>Under the "Projects" directory make another directory. Name this
with the same name as the "Batch" name used in Finereader. Under that
directory make two more directories: "text" and "pngs". These are where
you will store the images and text files that you will need to upload to
DP.<br>
</p>
<p>Here's a little graphic to demonstrate. Assuming a book named Book1:<br>
</p>
<blockquote> <img src="structure.png" border="3" width="234"
 height="143" alt="Directory Structure"><br>
  <br>
  <br>
</blockquote>
<p>Select File-&gt;Scan Multiple images (Ctrl+Shift+K) to start
scanning the book. From here the procedure will vary greatly depending
on what features your scanner has, (automatic document feeder or not)
and your personal preferences, (acknowledge each scan or have a timed
pause between.) Obviously, other packages will be different; your best
bet is to check the help files that came with your specific package.</p>
Some OCR packages may need to have the images split into two separate
pages. (see <a href="#software">software</a> section) Finereader can do
this automatically as long as there is some white space between the
pages. If there&#8217;s any question, it&#8217;s best to test a few scans.<br>
Crop the images, if necessary, to minimize black borders around the
page image. If you are ending up with LARGE black borders around your
page image, you should probably adjust your scanning "window" smaller to
avoid scanning outside where the page lays on the scanner bed. Doing
this will save you both time-the scanner doesn't have to scan such a
large area-and space on your drive-smaller files. Don't crop the image
down till there is no or very little margin around the text, this can
affect recognition and can cause difficulties during the proofing
process. Ideally, what you want is some white space around the text, but
no black. When you save your image files, you probably want ".tif" or
".png" format image files. Later you'll NEED ".png" format files, so if
your OCR software can handle them it might be better to use them now.
Avoid saving them as jpegs (lossy format) or .bmp bitmaps (huge files).
Under Finereader, to save all the image files at once, select them all
first,(click in the thumbnail window and press Ctrl-A) then choose
File-&gt;Save Images (F12), and be sure to give the images a name since
it doesn&#8217;t insert the batch name automatically. It will save them in a
series with the specified name, a hyphen, and a four digit counter.
(Book1 - 0001.png, Book1 - 0002.png... etc.) Save them to the
"Projects\Book1\pngs" directory.
<p><b>VERY IMPORTANT! </b>- Make sure the files are named in an order
that is sequential and alphabetically ordered. (Automatic under
Finereader-as long as they were loaded in the correct order.) If your
package allows it, your best bet is to name the files "001.png (or
.tif), 002.png, 003.png, etc". (Finereader doesn't, you'll need to
rename them later in the preprocessing section. It will name them
sequentially but not in the exact format we need.) This will make it
easier to keep the order straight and avoid gaps and holes in the naming
system. (And besides, you'll need to get them into this format later
anyway.)</p>
<p><span style="font-weight: bold;">For e-texts/.pdf files</span>, you
want to end up in the same place. If the page images are available as
single page .tifs, .gifs, or .pngs you'll need to download them, convert
them to .pngs, and make sure the filenames follow the correct format. If
you have multi page images, you may need to split them first. With .pdf
files you'll need to use one of the software utilities to extract the
.tif (usually) images from the .pdf</p>
<p><b>Note:</b> Abbyy Finereader OCR 6.0 is capable of working directly
with .pdf files. You don't need to extract the images first. If you set
up a batch, it will extract .tif images to the batch directory
automatically as it is loading the .pdf files. These can then be
converted to .pngs for later use. </p>
<hr size="3" width="100%" align="left">
<p><a name="ocr"><b>Whew! I've got the image files done. What's next?</b></a></p>
<p>Now you've got to run the images through an OCR (Optical Character
Recognition) program. Again, there are too many programs out there to
give useful specific directions for them all. You will need to wind up
at the same place though the path you take may be different.<br>
</p>
<p>If you don't have an OCR package, try to join up with someone who
does, post a message in the "Content Providers" forum to that effect.
Most likely, someone will be glad to help. </p>
<p>Assuming you DO have OCR software...</p>
<p>If you used Finereader for the scanning, you&#8217;ve already set up a
batch and the images are already there.</p>
<p> If not, open up Abbyy FR OCR. Click on File-&gt;New Batch, (Ctrl+N).
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
window. If they are out of order, under Abbyy 6.0 you can renumber the
images under the "Batch Processing" menu. In Abbyy 5.0 this is
non-trivial. Better to start in the right order.
<p> Check settings under "Tools--&gt;Options". Select the correct
language for the text. Hit (Ctrl-shift-R) or the "read all" icon, to
initiate the OCR sequence, then go away for another (usually shorter)
break. There is also an option under the "Process" menu to perform
background processing, which allows you to minimize the window and do
other things while waiting.</p>
<p>When that is done, save the files to "Projects\Book1\text"
directory. When saving text (F2 or File-&gt;Save Text), you must choose
to save each page as a separate file, and under options, choose the "Use
blank line for paragraph break" and "Insert line break options". You'll
end up with a list of files in the format; "Book1" - 0001.txt, "Book1" -
0002.txt... etc. These will need to be renamed in the same format as the
image files. i.e. 001.txt, 002.txt,... again, this can be done in the
pre-processing section.</p>
<hr size="3" width="100%" align="left">
<p><a name="process"><b>You're kidding me! I'm not done yet?</b></a></p>
<p>Almost, almost... At this point I would recommend that you save
copies of the original text to a separate directory in case something
unexpected and undesirable happens during the next section.</p>
<p>Now you are going to need to do a little preprocessing on those text
files. You are going to need to fire up PRTK and have at it, but:</p>
<p><b>BEFORE YOU START!!</b> <br>
PRTK can't deal very well with zero byte files. When it encounters one,
it tends to lock up and die a horrible death. So you need to make sure
there are no zero byte files among your text files. Go to the directory
where the text files are stored. Set your file browser view so that file
size details are shown, then sort the files smallest to largest. Look at
the smallest text file. If it is greater than zero bytes you are OK. If
not, open the file with a text editor and put SOMETHING in there. "Blank
Page" is popular. (Ah! so THAT'S why they show up occasionally when I'm
proofing pages.) Actually, it&#8217;s a good idea to put two lines of
something in there to save aggravation later.<br>
Something like:</p>
<p> HEADER<br>
Blank Page<br>
</p>
As long as it is no longer zero bytes. Do this for all of the zero byte
.txt files  then save them as plain text.
<p>If you haven't already, you are going to need to rename the image
files into the format "001.png, 002.png, 003.png.... etc. with no holes
or skips in the sequence, in the SAME order that the book is in. You
MUST have the leading zeros and there MUST be only the 3 characters
(digits) in the file name. PRTK has a tool to allow you to rename your
image and/or text files. Click on the Tools menu and go to File Renamer.
This will open up a somewhat terse dialog allowing you to do just that.
(Or, if you would prefer, use one of the other utilities available, 1-4a
Renamer in the <a href="#software">software</a> section is very nice.)
When that is done, do the same thing with the text files. If your book
has more than 1000 pages, (!!) split it into two roughly equal sections
and submit it as two different projects to go through proofing, then
reassemble it in post processing.</p>
<p>You now hopefully have the image files and text files, named in the
correct format with the corresponding numbers pointing to the same page.
(one image, one of text) Check several pairs randomly, if they don't
correspond, you need to figure out why and fix it before the text can be
submitted. Make sure there are the same number of image and text files.</p>
<p>In PRTK, open Processing-&gt;Text Batch Pre-Proofing. At the bottom
of the dialog box, browse to or type in the directory containing the
text files. In general, it is safe to just leave all of the default
checkbox and radio button settings. Some foreign language or specialty
texts may be adversely affected. You may need to experiment on copies to
find a good set of options. Most are pretty self-explanatory. The only
setting which really needs more explanation is the "Remove Headers"
button. Make sure the directory setting is pointing to the correct
directory then click on "Enter Headers".<br>
Click "Read Headers" This will show the top line of every text file.
Select the check box beside each one you want to get rid of. Usually, it
is easier to click "Select All" then UNCHECK the ones you DON'T want to
erase. (Here is where that HEADER line comes in the zero byte files.)
When you are satisfied with your selection, click "Insert". Then click
"Done". Then hit "Start" and watch the progress bar fly by. </p>
<p>Pre-processing complete.</p>
<hr size="3" width="100%" align="left">
<p><a name="submit"><b>OK, I'm finished, how do I submit the project?</b></a></p>
<p>If this is your first time submitting a project and/or you are not a
project manager,&nbsp; send an email to <a
 href="mailto:juliet.sutherland@verizon.net">JulietS</a>, (preferred)
or one of the two Charles&#8217;s, <a href="mailto:Charles@Aldarondo.net">Aldarondo</a>
or <a href="mailto:charlz@lvcablemodem.com">charlz</a> that includes the
author, title, etc and, ideally, the clearance line and any comments you
may want included on the project page. &nbsp;Make sure you include your
name and a contact email address (if different from the sending
address). They will contact you with an FTP address where you can upload
the image and text files. Upload all of the .png and .txt files you
generated earlier into that directory. When that is done, email back to
the person who contacted you. Alternately, if you anticipate having
several projects, you may want to send a message to <a
 href="mailto:juliet.sutherland@verizon.net">JulietS</a>&nbsp; and ask
to be made a project manager. &nbsp;This will open up access to some of
the project creation and control features. The same general procedures
are used once you are a project manager, you just need to create your
own project pages and set up your own upload directories, details are
given on the project managers page.<br>
<br>
At this point it is probably safe to delete the batch directory used by
Finereader under the "Batch" directory. You could always regenerate it
from the image files again if necessary. Keep the text and image files
around at least until the book is done post-processing and has been
submitted to Project Gutenberg so you can refer back to them, if
necessary, especially if you are going to do the post-processing
yourself. </p>
<p>Wow! That was fun, let's do another! :-)</p>
<p>Remember, after the text has made it through the site, then you get
to do post-processing... See the <a
 href="../post_proof.php">Post-Processing
FAQ</a> for more details. </p>

<?
theme('','footer');
?>
