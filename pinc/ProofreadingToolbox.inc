<?php
include_once($relPath.'misc.inc'); // attr_safe(), html_safe()
include_once($relPath.'faq.inc');
include_once($relPath.'stages.inc');
include_once($relPath.'CharacterSelector.inc');

function draw_tool_button($accesskey, $title, $label, $onclick, $draw = true)
{
    if ($draw)
    {
        $access_string = $accesskey ? "accesskey='$accesskey'" : '';
        echo "<button type='button' class='tool-button' onclick='$onclick' $access_string title='", attr_safe($title), "'>$label</button>\n";
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
    public function output($round)
    {
        echo "<div class='display-flex' id='toolbox'>";
        $character_selector = new CharacterSelector;
        $character_selector->draw();
        $this->draw_tools($round);
        echo '</div>';
    }

    public function draw_tools($round)
    {
        global $code_url;
        $format_round = is_formatting_round($round);

        echo "<div id='tools'>";
        draw_tool_button('i', _('Italics'), '<i>i</i>', 'top.surroundSelection("<i>","</i>");', $format_round);
        draw_tool_button('b', _('Bold'), '<b>B</b>', 'top.surroundSelection("<b>","</b>");', $format_round);
        draw_tool_button('s', _('Small Caps markup'), '<span style="font-variant: small-caps;">Sc</span>', 'top.surroundSelection("<sc>","</sc>");', $format_round);
        draw_tool_button('g', _('Gesperrt (spaced out)'), 'g', 'top.surroundSelection("<g>","</g>");', $format_round);
        draw_tool_button('f', _('Antiqua'), 'f', 'top.surroundSelection("<f>","</f>");', $format_round);
        draw_tool_button('m', _('Remove markup from selection'), "<span style='text-decoration: line-through;'>&lt;x&gt;</span>", 'top.transformText("remove_markup");');
        draw_tool_button('u', _('Uppercase selection'), 'ABC', 'top.transformText("upper-case");');
        draw_tool_button('t', _('Title case selection'), 'Abc', 'top.transformText("title-case");');
        draw_tool_button('l', _('Lowercase selection'), 'abc', 'top.transformText("lower-case");');
        draw_tool_button('', '[Greek:', '[Greek: ]', 'top.surroundSelection("[Greek: ","]");');
        draw_tool_button('', '[Sidenote:', '[Sidenote: ]', 'top.surroundSelection("[Sidenote: ","]");', $format_round);
        draw_tool_button('', '[Illustration:', '[Illustration: ]', 'top.surroundSelection("[Illustration: ","]");', $format_round);
        draw_tool_button('', _('Note'), '[** ]', 'top.surroundSelection("[** ","]");');
        draw_tool_button('', '[ ]', '[ ]', 'top.surroundSelection("[","]");');
        draw_tool_button('', '{ }', '{ }', 'top.surroundSelection("{","}");');
        draw_tool_button('', '[Footnote #:', '[Footnote #: ]', 'top.surroundSelection("[Footnote #: ","]");', $format_round);
        draw_tool_button('', _('Start poetry/table'), '/* */', 'top.surroundSelection("/*\\n","\\n*/");', $format_round);
        draw_tool_button('', _('Start block quote'), '/# #/', 'top.surroundSelection("/#\\n","\\n#/");', $format_round);
        draw_tool_button('', _('Thought Break'), '&lt;tb&gt;', 'top.surroundSelection("\\n<tb>\\n","");', $format_round);
        draw_tool_button('', '[Blank Page]', '[Blank Page]', 'top.replaceAllText("[Blank Page]");');

        echo "\n<br>\n<i>". _('Pop-up tools'). ":</i>\n";
        draw_popup_link(_('Search/Replace'), 'srchrep.php', 'dp_searchrepl', 300, 250);
        if ($format_round)
        {
            spacer_bar();
            draw_popup_link(_('Table Maker'), 'mktable.php', 'dp_mktable', 600, 500);
        }
        spacer_bar();
        draw_popup_link(_('Greek Transliterator'), 'greek2ascii.php', 'gkasciiWin', 640, 250);

        echo "\n<br>\n<i>", _('Documentation'), ":</i>\n";
        $url = get_faq_url($round->document);
        echo "<a href='$url' class='nowrap' target='roundDoc'>", _('Guidelines'), "</a>\n";
        spacer_bar();
        echo "<a href='$code_url/faq/prooffacehelp.php' accesskey='1' class='nowrap' target='helpNewWin'>", _("Help"), "</a>";

        echo "</div>";
    }
}

// vim: sw=4 ts=4 expandtab
