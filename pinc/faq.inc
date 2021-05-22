<?php
include_once($relPath.'forum_interface.inc');
include_once($relPath.'misc.inc');
include_once($relPath.'metarefresh.inc');

// This include holds information about DP people in charge of various things,
// that is used in FAQs. Also information about forums and wiki used in FAQs.

// SITE-SPECIFIC
$external_faq_overrides = [
    "proofreading_guidelines.php" => [
        "en" => "https://www.pgdp.net/wiki/DP_Official_Documentation:Proofreading/Proofreading_Guidelines",
        "de" => "https://www.pgdp.net/wiki/DP_Official_Documentation:Proofreading/German/Korrekturlese-Richtlinien",
        "es" => "https://www.pgdp.net/wiki/DP_Official_Documentation:Proofreading/Spanish/Reglas_de_Revisi%C3%B3n",
        "it" => "https://www.pgdp.net/wiki/DP_Official_Documentation:Proofreading/Italian/Regole_di_Correzione",
        "fr" => "https://www.pgdp.net/wiki/DP_Official_Documentation:Proofreading/French/Directives_de_Relecture_et_de_Correction",
        "nl" => "https://www.pgdp.net/wiki/DP_Official_Documentation:Proofreading/Dutch/Proeflees-Richtlijnen",
        "pt" => "https://www.pgdp.net/wiki/DP_Official_Documentation:Proofreading/Portuguese/Regras_de_Revis%C3%A3o",
    ],
    "formatting_guidelines.php" => [
        "en" => "https://www.pgdp.net/wiki/DP_Official_Documentation:Formatting/Formatting_Guidelines",
        "de" => "https://www.pgdp.net/wiki/DP_Official_Documentation:Formatting/German/Formatierungsrichtlinien",
        "it" => "https://www.pgdp.net/wiki/DP_Official_Documentation:Formatting/Italian/Regole_di_Formattazione",
        "fr" => "https://www.pgdp.net/wiki/DP_Official_Documentation:Formatting/French/Directives_de_Formatage",
        "nl" => "https://www.pgdp.net/wiki/DP_Official_Documentation:Formatting/Dutch/Formatteer-Richtlijnen",
        "pt" => "https://www.pgdp.net/wiki/DP_Official_Documentation:Formatting/Portuguese/Regras_de_Formata%C3%A7%C3%A3o",
    ],
    "DPflow.php" => [
        "en" => "https://www.pgdp.net/wiki/DP_Official_Documentation:General/General_Workflow_Diagram",
    ],
    "ProoferFAQ.php" => [
        "en" => "https://www.pgdp.org/wiki/DP_Official_Documentation:General/New_Volunteer_Frequently_Asked_Questions",
    ],
    "cp.php" => [
        "en" => "https://www.pgdp.net/wiki/DP_Official_Documentation:CP_and_PM/Content_Providing_FAQ",
        "fr" => "https://www.pgdp.net/wiki/DP_Official_Documentation:CP_and_PM/French/FAQ_fourniture_de_contenu",
        "pt" => "https://www.pgdp.net/wiki/DP_Official_Documentation:CP_and_PM/Portuguese/Fornecimento_de_Conte%C3%BAdos",
    ],
    "dochist.php" => [
        "en" => "https://www.pgdp.net/wiki/DP_Official_Documentation:Proofreading/Proofreading_and_Formatting_Guidelines_Revision_History",
    ],
    "faq_central.php" => [
        "en" => "https://www.pgdp.net/wiki/DP_Official_Documentation:General/FAQ_Central",
        "fr" => "https://www.pgdp.net/wiki/DP_Official_Documentation:General/French/Centre_des_FAQ",
    ],
    "longabsent.php" => [
        "en" => "https://www.pgdp.net/wiki/DP_Official_Documentation:General/Welcome_Back,_Alumni",
    ],
    "pm-faq.php" => [
        "en" => "https://www.pgdp.net/wiki/DP_Official_Documentation:CP_and_PM/Project_Managing_FAQ",
        "fr" => "https://www.pgdp.net/wiki/DP_Official_Documentation:CP_and_PM/French/FAQ_gestion_de_projet",
        "pt" => "https://www.pgdp.net/wiki/DP_Official_Documentation:CP_and_PM/Portuguese/Gest%C3%A3o_de_Projectos",
    ],
    "post_proof.php" => [
        "en" => "https://www.pgdp.net/wiki/DP_Official_Documentation:PP_and_PPV/Post-Processing_FAQ",
        "fr" => "https://www.pgdp.net/wiki/DP_Official_Documentation:PP_and_PPV/French/FAQ_post-processing",
    ],
    "ppv.php" => [
        "en" => "https://www.pgdp.net/wiki/DP_Official_Documentation:PP_and_PPV/Post-Processing_Verification_Guidelines",
    ],
    "scanning.php" => [
        "en" => "https://www.pgdp.net/wiki/DP_Official_Documentation:CP_and_PM/Scanning_FAQ",
    ],
    "wordcheck-faq.php" => [
        "en" => "https://www.pgdp.net/wiki/DP_Official_Documentation:Proofreading/WordCheck_FAQ",
    ],
    // not actually files in the source code, but references used in theme.inc
    "official-docs" => [
        "en" => "https://www.pgdp.net/wiki/DP_Official_Documentation:Main",
    ],
    "request-support" => [
        "en" => "https://www.pgdp.net/wiki/DP_Official_Documentation:Main/Request_Support",
    ],
    "greek" => [
        "en" => "https://www.pgdp.net/wiki/Greek",
        "fr" => "https://www.pgdp.net/wiki/DP_Official_Documentation:Proofreading/French/Translitt%C3%A9ration_du_grec",
    ],
];


// People:
$PM_rights_giver_contact = "<a href=\"mailto:dp-genmgr@pgdp.net\">lhamilton (Linda)</a>";
$site_funder = "Charles";
$clearance_verifier_contact = "<a href=\"mailto:juliet.sutherland@verizon.net\">JulietS</a>";

$OCR_Pool_URL = "$wiki_url/The_OCR_Pool";
$DPWiki_Guide_to_HTML_URL = "$wiki_url/HTML_advice_threads";
$Online_sources_URL = "$wiki_url/Sources_for_Scan_Harvesting";
$Common_browser_problems_URL = "$wiki_url/Browser_Problems%3F";
$DPWiki_HTML_guide_URL = $DPWiki_Guide_to_HTML_URL;

// Forums; as appropriate topics and posts are created, respective 0s should be
// replaced with their IDs. Numbers after // are their IDs at pgdp.net forums.

// Topic IDs:
$FineReader_tips_id = 0; // 5293;
$Guiprep_topic_id = 0; // 2237;
$Guideline_discussion_id = 0; // 37893 (v2.0)
$Using_project_details_id = 0; // 10217
$Gallery_of_Table_Layouts_id = 0; // 4311
$Stealth_Scannos_id = 0; // 1563
$Proofing_Interface_Help_id = 0; // 2739
$XHTML_suggestion_id = 0; // 4405

// Following usually need not be changed:
$FineReader_tips_URL = make_forum_url("t", $FineReader_tips_id, 5293);
$Guiprep_topic_URL = make_forum_url("t", $Guiprep_topic_id, 2237);
$Guideline_discussion_URL = make_forum_url("t", $Guideline_discussion_id, 37893);
$Using_project_details_URL = make_forum_url("t", $Using_project_details_id, 10217);
$Gallery_of_Table_Layouts_URL = make_forum_url("t", $Gallery_of_Table_Layouts_id, 4311);
$Stealth_Scannos_URL = make_forum_url("t", $Stealth_Scannos_id, 1563);
$Proofing_Interface_Help_URL = make_forum_url("t", $Proofing_Interface_Help_id, 2739);
$XHTML_suggestion_URL = make_forum_url("t", $XHTML_suggestion_id, 4405);

function make_forum_url($type, $id, $default)
{
    // If $id is non-zero, return a URL to a local forum/topic/post
    if ($id) {
        switch ($type) {
           case "f":
                return get_url_to_view_forum($id);
            case "t":
                return get_url_to_view_topic($id);
            case "p":
                return get_url_to_view_post($id);
        }
    }
    // otherwise, route them to a forum at pgdp.net
    else {
        $FURL = "https://www.pgdp.net/phpBB3";
        $id = $default;
        switch ($type) {
            case "f":
                return "$FURL/viewforum.php?f=$id";
            case "t":
                return "$FURL/viewtopic.php?t=$id";
            case "p":
                return "$FURL/viewtopic.php?p=$id#$id";
        }
    }

    return "";
}

function get_faq_url($faq, $langcode = null)
{
    global $code_dir, $code_url;

    // if no langcode was passed in, try to get the user's language
    if (!$langcode) {
        $langcode = substr(get_desired_language(), 0, 2);
    }

    // first try $langcode, then English
    foreach ([$langcode, "en"] as $langcode) {
        // Any overrides?
        $external_faq = get_external_faq($faq, $langcode);
        if ($external_faq) {
            return $external_faq;
        }

        // If not, return a file in the codebase
        if (is_file("$code_dir/faq/$langcode/$faq")) {
            return "$code_url/faq/$langcode/$faq";
        }
    }

    // fall back to the English version in the code if it exists
    if (is_file("$code_dir/faq/$faq")) {
        return "$code_url/faq/$faq";
    }

    return "";
}

function get_all_urls_for_faq($faq)
{
    global $code_dir, $code_url;
    global $external_faq_overrides;

    $urls = [];

    if (is_file("$code_dir/faq/$faq")) {
        $urls["en"] = "$code_url/faq/$faq";
    }

    $subdirs = scandir("$code_dir/faq");
    foreach ($subdirs as $dir) {
        if ($dir == "." or $dir == ".." or is_file("$code_dir/faq/$dir")) {
            continue;
        }

        $files = scandir("$code_dir/faq/$dir");
        foreach ($files as $file) {
            if (endswith($file, $faq)) {
                $urls[$dir] = "$code_url/faq/$dir/$faq";
            }
        }
    }

    // Override with any external links
    if (isset($external_faq_overrides[$faq])) {
        foreach ($external_faq_overrides[$faq] as $langcode => $url) {
            $urls[$langcode] = $url;
        }
    }

    return $urls;
}

function get_external_faq($faq, $langcode)
{
    global $external_faq_overrides;

    if (isset($external_faq_overrides[$faq][$langcode])) {
        return $external_faq_overrides[$faq][$langcode];
    }

    return null;
}

function maybe_redirect_to_external_faq($langcode = null)
{
    $faq = basename($_SERVER['PHP_SELF']);

    // if no langcode was passed in, try to get the user's language
    if (!$langcode) {
        $langcode = substr(get_desired_language(), 0, 2);
    }

    $url = get_external_faq($faq, $langcode);

    // fall back to English
    if (!$url) {
        $url = get_external_faq($faq, "en");
    }

    if ($url) {
        metarefresh(0, $url, '', '', true);
    }
}
