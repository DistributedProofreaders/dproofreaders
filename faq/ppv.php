<?php
$relPath='../pinc/';
include_once($relPath.'base.inc');
include_once($relPath.'faq.inc');
include_once($relPath.'theme.inc');

maybe_redirect_to_external_faq();

output_header('Post-Processing Verification Guidelines', NO_STATSBAR);
?>

<!--Updated May 2008 by stacy-->
<!--Updated April 2015 by lhamilton-->
<!--Text Start Here-->
<div style="margin-left: 5%; margin-right: 5%;">

<h1>Post-Processing Verification Guidelines</h1>
<center>Updated: 30 August 2015</center>

<h2>Contents</h2>

<p>
<a href="#gen">A) General Procedures</a><br>
<a href="#rr">B) Requests to Revise and Resubmit</a><br>
<a href="#checks">C) Specific Checks to Make</a><br>
<a href="#printers">D) Printers' Errors and Transcriber's Note</a><br>
<a href="#reader">E) Checking E-reader versions</a><br>
<a href="#rptcard">F) Submitting a PPV Summary</a><br>
<a href="#late">G) Late PPV Summaries</a>
</p>

<h2><a name="gen"></a>A) General Procedures</h2>

<ol>
<li>Make <a href="#checks">all the checks</a> you would make on a project you are post-processing.</li>
<li>Keep track of all errors and inconsistencies remaining in the project as
well as any corrections made.</li>
<li>Send the PPer a feedback note describing the required changes, suggesting amendments, and providing helpful hints
for future work.
<li>When the agreed-upon changes have been made and checked, upload the
project to PG.</li>
<li>Submit a <a href="#rptcard">PPV
Summary</a>. Generally, PPers should be copied on the PPV Summary.</li>
</ol>

<h2><a name="rr"></a>B) Requests to Revise and
Resubmit</h2>

<p>Please strongly encourage PPers to make corrections and amendments
themselves. (Of course, if PPers specifically request that you make
the changes for them, you may abide by their wishes.)</p>
<ol>
<li>Use the option on the project page to return the project to the PPer's queue.</li>
<li>Send them an email or private message letting them know that the
project will show up in their queue again. The message should note
the items to be corrected, and/or make suggestions that may improve
the project (specifying that these are not errors if that is the
case).</li>
<li>When complete, the PPer should reupload the project for PPV and
notify the PPVer that the revised files are now available.</li>
</ol>


<h2><a name="checks"></a>C) Specific Checks to Make</h2>
<p>Please check for the following types of errors, using tools you usually use
for PPing: </p>
<h3><a name="errors"></a>Errors</h3>
<p>Errors such as failure to grasp the italics guidelines are counted as 
one error, not one error each time italics are wrongly handled. Errors such 
as he/be errors are each counted as individual errors (i.e., 3 "he" instead 
of "be" count as 3 errors).</p>
<p>If the PPer is asked to resubmit a corrected file, then any errors not 
corrected or new errors introduced are added to the total number of errors 
for rating purposes.</p>

<h3>Level 1 (minor errors) - All Versions</h3>
<ul>
<li>Spellcheck/scanno errors</li>
<li>Gutcheck-type errors, e.g., punctuation, hyphen/emdash, missing/extra space, line
length, illegal characters, etc.</li>
<li>Jeebies errors (English only)</li>
<li>Paragraph breaks missing or incorrectly added</li>
<li>A few occurrences of hyphenated/non-hyphenated, spelling and
punctuation variants and other inconsistencies not addressed (may be
addressed by note in the TN)</li>
<li>Chapter and other headings inconsistently spaced, aligned, capitalized or
punctuated</li>
<li>Formatting inconsistencies (e.g., in margins, blanks lines etc.)</li>
<li>Other minor errors (such as a minor rewrap error, misplaced entry in the
TN, or minor inconsistency between the text and HTML versions)</li>
</ul>

<h3>Level 1 Errors - HTML Version Only</h3>

<h4>Images</h4>
<ul>
<li>Unused files in images folder (other than Thumbs.db)</li>
<li>Appropriate image size not used for thumbnail, inline and linked-to images.
Image sizes should not normally exceed the limits described <a href="http://www.pgdp.net/wiki/Guide_to_Image_Processing#Image_Display_Dimensions:_Considerations">here</a>,
but exceptions may be made if warranted by the type of image or book
(provided the PPer explains the exception).</li>
<li>Images with major blemishes, uncorrected rotation/distortion or without
appropriate cropping.</li>
<li>Failure to enter image size appropriately via HTML attribute or CSS such
that the image is distorted in HTML, epub or mobi.</li>
<li>Failure to use appropriate &quot;alt&quot; tags for images that have no
caption and to include empty &quot;alt&quot; tags if captions exist.</li>
</ul>

<h4>HTML Code</h4>
<ul>
<li>Use of px sizing units for items other than images and borders</li>
<li>&lt;title&gt; missing or incorrectly worded (Should be &lt;title&gt;The Project
Gutenberg eBook of Alice's Adventures in Wonderland, by Lewis
Carroll&lt;/title&gt; or &lt;title&gt;Alice's Adventures in
Wonderland, by Lewis Carroll&mdash;A Project Gutenberg
eBook&lt;/title&gt;)</li>
<li>Use of &lt;pre&gt; tags instead of their  CSS equivalents</li>
<li>Failure to place &lt;html&gt;, &lt;body&gt;, &lt;head&gt;, &lt;/head&gt;&lt;/body&gt;,
and &lt;/html&gt; tags each on its own line and correctly use them. (This is required by the WWers)</li>
<li>Use of tables for things that are not tables</li>
<li>Used CSS other than CSS 2.1 or below (except for the dropcap
&quot;transparent&quot; element
and other CSS 3 code permitted by <a href="http://upload.pglaf.org/">PGLAF</a>)</li>
<li>Used HTML version other than XHTML 1.0 Strict or 1.1</li>
<li>Failure to use &lt;div class=&quot;chapter&quot;&gt; or &lt;div class= &quot;section&quot;&gt; at chapter breaks
to enable proper page breaks for e-readers
(Please see
<a href="http://www.pgdp.net/wiki/Easy_Epub/Headings#Formatting_Chapter_Headings_to_Avoid_unwanted_page_breaks_mid-chapter">Easy Epub</a>
for more details).
It is also acceptable to use &lt;div class=&quot;chapter&quot;&gt; &lt;/div&gt;
or &lt;div class=&quot;section&quot;&gt; &lt;/div&gt;</li>
<li>Minor HTML errors in code that do not generate an HTML validation alert
(such as misspelling a language code)</li>
</ul>

<h3>Level 2 (major errors) - All Versions</h3>
<ul>
<li>Markup not handled (e.g. blockquotes, poetry indentation, or widespread
failure to mark italics)</li>
<li>Poetry indentation does not match original</li>
<li>Footnotes/footnote markers missing or incorrectly placed</li>
<li>Printers' errors not addressed</li>
<li>Missing page(s) or substantial sections of missing text</li>
<li>Substantial rewrapping errors, e.g., poetry has been rewrapped or text version
generally rewrapped so that it doesn't exceed 75
characters or fall below 55 characters (though the aim should be 72 characters) except where unavoidable,
e.g., some tables </li>
<li>Widespread/general occurrences of hyphenated/non-hyphenated, spelling and punctuation
variants and other inconsistencies not addressed (may be addressed
by note in the TN)</li>
<li>Other major errors that could seriously impact the readability of the book or that represent major
inconsistencies between the text and the HTML versions</li>
</ul>

<h3>Level 2 Errors - HTML version only</h3>
<ul>
<li>The W3C Markup Validation Service generates errors or warning messages
(Please enter number of errors)</li>
<li>The W3C CSS Validation Service generates errors or warning messages
other than for the dropcap &quot;transparent&quot; element
and other CSS 3 code permitted by <a href = "http://upload.pglaf.org/">PGLAF</a>
(Please
enter number of errors). Certain errors can generate other errors that will be automatically corrected when the original errors are fixed. Therefore, to count the number of real errors, simply run the Validator and count the errors that follow the message that includes "start tag was here". That will give you the real errors to enter into the PPV Form.</li>
<li>Non-working links within HTML or to images. (Either broken or link to wrong
place/file)</li>
<li>File and folder names not in lowercase or contain spaces, images not in
&quot;images&quot; folder, etc.</li>
<li>Cover image has not been included and/or has not been coded for e-reader
use. (For example, the cover should be 600x800px or at least 500px
wide and no more than 800px high and should be called cover.jpg.
Also, if the cover is newly created, it must meet <a href="http://www.pgdp.net/wiki/PP_guide_to_cover_pages#DP_policy">current
DP guidelines</a>.)</li>
<li>Project not presentable/useable when put through epubmaker (Please see the
section below on Checking E-reader Versions)</li>
<li>Heading elements used for things that are not headings and failure to use
hierarchical headings for book, chapter and section headings (single
h1, appropriate h2s and h3s etc.)</li>
</ul>

<h3>Strongly Recommended (not counted toward
rating but please mentor the PPers to comply with these
recommendations)</h3>

<ul>
<li>Enclose entire multi-part headings within the related heading tag</li>
<li>Avoid using empty tags (with &amp;nbsp; entities) or &lt;br /&gt; elements
for vertical spacing. e.g. &lt;p&gt;&lt;br /&gt;&lt;br /&gt;&lt;/p&gt;
(or with nbsps) -- &lt;td&gt;&amp;nbsp;&lt;/td&gt; is still
acceptable though</li>
<li>List Tags should be used for lists (e.g. a normal Index).
For further information please read
<a href="http://www.w3.org/wiki/HTML_lists">W3's List Use section</a></li>
<li>Include all text as text, not just as images</li>
<li>Keep your code line lengths reasonable</li>
<li>Tables display left, right, and center justification and top and bottom
align appropriately </li>
<li>Tables contain &lt;th&gt; elements for headings</li>
<li>Remove thumbs.db file from the images folder</li>
<li>E-reader version, although without major flaws, should also look as good as
possible</li>
</ul>

<h3>Mildly Recommended</h3>
<ul>
<li>Distinguish between purely decorative italics/bold/gesperrt and semantic uses of
them</li>
<li>Include space before the slash in self-closing tags &lt;br /&gt;</li>
<li>Ensure that there are no unused elements in the css (other than the base
HTML headings)</li>
</ul>


<h2><a name="printers"></a>D) Printers' Errors and Transcriber's Note</h2>
<p>Obvious printers' errors should be addressed in one, or a combination, of the
following ways:</p>

<ul>
<li>Correct silently (in other words, change things without flagging each 
change within the text) and state in the Transcriber's Note that all such 
errors have been corrected silently. If there are changes made this way, 
there should be at least one Transcriber's Note to say that this has been 
done in the text (Level 1 Error).</li>
<li>Correct all such errors and note them in Transcriber's Note</li>
<li>Leave uncorrected and state in the Transcriber's Note that at all such
errors were left uncorrected.</li>
</ul>

<p>&quot;Not addressing printers' errors&quot; means that all, or a large
percentage, of printers' errors have been left uncorrected and not
noted. If just one or two have been missed, and the rest addressed,
then those missed would instead be counted as the relevant type of
error (spellcheck, gutcheck, etc.).</p>

<p>Anything that could make a reader think an error has been made in the
transcription should be mentioned in the Transcriber's Note.</p>


<h2><a name="reader"></a>E) Checking E-reader versions</h2>
<p>With the clear expectation from PG and the recommendation of the DPF Board
that our projects should look good in e-reader versions, the PPV
community have agreed that PPVers should support PPers (and check their work) in producing HTML
versions that convert successfully to epub and
mobi (Kindle) formats.</p>

<p>The <a href="http://www.pgdp.net/wiki/Easy_Epub">Easy
Epub</a> wiki pages are intended to help with that process of checking
e-reader versions and making some simple changes to improve them if
necessary.</p>

<p>The wiki pages include <a href="http://www.pgdp.net/wiki/Easy_Epub/Viewing">Viewing</a>
- How to use epubmaker to view your project in e-reader format, even
if you don't have an e-reader. </p>
<p>Also, understanding the <a href="http://www.pgdp.org/~jana/best-practices/pages/best-practices/">Best
Practices</a> helps us understand the coding practices that can
ensure the project converts seamlessly to e-reader format.</p>

<p>Please use a <a href="http://www.pgdp.net/wiki/Easy_Epub/Viewing#I_don.27t_have_an_e-reader.21">suggested
viewer</a> to test the epub and mobi versions of the book.</p>

<p>It
doesn't take long to look through the pages of the epub and mobi
versions. Here are some problem areas to look for:</p>

<h3>Front and End of Book</h3>
<ul>
<li>TOC</li>
<li>Title page layout</li>
</ul>

<h3>Body of Book</h3>
<ul>
<li>Horizontal rules</li>
<li>Obscured sections within the book such that text covers other text or blank
areas occur where text should be</li>
<li>Poetry</li>
<li>Dropcaps</li>
<li>If hovers were used in the HTML (which isn't recommended), all
important &ldquo;hovered&rdquo; information should be present
and readable in a non-hovered way within the e-reader version. Also
Transcriber's Notes referring to hovers should be hidden in the
e-reader version.</li>
<li>Headings</li>
<li>Blockquotes</li>
<li>Page numbers (if present)</li>
<li>Sidenotes</li>
<li>Margins</li>
<li>Tables</li>
<li>Illustrations</li>
</ul>


<h2><a name="rptcard"></a>F) Submitting a PPV Summary</h2>

<p>Using the link on the project page, submit a PPV Summary for the project,
using the following criteria for determining project difficulty and
rating of PPer's work:</p>


<h3>Determining whether a project is Easy, Average, or Difficult</h3>

<p><b><font color="maroon">The PPV Summary Form will calculate whether a project is Easy, Average, or Difficult
based on the information you provide.</font></b></p>

<h4>Easy Project</h4>
<ul>
<li>Straight fiction with little or no poetry</li>
<li>Straight poetry</li>
<li>Other straight text, e.g., essay</li>
<li>Can include some illustration markup</li>
<li>Can include a few simple footnotes, blockquotes, etc.</li>
<li>No index</li>
<li>4 or fewer illustrations (not counting minor decorations or logos)</li>
</ul>

<h4>Average Project</h4>
<p>Can include 3 or more of the following kinds of content:</p>
<ul>
<li>poetry other than straight poetry covered under &quot;Easy Project&quot;</li>
<li>blockquotes</li>
<li>footnotes</li>
<li>sidenotes</li>
<li>index</li>
<li>advertisements</li>
<li>tables</li>
<li>5 or more illustrations (not counting minor decorations or logos)</li>
</ul>

<p>If a project has 0 or more &ldquo;average&rdquo; elements and 1 to 3 &ldquo;difficult&rdquo;
element, it qualifies as an &ldquo;Average&rdquo; project.</p>

<h4>Difficult Project</h4>
<p>Includes significant amounts of  4 or more of the following kinds of content:</p>
<ul>
<li>poetry other than straight poetry
covered under &quot;Easy Project&quot;</li>
<li>blockquotes</li>
<li>footnotes</li>
<li>sidenotes</li>
<li>index</li>
<li>advertisements</li>
<li>tables</li>
<li>drama</li>
<li>illustrations requiring advanced
preparation and/or difficult placement</li>
<li>20 or more illustrations</li>
<li><a href="#mult">multiple languages</a></li>
<li>Extensive Spellcheck/Gutcheck</li>
<li>Engli&#383;h</li>
<li>musical notation and files</li>
<li>extensive mathematical or chemical notation</li>
</ul>


<h4><a name="mult"></a>How to define multiple languages:</h4>
<ul>
<li>If the book is English on one page and Latin on the facing page, it counts as multiple
languages.</li>
<li>If the author is traveling and repeatedly reports conversations in the
foreign language of the country, it counts as multiple languages.</li>
<li>If extensive (several long paragraphs or more) quotations in a language
other than the base language are present, it counts as multiple
languages.</li>
<li>If the Frenchman in the novel says &quot;Zut!&quot; a lot, it does NOT
count as multiple languages.</li>
</ul>

<h3>Determining Allowable Errors for Various Ratings</h3>
<p><b><font color="maroon">The PPV Summary Form will calculate the rating based on the difficulty
of the project, file size, and the number and type of errors you record.</font></b>
If you like, you can also print
a copy of the form and jot down notes on it before
entering the information into the online form.</p>

<p>File size referred to below is based <b><font color="maroon">on the plain
text version</font></b>. It is easy to check the size of the text file in
kilobytes by looking at the files using your file manager. We use
only the text &ndash; not the HTML &ndash; file for this
purpose.</p>

<p>Errors such as failure to grasp the italics
guidelines is counted as one error, not one error each time italics
are wrongly handled. Errors such as he/be errors are each counted as
individual errors (e.g., 3 &ldquo;he&rdquo; for &ldquo;be&rdquo;
count as three errors). </p>

<p>If the PPer is asked to resubmit a corrected file,
then any errors not corrected or new errors introduced are added to
the total number of errors for rating purposes.</p>

<h4>Excellent</h4>
<p>There should be no Level 2 errors</p>
<table width="80%" align="center" border="1">
<tr align="left">
<th>If project is:</th><th>Errors</th>
</tr>
<tr valign="top">
<td><b>Easy</b></td>
<td>Maximum of one Level 1 error per 300kb, (or no more than 1 error if file
size &lt; 300kb)</td>
</tr>
<tr valign="top">
<td><b>Average</b></td>
<td>Maximum of one Level 1 error per 200kb, (or no more than 1 error if file
size &lt; 200kb)</td>
</tr>
<tr valign="top">
<td><b>Difficult</b></td>
<td>Maximum of one Level 1
error per 100kb, (or no more than 1 error if file size &lt; 100kb)</td>
</tr>
</table>

<h4>Very Good</h4>
<p>There should be no Level 2 errors</p>
<table width="80%" align="center" border="1">
<tr align="left">
<th>If project is:</th><th>Errors</th>
</tr>
<tr valign="top">
<td><b>Easy</b></td>
<td>Maximum of one Level 1 error per 150kb, (or no more than 1 error if file
size &lt; 150kb)</td>
</tr>

<tr valign="top">
<td><b>Average</b></td>
<td>Maximum of one Level 1 error per 100kb, (or no more than 1 error if file
size &lt; 100kb)</td>
</tr>

<tr valign="top">
<td><b>Difficult</b></td>
<td>Maximum of one Level 1 error per 50kb, (or no more than 1 error if file
size &lt; 50kb)</td>
</tr>
</table>

<h4>Good</h4>
<p>May contain no more than 5 Level 2 errors </p>
<table width="80%" align="center" border="1">
<tr align="left">
<th>If project is:</th><th>Errors</th>
</tr>
<tr valign="top">
<td><b>Easy</b></td>
<td>Maximum of 6  Level 1 error per 150kb, (or  no more than 6 Level 1 errors
if file size &lt; 150kb)</td>
</tr>

<tr valign="top">
<td><b>Average</b></td>
<td>Maximum of 6  Level 1 error per 100kb, (or  nor more than 6 Level 1 errors
if file size &lt; 100kb)</td>
</tr>
<tr valign="top">
<td><b>Difficult</b></td>
<td>Maximum of 6 Level 1 error per 50kb, (or no more than 6 Level 1 errors if
file size &lt; 50kb)</td>
</tr>
</table>

<h4>Fair</h4>
<p>A Fair rating is assigned if the project contains too many errors to be assigned a rating of Good. Essentially, if a
project does not qualify as Excellent, Very Good, or Good, it is
Fair.</p>

<a name="late"></a><h2>Late PPV Summaries</h2>

<p>Occasionally a project is marked as posted
and the link to the PPV form disappears from the Project Page
before the PPVer can complete the form.
In this situation, a PPVer can also access the form by entering the Project ID into a
<a href="<?php echo $code_url; ?>/tools/post_proofers/ppv_report.php">blank PPV Report</a>.
There is also a link to this blank form on the PPV page.</p>

</div>
<!--Text End Here--> 
<?php
