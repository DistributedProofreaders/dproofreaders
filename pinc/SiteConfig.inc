<?php
// This file must not include any other file as it is used in upgrade
// and other system scripts to access configuration information.

class SiteConfig
{
    private static ?SiteConfig $_site_config = null;

    // alert messages
    public bool $maintenance = false;
    public string $maintenance_message = "";
    public string $alert_message = "";

    // database configuration
    public string $db_server = "localhost";
    public string $db_user;
    public string $db_password;
    public string $db_name;

    // archiving configuration
    public ?string $archive_db_name;
    public ?string $archive_projects_dir;

    // data retention configuration
    public int $days_to_retain_projects_before_archiving = 100;
    public int $days_to_retain_page_data_after_posting = 365;
    public int $days_to_retain_pending_accounts = 90;
    public int $days_to_retain_job_logs = 365 + 30; // ~13 months

    // directories & URLs
    public string $code_dir;
    public string $code_url;
    public string $projects_dir;
    public string $projects_url;
    public string $dyn_dir;
    public string $dyn_url;
    public string $dyn_locales_dir;
    public ?string $blog_url;
    public ?string $wiki_url;

    // site identification
    public string $site_name;
    public string $site_abbreviation;
    public string $site_url;

    // forums
    public string $forum_type = "phpbb3";
    public ?string $forums_phpbb_table_prefix;
    public ?string $forums_phpbb_dir;
    public ?string $forums_phpbb_url;
    public ?string $forums_json_users = null;
    public ?string $forums_json_posts = null;

    // topic forums
    public ?int $beginners_site_forum_idx;
    public ?int $waiting_projects_forum_idx;
    public ?int $projects_forum_idx;
    public ?int $pp_projects_forum_idx;
    public ?int $posted_projects_forum_idx;
    public ?int $deleted_projects_forum_idx;
    public ?int $completed_projects_forum_idx;
    public ?int $post_processing_forum_idx;
    public ?int $teams_forum_idx;

    // proofreading controls
    public string $preceding_proofer_restriction = 'not_immediately_preceding';
    public bool $public_page_details = false;

    // uploads
    public string $uploads_dir;
    public string $uploads_subdir_trash = "TRASH";
    public string $uploads_subdir_commons = "Commons";
    public string $uploads_subdir_users = "Users";
    public ?string $antivirus_executable = null;

    // WordCheck & locales
    public string $aspell_executable = '/usr/bin/aspell';
    public string $aspell_prefix = "/usr";
    public string $system_locales_dir = '/usr/share/locale';

    // API
    public bool $api_enabled = true;
    public bool $api_rate_limit = false;
    public int $api_rate_limit_requests_per_window = 3600;
    public int $api_rate_limit_seconds_in_window = 3600;
    public array $api_storage_keys = [];

    // emails
    public array $phpmailer_smtp_config = [];
    public ?string $no_reply_email_addr;  // only used by noncvs code
    public ?string $general_help_email_addr;
    public ?string $site_manager_email_addr;
    public ?string $auto_email_addr;
    public ?string $db_requests_email_addr;
    public ?string $promotion_requests_email_addr;
    public ?string $ppv_reporting_email_addr;
    public ?string $image_sources_manager_addr;
    public ?string $translation_coordinator_email_addr;

    // Misc configuration
    public bool $use_secure_cookies = false;
    public string $php_cli_executable = '/usr/bin/php';
    public ?string $site_registration_protection_code = null;
    public bool $auto_post_to_project_topic = false;
    public bool $ordinary_users_can_see_queue_settings = true;
    public string $external_catalog_locator = 'lx2.loc.gov:210/LCDB';
    public array $default_project_char_suites = ["basic-latin"];
    public bool $testing = false;

    protected function __construct(string $filename)
    {
        try {
            require $filename;
        } catch (Error $error) {
            throw new RuntimeException("Configuration file $filename not found");
        }

        $ro = new ReflectionObject($this);
        foreach (get_class_vars("SiteConfig") as $var_name => $var_value) {
            if (str_starts_with($var_name, "_")) {
                continue;
            }

            if (isset($$var_name)) {
                // if it's set to something that isn't null
                $this->$var_name = $$var_name;
            } elseif (!$ro->getProperty($var_name)->hasDefaultValue()) {
                // if it doesn't have a default value, and the value wasn't
                // set to something, see if we can set it to null and if not
                // raise a configuration error.
                if ($ro->getProperty($var_name)->getType()->allowsNull()) {
                    $this->$var_name = null;
                } else {
                    throw new RuntimeException("Configuration error, $var_name is not set.");
                }
            }
        }

        // we make some temporary exceptions for pervasive globals
        global $code_url, $code_dir, $projects_dir, $projects_url;
        $code_url = $this->code_url;
        $code_dir = $this->code_dir;
        $projects_url = $this->projects_url;
        $projects_dir = $this->projects_dir;
    }

    public static function load(string $filename)
    {
        if (self::$_site_config) {
            return;
        }

        self::$_site_config = new SiteConfig($filename);
    }

    public static function get()
    {
        return self::$_site_config;
    }
}

SiteConfig::load(__DIR__ . "/site_vars.php");
