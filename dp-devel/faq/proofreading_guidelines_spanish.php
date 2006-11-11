<?
// Translated by user 'cbgrf' at pgdp.net, 2006-08-26

$relPath='../../c/pinc/';
include($relPath.'site_vars.php');
include($relPath.'faq.inc');
include($relPath.'pg.inc');
include($relPath.'connect.inc');
include($relPath.'theme.inc');
new dbConnect();
$no_stats=1;
theme('Reglas de Revisión','header');

$utf8_site=!strcasecmp($charset,"UTF-8");
?>

<h1 align="center">Reglas de Revisión</h1>

<h3 align="center">Version 1.9.c, generated January 1, 2006
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
  <a href="dochist.php"><font size="-1">(Revision History)</font></a></h3>

<h4>Proofreading Guidelines <a href="proofreading_guidelines_francaises.php">in French</a> /
    Directives de Relecture et correction <a href="proofreading_guidelines_francaises.php">en fran&ccedil;ais</a><br>

	Proofreading Guidelines <a href="proofreading_guidelines_portuguese.php">in Portuguese</a> /
    Regras de Revis&atilde;o <a href="proofreading_guidelines_portuguese.php">em Portugu&ecirc;s</a></h4>

<h4>V&eacute;alos <a href="../quiz/start.php">Proofreading Quiz and Tutorial</a></h4>

<table border="0" cellspacing="0" width="100%" summary="Proofreading Guidelines">
  <tbody>

  <tr>
    <td width="1" bgcolor="silver">&nbsp;</td>
    <td bgcolor="silver" align="center"><font size="+2"><b>Tabla de Contenidos</b></font></td>
  </tr>
  <tr>
    <td width="1" bgcolor="silver">&nbsp;</td>
    <td bgcolor="white" align="left">
    <ul>

      <li><a href="#prime">Regla principal</a></li>
      <li><a href="#about">Acerca de este documento</a></li>
      <li><a href="#comments">Comentarios del proyecto</a></li>
      <li><a href="#forums">Foro/Discusión sobre este proyecto</a></li>
      <li><a href="#prev_pg">Corrigiendo errores en anteriores  páginas </a></li>
    </ul>

    </td>
  </tr>
  <tr>
    <td width="1" bgcolor="silver">&nbsp;</td>
    <td bgcolor="silver" align="left">
      <ul>
        <li><font size="+1">C&oacute;mo revisar...</font></li>
      </ul>

    </td>
  </tr>
  <tr>
    <td width="1" bgcolor="silver">&nbsp;</td>
    <td bgcolor="white" align="left">
      <ul style="margin-left: 3em;">
        <li><a href="#line_br">Saltos de l&iacute;nea</a></li>
        <li><a href="#double_q">Comillas dobles</a></li>

        <li><a href="#single_q">Comillas simples</a></li>
        <li><a href="#quote_ea">Comillas en cada línea</a></li>
        <li><a href="#period_s">Punto final de las frases</a></li>
        <li><a href="#punctuat">Puntuación</a></li>
        <li><a href="#period_p">Puntos suspensivos "..."</a></li>

        <li><a href="#contract">Contracciones</a></li>
        <li><a href="#extra_sp">Varios espacios en blanco entre palabras</a></li>
        <li><a href="#trail_s">Espacios en blanco al final de la línea</a></li>
        <li><a href="#line_no">Números de línea</a></li>
        <li><a href="#italics">Textos en cursiva y negrita</a></li>
        <li><a href="#supers">Superíndice</a></li>

        <li><a href="#subscr">Subíndice</a></li>
        <li><a href="#font_sz">Cambio de tamaño de fuente</a></li>
        <li><a href="#small_caps">Palabras en mayúsculas pequeñas-versalitas</a></li>
        <li><a href="#drop_caps">Letras mayúsculas ornamentadas al inicio del párrafo, frase o sección</a></li>
        <li><a href="#a_chars">Caracteres acentuados</a></li>
        <li><a href="#d_chars">Signos diacríticos</a></li>

        <li><a href="#f_chars">Caracteres no-Latinos</a></li>
        <li><a href="#fract_s">Fracciones</a></li>
        <li><a href="#em_dashes">Guiones, el signo menos "-" y rayas</a></li>
        <li><a href="#eol_hyphen">Guión al final de la línea</a></li>
        <li><a href="#eop_hyphen">Guión al final de la página</a></li>
        <li><a href="#para_space">Párrafos</a></li>

        <li><a href="#mult_col">Múltiples Columnas</a></li>
        <li><a href="#blank_pg">Página en blanco</a></li>
        <li><a href="#page_hf">Encabezado y pie de página</a></li>
        <li><a href="#chap_head">Títulos de capítulo</a></li>
        <li><a href="#illust">Ilustraciones - Imágenes</a></li>
        <li><a href="#footnotes">Notas al pie de página</a></li>

        <li><a href="#poetry">Poesía</a></li>
        <li><a href="#para_side">Notas al margen</a></li>
        <li><a href="#tables">Tablas</a></li>
        <li><a href="#title_pg">Portada&mdash;Contraportada</a></li>
        <li><a href="#toc">La tabla de contenidos</a></li>
        <li><a href="#bk_index">Índices</a></li>

        <li><a href="#play_n">Obras de teatro&mdash;Nombres de actores&mdash;Directrices escénicas</a></li>
        <li><a href="#anything">Cualquier otra cosa que necesite tratamiento especial o no sepa como tratar</a></li>
        <li><a href="#prev_notes">Notas y comentarios de revisores anteriores</a></li>
      </ul>
    </td>
  </tr>
   <tr>

    <td width="1" bgcolor="silver">&nbsp;</td>
    <td bgcolor="silver" align="left">
    <ul>
      <li><font size="+1">Problemas comunes</font></li>
    </ul>
    </td>
  </tr>
  <tr>

    <td width="1" bgcolor="silver">&nbsp;</td>
    <td bgcolor="white" align="left">
      <ul style="margin-left: 3em;">
        <li><a href="#OCR_1lI">Problemas de OCR: 1-l-I</a></li>
        <li><a href="#OCR_0O">Problemas de OCR: 0-O</a></li>
        <li><a href="#OCR_scanno">Problemas de OCR: Scannos</a></li>
        <li><a href="#hand_notes">Notas escritas a mano</a></li>

        <li><a href="#bad_image">Imágenes de baja calidad</a></li>
        <li><a href="#bad_text">Imagen que no corresponde al texto</a></li>
        <li><a href="#round1">Errores de revisores anteriores</a></li>
        <li><a href="#p_errors">Errores de impresión / Ortografía</a></li>
        <li><a href="#f_errors">Errores de datos o hechos en el texto</a></li>
        <li><a href="#uncertain">Inseguridades</a></li>

      </ul>
    </td>
  </tr>
  <tr>
    <td width="1" bgcolor="silver">&nbsp;</td>
    <td bgcolor="silver">&nbsp;</td>
  </tr>
 </tbody>
</table>

<h3><a name="prime">Regla principal</a></h3>
<p><em>¡No cambie lo que escribió el autor!</em>
</p>
<p>El libro electrónico, que verá el lector, posiblemente dentro de muchos
años, debe decirle lo que quería decir el autor. Si el autor escribió
una palabra de una manera extraña, la dejamos como está. Si el autor
escribió de una forma racista, también se dejan las palabras como están.
Si el autor usaba cursiva, negrita o ponía una nota al pie cada tres
palabras, debemos usar cursiva, negrita y dejar las notas donde están.
Somos revisores, <b>no</b> editores. (Vea <a href="#p_errors">Errores de impresión /
ortografía</a>. para entender cómo manejar errores de impresión)
</p>
<p>Cambiamos solamente pequeños detalles de ortografía que no afecten el
sentido de lo escrito por el autor. Por ejemplo, unimos palabras que
fueron partidas al final de la línea. (Vea <a href="#eol_hyphen">Guiones al final de la
línea.</a>)
</p>
<p>Cambios de este tipo nos ayudan a crear una versión del libro bien
formateado de una manera consistente. Las reglas de revisión que sigamos
están diseñadas para ayudarnos a lograr ese resultado. Por favor, lea
cuidadosamente el resto de las Reglas de revisión, teniendo esto en
cuenta. Existe otro grupo de Reglas de formato aparte. Estas reglas de
revisión son designadas sólo para la revisión. Un segundo equipo de
voluntarios trabajará formateando el texto.
</p>
<p>
Para ayudar al próximo revisor y las personas que hacen el formato y el pos-proceso conservamos
<a href="#line_br">las quiebras de las líneas</a>. Esto hace más fácil comparar el
texto con la imagen original.
</p>
<!-- END RR -->

<table width="100%" border="0" cellspacing="0" summary="Summary Guidelines">
  <tbody>
    <tr>
      <td bgcolor="silver">&nbsp;</td>
    </tr>
  </tbody>

</table>



<h3><a name="about">Acerca de este documento</a></h3>
<p>
La finalidad de este documento es de explicar las reglas que utilizamos
para mantener la coherencia al revisar un libro compartido por muchos
revisores, de los que cada uno trabaja en páginas diferentes. Esto
asegura que todos estemos revisando <em>de la misma manera</em> y hace más
fácil el trabajo del formateador y el del pos-procesador que junta todas
las páginas en un e-book (libro electrónico) completo.
</p>
<p>
<i>Este documento de ninguna manera pretende ser modelo para la edición de libros.</i><br>
</p>
<p>
Hemos incluido en este documento todos los detalles que los nuevos
revisores han ido preguntado en sus comienzos. Si hay cosas que faltan,
o cree que algo se debe hacer de otra manera, o si algo no está bien
claro, avísenos por favor.
</p>
<p>
Este documento está evolucionando en el tiempo. Ayúdenos a mejorar
escribiendo sus sugerencias en el Foro de Documentación en <a href="http://www.pgdp.net/phpBB2/viewtopic.php?t=18057"> este enlace</a>.
</p>

<h3><a name="comments">Comentarios del proyecto</a></h3>

<p>En la página de interfaz (Project Page) con la que empieza la revisión,
hay una parte que se llama «Project Comments» (comentarios del proyecto)
la que contiene a la información específica del proyecto (libro).
<b>¡Léalos antes de empezar a revisar páginas!</b> Si el gerente del
proyecto (Project Manager) quiere que en el libro concreto se haga algo
diferente de la manera que está descrita en estas Reglas de Revisión,
eso será aclarado allí. Las instrucciones en los Project Comments
''anulan'' las de las Reglas de Revisión, por lo tanto sígalas. También
puede haber instrucciones en los Project Comments que se refieran al
formato, y no se utilizan durante la primera fase de revisión. En estos
comentarios es donde el Project Manager le puede dar todo tipo de
información o curiosidades sobre el autor y el proyecto.
</p>
<p>
<em>Por favor, lea también el foro del proyecto (Project Thread Forum)</em>: Allí
es donde el Project Manager puede aclarar las reglas de revisión
específicas al proyecto y los revisores suelen usarlo para avisar a
otros revisores sobre problemas que se repiten dentro del mismo y cómo
manejarlos (Vea abajo).
</p>
<p>En la Project Page (Página del Proyecto), el enlace 'Images, Pages
Proofread, & Differences' (Imágenes, Páginas Revisadas y Diferencias) le
permite ver como otros revisores han hecho los cambios. <a href="http://www.pgdp.net/phpBB2/viewtopic.php?t=10217"> En este foro</a> se
habla sobre las diferentes maneras de usar esta información.
</p>

<h3><a name="forums">Foro/Discusión sobre este proyecto</a></h3>
<p>En la página de interfaz (Project Page), donde empieza la revisión de
las páginas, en la línea «Forum» (Foro), hay un enlace titulado «Discuss
this Project» (Discutir este proyecto) si ya se ha iniciado la
discusión, o «Start a discussion on this Project» (Empezar la discusión
sobre este proyecto) si todavía no ha empezado. Haciendo clic en ese
enlace irá a un foro dedicado al proyecto en concreto. Ese es el lugar
para hacer preguntas sobre el libro, avisar al gerente del proyecto
(Project Manager) sobre problemas, etc. Se sugiere utilizar este foro
para comunicarse con el gerente del proyecto y otros revisores que
trabajan en el mismo.
</p>

<h3><a name="prev_pg">Corrigiendo errores en anteriores páginas</a></h3>
<p>Cuando selecciona un proyecto para revisar, se carga la Página del
Proyecto (Project Page). Esta página contiene enlaces a páginas del
proyecto que Ud. ha revisado últimamente. Si aún no ha revisado ninguna
página, no se muestra ningún enlace.
</p>

<p>Las páginas que aparecen debajo de «DONE» (terminado) o «IN PROGRESS»
(en progreso) están todavía disponibles para que Ud. las corrija o
termine su revisión. Sólo haga clic en el enlace de la página. Si
descubre que se ha equivocado en alguna página, o ha marcado algo
incorrectamente, puede hacer clic en cualquiera de ellas para reabrirla
y corregir el error.
</p>
<p>Puede también utilizar los enlaces «Images, Pages Proofread, &
Differences» (Imágenes, Páginas ya Revisadas y Diferencias) ó «Just My
Pages» (Sólo mis Páginas) en la Página del Proyecto. Estas páginas le
mostrarán el enlace «Edit» al lado de todas aquellas en las que Ud. ha
trabajado en la presente ronda y que todavía pueden ser corregidas.
</p>
<p>Para más información, vea la
<a href="prooffacehelp.php?i_type=0">Standard
Proofreading Interface Help</a> ó
<a href="prooffacehelp.php?i_type=1"> Enhanced
Proofreading Interface Help</a> dependiendo de la interfaz que utilice.
</p>
<!-- END RR -->

<table width="100%" border="0" cellspacing="0" cellpadding="6" summary="Title Page">
  <tbody>
    <tr>
      <td bgcolor="silver"><font size="+2">C&oacute;mo revisar...</font></td>
    </tr>
  </tbody>
</table>

<h3><a name="line_br">Saltos de línea</a></h3>

<p>Conservamos los saltos de línea. <b>Deje todos los saltos de línea donde
están</b> para que más adelante otros voluntarios puedan comparar fácilmente
las líneas del texto con las de la imagen.
</p>


<!-- END RR -->
<!-- We should have an example right here for this. -->

<h3><a name="double_q">Comillas dobles</a></h3>
<p>Revise las comillas dobles así: ". No las cambie a comillas simples.
Déjelas como las escribió el autor.
</p>
<p>Para otros idiomas que no sea el inglés, utilice las comillas
características para el idioma en concreto, si están disponibles en la
lista de caracteres de Latin-1. Guillemets franceses (<tt>&laquo;estas&raquo;</tt>) están
disponibles en los menús desplegables de la página de interfaz de
revisores porque forman una parte de Latin-1. Las comillas alemanas
(&bdquo;estas&rdquo;) no están disponibles allí porque no forman parte de Latin-1.
Puede que el Project Manager le informe en los <a href="#comments">Comentarios del
proyecto</a> de la pauta a seguir en un proyecto
en concreto.
</p>

<h3><a name="single_q">Comillas simples</a></h3>
<p>Revíselas como ASCII normal (así: <tt>'</tt> ), comilla simple (apóstrofe). No
cambie las comillas simples a dobles. Déjelas como el autor las
escribió.
</p>

<h3><a name="quote_ea">Comillas en cada línea</a></h3>

<p>Los párrafos que tienen las comillas al principio de cada línea se
corrigen dejando <b>solamente</b> las del principio del párrafo.
</p>
<p>Si la cita se prolonga a lo largo de varios párrafos, cada uno de ellos
debe retener solamente las comillas de la primera línea del mismo.
</p>
<p>A veces no hay comillas que cierran la cita hasta el final de la misma,
que puede estar en otra página. Déjelo como está&mdash;no ponga comillas que
no se ven en la imagen.
</p>

<h3><a name="period_s">Punto final de las frases</a></h3>
<p>Deje sólo un espacio después del punto final de la frase.
</p>
<p>Si en el texto escaneado aparecen varios espacios después del punto
final, no es necesario quitar los espacios sobrantes&mdash;eso se hace
automáticamente durante el pos-proceso. Vea las <a href="#para_side">Notas al margen</a> para un ejemplo.

<h3><a name="punctuat">Puntuación</a></h3>
<p>En general, no debería de haber espacios en blanco ante los caracteres
de puntuación excepto ante las comillas que abren la frase. Si hay un
espacio en blanco dejado por el OCR, quítelo. Esto se refiere incluso a
los idiomas como el francés, los que normalmente dejan un espacio en
blanco ante los caracteres de puntuación.
</p>
<p>Este espacio en blanco a veces aparece en los libros impresos durante los siglos XVIII y XIX ya que en esa época era frecuente dejar medio espacio entre la palabra y los dos puntos o el punto y coma.
</p>
<!-- END RR -->

<table width="100%" align="center" border="1"  cellpadding="4" cellspacing="0" summary="Punctuation">
  <tbody>

    <tr><th align="left" bgcolor="cornsilk">Texto escaneado:</th></tr>
    <tr>
      <td valign="top">and so it goes&nbsp;; ever and ever.</td>
    </tr>
    <tr><th align="left" bgcolor="cornsilk">Texto correctamente revisado:</th></tr>
    <tr>
      <td valign="top"><tt>and so it goes; ever and ever.</tt></td>

    </tr>
  </tbody>
</table>

<h3><a name="period_p">Puntos suspensivos "..."</a></h3>
<p>Las reglas son diferentes para inglés y otros idiomas (LOTE).
</p>
<p><b>Inglés</b>:
 Deje un espacio antes y después de los puntos suspensivos. La excepción es cuando los puntos suspensivos se encuentren al final de la frase donde no habrá espacio en blanco antes, sino cuatro puntos y un espacio después.... Lo mismo ocurre con cualquier otro signo de puntuación: los puntos suspensivos siguen al signo que los precede sin ningún espacio entre ellos.
</p>
<p>For example:<br>

   <tt>
   <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;That I know ... is true.
   <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;This is the end....
   <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Wherefore art thou Romeo?...
   </tt>
</p>
<p>A veces encontrarán el signo de puntuación al final; como en el ejemplo:
<br>
   <tt>
   <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Wherefore art thou Romeo...?
   </tt>
</p>
<p>Elimine si sobran puntos o añada los que sean necesarios para conseguir el formato correcto.
</p>

<p><b>LOTE:</b> (Languages Other Than English--otros idiomas que no sean inglés.)
Use la regla principal: &laquo;siga lo más exactamente posible el estilo de la página
impresa&raquo;. Inserte espacio en blanco si es necesario antes o entre los puntos
suspensivos, y use el mismo número de puntos que aparece en la imagen de
la página impresa. A veces la imagen no se ve bien.
En ese caso, deje una nota para llamar atención del pos-procesador así: <tt>[**unclear]</tt>.
</p>

<h3><a name="contract">Contracciones</a></h3>
<p>Elimine espacio en blanco de las contracciones. Por ejemplo:
<tt>would&nbsp;n't</tt>
se revisa como <tt>wouldn't</tt>. <br>
</p>
<p>El espacio en blanco es común en los libros de las épocas anteriores y
se dejaba para indicar que originalmente se trata de dos palabras
separadas, 'would' y 'not.' A veces lo deja el OCR sin razón aparente.
En todo caso elimine este espacio en blanco.
</p>
<p>Algunos gerentes de proyecto (Project Managers) le dirán en los
<a href="#comments">Comentarios del proyecto</a> que no elimine
estos espacios en blanco de las contracciones, especialmente si se trata
de los textos que contienen argot, dialectos o son textos en idiomas
distintos al inglés.
</p>


<h3><a name="extra_sp">Varios espacios en blanco entre palabras</a></h3>
<p>Las tabulaciones o varios espacios en blanco entre palabras aparecen a
menudo en los resultados de OCR. No se moleste en eliminarlos&mdash;eso se
hará automáticamente en el pos-proceso.
</p>
<p>En cambio, <b>sí</b> es necesario eliminar estos espacios entre las palabras y
signos de puntuación (comillas, puntos, punto y coma, etc.).
</p>
<p>
Por ejemplo: <b>A horse&nbsp;;&nbsp;&nbsp;&nbsp;my kingdom for a horse.</b> el espacio entre
la palabra 'horse' y el punto y coma hay que eliminar. Pero, los dos
espacios después del punto y coma se pueden dejar&mdash;no es necesario
eliminar uno de ellos.
</p>

<h3><a name="trail_s">Espacios en blanco al final de la línea</a></h3>
<p>No pierda tiempo en insertar espacios en blanco al final de las líneas
del texto. Es algo que se hace automáticamente más adelante en el
pos-proceso. Tampoco pierda tiempo en eliminar los que sobran al final
de las líneas.
</p>

<h3><a name="line_no">Números de línea</a></h3>

<p>Si las líneas tienen números, consérvelos. Colóquelos al menos seis
espacios a la derecha del resto del texto, aun cuando originalmente se
encuentran a la izquierda de la poesía/texto original.
</p>
<p>Números de línea se encuentran al margen de cada línea, o a veces cada
cinco o diez líneas. Son comunes en libros de poesía. Como el libro
electrónico (e-book) no tiene páginas, los números de los versos sirven
para orientar al lector.
</p>
<!-- END RR -->
<!-- We need an example image and text for this. -->

<h3><a name="italics">Textos en cursiva y negrita</a></h3>
<p><i>Textos en cursiva</i> a veces aparecen entre <tt>&lt;i&gt;</tt>y<tt>&lt;/i&gt;</tt>. <b>Texto en negrita</b> a
veces aparece entre <tt>&lt;b&gt;</tt>y<tt>&lt;/b&gt;</tt>. No elimine esta información de formato, a menos
que <b>no</b> aparezca en la imagen. No agregue este formato en esta
ronda, las personas que dan formato al texto (los formateadores) lo harán
más adelante.
</p>
<!-- END RR -->


<h3><a name="supers">Superíndice</a></h3>
<p>En los libros antiguos eran frecuentes las contracciones que se
imprimían en forma de superíndices.
<br>
Por ejemplo:
<br>Gen<sup>rl</sup> Washington defeated L<sup>d</sup>  Cornwall's army.
Aparecen en muchos libros en castellano y francés: S<sup>r</sup> , S<sup>ra</sup>, M<sup>lle</sup>, etc.
<br>
Revise estas contracciones insertando un acento circunflejo (carácter ^) para identificar que
lo que sigue es texto en superíndice.
<br>
Así:<br>
&nbsp;&nbsp;&nbsp;&nbsp; <tt>Gen^rl Washington defeated L^d Cornwall's army.
Y: S^r,S^ra, M^lle, etc.</tt>
</p>


<h3><a name="subscr">Subíndice</a></h3>
<p>El texto en subíndice se encuentra en obras científicas pero no es común en otro
tipo de obras. Revise el texto subscrito insertando un guión bajo (carácter <tt>_</tt>), así:
<br>
   <br>Por ejemplo:
   <br>&nbsp; &nbsp; &nbsp; &nbsp; H<sub>2</sub>O.
   <br>se revisa de esta manera:
   <br>&nbsp; &nbsp; &nbsp; &nbsp; <tt>H_2O.<br></tt>

</p>


<h3><a name="font_sz">Cambio de tamaño de fuente</a></h3>
<p>No indique cambios del tamaño de fuente. Las personas que dan el formato al texto (los formateadores) lo harán más adelante.
</p>

<h3><a name="small_caps">Palabras en mayúsculas pequeñas-versalitas</a></h3>
<p>
Las palabras impresas en letras mayúsculas pequeñas-versalitas-(<span style=
"font-variant:small-caps">Versalitas</span>)
con letras mayúsculas y mayúsculas pequeñas-versalitas mezcladas, pueden
aparecer entre estos símbolos: <tt>&lt;sc&gt;</tt> y <tt>&lt;/sc&gt;</tt>.  Por favor,
no elimine esta información
de formato, a menos que esté alrededor de algo que no existe en la imagen.  Tampoco
la ponga donde no esté puesta ya.  Las personas que dan formato (los formateadores)
lo harán mas adelante en el proceso.  Revise sólo los caracteres mismos, y no se
preocupe por los cambios de fuente. Si todas las letras son MAYÚSCULAS, minúsculas
o Mezcladas, déjelas como están.
</p>

<h3><a name="drop_caps">Letras mayúsculas ornamentadas al inicio del párrafo, frase o sección</a></h3>
<p>Revise este tipo de letra ornamentada al inicio de un párrafo, frase o
sección como letras normales.
</p>


<h3><a name="a_chars">Caracteres acentuados</a></h3>

<p>Por favor, utilice los caracteres de Latin-1 donde sea posible. Vea<a href="#d_chars">
Signos diacríticos</a> donde se explica como revisar
caracteres no-Latin-1.
</p>
<p>Si utiliza un teclado que no tiene los caracteres acentuados, existen
varias maneras de hacerlo:
</p>
<ul compact>
  <li> Los menús desplegables de la página de interfaz de revisión.</li>
  <li> Applets incluidos en su sistema de operación.
      <ul compact>
      <li>Windows: «Character Map»<br> Ruta de acceso:<br>
          Haga clic en Start, Run; charmap o<br>
          Haga clic en Start, Accessories, System Tools, Character Map.</li>
      <li>Macintosh: Key Caps o «Keyboard Viewer»<br>
          Para OS 9 y anteriores, está en Apple Menu,<br>
          Para OS X hasta 10.2, está en Applications, Utilities folder<br>
          Para OS X 10.3 y siguientes, está en Input Menu como «Keyboard Viewer.»</li>

      <li>Linux: Varias maneras, depende del escritorio.<br>
          Para KDE, intente KCharSelect (en el submenú Utilities del menú principal).</li>
      </ul>
  </li>
  <li>El programa en línea como<a
   href="http://free.pages.at/krauss/computer/xml/daten/edicode.html">Edicode</a>.</li>
  <li> Teclas personalizadas.<br>

       Tablas para <a href="#a_chars_win">Windows</a> y <a href="#a_chars_mac">Macintosh</a>
donde se muestra los atajos del teclado.</li>
  <li> Cambiar la configuración del teclado para que soporte caracteres acentuados ("deadkeys").
       <ul compact>
       <li>Windows: Panel de Control (Teclado, Input Locales)</li>
       <li>Macintosh: Input Menu (en la Barra del Menú)</li>
       <li>Linux: Modifique el teclado en su configuración X.</li>
      </ul>
</ul>
<p>El Proyecto Gutenberg aceptará como mínimo, versiones de textos 7-bit
ASCII, aunque acepta también otras versiones de codificación, que
conservan más información del texto original. Proyecto Gutemberg-Europa
utiliza UTF-8 como principal codificador, aunque están bienvenidas otras
formas de codificación. En este momento Distributed Proofreaders utiliza
Latin-1 o ISO 8859-1 y -15. pero en el futuro se incluirá Unicode.
Distributed Proofreaders Europe ya utiliza Unicode.
</p>

<!-- END RR -->
<a name="a_chars_win"></a>
<p><b>Para Windows</b>:
</p>
<ul compact>
  <li>Puede utilizar el &ldquo;Character Map&rdquo; (Start, Run, charmap) para elegir una letra; y después copiar y pegar.
  </li>

  <li>Menús desplegables de la interfaz &laquo;Enhanced&raquo; de revisión.
  </li>
  <li>O puede obtener los caracteres si oprime «Alt» + Número correspondiente del carácter que necesita.
      <br>
Este modo resulta el más rápido una vez haya aprendido los códigos correspondientes.<br>
(Estas instrucciones son para el teclado US-inglés. Puede que no funcionen con otros teclados.)
      <br>Oprima la tecla &ldquo;Alt&rdquo; y, sin soltarla, teclee las cuatro cifras en
el <i>teclado numérico</i> y entonces suelte la tecla &ldquo;Alt&rdquo; (línea de teclado numérico sobre las letras no funcionará para este fin).
      <br>Debe teclear las cuatro cifras, incluyendo el 0 (cero) al principio.
Como se puede dar cuenta, la letra mayúscula tiene el código de la minúscula
reducido en 32.
Igualmente existen configuraciones con las que no se puedan utilizar estos códigos.
      <br>En la siguiente tabla se ven los códigos utilizados ahora.
          (<a href="charwin.pdf">Tabla en inglés que puede imprimir)</a>
      <br>No debe utilizar otros caracteres aunque el gerente del proyecto lo diga
en los comentarios del proyecto<a href="#comments">Comentarios del proyecto</a>.
  </li>
</ul>

<br>
<table align="center" border="6" rules="all" summary="Atajos para Windows">
  <tbody>
  <tr>
      <th bgcolor="cornsilk" colspan=14>Atajos de Windows de los símbolos de Latin-1</th>
  </tr>

  <tr bgcolor="cornsilk">
      <th colspan=2>` grave</th>
      <th colspan=2>&acute; agudo (aigu)</th>
      <th colspan=2>^ circunflejo</th>
      <th colspan=2>~ tilde</th>
      <th colspan=2>&uml; diéresis</th>

      <th colspan=2>&deg; aro</th>
      <th colspan=2>&AElig; ligadura</th>
  </tr>
  <tr><td align="center" bgcolor="mistyrose" title="Pequeña a grave"         >&agrave; </td><td>Alt-0224</td>
      <td align="center" bgcolor="mistyrose" title="Pequeña a agudo"         >&aacute; </td><td>Alt-0225</td>

      <td align="center" bgcolor="mistyrose" title="Pequeña a circunflejo"    >&acirc;  </td><td>Alt-0226</td>
      <td align="center" bgcolor="mistyrose" title="Pequeña a tilde"         >&atilde; </td><td>Alt-0227</td>
      <td align="center" bgcolor="mistyrose" title="Pequeña a diéresis"        >&auml;   </td><td>Alt-0228</td>
      <td align="center" bgcolor="mistyrose" title="Pequeña a aro"          >&aring;  </td><td>Alt-0229</td>
      <td align="center" bgcolor="mistyrose" title="Pequeña ae ligadura"     >&aelig;  </td><td>Alt-0230</td>

  </tr>
  <tr><td align="center" bgcolor="mistyrose" title="A mayúscula grave"       >&Agrave; </td><td>Alt-0192</td>
      <td align="center" bgcolor="mistyrose" title="A mayúscula agudo"       >&Aacute; </td><td>Alt-0193</td>
      <td align="center" bgcolor="mistyrose" title="A mayúscula circunflejo"  >&Acirc;  </td><td>Alt-0194</td>
      <td align="center" bgcolor="mistyrose" title="A mayúscula tilde"       >&Atilde; </td><td>Alt-0195</td>

      <td align="center" bgcolor="mistyrose" title="A mayúscula diéresis"      >&Auml;   </td><td>Alt-0196</td>
      <td align="center" bgcolor="mistyrose" title="A mayúscula aro"        >&Aring;  </td><td>Alt-0197</td>
      <td align="center" bgcolor="mistyrose" title="AE mayúscula ligadura"   >&AElig;  </td><td>Alt-0198</td>
  </tr>
  <tr><td align="center" bgcolor="mistyrose" title="Pequeña e grave"         >&egrave; </td><td>Alt-0232</td>

      <td align="center" bgcolor="mistyrose" title="Pequeña e agudo"         >&eacute; </td><td>Alt-0233</td>
      <td align="center" bgcolor="mistyrose" title="Pequeña e circunflejo"    >&ecirc;  </td><td>Alt-0234</td>
      <td> </td><td> </td>
      <td align="center" bgcolor="mistyrose" title="Pequeña e diéresis"        >&euml;   </td><td>Alt-0235</td>
      <td> </td><td> </td>

      <td> </td><td> </td>
  </tr>
  <tr><td align="center" bgcolor="mistyrose" title="E mayúscula grave"       >&Egrave; </td><td>Alt-0200</td>
      <td align="center" bgcolor="mistyrose" title="E mayúscula agudo"       >&Eacute; </td><td>Alt-0201</td>
      <td align="center" bgcolor="mistyrose" title="E mayúscula circunflejo"  >&Ecirc;  </td><td>Alt-0202</td>

      <td> </td><td> </td>
      <td align="center" bgcolor="mistyrose" title="E mayúscula diéresis"      >&Euml;   </td><td>Alt-0203</td>
      <td> </td><td> </td>
      <td> </td><td> </td>
  </tr>

  <tr><td align="center" bgcolor="mistyrose" title="Pequeña i grave"         >&igrave; </td><td>Alt-0236</td>
      <td align="center" bgcolor="mistyrose" title="Pequeña i agudo"         >&iacute; </td><td>Alt-0237</td>
      <td align="center" bgcolor="mistyrose" title="Pequeña i circunflejo"    >&icirc;  </td><td>Alt-0238</td>
      <td> </td><td> </td>
      <td align="center" bgcolor="mistyrose" title="Pequeña i diéresis"        >&iuml;   </td><td>Alt-0239</td>

      <td> </td><td> </td>
      <td> </td><td> </td>
  </tr>
  <tr><td align="center" bgcolor="mistyrose" title="I mayúscula grave"       >&Igrave; </td><td>Alt-0204</td>
      <td align="center" bgcolor="mistyrose" title="I mayúscula agudo"       >&Iacute; </td><td>Alt-0205</td>

      <td align="center" bgcolor="mistyrose" title="I mayúscula circunflejo"  >&Icirc;  </td><td>Alt-0206</td>
      <td> </td><td> </td>
      <td align="center" bgcolor="mistyrose" title="I mayúscula diéresis"      >&Iuml;   </td><td>Alt-0207</td>
      <th colspan=2 bgcolor="cornsilk">/ barra</th>
      <th colspan=2 bgcolor="cornsilk">&OElig; ligadura</th>

  </tr>
  <tr><td align="center" bgcolor="mistyrose" title="Pequeña o grave"         >&ograve; </td><td>Alt-0242</td>
      <td align="center" bgcolor="mistyrose" title="Pequeña o agudo"         >&oacute; </td><td>Alt-0243</td>
      <td align="center" bgcolor="mistyrose" title="Pequeña o circunflejo"    >&ocirc;  </td><td>Alt-0244</td>
      <td align="center" bgcolor="mistyrose" title="Pequeña o tilde"         >&otilde; </td><td>Alt-0245</td>

      <td align="center" bgcolor="mistyrose" title="Pequeña o diéresis"        >&ouml;   </td><td>Alt-0246</td>
      <td align="center" bgcolor="mistyrose" title="Pequeña o barra"         >&oslash; </td><td>Alt-0248</td>
      <td align="center" bgcolor="mistyrose" title="Pequeña oe ligadura"     >&oelig;  </td><td>Use [oe]</td>
  </tr>
  <tr><td align="center" bgcolor="mistyrose" title="O mayúscula grave"       >&Ograve; </td><td>Alt-0210</td>

      <td align="center" bgcolor="mistyrose" title="O mayúscula agudo"       >&Oacute; </td><td>Alt-0211</td>
      <td align="center" bgcolor="mistyrose" title="O mayúscula circunflejo"  >&Ocirc;  </td><td>Alt-0212</td>
      <td align="center" bgcolor="mistyrose" title="O mayúscula tilde"       >&Otilde; </td><td>Alt-0213</td>
      <td align="center" bgcolor="mistyrose" title="O mayúscula diéresis"      >&Ouml;   </td><td>Alt-0214</td>
      <td align="center" bgcolor="mistyrose" title="O mayúscula barra"       >&Oslash; </td><td>Alt-0216</td>

      <td align="center" bgcolor="mistyrose" title="O mayúsculaE ligadura"   >&OElig;  </td><td>Use [OE]</td>
  </tr>
  <tr><td align="center" bgcolor="mistyrose" title="Pequeña u grave"         >&ugrave; </td><td>Alt-0249</td>
      <td align="center" bgcolor="mistyrose" title="Pequeña u agudo"         >&uacute; </td><td>Alt-0250</td>
      <td align="center" bgcolor="mistyrose" title="Pequeña u circunflejo"    >&ucirc;  </td><td>Alt-0251</td>

      <td> </td><td> </td>
      <td align="center" bgcolor="mistyrose" title="Pequeña u diéresis"        >&uuml;   </td><td>Alt-0252</td>
      <td> </td><td> </td>
      <td> </td><td> </td>
  </tr>

  <tr><td align="center" bgcolor="mistyrose" title="U mayúscula grave"       >&Ugrave; </td><td>Alt-0217</td>
      <td align="center" bgcolor="mistyrose" title="U mayúscula agudo"       >&Uacute; </td><td>Alt-0218</td>
      <td align="center" bgcolor="mistyrose" title="U mayúscula circunflejo"  >&Ucirc;  </td><td>Alt-0219</td>
      <td> </td><td> </td>
      <td align="center" bgcolor="mistyrose" title="U mayúscula diéresis"      >&Uuml;   </td><td>Alt-0220</td>

      <th colspan=2 bgcolor="cornsilk">dinero     </th>
      <th colspan=2 bgcolor="cornsilk">matemática  </th>
  </tr>
  <tr><td> </td><td> </td>
      <td> </td><td> </td>
      <td> </td><td> </td>

      <td align="center" bgcolor="mistyrose" title="Pequeña n tilde"         >&ntilde; </td><td>Alt-0241</td>
      <td align="center" bgcolor="mistyrose" title="Pequeña y diéresis"        >&yuml;   </td><td>Alt-0255</td>
      <td align="center" bgcolor="mistyrose" title="Cents"                 >&cent;   </td><td>Alt-0162</td>
      <td align="center" bgcolor="mistyrose" title="plus/minus"            >&plusmn; </td><td>Alt-0177</td>
  </tr>

  <tr><td> </td><td> </td>
      <td> </td><td> </td>
      <td> </td><td> </td>
      <td align="center" bgcolor="mistyrose" title="N mayúscula tilde"       >&Ntilde; </td><td>Alt-0209</td>
      <td align="center" bgcolor="mistyrose" title="Y mayúscula diéresis"      >&Yuml;   </td><td>Alt-0159</td>

      <td align="center" bgcolor="mistyrose" title="Pounds"                >&pound;  </td><td>Alt-0163</td>
      <td align="center" bgcolor="mistyrose" title="Multiplication"        >&times;  </td><td>Alt-0215</td>
  </tr>
  <tr><th colspan=2 bgcolor="cornsilk">cedilla </th>
      <th colspan=2 bgcolor="cornsilk">Islandés    </th>
      <th colspan=2 bgcolor="cornsilk">marcas        </th>

      <th colspan=2 bgcolor="cornsilk">acentos      </th>
      <th colspan=2 bgcolor="cornsilk">puntuación  </th>
      <td align="center" bgcolor="mistyrose" title="Yen"                   >&yen;    </td><td>Alt-0165</td>
      <td align="center" bgcolor="mistyrose" title="Division"              >&divide; </td><td>Alt-0247</td>
  </tr>
  <tr><td align="center" bgcolor="mistyrose" title="Pequeña c cedilla"       >&ccedil; </td><td>Alt-0231</td>

      <td align="center" bgcolor="mistyrose" title="Mayúscula Thorn"         >&THORN;  </td><td>Alt-0222</td>
      <td align="center" bgcolor="mistyrose" title="Copyright"             >&copy;   </td><td>Alt-0169</td>
      <td align="center" bgcolor="mistyrose" title="acento agudo"          >&acute;  </td><td>Alt-0180</td>
      <td align="center" bgcolor="mistyrose" title="Inverted Question Mark">&iquest; </td><td>Alt-0191</td>
      <td align="center" bgcolor="mistyrose" title="Dollars"               >&#036;   </td><td>Alt-0036</td>

      <td align="center" bgcolor="mistyrose" title="Logical Not"           >&not;    </td><td>Alt-0172</td>
  </tr>
  <tr><td align="center" bgcolor="mistyrose" title="Mayúscula C cedilla"     >&Ccedil; </td><td>Alt-0199</td>
      <td align="center" bgcolor="mistyrose" title="Pequeña thorn"           >&thorn;  </td><td>Alt-0254</td>
      <td align="center" bgcolor="mistyrose" title="Registration Mark"     >&reg;    </td><td>Alt-0174</td>

      <td align="center" bgcolor="mistyrose" title="diéresis accent"         >&uml;    </td><td>Alt-0168</td>
      <td align="center" bgcolor="mistyrose" title="Inverted Exclamation"  >&iexcl;  </td><td>Alt-0161</td>
      <td align="center" bgcolor="mistyrose" title="General Currency"      >&curren; </td><td>Alt-0164</td>
      <td align="center" bgcolor="mistyrose" title="Degrees"               >&deg;    </td><td>Alt-0176</td>
  </tr>

  <tr><th colspan=2 bgcolor="cornsilk">superíndices        </th>
      <td align="center" bgcolor="mistyrose" title="Eth mayúscula"           >&ETH;    </td><td>Alt-0208</td>
      <td align="center" bgcolor="mistyrose" title="Trademark"             >&trade;  </td><td>Alt-0153</td>
      <td align="center" bgcolor="mistyrose" title="macron accent"         >&macr;   </td><td>Alt-0175</td>
      <td align="center" bgcolor="mistyrose" title="guillemot left"        >&laquo;  </td><td>Alt-0171</td>

      <td> </td><td> </td>
      <td align="center" bgcolor="mistyrose" title="Micro"                 >&micro;  </td><td>Alt-0181</td>
  </tr>
  <tr><td align="center" bgcolor="mistyrose" title="superíndice 1"         >&sup1;   </td><td>Alt-0185</td>
      <td align="center" bgcolor="mistyrose" title="Pequeña eth"             >&eth;    </td><td>Alt-0240</td>

      <td align="center" bgcolor="mistyrose" title="Paragraph (pilcrow)"   >&para;   </td><td>Alt-0182</td>
      <td align="center" bgcolor="mistyrose" title="cedilla"               >&cedil;  </td><td>Alt-0184</td>
      <td align="center" bgcolor="mistyrose" title="guillemot right"       >&raquo;  </td><td>Alt-0187</td>
      <th colspan=2 bgcolor="cornsilk">ordinals  </th>
      <td align="center" bgcolor="mistyrose" title="1/4 Fraction"          >&frac14; <sup><small>1</small></sup></td><td>Alt-0188</td>

  </tr>
  <tr><td align="center" bgcolor="mistyrose" title="superíndice 2"         >&sup2;   </td><td>Alt-0178</td>
      <th colspan=2 bgcolor="cornsilk">sz ligadura        </th>
      <td align="center" bgcolor="mistyrose" title="Section"               >&sect;   </td><td>Alt-0167</td>
      <td> </td><td> </td>

      <td align="center" bgcolor="mistyrose" title="Middle dot"            >&middot; </td><td>Alt-0183</td>
      <td align="center" bgcolor="mistyrose" title="Masculine Ordinal"     >&ordm;   </td><td>Alt-0186</td>
      <td align="center" bgcolor="mistyrose" title="1/2 Fraction"          >&frac12; <sup><small>1</small></sup></td><td>Alt-0189</td>
  </tr>
  <tr><td align="center" bgcolor="mistyrose" title="superíndice 3"         >&sup3;   </td><td>Alt-0179</td>

      <td align="center" bgcolor="mistyrose" title="sz ligadura"           >&szlig;  </td><td>Alt-0223</td>
      <td align="center" bgcolor="mistyrose" title="Broken Vertical bar"   >&brvbar; </td><td>Alt-0166</td>
      <td> </td><td> </td>
      <td align="center" bgcolor="mistyrose" title="asterisk"              >&#042;   </td><td>Alt-0042</td>
      <td align="center" bgcolor="mistyrose" title="Feminine Ordinal"      >&ordf;   </td><td>Alt-0170</td>

      <td align="center" bgcolor="mistyrose" title="3/4 Fraction"          >&frac34; <sup><small>1</small></sup></td><td>Alt-0190</td>
  </tr>
  </tbody>
</table>
<p>
Note: en la mayoría de los casos las dos letras individuales (o y e) han reemplazado las ligaduras. (&OElig; se vuelve en Oedipus.) Eche un vistazo a los Comentarios del proyecto para ver si el gerente de proyecto quiere utilizar la ligadura; si no, utilice las dos letras.<br>
<sup><small>1</small></sup>Note: Si no está escrito especialmente en los
Comentarios del proyecto, no utilice los atajos («Alt» y número) símbolos para
fracciones. En vez de eso haga los fracciones como dice las reglas de revisión
en cuanto a los fracciones (&frac12;, &frac14;, etc.).<a href="#fract_s">Fracciones</a><br>


<p> <b>Para Apple Macintosh</b>:
</p>
<ul compact>
  <li>Puede utilizar el programa «Key Caps» como referencia.<br>
En OS 9 y anteriores, está en el menú de Apple; OS X hasta 10.2 está en «Applications, Utilities.»<br>
Se visualiza imagen del teclado y si oprime «shift, opt, command» o combinación de esas teclas, le muestra cómo crear cada carácter. Utilice esta referencia para ver cómo teclear el carácter deseado, o puede copiar desde aquí y pegar al texto de la interfaz de revisión.
</li>

  <li>En OS X 10.3 y siguientes, la misma función está ahora en una paleta disponible en el menú «Input» (el menú junto a «locale's flag icon» en la barra del menú).  Se llama «Show Keyboard Viewer.» Si no está en su menú de «Input» o no tiene ese menú puede activarlo abriendo «System Preferences,» el «International panel» y elegir «Input Menu.»  El «Show input menu in menu bar» debe estar marcado.  En la vista del «spreadsheet» marque la casilla de «Keyboard Viewer» en adición a cualquier «locales» que utilice.
En OS X 10.3 y siguientes, la misma función está ahora en una paleta disponible en el menú «Input» (el menú junto a «locale's flag icon» en la barra del menú).  Se llama «Show Keyboard Viewer.» Si no está en su menú de «Input» o no tiene ese menú puede activarlo abriendo «System Preferences,» el «International panel» y elegir «Input Menu.»  El «Show input menu in menu bar» debe estar marcado.  En la vista del «spreadsheet» marque la casilla de «Keyboard Viewer» en adición a cualquier «locales» que utilice.<br>
  </li>
  <li>
Si trabaja con la interfaz mejorada «enhanced», haga clic en &ldquo;more&rdquo; y se abrirá un menú desplegable mostrando las letras que se puedan copiar y pegar.
</li>
<li>O se pueden teclear los atajos de Apple Opt de los caracteres deseados.

      <br>Una vez acostumbrado a los códigos es la manera más rápida que copiar y pegar.
      <br>Oprima la tecla «Opt» y el acento; después teclee la letra que quiere acentuar (para algunos códigos, basta con oprimir «Opt» y la letra).
      <br>Estas instrucciones son para el teclado US-inglés. Puede que no funcionen con otros teclados.
      <br>La tabla siguiente muestra los códigos que utilizamos.
          (<a href="charapp.pdf">Una versión en inglés para imprimir.)</a>

      <br>Note: Por favor no utilice otros caracteres «especiales» aunque el gerente del proyecto lo diga en los Comentarios del proyecto. <a href="#comments">Comentarios del proyecto</a>.
  </li>
</ul>

<br>
<a name="a_chars_mac"></a>
<table align="center" border="6" rules="all" summary="Mac shortcuts">
  <tbody>
  <tr bgcolor="cornsilk"  >
      <th colspan=14>Atajos para los símbolos de Latin-1 para Apple Mac</th>

  <tr bgcolor="cornsilk"  >
      <th colspan=2>` grave</th>
      <th colspan=2>&acute; agudo (aigu)</th>
      <th colspan=2>^ circunflejo</th>
      <th colspan=2>~ tilde</th>
      <th colspan=2>&uml; diéresis</th>

      <th colspan=2>&deg; aro</th>
      <th colspan=2>&AElig; ligadura</th>
  </tr>
  <tr><td align="center" bgcolor="mistyrose" title="Pequeña a grave"         >&agrave; </td><td>Opt-`, a</td>
      <td align="center" bgcolor="mistyrose" title="Pequeña a agudo"         >&aacute; </td><td>Opt-e, a</td>

      <td align="center" bgcolor="mistyrose" title="Pequeña a circunflejo"    >&acirc;  </td><td>Opt-i, a</td>
      <td align="center" bgcolor="mistyrose" title="Pequeña a tilde"         >&atilde; </td><td>Opt-n, a</td>
      <td align="center" bgcolor="mistyrose" title="Pequeña a diéresis"        >&auml;   </td><td>Opt-u, a</td>
      <td align="center" bgcolor="mistyrose" title="Pequeña a aro"          >&aring;  </td><td>Opt-a   </td>
      <td align="center" bgcolor="mistyrose" title="Pequeña ae ligadura"     >&aelig;  </td><td>Opt-'   </td>

  </tr>
  <tr><td align="center" bgcolor="mistyrose" title="A mayúscula grave"       >&Agrave; </td><td>Opt-~, A</td>
      <td align="center" bgcolor="mistyrose" title="A mayúscula agudo"       >&Aacute; </td><td>Opt-e, A</td>
      <td align="center" bgcolor="mistyrose" title="A mayúscula circunflejo"  >&Acirc;  </td><td>Opt-i, A</td>
      <td align="center" bgcolor="mistyrose" title="A mayúscula tilde"       >&Atilde; </td><td>Opt-n, A</td>

      <td align="center" bgcolor="mistyrose" title="A mayúscula diéresis"      >&Auml;   </td><td>Opt-u, A</td>
      <td align="center" bgcolor="mistyrose" title="A mayúscula aro"        >&Aring;  </td><td>Opt-A   </td>
      <td align="center" bgcolor="mistyrose" title="A mayúsculaE ligadura"   >&AElig;  </td><td>Opt-"   </td>
  </tr>
  <tr><td align="center" bgcolor="mistyrose" title="Pequeña e grave"         >&egrave; </td><td>Opt-~, e</td>

      <td align="center" bgcolor="mistyrose" title="Pequeña e agudo"         >&eacute; </td><td>Opt-e, e</td>
      <td align="center" bgcolor="mistyrose" title="Pequeña e circunflejo"    >&ecirc;  </td><td>Opt-i, e</td>
      <td> </td><td> </td>
      <td align="center" bgcolor="mistyrose" title="Pequeña e diéresis"        >&euml;   </td><td>Opt-u, e</td>
      <td> </td><td> </td>

      <td> </td><td> </td>
  </tr>
  <tr><td align="center" bgcolor="mistyrose" title="E mayúscula grave"       >&Egrave; </td><td>Opt-~, E</td>
      <td align="center" bgcolor="mistyrose" title="E mayúscula agudo"       >&Eacute; </td><td>Opt-e, E</td>
      <td align="center" bgcolor="mistyrose" title="E mayúscula circunflejo"  >&Ecirc;  </td><td>Opt-i, E</td>

      <td> </td><td> </td>
      <td align="center" bgcolor="mistyrose" title="E mayúscula diéresis"      >&Euml;   </td><td>Opt-u, E</td>
      <td> </td><td> </td>
      <td> </td><td> </td>
  </tr>

  <tr><td align="center" bgcolor="mistyrose" title="Pequeña i grave"         >&igrave; </td><td>Opt-~, i</td>
      <td align="center" bgcolor="mistyrose" title="Pequeña i agudo"         >&iacute; </td><td>Opt-e, i</td>
      <td align="center" bgcolor="mistyrose" title="Pequeña i circunflejo"    >&icirc;  </td><td>Opt-i, i</td>
      <td> </td><td> </td>
      <td align="center" bgcolor="mistyrose" title="Pequeña i diéresis"        >&iuml;   </td><td>Opt-u, i</td>

      <td> </td><td> </td>
      <td> </td><td> </td>
  </tr>
  <tr><td align="center" bgcolor="mistyrose" title="I mayúscula grave"       >&Igrave; </td><td>Opt-~, I</td>
      <td align="center" bgcolor="mistyrose" title="I mayúscula agudo"       >&Iacute; </td><td>Opt-e, I</td>

      <td align="center" bgcolor="mistyrose" title="I mayúscula circunflejo"  >&Icirc;  </td><td>Opt-i, I</td>
      <td> </td><td> </td>
      <td align="center" bgcolor="mistyrose" title="I mayúscula diéresis"      >&Iuml;   </td><td>Opt-u, I</td>
      <th colspan=2 bgcolor="cornsilk">/ barra</th>
      <th colspan=2 bgcolor="cornsilk">&OElig; ligadura</th>

  </tr>
  <tr><td align="center" bgcolor="mistyrose" title="Pequeña o grave"         >&ograve; </td><td>Opt-~, o</td>
      <td align="center" bgcolor="mistyrose" title="Pequeña o agudo"         >&oacute; </td><td>Opt-e, o</td>
      <td align="center" bgcolor="mistyrose" title="Pequeña o circunflejo"    >&ocirc;  </td><td>Opt-i, o</td>
      <td align="center" bgcolor="mistyrose" title="Pequeña o tilde"         >&otilde; </td><td>Opt-n, o</td>

      <td align="center" bgcolor="mistyrose" title="Pequeña o diéresis"        >&ouml;   </td><td>Opt-u, o</td>
      <td align="center" bgcolor="mistyrose" title="Pequeña o barra"         >&oslash; </td><td>Opt-o   </td>
      <td align="center" bgcolor="mistyrose" title="Pequeña oe ligadura"     >&oelig;  </td><td>Use [oe]</td>
  </tr>
  <tr><td align="center" bgcolor="mistyrose" title="O mayúscula grave"       >&Ograve; </td><td>Opt-~, O</td>

      <td align="center" bgcolor="mistyrose" title="O mayúscula agudo"       >&Oacute; </td><td>Opt-e, O</td>
      <td align="center" bgcolor="mistyrose" title="I mayúscula circunflejo"  >&Ocirc;  </td><td>Opt-i, O</td>
      <td align="center" bgcolor="mistyrose" title="O mayúscula tilde"       >&Otilde; </td><td>Opt-n, O</td>
      <td align="center" bgcolor="mistyrose" title="O mayúscula diéresis"      >&Ouml;   </td><td>Opt-u, O</td>
      <td align="center" bgcolor="mistyrose" title="O mayúscula barra"       >&Oslash; </td><td>Opt-O   </td>

      <td align="center" bgcolor="mistyrose" title="O mayúsculaE ligadura"   >&OElig;  </td><td>Use [OE]</td>
  </tr>
  <tr><td align="center" bgcolor="mistyrose" title="Pequeña u grave"         >&ugrave; </td><td>Opt-~, u</td>
      <td align="center" bgcolor="mistyrose" title="Pequeña u agudo"         >&uacute; </td><td>Opt-e, u</td>
      <td align="center" bgcolor="mistyrose" title="Pequeña u circunflejo"    >&ucirc;  </td><td>Opt-i, u</td>

      <td> </td><td> </td>
      <td align="center" bgcolor="mistyrose" title="Pequeña u diéresis"        >&uuml;   </td><td>Opt-u, u</td>
      <td> </td><td> </td>
      <td> </td><td> </td>
  </tr>

  <tr><td align="center" bgcolor="mistyrose" title="U mayúscula grave"       >&Ugrave; </td><td>Opt-~, U</td>
      <td align="center" bgcolor="mistyrose" title="U mayúscula agudo"       >&Uacute; </td><td>Opt-e, U</td>
      <td align="center" bgcolor="mistyrose" title="U mayúscula circunflejo"  >&Ucirc;  </td><td>Opt-i, U</td>
      <td> </td><td> </td>
      <td align="center" bgcolor="mistyrose" title="U mayúscula diéresis"      >&Uuml;   </td><td>Opt-u, U</td>

      <th colspan=2 bgcolor="cornsilk">dinero     </th>
      <th colspan=2 bgcolor="cornsilk">matemática  </th>
  </tr>
  <tr><td> </td><td> </td>
      <td> </td><td> </td>
      <td> </td><td> </td>

      <td align="center" bgcolor="mistyrose" title="Pequeña n tilde"         >&ntilde; </td><td>Opt-n, n</td>
      <td align="center" bgcolor="mistyrose" title="Pequeña y diéresis"        >&yuml;   </td><td>Opt-u, y</td>
      <td align="center" bgcolor="mistyrose" title="Cents"                 >&cent;   </td><td>Opt-4   </td>
      <td align="center" bgcolor="mistyrose" title="plus/minus"            >&plusmn; </td><td>Opt-+   </td>
  </tr>

  <tr><td> </td><td> </td>
      <td> </td><td> </td>
      <td> </td><td> </td>
      <td align="center" bgcolor="mistyrose" title="N mayúscula tilde"       >&Ntilde; </td><td>Opt-n, N</td>
      <td align="center" bgcolor="mistyrose" title="Capital Y diéresis"      >&Yuml;   </td><td>Opt-u, Y</td>

      <td align="center" bgcolor="mistyrose" title="Pounds"                >&pound;  </td><td>Opt-3   </td>
      <td align="center" bgcolor="mistyrose" title="Multiplication"        >&times;  </td><td>(none)&nbsp;&dagger;</td>
  </tr>
  <tr><th colspan=2 bgcolor="cornsilk">cedilla </th>
      <th colspan=2 bgcolor="cornsilk">Islandés    </th>
      <th colspan=2 bgcolor="cornsilk">marcas        </th>

      <th colspan=2 bgcolor="cornsilk">acentos      </th>
      <th colspan=2 bgcolor="cornsilk">puntuación  </th>
      <td align="center" bgcolor="mistyrose" title="Yen"                   >&yen;    </td><td>Opt-y   </td>
      <td align="center" bgcolor="mistyrose" title="Division"              >&divide; </td><td>Opt-/   </td>
  </tr>
  <tr><td align="center" bgcolor="mistyrose" title="Pequeña c cedilla"       >&ccedil; </td><td>Opt-c   </td>

      <td align="center" bgcolor="mistyrose" title="Capital Thorn"         >&THORN;  </td><td>(none)&nbsp;&Dagger;</td>
      <td align="center" bgcolor="mistyrose" title="Copyright"             >&copy;   </td><td>Opt-g   </td>
      <td align="center" bgcolor="mistyrose" title="acento agudo"          >&acute;  </td><td>Opt-E   </td>
      <td align="center" bgcolor="mistyrose" title="Inverted Question Mark">&iquest; </td><td>Opt-?   </td>
      <td align="center" bgcolor="mistyrose" title="Dollars"               >&#036;   </td><td>Shift-4</td>

      <td align="center" bgcolor="mistyrose" title="Logical Not"           >&not;    </td><td>Opt-l   </td>
  </tr>
  <tr><td align="center" bgcolor="mistyrose" title="Capital C cedilla"     >&Ccedil; </td><td>Opt-C   </td>
      <td align="center" bgcolor="mistyrose" title="Pequeña thorn"           >&thorn;  </td><td>Shift-Opt-6</td>
      <td align="center" bgcolor="mistyrose" title="Registration Mark"     >&reg;    </td><td>Opt-r   </td>

      <td align="center" bgcolor="mistyrose" title="diéresis"         >&uml;    </td><td>Opt-U   </td>
      <td align="center" bgcolor="mistyrose" title="Inverted Exclamation"  >&iexcl;  </td><td>Opt-1   </td>
      <td align="center" bgcolor="mistyrose" title="General Currency"      >&curren; </td><td>Shift-Opt-2</td>
      <td align="center" bgcolor="mistyrose" title="Degrees"               >&deg;    </td><td>Opt-*   </td>
  </tr>

  <tr><th colspan=2 bgcolor="cornsilk">superíndices        </th>
      <td align="center" bgcolor="mistyrose" title="E mayúsculath"           >&ETH;    </td><td>(none)&nbsp;&Dagger;  </td>
      <td align="center" bgcolor="mistyrose" title="Trademark"             >&trade;  </td><td>Opt-2   </td>
      <td align="center" bgcolor="mistyrose" title="macron accent"         >&macr;   </td><td>Shift-Opt-,</td>
      <td align="center" bgcolor="mistyrose" title="guillemot left"        >&laquo;  </td><td>Opt-\   </td>

      <td> </td><td> </td>
      <td align="center" bgcolor="mistyrose" title="Micro"                 >&micro;  </td><td>Opt-m   </td>
  </tr>
  <tr><td align="center" bgcolor="mistyrose" title="superíndice 1"         >&sup1;   </td><td>(none)&nbsp;&Dagger;  </td>
      <td align="center" bgcolor="mistyrose" title="Pequeña eth"             >&eth;    </td><td>(none)&nbsp;&Dagger;  </td>

      <td align="center" bgcolor="mistyrose" title="Paragraph (pilcrow)"   >&para;   </td><td>Opt-7   </td>
      <td align="center" bgcolor="mistyrose" title="cedilla"               >&cedil;  </td><td>Opt-Z   </td>
      <td align="center" bgcolor="mistyrose" title="guillemot right"       >&raquo;  </td><td>Shift-Opt-\</td>
      <th colspan=2 bgcolor="cornsilk">ordinals  </th>
      <td align="center" bgcolor="mistyrose" title="1/4 Fraction"          >&frac14; </td><td>(none)&nbsp;&Dagger;<sup><small>1</small></sup>  </td>

  </tr>
  <tr><td align="center" bgcolor="mistyrose" title="superíndice 2"         >&sup2;   </td><td>(none)&nbsp;&Dagger;  </td>
      <th colspan=2 bgcolor="cornsilk">sz ligadura        </th>
      <td align="center" bgcolor="mistyrose" title="Section"               >&sect;   </td><td>Opt-6   </td>
      <td> </td><td> </td>

      <td align="center" bgcolor="mistyrose" title="Middle dot"            >&middot; </td><td>Opt-8  </td>
      <td align="center" bgcolor="mistyrose" title="Masculine Ordinal"     >&ordm;   </td><td>Opt-0   </td>
      <td align="center" bgcolor="mistyrose" title="1/2 Fraction"          >&frac12; </td><td>(none)&nbsp;&Dagger;<sup><small>1</small></sup>  </td>
  </tr>
  <tr><td align="center" bgcolor="mistyrose" title="superíndice 3"         >&sup3;   </td><td>(none)&nbsp;&Dagger;  </td>

      <td align="center" bgcolor="mistyrose" title="sz ligadura"           >&szlig;  </td><td>Opt-s   </td>
      <td align="center" bgcolor="mistyrose" title="Broken Vertical bar"   >&brvbar; </td><td>(none)&nbsp;&Dagger;  </td>
      <td> </td><td> </td>
      <td align="center" bgcolor="mistyrose" title="asterisk"              >&#042;   </td><td>(none)&nbsp;&Dagger;  </td>

      <td align="center" bgcolor="mistyrose" title="Feminine Ordinal"      >&ordf;   </td><td>Opt-9   </td>
      <td align="center" bgcolor="mistyrose" title="3/4 Fraction"          >&frac34; </td><td>(none)&nbsp;&Dagger;<sup><small>1</small></sup>  </td>
  </tr>
  </tbody>
</table>
<p>&Dagger;&nbsp;Note: No hay equivalente; utilice los menús desplegables.
</p>

<p><sup><small>1</small></sup>Note: Si no está escrito especialmente en los Comentarios del proyecto, no utilice los atajos ("Alt" y número) símbolos para fracciones. En vez de eso haga los fracciones como dice las reglas de revisión en cuanto a los fracciones (&frac12;, &frac14;, etc.):
<a href="#fract_s">Fracciones</a>. (1/2, 1/4, 3/4, etc.)
</p>

<h3><a name="d_chars">Signos diacríticos</a></h3>
<p>
En algunos proyectos se encontrarán signos especiales ubicados encima o
debajo del carácter latín normal (A-Z).  Estos signos se llaman
<i>diacríticos</i>. Indican la variación del valor fonético de la letra.
Cuando revisamos podemos indicarlos dentro del sistema ASCII normal utilizando
unos códigos especiales.  Por ejemplo, &#259; se indica como <tt>[)a]</tt>... el acento en
forma de u encima del carácter. Para un acento en forma de u debajo del carácter
se indica así: <tt>[a)]</tt>
<p>No olvide incluir los paréntesis así: (<tt>[&nbsp;]</tt>) para que el pos-procesador
sepa a qué
letra se refiere el signo.</p><p>
El pos-procesador eventualmente reemplazará estos signos con símbolos
que funcionen en el texto que está procesando, como 7-bit, ASCII, 8-bit,
 Unicode, html, etc.</p>

<p>
Fíjese que hay algunos signos diacríticos (la mayoría vocales) que ya se
encuentran entre los caracteres Latin-1 que utilizamos normalmente.
<b>En este caso, utilice el carácter Latin-1 disponible en el menú de la interfaz
del proyecto</b> (vea <a href="#a_chars">aquí</a>).
</p>

<!-- END RR -->

<p>En la siguiente tabla se ven los códigos utilizados ahora:<br>
El «x" representa cualquier carácter con signo diacrítico.<br>
(Cuando revise utilice el carácter del texto que está revisando, y no la «x" mostrada en la tabla.)
</p>

<!--
  diacritical mark           above  below
macron (straight line)       [=x]   [x=]
2 dots (diaresis or umlaut)  [:x]   [x:]
1 dot                        [.x]   [x.]
grave accent                 ['x]   [x'] or [/x] [x/]
acute (aigu) accent          [`x]   [x`] or [\x] [x\]
circumflex                   [^x]   [x^]
caron (v-shaped symbol)      [vx]   [xv]
breve (u-shaped symbol)      [)x]   [x)]
tilde                        [~x]   [x~]
cedilla                      [,x]   [x,]
-->

<table align="center" border="6" rules="all" summary="Diacriticals">
  <tbody>
  <tr bgcolor="cornsilk">
      <th colspan=4>S&iacute;mbolos de Revisi&oacute;n para Signos Diacr&iacute;ticos</th>

  <tr bgcolor="cornsilk">
      <th>signo diacr&iacute;tico</th>
      <th>ejemplo</th>
      <th>encima</th>
      <th>debajo</th>
   </tr>
  <tr><td>macron (línea recta)</td>

      <td align="center">&macr;</td>
      <td align="center"><tt>[=x]</tt></td>
      <td align="center"><tt>[x=]</tt></td>
      </tr>
  <tr><td>2 puntos (diéresis/umlaut)</td>
      <td align="center">&uml;</td>
      <td align="center"><tt>[:x]</tt></td>

      <td align="center"><tt>[x:]</tt></td>
      </tr>
  <tr><td>1 punto</td>
      <td align="center">&middot;</td>
      <td align="center"><tt>[.x]</tt></td>
      <td align="center"><tt>[x.]</tt></td>
      </tr>

  <tr><td>acento grave</td>
      <td align="center">`</td>
      <td align="center"><tt>[`x]</tt> or <tt>[\x]</tt></td>
      <td align="center"><tt>[x`]</tt> or <tt>[x\]</tt></td>

      </tr>
  <tr><td>acento agudo (aigu)</td>
      <td align="center">&acute;</td>
      <td align="center"><tt>['x]</tt> or <tt>[/x]</tt></td>
      <td align="center"><tt>[x']</tt> or <tt>[x/]</tt></td>

      </tr>
  <tr><td>acento circunflejo</td>
      <td align="center">&circ;</td>
      <td align="center"><tt>[^x]</tt></td>
      <td align="center"><tt>[x^]</tt></td>
      </tr>
  <tr><td>caron (s&iacute;mbolo en forma de v)</td>

      <td align="center"><font size="-2">&or;</font></td>
      <td align="center"><tt>[vx]</tt></td>
      <td align="center"><tt>[xv]</tt></td>
      </tr>
  <tr><td>breve (s&iacute;mbolo en forma de u)</td>
      <td align="center"><font size="-2">&cup;</font></td>
      <td align="center"><tt>[)x]</tt></td>

      <td align="center"><tt>[x)]</tt></td>
      </tr>
  <tr><td>tilde</td>
      <td align="center">&tilde;</td>
      <td align="center"><tt>[~x]</tt></td>
      <td align="center"><tt>[x~]</tt></td>
      </tr>

  <tr><td>cedilla</td>
      <td align="center">&cedil;</td>
      <td align="center"><tt>[,x]</tt></td>
      <td align="center"><tt>[x,]</tt></td>
      </tr>
  </tbody>
</table>

<h3><a name="f_chars">Caracteres no-Latinos</a></h3>
<p>
En algunos proyectos hay textos en caracteres no-latinos, es decir otros caracteres aparte de los «Latinos A-Z."  Por ejemplo: griego, cirílico, hebreo o árabe.
</p><p>
Para el griego debe hacer una transliteración: es decir, convertir cada carácter
del alfabeto griego en la letra equivalente de ASCII (letras latinas).  En la
interfaz de revisión hay una herramienta que hace esta tarea más fácil.
</p>
<p>
Oprima el botón «Greek" (griego en inglés) (lo encontrará al fondo de la interfaz de revisión) para
abrir la herramienta.  Haga clic en el carácter griego que necesita y el carácter
apropiado de ASCII aparecerá en la ventana de texto de la herramienta. Terminado
con todo el texto en griego, copie la transliteración y pegue la en la página que
está revisando. Sitúe estos caracteres entre paréntesis y el signo que indica
griego; así... [Greek: texto transliterado].  Por ejemplo:
<b>&Beta;&iota;&beta;&lambda;&omicron;&sigmaf;</b> será revisado como
<tt>[Greek: Biblos]</tt>. (Que quiere decir Libro--¡apropiado para DP!)
</p>
<p>
Si no está seguro de su transliteración, ponga una señal (así <tt>*</tt>) para llamar la
atención a la persona que hace la siguiente ronda de revisión.
</p>
<p>
Para otros idiomas que no se puedan transliterar fácilmente (como
cirílico, hebreo o árabe) deje el texto como aparece codificado por el
OCR y sitúe estos caracteres/palabras/frases entre paréntesis y el signo
que indica el idioma si se sabe...así: <tt>[Cyrillic:&nbsp;**]</tt>,
<tt>[Hebrew:&nbsp;**]</tt>,
<tt>[Arabic:&nbsp;**]</tt>.
Incluya los <tt>**</tt> para llamar la atención del pos-procesador.
</p>
<!-- END RR -->

<ul compact>
  <li>Griego: <a href="http://www.gutenberg.org/howto/greek/"> Greek HOWTO</a> (de Proyecto Gutenberg) o vea la herramienta en la interfaz de revisión del proyecto.
  </li>
<li>Cirílico: Aunque existe un sistema de transliteración de cirílico, le
sugerimos que intente una transliteración solamente si domina el idioma.
Si no, márquelo tan solo de la manera indicada arriba. Puede que
encuentre útil <a href="http://learningrussian.com/transliteration.htm"> esta
tabla de transliteración.</a>
  </li>
  <li>
Hebreo y árabe: No lo intente a menos que domine el idioma. Hay muchas
dificultades para transliterar estos idiomas. Ni <a href="http://www.pgdp.net">
Distributed Proofreaders</a> ni <a href="http://www.gutenberg.org/"> Project
Gutenberg</a> han elegido un método estandardizado.</li>
</ul>

<h3><a name="fract_s">Fracciones</a></h3>
<p>Revise fracciones así:<tt>2&frac12;</tt> se convierte en <tt>2-1/2</tt>.
El guión evita que se separen el número y las fracciones cuando las líneas se formateen en el pos-proceso.
</p>


<h3><a name="em_dashes">Guiones, el signo menos "-" y rayas</a></h3>
<p> Generalmente hay cuatro tipos de guiones que se encuentran en los libros:
  <ol compact>
    <li><i>Guiones de separación</i>.
Se utilizan para juntar dos palabras, o a veces <b>juntan</b> prefijos y sufijos a las palabras.
    <br>
Déjelos como simple guión, sin ningún espacio por delante o detrás.<br>Fíjese en la excepción del segundo caso.
    </li>
    <li><i>El signo menos "-"</i> (En-dash).
Estos pueden ser un poco más largos y se utilizan para indicar una <b>escala/rango</b> de números o el signo <b>matemático</b>.
    <br>
Revísalos como guión simple.  Los espacios (antes o después) tienen que coincidir
con el texto original; normalmente no hay espacios para escalas de números, y sí
antes del menos... a veces también detrás de él.
   </li>

    <li><i>Guión largo</i> (Em-dash, long dash).
 Estos separan las palabras&mdash;a veces para enfatizar algo, así&mdash;o cuando el que habla vacila antes de completar la frase&mdash;!

Revíselos como dos guiones (--) si son de tamaño normal, o cuatro
guiones (----) si el guión es más largo de lo normal. No deje ningún
espacio en blanco antes ni tampoco después, aunque parezca que en el
texto original haya espacios.
    </li>
    <li><i>Palabras o nombres omitidos a propósito</i>.
    <br>
Revíselos como cuatro guiones (----). Si reemplazan una palabra, deje un espacio en blanco antes y después de los guiones, como si se tratara de una palabra de verdad. Si es sólo parte de una palabra la que está sustituida, no deje espacio--júntelos con el resto de la palabra.<br>
Si la parte omitida de la palabra aparece de tamaño de un guión largo, revíselo como tal: dos guiones.    </li>

  </ol>
<p>
Note: Si un guión largo (em-dash) aparece al principio o al final de la
línea del texto de OCR, júntelo con la otra línea de texto para que no
haya espacios alrededor de el. Solamente si el autor utilizó un guión
largo (em-dash) al comienzo o al final del párrafo, línea de poesía o
diálogo se debe dejar como está. Vea los ejemplos que siguen.
</p>
<!-- END RR -->

<p><b>Ejemplos</b>&mdash;Guiones, signo menos "-" y rayas:
</p>

<table width="100%" align="center" border="1"  cellpadding="4" cellspacing="0" summary="Hyphens and Dashes">
  <tbody>
    <tr>
      <th valign="top" bgcolor="cornsilk">Imagen Original:</th>

      <th valign="top" bgcolor="cornsilk">Texto Revisado Correctamente:</th>
      <th valign="top" bgcolor="cornsilk">Tipo</th>
    </tr>
    <tr>
      <td valign="top">semi-detached</td>
      <td valign="top"><tt>semi-detached</tt></td>
      <td> Gui&oacute;n</td>

    </tr>
    <tr>
      <td valign="top">three- and four-part harmony</td>
      <td valign="top"><tt>three- and four-part harmony</tt></td>
      <td> Gui&oacute;n</td>
    </tr>
    <tr>

      <td valign="top">discoveries which the Crus-<br>
        aders made and brought home with</td>
      <td valign="top"><tt>discoveries which the Crusaders<br>
        made and brought home with</tt></td>
      <td> Gui&oacute;n</td>
    </tr>

    <tr>
      <td valign="top">factors which mold char-<br>
        acter&mdash;environment, training and heritage,</td>
      <td valign="top"><tt>factors which mold character--environment,<br>
        training and heritage,</tt>
      <td> Gui&oacute;n largo</td>

    </tr>
    <tr>
      <td valign="top">See pages 21&ndash;25</td>
      <td valign="top"><tt>See pages 21-25</tt></td>
      <td>Signo menos</td>
    </tr>
    <tr>

      <td valign="top">&ndash;14&deg; below zero</td>
      <td valign="top"><tt>-14&deg; below zero</tt></td>
      <td>Signo menos</td>
    </tr>
    <tr>

      <td valign="top">X &ndash; Y = Z</td>
      <td valign="top"><tt>X - Y = Z</tt></td>
      <td>Signo menos</td>
    </tr>
    <tr>
      <td valign="top">2&ndash;1/2</td>

      <td valign="top"><tt>2-1/2</tt></td>
      <td>Signo menos</td>
    </tr>
    <tr>
      <td valign="top">I am hurt;&mdash;A plague<br> on both your houses!&mdash;I am dead.</td>

      <td valign="top"><tt>I am hurt;--A plague<br> on both your houses!--I am dead.</tt></td>
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
      <td valign="top">It is the east, and Juliet is the sun!&mdash;</td>

      <td valign="top"><tt>It is the east, and Juliet is the sun!--</tt></td>
      <td>Gui&oacute;n largo</td>
    </tr>
 <tr>
      <td valign="top">"Three hundred&mdash;&mdash;" "years," she was going to
	say, but the left-hand cat interrupted her.</td>
      <td valign="top"><tt>"Three hundred----" "years," she was going to
	say, but the left-hand cat interrupted her.</tt></td>

      <td>Raya</td>
    </tr>
    <tr>
      <td valign="top">As the witness Mr. &mdash;&mdash; testified,</td>
      <td valign="top"><tt>As the witness Mr. ---- testified,</tt></td>
      <td>Raya</td>

    </tr>
    <tr>
      <td valign="top">As the witness Mr. S&mdash;&mdash; testified,</td>
      <td valign="top"><tt>As the witness Mr. S---- testified,</tt></td>
      <td>Raya</td>
    </tr>

    <tr>
      <td valign="top">the famous detective of &mdash;&mdash;B Baker St.</td>
      <td valign="top"><tt>the famous detective of ----B Baker St.</tt></td>
      <td>Raya</td>
    </tr>
    <tr>
      <td valign="top">&ldquo;You &mdash;&mdash; Yankee&rdquo;, she yelled.</td>

      <td valign="top"><tt>"You ---- Yankee", she yelled.</tt></td>
      <td>Raya</td>
    </tr>
    <tr>
      <td valign="top">&ldquo;I am not a d&mdash;d Yankee&rdquo;, he replied.</td>
      <td valign="top"><tt>"I am not a d--d Yankee", he replied.</tt></td>

      <td>Gui&oacute;n largo</td></tr>
  </tbody>
</table>

<h3><a name="eol_hyphen">Guión al final de la línea</a></h3>
<p>
Cuando hay un guión que corta la palabra al final de la línea, junte las
dos partes de la palabra. Si la palabra normalmente lleva guión (como
pos-proceso o well-meaning), junte sus dos partes dejando el guión donde
está. Si el guión se puso solamente para partir una palabra que no cabe
entera en la línea (y no se trata de una palabra que normalmente lleva
guión), junte las dos partes de la palabra y quite el guión. Coloque la
palabra rejuntada al final de la línea superior, y corte la línea
después de ella para mantener el formato de línea. Esto facilitará el
trabajo de voluntarios que sigan la revisión en siguientes rondas. Vea
<a href="#em_dashes">Guiones, el signo menos "-" y rayas</a> para ver los ejemplos en cada caso (nar-row
se convierte en narrow, pero low-lying no pierde su guión). Si a la
palabra le sigue la puntuación, suba esta también al final de la línea
superior, junto a la palabra rejuntada.
</p><p>Palabras como «to-day" y
"to-morrow" que hoy día no llevan guión aparecen con él en muchos libros
antiquos que estamos revisando. Deje estas palabras con guión como el
autor las escribió. Si no está seguro si el autor escribió la palabra
con guión o sin él, dejelo como está, ponga un asterisco * a
continuacíón, y rejunte la palabra. Así: to-*day. El asterisco llamará
atención del pos-procesador, el que tiene acceso a todas las páginas, y
puede decidir sobre la pauta que siguió el autor en la obra.
</p>

<h3><a name="eop_hyphen">Guión al final de la página</a></h3>
<p>
Cuando hay un guión que corta la palabra al final de la página, deje el
guión y ponga un asterisco <tt>*</tt> a continuación.<br>
Por ejemplo:<br>
   &nbsp;<br>
   &nbsp; &nbsp; &nbsp; &nbsp;something Pat had already become accus-<br>
   se convierte en:<br>
   &nbsp; &nbsp; &nbsp; &nbsp;<tt>something Pat had already become accus-*</tt>
</p>
<p>
En la página que empieza con la segunda parte de la palabra de la página
anterior, o con un guión, ponga un asterisco <tt>*</tt> al principio de todo.

La continuación del ejemplo de arriba:<br>

   &nbsp;<br>
   &nbsp; &nbsp; &nbsp; &nbsp;tomed to from having to do his own family<br>
   se convierte en:<br>
   &nbsp; &nbsp; &nbsp; &nbsp;<tt>*tomed to from having to do his own family</tt>

</p>
<p>Estos indiquen al pos-procesor que las dos partes deben juntarse.</p>


<h3><a name="para_space">Párrafos</a></h3>
<p>
Ponga una línea en blanco entre los párrafos. No sangre los párrafos; si el párrafo está ya sangrado no pierda tiempo en quitar los espacios&mdash;eso se hace automáticamente en el pos-proceso.
</p>
<p>Vea <a href="#para_side">Notas al margen</a> para un ejemplo.
</p>

<h3><a name="mult_col">Múltiples Columnas</a></h3>
<p>Revise el texto que está editado en dos columnas como una sola.
</p>
<p>
El texto de múltiples columnas también se revisa como si fuera de una
sola columna. Empiece con la primera de la izquierda, siga con la que
está a su derecha, y así hasta la última columna de la derecha. No es
necesario indicar dónde acaba una y empieza la siguiente columna.
Simplemente júntelo todo en una sola columna.
</p>
<p>Vea también <a href="#bk_index">Índices</a> y <a href="#tables">Tablas</a> secciones de las reglas de
revisión.
</p>


<h3><a name="blank_pg">Página en blanco</a></h3>

<p>
La mayoría de las páginas en blanco, o páginas con una ilustración y sin
texto, ya están marcadas con <tt>[Blank Page]</tt>. Déjelas como están. Si la
página está en blanco y no está marcada con [Blank Page] no es necesario
añadir esta marca.
</p>
<p>
Si el texto está donde normalmente tiene que estar pero la imagen de la
página del libro original está en blanco, o; si la imagen está pero no
aparece el texto, siga las instrucciones para <a href="#bad_image">Imágenes de baja
calidad</a> o <a href="#bad_text">Imagen
que no corresponde al texto</a>, correlativamente.
</p>

<h3><a name="page_hf">Encabezado y pie de página</a></h3>
<p>
Elimine del texto todos los encabezados y pies de página, pero <em>no</em>
elimine las <a href="#footnotes">Notas al pie de página</a>.
</p>
<p>
Los encabezados normalmente aparecen al principio de la página, con el
número de la página al lado. Pueden ser iguales para todas las páginas
del libro, (título del libro y el nombre del autor); pueden ser iguales
para todas las páginas de un capítulo (normalmente el título o número
del capítulo): o pueden ser diferentes en cada página (una descripción
de lo que trata el capítulo). Elimínelos a todos, sin importar la clase
que sean, incluyendo el número de página.
</p>
<!-- END RR -->

<p>
Los <a href="#chap_head">Títulos de capítulo</a> empiezan un poco más
abajo en la página y el número de la página no está en la misma línea.
Vea la sección siguiente para ejemplos.
</p>
<br>
<table width="100%" align="center" border="1" cellpadding="4"
 cellspacing="0" summary="Page Headers and Footers">
  <tbody>
    <tr><th align="left" bgcolor="cornsilk">Imagen del Ejemplo:</th></tr>

    <tr align="left">
      <td width="100%" valign="top">
      <img src="foot.png" alt="" width="500" height="850"><br>
      </td>
    </tr>
    <tr><th align="left" bgcolor="cornsilk">Texto Revisado Correctamente:</th></tr>
    <tr>
      <td width="100%" valign="top">

<table summary="" border="0" align="left"><tr><td>
    <tt>In the United States?[*] In a railroad? In a mining company?<br>
    In a bank? In a church? In a college?<br>
    <br>
    Write a list of all the corporations that you know or have<br>
    ever heard of, grouping them under the heads public and private.<br>
    <br>

    How could a pastor collect his salary if the church should<br>
    refuse to pay it?<br>
    <br>
    Could a bank buy a piece of ground «on speculation?" To<br>
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
</td></tr></table>
      </td>
    </tr>
  </tbody>
</table>

<h3><a name="chap_head">Títulos de capítulo</a></h3>

<p>Revise los títulos de capítulo como aparecen en el texto.
</p>
<p>
Un título de capítulo puede empezar un poco más abajo que el <a href="#page_hf">encabezado
de la página</a>, y el número de página no está en la misma línea. Títulos
de capítulo a veces se imprimen en mayúsculas. Si es así, deje todas las
mayúsculas tal y como están.
</p>
<p>
Preste atención al primer párrafo del capítulo. Puede que el impresor no
haya incluido (o el programa de OCR no haya reconocido) las comillas con
las que empieza el primer párrafo. Si el autor empieza este párrafo con
diálogo, introduzca las comillas omitidas.
</p>
<!-- END RR -->



<h3><a name="illust">Ilustraciones - Imágenes</a></h3>
<p>
Revise cualquier texto que acompañe la ilustración, preservando los
quiebros de línea. Si la nota que acompaña la ilustración o imagen se
encuentra en medio de un párrafo, sepárela del resto del texto del
párrafo con una línea en blanco antes, y otra después de esta nota. Si
<b>no existe</b> tal nota, el formato de la ilustración se deja para los
formateadores.
</p>
<p>
La mayoría de las páginas que contienen solamente la ilustración ya
están marcadas con <tt>[Blank Page]</tt>. Deje esta marca tal y como está.
</p>

<!-- END RR -->

<table width="100%" align="center" border="1" cellpadding="4"
 cellspacing="0" summary="Illustration">
  <tbody>
    <tr>
      <th align="left" bgcolor="cornsilk">
      Una ilustración / imagen de ejemplo:
      </th>

    </tr>
    <tr align="left">
      <td width="100%" valign="top"> <img src="illust.png" alt=""
          width="500" height="525"> <br>
      </td>
    </tr>
    <tr>
      <th align="left" bgcolor="cornsilk">Texto revisado correctamente:</th>

   </tr>
    <tr>
    <td width="100%" valign="top">
      <table summary="" border="0" align="left"><tr>
      <td>
      <p><tt>Martha told him that he had always been her ideal and<br>
      that she worshipped him.<br>
      <br>

      Frontispiece<br>
      Her Weight in Gold
      </tt></p>
      </td></tr></table>
    </td>
    </tr>
  </tbody>
</table>

<br>

<table width="100%" align="center" border="1"  cellpadding="4"
 cellspacing="0" summary="Illustration in Middle of Paragraph">
  <tbody>
   <tr>
     <th align="left" bgcolor="cornsilk">Una ilustración / imagen de ejemplo: (Una ilustración en el medio del párrafo)</th>
   </tr>
   <tr align="left">
     <td width="100%" valign="top"> <img src="illust2.png" alt=""
         width="500" height="514"> <br>
     </td>

   </tr>
   <tr>
     <th align="left" bgcolor="cornsilk">Texto revisado correctamente:</th>
   </tr>
    <tr valign="top">
     <td>
<table summary="" border="0" align="left"><tr><td>
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
</td></tr></table>
    </td>
    </tr>

  </tbody>
</table>

<h3><a name="footnotes">Notas al pie de página</a></h3>
<p><b>Las notas al pie se ubican fuera de la línea de texto</b>;
Es decir, la nota se deja al fondo de la página, y en el texto se coloca
un marcador al lado de la palabra a la que se refiere la nota al pie de
página.
</p>
<p>
La letra, el número o cualquier otro carácter que indica una nota al
pie, debe ponerse entre paréntesis cuadrados ([ y ]) justo al lado de la
palabra<tt>[1]</tt> a la que se refiere la nota o, al lado de su puntuación,<tt>[2]</tt>
como se ve en esta frase.
</p>
<p>
Cuando las notas están indicadas con caracteres especiales (*,
&dagger;, &Dagger;, &sect;, etc.), reemplácelos todos con [*] en el texto, y
ponga un asterisco * al
principio de la nota al pie.
</p>
<p>
Revise el texto de la nota al pie comparándolo con el texto original
preservando los cambios / quiebros de línea. Deje el texto de la nota al
fondo de la página. Esté seguro de utilizar la misma marca para la nota
al pie, que la que utiliza junto a la palabra a la que esta se refiere
(sea *, 1, 2 etc.).
</p>
<p>
Ponga cada nota al pie en una nueva línea. Ponga una línea en blanco al
final de cada nota al pie de página (si hay más que una).
</p>

<!-- END RR -->

<p>Vea un ejemplo aquí: <a href="#page_hf">Encabezado y pie de página</a>
para un ejemplo.

</p>
<p>
Si hay una referencia a una nota al pie en el texto pero ninguna nota
aparece al fondo de la página, deje la referencia donde está y no se
preocupe: es una situación común en los libros técnicos/científicos
donde se juntan todas las notas al final del capítulo. Vea «Notas al
final» abajo.
</p>

<table width="100%" border="1"  cellpadding="4" cellspacing="0" align="center" summary="Footnote Examples">
  <tbody>
    <tr>
      <th valign="top" align="left" bgcolor="cornsilk">Texto original:</th>
    </tr>
    <tr>
      <td valign="top">

<table summary="" border="0" align="left"><tr><td>
    The principal persons involved in this argument were Caesar<sup>1</sup>, former military<br>
    leader and Imperator, and the orator Cicero<sup>2</sup>. Both were of the aristocratic<br>
    (Patrician) class, and were quite wealthy.<br>
    <hr align="left" width="50%" noshade size="2">
    <font size=-1><sup>1</sup> Gaius Julius Caesar.</font><br>

    <font size=-1><sup>2</sup> Marcus Tullius Cicero.</font>
</td></tr></table>
      </td>
    </tr>
    <tr>
      <th valign="top" align="left" bgcolor="cornsilk">Texto revisado correctamente:</th>
    </tr>

      <tr valign="top">
      <td>
<table summary="" border="0" align="left"><tr><td>
    <tt>The principal persons involved in this argument were Caesar[1], former military</tt><br>
    <tt>leader and Imperator, and the orator Cicero[2]. Both were of the aristocratic</tt><br>
    <tt>(Patrician) class, and were quite wealthy.</tt><br>
    <br>
    <tt>1 Gaius Julius Caesar.</tt><br>

    <br>
    <tt>2 Marcus Tullius Cicero.</tt>
</td></tr></table>
      </td>
    </tr>
  </tbody>
</table>

<p>
En algunos libros las notas al pie están separadas del texto principal
por una línea horizontal. Omita esa línea, y deje tan solo una línea en
blanco entre el texto principal y las notas al pie. (Vea ejemplo de
arriba)
</p>
<p><b>Notas al final</b>
son notas al pie que de vez en cada página se colocan todas juntas al
final del capítulo, o al final del libro. Se revisan de la misma manera
que las notas al pie. Si encuentra una referencia a alguna nota en el
texto, retenga el número o la letra. Si revisa las páginas finales del
libro donde se encuentran todas las notas, ponga una línea en blanco al
final de cada una de ellas, para que esté claro donde empieza y termina
cada nota.
</p>
<!-- Need an example of Endnotes, maybe? Good idea!-->

<p><b>Notas al pie en <a href="#poetry">Poesía</a></b>
se tratan de la misma manera que otras notas al pie.<br> <br>

<b>Notas al pie en <a href="#tables">Tablas</a></b>
se deben quedar en el lugar en el que están en el texto original.
</p>

<table width="100%" align="center" border="1" cellpadding="4" cellspacing="0" summary="Footnotes">
  <tbody>
    <tr><th align="left" bgcolor="cornsilk">Poesía original con una nota al pie:</th></tr>
    <tr>
      <td valign="top">
<table summary="" border="0" align="left"><tr><td>
    Mary had a little lamb<sup>1</sup><br>
    &nbsp;&nbsp;&nbsp;Whose fleece was white as snow<br>

    And everywhere that Mary went<br>
    &nbsp;&nbsp;&nbsp;The lamb was sure to go!<br>
    <hr align="left" width="50%" noshade size=2>
    <font size=-2><sup>1</sup> This lamb was obviously of the Hampshire breed,<br>
    well known for the pure whiteness of their wool.</font>
</td></tr></table>

      </td>
    </tr>
    <tr><th align="left" bgcolor="cornsilk">Poesía revisada correctamente:</th></tr>
    <tr>
      <td valign="top">
<table summary="" border="0" align="left"><tr><td>
    <tt>
    Mary had a little lamb[1]<br>

    Whose fleece was white as snow<br>
    And everywhere that Mary went<br>
    The lamb was sure to go!<br>
    <br>
    1 This lamb was obviously of the Hampshire breed,<br>
    well known for the pure whiteness of their wool.<br>

    </tt>
</td></tr></table>
      </td>
    </tr>
  </tbody>
</table>

<h3><a name="poetry">Poesía</a></h3>
<p>
Introduzca una línea en blanco al comienzo de la poesía o epigrama y
otra al final, para que los formateadores puedan ver claramente donde
empieza y donde se acaba el formato especial.
</p>
<p>
Justifique todas las líneas a la izquierda y mantenga los cambios /
quiebras de línea. No intente sangrar la poesía. Los formateadores lo
harán. Introduzca una línea en blanco entre las estrofas.
</p>

<p><b>Notas al pie</b>
en poesía deben tratarse de la misma manera que cualquier nota al pie en
el texto común. Vea <a href="#footnotes">Notas al pie de página</a>
para más detalles.
</p>
<p><b>La numeración de las líneas</b>
en poesía debe conservarse. Separe los números del texto con algunos
espacios. Vea las instrucciones para los <a href="#line_no">Números de línea</a>.
</p>
<p>
Compruebe en los <a href="#comments">Comentarios del proyecto</a> que está revisando, si existen indicaciones especiales.
</p>
<!-- END RR -->

<br>
<!-- Need an example that shows overly long lines of poetry, rather than relative indentation -->

<table width="100%" align="center" border="1"  cellpadding="4"
      cellspacing="0" summary="Poetry Example">
 <tbody>
   <tr><th align="left" bgcolor="cornsilk">Imagen del ejemplo:</th></tr>
   <tr align="left">
     <th width="100%" valign="top"> <img src="poetry.png" alt=""
         width="500" height="508"> <br>

     </th>
   </tr>
   <tr><th align="left" bgcolor="cornsilk">Texto revisado correctamente:</th></tr>
   <tr>
     <td width="100%" valign="top">


<table summary="" border="0" align="left"><tr><td>
<tt>
to the scenery of his own country:<br></tt>

<p><tt>
Oh, to be in England<br>
Now that April's there,<br>
And whoever wakes in England<br>
Sees, some morning, unaware,<br>
That the lowest boughs and the brushwood sheaf<br>
Round the elm-tree hole are in tiny leaf,<br>
While the chaffinch sings on the orchard bough<br>
In England--now!</tt>

</p><p><tt>
And after April, when May follows,<br>
And the whitethroat builds, and all the swallows!<br>
Hark! where my blossomed pear-tree in the hedge<br>
Leans to the field and scatters on the clover<br>
Blossoms and dewdrops--at the bent spray's edge--<br>
That's the wise thrush; he sings each song twice over,<br>
Lest you should think he never could recapture<br>
The first fine careless rapture!<br>

And though the fields look rough with hoary dew,<br>
All will be gay, when noontide wakes anew<br>
The buttercups, the little children's dower;<br>
--Far brighter than this gaudy melon-flower!<br>
</tt>
</p><p><tt>
So it runs; but it is only a momentary memory;<br>
and he knew, when he had done it, and to his</tt>
</p>
</td></tr></table>

     </td>
   </tr>
 </tbody>
</table>

<h3><a name="para_side">Notas al margen</a></h3>
<p>
En algunos libros existen breves descripciones del párrafo al margen del
texto. Revise el texto de la «nota al margen» comparándolo con el texto
original, preservando los cambios de línea. Deje una línea en blanco
antes y después de la «nota al margen» para que esta se pueda distinguir
del resto del texto. Es posible que el programa de OCR haya colocado el
texto de la nota en cualquier parte de la página, hasta entremezclado
con el resto del texto de la página. Separe el texto de la nota del
resto del texto, colocándolo todo junto. No se preocupe de lugar en la
página conde coloca el texto de la nota. Los formateadores lo pondrán en
el lugar apropiado.</p>

<!-- END RR -->

  <table width="100%" align="center" border="1" cellpadding="4"
       cellspacing="0" summary="Sidenotes"> <col width="128*">

  <tbody>
    <tr valign="top">
      <th align="left" bgcolor="cornsilk">Imagen del ejemplo:</th>
    </tr>
    <tr valign="top">
      <td width="100%" align="left"><img src="side.png" alt=""
          width="550" height="800"><br>
      </td>
    </tr>

    <tr valign="top">
      <th align="left" bgcolor="cornsilk">Texto revisado correctamente:</th>
    </tr>
    <tr valign="top">
      <td width="100%">
<table summary="" border="0" align="left"><tr><td>
    <p><tt>
    Burning<br>

    discs<br>
    thrown into<br>
    the air.<br>
    <br>
    that such as looked at the fire holding a bit of larkspur<br>
    before their face would be troubled by no malady of the<br>

    eyes throughout the year.[1] Further, it was customary at<br>
    Würzburg, in the sixteenth century, for the bishop's followers<br>
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
    (J. W. Wolf, Beiträge zur deutschen<br>
    Mythologie, i. p. 217, § 185).<br>

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
    4 A. Birlinger, Volksthümliches aus<br>
    Schwaben (Freiburg im Breisgau, 1861-1862),<br>
    ii. pp. 96 sqq., § 128, pp. 103<br>

    sq., § 129; id., Aus Schwaben (Wiesbaden,<br>
    1874), ii. 116-120; E. Meier,<br>
    Deutsche Sagen, Sitten und Gebräuche<br>
    aus Schwaben (Stuttgart, 1852), pp.<br>
    423 sqq.; W. Mannhardt, Der Baumkultus,<br>
    p. 510.<br>

    </tt></p>
</td></tr></table>
      </td>
    </tr>
  </tbody>
</table>

<h3><a name="tables">Tablas</a></h3>
<p>La tarea del revisor es estar seguro que toda la información en la tabla
esté correctamente revisada. Los detalles de formato de tablas serán
tratados más adelante. Deje suficiente espacio entre los datos de
diversas columnas para que esté claro donde empieza y donde acaba cada
dato. Retenga los cambios / quiebros de línea.
</p>

<p><b>Notas al pie</b>
en las tablas deben quedarse donde están en el original. Vea <a href="#footnotes">Notas al
pie de página</a> para más detalles.
</p>
<!-- END RR -->
<table width="100%" align="center" border="1" cellpadding="4"
 cellspacing="0" summary="Table Example 1">
  <tbody>
    <tr><th align="left" bgcolor="cornsilk">Imagen del ejemplo:</th></tr>
    <tr align="left">
      <td width="100%" valign="top"> <img src="table1.png" alt="" width="500" height="142"><br>

      </td>
    </tr>
    <tr><th align="left" bgcolor="cornsilk">Texto revisado correctamente:</th></tr>
    <tr>
      <td width="100%" valign="top">
<table summary="" border="0" align="left"><tr><td>
<pre>
Deg. C.  Millimeters of Mercury. Gasolene.
Pure Benzene.

-10&deg;  13.4  43.5
 0&deg;  26.6  81.0
+10&deg;  46.6  132.0
20&deg;  76.3  203.0
40&deg;  182.0  301.8

</pre>
</td></tr></table>
      </td>
    </tr>
  </tbody>
</table>
<br>
<table width="100%" align="center" border="1" cellpadding="4"
 cellspacing="0" summary="Table Example 2">
  <tbody>
    <tr><th align="left" bgcolor="cornsilk">Imagen de ejemplo:</th></tr>
    <tr align="left">

      <td width="100%" valign="top"> <img src="table2.png" alt="" width="500" height="304"><br>
      </td>
    </tr>
    <tr><th align="left" bgcolor="cornsilk">Texto revisado correctamente:</th></tr>
    <tr>
      <td width="100%" valign="top">
<table summary="" border="0" align="left"><tr><td>
<pre>
TABLE II.

Flat strips compared   Copper   Copper
with round wire 30 cm.  Iron. Parallel wires 30 cm. in  Iron.
in length.             length.

Wire 1 mm. diameter   20  100  Wire 1 mm. diameter   20 100

        STRIPS.      SINGLE WIRE.
0.25 mm. thick, 2 mm.
  wide  15  35  0.25 mm. diameter   16   48
Same, 5 mm. wide       13  20  Two similar wires    12  30
 "   10  "    "   11   15  Four    "    "     9   18
 "   20  "    "    10  14  Eight  "    "   8   10
 "   40  "    "    9   13  Sixteen "    "     7    6
Same strip rolled up in  Same 16 wires bound
  the form of a wire  17   15    close together   18    12

</pre>
</td></tr></table>
      </td>
    </tr>
  </tbody>
</table>


<h3><a name="title_pg">Portada&mdash;Contraportada</a></h3>
<p>
Revise todo el texto como se ve en la imagen de la página, sean las
letras mayúsculas o minúsculas, etc., los años de la publicación, o
derechos del autor.
</p>
<p>
En los libros antiguos frecuentemente la primera letra es grande y
ornamentada&mdash;revísela como una letra normal.
</p>
<!-- END RR -->

<table width="100%" align="center" border="1" cellpadding="4"
 cellspacing="0" summary="Title Page Example">
  <tbody>
    <tr>
      <th align="left" bgcolor="cornsilk">
      Imagen del ejemplo:
      </th>
    </tr>
    <tr align="left">

      <td width="100%" valign="top"><img src="title.png" width="500"
          height="520" alt="title page image"><br>
      </td>
    </tr>
    <tr>
      <th align="left" bgcolor="cornsilk">Texto revisado correctamente:</th>
    </tr>
    <tr>
      <td width="100%" valign="top">

<table summary="" border="0" align="left"><tr><td>
      <p><tt>GREEN FANCY</tt>
      </p>
      <p><tt>BY</tt></p>
      <p><tt>GEORGE BARR McCUTCHEON</tt></p>
      <p><tt>AUTHOR OF «GRAUSTARK," «THE HOLLOW OF HER HAND,"<br>
         «THE PRINCE OF GRAUSTARK," ETC.</tt></p>

      <p><tt>WITH FRONTISPIECE BY<br>
         C. ALLAN GILBERT</tt></p>
      <p><tt>NEW YORK<br>
         DODD, MEAD AND COMPANY.</tt></p>
      <p><tt>1917</tt></p>
</td></tr></table>
      </td>

    </tr>
  </tbody>
</table>

<h3><a name="toc">La tabla de contenidos</a></h3>
<p>
Revise la tabla de contenidos exactamente como está en el libro, ya sean
las letras mayúsculas o minúsculas, etc. Conserve los números de las
páginas.
</p>
<p>
Haga caso omiso de los puntos o asteriscos utilizados para alinear los
números de las páginas. Estos serán eliminados más adelante en el
proceso.
</p>
<!-- END RR -->

<table width="100%" align="center" border="1" cellpadding="4"
 cellspacing="0" summary="TOC">
  <tbody>

    <tr>
      <th align="left" bgcolor="cornsilk">
      Imagen del ejemplo:
      </th>
    </tr>
    <tr align="left">
      <td width="100%" valign="top">
      <p><img src="tablec.png" alt="" width="500" height="650"></p>
      </td>

    </tr>
    <tr>
      <th align="left" bgcolor="cornsilk">Texto revisado correctamente:</th>
    </tr>
    <tr>
      <td width="100%" valign="top">
<table summary="" border="0" align="left"><tr><td>
      <p><tt>CONTENTS</tt></p>

      <p><tt><br>
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
          AND TWO MEN RIDE AWAY&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;35<br>
          <br>
          IV. AN EXTRAORDINARY CHAMBERMAID, A MIDNIGHT<br>
          TRAGEDY, AND A MAN WHO SAID «THANK YOU"&nbsp;&nbsp;&nbsp;50<br>

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
          OUTSIDE&nbsp;&nbsp;&nbsp;,,,..&nbsp;&nbsp;....,&nbsp;221<br>
      </tt>
</td></tr></table>
      </td>
    </tr>
  </tbody>

</table>



<h3><a name="bk_index">Índices</a></h3>
<p>
Retenga los números de las páginas en las páginas del índice. No es
necesario alinear los números como están en la imagen. Sólo esté seguro
que los números y la puntuación sean correctos.</p>
<p>Al índice se le dará formato más adelante en el proceso. La tarea del revisor está en
asegurarse que todo el texto y todos los números sean correctos.
</p>
<!-- END RR -->


<h3><a name="play_n">Obras de teatro&mdash;Nombres de actores&mdash;Directrices escénicas</a></h3>

<p>Para todas las obras de teatro:</p>
<ul compact>
 <li>Trate cada cambio de personaje como un párrafo nuevo, con una línea en blanco entre los diálogos.
<li>
Revise las directrices escénicas como están en el texto.<br>
Si se encuentran en una línea nueva, póngalas en una línea nueva; si están al final de un diálogo, déjelas así.
<br>A menudo las directrices escénicas comienzan con un paréntesis que no se cierra. Déjelo así; no cierre el paréntesis.</li>
 <li>
A veces, especialmente si la obra de teatro tiene un metro fijo, una
palabra puede estar cortada debido al tamaño de la página y la segunda
parte de la palabra está justo encima o debajo del principio de la
misma después de un paréntesis: (. Por favor, una la palabra y colóquela en la línea que le
corresponde.
<br>
   Vea el <a href="#play4">ejemplo abajo</a>.</li>
</ul>
<p>
Por favor, lea los <a href="#comments">Comentarios del proyecto</a>
para ver si el gerente del proyecto ha dado instrucciones
especificas al proyecto.
</p>
<!-- END RR -->

<table width="100%" align="center" border="1" cellpadding="4"
 cellspacing="0" summary="Play Example 1">
  <tbody>
    <tr>

      <th align="left" bgcolor="cornsilk">Imagen del ejemplo:</th>
    </tr>
    <tr align="left">
      <td width="100%" valign="top"><img src="play1.png" width="500"
          height="430" alt="title page image"><br>
      </td>
    </tr>
    <tr>
      <th align="left" bgcolor="cornsilk">Texto revisado correctamente:</th>

    </tr>
    <tr>
      <td width="100%" valign="top">
<table summary="" border="0" align="left"><tr><td>
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
Lap. But why a Peel-crow here?
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
</td></tr></table>
      </td>
    </tr>

  </tbody>
</table>
<br>
<a name="play4"><!-- Example --></a>
<table width="100%" align="center" border="1" cellpadding="4"
 cellspacing="0" summary="Play Example 4">
  <tbody>
    <tr>
      <th align="left" bgcolor="cornsilk">Imagen del ejemplo:</th>
    </tr>
    <tr align="left">

      <td width="100%" valign="top"><img src="play4.png" width="502"
          height="98" alt="Plays image"><br>
      </td>
    </tr>
    <tr>
      <th align="left" bgcolor="cornsilk">Texto revisado correctamente:</th>
    </tr>
    <tr>
      <td width="100%" valign="top">

<table summary="" border="0" align="left"><tr><td>
<p><tt>
Am. Sure you are fasting;<br>
Or not slept well to night; some dream (Ismena?)<br>
<br>
Ism. My dreams are like my thoughts, honest and innocent,<br>
Yours are unhappy; who are these that coast us?<br>
You told me the walk was private.<br>
</tt></p>
</td></tr></table>
      </td>

    </tr>
  </tbody>
</table>


<h3><a name="anything">Cualquier otra cosa que necesite tratamiento especial o no sepa como tratar</a></h3>
<p>
Si encuentra, mientras está revisando, algo que no está explicado en
esta guía de revisión o cree que necesite un tratamiento especial o no
está seguro cómo tratarlo, entre al <a href="#comments">Foro/Discusión sobre este
proyecto</a>=Project Discussion, (un enlace en los
<a href="#comments">Comentarios del proyecto</a>=Project
Comments), y mande un mensaje, anotando el número de la página en la cual
está (nº de png). Ponga también una nota en el texto que está revisando,
explicando el problema. Su nota avisará al próximo revisor, al
formateador y el pos-procesor cual es la duda o el problema.
</p>
<p>

Empiece su nota con un paréntesis cuadrado y dos asteriscos
<tt>[**</tt> así, y
termínela con otro paréntesis cuadrado <tt>]</tt>. Esto la separará claramente del
resto del texto del autor y llamará la atención del pos-procesor para
tratar esta parte del texto de la manera apropiada. Si el revisor
anterior ha dejado una nota, Ud. puede añadir otra diciendo que está de
acuerdo o desacuerdo. Aunque sepa la respuesta, de ninguna manera debe
eliminar ningún comentario dejado por otros revisores. Si ha encontrado
un enlace o una fuente que clarifica el problema, por favor cítelo para
que el pos-procesor pueda referirse a ello.
</p>
<p>
Si está revisando en las rondas más avanzadas, y encuentra una nota de
un revisor anterior y sabe la respuesta a su pregunta, por favor tómese
un momento para enviarle un mensaje privado haciendo clic en su nombre
en la interfaz, explicándole cómo tratar la situación en el futuro. Por
favor, como ya está advertido, no elimine su nota.
</p>

<h3><a name="prev_notes">Notas y comentarios de revisores anteriores</a></h3>
<p>
Es <b>imprescindible</b> que cualquier nota o comentario dejado por un revisor
anterior se deje tal i como está. Puede añadir un acuerdo o desacuerdo a
la nota existente. Pero aun que sepa la respuesta, de ninguna manera
elimine la nota o comentario. Si ha encontrado una fuente que clarifica
el problema, por favor, cítelo para que el pos-procesor pueda
consultarlo.
</p>
<p>
Si está dando formato al texto en una ronda avanzada y encuentra una
nota de un voluntario de una ronda anterior y sabe la respuesta, por
favor, tómese un momento para enviarle un mensaje privado explicando
cómo tratar la situación en el futuro. Por favor, como ya está dicho, no
elimine la nota original.
</p>
<!-- END RR -->

<table width="100%" border="0" cellspacing="0" summary="Common Problems">
  <tbody>

    <tr>
      <td bgcolor="silver">&nbsp;</td>
    </tr>
  </tbody>
</table>

<h2>Problemas comunes</h2>

<h3><a name="OCR_1lI">Problemas de OCR: 1-l-I</a></h3>
<p>
Frecuentemente el OCR no puede distinguir entre el número 1 (uno), la
letra minúscula l (ele) y la letra mayúscula I (i). Es especialmente el
caso con los libros antiguos cuyas páginas están en malas condiciones.
</p>

<p>
Esté atento a estos errores. Lea el contexto de la frase para averiguar
cual es la letra apropiada. ¡Tenga cuidado!, porque su mente corregirá
automáticamente estos errores mientras lee.
</p>
<p>Encontrar estos errores es más fácil si utiliza una fuente
mono-espaciada como por ejemplo DPCustomMono o Courier que se puede
descargar haciendo clic en este
enlace: <a href="font_sample.php">DPCustomMono</a> o Courier.
</p>

<h3><a name="OCR_0O">Problemas de OCR: 0-O</a></h3>
<p>
Frecuentemente el OCR no puede distinguir entre el número 0 (cero) y la
letra mayúscula O (o). Es especialmente el caso con los libros antiguos
cuyas páginas están en malas condiciones.
</p>
<p>
Esté atento a estos errores. Lea el contexto de la frase para averiguar
cual es la letra apropiada. ¡Tenga cuidado!, porque su mente corregirá
automáticamente estos errores mientras lee.
</p>
<p>
Encontrar estos errores es más fácil si utiliza una fuente
mono-espaciada como por ejemplo DPCustomMono o Courier que se puede
descargar haciendo clic en este
enlace: <a href="font_sample.php">DPCustomMono</a> o Courier.
</p>

<h3><a name="OCR_scanno">Problemas de OCR: Scannos</a></h3>
<p>
Otro problema del OCR es el reconocimiento equivocado de los caracteres.
Llamamos a estos errores «scannos» (como «typos» en inglés-errata /
errores tipográficos). Este reconocimiento equivocado puede crear
errores en el texto:</p>
<ul compact>
   <li>
Una palabra que parece correcta a la primera vista pero en verdad está
mal deletreada, se puede detectar utilizando el corrector automático de
ortografía.
   <li>
Una palabra cambiada por otra no menos valida pero no igual a la palabra
del texto original.
</ul>
<p>
Estos errores son sutiles porque no se pueden detectar de otra manera a
no ser leyendo el texto. Posiblemente el ejemplo más común del segundo
tipo es la palabra «and» reconocida por el OCR como «arid». Otros
ejemplos: «eve» por «eye», «Torn» por «Tom», «train» por «tram», «había»
por «habla», «cuidad» por «ciudad». Es más difícil encontrar este tipo y
le damos un nombre especial: «Stealth Scannos». Coleccionamos ejemplos
de «Stealth Scannos» en <a href="http://www.pgdp.net/phpBB2/viewtopic.php?t=1563">
este foro.</a> Los ejemplos de los scannos en español <a href="http://www.pgdp.net/wiki/Scannos_en_espa%C3%B1ol">se encuentran aquí</a>.
</p>
<p>Encontrar estos errores es más fácil si utiliza una fuente
mono-espaciada como por ejemplo DPCustomMono o Courier que se puede
descargar haciendo clic en este
enlace: <a href="font_sample.php">DPCustomMono</a> o Courier.
</p>
<!-- END RR -->
<!-- More to be added.... -->

<h3><a name="hand_notes">Notas escritas a mano</a></h3>
<p>
No incluya notas escritas a mano que encuentre en el libro (a no ser que
se trate de texto original que alguien haya reforzado a mano para que se
vea mejor). No incluya notas escritas al margen por lectores del libro,
etc.
</p>


<h3><a name="bad_image">Imágenes de baja calidad</a></h3>
<p>
Si una imagen es de baja calidad (no se descarga, está cortada,
ilegible), por favor envíe un mensaje al foro del proyecto. No haga clic
en «Return Page to Round» (devolver la página a la ronda). Si lo hace la
página se enviará al próximo revisor. En vez de eso, haga clic en
«Report Bad Page» (informar sobre la baja calidad de la imagen). La
página se envía a la cuarentena.
</p>
<p>
Observe que los archivos de algunas imágenes de las paginas son bastante
grandes, y puede ser normal que su navegador tenga problemas para
descargarlas, especialmente si tiene varios programas o ventanas
abiertos, o utiliza un ordenador viejo. Antes de reportar imagen de baja
calidad, intente hacer clic en «Image» (al lado de «View: Project
Comments» al fondo de la interfaz) y la página se abrirá en una ventana
nueva. Si la imagen es de buena calidad, el problema será de su
ordenador o navegador y no de la imagen.
</p>
<p>
Es común que la imagen sea de buena calidad pero el OCR no ha leído la
primera o primeras líneas del texto. Por favor, teclee el texto que
falta. Si faltan casi todas las líneas, puede teclearlas todas (si está
de acuerdo con eso) o, hacer clic en «Return Page to Round», y la página
será devuelta a la misma ronda para que lo haga otra persona. Si
encuentra varias páginas así, envíe un mensaje al foro (<a href="#forums">foros</a>) del proyecto y al
gerente del proyecto.
</p>

<h3><a name="bad_text">Imagen que no corresponde al texto</a></h3>

<p>Si la imagen no corresponde en absoluto al texto, mande un mensaje al
foro del proyecto (<a href="#forums">foros</a>). No haga clic en «Return Page to Round» (devuelve la
página a la ronda). Si lo hace la página va al próximo revisor. En vez
de eso, haga clic en «Report Bad Page» (informar sobre la baja calidad
de la página). La página se pone en cuarentena.<hr>
</p>

<h3><a name="round1">Errores de revisores anteriores</a></h3>
<p>
Si un revisor anterior ha hecho, o se le escaparon muchos errores, por
favor tómese un momento para enviarle un mensaje privado haciendo clic
en su nombre en la interfaz de revisión explicando cómo tratar esta
situación en el futuro.
</p>
<p>
Por favor, ¡<em>sea amable</em>! Todo el mundo aquí es voluntario y (se supone)
está intentando hacer lo mejor que puede. El objetivo de su mensaje debe
ser de informarle sobre la manera correcta de revisar y no de
criticarle. Muestre el punto concreto de su trabajo, y señale lo que
hizo y lo que tenía que haber hecho.
</p>
<p>
Si el revisor anterior hizo un trabajo magnífico, también puede enviarle
un mensaje diciéndoselo&mdash;especialmente si se trata de una página
difícil.<hr>
</p>

<h3><a name="p_errors">Errores de impresión / Ortografía</a></h3>
<p>
Corrija todos los errores del OCR (scannos, etc.) pero no corrija lo que
le parecen errores del impresor o de la ortografía (errores que también
se encuentran en la imagen del texto original). Muchos de los textos
antiguos tienen una manera diferente de deletrear y acentuar de la
actual, y nosotros mantenemos este estilo antiguo, incluyendo la
acentuación.
</p>
<p>
Si no está seguro ponga una nota en el texto así:
  txet <tt>[**typo for text?]</tt>
y pregunte en el Foro del proyecto. Si cambia algo, incluya una nota que describa lo que ha cambiado, así:
  <tt>[**Transcriber's Note: typo fixed, changed from "txet" to "text"]</tt>
Incluya los dos asteriscos <tt>**</tt> para llamar atención del pos-procesor.
</p>

<h3><a name="f_errors">Errores de datos o hechos en el texto</a></h3>

<p>
Normalmente no corregimos errores de datos o hechos en los libros.
Muchos de los libros que revisamos contienen datos o declaraciones que
actualmente no son aceptables. Déjelos como los escribió el autor del
libro.
</p>
<p>

Una excepción posible sería en algunos libros científicos o técnicos,
donde una ecuación o formula puede estar escrita incorrectamente,
(especialmente si aparece sin error en otras paginas del libro). Avise
al gerente del proyecto sobre este hecho, enviándole un mensaje privado
o un mensaje al foro del proyecto (<a href="#forums">Forum</a>). También ponga en el texto una nota
tipo: <tt>[**es distinto de la en Pág. 123]</tt>.
</p>

<h3><a name="uncertain">Inseguridades</a></h3>
<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; [...Está por escribir...]
</p>

<H2 align=center>Fin de las reglas</H2>

<TABLE cellSpacing=0 width="100%" border=0>
 <TBODY>
 <TR>
   <TD bgColor=silver><BR></TD></TR></TBODY></TABLE>
Volver <A
href="http://www.pgdp.net/"> al la página principal de Distributed
Proofreaders</A><BR><BR><BR>

<?
theme('','footer');
?>
