<?PHP
include_once($relPath.'page_states.inc');
include_once($relPath.'f_project_states.inc');

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

  var $temp1_rows;
  var $temp1_pages;

  var $temp2_rows;
  var $temp2_pages;

  var $out1_rows;
  var $out1_pages;

  var $out2_rows;
  var $out2_pages;

  var $total_rows;
  var $total_pages;

  var $availablepages;

function update_avail($project, $state) {

    $this->avail1_rows = mysql_query("SELECT * FROM $project WHERE state = '".AVAIL_FIRST."' ORDER BY image ASC");
    if ($this->avail1_rows != "") { $this->avail1_pages = (mysql_num_rows($this->avail1_rows)); } else $this->avail1_pages = 0;

    $this->avail2_rows = mysql_query("SELECT * FROM $project WHERE state = '".AVAIL_SECOND."' ORDER BY image ASC");
    if ($this->avail2_rows != "") { $this->avail2_pages = (mysql_num_rows($this->avail2_rows)); } else $this->avail2_pages = 0;

    $project_round = projectStateRound($state);
    if ( $project_round == 'SECOND' ) {
        $this->availablepages = $this->avail2_pages;
    } else if ( $project_round == 'FIRST' || $project_round == 'NEW' ) {
        $this->availablepages = $this->avail1_pages;
    } else $this->availablepages = 0;
}

function update_total($project, $state) {

    $this->total_rows = mysql_query("SELECT * FROM $project ORDER BY image ASC");
    if ($this->total_rows != "") { $this->total_pages = (mysql_num_rows($this->total_rows)); } else $this->total_pages = 0;
}

function update($project, $state) {

    $this->out1_rows = mysql_query("SELECT * FROM $project WHERE state = '".OUT_FIRST."' ORDER BY image ASC");
    if ($this->out1_rows != "") { $this->out1_pages = (mysql_num_rows($this->out1_rows)); } else $this->out1_pages = 0;

    $this->temp1_rows = mysql_query("SELECT * FROM $project WHERE state = '".TEMP_FIRST."' ORDER BY image ASC");
    if ($this->temp1_rows != "") { $this->temp1_pages = (mysql_num_rows($this->temp1_rows)); } else $this->temp1_pages = 0;

    $this->done1_rows = mysql_query("SELECT * FROM $project WHERE state = '".SAVE_FIRST."' || state = '".BAD_FIRST."' || state = '".BAD_SECOND."' ORDER BY image ASC");
    if ($this->done1_rows != "") { $this->done1_pages = (mysql_num_rows($this->done1_rows)); } else $this->done1_pages = 0;

    $this->out2_rows = mysql_query("SELECT * FROM $project WHERE state = '".OUT_SECOND."' ORDER BY image ASC");
    if ($this->out2_rows != "") { $this->out2_pages = (mysql_num_rows($this->out2_rows)); } else $this->out2_pages = 0;

    $this->temp2_rows = mysql_query("SELECT * FROM $project WHERE state = '".TEMP_SECOND."' ORDER BY image ASC");
    if ($this->temp2_rows != "") { $this->temp2_pages = (mysql_num_rows($this->temp2_rows)); } else $this->temp2_pages = 0;

    $this->done2_rows = mysql_query("SELECT * FROM $project WHERE state = '".SAVE_SECOND."' || state ='".BAD_FIRST."' || state = '".BAD_SECOND."' ORDER BY image ASC");
    if ($this->done2_rows != "") { $this->done2_pages = (mysql_num_rows($this->done2_rows)); } else $this->done2_pages = 0;

    $this->update_avail($project, $state);
    $this->update_total($project, $state);

}
}
?>
