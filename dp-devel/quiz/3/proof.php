<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"><html>
<head>
<meta name="generator" content="HTML Tidy, see www.w3.org">
<script src="../quiz.js" type="text/javascript"></script>
<script type="text/javascript" language="javascript">
var corr = "repentant and remorseful agony.\n\n\n\n\nCHAPTER VII.\n\nAt Oakwood\n\n\n\"Dearest mother, this is indeed\nlike some of\nOakwood's happy hours,\" exclaimed\nEmmeline, that same evening, as with\nchildish glee she had placed herself at her\nmother's feet, and raised her laughing eyes";
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
    feedb = ldexpect(sl,"agony.","chapter",5,"notfour","notfour");
  };
  if (feedb == "ok")
  {
    feedb = ldexpect(sl,"oakwood","dearest",3,"nottwo","nottwo");
  };
  if (feedb == "ok")
  {
    feedb = ldexpect(sl,"vii.","at oakwood",2,"numberinheader","numberinheader");
  };
  if (feedb == "ok")
  {
    if (sl.indexOf("\"dearest") == -1)
    {
          feedb = "missingquote";
    };
  };
  if (feedb == "ok")
  {
    if (s.indexOf("DEAREST") != -1)
    {
          feedb = "capital";
    };
  };
  if (feedb == "ok")
  {
    if (s.indexOf("dearest") != -1)
    {
          feedb = "lowercase";
    };
  };
  if (feedb == "ok")
  {
    if (s.indexOf(", \"") != -1)
    {
          feedb = "spquote";
    };
  };
  if (feedb == "ok")
  {
    if (s.indexOf(" ,") != -1)
    {
          feedb = "spcomma";
    };
   };
  if (feedb == "ok")
  {
    if (s.indexOf("arid") != -1)
    {
          feedb = "arid";
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
  document.forms[0].elements[0].value="repentant and remorseful agony.\n\nCHAPTER VII.\n\nAt Oakwood\n\nDEAREST mother, this is indeed\nlike some of\nOakwood's happy hours, \" exclaimed\nEmmeline , that same evening, as with\nchildish glee she had placed herself at her\nmother's feet, arid raised her laughing eyes";
};
</script>
<title></title>
<META http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
</head>
<body bgcolor='#ffffff'>
<form action=""><textarea rows="12" cols="60" name="output" wrap="off">
repentant and remorseful agony.
CHAPTER VII.
At Oakwood
DEAREST mother, this is indeed
like some of
Oakwood's happy hours, " exclaimed
Emmeline , that same evening, as with
childish glee she had placed herself at her
mother's feet, arid raised her laughing eyes
</textarea>
<p>
<input type="button" value="check" onclick="check();">
<input type="button" value="restart" onclick="restart();"></form>
</body>
</html>
