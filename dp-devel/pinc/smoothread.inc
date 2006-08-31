<?PHP
include_once($relPath.'dp_main.inc');
include_once($relPath.'site_vars.php');

/***************************************************************************************
*
* Functions for smoothreading commitment functionality
*
* At this point the functionality does not require the usage of a class
* nor make it more convenient. Future extensions might change this assessment.
*
****************************************************************************************/

/***************************************************************************************
*
* Data access functions
*
****************************************************************************************/

/***************************************************************************************
*
* function sr_users_is_commited: checks the association table for existing commitment
*
* inputs: projectid, username
*
* output: true/false regarding existence of commitment
*
* Remarks:
*
* Currently only a check for existence of record and field being <> 0 is done. The latter
* would allow the extension for a timestamp and the resetting to 0 if revoked in order
* to still show users that have once committed but then revoked that commitment.
*
****************************************************************************************/

function sr_user_is_committed($projectid, $username)
{
    $res = mysql_query("
        SELECT *
        FROM smoothread
        WHERE projectid = '$projectid' 
        AND user = '$username'
        ") or die(mysql_error());

    if (mysql_num_rows($res) == 0)
    {
        return false;
    }

    $row = mysql_fetch_assoc($res);

    if ($row['committed'] <> 0)
    {
        return true;
    }

    return false;
}


/***************************************************************************************
*
* function sr_commit: inserts record into the association table for commitment
*
* inputs: projectid, username
*
* output: none
*
* Remarks:
*
* Currently the record is only inserted with the committed field being set to one.
* This allows the future extension for a timestamp and the resetting to 0 if revoked in oder
* to still show users that have once committed but then revoked that commitment.
*
****************************************************************************************/

function sr_commit($projectid, $username)
{
    $res = mysql_query("
        INSERT INTO smoothread
        SET projectid='$projectid',  user='$username', committed='1'
        ") or die(mysql_error());

}

/***************************************************************************************
*
* function sr_withdraw_commitment: deletes record from the association table for commitment
*
* inputs: projectid, username
*
* output: none
*
* Remarks:
*
* Currently the record is of commitment is deleted.
*
****************************************************************************************/

function sr_withdraw_commitment($projectid, $username)
{
    $res = mysql_query("
        DELETE FROM smoothread
        WHERE projectid = '$projectid' 
        AND user = '$username'
        ") or die(mysql_error());

}

/***************************************************************************************
*
* function sr_get_committed_users: provide list of users with sr-commitment to project
*
* inputs: projectid
*
* output: Array of usernames
*
* Remarks:
*
****************************************************************************************/

function sr_get_committed_users($projectid)
{
    $list = array();

    $res = mysql_query("
        SELECT *
        FROM smoothread
        WHERE projectid = '$projectid' 
        AND committed <> '0'
        ORDER BY user
        ") or die(mysql_error());

    if (mysql_num_rows($res) == 0)
    {
        return $list;
    }

    while ($row = mysql_fetch_assoc($res))
    {
        //array_push($list, $row['user']);
        $list[] = $row['user'];
    }

    return $list;
}

/***************************************************************************************
*
* function sr_number_users_committed: provide nnumber of users with sr-commitment to project
*
* inputs: projectid
*
* output: number of users
*
* Remarks: 
*
****************************************************************************************/

function sr_number_users_committed($projectid)
{
    $number = 0;

    $res = mysql_query("
        SELECT count(user)
        FROM smoothread
        WHERE projectid = '$projectid' 
        AND committed <> '0'
        ORDER BY user
        ") or die(mysql_error());

    if (mysql_num_rows($res) == 0)
    {
        return 0;
    }

    $row = mysql_fetch_row($res);
    $number = $row[0];
    return $number;
}

/***************************************************************************************
*
* User interface functions
*
****************************************************************************************/

/***************************************************************************************
*
* function sr_echo_commitment_form: create button and call page for database access
*
* inputs: projectid
*
* output: none
*
* Remarks:
*
* This calls a transient page executing the database function for inserting commitment 
* and provides the current URI for return to current page.
*
****************************************************************************************/


function sr_echo_commitment_form($projectid)
{
    global $code_url;

    $next_url = $_SERVER['REQUEST_URI'];
    $button_text = _("Commit to SR");

    echo "<form method='post' action='$code_url/tools/change_sr_commitment.php'>";
    echo "<input type='hidden' name='projectid' value='$projectid'>\n";
    echo "<input type='hidden' name='action' value='commit'>\n";
    echo "<input type='hidden' name='next_url' value='$next_url'>\n";
    echo "<input type='submit' value='$button_text'>";
    echo "</form>\n";
}

/***************************************************************************************
*
* function sr_echo_withdrawal_form: create button and call page for database access
*
* inputs: projectid
*
* output: none
*
* Remarks:
*
* This calls a transient page executing the database function for revoking a commitment 
* and provides the current URI for return to current page.
*
****************************************************************************************/

function sr_echo_withdrawal_form($projectid)
{
    global $code_url;

    $next_url = $_SERVER['REQUEST_URI'];
    $button_text = _("Withdraw SR commitment");

    echo "<form method='post' action='$code_url/tools/change_sr_commitment.php'>";
    echo "<input type='hidden' name='projectid' value='$projectid'>\n";
    echo "<input type='hidden' name='action' value='withdraw'>\n";
    echo "<input type='hidden' name='next_url' value='$next_url'>\n";
    echo "<input type='submit' value='$button_text'>";
    echo "</form>\n";

}

/***************************************************************************************
*
* function get_bb_user_id: get the userid for phpBB for the given username
*
* inputs: username
*
* output: userid
*
* Remarks:
*
* This is actually a more common function that should move at some point into a different 
* module.
*
****************************************************************************************/

function get_bb_user_id($username)
{
   $id = NULL;

   $res = mysql_query("
       SELECT user_id
       FROM phpbb_users
       WHERE username = '$username' 
       ") or die(mysql_error());

    if (mysql_num_rows($res) == 0)
    {
        return NULL;
    }

    $row = mysql_fetch_row($res);
    $id = $row[0];
    return $id;

}

?>
