<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN" "http://www.w3.org/TR/REC-html40/loose.dtd">
<html>
<head>
<title>Post-Proofing FAQ</title>
</head>

<body>
<h1>Post-Proofing FAQ</h1>
<h5>(Version 2.1; last updated April 8, 2003)</h5>
<br>
<h3>Getting Started</h3>

<a href="#Tools">Tools</a><br>
<a href="#Selecting">Selecting a Book</a><br>
<a href="#Walkthrough">Walkthrough</a><br>
	<ul>
	<li><a href="#1-5">1-5. Choose a book and prepare it for proofing.</a>
	<li><a href="#6">6. Format the title page.</a>
	<li><a href="#7">7. Scan the book and correct obvious errors.</a>
	<li><a href="#8">8. Spell check.</a>
	<li><a href="#9">9. Search for -. Perform any other "paranoid "checks".</a>
	<li><a href="#10">10. Search for *.</a>
	<li><a href="#11">11. Format the book.</a>
	<li><a href="#12">12. Run Gutcheck.</a>
	<li><a href="#13">13. Remove page markers.</a>
	<li><a href="#14">14. Rewrap lines.</a>
	<li><a href="#15-17">15-17. Search for hyphens adjacent to spaces, 
 double spaces, and spaces at the end of lines.</a>
	<li><a href="#18">18. Replace all HTML elements.</a>
	<li><a href="#19">19. Look the book over again.</a>
	<li><a href="#20">20. Upload book for verification.</a>
	</ul>
<br>
<h3>Software</h3>

<a href="#Gutcheck">Gutcheck</a><br>
<a href="#PRTK">PRTK</a><br>
<a href="#Editors">Text Editors</a><br>
<a href="#Images">Images</a><br>
<a href="#Macros">Macros</a><br>
<br>
<h3>Advanced Proofing Questions</h3>

<a href="#Footnotes">Footnotes</a><br>
<a href="#Illustrations">Illustrations</a><br>
<a href="#Sidenotes">Sidenotes</a><br>
<a href="#Poetry">Poetry</a><br>
<a href="#Indexes">Indexes</a><br>
<a href="#Tables">Tables</a><br>
<a href="#Non-ASCII">Non-ASCII Characters</a><br>
<a href="#Symbols">Non-Latin Scripts and Unusual Symbols</a><br>
<a href="#Headings">Headings and Subheadings</a><br>
<a href="#Bonus">Paranoid Proofing Checks</a><br>
<a href="#Returning">Returning a Project</a><br>
<a href="#Formats">Non-ASCII Format
 s</a><br>
<a href="#Missing">Missing or Problem Images</a><br>
<a href="#Other">Other</a><br>
<br>
<hr>

<p>This FAQ is designed as a guide for post-proofers, especially new ones.  I hope that it makes the task of post-proofing a little less frightening!</p>

<hr>

<h2>Getting Started</h2>
<br>
<p>This section is designed especially for the first-time post-proofer.</p>
<br>
<h4><a name="Tools">What software will I need to get started?</a></h4>

<p>The requirements for software are minimal:</p>
	<ul>
	<li>a <a href="#Editors">text editor</a>, 
	<li>a program capable of opening <a href="#Images">images</a>, 
	<li>a spell checker, and 
	<li><a href="#Gutcheck">Gutcheck</a>.
	</ul>

<p>There are other useful <a name="#Extras">programs</a> available which are nice to have, but not essential.</p>
<br>
<h4><a name="Selecting">All right. I have everything I need to get started except a book.  Which one should I choose?</a></h4>

<p>For your first project, it's a good idea to pick a 
 fiction book with a relatively small number of pages.  Here's why:</p>
	<ul>
	<li>fiction usually has fewer words per page than non-fiction and more a streamlined format, which means that it scans clearer and is unlikely to be riddled with OCR errors and inconsistent formatting;
	<li>fiction generally lacks footnotes, tables, and other items which could be difficult for a new post-proofer to deal with; and
	<li>a low page count makes the work go faster and is easier to handle.
	</ul>

<p>Take a look at the Post-Processing section of your Personal Page to see all of the works available for post-proofing.  Pick one with a title that is likely to be fiction.  "The Boy Scout Camera Club" is a good bet; "Copyright Renewals" is not.  Download its text by going to its Book Options scrollbar and selecting "Download Zipped Text".</p>

<p>Scroll through the <i>whole</i> text to see if there are any difficulties, like footnotes, poetry, foreign languages, dialects, and tables in it.  T
 his way, you will know what you will be dealing with before you commit to the project.  If you see any of these items, you would be wise to choose a different project.  But, if you think you can handle it, give it a try!</p>

<p>Check the <a href="../phpBB2/viewforum.php?f=2">Project Questions and Comments Forum</a> for your book's title to see what proofers have been saying about it.  Again, this might alert you of issues which might make the work more difficult than you had realized.</p>

<p>When you have found a work that you want to do, check it out by going to its Book Options scrollbar and selecting Check Out Book.  The book is now yours!</p>
<br>
<h4><a name="Walkthrough">Great!  Now what am I supposed to <i>do</i>?</a></h4>

<p>There is no one way to post-process books.  Every post-proofer has a different technique and uses different tools.  The technique described below is the one the author personally uses, but there are many ways of achi
 eving the same end result.  In time, you will probably develop your own technique.</p>

<p>This walkthrough is a very hands-on way of post-proofing, which is why it is recommended for first-time post-proofers.  It takes longer to complete the book than if macros and global find-and-replace are used, but it gives the first timer a feel for what they should look out for by making the proofer scroll through the book several times.  Once you get the hang of what post-proofing is all about, feel free to use macros and such if you wish.</p>
<br>
<h4>The Walkthrough</h4>

<ol>
<li><a name="1-5">Choose a book and sign it out.</a><br>

<li>Look for comments and questions proofers had while proofing your book in the <a href="../phpBB2/viewforum.php?f=2">Project Questions and Comments Forum</a>.<br>

<li>Download the book's images (optional) and text.<br>

<li>If you are using a Mac, change the character set to Mac. In BBEdit Lite, use MIDex to do this by cli
 cking on the ISO-&gt;Mac button.  You will need to convert the book back to ISO when it is complete.<br>

<li>If the document is not displayed in a monospaced font, make this adjustment now.  Also, if you are not saving the document as plain text, but as a Word document or some other file type, make sure that the font size is 10 point.<br>

<li><a name="6">Format the first page(s) to your liking.  These pages contain the title of the book, the author's name, and occasionally information on a translator, etc.  Delete any copyright or publication information, and space the rest in a way that is pleasing to your eye. If this section is in ALL CAPS, you can change it to Capitalized Text if you wish.</a><br>

<li><a name="7">Scan the entire book for problems and formatting issues.  You will almost always run across things which obviously <i>just aren't right</i> which can be corrected at this time. There are many other things that you can look for during this first pass.  Here ar
 e a few things to watch for:</a>
	<ul>
	<li>poetry, tables, and other areas which <b>must not</b> be re-wrapped (ie. where line breaks are important);
	<li>punctuation in odd places (usually from specks on the scanned image) and dubious spellings;
	<li>lines of asterisks:  if you find one, it may be worth looking through the images to make sure that none were missed.  Also, make sure that they are correctly written (7 spaces, then 5 asterisks separated by 7 spaces);
	<li>extra line breaks within paragraphs that clearly shouldn't be there, or lack of space between paragraphs;
	<li>bad spacing:  double spaces, indented paragraphs, wordsthatruntogether;
	<li>words in ALL CAPS at the beginning of paragraphs
	<li>words that are italicized everywhere, like ships' names (watch for non-italicized occurrences);
	<li>words containing an accent (watch for the loss of the accent);
	<li>hyphenation: em-dashes should be marked --, and hyphens -, with no spaces on either side.  If it's cle
 arly wrong, fix it;
	<li>missing pages:  check the image numbers to make sure that there are none missing, and read the last few words on each page and the first few words on the next page to make sure that they go together;
	<li>pages which look like they haven't been proofed at all: you'll have to look carefully for errors in these areas; and
	<li>words in all caps at the beginning of paragraphs.
	</ul>
	
<p>Some pages will begin with a full sentence. Since you won't know if these sentences are the beginning of a new paragraph or a continuation of the previous one, either mark these with a * and check them later, or check them against the book's images as you come across them and format accordingly.</p>

<p>If you wish, you may remove the page markers on the way through.  Most people remove them at this stage, though some leave them in to make checking the text against the images easier.  If you choose to remove them, make an unedited copy of the file to give you a referen
 ce with the page numbers still included.</p>

<li><a name="8">Run a spell check.  Mark any words that are even remotely questionable with a *, or check each questionable word against the book's images as you go and correct when necessary.  Books of PG vintage, especially books of poetry, may contain archaic and unusual spellings and words.</a><br>

<li><a name="9">Search for -.</a>  If you run across something bizarre, mark it with a * or check it against the images.  The purpose of this step is essentially to make sure that there are no em-dashes in the middle of words, no single dashes that should be em-dashes, and that all end-of-line hyphenation is fixed. <a href="#PRTK">PRTK</a> has a Dash Check feature which will list all words containing dashes so that you can look for oddities with ease.

<p>Some proofers like to replace all hyphens with unbreaking hyphens in order to minimize the work in browsing for hyphenated words split between lines at the end.  In Microsoft Wor
 d, the unbreaking hyphen character can be inserted by doing a global find-and-replace of - into ^~.  If you choose to do this, you must change all unbreaking hyphens back to regular hyphens at the end, as they are non-ASCII and do not display properly on all machines.

<p>There are many other <a href="#Bonus">"paranoid checks"</a> that proofers perform at this stage.  These checks are particularly useful when the book scanned badly or the font was unusual, since these conditions introduce a lot of errors that are difficult for proofers to spot.  If this is your first book, you don't need to worry too much about these "bonus" checks, but once you learn the basics of proofing, you will want to be sure that you do a thorough job and catch as many mistakes as you can, and these extra checks will help you produce high quality etexts. 
 
<li><a name="10">Search for *.  In addition to the asterisks that you may have inserted to mark potential problems, proofers may have used them t
 o mark problems that they had and bring them to your attention. If you have left in the page markers, use them to guide you to the image containing the text that you are checking.</a><br>

<li><a name="11">Format chapter headings and any subheadings that you may find in the text.  It is recommended that there be three blank lines between chapters, two after the chapter title, and four between larger sections (eg. Part 1 and 2), but the exact numbers are not very important.  As long as you are consistent within your work, use whatever spacing seems appropriate and looks good to you.</a>

<p>Scan the book and make sure that all of the chapter headings and subheadings are there!  Proofers sometimes remove these accidentally.  If there is a table of contents, use it as a checklist; otherwise, refer to the images.  If the chapters are simply numbered Chapter 1, 2, etc., just make sure that there are no numbers missing.</p>

<li><a name="12">Run Gutcheck.  Check every potential pr
 oblem that it brings to your attention.</a><br>

<li><a name="13">Remove page markers, either manually or with a tool or search.</a><br>

<li><a name="14">Time to rewrap.  Did you see any poetry, tables, etc.?  If not, rewrapping the lines should be easy.  You will need to rewrap the lines to between 65 and 75 characters in length.  Each program has a different way of doing this, and you will have to find the way that works best for you.  In BBEdit Lite, select Hard Wrap from the Text menu.  For MS Word, save as Text with Line Breaks.  If worst comes to worst and you cannot find an easy way to rewrap the lines, find and replace all line breaks with spaces, count any line to find to see approximately where 65-75 characters falls, and insert lines breaks manually at this point.  It's painful, but it works.  Be grateful that you chose a book with a low page count!</a>

<p>If you have areas that you must not wrap, you must be more careful.  In BBEdit, it is possible to rewrap a 
 section by highlighting it and selecting Hard Wrap.  This allows you to rewrap the text in blocks between tables or poems.  Other programs may have a similar feature.  If you cannot find an easy way to rewrap around poems, there is a process which you can try <a href="rewrap.html">here</a>.</p>

<li><a name="15-17">Search for space-hyphen and hyphen-space and replace each instance with hyphen.  The exception here is hyphens replacing a word, like a person's name; in that case, leave spaces before and after the hyphens to indicate this.  There may be other exceptions like this as well, but you'll be able to identify them from their context.</a><br>

<li>Search for double space and replace with single space.  DO NOT do a global find and replace if your text contained poetry, charts, or lines of asterisks, all of which legitimately contain multiple spaces.<br>

<li>Remove end-of-line spaces by searching for spaces followed by returns and replacing them with returns only.  Again
 , don't do a global replace.  <a href="#PRTK">PRTK</a> has a feature which will remove end-of-line spaces automatically.<br>

<li><a name="18">Find and replace all incidences of &lt;i&gt; and &lt;/i&gt; with _.  Make sure that the same number of &lt;i:gt; were replaced as &lt;/i&gt;.  If there were more or less, some of the tags may not have been correctly typed, and you'll have to track them down.  Try searching for &gt;, &lt;, and / to find them more quickly.</a><br>

<li><a name="19">Give the whole thing a quick eyeball to make sure that all is well.</a><br>

<li><a name="20">Zip the finished product and upload it to the site.  Go to you Post-Processing page, and select Upload for Verification from the project's drop-down menu.  After your first few texts, they will also explain how to create two separate versions:  an ASCII version for posting and an archive version for, well, archiving.</a><br>

<li>Relax.  You're done!
</ol>

<hr>

<h2>Software</h2>
<br>
<h4><a name="G
 utcheck">What is Gutcheck, and how do I use it?</a></h4>

<p>Gutcheck is a nifty piece of software created by Jim Tinsley for people working on Project Gutenberg etexts.  It checks for errors which are common, but not easy to spot, like mismatched quotes, short lines, etc.</p>

<p>Gutcheck is currently being produced for Windows and *nix systems, and can be found <a href="http://gutcheck.sourceforge.net">here</a>.  A quick-and-dirty Mac build can be downloaded <a href="gutcheck-0-95.sit">here</a>.  A few kindly individuals have also constructed web interfaces for Gutcheck (<a href="http://caw.homelinux.net/gut/gutcheck.php">here</a> and <a href="http://inkwina.homeip.net/~phsi/gutcheck/gutcheck.php">here</a>) if you cannot run any of the builds available.  You could also simply ask another post-proofer to run Gutcheck for you if you have trouble with it, and they can send you a list of results.</p>
<br>
<h3><a name="Extras">Other Useful Software...</a></h3>
<br>
<h4><a name=
 "PRTK">PRTK</a></h4>

<p>PRTK (Proofreader's Toolkit) removes the page markers for you so that you needn't do it manually.  It may do other things as well, but I have never used it (help, please!).  Though it contains Gutcheck, the version bundled with it is very old, and it is recommended that you download the latest version.  PRTK is available <a href="http://texts01.archive.org/%7Echarlz/public/PRTK-1-0-134.zip">here</a>, and it runs only on the Windows platform.</p>
<br>
<h4><a name="Editors">Text Editors</a></h4>

<p>Any text editor can be used for post-proofing, but some have tools which make them more suitable than others for the job.  <a href="http://www.barebones.com/products/bblite/index.shtml">BBEdit Lite</a> is an excellent choice for Mac users.  Be sure to download the <a href="http://ftp.barebones.com//pub/third-party-plugins/MIDex_1.4.2.hqx">MIDex</a> plugin as well. Many *nix users use emacs, which can be downloaded <a href="http://ftp.gnu.org/pub/gnu/emacs/"
 >here</a>.  It is also available for <a href="http://www.emacs.mirkolinkonline.de/install_en.html">Windows</a>.  If you use Microsoft Word for any platform, you will be able to run a useful <a href="#Macros">macro</a>.</p>
<br>
<h4><a name="Images">Images</a></h4>

<p>You will need to be able to look at your book's scanned images at some point in the post-proofing process.  You can either download them and use a third-party program to view them, or view them online through a browser.  Any program that will display images will do.</p>

<p>Some people have recommended utilities which allow you to see thumbnails of images without opening them, making it easier to find the one you're looking for.  <a href="http://www.lemkesoft.de/gcdownload_us.html">GraphicConverter</a> is one such program for Macintosh machines.  <a href="http://www.irfanview.com/english.htm">Irfanview32</a> is a quality Windows image manipulation program.  <a href="http://www.xnview.com/">Xnview</a> runs on Wi
 ndows, *nix, and a host of smaller operating systems.</p>
<br>
<h4><a name="Macros">Macros</a></h4>

<p>Macros will perform certain repetitive post-proofing tasks for you to save time.</p>

<p>There is a macro available for <a href="Wordmacro.html">Microsoft Word</a>, which rewraps text while preserving paragraph breaks.  It may also replace double spaces with single ones and adjust the margins to less than 75 characters (I am less sure about these functions).  Please note that this macro will not work in Word 97 or older versions.</p>

<p>Naomi Parkhurst has written an <a href="BBEditscript.sit">Applescript</a> macro, which rewraps the text (including around poetry) and removes superfluous spaces.  It works for BBEdit (but not BBEdit Lite).</p>

<p>Garweyne has written a <a href="http://www.dm.unipi.it/~traverso/Ebooks/Lsp/footnotes.lsp">lisp script</a> to aid post-proofers wishing to rearrange footnotes. A more elaborate description of the
  script's abilites is available at the link above.</p>

<hr>

<h2>Advanced Proofing Questions</h2>
<br>
<p>This section contains information on the more complex aspects of post-proofing.  Most of these issues are also treated in the <a href="document.html">Document Guidelines</a> if you should need information on proper markup.</p>
<br>
<h3><a name="Footnotes">Footnotes</a></h3>
<br>
<h4>Should I leave footnotes inline, or move them elsewhere?</h4>

<p>This depends a great deal on the length of the footnotes, their frequency, and on the type of text that you are proofing.</p>

<p>It is suggested that you should leave the footnotes where they occur in the text if they are short and relatively uncommon.  In these cases, the footnotes won't disrupt the flow of the text very much.</p>

<p>If the footnotes in your text are long, numerous, or are primarily bibliographic references, you might be wise to move them to the end of the paragraph where t
 hey occur, on their own line, and just leave a marker (ex. [2]) in the paragraph.</p>

<p>If the footnotes are so common that even this would be highly disruptive to the reader, consider collecting them all and moving them to the end of the chapter.  This is a bit of a pain, but it makes such works infinitely more readable.  Mark the endnote in the text as you would an ordinary footnote, fix all of the numbering, and list the endnotes at the end of the chapter with the new numbering.</p>

<p>Footnotes in poetry are a special case worth mentioning.  Most post-proofers agree that the footnotes should not be simply inserted into the text where they occur, as this interferes with the rhythm of the poetry.  Mark the place referenced by the footnote, then move the footnote to its own line or to the end of the poem, whichever seems most appropriate.</p>

<p>Whatever format you choose, make sure that it is consistent throughout the text.</p>
<br>
<h3><a name="Illustrations">Illustra
 tions</a></h3>
<br>
<h4>Should I leave in all of those [Illustration] tags?  What about the ones with captions?</h4>

<p>[Illustration] tags with no caption can be safely removed.  However, do not do this until the end, when the ASCII version is produced.  Leave them in the archive version of the text.  That way, if someone decides to make an HTML version in the future, the tags will be there and it will be easier to correctly place the images.</p>

<p>[Illustration] tags with captions should be left in place for the reader to enjoy.</p>
<br>
<h4>My text will make no sense if the actual illustrations aren't included.</h4>

<p>If the text was scanned with the intention of just converting it to ASCII, email the Project Manager and ask for advice.  If you are willing and able and the scans are available, they may ask you to do an HTML version and provide good quality scans for the missing images.  Alternately, they may decide that the project doesn't really need the images and 
 explain their opinion.</p>

<p>If the text really should be produced in a version that will allow future readers to view the images, and you are unable or unwilling to do the work to put it in such a version, return it and post your concerns in the <a href="../phpBB2/viewforum.php?f=3">Post-Processing Forum</a>.</p>
<br>
<h3><a name="Sidenotes">Sidenotes</a></h3>
<br>
<h4>These [Sidenote] tags seem redundant!</h4>

<p>In most cases, sidenotes add a bit of summary or description to the text, but, in very rare cases, the sidenotes add nothing to the book and will be an annoyance rather than a help.  If your book fits this mold, consider leaving out the sidenotes.  BUT, think long and hard about this, as it is altering the text of the original.  Email the Project Manager and/or post in the <a href="../phpBB2/viewforum.php?f=3">Forums</a> for a second opinion before taking this step.</p>
<br>
<h3><a name="Poetry">Poetry</a></
 h3>
<br>
<h4>My book contains a few verses of poetry.  How should I format them?</h4>

<p>The proofers should have surrounded any verses of poetry with the markers /* and */.  Remove the markers, and check against the original image to make sure that the formatting is correct.  A quick scan should reveal if the spacing in your text and the original match.</p>

<p>Make sure that the indentation is consistent, at least within each poem.  It's entirely possible that one proofer indented some lines four spaces, while  the proofer who got the next page indented five, when the image shows the same amount of indentation.</p>

<p>DO NOT WRAP LINES.  You will have to take special care when you rewrap the text not to rewrap your poetry.  However, if a line is broken in two due to its length, but it was not intended to be (the second line of these is usually highly indented), join the two parts of the line together.  If they still don't fit on one line, break them and indent the second
  half heavily.</p>
<br>
<h4>My book <i>is</i> poetry.  How do I format it?</h4>

<p>Aside from the fact that poems won't be marked by poetry markers, the book should be formatted as for occasional verses (check the indentation, etc.)  Also, the poem(s) need not be indented two spaces by default, as they are the main text, and don't need to stand out from it.</p>

<p>Books which are entirely comprised of poetry do not need to have their lines rewrapped (watch the Gutcheck output for overly long lines, though).  However, the book may contain an introduction or other prose section which will need rewrapping.</p>

<p><a href="../phpBB2/privmsg.php?mode=post&amp;u=3561">bconstan</a> has graciously offered to aid anyone who needs extra help post-proofing poetry books.  If you have any questions, send her a message.</p>
<br>
<h3><a name="Indexes">Indexes</a></h3>
<br>
<h4>Is there anything special that I should know about formatting indexes?</h4>

<p>Pay 
 attention to the presence/absence of trailing commas and semicolons.  You may either leave these in or remove them, as long as you are consistent.</p>

<p>DO NOT REWRAP LINES.  Unless you have places a blank line between each and every entry, rewrapping will destroy the format of the index.  Be careful!</p>
 <br>
<h3><a name="Tables">Tables</a></h3>
<br>
<h4>What do I do with tables?</h4>

<p>Tables should have been marked with /* */ by the proofers, but a quick scan of the book should turn any up even if they are unmarked, as they are quite conspicuous.</p>

<p>You will have to move the text in the table around to make it as readable as possible.  If the headings are broken between lines, put them on the same line if you can.  Adjust the spacing of the columns so that they look good on the screen and aren't too close together.  Make all of the column entries line up.  If you are lucky, this formatting was already done for you, but not all proofers can format tables accurate
 ly (if their display font isn't monospace, for example).  Watch for tables that span multiple pages, as they will be unlikely to have similar formatting. "Related" tables should be formatted consistently, if possible.</p>

<p>DO NOT REWRAP LINES!  You don't want to destroy all of your hard work, now do you?</p>
<br>
<h4>The table will not fit into the lines widths allowed by PG.</h4>

<p>You have a couple of options here:</p>

<ol>
<li>Try your best.  You may have to split the chart into multiple rows.  Or you may come up with your own way to format the information in the troublesome chart.  Be inventive.
<li>Give up.  Mark the chart up as an [Illustration], use the title as the caption, and write it off.  It's not ideal, but sometimes it's the only way.
</ol>

<h3><a name="Non-ASCII">Non-ASCII Characters</a></h3>
<br>
<h4>My text has accents, pound signs, or other non-ASCII characters in it.  Should I preserve them in the final version?</h4>

<p>In general, yes.  Keep all o
 f the accented words (or symbols) as they are.  An 8-bit ASCII file can be made which preserves them.</p>

<p>Some English-language texts contain just the occasional word with an accent in it.  If the accents are rare and don't add any meaning to the text, take them out when producing the ASCII edition, but leave them in the archive version.</p>
<br>
<h3><a name="Symbols">Non-Latin Scripts and Unusual Symbols</a></h3>
<br>
<h4>How do I handle footnotes, etc. in Greek, Russian, or other texts with a non-Latin alphabet?</h4>

<p>If possible, the text should be transcribed into the Latin alphabet.  It's not a lossless process, but it's the only way to preserve these snippets in ASCII.  Information of the transcription process is available in the <a href="document.html">Document Guidelines</a>.</p>

<p>Many languages, like Arabic and Hebrew, are difficult to transcribe without an intimate knowledge of the language.  If your text contains snippet
 s of such a language and you don't have the knowledge to transcribe it yourself, try posting in the <a href="../phpBB2/viewforum.php?f=3">Forums</a> to find someone to team up with for transcription.</p>

<p>If you cannot transcribe the language, and you can't find anyone else who is capable of doing it for you, mark its presence with [Arabic] (for Arabic), and delete any OCR garbage that may have been left in the text.  It's too bad that the information will be lost, but you've done the best you could.</p>
<br>
<h4>My text has weird symbols in it (Zodiac signs, medical abbreviations, etc.).  How do I mark these up?</h4>

<p>If you are lucky, the proofer will have done some research and found the meaning of the symbol for you.  However, often the proofer will mark the symbol with a * and leave you with the legwork.

<p>If know what the symbol represents, write out its meaning, eg. [Symbol: Jupiter].  Do not try to replicate the symbol itself in ASC
 II.</p>

<p>If you have never run across the symbol before, here are a few web pages provided by proofers to help you:<br>

<a href="http://www.lib.umich.edu/eebo/docs/dox/medical.html">Apothecaries'/Medical</a><br>
<a href="http://www.lib.umich.edu/eebo/docs/dox/alchem.html">Alchemical</a><br>
<a href="http://www.lib.umich.edu/eebo/docs/dox/moresyms.html">Astronomical</a><br>
<a href="http://www.lib.umich.edu/eebo/docs/dox/latabbrs.html">Latin Abbreviations</a><br>
<a href="http://www.symbols.com/graphicsearch.html">Graphic Search for Symbols</a>
</p>

<p>This section has room to grow.  If you find any other good links, please <a href="../phpBB2/privmsg.php?mode=post&amp;u=1674">PM me</a>.</p>

<p>Note that some symbols may have more than one meaning.  If this is the case, try to determine the best meaning from the context of the symbol.</p>
<br>
<h3><a name="Headings">Headings and Subheadings</a></h3>
<br>
<h4>How do I differentiate subheadings f
 rom headings?</h4>

<p>Usually, the easiest way to differentiate between subheadings and headings is to change the line spacing (ex. leave three lines blank when a new heading begins, and only one for a new subheading).  However, some texts may have more than one layer of subheading.  In these cases, you will have to devise a markup which is appropriate to the text.  You could indent subheadings a certain number of spaces depending on their "layer", for example.</p>
<br>
<h3><a name="Bonus">Paranoid Proofing Checks</a></h3>
<br>
<h4>I want to make sure that I do a really good job post-processing my book.  Are there any common errors that often make it through the checking system?</h4>

<p>Yes, there are a few kinds of errors which often make it through both rounds of proofing and into the final etext.  These errors fit into three categories: specks that introduce punctuation, errors introduced by the tags used in proofing, and "scannos" that can make it through a spell check
 .</p>

<p>To check for random punctuation caused by specks on the image that was OCRed, search for the following things:</p>
	<ul>
	<li>, or . followed immediately by a letter,
	<li>. followed by a space and a lowercase letter,
	<li>, followed by a space and an uppercase letter,
	<li>/ and /', which often occur instead of ," and .",
	<li>.' for ." (reverse these if your book uses single quotes as double quotes,
	<li>{ and } instead of [ and ],
	<li>standalone ' followed by a hard return, and
	<li>standalone symbols, like &amp;, $, ^, =, \, /, Ç, È, @, ~, `, #, %, =, +, and |, which can creep in.
	</ul>

<p>A few errors are introduced by the HTML and other proofing markup that DP uses for proofing.  To eliminate these, do the following:</p>
	<ul>
	<li>before deleting the HTML elements, search for &lt;i&gt; followed by a space, and &lt;/i&gt; preceded by a space,
	<li>[ and ], to make sure that all [Footnote] and [Illustration] tags are properly formatted, and
	<li>after repla
 ced the HTML elements, search for &gt;, &lt;, and / to make sure that all of the tags have been replaced.
	</ul>

<p>There a myriad errors which will make it through a spell checker.  If you would like to avoid tedious find-and-replaces, you could remove these words from your program's spell checker.  Only a few of the most common scannos will be listed here.</p>
	<ul>
	<li>standalone 1 and O, which sometimes replace I and O,
	<li>arid, for and,
	<li>arc, for are,
	<li>m, for in,
	<li>yon, for you,
	<li>modem, for modern,
	<li>loth, for 10th,
	<li>hut, for but,
	<li>clay, for day,
	<li>wen, for well,
	<li>ail, for all,
	<li>bo, for be,
	<li>ho, for he,
	<li>lie, for he and the,
	<li>Alien, for Allen,
	<li>coining, for coming,
	<li>bad, for had,
	<li>tiling, for thing,
	<li>docs, for does,
	<li>riot, for not,
	<li>tho, for the,
	<li>tum, for turn,
	<li>cur, for our,
	<li>ringer, for finger,
	<li>mined, for ruined,
	<li>carnage, for carriage,
	<li>carne, for came,
	<li>tile, f
 or the,
	<li>bat, for but,
	<li>comer, for corner,
	<li>44 and 11, for ",
	<li>Borne, for Rome,
	<li>ease, for case,
	<li>lime, for time,
	<li>Spam, for Spain,
	<li>tram, for train,
	<li>gram, for grain,
	<li>guru, for gun,
	<li>vas, for was,
	<li>bum, for burn,
	<li>j, for ;,
	<li>tie, for the,
	<li>gaming, for gaining,
	<li>art, for act,
	<li>ray, for my,
	<li>eve(s), for eye(s),
	<li>car, for ear, and
	<li>cat, for eat.
	</ul>
	
<p>A more complete, and constantly growing list is maintained by <a href="../phpBB2/privmsg.php?mode=post&amp;u=6141">big_bill</a>.  If you have found a "stealth scanno" which isn't on his lists, send it to him.</p>

<p>The latest version (presently 1.1) of big_bill's lists can be found here:</p>
<a href="stealth_scannos_eng_common.txt">Common English Scannos</a><br>
<a href="stealth_scannos_eng_rare.txt">Rare English Scannos</a><br>
<a href="stealth_scannos_eng_suspect.txt">Theoretical (But as yet Unwitnessed) English Scannos</a><br>
<a href="stealth_scannos_fr_common.txt">Common French Scannos</a><br>
<a href="stealth_scannos_fr_rare.txt">Rare French Scannos</a><br>

<p>The lists are plain ASCII text, and could also be used by an adventurous programmer to check for common letter shifts (ex. h -&gt; b) and such.</p>
<br>
<h3><a name="Returning">Returning a Project</a></h3>
<br>
<h4>This project is too hard, or I don't have time for it, or I just don't want to do it any more!  How do I get rid of it!</h4>

<p>To dispose of your project and return it to the pool for another post-proofer, go to your Post-Proofing page, find the title of the book which you are returning, and select Return to Available from its drop-down menu.</p>
<br>
<h3><a name="Formats">Non-ASCII Formats</a></h3>
<br>
<h4>I want to do something special with
  this text.  Can I make a version of the text in HTML/XML/etc.?</h4>

<p>Yes!  Feel free to make non-ASCII versions if you wish.  As long as you also produce an ASCII version, PG will be glad to accept any other version that you may produce.</p>
<br>
<h3><a name="Missing">Missing or Problem Images</a></h3>
<br>
<h4>There's a page missing from the scans, or some words/pages are blurred/chopped off, etc.</h4>

<p>Try emailing the Project Manager.  If they still have the text, they may be able to clarify blurred or missing words, or give you a scan for a missing page.</p>

<p>If they don't have the text, join <a href="http://www.promo.net/pg/subs.html">gutvot-d</a> and post a message asking for help.  Give the name and author of the book which you are working on, what you will require as help (usually looking at a paper book to clarify a few words), and how much work there will be (are only a few lines cut off, or are there whole pages missing?).  These volunteers will reply to
  you if they have access to your book.  You should then send them the text with comments so that they can find the damaged portions, correct them, and send them back to you.  You could also do this process yourself if the book exists in your local library.</p>
<br>
<h3><a name="Other">Other</a></h3>
<br>
<h4>I have a question which isn't in this FAQ.</h4>

<p>Post-proofing involves common sense and personal judgement.  The only solid rule is for the post-proofer to preserve the author's intention to the best of their ability.  There can be more than one way to handle a particular piece of formatting, and all of them can be right.  You, as post-proofer, have a great deal of freedom to decide how to handle particular formatting issues and make global format changes.</p>

<p>If your common sense and personal judgement aren't helping you solve some particular problem, post your question in the <a href="../phpBB2/viewforum.php?f=3">Forums</a>.  Other po
 st-processors can then tell you how they would handle the situation.  Their suggestions might give you a logical answer for your text, or inspire your own idea as to how to handle the issue.</p>

<p>If you think that something should be added to the FAQ, <a href="../phpBB2/privmsg.php?mode=post&amp;u=1674">PM me</a>.  If it's a general or common enough question, I'll add it.</p>
</body>
</html>
