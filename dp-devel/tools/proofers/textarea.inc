<?PHP
include_once($relPath.'prefs_options.inc');

function echo_proofing_textarea( $page_text, $enhanced )
{
    global $userP, $f_f;

    if ( $userP['i_layout']==1 )
    {
        // "vertical"
        $n_cols      = $userP['v_tchars'];
        $n_rows      = $userP['v_tlines'];
        $line_wrap   = $userP['v_twrap'];
        $font_face_i = $userP['v_fntf'];
    }
    else
    {
        // "horizontal"
        $n_cols      = $userP['h_tchars'];
        $n_rows      = $userP['h_tlines'];
        $line_wrap   = $userP['h_twrap'];
        $font_face_i = $userP['h_fntf'];
    }

    echo "<textarea
        name='text_data'
        id='text_data'
        cols='$n_cols'
        rows='$n_rows'
    ";

    $font_face = $f_f[$font_face_i];
    if ( $font_face != '' && $font_face != BROWSER_DEFAULT_STR )
    {
        echo "style='font-family: $font_face' ";
    }

    if ( !$line_wrap )
    {
        echo "wrap='off' ";
    }

    if ( $enhanced )
    {
        echo "
            onselect='getCurSel()'
            onclick='getCurCaret()'
            onkeyup='getCurCaret()'
        ";
    }

    echo ">\n";

    // SENDING PAGE-TEXT TO USER
    // We're sending it in an HTML document, so encode special characters.
    echo htmlspecialchars( $page_text, ENT_NOQUOTES );

    echo "</textarea>";
}

?>
