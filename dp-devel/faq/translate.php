<?
$relPath='../pinc/';
include($relPath.'v_site.inc');
if (!isset($_COOKIE['pguser'])) { include($relPath.'connect.inc'); } else { include($relPath.'dp_main.inc'); }
include($relPath.'theme.inc');
new dbConnect();
$no_stats=1;
theme('Site translation','header');
?>
<h1>Site translation</h1>
<p>In order to translate PG site to your language, you need to translate the following files and
send them to <a href='mailto:<? echo $general_help_email_addr ?>'>the admin</a>. Visit
<a href='../phpBB2/viewtopic.php?t=107'>this forum topic</a> to make sure that noone is already
translating to your language.</p>
<h2>Site files</h2>
<p><a href='messages.zip'>These files</a> contain the text of site's user interface:</p>
<p><b>messages.po</b>: In this file, you need to translate text written after <code>msgid</code> identifier
and write it down after <code>msgstr</code> identifier:
<table border=1><tr><td>
<b>Before:</b>
</td><td>
<b>After:</b>
</td></tr><tr valign=top><td>
<pre>msgid "Yes"
msgstr ""
</pre>
</td><td>
<pre>msgid "Yes"
msgstr "Oui"
</pre>
</td></tr></table></p>
<p><b>messages.inc</b>: Similarly, in this file, you need to translate text written after
<code>, </code> identifier and write it down in the next line:
<table border=1><tr><td>
<b>Before:</b>
</td><td>
<b>After:</b>
</td></tr><tr valign=top><td>
<pre>, "Yes" =>
""
</pre>
</td><td>
<pre>, "Yes" =>
"Ja"
</pre>
</td></tr></table></p>
<p>You need to translate them both in order for the site interface to be translated to your
language. However, perhaps it would be wise to translate and send only <b>messages.po</b> at
first, so that if there are any problems with the language, they could be resolved before
translating the larger <b>messages.inc</b>.</p>
<p>At the bottom of messages.inc there is greeting email. Just translate it (if you think that a
translation is needed), you don't need to leave the English text. We recommend that this is done
in plain ASCII as there might be users who don't have support for more then that (for example, at
<a href='http://mail.yahoo.com'>Yahoo!</a>).</p>
<p>Pay attention that, in both files, you <b>must not</b> translate HTML tags and entities;
that any spaces at the beginning or the end of a line <b>must</b> still be present; and that
any eventual quotes <b>must</b> be escaped, as in the original text. Oh, and, of course, you
<b>must</b> save the files using UTF-8 encoding. Examples:</p>
<table border=1><tr><td>
<b>Before:</b>
</td><td>
<b>After:</b>
</td><td>
<i>Note</i>
</td></tr><tr valign=top><td>
<pre>msgid "Project&lt;BR&gt;Manager"
msgstr ""
</pre>
</td><td>
<pre>msgid "Project&lt;BR&gt;Manager"
msgstr "Управник&lt;BR&gt;пројекта"
</pre>
</td><td>
&lt;BR&gt; is not translated.
</td></tr><tr valign=top><td>
<pre>msgid "Name&amp;nbsp;of&amp;nbsp;Work"
msgstr ""
</pre>
</td><td>
<pre>msgid "Name&amp;nbsp;of&amp;nbsp;Work"
msgstr "Име&amp;nbsp;дела"
</pre>
</td><td>
&amp;nbsp; is not translated.
</td></tr><tr valign=top><td>
<pre>msgid " active users out of "
msgstr ""
</pre>
</td><td>
<pre>msgid " active users out of "
msgstr " активних корисника од "
</pre>
</td><td>
Spaces at the beginning and the end are preserved.
</td></tr><tr valign=top><td>
<pre>, "\"something\"" =>
""
</pre>
</td><td>
<pre>, "\"something\"" =>
"\"нешто\""
</pre>
</td><td>
Note the \ before the ".
</td></tr></table></p>
<h2>FAQs</h2>
<p><a href='faqs.zip'>These files</a> contain various FAQs from the site. The files are PHP files; you could translate them as if they are ordinary HTML files. Perhaps
it is the best to use a HTML editor (that knows of PHP) or a text editor with HTML syntax
highlighting. If you use a text editor, again, you have to save the files using UTF-8 encoding
and pay attention not to translate HTML tags and entities, just as above. But you should also
translate the page title:</p>
<table border=1><tr><td>
<b>Before:</b>
</td><td>
<b>After:</b>
</td></tr><tr valign=top><td>
<pre>theme('FAQ Central','header');</pre>
</td><td>
<pre>theme('Централни FAQ','header');</pre>
</td></tr></table></p>
<p>You should also change path for images and the <code>relPath</code> variable to one more
level below:
<table border=1><tr><td>
<b>Before:</b>
</td><td>
<pre>&lt;img src="tablec.png" alt="" width="591" height="780"&gt;</pre>
</td><td>
<pre>$relPath='../pinc/';</pre>
</td></tr><tr><td>
<b>After:</b>
</td><td>
<pre>&lt;img src="../tablec.png" alt="" width="591" height="780"&gt;</pre>
</td><td>
<pre>$relPath='../../pinc/';</pre>
</td></tr></table></p>
<p>Note that these are original files of Project Gutenberg Distributed Proofreaders. On this
site, some policies will be changed, first of all those about ASCII; for example, we will no
longer transliterate Greek. But for now, just translate these files literally, as they will
still be usable as documentation in your language for original PGDP site. </p>
<p>After translating, send files to
<a href='mailto:<? echo $general_help_email_addr ?>'>the admin</a> so that they will be included
in the site. If you can't translate at once all files that you want to translate, you can send
the files separately; but please, translate and send as much as possible at one moment.</p>
<h3>Misc</h3>
<p>After you translate something, you migh also tell us how is "welcome" written in your
language :)</p>

<p>For technical reasons, we are currently unable to add interface translation (though we can
add the documentation) for the following languages:
<ul>
<li>(Armenian)</li>
<li>Brezhoneg (Breton)</li>
<li>Cymraeg (Welsh)</li>
<li>Čakavski (Tchakavian)</li>
<li>Esperanto</li>
<li>(Georgian)</li>
<li>Ghàidhlig (Scottish Gaelic)</li>
<li>Kajkavski (Kajkavian)</li>
<li>Ladino</li>
<li>Latina (Latin)</li>
<li>Retoroman</li>
<li>Романи (Romany)</li>
<li>Sámigiella (Sami)</li>
<li>Словио (Slovio)</li>
<li>Serbščina, Horna (Upper Wendish)</li>
<li>Serbščina, Delna (Lower Wendish)</li>
<li>Старославянски (Old Slavic)</li>
<li>(Tzintzarian)</li>
<li>Vlaams (Flemish)</li>
<li>(Wendish)</li>
<li>Yiddish</li>
</ul>
You can make plans for translating them,
<a href='mailto:<? echo $general_help_email_addr ?>'>contact us</a> about it, tell us how some of
them are actually called, and we do plan to solve the technical difficulties once.</p>
<?
theme('','footer');
?>
