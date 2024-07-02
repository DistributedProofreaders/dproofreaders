<?php

// Timing requirements: This script should run once a day shortly after midnight

// Record current project state counts
class RecordProjectStateCounts extends BackgroundJob
{
    public function work()
    {
        $date_string = date('Y-m-d');
        $sql = sprintf(
            "
            SELECT count(*) AS count
            FROM project_state_stats
            WHERE date = '%s'
            ",
            DPDatabase::escape($date_string),
        );
        $res = DPDatabase::query($sql);
        $row = mysqli_fetch_assoc($res);
        if ($row["count"]) {
            throw new RuntimeException("Already run once for today ($date_string)");
        }

        // The SELECT we do below will only return counts for project-states that are
        // currently occupied. (I.e., there's at least one project in that state.)
        // But we want project_state_stats to get a row for every possible state for
        // every day, so we must do something extra to record zero for the unoccupied
        // states.

        // Initialize $num_projects_in_state_ and $num_pages_in_state_
        // to zero for every currently-defined state.
        $num_projects_in_state_ = [];
        $num_pages_in_state_ = [];
        foreach (ProjectStates::get_states() as $state) {
            $num_projects_in_state_[$state] = 0;
            $num_pages_in_state_[$state] = 0;
        }

        // Get the number of projects in each (currently-occupied) state.
        $sql = "
            SELECT state, count(*), sum(n_pages)
            FROM projects
            GROUP BY state
            ORDER BY state
        ";
        $result = DPDatabase::query($sql);

        while ([$state, $num_projects, $num_pages] = mysqli_fetch_row($result)) {
            $num_projects_in_state_[$state] = $num_projects;
            $num_pages_in_state_[$state] = $num_pages;
        }

        // $num_projects_in_state_ now has an entry for every defined state and for
        // every occupied state. (The occupied states should be a subset of the defined
        // states, but you never know.)
        // Insert a row into project_state_stats for each of those entries.

        foreach (array_keys($num_projects_in_state_) as $state) {
            $sql = sprintf(
                "
                INSERT INTO project_state_stats
                SET
                    date = NOW(),
                    state = '%s',
                    num_projects = %d,
                    num_pages = %d
                ",
                DPDatabase::escape($state),
                $num_projects_in_state_[$state],
                $num_pages_in_state_[$state]
            );

            DPDatabase::query($sql);
        }
    }
}
