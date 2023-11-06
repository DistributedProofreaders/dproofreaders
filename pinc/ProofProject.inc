<?php
declare(strict_types=1);

const MAX_REQUESTED_PAGES = 10;

trait ProofPage
{
    private function render_page_data(): array
    {
        $round_info_array = [];
        foreach ($this->round->other_rounds_with_visible_usernames as $other_round_id) {
            $other_round = get_Round_for_round_id($other_round_id);
            $username = $this->get_username_for_round($other_round);
            $round_info_array[] = [$other_round->id, $username];
        }
        $page_option = ($this->page_state == $this->round->page_out_state) ? "previous" : "current";
        return
            $this->get_page_text_array($page_option) +
            $this->get_page_state_array() +
            [
                "pageNum" => pathinfo($this->page_name, PATHINFO_FILENAME),
                "roundInfoArray" => $round_info_array,
                "pagename" => $this->page_name,
                "image_url" => "{$this->project->url}/{$this->page_name}",
            ];
    }

    private function get_username_for_round($round)
    {
        $res = DPDatabase::query("
            SELECT {$round->user_column_name}
            FROM {$this->projectid}
            WHERE image = '{$this->page_name}'
        ");
        $row = mysqli_fetch_assoc($res);
        return $row[$round->user_column_name];
    }

    private function get_page_text_array(string $page_option): array
    {
        if ($page_option == "current") {
            $desired_column_name = $this->round->text_column_name;
        } else {
            $desired_column_name = $this->round->prevtext_column_name;
        }

        $sql = "
            SELECT $desired_column_name
            FROM $this->projectid
            WHERE image='$this->page_name'
        ";
        $res = DPDatabase::query($sql);
        $text = mysqli_fetch_row($res)[0];

        return ["text" => $text];
    }

    private function get_page_state_array()
    {
        return [
            "pagestate" => $this->page_state,
            "saved" => ($this->page_state == $this->round->page_temp_state),
        ];
    }

    private function _return_to_round(): void
    {
        global $pguser;

        $setters = join(", ", [
            set_col_str("state", $this->round->page_avail_state),
            set_col_num($this->round->time_column_name, time()),
        ]);
        _Page_UPDATE($this->projectid, $this->page_name, $setters);
        _log_page_event($this->projectid, $this->page_name, 'returnToRound', $pguser, $this->round);
        _project_adjust_n_available_pages($this->projectid, +1);
    }
}

class ProofProject
{
    use ProofPage;

    public function __construct(object $project, array $query_params = [])
    {
        global $pguser, $PROJECT_STATES_IN_ORDER;

        $expected_state = $query_params["projstate"] ?? null;
        if (null === $expected_state) {
            throw new ProjectNoStateGivenException("No expected state found in request.");
        }
        if (!in_array($expected_state, $PROJECT_STATES_IN_ORDER)) {
            throw new ProjectInvalidStateException(sprintf("%s is not a valid project state", $expected_state));
        }
        if ($expected_state != $project->state) {
            $err = sprintf(
                _('Warning: Project "%1$s" is not in state "%2$s"; it is now in state "%3$s".'),
                $project->nameofwork,
                project_states_text($expected_state),
                project_states_text($project->state)
            );
            throw new ProjectNotInRequiredStateException($err);
        }

        $project_round = $project->get_project_available_round();
        $project_round->validate_user_can_access($pguser);

        $this->project = $project;
        $this->projectid = $project->projectid;
        $this->round = $project_round;
    }

    public function checkout_pages(int $requested_pages): array
    {
        global $pguser;
        if ($requested_pages < 1) {
            throw new InvalidValue("requested pages must be at least 1");
        }
        if ($requested_pages > MAX_REQUESTED_PAGES) {
            throw new ProjectTooManyPagesRequested(sprintf("requested pages must not be more than %d", MAX_REQUESTED_PAGES));
        }
        // get some pages. If not enough available return a reason
        $reason = "";
        $status_code = 0;
        $this->validate_no_outpages();
        $out_pages = 0;
        validate_user_can_get_pages_in_project($pguser, $this->project, $this->round);
        while ($out_pages < $requested_pages) {
            try {
                $this->checkout_available_proof_page();
                $out_pages += 1;
            } catch (ProjectNoMorePagesException | BeginnersQuotaReachedException | BeginnersProjectQuotaReachedException $exception) {
                if ($out_pages == 0) {
                    throw $exception;
                }
                $reason = $exception->getMessage();
                $status_code = $exception->getCode();
                break;
            }
        }

        // find the pages we have checked out and log page events
        $this->log_checkout_events();

        return [
            "reason" => $reason,
            "status" => $status_code,
            "npages" => $out_pages,
        ];
    }

    private function checkout_available_proof_page(): void
    {
        global $preceding_proofer_restriction;

        // If the global setting is to not serve the immediately preceding page,
        // first see if we can serve them a page they haven't at all seen before,
        // then fall back to not the immediately preceding page.
        if ($preceding_proofer_restriction == 'not_immediately_preceding') {
            $pages = $this->get_available_restricted_page('not_previously_proofed');
        }

        // If that failed, try again with the global setting. This is also the
        // common case if $preceding_proofer_restriction != not_immediately_preceding
        if (0 == $pages) {
            $pages = $this->get_available_restricted_page($preceding_proofer_restriction);
        }
        if (0 == $pages) {
            throw new ProjectNoMorePagesException();
        }
    }

    private function get_available_restricted_page(string $preceding_proofer_restriction): int
    // Returns the best page entry for the user given the specified preceding
    // project restriction. If successful, it returns [ $imagename, $state ],
    // on failure it returns NULL;
    {
        global $pguser;

        // The page to be retrieved must be an available page, of course.
        $restrictions = "state='{$this->round->page_avail_state}'";

        // Are there any other restrictions that the page must satisfy?
        // (This should maybe be a property of $round.)
        if (empty($preceding_proofer_restriction)) {
            // Nope, no other restrictions.
        } elseif ($preceding_proofer_restriction == 'not_immediately_preceding') {
            // Don't give this user a page that they worked on in the preceding round.
            // (Where "preceding" takes into account skipped rounds.)
            if ($this->round->round_number > 1) {
                // We need an SQL expression for the preceding proofreader.
                $preceding_proofer = "CASE";
                for ($rn = $this->round->round_number - 1; $rn > 0; $rn--) {
                    $earlier_round = get_Round_for_round_number($rn);
                    $ucn = $earlier_round->user_column_name;
                    $preceding_proofer .= " WHEN LENGTH($ucn) THEN $ucn";
                }
                // What if all of the earlier rounds were skipped?
                // (It's pretty unlikely, but we should allow for it.)
                // All of the WHEN LENGTH(...) clauses will fail.
                //
                // If the CASE expr doesn't have an ELSE clause, it will yield NULL,
                // so we'll end up requiring that
                //     NULL != '$pguser'
                // which is always NULL (effectively false), so no pages will
                // satisfy the restriction, which is not what we want.
                //
                // Instead, add an ELSE clause saying that the preceding proofreader's
                // name was the empty string. So we'll end up requiring that
                //     '' != '$pguser'
                // which is always true, so any (available) page will satisfy the
                // restriction, which is what we want.
                $preceding_proofer .= " ELSE ''";
                $preceding_proofer .= " END";

                $restrictions .= " AND $preceding_proofer != '$pguser'";
            }
        } elseif ($preceding_proofer_restriction == 'not_previously_proofed') {
            // Don't give this user a page that they have worked on before.
            for ($rn = 1; $rn < $this->round->round_number; $rn++) {
                $earlier_round = get_Round_for_round_number($rn);
                $restrictions .= " AND {$earlier_round->user_column_name} != '$pguser'";
            }
        } else {
            // The empty/null (i.e., no restriction) case has already been dealt with.
            throw new ValueError("Bad value for 'preceding_proofer_restriction': '$preceding_proofer_restriction'.");
        }

        // Update a page
        return $this->checkout_block($restrictions, 1);
    }

    public function mentor_checkout(): void
    {
        global $pguser;

        $this->validate_no_outpages();
        if(!$this->mentor_mode()) {
            throw new BadRequest("This is not a Mentor Project");
        }
        // in available projects
        // find proofreader in previous round with earliest time stamp
        $mentee_column = $this->round->mentee_round->user_column_name;
        $mentee_time_column = $this->round->mentee_round->time_column_name;
        $dbQuery = "
            SELECT $mentee_column, $mentee_time_column
            FROM $this->projectid
            WHERE state='{$this->round->page_avail_state}'
            ORDER BY $mentee_time_column ASC
            LIMIT 1
        ";
        $result = DPDatabase::query($dbQuery);
        $row = $result->fetch_assoc();
        if(!$row) {
            throw new ProjectNoMorePagesException();
        }
        $mentee = $row[$mentee_column];
        // checkout all mentee's pages
        $restrictions = "state='{$this->round->page_avail_state}' AND $mentee_column='$mentee'";
        if(0 == $this->checkout_block($restrictions, 9999)) {
            // can only happen if another mentor has intervened
            throw new ProjectNoMorePagesException();
        }
        $this->log_checkout_events();
    }

    private function checkout_block(string $restrictions, int $limit): int
    {
        global $pguser;

        $settings = sprintf(
            "
            state = '{$this->round->page_out_state}',
            {$this->round->time_column_name} = UNIX_TIMESTAMP(),
            {$this->round->user_column_name} = '%s'
        ",
            DPDatabase::escape($pguser)
        );

        $dbQuery = "
            UPDATE $this->projectid
            SET $settings
            WHERE $restrictions
            ORDER BY image ASC
            LIMIT $limit
        ";
        DPDatabase::query($dbQuery);
        // return the number of rows checked out
        return DPDatabase::affected_rows();
    }

    public function return_pages(): void
    {
        $page_array = $this->gather_pages();
        foreach ($page_array as $page_name) {
            $this->page_name = $page_name;
            $this->_return_to_round();
        }
    }

    public function get_outpage(): array
    {
        $page_array = $this->gather_pages();
        if (0 == count($page_array)) {
            throw new ProjectNoMoreOutPagesException(_("You have no more pages checked out."));
        }
        $this->page_name = $page_array[0];
        $this->page_state = $this->round->page_out_state;

        return $this->render_page_data();
    }

    public function get_data(): array
    {
        return [
            "outpages" => count($this->gather_pages()),
            "mentormode" => $this->mentor_mode(),
        ];
    }

    private function mentor_mode(): bool
    {
        return ($this->project->difficulty == 'beginner') && $this->round->is_a_mentor_round();
    }

    private function log_checkout_events(): void
    {
        global $pguser;

        // find the pages we have checked out and log page events
        $page_array = $this->gather_pages();
        _project_adjust_n_available_pages($this->projectid, -count($page_array));
        foreach ($page_array as $page_name) {
            _log_page_event($this->projectid, $page_name, 'checkout', $pguser, $this->round);
        }
    }

    private function validate_no_outpages(): void
    {
        $out_pages = count($this->gather_pages());
        if ($out_pages > 0) {
            throw new ProjectThereAreOutPagesException(sprintf(_("You already have %d pages checked out"), $out_pages));
        }
    }

    private function gather_pages(): array
    {
        global $pguser;

        $page_array = [];
        $sql = sprintf(
            "
            SELECT image
            FROM $this->projectid
            WHERE state = '{$this->round->page_out_state}'
            AND {$this->round->user_column_name} = '%s'
            ORDER BY image ASC
        ",
            DPDatabase::escape($pguser)
        );
        $result = DPDatabase::query($sql);
        while ([$page_name] = mysqli_fetch_row($result)) {
            $page_array[] = $page_name;
        }
        return $page_array;
    }
}

class ProofProjectPage extends ProofProject
{
    use ProofPage;

    public function __construct(object $project, string $page_name, array $query_params = [])
    {
        global $pguser, $PAGE_STATES_IN_ORDER;

        parent::__construct($project, $query_params);

        $expected_page_state = $query_params['pagestate'] ?? null;
        if (null === $expected_page_state) {
            throw new ProjectNoPageStateGivenException("No expected page state found in request.");
        }
        if (!in_array($expected_page_state, $PAGE_STATES_IN_ORDER)) {
            throw new ProjectInvalidPageStateException(sprintf("%s is not a valid page state", $expected_page_state));
        }

        $user_column = $this->round->user_column_name;
        $sql = "
            SELECT state, $user_column
            FROM $this->projectid
            WHERE image = '$page_name'
        ";
        $res = DPDatabase::query($sql);
        $row = mysqli_fetch_assoc($res);

        $current_page_state = $row["state"];
        if ($expected_page_state != $current_page_state) {
            $err = sprintf(
                _('Warning: Page  "%1$s" is no longer in state "%2$s"; it is now in state "%3$s".'),
                $page_name,
                $expected_page_state,
                $current_page_state
            );
            throw new ProjectPageInconsistentStateException($err);
        }

        if ($row[$user_column] != $pguser) {
            throw new PageNotOwnedException(_("You do not own this page."));
        }

        $this->page_name = $page_name;
        $this->page_state = $current_page_state;
    }

    public function return_to_round(): void
    {
        global $pguser;

        $this->validate_page(false, "returnToRound");
        $this->_return_to_round();
    }

    public function attempt_save_as_done(): array
    {
        global $pguser;

        $this->validate_page(false, "saveAsDone");
        $analysis = $this->receive_text();
        if(!$analysis["good"]) {
            return $analysis;
        }
        if ($this->round->has_a_daily_page_limit()) {
            $pre_save_dpl_count = get_dpl_count_for_user_in_round($pguser, $this->round);
            if ($pre_save_dpl_count >= $this->round->daily_page_limit) {
                // The user has already reached their limit of this kind of page.
                $this->_save_as_in_progress();
                $sentence = sprintf(
                    _("Your page was saved as 'In Progress' rather than 'Done', because you have already reached the daily page limit for %s."),
                    $this->round->id
                );
                throw new DailyLimitExceededException($sentence);
            } else {
                $this->save_as_done();
                if ($pre_save_dpl_count + 1 >= $this->round->daily_page_limit) {
                    $sentence = sprintf(
                        _("Your page has been saved as 'Done'. However, you have now reached the daily page limit for %s."),
                        $this->round->id
                    );
                    throw new DailyLimitReachedException($sentence);
                }
            }
        } else {
            $this->save_as_done();
        }
        return ["good" => true];
    }

    private function save_as_done(): void
    {
        global $pguser;

        $timestamp = time();
        $setters = join(", ", [
            set_col_str("state", $this->round->page_save_state),
            set_col_num($this->round->time_column_name, $timestamp),
            set_col_str($this->round->text_column_name, $this->page_text),
        ]);
        _Page_UPDATE($this->projectid, $this->page_name, $setters);
        _log_page_event($this->projectid, $this->page_name, 'saveAsDone', $pguser, $this->round);
        upi_set_t_latest_page_event($pguser, $this->projectid, $timestamp);
        _project_set_t_last_page_done($this->projectid, $timestamp);
        page_tallies_add($this->round->id, $pguser, +1);
    }

    public function save_as_in_progress(string $option = "current"): array
    {
        $this->validate_page(false, "saveAsInProgress");
        $analysis = $this->receive_text();
        if(!$analysis["good"]) {
            return $analysis;
        }
        $this->_save_as_in_progress();
        return $this->get_page_text_array($option) + $this->get_page_state_array() + ["good" => true];
    }

    private function _save_as_in_progress(): void
    {
        global $pguser;

        $this->page_state = $this->round->page_temp_state;
        $setters = join(", ", [
            set_col_str("state", $this->page_state),
            set_col_num($this->round->time_column_name, time()),
            set_col_str($this->round->text_column_name, $this->page_text),
        ]);
        _Page_UPDATE($this->projectid, $this->page_name, $setters);
        _log_page_event($this->projectid, $this->page_name, 'saveAsInProgress', $pguser, $this->round);
        upi_set_t_latest_page_event($pguser, $this->projectid, time());
    }

    private function receive_text(): array
    {
        $request_data = api_get_request_body();
        $text = $request_data["text"] ?? null;
        if(null === $text) {
            throw new ProjectNoTextException();
        }
        $text_checker = new TextChecker();
        $result = $text_checker->analyse($text, $this->project);
        if($result["good"]) {
            $this->page_text = _normalize_page_text($text, $this->projectid);
        }
        return $result;
    }

    public function resume_page(): array
    {
        global $pguser;

        // page could be in out, temp or saved state
        $this->validate_page(true, "reopen");
        if ($this->page_state == $this->round->page_save_state) {
            // Page comes from DONE.
            // no need to update text, just record state change
            $this->page_state = $this->round->page_temp_state;
            $setters = join(", ", [
                set_col_str("state", $this->page_state),
                set_col_num($this->round->time_column_name, time()),
            ]);
            _Page_UPDATE($this->projectid, $this->page_name, $setters);
            _log_page_event($this->projectid, $this->page_name, 'reopen', $pguser, $this->round);

            // When it was saved, the user's page-count was incremented.
            // Now they are 'unsaving' it, so decrement their page-count.
            // They'll get it back if/when they save-as-done again.
            page_tallies_add($this->round->id, $pguser, -1);
        }
        // otherwise it is in temp or out state: do nothing
        return $this->render_page_data();
    }

    public function render_page_text($page_option)
    {
        // page should be in out or temp state only
        $this->validate_page(false, "getText");
        return $this->get_page_text_array($page_option);
    }

    private function validate_page(bool $can_be_in_saved_state, string $op_name): void
    {
        $allowed_states = [$this->round->page_out_state, $this->round->page_temp_state];
        if ($can_be_in_saved_state) {
            $allowed_states[] = $this->round->page_save_state;
        }
        if (!in_array($this->page_state, $allowed_states)) {
            $err = sprintf(_("The page state %1s is not allowed for this operation (%2s)."), $this->page_state, $op_name);
            throw new ProjectPageStateException($err);
        }
    }
}

class TextChecker
{
    public const GOOD_TEXT = 0;
    public const BAD_TEXT = 1;

    private $text_array = [];
    private $good_text_block = "";

    private function append_good()
    {
        if ($this->good_text_block != "") {
            $this->text_array[] = [$this->good_text_block, self::GOOD_TEXT];
            $this->good_text_block = "";
        }
    }

    public function analyse($string, $project)
    {
        $all_good = true;
        $pattern_string = build_character_regex_filter($project->get_valid_codepoints());
        foreach (split_graphemes($string) as $grapheme) {
            if (1 === preg_match("/$pattern_string/u", $grapheme)) {
                $this->good_text_block .= $grapheme;
            } else {
                $all_good = false;
                $this->append_good();
                $this->text_array[] = [$grapheme, self::BAD_TEXT];
            }
        }
        // append any remaining good text
        $this->append_good();
        return [
            "mark_array" => $this->text_array,
            "good" => $all_good,
        ];
    }
}
