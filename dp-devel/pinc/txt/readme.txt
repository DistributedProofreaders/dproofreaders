This folder is for code related to text formatting, manipulating, etc. in pre-processing, 
post-processing and proofing.

It was created to limit the clutter in the root of the pinc folder.

If you add a code item to this folder, please add a description here.

--------------------------

v_ereg_latin1.inc:
     Variables for use in ereg functions with complete and slightly reduced 
     character sets for Latin 1.  See file for details.

f_prep_ereg_headers.inc
     Functions to determine possible headers and footers in a document. They 
     should be sent a single line of text.  They return true if line is only:
     function isRoman($line) // all roman numerals
     function isRomanBrackets($line) // all roman numerals and brackets
     function isPunct($line) // all punctuation only
     function isNumPunct($line) // all numeric with punctuation
     function isCapNumBrackets($line) // all capital letters numbers and brackets

v_prep_text_hyphens.inc
     Variable $nojoin is an array of first half of words which should not be de-hyphenated.

v_prep_text_scanos_0_m.inc
     Variable $scos0M is an array of common scannos 0-M

v_prep_text_scanos_n_z.inc
     Variable $scosNZ is an array of common scannos N-Z

f_prep_text_scanos.inc
     Function to combine the two scanno vars into an array
     function returnScannos()
