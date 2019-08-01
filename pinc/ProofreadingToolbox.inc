<?php
include_once($relPath.'misc.inc'); // attr_safe(), html_safe()
include_once($relPath.'faq.inc');
include_once($relPath.'stages.inc');
include_once($relPath.'prefs_options.inc'); // get_user_proofreading_font()

class ToolButton
{
    public function __construct($accesskey, $title, $label, $onclick, $formatting = false)
    {
        $access_string = $accesskey ? "accesskey='$accesskey'" : '';
        $this->html = "<button type='button' class='tool-button' onclick='$onclick' $access_string title='" . attr_safe($title) . "'>$label</button>\n";
        $this->formatting = $formatting;
    }

    public function draw($format_round)
    {
        if ($format_round || !$this->formatting)
        {
            echo "$this->html";
        }
    }
}

// Popup Link
// A link that, when clicked, opens a new window containing some tool.
function draw_popup_link($label, $url, $window_name, $width, $height)
{
    $window_features = "width=$width,height=$height,directories=0,location=0,menubar=0,resizable,scrollbars,status=0,toolbar=0";
    $js = "window.open('$url','$window_name','$window_features'); return false;";
    echo "<a href='#' class='nowrap' onClick=\"$js\"\n>$label</a>\n";
}

function spacer_bar()
{
    echo "|\n";
}

class ProofreadingToolbox
{
    public function __construct()
    {
        $this->initialize_tool_buttons();
    }

    public function output($round)
    {
        echo "<div>";
        $this->draw_character_selector();
        $this->draw_tools($round);
        echo '</div>';
    }

    private function initialize_tool_buttons()
    {
        $this->tool_buttons = [
            new ToolButton('i', _('Italics'), '<i>i</i>', 'top.surroundSelection("<i>","</i>");', true),
            new ToolButton('b', _('Bold'), '<b>B</b>', 'top.surroundSelection("<b>","</b>");', true),
            new ToolButton('s', _('Small Caps markup'), '<span style="font-variant: small-caps;">Sc</span>', 'top.surroundSelection("<sc>","</sc>");', true),
            new ToolButton('g', _('Gesperrt (spaced out)'), 'g', 'top.surroundSelection("<g>","</g>");', true),
            new ToolButton('f', _('Antiqua'), 'f', 'top.surroundSelection("<f>","</f>");', true),
            new ToolButton('m', _('Remove markup from selection'), "<span style='text-decoration: line-through;'>&lt;x&gt;</span>", 'top.transformText("remove_markup");'),
            new ToolButton('u', _('Uppercase selection'), 'ABC', 'top.transformText("upper-case");'),
            new ToolButton('t', _('Title case selection'), 'Abc', 'top.transformText("title-case");'),
            new ToolButton('l', _('Lowercase selection'), 'abc', 'top.transformText("lower-case");'),
            new ToolButton('', '[Greek:', '[Greek: ]', 'top.surroundSelection("[Greek: ","]");'),
            new ToolButton('', '[Sidenote:', '[Sidenote: ]', 'top.surroundSelection("[Sidenote: ","]");', true),
            new ToolButton('', '[Illustration:', '[Illustration: ]', 'top.surroundSelection("[Illustration: ","]");', true),
            new ToolButton('', _('Note'), '[** ]', 'top.surroundSelection("[** ","]");'),
            new ToolButton('', '[ ]', '[ ]', 'top.surroundSelection("[","]");'),
            new ToolButton('', '{ }', '{ }', 'top.surroundSelection("{","}");'),
            new ToolButton('', '[Footnote #:', '[Footnote #: ]', 'top.surroundSelection("[Footnote #: ","]");', true),
            new ToolButton('', _('Start poetry/table'), '/* */', 'top.surroundSelection("/*\\n","\\n*/");', true),
            new ToolButton('', _('Start block quote'), '/# #/', 'top.surroundSelection("/#\\n","\\n#/");', true),
            new ToolButton('', _('Thought Break'), '&lt;tb&gt;', 'top.surroundSelection("\\n<tb>\\n","");', true),
            new ToolButton('', '[Blank Page]', '[Blank Page]', 'top.replaceAllText("[Blank Page]");'),
        ];
    }

    private function draw_character_selector()
    {
        // this is further constructed by javascript using data from the server
        list($font_face, $font_size) = get_user_proofreading_font();
        if (!$font_face)
        {
            // browser default - this should then be same as proofreading textarea
            $font_face = "monospace";
        }
        echo "<div id='selector' style='font-family: $font_face;' class='flexbox'>";
        echo "\n<div id='char-selector' class='nowrap'></div>\n";
        echo "<div class='char_box'><input type='text' readonly class='big_text' id='large_char'></div>\n";
        echo "</div>";
    }


    public function draw_tools($round)
    {
        global $code_url;
        $formatting = is_formatting_round($round);

        echo "<div id='tools'>";
        foreach ($this->tool_buttons as $tool_button)
        {
            $tool_button->draw($formatting);
        }

        draw_popup_link(_('Search/Replace'), 'srchrep.php', 'dp_searchrepl', 300, 250);
        if ($formatting)
        {
            spacer_bar();
            draw_popup_link(_('Table Maker'), 'mktable.php', 'dp_mktable', 600, 500);
        }
        spacer_bar();
        draw_popup_link(_('Greek Transliterator'), 'greek2ascii.php', 'gkasciiWin', 640, 250);

        $url = get_faq_url($round->document);
        echo "[<a href='$url' class='nowrap' target='roundDoc'>", _('Guidelines'), "</a>]\n";

        echo "[<a href='$code_url/faq/InterfaceDiagram.png' class='nowrap' target='ifaceWin'>", _("Proofreading Diagram"), "</a>]\n";

        echo "[<a href='$code_url/faq/prooffacehelp.php' accesskey='1' class='nowrap' target='helpNewWin'>", _("Help"), "</a>]";

        echo "</div>";
    }
}

// vim: sw=4 ts=4 expandtab
