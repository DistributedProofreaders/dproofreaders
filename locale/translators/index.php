<?php
$relPath="./../../pinc/";
include_once($relPath.'base.inc');
include_once($relPath.'theme.inc');
include_once($relPath.'misc.inc');
include_once($relPath.'user_is.inc');
include_once($relPath.'metarefresh.inc');
include_once($relPath.'POFile.inc');
include_once($relPath.'faq.inc');

require_login();

$translate_url = "$code_url/locale/translators/index.php";

$may_manage = user_is_site_translator();

if ($may_manage)
    $allowed_functions = array(
        'manage', 'merge', 'upload', 'download', 'view');
else
    $allowed_functions = array('download', 'view');

if (user_is_a_sitemanager())
{
    $allowed_functions[] = 'xgettext';
    $allowed_functions[] = 'newtranslation';
    $allowed_functions[] = 'newtranslation2';
    $allowed_functions[] = 'delete';
    $allowed_functions[] = 'changeenable';
}

$func = get_enumerated_param($_GET, 'func', null, $allowed_functions, true);

if ($func == "download" || $func == "view")
{
    // View or download temporary files. No theme needed.

    if (@$_REQUEST['locale'] == "template")
    {
        $locale = "template";
        $filename = "$dyn_locales_dir/messages.pot";
    }
    else
    {
        $locale = validate_locale($_REQUEST['locale']);
        $filename = "$dyn_locales_dir/$locale/LC_MESSAGES/messages.po";
    }

    $po_file = new POFile($filename);
    if ($po_file->exists)
    {
        // Set the header content-type based on the values specified in the
        // file if set. If it isn't set, we don't know what the encoding
        // is with any sort of confidence.
        $po_content_type = $po_file->content_type;
        if($po_content_type && strpos($po_content_type, "=CHARSET") === FALSE)
            header($po_content_type);

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
        output_header(_("Translation Center"), false);
        echo "<p>" . _("The requested file does not exist.") . "</p>";
    }
    exit();
}

output_header(_("Translation Center"), false);

// Main Translation Center page
if (empty($func))
{
    main_form();
}
// First new language page: display a list of languages to create
else if ($func == "newtranslation")
{
    $existing_translations = get_installed_locale_translations();
    $system_locales = get_installed_system_locales();
    sort($system_locales);

    echo "<p><a href='$translate_url'>"
        . _("Back to the Translation Center") . "</a></p>\n";

    echo "<p>" . _("You can only create translations for locales installed on this system. If you don't see the language you want to create, contact the system administrator to have that locale installed on the system first.") . "</p>";

    echo "<table style='border: 0;'>\n";
    foreach ($system_locales as $locale) {
        if($locale == "C" or $locale =="POSIX")
            continue;

        $language_name = eng_name(short_lang_code($locale));
        echo "<tr>";
        echo "<td>$locale</td>";
        echo "<td>$language_name</td>";
        if (in_array($locale, $existing_translations))
            echo "<td>" . _("Translation exists") . "</td>";
        else
            echo "<td><a href='$translate_url?func=newtranslation2&amp;locale=$locale'>" . _("Create Translation File") . "</a></td>";
        echo "</tr>\n";
    }
    echo "</table>";
}
// Second new language page: create the new language files and redirect to the manage page
else if ($func == "newtranslation2")
{
    $locale = validate_locale($_REQUEST['locale'], /*check_dir_exists*/ False);

    if (file_exists("$dyn_locales_dir/$locale"))
    {
        echo "<p>" . sprintf(_("Translation directory for %s already exists."), $locale) ."</p>";

        echo "<p><a href='$translate_url'>"
            . _("Back to the Translation Center") . "</a></p>";
    }

    if (!file_exists("$dyn_locales_dir/messages.pot"))
    {
        echo "<p>" . _("No POT available to initialize translation.") ."</p>";

        echo "<p><a href='$translate_url'>"
            . _("Back to the Translation Center") . "</a></p>";
    }

    mkdir("$dyn_locales_dir/$locale", 0755);
    mkdir("$dyn_locales_dir/$locale/LC_MESSAGES/", 0755);

    $po_filename = "$dyn_locales_dir/$locale/LC_MESSAGES/messages.po";
    $po_file = new POFile($po_filename);
    try
    {
        $po_file->create_from_template("$dyn_locales_dir/messages.pot", $locale);

        metarefresh(0, "$translate_url?func=manage&amp;locale=$locale", "", "");
    }
    catch(Exception $exception)
    {
        echo "<p>" . _("An error occurred during translation initialization.") ."</p>";
        echo "<pre>" . $exception->getMessage() . "</pre>";

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

    echo "<p>" . sprintf(_("Locale %s deleted."), $locale) . "</p>";

    echo "<p><a href='$translate_url'>"
        . _("Back to the Translation Center") . "</a></p>";
}
// Update locale state and redirect to the manage page
else if ($func == "changeenable")
{
    $locale = validate_locale($_REQUEST['locale']);

    $enable = isset($_REQUEST['enable_locale']);
    set_locale_translation_enabled($locale, $enable);

    if($enable)
        $enable_string = _("Enabled");
    else
        $enable_string = _("Disabled");

    echo "<p>" . sprintf(_('Locale %1$s has been: %2$s.'), $locale, $enable_string) . "</p>";

    echo "<p><a href='$translate_url'>"
        . _("Back to the Translation Center") . "</a></p>";
}
// Scan PHP source code for translatable strings, create template file and report results.
else if ($func == "xgettext")
{
    if(chdir($code_dir) == FALSE)
        die ("Unable to change to requested directory.");

    $xgettext_arguments = [
        $xgettext_executable,
        "--output-dir=$dyn_locales_dir/",
        "--output=messages.pot",
        "--language=PHP",
        "--keyword=_",
        "--keyword='pgettext:1c,2'",
        "--add-comments=TRANSLATORS",
        "--from-code=UTF-8",
        "--sort-by-file",
        "`find -name '*.php' -o -name '*.inc'`",
        "2>&1",
    ];
    exec(implode(" ", $xgettext_arguments), $exec_out, $ret_var);
    if ($ret_var)
    {
        echo "<p class='center-align'>" . _("Strings <b>not</b> rebuilt!") . "<br>"
            . _("This is the <code>xgettext</code> output:") . "</p>";
        echo "<pre>";
        foreach($exec_out as $v)
            echo html_safe($v)."\n";
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
    echo "<p><a href='$translate_url?func=manage&amp;locale=$locale'>"
        . sprintf(_("Back to manage locale %s"), $locale) . "</a></p>";
}
// Perform the merging of a translation file with a template
else if ($func == "merge")
{
    $locale = validate_locale($_REQUEST['locale']);
    $fuzzy = @$_REQUEST['fuzzy'];
    do_merge($locale, $fuzzy);
    echo "<p><a href='$translate_url?func=manage&amp;locale=$locale'>"
        . sprintf(_("Back to manage locale %s"), $locale) . "</a></p>";
}
else if ($func == "manage")
{
    $locale = validate_locale($_REQUEST['locale']);
    manage_form($locale);
}


function main_form()
{
    // display the list of languages with links to the downloadable PO files

    global $dyn_locales_dir, $translate_url, $may_manage, $allowed_functions;

    $pot_filename = "$dyn_locales_dir/messages.pot";

    echo "<h1>"._("Translation Center")."</h1>";
    echo "<p>" . sprintf(_("See the <a href='%s'>Translation FAQ</a> for more information on how to use this interface to manage translations."), get_faq_url("translate.php")) . "</p>";

    if (!$may_manage)
        echo "<p><em>" . _("You are not a registered translator. You will be able to view the translation interface, but you cannot save a translation or add a new language.") . "</em></p>\n";

    echo "<h2>" . _("Translations") . "</h2>";
    echo "<p>" . _("The following languages are translated or in the process of being translated.") . "</p>";

    if (in_array("newtranslation", $allowed_functions))
    {
        if(is_file($pot_filename))
        {
            echo "<p> " . sprintf(_("<a href='%s'>Create a new translation.</a>"), "$translate_url?func=newtranslation") . "</p>";
        }
        else
        {
            echo "<p>" . _("To create a new translation, first generate a new POT file.") . "</p>";
        }
    }

    // PO files for currently existing languages
    echo "<table class='translation'>\n";
    echo "<tr>";
    echo "<th>" . _("Language") . "</th>";
    echo "<th>" . _("Locale") . "</th>";
    echo "<th>" . _("Status") . "</th>";
    echo "<th>" . _("PO file last modified") . "</th>";
    echo "<th>" . _("Translation progress") . "</th>";
    echo "<th>" . _("Actions") . "</th>";
    echo "<th></th>";
    echo "</tr>\n";

    $locale_translations = get_installed_locale_translations();
    $system_locales = get_installed_system_locales();
    foreach ($locale_translations as $locale)
    {
        $language_name = eng_name(short_lang_code($locale));
        $po_filename = "$dyn_locales_dir/$locale/LC_MESSAGES/messages.po";
        $translation_enabled = is_locale_translation_enabled($locale);
        echo "<tr>";
        echo "<td>$language_name</td>";
        echo "<td>$locale</td>";
        if($translation_enabled)
            echo "<td>" . _("Enabled") . "</td>";
        else
            echo "<td>" . _("Disabled") . "</td>";
        $po_file = new POFile($po_filename);
        if ($po_file->exists)
        {
            echo "<td>";
            echo date ("F d Y H:i:s", $po_file->last_modified);
            echo "</td>";
            echo "<td style='text-align: right'>";
            // TRANSLATORS: This shows the number of strings translated with a percentage
            $total_strings = $po_file->messages_count;
            $translated_strings = $po_file->messages_translated_count;
            if($total_strings)
                $percent_translated = $translated_strings/$total_strings*100;
            else
                $percent_translated = 0;
            echo sprintf(_('%1$s of %2$s translated (%3$d%%)'),
                $translated_strings, $total_strings, $percent_translated);
            echo "</td>";
        }
        else
        {
            echo "<td></td>";
            echo "<td></td>";
        }
        echo "<td>";
        $actions = array();
        if ($may_manage)
            $actions[] = "<a href='$translate_url?func=manage&amp;locale=$locale'>" . _("Manage") . "</a>";
        if ($po_file->exists)
        {
            $actions[] = "<a href='$translate_url?func=view&amp;locale=$locale'>" . _("View") . "</a>";
            $actions[] = "<a href='$translate_url?func=download&amp;locale=$locale'>" . _("Download") . "</a>";
        }
        echo implode(" | ", $actions);
        echo "</td>";

        if(!in_array($locale, $system_locales))
            echo "<td>" . _("Warning: system locale not installed") . "</td>";
        echo "</tr>\n";
    }
    echo "</table>\n";


    echo "<h2>" . _("PO Template") . "</h2>";
    $pot_file = new POFile($pot_filename);
    if ($pot_file->exists)
    {
        echo "<p>" . _("POT template file") . ": ";
        echo "<a href='$translate_url?func=view&amp;locale=template'>" . _("View")
           . "</a> | <a href='$translate_url?func=download&amp;locale=template'>"
           . _("Download") . "</a> ";
        echo " (" . _("Last modified") . ": "
                // TRANSLATORS: This is a date-formatted string
                . date ("F d Y H:i:s", $pot_file->last_modified) . ")";
        $total_strings = $pot_file->messages_count;
        $translated_strings = $pot_file->messages_translated_count;
        echo "  - " . sprintf(_("%d strings total"), $total_strings);
        echo "</p>";
    }
    else
    {
        echo "<p>" . _("No POT template file has been generated.") . "</p>";
    }

    if (in_array("xgettext", $allowed_functions))
    {
        echo "<form action='$translate_url?func=xgettext' method='POST'>";
        echo "<input type='submit' value='" . attr_safe(_("Regenerate template file")) . "'> ";
        echo _("Run <code>xgettext</code> to generate a fresh template file.");
        echo "</form>\n";
    }
}


function manage_form($locale)
{
    global $dyn_locales_dir, $translate_url, $charset;

    $system_locales = get_installed_system_locales();
    $translation_enabled = is_locale_translation_enabled($locale);

    echo "<p><a href='$translate_url'>"
        . _("Back to the Translation Center") . "</a></p>";

    echo "<h1>" . sprintf(_("Managing locale %s"), $locale) . "</h1>\n";

    echo "<p><b>" . _("Locale") . ":</b> $locale</p>\n";
    if(!in_array($locale, $system_locales))
        echo "<p><b>" . sprintf(_("Warning: While a locale translation exists for %s, a system locale does not. Without a system locale installed, gettext will not use this translation."), $locale) . "</b></p>";
    echo "<p><b>" . _("Language") . ":</b> " . eng_name($locale) . "</p>\n";
    echo "<p><b>" . _("Translation status") . ":</b> ";
    if($translation_enabled)
        echo _("Enabled");
    else
        echo _("Disabled");
    echo "</p>\n";

    $po_filename = "$dyn_locales_dir/$locale/LC_MESSAGES/messages.po";
    $po_file = new POFile($po_filename);
    if ($po_file->exists)
    {
        $total_strings = $po_file->messages_count;
        $translated_strings = $po_file->messages_translated_count;

        echo "<p><b>" . _("PO file") . ":</b> ";
        echo "<a href='$translate_url?func=view&amp;locale=$locale'>"
            . _("View") . "</a> | <a href='$translate_url?func=download&amp;locale=$locale'>"
            . _("Download") . "</a> (" . _("Last modified") . ": "
            // TRANSLATORS: this is a date-formatted string
            . date ("F d Y H:i:s", $po_file->last_modified) . ")";

        echo " - ";
        echo sprintf(_('%1$s of %2$s translated (%3$d%%)'),
            $translated_strings, $total_strings,
            $translated_strings/$total_strings*100);
        echo "</p>";

        $pot_filename = "$dyn_locales_dir/messages.pot";
        $pot_file = new POFile($pot_filename);
        if($pot_file->exists && $pot_file->last_modified > $po_file->last_modified)
        {
            echo "<p>" . _("The current template is more recent than the PO file. You should merge the PO file with the current template.") . "</p>";
        }

        echo "<form action='$translate_url?func=merge' method='POST'>";
        echo "<input type='hidden' name='locale' value='$locale'>";
        echo "<input type='submit' value='"
            . attr_safe(_("Merge current PO file with the current template")) . "'> ";
        echo _("Run <code>msgmerge</code> to update the current PO file against the current template.") . "<br>\n";
        echo "<input type='checkbox' name='fuzzy' checked> "
            . _("Do fuzzy matching when an exact match is not found (can be much slower but more accurate)") . "<br>" ;
        echo "</form><br><br>\n";
    }
    else
    {
        echo "<p>" . _("No PO file.") . "</p>\n";
    }

    echo "<form action='$translate_url?func=upload' method='POST' enctype='multipart/form-data'>\n";
    echo "<input type='hidden' name='locale' value='$locale'>";
    echo _("Select a PO file to upload:") . " ";
    echo "<input type='file' name='userfile'><br>\n";
    echo "<input type='submit' value='"
        . attr_safe(_("Upload file")) . "'> ";
    echo _("This replaces the current PO file with the file you provide, and installs the translation for use by the site.");
    echo "</form>\n";

    if (user_is_a_sitemanager())
    {
        echo "<br><br>";
        echo "<h2>" . _("Site administrator functions") . "</h2>";
        echo "<form action='$translate_url?func=changeenable' method='POST'>";
        echo "<input type='hidden' name='locale' value='$locale'>";
        if($translation_enabled)
            $checkbox_state = "checked";
        else
            $checkbox_state = "";
        echo "<input type='checkbox' name='enable_locale' value='1' $checkbox_state> ";
        echo _("Enable locale translation") . " ";
        echo "<input type='submit' value='" . attr_safe(_("Save")) . "'> ";
        echo "</form>\n";

        echo "<br><br>";
        echo "<form action='$translate_url?func=delete' method='POST'>";
        echo "<input type='hidden' name='locale' value='$locale'>";
        $confirm = javascript_safe(
            _("Are you sure you want to delete this locale and its translation file?"),
            $charset);
         echo "<input type='submit' onClick='return confirm(\"$confirm\");' value='"
            . attr_safe(_("Delete this locale")) . "'> ";
        echo _("Delete the locale directory, PO file and MO file.") . "<br>\n";
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

    $po_file = new POFile("$dyn_locales_dir/$locale/LC_MESSAGES/messages.po");
    try
    {
        $po_file->compile();

        echo "<p>" . _("File successfully uploaded and compiled.") . "</p>";
    }
    catch(Exception $exception)
    {
        echo "<p>" . _("Uploaded file contains the following errors:") . "</p>\n";
        echo "<pre>";
        foreach(explode("\n", $exception->getMessage()) as $v)
            echo html_safe($v)."\n";
        echo "</pre><br>";

        if (file_exists("messages.po"))
        {
            echo "<p>" . _("(Reverting to previous file.)") . "</p>\n";
            @copy($save, "messages.po");
        }
    }
}

function do_merge($locale, $fuzzy)
{
    global $dyn_locales_dir;

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

    $po_filename = "$dyn_locales_dir/$locale/LC_MESSAGES/messages.po";
    $pot_filename = "$dyn_locales_dir/messages.pot";
    $po_file = new POFile($po_filename);
    try
    {
        $po_file->merge_from_template($pot_filename, $fuzzy == 'on');

        echo "<p>" . _("File successfully updated. You may now download it.") . "</p>";
    }
    catch(Exception $exception)
    {
        echo "<p>" . _("<code>msgmerge</code> reports the following errors:") . "</p>\n";
        echo "<pre>";
        foreach(explode("\n", $exception->getMessage()) as $v)
            echo html_safe($v)."\n";
        echo "</pre><br>";
        return;
    }
}

function validate_locale($locale, $check_dir_exists = True)
{
    global $dyn_locales_dir;

    if (!is_string($locale) ||
        !(in_array($locale, get_installed_system_locales()) ||
        is_dir("$dyn_locales_dir/$locale")) ||
        ($check_dir_exists && !is_dir("$dyn_locales_dir/$locale")))
    {
        die (sprintf(_("locale %s is not valid"), $locale));
    }
    return $locale;
}

// vim: sw=4 ts=4 expandtab
