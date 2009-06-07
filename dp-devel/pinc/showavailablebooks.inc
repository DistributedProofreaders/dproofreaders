<?
include_once($relPath.'misc.inc');
include_once($relPath.'project_states.inc');
include_once($relPath.'gettext_setup.inc');
include_once($relPath.'filter_project_list.inc');
include_once($relPath.'SettingsClass.inc');
include_once($relPath.'special_colors.inc');

function show_projects_for_round( $round, $show_filter_block, $allow_special_colors_legend )
{
    $initial_project_selector = "state = '{$round->project_available_state}'";

    $filter_custom_display_fields = NULL;

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

    show_projects_for_stage(
        $round,
        $initial_project_selector,
        $show_filter_block,
        $filter_custom_display_fields,
        $allow_special_colors_legend,
        $columns );
}

// -----------------------------------------------------------------------------

function show_projects_for_smooth_reading()
{
    global $pguser;

    $initial_project_selector = "state = 'proj_post_first_checked_out' AND smoothread_deadline > UNIX_TIMESTAMP()";

    $show_filter_block = TRUE;
    $filter_custom_display_fields = array("checkedoutby" => TRUE);

    $allow_special_colors_legend = TRUE;

    $columns = array(
        "nameofwork"  => _("Title"),
        "authorsname" => _("Author"),
        "language"    => _("Language"),
        "genre"       => _("Genre")
    );

    // only show PM, PP, and Uploaded if they are logged in
    if($pguser)
    {
        $columns = array_merge($columns, array(
            "username"     => _("Project Manager"),
            "checkedoutby" => _("Post Processor"),
            "uploaded"     => _("Uploaded")
        ));
    }

    $columns = array_merge($columns, array(
        "n_pages"   => _("Total Pages"),
        "days_left" => _("Days Left")
    ));


    show_projects_for_stage(
        get_Stage_for_id("SR"),
        $initial_project_selector,
        $show_filter_block,
        $filter_custom_display_fields,
        $allow_special_colors_legend,
        $columns );
}

// -----------------------------------------------------------------------------

function show_projects_for_stage(
    $stage,
    $initial_project_selector,
    $show_filter_block,
    $filter_custom_display_fields,
    $allow_special_colors_legend,
    $columns )
// Generate a chunk of HTML consisting of:
// (1) an optional filter widget,
// (2) an optional "Special Days" color legend, and
// (3) a listing of projects.
// 
// Arguments:
// $stage:
//     An instance of the Stage class (or a subclass thereof).
//     This is used for various things, but most importantly,
//     $stage->id is deemed to identify this listing.
// $initial_project_selector:
//     A string containing an SQL condition (suitable for use in a
//     WHERE clause on the 'projects' table) that selects all the
//     projects that would be listed in the absence of a user's filter.
// $show_filter_block:
//     A boolean: should we show the filter widget?
// $filter_custom_display_fields:
//     An array naming any custom fields that should appear in the filter widget.
// $allow_special_colors_legend:
//     A boolean: should we show the special colors legend?
//     (This can be overriden by the corresponding user preference.)
// $columns:
//     An array specifying which columns should appear in the listing.
{
    global $pguser;

    if ($show_filter_block)
    {
        echo "<hr width='75%'>\n";
        process_and_display_project_filter_form($pguser, $stage->id, $stage->name, $_REQUEST, $initial_project_selector, $filter_custom_display_fields);
    }
    $projects_filter = get_project_filter_sql($pguser, $stage->id);
    $filtered_project_selector = "$initial_project_selector $projects_filter";

    // special colors legend
    if ($allow_special_colors_legend )
    {
        // Show the special colors legend if
        // the requestor is a guest (not logged in),
        // or is logged in and hasn't opted to suppress it
        // via the preference "Show Special Colors: No".
        $userSettings =& Settings::get_Settings($pguser);
        if (!isset($pguser) OR !$userSettings->get_boolean('hide_special_colors'))
        {
            echo "<hr width='75%'>\n";
            echo_special_legend($initial_project_selector);
        }
    }

    // Print out the heading information and the table for the project listing.

    $user_setting = "{$stage->id}_order";

    echo "\n<a name='{$stage->id}'></a>";
    $title = _('Projects Currently Available');
    echo "\n<h3>$title</h3>";
    echo "\n<p>{$stage->description}</p>";

    show_project_listing( $stage, $user_setting, $stage->id, $columns, $filtered_project_selector );
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function show_projects_for_pool( $pool, $checkedout_or_available )
{
    global $pguser;

    if ( $checkedout_or_available == 'checkedout' )
    {
        $header = _('Books I Have Checked Out');

        echo "<h2 align='center'>$header</h2>";

        $initial_project_selector = "state = '{$pool->project_checkedout_state}'";

        // The project must be checked-out to somebody.
        // We're only interested if it's checked out to the current user.
        $initial_project_selector .= " AND checkedoutby = '$pguser'";

        echo "<a name='checkedout'></a>\n";
        $projects_filter = "";
    }
    elseif ( $checkedout_or_available == 'available' )
    {
        $header = _('Books Available for Checkout');

        echo "<h2 align='center'>$header</h2>";

        $available_filtertype_stem = "{$pool->id}_av";

        $initial_project_selector = "state = '{$pool->project_available_state}'";
        process_and_display_project_filter_form($pguser, $available_filtertype_stem, $pool->name, $_REQUEST, $initial_project_selector);

        echo "<a name='available'></a>\n";
        echo "<center><b>$header</b></center>";
        $projects_filter = get_project_filter_sql($pguser, $available_filtertype_stem);
    }
    else
    {
        assert(FALSE);
    }

    // Print out the heading information and the table for the pool
    // project listing.

    $columns = array(
        "nameofwork"  => _("Title"),
        "authorsname" => _("Author"),
        "language"    => _("Language"),
        "genre"       => _("Genre"),
        "n_pages"     => _("Pages"),
        $pool->foo_field_name => $pool->foo_Header,
        "days_avail"  => _("Days")
    );

    $filtered_project_selector = "$initial_project_selector $projects_filter";

    $ch_or_av = substr( $checkedout_or_available, 0, 2 );
    $user_setting = "{$pool->id}_{$ch_or_av}_order";

    show_project_listing( $pool, $user_setting, $checkedout_or_available, $columns, $filtered_project_selector );
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function show_project_listing( $stage, $user_setting, $anchor, $columns, $filtered_project_selector = " " )
// Create the sortable project listing.
// Arguments:
//   $stage        - stage object (Round, Pool, or Stage)
//   $user_setting - the setting value to use when saving the sort order
//   $anchor       - the anchor on the page to jump to
//   $columns      - the columns to include in the table, this is an associative
//                   array with the key being the column name and the value
//                   being the label
//   $filtered_project_selector
//                 - an SQL condition (on the 'projects' table) specifying
//                   which projects to list.
{
    global $code_url, $userP, $pguser;

    // Some columns (notably those with numbers in them)
    // have special formatting styles -- specify them here.
    $column_styles = array(
        "n_available_pages" => "text-align: right;",
        "n_pages"   => "text-align: right;",
        "days_avail"=> "text-align: right;",
        "days_left" => "text-align: right;"
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
            $filtered_project_selector
        ORDER BY
            $presort
            $orderclause
    ";
    $result = mysql_query($query);

    // Start the table.
    echo "\r\n<table class='availprojectlisting'>";
    echo "<tr align=center bgcolor='{$stage->listing_bgcolors[1]}'>";

    // Print out the header row with links.
    foreach($columns as $name => $label)
    {
        $style_attribute="";
        if(@$column_styles[$name])
            $style_attribute="style='{$column_styles[$name]}'";

        echo "\n<th $style_attribute>";

        if(!in_array($name, $unsortable_columns))
        {
            $link_sort_by=$name;
            if($name == $sort_column)
                $link_sort_by .= ":" . ($sort_direction == "asc" ? "desc" : "asc");

            echo "<a href='?{$other_settings}{$order_param}=$link_sort_by#$anchor'>";
            // Change spaces in label to <br>s to force them to wrap. This
            // improves table layout in all browsers.
            $label = preg_replace("/ /","<br>",$label);
            echo $label;
            echo "</a>";
            if($name == $sort_column)
            {
                if($sort_direction == "asc")
                    echo "&nbsp;<img src='$code_url/graphics/sort_asc.png'>";
                else
                    echo "&nbsp;<img src='$code_url/graphics/sort_desc.png'>";
            }
        }
        else
        {
            echo $label;
        }

        echo "</th>";
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

    // Free the search results.
    mysql_free_result($result);
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

    // Load user settings
    $userSettings =& Settings::get_Settings($pguser);

    // The default sort column is the first entry in $columns.
    $default_sort_column = array_shift(array_keys($columns));

    // Read new sort order from URL, if any.
    $sort_by = array_get($_GET, "{$user_setting}_sort_by", NULL);

    // If that failed $sort_by will be set to NULL in which case we use the
    // value from the database.
    if($user_setting && $sort_by == NULL)
    {
        $sort_by = $userSettings->get_value($user_setting, NULL);
    }

    // If that failed (there was no previous sort order) fall back to the
    // default value (first column).
    if($sort_by == NULL)
    {
        $sort_by = $default_sort_column;
    }

    // Parse the $sort_by into its column:direction parts.
    @list($sort_column, $sort_direction) = explode(":", $sort_by);
    // '@' to suppress "Notice: Undefined offset: 1" when $sort_by has no colon.

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
        $sort_by_old = $userSettings->get_value($user_setting, NULL);

        // If orders have changed, save them to database.
        if ($sort_by != $sort_by_old)
        {
            $userSettings->set_value($user_setting, $sort_by);
        }
    }

    return array($sort_column, $sort_direction);
}

// vim: sw=4 ts=4 expandtab
?>
