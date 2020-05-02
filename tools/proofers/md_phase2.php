<?php
$relPath="./../../pinc/";
include_once($relPath.'base.inc');
include_once($relPath.'Project.inc');
include_once($relPath.'project_states.inc');
include_once($relPath.'theme.inc');
include_once($relPath.'metarefresh.inc');

require_login();

if (!$site_supports_metadata)
{
    echo 'md_phase2.php: $site_supports_metadata is false, so exiting.';
    exit();
}

$projectid = validate_projectID('projectid', @$_GET['projectid']);
if (!isset($_GET['imagename'])) {
    $imagename = null;
} else {   
    $imagename = validate_page_image_filename('imagename', @$_GET['imagename']);
}

// $ppage = get_requested_PPage();



if(!isset($imagename))
{
    //find next available page for this project
    $result = mysqli_query(DPDatabase::get_connection(), "SELECT image FROM $projectid WHERE state = 'avail_md_second' ORDER BY image ASC LIMIT 1");
    $row = mysqli_fetch_assoc($result);
    //if no more images
    if(!$row)
    {
        $body=_("No more files available for proofreading for this round of the project.<br> You will be taken back to the project listing page in 4 seconds.");
        //////////this will be changed to pre-processing state
        $result = mysqli_query(DPDatabase::get_connection(), "UPDATE $projectid SET state = 'P1.page_avail'");
        $result = mysqli_query(DPDatabase::get_connection(), "UPDATE projects SET state = '".PROJ_P1_AVAILABLE."' WHERE projectid = '$projectid'");
        //////////

        metarefresh(5,"md_available.php","Image Metadata Collection",$body);
        exit;
    }
    else
    {
        $imagename = $row["image"];
        //set the image as checked out
        $result = mysqli_query(DPDatabase::get_connection(), "UPDATE $projectid SET state = 'out_md_second' WHERE image = '$imagename'");
        metarefresh(0,"md_phase2.php?imagename=$imagename&projectid=$projectid","Image Metadata Collection","");
    }
}


if (isset($_POST['done']))
{
    //process the page metadata
    //get existing metadata
    $result = mysqli_query(DPDatabase::get_connection(), "SELECT metadata FROM $projectid WHERE image = '$imagename'");
    $row = mysqli_fetch_assoc($result);
    $old_md = $row["metadata"];

    //concat new metadata
    $i=0;
    foreach($_POST as $key => $val)
    {
        if ($val =='on')
        {
            $new_md = $new_md.','.$key;
            $i++;
        }
        $all_md = $old_md.$new_md;
        $result = mysqli_query(DPDatabase::get_connection(), sprintf("
            UPDATE $projectid
            SET metadata = '%s'
            WHERE image = '%s'
        ", mysqli_real_escape_string(DPDatabase::get_connection(), $all_md),
            mysqli_real_escape_string(DPDatabase::get_connection(), $imagename)));
    }

    //change page status and back to md_available.php
    $result = mysqli_query(DPDatabase::get_connection(), "UPDATE $projectid SET state = 'save_md_second' WHERE image = '$imagename'");
    metarefresh(0,'md_available.php',"Image Metadata Collection","");
}

if (isset($_POST['quit']))
{
    //they don't want to save so set page to avail return them to md_available
    $result = mysqli_query(DPDatabase::get_connection(), "UPDATE $projectid SET state = 'avail_md_second' WHERE image = '$imagename'");
    metarefresh(0,'md_available.php',"Image Metadata Collection","");
}

if (isset($_POST['continue']))
{
    //process the page metadata
    //get existing metadata
    $result = mysqli_query(DPDatabase::get_connection(), "SELECT metadata FROM $projectid WHERE image = '$imagename'");
    $row = mysqli_fetch_assoc($result);
    $old_md = $row["metadata"];

    //concat new metadata
    $i=0;
    $new_md = '';
    foreach($_POST as $key => $val)
    {
        if ($val =='on')
        {
            $new_md = $new_md.','.$key;
            $i++;
        }
        $all_md = $old_md.$new_md;
        $result = mysqli_query(DPDatabase::get_connection(), sprintf("
            UPDATE $projectid
            SET metadata = '%s'
            WHERE image = '%s'
        ", mysqli_real_escape_string(DPDatabase::get_connection(), $all_md),
            mysqli_real_escape_string(DPDatabase::get_connection(), $imagename)));
    }

    //change page status and keep going
    $result = mysqli_query(DPDatabase::get_connection(), "UPDATE $projectid SET state = 'save_md_second' WHERE image = '$imagename'");
    metarefresh(0,"md_phase2.php?projectid=$projectid","Image Metadata Collection","");

}

$md_groups = array(
    'Front Matter' => array(
            'acknowledge'  => 'Acknowledgement',
            'dedication'   => 'Dedication',
            'ednotes'      => "Editor's Notes",
            'foreword'     => 'Foreword',
            'intro'        => 'Introduction',
            'loi'          => 'List of Illustrations',
            'preface'      => 'Preface',
            'prologue'     => 'Prologue',
            'toc'          => 'Table of Contents',
            'titlepage'    => 'Title Page',
        ),
    'Body of Book' => array(
            'abbreviation' => 'Abbreviation',
            'division'     => 'Chapter/Part/Book Heading',
            'epigraph'     => 'Dedication/Epigraph',
            'footnote'     => 'Footnote',
            'illustration' => 'Illustration or Drawing',
            'letter'       => 'Letter',
            'list'         => 'List',
            'math'         => 'Symbolic Notation',
            'poetry'       => 'Poetry/Verse',
            'sidenote'     => 'Sidenote',
            'verse'        => 'Song/Music',
            'table'        => 'Table',
        ),
    'Back Matter' => array(
            'appendix'     => 'Appendix',
            'afterword'    => 'Afterword',
            'biblio'       => 'Bibliography',
            'colophon'     => 'Colophon',
            'endnote'      => 'End Note',
            'epilogue'     => 'Epilogue',
            'index'        => 'Index',
        ),
);


output_header(_("Image Frame"));

//Start the outside table
echo "<table cols ='2' border = '1'>";

//Display image
$user = User::load_current();
if ($user->profile->i_layout == 1) {
    $iWidth = $user->profile->v_zoom;
} else {
    $iWidth = $user->profile->h_zoom;
}
$iWidth=round((1000*$iWidth)/100);

// The outside table has a single row containing two cells.
echo "<tr>\n";

// The left cell contains the page image
echo "<td><img name='scanimage' id='scanimage' title='' alt='' src='$projects_url/$projectid/$imagename' width = '$iWidth'></td>";

// The right cell contains the metadata form.
echo "<td valign = 'top'>\n";

//start the metadata table
echo "<form method = 'post'><table cols ='2' border = '1'>";
echo "<tr><td colspan ='2' align = 'center'><b>This Image Contains:</b></td></tr>
";

foreach ( $md_groups as $header => $md_items )
{
    echo "<tr>";
    echo "<td><b>$header</b></td>";
    echo "<td></td>";
    echo "</tr>\n";
    foreach ( $md_items as $item_id => $item_label )
    {
        echo "<tr>";
        echo "<td>$item_label</td>";
        echo "<td><input type='checkbox' name='$item_id'></td>";
        echo "</tr>\n";
    }
    echo "\n";
}

echo "
    <tr><td></td><td></td></tr>
    <tr><td></td><td></td></tr>
    <tr><td></td><td></td></tr>
    <tr><td colspan ='2' align = 'center'><INPUT TYPE=SUBMIT VALUE='Save and Quit' NAME = 'done'></td></tr>
    <tr><td colspan ='2' align = 'center'><INPUT TYPE=SUBMIT VALUE='Quit Without Saving' NAME = 'quit'></td></tr>
    <tr><td colspan ='2' align = 'center'><INPUT TYPE=SUBMIT VALUE='Save and Do Another'NAME = 'continue'></td></tr>";
echo "</table></form>";

// End the right cell, the single row, and the outside table.
echo "</td></tr></table>\n";

// vim: sw=4 ts=4 expandtab
