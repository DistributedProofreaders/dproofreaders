<?php
$relPath="./../../pinc/";
include_once($relPath.'site_vars.php');
include_once($relPath.'dp_main.inc');
include_once($relPath.'theme.inc');
include_once($relPath.'iso_639_list.inc');
include_once($relPath.'metarefresh.inc');
include_once('parse_po.inc');

$no_stats=1;
theme(_("Translation Center"), "header");

if (isset($_GET['func'])) { $func = $_GET['func']; } else { $func = ""; }

if (empty($_REQUEST['lang']) && empty($func)) {
    echo "<center><b><i><font size='+2'>"._("Translation Center")."</font></i></b></center><br>";
    if(!user_is_site_translator())
        echo _("<em>You are not an appointed translator and, even though you can view the translation interface, you can not save your translation or add a new language.</em>")."<br><br>";
    echo _("We currently have the following languages translated or in the process of translation. These languages are based upon the ISO-639 list and if you do not see a language you would like to translate available please click <a href='new_lang.php?func=newlang'>here</a>.");
    echo " "._("For documentation, refer to the <a href='../faq/translate.php'>Site translation</a> guideline.");
    echo "<br><br><center>"._("Please click the <i>Translate</i> link next to the language you would like to translate to begin:")."</center><br><br>";

    $dir = opendir($dyn_locales_dir);
    echo "<table border='0' cellspacing='3' cellpadding='0' width='100%'><ul>\n";
     while (false !== ($file = readdir($dir))) {
         if ($file != "." && $file != ".." && $file != "CVS" && $file != "translators") {
             echo "<tr><td width='50%'><li>".$iso_639[$file]."</li></td><td width='50%'>[ <a href='index.php?func=translate&amp;lang=$file'>"._("Translate")."</a> ]</td></tr>\n";
         }
     }
     echo "</ul></table>\n";
}

if (!empty($_GET['lang']) && $func == "translate") {
    $lang = $_GET['lang'];
    $location = @$_GET['location'];
    $translation = parse_po("$dyn_locales_dir/$lang/LC_MESSAGES/messages.po");

/* Structure of $translation array:
$translation=array(
    'file_comments'=>"Comments from the top of the PO file",
    'comments'=>array(
        0=>"comment 1",
        1=>"comment 2",
        ...
    ),
    'location'=>array(
        0=>"#: default.php:22",
        1=>"#: default.php:22",
        ...
    ),
    'msgid'=>array(
        0=>" active users out of ",
        1=>" total users in the past twenty-four hours.",
        ...
    ),
    'msgstr'=>array(
        0=>" utenti attivi su ",
        1=>" utenti totali nelle ultime 24 ore.",
        ...
    );
*/

    if (!isset($translation['location']))
    {
        echo "\n<p>"._("Something is wrong: I cannot find any translatable strings in the translation file. Perhaps the file is corrupt?");
    }
    else if(!$location)
    {
        $locations=array_flip(
            preg_replace(
                        array("/^[^\s]+\s+/","/:[0-9]+.*/s"),
                        "",
                        $translation['location']
            )
        );

        echo "<table border='0' cellspacing='3' cellpadding='' width='100%'><ul>\n";
        echo "<tr><td width='50%'><li>"._("All")."</li></td><td width='50%'>[ <a href='index.php?func=translate&amp;lang=$lang&amp;location=all'>"._("Translate")."</a> ]</td></tr>\n";
        foreach($locations as $k=>$v)
            echo "<tr><td width='50%'><li>$k</li></td><td width='50%'>[ <a href='index.php?func=translate&amp;lang=$lang&amp;location=$k'>"._("Translate")."</a> ] (<a href='$code_url/".loc_eq($k)."' target='_new'>"._("Location")."</a>)</td></tr>\n";
                echo "</ul><tr><td width='50%'><form action='save_po.php' method='post'>";
                echo "<input type='hidden' name='lang' value='$lang'>";
        echo "<center><input type='submit' name='rebuild_strings' value='"._("Rebuild String List")."'></center><br></form></td></tr></table>\n";
    }
    else
    {
        $numOfTranslations = count($translation['location']);

        echo "<form action='save_po.php' method='post'>";
        echo "<input type='hidden' name='numofTranslations' value='".($numOfTranslations-1)."'>";
        echo "<input type='hidden' name='lang' value='$lang'>";
        echo "<input type='hidden' name='location' value='$location'>";

        echo "<h2>"._("File comments")."</h2>";
        echo "<textarea name='file_comments' rows='5' style='width: 100%';>".trim($translation['file_comments'])."</textarea>\n";

        echo "<hr>";
        echo "<h2>"._("Translatable strings")."</h2>";
        if($location!="all")
            echo "<p>" . sprintf(_("<b>Location for all strings:</b> <a href='%1\$s' target='_new'>%2\$s</a>"),"$code_url/".loc_eq($location),loc_eq($location)) . "</p>";

        for($i = 0; $i < $numOfTranslations; $i++)
        {
            if($location=="all" || strstr($translation['location'][$i],$location)!==FALSE) {
                if($location=="all") $loc = trim(substr($translation['location'][$i], 2, strpos(substr($translation['location'][$i], 2), ":"))); else $loc = $location;
                echo "<p>";
                echo sprintf(_("<b>String:</b> %s"),visible_invisibles(htmlspecialchars($translation['msgid'][$i]))) . "<br>";
                if($location=="all")
                    echo sprintf(_("<b>Location:</b> <a href='%1\$s' target='_new'>%2\$s</a>"),"$code_url/".loc_eq($loc),loc_eq($loc)) . "<br>";
                if(isset($translation['comments'][$i]))
                    echo _("<b>Comments:</b>") . "<div style='margin-left: 2em;'>" . nl2br(htmlspecialchars($translation['comments'][$i])) . "</div>";

                echo "<input type='hidden' name='location_".$i."' value='".base64_encode(serialize($translation['location'][$i]))."'>\n";
                echo "<input type='hidden' name='msgid_".$i."' value='".base64_encode(serialize($translation['msgid'][$i]))."'>\n";
                echo "<textarea name='msgstr_".$i."' rows='3' style='width: 100%'>".htmlspecialchars($translation['msgstr'][$i])."</textarea>";
                echo "</p>";
            }
        }

        echo "<center><input type='submit' name='save_po' value='"._("Save and Compile")."'></center>";
        echo "</form>";
        }
    }

theme('','footer');

function loc_eq($loc) {
        $a = array(
                "pinc/theme.inc" => "default.php",
                "pinc/pinc/simple_proof_text.inc" => "tools/proofers/round.php",
        "pinc/prefs_options.inc" => "userprefs.php",
        "pinc/showavailablebooks.inc" => "tools/proofers/round.php",
        "pinc/showstartexts.inc" => "default.php",
//        ...
        );

    if(@$a[$loc]) return $a[$loc]; else return $loc;
}

function visible_invisibles($str)
{
    return preg_replace(
        array(
            "/^ /",
            "/ $/",
        ),
        "<img src='space.gif' valign='bottom'>",
        $str
    );
}

// vim: sw=4 ts=4 expandtab
