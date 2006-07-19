<?php
$relPath="./pinc/";
include_once($relPath.'v_site.inc');
include_once($relPath.'pg.inc');
include_once($relPath.'connect.inc');
include_once($relPath.'maintenance_mode.inc');
include_once($relPath.'theme.inc');
include_once($relPath.'site_specific.inc');
$db_Connection=new dbConnect();
include_once($relPath.'showstartexts.inc');
include_once($relPath.'page_tally.inc');
include_once($relPath.'site_news.inc');

abort_if_in_maintenance_mode();
theme(_("Welcome"), "header");
$etext_limit = 10;


default_page_heading();

// Show the number of users that have been active over various recent timescales.
foreach ( array(1,7,28) as $days_back )
{
    $res = mysql_query("
        SELECT COUNT(*)
        FROM users
        WHERE t_last_activity > UNIX_TIMESTAMP() - $days_back * 24*60*60
    ") or die(mysql_error());
    $num_users = mysql_result($res,0);
    
    $template = (
        $days_back == 1
        ? _('%d active users in the past twenty-four hours.')
        : _('%d active users in the past %d days.')
    );
    $msg = sprintf( $template, number_format($num_users), $days_back );
    echo "<center><i><b>$msg</b></i></center>\n";
}

?>

<p><font face="<? echo $theme['font_mainbody']; ?>" color="<? echo $theme['color_headerbar_bg']; ?>" size="+1"><b><? echo _("About This Site") ?></b></font><br>
<?
echo sprintf( _("Distributed Proofreaders was founded in 2000 by Charles Franks to support the digitization of Public Domain books. Originally conceived to assist <a href='%s' target='_new'>Project Gutenberg</a> (PG), Distributed Proofreaders (DP) is now the main source of PG e-books. All our proofreaders, managers, developers and so on are volunteers."), $PG_home_url );
echo "\n";
echo sprintf( _("If you have any questions or comments regarding this site, please e-mail <a href='mailto:%s'>%s</a>."), $general_help_email_addr, $general_help_email_addr );
echo "<br><br>";

show_news_for_page("FRONT");
?>
</p>

<p><font face="<? echo $theme['font_mainbody']; ?>" color="<? echo $theme['color_headerbar_bg']; ?>" size="+1"><b><? echo _("Site Concept"); ?></b></font><br>
<? echo _("This site provides a web-based method of easing the proofreading work associated with the digitization of Public Domain books into Project Gutenberg e-books. By breaking the work into individual pages many proofreaders can be working on the same book at the same time. This significantly speeds up the proofreading/e-book creation process."); ?></p>
<p><? echo _("When a proofreader elects to proofread a page of a particular book, the text and image file are displayed on a single web page. This allows the page text to be easily reviewed and compared to the image file, thus assisting the proofreading of the page text. The edited text is then submitted back to the site via the same web page that it was edited on. A second proofreader is then presented with the work of the first proofreader and the page image. Once they have verified the work of the first proofreader and corrected any additional errors the page text is again submitted back to the site. The book then progresses through two formatting rounds using the same web interface."); ?></p>
<p><? echo _("Once all pages for a particular book have been processed, a post-processor joins the pieces, properly formats them into a Project Gutenberg e-book and submits it to the Project Gutenberg archive."); ?></p>

<p><font face="<? echo $theme['font_mainbody']; ?>" color="<? echo $theme['color_headerbar_bg']; ?>" size="+1"><b><? echo _("How You Can Help"); ?></b></font><br>
<? echo sprintf(_("The first step to take to help us out would be to <a href='accounts/addproofer.php'>register</a> to be a new proofreader. (A 'Register' link also appears at the top of the screen.)  After you register be sure to read over both the email you receive as well as  <a href='%s/faq/faq_central.php'>FAQ Central</a> which provides helpful resources on how to proofread.  (See also the 'Help' at the top of any screen.)  After you have registered &amp; read through some of the intro documents choose an interesting-looking book from our Current Projects and try proofreading a page or two."),$code_url); ?></p>

<p><? echo _("You don't even have to register to have a look at the <a href='tools/post_proofers/smooth_reading.php'>Smooth Reading Pool Preview</a>, though you do to upload corrections. Follow the link for more information."); ?></p>

<p><? echo _("Remember that there is no commitment expected on this site. Proofread as often or as seldom as you like, and as many or as few pages as you like.  We encourage people to do 'a page a day', but it's entirely up to you! We hope you will join us in our mission of 'preserving the literary history of the world in a freely available form for everyone to use'."); ?></p>

<font face="<? echo $theme['font_mainbody']; ?>" color="<? echo $theme['color_headerbar_bg']; ?>" size="+1"><b><? echo _("Most Recent Projects"); ?></b></font><br>
<?
//Gold E-texts
showstartexts($etext_limit,'gold');
//Silver E-texts
showstartexts($etext_limit,'silver');
//Bronze E-texts
showstartexts($etext_limit,'bronze');
theme("", "footer");

// vim: sw=4 ts=4 expandtab
?>
