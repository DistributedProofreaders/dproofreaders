<?php
include_once($relPath.'v_resolution.inc');
include_once($relPath.'languages.inc');

$p_l= array(_("No Rounds"), _("First Round"), _("Second Round"), _("Both Rounds"));
$u_l= array(_("English"),_("French"),_("German"),_("Spanish"), _("Italian"), _("Portuguese"));
$i_r= $i_resolutions;
$f_f= array(_("Browser Default"),'Courier','Times','Arial','Lucida','Monospaced','DPCustomMono2');
$f_s= array(_("Browser Default"),'8pt','9pt','10pt','11pt','12pt','13pt','14pt','15pt','16pt','18pt','20pt');
$u_n= array('0', '2', '4', '6', '8', '10', '12', '14', '16', '18', '20');
$u_iloc=installed_langs(); array_unshift($u_iloc,""); next($u_iloc);
$u_il=array(_("Browser Default"));
while(list(,$v)=each($u_iloc))
	array_push($u_il,lang_name($v)." (".eng_name($v).")");
$i_pm= array(_("All Projects"), _("Active Projects"), _("Search Page"));
$i_stats = array(_("Public"), _("Anonymous"), _("Private"));
?>
