<?PHP
include($relPath.'udb_user.php');
class dbConnect
{
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