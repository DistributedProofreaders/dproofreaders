<?PHP
// all latin1 characters (except 160)
$regLatin1=']A-Za-z0-9 ~!@#$%^&*()_+`={}|\:;<>?,./';
// latin1 characters ns (no space) (- 160)
$regLatin1ns=']A-Za-z0-9~!@#$%^&*()_+`={}|\:;<>?,./';
// latin1 characters n (no numbers) + ns (no space) (- 160)
$regLatin1nns=']A-Za-z~!@#$%^&*()_+`={}|\:;<>?,./';
  for ($i=161;$i<255;$i++)
    {
      $regLatin1.=chr($i);
      $regLatin1ns.=chr($i);
      $regLatin1nns.=chr($i);
    }
$regLatin1.="'".'"[-';
$regLatin1ns.="'".'"[-';
$regLatin1nns.="'".'"[-';

/*
  example to remove non-Latin 1 characters:
    ereg_replace("[^$regLatin1]","",$text)

  To create additional strings with a more limited chacter set, 
  add them to this file or do a character replace with '' on existing string.
  Do not just append or prepend values or ereg will fail within bracket expressions.
*/
?>