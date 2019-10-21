<?php
include_once($relPath.'site_vars.php');
include_once($relPath.'stages.inc');
include_once($relPath.'abort.inc');
include_once($relPath.'user_project_info.inc');
include_once($relPath.'Project.inc');

// This file brings together code that accesses the projectID* tables.

// The usual semantics for the return value is this:
// If something goes wrong, return a string containing an error message.
// Otherwise (i.e., success), return an empty string.

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function project_allow_pages( $projectid )
{
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

    $res = mysqli_query(DPDatabase::get_connection(), "
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
        ) ENGINE = MYISAM
    ");
    return ( $res ? '' : mysqli_error(DPDatabase::get_connection()) );
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function project_drop_pages( $projectid )
{
    $result = mysqli_query(DPDatabase::get_connection(), "DROP TABLE $projectid");

    _project_adjust_n_pages( $projectid, 'NONE' );
    _project_adjust_n_available_pages( $projectid, 'NONE' );
}

// -----------------------------------------------------------------------------

function Pages_prepForRound( $projectid, $round_number )
{
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

        $result = mysqli_query(DPDatabase::get_connection(), "
            UPDATE $projectid
            SET
                state = '{$round->page_avail_state}',
                {$round->time_column_name} = $timestamp
        ");
    
        _project_adjust_n_available_pages( $projectid, 'ALL' );
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
    $txt_expr = file_content_expr( $txt_file_path, $projectid );

    $round = get_Round_for_round_number(1);
    $page_state = $round->page_avail_state;

    $errs = '';

    $columns_for_rounds = "";
    for ($rn = 1; $rn <= MAX_NUM_PAGE_EDITING_ROUNDS; $rn++ )
    {
        $round = get_Round_for_round_number($rn);
        $columns_for_rounds .= "
            {$round->text_column_name} = '',
        ";
    }

    $sql = "
        INSERT INTO $projectid
        SET
            fileid      = '$fileid',
            image       = '$image_file_name',
            master_text = $txt_expr,
            $columns_for_rounds
            round1_time = $now,
            state       = '$page_state'
    ";
    // echo $sql, "\n";
    $res = mysqli_query(DPDatabase::get_connection(), $sql);
    if (!$res)
    {
        $errs .= mysqli_error(DPDatabase::get_connection());
    }

    // If the INSERT raised an error, don't do the other stuff.
    if ($errs) return $errs;

    _log_page_event( $projectid, $image_file_name, 'add', $user, NULL );

    _project_adjust_n_pages( $projectid, +1 );
    _project_adjust_n_available_pages( $projectid, +1 );

    return $errs;
}

function file_content_expr( $file_path, $projectid )
// Return an SQL expression that will yield:
// -- the contents of the given file as a string
// -- or NULL if the file can't be read.
// $file_path must be absolute.
{
        $txt = file_get_contents( $file_path );
        if ( $txt === FALSE )
        {
            return 'NULL';
        }
        $txt = _normalize_page_text($txt, $projectid);
        return sprintf("'%s'", mysqli_real_escape_string(DPDatabase::get_connection(), $txt));
}

// -----------------------------------------------------------------------------

function Page_delete( $projectid, $image, $user )
{
    $res = mysqli_query(DPDatabase::get_connection(), "
        SELECT COUNT(*)
        FROM $projectid
        WHERE image = '$image' AND state LIKE '%avail%'
    ");
    list($page_was_available) = mysqli_fetch_row($res); // either 0 or 1

    $sql = "DELETE FROM $projectid WHERE image = '$image'";
    mysqli_query(DPDatabase::get_connection(), $sql);

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
    _Page_UPDATE( $projectid, $image, "
        image='$new_image'
    ");

    _log_page_event( $projectid, $image, 'replaceImage', $user, NULL ); // $new_image
}

// -----------------------------------------------------------------------------

function Page_replaceText( $projectid, $image, $text_file_path, $user )
{
    $text_file_content_expr = file_content_expr( $text_file_path, $projectid );
    _Page_UPDATE( $projectid, $image, "
        master_text=$text_file_content_expr
    ");

    _log_page_event( $projectid, $image, 'replaceText', $user, NULL );
}

// -----------------------------------------------------------------------------

function Page_modifyText( $projectid, $image, $page_text, $text_column, $user )
{
    $page_text = _normalize_page_text($page_text, $projectid);
    _Page_UPDATE( $projectid, $image, sprintf("
        $text_column='%s'
    ", mysqli_real_escape_string(DPDatabase::get_connection(), $page_text)));

    $round = get_Round_for_text_column_name($text_column);

    _log_page_event( $projectid, $image, 'modifyText', $user, $round );
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function Page_checkout( $projectid, $image, $round, $user )
{
    _Page_require( $projectid, $image,
        array( $round->page_avail_state ),
        NULL, NULL,
        'checkout' );

    $timestamp = time();

    _Page_UPDATE( $projectid, $image, "
        state='{$round->page_out_state}',
        {$round->time_column_name}=$timestamp,
        {$round->user_column_name}='$user'
    " );

    _log_page_event( $projectid, $image, 'checkout', $user, $round );

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
    _Page_UPDATE( $projectid, $image, sprintf("
        state='{$round->page_temp_state}',
        {$round->time_column_name}=$timestamp,
        {$round->user_column_name}='$user',
        {$round->text_column_name}='%s'
    ", mysqli_real_escape_string(DPDatabase::get_connection(), $page_text)));

    _log_page_event( $projectid, $image, 'saveAsInProgress', $user, $round );
    upi_set_t_latest_page_event( $user, $projectid, 'UNIX_TIMESTAMP()' );

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
    _Page_UPDATE( $projectid, $image, sprintf("
        state='{$round->page_save_state}',
        {$round->time_column_name}=$timestamp,
        {$round->user_column_name}='$user',
        {$round->text_column_name}='%s'
    ", mysqli_real_escape_string(DPDatabase::get_connection(), $page_text)));

    _log_page_event( $projectid, $image, 'saveAsDone', $user, $round );
    upi_set_t_latest_page_event( $user, $projectid, 'UNIX_TIMESTAMP()' );

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

    _Page_UPDATE( $projectid, $image, "
        state='{$round->page_temp_state}',
        {$round->time_column_name}=$timestamp,
        {$round->user_column_name}='$user'
    " );

    _log_page_event( $projectid, $image, 'reopen', $user, $round );

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

    _Page_UPDATE( $projectid, $image, "
        state='{$round->page_avail_state}',
        {$round->time_column_name}=$timestamp,
        {$round->user_column_name}='$user'
    " );

    _log_page_event( $projectid, $image, 'returnToRound', $user, $round );

    _project_adjust_n_available_pages( $projectid, +1 );

    return $round->page_avail_state;
}

// -----------------------------------------------------------------------------

function Page_reclaim( $projectid, $image, $round, $user )
{
    _Page_require( $projectid, $image,
        array( $round->page_out_state, $round->page_temp_state ),
        NULL, NULL,
        'reclaim' );

    _Page_UPDATE( $projectid, $image, "
        state='{$round->page_avail_state}',
        {$round->time_column_name}=0
    " );

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

    _Page_UPDATE( $projectid, $image, "
        state='{$round->page_avail_state}',
        {$round->time_column_name}=0,
        {$round->user_column_name}='',
        {$round->text_column_name}=''
    ");

    _log_page_event( $projectid, $image, 'clearRound', $user, $round );

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

    _Page_UPDATE( $projectid, $image, "
        state='{$round->page_bad_state}',
        b_user='$user',
        b_code=$reason,
        {$round->text_column_name}={$round->prevtext_column_name}
    " );

    _log_page_event( $projectid, $image, 'markAsBad', $user, $round );
}

// -----------------------------------------------------------------------------

function Page_eraseBadMark( $projectid, $image, $round, $user )
{
    _Page_require( $projectid, $image,
        array( $round->page_bad_state ),
        NULL, NULL,
        'eraseBadMark' );

    _Page_UPDATE( $projectid, $image, "
        state='{$round->page_avail_state}',
        b_user='',
        b_code=0,
        {$round->user_column_name}=''
    " );

    _log_page_event( $projectid, $image, 'eraseBadMark', $user, $round );

    _project_adjust_n_available_pages( $projectid, +1 );
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
    $user_item = ( is_null($allowed_user_colname) ? '0' : $allowed_user_colname );
    $sql = "SELECT state, $user_item FROM $projectid WHERE image='$image'";
    $res = mysqli_query(DPDatabase::get_connection(), $sql);
    if ( !$res )
    {
        echo "The following mysql query:<br>\n";
        echo $sql, "<br>\n";
        echo "raised the following error:<br>\n";
        echo mysqli_error(DPDatabase::get_connection()), "<br>\n";
        exit;
    }

    list( $curr_page_state, $curr_page_owner ) = mysqli_fetch_row($res);
    if ( !in_array( $curr_page_state, $allowed_states ) )
    {
        $err = "\n"
            . "    "
            . sprintf(
                _("This operation (%s) requires that the page be in one of the following states:"),
                $op_name )
            . "\n"
            . "        "
            . implode( $allowed_states, ' ' )
            . "\n"
            . "    "
            . sprintf( _("But it is in %s"), $curr_page_state )
            ;
        abort($err);
    }

    if ( is_null($allowed_user_colname) )
    {
        // The operation can be done by anyone,
        // as far as this level is concerned.
        // (Higher levels may restrict who can do it.)
    }
    else
    {
        // The operation can only be done by the person who currently
        // "owns" the page.
        if ( $requesting_user != $curr_page_owner )
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
    $sql = "UPDATE $projectid SET $settings WHERE image='$image'";
    $res = mysqli_query(DPDatabase::get_connection(), $sql);
    if ( !$res )
    {
        echo "The following mysql query:<br>\n";
        echo $sql, "<br>\n";
        echo "raised the following error:<br>\n";
        echo mysqli_error(DPDatabase::get_connection()), "<br>\n";
        exit;
    }
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function _normalize_page_text( $page_text, $projectid )
// Normalize a page-text before we store it in the db,
// mainly so that inconsequential/inadvertent/invisible
// changes to a page don't show up as differences.
{
    // Filter characters to just the Latin-1 set
    $project = new Project($projectid);
    $result = filter_text_to_codepoints($page_text, $project->get_valid_codepoints());

    // 1. Eliminate blank lines at the end of the text.
    $result = rtrim($result);

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

    $result = preg_replace( $patterns, $repls, $result );

    return $result;
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function _log_page_event( $projectid, $image, $event_type, $username, $round )
{
    assert( strlen($event_type) <= 16 );

    $round_id_setting = ( is_null($round) ? 'NULL' : "'$round->id'" );

    mysqli_query(DPDatabase::get_connection(), "
        INSERT INTO page_events
        SET
            timestamp    = UNIX_TIMESTAMP(),
            projectid    = '$projectid',
            image        = '$image',
            event_type   = '$event_type',
            username     = '$username',
            round_id     = $round_id_setting
    ") or die(mysqli_error(DPDatabase::get_connection()));
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function _project_set_t_last_page_done( $projectid, $timestamp )
{
    mysqli_query(DPDatabase::get_connection(), "
        UPDATE projects
        SET t_last_page_done = $timestamp
        WHERE projectid = '$projectid'
    ") or die(mysqli_error(DPDatabase::get_connection()));
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

    mysqli_query(DPDatabase::get_connection(), "
        UPDATE projects
        SET n_pages = $expr
        WHERE projectid='$projectid'
    ") or die(mysqli_error(DPDatabase::get_connection()));
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

    mysqli_query(DPDatabase::get_connection(), "
        UPDATE projects
        SET n_available_pages = $expr
        WHERE projectid='$projectid'
    ") or die(mysqli_error(DPDatabase::get_connection()));
}

function project_recalculate_page_counts( $projectid )
// Calculate the project's n_pages and n_available_pages from scratch.
{
    $res = mysqli_query(DPDatabase::get_connection(), "
        SELECT COUNT(*), SUM(state LIKE '%.page_avail')
        FROM $projectid
    ");

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

    mysqli_query(DPDatabase::get_connection(), "
        UPDATE projects
        SET $settings
        WHERE projectid='$projectid'
    ") or die(mysqli_error(DPDatabase::get_connection()));
}

// vim: sw=4 ts=4 expandtab
?>
