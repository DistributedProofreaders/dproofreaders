<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"><html>
<head>
<meta name="generator" content="HTML Tidy, see www.w3.org">
<script src="../quiz.js" type="text/javascript"></script>
<script type="text/javascript" language="javascript">
var corr = "a detective, why was he watching? There was\nindeed no reward offered whatsoever for his arrest.\nPerhaps he belonged to the wretched type of beings\nwho do pride themselves on their public spirit--men\nwho wrote letters to the newspapers and\ninterfered in other people's business. He might now\nwell have wanted to show his public spirit by handing\nhim over to the police. The newspaper in his\nhand! Of course. He had read his description there,\nand identified him.\n\nCharles now found himself conjecturing how the\nman would set about carrying out his task of pub-*";
function check()
{
  var i;
  var cc;
  var s;
  var p;
  var part;
  var feedb;
  feedb = "ok";
  s = document.forms[0].elements[0].value;
  sl = s.toLowerCase();
  feedb = common_check(s);
  if (feedb == "ok")
  {
     
     if (s.indexOf("pub-*") == -1)
        feedb = "eophyphen";
  };
  if (feedb == "ok")
  {
     
     if (s.indexOf("handing") == -1)
        feedb = "hyphen";
  };
  if (feedb == "ok")
  {
     if ((s.indexOf("-\n") != -1) || (s.indexOf("- \n") != -1) || (s.indexOf("-\r") != -1) || (s.indexOf("- \r") != -1))
        feedb = "eolhyphen";
  };
  if (feedb == "ok")
  {
     p = s.indexOf("him.");
     part = s.substring(p,s.indexOf("Charles now"));
     if ((part.indexOf("\n") == part.lastIndexOf("\n")) && (part.indexOf("\r") == part.lastIndexOf("\r")))
        feedb = "para";
  };
  if (feedb == "ok")
  {
     if ((sl.indexOf("<i>") != -1) || (sl.indexOf("</i>") != -1))
        feedb = "extraital";
  };
  if (feedb == "ok")
  {
     if (!diff(s.toLowerCase(),corr.toLowerCase()))
     {
        feedb = "other";
     };
  };
  top.right.location.href= "returnfeed.php?feedb=" + feedb;  
};
function restart()
{
  document.forms[0].elements[0].value="a detective, why was he watching? There was\nindeed no reward offered whatsoever for his arrest.\nPerhaps he belonged to the wretched type of beings\nwho do pride themselves on their public spirit--\nmen who wrote letters to the newspapers and\ninterfered in <i>other</i> people's business. He might now\nwell have wanted to show his public spirit by hand-\ning him over to the police. The newspaper in his\nhand! Of course. He had read his description there,\nand identified him.\nCharles now found himself conjecturing how the\nman would set about carrying out his task of pub-";
};
</script>
<title></title>
<META http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
</head>
<body bgcolor='#ffffff'>
<form action=""><textarea rows="12" cols="60" name="output" wrap="off">
a detective, why was he watching? There was
indeed no reward offered whatsoever for his arrest.
Perhaps he belonged to the wretched type of beings
who do pride themselves on their public spirit--
men who wrote letters to the newspapers and
interfered in <i>other</i> people's business. He might now
well have wanted to show his public spirit by hand-
ing him over to the police. The newspaper in his
hand! Of course. He had read his description there,
and identified him.
Charles now found himself conjecturing how the
man would set about carrying out his task of pub-
</textarea>
<p>
<input type="button" value="check" onclick="check();">
<input type="button" value="restart" onclick="restart();"></form>
</body>
</html>
