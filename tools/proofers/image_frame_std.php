<?php
$relPath="./../../pinc/";
include_once($relPath.'base.inc');
include_once($relPath.'http_headers.inc');
include_once($relPath.'slim_header.inc');
include_once('PPage.inc');

require_login();

$ppage = get_requested_PPage($_GET);

slim_header("Image Frame", array('body_attributes' => 'id="standard_interface_image"'));

$user = User::load_current();
if ($user->profile->i_layout == 1)
    $iWidth = $user->profile->v_zoom;
else
    $iWidth = $user->profile->h_zoom;
$iWidth=round((1000*$iWidth)/100);
?>

<div class="center-align" id="imagedisplay">
<img name="scanimage" id="scanimage" title="" alt=""
    src="<?php echo $ppage->url_for_image(TRUE); ?>"
    width="<?php echo $iWidth; ?>"
>
</div>

<?php
// vim: sw=4 ts=4 expandtab
