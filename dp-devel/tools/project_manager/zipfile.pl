#!/usr/bin/perl

$zipname = $ARGV[0];
$filename = $ARGV[1];

system (zip -ll $zipname $filename);
