<?php
include_once($relPath.'site_vars.php');
include_once($relPath.'project_states.inc');

function showstartexts($etext_limit,$type) {
    global $code_url;

    if ($type == "bronze") {
        $state = SQL_CONDITION_BRONZE;
        $content="proofing";
        $category = _("%s in Proofreading");
        $description = _("These books are currently being processed through our site; sign in and start helping!");
    } elseif ($type == "silver") {
        $state = SQL_CONDITION_SILVER;
        $content="postprocessing";
        $category = _("%s In Progress");
        $description = _("These books are undergoing their final checks before being assembled into a completed e-book.");
    } elseif ($type == "gold") {
        $state = SQL_CONDITION_GOLD;
        $content="posted";
        $category = _("%s in Completed");
        $description =  _("These books have been processed through our site and posted to the Project Gutenberg archive.");
    }

    $total = mysql_num_rows(mysql_query("SELECT projectid FROM projects WHERE $state"));

    // Display star graphic
    echo "<img src='$code_url/graphics/{$type}_star.jpg' height='38' width='40' alt='$type star'>\n";

    // Display count and category as a link
    echo "<span class='progress-count'>";
    echo "<a href='list_etexts.php?x=".substr($type,0,1)."&amp;sort=5'>";
    echo sprintf($category, number_format($total)) . "</a>.</span><br>\n";

    // Display feed buttons
    echo "
        <a href='$code_url/feeds/backend.php?content=$content'>
        <img src='$code_url/graphics/xml.gif' width='36' height='14' alt='[XML]'></a>
        <a href='$code_url/feeds/backend.php?content=$content&amp;type=rss'>
        <img src='$code_url/graphics/rss.gif' width='36' height='14' alt='[RSS]'></a>
	\n";

    // Display description text
    echo "<p>$description</p>";
}

// vim: sw=4 ts=4 expandtab
?>
