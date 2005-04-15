<?PHP
// Give information on smooth reading
// including (most importantly) the list of projects currently available

$relPath='../../pinc/';
include_once($relPath.'dpsession.inc');
include_once($relPath.'maintenance_mode.inc');
include_once($relPath.'theme.inc');
include_once($relPath.'site_news.inc');
include_once($relPath.'bookpages.inc');
include_once($relPath.'special_colors.inc');

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
    echo "<h1>{$header_text}</h1>\n";
    show_site_news_for_page("smooth_reading.php");
    random_news_item_for_page("smooth_reading.php");
} else {
    $showPMsF = FALSE;
    $tcolspan = 6;
    $header_text = _("Smooth Reading Pool Preview");
    theme( $header_text, 'header');
    echo "<h1>{$header_text}</h1>\n";
    show_site_news_for_page("smooth_reading.phpPUBLIC");
    random_news_item_for_page("smooth_reading.phpPUBLIC");
};


// filter block
echo "<hr width='75%'>\n";

$state_sql = " (state = 'proj_post_first_checked_out' AND smoothread_deadline > UNIX_TIMESTAMP() ) ";
$label = "Smooth Reading";
$filtertype_stem = "SR";
include_once($relPath.'filter_project_list.inc');

// if not logged in, don't keep filter setting
if (!$logged_in) {
    $result = mysql_query("
        DELETE
        FROM user_filters
        WHERE username = '' and filtertype like 'SR%'
    ");
}

if (!isset($RFilter)) { $RFilter = ""; }

// special colours legend
// Don't display if the user has selected the
// setting "Show Special Colors: No";
// visitors (not logged in) get to see them
if (!$logged_in OR !$locuserSettings->get_boolean('hide_special_colors'))
{
    echo "<hr width='75%'>\n";
    echo "<p><font face='{$theme['font_mainbody']}'>\n";
    echo_special_legend($state_sql);
    echo "</font></p><br>\n";
}

// read saved sort orders from user_settings table;
// there won't be any for non-logged in visitors

$setting = 'SR_order';

if (!$logged_in) {
        $order_old = 'DaysA';
} else {
    $result = mysql_query("
        SELECT value
        FROM usersettings
        WHERE username = '$pguser' AND setting = '$setting'
    ");
    if (mysql_num_rows($result) >= 1)  {
        $order_old = mysql_result($result, 0, "value");
    } else {
        $order_old = 'DaysA';
    }
}


// read new sort order from url, if any
$url_param = "orderSR";
$order_new = (isset($_GET[$url_param]) ? $_GET[$url_param] : $order_old );

// if orders have changed for a logged in user, save them to database
if ($logged_in AND ($order_new != $order_old))
{
        $result = mysql_query("
                DELETE FROM usersettings
                WHERE username = '$pguser' AND setting = '$setting'
        ");
        $result = mysql_query("
                INSERT INTO usersettings
                VALUES ('$pguser', '$setting', '$order_new')
        ");
}


$listing_bgcolors =  array(0 => "#CCFFCC", 1 => "#CCFF99");

echo "\r\n<table border=1>";
echo "\r\n<tr bgcolor='$listing_bgcolors[1]'>";

$title = _('Projects Currently Available for Smooth Reading');
echo "\n<td colspan='$tcolspan'><h3>$title</h3></td>";

$order = $order_new;

$flip_title = FALSE;
$flip_author = FALSE;
$flip_lang = FALSE;
$flip_genre = FALSE;
$flip_PM = FALSE;
$flip_PP = FALSE;
$flip_PgTot = FALSE;
$flip_days = FALSE;

if ( $order == 'TitleA' )
{
        $orderclause = 'nameofwork ASC';
        $flip_title = TRUE;
}
elseif ( $order == 'TitleD' )
{
        $orderclause = 'nameofwork DESC';
}
elseif ( $order == 'AuthorA' )
{
        $orderclause = 'authorsname ASC, nameofwork ASC';
        $flip_author = TRUE;
}
elseif ( $order == 'AuthorD' )
{
        $orderclause = 'authorsname DESC, nameofwork ASC';
}
elseif ( $order == 'LangA' )
{
        $orderclause = 'language ASC, nameofwork ASC';
        $flip_lang = TRUE;
}
elseif ( $order == 'LangD' )
{
        $orderclause = 'language DESC, nameofwork ASC';
}
elseif ( $order == 'GenreA' )
{
        $orderclause = 'genre ASC, nameofwork ASC';
        $flip_genre = TRUE;
}
elseif ( $order == 'GenreD' )
{
        $orderclause = 'genre DESC, nameofwork ASC';
}
elseif ( $order == 'PMA' )
{
        $orderclause = 'projects.username ASC, nameofwork ASC';
        $flip_PM = TRUE;
}
elseif ( $order == 'PMD' )
{
        $orderclause = 'projects.username DESC, nameofwork ASC';
}
elseif ( $order == 'PPA' )
{
        $orderclause = 'checkedoutby ASC, nameofwork ASC';
        $flip_PP = TRUE;
}
elseif ( $order == 'PPD' )
{
        $orderclause = 'checkedoutby DESC, nameofwork ASC';
}

elseif ( $order == 'PgTotA' )
{
        $orderclause = 'total_pages ASC, nameofwork ASC';
        $flip_PgTot = TRUE;
}
elseif ( $order == 'PgTotD' )
{
        $orderclause = 'total_pages DESC, nameofwork ASC';
}
elseif ( $order == 'DaysA' )
{
        $orderclause = 'smoothread_deadline ASC, nameofwork ASC';
        $flip_days = TRUE;
}
elseif ( $order == 'DaysD' )
{
        $orderclause = 'smoothread_deadline DESC, nameofwork ASC';
}
else
{
        echo "smooth_reading.php: bad order value: '$order'";
        exit;
}


global $pageCountArray;

if (empty($pageCountArray)) {
    update_pageCountArray();
}


// make sure we have a figure for total pages of every available book

$query = "
          SELECT projectid
          FROM projects
          WHERE state = '{$round->project_available_state}'
";

$result = mysql_query($query);

$numrows = mysql_num_rows($result);
$rownum = 0;

while ($rownum < $numrows) {
        $book=mysql_fetch_assoc($result);
        if ($pageCountArray[$book['projectid']]['avail_pages'] == 0) {
                project_update_page_counts( $book['projectid'] );
                update_pageCountArray();
        }
        $rownum++;
}

$order_param = "orderSR";

// The originating request may have query-string settings (other than
// for $order_param). We should preserve those, and just append the
// setting for $order_param.
$other_settings = '';
foreach ( $_GET as $name => $value )
{
        if ( $name != $order_param )
        {
                $other_settings .= "$name=$value&amp;";
        }
}

$linkbase = "<a href='?{$other_settings}{$order_param}=";
$linkend = "'";

$query = "
        SELECT projects.*,
                page_counts.total_pages,
                phpbb_users.user_id, 
                round((smoothread_deadline - unix_timestamp())/(24 * 60 * 60)) AS days_left,
                projects.username as PM
        FROM projects
            LEFT OUTER JOIN page_counts
                USING (projectid)
            LEFT OUTER JOIN phpbb_users
                ON (phpbb_users.username = projects.checkedoutby)
        WHERE
                state = 'proj_post_first_checked_out'
                AND smoothread_deadline > UNIX_TIMESTAMP()
                $RFilter
        ORDER BY
                $orderclause
";
$result = mysql_query($query);

echo "<tr align=center bgcolor='{$listing_bgcolors[1]}'>";

$word = _("Title");
$link = $linkbase.($flip_title?"TitleD":"TitleA")."$linkend>";
echo "\n<td>$link<b>$word</b></a></td>";

$word = _("Author");
$link = $linkbase.($flip_author?"AuthorD":"AuthorA")."$linkend>";
echo "\n<td>$link<b>$word</b></a></td>";

$word = _("Language");
$link = $linkbase.($flip_lang?"LangD":"LangA")."$linkend>";
echo "\n<td>$link<b>$word</b></a></td>";

$word = _("Genre");
$link = $linkbase.($flip_genre?"GenreD":"GenreA")."$linkend>";
echo "\n<td>$link<b>$word</b></a></td>";

if ($logged_in) {

    $word = _("Project Manager");
    $link = $linkbase.($flip_PM?"PMD":"PMA")."$linkend>";
    echo "\n<td>$link<b>$word</b></a></td>";

    $word = _("Post Processor");
    $link = $linkbase.($flip_PP?"PPD":"PPA")."$linkend>";
    echo "\n<td>$link<b>$word</b></a></td>";

    // no point sorting by this link
    $word = _("More Info");
    echo "\n<td><b>$word</b></td>";

    // difficult to sort by this link(?)
    $word = _("Uploaded");
    echo "\n<td><b>$word</b></td>";

}

$word = _("Total Pages");
$link = $linkbase.($flip_PgTot?"PgTotD":"PgTotA")."$linkend>";
echo "\n<td>$link<b>$word</b></a></td>";

$word = _("Days Left");
$link = $linkbase.($flip_days?"DaysD":"DaysA")."$linkend>";
echo "\n<td>$link<b>$word</b></a></td>";

echo "</tr>";

// Determine whether to use special colors or not
// (this does not affect the alternating between two
// background colors) in the project listing.
if ($logged_in) {
    global $pguser;
    $userSettings = Settings::get_Settings($pguser);
    $show_special_colors = !$userSettings->get_boolean('hide_special_colors');
} else {
    // visitors get the colours
    $show_special_colors = TRUE;
}

$numrows = mysql_num_rows($result);
$rownum = 0;
$rownum2 = 0;

while ($rownum2 < $numrows) {
        $book=mysql_fetch_assoc($result);
        $bgcolor = $listing_bgcolors[$rownum % 2];

        // Special colours for special books of various types
        if ($show_special_colors) {
            $special_color = get_special_color_for_project($book);
            if (!is_null($special_color)) {
                $bgcolor = $special_color;
            }
        }

        if (TRUE) {

            $pm = $book['PM'];

            echo "<tr bgcolor='$bgcolor'>";
            $prid = $book['projectid'];
            $p = "$prid/$prid"."_smooth_avail.zip";
            $url = "$projects_url/$p";

            $temp="<a href=\"$url\">";
            $temp.="{$book['nameofwork']}</a>";

            echo "\n<td>$temp</td>";
            echo "\n<td>{$book['authorsname']}</td>";
            echo "\n<td>{$book['language']}</td>";
            if ($book['difficulty'] == "beginner")  {
                $genre = _("BEGINNERS")." ".$book['genre'];
            } elseif ($book['difficulty'] == "easy") {
                $genre = _("EASY")." ".$book['genre'];
            } elseif ($book['difficulty'] == "hard") {
                $genre = _("HARD")." ".$book['genre'];
            } else {
                $genre = $book['genre'];
            }
            echo "\n<td>$genre</td>";

            if ($logged_in) {
                echo "\n<td>$pm</td>";
                echo "\n<td><a href='$forums_url/privmsg.php?mode=post&u=".$book['user_id']."'>{$book['checkedoutby']}</a></td>";
                echo "\n<td><a href='$code_url/project.php?id=$prid&amp;expected_state=".PROJ_POST_FIRST_CHECKED_OUT."'>"._("Project Info and Upload")."</a></td>";

                global $projects_dir;
                if ($done_files = glob("$projects_dir/$prid/*smooth_done_*.zip") ) {
                   $num_done = count($done_files); 
                } else {
                   $num_done = 0;
                }

                echo "\n<td align=center>".$num_done."</td>";

            } 

            echo "\n<td align=center>{$book['total_pages']}</td>";

            echo "\n<td align=center>{$book['days_left']}</td>";

        } else {
            $rownum--;
        }
        $rownum++;
        $rownum2++;
}

echo "</table>\n<br>";

theme('', 'footer');

// vim: sw=4 ts=4 expandtab
?>
