<?php
global $relPath;
$relPath='../../pinc/';
include_once($relPath.'base.inc');

function dp_class_autoloader($class)
{
    global $relPath;
    include_once($relPath.$class.".inc");
}

spl_autoload_register('dp_class_autoloader');
