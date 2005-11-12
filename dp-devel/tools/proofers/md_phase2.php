<?
$relPath="./../../pinc/";
include($relPath.'v_site.inc');
include($relPath.'dp_main.inc');
include_once($relPath.'c_pages.inc');
include($relPath."doctype.inc");
include_once($relPath.'theme.inc');

// get cookie
//$tpage=new processpage();
//$npage=$tpage->getPageCookie();

$imagename ="001.png";


echo "<html><head><title>Image Frame</title></head><body bgcolor=#e0e8dd>";



//Start the outside table
echo "<table cols =\"2\" border = \"1\">";

//Display image
  if ($userP['i_layout']==1)
    {$iWidth=$userP['v_zoom'];}
  else {$iWidth=$userP['h_zoom'];}
    $iWidth=round((1000*$iWidth)/100);


echo "<td><img name=\"scanimage\" id=\"scanimage\" title=\"\" alt=\"\" src=\"$projects_url/$projectid/$imagename\" width = \"$iWidth\"></td>";


//start the metadata table
echo "<form action = \"some script\"><td valign = \"top\"><table cols =\"2\" border = \"1\">";
echo "<td colspan =\"2\" align = \"center\"><b>This Image Contains:</b></td><tr>
      <td>Front Matter</td><td><input type='checkbox' name='Metadata'></td><tr>
      <td>Back Matter</td><td><input type='checkbox' name='Metadata'></td></td><tr>
      <td>Verse</td><td><input type='checkbox' name='Metadata'></td><tr>
      <td>Poetry</td><td><input type='checkbox' name='Metadata'></td><tr>
      <td>Letter</td><td><input type='checkbox' name='Metadata'></td><tr>
      <td>Table of Contents</td><td><input type='checkbox' name='Metadata'></td><tr>
      <td>Footnote</td><td><input type='checkbox' name='Metadata'></td><tr>
      <td>Sidenote</td><td><input type='checkbox' name='Metadata'></td><tr>
      <td>Epigraph</td><td><input type='checkbox' name='Metadata'></td><tr>
      <td>Table</td><td><input type='checkbox' name='Metadata'></td><tr>
      <td>List</td><td><input type='checkbox' name='Metadata'></td><tr>
      <td>Math Notation</td><td><input type='checkbox' name='Metadata'></td><tr>
      <td>Illustration or Drawing</td><td><input type='checkbox' name='Metadata'></td><tr>
      <td></td><td></td><tr>
      <td></td><td></td><tr>
      <td></td><td></td><tr>
      <td colspan =\"2\" align = \"center\"><INPUT TYPE=SUBMIT VALUE=\"Save and Quit\"></td><tr>
      <td colspan =\"2\" align = \"center\"><INPUT TYPE=SUBMIT VALUE=\"Quit Without Saving\"></td><tr>
      <td colspan =\"2\" align = \"center\"><INPUT TYPE=SUBMIT VALUE=\"Save and Do Another\"></td><tr>";
echo "</form></table>";




echo "</table></body></html>";

?>