<?php
$relPath="./../../../pinc/";
include_once($relPath.'base.inc');
include_once($relPath.'slim_header.inc');
include_once($relPath.'misc.inc'); // html_safe()
include_once("$wikihiero_dir/wh_language.php");
include_once("$wikihiero_dir/wikihiero.php");

require_login();

$header_args = array(
  'js_files' => array(
      "$code_url/tools/proofers/hiero/hiero.js",
  ),
);

slim_header(_("Hieroglyphs"), $header_args);

$hierobox = array_get($_POST, "hierobox", "");
$table = array_get($_POST, "table", "");

$tables=array(
    "Phoneme",
    "A",
    "B",
    "C",
    "D",
    "E",
    "F",
    "G",
    "H",
    "I",
    "K",
    "L",
    "M",
    "N",
    "O",
    "P",
    "Q",
    "R",
    "S",
    "T",
    "U",
    "V",
    "W",
    "X",
    "Y",
    "Z",
    "Aa",
    "All"
);

$syntax=array("-",":","*","!");
$escaped_hierobox = html_safe($hierobox);
$table_options = table_options($tables, $table);
$syntax_options = syntax_options($syntax);
$glpyh_table = glyphs($hierobox, $wikihiero_url);

echo <<<HTML
<form name="hieroform" method="POST">
<table>
<tr><td rowspan="2">
<textarea name="hierobox" rows="4" cols="30">$escaped_hierobox</textarea>
</td><td colspan="2">
<select onChange="hieroform.submit()" name="table">
$table_options
</select>
</td></tr>
<tr><td>
<select onChange='hierobox.value+=this.value; this.value=0;'>
$syntax_options
</select>

</td><td class="right-align">
<input type="submit" value="Submit">
<input type="button" value="Reset" onClick="hierobox.value='';">
</td></tr>
<tr><td colspan="3">
$glpyh_table
</td></tr>
</table>
</form>
<hr>
HTML;

// Based on Wikihiero's WH_Text in its index.php

function WH_Text( $index )
{
    global $wh_language;
    global $lang;
    if(isset($wh_language[$index]))
    {
        if(isset($wh_language[$index][$lang]))
            return $wh_language[$index][$lang];
        else
            return $wh_language[$index]["en"];
    }
    return "";
}

// This file is almost completely based on WikiHiero's wh_table.php

//////////////////////////////////////////////////////////////////////////
//
// WikiHiero - A PHP convert from text using "Manual for the encoding of 
// hieroglyphic texts for computer input" syntax to HTML entities (table and
// images).
//
// Copyright (C) 2004 Guillaume Blanchard (Aoineko)
// 
// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License
// as published by the Free Software Foundation; either version 2
// of the License, or any later version.
// 
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
// 
// You should have received a copy of the GNU General Public License
// along with this program; if not, write to the Free Software
// Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
//
//////////////////////////////////////////////////////////////////////////
$lang=short_lang_code();
$wh_img_url="$wikihiero_url/".WH_IMG_DIR;
if($dh = opendir("$wikihiero_dir/".WH_IMG_DIR)) 
{
    while(($file = readdir($dh)) !== false) 
    {
        $files[]=$file;
    }
    closedir($dh);
}
natsort($files);
foreach($files as $file) {
    $code=WH_GetCode($file);
    if($table == "All")
    {
        if(in_array($code, $wh_phonemes))
            echo img("$wh_img_url/$file","$code [".array_search($code, $wh_phonemes)."]");
        else
            echo img("$wh_img_url/$file",$code);
    }
    else if($table == "Phoneme")
    {
        if(in_array($code, $wh_phonemes))
            echo img("$wh_img_url/$file","$code [".array_search($code, $wh_phonemes)."]");
    }
    else if($table == "Aa")
    {
        if((substr($code, 0, 2) == $table) && ctype_digit($code[2]))
        {
            if(in_array($code, $wh_phonemes))
                echo img("$wh_img_url/$file","$code [".array_search($code, $wh_phonemes)."]");
            else
                echo img("$wh_img_url/$file",$code);
        }
    }
    else
    {
        if(isset($code[0]) && $code[0] == $table && ctype_digit($code[1]))
        {
            if(in_array($code, $wh_phonemes))
                echo img("$wh_img_url/$file","$code [".array_search($code, $wh_phonemes)."]");
            else
                echo img("$wh_img_url/$file",$code);
        }
    }
}

function img($src,$title)
{
    $src = attr_safe($src);
    $title = attr_safe($title);
    $onClick = "add('". attr_safe(preg_replace(array("/^.*[[]/","/[]].*$/"), "" , $title)) . "');";
    return "<img src='$src' title='$title' onClick=\"$onClick\">";
}

function table_options($tables, $table)
{
    $table_options = "<option>".html_safe(WH_Text("Tables"))."</option>";
    $table_options .= "<option>----</option>";
    foreach($tables as $v) {
        $table_options .= "<option value='" . attr_safe($v) . "'";
        if ($v == $table) {
            $table_options .= " selected";
        }
        $table_options .= ">";
        if (strlen($v) <= 2)
            $table_options .= html_safe($v) . " &mdash; ";
        $table_options .= html_safe(WH_Text($v));
        $table_options .= "</option>";
    }

    return $table_options;
}

function syntax_options($syntax)
{
    $syntax_options = "<option value='0'>" . html_safe(WH_Text("Syntax")) . "</option>";
    $syntax_options .= "<option>----</option>";
    foreach($syntax as $v)
        $syntax_options .= "<option value='" . attr_safe($v) . "'> " . html_safe($v) . " " . html_safe(WH_Text($v)) . "</option>";

    return $syntax_options;
}

function glyphs($hierobox, $wikihiero_url)
{
    // Stupid, but it works:
    return preg_replace(
        "|".WH_IMG_DIR.WH_IMG_PRE."|",
        "$wikihiero_url/".WH_IMG_DIR.WH_IMG_PRE,
        WikiHiero($hierobox, WH_MODE_HTML)
    );
}