<?PHP
include($relPath.'html_styles.inc');

class htmlRender
{
var $headO=0;
var $bodyO=0;
var $centerO=0;
var $tableO=0;
var $trO=0;
var $tdO=0;

function htmlRender()
     {
     $styleC=new htmlStyles();
     $styleV=get_class_vars(get_class($styleC));
     foreach($styleV as $key => $val)
     {$this->$key = $val;}
     }

function startTable($tableType,$tableWidth,$extraTags,$return)
     {
     $tableS="";
     if (!$return)
       {
       if ($this->tdO) {$tableS.="</td>";$this->tdO=0;}
       if ($this->trO) {$tableS.="</tr>";$this->trO=0;}
       if ($this->tableO) {$tableS.="</table>";$this->tableO=0;}
       }
     // need table style to continue
     $tableS="<table";
     if (isset($this->table[$tableType]['ID'])) {$tableS.=" id=\"".$this->table[$tableType]['ID']."\"";}
     if (isset($this->table[$tableType]['BGCOLOR'])) {$tableS.=" bgcolor=\"".$this->table[$tableType]['BGCOLOR']."\"";}
     if (isset($this->table[$tableType]['BORDER'])) {$tableS.=" border=\"".$this->table[$tableType]['BORDER']."\"";}
     if (isset($this->table[$tableType]['BORDERCOLOR'])) {$tableS.=" bordercolor=\"".$this->table[$tableType]['BORDERCOLOR']."\"";}
     if (isset($this->table[$tableType]['CELLSPACING'])) {$tableS.=" cellspacing=\"".$this->table[$tableType]['CELLSPACING']."\"";}
     if (isset($this->table[$tableType]['CELLPADDING'])) {$tableS.=" cellpadding=\"".$this->table[$tableType]['CELLPADDING']."\"";}
     if (isset($this->table[$tableType]['STYLE'])) {$tableS.=" style=\"".$this->table[$tableType]['STYLE']."\"";}
     if (isset($this->table[$tableType]['CLASS'])) {$tableS.=" class=\"".$this->table[$tableType]['CLASS']."\"";}
     if ($tableWidth) {$tableS.=" width=\"".$tableWidth."\"";}
     if ($extraTags){$trS.=" ".$extraTags;}
     $tableS.=">";
     $this->tableO=1;
     if ($return) {return $tableS;} else {echo $tableS;}
     }

function startTR($trType,$extraTags,$return)
     {
     $trS="";
     if (!$return)
       {
     if ($this->tdO) {$trS.="</td>";$this->tdO=0;}
     if ($this->trO) {$trS.="</tr>";$this->trO=0;}
       }
     // need tr style to continue
     $trS="<tr";
     if (isset($this->tr[$trType]['ID'])) {$trS.=" id=\"".$this->tr[$trType]['ID']."\"";}
     if (isset($this->tr[$trType]['STYLE'])) {$trS.=" style=\"".$this->tr[$trType]['STYLE']."\"";}
     if (isset($this->tr[$trType]['CLASS'])) {$trS.=" class=\"".$this->tr[$trType]['CLASS']."\"";}
     if ($extraTags){$trS.=" ".$extraTags;}
     $trS.=">";
     $this->trO=1;
     if ($return) {return $trS;} else {echo $trS;}
     }

function startTD($tdType,$tdWidth,$tdCols,$tdRows,$tdAlign,$tdVAlign,$extraTags,$return)
     {
     $tdS="";
     if ($this->tdO && !$return) {$tdS.="</td>";$this->tdO=0;}
     // need td style to continue
     $tdS="<td";
     if (isset($this->td[$tdType]['ID'])) {$tdS.=" id=\"".$this->td[$tdType]['ID']."\"";}
     if (isset($this->td[$tdType]['BGCOLOR'])) {$tdS.=" \r\nbgcolor=\"".$this->td[$tdType]['BGCOLOR']."\"";}
     if (isset($this->td[$tdType]['STYLE'])) {$tdS.=" style=\"".$this->td[$tdType]['STYLE']."\"";}
     if (isset($this->td[$tdType]['CLASS'])) {$tdS.=" class=\"".$this->td[$tdType]['CLASS']."\"";}
     if ($tdWidth > 1) {$tdS.=" width=\"".$tdWidth."\"";}
     if ($tdCols > 1) {$tdS.=" colspan=\"".$tdCols."\"";}
     if ($tdRows > 1) {$tdS.=" rowspan=\"".$tdRows."\"";}
     if ($tdAlign) {$tdS.=" align=\"".$tdAlign."\"";}
     if ($tdVAlign) {$tdS.=" valign=\"".$tdVAlign."\"";}
     if ($extraTags){$tdS.=" ".$extraTags;}
     $tdS.=">";
     $this->tdO=1;
     if ($return) {return $tdS;} else {echo $tdS;}
     }

function closeTD($return)
     {
     if ($return) {return "</td>";} else {echo "</td>";}
     }

function closeTR($return)
     {
     if ($return) {return "</tr>";} else {echo "</tr>";}
     }

function closeTable($return)
     {
     if ($return) {return "</table>";} else {echo "</table>";}
     }

}
?>
