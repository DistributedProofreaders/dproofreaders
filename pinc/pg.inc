<?php

// Variables, functions, etc. (mostly URL-related) related to Project Gutenberg.

global $PG_home_url;
$PG_home_url = "https://www.gutenberg.org";
$PG_faq_url = "https://www.gutenberg.org/help/faq.html";
$PGLAF_upload_url = "https://upload.pglaf.org";
$PGLAF_inprogress_url = "https://inprogress.pglaf.org";

// -----------------------------------------------------------------------------

/**
 * Given a PG etext number, return a URL for the PG catalog page for that text.
 */
function get_pg_catalog_url_for_etext(?int $etext_number): string
{
    global $PG_home_url;
    $n = is_null($etext_number) ? "" : "$etext_number";
    return "$PG_home_url/ebooks/$n";
}

// -----------------------------------------------------------------------------

/**
 * Given a PG etext number, return an HTML <a> element
 * that links to the PG catalog page for that text.
 */
function get_pg_catalog_link_for_etext(int $etext_number, ?string $link_text = null): string
{
    $url = get_pg_catalog_url_for_etext($etext_number);

    if (is_null($link_text)) {
        $link_text = sprintf(_('PG #%d'), $etext_number);
    }

    return "<a href='$url'>$link_text</a>";
}
