<?
setcookie("language",$_POST['lang'],time()+31536000,"/");
header("Location: ".$_POST['returnto']);
?>
