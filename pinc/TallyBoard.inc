<?php

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

// A TallyBoard maintains all tally data (past and present)
// for a particular combination of tally_name and holder_type.

// If a holder isn't explicitly recorded in a TallyBoard,
// then it is implicitly recorded with a tally of zero.
// So the TallyBoard is prepared to answer questions about
// holders it has never heard of before.

// TallyBoards store and access data in 4 tables.
//
// For each (tally_name, holder_type, holder_id) tuple,
// * current_tallies - stores the holder's current tally and also their tally &
//                     tally_delta from the latest snapshot.
// * best_tally_rank - stores the highest 'rank' achieved by the holder, and
//                     when they achieved it.
// * past_tallies    - stores the holder's historical tally info, but only for
//                     snapshots in which their tally changed (tally_delta != 0).
//
// For each (tally_name, holder_type) tuple,
// * tally_snapshot_times - records the times of all past snapshots

/**
 * Return an array of TallyBoard objects, representing all
 * boards on which there is some tally explicitly recorded.
 *
 * @return TallyBoard[]
 */
function get_all_current_tallyboards()
{
    $tallyboards = [];
    $res = DPDatabase::query("
        SELECT DISTINCT tally_name, holder_type
        FROM current_tallies
        ORDER BY holder_type, tally_name
    ");
    while ([$tally_name, $holder_type] = mysqli_fetch_row($res)) {
        $tallyboards[] = new TallyBoard($tally_name, $holder_type);
    }
    return $tallyboards;
}

// -----------------------------------------------------------------------------

class TallyBoard
{
    public string $tally_name;
    public string $holder_type;

    public function __construct(string $tally_name, string $holder_type)
    {
        $this->tally_name = $tally_name;
        $this->holder_type = $holder_type;
    }

    // -------------------------------------------------------------------------

    public function add_to_tally(string $holder_id, int $amount)
    {
        $sql = sprintf(
            "
            INSERT INTO current_tallies
            SET
                tally_name  = '%s',
                holder_type = '%s',
                holder_id   = %d,
                tally_value = %d
            ON DUPLICATE KEY UPDATE
                tally_value = tally_value + %d
            ",
            DPDatabase::escape($this->tally_name),
            DPDatabase::escape($this->holder_type),
            intval($holder_id),
            intval($amount),
            intval($amount)
        );
        DPDatabase::query($sql);
    }

    // -------------------------------------------------------------------------

    /**
     * Return an array containing two strings of SQL code to
     * extract the current tallies for a (SQL-specified) set of holders.
     *
     * The resulting strings are:
     * - a join-expression (for use in the FROM clause);
     * - a select-expression (for use in the SELECT and/or WHERE clauses)
     *   that yields the current tally for each selected holder.
     *   (It could be just a column-name, but don't rely on that.
     *   In particular, don't assume that this will work:
     *   ```
     *   $res = DPDatabase::query("SELECT ... $expr_for_tally FROM ...");
     *   $row = mysqli_fetch_assoc($res);
     *   ... $row[$expr_for_tally] ...
     *   ```
     *   Instead, if you want to use mysqli_fetch_assoc, this should work:
     *   ```
     *   $res = DPDatabase::query("SELECT ... $expr_for_tally AS foo FROM ...");
     *   $row = mysqli_fetch_assoc($res);
     *   ... $row['foo'] ...
     *   ```
     *
     * @param string $holder_id_expr
     *   String containing an SQL expression (typically just a column name)
     *   that yields the holder_ids of the holders for whom you want to extract
     *   tally data.
     * @return string[]
     */
    public function get_sql_joinery_for_current_tallies($holder_id_expr)
    {
        static $n = 0;
        $n++;
        $alias = "current_tallies_$n";
        $join_sql = sprintf(
            "
            LEFT OUTER JOIN current_tallies AS $alias
            ON (
                $alias.tally_name      = '%s'
                AND $alias.holder_type = '%s'
                AND $alias.holder_id   = $holder_id_expr
            )
            ",
            DPDatabase::escape($this->tally_name),
            DPDatabase::escape($this->holder_type)
        );
        return [$join_sql, "IFNULL($alias.tally_value,0)"];
    }

    // -------------------------------------------------------------------------

    public function get_num_holders_with_positive_tally(): int
    {
        $sql = sprintf(
            "
            SELECT COUNT(*)
            FROM current_tallies
            WHERE
                tally_name      = '%s'
                AND holder_type = '%s'
                AND tally_value > 0
            ",
            DPDatabase::escape($this->tally_name),
            DPDatabase::escape($this->holder_type)
        );
        $res = DPDatabase::query($sql);
        [$count] = mysqli_fetch_row($res);
        return $count;
    }

    // -------------------------------------------------------------------------

    /**
     * Return the given holder's current tally on this TallyBoard.
     *
     * Note that if you want tallies for multiple holders, it's probably
     * more efficient to use SQL-joinery supplied by the previous method.
     */
    public function get_current_tally(string $holder_id): int
    {
        $sql = sprintf(
            "
            SELECT tally_value
            FROM current_tallies
            WHERE
                tally_name      = '%s'
                AND holder_type = '%s'
                AND holder_id   = %s
            ",
            DPDatabase::escape($this->tally_name),
            DPDatabase::escape($this->holder_type),
            intval($holder_id)
        );
        $res = DPDatabase::query($sql);

        $row = mysqli_fetch_assoc($res);
        if (!$row) {
            // The given holder is not on this board.
            // So they implicitly have a tally of zero.
            $current_tally = 0;
        } else {
            $current_tally = $row["tally_value"];
        }
        return $current_tally;
    }

    // -------------------------------------------------------------------------

    /**
     * Return the given holder's rank, as determined by the current tallies
     * on this TallyBoard.
     */
    public function get_rank($holder_id)
    {
        $sql = sprintf(
            "
            SELECT holder_id, tally_value
            FROM current_tallies
            WHERE
                tally_name      = '%s'
                AND holder_type = '%s'
            ORDER BY tally_value DESC
            ",
            DPDatabase::escape($this->tally_name),
            DPDatabase::escape($this->holder_type)
        );
        $result = DPDatabase::query($sql);
        $ranker = new Ranker(true);
        while ([$h_id, $tally_value] = mysqli_fetch_row($result)) {
            $rank = $ranker->next($tally_value);
            if ($h_id == $holder_id) {
                return $rank;
            }
        }

        // The given holder is not on this board.
        // So they implicitly have a tally of zero.
        // So they have a rank of zero (null rank).
        return 0;
    }

    // -------------------------------------------------------------------------

    /**
     * Return the tally-neighborhood of the target holder.
     *
     * Returns an array where the keys are integers from the range
     * [-$radius, +$radius], indicating a holder's position (w.r.t. tally)
     * relative to the target holder. (So key=0 refers to the target holder.)
     * The set of keys will be less than the full [-$radius, +$radius]
     * range if the target holder holder is within $radius of the
     * corresponding end of the ranked list.
     *
     * For a given key, the value is data for the corresponding neighbor:
     * an associative array as returned by `mysqli_fetch_assoc()`, with an
     * item added for the neighbor's rank.
     *
     * Parameters after `$radius` are an optimization for extracting
     * other info from the database at the same time.
     */
    public function get_neighborhood($target_holder_id, $radius)
    {
        assert($radius >= 0);

        $sql = sprintf(
            "
            SELECT
                IFNULL(tally_value, 0) AS current_tally,
                u_id, username, u_privacy, date_created
            FROM
                current_tallies
                RIGHT OUTER JOIN users ON u_id = holder_id
            WHERE
                tally_name = '%s'
                AND holder_type = '%s'
                AND tally_value > 0
            ORDER BY tally_value DESC, u_id ASC
            ",
            DPDatabase::escape($this->tally_name),
            DPDatabase::escape($this->holder_type)
        );
        $result = DPDatabase::query($sql);
        // Note that for the purposes of this function,
        // we pretend that holders with tally_value <= 0 don't exist.

        // First, run through the results,
        // looking for the row that contains the target holder.
        $target_holder_r = null;
        $r = 1;
        while ($row = mysqli_fetch_assoc($result)) {
            if ($row['u_id'] == $target_holder_id) {
                $target_holder_r = $r;
                break;
            }
            $r += 1;
        }

        // if the user wasn't in the results, they have a de facto
        // current_tally of 0; fill in the neighbors above them and then
        // populate their row
        if (is_null($target_holder_r)) {
            $target_holder_r = $r;
            $fill_zero_entry = true;
        } else {
            $fill_zero_entry = false;
        }

        // Now, go through the results again (calculating rank as you go),
        // and when you're in the neighborhood of the target holder,
        // save the results in the $neighbors array.

        mysqli_data_seek($result, 0);

        $neighbors = [];
        $r = 1;
        $ranker = new Ranker(false);
        while ($row = mysqli_fetch_assoc($result)) {
            $rank = $ranker->next($row["current_tally"]);

            $rel_posn = $r - $target_holder_r;
            if (abs($rel_posn) <= $radius) {
                // We're in the neighborhood of the target holder.

                $row["current_rank"] = $rank;
                $neighbors[$rel_posn] = $row;
            }

            $r++;
        }
        mysqli_free_result($result);

        // we need to pull the same data from the users table but with a
        // current_tally of 0 to populate $neighbors
        if ($fill_zero_entry) {
            $sql = sprintf(
                "
                SELECT
                    0 AS current_tally,
                    u_id, username, u_privacy, date_created
                FROM users
                WHERE u_id = %d
                ",
                $target_holder_id
            );
            $result = DPDatabase::query($sql);
            $row = mysqli_fetch_assoc($result);
            mysqli_free_result($result);
            $rank = $ranker->next($row["current_tally"]);
            $row["current_rank"] = $rank;
            $neighbors[0] = $row;
        }

        return $neighbors;
    }

    // XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

    /**
     * Take a snapshot of this TallyBoard's current data,
     * and append it to the TallyBoard's history
     * (ascribing it to $ascribe_time rather than the current time).
     *
     * If `$dry_run` is true, merely echo queries that would change db.
     */
    public function take_snapshot($ascribe_time, $dry_run = false)
    {
        $prev_ascribe_time = $this->get_time_of_latest_snapshot();

        $best_rank_for_ = [];
        if (is_null($prev_ascribe_time)) {
            // No previous snapshot on this TallyBoard.
        } else {
            // There was a previous snapshot on this TallyBoard.

            if (!$dry_run) {
                // Abort if this TallyBoard already has a snapshot for $ascribe_time
                if ($prev_ascribe_time == $ascribe_time) {
                    return 'already';
                }
            }

            // Get everyone's best tally-rank so far.
            $sql = sprintf(
                "
                SELECT holder_id, best_rank
                FROM best_tally_rank
                WHERE
                    tally_name      = '%s'
                    AND holder_type = '%s'
                ",
                DPDatabase::escape($this->tally_name),
                DPDatabase::escape($this->holder_type)
            );
            $result = DPDatabase::query($sql);
            while ([$holder_id, $best_rank] = mysqli_fetch_row($result)) {
                $best_rank_for_[$holder_id] = $best_rank;
            }
        }

        // update the last snapshot values for all holders of this Tallyboard
        $query = sprintf(
            "
            UPDATE current_tallies
            SET
                last_snap_tally_delta = tally_value - last_snap_tally_value,
                last_snap_tally_value = tally_value
            WHERE
                tally_name  = '%s'
                AND holder_type = '%s'
            ",
            DPDatabase::escape($this->tally_name),
            DPDatabase::escape($this->holder_type),
        );
        if ($dry_run) {
            // Normalize whitespace
            $query = preg_replace('/\s+/', ' ', trim($query));
            echo "$query\n";
        } else {
            DPDatabase::query($query);
        }

        // Now that we've captured everyone's current tally (into `last_snap_*`),
        // `tally_value` is free to change, and we'll use `last_snap_*` to
        // carry out the rest of this snapshot (updating `past_tallies` and
        // `best_tally_rank`).

        // Get everyone's current tally, in rank order.
        $sql = sprintf(
            "
            SELECT holder_id, last_snap_tally_delta, last_snap_tally_value
            FROM current_tallies
            WHERE
                tally_name      = '%s'
                AND holder_type = '%s'
            ORDER BY tally_value DESC
            ",
            DPDatabase::escape($this->tally_name),
            DPDatabase::escape($this->holder_type)
        );
        $result = DPDatabase::query($sql);

        $ranker = new Ranker(true);
        while ([$holder_id, $snap_tally_delta, $snap_tally_value] = mysqli_fetch_row($result)) {
            $rank = $ranker->next($snap_tally_value);

            if ($snap_tally_delta <> 0) {
                $query = sprintf(
                    "
                    INSERT INTO past_tallies
                    SET
                        timestamp   = %d,
                        tally_name  = '%s',
                        holder_type = '%s',
                        holder_id   = %d,
                        tally_delta = %d,
                        tally_value = %d
                    ",
                    $ascribe_time,
                    DPDatabase::escape($this->tally_name),
                    DPDatabase::escape($this->holder_type),
                    $holder_id,
                    $snap_tally_delta,
                    $snap_tally_value
                );
                if ($dry_run) {
                    // Normalize whitespace
                    $query = preg_replace('/\s+/', ' ', trim($query));
                    echo "$query\n";
                } else {
                    DPDatabase::query($query);
                }
            }

            if ($rank > 0 && (!isset($best_rank_for_[$holder_id]) || $rank < $best_rank_for_[$holder_id])) {
                $query = sprintf(
                    "
                    REPLACE INTO best_tally_rank
                    SET
                        tally_name  = '%s',
                        holder_type = '%s',
                        holder_id   = %d,
                        best_rank   = $rank,
                        best_rank_timestamp = $ascribe_time
                    ",
                    DPDatabase::escape($this->tally_name),
                    DPDatabase::escape($this->holder_type),
                    intval($holder_id)
                );
                if ($dry_run) {
                    // Normalize whitespace
                    $query = preg_replace('/\s+/', ' ', trim($query));
                    echo "$query\n";
                } else {
                    DPDatabase::query($query);
                }
            }
        }

        // insert this snapshot time into tally_snapshot_times
        $sql = sprintf(
            "
            INSERT INTO tally_snapshot_times
            SET
                tally_name = '%s',
                holder_type = '%s',
                timestamp = %d
            ",
            DPDatabase::escape($this->tally_name),
            DPDatabase::escape($this->holder_type),
            $ascribe_time
        );
        if ($dry_run) {
            // Normalize whitespace
            $sql = preg_replace('/\s+/', ' ', trim($sql));
            echo "$sql\n";
        } else {
            DPDatabase::query($sql);
        }
    }

    // -------------------------------------------------------------------------

    /**
     * Return the ascribed timestamp of the latest snapshot for this TallyBoard,
     * or $default if there has been no snapshot yet.
     */
    public function get_time_of_latest_snapshot($default = null)
    {
        $sql = sprintf(
            "
            SELECT MAX(timestamp) as max_timestamp
            FROM tally_snapshot_times
            WHERE
                tally_name      = '%s'
                AND holder_type = '%s'
            ",
            DPDatabase::escape($this->tally_name),
            DPDatabase::escape($this->holder_type)
        );
        $result = DPDatabase::query($sql);

        $row = mysqli_fetch_assoc($result);
        if (!$row) {
            // No snapshots for this TallyBoard yet.
            $max_timestamp = $default;
        } else {
            $max_timestamp = $row["max_timestamp"];
        }

        return $max_timestamp;
    }

    // -------------------------------------------------------------------------

    /**
     * Return an associative array of information about the given holder
     * from the latest snapshot.
     *
     * Keys are:
     * - timestamp
     * - tally_delta
     * - tally_value
     */
    public function get_info_from_latest_snapshot($holder_id)
    {
        $sql = sprintf(
            "
            SELECT
                last_snap_tally_delta as tally_delta,
                last_snap_tally_value as tally_value
            FROM current_tallies
            WHERE
                tally_name      = '%s'
                AND holder_type = '%s'
                AND holder_id   = %d
            ",
            DPDatabase::escape($this->tally_name),
            DPDatabase::escape($this->holder_type),
            intval($holder_id)
        );
        $result = DPDatabase::query($sql);

        $info = mysqli_fetch_assoc($result);
        if (!$info) {
            // That holder does not appear in any snapshot for this TallyBoard.
            // So we can infer that, at the latest snapshot:
            //     his tally would have been zero, and thus
            //     his delta would have been zero, and also
            //     his rank would have been zero (null rank).

            $info = [
                'tally_delta' => 0,
                'tally_value' => 0,
            ];
        }
        $info["timestamp"] = $this->get_time_of_latest_snapshot(0);

        return $info;
    }

    // -------------------------------------------------------------------------

    /**
     * Return an array of the largest recorded delta on this TallyBoard
     *
     * Array consisting of:
     * - the given holder's largest recorded delta on this TallyBoard, and
     * - the earliest timestamp for which that delta was recorded.
     */
    public function get_info_re_largest_delta($holder_id)
    {
        $sql = sprintf(
            "
            SELECT tally_delta, timestamp
            FROM past_tallies
            WHERE
                tally_name      = '%s'
                AND holder_type = '%s'
                AND holder_id   = %d
            ORDER BY tally_delta DESC, timestamp ASC
            LIMIT 1
            ",
            DPDatabase::escape($this->tally_name),
            DPDatabase::escape($this->holder_type),
            intval($holder_id)
        );
        $result = DPDatabase::query($sql);

        $info = mysqli_fetch_row($result);
        if (!$info) {
            // That holder does not appear in any snapshot for this TallyBoard.
            $largest_delta = 0;
            $timestamp = $this->get_time_of_latest_snapshot(0);
            $info = [$largest_delta, $timestamp];
        }
        return $info;
    }

    // -------------------------------------------------------------------------

    /**
     * Return an array of the best recorded rank on this TallyBoard
     *
     * Array consisting of:
     * - the given holder's best recorded rank on this TallyBoard, and
     * - the earliest timestamp for which that rank was recorded.
     */
    public function get_info_re_best_rank($holder_id)
    {
        $sql = sprintf(
            "
            SELECT best_rank, best_rank_timestamp
            FROM best_tally_rank
            WHERE
                tally_name      = '%s'
                AND holder_type = '%s'
                AND holder_id   = %d
            ",
            DPDatabase::escape($this->tally_name),
            DPDatabase::escape($this->holder_type),
            intval($holder_id)
        );
        $result = DPDatabase::query($sql);

        $info = mysqli_fetch_row($result);
        if (!$info) {
            // That holder does not appear on this TallyBoard.
            $best_rank = 0;
            $timestamp = $this->get_time_of_latest_snapshot(0);
            $info = [$best_rank, $timestamp];
        }
        return $info;
    }

    // -------------------------------------------------------------------------

    /**
     * Return all tally deltas recorded for the given holder
     * since $min_timestamp and optionally before $max_timestamp
     *
     * Specifically, the result is an array of key => value pairs,
     * where key is the ascribed time of a snapshot,
     * and value is the tally delta recorded for that time.
     * (The items are sorted by timestamp.)
     */
    public function get_deltas($holder_id, $min_timestamp, $max_timestamp = null)
    {
        $addl_where = $max_timestamp === null ? "" : sprintf("AND timestamp < %d", $max_timestamp);

        // Initialize all of the snapshot times
        $sql = sprintf(
            "
            SELECT timestamp
            FROM tally_snapshot_times
            WHERE
                tally_name      = '%s'
                AND holder_type = '%s'
                AND timestamp  >= %d
                $addl_where
            ORDER BY timestamp ASC
            ",
            DPDatabase::escape($this->tally_name),
            DPDatabase::escape($this->holder_type),
            $min_timestamp
        );
        $result = DPDatabase::query($sql);

        $delta_at_ = [];
        while ([$timestamp] = mysqli_fetch_row($result)) {
            $delta_at_[$timestamp] = 0;
        }

        // Fill in any actual deltas from past_tallies
        $sql = sprintf(
            "
            SELECT timestamp, tally_delta
            FROM past_tallies
            WHERE
                tally_name      = '%s'
                AND holder_type = '%s'
                AND holder_id   = %d
                AND timestamp  >= %d
                $addl_where
            ",
            DPDatabase::escape($this->tally_name),
            DPDatabase::escape($this->holder_type),
            $holder_id,
            $min_timestamp
        );
        $result = DPDatabase::query($sql);

        while ([$timestamp, $tally_delta] = mysqli_fetch_row($result)) {
            $delta_at_[$timestamp] = $tally_delta;
        }

        return $delta_at_;
    }

    // -------------------------------------------------------------------------

    /**
     * Return the sum of the 'tally_delta' values at all timestamps such that
     * `$start_timestamp < timestamp <= $end_timestamp`
     *
     * Note that the case of timestamp = $start_timestamp is excluded,
     * because its delta is for the period preceding $start_timestamp.
     *
     * If (as is often the case) $start_timestamp and $end_timestamp
     * match timestamps in the table, then the result should be the same
     * as the difference between the tally_value at those two times.
     * But summing the intervening deltas is the more general solution.
     */
    public function get_delta_sum($holder_id, $start_timestamp, $end_timestamp)
    {
        $sql = sprintf(
            "
            SELECT SUM(tally_delta)
            FROM past_tallies
            WHERE
                tally_name      = '%s'
                AND holder_type = '%s'
                AND holder_id   = %d
                AND %d < timestamp
                AND timestamp <= %d
            ",
            DPDatabase::escape($this->tally_name),
            DPDatabase::escape($this->holder_type),
            $holder_id,
            $start_timestamp,
            $end_timestamp
        );
        $res = DPDatabase::query($sql);
        [$sum] = mysqli_fetch_row($res);
        return $sum;
    }

    // XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

    public function get_delta_since_latest_snapshot($holder_id)
    {
        $current_tally = $this->get_current_tally($holder_id);
        $snapshot_info = $this->get_info_from_latest_snapshot($holder_id);
        $latest_snapshot_tally = $snapshot_info['tally_value'];
        return ($current_tally - $latest_snapshot_tally);
    }

    /**
     * Return an object whose members are various useful quantities
     * from this tallyboard for the given holder.
     *
     * This is just a convenience function.
     */
    public function get_vitals($holder_id)
    {
        $obj = new StdClass();

        $obj->current_tally = $this->get_current_tally($holder_id);
        $obj->current_rank = $this->get_rank($holder_id);

        $snapshot_info = $this->get_info_from_latest_snapshot($holder_id);
        $obj->latest_snapshot_timestamp = $snapshot_info['timestamp'];
        $obj->latest_snapshot_delta = $snapshot_info['tally_delta'];
        $obj->latest_snapshot_tally = $snapshot_info['tally_value'];

        [$obj->best_delta, $obj->best_delta_timestamp] =
            $this->get_info_re_largest_delta($holder_id);
        $today_delta = $obj->current_tally - $obj->latest_snapshot_tally;
        if ($today_delta > $obj->best_delta) {
            $obj->best_delta = $today_delta;
            $obj->best_delta_timestamp = time();
        }

        [$obj->best_rank, $obj->best_rank_timestamp] =
            $this->get_info_re_best_rank($holder_id);
        if ($obj->current_rank < $obj->best_rank) {
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
    private bool $zero_begets_zero;
    private int $n;
    private int $prev_rank;
    private int $prev_value;
    /**
     * Ranker constructor
     *
     * @param bool $zero_begets_zero
     *   If TRUE, a value of zero or less will yield the special rank of zero.
     *   (Think of it as a null rank.)
     */
    public function __construct($zero_begets_zero)
    {
        $this->zero_begets_zero = $zero_begets_zero;

        $this->n = 0;
        // Counts the number of calls to 'next',
        // and thus the position of each item in the sequence,
        // and is thus the first approximation to each item's rank.
    }

    public function next($value)
    {
        $this->n++;

        // The sequence of values supplied to the 'next' function
        // must be non-increasing
        // (i.e., each value must be less than or equal to the previous).
        //
        if ($this->n == 1) {
            // First call to 'next', no previous value.
            assert(!isset($this->prev_value));
        } else {
            assert($value <= $this->prev_value);
        }

        if ($this->zero_begets_zero && $value <= 0) {
            $rank = 0;
        } elseif ($this->n == 1 || $value != $this->prev_value) {
            // new rank
            $rank = $this->n;
        } else {
            // same rank as previous
            $rank = $this->prev_rank;
        }

        $this->prev_value = $value;
        $this->prev_rank = $rank;

        return $rank;
    }
}
