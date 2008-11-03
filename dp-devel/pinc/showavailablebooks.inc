<?
include_once($relPath.'misc.inc');
include_once($relPath.'project_states.inc');
include_once($relPath.'gettext_setup.inc');
include_once($relPath.'SettingsClass.inc');
include_once($relPath.'special_colors.inc');

function show_block_for_round( $round_number, $projects_filter )
{
    global $pguser;

    $round = get_Round_for_round_number($round_number);

    $setting = "{$round->id}_order";

    $columns = array(
        "nameofwork"  => _("Title"),
        "authorsname" => _("Author"),
        "language"    => _("Language"),
        "genre"       => _("Genre"),
        "username"    => _("Project Manager"),
        "n_available_pages" => _("Available Pages"),
        "n_pages"     => _("Total Pages"),
        "days_avail"  => _("Days")
    );

    $where_filter = "state = '{$round->project_available_state}'";
    $where_filter .= " $projects_filter";

    echo "\n<a name='{$round->id}'></a>";
    $title = _('Projects Currently Available');
    echo "\n<h3>$title</h3>";
    echo "\n<p>{$round->description}</p>";

    show_project_listing( $round, $setting, $round->id, $columns, $where_filter );
}

// -----------------------------------------------------------------------------

function show_project_listing( $stage, $user_setting, $anchor, $columns, $where_filter = " " )
// Create the sortable project listing.
// Arguments:
//   $stage        - stage object (Round, Pool, or Stage)
//   $user_setting - the setting value to use when saving the sort order
//   $anchor       - the anchor on the page to jump to
//   $columns      - the columns to include in the table, this is an associative
//                   array with the key being the column name and the value
//                   being the label
//   $where_filter - the where filter for the projects table (usually a
//                   combination of a state filter and a project filter)
{
    global $code_url, $userP, $pguser;

    // Some columns (notably those with numbers in them)
    // have special formatting styles -- specify them here.
    $column_styles = array(
        "n_available_pages" => "text-align: center;",
        "n_pages"   => "text-align: center;",
        "days"      => "text-align: center;",
        "days_left" => "text-align: center;"
    );

    // Identify columns that we know we can't sort by because their values
    // come from outside the database.
    $unsortable_columns = array( "uploaded" );
    
    // Load the sort column and direction
    list($sort_column, $sort_direction) = process_sorting_control($columns, $user_setting);

    // Determine SQL order by criteria based on the sorting information.
    if($sort_column == "days")
    {
        $orderclause = "modifieddate $sort_direction";
    }
    else
    {
        $orderclause = "$sort_column $sort_direction";
    }

    // Sort on nameofwork ASC as a secondary criteria if it isn't the primary
    // criteria.
    if($sort_column != "nameofwork")
    {
        $orderclause .= ", nameofwork ASC";
    }

    $order_param = "{$user_setting}_sort_by";

    // The originating request may have query-string settings (other than
    // for $order_param). We should preserve those, and just append the
    // setting for $order_param.
    $other_settings = '';
    foreach ( $_GET as $name => $value )
    {
        if ( $name != $order_param )
        {
            $other_settings .= urlencode($name) . "=" . urlencode($value) . "&amp;";
        }
    }

    // $presort allows us to place certain projects at the top of all project
    // listings such as BEGIN projects, but only for Rounds.
    $presort = "";
    if( is_a( $stage, 'Round' ) )
    {
        $presort = round_project_listing_presort($stage);
        if (!empty($presort)) $presort .= ',';
    }

    // Build and execute the query against the projects table.
    $query = "
        SELECT *,
            round((unix_timestamp() - modifieddate)/(24 * 60 * 60)) AS days_avail,
            round((smoothread_deadline - unix_timestamp())/(24 * 60 * 60)) AS days_left
        FROM projects
        WHERE
            $where_filter
        ORDER BY
            $presort
            $orderclause
    ";
    $result = mysql_query($query);

    // Start the table.
    echo "\r\n<table border=1>";
    echo "<tr align=center bgcolor='{$stage->listing_bgcolors[1]}'>";

    // Print out the header row with links.
    foreach($columns as $name => $label)
    {
        echo "<td>"; 

        if(!in_array($name, $unsortable_columns))
        {
            $link_sort_by=$name;
            if($name == $sort_column)
                $link_sort_by .= ":" . ($sort_direction == "asc" ? "desc" : "asc");

            echo "<b><a href='?{$other_settings}{$order_param}=$link_sort_by#$anchor'>$label</a></b>";
        }
        else
        {
            echo "<b>$label</b>";
        }

        echo "</td>";
    }

    echo "</tr>";

    $show_special_colors = FALSE;
    $show_email = FALSE;
    if($pguser)
    {
        // Determine whether to use special colors or not
        // (this does not affect the alternating between two
        // background colors) in the project listing.
        // Regardless of the preference, don't display
        // special colors to newbies.
        $userSettings =& Settings::get_Settings($pguser);
        $show_special_colors = (get_pages_proofed_maybe_simulated() >= 10
                                && !$userSettings->get_boolean('hide_special_colors'));

        // Show email addresses for site administrators
        // TODO: Figure out why uids 1342 and 12417 are exceptions, why we're
        // bothering to do a SQL lookup if we're using literals (why can't we
        // just use the usernames themselves?) and why this is in here at all.
        // If/when the literals are removed, this can be moved outside the
        // if($pguser) statement.
        $userid_q = mysql_query("SELECT u_id FROM users WHERE username = '$pguser'");
        $u_id = mysql_result($userid_q, 0, "u_id");
        $show_email = user_is_a_sitemanager() || $u_id == 1342 || $u_id == 12417;
    }


    $rownum = 0;

    while ($book=mysql_fetch_assoc($result))
    {
        $bgcolor = $stage->listing_bgcolors[$rownum % 2];

        // Special colours for special books of various types
        if ($show_special_colors)
        {
            $special_color = get_special_color_for_project($book);
            if (!is_null($special_color))
            {
                $bgcolor = $special_color;
            }
        }

        echo "<tr bgcolor='$bgcolor'>";
        foreach($columns as $name => $label)
        {
            $cell = "";
            if($name == "nameofwork")
            {
                $eURL = "$code_url/project.php?id={$book['projectid']}&amp;expected_state={$book['state']}";
                if ($userP['i_newwin']==0)
                    {$cell="<a href=\"$eURL\">";}
                else
                    {$cell="<a href=\"$eURL\" onclick=\"newProofWin('$eURL'); return false;\">";}
                $cell.="{$book['nameofwork']}</a>";
            }
            elseif($name == "genre")
            {
                if ($book['difficulty'] == "beginner")
                {
                    $genre = $book['genre'];
                    if( is_a( $stage, 'Round' ) )
                    {
                        if ( $stage->is_a_mentee_round() )
                            $genre = _("BEGINNERS ONLY")." ".$book['genre'];
                        else if ( $stage->is_a_mentor_round() )
                            $genre = _("MENTORS ONLY")." ".$book['genre'];
                    }
                }
                elseif ($book['difficulty'] == "easy")
                {
                    $genre = _("EASY")." ".$book['genre'];
                }
                elseif ($book['difficulty'] == "hard")
                {
                    $genre = _("HARD")." ".$book['genre'];
                }
                else
                {
                   $genre = $book['genre'];
                }
                $cell = $genre;
            }
            elseif($name == "username" || $name == "checkedoutby" || $name == "postproofer" || $name == "correctedby")
            // Create email or PM links for usernames.
            {
                if ($show_email)
                {
                    $email = get_forum_email_address($book[$name]);
                    $cell = "<a href='mailto:$email'>".$book[$name]."</a>";
                }

                if (!$show_email || $email == "")
                {
                    $contact_url = get_url_to_compose_message_to_user($book[$name]);
                    $cell = "<a href='$contact_url'>".$book[$name]."</a>";
                }
            }
            elseif($name == "uploaded")
            // Uploaded is a special case because it is pulled from the
            // filesystem and not from the database, and is hence not sortable.
            {
                global $projects_dir;
                if ($done_files = glob("$projects_dir/{$book['projectid']}/*smooth_done_*.zip") ) {
                   $num_done = count($done_files);
                } else {
                   $num_done = 0;
                }
                $cell = $num_done;
            }
            else
            {
                $cell = $book[$name];
            }

            // Determine any special style formatting.
            $style_attribute="";
            if(@$column_styles[$name])
                $style_attribute="style='{$column_styles[$name]}'";

            // Finally, output the table cell.
            echo "\n<td $style_attribute>$cell</td>";
        }

        $rownum++;
    }

    echo "</table>\n<br>";
}

// -----------------------------------------------------------------------------

function process_sorting_control($columns, $user_setting = NULL)
// This function's purpose is to intelligently determine the user's
// intent for a sort criteria. It does this by:
//   1. Checking $_GET for explicit sort order changes
//   2. If that fails pulling the previous sort order from the database
//   3. If that fails using a default sort order (first column) 
// After we have a sort criteria, validate it and fall back to
// the default sort column in ascending order if necessary.
// After validation the sort criteria is written to the database.
// Arguments:
//   $columns      - an associative array containing the valid columns in the
//                   table
//   $user_setting - the setting to use in the usersettings table. This value
//                   is also used in determining the sort order via the URL.
//                   Doing so allows for multiple tables to be on the same
//                   page and sorted in different orders.
// Returns the array($sort_column, $sort_direction):
//   $sort_column    - name of the column to sort by
//   $sort_direction - direction to sort by (asc or desc)
{
    global $pguser;

    // The default sort column is the first entry in $columns.
    $default_sort_column = array_shift(array_keys($columns));

    // Read new sort order from URL, if any.
    $sort_by = array_get($_GET, "{$user_setting}_sort_by", NULL);

    // If that failed $sort_by will be set to NULL in which case we use the
    // value from the database.
    if($user_setting && $sort_by == NULL)
    {
        $sort_by = get_sort_criteria( $pguser, $user_setting, NULL );
    }

    // If that failed (there was no previous sort order) fall back to the
    // default value (first column).
    if($sort_by == NULL)
    {
        $sort_by = $default_sort_column;
    }

    // Parse the $sort_by into its column:direction parts.
    list($sort_column, $sort_direction) = explode(":", $sort_by);

    // Confirm $sort_column is in $columns and if not, use the first entry.
    if(!array_key_exists($sort_column, $columns))
    {
        $sort_column = $default_sort_column;
    }

    // Default to ascending for the sort direction if none is specified
    // or if we don't recognize the sort direction.
    if(empty($sort_direction) || !in_array($sort_direction, array("asc", "desc")))
    {
        $sort_direction="asc";
    }

    // Recombine into $sort_by after error checking and correction.
    $sort_by="$sort_column:$sort_direction";

    // If a $user_setting was provided, save the results to the database.
    if($user_setting)
    {
        // Load the previous search criteria.
        $sort_by_old = get_sort_criteria( $pguser, $user_setting, NULL );

        // If orders have changed, save them to database.
        if ($sort_by != $sort_by_old)
        {
            set_sort_criteria( $pguser, $user_setting, $sort_by );
        }
    }

    return array($sort_column, $sort_direction);
}

// -----------------------------------------------------------------------------

function get_sort_criteria( $username, $user_setting, $default = "" )
// Return the sort criteria for a specific user and user_setting (ie: table).
// Arguments:
//   $username     - user for which to load the sort order
//   $user_setting - setting name to use
//   $default      - value to return if no previous setting was found
// Returns:
//   $sort_by      - the value returned from the database or $default
{
    $result = mysql_query("
        SELECT value
        FROM usersettings
        WHERE username = '$username' AND setting = '$user_setting'"
    );
    if (mysql_num_rows($result) >= 1)
    {
        $sort_by = mysql_result($result, 0, "value");
        mysql_free_result($result);
    }
    else
    {
        $sort_by = $default;
    }

    return $sort_by;
}

function set_sort_criteria( $username, $user_setting, $sort_by )
// Sets the sort criteria for a specific user and user_setting (ie: table)
// Arguments:
//   $username     - user for which to save the sort criteria
//   $user_setting - setting name to use
//   $sort_by      - sort criteria to save
{
    $result = mysql_query("
        DELETE FROM usersettings
        WHERE username = '$username' AND setting = '$user_setting'
    ");
    $result = mysql_query("
        INSERT INTO usersettings
        VALUES ('$username', '$user_setting', '$sort_by')
    ");
}

// vim: sw=4 ts=4 expandtab
?>
