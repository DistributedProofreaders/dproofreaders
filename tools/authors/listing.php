<?php
$relPath = '../../pinc/';
include_once($relPath.'base.inc');
include_once($relPath.'theme.inc');
include_once($relPath.'misc.inc'); // html_safe()
include_once('authors.inc');
include_once($relPath.'SortUtility.inc');
include_once($relPath.'BrowseUtility.inc');
include_once('menu.inc');
include_once('search.inc');

require_login();

$title = _("Authors");
output_header($title);
echo "<h1>$title</h1>";

echo_menu();

$message = @$_GET['message'];
if (isset($message)) {
    echo html_safe($message) . '<br>';
}

$sortUtility = new SortUtility('authors_listing');

prepare_search();

echo_search_form();

echo "<h2>" . get_search_title() . "</h2>";

$can_edit = user_is_PM() || user_is_authors_db_manager();

$result = search();

$browseUtility = new BrowseUtility($result);

// "Displaying entries x-y of z"
echo '<p>' . $browseUtility->getDisplayingString() . '</p>';

// "Previous" and/or "Next" links?
$prev_next_links = '';
if ($browseUtility->isPreviousBrowseAvailable()) {
    $prev_next_links = "<a href='listing.php?$query".$browseUtility->getPreviousBrowseQueryString()."'>&lt;- "
                       . _('Previous') . '</a> &nbsp; &nbsp; &nbsp; ';
}
if ($browseUtility->isNextBrowseAvailable()) {
    $prev_next_links .= "<a href='listing.php?$query".$browseUtility->getNextBrowseQueryString()."'>"
                           . _('Next') . ' -&gt;</a>';
}
if ($prev_next_links != '') {
    echo "<p>$prev_next_links</p>";
}

// table of search results
echo '<table class="themed"><tr>';

// print headers
// links to allow (asc/desc) sorting

echo "<th><a href='listing.php?$query".$sortUtility->getQueryStringForSortableValue($sort_author_id).
     "'>" . _("ID") . "</a></th>\n";
echo "<th><a href='listing.php?$query".$sortUtility->getQueryStringForSortableValue($sort_last_name).
     "'>" . _("Last name") . "</a></th>\n";
echo "<th><a href='listing.php?$query".$sortUtility->getQueryStringForSortableValue($sort_other_names).
     "'>" . _("Other name(s)") . "</a></th>\n";
echo "<th><a href='listing.php?$query".$sortUtility->getQueryStringForSortableValue($sort_born).
     "'>" . _("Born") . "</a></th>\n";
echo "<th><a href='listing.php?$query".$sortUtility->getQueryStringForSortableValue($sort_dead).
     "'>" . _("Deceased") . "</a></th>\n";

if ($can_edit) {
    echo "<th>" . _("Edit") . "</th>\n";
}

echo "\n";

$count = $browseUtility->getRowCountToList();
$i = 0;

while ($i++ < $count && $row = @mysqli_fetch_array($result)) {
    $id = $row['author_id'];
    echo "<tr><td>$id</td><td><a href=\"author.php?author_id=$id\">" .
         html_safe($row['last_name']) . "</a></td><td>" .
         html_safe($row['other_names']) . "</td><td>" .
         format_date_from_array($row, 'b') . '</td><td>' .
         format_date_from_array($row, 'd') . '</td>' .
         ($can_edit ? ("<td><a href='add.php?author_id=$id'>" .
         _('Edit') . '</a></td>') : '') . "</tr>\n";
}

echo '</table>';

if ($prev_next_links != '') {
    echo "<p>$prev_next_links</p>";
}

echo '<br>';

$browseUtility->echoCountSelectionList();
