<?php
$relPath="./../../pinc/";
include_once($relPath.'base.inc');
include_once($relPath.'theme.inc');
include_once($relPath.'user_is.inc');

require_login();

if ( !user_is_a_sitemanager() )
{
    die(_("You are not authorized to access this page."));
}

$title=_("Site Administration");
output_header($title);

echo "<h1>$title</h1>";

echo "<p>" . _("This page provides access to site administration utilities.") . "</p>";

$sections = array(
    _("User") => array(
        "manage_site_access_privileges.php" => _("Manage Site Access Privileges"),
        "../pending_access_requests.php" => _("Pending Access Requests"),
        "show_access_log.php" => _("Show Access Log"),
        "edit_mail_address_for_non_activated_user.php" => _("Resend Account Activation Email"),
    ),
    _("Project") => array(
        "copy_pages.php" => _("Copy Pages"),
        "delete_pages.php" => _("Delete Pages"),
        "rename_pages.php" => _("Rename Pages"),
        "../project_manager/clearance_check.php?username=" => _("Questionable Clearances"),
    ),
    _("Site") => array(
        "sitenews.php" => _("Manage Site News"),
        "manage_random_rules.php" => _("Manage Random Rules"),
        "manage_special_days.php" => _("Manage Special Days"),
        "shared_postednums.php" => _("Detect duplicate postednum"),
        "manage_site_word_lists.php" => _("Manage site word lists"),
        "show_common_words_from_project_word_lists.php" => _("Show common words from project word lists"),
        "../../locale/translators/index.php" => _("Translation Center"),
    ),
);

if($site_supports_metadata)
{
    $sections[_("Site")]["proj_approvals.php"] = _("Copyright approvals");
}

foreach($sections as $section => $pages)
{
    echo "<h2>$section</h2>";
    echo "<ul>";
    foreach($pages as $page => $label)
    {
        echo "<li><a href='$page'>$label</a></li>";
    }
    echo "</ul>";
}

// vim: sw=4 ts=4 expandtab
