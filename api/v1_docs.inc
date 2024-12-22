<?php
include_once($relPath.'faq.inc');

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
        return $docs_with_lang;
    } else {
        return array_keys($external_faq_overrides);
    }
}

function api_v1_document(string $method, array $data, array $query_params): string
{
    $lang_code = $query_params["language_code"] ?? "en";
    $document = $data[":document"];
    $faq_url = get_faq_url($document, $lang_code, false);
    if ("" === $faq_url) {
        throw new NotFoundError("$document is not available in language code '$lang_code'");
    }
    return $faq_url;
}
