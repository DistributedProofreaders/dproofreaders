<?PHP
include_once($relPath.'v_site.inc');
include_once($relPath.'RoundDescriptor.inc');

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
        $prd = get_PRD_for_round($rn);
        $columns_for_rounds .= "
            {$prd->time_column_name}   int(20)     NOT NULL default '0',
            {$prd->user_column_name}   varchar(25) NOT NULL default '',
            {$prd->text_column_name}   longtext    NOT NULL,
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

    $prd = get_PRD_for_round($round_number);
    if ( $round_number == 1 )
    {
        // Yes, these queries are pointless.
        // They have been for quite a while.
        // But I leave them here as a reminder, of sorts.

        $result = mysql_query("
            UPDATE $projectid
            SET state = '{$prd->page_avail_state}'
            WHERE 0
        ");

        if ($writeBIGtable)
        {
            $result = mysql_query("
                UPDATE project_pages
                SET state = '{$prd->page_avail_state}'
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
                state = '{$prd->page_avail_state}',
                {$prd->time_column_name} = '$timestamp'
        ");
    
        if ($writeBIGtable)
        {
            $result = mysql_query("
                UPDATE project_pages
                SET
                    state = '{$prd->page_avail_state}',
                    {$prd->time_column_name} = '$timestamp'
                WHERE projectid = '$projectid'
            ");
        }
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

    $have_file_privilege = TRUE;
    if ($have_file_privilege)
    {
        $txt_file_path_e = addslashes( $txt_file_path );
        $txt_expr = "LOAD_FILE('$txt_file_path_e')";
    }
    else
    {
        $txt = file_get_contents( $txt_file_path );
        if ( $txt === FALSE )
        {
            return "Unable to read file '$txt_file_path'";
        }
        $txt_expr = "'" . addslashes($txt) . "'";
    }

    $prd = get_PRD_for_round(1);
    $page_state = $prd->page_avail_state;

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

    return $errs;
}

// -----------------------------------------------------------------------------

function Page_delete( $projectid, $fileid )
{
    global $writeBIGtable;

    $sql = "DELETE FROM $projectid WHERE fileid = '$fileid'";
    mysql_query($sql);

    if ($writeBIGtable)
    {
        $sql = "DELETE FROM project_pages WHERE projectid = '$projectid' AND fileid = '$fileid'";
        mysql_query($sql);
    }
}

// -----------------------------------------------------------------------------

function Page_modifyStartingText( $projectid, $fileid, $page_text )
{
    _Page_UPDATE( $projectid, $fileid, "
        master_text='$page_text'
    " );
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function Page_checkout( $projectid, $fileid, $image, $round_number, $user )
{
    $timestamp = time();

    $prd = get_PRD_for_round( $round_number );

    _Page_UPDATE( $projectid, $fileid, "
        state='{$prd->page_out_state}',
        {$prd->time_column_name}='$timestamp',
        {$prd->user_column_name}='$user'
    " );

    return $prd->page_out_state;
}

// -----------------------------------------------------------------------------

function Page_saveAsInProgress( $projectid, $fileid, $image, $round_number, $user, $page_text )
{
    $timestamp = time();

    $prd = get_PRD_for_round( $round_number );

    _Page_UPDATE( $projectid, $fileid, "
        state='{$prd->page_temp_state}',
        {$prd->time_column_name}='$timestamp',
        {$prd->user_column_name}='$user',
        {$prd->text_column_name}='$page_text'
    " );

    return $prd->page_temp_state;
}

// -----------------------------------------------------------------------------

function Page_saveAsDone( $projectid, $fileid, $image, $round_number, $user, $page_text )
{
    $timestamp = time();

    $prd = get_PRD_for_round( $round_number );

    _Page_UPDATE( $projectid, $fileid, "
        state='{$prd->page_save_state}',
        {$prd->time_column_name}='$timestamp',
        {$prd->user_column_name}='$user',
        {$prd->text_column_name}='$page_text'
    " );

    return $prd->page_save_state;
}

// -----------------------------------------------------------------------------

function Page_reopen( $projectid, $fileid, $image, $round_number, $user )
{
    $timestamp = time();

    $prd = get_PRD_for_round( $round_number );

    _Page_UPDATE( $projectid, $fileid, "
        state='{$prd->page_temp_state}',
        {$prd->time_column_name}='$timestamp',
        {$prd->user_column_name}='$user'
    " );

    return $prd->page_temp_state;
}

// -----------------------------------------------------------------------------

function Page_returnToRound( $projectid, $fileid, $image, $round_number, $user )
{
    $timestamp = time();

    $prd = get_PRD_for_round( $round_number );

    _Page_UPDATE( $projectid, $fileid, "
        state='{$prd->page_avail_state}',
        {$prd->time_column_name}='$timestamp',
        {$prd->user_column_name}='$user'
    " );

    return $prd->page_avail_state;
}

// -----------------------------------------------------------------------------

function Page_reclaim( $projectid, $fileid, $round_number )
{
    $prd = get_PRD_for_round( $round_number );

    _Page_UPDATE( $projectid, $fileid, "
        state='{$prd->page_avail_state}',
        {$prd->time_column_name}=''
    " );
}

// -----------------------------------------------------------------------------

function Page_clearRound( $projectid, $fileid, $round_number )
{
    $prd = get_PRD_for_round( $round_number );

    _Page_UPDATE( $projectid, $fileid, "
        state='{$prd->page_avail_state}',
        {$prd->time_column_name}='',
        {$prd->user_column_name}='',
        {$prd->text_column_name}=''
    ");
}

// -----------------------------------------------------------------------------

function Page_markAsBad( $projectid, $fileid, $image, $round_number, $user, $reason )
{
    $prd = get_PRD_for_round( $round_number );

    _Page_UPDATE( $projectid, $fileid, "
        state='{$prd->page_bad_state}',
        b_user='$user',
        b_code=$reason,
        {$prd->text_column_name}={$prd->prevtext_column_name}
    " );
}

// -----------------------------------------------------------------------------

function Page_eraseBadMark( $projectid, $fileid, $round_number )
{
    $prd = get_PRD_for_round( $round_number );

    _Page_UPDATE( $projectid, $fileid, "
        state='{$prd->page_avail_state}',
        b_user='',
        b_code='',
        {$prd->user_column_name}=''
    " );
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function _Page_UPDATE( $projectid, $fileid, $settings )
{
    global $writeBIGtable;

    $sql = "UPDATE $projectid SET $settings WHERE fileid='$fileid'";
    $res = mysql_query($sql) or die(mysql_error());

    if ($writeBIGtable)
    {
        $sql = "UPDATE project_pages SET $settings WHERE projectid='$projectid' AND fileid='$fileid'";
        $res = mysql_query($sql) or die(mysql_error());
    }
}

// vim: sw=4 ts=4 expandtab
?>
