<?php
$relPath="../pinc/";
include_once($relPath."base.inc");
include_once($relPath."theme.inc");

output_header("Style Demos");

?>

<p>This page shows some of the standard styles.</p>

<h1>Headings</h1>
<h1>Heading 1</h1>
<h2>Heading 2</h2>
<h3>Heading 3</h3>
<h4>Heading 4</h4>
<h5>Heading 5</h5>

<h1>Paragraphs</h1>
<p>This is a paragraph of standard text.</p>

<p>This is a long paragraph of standard text. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam in felis eget lectus volutpat imperdiet eget accumsan urna. Duis vitae dictum justo. Sed blandit massa sed auctor convallis. Donec lobortis tortor eget lectus auctor eleifend. Nulla dignissim tristique aliquam. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Proin pellentesque purus eu nisl varius tempor. Integer consequat pharetra velit, a lobortis enim. Mauris nisi risus, euismod ut mollis sit amet, convallis sit amet nulla. Sed molestie nisi et tellus luctus, a dictum eros pulvinar.</p>

<p class='warning'>This paragraph is intended to convey a warning of some sort.</p>

<p class='error'>This paragraph is intended to convey an error of some sort.</p>

<h1>Links</h1>
<p><a href='../activity_hub.php'>Activity Hub</a></p>

<h1>Table formats<h1>
<h2>Un-styled table</h2>
<table>
<tr> <th>Column 1</th> <th>Column 2</th> <th>Column 3</th> </tr>
<tr> <td>Data 1</th> <td>Data 2</th> <td>Data 3</th> </tr>
<tr> <td>Data 1</th> <td>Data 2</th> <td>Data 3</th> </tr>
<tr> <td>Data 1</th> <td>Data 2</th> <td>Data 3</th> </tr>
</table>

<h2>Basic table</h2>
<table class='basic'>
<tr> <th>Column 1</th> <th>Column 2</th> <th>Column 3</th> </tr>
<tr> <td>Data 1</th> <td>Data 2</th> <td>Data 3</th> </tr>
<tr> <td>Data 1</th> <td>Data 2</th> <td>Data 3</th> </tr>
<tr> <td>Data 1</th> <td>Data 2</th> <td>Data 3</th> </tr>
</table>

<h2>Striped table</h2>
<table class='striped'>
<tr> <th>Column 1</th> <th>Column 2</th> <th>Column 3</th> </tr>
<tr> <td>Data 1</th> <td>Data 2</th> <td>Data 3</th> </tr>
<tr> <td>Data 1</th> <td>Data 2</th> <td>Data 3</th> </tr>
<tr> <td>Data 1</th> <td>Data 2</th> <td>Data 3</th> </tr>
</table>

<h2>Basic striped table</h2>
<table class='basic striped'>
<tr> <th>Column 1</th> <th>Column 2</th> <th>Column 3</th> </tr>
<tr> <td>Data 1</th> <td>Data 2</th> <td>Data 3</th> </tr>
<tr> <td>Data 1</th> <td>Data 2</th> <td>Data 3</th> </tr>
<tr> <td>Data 1</th> <td>Data 2</th> <td>Data 3</th> </tr>
</table>

<h2>Themed table</h2>
<table class='themed'>
<tr> <th>Column 1</th> <th>Column 2</th> <th>Column 3</th> </tr>
<tr> <td>Data 1</th> <td>Data 2</th> <td>Data 3</th> </tr>
<tr> <td>Data 1</th> <td>Data 2</th> <td>Data 3</th> </tr>
<tr> <td>Data 1</th> <td>Data 2</th> <td>Data 3</th> </tr>
</table>

<h2>Theme-striped table</h2>
<table class='theme_striped'>
<tr> <th>Column 1</th> <th>Column 2</th> <th>Column 3</th> </tr>
<tr> <td>Data 1</th> <td>Data 2</th> <td>Data 3</th> </tr>
<tr> <td>Data 1</th> <td>Data 2</th> <td>Data 3</th> </tr>
<tr> <td>Data 1</th> <td>Data 2</th> <td>Data 3</th> </tr>
</table>

<h2>Themed & themed-striped table</h2>
<table class='themed theme_striped'>
<tr> <th>Column 1</th> <th>Column 2</th> <th>Column 3</th> </tr>
<tr> <td>Data 1</th> <td>Data 2</th> <td>Data 3</th> </tr>
<tr> <td>Data 1</th> <td>Data 2</th> <td>Data 3</th> </tr>
<tr> <td>Data 1</th> <td>Data 2</th> <td>Data 3</th> </tr>
</table>


