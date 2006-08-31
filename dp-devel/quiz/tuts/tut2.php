<?
$relPath='../../pinc/';
include_once($relPath.'site_vars.php');
include_once($relPath.'connect.inc');
include_once($relPath.'theme.inc');
include_once($relPath.'prefs_options.inc');

theme(_('Proofreading Tutorial and Interactive Quiz -- Step 2'),'header');
?>
<h2>Part 2</h2>

<h3><a name="para_space">Paragraph Spacing/Indenting</a> </h3>
<p> Do not indent the start of paragraphs; instead put a blank line between paragraphs.
</p>

<h3><a name="eol_hyphen">End-of-line Hyphenation</a> </h3>
<p> Where a hyphen appears at the end of a line, join the two halves of the hyphenated
    word back together. If it is really a hyphenated word like well-meaning, join the
    two halves leaving the hyphen in-between. But if it was just hyphenated because it
    wouldn't fit on the line, and is not a word that is usually hyphenated, then join
    the two halves and remove the hyphen. Keep the joined word on the top line, and put
    a line break after it to preserve the line formatting--this makes it easier for
    the 2nd Round Proofreader. If it's the last word in a sentence and is followed by punctuation, then
    carry that punctuation onto the top line, too.
    </p>
    
<p>
<i>Em-dashes & long dashes</i>. These serve as separators between words--sometimes for emphasis like this--or when a speaker gets a word caught in his throat--! 
Proofread these as two hyphens. Don't leave a space before or after, even if it looks like there was a space in the original book image. 
<br>
Note: If a dash appears at the start or end of a line of your OCR'd text, join it with the other line so that there are no spaces or line breaks around it. Only if the author used a dash to start or end the paragraph or line of poetry or dialog should you leave it at the start or end of a line. 
</p>
    
<h3><a name="eop_hyphen">End-of-page Hyphenation</a> </h3>
<p> Leave the hyphen at the end of the last line, but mark it with a <tt>*</tt> after
    the hyphen so the post-processor will notice it. On pages that start with part of a
    word from the previous page, place a <tt>*</tt> before the word.</p>
    

<a href="../generic/main.php?type=step2">continue</a>

<? theme('','footer') ?>