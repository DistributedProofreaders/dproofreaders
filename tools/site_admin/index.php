<?php
$relPath="./../../pinc/";
include_once($relPath.'base.inc');
include_once($relPath.'theme.inc');
include_once($relPath.'user_is.inc');

require_login();

if ( !user_is_a_sitemanager() )
{
    die("You are not authorized to access this page.");
}

$title=_("Site Administration");
output_header($title);

echo "<h1>$title</h1>";

echo "<p>" . _("This page provides access to site administration utilities.") . "</p>";

$pages = array(
    "edit_mail_address_for_non_activated_user.php" => _("Send activation email for new user"),
    "manage_site_access_privileges.php" => _("Manage Site Access Privileges"),
    "manage_special_days.php" => _("Manage Special Days"),
    "sitenews.php" => _("Manage Site News"),
// This is a "metadata rounds" file, and unused. Don't provide link.
//    "proj_approvals.php" => _("Manage copyright clearance approvals"),
    "copy_pages.php" => _("Copy Pages from One Project to Another"),
    "rename_pages.php" => _("Rename pages"),
    "shared_postednums.php" => _("Detect duplicate postednum"),
    "displayrandrules.php" => _("Display Random Rules"),
    "manage_site_word_lists.php" => _("Manage site word lists"),
    "show_common_words_from_project_word_lists.php" => _("Show common words from project word lists"),
);

echo "<ul>";
foreach($pages as $page => $label)
    echo "<li><a href='$page'>$label</a></li>";
echo "</ul>";

// vim: sw=4 ts=4 expandtab
