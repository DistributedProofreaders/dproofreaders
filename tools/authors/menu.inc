<?php
include_once($relPath.'user_is.inc');

function echo_menu($id = null)
{
    // the current file
    $script = $_SERVER['SCRIPT_FILENAME'];
    $file = substr($script, strrpos($script, '/') + 1);

    $menu_entries = [];

    $menu_entries[] = newMenuItem(_('List authors'), 'listing.php', $file);
    if (user_is_authors_db_editor()) {
        $menu_entries[] = newMenuItem(_('Add author'), 'add.php', $file);
        if ($file == 'author.php') {
            $menu_entries[] = newMenuItem(_('Edit author'), "add.php?author_id=$id", $file);
            $menu_entries[] = newMenuItem(_('Add biography'), "addbio.php?author_id=$id", $file);
        }
        if ($file == 'bio.php') {
            $menu_entries[] = newMenuItem(_('Edit biography'), "addbio.php?bio_id=$id", $file);
        }
    }
    if (user_is_authors_db_manager()) {
        $menu_entries[] = newMenuItem(_('Manage authors'), 'manage.php', $file);
    }

    echo '<p>' . implode(" | ", $menu_entries) . '</p>';
}

function newMenuItem($label, $url, $current_file)
{
    if (startswith($url, $current_file)) {
        return "<b>" . html_safe($label) . "</b>";
    } else {
        return sprintf("<a href='%s'>%s</a>", attr_safe($url), html_safe($label));
    }
}
