<?php

$big_upload_blurb = sprintf(_("<b>Note about big uploads:</b>
    If you are trying to upload a very big zip file (e.g. 10 Mb)
    and the upload does not succeed, upload a small placeholder zip file
    instead and email %s for assistance."), $db_requests_email_addr);

function show_upload_form($form_content, $submit_label)
{
    global $big_upload_blurb;
    $standard_blurb = _("<b>Note:</b> Please make sure the file you upload is Zipped (not Gzip, TAR, etc.). The file should have the .zip extension, NOT .Zip, .ZIP, etc.");
    $standard_blurb .= "<br>" . _("The rest of the file's name must consist of ASCII letters, digits, underscores, and/or hyphens. It must not begin with a hyphen.");

    $submit_blurb = _("After you click the '%s' button, the browser will appear to be slow getting to the next page. This is because it is uploading the file.");

    // Display a div with a form; some content which can be abritrary HTML/other inputs;
    // a 'choose file' button and a labeled submit button
    echo "<div style='border: 1px solid grey; margin-left: .5em; padding: .25em;'>\n";

    echo "<form id='upload_form' method='POST' enctype='multipart/form-data'>\n";
    echo "<input type='hidden' name='mode' value='upload'>";
    echo $form_content;

    echo "<p>$standard_blurb</p>";
    // these are the form elements for non-resumable upload
    echo "<div id='old_uploader'>
    <p>$big_upload_blurb</p>",
    _("File to upload"), ": <input type='file' id='old_browse' accept='.zip' name='uploaded_file' size='25' maxsize='50'>
    <input type='submit' id='old_submit' value='", attr_safe($submit_label), "'>
    <p>", sprintf($submit_blurb, $submit_label), "</p>
    </div>";

    // if resumable, these are shown instead
    echo "<div id='resumable_uploader' style='display: none;'>
    <input type='hidden' name='resumable_filename' value=''>
    <p>", _("Your browser supports uploading large files (up to 1GB) via javascript. If the upload fails, or you navigate away from this page before it finishes, uploading the file again will pick up where it left off."), "</p>
    <p>", _("File to upload"), ": <span id='resumable_selected_file'></span> &nbsp;
    <span id='resumable_browse' class='button'>", _("Choose File"), "</span>
    <span id='resumable_submit' class='button'>", _("Upload"), "</span></p>
    </div>";

    echo "<p>", _("Upload progress"), ": <span id='upload_progress'></span></p>";

    echo "</form>\n";
    echo "</div>\n";
}
