<?php
$relPath = "./../../pinc/";
include_once($relPath.'base.inc');
include_once($relPath.'theme.inc');
include_once($relPath.'links.inc');
include_once('edit_common.inc');
include_once($relPath.'wordcheck_engine.inc');
include_once($relPath.'metarefresh.inc');
include_once($relPath.'Project.inc');
include_once($relPath.'faq.inc');

require_login();

$return = array_get($_REQUEST, "return", "$code_url/tools/project_manager/projectmgr.php");
$projectid = get_projectID_param($_REQUEST, 'projectid');

$pwlh = new ProjectWordListHolder($projectid);

$fatal_error = $pwlh->validate_project_and_access();
$errors = [];
$good_word_conflict = $bad_word_conflict = false;

if (isset($_POST['saveAndProject']) || isset($_POST['saveAndPM']) || isset($_POST['save'])) {
    if (!$fatal_error) {
        $pwlh->set_from_post();
        [$good_word_conflict, $bad_word_conflict, $errors] = $pwlh->save_to_files();
        if (!$errors) {
            if (isset($_POST['saveAndProject'])) {
                metarefresh(0, "$code_url/project.php?id=$pwlh->projectid", _("Save and Go To Project"));
            } elseif (isset($_POST['saveAndPM'])) {
                // TRANSLATORS: PM = project manager
                metarefresh(0, "$code_url/tools/project_manager/projectmgr.php", _("Save and Go To PM Page"));
            }
        } elseif (isset($_POST['save'])) {
            // No errors, but fall through.
        }
    } else {
        // fatal Errors.
        // fall through
    }
} elseif (isset($_POST['quit'])) {
    // if return is empty for whatever reason take them to
    // the PM page
    if (empty($return)) {
        $return = "$code_url/tools/project_manager/projectmgr.php";
    }

    // do the redirect
    metarefresh(0, $return, _("Quit Without Saving"));
} elseif (isset($_POST['reload'])) {
    // fall through
}

if (!$fatal_error) {
    $errors = array_merge($errors, $pwlh->set_from_files(!$good_word_conflict, !$bad_word_conflict));
}

$page_title = _("Edit project word lists");

output_header($page_title, NO_STATSBAR);
echo "<h1>$page_title</h1>\n";

if ($fatal_error) {
    echo "<p class='error'>" . html_safe($fatal_error) . "</p>";
    exit();
}

foreach ($errors as $error) {
    echo "<p class='error'>" . html_safe($error) . "</p>";
}

$pwlh->show_form();

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

class ProjectWordListHolder
{
    public string $projectid;
    public Project $project;
    public string $bad_words;
    public string $good_words;
    public int $bwl_timestamp;
    public int $gwl_timestamp;


    public function __construct($projectid)
    {
        $this->projectid = $projectid;
        $this->project = new Project($projectid);
    }

    public function validate_project_and_access()
    {
        if (!$this->project->dir_exists) {
            return _("Project directory does not exist, unable to manage word lists.");
        }

        if (!$this->project->can_be_managed_by_current_user) {
            return _("You are not authorized to manage this project.");
        }

        return null;
    }

    public function set_from_files($load_good_words = true, $load_bad_words = true)
    {
        $errors = [];

        if ($load_good_words) {
            $gwl_object = get_project_word_file($this->projectid, "good");
            $this->gwl_timestamp = $gwl_object->mod_time;
            $good_words = load_project_good_words($this->projectid);

            if (is_string($good_words)) {
                array_push($errors, $good_words);
                $this->good_words = '';
            } else {
                $this->good_words = implode("\n", $good_words);
            }
        }

        if ($load_bad_words) {
            $bwl_object = get_project_word_file($this->projectid, "bad");
            $this->bwl_timestamp = $bwl_object->mod_time;
            $bad_words = load_project_bad_words($this->projectid);
            if (is_string($bad_words)) {
                array_push($errors, $bad_words);
                $this->bad_words = '';
            } else {
                $this->bad_words = implode("\n", $bad_words);
            }
        }

        return $errors;
    }

    public function set_from_post()
    {
        $this->good_words = @$_POST['good_words'];
        $this->bad_words = @$_POST['bad_words'];
        $this->gwl_timestamp = get_integer_param($_POST, 'gwl_timestamp', null, null, null);
        $this->bwl_timestamp = get_integer_param($_POST, 'bwl_timestamp', null, null, null);
    }


    // -------------------------------------------------------------------------

    public function save_to_files()
    {
        $good_word_conflict = $bad_word_conflict = false;
        $messages = [];

        // first, check to see if the good or bad word list
        // has changed out from beneath us
        $gwl_object = get_project_word_file($this->projectid, "good");
        $bwl_object = get_project_word_file($this->projectid, "bad");
        $current_gwl_timestamp = $gwl_object->mod_time;
        $current_bwl_timestamp = $bwl_object->mod_time;

        if ($current_gwl_timestamp != $this->gwl_timestamp) {
            // TRANSLATORS: %s is a link to the Good Word List
            $error = sprintf(_("The Good Words List was changed by another process during your edit session. Your changes to this list have not been saved to prevent data loss. View the %s and merge your changes manually. If you want the superset of both lists, simply append the contents of the Good Words List to that within the Good Words edit box below - the server will remove any duplicates. Saving this page again will override this message."), new_window_link($gwl_object->abs_url, _("Good Words List")));
            $this->gwl_timestamp = $current_gwl_timestamp;
            array_push($messages, $error);
            $good_word_conflict = true;
        } else {
            // everything looks good, save the changes
            $good_words = explode("[lf]", str_replace(["\r", "\n"], ['', "[lf]"], $this->good_words));
            save_project_good_words($this->projectid, $good_words);
        }

        if ($current_bwl_timestamp != $this->bwl_timestamp) {
            // TRANSLATORS: %s is a link to the Bad Word List
            $error = sprintf(_("The Bad Words List was changed by another process during your edit session. Your changes to this list have not been saved to prevent data loss. View the %s and merge your changes manually. If you want the superset of both lists, simply append the contents of the Bad Words List to that within the Bad Words edit box below - the server will remove any duplicates. Saving this page again will override this message."), new_window_link($bwl_object->abs_url, _("Bad Words List")));
            $this->bwl_timestamp = $current_bwl_timestamp;
            array_push($messages, $error);
            $bad_word_conflict = true;
        } else {
            // everything looks good, save the changes
            $bad_words = explode("[lf]", str_replace(["\r", "\n"], ['', "[lf]"], $this->bad_words));
            save_project_bad_words($this->projectid, $bad_words);
        }


        return [$good_word_conflict, $bad_word_conflict, $messages];
    }

    // =========================================================================

    public function show_form()
    {
        echo "<form method='post' enctype='multipart/form-data' action='". attr_safe($_SERVER['PHP_SELF']) ."'>";

        $this->show_hidden_controls();

        echo "<table class='basic' style='width: 90%; margin: auto;'>";

        $this->show_visible_controls();

        // The space between buttons ensures that very long (translated) button
        // labels do not force the display to be wider than the screen.
        echo "<tr>";
        echo   "<th colspan='2' class='center-align' style='padding: 0.5em;'>";
        // TRANSLATORS: PM = project manager
        echo     "<input type='submit' name='saveAndPM' value='", attr_safe(_("Save and Go To PM Page")), "'> ";
        echo     "<input type='submit' name='saveAndProject' value='", attr_safe(_("Save and Go To Project")), "'> ";
        echo     "<input type='submit' name='save' value='", attr_safe(_("Save")), "'> ";
        echo     "<input type='submit' name='quit' value='", attr_safe(_("Quit Without Saving")), "'> ";
        echo     "<input type='submit' name='reload' value='", attr_safe(_("Refresh Word Lists")), "'>";
        echo   "</th>";
        echo "</tr>\n";

        echo "<tr>";
        echo "<th class='label'>" . _("Project Information") . "</th>";
        echo "<td>" . new_window_link("editproject.php?action=edit&amp;project=$this->projectid", _("Edit project information")) . "</td>";
        echo "</tr>";

        echo "</table>";
        echo "</form>";
        echo "\n";
    }

    // -------------------------------------------------------------------------

    public function show_hidden_controls()
    {
        global $return;

        echo "<input type='hidden' name='projectid' value='$this->projectid'>";
        echo "<input type='hidden' name='gwl_timestamp' value='$this->gwl_timestamp'>";
        echo "<input type='hidden' name='bwl_timestamp' value='$this->bwl_timestamp'>";
        echo "<input type='hidden' name='return' value='$return'>";
    }


    public function show_visible_controls()
    {
        $goodWordData = html_safe($this->good_words);
        $badWordData = html_safe($this->bad_words);

        $fields = [
            "projectid" => _("Project ID"),
            "nameofwork" => _("Title"),
            "authorsname" => _("Author"),
            "username" => _("Project Manager"),
            "checkedoutby" => _("Post-Processor"),
            "language" => _("Language"),
        ];

        foreach ($fields as $field => $label) {
            echo "<tr>";
            echo "<th class='label'>$label</th>";
            echo "<td>" . html_safe($this->project->$field) . "</td>";
            echo "</tr>";
        }

        $exist_OCR_pages = ($this->number_of_pages_in_round(null) > 0);
        $exist_pages_in_P1_or_later = ($this->number_of_pages_in_round(get_Round_for_round_number(1)) > 0);

        // due to some special circumstances, not all projects may have pages in P1
        // so we'll check to see if the project is in a state after P1 and count
        // that as just as good
        if (!$exist_pages_in_P1_or_later) {
            $current_project_round = get_Round_for_project_state($this->project->state);
            if ($current_project_round && $current_project_round->round_number > 1) {
                $exist_pages_in_P1_or_later = true;
            }
        }

        // if the project doesn't have any OCR pages and the project
        // has no P1 or later pages, report a message. The second criteria
        // is important for type-in projects that may have no OCR pages but
        // will have P1 or later pages
        if (!$exist_OCR_pages && !$exist_pages_in_P1_or_later) {
            echo "<tr>";
            echo "<td colspan='2'>";
            echo "<p class='error' style='text-align: center;'>";
            echo _("There are no pages associated with this project.");
            echo "</p>";
            echo "</td>";
            echo "</tr>";
        } else {
            echo "<tr>";
            echo "<th class='label' style='text-align: center;' colspan='2'>";
            echo _("WordCheck Tools and Reports");
            echo "</th>";
            echo "</tr>";

            echo "<tr>";
            echo "<th class='label'>" . _("Ad Hoc Word Details") . "</th>";
            echo "<td>" . new_window_link("show_adhoc_word_details.php?projectid=$this->projectid", _("Show details for ad hoc words")) . "</td>";
            echo "</tr>";

            echo "<tr>";
            echo "<th class='label'>" . _("WordCheck Statistics") . "</th>";
            echo "<td>" . new_window_link("show_project_wordcheck_stats.php?projectid=$this->projectid", _("Show WordCheck flagged word statistics")) . "</td>";
            echo "</tr>";

            if ($exist_pages_in_P1_or_later) {
                echo "<tr>";
                echo "<th class='label'>" . _("WordCheck Usage") . "</th>";
                echo "<td>" . new_window_link("show_project_wordcheck_usage.php?projectid=$this->projectid", _("Show WordCheck interface usage")) . "</td>";
                echo "</tr>";
            }

            echo "<tr>";
            echo "<th class='label' style='text-align: center;' colspan='2'>";
            echo _("Word List Suggestion Tools");
            echo "</th>";
            echo "</tr>";

            echo "<tr>";
            echo "<td colspan='2'>";

            echo "<table style='width: 100%;'>";

            echo "<tr>";
            echo "<td style='width: 50%; text-align: center;' class='top-align'>";

            echo "<p>";
            echo "<b>" . _("Words that WordCheck would currently flag:") . "</b><br>";
            echo new_window_link(
                "show_current_flagged_words.php?projectid=$this->projectid",
                _("Display")
            );
            echo " | ";
            echo "<a href='show_current_flagged_words.php?projectid=$this->projectid&amp;format=file'>" . _("Download") . "</a>";
            echo "</p>";

            $suggestions = load_project_good_word_suggestions($this->projectid);
            if (count($suggestions)) {
                echo "<p>";
                echo "<b>" . _("Suggestions from proofreaders:") . "</b><br>";
                echo new_window_link(
                    "show_good_word_suggestions.php?projectid=$this->projectid",
                    _("Display")
                );
                echo " | ";
                echo "<a href='show_good_word_suggestions.php?projectid=$this->projectid&amp;timeCutoff=0&amp;format=file'>" . _("Download") . "</a>";
                echo "</p>";
            }

            echo "</td>";
            echo "<td style='width: 50%; text-align: center;' class='top-align'>";

            // see if the site has Possible Bad Word files
            $possible_bad_words = load_site_possible_bad_words_given_project($this->projectid);

            if (count($possible_bad_words)) {
                echo "<p>";
                echo "<b>" . _("Words in the Site's Possible bad words file:") . "</b><br>";
                echo new_window_link(
                    "show_project_possible_bad_words.php?projectid=$this->projectid",
                    _("Display")
                );
                echo " | ";
                echo "<a href='show_project_possible_bad_words.php?projectid=$this->projectid&amp;format=file'>" . _("Download") . "</a>";
                echo "</p>";
            }

            // see if there are P1 (or later) and OCR pages before showing the link.
            // type-in projects may have P1 (or later) pages but no OCR pages
            // and the current show_project_stealth_scannos.php page only works
            // with projects that have OCR text
            if ($exist_pages_in_P1_or_later && $exist_OCR_pages) {
                echo "<p>";
                echo "<b>" . _("Suggestions from diff analysis:") . "</b><br>";
                echo new_window_link(
                    "show_project_stealth_scannos.php?projectid=$this->projectid",
                    _("Display")
                );
                echo " | ";
                echo "<a href='show_project_stealth_scannos.php?projectid=$this->projectid&amp;format=file'>" . _("Download") . "</a>";
                echo "</p>";
            }

            echo "</td>";
            echo "</tr>";
            echo "</table>";

            echo "</td>";
            echo "</tr>";
        }

        echo "<tr>";
        echo "<th class='label' style='text-align: center;' colspan='2'>";
        echo _("Project Dictionary - Word Lists");
        echo "</th>";
        echo "</tr>";

        echo "<tr>";
        echo "<td colspan='2'>";

        echo "<table style='width: 100%;'>";
        echo "<tr>";
        echo "<th class='label' style='text-align: center;'>" . _("Good Words") . "</th>";
        echo "<th class='label' style='text-align: center;'>" . _("Bad Words") . "</th>";
        echo "</tr>";

        echo "<tr>";
        echo "<td style='width: 50%;'>";
        echo "<textarea class='mono' name='good_words' cols='40' rows='20'>$goodWordData</textarea>";
        echo "</td>";

        echo "<td style='width: 50%;'>";
        echo "<textarea class='mono' name='bad_words' cols='40' rows='20'>$badWordData</textarea>";
        echo "</td>";
        echo "</tr>";
        echo "</table>";

        echo "</td>";
        echo "</tr>";

        echo "<tr>";
        echo "<td colspan='2' style='text-align: center;'>";

        echo sprintf(
            // TRANSLATORS: %s is a link to the WordCheck FAQ.
            _("See the %s for more information on word lists."),
            new_window_link(get_faq_url("wordcheck-faq.php"), _("WordCheck FAQ"))
        );

        echo "</td>";
        echo "</tr>";
    }


    public function number_of_pages_in_round($round)
    {
        if (!$this->project->pages_table_exists) {
            return 0;
        }

        if ($round !== null) {
            $round_column = $round->text_column_name;
        } else {
            $round_column = "master_text";
        }
        validate_projectID($this->projectid);
        $sql = "select count(*) from $this->projectid where $round_column <> ''";

        $res = DPDatabase::query($sql);
        $count = mysqli_fetch_row($res);

        mysqli_free_result($res);

        return $count[0];
    }
}
