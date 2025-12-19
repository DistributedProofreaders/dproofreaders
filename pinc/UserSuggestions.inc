<?php

/**
 * Wrapper around the Project class for suggestion attributes.
 */
class ProjectSuggestion extends Project
{
    public float $percent_done;
    public int $created;
    public float $priority;
    public string $round;

    public function __construct(array $project_array)
    {
        foreach (["percent_done", "created", "priority", "round"] as $field) {
            $this->$field = $project_array[$field];
            unset($project_array[$field]);
        }
        parent::__construct($project_array);
    }
}

class UserSuggestions
{
    private ?string $_username;
    private int $_cache_valid_duration = 60 * 60;
    private string $_cache_key = "my_suggestions:criteria";

    public function __construct(?string $username)
    {
        $this->_username = $username;
    }

    private function _get_cached_criteria(): array
    {
        // We cache this in the session table so we don't calculate it on every
        // page load. We keep it for an hour.
        if ($this->_username == User::current_username() &&
            isset($_SESSION[$this->_cache_key]) &&
            $_SESSION[$this->_cache_key]["cache_expires_at"] > time()) {
            return $_SESSION[$this->_cache_key];
        } else {
            return [];
        }
    }

    private function _set_cached_criteria(array $criteria): void
    {
        // only cache for the user looking at their own suggestions
        if ($this->_username == User::current_username()) {
            $criteria["cache_expires_at"] = time() + $this->_cache_valid_duration;
            $_SESSION[$this->_cache_key] = $criteria;
        }
    }

    private function _calculate_normalized_weights(array $criteria): array
    {
        $new_criteria = [];
        $total = array_sum(array_values($criteria));
        foreach ($criteria as $key => $val) {
            $new_criteria[$key] = $val / $total;
        }
        return $new_criteria;
    }

    public function get_criteria(bool $flush_cache = false): array
    {
        $criteria = $this->_get_cached_criteria();
        if ($criteria && !$flush_cache) {
            return $criteria;
        }

        // rounds the user can work in [ID] => $avail_state
        $states_can_work_in = [];
        foreach (get_stages_user_can_work_in($this->_username) as $stage_id => $stage) {
            // only include rounds
            if ($stage instanceof Round) {
                $states_can_work_in[$stage_id] = $stage->project_available_state;
            }
        }

        $backlog_stats = get_round_backlog_stats(Rounds::get_ids());
        arsort($backlog_stats);

        // ELR pages
        $elr_pages = get_pages_proofed_maybe_simulated($this->_username);

        // get some info about projects they've worked on in the past (cumulative)
        // Language
        $user_languages = $this->_get_user_counts_for_project_field("language");
        // reduce to just primary languages
        foreach ($user_languages as $language => $count) {
            if (stripos($language, "with") !== false) {
                @$user_languages[Project::decode_language($language)[0]] += $count;
                unset($user_languages[$language]);
            }
        }
        // if the user hasn't proofread any projects, lets assume they'll want to
        // proofread in the language the UI is in and fall back to English
        if (!$user_languages) {
            $user_languages = [
                lang_name(short_lang_code(get_user_language($this->_username))) => 5,
            ];
            $user_languages["English"] = 1;
        }

        // Genre
        $user_genres = $this->_get_user_counts_for_project_field("genre");

        // Difficulty
        $user_difficulty = $this->_get_user_counts_for_project_field("difficulty");
        unset($user_difficulty["beginner"]);

        $criteria = [
            "filters" => [
                "accessible_rounds" => array_keys($states_can_work_in),
                "accessible_states" => array_values($states_can_work_in),
                "too_familiar_genres" => array_slice(array_keys($user_genres), 0, 5),
                "elr_pages" => $elr_pages,
                "is_beginner" => $elr_pages < 100,
            ],
            "weights" => [
                "view" => [
                    "impact" => [
                        "round" => 100,
                        "created" => 80,
                        "language" => 50,
                    ],
                    "familiar" => [
                        "created" => 100,
                        "round" => 100,
                    ],
                    "style" => [
                        "genre" => 50,
                        "language" => 50,
                        "difficulty" => 50,
                        "round" => 10,
                        "created" => 1,
                    ],
                    "different" => [
                        "language" => 50,
                        "percent_done" => 20,
                    ],
                    "getting-started" => [
                        "language" => 100,
                        "difficulty" => 50,
                    ],
                ],
                "site" => [
                    "round" => $this->_calculate_normalized_weights($backlog_stats),
                ],
                "user" => [
                    "language" => $this->_calculate_normalized_weights($user_languages),
                    "genre" => $this->_calculate_normalized_weights($user_genres),
                    "difficulty" => $this->_calculate_normalized_weights($user_difficulty),
                ],
            ],
        ];

        $this->_set_cached_criteria($criteria);

        return $criteria;
    }

    public function get_suggestions(string $round_view): array
    {
        global $ELR_round;

        // common project fields needed for all SQL queries
        $project_select_fields = <<<SQL
                projectid, nameofwork, state, language, genre, difficulty,
                n_pages, n_available_pages,
                1 - (n_available_pages / n_pages) AS percent_done,
                (unix_timestamp() - modifieddate)/(24 * 60 * 60) AS days_checkedout,
                -- following two fields are used to later build a Project object
                -- to validate a user can work in it
                special_code, modifieddate
            SQL;

        $selection_criteria = $this->get_criteria();

        // assign by value so we can alter with the new variable name
        $site_weights = &$selection_criteria["weights"]["site"];
        $user_weights = &$selection_criteria["weights"]["user"];
        $view_weights = &$selection_criteria["weights"]["view"][$round_view];

        // only pull projects in states that the user can access right now
        if ($selection_criteria["filters"]["accessible_states"]) {
            $avail_state_clause = sprintf(
                "state in (%s)",
                surround_and_join($selection_criteria["filters"]["accessible_states"], "'", "'", ',')
            );
        } else {
            // user is not approved to work in any state
            return [[], ""];
        }

        // weight beginner-level projects first if the user is a beginner
        if ($selection_criteria["filters"]["is_beginner"]) {
            $user_weights["difficulty"] = [
                "beginner" => 0.5,
                "easy" => 0.2,
            ];
            $beginner_clause = "1";
        } else {
            // if the user isn't a beginner, exclude all beginner projects
            $beginner_clause = sprintf("difficulty <> 'beginner'");
        }

        // get a sorted list of the rounds a user can work in sorted sorted by
        // which ones have the most work in them (eg backlog)
        $weighted_accessible_backlog = array_values(
            array_intersect(
                array_keys($site_weights["round"]),
                $selection_criteria["filters"]["accessible_rounds"]
            )
        );

        if ($round_view == "impact") {
            $explain_why = sprintf(
                _("Here are some of the oldest projects in rounds you can work in that have the largest backlogs, primarily %s. They've been weighted by your most commonly proofread language: %s. Working on any project in the list will help reduce the biggest backlogs and finish the oldest projects."),
                $weighted_accessible_backlog[0],
                array_get_first($user_weights["language"], "key")
            );

            $sql = "
                SELECT
                    $project_select_fields
                FROM projects
                WHERE $avail_state_clause
                    AND $beginner_clause
            ";
        } elseif ($round_view == "familiar") {
            $explain_why = sprintf(
                _("Here are some projects you've worked in before, weighted by the rounds with the largest backlogs, primarily %s."),
                $weighted_accessible_backlog[0]
            );

            $sql = sprintf(
                "
                SELECT
                    user_project_info.projectid,
                    $project_select_fields
                FROM user_project_info LEFT OUTER JOIN projects USING (projectid)
                WHERE
                    user_project_info.t_latest_page_event > 0
                    AND user_project_info.username='%s'
                    AND $avail_state_clause
                    AND $beginner_clause
                ",
                DPDatabase::escape($this->_username)
            );

        } elseif ($round_view == "style") {
            // if we don't have any genre data, bail
            if (!$user_weights["genre"]) {
                return [[], ""];
            }

            $explain_why = sprintf(
                _("Here are a selection of projects primarily in <i>%s</i> weighted by your most common genre <i>%s</i> and difficulty <i>%s</i> followed by the rounds with the largest backlogs."),
                array_get_first($user_weights["language"], "key"),
                array_get_first($user_weights["genre"], "key"),
                array_get_first($user_weights["difficulty"], "key")
            );

            $sql = "
                SELECT
                    $project_select_fields
                FROM projects
                WHERE $avail_state_clause
                    AND $beginner_clause
            ";
        } elseif ($round_view == "different") {
            $explain_why = sprintf(
                _("Here are projects in %s, your most proofread language, that you've never worked on before."),
                array_get_first($user_weights["language"], "key")
            );

            if ($selection_criteria["filters"]["too_familiar_genres"]) {
                $genre_not_clause = sprintf(
                    "genre not in (%s)",
                    surround_and_join($selection_criteria["filters"]["too_familiar_genres"], "'", "'", ',')
                );

                $explain_why .= " " . sprintf(
                    _("We've filtered out those with genres that you proofread in most often: %s."),
                    join(", ", $selection_criteria["filters"]["too_familiar_genres"])
                );
            } else {
                $genre_not_clause = "1";
            }

            $sql = sprintf(
                "
                SELECT
                    $project_select_fields
                FROM projects
                WHERE $avail_state_clause
                    AND projectid NOT IN (
                        SELECT projectid
                        FROM user_project_info
                        WHERE
                            t_latest_page_event > 0
                            AND username='%s'
                        )
                    AND $genre_not_clause
                    AND $beginner_clause
                ",
                DPDatabase::escape($this->_username)
            );
        } elseif ($round_view == "getting-started") {
            $explain_why = _("The following projects are good ones to get started in. Click one to open up the project page, read the project comments, and then click 'Start Proofreading'.");

            $sql = sprintf(
                "
                SELECT
                    $project_select_fields
                FROM projects
                WHERE
                    difficulty in ('beginner', 'easy', 'average')
                    AND state = '%s'
                ",
                $ELR_round->project_available_state,
            );
        }

        // for each project returned from the query, generate a priority field
        // calculated by the view's weights paired with that field's weights from
        // the selection criteria. The priority field is used for sorting the rows
        $now = time();
        $result = DPDatabase::query($sql);
        $projects = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $row["created"] = hexdec(substr($row["projectid"], 9, 8));
            $row["round"] = Rounds::get_by_project_state($row["state"])->id;

            $priority = 1;
            foreach ($view_weights as $field => $weight) {
                // dynamic fields calculated from project
                if ($field == "created") {
                    $field_weight = ($now - $row["created"]) / $now;
                } elseif ($field == "percent_done") {
                    $field_weight = 1 - $row["percent_done"];
                } else {
                    $normalized_field_value = $row[$field];
                    // only use the primary language for the project when weighting
                    if ($field == "language") {
                        $normalized_field_value = Project::decode_language($row[$field])[0];
                    }

                    // Default field weight needs to be non-zero
                    $field_weight = 0.01;

                    // get the field weight from the user weights if one exists,
                    // otherwise try the site weights
                    if (isset($user_weights[$field][$normalized_field_value])) {
                        $field_weight = $user_weights[$field][$normalized_field_value];
                    } elseif (isset($site_weights[$field][$normalized_field_value])) {
                        $field_weight = $site_weights[$field][$normalized_field_value];
                    }
                }
                $priority *= $weight * $field_weight;
            }
            $row["priority"] = $priority;

            $projects[] = $row;
        }
        mysqli_free_result($result);

        uasort($projects, "compare_array_by_priority");

        $user = new User($this->_username);
        $return_projects = [];
        // loop through the sorted rows checking each one against the ability
        // for the proofreader to work on it *right now* based on the reserve, etc.
        foreach (array_reverse($projects) as $project_row) {
            // only return 20 projects
            if (count($return_projects) >= 20) {
                break;
            }

            // Build the project from the row so we don't have to go back to
            // the database again for every project. This requires all the necessary
            // fields used here, and in dependent functions, be populated in the row.
            $project = new ProjectSuggestion($project_row);

            try {
                $round = Rounds::get_by_project_state($project->state);
                validate_user_against_project_reserve($user, $project, $round);
                $return_projects[] = $project_row;
            } catch (Exception $exception) {
                continue;
            }
        }
        return [$return_projects, $explain_why];
    }

    private function _get_user_counts_for_project_field(string $field, int $limit = 8): array
    {
        // $field *must* be a valid field in the projects table!
        $sql = sprintf(
            "
            SELECT
                COUNT($field) as count,
                $field
            FROM user_project_info LEFT OUTER JOIN projects USING (projectid)
            WHERE
                user_project_info.username='%s'
                AND user_project_info.t_latest_page_event > 0
            GROUP BY $field
            ORDER BY count DESC
            LIMIT %d
            ",
            $this->_username,
            $limit
        );
        $field_counts = [];
        $result = DPDatabase::query($sql);
        while ([$count, $value] = mysqli_fetch_row($result)) {
            $field_counts[$value] = $count;
        }
        mysqli_free_result($result);
        return $field_counts;
    }
}

function compare_array_by_priority(array $a, array $b): int
{
    if ($a["priority"] == $b["priority"]) {
        return 0;
    }

    return ($a["priority"] < $b["priority"]) ? -1 : 1;
}
