<?php
$relPath = "./pinc/";
include_once($relPath.'base.inc');
include_once($relPath.'pg.inc');
include_once($relPath.'theme.inc');
include_once($relPath.'showstartexts.inc');
include_once($relPath.'page_tally.inc');
include_once($relPath.'site_news.inc');
include_once($relPath.'walkthrough.inc');

// SITE-SPECIFIC
// Add social media tags to main page for PROD
$extra_args = [];
if (!$testing) {
    $extra_args = ['head_data' => <<<EOF
            <meta name='description' content='Distributed Proofreaders provides a web-based method to ease the conversion of Public Domain books into e-books. By dividing the workload into individual pages, many volunteers can work on a book at the same time.'>
            <meta property='og:type'  content='website'>
            <meta property='og:url'   content='$code_url/'>
            <meta property='og:title' content='Distributed Proofreaders'>
            <meta property='og:description' content='Distributed Proofreaders provides a web-based method to ease the conversion of Public Domain books into e-books. By dividing the workload into individual pages, many volunteers can work on a book at the same time.'>
            <meta property='og:image' content='https://www.pgdp.net/dp_branding/dp-mark-400px-white.png'>
            <meta property='og:image:width'  content='400'>
            <meta property='og:image:height' content='400'>
            <script type="application/ld+json">
                {
                  "@context": "https://schema.org",
                  "@type": "Organization",
                  "name": "Distributed Proofreaders",
                  "legalName": "Distributed Proofreaders",
                  "url": "https://www.pgdp.net",
                  "logo": "https://www.pgdp.net/dp_branding/dp-mark-400px-white.png",
                  "foundingDate": "2000",
                  "sameAs": [
                    "https://www.pgdp.net/c",
                    "https://blog.pgdp.net/",
                    "https://twitter.com/DProofreaders",
                    "https://www.linkedin.com/groups/832347/",
                    "https://www.facebook.com/distributed.proofreaders/"
                  ]
                }
            </script>
        EOF
    ];
}

output_header(_("Welcome"), true, $extra_args);
$etext_limit = 10;

$image = get_page_header_image("FRONT");
if ($image) {
    echo "<div style='margin-top: 1em;'>$image</div>";
}

show_news_for_page("FRONT");

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

// SITE-SPECIFIC
// Sites outside the PGDP arena will probably want to change
// some or all of the wording in the following sections.

echo "\n"
    . "<h2>"
    . _("Site Concept")
    . "</h2>"
    . "\n";

echo "<p>"
    . _("Distributed Proofreaders provides a web-based method to ease the conversion of Public Domain books into e-books. By dividing the workload into individual pages, many volunteers can work on a book at the same time, which significantly speeds up the creation process.")
    . "</p>"
    . "\n";

echo "<p>"
    . _("During proofreading, volunteers are presented with a scanned page image and the corresponding OCR text on a single web page. This allows the text to be easily compared to the image, proofread, and sent back to the site. A second volunteer is then presented with the first volunteer's work and the same page image, verifies and corrects the work as necessary, and submits it back to the site. The book then similarly progresses through a third proofreading round and two formatting rounds using the same web interface.")
    . "</p>"
    . "\n";

echo "<p>"
    . _("Once all the pages have completed these steps, a post-processor carefully assembles them into an e-book, optionally makes it available to interested parties for 'smooth reading', and submits it to the Project Gutenberg archive.")
    . "</p>"
    . "\n";

// -----------------------------------------------------------------------------

echo "\n"
    . "<h2>"
    . _("How You Can Help")
    . "</h2>"
    . "\n";

echo ""
    . "<ul>\n"
    .   "<li>\n"
    .     sprintf(
            _("<a href='%s'>Register</a> with the site as a volunteer"),
            "$code_url/accounts/addproofer.php")
    .     "<br>"
    .     _("and/or")
    .   "</li>\n"
    .   "<li>\n"
    .     sprintf(
            _("<a href='%s'>Donate</a> to the Distributed Proofreaders Foundation."),
            "http://www.pgdp.net/wiki/DPFoundation:Information_for_Donors")
    .   "</li>\n"
    . "</ul>"
    . "\n";

echo "<p>"
    . _('Registered volunteers may contribute to Distributed Proofreaders in several ways including proofreading, "smooth reading" pre-released e-books to check for errors, managing projects, providing content, or even helping develop improvements to the site. Volunteers may also join other members of our community in our forums to discuss these and many other topics.')
    . "</p>"
    . "\n";

echo "\n"
    . "<h2>"
    . _("Volunteering at Distributed Proofreaders")
    . "</h2>"
    . "\n";

echo "<p>"
    . sprintf(
        _("It's easy to volunteer at Distributed Proofreaders. Simply <a href='%s'>register as a volunteer</a>. Once you've confirmed your registration by e-mail, you'll receive an introductory e-mail with basic instructions on how to log in and use the site. Then, you're ready to sign in and start learning to proofread or visit the smooth reading page to pick an e-book to read! Wherever you go, you'll find lots of information to help you get started."),
        "$code_url/accounts/addproofer.php");

$walkthrough_url = get_walkthrough_url();
if ($walkthrough_url) {
    echo " " . sprintf(
        _("Please try our <a href='%s'>Walkthrough</a> for a preview of the steps involved when proofreading on this site."),
        $walkthrough_url);
}

echo "</p>"
    . "\n";

echo "<p>"
    . _("There is no commitment expected on this site beyond the understanding that you do your best. Spend as much or as little time as you like. We encourage you to proofread at least a page a day and/or smooth read a book as often as your time allows, but it's entirely up to you.")
    . "</p>"
    . "\n";

echo "<p>"
    . _('We hope you will join us in our mission of "preserving the literary history of the world in a freely available form for everyone to use."')
    . "</p>"
    . "\n";

echo "<p>"
    . sprintf(
        _("Distributed Proofreaders regrets that we are unable to verify court-ordered or any official government-related service that requires confirmation of identity. Also, because our system cannot adequately record time spent participating, we are unable to verify hours worked for other community services that require tracking of time spent. For more information, please see <a href='%s'>this page</a>."),
        "$wiki_url/DP_Official_Documentation:General/Certification_of_Volunteer_Work")
    . "</p>"
    . "\n";

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

echo "\n"
    . "<h2>"
    . _("Current Progress")
    . "</h2>"
    . "\n"
    . "\n";

//Gold E-texts
showstartexts($etext_limit, 'gold');

//Silver E-texts
showstartexts($etext_limit, 'silver');

//Bronze E-texts
showstartexts($etext_limit, 'bronze');

echo "<hr style='clear: both'>";
echo "<p class='center-align'>\n";
echo _("Our community of proofreaders, project managers, developers, etc. is composed entirely of volunteers.");
echo "<br>\n";

// Show the number of users that have been active over various recent timescales.
foreach ([1, 7, 30] as $days_back) {
    $res = DPDatabase::query(sprintf("
        SELECT COUNT(*)
        FROM users
        WHERE t_last_activity > UNIX_TIMESTAMP() - %d * 24*60*60
    ", $days_back));
    [$num_users] = mysqli_fetch_row($res);

    $template = (
        $days_back == 1
        ? _('%s active users in the past twenty-four hours.')
        : _('%1$s active users in the past %2$d days.')
    );
    $msg = sprintf($template, number_format($num_users), $days_back);
    echo "<span class='bold italic'>$msg</span><br>\n";
}

echo "</p>\n";
echo "<hr><p class='center-align'>\n";
echo sprintf(
    _('Questions or comments? Please contact us at <a href="%1$s">%2$s</a>.'),
    "mailto:$general_help_email_addr",
    $general_help_email_addr);
echo "</p>\n";
