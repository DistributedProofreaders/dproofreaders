<?
// This include holds information about DP people in charge of various things,
// that is used in FAQs. Also information about forums used in FAQs.

// People:
$PM_rights_giver="JulietS";
$PM_rights_giver_contact="<a href=\"mailto:juliet.sutherland@verizon.net\">JulietS</a>";
$proj_comments_supplier_1="Robert";
$proj_comments_supplier_2="Charlz";
$PG_poster="Charlz";
$site_funder="Charles";
$clearance_verifier="JulietS";
$clearance_verifier_contact="<a href=\"mailto:juliet.sutherland@verizon.net\">JulietS</a>";

// Forums; as appropriate topics and posts are created, respective 0s should be
// replaced with their IDs. Numbers after // are their IDs at pgdp.net forums.

// Topic IDs:
$FineReader_tips_id=0; // 5293;
$OCR_Pool_id=0; // 4957;
$Guiprep_topic_id=0; // 2237;
$DPWiki_Guide_to_HTML_id=0; // 5489
$Guideline_discussion_id=0; // 10779
$Using_project_details_id=0; // 10217
$Gallery_of_Table_Layouts_id=0; // 4311
$Stealth_Scannos_id=0; // 1563
$Proofing_Interface_Help_id=0; // 2739

// Message IDs:
$Online_sources_id=0; // 18049;
$Common_browser_problems_id=0; // 17984

// Following usually need not be changed:
$FineReader_tips_URL=make_forum_url("t",$FineReader_tips_and_tricks_id,5293);
$OCR_Pool_URL=make_forum_url("t",$OCR_Pool_id,4957);
$Guiprep_topic_URL=make_forum_url("t",$Guiprep_topic_id,2237);
$DPWiki_Guide_to_HTML_URL=make_forum_url("t",$DPWiki_Guide_to_HTML_id,5489);
$Guideline_discussion_URL=make_forum_url("t",$Guideline_discussion_id,10779);
$Using_project_details_URL=make_forum_url("t",$Using_project_details_id,10217);
$Gallery_of_Table_Layouts_URL=make_forum_url("t",$Gallery_of_Table_Layouts_id,4311);
$Stealth_Scannos_URL=make_forum_url("t",$Stealth_Scannos_URL,1563);
$Proofing_Interface_Help_URL=make_forum_url("t",$Proofing_Interface_Help_id,2739);
$Online_sources_URL=make_forum_url("p",$Online_sources_id,18049);
$Common_browser_problems_URL=make_forum_url("p",$Common_browser_problems_id,17984);
$XHTML_suggestion_URL="http://www.pgdp.net/phpBB2/viewtopic.php?t=4244&amp;view=next";
$DPWiki_HTML_guide_URL="http://www.pgdp.net/phpBB2/viewtopic.php?t=1563&amp;view=next";

function make_forum_url($type,$id,$default)
{
	global $forums_url;

	if($id) {
		$FURL=$forums_url;
	} else {
		$FURL="http://www.pgdp.net/phpBB2";
		$id=$default;
	}

	switch($type) {
		case "f":
			return "$FURL/viewforum.php?f=$id";
			break;
		case "t":
			return "$FURL/viewtopic.php?t=$id";
			break;
		case "p":
			return "$FURL/viewtopic.php?p=$id#$id";
			break;
	}
	
	return "";
}
?>
