<?php function slim_header($title,$dt=TRUE,$hb=TRUE)
{
global $charset;
if($dt) { ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<?php
} ?>
<html>
<head>
    <title><?php echo $title; ?></title>
    
<?php
echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=$charset\" />";
?>

    <meta http-equiv="Pragma" content="no-cache" />
    <meta http-equiv="Expires" content="-1" />

<?php if($hb) echo "\n</head>\n\n<body>\n";
}
?>
