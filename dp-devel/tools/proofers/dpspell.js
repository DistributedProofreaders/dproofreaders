function setSpell(form_value,word_value)
{
  if (word_value !='sp1input')
  {
    eval('document.spcorrects.wd'+form_value).value=word_value;
  }
  else
  {
    word_value=prompt('Enter the Correct Word',eval('document.spcorrects.wd'+form_value).value);
    if (word_value)
    {
      eval('document.spcorrects.wd'+form_value).value=word_value;
      eval('document.spcorrects.sp'+form_value).options[1].text='-- '+word_value +' --';
    }
  }
}