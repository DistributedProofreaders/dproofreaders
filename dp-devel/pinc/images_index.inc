<?
$relPath="./../../pinc/";
include($relPath.'v_site.inc');
include_once($relPath.'project_states.inc');
include_once($relPath.'project_trans.inc');
include_once($relPath.'maybe_mail.inc');

    function generate_images_index( $project )
    {
	global $projects_dir, $code_url;

        $projectpath = "$projects_dir/$project";
	$images_html = "$projectpath/images.html";

	$s =
	    "<HTML>\n" .
	    "<HEAD>  <TITLE>Image Index</TITLE>  </HEAD>\n" .
	    "<BODY>\n" .
	    "<H1 ALIGN=CENTER>Image Index</H1>\n" .
	    "<P>Here are the individual images for this project:</P>\n" .
	    "<HR>\n";

	foreach( glob("$projectpath/*.png") as $png_path )
	{
	    $size = filesize($png_path);
	    $png_filename = basename($png_path);
	    $s .= "<A HREF=\"$png_filename\"><B>$png_filename</B></A>";
	    $s .= " <I>($size bytes)</I><BR>\n";
	}
	$s .= "<HR>\n";
	$s .= "<P>Back to your <A HREF=\"$code_url/tools/proofers/proof_per.php\">personal page</A>.</P>\n";
	$s .= "</BODY>\n";
	$s .= "</HTML>\n";
	

	( $f = fopen( $images_html, 'w' ) ) and
	fwrite( $f, $s ) and
	fclose($f) or
	print( "\nProblems writing $images_html\n" );
    }
?>