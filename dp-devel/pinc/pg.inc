<?PHP

// Variables, functions, etc. (mostly URL-related) related to Project Gutenberg.

$PG_home_url            = "http://www.gutenberg.net/";
$PG_history_url         = "http://www.gutenberg.net/about/";
$PG_catalog_url         = "http://www.gutenberg.net/catalog/";
$PG_gutindex_url        = "http://www.gutenberg.net/dirs/GUTINDEX.ALL";
$PG_faq_url             = "http://www.gutenberg.net/faq/gutfaq.htm";
$PG_html_faq_url        = "http://www.gutenberg.net/faq/gutfaq.htm#H.1";
$PG_donation_url        = "http://www.gutenberg.net/fundraising/donate";
$PG_copyright_howto_url = "http://www.gutenberg.net/howto/copyright-howto";
$PG_greek_howto_url     = "http://www.gutenberg.net/howto/greek/";
$PG_mailing_lists_url   = "http://www.gutenberg.net/howto/subscribe-howto";

$PG_in_progress_url     = "http://www.dprice48.freeserve.co.uk/GutIP.html"; 

$PG_paypal_business_id  = "donate@gutenberg.net";

// -----------------------------------------------------------------------------

function get_pg_catalog_url_for_etext( $etext_number )
// Given a PG etext number, return a URL for the PG catalog page for that text.
{
    return "http://www.gutenberg.net/etext/$etext_number";
}

// -----------------------------------------------------------------------------

function get_pg_catalog_link_for_etext( $etext_number, $link_text=null )
// Given a PG etext number, return an HTML <a> element
// that links to the PG catalog page for that text.
{
    $url = get_pg_catalog_url_for_etext( $etext_number );

    if ( is_null($link_text) )
    {
        $link_text = $etext_number;
    }

    return "<a href='$url'>$link_text</a>";
}

// vim: sw=4 ts=4 expandtab
?>
