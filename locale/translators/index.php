<?php
$relPath="./../../pinc/";
include_once($relPath.'base.inc');
include_once($relPath.'theme.inc');
include_once($relPath.'misc.inc');
include_once($relPath.'user_is.inc');
include_once($relPath.'metarefresh.inc');
// include() not include_once() as we need the iso_639 array in the global
// namespace regardless of where it was include_once()d before.
include($relPath.'iso_639_list.inc');

require_login();
undo_all_magic_quotes();

$translate_url = "$code_url/locale/translators/index.php";

$may_edit = user_is_site_translator();

if ($may_edit)
    $allowed_functions = array(
        'xgettext', 'edit', 'merge', 'upload', 'download', 'view', 'newtranslation', 'newtranslation2');
else
    $allowed_functions = array('download', 'view');

if (user_is_a_sitemanager())
{
    $allowed_functions[] = 'delete';
}

$func = get_enumerated_param($_GET, 'func', null, $allowed_functions, true);

if ($func == "download" || $func == "view")
{
    // View or download temporary files. No theme needed.

    if (@$_REQUEST['locale'] == "template")
    {
        $filename = "$dyn_locales_dir/messages.pot";
    }
    else
    {
        $locale = validate_locale($_REQUEST['locale']);
        $filename = "$dyn_locales_dir/$locale/LC_MESSAGES/messages.po";
    }

    if (file_exists($filename))
    {
        // TODO, in what charset are these files?
        header("Content-type: text/plain; charset=$charset");

        if ($func == "download") {
            $output_fname =
                ($locale == 'template') ? "messages.pot" : "${locale}_messages.po";
            header("Content-Disposition: attachment; filename=\"$output_fname\"");
            header("Content-Length: ".filesize($filename));
        }

        // Send the template file as a plain/text document. No encoding is necessary.
        readfile($filename);
    }
    else
    {
        $no_stats=1;
        theme(_("Translation Center"), "header");
        echo "<p>" . _("The requested file does not exist.") . "</p>";
    }
    exit();
}

$no_stats=1;
theme(_("Translation Center"), "header");

// Main Translation Center page
if (empty($func))
{
    main_form();
}
// First new language page: display a list of languages to create
else if ($func == "newtranslation")
{
    $existing_langs = get_existing_langs();

    echo "<p><a href='$translate_url'>"
        . _("Back to the Translation Center") . "</a></p>\n";

    echo "<table style='border: 0;'><ul>\n";
    foreach ($iso_639 as $short_lang => $full_lang) {
        if (!in_array($short_lang, $existing_langs)) {
            echo "<tr><td width='50%' align='left'><li>$full_lang</li></td><td width='50%' align='left'>[ <a href='$translate_url?func=newtranslation2&amp;locale=$short_lang'>" . _("Create Translation File") . "</a> ]</td></tr>\n";
        }
    }
    echo "</ul></table>";
}
// Second new language page: create the new language files and redirect to the edit page
else if ($func == "newtranslation2")
{
    $locale = validate_locale($_REQUEST['locale'], /*check_dir_exists*/ False);

    if (!file_exists("$dyn_locales_dir/$locale"))
    {
        mkdir("$dyn_locales_dir/$locale", 0755);
        mkdir("$dyn_locales_dir/$locale/LC_MESSAGES/", 0755);

        if (file_exists("$dyn_locales_dir/messages.pot"))
        {
            copy("$dyn_locales_dir/messages.pot", "$dyn_locales_dir/$locale/LC_MESSAGES/messages.po");
        }

        metarefresh(0, "$translate_url?func=edit&amp;locale=$locale", "", "");
    }
    else
    {
        echo "<p>" . _("Invalid parameter.") ."</p>";

        echo "<p><a href='$translate_url'>"
            . _("Back to the Translation Center") . "</a></p>";
    }
}
// Perform language delete and redirect to Translation Center
else if ($func == "delete")
{
    $locale = validate_locale($_REQUEST['locale']);
    assert(is_dir("$dyn_locales_dir/$locale"));
    exec("rm -r $dyn_locales_dir/$locale");

    echo "<p>" . sprintf(_("Language %s deleted."), $locale) . "</p>";

    echo "<p><a href='$translate_url'>"
        . _("Back to the Translation Center") . "</a></p>";
}
// Scan PHP source code for translatable strings, create template file and report results.
else if ($func == "xgettext")
{
    if(chdir($code_dir) == FALSE)
        die ("Unable to change to requested directory.");

    exec("$xgettext_executable `find -name \"*.php\" -o -name \"*.inc\"` -p $dyn_locales_dir/ -o messages.pot --keyword=_ -L PHP --add-comments=TRANSLATORS 2>&1", $exec_out, $ret_var);
    if ($ret_var)
    {
        echo "<center>" . _("Strings <b>not</b> rebuilt!") . "</center><br>"
            . _("This is the <code>xgettext</code> output:") . "<br><br>";
        echo "<pre>";
        foreach($exec_out as $v)
            echo htmlspecialchars($v)."\n";
        echo "</pre><br>";
    }
    else
    {
        echo "<p>" . _("<code>xgettext</code> ran successfully.") ."</p>";
    }

    echo "<p><a href='$translate_url'>"
        . _("Back to the Translation Center") . "</a></p>";
}
// Perform the upload and compilation of a translation file
else if ($func == "upload")
{
    $locale = validate_locale($_REQUEST['locale']);
    do_upload($locale);
    echo "<p><a href='$translate_url?func=edit&amp;locale=$locale'>"
        . sprintf(_("Back to Edit Language %s"), $locale) . "</a></p>";
}
// Perform the merging of a translation file with a template
else if ($func == "merge")
{
    $locale = validate_locale($_REQUEST['locale']);
    $fuzzy = @$_REQUEST['fuzzy'];
    do_merge($locale, $fuzzy);
    echo "<p><a href='$translate_url?func=edit&amp;locale=$locale'>"
        . sprintf(_("Back to Edit Language %s"), $locale) . "</a></p>";
}
else if ($func == "edit")
{
    $locale = validate_locale($_REQUEST['locale']);
    edit_form($locale);
}


function main_form()
{
    // display the list of languages with links to the downloadable PO files

    global $dyn_locales_dir, $iso_639, $translate_url, $may_edit;

    echo "<h1>"._("Translation Center")."</h1>";
    if (!$may_edit)
        echo "<p><em>" . _("You are not a registered translator. You will be able to view the translation interface, but you cannot save a translation or add a new language.") . "</em></p>\n";

    echo "<p>" . _("The following languages are translated or in the process of being translated.");

    if ($may_edit)
        echo "<br> " . sprintf(_("If the language you would like to provide translations for does not appear below, you can <a href='%s'>create a new translation</a>."), "$translate_url?func=newtranslation");

    echo "</p>\n";

    // PO template file
    $pot_filename = "$dyn_locales_dir/messages.pot";
    if (file_exists($pot_filename))
    {
        echo "<p>" . _("POT template file:") . " ";
        echo "<a href='$translate_url?func=view&amp;locale=template'>" . _("view")
           . "</a> | <a href='$translate_url?func=download&amp;locale=template'>"
           . _("download") . "</a> ";
        echo " (" . _("Last modified:") . " "
                . date ("F d Y H:i:s", filemtime($pot_filename)) . ")</p>";
    }
    else
    {
        echo "<p>" . _("No POT template file has been generated.") . "</p>";
    }

    if ($may_edit)
    {
        echo "<form action='$translate_url?func=xgettext' method='POST'>";
        echo "<input type='submit' value='" . attr_safe(_("Regenerate template file")) . "'> ";
        echo _("Run <code>xgettext</code> to generate a fresh template file.");
        echo "</form>\n";
    }

    // PO files for currently existing languages
    echo "<table style='border: 0;'>\n";
    echo "<tr><th>" . _("Language code:") . "</th><th>" . _("Language name:")
        . "</th><th>" . _("PO file:");
    if ($may_edit)
        echo "</th><th>" . _("Edit this language");
    echo "</th></tr>\n";

    $existing_langs = get_existing_langs();
    foreach ($existing_langs as $file)
    {
        $po_filename = "$dyn_locales_dir/$file/LC_MESSAGES/messages.po";
        if (file_exists($po_filename))
        {
            echo "<tr><td>$file</td><td>" . $iso_639[$file] . "</td><td>"
                . "<a href='$translate_url?func=view&amp;locale=$file'>"
                . _("view") . "</a> | <a href='$translate_url?func=download&amp;locale=$file'>"
                . _("download") . "</a> (" . _("Last modified:") . " "
                . date ("F d Y H:i:s", filemtime($po_filename)) . ")</td>";
        }
        else
        {
            echo "<tr><td>$file</td><td>" . $iso_639[$file] . "</td><td>"
                . _("No PO file.") . "</td>";
        }

        if ($may_edit)
            echo "<td><a href='$translate_url?func=edit&amp;locale=$file'>"
                . _("Edit this language") . "</td>";
        echo "</tr>\n";
    }
    echo "</table>\n";
}


function edit_form($locale)
{
    global $dyn_locales_dir, $iso_639, $translate_url, $charset;

    echo "<p><a href='$translate_url'>"
        . _("Back to the Translation Center") . "</a></p>";

    echo "<h1>" . sprintf(_("Editing Language %s"), $locale) . "</h1>\n";

    echo "<p><b>" . _("Language code:") . "</b> $locale</p>\n";
    echo "<p><b>" . _("Language name:") . "</b> " . $iso_639[$locale] . "</p>\n";

    $po_filename = "$dyn_locales_dir/$locale/LC_MESSAGES/messages.po";
    if (file_exists($po_filename))
    {
        echo "<p><b>" . _("PO file:") . "</b> ";
        echo "<a href='$translate_url?func=view&amp;locale=$locale'>"
            . _("view") . "</a> | <a href='$translate_url?func=download&amp;locale=$locale'>"
            . _("download") . "</a> (" . _("Last modified:") . " "
            . date ("F d Y H:i:s", filemtime($po_filename)) . ")</p>";

        $pot_filename = "$dyn_locales_dir/messages.pot";
        if (file_exists($pot_filename) && filemtime($pot_filename) > filemtime($po_filename))
        {
            echo "<p>" . _("The current template is more recent than the PO file. You should merge the PO file with the current template.") . "</p>";
        }

        echo "<form action='$translate_url?func=merge' method='POST'>";
        echo "<input type='hidden' name='locale' value='$locale'>";
        echo "<input type='submit' value='"
            . attr_safe(_("Merge current PO file with the current template")) . "'> ";
        echo _("Run <code>msgmerge</code> to update the current PO file against the current template.") . "<br>\n";
        echo "<input type='checkbox' name='fuzzy'> "
            . _("Do fuzzy matching when an exact match is not found (can be much slower)") . "<br>" ;
        echo "</form><br><br>\n";
    }
    else
    {
        echo "<p>" . _("No PO file.") . "</p>\n";
    }

    echo "<form action='$translate_url?func=upload' method='POST' enctype='multipart/form-data'>\n";
    echo "<input type='hidden' name='locale' value='$locale'>";
    echo _("Select a PO file to upload:") . " ";
    echo "<input type='hidden' name='MAX_FILE_SIZE' value='5000000'>";
    echo "<input type='file' name='userfile'><br>\n";
    echo "<input type='submit' value='"
        . attr_safe(_("Upload file")) . "'> ";
    echo _("This replaces the current PO file with the file you provide, and installs the translation for use by the site.");
    echo "</form>\n";

    if (user_is_a_sitemanager())
    {
        echo "<br><br>";
        echo "<form action='$translate_url?func=delete' method='POST'>";
        echo "<input type='hidden' name='locale' value='$locale'>";
        $confirm = javascript_safe(
            _("Are you sure you want to delete this language and its translation file?"),
            $charset);
         echo "<input type='submit' onClick='return confirm(\"$confirm\");' value='"
            . attr_safe(_("Delete this language")) . "'> ";
        echo _("Delete the language directory, PO file and MO file.") . "<br>\n";
        echo "</form>\n";
    }
}

function do_upload($locale)
{
    global $dyn_locales_dir;

    if(chdir("$dyn_locales_dir/$locale/LC_MESSAGES/") == FALSE)
        die ("Unable to change to messages directory.");

    if (file_exists("messages.po"))
        $save = "messages_".strftime("%Y%m%d%H%M%S",filemtime("messages.po")).".po";

    assert( isset( $_FILES['userfile']));
    $upload_info = $_FILES['userfile'];
    $error_code = $upload_info['error'];
    // If the upload failed, print error and go no further.
    if ($error_code != UPLOAD_ERR_OK)
    {
        echo sprintf( _("Error code = %d."), $error_code )
            . "<br>\n"
            . "(" . get_upload_err_msg($error_code) . ")<br>";
        return;
    }

    // If a translation already exists, try to make a backup and give up if we can't
    if (file_exists("messages.po") && !@copy("messages.po", $save))
    {
        echo "<p>" . _("Could not save a copy of the previous file.")
	. " " . _("New file was not uploaded.") . "</p>";
        return;
    }

    // Try to move the upload file into place.
    if (!move_uploaded_file($_FILES['userfile']['tmp_name'], "messages.po"))
    {
        echo "<p>" . _("The file upload failed.") . "</p>";
        return;
    }

    // Compile the translation, and show warnings if it fails
    exec("msgfmt messages.po -o messages.mo 2>&1", $exec_out, $ret_var);
    if ($ret_var)
    {
        echo "<p>" . _("Uploaded file contains the following errors:") . "</p>\n";
        echo "<pre>";
        foreach($exec_out as $v)
            echo htmlspecialchars($v)."\n";
        echo "</pre><br>";

        if (file_exists("messages.po"))
        {
            echo "<p>" . _("(Reverting to previous file.)") . "</p>\n";
            @copy($save, "messages.po");
        }
    }
    else
    {
        echo "<p>" . _("File successfully uploaded and compiled.") . "</p>";
    }
}

function do_merge($locale, $fuzzy)
{
    global $dyn_locales_dir;

    if ($fuzzy == 'on')
        $fuzzy_option = "";
    else
        $fuzzy_option = "-N";

    if(chdir("$dyn_locales_dir/$locale/LC_MESSAGES/") == FALSE)
        die ("Unable to change to messages directory.");

    // Try to back up the existing translation
    assert(file_exists("messages.po"));
    $save = "messages_".strftime("%Y%m%d%H%M%S",filemtime("messages.po")).".po";
    if (!@copy("messages.po", $save))
    {
        echo "<p>" . _("Could not save a copy of the previous file.")
	. " " . _("File was not updated.") . "</p>";
        return;
    }

    // Try to perform the merge, show warnings if it fails
    exec("msgmerge $fuzzy_option -D . -D $dyn_locales_dir $save messages.pot -o temp.po 2>&1", $exec_out, $ret_var);
    if ($ret_var)
    {
        echo "<p>" . _("<code>msgmerge</code> reports the following errors:") . "</p>\n";
        echo "<pre>";
        foreach($exec_out as $v)
            echo htmlspecialchars($v)."\n";
        echo "</pre><br>";
        return;
    }
    
    if (@rename("temp.po", "messages.po"))
        echo "<p>" . _("File successfully updated. You may now download it.") . "</p>";
    else
        echo "<p>" . _("Could not rename the final file.") . "</p>";
}

function validate_locale($locale, $check_dir_exists = True)
{
    global $dyn_locales_dir;

    if (!is_string($locale) ||
        !in_array($locale, get_installed_system_locales()) ||
        ($check_dir_exists && !is_dir("$dyn_locales_dir/$locale")))
    {
        die (sprintf(_("locale %s is not valid"), $locale));
    }

    return $locale;
}

function get_existing_langs()
// Get existing translations with recognized language names
{
    global $dyn_locales_dir, $iso_639;

    $dir = opendir($dyn_locales_dir);
    $existing_langs = array();
    while (false != ($file = readdir($dir))) {
        if ($file == "." || $file == ".." || $file == "CVS" || $file == "translators") continue;
        if (array_key_exists($file, $iso_639)) {
            $existing_langs[] = $file;
        }
    }
    return $existing_langs;
}

// vim: sw=4 ts=4 expandtab
