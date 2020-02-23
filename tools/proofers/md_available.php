<?php
$relPath="./../../pinc/";
include_once($relPath.'base.inc');
include_once($relPath.'user_is.inc');
include_once($relPath.'theme.inc');
include_once($relPath.'project_states.inc');
include_once($relPath.'projectinfo.inc');

require_login();

output_header(_("Image Metadata Collection"));

if (!$site_supports_metadata)
{
    echo _("md_available.php: \$site_supports_metadata is false, so exiting.");
    exit();
}

//Phase 1 table----------------------------------------------------------------------------------

echo "<table class='themed theme_striped'>\n";
      // Header row
        echo "<tr>\n";
        echo "    <td align='center' colspan='4'><b>" . sprintf(_("Books Waiting for Phase %d Review"), 1) . "</b></td><tr></tr>\n";
      echo "    <td align='center' colspan='4'>" . _("In this phase we determine that the book has all of its pages and annotate what the original page number was in the printed version.") . "</td><tr></tr>\n";
        echo "    <td align='center' colspan='1'><b>" . _("Title") . "</b></td>\n";
        echo "    <td align='center' colspan='1'><b>" . _("Author") . "</b></td>\n";
        echo "    <td align='center' colspan='1'><b>" . _("Total Pages") . "</b></td>\n";
//        echo "    <td align='center' colspan='1'><b>Remaining Pages</b></td>\n";
        echo "</tr>\n";

      $result = mysqli_query(DPDatabase::get_connection(), "SELECT projectid, nameofwork, authorsname, language, state FROM projects
                WHERE state = 'project_md_first' AND thumbs = 'yes'");

      while ($row = mysqli_fetch_assoc($result)) {
           $projectid = $row["projectid"];
           $state = $row["state"];
           $name = $row["nameofwork"];
           $author = $row["authorsname"];
           $language = $row["language"];

           $numpages = Project_getNumPages( $projectid );

      echo "<tr>";
      echo "<td align='right'><a href = \"md_phase1.php?projectid=$projectid\">".html_safe($name)."</a></td>\n";
      echo "<td align='right'>".html_safe($author)."</td>\n";
      echo "<td align='right'>$numpages</td>\n";
//      echo "<td align='right'>#pages</td>\n";

      echo "</tr>";
    }

//echo "</table>";
echo "<br>";
        echo "<tr></tr>\n";
        echo "<tr></tr>\n";
        echo "<tr></tr>\n";
        echo "<tr></tr>\n";
        echo "<tr></tr>\n";
        echo "<tr></tr>\n";


//Phase 2 table----------------------------------------------------------------------------------
//echo "<table class='themed theme_striped'>\n";
      // Header row
        echo "<tr>\n";
        echo "    <td align='center' colspan='4'><b>" . sprintf(_("Books Waiting for Phase %d Review"), 2) . "</b></td><tr></tr>\n";
            echo "    <td align='center' colspan='4'>" . _("In this phase we go through each page and annotate which pages contain footnotes, chapter headings, etc.") . "</td><tr></tr>\n";
        echo "    <td align='center' colspan='1'><b>" . _("Title") . "</b></td>\n";
        echo "    <td align='center' colspan='1'><b>" . _("Author") . "</b></td>\n";
        echo "    <td align='center' colspan='1'><b>" . _("Total Pages") . "</b></td>\n";
        echo "    <td align='center' colspan='1'><b>" . _("Remaining Pages") . "</b></td>\n";
        echo "</tr>\n";

      $result = mysqli_query(DPDatabase::get_connection(), "SELECT projectid, nameofwork, authorsname, language, username, state FROM projects
                WHERE state = 'project_md_second'");

      while ($row = mysqli_fetch_assoc($result)) {
           $projectid = $row["projectid"];
           $state = $row["state"];
           $name = $row["nameofwork"];
           $author = $row["authorsname"];
           $language = $row["language"];

           $numpages = Project_getNumPages( $projectid );
           $availpages = Project_getNumPagesInState( $projectid, 'avail_md_second');

      echo "<tr>";
      echo "<td align='right'><a href = \"md_phase2.php?projectid=$projectid\">".html_safe($name)."</a></td>\n";
      echo "<td align='right'>".html_safe($author)."</td>\n";
      echo "<td align='right'>$numpages</td>\n";
      echo "<td align='right'>$availpages</td>\n";

      echo "</tr>";
    }

echo "</table>";

echo "</center>";
echo "<br>";

// vim: sw=4 ts=4 expandtab
