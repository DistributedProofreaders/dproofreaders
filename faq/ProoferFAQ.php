<?php
$relPath='../pinc/';
include_once($relPath.'base.inc');
include_once($relPath.'faq.inc');
include_once($relPath.'pg.inc');
include_once($relPath.'theme.inc');

maybe_redirect_to_external_faq();

output_header('Beginning Proofreaders\' FAQ', NO_STATSBAR);
?>

<h1>Beginning Proofreaders' <br> Frequently Asked Questions (FAQ)</h1>
<h3 align="right">Version 1.7, released May 27, 2004 </h3>

<p> The purpose of this FAQ is to provide answers to common questions that new people
    joining us at the Distributed Proofreaders web site have asked.
    Obviously not all questions can be included here.  If you don't find an answer here,
    you can look in our
    <a href="faq_central.php">other documentation
    pages</a> or email us at <a href="mailto:<?php echo $general_help_email_addr; ?>">DP Help</a>.
</p>
<table border="0" cellspacing="0" width="100%">
  <tbody>
    <tr>
      <td width="1" bgcolor="silver">&nbsp;</td>
      <td bgcolor="silver" align="left"><font size="+2"><b>Contents</b></font></td>
    </tr>
    <tr>
      <td width="1" bgcolor="silver">&nbsp;</td>
      <td bgcolor="white" align="left">
      <ol>
        <li><a href="#1">What is Distributed Proofreaders?                     </a></li>
        <li><a href="#2">What is Project Gutenberg?                            </a></li>
        <li><a href="#3">Why do we pick the books that we do?                  </a></li>
        <li><a href="#4">How can I help?                                       </a></li>
        <li><a href="#5">How do I handle ...?                                  </a></li>
        <li><a href="#6">How do I contact ...?                                 </a></li>
        <li><a href="#7">What is the entire process for creating an etext?     </a></li>
        <li><a href="#8">How can I get copies of the etexts I've worked on?    </a></li>
        <li><a href="#9">How can I get copies of other Gutenberg etexts?       </a></li>
        <li><a href="#10">I think I messed something up (did something wrong),
                          how can I fix it?                                    </a></li>
        <li><a href="#11">I'm having trouble on the webpage trying to ...
                          Log in/Proofread a page/Get a new page                   </a></li>
      </ol>
      </td>
    </tr>
    <tr>
      <td width="1" bgcolor="silver">&nbsp;</td>
      <td bgcolor="silver">&nbsp;</td>
    </tr>
  </tbody>
</table>

<h3><a name="1">1. What is Distributed Proofreaders? </a></h3>
Distributed Proofreaders is an effort to support <a href="
<?php echo $PG_home_url; ?>">Project Gutenberg</a>, and a recognized affiliated site of
Project Gutenberg.  The basic concept is that our website software allows several
proofreaders to be working on the same book at the same time, each proofreading on
different pages.  This will significantly speed up the proofreading process.

<h4>How it works:</h4>
<ol compact>
  <li>This website uses online software and databases to create a "library".
  <li>People ("content providers") scan books and upload the scanned images into this
      library.
  <li>People like you ("proofreaders") choose a project ("book") to work on today.
  <li>The website then shows you a webpage containing the scanned image of one page and
      the text from that image (as produced by OCR software). This allows you to easily
      compare the scanned text to the image of the page, so you can note the differences
      and fix them.
  <li>You read the text, and correct it to match the page image.
      Basically fixing OCR errors, and marking things like bold or italic text,
      footnotes, etc.  according to our guidelines (so we all mark them the same way).
  <li>When done with that page, you save the page, and then either request
      another page to proofread or quit for the day.
  <br>Note that, <i>at the same time</i>, others will be working on other pages from
      this book, or from different books.  Each proofreader does just a bit (we suggest
      "a page a day"), but working together we can get a lot of books done! [In 2004, we
      average 300-400 proofreaders participating each day from countries all over the
      world, and we finish 4000-7000 pages per day. That's about 4 pages every minute of
      every day!]
  <li>The site stores that proofread page in our database for the next round.  (Each book
      goes through two rounds of proofreading, to try to catch all errors in the text.)
  <li>When all the pages in a book have been proofread, a "post-processor" does the
      finishing work of getting this book ready: combining all the pages into one big
      file, making sure the markings are consistent, etc., and one last check for
      errors.
  <li>Finally the book is submitted to the Project Gutenberg archive, and is posted on
      mirror sites all over the world, freely available for anyone to read and enjoy.
</ol>

<h3><a name="2">2. What is Project Gutenberg? </a></h3>
<p> Michael Hart founded Project Gutenberg in 1971. His idea was: anything that can be
    entered into a computer can be reproduced indefinitely. This led to the concept of
    entering books into computers and sharing these books with the whole world.  </p>
<p> These Electronic Texts (E-texts) would be made available in the simplest, easiest to
    use form. This means "Plain Vanilla ASCII." Italics, underlines, and bolds would be
    converted to ASCII. In the same vein, the books selected would be those that
    appealed to the greatest number of people possible. Due to copyright laws, it is
    only legal to do this with older books (in general, copyrighted before 1923). As a
    result, Project Gutenberg is mostly comprised of the "Classics." </p>
<p> You can read more about the history of Project Gutenberg <a
    href="<?php echo $PG_history_url; ?>">here</a></p>

<h3><a name="3">3. Why do we pick the books that we do? </a></h3>
<p> The Project Managers pick whatever books we can find. Due to US
    copyright laws, we are severely limited in the books we are allowed to work with. We
    go to Used & Rare bookshops and scour the Internet websites &amp; auctions.
    We check out rare books from libraries and scan them.  We obtain page images from other archive sites. We try to find
    books that we think people would enjoy reading and that we can find at an acceptable
    price.  </p>

<p> Before selecting a book to convert to an etext, we check
    <a href="<?php echo $PG_gutindex_url; ?>">Project Gutenberg's list</a>
    (to make certain that it hasn't already been done) and we check
    <a href="<?php echo $PG_in_progress_url; ?>">David's In-Progress List</a>
    (to make certain that it isn't being done by someone else). </p>

<p> In summary, <i>we do whatever books people provide to us (that we legally can).</i>
    If you have a book that you would like to see done (and it is copyright cleared)
    we can probably do it (with your help).
    Contact us at <a href="mailto:<?php echo $general_help_email_addr; ?>">DP Help</a>.
    or see the
    <a href="<?php echo $forums_url; ?>/viewforum.php?f=9">
    "Content Providers" Forum</a>. </p>

<h3><a name="4">4. How can I help? </a></h3>
<p> The process of creating an etext is a long one.</p>

<p>Distributed Proofreaders was set up to make that go faster, by letting you help the
    Project Managers by proofreading pages in their books. If you have not already done
    so, click on the "Register" link and make an account. This enables you to select an
    available book and <b>proofread a few pages</b>.  We encourage people to try to do
    at least "a page a day", but any work done is greatly appreciated and goes a long
    way toward assisting in creating etexts.  This is the way most people help.  </p>

<p>If you really catch Distributed Proofreading fever, you may want to <b>become a Project Manager</b>.
    Project managers mainly shepherd a project ("book") through the uploading,
    proofreading and post-processing processes on this website. Sometimes they do most of
    the tasks themselves; sometimes they coordinate others who are working on the tasks.</p>

<p>If you think that being a Project Manager is for you, Read the <a href="
    pm-faq.php">Project Manager's FAQ</a>.  (We do
    have experienced Project Managers who will mentor you in this process.) When you
    feel ready, contact us at <a href="mailto:<?php echo $general_help_email_addr; ?>">DP Help</a>. </p>

    <form action="https://www.paypal.com/cgi-bin/webscr" method="post">
      <input type="hidden" name="cmd" value="_xclick">
      <input type="hidden" name="business" value="<?php echo $PG_paypal_business_id; ?>">
      <input type="image" src="http://images.paypal.com/images/x-click-butcc-donate.gif"
      align="right" name="submit" alt="Make payments with PayPal - it's fast, free and secure!">
    </form>
<p> If you want to do more for the site, but don't have the time, or inclination, to
    become a Project Manager, you might consider <b>making a donation</b>. Funding for
    the site comes entirely from <?php echo $site_funder; ?> and the Project Managers, and voluntary
    donations.
    See the "donate" button on our
    <a href="..">main page</a> if you wish to make a
    tax-deductable donation. Or here! </p>

<p> You can also <b>donate books</b> (Public Domain) by shipping them to us for scanning
    (better if they do not need to be returned). You can also scan the books and send us
    the images (best if you want to keep the book). We would prefer it if you would
    clear the books first with Project Gutenberg before scanning and sending us the
    images.  Please refer to the <a
    href="cp.php">Content Provider's
    FAQ</a> for more details on clearing and scanning books. </p>

<p> So if you want to do more than just proofreading, you can also help by taking on any
    of the following roles:
    <ul compact>
      <li><b>Content Provider</b>.  Does any or all of the following tasks:
        <ol compact>
           <li>Find a suitable (non-copyright) book to proofread.
           <li>Obtain copyright clearance for the book.
           <li>Run each page of the book through a scanner.
           <li>Process each page image through OCR (optical Character Reader) software.
           <li>Run pre-processing software on the OCR'd file to fix common problems.
           <li>Upload the page image files and OCR'd text files to the DP website.
        </ol>
      <li><b>Project Manager</b>.  See discussion above.
      <li><b>Post Processor</b>.  Does all the finishing work to take a project from a set
          of proofread pages into a combined etext file suitable for adding to the
          Project Gutenberg archive.  Combines all the pages into one big file, deals
          with words or paragraphs split across pages, moves footnotes &amp; sidenotes
          to the proper place, and generally makes sure that all the proofreaders were
          consistent in the way they proofread the text, and then finally sends it on to
          Project Gutenberg.
      <li><b>Website Help</b>.  We always welcome people to help in the work of
          maintaining and improving this website. Programmers (PHP, mySQL and some Java
          Script) who can work on the website software, beta testers to check out new
          versions, document writers to help with our documentation are all needed.
          Contact <a href="mailto:<?php echo $general_help_email_addr; ?>">DP Help</a> if you
          you would like to help with any of these tasks.
    </ul>
<p> You can do any of these entirely on your own, or you can work together with others to
    do the tasks.  Most of our projects are done by a group of people working together.

<h3><a name="5">5. How do I handle ...?  </a></h3>
<p> There are no set "Rules" enforced by Project Gutenberg, but in order to allow the
    distributed proofreading to work, we have written up our own 
    <a href="proofreading_guidelines.php">Proofreading Guidelines</a> and <a href="formatting_guidelines.php">Formatting Guidelines</a>.
    Please read these and any project comments that a project manager may have provided
    before starting to proofread.  The main goal is to preserve as much formatting as
    possible, marked the same way, while making the etext readable on a computer. If you
    are a new proofreader it may be helpful to print out a copy of our 2-page summary,
    the <a href="proofing_summary.pdf">Handy Proofreading
    Guide</a>, and keep it handy while proofreading. This covers the basics of
    proofreading. </p>
<p>Also, some of our projects are marked "Beginners only".  These are books that are
    straightforward, without complex proofreading issues.  It's a good idea to choose
    one of these books when you first start proofreading. </p>

<h3><a name="6">6. How do I contact someone ...? </a></h3>
<!--
<ul compact>
  <li>about a question on a specific book:
  <li>about providing material to be proofread here:
  <li>about becoming a Project Manager for a specific book:
  <li>about helping write the code that operates this website:
  <li>about helping test the code that operates this website:
  <li>about helping update the documentation about this website:
  <li>about problems in getting the proofreading website to work:
</ul>
-->
<p> You can email DP Help at: <a href="mailto:<?php echo $general_help_email_addr; ?>">DP Help</a></p>

<p>Other Project Managers can be reached by clicking on their name on
    the Projects page. Each project has a link to the Project Manager in charge of it.</p>
<p>Also, the "Discuss this book" link on the opening page where you start proofreading the
    book links to the Forum for this book.  That's the best place to contact the Project
    Manager of the book, or to ask questions about the book or ask how to handle some
    proofreading issue in the book.  </p>

<h3><a name="7">7. What is the entire process for creating an etext?  </a></h3>

<p> A book follows a long road to become an etext. These steps are
    covered in more detail in the
    <a href="pm-faq.php">Project Manager's FAQ</a>.
<p> This <a href="DPflow.php">Workflow Diagram</a>
    for the site shows the general flow of material into and out of the site.  </p>


<h3><a name="8">8. How can I get copies of the etexts I've worked on? </a></h3>
<p> On the opening page where you start proofreading a book there is an item "Book
    Completed".  Click on "Yes, I would like to be notified when this has been posted to
    Project Gutenberg." If you do that, when the book is eventually added to the Project
    Gutenberg Archive, you will receive an email notifying you and giving the link to
    download this book.</p>
<p>Also, on the DP main page, there is a weekly list of links to recent books completed
    and sent to Project Gutenberg, books proofread and being post-processed, and books
    currently in the process of proofreading. </p>

<h3><a name="9">9. How can I get copies of other Gutenberg etexts? </a></h3>
<p> You can go to Project Gutenberg's <a
    href="<?php echo $PG_catalog_url; ?>">online catalog</a> and get copies of any etext in
    the library, including the ones done through Distributed Proofreaders. </p>

<h3><a name="10">10. I think I messed something up (did something wrong), how can I fix it? </a></h3>
<p> <i>Don't panic.</i> We all make mistakes. If you think you made a mistake on the last few
    pages of a particular project, go back to the Project Page and note the "DONE" links.
    They reconnect to the last 5 pages you proofread for that project.
    Click on one, and you can make corrections to your proofreading of that page.</p>

<p> If it's earlier than one of these last 5 pages, or you are not sure that you handled
    something correctly, leave a note in the Project Forum for that book (reached from
    the opening page where you started proofreading -- click on "Discuss this Project").
    Give the number of the page you were on (if you remember) and what you did. This
    lets the second round proofreader or the post-processor fix it if it was not correct.
    </p>

<p> Remember that all your proofread pages will be proofread again in the 'second round' of
    proofreading.  Few mistakes make it by both proofreaders undetected!  <i>So just do
    your best and don't worry.</i>  (Second-round proofreading is limited to more experienced
    proofreaders.) </p>

<p>Also, feel free to leave short notes in the pages as you do them, just make certain
    to mark them with an asterisk so that the next proofreader can find them.  Like this: <br>
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <kbd>John Smyth* [**image too faint--I can't tell if it's Smythe or Smith here.] </kbd></p>


<h3><a name="11">11. I'm having trouble on the webpage trying to ... Log in/Proofread a page/Get a new page </a></h3>
<p>
    Almost all browser-related problems (not being able to log in, not seeing the
    proofreading page, not getting a fresh page to proofread after you have proofread your first
    page) can be solved by verifying that your computer is set with the correct time and date and that your 
    browser options are set the following way:
      <ol compact type="a">
        <li>Cookies accepted/on*
        <li>Javascript enabled
         <p>Also, if in your Preferences (<a
          href="<?php echo $code_url; ?>/userprefs.php">located
          here</a>), "Launch in New Window" is set to "Yes", then there is
          the following additional requirement: </p>
        <li>Pop-up Windows allowed* (and make sure they aren't being blocked by another utility)
      </ol>
    Setting these options correctly solves most problems accessing or using the site.
    For specific examples of setting these options for various browsers, check the latest
    info on the DPWiki post, available by
    <a href="<?php echo $Common_browser_problems_URL; ?>">clicking here.</a>

<table border=1 rules="none" width="75%" align="center">
  <tr><td colspan=2> * <i>Security note:</i> for security and privacy reasons, many people have some of
      these options turned off.  They must be turned on for the DP website to work. <br>
      However, they can be limited: </td></tr>
  <tr><td>Cookies:</td>
      <td>DP cookies are only for the DP Website, so rather than setting this
          option to "Accept All Cookies", you can set it to the more restricted option
          "Accept Cookies for the originating website only". </td></tr>
  <tr><td>&nbsp;</td><td>&nbsp;</td></tr>
  <tr><td>Pop-up Windows:</td>
      <td>Most browser Pop-up options or pop-up blocking utilities
          offer an option to list specific sites from which you accept pop-ups.  So
          rather than simply setting it to "Accept all pop-ups", you can set the more
          restrictive option of "Suppress all pop-ups" but include the DP website
          (<?php echo preg_replace(array("|^[a-z]+://|","|/.*$|"),"",$code_url); ?>) in the Exceptions list. </td></tr>
  <tr><td colspan=2> <i>Note:</i> the exact wording of these options will depend on your
          browser.</td></tr>
</table>

<p> The DP site attempts to cooperate with firewalls, web caches and proxies, though if you see the
    <i>'I get the same page to proofread over and over again'</i> difficulty, please email us at <a href="mailto:<?php echo $general_help_email_addr; ?>">DP Help</a>, including your browser details.
    </p>


<hr align="center" width="66%">
<h4>Revision History of this Document</h4>
       05/27/2004 -- Version 1.7: Major changes by pourlean - updated for caching changes
   <br>04/16/2004 -- Version 1.6: Major changes by pourlean - removed all personal email addresses.
   <br>06/16/2003 -- Version 1.5: Additional updates & style revision done by Tim Bonham.
   <br>10/27/2002 -- updated version produced by Charles Franks.
   <br>10/16/2001 -- original version of this document produced by Robert Rowe.

<table border='0' cellpadding='0' cellspacing='0' width='100%' bgcolor='silver'>
<tr><td width='10'>&nbsp;</td>
    <td width='100%' align="center"><font face='verdana, helvetica, sans-serif' size='1'>
        Return to:
        <a href="..">Distributed Proofreaders home page</a>,
        &nbsp;&nbsp;&nbsp;
        <a href="faq_central.php">DP FAQ Central page</a>,
        &nbsp;&nbsp;&nbsp;
        <a href="<?php echo $PG_home_url; ?>">Project Gutenberg home page</a>.
        </font>
    </td>
</tr>
</table>

<?php
