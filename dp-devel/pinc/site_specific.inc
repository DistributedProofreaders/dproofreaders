<?php
// This include holds site-specific things. Currently, only the heading on
// default.php but it may grow. It should be viewed as complement of
// v_site.inc
 
function default_page_heading()
{
global $code_url;
$real_site_url = 'http://www.pgdp.net';

if ($code_url != "$real_site_url/c" )
{
        echo "<font color='red'>";
        echo "<center>";
        echo "<p>";
        echo "<b>THIS IS A TEST SITE!!!</b>";
        echo "</p>";
        echo "<p>";
        echo "For the real site, go to <a href='$real_site_url'>$real_site_url</
a>.";
        echo "</p>";
        echo "<p>";
        echo "To leave feedback or report bugs regarding this TEST site, ";
        echo "please go to <a href='$real_site_url/phpBB2/viewtopic.php?t=12226'>
this forum topic</a> on the real site.";

        echo "</p>";
        echo "</center>";
        echo "</font>";
}

}
?>
