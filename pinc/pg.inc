<?php

// Variables, functions, etc. (mostly URL-related) related to Project Gutenberg.

$PG_home_url = "https://www.gutenberg.org/";
$PG_history_url = "https://www.gutenberg.org/wiki/Gutenberg:About";
$PG_catalog_url = "https://www.gutenberg.org/catalog/";
$PG_gutindex_url = "https://www.gutenberg.org/dirs/GUTINDEX.ALL";
$PG_faq_url = "https://www.gutenberg.org/wiki/Category:FAQ";
$PG_html_faq_url = "https://www.gutenberg.org/wiki/Gutenberg:HTML_FAQ";
$PG_donation_url = "https://www.gutenberg.org/wiki/Gutenberg:Project_Gutenberg_Needs_Your_Donation";
$PG_copyright_howto_url = "https://www.gutenberg.org/wiki/Category:How-To";
$PG_greek_howto_url = "https://www.gutenberg.org/wiki/Gutenberg:Greek_How-To";
$PG_mailing_lists_url = "https://www.gutenberg.org/wiki/Gutenberg:Subscribe_How-To";

$PG_in_progress_url = "http://www.dprice48.freeserve.co.uk/GutIP.html";

$PG_paypal_business_id = "donate@gutenberg.org";

// -----------------------------------------------------------------------------

function get_pg_catalog_url_for_etext($etext_number)
// Given a PG etext number, return a URL for the PG catalog page for that text.
{
    return "http://www.gutenberg.org/ebooks/$etext_number";
}

// -----------------------------------------------------------------------------

function get_pg_catalog_link_for_etext($etext_number, $link_text = null)
// Given a PG etext number, return an HTML <a> element
// that links to the PG catalog page for that text.
{
    $url = get_pg_catalog_url_for_etext($etext_number);

    if (is_null($link_text)) {
        $link_text = sprintf(_('PG #%d'), $etext_number);
    }

    return "<a href='$url'>$link_text</a>";
}
