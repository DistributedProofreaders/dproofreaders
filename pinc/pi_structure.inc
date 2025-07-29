<?php

function draw_toolbox()
{
    echo "
<div class='fixed-box tool_box row_flex' id='toolbox'>
    <div id='outer_picker_box' class='fixed-box'>
        <div id='mid_picker_box' class='row_flex'>
            <button type='button' id='hide_picker_button' class='show_hide_picker_button' title='", attr_safe(_("Hide the character picker")), "'>&lang;</button>
            <div class='char_box'><input type='text' readonly class='big_text' id='large_char'></div>
            <div id='picker_box'>
            </div>
        </div>
        <button type='button' id='show_picker_button' class='show_hide_picker_button' style='display:none;' title='", attr_safe(_("Show the character picker")), "'>&rang;</button>
    </div>
    <div id='edit_box' class='stretch-box right-align'>
    <div>",
    action_button('italic', '<i>i</i>', _('italics'), "format"),
    action_button('bold', '<b>B</b>', _('Bold'), "format"),
    action_button('small_caps', '<span style="font-variant: small-caps;">Sc</span>', _('Small Caps markup'), "format"),
    action_button('gesperrt', 'g', _('Gesperrt (spaced out)'), "format"),
    action_button('antiqua', 'f', _('Antiqua'), "format"),
    action_button('remove_markup', "<span style='text-decoration: line-through;'>&lt;x&gt;</span>", _('Remove markup from selection')),
    action_button('upper_case', 'ABC', _('Uppercase selection')),
    action_button('title_case', 'Abc', _('Title case selection')),
    action_button('lower_case', 'abc', _('Lowercase selection')),
    "</div>
    <div>",
    action_button('sidenote', '[Sidenote: ]', '[Sidenote:', "format"),
    action_button('illustration', '[Illustration: ]', '[Illustration:', "format"),
    action_button('note', '[** ]', _('Note')),
    action_button('brackets', '[ ]', '[ ]'),
    action_button('braces', '{ }', '{ }'),
    action_button('footnote', '[Footnote #: ]', '[Footnote #:', "format"),
    action_button('slash_star', '/* */', _('Start poetry/table'), "format"),
    action_button('slash_number', '/# #/', _('Start block quote'), "format"),
    action_button('thought_break', '&lt;tb&gt;', _('Thought Break'), "format"),
    action_button('blank_page', '[Blank Page]', '[Blank Page]'),
    "</div>
        </div>
</div>";
}

function action_button($id, $label, $title = "", $class = "")
{
    return "<button type='button' class='margin_a $class' id='$id' title='$title'>$label</button>";
}
