<?PHP

class projectInfo
{
  var $avail1_rows;
  var $avail1_pages;

  var $avail2_rows;
  var $avail2_pages;

  var $done1_rows;
  var $done1_pages;

  var $done2_rows;
  var $done2_pages;

  var $out1_rows;
  var $out1_pages;

  var $out2_rows;
  var $out2_pages;

  var $total_rows;
  var $total_pages;

  var $availablepages;

function update_avail($project, $state) {

    $this->avail1_rows = mysql_query("SELECT * FROM $project WHERE state = 2 ORDER BY image ASC");
    if ($this->avail1_rows != "") { $this->avail1_pages = (mysql_num_rows($this->avail1_rows)); } else $this->avail1_pages = 0;

    $this->avail2_rows = mysql_query("SELECT * FROM $project WHERE state = 12 ORDER BY image ASC");
    if ($this->avail2_rows != "") { $this->avail2_pages = (mysql_num_rows($this->avail2_rows)); } else $this->avail2_pages = 0;

    if ($state == 12) {
        $this->availablepages = $this->avail2_pages;
    } else if (($state <= 2) || ($state >= 0)) {
        $this->availablepages = $this->avail1_pages;
    } else $this->availablepages = 0;
}

function update_total($project, $state) {

    $this->total_rows = mysql_query("SELECT * FROM $project ORDER BY image ASC");
    if ($this->total_rows != "") { $this->total_pages = (mysql_num_rows($this->total_rows)); } else $this->total_pages = 0;
}

function update($project, $state) {

    $this->out1_rows = mysql_query("SELECT * FROM $project WHERE state = 5 ORDER BY image ASC");
    if ($this->out1_rows != "") { $this->out1_pages = (mysql_num_rows($this->out1_rows)); } else $this->out1_pages = 0;

    $this->done1_rows = mysql_query("SELECT * FROM $project WHERE state = 9 ORDER BY image ASC");
    if ($this->done1_rows != "") { $this->done1_pages = (mysql_num_rows($this->done1_rows)); } else $this->done1_pages = 0;

    $this->out2_rows = mysql_query("SELECT * FROM $project WHERE state = 15 ORDER BY image ASC");
    if ($this->out2_rows != "") { $this->out2_pages = (mysql_num_rows($this->out2_rows)); } else $this->out2_pages = 0;

    $this->done2_rows = mysql_query("SELECT * FROM $project WHERE state = 19 ORDER BY image ASC");
    if ($this->done2_rows != "") { $this->done2_pages = (mysql_num_rows($this->done2_rows)); } else $this->done2_pages = 0;

    $this->update_avail($project, $state);
    $this->update_total($project, $state);

}
}
?>
