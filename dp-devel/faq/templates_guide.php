<?
$relPath='../pinc/';
include($relPath.'v_site.inc');
include($relPath.'connect.inc');
include($relPath.'theme.inc');
new dbConnect();
$no_stats=1;
theme('Templates available for Project Comments','header');
?>

<style TYPE="text/css">
<!--
  tt {color: red}
        -->
  </style>
<!--
  <style>
                @page { size: 21.59cm 27.94cm; margin-left: 3.18cm; margin-right: 3.18cm; margin-top: 2.54cm; margin-bottom: 2.54cm }
                P { margin-bottom: 0.21cm }
                TD P { margin-bottom: 0.21cm }
  </style>
        -->

<h1 align="center">Layout Templates</h1>
<br><br>
There are two types of template available <br>
<ol>
<li>standard sections of text, like pre-fabricated building blocks, <br>
that can be automatically included in your project comments <br>
wherever you place a special tag. These are particularly useful for series, <br> 
where some text will be common for many individual volumes, but there <br>
are also some standard technical sections that can be <br>
fitted into your comments like jigsaw pieces. </li>
<br><br>
<li>fill-in-the-blank skeleton templates, for which you will have to <br>
manually copy and paste the code and then fill in <br>
specific information relating to the individual project.<br> 
</li><ol>
<br>
<b>Both types can be plain text or HTML.</b>
<br><br>

<h2>Standard Templates</h2>
These can be accessed by pointing to a specific file <br />
within the comments section of the Project Comments form.<br />
<br />
The standard template files currently available are:<br />
<a href="templates_catalog.php#bg1a" target="_blank">BG1a.txt</a> Beginners Only, round one, upper<br />
<a href="templates_catalog.php#bg1b" target="_blank">BG1b.txt</a> Beginners Only, round one, lower<br />
<a href="templates_catalog.php#bgr2" target="_blank">BGr2.txt</a> Beginners Only, round two extra<br />
<a href="templates_catalog#diac" target="_blank">diac.txt</a> diacritical marks<br />
<a href="templates_catalog#pgnm" target="_blank">pgnm.txt</a> preserve index page numbers<br />
<a href="templates_catalog.php#port" target="_blank">port.txt</a> Portuguese National Library<br />
<a href="templates_catalog.php#wrrn" target="_blank">wrrn.txt</a> Warren Commission<br />
<br />
<br />
To place any one of the above samples into your comments simply include the line:<br />
[template=file.txt]<br />
where <i>file.txt</i> is the name of the template you wish to have included <br>
in your project comments. You can use as many as you wish.<br />
<br />
If you would like to create your own re-usable templates similar to those above<br />
please email them as a text file to <a href="mailto:dphelp@pgdp.net">dphelp@pgdp.net</a>.<br />
The filename needs to be four letters in length.
<br>
  <br />
<h2> Skeleton Layout Samples </h2>
If you would like to use a bit more styling within your comments feel free to<br />
create your own look OR use one of the samples below; <br />
More samples will be made available as they are seen around the site<br />
or sent to site development for inclusion here.<br />
<br />
Style names are based on User ID where style was first seen:<br />
<br />
<strong>Cedron:</strong> <a href="templates_catalog.php#cedrlayout">Layout</a> / <a href="templates_catalog.php#cedrcode">Code</a><br />
<strong>Cgehring:</strong> <a href="templates_catalog.php#cgehlayout">Layout</a> / <a href="templates_catalog.php#cgehcode">Code</a> 
 
<br />
<br />

To use these styles copy the code and paste it with your information added<br />
into the commment window on your Project Information page.
<br />
<br />


<?
theme('','footer');
?>