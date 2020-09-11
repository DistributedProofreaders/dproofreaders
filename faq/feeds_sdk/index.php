<?php
$relPath='../../pinc/';
include_once($relPath.'base.inc');
include_once($relPath.'slim_header.inc');

slim_header(_("Feeds SDK"));
?>

<table border="0" style="border-collapse: collapse" width="100%" id="table1">
<tr>
<td bgcolor="#006699"><b><font face="Arial" color="#FFFFFF">
<a name="top"></a>Distributed 
Proofreaders XML Feeds SDK</font></b></td>
</tr>
<tr>
<td bgcolor="#FFFFFF"><font face="Arial" size="2">Revision 1.0 - March 
19th, 2003</font></td>
</tr>
<tr>
<td bgcolor="#D8E4F1"><b><font face="Arial">Contents</font></b></td>
</tr>
</table>

<blockquote>
<p><u><b><a href="#1">1. Overview</a></b></u></p>
<blockquote>
<p><b><a href="#1.1">1.1 XML Overview</a><br>
<u><a href="#1.2">1.2 Sample Uses</a><br>
<a href="#1.3">1.3 Feed Locations</a><br>
<a href="#1.4">1.4 Suggestions &amp; Updates</a></u></b></p>
</blockquote>
<p><u><b><a href="#2">2. JavaScript</a></b></u></p>
<blockquote>
<p><b><u><a href="#2.1">2.1 Parsing XML Using JavaScript</a><br>
<a href="#2.2">2.2 Sample Output</a></u></b><br>
<b><font face="Times New Roman"><a href="#2.3">2.3 Source Code</a></font></b></p>
</blockquote>
</blockquote>
<hr>
<table border="0" style="border-collapse: collapse" width="100%" id="table2" bgcolor="#D8E4F1">
<tr>
<td><b><font face="Arial"><a name="1"></a>1. Overview</font></b></td>
<td align="right"><font face="Arial" size="2"><a href="#top">Back to top</a></font></td>
</tr>
</table>
<blockquote>
<blockquote>
<p>This SDK tries to explain in as much detail as possible how the XML 
files for Project Gutenberg's Distributed Proofreaders are created as 
well as focusing mostly on how to implement these XML feeds into your 
site.</p>
<p>If you are further interested in XML and RSS you may want to look into the 
following sites as well:</p>
<ul>
<li><a href="https://www.w3.org/XML/">https://www.w3.org/XML/</a></li>
<li><a href="https://cyber.harvard.edu/rss/rss.html">
https://cyber.harvard.edu/rss/rss.html</a></li>
</ul>
<p>This is a beta document. If you have comments, find 
errors, or just have questions, please contact 
<a href="mailto:jgruber@tampabay.rr.com?subject=DP XML Feeds">
jgruber@tampabay.rr.com</a>.</p>
</blockquote>
<table border="0" style="border-collapse: collapse" width="95%" id="table3" bgcolor="#D8E4F1" height="20">
<tr>
<td><b><font face="Arial"><a name="1.1"></a>1.1 XML Overview</font></b></td>
<td align="right"><font face="Arial" size="2"><a href="#top">Back to 
top</a></font></td>
</tr>
</table>
<blockquote>
<p>From the World Wide Web Consortium:</p>
<p><cite>&quot;Extensible Markup Language (XML) is a simple, very flexible 
text format derived from SGML (<a href="https://www.iso.org/standard/16387.html">ISO 
8879</a>). Originally designed to meet the challenges of large-scale 
electronic publishing, XML is also playing an increasingly important 
role in the exchange of a wide variety of data on the Web and 
elsewhere.&quot;</cite></p>
<p>I personally find it very difficult to explain to someone exactly 
what XML is but the best way I can describe it is a format that looks a 
lot like HTML with tags (called elements) that you define in a file 
called a DTD or XSD.&nbsp; XML files allow a lot of flexibility on what 
data can be included in them but are very specific on how you write the 
code to implement that data.</p>
<p>We here at Distributed Proofreaders have implemented two different 
types of XML files based upon the DTD definition format.&nbsp; The first 
is a very common XML format called RSS a.k.a. <i><b>R</b>eally <b>S</b>imple
<b>S</b>yndication.</i>&nbsp; This basically is a standard format used 
by a lot of news aggregators to pull information from various XML feeds 
across the World Wide Web.&nbsp; The most recent version of RSS is 2.0 
which includes a lot of new features however we are using version 0.91 
due to a large number of news aggregators/syndicators still depending 
upon version 0.91.&nbsp; Some examples of these sites &amp; programs are 
Syndic8.com and Trillian Instant Messenger.&nbsp; Hopefully, over the 
next few months everyone will move to the latest RSS standard.</p>
<p>We then have another XML format which we have designed for our own 
purposes here at Distributed Proofreaders.&nbsp; There is no official 
name for it like there is for RSS however it includes the same features 
and a lot more than RSS does.&nbsp; Because XML allows us to create our 
own elements we can split the XML file into author, title, date posted &amp; 
much more.&nbsp; This allows much more flexibility for sites who are 
willing to implement the code that we are going to discuss below into 
their site.&nbsp; It includes more data for your end user as well as 
providing you more options on how to display the data.&nbsp; This feed 
is again based upon the DTD definition format and you can see our DTD
<a href="<?php echo $code_url; ?>/feeds/projects.dtd">here</a>.</p>
</blockquote>
<table border="0" style="border-collapse: collapse" width="95%" id="table4" bgcolor="#D8E4F1">
<tr>
<td><b><font face="Arial"><a name="1.2"></a>1.2 Sample Uses</font></b></td>
<td align="right"><font face="Arial" size="2"><a href="#top">Back to 
top</a></font></td>
</tr>
</table>
<blockquote>
<p>There are many ways you can implement the various feeds we create on 
your site.&nbsp; Further down we'll get into some examples of how you 
can incorporate them but you may be asking now what you should do with 
them.&nbsp; There are many different ways of putting our content on your 
site.&nbsp; Here are just a few examples:</p>
<ul>
<li>A &quot;slashbox&quot;.&nbsp; A term coined by the site
<a href="https://slashdot.org/">Slashdot.org</a> to describe a 
small table to the side of a page listing small bits of information.&nbsp; 
E.G.: The names of the last ten projects posted to Project Gutenberg 
with links to our library.</li>
<li>An online library.&nbsp; A dedicated page on your site to 
display a list of projects sorted by authors name.&nbsp; You can 
include links to download the text file or the zip file or the html 
file, etc...&nbsp; Allow a user to resort the display by the books 
name!</li>
<li>Distributed Proofreaders Updates.&nbsp; Develop a program that 
runs in a users system tray that keeps a user up to date with any 
news updates from their favorite sites.&nbsp; Our news updates are 
syndicated here at Distributed Proofreaders!</li>
</ul>
<p>These are only a small listing of the types of ways you can use our 
feeds.&nbsp; There are many more ways you can implement the feeds, much 
more than we can list here.&nbsp; We would appreciate however if you let 
us know of any great ways that you are using Distributed Proofreader 
feeds on your site or application.</p>
</blockquote>
<table border="0" style="border-collapse: collapse" width="95%" id="table5" bgcolor="#D8E4F1">
<tr>
<td><b><font face="Arial"><a name="1.3"></a>1.3 Feed Locations</font></b></td>
<td align="right"><font face="Arial" size="2"><a href="#top">Back to 
top</a></font></td>
</tr>
</table>
<blockquote>
<p>The site provides access to the following content via feeds
in RSS &amp; our own format which is yet to be named. Below are
the links to the different feeds.</p>
<ul>
    <li>
        <b>posted</b> - The most recent projects posted to Project Gutenberg.
        (<a href="<?php echo $code_url; ?>/feeds/backend.php?content=posted">XML</a>,
        <a href="<?php echo $code_url; ?>/feeds/backend.php?content=posted&amp;type=rss">
RSS</a>)
    </li>
    <li>
        <b>postprocessing</b> - The most recent projects available for Post-Processing.
        (<a href="<?php echo $code_url; ?>/feeds/backend.php?content=postprocessing">XML</a>,
        <a href="<?php echo $code_url; ?>/feeds/backend.php?content=postprocessing&amp;type=rss">
RSS</a>)
    </li>
    <li>
        <b>proofing</b> - The most recent projects released into the P1 round for proofreading.
        (<a href="<?php echo $code_url; ?>/feeds/backend.php?content=proofing">XML</a>,
        <a href="<?php echo $code_url; ?>/feeds/backend.php?content=proofing&amp;type=rss">
RSS</a>)
    </li>
    <li>
        <b>smoothreading</b> - The most recent projects available for Smooth Reading.
        (<a href="<?php echo $code_url; ?>/feeds/backend.php?content=smoothreading">XML</a>,
        <a href="<?php echo $code_url; ?>/feeds/backend.php?content=smoothreading&amp;type=rss">
RSS</a>)
    </li>
</ul>
</blockquote>
<table border="0" style="border-collapse: collapse" width="95%" id="table10" bgcolor="#D8E4F1">
<tr>
<td><a name="1.4"></a><b><font face="Arial">1.4 Suggestions &amp; 
Updates</font></b></td>
<td align="right"><font face="Arial" size="2"><a href="#top">Back to 
top</a></font></td>
</tr>
</table>
<blockquote>
<p>Lastly, this is a beta document so far.&nbsp; I work on it when I 
don't have anything to code or my boss isn't looking at work.&nbsp; If 
there are any typos or mistakes please let me know by contacting me at
<a href="mailto:jgruber@tampabay.rr.com">jgruber@tampabay.rr.com</a>.&nbsp; 
If there are any problems with the example code that we have please let 
us know as well as letting us know of any ways to improve upon the code.&nbsp; 
While I don't know every language by heart that we have listed examples 
for here we have done our best to make sure they are correct.&nbsp; 
However, we do not take any responsibility for any damages or unintended 
consequences caused by using the code listed here.</p>
</blockquote>
</blockquote>
<hr>
<table border="0" style="border-collapse: collapse" width="100%" id="table6" bgcolor="#D8E4F1">
<tr>
<td><b><font face="Arial"><a name="2"></a>2. JavaScript</font></b></td>
<td align="right"><font face="Arial" size="2"><a href="#top">Back to top</a></font></td>
</tr>
</table>
<blockquote>
<blockquote>
<p>If you do not have physical access to the web server on which you 
wish to implement a feed you may not be able to install server side 
scripting language programs such as PHP or ASP.&nbsp; The are two ways 
you can still display our content on your site and one of those is by 
using JavaScript.&nbsp; The other is CSS, discussed further below, 
however JavaScript is preferred due to it being able to be used by most 
browsers out there.&nbsp; CSS only works with more recent browsers such 
as Internet Explorer 5.5 &amp; Netscape 6.0.</p>
</blockquote>
<table border="0" style="border-collapse: collapse" width="95%" id="table7" bgcolor="#D8E4F1">
<tr>
<td><b><font face="Arial"><a name="2.1"></a>2.1 Parsing XML Using 
JavaScript</font></b></td>
<td align="right"><font face="Arial" size="2"><a href="#top">Back to 
top</a></font></td>
</tr>
</table>
<blockquote>
<p>Of course we'll start our parsing the XML file by letting the browser 
know this is JavaScript code:</p>
<blockquote>
<table border="0" style="border-collapse: collapse" width="85%" id="table11" bgcolor="#C0C0C0">
<tr>
<td><font size="2" face="Arial"><code>&lt;script language=&quot;JavaScript&quot;&gt;</code></font></td>
</tr>
</table>
</blockquote>
<p>We'll then create the XML document object which will use Microsoft's 
XMLDOM parser.</p>
<blockquote>
<table border="0" style="border-collapse: collapse" width="85%" id="table12" bgcolor="#C0C0C0">
<tr>
<td><font size="2" face="Arial"><code>var xmlDoc = new ActiveXObject(&quot;Microsoft.XMLDOM&quot;);</code></font></td>
</tr>
</table>
</blockquote>
<p>The next few lines are a function that will load the XML feed into 
the parser.&nbsp; It takes the value xmlFile that was passed to it &amp; 
loads the file.&nbsp; The xmlDoc.async line ensures the script does not 
continue until the file is completely loaded.</p>
<blockquote>
<table border="0" style="border-collapse: collapse" width="85%" id="table13" bgcolor="#C0C0C0">
<tr>
<td><font size="2" face="Arial"><code>function loadXML(xmlFile) <br>
{ <br>
xmlDoc.async=&quot;false&quot;;<br>
xmlDoc.load(xmlFile); <br>
xmlObj=xmlDoc.documentElement; <br>
}</code></font></td>
</tr>
</table>
</blockquote>
<p>We'll then call the above function with the following line.&nbsp; Be 
sure to replace XML_FEED_URL with the URL to the feed you would like to 
serve.</p>
<blockquote>
<table border="0" style="border-collapse: collapse" width="85%" id="table14" bgcolor="#C0C0C0">
<tr>
<td><font size="2" face="Arial"><code>loadXML('XML_FEED_URL');</code></font></td>
</tr>
</table>
</blockquote>
<p>Next, we'll then loop through the amount of nodes that are in the 
feed &amp; display them all.&nbsp; There is more to this section of code but 
I'll explain that in more detail in a minute.</p>
<blockquote>
<table border="0" style="border-collapse: collapse" width="85%" id="table15" bgcolor="#C0C0C0">
<tr>
<td><font size="2" face="Arial"><code>i = 0;<br>
numNodes = xmlObj.childNodes.length-1;<br>
while (i &lt;= numNodes)<br>
{<br>
if (xmlObj.childNodes(i).childNodes(1).hasChildNodes != false) { <br>
document.write(&quot;Title: &quot;);<br>
document.write(xmlObj.childNodes(i).childNodes(1).firstChild.text); <br>
document.write(&quot;&lt;br&gt;&quot;);<br>
}<br>
if (xmlObj.childNodes(i).childNodes(2).hasChildNodes != false) { <br>
document.write(&quot;Author: &quot;);<br>
document.write(xmlObj.childNodes(i).childNodes(2).firstChild.text); <br>
document.write(&quot;&lt;br&gt;&quot;);<br>
}<br>
if (xmlObj.childNodes(i).childNodes(3).hasChildNodes != false) { <br>
document.write(&quot;Language: &quot;);<br>
document.write(xmlObj.childNodes(i).childNodes(3).firstChild.text); <br>
document.write(&quot;&lt;br&gt;&quot;);<br>
}<br>
if (xmlObj.childNodes(i).childNodes(4).hasChildNodes != false) { <br>
document.write(&quot;Genre: &quot;);<br>
document.write(xmlObj.childNodes(i).childNodes(4).firstChild.text); <br>
document.write(&quot;&lt;br&gt;&quot;);<br>
}<br>
if (xmlObj.childNodes(i).childNodes(5).childNodes(0).hasChildNodes != 
false) { <br>
document.write(&quot;Text Download: &lt;a href='&quot;);<br>
document.write(xmlObj.childNodes(i).childNodes(5).childNodes(0).firstChild.text);
<br>
document.write(&quot;'&gt;&quot;);<br>
document.write(xmlObj.childNodes(i).childNodes(5).childNodes(0).firstChild.text);
<br>
document.write(&quot;&lt;/a&gt;&lt;br&gt;&quot;);<br>
}<br>
if (xmlObj.childNodes(i).childNodes(5).childNodes(1).hasChildNodes != 
false) { <br>
document.write(&quot;Zip Download: &lt;a href='&quot;);<br>
document.write(xmlObj.childNodes(i).childNodes(5).childNodes(1).firstChild.text);
<br>
document.write(&quot;'&gt;&quot;);<br>
document.write(xmlObj.childNodes(i).childNodes(5).childNodes(1).firstChild.text);
<br>
document.write(&quot;&lt;/a&gt;&lt;br&gt;&quot;);<br>
}<br>
if (xmlObj.childNodes(i).childNodes(5).childNodes(2).hasChildNodes != 
false) { <br>
document.write(&quot;HTML Download: &lt;a href='&quot;);<br>
document.write(xmlObj.childNodes(i).childNodes(5).childNodes(2).firstChild.text);
<br>
document.write(&quot;'&gt;&quot;);<br>
document.write(xmlObj.childNodes(i).childNodes(5).childNodes(2).firstChild.text);
<br>
document.write(&quot;&lt;/a&gt;&lt;br&gt;&quot;);<br>
}<br>
if (xmlObj.childNodes(i).childNodes(5).childNodes(3).hasChildNodes != 
false) { <br>
document.write(&quot;Library Reference: &lt;a href='&quot;);<br>
document.write(xmlObj.childNodes(i).childNodes(5).childNodes(3).firstChild.text);
<br>
document.write(&quot;'&gt;&quot;);<br>
document.write(xmlObj.childNodes(i).childNodes(5).childNodes(3).firstChild.text);
<br>
document.write(&quot;&lt;/a&gt;&lt;br&gt;&quot;);<br>
}<br>
<br>
document.write(&quot;&lt;br&gt;&lt;hr width='75%')&lt;br&gt;&quot;);<br>
i++;<br>
}</code></font></td>
</tr>
</table>
</blockquote>
<p>Lastly, let's make sure to let the browser know we are done writing 
JavaScript</p>
<blockquote>
<table border="0" style="border-collapse: collapse" width="85%" id="table16" bgcolor="#C0C0C0">
<tr>
<td><font size="2" face="Arial"><code>&lt;/script&gt;</code></font></td>
</tr>
</table>
</blockquote>
<p>Now you may be wondering about that large section of code right 
before we ended with the &lt;/script&gt; tag.&nbsp; There's actually a lot to 
it but I'll try to explain as best as I can.&nbsp; The first thing 
you'll see is <code>numNodes = </code><font size="2" face="Arial"><code>
xmlObj.childNodes.length-1;</code></font><code><font face="Arial">.</font></code><font face="Times New Roman"> 
This line puts in the variable numNodes how many nodes or listings are 
in the XML feed.&nbsp; We then start a while loop to go through each and 
every one of them.</font></p>
<blockquote>
<table border="0" style="border-collapse: collapse" width="85%" id="table17" bgcolor="#C0C0C0">
<tr>
<td><font size="2" face="Arial"><code>if (xmlObj.childNodes(i).childNodes(1).hasChildNodes != false) { <br>
document.write(&quot;Title: &quot;);<br>
document.write(xmlObj.childNodes(i).childNodes(1).firstChild.text); <br>
document.write(&quot;&lt;br&gt;&quot;);<br>
}</code></font></td>
</tr>
</table>
</blockquote>
<p>The above code we'll use explain the rest of the code.&nbsp; First 
thing it does is check to see if xmlObj.childNodes(i)... is empty.&nbsp; 
If it is not empty then we'll display the information in that element 
otherwise that part of the code will be skipped over.&nbsp; You can 
access the information in an element by using <code>xmlObj.childNodes(i).childNodes(1).firstChild.text</code>.&nbsp; 
Now the second childNodes(1) can be incremented for each additional 
element.&nbsp; Elements below elements would be expressed as <code>
xmlObj.childNodes(i).childNodes(0).childNodes(1).firstChild.text</code>. 
Depending upon which feed you are using will determine how many of these 
you use as well as specifically how you use them.</p>
<p>This should give you a basic overview on how to use JavaScript to 
include XML feeds on your site.&nbsp; While this isn't a complete lesson 
on XML Parsing with JavaScript it does cover the basics.&nbsp; The code 
displayed here is a very generic display and you should definitely 
format it to make it look best on your own site.&nbsp; You should be 
able to figure out now how to do this using the code we've went over.&nbsp; 
See below for both the output from the above code as well as the full 
source code.</p>
</blockquote>
<table border="0" style="border-collapse: collapse" width="95%" id="table8" bgcolor="#D8E4F1">
<tr>
<td><b><font face="Arial"><a name="2.2"></a>2.2 Sample Output</font></b></td>
<td align="right"><font face="Arial" size="2"><a href="#top">Back to 
top</a></font></td>
</tr>
</table>
<blockquote>
<p><img border="0" src="output_js.png" width="768" height="576"></p>
</blockquote>
<table border="0" style="border-collapse: collapse" width="95%" id="table9" bgcolor="#D8E4F1">
<tr>
<td><a name="2.3"></a><b><font face="Arial">2.3 Source Code</font></b></td>
<td align="right"><font face="Arial" size="2"><a href="#top">Back to 
top</a></font></td>
</tr>
</table>
<blockquote>
<table border="0" style="border-collapse: collapse" width="85%" id="table18" bgcolor="#C0C0C0">
<tr>
<td><font size="2" face="Arial"><code><br>
&lt;script language=&quot;JavaScript&quot;&gt;<br>var xmlDoc = new ActiveXObject(&quot;Microsoft.XMLDOM&quot;);
<br><br>
function loadXML(xmlFile) <br>{<br>
xmlDoc.async=&quot;false&quot;;<br>xmlDoc.load(xmlFile); <br>xmlObj=xmlDoc.documentElement; 
<br>}<br><br>loadXML('http://www.josephgruber.com/dp-devel/feeds/backend.php?content=posted');<br>
<br>i = 0;<br>numNodes = xmlObj.childNodes.length-1;<br>while (i &lt;= numNodes)<br>{<br>if (xmlObj.childNodes(i).childNodes(1).hasChildNodes != false) {
<br>document.write(&quot;Title: &quot;);<br>document.write(xmlObj.childNodes(i).childNodes(1).firstChild.text); 
<br>document.write(&quot;&lt;br&gt;&quot;);<br>}<br>if (xmlObj.childNodes(i).childNodes(2).hasChildNodes != false) {
<br>document.write(&quot;Author: &quot;);<br>document.write(xmlObj.childNodes(i).childNodes(2).firstChild.text); 
<br>document.write(&quot;&lt;br&gt;&quot;);<br>}<br>if (xmlObj.childNodes(i).childNodes(3).hasChildNodes != false) {
<br>document.write(&quot;Language: &quot;);<br>document.write(xmlObj.childNodes(i).childNodes(3).firstChild.text); 
<br>document.write(&quot;&lt;br&gt;&quot;);<br>}<br>if (xmlObj.childNodes(i).childNodes(4).hasChildNodes != false) {
<br>document.write(&quot;Genre: &quot;);<br>document.write(xmlObj.childNodes(i).childNodes(4).firstChild.text); 
<br>document.write(&quot;&lt;br&gt;&quot;);<br>}<br>if (xmlObj.childNodes(i).childNodes(5).childNodes(0).hasChildNodes != 
false) { <br>document.write(&quot;Text Download: &lt;a href='&quot;);<br>document.write(xmlObj.childNodes(i).childNodes(5).childNodes(0).firstChild.text);
<br>document.write(&quot;'&gt;&quot;);<br>document.write(xmlObj.childNodes(i).childNodes(5).childNodes(0).firstChild.text);
<br>document.write(&quot;&lt;/a&gt;&lt;br&gt;&quot;);<br>}<br>if (xmlObj.childNodes(i).childNodes(5).childNodes(1).hasChildNodes != 
false) { <br>document.write(&quot;Zip Download: &lt;a href='&quot;);<br>document.write(xmlObj.childNodes(i).childNodes(5).childNodes(1).firstChild.text);
<br>document.write(&quot;'&gt;&quot;);<br>document.write(xmlObj.childNodes(i).childNodes(5).childNodes(1).firstChild.text);
<br>document.write(&quot;&lt;/a&gt;&lt;br&gt;&quot;);<br>}<br>if (xmlObj.childNodes(i).childNodes(5).childNodes(2).hasChildNodes != 
false) { <br>document.write(&quot;HTML Download: &lt;a href='&quot;);<br>document.write(xmlObj.childNodes(i).childNodes(5).childNodes(2).firstChild.text);
<br>document.write(&quot;'&gt;&quot;);<br>document.write(xmlObj.childNodes(i).childNodes(5).childNodes(2).firstChild.text);
<br>document.write(&quot;&lt;/a&gt;&lt;br&gt;&quot;);<br>}<br>if (xmlObj.childNodes(i).childNodes(5).childNodes(3).hasChildNodes != 
false) { <br>document.write(&quot;Library Reference: &lt;a href='&quot;);<br>document.write(xmlObj.childNodes(i).childNodes(5).childNodes(3).firstChild.text);
<br>document.write(&quot;'&gt;&quot;);<br>document.write(xmlObj.childNodes(i).childNodes(5).childNodes(3).firstChild.text);
<br>document.write(&quot;&lt;/a&gt;&lt;br&gt;&quot;);<br>}<br>
<br>document.write(&quot;&lt;br&gt;&lt;hr width='75%')&lt;br&gt;&quot;);<br>i++;<br>}<br>&lt;/script&gt;<br>
&nbsp;</code></font></td>
</tr>
</table>
</blockquote>
</blockquote>
