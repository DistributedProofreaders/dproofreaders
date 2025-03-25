<?php

class SiteConfig
{
    private static bool $_is_loaded = false;

    // alert messages
    public static bool $maintenance = false;
    public static string $maintenance_message = "";
    public static string $alert_message = "";

    // database configuration
    public static string $db_server = "localhost";
    public static string $db_user;
    public static string $db_password;
    public static string $db_name;

    // archiving configuration
    public static ?string $archive_db_name;
    public static ?string $archive_projects_dir;

    // directories & URLs
    public static string $code_dir;
    public static string $code_url;
    public static string $projects_dir;
    public static string $projects_url;
    public static string $dyn_dir;
    public static string $dyn_url;
    public static string $dyn_locales_dir;
    public static ?string $blog_url;
    public static ?string $wiki_url;

    // site identification
    public static string $site_name;
    public static string $site_abbreviation;
    public static string $site_url;

    // forums
    public static string $forum_type = "phpbb3";
    public static ?string $forums_phpbb_table_prefix;
    public static ?string $forums_phpbb_dir;
    public static ?string $forums_phpbb_url;
    public static ?string $forums_json_users;
    public static ?string $forums_json_posts = null;

    // topic forums
    public static ?int $beginners_site_forum_idx;
    public static ?int $waiting_projects_forum_idx;
    public static ?int $projects_forum_idx;
    public static ?int $pp_projects_forum_idx;
    public static ?int $posted_projects_forum_idx;
    public static ?int $deleted_projects_forum_idx;
    public static ?int $completed_projects_forum_idx;
    public static ?int $post_processing_forum_idx;
    public static ?int $teams_forum_idx;

    // proofreading controls
    public static string $preceding_proofer_restriction = 'not_immediately_preceding';
    public static bool $public_page_details = false;

    // uploads
    public static string $uploads_dir;
    public static string $uploads_subdir_trash = "TRASH";
    public static string $uploads_subdir_commons = "Commons";
    public static string $uploads_subdir_users = "Users";
    public static string $antivirus_executable;

    // WordCheck & locales
    public static string $aspell_executable = '/usr/bin/aspell';
    public static string $aspell_prefix = "/usr";
    public static string $system_locales_dir = '/usr/share/locale';

    // API
    public static bool $api_enabled = true;
    public static bool $api_rate_limit = false;
    public static int $api_rate_limit_requests_per_window = 3600;
    public static int $api_rate_limit_seconds_in_window = 3600;
    public static array $api_storage_keys = [];

    // emails
    public static array $phpmailer_smtp_config = [];
    public static ?string $no_reply_email_addr;  // only used by noncvs code
    public static ?string $general_help_email_addr;
    public static ?string $site_manager_email_addr;
    public static ?string $auto_email_addr;
    public static ?string $db_requests_email_addr;
    public static ?string $promotion_requests_email_addr;
    public static ?string $ppv_reporting_email_addr;
    public static ?string $image_sources_manager_addr;
    public static ?string $translation_coordinator_email_addr;

    // Misc configuration
    public static bool $use_secure_cookies = false;
    public static string $php_cli_executable = '/usr/bin/php';
    public static ?string $site_registration_protection_code = null;
    public static bool $auto_post_to_project_topic = false;
    public static bool $ordinary_users_can_see_queue_settings = true;
    public static string $external_catalog_locator = 'lx2.loc.gov:210/LCDB';
    public static array $default_project_char_suites = ["basic-latin"];
    public static bool $testing = false;

    public static function load(string $filename): void
    {
        if (SiteConfig::$_is_loaded) {
            return;
        }

        try {
            require $filename;
        } catch (Error $error) {
            throw new RuntimeException("Configuration file $filename not found");
        }

        $ro = new ReflectionObject(new SiteConfig());
        foreach (get_class_vars("SiteConfig") as $var_name => $var_value) {
            if (str_starts_with($var_name, "_")) {
                continue;
            }

            if (isset($$var_name)) {
                // if it's set to something that isn't null
                SiteConfig::$$var_name = $$var_name;
            } elseif (!$ro->getProperty($var_name)->hasDefaultValue()) {
                // if it doesn't have a default value, and the value wasn't
                // set to something, see if we can set it to null and if not
                // raise a configuration error.
                if ($ro->getProperty($var_name)->getType()->allowsNull()) {
                    SiteConfig::$$var_name = null;
                } else {
                    throw new RuntimeException("Configuration error, $var_name is not set.");
                }
            }
        }

        // we make some temporary exceptions for pervasive globals
        global $code_url, $code_dir, $projects_dir, $projects_url;
        $code_url = SiteConfig::$code_url;
        $code_dir = SiteConfig::$code_dir;
        $projects_url = SiteConfig::$projects_url;
        $projects_dir = SiteConfig::$projects_dir;
    }
}

SiteConfig::load(__DIR__ . "/site_vars.php");
