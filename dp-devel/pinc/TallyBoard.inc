<?PHP

// A "tally" is simply a running count of some specified quantity for
// some specified agent (e.g., a user or team), called the tally's 'holder'.
// (E.g., the holder could be user 23, and the quantity could be
// pages saved-as-done in P1 or P2.)

// A holder is identified by a 'holder_type' (e.g., 'U' for user)
// and an integer holder_id (e.g. 23).

// A tally quantity is identified by a 'tally_name'
// (e.g. 'P' for pages saved-as-done in P1 or P2).

// This file will attempt to encapsulate all access to the tables that
// hold tally data.

// -----------------------------------------------------------------------------

include_once($relPath.'connect.inc');
new dbConnect();

// A TallyBoard maintains all tally data (past and present)
// for a particular combination of tally_name and holder_type.

class TallyBoard
{
    function TallyBoard( $tally_name, $holder_type )
    {
        $this->tally_name  = $tally_name;
        $this->holder_type = $holder_type;
    }

    // -------------------------------------------------------------------------

    function initialize_tally( $holder_id )
    // Establish a tally (initialized to zero) for the given holder.
    // Typically, this is called when the holder is created.
    // (Eventually, this function won't be necessary.)
    {
        mysql_query("
            INSERT INTO current_tallies
            SET
                tally_name  = '$this->tally_name',
                holder_type = '$this->holder_type',
                holder_id   = $holder_id,
                tally_value = 0
        ") or die(mysql_error());

        // -----------------

        // We'll be inserting a record into past_tallies.
        // The timestamp we use in that record should be the same as the most recent
        // timestamp used by the periodic snapshot script. (If we use a more recent
        // timestamp, we'll mislead the next run of that script.)

        $result = mysql_query("
            SELECT MAX(timestamp)
            FROM past_tallies
            WHERE
                tally_name='$this->tally_name'
                AND holder_type='$this->holder_type'
        ") or die(mysql_error());
        $latest_time_in_table = mysql_result($result,0,0);

        if (!is_null($latest_time_in_table))
        {
            $time_for_record = $latest_time_in_table;
        }
        else
        {
            // No such entry in past_tallies.
            // (This is presumably the very first user.)
            // The choice of timestamp is arbitrary?
            // Pick the most recent midnight.
            $now = getdate();
            $time_for_record = mktime(0,0,0,$now['mon'],$now['mday'],$now['year']);
        }

        mysql_query("
            INSERT INTO past_tallies
            SET
                timestamp   = $time_for_record,
                holder_type = '$this->holder_type',
                holder_id   = $holder_id,
                tally_name  = '$this->tally_name',
                tally_delta = 0,
                tally_value = 0,
                tally_rank  = 0
        ") or die(mysql_error());
    }

    // -------------------------------------------------------------------------

    function add_to_tally( $holder_id, $amount )
    {
        mysql_query("
            UPDATE current_tallies
            SET tally_value = tally_value + $amount
            WHERE
                tally_name      = '$this->tally_name'
                AND holder_type = '$this->holder_type'
                AND holder_id   = '$holder_id'
        ") or die(mysql_error());
    }

    // -------------------------------------------------------------------------

    function get_sql_joinery_for_current_tallies( $holder_id_expr )
    // Return an array containing two strings of SQL code.
    // You can use these in a SELECT statement when you want to
    // extract the current tallies for a (SQL-specified) set of holders.
    //
    // $holder_id_expr is a string containing an SQL expression
    // (typically just a column name) that yields the holder_ids
    // of the holders for whom you want to extract tally data.
    //
    // The resulting strings are:
    // -- a join-expression (for use in the FROM clause);
    // -- a select-expression (for use in the SELECT and/or WHERE clauses)
    //    that yields the current tally for each selected holder.
    //    (It could be just a column-name, but don't rely on that.
    //    In particular, don't assume that this will work:
    //
    //        $res = mysql_query("SELECT ... $expr_for_tally FROM ...");
    //        $row = mysql_fetch_assoc($res);
    //        ... $row[$expr_for_tally] ...
    //
    //    Instead, if you want to use mysql_fetch_assoc, this should work:
    //
    //        $res = mysql_query("SELECT ... $expr_for_tally AS foo FROM ...");
    //        $row = mysql_fetch_assoc($res);
    //        ... $row['foo'] ...
    {
        return array(
            "
                LEFT OUTER JOIN current_tallies
                ON (
                    tally_name      = '$this->tally_name'
                    AND holder_type = '$this->holder_type'
                    AND holder_id   = $holder_id_expr
                )
            ",
            "current_tallies.tally_value"
        );
    }
}

// vim: sw=4 ts=4 expandtab
?>
