<?PHP
include_once($relPath.'prefs_options.inc');

function echo_proofing_textarea( $page_text, $enhanced )
{
    global $userP, $f_f, $f_s, $lang;

    if ( $userP['i_layout']==1 )
    {
        // "vertical"
        $n_cols      = $userP['v_tchars'];
        $n_rows      = $userP['v_tlines'];
        $line_wrap   = $userP['v_twrap'];
        $font_face_i = $userP['v_fntf'];
        $font_size_i = $userP['v_fnts'];
    }
    else
    {
        // "horizontal"
        $n_cols      = $userP['h_tchars'];
        $n_rows      = $userP['h_tlines'];
        $line_wrap   = $userP['h_twrap'];
        $font_face_i = $userP['h_fntf'];
        $font_size_i = $userP['h_fnts'];
    }

    echo "<textarea
        name='text_data'
        id='text_data'
        cols='$n_cols'
        rows='$n_rows'
        dir='".lang_direction($lang)."'
    ";

    $font_face = $f_f[$font_face_i];
    $font_size = $f_s[$font_size_i];
    echo "style='";
    if ( $font_face != '' && $font_face != BROWSER_DEFAULT_STR )
    {
        echo "font-family: $font_face;";
	echo " ";
    }
    if ( $font_size != '' && $font_size != BROWSER_DEFAULT_STR )
    {
	echo "font-size: $font_size;";
    }
    echo "padding-left: 0.25em;' ";

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
