<?
$relPath="./../../pinc/";
include_once($relPath.'v_site.inc');
include_once($relPath.'dp_main.inc');
include_once($relPath.'user_is.inc');
include_once($relPath.'theme.inc');
include_once($relPath.'projectinfo.inc');
//include_once($relPath.'project_edit.inc');
//$projectinfo = projectinfo();
$show_image_size = '';
$badmetadata = 0;


theme("Image Metadata Phase1", "header");


if (isset($_POST['done']))
{
//parse through post values and update database
foreach($HTTP_POST_VARS as $key => $val)
  {
    //echo "key is $key and value is $val<p>";
    if (strpos($key, 'pagenum') == 'TRUE'){
    $pagenum = str_replace("pagenum_", "", $key);
    $result = mysql_query("UPDATE $projectid SET orig_page_num = '$val' WHERE fileid = $pagenum");
    }else{
       $result = mysql_query("UPDATE $projectid SET metadata = '$val' WHERE fileid = '$key'");
       if ($val == 'badscan' || $val == 'missing' || $val == 'sequence'){
       $badmetadata = 1;
       }
    }
    if ($badmetadata == 1){
    $result = mysql_query("UPDATE projects SET state = 'project_md_bad' WHERE projectid = '$projectid'");
    metarefresh(0,'md_available.php',"Image Metadata Collection","");
    }else{
    $result = mysql_query("UPDATE projects SET state = 'project_md_second' WHERE projectid = '$projectid'");
    $result = mysql_query("UPDATE $projectid SET state = 'avail_md_second'");
    metarefresh(0,'md_available.php',"Image Metadata Collection","");
    }
}
}

if(isset($_POST['return']))
   {
   //they don't want to save so clean it up and return them to md_available
   metarefresh(0,'md_available.php',"Image Metadata Collection","");
   }


if(isset($_POST['continue']))
{
  foreach($HTTP_POST_VARS as $key => $val)
  {
    //echo "key is $key and value is $val<p>";
    if (strpos($key, 'pagenum') == 'TRUE'){
    $pagenum = str_replace("pagenum_", "", $key);
    $result = mysql_query("UPDATE $projectid SET orig_page_num = '$val' WHERE fileid = $pagenum");
    }else{
       $result = mysql_query("UPDATE $projectid SET metadata = '$val' WHERE fileid = '$key'");
       }
    }
}



$projectid = $_GET['projectid'];

$result = mysql_query("SELECT nameofwork, authorsname, language, username, state FROM projects WHERE projectid = '$projectid'");

$manager = mysql_result($result, 0, "username");
$state = mysql_result($result, 0, "state");
$name = mysql_result($result, 0, "nameofwork");
$author = mysql_result($result, 0, "authorsname");
$language = mysql_result($result, 0, "language");


//$projectinfo->update($projectid, $state);

 $res = mysql_query("SELECT count(fileid) AS totalpages FROM $projectid");
  $numpages = mysql_result($res,0,"totalpages");

echo "<center><table border=1>";

echo "<tr><td bgcolor='".$theme['color_headerbar_bg']."' colspan=6><b><font color='".$theme['color_headerbar_font']."' 
size=+1>Project Name: $name </b></td></tr>";

echo "<tr><td bgcolor='".$theme['color_navbar_bg']."'><b>Author:</b></td><td>$author</td><td 
bgcolor='".$theme['color_navbar_bg']."'><b>Total Number of Master Pages:</b></td><td>$numpages</td>";
echo "<td bgcolor='".$theme['color_navbar_bg']."'><b>Language:</b></td><td>$language</td></tr><tr></tr>";
echo "</table>";

//---------------------------------------------------------------------------------------------------


//echo "<h3>Per-Page Info</h3>\n";


echo "<form method ='post'><table border=1>\n";


	// Top header row
//	{
		echo "<tr>\n";
		echo "    <td align='center' colspan='1'><b>I</b></td>\n";
		echo "    <td align='center' colspan='1'><b>Image Name</b></td>\n";
		echo "    <td align='center' colspan='1'><b>Original Page #</b></td>\n";
		echo "    <td align='center' colspan='1'><b>Page Metadata</b></td>\n";
		echo "    <td align='center' colspan='1'><b>Thumbnail</b></td>\n";
		echo "</tr>\n";
//	}

// Image rows
	$path = "$projects_dir/$projectid/";

	$fields_to_get = 'fileid, image, state, metadata';

	$res = mysql_query( "SELECT fileid, image, state, metadata, orig_page_num FROM $projectid ORDER BY image ASC");
	$num_rows = mysql_num_rows($res);

	for ( $rownum=0; $rownum < $num_rows; $rownum++ )
	{

               $illustration ='';
               $blank ='';
               $missing ='';
               $badscan ='';
               $sequence ='';
               $nonblank ='';
               $orig_page_num ='';

		$page_res = mysql_fetch_array( $res, MYSQL_ASSOC );

		$fileid = $page_res['fileid'];
            $metadata = $page_res['metadata'];
            $orig_page_num = $page_res['orig_page_num'];

		if ($rownum % 2 ) {
			$row_color = $theme['color_mainbody_bg'];
		} else {
			$row_color = $theme['color_navbar_bg'];
		}
		echo "<tr bgcolor='$row_color'>";

// --------------------------------------------
		// Index
		$index = $rownum+1;
		echo "<td align='right'>$index</td>\n";


		// Image Name
		$imagename = $page_res['image'];
		if (file_exists($path.$imagename)) {
			$bgcolor = $row_color;
			if ($show_image_size) $imagesize = filesize(realpath($path.$imagename));
		} else {
			$bgcolor = "#FF0000";
			if ($show_image_size) $imagesize = 0;
		}
		echo "<td bgcolor='$bgcolor'><a href=../project_manager/displayimage.php?project=$projectid&imagefile=$imagename>$imagename</a></td>\n";

		// Original Page Number   
                echo "<td bgcolor='$bgcolor'><input type ='textbox' name='pagenum.$fileid' value = $orig_page_num></td>";
              

            // Set up existing page metadata if there is any, page defaults to nonblank
               $nonblank ='';
               if ($metadata == 'illustration')
               {
               $illustration = 'checked';
               }elseif ($metadata == 'blank')
               {
               $blank = 'checked';
               }elseif ($metadata == 'missing')
               {
               $missing = 'checked';
               }elseif ($metadata == 'badscan')
               {
               $badscan = 'checked';
               }elseif ($metadata == 'sequence')
               {
               $sequence = 'checked';
               }else{
               $nonblank = 'checked';
               }

            echo "<td bgcolor='$bgcolor' align='left'>
                 <input type='hidden' name=$fileid value='nonblank' $nonblank> 
                 <input type='radio' name=$fileid value='illustration' $illustration>Illustration<br>
                 <input type='radio' name=$fileid value='blank' $blank>Blank<br>
                 <input type='radio' name=$fileid value='missing' $missing>Page Missing After This One<br>
                 <input type='radio' name=$fileid value='badscan' $badscan>Bad Scan<br>
                 <input type='radio' name=$fileid value='sequence' $sequence>Page Out of Sequence<br>
                 <input type='radio' name=$fileid value='nonblank' $nonblank>Non-Blank<br>
                 </td>";

            // Show Thumbnail
            echo "<td bgcolor='$bgcolor' align='right'>
                  <a href=\"../project_manager/displayimage.php?project=$projectid&imagefile=$imagename\"><img src =\"$projects_url/$projectid/thumbs/$imagename\" alt = \"$imagename\" border =\"0\"></a>
                  </td>";

		echo "</tr>";
	}
	echo "</table>";

 echo "<INPUT TYPE=SUBMIT VALUE=\"Save and Continue Working\" NAME =\"continue\">
       <INPUT TYPE=SUBMIT VALUE=\"Save as Done\" NAME =\"done\">
       <INPUT TYPE=SUBMIT VALUE=\"Leave As Is and Quit\" NAME =\"return\">";

echo "</form></center>";
echo "<br>";

theme("","footer");


?>
