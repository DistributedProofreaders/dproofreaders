<?PHP
    include_once($relPath.'http_headers.inc');
    echo "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Frameset//EN\" \r\n".
      "\"http://www.w3.org/TR/html4/frameset.dtd\">\r\n";
    include($relPath.'slim_header.inc');
    slim_header($project,FALSE,FALSE);
    echo "<HTML><HEAD><TITLE>$project</TITLE>\r\n";
    echo "</HEAD>\r\n<FRAMESET \r\n";
    if ($userP['i_layout']=='1')
      {echo "COLS=\"*,{$userP['v_tframe']}%\">";}
    else
      {echo "ROWS=\"*,{$userP['h_tframe']}%\">";}
    echo "<FRAME \nNAME=\"imageframe\" SRC=\"image_frame.php\" marginwidth=\"2\" marginheight=\"2\">".
      "<FRAME \nNAME=\"textframe\" SRC=\"text_frame.php\"";
    if ($userP['i_layout']=='1' && $userP['v_tscroll']==0)
      {echo " SCROLLING=\"no\"";}
    if ($userP['i_layout']=='0' && $userP['h_tscroll']==0)
      {echo " SCROLLING=\"no\"";}
    echo " marginwidth=\"2\" marginheight=\"2\"></FRAMESET><NOFRAMES>Your \n".
      "browser is not frames capable!</NOFRAMES></HTML>";
?>
