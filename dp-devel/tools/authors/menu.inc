<?PHP
include_once($relPath.'user_is.inc');

function echo_menu() {
  global $menu_cache, $id; // $id is possibly

  // check cache
  if (isset($menu_cache)) {
    echo $menu_cache;
    return;
  }

  $menu_cache = '<p align="center">';

  // the current file
  $script = $_SERVER['SCRIPT_FILENAME'];
  $file = substr($script, strrpos($script, '/')+1);

  if ($file != 'listing.php')
    newMenuItem(_('List authors'), 'listing.php');
  if (user_is_authors_db_editor()) {
    if ($file != 'add.php') {
      newMenuItem(_('Add author'), 'add.php');
    }
    if ($file == 'author.php') {
      newMenuItem(_('Edit author'), "add.php?author_id=$id");
      newMenuItem(_('Add biography'), "addbio.php?author_id=$id");
    }
    if ($file == 'bio.php') {
      newMenuItem(_('Edit biography'), "addbio.php?bio_id=$id");
    }
  }
  if (user_is_authors_db_manager() && $file != 'manage.php') {
    newMenuItem(_('Manage authors'), 'manage.php');
  }

  $menu_cache .= '</p>';
  echo $menu_cache;
}

function newMenuItem($label, $url) {
  global $separator, $menu_cache;
  if (isset($separator))
    $menu_cache .= '|';
  else
    $separator = true;
  $menu_cache .= " <a href='$url'>$label</a> ";
}

?>
