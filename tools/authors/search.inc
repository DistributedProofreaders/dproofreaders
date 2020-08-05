<?php
include_once($relPath."misc.inc"); // get_enumerated_param(), attr_safe(), html_safe()

// Functions to search authors, display search fields.

// At creation, used by listing.php and manage.php .

// $query is built. It contains last_name & other_names
// if possible, so that a search can be passed on when
// re-sorting / clicking "previous" / "next"
// It also possibly contains 'view'-argument for manage.php
// $query always ends in '&' unless empty.
// $query_without_view is $query without 'view=...&'

// Using a SortUtility-object to keep track of the sortable
// columns. URL '?orderby=dead' sorts by 'dead', i.e. the
// db-table-columns 'dyear, dmonth, dday'

function prepare_search() {
    global $last_name, $other_names,
           $sql_what_to_list, $human_what_to_list, $human_order,
           $query, $query_without_view, $sortUtility,
           $sort_author_id, $sort_last_name, $sort_other_names,
           $sort_born, $sort_dead;

    $view = get_enumerated_param($_REQUEST, 'view', null, array('enabled', 'disabled', 'all'), true);

    // Create columns
    $sort_author_id = new SortableValue('author_id', 'Id');
    $sort_last_name = new SortableValue('last_name', 'Last name');
    $sort_other_names = new SortableValue('other_names', 'Other name(s)');
    $sort_born = new SortableValue('born', 'Birth', 'byear,bmonth,bday,bcomments');
    $sort_dead = new SortableValue('dead', 'Death', 'dyear,dmonth,dday,dcomments');

    $sortUtility->setSortableValues($sort_author_id,
                                    $sort_last_name,
                                    $sort_other_names,
                                    $sort_born,
                                    $sort_dead);

    // if no ordering is requested, sort by ID (ascending)
    $sortUtility->setSortingRule($sort_author_id);

    // when sorting, sort by ID at a tie
    $sortUtility->setPrimaryValue($sort_author_id);

    // what to list: 'All' or 'Search results'
    // sql equivalent: 1 or more advanced query
    // query is the query to append to the URL
    // (basically a copy of what we got)

    $sql_what_to_list = '';
    if (isset($_REQUEST['last_name'])) {
        $human_what_to_list = _('Search Results');
        $sql_what_to_list = '';
        $query = '';
        foreach (array('last_name', 'other_names') as $key) {
            $$key = html_safe($_REQUEST[$key]);
            if ($$key != '')
                $sql_what_to_list .= " AND $key LIKE '" . sql_like_encode($_REQUEST[$key]) . "'";
            $query .= "&$key=$_REQUEST[$key]";
        }
        $sql_what_to_list = substr($sql_what_to_list, 5);
        $query = substr($query, 1) . '&';
    }
    if ($sql_what_to_list == '') {
        // acts as an else, but also catches those cases where there's an empty search
        $last_name = $other_names = '';
        $human_what_to_list = pgettext("all authors", "All");
        $sql_what_to_list = "1";
        $query = '';
    }

    // Most likely, we'll now wrap $sql_what_to_list with [(...) AND enabled='yes']
    // Exception: the user is an authors db manager
    // AND
    // (has requested 'no' OR want every single author)

    // On efficiency: I'm assuming here that the enabled authors are a lot more than the disabled.

    $query_without_view = $query;

    if (user_is_authors_db_manager() && isset($view)) {
        // argument 'view': 'enabled'(default), 'disabled', 'all'
        if ($view == 'disabled')
            $sql_what_to_list = "enabled != 'yes' AND ($sql_what_to_list)";
        else if ($view != 'all')
            $sql_what_to_list = "($sql_what_to_list) AND enabled = 'yes'";
        // the view argument should be passed on to any re-sorts
        $query .= "view=" . urlencode($view) . "&";
    }
    else
        $sql_what_to_list = "($sql_what_to_list) AND enabled = 'yes'";

    // NOTE: If a search is made that ends up returning all authors in the database,
    // it will read 'Search Results' rather than 'All'.
    // Also, if one result is returned, pluralis 'Results' will
    // still be used.

    // Arguments specifying how to sort,
    // in terms understandable to the user
    $sortingValue = $sortUtility->getSortingValue();
    $human_order = '<code>' . $sortingValue->getLabel() .'</code>';

    // Descending?
    if ($sortUtility->getSortingDirection() == DESCENDING)
        $human_order .= ' (' . _('descending') . ')';
}

// prepare a string for use with LIKE
function sql_like_encode($str) {
    // escape quotes -- note: does not encode % and _ !
    $str = mysqli_real_escape_string(DPDatabase::get_connection(), $str);
    // % to \%
    // _ to \_
    $str = preg_replace('/%|_/', '\\\$1', $str);
    // * to %
    $str = preg_replace('/\\*/', '%', $str);
    // ? to _
    $str = preg_replace('/\\?/', '_', $str);
    // ^ means start-of-string
    // $ means end-of-string
    // i.e. don't pad with wildcard %
    if (substr($str, 0, 1) == '^')
        $str = substr($str, 1);
    else
        $str = "%$str";
    if (substr($str, -1) == '$')
        $str = substr($str, 0, -1);
    else
        $str = "$str%";
    return $str;
}

function echo_search_form() {
    global $last_name, $other_names;
    $view    = array_get($_REQUEST, 'view', null);
    $orderby = array_get($_REQUEST, 'orderby', null);
    $desc    = array_get($_REQUEST, 'desc', null);
    echo "<h2>" . _("Search") . "</h2>";
    echo "<p>" . _('Case insensitive.') . " ";
    echo sprintf(
            _('Use %1$s for partial matches or %2$s to match exactly one character.' .
            '%3$s encodes start-of-string and %4$s encodes end-of-string.'),
            '<b>*</b>', '<b>?</b>', '<b>^</b>', '<b>$</b>');
    ?>
    <form method="GET">
    <table class='themed' style='width: auto;'>
    <tr><td>Last name:</td><td><input type="text" name="last_name" size="20" value="<?php echo $last_name; ?>"></td></tr>
    <tr><td>Other name(s):</td>
        <td><input type="text" name="other_names" size="20" value="<?php echo $other_names; ?>"></td></tr>
    <tr><td><input type="submit" value="<?php echo _('Search'); ?>"></td>
        <td class="right-align"><input type="button" onClick="var f=this.form;f.last_name.value='';f.other_names.value='';" value="<?php echo _('Reset'); ?>"></td></tr>
    </table>
    <?php
    echo "<a href='" . attr_safe($_SERVER['PHP_SELF']) . "'>" . _('List all') . "</a>\n";

    if (isset($view))
        echo "<input type='hidden' name='view' value='" . attr_safe($view) . "'>\n";
    if (isset($orderby))
        echo "<input type='hidden' name='orderby' value='" . attr_safe($orderby) . "'>\n";
    if (isset($desc))
        echo "<input type='hidden' name='desc' value=''>\n";
    echo '</form>';
}

// e.g. "All sorted by Last name"
function get_search_title() {
    global $human_what_to_list, $human_order;
    return $human_what_to_list . ' ' . _('sorted by') . ' ' . $human_order;
}

function search() {
    global $sortUtility, $sql_what_to_list, $search_result;
    $orderby = $sortUtility->getOrderBy();
    $sql = "SELECT * FROM authors WHERE $sql_what_to_list ORDER BY $orderby";
    $search_result = DPDatabase::query($sql);
    return $search_result;
}

// Builds a query string by joining the arguments by '&',
// ignoring arguments that are blank strings.
function build_query_string() {
    $count = func_num_args();
    $non_empty = array();
    for ($i = 0; $i < $count; $i++) {
        $arg = func_get_arg($i);
        if ($arg != '')
            array_push($non_empty, $arg);
    }
    return join('&', $non_empty);
}
