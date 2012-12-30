<?php
// all functions return true if line has only that content type

function isRoman($line)
  {return preg_match('/^m?m?m?(c[md]|d?c{0,3})(x[lc]|l?x{0,3})(i[xv]|v?i{0,3})$/i',$line);}

function isRomanBrackets($line)
  {return preg_match('/^([] []{0,3})m?m?m?(c[md]|d?c{0,3})(x[lc]|l?x{0,3})(i[xv]|v?i{0,3})([] []{0,3})$/i',$line);}

function isPunct($line)
  {return preg_match('/^[[:space:][:punct:]]+$/' ,$line);}

function isNumPunct($line)
  {return preg_match('/^[[:space:][:punct:][:digit:]]+$/', $line);}

function isCapNumBrackets($line)
  {return preg_match('/^[]A-Z[:space:][:digit:][]+$/', $line);}
?>
