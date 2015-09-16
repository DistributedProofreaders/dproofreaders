<?php
// This include holds site-specific things. Currently, only the heading on
// default.php but it may grow. It should be viewed as complement of
// site_vars.php

function default_page_heading()
{
    global $code_url, $testing;
    $real_site_url = 'http://www.pgdp.net';

    if ($testing)
    {
        echo "<div style='color: red'>";
        echo "<center>";
        echo "<p>";
        echo "<b>" . _("THIS IS A TEST SITE!!!") . "</b>";
        echo "</p>";
        echo "<p>";
        printf(_("For the real site, go to <a href='%1\$s'>%2\$s</a>."), $real_site_url, $real_site_url);
        echo "</p>";
        echo "<p>";
        printf(_("To leave feedback or report bugs regarding this TEST site,
            please go to <a href='%s'>this forum topic</a> on the real site."),
            "$real_site_url/phpBB3/viewtopic.php?t=12226");
        echo "</p>";
        echo "</center>";
        echo "</div>";
    }

}

// vim: sw=4 ts=4 expandtab
