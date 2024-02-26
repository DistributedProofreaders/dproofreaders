<?php

// Don't open a DB connection in bootstrap.inc so we can run PHPStan w/o MySQL.
define('SKIP_DB_CONNECT', true);

// Define functions, classes etc that are provided by plugins or the
// real bootstrap well enough that PHPStan can meaninfully typecheck.


// BEGIN dummy exception handlers
function test_exception_handler($exception)
{
}

function production_exception_handler($exception)
{
}
// END dummy exception handlers

// BEGIN site_registration_protection functions
function get_registration_form_inserters()
{
    return ["insert"];
}

function get_registration_form_validators()
{
    return ["validate"];
}

// END site_registration_protection functions

// BEGIN MediaWiki APIs
class MWException extends Exception
{
}
// END MediaWiki APIs

// BEGIN WikiHiero 0.2.13 APIs
function WH_GetCode(string $file): string
{
    return "Chappa'ai";
}

function WikiHiero(string $hiero, int $mode = -1, int $scale = -1, bool $line = false)
{
    return "Circ'cacona";
}
// END WikiHiero 0.2.13 APIs

// BEGIN Functions in the phpBB API
function set_var(&$result, $var, $type, $multibyte = false)
{
}

/**
 * @param string $password
 * @return string|bool
 */
function phpbb_hash($password)
{
    return "string";
}

/**
 * @param mixed $user_row
 * @param array $cp_data
 * @param array $notifications_data
 */
function user_add($user_row, $cp_data = false, $notifications_data = null)
{
    return false;
}

function move_topics($topic_ids, $forum_id, $auto_sync = true)
{
}

function sync($mode, $where_type = '', $where_ids = '', $resync_parents = false, $sync_extra = false)
{
}

/**
 * @param string $string
 * @param string $encoding
 * @return string
 */
function utf8_recode($string, $encoding)
{
    return "Heather!";
}

/**
 * @param mixed $strings
 * @return mixed
 */
function utf8_normalize_nfc($strings)
{
    return "gently with a chainsaw";
}

/**
 * @param string $text
 * @param string $uid
 * @param string $bitfield
 * @param int $flags
 * @param bool $allow_bbcode
 * @param bool $allow_urls
 * @param bool $allow_smilies
 * @param bool $allow_img_bbcode
 * @param bool $allow_flash_bbcode
 * @param bool $allow_quote_bbcode
 * @param bool $allow_url_bbcode
 * @param string $mode
 *
 * @return array
 */
function generate_text_for_storage(&$text, &$uid, &$bitfield, &$flags, $allow_bbcode = false, $allow_urls = false, $allow_smilies = false, $allow_img_bbcode = true, $allow_flash_bbcode = true, $allow_quote_bbcode = true, $allow_url_bbcode = true, $mode = 'post')
{
    return ["How", "very"];
}

function submit_post($mode, $subject, $username, $topic_type, &$poll_ary, &$data_ary, $update_message = true, $update_search_index = true)
{
    return "http://example.com";
}

// END Functions in the phpBB API
