<?
$relPath="./../../pinc/";
include($relPath.'dp_main.inc');
ob_start();
include_once($relPath.'theme.inc');
include_once($relPath.'user_is.inc');

if ( user_is_a_sitemanager() or user_is_site_news_editor()) {

    if (isset($_GET['news_page'])) {
        $news_page = $_GET['news_page'];
        $type_result = mysql_query("SELECT * FROM news_pages WHERE news_page_id = '$news_page'");
        if ($news_type_row = mysql_fetch_assoc($type_result)) {
            $news_type = _($news_type_row['news_type']);       
            $last_modified = strftime(_("%A, %B %e, %Y"), $news_type_row['modifieddate']); 
            theme("Site News Update for ".$news_type, "header");
            echo "<br>";
            echo "<a href='sitenews.php'>"._("Site News Central")."</a><br>";
        } else {
           echo _("Error").": <b>".$news_page."</b> "._("Unknown news_page specified, exiting.");
           exit();
        }
    } else {

        theme("Site News Central", "header");
        $type_result = mysql_query("SELECT * FROM news_pages WHERE 1 = 1 order by news_type");

        if ($type_result) {

            echo "<h1>"._("Site News Central")."</h1>";
            echo "<br><br><font size = +1><ul>";
            while ($news_type_row = mysql_fetch_assoc($type_result)) {
                $news_page_id = $news_type_row['news_page_id'];
                $news_type = _($news_type_row['news_type']);       
                $last_modified = strftime(_("%A, %B %e, %Y"), $news_type_row['modifieddate']); 
                echo "<li>"._("Edit Site News for ")."<a href='sitenews.php?news_page=".$news_page_id."'>".
                    $news_type."</a> "._("Last modified : ").$last_modified."<br><br>";
             }
             echo "</ul></font>";
       }
       theme('','footer');
       exit();
    }


    // Save a new site news item
    if (isset($_GET['action']) && $_GET['action'] == "add") {
        $message = strip_tags($_POST['message'], '<a><b><i><u><font><img>');
        $message = nl2br($message);
        $date_posted = time();
        $insert_news = mysql_query("
            INSERT INTO news (news_page_id,uid, display, date_posted, message) 
            VALUES ('$news_page',NULL, 1,'$date_posted', '$message')
        ");
        // by default, new items go at the top
        $update_news = mysql_query("
            UPDATE news SET ordering = uid WHERE uid = LAST_INSERT_ID()
        ");
        news_change_made($news_page);
        header("Location: sitenews.php?news_page=$news_page");
    }
    // View a specific site news item
    elseif (isset($_GET['action']) && $_GET['$action'] == "view") {
        $uid = $_GET['uid'];
        $result = mysql_query("SELECT * FROM news WHERE uid = $uid");
        $date_posted = strftime(_("%A, %B %e, %Y"),mysql_result($result,0,'date_posted'));
        echo "<b>$date_posted</b><br>";
        echo mysql_result($result,0,"message");
        echo "<br><br><a href='javascript:history.back()'>Go Back...</a>";
    }
    // Delete a specific site news item
    elseif (isset($_GET['action']) && $_GET['action'] == "delete") {
        $uid = $_GET['uid'];
        $result = mysql_query("DELETE FROM news WHERE uid=$uid");
        header("Location: sitenews.php?news_page=$news_page");
    }
    // Display a specific site news item
    elseif (isset($_GET['action']) && $_GET['action'] == "display") {
        $uid = $_GET['uid'];
        $result = mysql_query("UPDATE news SET display = 1 WHERE uid=$uid");
        news_change_made($news_page);
        header("Location: sitenews.php?news_page=$news_page");
    }
    // Hide a specific site news item
    elseif (isset($_GET['action']) && $_GET['action'] == "hide") {
        $uid = $_GET['uid'];
        $result = mysql_query("UPDATE news SET display = 0 WHERE uid=$uid");
        news_change_made($news_page);
        header("Location: sitenews.php?news_page=$news_page");
    }
    // Archive a specific site news item
    elseif (isset($_GET['action']) && $_GET['action'] == "archive") {
        $uid = $_GET['uid'];
        $result = mysql_query("UPDATE news SET archive = 1, display = 0 WHERE uid=$uid");
        header("Location: sitenews.php?news_page=$news_page");
    }
    // Unarchive a specific site news item
    elseif (isset($_GET['action']) && $_GET['action'] == "unarchive") {
        $uid = $_GET['uid'];
        $result = mysql_query("UPDATE news SET archive = 0, display = 0 WHERE uid=$uid");
        header("Location: sitenews.php?news_page=$news_page");
    }
    // Move a specific site news item higher in the display list
    elseif (isset($_GET['action']) && $_GET['action'] == "moveup") {
        $uid = $_GET['uid'];
        move_news_item ($news_page, $uid, 'up');
        news_change_made($news_page);
        header("Location: sitenews.php?news_page=$news_page");
    }
    // Move a specific site news item lower in the display list
    elseif (isset($_GET['action']) && $_GET['action'] == "movedown") {
        $uid = $_GET['uid'];
        move_news_item ($news_page, $uid, 'down');
        news_change_made($news_page);
        header("Location: sitenews.php?news_page=$news_page");
    }
    // Save an update to a specific site news item
    elseif (isset($_GET['action']) && $_GET['action'] == "edit_update") {
        $message = $_POST['message'];
        $message = strip_tags($_POST['message'], '<a><b><i><u><font><img>');
        $message = nl2br($message);
        $uid = $_POST['uid'];
        $result = mysql_query("UPDATE news SET message='$message' WHERE uid=$uid");
        $result = mysql_query("SELECT display FROM news WHERE uid=$uid");
        $row = mysql_fetch_assoc($result);
        $visible_change_made = ($row['display'] == 1);
        if ($visible_change_made) {news_change_made($news_page);}
        header("Location: sitenews.php?news_page=$news_page");
    }
    // Add/Edit form for a specific site news item
    else {
        $action = "add";
        $submit_query = "Add Site News Item for ".$news_type;
        if (isset($_GET['action']) && $_GET['action'] == "edit") {
            $uid = $_GET['uid'];
            $result = mysql_query("SELECT * FROM news WHERE uid=$uid");
            $message = mysql_result($result,0,"message");
            $action = "edit_update";
            $submit_query = "Edit Site News Item for ".$news_type;
        }
        if (empty($message)) { $message = ""; }

        echo "<form action='sitenews.php?news_page=$news_page&action=$action' method='post'>";
        echo "<center><textarea name='message' cols=50 rows=5>$message</textarea><br><input type='submit' value='$submit_query' name='submit'></center><br><br>";
        echo "<input type='hidden' name='uid' value='$uid'></form>";

        // show all news items for this page
        // three categories:
        // 1) visible (currently displayed on page every time)
        // 2) hidden (displayed on "Recent News", and one shown as Random)
        // 3) archived (not visible to users at all, saved for later use or historical interest)

        $result = mysql_query("
            SELECT * 
            FROM news 
            WHERE news_page_id = '$news_page' AND (archive IS NULL OR archive != 1) 
            ORDER BY display DESC, ordering DESC
        ");

        if (mysql_numrows($result) > 0) {

            $first_hidden = 1;
            $first_vis = 1;

            echo "<font size=+2><b>"._("Fixed News Items for ").$news_type.
                "</b></font>&nbsp;&nbsp; ("._("Last modified: ").$last_modified.")<hr><br><br>";

            echo _("All of these items are shown every time the page is loaded. Most important and recent news items go here, where they are guaranteed to be displayed.")."<br><br>";

            while($row = mysql_fetch_array($result)) {
                $date_posted = strftime(_("%A, %B %e, %Y"),$row['date_posted']);
                $visible = $row['display'];
                $base_url = "[<a href='sitenews.php?news_page=$news_page&uid=".$row['uid']."&action="; 
                if ($visible == 1) {
                    echo $base_url."hide'>"._("Make Random")."</a>]&nbsp;";
                    if ($first_vis == 1) {
                       $first_vis = 0;
                    } else {
                        echo $base_url."moveup'>"._("Move Higher")."</a>]&nbsp;";
                    }
                   echo $base_url."movedown'>"._("Move Lower")."</a>]&nbsp;";
                } else {
                    if ($first_hidden == 1) {
                        echo "<br><br><font size=+2><b>"._("Random News Items for ").$news_type.
                           _(" (Also appear as 'Recent News')")."</b></font><hr><br><br>";
                       echo _("This is the pool of available random news items for this page. Every time the page is loaded, a randomly selected one of these items is displayed.")."<br><br>";
                       $first_hidden = 0;
                    }
                    echo $base_url."display'>Make Fixed</a>]&nbsp;";        
                    echo $base_url."archive'>Archive Item</a>]&nbsp;";        
                }
                echo $base_url."edit'>Edit</a>]&nbsp;";
                echo $base_url."delete'>Delete</a>]&nbsp; -- ($date_posted)<br><br>";
                echo $row['message']."<br><br>";
            }
        }

        $result = mysql_query("
            SELECT * 
            FROM news 
            WHERE news_page_id = '$news_page' AND archive = 1 
            ORDER BY uid DESC;
        ");

        if (mysql_numrows($result) > 0) {

            echo "<font size=+2><b>"._("Archived News Items for ").$news_type.
                           _(" (Only visible on this page)")."</b></font><hr><br><br>";
            echo _("Items here are not visible anywhere, and can be safely stored here until they become current again.")."<br><br>";
            while($row = mysql_fetch_array($result)) {
                $date_posted = strftime(_("%A, %B %e, %Y"),$row['date_posted']);
                $visible = $row['display'];
                $base_url = "[<a href='sitenews.php?news_page=$news_page&uid=".$row['uid']."&action="; 
                echo $base_url."unarchive'>Unarchive Item</a>]&nbsp;";        
                echo $base_url."edit'>Edit</a>]&nbsp;";
                echo $base_url."delete'>Delete</a>]&nbsp; -- ($date_posted)<br><br>";
                echo $row['message']."<br><br>";
            }
        }
    }
}
else {
    echo "You are not authorized to use this form.";
}


function news_change_made ($news_page) {
    $date_changed = time();
    $result = mysql_query("
            UPDATE news_pages SET modifieddate = $date_changed WHERE news_page_id = '$news_page'
    ");
}

function move_news_item ($news_page_id, $uid_to_move, $direction) {

    $result = mysql_query("
        SELECT * FROM news 
        WHERE news_page_id = '$news_page_id' AND 
           display = 1  AND (archive IS NULL OR archive != 1) 
        ORDER BY ordering
    ");

    $i = 1 ;   
    while ($row = mysql_fetch_assoc($result)) {
        $curr_uid = $row['uid'];
        $update_query = mysql_query("
            UPDATE news SET ordering = $i WHERE uid = $curr_uid
        ");
        if (intval($curr_uid) == intval($uid_to_move)) {$old_pos = $i;}
        $i++;
    }

    if (isset($old_pos)) {
        if ($direction == 'up') {
            $result = mysql_query("
                UPDATE news SET ordering = $old_pos 
                WHERE news_page_id = '$news_page_id' AND display = 1 AND ordering = ($old_pos + 1)
            ");
            $result = mysql_query("
                UPDATE news SET ordering = $old_pos + 1 WHERE uid = $uid_to_move
            ");
        } else {
            $result = mysql_query("
                UPDATE news SET ordering = $old_pos 
                WHERE news_page_id = '$news_page_id' AND display = 1 AND ordering = ($old_pos - 1)
            ");
            $result = mysql_query("
                UPDATE news SET ordering = $old_pos - 1 WHERE uid = $uid_to_move
            ");
        }
    }
}




theme("", "footer");
ob_end_flush();
?>
