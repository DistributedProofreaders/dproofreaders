<?PHP
// includes scannos within function and returns them as a single array

function returnScannos()
  {
    include_once('v_prep_text_scanos_0_m.inc');
    include_once('v_prep_text_scanos_n_z.inc');
    return array_merge($scos0M,$scosNZ);
  }
?>
