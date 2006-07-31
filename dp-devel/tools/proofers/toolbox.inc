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

// PIT Tool Button:
// A button that, when pressed, alters the text in some way.

$all_pit_tool_buttons = array();

function define_pit_tool_button( $id, $accesskey, $title, $label, $onclick )
{
    # IE, not surprisingly, differs from all other browsers
    # in rendering the buttons. The difference is in the centering
    # of the labels, and only Opera gets it wrong if pretending to be IE
    $spc = stristr(@$_SERVER['HTTP_USER_AGENT'],"msie") ? "" : "&nbsp;";

    $onmousedown_js = "this.style.position = 'relative'; this.style.top = '1px'; this.style.left = '1px'";
    $onmouseup_js = "this.style.top = '0px'; this.style.left = '0px'";

    global $all_pit_tool_buttons;
    $all_pit_tool_buttons[$id] = <<<EOS
<a
    href="#"
    onclick="$onclick"
    accesskey="$accesskey"
>
<div
    class='proofbutton'
    style='height: 22px; padding-left: 2px; padding-right: 2px;'
    onmousedown="$onmousedown_js"
    onmouseup="$onmouseup_js"
    align="top"
    title="$title"
>$spc$label
</div>
</a>

EOS;
}

function echo_tool_buttons()
{
    global $all_pit_tool_buttons;
    echo implode('', $all_pit_tool_buttons);
}

define_pit_tool_button(
    'italic',
    'i',
    _('Italics'),
    '<i>i</i>',
    "top.new_iMU('<i>','</i>')"
);
define_pit_tool_button(
    'bold',
    'b',
    _('Bold'),
    '<b>B</b>',
    "top.new_iMU('<b>','</b>')"
);
define_pit_tool_button(
    'small_caps',
    's',
    _('Small Caps markup'),
    "<span style='font-variant: small-caps;'>Sc</span>",
    "top.new_iMU('<sc>','</sc>')"
);
define_pit_tool_button(
    'upper_case',
    'u',
    _('Uppercase selection'),
    'ABC',
    "top.transformText('upper-case')"
);
define_pit_tool_button(
    'title_case',
    't',
    _('Title case selection'),
    'Abc',
    "top.transformText('title-case')"
);
define_pit_tool_button(
    'lower_case',
    'l',
    _('Lowercase selection'),
    'abc',
    "top.transformText('lower-case')"
);

// -----------------------------------------------------------------------------

// vim: sw=4 ts=4 expandtab
?>
