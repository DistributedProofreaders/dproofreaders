<?
$relPath='../pinc/';
include($relPath.'v_site.inc');
include($relPath.'connect.inc');
include($relPath.'theme.inc');
new dbConnect();
$no_stats=1;
theme('Post-Proofing FAQ','header');
?>

<h1>Post-Proofing FAQ</h1> 
<h5>(Version 3.5; last updated September 12, 2003)</h5> 
<br> 
<h3>Getting Started</h3> 
 
<a href="#Introduction">What is Post-Proofing?</a><br>
<a href="#Tools">Tools</a><br> 
<a href="#Selecting">Selecting a Book</a><br>
<a href="#Time">How Long Do Etexts Take?</a><br>
<a href="#Walkthrough">Walkthrough</a><br> 
	<ul> 
	<li><a href="#1-5">1-5. Choose a book and prepare it for proofing.</a>
	<li><a href="#6">6. Format the title page.</a> 
	<li><a href="#7">7. Scan the book and correct obvious errors.</a>
	<li><a href="#8">8. Spell check.</a> 
	<li><a href="#9">9. Search for -. Perform any other "paranoid "checks" 
                            (ex. searching for Stealth Scannos).</a> 
	<li><a href="#10">10. Search for *.</a> 
	<li><a href="#11">11. Format the book.</a> 
	<li><a href="#12">12. Run Gutcheck.</a>  
	<li><a href="#13">13. Remove page markers.</a> 
	<li><a href="#14">14. Rewrap lines.</a> 
	<li><a href="#15-17">15-17. Search for hyphens adjacent to spaces, 
                             double spaces, and spaces at the end of lines.</a>
	<li><a href="#18">18. Replace all HTML elements.</a> 
	<li><a href="#19">19. Look the book over again.</a>
	<li><a href="#20">20. Add your name to the credits line.</a>
	<li><a href="#21">21. Upload book for verification.</a>
	</ul> 
<br> 
<h3>Software</h3> 
 
<a href="#Gutcheck">Gutcheck</a><br> 
<a href="#Editors">Text Editors</a><br> 
<a href="#Images">Images</a><br> 
<a href="#Tools">Other Tools</a><br> 
<br> 
<h3>Advanced Proofing Questions</h3>

<h4>Text Formatting</h4>
<a href="#Footnotes">Footnotes</a><br> 
<a href="#Illustrations">Illustrations</a><br> 
<a href="#Poetry">Poetry</a><br> 
<a href="#Tables">Tables</a><br>
<a href="#Sidenotes">Sidenotes</a><br> 
<a href="#Headings">Headings and Subheadings</a><br>
<a href="#Indexes">Indexes</a><br> 
<a href="#Errata">Errata Pages</a><br>
<h4>Symbols and Scripts</h4>
<a href="#Non-ASCII">Non-ASCII Characters</a><br> 
<a href="#Symbols">Non-Latin Scripts and Unusual Symbols</a><br> 
<h4>Extra Checks</h4>
<a href="#Bonus">Paranoid Proofing Checks (Stealth Scannos, etc.)</a><br> 
<h4>Technical Questions</h4>
<a href="#Formats">Non-ASCII Formats</a><br> 
<a href="#Missing">Missing or Problem Images</a><br> 
<a href="#Returning">Returning a Project</a><br>
<a href="#Beginner">BEGINNER and MENTOR projects</a><br>
<a href="#PPV">Post-Processing Verification</a>
<a href="#Other">Other Questions, and Suggestions for the FAQ</a><br> 
<br> 
<hr> 

<p>
This FAQ is designed as a guide for post-proofers, especially new ones. 
I hope that it makes the task of post-proofing a little less frightening!
</p> 
 
<hr> 
<br>
<h2>Getting Started</h2> 
<br> 
<p>
This section is designed especially for the first-time post-proofer.  
It provides a walkthrough of all of the essential steps needed 
to proof a relatively simple book.  
For more difficult books, please see the Advanced Proofing Questions section for advice.
</p>
<br>
<h4><a name="Introduction">What is post-processing, and who can post-proof?</a></h4>

<p>
The purpose of post-processing is to massage an etext into a readable form.  
In its journey through two proofing rounds, 
the text will have been edited by perhaps hundreds of proofers.  
The post-processor must standardize the formatting of the book 
and adjust it to comply with Project Gutenberg's requirements.  
The post-processor must also correct as many errors as possible 
that have survived both proofing rounds.  
The ultimate goal of post-processing is to create a plain-text etext 
with consistent formatting throughout, which contains as few errors as possible, 
and which accurately reflects the intentions of the author.
</p>

<p>
Post-processors require more experience than ordinary proofers.  
Because they are preparing the text for uploading to Project Gutenberg, 
they are the final editors of the text.  
Because of this, post-proofing is only available for proofers 
who have completed at least <b>400</b> pages in the first and/or second proofing rounds.  
Also, post-proofers should be very familiar with the 
<a href="document.php">Proofing Guidelines</a> before attempting to post-proof.
</p>
<br>
<h4><a name="Tools">What software will I need to get started?</a></h4> 
 
<p>The requirements for software are minimal:</p> 
	<ul> 
	<li>a <a href="#Editors">text editor</a>,
	<li>a program capable of opening <a href="#Images">images</a>,
	<li>a spell checker, and 
	<li><a href="#Gutcheck">Gutcheck</a>. 
	</ul> 
 
<p>
There are other useful <a href="#Extras">programs</a> available which are nice to have, 
but not essential. 
Take a look and see if any of them would be of use to you.
</p> 
<br> 
<h4><a name="Selecting"> All right. I have everything I need to get started except a book. 
Which one should I choose?</a></h4> 
 
<p>
For your first project, it's a good idea to pick a fiction book 
with a relatively small number of pages. Here's why:
</p> 
	<ul> 
    <li>fiction usually has fewer words per page than non-fiction 
        and a more streamlined format, 
        which means that it scans clearer and is unlikely to be riddled 
        with OCR errors and inconsistent formatting;
    <li>fiction generally lacks footnotes, tables, and other items 
        which could be difficult for a new post-proofer to deal with; and  
    <li>a low page count makes the work go faster and is easier to handle. 
	</ul>
 
<p>
Take a look at the Post-Processing section of your Personal Page 
to see all of the works available for post-proofing. 
Projects which are marked "TRAINEE" have been set aside for new post-processors, 
and are ideal texts with which to learn the ropes of post-processing.  
Books labelled "BEGINNERS" or "MENTORS" were originally set aside for new proofers.  
These books are also relatively easy, and would make good first projects.  
Unlike "TRAINEE" texts, however, these books may be checked out 
by both new and experienced post-processors. 
Alternatively, pick a text with a title that is likely to be fiction. 
"The Boy Scout Camera Club" is a good bet; "Copyright Renewals" is not. 
These texts are often marked "EASY" and are available to all post-proofers.
</p>

<p>
Download the text you have chosen by going to its Book Options scrollbar 
and selecting "Download Zipped Text".
</p>
 
<p>
Scroll through the <i>whole</i> text to see if there are any difficulties, 
like footnotes, poetry, foreign languages, dialects, and tables in it. 
This way, you will know what you will be dealing with before you commit to the project. 
If you see any of these items, you would be wise to choose a different project. 
But, if you think you can handle it, give it a try!
</p>
 
<p>
Check the <a href="<? echo $projects_forum_url ?>">Project Questions and Comments Forum</a> 
for your book's title to see what proofers have been saying about it. 
Again, this might alert you of issues which might make the work 
more difficult than you had realized.
</p>
 
<p>
When you have found a work that you want to do, 
check it out by going to its Book Options dropdown listbox and selecting Check Out Book. 
The book is now yours!
</p>
<br>
<h4><a name="Time">How long will it take me to post-process my book?</a></h4>

<p>
It is impossible to answer this question in advance.  
The time that a book will take to complete depends essentially on three factors:
</p>
	<ul>
	<li>the difficulty and length of the work itself;
	<li>the tools being used; and
	<li>the amount of experience the post-proofer has.
	</ul>

<p>
Some proofers can finish an easy book in only an hour or two 
(or even less, for the especially speedy).  
However, most proofers require longer than this to do a good job.  
Some especially difficult works can take weeks (or more!) to complete.
</p>

<p>
Try not to feel discouraged if you take more than a day to complete an "easy" book.  
Concentrate on learning the process of post-proofing, 
familiarizing yourself with any tools you might be using, 
and doing a quality job, rather than on working quickly.  
You will speed up naturally with practice.
</p>
<br> 
<h4><a name="Walkthrough">Great!  Now what am I supposed to <i>do</i>?</a></h4> 
 
<p>
<b>There is no one way to post-process books.</b> 
Every post-proofer has a different technique and uses different tools. 
The technique described below is the one the author personally uses,
but there are many ways of achieving the same end result. 
In time, you will probably develop your own technique.
</p>
 
<p>
This walkthrough is a very hands-on way of post-proofing, 
which is why it is recommended for first-time post-proofers. 
It takes longer to complete the book this way 
than if macros and global find-and-replace are used, 
but it gives the first timer a feel for what they should look out for 
by making the proofer scroll through the book several times.  
Also, the walk-through below is suitable for use on all operating systems, 
regardless of what text editor is used or what tools are available for that operating system. 
Once you get the hang of what post-proofing is all about,
feel free to use macros and such if you wish. 
A list of helpful tools and their functions is provided in the 
<a href="#Software">Software</a> section below.
</p>

<p>
It is strongly advised that you read through the following walkthrough 
<i>before</i> starting to post-proof. 
This will give you an idea of what you will be doing in advance. 
You might also like to look for helpful <a href="#Software">software</a> 
if you are so inclined.
</p>
<br> 
<h4>The Walkthrough</h4> 
 
<ol> 
<li><a name="1-5">Choose a book and sign it out.</a>  
    For guidance, see <a href="#Selecting">Selecting a Book</a>, above.<br>

<li>Look for comments and questions proofers had while proofing your book 
    in the <a href="<? echo $projects_forum_url ?>">Project Questions and Comments Forum</a>.<br> 
 
<li>Download the book's images (optional) and text. 
    You may wish to make a local backup of the text file, 
    for ease of reference later in the process.<br> 
 
<li>If you are using a Mac, change the character set to Mac. 
    In BBEdit Lite, use MIDex to do this by clicking on the ISO-&gt;Mac button. 
    You will need to convert the book back to ISO when it is complete.<br>
 
<li>If the document is not displayed in a monospaced font 
    (that is, a font where each letter, space, and punctuation mark 
    take up exactly the same amount of space), make this adjustment now.  
    Also, if you are not saving the working document as plain text (.txt), 
    but as a Microsoft Word document or some other file type, 
    make sure that the font size is 10 point.  
    Please note, however, that the final submitted version MUST be saved 
      as a text file (.txt).  
    This is easily done in all word processing programs.  
    If you think that you might forget to make this conversion, 
    it would be wise to save as plain text from the very beginning.<br>
 
<li><a name="6">Format the first page(s) to your liking.</a> 
    These pages contain the title of the book, the author's name, 
    and occasionally information on a translator, etc. 
    Delete any copyright or publication information (unless you really want to keep them in), 
    <b>except</b> for the years of publication and/or copyright (which, in a recent change, 
    we are now keeping), and space the rest in a way that is pleasing to your eye. 
    If this section is in ALL CAPS, you can change it to Capitalized Text if you wish.<br>
 
<li><a name="7">Scan the entire book for problems and formatting issues.</a> 
    You will almost always run across things which obviously <i>just aren't right</i>, 
    which can be corrected at this time. 
    There are many other things that you can look for during this first pass. 
    For instance, you may notice deviations from the proofing guidelines, 
      which should be corrected.  
    Here are a few things to watch for:
    <ul> 
	<li>poetry, tables, and other areas which <b>must not</b> be re-wrapped 
            (i.e. where line breaks are important).  
            These should be surrounded by /* */, but be wary, as some might not be!;
	<li>punctuation in odd places (usually from specks on the scanned image) 
            and dubious spellings;
	<li>lines of asterisks: if you find one, it may be worth looking through the images 
            to make sure that none were missed. 
            Also, make sure that they are correctly written 
            (7 spaces, then 5 asterisks separated by 7 spaces);
	<li>extra line breaks within paragraphs that clearly shouldn't be there, 
            or lack of space between paragraphs;
	<li>bad spacing: double spaces, indented paragraphs, wordsthatruntogether;
    <li>words in ALL CAPS at the beginning of paragraphs 
	<li>words that are italicized everywhere, like ships' names 
            (watch for non-italicized occurrences);
    <li>words containing an accent (watch for the loss of the accent); 
	<li>hyphenation: em-dashes should be marked --, and hyphens -, 
            with no spaces on either side. 
            If it's clearly wrong, fix it;
	<li>missing pages:  check the image numbers 
            to make sure that there are none missing, 
            and read the last few words on each page 
            and the first few words on the next page 
            to make sure that they go together;
	<li>pages which look like they haven't been proofed at all: 
            you'll have to look carefully for errors in these areas; and
    <li>words in all caps at the beginning of paragraphs. 
    </ul> 
 
<p>
Some pages will begin with a full sentence. 
Since you won't know if these sentences are the beginning of a new paragraph 
or a continuation of the previous one, either mark these with a * 
and check them later, 
or check them against the book's images as you come across them 
and format accordingly.
</p> 
 
<p>
If you wish, you may remove the page markers on the way through. 
Most people remove them at this stage, 
though some leave them in to make checking the text against the images easier. 
If you choose to remove them, make an unedited copy of the file 
to give you a reference with the page numbers still included.
</p>
 
<li><a name="8">Run a spell check.</a> 
    Mark any words that are even remotely questionable with a *, 
    or check each questionable word against the book's images as you go 
    and correct when necessary. 
    Proofers often miss some misspellings, such as ltttle for little.  
    Books of PG vintage, especially books of poetry, 
    may contain archaic and unusual spellings and words.<br> 
 
<li><a name="9">Search for -.</a> 
    If you run across something bizarre, 
    mark it with a * or check it against the images. 
    The purpose of this step is essentially to make sure that 
    there are no em-dashes in the middle of words, 
    no single dashes that should be em-dashes, 
    and that all end-of-line hyphenation is fixed. 
    <a href="#PRTK">PRTK</a> has a Dash Check feature which will list all words 
    containing dashes so that you can look for oddities with ease. 
 
<p>
Some proofers like to replace all hyphens with unbreaking hyphens 
in order to minimize the work in browsing for hyphenated words 
split between lines at the end. 
In Microsoft Word, the unbreaking hyphen character can be inserted 
by doing a global find-and-replace of - into ^~. 
If you choose to do this, you must change all unbreaking hyphens 
back to regular hyphens at the end, 
as they are non-ASCII and do not display properly on all machines.
</p>
 
<p>
There are many other <a href="#Bonus">"paranoid checks"</a> 
that proofers perform at this stage. 
These checks are particularly useful when the book scanned badly 
or the font was unusual, 
since these conditions introduce a lot of errors that are difficult for proofers to spot. 
If this is your first book, you don't need to worry about these "bonus" checks, 
but once you learn the basics of proofing, 
you will want to be sure that you do a thorough job 
and catch as many mistakes as you can, 
and these extra checks will help you produce high quality etexts.
</p>
 
<li><a name="10">Search for *.</a> 
    In addition to the asterisks that you may have inserted to mark potential problems, 
    proofers may have used them to mark problems that they had 
    and bring them to your attention. 
    If you have left in the page markers, 
    use them to guide you to the image containing the text that you are checking.<br>
 
<li><a name="11">Format chapter headings and any subheadings 
    that you may find in the text.</a> 
    It is recommended that there be three blank lines between chapters, 
    two after the chapter title, and four between larger sections (eg.  Part 1 and 2), 
    but the exact numbers are not very important. 
    As long as you are consistent within your work, 
    use whatever spacing seems appropriate and looks good to you.
 
<p>
Scan the book and make sure that all of the chapter headings and subheadings are there! 
Proofers sometimes remove these accidentally. 
If there is a table of contents, use it as a checklist; otherwise, refer to the images. 
If the chapters are simply numbered Chapter 1, 2, etc., 
just make sure that there are no numbers missing.
</p> 
 
<li><a name="12">Run Gutcheck.</a> 
    Check every potential problem that it brings to your attention.  
    Not all Gutcheck "flags" are genuine errors 
   (for example, it may report short lines where the text contains poetry or a table), 
   but each must be looked into and corrected if necessary.<br>
 
<li><a name="13">Remove page markers, either manually or with a tool or search.</a> 
    Remove [Blank Page] tags as well.<br>
 
<li><a name="14-16">Search for space-hyphen and hyphen-space 
    and replace each instance with hyphen.</a> 
    The exception here is hyphens replacing a word, like a person's name; 
    in that case, leave spaces before and after the hyphens to indicate this. 
    There may be other exceptions like this as well, 
    but you'll be able to identify them from their context.<br>
 
<li>Search for double space and replace with single space. 
    DO NOT do a global find and replace 
    if your text contained poetry, charts, or lines of asterisks, 
    all of which legitimately contain multiple spaces.<br>
 
<li>Remove end-of-line spaces by searching for spaces followed by returns 
    and replacing them with returns only. Again, don't do a global replace. 
    <a href="#PRTK">PRTK</a> has a feature which will remove end-of-line spaces 
    automatically. (So does RewrapIndent)<br>
 
<li><a name="17">Find and replace all incidences of &lt;i&gt; and &lt;/i&gt; with _.</a> 
    Make sure that the same number of &lt;i&gt; were replaced as &lt;/i&gt;. 
    If there were more or less, some of the tags may not have been correctly typed, 
    and you'll have to track them down.   
    Search for &lt;b&gt; and &lt;/b&gt; and replace them with *, +, 
    or any other character which is not used in your text 
    (suggestions from other post-processors are ~, %, #, =, 
    and converting words in bold to ALL CAPS). 
    Try searching for &gt;, &lt;, and / to find both of these more quickly.  
    You may wish to preface your text with a transcriber's note explaining 
    which symbol you used for italics and which for bold, 
    but this is not absolutely necessary.<br>
 
<li><a name="18">Time to rewrap.</a> 
    Did you see any poetry, tables, etc.? 
    If not, rewrapping the lines should be easy. 
    You will need to rewrap the lines to between 65 and 75 characters in length. 
    Each program has a different way of doing this, 
    and you will have to find the way that works best for you. 
    In BBEdit Lite, select Hard Wrap from the Text menu. 
    For MS Word, save as Text with Line Breaks. 
    If worst comes to worst and you cannot find an easy way to rewrap the lines, 
    find and replace all line breaks with spaces, 
    count any line to find to see approximately where 65-75 characters falls, 
    and insert lines breaks manually at this point. 
    It's painful, but it works. 
    Be grateful that you chose a book with a low page count!<br>
 
<p>
If you have areas that you must not wrap, you must be more careful. 
In BBEdit, it is possible to rewrap a section by highlighting it and selecting Hard Wrap. 
This allows you to rewrap the text in blocks between tables or poems. 
Other programs may have a similar feature. 
If you cannot find an easy way to rewrap around poems, 
there is a process which you can try <a href="rewrap.html">here</a>.
</p>

<p>
Rewrapping sometimes reveals spacing errors.  
Repeat steps 14 and 16 to catch any new problems 
that may have been introduced by the rewrap.
</p>
 
<li><a name="19">Give the whole thing a quick eyeball to make sure that all is well.</a>  
    If you are not sure what the finished text should look like, 
    download a text from <a href="http://www.promo.net/pg">Project Gutenberg</a> 
    and skim it to get a clearer idea.<br>

<li><a name="20">Add your name to the credits line.</a>  
    If you wish for your name to appear in the credits of the final etext, 
    type it at the top of the text in the form you would like it to appear.  
    This might be your proper name, your screen name, 
    or any other name that you wish to be known by.  
    If you do NOT wish to be credited, leave a note to this effect as well.  
    If you would like to be notified when your etext is posted to PG, 
    put your email with your name at the top of the etext.<br>
 
<li><a name="21">Zip the finished product and upload it to the site.</a> 
    Go to your Post-Processing page, 
    and select Upload for Verification from the project's drop-down menu. 
    After your first few texts, 
    they will also explain how to create two separate versions: 
    an ASCII version for posting and an archive version for, well, archiving.<br>

<p>
NOTE:  Please ensure that your files .zip extension is in lower case, NOT in upper case.  
Some people have not been able to upload their files 
when the .zip extension was written in upper case.
</p>
 
<li>Relax.  You're done! 
</ol> 
 
<hr> 
<a name="Software"></a>
<h2>Software</h2> 
<br> 
<h4><a name="Gutcheck">What is Gutcheck, and how do I use it?</a></h4> 
 
<p>
Gutcheck is a nifty piece of software created by Jim Tinsley 
for people working on Project Gutenberg etexts. 
It checks for errors which are common, but not easy to spot, 
like mismatched quotes, short lines, etc.
</p>
 
<p>
Gutcheck is currently being produced for Windows and *nix systems, 
and can be found <a href="http://gutcheck.sourceforge.net">here</a>. 
A quick-and-dirty Mac build can be downloaded <a href="gutcheck-0-95.sit">here</a>. 
A few kindly individuals have also constructed web interfaces for Gutcheck 
(<a href="http://caw.homelinux.net/gut/gutcheck.php">here</a> and 
<a href="http://inkwina.homeip.net/%7Ephsi/gutcheck/gutcheck.php">here</a> 
*please note that neither of these are working at present; 
if you would like to set one up, please 
<a href="<? echo $forums_url ?>/privmsg.php?mode=post&amp;u=1674">send me the link</a>*) 
if you cannot run any of the builds available. 
You could also simply ask another post-proofer to run Gutcheck for you 
if you have trouble with it, and they can send you a list of results.
</p>
<br> 
<h3><a name="Extras">Other Useful Software...</a></h3> 
<br> 
<h4><a name="Editors">Text Editors</a></h4> 
 
<p>
Any text editor can be used for post-proofing, 
but some have tools which make them more suitable than others for the job. 
<a href="http://www.barebones.com/products/bblite/index.shtml">BBEdit Lite</a> 
is an excellent choice for Mac users. 
Be sure to download the 
<a href="http://ftp.barebones.com//pub/third-party-plugins/MIDex_1.4.2.hqx">MIDex</a> 
plugin as well. 
It is no longer being supported, but remains on the site for download.  
Many *nix users use emacs, which can be downloaded 
<a href="http://ftp.gnu.org/pub/gnu/emacs/">here</a>, 
though it is probably on your machine already. 
It is also available for 
<a href="http://www.emacs.mirkolinkonline.de/install_en.html">Windows</a>. 
If you use Microsoft Word for any platform, 
you will be able to run a useful <a href="#Tools">macro</a>.
</p>
<br> 
<h4><a name="Images">Images</a></h4> 
 
<p>
You will need to be able to look at your book's scanned images 
at some point in the post-proofing process. 
You can either download them and use a third-party program to view them, 
or view them online through a browser. 
Any program that will display images will do.
</p>
 
<p>
Some people have recommended utilities which allow you to see thumbnails of images 
without opening them, making it easier to find the one you're looking for. 
<a href="http://www.lemkesoft.de/gcdownload_us.html">GraphicConverter</a> 
is one such program for Macintosh machines. 
<a href="http://www.irfanview.com/english.htm">Irfanview32</a> 
is a quality Windows image manipulation program. 
<a href="http://www.xnview.com/">Xnview</a> 
runs on Windows, *nix, and a host of smaller operating systems.
</p>
<br> 
<h4><a name="Tools">Other Very Useful Tools</a></h4> 
 
<p>
Below are some useful pieces of software which are designed to aid post-processors.  
There are also some macros listed here, 
which will perform certain repetitive post-proofing tasks for you 
to save you time and effort.  
Though none of these tools are necessary for the job 
(i.e. it can be done even if you choose not to run any of them), 
they can make it a LOT easier.  
Indeed, many post-processors wouldn't dream of working without them!
</p> 

<p><b>Software Packages</b></p>

<p>
There are presently two pieces of software 
which have been specifically written by post-proofers for post-proofers.  
Both provide an all-in-one kit, so you can use them for all of your post-processing, 
or just take advantage of some of their extras.  
Each program has their supporters and detractors, so give them both a try!
</p>

<p>
<b>Guiguts</b> was written by thundergnat.  
The tool is almost a complete post-proofing kit in itself.  
Among its special features are the ability to 
automatically remove page headers while keeping track of each page's identity, 
analysis of word frequencies 
(especially useful for catching misspelled proper names and other odd spellings), 
automated checks for markup errors, a footnote moving function, 
easy checking of "Stealth Scannos", and much more.  
You won't even need a text editor or Gutcheck, because they're built in!
</p>

<p>
Guiguts comes in two forms: as a 
<a href="http://mywebpages.comcast.net/thundergnat/winguts.zip">DOS/Windows executable</a> 
(no other software needed to run it), and as a 
<a href="http://mywebpages.comcast.net/thundergnat/guiguts.zip">perl script</a>, 
which can be run on any platform, but requires a perl interpreter.  
The online manual can be found 
<a href="http://mywebpages.comcast.net/thundergnat/guiguts.html">here</a>.  
Guiguts does not come with a spell checker. 
For the latest news, and the best place to ask a question about GuiGuts,
please see the forum thread 
<a href="<? echo $forums_url ?>/viewtopic.php?t=3075">Gui frontend to gutcheck and other post processing - Guiguts</a>
</p>

<p>
BillFlis has created a set of four tools which run on the Windows platform only.  
<b>GutSweeper</b> scans the text and makes some automatic corrections, 
such as eliminating double spaces and splitting oe ligatures.  
It is markup sensitive, 
so that it will not ruin the formatting of poetry, block quotes, and tables.  
This saves some time for the proofer, 
as all of its changes are ones that they would have to make anyway.  
<b>GutAxe</b> is an interactive tool, 
which allows the post-proofer to make more complicated changes to the text.  
It works a bit like a spell checker, 
highlighting a "problem" area and suggesting possible solutions.  
It scans for common "Stealth Scannos" and punctuation errors, among other things.  
<b>GutWrench</b> supplements GutAxe with a few extra checks.  
<b>GutHammer</b> rewraps the text in a similar way to Big_Bill's RewrapIndent tool 
(see Macros, below).  
The changes made by these programs are saved as new files, 
with no alteration being made to the original, 
so they are totally undoable if something goes wrong.  
The tools can be downloaded 
<a href="http://frankfordinstitute.bravepages.com/GutWrench.htm">here</a>.
For the latest news, and the best place to ask a question about GutWrench,
please see the forum thread 
<a href="<? echo $forums_url ?>/viewtopic.php?t=21114">GutWrench: Alternative to PRTK for Post-Processing</a>
</p>

<p><b>Spell Checkers</b></p>

<p>
Many text editors do not provide a spell checking feature.  
Instead, spell check programs are used to provide this essential function.  
A separate spell-checking program is also useful 
when post-proofing texts which are not in English.  
Many post-processors do not have access to non-English spell checkers, 
and buying add-ons for programs like Microsoft Windows can be very expensive.  
Independent spell checkers provide dictionaries 
for a wide range of common (French, Spanish, Dutch) 
and not-so-common (Catalan, Estonian, or English Biomedical word list) for free.
</p>

<p>
<b>Excalibur</b> is a Macintosh spellchecker.  
It has separate downloadable dictionaries for many different languages.  
The dictionaries provided are very complete.  It works with LaTex.  
The major drawback of this program is that its dictionaries are difficult to edit.  
Though adding words is easy, removing words (such as common Stealth Scannos) is not.  
Excalibur can be downloaded 
<a href="http://www.eg.bucknell.edu/~excalibr/excalibur.html">here</a>.
</p>

<p>
<b>Aspell</b> is a spell checker which is available 
both as a Windows executable and as a perl script.  
It provides a good selection of different language dictionaries.  
It is available <a href="http://aspell.sourceforge.net/">here</a>.
</p>

<p>
<b>Ispell</b> has been ported to Windows, OS/2, and also runs on *nix and MacOSX.  
It does not run on DOS or older Mac OS machines.  
Like the other two spell checkers, 
there are a good selection of language dictionaries available for downloading.  
Ispell can be found <a href="http://fmg-www.cs.ucla.edu/geoff/ispell.html">here</a>.
</p>

<p><b>Macros</b></p>

<p>
Please note that macros may need an additional piece of software in order to run.  
For example, a lisp or perl script will need a lisp or perl interpreter to work, such as 
ActivePerl, available <a href="http://www.activestate.com/Products/ActivePerl/">here</a>,
and Clisp, available <a href="http://clisp.sourceforge.net/">here</a>, both for free.
If you need help getting them to work, or your platform isn't supported by these,
ask on the <a href="<? echo $post_processing_forum_url ?>">Post-Processing Forum</a> for help;
almost certainly someone will be able to assist you!
</p>
 
<p>
There is a macro available for <a href="Wordmacro.html">Microsoft Word</a>, 
which rewraps text while preserving paragraph breaks. 
It may also replace double spaces with single ones 
and adjust the margins to less than 75 characters (I am less sure about these functions). 
Please note that this macro will not work in Word 97 or older versions.
</p>
 
<p>
Naomi Parkhurst has written an <a href="BBEditscript.sit">Applescript</a> macro, 
which rewraps the text (including around poetry) and removes superfluous spaces. 
It works for BBEdit (but not BBEdit Lite).
</p>
 
<p>
Garweyne has written a 
<a href="http://www.dm.unipi.it/%7Etraverso/Ebooks/Lsp/footnotes.lsp">lisp script</a> 
to aid post-proofers wishing to rearrange footnotes. 
A more elaborate description of the script's abilites is available at the link above. 
For even more information, and the best place to ask a question on this tool, 
see the forum thread
<a href="<? echo $forums_url ?>/viewtopic.php?t=1780">Footnote moving tool</a>.
Many more of his lisp scripts are available 
<a href="http://www.dm.unipi.it/%7Etraverso/Ebooks/Lsp/dptools.el">here</a>.
</p>
 
<p>
Bill Keir (big_bill) has written a lisp script called 
<a href="http://users.hunterlink.net.au/%7Embbbk/RewrapIndent.zip">RewrapIndent</a> 
which will both rewrap and indent text automatically. 
It honours the /*  */ tags that proofers use to mark poetry, 
so poetry will not be rewrapped but will be automatically indented,
and allows the addition of other tags to handle block quotes automatically if needed.
It handles complicated cases like poetry nested inside block quotes, 
and multiple levels of block quotes within block quotes, 
but can also be used to quickly and tidily rewrap any simple book 
that needs no special indenting, to whatever line length you choose, too.
For more details see the HTML documentation inside the zip file.
To ask questions about RewrapIndent, please see the forum thread 
<a href="<? echo $forums_url ?>/viewtopic.php?t=1981">Rewrap and Indent tool</a>.
</p>
 
<p><b>PRTK</b></p>

<p>
PRTK (Proofreader's Toolkit) removes the page markers for you 
so that you needn't do it manually. 
Its other features are of little use, and may cause more problems than they solve.  
In particular, DO NOT use its rewrap feature. 
Though it contains Gutcheck, the version bundled with it is very old, 
and it is recommended that you download the latest version of Gutcheck instead.
PRTK is available 
<a href="http://texts01.archive.org/%7Echarlz/public/PRTK-1-0-134.zip">here</a>, 
and it runs only on the Windows platform.
</p>
<br>
<hr>
<br>
<h2>Advanced Proofing Questions</h2> 
<br> 
<p>
This section contains information on the more complex aspects of post-proofing.  
It is designed for advanced post-processors, rather than beginners.
</p>

<p>
The formatting issues treated below are also discussed 
in the <a href="document.php">Proofing Guidelines</a>, 
should you need information on proper markup.
</p>
<br> 
<h3><a name="Footnotes">Footnotes</a></h3> 
<br> 
<h4>Should I leave footnotes inline, or move them elsewhere?</h4> 
 
<p>
This depends a great deal on the length of the footnotes, their frequency, 
and on the type of text that you are proofing.
</p>
 
<p>
It is suggested that you should leave the footnotes where they occur in the text 
if they are short and relatively uncommon. 
In these cases, the footnotes won't disrupt the flow of the text very much.
</p>
 
<p>
If the footnotes in your text are long, numerous, 
or are primarily bibliographic references, 
you might be wise to move them to the end of the paragraph where they occur, 
on their own line, and just leave a marker (ex. [2]) in the paragraph.
</p>
 
<p>
If the footnotes are so common that even this would be highly disruptive to the reader, 
consider collecting them all and moving them to the end of the chapter. 
This is a bit of a pain, but it makes such works infinitely more readable. 
Mark the endnote in the text as you would an ordinary footnote, 
fix all of the numbering, 
and list the endnotes at the end of the chapter with the new numbering.
</p>

<p>
If you should you decide to move footnotes, Garweyne has a 
<a href="http://www.dm.unipi.it/%7Etraverso/Ebooks/Lsp/footnotes.lsp"> lisp tool</a> 
which makes this MUCH easier.
</p> 

<p>
Footnotes in poetry are a special case worth mentioning. 
Most post-proofers agree that the footnotes should not be simply inserted into the text 
where they occur, as this interferes with the rhythm of the poetry. 
Mark the place referenced by the footnote, 
then move the footnote to its own line or to the end of the poem, 
whichever seems most appropriate.
</p>
 
<p>
Whatever format you choose, make sure that it is consistent throughout the text.
</p> 
<br> 
<h3><a name="Illustrations">Illustrations</a></h3> 
<br> 
<h4>Should I leave in all of those [Illustration] tags?  
    What about the ones with captions?</h4> 
 
<p>
[Illustration] tags with no caption can be safely removed. 
However, do not do this until the end, when the ASCII version is produced. 
Leave them in the archive version of the text. 
That way, if someone decides to make an HTML version in the future, 
the tags will be there and it will be easier to correctly place the images.
</p>
 
<p>[Illustration] tags with captions should be left in place for the reader to enjoy.</p>
<br> 
<h4>My text will make no sense if the actual illustrations aren't included.</h4> 
 
<p>
If the text was scanned with the intention of just converting it to ASCII, 
email the Project Manager and ask for advice. 
If you are willing and able and the scans are available, 
they may ask you to do an HTML version 
and provide good quality scans for the missing images. 
Alternately, they may decide that the project doesn't really need the images 
and explain their opinion.
</p>
 
<p>
If the text really should be produced in a version 
that will allow future readers to view the images, 
and you are unable or unwilling to do the work to put it in such a version, 
return it and post your concerns in the 
<a href="<? echo $projects_forum_url ?>">Post-Processing Forum</a>.</p>
<br> 
<h3><a name="Poetry">Poetry</a></h3> 
<br> 
<h4>My book contains a few verses of poetry.  How should I format them?</h4> 
 
<p>
The proofers should have surrounded any verses of poetry with the markers /* and */. 
Remove the markers, and check against the original image to make sure 
that the formatting is correct. 
A quick scan should reveal if the spacing in your text and the original match.
</p>
 
<p>
Make sure that the indentation is consistent, at least within each poem. 
It's entirely possible that one proofer indented some lines four spaces, 
while the proofer who got the next page indented five, 
when the image shows the same amount of indentation. 
</p>

<p>
Indent all of the poetry by 2 spaces (or more if you prefer), 
preserving any further indenting that the author intended.
</p>
 
<p>
DO NOT WRAP LINES. You will have to take special care when you rewrap the text 
not to rewrap your poetry. 
However, if a line is broken in two due to its length, 
but it was not intended to be (the second line of these is usually highly indented), 
join the two parts of the line together. 
If they still don't fit on one line, break them and indent the second half heavily.
</p>
<br> 
<h4>My book <i>is</i> poetry.  How do I format it?</h4> 
 
<p>
Aside from the fact that poems won't be marked by poetry markers, 
the book should be formatted as for occasional verses (check the indentation, etc.). 
Also, the poem(s) need not be indented two spaces by default, as they are the main text, 
and don't need to stand out from it.
</p>
 
<p>
Books which are entirely comprised of poetry do not need to have their lines rewrapped 
(watch the Gutcheck output for overly long lines, though). 
However, the book may contain an introduction or other prose section 
which will need rewrapping.
</p>
 
<p>
<a href="<? echo $forums_url ?>/privmsg.php?mode=post&amp;u=3561">bconstan</a> 
has graciously offered to aid anyone who needs extra help post-proofing poetry books. 
If you have any questions, send her a message.
</p>
<br> 
<h3><a name="Tables">Tables</a></h3> 
<br> 
<h4>What do I do with tables?</h4> 
 
<p>
Tables should have been marked with /* */ by the proofers, 
but a quick scan of the book should turn any up even if they are unmarked, 
as they are quite conspicuous.
</p>
 
<p>
You will have to move the text in the table around to make it as readable as possible. 
If the headings are broken between lines, put them on the same line if you can. 
Adjust the spacing of the columns so that they look good on the screen 
and aren't too close together. 
Make all of the column entries line up. 
If you are lucky, this formatting was already done for you, 
but not all proofers can format tables accurately 
(if their display font isn't monospace, for example). 
Watch for tables that span multiple pages, 
as they will be unlikely to have similar formatting. 
"Related" tables should be formatted consistently, if possible.
</p>
 
<p>
DO NOT REWRAP LINES! You don't want to destroy all of your hard work, now do you?
</p>
<br> 
<h4>The table will not fit into the lines widths allowed by PG.</h4> 
 
<p>You have a couple of options here:</p> 
 
<ol> 
<li>Try your best. You may have to split the chart into multiple rows. 
    Or you may come up with your own way to format the information in the troublesome chart. 
    Be inventive.
<li>Give up. Mark the chart up as an [Illustration], use the title as the caption, 
    and write it off. It's not ideal, but sometimes it's the only way.
</ol> 
<br> 
<h3><a name="Sidenotes">Sidenotes</a></h3> 
<br> 
<h4>These [Sidenote] tags seem redundant!</h4> 
 
<p>
In most cases, sidenotes add a bit of summary or description to the text, 
but, in very rare cases, the sidenotes add nothing to the book 
and will be an annoyance rather than a help. 
If your book fits this mold, consider leaving out the sidenotes. 
BUT, think long and hard about this, as it is altering the text of the original. 
Email the Project Manager and/or post in the 
<a href="<? echo $projects_forum_url ?>">Forums</a> 
for a second opinion before taking this step.
</p>
<br> 
<h3><a name="Headings">Headings and Subheadings</a></h3> 
<br> 
<h4>How do I differentiate subheadings from headings?</h4> 
 
<p>
Usually, the easiest way to differentiate between subheadings and headings 
is to change the line spacing 
(ex. leave three lines blank when a new heading begins, and only one for a new subheading). 
However, some texts may have more than one layer of subheading. 
In these cases, you will have to devise a markup which is appropriate to the text. 
You could indent subheadings a certain number of spaces depending on their "layer", 
for example.
</p>
<br> 
<h3><a name="Indexes">Indexes</a></h3> 
<br> 
<h4>Is there anything special that I should know about formatting indexes?</h4> 
 
<p>
Pay attention to the presence/absence of trailing commas and semicolons. 
You may either leave these in or remove them, as long as you are consistent.
</p>
 
<p>
DO NOT REWRAP LINES. 
Unless you have places a blank line between each and every entry, 
rewrapping will destroy the format of the index. Be careful!
</p>
<br>
<h3><a name="Errata">Errata Pages</a></h3>
<br>
<h4>My book has an errata page at the end.  Should I correct the errors?</h4>

<p>
Yes.  The list of errata at the end of the book reflect the author's intention, 
and one of the guiding principles of etext production 
is to preserve the author's original intent.  
First- and second-round proofers had access to only one page of the book at a time, 
so none of the errata errors will have been corrected by them.  
This job therefore falls to the post-processor, 
who has access to all of the pages of the book.
</p>
<br>
<h3><a name="Non-ASCII">Non-ASCII Characters</a></h3> 
<br> 
<h4>My text has accents, pound signs, or other non-ASCII characters in it. Should I preserve them in the final version?</h4> 
 
<p>
In general, yes. Keep all of the accented words (or symbols) as they are. 
An ISO-8859-1 (also called Latin-1 or 8-bit ASCII) file can be made which preserves them. 
If the text is in a language containing many accents 
that are not found in ASCII or ISO-8859-1, 
there are other forms of encoding out there in which they can be preserved.
</p>
 
<p>
Some English-language texts contain just the occasional word with an accent in it. 
If the accents are rare and don't add any meaning to the text, 
take them out when producing the ASCII edition, 
but leave them in the archive version.
</p>
<br> 
<h3><a name="Symbols">Non-Latin Scripts and Unusual Symbols</a></h3> 
<br> 
<h4>How do I handle footnotes, etc. in Greek, Russian, 
or other texts with a non-Latin alphabet?</h4> 
 
<p>
If possible, the text should be transcribed into the Latin alphabet. 
It's not a lossless process, but it's the only way to preserve these snippets in ASCII. 
Information of the transcription process is available in the 
<a href="document.php">Proofing Guidelines</a>.
</p>
 
<p>
Many languages, like Arabic and Hebrew, are difficult to transcribe 
without an intimate knowledge of the language. 
If your text contains snippets of such a language 
and you don't have the knowledge to transcribe it yourself, 
try posting in the <a href="<? echo $projects_forum_url ?>">Forums</a> 
to find someone to team up with for transcription.
</p>
 
<p>
If there is a significant amount of text in a non-Latin script, 
it may be worth making a Unicode version, 
which would allow the original script to be preserved.
</p>
 
<p>
If you cannot transcribe the language, 
and you can't find anyone else who is capable of doing it for you, 
mark its presence with [Arabic] (for Arabic), 
and delete any OCR garbage that may have been left in the text. 
It's too bad that the information will be lost, but you've done the best you could.
</p>
<br> 
<h4>My text has weird symbols in it (Zodiac signs, medical abbreviations, etc.).  
How do I mark these up?</h4> 
 
<p>
If you are lucky, the proofer will have done some research 
and found the meaning of the symbol for you. 
However, often the proofer will mark the symbol with a * and leave you with the legwork.
</p>
 
<p>
If know what the symbol represents, write out its meaning, e.g. [Symbol: Jupiter]. 
Do not try to replicate the symbol itself in ASCII.
</p>
 
<p>
If you have never run across the symbol before, 
here are a few web pages provided by proofers to help you:<br>

<a href="http://www.lib.umich.edu/eebo/docs/dox/medical.html">Apothecaries'/Medical</a><br> 
<a href="http://www.lib.umich.edu/eebo/docs/dox/alchem.html">Alchemical</a><br> 
<a href="http://www.lib.umich.edu/eebo/docs/dox/moresyms.html">Astronomical</a><br> 
<a href="http://www.lib.umich.edu/eebo/docs/dox/latabbrs.html">Latin Abbreviations</a><br> 
<a href="http://www.symbols.com/graphicsearch.html">Graphic Search for Symbols</a> 
</p> 
 
<p>
This section has room to grow. 
If you find any other good links, 
please <a href="<? echo $forums_url ?>/privmsg.php?mode=post&amp;u=1674">PM me</a>.
</p>
 
<p>
Note that some symbols may have more than one meaning. 
If this is the case, try to determine the best meaning from the context of the symbol.
</p>
<br> 
<h3><a name="Bonus">Paranoid Proofing Checks</a></h3> 
<br> 
<h4>I want to make sure that I do a really good job post-processing my book. 
Are there any common errors that often make it through the checking system?</h4> 
 
<p>
Yes, there are a few kinds of errors which often make it through both rounds of proofing 
and into the final etext. 
These errors fit into three categories: 
specks that introduce punctuation, 
errors introduced by the tags used in proofing, 
and "scannos" that can make it through a spell check.
</p>
 
<p>
To check for random punctuation caused by specks on the image that was OCRed, 
search for the following things:
</p>
	<ul> 
	<li>, or . followed immediately by a letter, 
	<li>. followed by a space and a lowercase letter, 
	<li>, followed by a space and an uppercase letter, 
	<li>/ and /', which often occur instead of ," and .",
	<li>.' for ." (reverse these if your book uses single quotes as double quotes, 
	<li>{ and } instead of [ and ],
	<li>standalone ' followed by a hard return, and
	<li>standalone symbols, like &amp;, $, ^, =, \, /, «, », 
            @, ~, `, #, %, +, and |, which can creep in.
	</ul> 
 
<p>
A few errors are introduced by the HTML and other proofing markup that DP uses for proofing. 
To eliminate these, do the following:
</p>
	<ul> 
	<li>before deleting the HTML elements, 
            search for &lt;i&gt; followed by a space, and &lt;/i&gt; preceded by a space, 
	<li>[ and ], to make sure that all [Footnote] and [Illustration] tags 
            are properly formatted, and
	<li>after replacing the HTML elements, search for &gt;, &lt;, and / 
            to make sure that all of the tags have been replaced.
	</ul> 
 
<p>
There are myriad errors which will make it through a spell checker. 
If you would like to avoid tedious find-and-replaces, 
you could remove these words from your program's spell checker. 
Only a few of the most common scannos will be listed here.
</p>
 
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
	<li>tile, for the,
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
 
<p>
A more complete, and constantly growing list is maintained by 
<a href="<? echo $forums_url ?>/privmsg.php?mode=post&amp;u=6141"> big_bill</a>. 
If you have found a "stealth scanno" which isn't on his lists, send it to him. 
He also collects statistics on the appearance rates of those already on the lists,
so if you care to keep count of sightings of old stealth scannos he'd be just as happy
to accept those as reports of new ones.
</p>
 
<p>
The latest version (presently 1.22) of big_bill's lists can be found here:
</p> 
<a href="stealth_scannos_eng_common.txt">Common English Scannos</a><br> 
<a href="stealth_scannos_eng_rare.txt">Rare English Scannos</a><br> 
<a href="stealth_scannos_eng_suspect.txt">
  Theoretical (But as yet Unwitnessed) English Scannos</a> <br> 
<a href="stealth_scannos_fr_common.txt">Common French Scannos</a><br> 
<a href="stealth_scannos_fr_rare.txt">Rare French Scannos</a><br> 
<a href="stealth_scannos_fr_suspect.txt">
  Theoretical (But as yet Unwitnessed) French Scannos</a><br> 
<a href="stealth_scannos_ger_common.txt">Common German Scannos</a><br> 
<a href="stealth_scannos_ger_rare.txt">Rare German Scannos</a><br> 
<a href="stealth_scannos_ger_suspect.txt">
  Theoretical (But as yet Unwitnessed) German Scannos</a><br> 
 
<p>
The lists are plain text, and could also be used by an adventurous programmer 
to check for common letter shifts (ex. h -&gt; b) and such. 
Several of the custom built post-processing <a href="#tools">Tools</a> 
make use of them, also.
</p>
<br> 
<h3><a name="Formats">Non-ASCII Formats</a></h3> 
<br> 
<h4>I want to do something special with this text. 
Can I make a version of the text in HTML/XML/etc.?</h4> 
 
<p>
Yes! Feel free to make non-ASCII versions if you wish. 
As long as you also produce an ASCII version, 
PG will be glad to accept any other version that you may produce.
</p>
<br> 
<h3><a name="Missing">Missing or Problem Images</a></h3> 
<br> 
<h4>There's a page missing from the scans, 
or some words/pages are blurred/chopped off, etc.</h4> 
 
<p>
Try emailing the Project Manager. 
If they still have the text, they may be able to clarify blurred or missing words, 
or give you a scan for a missing page.
</p>
 
<p>
If they don't have the text, 
join <a href="http://www.promo.net/pg/subs.html">gutvot-d</a> 
and post a message asking for help. 
Give the name and author of the book which you are working on, 
what you will require as help 
(usually looking at a paper book to clarify a few words), 
and how much work there will be 
(are only a few lines cut off, or are there whole pages missing?). 
These volunteers will reply to you if they have access to your book. 
You should then send them the text with comments 
so that they can find the damaged portions, 
correct them, and send them back to you. 
You could also do this process yourself if the book exists in your local library.
</p>

<p>
Please do not submit the project for Verification until the missing text has been found.
</p>
<br> 
<h3><a name="Returning">Returning a Project</a></h3> 
<br> 
<h4>This project is too hard, or I don't have time for it, 
or I just don't want to do it any more!  How do I get rid of it?</h4> 
 
<p>
To dispose of your project and return it to the pool for another post-proofer, 
go to your Post-Proofing page, find the title of the book which you are returning, 
and select Return to Available from its drop-down menu.
</p>
<br>
<h3><a name="Beginner">BEGINNER and MENTOR Projects</a></h3>
<br>
<h4>What do the labels "BEGINNER" and "MENTOR" mean in the post-processing list?  
Why are these projects split into different parts?</h4>

<p>
The "BEGINNER" and "MENTOR" labels are survivals from the first two proofing rounds.  
They were used to label books reserved especially for beginning proofers 
(in the first round) and mentors (in the second round).  
However, they are NOT reserved for "beginner" and "mentor" post-processors.  
They are available to all.
</p>

<p>
"BEGINNER" and "MENTOR" books are generally split into smaller units.  
This not only ensures a constant supply of projects for beginners, 
but helps them get feedback from mentors faster 
than if the books were kept in one piece.  
It is encouraged, though not absolutely essential, 
for the same post-processor to check out all of the pieces 
of BEGINNER and MENTOR books at the same time 
so that the formatting will be consistent throughout.  
The pieces should be joined together into one file for submission.
</p>
<br>
<h3>Post-Processing Verification</h3>
<br>
<h4><a name="PPV">What is Post-Proofing Verification, and who can do it?</a></h4>

<p>
Post-proofing verification is the "second round" of post-proofing.  
The post-proofer looks over the post-proofed etext for errors big and small, 
and submits them to Project Gutenberg.  
They will often provide feedback to post-proofers as well, 
so that they can improve the quality of their work.
</p>

<p>
PPV's (as they are known) need to be experienced post-proofers, 
familiar with common problems in etexts and able to provide feedback.  
Because of this, there is only a limited pool of people capable of PPVing.  
Once a person has submitted a number of consistently good etexts, 
the PPV will (at their discretion) give them permission to PPV projects themselves.  
If you have not been given this permission, please do not check out PPV projects.
</p>

<br>
<h3><a name="Other">Other Questions</a></h3> 
<br> 
<h4>I have a question which isn't in this FAQ.</h4> 
 
<p>
Post-proofing involves common sense and personal judgement. 
The only solid rule is for the post-proofer to preserve the author's intention 
to the best of their ability. 
There can be more than one way to handle a particular piece of formatting, 
and all of them can be right. 
You, as post-proofer, have a great deal of freedom to decide 
how to handle particular formatting issues and make global format changes.
</p>
 
<p>
If your common sense and personal judgement aren't helping you solve 
some particular problem, post your question in the 
<a href="<? echo $post_processing_forum_url ?>">Forums</a>. 
Other post-processors can then tell you how they would handle the situation. 
Their suggestions might give you a logical answer for your text, 
or inspire your own idea as to how to handle the issue.
</p>

<hr>
 
<p>
If you think that something should be added to the FAQ, 
<a href="<? echo $forums_url ?>/privmsg.php?mode=post&amp;u=1674">PM me</a>. 
I don't mind answering questions, really I don't!.
</p>

<p>
If you have any suggestions 
(ex. a nifty new spell checker or text editor that I haven't included, 
comments on grammar, anything!), 
please don't be afraid to 
<a href="<? echo $forums_url ?>/privmsg.php?mode=post&amp;u=1674">tell me about them</a>.
</p>
<br> 

<?
theme('','footer');
?>
