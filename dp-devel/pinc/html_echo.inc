<?PHP
function echoFont($txt,$size,$color,$face,$return)
{
  $tag="<font";
  $tag.=$size?" size=\"$size\"":'';
  $tag.=$color?" color=\"$color\"":'';
  $tag.=$face?" face=\"$face\"":'';
  $tag.=">$txt</font>";
  if ($return) {return $tag;} else {echo $tag;}
}

function echoB($txt,$return)
{
  $tag="<b>$txt</b>";
  if ($return) {return $tag;} else {echo $tag;}
}

function echoStrong($txt,$return)
{
  $tag="<strong>$txt</strong>";
  if ($return) {return $tag;} else {echo $tag;}
}

function echoI($txt,$return)
{
  $tag="<i>$txt</i>";
  if ($return) {return $tag;} else {echo $tag;}
}

function echoImg($src,$width,$height,$alt,$title,$border,$id,$extraTags,$return)
{
  $tag="<img \r\nsrc=\"$src\"";
  $tag.=$width?" width=\"$width\"":'';
  $tag.=$height?" height=\"$height\"":'';
  $tag.=$alt?" alt=\"$alt\"":'';
  $tag.=$title?" title=\"$title\"":'';
  $tag.=$border==0?" border=\"0\"":'';
  $tag.=$id?" id=\"$id\"":'';
  $tag.=$extraTags?" $extraTags":'';
  $tag.=">";
  if ($return) {return $tag;} else {echo $tag;}
}

function echoImage($src,$width,$height,$alt,$title,$border,$id,$extraTags,$return)
{
  $tag=echoImg($src,$width,$height,$alt,$title,$border,$id,$extraTags,$return);
  if ($return) {return $tag;}
}

function echoHref($txt,$href,$extraTags,$return)
{
  $tag="<a \r\nhref=\"$href\"";
  $tag.=$extraTags?" $extraTags":'';
  $tag=">$txt</a>";
  if ($return) {return $tag;} else {echo $tag;}
}

function echoOption($txt,$value,$selected,$return)
{
  $tag="<option \r\nvalue=\"$value\"";
  $tag.=$selected?" selected":'';
  $tag.=">$txt</option>";
  if ($return) {return $tag;} else {echo $tag;}
}

function echoSelect($txt_array,$value_array,$select_value,$id,$extraTags,$return)
{
  $txt_array=is_array($txt_array)?$txt_array:explode('|', $txt_array);
  $value_array=is_array($value_array)?$value_array:explode('|', $value_array);
  $tag="<select id=\"$id\" name=\"$id\"";
  $tag.=$extraTags?" $extraTags":'';
  $tag.=">";
  for ($i=0;$i<count($value_array);$i++)
    {
    $txt=$txt_array[$i];
    $value=$value_array[$i];
    if($value==$select_value)
      {$selected=1;}
    else
      {$selected=0;}
    $tag.=echoOption($txt,$value,$selected,1);
    }
  $tag.="</select>";
  if ($return) {return $tag;} else {echo $tag;}
}

function fillSelectFromSQL($result,$txt_field,$value_field,$select_value,$id,$extraTags,$return)
{
  // use $value_field='num_seq' to create a numeric sequence of option values
  $numRows=mysql_num_rows($result);
  $txt_array=array();
  $value_array=array();
  for($i=0;$i<$numRows;$i++)
    {
    $row=mysql_fetch_assoc($result);
    $txt_array[$i]=$row[$txt_field];
    if($value_field=='num_seq')
      {$value_array[$i]=$i;}
    else 
      {$value_array[$i]=$row[$value_field];}
    }
  echoSelect($txt_array,$value_array,$select_value,$id,$extraTags,$return);
}

function echoInputText($value,$size,$id,$extraTags,$return)
{
  $tag="<input \r\ntype=\"text\"";
  $tag.=$id?" id=\"$id\" name=\"$id\"":'';
  $tag.=$size?" size=\"$size\"":'';
  $tag.=" value=\"$value\"";
  $tag.=$extraTags?" $extraTags":'';
  $tag.=">";
  if ($return) {return $tag;} else {echo $tag;}
}

function echoInputSubmit($value,$title,$accesskey,$id,$extraTags,$return)
{
  $tag="<input \r\ntype=\"submit\"";
  $tag.=$id?" id=\"$id\" name=\"$id\"":'';
  $tag.=$title?" title=\"$title\"":'';
  $tag.=$accesskey?" accesskey=\"$accesskey\"":'';
  $tag.=" value=\"$value\"";
  $tag.=$extraTags?" $extraTags":'';
  $tag.=">";
  if ($return) {return $tag;} else {echo $tag;}
}

function echoInputImage($src,$size,$title,$accesskey,$id,$extraTags,$return)
{
  $tag="<input \r\ntype=\"image\" src=\"$src\"";
  $tag.=$id?" id=\"$id\" name=\"$id\"":'';
  $tag.=$size?" size=\"$size\"":'';
  $tag.=$title?" title=\"$title\" alt=\"$title\"":'';
  $tag.=$accesskey?" accesskey=\"$accesskey\"":'';
  $tag.=$extraTags?" $extraTags":'';
  $tag.=">";
  if ($return) {return $tag;} else {echo $tag;}
}
?>
