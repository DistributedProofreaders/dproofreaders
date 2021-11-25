<?php
$relPath = '../pinc/';
include_once($relPath.'base.inc');
include_once($relPath.'theme.inc');

/*
Author: cpeel
Created: 2016-05-03
*/

$title = _("Distributed Proofreaders Branding");

output_header($title, NO_STATSBAR);

echo "<h1>$title</h1>";

echo "<p>" . _("Consistent branding and logos are an important part of today's world. This page provides some guidance when referring to Distributed Proofreaders as well as visual assets for both web and print.") . "</p>";

echo "<h2>" . _("Referring to Distributed Proofreaders") . "</h2>";
echo "<p>" . _("Distributed Proofreaders refers to the web site at <a href='https://www.pgdp.net'>www.pgdp.net</a>. It is governed by the <a href='https://www.pgdp.net/wiki/DPF'>Distributed Proofreaders Foundation</a>. Community members affectionately refer to it as simply 'DP', but use the full 'Distributed Proofreaders' name for clarity when outside the community.") . "</p>";

echo "<p>" . _("Other entities with similar names:") . "</p>";

echo "<ul>";
echo "<li>" . _("Distributed Proofreaders has many sister sites, some of which have 'DP' in their name, such as DP Canada. These are separate entities and are not legally connected to pgdp.net.") . "</li>";
echo "<li>" . _("The source code that powers the pgdp.net website is open source software hosted at <a href='https://github.com'>GitHub</a>. The source code project is named <a href='https://github.com/DistributedProofreaders/dproofreaders'>dproofreaders</a> and while technically separate from pgdp.net, it is very closely tied to pgdp.net and the needs of pgdp.net drive almost all features within that project.") . "</li>";
echo "</ul>";

echo "<h2>" . _("Logos and Marks") . "</h2>";

echo "<p>" . _("The following are DP logos and marks that can be used to link back to <a href='https://www.pgdp.net'>https://www.pgdp.net</a>. An SVG is included, as well as PNGs at different sizes. Some of the PNGs have a white background and others use a transparent background. Use whichever makes sense for your purposes. If you need different sizes, you are encouraged to generate them from the SVG file, using a program like <a href='http://www.inkscape.org'>Inkscape</a>, rather than resizing the PNGs.") . "</p>";

echo "<p>" . _("For print media, you are encouraged to use the black and white version without the drop shadow. If your design software supports it, use the SVG version for the best output.") . "</p>";

echo "<p>" . _("The SVG files have had the text changed into paths for best consumability. See <a href='#fonts_and_colors'>Fonts and Colors</a> for what fonts were used.") . "</p>";

echo "<h3 id='color_logo'>" . _("Color Logo") . "</h3>";

echo "<h4>" . _("SVG files") . "</h4>";

echo <<<SVGLOGO
        <table class='branding'>
        <tr>
            <td>Transparent<br>Background, light themes</td>
            <td style='background-color:white;'>
                <img src='dp-logo.svg'>
            </td>
        </tr>
        <tr>
            <td></td>
            <td>
                <a href='dp-logo.svg'>dp-logo.svg</a>
            </td>
        </tr>
        <tr>
            <td>Transparent<br>Background, dark themes</td>
        <td style='background-color:black;'>
                <img src='dp-logo-dark.svg'>
            </td>
        </tr>
        <tr>
            <td></td>
            <td>
                <a href='dp-logo-dark.svg'>dp-logo-dark.svg</a>
            </td>
        </tr>
        </table>
    SVGLOGO;

echo "<h4>" . _("PNG files") . "</h4>";

echo <<<LOGO
        <table class='branding'>
        <tr>
            <td>Transparent<br>Background, light themes</td>
            <td style='background-color:white'>
                <img src='dp-logo-500px.png'>
            </td>
        </tr>
        <tr>
            <td></td>
            <td>
                <a href='dp-logo-500px.png'>dp-logo-500px.png</a>
            </td>
        </tr>
        <tr>
            <td>White<br>Background</td>
            <td>
                <img src='dp-logo-500px-white.png'>
            </td>
        </tr>
        <tr>
            <td></td>
            <td>
                <a href='dp-logo-500px-white.png'>dp-logo-500px-white.png</a>
            </td>
        </tr>
        </table>
    LOGO;

echo "<h3 id='bw_logo'>" . _("Solid Black Logo") . "</h3>";

echo "<h4>" . _("SVG file") . "</h4>";

echo <<<SVGLOGO
        <table class='branding'>
        <tr>
            <td>Transparent<br>Background</td>
            <td class='black-transparent'>
                <img src='dp-logo-bw.svg'>
            </td>
        </tr>
        <tr>
            <td></td>
            <td>
                <a href='dp-logo-bw.svg'>dp-logo-bw.svg</a>
            </td>
        </tr>
        </table>
    SVGLOGO;

echo "<h4>" . _("PNG file") . "</h4>";

echo <<<LOGO
        <table class='branding'>
        <tr>
            <td>Transparent<br>Background</td>
            <td class='black-transparent'>
                <img src='dp-logo-bw-500px.png'>
            </td>
        </tr>
        <tr>
            <td></td>
            <td>
                <a href='dp-logo-bw-500px.png'>dp-logo-bw-500px.png</a>
            </td>
        </tr>
        </table>
    LOGO;

echo "<h3 id='mark'>" . _("Mark") . "</h3>";

echo "<h4 id='mark'>" . _("SVG file") . "</h4>";

echo <<<SVGMARK
        <table class='branding'>
        <tr>
            <td>Transparent<br>Background</td>
            <td class='gray'>
                <img style='height:64px;' src='dp-mark.svg'><br>
            </td>
        </tr>
        <tr>
            <td></td>
            <td>
                <a href='dp-mark.svg'>dp-mark.svg</a>
            </td>
        </tr>
        </table>
    SVGMARK;

echo "<h4 id='mark'>" . _("PNG files") . "</h4>";

echo <<<MARK
        <table class='branding'>
        <tr>
            <th>Size</th>
            <th class='gray'>Transparent<br>Background</th>
            <th>White<br>Background</th>
        </tr>
        <tr>
            <td>32 x 32</td>
            <td class='gray'>
                <img src='dp-mark-32px.png'><br>
                <a href='dp-mark-32px.png'>dp-mark-32px.png</a>
            </td>
            <td>
                <img src='dp-mark-32px-white.png'><br>
                <a href='dp-mark-32px-white.png'>dp-mark-32px-white.png</a>
            </td>
        </tr>
        <tr>
            <td>64 x 64</td>
            <td class='gray'>
                <img src='dp-mark-64px.png'><br>
                <a href='dp-mark-64px.png'>dp-mark-64px.png</a>
            </td>
            <td>
                <img src='dp-mark-64px-white.png'><br>
                <a href='dp-mark-64px-white.png'>dp-mark-64px-white.png</a>
            </td>
        </tr>
        <tr>
            <td>120 x 120</td>
            <td class='gray'>
                <img src='dp-mark-120px.png'><br>
                <a href='dp-mark-120px.png'>dp-mark-120px.png</a>
            </td>
            <td>
                <img src='dp-mark-120px-white.png'><br>
                <a href='dp-mark-120px-white.png'>dp-mark-120px-white.png</a>
            </td>
        </tr>
        <tr>
            <td>400 x 400</td>
            <td class='gray'>
                <a href='dp-mark-400px.png'>dp-mark-400px.png</a>
            </td>
            <td>
                <a href='dp-mark-400px-white.png'>dp-mark-400px-white.png</a>
            </td>
        </tr>
        </table>
    MARK;

echo "<h3 id='fonts_and_colors'>" . _("Fonts and Colors") . "</h3>";

echo "<p>" . _("If you are looking to create additional branding assets, here are some guidelines for how the current assets were created.") . "</p>";

echo <<<ASSETS
        <ul>
            <li>DP blue: #007dc1</li>
            <li>Base logo font: Amiri, available for free from <a href='https://www.google.com/fonts#ChoosePlace:select/Collection:Amiri'>Google Fonts</a></li>
            <li>'Preserving History' font: Times New Roman</li>
            <li>DP mark: Garamond</li>
            <li>DP logo and mark drop shadow (created with the Drop Shadow filter in Inkscape):<ul>
                <li>Blur radius: 2.0</li>
                <li>Horiz offset (px): -3.7</li>
                <li>Vertical offset (px): 3.7</li>
                <li>Color (RGBA): 00000048</li>
            </ul></li>
        </ul>
    ASSETS;
