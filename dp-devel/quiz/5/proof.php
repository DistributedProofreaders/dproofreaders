<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"><html>
<head>
<meta name="generator" content="HTML Tidy, see www.w3.org">
<script src="../quiz.js" type="text/javascript"></script>
<script type="text/javascript" language="javascript">
var corr = "We ask ourselves how Byron's poem\n\n/*\nYou have the Pyrrhic dance as yet,\n  Where is the Pyrrhic phalanx gone?\nOf two such lessons, why forget\n  The nobler and the manlier one?\n*/\n\nis related to these well known words:\n\n/#\nWhen in the Course of human events, it\nbecomes necessary for one people to dissolve the \npolitical bands which have connected ...\n#/\n\nNot at all we suspect.";
function check()
{
  var i;
  var count;
  var s;
  var p;
  var p2;
  var part;
  var feedb;
  feedb = "ok";
  s = document.forms[0].elements[0].value;
  sl = s.toLowerCase();
  feedb = common_check(s);
  if (feedb == "ok")
  {
    if ((s.indexOf("/*") == -1) || (s.indexOf("*/") == -1))
    {
          feedb = "nopoetry";
    };
  };
  if (feedb == "ok")
  {
    if ((s.indexOf("/#") == -1) || (s.indexOf("#/") == -1))
    {
          feedb = "nobc";
    };
  };
  if (feedb == "ok")
  {
    if ( (s.indexOf("words") > s.indexOf("/#")) || (s.indexOf("Not at") < s.indexOf("#/")))
    {
          feedb = "bqtoomuch";
    };
  };
  if (feedb == "ok")
  {
    feedb = ldexpect(s,"Byron's poem","/*",2,"pmspacing","pmspacing");
  };
  if (feedb == "ok")
  {
    feedb = ldexpect(s,"*/","is related",2,"pmspacing","pmspacing");
  };
  if (feedb == "ok")
  {
    feedb = ldexpect(s,"known words:","/#",2,"bqspacing","bqspacing");
  };
  if (feedb == "ok")
  {
    feedb = ldexpect(s,"#/","Not at",2,"bqspacing","bqspacing");
  };
  if (feedb == "ok")
  {
    feedb = ldexpect(s,"phalanx","gone?",0,"plinenotjoined","plinenotjoined");
  };
  if (feedb == "ok")
  {
    if ((s.indexOf(" Where is") == -1) || (s.indexOf(" The nobler") == -1))
    {
          feedb = "nopindent";
    };
  };
  if (feedb == "ok")
  {
    if ((s.indexOf("/*You") != -1) || (s.indexOf("one?*/") != -1) || (s.indexOf("/#When") != -1) || (s.indexOf("...#/") != -1))
    {
          feedb = "poetrymarkerown";
    };
  };
  if (feedb == "ok")
  {
    if ((s.indexOf(" You have") != -1) || (s.indexOf(" Of two") != -1))
    {
          feedb = "baseindent";
    };
  };
  if (feedb == "ok")
  {
    if ((s.indexOf("   Where is") != -1) || (s.indexOf("   The nobler") != -1) || (s.indexOf("  Where is") == -1) || (s.indexOf("  The nobler") == -1))
    {
          feedb = "otherpindent";
    };
  };
  if (feedb == "ok")
  {
    if (s.indexOf("hands") != -1)
    {
          feedb = "hands";
    };
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
  document.forms[0].elements[0].value="We ask ourselves how Byron's poem\n\nYou have the Pyrrhic dance as yet,\nWhere is the Pyrrhic phalanx\n                 gone?\nOf two such lessons, why forget\nThe nobler and the manlier one?\n\nis related to these well known words:\n\nWhen in the Course of human events, it\nbecomes necessary for one people to dissolve the\n politicalhands which have connected ...\n\nNot at all we suspect.";
};
</script>
<title></title>
<META http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
</head>
<body bgcolor='#ffffff'>
<form action=""><textarea rows="12" cols="60" name="output" wrap="off">
We ask ourselves how Byron's poem
You have the Pyrrhic dance as yet,
Where is the Pyrrhic phalanx
                 gone?
Of two such lessons, why forget
The nobler and the manlier one?
is related to these well known words:
When in the Course of human events, it
becomes necessary for one people to dissolve the
political hands which have connected ...
Not at all we suspect.</textarea>
<p>
<input type="button" value="check" onclick="check();">
<input type="button" value="restart" onclick="restart();"></form>
</body>
</html>
