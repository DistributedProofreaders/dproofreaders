<?php
// not including base.inc as this is a simple redirect
setcookie("language",$_POST['lang'],time()+31536000,"/");
header("Location: ".$_POST['returnto']);

// vim: sw=4 ts=4 expandtab
