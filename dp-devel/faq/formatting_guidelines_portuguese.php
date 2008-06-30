<?

// Translated by user 'rfarinha' at pgdp.net, 12/12/2007

$relPath='../pinc/';
include($relPath.'site_vars.php');
include($relPath.'faq.inc');
include($relPath.'pg.inc');
include($relPath.'connect.inc');
include($relPath.'theme.inc');
new dbConnect();
$no_stats=1;
theme('Regras de Formata&ccedil;&atilde;o','header');

$utf8_site=!strcasecmp($charset,"UTF-8");
?>

<!-- NOTE TO MAINTAINERS AND DEVELOPERS:

     There are now HTML comments interspersed in this document which are/will be
     used by a script which automagically slices out the Random Rule text for the
     database. It does this by copying:
       1) All text from one h_3 to the next h_3
              -OR-
       2) All text from h_3 to the END_RR comment line.

    This allows us to have "extra" information in the Guidelines, but leave it out
    in the Random Rule for purposes of clarity/brevity.

    If you are updating this document, the above should be kept in mind.
-->

<h1 align="center">Regras de Formata&ccedil;&atilde;o</h1>

<h3 align="center">Vers&atilde;o 1.9.e, publicada a 19 de Julho de 2007 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
  <a href="dochist.php"><font size="-1">(Hist&oacute;rico)</font></a></h3>

<h4>Regras de Formata&ccedil;&atilde;o <a href="document.php">em Ingl&ecirc;s</a> /
      Formatting Guidelines <a href="document.php">in English</a><br />
    Regras de Formata&ccedil;&atilde;o <a href="formatting_guidelines_francaises.php">em Franc&ecirc;s</a> /
      Directives de Formatage <a href="formatting_guidelines_francaises.php">en fran&ccedil;ais</a><br />
    Regras de Formata&ccedil;&atilde;o <a href="formatting_guidelines_dutch.php">em Holand&ecirc;s</a> /
      Formatteer-Richtlijnen <a href="formatting_guidelines_dutch.php">in het Nederlands</a><br />
    Regras de Formata&ccedil;&atilde;o <a href="formatting_guidelines_german.php">em Alem&atilde;o</a> /
      Formatierungsrichtlinien <a href="formatting_guidelines_german.php">auf Deutsch</a><br />
</h4>

<h4>Fa&ccedil;a o <a href="../quiz/start.php?show_only=FQ">Teste de Formata&ccedil;&atilde;o</a>!</h4>

<table border="0" cellspacing="0" width="100%" summary="Regras de Formata&ccedil;&atilde;o">
  <tbody>
  <tr>
    <td width="1" bgcolor="silver">&nbsp;</td>
    <td bgcolor="silver" align="center"><font size="+2"><b>&Iacute;ndice</b></font></td>
  </tr>
  <tr>
    <td width="1" bgcolor="silver">&nbsp;</td>
    <td bgcolor="white" align="left">
    <ul>
      <li><a href="#prime">A Regra B&aacute;sica</a></li>
      <li><a href="#summary">Resumo das Regras</a></li>
      <li><a href="#about">Sobre Este Documento</a></li>
      <li><a href="#comments">Coment&aacute;rios do Projecto</a></li>
      <li><a href="#forums">F&oacute;rum/Discuss&atilde;o deste Projecto</a></li>
      <li><a href="#prev_pg">Correc&ccedil;&atilde;o de Erros em P&aacute;ginas Precedentes</a></li>
    </ul>
    </td>
  </tr>
  <tr>
    <td width="1" bgcolor="silver">&nbsp;</td>
    <td bgcolor="silver" align="left">
      <ul>
        <li><font size="+1">Formatar...</font></li>
      </ul>
    </td>
  </tr>
  <tr>
    <td width="1" bgcolor="silver">&nbsp;</td>
    <td bgcolor="white" align="left">
      <ul style="margin-left: 3em;">
        <li><a href="#italics">Texto em It&aacute;lico</a></li>
        <li><a href="#bold">Texto em Negrito</a></li>
        <li><a href="#underl">Texto Sublinhado</a></li>
        <li><a href="#spaced">T e x t o &nbsp; E s p a &ccedil; a d o</a></li>
        <li><a href="#font_ch">Altera&ccedil;&otilde;es da Fonte</a></li>
        <li><a href="#small_caps">Texto em Mai&uacute;sculas Mais Pequenas <span style="font-variant: small-caps">(Small Capitals)</span></a></li>
        <li><a href="#word_caps">Texto em Mai&uacute;sculas</a></li>
        <li><a href="#font_sz">Altera&ccedil;&otilde;es no Tamanho da Fonte</a></li>
        <li><a href="#extra_sp">Espa&ccedil;o Extra Entre Palavras</a></li>
        <li><a href="#supers">Texto Superescrito (Superscripts)</a></li>
        <li><a href="#subscr">Texto Subescrito (Subscripts)</a></li>
        <li><a href="#page_ref">Refer&ecirc;ncias a P&aacute;ginas "(Ver Pag. 123)"</a></li>
        <li><a href="#line_br">Quebras de Linha</a></li>
        <li><a href="#chap_head">T&iacute;tulos de Cap&iacute;tulo</a></li>
        <li><a href="#sect_head">T&iacute;tulos de Sec&ccedil;&atilde;o</a></li>
        <li><a href="#maj_div">Outras Divis&otilde;es do Texto</a></li>
        <li><a href="#para_space">Espa&ccedil;o entre Par&aacute;grafos e Avan&ccedil;os</a></li>
        <li><a href="#extra_s">Espa&ccedil;os Extra/Asteriscos/Linhas Entre Par&aacute;grafos</a></li>
        <li><a href="#illust">Figuras</a></li>
        <li><a href="#footnotes">Notas de Rodap&eacute;</a></li>
        <li><a href="#para_side">Notas (Sidenotes)</a></li>
        <li><a href="#block_qt">Cita&ccedil;&otilde;es</a></li>
        <li><a href="#mult_col">V&aacute;rias Colunas</a></li>
        <li><a href="#lists">Lista de Itens</a></li>
        <li><a href="#tables">Tabelas</a></li>
        <li><a href="#poetry">Poesia/Epigramas</a></li>
        <li><a href="#line_no">Numera&ccedil;&atilde;o de Linhas</a></li>
        <li><a href="#letter">Cartas/Correspond&ecirc;ncia</a></li>
        <li><a href="#blank_pg">P&aacute;gina em Branco</a></li>
        <li><a href="#title_pg">P&aacute;gina de Rosto (Frente e Verso)</a></li>
        <li><a href="#toc">Tabela de Conte&uacute;dos</a></li>
        <li><a href="#bk_index">&Iacute;ndices</a></li>
        <li><a href="#play_n">Pe&ccedil;as de Teatro: Nome de Actores/Marca&ccedil;&otilde;es C&eacute;nicas</a></li>
        <li><a href="#anything">Qualquer outra situa&ccedil;&atilde;o que necessite de um tratamento especial ou que suscite d&uacute;vidas</a></li>
        <li><a href="#prev_notes">Notas de Volunt&aacute;rios Precedentes</a></li>
      </ul>
    </td>
  </tr>
  <tr>
    <td width="1" bgcolor="silver">&nbsp;</td>
    <td bgcolor="silver" align="left">
    <ul>
      <li><font size="+1">Regras Espec&iacute;ficas para Livros Especiais</font></li>
    </ul>
    </td>
  </tr>
  <tr>
    <td width="1" bgcolor="silver">&nbsp;</td>
    <td bgcolor="white" align="left">
      <ul style="margin-left: 3em;">
        <li><a href="#sp_ency">Enciclop&eacute;dias</a></li>
        <li><a href="#sp_poet">Livros de Poesia</a></li>
        <li><a href="#sp_chem">Livros de Qu&iacute;mica</a>   [em constru&ccedil;&atilde;o...]</li>
        <li><a href="#sp_math">Livros de Matem&aacute;tica</a> [em constru&ccedil;&atilde;o...]</li>
      </ul>
    </td>
  </tr>
  <tr>
    <td width="1" bgcolor="silver">&nbsp;</td>
    <td bgcolor="silver" align="left">
    <ul>
      <li><font size="+1">Problemas Comuns</font></li>
    </ul>
    </td>
  </tr>
  <tr>
    <td width="1" bgcolor="silver">&nbsp;</td>
    <td bgcolor="white" align="left">
      <ul style="margin-left: 3em;">
        <li><a href="#bad_image">Imagens Danificadas</a></li>
        <li><a href="#bad_text">Imagem N&atilde;o Corresponde ao Texto</a></li>
        <li><a href="#round1">Erros de Revis&atilde;o ou Formata&ccedil;&atilde;o Precedentes</a></li>
        <li><a href="#p_errors">Erros de Impress&atilde;o/Ortografia</a></li>
        <li><a href="#f_errors">Erros Factuais no Texto</a></li>
        <li><a href="#uncertain">Termos Desconhecidos</a></li>
      </ul>
    </td>
  </tr>
  <tr>
    <td width="1" bgcolor="silver">&nbsp;</td>
    <td bgcolor="silver">&nbsp;</td>
  </tr>
 </tbody>
</table>

<h3><a name="prime">A Regra B&aacute;sica</a></h3>
<p><em>"N&atilde;o alterar o que o autor escreveu!"</em>
</p>
<p>O livro electr&oacute;nico definitivo, lido daqui a muitos
   anos no futuro, deve transmitir exactamente o que o autor queria dizer.
   Se o autor escreveu uma palavra de uma forma estranha, deve mant&ecirc;-la assim. Se o autor
   escreveu declara&ccedil;&otilde;es marcadamente tendenciosas ou
   racistas, deve mant&ecirc;-las assim. Se o autor usou o it&aacute;lico ou o negrito,
   ou se colocou uma nota de rodap&eacute; em cada tr&ecirc;s palavras,
   deve usar o it&aacute;lico, o negrito e as notas de rodap&eacute;
   respectivamente. (Consulte <a href="#p_errors">Erros de
   Impress&atilde;o/Ortografia</a> para corrigir erros &oacute;bvios de tipografia.)
</p>
<p>Mudamos apenas pequenas conven&ccedil;&otilde;es tipogr&aacute;ficas que
   n&atilde;o afectem o sentido do que o autor escreveu.
   Por exemplo, juntamos  as palavras hifenizadas no final de uma linha
   (<a href="#eol_hyphen">Hifeniza&ccedil;&atilde;o no Final da Linha</a>).
   Altera&ccedil;&otilde;es como esta ajuda-nos a criar uma vers&atilde;o do
   livro formatada e consistente (<em>normalizada</em>).
   As regras de formata&ccedil;&atilde;o, que seguimos, foram especificamente
   pensadas para atingir esse objectivo. Por favor, leia as
   Regras de Formata&ccedil;&atilde;o com esta ideia em mente.
</p>
<p>Para ajudar o formatador e o p&oacute;s-processador, preserve as
   <a href="#line_br">Quebras de Linha</a>.
   Este passo facilitar&aacute; a compara&ccedil;&atilde;o entre o texto e a imagem.
</p>
<!-- END RR -->

<table width="100%" border="0" cellspacing="0" summary="Summary Guidelines">
  <tbody>
    <tr>
      <td bgcolor="silver">&nbsp;</td>
    </tr>
  </tbody>
</table>

<h3><a name="summary">Resumo das Regras</a></h3>
<p>O <a href="formatting_summary.pdf">Resumo das Regras de Formata&ccedil;&atilde;o</a>
   &eacute; um pequeno documento de duas p&aacute;ginas
   (em  formato pdf) que resume os principais pontos das Regras de
   Formata&ccedil;&atilde;o, e que d&aacute; algumas dicas de como
   formatar. Os Formatadores Principiantes
   devem imprimi-lo e mant&ecirc;-lo &agrave; m&atilde;o enquanto est&atilde;o
   a fazer a formata&ccedil;&atilde;o.
</p>
<p>Pode necessitar de descarregar e instalar um leitor de pdf. Pode
   descarreg&aacute;-lo gratuitamente a partir da Adobe&reg;
   (<a href="http://www.adobe.com/products/acrobat/readstep2.html">aqui</a>).
</p>

<h3><a name="about">Sobre Este Documento</a></h3>
<p>Este documento existe para explicar as regras de formata&ccedil;&atilde;o
   utilizadas para garantir a consist&ecirc;ncia ao formatar
   um livro, que &eacute; trabalhado por v&aacute;rios formatadores, em que
   cada um formata p&aacute;ginas diferentes.
   Este documento ajuda-nos a formatar <em>da mesma forma</em>. Assim,
   ajuda no trabalho do p&oacute;s-processador,
   facilitando o processo de juntar as p&aacute;ginas revistas num e-book.
</p>
<p><i>Com as Regras n&atilde;o se pretende a cria&ccedil;&atilde;o de um livro de estilo</i>.
</p>
<p>Inclu&iacute;mos neste documento todos os itens de formata&ccedil;&atilde;o
   em que os novos utilizadores tiveram d&uacute;vidas
   enquanto formatavam. Se faltar algum item, se
   achar que deveria haver uma outra metodologia, ou se achar alguma
   explica&ccedil;&atilde;o vaga, por favor diga-nos.
</p>
<p>Este documento &eacute; um trabalho em curso. Ajude-nos, dando-nos
   sugest&otilde;es no Documentation Forum
   (F&oacute;rum da Documenta&ccedil;&atilde;o)
   <a href="<? echo $Guideline_discussion_URL; ?>">nesta liga&ccedil;&atilde;o</a>.
</p>

<h3><a name="comments">Coment&aacute;rios do Projecto</a></h3>

<p>Na P&aacute;gina do Projecto onde come&ccedil;a a formatar p&aacute;ginas,
   existe a sec&ccedil;&atilde;o "Project Comments" (Coment&aacute;rios do Projecto)
   que cont&eacute;m informa&ccedil;&atilde;o espec&iacute;fica desse projecto (livro). <b>Leia-a
   antes de come&ccedil;ar a formata&ccedil;&atilde;o!</b> Se o Gestor do Projecto quiser formatar
   o livro de forma diferente da especificada nas Regras, escrever&aacute;
   a&iacute; as suas indica&ccedil;&otilde;es (instru&ccedil;&otilde;es).
   As instru&ccedil;&otilde;es dos Coment&aacute;rios do Projecto <em>tomam
   preced&ecirc;ncia</em> em rela&ccedil;&atilde;o &agrave;s
   Regras e devem ser seguidas. (&Eacute; tamb&eacute;m aqui que o Gestor do Projecto pode escrever
   algumas curiosidades sobre o autor ou sobre o projecto.)
</p>
<p><em>Por favor, leia tamb&eacute;m o f&oacute;rum do livro (discuss&atilde;o do projecto)</em>:
   O Gestor do Projecto pode us&aacute;-lo para esclarecer regras espec&iacute;ficas do
   projecto sendo utilizado pelos volunt&aacute;rios para avisar outros volunt&aacute;rios de
   quest&otilde;es recorrentes e para definir a melhor forma de as tratar. (Ver em baixo).
</p>
<p>Na P&aacute;gina do Projecto, a liga&ccedil;&atilde;o 'Images, Pages Proofread,
   &amp; Differences' (Imagens, P&aacute;ginas Revistas & Diferen&ccedil;as) &eacute; poss&iacute;vel
   ver como &eacute; que outros revisores fizeram altera&ccedil;&otilde;es.
   <a href="<? echo $Using_project_details_URL ?>">Neste f&oacute;rum</a>
   debatem-se diferentes formas de usar este tipo de informa&ccedil;&atilde;o.
</p>

<h3><a name="forums">F&oacute;rum/Discuss&atilde;o deste Projecto</a></h3>
<p>Na P&aacute;gina do Projecto onde come&ccedil;a a formatar
   p&aacute;ginas, na linha denominada "Forum", existe
   a liga&ccedil;&atilde;o "Discuss this Project" (se algu&eacute;m j&aacute;
   tiver dado in&iacute;cio &agrave; discuss&atilde;o), ou "Start
   a discussion on this Project" (se ainda ningu&eacute;m o tiver feito).
   Ao clicar nessa liga&ccedil;&atilde;o, ir&aacute; para um t&oacute;pico (thread) do
   f&oacute;rum de projectos dedicado a esse projecto espec&iacute;fico.
   Esse &eacute; o local onde deve colocar as suas d&uacute;vidas
   sobre o livro, informar o Gestor de Projecto de problemas, etc.
   Utilizar este t&oacute;pico do f&oacute;rum
   &eacute; a forma recomendada de comunica&ccedil;&atilde;o com o Gestor de Projecto e com
   outros volunt&aacute;rios que trabalham nesse livro.
</p>

<h3><a name="prev_pg">Correc&ccedil;&atilde;o de erros em P&aacute;ginas Precedentes</a></h3>
<p>Ao seleccionar um projecto para formatar, surge a <a href="#comments">P&aacute;gina do Projecto</a>.
   Esta p&aacute;gina tem liga&ccedil;&otilde;es para as p&aacute;ginas que
   trabalhou recentemente. (Se ainda n&atilde;o trabalhou nenhuma, n&atilde;o existir&aacute;
   nenhuma liga&ccedil;&atilde;o.)
</p>
<p>As p&aacute;ginas listadas por baixo de "DONE" (Conclu&iacute;das)
   ou "IN PROGRESS" (Em Processamento) est&atilde;o dispon&iacute;veis para
   edi&ccedil;&atilde;o ou para conclus&atilde;o de formata&ccedil;&atilde;o.
   Basta clicar na liga&ccedil;&atilde;o para a p&aacute;gina em quest&atilde;o. Assim, quando
   descobrir que se enganou numa p&aacute;gina, ou quando marcar algo incorrectamente, pode
   clicar aqui para reabrir a p&aacute;gina e corrigir o erro.
</p>
<p>Pode utilizar as liga&ccedil;&otilde;es "Images, Pages Proofread, &amp;
   Differences" ou "Just My Pages" dispon&iacute;veis
   na <a href="#comments">P&aacute;gina do Projecto</a>. Estas p&aacute;ginas
   disponibilizar&atilde;o uma liga&ccedil;&atilde;o de edi&ccedil;&atilde;o ("Edit")
   junto &agrave;s p&aacute;ginas em que trabalhou na ronda actual e que ainda podem ser corrigidas.
</p>
<p>Para mais informa&ccedil;&otilde;es, consulte os t&oacute;picos
   <a href="prooffacehelp.php?i_type=0">Standard
   Proofreading Interface Help</a> (Ajuda de Interface Normalizada de Revis&atilde;o)
   ou a <a href="prooffacehelp.php?i_type=1">Enhanced Proofreading
   Interface Help</a> (Ajuda de Interface Melhorada de Revis&atilde;o),
   dependendo da interface que utilizar.
</p>
<!-- END RR -->
<table width="100%" border="0" cellspacing="0" cellpadding="6" summary="Title Page">
  <tbody>
    <tr>
      <td bgcolor="silver"><font size="+2">Formatar...</font></td>
    </tr>
  </tbody>
</table>


<h3><a name="italics">Texto em It&aacute;lico</a></h3>
<p>Formate o texto em <i>it&aacute;lico</i> colocando-o entre <tt>&lt;i&gt;</tt> e
   <tt>&lt;/i&gt;</tt>. (N&atilde;o se esque&ccedil;a do "/" ao fechar a tag de
   it&aacute;lico).
</p>
<p>A pontua&ccedil;&atilde;o &eacute; colocada <b>fora</b> do it&aacute;lico,
   a n&atilde;o ser quando a frase ou sec&ccedil;&atilde;o
   esteja toda em it&aacute;lico, ou quando a pontua&ccedil;&atilde;o em si
   faz parte de uma frase, t&iacute;tulo ou abreviatura que esteja em it&aacute;lico.
</p>
<p>Os pontos que indicam que se trata de uma abreviatura num
   t&iacute;tulo de um jornal como <i>Phil. Trans.</i>, s&atilde;o parte do
   t&iacute;tulo no que toca ao it&aacute;lico, e s&atilde;o formatados assim:
   <tt>&lt;i&gt;Phil. Trans.&lt;/i&gt;</tt>.
</p>
<p>No caso de datas e semelhantes, formate toda a frase como se fosse
   it&aacute;lico, em vez de formatar apenas as palavras como it&aacute;lico
   e n&atilde;o os n&uacute;meros. O motivo desta diferen&ccedil;a &eacute;
   o facto de muitos tipos de letra existentes em livros antigos terem o mesmo formato,
   independentemente do n&uacute;mero ser it&aacute;lico ou n&atilde;o.
</p>
<p>Se o texto em it&aacute;lico for uma lista de palavras ou nomes, coloque-os individualmente entre
   tags de it&aacute;lico.
</p>
<!-- END RR -->

<p><b>Exemplos</b>&mdash;Texto em It&aacute;lico:
</p>

<table width="100%" align="center" border="1" cellpadding="4" cellspacing="0" summary="Italics">
  <tbody>
    <tr>
      <th valign="top" bgcolor="cornsilk">Texto Original:</th>
      <th valign="top" bgcolor="cornsilk">Texto Formatado Correctamente:</th>
    </tr>
    <tr>
      <td valign="top"><i>Enacted </i>4<i> July, </i>1776 </td>
      <td valign="top"><tt>&lt;i&gt;Enacted 4 July, 1776&lt;/i&gt;</tt> </td>
    </tr>
    <tr>
      <td valign="top"><i>God knows what she saw in me!</i> I spoke<br> in such an affected manner.</td>
      <td valign="top"><tt>&lt;i&gt;God knows what she saw in me!&lt;/i&gt; I spoke<br> in such an affected manner.</tt></td>
    </tr>
    <tr>
      <td valign="top">As in many other of these <i>Studies</i>, and</td>
      <td valign="top"><tt>As in many other of these &lt;i&gt;Studies&lt;/i&gt;, and</tt></td>
    </tr>
    <tr>
      <td valign="top">(<i>Psychological Review</i>, 1898, p. 160)</td>
      <td valign="top"><tt>(&lt;i&gt;Psychological Review&lt;/i&gt;, 1898, p. 160)</tt></td>
    </tr>
    <tr>
      <td valign="top">L. Robinson, art. "<i>Ticklishness</i>,"</td>
      <td valign="top"><tt>L. Robinson, art. "&lt;i&gt;Ticklishness&lt;/i&gt;,"</tt></td>
    </tr>
    <tr>
      <td valign="top" align="right"><i>December</i> 3, <i>morning</i>.<br />
                     1323 Picadilly Circus</td>
      <td valign="top"><tt>/*<br />
         &lt;i&gt;December 3, morning.&lt;/i&gt;<br />
         1323 Picadilly Circus<br />
         */</tt></td>
    </tr>
    <tr>
      <td valign="top">
      Volunteers may be tickled pink to read<br>
      <i>Ticklishness</i>, <i>Tickling and Laughter</i>,<br>
      <i>Remarks on Tickling and Laughter</i><br>
      and <i>Ticklishness, Laughter and Humour</i>.
      </td>
      <td valign="top">
      <tt>Volunteers may be tickled pink to read<br>
      &lt;i&gt;Ticklishness&lt;/i&gt;, &lt;i&gt;Tickling and Laughter&lt;/i&gt;,<br>
      &lt;i&gt;Remarks on Tickling and Laughter&lt;/i&gt;<br>
      and &lt;i&gt;Ticklishness, Laughter and Humour&lt;/i&gt;.</tt>
      </td>
    </tr>
  </tbody>
</table>

<h3><a name="bold">Texto em Negrito</a></h3>
<p>Formate o <b>texto em negrito</b> (texto impresso de forma mais carregada) colocando-o
   entre <tt>&lt;b&gt;</tt> e <tt>&lt;/b&gt;</tt>. (N&atilde;o se
   esque&ccedil;a do "/" ao fechar a tag de negrito).
</p>
<p>A pontua&ccedil;&atilde;o &eacute; colocada <b>fora</b> do negrito,
   a n&atilde;o ser quando a frase ou sec&ccedil;&atilde;o
   esteja toda em negrito, ou quando a pontua&ccedil;&atilde;o em si faz parte
   de uma frase, t&iacute;tulo ou abreviatura que esteja em negrito.
</p>
<p>Veja a imagem e o texto do <a href="#page_hf">Cabe&ccedil;alho e
   Rodap&eacute; da P&aacute;gina</a>, como exemplo.
</p>
<p>Alguns Gestores de Projecto pedem nos
   <a href="#comments">Coment&aacute;rios do Projecto</a> para que
   o texto em negrito seja formatado todo em mai&uacute;sculas.
</p>

<h3><a name="underl">Texto Sublinhado</a></h3>
<p>Formate o <u>texto sublinhado</u> como se fosse <a href="#italics">It&aacute;lico</a>,
   ou seja, colocando-o entre <tt>&lt;i&gt;</tt> e
   <tt>&lt;/i&gt;</tt>. (N&atilde;o se esque&ccedil;a do "/" ao fechar a tag).
</p>
<p>O sublinhado serve muitas vezes para dar &ecirc;nfase quando n&atilde;o
   era poss&iacute;vel escrever o texto em it&aacute;lico, como &eacute;
   o caso dos documentos dactilografados.
</p>
<p>Alguns Gestores de Projecto podem pedir nos <a href="#comments">Coment&aacute;rios do Projecto</a>
   para colocar o texto sublinhado entre <tt>&lt;u&gt;</tt> e <tt>&lt;/u&gt;</tt>.
</p>

<h3><a name="spaced">T e x t o &nbsp; E s p a &ccedil; a d o</a></h3>
<p>Formate o texto  e s p a &ccedil; a d o colocando-o entre <tt>&lt;g&gt;</tt> e
   <tt>&lt;/g&gt;</tt>. (N&atilde;o se esque&ccedil;a do "/" ao fechar
   a tag). Remova o espa&ccedil;o extra entre as letras de cada palavra.
</p>
<p>A pontua&ccedil;&atilde;o &eacute; colocada <b>fora</b> das tags,
   a n&atilde;o ser quando a frase ou sec&ccedil;&atilde;o
   seja toda espa&ccedil;ada, ou quando a pontua&ccedil;&atilde;o
   em si faz parte de uma frase espa&ccedil;ada.
</p>
<p>Esta era uma t&eacute;cnica usada para dar &ecirc;nfase a uma parte do
   texto nos livros mais antigos, nomeadamente em alem&atilde;o.
</p>

<h3><a name="font_ch">Altera&ccedil;&otilde;es da Fonte</a></h3>
<p>Formate uma altera&ccedil;&atilde;o da fonte inserida num par&aacute;grafo
   ou linha de texto normal, colocando-a entre <tt>&lt;f&gt;</tt> e
   <tt>&lt;/f&gt;</tt>. (N&atilde;o se esque&ccedil;a do "/" ao fechar a tag).
   Utilize esta sinaliza&ccedil;&atilde;o para identificar quaisquer fontes
   especiais ou outra formata&ccedil;&atilde;o, <b>excepto</b> negrito,
   it&aacute;lico, mai&uacute;sculas mais pequenas (small caps), e texto
   espa&ccedil;ado, que t&ecirc;m tags pr&oacute;prias.
</p>
<p>Utiliza&ccedil;&otilde;es poss&iacute;veis desta sinaliza&ccedil;&atilde;o:</p>
<ul compact>
  <li>antiqua (uma variante da fonte roman) dentro de fraktur</li>
  <li>blackletter dentro de uma sec&ccedil;&atilde;o de texto normal</li>
  <li>fonte maior ou menor se estiver <b>inclu&iacute;da</b> num par&aacute;grafo de fonte normal
    (se for um par&aacute;grafo inteiro num tamanho ou fonte diferente, consulte a
    sec&ccedil;&atilde;o de <a href="#block_qt">cita&ccedil;&otilde;es</a>)</li>
  <li>texto normal inserido num par&aacute;grafo de texto em it&aacute;lico</li>
</ul>
<p>A utiliza&ccedil;&atilde;o espec&iacute;fica desta sinaliza&ccedil;&atilde;o
   num projecto, estar&aacute; geralmente documentada nos
   <a href="#comments">Coment&aacute;rios do Projecto</a>. Os formatadores
   devem colocar uma mensagem na <a href="#forums">Discuss&atilde;o do Projecto</a>
   se achar necess&aacute;ria uma sinaliza&ccedil;&atilde;o ainda n&atilde;o discutida.
</p>
<p>A pontua&ccedil;&atilde;o &eacute; colocada <b>fora</b> do texto com fonte
   diferente, a n&atilde;o ser quando a frase ou sec&ccedil;&atilde;o
   esteja toda com fonte diferente, ou quando a pontua&ccedil;&atilde;o
   em si faz parte de uma frase, t&iacute;tulo
   ou abreviatura que esteja com fonte diferente.
</p>

<h3><a name="small_caps">Texto em Mai&uacute;sculas Mais Pequenas (Small Caps)</a></h3>
<p>A sinaliza&ccedil;&atilde;o &eacute; diferente nos casos de texto em letra
   mai&uacute;scula maior e menor misturada (<span style="font-variant:small-caps;">Mixed Case Small Caps</span>)
   e <span style="font-variant: small-caps;">texto em mai&uacute;sculas mais pequenas</span>:
</p>
<p>Formate as palavras impressas em letra mai&uacute;scula maior e mais
   pequena (<span style="font-variant: small-caps;">Mixed Small Caps</span>)
   misturando letras mai&uacute;sculas e min&uacute;sculas, colocando o texto
   entre <tt>&lt;sc&gt;</tt> e <tt>&lt;/sc&gt;</tt>
  <br>
&nbsp;&nbsp;&nbsp;&nbsp;Exemplo:
   <span style="font-variant: small-caps;">This is Small Caps</span> <br>
&nbsp;&nbsp;&nbsp;&nbsp;seria formatado correctamente como:
   <tt>&lt;sc&gt;This is Small Caps&lt;/sc&gt;</tt>.
</p>

<p>Formate as palavras impressas apenas em letra mai&uacute;scula mais pequena
   (<span style="font-variant: small-caps;">small caps</span>)
   com letras MAI&Uacute;SCULAS, colocando o texto entre <tt>&lt;sc&gt;</tt> e <tt>&lt;/sc&gt;</tt>.
   <br>
&nbsp;&nbsp;&nbsp;&nbsp;Exemplo:
   You cannot be serious about
   <span style="font-variant: small-caps;">aardvarks</span>!<br>
&nbsp;&nbsp;&nbsp;&nbsp;seria formatado correctamente como:
   <tt>You cannot be serious about
   &lt;sc&gt;AARDVARKS&lt;/sc&gt;!</tt> <br>
</p>

<p>As palavras de t&iacute;tulos (de cap&iacute;tulos, sub-cap&iacute;tulos,
   sec&ccedil;&otilde;es, etc.) que surjam inteiramente em letra mai&uacute;scula,
   devem ser formatados como letra mai&uacute;scula, sem utilizar &lt;sc&gt;
   nem &lt;/sc&gt;. Se a primeira palavra de um cap&iacute;tulo estiver
   em Small Caps, deve ser formatada como Mixed Small Caps sem sinaliza&ccedil;&atilde;o.
</p>

<h3><a name="word_caps">Texto em Mai&uacute;sculas</a></h3>
<p>Formate as palavras impressas em letra mai&uacute;scula como palavras de letra mai&uacute;scula.
</p>
<p>Como excep&ccedil;&atilde;o, temos <a href="#chap_head">primeira palavra
   do texto de um cap&iacute;tulo</a>: muitos livros antigos come&ccedil;am o
   texto de um cap&iacute;tulo com uma palavra toda escrita em mai&uacute;sculas; deve mudar para letra
   mai&uacute;scula e min&uacute;scula, para que "ERA uma vez," se torne "<tt>Era uma vez,</tt>".
</p>

<h3><a name="font_sz">Altera&ccedil;&otilde;es no Tamanho da Fonte</a></h3>
<p>Geralmente, n&atilde;o fazemos nada relativamente a altera&ccedil;&otilde;es no tamanho da fonte.
</p>
<p>Como excep&ccedil;&atilde;o, temos a altera&ccedil;&atilde;o de tamanho da fonte para evidenciar as
   <a href="#block_qt">cita&ccedil;&otilde;es</a>, ou as altera&ccedil;&otilde;es
   de tamanho da fonte inclu&iacute;da num par&aacute;grafo ou linha de texto
   (consulte <a href="#font_ch">Altera&ccedil;&otilde;es da Fonte</a>).
</p>

<h3><a name="extra_sp">Espa&ccedil;o Extra Entre Palavras</a></h3>
<p>Os espa&ccedil;os extra entre palavras s&atilde;o comuns nos resultados
   de OCR. N&atilde;o &eacute; necess&aacute;rio
   remov&ecirc;-los&mdash;estes s&atilde;o facilmente removidos durante o p&oacute;s-processamento.
</p>
<p>No entanto, <b>&eacute; necess&aacute;rio</b> remover o espa&ccedil;amento
   extra relativo &agrave; pontua&ccedil;&atilde;o, tra&ccedil;os, aspas, etc,
   quando o s&iacute;mbolo surge separado da palavra.
</p>
<p>Por exemplo, em <tt>A horse&nbsp;;&nbsp;&nbsp;my kingdom for a horse.</tt>
   o espa&ccedil;o entre a palavra "horse" e o ponto e v&iacute;rgula deve ser
   removido. Mas os dois espa&ccedil;os ap&oacute;s o ponto e v&iacute;rgula n&atilde;o s&atilde;o
   problem&aacute;ticos&mdash;n&atilde;o &eacute; necess&aacute;rio apagar um deles.
</p>

<h3><a name="supers">Texto Superescrito (Superscripts)</a></h3>
<p>Os livros antigos abreviam frequentemente palavras em contrac&ccedil;&otilde;es, e imprimem-nas como
   texto superescrito. Por exemplo:<br>
   &nbsp;&nbsp;&nbsp;&nbsp;Gen<sup>rl</sup> Washington defeated L<sup>d</sup> Cornwall's army.<br>
   Formate estas contrac&ccedil;&otilde;es inserindo um acento circunflexo, seguido do texto superescrito, assim:<br>
   &nbsp;&nbsp;&nbsp;&nbsp;<tt>Gen^rl Washington defeated L^d Cornwall's army.</tt>
</p>
<p>Nos livros t&eacute;cnicos e cient&iacute;ficos, formate o texto superescrito entre chavetas
   <tt>{</tt> e <tt>}</tt> , mesmo que seja apenas um caractere superescrito.
   <br>Por exemplo:
   <br>&nbsp;&nbsp;&nbsp;&nbsp;... up to x<sup>n-1</sup> elements in the array.
   <br>deve ser formatado assim:
   <br>&nbsp;&nbsp;&nbsp;&nbsp;<tt>... up to x^{n-1} elements in the array.<br></tt>
</p>
<p>O Gestor do Projecto pode dar instru&ccedil;&otilde;es diferentes
   nos <a href="#comments">Coment&aacute;rios do Projecto</a>.
</p>

<h3><a name="subscr">Texto Subescrito (Subscripts)</a></h3>
<p>O texto subescrito encontra-se geralmente em trabalhos cient&iacute;ficos,
   mas n&atilde;o &eacute; comum nos outros
   livros. Formate-o colocando um underscore <tt>_</tt> antes
   e coloque-o entre chavetas <tt>{</tt> e <tt>}</tt>.
   <br>Por exemplo:
   <br>&nbsp;&nbsp;&nbsp;&nbsp;H<sub>2</sub>O.
   <br>deve ser formatado assim:
   <br>&nbsp;&nbsp;&nbsp;&nbsp;<tt>H_{2}O.<br></tt>
</p>

<h3><a name="page_ref">Refer&ecirc;ncias a P&aacute;ginas &quot;(Ver Pag. 123)&quot;</a></h3>
<p>Formate as refer&ecirc;ncias a p&aacute;ginas como <tt>(ver pag. 123)</tt> tal
   como surgem na imagem.</p>
<p>O Gestor de Projecto pode pedir um tratamento diferente para as refer&ecirc;ncias
   a p&aacute;ginas. Consulte os <a href="#comments">Coment&aacute;rios do Projecto</a>
   para saber como actuar neste caso.
</p>

<h3><a name="line_br">Quebras de Linha</a></h3>
<p><b>Mantenha todas as quebras de linha existentes</b> para que o pr&oacute;ximo
   formatador e p&oacute;s-processador possam comparar as linhas
   do texto formatado com as linhas das imagens mais facilmente. Tenha especial aten&ccedil;&atilde;o
   ao juntar <a href="#eol_hyphen">palavras hifenizadas</a> ou ao mover palavras adjacentes a
   <a href="#em_dashes">tra&ccedil;os</a>. Se o volunt&aacute;rio anterior
   tiver alterado as quebras de linha, por favor, recoloque-as de forma a representar
   a imagem mais fielmente.
</p>
<p>Todos os espa&ccedil;os extra entre linhas que n&atilde;o se encontrem na
   imagem, devem ser removidos, excepto quando s&atilde;o colocados intencionalmente
   por quest&otilde;es de formata&ccedil;&atilde;o. As linhas em branco no fim de
   cada p&aacute;gina n&atilde;o s&atilde;o problem&aacute;ticas&mdash;estas s&atilde;o removidas
   quando gravar a p&aacute;gina.
</p>

<!-- END RR -->
<!-- We should have an example right here for this. -->

<h3><a name="chap_head">T&iacute;tulos de Cap&iacute;tulo</a></h3>
<p>Formate os t&iacute;tulos de cap&iacute;tulo tal como surgem no texto.
</p>
<p>Um t&iacute;tulo de cap&iacute;tulo pode come&ccedil;ar um pouco
   mais abaixo do que o <a href="#page_hf">cabe&ccedil;alho</a>
   e n&atilde;o tem o n&uacute;mero da p&aacute;gina na mesma linha.
   Os T&iacute;tulos de Cap&iacute;tulo s&atilde;o impressos geralmente
   em letra mai&uacute;scula; se assim for,
   mantenha-os desta forma. Os T&iacute;tulos de Cap&iacute;tulo s&atilde;o
   impressos geralmente com fontes maiores ou diferentes, podendo
   parecer negrito ou com caracteres espa&ccedil;ados, mas n&atilde;o os
   formatamos como uma fonte diferente
   nem como negrito, nem como texto espa&ccedil;ado; no entanto deve incluir
   a formata&ccedil;&atilde;o em it&aacute;lico ou do texto em mai&uacute;sculas mais pequenas,
   se isso estiver no cabe&ccedil;alho.
</p>
<p>Insira quatro linhas em branco antes do "CAP&Iacute;TULO XXX". Inclua estas
   linhas, mesmo se o cap&iacute;tulo come&ccedil;ar numa p&aacute;gina nova;
   n&atilde;o h&aacute; 'p&aacute;ginas' num e-book, e as linhas em branco
   s&atilde;o necess&aacute;rias. Deixe uma linha em branco entre cada parte adicional
   do t&iacute;tulo do cap&iacute;tulo, que pode ser por exemplo uma descri&ccedil;&atilde;o
   do cap&iacute;tulo ou uma cita&ccedil;&atilde;o introdut&oacute;ria, etc., e
   finalmente, deixe duas linhas em branco antes do in&iacute;cio do texto do cap&iacute;tulo.
</p>
<p>Por vezes, as primeiras palavras de cada cap&iacute;tulo eram impressas em letra
   mai&uacute;scula, nos livros antigos;
   deixe a primeira letra em mai&uacute;scula e altere as outras para min&uacute;sculas.
</p>
<p>Preste aten&ccedil;&atilde;o &agrave; poss&iacute;vel falta de aspas no
   in&iacute;cio do primeiro par&aacute;grafo. Alguns
   editores podem n&atilde;o as ter inclu&iacute;do ou o OCR pode n&atilde;o
   ter captado devido ao in&iacute;cio em mai&uacute;sculas no
   original. Se o autor come&ccedil;ar o par&aacute;grafo com um di&aacute;logo, coloque aspas.
</p>
<!-- END RR -->

<table width="100%" align="center" border="1" cellpadding="4"
 cellspacing="0" summary="Cap&iacute;tulos">
 <tbody>
    <tr><th align="left" bgcolor="cornsilk">Imagem de Exemplo:</th></tr>
    <tr align="left">
      <td width="100%" valign="top"> <img src="chap1.png" alt=""
          width="500" height="725"><br>
      </td>
    </tr>
    <tr><th align="left" bgcolor="cornsilk">Texto Formatado Correctamente:</th></tr>
    <tr>
      <td width="100%" valign="top">
<table summary="" border="0" align="left"><tr><td>
    <tt>
    GREEN FANCY<br>
    <br>
    <br>
    <br>
    <br>
    CHAPTER I<br>
    <br>
    THE FIRST WAYFARER AND THE SECOND WAYFARER<br>
    MEET AND PART ON THE HIGHWAY<br>
    <br>
    <br>
    A solitary figure trudged along the narrow<br>
    road that wound its serpentinous way<br>
    through the dismal, forbidding depths of<br>
    the forest: a man who, though weary and footsore,<br>
    lagged not in his swift, resolute advance. Night<br>
    was coming on, and with it the no uncertain prospects<br>
    of storm. Through the foliage that overhung<br>
    the wretched road, his ever-lifting and apprehensive<br>
    eye caught sight of the thunder-black, low-lying<br>
    clouds that swept over the mountain and bore<br>
    down upon the green, whistling tops of the trees.<br>
    <br>
    At a cross-road below he had encountered a small<br>
    girl driving homeward the cows. She was afraid<br>
    of the big, strange man with the bundle on his back<br>
    and the stout walking stick in his hand: to her a<br>
    remarkable creature who wore "knee pants" and<br>
    stockings like a boy on Sunday, and hob-nail shoes,<br>
    and a funny coat with "pleats" and a belt, and a<br>
    green hat with a feather sticking up from the band.
    </tt>
</td></tr></table>
      </td>
    </tr>
  </tbody>
</table>
<br>
<table width="100%" align="center" border="1" cellpadding="4"
 cellspacing="0" summary="Cabe&ccedil;alho/Rodap&eacute; da P&aacute;gina">
  <tbody>
    <tr><th align="left" bgcolor="cornsilk">Imagem de Exemplo:</th></tr>
    <tr align="left">
      <td width="100%" valign="top">
      <img src="foot.png" alt="" width="500" height="850"><br>
      </td>
    </tr>
    <tr><th align="left" bgcolor="cornsilk">Texto Formatado Correctamente:</th></tr>
    <tr>
      <td width="100%" valign="top">
<table summary="" border="0" align="left"><tr><td>
    <tt>/#<br>In the United States?[A] In a railroad? In a mining company?<br>
    In a bank? In a church? In a college?<br>
    <br>
    Write a list of all the corporations that you know or have<br>
    ever heard of, grouping them under the heads &lt;i&gt;public&lt;/i&gt; and &lt;i&gt;private&lt;/i&gt;.<br>
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
    would the use of the land go?<br>#/<br>
    <br>
    <br>
    <br>
    <br>
    CHAPTER XXXV.<br>
    <br>
    &lt;sc&gt;Commercial Paper.&lt;/sc&gt;<br>
    <br>
    <br>
    &lt;b&gt;Kinds and Uses.&lt;/b&gt;--If a man wishes to buy some commodity<br>
    from another but has not the money to pay for<br>
    it, he may secure what he wants by giving his written<br>
    promise to pay at some future time. This written<br>
    promise, or &lt;i&gt;note&lt;/i&gt;, the seller prefers to an oral promise<br>
    for several reasons, only two of which need be mentioned<br>
    here: first, because it is &lt;i&gt;prima facie&lt;/i&gt; evidence of<br>
    the debt; and, second, because it may be more easily<br>
    transferred or handed over to some one else.<br>
    <br>
    If J. M. Johnson, of Saint Paul, owes C. M. Jones,<br>
    of Chicago, a hundred dollars, and Nelson Blake, of<br>
    Chicago, owes J. M. Johnson a hundred dollars, it is<br>
    plain that the risk, expense, time and trouble of sending<br>
    the money to and from Chicago may be avoided,<br>
    <br>
    [Footnote A: The United States: "Its charter, the constitution. * * * Its flag the<br>
    symbol of its power; its seal, of its authority."--Dole.]
    </tt>
</td></tr></table>
      </td>
    </tr>
  </tbody>
</table>

<h3><a name="sect_head">T&iacute;tulos de Sec&ccedil;&atilde;o</a></h3>
<p>Alguns textos subdividem-se em sec&ccedil;&otilde;es dentro dos
   cap&iacute;tulos. Formate estes t&iacute;tulos tal como surgem no texto.
   Deixe 2 linhas em branco antes do t&iacute;tulo e uma depois, a n&atilde;o
   ser que o Gestor de Projecto d&ecirc; outras indica&ccedil;&otilde;es.
   Se n&atilde;o tem a certeza se se trata de um t&iacute;tulo de sec&ccedil;&atilde;o
   ou de cap&iacute;tulo, coloque a quest&atilde;o no F&oacute;rum do Projecto,
   referindo o n&uacute;mero da p&aacute;gina. Os T&iacute;tulos de
   Sec&ccedil;&atilde;o s&atilde;o impressos geralmente com fontes
   maiores ou diferentes, podendo surgir em negrito ou espa&ccedil;ados,
   mas n&atilde;o os formatamos como uma fonte diferente nem como negrito, nem como
   texto espa&ccedil;ado; no entanto deve incluir a formata&ccedil;&atilde;o em
   it&aacute;lico ou do texto em mai&uacute;sculas pequenas se surgir no cabe&ccedil;alho.
</p>

<h3><a name="maj_div">Outras Divis&otilde;es do Texto</a></h3>
<p>Subdivis&otilde;es do texto como o Pref&aacute;cio, Tabela de Conte&uacute;dos,
   Introdu&ccedil;&atilde;o, Pr&oacute;logo, Ep&iacute;logo,
   Ap&ecirc;ndices, Refer&ecirc;ncias, Conclus&atilde;o, Gloss&aacute;rio,
   Resumo, Agradecimentos, Bibliografia, etc., devem
   ser formatadas da mesma forma que os T&iacute;tulos de Cap&iacute;tulo,
   <i>ou seja</i>, 4 linhas em branco antes do t&iacute;tulo e 2 linhas
   em branco antes do in&iacute;cio do texto.
</p>

<h3><a name="para_space">Espa&ccedil;o entre Par&aacute;grafos e Avan&ccedil;os</a></h3>
<p>Coloque uma linha em branco antes do in&iacute;cio dos par&aacute;grafos,
   mesmo quando um par&aacute;grafo come&ccedil;a no topo da p&aacute;gina.
   N&atilde;o deve deixar espa&ccedil;o no in&iacute;cio dos par&aacute;grafos,
   mas se os avan&ccedil;os j&aacute; existirem, n&atilde;o
   &eacute; necess&aacute;rio remover esses espa&ccedil;os&mdash;isso pode
   ser feito automaticamente no p&oacute;s-processamento.
</p>
<p>Veja a imagem e o texto dos <a href="#chap_head">T&iacute;tulos de Cap&iacute;tulo</a>, como exemplo.
</p>

<h3><a name="extra_s">Espa&ccedil;os Extra/Asteriscos/Linhas Entre Par&aacute;grafos</a></h3>
<p>A maioria dos par&aacute;grafos come&ccedil;a na linha imediatamente a seguir. Por vezes
   dois par&aacute;grafos encontram-se separados para indicar uma interrup&ccedil;&atilde;o
   na linha de ideias ("thought break"). Este tipo de interrup&ccedil;&atilde;o pode assumir a
   forma de uma linha de asteriscos, h&iacute;fenes ou outro caractere,
   uma linha horizontal simples ou ornamentada, uma decora&ccedil;&atilde;o simples,
   ou apenas uma linha extra em branco.
</p>
<p>Este tipo de interrup&ccedil;&atilde;o pode representar uma mudan&ccedil;a
   de cen&aacute;rio ou assunto, um salto no tempo ou um pouco
   de suspense. &Eacute; uma interrup&ccedil;&atilde;o propositada do autor,
   devendo ser preservada. Formate estas situa&ccedil;&otilde;es, colocando a
   <tt>&lt;tb&gt;</tt> e deixando uma linha branco antes e depois.
</p>
<p>Os Gestores de Projecto e/ou os P&oacute;s-processadores poder&atilde;o
   solicitar informa&ccedil;&atilde;o adicional. Por exemplo, alguns projectos
   cont&ecirc;m v&aacute;rios tipos de divis&otilde;es de texto, utilizando diferentes
   estilos tais como uma linha de asteriscos numa determinada passagem do texto
   e uma linha em branco noutra. Nestes casos, &eacute; poss&iacute;vel que
   surja uma nota nos Coment&aacute;rios do Projecto solicitando que se formate
   como <tt>&lt;tb stars&gt;</tt> no primeiro caso e <tt>&lt;tb&gt;</tt> no
   segundo. Por favor, n&atilde;o deixe de ler os coment&aacute;rios do projecto
   atentamente, para que tome conhecimento das especifica&ccedil;&otilde;es
   do projecto em quest&atilde;o. Lembre-se que as regras existentes nos
   coment&aacute;rios do projecto s&atilde;o v&aacute;lidas apenas para aquele
   projecto. N&atilde;o as transfira para os outros.
</p>
<p>Algumas tipografias usavam linhas decorativas no final de cada
   cap&iacute;tulo. Como j&aacute; evidenciamos os <a href="#chap_head">T&iacute;tulos
   de Cap&iacute;tulo</a>, n&atilde;o h&aacute; necessidade de acrescentar este
   tipo de formata&ccedil;&atilde;o no fim de cada cap&iacute;tulo.
</p>
<p>O ecr&atilde; de revis&atilde;o/formata&ccedil;&atilde;o disponibiliza
   este tipo de formata&ccedil;&atilde;o atrav&eacute;s do sistema "corta e cola".
</p>
<!-- END RR -->
<br>
<table width="100%" align="center" border="1" cellpadding="4"
 cellspacing="0" summary="Thought Break">
  <tbody>
    <tr><th align="left" bgcolor="cornsilk"> Imagem de Exemplo:</th></tr>
    <tr align="left">
      <td width="100%" valign="top"> <img src="tbreak.png" alt="thought break"
          width="500" height="264"> <br>
      </td>
    </tr>
    <tr><th align="left" bgcolor="cornsilk">Texto Formatado Correctamente:</th></tr>
    <tr>
      <td width="100%" valign="top">
<table summary="" border="0" align="left"><tr><td>
    <p><tt>
    like the gentleman with the spiritual hydrophobia<br>
    in the latter end of Uncle Tom's Cabin.<br>
    Unconsciously Mr. Dixon has done his best to<br>
    prove that Legree was not a fictitious character.</tt>
    </p>
    <p><tt>&lt;tb&gt;</tt>
    </p>
    <p><tt>
    Joel Chandler Harris, Harry Stillwell Edwards,<br>
    George W. Cable, Thomas Nelson Page,<br>
    James Lane Allen, and Mark Twain are Southern<br>
    men in Mr. Griffith's class. I recommend</tt>
    </p>
</td></tr></table>
      </td>
    </tr>
  </tbody>
</table>


<h3><a name="illust">Figuras</a></h3>
<p>O texto relativo a uma figura ser assinalado com <tt>[Illustration:&nbsp;</tt> e <tt>]</tt>
   a rodear o texto da legenda. Formate a nota tal como foi impressa,
   mantendo as quebras de linha, o texto em it&aacute;lico, etc...
</p>
<p>Se a figura n&atilde;o tiver legenda, coloque apenas <tt>[Illustration]</tt>.
</p>
<p>Se a figura estiver no meio ou ao lado de um par&aacute;grafo, mova a
   indica&ccedil;&atilde;o de figura para o in&iacute;cio ou para o final do
   par&aacute;grafo e deixe uma linha em branco para a separar. Junte as linhas do par&aacute;grafo,
   removendo as linhas em branco que possam ter ficado ao mover a figura.
</p>
<p>Se n&atilde;o houver mudan&ccedil;a de par&aacute;grafo na p&aacute;gina,
   acrescente um <tt>*</tt> &agrave; indica&ccedil;&atilde;o de figura, da
   seguinte forma <tt>*[Illustration: <font color="red">(texto da legenda)</font>]</tt>,
   movendo-a para o topo da p&aacute;gina e deixando uma linha em branco depois.
</p>
<!-- END RR -->

<table width="100%" align="center" border="1" cellpadding="4"
 cellspacing="0" summary="Illustration">
  <tbody>
    <tr>
      <th align="left" bgcolor="cornsilk">
      Imagem de Exemplo:
      </th>
    </tr>
    <tr align="left">
      <td width="100%" valign="top"> <img src="illust.png" alt=""
          width="500" height="525"> <br>
      </td>
    </tr>
    <tr>
      <th align="left" bgcolor="cornsilk">Texto Formatado Correctamente:</th>
   </tr>
    <tr>
    <td width="100%" valign="top">
      <table summary="" border="0" align="left"><tr>
      <td>
      <p><tt>[Illustration: Martha told him that he had always been her ideal and<br>
      that she worshipped him.<br>
      <br>
      &lt;i&gt;Frontispiece&lt;/i&gt;<br>
      <br>
      &lt;i&gt;Her Weight in Gold&lt;/i&gt;]
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
     <th align="left" bgcolor="cornsilk">Imagem de Exemplo: (Figura no meio do par&aacute;grafo)</th>
   </tr>
   <tr align="left">
     <td width="100%" valign="top"> <img src="illust2.png" alt=""
         width="500" height="514"> <br>
     </td>
   </tr>
   <tr>
     <th align="left" bgcolor="cornsilk">Texto Formatado Correctamente:</th>
   </tr>
    <tr valign="top">
     <td>
<table summary="" border="0" align="left"><tr><td>
     <p><tt>
     such study are due to Italians. Several of these instruments<br>
     have already been described in this journal, and on the present<br>
     occasion we shall make known a few others that will<br>
     serve to give an idea of the methods employed.<br>
     </tt></p>
     <p><tt>[Illustration: &lt;sc&gt;Fig.&lt;/sc&gt; 1.--APPARATUS FOR THE STUDY OF HORIZONTAL<br>
     SEISMIC MOVEMENTS.]</tt></p>
     <p><tt>
     For the observation of the vertical and horizontal motions<br>
     of the ground, different apparatus are required. The</tt>
     </p>
</td></tr></table>
    </td>
    </tr>
  </tbody>
</table>

<h3><a name="footnotes">Notas de Rodap&eacute;</a></h3>
<p><b>As notas de rodap&eacute; s&atilde;o colocadas fora da linha</b>;
   ou seja, o texto das notas de rodap&eacute; &eacute; movido
   para o final da p&aacute;gina, e uma indica&ccedil;&atilde;o
   &eacute; colocada no local onde vem referenciada.
</p>
<p>Isto significa que na formata&ccedil;&atilde;o:
</p>
<p>1. O n&uacute;mero, letra, ou outro caractere que marca o local da nota de rodap&eacute;, deva
   estar entre par&ecirc;nteses rectos (<tt>[]</tt>) junto
   &agrave; palavra a que se refere<tt>[1]</tt> ou ao seu
   sinal de pontua&ccedil;&atilde;o,<tt>[2]</tt> como apresentamos
   no texto e nos dois exemplos desta frase.
</p>
<p>Quando a nota de rodap&eacute; &eacute; referenciada atrav&eacute;s de
   caracteres especiais (*, &dagger;, &Dagger;, &sect;,
   etc.) substitua-os por letras mai&uacute;sculas por ordem alfab&eacute;tica (A, B, C, etc.).
</p>
<p>2. A nota de rodap&eacute; deve assinalada com <tt>[Footnote #:&nbsp;</tt> e <tt>]</tt>
   a rodear o texto da nota de rodap&eacute;, substituindo o s&iacute;mbolo #
   pelo n&uacute;mero ou letra correspondente. Formate o texto da nota tal como
   foi impresso, mantendo as quebras de linha, o texto em it&aacute;lico, etc...
   Deixe o texto das notas de rodap&eacute; no final da p&aacute;gina. Verifique que o
   n&uacute;mero/letra corresponde &agrave; nota de rodap&eacute;
   correcta. Coloque cada nota de rodap&eacute; numa linha diferente
   pela ordem em que surgem no texto. Coloque uma linha em branco entre cada
   nota de rodap&eacute; no caso de existir mais do que uma.
</p>
<!-- END RR -->

<p>Em alguns livros, o Gestor de Projecto pode pedir para colocar as notas de rodap&eacute;
   na pr&oacute;pria linha; consulte os <a href="#comments">Coment&aacute;rios
   do Projecto</a> para saber como actuar neste caso.
</p>
<p>Veja a imagem e o texto do <a href="#page_hf">Cabe&ccedil;alho e Rodap&eacute;
   da P&aacute;gina</a> como exemplo de nota de rodap&eacute;.
</p>
<p>Se existir uma nota de rodap&eacute; no fim da p&aacute;gina sem n&uacute;mero associado,
   principalmente se come&ccedil;ar a meio de uma frase ou de uma palavra,
   &eacute; provavelmente a continua&ccedil;&atilde;o de uma outra
   na p&aacute;gina anterior. Deixe-a no final da p&aacute;gina, junto &agrave;s
   outras notas de rodap&eacute;, e coloque-a deste modo:
   <tt>*[Footnote: <font color="red">(texto da nota de rodap&eacute;)</font>]</tt>
   (sem n&uacute;mero ou marca&ccedil;&atilde;o). O <tt>*</tt> indica que
   se trata da continua&ccedil;&atilde;o de uma nota de rodap&eacute;, e chama
   a aten&ccedil;&atilde;o do p&oacute;s-processador.
</p>
<p>Se uma nota de rodap&eacute; continuar na p&aacute;gina seguinte (ou seja,
   quando a p&aacute;gina termina antes da nota de rodap&eacute; acabar),
   deixe-a no final da p&aacute;gina, e coloque um asterisco <tt>*</tt>
   onde terminar, assim: <tt>[Footnote 1: <font color="red">(texto da
   nota de rodap&eacute;)</font>]*</tt>. (O <tt>*</tt> indica que ainda
   n&atilde;o acabou, e chamar&aacute; a aten&ccedil;&atilde;o do
   p&oacute;s-processador, que lhe acrescentar&aacute; a parte que
   falta.)
</p>
<p>Se uma nota de rodap&eacute; come&ccedil;ar ou acabar com uma
   palavra hifenizada, sinalize <b>ambas</b> (a nota de rodap&eacute;
   e a palavra) com <tt>*</tt>. Assim:<br>
   <tt>[Footnote 1: Esta nota de rodap&eacute; e a &uacute;ltima palavra continuam na p&aacute;-*]*</tt><br>
   ou se for no princ&iacute;pio, assim<br>
   <tt>*[Footnote: *gina seguinte.]</tt>.
</p>
<p>Se uma nota de rodap&eacute; &eacute; referenciada no texto e
   n&atilde;o aparece nessa p&aacute;gina, mantenha o n&uacute;mero/marca&ccedil;&atilde;o
   da nota e coloque-a entre par&ecirc;nteses rectos <tt>[]</tt>. Esta
   situa&ccedil;&atilde;o &eacute; frequente em livros cient&iacute;ficos e t&eacute;cnicos, onde
   as notas de rodap&eacute; se agrupam no final de cada cap&iacute;tulo. Ver "Notas" em baixo.
</p>

<table width="100%" border="1"  cellpadding="4" cellspacing="0" align="center" summary="Footnote Examples">
  <tbody>
    <tr>
      <th valign="top" align="left" bgcolor="cornsilk">Texto Original:</th>
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
      <th valign="top" align="left" bgcolor="cornsilk">Formatado Correctamente com Notas de Rodap&eacute; Fora da Linha:</th>
    </tr>
      <tr valign="top">
      <td>
<table summary="" border="0" align="left"><tr><td>
    <tt>The principal persons involved in this argument were Caesar[1], former military</tt><br>
    <tt>leader and Imperator, and the orator Cicero[2]. Both were of the aristocratic</tt><br>
    <tt>(Patrician) class, and were quite wealthy.</tt><br>
    <br>
    <tt>[Footnote 1: Gaius Julius Caesar.]</tt><br>
    <br>
    <tt>[Footnote 2: Marcus Tullius Cicero.]</tt>
</td></tr></table>
      </td>
    </tr>
  </tbody>
</table>

<p>Em alguns livros, as notas de rodap&eacute; encontram-se separadas do texto por uma linha horizontal.
   N&atilde;o a mantenha e deixe uma linha em branco entre o texto e as notas de rodap&eacute;.
   (Ver exemplo em cima.)
</p>
<p><b>Notas</b>. S&atilde;o notas de rodap&eacute; que foram agrupadas no final do
   cap&iacute;tulo ou do livro, em vez de se situarem no final da p&aacute;gina. Estas
   s&atilde;o formatadas da mesma forma que as notas de rodap&eacute;. Se encontar uma
   refer&ecirc;ncia de nota no texto, coloque-a entre <tt>[</tt> e <tt>]</tt>.
   Se formatar uma das p&aacute;ginas finais com texto de notas,
   coloque o texto de cada nota entre <tt>[Footnote #:
   <font color="red">(texto da nota)</font>]</tt>, substituindo
   o s&iacute;mbolo # pelo n&uacute;mero ou letra correspondente.
   Coloque uma linha em branco entre cada nota, para que fiquem em par&aacute;grafos
   separados quando forem trabalhadas em p&oacute;s-processamento.
</p>
<!-- Need an example of Endnotes, maybe? Good idea!-->

<p>As <b>notas de rodap&eacute; em <a href="#poetry">Poesia</a> ou <a href="#tables">Tabelas</a></b>
   devem ter o mesmo tratamento que as outras notas de rodap&eacute;.
   Os formatadores devem sinaliz&aacute;-las e mov&ecirc;-las
   para o final da p&aacute;gina; o p&oacute;s-processador decidir&aacute;
   onde coloc&aacute;-las na formata&ccedil;&atilde;o final.
</p>

<table width="100%" align="center" border="1" cellpadding="4" cellspacing="0" summary="Footnotes">
  <tbody>
    <tr><th align="left" bgcolor="cornsilk">Original de Poesia com Nota de Rodap&eacute;:</th></tr>
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
    <tr><th align="left" bgcolor="cornsilk">Texto Formatado Correctamente:</th></tr>
    <tr>
      <td valign="top">
<table summary="" border="0" align="left"><tr><td>
    <tt>/*<br>
    Mary had a little lamb[1]<br>
    &nbsp;&nbsp;Whose fleece was white as snow<br>
    And everywhere that Mary went<br>
    &nbsp;&nbsp;The lamb was sure to go!<br>
    */<br>
    <br>
    [Footnote 1: This lamb was obviously of the Hampshire breed,<br>
    well known for the pure whiteness of their wool.]<br>
    </tt>
</td></tr></table>
      </td>
    </tr>
  </tbody>
</table>

<h3><a name="para_side">Notas (Sidenotes)</a></h3>
<p>Alguns livros t&ecirc;m pequenas descri&ccedil;&otilde;es
   do par&aacute;grafo ao lado e ao longo do texto.
   Chamam-se notas (sidenotes). Mova as notas para o in&iacute;cio
   do par&aacute;grafo a que pertencem. Uma nota deve ser assinalada com
   <tt>[Sidenote:&nbsp;</tt> e <font color="red">(texto da nota)</font><tt>]</tt>
   a rodear o texto da nota. Formate a nota tal como foi impressa,
   mantendo as quebras de linha, o texto em it&aacute;lico, etc... Deixe uma
   linha em branco depois da nota, de forma a n&atilde;o ficar
   colada com o par&aacute;grafo, quando for trabalhada no p&oacute;s-processamento.
</p>
<p>Se existir mais do que uma por par&aacute;grafo, coloque-as uma(s) depois da(s) outra(s)
   no in&iacute;cio do par&aacute;grafo. Deixe uma linha em branco entre elas.
</p>
<p>Se o par&aacute;grafo come&ccedil;ar na p&aacute;gina anterior,
   coloque a nota no topo da p&aacute;gina e marque-a com <tt>*</tt> para
   que o p&oacute;s-processador perceba que faz parte da
   p&aacute;gina anterior. Assim:  <tt>*[Sidenote: <font color="red">(texto da nota)</font>]</tt>. O
   p&oacute;s-processador ir&aacute; coloc&aacute;-la no local correcto.
</p>
<p>Por vezes, o Gestor de Projecto pede para colocar as notas junto &agrave; frase
   a que pertencem, em vez de ser no in&iacute;cio ou final do par&aacute;grafo. Neste caso,
   n&atilde;o as separe com linhas em branco.
</p>
<!-- END RR -->

  <table width="100%" align="center" border="1" cellpadding="4"
       cellspacing="0" summary="Sidenotes"> <col width="128*">
  <tbody>
    <tr valign="top">
      <th align="left" bgcolor="cornsilk">Imagem de Exemplo:</th>
    </tr>
    <tr valign="top">
      <td width="100%" align="left"><img src="side.png" alt=""
          width="550" height="800"><br>
      </td>
    </tr>
    <tr valign="top">
      <th align="left" bgcolor="cornsilk">Texto Formatado Correctamente:</th>
    </tr>
    <tr valign="top">
      <td width="100%">
<table summary="" border="0" align="left"><tr><td>
    <p><tt>
    *[Sidenote: Burning<br>
    discs<br>
    thrown into<br>
    the air.]<br>
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
    [Sidenote: The Midsummer<br>
    fires in<br>
    Swabia.]<br>
    <br>
    [Sidenote: Omens<br>
    drawn from<br>
    the leaps<br>
    over the<br>
    fires.]<br>
    <br>
    [Sidenote: Burning<br>
    wheels<br>
    rolled<br>
    down hill.]<br>
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
    [Footnote 1: &lt;i&gt;Op. cit.&lt;/i&gt; iv. 1. p. 242. We have<br>
    seen (p. 163) that in the sixteenth<br>
    century these customs and beliefs were<br>
    common in Germany. It is also a<br>
    German superstition that a house which<br>
    contains a brand from the midsummer<br>
    bonfire will not be struck by lightning<br>
    (J. W. Wolf, &lt;i&gt;Beitr&auml;ge zur deutschen<br>
    Mythologie&lt;/i&gt;, i. p. 217, &sect; 185).]<br>
    <br>
    [Footnote 2: J. Boemus, &lt;i&gt;Mores, leges et ritus<br>
    omnium gentium&lt;/i&gt; (Lyons, 1541), p.<br>
    226.]<br>
    <br>
    [Footnote 3: Karl Freiherr von Leoprechting,<br>
    &lt;i&gt;Aus dem Lechrain&lt;/i&gt; (Munich, 1855),<br>
    pp. 181 &lt;i&gt;sqq.&lt;/i&gt;; W. Mannhardt, &lt;i&gt;Der<br>
    Baumkultus&lt;i&gt;, p. 510.]<br>
    <br>
    [Footnote 4: A. Birlinger, &lt;i&gt;Volksth&uuml;mliches aus<br>
    Schwaben&lt;/i&gt; (Freiburg im Breisgau, 1861-1862),<br>
    ii. pp. 96 &lt;i&gt;sqq.&lt;/i&gt;, &sect; 128, pp. 103<br>
    &lt;i&gt;sq.&lt;/i&gt;, &sect; 129; &lt;i&gt;id.&lt;/i&gt;, &lt;i&gt;Aus Schwaben&lt;/i&gt; (Wiesbaden,<br>
    1874), ii. 116-120; E. Meier,<br>
    &lt;i&gt;Deutsche Sagen, Sitten und Gebr&auml;uche<br>
    aus Schwaben&lt;/i&gt; (Stuttgart, 1852), pp.<br>
    423 &lt;i&gt;sqq.&lt;/i&gt;; W. Mannhardt, &lt;i&gt;Der Baumkultus&lt;/i&gt;,<br>
    p. 510.]<br>
    </tt></p>
</td></tr></table>
      </td>
    </tr>
  </tbody>
</table>

<h3><a name="block_qt">Cita&ccedil;&otilde;es</a></h3>
<p>Coloque o texto das cita&ccedil;&otilde;es entre <tt>/#</tt> e
   <tt>#/</tt>. Deixe uma linha em branco entre estes s&iacute;mbolos
   e o restante texto. Estes s&iacute;mbolos asseguram que as cita&ccedil;&otilde;es
   sejam tratadas e formatadas de forma diferente no p&oacute;s-processamento.
</p>
<p>Para al&eacute;m destes s&iacute;mbolos, as cita&ccedil;&otilde;es devem ser
   formatadas como qualquer outro texto o seria.
</p>
<p>As "cita&ccedil;&otilde;es em bloco" s&atilde;o cita&ccedil;&otilde;es mais
   longas (que geralmente se estendem por v&aacute;rias linhas ou mesmo p&aacute;ginas)
   e s&atilde;o frequentemente (mas n&atilde;o sempre) impressas com um avan&ccedil;o
   maior e/ou uma fonte mais pequena, relativamente ao resto do texto.
</p>
<!-- END RR -->

<table width="100%" align="center" border="1" cellpadding="4"
 cellspacing="0" summary="Block Quotation">
  <tbody>
    <tr><th align="left" bgcolor="cornsilk">Imagem de Exemplo:</th></tr>
    <tr align="left">
      <td width="100%" valign="top"> <img src="bquote.png" alt="" width="500" height="475"><br>
      </td>
    </tr>
    <tr><th align="left" bgcolor="cornsilk">Texto Formatado Correctamente:</th></tr>
    <tr>
      <td width="100%" valign="top">
<table summary="" border="0" align="left"><tr><td>
    <p><tt>later day was welcomed in their home on the Hudson.<br>
    Dr. Bakewell's contribution was as follows:[24]</tt></p>
    <p><tt>/#<br>
    The uncertainty as to the place of Audubon's birth has been<br>
    put to rest by the testimony of an eye witness in the person<br>
    of old Mandeville Marigny now dead some years. His repeated<br>
    statement to me was, that on his plantation at Mandeville,<br>
    Louisiana, on Lake Ponchartrain, Audubon's mother was<br>
    his guest; and while there gave birth to John James Audubon.<br>
    Marigny was present at the time, and from his own lips, I have,<br>
    as already said, repeatedly heard him assert the above fact.<br>
    He was ever proud to bear this testimony of his protection<br>
    given to Audubon's mother, and his ability to bear witness as<br>
    to the place of Audubon's birth, thus establishing the fact that<br>
    he was a Louisianian by birth.<br>
    #/<br></tt>
    </p>
    <p><tt>We do not doubt the candor and sincerity of the<br>
    excellent Dr. Bakewell, but are bound to say that the<br>
    incidents as related above betray a striking lapse of<br>
    </tt></p>
</td></tr></table>
      </td>
    </tr>
  </tbody>
</table>

<h3><a name="mult_col">V&aacute;rias Colunas</a></h3>
<p>Formate o texto impresso em duas colunas, como se de uma &uacute;nica se tratasse.
</p>
<p>Texto de v&aacute;rias colunas deve ser formatado como uma coluna s&oacute;,
   colocando o texto da esquerda em primeiro lugar, depois o da seguinte e por a&iacute; adiante.
   N&atilde;o &eacute; necess&aacute;rio marcar o local onde as colunas estavam divididas: junte-as.
</p>
<p>Se as colunas s&atilde;o listas de itens, marque o in&iacute;cio da lista com <tt>/*</tt>
   e o fim com <tt>*/</tt> para que as linhas n&atilde;o sejam alteradas
   durante o p&oacute;s-processamento. Deixe uma linha em branco entre estas
   marca&ccedil;&otilde;es e o resto do texto.
</p>
<p>Consulte tamb&eacute;m as sec&ccedil;&otilde;es <a href="#bk_index">&Iacute;ndices</a>,
   <a href="#lists">Lista de Itens</a> e <a href="#tables">Tabelas</a> destas Regras.
</p>

<h3><a name="lists">Lista de Itens</a></h3>
<p>Coloque as listas entre os s&iacute;mbolos <tt>/*</tt> e <tt>*/</tt>.
   Deixe uma linha em branco entre estes s&iacute;mbolos e o resto do texto.
   Estes s&iacute;mbolos asseguram que as linhas se mantenham ao serem tratadas
   no p&oacute;s-processamento. Utilize este tipo de sinaliza&ccedil;&atilde;o
   para qualquer tipo de lista que n&atilde;o deva ser reformatada, incluindo
   listas de perguntas e respostas, ingredientes de uma receita, etc.
</p>
<!-- END RR -->

<table width="100%" align="center" border="1" cellpadding="4" cellspacing="0" summary="List">
  <tbody>
    <tr><th align="left" bgcolor="cornsilk">Texto Original:</th></tr>
    <tr>
      <td valign="top">
<table summary="" border="0" align="left"><tr><td>
<pre>
Andersen, Hans Christian   Daguerre, Louis J. M.    Melville, Herman
Bach, Johann Sebastian     Darwin, Charles          Newton, Isaac
Balboa, Vasco Nunez de     Descartes, Ren&eacute;          Pasteur, Louis
Bierce, Ambrose            Earhart, Amelia          Poe, Edgar Allan
Carroll, Lewis             Einstein, Albert         Ponce de Leon, Juan
Churchill, Winston         Freud, Sigmund           Pulitzer, Joseph
Columbus, Christopher      Lewis, Sinclair          Shakespeare, William
Curie, Marie               Magellan, Ferdinand      Tesla, Nikola
</pre>
</td></tr></table>
      </td>
    </tr>
    <tr><th align="left" bgcolor="cornsilk">Texto Formatado Correctamente:</th></tr>
    <tr>
      <td valign="top">
<table summary="" border="0" align="left"><tr><td>
    <tt>
    /*<br>
    Andersen, Hans Christian<br>
    Bach, Johann Sebastian<br>
    Balboa, Vasco Nunez de<br>
    Bierce, Ambrose<br>
    Carroll, Lewis<br>
    Churchill, Winston<br>
    Columbus, Christopher<br>
    Curie, Marie<br>
    Daguerre, Louis J. M.<br>
    Darwin, Charles<br>
    Descartes, Ren&eacute;<br>
    Earhart, Amelia<br>
    Einstein, Albert<br>
    Freud, Sigmund<br>
    Lewis, Sinclair<br>
    Magellan, Ferdinand<br>
    Melville, Herman<br>
    Newton, Isaac<br>
    Pasteur, Louis<br>
    Poe, Edgar Allan<br>
    Ponce de Leon, Juan<br>
    Pulitzer, Joseph<br>
    Shakespeare, William<br>
    Tesla, Nikola<br>
    */
    </tt>
</td></tr></table>
      </td>
    </tr>
  </tbody>
</table>

<h3><a name="tables">Tabelas</a></h3>
<p>Coloque as tabelas entre <tt>/*</tt> e <tt>*/</tt>. Deixe uma linha
   em branco entre estes s&iacute;mbolos e o resto do texto. Estes
   s&iacute;mbolos asseguram que as linhas se mantenham ao serem tratadas
   no p&oacute;s-processamento. Formate a tabela com espa&ccedil;os para se
   assemelhar &agrave; tabela original. N&atilde;o fa&ccedil;a uma tabela
   com mais de 75 caracteres. O Projecto Gutenberg acrescenta "...excepto quando
   n&atilde;o for poss&iacute;vel. Nunca deve ter, no entanto, mais do que 80...".
</p>
<p>N&atilde;o tente alinhar o texto (com tabs)&mdash;utilize apenas os espa&ccedil;os.
   Os caracteres tabs s&atilde;o lidos de forma diferente de computador para computador, e a sua
   formata&ccedil;&atilde;o n&atilde;o ser&aacute; apresentada da mesma forma.
</p>
<p>&Eacute; extremamente dif&iacute;cil formatar tabelas em texto plain ASCII; d&ecirc; o seu melhor.
   A tarefa ser&aacute; facilitada se usar uma fonte mono-espa&ccedil;ada como a
   <a href="font_sample.php">DPCustomMono</a> ou Courier. Lembre-se que o objectivo
   &eacute; preservar o que o autor quis transmitir, quando estiver a formatar
   uma tabela leg&iacute;vel num e-book. Por vezes, isso implica sacrificar o formato
   original da tabela da p&aacute;gina impressa. Consulte os <a href="#comments">Coment&aacute;rios
   do Projecto</a> e o seu f&oacute;rum, porque pode ter sido definido um determinado
   formato. Se n&atilde;o existir a&iacute; nenhuma refer&ecirc;ncia, pode
   encontrar algumas dicas &uacute;teis na <a href="<? echo $Gallery_of_Table_Layouts_URL; ?>">Galeria
   de Formata&ccedil;&atilde;o de Tabelas</a>.
</p>
<p>As <b>notas de rodap&eacute;</b> de tabelas devem estar no fim da tabela.
   Para mais informa&ccedil;&otilde;es, consulte as <a href="#footnotes">notas de rodap&eacute;</a>.
</p>
<!-- END RR -->
<table width="100%" align="center" border="1" cellpadding="4"
 cellspacing="0" summary="Table Example 1">
  <tbody>
    <tr><th align="left" bgcolor="cornsilk">Imagem de Exemplo:</th></tr>
    <tr align="left">
      <td width="100%" valign="top"> <img src="table1.png" alt="" width="500" height="142"><br>
      </td>
    </tr>
    <tr><th align="left" bgcolor="cornsilk">Texto Formatado Correctamente:</th></tr>
    <tr>
      <td width="100%" valign="top">
<table summary="" border="0" align="left"><tr><td>
<pre><tt>/*
Deg. C.   Millimeters of Mercury.    Gasolene.
               Pure Benzene.

 -10&deg;               13.4                 43.5
   0&deg;               26.6                 81.0
 +10&deg;               46.6                132.0
  20&deg;               76.3                203.0
  40&deg;              182.0                301.8
*/</tt></pre>
</td></tr></table>
      </td>
    </tr>
  </tbody>
</table>
<br>
<table width="100%" align="center" border="1" cellpadding="4"
 cellspacing="0" summary="Table Example 2">
  <tbody>
    <tr><th align="left" bgcolor="cornsilk">Imagem de Exemplo:</th></tr>
    <tr align="left">
      <td width="100%" valign="top"> <img src="table2.png" alt="" width="500" height="304"><br>
      </td>
    </tr>
    <tr><th align="left" bgcolor="cornsilk">Texto Formatado Correctamente:</th></tr>
    <tr>
      <td width="100%" valign="top">
<table summary="" border="0" align="left"><tr><td>
<pre><tt>/*
TABLE II.

-----------------------+----+-----++-------------------------+----+------
                       | C  |     ||                         |  C |
Flat strips compared   | o  |     ||                         |  o |
with round wire 30 cm. | p  |Iron.|| Parallel wires 30 cm.   |  p | Iron.
in length.             | p  |     || in length.              |  p |
                       | e  |     ||                         |  e |
                       | r  |     ||                         |  r |
                       | .  |     ||                         |  . |
-----------------------+----+-----++-------------------------+----+------
Wire 1 mm. diameter    | 20 | 100 || Wire 1 mm. diameter     | 20 |  100
-----------------------+----+-----++-------------------------+----+------
        STRIPS.        |    |     ||       SINGLE WIRE.      |    |
0.25 mm. thick, 2 mm.  |    |     ||                         |    |
  wide                 | 15 |  35 || 0.25 mm. diameter       | 16 |   48
Same, 5 mm. wide       | 13 |  20 || Two  similar wires      | 12 |   30
 "   10  "    "        | 11 |  15 || Four    "      "        |  9 |   18
 "   20  "    "        | 10 |  14 || Eight   "      "        |  8 |   10
 "   40  "    "        |  9 |  13 || Sixteen "      "        |  7 |    6
Same strip rolled up in|    |     || Same, 16 wires bound    |    |
  the form of wire     | 17 |  15 ||   close together        | 18 |   12
-----------------------+----+-----++-------------------------+----+------
*/</tt></pre>
</td></tr></table>
      </td>
    </tr>
  </tbody>
</table>


<h3><a name="poetry">Poesia/Epigramas</a></h3>
<p>Esta sec&ccedil;&atilde;o refere-se a um poema ou epigrama
   ocasional existente num livro que n&atilde;o seja de poesia.
   No caso de se tratar de um Livro de Poesia, consulte as
   <a href="doc-poet.php">regras especiais para Livros de Poesia</a>.
</p>
<p>Identifique a poesia e os epigramas tornando-os mais facilmente
   detect&aacute;veis para o p&oacute;s-processador. Insira
   uma linha com <tt>/*</tt> no in&iacute;cio do poema ou epigrama e uma
   outra com <tt>*/</tt> no fim. Deixe uma linha em branco entre estes indicadores e o
   restante texto.
</p>
<p>Mantenha os avan&ccedil;os relativos de uns versos em rela&ccedil;&atilde;o
   aos outros de um poema ou epigrama, adicionando
   2, 4, 6 (ou mais) espa&ccedil;os antes dos versos em causa, assemelhando-os ao original.
</p>
<p>Quando a linha de um verso &eacute; demasiado longa para a p&aacute;gina,
   &eacute; comum ver-se nos livros impressos, a continua&ccedil;&atilde;o
   do verso na linha seguinte, alinhada &agrave; direita. Este tipo de linhas
   de continua&ccedil;&atilde;o deve ser formatado, colocando todo o verso
   na linha de cima. As linhas de continua&ccedil;&atilde;o come&ccedil;am
   geralmente por letra min&uacute;scula. Surgem com um avan&ccedil;o
   incomum relativamente &agrave; estrutura do restante poema.
</p>
<p>Se a poesia aparecer centrada na p&aacute;gina impressa, n&atilde;o tente centrar os versos
   do poema ao formatar o texto. Mova os versos para junto da margem esquerda,
   preservando os avan&ccedil;os relativos dos mesmos.
</p>
<p>As <b>notas de rodap&eacute;</b> de poesia s&atilde;o formatadas da
   mesma forma do que as outras notas de rodap&eacute;. Para mais
   informa&ccedil;&otilde;es, consulte o t&oacute;pico sobre as
   <a href="#footnotes">notas de rodap&eacute;</a>.
</p>
<p>A <b>numera&ccedil;&atilde;o de linhas</b> de poesia deve ser mantida. Coloque-as no final da
   linha, deixando, pelo menos, 6 espa&ccedil;os entre elas e o fim do texto.
   Para mais informa&ccedil;&otilde;es, consulte o t&oacute;pico sobre a
   <a href="#line_no">Numera&ccedil;&atilde;o de Linhas</a>.
</p>
<p>Consulte os <a href="#comments">Coment&aacute;rios do Projecto</a> do livro que estiver a formatar.
   Os livros de poesia t&ecirc;m, frequentemente, instru&ccedil;&otilde;es
   especiais do Gestor de Projecto. N&atilde;o precisar&aacute;
   de seguir todas estas regras de formata&ccedil;&atilde;o para
   livros que, na sua maioria, sejam compostos de poesia.
</p>
<!-- END RR -->

<br>
<table width="100%" align="center" border="1"  cellpadding="4"
       cellspacing="0" summary="Poetry Example">
  <tbody>
    <tr><th align="left" bgcolor="cornsilk">Imagem de Exemplo:</th></tr>
    <tr align="left">
      <th width="100%" valign="top"> <img src="poetry.png" alt=""
          width="500" height="508"> <br>
      </th>
    </tr>
    <tr><th align="left" bgcolor="cornsilk">Texto Formatado Correctamente:</th></tr>
    <tr>
      <td width="100%" valign="top">

<table summary="" border="0" align="left"><tr><td>
<tt>
to the scenery of his own country:<br></tt>
<p><tt>
/*<br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Oh, to be in England<br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Now that April's there,<br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;And whoever wakes in England<br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Sees, some morning, unaware,<br>
That the lowest boughs and the brushwood sheaf<br>
Round the elm-tree bole are in tiny leaf,<br>
While the chaffinch sings on the orchard bough<br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;In England--now!</tt>
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
*/<br></tt>
</p><p><tt>
So it runs; but it is only a momentary memory;<br>
and he knew, when he had done it, and to his</tt>
</p>
</td></tr></table>

      </td>
    </tr>
  </tbody>
</table>

<h3><a name="line_no">Numera&ccedil;&atilde;o de Linhas</a></h3>
<p>Mantenha a numera&ccedil;&atilde;o de linhas. Coloque-a seis espa&ccedil;os
   do lado direito do texto (no final da respectiva linha), mesmo quando se
   encontrem do lado esquerdo no texto/poesia da imagem original.
</p>
<p>A numera&ccedil;&atilde;o de linha s&atilde;o n&uacute;meros colocados junto
   &agrave; margem de cada linha, ou de cada 5 ou 10 linhas, sendo comuns em
   livros de poesia. Uma vez que a poesia n&atilde;o ser&aacute; reformatada numa vers&atilde;o
   e-book, a numera&ccedil;&atilde;o de linha ser&aacute; &uacute;til para os leitores.
</p>
<!-- END RR -->
<!-- We need an example image and text for this. -->

<h3><a name="letter">Cartas/Correspond&ecirc;ncia</a></h3>
<p>Formate as cartas e correspond&ecirc;ncia como <a href="#para_space">par&aacute;grafos</a>.
   Coloque uma linha em branco antes do in&iacute;cio da carta.
   N&atilde;o &eacute; necess&aacute;rio reproduzir os espa&ccedil;os.
</p>
<p>Coloque as linhas do cabe&ccedil;alho e rodap&eacute; (como moradas,
   datas, sauda&ccedil;&otilde;es ou assinaturas) entre <tt>/*</tt> e
   <tt>*/</tt>. Deixe uma linha em branco entre estes s&iacute;mbolos e o resto do
   texto. Estes s&iacute;mbolos asseguram que as linhas que existam entre
   eles sejam tratadas e formatadas de forma diferente no p&oacute;s-processamento.
</p>
<p>N&atilde;o coloque espa&ccedil;os antes do cabe&ccedil;alho ou
   rodap&eacute;, mesmo que existam espa&ccedil;os ou estejam alinhados
   &agrave; direita no original&mdash;coloque-os
   junto &agrave; margem esquerda. O p&oacute;s-processador ir&aacute;
   format&aacute;-las convenientemente.
</p>
<!-- END RR -->

<table width="100%" align="center" border="1"  cellpadding="4"
       cellspacing="0" summary="Letter Example">
  <tbody>
    <tr><th align="left" bgcolor="cornsilk">Imagem de Exemplo:</th></tr>
    <tr align="left">
      <th width="100%" valign="top"> <img src="letter.png" alt=""
          width="500" height="217"> <br>
      </th>
    </tr>
    <tr><th align="left" bgcolor="cornsilk">Texto Formatado Correctamente:</th></tr>
    <tr>
      <td width="100%" valign="top">
<table summary="" border="0" align="left"><tr><td>
<p><tt>&lt;i&gt;John James Audubon to Claude Fran&ccedil;ois Rozier&lt;/i&gt;</tt></p>
<p><tt>[Letter No. 1, addressed]</tt></p>
<p><tt>/*<br>
&lt;sc&gt;M. Fr. Rozier&lt;/sc&gt;,<br>
Merchant-Nantes.<br>
&lt;sc&gt;New York&lt;/sc&gt;, &lt;i&gt;10 January, 1807.&lt;/i&gt;</tt></p>
<p><tt>
&lt;sc&gt;Dear Sir:&lt;/sc&gt;<br>
*/</tt></p>
<p><tt>
We have had the pleasure of receiving by the &lt;i&gt;Penelope&lt;/i&gt; your<br>
consignment of 20 pieces of linen cloth, for which we send our<br>
thanks. As soon as we have sold them, we shall take great<br>
pleasure in making our return.</tt>
</p>
</td></tr></table>
      </td>
    </tr>
  </tbody>
</table>


<h3><a name="blank_pg">P&aacute;gina em Branco</a></h3>
<p>Se a p&aacute;gina n&atilde;o tiver nem imagens nem texto, formate-a como <tt>[Blank Page]</tt>.
</p>
<p>Se houver algum texto para formatar na &aacute;rea de texto, ou se tiver uma imagem
   sem texto, siga as instru&ccedil;&otilde;es descritas em <a href="#bad_image">Imagens Danificadas</a>
   ou <a href="#bad_text">Imagem N&atilde;o Corresponde ao Texto</a>.
</p>

<h3><a name="title_pg">P&aacute;gina de Rosto (Frente e Verso)</a></h3>
<p>Formate todo o texto, tal como foi impresso na p&aacute;gina, mesmo que
   esteja tudo em mai&uacute;sculas, letras mai&uacute;sculas e min&uacute;sculas,
   etc., incluindo os anos de publica&ccedil;&atilde;o ou direito de autor.
</p>
<p>Nos livros mais antigos a primeira letra &eacute; geralmente representada
   por uma imagem grande e ornamentada&mdash;formate como se estivesse apenas a letra.
</p>
<!-- END RR -->

<table width="100%" align="center" border="1" cellpadding="4"
 cellspacing="0" summary="Title Page Example">
  <tbody>
    <tr>
      <th align="left" bgcolor="cornsilk">
      Imagem de Exemplo:
      </th>
    </tr>
    <tr align="left">
      <td width="100%" valign="top"><img src="title.png" width="500"
          height="520" alt="title page image"><br>
      </td>
    </tr>
    <tr>
      <th align="left" bgcolor="cornsilk">Texto Formatado Correctamente:</th>
    </tr>
    <tr>
      <td width="100%" valign="top">
<table summary="" border="0" align="left"><tr><td>
      <p><tt>GREEN FANCY</tt>
      </p>
      <p><tt>BY</tt></p>
      <p><tt>GEORGE BARR McCUTCHEON</tt></p>
      <p><tt>AUTHOR OF "GRAUSTARK," "THE HOLLOW OF HER HAND,"<br>
         "THE PRINCE OF GRAUSTARK," ETC.</tt></p>
      <p><tt>&lt;i&gt;WITH FRONTISPIECE BY&lt;/i&gt;<br>
         &lt;i&gt;C. ALLAN GILBERT&lt;/i&gt;</tt></p>
      <p><tt>NEW YORK<br>
         DODD, MEAD AND COMPANY<br>
         1917</tt></p>
</td></tr></table>
      </td>
    </tr>
  </tbody>
</table>

<h3><a name="toc">Tabela de Conte&uacute;dos</a></h3>
<p>Formate a Tabela de Conte&uacute;dos tal como est&aacute; impressa no
   livro, mesmo que esteja tudo em mai&uacute;sculas,
   letras mai&uacute;sculas e min&uacute;sculas, etc., entre os marcadores
   <tt>/*</tt> e <tt>*/</tt>. Deixe uma linha
   em branco entre estes marcadores e o resto do texto. As refer&ecirc;ncias
   a n&uacute;meros de p&aacute;ginas devem ser
  mantidas e colocadas, a pelo menos, seis espa&ccedil;os do fim da linha de texto.
</p>
<p>Remova os pontos ou asteriscos usados para alinhar os n&uacute;meros de p&aacute;gina.
</p>
<!-- END RR -->

<table width="100%" align="center" border="1" cellpadding="4"
 cellspacing="0" summary="TOC">
  <tbody>
    <tr>
      <th align="left" bgcolor="cornsilk">
      Imagem de Exemplo:
      </th>
    </tr>
    <tr align="left">
      <td width="100%" valign="top">
      <p><img src="tablec.png" alt="" width="500" height="650"></p>
      </td>
    </tr>
    <tr>
      <th align="left" bgcolor="cornsilk">Texto Formatado Correctamente:</th>
    </tr>
    <tr>
      <td width="100%" valign="top">
<table summary="" border="0" align="left"><tr><td>
      <p><tt><br><br><br><br>CONTENTS<br><br></tt></p>
      <p><tt>/*<br>
          CHAPTER&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;PAGE<br>
          <br>
          I. &lt;sc&gt;The First Wayfarer and the Second Wayfarer<br>
          Meet and Part on the Highway&lt;/sc&gt;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;1<br>
          <br>
          II. &lt;sc&gt;The First Wayfarer Lays His Pack Aside and<br>
          Falls in with Friends&lt;/sc&gt;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;15<br>
          <br>
          III. &lt;sc&gt;Mr. Rushcroft Dissolves, Mr. Jones Intervenes,<br>
          and Two Men Ride Away&lt;/sc&gt;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;33<br>
          <br>
          IV. &lt;sc&gt;An Extraordinary Chambermaid, a Midnight<br>
          Tragedy, and a Man Who Said "Thank You"&lt;/sc&gt;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;50<br>
          <br>
          V. &lt;sc&gt;The Farm-boy Tells a Ghastly Story, and an<br>
          Irishman Enters&lt;/sc&gt;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;67<br>
          <br>
          VI. &lt;sc&gt;Charity Begins Far from Home, and a Stroll in<br>
          the Wildwood Follows&lt;/sc&gt;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;85<br>
          <br>
          VII. &lt;sc&gt;Spun-gold Hair, Blue Eyes, and Various Encounters&lt;/sc&gt;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;103<br>
          <br>
          VIII. &lt;sc&gt;A Note, Some Fancies, and an Expedition in<br>
          Quest of Facts&lt;/sc&gt;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;120<br>
          <br>
          IX. &lt;sc&gt;The First Wayfarer, the Second Wayfarer, and<br>
          the Spirit of Chivalry Ascendant&lt;/sc&gt;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;134<br>
          <br>
          X. &lt;sc&gt;The Prisoner of Green Fancy, and the Lament of<br>
          Peter the Chauffeur&lt;/sc&gt;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;148<br>
          <br>
          XI. &lt;sc&gt;Mr. Sprouse Abandons Literature at an Early<br>
          Hour in the Morning&lt;/sc&gt;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;167<br>
          <br>
          XII. &lt;sc&gt;The First Wayfarer Accepts an Invitation, and<br>
          Mr. Dillingford Belabors a Proxy&lt;/sc&gt;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;183<br>
          <br>
          XIII. &lt;sc&gt;The Second Wayfarer Receives Two Visitors at<br>
          Midnight&lt;/sc&gt;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;199<br>
          <br>
          XIV. &lt;sc&gt;A Flight, a Stone-cutter's Shed, and a Voice<br>
          Outside&lt;/sc&gt;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;221<br>
          */<br>
      </tt></p>
</td></tr></table>
      </td>
    </tr>
  </tbody>
</table>

<h3><a name="bk_index">&Iacute;ndices</a></h3>
<p>Por favor, mantenha os n&uacute;meros de p&aacute;ginas nas p&aacute;ginas
   de &iacute;ndice. Coloque o &iacute;ndice entre os s&iacute;mbolos <tt>/*</tt> e <tt>*/</tt>,
   deixando uma linha em branco antes do s&iacute;mbolo <tt>/*</tt>. N&atilde;o
   alinhe os n&uacute;meros como surgem na imagem, basta colocar uma v&iacute;rgula
   ou dois pontos e depois a numera&ccedil;&atilde;o das p&aacute;ginas.
</p>
<p>Os &iacute;ndices t&ecirc;m por vezes duas colunas; este espa&ccedil;o
   limitado pode fazer com que os t&oacute;picos fiquem
   divididos e passem para a linha seguinte. Coloque-os na mesma linha.
</p>
<p>Seguindo esta regra, &eacute; aceit&aacute;vel que os &iacute;ndices resultem
   em linhas longas, o que n&atilde;o constitui
   um problema porque ser&atilde;o formatadas automaticamente durante o p&oacute;s-processamento.
</p>
<p>Coloque uma linha em branco entre cada t&oacute;pico no &iacute;ndice.
</p>
<p>No caso de existirem sub-t&oacute;picos, coloque um por linha, com um avan&ccedil;o
   a dois espa&ccedil;os.
</p>
<p>Cada nova sec&ccedil;&atilde;o do &iacute;ndice (A, B, C...) deve ser revista
   como se tratasse de um <a href="#sect_head">t&iacute;tulo de sec&ccedil;&atilde;o</a>
   colocando duas linhas em branco antes.
</p>
<p>Por vezes nos livros antigos, a primeira palavra de cada letra do &iacute;ndice
   &eacute; impressa toda em mai&uacute;scula ou mai&uacute;sculas mais pequenas (small caps);
   formate-a de acordo com o estilo utilizado nos restantes t&oacute;picos do &iacute;ndice.
</p>
<!-- END RR -->

<table width="100%" align="center" border="1"  cellpadding="4" cellspacing="0" summary="Rejoining Index Lines">
  <tbody>
    <tr>
      <th align="left" bgcolor="cornsilk">Texto Digitalizado:</th>
    </tr>
    <tr>
      <td valign="top">
<table summary="" border="0" align="left"><tr><td>
    Elizabeth I, her royal Majesty the<br>
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Queen, 123, 144-155.<br>
    &nbsp;&nbsp;birth of, 145.<br>
    &nbsp;&nbsp;christening, 146-147.<br>
    &nbsp;&nbsp;death and burial, 152.<br>
    <br>
    Ethelred II, the Unready, 33.
</td></tr></table>
      </td>
    </tr>
    <tr>
      <th align="left" bgcolor="cornsilk">Texto Formatado Correctamente: (ap&oacute;s jun&ccedil;&atilde;o de linhas)</th>
    </tr>
    <tr>
      <td valign="top">
<table summary="" border="0" align="left"><tr><td>
    <tt><br>/*<br>
    Elizabeth I, her royal Majesty the Queen, 123, 144-155.<br>
    &nbsp;&nbsp;birth of, 145.<br>
    &nbsp;&nbsp;christening, 146-147.<br>
    &nbsp;&nbsp;death and burial, 152.<br>
    <br>
    Ethelred II, the Unready, 33.<br>
    */</tt>
</td></tr></table>
      </td>
    </tr>
  </tbody>
</table>
<br>
<table width="100%" align="center" border="1"  cellpadding="4" cellspacing="0" summary="Aligning Index Subtopics">
  <tbody>
    <tr><th align="left" bgcolor="cornsilk">Texto Digitalizado:</th></tr>
    <tr>
      <td valign="top">
<table summary="" border="0" align="left"><tr><td>
    Hooker, Jos., maj. gen. U. S. V., 345; assigned<br>
    &nbsp;&nbsp;to command Porter's corps, 350; afterwards,<br>
    &nbsp;&nbsp;McDowell's, 367; in pursuit of Lee, 380;<br>
    &nbsp;&nbsp;at South Mt., 382; unacceptable to Halleck,<br>
    &nbsp;&nbsp;retires from active service, 390.<br>
    <br>
    Hopkins, Henry H., 209; notorious secessionist in<br>
    &nbsp;&nbsp;Kanawha valley, 217; controversy with Gen.<br>
    &nbsp;&nbsp;Cox over escaped slave, 233.<br>
    <br>
    Hosea, Lewis M., 187; capt. on Gen. Wilson's staff, 194.<br>
</td></tr></table>
      </td>
    </tr>
    <tr>
      <th align="left" bgcolor="cornsilk">Texto Formatado Correctamente: (com sub-t&oacute;picos de &iacute;ndice alinhados)</th>
    </tr>
    <tr>
      <td valign="top">
<table summary="" border="0" align="left"><tr><td>
    <tt><br>/*<br>
    Hooker, Jos., maj. gen. U. S. V., 345;<br>
    &nbsp;&nbsp;assigned to command Porter's corps, 350;<br>
    &nbsp;&nbsp;afterwards, McDowell's, 367;<br>
    &nbsp;&nbsp;in pursuit of Lee, 380;<br>
    &nbsp;&nbsp;at South Mt., 382;<br>
    &nbsp;&nbsp;unacceptable to Halleck, retires from active service, 390.<br>
    <br>
    Hopkins, Henry H., 209;<br>
    &nbsp;&nbsp;notorious secessionist in Kanawha valley, 217;<br>
    &nbsp;&nbsp;controversy with Gen. Cox over escaped slave, 233.<br>
    <br>
    Hosea, Lewis M., 187;<br>
    &nbsp;&nbsp;capt. on Gen. Wilson's staff, 194.<br>
    */</tt>
</td></tr></table>
      </td>
    </tr>
  </tbody>
</table>

<h3><a name="play_n">Pe&ccedil;as de Teatro: Nome de Actores/Marca&ccedil;&otilde;es C&eacute;nicas</a></h3>
<p>Para todas as pe&ccedil;as de teatro:</p>
<ul compact>
 <li>Formate a lista de personagens (Dramatis Person&aelig;) como uma <a href="#lists">lista</a> normal.</li>
 <li>Coloque quatro linhas em branco antes do in&iacute;cio de um Acto.</li>
 <li>Coloque duas linhas em branco antes do in&iacute;cio de cada Cena.</li>
 <li>Num di&aacute;logo, cada vez que mudar a personagem ou orador coloque uma
     linha em branco a separ&aacute;-los (uma por par&aacute;grafo).</li>
 <li>Formate os nomes dos actores tal como aparecem no texto original, quer seja em
     <a href="#italics">it&aacute;lico</a>, <a href="#bold">negrito</a> ou em
     <a href="#word_caps">letras mai&uacute;sculas</a>.</li>
 <li>As marca&ccedil;&otilde;es c&eacute;nicas s&atilde;o formatadas tal como surgem
     no texto original.<br>
     Se a marca&ccedil;&atilde;o c&eacute;nica surge isolada numa linha, formate-a
     desta forma; se estiver no final da linha de um di&aacute;logo, deixe-a l&aacute;;
     se estiver alinhada &agrave; direita no fim de uma fala de di&aacute;logo,
     deixe seis espa&ccedil;os entre o di&aacute;logo e a marca&ccedil;&atilde;o c&eacute;nica.<br>
     Frequentemente come&ccedil;am com um par&ecirc;ntese aberto, n&atilde;o
     sendo fechado posteriormente.<br>
     Por favor mantenha-o assim: n&atilde;o feche os par&ecirc;nteses. &Eacute; comum
     encontrar texto em it&aacute;lico entre os par&ecirc;nteses.</li>
</ul>
<p>Para pe&ccedil;as com m&eacute;trica: (Pe&ccedil;as escritas como poesia com rima)</p>
<ul compact>
 <li>Muitas pe&ccedil;as utilizam m&eacute;trica e tal como acontece com a poesia
     n&atilde;o deve ser formatada automaticamente Coloque este tipo de texto entre
     os s&iacute;mbolos <tt>/*</tt> e <tt>*/</tt> como se fosse poesia.
     Se as marca&ccedil;&otilde;es c&eacute;nicas estiverem isoladas numa linha
     pr&oacute;pria, n&atilde;o as coloque entre <tt>/*</tt> e <tt>*/</tt>.
     (as marca&ccedil;&otilde;es c&eacute;nicas n&atilde;o s&atilde;o m&eacute;tricas,
     e por isso podem ser formatadas automaticamente na fase de p&oacute;s-processamento.
     Os s&iacute;mbolos <tt>/*</tt> e <tt>*/</tt>
     impedem a formata&ccedil;&atilde;o autom&aacute;tica do di&aacute;logo m&eacute;trico.)</li>
 <li>Preserve a indexa&ccedil;&atilde;o relativa de um di&aacute;logo, quando uma
     linha com m&eacute;trica &eacute; partilhada por mais do que uma personagem.</li>
 <li>Una as linhas com m&eacute;trica que ficaram divididas devido ao limite no papel,
     como formata a poesia.<br>
     Se se tratar apenas de uma palavra, surge geralmente na linha
     de cima ou de baixo precedida de um par&ecirc;ntese aberto, n&atilde;o ficando
     por isso isolado numa linha.<br>
     Veja o <a href="#play4">exemplo</a>.</li>
</ul>
<p>Por favor, verifique se o Gestor de Projecto n&atilde;o especificou uma
     formata&ccedil;&atilde;o diferente nos <a href="#comments">Coment&aacute;rios do Projecto</a>.
</p>
<!-- END RR -->

<table width="100%" align="center" border="1" cellpadding="4"
 cellspacing="0" summary="Play Example 1">
  <tbody>
    <tr>
      <th align="left" bgcolor="cornsilk">Imagem de Exemplo:</th>
    </tr>
    <tr align="left">
      <td width="100%" valign="top"><img src="play1.png" width="500"
          height="430" alt="title page image"><br>
      </td>
    </tr>
    <tr>
      <th align="left" bgcolor="cornsilk">Texto Formatado Correctamente:</th>
    </tr>
    <tr>
      <td width="100%" valign="top">
<table summary="" border="0" align="left"><tr><td>
<p><tt>/*<br>
Has not his name for nought, he will be trode upon:<br>
What says my Printer now?
</tt></p><p><tt>
&lt;i&gt;Clow.&lt;/i&gt; Here's your last Proof, Sir.<br>
You shall have perfect Books now in a twinkling.
</tt></p><p><tt>
&lt;i&gt;Lap.&lt;/i&gt; These marks are ugly.
</tt></p><p><tt>
&lt;i&gt;Clow.&lt;/i&gt; He says, Sir, they're proper:<br>
Blows should have marks, or else they are nothing worth.
</tt></p><p><tt>
&lt;i&gt;La.&lt;/i&gt; But why a Peel-crow here?
</tt></p><p><tt>
&lt;i&gt;Clow.&lt;/i&gt; I told 'em so Sir:<br>
A scare-crow had been better.
</tt></p><p><tt>
&lt;i&gt;Lap.&lt;/i&gt; How slave? look you, Sir,<br>
Did not I say, this &lt;i&gt;Whirrit&lt;/i&gt;, and this &lt;i&gt;Bob&lt;/i&gt;,<br>
Should be both &lt;i&gt;Pica Roman&lt;/i&gt;.
</tt></p><p><tt>
&lt;i&gt;Clow.&lt;/i&gt; So said I, Sir, both &lt;i&gt;Picked Romans&lt;/i&gt;,<br>
And he has made 'em &lt;i&gt;Welch&lt;/i&gt; Bills,<br>
Indeed I know not what to make on 'em.
</tt></p><p><tt>
&lt;i&gt;Lap.&lt;/i&gt; Hay-day; a &lt;i&gt;Souse&lt;/i&gt;, &lt;i&gt;Italica&lt;/i&gt;?
</tt></p><p><tt>
&lt;i&gt;Clow.&lt;/i&gt; Yes, that may hold, Sir,<br>
&lt;i&gt;Souse&lt;/i&gt; is a &lt;i&gt;bona roba&lt;/i&gt;, so is &lt;i&gt;Flops&lt;/i&gt; too.<br>
*/</tt></p>
</td></tr></table>
      </td>
    </tr>
  </tbody>
</table>
<br>
<table width="100%" align="center" border="1" cellpadding="4"
 cellspacing="0" summary="Play Example 2">
  <tbody>
    <tr>
      <th align="left" bgcolor="cornsilk">Imagem de Exemplo:</th>
    </tr>
    <tr align="left">
      <td width="100%" valign="top"><img src="play2.png" width="500"
          height="680" alt="title page image"><br>
      </td>
    </tr>
    <tr>
      <th align="left" bgcolor="cornsilk">Texto Formatado Correctamente:</th>
    </tr>
    <tr>
      <td width="100%" valign="top">
<table summary="" border="0" align="left"><tr><td>
<p><tt>/*<br>
&lt;sc&gt;Clin.&lt;/sc&gt; And do I hold thee, my Antiphila,<br>
Thou only wish and comfort of my soul!<br>
<br>
&lt;sc&gt;Syrus.&lt;/sc&gt; In, in, for you have made our good man wait. (&lt;i&gt;Exeunt.&lt;/i&gt;<br>
*/<br>
<br>
<br>
<br>
<br>
ACT THE THIRD.<br>
<br>
<br>
&lt;sc&gt;Scene I.&lt;/sc&gt;<br>
<br>
/*<br>
&lt;sc&gt;Chrem.&lt;/sc&gt; 'Tis now just daybreak.--Why delay I then<br>
To call my neighbor forth, and be the first<br>
To tell him of his son's return?--The youth,<br>
I understand, would fain not have it so.<br>
But shall I, when I see this poor old man<br>
Afflict himself so grievously, by silence<br>
Rob him of such an unexpected joy,<br>
When the discov'ry can not hurt the son?<br>
No, I'll not do't; but far as in my pow'r<br>
Assist the father. As my son, I see,<br>
Ministers to th' occasions of his friend,<br>
Associated in counsels, rank, and age,<br>
So we old men should serve each other too.<br>
*/<br>
<br>
<br>
&lt;sc&gt;Scene II.&lt;/sc&gt;<br>
<br>
&lt;i&gt;Enter&lt;/i&gt; &lt;sc&gt;Menedemus.&lt;/sc&gt;<br>
<br>
/*<br>
&lt;sc&gt;Mene.&lt;/sc&gt; (&lt;i&gt;to himself&lt;/i&gt;). Sure I'm by nature form'd for misery<br>
Beyond the rest of humankind, or else<br>
'Tis a false saying, though a common one,<br>
"That time assuages grief." For ev'ry day<br>
My sorrow for the absence of my son<br>
Grows on my mind: the longer he's away,<br>
The more impatiently I wish to see him,<br>
The more pine after him.<br>
<br>
&lt;sc&gt;Chrem.&lt;/sc&gt; But he's come forth. (&lt;i&gt;Seeing&lt;/i&gt; &lt;sc&gt;Menedemus.&lt;/sc&gt;)<br>
Yonder he stands. I'll go and speak with him.<br>
Good-morrow, neighbor! I have news for you;<br>
Such news as you'll be overjoy'd to hear.<br>
*/</tt></p>
</td></tr></table>
      </td>
    </tr>
  </tbody>
</table>
<br>
<a name="play3"><!-- Example --></a>
<table width="100%" align="center" border="1" cellpadding="4"
 cellspacing="0" summary="Play Example 3">
  <tbody>
    <tr>
      <th align="left" bgcolor="cornsilk">Imagem de Exemplo:</th>
    </tr>
    <tr align="left">
      <td width="100%" valign="top"><img src="play3.png" width="504"
          height="206" alt="Plays image"><br>
      </td>
    </tr>
    <tr>
      <th align="left" bgcolor="cornsilk">Texto Formatado Correctamente:</th>
    </tr>
    <tr>
      <td width="100%" valign="top">
<table summary="" border="0" align="left"><tr><td>
<p><tt>[&lt;i&gt;Hernda has come from the grove and moves up to his side&lt;/i&gt;]<br>
<br>
/*<br>
&lt;i&gt;Her.&lt;/i&gt; [&lt;i&gt;Adoringly&lt;/i&gt;] And you the master!<br>
<br>
&lt;i&gt;Hud.&lt;/i&gt; Daughter, you owe my lord Megario<br>
Some pretty thanks.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;[&lt;i&gt;Kisses her cheek&lt;/i&gt;]<br>
<br>
&lt;i&gt;Her.&lt;/i&gt;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;I give them, sir.<br>
*/</tt></p>
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
      <th align="left" bgcolor="cornsilk">Imagem de Exemplo:</th>
    </tr>
    <tr align="left">
      <td width="100%" valign="top"><img src="play4.png" width="502"
          height="98" alt="Plays image"><br>
      </td>
    </tr>
    <tr>
      <th align="left" bgcolor="cornsilk">Texto Formatado Correctamente:</th>
    </tr>
    <tr>
      <td width="100%" valign="top">
<table summary="" border="0" align="left"><tr><td>
<p><tt>/*<br>
&lt;i&gt;Am.&lt;/i&gt; Sure you are fasting;<br>
Or not slept well to night; some dream (&lt;i&gt;Ismena?&lt;/i&gt;)<br>
<br>
&lt;i&gt;Ism.&lt;/i&gt; My dreams are like my thoughts, honest and innocent,<br>
Yours are unhappy; who are these that coast us?<br>
You told me the walk was private.<br>
*/</tt></p>
</td></tr></table>
      </td>
    </tr>
  </tbody>
</table>

<h3><a name="anything">Qualquer outra situa&ccedil;&atilde;o que necessite
   de um tratamento especial ou que suscite d&uacute;vidas</a></h3>
<p>Se ao formatar, encontrar alguma situa&ccedil;&atilde;o que n&atilde;o esteja
   descrita neste documento, e que ache que merece um tratamento especial ou que
   n&atilde;o saiba ao certo como a formatar, coloque a sua quest&atilde;o, anotando
   o n&uacute;mero da imagem .png (p&aacute;gina), no t&oacute;pico de Discuss&atilde;o
   do Projecto (uma liga&ccedil;&atilde;o para o f&oacute;rum espec&iacute;fico do
   projecto, que pode ser encontrada na p&aacute;gina dos <a href="#comments">Coment&aacute;rios
   do Projecto</a>), e coloque uma nota no texto formatado a explicar
   o problema. Esta nota explicar&aacute; o problema ou quest&atilde;o ao pr&oacute;ximo
   formatador ou ao p&oacute;s-processador.
</p>
<p>Coloque a sua nota a seguir a um par&ecirc;ntese recto e dois asteriscos <tt>[**</tt>
   e termine-a fechando o par&ecirc;ntese recto <tt>]</tt>. Esta ac&ccedil;&atilde;o
   far&aacute; a nota sobressair do texto do autor, e alertar&aacute; o p&oacute;s-processador
   para uma compara&ccedil;&atilde;o mais cuidadosa desta parte do texto e da imagem
   correspondente, de forma a resolver o problema. Quaisquer coment&aacute;rios/notas de
   volunt&aacute;rios anteriores <b>devem</b> ser mantidas. Estando de acordo ou n&atilde;o,
   pode manifestar a sua opini&atilde;o sobre a nota deixada, acrescentando-a, mas
   n&atilde;o poder&aacute; apagar o coment&aacute;rio. Se souber de uma fonte que possa
   clarificar a quest&atilde;o, indique-a ao p&oacute;s-processador para que
   ele a possa verificar.
</p>
<p>Se estiver a formatar numa ronda mais avan&ccedil;ada, encontrar uma nota de um
   volunt&aacute;rio de rondas anteriores, e se conseguir resolver a quest&atilde;o,
   por favor, perca um pouco do seu tempo a dar-lhe algum feedback. Para isso clique no
   nome do volunt&aacute;rio que encontra no ecr&atilde; de revis&atilde;o/formata&ccedil;&atilde;o,
   e envie uma mensagem a explicar a forma de resolver quest&otilde;es semelhantes no
   futuro. Por favor, como referimos anteriormente, n&atilde;o remova a nota.
</p>

<h3><a name="prev_notes">Notas de Volunt&aacute;rios Precedentes</a></h3>
<p>Quaisquer notas ou coment&aacute;rios de um volunt&aacute;rio precedente
   <b>n&atilde;o devem</b> ser removidos. Pode concordar ou discordar dessa
   nota/coment&aacute;rio, mas mesmo que saiba a resposta, nunca a/o remova.
   Se souber a fonte que esclarece a quest&atilde;o, por favor cite-a na nota, para
   que o p&oacute;s-processador possa verific&aacute;-la.
</p>
<p>Se estiver a formatar numa ronda mais avan&ccedil;ada, encontrar uma nota de um
   volunt&aacute;rio de rondas anteriores, e se conseguir resolver a quest&atilde;o,
   por favor, perca um pouco do seu tempo a dar-lhe algum feedback. Para isso clique no
   nome do volunt&aacute;rio que encontra no ecr&atilde; de revis&atilde;o/formata&ccedil;&atilde;o,
   e envie uma mensagem a explicar a forma de resolver quest&otilde;es semelhantes no futuro.
   Por favor, como referimos anteriormente, n&atilde;o remova a nota.
</p>
<!-- END RR -->

<br>
<table width="100%" border="0" cellspacing="0" summary="Other Guidelines">
  <tbody>
    <tr>
      <td bgcolor="silver">&nbsp;</td>
    </tr>
  </tbody>
</table>
<br>

<h2><a name="sp_ency"></a>
    <a name="sp_chem"></a>
    <a name="sp_math"></a>
    <a name="sp_poet"></a>
    Regras Espec&iacute;ficas para Livros Especiais</h2>
<p>Estes livros t&ecirc;m regras espec&iacute;ficas, complementares
   &agrave;s regras gerais apresentadas neste documento. Os projectos de livros especiais
   s&atilde;o normalmente dif&iacute;ceis, n&atilde;o sendo recomendados a formatadores
   com pouca experi&ecirc;ncia. S&atilde;o mais apropriados para formatadores experientes,
   ou para pessoas com conhecimentos da &aacute;rea.
</p>
<p>Clique nas liga&ccedil;&otilde;es seguintes, quando necessitar de consultar
   as regras espec&iacute;ficas para os livros especiais:
</p>
<ul compact>
  <li><b><a href="doc-ency.php">Enciclop&iacute;dias</a></b></li>
  <li><b><a href="doc-poet.php">Livros de Poesia</a></b></li>
  <li><b>                       Livros de Qu&iacute;mica  [em constru&ccedil;&atilde;o...]</b></li>
  <li><b>                       Livros de Matem&aacute;tica  [em constru&ccedil;&atilde;o...]</b></li>
</ul>

<table width="100%" border="0" cellspacing="0" summary="Common Problems">
  <tbody>
    <tr>
      <td bgcolor="silver">&nbsp;</td>
    </tr>
  </tbody>
</table>

<h2>Problemas Comuns</h2>

<h3><a name="bad_image">Imagens Danificadas</a></h3>
<p>Se uma imagem estiver danificada (n&atilde;o aparecer no ecr&atilde;, cortada,
   imposs&iacute;vel de ser lida), por favor, escreva uma mensagem
   sobre esta imagem no <a href="#forums">f&oacute;rum</a> dos coment&aacute;rios
   do projecto. N&atilde;o clique em "Return Page to Round" ("Devolver P&aacute;gina
   &agrave; Ronda"); se o fizer, a p&aacute;gina passar&aacute; para outro
   volunt&aacute;rio. Em vez disso, clique em "Report Bad Page" ("Reportar Uma
   P&aacute;gina Danificada") para que esta p&aacute;gina fique de 'quarentena'.
</p>
<p>Tenha em aten&ccedil;&atilde;o de que alguma imagens s&atilde;o muito grandes, sendo normal
   demorar um pouco, principalmente se tiver v&aacute;rias janelas abertas ou se o
   computador que estiver a usar j&aacute; n&atilde;o for recente. Antes de reportar
   esta imagem como sendo danificada, tente clicar na
   linha "Image" no final da p&aacute;gina, para que a imagem surja numa nova janela.
   Se surgir, ent&atilde;o o problema dever&aacute; ser do seu browser ou
   sistema.
</p>
<p>Apesar de uma imagem ser relativamente boa, &eacute; compreens&iacute;vel que faltem as
   duas primeiras linhas no OCR. Por favor escreva a(s) linha(s) em falta. Se faltarem quase todas
   as linhas, rescreva toda a p&aacute;gina (se quiser),
   ou clique em "Return Page to Round" e a p&aacute;gina
   ser&aacute; entregue a outro volunt&aacute;rio. Se existirem v&aacute;rias p&aacute;ginas
   nestas condi&ccedil;&otilde;es, por favor escreva algo sobre o assunto no
   <a href="#forums">f&oacute;rum</a> dos coment&aacute;rios do projecto, de
   forma a alertar o Gestor de Projecto.
</p>

<h3><a name="bad_text">Imagem N&atilde;o Corresponde ao Texto</a></h3>
<p>Se a imagem n&atilde;o corresponder ao texto apresentado, por favor, escreva
   uma mensagem sobre esta imagem no <a href="#forums">f&oacute;rum</a> dos
   coment&aacute;rios do projecto. N&atilde;o clique em "Return Page
   to Round"; se o fizer, a p&aacute;gina passar&aacute; para outro volunt&aacute;rio.
   Em vez disso, clique em "Report Bad Page", para que esta p&aacute;gina fique de 'quarentena'.
</p>

<h3><a name="round1">Erros de Revis&atilde;o ou Formata&ccedil;&atilde;o Precedentes</a></h3>
<p>Se o volunt&aacute;rio precedente cometeu v&aacute;rios erros, ou ignorou v&aacute;rias
   situa&ccedil;&otilde;es, por favor, perca um pouco de tempo e d&ecirc;-lhe
   um Feedback (uma no&ccedil;&atilde;o do que fez) clicando no seu nome
   no ecr&atilde; de revis&atilde;o/formata&ccedil;&atilde;o, e enviando uma
   mensagem privada a explicar como lidar com a situa&ccedil;&atilde;o, para que
   n&atilde;o volte a cometer os mesmos erros.
</p>
<p><em>Por favor, seja simp&aacute;tico! </em> Todos n&oacute;s somos volunt&aacute;rios
   e damos todos o nosso melhor. O objectivo da sua mensagem de feedback deve ser
   inform&aacute;-los da forma correcta de formatar, e n&atilde;o uma cr&iacute;tica.
   D&ecirc; um exemplo espec&iacute;fico do trabalho do volunt&aacute;rio em quest&atilde;o,
   mostrando o que fez e o que deveria ter feito.
</p>
<p>Se o volunt&aacute;rio precedente tiver realizado um trabalho excelente, pode
   enviar-lhe uma mensagem a congratul&aacute;-lo&mdash;especialmente se tiver
   formatado uma p&aacute;gina particularmente dif&iacute;cil.
</p>

<h3><a name="p_errors">Erros de Impress&atilde;o/Ortografia</a></h3>
<p>Corrija todas as palavras que tenham sido lidas de forma incorrecta pelo
   OCR (scannos), mas n&atilde;o fa&ccedil;a correc&ccedil;&otilde;es
   do que lhe pare&ccedil;a erros ortogr&aacute;ficos ou de impress&atilde;o
   na imagem original. Muitos dos textos antigos cont&ecirc;m palavras escritas
   de forma diferente da que usamos actualmente, e
   n&oacute;s preservamos estes dizeres antigos, incluindo caracteres acentuados.
</p>
<p>Se tiver d&uacute;vidas, coloque uma nota no tetxo <tt>[**typo deveria ser
   texto?]</tt> e esclare&ccedil;a-se no t&oacute;pico de discuss&atilde;o do Projecto.
   Se alterar algo, inclua uma nota com uma descri&ccedil;&atilde;o do que alterou:
   <tt>[**typo corrigido, alterado de "tetxo" para "texto"]</tt>.
   Inclua dois <tt>**</tt> para chamar a aten&ccedil;&atilde;o do p&oacute;s-processador.
</p>

<h3><a name="f_errors">Erros Factuais no Texto</a></h3>
<p>Por norma, n&atilde;o corrija erros factuais existentes no livro. Muitos dos livros que
   trabalhamos, re&uacute;nem conte&uacute;dos que talvez j&aacute; n&atilde;o sejam
   correctos actualmente. Deixe-os como o autor as escreveu.
</p>
<p>Uma poss&iacute;vel excep&ccedil;&atilde;o &eacute; permitida nos livros
   t&eacute;cnicos e cient&iacute;ficos, onde uma f&oacute;rmula ou
   equa&ccedil;&atilde;o conhecida pode estar incorrecta e (especialmente) se aparecer
   de forma correcta noutras p&aacute;ginas do livro. Alerte o Gestor de Projecto
   para este facto, atrav&eacute;s de uma mensagem no <a href="#forums">F&oacute;rum</a>,
   ou adicionando <tt>[**note sic explique-a-sua-quest&atilde;o]</tt> no local onde verifica tal erro.
</p>

<h3><a name="uncertain">Termos Desconhecidos</a></h3>
<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;[...em constru&ccedil;&atilde;o...]
</p>

<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="silver" summary="Links">
<tr>
  <td width="10">&nbsp;</td>
  <td width="100%" align="center"><font face="verdana, helvetica, sans-serif" size="1">
     Voltar para:
     <a href="..">P&aacute;gina Principal do Distributed Proofreaders</a>,
     &nbsp;&nbsp;&nbsp;
     <a href="faq_central.php">P&aacute;gina do Centro de FAQ's</a>,
     &nbsp;&nbsp;&nbsp;
     <a href="<? echo $PG_home_url; ?>">P&aacute;gina Principal do Projecto Gutenberg</a>.
     </font>
  </td>
</tr>
</table>

<?
theme('','footer');
?>
