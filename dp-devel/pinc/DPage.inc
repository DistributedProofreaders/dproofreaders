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

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function Page_checkout( $projectid, $fileid, $image, $round_number, $user )
{
    global $writeBIGtable;

    $timestamp = time();

    $prd = get_PRD_for_round( $round_number );

    $dbQuery="UPDATE $projectid SET state='";
    $dbQuery.="{$prd->out_state}', {$prd->time_column_name}='$timestamp', {$prd->user_column_name}='$user'";
    $dbQuery.=" WHERE fileid='$fileid' AND image='$image'";
    $update = mysql_query($dbQuery);

    if ($writeBIGtable)
    {
        $dbQuery="UPDATE project_pages SET state='";
        $dbQuery.="{$prd->out_state}', {$prd->time_column_name}='$timestamp', {$prd->user_column_name}='$user'";
        $dbQuery.=" WHERE projectid = '$projectid' and fileid='$fileid'";
        $update = mysql_query($dbQuery);
    }

    return $prd->out_state;
}

// -----------------------------------------------------------------------------

function Page_saveAsInProgress( $projectid, $fileid, $image, $round_number, $user, $page_text )
{
    global $writeBIGtable;

    $timestamp = time();

    $prd = get_PRD_for_round( $round_number );

    $dbQuery="UPDATE $projectid SET {$prd->text_column_name}='$page_text', {$prd->time_column_name}='$timestamp', state='{$prd->temp_state}', {$prd->user_column_name}='$user'";
    $dbQuery.=" WHERE image='$image' AND fileid='$fileid'";
    $result = mysql_query($dbQuery);

    if ($writeBIGtable)
    {
        $dbQuery="UPDATE project_pages SET {$prd->text_column_name}='$page_text', {$prd->time_column_name}='$timestamp', state='{$prd->temp_state}', {$prd->user_column_name}='$user'";
        $dbQuery.=" WHERE projectid = '$projectid' AND fileid='$fileid'";
        $result = mysql_query($dbQuery);
    }

    return $prd->temp_state;
}

// -----------------------------------------------------------------------------

function Page_saveAsDone( $projectid, $fileid, $image, $round_number, $user, $page_text )
{
    global $writeBIGtable;

    $timestamp = time();

    $prd = get_PRD_for_round( $round_number );

    $dbQuery="UPDATE $projectid SET {$prd->text_column_name}='$page_text', {$prd->time_column_name}='$timestamp', state='{$prd->save_state}', {$prd->user_column_name}='$user'";
    $dbQuery.=" WHERE image='$image' AND fileid='$fileid'";
    $result = mysql_query($dbQuery) or die(mysql_error());

    if ($writeBIGtable)
    {
        $dbQuery="UPDATE project_pages SET {$prd->text_column_name}='$page_text', {$prd->time_column_name}='$timestamp', state='{$prd->save_state}', {$prd->user_column_name}='$user'";
        $dbQuery.=" WHERE projectid = '$projectid' AND fileid='$fileid'";
        $result = mysql_query($dbQuery) or die(mysql_error());
    }

    return $prd->save_state;
}

// -----------------------------------------------------------------------------

function Page_reopen( $projectid, $fileid, $image, $round_number, $user )
{
    global $writeBIGtable;

    $timestamp = time();

    $prd = get_PRD_for_round( $round_number );

    $dbQuery="UPDATE $projectid SET {$prd->time_column_name}='$timestamp', state='{$prd->temp_state}', {$prd->user_column_name}='$user'";
    $dbQuery.=" WHERE image='$image' AND fileid='$fileid'";
    $result = mysql_query($dbQuery);

    if ($writeBIGtable)
    {
        $dbQuery="UPDATE project_pages SET {$prd->time_column_name}='$timestamp', state='{$prd->temp_state}', {$prd->user_column_name}='$user'";
        $dbQuery.=" WHERE projectid = '$projectid' AND fileid='$fileid'";
        $result = mysql_query($dbQuery);
    }

    return $prd->temp_state;
}

// -----------------------------------------------------------------------------

function Page_returnToRound( $projectid, $fileid, $image, $round_number, $user )
{
    global $writeBIGtable;

    $timestamp = time();

    $prd = get_PRD_for_round( $round_number );

    $dbQuery="UPDATE $projectid SET {$prd->time_column_name}='$timestamp', state='{$prd->avail_state}', {$prd->user_column_name}='$user'";
    $dbQuery.=" WHERE image='$image' AND fileid='$fileid'";
    $result = mysql_query($dbQuery);

    if ($writeBIGtable)
    {
        $dbQuery="UPDATE project_pages SET {$prd->time_column_name}='$timestamp', state='{$prd->avail_state}', {$prd->user_column_name}='$user'";
        $dbQuery.=" WHERE projectid = '$projectid' AND fileid='$fileid'";
        $result = mysql_query($dbQuery);
    }

    return $prd->avail_state;
}

// vim: sw=4 ts=4 expandtab
?>
