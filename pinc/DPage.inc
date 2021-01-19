<?php
include_once($relPath.'misc.inc');
include_once($relPath.'site_vars.php');
include_once($relPath.'stages.inc');
include_once($relPath.'abort.inc');
include_once($relPath.'user_project_info.inc');
include_once($relPath.'Project.inc');
include_once($relPath.'unicode.inc');

use voku\helper\UTF8;

// This file brings together code that accesses the projectID* tables.

// The usual semantics for the return value is this:
// If something goes wrong, return a string containing an error message.
// Otherwise (i.e., success), return an empty string.

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function project_allow_pages( $projectid )
{
    validate_projectID($projectid);
    $columns_for_rounds = "";
    for ($rn = 1; $rn <= MAX_NUM_PAGE_EDITING_ROUNDS; $rn++ )
    {
        $round = get_Round_for_round_number($rn);
        $columns_for_rounds .= "
            {$round->time_column_name}   int(20)     NOT NULL default '0',
            {$round->user_column_name}   varchar(25) NOT NULL default '',
            {$round->text_column_name}   longtext    NOT NULL,
            INDEX {$round->user_column_name} ( {$round->user_column_name} ),
        ";
    }

    // While the rest of the DP tables use the MySQL default engine type,
    // usually InnoDB with MySQL 5.5 and later, we still explicitly create
    // project tables as MyISAM. This is primarily because InnoDB versions of
    // project tables are often 3x the size of the MyISAM versions. If you opt
    // to use InnoDB, consider using the ROW_FORMAT=COMPRESSED option which
    // makes project tables only 2x the size of the MyISAM versions, i.e.
    //     $engine = "ENGINE=InnoDB ROW_FORMAT=COMPRESSED";
    $engine = "ENGINE=MyISAM";

    $sql = "
        CREATE TABLE $projectid
        (
            fileid        varchar(20) NOT NULL default '', UNIQUE (fileid),
            image         varchar(12) NOT NULL default '', UNIQUE (image),
            master_text   longtext    NOT NULL,
            $columns_for_rounds
            state         VARCHAR(50) NOT NULL default '', INDEX(state),
            b_user        VARCHAR(25) NOT NULL default '',
            b_code        INT(1)      NOT NULL default '0',
            orig_page_num VARCHAR(6)  NOT NULL default '',
            metadata      SET(
                'blank', 'missing', 'badscan', 'outofseq',
                'acknowledge', 'dedication', 'ednotes', 'foreword', 'intro', 'loi', 'preface', 'prologue', 'toc', 'titlepage',
                'abbreviation', 'division', 'epigraph', 'footnote', 'illustration', 'letter', 'list', 'math', 'poetry', 'sidenote', 'verse', 'table',
                'appendix', 'afterword', 'biblio', 'colophon', 'endnote', 'epilogue', 'index'
                ) NOT NULL default ''
        ) $engine
    ";
    $res = DPDatabase::query($sql, FALSE);
    return ( $res ? '' : DPDatabase::log_error() );
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function project_drop_pages( $projectid )
{
    validate_projectID($projectid);
    $result = DPDatabase::query("DROP TABLE $projectid");

    _project_adjust_n_pages($projectid, 'NONE');
    _project_adjust_n_available_pages($projectid, 'NONE');
}

// -----------------------------------------------------------------------------

function Pages_prepForRound( $projectid, $round_number )
{
    validate_projectID($projectid);
    $round = get_Round_for_round_number($round_number);

    {
        // For the first round, these queries are mostly redundant,
        // because when project_add_page() create a page, 
        // the page gets the first round's avail state.
        // (They're not *entirely* redundant, because the queries will
        // still change the round1_time, but that's fairly unimportant.)
        // 
        // However, it's probably better that this function doesn't rely on
        // how project_add_page() sets page-states.

        $timestamp = time();
        $setters = join(", ", [
            set_col_str("state", $round->page_avail_state),
            set_col_num($round->time_column_name, $timestamp)
        ]);
        $sql = "
            UPDATE $projectid
            SET
                $setters
        ";
        $result = DPDatabase::query($sql);

        _project_adjust_n_available_pages($projectid, 'ALL');
    }
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function project_add_page(
    $projectid,
    $fileid,
    $image_file_name,
    $txt_file_path,
    $user,
    $now )
{
    validate_projectID($projectid);
    $txt_expr = file_content_expr( $txt_file_path, $projectid );

    $round = get_Round_for_round_number(1);
    $page_state = $round->page_avail_state;

    $columns_for_rounds = [];
    for ($rn = 1; $rn <= MAX_NUM_PAGE_EDITING_ROUNDS; $rn++ )
    {
        $round = get_Round_for_round_number($rn);
        $columns_for_rounds[] = "{$round->text_column_name} = ''";
    }
    $columns_for_rounds = join(", ", $columns_for_rounds);
    $setters = join(", ", [
        set_col_str("fileid", $fileid),
        set_col_str("image", $image_file_name),
        set_col_num("round1_time", $now),
        set_col_str("state", $page_state)
    ]);
    $sql = "
        INSERT INTO $projectid
        SET
            $setters,
            master_text = $txt_expr,
            $columns_for_rounds
            ";

    // echo $sql, "\n";
    DPDatabase::query($sql);

    _log_page_event( $projectid, $image_file_name, 'add', $user, NULL );

    _project_adjust_n_pages($projectid, +1);
    _project_adjust_n_available_pages($projectid, +1);
}

function load_page_from_file( $file_path, $projectid )
// Load a page from a file. This function should always be used to load
// page contents from a file to ensure consistency.
{
    $text = file_get_contents( $file_path );
    if( $text === FALSE)
    {
        return NULL;
    }

    // If not UTF-8, attempt to detect and convert it
    if(!UTF8::is_utf8($text))
    {
        $encoding = guess_string_encoding($text);
        if($encoding === FALSE)
        {
            $text = mb_convert_encoding($text, "UTF-8", "ISO-8859-1");
        }
        elseif($encoding != 'UTF-8')
        {
            $text = mb_convert_encoding($text, "UTF-8", $encoding);
        }
    }

    return _normalize_page_text($text, $projectid);
}

function file_content_expr( $file_path, $projectid )
// Return an SQL expression that will yield:
// -- the contents of the given file as a string
// -- or NULL if the file can't be read.
// $file_path must be absolute.
{
        $txt = load_page_from_file( $file_path, $projectid );
        if ( $txt === NULL )
        {
            return 'NULL';
        }
        return sprintf("'%s'", DPDatabase::escape($txt));
}

// -----------------------------------------------------------------------------

function Page_delete( $projectid, $image, $user )
{
    validate_projectID($projectid);

    $where_image = sprintf("WHERE image = '%s'", DPDatabase::escape($image));
    $sql = "
        SELECT COUNT(*)
        FROM $projectid
        $where_image AND state LIKE '%avail%'
    ";
    $res = DPDatabase::query($sql);
    list($page_was_available) = mysqli_fetch_row($res); // either 0 or 1

    $sql = "
        DELETE
        FROM $projectid
        $where_image
    ";
    DPDatabase::query($sql);

    _log_page_event( $projectid, $image, 'delete', $user, NULL );

    _project_adjust_n_pages( $projectid, -1 );
    if ($page_was_available)
    {
        _project_adjust_n_available_pages( $projectid, -1 );
    }
}

// -----------------------------------------------------------------------------

function Page_replaceImage( $projectid, $image, $new_image, $user )
// e.g., replace 001.png with 001.jpg
{
    $setter = set_col_str("image", $new_image);
    _Page_UPDATE( $projectid, $image, $setter);

    _log_page_event($projectid, $image, 'replaceImage', $user, NULL); // $new_image
}

// -----------------------------------------------------------------------------

function Page_replaceText( $projectid, $image, $text_file_path, $user )
{
    $text_file_content_expr = file_content_expr( $text_file_path, $projectid );
    $setter = "master_text=$text_file_content_expr";
    _Page_UPDATE($projectid, $image, $setter);

    _log_page_event($projectid, $image, 'replaceText', $user, NULL);
}

// -----------------------------------------------------------------------------

function Page_modifyText( $projectid, $image, $page_text, $text_column, $user )
{
    $page_text = _normalize_page_text($page_text, $projectid);
    $setter = set_col_str($text_column, $page_text);
    _Page_UPDATE( $projectid, $image, $setter);
    $round = get_Round_for_text_column_name($text_column);

    _log_page_event($projectid, $image, 'modifyText', $user, $round);
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function Page_checkout( $projectid, $image, $round, $user )
{
    _Page_require( $projectid, $image,
        array( $round->page_avail_state ),
        NULL, NULL,
        'checkout' );

    $timestamp = time();
    $setters = join(", ", [
        set_col_str("state", $round->page_out_state),
        set_col_num($round->time_column_name, $timestamp),
        set_col_str($round->user_column_name, $user)
    ]);
    _Page_UPDATE($projectid, $image, $setters);

    _log_page_event($projectid, $image, 'checkout', $user, $round );

    _project_adjust_n_available_pages( $projectid, -1 );

    return $round->page_out_state;
}

// -----------------------------------------------------------------------------

function Page_saveAsInProgress( $projectid, $image, $round, $user, $page_text )
{
    _Page_require( $projectid, $image,
        array( $round->page_out_state, $round->page_temp_state ),
        $round->user_column_name, $user,
        'saveAsInProgress' );

    $timestamp = time();

    $page_text = _normalize_page_text($page_text, $projectid);
    $setters = join(", ", [
        set_col_str("state", $round->page_temp_state),
        set_col_num($round->time_column_name, $timestamp),
        set_col_str($round->user_column_name, $user),
        set_col_str($round->text_column_name, $page_text)
    ]);
    _Page_UPDATE($projectid, $image, $setters);

    _log_page_event($projectid, $image, 'saveAsInProgress', $user, $round);
    upi_set_t_latest_page_event($user, $projectid, time());

    return $round->page_temp_state;
}

// -----------------------------------------------------------------------------

function Page_saveAsDone( $projectid, $image, $round, $user, $page_text )
{
    _Page_require( $projectid, $image,
        array( $round->page_out_state, $round->page_temp_state ),
        $round->user_column_name, $user,
        'saveAsDone' );

    $timestamp = time();

    $page_text = _normalize_page_text($page_text, $projectid);
    $setters = join(", ", [
        set_col_str("state", $round->page_save_state),
        set_col_num($round->time_column_name, $timestamp),
        set_col_str($round->user_column_name, $user),
        set_col_str($round->text_column_name, $page_text)
    ]);
    _Page_UPDATE($projectid, $image, $setters);

    _log_page_event($projectid, $image, 'saveAsDone', $user, $round);
    upi_set_t_latest_page_event($user, $projectid, time());

    _project_set_t_last_page_done( $projectid, $timestamp );

    return $round->page_save_state;
}

// -----------------------------------------------------------------------------

function Page_reopen( $projectid, $image, $round, $user )
{
    _Page_require( $projectid, $image,
        array( $round->page_save_state, $round->page_temp_state ),
        $round->user_column_name, $user,
        'reopen' );

    $timestamp = time();
    $setters = join(", ", [
        set_col_str("state", $round->page_temp_state),
        set_col_num($round->time_column_name, $timestamp),
        set_col_str($round->user_column_name, $user)
    ]);
    _Page_UPDATE($projectid, $image, $setters);

    _log_page_event($projectid, $image, 'reopen', $user, $round);

    return $round->page_temp_state;
}

// -----------------------------------------------------------------------------

function Page_returnToRound( $projectid, $image, $round, $user )
{
    _Page_require( $projectid, $image,
        array( $round->page_out_state, $round->page_temp_state ),
        $round->user_column_name, $user,
        'returnToRound' );

    $timestamp = time();
    $setters = join(", ", [
        set_col_str("state", $round->page_avail_state),
        set_col_num($round->time_column_name, $timestamp),
        set_col_str($round->user_column_name, $user)
    ]);
    _Page_UPDATE($projectid, $image, $setters);

    _log_page_event($projectid, $image, 'returnToRound', $user, $round);

    _project_adjust_n_available_pages($projectid, +1);

    return $round->page_avail_state;
}

// -----------------------------------------------------------------------------

function Page_reclaim( $projectid, $image, $round, $user )
{
    _Page_require( $projectid, $image,
        array( $round->page_out_state, $round->page_temp_state ),
        NULL, NULL,
        'reclaim' );

    $setters = join(", ", [
        set_col_str("state", $round->page_avail_state),
        set_col_num($round->time_column_name, 0)
    ]);
    _Page_UPDATE($projectid, $image, $setters);

    _log_page_event( $projectid, $image, 'reclaim', $user, $round );

    _project_adjust_n_available_pages( $projectid, +1 );
}

// -----------------------------------------------------------------------------

function Page_clearRound( $projectid, $image, $round, $user )
{
    _Page_require( $projectid, $image,
        array( $round->page_save_state ),
        NULL, NULL,
        'clearRound' );

    $setters = join(", ", [
        set_col_str("state", $round->page_avail_state),
        set_col_num($round->time_column_name, 0),
        set_col_str($round->user_column_name, ""),
        set_col_str($round->text_column_name, "")
    ]);

    _Page_UPDATE($projectid, $image, $setters);

    _log_page_event($projectid, $image, 'clearRound', $user, $round);

    _project_adjust_n_available_pages( $projectid, +1 );
}

// -----------------------------------------------------------------------------

$PAGE_BADNESS_REASONS = array(
    '',
    _('Missing Image'),
    _('Missing Text'),
    _('Image/Text Mismatch'),
    _('Corrupted Image'),
    _('Other'),
);

function Page_markAsBad( $projectid, $image, $round, $user, $reason )
{
    _Page_require( $projectid, $image,
        array( $round->page_out_state, $round->page_temp_state ),
        $round->user_column_name, $user,
        'markAsBad' );

    $setters = join(", ", [
        set_col_str("state", $round->page_bad_state),
        set_col_str("b_user", $user),
        set_col_num("b_code", $reason),
        "{$round->text_column_name}={$round->prevtext_column_name}"
    ]);
    _Page_UPDATE($projectid, $image, $setters);

    _log_page_event($projectid, $image, 'markAsBad', $user, $round);
}

// -----------------------------------------------------------------------------

function Page_eraseBadMark( $projectid, $image, $round, $user )
{
    _Page_require( $projectid, $image,
        array( $round->page_bad_state ),
        NULL, NULL,
        'eraseBadMark' );

    $setters = join(", ", [
        set_col_str("state", $round->page_avail_state),
        set_col_str("b_user", ""),
        set_col_num("b_code", 0),
        set_col_str($round->user_column_name, "")
    ]);
    _Page_UPDATE($projectid, $image, $setters);

    _log_page_event($projectid, $image, 'eraseBadMark', $user, $round);

    _project_adjust_n_available_pages($projectid, +1);
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function _Page_require(
    $projectid,
    $image,
    $allowed_states,
    $allowed_user_colname,
    $requesting_user,
    $op_name )
{
    validate_projectID($projectid);
    $user_item = ( is_null($allowed_user_colname) ? '0' : $allowed_user_colname );
    $sql = "
        SELECT state, $user_item
        FROM $projectid
        WHERE image='" . DPDatabase::escape($image) . "'
    ";
    $res = DPDatabase::query($sql);
    list($curr_page_state, $curr_page_owner) = mysqli_fetch_row($res);
    if ( !in_array( $curr_page_state, $allowed_states ) )
    {
        $err = "\n"
            . "    "
            . sprintf(
                _("This operation (%s) requires that the page be in one of the following states:"),
                $op_name )
            . "\n"
            . "        "
            . implode( ' ', $allowed_states )
            . "\n"
            . "    "
            . sprintf( _("But it is in %s"), $curr_page_state )
            ;
        abort($err);
    }

    if (is_null($allowed_user_colname))
    {
        // The operation can be done by anyone,
        // as far as this level is concerned.
        // (Higher levels may restrict who can do it.)
    }
    else
    {
        // The operation can only be done by the person who currently
        // "owns" the page.
        if ($requesting_user != $curr_page_owner)
        {
            $err = "\n"
                . "    "
                . sprintf(
                    _("This operation (%s) can only be done by the user who has the page checked out, which you are not."),
                    $op_name )
                ;
            abort($err);
        }
    }
}

function _Page_UPDATE( $projectid, $image, $settings )
{
    validate_projectID($projectid);
    $sql = "
        UPDATE $projectid
        SET $settings
        WHERE image='" . DPDatabase::escape($image) . "'
    ";
    DPDatabase::query($sql);
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function _normalize_page_text( $page_text, $projectid )
// Normalize a page-text before we store it in the db. This does several
// transformations to ensure we have a UTF-8 string with codepoints
// allowed for the project. This function assumes that $page_text is
// valid UTF-8.
// When updating this function, also update get_normalize_page_text_changes()
// used to report page text changes to users elsewhere.
{
    // Filter characters to just the Latin-1 set
    $project = new Project($projectid);

    // Remove BOM if it exists
    if(UTF8::string_has_bom($page_text))
    {
        $page_text = UTF8::str_replace(UTF8::bom(), '', $page_text);
    }

    # normalize UTF-8 string
    $page_text = utf8_normalize($page_text);

    # replace some Unicode characters with ASCII-equivalents
    foreach(get_utf8_to_ascii_codepoints() as $ascii_replace => $codepoints)
    {
        $page_text = utf8_filter_out_codepoints($page_text, $codepoints, $ascii_replace);
    }

    # filter to desired codepoints
    $page_text = utf8_filter_to_codepoints($page_text, $project->get_valid_codepoints());

    // 1. Eliminate blank lines at the end of the text.
    $page_text = rtrim($page_text);

    // 2. Ensure all line-ends are CR-LF.
    // 3. Eliminate whitespace at the end of each line.

    $patterns = array();
    $repls = array();

    // For each CR that isn't immediately followed by a LF,
    // change it to a CRLF. (Convert Max line-ends to DOS.)
    $patterns[] = '/\r(?!\n)/';
    $repls[]    = "\r\n";

    // For each LF that isn't immediately preceded by a CR,
    // change it to a CRLF. (Convert Unix line-ends to DOS.)
    $patterns[] = '/(?<!\r)\n/';
    $repls[]    = "\r\n";

    // Remove any blanks/tabs at the end of each line
    $patterns[] = '/[ \t]+\r\n/';
    $repls[]    = "\r\n";

    return preg_replace( $patterns, $repls, $page_text );
}

function get_normalize_page_text_changes($text, $projectid)
// Return a list of "notable" changes that a page text will undergo for
// user consumption. This should be updated when _normalize_page_text()
// is updated.
{
    $project = new Project($projectid);

    $changes = [];

    // Remove BOM if it exists
    if(UTF8::string_has_bom($text))
    {
        $text = UTF8::str_replace(UTF8::bom(), '', $text);
    }

    # normalize UTF-8 string
    $text = utf8_normalize($text);

    $invalid_chars = get_invalid_characters($text, $project->get_valid_codepoints());
    if($invalid_chars)
    {
        $whitespace_codepoints = array_get(get_utf8_to_ascii_codepoints(), " ", []);
        $unicode_whitespace_chars = array_map('voku\helper\UTF8::hex_to_chr', $whitespace_codepoints);
        $found_whitespace_chars = array_intersect(array_keys($invalid_chars), $unicode_whitespace_chars);
        if($found_whitespace_chars)
        {
            $whitespace_char_names = [];
            foreach($found_whitespace_chars as $char)
            {
                $name = IntlChar::charName($char);
                if(!$name)
                    $name = IntlChar::charName($char, IntlChar::UNICODE_10_CHAR_NAME);
                $whitespace_char_names[] = $name;
                unset($invalid_chars[$char]);
            }
            $whitespace_char_string = implode(", ", $whitespace_char_names);
            $changes[] = sprintf(_("Whitespace characters will be converted to a space: <span class='mono'>%s</span>"), $whitespace_char_string);
        }
    }

    if($invalid_chars)
    {
        foreach(get_utf8_to_ascii_codepoints() as $ascii_replace => $codepoints)
        {
            # space are handled above, so skip them here
            if($ascii_replace == " ")
                continue;

            $found_chars = [];
            foreach($codepoints as $codepoint)
            {
                $char = UTF8::hex_to_chr($codepoint);
                if(isset($invalid_chars[$char]))
                {
                    $found_chars[] = $char;
                    unset($invalid_chars[$char]);
                }
            }
            if($found_chars)
            {
                $char_string = implode($found_chars);
                $changes[] = sprintf(_("<span class='mono'>%1\$s</span> will be converted to <span class='mono'>%2\$s</span>"), $char_string, $ascii_replace);
            }
        }
    }

    if($invalid_chars)
    {
        $invalid_char_names = [];
        foreach(array_keys($invalid_chars) as $char)
        {
            // in case its a combined character
            $codepoints = UTF8::split($char);
            $base_codepoint = $codepoints[0];
            // if not defined or is a control character or soft hyphen
            if(!IntlChar::isdefined($base_codepoint) || IntlChar::iscntrl($base_codepoint) || ($base_codepoint === "\x{ad}"))
            {
                // put U+xxxx instead of utf8 string
                $invalid_char_names[] = UTF8::chr_to_hex($base_codepoint);
            }
            else
            {
                // combined characters will get here unless first component is caught above
                $title = utf8_character_name($char);
                $invalid_char_names[] = "<span title='$title'>$char</span>";
            }
        }
        $invalid_char_string = implode(", ", $invalid_char_names);
        $changes[] = sprintf(_("Invalid characters will be removed: <span class='mono'>%s</span>"), $invalid_char_string);
    }

    return $changes;
}

function get_load_page_from_file_changes($filename, $projectid)
// Return a list of text page changes should $filename be loaded into a project.
// This should be updated if load_page_from_file() is updated.
{
    $changes = [];

    $text = file_get_contents($filename);
    if( $text === FALSE)
    {
        return $changes;
    }

    if(!UTF8::is_utf8($text))
    {
        $encoding = guess_string_encoding($text);
        if($encoding === FALSE)
        {
            $changes[] = _("Unable to determine file encoding, will convert from ISO-8859-1");
            $text = mb_convert_encoding($text, "UTF-8", "ISO-8859-1");
        }
        elseif($encoding != 'UTF-8')
        {
            $changes[] = sprintf(_("Non-UTF-8 file; will convert from %s"), $encoding);
            $text = mb_convert_encoding($text, "UTF-8", $encoding);
        }
    }

    return array_merge($changes, get_normalize_page_text_changes($text, $projectid));
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function _log_page_event( $projectid, $image, $event_type, $username, $round )
{
    assert( strlen($event_type) <= 16 );

    if (is_null($round))
    {
        $round_id_setting = 'NULL';
    }
    else
    {
        $round_id_setting = "'" . DPDatabase::escape($round->id) . "'";
    };

    $sql = sprintf("
        INSERT INTO page_events
        SET
            timestamp    = UNIX_TIMESTAMP(),
            projectid    = '%s',
            image        = '%s',
            event_type   = '%s',
            username     = '%s',
            round_id     = %s
    ",
        DPDatabase::escape($projectid),
        DPDatabase::escape($image),
        DPDatabase::escape($event_type),
        DPDatabase::escape($username),
        $round_id_setting
    );
    DPDatabase::query($sql);
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function _project_set_t_last_page_done( $projectid, $timestamp )
{
    $sql = sprintf("
        UPDATE projects
        SET t_last_page_done = %d
        WHERE projectid = '%s'
    ",
        $timestamp,
        DPDatabase::escape($projectid)
    );
    DPDatabase::query($sql);

}

function _project_adjust_n_pages( $projectid, $adjustment )
{
    if ( $adjustment === 'NONE' )
    {
        $expr = '0';
    }
    else
    {
        $expr = "n_pages + $adjustment";
    }

    $sql = sprintf("
        UPDATE projects
        SET n_pages = %s
        WHERE projectid='%s'
    ",
        $expr,
        DPDatabase::escape($projectid)
    );
    DPDatabase::query($sql);
}

function _project_adjust_n_available_pages( $projectid, $adjustment )
{
    if ( $adjustment === 'NONE' )
    {
        $expr = '0';
    }
    else if ( $adjustment === 'ALL' )
    {
        $expr = 'n_pages';
    }
    else
    {
        // MySQL 5.5 and later throws errors if n_available_pages is 0 and
        // $adjustment is -1. This is because n_available_ages is unsigned.
        // This shouldn't occur, but sometimes it does, so lets account for it.
        if($adjustment >= 0)
        {
            $expr = "n_available_pages + $adjustment";
        }
        else
        {
            // Note we have to cast both the evaluation and the false condition
            // because of how MySQL evaluates the query, even though the second
            // one (ie: false condition) won't ever be negative.
            $expr = "IF(CAST(n_available_pages AS SIGNED) + $adjustment < 0, 0, CAST(n_available_pages as SIGNED) + $adjustment)";
        }
    }

    $sql = sprintf("
        UPDATE projects
        SET n_available_pages = %s
        WHERE projectid = '%s'
    ",
        $expr,
        DPDatabase::escape($projectid)
    );
    DPDatabase::query($sql);
}

function project_recalculate_page_counts( $projectid )
// Calculate the project's n_pages and n_available_pages from scratch.
{
    validate_projectID($projectid);
    $sql = "
        SELECT COUNT(*), SUM(state LIKE '%.page_avail')
        FROM $projectid
    ";
    $res = DPDatabase::query($sql);

    if ( $res === FALSE )
    {
        // no pages table
        // This could be an archived project, in which case 'n_pages'
        // records the number of pages that the project had, and
        // we don't want to reset that to zero.
        // But resetting 'n_available_pages' should be fine.
        $settings = "n_available_pages=0";
    }
    else
    {
        list($n_pages,$n_available_pages) = mysqli_fetch_row($res);

        // If pages table exists, but is empty,
        // COUNT(*) will be 0, but SUM(...) will be NULL.
        if ( is_null($n_available_pages) ) $n_available_pages = 0;

        $settings = "n_pages=$n_pages, n_available_pages=$n_available_pages";
    }

    $sql = "
        UPDATE projects
        SET $settings
        WHERE projectid='$projectid'
    ";
    DPDatabase::query($sql);
}

// vim: sw=4 ts=4 expandtab
?>
