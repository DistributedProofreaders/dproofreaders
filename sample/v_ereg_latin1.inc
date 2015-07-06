<?php
// all latin1 characters (except 160)
$regLatin1=']A-Za-z0-9 ~!@#$%^&*()_+`={}|\:;<>?,./\xa1-\xff\'"[-';
// latin1 characters ns (no space) (- 160)
$regLatin1ns=']A-Za-z0-9~!@#$%^&*()_+`={}|\:;<>?,./\xa1-\xff\'"[-';
// latin1 characters n (no numbers) + ns (no space) (- 160)
$regLatin1nns=']A-Za-z~!@#$%^&*()_+`={}|\:;<>?,./\xa1-\xff\'"[-';

/*
  example to remove non-Latin 1 characters:
    preg_replace("/[^$regLatin1]/", "", $text)

  To create additional strings with a more limited chacter set,
  add them to this file or do a character replace with '' on existing string.
  Do not just append or prepend values or preg will fail within bracket expressions.
*/
?>
