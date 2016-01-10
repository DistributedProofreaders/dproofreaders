<?php
$relPath="./pinc/";
include_once($relPath.'base.inc');
include_once($relPath.'pg.inc');
include_once($relPath.'theme.inc');
include_once($relPath.'site_specific.inc');
include_once($relPath.'showstartexts.inc');
include_once($relPath.'page_tally.inc');
include_once($relPath.'site_news.inc');
include_once($relPath.'misc.inc'); // undo_all_magic_quotes()

undo_all_magic_quotes();

output_header(_("Welcome"));
$etext_limit = 10;

default_page_heading();

show_news_for_page("FRONT");

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

echo "\n"
    . "<p>"
    . "<font face=\"{$theme['font_mainbody']}\" color=\"{$theme['color_headerbar_bg']}\" size=\"+1\"><b>"
    . _("Site Concept")
    . "</b></font><br>"
    . "\n";

echo ""
    . _("Distributed Proofreaders provides a web-based method to ease the conversion of Public Domain books into e-books. By dividing the workload into individual pages, many volunteers can work on a book at the same time, which significantly speeds up the creation process.")
    . "</p>"
    . "\n";

echo "<p>"
    . _("During proofreading, volunteers are presented with a scanned page image and the corresponding OCR text on a single web page. This allows the text to be easily compared to the image, proofread, and sent back to the site. A second volunteer is then presented with the first volunteer's work and the same page image, verifies and corrects the work as necessary, and submits it back to the site. The book then similarly progresses through two formatting rounds using the same web interface.")
    . "</p>"
    . "\n";

echo "<p>"
    . _("Once all the pages have completed these steps, a post-processor carefully assembles them into an e-book, optionally makes it available to interested parties for 'smooth reading', and submits it to the Project Gutenberg archive.")
    . "</p>"
    . "\n";

// -----------------------------------------------------------------------------

echo "\n"
    . "<p>"
    . "<font face=\"{$theme['font_mainbody']}\" color=\"{$theme['color_headerbar_bg']}\" size=\"+1\"><b>"
    . _("How You Can Help")
    . "</b></font><br>"
    . "\n";

echo ""
    . "<ul>\n"
    .   "<li>\n"
    .     sprintf(
            _("<a href='accounts/addproofer.php'>Register</a> with the site as a volunteer."))
    .   "</li>\n"
    .   "<li>\n"
    .     sprintf(
            _("Read the introductory email you receive and the <a href='%s/faq/ProoferFAQ.php'>Beginning Proofreader's FAQ</a>."),
            $code_url)
    .   "</li>\n"
    .   "<li>\n"
    .     sprintf(
            _("Confirm your registration, sign in, choose a project, and try proofreading a page or two!"))
    .   "</li>\n"
    . "</ul>"
    . "\n";

echo "<p>\n"
    . sprintf(
        _("Unregistered guests are invited to participate in <a href='tools/post_proofers/smooth_reading.php'>Smooth Reading</a>."))
    . "</p>"
    . "\n"
    . "\n";

echo "<p>"
    . _("Remember that there is no commitment expected on this site beyond the understanding that you do your best.")
    . "</p>"
    . "\n";

echo "<p>"
    . _("Proofread as often or as seldom as you like, and as many or as few pages as you like.  We encourage people to do 'a page a day', but it's entirely up to you! We hope you will join us in our mission of 'preserving the literary history of the world in a freely available form for everyone to use'.")
    . "</p>"
    . "\n";

echo "<p>"
    . sprintf(
        _("There are many other ways to contribute to the site, including managing projects, providing content, or even helping develop improvements to the site! Join other members of our community in the <a href='%s'>Forums</a> to discuss these and many other topics."),
        $forums_url)
    . "</p>"
    . "\n";

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

echo "\n"
    . "<font face=\"{$theme['font_mainbody']}\" color=\"{$theme['color_headerbar_bg']}\" size=\"+1\"><b>"
    . _("Current Progress")
    . "</b></font><br>"
    . "\n"
    . "\n";

echo "<table><tr><td valign='top'>";

//Gold E-texts
showstartexts($etext_limit,'gold'); echo "</td><td valign='top'>";

//Silver E-texts
showstartexts($etext_limit,'silver'); echo "</td><td valign='top'>";

//Bronze E-texts
showstartexts($etext_limit,'bronze'); echo "</td></tr></table>";

echo "<hr><center>\n";
echo _("Our community of proofreaders, project managers, developers, etc. is composed entirely of volunteers.");
echo "</center>\n";

// Show the number of users that have been active over various recent timescales.
foreach ( array(1,7,30) as $days_back )
{
    $res = mysql_query("
        SELECT COUNT(*)
        FROM users
        WHERE t_last_activity > UNIX_TIMESTAMP() - $days_back * 24*60*60
    ") or die(mysql_error());
    $num_users = mysql_result($res,0);
    
    $template = (
        $days_back == 1
        ? _('%s active users in the past twenty-four hours.')
        : _('%s active users in the past %d days.')
    );
    $msg = sprintf( $template, number_format($num_users), $days_back );
    echo "<center><i><b>$msg</b></i></center>\n";
}

echo "<hr><center>\n";
echo sprintf(
    _("Questions or comments? Please contact us at <a href='mailto:%s'>%s</a>."),
    $general_help_email_addr,
    $general_help_email_addr);
echo "</center>&nbsp;<br>\n";

// vim: sw=4 ts=4 expandtab
