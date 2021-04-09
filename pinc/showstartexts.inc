<?php
include_once($relPath.'site_vars.php');
include_once($relPath.'project_states.inc');

function showstartexts($etext_limit,$type) {
    global $code_url;

    if ($type == "bronze") {
        $aria_label_attr = attr_safe(_("Bronze Star"));
        $state = SQL_CONDITION_BRONZE;
        $content="proofing";
        $category = _("%s in Proofreading");
        $description = _("These books are currently being processed through our site; sign in and start helping!");
    } elseif ($type == "silver") {
        $aria_label_attr = attr_safe(_("Silver Star"));
        $state = SQL_CONDITION_SILVER;
        $content="postprocessing";
        $category = _("%s In Progress");
        $description = _("These books are undergoing their final checks before being assembled into a completed e-book.");
    } elseif ($type == "gold") {
        $aria_label_attr = attr_safe(_("Gold Star"));
        $state = SQL_CONDITION_GOLD;
        $content="posted";
        $category = _("%s in Completed");
        $description =  _("These books have been processed through our site and posted to the Project Gutenberg archive.");
    }

    $total = mysqli_num_rows(mysqli_query(DPDatabase::get_connection(), "SELECT projectid FROM projects WHERE $state"));

    echo "<div class='star-text-summary'>";

    echo "<div class='star-header'>";

    // Display star
    echo "<div class='star-$type' aria-label='$aria_label_attr'>★</div>";

    // Display count and category as a link
    echo "<span class='large'>";
    echo "<a href='list_etexts.php?x=".substr($type,0,1)."&amp;sort=5'>";
    echo sprintf($category, number_format($total)) . "</a></span><br>\n";

    // Display feed buttons
    echo "
        <a href='$code_url/feeds/backend.php?content=$content'>
        <img src='$code_url/graphics/xml.gif' width='36' height='14' alt='[XML]'></a>
        <a href='$code_url/feeds/backend.php?content=$content&amp;type=rss'>
        <img src='$code_url/graphics/rss.gif' width='36' height='14' alt='[RSS]'></a>
        \n";

    echo "</div>";

    // Display description text
    echo "<div class='star-description'>";
    echo "<p>$description</p>";
    echo "</div>";

    echo "</div>";
}

?>
