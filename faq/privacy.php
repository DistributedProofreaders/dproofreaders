<?php

// This file can be accessed directly in which case it needs appropriate
// HTML tags, and it can be include()d into other files, such as
// addproofer.php. We determine which is the case by checking if $relPath
// is set or not.
if (!isset($relPath)) {
    $relPath = "./../pinc/";
    include_once($relPath.'base.inc');
    include_once($relPath.'theme.inc');

    if (!isset($_GET['embedded'])) {
        output_header(_("Privacy Statement"), false);
    }
}


$test_system_access_string = '';
if ($testing) {
    $test_system_access_string = "<span class='test_warning'>";
    $test_system_access_string .= _('Because this is a testing site, developers with login access to this server will also have access to the Real Name you provide.');
    $test_system_access_string .= "</span>";
}

// The privacy statement is particularly important and thus needs to be
// translateable. To do this we use _() on every sentence (not on every
// paragraph to reduce the number of new translations required should
// any given sentence change). This makes the code more difficult to read
// though, particularly since many strings need $site_name replaced in them.
// To mitigate that, some wrapper functions are used to take straightforward
// _() strings and output them by first pre-processing all strings with
// sprintf(<string>, $site_name). sprintf will ignore unused arguments so
// this works fine for strings without $site_name substitutions.


echo "<h2>" . _('Privacy Statement') . "</h2>";

output_section(
    _('Usage of information'),
    [
        _('When you register, %s will send a confirmation/introduction message to your e-mail address.'),
        _('You may also receive optional e-mail alerts from our discussion forums and occasional notifications and feedback related to your proofreading activities.'),
        _('%1$s may also invite you by e-mail to vote in periodic Board of Trustees elections.'),
        _('You may also be contacted for site-related purposes by %1$s administrators.'),
    ]
);

output_section(
    _('Access to your information'),
    [
        _('Real Name: Each book that %1s produces includes a credits line to identify the people holding certain roles (Content Provider, Image Preparer, Text Preparer, Post Processor, and Project Manager) who have worked on the book.'),
        _('If you take on any of these roles, you can choose (in your Preferences) how or whether you prefer to be credited.'),
        _('Apart from this possibility, the information you enter as your Real Name in your Preferences will be accessible only to our site administrators.'),
        $test_system_access_string,
    ],
    [
        _('E-mail Address: Your e-mail address will be accessible only to %s administrators unless you make it available to other volunteers yourself, either by telling them or sending email to them from your email client.'),
    ],
    [
        _('How did you hear about us: Your answer to the "How did you hear about us?" question on the registration form is for general statistical purposes and any tie to your profile will be visible only to our site administrators.'),
    ],
    [
        _('During the various production phases, your User Name and your %s work may be viewable by other volunteers.'),
        _('In addition, administrators and volunteers delegated to an evaluation or mentoring role will be able to review the work performed under your User Name.'),
    ],
    [
        _('You have the option to make your %1$s statistics private so that logged-in users can see them, or anonymous so that only you and %1$s administrators can see them.'),
        _('This includes information such as the number of pages you have proofread, when you were last on the site, etc., that is viewable from your member details (personal stats) page, the sidebar on the round pages, and the Statistics Central page.'),
    ],
    [
        _('Your User Name and any public information you provide in your forum profile will be accessible to other %s volunteers and to unregistered guests using the forums.'),
        _('Your posts on our discussion forums will be viewable by other volunteers.'),
        _('Certain clearly-designated forums are also viewable by unregistered guests to the forums.'),
        _('Your User Name and any information you post on the wiki are visible to both volunteers and unregistered guests using the wiki.'),
    ]
);

output_section(
    _('Tracking information'),
    [
        _('%s tracks your User Name, the date your account was created and your last login date.'),
        _('If you set your statistics to "anonymous", the date your account was created and your last login date will be visible only to administrators.'),
        _('Otherwise, they will be visible to other logged-in users.'),
    ],
    [
        _('%s automatically receives and records information from your computer and browser, including your IP address.'),
        _('This is accessible to our site administrators and forum moderators only.'),
    ],
    [
        _("The system also tracks the number of pages you have completed, your best day ever, team memberships, proofreading roles, and the highest rank you've achieved."),
        _('In your %1$s site profile, as mentioned above, you can control whether this information is viewable by logged-in users or only by yourself and %1$s administrators.'),
    ]
);

output_section(
    _('Sharing Information'),
    [
        _('For the purposes of this policy, the term "%s site administrators" includes the General Manager (GM) and the members of the GM\'s staff with the rank of "squirrel".'),
        _('"%s administrators" includes site administrators and "project facilitators".'),
        _('The terms "site administrator" and "administrator" refer to roles associated with the %1$s website.'),
    ],
    [
        _('The Distributed Proofreaders Foundation is a separate organization whose Trustees may happen to also be administrators of the %s website, but often are not.'),
        sprintf(_('Should you make a formal complaint to the Distributed Proofreaders Foundation Board of Trustees pursuant to the DP <a href="%s">Code of Conduct</a>, it may be necessary for Board members as part of their investigation to review details concerning your work at DP and your DP communications.'), 'https://www.pgdp.net/wiki/DP_Official_Documentation:General/Code_of_Conduct'),
    ],
    [
        _('%s will disclose user data in response to valid, compulsory legal processes from government agencies with appropriate jurisdiction and authority.'),
    ],
    [
        _('%s never makes commercial use of information entered on or for its website.'),
        _('It does not share volunteer information other than as indicated above.'),
    ]
);

//---------------------------------------------------------------------------

function insert_site_name($sentence)
{
    global $site_name;
    return sprintf($sentence, $site_name);
}

/**
 * Output a paragraph of sentences.
 *
 * Each sentence will have the first sprintf argument replaced with $site_name.
 */
function output_paragraph($sentences)
{
    echo "<p>";
    echo implode(" ", array_map("insert_site_name", $sentences));
    echo "</p>";
}

/**
 * Output a section of the privacy statement, including the section header
 * and one or more paragraphs.
 *
 * The number of arguments this function accepts is variable, and is of the
 * format ($header, $paragraph1, $paragraph2, ...) where each paragraph is
 * an array of strings (ie: sentences).
 */
function output_section()
{
    $arguments = func_get_args();
    $header = array_shift($arguments);
    echo "<h3>$header</h3>";
    foreach ($arguments as $paragraph) {
        output_paragraph($paragraph);
    }
}
