<?php function slim_header($title,$dt=TRUE,$hb=TRUE)
{
if($dt) { ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<?php
} ?>
<html>
<head>
    <title><?php echo $title ?></title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="Pragma" content="no-cache" />
    <meta http-equiv="Expires" content="-1" />
<?php if($body) echo "</head>\n<body>";
}
?>
