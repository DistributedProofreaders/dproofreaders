<? $relPath='../../pinc/';
include_once($relPath.'v_site.inc');
include_once($relPath.'theme.inc');
include_once($relPath.'prefs_options.inc');
include './data/qd_' . $_REQUEST['type'] . '.inc';
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta name="generator" content="HTML Tidy, see www.w3.org">
<script type='text/javascript'>
s = "<?
if ($testing)
echo str_replace("\n",'\n',addslashes($solutions[0]));?>";
</script>
<title></title>
<META http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
</head>
<body bgcolor='#ffffff'>
<form action="./returnfeed.php?type=<?php echo $_REQUEST['type']; ?>" target="right" method="post">
<textarea rows="12" cols="60" name="output" id='output' wrap="off">
<?php echo $ocr_text; ?>
</textarea> <p>
<input type="submit" value="Check">
<input type="reset" value="Restart"></form>

<a href='#' onclick="document.forms[0].elements['output'].value=s" accesskey='`'></a>

</body>
</html>
