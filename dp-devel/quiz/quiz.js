function common_check(s)
{
  var i;
  var cc;
  var feedb;
  feedb = "ok";
  for(i=0;i<s.length;i++)
  {
    if (s.charCodeAt(i) == 10)
    {
      if (cc>60)
        feedb = "../longline";
      cc=0;
    }
    else
    {
      if (s.charCodeAt(i) != 13)
        cc++;
    };
  };
  return feedb;  
};

function trim(s)
{
  var i;
  for(i=0;i<s.length;i++)
  {
    if ((s.charCodeAt(i) != 10) && (s.charCodeAt(i) != 13) && (s.charAt(i) != " "))
    {
       s = s.substring(i,s.length+1);
       break;
    };
    if (i==s.length-1)
       s = "";
  };
  for(i=(s.length-1);i>-1;i--)
  {
     if ((s.charCodeAt(i) != 10) && (s.charCodeAt(i) != 13) && (s.charAt(i) != " "))
     {
       s = s.substring(0,i+1);
       break;     	
     };     	
  };  
//  alert(s);
  return s;     
};

function diff(s1,s2)
{
  var i;
  var buf;
  ar1 = new Array();
  ar2 = new Array();
  var max;
  buf = "";
  s1 += "\n\r";
  s2 += "\n\r";
  for(i=0;i<s1.length;i++)
  {
    if (s1.charCodeAt(i) == 10) 
    {
        buf = trim(buf);
    	if ((buf != "") || (ar1.length > 0))
    	{
         ar1[ar1.length] = buf;
//    	   ar1.push(buf);
    	   buf = "";
    	};
    }
    else
    {
      if (s1.charCodeAt(i) != 13)
        buf = buf + s1.charAt(i);
    };
  };
  buf = "";
  for(i=0;i<s2.length;i++)
  {
    if (s2.charCodeAt(i) == 10) 
    {
        buf = trim(buf);
    	if ((buf != "") || (ar2.length > 0))
    	{
         ar2[ar2.length] = buf;
//    	   ar2.push(buf);
    	   buf = "";
    	};
    }
    else
    {
      if (s2.charCodeAt(i) != 13)
        buf = buf + s2.charAt(i);
    };
  };
//  alert(ar1.join("#"));
//  alert(ar2.join("#"));
  if (ar1.length > ar2.length)
  {
     for(i=ar2.length;i<ar1.length;i++)
     {
       if (ar1[i] != "")
          return false;
     };	
  };
  max = ar1.length;
  if (ar1.length > ar2.length)
  {
     for(i=ar2.length;i<ar1.length;i++)
     {
       if (ar1[i] != "")
          return false;
     };	
     max = ar2.length;
  };
  if (ar2.length > ar1.length)
  {
     for(i=ar1.length;i<ar2.length;i++)
     {
       if (ar2[i] != "")
          return false;
     };	
  };
  for(i=0;i<max;i++)
  {
     if (ar1[i] != ar2[i])
     {
//     	alert(i);
        return false;	
     };
  };
  return true;
};

function countchar(s,ch)
{
  var i;
  var count;
  count = 0;
  for(i=0;i<s.length;i++)
  {
    if (s.charCodeAt(i) == ch) 
      count++;
  };
  return count;
};


function ldexpect(s,s1,s2,exp,feedbl,feedbh)
{
  var count;
  var p1;
  var p2;
  p1 = s.indexOf(s1)
  p2 = s.indexOf(s2)
  if ((p1 != -1) && (p2 != -1))
  {
    count = countchar(sl.substring(p1,p2),10);
    if (count == exp) 
      return "ok";
    if (count < exp) 
      return feedbl;
    if (count > exp) 
      return feedbh;
  };   
  return "ok";
};   