<?php
$relPath='../../pinc/';
include_once($relPath.'base.inc');
include_once($relPath.'faq.inc');
include_once($relPath.'pg.inc');
include_once($relPath.'theme.inc');

maybe_redirect_to_external_faq("es");

$theme_args["css_data"] = "p.backtotop {text-align:right; font-size:75%;margin-right:-5%;}";

output_header('Proofreading Guidelines', NO_STATSBAR, $theme_args);

?>

<!-- NOTE TO MAINTAINERS AND DEVELOPERS:

     There are now HTML comments interspersed in this document that are/will be
     used by a script that automagically slices out the Random Rule text for the
     database. It does this by copying:
       1) All text from one h_3 to the next h_3
        -OR-
       2) All text from h_3 to the END_RR comment line.

    This allows us to have "extra" information in the Guidelines, but leave it out
    in the Random Rule for purposes of clarity/brevity.

    If you are updating this document, the above should be kept in mind.
-->

<h1 align="center"><a name="top">Reglas de Revisi&oacute;n</a></h1>

<h3 align="center">
    Versi&oacute;n 2.0, revisada el 7 de junio de 2009.
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <a href="../dochist.php"><font size="-1">(Revision History)</font></a>
    <br>
    (Traducci&oacute;n actualizada el 1&ordm; de junio de 2015)
</h3>

<p>
Reglas de Revisi&oacute;n <a href="../proofreading_guidelines.php">en ingl&eacute;s</a> /
      Proofreading Guidelines <a href="../proofreading_guidelines.php">in English</a><br />
    Reglas de Revisi&oacute;n <a href="../fr/proofreading_guidelines.php">en franc&eacute;s</a> /
      Directives de Relecture et correction <a href="../fr/proofreading_guidelines.php">en fran&ccedil;ais</a><br />
    Reglas de Revisi&oacute;n <a href="../pt/proofreading_guidelines.php">en portugu&eacute;s</a> /
      Regras de Revis&atilde;o <a href="../pt/proofreading_guidelines.php">em Portugu&ecirc;s</a><br />
    Reglas de Revisi&oacute;n <a href="../nl/proofreading_guidelines.php">en holand&eacute;s</a> /
      Proeflees-Richtlijnen <a href="../nl/proofreading_guidelines.php">in het Nederlands</a><br />
    Reglas de Revisi&oacute;n <a href="../de/proofreading_guidelines.php">en alem&aacute;n</a> /
      Korrekturlese-Richtlinien <a href="../de/proofreading_guidelines.php">auf Deutsch</a><br />
    Reglas de Revisi&oacute;n <a href="../it/proofreading_guidelines.php">en italiano</a> /
      Regole di Correzione <a href="../it/proofreading_guidelines.php">in Italiano</a><br>
</p>

<p>Vea los <a href="../../quiz/start.php?show_only=PQ">Tests y tutoriales de revisi&oacute;n</a>! (en ingl&eacute;s)
</p>

<table border="0" cellspacing="0" width="100%" summary="Table of Contents">
  <tbody>
  <tr>
    <td width="1" bgcolor="silver">&nbsp;</td>
    <td bgcolor="silver" align="center"><h2 style="margin-bottom: 0; margin-top: 0;">Tabla de contenidos</h2></td>
  </tr>
  <tr>
    <td width="1" bgcolor="silver">&nbsp;</td>
    <td bgcolor="white" align="left">
      <ul>
        <li><a href="#prime">Regla principal</a></li>
        <li><a href="#summary">Resumen de las Reglas de Revisi&oacute;n</a></li>
        <li><a href="#about">Acerca de este documento</a></li>
        <li><a href="#comments">Comentarios del Proyecto</a></li>
        <li><a href="#forums">Discusi&oacute;n del Proyecto</a></li>
        <li><a href="#prev_pg">C&oacute;mo corregir errores en p&aacute;ginas anteriores</a></li>
      </ul>
    </td>
  </tr>
  <tr>
    <td width="1" bgcolor="silver">&nbsp;</td>
    <td bgcolor="silver" align="left">&nbsp;</td>
  </tr>
  <tr>
    <td width="1" bgcolor="silver">&nbsp;</td>
    <td bgcolor="white" align="left">
      <ul>
        <li style="margin-top:.25em;"><font size="+1">Revisando caracteres:</font>
        <ul>
          <li><a href="#double_q">Comillas dobles</a></li>
          <li><a href="#single_q">Comillas simples</a></li>
          <li><a href="#quote_ea">Comillas en cada l&iacute;nea</a></li>
          <li><a href="#period_s">Punto final de las oraciones</a></li>
          <li><a href="#punctuat">Puntuaci&oacute;n</a></li>
          <li><a href="#extra_sp">Varios espacios en blanco entre palabras</a></li>
          <li><a href="#trail_s">Espacio en blanco al final de la l&iacute;nea</a></li>
          <li><a href="#em_dashes">Guiones, el signo menos "-" y rayas</a></li>
          <li><a href="#eol_hyphen">Gui&oacute;n al final de la l&iacute;nea</a></li>
          <li><a href="#eop_hyphen">Gui&oacute;n al final de la p&aacute;gina</a></li>
          <li><a href="#period_p">Puntos suspensivos &quot;...&quot;</a></li>
          <li><a href="#contract">Contracciones</a></li>
          <li><a href="#fract_s">Fracciones</a></li>
          <li><a href="#a_chars">Caracteres acentuados y caracteres no ASCII</a></li>
          <li><a href="#d_chars">Signos diacr&iacute;ticos</a></li>
          <li><a href="#f_chars">Caracteres no latinos</a></li>
          <li><a href="#supers">Super&iacute;ndices</a></li>
          <li><a href="#subscr">Sub&iacute;ndices</a></li>
          <li><a href="#drop_caps">Letras may&uacute;sculas ornamentadas al comiendo del p&aacute;rrafo, frase o secci&oacute;n</a></li>
          <li><a href="#small_caps">Versalitas</a></li>
        </ul></li>
        <li style="margin-top:.25em;"><font size="+1">Revisando p&aacute;rrafos:</font>
        <ul>
          <li><a href="#line_br">Saltos de l&iacute;nea</a></li>
          <li><a href="#chap_head">T&iacute;tulos de cap&iacute;tulo</a></li>
          <li><a href="#para_space">Espaciado y sangr&iacute;a de p&aacute;rrafos</a></li>
          <li><a href="#page_hf">Encabezados y pies de p&aacute;gina</a></li>
          <li><a href="#illust">Ilustraciones</a></li>
          <li><a href="#footnotes">Notas al pie de p&aacute;gina y notas al final</a></li>
          <li><a href="#para_side">Notas al margen</a></li>
          <li><a href="#mult_col">M&uacute;ltiples columnas</a></li>
          <li><a href="#tables">Tablas</a></li>
          <li><a href="#poetry">Poes&iacute;a o epigramas</a></li>
          <li><a href="#line_no">N&uacute;meros de l&iacute;nea</a></li>
          <li><a href="#next_word">Palabra suelta al final de la p&aacute;gina</a></li>
        </ul></li>
        <li style="margin-top:.25em;"><font size="+1">Revisando p&aacute;ginas:</font>
        <ul>
          <li><a href="#blank_pg">P&aacute;gina en blanco</a></li>
          <li><a href="#title_pg">Portada y contraportada</a></li>
          <li><a href="#toc">Tabla de contenidos</a></li>
          <li><a href="#bk_index">&Iacute;ndices</a></li>
          <li><a href="#play_n">Obras de teatro: nombre de actores y directrices esc&eacute;nicas</a></li>
        </ul></li>
        <li><a href="#anything">Cualquier otra cosa que necesite tratamiento especial o no sepa como tratar</a></li>
        <li><a href="#prev_notes">Notas y comentarios de revisores anteriores</a></li>
      </ul>
    </td>
  </tr>
  <tr>
    <td width="1" bgcolor="silver">&nbsp;</td>
    <td bgcolor="silver" align="left">&nbsp;</td>
  </tr>
  <tr>
    <td width="1" bgcolor="silver">&nbsp;</td>
    <td bgcolor="white" align="left">
      <ul>
        <li style="margin-top:.25em;"><font size="+1">Problemas comunes:</font>
        <ul>
          <li><a href="#formatting">Formato</a></li>
          <li><a href="#common_OCR">Errores comunes del OCR</a></li>
          <li><a href="#OCR_scanno">Problemas de OCR: Scannos</a></li>
          <li><a href="#OCR_raised_o">Problemas del OCR: &iquest;es eso &deg; &ordm; realmente un s&iacute;mbolo de grados?</a></li>
          <li><a href="#hand_notes">Notas manuscritas</a></li>
          <li><a href="#bad_image">Im&aacute;genes de mala calidad</a></li>
          <li><a href="#bad_text">Imagen que no corresponde al texto</a></li>
          <li><a href="#round1">Errores de revisores anteriores</a></li>
          <li><a href="#p_errors">Errores de impresi&oacute;n o de ortograf&iacute;a</a></li>
          <li><a href="#f_errors">Errores f&aacute;cticos en el texto</a></li>
          <li><a href="#insert_char">Insertando caracteres especiales</a></li>
        </ul></li>
        <li><a href="#index">&Iacute;ndice alf&aacute;b&eacute;tico de las Reglas de Revisi&oacute;n</a></li>
      </ul>
    </td>
  </tr>
  <tr>
    <td width="1" bgcolor="silver">&nbsp;</td>
    <td bgcolor="silver">&nbsp;</td>
  </tr>
 </tbody>
</table>

<div style="margin-right:6%; margin-left:0.5%;">

<h3><a name="prime">Regla principal</a></h3>
<p><em>"&iexcl;No cambie lo que escribi&oacute; el autor!"</em>
</p>
<p>El libro electr&oacute;nico final que ver&aacute; el lector, posiblemente dentro de muchos a&ntilde;os, debe expresar con exactitud lo que el autor quiso decir. Si el autor escrib&iacute;a palabras con ortograf&iacute;a extra&ntilde;a, las dejamos tal cual. Si el autor hac&iacute;a declaraciones indignantemente racistas o sesgadas, las dejamos tal cual. Si el autor usaba comas, super&iacute;ndices o notas al pie cada tres palabras, debemos conservar las comas, los super&iacute;ndices y las notas donde est&aacute;n. Somos revisores, <b>no</b> editores. Si el texto no es igual a la imagen, debe cambiar el texto para que lo sea. (Vea <a href="#p_errors">Errores de impresi&oacute;n/ortograf&iacute;a</a> para saber c&oacute;mo tratar errores de impresi&oacute;n.)
</p>

<p>Cambiamos solamente peque&ntilde;os detalles relativos a convenciones tipogr&aacute;ficas que no afectan el sentido de lo escrito por el autor. Por ejemplo, unimos palabras que fueron divididas al final de la l&iacute;nea (vea <a href="#eol_hyphen">Guiones al final de la l&iacute;nea</a>). 
   Cambios de este tipo nos ayudan a crear una versi&oacute;n del libro <em>coherente</em>.
   Las reglas de revisi&oacute;n que seguimos est&aacute;n dise&ntilde;adas para ayudarnos a alcanzar este resultado. Teniendo esto en cuenta, por favor lea cuidadosamente el resto de las Reglas de Revisi&oacute;n. Estas reglas sirven <i>solamente</i> para los revisores. Como revisor, su objetivo es que el texto concuerde con la imagen. M&aacute;s adelante los formateadores se encargar&aacute;n de aplicar formato a la p&aacute;gina.
</p>
<p>Para ayudar al pr&oacute;ximo revisor, y a los encargados del formato y del posproceso, conservamos los <a href="#line_br">saltos de l&iacute;nea</a>. As&iacute; resulta m&aacute;s f&aacute;cil comparar el texto con la imagen original.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Volver al comienzo</a></p>


<h3><a name="summary">Resumen de las Reglas de Revisi&oacute;n</a></h3>
<p>El <a href="../proofing_summary.pdf">Resumen de las Reglas de Revisi&oacute;n</a> (en ingl&eacute;s) es un documento PDF corto (dos p&aacute;ginas), f&aacute;cil de imprimir, que sintetiza los principales punto de estas Reglas y brinda ejemplos para saber c&oacute;mo corregir las p&aacute;ginas. Se recomienda a los revisores principiantes imprimir este documento y mantenerlo a mano mientras realizan su labor.
</p>
<p>Puede que usted necesite descargar e instalar un programa para abrir archivos PDF. Es posible obtener uno gratuito en la p&aacute;gina de Adobe&reg;, <a href="http://www.adobe.com/products/acrobat/readstep2.html">aqu&iacute;</a>.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Volver al comienzo</a></p>


<h3><a name="about">Acerca de este documento</a></h3>
<p>La finalidad de este documento es explicar las reglas de revisi&oacute;n que utilizamos para mantener la coherencia cuando estamos revisando un mismo libro que ha sido distribuido entre muchos colaboradores que trabajan en p&aacute;ginas diferentes. Esto asegura que todos estemos realizando la revisi&oacute;n de <em>la misma manera</em>; lo cual a su vez hace m&aacute;s f&aacute;cil el trabajo de los formateadores y de los posprocesadores. El posprocesador re&uacute;ne todas las p&aacute;ginas para formar un libro electr&oacute;nico completo.
</p>
<p><i>Este documento de ninguna manera pretende ser modelo para la edici&oacute;n de libros</i>.
</p>
<p>Hemos incluido en estas Reglas de Revisi&oacute;n todos los puntos que han generado dudas en los nuevos revisores. Existen, adem&aacute;s, unas <a href="../formatting_guidelines.php">Reglas de Formato</a> (en ingl&eacute;s) distintas, utilizadas por el grupo de voluntarios que trabaja en las rondas de formato. Si usted encuentra un tema que no se encuentra mencionado en estas reglas, es posible que el problema vaya a ser abordado en las rondas de formato. Si usted no est&aacute; seguro, por favor pregunte en la <a href="#forums">Discusi&oacute;n del Proyecto</a>.
</p>
<p>Si encuentra alg&uacute;n tema que no haya sido tratado, considera que un punto debe ser abordado de manera diferente o que la explicaci&oacute;n es demasiado vaga, por favor h&aacute;ganoslo saber. 
<?php if($site_url == "http://www.pgdp.net" || $site_url == "http://www.pgdp.org") { ?>
Si se encuentra con un t&eacute;rmino que no conoce en estas reglas, puede revisar la <a href="http://www.pgdp.net/wiki/DP_Jargon">gu&iacute;a de jerga en la Wiki</a>. 
<?php } ?>
Este documento se encuentra en permanente elaboraci&oacute;n. Ay&uacute;denos a mejorarlo publicando sugerencias para cambios en <a href="<?php echo $Guideline_discussion_URL; ?>">este hilo</a> del Foro de Documentaci&oacute;n.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Volver al comienzo</a></p>


<h3><a name="comments">Comentarios del Proyecto</a></h3>
<p>Cuando elige un proyecto para trabajar, se abre la P&aacute;gina del Proyecto. All&iacute; usted encontrar&aacute; una secci&oacute;n llamada "Comentarios del Proyecto" (Project Comments) que contiene informaci&oacute;n espec&iacute;fica sobre el proyecto. <b>&iexcl;Debe leer los Comentarios del Proyecto antes de comenzar a revisar p&aacute;ginas!</b> Si el Gestor de Proyecto (Project Manager) desea que en este libro en concreto algo sea realizado de manera diferente a lo que establecen estas Reglas, esa informaci&oacute;n estar&aacute; all&iacute;. Las instrucciones en los Comentarios del Proyecto <em>est&aacute;n por encima</em> de estas Reglas de Revisi&oacute;n, por lo tanto, s&iacute;galas. En los Comentarios del Proyecto tambi&eacute;n puede haber instrucciones relativas al formato. Ignore estas instrucciones en la fase de revisi&oacute;n. Adem&aacute;s, en estos comentarios es donde ocasionalmente el gestor del proyecto agrega datos interesantes y curiosos sobre el libro o el autor.
</p>
<p>
<em>Por favor, lea tambi&eacute;n la Discusi&oacute;n del Proyecto</em>. All&iacute; es donde el Gestor del Proyecto (Project Manager) puede aclarar las reglas de revisi&oacute;n espec&iacute;ficas para este proyecto. Adem&aacute;s, los revisores suelen utilizar el foro para alertar a otros revisores sobre problemas recurrentes en el libro y compartir informaci&oacute;n sobre c&oacute;mo tratarlos. (Ver m&aacute;s abajo.)
</p>
<p>
En la P&aacute;gina del Proyecto (Project Page), el enlace "Images, Pages Proofread, &amp; Differences" (Im&aacute;genes, p&aacute;ginas revisadas y diferencias) le permite ver c&oacute;mo otros revisores han corregido el texto. En <a href="<?php echo $Using_project_details_URL; ?>">esta discusi&oacute;n del foro</a> (en ingl&eacute;s) se habla sobre las diferentes maneras de utilizar esta informaci&oacute;n.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Volver al comienzo</a></p>


<h3><a name="forums">Discusi&oacute;n del Proyecto</a></h3>
<p>
En la P&aacute;gina del Proyecto, en la l&iacute;nea "Forum" (Foro), hay un enlace llamado "Discuss this Project" (Discutir este proyecto), si la discusi&oacute;n ya ha sido comenzada; o "Start a discussion on this Project" (Comenzar una discusi&oacute;n sobre este proyecto), si todav&iacute;a no se ha empezado. Al hacer clic en ese enlace, usted ser&aacute; llevado al hilo del foro dedicado a este proyecto en espec&iacute;fico. Ese es el lugar indicado para hacer preguntas sobre este libro, informar al Gestor del Proyecto (Project Manager) sobre problemas, etc. La Discusi&oacute;n del Proyecto en el foro es la mejor manera de comunicarse con el Gestor del Proyecto y los otros voluntarios que est&aacute;n trabajando en el libro.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Volver al comienzo</a></p>


<h3><a name="prev_pg">C&oacute;mo corregir errores en p&aacute;ginas anterioers</a></h3>
<p>La <a href="#comments">P&aacute;gina del Proyecto</a> contiene enlaces a las p&aacute;ginas de ese libro que usted ha revisado recientemente. (Si a&uacute;n no ha revisado ninguna p&aacute;gina, no se mostrar&aacute; ning&uacute;n enlace.)
</p>
<p>Las p&aacute;ginas que aparecen debajo de "DONE" (terminado) o "IN PROGRESS" (en progreso) est&aacute;n todav&iacute;a disponibles para que usted las corrija o complete la revisi&oacute;n. Solo debe hacer clic en el enlace de la p&aacute;gina. De este modo, si usted descubre que ha cometido un error en alguna p&aacute;gina, puede enmendar la equivocaci&oacute;n haciendo clic en estos enlaces para volver a acceder a la p&aacute;gina en cuesti&oacute;n.
</p>
<p>Tambi&eacute;n puede utilizar los enlaces "Images, Pages Proofread, &amp; Differences" (Im&aacute;genes, p&aacute;ginas revisadas y diferencias) &oacute; "Just My Pages" (Solo mis p&aacute;ginas) en la <a href="#comments">P&aacute;gina del Proyecto</a>. All&iacute; ver&aacute; el enlace "Edit" (editar) al lado de todas las p&aacute;ginas en las que usted ha trabajado en la presente ronda, las cuales todav&iacute;a pueden ser corregidas.
</p>
<p>Para obtener m&aacute;s informaci&oacute;n, visite la <a href="../prooffacehelp.php?i_type=0">Ayuda de la Interfaz de Revisi&oacute;n Est&aacute;ndar</a> (en ingl&eacute;s) o la <a href="../prooffacehelp.php?i_type=1">Ayuda para la Interfaz de Revisi&oacute;n Mejorada</a> (en ingl&eacute;s), dependiendo de la interfaz que usted utilice.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Volver al comienzo</a></p>


<table width="100%" border="0" cellspacing="0" cellpadding="6" summary="Character-level proofreading">
  <tbody>
    <tr>
      <td bgcolor="silver"><h2 style="margin-bottom: 0; margin-top: 0;">Revisando caracteres:</h2></td>
    </tr>
  </tbody>
</table>


<h3><a name="double_q">Comillas dobles</a></h3>
<p>
Revise las &ldquo;comillas dobles&rdquo; como comillas dobles rectas ASCII <tt>"</tt>. No cambie las comillas dobles a comillas simples. D&eacute;jelas como las escribi&oacute; el autor. Vea la secci&oacute;n <a href="#chap_head">T&iacute;tulos de cap&iacute;tulos</a> si se encuentra con una comilla doble que falta al comienzo de un cap&iacute;tulo.
</p>
<p>Para comillas diferentes de <tt>"</tt>, use las mismas comillas que aparecen en la imagen si est&aacute;n disponibles. Las comillas francesas, guillemets, <tt>&laquo;como estas&raquo;</tt>, est&aacute;n disponibles en el men&uacute; desplegable de la interfaz de revisi&oacute;n, dado que son parte del conjunto de caracteres Latin-1. Recuerde eliminar los espacios entre las comillas y el texto entre comillas; si el espacio es necesario por alguna raz&oacute;n, se a&ntilde;adir&aacute; en el posproceso. Esta regla se aplica tambi&eacute;n en los idiomas que utilizan comillas guillemets invertidas, <tt>&raquo;as&iacute;&laquo;</tt>.
</p>
<p>
Las comillas de <tt>&bdquo;este tipo&ldquo;</tt>, utilizadas en algunos textos en alem&aacute;n o en otros idiomas, 
est&aacute;n tambi&eacute;n disponibles en el men&uacute; desplegable; por razones de simplicidad, usted deber&iacute;a siempre usar&nbsp; <tt>&bdquo;</tt>&nbsp; y&nbsp; <tt>&ldquo;</tt>&nbsp;, independientemente del tipo de comillas que se utilicen en el texto original, siempre que las comillas usadas en el mismo sean claramente inferiores y superiores. Si es necesario, las comillas ser&aacute;n cambiadas a las que se utilizan en el libro original durante el posprocesamiento. 
</p>
<p>El Gestor del Proyecto (Project Manager) puede pedir en los <a href="#comments">Comentarios del Proyecto</a> que las comillas sean tratadas de manera diferente para un libro en particular. Por favor, no aplique esas instrucciones en otros proyectos.
</p>

<!-- END RR -->
<p class="backtotop"><a href="#top">Volver al comienzo</a></p>


<h3><a name="single_q">Comillas simples</a></h3>
<p>
Rev&iacute;selas como comillas simples ASCII o ap&oacute;strofos, as&iacute;: <tt>'</tt>. No cambie las comillas simples a dobles. D&eacute;jelas como el autor las escribi&oacute;.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Volver al comienzo</a></p>


<h3><a name="quote_ea">Comillas en cada l&iacute;nea</a></h3>
<p>
Los p&aacute;rrafos que tienen comillas al comienzo de cada l&iacute;nea se corrigen dejando <b>solamente</b> las de la primera l&iacute;nea, donde comienza el p&aacute;rrafo. Si la cita se prolonga a lo largo de varios p&aacute;rrafos, en cada uno se deben conservar las comillas de la primera l&iacute;nea.
</p>
<p>Sin embargo, cuando se trata de poes&iacute;a conservamos todas las comillas que aparecen en la imagen, dado que los saltos de l&iacute;nea no ser&aacute;n modificados.
</p>
<p>A veces no hay comillas de cierre hasta el final de la cita, que puede estar en una p&aacute;gina diferente. Debe conservar el texto tal como aparece en la imagen; no a&ntilde;ada comillas de cierre.
</p>
<p>Hay algunas excepciones para ciertos idiomas. En franc&eacute;s, por ejemplo, el di&aacute;logo encerrado entre comillas utiliza una combinaci&oacute;n de diferentes tipos de puntuaci&oacute;n para indicar las distintas personas que intervienen en la conversaci&oacute;n. Si usted no est&aacute; familiarizado con un idioma en particular, revise los <a href="#comments">Comentarios del Proyecto</a> o deje un mensaje para el Gestor del Proyecto en la Discusi&oacute;n del Proyecto para saber c&oacute;mo proceder.
</p>
<!-- END RR -->
<table width="100%" border="1" cellpadding="4" cellspacing="0" align="center" summary="Example of quote marks on each line">
  <tbody>
    <tr>
      <th valign="top" align="left" bgcolor="cornsilk">Imagen original:</th>
    </tr>
    <tr>
      <td valign="top">
        Clearly he wasn't an academic with a preface like this<br>
        one. &ldquo;I do not give the name of the play, act or scene,<br>
        &ldquo;in head or foot lines, in my numerous quotations from<br>
        &ldquo;Shakspere, designedly leaving the reader to trace and<br>
        &ldquo;find for himself a liberal education by studying the<br>
        &ldquo;wisdom of the Divine Bard.<br>
        <br>
        &ldquo;There are many things in this volume that the ordinary<br>
        &ldquo;mind will not understand, yet I only contract with the<br>
        &ldquo;present and future generations to give rare and rich<br>
        &ldquo;food for thought, and cannot undertake to furnish the<br>
        &ldquo;reader brains with each book!&rdquo;
      </td>
    </tr>
    <tr>
      <th valign="top" align="left" bgcolor="cornsilk">Texto revisado correctamente:</th>
    </tr>
    <tr valign="top">
      <td>
        <tt>
        Clearly he wasn't an academic with a preface like this<br>
        one. "I do not give the name of the play, act or scene,<br>
        in head or foot lines, in my numerous quotations from<br>
        Shakspere, designedly leaving the reader to trace and<br>
        find for himself a liberal education by studying the<br>
        wisdom of the Divine Bard.<br>
        <br>
        "There are many things in this volume that the ordinary<br>
        mind will not understand, yet I only contract with the<br>
        present and future generations to give rare and rich<br>
        food for thought, and cannot undertake to furnish the<br>
        reader brains with each book!"
        </tt>
      </td>
    </tr>
  </tbody>
</table>
<p class="backtotop"><a href="#top">Volver al comienzo</a></p>


<h3><a name="period_s">Punto final de las oraciones</a></h3>
<p>Deje solo un espacio despu&eacute;s del punto final de las frases.
</p>
<p>Si en el texto escaneado aparecen varios espacios despu&eacute;s del punto final, no es necesario quitar los espacios sobrantes. Esto se hace autom&aacute;ticamente durante el posprocesamiento.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Volver al comienzo</a></p>


<h3><a name="punctuat">Puntuaci&oacute;n</a></h3>
<p>Espacios antes de la puntuaci&oacute;n aparecen a veces porque las imprentas en los siglos XVIII y XIX utilizaban a menudo espacios parciales antes de signos de puntuaci&oacute;n tales como el punto y coma o los dos puntos.
</p>
<p>En general, deber&iacute;a haber un espacio despu&eacute;s de un signo de puntuaci&oacute;n y ning&uacute;n espacio antes. Si el OCR no ha dejado un espacio despu&eacute;s de un signo de puntuaci&oacute;n, usted debe a&ntilde;adirlo; si el OCR ha dejado un espacio antes del signo de puntuaci&oacute;n, usted debe eliminarlo. Esto se aplica tambi&eacute;n a idiomas tales como el franc&eacute;s, en el cual normalmente se dejan espacios antes de los signos de puntuaci&oacute;n. Sin embargo, los signos de puntuaci&oacute;n que normalmente aparecen de a pares, tales como "comillas", (par&eacute;ntesis), [corchetes] y {llaves}, tienen normalmente un espacio antes del signo de apertura, el cual debe ser conservado.
</p>
<!-- END RR -->

<table width="100%" align="center" border="1" cellpadding="4" cellspacing="0" summary="Punctuation example">
  <tbody>
    <tr><th align="left" bgcolor="cornsilk">Imagen original:</th></tr>
    <tr>
      <td valign="top">and so it goes&nbsp;; ever and ever.</td>
    </tr>
    <tr><th align="left" bgcolor="cornsilk">Texto correctamente revisado:</th></tr>
    <tr>
      <td valign="top"><tt>and so it goes; ever and ever.</tt></td>
    </tr>
  </tbody>
</table>
<p class="backtotop"><a href="#top">Volver al comienzo</a></p>


<h3><a name="extra_sp">Varios espacios en blanco entre palabras</a></h3>
<p>Varios espacios en blanco entre palabras aparecen a menudo en los resultados de OCR. No se moleste en eliminarlos&---;eso se har&aacute; autom&aacute;ticamente en el posprocesamiento. Por el contrario, <b>s&iacute;</b> es necesario eliminar espacios en blanco que aparecen entre las palabras y los signos de puntuaci&oacute;n (comillas, puntos, punto y coma, etc.).
</p>
<p>
Por ejemplo, en <tt>"Un caballo&nbsp;;&nbsp;&nbsp;mi reino por un caballo."</tt> el espacio entre la palabra "caballo" y el punto y coma debe ser eliminado. Pero no es necesario eliminar el espacio extra que aparece despu&eacute;s del punto y coma.
</p>
<p>Adem&aacute;s, si usted encuentra alg&uacute;n car&aacute;cter de tabulaci&oacute;n en el texto, debe eliminarlo.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Volver al comienzo</a></p>


<h3><a name="trail_s">Espacios en blanco al final de la l&iacute;nea</a></h3>
<p>No pierda tiempo en insertar espacios en blanco al final de las l&iacute;neas del texto. Estos espacios ser&aacute;n automaticamente eliminados cuando usted guarde la p&aacute;gina como "DONE" (completada). Durante el posprocesamiento del libro, cada final de l&iacute;nea ser&aacute; convertido en un solo espacio.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Volver al comienzo</a></p>


<h3><a name="em_dashes">Guiones, el signo menos "-" y rayas</a></h3>
<p>Generalmente en los libros hay cuatro tipo de guiones:
</p>
  <ol compact>
    <li><i>Guiones de separaci&oacute;n</i>. Se utilizan para <b>unir</b> dos palabras, o a veces re&uacute;nen prefijos y sufijos a las palabras.
    <br>D&eacute;jelos como un gui&oacute;n simple, sin ning&uacute;n espacio antes o despu&eacute;s.
        Tenga en cuenta que existe una excepci&oacute;n a esta regla en el punto N&deg; 2 de esta lista.
    </li>
    <li><i>El gui&oacute;n medio (gui&oacute;n "ene" o de medio cuadrat&iacute;n)</i>. Estos guiones pueden ser un poco m&aacute;s largos y se utilizan para indicar un <b>rango</b> de n&uacute;meros o el signo matem&aacute;tico <b>menos</b>.
    <br>Rev&iacute;selos como un gui&oacute;n simple. Los espacios (antes o despu&eacute;s) tienen que coincidir con el texto original; normalmente no hay espacios para rangos de n&uacute;meros, y s&iacute; antes (y a veces tambi&eacute;n despu&eacute;s) del signo menos.
    </li>
    <li><i>Guiones largos (gui&oacute;n "eme" o de uno o m&aacute;s cuadratines)</i>. Estos guiones <b>separan</b> las palabras&mdash;a veces para enfatizar algo, as&iacute;&---;o cuando el que habla vacila antes de completar la frase&mdash;&mdash;!
    <br>Rev&iacute;selos como dos guiones (--) si el gui&oacute;n ocupa el espacio de 2 o 3 caracteres, o cuatro guiones (----) si el gui&oacute;n ocupa el espacio de 4 o 5 caracteres. No deje ning&uacute;n espacio en blanco antes ni despu&eacute;s, aunque parezca que en el texto original hay espacios.
    </li>
    <li><i>Palabras o nombres omitidos o censurados a prop&oacute;sito.</i>.
    <br>Si est&aacute;n representadas con un gui&oacute;n en la imagen, revise estas palabras como dos guiones o cuatro guiones tal como se describe en el punto N&deg; 3. Cuando los guiones representan una palabra completa, deje un espacio antes y despu&eacute;s de los guiones, como si se tratase de la verdadera palabra. Si solo se trata de parte de una palabra, no deje espacios entre los guiones y la parte realmente escrita de la palabra.
    </li>
  </ol>
<p>Vea tambi&eacute;n las reglas para los guiones cortos y largos <a href="#eol_hyphen">al final de la l&iacute;nea</a> y <a href="#eop_hyphen">al final de la p&aacute;gina</a>.
</p>
<!-- END RR -->

<p><b>Ejemplos</b>&mdash;Guiones, signo menos y rayas:
</p>

<table width="100%" align="center" border="1" cellpadding="4" cellspacing="0" summary="Hyphens and Dashes examples">
  <tbody>
    <tr>
      <th valign="top" bgcolor="cornsilk">Imagen original:</th>
      <th valign="top" bgcolor="cornsilk">Texto revisado correctamente:</th>
      <th valign="top" bgcolor="cornsilk">Tipo:</th>
    </tr>
    <tr>
      <td valign="top">semi-detached</td>
      <td valign="top"><tt>semi-detached</tt></td>
      <td>Gui&oacute;n de separaci&oacute;n</td>
    </tr>
    <tr>
      <td valign="top">three- and four-part harmony</td>
      <td valign="top"><tt>three- and four-part harmony</tt></td>
      <td>Gui&oacute;n de separaci&oacute;n</td>
    </tr>
    <tr>
      <td valign="top">discoveries which the Crus-<br>
        aders made and brought home with</td>
      <td valign="top"><tt>discoveries which the Crusaders<br>
        made and brought home with</tt></td>
      <td>Gui&oacute;n de separaci&oacute;n</td>
    </tr>
    <tr>
      <td valign="top">factors which mold char-<br>
        acter&mdash;environment, training and heritage,</td>
      <td valign="top"><tt>factors which mold character--environment,<br>
        training and heritage,</tt></td>
      <td>Gui&oacute;n de separaci&oacute;n &amp; gui&oacute;n largo</td>
    </tr>
    <tr>
      <td valign="top">See pages 21&ndash;25</td>
      <td valign="top"><tt>See pages 21-25</tt></td>
      <td>Gui&oacute;n medio</td>
    </tr>
    <tr>
      <td valign="top">It was &ndash;14&deg;C outside.</td>
      <td valign="top"><tt>It was -14&deg;C outside.</tt></td>
      <td>Gui&oacute;n medio</td>
    </tr>
    <tr>
      <td valign="top">X &ndash; Y = Z</td>
      <td valign="top"><tt>X - Y = Z</tt></td>
      <td>Gui&oacute;n medio</td>
    </tr>
    <tr>
      <td valign="top">2&ndash;1/2</td>
      <td valign="top"><tt>2-1/2</tt></td>
      <td>Gui&oacute;n medio</td>
    </tr>
    <tr>
      <td valign="top">&mdash;A plague on both<br> your houses!&mdash;I am dead.</td>
      <td valign="top"><tt>--A plague on both<br> your houses!--I am dead.</tt></td>
      <td>Gui&oacute;n largo</td>
    </tr>
    <tr>
      <td valign="top">sensations&mdash;sweet, bitter, salt, and sour<br>
        &mdash;if even all of these are simple tastes. What</td>
      <td valign="top"><tt>sensations--sweet, bitter, salt, and sour--if<br>
        even all of these are simple tastes. What</tt></td>
      <td>Gui&oacute;n largo</td>
    </tr>
    <tr>
      <td valign="top">senses&mdash;touch, smell, hearing, and sight&mdash;<br>
        with which we are here concerned,</td>
      <td valign="top"><tt>senses--touch, smell, hearing, and sight--with<br>
        which we are here concerned,</tt></td>
      <td>Gui&oacute;n largo</td>
    </tr>
    <tr>
      <td valign="top">It is the east, and Juliet is the sun&mdash;!</td>
      <td valign="top"><tt>It is the east, and Juliet is the sun--!</tt></td>
      <td>Gui&oacute;n largo</td>
    </tr>
    <tr>
      <td valign="top"><img src="../dashes.png" width="300" height="28" alt=""></td>
      <td valign="top"><tt>how a--a--cannon-ball goes----"</tt></td>
      <td>Guiones largos, gui&oacute;n de separaci&oacute;n,<br> &amp; gui&oacute;n largo</td>
    </tr>
    <tr>
      <td valign="top">"Three hundred&mdash;&mdash;" "years," she was going to<br>
        say, but the left-hand cat interrupted her.</td>
      <td valign="top"><tt>"Three hundred----" "years," she was going to<br>
        say, but the left-hand cat interrupted her.</tt></td>
      <td>Gui&oacute;n largo</td>
    </tr>
    <tr>
      <td valign="top">As the witness Mr. &mdash;&mdash; testified,</td>
      <td valign="top"><tt>As the witness Mr. ---- testified,</tt></td>
      <td>Gui&oacute;n largo</td>
    </tr>
    <tr>
      <td valign="top">As the witness Mr. S&mdash;&mdash; testified,</td>
      <td valign="top"><tt>As the witness Mr. S---- testified,</tt></td>
      <td>Gui&oacute;n largo</td>
    </tr>
    <tr>
      <td valign="top">the famous detective of &mdash;&mdash;B Baker St.</td>
      <td valign="top"><tt>the famous detective of ----B Baker St.</tt></td>
      <td>Gui&oacute;n largo</td>
    </tr>
    <tr>
      <td valign="top">&ldquo;You &mdash;&mdash; Yankee&rdquo;, she yelled.</td>
      <td valign="top"><tt>"You ---- Yankee", she yelled.</tt></td>
      <td>Gui&oacute;n largo</td>
    </tr>
    <tr>
      <td valign="top">&ldquo;I am not a d&mdash;d Yankee&rdquo;, he replied.</td>
      <td valign="top"><tt>"I am not a d--d Yankee", he replied.</tt></td>
      <td>Gui&oacute;n largo</td>
    </tr>
  </tbody>
</table>
<p class="backtotop"><a href="#top">Volver al comienzo</a></p>


<h3><a name="eol_hyphen">Gui&oacute;n al final de la l&iacute;nea</a></h3>
<p>Cuando hay un gui&oacute;n que corta la palabra al final de la l&iacute;nea, junte las dos partes de la palabra. Si la palabra normalmente lleva gui&oacute;n (como te&oacute;rico-pr&aacute;ctico), junte sus dos partes dejando el gui&oacute;n donde est&aacute;. Si el gui&oacute;n se puso solamente para partir una palabra que no cabe entera en la l&iacute;nea (y no se trata de una palabra que normalmente lleva gui&oacute;n), junte las dos partes de la palabra y quite el gui&oacute;n. Coloque la palabra unida al final de la l&iacute;nea superior, y corte la l&iacute;nea despu&eacute;s de ella para mantener el salto de l&iacute;nea. Esto facilitar&aacute; el trabajo de los voluntarios que contin&uacute;en la revisi&oacute;n en las siguientes rondas. Si la palabra tiene un signo de puntuaci&oacute;n despu&eacute;s, el signo de puntuaci&oacute;n debe subirse, junto con la palabra, a la l&iacute;nea superior.
</p>
<p>Palabras como "to-day" y "to-morrow", que en la actualidad no llevan gui&oacute;n, aparecen con gui&oacute;n en muchos libros antiguos. Deje estas palabras con gui&oacute;n como el autor las escribi&oacute;. Si no est&aacute; seguro si el autor escribi&oacute; la palabra con gui&oacute;n o sin &eacute;l, conserve el gui&oacute;n con un asterisco  <tt>*</tt> tras &eacute;l, y una la palabra, as&iacute;: <tt>to-*day</tt>. El asterisco llamar&aacute; la atenci&oacute;n del posprocesador, quien tiene acceso a todas las p&aacute;ginas y puede decidir sobre la pauta que sigui&oacute; el autor en la obra.
</p>
<p>De igual manera, si un gui&oacute;n largo (o gui&oacute;n "eme") aparece al principio o al final de una l&iacute;nea en el texto resultado del OCR, debe unirlo con la l&iacute;nea anterior de modo tal que no haya espacios o saltos de l&iacute;nea alrededor del gui&oacute;n. Sin embargo, si el autor utiliz&oacute; un gui&oacute;n largo para comenzar o terminar un p&aacute;rrafo o un verso en una poes&iacute;a, debe dejar el gui&oacute;n tal cual est&aacute;, sin unirlo con otra l&iacute;nea. Consulte <a href="#em_dashes">Guiones, el signo menos "-" y rayas</a> para ver ejemplos.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Volver al comienzo</a></p>


<h3><a name="eop_hyphen">Gui&oacute;n al final de la p&aacute;gina</a></h3>
<p>Cuando encuentre un gui&oacute;n corto o largo al final de una p&aacute;gina, deje el gui&oacute;n donde est&aacute; y agregue un asterisco <tt>*</tt> despu&eacute;s del mismo. Por ejemplo:
</p>
<table width="100%" align="center" border="1" cellpadding="4" cellspacing="0" summary="End-of-page Hyphenation example">
  <tbody>
    <tr><th align="left" bgcolor="cornsilk">Imagen original:</th></tr>
    <tr>
      <td valign="top">something Pat had already become accus-</td>
    </tr>
    <tr><th align="left" bgcolor="cornsilk">Texto revisado correctamente:</th></tr>
    <tr>
      <td valign="top"><tt>something Pat had already become accus-*</tt></td>
    </tr>
  </tbody>
</table>
<p>En p&aacute;ginas que comienzan con parte de una palabra, cortada en la p&aacute;gina anterior, o con un gui&oacute;n largo, coloque un asterisco <tt>*</tt> antes de la palabra cortada o el gui&oacute;n. Continuando con el ejemplo de arriba:
</p>
<table width="100%" align="center" border="1" cellpadding="4" cellspacing="0" summary="Start-of-page Hyphenation example">
  <tbody>
    <tr><th align="left" bgcolor="cornsilk">Imagen original:</th></tr>
    <tr>
      <td valign="top">tomed to from having to do his own family</td>
    </tr>
    <tr><th align="left" bgcolor="cornsilk">Texto revisado correctamente:</th></tr>
    <tr>
      <td valign="top"><tt>*tomed to from having to do his own family</tt></td>
    </tr>
  </tbody>
</table>
<p>Estos asteriscos le indicar&aacute;n al posprocesador que la palabra debe ser unida cuando las p&aacute;ginas sean combinadas para producir el libro electr&oacute;nico final. Por favor no re&uacute;na los fragmentos entre una p&aacute;gina y otra en las rondas de revisi&oacute;n.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Volver al comienzo</a></p>


<h3><a name="period_p">Puntos suspensivos &quot;...&quot;</a></h3>
<p>Las reglas son diferentes para ingl&eacute;s y otros idiomas (LOTE, <i>Languages Other Than English</i>: idiomas distintos del ingl&eacute;s).
</p>
<p><b>INGL&Eacute;S</b>: los puntos suspensivos deber&iacute;an tener tres puntos. En relaci&oacute;n a los espacios, si los puntos suspensivos se encuentran en la mitad de una oraci&oacute;n, debe tratar los puntos suspensivos como si fueran una palabra (es decir, debe dejar un espacio antes y uno despu&eacute;s de los tres puntos). Si se encuentran al final de una oraci&oacute;n, debe tratar los puntos suspensivos como puntuaci&oacute;n final, sin espacios antes.
</p>
<p>Tenga en cuenta que tambi&eacute;n habr&aacute; un signo de puntuaci&oacute;n final para la oraci&oacute;n. Si se trata de un punto, habr&aacute; cuatro puntos en total. Elimine cualquier punto que exista; o a&ntilde;ada los que sean necesarios para llegar a los tres o cuatro puntos requeridos. Una buena pista para saber si uno se encuentra al final de una oraci&oacute;n es el uso de may&uacute;sculas al comienzo de la siguiente palabra, o la existencia de puntuaci&oacute;n que marque el final (como por ejemplo un signo de pregunta o de exclamaci&oacute;n).
</p>
<p><b>IDIOMAS DISTINTOS DEL INGL&Eacute;S (LOTE)</b>: Use la regla principal: "siga lo m&aacute;s exactamente posible el estilo de la p&aacute;gina impresa". Inserte espacios en blanco si es necesario antes o entre los puntos suspensivos, y use el mismo n&uacute;mero de puntos que aparece en la imagen de la p&aacute;gina impresa. A veces la imagen no se ve bien. En ese caso, deje una nota para llamar atenci&oacute;n del posprocesador, as&iacute;: <tt>[**unclear]</tt> o <tt>[**poco claro]</tt>. (Nota: los posprocesadores deber&aacute;n remplazar estos espacios regulares por espacios que no ocasionen quiebres de l&iacute;nea.)
</p>
<!-- END RR -->
<p>Ejemplos para el idioma ingl&eacute;s:
</p>
<table width="100%" align="center" border="1" cellpadding="4" cellspacing="0" summary="Ellipses examples">
  <tbody>
    <tr>
      <th valign="top" bgcolor="cornsilk">Imagen original:</th>
      <th valign="top" bgcolor="cornsilk">Texto revisado correctamente:</th>
    </tr>
    <tr>
      <td valign="top">That I know .&nbsp;.&nbsp;. is true.</td>
      <td valign="top"><tt>That I know ... is true.</tt></td>
    </tr>
    <tr>
      <td valign="top">This is the end....</td>
      <td valign="top"><tt>This is the end....</tt></td>
    </tr>
    <tr>
      <td valign="top">The moving finger writes; and.&nbsp;.&nbsp;. The poet<br> surely had a pen though!</td>
      <td valign="top"><tt>The moving finger writes; and.... The poet<br> surely had a pen though! </tt></td>
    </tr>
    <tr>
      <td valign="top">Wherefore art thou Romeo.&nbsp;.&nbsp;.&nbsp;?</td>
      <td valign="top"><tt>Wherefore art thou Romeo...?</tt></td>
    </tr>
    <tr>
      <td valign="top">&ldquo;I went to the store,&nbsp;.&nbsp;.&nbsp;.&rdquo; said Harry.</td>
      <td valign="top"><tt>"I went to the store, ..." said Harry.</tt></td>
    </tr>
    <tr>
      <td valign="top">&ldquo;... And I did too!&rdquo; said Sally.</td>
      <td valign="top"><tt>"... And I did too!" said Sally.</tt></td>
    </tr>
    <tr>
      <td valign="top">&ldquo;Really?&nbsp;&nbsp;.&nbsp;.&nbsp;. Oh, Harry!&rdquo;</td>
      <td valign="top"><tt>"Really?... Oh, Harry!"</tt></td>
    </tr>
  </tbody>
</table>
<p class="backtotop"><a href="#top">Volver al comienzo</a></p>


<h3><a name="contract">Contracciones</a></h3>
<p>
En ingl&eacute;s, elimine el espacio en blanco de las contracciones. Por ejemplo: <tt>would&nbsp;n't</tt> se revisa como <tt>wouldn't</tt> y <tt>'t&nbsp;is</tt> como <tt>'tis</tt>.
</p>
<p>El espacio en blanco es com&uacute;n en los libros del siglo XIX y se dejaba para indicar que, en principio, se trata de dos palabras diferentes: 'would' y 'not.' A veces lo deja el OCR sin raz&oacute;n aparente. Siempre elimine el espacio en blanco.
</p>
<p>Algunos Gestores de Proyecto (Project Managers) pueden pedir en los <a href="#comments">Comentarios del Proyecto</a> que estos espacios no sean eliminados, especialmente si se trata de textos que contienen argot, dialectos o poes&iacute;a.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Volver al comienzo</a></p>


<h3><a name="fract_s">Fracciones</a></h3>
<p>Revise las fracciones as&iacute;: <tt>&frac14;</tt> se convierte en <tt>1/4</tt>; <tt>2&frac12;</tt> se convierte en <tt>2-1/2</tt>. El gui&oacute;n evita que se separen el n&uacute;mero y la fracci&oacute;n cuando las l&iacute;neas se reordenen durante el el posproceso. A menos que sea especificamente solicitado en los <a href="#comments">Comentarios del Proyecto</a> (Project Comments), por favor no utilice los s&iacute;mbolos fraccionales (&frac14;, 2&frac12;, etc.).
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Volver al comienzo</a></p>


<h3><a name="a_chars">Caracteres acentuados y caracteres no ASCII</a></h3>
<p>Por favor revise este tipo de caracteres utilizando los caracteres UTF-8 apropiados. Para los caracteres que no son parte de Unicode, vea las instrucciones del Gestor del Proyecto en los <a href="#comments">Comentarios del Proyecto</a>. 
Si estos caracteres no se encuentran en su teclado, vea la secci&oacute;n <a href="#insert_char">Insertando Caracteres Especiales</a> para obtener informaci&oacute;n sobre c&oacute;mo colocar estos caracteres mientras revisa una p&aacute;gina.
</p>

<!-- END RR -->
<p class="backtotop"><a href="#top">Volver al comienzo</a></p>


<h3><a name="d_chars">Signos diacr&iacute;ticos</a></h3>
<p>En algunos proyectos encontrar&aacute; signos especiales ubicados encima o debajo del car&aacute;cter latino normal (A-Z). Estos signos se llaman <i>diacr&iacute;ticos</i> y se&ntilde;alan la variaci&oacute;n del valor fon&eacute;tico de la letra.
</p>
<p>Si tal car&aacute;cter no existe en Unicode, deber&iacute;a ser ingresado utilizando <i>marcas diacr&iacute;ticas combinadas</i>. Estos son s&iacute;mbolos de Unicode que no pueden aparecer por s&iacute; solos, sino que aparecen arriba (o debajo) de la letra despu&eacute;s de la cual se colocan. Pueden ser ingresados colocando primero la letra base y, despu&eacute;s, la marca diacr&iacute;tica combinada, usando <i>applets</i> o programas mencionados en <a href="#insert_char">Insertando caracteres especiales</a>. 
</p>
<p>En algunos sistemas, las marcas di&aacute;criticas pueden aparecer desplazadas del lugar donde deber&iacute;an estar. Por ejemplo, movidas a la derecha. Deben utilizarse igualmente, dado que las personas que utilicen otros sistemas podr&aacute;n verlas correctamente. Si, por alguna raz&oacute;n, usted no puede ver o ingresar marcas diacr&iacute;ticas combinadas de manera apropiada, deje una [**nota] junto a la letra en cuesti&oacute;n. Tenga en cuenta que tambi&eacute;n existen <i>caracteres modificadores de espaciado</i>. Estos no deben utilizarse.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Volver al comienzo</a></p>


<h3><a name="f_chars">Caracteres no latinos</a></h3>
<p>Algunos proyectos incluyen fragmentos en caracteres no-latinos, es decir, otros caracteres diferentes del alfabeto A-Z. Por ejemplo, griego, cir&iacute;lico, hebreo o &aacute;rabe.
</p>
<p>Estos caracteres deber&iacute;an ingresarse en el texto de igual modo que los caracteres Latin-1. (<b>&iexcl;Sin transliterarlos!</b>)
</p>
<p>Si un documento est&aacute; escrito en su totalidad en un alfabeto no latino, es mejor instalar un <i>driver</i> para el teclado que soporte el idioma. Consulte el manual de su sistema operativo para saber c&oacute;mo conseguir esto.
</p>
<p>Si el alfabeto de caracteres especiales solo aparece ocasionalmente, puede utilizar un programa diferente para ingresar las palabras. Vea <a href="#insert_char">Insertando caracteres especiales</a> para conocer algunos programas.
</p>
<p>Si no est&aacute; seguro sobre un car&aacute;cter o un acento, m&aacute;rquelo con una [**nota] para llamar la atenci&oacute;n del revisor de la siguiente ronda o del posprocesador. 
</p>
<p>Para alfabetos que no pueden ser ingresados tan sencillamente, como por ejemplo &aacute;rabe, identif&iacute;quelos con la marca apropiada: <tt>[Arabic:&nbsp;**]</tt>. Incluya los asteriscos <tt>**</tt> para que el posprocesador pueda tratar el tema m&aacute;s tarde. 
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Volver al comienzo</a></p>


<h3><a name="supers">Super&iacute;ndices</a></h3>
<p>En los libros antiguos frecuentemente se abreviaban las palabras con contracciones que eran impresas como super&iacute;ndices. Revise este tipo de palabras insertando un acento circunflejo (car&aacute;cter <tt>^</tt>) antes del car&aacute;cter que aparece como super&iacute;ndice. Si m&aacute;s de una letra aparece como super&iacute;ndice, rodee el texto con llaves <tt>{ }</tt>. Por ejemplo:
</p>
<table width="100%" align="center" border="1" cellpadding="4" cellspacing="0" summary="Superscripts example">
  <tbody>
    <tr><th align="left" bgcolor="cornsilk">Imagen original:</th></tr>
    <tr>
      <td valign="top">Gen<sup>rl</sup> Washington defeated L<sup>d</sup> Cornwall's army.</td>
    </tr>
    <tr><th align="left" bgcolor="cornsilk">Texto revisado correctamente:</th></tr>
    <tr>
      <td valign="top"><tt>Gen^{rl} Washington defeated L^d Cornwall's army.</tt></td>
    </tr>
  </tbody>
</table>
<p>Si el super&iacute;ndice representa la marca de una nota al pie, vea la secci&oacute;n de <a href="#footnotes">Notas al pie</a>.
</p>
<p>El Gestor del Proyecto (Project Manager) puede especificar en los <a href="#comments">Comentarios del Proyecto</a> que el texto en super&iacute;ndice sea marcado de manera diferente.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Volver al comienzo</a></p>


<h3><a name="subscr">Sub&iacute;ndices</a></h3>
<p>El texto en sub&iacute;ndice se encuentra en obras cient&iacute;ficas pero no es com&uacute;n en otro tipo de libros. Revise el texto subscrito insertando un gui&oacute;n bajo (car&aacute;cter <tt>_</tt>) y rodeando el texto en sub&iacute;ndice con llaves <tt>{</tt> <tt>}</tt>, as&iacute;:
</p>
<table width="100%" align="center" border="1" cellpadding="4" cellspacing="0" summary="Subscripts example">
  <tbody>
    <tr><th align="left" bgcolor="cornsilk">Imagen original:</th></tr>
    <tr>
      <td valign="top">H<sub>2</sub>O.</td>
    </tr>
    <tr><th align="left" bgcolor="cornsilk">Texto revisado correctamente:</th></tr>
    <tr>
      <td valign="top"><tt>H_{2}O.</tt></td>
    </tr>
  </tbody>
</table>
<!-- END RR -->
<p class="backtotop"><a href="#top">Volver al comienzo</a></p>


<h3><a name="drop_caps">Letras may&uacute;sculas ornamentadas al inicio del p&aacute;rrafo, frase o secci&oacute;n</a></h3>
<p>Revise este tipo de letra ornamentada al inicio de un p&aacute;rrafo, frase o secci&oacute;n como una letra normal. Vea adem&aacute;s la secci&oacute;n de <a href="#chap_head">T&iacute;tulos de cap&iacute;tulos</a>.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Volver al comienzo</a></p>


<h3><a name="small_caps">Versalitas</a></h3>
<p>Por favor revise solamente que la palabra sea correcta cuando se trata de palabras impresas en <span style="font-variant: small-caps">Versalitas</span>. No se preocupe por cambiar de may&uacute;sculas a min&uacute;sculas o viceversa. Si la palabra aparece toda en may&uacute;sculas, toda en min&uacute;scula o con may&uacute;scula solo al principio, d&eacute;jela como est&aacute;. Las versalitas pueden ocasionalmente aparecer con etiquetas <tt>&lt;sc&gt;</tt> y <tt>&lt;/sc&gt;</tt> alrededor; vea las <a href="#formatting">Reglas de Formato</a> en ese caso.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Volver al comienzo</a></p>


<table width="100%" border="0" cellspacing="0" cellpadding="6" summary="Paragraph-level proofreading">
  <tbody>
    <tr>
      <td bgcolor="silver"><h2 style="margin-bottom: 0; margin-top: 0;">Revisando p&aacute;rrafos:</h2></td>
    </tr>
  </tbody>
</table>


<h3><a name="line_br">Saltos de l&iacute;nea</a></h3>
<p>Conservamos los saltos de l&iacute;nea. <b>Deje todos los saltos de l&iacute;nea donde est&aacute;n</b> para que m&aacute;s adelante otros voluntarios puedan comparar f&aacute;cilmente las l&iacute;neas del texto con las de la imagen. Sea especialmente cuidadoso cuando vuelva a unir <a href="#eol_hyphen">palabras cortadas por guiones</a> o mueva palabras alrededor de <a href="#em_dashes">guiones largos</a>. Si el revisor anterior modific&oacute; los saltos de l&iacute;nea, por favor vuelva a colocarlos de manera tal que coincidan con la imagen.
</p>
<!-- END RR -->
<!-- We should have an example right here for this. -->
<p class="backtotop"><a href="#top">Volver al comienzo</a></p>


<h3><a name="chap_head">T&iacute;tulos de cap&iacute;tulo</a></h3>
<p>Los t&iacute;tulos de los cap&iacute;tulos deben quedar como aparecen en la imagen.
</p>
<p>Un t&iacute;tulo de cap&iacute;tulo suele empezar un poco m&aacute;s abajo que el encabezado de la p&aacute;gina, y el n&uacute;mero de p&aacute;gina no suele estar en la misma l&iacute;nea. A menudo, los t&iacute;tulos de cap&iacute;tulo aparecen impresos en may&uacute;sculas. Si es as&iacute;, cons&eacute;rvelos en may&uacute;scula.
</p>
<p>Preste atenci&oacute;n al primer p&aacute;rrafo del cap&iacute;tulo. Puede que el impresor no haya incluido (o el programa de OCR no haya reconocido) las comillas con las que empieza el primer p&aacute;rrafo. Si el autor empieza este p&aacute;rrafo con di&aacute;logo entrecomillado, introduzca las comillas omitidas.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Volver al comienzo</a></p>


<h3><a name="para_space">Espaciado y sangr&iacute;a de p&aacute;rrafos</a></h3>
<p>Coloque una l&iacute;nea en blanco entre los p&aacute;rrafos, incluso a los que aparecen al comienzo de la p&aacute;gina. No agregue sangr&iacute;a a los p&aacute;rrafos; si el p&aacute;rrafo ya aparece con sangr&iacute;a, no pierda tiempo quitando los espacios, eso se hace autom&aacute;ticamente en el posprocesamiento.
</p>
<p>Vea <a href="#para_side">Notas al margen</a> para un ejemplo.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Volver al comienzo</a></p>


<h3><a name="page_hf">Encabezados y pies de p&aacute;gina</a></h3>
<p>Elimine del texto todos los encabezados y pies de p&aacute;gina, pero <em>no</em> elimine las <a href="#footnotes">notas al pie</a> de p&aacute;gina.
</p>
<p>Los encabezados normalmente aparecen al principio de la p&aacute;gina, con el n&uacute;mero de la p&aacute;gina a su lado. Pueden ser iguales para todas las p&aacute;ginas del libro (t&iacute;tulo del libro y el nombre del autor); pueden ser iguales para todas las p&aacute;ginas de un cap&iacute;tulo (normalmente el t&iacute;tulo o n&uacute;mero del cap&iacute;tulo); o pueden ser diferentes en cada p&aacute;gina (una descripci&oacute;n de lo que trata el cap&iacute;tulo). Elim&iacute;nelos todos, sin importar de qu&eacute; clase sean, incluyendo el n&uacute;mero de p&aacute;gina.
</p>
<p>Los pies de p&aacute;gina aparecen al final de la imagen y pueden contener el n&uacute;mero de la p&aacute;gina u otras anotaciones extra&ntilde;as que no son parte de lo que el autor escribi&oacute;.
</p>
<!-- END RR -->

<p>Los <a href="#chap_head">t&iacute;tulos de cap&iacute;tulo</a> empiezan un poco m&aacute;s abajo en la p&aacute;gina y el n&uacute;mero de la p&aacute;gina no est&aacute; en la misma l&iacute;nea. Vea el siguiente ejemplo.
</p>
<table width="100%" align="center" border="1" cellpadding="4" cellspacing="0" summary="Page Headers and Footers">
  <tbody>
    <tr><th align="left" bgcolor="cornsilk">Imagen original:</th></tr>
    <tr align="left">
      <td width="100%" valign="top"><img src="../foot.png" alt="" width="500" height="860"><br>
      </td>
    </tr>
    <tr><th align="left" bgcolor="cornsilk">Texto revisado correctamente:</th></tr>
    <tr>
      <td width="100%" valign="top">
        <tt>In the United States?[*] In a railroad? In a mining company?<br>
        In a bank? In a church? In a college?<br>
        <br>
        Write a list of all the corporations that you know or have<br>
        ever heard of, grouping them under the heads public and private.<br>
        <br>
        How could a pastor collect his salary if the church should<br>
        refuse to pay it?<br>
        <br>
        Could a bank buy a piece of ground "on speculation?" To<br>
        build its banking-house on? Could a county lend money if it<br>
        had a surplus? State the general powers of a corporation.<br>
        Some of the special powers of a bank. Of a city.<br>
        <br>
        A portion of a man's farm is taken for a highway, and he is<br>
        paid damages; to whom does said land belong? The road intersects<br>
        the farm, and crossing the road is a brook containing<br>
        trout, which have been put there and cared for by the farmer;<br>
        may a boy sit on the public bridge and catch trout from that<br>
        brook? If the road should be abandoned or lifted, to whom<br>
        would the use of the land go?<br>
        <br>
        CHAPTER XXXV.<br>
        <br>
        Commercial Paper.<br>
        <br>
        Kinds and Uses.--If a man wishes to buy some commodity<br>
        from another but has not the money to pay for<br>
        it, he may secure what he wants by giving his written<br>
        promise to pay at some future time. This written<br>
        promise, or note, the seller prefers to an oral promise<br>
        for several reasons, only two of which need be mentioned<br>
        here: first, because it is prima facie evidence of<br>
        the debt; and, second, because it may be more easily<br>
        transferred or handed over to some one else.<br>
        <br>
        If J. M. Johnson, of Saint Paul, owes C. M. Jones,<br>
        of Chicago, a hundred dollars, and Nelson Blake, of<br>
        Chicago, owes J. M. Johnson a hundred dollars, it is<br>
        plain that the risk, expense, time and trouble of sending<br>
        the money to and from Chicago may be avoided,<br>
        <br>
        * The United States: "Its charter, the constitution. * * * Its flag the<br>
        symbol of its power; its seal, of its authority."--Dole.
        </tt>
      </td>
    </tr>
  </tbody>
</table>
<p class="backtotop"><a href="#top">Volver al comienzo</a></p>


<h3><a name="illust">Ilustraciones</a></h3>
<p>Revise cualquier texto que acompa&ntilde;e la ilustraci&oacute;n, preservando los saltos de l&iacute;nea. Si la nota que acompa&ntilde;a la ilustraci&oacute;n o imagen se encuentra en medio de un p&aacute;rrafo, sep&aacute;rela del resto del texto del p&aacute;rrafo con una l&iacute;nea en blanco antes y otra despu&eacute;s. Todo el texto que forma parte de la leyenda de la imagen debe ser incluido, como por ejemplo "Ver p&aacute;gina 66" o un t&iacute;tulo que se encuentre dentro de la ilustraci&oacute;n.
</p>
<p>
La mayor&iacute;a de las p&aacute;ginas que contienen solamente una ilustraci&oacute;n sin ning&uacute;n texto asociado aparecen marcadas como <tt>[Blank Page]</tt> (p&aacute;gina en blanco). Deje esta marca tal y como est&aacute;.
</p>
<!-- END RR -->

<table width="100%" align="center" border="1" cellpadding="4" cellspacing="0" summary="Illustration example">
  <tbody>
    <tr>
      <th align="left" bgcolor="cornsilk">Imagen original:</th>
    </tr>
    <tr align="left">
      <td width="100%" valign="top"> <img src="../illust.png" alt="" width="500" height="525"> <br>
      </td>
    </tr>
    <tr>
      <th align="left" bgcolor="cornsilk">Texto revisado correctamente:</th>
    </tr>
    <tr>
      <td width="100%" valign="top">
        <p><tt>Martha told him that he had always been her ideal and<br>
        that she worshipped him.<br>
        <br>
        Frontispiece<br>
        Her Weight in Gold
        </tt></p>
      </td>
    </tr>
  </tbody>
</table>

<br>
<table width="100%" align="center" border="1" cellpadding="4" cellspacing="0" summary="Illustration in Middle of Paragraph">
  <tbody>
    <tr>
      <th align="left" bgcolor="cornsilk">Imagen original (ilustraci&oacute;n en el medio de un p&aacute;rrafo):</th>
    </tr>
    <tr align="left">
      <td width="100%" valign="top"> <img src="../illust2.png" alt="" width="500" height="514"> <br>
      </td>
    </tr>
    <tr>
      <th align="left" bgcolor="cornsilk">Texto revisado correctamente:</th>
    </tr>
    <tr valign="top">
      <td>
        <p><tt>
        such study are due to Italians. Several of these instruments<br>
        have already been described in this journal, and on the present<br>
        </tt></p>
        <p><tt>FIG. 1.--APPARATUS FOR THE STUDY OF HORIZONTAL<br>
        SEISMIC MOVEMENTS.</tt></p>
        <p><tt>
        occasion we shall make known a few others that will<br>
        serve to give an idea of the methods employed.<br>
        </tt></p>
        <p><tt>
        For the observation of the vertical and horizontal motions<br>
        of the ground, different apparatus are required. The</tt>
        </p>
      </td>
    </tr>
  </tbody>
</table>
<p class="backtotop"><a href="#top">Volver al comienzo</a></p>


<h3><a name="footnotes">Notas al pie de p&aacute;gina y notas al final</a></h3>
<p>Revise las notas al pie dejando el texto de la nota al final de la p&aacute;gina y colocando una etiqueta en el lugar en que la nota est&aacute; referenciada en el texto.
</p>
<p>
En el texto principal, el car&aacute;cter que marca la ubicaci&oacute;n de la nota al pie debe ser rodeado con corchetes <tt>[ ]</tt> y colocado junto a la palabra en la que est&aacute; la referencia<tt>[1]</tt> o a la puntuaci&oacute;n que la acompa&ntilde;a,<tt>[2]</tt> tal como se muestra en la imagen y en los dos ejemplos de esta oraci&oacute;n. Las referencias de las notas al pie pueden ser n&uacute;meros, letras o s&iacute;mbolos. Cuando las notas al pie est&aacute;n indicadas con s&iacute;mbolos *, &dagger;, &Dagger;, &sect;, etc.), los remplazamos con <tt>[*]</tt> en el texto y <tt>*</tt> al comienzo de la nota al pie.
</p>
<p>Al final de la p&aacute;gina, revise el texto de la nota al pie compar&aacute;ndolo con el texto original y preservando los saltos de l&iacute;nea. Utilice el mismo tipo de marca para la referencia a la nota al pie en el texto principal y la nota al pie misma. Use solamente el car&aacute;cter del s&iacute;mbolo para la referencia, sin corchetes ni ninguna otra marca.
</p>
<p>Ponga cada nota al pie en una nueva l&iacute;nea por orden de aparici&oacute;n, con una l&iacute;nea en blanco antes de cada una.
</p>
<!-- END RR -->
<p>No incluya ning&uacute;n car&aacute;cter ajeno para separar las notas al pie de texto principal. Solamente deje una l&iacute;nea en blanco.
</p>
<p>
Las <b>notas al final</b> son similares a las notas al pie pero han sido colocadas al final del cap&iacute;tulo o al final del libro. Se revisan de la misma manera que las notas al pie. Cuando encuentre una referencia a una nota al final en el texto, solamente col&oacute;quela entre <tt>[ ]</tt>. Si usted est&aacute; revisando una de las p&aacute;ginas donde se encuentran las notas al final, coloque una l&iacute;nea en blanco antes de cada nota de manera tal que sea claro cuando cada una comienza y finaliza.
</p>
<!-- Need an example of Endnotes, maybe? Good idea!-->

<p>
Las notas al pie que forman parte de una <a href="#tables">tabla</a> deben ser conservadas en el lugar preciso en el que se encuentran en la imagen original.
</p>

<table width="100%" border="1" cellpadding="4" cellspacing="0" align="center" summary="Footnote Example">
  <tbody>
    <tr>
      <th valign="top" align="left" bgcolor="cornsilk">Imagen original:</th>
    </tr>
    <tr>
      <td valign="top">
        The principal persons involved in this argument were Caesar*, former military<br>
        leader and Imperator, and the orator Cicero&dagger;. Both were of the aristocratic<br>
        (Patrician) class, and were quite wealthy.<br>
        <hr align="left" width="25%" noshade size="2">
        <font size=-1>* Gaius Julius Caesar.</font><br>
        <font size=-1>&dagger; Marcus Tullius Cicero.</font>
      </td>
    </tr>
    <tr>
      <th valign="top" align="left" bgcolor="cornsilk">Texto revisado correctamente:</th>
    </tr>
    <tr valign="top">
      <td>
        <tt>The principal persons involved in this argument were Caesar[*], former military</tt><br>
        <tt>leader and Imperator, and the orator Cicero[*]. Both were of the aristocratic</tt><br>
        <tt>(Patrician) class, and were quite wealthy.</tt><br>
        <br>
        <tt>* Gaius Julius Caesar.</tt><br>
        <br>
        <tt>* Marcus Tullius Cicero.</tt>
      </td>
    </tr>
  </tbody>
</table>
<br>
<table width="100%" align="center" border="1" cellpadding="4" cellspacing="0" summary="Footnote Example">
  <tbody>
    <tr><th align="left" bgcolor="cornsilk">Imagen original de poes&iacute;a con notas al pie:</th></tr>
    <tr>
      <td valign="top">
        Mary had a little lamb<sup>1</sup><br>
        &nbsp;&nbsp;&nbsp;Whose fleece was white as snow<br>
        And everywhere that Mary went<br>
        &nbsp;&nbsp;&nbsp;The lamb was sure to go!<br>
        <hr align="left" width="10%" noshade size=2>
        <font size=-2><sup>1</sup> This lamb was obviously of the Hampshire breed,<br>
        well known for the pure whiteness of their wool.</font>
      </td>
    </tr>
    <tr><th align="left" bgcolor="cornsilk">Texto revisado correctamente:</th></tr>
    <tr>
      <td valign="top">
        <tt>
        Mary had a little lamb[1]<br>
        Whose fleece was white as snow<br>
        And everywhere that Mary went<br>
        The lamb was sure to go!<br>
        <br>
        1 This lamb was obviously of the Hampshire breed,<br>
        well known for the pure whiteness of their wool.<br>
        </tt>
      </td>
    </tr>
  </tbody>
</table>
<p class="backtotop"><a href="#top">Volver al comienzo</a></p>


<h3><a name="para_side">Notas al margen</a></h3>
<p>
En algunos libros existen breves descripciones del p&aacute;rrafo al margen del texto. Revise el texto de la nota al margen compar&aacute;ndolo con el texto original, preservando los saltos de l&iacute;nea (y tratando los <a href="#eol_hyphen">gu&iacute;ones al final de la l&iacute;nea</a> de manera habitual). Deje una l&iacute;nea en blanco antes y despu&eacute;s de la nota al margen para que esta se pueda distinguir del resto del texto. Es posible que el programa de OCR haya colocado el texto de la nota en cualquier parte de la p&aacute;gina, e incluso puede haberlo mezclado con el resto del texto de la p&aacute;gina. Separe el texto de la nota del resto del texto, reuniendo el texto completo de la nota al margen, pero no se preocupe del lugar donde se encuentra la nota al margen en la p&aacute;gina.
</p>
<!-- END RR -->

<table width="100%" align="center" border="1" cellpadding="4" cellspacing="0" summary="Sidenotes example">
  <tbody>
    <tr valign="top">
      <th align="left" bgcolor="cornsilk">Imagen original:</th>
    </tr>
    <tr valign="top">
      <td width="100%" align="left"><img src="../side.png" alt="" width="550" height="800"><br>
      </td>
    </tr>
    <tr valign="top">
      <th align="left" bgcolor="cornsilk">Texto revisado correctamente:</th>
    </tr>
    <tr valign="top">
      <td width="100%">
        <p><tt>
        Burning<br>
        discs<br>
        thrown into<br>
        the air.<br>
        <br>
        that such as looked at the fire holding a bit of larkspur<br>
        before their face would be troubled by no malady of the<br>
        eyes throughout the year.[1] Further, it was customary at<br>
        W&uuml;rzburg, in the sixteenth century, for the bishop's followers<br>
        to throw burning discs of wood into the air from a mountain<br>
        which overhangs the town. The discs were discharged by<br>
        means of flexible rods, and in their flight through the darkness<br>
        presented the appearance of fiery dragons.[2]<br>
        <br>
        The Midsummer<br>
        fires in<br>
        Swabia.<br>
        <br>
        In the valley of the Lech, which divides Upper Bavaria<br>
        from Swabia, the midsummer customs and beliefs are, or<br>
        used to be, very similar. Bonfires are kindled on the<br>
        mountains on Midsummer Day; and besides the bonfire<br>
        a tall beam, thickly wrapt in straw and surmounted by a<br>
        cross-piece, is burned in many places. Round this cross as<br>
        it burns the lads dance with loud shouts; and when the<br>
        flames have subsided, the young people leap over the fire in<br>
        pairs, a young man and a young woman together. If they<br>
        escape unsmirched, the man will not suffer from fever, and<br>
        the girl will not become a mother within the year. Further,<br>
        it is believed that the flax will grow that year as high as<br>
        they leap over the fire; and that if a charred billet be taken<br>
        from the fire and stuck in a flax-field it will promote the<br>
        growth of the flax.[3] Similarly in Swabia, lads and lasses,<br>
        hand in hand, leap over the midsummer bonfire, praying<br>
        that the hemp may grow three ells high, and they set fire<br>
        to wheels of straw and send them rolling down the hill.<br>
        Among the places where burning wheels were thus bowled<br>
        down hill at Midsummer were the Hohenstaufen mountains<br>
        in Wurtemberg and the Frauenberg near Gerhausen.[4]<br>
        At Deffingen, in Swabia, as the people sprang over the mid-*<br>
        <br>
        Omens<br>
        drawn from<br>
        the leaps<br>
        over the<br>
        fires.<br>
        <br>
        Burning<br>
        wheels<br>
        rolled<br>
        down hill.<br>
        <br>
        1 Op. cit. iv. 1. p. 242. We have<br>
        seen (p. 163) that in the sixteenth<br>
        century these customs and beliefs were<br>
        common in Germany. It is also a<br>
        German superstition that a house which<br>
        contains a brand from the midsummer<br>
        bonfire will not be struck by lightning<br>
        (J. W. Wolf, Beitr&auml;ge zur deutschen<br>
        Mythologie, i. p. 217, &sect; 185).<br>
        <br>
        2 J. Boemus, Mores, leges et ritus<br>
        omnium gentium (Lyons, 1541), p.<br>
        226.<br>
        <br>
        3 Karl Freiherr von Leoprechting,<br>
        Aus dem Lechrain (Munich, 1855),<br>
        pp. 181 sqq.; W. Mannhardt, Der<br>
        Baumkultus, p. 510.<br>
        <br>
        4 A. Birlinger, Volksth&uuml;mliches aus<br>
        Schwaben (Freiburg im Breisgau, 1861-1862),<br>
        ii. pp. 96 sqq., &sect; 128, pp. 103<br>
        sq., &sect; 129; id., Aus Schwaben (Wiesbaden,<br>
        1874), ii. 116-120; E. Meier,<br>
        Deutsche Sagen, Sitten und Gebr&auml;uche<br>
        aus Schwaben (Stuttgart, 1852), pp.<br>
        423 sqq.; W. Mannhardt, Der Baumkultus,<br>
        p. 510.<br>
        </tt></p>
      </td>
    </tr>
  </tbody>
</table>
<p class="backtotop"><a href="#top">Volver al comienzo</a></p>


<h3><a name="mult_col">M&uacute;ltiples Columnas</a></h3>
<p>Cuando se encuentra con texto normal que ha sido impreso en dos columnas, coloque el texto de la columna izquierda primero y el texto de la comuna derecha debajo. No es necesario que marque donde fueron separadas las columnas. Vea el fragmento final en los ejemplos para <a href="#para_side">notas al margen</a> para ver c&oacute;mo se revisan textos en m&uacute;ltiples columnas.
</p>
<p>Revise adem&aacute;s las secciones de <a href="#bk_index">&Iacute;ndice</a> y <a href="#tables">Tablas</a> de estas Reglas de Revisi&oacute;n.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Volver al comienzo</a></p>


<h3><a name="tables">Tablas</a></h3>
<p>La tarea del revisor es asegurar que toda la informaci&oacute;n en la tabla est&eacute; correctamente escrita. Separe los distintos &iacute;tems de la tabla como sea necesario, pero no se preocupe de alinearlos con precisi&oacute;n. Mantenga los saltos del l&iacute;nea (y trate los <a href="#eol_hyphen">guiones al final de la l&iacute;nea</a> de manera habitual). Ignore los puntos u otros s&iacute;mbolos que puedan haberse usado para dar formato a la tabla.
</p>
<p>
Las <b>notas al pie</b> que aparecen dentro de una tabla deben conservarse en el lugar preciso en el que se encuentran. Vea la secci&oacute;n <a href="#footnotes">Notas al pie</a> para m&aacute;s detalles.
</p>
<!-- END RR -->
<table width="100%" align="center" border="1" cellpadding="4" cellspacing="0" summary="Table Example">
  <tbody>
    <tr><th align="left" bgcolor="cornsilk">Imagen original:</th></tr>
    <tr align="left">
      <td width="100%" valign="top"> <img src="../table2.png" alt="" width="500" height="304"><br>
      </td>
    </tr>
    <tr><th align="left" bgcolor="cornsilk">Texto revisado correctamente:</th></tr>
    <tr>
      <td width="100%" valign="top">
<pre><tt>TABLE II.

Flat strips compared    Copper.                            Copper.
with round wire 30 cm.       Iron.  Parallel wires 30 cm. in     Iron.
in length.                                 length.

Wire 1 mm. diameter   20  100  Wire 1 mm. diameter   20  100

        STRIPS.                 SINGLE WIRE.
0.25 mm. thick, 2 mm.
  wide           ...... 15  35      0.25 mm. diameter ....  16   48
Same, 5 mm. wide  ....     13  20  Two similar wires  ...... 12  30
 "   10  "    "           11   15    Four    "    "     9   18
 "   20    "    "         10  14      Eight  "    "     8   10
 "   40  "    "          9   13     Sixteen "    "     7    6
Same strip rolled up in           Same, 16 wires bound
  the form of wire  .. 17   15    close together .....  18    12
</tt></pre>
      </td>
    </tr>
  </tbody>
</table>
<br>
<table width="100%" align="center" border="1" cellpadding="4" cellspacing="0" summary="Table Example">
  <tbody>
    <tr><th align="left" bgcolor="cornsilk">Imagen original:</th></tr>
    <tr align="left">
      <td width="100%" valign="top"> <img src="../table3.png" alt="" width="480" height="231"><br>
      </td>
    </tr>
    <tr><th align="left" bgcolor="cornsilk">Texto revisado correctamente:</th></tr>
    <tr>
      <td width="100%" valign="top">
<pre><tt>Agents.   Objects.
        {     1st person, I,       me,
            {  2d   "    thou,   thee,
Singular  {      "  mas.  {  he,   him,
           {  3d  "  fem.  {  she,   her,
         {            it,        it.

       {  1st person, we,         us,
Plural   {   2d   "  ye, or you,   you,
        {  3d  "   they,         them,
                  who,       whom.
</tt></pre>
      </td>
    </tr>
  </tbody>
</table>
<p class="backtotop"><a href="#top">Volver al comienzo</a></p>


<h3><a name="poetry">Poes&iacute;a o epigramas</a></h3>
<p>
Introduzca una l&iacute;nea en blanco al comienzo de la poes&iacute;a o epigrama y otra al final, para que los formateadores puedan ver claramente d&oacute;nde empieza y d&oacute;nde termina el formato especial. Todo el texto debe ser alineado a la izquierda y los saltos de l&iacute;nea deben mantenerse. Coloque una l&iacute;nea en blanco entre las estrofas, si existe una en la imagen.
</p>
<p>
Los <a href="#line_no">n&uacute;meros de l&iacute;nea</a> en poes&iacute;a deben ser conservados y revisados.
</p>
<p>
Revise los <a href="#comments">Comentarios del Proyecto</a> en caso de que exista alguna instrucci&oacute;n especial.
</p>
<!-- END RR -->

<table width="100%" align="center" border="1" cellpadding="4" cellspacing="0" summary="Poetry Example">
  <tbody>
    <tr><th align="left" bgcolor="cornsilk">Imagen original:</th></tr>
    <tr align="left">
      <td width="100%" valign="top"> <img src="../poetry2.png" alt="" width="480" height="385"> <br>
      </td>
    </tr>
    <tr><th align="left" bgcolor="cornsilk">Texto revisado correctamente:</th></tr>
    <tr>
      <td width="100%" valign="top">
        <tt>THE CHAMBERED NAUTILUS<br>
        <br>
        This is the ship of pearl which, poets<br>
        feign,<br>
        Sails the unshadowed main,--<br>
        The venturous bark that flings<br>
        On the sweet summer wind its purpled<br>
        wings<br>
        In gulfs enchanted, where the Siren sings<br>
        And coral reefs lie bare,<br>
        Where the cold sea maids rise to sun<br>
        their streaming hair.<br>
        <br>
        Its webs of living gauze no more unfurl;<br>
        Wrecked is the ship of pearl!</tt>
      </td>
    </tr>
  </tbody>
</table>
<p class="backtotop"><a href="#top">Volver al comienzo</a></p>


<h3><a name="line_no">N&uacute;meros de l&iacute;nea</a></h3>
<p>Los n&uacute;meros de l&iacute;nea son comunes en libros de poes&iacute;a y, usualmente, aparecen cerca del margen cada cinco o diez l&iacute;neas. Conserve los n&uacute;meros de l&iacute;nea, utilizando algunos espacios para separarlos del texto principal de la l&iacute;nea de manera tal que los formateadores puedan encontrarlos. Dado que las l&iacute;neas de la poes&iacute;a se mantendr&aacute;n tan cual en el libro electr&oacute;nico final, los n&uacute;meros de l&iacute;nea puede ser &uacute;tiles para los lectores.
</p>
<!-- END RR -->
<!-- We need an example image and text for this. -->
<p class="backtotop"><a href="#top">Volver al comienzo</a></p>


<h3><a name="next_word">Palabra suelta al final de la p&aacute;gina</a></h3>
<p>Esta palabra debe ser eliminada, incluso si se trata de la segunda mitad de una palabra cortada con un gui&oacute;n.
</p>
<p>En algunos libros antiguos, la palabra suelta al final de la p&aacute;gina (usualmente impresa cerca del margen derecho) indica la primera palabra de la p&aacute;gina siguiente del libro. Esto se utilizaba para que el impresor tuviese cuidado de imprimir la contracara correcta, para ayudar a los ayudantes del impresor a armar las p&aacute;ginas antes de la encuadernaci&oacute;n y para ayudar al lector a no saltarse una p&aacute;gina al leer.
</p>
<!-- END RR -->
<!-- We need an example here! -->
<p class="backtotop"><a href="#top">Volver al comienzo</a></p>


<table width="100%" border="0" cellspacing="0" cellpadding="6" summary="Page-level proofreading">
  <tbody>
    <tr>
      <td bgcolor="silver"><h2 style="margin-bottom: 0; margin-top: 0;">Revisando p&aacute;ginas:</h2></td>
    </tr>
  </tbody>
</table>


<h3><a name="blank_pg">P&aacute;gina en blanco</a></h3>
<p>
La mayor&iacute;a de las p&aacute;ginas en blanco, o p&aacute;ginas con una ilustraci&oacute;n y sin texto, ya est&aacute;n marcadas con <tt>[Blank Page]</tt>. D&eacute;jelas como est&aacute;n. Si la p&aacute;gina est&aacute; en blanco y no est&aacute; marcada con <tt>[Blank Page]</tt> no es necesario a&ntilde;adir esta marca.
</p>
<p>Si el texto est&aacute; donde normalmente tiene que estar pero la imagen de la p&aacute;gina del libro original est&aacute; en blanco, o si la imagen est&aacute; pero no aparece el texto, siga las instrucciones para <a href="#bad_image">Im&aacute;genes de baja calidad</a> o <a href="#bad_text">Imagen que no corresponde al texto</a>, respectivamente.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Volver al comienzo</a></p>


<h3><a name="title_pg">Portada y contraportada</a></h3>
<p>Revise todo el texto como se ve en la imagen de la p&aacute;gina, sean las letras may&uacute;sculas o min&uacute;sculas, etc., incluyendo el a&ntilde;o de publicaci&oacute;n y los derechos del autor.
</p>
<p>En los libros antiguos frecuentemente la primera letra es grande y ornamentada, rev&iacute;sela como una letra normal.
</p>
<!-- END RR -->

<table width="100%" align="center" border="1" cellpadding="4" cellspacing="0" summary="Title Page Example">
  <tbody>
    <tr>
      <th align="left" bgcolor="cornsilk">Imagen original:</th>
    </tr>
    <tr align="left">
      <td width="100%" valign="top"><img src="../title.png" width="500" height="520" alt=""><br>
      </td>
    </tr>
    <tr>
      <th align="left" bgcolor="cornsilk">Texto revisado correctamente:</th>
    </tr>
    <tr>
      <td width="100%" valign="top">
        <p><tt>GREEN FANCY</tt>
        </p>
        <p><tt>BY</tt></p>
        <p><tt>GEORGE BARR McCUTCHEON</tt></p>
        <p><tt>AUTHOR OF "GRAUSTARK," "THE HOLLOW OF HER HAND,"<br>
        "THE PRINCE OF GRAUSTARK," ETC.</tt></p>
        <p><tt>WITH FRONTISPIECE BY<br>
        C. ALLAN GILBERT</tt></p>
        <p><tt>NEW YORK<br>
        DODD, MEAD AND COMPANY<br>
        1917</tt></p>
      </td>
    </tr>
  </tbody>
</table>
<p class="backtotop"><a href="#top">Volver al comienzo</a></p>


<h3><a name="toc">Tabla de contenidos</a></h3>
<p>Revise la tabla de contenidos exactamente como est&aacute; en el libro, respetando las may&uacute;sculas y min&uacute;sculas. Si se encuentra con <span style="font-variant: small-caps">Versalitas</a>, revise la secci&oacute;n de <a href="#small_caps">Versalitas</a>. Conserve los n&uacute;meros de p&aacute;gina.
</p>
<p>Haga caso omiso de los puntos o asteriscos utilizados para alinear los n&uacute;meros de las p&aacute;ginas. Estos ser&aacute;n eliminados m&aacute;s adelante, durante las rondas de formato.
</p>
<!-- END RR -->

<table width="100%" align="center" border="1" cellpadding="4" cellspacing="0" summary="Table of Contents example">
  <tbody>
    <tr>
      <th align="left" bgcolor="cornsilk">Imagen original:</th>
    </tr>
    <tr align="left">
      <td width="100%" valign="top"><img src="../tablec.png" alt="" width="500" height="650">
      </td>
    </tr>
    <tr>
      <th align="left" bgcolor="cornsilk">Texto revisado correctamente:</th>
    </tr>
    <tr>
      <td width="100%" valign="top">
        <p><tt>CONTENTS</tt></p>
        <p><tt>
        CHAPTER&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;PAGE<br>
        <br>
        I. THE FIRST WAYFARER AND THE SECOND WAYFARER<br>
        MEET AND PART ON THE HIGHWAY&nbsp;&nbsp;.....&nbsp;1<br>
        <br>
        II.  THE FIRST WAYFARER LAYS HIS PACK ASIDE AND<br>
        FALLS IN WITH FRIENDS&nbsp;&nbsp;....&nbsp;...&nbsp;15<br>
        <br>
        III. MR. RUSHCROFT DISSOLVES, MR. JONES INTERVENES,<br>
        AND TWO MEN RIDE AWAY&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;33<br>
        <br>
        IV. AN EXTRAORDINARY CHAMBERMAID, A MIDNIGHT<br>
        TRAGEDY, AND A MAN WHO SAID "THANK YOU"&nbsp;&nbsp;&nbsp;50<br>
        <br>
        V. THE FARM-BOY TELLS A GHASTLY STORY, AND AN<br>
        IRISHMAN ENTERS&nbsp;&nbsp;..&nbsp;&nbsp;..&nbsp;67<br>
        <br>
        VI. CHARITY BEGINS FAR FROM HOME, AND A STROLL IN<br>
        THE WILDWOOD FOLLOWS&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;85<br>
        <br>
        VII. SPUN-GOLD HAIR, BLUE EYES, AND VARIOUS ENCOUNTERS&nbsp;&nbsp;...&nbsp;&nbsp;&nbsp;103<br>
        <br>
        VIII. A NOTE, SOME FANCIES, AND AN EXPEDITION IN<br>
        QUEST OF FACTS&nbsp;&nbsp;..&nbsp;,,&nbsp;120<br>
        <br>
        IX. THE FIRST WAYFARER, THE SECOND WAYFARER, AND<br>
        THE SPIRIT OF CHIVALRY ASCENDANT&nbsp;&nbsp;&nbsp;,&nbsp;&nbsp;134<br>
        <br>
        X. THE PRISONER OF GREEN FANCY, AND THE LAMENT OF<br>
        PETER THE CHAUFFEUR&nbsp;...&nbsp;&nbsp;&nbsp;....148<br>
        <br>
        XI. MR. SPROUSE ABANDONS LITERATURE AT AN EARLY<br>
        HOUR IN THE MORNING&nbsp;..&nbsp;&nbsp;...&nbsp;&nbsp;,&nbsp;167<br>
        <br>
        XII. THE FIRST WAYFARER ACCEPTS AN INVITATION, AND<br>
        MR. DILLINGFORD BELABORS A PROXY&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;183<br>
        <br>
        XIII. THE SECOND WAYFARER RECEIVES TWO VISITORS AT<br>
        MIDNIGHT &nbsp;,,,..&nbsp;&nbsp;....&nbsp;199<br>
        <br>
        XIV. A FLIGHT, A STONE-CUTTER'S SHED, AND A VOICE<br>
        OUTSIDE&nbsp;&nbsp;&nbsp;,,,..&nbsp;&nbsp;....,&nbsp;221</tt></p>
      </td>
    </tr>
  </tbody>
</table>
<p class="backtotop"><a href="#top">Volver al comienzo</a></p>


<h3><a name="bk_index">&Iacute;ndices</a></h3>
<p>No es necesario alinear los n&uacute;meros de p&aacute;gina en el &iacute;ndice para imitar la imagen; solo aseg&uacute;rese de que los n&uacute;meros y la puntuaci&oacute;n sean id&eacute;nticos a los de la imagen y conserve los saltos de l&iacute;nea.
</p>
<p>El formato espec&iacute;fico del &iacute;ndice se realizar&aacute; despu&eacute;s durante las rondas de formato. El trabajo del revisor es solamente asegurarse de que el texto y los n&uacute;meros sean correctos.
</p>
<p>Revise tambi&eacute;n la secci&oacute;n <a href="#mult_col">Columnas m&uacute;ltiples</a>.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Volver al comienzo</a></p>


<h3><a name="play_n">Obras de teatro: nombres de personajes y directrices esc&eacute;nicas</a></h3>
<p>En los di&aacute;logos, trate cada cambio de personaje como un nuevo p&aacute;rrafo, con una l&iacute;nea en blanco previa al mismo. De igual manera, si el nombre del personaje est&aacute; en su propia l&iacute;nea, tr&aacute;telo como un p&aacute;rrafo diferente.
</p>
<p>Las directrices esc&eacute;nicas se mantienen tal como est&aacute;n en la imagen original. Si se encuentran en una l&iacute;nea nueva, col&oacute;quelas en una l&iacute;nea nueva; si se encuentran al final de una l&iacute;nea de di&aacute;logo, cons&eacute;rvelas ah&iacute;. Las directrices esc&eacute;nicas a menudo comienzan con un corchete [ de apertura y omiten el corchete de cierre. Esta convenci&oacute;n se conserva: no agregue el corchete de cierre.
</p>
<p>A veces, especialmente si se trata de drama en verso, una palabra puede aparecer cortada y con gui&oacute;n debido a la limitaci&oacute;n que plantea el tama&ntilde;o de la p&aacute;gina, o bien aparecer encima o debajo de la l&iacute;nea despu&eacute;s de un par&eacute;ntesis (. Esta palabra debe unirse de acuerdo a las normas respecto a <a href="#eol_hyphen">guiones al final de la l&iacute;nea</a>. Vea el <a href="#play4">ejemplo</a>.
</p>
<p>Por favor, lea los <a href="#comments">Comentarios del Proyecto</a> para verificar si el Gestor del Proyecto (Project Manager) ha dado instrucciones espec&iacute;ficas respecto al tratamiento de este tema.
</p>
<!-- END RR -->

<table width="100%" align="center" border="1" cellpadding="4" cellspacing="0" summary="Play Example 1">
  <tbody>
    <tr>
      <th align="left" bgcolor="cornsilk">Imagen original:</th>
    </tr>
    <tr align="left">
      <td width="100%" valign="top"><img src="../play1.png" width="500" height="430" alt=""><br>
      </td>
    </tr>
    <tr>
      <th align="left" bgcolor="cornsilk">Texto revisado correctamente:</th>
    </tr>
    <tr>
      <td width="100%" valign="top">
        <p><tt>
        Has not his name for nought, he will be trode upon:<br>
        What says my Printer now?
        </tt></p><p><tt>
        Clow. Here's your last Proof, Sir.<br>
        You shall have perfect Books now in a twinkling.
        </tt></p><p><tt>
        Lap. These marks are ugly.
        </tt></p><p><tt>
        Clow. He says, Sir, they're proper:<br>
        Blows should have marks, or else they are nothing worth.
        </tt></p><p><tt>
        La. But why a Peel-crow here?
        </tt></p><p><tt>
        Clow. I told 'em so Sir:<br>
        A scare-crow had been better.
        </tt></p><p><tt>
        Lap. How slave? look you, Sir,<br>
        Did not I say, this Whirrit, and this Bob,<br>
        Should be both Pica Roman.
        </tt></p><p><tt>
        Clow. So said I, Sir, both Picked Romans,<br>
        And he has made 'em Welch Bills,<br>
        Indeed I know not what to make on 'em.
        </tt></p><p><tt>
        Lap. Hay-day; a Souse, Italica?
        </tt></p><p><tt>
        Clow. Yes, that may hold, Sir,<br>
        Souse is a bona roba, so is Flops too.</tt></p>
      </td>
    </tr>
  </tbody>
</table>
<br>
<a name="play4"><!-- Example --></a>
<table width="100%" align="center" border="1" cellpadding="4" cellspacing="0" summary="Play Example 4">
  <tbody>
    <tr>
      <th align="left" bgcolor="cornsilk">Imagen original:</th>
    </tr>
    <tr align="left">
      <td width="100%" valign="top"><img src="../play4.png" width="502" height="98" alt=""><br>
      </td>
    </tr>
    <tr>
      <th align="left" bgcolor="cornsilk">Texto revisado correctamente:</th>
    </tr>
    <tr>
      <td width="100%" valign="top">
        <p><tt>
        Am. Sure you are fasting;<br>
        Or not slept well to night; some dream (Ismena?)<br>
        <br>
        Ism. My dreams are like my thoughts, honest and innocent,<br>
        Yours are unhappy; who are these that coast us?<br>
        You told me the walk was private.<br>
        </tt></p>
      </td>
    </tr>
  </tbody>
</table>
<p class="backtotop"><a href="#top">Volver al comienzo</a></p>


<h3><a name="anything">Cualquier otra cosa que necesite tratamiento especial o no sepa como tratar</a></h3>
<p>Si se encuentra con algo que no est&aacute; explicado en estas reglas de revisi&oacute;n o que usted cree que puede necesitar un tratamiento especial o no est&aacute; seguro de c&oacute;mo revisarlo, publique su pregunta en la <a href="#forums">Discusi&oacute;n del Proyecto</a> (Project Discussion), dando cuenta del n&uacute;mero de png (p&aacute;gina).
</p>
<p>Usted deber&iacute;a agregar tambi&eacute;n una nota en el texto para explicar el problema o la pregunta al siguiente revisor, al formateador o al posprocesador. Comience su nota con corchete y dos asteriscos <tt>[**</tt> y coloque un corchete de cierre al final <tt>]</tt>. De esta manera, quedar&aacute; claramente separada del texto del autor y llamar&aacute; la atenci&oacute;n del posprocesador para que revise cuidadosamente esta parte del texto. Usted puede identificar tambi&eacute;n en qu&eacute; ronda se coloc&oacute; la nota de manera tal que los voluntarios que trabajen despu&eacute;s de usted en esa p&aacute;gina sepan qui&eacute;n dejo la nota. Todos los comentarios de los revisores que trabajaron en la p&aacute;gina antes de usted <b>deben</b> ser conservados. Vea la secci&oacute;n siguiente para m&aacute;s detalles.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Volver al comienzo</a></p>


<h3><a name="prev_notes">Notas y comentarios de revisores anteriores</a></h3>
<p>Es <b>imprescindible</b> que cualquier nota o comentario dejado por un revisor anterior se deje tal y como est&aacute;. Puede a&ntilde;adir un acuerdo o desacuerdo a la nota existente, pero, aunque sepa la respuesta, de ninguna manera elimine la nota o comentario. Si ha encontrado una fuente que clarifica el problema, por favor c&iacute;telo para que el posprocesador pueda consultarlo.
</p>
<p>Si encuentra una nota de un voluntario de una ronda anterior y sabe la respuesta, por favor, t&oacute;mese un momento para enviarle un mensaje privado explicando c&oacute;mo tratar la situaci&oacute;n en el futuro. Por favor, como ya se ha dicho, no elimine la nota original.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Volver al comienzo</a></p>


<table width="100%" border="0" cellspacing="0" cellpadding="6" summary="Common Problems">
  <tbody>
    <tr>
      <td bgcolor="silver"><h2 style="margin-bottom: 0; margin-top: 0;">Problemas comunes:</h2></td>
    </tr>
  </tbody>
</table>


<h3><a name="formatting">Formato</a></h3>
<p>Ocasionalmente usted se puede encontrar con atributos de formato presentes en el texto. <b>No agregue nada ni corrija esta informaci&oacute;n de formato</b>; los formateadores cumplir&aacute;n con esa tarea m&aacute;s adelante en el proceso. Sin embargo, si las marcas de formato interfieren con la correcci&oacute;n del texto, usted puede removerlas. El bot&oacute;n <s>&lt;x&gt;</s> de la interfaz de revisi&oacute;n eliminar&aacute; etiquetas tales como &lt;i&gt; y &lt;b&gt; en el texto seleccionado. Algunos ejemplos de tareas que se realizan en la ronda de formato incluyen:
</p>
<ul>
  <li>&lt;i&gt;cursiva&lt;/i&gt;, &lt;b&gt;negrita&lt;/b&gt;, &lt;sc&gt;Versalitas&lt;/sc&gt;</li>
  <li>Texto espaciado</li>
  <li>Cambios del tama&ntilde;o de la fuente</li>
  <li>Espaciado de los t&iacute;tulos de cap&iacute;tulo y secci&oacute;n</li>
  <liEspacios extras, asteriscos o l&iacute;neas entre los parr&aacute;fos</li>
  <li>Notas al pie que se extienden por m&aacute;s de una p&aacute;gina</li>
  <li>Notas al pie marcadas con s&iacute;mbolos</li>
  <li>Ilustraciones</li>
  <li>Posicionamiento de las notas al margen</li>
  <li>Disposici&oacute;n de la informaci&oacute;n en las tablas</li>
  <li>Sangr&iacute;a (en poes&iacute;a o en otro tipo de texto)</li>
  <li>Reuni&oacute;n de l&iacute;neas largas en poes&iacute;as y en &iacute;ndices</li>
</ul>
<p>Si el revisor anterior insert&oacute; formato, por favor t&oacute;mese un momento y escr&iacute;bale un mensaje privado, haciendo clic en su nombre en la interfaz de revisi&oacute;n, explic&aacute;ndole c&oacute;mo abordar esta situaci&oacute;n en el futuro. <b>Recuerde que el formato se hace en las rondas de formato.</b>
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Volver al comienzo</a></p>


<h3><a name="common_OCR">Errores comunes del OCR</a></h3>
<p>El programa de Reconocimiento &Oacute;ptico de Caracteres (OCR) com&uacute;nmente tiene problemas distinguiendo entre caracteres similares. Algunos ejemplos de esto son:
</p>
<ul>
  <li>El d&iacute;gito "1" (uno), la letra min&uacute;scula "l" (ele) y la letra may&uacute;scula "I". Tenga en cuenta que en algunas fuentes el n&uacute;mero uno puede parecerse a una letra <small>I</small> peque&ntilde;a.</li>
  <li>El d&iacute;gito "0" (cero) y la letra may&uacute;scula "O".</li>
  <li>Los guiones cortos y largos. Rev&iacute;selos con mucho cuidado. El OCR a menudo coloca solo un gui&oacute;n para los guiones largos y deber&iacute;an ser dos. Revise la secci&oacute;n de estas reglas para <a href="#eol_hyphen">palabras con guiones</a> y <a href="#em_dashes">guiones largos</a> para informaci&oacute;n m&aacute;s detallada.
  </li>
  <li>Los par&eacute;ntesis ( ) y las llaves { }.</li>
</ul>
<p>Tenga cuidado con todos estos caracteres. Normalmente el contexto de la oraci&oacute;n es suficiente para determinar cu&aacute;l es el car&aacute;cter correcto, pero sea minucioso: a menudo su cerebro autom&aacute;ticamente "corrige" estos problemas mientras usted lee.
</p>
<p>Para notar estos errores m&aacute;s f&aacute;cilmente se recomienda utilizar una fuente mono-espacio tal como  <a href="../font_sample.php">DPCustomMono</a> o Courier.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Volver al comienzo</a></p>


<h3><a name="OCR_scanno">Problemas de OCR: Scannos</a></h3>
<p>Otro problema del OCR es el reconocimiento err&oacute;neo de caracteres. Llamamos a estos errores "scannos" (como "typos" en ingl&eacute;s, es decir, errores tipogr&aacute;ficos). Esto puede terminar creando una palabra que:</p>
<ul compact>
   <li>parece correcta a primera vista pero en verdad est&aacute; mal escrita.  <br>
       Esto usualmente se puede detectar utilizando el corrector ortogr&aacute;fico <a href="../wordcheck-faq.php">WordCheck</a> en la interfaz de revisi&oacute;n.</li>
   <li>ha sido cambiada por otra palabra igualmente v&aacute;lida o existente, pero no es la palabra exacta que aparece en la imagen. <br>
       Este error es muy sutil porque solamente puede ser identificado por una persona que lea el texto.</li>
</ul>
<p>Posiblemente, el ejemplo m&aacute;s com&uacute;n de este segundo tipo de errores es "and" siendo interpretado por el OCR como "arid". Otros ejemplos son "eve" en vez de "eye", "Torn" en vez de "Tom", "train" en vez de "tram". Este tipo de errores son dif&iacute;ciles de detectar y les damos un nombre especial: "Stealth Scannos" (errores sigilosos). Recolectamos ejemplos de Stealth Scannos en <a href="<?php echo $Stealth_Scannos_URL; ?>">este hilo del foro</a>.
</p>
<p>Los ejemplos de los scannos en espa&ntilde;ol se encuentran <a href="http://www.pgdp.net/wiki/Scannos_en_espa%C3%B1ol">aqu&iacute;</a>.</p>
<p>Encontrar estos errores es m&aacute;s f&aacute;cil si utiliza una fuente monoespacio como <a href="../font_sample.php">DPCustomMono</a> o Courier. Para ayudar el proceso de revisi&oacute;n, el uso de <a href="../wordcheck-faq.php">WordCheck</a> es recomendado en <?php echo $ELR_round->id; ?> y es obligatorio en las dem&aacute;s rondas de revisi&oacute;n.
</p>
<!-- END RR -->
<!-- More to be added.... -->
<p class="backtotop"><a href="#top">Volver al comienzo</a></p>


<h3><a name="OCR_raised_o">Problemas del OCR: &iquest;es eso &deg; &ordm; realmente un s&iacute;mbolo de grados?</a></h3>
<p>Existen tres s&iacute;mbolos diferentes que puede ser muy similares en la imagen y que el programa de OCR intepreta de la misma manera (y a menudo de forma incorrecta):
</p>
<ul>
  <li>El s&iacute;mbolo de grados <tt style="font-size:150%;">&deg;</tt>: este s&iacute;mbolo solo debe ser usado para indicar grados (de temperatura, en un &aacute;ngulo, etc.).
  </li>
  <li>La o super&iacute;ndice: todas las otras instancias de una "o" elevada deben ser revisadas como <tt>^o</tt>, de acuerdo a las reglas de revisi&oacute;n para <a href="#supers">super&iacute;ndices</a>.</li>
  <li>El ordinal masculino <tt style="font-size:150%;">&ordm;</tt>: revise este s&iacute;mbolo como una "o" super&iacute;ndice al menos que el car&aacute;cter especial sea requerido en los <a href="#comments">Comentarios del Proyecto</a>. Puede ser utilizado en idiomas tales como espa&ntilde;ol y portugu&eacute;s y es el equivalente del -th en ingl&eacute;s (4th, 5th, etc.). Siempre se ubica despu&eacute;s de un n&uacute;mero y tiene un equivalente femenino en la "a" super&iacute;ndice (<tt>&ordf;</tt>).</li>
</ul>
<!-- END RR -->
<p class="backtotop"><a href="#top">Volver al comienzo</a></p>


<h3><a name="hand_notes">Notas manuscritas</a></h3>
<p>No incluya notas escritas a mano que encuentre en el libro (a no ser que se trate de texto original que alguien haya reforzado a mano para que se vea mejor). No incluya notas escritas al margen por lectores del libro, etc.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Volver al comienzo</a></p>


<h3><a name="bad_image">Im&aacute;genes de mala calidad</a></h3>
<p>Si una imagen es de mala calidad (no se descarga, est&aacute; cortada, ilegible, etc.), por favor publique un mensaje en el <a href="#forums">foro del proyecto</a> y haga clic en el bot&oacute;n "Report Bad Page" (Informar p&aacute;gina defectuosa), para que la p&aacute;gina entre en "cuarentena", y no en "Return page to round" (Devolver la p&aacute;gina a la ronda"). Si solamente una parte de la imagen es de mala calidad, deje una nota como se explic&oacute; <a href="#anything">m&aacute;s arriba</a> y publique un comentario en la Discusi&oacute;n del Proyecto sin reportar toda la p&aacute;gina como defectuosa. El bot&oacute;n "Bad Page" solamente est&aacute; disponible durante la primera ronda de revisi&oacute;n, por lo tanto es importante que estos problemas se resuelvan al comienzo del trabajo en el proyecto.
</p>
<p>Tenga en cuenta que los archivos de algunas im&aacute;genes de las paginas son bastante grandes, y puede ser normal que su navegador tenga problemas para descargarlas, especialmente si tiene varios programas o ventanas abiertos, o utiliza un ordenador/computador antiguo. Antes de reportar una imagen como de mala calidad, intente arcercar la imagen, cerrar algunas de sus ventanas y programas o publicar un comentario en la Discusi&oacute;n del Proyecto para ver si alguien m&aacute;s tiene el mismo problema.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Volver al comienzo</a></p>


<h3><a name="bad_text">Imagen que no corresponde al texto</a></h3>
<p>Si la imagen no corresponde en absoluto al texto, por favor deje un comentario en la <a href="#forums">Discusi&oacute;n del Proyecto</a> en el foro y haga clic en el bot&oacute;n "Report Bad Page" (Informar p&aacute;gina defectuosa) para que esta p&aacute;gina entre en "cuarentena"; no devuelva la p&aacute;gina a la ronda. El bot&oacute;n "Bad page" solo se encuentra disponible en la primera ronda de revisi&oacute;n, por tanto es importante que este tipo de problemas sean resueltos al principio del trabajo.
</p>
<p>Sucede bastante a menudo que el texto resultado del OCR sea en su mayor parte correcto, pero que falte la primera o las dos primeras l&iacute;neas de texto. Por favor, digite la o las l&iacute;neas que falten. Si la mayor&iacute;a del texto falta, usted tiene la opci&oacute;n de escribir todo el texto (si as&iacute; lo desea) o hacer clic en "Return Page to Round" (Devolver p&aacute;gina a la ronda) y la p&aacute;gina ser&aacute; corregida por otro revisor. Si hay varias p&aacute;ginas en esta condici&oacute;n, usted puede publicar un comentario en la <a href="#forums">Discusi&oacute;n del Proyecto</a> para informar al Gestor del Proyecto.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Volver al comienzo</a></p>


<h3><a name="round1">Errores de revisores anteriores</a></h3>
<p>Si un revisor anterior ha cometido muchos errores o ha pasado por alto muchos problemas del OCR, por favor t&oacute;mese un momento para proveerle feedback, haciendo clic en su nombre en la interfaz de revisi&oacute;n y envi&aacute;ndole un mensaje privado, explic&aacute;ndole c&oacute;mo abordar esa situaci&oacute;n en el futuro.
</p>
<p>Por favor, <em>&iexcl;sea amable!</em> Todo el mundo aqu&iacute; es voluntario y est&aacute; intentando hacer lo mejor que puede. El objetivo de su mensaje debe ser informarle sobre la manera correcta de revisar y no criticarlo. Provea un ejemplo concreto de su trabajo, mostr&aacute;ndole lo que hizo y lo que deber&iacute;a haber hecho.
</p>
<p>Si el revisor anterior hizo un trabajo magn&iacute;fico, tambi&eacute;n puede enviarle un mensaje de felicitaciones, especialmente si se trata de una p&aacute;gina particularmente dif&iacute;cil.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Volver al comienzo</a></p>


<h3><a name="p_errors">Errores de impresi&oacute;n o de ortograf&iacute;a</a></h3>
<p>Corrija todos los errores del OCR (scannos) pero no corrija lo que le pueden parecen errores de impresi&oacute;n o de ortograf&iacute;a que aparecen en la imagen original. La ortograf&iacute;a de los textos antiguos puede diferir de la que usamos actualmente y nosotros conservamos esas caracter&iacute;sticas, incluyendo los caracteres acentuados.
</p>
<p>Coloque una nota en el texto junto al error de impressi&oacute;n<tt>[**error por "impresi&oacute;n"?]</tt>. Si no est&aacute; seguro de que sea efectivamente un error, por favor pregunte en la <a href="#forums">Discusi&oacute;n del Proyecto</a>. Si usted realiza un cambio, incluya una nota para describirlo<tt>[**error "impressi&oacute;n" arreglado]</tt>. Incluya los dos asteriscos <tt>**</tt> para que el posprocesador pueda encontrar la nota.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Volver al comienzo</a></p>


<h3><a name="f_errors">Errores f&aacute;cticos en el texto</a></h3>
<p>No corregimos errores de datos o hechos en los libros. Muchos de los libros que revisamos contienen datos o declaraciones que actualmente no son aceptables. D&eacute;jelos como los escribi&oacute; el autor del libro. Vea la secci&oacute;n <a href="#p_errors">Errores de impresi&oacute;n o de ortograf&iacute;a</a> para saber c&oacute;mo dejar una nota si piensa que el texto impreso no es lo que autor quiso decir.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Volver al comienzo</a></p>


<h3><a name="insert_char">Insertando caracteres especiales</a></h3>
<p>Existen varias maneras de insertar caracteres especiales, si no puede encontrarlos en su teclado:
</p>
<ul compact>
  <li>Los men&uacute;s desplegables en la interfaz de revisi&oacute;n.</li>
  <li>Aplicaciones incluidas en su sistema operativo: 
    <ul compact>
      <li>Windows: Mapa de caracteres<br> Ruta de acceso:<br>
          Haga clic en "Inicio", "Ejecutar", escriba "charmap"; o<br>
          Haga clic en "Inicio", "Accessorios", "Herramientas del sistema", "Mapa de catacteres".</li>
      <li>Macintosh: Key Caps o "Keyboard Viewer"<br>
          Para OS 9 y anteriores, est&aacute; en Apple Menu,<br>
          Para OS X hasta 10.2, est&aacute; en Applications, Utilities folder<br>
          Para OS X 10.3 y siguientes, est&aacute; en Input Menu como "Keyboard Viewer".</li>
      <li>Linux: Varias maneras, dependiendo del escritorio.</li>
    </ul>
  </li>
  <li>Un programa en l&iacute;nea.</li>
  <li>Atajos del teclado.<br>
       (Vea las tablas para <a href="#a_chars_win">Windows</a> y <a href="#a_chars_mac">Macintosh</a> m&aacute;s abajo.)</li>
  <li>Cambiar la configuraci&oacute;n del teclado para que soporte caracteres acentuados:
    <ul compact>
      <li>Windows: Panel de Control (Teclado, Input Locales).</li>
      <li>Macintosh: Input Menu (en la Barra del Men&uacute;).</li>
      <li>Linux: Modifique el teclado en su configuraci&oacute;n X.</li>
    </ul>
  </li>
</ul>
<!-- END RR -->

<a name="a_chars_win"></a>
<p><b>Para Windows</b>:
</p>
<ul compact>
  <li>Puede utilizar el Mapa de caracteres (Inicio/Ejectutar/charmap) para elegir una letra, y despu&eacute;s copiar y pegar.
  </li>
  <li>Men&uacute;s desplegables de la interfaz de revisi&oacute;n.
  </li>
  <li>O puede obtener los caracteres oprimiendo la tecla Alt y el c&oacute;digo n&uacute;merico espec&iacute;fico del caracter que necesita. Este modo es m&aacute;s r&aacute;pido una vez ha aprendido los c&oacute;digos correspondientes. 
      <br>Oprima la tecla Alt y, sin soltarla, digite las cuatro cifras en el <i>teclado num&eacute;rico</i> (la l&iacute;nea de n&uacute;meros sobre las letras no funcionar&aacute;) y luego suelte la tecla Alt. 
      <br>Debe teclear las cuatro cifras, incluyendo el 0 (cero) al principio. Como se puede dar cuenta, la letra may&uacute;scula es igual al c&oacute;digo de la correspondiente letra min&uacute;scula menos 32. 
      <br>Estas instrucciones son para la configuraci&oacute;n US-English del teclado. Puede que no funcionen para otras configuraciones. 
      <br>(<a href="../charwin.pdf">Versi&oacute;n para imprimir de la tabla.</a>)
  </li>
</ul>

<br>
<table align="center" border="6" rules="all" summary="Windows shortcuts">
  <tbody>
  <tr>
      <th bgcolor="cornsilk" colspan=14>Atajos de Windows para los s&iacute;mbolos Latin-1</th>
  </tr>
  <tr bgcolor="cornsilk">
      <th colspan=2>` grave</th>
      <th colspan=2>&acute; agudo (aigu)</th>
      <th colspan=2>^ circunflejo</th>
      <th colspan=2>~ tilde</th>
      <th colspan=2>&uml; di&eacute;resis</th>
      <th colspan=2>&deg; aro</th>
      <th colspan=2>ligadura &AElig;</th>
  </tr>
  <tr><td align="center" bgcolor="mistyrose" title="Small a grave"         >&agrave; </td><td>Alt-0224</td>
      <td align="center" bgcolor="mistyrose" title="Small a acute"         >&aacute; </td><td>Alt-0225</td>
      <td align="center" bgcolor="mistyrose" title="Small a circumflex"    >&acirc;  </td><td>Alt-0226</td>
      <td align="center" bgcolor="mistyrose" title="Small a tilde"         >&atilde; </td><td>Alt-0227</td>
      <td align="center" bgcolor="mistyrose" title="Small a umlaut"        >&auml;   </td><td>Alt-0228</td>
      <td align="center" bgcolor="mistyrose" title="Small a ring"          >&aring;  </td><td>Alt-0229</td>
      <td align="center" bgcolor="mistyrose" title="Small ae ligature"     >&aelig;  </td><td>Alt-0230</td>
  </tr>
  <tr><td align="center" bgcolor="mistyrose" title="Capital A grave"       >&Agrave; </td><td>Alt-0192</td>
      <td align="center" bgcolor="mistyrose" title="Capital A acute"       >&Aacute; </td><td>Alt-0193</td>
      <td align="center" bgcolor="mistyrose" title="Capital A circumflex"  >&Acirc;  </td><td>Alt-0194</td>
      <td align="center" bgcolor="mistyrose" title="Capital A tilde"       >&Atilde; </td><td>Alt-0195</td>
      <td align="center" bgcolor="mistyrose" title="Capital A umlaut"      >&Auml;   </td><td>Alt-0196</td>
      <td align="center" bgcolor="mistyrose" title="Capital A ring"        >&Aring;  </td><td>Alt-0197</td>
      <td align="center" bgcolor="mistyrose" title="Capital AE ligature"   >&AElig;  </td><td>Alt-0198</td>
  </tr>
  <tr><td align="center" bgcolor="mistyrose" title="Small e grave"         >&egrave; </td><td>Alt-0232</td>
      <td align="center" bgcolor="mistyrose" title="Small e acute"         >&eacute; </td><td>Alt-0233</td>
      <td align="center" bgcolor="mistyrose" title="Small e circumflex"    >&ecirc;  </td><td>Alt-0234</td>
      <td> </td><td> </td>
      <td align="center" bgcolor="mistyrose" title="Small e umlaut"        >&euml;   </td><td>Alt-0235</td>
      <td> </td><td> </td>
      <td> </td><td> </td>
  </tr>
  <tr><td align="center" bgcolor="mistyrose" title="Capital E grave"       >&Egrave; </td><td>Alt-0200</td>
      <td align="center" bgcolor="mistyrose" title="Capital E acute"       >&Eacute; </td><td>Alt-0201</td>
      <td align="center" bgcolor="mistyrose" title="Capital E circumflex"  >&Ecirc;  </td><td>Alt-0202</td>
      <td> </td><td> </td>
      <td align="center" bgcolor="mistyrose" title="Capital E umlaut"      >&Euml;   </td><td>Alt-0203</td>
      <td> </td><td> </td>
      <td> </td><td> </td>
  </tr>
  <tr><td align="center" bgcolor="mistyrose" title="Small i grave"         >&igrave; </td><td>Alt-0236</td>
      <td align="center" bgcolor="mistyrose" title="Small i acute"         >&iacute; </td><td>Alt-0237</td>
      <td align="center" bgcolor="mistyrose" title="Small i circumflex"    >&icirc;  </td><td>Alt-0238</td>
      <td> </td><td> </td>
      <td align="center" bgcolor="mistyrose" title="Small i umlaut"        >&iuml;   </td><td>Alt-0239</td>
      <td> </td><td> </td>
      <td> </td><td> </td>
  </tr>
  <tr><td align="center" bgcolor="mistyrose" title="Capital I grave"       >&Igrave; </td><td>Alt-0204</td>
      <td align="center" bgcolor="mistyrose" title="Capital I acute"       >&Iacute; </td><td>Alt-0205</td>
      <td align="center" bgcolor="mistyrose" title="Capital I circumflex"  >&Icirc;  </td><td>Alt-0206</td>
      <td> </td><td> </td>
      <td align="center" bgcolor="mistyrose" title="Capital I umlaut"      >&Iuml;   </td><td>Alt-0207</td>
      <th colspan=2 bgcolor="cornsilk">/ barra</th>
      <td></td>
  </tr>
  <tr><td align="center" bgcolor="mistyrose" title="Small o grave"         >&ograve; </td><td>Alt-0242</td>
      <td align="center" bgcolor="mistyrose" title="Small o acute"         >&oacute; </td><td>Alt-0243</td>
      <td align="center" bgcolor="mistyrose" title="Small o circumflex"    >&ocirc;  </td><td>Alt-0244</td>
      <td align="center" bgcolor="mistyrose" title="Small o tilde"         >&otilde; </td><td>Alt-0245</td>
      <td align="center" bgcolor="mistyrose" title="Small o umlaut"        >&ouml;   </td><td>Alt-0246</td>
      <td align="center" bgcolor="mistyrose" title="Small o slash"         >&oslash; </td><td>Alt-0248</td>
      <td></td><td></td>
  </tr>
  <tr><td align="center" bgcolor="mistyrose" title="Capital O grave"       >&Ograve; </td><td>Alt-0210</td>
      <td align="center" bgcolor="mistyrose" title="Capital O acute"       >&Oacute; </td><td>Alt-0211</td>
      <td align="center" bgcolor="mistyrose" title="Capital O circumflex"  >&Ocirc;  </td><td>Alt-0212</td>
      <td align="center" bgcolor="mistyrose" title="Capital O tilde"       >&Otilde; </td><td>Alt-0213</td>
      <td align="center" bgcolor="mistyrose" title="Capital O umlaut"      >&Ouml;   </td><td>Alt-0214</td>
      <td align="center" bgcolor="mistyrose" title="Capital O slash"       >&Oslash; </td><td>Alt-0216</td>
      <td></td><td></td>
  </tr>
  <tr><td align="center" bgcolor="mistyrose" title="Small u grave"         >&ugrave; </td><td>Alt-0249</td>
      <td align="center" bgcolor="mistyrose" title="Small u acute"         >&uacute; </td><td>Alt-0250</td>
      <td align="center" bgcolor="mistyrose" title="Small u circumflex"    >&ucirc;  </td><td>Alt-0251</td>
      <td> </td><td> </td>
      <td align="center" bgcolor="mistyrose" title="Small u umlaut"        >&uuml;   </td><td>Alt-0252</td>
      <td> </td><td> </td>
      <td> </td><td> </td>
  </tr>
  <tr><td align="center" bgcolor="mistyrose" title="Capital U grave"       >&Ugrave; </td><td>Alt-0217</td>
      <td align="center" bgcolor="mistyrose" title="Capital U acute"       >&Uacute; </td><td>Alt-0218</td>
      <td align="center" bgcolor="mistyrose" title="Capital U circumflex"  >&Ucirc;  </td><td>Alt-0219</td>
      <td> </td><td> </td>
      <td align="center" bgcolor="mistyrose" title="Capital U umlaut"      >&Uuml;   </td><td>Alt-0220</td>
      <th colspan=2 bgcolor="cornsilk">moneda     </th>
      <th colspan=2 bgcolor="cornsilk">matem&aacute;ticas  </th>
  </tr>
  <tr><td> </td><td> </td>
      <td align="center" bgcolor="mistyrose" title="Small y acute"         >&yacute; </td><td>Alt-0253</td>
      <td> </td><td> </td>
      <td align="center" bgcolor="mistyrose" title="Small n tilde"         >&ntilde; </td><td>Alt-0241</td>
      <td align="center" bgcolor="mistyrose" title="Small y umlaut"        >&yuml;   </td><td>Alt-0255</td>
      <td align="center" bgcolor="mistyrose" title="Cents"                 >&cent;   </td><td>Alt-0162</td>
      <td align="center" bgcolor="mistyrose" title="plus/minus"            >&plusmn; </td><td>Alt-0177</td>
  </tr>
  <tr><td> </td><td> </td>
      <td align="center" bgcolor="mistyrose" title="Capital Y acute"       >&Yacute; </td><td>Alt-0221</td>
      <td> </td><td> </td>
      <td align="center" bgcolor="mistyrose" title="Capital N tilde"       >&Ntilde; </td><td>Alt-0209</td>
      <td></td><td></td>
      <td align="center" bgcolor="mistyrose" title="Pounds"                >&pound;  </td><td>Alt-0163</td>
      <td align="center" bgcolor="mistyrose" title="Multiplication"        >&times;  </td><td>Alt-0215</td>
  </tr>
  <tr><th colspan=2 bgcolor="cornsilk">cedilla </th>
      <th colspan=2 bgcolor="cornsilk">island&eacute;s    </th>
      <th colspan=2 bgcolor="cornsilk">marcas        </th>
      <th colspan=2 bgcolor="cornsilk">acentos      </th>
      <th colspan=2 bgcolor="cornsilk">puntuaci&oacute;n  </th>
      <td align="center" bgcolor="mistyrose" title="Yen"                   >&yen;    </td><td>Alt-0165</td>
      <td align="center" bgcolor="mistyrose" title="Division"              >&divide; </td><td>Alt-0247</td>
  </tr>
  <tr><td align="center" bgcolor="mistyrose" title="Small c cedilla"       >&ccedil; </td><td>Alt-0231</td>
      <td align="center" bgcolor="mistyrose" title="Capital Thorn"         >&THORN;  </td><td>Alt-0222</td>
      <td align="center" bgcolor="mistyrose" title="Copyright"             >&copy;   </td><td>Alt-0169</td>
      <td align="center" bgcolor="mistyrose" title="acute accent"          >&acute;  </td><td>Alt-0180</td>
      <td align="center" bgcolor="mistyrose" title="Inverted Question Mark">&iquest; </td><td>Alt-0191</td>
      <td align="center" bgcolor="mistyrose" title="General Currency"      >&curren; </td><td>Alt-0164</td>
      <td align="center" bgcolor="mistyrose" title="Logical Not"           >&not;    </td><td>Alt-0172</td>
  </tr>
  <tr><td align="center" bgcolor="mistyrose" title="Capital C cedilla"     >&Ccedil; </td><td>Alt-0199</td>
      <td align="center" bgcolor="mistyrose" title="Small thorn"           >&thorn;  </td><td>Alt-0254</td>
      <td align="center" bgcolor="mistyrose" title="Registration Mark"     >&reg;    </td><td>Alt-0174</td>
      <td align="center" bgcolor="mistyrose" title="umlaut accent"         >&uml;    </td><td>Alt-0168</td>
      <td align="center" bgcolor="mistyrose" title="Inverted Exclamation"  >&iexcl;  </td><td>Alt-0161</td>
      <td> </td><td> </td>
      <td align="center" bgcolor="mistyrose" title="Degrees"               >&deg;    </td><td>Alt-0176</td>
  </tr>
  <tr><th colspan=2 bgcolor="cornsilk">super&iacute;ndices        </th>
      <td align="center" bgcolor="mistyrose" title="Capital Eth"           >&ETH;    </td><td>Alt-0208</td>
      <td align="center" bgcolor="mistyrose" title="Paragraph (pilcrow)"   >&para;   </td><td>Alt-0182</td>
      <td align="center" bgcolor="mistyrose" title="macron accent"         >&macr;   </td><td>Alt-0175</td>
      <td align="center" bgcolor="mistyrose" title="guillemet left"        >&laquo;  </td><td>Alt-0171</td>
      <td> </td><td> </td>
      <td align="center" bgcolor="mistyrose" title="Micro"                 >&micro;  </td><td>Alt-0181</td>
  </tr>
  <tr><td align="center" bgcolor="mistyrose" title="superscript 1"         >&sup1;   </td><td>Alt-0185&nbsp;*</td>
      <td align="center" bgcolor="mistyrose" title="Small eth"             >&eth;    </td><td>Alt-0240</td>
      <td align="center" bgcolor="mistyrose" title="Section"               >&sect;   </td><td>Alt-0167</td>
      <td align="center" bgcolor="mistyrose" title="cedilla"               >&cedil;  </td><td>Alt-0184</td>
      <td align="center" bgcolor="mistyrose" title="guillemet right"       >&raquo;  </td><td>Alt-0187</td>
      <th colspan=2 bgcolor="cornsilk">ordinales  </th>
      <td align="center" bgcolor="mistyrose" title="1/4 Fraction"          >&frac14; </td><td>Alt-0188&nbsp;&dagger;</td>
  </tr>
  <tr><td align="center" bgcolor="mistyrose" title="superscript 2"         >&sup2;   </td><td>Alt-0178&nbsp;*</td>
      <th colspan=2 bgcolor="cornsilk">ligadura sz       </th>
      <td align="center" bgcolor="mistyrose" title="Broken Vertical bar"   >&brvbar; </td><td>Alt-0166</td>
      <td> </td><td> </td>
      <td align="center" bgcolor="mistyrose" title="Middle dot"            >&middot; </td><td>Alt-0183</td>
      <td align="center" bgcolor="mistyrose" title="Masculine Ordinal"     >&ordm;   </td><td>Alt-0186&nbsp;*</td>
      <td align="center" bgcolor="mistyrose" title="1/2 Fraction"          >&frac12; </td><td>Alt-0189&nbsp;&dagger;</td>
  </tr>
  <tr><td align="center" bgcolor="mistyrose" title="superscript 3"         >&sup3;   </td><td>Alt-0179&nbsp;*</td>
      <td align="center" bgcolor="mistyrose" title="sz ligature"           >&szlig;  </td><td>Alt-0223</td>
      <td> </td><td> </td>
      <td> </td><td> </td>
      <td> </td><td> </td>
      <td align="center" bgcolor="mistyrose" title="Feminine Ordinal"      >&ordf;   </td><td>Alt-0170&nbsp;*</td>
      <td align="center" bgcolor="mistyrose" title="3/4 Fraction"          >&frac34; </td><td>Alt-0190&nbsp;&dagger;</td>
  </tr>
  </tbody>
</table>
<p>* A menos que esto sea requerido espec&iacute;ficamente en los <a href="#comments">Comentarios del Proyecto</a>, por favor no use los s&iacute;mbolos ordinales o de super&iacute;ndice, sino que siga las instrucciones para <a href="#supers">Super&iacute;ndices</a> contenidas en estas reglas (x^2, f^o, etc.).
</p>
<p>&dagger; A menos que esto sea requerido espec&iacute;ficamente en los <a href="#comments">Comentarios del Proyecto</a>, por favor no use los s&iacute;mbolos para fracciones, sino que siga las instrucciones para <a href="#fract_s">Fracciones</a> contenidas en estas reglas de revisi&oacute;n (1/2, 1/4, 3/4, etc.).
</p>
<p><b>Para Apple Macintosh</b>:
</p>
<ul compact>
  <li>Puede utilizar el programa "Key Caps" como referencia.<br>
      En OS 9 y versiones anteriores, est&aacute; en el Men&uacute; de Apple; OS X hasta 10.2 est&aacute; en "Applications", carpeta de "Utilities".<br>
Se visualizar&aacute; una imagen del teclado y si oprime "shift", "opt", "command" o combinaci&oacute;n de esas teclas, le muestra c&oacute;mo crear cada car&aacute;cter. Utilice esta referencia para ver c&oacute;mo teclear el car&aacute;cter deseado, o puede copiar desde aqu&iacute; y pegar en el texto en la interfaz de revisi&oacute;n.</li>
  <li>En OS X 10.3 y versiones siguientes, la misma funci&oacute;n est&aacute; ahora en una paleta disponible en el men&uacute; &laquo;Input&raquo; (el men&uacute; junto a "locale's flag icon" en la barra del men&uacute;). Se llama "Show Keyboard Viewer". Si no est&aacute; en su men&uacute; "Input" o no tiene ese men&uacute; puede activarlo abriendo "System Preferences", el panel "International" y elegir "Input Menu". Aseg&uacute;rese "Show input menu in menu bar" est&eacute; marcado. En la vista "spreadsheet", marque la casilla de "Keyboard Viewer" en adici&oacute;n a cualquier "Input" local que utilice.
  </li>
  <li>Los men&uacute;s desplegables en la interfaz de revisi&oacute;n. 
  </li>
  <li>O se pueden teclear los atajos de Apple Opt listados m&aacute;s abajo para los caracteres deseados. 
      <br>Una vez que se acostumbre a los c&oacute;digos, esta manera resulta m&aacute;s r&aacute;pida que copiar y pegar. 
      <br>Oprima la tecla "Opt" y el acento; despu&eacute;s teclee la letra que quiere acentuar (para algunos c&oacute;digos, basta con oprimir "Opt" y la letra). 
      <br>Estas instrucciones son para la configuraci&oacute;n US-English del teclado. Puede que no funcionen con otras configuraci&oacute;n. 
      <br>(<a href="../charapp.pdf">Versi&oacute;n para imprimir de esta tabla.</a>)
  </li>
</ul>

<br>
<a name="a_chars_mac"></a>
<table align="center" border="6" rules="all" summary="Mac shortcuts">
  <tbody>
  <tr bgcolor="cornsilk">
      <th colspan=14>Atajos para los s&iacute;mbolos de Latin-1 para Apple Mac</th>
  </tr>
  <tr bgcolor="cornsilk">
      <th colspan=2>` grave</th>
      <th colspan=2>&acute; agudo (aigu)</th>
      <th colspan=2>^ circunflejo</th>
      <th colspan=2>~ tilde</th>
      <th colspan=2>&uml; di&eacute;resis</th>
      <th colspan=2>&deg; aro</th>
      <th colspan=2>ligadura &AElig;</th>
  </tr>
  <tr><td align="center" bgcolor="mistyrose" title="Small a grave"         >&agrave; </td><td>Opt-`, a</td>
      <td align="center" bgcolor="mistyrose" title="Small a acute"         >&aacute; </td><td>Opt-e, a</td>
      <td align="center" bgcolor="mistyrose" title="Small a circumflex"    >&acirc;  </td><td>Opt-i, a</td>
      <td align="center" bgcolor="mistyrose" title="Small a tilde"         >&atilde; </td><td>Opt-n, a</td>
      <td align="center" bgcolor="mistyrose" title="Small a umlaut"        >&auml;   </td><td>Opt-u, a</td>
      <td align="center" bgcolor="mistyrose" title="Small a ring"          >&aring;  </td><td>Opt-a   </td>
      <td align="center" bgcolor="mistyrose" title="Small ae ligature"     >&aelig;  </td><td>Opt-'   </td>
  </tr>
  <tr><td align="center" bgcolor="mistyrose" title="Capital A grave"       >&Agrave; </td><td>Opt-`, A</td>
      <td align="center" bgcolor="mistyrose" title="Capital A acute"       >&Aacute; </td><td>Opt-e, A</td>
      <td align="center" bgcolor="mistyrose" title="Capital A circumflex"  >&Acirc;  </td><td>Opt-i, A</td>
      <td align="center" bgcolor="mistyrose" title="Capital A tilde"       >&Atilde; </td><td>Opt-n, A</td>
      <td align="center" bgcolor="mistyrose" title="Capital A umlaut"      >&Auml;   </td><td>Opt-u, A</td>
      <td align="center" bgcolor="mistyrose" title="Capital A ring"        >&Aring;  </td><td>Opt-A   </td>
      <td align="center" bgcolor="mistyrose" title="Capital AE ligature"   >&AElig;  </td><td>Opt-"   </td>
  </tr>
  <tr><td align="center" bgcolor="mistyrose" title="Small e grave"         >&egrave; </td><td>Opt-`, e</td>
      <td align="center" bgcolor="mistyrose" title="Small e acute"         >&eacute; </td><td>Opt-e, e</td>
      <td align="center" bgcolor="mistyrose" title="Small e circumflex"    >&ecirc;  </td><td>Opt-i, e</td>
      <td> </td><td> </td>
      <td align="center" bgcolor="mistyrose" title="Small e umlaut"        >&euml;   </td><td>Opt-u, e</td>
      <td> </td><td> </td>
      <td> </td><td> </td>
  </tr>
  <tr><td align="center" bgcolor="mistyrose" title="Capital E grave"       >&Egrave; </td><td>Opt-`, E</td>
      <td align="center" bgcolor="mistyrose" title="Capital E acute"       >&Eacute; </td><td>Opt-e, E</td>
      <td align="center" bgcolor="mistyrose" title="Capital E circumflex"  >&Ecirc;  </td><td>Opt-i, E</td>
      <td> </td><td> </td>
      <td align="center" bgcolor="mistyrose" title="Capital E umlaut"      >&Euml;   </td><td>Opt-u, E</td>
      <td> </td><td> </td>
      <td> </td><td> </td>
  </tr>
  <tr><td align="center" bgcolor="mistyrose" title="Small i grave"         >&igrave; </td><td>Opt-`, i</td>
      <td align="center" bgcolor="mistyrose" title="Small i acute"         >&iacute; </td><td>Opt-e, i</td>
      <td align="center" bgcolor="mistyrose" title="Small i circumflex"    >&icirc;  </td><td>Opt-i, i</td>
      <td> </td><td> </td>
      <td align="center" bgcolor="mistyrose" title="Small i umlaut"        >&iuml;   </td><td>Opt-u, i</td>
      <td> </td><td> </td>
      <td> </td><td> </td>
  </tr>
  <tr><td align="center" bgcolor="mistyrose" title="Capital I grave"       >&Igrave; </td><td>Opt-`, I</td>
      <td align="center" bgcolor="mistyrose" title="Capital I acute"       >&Iacute; </td><td>Opt-e, I</td>
      <td align="center" bgcolor="mistyrose" title="Capital I circumflex"  >&Icirc;  </td><td>Opt-i, I</td>
      <td> </td><td> </td>
      <td align="center" bgcolor="mistyrose" title="Capital I umlaut"      >&Iuml;   </td><td>Opt-u, I</td>
      <th colspan=2 bgcolor="cornsilk">/ barra</th>
      <td></td>
  </tr>
  <tr><td align="center" bgcolor="mistyrose" title="Small o grave"         >&ograve; </td><td>Opt-`, o</td>
      <td align="center" bgcolor="mistyrose" title="Small o acute"         >&oacute; </td><td>Opt-e, o</td>
      <td align="center" bgcolor="mistyrose" title="Small o circumflex"    >&ocirc;  </td><td>Opt-i, o</td>
      <td align="center" bgcolor="mistyrose" title="Small o tilde"         >&otilde; </td><td>Opt-n, o</td>
      <td align="center" bgcolor="mistyrose" title="Small o umlaut"        >&ouml;   </td><td>Opt-u, o</td>
      <td align="center" bgcolor="mistyrose" title="Small o slash"         >&oslash; </td><td>Opt-o   </td>
      <td></td><td></td>
  </tr>
  <tr><td align="center" bgcolor="mistyrose" title="Capital O grave"       >&Ograve; </td><td>Opt-`, O</td>
      <td align="center" bgcolor="mistyrose" title="Capital O acute"       >&Oacute; </td><td>Opt-e, O</td>
      <td align="center" bgcolor="mistyrose" title="Capital I circumflex"  >&Ocirc;  </td><td>Opt-i, O</td>
      <td align="center" bgcolor="mistyrose" title="Capital O tilde"       >&Otilde; </td><td>Opt-n, O</td>
      <td align="center" bgcolor="mistyrose" title="Capital O umlaut"      >&Ouml;   </td><td>Opt-u, O</td>
      <td align="center" bgcolor="mistyrose" title="Capital O slash"       >&Oslash; </td><td>Opt-O   </td>
      <td></td><td></td>
  </tr>
  <tr><td align="center" bgcolor="mistyrose" title="Small u grave"         >&ugrave; </td><td>Opt-`, u</td>
      <td align="center" bgcolor="mistyrose" title="Small u acute"         >&uacute; </td><td>Opt-e, u</td>
      <td align="center" bgcolor="mistyrose" title="Small u circumflex"    >&ucirc;  </td><td>Opt-i, u</td>
      <td> </td><td> </td>
      <td align="center" bgcolor="mistyrose" title="Small u umlaut"        >&uuml;   </td><td>Opt-u, u</td>
      <td> </td><td> </td>
      <td> </td><td> </td>
  </tr>
  <tr><td align="center" bgcolor="mistyrose" title="Capital U grave"       >&Ugrave; </td><td>Opt-`, U</td>
      <td align="center" bgcolor="mistyrose" title="Capital U acute"       >&Uacute; </td><td>Opt-e, U</td>
      <td align="center" bgcolor="mistyrose" title="Capital U circumflex"  >&Ucirc;  </td><td>Opt-i, U</td>
      <td> </td><td> </td>
      <td align="center" bgcolor="mistyrose" title="Capital U umlaut"      >&Uuml;   </td><td>Opt-u, U</td>
      <th colspan=2 bgcolor="cornsilk">moneda     </th>
      <th colspan=2 bgcolor="cornsilk">matem&aacute;ticas  </th>
  </tr>
  <tr><td> </td><td> </td>
      <td align="center" bgcolor="mistyrose" title="Small y acute"         >&yacute; </td><td>Opt-e, y</td>
      <td> </td><td> </td>
      <td align="center" bgcolor="mistyrose" title="Small n tilde"         >&ntilde; </td><td>Opt-n, n</td>
      <td align="center" bgcolor="mistyrose" title="Small y umlaut"        >&yuml;   </td><td>Opt-u, y</td>
      <td align="center" bgcolor="mistyrose" title="Cents"                 >&cent;   </td><td>Opt-4   </td>
      <td align="center" bgcolor="mistyrose" title="plus/minus"            >&plusmn; </td><td>Shift-Opt-=</td>
  </tr>
  <tr><td> </td><td> </td>
      <td align="center" bgcolor="mistyrose" title="Capital Y acute"       >&Yacute; </td><td>Opt-e, Y</td>
      <td> </td><td> </td>
      <td align="center" bgcolor="mistyrose" title="Capital N tilde"       >&Ntilde; </td><td>Opt-n, N</td>
      <td></td><td></td>
      <td align="center" bgcolor="mistyrose" title="Pounds"                >&pound;  </td><td>Opt-3   </td>
      <td align="center" bgcolor="mistyrose" title="Multiplication"        >&times;  </td><td>(none)&nbsp;&Dagger;</td>
  </tr>
  <tr><th colspan=2 bgcolor="cornsilk">cedilla </th>
      <th colspan=2 bgcolor="cornsilk">island&eacute;s    </th>
      <th colspan=2 bgcolor="cornsilk">marcas        </th>
      <th colspan=2 bgcolor="cornsilk">acentos      </th>
      <th colspan=2 bgcolor="cornsilk">puntuaci&oacute;n  </th>
      <td align="center" bgcolor="mistyrose" title="Yen"                   >&yen;    </td><td>Opt-y   </td>
      <td align="center" bgcolor="mistyrose" title="Division"              >&divide; </td><td>Opt-/   </td>
  </tr>
  <tr><td align="center" bgcolor="mistyrose" title="Small c cedilla"       >&ccedil; </td><td>Opt-c   </td>
      <td align="center" bgcolor="mistyrose" title="Capital Thorn"         >&THORN;  </td><td>(none)&nbsp;&Dagger;</td>
      <td align="center" bgcolor="mistyrose" title="Copyright"             >&copy;   </td><td>Opt-g   </td>
      <td align="center" bgcolor="mistyrose" title="acute accent"          >&acute;  </td><td>Opt-E   </td>
      <td align="center" bgcolor="mistyrose" title="Inverted Question Mark">&iquest; </td><td>Opt-?   </td>
      <td align="center" bgcolor="mistyrose" title="General Currency"      >&curren; </td><td>(none)&nbsp;&Dagger;  </td>
      <td align="center" bgcolor="mistyrose" title="Logical Not"           >&not;    </td><td>Opt-l   </td>
  </tr>
  <tr><td align="center" bgcolor="mistyrose" title="Capital C cedilla"     >&Ccedil; </td><td>Opt-C   </td>
      <td align="center" bgcolor="mistyrose" title="Small thorn"           >&thorn;  </td><td>(none)&nbsp;&Dagger;  </td>
      <td align="center" bgcolor="mistyrose" title="Registration Mark"     >&reg;    </td><td>Opt-r   </td>
      <td align="center" bgcolor="mistyrose" title="umlaut accent"         >&uml;    </td><td>Opt-U   </td>
      <td align="center" bgcolor="mistyrose" title="Inverted Exclamation"  >&iexcl;  </td><td>Opt-1   </td>
      <td> </td><td> </td>
      <td align="center" bgcolor="mistyrose" title="Degrees"               >&deg;    </td><td>Shift-Opt-8   </td>
  </tr>
  <tr><th colspan=2 bgcolor="cornsilk">super&iacute;ndices        </th>
      <td align="center" bgcolor="mistyrose" title="Capital Eth"           >&ETH;    </td><td>(none)&nbsp;&Dagger;  </td>
      <td align="center" bgcolor="mistyrose" title="Paragraph (pilcrow)"   >&para;   </td><td>Opt-7   </td>
      <td align="center" bgcolor="mistyrose" title="macron accent"         >&macr;   </td><td>Shift-Opt-,</td>
      <td align="center" bgcolor="mistyrose" title="guillemet left"        >&laquo;  </td><td>Opt-\   </td>
      <td> </td><td> </td>
      <td align="center" bgcolor="mistyrose" title="Micro"                 >&micro;  </td><td>Opt-m   </td>
  </tr>
  <tr><td align="center" bgcolor="mistyrose" title="superscript 1"         >&sup1;   </td><td>(none)&nbsp;*&Dagger;  </td>
      <td align="center" bgcolor="mistyrose" title="Small eth"             >&eth;    </td><td>(none)&nbsp;&Dagger;  </td>
      <td align="center" bgcolor="mistyrose" title="Section"               >&sect;   </td><td>Opt-6   </td>
      <td align="center" bgcolor="mistyrose" title="cedilla"               >&cedil;  </td><td>Opt-Z   </td>
      <td align="center" bgcolor="mistyrose" title="guillemet right"       >&raquo;  </td><td>Shift-Opt-\</td>
      <th colspan=2 bgcolor="cornsilk">ordinales  </th>
      <td align="center" bgcolor="mistyrose" title="1/4 Fraction"          >&frac14; </td><td>(none)&nbsp;&dagger;&Dagger;</td>
  </tr>
  <tr><td align="center" bgcolor="mistyrose" title="superscript 2"         >&sup2;   </td><td>(none)&nbsp;*&Dagger;  </td>
      <th colspan=2 bgcolor="cornsilk">ligadura sz        </th>
      <td align="center" bgcolor="mistyrose" title="Broken Vertical bar"   >&brvbar; </td><td>(none)&nbsp;&Dagger;  </td>
      <td> </td><td> </td>
      <td align="center" bgcolor="mistyrose" title="Middle dot"            >&middot; </td><td>Shift-Opt-9  </td>
      <td align="center" bgcolor="mistyrose" title="Masculine Ordinal"     >&ordm;   </td><td>Opt-0&nbsp;*</td>
      <td align="center" bgcolor="mistyrose" title="1/2 Fraction"          >&frac12; </td><td>(none)&nbsp;&dagger;&Dagger;</td>
  </tr>
  <tr><td align="center" bgcolor="mistyrose" title="superscript 3"         >&sup3;   </td><td>(none)&nbsp;*&Dagger;  </td>
      <td align="center" bgcolor="mistyrose" title="sz ligature"           >&szlig;  </td><td>Opt-s   </td>
      <td> </td><td> </td>
      <td> </td><td> </td>
      <td> </td><td> </td>
      <td align="center" bgcolor="mistyrose" title="Feminine Ordinal"      >&ordf;   </td><td>Opt-9&nbsp;*</td>
      <td align="center" bgcolor="mistyrose" title="3/4 Fraction"          >&frac34; </td><td>(none)&nbsp;&dagger;&Dagger;</td>
  </tr>
  </tbody>
</table>
<p>* A menos que esto sea requerido espec&iacute;ficamente en los <a href="#comments">Comentarios del Proyecto</a>, por favor no use los s&iacute;mbolos ordinales o de super&iacute;ndice, sino que siga las instrucciones para <a href="#supers">Super&iacute;ndices</a> contenidas en estas reglas (x^2, f^o, etc.).
</p>
<p>&dagger; A menos que esto sea requerido espec&iacute;ficamente en los <a href="#comments">Comentarios del Proyecto</a>, por favor no use los s&iacute;mbolos para fracciones, sino que siga las instrucciones para <a href="#fract_s">Fracciones</a> contenidas en estas reglas de revisi&oacute;n (1/2, 1/4, 3/4, etc.).
</p>
<p>&Dagger;&nbsp;Nota: no hay atajo equivalente, utilice los men&uacute;s desplegables.
</p>
<p class="backtotop"><a href="#top">Volver al comienzo</a></p>

</div>


<table width="100%" border="0" cellspacing="0" cellpadding="6" summary="Alphabetical Index">
  <tbody>
    <tr>
      <td bgcolor="silver"><h2 style="margin-bottom: 0; margin-top: 0;"><a name="index">&Iacute;ndice alfab&eacute;tico de las Reglas de Revisi&oacute;n</a></h2></td>
    </tr>
  </tbody>
</table>
<br>

<table border="0" width="100%" summary="Alphabetical Index">
  <tr>
    <td width="50%" valign="top">
      <ul>
        <li><a href="#a_chars">Acentos, Caracteres con</a></li>
        <li><a href="#about">Acerca de este documento</a></li>
        <li><a href="#insert_char">Atajos del teclado para caracteres Latin-1</a></li>
        <li><a href="#blank_pg">Blanco, P&aacute;gina en</a></li>
        <li><a href="#drop_caps">Capital, Letra</a></li>
        <li><a href="#chap_head">Cap&iacute;tulo, T&iacute;tulos de</a></li>
        <li><a href="#a_chars">Caracteres acentuados y caracteres no ASCII</a></li>
        <li><a href="#insert_char">Caracteres Latin-1, Insertando</a></li>
        <li><a href="#a_chars">Caracteres no ASCII</a></li>
        <li><a href="#f_chars">Caracteres no Latin-1</a></li>
        <li><a href="#next_word"><i>Catchwords</i></a></li>
        <li><a href="#mult_col">Columnas, M&uacute;ltiples</a></li>
        <li><a href="#prev_notes">Comentarios de revisores anteriores</a></li>
        <li><a href="#comments">Comentarios del Proyecto</a></li>
        <li><a href="#double_q">Comillas dobles</a></li>
        <li><a href="#chap_head">Comillas dobles que faltan al comienzo de un cap&iacute;tulo</a></li>
        <li><a href="#quote_ea">Comillas en cada l&iacute;nea</a></li>
        <li><a href="#single_q">Comillas simples</a></li>
        <li><a href="#contract">Contracciones</a></li>
        <li><a href="#title_pg">Contraportada</a></li>
        <li><a href="#anything">Cualquier otra cosa que necesite tratamiento especial o no sepa como tratar</a></li>
        <li><a href="#formatting">Cursivas</a></li>
        <li><a href="#d_chars">Diacr&iacute;ticos, Caracteres</a></li>
        <li><a href="#play_n">Directrices esc&eacute;nicas (obras de teatro)</a></li>
        <li><a href="#forums">Discusi&oacute;n del Proyecto</a></li>
        <li><a href="#chap_head">Encabezados de cap&iacute;tulo</a></li>
        <li><a href="#page_hf">Encabezados de p&aacute;gina</a></li>
        <li><a href="#page_hf">Encabezados y pies de p&aacute;gina</a></li>
        <li><a href="#poetry">Epigramas</a></li>
        <li><a href="#p_errors">Errores de impresi&oacute;n</a></li>
        <li><a href="#p_errors">Errores de impresi&oacute;n o de ortograf&iacute;a</a></li>
        <li><a href="#round1">Errores del revisor anterior</a></li>
        <li><a href="#f_errors">Errores f&aacute;cticos</a></li>
        <li><a href="#prev_pg">Errores, c&oacute;mo arreglarlos en p&aacute;ginas anteriores</a></li>
        <li><a href="#trail_s">Espacio al final de la l&iacute;nea</a></li>
        <li><a href="#extra_sp">Espacios extras</a></li>
        <li><a href="#extra_sp">Espacios extras entre palabras</a></li>
        <li><a href="#eol_hyphen">Final de la l&iacute;nea, Guiones al</a></li>
        <li><a href="#eop_hyphen">Final de la p&aacute;gina, Guiones al</a></li>
        <li><a href="#formatting">Formato</a></li>
        <li><a href="#formatting">Formato preexistente</a></li>
        <li><a href="#forums">Foro</a></li>
        <li><a href="#fract_s">Fracciones</a></li>
        <li><a href="#OCR_raised_o">Grado, s&iacute;mbolo de</a></li>
        <li><a href="#f_chars">Griego, Texto en</a></li>
        <li><a href="#summary">Gu&iacute;a corta de revisi&oacute;n</a></li>
        <li><a href="#em_dashes">Guiones</a></li>
        <li><a href="#eol_hyphen">Guiones al final de la l&iacute;nea</a></li>
        <li><a href="#eop_hyphen">Guiones al final de la p&aacute;gina</a></li>
        <li><a href="#em_dashes">Guiones largos o "eme"</a></li>
        <li><a href="#f_chars">Hebreo, Texto en</a></li>
        <li><a href="#illust">Ilustraciones</a></li>
        <li><a href="#bad_image">Imagen de mala calidad</a></li>
        <li><a href="#bad_text">Imagen que no corresponde al texto</a></li>
        <li><a href="#bk_index">&Iacute;ndices</a></li>
        <li><a href="#insert_char">Insertando caracteres especiales</a></li>
        <li><a href="#period_p"><i>Languages Other Than English</i> (LOTE), Puntos suspensivos en</a></li>
        <li><a href="#drop_caps">Letra ilustrada u ornamentada</a></li>
      </ul>
    </td>
    <td width="50%" valign="top">
      <ul>
        <li><a href="#a_chars">Ligaduras</a></li>
        <li><a href="#a_chars">Ligaduras ae</a></li>
        <li><a href="#a_chars">Ligadura oe</a></li>
        <li><a href="#hand_notes">Manuscritas, Notas</a></li>
        <li><a href="#em_dashes">Menos, Signo</a></li>
        <li><a href="#insert_char">Men&uacute;s despleglables</a></li>
        <li><a href="#formatting">Negritas, Texto en</a></li>
        <li><a href="#play_n">Nombres de personajes (obras de teatro)</a></li>
        <li><a href="#footnotes">Notas al final</a></li>
        <li><a href="#para_side">Notas al margen</a></li>
        <li><a href="#footnotes">Notas al pie</a></li>
        <li><a href="#prev_notes">Notas de los correctores anteriores</a></li>
        <li><a href="#hand_notes">Notas manuscritas</a></li>
        <li><a href="#line_no">N&uacute;meros de L&iacute;nea</a></li>
        <li><a href="#play_n">Obras de teatro</a></li>
        <li><a href="#play_n">Obras de teatro: nombres de personajes y directrices esc&eacute;nicas</a></li>
        <li><a href="#common_OCR">OCR, Problemas comunes del</a></li>
        <li><a href="#OCR_raised_o">Ordinal, S&iacute;mbolo</a></li>
        <li><a href="#p_errors">Ortograf&iacute;a, Errores de</a></li>
        <li><a href="#anything">Otras cosas de las que usted no est&aacute; seguro</a></li>
        <li><a href="#blank_pg">P&aacute;gina en blanco</a></li>
        <li><a href="#prev_pg">P&aacute;ginas anteriores, C&oacute;mo arreglar errores en</a></li>
        <li><a href="#next_word">Palabra suelta al final de la p&aacute;gina</a></li>
        <li><a href="#small_caps"><span style="font-variant: small-caps">Palabras en Versalitas</span></a></li>
        <li><a href="#para_space">P&aacute;rrafos, Espaciado de</a></li>
        <li><a href="#illust">Pie de foto en ilustraciones</a></li>
        <li><a href="#page_hf">Pies de p&aacute;gina</a></li>
        <li><a href="#poetry">Poes&iacute;a</a></li>
        <li><a href="#title_pg">Portada</a></li>
        <li><a href="#title_pg">Portada y contraportada</a></li>
        <li><a href="#common_OCR">Problemas comunes de OCR</a></li>
        <li><a href="#OCR_raised_o">Problemas del OCR: &iquest;es eso &deg; &ordm; realmente un s&iacute;mbolo de grados?</a></li>
        <li><a href="#OCR_scanno">Problemas del OCR: Scannos</a></li>
        <li><a href="#period_s">Punto final de las oraciones</a></li>
        <li><a href="#period_p">Puntos suspensivos</a></li>
        <li><a href="#punctuat">Puntuaci&oacute;n, Espaciado de la</a></li>
        <li><a href="#prime">Regla principal</a></li>
        <li><a href="#summary">Resumen de las Reglas de Revisi&oacute;n</a></li>
        <li><a href="#prev_notes">Revisores anteriores, Comentarios o notas de</a></li>
        <li><a href="#round1">Revisores anteriores, Errores de</a></li>
        <li><a href="#line_br">Saltos de l&iacute;nea</a></li>
        <li><a href="#para_space">Sangr&iacute;a de p&aacute;rrafos</a></li>
        <li><a href="#para_space">Sangr&iacute;a y espaciado de p&aacute;rrafos</a></li>
        <li><a href="#OCR_scanno">Scannos</a></li>
        <li><a href="#d_chars">Signos diacr&iacute;ticos</a></li>
        <li><a href="#subscr">Sub&iacute;ndices</a></li>
        <li><a href="#supers">Super&iacute;ndices</a></li>
        <li><a href="#period_p">Suspensivos, Puntos</a></li>
        <li><a href="#toc">Tabla de contenidos</a></li>
        <li><a href="#tables">Tablas</a></li>
        <li><a href="#extra_sp">Tabulacioness</a></li>
        <li><a href="#bad_text">Texto, Imagen que no corresponde al</a></li>
        <li><a href="#chap_head">T&iacute;tulos de cap&iacute;tulos</a></li>
        <li><a href="#small_caps"><span style="font-variant: small-caps">Versalitas</span></a></li>
        <li><a href="#OCR_scanno">WordCheck</a></li>
      </ul>
    </td>
  </tr>
</table>

<br>
<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="silver" summary="Links">
  <tr>
    <td width="10">&nbsp;</td>
    <td width="100%" align="center"><font face="verdana, helvetica, sans-serif" size="1">
      Return to:
      <a href="../.."><?php echo "$site_name"; ?> home page</a>,
      &nbsp;&nbsp;&nbsp;
      <a href="../faq_central.php"><?php echo "$site_abbreviation"; ?> FAQ Central page</a>,
      &nbsp;&nbsp;&nbsp;
      <a href="<?php echo $PG_home_url; ?>">Project Gutenberg home page</a>.
      </font>
    </td>
  </tr>
</table>

<?php
// vim: sw=4 ts=4 expandtab
