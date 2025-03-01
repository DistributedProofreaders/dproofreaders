<?php

function show_username_form(?string $username): void
{
    echo "<form method='GET'>\n";
    echo "<p>" . _("Username") . ": ";
    echo "<input name='username' type='text' value='" . attr_safe($username) . "' size='20' autocapitalize='none' required>\n";
    echo "</p>";
    echo "<input type='submit' value='" . attr_safe(_("Look up this user")) . "'>\n";
    echo "</form>\n";
}
