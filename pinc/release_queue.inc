<?php
// A place for shared functions dealing with release queues.
include_once($relPath.'Project.inc');

define('MAX_PROJECTS_TARGET', 65535); // max unsigned smallint
define('MAX_PAGES_TARGET', 16777215); // max unsigned mediumint

/**
 * Format a queue's targets as a release condition.
 */
function format_queue_targets_as_condition(int $projects_target, int $pages_target): string
{
    if ($projects_target == MAX_PROJECTS_TARGET || $pages_target == MAX_PAGES_TARGET) {
        // The queue always releases (assuming that the actual number of relevant
        // projects/pages is always less than the corresponding target's maximum).
        return "TRUE";
    } elseif ($projects_target == 0 && $pages_target == 0) {
        return "FALSE";
    } elseif ($projects_target > 0 && $pages_target == 0) {
        return "projects < $projects_target";
    } elseif ($projects_target == 0 && $pages_target > 0) {
        return "pages < $pages_target";
    } else {
        return "projects < $projects_target or pages < $pages_target";
    }
}


$today_MMDD = date('md', strtotime('today'));
$tomorrow_MMDD = date('md', strtotime('tomorrow'));

/**
 * Expand certain sequences in $project_selector.
 *
 * Expanding date-related sequences means we don't have to
 * update the queue's project_selector every day.
 */
function cook_project_selector(string $project_selector): string
{
    global $today_MMDD, $tomorrow_MMDD;
    return
        preg_replace(
            ['/{today_MMDD}/', '/{tomorrow_MMDD}/'],
            [$today_MMDD,      $tomorrow_MMDD],
            $project_selector
        );
}

/** @return array{0: ?int, 1: ?int} */
function _get_queue_length(string $cooked_project_selector, string $project_waiting_state): array
{
    $state_clause = set_col_str("state", $project_waiting_state);
    $c_sql = "
        SELECT count(*) as total, SUM(CASE WHEN project_holds.state is NULL THEN 1 ELSE 0 END) as unheld
        FROM projects
            LEFT OUTER JOIN project_holds USING (projectid, state)
        WHERE ($cooked_project_selector)
            AND $state_clause
    ";

    try {
        $c_res = DPDatabase::query($c_sql);
        $row = mysqli_fetch_row($c_res);
        return [$row[0], $row[1] ?? 0];
    } catch (DBQueryError $error) {
        return [null, null];
    }
}

/**
 * Generator to return queue_defns table data for queues, one queue at a time.
 *
 * Data is returned as an array of associative arrays that abstracts away
 * the lower-level database structure.
 *
 * For rows that represent queue groups, the returned format looks like:
 * ```
 * {
 *    "is_group": true,
 *    "group": "PM Queues (experimental)",
 * }
 * ```
 * For rows that represent queues, the returned format looks like:
 * ```
 * {
 *     "id": 161,
 *     "round_id": "P1",
 *     "is_group": false,
 *     "group": "PM Queues (experimental)",
 *     "ordering": 3999,
 *     "name": "EASY English",
 *     "enabled": true,
 *     "populated": true,
 *     "length": 1,
 *     "unheld_length": 0,
 *     "comment": "Always have EASY English in P1",
 *     "projects_target": 4,
 *     "pages_target": 120
 * }
 * ```
 *
 * @param null|Round $round
 *   Return the queues for the specified round, or all rounds if null.
 * @param string $show
 *   Select which queues to return:
 *   * all - Don't filter out any queues
 *   * enabled - Filter out queues which aren't enabled
 *   * populated - Filter out queues which aren't enabled *OR* contain no projects
 * @param null|string|array $name_selector
 *   Select which queues to return in the query where:
 *   * null - Don't filter out any queues
 *   * string - Filter out queues whose name does not contain string
 *   * array - Filter out queues whose name contains none of the strings
 * @param null|string|array $group_selector
 *   Select which groups and queues to return:
 *   * null - Don't filter out any groups/queues.
 *   * string - Filter out queues/groups whose group name does not contain string
 *   * array - Filter out queues/groups whose group name contains none of the strings
 * @param boolean $show_groups
 *   If true, return dummy group objects as well as queues
 *
 * @return Generator<array>
 */
function fetch_queues_data(?Round $round, string $show, bool $show_groups, $name_selector = null, $group_selector = null)
{
    // Building the WHERE clause is rather ad-hoc, mostly because
    // a bunch of the output fields don't come directly from the DB result.
    if (!is_null($round)) {
        $proj_where = set_col_str("round_id", $round->id);
    } else {
        $proj_where = "1";
    }
    $clauses = [];
    if (in_array($show, ["enabled", "populated"])) {
        $clauses[] = "enabled = 1";
    }
    if (is_string($name_selector)) {
        $name_selector = [$name_selector];
    }
    if (is_array($name_selector)) {
        $values = array_map("DPDatabase::escape", $name_selector);
        $clauses[] = surround_and_join($values, "name LIKE '%", "%'", ' OR ');
    }
    if (empty($clauses)) {
        $clauses = ["1"];
    }
    $filter_where = surround_and_join($clauses, "(", ")", " AND ");
    // We always want to see the rows that introduce groups.
    $where = "$proj_where AND ($filter_where OR (SUBSTR(name, 1, 1) = '*'))";
    $sql = "
        SELECT *
        FROM queue_defns
        WHERE $where
        ORDER BY round_id, ordering
    ";

    // Because queues are ordered, there is a convention to break them up
    // into sections with a disabled queue at the start of the section
    // indicating the section's name. These queue names start with "***"
    // and we identify them here and treat them differently.
    $group = null; // the most recently seen row defining a group
    $round = null; // the most recently seen round.
    $res = DPDatabase::query($sql);
    while ($row = mysqli_fetch_assoc($res)) {
        // If we've gone from (eg) round F2 in the previous loop iteration, to P1
        // in this iteration, then reset the group
        if (!$round || $round->id != $row["round_id"]) {
            $round = Rounds::get_by_id($row["round_id"]);
            $group = null;
        }
        if (str_starts_with($row["name"], "***")) {
            $group = str_replace("***", "", $row["name"]);
            // Check for group
            if ($show_groups && str_contains_any_of($group, $group_selector)) {
                yield [
                    "is_group" => true,
                    "group" => $group,
                ];
            }
            continue;
        }

        if (!is_null($group_selector) && !str_contains_any_of($group, $group_selector)) {
            continue;
        }

        [$length, $unheld_length] = _get_queue_length($row["project_selector"], $round->project_waiting_state);
        if ($show == "populated" && $length == 0) {
            continue;
        }
        $data = _queue_data($row, $length, $unheld_length);
        $data["is_group"] = false;
        $data["group"] = $group;
        yield $data;
    }
}

/**
 * Return queue data array for a specified release queue
 *
 * @param Round $round
 *   The round containing the release queue
 * @param string $name
 *   The release queue's name
 *
 * @return array|null
 **/
function fetch_queue_data_by_name($round, $name)
{
    $sql = sprintf(
        "
        SELECT *
        FROM queue_defns
        WHERE round_id='%s' AND name='%s'
        ",
        DPDatabase::escape($round->id),
        DPDatabase::escape($name)
    );
    $res = mysqli_fetch_assoc(DPDatabase::query($sql));
    if (!$res) {
        return null;
    }
    return _queue_data($res);
}

/**
 * Return queue data array for a specified release queue
 **/
function fetch_queue_data(int $queueid): ?array
{
    $sql = sprintf(
        "
        SELECT *
        FROM queue_defns
        WHERE id=%d
        ",
        $queueid
    );
    $res = mysqli_fetch_assoc(DPDatabase::query($sql));
    if (!$res) {
        return null;
    }
    return _queue_data($res);
}

function _queue_data(array $row, int $length = null, int $unheld_length = null): array
{
    $round = Rounds::get_by_id($row["round_id"]);
    if (is_null($length) || is_null($unheld_length)) {
        [$length, $unheld_length] = _get_queue_length($row["project_selector"], $round->project_waiting_state);
    }
    return [
        "id" => (int)$row["id"],
        "round_id" => $row["round_id"],
        "ordering" => (int)$row["ordering"],
        "name" => $row["name"],
        "enabled" => (bool)$row["enabled"],
        "populated" => $length != 0,
        "length" => (int)$length,
        "unheld_length" => (int)$unheld_length,
        "comment" => $row["comment"],
        "project_selector" => $row["project_selector"],
        "projects_target" => (int)$row["projects_target"],
        "pages_target" => (int)$row["pages_target"],
    ];
}

/**
 * A generator that yields project data for projects in a queue
 *
 * @param Round $round
 *   The queue the round is in.
 * @param string $project_selector
 *   The `queue_defns.project_selector` field for the queue
 * @param boolean $unheld_only
 *   Only return projects that aren't in the hold state.
 *
 * @return Generator<array>
 **/
function fetch_queue_projects_data(Round $round, string $project_selector, bool $unheld_only)
{
    $cooked_project_selector = cook_project_selector($project_selector);
    $unheld_only_sql = $unheld_only ? "project_holds.state is NULL" : "1";

    $sql = "
        SELECT
            projectID,
            nameofwork,
            authorsname,
            language,
            genre,
            difficulty,
            username,
            modifieddate,
            project_holds.state as holds_state
        FROM projects
            LEFT OUTER JOIN project_holds USING (projectid, state)
        WHERE ($cooked_project_selector)
            AND state='{$round->project_waiting_state}'
            AND $unheld_only_sql
        ORDER BY modifieddate, nameofwork
    ";
    $res = DPDatabase::query($sql);
    while ($row = mysqli_fetch_assoc($res)) {
        yield [
            "projectid" => $row["projectID"],
            "title" => $row["nameofwork"],
            "author" => $row["authorsname"],
            "languages" => Project::decode_language($row["language"]),
            "genre" => $row["genre"],
            "difficulty" => $row["difficulty"],
            "project_manager" => $row["username"],
            "last_state_change_time" => (int)$row["modifieddate"],
            "holds_state" => $row["holds_state"],
        ];
    }
}

/**
 * Return the page and project releases stats for a given release queue
 * @param Round $round
 *   The round containing the release queue
 * @param string $name
 *   The name of the release queue
 *
 * @return array{"days_ago": int, "projects_released": int, "pages_released": int}[]
 **/
function fetch_queue_stats_data(Round $round, string $name): array
{
    $data = [];
    foreach ([1, 7, 28, 84] as $days_ago) {
        $seconds_ago = time() - 60 * 60 * 24 * $days_ago;
        $sql = sprintf(
            "
            SELECT count(*)
            FROM project_events
            WHERE event_type = 'transition'
                AND details1 = '%s'
                AND details3 = 'via_q: %s'
                AND timestamp >= %d
            ",
            DPDatabase::escape($round->project_waiting_state),
            DPDatabase::escape($name),
            $seconds_ago
        );
        $result = DPDatabase::query($sql);
        $projects_released = mysqli_fetch_row($result)[0];

        $sql = sprintf(
            "
            SELECT sum(n_pages)
            FROM projects
            WHERE projectid in (
                SELECT projectid
                FROM project_events
                WHERE event_type = 'transition'
                    AND details1 = '%s'
                    AND details3 = 'via_q: %s'
                    AND timestamp >= %d
            )
            ",
            DPDatabase::escape($round->project_waiting_state),
            DPDatabase::escape($name),
            $seconds_ago
        );
        $result = DPDatabase::query($sql);
        $pages_released = mysqli_fetch_row($result)[0] ?? 0;

        $data[] = [
            "days_ago" => $days_ago,
            "projects_released" => $projects_released,
            "pages_released" => $pages_released,
        ];
    }
    return $data;
}
