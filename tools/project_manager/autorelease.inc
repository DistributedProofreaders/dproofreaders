<?php
include_once($relPath.'site_vars.php');
include_once($relPath.'send_mail.inc');
include_once($relPath.'stages.inc');
include_once($relPath.'release_queue.inc');

function autorelease()
{
    // autorelease is called via cron and output is never shown to end-users
    // so it's safe to enable full error reporting here
    error_reporting(E_ALL);

    echo "<pre>\n";
    echo "Starting autorelease\n";

    for ($rn = 1; $rn <= MAX_NUM_PAGE_EDITING_ROUNDS; $rn++) {
        $round = get_Round_for_round_number($rn);
        autorelease_for_round($round);
    }

    echo "</pre>\n";
}

function attempt_to_release($round, $project, $queue_name)
{
    $projectid = $project['projectid'];

    $project_obj = new Project($projectid);
    $errors = project_pre_release_check($project_obj, $round);

    if ($errors) {
        configure_gettext_for_user($project_obj->username);
        $body_blurb_messages[] = _("Some errors have been found:");
        $body_blurb_messages[] = "* " . implode("\n* ", $errors);
        $body_blurb_messages[] = _("Please correct the errors and put the project back into Waiting for Release.");
        $body_blurb = implode("\n\n", $body_blurb_messages);
        send_mail_project_manager($project_obj, $body_blurb, _("Errors Before Release"));
        $new_state = $round->project_bad_state;
    } else {
        $new_state = $round->project_available_state;
    }


    $error_msg = project_transition($projectid, $new_state, PT_AUTO, ['details' => "via_q: $queue_name"]);
    if ($error_msg) {
        echo "$error_msg\n";
        return false;
    }

    if ($new_state == $round->project_bad_state) {
        // nothing else to do
        return false;
    }

    return true;
}




function autorelease_for_round($round)
{
    echo "\n";
    echo "Starting autorelease for round {$round->id}...\n";

    $sql = sprintf("
        SELECT *
        FROM queue_defns
        WHERE round_id='%s'
        ORDER BY ordering",
        DPDatabase::escape($round->id));
    $q_res = DPDatabase::query($sql);

    if (mysqli_num_rows($q_res) == 0) {
        echo "\n";
        echo "There are no queue definitions for this round (enabled or not)!\n";
        echo "We interpret this as meaning that projects should not\n";
        echo "wait here, but automatically become available.\n";

        maybe_release_projects(
            $round,
            null,
            false, // $observe_release_restrictions
            // Mind you, there's no reason it *couldn't* observe release restrictions.
            // We'd just have to create $round->release_restrictor earlier.
            false // $release_at_most_one
        );

        echo "\n";
        echo "Finished round {$round->id}\n";
        echo "XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX";
        return;
    }

    // -------------------------------------------------------------------------

    // Some queue definitions do exist for this round.

    $round->release_restrictor = new ReleaseRestrictor($round);

    AP_setup($round);

    // -------------------------------------------------------------------------

    // Release of Different types of Projects from various logical queues

    $sql = sprintf("
        SELECT *
        FROM queue_defns
        WHERE enabled AND round_id='%s'
        ORDER BY ordering",
        DPDatabase::escape($round->id));
    $q_res = DPDatabase::query($sql);

    while ($qd = mysqli_fetch_object($q_res)) {
        // $qd->name
        //     A human-readable name for this queue.
        //
        // $qd->project_selector
        //     An SQL condition that defines the kind of project
        //     that can be released by this queue.
        //
        // $qd->release_criterion
        //     A PHP condition that specifies the circumstances under which
        //     this queue can release a project.
        //     It can reference 'projects' and 'pages', which denote (respectively)
        //      -- the number of projects matching this queue's project_selector
        //         that are currently available in the round, and
        //      -- their total number of pages in any state other than 'saved'.

        $qd->cooked_project_selector = cook_project_selector($qd->project_selector);

        // Release available projects of a given type (defined by $qd->project_selector)
        // as long as the $qd->release_criterion is true for projects of that type.

        echo "\n";
        echo "Considering {$qd->name} projects...\n";
        echo "\n";
        echo "    For projects matching: {$qd->cooked_project_selector}\n";
        echo "    release if: {$qd->release_criterion}\n";

        // Does the $qd->release_criterion allow us to release any projects?
        if (! AP_evaluate_criteria($round, $qd->cooked_project_selector, $qd->release_criterion)) {
            echo "    The release criterion fails, so no projects released in this category.\n";
            continue;
        }

        // We are allowed to release projects!

        $n_projects = maybe_release_projects(
            $round,
            $qd,
            true, // $observe_release_restrictions
            false // $release_at_most_one
        );

        if ($n_projects->waiting == 0) {
            echo "    Could have released some projects in this category, but none were waiting!\n";
            // email someone?
            continue;
        }

        if ($n_projects->released == 0) {
            // At this point, we know that:
            // the queue's release_criterion succeeded
            // (so it wants to release some projects),
            // and there are projects in this queue
            // (wanting to be released),
            // but every one of them must either
            // have been rejected by the release_restrictor, or
            // had something go wrong in attempt_to_release().
            //
            // If there are still active pages of this type in the round,
            // there's no cause to panic.
            // But if there aren't, we'll try to release a single project
            // via this queue, as a sort of "emergency valve",
            // even though this will mean violating the release restrictions.

            if (AP_evaluate_criteria($round, $qd->cooked_project_selector, 'pages == 0')) {
                echo "    Attempting an emergency valve release: no pages available, queue non-empty\n\n";

                $n_projects = maybe_release_projects(
                    $round,
                    $qd,
                    false, //$observe_release_restrictions
                    // It *must* ignore release restrictions
                    // to have any hope of releasing anything.
                    true // $release_at_most_one
                );
            }
        }

        echo "    Released <b>{$n_projects->released}</b> {$qd->name} projects.\n";
    }

    AP_teardown($round);

    echo "\n";
    echo "Finished round {$round->id}\n";
    echo "XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX";
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function maybe_release_projects(
    $round,
    $qd,
    $observe_release_restrictions,
    $release_at_most_one
) {
    $n_projects = new StdClass();

    $and_extra_condition = ($qd ? "AND ($qd->cooked_project_selector)" : "");
    $q_name = ($qd ? $qd->name : "");

    // set of candidates for release:
    // Projects that are in this round's waiting state,
    // don't have a hold in that state,
    // and, if $qd is set, satisfy its project selector.

    // $cooked_project_selector can contain '%'s so we need to build the
    // escaped state selector separately.
    $escaped_state = sprintf(
        "state = '%s'",
        DPDatabase::escape($round->project_waiting_state));

    $sql = "
        SELECT *
        FROM projects
            LEFT OUTER JOIN project_holds USING (projectid, state)
        WHERE $escaped_state $and_extra_condition
            AND project_holds.state IS NULL
        ORDER BY modifieddate ASC, nameofwork ASC
    ";
    $waiting_res = DPDatabase::query($sql);

    $n_projects->waiting = mysqli_num_rows($waiting_res);

    echo "\n";
    if ($qd) {
        echo "    There are {$n_projects->waiting} projects waiting for release in this queue.\n";
    } else {
        echo "There are {$n_projects->waiting} projects waiting for release in this round.\n";
    }

    $n_projects->considered = 0;
    $n_projects->blocked = 0;
    $n_projects->failed = 0;
    $n_projects->released = 0;

    while ($project = mysqli_fetch_assoc($waiting_res)) {
        echo "\n";
        echo "    considering {$project['projectid']} \"{$project['nameofwork']}\"...\n";
        $n_projects->considered++;

        if ($observe_release_restrictions) {
            assert(isset($round->release_restrictor));

            if (!$round->release_restrictor->approves_project($project)) {
                // Messages have been echoed.
                $n_projects->blocked++;
                // Go to the next project in the list.
                continue;
            }
        }

        if (!attempt_to_release($round, $project, $q_name)) {
            // Something went wrong. Error messages have been output.
            $n_projects->failed++;
            // Go to the next project in the list.
            continue;
        }

        // ----------------------------------------------------

        echo "        Project released!\n";

        $n_projects->released++;

        if (isset($round->release_restrictor)) {
            $round->release_restrictor->update_for_released_project($project);

            // Note that we must update the release_restrictor
            // whether or not $observe_release_restrictions is set:
            // even if *this* call to maybe_release_projects()
            // isn't observing release_restrictions,
            // some future call (in this round) probably will be.
        }

        if ($qd) {
            // Update active_page_counts
            // (The release of this project will affect the release-ability
            // of other projects in this queue, and possibly of projects
            // in other queues.)
            AP_add_project($round, $project['projectid']);
        }

        // ----------------------------------------------------

        // Should we try to release any more projects?

        if ($release_at_most_one) {
            break;
        } else {
            if ($qd) {
                // Keep releasing projects until the queue's release_criterion fails,
                // or we run out of projects.

                if (! AP_evaluate_criteria($round, $qd->cooked_project_selector, $qd->release_criterion)) {
                    echo "    The release criterion now fails, so no further projects released in this category.\n";
                    break;
                }
            } else {
                // Keep releasing until we run out of projects.
            }
        }
    }

    if ($n_projects->waiting > 0) {
        echo "\n";
        echo sprintf("    Of the %d projects that were waiting...\n", $n_projects->waiting);
        echo sprintf("        %3d projects were blocked by release restrictions\n", $n_projects->blocked);
        echo sprintf("        %3d projects failed the pre-release check or the state transition\n", $n_projects->failed);
        echo sprintf("        %3d projects were released\n", $n_projects->released);
        echo sprintf("        %3d projects were not considered\n", $n_projects->waiting - $n_projects->considered);
        echo "\n";
    }

    return $n_projects;
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

class ReleaseRestrictor
// An object of this class is responsible for imposing various queue-independent
// restrictions on the auto-release of projects into a given round.
// (E.g., not too many by the same author, not too many with the same PM.)
{
    public $this_round_authors;
    public $this_round_pms;
    public $fresh_released_pms;

    public function __construct($round)
    {
        // ----------------------------------------------------------
        // First, get the set of all authors with works in this round
        $this->this_round_authors = [];
        $sql = sprintf("
                SELECT authorsname
                FROM projects
                WHERE state = '%s'
                ORDER BY authorsname",
                DPDatabase::escape($round->project_available_state));
        $author_res = DPDatabase::query($sql);
        while ($author_row = mysqli_fetch_assoc($author_res)) {
            $author = $author_row['authorsname'];
            @$this->this_round_authors[$author]++;
        }
        echo "\n";
        echo "    List of authors in this round:\n";
        foreach ($this->this_round_authors as $author => $one) {
            echo "        $author\n";
        }

        // ----------------------------------------------------------
        // Next, get the set of all PMs with works in this round
        $this->this_round_pms = [];
        $sql = sprintf("
                SELECT username
                FROM projects
                WHERE state = '%s'
                ORDER BY username",
                DPDatabase::escape($round->project_available_state));
        $pm_res = DPDatabase::query($sql);
        while ($pm_row = mysqli_fetch_assoc($pm_res)) {
            $pm = $pm_row['username'];
            @$this->this_round_pms[$pm]++;
        }
        echo "\n";
        echo "    List of pms in this round:\n";
        foreach ($this->this_round_pms as $pm => $one) {
            echo "        $pm\n";
        }

        // ----------------------------------------------------------
        // Keep track of which PMs get a book released in this particular execution of the script
        $this->fresh_released_pms = [];
    }

    // =========================================================================

    public function approves_project($project)
    {
        $is_special = !empty($project['special_code']);
        if ($is_special) {
            echo "        It's special, so it's exempt from release restrictions.\n";
            return true;
        }

        // ---------------------------------------------------------------------
        // Block the project's release if there are projects by its author available in this round.
        // If the author is 'Anonymous', 'Unknown', or 'Various', we block when there are 4 or more
        // by that author available, but otherwise, we block at even a single project by that author.

        // TODO: Add a flag to some queues that all have identical authors that allows
        // a queue-defined limit of identical authors - eg some of the periodicals that have short
        // issues

        $authorsname = $project['authorsname'];
        if ((($authorsname == 'Anonymous') && (@$this->this_round_authors[$authorsname] >= 4)) ||
            (($authorsname == 'Unknown') && (@$this->this_round_authors[$authorsname] >= 4)) ||
            (($authorsname == 'Various') && (@$this->this_round_authors[$authorsname] >= 4)) ||
            (array_key_exists($authorsname, $this->this_round_authors) && ($authorsname <> 'Anonymous') && ($authorsname <> 'Unknown') && ($authorsname <> 'Various'))) {
            echo "        Its author, $authorsname, already has a project(s) in this round.\n";
            return false;
        }

        // ---------------------------------------------------------------------
        // Block the project's release if its PM has 13 or more projects available in this round.

        $pm = $project['username'];
        if ((array_key_exists($pm, $this->this_round_pms)) && ($this->this_round_pms[$pm] >= 13)) {
            echo "        The PM, $pm, already has {$this->this_round_pms[$pm]} projects in this round.\n";
            return false;
        }

        // ---------------------------------------------------------------------
        // Block the project's release if its PM has already had a project released
        // in the current execution of this script.

        if (array_key_exists($pm, $this->fresh_released_pms)) {
            echo "        The PM, $pm, already has had a project released during this execution of the release script.\n";
            return false;
        }

        return true;
    }

    // =========================================================================

    public function update_for_released_project($project)
    {
        // special authors (anon, etc) have to have their counts incremented
        $authorsname = $project['authorsname'];
        if (array_key_exists($authorsname, $this->this_round_authors)) {
            $this->this_round_authors[$authorsname] += 1;
        } else {
            $this->this_round_authors[$authorsname] = 1;
        }

        // increment the count for this project's PM
        $pm = $project['username'];
        if (array_key_exists($pm, $this->this_round_pms)) {
            $this->this_round_pms[$pm] += 1;
        } else {
            $this->this_round_pms[$pm] = 1;
        }

        // keep track of which PMs have had a book released this execution of the script
        $this->fresh_released_pms[$pm] = 1;

        global $testing;
        if ($testing) {
            echo "\n";
            echo "    ((((\n";
            echo "    ReleaseRestrictor::update_for_released_project():\n";

            echo "        this_round_authors:\n";
            foreach ($this->this_round_authors as $authorsname => $count) {
                echo sprintf("            %2d %s\n", $count, $authorsname);
            }

            echo "        this_round_pms:\n";
            foreach ($this->this_round_pms as $pm => $count) {
                echo sprintf("            %2d %s\n", $count, $pm);
            }

            echo "        fresh_released_pms:\n";
            foreach ($this->this_round_pms as $pm => $_) {
                echo sprintf("            %s\n", $pm);
            }

            echo "    ))))\n";
        }
    }
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function AP_setup($round)
// Set up temporary table active_page_counts,
// containing a row for each project in this round.
// fields:
//     projectid
//     pages = number of pages yet to receive proofreading in this round
//
// This table is similar to projects.n_available_pages
// but contains slightly different information.
// Maybe they could be merged.
{
    // If we had one table with all page info,
    // we could set up this table with a single
    // CREATE TABLE ... SELECT ...
    // statement.
    // Instead, we have to loop over the active projects.

    // Create the table.
    $sql = "
        CREATE TEMPORARY TABLE active_page_counts
        (
            projectid  varchar(22),
            pages      INT(4)
        )";
    DPDatabase::query($sql);

    // Get a list of active projects.
    $sql = sprintf("
        SELECT projectid
        FROM projects
        WHERE state = '%s'",
        DPDatabase::escape($round->project_available_state));
    $projects_res = DPDatabase::query($sql);

    // Run through them and fill up the table.
    while ($project = mysqli_fetch_assoc($projects_res)) {
        AP_add_project($round, $project['projectid']);
    }
}

function AP_teardown($round)
{
    $sql = "DROP TABLE active_page_counts";
    DPDatabase::query($sql);
}

function AP_add_project($round, $projectid)
{
    validate_projectID($projectid);
    $sql = sprintf("
        INSERT INTO active_page_counts
        SELECT
            '$projectid',
            SUM( state != '%s' ) as pages
        FROM $projectid",
        DPDatabase::escape($round->page_save_state));
    DPDatabase::query($sql);
}

function AP_evaluate_criteria($round, $cooked_project_selector, $release_criterion)
{
    // Get the criterion-evaluation environment
    // (values for 'projects', 'pages')

    // $cooked_project_selector can contain '%'s so we need to build the
    // escaped state selector separately.
    $sum_projects = sprintf(
        "SUM(projects.state='%s')",
        DPDatabase::escape($round->project_available_state));

    $sql = "
        SELECT
            $sum_projects as projects,
            SUM(active_page_counts.pages) as pages
        FROM projects NATURAL JOIN active_page_counts
        WHERE $cooked_project_selector";
    $res = DPDatabase::query($sql);
    $env = mysqli_fetch_assoc($res);
    // print_r($env);

    return evaluate_expression($release_criterion, $env);
}

function evaluate_expression($expression, $env)
{
    $expr = $expression;
    foreach ($env as $id => $value) {
        if (is_null($value)) {
            $value = 0;
        }
        $expr = str_replace($id, $value, $expr);
    }
    // should check $expr for traps
    $result = eval("return ($expr);");

    if (true) {
        echo "\n";
        echo "    Evaluate '$expression' wrt {";
        foreach ($env as $id => $value) {
            echo " $id => $value,";
        }
        echo "} = '$expr' = $result\n";
    }

    return $result;
}
