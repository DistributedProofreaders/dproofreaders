<?
$relPath='../pinc/';
include($relPath.'site_vars.php');
include($relPath.'faq.inc');
include($relPath.'connect.inc');
include($relPath.'word_checker.inc');
include($relPath.'theme.inc');
new dbConnect();
$no_stats=1;
theme('Spellcheck FAQ','header');
?>
<style type="text/css">
span.mono
   { font-family: DPCustomMono2,Courier,san-serif; }
</style>

<h1>Spellcheck FAQ</h1>

<h2>Contents</h2>
<h3>General Questions</h3>
<ul>
  <li><a href="#new_interface">What's up with the new spellcheck interface?</a></li>
  <li><a href="#good_bad_words">What's with 'Good' and 'Bad' words?</a></li>
  <li><a href="#misspellings_from">Where do misspelled (Flagged) words come from?</a></li>
  <li><a href="#example">Can you give me a simple example of how the levels work to Flag words for the proofer to correct or accept?</a></li>
  <li><a href="#capitalization">How does capitalization affect the word lists?</a></li>
</ul>
<h3>Proofer Questions</h3>
<ul>
  <li><a href="#why_spellcheck">Why should I use a spell-checker? I'm a good speller!</a></li>
  <li><a href="#aw_button">What's the AW button and what does it do?</a></li>
  <li><a href="#need_aw">Do I have to hit the AW button for every word on the page?</a></li>
  <li><a href="#aw_diff">Why don't all Bad words have an AW button?</a></li>
  <li><a href="#wrong_aw">I hit AW for a word but it was wrong - what do I do now?</a></li>
  <li><a href="#aw_undo">I hit AW but didn't mean to, can I undo it?</a></li>
  <li><a href="#add_word_to_dict">How do I get a word added to the project dictionary?</a></li>
  <li><a href="#check_against_diff_lang">How can I check the page against the dictionary for a different language?</a></li>
</ul>
<h3>Project Manager Questions</h3>
<ul>
  <li><a href="#site_word_lists">How do I view Site Word Lists?</a></li>
  <li><a href="#project_word_lists">How do I view Project Word Lists?</a></li>
  <li><a href="#add_another_language">Can I add additional language dictionaries to the spellchecker?</a></li>
  <li><a href="#manage_word_lists">What do I have to do? How do I manage project words?</a></li>
</ul>

<hr>

<h2>General Questions</h2>
<h3><a name="new_interface"></a>What's up with the new spellcheck interface?</h3>
<p>The previous spellcheck interface had a couple of areas that could have used improvement:</p>
<ul>
  <li>There was no way to specify project-level dictionaries or accepted word lists.</li>
  <li>The suggestions listed for misspelled words was very long, increasing the size of the returned HTML page and was frequently not used.</li>
  <li>In the standard interface the spellcheck page did not show the page image.</li>
</ul>

<p>To address these and other areas, the spellcheck code was revamped to add the following enhancements:
<ul>
  <li>Instead of a drop-down box, misspelled words are displayed in a text box for direct editing.
  <li>The standard interface now shows the page image beside the spellcheck page for direct comparison to the original text.</li>
  <li>Page text is still checked against the dictionaries for all project languages. In addition the user has the ability to select additional languages to check the page against, useful if an English-only project has a page with a long quote in French for example.</li>
  <li>Each project has 'good' and 'bad' word lists that are used when determining misspelled words. Good words are words that are valid for the project even though they are not found in the dictionary. Such words will often include proper nouns of people or places used frequently. Good words can be thought of as a project-specific dictionary. Bad Words are words that should be flagged as misspelled for a project even though they may be found in the dictionary. These words might include project-specific stealth scannos. Both the Good and Bad Word Lists are managed by the Project Manager.</li>
  <li>Misspelled words have an AW button next to them. AW stands for 'Accept Word' and is used to indicate that the word matches the image. Once selected, all identically spelled words on the page are also accepted as correct. After a word has been modified, the AW button for that word will become disabled.</li>
  <li>Words that are flagged by proofers as accepted via the AW button are added to a file for review by the Project Manager. Commonly accepted words can be added to the 'good' word list.</li>
</ul>

<h3><a name="good_bad_words"></a>What's with 'Good' and 'Bad' words?</h3>
<p>The spellcheck interface is designed to help proofers catch differences between the page image and the page text. Often when the OCR software identifies the word incorrectly the word becomes misspelled and can be caught by a spell checker. Other times the OCR software incorrectly identifies a word in the image but the resulting text is a valid word. These words are still wrong despite being valid words. The team has decided to use the Good/Bad nomenclature to better reflect the intent of the spellcheck interface - to help the proofer match the image and the text, rather than use an inaccurate label like 'misspelling'.</p>

<h3><a name="misspellings_from"></a>Where do misspelled (Flagged) words come from?</h3>
<p>Words flagged as misspelled can come from a variety of sources. These sources originate from one of three levels:</p>
<ul>
  <li>World - misspellings as determined by an external spell-checker and dictionaries</li>
  <li>Site - words identified by site administrators as stealth scannos</li>
  <li>Project - words specified by the project manager as valid (Good Words List) or stealth scannos (Bad Words List)</li>
</ul>
<p>Each level takes precedence over the level before it. Words identified as Bad at the World level (by an external spell-checker) but are valid at the Project level (project Good words) will not be marked as misspelled. This allows the person closest to the text more control over what is considered misspelled: Project Managers can adjust the Good and Bad Words Lists at the project level. Site administrators can manage Bad Words commonly found as stealth scannos at the Site level. Spellcheckers and other external validators can be used to determine Bad Words at the World level.</p>

<h3><a name="example"></a>Can you give me a simple example of how the levels work to Flag words for the proofer to correct or accept?</h3>
<p>To help illustrate how the new spellcheck system works, consider the following pseudo-project.</p>
<ul>
  <li>Name: A Description of West Texas Towns</li>
  <li>Languages: English</li>
  <li>Good Words List: Lubbock Levelland Muleshoe Plainview Littlefield</li>
  <li>Bad Words List: fiat</li>
</ul>
<p>Now lets consider the following OCR'd text:</p>
<blockquote><span class="mono">Lubbock is a town of many things: arid fiat 1and, grid-like roads, arid the infamous tumbleweed.</span></blockquote>
<p>When a proofer selects to spellcheck the text, the spellcheck system evaluates the text at three levels: World, Site, and Project. At each level words are added or removed from the misspelled word list in order to determine the words to be flagged in the page text for the proofer to evaluate. Here's an example of how the "flagging" process works, level by level.</p>

<p><b>World</b></p>
<p><i>Current list of Flagged words entering level:</i> <i>none</i></p>
<p>At the World level, the text is run through an external spell-checker (such as aspell) using the dictionaries of the project's Primary and Secondary (if specified) languages. In this case the text would be checked against the English dictionary. The results depend on the particulars of the spell-checker and dictionary, but lets assume that the following words are flagged as misspelled or Bad: <span class="mono">Lubbock</span> and <span class="mono">tumbleweed</span></p>
<p><i>Current list of Flagged words leaving level:</i> <span class="mono">Lubbock tumbleweed</span></p>

<p><b>Site</b></p>
<p><i>Current list of Flagged words entering level:</i> <span class="mono">Lubbock tumbleweed</span></p>
<p>At the Site level, the text is checked for stealth scannos, that is OCR software errors which resulted in valid/correctly spelled, but yet incorrect words. In addition, words may be checked against a series of patterns that are frequently incorrect such as a word containing both alphabetic and numeric characters. In the text above, the following would be flagged as Bad: <span class="mono">arid</span> (a common stealth scanno) and <span class="mono">1and</span> (matches a suspicious pattern).</p>
<p><i>Current list of Flagged words leaving level:</i> <span class="mono">Lubbock tumbleweed arid 1and</span></p>

<p><b>Project</b></p>
<p><i>Current list of Flagged words entering level:</i> <span class="mono">Lubbock tumbleweed arid 1and</span></p>
<p>The Project level allows the Project Manager to have more control over which words are considered Good and Bad. At this level the Flagged words are compared to the project's Good Words List. Any words found on the project's Good Words List are assumed to be correct and are removed from the page's list of Flagged words. This would result in <span class="mono">Lubbock</span> being removed from the Flagged words for this page.</p>
<P>Also at this level, the text is compared against the project's Bad Words List. Any words in the text that are found on the project's Bad Words List are added to the list of Flagged words for this page. For this example, <span class="mono">fiat</span> is added to the list.</p>
<p><i>Current list of Flagged words leaving level:</i> <span class="mono">tumbleweed arid 1and fiat</span></p>

<p>The final list of Flagged words would be presented to the user as misspelled and prompt the user to correct or accept them. The proofer might click the AW button next to <span class="mono">tumbleweed</span> to mark it is valid for this page. The next time the Project Manager generates suggestions from the Accepted Words list, <span class="mono">tumbleweed</span> will show up for possible inclusion on the Good Word List.</p>
<p>Because <span class="mono">arid</span> is a Site-level Bad word (a stealth scanno in this case), it will not have an AW button to force the proofer to look closely at all instances. In this situation the first instance of <span class="mono">arid</span> is correct while the second instance of the word is a scanno for the word <span class="mono">and</span>.</p>

<h3><a name="capitalization"></a>How does capitalization affect the word lists?</h3>
<p>Good and Bad words are treated as exact matches and therefore are capitalization specific, for example "Lubbock" and "lubbock" are considered separate words.</p>

<hr>

<h2>Proofer Questions</h2>
<h3><a name="why_spellcheck"></a>Why should I use a spell-checker? I'm a good speller!</h3>
<p>The "spell-checker" does much more than simply check the text for misspelled words -- it helps detect scannos and other OCR errors. It is intended to flag words which are not in the dictionaries and Good Word Lists, because such words are often situations where the OCR process has confused a letter or word with one that is visually similar. Since it is often visually similar, it is easy for a proofer to skip over, "seeing" it as the correct word. The AW button exists for the common case where the word has been correctly transcribed, but isn't in the dictionaries.</p>

<p>The spell checker is also used to flag words which are commonly incorrectly identified by OCR. The classic example is "arid" which is a perfectly good word, but is often a scanno for "and", a much more common word. Another example is "modem", which is very uncommon in books from before the 1960s, but can easily be a scanno for "modern".</p>

<p>The checker will attempt to flag these kinds of situations for the proofer's attention, so that the proofer can consider them carefully, and take proper action in each case.</p>


<h3><a name="aw_button"></a>What's the AW button and what does it do?</h3>
<p>AW stands for Accept Word and provides a way for proofers to indicate that the word matches the image. Once clicked the button will cause all identically spelled words to be accepted as correct. Additionally these words are added to a file for the project manager. The project manager can review these accepted words and add those that occur frequently to the project's good word list.</p>
<p>After a word has been modified the AW button for that word becomes disabled to prevent accepting the incorrect word as valid. In addition, Accepted Words are only valid for the current spellcheck session and do not persist for the proofer across spellcheck sessions either for the same or different pages.</p>

<h3><a name="need_aw"></a>Do I have to hit the AW button for every word on the page?</h3>
<p><i>If a flagged word matches what appears in the scan, you do not have to do anything to it.</i> If, as well as being correct, it is a word that appears several times on this page, or is one that is likely to appear several times in a project (such as a proper name, or technical term), you may optinoally choose to press the AW (Accept Word) button next to it, which will a) remove flags from all occurences of this word on this page for this session of flagged words mode, and b) add it to a list of candidate project-specific good words available to the project manager.</p>

<h3><a name="aw_diff"></a>Why don't all Bad words have an AW button?</h3>
<p>Words that have been identified as stealth scannos do not have an AW button to ensure that careful attention is given to them.</p>

<h3><a name="wrong_aw"></a>I hit AW for a word but it was wrong - what do I do now?</h3>
<p>Don't panic! Hitting the AW button does not automatically add the word to the project's dictionary, simply suggest it to the Project Manager for inclusion. To correct the word, exit out of the spellcheck interface (by either applying your changes or quitting without applying) and correct the word in the normal text window. Alternatively you can run the spellcheck again to correct the word since Accept Words are not kept across spellcheck sessions.</p>

<h3><a name="aw_undo"></a>I hit AW but didn't mean to, can I undo it?</h3>
<p>There is no way to undo hitting the AW button, however exiting the spellcheck interface and running spellcheck again will accomplish the same thing.</p>

<h3><a name="add_word_to_dict"></a>How do I get a word added to the project dictionary?</h3>
<p>Words can only be added to the project's Good Words List by the Project Manager. The suggested way to encourage the PM to add a word to the dictionary is to use the Accept Word (AW) button in the spelling interface to signify that the word is spelled correctly. The PM can generate a list of commonly Accepted words and add them to the Good Words List for the project.</p>

<p>Proofers are encouraged to use the project's discussion topic to suggest words for the project's Bad Words List.</p>

<h3><a name="check_against_diff_lang"></a>How can I check the page against the dictionary for a different language?</h3>
<p>When a page is intially checked for Bad words, the text is checked against the dictionaries for all project languages. An additional ad-hoc language dictionary can be used by selecting the language from the drop-down list at the top of the page and clicking the Check button. This will cause the text to be checked against the dictionaries for the project languages in addition to the ad-hoc language. Only one ad-hoc language can be used at a time and a request for another will replace the previous ad-hoc language. Corrections and Accepted Words will be retained between checks against ad-hoc languages.</p>

<hr>

<h2>Project Manager Questions</h2>
<h3><a name="site_word_lists"></a>How do I view Site Word Lists?</h3>
<p>Site-level words are stored in language-specific files. Current site-level word lists are:</p>
<ul>
<?
$site_word_lists=get_site_word_lists();
foreach($site_word_lists as $word_list_file => $word_list_url) {
    $filename=basename($word_list_file);
    $word_count=count(explode("\n",file_get_contents($word_list_file)))-1;
    echo "<li><a href=\"$word_list_url\">$filename</a> - $word_count word(s)</li>";
}
?>
</ul>

<h3><a name="project_word_lists"></a>How do I view Project Word Lists?</h3>
<p>Project words lists are stored under the project directory. They can be viewed from the "Word Lists" line of the project info table. Project word lists can be updated by editing the information for a project.</p>

<h3><a name="add_another_language"></a>Can I add additional language dictionaries to the spellchecker?</h3>
<p>When a page is checked against the external spell-checker the checker uses dictionaries from the project's languages. There is no way to have the spell-checker use additional dictionaries beyond those for the project's (one or two) languages. If a project has only a Primary language, the PM can elect to select a Secondary language for the project to have that language's dictionary used in the spell-checker. Secondary languages are often used by Proofers when determining projects to proof so it is recommended that only projects with significant use of a second language have a Secondary language specified.</p>
<p>Proofers can select an ad-hoc language to use on a per-page basis if that page contains text from a non-project language, such as a quote. PMs may wish to include such a suggestion in the project instructions and/or in the forum for the project.</p>
<p>Alternatively PMs may elect to add words to the project's Good Words List for commonly used words, regardless of the language, that do not appear in the dictionaries for the project's Primary or Secondary languages.</p>

<h3><a name="manage_word_lists"></a>What do I have to do? How do I manage project words?</h3>
<p>A PM can just do nothing, and let the external spell-checker do everything. But he can also define project-specific Good and Bad Words Lists. Such lists can be defined in pre-processing, or defined through on-line tools. These on-line tools can also be used to incrementally modify the previously defined lists, so it is recommended to use the on-line tools at least for a final check. The on-line tools can be used at any time, even during a round, without making the project unavailable, and are immediately used.

<p>Using off-line tools may yield suboptimal reject lists, since it is not guaranteed that the spell-checker version and the dictionary version are identical to the versions used on site. Also, external tools will not know about site- and project-specific Good and Bad Words Lists. For off-line tools, refer to their documentation.</p>
<p>Word lists should contain one word per line. A number at the end of a line is discarded (so you can use frequency lists generated by the on-line tools without having to remove manually the frequencies).</p>

<p>On line, when a project is loaded, go to the edit window. It has two (new) text boxes, one each for Good and Bad words, and can be edited.

<p>To define a new Good Words List, click on the link "Generate suggestions from dictionary". This will open a new window listing all words in the text the spell-checker thinks are misspelled sorted by the frequency those words occur in the text. You can then either copy-and-paste from the page directly, or download the complete list with their frequencies. Edit the list, discarding words you do not want to be considered Good, and paste it in the Good word text area. The suggestions generated from the dictionary only includes words not accepted in the current configuration, and new words should be added to the current list of words, not replace them. Care should be taken when adding words to the Good Words List not to incorporate frequently misspelled (or mis-OCRd) words into the list.</p>

<p>Another source of Good words is to consult the list of words accepted by the proofers via the Accept Word button in the spelling interface. To do this, click on the "Generate suggestions from Accepted Words" link. The procedure is the same.</p>

<p>Bad words are generally stealth scannos that occur often for a particular project. Bad Words Lists are managed using techniques similar to those used to manage Good Word lists, although there are currently no tools that will generate a list of Suggested Bad words for you.</p>



<?
theme('','footer');
?>
