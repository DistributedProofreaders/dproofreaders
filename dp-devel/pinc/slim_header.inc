<?php 
include_once("gettext_setup.inc");

function slim_header($title="",$include_doctype=TRUE,$close_head_open_body=TRUE)
{
global $charset;
if($include_doctype) { ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<?php
} ?>
<html <? echo lang_html_header(); ?>>
<head>
    <title><?php echo $title; ?></title>
    
<?php
echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=$charset\" />";
?>

    <meta http-equiv="Pragma" content="no-cache" />
    <meta http-equiv="Expires" content="-1" />

<?php if($close_head_open_body) echo "\n</head>\n\n<body onLoad=\"self.focus()\">\n";
}
?>
