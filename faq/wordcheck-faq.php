<?php
$relPath = '../pinc/';
include_once($relPath.'base.inc');
include_once($relPath.'faq.inc');
include_once($relPath.'wordcheck_engine.inc');
include_once($relPath.'theme.inc');

maybe_redirect_to_external_faq();

output_header('WordCheck FAQ', NO_STATSBAR);
?>
<h1>WordCheck FAQ</h1>

<h2>Contents</h2>
<h3>General Questions</h3>
<ul>
  <li><a href="#new_interface">What's up with the new spellcheck interface?</a></li>
  <li><a href="#good_bad_words">What are 'Good', 'Bad', and 'Flagged' words?</a></li>
  <li><a href="#misspellings_from">Where do Flagged words come from?</a></li>
  <li><a href="#example">Can you give me a simple example of how the levels work to flag words for the proofreader to correct or accept?</a></li>
  <li><a href="#capitalization">How does capitalization affect the word lists?</a></li>
</ul>
<h3>Proofreader Questions</h3>
<ul>
  <li><a href="#why_spellcheck">Why should I use a spell-checker? I'm a good speller!</a></li>
  <li><a href="#when_wordcheck">Should I run WordCheck before or after I "manually" proofread a page?</a></li>
  <li><a href="#ua_button">What's the "Unflag All &amp; Suggest" button (<img src="<?php echo $code_url; ?>/graphics/Book-Plus-Small.gif" border="0">) and what does it do?</a></li>
  <li><a href="#need_aw">Do I have to hit the Unflag All button for every word on the page?</a></li>
  <li><a href="#ua_diff">Why don't all Flagged words have an Unflag All button?</a></li>
  <li><a href="#wrong_ua">I hit Unflag All for a word but it was wrong - what do I do now?</a></li>
  <li><a href="#ua_undo">I hit Unflag All but didn't mean to, can I undo it?</a></li>
  <li><a href="#add_word_to_dict">How do I get a word added to the project dictionary?</a></li>
  <li><a href="#check_against_diff_lang">How can I check the page against the dictionary for a different language?</a></li>
</ul>
<h3>Project Manager Questions</h3>
<ul>
  <li><a href="#site_word_lists">How do I view Site Word Lists?</a></li>
  <li><a href="#project_word_lists">How do I view Project Word Lists?</a></li>
  <li><a href="#site_dictionaries">What dictionaries are installed on the site?</a></li>
  <li><a href="#add_another_language">Can I add additional language dictionaries to WordCheck/spellcheck?</a></li>
  <li><a href="#site_patterns">What are site patterns?</a></li>
  <li><a href="#manage_word_lists">What do I have to do? How do I manage project words?</a></li>
  <li><a href="#manage_suggestions">Can I view all proofreader suggestions at once or do I have to do it project by project?</a></li>
  <li><a href="#wordlist_value">Why is it important to define project-specific lists?</a></li>
  <li><a href="#wordlist_precedence">What happens if words appear on both Good and Bad word lists?</a></li>
  <li><a href="#wordlist_multi_languages">How do the site-wide Good/Bad Word Lists behave when more than one language is selected?</a></li>
  <li><a href="#clone_project">What happens to the word lists when a project is cloned?</a></li>
  <li><a href="#what_is_a_word">What counts as a "word" in WordCheck?</a></li>
  <li><a href="#repeated_rounds">What do retreads/repeats/second passes do to the proofreader suggestions?</a></li>
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
  <li>Instead of a drop-down box, Flagged words are displayed in a text box for direct editing.
  <li>The standard interface now shows the page image beside the spellcheck page for direct comparison to the original text.</li>
  <li>Page text is still checked against the dictionaries for all project languages. In addition the user has the ability to select additional languages to check the page against, useful if an English-only project has a page with a long quote in French for example.</li>
  <li>Each project has 'good' and 'bad' word lists that are used when determining words to flag in the interface. Good words are words that are valid for the project even though they are not found in the dictionary. Such words will often include proper nouns of people or places used frequently. Good words can be thought of as a project-specific dictionary. Bad Words are words that should be flagged for a project even though they may be found in the dictionary. These words might include common project-specific stealth scannos. Both the Good and Bad Word Lists are managed by the Project Manager.</li>
  <li>Misspelled words have an "<a href="#ua_button">Unflag All &amp; Suggest</a>" button (<img src="<?php echo $code_url; ?>/graphics/Book-Plus-Small.gif" border="0">) next to them. The button is used to indicate that the word matches the image. Once clicked all identically spelled words on the page are also accepted as correct. After a word has been modified, the Unflag All button for that word will become disabled (<img src="<?php echo $code_url; ?>/graphics/Book-Plus-Small-Disabled.gif" border="0">).</li>
  <li>Words that are flagged by proofreaders as accepted via the <a href="#ua_button">Unflag All</a> button are added to a file for review by the Project Manager. Commonly unflagged words can be added to the 'good' word list.</li>
</ul>
<p>The new interface has been relabeled as WordCheck to identify the broader scope of the tool.</p>

<h3><a name="good_bad_words"></a>What are 'Good', 'Bad', and 'Flagged' words?</h3>
<p>The WordCheck interface is designed to help proofreaders catch differences between the page image and the page text. Often when the OCR software identifies the word incorrectly the word becomes misspelled and can be caught by a spell checker. Other times the OCR software incorrectly identifies a word in the image but the resulting text is a valid word. These words are still wrong despite being valid words. The team has decided to use the Good/Bad nomenclature to better reflect the intent of the WordCheck interface - to help the proofreader match the image and the text, rather than use an inaccurate label like 'misspelling'.</p>
<p>After WordCheck has processed words at the various levels it comes up with a final set of Bad words to present to the user for validation or correction. These words are called Flagged words as they have been flagged by the system for closer inspection.</p>

<h3><a name="misspellings_from"></a>Where do Flagged words come from?</h3>
<p>Flagged words can come from a variety of sources. These sources originate from one of three levels:</p>
<ul>
  <li>World - misspellings as determined by an external spell-checker and dictionaries</li>
  <li>Site - words identified by site administrators as common stealth scannos</li>
  <li>Project - words specified by the project manager as valid (Good Words List) or possible stealth scannos (Bad Words List)</li>
</ul>
<p>Each level takes precedence over the level before it. Words identified as Bad at the World level (by an external spell-checker) but are valid at the Project level (project Good words) will not be flagged. This allows the person closest to the text more control over what is flagged: Project Managers can adjust the Good and Bad Words Lists at the project level. Site administrators can manage Bad Words commonly found as stealth scannos at the Site level. Spellcheckers and other external validators can be used to determine Bad Words at the World level.</p>

<h3><a name="example"></a>Can you give me a simple example of how the levels work to flag words for the proofreader to correct or accept?</h3>
<p>To help illustrate how the WordCheck system works, consider the following pseudo-project.</p>
<ul>
  <li>Name: A Description of West Texas Towns</li>
  <li>Languages: English</li>
  <li>Good Words List: Lubbock Levelland Muleshoe Plainview Littlefield</li>
  <li>Bad Words List: fiat</li>
</ul>
<p>Now lets consider the following OCR'd text:</p>
<blockquote><span class="mono">Lubbock is a town of many things: arid fiat 1and, grid-like roads, arid the infamous tumbleweed.</span></blockquote>
<p>When a proofreader selects to WordCheck the text, WordCheck evaluates the text at three levels: World, Site, and Project. At each level words are added or removed from the Flagged word list in order to determine the words to be flagged in the page text for the proofreader to evaluate. Here's an example of how the "flagging" process works, level by level.</p>

<p><b>World</b></p>
<p><i>Current list of Flagged words entering level:</i> <i>none</i></p>
<p>At the World level, the text is run through an external spell-checker (such as aspell) using the dictionaries of the project's Primary and Secondary (if specified) languages. In this case the text would be checked against the English dictionary. The results depend on the particulars of the spell-checker and dictionary, but lets assume that the following words are flagged as misspelled or Bad: <span class="mono">Lubbock</span> and <span class="mono">tumbleweed</span></p>
<p><i>Current list of Flagged words leaving level:</i> <span class="mono">Lubbock tumbleweed</span></p>

<p><b>Site</b></p>
<p><i>Current list of Flagged words entering level:</i> <span class="mono">Lubbock tumbleweed</span></p>
<p>At the Site level, the text is checked for possible stealth scannos, that is OCR software errors which resulted in valid/correctly spelled, but yet incorrect words. In addition, words may be checked against a series of <a href="#site_patterns">patterns</a> that are frequently incorrect such as a word containing both alphabetic and numeric characters. In the text above, the following would be flagged as Bad: <span class="mono">arid</span> (a common stealth scanno) and <span class="mono">1and</span> (matches a suspicious pattern).</p>
<p><i>Current list of Flagged words leaving level:</i> <span class="mono">Lubbock tumbleweed arid 1and</span></p>

<p><b>Project</b></p>
<p><i>Current list of Flagged words entering level:</i> <span class="mono">Lubbock tumbleweed arid 1and</span></p>
<p>The Project level allows the Project Manager to have more control over which words are considered Good and Bad. At this level the Flagged words are compared to the project's Good Words List. Any words found on the project's Good Words List are assumed to be correct and are removed from the page's list of Flagged words. This would result in <span class="mono">Lubbock</span> being removed from the Flagged words for this page.</p>
<P>Also at this level, the text is compared against the project's Bad Words List. Any words in the text that are found on the project's Bad Words List are added to the list of Flagged words for this page. For this example, <span class="mono">fiat</span> is added to the list.</p>
<p><i>Current list of Flagged words leaving level:</i> <span class="mono">tumbleweed arid 1and fiat</span></p>

<p>The final list of Flagged words would be presented to the user and prompt the user to correct or accept them. The proofreader might click the <a href="#ua_button">Unflag All</a> button (<img src="<?php echo $code_url; ?>/graphics/Book-Plus-Small.gif" border="0">) next to <span class="mono">tumbleweed</span> to mark it is valid for this page. The next time the Project Manager generates suggestions from the Accepted Words list, <span class="mono">tumbleweed</span> will show up for possible inclusion on the Good Word List.</p>
<p>Because <span class="mono">arid</span> is a Site-level Bad word (a stealth scanno in this case), it will not have an <a href="#ua_button">Unflag All</a> button. This will force the proofreader to look closely at all instances. In this situation the first instance of <span class="mono">arid</span> is correct while the second instance of the word is a scanno for the word <span class="mono">and</span>.</p>

<h3><a name="capitalization"></a>How does capitalization affect the word lists?</h3>
<p>Good and Bad words are treated as exact matches and therefore are capitalization specific, for example "Lubbock" and "lubbock" are considered separate words.</p>

<hr>

<h2>Proofreader Questions</h2>
<h3><a name="why_spellcheck"></a>Why should I use a spell-checker? I'm a good speller!</h3>
<p>WordCheck does much more than simply check the text for misspelled words -- it helps detect scannos and other OCR errors. It is intended to flag words which are not in the dictionaries and Good Word Lists, because such words are often situations where the OCR process has confused a letter or word with one that is visually similar. Since it is often visually similar, it is easy for a proofreader to skip over, "seeing" it as the correct word. The <a href="#ua_button">Unflag All button</a> exists for the common case where the word has been correctly transcribed, but isn't in the dictionaries.</p>

<p>The spell checker is also used to flag words which are commonly incorrectly identified by OCR. The classic example is "arid" which is a perfectly good word, but is often a scanno for "and", a much more common word. Another example is "modem", which is very uncommon in books from before the 1960s, but can easily be a scanno for "modern".</p>

<p>The checker will attempt to flag these kinds of situations for the proofreader's attention, so that the proofreader can consider them carefully, and take proper action in each case.</p>


<h3><a name="when_wordcheck"></a>Should I run WordCheck before or after I "manually" proofread a page?</h3>
<p>The answer to this question is entirely up to you.</p>

<p>Some people will like to use WordCheck as a "first pass" through the page text to catch the more obvious OCR errors, and to highlight potential typographical errors and stealth scannos. Some folks believe that finding and fixing those types of errors before they proofread the page in regular text-editing mode eliminates them as a possible source of distraction at finding other errors remaining in the page.</p>

<p>Other people will prefer to proofread the page in text-editing mode first, and then use the WordCheck as a "final pass" through the page to re-check the punctuation and potential stealth scannos one more time. Some folks feel a great deal of satisfaction in finding that any word which WordCheck may flag is actually a "false flag" since they see it as an affirmation of their proofreading skills.</p>

<p>And other proofreaders will prefer other approaches to using WordCheck. Thus, run WordCheck at the time when it best fits into your particular page proofreading method.</p>


<h3><a name="ua_button"></a>What's the "Unflag All &amp; Suggest" button (<img src="<?php echo $code_url; ?>/graphics/Book-Plus-Small.gif" border="0">) and what does it do?</h3>
<p>This button, whose icon shows a book and a plus sign (<img src="<?php echo $code_url; ?>/graphics/Book-Plus-Small.gif" border="0">), provides a way for proofreaders to indicate that the word matches the image. Once clicked the button will cause all identically spelled words to be unflagged, just as if the word had been found in a dictionary or "good word" list. Additionally words for which the button has been clicked are added to a file for the project manager. The project manager can review these unflagged words and add those that occur frequently to the project's Good Word list.</p>

<p>After a word has been modified, the Unflag All button for that word becomes disabled (<img src="<?php echo $code_url; ?>/graphics/Book-Plus-Small-Disabled.gif" border="0">)because the proofreader has decided that the word as shown was not correct. In addition, words are only unflagged for the current wordcheck session and do not persist for the proofreader across wordcheck sessions either for the same or different pages.</p>


<h3><a name="need_aw"></a>Do I have to hit the Unflag All button for every word on the page?</h3>
<p><i>If a Flagged word matches what appears in the scan, you do not have to do anything to it.</i> If, as well as being correct, it is a word that appears several times on this page, or is one that is likely to appear several times in a project (such as a proper name, or technical term), you may optionally choose to press the <a href="#ua_button">Unflag All button</a> next to it, which will a) remove flags from all occurrences of this word on this page for this session of WordCheck mode, and b) add it to a list of candidate project-specific good words available to the project manager.</p>

<h3><a name="ua_diff"></a>Why don't all Flagged words have an Unflag All button?</h3>
<p>Words that have been identified as potential stealth scannos, or on a "bad words" list for any reason, do not have an <a href="#ua_button">Unflag All button</a> to ensure that careful attention is given to each occurrence of such words.</p>

<h3><a name="wrong_ua"></a>I hit Unflag All for a word but it was wrong - what do I do now?</h3>
<p>Don't panic! Hitting the <a href="#ua_button">Unflag All button</a> does not automatically add the word to the project's dictionary, simply suggest it to the Project Manager for inclusion. To correct the word, exit out of WordCheck (by either applying your changes or quitting without applying) and correct the word in the normal text window. Alternatively you can run WordCheck again to correct the word since unflagged words are not kept after the end of a WordCheck session.</p>

<p>If you are worried that the Project manager might add the word to the "good words" list wrongly, you can always send a Private Message indicating what happened. However, Project Managers are responsible for checking that words are actually "good" before adding them to the list.</p>

<h3><a name="ua_undo"></a>I hit Unflag All but didn't mean to, can I undo it?</h3>
<p>There is no way to undo hitting the <a href="#ua_button">Unflag All button</a>, however exiting WordCheck and running it again will accomplish the same thing.</p>

<h3><a name="add_word_to_dict"></a>How do I get a word added to the project dictionary?</h3>
<p>Words can only be added to the project's Good Words List by the Project Manager. The suggested way to encourage the Project Manager to add a word to the dictionary is to use the <a href="#ua_button">Unflag All button</a> in WordCheck to signify that the word is correct, even though it is being flagged. The Project Manager can generate a list of commonly Unflagged words and add them to the Good Words List for the project.</p>

<p>Proofreaders are encouraged to use the project's discussion topic to suggest words for the project's Bad Words List.</p>

<h3><a name="check_against_diff_lang"></a>How can I check the page against the dictionary for a different language?</h3>
<p>When a page is initially checked for word to flag, the text is checked against the dictionaries for all project languages. An additional ad-hoc language dictionary can be used by selecting the language from the drop-down list at the top of the page and clicking the Check button. This will cause the text to be checked against the dictionaries for the project languages in addition to the ad-hoc language. Only one ad-hoc language can be used at a time and a request for another will replace the previous ad-hoc language. Corrections and Unflagged words will be retained between checks against ad-hoc languages.</p>

<hr>

<h2>Project Manager Questions</h2>
<h3><a name="site_word_lists"></a>How do I view Site Word Lists?</h3>
<p>Site-level words are stored in language-specific files.</p>
<p>Site-level Good and Bad word lists are used when calculating Flagged words in a body of text. Here is the current set of such lists:</p>
<?php createWordListTable(get_site_good_bad_word_lists()); ?>

<p>Possible Bad word lists are used to suggest possible Bad words for a Project Manager. Here is the current set of such lists:</p>
<?php createWordListTable(get_site_possible_bad_word_lists()); ?>

<?php
function createWordListTable($word_lists)
{
    // return if there aren't any word_lists
    if (count($word_lists) == 0) {
        echo "<p style='padding-left: 2em'><i>None.</i></p>";
        return;
    }

    // start the table and build the header
    echo "<table style='padding-left: 2em'>";
    echo "<tr>";
    echo "<th>" . _("Name") . "</th>";
    echo "<th>" . _("Number of Words") . "</th>";
    echo "<th>" . _("Last modified") . "</th>";
    echo "</tr>";

    // TRANSLATORS: This is a strftime-formatted string for the date with year and time
    $datetime_format = _("%A, %B %e, %Y at %X");

    // loop through the word lists building rows as we go
    foreach ($word_lists as $word_list_file => $word_list_url) {
        $filename = basename($word_list_file);
        $word_count = count(explode("\n", file_get_contents($word_list_file))) - 1;
        $modifiedString = strftime($datetime_format, filemtime($word_list_file));
        echo "<tr>";
        echo "<td><a href=\"$word_list_url\">$filename</a></td>";
        echo "<td>$word_count</td>";
        echo "<td>$modifiedString</td>";
        echo "</tr>";
    }

    // close the table and we're done
    echo "</table>";
}
?>

<h3><a name="project_word_lists"></a>How do I view Project Word Lists?</h3>
<p>Project words lists are stored under the project directory. They can be viewed from the "Word Lists" line of the project info table. Project word lists can be updated by editing the information for a project.</p>

<h3><a name="site_dictionaries"></a>What dictionaries are installed on the site?</h3>
<p>The following languages have dictionaries installed on the site:</p>
<ul>
<?php
$languages = array_values(get_languages_with_dictionaries());
sort($languages);
foreach ($languages as $language) {
    echo "<li>$language</li>";
}
?>
</ul>

<h3><a name="add_another_language"></a>Can I add additional language dictionaries to WordCheck?</h3>
<p>When a page is checked against the external spell-checker the checker uses dictionaries from the project's languages. There is currently no way for the project manager to specify additional project-wide dictionaries beyond those for the project's (one or two) languages. If a project has only a Primary language, the Project Manager can elect to select a Secondary language for the project to have that language's dictionary used in the spell-checker. Secondary languages are often used by Proofreaders when determining projects to proofread so it is recommended that only projects with significant use of a second language have a Secondary language specified.</p>

<p>Proofreaders can select an ad-hoc language to use on a per-page basis if that page contains text from a non-project language, such as a quote. Project Managers may wish to include such a suggestion in the project instructions and/or in the forum for the project.</p>

<p>Alternatively Project Managers may elect to add words to the project's Good Words List for commonly used words, regardless of the language, that do not appear in the dictionaries for the project's Primary or Secondary languages.</p>

<h3><a name="site_patterns"></a>What are site patterns?</h3>
<p>In addition to the Good and Bad word lists, WordCheck detects suspicious patterns as well. A classic suspicious pattern is a word with one or more digits mixed in with letters, for example: <span class="mono">1and</span>. WordCheck flags these words without an <a href="#ua_button">Unflag All button</a>. Common word-with-digit patterns such as ordinals (1st, 2nd, 3rd) are excluded from this flagging. Patterns are specified site-wide directly in the code.</p>

<p>The ordinal patterns are language-specific. The code currently recognizes the ordinals for English and French and uses them accordingly based on the project languages. Others can be added with code changes.</p>

<h3><a name="manage_word_lists"></a>What do I have to do? How do I manage project words?</h3>
<p>A Project Manager can just do nothing, and let the external spell-checker do everything. But a PM can also define project-specific Good and Bad Words Lists. Such lists can be defined in pre-processing, or defined through on-line tools available from the Edit Project Word List page. These on-line tools can also be used to incrementally modify the previously defined lists, so it is recommended to use the on-line tools at least for a final check. The on-line tools can be used at any time, even during a round, without making the project unavailable. Once the project information with the updated word lists is saved, those lists are immediately used.</p>

<p>Using off-line tools may yield suboptimal reject lists, since it is not guaranteed that the spell-checker version and the dictionary version are identical to the versions used on site. Also, external tools will not know about site- and project-specific Good and Bad Words Lists. For off-line tools, refer to their documentation.</p>

<p>Word lists should contain one word per line. Leading spaces are trimmed, as are trailing spaces and characters after a trailing space. This allows direct copy-and-pasting from the downloaded word lists and the system will trim out any frequencies used in the list.</p>

<p>On line, when a project is loaded, go to the Edit Project Word List window via the Project page or the Project Search page. It has two text boxes, one each for Good and Bad words, and can be edited.</p>

<p>To define a new Good Words List, click on the link "Show words in the project that WordCheck would currently flag" from the Edit Project Word Lists page. This will open a new window listing all words in the text that WordCheck will flag for the proofreader sorted by the frequency those words occur in the text. The time required to open up this page is proportional to the size of the project and to the number of project languages specified. It will take more time to open this page for longer projects with two languages specified compared with shorter projects with one language. You can then either copy-and-paste from the page directly, or select the checkboxes against the words you wish to add and submit the form. Alternatively you can download the complete list with their frequencies for offline analysis, discard words you do not want to be considered Good, and paste it in the Good word text area. The suggestions generated from the dictionary only includes words not accepted in the current configuration, and new words should be added to the current list of words, not replace them. Care should be taken when adding words to the Good Words List not to incorporate frequently misspelled (or mis-OCRd) words into the list.</p>

<p>Another source of Good words is to consult the list of words accepted by the proofreaders via the <a href="#ua_button">Unflag All</a> button in the WordCheck interface. To do this, click on the "Show suggestions from proofreaders" link from the Edit Project Word List page. The "Show suggestions from proofreaders" results list presents the data related to proofreaders' suggestions in a much more "analysis friendly" form than does the related "Good Word Suggestions" file (which can be accessed from the Project Page).  The "Good Words Suggestions" file contains useful "page reference" data, but that file should be used as a supplement to, not as a substitute for, the "Show suggestions from proofreaders" results list.</p>

<p>Bad words are generally possible stealth scannos that occur often for a particular project. Bad Words Lists are managed using techniques similar to those used to manage Good Word lists. The "Show words in the project that are in the site possible bad words file" will list all words in the text sorted by frequency that often exist as stealth scannos.</p>


<h3><a name="manage_suggestions"></a>Can I view all proofreader suggestions at once or do I have to do it project by project?</h3>
<p>A recent WordCheck update allows PMs to manage all proofreader suggestions at once, rather than opening up every project to see if there are suggestions to review. To do this, access the Manage All Proofreader's Suggestions link from the <a href="<?php echo $code_url; ?>/tools/project_manager/projectmgr.php">Project Search</a> page.</p>


<h3><a name="wordlist_value"></a>Why is it important to define project-specific lists?</h3>
<p>One of the most frequently requested improvements (do a search on the task page at <a href="<?php echo $code_url; ?>/tasks.php"><?php echo $code_url; ?>/tasks.php</a> for "dictionary" and "spell") on site over the years has been for the ability to add words to the various dictionaries used by the spell checker.</p>

<p>WordCheck, which effectively replaced the spell checker, provides this capability through the project bad and good lists. A word placed in the project good list will not be flagged, even if it is not recognised by the aspell dictionary. This is exactly the sort of behaviour that is ideal for words that validly appear in your project but not in the standard aspell dictionary, such as proper nouns, names, technical terms and jargon, etc.</p>

<p>Note that if the project good list is NOT populated, WordCheck will operate almost exactly the same as the old spell checker: specifically, names of characters and other such words, correctly OCRd, will all be flagged for attention when there is no need. The utility of WordCheck for proofreaders, in all rounds, will be vastly increased by a bit of simple preparation on the Project Manager's part. This preparation, at a stroke, will remove the vast majority of the false positive flags that have been making in-round spell checking an often tedious and laborious task. Instead of, say, the old experience of only one in twenty Flagged words actually being an OCR error in need of correction, we'd expect that the vast majority of words flagged by WordCheck would probably be errors -- but only if the project good words list is appropriately populated.</p>

<p>This is why pasting in a suitable project good words list is important, and why it's strongly encouraged not only for all new projects, but also all existing projects that have yet to complete the rounds.</p>

<p>The online tools that are available for automatically generating possible contents of these lists are explained above.</p>


<h3><a name="wordlist_precedence"></a>What happens if words appear on both Good and Bad word lists?</h3>
<p>It is possible for words to appear both on a Good and Bad Word List at the same level, such as at the Site or Project level. Bad words are evaluated after Good words so words that appear both on a Good and Bad list at the same level would be listed as Bad. Since the Project level takes precedence over the Site level, a word on the Site Bad Word List can be removed from being Flagged by adding it to the Project's Good Word List.</p>


<h3><a name="wordlist_multi_languages"></a>How do the site-wide Good/Bad Word Lists behave when more than one language is selected?</h3>
<p>When applying word lists, a merged list is formed of words from all applicable languages, including all project languages and any ad-hoc language used in WordCheck. All the words from the site-level Good Word Lists for each language being checked against are combined into a single merged good-words list which is then used as described above. Similarly, the Bad Word Lists for each such language are combined into a single merged bad-words list. For example, in <i>English</i> + <i>French</i> projects, every occurrence of the word "do" will be flagged unless it is included on the project's Good Words List because it is on the Site Bad Words List for French (because it is a common stealth scanno in French, although not in English).</p>


<h3><a name="clone_project"></a>What happens to the word lists when a project is cloned?</h3>
<p>When a project is cloned, the Good and Bad Word Lists are copied to the new project. The Good Word Suggestions file that contains suggestions from proofreaders is not copied to the new project.</p>


<h3><a name="what_is_a_word"></a>What counts as a "word" in WordCheck?</h3>
<p>A "word" is any sequence of letters (with or without accents), digits, or apostrophes, surrounded by any other characters (such as spaces or punctuation). In addition, any of the approved combinations for ligatures (such as [oe]) or diacritics (such as [=a], that represents &#257;) forms part of a word, so that "c[oe]eur" is a single word.</p>
<p>What this means is that words with characters other than those mentioned above will never be flagged in the text (such as commas). That isn't to say that future versions of WordCheck can't be modified/enhanced to include checking for words using a different string of characters, such as other punctuation, as well. While words in the Word Lists with characters other than mentioned above will never be Flagged in the text, there is no downside to including them for when WordCheck can make use of them.</p>
<p>For example, including <span class='mono'>etc</span> on a Word List will match <span class='mono'>etc</span> and <span class='mono'>etc.</span> (notice the period) in the text. Adding just <span class='mono'>etc.</span> (again, notice the period) will not match anything in the text with the current version of WordCheck.</p>

<h3><a name="repeated_rounds"></a>What do retreads/repeats/second passes do to the proofreader suggestions?</h3>
<p>If a project is cycled back through a previous round, the output of the Suggestion from Proofreaders page may give odd results. If the good_word_suggestions.txt file is preserved during the move, previous proofreader suggestions will be retained and may show up on the Suggestion from Proofreaders page if not all suggestions have been added to one of the project's Word List. It is therefore possible for retread projects to list proofreader suggestions for rounds later than the project is currently in. It is also possible for a word that only appears once in the text to show up as being suggested twice. WordCheck will not be affected by this and the PM can safely ignore the earlier data if they so choose.</p>

<?php
