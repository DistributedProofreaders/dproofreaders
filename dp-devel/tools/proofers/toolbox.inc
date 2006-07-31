<?PHP
include_once($relPath.'v_site.inc'); // $wikihiero_dir

// "PIT" stands for "Proofing Interface Toolbox" (ctrl_frame.php)

// -----------------------------------------------------------------------------

// PIT Popup Link:
// A link that, when clicked, opens a new window containing some tool.

$all_pit_popup_links = array();

function define_pit_popup_link( $id, $label, $url, $window_name, $width, $height )
{
    $window_features = "width=$width,height=$height,directories=0,location=0,menubar=0,resizable,scrollbars,status=0,toolbar=0";
    $js = "window.open('$url','$window_name','$window_features'); return false;";
    $element = "<a href='#' onClick=\"$js\"\n>$label</a>\n";

    global $all_pit_popup_links;
    $all_pit_popup_links[$id] = $element;
}

function echo_popup_links()
{
    global $all_pit_popup_links;
    echo implode('&nbsp;|&nbsp;', $all_pit_popup_links);
}

define_pit_popup_link(
    'search_and_replace',
    _('Search/Replace'),
    'srchrep.php',
    'dp_searchrepl',
    300, 250
);
define_pit_popup_link(
    'table_maker',
    _('Table Maker'),
    'mktable.php',
    'dp_mktable',
    600, 500
);
define_pit_popup_link(
    'greek_transliterator',
    _('Greek Transliterator'),
    'greek2ascii.php',
    'gkasciiWin',
    640, 210
);
if ($wikihiero_dir)
{
    define_pit_popup_link(
        'hieroglyph_transliterator',
        _("Hieroglyphs"),
        'hiero/index.php',
        'dp_hiero',
        600, 400
    );
}

// -----------------------------------------------------------------------------

// vim: sw=4 ts=4 expandtab
?>
