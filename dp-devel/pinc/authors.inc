<?PHP
  function get_biography($id) {
    $result = mysql_query("SELECT bio FROM biographies WHERE bio_id = $id;");
    if (!$result || mysql_num_rows($result) == 0) {
      // not found!
      return _("An error has occurred somewhere. The project manager of this project has " .
               "requested that a biography be automatically inserted here, but no biography is known " .
               "by the id provided:")
             . " <b>$id</b><br /><br />\n" .
             _("You may wish to contact the project manager so that the problem can be resolved.");
    }
    else {
      $bio_text = mysql_result($result, 0, 'bio');
      // wrap in commentaries and return
      return "<!-- begin bio retrieved from database: id=$id -->\n$bio_text\n<!-- end bio retrieved from database: id=$id -->\n";
    }
  }
?>
