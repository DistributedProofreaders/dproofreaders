<?php

// $Id$

include_once($relPath.'Stage.inc');

// -----------------------------------------------------------------------------

global $n_rounds, $Round_for_round_id_, $Round_for_round_number_;
global $Round_for_page_state_, $PAGE_STATES_IN_ORDER;

$n_rounds = 0;
$Round_for_round_id_ = [];
$Round_for_round_number_ = [];
$Round_for_page_state_ = [];
$PAGE_STATES_IN_ORDER = [];

// A container for various constants relating to a particular round of proofreading.
class Round extends Stage
{
    /**
     * Round constructor
     *
     * @param array $pi_tools
     *   A list of which tools should be available in the proofreading
     *   interface's toolbox when proofreading a page in this round.
     *   The format is:
     *   ```
     *   [
     *       'popup_links' => [ 'link1', 'link2', ... ],
     *       'tool_buttons' => [ 'button1', 'button2', ... ],
     *       'tool_links' => [ 'link1', 'link2', ... ],
     *   ]
     *   ```
     *   Alternatively, if you want all tools to be visible, use 'ALL'
     *   for the array instead:
     *   ```
     *   [
     *       'popup_links' => 'ALL',
     *       'tool_buttons' => 'ALL',
     *       'tool_links' => 'ALL',
     *   ]
     *   ```
     *   See `pinc/ProofreadingToolbox.inc` for which tools are available
     *   and their names.
     * @param $daily_page_limit
     *   This is either NULL (indicating no daily page limit for this round)
     *   or a non-negative (though likely positive) integer.
     * @param array $other_rounds_with_visible_usernames
     *   An array of round_ids.
     *   If user X worked on a page in this round, they can see the
     *   username of another user Y who worked on the page *if* user Y
     *   worked on the page in a round that appears in this parameter.
     * @param array $honorifics
     *   An array of integer => string items that determine a user's
     *   "title" on the basis of their page tally in this round.
     *   In each item:
     *   - The number is a page-tally threshold.
     *   - The string is the honorific for someone who has achieved that
     *     threshold, but not the next higher.
     *   (Needn't be in a particular order.)
     */
    public function __construct(
        $round_number,
        $id,
        $name,
        $access_minima,
        $after_satisfying_minima,
        $evaluation_criteria,
        $access_change_callback,
        $description,
        $document,
        $pi_tools,
        $daily_page_limit,
        $other_rounds_with_visible_usernames,
        $honorifics
    ) {
        $round_id = $id;

        parent::__construct(
            $id,
            $name,
            $access_minima,
            $after_satisfying_minima,
            $evaluation_criteria,
            $access_change_callback,
            $description,
            $document,
            "tools/proofers/round.php?round_id=$round_id"
        );

        $this->round_number = $round_number;
        $this->pi_tools = $pi_tools;
        $this->daily_page_limit = $daily_page_limit;
        $this->other_rounds_with_visible_usernames = $other_rounds_with_visible_usernames;
        $this->honorifics = $honorifics;
        krsort($this->honorifics);

        $this->time_column_name = "round{$this->round_number}_time";
        $this->text_column_name = "round{$this->round_number}_text";
        $this->user_column_name = "round{$this->round_number}_user";

        // prevtext_column_name
        //
        // This used to be just a simple column name
        // (the name of the 'text' column of the immediately previous round).
        //
        // But now that a project can skip rounds,
        // the previous version of the text may be further back than that,
        // so prevtext_column_name is now an SQL expression
        // identifying the appropriate text column.
        //
        // Here's the pattern in pseudocode:
        //   r1: master_text
        //   r2: if r1_user != '' then r1_text else master_text
        //   r3: if r2_user != '' then r2_text else if r1_user != '' then r1_text else master_text
        //   etc.
        //
        if ($this->round_number == 1) {
            $this->prevtext_column_name = "master_text";
        } else {
            $sql = "CASE";
            for ($rn = $this->round_number - 1; $rn > 0; $rn--) {
                $sql .= " WHEN LENGTH(round{$rn}_user) THEN round{$rn}_text";
            }
            $sql .= " ELSE master_text";
            $sql .= " END";
            $this->prevtext_column_name = $sql;
        }

        // These can be changed by calling declare_mentoring_pair():
        $this->mentor_round = null;
        $this->mentee_round = null;
    }

    public function get_access_criteria()
    {
        return [$this->id => sprintf(_("'%s' pages completed"), $this->id)];
    }

    public function is_a_mentee_round()
    {
        return !is_null($this->mentor_round);
    }
    public function is_a_mentor_round()
    {
        return !is_null($this->mentee_round);
    }

    // -----------

    public function has_a_daily_page_limit()
    {
        return !is_null($this->daily_page_limit);
    }

    // -----------

    public function get_honorific_for_page_tally($page_tally)
    {
        // Note that krsort($this->honorifics) put it in descending order.
        foreach ($this->honorifics as $threshold => $honorific) {
            if ($page_tally >= $threshold) {
                return $honorific;
            }
        }
        // $page_tally is less than the least threshold.
        // Unusual, since the least threshold is usually zero,
        // but it can happen.
        // Give them the lowest honorific.
        return $honorific;
    }

    // -----------

    public function validate_user_can_access($user)
    {
        global $code_url;

        $uao = $this->user_access($user);
        if (!$uao->can_access) {
            $message = sprintf(
                // TRANSLATORS: %1$s is the round name, %2$s is the round ID (eg: P1)
                _('You have not yet been cleared to work on projects in %1$s (%2$s).'),
                $this->name,
                $this->id,
            );
            throw new UserNotQualifiedForRoundException($message);
        }
    }
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

/**
 * Return an SQL snippet used to sort rounds
 *
 * In an SQL query, if you `ORDER BY round_id`, it will use alphabetical order,
 * which is not very useful. Instead, ORDER BY the result of this function,
 * and it will use the canonical order-of-declaration for rounds.
 */
function sql_collater_for_round_id($round_id_column)
{
    global $Round_for_round_id_;
    return sprintf(
        "FIELD($round_id_column, %s)",
        surround_and_join(array_keys($Round_for_round_id_), "'", "'", ",")
    );
}

/**
 * Return an SQL snippet used to page states
 *
 * In an SQL query, if you `ORDER BY state`, it will use alphabetical order,
 * which is not very useful. Instead, ORDER BY the result of this function,
 * and it will use the canonical order-of-declaration for page states.
 */
function sql_collater_for_page_state($state_column)
{
    global $PAGE_STATES_IN_ORDER;
    return sprintf(
        "FIELD($state_column, %s)",
        surround_and_join($PAGE_STATES_IN_ORDER, "'", "'", ",")
    );
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

/**
 * Define pairs of rounds used for mentoring
 *
 * Asserts that, for difficulty='beginner' projects, pages done in one round
 * (the "mentored" or "mentee" round) will garner feedback by qualified users
 * ("mentors") in a subsequent round (the "mentoring" or "mentor" round).
 */
function declare_mentoring_pair($mentee_round_id, $mentor_round_id)
{
    $mentee_round = get_Stage_for_id($mentee_round_id);
    $mentor_round = get_Stage_for_id($mentor_round_id);
    assert(is_a($mentee_round, 'Round'));
    assert(is_a($mentor_round, 'Round'));

    // Currently, we don't allow:
    // -- a round to be mentored by more than one round, or
    // -- a round to mentor more than one round.
    assert(is_null($mentee_round->mentor_round));
    assert(is_null($mentor_round->mentee_round));

    $mentee_round->mentor_round = $mentor_round;
    $mentor_round->mentee_round = $mentee_round;
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

/**
 * Check if the given user is allowed to get a new page
 * of the given project in the given round
 * (via "Start Proofreading" or "Save and Do Another"),
 * otherwise, throw an exception explaining why not.
 *
 * You can assume that this function will be called only if:
 * - the current user has general access to the round, and
 * - the project is available in the round,
 * so this function doesn't have to check for either of those.
 *
 * It also doesn't need to check whether any of the project's pages
 * are actually available.
 */
function validate_user_can_get_pages_in_project($user, $project, $round)
{
    // Projects with difficulty='beginner' are treated differently in
    // various ways.
    if ($project->difficulty == 'beginner') {
        if ($round->is_a_mentee_round()) {
            if (!that_user_can_work_on_beginner_pages_in_round($user->username, $round)) {
                throw new BeginnersQuotaReachedException(_("You have reached your quota of pages from 'Beginners Only' projects in this round.
                    Perhaps you could try working on an EASY project."));
            }

            if (that_user_is_a_sitemanager($user->username) ||
                that_user_is_proj_facilitator($user->username)) {
                // User is exempt from N-pages-per-beginner-project restriction.
            } else {
                // When a beginner project is in a mentored round,
                // any given user can work on at most N pages
                // from that project in that round.

                // (For a while, N was 11 for all beginner projects.
                // Then we reduced it to 6 for English beginner projects.)
                $max_n_pages_per_project = ($project->language == 'English' ? 6 : 11);

                $sql = sprintf(
                    "
                    SELECT COUNT(*) as pagesdone
                    FROM {$project->projectid}
                    WHERE {$round->user_column_name} = '%s'
                    ",
                    DPDatabase::escape($user->username)
                );
                $result = DPDatabase::query($sql);
                $row = mysqli_fetch_assoc($result);
                if ($row["pagesdone"] >= $max_n_pages_per_project) {
                    throw new BeginnersProjectQuotaReachedException(_("You have reached your quota of pages from this 'Beginners Only' project,
                        but you're welcome to work on a different one. Or you could try working on an EASY project."));
                }
            }
        } elseif ($round->is_a_mentor_round()) {
            if (!that_user_can_work_on_beginner_pages_in_round($user->username, $round)) {
                throw new NoAccessToMentorsOnlyException(_("You do not have access to difficulty='beginner' (Mentors Only) projects in this round."));
            }
        } else {
            // In other rounds, difficulty='beginner' projects aren't handled specially.
        }
    }

    validate_user_against_project_reserve($user, $project, $round);

    // None of the above restrictions apply.
}

function validate_user_against_project_reserve($user, $project, $round)
{
    // Allow projects to be reserved for newcomers based on
    // criteria defined in get_reserve_length().
    $n_days_of_reserve = get_reserve_length($project, $round);
    if ($n_days_of_reserve > 0) {
        $page_tally_threshold = 500;
        $days_on_site_threshold = 21;

        $t_release = $project->modifieddate;
        $t_generally_available =
            $t_release + $n_days_of_reserve * 24 * 60 * 60;
        $t_now = time();

        // echo "t_release = $t_release<br>\n";
        // echo "t_generally_available = $t_generally_available<br>\n";
        // echo "t_now = $t_now<br>\n";
        if ($t_now < $t_generally_available) {
            // The project is still in its "reserved" period.
            // It is available only to inexperienced users plus a few others.

            if ($project->can_be_managed_by_user($user->username)) {
                // The current user is an SA, PF, or the project's PM,
                // and is not subject to this restriction.
            } else {
                // Is this user an inexperienced user?

                $days_on_site = ($t_now - $user->date_created) / 86400;
                // echo "days_on_site = $days_on_site<br>\n";

                $ELR_page_tally = user_get_ELR_page_tally($user->username);
                // echo "ELR_page_tally = $ELR_page_tally<br>\n";

                if ($days_on_site < $days_on_site_threshold or $ELR_page_tally < $page_tally_threshold) {
                    // Yes, inexperienced.
                    // So not subject to this restriction
                    // (and so, allowed to proofread, unless there's some other problem).
                } else {
                    // No, experienced.
                    // So cannot proofread this project yet.
                    $message = sprintf(
                        _('This project is currently reserved for proofreaders who joined less than %1$d days ago or have done less than %2$s %3$s pages. It will become generally available %4$s.'),
                        $days_on_site_threshold,
                        $page_tally_threshold,
                        $round->id,
                        icu_date_template("long+time", $t_generally_available)
                    );
                    throw new ReservedForNewProofreadersException($message);
                }
            }
        } else {
            // The project has finished its "reserved" period.
            // It is available to all (subject to other restrictions).
        }
    }

    // None of the above restrictions apply.
}

/**
 * Should $project be temporarily reserved for inexperienced users
 * after becoming available in $round?
 *
 * If so, return the length of the reserve in days.
 * If not, return 0.
 */
function get_reserve_length($project, $round)
{
    // We only reserve-for-newbies in P1.
    if ($round->id != 'P1') {
        return 0;
    }

    // Missing page(s) projects are exempted from being reserved
    // because we want them to catch up to their "parent" project
    // as quickly as possible.
    if (str_contains($project->nameofwork, '[missing page')) {
        return 0;
    }

    // *->P1 projects are exempted, because they were already reserved
    // for inexperienced users on their first pass through P1.
    if (str_contains($project->nameofwork, '->P1}')) {
        return 0;
    }

    // If it is deemed appropriate to add a reserve for languages
    // other than English, this is the block of code to customize.
    // If the reserve will be the same as the English reserve, just
    // add "|| $project->language == '<language>'" to the end of the
    // condition. Otherwise, a most complex block will be needed.
    if ($project->language == 'English') {
        // All 'special day' projects are exempted.
        if ($project->special_code != '') {
            return 0;
        }

        // Otherwise, reserve for 7 days.
        return 7;
    }

    // Anything not covered above
    // (i.e., language other than 'English')
    // is not reserved.
    return 0;
}

// -------------------------------------------------------------------

/**
 * Return an SQL blurb that can be used to sort projects in a round.
 *
 * On each round page, the order of the project list can be adjusted by
 * clicking on column headers. If you want certain criteria to take precedence
 * over such sorts, have this function return a string expressing those criteria
 * as one or more ordering specifications (such as would appear in an ORDER BY
 * clause in SQL), referring to columns of the projects table.
 *
 * For instance, to have beginner projects always appear at the top of
 * every round's project list, this function could return one of:
 * - `"(difficulty = 'beginner') DESC"`
 * - `"IF( difficulty = 'beginner', 1, 2 )"`
 * - `"CASE difficulty WHEN 'beginner' THEN 1 ELSE 2 END"`
 *
 * If you don't want any criteria to take precedence over column-sorts,
 * return NULL or the empty string.
 */
function round_project_listing_presort($round)
{
    if (is_proofreading_round($round)) {
        return "
            (difficulty = 'beginner') DESC,
            (nameofwork LIKE '%Newcomers Only%' OR nameofwork LIKE '%Rapid Review%') DESC
        ";
    } elseif (is_formatting_round($round)) {
        return "
            (
                nameofwork LIKE '%Fast Formatting Feedback%' OR
                nameofwork LIKE '%F1 Apprentice%' OR
                nameofwork LIKE '%F2 Qual%'
            ) DESC
        ";
    } else {
        assert(false);
    }
}

function is_proofreading_round($round)
{
    return startswith($round->id, 'P');
}

function is_formatting_round($round)
{
    return startswith($round->id, 'F');
}

/**
 * From an array & key, get a Round object matching the ID
 *
 * @param array $arr
 * @param string $key
 * @param mixed $default
 * @param bool $allownull
 *
 * @throws InvalidArgumentException
 */
function get_round_param($arr, $key, $default = null, $allownull = false)
{
    global $Round_for_round_id_;
    // sanity checks on the args
    assert(is_array($arr));
    assert(!is_array($key));
    assert(is_null($default) || is_string($default));
    if (!is_null($default)) {
        assert(!$allownull);
    }
    if (isset($arr[$key])) {
        $s = $arr[$key];
        if (!is_string($s)) {
            throw new InvalidArgumentException(sprintf(
                _("Parameter '%1\$s' is not a valid type"),
                $key
            ));
        }
        // Trim whitespace from both ends of the string.
        $s = trim($s);
        if ($s == '' && $allownull) {
            return null;
        }

        $round = $Round_for_round_id_[$s] ?? null;
        if ($round) {
            return $round;
        } else {
            throw new InvalidArgumentException(sprintf(
                _("Parameter '%1\$s' ('%2\$s') is not a valid round ID"),
                $key,
                $s
            ));
        }
    } else {
        // parameter not set, use default
        if (is_null($default) && !$allownull) {
            // There is no default. The parameter is required.
            throw new InvalidArgumentException(sprintf(
                _("Parameter '%1\$s' is required"),
                $key
            ));
        } else {
            return $default;
        }
    }
}
