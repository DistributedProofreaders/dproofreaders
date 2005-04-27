<?
$relPath='../pinc/';
include($relPath.'v_site.inc');
include($relPath.'connect.inc');
include($relPath.'theme.inc');
new dbConnect();
$no_stats=1;
theme('Post-Processing Verification Guidelines','header');
?>

<div style="margin-left: 5%; margin-right: 5%;">

<h1>Post-Processing Verification Guidelines</h1>


<h2>Contents</h2>

<h3><a href="#gen">General Procedures</a></h3>
<h3><a href="#rev">Requests to Revise and Resubmit</a></h3>
<h3><a href="#text">Specific Procedures: Text Version</a></h3>
<h3><a href="#html">Specific Procedures: HTML Version</a></h3>
<h3><a href="#track">Tracking PP Progress</a></h3>


<hr>


<h2><a name="gen">General Procedures</a></h2>

<ol>
<li>Make all the checks you would make on a text you are post-processing.</li>

<li>Keep track of all the corrections you make.</li>

<li>Send the PPer a feedback note describing the corrections you made, and providing helpful hints for future work.</li>

<li>Upload the project to PG.</li>
</ol>

<hr>

<h2><a name="rev">Requests to Revise and Resubmit</a></h2>

<p>
Sometimes a PPVer will decide to ask the PPer to revise and resubmit. This is an OPTION. You do not have to do this. Usually revise and resubmit cases fall into one of three scenarios:
</p>

<ol>
<li><b>The text file is in very poor shape</b>. Sometimes a first-timer has completely missed the point of PPing, submitting a file with no formatting at all, or with dozens of gutcheck or spellcheck errors, etc. <i>If you feel that it would be faster to PP the text yourself from scratch than to fix up what has been submitted</i>, this is a case for requesting a resubmit. I find that these cases are rather rare.</li>

<li><b>The HTML file is in poor shape</b>. For example, the validator lists numerous errors, or the image sizes are much too large (<a href="">see below</a>). <i>If you feel it would be faster to generate the HTML version yourself rather than to correct what has been submitted</i>, this is a case for requesting a resubmit. This scenario occurs rather often with newbie HTML producers, but rarely occurs a second time; the PPer is usually simply unaware of requirements for HTML.</li>

<li><b>The required HTML file is absent</b>. The PPVer can of course go ahead and create the HTML if they wish. If the PPVer does not wish to do this, then this is a case for requesting a resubmit. This scenario occurs rather often. Sometimes the PPer simply overlooks the instruction to create an HTML version. Sometimes they do not have the knowledge to do it, in which case they can be encouraged to find another DPer to do it for them (an "HTML partner").</li>
</ol>

<p>
<b>Note:</b> Currently we do not require an HTML version for every project; an HTML version is required only when explicitly stated in the project comments.
</p>

<h3>How to request a revise and resubmit:</h3>

<ol>
<li>Send an email to <? echo $db_requests_email_addr; ?> to request that the project be returned to the PPer's PP queue, so that they can revise and upload again.</li>

<li>Send a private message or email to the PPer letting them know to expect the project to show up in their queue again, explaining the corrections that need to be made before re-uploading for PPV, and stating that if they do not wish/do not have time to revise, they can return the project to the PP queue so that someone else can work on it. Try to be as tactful and helpful as possible. Point them to posted projects to show them what the final product is supposed to look like. Provide links to relevant parts of the PP guidelines which they overlooked.</li>
</ol>

<hr>

<h2><a name="text">Specific Procedures</a></h2>

This is what I do, in the order I do it. Feel free to follow whatever procedure works best for you, using your preferred tools. I do not provide great detail, since this is more or less a repetition of the PP checklist.


<h3>Specific Procedures: Text version</h3>

<ol>

<li>Check project comments/forum discussion for specific project requirements or for specific problems noted in the text during proofreading.</li>

<li>Check for end-of-line spaces. </li>

<li>Check for extraneous or wrong characters:<br />
e.g.: ~ ` @ # $ % ^ & + = | \ « » </li>

<li>Check for comma/period error.</li>

<li>Check for extra period after lowercase a (gutcheck doesn't flag this)</li>

<li>Spellcheck/scanno check.</li>

<li>Hyphen check.</li>

<li>Check for two spaces between words and at ends of sentences.</li>

<li>Check footnotes for proper formatting/placement/numbering.</li>

<li>Check illustrations for proper formatting/placement, and verify that the list of illustrations, if any, matches the illustrations marked in the text.</li>

<li>Check that poetry is indented at least two spaces.</li>

<li>Check ellipses for formatting.</li>

<li>Check that any fractions are formatted properly.</li>

<li>Check that thought breaks (rows of asterisks) are consistent.</li>

<li>Check that chapter spacing is consistent and that all chapter headings are present.</li>

<li>Check general formatting of title page info, leaving in publication year and publisher info.</li>

<li>Gutcheck</li>
</ol>

<hr>

<h3><a name="html">Specific Procedures: HTML version</a></h3>

<p>
<b>Note:</b> Currently we do not require an HTML version for every project; an HTML version is required only when explicitly stated in the project comments.
</p>

<p>
PG's guidelines on HTML can be found here: http://gutenberg.net/faq/H-4
</p>

<ol>
<li>Run through validator at http://validator.w3.org/. Make corrections if there are only a few and it's easy to do. Otherwise, you may decide to return the project to the PPer for revision.</li>

<li>Run HTML Tidy. If you are not experienced with HTML Tidy, be aware that if you set Tidy to "repair" the code rather than just give you a list of warnings/errors, you may get unexpected results. Address any warnings/errors. Run through validator again.</li>

<li><a name="imagesize"></a>Check image size. If total file size (text, html, images) is under 1MB, image size isn't critical. If total file size is over 1MB, 50k is the suggested maximum for each image file. If images are excessively large, return the project to the PPer for revision (unless you would rather resize the images yourself).</li>

<li>Check that all images referenced in the HTML are actually present.</li>

<li>Check that all images and directories are named in lowercase, and referred to in lowercase in the HTML.</li>

<li>Check all links in the table of contents to make sure they work. If possible, check all remaining links.</li>

<li>Current recommendation is that background color and absolute font size and font family not be forced; preference is for white as a background color and relative font sizes. However, this is a gray area. Use your judgment: if the background color or font is really hard to read, contact the PPer to discuss changing them. Otherwise, try submitting to PG as is.</li>
</ol>

<hr>

<h2><a name="track">Tracking PP Progress</a></h2>

<p>
After a PPer has completed at least 8 projects, if their work is of consistent high quality it may be time to allow them to submit directly to PG and to become PPVers themselves. If you have worked with the same PPer through several projects and feel they are ready to be "promoted", send an email to <a href="mailto:<? echo $general_help_email_addr; ?>">DP Help</a> to this effect.
</p>

</div>

    
<?
theme('','footer');
?>
