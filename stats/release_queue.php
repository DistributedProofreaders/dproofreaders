<?php
$relPath = '../pinc/';
include_once($relPath.'base.inc');
include_once($relPath.'dpsql.inc');
include_once($relPath.'misc.inc'); // html_safe()
include_once($relPath.'project_states.inc');
include_once($relPath.'stages.inc');
include_once($relPath.'user_is.inc');
include_once($relPath.'theme.inc');
include_once($relPath.'release_queue.inc');

require_login();

if ($ordinary_users_can_see_queue_settings) {
    $user_can_see_queue_settings = true;
} else {
    // Only privileged users can see queue settings
    $user_can_see_queue_settings = user_is_a_sitemanager() || user_is_proj_facilitator();
}

$round_id = get_enumerated_param($_GET, 'round_id', null, array_keys($Round_for_round_id_), true);
$name = @$_GET['name'];

if (is_null($round_id)) {
    $title = _("Release Queues");
    output_header($title, NO_STATSBAR);
    echo "<h1>$title</h1>\n";

    echo _("Each round has its own set of release queues."), "\n";
    echo _("Please select the round that you're interested in:"), "\n";
    echo "<ul>\n";
    foreach (array_keys($Round_for_round_id_) as $round_id) {
        echo "<li><a href='release_queue.php?round_id=$round_id'>$round_id</a></li>\n";
    }
    echo "</ul>\n";
    return;
}

$round = get_Round_for_round_id($round_id);

if (!isset($name)) {
    $title = sprintf(_("Release Queues for Round '%s'"), $round_id);
    output_header($title, NO_STATSBAR);
    echo "<h1>$title</h1>\n";

    $errors = [];
    echo "<table class='themed theme_striped'>\n";
    {
        echo "<tr>";
        // TRANSLATORS: "Order" refers to a number used to sort items in a specific order
        echo "<th>", _("Order"), "</th>\n";
        echo "<th>", _("Enabled"), "</th>\n";
        echo "<th>", _("Name"), "</th>\n";
        echo "<th>", _("Current<br>length"), "</th>\n";
        if ($user_can_see_queue_settings) {
            echo "<th>", _("Project Selector"), "</th>\n";
            echo "<th>", _("Release Criterion"), "</th>\n";
            echo "<th>", _("Comment"), "</th>\n";
        }
        echo "</tr>\n";
    }

    $q_sql = sprintf("
        SELECT *
        FROM queue_defns
        WHERE round_id='%s'
        ORDER BY ordering",
        DPDatabase::escape($round_id)
    );
    $q_res = DPDatabase::query($q_sql);

    while ($qd = mysqli_fetch_object($q_res)) {
        $cooked_project_selector = cook_project_selector($qd->project_selector);
        $state_clause = sprintf(
            "state='%s'",
            DPDatabase::escape($round->project_waiting_state)
        );
        $c_sql = "
            SELECT COUNT(*)
            FROM projects
            WHERE ($cooked_project_selector)
                AND $state_clause
        ";
        try {
            $c_res = DPDatabase::query($c_sql);
            [$current_length] = mysqli_fetch_row($c_res);
            $ename = urlencode($qd->name);
            $link_cell = "<a href='release_queue.php?round_id=$round_id&amp;name=$ename'>" . html_safe($qd->name) . "</a>";
        } catch (DBQueryError $error) {
            $current_length = '???';
            $errors[] = sprintf(
                _('There is a syntax error in the project selector for #%1$d "%2$s"'),
                $qd->ordering,
                $qd->name);
            $link_cell = html_safe($qd->name);
        }

        echo "<tr>";
        echo "<td>$qd->ordering</td>\n";
        echo "<td>$qd->enabled</td>\n";
        echo "<td>$link_cell</td>\n";
        echo "<td>$current_length</td>\n";
        if ($user_can_see_queue_settings) {
            echo "<td>", html_safe($qd->project_selector), "</td>\n";
            echo "<td>", html_safe($qd->release_criterion), "</td>\n";
            echo "<td>", html_safe($qd->comment), "</td>\n";
        }
        echo "</tr>\n";
    }
    echo "</table>\n";

    foreach ($errors as $error) {
        echo "<p class='error'>$error</p>";
    }
} else {
    $sql = sprintf("
        SELECT *
        FROM queue_defns
        WHERE round_id='%s' AND name='%s'",
        DPDatabase::escape($round_id),
        DPDatabase::escape($name)
    );
    $qd = mysqli_fetch_object(DPDatabase::query($sql));
    if (!$qd) {
        die(html_safe("No such release queue '$name' in $round_id."));
    }
    $cooked_project_selector = cook_project_selector($qd->project_selector);
    $comment = $qd->comment;

    //// TRANSLATORS: %s is the name of this release queue.
    $title = sprintf(_("\"%s\" Release Queue"), html_safe($name));
    $title = preg_replace('/(\\\\)/', "", $title); // Unescape apostrophes, etc.
    // Suppress stats since this page is very wide
    output_header($title, NO_STATSBAR);
    echo "<h1>$title</h1>";

    // Add Back to to Release Queues link
    echo "<p><a href='".$code_url."/stats/release_queue.php?round_id=$round_id'>"._("Back to Release Queues")."</a></p>\n";

    if ($user_can_see_queue_settings) {
        echo "<p><b>" . _("Selector") . ":</b> $qd->project_selector</p>\n";
        if ($cooked_project_selector != $qd->project_selector) {
            echo "<p><b>" . _("Filled-in") . ":</b> $cooked_project_selector</p>\n";
        }
        echo "<p><b>" . _("Comment") . ":</b> $comment</p>\n";
    }


    $comments_url1 = DPDatabase::escape("<a href='$code_url/project.php?id=");
    $comments_url2 = DPDatabase::escape("'>");
    $comments_url3 = DPDatabase::escape("</a>");

    dpsql_dump_themed_query("
        SELECT

            concat('$comments_url1',projectID,'$comments_url2', nameofwork, '$comments_url3') as '"
                . DPDatabase::escape(_("Title")) . "',
            authorsname as '" . DPDatabase::escape(_("Author")) . "',
            language    as '" . DPDatabase::escape(_("Language")) . "',
            genre       as '" . DPDatabase::escape(_("Genre")) . "',
            difficulty  as '" . DPDatabase::escape(_("Difficulty")) . "',
            username    as '" . DPDatabase::escape(_("Project Manager")) . "',
            FROM_UNIXTIME(modifieddate) as '"
                . DPDatabase::escape(_("Date Last Modified")) . "',
            IF(ISNULL(project_holds.state),'&nbsp;','Y') AS '" . DPDatabase::escape(_("Hold?")) . "'
        FROM projects
            LEFT OUTER JOIN project_holds USING (projectid, state)
        WHERE ($cooked_project_selector)
            AND state='{$round->project_waiting_state}'
        ORDER BY modifieddate, nameofwork
    ");
}
