<?php
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

$relPath="./../../../pinc/";
include_once($relPath.'base.inc');
include_once($relPath.'slim_header.inc');
$lang=short_lang_code();

include "$wikihiero_dir/wh_language.php";
include "$wikihiero_dir/wikihiero.php";

require_login();

$wh_img_url="$wikihiero_url/".WH_IMG_DIR;

if(array_key_exists("table", $_GET))
    $table = $_GET["table"];
else
    $table = "";

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

slim_header("$table - ".WH_Text($table));
?>
<script>
function add(glyph) {
    text=window.parent.hierodisplay.document.hieroform.hierobox.value;
    lastc=text.charCodeAt(text.length-1);
    if((lastc>=48&&lastc<=57)||(lastc>=65 && lastc<=90)||(lastc>=97&&lastc<=122))
        sep='-';
    else
        sep='';
    window.parent.hierodisplay.document.hieroform.hierobox.value+=sep+glyph;
}
</script>
<?php
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
            if(($code[0] == $table) && ctype_digit($code[1]))
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
    return "<img src=\"$src\" title=\"$title\" onClick=\"add('".preg_replace(array("/^.*[[]/","/[]].*$/"),"",$title)."');\">\n";
}
