<?PHP

function getCookieHash($userID)
{
  $hashID="akdgao7wnkezqo390akdlal";
  return md5($userID.$hashID);
}

function checkCookieHash($userID,$cookieHash)
{
  $hashID="akdgao7wnkezqo390akdlal";
//echo md5($userID.$hashID)."<P>$cookieHash";
  if (md5($userID.$hashID) == $cookieHash)
    {return true;}
  else
    {return false;}
}
?>