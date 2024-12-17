<?php
include_once($relPath.'faq.inc');

function api_v1_documents(string $method, array $data, array $query_params): array
{
    global $external_faq_overrides;

    $lang_code = $query_params["lang_code"] ?? null;
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
    $lang_code = $query_params["lang_code"] ?? "en_US";
    return get_faq_url($data[":document_name"], $lang_code);
}
