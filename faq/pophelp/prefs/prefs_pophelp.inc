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
 *
 * The content of this file is grouped by the tabs in user preferences:
 * General / Proofreading / Project Manager
*/

$prefs_pophelp = array(

// =====================================================================
// General Tab Settings

'set_align' =>
    array (
    'title' => _("Statistics Bar Alignment"),
    'content' =>
_("<p>Choose a side of the page for the Statistics Bar to appear on.</p>")
    ),

// OBSOLETE?
//'set_topten' =>
//    array (
//    'title' => _("Show Top Ten"),
//    'content' => _("
//<p>Toggles the display of the top 10 list of volunteers with the highest page 
//counts to appear in the Statistics Bar on various pages.</p>")
//    ),

'set_updates' =>
    array (
    'title' => _("E-mail Updates"),
    'content' =>
_("<p>(Future Use) We may send out messages to all active users,
such as a location change or major changes to the site.</p>")
    ),

'set_creditswanted' =>
    array (
    'title' => _("Credits Wanted"),
    'content' =>
_("<p>These checkboxes indicate which activities you wish to be credited for (acknowledged) when a project is completed.</p>
<p>CP = 'Content Providing,' IP = 'Image Preparing,' TP = 'Text Preparing,' PM = 'Project Managing,' PP = 'Post-Processing'</p>")
    ),

'set_creditname' =>
    array (
    'title' => _("Credit Name"),
    'content' =>
_("<p>This setting specifies how your credits will appear when a project is completed.</p>
<p>Choose from using your Real Name (e.g. John Doe), your Username (e.g. jdoe), or 'Other.'</p>
<p>Selecting 'Other' will use the text entered as your Credit Name.</p>")
    ),

'set_email' =>
    array (
    'title' => _("E-mail"),
    'content' => _("<p>Your current e-mail address.</p>")
    ),

'set_intlang' =>
    array (
    'title' => _("Interface Language"),
    'content' => _("<p>The language that the site will be presented in.</p>")
    ),

// OBSOLETED. MAYBE.
//'set_lang' =>
//    array (
//    'title' => _("Language"),
//    'content' => _("Your primary language.")
//    );

'set_name' =>
    array (
    'title' => _("Name"),
    'content' => _("<p>Your real name.</p>")
    ),

'set_neighbors' =>
    array (
    'title' => _("Show Rank Neighbors"),
    'content' =>
_("<p>Sets the number of neighbors (volunteers closest to you in page 
count) which are displayed in statistics.</p>
<p>Set this to 0 (zero) to disable this feature.</p>")
    ),

// FIXME
// Relocate elsewhere on userprefs screen, away from
// actual settings, possibly to the to bottom right.
// It is NOT a usersetting, but (debatably) a convenience link
// which is rather pointless, as in practice, a user should not be
// able to see this screen without being signed in.

'set_password' =>
    array (
    'title' => _("Password"),
    'content' =>
_("<p>This is <strong>NOT</strong> a preference setting.</p>
<p>If you need to reset your password, follow the link.
You will be sent an email confirmation of your password change,
so be sure that your current email address is on record.</p>")
    ),

'set_privacy' =>
    array (
    'title' => _("Statistics Privacy"),
    'content' =>
_("<p>This setting controls who is able to see your statistics.</p>
<ul>
<li><b>Private:</b> Only logged-in users.</li>
<li><b>Anonymous:</b> Only you and administrators.</li>
</ul>")
    ),

'set_theme' =>
    array (
    'title' => _("Theme"),
    'content' => _("<p>This setting determines which site theme is used as your default.</p>")
    ),

// =====================================================================
// PM Tab Settings

'set_auto_thread' =>
    array (
    'title' => _("Watching Project Threads"),
    'content' =>
_("<p>This setting determines if you will be automatically signed up for
email notifications when a forum thread is created for one of your projects.</p>
<p>Changing this setting only applies to forum threads created in the future.
If you wish to change the notification on existing forum threads, you will need to do so manually.</p>")
    ),

'set_pmdefault' =>
    array (
    'title' => _("Default Project Manager Page"),
    // TRANSLATORS: PM = project manager
    'content' => _("<p>This setting determines which display is used by default when loading the PM page.</p>")
    ),

'set_pmto_post' =>
    array (
    'title' => _("Send Projects to PP Pool"),
    'content' =>
_("<p>You may choose either to have your projects placed automatically in
the pool of projects available for Post-Processing, <strong>or</strong>
have them assigned directly to you.</p>

<p>Selecting 'Yes' automatically sends your projects to the post-processing pool when they have completed all rounds.</p>
<p>Selecting 'No' automatically assigns your projects to you as Post-Processor when they have completed all rounds.</p>

<p>Changing the option <strong>does not</strong> affect projects already in Post-Processing.</p>")
    ),

// =====================================================================
// Proofreading Tab Settings


'set_facetype' =>
    array (
    'title' => _("Interface Type"),
    'content' =>
_("<p>This setting determines which proofreading interface you prefer.</p>

<p>The Standard Interface is the default and is designed to work with very minimal 
browser requirements.</p>

<p>The Enhanced Interface has additional tools and features which are not present in the 
Standard Interface and may require more a recent browser for complete functionality.</p>

<p>Both interfaces require a frames and JavaScript enabled browser as a minimum.</p>")
    ),

// FIXME In the userprefs display, add hover text to these icons
// which say 'Vertical Layout' and 'Horizontal Layout'

'set_layout' =>
    array (
    'title' => _("Interface Layout"),
    'content' =>
_("<p>This setting specifies your preferred proofreading interface layout.</p>
<p>The white and grey areas of the icons represent the image and text frames, respectively.</p>")
    ),

'set_horzprefs' =>
    array (
    'title' => _("Horizontal Interface Preferences"),
    'content' =>
_("<p>Settings in this group affect how the proofreading interface is displayed when using the 
'Horizontal' Interface Layout.</p>")
    ),

'set_vertprefs' =>
    array (
    'title' => _("Vertical Interface Preferences"),
    'content' =>
_("<p>Settings in this group affect how the proofreading interface is displayed when using the
'Vertical' Interface Layout.</p>")
    ),

// FIXME Needless duplication. The settings are stored differently, but the controls and
// descriptions are identical. The calls to these popups should be harmonized and the
// duplication collapsed.

'set_h_fontface' =>
    array (
    'title' => _("Font Face"),
    'content' => sprintf(_("<p>This setting specifies the Font Face used for the proofreading area. See the <a target='_blank' href='%s'>Font Sample</a> page for samples of these fonts.</p>"), "$code_url/faq/font_sample.php")
    ),

'set_v_fontface' =>
    array (
    'title' => _("Font Face"),
    'content' => sprintf(_("<p>This setting specifies the Font Face used for the proofreading area. See the <a target='_blank' href='%s'>Font Sample</a> page for samples of these fonts.</p>"), "$code_url/faq/font_sample.php")
    ),

'set_h_fontsize' =>
    array (
    'title' => _("Font Size"),
    'content' => _("<p>This setting specifies the Font Size used for the proofreading area.</p>")
    ),

'set_v_fontsize' =>
    array (
    'title' => _("Font Size"),
    'content' => _("<p>This setting specifies the Font Size used for the proofreading area.</p>")
    ),

'set_font_sample' =>
    array (
    'title' => _("Font Sample"),
    'content' => _("<p>This text displays what the proofreading text will look like using the specified font face and size. If you are using an 'Other' font and the font does not display, check that the name matches exactly, including spaces. Some browsers restrict what local fonts can be used.</p>")
    ),

'set_h_scroll' =>
    array (
    'title' => _("Scroll Text Frame"),
    'content' => _("<p>Enables/Disables scrollbars on the text frame.</p>")
    ),

'set_v_scroll' =>
    array (
    'title' => _("Scroll Text Frame"),
    'content' => _("<p>Enables/Disables scrollbars on the text frame.</p>")
    ),

'set_h_textlength' =>
    array (
    'title' => _("Length of Text Lines"),
    'content' => _("<p>This setting specifies the maximum number of characters to display for lines of text in the proofreading area.</p>")
    ),

'set_v_textlength' =>
    array (
    'title' => _("Length of Text Lines"),
    'content' => _("<p>This setting specifies the maximum number of characters to display for lines of text in the proofreading area.</p>")
    ),

'set_h_textlines' =>
    array (
    'title' => _("Number of Text Lines"),
    'content' => _("<p>This setting specifies the number of lines of text to display in the proofreading area.</p>")
    ),

'set_v_textlines' =>
    array (
    'title' => _("Number of Text Lines"),
    'content' => _("<p>This setting specifies the number of lines of text to display in the proofreading area.</p>")
    ),

'set_h_textsize' =>
    array (
    'title' => _("Text Frame Size"),
    'content' =>
_("<p>This setting specifies what percentage of the browser window will be used by the proofreading area.</p>
<p>The remainder of the proofreading window will contain the image of the scanned book page.
The lower control frame is not included when calculating this percentage.</p>")
    ),

'set_v_textsize' =>
    array (
    'title' => _("Text Frame Size"),
    'content' =>
_("<p>This setting specifies what percentage of the browser window will be used by the proofreading area.</p>
<p>The remainder of the proofreading window will contain the image of the scanned book page.
The lower control frame is not included when calculating this percentage.</p>")
    ),

'set_h_wrap' =>
    array (
    'title' => _("Wrap Text"),
    'content' => _("<p>Enable/Disable wrapping of lines in the proofreading area that exceed the 'Length of Text Lines' setting.</p>")
    ),

'set_v_wrap' =>
    array (
    'title' => _("Wrap Text"),
    'content' => _("<p>Enable/Disable wrapping of lines in the proofreading area that exceed the 'Length of Text Lines' setting.</p>")
    ),

'set_h_zoom' =>
    array (
    'title' => _("Image Zoom"),
    'content' => _("<p>This setting specifies a default zoom percentage for the image width of the page image.</p>")
    ),

'set_v_zoom' =>
    array (
    'title' => _("Image Zoom"),
    'content' => _("<p>This setting specifies a default zoom percentage for the image width of the page image.</p>")
    ),

'set_newwindow' =>
    array (
    'title' => _("Launch in New Window"),
    'content' =>
_("<p>This setting specifies if the proofreading interface will launch in a new window or not.</p>")
    ),

'set_statusbar' =>
    array (
    'title' => _("Show Status Bar"),
    'content' =>
_("<p>This setting specifies if the standard browser status bar should be displayed when 'Launch in New Window' is set to Yes.</p>")
    ),

'set_toolbar' =>
    array (
    'title' => _("Show Toolbar"),
    'content' =>
_("<p>This setting specifies if the standard browser toolbars should be displayed when 'Launch in New Window' is set to Yes.</p>")
    ),

// FIXME This setting, and all the code that uses it and the 1000px for calculations should die. :D
'set_screenres' =>
    array (
    'title' => _("Screen Resolution"),
    'content' => _("<p>You may select a screen resolution from the dropdown list.</p>")
    ),

'set_showspecialcolors' =>
    array (
    'title' => _("Show Special Colors"),
    'content' =>
_("<p>This setting specifies if colors used to indicate special projects should be displayed in the project listings.</p>")
    ),

'set_profilename' =>
    array (
    'title' => _("Profile Name"),
    'content' =>
_("<p>This field displays the name of the current profile, and can be edited to create a new profile.</p>
<p>Entering a new name and and clicking 'Save as New Profile' will save a new profile with that name.</p>
<p>A maximum of 10 profiles can be saved.</p>")
    ),

'set_switch' =>
    array (
    'title' => _("Switch Profiles"),
    'content' =>
_("<p>This dropdown displays a list of your saved profiles.
Select one and click on 'Switch Profiles' to set it as your new default profile.</p>")
    )

);


