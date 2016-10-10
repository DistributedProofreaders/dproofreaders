<?php
include_once($relPath.'user_is.inc');

function echo_menu($id=NULL) {
    // the current file
    $script = $_SERVER['SCRIPT_FILENAME'];
    $file = substr($script, strrpos($script, '/')+1);

    $menu_entries = array();

    if ($file != 'listing.php')
        $menu_entries[] = newMenuItem(_('List authors'), 'listing.php');
    if (user_is_authors_db_editor()) {
        if ($file != 'add.php') {
            $menu_entries[] = newMenuItem(_('Add author'), 'add.php');
        }
        if ($file == 'author.php') {
            $menu_entries[] = newMenuItem(_('Edit author'), "add.php?author_id=$id");
            $menu_entries[] = newMenuItem(_('Add biography'), "addbio.php?author_id=$id");
        }
        if ($file == 'bio.php') {
            $menu_entries[] = newMenuItem(_('Edit biography'), "addbio.php?bio_id=$id");
        }
    }
    if (user_is_authors_db_manager() && $file != 'manage.php') {
        $menu_entries[] = newMenuItem(_('Manage authors'), 'manage.php');
    }

    echo '<p align="center">' . implode(" | ", $menu_entries) . '</p>';
}

function newMenuItem($label, $url, $separator='|') {
    return sprintf("<a href='%s'>%s</a>", attr_safe($url), html_safe($label));
}
