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

    $ext_sort_param_name = "order{$stage->id}";
    $ext_sort_setting_name = "{$stage->id}_order";

    $anchor = $stage->id;
    echo "\n<a name='$anchor'></a>";
    $title = _('Projects Currently Available');
    echo "\n<h3>$title</h3>";
    echo "\n<p>{$stage->description}</p>";

    show_project_listing(
        $filtered_project_selector,
        $columns,
        $ext_sort_param_name,
        $ext_sort_setting_name,
        $anchor,
        $stage
    );
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function show_projects_for_pool( $pool, $checkedout_or_available )
{
    global $pguser;

    $anchor = $checkedout_or_available;

    if ( $checkedout_or_available == 'checkedout' )
    {
        $header = _('Books I Have Checked Out');

        echo "<h2 align='center'>$header</h2>";

        $initial_project_selector = "state = '{$pool->project_checkedout_state}'";

        // The project must be checked-out to somebody.
        // We're only interested if it's checked out to the current user.
        $initial_project_selector .= " AND checkedoutby = '$pguser'";

        echo "<a name='$anchor'></a>\n";
        $projects_filter = "";
    }
    elseif ( $checkedout_or_available == 'available' )
    {
        $header = _('Books Available for Checkout');

        echo "<h2 align='center'>$header</h2>";

        $available_filtertype_stem = "{$pool->id}_av";

        $initial_project_selector = "state = '{$pool->project_available_state}'";
        process_and_display_project_filter_form($pguser, $available_filtertype_stem, $pool->name, $_REQUEST, $initial_project_selector);

        echo "<a name='$anchor'></a>\n";
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
    $ext_sort_param_name = "order_{$checkedout_or_available}";
    $ext_sort_setting_name = "{$pool->id}_{$ch_or_av}_order";

    show_project_listing(
        $filtered_project_selector,
        $columns,
        $ext_sort_param_name,
        $ext_sort_setting_name,
        $anchor,
        $pool
    );
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

// Create the sortable project listing.
function show_project_listing(

    $filtered_project_selector,
        // an SQL condition (on the 'projects' table) specifying
        // which projects to list.

    $columns,
        // the columns to include in the table, this is an associative
        // array with the key being the column name and the value
        // being the label

    $ext_sort_param_name,
        // The parameter-name to use when specifying a sort order
        // in a URL query-string.
    $ext_sort_setting_name,
        // the setting value to use when saving the sort order

    $anchor,
        // the anchor on the page to jump to

    $stage
        // stage object (Round, Pool, or Stage)
)
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
    list($curr_sql_sort_col, $curr_sql_sort_dir) =
        process_sorting_control($columns, $ext_sort_param_name, $ext_sort_setting_name);

    $curr_sql_orderclause = "$curr_sql_sort_col $curr_sql_sort_dir";

    // Sort on nameofwork ASC as a secondary criteria if it isn't the primary
    // criteria.
    if($curr_sql_sort_col != "nameofwork")
    {
        $curr_sql_orderclause .= ", nameofwork ASC";
    }

    // The originating request may have query-string settings (other than
    // for $ext_sort_param_name). We should preserve those, and just append the
    // setting for $ext_sort_param_name.
    $other_settings = '';
    foreach ( $_GET as $name => $value )
    {
        if ( $name != $ext_sort_param_name )
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
            $curr_sql_orderclause
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
            if($name == $curr_sql_sort_col)
                $link_sort_by .= ":" . ($curr_sql_sort_dir == "asc" ? "desc" : "asc");

            echo "<a href='?{$other_settings}{$ext_sort_param_name}=$link_sort_by#$anchor'>";
            // Change spaces in label to <br>s to force them to wrap. This
            // improves table layout in all browsers.
            $label = preg_replace("/ /","<br>",$label);
            echo $label;
            echo "</a>";
            if($name == $curr_sql_sort_col)
            {
                if($curr_sql_sort_dir == "asc")
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
                $genre = $book['genre'];
                if ($book['difficulty'] == "beginner")
                {
                    if( is_a( $stage, 'Round' ) )
                    {
                        if ( $stage->is_a_mentee_round() )
                            $genre = _("BEGINNERS ONLY")." ".$genre;
                        else if ( $stage->is_a_mentor_round() )
                            $genre = _("MENTORS ONLY")." ".$genre;
                    }
                }
                elseif ($book['difficulty'] == "easy")
                {
                    $genre = _("EASY")." ".$genre;
                }
                elseif ($book['difficulty'] == "hard")
                {
                    $genre = _("HARD")." ".$genre;
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

function process_sorting_control($columns, $ext_sort_param_name, $ext_sort_setting_name)
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
//   $ext_sort_param_name - the parameter-name to use in the URL query-string
//   $ext_sort_setting_name - the setting to use in the usersettings table. This value
//                   is also used in determining the sort order via the URL.
//                   Doing so allows for multiple tables to be on the same
//                   page and sorted in different orders.
// Returns the array($curr_sql_sort_col, $curr_sql_sort_dir):
//   $curr_sql_sort_col - name of the column to sort by
//   $curr_sql_sort_dir - direction to sort by (asc or desc)
{
    global $pguser;

    // Load user settings
    $userSettings =& Settings::get_Settings($pguser);

    // The default sort column is the first entry in $columns.
    $default_sort_column = array_shift(array_keys($columns));

    // We get an ext_sort from one of three places,
    // which we consult in the following order,
    // stopping as soon as we get a non-NULL value.
    $ext_sort_providers = array(

        // A parameter in the invoking URL's query-string:
        array(
            sprintf( _("URL parameter '%s'"), $ext_sort_param_name ),
            @$_GET[$ext_sort_param_name] ),

        // A setting in the usersettings table in the DB (accessed via Setting class):
        array(
            sprintf( _("DB setting '%s'"), $ext_sort_setting_name ),
            $userSettings->get_value($ext_sort_setting_name) ),

        // A fallback default:
        array(
            _("site default"),
            $default_sort_column ),

        // And if none of those works, give up.
        'ABORT'
    );

    foreach ( $ext_sort_providers as $ext_sort_provider )
    {
        if ($ext_sort_provider == 'ABORT')
        {
            echo "\n<p>";
            echo _("system error: the fallback default failed!");
            echo "</p>";
            exit;
        }

        list($provider_noun, $curr_ext_sort) = $ext_sort_provider;
        if (1) // debug trace
        {
            echo "\n<p>";
            echo sprintf(
                    _("%s supplies value '%s'"),
                    $provider_noun, $curr_ext_sort );
            echo "</p>";
        }

        if ( is_null($curr_ext_sort) )
        {
            // This (potential) provider isn't supplying a value this time.
            continue;
        }

        // Parse the $curr_ext_sort into its column:direction parts.
        @list($curr_sql_sort_col, $curr_sql_sort_dir) = explode(":", $curr_ext_sort);
        // '@' to suppress "Notice: Undefined offset: 1" when $curr_ext_sort has no colon.

        // Confirm $curr_sql_sort_col is in $columns and if not, skip to the next provider.
        if(!array_key_exists($curr_sql_sort_col, $columns))
        {
            continue;
        }

        // Default to ascending for the sort direction if none is specified
        // or if we don't recognize the sort direction.
        if(empty($curr_sql_sort_dir) || !in_array($curr_sql_sort_dir, array("asc", "desc")))
        {
            $curr_sql_sort_dir="asc";
        }

        // This provider supplied a valid sort value, so look no further.
        break;
    }

    // Recombine into $curr_ext_sort after error checking and correction.
    $curr_ext_sort="$curr_sql_sort_col:$curr_sql_sort_dir";

    // Save the results to the database.
    {
        // Load the previous search criteria.
        $saved_ext_sort = $userSettings->get_value($ext_sort_setting_name, NULL);

        // If orders have changed, save them to database.
        if ($curr_ext_sort != $saved_ext_sort)
        {
            $userSettings->set_value($ext_sort_setting_name, $curr_ext_sort);
        }
    }

    return array($curr_sql_sort_col, $curr_sql_sort_dir);
}

// vim: sw=4 ts=4 expandtab
?>
