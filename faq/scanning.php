<?php
$relPath = '../pinc/';
include_once($relPath.'base.inc');
include_once($relPath.'faq.inc');
include_once($relPath.'theme.inc');

maybe_redirect_to_external_faq();

output_header('Scanning FAQ', NO_STATSBAR);
?>

<h1>Scanning FAQ</h1>
<p>See also <a href="./cp.php">Content Provider's FAQ</a>.</p>
<hr> <a href="#1"><b>Do I <i>have</i> to use Abbyy Finereader?<br>
</b></a> <a href="#2"><b>What kind of scanner should I get?<br>
</b></a> <a href="#3"><b>Should I get a scanner with an automatic
document feeder?<br>
</b></a> <a href="#4"><b>What kind of scanner does charlz have?<br>
</b></a> <a href="#5"><b>How much does one of them cost anyway?<br>
</b></a> <a href="#6"><b>My scans are coming out very bad, any
suggestions?</b></a><br>
<a href="#7"><b>Can I use a digital camera to "scan" the images?</b><br>
</a> <a href="#8"><b>How long does it take to scan a book?</b><br>
</a> <a href="#9"><b>I have a scanner but no books that are qualified / <br>
I have several books I'd like to get on the site but don't have access
to a scanner /<br>
I have an image of a book but no OCR software </b><br>
</a> <a href="#10"><b>I don't have a computer; How can I help?</b><br>
</a><a href="#11"><b>I'm using Linux, is there an OCR package I can use?</b></a><br>
<a href="#13"><b>Speaking of Linux, what scanners are supported?</b></a><br>
<a href="#12"><b>Are there any free OCR packages available?</b></a><br>
<p> </p>
<hr> <a name="1"><b>Do I <i>have</i> to use Abbyy Finereader?</b><br>
</a>
<p> No, of course not. The scanning guidelines are heavily skewed toward
 using that simply because there are more people who have been using
that package involved in the site, so there are more people familiar
with it to answer questions. You don't need to buy the newest version,
version 5.0 Pro is great for nearly everything most people will need
to do. (The big three: charlz, aldorondo and JulietS all use version
5.0 Pro.) It is still available from many software distributors for
much less than the latest version, and you can often find it for sale
used on ebay. Avoid the Home And Sprint versions if possible. They are
missing a lot of the functionality that makes the job easy.<br>
Two other packages that people have been successful with are: OmniPage
Pro 10 &amp; 11 and Textbridge Millennium Pro. They both have good
recognition rates and similar functionality as far as automating the
scanning process. The details differ but reading the help files should
get you on the right track. OEM software that comes free with scanners
CAN be used... just be aware that accuracy is typically much worse, AND
be prepared to do a lot more saving and formatting manually. </p>
<p> </p>
<hr> <a name="2"><b>What kind of scanner should I get?</b><br>
</a>
<p> Well, there are a lot of choices. Generally you should stick with a
flatbed scanner. The typical scanner you find in a computer store is a
bit bigger than letter size (or A4 if you live in Europe) and generally
comes with one of three interfaces: SCSI, USB and parallel. SCSI is the
fastest but may require additional hardware to interface to your
computer. Most computers come with a USB port these days and these
scanners are usually easiest to set up. Parallel is the slowest
interface but may be the only realistic choice for older computers.
There are some scanners floating around with firewire or USB2
interfaces but these are usually more expensive and intended for
specific purposes. You probably will want to avoid "handheld" scanners
where you run the scanning lens down the page of text. They require a
smooth steady motion which can be difficult to do once or twice let
alone the 300 or 400 times to do a full length book. Some are also not
wide enough to do a page in a single scan and need the images
"stitched" back together; a process that can be painstaking and time
consuming. </p>
<p> </p>
<hr> <a name="3"><b>Should I get a scanner with an automatic document
feeder?</b><br>
</a>
<p> This is a mostly matter of personal preference. ADFs CAN make
scanning go much quicker, but be aware that in order to run a book
through an ADF, the book NEEDS to be destroyed, so it is probably not
realistic for rare or valuable books. The ADF is often available as an
option on a standard scanner and can be installed and removed as needed
so just <i>having</i> one doesn't mean you <i>have</i> to use it. If you
 can justify the added cost, it can make things easier and faster but it
 is not strictly necessary. </p>
<p> </p>
<hr> <a name="4"><b>What kind of scanner does charlz have?</b><br>
</a>
<p> Fujitsu FI-4340C color and duplex Flatbed and ADF</p>
<p> <img src="./fi4340c.jpg" border="3" alt="super scanner"> </p>
<p> The process we (Charles Franks) use is we tear off the cover of the
book (gasp), chop the edges on four <br>
sides of the book (double gasp!!), send it through the ADF, and then
let the book run through the site. </p>
<p> </p>
<hr> <a name="5"><b>How much does one of them cost anyway?</b><br>
</a>
<p> About $3500 US.</p>
<p> <b>Wow...</b></p>
<p> Yep. </p>
<p> </p>
<hr> <a name="6"><b>My scans are coming out very bad, any suggestions?</b></a><br>
<p> It depends on what is wrong with them. The default scanning software
 settings are usually pretty good. Make sure that you are using a "text"
 or OCR setting if one is available. Scan in black and white, not
grayscale. 300 or 400dpi is usually fine unless your text is extremely
small. Higher resolution scans make much larger image files, and they
can get pretty unwieldy in a hurry. Try adjusting the brightness up or
down to try to clear up muddy or washed-out images. Experiment around a
little. It is a good idea to make several test scans and test the OCR on
 them before doing the book. If you are using Abbyy to scan the text,
you can choose to let Abbyy control the brightness level rather than
the twain driver. This will do adaptive adjustment of the brightness
level to insure usable scans, but tends to slow down scanning a <span
 class="italic">lot</span> especially on slower computers.
Make sure to press the spine of the book down to flatten out the pages
against the scanner bed. To much "tenting" will cause guttering where
it looks like the text is running off on a curve </p>
<p> </p>
<hr> <a name="7"><b>Can I use a digital camera to "scan" the images?</b><br>
</a>
<p> This question comes up now and again especially as digital cameras
have gotten cheaper and better. The answer is... Maybe. If you have a
camera that can focus closely, light the page well and uniformly (don't
count on flash photography) ideally, have the camera mounted on a stand
or tripod to minimize movement, and make sure the page is as flat as
possible. Set your camera to take "high quality" high resolution in
black and white. Rotate, crop and convert your images as necessary. Fire
 up your OCR program and give it a shot. Yes, you can probably get
usable "scans", but be prepared for relatively low accuracy on OCR
unless you are very good or very lucky. </p>
<p> </p>
<hr> <a name="8"><b>How long does it take to scan a book?</b><br>
</a>
<p> It depends on the speed and options of your scanner and the
condition and size of the book. A high speed scanner with an ADF can
scan a 400 page book with pages in good condition in less than ten
minutes. For a standard flatbed scanner doing manual page turning, once
you get into a rhythm, you can probably do a scan every 20 to 40 seconds
 or about 3-6 pages per minute (two pages per scan), 180-360 per hour.
Allowing for glitches, short breaks, etc. a 400 page book will probably
take in the region of two hours to scan. </p>
<p> </p>
<hr> <a name="9"><b>I have a scanner but no books that are qualified / <br>
I have several books I'd like to get on the site but don't have access
to a scanner /<br>
I have an image of a book but no OCR software</b><br>
</a>
<p> Leave a message in the "Content Provider" forum to that effect. 
You can also use the OCR Pool. See the Content Provider Forum for more details. Ask and someone will help you.
</p>
<p> </p>
<hr> <a name="10"><b>I don't have a computer; How can I help?</b><br>
</a>
<p> How are you accessing this FAQ anyway? Wow, you don't let many
things get in your way do you? If nothing else, we can always use money
to buy new (old) books, new software, a new super scanner
(sooner than anticipated at the present rate :-) or other incidentals.
Find or donate books that someone else can scan. Go to your local
library, many have public access computers with Internet. You could log
on and proofread a few pages occasionally. </p>
<p> </p>
<hr> <a name="11"><b>I'm using Linux, is there an OCR package I can use?</b></a><br>
<p> There are SOME packages available. Perhaps the most highly developed
 at the time of this writing is <a
 href="http://linux.bankhacker.com/en/software/Clara+OCR/">Clara OCR</a>,
a Free-GPL OCR package. Its accuracy is poor however, and is not really
 recommended at this time. (Late 2002) Hopefully, as it develops, it
will improve. There are several commercial products that will run on
Unix/Linux, but they tend to be VERY expensive. (several  thousand
dollar range) Probably, your best bet is to make use of the OCR Pool.
See the Content Providers Forum for details.
<br>
</p>
<hr> <a name="13"><b>Speaking of Linux, what scanners are supported?</b></a><br>
<p> What you probably need to know is: What scanners are compatible with
 the SANE (Scanner Access Now Easy) driver? Go to <a
 href="http://www.mostang.com/sane/sane-supported-devices.html">this</a>
page to check compatibility.<br>
The SANE homepage is <a href="http://www.mostang.com/sane/">here.</a><br>
</p>
<hr> <a name="12"><b>Are there any free OCR packages available?<br>
<br>
</b></a>&nbsp;Here are a few:<br>
<a href="http://www.simpleocr.com/">http://www.simpleocr.com/</a>
(Windows)<br>
<a href="http://www.claraocr.org/">http://www.claraocr.org/</a> (Linux)<br>
<a href="http://jocr.sourceforge.net/">http://jocr.sourceforge.net/</a>
(Linux)<br>
<a href="http://www.expervision.com/webtr6.htm">http://www.expervision.com/webtr6.htm</a>
(Windows)<br>
<a
 href="http://ftp.cityu.edu.hk/pub/chinese/ifcss/unix/ocr/omniocr2.2.README">
http://ftp.cityu.edu.hk/pub/chinese/ifcss/unix/ocr/omniocr2.2.README</a>
(Unix - Chinese)<br>
<a href="http://http.cs.berkeley.edu/%7Efateman/kathey/ocrchie.html">http://http.cs.berkeley.edu/~fateman/kathey/ocrchie.html</a>
(Linux)<br>
<br>
Just be aware, in the OCR world, you typically get what you pay for.
<p><br>
<br>
</p>

<?php
