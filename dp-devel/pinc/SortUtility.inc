<?php

// Helps keeping track of HTTP-arguments specifying how to sort a table.
// (It doesn't have to be a table, but it most likely is.)
// A SortableValue is typically used for a column in the db-table,
// but it can also represent a more complex SQL-ORDER-BY-snippet.

define('ASCENDING', 1);
define('DESCENDING', 2);

class SortableValue {
  var $_name,
      $_label,
      $_sqlRuleAscending,
      $_sqlRuleDescending;

  // constructor.
  // $name and $label are required. $name is the name of the value and is used
  // as an HTTP-parameter when requesting that the view is sorted by this value.
  // $label is displayed to the user as a descriptor of the value (typically
  // column in an HTML-table).
  // If $sqlRuleAscending is not provided, it is set to $name, that is, the
  // ORDER BY-clause will be "ORDER BY $name".
  // If $sqlRuleDescending is not provided, it will be the inverse of
  // $sqlRuleAscending. With 'inverse', I mean that the inverse of
  // "a, b DESC, c ASC" is "a DESC, b ASC, c DESC".
  function SortableValue($name, $label,
                         $sqlRuleAscending = null, $sqlRuleDescending = null) {
    $this->_name = $name;
    $this->_label = $label;
    if (isset($sqlRuleAscending)) {
      $this->_sqlRuleAscending = $sqlRuleAscending;
      if (isset($sqlRuleDescending))
        $this->_sqlRuleDescending = $sqlRuleDescending;
      else
        $this->_sqlRuleDescending = $this->_flipAscendDescendSqlRule($sqlRuleAscending);
    }
    else {
      $this->_sqlRuleAscending = $name;
      $this->_sqlRuleDescending = "$name DESC";
    }
  }

  function _flipAscendDescendSqlRule($sqlRuleAscending) {
    $vars = explode(',', $sqlRuleAscending);
    $newVars;
    $oldVar;
    $newVar;
    $count = 0;
    foreach ($vars as $var) {
      $oldVar = $var;
      if (($newVar=str_replace(' ASC', ' DESC', $var)) != $oldVar)
        ; // ASC -> DESC
      else if (($newVar=str_replace(' DESC', '', $var)) != $oldVar)
        ; // DESC -> ASC
      else
        $newVar = "$var DESC"; // ASC -> DESC
      $newVars[$count++] = trim($newVar);
    }
    return join(', ', $newVars);
  }

  // Returns the name of the SortableValue.
  function getName() {
    return $this->_name;
  }

  // Returns the label of the SortableValue.
  function getLabel() {
    return $this->_label;
  }

  // Depending on whether $direction is ASCENDING or DESCENDING,
  // returns a SQL-snippet to be used in an ORDER BY-clause.
  function getSQLRule($direction) {
    if ($direction == ASCENDING)
      return $this->_sqlRuleAscending;
    else
      return $this->_sqlRuleDescending;
  }

}

class SortUtility {
  // the name of the utility -- used when identifying
  // HTTP-arguments on sorting
  var $_name,
  // the SortableValues
      $_collection;

  // optionally: one SortableValue is 'primary' meaning it is
  // always sorted by
  // ( ORDER BY whatever, primary )
  var $_primaryValue;

  // sorting
  var $_sortingValue,
      $_sortingDirection;

  // if true, the user's choice of sorting is stored to database
  // at selection, and read from it at loading
  var $_storeSortingInDatabase = false;

  // constructor
  // $name should be unique on the entire site,
  // since, if db-storing of sorting-options is
  // turned on, the name will be used when
  // storing the data.
  function SortUtility($name) {
    $this->_name = $name;
    $this->_collection = array();

    $this->_checkForUserPreferenceOnSorting();
  }

  // Returns true if a user choice is found, false otherwise.
  // Those are, in order: HTTP-argument, saved setting in database
  function _checkForUserPreferenceOnSorting() {

    // Did the user click a sorting link (or used bookmark)?
    if (isset($_GET[$this->_name.'_order'])) {
      if ($this->_parseSortingRule($_GET[$this->_name.'_order'])) {
        // Parameter was passed and evaluated ok.
        // Possibly store it in the database
        if ($this->_storeSortingInDatabase)
          $this->_storeSorting();
        return true;
      }
    }

    // Check for a stored preference in the database
    if ($this->_storeSortingInDatabase) {
      $result = mysql_query("SELECT value FROM usersettings WHERE username='$user' && setting='".$this->_name."_order'");
      if (mysql_num_rows($result) >= 1)
        return $this->_parseSortingRule(mysql_result($result, 0));
    }

    // nothing found
    unset($this->_sortingValue);
    unset($this->_sortingDirection);

    return false;
  }

  // Sets the sorting rule to use. Will not take effect if
  // there is an HTTP-argument or setting in the database
  // specifying how to sort this data.
  function setSortingRule($sortingValue, $sortingDirection = ASCENDING) {
    if (!$this->_checkForUserPreferenceOnSorting()) {
      // No Sorting link clicked, no database setting searched & found
      $this->_sortingValue = $sortingValue;
      $this->_sortingDirection = $sortingDirection;
    }
  }

  // used internally. Tries to parse a sortingRule of
  // the form "nameX" where 'name' is the name of a
  // SortableValue and X is A or D, specifying
  // ascending and descending, respectively.
  function _parseSortingRule($sortingRule) {
    if (preg_match('/A$/', $sortingRule)) {
      $name = substr($sortingRule, 0, -1);
      $this->_sortingDirection = ASCENDING;
    }
    else if (preg_match('/D$/', $sortingRule)) {
      $name = substr($sortingRule, 0, -1);
      $this->_sortingDirection = DESCENDING;
    }
    if (isset($name)) {
      foreach ($this->_collection as $col) {
        if ($col->_name == $name) {
          $this->_sortingValue = $col;
          return true;
        }
      }
    }
    // nothing parsable found
    $this->_sortingValue = null;
    $this->_sortingDirection = null;
    return false;
  }

  // By default, the users choice of sorting (as registered when
  // clicking links) is not saved in the database. This function
  // is used to set that property to true or false.
  function setStoreSortingInDatabase($storeSortingInDatabase) {
    $this->_storeSortingInDatabase = $storeSortingInDatabase;
  }

  // Returns the SortableValue that should be sorted by.
  // This is the one the user has choose to sort by,
  // or the default as specified by setSortingRule(...).
  function getSortingValue() {
    if (isset($this->_sortingValue))
      return $this->_sortingValue;
    else if (count($this->_collection) > 0) {
      $this->_sortingValue = $this->_collection[0];
      $this->_sortingDirection = ASCENDING;
      return $this->_sortingValue;
    }
    else
      return null;
  }

  // Returns ASCENDING or DESCENDING specifying how
  // to sort.
  function getSortingDirection() {
    if (isset($this->_sortingDirection))
      return $this->_sortingDirection;
    else
      return ASCENDING;
  }

  // Adds the SortableValue to the list.
  function addSortableValue($sortableValue) {
    array_push($this->_collection, $sortableValue);
  }

  // The SortableValues provided as arguments to this
  // function will be used as SortableValues.
  // Any SortableValues previously added using the
  // addSortableValue(...)-function will be removed.
  function setSortableValues() {
    $this->_collection = func_get_args();
  }

  // Returns the number of SortableValues managed
  // by this SortUtility.
  function getValueCount() {
    return count($this->_collection);
  }

  // Returns the SortableValue at the provided index, where
  // $index ranges from 0 to getvalueCount() - 1, inclusive.
  function getValueAt($index) {
    if ($index >= 0 && $index < count($this->_collection))
      return $this->_collection[$index];
    else
      return null;
  }

  // Returns the name of the SortUtility.
  function getName() {
    return $this->_name;
  }

  // Returns a SQL-snippet for use in an ORDER BY-context,
  // e.g. "name DESC, id".
  function getOrderBy() {
    $val = $this->getSortingValue();
    if (isset($val)) {
      $rule = $val->getSQLRule($this->_sortingDirection);
      if (isset($this->_primaryValue) && $col != $this->_primaryValue)
        return $rule . ', ' . $this->_primaryValue->getName();
      return $rule;
    }
    else {
      if (isset($this->_primaryValue))
        return $this->_primaryValue->getName();
      return null;
    }
  }

  // Defines the primary SortableValue which is always appended
  // to the end of the ORDER BY-string. This can e.g. be a
  // name- or id-column by which sorting should be performed
  // when the sorting value as selected by the user cannot
  // distinguish between two or more rows in the database.
  function setPrimaryValue($primaryValue) {
    $this->_primaryValue = $primaryValue;
  }

  // Returns the primary SortableValue.
  function getPrimaryValue() {
    return $this->primaryValue;
  }

  // Returns a query string for sorting the view as now.
  // This should be used when building a link for reloading
  // the page for some reason, while keeping the sorting intact.
  function getQueryStringForCurrentView($sortableValue = null) {

    $currentlySortedValue = $this->getSortingValue();

    if ($sortableValue == null || $sortableValue == $currentlySortedValue) {
      return $this->_name.'_order=' . $currentlySortedValue->getName() .
             ($this->getSortingDirection() == ASCENDING ? 'A' : 'D');
    }
    else {
      return $this->_name.'_order=' . $sortableValue->getName() . 'A';
    }
  }

  // Returns a query string for sorting the SortableValue.
  // This should be used when building a link for sorting the value.
  //
  // If the SortableValue is the one currently sorted by and
  // it is sorted ascending, the query string will request that the
  // value be sorted descending (i.e. it's a flip/flop-link). In all
  // other cases will the direction be ascending.
  //
  // The argument defaults to the currently sorted value.
  function getQueryStringForSortableValue($sortableValue) {

    $currentlySortedValue = $this->getSortingValue();

    // ascending or descending?
    if ($sortableValue == $currentlySortedValue)
      $direction = ($this->getSortingDirection() == ASCENDING ? 'D' : 'A');
    else
      $direction = 'A';

    return $this->_name . '_order=' . $sortableValue->getName()
           . $direction;
  }

  // Store the user's preference on sorting this particular table in the database
  function _storeSorting() {
    // The name used for this particular setting in `usersettings`
    $setting_name = $this->_name . '_order';

    // The value to store is $setting_value
    $currentlySortedValue = $this->getSortingValue();
    $setting_value = $currentlySortedValue->getName() .
                     ($this->getSortingDirection() == ASCENDING ? 'A' : 'D');

    // See if there's a preference already
    $result = mysql_query("SELECT value FROM usersettings WHERE username='$user' && setting='$setting_name'");

    if (mysql_num_rows($result) > 0)
      // existing preference. UPDATE
      mysql_query("UPDATE usersettings SET value='$setting_value'" .
                  "WHERE (username = '$user' AND setting='$setting_name')");
    else
      // no preference yet. INSERT
      mysql_query('INSERT INTO usersettings (username, setting, value) ' .
                  "VALUES ('$user', '$setting_name', '$setting_value')");
  }

}

?>