<?
$relPath='../../pinc/';
include_once($relPath.'site_vars.php');
include_once($relPath.'connect.inc');
include_once($relPath.'theme.inc');
include_once($relPath.'prefs_options.inc');

theme(_('Proofreading Tutorial and Interactive Quiz -- Step 5'),'header');
?>
<h2>Part 5</h2>

<h3>Poetry/Epigrams</h3>
<p> Mark poetry or epigrams so the post-processor can find the poetry more quickly. Insert a
    separate line with <tt>/*</tt> at the start of the poetry or epigram and a separate
    line with <tt>*/</tt> at the end. </p>

<p> Some poems have some lines that are indented relative to the others. This is usually 
    for one of two reasons:  </p>

<p> 1) The poet wrote them that way, with some lines more indented than others. </p>

<p> 2) The printer ran out of page-width to hold an unsually long line and inserted a 
    line-break, with the trailing fragment moved to the next line and indented. </p>

<p> Type 1), reflecting the author's intent (and often also the internal structure of the 
    poem's rhythmic or rhyming schemes), we keep, by adding enough spaces in front of 
    the lines with extra indenting to make it resemble the original. </p>

<p> Type 2), reflecting the printer's attempts to save the expense of wider paper, 
    we undo, rejoining the broken fragment of the line back with the beginning of it, 
    all on one long line. (Such a line may have to be rebroken during PPing, but that's 
    for the PP to determine, not the proofreader). </p>

<p> How do you tell type 1) (author's indenting, keep) from type 2) (printer's indenting, undo)? 
    There are some common patterns you can use. Not all will apply in all cases. </p> 

<p> Lines with type 1) indents (author's, keep) usually start with an upper case letter. 
    Often lines that rhyme with each other are indented a similar amount (you may for instance 
    get every second line rhyming, so that lines 2 and 4 rhyme, and they are indented, while 
    lines 1 and 3, which may or may not rhyme, are not indented. This is a very common pattern, 
    but there are many others). </p>

<p> Lines with type 2) indents (printer's, rejoin) usually start with a lower case letter, 
    and rather than occurring at regular places in the metre of the poem, as type 1) indents
    often do, will appear randomly, depending on the lengths of the words in the line.</p> 

<p> If the poetry was centered on the printed page, don't bother trying to center lines of 
    poetry during proofreading; that won't work for an e-book viewed with many different
    screen sizes. </p>



<h3>Block Quotations</h3>
<p> These are long quotations (typically several lines) included in the text. They are
    often (but not always) printed more narrowly (with wider margins) or in a smaller font
    size--sometimes but not always, both.  Mark block quotations so the post-processing tools can 
    automatically indent them and rewrap them by surrounding them with <tt>/#</tt> and <tt>#/</tt>. 
    Do not indent the text in a block quote, and, just as when proofreading ordinary text, 
    leave the line breaks as they are in the image (hyphenated end-of-line words should still be rejoined) 
    for the convenience of proofreaders in later rounds, or the post-processor 
    (who may also want to check the text against the image). </p>
<a href="../generic/main.php?type=step5">continue</a>

<?
theme("","footer")
?>
</body>
</html>