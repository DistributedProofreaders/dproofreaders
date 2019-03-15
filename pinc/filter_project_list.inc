<?php
include_once($relPath.'misc.inc'); // attr_safe()

// This file includes functions to handle the creation, update, and display of
// the project filters used on the Round, Project, and Smooth Reading pages.
// This includes displaying the form, processing updates made from the form,
// and getting the filter values from the user_filters table.
//
// For consistency the form field names (also $field and $fields in the
// functions) use the corresponding column name from the projects table. Hence
// the field name for the Post Processor is checkedoutby and the field for
// Project Managers is username.

function process_and_display_project_filter_form($username, $filter_type, $filter_label, $data, $state_sql, $custom_display_fields=NULL)
{
    // process the data passed into us for changes
    $filter_changed=maybe_update_project_filter($username, $filter_type, $data, $state_sql);

    // if the filter was updated or the username is empty, use the new data
    // to populate the form otherwise pull the data from the stored filter
    if($filter_changed || $username=="")
        $form_data_source=$data;
    else
        $form_data_source=get_current_project_filter_data($username, $filter_type);

    // display the form
    display_project_filter_form($filter_type, $filter_label, $form_data_source, $state_sql, $custom_display_fields);
    display_current_project_filter_settings($username, $filter_type);
}

function display_project_filter_form($filter_type, $filter_label, $data, $state_sql, $custom_display_fields=NULL)
{
    // set the default display state of the form fields
    $display_fields=array();
    $display_fields["language"] = TRUE;
    $display_fields["genre"] = TRUE;
    $display_fields["username"] = TRUE;
    $display_fields["difficulty"] = TRUE;
    $display_fields["special_code"] = TRUE;
    $display_fields["checkedoutby"] = FALSE;

    // overwrite the default display state of the form fields with any
    // custom display requests
    if($custom_display_fields && is_array($custom_display_fields))
        $display_fields=array_merge($display_fields, $custom_display_fields);

    // if no fields are set for display, we have nothing to do
    $is_any_field_displayed = FALSE;
    foreach($display_fields as $field => $is_displayed)
        $is_any_field_displayed = $is_any_field_displayed || $is_displayed;

    if(!$is_any_field_displayed)
        return;

    // if they want to reset the filter, zero out the data passed in
    if(array_key_exists("reset",$data))
        $data=array();

    // Create only ONE table, fill its cells with the selectors
    // Track number of columns added so colspans can be given later
    $filter_column_count = 0;

    echo "
        <form id='filter_form' method='post' action='#filter_form'>
        <input type='hidden' name='filter_action' value='update'>";

    echo "<p>" . sprintf(_("Filter available %s books by:"),$filter_label) . "</p>";
    echo "<table class='filter'>\n";

    // create the row for listboxes for Language, Genre, PM, Special Days, PPers (when enabled)
    echo "<tr>\n";

    // TODO: Even though each of the <select>s have an "All" option, it doesn't
    // really do anything except act as a placeholder for the case where the
    // user hasn't selected something. For the purposes of updating the filter,
    // the "All" options are no-ops. There should probably be some javascript
    // action that would deselect the "All" option if another option was
    // selected.

    $display_fields_on_first_line = array("genre", "username", "checkedoutby", "special_code", "language");
    foreach($display_fields_on_first_line as $field)
    {
        if(@$display_fields[$field])
            $filter_column_count++;
    }

    // Calculate the <td> width for cells on the first line
    $td_width = floor(100 / $filter_column_count) . '%';

    if($display_fields["genre"])
    {
        $genre_options = _load_project_filter_field_values("genre", $state_sql);
        echo _build_project_filter_select(_("Genre"), _("All Genres"), "genre", $genre_options, @$data["genre"], $td_width, TRUE);
    }

    if($display_fields["username"])
    {
        $pm_options=_load_project_filter_field_values("username", $state_sql);
        echo _build_project_filter_select(
            _("Project Manager"),
            // TRANSLATORS: PMs = project managers
            _("All PMs"),
            "username", $pm_options, @$data["username"], $td_width);
    }

    if($display_fields["checkedoutby"])
    {
        $pp_options=_load_project_filter_field_values("checkedoutby", $state_sql);
        echo _build_project_filter_select(_("Post Processor"), _("All PPers"), "checkedoutby", $pp_options, @$data["checkedoutby"], $td_width);
    }

    if($display_fields["special_code"])
    {
        $specialday_options=_load_project_filter_field_values("special_code", $state_sql);
        // TODO: "All Special Days" is actually an incorrect label considering
        // what the resulting output is. Instead it behaves like
        // "all projects regardless of special day status". Unlike the other "All"
        // labels, selecting "All Special Days" is not equivalent to selecting all
        // the other options in this select. See task #797.
        echo _build_project_filter_select(_("Special Days"), _("All Special Days"), "special_code", $specialday_options, @$data["special_code"], $td_width, TRUE);
    }

    // if enabled, the language selection will be on a row by itself
    if($display_fields["language"])
    {
        $language_options=_load_project_filter_field_values("language", $state_sql);
        echo _build_project_filter_select(_("Language"), _("All Languages"), "language", $language_options, @$data["language"], "auto", FALSE, $filter_column_count);
    }

    echo "</tr>\n";

    // if enabled, the difficulty selection will be on a row by itself
    if($display_fields["difficulty"])
    {
        echo "<tr><td colspan='$filter_column_count'>";

        echo "<b>" . _("Difficulty") . ":</b> ";
        echo pgettext("all difficulties", "All") . "<input type='checkbox' name='difficulty[]' value=''";
        $difficulty = @$data["difficulty"];
        if(!count($difficulty) || (count($difficulty)==1 && $difficulty[0]==""))
            echo " checked";
        echo ">   ";
        $difficulty_options=_load_project_filter_field_values("difficulty", $state_sql);
        foreach($difficulty_options as $value => $option)
        {
            echo "$option<input type='checkbox' name='difficulty[]' value='$value'";
            if(is_array($difficulty) && in_array($value, $difficulty))
                echo " checked";
            echo ">   ";
        }
        echo "</td></tr>\n";
    }

    // Next row, buttons and text
    echo "<tr>\n<td class='center-align' colspan='$filter_column_count'>\n";

    // buttons
    echo "<input type='submit' name='reset' value='".
        attr_safe(_("Remove Filter"))
         ."'>";
    echo "<input type='submit' value='".
        attr_safe(_("Apply Selections"))
         ."'>";

    echo "</table>\n";

    echo "</form>\n";
}

function maybe_update_project_filter($username, $filter_type, $data, $state_sql)
// This function processes the posted form and updates the database accordingly.
// The function handles the case where it was asked to process a $_POST
// but the form wasn't actually posted.
// If a form was posted, this function returns TRUE, otherwise it returns FALSE.
{
    // don't bother updating the filter if there was no update request
    // (ie: the form wasn't posted) or the username was blank (ie: the user
    // isn't logged in)
    if(array_get($data,"filter_action","")!="update" || empty($username))
        return FALSE;

    // if they want to reset the filter, zero out the data passed in
    if(array_key_exists("reset",$data))
        $data=array();

    $filter = "";
    $display = "";

    // enumerate the expected data fields including their human-friendly
    // labels to update the filter display
    $data_fields = array(
        "language" => _("Language"),
        "genre" => _("Genre"),
        "username" => _("Project Manager"),
        "checkedoutby" => _("Post Processor" ),
        "special_code" => _("Special Days"),
        "difficulty" => _("Difficulty"),
    );

    foreach($data_fields as $field => $label)
    {
        // load the field data
        $field_data = array_get($data,$field,array());

        // if there is no data, skip it
        if(!count($field_data))
            continue;

        // associative arrays are handled differently from non-associative ones
        $is_associative=FALSE;
        if($field == "special_code" || $field=="difficulty" || $field=="genre")
            $is_associative=TRUE;

        $filter_segments=array();
        $display_segments=array();

        // load all possible field values
        $possible_field_values = _load_project_filter_field_values($field, $state_sql);
        foreach($field_data as $field_datum)
        {
            // check to see if the value is a valid one and if not skip it
            if(!$is_associative && !in_array($field_datum, $possible_field_values)) continue;
            elseif($is_associative && !array_key_exists($field_datum, $possible_field_values)) continue;

            $filter_segments[]=sprintf("$field='%s'", mysqli_real_escape_string(DPDatabase::get_connection(), $field_datum));
            $display_segments[]=$field_datum;
        }

        // if we have any filter segments, build the filter
        // note the the filter needs to be parseable by
        // _populate_project_filter_data_from_filter()
        if(count($filter_segments))
        {
            $filter.=" AND (" . implode(" OR ", $filter_segments) . ")";
            $display.="<br>$label=" . implode(", ", $display_segments);
        }
    }

    //Note: When using UPDATE, MySQL will not update columns where the new value is the same as the old value. 
    //This creates the possiblity that mysqli_affected_rows(DPDatabase::get_connection()) may not actually equal the number of rows matched, 
    //only the number of rows that were literally affected by the query. This means we can't try to do an
    //UPDATE and based on the value of mysqli_affected_rows(DPDatabase::get_connection()) do an INSERT. Instead, well just DELETE and INSERT
    //our changes.

    // _internal filter is database-readable SQL for WHERE clause in queries
    // _display filter is human readable summary for reporting current filter to user

    // remove old filter values (if any) from table
    $query = sprintf("DELETE FROM user_filters WHERE username = '%s' AND filtertype = '%s_internal'", mysqli_real_escape_string(DPDatabase::get_connection(), $username), mysqli_real_escape_string(DPDatabase::get_connection(), $filter_type));
    $result = mysqli_query(DPDatabase::get_connection(), $query);

    $query = sprintf("DELETE FROM user_filters WHERE username = '%s' AND filtertype = '%s_display'", mysqli_real_escape_string(DPDatabase::get_connection(), $username), mysqli_real_escape_string(DPDatabase::get_connection(), $filter_type));
    $result = mysqli_query(DPDatabase::get_connection(), $query);

    // if the filter isn't empty, add the filter and the display to the table
    if(!empty($filter))
    {
        $query = sprintf("INSERT INTO user_filters (username, filtertype, value) VALUES ('%s', '%s_internal', '%s')", mysqli_real_escape_string(DPDatabase::get_connection(), $username), mysqli_real_escape_string(DPDatabase::get_connection(), $filter_type), mysqli_real_escape_string(DPDatabase::get_connection(), $filter));
        $result = mysqli_query(DPDatabase::get_connection(), $query);

        $query = sprintf("INSERT INTO user_filters (username, filtertype, value) VALUES ('%s', '%s_display', '%s')", mysqli_real_escape_string(DPDatabase::get_connection(), $username), mysqli_real_escape_string(DPDatabase::get_connection(), $filter_type), mysqli_real_escape_string(DPDatabase::get_connection(), $display));
        $result = mysqli_query(DPDatabase::get_connection(), $query);
    }

    return TRUE;
}

function get_project_filter_sql($username, $filter_type)
{
    $filter = "";

    $query = sprintf("SELECT value FROM user_filters WHERE username = '%s' AND filtertype = '%s_internal'", mysqli_real_escape_string(DPDatabase::get_connection(), $username), mysqli_real_escape_string(DPDatabase::get_connection(), $filter_type));
    $result = mysqli_query(DPDatabase::get_connection(), $query);
    $row = mysqli_fetch_assoc($result);
    if($row)
        $filter = $row["value"];
    mysqli_free_result($result);

    return $filter;
}

function get_project_filter_display($username, $filter_type)
{
    $filter_display="";

    $query = sprintf("SELECT value FROM user_filters WHERE username = '%s' AND filtertype = '%s_display'", mysqli_real_escape_string(DPDatabase::get_connection(), $username), mysqli_real_escape_string(DPDatabase::get_connection(), $filter_type));
    $result = mysqli_query(DPDatabase::get_connection(), $query);
    $row = mysqli_fetch_assoc($result);
    if($row)
        $filter_display = $row["value"];
    mysqli_free_result($result);

    return $filter_display;
}

function get_current_project_filter_data($username, $filter_type)
// Return a data array of the current project filter
{
    $filter=get_project_filter_sql($username, $filter_type);
    return _populate_project_filter_data_from_filter($filter);
}

function display_current_project_filter_settings($username, $filter_type)
// Print out the current project filter settings for a given user and filter type
{
    // if the username is blank (ie: the user isn't logged in) just return
    // as there would be nothing to show them
    if(empty($username))
        return;

    $display_filter = get_project_filter_display($username, $filter_type);

    echo "<p>", _("Current filter:"), " <b>";

    if(!empty($display_filter))
        echo $display_filter;
    else
        echo _("No filter, all books shown.");

    echo "</b></p>\n";
}


//----------------------------------------------------------------------------
// Supporting functions

function _build_project_filter_select($label, $all_label, $name, $options, $selected=NULL, $width="auto", $are_options_associative=FALSE, $colspan=0)
// This function builds the <select> form inputs for the project filter table
// and returns the HTML code needed to show the form element to the user,
// including the encapsulating <td> tags.
//
// It takes the following arguments:
//   $label     - the label placed above the <select>
//   $all_label - the string to use for the "All of these" option prepended
//       to the top of the <select>'s options
//   $name      - the name of the <select>
//   $options   - an array containing the options
//   $selected  - an array containing already-selected values
//   $width     - the width of the enclosing <td> tag, by default this is auto
//   $are_options_associative - if the options are an associative array,
//       this needs to be set to TRUE, otherwise a flat array is assumed
//   $colspan   - the colspan value for the <td>, by default this is left off
//
// In the <select> tags, we set the name attribute to 'whatever[]'.
// I'm pretty sure this doesn't mean anything to HTML/HTTP,
// but PHP takes it as a cue to make the multiple values of
// the select control available as an array.
// That is, $_POST['whatever'] will be an array containing
// all selected values.
{
    // ensure that $selected is an array
    if($selected==NULL)
        $selected=array();
    elseif(!is_array($selected))
        $selected=array($selected);

    $return="<td";
    if($colspan!=0)
        $return.=" colspan='$colspan'";
    if($width!="auto")
        $return.=" style='width: $width;'";
    $return.=">";
    $return.="<b>$label:</b>";
    $return.="<br>";
    $return.="<select name='{$name}[]' size='4' multiple>";
    $return.="<option value=''";
    if(!count($selected) || in_array("",$selected))
        $return.=" selected";
    $return.=">$all_label</option>";
    foreach($options as $value => $option)
    {
        $return.="<option ";
        if($are_options_associative)
        {
            $return.="value='" . attr_safe($value) . "'";
            if(in_array($value, $selected))
                $return.=" selected";
        }
        else
        {
            $return.="value='" . attr_safe($option) . "'";
            if(in_array($option, $selected))
                $return.=" selected";
        }

        $return.=">$option</option>";
    }
    $return.="</select>";
    $return.="</td>\n";

    return $return;
}

function _populate_project_filter_data_from_filter($filter)
// This function parses out the SQL filter into an associative
// array that can be used to populate the filter form when
// passed into display_project_filter_form().
{
    $data = array();

    // remove no-ops and redundant strings in the filter
    // that were used in older code
    $noops = array("/1\s+AND\s+/i","/0\s+OR\s+/i","/projects\./");
    $filter = preg_replace($noops,"",$filter);

    // split the filter into segments; each segment will contain
    // the ( ... OR ... ) string for one form field
    $segments = explode(" AND ", $filter);
    foreach($segments as $segment)
    {
        // remove trailing quotes, spaces, or parens
        $segment = rtrim($segment,"' )");
        $segment = ltrim($segment,"' (");

        // split each segment into a set of column='value' pairs
        $value_pairs = explode(" OR ", $segment);
        foreach($value_pairs as $value_pair)
        {
            // split the column='value' pairs into their two parts
            $value_pair = explode("=", $value_pair);
            if (count($value_pair) != 2) continue;
            list($field, $value) = $value_pair;

            // clean up the $value, which looks like: 'value'
            $value = rtrim($value,"' ");
            $value = ltrim($value,"' ");

            // skip any empty values
            if($value=="") continue;

            if(!is_array(@$data[$field]))
                $data[$field]=array();

            $data[$field][]=$value;
        }
    }

    return $data;
}

function _load_project_filter_field_values($field, $state_sql)
// Given a specific field, return all possible values for that field.
{
    $load_associative = FALSE;

    switch($field)
    {
        case "language":
            $query = "SELECT distinct language FROM projects WHERE ($state_sql) ORDER BY language";
            break;
        case "genre":
            $query = "SELECT distinct projects.genre, genres.trans_genre FROM projects NATURAL JOIN genres WHERE ($state_sql) ORDER BY trans_genre";
            $load_associative = TRUE;
            break;
        case "special_code":
            $query = "
                SELECT  DISTINCT special_code, display_name
                FROM projects, special_days
                WHERE projects.special_code = special_days.spec_code AND ($state_sql) ORDER BY display_name
            ";
            $load_associative = TRUE;
            break;
        case "difficulty":
            $difficulty_options = array('beginner' => _("Beginners Only"), 'easy' => _("Easy"), 'average' => _("Average"), 'hard' => _("Hard"));
            return $difficulty_options;
            break;
        case "username":
            $query = "SELECT distinct username FROM projects WHERE ($state_sql) ORDER BY username";
            break;
        case "checkedoutby":
            $query = "SELECT distinct checkedoutby FROM projects WHERE ($state_sql) ORDER BY checkedoutby";
            break;
        default:
            die("Invalid field type.");
    }

    $return = array();

    $result = mysqli_query(DPDatabase::get_connection(), $query);
    while ($a_res = @mysqli_fetch_row($result))
    {
        if($load_associative)
            $return[$a_res[0]] = $a_res[1];
        else
            $return[]=$a_res[0];
    }

    if($result)
        mysqli_free_result($result);

    return $return;
}

// vim: sw=4 ts=4 expandtab
