<?PHP
// Give information on smooth reading
// including (most importantly) the list of projects currently available

$relPath='../../pinc/';
include_once($relPath.'dpsession.inc');
include_once($relPath.'maintenance_mode.inc');
include_once($relPath.'theme.inc');
include_once($relPath.'site_news.inc');
include_once($relPath.'special_colors.inc');
include_once($relPath.'page_header.inc');
include_once($relPath.'filter_project_list.inc');
include_once($relPath.'forum_interface.inc');
include_once($relPath.'showavailablebooks.inc');

// the user_is functions don't work unless this has been executed previously!
// it's in dp_main.inc, but we also want this page to be accessible to 
// people who aren't logged in, so we can't include that file,
// which requries a visitor to log in
dpsession_resume();

//Check to see if we are in a maintenance mode
abort_if_in_maintenance_mode();

$locuserSettings = Settings::get_Settings($pguser);

// ---------------------------------------
//Page construction varies with whether the user is logged in or out
if (isset($GLOBALS['pguser'])) { $logged_in = TRUE;} else { $logged_in = FALSE;}


if ($logged_in) {
    // we show more columns when user is logged in, so we don't have room for the stats bar
    $no_stats = 1;
    $tcolspan = 10;
    $showPPersF = TRUE;
    $header_text = _("Smooth Reading Pool");
    theme( $header_text, 'header');
    page_header( 'SR', $header_text );
    show_news_for_page("SR");
} else {
    $showPMsF = FALSE;
    $tcolspan = 6;
    $header_text = _("Smooth Reading Pool Preview");
    theme( $header_text, 'header');
    page_header( 'SR', $header_text );
    show_news_for_page("SR_PREV");
}

echo "<h3>Smooth Reading</h3>";

if (!$logged_in)
{
    echo "
    <p style='font-size: 120%;'>
    This Preview page shows which books are currently available for Smooth Reading.
    Click on a book's title to view more information about it
    or to download the text.
    </p>

    <p>
    Please note that while unregistered guests are welcome
    to download texts for smooth reading,
    only registered volunteers are able to upload annotated texts.
    A registration link is available at the top of this page.
    </p>
    ";
}

{
    echo "
    <p style='font-size: 120%;'>
        The goal of 'Smooth Reading' is to read the text attentively, as for pleasure,
        with just a little more attention than usual to punctuation, etc.
        This is NOT full scale proof-reading, and comparison with the scans is not needed.
        Just read it as your normal, sensitized-to-proofing-errors self,
        and report any problem that disrupts the sense or the flow of the book.
        Note that some of these will be due to the author and/or publisher.
    </p>

    <p>
    The way to report errors is by adding a comment of the form
        <blockquote style='color: red; background-color: inherit;'>
        [**correction or query]<br>
        </blockquote>
    immediately after the problem spot.
    Do not correct or change the problem, just note it in the above format.
    </p>

    <h3>Examples:</h3>
    <p>
    <ul>
    <li>that was the end,[**.] However, the next day</li>
    <li>that was the end[**.] However, the next day</li>
    <li>that was the emd.[**end] However, the next day</li>
    </ul>
    </p>

    <p>
    For more information on the origin of smoothreading,
    see <a href='http://www.pgdp.net/phpBB2/viewtopic.php?t=3429'>this thread</a>.
    </p>
    ";
}


// filter block
echo "<hr width='75%'>\n";

$state_sql = " (state = 'proj_post_first_checked_out' AND smoothread_deadline > UNIX_TIMESTAMP() ) ";
process_and_display_project_filter_form($pguser, "SR", _("Smooth Reading"), $_REQUEST, $state_sql, array("checkedoutby" => TRUE));

// special colours legend
// Don't display if the user has selected the
// setting "Show Special Colors: No";
// visitors (not logged in) get to see them
if (!$logged_in OR !$locuserSettings->get_boolean('hide_special_colors'))
{
    echo "<hr width='75%'>\n";
    echo_special_legend($state_sql);
}

show_projects_available_for_smoothreading( get_Stage_for_id("SR"), get_project_filter_sql($pguser, "SR") );

theme('', 'footer');

// vim: sw=4 ts=4 expandtab
?>
