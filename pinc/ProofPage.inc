<?php

include_once($relPath."RoundDescriptor.inc"); // text_column_name etc.

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

class ProofPage
{
    public function __construct($project, $round, $pagename)
    {
        $this->project = $project;
        $this->projectid = $project->projectid;
        $this->pagename = $pagename;
        $this->round = $round;
    }

    public function validate($can_be_in_saved_state = false)
    {
        global $pguser;

        $this->project->validate_project_page_table();
        validate_projectID($this->projectid);
        $user_column = $this->round->user_column_name;

        $sql = sprintf("
            SELECT state, $user_column
            FROM %s
            WHERE image = '%s'
        ", $this->projectid, DPDatabase::escape($this->pagename)
        );
        $res = DPDatabase::query($sql);
        $row = mysqli_fetch_assoc($res);

        $this->state = $row["state"];
        $allowed_states = [$this->round->page_temp_state];
        if ($can_be_in_saved_state) {
            $allowed_states[] = $this->round->page_save_state;
        }
        if (!in_array($this->state, $allowed_states)) {
            throw new BadRequest(_("The page state $this->state is not allowed for this operation."), DP::WRONG_PAGE_STATE);
        }
        if ($row[$user_column] != $pguser) {
            throw new UnauthorizedError(_("You do not own this page."), DP::WRONG_PAGE_OWNER);
        }
    }

    public function render_page_data()
    {
        global $projects_url;

        return [
            "pagename" => $this->pagename,
            "image_url" => "{$this->project->url}/{$this->pagename}",
            "text" => $this->get_text(),
        ];
    }

    public function render_page_text($page_option)
    {
        return ["text" => $this->get_text($page_option)];
    }

    private function get_text($page_option = "current")
    {
        if ($page_option == "current") {
            $desired_column_name = $this->round->text_column_name;
        } else {
            $desired_column_name = $this->round->prevtext_column_name;
        }

        $sql = "
            SELECT $desired_column_name
            FROM {$this->projectid}
            WHERE image='{$this->pagename}'
        ";
        $res = DPDatabase::query($sql);
        [$page_text] = mysqli_fetch_row($res);

        return $page_text;
    }

    public function checkout()
    {
        global $pguser;

        $setters = join(", ", [
            set_col_str("state", $this->round->page_temp_state),
            set_col_num($this->round->time_column_name, time()),
            set_col_str($this->round->user_column_name, $pguser),
            // copy text from prev. round (unlike DPage version)
            "{$this->round->text_column_name} = {$this->round->prevtext_column_name}",
        ]);
        _Page_UPDATE($this->projectid, $this->pagename, $setters);
        _log_page_event($this->projectid, $this->pagename, 'checkout', $pguser, $this->round);
        _project_adjust_n_available_pages($this->projectid, -1);
    }

    public function save_as_in_progress($page_text)
    {
        global $pguser;

        $page_text = _normalize_page_text($page_text, $this->projectid);
        $setters = join(", ", [
            set_col_num($this->round->time_column_name, time()),
            set_col_str($this->round->text_column_name, $page_text),
        ]);
        _Page_UPDATE($this->projectid, $this->pagename, $setters);
        _log_page_event($this->projectid, $this->pagename, 'saveAsInProgress', $pguser, $this->round);
        upi_set_t_latest_page_event($pguser, $this->projectid, time());
    }

    public function attempt_save_as_done($page_text)
    {
        // return true if succeeded
        global $pguser;

        if ($this->round->has_a_daily_page_limit()) {
            $pre_save_dpl_count = get_dpl_count_for_user_in_round($pguser, $this->round);
            if ($pre_save_dpl_count >= $this->round->daily_page_limit) {
                // The user has already reached their limit of this kind of page.
                $this->save_as_in_progress($page_text);
                return false;
            }
        }

        $timestamp = time();
        $page_text = _normalize_page_text($page_text, $this->projectid);
        $setters = join(", ", [
            set_col_str("state", $this->round->page_save_state),
            set_col_num($this->round->time_column_name, $timestamp),
            set_col_str($this->round->text_column_name, $page_text),
        ]);
        _Page_UPDATE($this->projectid, $this->pagename, $setters);
        _log_page_event($this->projectid, $this->pagename, 'saveAsDone', $pguser, $this->round);
        upi_set_t_latest_page_event($pguser, $this->projectid, $timestamp);
        _project_set_t_last_page_done($this->projectid, $timestamp);
        page_tallies_add($this->round->id, $pguser, +1);
        return true;
    }

    public function return_to_round()
    {
        global $pguser;

        $setters = join(", ", [
            set_col_str("state", $this->round->page_avail_state),
            set_col_num($this->round->time_column_name, time()),
        ]);
        _Page_UPDATE($this->projectid, $this->pagename, $setters);
        _log_page_event($this->projectid, $this->pagename, 'returnToRound', $pguser, $this->round);
        _project_adjust_n_available_pages($this->projectid, +1);
    }

    public function resume_saved_page()
    {
        global $pguser;

        if ($this->state == $this->round->page_save_state) {
            // Page comes from DONE.
            // no need to update text, just record state change
            $setters = join(", ", [
                set_col_str("state", $this->round->page_temp_state),
                set_col_num($this->round->time_column_name, time()),
            ]);
            _Page_UPDATE($this->projectid, $this->pagename, $setters);
            _log_page_event($this->projectid, $this->pagename, 'reopen', $pguser, $this->round);

            // When it was saved, the user's page-count was incremented.
            // Now they are 'unsaving' it, so decrement their page-count.
            // They'll get it back if/when they save-as-done again.
            page_tallies_add($this->round->id, $pguser, -1);
        }
        // otherwise it is in temp state: do nothing
    }
}
