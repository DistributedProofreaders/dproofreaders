<?
$relPath='../pinc/';
include_once($relPath.'v_site.inc');
include_once($relPath.'theme.inc');
$no_stats=1;
theme('FAQ Central','header');
?>
<div id='article'><a name="top"></a>
<h1 >Site translation</h1 >

<p>
This page contains information on how to translate DP site. For information on translation status of various languages, see <a href='http://dp.rastko.net/stats/software.php' class='external' title="http://dp.rastko.net/stats/software.php">Software information</a>.

<p>
If you wish to translate our site to your language, or improve upon the existing translation, please visit the <a href='http:../phpBB2/viewtopic.php?t=107' class='external' title="http:../phpBB2/viewtopic.php?t=107">Site translation thread</a> first to make sure that noone is already doing that. You may also report problems with existing translation there.

<p>
There are several things that could be translated.

<p><table border="0" id="toc"><tr><td align="center">
<b>Table of contents</b> <script type='text/javascript'>showTocToggle("show","hide")</script></td></tr><tr id='tocinside'><td>
<div style="margin-bottom:0px;">
<A CLASS="internal" HREF="#Site">1 Site</A><BR>
</div>
<div style="margin-left:2em;">
<A CLASS="internal" HREF="#Translation_Center">1.1 Translation Center</A><BR>
<div style="margin-left:2em;">
<A CLASS="internal" HREF="#Languages">1.1.1 Languages</A><BR>
<A CLASS="internal" HREF="#Parts">1.1.2 Parts</A><BR>
<A CLASS="internal" HREF="#Strings">1.1.3 Strings</A><BR>
<A CLASS="internal" HREF="#Special_strings">1.1.4 Special strings</A><BR>
<A CLASS="internal" HREF="#Strftime_and_sprintf">1.1.5 Strftime and sprintf</A><BR>
</div>
</div>
<div style="margin-bottom:0px;">
<A CLASS="internal" HREF="#FAQs">2 FAQs</A><BR>
</div>
<div style="margin-bottom:0px;">
<A CLASS="internal" HREF="#Forums">3 Forums</A><BR>
</div>
<div style="margin-bottom:0px;">
<A CLASS="internal" HREF="#Misc">4 Misc</A><BR>
</div>
<div style="margin-left:2em;">
<A CLASS="internal" HREF="#Linking">4.1 Linking</A><BR>
<A CLASS="internal" HREF="#Unsupported_languages">4.2 Unsupported languages</A><BR>
</div>
</td></tr></table><P>
<h2><a name="Site">Site</a></h2>

Site interface could be translated with the help of an on-line translation interface&mdash;the Translation Center.

<h3><a name="Translation_Center">Translation Center</a></h3>

Translation Center is located at <a href='http://dp.rastko.net/locale/translators' class='external' title="http://dp.rastko.net/locale/translators">dp.rastko.net/locale/translators</a>. Each user can wade through it and see how it works, but only an appointed translator can really save the translation. If you want to translate the site, you need to ask for translator status. After you get it, read the instructions for use of the interface.

<h4><a name="Languages">Languages</a></h4>

Upon entering it, you will see a list of languages. If your language is not in the list, you can add it by clicking on the link above. Note however, that even though you can work on the translation, the language will not be automatically added to the site, but will have to be added by site admins. It is not technically possible to add to the site all languages which could be translated! (For a partial list see <a href="#Misc" class='internal' title ="">misc</a> below.) You should therefore announce your wish to add a new language in the Site translation thread.

<p>
Clicking on "Translate" beside a language will take you to a page dedicated to that language. You will now see a list of site parts which could be translated.

<p>

<h4><a name="Parts">Parts</a></h4>

Clicking on "Location" aside one of them will lead you to that part so that you will see what are you actually translating. This will not always work, especially for pages ending in <code >.inc</code >; just try to recognise the strings. If you want to translate something specific but don't know in which part of the site it is actually located, ask in the thread!

<p>
There are priorities. First things that should be translated are those that all visitors of the site see (<code >default.php</code >, <code >pinc/theme.inc</code >, <code >pinc/showstartexts.inc</code >...). Then, those that all logged in users see (<code >tools/proofers/proof_per.php</code >, <code >userprefs.php</code >, <code >pinc/button_defs.inc</code >...). If no Project Managers who don't know English are expected, PM interface could even be left untranslated. Even the translation interface is translatable, though from obvious reasons it is not neccesary to translate it.

<p>
If a string is changed in the site code, its translation is no longer usable (alas, even if the change would not reflect on translations). At the bottom of the interface, there is "Rebuild String List" button. Clicking on it will insert into the translation interface any changed and new strings from the site code. Changes which would require its use will usually be announced on the Site translation thread. Rest assured that your existing translations would never be lost, the old string and its translation will still be visible, and if the new string is same or similar to old one, you can just copy and paste it.

<h4><a name="Strings">Strings</a></h4>

Clicking on "Translate" at the right will (finally!) let you to actually translate something. If you wish, clicking next to "All" will give you access to entire site at once but we don't recommend this as the page might be several hundreds of kilobytes long! Regardless of which you choose, you don't need to translate entire page of strings at once, of course&mdash;do as much as you wish. Again, there is need to coordinate; if two persons are translating same part of the site at the same time, they might cancel each other's work! And while backup of each translation is kept, and the work will not be lost, this will just make a headache for everyone, so please unless you're just fixing a typo, announce your wish to translate in the thread and wait a bit to see if someone was already translating.

<p>
The translation interface is pretty straightforward. There is a list of strings, each followed with a text box. Type your translation of a string in its text box, and move onto the next string. When you have translated all the strings, or gotten bored, click on the "Save and Compile" button at the bottom to save your strings and that's it!

<p>
In the past, your strings would have been visible immediately after the translation. Unfortunately, this had to be turned off as it caused some problems on the site. The translations will be compiled periodically; if you have made a significant translation, <a href='http://dp.rastko.net/phpBB2/privmsg.php?mode=post&u=3' class='external' title="http://dp.rastko.net/phpBB2/privmsg.php?mode=post&amp;u=3">ask</a> the admin to compile it or announce it in the thread.

<p>
Distributed Proofreaders Europe for a while used a different translation system, which resulted in somewhat incompatible translations. Partly because of this, and partly because some strings have changed between the two versions of the software (for example, "proofer" changed to "proofreader"), existing translations are not usable in entirety. In particular, of 173 messages in <code >messages.inc</code >, 80 are salvaged, and a few dozen more could be reinserted manually. You can download the .inc file for your language <a href='http://dp.rastko.net/downloads/old_translations' class='external' title="http://dp.rastko.net/downloads/old translations">here</a> if you want to reuse old messages.

<p>

<h4><a name="Special_strings">Special strings</a></h4>

Pay attention that you <strong>must not</strong> translate HTML tags and entities (except those entities which are parts of words in the text); 
that any spaces at the beginning or the end of a line <strong>must</strong> still be present; and that
any eventual quotes <strong>must</strong> be escaped, as in the original text. Examples:

<p>

<table border=1><tr ><td >
<b >String:</b >
</td ><td >
<b >Translation:</b >
</td ><td >
<i >Note</i >
</td ></tr ><tr valign=top><td >
<em><strong>Project&lt;BR&gt;Manager</strong></em>
</td ><td >
<code >Управник&lt;BR&gt;пројекта</code >
</td ><td >
<code >&lt;BR&gt;</code > is not translated.
</td ></tr ><tr valign=top><td >
<em><strong>Name&amp;nbsp;of&amp;nbsp;Work</strong></em>
</td ><td >
<code >Име&amp;nbsp;дела</code >
</td ><td >
<code >&amp;nbsp;</code > is not translated.
</td ></tr ><tr valign=top><td >
<em><strong>Encyclop&amp;aelig;dia&nbsp;Britannica</strong></em>
</td ><td >
<code >Енциклопедија&nbsp;Британика</code >
</td ><td >
<code >&amp;aelig;</code > is part of a word&mdash;translated.
</td ></tr ><tr valign=top><td >
<img src="../locale/translators/space.gif"><em><strong>active users out of</strong></em><img src="../locale/translators/space.gif">
</td ><td >
<code >&nbsp;активних корисника од&nbsp;</code >
</td ><td >
Spaces at the beginning and the end are preserved.
</td ></tr ><tr valign=top><td >
<em><strong>\"Show Projects From\"</strong></em>
</td ><td >
<code >\"Приказ пројеката од\"</code >
</td ><td >
Note the <code >\</code > before the <code >"</code >. Other possibilities are <code >\r</code >, <code >\n</code >, <code >\\</code >
</td ></tr ><tr valign=top><td >
<em><strong>%A, %B %e, %Y</strong></em>
</td ><td >
<code >%A, %e. %B %Y.</code >
</td ><td >
See <a href="#Strftime_and_sprintf" class='internal' title ="">strftime and sprintf</a> below.
</td ></tr ></table >

<h4><a name="Strftime_and_sprintf">Strftime and sprintf</a></h4>

Some strings are not displayed as-is, but are passed to PHP's <a href='http://www.php.net/manual/en/function.strftime.php' class='external' title="http://www.php.net/manual/en/function.strftime.php">strftime</a> function. These strings are recognisable by containing or consisting in entirety of % signs, each followed by a letter (%A, %B... we shall call such percent-letter combinations <em>tags</em>). The tags are replaced with already translated time-related terms (day names, month names...)&mdash;you don't need to translate them yourself! For example, %B expands to month name (f.e. August), and %Y expands to full year (f.e. 2004).

<p>
It might be neccesary to reorganise the tags so that they form a date which is more natural for your language. As for above examples, "%A, %B %e, %Y" becomes "Friday, August 13 2004" while "%A, %e. %B %Y." becomes "Friday, 13. August 2004." when used for English. For a full list of all tags and a more detailed explanation, you can see <a href='http://www.php.net/manual/en/function.strftime.php' class='external' title="http://www.php.net/manual/en/function.strftime.php">strftime</a> in PHP's manual.

<p>
Note that some other strings also have tags, but are not passed to strftime, but to <a href='http://www.php.net/manual/en/function.sprintf.php' class='external' title="http://www.php.net/manual/en/function.sprintf.php">sprintf</a> function instead. You will see that the strings and tags are not date-related. An example could be "&lt;a href=%s&gt;your preferences page&lt;/a&gt;". You should just leave the tags as they are.

<p>
If the need arises to use the % sign in either strftime or sprintf strings, you should type in %% instead. The additional % acts as an escape.

<p>

<h2><a name="FAQs">FAQs</a></h2>

It is possible to translate various FAQs of the site. The files are <a href='http://www.php.net/manual/en/introduction.php' class='external' title="http://www.php.net/manual/en/introduction.php">PHP</a> files; you could translate them as if they are ordinary HTML files. <strong>Do not</strong> use "Save As" when viewing a FAQ and translate saved file; such translations will not be directly usable. Download a file from <a href='http://dp.rastko.net/faq/download.php' class='external' title="http://dp.rastko.net/faq/download.php">here</a> and translate it.

<p>
For translating FAQs, it is perhaps best to use a HTML editor (that knows of PHP) or a text editor with HTML syntax highlighting. If you use a text editor, you have to save the files using UTF-8 encoding and pay attention not to translate HTML tags and entities, just as above. But you should also
translate the page title:

<table border=1><tr ><td >
<b >Before:</b >
</td ><td >
<b >After:</b >
</td ></tr ><tr valign=top><td >
<pre>theme('FAQ Central','header');</pre>

</td ><td >
<pre>theme('Централни FAQ','header');</pre>

</td ></tr ></table >

You should also change path for images and the <code >relPath</code > variable to one more
level below:

<table border=1><tr ><td >
<b >Before:</b >
</td ><td >
<pre>&lt;img src=&quot;tablec.png&quot; alt=&quot;&quot; width=&quot;591&quot; height=&quot;780&quot;&gt;</pre>

</td ><td >
<pre>$relPath='../pinc/';</pre>

</td ></tr ><tr ><td >
<b >After:</b >
</td ><td >
<pre>&lt;img src=&quot;../tablec.png&quot; alt=&quot;&quot; width=&quot;591&quot; height=&quot;780&quot;&gt;</pre>

</td ><td >
<pre>$relPath='../../pinc/';</pre>

</td ></tr ></table >

<p>
Note that these are original files of Project Gutenberg Distributed Proofreaders. On Distributed Proofreaders Europe, some policies will different, in particular those about ASCII; for example, on Distributed Proofreaders Europe, Greek is not transliterated. But for now, just translate these files literally, as they will still be usable as documentation in your language for the original PGDP site.

<p>
Again, there are priorities, and you don't need to translate everything. At the very least you should translate FAQ Central (<code >faq_central.php</code >) and the Privacy Statement (<code >privacy.php</code >), then the Beginning Proofreader's FAQ (<code >ProoferFAQ.php</code >), Proofreading Guidelines (<code >document.php</code >) and Proofreading Interface Help (<code >prooffacehelp.php</code >), and after that whatever you like the most.

<p>
After translating, send translated files to <a href='mailto:dp-admin@rastko.net' class='external' title="mailto:dp-admin@rastko.net">the admin</a> so that they can be included in the site. If you can't translate all of the files that you want to translate at once, you can send the files separately; but please, translate and send as much as possible at one time. If you notice some errors afterwards, please don't update your local files, but download them from the site again and update those. This is because some changes might have to be made to your files and so would have to be made again otherwise.

<p>

<h2><a name="Forums">Forums</a></h2>

If your community is active enough, it can have a forum on its own, or even a full forum hierarchy. For a forum, just <a href='http://dp.rastko.net/phpBB2/privmsg.php?mode=post&u=3' class='external' title="http://dp.rastko.net/phpBB2/privmsg.php?mode=post&amp;u=3">ask</a> the admin. For full hierarchy, you have to translate all the forums' names and PM them to the admin.

<p>
Translation of the forum interface to your language should be installed when the language itself is installed, if not before. If it isn't, remind the admin to do it.

<h2><a name="Misc">Misc</a></h2>

After you translate something, you migh also tell us how is "welcome" written in your
language :)

<h3><a name="Linking">Linking</a></h3>

When you are linking to the site, you can link directly to its version in a particular language, and it will appear in that language irrespective of the user's browser settings. This should be used especially when writing about the site in a foreign language.

<p>
Currently the following URLs are available:

<p>

<ul><li> Danish: <a href="http://dp.rastko.net/da" class='external' title="http://dp.rastko.net/da">http://dp.rastko.net/da</a>
</li><li> Dutch: <a href="http://dp.rastko.net/nl" class='external' title="http://dp.rastko.net/nl">http://dp.rastko.net/nl</a>
</li><li> German: <a href="http://dp.rastko.net/de" class='external' title="http://dp.rastko.net/de">http://dp.rastko.net/de</a>
</li><li> English: <a href="http://dp.rastko.net" class='external' title="http://dp.rastko.net">http://dp.rastko.net</a>
</li><li> Finnish: <a href="http://dp.rastko.net/fi" class='external' title="http://dp.rastko.net/fi">http://dp.rastko.net/fi</a>
</li><li> French: <a href="http://dp.rastko.net/fr" class='external' title="http://dp.rastko.net/fr">http://dp.rastko.net/fr</a>
</li><li> Italian: <a href="http://dp.rastko.net/it" class='external' title="http://dp.rastko.net/it">http://dp.rastko.net/it</a>
</li><li> Norwegian: <a href="http://dp.rastko.net/no" class='external' title="http://dp.rastko.net/no">http://dp.rastko.net/no</a>
</li><li> Polish: <a href="http://dp.rastko.net/pl" class='external' title="http://dp.rastko.net/pl">http://dp.rastko.net/pl</a>
</li><li> Portuguese: <a href="http://dp.rastko.net/pt" class='external' title="http://dp.rastko.net/pt">http://dp.rastko.net/pt</a>
</li><li> Serbian: <a href="http://dp.rastko.net/sr" class='external' title="http://dp.rastko.net/sr">http://dp.rastko.net/sr</a>
</li><li> Spanish: <a href="http://dp.rastko.net/es" class='external' title="http://dp.rastko.net/es">http://dp.rastko.net/es</a>
</li><li> Swedish: <a href="http://dp.rastko.net/sv" class='external' title="http://dp.rastko.net/sv">http://dp.rastko.net/sv</a>
</li><li> Urdu: <a href="http://dp.rastko.net/ur" class='external' title="http://dp.rastko.net/ur">http://dp.rastko.net/ur</a>
</li></ul>

<p>
For more information, see <a href='http://dp.rastko.net/phpBB2/viewtopic.php?t=783' class='external' title="http://dp.rastko.net/phpBB2/viewtopic.php?t=783">Linking to the DP Europe site</a> forum topic.

<h3><a name="Unsupported_languages">Unsupported languages</a></h3>

For technical reasons, we are currently unable to add interface translation (though we can
add the documentation) for the following languages and dialects:

<p>

<ul><li>(Armenian)
</li><li>Brezhoneg (Breton)
</li><li>Cymraeg (Welsh)
</li><li>Čakavski (Tchakavian)
</li><li>Esperanto
</li><li>(Georgian)
</li><li>Ghàidhlig (Scottish Gaelic)
</li><li>Kajkavski (Kajkavian)
</li><li>Ladino
</li><li>Latina (Latin)
</li><li>Retoroman
</li><li>Романи (Romany)
</li><li>Sámigiella (Sami)
</li><li>Словио (Slovio)
</li><li>Serbščina, Horna (Upper Wendish)
</li><li>Serbščina, Delna (Lower Wendish)
</li><li>Старославянски (Old Slavic)
</li><li>(Tzintzarian)
</li><li>Yiddish
</li></ul>

<p>
You can make plans for translating them,
<a href='http://dp.rastko.net/phpBB2/viewtopic.php?t=107' class='external' title="http://dp.rastko.net/phpBB2/viewtopic.php?t=107">contact us</a> about it, tell us how some of
them are actually called, and we do plan to solve the technical difficulties once.

<p>
</div>
<?
theme('','footer');
?>
