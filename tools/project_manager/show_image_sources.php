<?php
// Display lists of image sources, or lists of projects that used image sources
// List contents vary with user permissions

$relPath = '../../pinc/';
include_once($relPath.'base.inc');
include_once($relPath.'theme.inc');
include_once($relPath.'project_states.inc');
include_once($relPath.'pg.inc');
include_once($relPath.'Project.inc'); // load_image_sources()

require_login();

$which = get_enumerated_param($_GET, 'which', 'DONE', ['ALL', 'DONE', 'INPROG']);
$imso_code = $_GET['name'] ?? null;

if (!$imso_code) {
    $header_text = _("Image Sources");
    output_header($header_text, NO_STATSBAR);

    echo "<h1>{$header_text}</h1>\n";

    $image_sources = load_image_sources();
    $image_sources_stats = load_image_source_stats();

    echo "<table class='image_source'>\n";

    // Column Headers
    echo "<tr>\n";
    echo "<th style='width: 15%'>" . _("Name in Dropdown") . "</th>";
    echo "<th colspan='2'>" . _("Image Source Details") . "</th>\n";
    echo "<th style='width: 15%'>" . _("Works: In Progress / Completed / Total") . "</th>\n";
    echo "</tr>\n";

    foreach ($image_sources as $image_source_id => $row) {
        // skip the source if the user isn't authorized to see it
        if (!can_user_see_image_source($row)) {
            continue;
        }

        echo "<tr class='first'>\n";
        echo "<td rowspan='4' class='center-align'>{$row['display_name']}";
        // Show the status if source is not enabled
        // KEY: -1 = Pending Review, 0 = Disabled, 1 = Enabled
        if ($row['is_active'] != 1) {
            $status_text = "";
            echo "<br><br>";
            echo "<small>";
            if ($row['is_active'] == -1) {
                $status_text = _("Pending Review");
            }
            if ($row['is_active'] == 0) {
                $status_text = _("Disabled");
            }
            echo "[{$status_text}]</small>";
        }
        echo "</td>\n";

        echo "<th class='label'>" . _("Full Name") . ":</th>\n";

        $source_fullname = $row['full_name'];
        // Since we apparently allow an empty full_name, check for that
        // and try to recover by using the display name, flagged with
        // an asterisk so even if display_name is blank, we get a visible
        // indication of the issue (and a visible link when applicable).
        if ($row['full_name'] == '') {
            $source_fullname .= "<span title='"
                . attr_safe(_("Record is missing a value for Full Name; Display Name used instead."))
                . "'>*</span>" . $row['display_name'];
        }

        if (!is_null($row['url'])) {
            $link_name = "<br><a class='sourcelink' href='" . attr_safe($row['url']) . "'>{$row['url']}</a>";
        } else {
            $link_name = "";
        }

        echo "<td class='title'>$source_fullname ${link_name}</td>";

        $row['projects_total'] = $image_sources_stats[$image_source_id]['projects_total'] ?? 0;
        $row['projects_completed'] = $image_sources_stats[$image_source_id]['projects_completed'] ?? 0;
        $projects_inprogress = $row['projects_total'] - $row['projects_completed'];

        echo "<td rowspan='4' class='center-align'>";
        $p_link = $projects_inprogress;
        if ($projects_inprogress > 0) {
            $p_link = "<a href='show_image_sources.php?name="
                . $row['code_name']
                . "&amp;which=INPROG'>$projects_inprogress</a>";
        }
        echo $p_link;
        echo " / "; // This is a divider slash between counts
        $c_link = $row['projects_completed'];
        if ($row['projects_completed'] > 0) {
            $c_link = "<a href='show_image_sources.php?name="
                . $row['code_name']
                . "&amp;which=DONE'>{$row['projects_completed']}</a>";
        }
        echo $c_link;
        echo "<br><br>";
        $t_link = $row['projects_total'];
        if ($row['projects_total'] > 0) {
            $t_link = "<a href='show_image_sources.php?name="
                . $row['code_name']
                . "&amp;which=ALL'>{$row['projects_total']}</a>";
        }
        // TRANSLATORS: %s is a number
        echo sprintf(_("%s in total"), $t_link);
        echo "</td>\n";
        echo "</tr>\n";

        echo "<tr>\n";
        echo "<th class='label'>" . _("Image Policies") . ":</th>\n";
        echo "<td>";
        switch ($row['ok_show_images']) {
            case 1:
                echo _("Images can be published.");
                break;
            case 0:
                echo _("Images cannot be published.");
                break;
            case -1:
            default:
                echo _("Image publishing policy is unknown.");
                break;
        }
        echo " "; // Space between policy statements
        switch ($row['ok_keep_images']) {
            case 1:
                echo _("Images can be stored.");
                break;
            case 0:
                echo _("Images cannot be stored.");
                break;
            case -1:
            default:
                echo _("Image storage policy is unknown.");
                break;
        }
        echo "</td>";
        echo "</tr>\n";

        echo "<tr>\n";
        echo "<th class='label'>" . _("Description") . ":</th>\n";
        echo "<td>{$row['public_comment']}</td>";
        echo "</tr>\n";

        echo "<tr>\n";
        echo "<th class='label'>" . _("Notes") . ":</th>\n";
        echo "<td>";
        echo html_safe($row['internal_comment']);
        echo "</td>";
        echo "</tr>\n";
    }

    echo "</table>\n";
} else {
    $image_sources = load_image_sources();
    $imso = $image_sources[$imso_code] ?? [];

    if ($imso && can_user_see_image_source($imso)) {
        $base_link = "<a href='show_image_sources.php?name=%s&amp;which=%s'>%s</a>";
        $all_link = sprintf($base_link, $imso_code, "ALL", pgettext("all sources", "All"));
        $inprog_link = sprintf($base_link, $imso_code, "INPROG", _("In Progress"));
        $done_link = sprintf($base_link, $imso_code, "DONE", _("Completed"));

        switch ($which) {
            case 'ALL':
                $where_cls = " AND state != 'project_delete'";
                $title = sprintf(_("All Ebooks being produced with images from %s"), $imso['full_name']);
                $links_list = $inprog_link . ", " . $done_link . ".";
                break;
            case 'INPROG':
                $where_cls = " AND state != 'project_delete' AND state != 'proj_submit_pgposted'";
                $title = sprintf(_("In Progress Ebooks being produced with images from %s"), $imso['full_name']);
                $links_list = $all_link . ", " . $done_link . ".";
                break;
            case 'DONE':
            default:
                $where_cls = " AND  ".SQL_CONDITION_GOLD." ";
                $title = sprintf(_("Completed Ebooks produced with images from %s"), $imso['full_name']);
                $links_list = $inprog_link . ", " . $all_link . ".";
        }

        $description = $imso['public_comment'];
        $internal_notes = $imso['internal_comment'];
        $info_url = sprintf("<a href='%s'>%s</a>", attr_safe($imso['url']), html_safe($imso['url']));

        output_header($title, NO_STATSBAR);

        echo "<h1>$title</h1>\n";

        echo "<p>";
        echo sprintf(
            _("See other lists of Ebooks being produced with images from %s: "),
            $imso['full_name']
        );
        echo $links_list;
        echo "</p>";

        echo "<p><a href='show_image_sources.php'>"
            . _("Back to the full listing of Image Sources")
            . "</a></p>";

        echo "<table class='image_source'>\n";
        echo "<tr>";
        echo "<td colspan='5' class='title'>";
        echo "<h2>{$imso['full_name']}</h2>";
        echo "<p><b>" . _("URL") . ":</b> $info_url</p>\n\n";

        echo "<p><b>" . _("Description") . ":</b> $description</p>\n";

        echo "<p><b>" . _("Internal Notes") . ":</b> $internal_notes</p>\n";
        echo "</td></tr>\n";

        $sql = sprintf("
            SELECT
                projectid, nameofwork, authorsname,
                genre, language, postednum
            FROM projects
            WHERE image_source = '%s' ".$where_cls."
            ORDER BY nameofwork
            ", DPDatabase::escape($imso_code));
        $result = DPDatabase::query($sql);

        echo "<tr>";
        echo "<th>" . _("Title") . "</th>";
        echo "<th>" . _("Author") . "</th>";
        echo "<th>" . _("Genre") . "</th>";
        echo "<th>" . _("Language") . "</th>";
        if ($which != "INPROG") {
            echo "<th>" . _("PG Number") . "</th>";
        }
        echo "</tr>\n";

        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>\n";
            echo "<td>";
            echo "<a href='$code_url/project.php?id=" .
                $row['projectid'] . "'>" . html_safe($row['nameofwork']) . "</a>";
            echo "</td>";
            echo "<td>";
            echo html_safe($row['authorsname']);
            echo "</td>";
            echo "<td class='center-align'>";
            echo html_safe($row['genre']);
            echo "</td>";
            echo "<td class='center-align'>";
            echo html_safe($row['language']);
            echo "</td>";

            // For In Progress, suppress final column since it conveys no info
            if ($which != "INPROG") {
                echo "<td class='center-align'>";
                if (!is_null($row['postednum'])) {
                    echo get_pg_catalog_link_for_etext($row['postednum'], $row['postednum']);
                } else {
                    echo _("In Progress");
                }
                echo "</td>";
            }
            echo "</tr>\n";
        }
        echo "</table>\n";
    } else {
        $title = _("Unknown Image Source Code");
        output_header($title, NO_STATSBAR);

        echo "<h1>$title</h1>\n";

        echo  "<p><a href='show_image_sources.php'>"._("Back to the full listing of Image Sources")."</a></p><br><br>";
    }
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function load_image_source_stats()
{
    $stats = [];
    $sql = "
        SELECT
            image_source,
            count(distinct  projectid) as projects_total,
            sum(".SQL_CONDITION_GOLD.") as projects_completed
            FROM projects
            WHERE state != 'project_delete'
            AND image_source != ''
            GROUP BY image_source
    ";
    $result = DPDatabase::query($sql);
    while ($row = mysqli_fetch_assoc($result)) {
        $stats[(string)$row['image_source']] = $row;
    }
    mysqli_free_result($result);
    return $stats;
}
