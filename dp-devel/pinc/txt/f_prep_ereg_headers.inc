<?PHP
// all functions return true if line has only that content type

function isRoman($line)
  {return eregi('^m?m?m?(c[md]|d?c{0,3})(x[lc]|l?x{0,3})(i[xv]|v?i{0,3})$',$line);}

function isRomanBrackets($line)
  {return eregi('^([] []{0,3})m?m?m?(c[md]|d?c{0,3})(x[lc]|l?x{0,3})(i[xv]|v?i{0,3})([] []{0,3})$',$line);}

function isPunct($line)
  {return ereg('^[:space::punct:]+$',$line);}

function isNumPunct($line)
  {return ereg('^[:space::punct::digit:]+$',$line);}

function isCapNumBrackets($line)
  {return ereg('^[]A-Z:space::digit:[]+$',$line);}
?>