<?php

// Searching for book records in an external catalog
// via Z39.50 protocol (implemented by yaz library).

$relPath='../../pinc/';
include_once($relPath.'base.inc');
include_once($relPath.'theme.inc');
include_once($relPath.'marc_format.inc');

require_login();

$action = @$_REQUEST['action'];

if ( $action == 'show_query_form' )
{
    show_query_form();
}
elseif ( $action == "do_search_and_show_hits" )
{
    do_search_and_show_hits();
}
else
{
    die( "unrecognized value for 'action' parameter: '$action'" );
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function show_query_form()
{
    output_header(_("Create a Project"));
    if (!function_exists('yaz_connect'))
    {
        echo "<br>";
        echo "<center>";
        echo "<b>";
        echo _("PHP is not compiled with YAZ support.  Please do so and try again.");
        echo "</b>";
        echo "<br><br>\n";
        echo sprintf(
            _("Until you do so, click <a href='%s'>here</a> for creating a new project."),
            'editproject.php?action=createnew' );
        echo "<br><br>\n";
        echo sprintf(
            _("If you believe you should be seeing the Create Project page please contact a <a href='%s'>Site Administrator</a>"),
            "mailto:".$GLOBALS['site_manager_email_addr'] );
        echo "</center>";
    }
    else
    {
        global $theme;

        echo "<br>\n";
        echo "<form method='post' action='external_catalog_search.php'>\n";
        echo "<input type='hidden' name='action' value='do_search_and_show_hits'>\n";
        echo "<center>";
        echo "<table cellspacing='0' cellpadding='5' border='1' width='75%' style='border: 1px solid #111; border-collapse: collapse'>";

        echo "<tr>";
        echo   "<td bgcolor='".$theme['color_headerbar_bg']."' colspan='2' align='center'>";
        echo     "<b>";
        echo       "<font color='".$theme['color_headerbar_font']."'>";
        echo         _("Create a Project");
        echo       "</font>";
        echo     "</b>";
        echo   "</td>";
        echo "</tr>\n";

        echo "<tr>";
        echo   "<td bgcolor='".$theme['color_navbar_bg']."' colspan='2' align='center'>";
        echo     "<font color='".$theme['color_navbar_font']."'>";
        echo       _("Please put in as much information as possible to search for your project.  The more information the better but if not accurate enough may rule out results.");
        echo     "</font>";
        echo   "</td>";
        echo "</tr>\n";

        foreach (
            array(
                'title'     => _('Title'),
                'author'    => _('Author'),
                'publisher' => _('Publisher'),
                'pubdate'   => _('Publication Year (eg: 1912)'),
                'isbn'      => _('ISBN'),
                'issn'      => _('ISSN'),
                'lccn'      => _('LCCN'),
            )
            as $field_name => $field_label
        )
        {
            echo "<tr>";
            echo   "<td bgcolor='".$theme['color_navbar_bg']."' width='35%'>";
            echo     "<b>";
            echo       "<font color='".$theme['color_navbar_font']."'>";
            echo         $field_label;
            echo       "</font>";
            echo     "</b>";
            echo   "</td>";
            echo   "<td bgcolor='#FFFFFF'>";
            echo     "<input type='text' size='30' name='$field_name'>";
            echo   "</td>";
            echo "</tr>\n";
        }

        echo "<tr>";
        echo   "<td bgcolor='".$theme['color_headerbar_bg']."' colspan='2' align='center'>";
        echo     "<input type='submit' value='", attr_safe(_('Search')), "'>";
        echo   "</td>";
        echo "</tr>\n";

        echo "</table>";
        echo "</center>";
        echo "</form>\n";
    }
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function do_search_and_show_hits()
{
    output_header("Search Results");
    echo "<br>";
    if (empty($_GET['start'])) { $start = 1; } else { $start = $_GET['start']; }
    if (!empty($_GET['fq']))
    {
        $fullquery = unserialize(base64_decode($_GET['fq']));
    }
    else
    {
        $fullquery = query_format();
    }

    global $external_catalog_locator;
    $id = yaz_connect($external_catalog_locator);
    yaz_syntax($id, "usmarc");
    yaz_element($id, "F");
    yaz_search($id, "rpn", trim($fullquery));
    $extra_options = array("timeout" => 60);
    yaz_wait($extra_options);
    $errorMsg = yaz_error($id);

    if (!empty($errorMsg))
    {
        echo "<center>";
        echo _("The following error has occurred:");
        echo "<br><br>";
        echo "<b><i>$errorMsg</i></b>";
        echo "<p>";
        $url = "editproject.php?action=createnew";
        echo sprintf(
            _("Please try again. If the problem recurs, please create your project manually by following this <a href='%s'>link</a>."), $url);
        echo "</center>";
        exit();
    }
    
    echo "<center>";
    if (yaz_hits($id) == 0)
    {
        echo "<b>";
        echo _("There were no results returned.");
        echo "</b>";
        echo "<br>";
        echo _("Please search again or click 'No Matches' to create the project manually.");
        echo "<br>";
    }
    else
    {
        echo "<b>";
        echo sprintf(
            _("%d results returned. Note that some non-book results may not be displayed."),
            yaz_hits($id) );
        echo "<br>";
        echo _("Please pick a result from below:");
        echo "</b>";
    }
    echo "</center>";

    echo "<br><form method='post' action='editproject.php'>";
    echo "<input type='hidden' name='action' value='create_from_marc_record'>";
    echo "<table border='0 width='100%' cellpadding='0' cellspacing='0'>";

    // -----------------------------------------------------

    $hits_per_page = 20; // Perhaps later this can be a PM preference or an option on the form.
    $i = 1;
    while (($start <= yaz_hits($id) && $i <= $hits_per_page))
    {
        $rec = yaz_record($id, $start, "array");
        //if it's not a book don't display it.  we might want to uncomment in the future if there are too many records being returned - if (substr(yaz_record($id, $start, "raw"), 6, 1) != "a") { $start++; continue; }
        $title = marc_title($rec);
        $author = marc_author($rec);
        $publisher = marc_publisher($rec);
        $language = marc_language($rec);
        $lccn = marc_lccn($rec);
        $isbn = marc_isbn($rec);

        if ($i % 2 == 1) { echo "<tr>"; }

        echo "<td width='5%' align='center' valign='top'>";
        echo "<input type='radio' name='rec' value='".base64_encode(serialize($rec))."'>";
        echo "</td>";
        echo "<td width='45%' align='left' valign='top'>";
        echo "<table border='0' width='100%' cellpadding='0' cellspacing='0'>";

        foreach ( array(
                array( 'label' => _("Title"),     'value' => $title ),
                array( 'label' => _("Author"),    'value' => $author ),
                array( 'label' => _("Publisher"), 'value' => $publisher ),
                array( 'label' => _("Language"),  'value' => $language ),
                array( 'label' => _("LCCN"),      'value' => $lccn ),
                array( 'label' => _("ISBN"),      'value' => $isbn )
            )
            as $couple
        )
        {
            $label = $couple['label'];
            $value = $couple['value'];
            echo "<tr>";
            echo   "<td width='20%' align='left' valign='top'><b>$label</b>:</td>";
            echo   "<td align='left' valign='top'>$value</td>";
            echo "</tr>\n";
        }

        echo "</table><p></td>";

        if ($i % 2 != 1) { echo "</tr>\n"; }

        $i++;
        $start++;
    }
    if ($i % 2 != 1) { echo "</tr>\n"; }

    // -----------------------------------------------------

    $encoded_fullquery = base64_encode(serialize($fullquery));
    echo "<tr>";
    echo "<td colspan='2' width='50%' align='left' valign='top'>";
    if (isset($_GET['start']) && ($_GET['start']-$hits_per_page) > 0)
    {
        $url = "external_catalog_search.php?action=do_search_and_show_hits&start=".($_GET['start']-$hits_per_page)."&fq=$encoded_fullquery";
        echo "<a href='$url'>Previous</a>";
    }
    else
    {
        echo "&nbsp;";
    }
    echo "</td>";
    echo "<td colspan='2' width='50%' align='right' valign='top'>";
    if (($start+$hits_per_page) <= yaz_hits($id))
    {
        $url = "external_catalog_search.php?action=do_search_and_show_hits&start=$start&fq=$encoded_fullquery";
        echo "<a href='$url'>Next</a>";
    }
    else
    {
        echo "&nbsp;";
    }
    echo "</td>";
    echo "</tr>\n";

    // -----------------------------------------------------

    echo "</table><br><center>";
    if (yaz_hits($id) != 0)
    {
        echo "<input type='submit' value='", attr_safe(_("Create the Project")), "'>&nbsp;";
    }

    $label = attr_safe(_('Search Again'));
    $url = "external_catalog_search.php?action=show_query_form";
    echo "<input type='button' value='$label' onclick='javascript:location.href=\"$url\";'>";
    echo "&nbsp;";

    $label = attr_safe(_('No Matches'));
    $url = "editproject.php?action=createnew";
    echo "<input type='button' value='$label' onclick='javascript:location.href=\"$url\";'>";
    echo "&nbsp;";

    $label = attr_safe(_('Quit'));
    $url = "projectmgr.php";
    echo "<input type='button' value='$label' onclick='javascript:location.href=\"$url\";'>";

    echo "</form>";
    echo "</center>";
    yaz_close($id);
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function query_format()
{
    $attr_set = 0;
    $fullquery = "";

    if ($_REQUEST['title'])
    {
        $fullquery = $fullquery.' @attr 1=4 "'.$_REQUEST['title'].'"';
        $attr_set++;
    }
    if ($_REQUEST['author'])
    {
        $author = $_REQUEST['author'];
        if (stristr($_REQUEST['author'], ","))
        {
            $author = $_REQUEST['author'];
        }
        else
        {
            if (stristr($_REQUEST['author'], " "))
            {
                $author = substr($_REQUEST['author'], strrpos($_REQUEST['author'], " "))
                    . ", "
                    . substr($_REQUEST['author'], 0, strrpos($_REQUEST['author'], " "));
            }
        }
        $fullquery = $fullquery.' @attr 1=1003 "'.trim($author).'"';
        $attr_set++;
    }
    if ($_REQUEST['isbn'])
    {
        $fullquery = $fullquery.' @attr 2=3 @attr 1=7 '.str_replace("-", "", $_REQUEST['isbn']).'';
        $attr_set++;
    }
    if ($_REQUEST['issn'])
    {
        $fullquery = $fullquery.' @attr 2=3 @attr 1=8 '.$_REQUEST['issn'].'';
        $attr_set++;
    }
    if ($_REQUEST['lccn'])
    {
        $fullquery = $fullquery.' @attr 2=3 @attr 1=9 '.$_REQUEST['lccn'].'';
        $attr_set++;
    }
    if ($_REQUEST['pubdate'])
    {
        $fullquery = $fullquery.' @attr 2=3 @attr 1=31 '.$_REQUEST['pubdate'].'';
        $attr_set++;
    }
    if ($_REQUEST['publisher'])
    {
        $fullquery = $fullquery.' @attr 1=1018 "'.$_REQUEST['publisher'].'"';
        $attr_set++;
    }
    for ($i = 1; $i <= ($attr_set - 1); $i++)
    {
        $fullquery = "@and ".$fullquery;
    }
    return $fullquery;
}

// vim: sw=4 ts=4 expandtab
