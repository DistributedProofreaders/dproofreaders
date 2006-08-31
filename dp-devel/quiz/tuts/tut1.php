<?
$relPath='../../pinc/';
include_once($relPath.'site_vars.php');
include_once($relPath.'connect.inc');
include_once($relPath.'theme.inc');
include_once($relPath.'prefs_options.inc');

theme(_('Proofreading Tutorial and Interactive Quiz -- Step 1'),'header');
?>
<h1>Tutorial</h1>
<h2>Intro</h2>
<p>
In this tutorial you will be presented extracts from the Proofreading Guidelines. After each part you will be led to a quiz page, where you can try out the newly learned rules.</p>
<p>
Don't be alarmed by the number of changes you'll have to apply in the quizzes. On normal 
easy texts the rate of things to correct is usually much lower than that.
</p>
<h2>Part 1</h2>
<h3>Project Comments</h3>
<p> On the interface page where you start proofreading pages, there is a section called
    "Project Comments" containing info specific to that project (book). <em>Read these
    before you start proofreading pages!</em> If the Project Manager wants you to format
    something in this book differently from the way specified in the Guidelines, that
    will be noted here.  Instructions in the Project Comments <em>override</em> the rules
    in these Guidelines, so follow them.  (This is also where the Project Manager gives
    you interesting tidbits of information about the books, like where the source came
    from, etc.) </p>
<h3>The Primary Rule</h3>
<p> In doing your proofreading, the primary rule to follow is that the final electronic
    book seen by a reader, possibly many years in the future, should <b>accurately
    convey the intent of the author</b>. </p>
<p> So the general rule is <em>"Don't change what the author wrote!"</em>  If the
    author spelled words oddly, leave them spelled that way. If the author wrote
    outrageous racist or biased statements, leave them that way. If the author seems to
    put italics or a footnote every third word, leave them italicized or footnoted. </p>
<p> On the other hand, we do change minor printing items that don't affect the sense of
    what the author wrote.<br>
  <br>
    For example, typesetters in the 1700's &amp; 1800's often inserted a &frac34;ths
    space before punctuation such as a semicolon or comma. Our OCR scanners generally
    read this as a space character. But when proofreading, we remove that space; since
    it distracts modern readers and removing it doesn't affect the meaning of the
    author's words. Typesetters of that time also used the "long s" (&fnof; or &#383;)
    <!-- Need a better html or unicode tag for this! ??? -->
    in words,
    which looks similar to a modern "f".  In English, the "long s" and the regular "s"
    are the same, so we make them all the regular "s". </p>
    
<h3><a name="italics">Italics</a> </h3>
<p> Text that is italicized should have <tt>&lt;i&gt;</tt> inserted at the start and
    <tt>&lt;/i&gt;</tt> inserted at the end of the italics. (Note the "/" in the closing
    symbol.)
   </p>
    <p>
    Punctuation goes OUTSIDE the italics, unless it is an entire sentence or section
    that is being italicized, or the punctuation is itself part of a phrase, title
    or abbreviation that is being italicised. (For instance, the . that mark that
    words in the title of a journal like <i>Phil. Trans.</i> have been abbreviated
    is part of the title for italicisation 
    purposes, and are included within the italic tags, thus:
    <tt>&lt;i&gt;Phil. Trans.&lt;/i&gt;</tt>)
</p>  
    
<h3><a name="bold">Bold Text</a> </h3>
<p> Bold text (text printed in a heavier typeface) should be marked with
    <tt>&lt;b&gt;</tt> inserted before the bold text and <tt>&lt;/b&gt;</tt>after it.
</p>
<a href="../generic/main.php?type=step1">continue</a>
<?
theme("", "footer");
?>