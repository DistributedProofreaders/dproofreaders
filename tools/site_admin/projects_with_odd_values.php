<?php
$relPath = "./../../pinc/";
include_once($relPath.'base.inc');
include_once($relPath.'slim_header.inc');
include_once($relPath.'iso_lang_list.inc');
include_once($relPath.'genres.inc'); // load_genre_translation_array()
include_once($relPath.'Project.inc'); // get_project_difficulties()
include_once($relPath.'user_is.inc');

require_login();

if (!user_is_a_sitemanager() && !user_is_proj_facilitator()) {
    die(_("You are not authorized to access this page."));
}

$title = _("Projects with 'odd' values");
slim_header($title);
echo "<h1>$title</h1>";

echo "<p>" . _("This page shows projects that contain 'odd' values for fields with well-known values.") . "</p>";

$lc = load_common_values();

$sections = [];
foreach (array_keys($lc) as $property) {
    $sections[] = "<a href='#$property'>$property</a>";
}
echo "<p>" . join(" | ", $sections) . "</p>";

foreach (load_projects_with_bad_values($lc) as $property => $projects) {
    echo "<a id='$property'>\n";
    echo "<h2>$property</h2>\n";
    echo "<table class='basic striped'>\n";
    echo "<tr>";
    echo "<th>" . _("Value") . "</th>";
    echo "<th>" . _("Project") . "</th>";
    echo "</tr>";

    foreach ($projects as $project) {
        echo "<tr>\n";
        echo "<td>{$project->$property}</td>\n";
        $label = $project->nameofwork ? $project->nameofwork : "<i>" . _("No project title") . "</i>";
        echo "<td><a href='$project->page_url'>$label</a></td>\n";
        echo "</tr>\n";
    }
    echo "</table>\n";
}

// ---------------------------------------------------------------------------

function load_common_values()
{
    $lc = [];

    $lc['language'] = [];
    foreach (get_iso_language_list() as $lang) {
        $lc['language'][strtolower($lang['lang_name'])] = 1;
    }

    $lc['genre'] = [];
    foreach (array_keys(load_genre_translation_array()) as $genre) {
        $lc['genre'][strtolower($genre)] = 1;
    }

    $lc['difficulty'] = [];
    foreach (get_project_difficulties() as $value => $label) {
        $lc['difficulty'][$value] = 1;
    }

    $lc['special_code'] = [];
    foreach (load_special_days() as $special_day) {
        $lc['special_code'][strtolower($special_day["spec_code"])] = 1;
    }

    $lc['special_code'][''] = 1;
    for ($m = 1; $m <= 12; $m++) {
        for ($d = 1; $d <= 31; $d++) {
            foreach (['birthday', 'otherday'] as $x) {
                $v = sprintf('%s %02d%02d', $x, $m, $d);
                $lc['special_code'][$v] = 1;
            }
        }
    }

    return $lc;
}

function load_projects_with_bad_values($lc)
{
    $projects_with_bad = [];

    $sql = "
        SELECT projectid, nameofwork, language, genre, difficulty, special_code
        FROM projects
        ORDER BY projectid
    ";
    $res = DPDatabase::query($sql);
    while ($row = mysqli_fetch_assoc($res)) {
        $project = new Project($row);

        // language
        foreach ($project->languages as $lang) {
            if (! array_key_exists(strtolower($lang), $lc['language'])) {
                $projects_with_bad['language'][] = $project;
            }
        }

        foreach (['genre', 'difficulty', 'special_code'] as $property) {
            if (! array_key_exists(strtolower($project->$property), $lc[$property])) {
                $projects_with_bad[$property][] = $project;
            }
        }
    }

    return $projects_with_bad;
}
