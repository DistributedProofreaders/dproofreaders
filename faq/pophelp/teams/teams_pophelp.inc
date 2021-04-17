<?php
/* Transition file from old pophelp static HTML files to arrays.
 *
 * These are defined as follows:
 *
 * 'setting_name' =>         // The old HTML filename, minus the extension
 *     array (
 *     'title'    => _(""),  // The <title> element from the old files
 *     'content'  => _("")   // The actual help text, as valid HTML
 *     ),
*/

$teams_pophelp = [

    'edit_teamname' =>
        [
            'title' => _("Team Name"),
            'content' =>
_("<p>Use this field to provide a name for your team. Team names are limited to 50 characters.</p>"),
        ],

    // FIXME These sizes aren't specified or enforced. Ditto filesizes.
    'edit_teamicon' =>
        [
            'title' => 'Team Icon',
            'content' =>
_("<p>You may upload a 25x25 GIF, JPG or PNG image to display next to your team's name in the team listings.</p>"),
        ],

    // FIXME These sizes aren't specified or enforced. Ditto filesizes.
    'edit_teamavatar' => [
        'title' => 'Team Avatar',
        'content' =>
"<p>You may upload a GIF, JPG or PNG image up to 200x150 pixels to use as your team logo (a.k.a. avatar) on your team's statistics page.</p>",
    ],

    // FIXME Why is markup allowed here?
    'edit_teamdesc' => [
        'title' => 'Team Description',
        'content' =>
"<p>Use this field for a description of your team.</p>

<p>The following markup is allowed in the description:</p>
<ul>
<li>[b][/b]=<b>bold</b> </li>
<li>[i][/i]=<i>italic</i> </li>
<li>[p][/p]=paragraph </li>
<li>[lb]=line break </li>
</ul>

<p>Standard HTML tags will be removed from the description.</p>",
    ],

    'edit_teamwebpage' =>
        [
            'title' => 'Team Webpage',
            'content' =>
"<p>Use this field to provide a link to your team's homepage.
It must be in the format of http://www.yourwebpage.com.
If none is provided, it will default to the home page of this site.</p>

<p>Standard HTML tags will be removed from the webpage.</p>",
        ],

];
