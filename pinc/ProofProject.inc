<?php
declare(strict_types=1);

class ProofProjectPage extends LPage
{
    public function __construct(object $proof_project, object $project_page)
    {
        $this->page_state = $project_page->page_state;
        $this->page_name = $project_page->page_name;
        $this->project = $proof_project->project;
        $this->projectid = $proof_project->project->projectid;
        $this->round = $proof_project->round;

        parent::__construct($this->projectid, $this->page_name, $this->page_state, 0);
    }

    public function checkout($user)
    {
        // use DPage function
        $this->page_state = Page_checkout($this->projectid, $this->imagefile, $this->round, $user);
        return $this->render_page_data();
    }

    public function attempt_save_as_done(string $page_text): array
    {
        global $pguser;

        $this->page_text = $this->get_valid_text($page_text);
        [$saved, $dpl_reached] = $this->attemptSaveAsDone($this->page_text, $pguser);

        if (!$dpl_reached) {
            return ["message" => _("Your page has been checked-in.")];
        } elseif ($saved) {
            $sentence = sprintf(
                _("Your page has been checked-in. However, you have now reached the daily page limit for %s."),
                $this->round->id
            );
            return ["message" => $sentence];
        } else {
            $sentence = sprintf(
                _("Your page was not checked-in because you have already reached the daily page limit for %s. You can save it and will be able to check it in after server midnight."),
                $this->round->id
            );
            throw new DailyLimitExceededException($sentence);
        }
    }

    private function save_as_done(): void
    {
        global $pguser;

        $this->page_state = Page_saveAsDone($this->projectid, $this->page_name, $this->round, $pguser, $this->page_text);
        // add to user page count
        page_tallies_add($this->round->id, $pguser, +1);
    }

    public function save_as_in_progress(string $page_text): array
    {
        global $pguser;
        $this->page_text = $this->get_valid_text($page_text);
        $this->saveAsInProgress($page_text, $pguser);
        return $this->get_page_text_array() + $this->get_page_state_array();
    }

    public function save_and_revert(string $page_text): array
    {
        global $pguser;
        $this->page_text = $this->get_valid_text($page_text);
        $this->saveAsInProgress($page_text, $pguser);
        $this->revertToOriginal();
        return $this->get_page_text_array() + $this->get_page_state_array();
    }

    public function resume_page(): array
    {
        global $pguser;

        // page could be in out, temp or saved state
        if ($this->page_state == $this->round->page_save_state) {
            // Page comes from DONE.
            // no need to update text, just record state change
            $this->page_state = Page_reopen($this->projectid, $this->imagefile, $this->round, $pguser);
            // When it was saved, the user's page-count was incremented.
            // Now they are 'unsaving' it, so decrement their page-count.
            // They'll get it back if/when they save-as-done again.
            page_tallies_add($this->round->id, $pguser, -1);
        } else {
            // Page comes from IN PROGRESS or OUT.
            // Resuming such a page has no actual effect on the page,
            _Page_require($this->projectid, $this->page_name, [$this->round->page_out_state, $this->round->page_temp_state], $this->round->user_column_name, $pguser, 'resume');
        }
        return $this->render_page_data();
    }

    public function render_page_text(): array
    {
        // validate user and page? page should be in out or temp state only
        return $this->get_page_text_array();
    }

    private function render_page_data(): array
    {
        $round_info_array = [];
        foreach ($this->round->other_rounds_with_visible_usernames as $other_round_id) {
            $other_round = get_Round_for_round_id($other_round_id);
            $username = $this->get_username_for_round($other_round);
            $round_info_array[] = [$other_round->id, $username];
        }
        return
            $this->get_page_text_array() +
            $this->get_page_state_array() +
            [
                "pagenum" => pathinfo($this->page_name, PATHINFO_FILENAME),
                "round_info_array" => $round_info_array,
                "pagename" => $this->page_name,
                "image_url" => "{$this->project->url}/{$this->page_name}",
                "langDir" => lang_direction(langcode2_for_langname($this->project->languages[0])),
            ];
    }

    public function get_page_text_array(): array
    {
        return ["text" => $this->get_text()];
    }

    private function get_page_state_array()
    {
        return [
            "pagestate" => $this->page_state,
            "saved" => $this->can_be_reverted_to_last_save(),
        ];
    }

    private function get_valid_text(string $text): string
    {
        $pattern_string = build_character_regex_filter($this->project->get_valid_codepoints());
        foreach (split_graphemes($text) as $grapheme) {
            if (1 != preg_match("/$pattern_string/u", $grapheme)) {
                throw new ProjectInvalidTextException(_("The text contains characters which are not allowed for this project"));
            }
        }
        return _normalize_page_text($text, $this->project->projectid);
    }
}

class ProofProject
{
    public function __construct(object $project)
    {
        global $pguser;

        $project_round = $project->get_project_available_round();
        $project_round->validate_user_can_access($pguser);

        $this->project = $project;
        $this->round = $project_round;
    }

    public function checkout(): array
    {
        global $pguser;

        [$page_name, $state] = get_available_proof_page_array($this->project, $this->round, $pguser);
        $project_page = new ProjectPage($page_name, $state);
        $proof_project_page = new ProofProjectPage($this, $project_page);

        return $proof_project_page->checkout($pguser);
    }
}

class TextChecker
{
    private const VALID_TEXT = 0;
    private const INVALID_TEXT = 1;

    private $text_array;
    private $valid_text_block;

    private function append_valid(): void
    {
        if ($this->valid_text_block != "") {
            $this->text_array[] = [$this->valid_text_block, self::VALID_TEXT];
            $this->valid_text_block = "";
        }
    }

    public function analyse(string $page_text, object $project): array
    {
        $pattern_string = build_character_regex_filter($project->get_valid_codepoints());
        $this->text_array = [];
        $this->valid_text_block = "";
        $all_valid = true;
        foreach (split_graphemes($page_text) as $grapheme) {
            if (1 === preg_match("/$pattern_string/u", $grapheme)) {
                $this->valid_text_block .= $grapheme;
            } else {
                $all_valid = false;
                $this->append_valid();
                $this->text_array[] = [$grapheme, self::INVALID_TEXT];
            }
        }
        // append any remaining valid text
        $this->append_valid();
        return [
            "mark_array" => $this->text_array,
            "valid" => $all_valid,
        ];
    }
}
