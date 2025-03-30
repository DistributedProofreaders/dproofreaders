<?php

class SiteConfig
{
    private static ?SiteConfig $_site_config = null;

    private static array $_default_values = [
        "maintenance" => false,
        "maintenance_message" => "",
        "alert_message" => "",
        "db_server" => "localhost",
        "forum_type" => "phpbb3",
        "forums_json_users" => null,
        "forums_json_posts" => null,
        "preceding_proofer_restriction" => 'not_immediately_preceding',
        "public_page_details" => false,
        "uploads_subdir_trash" => "TRASH",
        "uploads_subdir_commons" => "Commons",
        "uploads_subdir_users" => "Users",
        "antivirus_executable" => null,
        "aspell_executable" => '/usr/bin/aspell',
        "aspell_prefix" => "/usr",
        "system_locales_dir" => '/usr/share/locale',
        "api_enabled" => true,
        "api_rate_limit" => false,
        "api_rate_limit_requests_per_window" => 3600,
        "api_rate_limit_seconds_in_window" => 3600,
        "api_storage_keys" => [],
        "phpmailer_smtp_config" => [],
        "use_secure_cookies" => false,
        "php_cli_executable" => '/usr/bin/php',
        "site_registration_protection_code" => null,
        "auto_post_to_project_topic" => false,
        "ordinary_users_can_see_queue_settings" => true,
        "external_catalog_locator" => 'lx2.loc.gov:210/LCDB',
        "default_project_char_suites" => ["basic-latin"],
        "testing" => false,
        "blog_url" => "BLOG",
    ];

    // alert messages
    public readonly bool $maintenance;
    public readonly string $maintenance_message;
    public readonly string $alert_message;

    // database configuration
    public readonly string $db_server;
    public readonly string $db_user;
    public readonly string $db_password;
    public readonly string $db_name;

    // archiving configuration
    public readonly ?string $archive_db_name;
    public readonly ?string $archive_projects_dir;

    // directories & URLs
    public readonly string $code_dir;
    public readonly string $code_url;
    public readonly string $projects_dir;
    public readonly string $projects_url;
    public readonly string $dyn_dir;
    public readonly string $dyn_url;
    public readonly string $dyn_locales_dir;
    public readonly ?string $blog_url;
    public readonly ?string $wiki_url;

    // site identification
    public readonly string $site_name;
    public readonly string $site_abbreviation;
    public readonly string $site_url;

    // forums
    public readonly string $forum_type;
    public readonly ?string $forums_phpbb_table_prefix;
    public readonly ?string $forums_phpbb_dir;
    public readonly ?string $forums_phpbb_url;
    public readonly ?string $forums_json_users;
    public readonly ?string $forums_json_posts;

    // topic forums
    public readonly ?int $beginners_site_forum_idx;
    public readonly ?int $waiting_projects_forum_idx;
    public readonly ?int $projects_forum_idx;
    public readonly ?int $pp_projects_forum_idx;
    public readonly ?int $posted_projects_forum_idx;
    public readonly ?int $deleted_projects_forum_idx;
    public readonly ?int $completed_projects_forum_idx;
    public readonly ?int $post_processing_forum_idx;
    public readonly ?int $teams_forum_idx;

    // proofreading controls
    public readonly string $preceding_proofer_restriction;
    public readonly bool $public_page_details;

    // uploads
    public readonly string $uploads_dir;
    public readonly string $uploads_subdir_trash;
    public readonly string $uploads_subdir_commons;
    public readonly string $uploads_subdir_users;
    public readonly ?string $antivirus_executable;

    // WordCheck & locales
    public readonly string $aspell_executable;
    public readonly string $aspell_prefix;
    public readonly string $system_locales_dir;

    // API
    public readonly bool $api_enabled;
    public readonly bool $api_rate_limit;
    public readonly int $api_rate_limit_requests_per_window;
    public readonly int $api_rate_limit_seconds_in_window;
    public readonly array $api_storage_keys;

    // emails
    public readonly array $phpmailer_smtp_config;
    public readonly ?string $no_reply_email_addr;  // only used by noncvs code
    public readonly ?string $general_help_email_addr;
    public readonly ?string $site_manager_email_addr;
    public readonly ?string $auto_email_addr;
    public readonly ?string $db_requests_email_addr;
    public readonly ?string $promotion_requests_email_addr;
    public readonly ?string $ppv_reporting_email_addr;
    public readonly ?string $image_sources_manager_addr;
    public readonly ?string $translation_coordinator_email_addr;

    // Misc configuration
    public readonly bool $use_secure_cookies;
    public readonly string $php_cli_executable;
    public readonly ?string $site_registration_protection_code;
    public readonly bool $auto_post_to_project_topic;
    public readonly bool $ordinary_users_can_see_queue_settings;
    public readonly string $external_catalog_locator;
    public readonly array $default_project_char_suites;
    public readonly bool $testing;

    public function __construct(string $filename)
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
            } elseif (array_key_exists($var_name, self::$_default_values)) {
                // set it to the default value
                $this->$var_name = self::$_default_values[$var_name];
            } elseif ($ro->getProperty($var_name)->getType()->allowsNull()) {
                // if it can be null, set it to null
                $this->$var_name = null;
            } else {
                throw new RuntimeException("Configuration error, $var_name is not set.");
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
