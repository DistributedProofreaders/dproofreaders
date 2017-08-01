<?php
include_once($relPath.'site_vars.php'); // $wikihiero_dir $utf8_site
include_once($relPath.'misc.inc'); // attr_safe(), html_safe()
include_once($relPath.'CharacterSelector.inc');

class ProofreadingToolbox
{
    public function __construct()
    {
        $this->initialize_popup_links();
        $this->initialize_tool_buttons();
        $this->initialize_tool_links();
        $this->character_selector = new CharacterSelector();
    }

    public function output($round)
    {
        global $code_url;

        echo "<div id='toolbox'>";
        echo '<a href="#" accesskey="=" onfocus="top.focusText()"></a>';
        echo '<form name="markform" id="markform" onsubmit="return(false);" action="ctrl_frame.php">';
        echo '<table style="width: 100%;" align="center">';
        echo "<tr>";
        echo '<td valign="top" align="right">';
        $this->character_selector->echo_block();
        echo '</td>';
        echo '<td valign="top" align="center">';
        echo <<<EOS
        <input
            accesskey="["
            type="text"
            value=""
            name="markBox"
            class="dropnormal"
            size="9"
            onclick="this.select();"
        >
        <input
            accesskey="]"
            type="text"
            value=""
            name="markBoxEnd"
            class="dropnormal"
            size="9"
            onclick="this.select()"
        >
EOS;

        $this->echo_tool_buttons( $round->pi_tools['tool_buttons'] );

        echo '</td>';
        echo '<td align="right" valign="top">';
        $help = _("Help");
        echo "<b><span style='color: red;'>$help---&gt;</span></b>";
        echo "<a href='../../faq/" . lang_dir() . "/prooffacehelp.php' accesskey='1' target='helpNewWin'>";
        echo <<<EOS
        <img
            src="gfx/tags/help.png"
            width="18"
            height="18"
            border="0"
            align="top"
            alt="$help"
            title="$help"
        >
        </a>
EOS;
        echo '</td>';
        echo '</tr>';

        echo '<tr>';
        echo '<td valign="top" colspan="3" align="center">';
        echo "<span class='small'>\n";

        echo "<i>"._("Markup shortcuts").":</i>\n";
        $this->echo_tool_links( $round->pi_tools['tool_links'] );
        echo "\n<br>\n";

        echo "<i>". _('Pop-up tools'). ":</i>\n";
        $this->echo_popup_links( $round->pi_tools['popup_links'] );
        echo "\n<br>\n";

        echo "<i>"._("Reference Information").":</i> ";
        $url = "$code_url/faq/".lang_dir().$round->document;
        echo "[<a style=\"color:#0000FF; text-decoration: underline;\" href='$url' target='_blank'>";
        echo _('Guidelines');
        echo "</a>]\n";

        $url = "$code_url/faq/InterfaceDiagram.png";
        echo "[<a style=\"color:#0000FF; text-decoration: underline;\" href='$url' target='_blank'>";
        echo _("Proofreading Diagram");
        echo "</a>]\n";

        echo "</span>\n";
        echo '</td>';
        echo '</tr>';
        echo '</table>';
        echo '</form>';
        echo '</div>';
    }


    // Popup Link
    // A link that, when clicked, opens a new window containing some tool.
    private function define_popup_link( $id, $label, $url, $window_name, $width, $height )
    {
        $window_features = "width=$width,height=$height,directories=0,location=0,menubar=0,resizable,scrollbars,status=0,toolbar=0";
        $js = "window.open('$url','$window_name','$window_features'); return false;";
        $element = "<a href='#' onClick=\"$js\"\n>$label</a>\n";

        $this->_popup_links[$id] = $element;
    }

    private function echo_popup_links( $ids )
    {
        echo implode('&nbsp;|&nbsp;', $this->array_extract($this->_popup_links, $ids));
    }

    private function initialize_popup_links()
    {
        global $wikihiero_dir;

        $this->_popup_links = array();
        $this->define_popup_link(
            'search_and_replace',
            _('Search/Replace'),
            'srchrep.php',
            'dp_searchrepl',
            300, 250
        );
        $this->define_popup_link(
            'table_maker',
            _('Table Maker'),
            'mktable.php',
            'dp_mktable',
            600, 500
        );
        $this->define_popup_link(
            'greek_transliterator',
            _('Greek Transliterator'),
            'greek2ascii.php',
            'gkasciiWin',
            640, 250
        );
        if ($wikihiero_dir)
        {
            $this->define_popup_link(
                'hieroglyph_transliterator',
                _("Hieroglyphs"),
                'hiero/index.php',
                'dp_hiero',
                600, 400
            );
        }
    }


    // Tool Button
    // A button that, when pressed, alters the text in some way.
    private function define_tool_button( $id, $accesskey, $title, $label, $onclick )
    {
        # If the label is very short, the button will be very narrow,
        # unless we add some padding.
        # (But in testing whether the label is short,
        # we have to ignore any markup.)
        $label_text = strip_tags($label);
        $lr_padding = (
            strlen($label_text) == 1
            ? '7px'
            : '2px'
        );

        $title_safe = attr_safe($title);
        $this->_tool_buttons[$id] = <<<EOS
        <button
            onclick="$onclick"
            accesskey="$accesskey"
            class='proofbutton'
            style='padding-left: $lr_padding; padding-right: $lr_padding;'
            align="top"
            title="$title_safe"
        >$label</button>
EOS;
    }

    private function echo_tool_buttons( $ids )
    {
        echo implode('', $this->array_extract($this->_tool_buttons, $ids));
    }

    private function initialize_tool_buttons()
    {
        $this->_tool_buttons = array();
        $this->define_tool_button(
            'italic',
            'i',
            _('Italics'),
            '<i>i</i>',
            "top.surroundSelection('<i>','</i>')"
        );
        $this->define_tool_button(
            'bold',
            'b',
            _('Bold'),
            '<b>B</b>',
            "top.surroundSelection('<b>','</b>')"
        );
        $this->define_tool_button(
            'small_caps',
            's',
            _('Small Caps markup'),
            "<span style='font-variant: small-caps;'>Sc</span>",
            "top.surroundSelection('<sc>','</sc>')"
        );
        $this->define_tool_button(
            'gesperrt',
            'g',
            _('Gesperrt (spaced out)'),
            'g',
            "top.surroundSelection('<g>','</g>')"
        );
        $this->define_tool_button(
            'antiqua',
            'f',
            _('Antiqua'),
            'f',
            "top.surroundSelection('<f>','</f>')"
        );
        $this->define_tool_button(
            'remove_markup',
            'm',
            _('Remove markup from selection'),
            "<span style='text-decoration: line-through;'>&lt;x&gt;</span>",
            "top.transformText('remove_markup')"
        );
        $this->define_tool_button(
            'upper_case',
            'u',
            _('Uppercase selection'),
            'ABC',
            "top.transformText('upper-case')"
        );
        $this->define_tool_button(
            'title_case',
            't',
            _('Title case selection'),
            'Abc',
            "top.transformText('title-case')"
        );
        $this->define_tool_button(
            'lower_case',
            'l',
            _('Lowercase selection'),
            'abc',
            "top.transformText('lower-case')"
        );
    }


    // Tool Link
    // A link that, when clicked, alters the text in some way.
    // (It's not clear whether it's useful to have both
    // tool buttons and tool links in the toolbox.)
    private function define_tool_link( $id, $label, $title, $onclick )
    {
        $label   = html_safe($label);
        $title   = attr_safe($title);
        $onclick = attr_safe($onclick);

        $this->_tool_links[$id] =
            "<a href='#' onclick=\"$onclick\" title=\"$title\">$label</a>";
    }

    private function echo_tool_links( $ids )
    {
        echo implode("\n&nbsp;\n", $this->array_extract($this->_tool_links, $ids));
    }

    private function initialize_tool_links()
    {
        $this->_tool_links = array();
        $this->define_tool_link(
            'greek',
            '[Greek: ]',
            '[Greek:',
            "top.surroundSelection('[Greek: ',']')"
        );
        $this->define_tool_link(
            'sidenote',
            '[Sidenote: ]',
            '[Sidenote:',
            "top.surroundSelection('[Sidenote: ',']')"
        );
        $this->define_tool_link(
            'illustration',
            '[Illustration: ]',
            '[Illustration:',
            "top.surroundSelection('[Illustration: ',']')"
        );
        $this->define_tool_link(
            'note',
            '[** ]',
            _('Note'),
            "top.surroundSelection('[** ',']')"
        );
        $this->define_tool_link(
            'brackets',
            '[ ]',
            '[ ]',
            "top.surroundSelection('[',']')"
        );
        $this->define_tool_link(
            'braces',
            '{ }',
            '{ }',
            "top.surroundSelection('{','}')"
        );
        $this->define_tool_link(
            'footnote',
            '[Footnote #: ]',
            '[Footnote #:',
            "top.surroundSelection('[Footnote #: ',']')"
        );
        $this->define_tool_link(
            'slash_star',
            '/* */',
            _('Start poetry/table'),
            "top.surroundSelection('/*\\n','\\n*/')"
        );
        $this->define_tool_link(
            'slash_number',
            '/# #/',
            _('Start block quote'),
            "top.surroundSelection('/#\\n','\\n#/')"
        );
        $this->define_tool_link(
            'thought_break',
            '<tb>',
            _('Thought Break'),
            "top.surroundSelection('\\n<tb>\\n','')"
        );
        $this->define_tool_link(
            'blank_page',
            '[Blank Page]',
            '[Blank Page]',
            "top.replaceAllText('[Blank Page]')"
        );
    }

    private function array_extract( $source, $keys )
    {
        if ( $keys == 'ALL' )
        {
            return $source;
        }

        $result = array();
        foreach ( $keys as $key )
        {
            if ( array_key_exists($key, $source) )
            {
                $result[$key] = $source[$key];
            }
            else
            {
                // It's tempting to do an assert(0), but $key could be for a tool
                // that *can* exist, but happens not to in this installation.
                // (i.e., currently, hieroglyph_transliterator)
            }
        }
        return $result;
    }
}

// vim: sw=4 ts=4 expandtab
