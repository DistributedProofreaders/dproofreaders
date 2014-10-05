<?php
include_once($relPath.'forum_interface.inc');

// This include holds information about DP people in charge of various things,
// that is used in FAQs. Also information about forums and wiki used in FAQs.

// People:
$PM_rights_giver_contact="<a href=\"mailto:dp-genmgr@pgdp.net\">lhamilton (Linda)</a>";
$site_funder="Charles";
$clearance_verifier_contact="<a href=\"mailto:juliet.sutherland@verizon.net\">JulietS</a>";

// Wiki:
$greek_wiki_url="$wiki_url/Transliterating_Greek";
$greek_wiki_url_french="$wiki_url/French/Translitt%C3%A9ration_du_grec";

$OCR_Pool_URL="$wiki_url/The_OCR_Pool";
$DPWiki_Guide_to_HTML_URL="$wiki_url/HTML_advice_threads";
$Online_sources_URL="$wiki_url/Sources_for_Scan_Harvesting";
$Common_browser_problems_URL="$wiki_url/Browser_Problems%3F";
$DPWiki_HTML_guide_URL=$DPWiki_Guide_to_HTML_URL;

// Forums; as appropriate topics and posts are created, respective 0s should be
// replaced with their IDs. Numbers after // are their IDs at pgdp.net forums.

// Topic IDs:
$FineReader_tips_id=0; // 5293;
$Guiprep_topic_id=0; // 2237;
$Guideline_discussion_id=0; // 37893 (v2.0)
$Using_project_details_id=0; // 10217
$Gallery_of_Table_Layouts_id=0; // 4311
$Stealth_Scannos_id=0; // 1563
$Proofing_Interface_Help_id=0; // 2739
$XHTML_suggestion_id=0; // 4405

// Following usually need not be changed:
$FineReader_tips_URL=make_forum_url("t",$FineReader_tips_id,5293);
$Guiprep_topic_URL=make_forum_url("t",$Guiprep_topic_id,2237);
$Guideline_discussion_URL=make_forum_url("t",$Guideline_discussion_id,37893);
$Using_project_details_URL=make_forum_url("t",$Using_project_details_id,10217);
$Gallery_of_Table_Layouts_URL=make_forum_url("t",$Gallery_of_Table_Layouts_id,4311);
$Stealth_Scannos_URL=make_forum_url("t",$Stealth_Scannos_id,1563);
$Proofing_Interface_Help_URL=make_forum_url("t",$Proofing_Interface_Help_id,2739);
$XHTML_suggestion_URL=make_forum_url("t",$XHTML_suggestion_id,4405);

function make_forum_url($type,$id,$default)
{
    if(!$id) {
        $id=$default;
    }

    switch($type) {
        case "f":
            return get_url_to_view_forum($id);
            break;
        case "t":
            return get_url_to_view_topic($id);
            break;
        case "p":
            return get_url_to_view_post($id);
            break;
    }
    
    return "";
}
?>
