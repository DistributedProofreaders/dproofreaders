<?php
$relPath = '../pinc/';
include_once($relPath.'base.inc');
include_once($relPath.'theme.inc');
include_once($relPath.'user_is.inc');

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

<h2>Troubleshooting</h2>
<p>If the site is not showing up in the language you expect, the <a href='../locale/debug_ui_language.php'>UI Language Debugger</a> is a useful page for determining why. Providing information on this page to a developer or squirrel can assist in troubleshooting.</p>

<h2>Languages vs locales</h2>
<p>Many people think of translations as done from one language into another, such as from English into Spanish. But this is an oversimplification: which English -- US? Canadian? British? -- and which Spanish -- Spain? Mexico? Instead, they are done from one localization, such as US English, into another, such as Mexican Spanish. Computers term these localizations locales and they have unique identifiers. For example, US English is en_US and Mexican Spanish is es_MX.</p>

<p>Prior versions of the DP code included this lack of distinction between language and localization. This version uses locales when referring to specific interface language translations in the code. The user interface still uses "language" however.</p>

<h2>Becoming a translator &amp; getting help</h2>
<p>If you would like to help with a current translation effort, would like to translate the user interface into a new language, or have questions that are not answered in the following sections, please contact the <a href='mailto:<?php echo $translation_coordinator_email_addr; ?>'>site translation coordinator</a>. 

<?php if (user_is_a_sitemanager()) { ?>

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
<kbd>locale -a</kbd>
</blockquote>

<p>If a locale isn't listed there, gettext can't use a DP site translation for that locale. Consult your system documentation on how to install system locales. CentOS/Redhat distros install many locales by default whereas for Ubuntu you need to install additional language packages, such as <kbd>language-pack-fr</kbd>. See the <a href='https://help.ubuntu.com/community/Locale'>Ubuntu locale documentation</a> for more information.

<p>Note that locales can come in various different character sets, eg:</p>
<ul>
    <li>en_US  (actually ISO-8859-1, ie: Latin-1)</li>
    <li>en_US.utf8</li>
</ul>

<p>The DP localization code requires the UTF-8 version be installed.</p>

<p>After you install new locales, you <b>must restart your web server</b> for the locales to be usable by the DP code!</p>

<h3>Installing translations</h3>
<p>The DP code comes with a set of translations provided by the community at pgdp.net. These are in the <kbd>SETUP/locale</kbd> directory. The translations may not be complete if strings were added or changed in the code since the translation was done. Strings that haven't been translated will display in English.</p>

<p>To install the translations:<ol>
    <li>Change into the <kbd>SETUP/locale</kbd> directory and run <kbd>compile.sh</kbd> to compile the PO files into MO files</li>
    <li>Copy the desired language directories to <kbd><?php echo $dyn_locales_dir; ?></kbd></li>
    <li>Confirm the web server has full read/write permissions on the files and directories</li>
</ol></p>

<p>The files are located outside the source directory because the site allows designated users to update the translations. This also persists the files across code upgrades.</p>

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

<p>To specify a user as a site translator, grant the site_translator setting for the user on the <a href='../tools/site_admin/manage_site_access_privileges.php'>Manage Site Access Privileges</a> page.

<?php } // user_is_a_sitemanager();?>


<?php if (user_is_site_translator()) { ?>

<hr>

<h2>Translators</h2>

<h3>Character sets</h3>
<p>PO files, also known as message files, can be saved and uploaded in various different encodings, including ISO-8859-1 (ie: Latin-1) and UTF-8. The file encoding must match the "Content-Type" heading at the top of the file to ensure proper localization of the page to the user. Mismatched file encodings and Content-Type headings will result in message corruption in the file.</p>

<p>The DP code supports UTF-8 character encoding. Messages in PO files using different character sets will be converted automatically before being shown to the user. In order to minimize problems caused by PO editors not uniformly saving with the same file encoding declared in the PO headers, all PO files have been set to use a Content-Type of UTF-8.</p>

<h3>PO Editors</h3>
<p>To work with DP code translations, you should use a PO editor. This is a tool that allows working with PO files, also known as message files, and ensures that the file is in the correct format and character set. Here are links to some common editors:</p>
<ul>
    <li><a href='https://poedit.net'>Poedit</a> - supports Windows, Mac, and Linux.</li>
    <li><a href='https://localise.biz/free/poeditor'>Loco online poeditor</a> - a fully browser-based PO file editor.</li>
</ul>

<p><strong>Why use a PO editor?</strong> A PO file is a specialized kind of text file. It can be edited in a text editor, but using a dedicated PO editor has some important advantages, among which are:
<ul>
    <li>Multi-line messages are joined for editing, so that the string can be easily translated as a unit, without worrying about differences in grammar.</li>
    <li>It is visually quite obvious which strings have been identified as fuzzy, and in need of review.</li>
</ul>

<h3>Translator coordination</h3>
<p>PO files contain the entire translation for a locale. When a new PO file is uploaded, its contents replace the prior contents in their entirety, not just any untranslated strings. If multiple translators are working on a single locale, they should coordinate their efforts to work in series, not parallel, or select one person to do the actual updates, even if multiple people are helping with the localization.</p>

<h3>Using the Translation Center</h3>
<p>Translations are managed in the <a href='../locale/translators/index.php'>Translation Center</a>.</p>

<p>The initial page shows all installed locale translations, if they are enabled or disabled, the last time the PO file was modified, the progress of the translation, and a list of actions you can take on the files. Only enabled locale translations can be used by site users.</p>

<p>The 'view' link will show you the selected PO file in your browser window. The 'download' link will download the PO file to your local computer. The 'manage' button will display another page allowing you to do further actions on the PO file.</p>

<p>You can also view in your browser, or download the current PO template file (POT) which contains all the strings in the installed DP code that have been marked for translation. A site administrator can regenerate the template using this page, as needed.</p>

<h3>Translation workflow</h3>
<p>Basic steps for editing a translation:
<ol>
    <li>Select the 'manage' link for the locale translation you want to manage.</li>
    <li>(optional, see below) Merge the current PO file with the most current template.</li>
    <li>Use the 'download' link to download the PO file you want to update.</li>
    <li>Update the PO file using a PO editor to change translations for existing strings or translate new strings that have not been translated (see below for translation tips).</li>
    <li>Upload the new PO file to the system using the form at the bottom of the page. If the locale translation is enabled, the changes should be immediate.</li>
    <li>(optional) If the locale translation is disabled and is ready for release, contact an admin to have them enable it.</li>
</ol>

<h3>Merging the current PO file with the current template</h3>
<p>The PO template (POT) file contains all the strings in the code that need translating. When the site code is updated, new strings might be added or existing strings changed such that some strings in the PO no longer match those in the POT file.</p>

<p>The 'manage' page will notify you if the POT file has been regenerated since the PO file was uploaded, possibly indicating that there are new or changed strings that need to be translated. Merging the PO file with the current template will update the PO file on the system to contain all the strings in POT file, retaining those in the current PO file that match.</p>

<p>During the merge, the system matches strings in the POT file with those in the PO file that have been translated. If no exact string is found and fuzzy matching is enabled, strings that are determined to be "close" are matched and the translation is flagged as "fuzzy". Fuzzy translations should be checked in the PO editor (editors will indicate if a translation is flagged as fuzzy), reviewed and updated if necessary, and then unflagged as fuzzy. <i>Using fuzzy matching can take a very long time to complete the merge.</i> See also the <a href='http://www.gnu.org/software/gettext/manual/gettext.html#Fuzzy-Entries'>gettext manual for fuzzy entries</a>.</p>

<h3>Translation tips</h3>
<p>Many strings can be translated as they are. However, there are a number of strings that will contain some kind of code.</p>

<h4>HTML, attributes, entities and spaces</h4>
<p>You <strong>must not</strong> translate HTML tags, attributes, and entities. For example: if the string is "No&amp;nbsp;space" translate "No" and "space" but leave &amp;nbsp; as-is. Because the site is UTF-8 enabled, you should not need to use entities for most characters.</p>

<p>If you are unfamiliar with HTML tags, attributes and entities, and uncertain whether something should be translated or not, ask for clarification.</p>

<p>Although there should not be any, if there are spaces at the beginning or the end of a line, keep them.</p>

<h4>ICU-formatted times and dates</h4>

<p>PHP code can localize dates and times using <a href='https://unicode-org.github.io/icu/userguide/format_parse/datetime/'>ICU-formatted strings.</a></p>

<p>It might be necessary to reorganize the tags so that they form a date which is more natural for your language. For examples, <kbd>EEEE, MMMM d y</kbd> becomes "Friday, August 6 2004" while <kbd>EEEE, d. MMMM y.</kbd> becomes "Friday, 6. August 2004.".</p>

<h4>sprintf</h4>

<p>Some non-date-related strings are also tagged. These are passed to PHP's <a href='http://www.php.net/manual/en/function.sprintf.php' class='external' title="http://www.php.net/manual/en/function.sprintf.php">sprintf</a> function. For example: "&lt;a href=%s&gt;your preferences page&lt;/a&gt;". You should just leave the tags as they are&mdash;any strings associated with the %s that need to be translated will be translated elsewhere.</p>

<p>Some sprintf-formatted strings have more than one substitution variable. For example: "&lt;a href='%1$s'&gt;%2$s&lt;/a&gt;". Treat the %1$s and similar strings as placeholders and keep them intact. If necessary, you can change the order of the placeholders in the string to better suit the destination language.</p>

<p>If you need to insert a % sign in a sprintf string for a translation, use two in a row (%%). If the string is not a sprintf string (ie: it doesn't have any formatting characters or placeholders already) a single % should be used as needed.</p>

<h4>Quotes</h4>
<p>If the original string has quotes (eg: ' or ") in HTML attributes, use the same quotes in the translated string. If the quotes are part of a paragraph, you are welcome to use the language's quote characters in their place.</p>

<?php } // user_is_site_translator()?>

</div>
<?php
