<?php
include_once($relPath.'forum_interface.inc');
include_once($relPath.'metarefresh.inc');

// This include holds information about DP people in charge of various things,
// that is used in FAQs. Also information about forums and wiki used in FAQs.

// SITE-SPECIFIC
global $external_faq_overrides;
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
    "faq_central.php" => [
        "en" => "https://www.pgdp.net/wiki/DP_Official_Documentation:General/FAQ_Central",
        "fr" => "https://www.pgdp.net/wiki/DP_Official_Documentation:General/French/Centre_des_FAQ",
    ],
    "post_proof.php" => [
        "en" => "https://www.pgdp.net/wiki/DP_Official_Documentation:PP_and_PPV/Post-Processing_FAQ",
        "fr" => "https://www.pgdp.net/wiki/DP_Official_Documentation:PP_and_PPV/French/FAQ_post-processing",
    ],
    "ppv.php" => [
        "en" => "https://www.pgdp.net/wiki/DP_Official_Documentation:PP_and_PPV/Post-Processing_Verification_Guidelines",
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
];


function get_faq_url(string $faq, ?string $langcode = null, bool $english_fallback = true): string
{
    global $code_dir, $code_url;

    // if no langcode was passed in, try to get the user's language
    if (!$langcode) {
        $langcode = substr(get_desired_language(), 0, 2);
    }

    $check_lang_codes = [$langcode];
    if ($english_fallback) {
        array_push($check_lang_codes, "en");
    }

    // first try $langcode, then English
    foreach ($check_lang_codes as $langcode) {
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
    if ($english_fallback && is_file("$code_dir/faq/$faq")) {
        return "$code_url/faq/$faq";
    }

    return "";
}

function get_external_faq(string $faq, string $langcode): ?string
{
    global $external_faq_overrides;

    if (isset($external_faq_overrides[$faq][$langcode])) {
        return $external_faq_overrides[$faq][$langcode];
    }

    return null;
}
