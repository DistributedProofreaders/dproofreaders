<?php
include_once($relPath.'faq.inc');
include_once("api_common.inc");

/** @param array<string, string|string[]> $query_params */
function api_v1_documents(string $method, array $data, array $query_params): array
{
    global $external_faq_overrides;

    $lang_code = $query_params["language_code"] ?? null;
    if ($lang_code) {
        $docs_with_lang = [];
        foreach ($external_faq_overrides as $doc => $doc_array) {
            if (isset($doc_array[$lang_code])) {
                $docs_with_lang[] = $doc;
            }
        }
        $docs = $docs_with_lang;
    } else {
        $docs = array_keys($external_faq_overrides);
    }
    send_cache_header(60 * 60 * 24, "public");
    return $docs;
}

/** @param array<string, string|string[]> $query_params */
function api_v1_document(string $method, array $data, array $query_params): string
{
    $lang_code = $query_params["language_code"] ?? "en";
    $document = $data[":document"];
    $faq_url = get_faq_url($document, $lang_code, false);
    if ("" === $faq_url) {
        throw new NotFoundError("$document is not available in language code '$lang_code'");
    }
    send_cache_header(60 * 60 * 24, "public");
    return $faq_url;
}

/** @param array<string, string|string[]> $query_params */
function api_v1_dictionaries(string $method, array $data, array $query_params): array
{
    $dict_list = get_languages_with_dictionaries();
    asort($dict_list);
    send_cache_header(60 * 60 * 24, "public");
    return $dict_list;
}
