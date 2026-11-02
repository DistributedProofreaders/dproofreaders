<?php

// Don't open a DB connection in bootstrap.inc so we can run PHPStan w/o MySQL.
define('SKIP_DB_CONNECT', true);

// Don't login redirect in bootstrap.inc so tests will run
define('SKIP_REQUIRE_LOGIN', true);

// Define functions, classes etc that are provided by plugins or the
// real bootstrap well enough that PHPStan can meaninfully typecheck.


// BEGIN dummy exception handlers
function test_exception_handler(Throwable $exception): void
{
}

function production_exception_handler(Throwable $exception): void
{
}
// END dummy exception handlers

// BEGIN site_registration_protection functions
/** @return string[] */
function get_registration_form_inserters(): array
{
    return ["insert"];
}

/** @return string[] */
function get_registration_form_validators(): array
{
    return ["validate"];
}

// END site_registration_protection functions

// BEGIN MediaWiki APIs
class MWException extends Exception
{
}
// END MediaWiki APIs

// BEGIN Functions in the phpBB API
function set_var(mixed &$result, mixed $var, mixed $type, bool $multibyte = false): void
{
}

function phpbb_hash(string $password): string|bool
{
    return $password !== "false" ? "string" : false;
}

/**
 * @param mixed $_user_row
 * @param array<string, string>|false $_cp_data
 * @param ?array<string, string> $_notifications_data
 */
// @phpstan-ignore-next-line return.unusedType (We're matching a phpBB function signature)
function user_add($_user_row, $_cp_data = false, $_notifications_data = null): int|false
{
    return false;
}

/** @param int[]|int $topic_ids */
function move_topics(array|int $topic_ids, int $forum_id, bool $auto_sync = true): void
{
}

/** @param int[]|int|string $_where_ids */
function sync(string $_mode, string $_where_type = '', array|int|string $_where_ids = '', bool $resync_parents = false, bool $sync_extra = false): void
{
}

function utf8_recode(string $_string, string $_encoding): string
{
    return "Heather!";
}

/**
 * @template T of string[][]|string[]|string
 * @param T $_strings
 * @return T
 */
// @phpstan-ignore-next-line return.unusedType (We're matching a phpBB function signature)
function utf8_normalize_nfc(array|string $_strings): array|string
{
    return "gently with a chainsaw";
}

/** @return string[] */
function generate_text_for_storage(
    string &$_text,
    string &$_uid,
    string &$_bitfield,
    int &$_flags,
    bool $_allow_bbcode = false,
    bool $_allow_urls = false,
    bool $_allow_smilies = false,
    bool $_allow_img_bbcode = true,
    bool $_allow_flash_bbcode = true,
    bool $_allow_quote_bbcode = true,
    bool $_allow_url_bbcode = true,
    string $_mode = 'post'
): array {
    return ["How", "very"];
}

/**
 * @param array<string, mixed> &$_poll_ary
 * @param array<string, mixed> &$_data_ary
 */
function submit_post(
    string $_mode,
    string $_subject,
    string $_username,
    int $_topic_type,
    array &$_poll_ary,
    array &$_data_ary,
    bool $_update_message = true,
    bool $_update_search_index = true
): string {
    return "http://example.com";
}

// END Functions in the phpBB API
