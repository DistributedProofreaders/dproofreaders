<?php
include_once($relPath.'Project.inc');
include_once($relPath.'page_tally.inc');
include_once($relPath.'prefs_options.inc'); // get_user_proofreading_font()
include_once($relPath.'LPage.inc');
include_once($relPath.'links.inc');

// Page-related code that's common to the standard and enhanced interfaces.

// "pi" = "proofing interface"

function url_for_pi_do_whichever_page($projectid, $proj_state, $escape_amp = false)
{
    global $code_url;
    if ($escape_amp) {
        $amp = '&amp;';
    } else {
        $amp = '&';
    }
    return "$code_url/tools/proofers/proof.php"
        . "?"
        . "projectid=$projectid"
        . $amp
        . "proj_state=$proj_state";
}

function url_for_pi_do_particular_page($projectid, $proj_state, $imagefile, $page_state, $escape_amp = false)
{
    global $code_url;
    if ($escape_amp) {
        $amp = '&amp;';
    } else {
        $amp = '&';
    }
    return "$code_url/tools/proofers/proof.php"
        . "?"
        . "projectid=$projectid"
        . $amp
        . "imagefile=$imagefile"
        . $amp
        . "proj_state=$proj_state"
        . $amp
        . "page_state=$page_state";
}

// -----------------------------------------------------------------------------

function get_requested_PPage($request_params)
{
    $projectid = get_projectID_param($request_params, 'projectid');
    $proj_state = get_enumerated_param($request_params, 'proj_state', null, ProjectStates::get_states());
    $imagefile = get_page_image_param($request_params, 'imagefile');
    $page_state = get_enumerated_param($request_params, 'page_state', null, Rounds::get_page_states());
    $reverting = get_integer_param($request_params, 'reverting', 0, 0, 1);

    $lpage = get_indicated_LPage(
        $projectid,
        $proj_state,
        $imagefile,
        $page_state,
        $reverting
    );

    return new PPage($lpage, $proj_state);
}

// ---------------------------------------------------------

// The initial 'P' is for 'Presentation' (i.e., user interface).
class PPage
{
    public function __construct(&$lpage, $proj_state)
    {
        $this->lpage = & $lpage;
        $this->proj_state = $proj_state;
    }

    // -----------------------------------------------------

    public function url_for_image()
    {
        global $projects_url;
        return "$projects_url/{$this->lpage->projectid}/{$this->lpage->imagefile}";
    }

    public function url_for_display_image($escape_amp = false)
    {
        global $code_url;
        if ($escape_amp) {
            $amp = '&amp;';
        } else {
            $amp = '&';
        }
        return "$code_url/tools/page_browser.php"
            . "?project={$this->lpage->projectid}"
            . $amp
            . "imagefile={$this->lpage->imagefile}";
    }

    public function url_for_do_another_page($escape_amp = false)
    {
        global $code_url;
        if ($escape_amp) {
            $amp = '&amp;';
        } else {
            $amp = '&';
        }
        return "$code_url/tools/proofers/proof_frame.php"
            . "?"
            . "projectid={$this->lpage->projectid}"
            . $amp
            . "proj_state={$this->proj_state}";
    }

    public function url_for_project_comments($escape_amp = false)
    {
        global $code_url;
        if ($escape_amp) {
            $amp = '&amp;';
        } else {
            $amp = '&';
        }
        return "$code_url/project.php"
            . "?"
            . "id={$this->lpage->projectid}"
            . $amp
            . "expected_state={$this->proj_state}"
            . "#project-comments";
    }

    public function urlencoded($escape_amp = false)
    {
        if ($escape_amp) {
            $amp = '&amp;';
        } else {
            $amp = '&';
        }
        return implode(
            $amp,
            [
                'projectid='  . $this->lpage->projectid,
                'proj_state=' . $this->proj_state,
                'imagefile='  . $this->lpage->imagefile,
                'page_state=' . $this->lpage->page_state,
                'reverting='  . $this->lpage->reverting_to_orig,
            ]
        );
    }

    // -----------------------------------------------------

    public function echo_hidden_fields()
    {
        foreach ([
            'imagefile' => $this->lpage->imagefile,
            'proj_state' => $this->proj_state,
            'page_state' => $this->lpage->page_state,
            'projectid' => $this->lpage->projectid,
            'reverting' => $this->lpage->reverting_to_orig,
        ] as $param_name => $param_value
        ) {
            echo "<input type='hidden' value='$param_value' name='$param_name' id='$param_name'>\n";
        }
    }

    public function echo_info()
    {
        $round_info_array = $this->lpage->get_info();

        //display page number
        echo "<span class='small bold'>";
        echo sprintf(_("Page: %s"), $this->lpage->get_filename());

        if (count($round_info_array) > 0) {
            echo " &mdash; ", implode(", ", array_map([$this, 'make_round_user_string'], $round_info_array));
        }
        echo "</span>\n";
    }

    private function make_round_user_string(RoundInfo $info): string
    {
        $round_user_string = "$info->round_id: ";
        $username = $info->username;
        if ($username == '' || $info->user_id == null) {
            // Grey, because it's not the link expected by the user.
            $round_user_string .= "<span class='text-link-disabled'>";
            if ($username == '') {
                // e.g., the project might have skipped $other_round,
                // or it could be a future round.
                $round_user_string .= "<i>" . pgettext("no user", "none") . "</i>";
            } else {
                $round_user_string .= $username;
            }
            $round_user_string .= "</span>";
        } else {
            $round_user_string .= private_message_link($username, "comments");
        }
        return $round_user_string;
    }

    public function echo_proofing_textarea()
    {
        $user = User::load_current();

        $page_text = $this->lpage->get_text();

        $lang = $this->lpage->get_language();

        if ($user->profile->i_layout == 1) {
            // "vertical"
            $n_cols = $user->profile->v_tchars;
            $n_rows = $user->profile->v_tlines;
            $line_wrap = $user->profile->v_twrap;
        } else {
            // "horizontal"
            $n_cols = $user->profile->h_tchars;
            $n_rows = $user->profile->h_tlines;
            $line_wrap = $user->profile->h_twrap;
        }

        echo "<textarea
            name='text_data'
            id='text_data'
            cols='$n_cols'
            rows='$n_rows'
            autocorrect='off'
            autocomplete='off'
            autocapitalize='off'
            dir='".lang_direction($lang)."'
        ";

        [, $font_size, $font_family] = get_user_proofreading_font();
        $font_size_string = '';
        if ($font_size != '') {
            $font_size_string = "font-size: $font_size;";
        }
        echo "style=\"font-family: $font_family; $font_size_string padding-left: 0.25em;\" ";

        if (!$line_wrap) {
            echo "wrap='off' ";
        }

        echo ">\n";

        // SENDING PAGE-TEXT TO USER
        // We're sending it in an HTML document, so encode special characters.
        echo html_safe($page_text);

        echo "</textarea>";
    }

    // -------------------------------------------------------------------------

    /**
     * Attempt to save a page as done
     *
     * This is only an attempt, because a daily page limit might block the save,
     * or prevent further saves.
     * If there's a problem, this function does not return to the caller.
     */
    public function attempt_to_save_as_done($text_data)
    {
        global $code_url, $pguser;

        $projectid = $this->lpage->projectid;
        $round = $this->lpage->round;

        [$saved, $dpl_reached] = $this->attemptSaveAsDone($text_data, $pguser);

        if (!$dpl_reached) {
            assert($saved);
            return; // to let the caller do the appropriate normal thing
        }

        assert($dpl_reached);

        if ($saved) {
            $title = _("Saved, but at limit");
            $sentence = sprintf(
                _("Your page has been saved as 'Done'. However, you have now reached the daily page limit for %s."),
                $round->id
            );
        } else {
            $this->saveAsInProgress($text_data, $pguser);
            $title = _("Already at limit");
            $sentence = sprintf(
                _("Your page was saved as 'In Progress' rather than 'Done', because you have already reached the daily page limit for %s."),
                $round->id
            );
        }

        slim_header($title);

        echo "<p>$sentence</p>\n";

        echo "<p>"
            . sprintf(
                _("You will be able to save more pages in %s after server midnight."),
                $round->id
            )
            . "</p>\n";

        echo "<ul>\n"
            .   "<li>"
            .     return_to_project_page_link($projectid)
            .   "</li>\n"
            .   "<li>"
            .     return_to_round_page_link($round->id)
            .   "</li>\n"
            .   "<li>"
            .     return_to_activity_hub_link()
            .   "</li>\n"
            . "</ul>\n";

        // Do not return to caller.
        exit;
    }

    // -------------------------------------------------------------------------

    // The remaining functions just delegate to $this->lpage...

    public function revertToOriginal()
    {
        $this->lpage->revertToOriginal();
    }

    public function revertToSaved()
    {
        $this->lpage->revertToSaved();
    }

    public function returnToRound($user)
    {
        $this->lpage->returnToRound($user);
    }

    public function markAsBad($user, $reason)
    {
        return $this->lpage->markAsBad($user, $reason);
    }

    public function saveAsInProgress($page_text, $user)
    {
        $this->lpage->saveAsInProgress($page_text, $user);
    }

    public function attemptSaveAsDone($page_text, $user)
    {
        return $this->lpage->attemptSaveAsDone($page_text, $user);
    }

    public function can_be_reverted_to_last_save()
    {
        return $this->lpage->can_be_reverted_to_last_save();
    }

    public function projectid()
    {
        return $this->lpage->projectid;
    }

    public function imagefile()
    {
        return $this->lpage->imagefile;
    }
}
