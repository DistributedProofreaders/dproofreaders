<? $relPath='../../../pinc/';
include_once($relPath.'v_site.inc');
include_once($relPath.'theme.inc');
include_once($relPath.'prefs_options.inc');
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta name="generator" content="HTML Tidy, see www.w3.org">
<script src="../quiz.js" type="text/javascript"></script>
<script type="text/javascript">
var corr="Good-natured and unsuspicious,\nperhaps also not sufficiently <i>vigilant</i>,\nHarvey was long in discovering how\nhe was pillaged. <b>Cartwright</b>, the\nname of the person who was preying\non his employer, was not a young\nman. He was between forty and fifty\nyears of age, and had been in various\nsituations, where he had always given";
function check()
{
  var i;
  var cc;
  var s;
  var sl;
  var p;
  var feedb;
  feedb = "ok";
  s = document.forms[0].elements[0].value;
  sl = s.toLowerCase();
  feedb = common_check(s);
  if (feedb == "ok")
  {
     if (s.indexOf(" tbe ") != -1)
        feedb = "tbe";
  };
  if (feedb == "ok")
  {
     if ((sl.indexOf("<i>") == -1) && (sl.indexOf("</i>") == -1))
        feedb = "noital";
  };
  if (feedb == "ok")
  {
     if ((sl.indexOf("<b>") == -1) && (sl.indexOf("</b>") == -1) )
        feedb = "nobold";
  };
  if (feedb == "ok")
  {
     if (((sl.indexOf("<i>") != -1) && (sl.indexOf("</i>") == -1)) || ((sl.indexOf("<i>") == -1) && (sl.indexOf("</i>") != -1)))
        feedb = "italcorrupt";
  };
  if (feedb == "ok")
  {
     if (((sl.indexOf("<b>") != -1) && (sl.indexOf("</b>") == -1)) || ((sl.indexOf("<b>") == -1) && (sl.indexOf("</b>") != -1)))
        feedb = "boldcorrupt";
  };
  if (feedb == "ok")
  {
     if ((sl.indexOf("<b> ") != -1) || (sl.indexOf(" </b>") != -1) || (sl.indexOf("<i> ") != -1) || (sl.indexOf(" </i>") != -1))
        feedb = "spacedmarkup";
  };
  if (feedb == "ok")
  {
     if (sl.indexOf("<b>") != sl.lastIndexOf("<b>"))
        feedb = "multiplebold";
  };
  if (feedb == "ok")
  {
     if (sl.indexOf("<i>") != sl.lastIndexOf("<i>"))
        feedb = "multipleital";
  };
  if (feedb == "ok")
  {
     if (sl.indexOf(",</i>") != -1)
        feedb = "commainital";
  };
  if (feedb == "ok")
  {
     if (sl.indexOf(",</b>") != -1)
        feedb = "commainbold";
  };
  if (feedb == "ok")
  {
     if (sl.indexOf("<i>vigilant</i>") == -1)
        feedb = "italwrong";
  };
  if (feedb == "ok")
  {
     if (sl.indexOf("<b>cartwright</b>") == -1)
        feedb = "boldwrong";
  };
  if (feedb == "ok")
  {
     if (!diff(sl,corr.toLowerCase()))
     {
        feedb = "other";
     };
  };
  top.right.location.href= "returnfeed.php?feedb=" + feedb;  
};
function restart()
{
  document.forms[0].elements[0].value="Good-natured and unsuspicious,\nperhaps also not sufficiently vigilant,\nHarvey was long in discovering how\nhe was pillaged. Cartwright, the\nname of tbe person who was preying\non his employer, was not a young\nman. He was between forty and fifty\nyears of age, and had been in various\nsituations, where he had always given";
};
</script>
<title></title>
<META http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
</head>
<body bgcolor='#ffffff'>
<form action=""><textarea rows="12" cols="60" name="output" wrap="off">
Good-natured and unsuspicious,
perhaps also not sufficiently vigilant,
Harvey was long in discovering how
he was pillaged. Cartwright, the
name of tbe person who was preying
on his employer, was not a young
man. He was between forty and fifty
years of age, and had been in various
situations, where he had always given
</textarea> <p>
<input type="button" value="check" onclick="check();">
<input type="button" value="restart" onclick="restart();"></form>
</body>
</html>
