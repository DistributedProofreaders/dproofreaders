<?PHP
include_once($relPath.'udb_user.php');
include_once($relPath.'user_is.inc');

function isMaintenanceMode() {
	global $maintenance, $pguser;

	if ($maintenance) {
		if (!user_is_a_sitemanager()) {
			echo "<html><head><title>Distributed Proofreaders Maintenance</title></head>";
			echo "<body><center><h2>$pguser We are currently performing maintenance on the server.  Please ";
			echo "bear with us, we should have the site back up and running shortly.</h2></center></body></html>";
			exit();
		}
	}
}

class dbConnect
{
var $error='';
function dbConnect()
     {$db_info=new db_udb_user();
     $this->db_lk=mysql_connect($db_info->server,$db_info->username,$db_info->password);
     if (!$this->db_lk)
          {$this->error='Unable to connect to database server.'; return 0;}

     if (!mysql_select_db($db_info->dbname,$this->db_lk))
          {$this->error='Unable to locate database.'; return 0;}
          else {return 1;}
     }

function close()
     {if (isset($this->db_lk)) {mysql_close($this->db_lk);}}
}
?>
