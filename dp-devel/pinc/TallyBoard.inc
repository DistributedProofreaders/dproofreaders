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

        // When calculating ranks in a TallyBoard for teams,
        // we do not count team #1.
        // It's a kludge to express this here, because this class
        // shouldn't have to know anything about particular values
        // of holder_type or holder_id (or tally_name).
        //
        $this->rank_exclusion_kludge =
            ( $this->holder_type == 'T' ? 'holder_id != 1' : '1' );
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

    // -------------------------------------------------------------------------

    function get_rank( $holder_id )
    // Return the given holder's rank, as determined
    // by the current tallies on this TallyBoard.
    {
        $result = mysql_query("
            SELECT holder_id
            FROM current_tallies
            WHERE
                tally_name      = '$this->tally_name'
                AND holder_type = '$this->holder_type'
                AND $this->rank_exclusion_kludge
            ORDER BY tally_value DESC
        ");
        $i = 1;
        while ($row = mysql_fetch_assoc($result))
        {
            if ($row['holder_id'] == $holder_id) { return $i; }
            $i++;
        }
        // assert(FALSE); ?
        return NULL;
    }

    // -------------------------------------------------------------------------

    function get_ranks()
    // Return an associative array that maps each holder's id
    // to its rank, as detrmined by tallies.
    {
        $rank_of_ = array();

        $result = mysql_query("
            SELECT holder_id, tally_value
            FROM current_tallies
            WHERE
                tally_name      = '$this->tally_name'
                AND holder_type = '$this->holder_type'
                AND $this->rank_exclusion_kludge
            ORDER BY tally_value DESC
        ");
        $ranker = new Ranker(TRUE);
        while (list($holder_id, $tally_value) = mysql_fetch_row($result))
        {
            $rank_of_[$holder_id] = $ranker->next( $tally_value );
        }

        return $rank_of_;
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
    // Return an array consisting of:
    //
    // -- The tally-neighborhood of the target holder. This is an array:
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
    //    
    // -- The maximum current rank on this TallyBoard.
    {
        assert( $radius >= 0 );

        $result = mysql_query("
            SELECT
                holder_id,
                tally_value AS $tally_alias,
                $other_select_exprs
            FROM
                $other_table
                LEFT OUTER JOIN current_tallies
                ON (
                    tally_name      = '$this->tally_name'
                    AND holder_type = '$this->holder_type'
                    AND holder_id   = $holder_id_expr
                )
            WHERE tally_value > 0 OR holder_id='$target_holder_id'
            ORDER BY tally_value DESC
        ");
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
            if ( $row['holder_id'] == $target_holder_id )
            {
                $target_holder_r = $r;
                break;
            }
        }

        if ( is_null($target_holder_r) )
        {
            // The target holder does not appear on this TallyBoard.
            // This shouldn't ever happen.
            die( "Holder '$target_holder_id' does not appear on this TallyBoard." );
        }

        // Now, go through the results again (calculating rank as you go),
        // and when you're in the neighborhood of the target holder,
        // save the results in the $neighbors array.

        mysql_data_seek( $result, 0 );

        $neighbors = array();
        $r = 1;
        $ranker = new Ranker(FALSE);
        while ($row = mysql_fetch_assoc($result))
        {
            $latest_rank = $ranker->next( $row[$tally_alias] );

            $rel_posn = $r - $target_holder_r;
            if ( abs($rel_posn) <= $radius )
            {
                // We're in the neighborhood of the target holder.

                $row[$rank_alias] = $latest_rank;
                $neighbors[$rel_posn] = $row;
            }

            $r++;
        }

        $max_current_rank = $latest_rank;

        return array( $neighbors, $max_current_rank );
    }

    // XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

    function take_snapshot( $ascribe_time, $dry_run )
    // Take a snapshot of this TallyBoard's current data,
    // and append it to the TallyBoard's history
    // (ascribing it to $ascribe_time rather than the current time).
    // If $dry_run is true, merely echo queries that would change db.
    // 
    {
        // Get the (ascribed) time of the previous snapshot.
        $result = mysql_query("
            SELECT MAX(timestamp)
            FROM past_tallies
            WHERE
                tally_name      = '$this->tally_name'
                AND holder_type = '$this->holder_type'
        ") or die(mysql_error());
        $prev_ascribe_time = mysql_result($result,0,0);

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

        // Get everyone's current rank.
        $rank_for_ = $this->get_ranks();

        // Get everyone's current tally.
        $result = mysql_query("
            SELECT holder_id, tally_value
            FROM current_tallies
            WHERE
                tally_name      = '$this->tally_name'
                AND holder_type = '$this->holder_type'
                AND $this->rank_exclusion_kludge
        ") or die(mysql_error());

        while ( list($holder_id, $current_tally) = mysql_fetch_row($result) )
        {
            $rank = $rank_for_[$holder_id];
            $tally_delta = $current_tally - $prev_tally_for_[$holder_id];
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
        return mysql_fetch_assoc($result);
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
        return mysql_fetch_row($result);
    }

    // -------------------------------------------------------------------------

    function get_info_re_best_rank( $holder_id )
    // Return an array consisting of:
    // -- the given holder's best recorded rank on this TallyBoard, and
    // -- the earliest timestamp for which that rank was recorded.
    {
        $result = mysql_query("
            SELECT tally_rank, timestamp
            FROM past_tallies
            WHERE
                tally_name      = '$this->tally_name'
                AND holder_type = '$this->holder_type'
                AND holder_id   = $holder_id
            ORDER BY tally_rank ASC, timestamp ASC
            LIMIT 1
        ") or die(mysql_error());
        return mysql_fetch_row($result);
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
