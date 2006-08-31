<?
$relPath="./../../pinc/";
include_once($relPath.'site_vars.php');
include_once($relPath.'dp_main.inc');
include_once($relPath.'theme.inc');
include_once($relPath.'iso_639_list.inc');
include_once($relPath.'metarefresh.inc');
include_once('parse_po.inc');

theme(_("Translation Center"), "header");

if(!user_is_site_translator()) {
	echo _("You can not access this page.");
	theme("","footer");
	exit();
}

if (isset($_POST['lang']) && isset($_POST['save_po'])) {

    $lang = $_POST['lang'];
    chdir("$dyn_locales_dir/$lang/LC_MESSAGES/");
    $translation = parse_po(file("messages.po"));
    $result = mysql_query("SELECT real_name, email FROM users WHERE username = '$pguser'");
    $real_name = mysql_result($result, 0, "real_name");
    $email_addr = mysql_result($result, 0, "email");
    if($po_file = @fopen("messages_temp.po", "w")) {
    	fputs($po_file, "# ".str_replace("\n", "\n# ", stripslashes($_POST['comments']))."\n");
    	fputs($po_file, "msgid \"\"\n");
    	fputs($po_file, "msgstr \"\"\n");
    	fputs($po_file, "\"Project-Id-Version: 1.0\\n\"\n");
    	fputs($po_file, "\"POT-Creation-Date: ".date("Y-m-j h:iO")."\\n\"\n");
    	fputs($po_file, "\"PO-Revision-Date: ".date("Y-m-j h:iO")."\\n\"\n");
    	fputs($po_file, "\"Last-Translator: $real_name <$email_addr>\\n\"\n");
    	fputs($po_file, "\"Language-Team: ".$iso_639[$lang]." <$email_addr>\\n\"\n");
    	fputs($po_file, "\"MIME-Version: 1.0\\n\"\n");
    	fputs($po_file, "\"Content-Type: text/plain; charset=$charset\\n\"\n");
    	fputs($po_file, "\"Content-Transfer-Encoding: 8bit\\n\"\n\n");

    	$i = 0;
    	while ($i <= $_POST['numofTranslations']) {
            if($_POST['location_'.$i]) {
                $translation_location = unserialize(base64_decode($_POST['location_'.$i]));
                $translation_msgid = unserialize(base64_decode($_POST['msgid_'.$i]));
                $translation_msgstr = preg_replace(
			'/(?<!\\\\)"/', //unescaped quotes
			'\"',
			stripslashes(
				preg_replace(
					"/(\r\n|\r|\n)/",
					"\"\n\"",
					$_POST['msgstr_'.$i]
				)
			)
		);
            } else {
                $translation_location = $translation['location'][$i];
                $translation_msgid = $translation['msgid'][$i];
                $translation_msgstr = $translation['msgstr'][$i];
            }
 
    	    fputs($po_file, $translation_location);
    	    fputs($po_file, "msgid \"$translation_msgid\"\n");
    	    fputs($po_file, "msgstr \"$translation_msgstr\"\n\n");
    	    $i++;
    	}
    	fclose($po_file);

    	if(@copy("messages.po","messages_".dechex(filemtime("messages.po")).".po")) {
    		if(@rename("messages_temp.po","messages.po")) {

    	                exec("msgfmt messages.po -o messages.mo 2>&1",$exec_out,$ret_var);

    			if($ret_var) {
    				echo "<center>"._("Translation <b>not</b> completed!")."</center><br>"._("Following error is given:")."<br><br>";
    	                        echo "<pre>";
                    	        foreach($exec_out as $v)
                                    	echo $v."\n";
    	                        echo "</pre><br>";
    	                } else {
                    	        echo "<center><b><i><font size='+2'>"._("Translation Completed!")."</font></i></b><br><br>";
    	                }
    		
    		} else {
    			echo "<center>"._("Translation <b>not</b> completed!")."</center><br>"._("Could not copy your changes to the working .po file. Your changes are saved in a temporary file.")."<br><br>";
    		}
    	} else {
    		echo "<center>"._("Translation <b>not</b> completed!")."
</center><br>"._("Could not backup existing .po file. Your changes are saved in a temporary file.")."<br><br>";
    	}

    } else {
    	echo "<center>"._("Translation <b>not</b> completed!")."</center><br>"._("Could not open .po file for writing. If you don't save your changes yourself they will be lost!!")."<br><br>";
    }
    echo "<a href='$code_url/locale/translators/'>"._("Please click here to return to the Translation Center.")."</a></center>";
}

if (isset($_POST['lang']) && isset($_POST['rebuild_strings'])) {

    $lang = $_POST['lang'];

    // This can be removed when UTF-8 is enabled throughout the site.
    // It makes sure that the .po file is UTF-8
    exec("iconv -f iso-8859-1 -t utf8 < $dyn_locales_dir/$lang/LC_MESSAGES/messages.po > $dyn_locales_dir/$lang/LC_MESSAGES/temp.po");

//    $translation = parse_po(file("$dyn_locales_dir/$lang/LC_MESSAGES/temp.po"));

    chdir($code_dir);
    exec("$xgettext_executable -j `find -name \"*.php\" -o -name \"*.inc\"` -p $dyn_locales_dir/$lang/LC_MESSAGES/ --keyword=_ -C -L PHP 2>&1",$exec_out,$ret_var);

    if($ret_var) {
        echo "<center>"._("Strings <b>not</b> rebuilt!")."</center><br>"._("Following error is given:")."<br><br>";
        echo "<pre>";
        foreach($exec_out as $v)
            echo $v."\n";
        echo "</pre><br>";
    } else {
/*    	$i=4;
    	$lines = file("$dyn_locales_dir/$lang/LC_MESSAGES/temp.po");
    	$po_file = fopen("$dyn_locales_dir/$lang/LC_MESSAGES/temp.po", "w");
    	fputs($po_file, "# ".str_replace("\n", "\n# ", $_POST['comments']?$_POST['comments']:$translation['comments'])."\n");
    	while ($i < count($lines)) {
    		fputs($po_file, $lines[$i]);
    		$i++;
    	}
    	fclose($po_file);*/

    	echo "<center><b><i><font size='+2'>"._("Strings Rebuilt!")."</font></i></b><br><br>";
    }
    echo "<a href='index.php?func=translate&amp;lang=$lang'>"._("Please click here to return to translate the ").$iso_639[$lang]." "._("language file").".</a>";
}

theme('','footer');
?>
