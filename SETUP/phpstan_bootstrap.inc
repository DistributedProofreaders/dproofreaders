<?php

// Don't open a DB connection in bootstrap.inc so we can run PHPStan w/o MySQL.
define('SKIP_DB_CONNECT', true);

// Two dummy functions to quell PHPStan warnings when loading bootstrap.inc
function test_exception_handler($exception)
{
}

function production_exception_handler($exception)
{
}
