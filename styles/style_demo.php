<?php
$relPath = "../pinc/";
include_once($relPath."base.inc");
include_once($relPath."theme.inc");

output_header("Style Demos");

?>

<h1>Style Demos</h1>

<p>This page demonstrates how common page elements are rendered in the current theme. Logged-in users can change their theme in their <a href='../userprefs.php'>Preferences</a>.</p>

<h2>Quick Links</h2>

<ul class='quick-links'>
  <li><a href='#headings'>Headings</a></li>
  <li><a href='#paragraphs'>Paragraphs</a></li>
  <li><a href='#links'>Links</a></li>
  <li><a href='#table-formats'>Table formats</a></li>
</ul>

<h2 id='headings'>Headings</h2>
<h1>Heading 1</h1>
<h2>Heading 2</h2>
<h3>Heading 3</h3>
<h4>Heading 4</h4>
<h5>Heading 5</h5>

<h2 id='paragraphs'>Paragraphs</h2>
<p>This is a paragraph of standard text.</p>

<p>This is a long paragraph of standard text. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam in felis eget lectus volutpat imperdiet eget accumsan urna. Duis vitae dictum justo. Sed blandit massa sed auctor convallis. Donec lobortis tortor eget lectus auctor eleifend. Nulla dignissim tristique aliquam. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Proin pellentesque purus eu nisl varius tempor. Integer consequat pharetra velit, a lobortis enim. Mauris nisi risus, euismod ut mollis sit amet, convallis sit amet nulla. Sed molestie nisi et tellus luctus, a dictum eros pulvinar.</p>

<p class='warning'>This paragraph is intended to convey a warning of some sort.</p>

<p class='error'>This paragraph is intended to convey an error of some sort.</p>

<h2 id='links'>Links</h2>
<p><a href='../activity_hub.php'>Activity Hub</a></p>

<h2 id='table-formats'>Table formats</h2>
<h3>Un-styled table</h3>
<table>
<tr> <th>Column 1</th> <th>Column 2</th> <th>Column 3</th> </tr>
<tr> <td>Data 1</td> <td>Data 2</td> <td>Data 3</td> </tr>
<tr> <td>Data 1</td> <td>Data 2</td> <td>Data 3</td> </tr>
<tr> <td>Data 1</td> <td>Data 2</td> <td>Data 3</td> </tr>
</table>

<h3>Basic table</h3>
<table class='basic'>
<tr> <th>Column 1</th> <th>Column 2</th> <th>Column 3</th> </tr>
<tr> <td>Data 1</td> <td>Data 2</td> <td>Data 3</td> </tr>
<tr> <td>Data 1</td> <td>Data 2</td> <td>Data 3</td> </tr>
<tr> <td>Data 1</td> <td>Data 2</td> <td>Data 3</td> </tr>
</table>

<h3>Striped table</h3>
<table class='striped'>
<tr> <th>Column 1</th> <th>Column 2</th> <th>Column 3</th> </tr>
<tr> <td>Data 1</td> <td>Data 2</td> <td>Data 3</td> </tr>
<tr> <td>Data 1</td> <td>Data 2</td> <td>Data 3</td> </tr>
<tr> <td>Data 1</td> <td>Data 2</td> <td>Data 3</td> </tr>
</table>

<h3>Basic striped table</h3>
<table class='basic striped'>
<tr> <th>Column 1</th> <th>Column 2</th> <th>Column 3</th> </tr>
<tr> <td>Data 1</td> <td>Data 2</td> <td>Data 3</td> </tr>
<tr> <td>Data 1</td> <td>Data 2</td> <td>Data 3</td> </tr>
<tr> <td>Data 1</td> <td>Data 2</td> <td>Data 3</td> </tr>
</table>

<h3>Themed table</h3>
<table class='themed'>
<tr> <th>Column 1</th> <th>Column 2</th> <th>Column 3</th> </tr>
<tr> <td>Data 1</td> <td>Data 2</td> <td>Data 3</td> </tr>
<tr> <td>Data 1</td> <td>Data 2</td> <td>Data 3</td> </tr>
<tr> <td>Data 1</td> <td>Data 2</td> <td>Data 3</td> </tr>
</table>

<h3>Theme-striped table</h3>
<table class='theme_striped'>
<tr> <th>Column 1</th> <th>Column 2</th> <th>Column 3</th> </tr>
<tr> <td>Data 1</td> <td>Data 2</td> <td>Data 3</td> </tr>
<tr> <td>Data 1</td> <td>Data 2</td> <td>Data 3</td> </tr>
<tr> <td>Data 1</td> <td>Data 2</td> <td>Data 3</td> </tr>
</table>

<h3>Themed & themed-striped table</h3>
<table class='themed theme_striped'>
<tr> <th>Column 1</th> <th>Column 2</th> <th>Column 3</th> </tr>
<tr> <td>Data 1</td> <td>Data 2</td> <td>Data 3</td> </tr>
<tr> <td>Data 1</td> <td>Data 2</td> <td>Data 3</td> </tr>
<tr> <td>Data 1</td> <td>Data 2</td> <td>Data 3</td> </tr>
</table>


