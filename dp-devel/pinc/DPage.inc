<?PHP
include_once($relPath.'v_site.inc');

// This file brings together code that accesses the projectID* tables and the
// project_pages table.

// The usual semantics for the return value is this:
// If something goes wrong, return a string containing an error message.
// Otherwise (i.e., success), return an empty string.

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function project_allow_pages( $projectid )
{
    $res = mysql_query("
        CREATE TABLE $projectid
        (
            fileid        varchar(20) NOT NULL default '', UNIQUE (fileid),
            image         varchar(8)  NOT NULL default '', UNIQUE (image),
            master_text   longtext    NOT NULL,
            round1_text   longtext    NOT NULL,
            round2_text   longtext    NOT NULL,
            round1_user   varchar(25) NOT NULL default '',
            round2_user   varchar(25) NOT NULL default '',
            round1_time   int(20)     NOT NULL default '0',
            round2_time   int(20)     NOT NULL default '0',
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

function Pages_delete( $projectid )
{
    global $writeBIGtable;

    $sql = "DELETE FROM $projectid";
    mysql_query($sql);

    if ($writeBIGtable)
    {
        $sql = "DELETE FROM project_pages WHERE projectid = '$projectid'";
        mysql_query($sql);
    }
}

// -----------------------------------------------------------------------------

function Pages_prepForRound( $projectid, $round_number )
{
    global $writeBIGtable;

    if ( $round_number == 1 )
    {
        $result = mysql_query("
            UPDATE $projectid
            SET state = '".AVAIL_FIRST."'
            WHERE state = '".UNAVAIL_FIRST."'
        ");

        if ($writeBIGtable)
        {
            $result = mysql_query("
                UPDATE project_pages
                SET state = '".AVAIL_FIRST."'
                WHERE projectid = '$projectid' AND state = '".UNAVAIL_FIRST."'
            ");
        }
    }
    else if ( $round_number == 2 )
    {
        $timestamp = time();

        $result = mysql_query("UPDATE $projectid SET state = '".AVAIL_SECOND."', round2_time = '$timestamp'");
    
        if ($writeBIGtable)
        {
            $result = mysql_query("UPDATE project_pages SET state = '".AVAIL_SECOND."', round2_time = '$timestamp' WHERE projectid = '$projectid' ");
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
    $txt_file_path_e = addslashes( $txt_file_path );

    $errs = '';

    $sql = "
        INSERT INTO $projectid
        SET
            fileid      = '$fileid',
            image       = '$image_file_name',
            master_text = LOAD_FILE('$txt_file_path_e'),
            round1_time = $now,
            state       = '".AVAIL_FIRST."'
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
                    master_text = LOAD_FILE('$txt_file_path_e'),
                    round1_time = $now,
                    state       = '".AVAIL_FIRST."'
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
        state='{$prd->out_state}',
        {$prd->time_column_name}='$timestamp',
        {$prd->user_column_name}='$user'
    " );

    return $prd->out_state;
}

// -----------------------------------------------------------------------------

function Page_saveAsInProgress( $projectid, $fileid, $image, $round_number, $user, $page_text )
{
    $timestamp = time();

    $prd = get_PRD_for_round( $round_number );

    _Page_UPDATE( $projectid, $fileid, "
        state='{$prd->temp_state}',
        {$prd->time_column_name}='$timestamp',
        {$prd->user_column_name}='$user',
        {$prd->text_column_name}='$page_text'
    " );

    return $prd->temp_state;
}

// -----------------------------------------------------------------------------

function Page_saveAsDone( $projectid, $fileid, $image, $round_number, $user, $page_text )
{
    $timestamp = time();

    $prd = get_PRD_for_round( $round_number );

    _Page_UPDATE( $projectid, $fileid, "
        state='{$prd->save_state}',
        {$prd->time_column_name}='$timestamp',
        {$prd->user_column_name}='$user',
        {$prd->text_column_name}='$page_text'
    " );

    return $prd->save_state;
}

// -----------------------------------------------------------------------------

function Page_reopen( $projectid, $fileid, $image, $round_number, $user )
{
    $timestamp = time();

    $prd = get_PRD_for_round( $round_number );

    _Page_UPDATE( $projectid, $fileid, "
        state='{$prd->temp_state}',
        {$prd->time_column_name}='$timestamp',
        {$prd->user_column_name}='$user'
    " );

    return $prd->temp_state;
}

// -----------------------------------------------------------------------------

function Page_returnToRound( $projectid, $fileid, $image, $round_number, $user )
{
    $timestamp = time();

    $prd = get_PRD_for_round( $round_number );

    _Page_UPDATE( $projectid, $fileid, "
        state='{$prd->avail_state}',
        {$prd->time_column_name}='$timestamp',
        {$prd->user_column_name}='$user'
    " );

    return $prd->avail_state;
}

// -----------------------------------------------------------------------------

function Page_reclaim( $projectid, $fileid, $round_number )
{
    $prd = get_PRD_for_round( $round_number );

    _Page_UPDATE( $projectid, $fileid, "
        state='{$prd->avail_state}',
        {$prd->time_column_name}=''
    " );
}

// -----------------------------------------------------------------------------

function Page_clearRound( $projectid, $fileid, $round_number )
{
    $prd = get_PRD_for_round( $round_number );

    _Page_UPDATE( $projectid, $fileid, "
        state='{$prd->avail_state}',
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
        state='{$prd->bad_state}',
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
        state='{$prd->avail_state}',
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
