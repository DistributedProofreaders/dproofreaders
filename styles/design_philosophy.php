<?php
$relPath="../pinc/";
include_once($relPath."base.inc");
include_once($relPath."theme.inc");

output_header("Style Design Philosophy", NO_STATSBAR);

?>

<h1>Style Design Philosophy</h1>

<h2>DP Style History</h2>
<p>The DP codebase has grown very organically over the years, starting out in 2000 when CSS was young and browser support for CSS was very poor. Since that time developers have added new code and styling for code in a variety of ways. CSS, and browser support for it, has come a <b>long</b> ways in 17 years and it's time to get a common look-and-feel using modern CSS.</p>

<h2>Style Demo</h2>
<p>See the <a href='style_demo.php'>Style Demo</a> page for some examples of oft-used DP styles.</p>

<h2>Design Goals</h2>
<h3>Consistency</h3>
<p>One of the goals of the style redesign is <b>consistency</b> across the site. Fonts, colors, table layouts, page layouts, etc should all be consistent across the site. This means that many pages will change such that all look alike.</p>

<h3>Modern CSS</h3>
<p>Another goal of the style redesign is using <b>modern CSS</b>. This means getting rid of &lt;font&gt; tags and HTML attributes that specify style and putting all of that into CSS files. We are aiming to support CSS 3.0, although most of what we want to implement is actually 2.1. This means that while the site will function on all browsers, older browsers that do not support modern CSS may not get the optimal visual experience.</p>

<h2>Recommendations</h2>
<h3>Headers</h3>
<p>Pages should generally start with an &lt;h1&gt; header at the top of the page. There are often cases when you might not want a header at all in which case it can be left off. If you need more sections on the page, use increasing header tags to create them (h1 to h2 to h3, etc).</p>

<p><b>Do not</b> use an &lt;h2&gt; tag as the first tag just because you want smaller text.</p>

<h3>Centering</h3>
<p>Early DP designers were fascinated with centering. Everything was centered. Tables in pages would be less than 100% and centered on the page. Don't do this. In the age of tabs, centering content makes it "jump around" when you have the page up in a background tab, resize the window for the foreground tab, and go back. By left-aligning text the user knows where to look to find the start of page content.</p>

<h3>Tables</h3>
<p>Use tables for showing tabular data and divs for doing layout. Lots of old code still uses tables for layout however.</p>

<p>Don't put table titles in the table. You probably want a header instead.</p>

<h3>Re-use existing styles</h3>
<p>Give a long, hard thought on why you can't use an existing style before creating a new one. If you're showing an error use the .error class, .warning for a warning. Reuse the existing table styles for new tables.</p>
