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

include_once($relPath.'misc.inc');
include_once($relPath.'connect.inc');
new dbConnect();

// A TallyBoard maintains all tally data (past and present)
// for a particular combination of tally_name and holder_type.

// If a holder isn't explicitly recorded in a TallyBoard,
// then it is implicitly recorded with a tally of zero.
// So  the TallyBoard is prepared to answer questions about
// holders it has never heard of before.

// -----------------------------------------------------------------------------

function get_all_current_tallyboards()
// Return an array of TallyBoard objects, representing all
// boards on which there is some tally explicitly recorded.
{
    $tallyboards = array();
    $res = mysql_query("
        SELECT DISTINCT tally_name, holder_type
        FROM current_tallies
        ORDER BY tally_name, holder_type
    ") or die(mysql_error());
    while ( list($tally_name, $holder_type) = mysql_fetch_row($res) )
    {
        $tallyboards[] =& new TallyBoard( $tally_name, $holder_type );
    }
    return $tallyboards;
}

// -----------------------------------------------------------------------------

class TallyBoard
{
    function TallyBoard( $tally_name, $holder_type )
    {
        $this->tally_name  = $tally_name;
        $this->holder_type = $holder_type;
    }

    // -------------------------------------------------------------------------

    function add_to_tally( $holder_id, $amount )
    {
        // In MySQL 4.1.0, we could use ON DUPLICATE KEY UPDATE to do this
        // in one query.

        mysql_query("
            UPDATE current_tallies
            SET tally_value = tally_value + $amount
            WHERE
                tally_name      = '$this->tally_name'
                AND holder_type = '$this->holder_type'
                AND holder_id   = '$holder_id'
        ") or die(mysql_error());

        $n = mysql_affected_rows();
        if ( $n > 1 )
        {
            // Key constraint should prevent this.
            die( "add_to_tally: query affected $n rows" );
        }
        else if ( $n == 0 )
        {
            // No row matched the WHERE condition.
            // Ensure that one exists.
            mysql_query("
                INSERT INTO current_tallies
                SET
                    tally_name  = '$this->tally_name',
                    holder_type = '$this->holder_type',
                    holder_id   = '$holder_id',
                    tally_value = $amount
            ") or die(mysql_error());
        }
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
            "IFNULL(current_tallies.tally_value,0)"
        );
    }

    // -------------------------------------------------------------------------

    function get_num_holders_with_positive_tally()
    {
        $res = mysql_query("
            SELECT COUNT(*)
            FROM current_tallies
            WHERE
                tally_name      = '$this->tally_name'
                AND holder_type = '$this->holder_type'
                AND tally_value > 0
        ") or die(mysql_error());
        return mysql_result($res,0);
    }

    // -------------------------------------------------------------------------

    function get_current_tally( $holder_id )
    // Return the given holder's current tally on this TallyBoard.
    // (Note that if you want tallies for multiple holders, it's probably
    // more efficient to use SQL-joinery supplied by the previous method.)
    {
        $res = mysql_query("
            SELECT tally_value
            FROM current_tallies
            WHERE
                tally_name      = '$this->tally_name'
                AND holder_type = '$this->holder_type'
                AND holder_id   = $holder_id
        ") or die(mysql_error());

        if ( mysql_num_rows($res) == 0 )
        {
            // The given holder is not on this board.
            // So they implicitly have a tally of zero.
            $current_tally = 0;
        }
        else
        {
            $current_tally = mysql_result($res,0,0);
        }
        return $current_tally;
    }

    // -------------------------------------------------------------------------

    function get_rank( $holder_id )
    // Return the given holder's rank, as determined
    // by the current tallies on this TallyBoard.
    {
        $result = mysql_query("
            SELECT holder_id, tally_value
            FROM current_tallies
            WHERE
                tally_name      = '$this->tally_name'
                AND holder_type = '$this->holder_type'
            ORDER BY tally_value DESC
        ");
        $ranker = new Ranker(TRUE);
        while (list($h_id,$tally_value) = mysql_fetch_row($result))
        {
            $rank = $ranker->next( $tally_value );
            if ($h_id == $holder_id) { return $rank; }
        }
        
        // The given holder is not on this board.
        // So they implicitly have a tally of zero.
        // So they have a rank of zero (null rank).
        return 0;
    }

    // -------------------------------------------------------------------------

    function get_neighborhood(
        $target_holder_id,
        $radius,
        // The remaining params are an optimization for extracting
        // other info from the database at the same time.
        $other_table,
        $holder_id_expr,
        $other_select_exprs,
        $tally_alias,
        $rank_alias
    )
    // Return the tally-neighborhood of the target holder. This is an array:
    //
    //    The keys are integers from the range [-$radius, +$radius],
    //    indicating a holder's position (w.r.t. tally)
    //    relative to the target holder.
    //    (So key=0 refers to the target holder.)
    //    The set of keys will be less than the full [-$radius, +$radius]
    //    range if the target holder holder is within $radius of the
    //    corresponding end of the ranked list.
    //
    //    For a given key, the value is data for the corresponding neighbor:
    //    an associative array as returned by mysql_fetch_assoc(), with an
    //    item added for the neighbor's rank.
    {
        assert( $radius >= 0 );

        $result = mysql_query("
            SELECT
                $holder_id_expr AS _holder_id,
                IFNULL(tally_value,0) AS $tally_alias,
                $other_select_exprs
            FROM
                $other_table
                LEFT OUTER JOIN current_tallies
                ON (
                    tally_name      = '$this->tally_name'
                    AND holder_type = '$this->holder_type'
                    AND holder_id   = $holder_id_expr
                )
            WHERE tally_value > 0 OR $holder_id_expr='$target_holder_id'
            ORDER BY tally_value DESC, holder_id ASC
        ") or die(mysql_error());
        // Note that for the purposes of this function,
        // we pretend that holders with tally_value <= 0 don't exist,
        // with the possible exception of the target holder.

        $num_rows = mysql_num_rows($result);
        
        // First, run through the results,
        // looking for the row that contains the target holder.
        $target_holder_r = NULL;
        for ( $r = 1; $r <= $num_rows; $r++ )
        {
            $row = mysql_fetch_assoc($result);
            if ( $row['_holder_id'] == $target_holder_id )
            {
                $target_holder_r = $r;
                break;
            }
        }

        if ( is_null($target_holder_r) )
        {
            die( "Holder '$target_holder_id' does not appear in $other_table.$holder_id_expr." );
        }
        // Note that it's *not* a problem if $target_holder_id doesn't appear
        // in current_tallies.holder_id. That's handled by the LEFT OUTER JOIN
        // and the IFNULL(tally_value,0).

        // Now, go through the results again (calculating rank as you go),
        // and when you're in the neighborhood of the target holder,
        // save the results in the $neighbors array.

        mysql_data_seek( $result, 0 );

        $neighbors = array();
        $r = 1;
        $ranker = new Ranker(FALSE);
        while ($row = mysql_fetch_assoc($result))
        {
            $rank = $ranker->next( $row[$tally_alias] );

            $rel_posn = $r - $target_holder_r;
            if ( abs($rel_posn) <= $radius )
            {
                // We're in the neighborhood of the target holder.

                $row[$rank_alias] = $rank;
                $neighbors[$rel_posn] = $row;
            }

            $r++;
        }

        return $neighbors;
    }

    // XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

    function take_snapshot( $ascribe_time, $dry_run )
    // Take a snapshot of this TallyBoard's current data,
    // and append it to the TallyBoard's history
    // (ascribing it to $ascribe_time rather than the current time).
    // If $dry_run is true, merely echo queries that would change db.
    // 
    {
        $prev_ascribe_time = $this->get_time_of_latest_snapshot();

        $prev_tally_for_ = array();
        if ( is_null($prev_ascribe_time) )
        {
            // No previous snapshot on this TallyBoard.
        }
        else
        {
            // There was a previous snapshot on this TallyBoard.

            if ( !$dry_run )
            {
                // Abort if this TallyBoard already has a snapshot for $ascribe_time
                if ( $prev_ascribe_time == $ascribe_time )
                {
                    return 'already';
                }
            }

            // Get everyone's most recent saved tally.
            $result = mysql_query("
                SELECT holder_id, tally_value
                FROM past_tallies
                WHERE
                    timestamp       = $prev_ascribe_time
                    AND tally_name  = '$this->tally_name'
                    AND holder_type = '$this->holder_type'
            ") or die(mysql_error());
            while ( list($holder_id, $prev_tally_value) = mysql_fetch_row($result) )
            {
                $prev_tally_for_[$holder_id] = $prev_tally_value;
            }
        }

        // Get everyone's current tally, in rank order.
        $result = mysql_query("
            SELECT holder_id, tally_value
            FROM current_tallies
            WHERE
                tally_name      = '$this->tally_name'
                AND holder_type = '$this->holder_type'
            ORDER BY tally_value DESC
        ") or die(mysql_error());

        $ranker = new Ranker(TRUE);
        while ( list($holder_id, $current_tally) = mysql_fetch_row($result) )
        {
            $rank = $ranker->next( $current_tally );
            $previous_tally = array_get( $prev_tally_for_, $holder_id, 0 );
            $tally_delta = $current_tally - $previous_tally;
            $query = "
                INSERT INTO past_tallies
                SET
                    timestamp   = $ascribe_time,
                    tally_name  = '$this->tally_name',
                    holder_type = '$this->holder_type',
                    holder_id   = $holder_id,
                    tally_delta = $tally_delta,
                    tally_value = $current_tally,
                    tally_rank  = $rank
            ";
            if ($dry_run)
            {
                // Normalize whitespace
                $query = preg_replace('/\s+/', ' ', trim($query));
                echo "$query\n";
            }
            else
            {
                mysql_query($query) or die(mysql_error());
            }
        }
    }

    // -------------------------------------------------------------------------

    function get_time_of_latest_snapshot( $default=NULL )
    // Return the ascribed timestamp of the latest snapshot for this TallyBoard,
    // or $default if there has been no snapshot yet.
    {
        $result = mysql_query("
            SELECT MAX(timestamp)
            FROM past_tallies
            WHERE
                tally_name      = '$this->tally_name'
                AND holder_type = '$this->holder_type'
        ") or die(mysql_error());
        $max_timestamp = mysql_result($result,0,0);

        if ( is_null($max_timestamp) )
        {
            // No snapshots for this TallyBoard yet.
            $max_timestamp = $default;
        }

        return $max_timestamp;
    }

    // -------------------------------------------------------------------------

    function get_info_from_latest_snapshot( $holder_id )
    // Return an associative array of information about the given holder
    // from the latest snapshot. Keys are:
    //     'timestamp'
    //     'tally_delta'
    //     'tally_value'
    //     'tally_rank'
    {
        $result = mysql_query("
            SELECT timestamp, tally_delta, tally_value, tally_rank
            FROM past_tallies
            WHERE
                tally_name      = '$this->tally_name'
                AND holder_type = '$this->holder_type'
                AND holder_id   = $holder_id
            ORDER BY timestamp DESC
            LIMIT 1
        ") or die(mysql_error());

        if ( mysql_num_rows($result) == 0 )
        {
            // That holder does not appear in any snapshot for this TallyBoard.
            // So we can infer that, at the latest snapshot:
            //     his tally would have been zero, and thus
            //     his delta would have been zero, and also
            //     his rank would have been zero (null rank).

            $timestamp = $this->get_time_of_latest_snapshot(0);

            $info = array(
                'timestamp' => $timestamp,
                'tally_delta' => 0,
                'tally_value' => 0,
                'tally_rank'  => 0
            );
        }
        else
        {
            $info = mysql_fetch_assoc($result);
        }
        return $info;
    }

    // -------------------------------------------------------------------------

    function get_info_re_largest_delta( $holder_id )
    // Return an array consisting of:
    // -- the given holder's largest recorded delta on this TallyBoard, and
    // -- the earliest timestamp for which that delta was recorded.
    {
        $result = mysql_query("
            SELECT tally_delta, timestamp
            FROM past_tallies
            WHERE
                tally_name      = '$this->tally_name'
                AND holder_type = '$this->holder_type'
                AND holder_id   = $holder_id
            ORDER BY tally_delta DESC, timestamp ASC
            LIMIT 1
        ") or die(mysql_error());

        if ( mysql_num_rows($result) == 0 )
        {
            // That holder does not appear in any snapshot for this TallyBoard.
            $largest_delta = 0;
            $timestamp = $this->get_time_of_latest_snapshot(0);
            $info = array( $largest_delta, $timestamp );
        }
        else
        {
            $info = mysql_fetch_row($result);
        }
        return $info;
    }

    // -------------------------------------------------------------------------

    function get_info_re_best_rank( $holder_id )
    // Return an array consisting of:
    // -- the given holder's best recorded rank on this TallyBoard, and
    // -- the earliest timestamp for which that rank was recorded.
    {
        $result = mysql_query("
            SELECT best_rank, best_rank_timestamp
            FROM best_tally_rank
            WHERE
                tally_name      = '$this->tally_name'
                AND holder_type = '$this->holder_type'
                AND holder_id   = $holder_id
        ") or die(mysql_error());

        if ( mysql_num_rows($result) == 0 )
        {
            // That holder does not appear on this TallyBoard.
            $best_rank = 0;
            $timestamp = $this->get_time_of_latest_snapshot(0);
            return array( $best_rank, $timestamp );
        }
        else
        {
            return mysql_fetch_row($result);
        }
    }

    // -------------------------------------------------------------------------

    function get_deltas( $holder_id, $min_timestamp )
    // Return all tally deltas recorded for the given holder
    // since $min_timestamp.
    // Specifically, the result is an array of key => value pairs,
    // where key is the ascribed time of a snapshot,
    // and value is the tally delta recorded for that time.
    // (The items are sorted by timestamp.)
    {
        $result = mysql_query("
            SELECT timestamp, tally_delta
            FROM past_tallies
            WHERE
                tally_name      = '$this->tally_name'
                AND holder_type = '$this->holder_type'
                AND holder_id   = '$holder_id'
                AND timestamp  >= $min_timestamp
            ORDER BY timestamp ASC
        ") or die(mysql_error());

        $delta_at_ = array();
        while( list($timestamp, $tally_delta) = mysql_fetch_row($result) )
        {
            $delta_at_[$timestamp] = $tally_delta;
        }
        return $delta_at_;
    }

    // -------------------------------------------------------------------------

    function get_delta_sum( $holder_id, $start_timestamp, $end_timestamp )
    // Return the sum of the 'tally_delta' values at all timestamps such that
    //     $start_timestamp < timestamp <= $end_timestamp
    // Note that the case of timestamp = $start_timestamp is excluded,
    // because its delta is for the period preceding $start_timestamp.
    //
    // If (as is often the case) $start_timestamp and $end_timestamp
    // match timestamps in the table, then the result should be the same
    // as the difference between the tally_value at those two times.
    // But summing the intervening deltas is the more general solution.
    {
        $res = mysql_query("
            SELECT SUM(tally_delta)
            FROM past_tallies
            WHERE
                tally_name      = '$this->tally_name'
                AND holder_type = '$this->holder_type'
                AND holder_id   = '$holder_id'
                AND $start_timestamp < timestamp
                AND timestamp <= $end_timestamp
        ") or die(mysql_error());
        return mysql_result($res,0);
    }

    // XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

    function get_vitals( $holder_id )
    // Return an object whose members are various useful quantities
    // from this tallyboard for the given holder.
    // (This is just a convenience function.)
    {
        $obj = new StdClass;

        $obj->current_tally = $this->get_current_tally($holder_id);
        $obj->current_rank  = $this->get_rank($holder_id);

        $snapshot_info = $this->get_info_from_latest_snapshot($holder_id);
        $obj->latest_snapshot_timestamp = $snapshot_info['timestamp'];
        $obj->latest_snapshot_delta     = $snapshot_info['tally_delta'];
        $obj->latest_snapshot_tally     = $snapshot_info['tally_value'];
        $obj->latest_snapshot_rank      = $snapshot_info['tally_rank'];

        list($obj->best_delta, $obj->best_delta_timestamp) =
            $this->get_info_re_largest_delta($holder_id);
        $today_delta = $obj->current_tally - $obj->latest_snapshot_tally;
        if ($today_delta > $obj->best_delta)
        {
            $obj->best_delta = $today_delta;
            $obj->best_delta_timestamp = time();
        }

        list($obj->best_rank, $obj->best_rank_timestamp) =
            $this->get_info_re_best_rank($holder_id);
        if ($obj->current_rank < $obj->best_rank)
        {
            $obj->best_rank = $obj->current_rank;
            $obj->best_rank_timestamp = time();
        }

        return $obj;
    }
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

// Given a descending sequence of values,
// an object of this class will yield their sequence of ranks.

class Ranker
{
    function Ranker( $zero_begets_zero )
    // If $zero_begets_zero is TRUE,
    // a value of zero or less will yield the special rank of zero.
    // (Think of it as a null rank.)
    {
        $this->zero_begets_zero = $zero_begets_zero;

        $this->n = 0;
        // Counts the number of calls to 'next',
        // and thus the position of each item in the sequence,
        // and is thus the first approximation to each item's rank.
    }

    function next( $value )
    {
        $this->n++;

        // The sequence of values supplied to the 'next' function
        // must be non-increasing
        // (i.e., each value must be less than or equal to the previous).
        //
        if ( $this->n == 1 )
        {
            // First call to 'next', no previous value.
            assert( !isset($this->prev_value) );
        }
        else
        {
            assert( $value <= $this->prev_value );
        }

        if ( $this->zero_begets_zero && $value <= 0 )
        {
            $rank = 0;
        }
        else if ( $this->n == 1 || $value != $this->prev_value )
        {
            // new rank
            $rank = $this->n;
        }
        else
        {
            // same rank as previous
            $rank = $this->prev_rank;
        }

        $this->prev_value = $value;
        $this->prev_rank  = $rank;

        return $rank;
    }
}

// vim: sw=4 ts=4 expandtab
?>
