<?php

// Translated by user 'rfarinha' at pgdp.net, 3/7/2009

$relPath='../../pinc/';
include_once($relPath.'base.inc');
include_once($relPath.'faq.inc');
include_once($relPath.'pg.inc');
include_once($relPath.'theme.inc');

maybe_redirect_to_external_faq("pt");

$theme_args["css_data"] = "p.backtotop {text-align:right; font-size:75%;margin-right:-5%;}";

output_header('Regras de Formata&ccedil;&atilde;o', NO_STATSBAR, $theme_args);

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

<h1 align="center"><a name="top">Regras de Formata&ccedil;&atilde;o</a></h1>

<h3 align="center">Vers&atilde;o 2.0, publicada a 7 de Junho de 2009 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
  <a href="../dochist.php"><font size="-1">(Hist&oacute;rico)</font></a></h3>

<p>Regras de Formata&ccedil;&atilde;o <a href="../formatting_guidelines.php">em Ingl&ecirc;s</a> /
      Formatting Guidelines <a href="../formatting_guidelines.php">in English</a><br>
    Regras de Formata&ccedil;&atilde;o <a href="../fr/formatting_guidelines.php">em Franc&ecirc;s</a> /
      Directives de Formatage <a href="../fr/formatting_guidelines.php">en fran&ccedil;ais</a><br>
    Regras de Formata&ccedil;&atilde;o <a href="../nl/formatting_guidelines.php">em Holand&ecirc;s</a> /
      Formatteer-Richtlijnen <a href="../nl/formatting_guidelines.php">in het Nederlands</a><br>
    Regras de Formata&ccedil;&atilde;o <a href="../de/formatting_guidelines.php">em Alem&atilde;o</a> /
      Formatierungsrichtlinien <a href="../de/formatting_guidelines.php">auf Deutsch</a><br>
    Regras de Formata&ccedil;&atilde;o <a href="../it/formatting_guidelines.php">em Italiano</a> /
      Regole di Formattazione <a href="../it/formatting_guidelines.php">in Italiano</a><br>
</p>

<p>Fa&ccedil;a o <a href="../../quiz/start.php?show_only=FQ">Teste de Formata&ccedil;&atilde;o</a>!
</p>

<table border="0" cellspacing="0" width="100%" summary="Tabela de Conte&uacute;dos">
  <tbody>
  <tr>
    <td width="1" bgcolor="silver">&nbsp;</td>
    <td bgcolor="silver" align="center"><h2 style="margin-top:0; margin-bottom:0;">Tabela de Conte&uacute;dos</h2></td>
  </tr>
  <tr>
    <td width="1" bgcolor="silver">&nbsp;</td>
    <td bgcolor="white" align="left">
    <ul>
      <li><a href="#prime">A Regra B&aacute;sica</a></li>
      <li><a href="#summary">Resumo das Regras</a></li>
      <li><a href="#about">Sobre Este Documento</a></li>
      <li><a href="#separate_pg">Cada P&aacute;gina &eacute; um Elemento</a></li>
      <li><a href="#comments">Coment&aacute;rios do Projecto</a></li>
      <li><a href="#forums">F&oacute;rum/Discuss&atilde;o deste Projecto</a></li>
      <li><a href="#prev_pg">Correc&ccedil;&atilde;o de Erros em P&aacute;ginas Precedentes</a></li>
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
        <li style="margin-top:.25em;"><font size="+1">Formata&ccedil;&atilde;o de Caracteres:</font>
        <ul>
          <li><a href="#inline">Coloca&ccedil;&atilde;o de Sinais de Formata&ccedil;&atilde;o Interna</a></li>
          <li><a href="#italics">Texto em It&aacute;lico</a></li>
          <li><a href="#bold">Texto em Negrito</a></li>
          <li><a href="#underl">Texto Sublinhado</a></li>
          <li><a href="#spaced"><span style="letter-spacing: .2em;">Texto Espa&ccedil;ado</span> (gesperrt)</a></li>
          <li><a href="#font_ch">Altera&ccedil;&otilde;es da Fonte</a></li>
          <li><a href="#small_caps">Palavras em Mai&uacute;sculas Mais Pequenas <span style="font-variant: small-caps">(Small Capitals)</span></a></li>
          <li><a href="#word_caps">Palavras em Mai&uacute;sculas</a></li>
          <li><a href="#font_sz">Altera&ccedil;&otilde;es no Tamanho da Fonte</a></li>
          <li><a href="#extra_sp">Avan&ccedil;os e Espa&ccedil;os Extra Entre Palavras</a></li>
          <li><a href="#supers">Texto Superescrito (Superscripts)</a></li>
          <li><a href="#subscr">Texto Subescrito (Subscripts)</a></li>
          <li><a href="#page_ref">Refer&ecirc;ncias a P&aacute;ginas "(Ver Pag. 123)"</a></li>
        </ul></li>
        <li style="margin-top:.25em;"><font size="+1">Formata&ccedil;&atilde;o de Par&aacute;grafos:</font>
        <ul>
          <li><a href="#chap_head">T&iacute;tulos de Cap&iacute;tulo</a></li>
          <li><a href="#sect_head">T&iacute;tulos de Sec&ccedil;&atilde;o</a></li>
          <li><a href="#maj_div">Outras Divis&otilde;es do Texto</a></li>
          <li><a href="#para_space">Espa&ccedil;o entre Par&aacute;grafos e Avan&ccedil;os</a></li>
          <li><a href="#extra_s">Espa&ccedil;os Extra/Asteriscos/Linhas Entre Par&aacute;grafos</a></li>
          <li><a href="#illust">Figuras</a></li>
          <li><a href="#footnotes">Notas de Rodap&eacute;</a></li>
          <li><a href="#para_side">Notas (Sidenotes)</a></li>
          <li><a href="#outofline">Coloca&ccedil;&atilde;o de Sinais de Formata&ccedil;&atilde;o Externa</a></li>
          <li><a href="#block_qt">Cita&ccedil;&otilde;es</a></li>
          <li><a href="#lists">Lista de Itens</a></li>
          <li><a href="#tables">Tabelas</a></li>
          <li><a href="#poetry">Poesia/Epigramas</a></li>
          <li><a href="#line_no">Numera&ccedil;&atilde;o de Linhas</a></li>
          <li><a href="#letter">Cartas/Correspond&ecirc;ncia</a></li>
          <li><a href="#r_align">Texto Alinhado &agrave; Direita</a></li>
        </ul></li>
        <li style="margin-top:.25em;"><font size="+1">Formata&ccedil;&atilde;o de P&aacute;ginas:</font>
        <ul>
          <li><a href="#blank_pg">P&aacute;gina em Branco</a></li>
          <li><a href="#title_pg">P&aacute;gina de Rosto (Frente e Verso)</a></li>
          <li><a href="#toc">Tabela de Conte&uacute;dos</a></li>
          <li><a href="#bk_index">&Iacute;ndices</a></li>
          <li><a href="#play_n">Pe&ccedil;as de Teatro: Nome de Actores/Marca&ccedil;&otilde;es C&eacute;nicas</a></li>
        </ul></li>
        <li><a href="#anything">Qualquer outra situa&ccedil;&atilde;o que necessite de um tratamento especial ou que suscite d&uacute;vidas</a></li>
        <li><a href="#prev_notes">Notas de Volunt&aacute;rios Precedentes</a></li>
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
        <li style="margin-top:.25em;"><font size="+1">Problemas Comuns:</font>
        <ul>
          <li><a href="#bad_image">Imagens Danificadas</a></li>
          <li><a href="#bad_text">Imagem N&atilde;o Corresponde ao Texto</a></li>
          <li><a href="#round1">Erros de Revis&atilde;o ou Formata&ccedil;&atilde;o Precedente</a></li>
          <li><a href="#p_errors">Erros de Impress&atilde;o/Ortografia</a></li>
          <li><a href="#f_errors">Erros Factuais no Texto</a></li>
        </ul></li>
        <li><a href="#index">&Iacute;ndice Alfab&eacute;tico das Regras</a></li>
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

<h3><a name="prime">A Regra B&aacute;sica</a></h3>
<p><em>"N&atilde;o alterar o que o autor escreveu!"</em>
</p>
<p>O livro electr&oacute;nico definitivo, lido daqui a muitos
   anos no futuro, deve transmitir exactamente o que o autor queria dizer.
   Se o autor escreveu uma palavra de uma forma estranha, deve mant&ecirc;-la assim. Se o autor
   escreveu declara&ccedil;&otilde;es marcadamente tendenciosas ou
   racistas, deve mant&ecirc;-las assim. Se o autor usou v&iacute;rgulas,
   texto superescrito, ou se colocou uma nota de rodap&eacute; em cada tr&ecirc;s palavras,
   deve mant&ecirc;-las respectivamente. N&oacute;s somos revisores, <b>n&atilde;o</b>
   editores. Se algo no texto n&atilde;o corresponder &agrave; imagem, deve alterar
   o texto para que corresponda. (Consulte <a href="#p_errors">Erros de
   Impress&atilde;o/Ortografia</a> para corrigir erros &oacute;bvios de tipografia.)
</p>
<p>Mudamos apenas pequenas conven&ccedil;&otilde;es tipogr&aacute;ficas que
   n&atilde;o afectem o sentido do que o autor escreveu.
   Por exemplo, reposicionamos as legendas das figuras quando necess&aacute;rio,
   para que s&oacute; surjam entre par&aacute;grafos
   (consulte a sec&ccedil;&atilde;o <a href="#illust">Figuras</a>).
   Altera&ccedil;&otilde;es como esta ajuda-nos a criar uma vers&atilde;o do
   livro formatada e consistente (<em>normalizada</em>).
   As regras de formata&ccedil;&atilde;o que seguimos foram especificamente
   pensadas para atingir esse objectivo. Por favor, leia o
   presente documento com esta ideia em mente. Estas regras dizem respeito
   <em>apenas</em> &agrave; formata&ccedil;&atilde;o.
   Os revisores verificaram o conte&uacute;do da imagem, e agora, como formatador
   dever&aacute; ocupar-se da formata&ccedil;&atilde;o do texto, de acordo com a imagem.
</p>
<p>Para ajudar o formatador e o p&oacute;s-processador, preserve as
   quebras de linha.
   Este passo facilitar&aacute; a compara&ccedil;&atilde;o entre o texto e a imagem.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Voltar ao in&iacute;cio</a></p>


<h3><a name="summary">Resumo das Regras</a></h3>
<p>O <a href="../formatting_summary.pdf">Resumo das Regras de Formata&ccedil;&atilde;o</a>
   &eacute; um pequeno documento de duas p&aacute;ginas
   em PDF, que resume os principais pontos das Regras de
   Formata&ccedil;&atilde;o, e que d&aacute; algumas dicas de como
   formatar. Os Formatadores Principiantes
   devem imprimi-lo e mant&ecirc;-lo &agrave; m&atilde;o enquanto est&atilde;o
   a fazer a formata&ccedil;&atilde;o.
</p>
<p>Se precisar de descarregar e instalar um leitor de documentos PDF, pode
   obt&ecirc;-lo gratuitamente a partir da Adobe&reg;
   <a href="http://www.adobe.com/products/acrobat/readstep2.html">aqui</a>.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Voltar ao in&iacute;cio</a></p>


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
   enquanto formatavam. Existe um outro documento para as
   <a href="proofreading_guidelines.php">Regras de Revis&atilde;o</a>.
   Se se confrontar com uma situa&ccedil;&atilde;o para a qual n&atilde;o
   encontra aqui refer&ecirc;ncia, &eacute; prov&aacute;vel que deva ser tratada
   nas rondas de revis&atilde;o e que por conseguinte n&atilde;o seja aqui mencionada.
   Se n&atilde;o tiver a certeza, por favor, coloque a quest&atilde;o na
   <a href="#forums">Discuss&atilde;o do Projecto</a> (Project Discussion).
</p>
<p>Se faltar algum item, se achar que deveria haver uma outra metodologia,
   ou se achar alguma explica&ccedil;&atilde;o vaga, por favor diga-nos.
<?php if($site_url == "http://www.pgdp.net" || $site_url == "http://www.pgdp.org") { ?>
   Se se deparar com um termo desconhecido nestas regras, consulte o
   <a href="http://www.pgdp.net/wiki/DP_Jargon">guia de jarg&atilde;o wiki</a>.
<?php } ?>
   Este documento &eacute; um trabalho em curso. Ajude-nos, dando-nos
   sugest&otilde;es no F&oacute;rum da Documenta&ccedil;&atilde;o
   <a href="<?php echo $Guideline_discussion_URL; ?>">nesta liga&ccedil;&atilde;o</a>.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Voltar ao in&iacute;cio</a></p>


<h3><a name="separate_pg">Cada P&aacute;gina &eacute; um Elemento</a></h3>
<p>Uma vez que cada projecto &eacute; partilhado por v&aacute;rios formatadores,
   cada um a trabalhar em p&aacute;ginas distintas, n&atilde;o existe a certeza
   de que trabalhar&aacute; a p&aacute;gina seguinte do projecto.
   Com isto em mente, certifique-se que abre e fecha todos os s&iacute;mbolos
   de formata&ccedil;&atilde;o em cada p&aacute;gina. Esta ac&ccedil;&atilde;o
   facilitar&aacute; o trabalho do p&oacute;s-processador na jun&ccedil;&atilde;o
   de todas as p&aacute;ginas na cria&ccedil;&atilde;o de um e-book.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Voltar ao in&iacute;cio</a></p>


<h3><a name="comments">Coment&aacute;rios do Projecto</a></h3>
<p>A P&aacute;gina do Projecto &eacute; carregada, quando selecciona um projecto para formatar.
   Nesta p&aacute;gina existe uma sec&ccedil;&atilde;o chamada
   "Project Comments" (Coment&aacute;rios do Projecto)
   que cont&eacute;m informa&ccedil;&atilde;o espec&iacute;fica sobre esse projecto (livro). <b>Leia-a
   antes de come&ccedil;ar a revis&atilde;o!</b> Se o Gestor do Projecto quiser formatar
   o livro de forma diferente da especificada nas Regras, escrever&aacute;
   a&iacute; as suas indica&ccedil;&otilde;es (instru&ccedil;&otilde;es).
   As instru&ccedil;&otilde;es existentes nos Coment&aacute;rios do Projecto <em>t&ecirc;m
   preced&ecirc;ncia</em> sobre estas regras e devem ser seguidas.
   (Este &eacute; tamb&eacute;m o local onde o Gestor de Projecto pode fornecer
   algumas informa&ccedil;&otilde;es sobre o autor ou o livro.)
</p>
<p><em>Por favor, leia tamb&eacute;m o f&oacute;rum (discuss&atilde;o) do projecto</em>:
   o Gestor do Projecto pode esclarecer aqui regras espec&iacute;ficas do
   projecto, e &eacute; muitas vezes utilizado pelos volunt&aacute;rios para avisar os outros volunt&aacute;rios
   de quest&otilde;es recorrentes e para definir a melhor forma de as tratar. (Ver em baixo)
</p>
<p>Na P&aacute;gina do Projecto, a liga&ccedil;&atilde;o 'Images, Pages Proofread,
   &amp; Differences' permite ver
   como &eacute; que outros volunt&aacute;rios fizeram altera&ccedil;&otilde;es.
   <a href="<?php echo $Using_project_details_URL; ?>">Neste F&oacute;rum</a>
   debatem-se diferentes formas de usar este tipo de informa&ccedil;&atilde;o.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Voltar ao in&iacute;cio</a></p>


<h3><a name="f&oacute;rums">F&oacute;rum/Discuss&atilde;o deste Projecto</a></h3>
<p>Na p&aacute;gina do ecr&atilde; de formata&ccedil;&atilde;o
   (P&aacute;gina do Projecto), onde clica para come&ccedil;ar a formatar
   p&aacute;ginas, existe uma linha denominada "F&oacute;rum", com
   uma liga&ccedil;&atilde;o com o nome "Discuss this Project" (se algu&eacute;m j&aacute;
   tiver dado in&iacute;cio &agrave; discuss&atilde;o), ou "Start
   a discussion on this Project" (se ainda ningu&eacute;m o tiver feito).
   Ao clicar nessa liga&ccedil;&atilde;o, ir&aacute; para um t&oacute;pico (thread) do
   f&oacute;rum de projectos, dedicado a esse projecto espec&iacute;fico.
   Esse &eacute; o local onde deve colocar as suas d&uacute;vidas
   sobre o livro, informar o Gestor de Projecto de problemas, etc.
   Utilizar este t&oacute;pico do f&oacute;rum
   &eacute; a forma recomendada de comunica&ccedil;&atilde;o com o Gestor de Projecto e com
   outros volunt&aacute;rios que trabalham nesse projecto.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Voltar ao in&iacute;cio</a></p>


<h3><a name="prev_pg">Correc&ccedil;&atilde;o de Erros em P&aacute;ginas Precedentes</a></h3>
<p>A <a href="#comments">P&aacute;gina do Projecto</a> cont&eacute;m
   liga&ccedil;&otilde;es para p&aacute;ginas desse projecto em que
   trabalhou recentemente. (Se n&atilde;o reviu ainda nenhuma p&aacute;gina, n&atilde;o ter&aacute;
   nenhuma liga&ccedil;&atilde;o.)
</p>
<p>As p&aacute;ginas listadas por baixo de "DONE"
   ou "IN PROGRESS" est&atilde;o dispon&iacute;veis para
   edi&ccedil;&atilde;o ou para conclus&atilde;o de formata&ccedil;&atilde;o.
   Basta clicar na liga&ccedil;&atilde;o para a p&aacute;gina em quest&atilde;o. Assim, se
   descobrir que se enganou numa p&aacute;gina, ou se marcar algo incorrectamente, pode
   clicar aqui para reabrir a p&aacute;gina e corrigir o erro.
</p>
<p>Pode utilizar as liga&ccedil;&otilde;es "Images, Pages Proofread, &amp;
   Differences" ou "Just My Pages" dispon&iacute;veis
   na <a href="#comments">P&aacute;gina do Projecto</a>. Estas p&aacute;ginas
   disponibilizar&atilde;o uma liga&ccedil;&atilde;o para a edi&ccedil;&atilde;o
   das p&aacute;ginas em que trabalhou e que ainda podem ser corrigidas.
</p>
<p>Para mais informa&ccedil;&otilde;es, consulte os t&oacute;picos
   <a href="../prooffacehelp.php?i_type=0">Standard
   Proofreading Interface Help</a> (Ajuda de Interface Normalizada de Revis&atilde;o)
   ou <a href="../prooffacehelp.php?i_type=1">Enhanced Proofreading
   Interface Help</a> (Ajuda de Interface Melhorada de Revis&atilde;o),
   dependendo da interface que utilizar.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Voltar ao in&iacute;cio</a></p>


<table width="100%" border="0" cellspacing="0" cellpadding="6" summary="Character-level formatting">
  <tbody>
    <tr>
      <td bgcolor="silver"><h2 style="margin-bottom: 0; margin-top: 0;">Formata&ccedil;&atilde;o de Caracteres:</h2></td>
    </tr>
  </tbody>
</table>

<h3><a name="inline">Coloca&ccedil;&atilde;o de Sinais de Formata&ccedil;&atilde;o Interna</a></h3>
<p>Referimo-nos a sinais de formata&ccedil;&atilde;o como <kbd>&lt;i&gt;</kbd>&nbsp;<kbd>&lt;/i&gt;</kbd>,
   <kbd>&lt;b&gt;</kbd>&nbsp;<kbd>&lt;/b&gt;</kbd>, <kbd>&lt;sc&gt;</kbd>&nbsp;<kbd>&lt;/sc&gt;</kbd>,
   <kbd>&lt;f&gt;</kbd>&nbsp;<kbd>&lt;/f&gt;</kbd>, or <kbd>&lt;g&gt;</kbd>&nbsp;<kbd>&lt;/g&gt;</kbd>.
   Coloque a pontua&ccedil;&atilde;o <b>fora</b> destes sinais, excepto se a
   formata&ccedil;&atilde;o se aplicar a toda a frase ou par&aacute;grafo,
   ou se a pontua&ccedil;&atilde;o em si fizer parte da frase,
   t&iacute;tulo ou abreviatura que estiver a formatar.
   Se a formata&ccedil;&atilde;o se prolongar por v&aacute;rios par&aacute;grafos,
   coloque os sinais em cada par&aacute;grafo.
</p>
<p>Os pontos que sinalizam uma palavra abreviada como no
   t&iacute;tulo de um jornal como o <i>Phil. Trans.</i>, fazem parte do
   t&iacute;tulo, devendo, por isso, ser inclu&iacute;dos na formata&ccedil;&atilde;o, assim:
   <kbd>&lt;i&gt;Phil. Trans.&lt;/i&gt;</kbd>.
</p>
<p>Nos livros antigos, os caracteres de impress&atilde;o de n&uacute;meros eram
   muitas vezes os mesmos, n&atilde;o fazendo distin&ccedil;&atilde;o entre texto normal,
   it&aacute;lico ou negrito. Ao formatar datas e express&otilde;es semelhantes,
   formate toda a express&atilde;o com um tipo de sinaliza&ccedil;&atilde;o,
   em vez de formatar as palavras como it&aacute;lico (ou negrito) e excluir os n&uacute;meros.
</p>
<p>Ao formatar um conjunto/lista de palavras ou frases (como nomes, t&iacute;tulos, etc.),
   sinalize cada &iacute;tem da lista individualmente.
</p>
<p>Consulte a sec&ccedil;&atilde;o <a href="#tables">Tabelas</a> para detalhes
   sobre formata&ccedil;&atilde;o em tabelas.
</p>
<!-- END RR -->
<p><b>Exemplos</b>:
</p>
<table width="100%" align="center" border="1" cellpadding="4" cellspacing="0" summary="Inline markup examples">
  <tbody>
    <tr>
      <th valign="top" bgcolor="cornsilk">Imagem Original:</th>
      <th valign="top" bgcolor="cornsilk">Texto Formatado Correctamente:</th>
    </tr>
    <tr>
      <td valign="top"><i>Enacted </i>4<i> July, </i>1776 </td>
      <td valign="top"><kbd>&lt;i&gt;Enacted 4 July, 1776&lt;/i&gt;</kbd> </td>
    </tr>
    <tr>
      <td valign="top">It cost 9<i>l.</i> 4<i>s.</i> 1<i>d.</i></td>
      <td valign="top"><kbd>It cost 9&lt;i&gt;l.&lt;/i&gt; 4&lt;i&gt;s.&lt;/i&gt; 1&lt;i&gt;d.&lt;/i&gt;</kbd></td>
    </tr>
    <tr>
      <td valign="top"><b>God knows what she saw in me!</b> I spoke<br> in such an affected manner.</td>
      <td valign="top"><kbd>&lt;b&gt;God knows what she saw in me!&lt;/b&gt; I spoke<br> in such an affected manner.</kbd></td>
    </tr>
    <tr>
      <td valign="top">As in many other of these <i>Studies</i>, and</td>
      <td valign="top"><kbd>As in many other of these &lt;i&gt;Studies&lt;/i&gt;, and</kbd></td>
    </tr>
    <tr>
      <td valign="top">(<i>Psychological Review</i>, 1898, p. 160)</td>
      <td valign="top"><kbd>(&lt;i&gt;Psychological Review&lt;/i&gt;, 1898, p. 160)</kbd></td>
    </tr>
    <tr>
      <td valign="top">L. Robinson, art. "<span style="font-variant:small-caps;">Ticklishness</span>,"</td>
      <td valign="top"><kbd>L. Robinson, art. "&lt;sc&gt;Ticklishness&lt;/sc&gt;,"</kbd></td>
    </tr>
    <tr>
      <td valign="top" align="right"><i>December</i> 3, <i>morning</i>.<br>
                     1323 Picadilly Circus</td>
      <td valign="top"><kbd>/*<br>
         &lt;i&gt;December 3, morning.&lt;/i&gt;<br>
         1323 Picadilly Circus<br>
         */</kbd></td>
    </tr>
    <tr>
      <td valign="top">
      Volunteers may be tickled pink to read<br>
      <i>Ticklishness</i>, <i>Tickling and Laughter</i>,<br>
      <i>Remarks on Tickling and Laughter</i><br>
      and <i>Ticklishness, Laughter and Humour</i>.
      </td>
      <td valign="top">
      <kbd>Volunteers may be tickled pink to read<br>
      &lt;i&gt;Ticklishness&lt;/i&gt;, &lt;i&gt;Tickling and Laughter&lt;/i&gt;,<br>
      &lt;i&gt;Remarks on Tickling and Laughter&lt;/i&gt;<br>
      and &lt;i&gt;Ticklishness, Laughter and Humour&lt;/i&gt;.</kbd>
      </td>
    </tr>
    <tr>
      <td valign="top">&ldquo;<i>That's the idea!</i>&rdquo; exclaimed Tacks.</td>
      <td valign="top"><kbd>"&lt;i&gt;That's the idea!&lt;/i&gt;" exclaimed Tacks.</kbd></td>
    </tr>
    <tr>
      <td valign="top">The professor set the reading assignment<br>
        for&nbsp; <span style="letter-spacing: .2em;">Erlebnis Geschichte Deutschland<br>
        seit 184</span>5.</td>
      <td valign="top"><kbd>The professor set the reading assignment<br>
        for &lt;g&gt;Erlebnis Geschichte Deutschland<br>
        seit 1845&lt;/g&gt;.</kbd></td>
    </tr>
  </tbody>
</table>
<p class="backtotop"><a href="#top">Voltar ao in&iacute;cio</a></p>


<h3><a name="italics">Texto em It&aacute;lico</a></h3>
<p>Formate o texto em <i>it&aacute;lico</i> colocando-o entre <kbd>&lt;i&gt;</kbd> e
   <kbd>&lt;/i&gt;</kbd>. (N&atilde;o se esque&ccedil;a do "/" ao fechar a tag de
   it&aacute;lico).
</p>
<p>Consulte tamb&eacute;m a sec&ccedil;&atilde;o <a href="#inline">Coloca&ccedil;&atilde;o
   de Sinais de Formata&ccedil;&atilde;o</a>.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Voltar ao in&iacute;cio</a></p>


<h3><a name="bold">Texto em Negrito</a></h3>
<p>Formate o <b>texto em negrito</b> (texto impresso de forma mais carregada) colocando-o
   entre <kbd>&lt;b&gt;</kbd> e <kbd>&lt;/b&gt;</kbd>. (N&atilde;o se
   esque&ccedil;a do "/" ao fechar a tag de negrito).
</p>
<p>Consulte tamb&eacute;m as sec&ccedil;&otilde;es
   <a href="#inline">Coloca&ccedil;&atilde;o de Sinais de Formata&ccedil;&atilde;o</a> e
   <a href="#chap_head">T&iacute;tulos de Cap&iacute;tulo</a>.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Voltar ao in&iacute;cio</a></p>


<h3><a name="underl">Texto Sublinhado</a></h3>
<p>Formate o <u>texto sublinhado</u> como se fosse <a href="#italics">It&aacute;lico</a>,
   ou seja, colocando-o entre <kbd>&lt;i&gt;</kbd> e
   <kbd>&lt;/i&gt;</kbd>. (N&atilde;o se esque&ccedil;a do "/" ao fechar a tag).
   O sublinhado servia muitas vezes para dar &ecirc;nfase quando n&atilde;o
   era poss&iacute;vel escrever o texto em it&aacute;lico, como &eacute;
   o caso dos documentos dactilografados.
</p>
<p>Consulte tamb&eacute;m a sec&ccedil;&atilde;o <a href="#inline">Coloca&ccedil;&atilde;o
   de Sinais de Formata&ccedil;&atilde;o</a>.
</p>
<p>Alguns Gestores de Projecto podem pedir nos <a href="#comments">Coment&aacute;rios do Projecto</a>
   para colocar o texto sublinhado entre <kbd>&lt;u&gt;</kbd> e <kbd>&lt;/u&gt;</kbd>.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Voltar ao in&iacute;cio</a></p>


<h3><a name="spaced"><span style="letter-spacing: .2em;">Texto Espa&ccedil;ado</span> (gesperrt)</a></h3>
<p>Formate o texto&nbsp; <span style="letter-spacing: .2em;">espa&ccedil;ado</span>&nbsp;
   colocando-o entre <kbd>&lt;g&gt;</kbd> e <kbd>&lt;/g&gt;</kbd>.
   (N&atilde;o se esque&ccedil;a do "/" ao fechar
   a tag). Remova os espa&ccedil;os extra entre as letras de cada palavra.
   Esta era uma t&eacute;cnica de impress&atilde;o utilizada para dar &ecirc;nfase
   em alguns livros antigos, principalmente em alem&atilde;o.
</p>
<p>Consulte tamb&eacute;m as sec&ccedil;&otilde;es
   <a href="#inline">Coloca&ccedil;&atilde;o de Sinais de Formata&ccedil;&atilde;o</a>
   e <a href="#chap_head">T&iacute;tulos de Cap&iacute;tulo</a>.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Voltar ao in&iacute;cio</a></p>


<h3><a name="font_ch">Altera&ccedil;&otilde;es da Fonte</a></h3>
<p>Alguns Gestores de Projecto podem solicitar que sinalize
   altera&ccedil;&otilde;es da fonte existentes num par&aacute;grafo
   ou frase de texto regular, colocando-a entre <kbd>&lt;f&gt;</kbd> e
   <kbd>&lt;/f&gt;</kbd>. (N&atilde;o se esque&ccedil;a do "/" ao fechar a tag).
   Utilize esta sinaliza&ccedil;&atilde;o para identificar quaisquer fontes
   especiais ou outra formata&ccedil;&atilde;o, que n&atilde;o tenha
   sinaliza&ccedil;&atilde;o pr&oacute;pria como o it&aacute;lico e o negrito.
</p>
<p>Utiliza&ccedil;&otilde;es poss&iacute;veis desta sinaliza&ccedil;&atilde;o:</p>
<ul compact>
  <li>antiqua (uma variante da fonte roman) dentro de fraktur</li>
  <li>blackletter (fontes "gothic" ou "Old English") dentro de uma sec&ccedil;&atilde;o de texto regular</li>
  <li>fonte maior ou menor se estiver <b>inclu&iacute;da</b> num par&aacute;grafo de fonte regular
    (se for um par&aacute;grafo inteiro num tamanho ou fonte diferente, consulte a
    sec&ccedil;&atilde;o de <a href="#block_qt">cita&ccedil;&otilde;es</a>)</li>
  <li>texto regular inserido num par&aacute;grafo de texto em it&aacute;lico</li>
</ul>
<p>A utiliza&ccedil;&atilde;o desta sinaliza&ccedil;&atilde;o
   num projecto estar&aacute;, geralmente, referida nos
   <a href="#comments">Coment&aacute;rios do Projecto</a>. Os formatadores
   devem colocar uma mensagem na <a href="#forums">Discuss&atilde;o do Projecto</a>
   se acharem necess&aacute;ria uma sinaliza&ccedil;&atilde;o ainda n&atilde;o discutida.
</p>
<p>Consulte tamb&eacute;m a sec&ccedil;&atilde;o <a href="#inline">Coloca&ccedil;&atilde;o
   de Sinais de Formata&ccedil;&atilde;o</a>.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Voltar ao in&iacute;cio</a></p>


<h3><a name="small_caps">Palavras em Mai&uacute;sculas Mais Pequenas <span style="font-variant: small-caps">(Small Capitals)</span></a></h3>
<p>A sinaliza&ccedil;&atilde;o &eacute; diferente nos casos de texto em letra
   mai&uacute;scula maior e menor misturada (<span style="font-variant:small-caps;">Mixed Case Small Caps</span>)
   e <span style="font-variant: small-caps;">texto em mai&uacute;sculas mais pequenas</span>:
</p>
<p>Formate as palavras impressas em letra mai&uacute;scula maior e mais
   pequena (<span style="font-variant: small-caps;">Mixed Small Caps</span>),
   misturando letras mai&uacute;sculas e min&uacute;sculas.
   Formate as palavras impressas em <span style="font-variant: small-caps;">mai&uacute;sculas
   mais pequenas</span> como mai&uacute;sculas. Em ambos os casos, coloque
   o texto entre <kbd>&lt;sc&gt;</kbd> e <kbd>&lt;/sc&gt;</kbd>.
</p>
<p>Os T&iacute;tulos (<a href="#chap_head">T&iacute;tulos de cap&iacute;tulo</a>,
   <a href="#sect_head">T&iacute;tulos de sec&ccedil;&atilde;o</a>, Legendas, etc.)
   podem estar impressos em <span style="font-variant: small-caps;">mai&uacute;sculas
   mais pequenas</span>, mas este facto &eacute; normalmente resultante de
   uma altera&ccedil;&atilde;o do <a href="#font_sz">tamanho da fonte</a>
   e n&atilde;o deve ser sinalizado como small caps. Se a
   <a href="#chap_head">primeira palavra de um cap&iacute;tulo</a> estiver
   em mai&uacute;scula mais pequena, deve ser formatada como mixed small caps, sem as tags.
</p>
<p>Consulte tamb&eacute;m a sec&ccedil;&atilde;o <a href="#inline">Coloca&ccedil;&atilde;o
   de Sinais de Formata&ccedil;&atilde;o</a>.
</p>
<table width="100%" align="center" border="1" cellpadding="4" cellspacing="0" summary="Small caps examples">
  <tbody>
    <tr>
      <th valign="top" bgcolor="cornsilk">Imagem Original:</th>
      <th valign="top" bgcolor="cornsilk">Texto Formatado Correctamente:</th>
    </tr>
    <tr>
      <td valign="top"><span style="font-variant: small-caps;">This is Small Caps</span></td>
      <td valign="top"><kbd>&lt;sc&gt;This is Small Caps&lt;/sc&gt;</kbd></td>
    </tr>
    <tr>
      <td valign="top">You cannot be serious about <span style="font-variant: small-caps;">aardvarks</span>!</td>
      <td valign="top"><kbd>You cannot be serious about &lt;sc&gt;AARDVARKS&lt;/sc&gt;!</kbd></td>
    </tr>
  </tbody>
</table>
<!-- END RR -->
<p class="backtotop"><a href="#top">Voltar ao in&iacute;cio</a></p>


<h3><a name="word_caps">Texto em Mai&uacute;sculas</a></h3>
<p>Formate as palavras impressas em letra mai&uacute;scula como palavras de letra mai&uacute;scula.
</p>
<p>Como excep&ccedil;&atilde;o, temos <a href="#chap_head">primeira palavra
   do texto de um cap&iacute;tulo</a>: muitos livros antigos come&ccedil;am o texto
   de um cap&iacute;tulo com uma palavra toda escrita em mai&uacute;sculas;
   deve mudar para letra mai&uacute;scula e min&uacute;scula, para que "ERA uma vez,"
   se torne "<kbd>Era uma vez,</kbd>".
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Voltar ao in&iacute;cio</a></p>


<h3><a name="font_sz">Altera&ccedil;&otilde;es no Tamanho da Fonte</a></h3>
<p>Geralmente, n&atilde;o fazemos nada relativamente a altera&ccedil;&otilde;es no tamanho da fonte.
   Como excep&ccedil;&atilde;o, temos a altera&ccedil;&atilde;o de tamanho da fonte para evidenciar as
   <a href="#block_qt">cita&ccedil;&otilde;es</a>, ou as altera&ccedil;&otilde;es
   de tamanho da fonte inclu&iacute;da num par&aacute;grafo ou linha de texto
   (consulte <a href="#font_ch">Altera&ccedil;&otilde;es da Fonte</a>).
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Voltar ao in&iacute;cio</a></p>


<h3><a name="extra_sp">Espa&ccedil;os Extra Entre Palavras</a></h3>
<p>Os espa&ccedil;os extra entre palavras s&atilde;o comuns nos resultados
   de OCR. N&atilde;o &eacute; necess&aacute;rio
   remov&ecirc;-los&mdash;estes s&atilde;o facilmente
   automaticamente durante o p&oacute;s-processamento.
   No entanto, <b>&eacute; necess&aacute;rio</b> remover o espa&ccedil;amento
   extra relativo &agrave; pontua&ccedil;&atilde;o, tra&ccedil;os, aspas, etc,
   quando o s&iacute;mbolo surge separado da palavra.
   Remova ainda os espa&ccedil;os extra existentes entre a sinaliza&ccedil;&atilde;o
   <kbd>/* */</kbd> que preserva o espa&ccedil;amento, uma vez que estes
   n&atilde;o ser&atilde;o removidos automaticamente.
</p>
<p>Por fim, se encontrar caracteres de avan&ccedil;o no texto (tab), deve remov&ecirc;-los.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Voltar ao in&iacute;cio</a></p>


<!-- Remove this section after some transition, since it's now all handled in proofreading -->
<h3><a name="supers">Texto Superescrito (Superscripts)</a></h3>
<p>Os livros antigos abreviam frequentemente palavras em contrac&ccedil;&otilde;es, e imprimem-nas como
   texto superescrito. Formate estas contrac&ccedil;&otilde;es inserindo o caracter (<kbd>^</kbd>),
   seguido do texto superescrito. Se o superescrito se mantiver em mais do que um caracter,
   rodeie temb&eacute;m o texto com chavetas <kbd>{</kbd> e <kbd>}</kbd>. Por exemplo:
</p>
<table width="100%" align="center" border="1" cellpadding="4" cellspacing="0" summary="Superscripts example">
  <tbody>
    <tr><th align="left" bgcolor="cornsilk">Imagem Original:</th></tr>
    <tr>
      <td valign="top">Gen<sup>rl</sup> Washington defeated L<sup>d</sup> Cornwall's army.</td>
    </tr>
    <tr><th align="left" bgcolor="cornsilk">Texto Formatado Correctamente:</th></tr>
    <tr>
      <td valign="top"><kbd>Gen^{rl} Washington defeated L^d Cornwall's army.</kbd></td>
    </tr>
  </tbody>
</table>
<p>Se o texto superescrito for uma indica&ccedil;&atilde;o de nota de rodap&eacute;,
   consulte antes a sec&ccedil;&atilde;o <a href="#footnotes">Notas de Rodap&eacute;</a>.
</p>
<p>O Gestor de Projecto pode definir uma forma diferente de formatar o texto superescrito
   nos <a href="#comments">Coment&aacute;rios do Projecto</a>.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Voltar ao in&iacute;cio</a></p>


<!-- Remove this section after some transition, since it's now all handled in proofreading -->
<h3><a name="subscr">Texto Subescrito (Subscripts)</a></h3>
<p>O texto subescrito encontra-se geralmente em trabalhos cient&iacute;ficos,
   mas n&atilde;o &eacute; comum nos outros
   livros. Formate-o colocando um caracter <kbd>_</kbd>, e rodeando o texto
   com chavetas <kbd>{</kbd> e <kbd>}</kbd>. Por exemplo:
</p>
<table width="100%" align="center" border="1" cellpadding="4" cellspacing="0" summary="Subscripts example">
  <tbody>
    <tr><th align="left" bgcolor="cornsilk">Imagem Original:</th></tr>
    <tr>
      <td valign="top">H<sub>2</sub>O.</td>
    </tr>
    <tr><th align="left" bgcolor="cornsilk">Texto Formatado Correctamente:</th></tr>
    <tr>
      <td valign="top"><kbd>H_{2}O.</kbd></td>
    </tr>
  </tbody>
</table>
<!-- END RR -->
<p class="backtotop"><a href="#top">Voltar ao in&iacute;cio</a></p>


<h3><a name="page_ref">Refer&ecirc;ncias a P&aacute;ginas "(Ver Pag. 123)"</a></h3>
<p>Formate as refer&ecirc;ncias a p&aacute;ginas como <kbd>(ver pag. 123)</kbd> tal
   como surgem na imagem.</p>
<p>Consulte os <a href="#comments">Coment&aacute;rios do Projecto</a>,
   verificando se o Gestor de Projecto solicita uma formata&ccedil;&atilde;o
   especial relativa &agrave; refer&ecirc;ncia a p&aacute;ginas.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Voltar ao in&iacute;cio</a></p>


<table width="100%" border="0" cellspacing="0" cellpadding="6" summary="Paragraph-level formatting">
  <tbody>
    <tr>
      <td bgcolor="silver"><h2 style="margin-bottom: 0; margin-top: 0;">Formata&ccedil;&atilde;o de Par&aacute;grafos:</h2></td>
    </tr>
  </tbody>
</table>


<h3><a name="chap_head">T&iacute;tulos de Cap&iacute;tulo</a></h3>
<p>Formate os t&iacute;tulos de cap&iacute;tulo tal como surgem na imagem.
   Um t&iacute;tulo de cap&iacute;tulo pode come&ccedil;ar um pouco
   mais abaixo do que o <a href="#page_hf">cabe&ccedil;alho</a>
   e n&atilde;o tem o n&uacute;mero da p&aacute;gina na mesma linha.
   Os T&iacute;tulos de Cap&iacute;tulo s&atilde;o impressos geralmente
   em letra mai&uacute;scula; se assim for,
   mantenha-os desta forma. Sinalize o texto que estiver em
   <a href="#italics">it&aacute;lico</a> ou em <b>letra mai&uacute;scula maior e
   mais pequena</b> (<a href="#small_caps">small caps</a>) na imagem.
</p>
<p>Insira quatro linhas em branco antes de "CAP&Iacute;TULO XXX". Inclua estas
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
<p>Quando os t&iacute;tulos de cap&iacute;tulo parecerem estar em negrito ou
   ser espa&ccedil;ados, &eacute; muitas vezes devido &agrave; fonte ou a
   altera&ccedil;&otilde;es do tamanho da fonte. <b>Estes n&atilde;o devem ser sinalizados</b>.
   As linhas linhas em branco extra separam o t&iacute;tulo, n&atilde;o devendo por
   isso sinalizar a altera&ccedil;&atilde;o da fonte. Veja o primeiro exemplo abaixo.
</p>
<!-- END RR -->

<table width="100%" align="center" border="1" cellpadding="4" cellspacing="0" summary="Chapter heading example">
 <tbody>
    <tr><th align="left" bgcolor="cornsilk">Imagem Original:</th></tr>
    <tr align="left">
      <td width="100%" valign="top"> <img src="../chap1.png" alt="" width="500" height="725"><br>
      </td>
    </tr>
    <tr><th align="left" bgcolor="cornsilk">Texto Formatado Correctamente:</th></tr>
    <tr>
      <td width="100%" valign="top">
        <kbd>
        <br>
        <br>
        <br>
        <br>
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
        </kbd>
      </td>
    </tr>
  </tbody>
</table>

<br>
<table width="100%" align="center" border="1" cellpadding="4" cellspacing="0" summary="Cap&iacute;tulos">
  <tbody>
    <tr><th align="left" bgcolor="cornsilk">Imagem Original:</th></tr>
    <tr align="left">
      <td width="100%" valign="top"><img src="../foot.png" alt="" width="500" height="860"><br>
      </td>
    </tr>
    <tr><th align="left" bgcolor="cornsilk">Texto Formatado Correctamente:</th></tr>
    <tr>
      <td width="100%" valign="top">
        <kbd>/#<br>In the United States?[A] In a railroad? In a mining company?<br>
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
        </kbd>
      </td>
    </tr>
  </tbody>
</table>
<p class="backtotop"><a href="#top">Voltar ao in&iacute;cio</a></p>


<h3><a name="sect_head">T&iacute;tulos de Sec&ccedil;&atilde;o</a></h3>
<p>Alguns textos subdividem-se em sec&ccedil;&otilde;es dentro dos
   cap&iacute;tulos. Formate estes t&iacute;tulos tal como surgem na imagem.
   Deixe 2 linhas em branco antes do t&iacute;tulo e uma depois, a n&atilde;o
   ser que o Gestor de Projecto d&ecirc; outras indica&ccedil;&otilde;es.
   Se n&atilde;o tem a certeza se se trata de um t&iacute;tulo de sec&ccedil;&atilde;o
   ou de cap&iacute;tulo, coloque a quest&atilde;o na <a href="#forums">Discuss&atilde;o do Projecto</a>,
   indicando o n&uacute;mero da p&aacute;gina.
</p>
<p>Sinalize o texto que estiver em <a href="#italics">it&aacute;lico</a> ou
   em <b>letra mai&uacute;scula maior e mais pequena</b> (<a href="#small_caps">small caps</a>)
   na imagem. Quando os t&iacute;tulos de sec&ccedil;&atilde;o parecerem estar
   em negrito ou ser espa&ccedil;ados, &eacute; muitas vezes devido &agrave; fonte
   ou a altera&ccedil;&otilde;es do tamanho da fonte. <b>Estes n&atilde;o devem ser
   sinalizados</b>. As linhas em branco extra separam o t&iacute;tulo, n&atilde;o devendo
   por isso sinalizar a altera&ccedil;&atilde;o da fonte.
</p>
<!-- END RR -->
<table width="100%" align="center" border="1" cellpadding="4" cellspacing="0" summary="T&iacute;tulos de Sec&ccedil;&atilde;o">
  <tbody>
    <tr>
      <th align="left" bgcolor="cornsilk">Imagem Original:</th>
    </tr>
    <tr align="left">
      <td width="100%" valign="top"> <img src="../section.png" alt="" width="500" height="283"> <br>
      </td>
    </tr>
    <tr>
      <th align="left" bgcolor="cornsilk">Texto Formatado Correctamente:</th>
    </tr>
    <tr valign="top">
      <td>
        <p><kbd>
        and numerous, found in collections of well-authenticated<br>
        specimens. The suggested caution implied<br>
        is not unnecessary, for the periods overlap, and there<br>
        is but little to show when such things as lamps and<br>
        lanterns were actually made.<br>
        <br>
        <br>
        RUSHLIGHTS AND HOLDERS.<br>
        <br>
        In tracing the development of lighting from quite<br>
        homely beginnings, rushlights, prepared by the<br>
        cottager and the farm hand for the winter supply,<br>
        seem to come first on the list. Rushlights, however,</kbd>
        </p>
      </td>
    </tr>
  </tbody>
</table>
<p class="backtotop"><a href="#top">Voltar ao in&iacute;cio</a></p>


<h3><a name="maj_div">Outras Divis&otilde;es do Texto</a></h3>
<p>Subdivis&otilde;es do texto como o Pref&aacute;cio, Tabela de Conte&uacute;dos,
   Introdu&ccedil;&atilde;o, Pr&oacute;logo, Ep&iacute;logo,
   Ap&ecirc;ndices, Refer&ecirc;ncias, Conclus&atilde;o, Gloss&aacute;rio,
   Resumo, Agradecimentos, Bibliografia, etc., devem
   ser formatadas da mesma forma que os <a href="#chap_head">T&iacute;tulos de Cap&iacute;tulo</a>,
   <i>ou seja</i>, 4 linhas em branco antes do t&iacute;tulo e 2 linhas
   em branco antes do in&iacute;cio do texto.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Voltar ao in&iacute;cio</a></p>


<!-- Remove this section after some transition, since it's now all handled in proofreading -->
<h3><a name="para_space">Espa&ccedil;o entre Par&aacute;grafos e Avan&ccedil;os</a></h3>
<p>Coloque uma linha em branco antes do in&iacute;cio dos par&aacute;grafos,
   mesmo quando um par&aacute;grafo come&ccedil;a no topo da p&aacute;gina.
   N&atilde;o deve deixar espa&ccedil;o no in&iacute;cio dos par&aacute;grafos,
   mas se os avan&ccedil;os j&aacute; existirem, n&atilde;o
   &eacute; necess&aacute;rio remover esses espa&ccedil;os&mdash;isso pode
   ser feito automaticamente no p&oacute;s-processamento.
</p>
<p>Veja a imagem/texto da sec&ccedil;&atilde;o <a href="#chap_head">T&iacute;tulos de Cap&iacute;tulo</a> como exemplo.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Voltar ao in&iacute;cio</a></p>


<h3><a name="extra_s">Espa&ccedil;os Extra/Asteriscos/Linhas Entre Par&aacute;grafos</a></h3>
<p>Na imagem, a maioria dos par&aacute;grafos come&ccedil;a na linha imediatamente a seguir. Por vezes
   dois par&aacute;grafos encontram-se separados para indicar uma interrup&ccedil;&atilde;o
   na linha de ideias ("thought break"). Este tipo de interrup&ccedil;&atilde;o pode assumir a
   forma de uma linha de asteriscos, h&iacute;fenes ou outro caracter,
   uma linha horizontal simples ou ornamentada, uma decora&ccedil;&atilde;o simples,
   ou apenas uma ou duas linhas extra em branco.
</p>
<p>Este tipo de interrup&ccedil;&atilde;o pode representar uma mudan&ccedil;a
   de cen&aacute;rio ou assunto, um salto no tempo ou um pouco
   de suspense. &Eacute; uma interrup&ccedil;&atilde;o propositada do autor,
   devendo ser preservada. Formate estas situa&ccedil;&otilde;es, colocando a
   <kbd>&lt;tb&gt;</kbd> e deixando uma linha branco antes e depois.
</p>
<p>Algumas tipografias usavam linhas decorativas no final de cada
   <a href="#chap_head">cap&iacute;tulo</a> ou <a href="#sect_head">sec&ccedil;&atilde;o</a>.
   Estas n&atilde;o s&atilde;o "thought breaks", e <b>n&atilde;o</b> devem
   ser sinalizadas com <kbd>&lt;tb&gt;</kbd>.
</p>
<p>Por favor, consulte os <a href="#comments">Coment&aacute;rios do Projecto</a>.
   O Gestor de Projecto pode solicitar que adicione mais informa&ccedil;&atilde;o
   a este tipo de formata&ccedil;&atilde;o. Por exemplo, inserir
   <kbd>&lt;tb stars&gt;</kbd> para representar uma linha de asteriscos.
</p>
<!-- END RR -->
<table width="100%" align="center" border="1" cellpadding="4" cellspacing="0" summary="Thought Break example">
  <tbody>
    <tr><th align="left" bgcolor="cornsilk">Imagem Original:</th></tr>
    <tr align="left">
      <td width="100%" valign="top"> <img src="../tbreak.png" alt="" width="500" height="249"> <br>
      </td>
    </tr>
    <tr><th align="left" bgcolor="cornsilk">Texto Formatado Correctamente:</th></tr>
    <tr>
      <td width="100%" valign="top">
        <p><kbd>
        last week, but my dressmaker put me off, because she<br>
        was working for Phillis B.'s wedding."</kbd>
        </p>
        <p><kbd>
        We both gave a glance at Hattie. She sat gazing at<br>
        Miss ----, her lips partly open, her eyes moistened,--a<br>
        picture in which delight and incredulity were in pleasant<br>
        strife.</kbd>
        </p>
        <p><kbd>&lt;tb&gt;</kbd>
        </p>
        <p><kbd>
        We have been in the interior a fortnight. One thing<br>
        filled me with astonishment, soon after I came here, namely,<br>
        to find widow ladies and their daughters, all through the</kbd>
        </p>
      </td>
    </tr>
  </tbody>
</table>
<p class="backtotop"><a href="#top">Voltar ao in&iacute;cio</a></p>


<h3><a name="illust">Figuras</a></h3>
<p>O texto relativo a uma figura deve ser assinalado com <kbd>[Illustration:&nbsp;</kbd> e <kbd>]</kbd>
   a rodear o texto da legenda. Formate a nota tal como foi impressa,
   mantendo as quebras de linha, o texto em it&aacute;lico, etc.
   O texto que pode ser (parte de) uma legenda deve ser inclu&iacute;do.
   S&atilde;o exemplos: "Veja a p&aacute;gina 66" ou um t&iacute;tulo dentro da figura.
</p>
<p>Se a figura n&atilde;o tiver legenda, coloque apenas <kbd>[Illustration]</kbd>.
  (Remova o espa&ccedil;o antes do <kbd>]</kbd> neste caso)
</p>
<p>Se a figura estiver no meio ou ao lado de um par&aacute;grafo, mova a
   indica&ccedil;&atilde;o de figura para o in&iacute;cio ou para o final do
   par&aacute;grafo e deixe uma linha em branco para a separar do texto.
   Junte as linhas do par&aacute;grafo,
   removendo as linhas em branco que possam ter ficado ao mover a figura.
</p>
<p>Se n&atilde;o houver mudan&ccedil;a de par&aacute;grafo na p&aacute;gina,
   acrescente um <kbd>*</kbd> &agrave; indica&ccedil;&atilde;o de figura, da
   seguinte forma <kbd>*[Illustration: <font color="red">(texto da legenda)</font>]</kbd>,
   movendo-a para o topo da p&aacute;gina e deixando uma linha em branco depois.
</p>
<!-- END RR -->

<table width="100%" align="center" border="1" cellpadding="4" cellspacing="0" summary="Illustration example">
  <tbody>
    <tr>
      <th align="left" bgcolor="cornsilk">Imagem Original:</th>
    </tr>
    <tr align="left">
      <td width="100%" valign="top"> <img src="../illust.png" alt="" width="500" height="525"> <br>
      </td>
    </tr>
    <tr>
      <th align="left" bgcolor="cornsilk">Texto Formatado Correctamente:</th>
    </tr>
    <tr>
      <td width="100%" valign="top">
        <p><kbd>[Illustration: Martha told him that he had always been her ideal and<br>
        that she worshipped him.<br>
        <br>
        /*<br>
        &lt;i&gt;Frontispiece&lt;/i&gt;<br>
        &lt;i&gt;Her Weight in Gold&lt;/i&gt;<br>
        */<br>
        ]
        </kbd></p>
      </td>
    </tr>
  </tbody>
</table>

<br>
<table width="100%" align="center" border="1" cellpadding="4" cellspacing="0" summary="Illustration in Middle of Paragraph">
  <tbody>
    <tr>
      <th align="left" bgcolor="cornsilk">Imagem Original: (Figura no meio do par&aacute;grafo)</th>
    </tr>
    <tr align="left">
      <td width="100%" valign="top"> <img src="../illust2.png" alt="" width="500" height="514"> <br>
      </td>
    </tr>
    <tr>
      <th align="left" bgcolor="cornsilk">Texto Formatado Correctamente:</th>
    </tr>
    <tr valign="top">
      <td>
        <p><kbd>
        such study are due to Italians. Several of these instruments<br>
        have already been described in this journal, and on the present<br>
        occasion we shall make known a few others that will<br>
        serve to give an idea of the methods employed.<br>
        </kbd></p>
        <p><kbd>[Illustration: &lt;sc&gt;Fig. 1.&lt;/sc&gt;--APPARATUS FOR THE STUDY OF HORIZONTAL<br>
        SEISMIC MOVEMENTS.]</kbd></p>
        <p><kbd>
        For the observation of the vertical and horizontal motions<br>
        of the ground, different apparatus are required. The</kbd>
        </p>
      </td>
    </tr>
  </tbody>
</table>
<p class="backtotop"><a href="#top">Voltar ao in&iacute;cio</a></p>


<h3><a name="footnotes">Notas de Rodap&eacute;</a></h3>
<p>Formate as notas de rodap&eacute;, deixando o texto no fim da p&aacute;gina
   e colocando uma indica&ccedil;&atilde;o no local onde vem referenciada.
   Isto significa que na formata&ccedil;&atilde;o:
</p>
<p>1. No texto principal, o caracter que marca o local da nota de rodap&eacute;, deve
   estar entre par&ecirc;nteses rectos (<kbd>[</kbd> e <kbd>]</kbd>) junto
   &agrave; palavra a que se refere<kbd>[1]</kbd> ou ao seu
   sinal de pontua&ccedil;&atilde;o,<kbd>[2]</kbd> como apresentamos
   na imagem e nos dois exemplos desta frase.
   Os caracteres sinalizadores podem ser n&uacute;meros, letras ou s&iacute;mbolos.
   Quando a nota de rodap&eacute; &eacute; referenciada atrav&eacute;s de
   caracteres especiais (*, &dagger;, &Dagger;, &sect;,
   etc.) substitua-os por letras mai&uacute;sculas por ordem alfab&eacute;tica (A, B, C, etc.).
</p>
<p>2. No final da p&aacute;gina, uma
   nota de rodap&eacute; deve estar assinalada com <kbd>[Footnote #:&nbsp;</kbd> e <kbd>]</kbd>
   a rodear o texto da nota de rodap&eacute;, em que o s&iacute;mbolo # &eacute; substituido
   pelo n&uacute;mero ou letra correspondente. Formate o texto da nota tal como
   foi impresso, mantendo as quebras de linha, o texto em it&aacute;lico, etc.
   Utilize o mesmo caracter que utilizou para a sinalizar no texto.
   Coloque as notas de rodap&eacute; pela ordem em que surgem no texto, com uma linha em branco
   antes de cada uma.
</p>
<!-- END RR -->

<p>Se uma nota de rodap&eacute; estiver incompleta,
   mantenha-a no final da p&aacute;gina, e adicione um asterisco <kbd>*</kbd>
   onde a nota termina. Assim:: <kbd>[Footnote 1: <font color="red">(texto da
   nota de rodap&eacute;)</font>]*</kbd>. O <kbd>*</kbd> chamar&aacute; a
   aten&ccedil;&atilde;o do p&oacute;s-processador, que ir&aacute; juntar
   posteriormente as partes da nota de rodap&eacute;.
</p>
<p>Se uma nota de rodap&eacute; come&ccedil;ou
   na p&aacute;gina anterior, mantenha-a no final de p&aacute;gina e
   coloque-a entre <kbd>*[Footnote: <font color="red">(texto da nota de rodap&eacute;)</font>]</kbd>
   (sem nenhum n&uacute;merou ou sinalizador). O <kbd>*</kbd>
   chamar&aacute; a aten&ccedil;&atilde;o do p&oacute;s-processador, que ir&aacute;
   juntar posteriormente as partes da nota de rodap&eacute;.
</p>
<p>Se uma nota de rodap&eacute; come&ccedil;ar ou acabar com uma
   palavra hifenizada, sinalize <b>ambas</b> (a nota de rodap&eacute;
   e a palavra) com <kbd>*</kbd>. Assim:<br>
   <kbd>[Footnote 1: Esta nota de rodap&eacute; e a &uacute;ltima palavra continuam na p&aacute;-*]*</kbd><br>
   ou se for no princ&iacute;pio, assim<br>
   <kbd>*[Footnote: *gina seguinte.]</kbd>.
</p>
<p>N&atilde;o inclua nenhuma linha horizontal a separar
   as notas de rodap&eacute; do texto principal.
</p>
<p>As <b>Notas</b> s&atilde;o notas de rodap&eacute; que foram agrupadas no final do
   cap&iacute;tulo ou do livro, em vez de se situarem no final da p&aacute;gina. Estas
   s&atilde;o formatadas da mesma forma que as notas de rodap&eacute;. Se encontrar uma
   refer&ecirc;ncia de nota no texto, coloque-a entre <kbd>[</kbd> e <kbd>]</kbd>.
   Se formatar uma das p&aacute;ginas finais com texto de notas,
   coloque o texto de cada nota entre <kbd>[Footnote #:
   <font color="red">(texto da nota)</font>]</kbd>, em que o s&iacute;mbolo # &eacute; substituido
   pelo n&uacute;mero ou letra correspondente.
   Coloque uma linha em branco entre cada nota, para que fiquem em par&aacute;grafos
   separados quando forem trabalhadas em p&oacute;s-processamento.
</p>
<!-- Need an example of Endnotes, maybe? Good idea!-->

<p>Ao formatar as notas de rodap&eacute; em <a href="#tables">Tabelas</a>,
   estas devem ficar onde se encontram representadas na imagem original.
</p>

<table width="100%" border="1" cellpadding="4" cellspacing="0" align="center" summary="Footnote Example">
  <tbody>
    <tr>
      <th valign="top" align="left" bgcolor="cornsilk">Imagem Original:</th>
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
    <tr><th align="left" bgcolor="cornsilk">Texto Formatado Correctamente:</th></tr>
      <tr valign="top">
      <td>
        <kbd>The principal persons involved in this argument were Caesar[A], former military</kbd><br>
        <kbd>leader and Imperator, and the orator Cicero[B]. Both were of the aristocratic</kbd><br>
        <kbd>(Patrician) class, and were quite wealthy.</kbd><br>
        <br>
        <kbd>[Footnote A: Gaius Julius Caesar.]</kbd><br>
        <br>
        <kbd>[Footnote B: Marcus Tullius Cicero.]</kbd>
      </td>
    </tr>
  </tbody>
</table>
<br>
<table width="100%" align="center" border="1" cellpadding="4" cellspacing="0" summary="Footnote example">
  <tbody>
    <tr><th align="left" bgcolor="cornsilk">Poesia Original com Nota de Rodap&eacute;:</th></tr>
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
    <tr><th align="left" bgcolor="cornsilk">Texto Formatado Correctamente:</th></tr>
    <tr>
      <td valign="top">
        <kbd>/*<br>
        Mary had a little lamb[1]<br>
        &nbsp;&nbsp;Whose fleece was white as snow<br>
        And everywhere that Mary went<br>
        &nbsp;&nbsp;The lamb was sure to go!<br>
        */<br>
        <br>
        [Footnote 1: This lamb was obviously of the Hampshire breed,<br>
        well known for the pure whiteness of their wool.]<br>
        </kbd>
      </td>
    </tr>
  </tbody>
</table>
<p class="backtotop"><a href="#top">Voltar ao in&iacute;cio</a></p>


<h3><a name="para_side">Notas (Sidenotes)</a></h3>
<p>Alguns livros t&ecirc;m pequenas descri&ccedil;&otilde;es
   do par&aacute;grafo ao lado e ao longo do texto.
   Chamam-se notas (sidenotes). Mova as notas para o in&iacute;cio
   do par&aacute;grafo a que pertencem. Uma nota deve ser assinalada com
   <kbd>[Sidenote:&nbsp;</kbd> e <kbd>]</kbd>
   a rodear o texto da nota. Formate a nota tal como foi impressa,
   mantendo as quebras de linha, o texto em it&aacute;lico, etc. (formatando a
   hifeniza&ccedil;&atilde;o do final da linha e tra&ccedil;os normalmente). Deixe uma
   linha em branco antes e depois da nota para a separar do texto principal.
</p>
<p>Se existir mais do que uma por par&aacute;grafo, coloque-as uma(s) depois da(s) outra(s)
   no in&iacute;cio do par&aacute;grafo. Deixe uma linha em branco entre elas.
</p>
<p>Se o par&aacute;grafo come&ccedil;ar na p&aacute;gina anterior,
   coloque a nota no topo da p&aacute;gina e marque-a com <kbd>*</kbd> para
   que o p&oacute;s-processador perceba que faz parte da
   p&aacute;gina anterior. Assim: <kbd>*[Sidenote: <font color="red">(texto da nota)</font>]</kbd>. O
   p&oacute;s-processador ir&aacute; coloc&aacute;-la no local correcto.
</p>
<p>Por vezes, o Gestor de Projecto pede para colocar as notas junto &agrave; frase
   a que pertencem, em vez de ser no in&iacute;cio ou final do par&aacute;grafo. Neste caso,
   n&atilde;o as separe com linhas em branco.
</p>
<!-- END RR -->

<table width="100%" align="center" border="1" cellpadding="4" cellspacing="0" summary="Sidenotes example">
  <tbody>
    <tr valign="top">
      <th align="left" bgcolor="cornsilk">Imagem Original:</th>
    </tr>
    <tr valign="top">
      <td width="100%" align="left"><img src="../side.png" alt="" width="550" height="800"><br>
      </td>
    </tr>
    <tr valign="top">
      <th align="left" bgcolor="cornsilk">Texto Formatado Correctamente:</th>
    </tr>
    <tr valign="top">
      <td width="100%">
        <p><kbd>
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
        </kbd></p>
      </td>
    </tr>
  </tbody>
</table>
<p class="backtotop"><a href="#top">Voltar ao in&iacute;cio</a></p>


<h3><a name="outofline">Coloca&ccedil;&atilde;o de Sinais de Formata&ccedil;&atilde;o Externa</a></h3>
<p>Referimo-nos a sinais de formata&ccedil;&atilde;o como <kbd>/#</kbd> <kbd>#/</kbd>
   e <kbd>/*</kbd> <kbd>*/</kbd>. A sinaliza&ccedil;&atilde;o <kbd>/#</kbd> <kbd>#/</kbd>
   indica que o texto foi impresso de forma diferente, mas que pode ser processado automaticamente
   em p&oacute;s-processamento. A sinaliza&ccedil;&atilde;o <kbd>/*</kbd> <kbd>*/</kbd>
   indica que o texto n&atilde;o deve ser processado automaticamente durante o
   p&oacute;s-processamento&mdash;onde as quebras de linha, avan&ccedil;os
   e espa&ccedil;amento t&ecirc;m de ser preservados.
</p>
<p>Certifique-se de que fecha a sinaliza&ccedil;&atilde;o em qualquer p&aacute;gina
   em que a abra. Depois do p&oacute;s-processamento, todos os sinais
   de formata&ccedil;&atilde;o externa ser&atilde;o removidos, bem como a linha
   em que se encontram. Por este motivo, deixe uma linha em branco entre o
   texto regular e o sinal de abertura, assim como uma linha em branco entre o sinal
   de fecho e o texto regular.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Voltar ao in&iacute;cio</a></p>


<h3><a name="block_qt">Cita&ccedil;&otilde;es</a></h3>
<p>As cita&ccedil;&otilde;es em bloco s&atilde;o blocos de texto
   (geralmente ocupam v&aacute;rias linhas e por vezes v&aacute;rias p&aacute;ginas)
   que se distinguem do restante circundante pelas suas margens mais largas,
   pelo uso de fonte mais pequena, por avan&ccedil;os diversos, entre outros elementos.
   Coloque as cita&ccedil;&otilde;es entre <kbd>/#</kbd> e <kbd>#/</kbd>.
   Consulte a sec&ccedil;&atilde;o <a href="#outofline">Coloca&ccedil;&atilde;o
   de Sinais de Formata&ccedil;&atilde;o Externa</a> para obter mais detalhes
   sobre este tipo de sinaliza&ccedil;&atilde;o.
</p>
<p>Exceptuando a adi&ccedil;&atilde;o dos sinalizadores, as cita&ccedil;&otilde;es devem ser
   formatadas como o texto regular.
</p>
<!-- END RR -->

<table width="100%" align="center" border="1" cellpadding="4" cellspacing="0" summary="Block Quotation example">
  <tbody>
    <tr><th align="left" bgcolor="cornsilk">Imagem Original:</th></tr>
    <tr align="left">
      <td width="100%" valign="top"> <img src="../bquote.png" alt="" width="500" height="475"><br>
      </td>
    </tr>
    <tr><th align="left" bgcolor="cornsilk">Texto Formatado Correctamente:</th></tr>
    <tr>
      <td width="100%" valign="top">
        <p><kbd>later day was welcomed in their home on the Hudson.<br>
        Dr. Bakewell's contribution was as follows:[24]</kbd></p>
        <p><kbd>/#<br>
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
        #/<br></kbd>
        </p>
        <p><kbd>We do not doubt the candor and sincerity of the<br>
        excellent Dr. Bakewell, but are bound to say that the<br>
        incidents as related above betray a striking lapse of<br>
        </kbd></p>
      </td>
    </tr>
  </tbody>
</table>
<p class="backtotop"><a href="#top">Voltar ao in&iacute;cio</a></p>


<h3><a name="lists">Lista de Itens</a></h3>
<p>Coloque as listas entre <kbd>/*</kbd> e <kbd>*/</kbd>.
   Consulte a sec&ccedil;&atilde;o <a href="#outofline">Coloca&ccedil;&atilde;o
   de Sinais de Formata&ccedil;&atilde;o Externa</a> para obter mais detalhes
   sobre este tipo de sinaliza&ccedil;&atilde;o.
</p>
<!-- END RR -->

<table width="100%" align="center" border="1" cellpadding="4" cellspacing="0" summary="List example">
  <tbody>
    <tr><th align="left" bgcolor="cornsilk">Imagem Original:</th></tr>
    <tr>
      <td valign="top">
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
      </td>
    </tr>
    <tr><th align="left" bgcolor="cornsilk">Texto Formatado Correctamente:</th></tr>
    <tr>
      <td valign="top">
        <kbd>
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
        </kbd>
      </td>
    </tr>
  </tbody>
</table>
<p class="backtotop"><a href="#top">Voltar ao in&iacute;cio</a></p>


<h3><a name="tables">Tabelas</a></h3>
<p>Coloque as tabelas entre <kbd>/*</kbd> e <kbd>*/</kbd>. Consulte a sec&ccedil;&atilde;o
   <a href="#outofline">Coloca&ccedil;&atilde;o de Sinais de Formata&ccedil;&atilde;o
   Externa</a> para obter mais detalhes sobre este tipo de sinaliza&ccedil;&atilde;o.
   Formate a tabela com espa&ccedil;os (<b>e n&atilde;o avan&ccedil;os</b>) para se
   assemelhar &agrave; tabela original. Tente evitar, sempre que poss&iacute;vel,
   tabelas excessivamente largas; menos de 75 caracteres &eacute; o ideal.
</p>
<p>N&atilde;o utilize avan&ccedil;os para formatar&mdash;utilize apenas caracteres de espa&ccedil;os.
   Os caracteres de avan&ccedil;os s&atilde;o interpretados de forma diferente entre computadores, e a sua
   formata&ccedil;&atilde;o cuidada nem sempre ser&aacute; apresentada da mesma forma.
   Remova quaisquer pontos ou outra pontua&ccedil;&atilde;o (leaders)
   utilizada para alinhar os itens.
</p>
<p>Se for necess&aacute;rio adicionar formata&ccedil;&atilde;o interna
   (it&aacute;lico, negrito, etc.), sinalize cada c&eacute;lula separadamente.
   Ao alinhar o texto, esteja ciente de que a formata&ccedil;&atilde;o interna
   surgir&aacute; de forma diferente na vers&atilde;o final do texto. Por exemplo,
   a <kbd>&lt;i&gt;</kbd>sinaliza&ccedil;&atilde;o de it&aacute;lico<kbd>&lt;/i&gt;</kbd>
   &eacute; normalmente convertida em <kbd>_</kbd>underscores<kbd>_</kbd>,
   e a maioria da formata&ccedil;&atilde;o interna ser&aacute; tratada de forma semelhante.
   No entanto, a <kbd>&lt;sc&gt;</kbd>Sinaliza&ccedil;&atilde;o de Small Caps<kbd>&lt;/sc&gt;</kbd>
   &eacute; removida por completo.
</p>
<p>&Eacute; extremamente dif&iacute;cil formatar tabelas em texto; d&ecirc; o seu melhor.
   A tarefa ser&aacute; facilitada se usar uma fonte mono-espa&ccedil;ada como a
   <a href="../font_sample.php">DPCustomMono</a> ou Courier. Lembre-se que o objectivo
   &eacute; preservar o que o autor quis transmitir, quando estiver a formatar
   uma tabela leg&iacute;vel num e-book. Por vezes, isso implica sacrificar o formato
   original da tabela da p&aacute;gina impressa. Consulte os <a href="#comments">Coment&aacute;rios
   do Projecto</a> e o seu f&oacute;rum, porque pode ter sido definido um determinado
   formato. Se n&atilde;o existir a&iacute; nenhuma refer&ecirc;ncia, pode
   encontrar algumas dicas &uacute;teis na <a href="<?php echo $Gallery_of_Table_Layouts_URL; ?>">Galeria
   de Formata&ccedil;&atilde;o de Tabelas</a>.
</p>
<p>As <b>notas de rodap&eacute;</b> de tabelas devem estar no fim da tabela.
   Para mais informa&ccedil;&otilde;es, consulte a sec&ccedil;&atilde;o <a href="#footnotes">Notas de Rodap&eacute;</a>.
</p>
<!-- END RR -->
<table width="100%" align="center" border="1" cellpadding="4" cellspacing="0" summary="Table Example">
  <tbody>
    <tr><th align="left" bgcolor="cornsilk">Imagem Original:</th></tr>
    <tr align="left">
      <td width="100%" valign="top"> <img src="../table2.png" alt="" width="500" height="304"><br>
      </td>
    </tr>
    <tr><th align="left" bgcolor="cornsilk">Texto Formatado Correctamente:</th></tr>
    <tr>
      <td width="100%" valign="top">
<pre><kbd>
TABLE II.

/*
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
*/</kbd></pre>
      </td>
    </tr>
  </tbody>
</table>
<br>
<table width="100%" align="center" border="1" cellpadding="4" cellspacing="0" summary="Table Example">
  <tbody>
    <tr><th align="left" bgcolor="cornsilk">Imagem Original:</th></tr>
    <tr align="left">
      <td width="100%" valign="top"> <img src="../table3.png" alt="" width="480" height="231"><br>
      </td>
    </tr>
    <tr><th align="left" bgcolor="cornsilk">Texto Formatado Correctamente:</th></tr>
    <tr>
      <td width="100%" valign="top">
<pre><kbd>/*
                        &lt;i&gt;Agents.&lt;/i&gt;      &lt;i&gt;Objects.&lt;/i&gt;
            { 1st person,  I,             me,
            { 2d    "      thou,          thee,
&lt;i&gt;Singular&lt;/i&gt;  { 3d    "  mas. { he,         him,
            {       "  fem. { she,        her,
            {              it,            it.

            { 1st person,  we,            us,
 &lt;i&gt;Plural&lt;/i&gt;   { 2d    "      ye, or you,    you,
            { 3d    "      they,          them,
                           who,           whom.
*/</kbd></pre>
      </td>
    </tr>
  </tbody>
</table>
<p class="backtotop"><a href="#top">Voltar ao in&iacute;cio</a></p>


<h3><a name="poetry">Poesia/Epigramas</a></h3>
<p>Sinalize a poesia ou epigramas com <kbd>/*</kbd> e <kbd>*/</kbd>,
   para que as quebras de linha e o espa&ccedil;amento sejam preservados.
   Consulte a sec&ccedil;&atilde;o <a href="#outofline">Coloca&ccedil;&atilde;o de Sinais de
   Formata&ccedil;&atilde;o Externa</a> para obter mais detalhes sobre este tipo
   de sinaliza&ccedil;&atilde;o.
</p>
<p>Mantenha os avan&ccedil;os relativos de uns versos em rela&ccedil;&atilde;o
   aos outros de um poema ou epigrama, adicionando
   2, 4, 6 (ou mais) espa&ccedil;os antes dos versos em causa, assemelhando-os ao original.
   Se a poesia aparecer centrada na p&aacute;gina impressa, n&atilde;o tente centrar os versos
   do poema ao formatar o texto. Mova os versos para junto da margem esquerda,
   preservando os avan&ccedil;os relativos dos mesmos.
</p>
<p>Quando a linha de um verso &eacute; demasiado longa para a p&aacute;gina,
   &eacute; comum ver-se nos livros impressos, a continua&ccedil;&atilde;o
   do verso na linha seguinte, alinhada &agrave; direita. Este tipo de linhas
   de continua&ccedil;&atilde;o deve ser formatado, colocando todo o verso
   na linha de cima. As linhas de continua&ccedil;&atilde;o come&ccedil;am
   geralmente por letra min&uacute;scula. Surgem com um avan&ccedil;o
   incomum relativamente &agrave; estrutura do restante poema.
</p>
<p>Se existir uma linha de pontos num poema, formate-a como um <a href="#extra_s">thought break</a>.
</p>
<p>A <a href="#line_no">numera&ccedil;&atilde;o de linhas</a>, na poesia, deve ser mantida.
</p>
<p>Consulte os <a href="#comments">Coment&aacute;rios do Projecto</a> do livro que estiver a formatar.
   Os livros de poesia t&ecirc;m, frequentemente, instru&ccedil;&otilde;es
   especiais do Gestor de Projecto. N&atilde;o precisar&aacute;
   de seguir todas estas regras de formata&ccedil;&atilde;o para
   livros que, na sua maioria, sejam compostos por poesia.
</p>
<!-- END RR -->

<table width="100%" align="center" border="1" cellpadding="4" cellspacing="0" summary="Poetry Example">
  <tbody>
    <tr><th align="left" bgcolor="cornsilk">Imagem Original:</th></tr>
    <tr align="left">
      <td width="100%" valign="top"> <img src="../poetry.png" alt="" width="500" height="508"> <br>
      </td>
    </tr>
    <tr><th align="left" bgcolor="cornsilk">Texto Formatado Correctamente:</th></tr>
    <tr>
      <td width="100%" valign="top">
        <kbd>
        to the scenery of his own country:<br></kbd>
        <p><kbd>
        /*<br>
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Oh, to be in England<br>
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Now that April's there,<br>
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;And whoever wakes in England<br>
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Sees, some morning, unaware,<br>
        That the lowest boughs and the brushwood sheaf<br>
        Round the elm-tree bole are in tiny leaf,<br>
        While the chaffinch sings on the orchard bough<br>
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;In England--now!</kbd>
        </p><p><kbd>
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
        */<br></kbd>
        </p><p><kbd>
        So it runs; but it is only a momentary memory;<br>
        and he knew, when he had done it, and to his</kbd>
        </p>
      </td>
    </tr>
  </tbody>
</table>
<p class="backtotop"><a href="#top">Voltar ao in&iacute;cio</a></p>


<h3><a name="line_no">Numera&ccedil;&atilde;o de Linhas</a></h3>
<p>A numera&ccedil;&atilde;o de linhas &eacute; comum nos livros de poesia, e surge junto
   &agrave; margem a cada cinco ou dez linhas. Mantenha-a, inserindo-a a seguir ao texto,
   pelo menos, a seis espa&ccedil;os de dist&acirc;ncia (&agrave; direita), mesmo se
   a numera&ccedil;&atilde;o estiver &agrave; esquerda da poesia/texto na imagem original.
   Uma vez que a poesia n&atilde;o ser&aacute; reformatada numa vers&atilde;o
   e-book, a numera&ccedil;&atilde;o de linhas ser&aacute; &uacute;til para os leitores.
</p>
<!-- END RR -->
<!-- We need an example image and text for this. -->
<p class="backtotop"><a href="#top">Voltar ao in&iacute;cio</a></p>


<h3><a name="letter">Cartas/Correspond&ecirc;ncia</a></h3>
<p>Formate as cartas e correspond&ecirc;ncia como <a href="#para_space">par&aacute;grafos</a>.
   Coloque uma linha em branco antes do in&iacute;cio da carta.
   N&atilde;o &eacute; necess&aacute;rio reproduzir os espa&ccedil;os.
</p>
<p>Coloque as linhas do cabe&ccedil;alho e rodap&eacute; (como moradas,
   datas, sauda&ccedil;&otilde;es ou assinaturas) entre <kbd>/*</kbd> e
   <kbd>*/</kbd>. Consulte a sec&ccedil;&atilde;o <a href="#outofline">Coloca&ccedil;&atilde;o
   de Sinais de Formata&ccedil;&atilde;o Externa</a> para obter mais detalhes
   sobre este tipo de sinaliza&ccedil;&atilde;o.
</p>
<p>N&atilde;o coloque espa&ccedil;os antes do cabe&ccedil;alho ou
   rodap&eacute;, mesmo que existam espa&ccedil;os ou estejam alinhados
   &agrave; direita no original&mdash;coloque-os
   junto &agrave; margem esquerda. O p&oacute;s-processador ir&aacute;
   format&aacute;-las convenientemente.
</p>
<p>Se a correspond&ecirc;ncia estiver impressa de forma diferente relativamente ao texto
   principal, consulte a sec&ccedil;&atilde;o <a href="#block_qt">Cita&ccedil;&otilde;es</a>.
</p>
<!-- END RR -->

<table width="100%" align="center" border="1" cellpadding="4" cellspacing="0" summary="Letter Example">
  <tbody>
    <tr><th align="left" bgcolor="cornsilk">Imagem Original:</th></tr>
    <tr align="left">
      <td width="100%" valign="top"> <img src="../letter.png" alt="" width="500" height="217"> <br>
      </td>
    </tr>
    <tr><th align="left" bgcolor="cornsilk">Texto Formatado Correctamente:</th></tr>
    <tr>
      <td width="100%" valign="top">
        <p><kbd>&lt;i&gt;John James Audubon to Claude Fran&ccedil;ois Rozier&lt;/i&gt;</kbd></p>
        <p><kbd>[Letter No. 1, addressed]</kbd></p>
        <p><kbd>/*<br>
        &lt;sc&gt;M. Fr. Rozier&lt;/sc&gt;,<br>
        Merchant-Nantes.<br>
        &lt;sc&gt;New York&lt;/sc&gt;, &lt;i&gt;10 January, 1807&lt;/i&gt;.</kbd></p>
        <p><kbd>
        &lt;sc&gt;Dear Sir&lt;/sc&gt;:<br>
        */</kbd></p>
        <p><kbd>
        We have had the pleasure of receiving by the &lt;i&gt;Penelope&lt;/i&gt; your<br>
        consignment of 20 pieces of linen cloth, for which we send our<br>
        thanks. As soon as we have sold them, we shall take great<br>
        pleasure in making our return.</kbd>
        </p>
      </td>
    </tr>
  </tbody>
</table>
<br>
<table width="100%" align="center" border="1" cellpadding="4" cellspacing="0" summary="Letter Example">
  <tbody>
    <tr><th align="left" bgcolor="cornsilk">Imagem Original:</th></tr>
    <tr align="left">
      <td width="100%" valign="top"> <img src="../letter2.png" alt="" width="500" height="271"> <br>
      </td>
    </tr>
    <tr><th align="left" bgcolor="cornsilk">Texto Formatado Correctamente:</th></tr>
    <tr>
      <td width="100%" valign="top">
        <p><kbd>/#<br>
        lack of memory which &lt;i&gt;baffles belief&lt;/i&gt;, I have a certain<br>
        "uptaking" knack. My preachment will bore you, but you<br>
        will (if you read it) detect an &lt;i&gt;ensemble&lt;/i&gt;; but, for goodness'<br>
        sake, &lt;i&gt;zitti&lt;/i&gt;! They'll think, when they hear the P.R.A., that,<br>
        Lor' bless him! he'd known it all his life. Nevertheless,<br>
        enough for the day, &amp;c. Best love to Gussey.--Affect. bro.,</kbd></p>
        <p><kbd>/*<br>
        &lt;sc&gt;Fred.&lt;/sc&gt;<br>
        */<br>
        #/</kbd></p>
        <p><kbd>I remember--when my husband and I were<br>
        sitting with him one afternoon after his return<br>
        home that autumn--his saying, "I feel distinctly I</kbd>
        </p>
      </td>
    </tr>
  </tbody>
</table>
<p class="backtotop"><a href="#top">Voltar ao in&iacute;cio</a></p>


<h3><a name="r_align">Texto Alinhado &agrave; Direita</a></h3>
<p>Coloque o texto alinhado &agrave; direita entre <kbd>/*</kbd> e <kbd>*/</kbd>.
   Consulte a sec&ccedil;&atilde;o <a href="#outofline">Coloca&ccedil;&atilde;o
   de Sinais de Formata&ccedil;&atilde;o Externa</a> para obter mais detalhes sobre
   este tipo de sinaliza&ccedil;&atilde;o, e a sec&ccedil;&atilde;o
   <a href="#letter">Cartas/Correspond&ecirc;ncia</a> para ver exemplos.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Voltar ao in&iacute;cio</a></p>


<table width="100%" border="0" cellspacing="0" cellpadding="6" summary="Page-level formatting">
  <tbody>
    <tr>
      <td bgcolor="silver"><h2 style="margin-bottom: 0; margin-top: 0;">Formata&ccedil;&atilde;o de P&aacute;ginas:</h2></td>
    </tr>
  </tbody>
</table>


<h3><a name="blank_pg">P&aacute;gina em Branco</a></h3>
<p>Se a p&aacute;gina n&atilde;o tiver nem imagens nem texto, formate-a como <kbd>[Blank Page]</kbd>.
</p>
<p>Se houver algum texto para formatar na &aacute;rea de texto, ou se tiver uma imagem
   sem texto, siga as instru&ccedil;&otilde;es descritas em <a href="#bad_image">Imagens Danificadas</a>
   ou <a href="#bad_text">Imagem N&atilde;o Corresponde ao Texto</a>.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Voltar ao in&iacute;cio</a></p>


<h3><a name="title_pg">P&aacute;gina de Rosto (Frente e Verso)</a></h3>
<p>Formate todo o texto, tal como foi impresso na p&aacute;gina, mesmo que
   esteja tudo em mai&uacute;sculas, letras mai&uacute;sculas e min&uacute;sculas,
   etc., incluindo os anos de publica&ccedil;&atilde;o ou direito de autor.
</p>
<p>Nos livros mais antigos a primeira letra &eacute; geralmente representada
   por uma imagem grande e ornamentada&mdash;formate como se estivesse apenas a letra.
</p>
<!-- END RR -->

<table width="100%" align="center" border="1" cellpadding="4" cellspacing="0" summary="Title Page Example">
  <tbody>
    <tr>
      <th align="left" bgcolor="cornsilk">Imagem Original:</th>
    </tr>
    <tr align="left">
      <td width="100%" valign="top"><img src="../title.png" width="500" height="520" alt=""><br>
      </td>
    </tr>
    <tr>
      <th align="left" bgcolor="cornsilk">Texto Formatado Correctamente:</th>
    </tr>
    <tr>
      <td width="100%" valign="top">
        <p><kbd>/*<br>
        GREEN FANCY</kbd></p>
        <p><kbd>BY<br>
        GEORGE BARR McCUTCHEON</kbd></p>
        <p><kbd>AUTHOR OF "GRAUSTARK," "THE HOLLOW OF HER HAND,"<br>
        "THE PRINCE OF GRAUSTARK," ETC.</kbd></p>
        <p><kbd>&lt;i&gt;WITH FRONTISPIECE BY<br>
        C. ALLAN GILBERT&lt;/i&gt;</kbd></p>
        <p><kbd>[Illustration]</kbd></p>
        <p><kbd>NEW YORK<br>
        DODD, MEAD AND COMPANY<br>
        1917<br>
        */</kbd></p>
      </td>
    </tr>
  </tbody>
</table>
<p class="backtotop"><a href="#top">Voltar ao in&iacute;cio</a></p>


<h3><a name="toc">Tabela de Conte&uacute;dos</a></h3>
<p>Formate a Tabela de Conte&uacute;dos tal como est&aacute; impressa no
   livro, mesmo que esteja tudo em mai&uacute;sculas,
   letras mai&uacute;sculas e min&uacute;sculas, etc., entre os marcadores
   <kbd>/*</kbd> e <kbd>*/</kbd>. Consulte a sec&ccedil;&atilde;o
   <a href="#outofline">Coloca&ccedil;&atilde;o de Sinais de Formata&ccedil;&atilde;o
   Externa</a> para obter mais detalhes sobre este tipo de sinaliza&ccedil;&atilde;o.
</p>
<p>As refer&ecirc;ncias a n&uacute;meros de p&aacute;ginas devem ser mantidas e colocadas,
   <b>a pelo menos, seis espa&ccedil;os</b> do fim da linha de texto.
   Remova os pontos ou outra pontua&ccedil;&atilde;o usados para alinhar os n&uacute;meros de p&aacute;gina.
</p>
<!-- END RR -->

<table width="100%" align="center" border="1" cellpadding="4" cellspacing="0" summary="Table of Contents example">
  <tbody>
    <tr>
      <th align="left" bgcolor="cornsilk">Imagem Original:</th>
    </tr>
    <tr align="left">
      <td width="100%" valign="top"><img src="../tablec.png" alt="" width="500" height="650"></td>
    </tr>
    <tr>
      <th align="left" bgcolor="cornsilk">Texto Formatado Correctamente:</th>
    </tr>
    <tr>
      <td width="100%" valign="top">
        <p><kbd><br><br><br><br>CONTENTS<br>
        <br>
        <br>
        /*<br>
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
        */</kbd></p>
      </td>
    </tr>
  </tbody>
</table>
<p class="backtotop"><a href="#top">Voltar ao in&iacute;cio</a></p>


<h3><a name="bk_index">&Iacute;ndices</a></h3>
<p>Coloque o &iacute;ndice entre <kbd>/*</kbd> e <kbd>*/</kbd>.
   Consulte a sec&ccedil;&atilde;o <a href="#outofline">Coloca&ccedil;&atilde;o
   de Sinais de Formata&ccedil;&atilde;o Externa</a> para obter mais detalhes
   sobre este tipo de sinaliza&ccedil;&atilde;o. N&atilde;o
   alinhe os n&uacute;meros como surgem na imagem, basta colocar uma v&iacute;rgula
   ou dois pontos e depois a numera&ccedil;&atilde;o das p&aacute;ginas.
</p>
<p>Os &iacute;ndices t&ecirc;m por vezes duas colunas; este espa&ccedil;o
   limitado pode fazer com que os t&oacute;picos fiquem
   divididos e passem para a linha seguinte. Coloque-os na mesma linha.
   Seguindo esta regra, &eacute; aceit&aacute;vel que os &iacute;ndices resultem
   em linhas longas, o que n&atilde;o constitui
   um problema porque ser&atilde;o formatadas automaticamente durante o p&oacute;s-processamento.
</p>
<p>Coloque uma linha em branco entre cada t&oacute;pico no &iacute;ndice.
   No caso de existirem sub-t&oacute;picos (muitas vezes separados por ponto e
   v&iacute;rgulas <kbd>;</kbd>), coloque um por linha, com um avan&ccedil;o
   a dois espa&ccedil;os.
</p>
<p>Cada nova sec&ccedil;&atilde;o do &iacute;ndice (A, B, C...) deve ser formatada
   como se tratasse de um <a href="#sect_head">t&iacute;tulo de sec&ccedil;&atilde;o</a>
   colocando duas linhas em branco antes.
</p>
<p>Por vezes nos livros antigos, a primeira palavra de cada letra do &iacute;ndice
   &eacute; impressa toda em mai&uacute;sculas ou mai&uacute;sculas mais pequenas (small caps);
   formate-a de acordo com o estilo utilizado nos restantes t&oacute;picos do &iacute;ndice.
</p>
<p>Por favor, consulte os <a href="#comments">Coment&aacute;rios do Projecto</a>,
   uma vez que o Gestor de Projecto pode solicitar uma outra forma de formatar, como por exemplo:
   tratar o &iacute;ndice como uma <a href="#toc">Tabela de Conte&uacute;dos</a>.
</p>
<!-- END RR -->

<table width="100%" align="center" border="1" cellpadding="4" cellspacing="0" summary="Rejoining Index Lines">
  <tbody>
    <tr>
      <th align="left" bgcolor="cornsilk">Imagem Original:</th>
    </tr>
    <tr>
      <td valign="top">
        Elizabeth I, her royal Majesty the<br>
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Queen, 123, 144-155.<br>
        &nbsp;&nbsp;birth of, 145.<br>
        &nbsp;&nbsp;christening, 146-147.<br>
        &nbsp;&nbsp;death and burial, 152.<br>
        <br>
        Ethelred II, the Unready, 33.
      </td>
    </tr>
    <tr>
      <th align="left" bgcolor="cornsilk">Texto Formatado Correctamente:</th>
    </tr>
    <tr>
      <td valign="top">
        <kbd><br>/*<br>
        Elizabeth I, her royal Majesty the Queen, 123, 144-155.<br>
        &nbsp;&nbsp;birth of, 145.<br>
        &nbsp;&nbsp;christening, 146-147.<br>
        &nbsp;&nbsp;death and burial, 152.<br>
        <br>
        Ethelred II, the Unready, 33.<br>
        */</kbd>
      </td>
    </tr>
  </tbody>
</table>
<br>
<table width="100%" align="center" border="1" cellpadding="4" cellspacing="0" summary="Aligning Index Subtopics">
  <tbody>
    <tr><th align="left" bgcolor="cornsilk">Imagem Original:</th></tr>
    <tr>
      <td valign="top">
        Hooker, Jos., maj. gen. U. S. V., 345; assigned<br>
        &nbsp;&nbsp;&nbsp;to command Porter's corps, 350; afterwards,<br>
        &nbsp;&nbsp;&nbsp;McDowell's, 367; in pursuit of Lee, 380;<br>
        &nbsp;&nbsp;&nbsp;at South Mt., 382; unacceptable to Halleck,<br>
        &nbsp;&nbsp;&nbsp;retires from active service, 390.<br>
        Hopkins, Henry H., 209; notorious secessionist in<br>
        &nbsp;&nbsp;&nbsp;Kanawha valley, 217; controversy with Gen.<br>
        &nbsp;&nbsp;&nbsp;Cox over escaped slave, 233.<br>
        <br>
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>J</b><br>
        <br>
        <span style="font-variant: small-caps">James</span>, Lewis M., 187; capt. on Gen. Wilson's staff, 194.<br>
      </td>
    </tr>
    <tr>
      <th align="left" bgcolor="cornsilk">Texto Formatado Correctamente:</th>
    </tr>
    <tr>
      <td valign="top">
        <kbd><br>/*<br>
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
        <br>
        J<br>
        <br>
        James, Lewis M., 187;<br>
        &nbsp;&nbsp;capt. on Gen. Wilson's staff, 194.<br>
        */</kbd>
      </td>
    </tr>
  </tbody>
</table>
<br>
<table width="100%" align="center" border="1" cellpadding="4" cellspacing="0" summary="Index Example">
  <tbody>
    <tr><th align="left" bgcolor="cornsilk">Imagem Original:</th></tr>
    <tr align="left">
      <td valign="top"> <img src="../index.png" alt="" width="438" height="355"></td>
    </tr>
    <tr><th align="left" bgcolor="cornsilk">Texto Formatado Correctamente:</th></tr>
    <tr>
      <td valign="top">
        <kbd><br>/*<br>
        Sales committee, 52<br>
        <br>
        Sales manager, 30<br>
        <br>
        Sales records, 120<br>
        &nbsp;&nbsp;daily, 121<br>
        &nbsp;&nbsp;monthly, 123<br>
        &nbsp;&nbsp;salesmen's, 123<br>
        <br>
        Shipping clerk, 184<br>
        &nbsp;&nbsp;class rates, 186<br>
        &nbsp;&nbsp;commodity rate file, 193<br>
        &nbsp;&nbsp;commodity rates, 186<br>
        &nbsp;&nbsp;freight tariffs, 188<br>
        &nbsp;&nbsp;routing shipments, 194<br>
        <br>
        Shipping department, 183-229<br>
        &nbsp;&nbsp;back orders, 199<br>
        &nbsp;&nbsp;checking shipments, 200<br>
        */</kbd>
      </td>
    </tr>
  </tbody>
</table>
<p class="backtotop"><a href="#top">Voltar ao in&iacute;cio</a></p>


<h3><a name="play_n">Pe&ccedil;as de Teatro: Nome de Actores/Marca&ccedil;&otilde;es C&eacute;nicas</a></h3>
<p>Para todas as pe&ccedil;as de teatro:</p>
<ul compact>
 <li>Formate a lista de personagens (Dramatis Person&aelig;) como uma <a href="#lists">lista</a> normal.</li>
 <li>Formate cada Acto como um <a href="#chap_head">t&iacute;tulo de cap&iacute;tulo</a>,
     colocando 4 linhas em branco antes e 2 depois.</li>
 <li>Formate cada Cena como um <a href="#sect_head">t&iacute;tulo de sec&ccedil;&atilde;o</a>,
     colocando 2 linhas em branco antes.</li>
 <li>Num di&aacute;logo, formate a mudan&ccedil;a de personagem com um novo par&aacute;grafo, com uma
     linha em branco   antes. Se o nome da personagem estiver isolado numa linha,
     formate-o igualmente como um novo par&aacute;grafo.</li>
 <li>Formate os nomes dos actores da forma como surgem na imagem original, estejam em
     <a href="#italics">it&aacute;lico</a>, <a href="#bold">negrito</a>, ou
     letra mai&uacute;scula.</li>
 <li>As marca&ccedil;&otilde;es c&eacute;nicas devem ser formatadas como surgem
     na imagem original. Assim, se a marca&ccedil;&atilde;o estiver isolada numa linha, formate-a
     dessa forma; se estiver no fim de uma linha de di&aacute;logo, deixe ficar;
     se estiver alinhada &agrave; direita no fim de uma linha de di&aacute;logo,
     deixe pelo menos seis espa&ccedil;os entre o di&aacute;logo e a marca&ccedil;&atilde;o c&eacute;nica.<br>
     Frequentemente come&ccedil;am com um par&ecirc;ntese aberto, n&atilde;o
     sendo fechado posteriormente.
     Por favor mantenha-o assim: n&atilde;o feche os par&ecirc;nteses. Geralmente
     coloca-se a sinaliza&ccedil;&atilde;o de it&aacute;lico entre os par&ecirc;nteses.</li>
</ul>
<p>Para pe&ccedil;as com m&eacute;trica: (Pe&ccedil;as escritas como poesia com rima)</p>
<ul compact>
 <li>Muitas pe&ccedil;as utilizam m&eacute;trica e, tal como acontece com a poesia,
     n&atilde;o devem ser formatadas automaticamente. Coloque este tipo de texto entre
     <kbd>/*</kbd> e <kbd>*/</kbd> como se fosse <a href="#poetry">poesia</a>.
     Se as marca&ccedil;&otilde;es c&eacute;nicas estiverem isoladas numa linha
     pr&oacute;pria, n&atilde;o as coloque entre <kbd>/*</kbd> e <kbd>*/</kbd>.
     (as marca&ccedil;&otilde;es c&eacute;nicas n&atilde;o s&atilde;o m&eacute;tricas,
     e por isso podem ser formatadas automaticamente na fase de p&oacute;s-processamento.
     Os s&iacute;mbolos <kbd>/*</kbd> e <kbd>*/</kbd>
     impedem a formata&ccedil;&atilde;o autom&aacute;tica do di&aacute;logo m&eacute;trico.)</li>
 <li>Preserve o avan&ccedil;o relativo de um di&aacute;logo
     como faz para a <a href="#poetry">poesia</a>.</li>
 <li>Una as linhas com m&eacute;trica que ficaram divididas devido ao limite no papel,
     como formata a <a href="#poetry">poesia</a>.
     Se se tratar apenas de uma palavra, surge geralmente na linha
     de cima ou de baixo precedida de um par&ecirc;ntese aberto, n&atilde;o ficando
     por isso isolado numa linha.
     Veja o <a href="#play4">exemplo</a>.</li>
</ul>
<p>Por favor, verifique se o Gestor de Projecto n&atilde;o especificou uma
   formata&ccedil;&atilde;o diferente nos <a href="#comments">Coment&aacute;rios do Projecto</a>.
</p>
<!-- END RR -->

<table width="100%" align="center" border="1" cellpadding="4" cellspacing="0" summary="Play Example 1">
  <tbody>
    <tr>
      <th align="left" bgcolor="cornsilk">Imagem Original:</th>
    </tr>
    <tr align="left">
      <td width="100%" valign="top"><img src="../play1.png" width="500" height="430" alt=""><br>
      </td>
    </tr>
    <tr>
      <th align="left" bgcolor="cornsilk">Texto Formatado Correctamente:</th>
    </tr>
    <tr>
      <td width="100%" valign="top">
        <p><kbd>/*<br>
        Has not his name for nought, he will be trode upon:<br>
        What says my Printer now?
        </kbd></p><p><kbd>
        &lt;i&gt;Clow.&lt;/i&gt; Here's your last Proof, Sir.<br>
        You shall have perfect Books now in a twinkling.
        </kbd></p><p><kbd>
        &lt;i&gt;Lap.&lt;/i&gt; These marks are ugly.
        </kbd></p><p><kbd>
        &lt;i&gt;Clow.&lt;/i&gt; He says, Sir, they're proper:<br>
        Blows should have marks, or else they are nothing worth.
        </kbd></p><p><kbd>
        &lt;i&gt;La.&lt;/i&gt; But why a Peel-crow here?
        </kbd></p><p><kbd>
        &lt;i&gt;Clow.&lt;/i&gt; I told 'em so Sir:<br>
        A scare-crow had been better.
        </kbd></p><p><kbd>
        &lt;i&gt;Lap.&lt;/i&gt; How slave? look you, Sir,<br>
        Did not I say, this &lt;i&gt;Whirrit&lt;/i&gt;, and this &lt;i&gt;Bob&lt;/i&gt;,<br>
        Should be both &lt;i&gt;Pica Roman&lt;/i&gt;.
        </kbd></p><p><kbd>
        &lt;i&gt;Clow.&lt;/i&gt; So said I, Sir, both &lt;i&gt;Picked Romans&lt;/i&gt;,<br>
        And he has made 'em &lt;i&gt;Welch&lt;/i&gt; Bills,<br>
        Indeed I know not what to make on 'em.
        </kbd></p><p><kbd>
        &lt;i&gt;Lap.&lt;/i&gt; Hay-day; a &lt;i&gt;Souse&lt;/i&gt;, &lt;i&gt;Italica&lt;/i&gt;?
        </kbd></p><p><kbd>
        &lt;i&gt;Clow.&lt;/i&gt; Yes, that may hold, Sir,<br>
        &lt;i&gt;Souse&lt;/i&gt; is a &lt;i&gt;bona roba&lt;/i&gt;, so is &lt;i&gt;Flops&lt;/i&gt; too.<br>
        */</kbd></p>
      </td>
    </tr>
  </tbody>
</table>
<br>
<table width="100%" align="center" border="1" cellpadding="4" cellspacing="0" summary="Play Example 2">
  <tbody>
    <tr>
      <th align="left" bgcolor="cornsilk">Imagem Original:</th>
    </tr>
    <tr align="left">
      <td width="100%" valign="top"><img src="../play2.png" width="500" height="680" alt=""><br>
      </td>
    </tr>
    <tr>
      <th align="left" bgcolor="cornsilk">Texto Formatado Correctamente:</th>
    </tr>
    <tr>
      <td width="100%" valign="top">
        <p><kbd>/*<br>
        &lt;sc&gt;Clin.&lt;/sc&gt; And do I hold thee, my Antiphila,<br>
        Thou only wish and comfort of my soul!<br>
        <br>
        &lt;sc&gt;Syrus.&lt;/sc&gt; In, in, for you have made our good man wait. &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(&lt;i&gt;Exeunt.&lt;/i&gt;<br>
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
        &lt;i&gt;Enter&lt;/i&gt; &lt;sc&gt;Menedemus&lt;/sc&gt;.<br>
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
        &lt;sc&gt;Chrem.&lt;/sc&gt; But he's come forth. (&lt;i&gt;Seeing&lt;/i&gt; &lt;sc&gt;Menedemus&lt;/sc&gt;.)<br>
        Yonder he stands. I'll go and speak with him.<br>
        Good-morrow, neighbor! I have news for you;<br>
        Such news as you'll be overjoy'd to hear.<br>
        */</kbd></p>
      </td>
    </tr>
  </tbody>
</table>
<br>
<a name="play3"><!-- Example --></a>
<table width="100%" align="center" border="1" cellpadding="4" cellspacing="0" summary="Play Example 3">
  <tbody>
    <tr>
      <th align="left" bgcolor="cornsilk">Imagem Original:</th>
    </tr>
    <tr align="left">
      <td width="100%" valign="top"><img src="../play3.png" width="504" height="206" alt=""><br>
      </td>
    </tr>
    <tr>
      <th align="left" bgcolor="cornsilk">Texto Formatado Correctamente:</th>
    </tr>
    <tr>
      <td width="100%" valign="top">
        <p><kbd>[&lt;i&gt;Hernda has come from the grove and moves up to his side&lt;/i&gt;]<br>
        <br>
        /*<br>
        &lt;i&gt;Her.&lt;/i&gt; [&lt;i&gt;Adoringly&lt;/i&gt;] And you the master!<br>
        <br>
        &lt;i&gt;Hud.&lt;/i&gt; Daughter, you owe my lord Megario<br>
        Some pretty thanks.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;[&lt;i&gt;Kisses her cheek&lt;/i&gt;]<br>
        <br>
        &lt;i&gt;Her.&lt;/i&gt;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;I give them, sir.<br>
        */</kbd></p>
      </td>
    </tr>
  </tbody>
</table>
<br>
<a name="play4"><!-- Example --></a>
<table width="100%" align="center" border="1" cellpadding="4" cellspacing="0" summary="Play Example 4">
  <tbody>
    <tr>
      <th align="left" bgcolor="cornsilk">Imagem Original:</th>
    </tr>
    <tr align="left">
      <td width="100%" valign="top"><img src="../play4.png" width="502" height="98" alt=""><br>
      </td>
    </tr>
    <tr>
      <th align="left" bgcolor="cornsilk">Texto Formatado Correctamente:</th>
    </tr>
    <tr>
      <td width="100%" valign="top">
        <p><kbd>/*<br>
        &lt;i&gt;Am.&lt;/i&gt; Sure you are fasting;<br>
        Or not slept well to night; some dream (&lt;i&gt;Ismena?&lt;/i&gt;)<br>
        <br>
        &lt;i&gt;Ism.&lt;/i&gt; My dreams are like my thoughts, honest and innocent,<br>
        Yours are unhappy; who are these that coast us?<br>
        You told me the walk was private.<br>
        */</kbd></p>
      </td>
    </tr>
  </tbody>
</table>
<p class="backtotop"><a href="#top">Voltar ao in&iacute;cio</a></p>


<h3><a name="anything">Qualquer outra situa&ccedil;&atilde;o que necessite
   de um tratamento especial ou que suscite d&uacute;vidas</a></h3>
<p>Se ao formatar, encontrar alguma situa&ccedil;&atilde;o que n&atilde;o esteja
   descrita neste documento, e que ache que merece um tratamento especial ou que
   n&atilde;o saiba ao certo como formatar, coloque a sua quest&atilde;o, indicando
   o n&uacute;mero da imagem .png (p&aacute;gina), na <a href="#forums">Discuss&atilde;o
   do Projecto</a>.
</p>
<p>Deve tamb&eacute;m colocar uma nota no texto formatado para explicar ao
   volunt&aacute;rio seguinte e ao p&oacute;s-processador qual o problema ou quest&atilde;o.
   Coloque a sua nota a seguir a um par&ecirc;ntese recto e dois asteriscos <kbd>[**</kbd>
   e termine-a fechando o par&ecirc;ntese recto <kbd>]</kbd>. Esta ac&ccedil;&atilde;o
   far&aacute; a nota sobressair do texto do autor, e alertar&aacute; o revisor seguinte
   para uma compara&ccedil;&atilde;o mais cuidadosa entre esta parte do texto e a imagem
   correspondente, de forma a resolver o problema. Deve tamb&eacute;m identificar a ronda
   em que est&aacute; a trabalhar antes do <kbd>]</kbd>, para que os volunt&aacute;rios
   seguintes saibam quem foi.
   Qualquer coment&aacute;rio deixado por um volunt&aacute;rio anterior <b>tem de</b>
   ser mantido. Consulte a sec&ccedil;&atilde;o seguinte para mais informa&ccedil;&otilde;es.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Voltar ao in&iacute;cio</a></p>


<h3><a name="prev_notes">Notas de Volunt&aacute;rios Precedentes</a></h3>
<p>Quaisquer notas ou coment&aacute;rios de um volunt&aacute;rio precedente
   <b>n&atilde;o devem</b> ser removidos. Pode concordar ou discordar dessa
   nota/coment&aacute;rio, mas mesmo que saiba a resposta, nunca a/o remova.
   Se souber a fonte que esclarece a quest&atilde;o, por favor cite-a na nota, para
   que o p&oacute;s-processador possa verific&aacute;-la.
</p>
<p>Se estiver a formatar numa ronda mais avan&ccedil;ada e encontrar uma nota de um
   volunt&aacute;rio de rondas anteriores com uma quest&atilde;o que saiba resolver,
   por favor, perca um pouco do seu tempo a dar-lhe algum feedback. Para isso clique no
   nome do volunt&aacute;rio que encontra no ecr&atilde; de revis&atilde;o/formata&ccedil;&atilde;o,
   e envie uma mensagem a explicar a forma de resolver quest&otilde;es semelhantes no futuro.
   Por favor, como referimos anteriormente, n&atilde;o remova a nota.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Voltar ao in&iacute;cio</a></p>


<table width="100%" border="0" cellspacing="0" cellpadding="6" summary="Common Problems">
  <tbody>
    <tr>
      <td bgcolor="silver"><h2 style="margin-bottom: 0; margin-top: 0;">Problemas Comuns:</h2></td>
    </tr>
  </tbody>
</table>


<h3><a name="bad_image">Imagens Danificadas</a></h3>
<p>Se uma imagem estiver danificada (n&atilde;o aparecer no ecr&atilde;, cortada,
   imposs&iacute;vel de ser lida), por favor, escreva uma mensagem
   sobre esta imagem na <a href="#forums">discuss&atilde;o do projecto</a>.
</p>
<p>Tenha em aten&ccedil;&atilde;o de que alguma imagens s&atilde;o muito grandes, sendo normal
   demorar um pouco, principalmente se tiver v&aacute;rias janelas abertas ou se o
   computador que estiver a usar j&aacute; n&atilde;o for recente. Antes de reportar
   esta imagem como sendo danificada, tente ajustar o zoom da imagem,
   fechar algumas janelas e programas, ou colocar a situa&ccedil;&atilde;o na
   discuss&atilde;o do projecto, para saber se mais algu&eacute;m tem o mesmo problema.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Voltar ao in&iacute;cio</a></p>


<h3><a name="bad_text">Imagem N&atilde;o Corresponde ao Texto</a></h3>
<p>Se a imagem n&atilde;o corresponder ao texto apresentado, por favor, escreva
   uma mensagem sobre esta imagem na <a href="#forums">discuss&atilde;o do projecto</a>.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Voltar ao in&iacute;cio</a></p>


<h3><a name="round1">Erros de Revis&atilde;o ou Formata&ccedil;&atilde;o Precedentes</a></h3>
<p>Se o volunt&aacute;rio precedente cometeu v&aacute;rios erros, ou ignorou v&aacute;rias
   situa&ccedil;&otilde;es, por favor, perca um pouco de tempo e d&ecirc;-lhe
   feedback (uma no&ccedil;&atilde;o do que fez) clicando no seu nome
   no ecr&atilde; de revis&atilde;o/formata&ccedil;&atilde;o, e enviando uma
   mensagem privada a explicar como lidar com a situa&ccedil;&atilde;o, para que
   n&atilde;o volte a cometer os mesmos erros.
</p>
<p><em>Por favor, seja simp&aacute;tico!</em> Todos n&oacute;s somos volunt&aacute;rios
   e damos todos o nosso melhor. O objectivo da sua mensagem de feedback deve ser
   inform&aacute;-los da forma correcta de formatar, e n&atilde;o uma cr&iacute;tica.
   D&ecirc; um exemplo espec&iacute;fico do trabalho do volunt&aacute;rio em quest&atilde;o,
   mostrando o que fez e o que deveria ter feito.
</p>
<p>Se o volunt&aacute;rio precedente tiver realizado um trabalho excelente, pode
   enviar-lhe uma mensagem a congratul&aacute;-lo&mdash;especialmente se tiver
   formatado uma p&aacute;gina particularmente dif&iacute;cil.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Voltar ao in&iacute;cio</a></p>


<h3><a name="p_errors">Erros de Impress&atilde;o/Ortografia</a></h3>
<p>Corrija todas as palavras que tenham sido lidas de forma incorrecta pelo
   OCR (scannos), mas n&atilde;o fa&ccedil;a correc&ccedil;&otilde;es
   do que lhe pare&ccedil;a erros ortogr&aacute;ficos ou de impress&atilde;o
   na imagem original. Muitos dos textos antigos cont&ecirc;m palavras escritas
   de forma diferente da que usamos actualmente, e
   n&oacute;s preservamos estes dizeres antigos, incluindo caracteres acentuados.
</p>
<p>Coloque uma nota no texto junto ao erro de impresso&atilde;<kbd>[**typo
   impress&atilde;o?]</kbd>. Se tiver d&uacute;vidas, coloque
   a quest&atilde;o igualmente na <a href="#forums">discuss&atilde;o do projecto</a>.
   Se fizer uma altera&ccedil;&atilde;o, inclua uma nota a descrever o que alterou:
   <kbd>[**typo "impresso&atilde;" corrigido]</kbd>.
   Inclua os dois asteriscos <kbd>**</kbd> para chamar a aten&ccedil;&atilde;o do p&oacute;s-processador.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Voltar ao in&iacute;cio</a></p>


<h3><a name="f_errors">Erros Factuais no Texto</a></h3>
<p>N&atilde;o corrija erros factuais existentes no livro. Muitos dos livros que
   trabalhamos, re&uacute;nem conte&uacute;dos que talvez j&aacute; n&atilde;o sejam
   correctos actualmente. Deixe-os como o autor as escreveu. Consulte a sec&ccedil;&atilde;o
   <a href="#p_errors">Erros de Impress&atilde;o/Ortografia</a> para saber como deixar uma nota
   se achar que o autor n&atilde;o pretendia dizer aquilo que est&aacute; impresso.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Voltar ao in&iacute;cio</a></p>

</div>


<table width="100%" border="0" cellspacing="0" cellpadding="6" summary="Alphabetical Index">
  <tbody>
    <tr>
      <td bgcolor="silver"><h2 style="margin-bottom: 0; margin-top: 0;"><a name="index">&Iacute;ndice Alfab&eacute;tico das Regras</a></h2></td>
    </tr>
  </tbody>
</table>
<br>

<table border="0" width="100%" summary="Alphabetical Index">
  <tr>
    <td width="50%" valign="top">
      <ul>
        <li><a href="#font_ch">Altera&ccedil;&otilde;es da Fonte</a></li>
        <li><a href="#font_sz">Altera&ccedil;&otilde;es no Tamanho da Fonte</a></li>
        <li><a href="#extra_s">Asteriscos Entre Par&aacute;grafos</a></li>
        <li><a href="#extra_sp">Avan&ccedil;os</a></li>
        <li><a href="#para_space">Avan&ccedil;os, Par&aacute;grafo</a></li>
        <li><a href="#separate_pg">Cada P&aacute;gina &eacute; um Elemento</a></li>
        <li><a href="#chap_head">Cap&iacute;tulo, T&iacute;tulos</a></li>
        <li><a href="#letter">Cartas/Correspond&ecirc;ncia</a></li>
        <li><a href="#block_qt">Cita&ccedil;&otilde;es</a></li>
        <li><a href="#outofline">Coloca&ccedil;&atilde;o de Sinais de Formata&ccedil;&atilde;o Externa</a></li>
        <li><a href="#inline">Coloca&ccedil;&atilde;o de Sinais de Formata&ccedil;&atilde;o Interna</a></li>
        <li><a href="#comments">Coment&aacute;rios do Projecto</a></li>
        <li><a href="#prev_notes">Coment&aacute;rios, Volunt&aacute;rios Precedentes</a></li>
        <li><a href="#toc">Conte&uacute;dos, Tabela de</a></li>
        <li><a href="#letter">Correspond&ecirc;ncia</a></li>
        <li><a href="#prev_pg">Corrigir Erros em P&aacute;ginas Anteriores</a></li>
        <li><a href="#bad_image">Danificadas, Imagens</a></li>
        <li><a href="#extra_s">Decora&ccedil;&otilde;es Entre Par&aacute;grafos</a></li>
        <li><a href="#play_n">Direc&ccedil;&otilde;es C&eacute;nicas (Pe&ccedil;as)</a></li>
        <li><a href="#r_align">Direita, Texto Alinhado</a></li>
        <li><a href="#forums">Discuss&atilde;o do Projecto</a></li>
        <li><a href="#maj_div">Divis&otilde;es no Texto, Maiores</a></li>
        <li><a href="#play_n">Drama</a></li>
        <li><a href="#anything">D&uacute;vidas</a></li>
        <li><a href="#poetry">Epigramas</a></li>
        <li><a href="#round1">Erros Anteriores de Revis&atilde;o ou Formata&ccedil;&atilde;o</a></li>
        <li><a href="#f_errors">Erros, Factos</a></li>
        <li><a href="#f_errors">Erros Factuais no Texto</a></li>
        <li><a href="#p_errors">Erros, Impress&atilde;o</a></li>
        <li><a href="#extra_s">Espa&ccedil;os Entre Par&aacute;grafos, Extra</a></li>
        <li><a href="#extra_sp">Espa&ccedil;os Extra</a></li>
        <li><a href="#extra_sp">Espa&ccedil;os Extra Entre Palavras</a></li>
        <li><a href="#extra_s">Espa&ccedil;os Extra Entre Par&aacute;grafos</a></li>
        <li><a href="#para_space">Espa&ccedil;os, Par&aacute;grafo</a></li>
        <li><a href="#illust">Figuras</a></li>
        <li><a href="#maj_div">Figuras, Lista de</a></li>
        <li><a href="#font_ch">Fonte Antiqua</a></li>
        <li><a href="#outofline">Formata&ccedil;&atilde;o Externa, Sinais de</a></li>
        <li><a href="#inline">Formata&ccedil;&atilde;o Interna, Sinais de</a></li>
        <li><a href="#separate_pg">Formatar Cada P&aacute;gina Individualmente</a></li>
        <li><a href="#forums">F&oacute;rum</a></li>
        <li><a href="#spaced"><span style="letter-spacing: .2em;">Gesperrt</span> (Texto Espa&ccedil;ado)</a></li>
        <li><a href="#summary">Guia Pr&aacute;tico de Formata&ccedil;&atilde;o</a></li>
        <li><a href="#extra_s">Horizontais, Linhas</a></li>
        <li><a href="#bad_image">Imagens Danificadas</a></li>
        <li><a href="#bk_index">&Iacute;ndices</a></li>
        <li><a href="#maj_div">Introdu&ccedil;&atilde;o</a></li>
        <li><a href="#illust">Legendas, Figuras</a></li>
        <li><a href="#word_caps">Letras Mai&uacute;sculas</a></li>
        <li><a href="#extra_s">Linhas Entre Par&aacute;grafos</a></li>
        <li><a href="#extra_s">Linhas Horizontais</a></li>
        <li><a href="#line_no">Linhas, Numera&ccedil;&atilde;o</a></li>
      </ul>
    </td>
    <td width="50%" valign="top">
      <ul>
        <li><a href="#maj_div">Lista de Figuras</a></li>
        <li><a href="#lists">Lista de Itens</a></li>
        <li><a href="#word_caps">Mai&uacute;sculas, Letras</a></li>
        <li><a href="#small_caps">Mai&uacute;sculas, <span style="font-variant: small-caps">Mais Pequenas</span></a></li>
        <li><a href="#font_ch">Mudan&ccedil;a de Fonte</a></li>
        <li><a href="#font_sz">Mudan&ccedil;a de Tamanho da Fonte</a></li>
        <li><a href="#play_n">Nomes de Actores (Pe&ccedil;as)</a></li>
        <li><a href="#footnotes">Notas</a></li>
        <li><a href="#footnotes">Notas de Rodap&eacute;</a></li>
        <li><a href="#para_side">Notas (Sidenotes)</a></li>
        <li><a href="#prev_notes">Notas, Volunt&aacute;rios Precedentes</a></li>
        <li><a href="#line_no">Numera&ccedil;&atilde;o de Linhas</a></li>
        <li><a href="#maj_div">Outras Divis&otilde;es no Texto</a></li>
        <li><a href="#title_pg">P&aacute;gina de Rosto (Frente/Verso)</a></li>
        <li><a href="#blank_pg">P&aacute;gina em Branco</a></li>
        <li><a href="#title_pg">P&aacute;gina, T&iacute;tulo</a></li>
        <li><a href="#blank_pg">P&aacute;gina Vazia</a></li>
        <li><a href="#prev_pg">P&aacute;ginas Anteriores, Corrigir Erros em</a></li>
        <li><a href="#small_caps">Palavras em <span style="font-variant: small-caps">Letra Mai&uacute;scula Mais Pequena</span></a></li>
        <li><a href="#play_n">Pe&ccedil;as: Nomes de Actores/Direc&ccedil;&otilde;es C&eacute;nicas</a></li>
        <li><a href="#poetry">Poesia</a></li>
        <li><a href="#maj_div">Pref&aacute;cio</a></li>
        <li><a href="#page_ref">Refer&ecirc;ncias a P&aacute;ginas "Ver p&aacute;g. 123"</a></li>
        <li><a href="#prime">Regra B&aacute;sica</a></li>
        <li><a href="#summary">Resumo das Regras</a></li>
        <li><a href="#sect_head">Sec&ccedil;&atilde;o, T&iacute;tulos</a></li>
        <li><a href="#para_side">Sidenotes</a></li>
        <li><a href="#outofline">Sinais de Formata&ccedil;&atilde;o Externa</a></li>
        <li><a href="#inline">Sinais de Formata&ccedil;&atilde;o Interna, Coloca&ccedil;&atilde;o de</a></li>
        <li><a href="#small_caps"><span style="font-variant: small-caps">Small Capitals</span></a></li>
        <li><a href="#about">Sobre este documento</a></li>
        <li><a href="#subscr">Subescrito</a></li>
        <li><a href="#supers">Superescrito</a></li>
        <li><a href="#toc">Tabela de Conte&uacute;dos</a></li>
        <li><a href="#tables">Tabelas</a></li>
        <li><a href="#r_align">Texto Alinhado &agrave; Direita</a></li>
        <li><a href="#maj_div">Texto, Outras Divis&otilde;es no</a></li>
        <li><a href="#italics">Texto em It&aacute;lico</a></li>
        <li><a href="#bold">Texto em Negrito</a></li>
        <li><a href="#spaced"><span style="letter-spacing: .2em;">Texto Espa&ccedil;ado</span> (gesperrt)</a></li>
        <li><a href="#bad_text">Texto, Imagem N&atilde;o Correspondente</a></li>
        <li><a href="#bad_text">Texto N&atilde;o Corresponde &agrave; Imagem</a></li>
        <li><a href="#subscr">Texto Subescrito (Subscripts)</a></li>
        <li><a href="#underl">Texto Sublinhado</a></li>
        <li><a href="#supers">Texto Superescrito (Superscripts)</a></li>
        <li><a href="#chap_head">T&iacute;tulos de Cap&iacute;tulo</a></li>
        <li><a href="#maj_div">T&iacute;tulos, Outros</a></li>
        <li><a href="#sect_head">T&iacute;tulos, Sec&ccedil;&atilde;o</a></li>
        <li><a href="#anything">Tudo o Resto...</a></li>
        <li><a href="#page_ref">"Ver p&aacute;g. 123" (Refer&ecirc;ncia a P&aacute;ginas)</a></li>
        <li><a href="#title_pg">Verso da P&aacute;gina de Rosto</a></li>
      </ul>
    </td>
  </tr>
</table>

<br>
<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="silver" summary="Links">
  <tr>
    <td width="10">&nbsp;</td>
    <td width="100%" align="center"><font face="verdana, helvetica, sans-serif" size="1">
      Voltar para:
      <a href="../..">P&aacute;gina Principal do <?php echo "$site_name"; ?></a>,
      &nbsp;&nbsp;&nbsp;
      <a href="../faq_central.php"><?php echo "$site_abbreviation"; ?> P&aacute;gina do Centro de FAQ's</a>,
      &nbsp;&nbsp;&nbsp;
      <a href="<?php echo $PG_home_url; ?>">P&aacute;gina Principal do Projecto Gutenberg</a>.
      </font>
    </td>
  </tr>
</table>

<?php
// vim: sw=4 ts=4 expandtab
