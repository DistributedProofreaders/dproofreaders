<?PHP
  include_once($relPath.'v_site.inc');
  include_once($relPath.'connect.inc');
  include_once($relPath.'dpsession.inc');
  include_once($relPath.'metarefresh.inc');
  include_once($relPath.'maintenance_mode.inc');

  //global db vars
  $db_Connection=new dbConnect();
  if ($db_Connection->error !='') {
    die('Error: Unable to connect to database.');
  }
  $db_link=$db_Connection->db_lk;

  //all $_GET to globals
  if (!empty($_GET)) {
    extract($_GET);
  }

  //all $_POST to globals
  if (!empty($_POST)) {
    extract($_POST);
  }

  // Require that the sender be logged in.
  $user_is_logged_in = dpsession_resume();
  if ( !$user_is_logged_in  )
  {
    // Remember the current REQUEST_URI, so that we can send the user there
    // after the login process.
    $url=$relPath."../accounts/signin.php?destination={$_SERVER['REQUEST_URI']}";
    $body="<A HREF=\"$url\">" . _("Please Sign In") . "</A>";
    $title = _("Please Sign In");
    metarefresh(0,$url,$title,$body);
    exit;
  }

  //Check to see if we are in a maintenance mode
  isMaintenanceMode();


  function ptog($varNM)
  {
    if (isset($_POST[$varNM])) {
      return $_POST[$varNM];
    }
    else {
      return NULL;
    }
  }

  function gtog($varNM) {
    if (isset($_GET[$varNM])) {
      return $_GET[$varNM];
    }
    else {
      return NULL;
    }
  }
?>
