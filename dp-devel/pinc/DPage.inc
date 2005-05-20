<?PHP
include_once($relPath.'v_site.inc');
include_once($relPath.'stages.inc');

// This file brings together code that accesses the projectID* tables and the
// project_pages table.

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
        ";
    }

    $res = mysql_query("
        CREATE TABLE $projectid
        (
            fileid        varchar(20) NOT NULL default '', UNIQUE (fileid),
            image         varchar(12) NOT NULL default '', UNIQUE (image),
            master_text   longtext    NOT NULL,
            $columns_for_rounds
            state         VARCHAR(50) NOT NULL default '', INDEX(state),
            b_user        VARCHAR(25) NOT NULL default '',
            b_code        INT(1)      NOT NULL default '',
            orig_page_num VARCHAR(6)  NOT NULL default '',
            metadata      SET(
                'blank', 'missing', 'badscan', 'outofseq',
                'acknowledge', 'dedication', 'ednotes', 'foreword', 'intro', 'loi', 'preface', 'prologue', 'toc', 'titlepage',
                'abbreviation', 'division', 'epigraph', 'footnote', 'illustration', 'letter', 'list', 'math', 'poetry', 'sidenote', 'verse', 'table',
                'appendix', 'afterword', 'biblio', 'colophon', 'endnote', 'epilogue', 'index'
                ) NOT NULL default ''
        )
    ");
    return ( $res ? '' : mysql_error() );
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function project_drop_pages( $projectid )
{
    global $writeBIGtable;

    $result = mysql_query("DROP TABLE $projectid");

    if ($writeBIGtable) {
        $result = mysql_query("DELETE FROM project_pages WHERE projectid = '$projectid'");
    }

    _project_adjust_n_pages( $projectid, 'NONE' );
}

// -----------------------------------------------------------------------------

function Pages_prepForRound( $projectid, $round_number )
{
    global $writeBIGtable;

    // When prepping for round 2, we just mark all pages as P2.page_avail.
    // So when prepping for round 1, why don't we just mark all pages as P1.page_avail?
    //
    // I suspect the reason is that, for round 1, this function is called
    // when the project goes from PROJ_P1_WAITING_FOR_RELEASE to
    // PROJ_P1_AVAILABLE, and it can go through this transition
    // more than once (via PROJ_P1_UNAVAILABLE). After the first
    // time, it can have some pages that have already been worked on, and
    // we don't want the transition to put such pages back to P1.page_avail.
    //
    // The problem doesn't arise for second round because there, the transition
    // is PROJ_P1_COMPLETE to PROJ_P2_AVAILABLE, and the
    // project can't go through that transition twice.

    $round = get_Round_for_round_number($round_number);
    if ( $round_number == 1 )
    {
        // Yes, these queries are pointless.
        // They have been for quite a while.
        // But I leave them here as a reminder, of sorts.

        $result = mysql_query("
            UPDATE $projectid
            SET state = '{$round->page_avail_state}'
            WHERE 0
        ");

        if ($writeBIGtable)
        {
            $result = mysql_query("
                UPDATE project_pages
                SET state = '{$round->page_avail_state}'
                WHERE projectid = '$projectid' AND 0
            ");
        }
    }
    else
    {
        $timestamp = time();

        $result = mysql_query("
            UPDATE $projectid
            SET
                state = '{$round->page_avail_state}',
                {$round->time_column_name} = '$timestamp'
        ");
    
        if ($writeBIGtable)
        {
            $result = mysql_query("
                UPDATE project_pages
                SET
                    state = '{$round->page_avail_state}',
                    {$round->time_column_name} = '$timestamp'
                WHERE projectid = '$projectid'
            ");
        }

        _project_adjust_n_available_pages( $projectid, 'ALL' );
    }
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function project_add_page(
    $projectid,
    $fileid,
    $image_file_name,
    $txt_file_path, // must be absolute for LOAD_FILE
    $now )
{
    global $writeBIGtable;

    $image_file_name_e = addslashes( $image_file_name );

    $txt_expr = file_content_expr( $txt_file_path );

    $round = get_Round_for_round_number(1);
    $page_state = $round->page_avail_state;

    $errs = '';

    $sql = "
        INSERT INTO $projectid
        SET
            fileid      = '$fileid',
            image       = '$image_file_name',
            master_text = $txt_expr,
            round1_time = $now,
            state       = '$page_state'
    ";
    // echo $sql, "\n";
    $res = mysql_query($sql);
    if (!$res)
    {
        $errs .= mysql_error();
    }

    if ($writeBIGtable)
    {
        $sql = "
            INSERT INTO project_pages
            SET
                    projectid   = '$projectid',
                    fileid      = '$fileid',
                    image       = '$image_file_name',
                    master_text = $txt_expr,
                    round1_time = $now,
                    state       = '$page_state'
            ";
        $res = mysql_query($sql);
        if (!$res)
        {
            if ($errs) $errs .= "\n";
            $errs .= mysql_error();
        }
    }

    _project_adjust_n_pages( $projectid, +1 );
    _project_adjust_n_available_pages( $projectid, +1 );

    return $errs;
}

function file_content_expr( $file_path )
// Return an SQL expression that will yield:
// -- the contents of the given file as a string
// -- or NULL if the file can't be read.
// $file_path must be absolute.
{
    $have_file_privilege = TRUE;
    if ($have_file_privilege)
    {
        $file_path_e = addslashes( $file_path );
        return "LOAD_FILE('$file_path_e')";
    }
    else
    {
        $txt = file_get_contents( $file_path );
        if ( $txt === FALSE )
        {
            return 'NULL';
        }
        return "'" . addslashes($txt) . "'";
    }
}

// -----------------------------------------------------------------------------

function Page_delete( $projectid, $image )
{
    global $writeBIGtable;

    $sql = "DELETE FROM $projectid WHERE image = '$image'";
    mysql_query($sql);

    if ($writeBIGtable)
    {
        $sql = "DELETE FROM project_pages WHERE projectid = '$projectid' AND image = '$image'";
        mysql_query($sql);
    }

    _project_adjust_n_pages( $projectid, -1 );
}

// -----------------------------------------------------------------------------

function Page_replaceImage( $projectid, $image, $new_image )
// e.g., replace 001.png with 001.jpg
{
    _Page_UPDATE( $projectid, $image, "
        image='$new_image'
    ");
}

// -----------------------------------------------------------------------------

function Page_replaceText( $projectid, $image, $text_file_path )
{
    $text_file_content_expr = file_content_expr( $text_file_path );
    _Page_UPDATE( $projectid, $image, "
        master_text=$text_file_content_expr
    ");
}

// -----------------------------------------------------------------------------

function Page_modifyText( $projectid, $image, $page_text, $text_column )
{
    _Page_UPDATE( $projectid, $image, 
        $text_column."='$page_text'");
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function Page_checkout( $projectid, $image, $round, $user )
{
    $timestamp = time();

    _Page_UPDATE( $projectid, $image, "
        state='{$round->page_out_state}',
        {$round->time_column_name}='$timestamp',
        {$round->user_column_name}='$user'
    " );

    _project_adjust_n_available_pages( $projectid, -1 );

    return $round->page_out_state;
}

// -----------------------------------------------------------------------------

function Page_saveAsInProgress( $projectid, $image, $round, $user, $page_text )
{
    $timestamp = time();

    _Page_UPDATE( $projectid, $image, "
        state='{$round->page_temp_state}',
        {$round->time_column_name}='$timestamp',
        {$round->user_column_name}='$user',
        {$round->text_column_name}='$page_text'
    " );

    return $round->page_temp_state;
}

// -----------------------------------------------------------------------------

function Page_saveAsDone( $projectid, $image, $round, $user, $page_text )
{
    $timestamp = time();

    _Page_UPDATE( $projectid, $image, "
        state='{$round->page_save_state}',
        {$round->time_column_name}='$timestamp',
        {$round->user_column_name}='$user',
        {$round->text_column_name}='$page_text'
    " );

    return $round->page_save_state;
}

// -----------------------------------------------------------------------------

function Page_reopen( $projectid, $image, $round, $user )
{
    $timestamp = time();

    _Page_UPDATE( $projectid, $image, "
        state='{$round->page_temp_state}',
        {$round->time_column_name}='$timestamp',
        {$round->user_column_name}='$user'
    " );

    return $round->page_temp_state;
}

// -----------------------------------------------------------------------------

function Page_returnToRound( $projectid, $image, $round, $user )
{
    $timestamp = time();

    _Page_UPDATE( $projectid, $image, "
        state='{$round->page_avail_state}',
        {$round->time_column_name}='$timestamp',
        {$round->user_column_name}='$user'
    " );

    _project_adjust_n_available_pages( $projectid, +1 );

    return $round->page_avail_state;
}

// -----------------------------------------------------------------------------

function Page_reclaim( $projectid, $image, $round )
{
    _Page_UPDATE( $projectid, $image, "
        state='{$round->page_avail_state}',
        {$round->time_column_name}=''
    " );

    _project_adjust_n_available_pages( $projectid, +1 );
}

// -----------------------------------------------------------------------------

function Page_clearRound( $projectid, $image, $round )
{
    _Page_UPDATE( $projectid, $image, "
        state='{$round->page_avail_state}',
        {$round->time_column_name}='',
        {$round->user_column_name}='',
        {$round->text_column_name}=''
    ");

    _project_adjust_n_available_pages( $projectid, +1 );
}

// -----------------------------------------------------------------------------

function Page_markAsBad( $projectid, $image, $round, $user, $reason )
{
    _Page_UPDATE( $projectid, $image, "
        state='{$round->page_bad_state}',
        b_user='$user',
        b_code=$reason,
        {$round->text_column_name}={$round->prevtext_column_name}
    " );
}

// -----------------------------------------------------------------------------

function Page_eraseBadMark( $projectid, $image, $round )
{
    _Page_UPDATE( $projectid, $image, "
        state='{$round->page_avail_state}',
        b_user='',
        b_code='',
        {$round->user_column_name}=''
    " );

    _project_adjust_n_available_pages( $projectid, +1 );
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function _Page_UPDATE( $projectid, $image, $settings )
{
    global $writeBIGtable;

    $sql = "UPDATE $projectid SET $settings WHERE image='$image'";
    $res = mysql_query($sql) or die(mysql_error());

    if ($writeBIGtable)
    {
        $sql = "UPDATE project_pages SET $settings WHERE projectid='$projectid' AND image='$image'";
        $res = mysql_query($sql) or die(mysql_error());
    }
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function _project_adjust_n_pages( $projectid, $adjustment )
{
    if ( $adjustment == 'NONE' )
    {
        $expr = '0';
    }
    else
    {
        $expr = "n_pages + $adjustment";
    }

    mysql_query("
        UPDATE projects
        SET n_pages = $expr
        WHERE projectid='$projectid'
    ") or die(mysql_error());
}

function _project_adjust_n_available_pages( $projectid, $adjustment )
{
    if ( $adjustment == 'ALL' )
    {
        $expr = 'n_pages';
    }
    else
    {
        $expr = "n_available_pages + $adjustment";
    }

    mysql_query("
        UPDATE projects
        SET n_available_pages = $expr
        WHERE projectid='$projectid'
    ") or die(mysql_error());
}

// vim: sw=4 ts=4 expandtab
?>
