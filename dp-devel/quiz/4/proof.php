<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"><html>
<head>
<meta name="generator" content="HTML Tidy, see www.w3.org">
<script src="../quiz.js" type="text/javascript"></script>
<script type="text/javascript" language="javascript">
var corr = "printing would be good for nothing but\nwaste paper, might not\nbe realised.[A] The work\nappeared about the end\nof December 1818 with\n1819 on the title-page.\nSchopenhauer had\nmeanwhile proceeded in\nSeptember to Italy, where he revised the\nfinal proofs.\n\n[Illustration: High art.]\n\nSo far as the reception of the work was\n\n[Footnote A: Wallace, p. 108.]";
var cor2 = "printing would be good for nothing but\nwaste paper, might not\nbe realised.[A] The work\nappeared about the end\nof December 1818 with\n1819 on the title-page.\nSchopenhauer had\nmeanwhile proceeded in\nSeptember to Italy, where he revised the\nfinal proofs.\n\n[Illustration: High art.]\n\nSo far as the reception of the work was\n\n[Footnote A:\nWallace, p. 108.]";
var cor3 = "printing would be good for nothing but\nwaste paper, might not\nbe realised.[A] The work\nappeared about the end\nof December 1818 with\n1819 on the title-page.\nSchopenhauer had\nmeanwhile proceeded in\nSeptember to Italy, where he revised the\nfinal proofs.\n\n[Illustration:\nHigh art.]\n\nSo far as the reception of the work was\n\n[Footnote A: Wallace, p. 108.]";
var cor4 = "printing would be good for nothing but\nwaste paper, might not\nbe realised.[A] The work\nappeared about the end\nof December 1818 with\n1819 on the title-page.\nSchopenhauer had\nmeanwhile proceeded in\nSeptember to Italy, where he revised the\nfinal proofs.\n\n[Illustration:\nHigh art.]\n\nSo far as the reception of the work was\n\n[Footnote A:\nWallace, p. 108.]";
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
    if ((s.indexOf("(A)") != -1) || (s.indexOf("{A}") != -1) || (s.indexOf("(1)") != -1) || (s.indexOf("{1}") != -1) || (s.indexOf("(Footnote") != -1) || (s.indexOf("{Footnote") != -1) || (s.indexOf("(Illustration") != -1) || (s.indexOf("{Illustration") != -1))
    {
          feedb = "sqbr";
    };
  };
  if (feedb == "ok")
  {
    if (sl.indexOf("[illustration") == -1)
    {
          feedb = "noillu";
    };
  };
  if (feedb == "ok")
  {
    if ((sl.indexOf("[illustration") < sl.indexOf("final proofs")) || (sl.indexOf("[illustration") > sl.indexOf("so far as")))
    {
          feedb = "illupos";
    };
  };
  if (feedb == "ok")
  {
    feedb = ldexpect(sl,"final proofs.","[illustration",2,"illunospace","illumuchspace");
  };
  if (feedb == "ok")
  {
    feedb = ldexpect(sl,"high art.]","so far as",2,"illunospace","illumuchspace");
  };
  if (feedb == "ok")
  {
    if (sl.indexOf("high art") == -1)
    {
          feedb = "nocaption";
    };
  };
  if (feedb == "ok")
  {
    if ((s.indexOf("[Illustration: High art.]") == -1) && (s.indexOf("[Illustration:\nHigh art.]") == -1) && (s.indexOf("[Illustration: \nHigh art.]") == -1))
    {
          feedb = "illuother";
    };
  };
  if (feedb == "ok")
  {
    if ((s.indexOf("The") < s.indexOf("[")) || (s.indexOf("The") < s.indexOf("]")))
    {
          feedb = "fnmarkermissing";
    };
  };
  if (feedb == "ok")
  {
    if (s.indexOf("].") != -1)
    {
          feedb = "fnmarkerbefore";
    };
  };
  if (feedb == "ok")
  {
    if (s.indexOf("realised. [") != -1)
    {
          feedb = "spacedfnmarker";
    };
  };
  if (feedb == "ok")
  {
    if (s.indexOf("[1]") != -1)
    {
      feedb = "fnmarkerone";
    };
  };
  if (feedb == "ok")
  {
    if (s.indexOf("[A]") == -1)
    {
      feedb = "fnmarkerwrong";
    };
  };
  if (feedb == "ok")
  {
    if (s.indexOf("realised.[A] The") == -1)
    {
      feedb = "fnmarkerother";
    };
  };
  if (feedb == "ok")
  {
    if (s.indexOf("[Footnote") == -1)
    {
      feedb = "nofn";
    };
  };
  if (feedb == "ok")
  {
    if (s.indexOf("[Footnote") < sl.indexOf("work was")) 
    {
          feedb = "fnpos";
    };
  };
  if (feedb == "ok")
  {
    if (s.indexOf("[Footnote:") != -1)
    {
      feedb = "fnwomarker";
    };
  };
  if (feedb == "ok")
  {
    if ((s.indexOf("[Footnote A ") != -1) || (s.indexOf("[Footnote AWallace") != -1))
    {
      feedb = "fncolonmissing";
    };
  };
  if (feedb == "ok")
  {
    if (s.indexOf("[Footnote A:") == -1)
    {
      feedb = "fnfalsemarker";
    };
  };
  if (feedb == "ok")
  {
    if (s.indexOf("lie") != -1)
    {
          feedb = "lie";
    };
  };
  if (feedb == "ok")
  {
     if (!(diff(s.toLowerCase(),corr.toLowerCase()) || diff(s.toLowerCase(),cor2.toLowerCase()) || diff(s.toLowerCase(),cor3.toLowerCase()) || diff(s.toLowerCase(),cor4.toLowerCase())))
     {
        feedb = "other";
     };
  };
  top.right.location.href= "returnfeed.php?feedb=" + feedb;  
};
function restart()
{
  document.forms[0].elements[0].value="printing would be good for nothing but\nwaste paper, might not\nbe realised. The work\nappeared about the end\nof December 1818 with\n1819 on the title-page.\nSchopenhauer had\nmeanwhile proceeded in\nSeptember to Italy, where lie revised the\nfinal proofs. \n\nSo far as the reception of the work was\n\nWallace, p. 108.";
};
</script>
<title></title>
<META http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
</head>
<body bgcolor='#ffffff'>
<form action=""><textarea rows="12" cols="60" name="output" wrap="off">
printing would be good for nothing but
waste paper, might not
be realised. The work
appeared about the end
of December 1818 with
1819 on the title-page.
Schopenhauer had
meanwhile proceeded in
September to Italy, where lie revised the
final proofs. 
So far as the reception of the work was
Wallace, p. 108.
</textarea>
<p>
<input type="button" value="check" onclick="check();">
<input type="button" value="restart" onclick="restart();"></form>
</body>
</html>
