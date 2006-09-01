<?
$relPath="./../../pinc/";
include($relPath.'dp_main.inc');
include_once($relPath.'theme.inc');
include_once($relPath.'user_is.inc');
include_once($relPath.'site_news.inc');

if ( !(user_is_a_sitemanager() or user_is_site_news_editor()) )
{
    echo "You are not authorized to use this form.";
    exit;
}

if (isset($_GET['news_page_id'])) {
    $news_page_id = $_GET['news_page_id'];
    if ( isset($NEWS_PAGES[$news_page_id]) )
    {
        $news_subject = get_news_subject($news_page_id);
        $title = sprintf('News Desk for %s', $news_subject );
        theme($title, "header");
        echo "<br>";
        echo "<a href='sitenews.php'>"._("Site News Central")."</a><br>";
        echo "<h1 align='center'>$title</h1>";
        echo "<br>\n";
        handle_any_requested_db_updates( $news_page_id );
        show_item_editor( $news_page_id );
        show_all_news_items_for_page( $news_page_id );
        theme("", "footer");
    } else {
        echo _("Error").": <b>".$news_page_id."</b> "._("Unknown news_page_id specified, exiting.");
    }
} else {

    theme("Site News Central", "header");

    echo "<h1>"._("Site News Central")."</h1>";
    echo "<br><br><font size = +1><ul>";
    echo "\n";
    foreach ( $NEWS_PAGES as $news_page_id => $news_subject )
    {
        echo "<li>";

        $news_subject = get_news_subject($news_page_id);
        $link = "<a href='sitenews.php?news_page_id=$news_page_id'>$news_subject</a>";
        echo sprintf( _("Edit Site News for %s"), $link );
        echo "\n";

        $date_changed = get_news_page_last_modified_date( $news_page_id );
        if ( !is_null($date_changed) ) {
            $last_modified = strftime(_("%A, %B %e, %Y"), $date_changed);
            echo "<br>". _("Last modified : ").$last_modified;
        }
        echo "<br><br>";
        echo "\n";
    }
    echo "</ul></font>";
    theme('','footer');
}

// Everything else is just function declarations.

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function handle_any_requested_db_updates( $news_page_id )
{
    switch( @$_GET['action'] )
    {
        case 'add':
            // Save a new site news item
            $content = strip_tags($_POST['content'], '<a><b><i><u><font><img>');
            $content = nl2br($content);
            $date_posted = time();
            $insert_news = mysql_query("
                INSERT INTO news_items
                SET
                    id           = NULL,
                    news_page_id = '$news_page_id',
                    status       = 'current',
                    date_posted  = '$date_posted',
                    content      = '$content'
            ");
            // by default, new items go at the top
            $update_news = mysql_query("
                UPDATE news_items SET ordering = id WHERE id = LAST_INSERT_ID()
            ");
            news_change_made($news_page_id);
            break;

        case 'delete':
            // Delete a specific site news item
            $item_id = $_GET['item_id'];
            $result = mysql_query("DELETE FROM news_items WHERE id=$item_id");
            break;

        case 'display':
            // Display a specific site news item
            $item_id = $_GET['item_id'];
            $result = mysql_query("UPDATE news_items SET status = 'current' WHERE id=$item_id");
            news_change_made($news_page_id);
            break;

        case 'hide':
            // Hide a specific site news item
            $item_id = $_GET['item_id'];
            $result = mysql_query("UPDATE news_items SET status = 'recent' WHERE id=$item_id");
            news_change_made($news_page_id);
            break;

        case 'archive':
            // Archive a specific site news item
            $item_id = $_GET['item_id'];
            $result = mysql_query("UPDATE news_items SET status = 'archived' WHERE id=$item_id");
            break;

        case 'unarchive':
            // Unarchive a specific site news item
            $item_id = $_GET['item_id'];
            $result = mysql_query("UPDATE news_items SET status = 'recent' WHERE id=$item_id");
            break;

        case 'moveup':
            // Move a specific site news item higher in the display list
            $item_id = $_GET['item_id'];
            move_news_item ($news_page_id, $item_id, 'up');
            news_change_made($news_page_id);
            break;

        case 'movedown':
            // Move a specific site news item lower in the display list
            $item_id = $_GET['item_id'];
            move_news_item ($news_page_id, $item_id, 'down');
            news_change_made($news_page_id);
            break;

        case 'edit_update':
            // Save an update to a specific site news item
            $content = $_POST['content'];
            $content = strip_tags($_POST['content'], '<a><b><i><u><font><img>');
            $content = nl2br($content);
            $item_id = $_POST['item_id'];
            $result = mysql_query("UPDATE news_items SET content='$content' WHERE id=$item_id");
            $result = mysql_query("SELECT status FROM news_items WHERE id=$item_id");
            $row = mysql_fetch_assoc($result);
            $visible_change_made = ($row['status'] == 'current');
            if ($visible_change_made) {news_change_made($news_page_id);}
            break;
    }
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function show_item_editor( $news_page_id )
// Show a form:
// -- to edit the text of an existing item (if requested), or
// -- to compose a new item (otherwise).
{
    if (isset($_GET['action']) && $_GET['action'] == "edit") {
        $item_id = $_GET['item_id'];
        $result = mysql_query("SELECT * FROM news_items WHERE id=$item_id");
        $initial_content = mysql_result($result,0,"content");
        $action_to_request = "edit_update";
        $submit_button_label = "Edit News Item";
    } else {
        $item_id = "";
        $initial_content = "";
        $action_to_request = "add";
        $submit_button_label = "Add News Item";
    }

    echo "<form action='sitenews.php?news_page_id=$news_page_id&action=$action_to_request' method='post'>";
    echo "<center>";
    echo "<textarea name='content' cols='80' rows='8'>$initial_content</textarea>";
    echo "<br>\n";
    echo "<input type='submit' value='$submit_button_label' name='submit'>";
    echo "</center>";
    echo "<br>\n";
    echo "<br>\n";
    echo "<input type='hidden' name='item_id' value='$item_id'>";
    echo "</form>";
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function show_all_news_items_for_page( $news_page_id )
{
    // three categories:
    // 1) current (currently displayed on page every time)
    // 2) recent (displayed on "Recent News", and one shown as Random)
    // 3) archived (not visible to users at all, saved for later use or historical interest)

    $categories = array(
        array(
            'status'   => 'current',
            'title'    => _('Fixed News Items'),
            'blurb'    => _("All of these items are shown every time the page is loaded. Most important and recent news items go here, where they are guaranteed to be displayed."),
            'order_by' => 'ordering DESC',
            'actions'  => array(
                'hide'     => _('Make Random'),
                'moveup'   => _('Move Higher'),
                'movedown' => _('Move Lower'),
            ),
        ),

        array(
            'status'   => 'recent',
            'title'    => _('Random/Recent News Items'),
            'blurb'    => _("This is the pool of available random news items for this page. Every time the page is loaded, a randomly selected one of these items is displayed."),
            'order_by' => 'ordering DESC',
            'actions'  => array(
                'display' => 'Make Fixed',
                'archive' => 'Archive Item',
            ),
        ),

        array(
            'status'   => 'archived',
            'title'    => _('Archived News Items'),
            'blurb'    => _("Items here are not visible anywhere, and can be safely stored here until they become current again."),
            'order_by' => 'id DESC',
            'actions'  => array(
                'unarchive' => 'Unarchive Item',
            ),
        ),
    );

    foreach ( $categories as $category )
    {
        $status = $category['status'];

        $result = mysql_query("
            SELECT *
            FROM news_items
            WHERE news_page_id = '$news_page_id' AND status = '$status'
            ORDER BY {$category['order_by']}
        ");

        if (mysql_num_rows($result) == 0) continue;

        echo "<hr size='5'>\n";
        echo "<font size=+2><b>{$category['title']}</b></font>";
        if ($status == 'current')
        {
            $date_changed = get_news_page_last_modified_date( $news_page_id );
            $last_modified = strftime(_("%A, %B %e, %Y"), $date_changed);
            echo "&nbsp;&nbsp; ("._("Last modified: ").$last_modified.")";
        }
        echo "<br><br>";
        echo $category['blurb'];
        echo "<br><br>";
        echo "\n";

        $actions = $category['actions'] +
            array(
                'edit'     => 'Edit',
                'delete'   => 'Delete',
            );

        while($news_item = mysql_fetch_array($result))
        {
            echo "<hr width='70%' align='left'>\n";
            $date_posted = strftime(_("%A, %B %e, %Y"),$news_item['date_posted']);
            foreach ( $actions as $action => $label )
            {
                $url = "sitenews.php?news_page_id=$news_page_id&item_id={$news_item['id']}&action=$action";
                echo "[<a href='$url'>$label</a>]\n";
            }
            echo " -- ($date_posted)<br><br>";
            echo "\n";
            echo $news_item['content']."<br><br>";
            echo "\n";
        }
    }
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function news_change_made ($news_page_id) {
    $date_changed = time();
    $result = mysql_query("
            REPLACE INTO news_pages
            SET news_page_id = '$news_page_id', t_last_change = $date_changed
    ");
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function move_news_item ($news_page_id, $id_of_item_to_move, $direction) {

    $result = mysql_query("
        SELECT * FROM news_items
        WHERE news_page_id = '$news_page_id' AND
            status = 'current'
        ORDER BY ordering
    ");

    $i = 1 ;
    while ($news_item = mysql_fetch_assoc($result)) {
        $curr_id = $news_item['id'];
        $update_query = mysql_query("
            UPDATE news_items SET ordering = $i WHERE id = $curr_id
        ");
        if (intval($curr_id) == intval($id_of_item_to_move)) {$old_pos = $i;}
        $i++;
    }

    if (isset($old_pos)) {
        if ($direction == 'up') {
            $result = mysql_query("
                UPDATE news_items SET ordering = $old_pos
                WHERE news_page_id = '$news_page_id' AND status = 'current' AND ordering = ($old_pos + 1)
            ");
            $result = mysql_query("
                UPDATE news_items SET ordering = $old_pos + 1 WHERE id = $id_of_item_to_move
            ");
        } else {
            $result = mysql_query("
                UPDATE news_items SET ordering = $old_pos
                WHERE news_page_id = '$news_page_id' AND status = 'current' AND ordering = ($old_pos - 1)
            ");
            $result = mysql_query("
                UPDATE news_items SET ordering = $old_pos - 1 WHERE id = $id_of_item_to_move
            ");
        }
    }
}

// vim: sw=4 ts=4 expandtab
?>
