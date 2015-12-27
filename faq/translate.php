<?php
$relPath='../pinc/';
include_once($relPath.'base.inc');
include_once($relPath.'theme.inc');
include_once($relPath.'user_is.inc');
include_once($relPath.'misc.inc'); // undo_all_magic_quotes()

undo_all_magic_quotes();

output_header('Site translation', NO_STATSBAR);
?>
<div id='article'><a name="top"></a>
<h1>Site translation</h1>


<h2>Using translations</h2>
<p>The DP code allows the interface to be translated into different languages (ie: locales). The site attempts to determine which language to use based on your browser's settings. If a translation is installed and enabled for the determined language, it will use it. If you wish to override it, you can do so:</p>
<ul>
    <li>For guests, see the 'Set language' form in the right column.</li>
    <li>For registered users, see the 'Interface Language' setting in your <a href='../userprefs.php'>user preferences</a>.</li>
</ul>

<h2>Becoming a translator</h2>
<p>If you want to translate the user interface into a new language, or want to refine an existing translation, contact a <a href='mailto:<?php echo $site_manager_email_addr; ?>'>site administrator</a> for full access to the <a href='../locale/translators/index.php'>Translation Center</a>.</p>

<h2>Languages vs locales</h2>
<p>Many people think of translations as done from one language into another, such as from English into Spanish. But this is an oversimplification: which English -- US? Canadian? British? -- and which Spanish -- Spain? Mexico? Instead, they are done from one localization, such as US English, into another, such as Mexican Spanish. Computers term these localizations locales and they have unique identifiers. For example, US English is en_US and Mexican Spanish is es_MX.</p>

<p>Prior versions of the DP code included this lack of distinction between language and localization. This version uses locales when referring to specific interface language translations in the code. The user interface still uses "language" however.</p>


<?php if(user_is_a_sitemanager()) { ?>

<!--
This section is only shown to admins for two reasons
1) To reduce confusion by volunteers who don't need to see it.
2) To prevent non-admins from seeing the value of $dyn_locales_dir
-->

<hr>

<h2>System Administrators</h2>

<h3>Prerequisites</h3>
<p>The DP code uses GNU gettext to support site translations, so ensure it is installed and PHP is configured to use it.</p>

<p>The PHP gettext library sets the locales per process, not per thread. This means that if your Apache installation is using a threaded MPM, the wrong language might be displayed to the user as threads race calling setlocale() -- see the warning in the <a href='http://php.net/manual/en/function.setlocale.php'>PHP docs for setlocale()</a>. For this reason you should ensure your Apache MPM is set to prefork and not worker or event. You can see what MPM Apache is using via:</p>
<blockquote>
<pre>
# Ubuntu:
# apache2 -V | grep MPM
Server MPM:     Prefork

# CentOS/Redhat
# httpd -V | grep MPM
Server MPM:     Prefork
</pre>
</blockquote>

<h3>System preparation</h3>
<p>In order for a translation to be used, the underlying operating system must be aware of the locale first. You can view all system locales with:</p>
<blockquote>
<tt>locale -a</tt>
</blockquote>

<p>If a locale isn't listed there, gettext can't use a DP site translation for that locale. Consult your system documentation on how to install system locales. CentOS/Redhat distros install many locales by default whereas for Ubuntu you need to install additional language packages, see the <a href='https://help.ubuntu.com/community/Locale'>Ubuntu locale documentation</a>.

<p>Note that locales can come in various different character sets, eg:</p>
<ul>
    <li>en_US  (actually ISO-8859-1, ie: Latin-1)</li>
    <li>en_US.utf8</li>
</ul>

<p>The DP localization code requires the ISO-8859-1 character set be installed, even if the underlying PO files are in UTF-8. CentOS/Redhat systems install locales with both ISO-8859-1 and UTF-8 character sets by default. Ubuntu systems often don't have the ISO-8859-1 character sets, but you can generate them with <tt>locale-gen</tt>, eg: <tt>locale-gen es_MX</tt>.

<p>After you install new locales, you <b>must restart your web server</b> for the locales to be usable by the DP code!</p>

<h3>Installing translations</h3>
<p>The DP code comes with a set of translations provided by the community at pgdp.net. These are in the <tt>SETUP/locale</tt> directory. The translations may not be complete if strings were added or changed in the code since the translation was done. Strings that haven't been translated will display in English.</p>

<p>To install the translations, copy them to <tt><?php echo $dyn_locales_dir; ?></tt> and ensure the web server has full read/write permissions on the files and directories. The reason they are located elsewhere is because the site allows designated users to update the translations and to persist outside of the site code path for easier upgrades.</p>

<p>After the translations are installed you can manage them in the <a href='../locale/translators/index.php'>Translation Center</a>.</p>

<p>To review translations for missing strings and enable the translation:
<ol>
    <li>Generating a new template</li>
    <li>For each language, access the 'manage' link and:<ol>
        <li>Merge the current PO file with the current template</li>
        <li>Download the PO file</li>
        <li>Use a PO editor to view the untranslated strings</li>
        <li>If you are satisfied with the thoroughness of the translation, you can enable it</li>
    </ol></li>
</ol>

<h3>Regenerating the template</h3>
<p>The PO template (POT) file contains all translatable strings in the site code. If the code is changed, regenerate the template via the <a href='../locale/translators/index.php'>Translation Center</a>. This ensures that new or modified strings are available to translators for translation.</p>

<h3>Enabling translators</h3>
<p>Site translators have the ability to download the a translation, update it, and upload it back to the site. Note that translators have these abilities for all translations, not just a specific one. They cannot enable or disable a translation, create a translation for a new locale, delete a translation, or regenerate the template file.</p>

<p>To specify a user as a site translator, create an entry in the usersettings table for them:</p>
<blockquote>
<tt>INSERT INTO usersettings SET username='&lt;username&gt;', setting='site_translator', value='yes';</tt>
</blockquote>

<?php } // user_is_a_sitemanager(); ?>

<?php if(user_is_site_translator()) { ?>

<hr>

<h2>Translators</h2>

<h3>Character sets</h3>
<p>PO files, also known as message files, can be saved and uploaded in various different encodings, including ISO-8859-1 (ie: Latin-1) and UTF-8. The encoding must match the "Content-Type" heading at the top of the file to ensure proper localization of the page to the user. PO editors will do this for you automatically. Mismatched encodings and Content-Type headings will result in message corruption in the file, therefore it is imperative that you use a PO editor and not just a text editor!</p>

<p>The DP code currently only supports the ISO-8859-1 (ie: Latin-1) character set. Messages in PO files using different character sets, such as UTF-8, will be converted automatically before being shown to the user. Any characters that do not have an exact representation in ISO-8859-1 will be transliterated to the closest possible character. If you wish to represent characters in a message string that do not have ISO-8859-1 equivalents, use <a href='http://www.w3.org/TR/html4/sgml/entities.html'>HTML entities</a> in the string instead.</p>

<h3>PO Editors</h3>
<p>To work with DP code translations, you must use a PO editor. This is a tool that allows working with PO files, also known as message files, and ensures that the file is in the correct format and character set. Here are links to some common editors:</p>
<ul>
    <li><a href='https://poedit.net'>Poedit</a> - supports Windows, Mac, and Linux.</li>
    <li><a href='https://localise.biz/free/poeditor'>Loco online poeditor</a> - a fully browser-based PO file editor</li>
</ul>

<h3>Translator coordination</h3>
<p>PO files contain the entire translation for a locale. When a new PO file is uploaded, its contents replace the prior contents in their entirity, not just any untranslated strings. If multiple translators are working on a single locale, they should coordinate their efforts to work in series, not parallel. </p>

<h3>Using the Translation Center</h3>
<p>Translations are managed in the <a href='../locale/translators/index.php'>Translation Center</a>.</p>

<p>The initial page shows all installed locale translations, if they are enabled or disabled, the last time the PO file was modified, the progress of the translation, and a list of actions you can take on the files. Only enabled locale translations can be used by site users.</p>

<p>The 'view' link will show you the selected PO file in your browser window. The 'download' link will download the PO file to your local computer. The 'manage' button will display another page allowing you to do further actions on the PO file.</p>

<p>The page shows the current PO template file (POT) which contains all the strings in the installed DP code. A site administrator can regenerate the template using this page.</p>

<h3>Translation workflow</h3>
<p>The basic steps for editing a translation are:
<ol>
    <li>Select the 'manage' link for the locale translation you want to manage.</li>
    <li>(optional) Merge the current PO file with the most current template. (see below)</li>
    <li>Using the 'download' link to download the PO file you want to update.</li>
    <li>Update the PO file using a PO editor to change translations for existing strings or translate new strings that have not been translated.</li>
    <li>Upload the new PO file to the system using the form at the bottom of the page. If the locale translation is enabled, the changes should be immediate.</li>
    <li>(optional) If the locale translation is disabled and is ready for release, contact an admin to have them enable it.</li>
</ol>

<h3>Merging the current PO file with the current template</h3>
<p>The PO template (POT) file contains all the strings in the code that need translating. When the site code is updated, new strings might be added or existing strings changed such that some strings in the PO no longer match those in the POT file.</p>

<p>The 'manage' page will notify you if the POT file has been regenerated since the PO file was uploaded, possibly indicating that there are new or changed strings that need to be translated. Merging the PO file with the current template will update the PO file on the system to contain all the strings in POT file, retaining those in the current PO file that match.</p>

<p>During the merge, the system matches strings in the POT file with those in the PO file that have been translated. If no exact string is found and fuzzy matching is enabled, strings that are determined to be "close" are matched and the translation is flagged as "fuzzy". Fuzzy translations should be checked in the PO editor (editors will indicate if a translation is flagged as fuzzy), reviewed or updated, and then unflagged as fuzzy. <i>Using fuzzy matching can take a very long time to complete the merge.</i> See also the <a href='http://www.gnu.org/software/gettext/manual/gettext.html#Fuzzy-Entries'>gettext manual for fuzzy entries</a>.</p>

<h3>Translation tips</h3>
<h4>HTML and spaces</h4>
<p>Pay attention that you <strong>must not</strong> translate HTML tags, attributes, and entities (except those entities which are parts of words in the text). For example: if the string is "No&amp;nbsp;space" translate "No" and "space" but leave &amp;nbsp; as-is. If an entity is needed for an accented character in a word that is not available in ISO-8859-1, use the entity as a substitute for that letter or letter combination in the translated word, but do not attempt to translate the entity itself.</p>

<p>Although there should not be any, if there are spaces at the beginning or the end of a line, keep them.</p>


<h4>strftime and sprintf</h4>
<p>Some strings are not displayed as-is, but are passed to PHP's <a href='http://www.php.net/manual/en/function.strftime.php'>strftime</a> or <a href='http://www.php.net/manual/en/function.sprintf.php'>sprintf</a> functions.</p>

<p>Strings passed to strftime contain, or consist entirely of "tags" prefixed by %, followed by a letter (%A, %B, etc.). When viewed on the website by the end-user, these tags are replace with already translated time-related terms (day names, month names, etc.)&mdash;you don't need to translate them yourself! For example, %B expands to month name (e.g. August), and %Y expands to display the full year (e.g. 2004).</p>

<p>It might be neccesary to reorganise the tags so that they form a date which is more natural for your language. For examples, "%A, %B %e, %Y" becomes "Friday, August 13 2004" while "%A, %e. %B %Y." becomes "Friday, 13. August 2004.". For a full list of all tags and a more detailed explanation, you can see <a href='http://www.php.net/manual/en/function.strftime.php' class='external' title="http://www.php.net/manual/en/function.strftime.php">strftime</a> in PHP's manual.</p>

<p>Note that some non-date-related strings are also tagged. These are passed to the <a href='http://www.php.net/manual/en/function.sprintf.php' class='external' title="http://www.php.net/manual/en/function.sprintf.php">sprintf</a> function instead of strftime. For example: "&lt;a href=%s&gt;your preferences page&lt;/a&gt;". You should just leave the tags as they are -- any strings associated with the %s that need to be translated will be translated elsewhere.</p>

<p>Some sprintf-formatted strings have more than one substitution variable. For example: "&lt;a href='%1$s'&gt;%2$s&lt;/a&gt;". Treat the %1$s and similar strings as placeholders and keep them intact. If necessary, you can change the order of the placeholders in the stringto better suit the destination language.</p>

<p>If you need to insert a % sign in either strftime or sprintf strings for a translation, use two in a row (%%). If the string is not a strftime or sprintf string (ie: it doesn't have any formatting characters or placeholders already) a single % should be used as needed.</p>

<h4>Quotes</h4>
<p>If the original string has quotes (eg: ' or ") in HTML attributes, use the same quotes in the translated string. If the quotes are part of a paragraph, you are welcome to use the language's quote characters in their place.</p>

<?php } // user_is_site_translator() ?>

</div>
<?php
// vim: sw=4 ts=4 expandtab
