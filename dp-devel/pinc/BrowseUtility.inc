<?php

// Handles HTTP-arguments 'start' and 'count'
// specifying where in a db-query-result to start and
// how many rows to display.
// Aids in creation of "previous" and "next" links.

// If count is larger than zero, it will be used as is.
// If count is zero, it will interpreted as 'infinite',
// thus displaying all the stuff at once (start is set to 0).
// If count is less than zero, it is made the default, or 20.

class BrowseUtility {
  // the two HTTP-arguments
  var $_start,
      $_count;

  // the number of items to display
  var $_displayed_count;

  // last index is typically start+count-1
  var $_last_index;

  // holds row count of search result
  var $_total_row_count;

  // IMPORTANT NOTE: The search result may be modified:
  // An appropriate number of rows are removed, so that
  // a fetch_xxx-loop will start at the correct place.
  //
  // HTTP-arguments always have precedence before $count provided here
  function BrowseUtility($search_result, $count = 20) {

    $this->_total_row_count = mysql_numrows($search_result);

    if (isset($_REQUEST['start']) && $_REQUEST['start'] > 0)
      $this->_start = $_REQUEST['start'];
    else
      $this->_start = 0;

    if (isset($_REQUEST['count']) && $_REQUEST['count'] >= 0) {
      $this->_count = $_REQUEST['count'];
      $this->_displayed_count = $this->_count;
      if ($this->_count == 0) {
        // all
        $this->_start = 0;
        $this->_displayed_count = $this->_total_row_count;
      }
    }
    else {
      $this->_count = $count;
      $this->_displayed_count = $this->_count;
    }

    if ($this->_total_row_count > 0) {
      // Make sure last index doesn't overflow
      // (e.g. start:20, count:20, but only 34 rows).
      $this->_last_index = $this->_start + $this->_displayed_count - 1;
      $last_existant = $this->_total_row_count - 1;
      if ($this->_last_index > $last_existant)
        $this->_last_index = $last_existant;

      // If we should start listing at row 5, skip the first
      // 5 rows (0, 1, 2, 3, 4).
      for ($i = 0; $i < $this->_start; $i++)
        mysql_fetch_row($search_result);
    }
    else {
      // Nothing to browse
      $this->_start = 0;
      $this->_count = 0;
      $this->_last_index = 0;
    }
  }

  // returns the number of rows to display (typically count)
  function getRowCountToList() {
    return $this->_displayed_count;
  }

  // returns true if there can (should) be a "previous" link, false otherwise
  function isPreviousBrowseAvailable() {
    return ($this->_start > 0);
  }

  // returns true if there can (should) be a "next" link, false otherwise
  function isNextBrowseAvailable() {
    return ($this->_last_index < $this->_total_row_count - 1);
  }

  // returns query strings such as "start=40&count=20"
  // to use with a "previous" link
  function getPreviousBrowseQueryString() {
    $new_start = $this->_start - $this->_count;
    if ($new_start < 0)
      $new_start = 0;
    return "start=$new_start&count=" . $this->_count;
  }

  // returns query strings such as "start=40&count=20"
  // to use with a "next" link
  function getNextBrowseQueryString() {
    $new_start = $this->_start + $this->_count;
    return "start=$new_start&count=" . $this->_count;
  }

  // "Displaying entries 6-10 of 24."
  // indices are reported one high, e.g. 1-10 instead of 0-9
  function getDisplayingString() {
    if ($this->_total_row_count == 0)
      return _('No entries displayed.');
    else if ($this->_start == $this->_last_index)
      return sprintf(_('Displaying entry %1$d of %2$d.'),
                     $this->_start+1, $this->_total_row_count);
    else
      return sprintf(_('Displaying entries %1$d-%2$d of %3$d.'),
                     $this->_start+1, $this->_last_index+1, $this->_total_row_count);
  }

  // writes a drop-down for selecting the count-parameter
  // arguments: any number of integers that will be used
  // as options for count. Zero means infinity.
  // No arguments means a default list will be used.
  // The page is simply reloaded with the new count,
  // all other arguments being the same and the method (POST/GET)
  // being the same.
  // Supports POST and GET.
  function echoCountSelectionList() {
    if (func_num_args() == 0)
      $list = array(10, 20, 30, 40, 50, 100, 0);
    else
      $list = func_get_args();

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
      $method = 'POST';
      $httpParams = $_POST;
    }
    else {
      $method = 'GET';
      $httpParams = $_GET;
    }

    echo "<form name='countForm' method='$method'>\n";

    // pass along http-parameters
    foreach ($httpParams as $name => $value) {
      if ($name == 'start')
        $value = ($this->_count == 0 ? 0 : $value); // list all :OR: start at same
      if ($name != 'count')
        echo "<input type='hidden' name='$name' value='" . htmlspecialchars($value) . "' />\n";
    }

    $dropdown = '<select name="count" size="1" onChange="this.form.submit();">' . "\n";

    foreach ($list as $option) {
        $selected = ($option == $this->_count) ? ' SELECTED' : '';
      if ($option == 0)
        $dropdown .= "<option value='0'$selected>" . _('all') . "</option>\n";
      else
        $dropdown .= "<option value='$option'$selected>$option</option>\n";
    }

    $dropdown .= '</select>';

    printf(_('Display %1$s items on each page.'), $dropdown);

    echo '</form>';
  }
}

?>