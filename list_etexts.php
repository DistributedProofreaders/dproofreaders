<?php
$relPath = "./pinc/";
include_once($relPath.'base.inc');
include_once($relPath.'theme.inc');
include_once($relPath.'project_states.inc');
include_once($relPath.'list_projects.inc');

$x = get_enumerated_param($_GET, 'x', 'g', ['g', 's', 'b']);
$sort = get_integer_param($_GET, 'sort', 0, 0, 5);
$per_page = get_integer_param($_GET, 'per_page', 20, 1, 100);
$offset = get_integer_param($_GET, 'offset', 0, 0, null);

if ($x == "g") {
    $type = "gold";
    $title = _("Completed Gold E-Texts");
    $state = SQL_CONDITION_GOLD;
    $group_clause = "GROUP BY postednum";
    $info = [
        _("Below is the list of Gold e-texts that have been produced on this site. Gold e-texts are books that have passed through all phases of proofreading, formatting, and post-processing. They have been submitted to Project Gutenberg and are now available for your enjoyment and download."),
        _("These e-texts are the product of hundreds of hours of labor donated by all of our volunteers. The list is sorted with the most recently submitted e-texts at the top. You can sort them based upon your own preferences by clicking below. Enjoy!!"),
    ];
    $rss_content = "posted";
} elseif ($x == "s") {
    $type = "silver";
    $title = _("In Progress Silver E-Texts");
    $state = SQL_CONDITION_SILVER;
    $group_clause = "";
    $info = [
        _("Below is the list of Silver e-texts that have almost completed processing on our site. Silver e-texts are books that have passed through all phases of proofreading and formatting and are now in the post-processing phase. Post-processing is the final assembly stage in which one volunteer performs a series of checks for consistency and correctness before the e-book is submitted to Project Gutenberg for your enjoyment and download."),
        _("These e-texts are the product of hundreds of hours of labor donated by our volunteers, and are in one of our post-processing states. The list is sorted by date, with the most recent to have changed state sorted to the top. You can re-sort them based on your own preferences. Enjoy!!"),
    ];
    $rss_content = "postprocessing";
} elseif ($x == "b") {
    $type = "bronze";
    $title = _("Now Proofreading Bronze E-Texts");
    $state = SQL_CONDITION_BRONZE;
    $group_clause = "";
    $info = [
        _("Below is the list of Bronze e-texts that are currently available for proofreading on this site. Bronze e-texts are those that our volunteers are now proofreading or formatting. After going through three proofreading and two formatting rounds, the e-text then goes to an experienced volunteer for final assembly (post-processing), after which the e-text is submitted to Project Gutenberg for your enjoyment and download."),
        _("Some of these e-texts are just beginning their journeys, others are almost done, and many variations in between. If you would like to help, log in and choose an activity from the navigation bar to see the lists of projects you are eligible to work in."),
        _("The list is sorted by date, with the most recent to have changed state sorted to the top, and all rounds mixed together. You can re-sort them based on your own preferences."),
    ];
    $rss_content = "proofing";
} else {
    die("x parameter must be 'g', 's', or 'b'. ('$x')");
}

// Tell RSS feed readers which RSS feed is connected to this page
$attr_safe_title = attr_safe($title);
$extra_args = [
    "head_data" => "<link rel='alternate' type='application/rss+xml' href='$code_url/feeds/backend.php?content=$rss_content&type=rss' title='$attr_safe_title' />",
];

output_header($title, NO_STATSBAR, $extra_args);

echo "<h1>$title</h1>";

$listsuffix = "&amp;sort=$sort&amp;per_page=$per_page";
$menu = [];
foreach (["g" => _("Gold"), "s" => _("Silver"), "b" => _("Bronze")] as $key => $type_option) {
    if ($x == $key) {
        $menu[] = $type_option;
    } else {
        $menu[] = "<a href='list_etexts.php?x=$key$listsuffix'>$type_option</a>";
    }
}
echo "<p>" .  implode(" | ", $menu) . "</p>";
echo "<div class='star-$type' style='float: left; width: 1.5em;'>★</div>";
echo surround_and_join($info, "<p>", "</p>", "");

$listurl = "list_etexts.php?x=$x&amp;per_page=$per_page&amp;offset=$offset";
echo sprintf(_("<i>Title:</i> <a href='%1\$s'>asc</a> or <a href='%2\$s'>desc</a> | "), "$listurl&amp;sort=0", "$listurl&amp;sort=1");
echo sprintf(_("<i>Author:</i> <a href='%1\$s'>asc</a> or <a href='%2\$s'>desc</a> | "), "$listurl&amp;sort=2", "$listurl&amp;sort=3");
echo sprintf(_("<i>Date:</i> <a href='%1\$s'>asc</a> or <a href='%2\$s'>desc</a>"), "$listurl&amp;sort=4", "$listurl&amp;sort=5");

echo "<hr class='divider'>";

$sortlist = [
    "ORDER BY nameofwork asc",
    "ORDER BY nameofwork desc",
    "ORDER BY authorsname asc",
    "ORDER BY authorsname desc",
    "ORDER BY modifieddate asc",
    "ORDER BY modifieddate desc",
];

list_projects($state, $sortlist[$sort], $group_clause, "list_etexts.php?x=$x&amp;sort=$sort&amp;", $per_page, $offset);
