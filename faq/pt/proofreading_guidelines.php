<?php

// File received from user 'rfarinha' at pgdp.net, 2/25/2009

$relPath='../../pinc/';
include_once($relPath.'base.inc');
include_once($relPath.'faq.inc');
include_once($relPath.'pg.inc');
include_once($relPath.'theme.inc');

maybe_redirect_to_external_faq("pt");

$theme_args["css_data"] = "p.backtotop {text-align:right; font-size:75%;margin-right:-5%;}";

output_header('Regras de Revis&atilde;o', NO_STATSBAR, $theme_args);

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

<h1 align="center"><a name="top">Regras de Revis&atilde;o</a></h1>

<h3 align="center">Vers&atilde;o 2.0, publicada a 7 de Junho de 2009 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
  <a href="../dochist.php"><font size="-1">(Hist&oacute;rico)</font></a></h3>

<p>Regras de Revis&atilde;o <a href="../proofreading_guidelines.php">em Ingl&ecirc;s</a> /
      Proofreading Guidelines <a href=../proofreading_guidelines.php">in English</a><br>
    Regras de Revis&atilde;o <a href="../fr/proofreading_guidelines.php">em Franc&ecirc;s</a> /
      Directives de Relecture et Correction <a href="../fr/proofreading_guidelines.php">en fran&ccedil;ais</a><br>
    Regras de Revis&atilde;o <a href="../es/proofreading_guidelines.php">em Castelhano</a> /
      Reglas de Revisi&oacute;n <a href="../es/proofreading_guidelines.php">en espa&ntilde;ol</a><br>
    Regras de Revis&atilde;o <a href="../nl/proofreading_guidelines.php">em Holand&ecirc;s</a> /
      Proeflees-Richtlijnen <a href="../nl/proofreading_guidelines.php">in het Nederlands</a><br>
    Regras de Revis&atilde;o <a href="../de/proofreading_guidelines.php">em Alem&atilde;o</a> /
      Korrekturlese-Richtlinien <a href="../de/proofreading_guidelines.php">auf Deutsch</a><br>
    Regras de Revis&atilde;o <a href="../it/proofreading_guidelines.php">em Italiano</a> /
      Regole di Correzione <a href="../it/proofreading_guidelines.php">in Italiano</a><br>
</p>

<p>Veja o <a href="../../quiz/start.php?show_only=PQ">Teste de Revis&atilde;o e Tutorial</a>!
</p>

<table border="0" cellspacing="0" width="100%" summary="Table of Contents">
  <tbody>
  <tr>
    <td width="1" bgcolor="silver">&nbsp;</td>
    <td bgcolor="silver" align="center"><h2 style="margin-bottom: 0; margin-top: 0;">&Iacute;ndice</h2></td>
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
    <td bgcolor="silver" align="left">&nbsp;</td>
  </tr>
  <tr>
    <td width="1" bgcolor="silver">&nbsp;</td>
    <td bgcolor="white" align="left">
      <ul>
        <li style="margin-top:.25em;"><font size="+1">Revis&atilde;o de Caracteres:</font>
        <ul>
          <li><a href="#double_q">Aspas</a></li>
          <li><a href="#single_q">Plicas</a></li>
          <li><a href="#quote_ea">Aspas no In&iacute;cio de Cada Linha</a></li>
          <li><a href="#period_s">Pontua&ccedil;&atilde;o Final</a></li>
          <li><a href="#punctuat">Pontua&ccedil;&atilde;o e Espa&ccedil;os</a></li>
          <li><a href="#extra_sp">Espa&ccedil;o Extra Entre Palavras</a></li>
          <li><a href="#trail_s">Espa&ccedil;o Extra no Final da Linha</a></li>
          <li><a href="#em_dashes">Tra&ccedil;os, H&iacute;fenes e Sinais de Subtrac&ccedil;&atilde;o</a></li>
          <li><a href="#eol_hyphen">Hifeniza&ccedil;&atilde;o no Final da Linha</a></li>
          <li><a href="#eop_hyphen">Hifeniza&ccedil;&atilde;o no Final da P&aacute;gina</a></li>
          <li><a href="#period_p">Elipses (Retic&ecirc;ncias) "..."</a></li>
          <li><a href="#contract">Contrac&ccedil;&otilde;es</a></li>
          <li><a href="#fract_s">Frac&ccedil;&otilde;es</a></li>
          <li><a href="#a_chars">Acentua&ccedil;&atilde;o/Caracteres Non-ASCII</a></li>
          <li><a href="#d_chars">Caracteres com Sinais Diacr&iacute;ticos</a></li>
          <li><a href="#f_chars">Caracteres Non-Latin</a></li>
          <li><a href="#supers">Texto Superescrito (Superscripts)</a></li>
          <li><a href="#subscr">Texto Subescrito (Subscripts)</a></li>
          <li><a href="#drop_caps">Letra Grande e Ornamentada no In&iacute;cio do Par&aacute;grafo</a></li>
          <li><a href="#small_caps">Texto em Mai&uacute;sculas Mais Pequenas
            (<span style="font-variant: small-caps;">Small Capitals</span>)</a></li>
        </ul></li>
        <li style="margin-top:.25em;"><font size="+1">Revis&atilde;o de Par&aacute;grafos:</font>
        <ul>
          <li><a href="#line_br">Quebras de Linha</a></li>
          <li><a href="#chap_head">T&iacute;tulos de Cap&iacute;tulo</a></li>
          <li><a href="#para_space">Espa&ccedil;o entre Par&aacute;grafos e Avan&ccedil;os</a></li>
          <li><a href="#page_hf">Cabe&ccedil;alho e Rodap&eacute; da P&aacute;gina</a></li>
          <li><a href="#illust">Figuras</a></li>
          <li><a href="#footnotes">Notas de Rodap&eacute;</a></li>
          <li><a href="#para_side">Notas (Sidenotes)</a></li>
          <li><a href="#mult_col">V&aacute;rias Colunas</a></li>
          <li><a href="#tables">Tabelas</a></li>
          <li><a href="#poetry">Poesia/Epigramas</a></li>
          <li><a href="#line_no">Numera&ccedil;&atilde;o de Linhas</a></li>
          <li><a href="#next_word">Palavra Isolada no Final da P&aacute;gina</a></li>
        </ul></li>
        <li style="margin-top:.25em;"><font size="+1">Revis&atilde;o de P&aacute;ginas:</font>
        <ul>
          <li><a href="#blank_pg">P&aacute;gina em Branco</a></li>
          <li><a href="#title_pg">P&aacute;gina de Rosto (Frente e Verso)</a></li>
          <li><a href="#toc">Tabela de Conte&uacute;dos</a></li>
          <li><a href="#bk_index">&Iacute;ndices</a></li>
          <li><a href="#play_n">Pe&ccedil;as de Teatro: Nome de Actores/Marca&ccedil;&otilde;es C&eacute;nicas</a></li>
        </ul></li>
        <li><a href="#anything">Qualquer outra situa&ccedil;&atilde;o que necessite de um
          tratamento especial ou que suscite d&uacute;vidas</a></li>
        <li><a href="#prev_notes">Notas de Revisores Precedentes</a></li>
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
          <li><a href="#formatting">Formata&ccedil;&atilde;o</a></li>
          <li><a href="#common_OCR">Problemas Comuns de OCR</a></li>
          <li><a href="#OCR_scanno">Problemas de OCR: Erros de Leitura</a></li>
          <li><a href="#OCR_raised_o">Problemas de OCR: Ser&aacute; que &deg; &ordm; s&atilde;o referentes a grau?</a></li>
          <li><a href="#hand_notes">Notas Manuscritas no Livro</a></li>
          <li><a href="#bad_image">Imagens Danificadas</a></li>
          <li><a href="#bad_text">Imagem N&atilde;o Corresponde ao Texto</a></li>
          <li><a href="#round1">Erros do Revisor Precedente</a></li>
          <li><a href="#p_errors">Erros de Impress&atilde;o/Ortografia</a></li>
          <li><a href="#f_errors">Erros Factuais no Texto</a></li>
          <li><a href="#insert_char">Inser&ccedil;&atilde;o de Caracteres Especiais</a></li>
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
<p>O livro electr&oacute;nico definitivo, lido daqui a muitos anos no
   futuro, deve transmitir exactamente o que o autor queria dizer. Se o autor escreveu
   uma palavra de uma forma estranha, deve mant&ecirc;-la assim. Se o
   autor escreveu declara&ccedil;&otilde;es marcadamente tendenciosas ou
   racistas, deve mant&ecirc;-las assim. Se o autor usou v&iacute;rgulas,
   texto superescrito, ou se colocou uma nota de rodap&eacute; em cada
   tr&ecirc;s palavras, deve mant&ecirc;-las respectivamente. N&oacute;s somos revisores, <b>n&atilde;o</b>
   editores. Se algo no texto n&atilde;o corresponder &agrave; imagem, deve alterar o texto para que corresponda.
   (Consulte <a href="#p_errors">Erros de Impress&atilde;o/Ortografia</a>
   para corrigir erros &oacute;bvios de tipografia.)
</p>
<p>Mudamos apenas pequenas conven&ccedil;&otilde;es tipogr&aacute;ficas
   que n&atilde;o afectem o sentido do que o autor escreveu.
   Por exemplo, juntamos as palavras hifenizadas no final de uma
   linha (<a href="#eol_hyphen">Hifeniza&ccedil;&atilde;o
   no Final da Linha</a>). Altera&ccedil;&otilde;es
   como esta ajuda-nos a criar uma vers&atilde;o
   do livro formatada e consistente (<em>normalizada</em>).
   As regras de revis&atilde;o que seguimos foram
   especificamente pensadas para atingir esse objectivo. Por favor, leia o
   presente documento com esta ideia em mente. Estas regras dizem respeito <em>apenas</em>
   &agrave; revis&atilde;o.
   Como revisor dever&aacute; certificar-se que o texto corresponde &agrave;
   imagem. Posteriormente os formatadores ir&atilde;o ocupar-se da formata&ccedil;&atilde;o
   do texto de acordo com a imagem.
</p>
<p>Para ajudar os revisores seguintes, os formatadores e o
   p&oacute;s-processador, preserve as <a href="#line_br">Quebras de
   Linha</a>. Este passo facilitar&aacute; a compara&ccedil;&atilde;o entre o
   texto e a imagem.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Voltar ao in&iacute;cio</a></p>


<h3><a name="summary">Resumo das Regras</a></h3>
<p>O <a href="../proofing_summary.pdf">Resumo das Regras</a>
   &eacute; um pequeno documento de 2 p&aacute;ginas em PDF, que resume
   os pontos principais destas regras, e que fornece exemplos explicativos
   de como deve rever. Encorajamos os Revisores Principiantes a imprimi-lo e a
   mant&ecirc;-lo &agrave; m&atilde;o enquanto rev&ecirc;em.
</p>
<p>Se precisar de descarregar e instalar um leitor de documentos
   PDF, pode obt&ecirc;-lo gratuitamente a partir da Adobe&reg;
   <a href="http://www.adobe.com/products/acrobat/readstep2.html">aqui</a>.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Voltar ao in&iacute;cio</a></p>


<h3><a name="about">Sobre Este Documento</a></h3>
<p>Este documento existe para minimizar as diferen&ccedil;as de
   revis&atilde;o de um livro que, ao ser dividido em
   p&aacute;ginas, &eacute; revisto por v&aacute;rios revisores. Este
   documento ajuda-nos a rever <em>da
   mesma forma</em>. Assim, ajuda no trabalho do p&oacute;s-processador,
   tornando mais f&aacute;cil o processo de juntar as p&aacute;ginas
   revistas num e-book.
</p>
<p><i>Com as Regras n&atilde;o se pretende a cria&ccedil;&atilde;o de um livro de estilo</i>.
</p>
<p>Inclu&iacute;mos neste documento todos os itens de
   revis&atilde;o em que os novos utilizadores tiveram
   d&uacute;vidas enquanto reviam. Existe um outro documento para as
   <a href="formatting_guidelines.php">Regras de
   Formata&ccedil;&atilde;o</a>. Um segundo grupo de volunt&aacute;rios
   trabalhar&aacute; a formata&ccedil;&atilde;o do texto.
   Se se confrontar com uma situa&ccedil;&atilde;o para a qual
   n&atilde;o encontra aqui refer&ecirc;ncia, &eacute;
   prov&aacute;vel que deva ser tratada nas rondas de formata&ccedil;&atilde;o
   e que por conseguinte n&atilde;o seja aqui mencionada. Se n&atilde;o tiver
   a certeza, por favor, coloque a quest&atilde;o na
   <a href="#forums">Discuss&atilde;o do Projecto</a> (Project Discussion).
</p>
<p>Se faltar algum item, se achar que deveria haver uma outra metodologia, ou se achar alguma
   explica&ccedil;&atilde;o vaga, por favor diga-nos.
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


<h3><a name="comments">Coment&aacute;rios do Projecto</a></h3>
<p>A P&aacute;gina do Projecto &eacute; carregada, quando selecciona um projecto
   para rever. Nesta p&aacute;gina existe uma sec&ccedil;&atilde;o chamada
   "Project Comments" (Coment&aacute;rios do Projecto) que cont&eacute;m informa&ccedil;&atilde;o
   espec&iacute;fica sobre esse projecto (livro). <b>Leia-a antes de come&ccedil;ar a
   revis&atilde;o!</b> Se o Gestor do Projecto quiser rever o livro de
   forma diferente da especificada nas Regras, escrever&aacute; a&iacute; as suas
   indica&ccedil;&otilde;es (instru&ccedil;&otilde;es). As
   instru&ccedil;&otilde;es existentes nos Coment&aacute;rios do Projecto
   <em>t&ecirc;m preced&ecirc;ncia</em> sobre estas regras e devem ser seguidas.
   Podem tamb&eacute;m existir instru&ccedil;&otilde;es referentes &agrave;
   formata&ccedil;&atilde;o, que n&atilde;o s&atilde;o aplic&aacute;veis na
   revis&atilde;o. Por fim, este &eacute; tamb&eacute;m o local onde o
   Gestor de Projecto pode fornecer algumas informa&ccedil;&otilde;es
   sobre o autor ou o livro.
</p>
<p><em>Por favor, leia tamb&eacute;m o f&oacute;rum (discuss&atilde;o) do
   projecto</em>: o Gestor do Projecto pode esclarecer aqui regras
   espec&iacute;ficas do projecto, e &eacute; muitas vezes utilizado pelos revisores para
   avisar os outros revisores de quest&otilde;es recorrentes e para definir a
   melhor forma de as tratar. (Ver em baixo)
</p>
<p>Na P&aacute;gina do Projecto, a liga&ccedil;&atilde;o 'Images, Pages
   Proofread, &amp; Differences' permite ver como &eacute; que outros revisores
   fizeram altera&ccedil;&otilde;es. <a href="<?php echo $Using_project_details_URL; ?>">Neste F&oacute;rum</a>
   debatem-se diferentes formas de usar este tipo de informa&ccedil;&atilde;o.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Voltar ao in&iacute;cio</a></p>


<h3><a name="forums">F&oacute;rum/Discuss&atilde;o deste Projecto</a></h3>
<p>Na p&aacute;gina do ecr&atilde; de revis&atilde;o (P&aacute;gina do
   Projecto), onde clica para come&ccedil;ar a rever p&aacute;ginas, existe
   uma linha denominada "F&oacute;rum", com uma liga&ccedil;&atilde;o
   com o nome "Discuss this Project" (se algu&eacute;m j&aacute; tiver dado
   in&iacute;cio &agrave; discuss&atilde;o), ou "Start a discussion on this
   Project" (se ainda ningu&eacute;m o tiver feito). Ao clicar nessa
   liga&ccedil;&atilde;o, ir&aacute; para um t&oacute;pico (thread) do
   f&oacute;rum de projectos, dedicado a esse projecto espec&iacute;fico.
   Esse &eacute; o local onde deve colocar as suas d&uacute;vidas sobre o livro,
   informar o Gestor de Projecto de problemas, etc. Utilizar este
   t&oacute;pico do f&oacute;rum &eacute; a forma recomendada de
   comunica&ccedil;&atilde;o com o Gestor de Projecto e com outros
   revisores que trabalham nesse projecto.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Voltar ao in&iacute;cio</a></p>


<h3><a name="prev_pg">Correc&ccedil;&atilde;o de Erros em P&aacute;ginas Precedentes</a></h3>
<p>A <a href="#comments">P&aacute;gina do Projecto</a> cont&eacute;m
   liga&ccedil;&otilde;es para p&aacute;ginas desse projecto em que trabalhou
   recentemente. (Se n&atilde;o reviu ainda nenhuma p&aacute;gina, n&atilde;o
   ter&aacute; nenhuma liga&ccedil;&atilde;o.)
</p>
<p>As p&aacute;ginas listadas por baixo de "DONE" ou "IN PROGRESS" est&atilde;o
   dispon&iacute;veis para edi&ccedil;&atilde;o ou para conclus&atilde;o de
   revis&atilde;o. Basta clicar na liga&ccedil;&atilde;o para a p&aacute;gina em
   quest&atilde;o. Assim, se descobrir que se enganou numa p&aacute;gina, ou
   se marcar algo incorrectamente, pode clicar aqui para
   reabrir a p&aacute;gina e corrigir o erro.
</p>
<p>Pode utilizar as liga&ccedil;&otilde;es "Images, Pages
   Proofread, &amp; Differences" ou "Just My Pages" dispon&iacute;veis
   na <a href="#comments">P&aacute;gina do Projecto</a>. Estas
   p&aacute;ginas disponibilizar&atilde;o uma liga&ccedil;&atilde;o para a
   edi&ccedil;&atilde;o das p&aacute;ginas que reviu e que ainda podem ser
   corrigidas.
</p>
<p>Para mais informa&ccedil;&otilde;es, consulte os t&oacute;picos
   <a href="../prooffacehelp.php?i_type=0">Standard Proofreading Interface Help</a>
   (Ajuda de Interface Normalizada de Revis&atilde;o) ou <a href="../prooffacehelp.php?i_type=1">Enhanced
   Proofreading Interface Help</a> (Ajuda de Interface Melhorada de Revis&atilde;o),
   dependendo da interface que utilizar.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Voltar ao in&iacute;cio</a></p>


<table width="100%" border="0" cellspacing="0" cellpadding="6" summary="Character-level proofreading">
  <tbody>
    <tr>
      <td bgcolor="silver"><h2 style="margin-bottom: 0; margin-top: 0;">Revis&atilde;o de Caracteres:</h2></td>
    </tr>
  </tbody>
</table>


<h3><a name="double_q">Aspas</a></h3>
<p>Reveja as &ldquo;aspas&rdquo; como as aspas ASCII (<kbd>"</kbd>). N&atilde;o as converta em
   plicas ('). Deixe-as como o autor as escreveu.
   Consulte a sec&ccedil;&atilde;o <a href="#chap_head">T&iacute;tulos de
   Cap&iacute;tulo</a> se faltar um s&iacute;mbolo de aspas no in&iacute;cio
   de um cap&iacute;tulo.
</p>
<p>Para aspas diferentes de <kbd>"</kbd>, utilize as mesmas que surgem na
   imagem, se estas estiverem dispon&iacute;veis. O equivalente &agrave;s
   aspas francesas &eacute; <kbd>&laquo;isto&raquo;</kbd>, e est&atilde;o
   dispon&iacute;veis nos menus do ecr&atilde; de revis&atilde;o, uma
   vez que fazem parte do Latin-1. Lembre-se de remover o espa&ccedil;o
   entre as aspas e o texto citado; se for necess&aacute;rio, ser&aacute;
   adicionado no p&oacute;s-processamento. O mesmo se aplica nos idiomas
   em que se utilizam aspas inversas, <kbd>&raquo;como estas&laquo;</kbd>.
</p>
<p>As aspas utilizadas em alguns textos (em alem&atilde;o e outros idiomas),&nbsp; <kbd>&bdquo;como estas&rdquo;</kbd>
   est&atilde;o tamb&eacute;m dispon&iacute;veis nos menus. Para simplificar,
   deve utilizar sempre&nbsp; <kbd>&bdquo;</kbd>&nbsp; e&nbsp; <kbd>&ldquo;</kbd>&nbsp;
   independentemente das aspas que surjam no texto original, desde que as
   aspas utilizadas no texto original estejam claramente em baixo e em cima. Se necess&aacute;rio,
   as aspas ser&atilde;o alteradas para as utilizadas no texto, na fase de p&oacute;s-processamento.
</p>
<p>O Gestor de Projecto pode indicar-lhe, nos <a href="#comments">Coment&aacute;rios
   do Projecto</a>, que deve rever as aspas em textos de idioma n&atilde;o-ingl&ecirc;s
   de forma diferente, num determinado livro. Por favor, n&atilde;o
   utilize essas indica&ccedil;&otilde;es noutros projectos.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Voltar ao in&iacute;cio</a></p>


<h3><a name="single_q">Plicas</a></h3>
<p>Reveja-as como <kbd>'</kbd> (ap&oacute;strofes). N&atilde;o as converta em
   aspas ("). Deixe-as como o autor as escreveu.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Voltar ao in&iacute;cio</a></p>


<h3><a name="quote_ea">Aspas no In&iacute;cio de Cada Linha</a></h3>
<p>Remova as aspas de uma cita&ccedil;&atilde;o no in&iacute;cio de
   cada linha, <b>excepto</b> a da primeira linha.
   Se a cita&ccedil;&atilde;o se prolongar por muitos par&aacute;grafos,
   cada um destes par&aacute;grafos deve come&ccedil;ar com aspas.
</p>
<p>No entanto, em poesia, deixe as aspas extra onde elas surgem na imagem,
   uma vez que as quebras de linha n&atilde;o ser&atilde;o alteradas.
</p>
<p>Na maioria das vezes, as aspas s&oacute; fecham quando a
   cita&ccedil;&atilde;o acaba, o que pode n&atilde;o acontecer na
   p&aacute;gina que estiver a rever. Deixe o texto assim&mdash;n&atilde;o
   feche aspas, se isso n&atilde;o estiver representado na imagem.
</p>
<p>Existem algumas excep&ccedil;&otilde;es lingu&iacute;sticas. Em
   franc&ecirc;s, por exemplo, o di&aacute;logo entre aspas utiliza uma
   combina&ccedil;&atilde;o de pontua&ccedil;&atilde;o diferente para indicar
   v&aacute;rios interlocutores. Se n&atilde;o estiver familiarizado com
   determinado idioma, consulte os <a href="#comments">Coment&aacute;rios do
   Projecto</a> ou pergunte ao Gestor de Projecto na Discuss&atilde;o de
   Projecto como proceder.
</p>
<!-- END RR -->
<table width="100%" border="1" cellpadding="4" cellspacing="0" align="center" summary="Example of quote marks on each line">
  <tbody>
    <tr>
      <th valign="top" align="left" bgcolor="cornsilk">Imagem Original:</th>
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
      <th valign="top" align="left" bgcolor="cornsilk">Texto Revisto Correctamente:</th>
    </tr>
    <tr valign="top">
      <td>
        <kbd>
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
        </kbd>
      </td>
    </tr>
  </tbody>
</table>
<p class="backtotop"><a href="#top">Voltar ao in&iacute;cio</a></p>


<h3><a name="period_s">Pontua&ccedil;&atilde;o Final</a></h3>
<p>Reveja os pontos finais entre frases com um espa&ccedil;o logo a seguir.
</p>
<p>N&atilde;o &eacute; necess&aacute;rio remover espa&ccedil;os extra que
   possam existir depois de pontos finais&mdash;fazemo-lo automaticamente
   durante o p&oacute;s-processamento.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Voltar ao in&iacute;cio</a></p>


<h3><a name="punctuat">Pontua&ccedil;&atilde;o e Espa&ccedil;os</a></h3>
<p>O espa&ccedil;amento antes da pontua&ccedil;&atilde;o &eacute; comum
   em livros dos s&eacute;culos XVIII e XIX, que utilizava espa&ccedil;os
   parciais antes de um ponto e v&iacute;rgula, ou v&iacute;rgula.
</p>
<p>Geralmente, n&atilde;o deve existir espa&ccedil;amento antes dos
   caracteres de pontua&ccedil;&atilde;o, excepto antes das aspas. Se no
   texto resultante do OCR existir espa&ccedil;o antes da
   pontua&ccedil;&atilde;o, este deve ser removido. Isto aplica-se
   tamb&eacute;m a idiomas, como o franc&ecirc;s, que normalmente utilizam
   espa&ccedil;os antes dos caracteres de pontua&ccedil;&atilde;o.
   No entanto, pontua&ccedil;&atilde;o que habitualmente surja aos pares,
   como as "aspas", (par&ecirc;nteses), [par&ecirc;nteses rectos], e
   {chavetas}, geralmente tem um espa&ccedil;o antes do primeiro s&iacute;mbolo,
   que deve ser mantido.
</p>
<!-- END RR -->

<table width="100%" align="center" border="1" cellpadding="4" cellspacing="0" summary="Punctuation example">
  <tbody>
    <tr><th align="left" bgcolor="cornsilk">Imagem Original:</th></tr>
    <tr>
      <td valign="top">and so it goes&nbsp;; ever and ever.</td>
    </tr>
    <tr><th align="left" bgcolor="cornsilk">Texto Revisto Correctamente:</th></tr>
    <tr>
      <td valign="top"><kbd>and so it goes; ever and ever.</kbd></td>
    </tr>
  </tbody>
</table>
<p class="backtotop"><a href="#top">Voltar ao in&iacute;cio</a></p>


<h3><a name="extra_sp">Espa&ccedil;o Extra Entre Palavras</a></h3>
<p>Os espa&ccedil;os extra entre palavras s&atilde;o comuns nos
   resultados de OCR. N&atilde;o &eacute; necess&aacute;rio
   remov&ecirc;-los&mdash;estes s&atilde;o facilmente
   removidos facilmente durante o p&oacute;s-processamento.
   No entanto, <b>&eacute; necess&aacute;rio</b> remover o espa&ccedil;amento
   extra relativo &agrave; pontua&ccedil;&atilde;o, tra&ccedil;os, aspas, etc.,
   quando estes sinais surgem separados da palavra.
</p>
<p>Por exemplo, em <kbd>A horse&nbsp;;&nbsp;&nbsp;my kingdom for a
   horse.</kbd> o espa&ccedil;o entre a palavra "horse" e o ponto e
   v&iacute;rgula deve ser removido. Mas os dois espa&ccedil;os
   ap&oacute;s o ponto e v&iacute;rgula n&atilde;o s&atilde;o
   problem&aacute;ticos&mdash;n&atilde;o &eacute; necess&aacute;rio apagar
   um deles.
</p>
<p>Adicionalmente, se encontrar algum avan&ccedil;o no texto, deve
   elimin&aacute;-lo tamb&eacute;m.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Voltar ao in&iacute;cio</a></p>


<h3><a name="trail_s">Espa&ccedil;o Extra no Final da Linha</a></h3>
<p>N&atilde;o perca tempo a inserir espa&ccedil;os no final de cada
   linha do texto. Esses espa&ccedil;os ser&atilde;o eliminados
   automaticamente assim que guardar a p&aacute;gina. Quando o texto
   for p&oacute;s-processado, cada fim de linha ser&aacute; convertido
   num espa&ccedil;o.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Voltar ao in&iacute;cio</a></p>


<h3><a name="em_dashes">Tra&ccedil;os, H&iacute;fenes e Sinais de Subtrac&ccedil;&atilde;o</a></h3>
<p>Encontrar&aacute; nos livros quatro tipos principais:
</p>
  <ol compact>
    <li><i>H&iacute;fenes</i>. S&atilde;o usados para <b>unir</b>
        palavras, ou prefixos ou sufixos a uma palavra.
    <br>Reveja-os como um h&iacute;fen simples, sem espa&ccedil;os dos dois lados.
        Repare na excep&ccedil;&atilde;o no segundo exemplo em baixo.
    </li>
    <li><i>Tra&ccedil;os</i>. S&atilde;o um pouco mais longos, e
        s&atilde;o usados na <b>uni&atilde;o</b> de
        n&uacute;meros, ou como sinais matem&aacute;ticos de <b>subtrac&ccedil;&atilde;o</b>.
    <br>Reveja-os tamb&eacute;m como um h&iacute;fen &uacute;nico. O uso de
        espa&ccedil;amento &eacute; determinado pela forma como surge no livro
        original; geralmente n&atilde;o existem espa&ccedil;os na uni&atilde;o
        de n&uacute;meros, mas existem para os sinais de
        menos/subtrac&ccedil;&atilde;o (por vezes em ambos os lados, outras
        s&oacute; de um).
    </li>
    <li><i>Tra&ccedil;os Longos</i>. S&atilde;o <b>separadores</b>
        entre palavras&mdash;para dar &ecirc;nfase desta forma&mdash;ou quando
        o narrador se "engasga"&mdash;!
    <br>Reveja-os com dois h&iacute;fenes, se o tra&ccedil;o for curto
        (ocupando o espa&ccedil;o de 2-3 letras), e
        quatro h&iacute;fenes, se for longo (4-5 letras). N&atilde;o deixe espa&ccedil;o
        antes nem depois, mesmo que o exista na imagem do livro original.
    </li>
    <li><i>Nomes ou Palavras Deliberadamente Omitidos ou Censurados</i>
    <br>Se for representado por um tra&ccedil;o na imagem, reveja-o com
        dois ou quatro h&iacute;fenes como foi descrito para os
        Tra&ccedil;os &amp; Tra&ccedil;os Longos. Quando substitui uma palavra, deixe
        mais ou menos o mesmo espa&ccedil;o que essa palavra teria. Se for
        parte de uma palavra, ent&atilde;o n&atilde;o deixe
        espa&ccedil;os&mdash;junte-o ao resto da palavra.
    </li>
  </ol>
<p>Consulte tamb&eacute;m as regras para os h&iacute;fenes e tra&ccedil;os
   no <a href="#eol_hyphen">final da linha</a> e no
   <a href="#eop_hyphen">final da p&aacute;gina</a>.
</p>
<!-- END RR -->

<p><b>Exemplos</b>&mdash;Tra&ccedil;os, H&iacute;fenes e Sinais de Subtrac&ccedil;&atilde;o:
</p>

<table width="100%" align="center" border="1" cellpadding="4" cellspacing="0" summary="Hyphens and Dashes examples">
  <tbody>
    <tr>
      <th valign="top" bgcolor="cornsilk">Imagem Original:</th>
      <th valign="top" bgcolor="cornsilk">Texto Revisto Correctamente:</th>
      <th valign="top" bgcolor="cornsilk">Tipo</th>
    </tr>
    <tr>
      <td valign="top">semi-detached</td>
      <td valign="top"><kbd>semi-detached</kbd></td>
      <td>H&iacute;fen</td>
    </tr>
    <tr>
      <td valign="top">three- and four-part harmony</td>
      <td valign="top"><kbd>three- and four-part harmony</kbd></td>
      <td>H&iacute;fenes</td>
    </tr>
    <tr>
      <td valign="top">discoveries which the Crus-<br>
        aders made and brought home with</td>
      <td valign="top"><kbd>discoveries which the Crusaders<br>
        made and brought home with</kbd></td>
      <td>H&iacute;fen</td>
    </tr>
    <tr>
      <td valign="top">factors which mold char-<br>
        acter&mdash;environment, training and heritage,</td>
      <td valign="top"><kbd>factors which mold character--environment,<br>
        training and heritage,</kbd></td>
      <td>H&iacute;fen &amp; Tra&ccedil;o</td>
    </tr>
    <tr>
      <td valign="top">See pages 21&ndash;25</td>
      <td valign="top"><kbd>See pages 21-25</kbd></td>
      <td>Tra&ccedil;o</td>
    </tr>
    <tr>
      <td valign="top">It was &ndash;14&deg;C outside.</td>
      <td valign="top"><kbd>It was -14&deg;C outside.</kbd></td>
      <td>Tra&ccedil;o</td>
    </tr>
    <tr>
      <td valign="top">X &ndash; Y = Z</td>
      <td valign="top"><kbd>X - Y = Z</kbd></td>
      <td>Tra&ccedil;o</td>
    </tr>
    <tr>
      <td valign="top">2&ndash;1/2</td>
      <td valign="top"><kbd>2-1/2</kbd></td>
      <td>Tra&ccedil;o</td>
    </tr>
    <tr>
      <td valign="top">&mdash;A plague on both<br> your houses!&mdash;I am dead.</td>
      <td valign="top"><kbd>--A plague on both<br> your houses!--I am dead.</kbd></td>
      <td>Tra&ccedil;os</td>
    </tr>
    <tr>
      <td valign="top">sensations&mdash;sweet, bitter, salt, and sour<br>
        &mdash;if even all of these are simple tastes. What</td>
      <td valign="top"><kbd>sensations--sweet, bitter, salt, and sour--if<br>
        even all of these are simple tastes. What</kbd></td>
      <td>Tra&ccedil;os</td>
    </tr>
    <tr>
      <td valign="top">senses&mdash;touch, smell, hearing, and sight&mdash;<br>
        with which we are here concerned,</td>
      <td valign="top"><kbd>senses--touch, smell, hearing, and sight--with<br>
        which we are here concerned,</kbd></td>
      <td>Tra&ccedil;os</td>
    </tr>
    <tr>
      <td valign="top">It is the east, and Juliet is the sun&mdash;!</td>
      <td valign="top"><kbd>It is the east, and Juliet is the sun--!</kbd></td>
      <td>Tra&ccedil;o</td>
    </tr>
    <tr>
      <td valign="top"><img src="../dashes.png" width="300" height="28" alt=""></td>
      <td valign="top"><kbd>how a--a--cannon-ball goes----"</kbd></td>
      <td>Tra&ccedil;os, H&iacute;fen,<br> &amp; Tra&ccedil;o Longo</td>
    </tr>
    <tr>
      <td valign="top">"Three hundred&mdash;&mdash;" "years," she was going to<br>
        say, but the left-hand cat interrupted her.</td>
      <td valign="top"><kbd>"Three hundred----" "years," she was going to<br>
        say, but the left-hand cat interrupted her.</kbd></td>
      <td>Tra&ccedil;o Longo</td>
    </tr>
    <tr>
      <td valign="top">As the witness Mr. &mdash;&mdash; testified,</td>
      <td valign="top"><kbd>As the witness Mr. ---- testified,</kbd></td>
      <td>Tra&ccedil;o Longo</td>
    </tr>
    <tr>
      <td valign="top">As the witness Mr. S&mdash;&mdash; testified,</td>
      <td valign="top"><kbd>As the witness Mr. S---- testified,</kbd></td>
      <td>Tra&ccedil;o Longo</td>
    </tr>
    <tr>
      <td valign="top">the famous detective of &mdash;&mdash;B Baker St.</td>
      <td valign="top"><kbd>the famous detective of ----B Baker St.</kbd></td>
      <td>Tra&ccedil;o Longo</td>
    </tr>
    <tr>
      <td valign="top">&ldquo;You &mdash;&mdash; Yankee&rdquo;, she yelled.</td>
      <td valign="top"><kbd>"You ---- Yankee", she yelled.</kbd></td>
      <td>Tra&ccedil;o Longo</td>
    </tr>
    <tr>
      <td valign="top">&ldquo;I am not a d&mdash;d Yankee&rdquo;, he replied.</td>
      <td valign="top"><kbd>"I am not a d--d Yankee", he replied.</kbd></td>
      <td>Tra&ccedil;o</td>
    </tr>
  </tbody>
</table>
<p class="backtotop"><a href="#top">Voltar ao in&iacute;cio</a></p>


<h3><a name="eol_hyphen">Hifeniza&ccedil;&atilde;o no Final da Linha</a></h3>
<p>Quando surge um h&iacute;fen no final da linha, junte as duas partes
   da palavra. Remova o h&iacute;fen ao juntar as palavras, excepto
   se for uma palavra realmente hifenizada, como &eacute; o
   caso de "guarda-chuva". Consulte a sec&ccedil;&atilde;o
   <a href="#em_dashes">Tra&ccedil;os, H&iacute;fenes e Sinais de
   Subtrac&ccedil;&atilde;o</a> para ver exemplos de cada caso.
   Junte as partes na linha de cima, e coloque uma quebra de linha para
   preservar a formata&ccedil;&atilde;o da linha&mdash;este passo facilita
   o trabalho dos volunt&aacute;rios em rondas mais avan&ccedil;adas. Se a palavra
   for seguida de pontua&ccedil;&atilde;o, esta tamb&eacute;m deve ser transportada para a
   linha de cima.
</p>
<p>Palavras como to-day e to-morrow que actualmente j&aacute; n&atilde;o
   levam h&iacute;fen s&atilde;o por vezes hifenizadas em livros antigos
   como os que trabalhamos. Deixe-as hifenizadas como o autor as deixou.
   Se n&atilde;o tiver a certeza se o autor as hifenizou ou n&atilde;o,
   deixe o h&iacute;fen, marque com um <kbd>*</kbd> depois e junte as partes.
   Assim: <kbd>to-*day</kbd>. O asterisco chamar&aacute; a
   aten&ccedil;&atilde;o do p&oacute;s-processador, que tem
   acesso a todas as p&aacute;ginas, e pode saber o modo como o autor escreve
   geralmente determinada palavra.
</p>
<p>Do mesmo modo, se um tra&ccedil;o surgir no in&iacute;cio ou final
   da linha do seu texto OCR, mude-o de linha
   e/ou junte-lhe a palavra seguinte para que n&atilde;o haja espa&ccedil;os
   ou quebras de linha &agrave; sua volta. No entanto, se o autor utilizar um
   tra&ccedil;o para iniciar ou terminar um par&aacute;grafo ou uma linha de poesia,
   deve mant&ecirc;-lo, sem o unir &agrave; linha seguinte.
   Consulte a sec&ccedil;&atilde;o <a href="#em_dashes">Tra&ccedil;os,
   H&iacute;fenes e Sinais de Subtrac&ccedil;&atilde;o</a> para ver exemplos.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Voltar ao in&iacute;cio</a></p>


<h3><a name="eop_hyphen">Hifeniza&ccedil;&atilde;o no Final da P&aacute;gina</a></h3>
<p>Reveja os h&iacute;fens e ou tra&ccedil;os existentes no final da p&aacute;gina,
   deixando-os no fim da &uacute;ltima linha, e
   sinalize-os com um <kbd>*</kbd> depois destes. Por exemplo:
</p>
<table width="100%" align="center" border="1" cellpadding="4" cellspacing="0" summary="End-of-page Hyphenation example">
  <tbody>
    <tr><th align="left" bgcolor="cornsilk">Imagem Original:</th></tr>
    <tr>
      <td valign="top">something Pat had already become accus-</td>
    </tr>
    <tr><th align="left" bgcolor="cornsilk">Texto Revisto Correctamente:</th></tr>
    <tr>
      <td valign="top"><kbd>something Pat had already become accus-*</kbd></td>
    </tr>
  </tbody>
</table>
<p>Nas p&aacute;ginas que come&ccedil;arem com apenas uma parte da
   palavra da p&aacute;gina anterior ou um tra&ccedil;o,
   coloque um <kbd>*</kbd> antes da palavra parcial ou tra&ccedil;o.
   Continuando o exemplo anterior:
</p>
<table width="100%" align="center" border="1" cellpadding="4" cellspacing="0" summary="Start-of-page Hyphenation example">
  <tbody>
    <tr><th align="left" bgcolor="cornsilk">Imagem Original:</th></tr>
    <tr>
      <td valign="top">tomed to from having to do his own family</td>
    </tr>
    <tr><th align="left" bgcolor="cornsilk">Texto Revisto Correctamente:</th></tr>
    <tr>
      <td valign="top"><kbd>*tomed to from having to do his own family</kbd></td>
    </tr>
  </tbody>
</table>
<p>Os asteriscos chamar&atilde;o a aten&ccedil;&atilde;o do
   p&oacute;s-processador de que precisa de juntar as duas partes da
   palavra, quando estiver a fazer o tratamento do e-book final.
   Por favor, n&atilde;o junte os fragmentos entre p&aacute;ginas.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Voltar ao in&iacute;cio</a></p>


<h3><a name="period_p">Elipses (Retic&ecirc;ncias) "..."</a></h3>
<p>As regras referentes a este item s&atilde;o
   diferentes tendo em conta se se trata de um texto em ingl&ecirc;s (English) ou
   n&atilde;o (Languages Other Than English &mdash; LOTE).
</p>
<p><b>INGL&Ecirc;S</b>: Uma elipse deve ter tr&ecirc;s pontos.
   Relativamente ao espa&ccedil;amento, no meio de uma frase
   reveja os tr&ecirc;s pontos como uma palavra (ou seja, um
   espa&ccedil;o antes dos 3 pontos e outro depois).
   No final de uma frase reveja a elipse como pontua&ccedil;&atilde;o
   final, sem espa&ccedil;os antes.
</p>
<p>Repare que existir&aacute; tamb&eacute;m o sinal de
   pontua&ccedil;&atilde;o final no fim de uma frase.
   Assim, no caso de um ponto final, ser&atilde;o 4 pontos no total.
   Remova pontos extra, se existirem, ou adicione mais, se necess&aacute;rio,
   para que atinjam os tr&ecirc;s (ou quatro) consoante a situa&ccedil;&atilde;o.
   Uma boa pista para saber se &eacute; o final de uma frase &eacute; a
   utiliza&ccedil;&atilde;o de letra mai&uacute;scula no in&iacute;cio
   da palavra seguinte, ou a presen&ccedil;a de um sinal de
   pontua&ccedil;&atilde;o final (por exemplo, um ponto de
   interroga&ccedil;&atilde;o ou um ponto de exclama&ccedil;&atilde;o).
</p>
<p><b>LOTE:</b> (Languages Other Than English) Siga a regra geral "Siga
   fielmente o estilo utilizado na p&aacute;gina impressa".
   Acrescente espa&ccedil;os, se existirem espa&ccedil;os antes ou entre os
   pontos, e utilize o mesmo n&uacute;mero de pontos que surgem na imagem.
   Por vezes, a p&aacute;gina impressa n&atilde;o &eacute;
   percept&iacute;vel: neste caso, coloque uma nota <kbd>[**unclear]</kbd>
   de forma a chamar a aten&ccedil;&atilde;o do p&oacute;s-processador. (Nota: os
   P&oacute;s-processadores devem substituir os esses espa&ccedil;os, por
   espa&ccedil;os que n&atilde;o provoquem quebras de linha).
</p>
<!-- END RR -->
<p>Exemplos em Ingl&ecirc;s:
</p>
<table width="100%" align="center" border="1" cellpadding="4" cellspacing="0" summary="Ellipses examples">
  <tbody>
    <tr>
      <th valign="top" bgcolor="cornsilk">Imagem Original:</th>
      <th valign="top" bgcolor="cornsilk">Texto Revisto Correctamente:</th>
    </tr>
    <tr>
      <td valign="top">That I know .&nbsp;.&nbsp;. is true.</td>
      <td valign="top"><kbd>That I know ... is true.</kbd></td>
    </tr>
    <tr>
      <td valign="top">This is the end....</td>
      <td valign="top"><kbd>This is the end....</kbd></td>
    </tr>
    <tr>
      <td valign="top">The moving finger writes; and.&nbsp;.&nbsp;. The poet<br> surely had a pen though!</td>
      <td valign="top"><kbd>The moving finger writes; and.... The poet<br> surely had a pen though! </kbd></td>
    </tr>
    <tr>
      <td valign="top">Wherefore art thou Romeo.&nbsp;.&nbsp;.&nbsp;?</td>
      <td valign="top"><kbd>Wherefore art thou Romeo...?</kbd></td>
    </tr>
    <tr>
      <td valign="top">&ldquo;I went to the store,&nbsp;.&nbsp;.&nbsp;.&rdquo; said Harry.</td>
      <td valign="top"><kbd>"I went to the store, ..." said Harry.</kbd></td>
    </tr>
    <tr>
      <td valign="top">&ldquo;... And I did too!&rdquo; said Sally.</td>
      <td valign="top"><kbd>"... And I did too!" said Sally.</kbd></td>
    </tr>
    <tr>
      <td valign="top">&ldquo;Really?&nbsp;&nbsp;.&nbsp;.&nbsp;. Oh, Harry!&rdquo;</td>
      <td valign="top"><kbd>"Really?... Oh, Harry!"</kbd></td>
    </tr>
  </tbody>
</table>
<p class="backtotop"><a href="#top">Voltar ao in&iacute;cio</a></p>


<h3><a name="contract">Contrac&ccedil;&otilde;es</a></h3>
<p>Em ingl&ecirc;s, remova todo e qualquer espa&ccedil;o extra nas
   contrac&ccedil;&otilde;es, por exemplo <kbd>would&nbsp;n't</kbd>
   deve ser revisto como <kbd>wouldn't</kbd>, e <kbd>'t&nbsp;is</kbd>
   como <kbd>'tis</kbd>.
</p>
<p>Esta era uma conven&ccedil;&atilde;o frequente entre os
   editores do s&eacute;culo XIX, que mantinham o espa&ccedil;o para indicar que "would" e
   "not" eram originalmente duas palavras separadas. Por vezes, trata-se
   apenas de uma falha de OCR. Remova o espa&ccedil;o em qualquer dos
   casos.
</p>
<p>Alguns Gestores de Projecto podem pedir, nos <a href="#comments">Coment&aacute;rios
   do Projecto</a>, para n&atilde;o remover espa&ccedil;os extra nas
   contrac&ccedil;&otilde;es, principalmente se os textos contiverem
   coloquialismos, dialectos ou poesia.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Voltar ao in&iacute;cio</a></p>


<h3><a name="fract_s">Frac&ccedil;&otilde;es</a></h3>
<p>Reveja as frac&ccedil;&otilde;es da seguinte forma: <kbd>2&frac12;</kbd>
   &eacute; revista como <kbd>2-1/2</kbd>. O h&iacute;fen impede que o n&uacute;mero e a
   frac&ccedil;&atilde;o sejam separados na formata&ccedil;&atilde;o
   autom&aacute;tica, realizada no p&oacute;s-processamento.
   N&atilde;o utilize os s&iacute;mbolos de frac&ccedil;&atilde;o,
   excepto se tal for solicitado nos <a href="#comments">Coment&aacute;rios
   do Projecto</a>.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Voltar ao in&iacute;cio</a></p>


<h3><a name="a_chars">Acentua&ccedil;&atilde;o/Caracteres Non-ASCII</a></h3>
<p>Por favor, reveja-os utilizando os caracteres UTF-8 apropriados. Para caracteres que n&atilde;o
   existam em Unicode, consulte as instru&ccedil;&otilde;es do Gestor de Projecto no
   <a href="#comments">Coment&aacute;rios do Projecto</a>.
   Se n&atilde;o existirem no seu teclado, consulte a sec&ccedil;&atilde;o
   <a href="#insert_char">Inser&ccedil;&atilde;o de Caracteres Especiais</a>
   para mais informa&ccedil;&otilde;es sobre como os incluir na revis&atilde;o.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Voltar ao in&iacute;cio</a></p>


<h3><a name="d_chars">Caracteres com Sinais Diacr&iacute;ticos</a></h3>
<p>Em alguns projectos, encontrar&aacute; caracteres como sinais
   especiais, acima ou abaixo dos caracteres latinos normais (A...Z). A
   estes d&aacute;-se o nome de <i>sinais diacr&iacute;ticos</i>.
   Eles atribuem uma pron&uacute;ncia especial a este caracter.
</p>
<p>Se esse caracter n&atilde;o existir em Unicode, deve ser revisto de forma a utilizar
   os <i>sinais diacr&iacute;ticos correspondentes</i>: estes s&atilde;o s&iacute;mbolos Unicode que n&atilde;o
   podem estar sozinhos. Surgem acima (ou abaixo) da letra onde se encontram.
   Podem ser inseridos da seguinte forma: escrever a letra-base em primeiro lugar, e s&oacute; depois
   o sinal correspondente, utilizando as aplica&ccedil;&otilde;es e programas mencionados na
   sec&ccedil;&atilde;o <a href="#insert_char">Inser&ccedil;&atilde;o de Caracteres Especiais</a>.
</p>
<p>Em alguns sistemas, os sinais diacr&iacute;ticos podem n&atilde;o aparecer exactamente onde deveriam,
   mas, por exemplo, alinhados &agrave; direita. Devem ser utilizados na mesma, uma vez que outras pessoas
   com outros sistemas as ver&atilde;o sem quaisquer problemas. No entanto, se por alguma raz&atilde;o n&atilde;o
   as conseguir ver ou inserir devidamente, sinalize a letra com uma [**nota]. Tenha em aten&ccedil;&atilde;o
   que <i>Letras modificadoras de espa&ccedil;os</i> tamb&eacute;m existem;
   estas n&atilde;o devem ser utilizadas.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Voltar ao in&iacute;cio</a></p>


<h3><a name="f_chars">Caracteres Non-Latin</a></h3>
<p>Alguns projectos cont&ecirc;m texto impresso em caracteres
   non-Latin; ou seja, outros caracteres para al&eacute;m dos latinos
   de A a Z&mdash;por exemplo, caracteres Gregos, Cir&iacute;licos (utilizados em
   russo, eslavo e outros idiomas), Hebraicos ou &Aacute;rabes.
</p>
<p>Estes caracteres devem ser inseridos no texto como os caracteres Latin.
   (<b>SEM translitera&ccedil;&atilde;o!</b>)
</p>
<p>Se todo o documento estiver escrito em caracteres non-Latin script, &eacute; prefer&iacute;vel instalar um driver de
   teclado que identifique o idioma. Para mais informa&ccedil;&otilde;es, consulte o manual
   do seu sistema operativo.
</p>
<p>Se surgirem apenas ocasionalmente, pode utilizar um programa externo para os submeter.
   Consulte a sec&ccedil;&atilde;o <a href="#insert_char">Inser&ccedil;&atilde;o
   de Caracteres Especiais</a> para mais informa&ccedil;&otilde;es sobre
   alguns desses programas.
</p>
<p>Se tiver d&uacute;vidas quanto a um determinado caracter ou acentua&ccedil;&atilde;o, sinalize
   a d&uacute;vida com uma [**nota] para chamar a aten&ccedil;&atilde;o do pr&oacute;ximo
   revisor ou p&oacute;s-processador para essa quest&atilde;o.
</p>
<p>Para aqueles caracteres um pouco mais complicados de submeter, como &eacute; o caso do &aacute;rabe, sinalize-o
   entre os s&iacute;mbolos apropriados: <kbd>[Arabic:&nbsp;**]</kbd>.
   Coloque <kbd>**</kbd> para que o p&oacute;s-processador possa revolver a quest&atilde;o posteriormente.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Voltar ao in&iacute;cio</a></p>


<h3><a name="supers">Texto Superescrito (Superscripts)</a></h3>
<p>Os livros antigos abreviam frequentemente palavras em
   contrac&ccedil;&otilde;es, e imprimem-nas como texto superescrito.
   Reveja estas contrac&ccedil;&otilde;es inserindo o caracter (<kbd>^</kbd>),
   seguido do texto superescrito. Se o superescrito se mantiver em mais do
   que um caracter, rodeie temb&eacute;m o texto com chavetas
   <kbd>{</kbd> e <kbd>}</kbd>. Por exemplo:
</p>
<table width="100%" align="center" border="1" cellpadding="4" cellspacing="0" summary="Superscripts example">
  <tbody>
    <tr><th align="left" bgcolor="cornsilk">Imagem Original:</th></tr>
    <tr>
      <td valign="top">Gen<sup>rl</sup> Washington defeated L<sup>d</sup> Cornwall's army.</td>
    </tr>
    <tr><th align="left" bgcolor="cornsilk">Texto Revisto Correctamente:</th></tr>
    <tr>
      <td valign="top"><kbd>Gen^{rl} Washington defeated L^d Cornwall's army.</kbd></td>
    </tr>
  </tbody>
</table>
<p>Se o texto superescrito for uma indica&ccedil;&atilde;o de nota
   de rodap&eacute;, consulte antes a sec&ccedil;&atilde;o
   <a href="#footnotes">Notas de Rodap&eacute;</a>.
</p>
<p>O Gestor de Projecto pode definir uma forma diferente de rever o texto
   superescrito nos <a href="#comments">Coment&aacute;rios do Projecto</a>.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Voltar ao in&iacute;cio</a></p>


<h3><a name="subscr">Texto Subescrito (Subscripts)</a></h3>
<p>O texto subescrito encontra-se geralmente em trabalhos
   cient&iacute;ficos, mas n&atilde;o &eacute; comum nos
   outros livros. Reveja-o colocando um caracter <kbd>_</kbd>,
   e rodeando o texto com chavetas <kbd>{</kbd> e <kbd>}</kbd>.
   Por exemplo:
</p>
<table width="100%" align="center" border="1" cellpadding="4" cellspacing="0" summary="Subscripts example">
  <tbody>
    <tr><th align="left" bgcolor="cornsilk">Imagem Original:</th></tr>
    <tr>
      <td valign="top">H<sub>2</sub>O.</td>
    </tr>
    <tr><th align="left" bgcolor="cornsilk">Texto Revisto Correctamente:</th></tr>
    <tr>
      <td valign="top"><kbd>H_{2}O.</kbd></td>
    </tr>
  </tbody>
</table>
<!-- END RR -->
<p class="backtotop"><a href="#top">Voltar ao in&iacute;cio</a></p>


<h3><a name="drop_caps">Letra Grande e Ornamentada no In&iacute;cio do Par&aacute;grafo</a></h3>
<p>Reveja as letras grandes e ornamentadas do in&iacute;cio do
   cap&iacute;tulo, sec&ccedil;&atilde;o ou par&aacute;grafo como se
   s&oacute; existisse a letra. N&atilde;o deixe de consultar tamb&eacute;m
   a sec&ccedil;&atilde;o <a href="#chap_head">T&iacute;tulos de
   Cap&iacute;tulo</a> das Regras de Revis&atilde;o.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Voltar ao in&iacute;cio</a></p>


<h3><a name="small_caps">Texto em Mai&uacute;sculas Mais Pequenas
    (<span style="font-variant: small-caps;">Small Caps)</span></a></h3>
<p>Por favor, reveja apenas os caracteres em
   <span style="font-variant: small-caps">Small Caps</span>
   (letras mai&uacute;sculas que s&atilde;o mais pequenas do que o habitual).
   N&atilde;o se preocupe com as altera&ccedil;&otilde;es de tamanho.
   Se o texto de OCR j&aacute; est&aacute; TODO EM MAI&Uacute;SCULAS, em
   Texto Mixto ou em min&uacute;sculas, deixe-o tal como est&aacute;.
   As small caps podem aparecer ocasionalmente entre <kbd>&lt;sc&gt;</kbd>
   e <kbd>&lt;/sc&gt;</kbd>; neste caso, consulte a sec&ccedil;&atilde;o
   <a href="#formatting">Formata&ccedil;&atilde;o</a>.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Voltar ao in&iacute;cio</a></p>


<table width="100%" border="0" cellspacing="0" cellpadding="6" summary="Paragraph-level proofreading">
  <tbody>
    <tr>
      <td bgcolor="silver"><h2 style="margin-bottom: 0; margin-top: 0;">Revis&atilde;o de Par&aacute;grafos:</h2></td>
    </tr>
  </tbody>
</table>


<h3><a name="line_br">Quebras de Linha</a></h3>
<p><b>Mantenha todas as quebra de linha existentes</b>, para
   que o pr&oacute;ximo revisor possa comparar
   as linhas do texto revisto e as linhas da imagem mais
   facilmente. Tenha particular aten&ccedil;&atilde;o ao juntar <a href="#eol_hyphen">palavras
   hifenizadas</a> ou ao mover <a href="#em_dashes">tra&ccedil;os</a>. Se o revisor
   anterior alterou as quebras de linha, por favor, recoloque-as
   de forma a representar a imagem mais fielmente.
</p>
<!-- END RR -->
<!-- We should have an example right here for this. -->
<p class="backtotop"><a href="#top">Voltar ao in&iacute;cio</a></p>


<h3><a name="chap_head">T&iacute;tulos de Cap&iacute;tulo</a></h3>
<p>Reveja os t&iacute;tulos de cap&iacute;tulo tal como surgem na imagem.
</p>
<p>Um t&iacute;tulo de cap&iacute;tulo pode come&ccedil;ar um pouco
   mais abaixo do que o <a href="#page_hf">cabe&ccedil;alho</a> e
   n&atilde;o ter o n&uacute;mero da p&aacute;gina na mesma linha.
   Os t&iacute;tulos de Cap&iacute;tulo s&atilde;o impressos geralmente em
   letra mai&uacute;scula; se assim for, mantenha-os desta forma.
</p>
<p>Preste aten&ccedil;&atilde;o &agrave; poss&iacute;vel falta de aspas
   no in&iacute;cio do primeiro par&aacute;grafo. Alguns editores podem
   n&atilde;o as ter inclu&iacute;do ou o OCR pode n&atilde;o ter captado
   devido ao in&iacute;cio em mai&uacute;sculas na imagem. Se o autor
   come&ccedil;ar o par&aacute;grafo com um di&aacute;logo, coloque
   aspas.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Voltar ao in&iacute;cio</a></p>


<h3><a name="para_space">Espa&ccedil;o entre Par&aacute;grafos e Avan&ccedil;os</a></h3>
<p>Coloque uma linha em branco para separar par&aacute;grafos, mesmo
   se estiver no in&iacute;cio de uma p&aacute;gina. N&atilde;o deve
   deixar espa&ccedil;o no in&iacute;cio dos
   par&aacute;grafos, mas se os avan&ccedil;os j&aacute; existirem,
   n&atilde;o &eacute; necess&aacute;rio remover esses espa&ccedil;os&mdash;isso pode ser
   feito automaticamente no p&oacute;s-processamento.
</p>
<p>Veja a imagem e o texto das <a href="#para_side">Notas (Sidenotes)</a> como
   exemplo.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Voltar ao in&iacute;cio</a></p>


<h3><a name="page_hf">Cabe&ccedil;alho e Rodap&eacute; da P&aacute;gina</a></h3>
<p>Remova o cabe&ccedil;alho e rodap&eacute; das p&aacute;ginas,
   mas <em>n&atilde;o</em> as <a href="#footnotes">notas
   de rodap&eacute;</a> do texto.
</p>
<p>O cabe&ccedil;alho est&aacute; situado normalmente no topo da
   imagem em que o n&uacute;mero da p&aacute;gina se encontra no lado oposto. O cabe&ccedil;alho pode
   ser igual ao longo de todo o livro (geralmente &eacute; o t&iacute;tulo
   e o nome do autor), de cada cap&iacute;tulo (geralmente o n&uacute;mero
   do cap&iacute;tulo), ou ser diferente em cada p&aacute;gina
   (descrevendo a ac&ccedil;&atilde;o dessa p&aacute;gina). Remova-os
   todos, inclusive o n&uacute;mero da p&aacute;gina.
   As linhas extra em branco devem ser removidas, excepto nos locais onde
   as adicionamos intencionalmente para revis&atilde;o. No entanto, as
   linhas em branco no final da p&aacute;gina podem ser mantidas&mdash;estas
   ser&atilde;o removidas assim que guardar a p&aacute;gina.
</p>
<p>Os rodap&eacute;s da p&aacute;gina encontram-se no final da imagem e podem
   conter um n&uacute;mero de p&aacute;gina ou outras sinaliza&ccedil;&otilde;es
   alheias, que n&atilde;o fazem parte daquilo que o autor escreveu.
</p>
<!-- END RR -->

<p>Um <a href="#chap_head">T&iacute;tulo do Cap&iacute;tulo</a> come&ccedil;a
   geralmente um pouco mais abaixo e n&atilde;o tem o
   n&uacute;mero da p&aacute;gina na mesma linha. Veja o exemplo em baixo:
</p>
<table width="100%" align="center" border="1" cellpadding="4" cellspacing="0" summary="Page Headers and Footers">
  <tbody>
    <tr><th align="left" bgcolor="cornsilk">Imagem Original:</th></tr>
    <tr align="left">
      <td width="100%" valign="top"><img src="../foot.png" alt="" width="500" height="860"><br>
      </td>
    </tr>
    <tr><th align="left" bgcolor="cornsilk">Texto Revisto Correctamente:</th></tr>
    <tr>
      <td width="100%" valign="top">
        <kbd>In the United States?[*] In a railroad? In a mining company?<br>
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
        </kbd>
      </td>
    </tr>
  </tbody>
</table>
<p class="backtotop"><a href="#top">Voltar ao in&iacute;cio</a></p>


<h3><a name="illust">Figuras</a></h3>
<p>Ignore as figuras, mas reveja as legendas como se encontram impressas,
   preservando as quebras de linha. Se a legenda estiver no meio de um
   par&aacute;grafo, insira linhas em branco para a separar do restante
   texto. O texto que pode ser (parte de) uma legenda deve ser inclu&iacute;do.
   S&atilde;o exemplos: "Veja a p&aacute;gina 66" ou um t&iacute;tulo
   dentro da figura.
</p>
<p>A maioria das p&aacute;ginas sem texto, que contenham uma imagem,
   estar&atilde;o representadas com <kbd>[Blank Page]</kbd>. Mantenha-as assim.
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
      <th align="left" bgcolor="cornsilk">Texto Revisto Correctamente:</th>
    </tr>
    <tr>
      <td width="100%" valign="top">
        <p><kbd>Martha told him that he had always been her ideal and<br>
        that she worshipped him.<br>
        <br>
        Frontispiece<br>
        Her Weight in Gold
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
      <th align="left" bgcolor="cornsilk">Texto Revisto Correctamente:</th>
    </tr>
    <tr valign="top">
      <td>
        <p><kbd>
        such study are due to Italians. Several of these instruments<br>
        have already been described in this journal, and on the present<br>
        </kbd></p>
        <p><kbd>FIG. 1.--APPARATUS FOR THE STUDY OF HORIZONTAL<br>
        SEISMIC MOVEMENTS.</kbd></p>
        <p><kbd>
        occasion we shall make known a few others that will<br>
        serve to give an idea of the methods employed.<br>
        </kbd></p>
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
<p>Reveja as notas de rodap&eacute; deixando o texto da nota no final
   da p&aacute;gina, e colocando uma indica&ccedil;&atilde;o no local onde
   &eacute; referenciada no texto.
</p>
<p>No texto principal, o caracter que sinaliza uma localiza&ccedil;&atilde;o de
   nota de rodap&eacute; deve ser rodeado de par&ecirc;nteses rectos (<kbd>[</kbd>
   e <kbd>]</kbd>), e ser colocado junto &agrave; palavra
   referente<kbd>[1]</kbd> ou &agrave; pontua&ccedil;&atilde;o,<kbd>[2]</kbd>
   como mostramos na imagem e nos dois exemplos desta frase.
   Estes sinalizadores de notas de rodap&eacute; podem ser n&uacute;meros,
   letras ou s&iacute;mbolos. No caso de um s&iacute;mbolo,
   ou de um conjunto de s&iacute;mbolos (*, &dagger;, &Dagger;,
   &sect;, etc.), substituimo-los a todos por <kbd>[*]</kbd> no texto, e <kbd>*</kbd> junto
   &agrave; nota de rodap&eacute;.
</p>
<p>No fim da p&aacute;gina, reveja o texto da nota de rodap&eacute; tal como foi impressa,
   preservando as quebras de linha. Antes da nota de rodap&eacute;,
   utilize a mesma sinaliza&ccedil;&atilde;o que utilizou para a sinalizar no
   texto. Utilize o mesmo caracter, sem os par&ecirc;nteses rectos ou qualquer
   outra pontua&ccedil;&atilde;o.
</p>
<p>Coloque as notas de rodap&eacute; pela ordem em que surgem
   no texto, com uma linha em branco antes de cada uma.
</p>
<!-- END RR -->
<p>N&atilde;o inclua nenhuma linha horizontal a separar as notas de rodap&eacute;
   do texto principal.
</p>
<p>As <b>Notas</b> s&atilde;o notas de rodap&eacute; que foram agrupadas
   no final do cap&iacute;tulo ou do livro, em vez de se situarem no final
   da p&aacute;gina. Estas s&atilde;o revistas da mesma forma. Onde
   encontrar uma refer&ecirc;ncia para uma nota, rodeie-a com <kbd>[</kbd> e
   <kbd>]</kbd>. Se estiver a rever uma das p&aacute;ginas finais que
   contenha notas, coloque uma linha em branco entre cada uma, para que
   fiquem separadas de forma clara.
</p>
<!-- Need an example of Endnotes, maybe? Good idea!-->

<p>As notas de rodap&eacute; em <a href="#tables">Tabelas</a> devem
   permanecer tal como surgem na imagem original.
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
    <tr>
      <th valign="top" align="left" bgcolor="cornsilk">Texto Revisto Correctamente:</th>
    </tr>
    <tr valign="top">
      <td>
        <kbd>The principal persons involved in this argument were Caesar[*], former military</kbd><br>
        <kbd>leader and Imperator, and the orator Cicero[*]. Both were of the aristocratic</kbd><br>
        <kbd>(Patrician) class, and were quite wealthy.</kbd><br>
        <br>
        <kbd>* Gaius Julius Caesar.</kbd><br>
        <br>
        <kbd>* Marcus Tullius Cicero.</kbd>
      </td>
    </tr>
  </tbody>
</table>
<br>
<table width="100%" align="center" border="1" cellpadding="4" cellspacing="0" summary="Footnote Example">
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
    <tr><th align="left" bgcolor="cornsilk">Texto Revisto Correctamente:</th></tr>
    <tr>
      <td valign="top">
        <kbd>
        Mary had a little lamb[1]<br>
        Whose fleece was white as snow<br>
        And everywhere that Mary went<br>
        The lamb was sure to go!<br>
        <br>
        1 This lamb was obviously of the Hampshire breed,<br>
        well known for the pure whiteness of their wool.<br>
        </kbd>
      </td>
    </tr>
  </tbody>
</table>
<p class="backtotop"><a href="#top">Voltar ao in&iacute;cio</a></p>


<h3><a name="para_side">Notas (Sidenotes)</a></h3>
<p>Alguns livros t&ecirc;m pequenas descri&ccedil;&otilde;es do
   par&aacute;grafo ao lado e ao longo do texto. Chamam-se notas
   (sidenotes). Reveja as notas tal como foram impressas, mantendo as
   quebras de linha (revendo a <a href="#eol_hyphen">hifeniza&ccedil;&atilde;o
   do final da linha</a> normalmente). Deixe uma linha em branco depois da
   nota, de forma a distingui-la do texto circundante. No OCR, as notas
   podem aparecer noutro lado, ou surgirem intercaladas com o restante texto.
   Separe-as de forma a que o texto da nota fique todo junto, mas
   n&atilde;o se preocupe com a sua posi&ccedil;&atilde;o na p&aacute;gina.
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
      <th align="left" bgcolor="cornsilk">Texto Revisto Correctamente:</th>
    </tr>
    <tr valign="top">
      <td width="100%">
        <p><kbd>
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
        </kbd></p>
      </td>
    </tr>
  </tbody>
</table>
<p class="backtotop"><a href="#top">Voltar ao in&iacute;cio</a></p>


<h3><a name="mult_col">V&aacute;rias Colunas</a></h3>
<p>Reveja o texto impresso em duas colunas, como se de uma &uacute;nica
   se tratasse. Coloque o texto da esquerda em primeiro lugar, depois o
   da seguinte e por a&iacute; adiante. N&atilde;o &eacute;
   necess&aacute;rio marcar o local onde as colunas estavam divididas:
   junte-as. Consulte o &uacute;ltimo exemplo da sec&ccedil;&atilde;o
   <a href="#para_side">Notas (Sidenotes)</a> para ver um exemplo de
   v&aacute;rias colunas.
</p>
<p>Consulte tamb&eacute;m as sec&ccedil;&otilde;es <a href="#bk_index">&Iacute;ndices</a>
   e <a href="#tables">Tabelas</a> das Regras de Revis&atilde;o.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Voltar ao in&iacute;cio</a></p>


<h3><a name="tables">Tabelas</a></h3>
<p>A fun&ccedil;&atilde;o do revisor &eacute; certificar-se de que a
   informa&ccedil;&atilde;o existente na tabela &eacute; revista
   correctamente. Coloque espa&ccedil;os entre os diferentes
   campos de cada linha da tabela, os suficientes para se distinguir onde
   come&ccedil;am e acabam, mas n&atilde;o se preocupe com o alinhamento preciso.
   Mantenha as quebras de linha (revendo a
   <a href="#eol_hyphen">hifeniza&ccedil;&atilde;o do final da linha</a>
   normalmente). Ignore os pontos ou outro tipo de pontua&ccedil;&atilde;o
   utilizada para alinhar os itens.
</p>
<p>As <b>notas de rodap&eacute;</b> de tabelas
   devem ser colocadas no s&iacute;tio onde aparecem na imagem. Para mais detalhes,
   consulte a sec&ccedil;&atilde;o <a href="#footnotes">Notas de Rodap&eacute;</a>.
</p>
<!-- END RR -->
<table width="100%" align="center" border="1" cellpadding="4" cellspacing="0" summary="Table Example">
  <tbody>
    <tr><th align="left" bgcolor="cornsilk">Imagem Original:</th></tr>
    <tr align="left">
      <td width="100%" valign="top"> <img src="../table2.png" alt="" width="500" height="304"><br>
      </td>
    </tr>
    <tr><th align="left" bgcolor="cornsilk">Texto Revisto Correctamente:</th></tr>
    <tr>
      <td width="100%" valign="top">
<pre><kbd>TABLE II.

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
</kbd></pre>
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
    <tr><th align="left" bgcolor="cornsilk">Texto Revisto Correctamente:</th></tr>
    <tr>
      <td width="100%" valign="top">
<pre><kbd>Agents.   Objects.
        {     1st person, I,       me,
            {  2d   "    thou,   thee,
Singular  {      "  mas.  {  he,   him,
           {  3d  "  fem.  {  she,   her,
         {            it,        it.

       {  1st person, we,         us,
Plural   {   2d   "  ye, or you,   you,
        {  3d  "   they,         them,
                  who,       whom.
</kbd></pre>
      </td>
    </tr>
  </tbody>
</table>
<p class="backtotop"><a href="#top">Voltar ao in&iacute;cio</a></p>


<h3><a name="poetry">Poesia/Epigramas</a></h3>
<p>Insira uma linha em branco no in&iacute;cio e outra no final da
   poesia/epigrama, para que os formatadores possam identificar onde
   come&ccedil;a e acaba. Deixe as linhas alinhadas &agrave; esquerda e mantenha as
   quebras de linha. Insira uma linha em
   branco entre estrofes, quando existir uma na imagem.
</p>
<p>A <a href="#line_no">Numera&ccedil;&atilde;o de Linhas</a> na poesia deve ser
   mantida.
</p>
<p>Consulte os <a href="#comments">Coment&aacute;rios do Projecto</a>
   que estiver a rever.
</p>
<!-- END RR -->

<table width="100%" align="center" border="1" cellpadding="4" cellspacing="0" summary="Poetry Example">
  <tbody>
    <tr><th align="left" bgcolor="cornsilk">Imagem Original:</th></tr>
    <tr align="left">
      <td width="100%" valign="top"> <img src="../poetry2.png" alt="" width="480" height="385"> <br>
      </td>
    </tr>
    <tr><th align="left" bgcolor="cornsilk">Texto Revisto Correctamente:</th></tr>
    <tr>
      <td width="100%" valign="top">
        <kbd>THE CHAMBERED NAUTILUS<br>
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
        Wrecked is the ship of pearl!</kbd>
      </td>
    </tr>
  </tbody>
</table>
<p class="backtotop"><a href="#top">Voltar ao in&iacute;cio</a></p>


<h3><a name="line_no">Numera&ccedil;&atilde;o de Linhas</a></h3>
<p>A numera&ccedil;&atilde;o de linhas &eacute; comum nos livros de poesia,
   e surgem junto &agrave; margem a cada cinco ou dez linhas. Mantenha-as,
   inserindo alguns espa&ccedil;os para a separar do resto do texto, na
   pr&oacute;pria linha, para que os formatadores possam
   encontr&aacute;-la facilmente. Uma vez que a poesia
   n&atilde;o ser&aacute; reformatada numa vers&atilde;o e-book, a
   numera&ccedil;&atilde;o de linha ser&aacute; &uacute;til para os
   leitores.
</p>
<!-- END RR -->
<!-- We need an example image and text for this. -->
<p class="backtotop"><a href="#top">Voltar ao in&iacute;cio</a></p>


<h3><a name="next_word">Palavra Isolada no Final da P&aacute;gina</a></h3>
<p>Reveja estes casos eliminando a palavra, mesmo que seja a segunda parte de uma palavra hifenizada.
</p>
<p>Em livros mais antigos, a palavra isolada no final da
   p&aacute;gina (chamada "catchword", normalmente impressa perto da
   margem direita) indica a primeira palavra da p&aacute;gina seguinte do
   livro (chamada "incipit"). Era utilizada para que o impressor
   imprimisse a p&aacute;gina seguinte (chamada "verso") correctamente,
   facilitando o trabalho dos ajudantes do impressor ao ordenar as
   p&aacute;ginas antes as encadernar, e para ajudar o leitor, evitando que
   saltesse p&aacute;ginas sem querer.
</p>
<!-- END RR -->
<!-- We need an example here! -->
<p class="backtotop"><a href="#top">Voltar ao in&iacute;cio</a></p>


<table width="100%" border="0" cellspacing="0" cellpadding="6" summary="Page-level proofreading">
  <tbody>
    <tr>
      <td bgcolor="silver"><h2 style="margin-bottom: 0; margin-top: 0;">Revis&atilde;o de P&aacute;ginas:</h2></td>
    </tr>
  </tbody>
</table>


<h3><a name="blank_pg">P&aacute;gina em Branco</a></h3>
<p>A maioria das p&aacute;ginas em branco, ou apenas com uma imagem,
   estar&aacute; sinalizada com <kbd>[Blank Page]</kbd>. Mantenha esta
   marca&ccedil;&atilde;o. Se existir uma p&aacute;gina em branco, em que
   esta marca&ccedil;&atilde;o n&atilde;o apare&ccedil;a n&atilde;o
   &eacute; necess&aacute;rio adicion&aacute;-la.
</p>
<p>Se houver algum texto para rever na &aacute;rea de texto, ou se
   tiver uma imagem sem texto, siga as instru&ccedil;&otilde;es
   da sec&ccedil;&atilde;o <a href="#bad_image">P&aacute;gina Danificada</a>
   ou <a href="#bad_text">Imagem N&atilde;o Corresponde ao Texto</a>.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Voltar ao in&iacute;cio</a></p>


<h3><a name="title_pg">P&aacute;gina de Rosto (Frente e Verso)</a></h3>
<p>Reveja todo o texto, tal como foi impresso na p&aacute;gina, mesmo que esteja
   tudo em mai&uacute;sculas, mai&uacute;sculas e min&uacute;sculas, etc.,
   incluindo os anos de publica&ccedil;&atilde;o ou direito de autor.
</p>
<p>Nos livros mais antigos, a primeira letra &eacute; geralmente
   representada por uma imagem grande e ornamentada&mdash;reveja como se estivesse
   apenas a letra.
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
      <th align="left" bgcolor="cornsilk">Texto Revisto Correctamente:</th>
    </tr>
    <tr>
      <td width="100%" valign="top">
        <p><kbd>GREEN FANCY</kbd>
        </p>
        <p><kbd>BY</kbd></p>
        <p><kbd>GEORGE BARR McCUTCHEON</kbd></p>
        <p><kbd>AUTHOR OF "GRAUSTARK," "THE HOLLOW OF HER HAND,"<br>
        "THE PRINCE OF GRAUSTARK," ETC.</kbd></p>
        <p><kbd>WITH FRONTISPIECE BY<br>
        C. ALLAN GILBERT</kbd></p>
        <p><kbd>NEW YORK<br>
        DODD, MEAD AND COMPANY<br>
        1917</kbd></p>
      </td>
    </tr>
  </tbody>
</table>
<p class="backtotop"><a href="#top">Voltar ao in&iacute;cio</a></p>


<h3><a name="toc">Tabela de Conte&uacute;dos</a></h3>
<p>Reveja a Tabela de Conte&uacute;dos tal como est&aacute; impressa no livro,
   mesmo que esteja tudo em mai&uacute;sculas, mai&uacute;sculas e
   min&uacute;sculas, etc. No caso de existirem palavras em
   <span style="font-variant: small-caps">Mai&uacute;sculas Mais Pequenas</span>,
   consulte a sec&ccedil;&atilde;o <a href="#small_caps">Texto em Mai&uacute;sculas
   Mais Pequenas (Small Capitals)</a>.
</p>
<p>Ignore pontos ou qualquer outra pontua&ccedil;&atilde;o utilizada para alinhar os n&uacute;meros
   de p&aacute;gina. Estes ser&atilde;o removidos posteriormente.
</p>
<!-- END RR -->

<table width="100%" align="center" border="1" cellpadding="4" cellspacing="0" summary="Table of Contents example">
  <tbody>
    <tr>
      <th align="left" bgcolor="cornsilk">Imagem Original:</th>
    </tr>
    <tr align="left">
      <td width="100%" valign="top"><img src="../tablec.png" alt="" width="500" height="650">
      </td>
    </tr>
    <tr>
      <th align="left" bgcolor="cornsilk">Texto Revisto Correctamente:</th>
    </tr>
    <tr>
      <td width="100%" valign="top">
        <p><kbd>CONTENTS</kbd></p>
        <p><kbd>
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
        OUTSIDE&nbsp;&nbsp;&nbsp;,,,..&nbsp;&nbsp;....,&nbsp;221</kbd></p>
      </td>
    </tr>
  </tbody>
</table>
<p class="backtotop"><a href="#top">Voltar ao in&iacute;cio</a></p>


<h3><a name="bk_index">&Iacute;ndices</a></h3>
<p>N&atilde;o &eacute; necess&aacute;rio alinhar os n&uacute;meros como
   surgem na imagem; certifique-se apenas que os n&uacute;meros e a
   pontua&ccedil;&atilde;o correspondem aos da imagem e mantenha
   as quebras de linha.
</p>
<p>A formata&ccedil;&atilde;o espec&iacute;fica do &iacute;ndice ocorrer&aacute;
   mais tarde. A fun&ccedil;&atilde;o do revisor &eacute;
   certificar-se de que o texto e os n&uacute;meros est&atilde;o
   correctos.
</p>
<p>Consulte a sec&ccedil;&atilde;o <a href="#mult_col">V&aacute;rias Colunas</a>.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Voltar ao in&iacute;cio</a></p>


<h3><a name="play_n">Pe&ccedil;as de Teatro: Nome de Actores/Marca&ccedil;&otilde;es C&eacute;nicas</a></h3>
<p>Num di&aacute;logo, cada vez que mudar a personagem ou orador
   coloque uma linha em branco a separar (um por par&aacute;grafo).
   Se o nome do orador estiver numa linha pr&oacute;pria, reveja-a tamb&eacute;m
   como um par&aacute;grafo separado.
</p>
<p>As marca&ccedil;&otilde;es c&eacute;nicas s&atilde;o revistas
   tal como surgem no texto original.
   Se a marca&ccedil;&atilde;o c&eacute;nica surge isolada numa linha,
   reveja-a desta forma; se estiver no final da linha de um
   di&aacute;logo, deixe-a l&aacute;.
   Frequentemente come&ccedil;am com um par&ecirc;ntese aberto, n&atilde;o
   sendo fechado posteriormente. Por favor mantenha assim:
   n&atilde;o feche os par&ecirc;nteses.
</p>
<p>Por vezes, especialmente em pe&ccedil;as com m&eacute;trica, uma
   palavra pode ser quebrada devido a uma limita&ccedil;&atilde;o de
   espa&ccedil;o, sendo colocada imediatamente acima ou abaixo, precedida
   de um par&ecirc;nteses aberto, em vez de ter uma linha
   pr&oacute;pria. Por favor, junte as duas partes da palavra como &eacute; regra
   em casos de <a href="#eol_hyphen">hifeniza&ccedil;&atilde;o no final da linha</a>.
   Veja o <a href="#play4">exemplo</a>.
</p>
<p>Por favor, verifique se o Gestor de Projecto n&atilde;o especificou
   uma forma de revis&atilde;o diferente nos <a href="#comments">Coment&aacute;rios
   do Projecto</a>.
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
      <th align="left" bgcolor="cornsilk">Texto Revisto Correctamente:</th>
    </tr>
    <tr>
      <td width="100%" valign="top">
        <p><kbd>
        Has not his name for nought, he will be trode upon:<br>
        What says my Printer now?
        </kbd></p><p><kbd>
        Clow. Here's your last Proof, Sir.<br>
        You shall have perfect Books now in a twinkling.
        </kbd></p><p><kbd>
        Lap. These marks are ugly.
        </kbd></p><p><kbd>
        Clow. He says, Sir, they're proper:<br>
        Blows should have marks, or else they are nothing worth.
        </kbd></p><p><kbd>
        La. But why a Peel-crow here?
        </kbd></p><p><kbd>
        Clow. I told 'em so Sir:<br>
        A scare-crow had been better.
        </kbd></p><p><kbd>
        Lap. How slave? look you, Sir,<br>
        Did not I say, this Whirrit, and this Bob,<br>
        Should be both Pica Roman.
        </kbd></p><p><kbd>
        Clow. So said I, Sir, both Picked Romans,<br>
        And he has made 'em Welch Bills,<br>
        Indeed I know not what to make on 'em.
        </kbd></p><p><kbd>
        Lap. Hay-day; a Souse, Italica?
        </kbd></p><p><kbd>
        Clow. Yes, that may hold, Sir,<br>
        Souse is a bona roba, so is Flops too.</kbd></p>
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
      <th align="left" bgcolor="cornsilk">Texto Revisto Correctamente:</th>
    </tr>
    <tr>
      <td width="100%" valign="top">
        <p><kbd>
        Am. Sure you are fasting;<br>
        Or not slept well to night; some dream (Ismena?)<br>
        <br>
        Ism. My dreams are like my thoughts, honest and innocent,<br>
        Yours are unhappy; who are these that coast us?<br>
        You told me the walk was private.<br>
        </kbd></p>
      </td>
    </tr>
  </tbody>
</table>
<p class="backtotop"><a href="#top">Voltar ao in&iacute;cio</a></p>


<h3><a name="anything">Qualquer outra situa&ccedil;&atilde;o que
   necessite de um tratamento especial ou que suscite d&uacute;vidas</a></h3>
<p>Se ao rever, encontrar alguma situa&ccedil;&atilde;o que n&atilde;o
   esteja descrita neste documento, e que ache que merece um tratamento
   especial ou que n&atilde;o saiba ao certo como rever, coloque a sua
   quest&atilde;o, indicando o n&uacute;mero da imagem .png (p&aacute;gina),
   na <a href="#forums">Discuss&atilde;o do Projecto</a>.
</p>
<p>Deve tamb&eacute;m colocar uma nota no texto revisto para explicar ao revisor
   seguinte, ao formatador e ao p&oacute;s-processador qual o problema ou quest&atilde;o.
   Coloque a sua nota a seguir a um par&ecirc;ntese recto e dois asteriscos <kbd>[**</kbd>
   e termine-a fechando o par&ecirc;ntese recto <kbd>]</kbd>. Esta
   ac&ccedil;&atilde;o far&aacute; a nota sobressair do texto do autor, e
   alertar&aacute; o revisor seguinte para uma compara&ccedil;&atilde;o
   mais cuidadosa entre esta parte do texto e a imagem correspondente,
   de forma a resolver o problema. Deve tamb&eacute;m identificar a ronda
   em que est&aacute; a trabalhar antes do <kbd>]</kbd>, para que os
   volunt&aacute;rios seguintes saibam quem foi.
   Qualquer coment&aacute;rio deixado por um revisor anterior <b>tem de</b>
   ser mantido. Consulte a sec&ccedil;&atilde;o seguinte para mais
   informa&ccedil;&otilde;es.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Voltar ao in&iacute;cio</a></p>


<h3><a name="prev_notes">Notas de Revisores Precedentes</a></h3>
<p>Quaisquer notas ou coment&aacute;rios de um revisor
   precedente <b>n&atilde;o devem</b> ser removidos. Pode
   concordar ou discordar dessa nota/coment&aacute;rio, mas mesmo que
   saiba a resposta, nunca o remova. Se souber a fonte que esclarece a
   quest&atilde;o, por favor cite-a na nota, para que o
   p&oacute;s-processador possa referi-la.
</p>
<p>Se se cruzar com uma nota de um
   volunt&aacute;rio precedente e se souber responder, por favor, dedique um
   pouco do seu tempo a responder-lhe, clicando no seu nick vis&iacute;vel
   no ecr&atilde; de revis&atilde;o e enviando-lhe uma mensagem explicando
   como pode resolver a situa&ccedil;&atilde;o futuramente. Por favor,
   como j&aacute; foi referido anteriormente, n&atilde;o remova a nota.
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


<h3><a name="formatting">Formata&ccedil;&atilde;o</a></h3>
<p>Por vezes encontrar&aacute; elementos de formata&ccedil;&atilde;o
   j&aacute; presentes no texto. <b>N&atilde;o adicione nem corrija essa
   formata&ccedil;&atilde;o</b>; os formatadores tratar&atilde;o disso
   posteriormente. No entanto, pode remov&ecirc;-la se interferir na sua
   revis&atilde;o. O bot&atilde;o <s>&lt;x&gt;</s> na interface de revis&atilde;o
   remove elementos de formata&ccedil;&atilde;o como &lt;i&gt; e &lt;b&gt;
   do texto destacado. Alguns exemplos de tarefas de formata&ccedil;&atilde;o
   incluem:
</p>
<ul>
  <li>Texto em &lt;i&gt;it&aacute;lico&lt;/i&gt;, &lt;b&gt;negrito&lt;/b&gt;, &lt;sc&gt;Small Caps&lt;/sc&gt;</li>
  <li>Texto espa&ccedil;ado</li>
  <li>Altera&ccedil;&otilde;es no tamanho da fonte</li>
  <li>Espa&ccedil;amento de t&iacute;tulos de cap&iacute;tulo e de sec&ccedil;&atilde;o</li>
  <li>Espa&ccedil;os extra, asteriscos, ou linhas entre par&aacute;grafos</li>
  <li>Notas de rodap&eacute; que se prolonguem por mais de uma p&aacute;gina</li>
  <li>Notas de rodap&eacute; sinalizadas com s&iacute;mbolos</li>
  <li>Figuras</li>
  <li>Posi&ccedil;&atilde;o das Notas (Sidenotes)</li>
  <li>Organiza&ccedil;&atilde;o de dados nas tabelas</li>
  <li>Avan&ccedil;os (em poesia ou noutro lado)</li>
  <li>Unir linhas longas em poesia e &ccedil;ndices</li>
</ul>
<p>Se o revisor anterior inseriu formata&ccedil;&atilde;o, por favor dedique
   um pouco do seu tempo a responder-lhe, clicando no seu nick vis&iacute;vel
   no ecr&atilde; de revis&atilde;o e enviando-lhe uma mensagem explicando como
   deve proceder futuramente. <b>Deixe a formata&ccedil;&atilde;o para as rondas
   de formata&ccedil;&atilde;o.</b>
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Voltar ao in&iacute;cio</a></p>


<h3><a name="common_OCR">Problemas Comuns de OCR</a></h3>
<p>O OCR tem alguma dificuldade em distinguir caracteres semelhantes.
   Eis alguns exemplos:
</p>
<ul>
  <li>O digito '1' (um), a letra min&uacute;scula 'l' (&eacute;le), e
    letra mai&uacute;scula 'I'.
    Lembre-se que em algumas fontes, o n&uacute;mero um pode parecer um
    <small>I</small> (como um 'i' em letra mai&uacute;scula mais pequena).</li>
  <li>O digito '0' (zero), e a letra mai&uacute;scula 'O'.</li>
  <li>Tra&ccedil;os &amp; h&iacute;fens: Reveja-os com
    aten&ccedil;&atilde;o&mdash;frequentemente, o texto de OCR tem apenas
    um h&iacute;fen para representar um tra&ccedil;o, quando deveria ter dois.
    Consulte as regras das sec&ccedil;&otilde;es referentes a
    <a href="#eol_hyphen">palavras hifenizadas</a> e
    <a href="#em_dashes">Tra&ccedil;os</a>, para mais informa&ccedil;&otilde;es.</li>
  <li>Par&ecirc;nteses ( ) e chavetas { }.</li>
</ul>
<p>D&ecirc; especial aten&ccedil;&atilde;o a estes erros. Na maioria das vezes,
   o contexto da frase &eacute; suficiente para perceber qual o caracter correcto,
   mas cuidado&mdash;muitas vezes a sua mente ter&aacute;
   tend&ecirc;ncia para 'corrigir' automaticamente &agrave; medida
   que vai lendo.
</p>
<p>Detectar este tipo de erros, torna-se mais f&aacute;cil se utilizar
   uma fonte mono-espa&ccedil;ada, como por exemplo <a href="../font_sample.php">DPCustomMono</a>
   ou Courier.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Voltar ao in&iacute;cio</a></p>


<h3><a name="OCR_scanno">Problemas de OCR: Erros de Leitura (Scannos)</a></h3>
<p>Outro problema comum do OCR &eacute; o reconhecimento incorrecto de
   caracteres. Chamamos a estes erros "scannos" ou "erros de leitura"
   (como "typos" ou "erros tipogr&aacute;ficos"). Este problema pode
   resultar numa palavra que:
</p>
<ul compact>
   <li>parece correcta &agrave; primeira vista, mas surge mal escrita.<br>
       Podem ser detectadas utilizando o <a href="../wordcheck-faq.php">corrector ortogr&aacute;fico</a> (WordCheck) do
       ecr&atilde; de revis&atilde;o.
   </li>
   <li>existe, mas que tem um significado diferente e que n&atilde;o
       corresponde &agrave; da imagem<br>
       Este tipo &eacute; mais subtil e apenas &eacute; detectado se
       algu&eacute;m de facto ler o texto.</li>
</ul>
<p>O exemplo mais frequente da segunda hip&oacute;tese &eacute; a palavra "uma"
   que &eacute; reconhecida muitas vezes como "urna." Outros exemplos:
   "delia" em vez de "della", "coin" em vez de "com",
   "era" em vez de "em". Este tipo de erros &eacute; mais
   dif&iacute;cil de detectar, tendo direito a um nome especial: "Stealth
   Scannos" (Furtivos). Coleccionamos exemplos <a href="<?php echo $Stealth_Scannos_URL; ?>">neste
   t&oacute;pico</a>.
</p>
<p>Detectar este tipo de erros, torna-se mais f&aacute;cil se utilizar
   uma fonte mono-espa&ccedil;ada, como por exemplo <a href="../font_sample.php">DPCustomMono</a>
   ou Courier. Para ajudar na revis&atilde;o, recomendamos a utiliza&ccedil;&atilde;o
   do <a href="../wordcheck-faq.php">WordCheck</a> (ou equivalente) em
   <?php echo $ELR_round->id; ?>, sendo necess&aacute;ria noutras rondas de
   revis&atilde;o.
</p>
<!-- END RR -->
<!-- More to be added.... -->
<p class="backtotop"><a href="#top">Voltar ao in&iacute;cio</a></p>


<h3><a name="OCR_raised_o">Problemas de OCR: Ser&aacute; que &deg; &ordm; s&atilde;o referentes a grau?</a></h3>
<p>Existem s&iacute;mbolos distintos que parecem semelhantes na imagem,
   e que o software de OCR interpreta como sendo iguais (geralmente de forma
   incorrecta):
</p>
<ul>
  <li>O s&iacute;mbolo do grau <kbd style="font-size:150%;">&deg;</kbd>:
      Deve ser utilizado apenas para indicar graus (de temperatura,
      de &acirc;ngulo, etc.).</li>
  <li>O 'o' superescrito: Na pr&aacute;tica todas as ocorr&ecirc;ncias
      de 'o' superescrito devem ser revistas como <kbd>^o</kbd>, de acordo com
      as regras de <a href="#supers">Texto Superescrito (Superscripts)</a>.</li>
  <li>O ordinal masculino <kbd style="font-size:150%;">&ordm;</kbd>:
      Reveja-o tamb&eacute;m como um 'o' superescrito, a n&atilde;o ser
      que o caracter especial seja solicitado nos <a href="#comments">Coment&eacute;rios
      do Projecto</a>. Pode ser utilizado em idiomas como o Castelhano e o
      Portugu&ecirc;s, sendo o equivalente para o -th em English (4th, 5th, etc.).
      &Eacute; precedido de n&uacute;meros e o equivalente feminino em superescrito
      &eacute; (<kbd>&ordf;</kbd>), devendo ser revisto como ^a.</li>
</ul>
<!-- END RR -->
<p class="backtotop"><a href="#top">Voltar ao in&iacute;cio</a></p>


<h3><a name="hand_notes">Notas Manuscritas no Livro</a></h3>
<p>N&atilde;o inclua notas manuscritas do livro (excepto se o
   texto manuscrito estiver a sobrepor caracteres que se tornaram menos
   evidentes, de forma a torn&aacute;-los mais vis&iacute;veis). N&atilde;o inclua
   notas manuscritas de leitores (por vezes surgem nas margens),
   etc.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Voltar ao in&iacute;cio</a></p>


<h3><a name="bad_image">Imagens Danificadas</a></h3>
<p>Se uma imagem estiver danificada (n&atilde;o carregar, ser ileg&iacute;vel, etc.),
   por favor, escreva uma mensagem sobre esta imagem na <a href="#forums">Discuss&atilde;o
   do Projecto</a> e clique no bot&atilde;o "Report
   Bad Page" (Reportar Uma P&aacute;gina Danificada), para que esta p&aacute;gina
   fique de 'quarentena'; em vez de a devolver &agrave; ronda.
   Se apenas uma pequena parte da imagem estiver ileg&iacute;vel,
   deixe uma nota como refer&iacute;mos <a href="#anything">acima</a>,
   e coloque-a na discuss&atilde;o do projecto, sem sinalizar a p&aacute;gina como
   imagem danificada. O bot&atilde;o "Bad Page" est&aacute; apenas dispon&iacute;vel
   na primeira ronda de revis&atilde;o, sendo importante resolver estas
   situa&ccedil;&otilde;es o quanto antes.
</p>
<p>Tenha em aten&ccedil;&atilde;o de que alguma imagens s&atilde;o
   muito grandes, sendo normal demorar um pouco, principalmente se tiver
   v&aacute;rias janelas abertas ou se o computador que estiver a usar
   j&aacute; n&atilde;o for recente. Antes de reportar esta imagem como
   sendo danificada, tente ajustar o zoom da imagem, fechar algumas janelas
   e programas, ou colocar a situa&ccedil;&atilde;o na discuss&atilde;o do projecto,
   para saber se mais algu&eacute;m tem o mesmo problema.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Voltar ao in&iacute;cio</a></p>


<h3><a name="bad_text">Imagem N&atilde;o Corresponde ao Texto</a></h3>
<p>Se a imagem n&atilde;o corresponder ao texto apresentado, por favor,
   escreva uma mensagem sobre esta imagem na <a href="#forums">discuss&atilde;o
   do projecto</a> e clique em e clique no bot&atilde;o "Report Bad Page"
   (Reportar Uma P&aacute;gina Danificada), para que esta p&aacute;gina fique
   de 'quarentena'; em vez de a devolver &agrave; ronda. O bot&atilde;o
   "Bad Page" est&aacute; apenas dispon&iacute;vel na primeira ronda
   de revis&atilde;o, sendo importante resolver estas situa&ccedil;&otilde;es
   o quanto antes.
</p>
<p>&Eacute; comum que o texto de OCR esteja na sua maioria correcto e
   que apenas faltem as primeiras linhas do texto. Por favor, reescreva
   a(s) linha(s) em falta. Se praticamente todas as linhas do texto
   estiverem em falta, reescreva toda a p&aacute;gina (se estiver
   disposto/a a faz&ecirc;-lo), ou clique no bot&atilde;o "Return Page to Round"
   (Devolver a P&aacute;gina &agrave; Ronda), e a p&aacute;gina ser&aacute;
   atribu&iacute;da a outra pessoa. Se existirem v&aacute;rias p&aacute;ginas
   nestas condi&ccedil;&otilde;es, coloque uma nota sobre o assunto na
   <a href="#forums">discuss&atilde;o do projecto</a> para alertar o
   Gestor de Projecto.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Voltar ao in&iacute;cio</a></p>


<h3><a name="round1">Erros do Revisor Precedente</a></h3>
<p>Se o revisor precedente cometeu v&aacute;rios erros, ou ignorou
   v&aacute;rias situa&ccedil;&otilde;es, por favor, dedique um pouco de
   tempo e d&ecirc;-lhe um Feedback (uma no&ccedil;&atilde;o do que
   fez) clicando no seu nick no ecr&atilde; de revis&atilde;o, e enviando
   uma mensagem privada a explicar como lidar com a
   situa&ccedil;&atilde;o, para que n&atilde;o a repita
   posteriormente.
</p>
<p><em>Por favor, seja simp&aacute;tico!</em> Todos n&oacute;s somos
   volunt&aacute;rios e damos todos o nosso melhor. O objectivo da sua
   mensagem de feedback deve ser inform&aacute;-los da forma correcta derever,
   e n&atilde;o uma cr&iacute;tica n&atilde;o construtiva. D&ecirc; um exemplo
   espec&iacute;fico do trabalho do volunt&aacute;rio em quest&atilde;o,
   mostrando o que fez e o que deveria ter feito.
</p>
<p>Se o revisor precedente tiver realizado um trabalho excelente, pode
   enviar-lhe uma mensagem a congratul&aacute;-lo&mdash;especialmente se
   tiver revisto uma p&aacute;gina particularmente dif&iacute;cil.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Voltar ao in&iacute;cio</a></p>


<h3><a name="p_errors">Erros de Impress&atilde;o/Ortografia</a></h3>
<p>Corrija todas as palavras que tenham sido lidas de forma
   incorrecta pelo OCR (scannos), mas n&atilde;o fa&ccedil;a
   correc&ccedil;&otilde;es do que lhe pare&ccedil;a erros
   ortogr&aacute;ficos ou de impress&atilde;o na imagem original. Muitos
   dos textos antigos cont&ecirc;m palavras escritas de forma diferente da
   que usamos actualmente, e n&oacute;s preservamos estes dizeres antigos,
   incluindo caracteres acentuados.
</p>
<p>Se tiver d&uacute;vidas, coloque uma nota no tetxo <kbd>[**typo deveria ser texto&nbsp;?]</kbd>
   e esclare&ccedil;a-se no t&oacute;pico de <a href="#forums">Discuss&atilde;o do Projecto</a>.
   Se alterar algo, inclua uma nota com uma descri&ccedil;&atilde;o do que
   alterou: <kbd>[**typo corrigido, alterado de "tetxo" para "texto"]</kbd>. Inclua
   <kbd>**</kbd>, para chamar a aten&ccedil;&atilde;o do p&oacute;s-processador.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Voltar ao in&iacute;cio</a></p>


<h3><a name="f_errors">Erros Factuais no Texto</a></h3>
<p>N&atilde;o corrija erros factuais existentes no livro.
   Muitos dos livros que revemos re&uacute;nem conte&uacute;dos que
   talvez j&aacute; n&atilde;o sejam correctos actualmente. Deixe-os como
   o autor as escreveu. Consulte a sec&ccedil;&atilde;o
   <a href="#p_errors">Erros de Impress&atilde;o/Ortografia</a>
   para saber como deixar uma nota se achar que o que est&aacute; impresso
   n&atilde;o &eacute; o que o autor pretendia transmitir.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Voltar ao in&iacute;cio</a></p>


<h3><a name="insert_char">Inser&ccedil;&atilde;o de Caracteres Especiais</a></h3>
<p>Se n&atilde;o existirem no seu teclado, existem v&aacute;rias formas de
   adicionar caracteres especiais:
</p>
<ul compact>
  <li>Os menus da interface de revis&atilde;o.</li>
  <li>Aplica&ccedil;&otilde;es existentes no seu sistema operativo.
    <ul compact>
      <li>Windows: "Mapa de Caracteres"<br>
          Acess&iacute;vel atrav&eacute;s de:<br>
          In&iacute;cio: Executar: charmap, ou<br>
          In&iacute;cio: Acess&oacute;rios: Ferramentas de Sistema: Mapa de Caracteres.</li>
      <li>Macintosh: Key Caps ou "Keyboard Viewer"<br>
          Nos OS 9 &amp; anteriores, encontra-se no Apple Menu,<br>
          Nos OS X at&eacute; 10.2, est&aacute; em Applications, Utilities folder<br>
          Nos OS X 10.3 e posterior, est&aacute; no Input Menu como "Keyboard Viewer."</li>
      <li>Linux: O nome e localiza&ccedil;&atilde;o do seleccionador de caracteres
          varia consoante o ambiente de trabalho.</li>
    </ul>
  </li>
  <li>Uma aplica&ccedil;&atilde;o em linha.</li>
  <li>Os atalhos do teclado.<br>
      (Consulte as tabelas para <a href="#a_chars_win">Windows</a> e <a href="#a_chars_mac">Macintosh</a>
      abaixo.)</li>
  <li>Mudar para uma localiza&ccedil;&atilde;o ("locale") ou
      configura&ccedil;&atilde;o de teclado que suporte caracteres acentuados ("deadkeys")
    <ul compact>
      <li>Windows: Painel de Controlo (Teclado, Op&ccedil;&otilde;es Regionais e de Idioma)</li>
      <li>Macintosh: Input Menu (na Barra de Menu)</li>
      <li>Linux: Modifique o teclado na sua configura&ccedil;&atilde;o X.</li>
    </ul>
  </li>
</ul>
<!-- END RR -->

<a name="a_chars_win"></a>
<p><b>Para Windows</b>:
</p>
<ul compact>
  <li>Pode utilizar o Mapa de Caracteres (In&iacute;cio:
      Executar: charmap) para seleccionar uma letra individual,
      utilizando o "corta &amp; cola".
  </li>
  <li>Os menus da interface de revis&atilde;o.
  </li>
  <li>Ou pode premir em Alt+um atalho de
      c&oacute;digo NumberPad destes caracteres.
      Este m&eacute;todo &eacute; mais r&aacute;pido do que o uso do "corta &amp;
      cola", quando j&aacute; souber os c&oacute;digos.<br>
      Prima e mantenha premida a tecla Alt, enquanto escreve os quatro
      d&iacute;gitos no <i>Number Pad</i>, soltando-a depois desta
      ac&ccedil;&atilde;o; tenha em aten&ccedil;&atilde;o de que os
      n&uacute;meros por cima das letras n&atilde;o funcionar&atilde;o para
      esta ac&ccedil;&atilde;o. <br>
      Deve inserir os 4 d&iacute;gitos, incluindo o 0 (zero) quando for &agrave;
      esquerda. Repare que a vers&atilde;o das letras em mai&uacute;scula
      &eacute; menos 32 que a vers&atilde;o em letra min&uacute;scula.<br>
      Estas instru&ccedil;&otilde;es s&atilde;o para o formato de
      teclado US-English. Pode n&atilde;o funcionar para outros formatos. <br>
      (<a href="../charwin.pdf">Clique para imprimir esta tabela</a>)
  </li>
</ul>

<br>
<table align="center" border="6" rules="all" summary="Windows shortcuts">
  <tbody>
  <tr>
      <th colspan="14" bgcolor="cornsilk">Atalhos de Windows para os s&iacute;mbolos Latin-1</th>
  </tr>
  <tr bgcolor="cornsilk">
      <th colspan="2">` grave</th>
      <th colspan="2">&acute; agudo</th>
      <th colspan="2">^ circunflexo</th>
      <th colspan="2">~ til</th>
      <th colspan="2">&uml; trema</th>
      <th colspan="2">&deg; anel</th>
      <th colspan="2">&AElig; ligadura</th>
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
      <th colspan="2" bgcolor="cornsilk">/ barra</th>
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
      <th colspan="2" bgcolor="cornsilk">moeda</th>
      <th colspan="2" bgcolor="cornsilk">matem&aacute;tica</th>
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
  <tr><th colspan="2" bgcolor="cornsilk">cedilha</th>
      <th colspan="2" bgcolor="cornsilk">Island&ecirc;s</th>
      <th colspan="2" bgcolor="cornsilk">sinais</th>
      <th colspan="2" bgcolor="cornsilk">acentos</th>
      <th colspan="2" bgcolor="cornsilk">pontua&ccedil;&atilde;o</th>
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
  <tr><th colspan="2" bgcolor="cornsilk">superescritos</th>
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
      <th colspan="2" bgcolor="cornsilk">ordinais</th>
      <td align="center" bgcolor="mistyrose" title="1/4 Fraction"          >&frac14; </td><td>Alt-0188&nbsp;&dagger;</td>
  </tr>
  <tr><td align="center" bgcolor="mistyrose" title="superscript 2"         >&sup2;   </td><td>Alt-0178&nbsp;*</td>
      <th colspan="2" bgcolor="cornsilk">ligadura sz</th>
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
<p>* N&atilde;o utilize s&iacute;mbolos de ordinal ou superescrito,
   excepto quando solicitado nos <a href="#comments">Coment&aacute;rios
   do Projecto</a>. Por norma siga as regras relativas ao
   <a href="#supers">Texto Superescrito</a>. (x^2, f^o, etc.)
</p>
<p>&dagger; N&atilde;o utilize estes
   s&iacute;mbolos de frac&ccedil;&otilde;es, excepto quando solicitado
   nos <a href="#comments">Coment&aacute;rios do Projecto</a>. Por
   norma siga as regras relativas &agrave;s <a href="#fract_s">Frac&ccedil;&otilde;es</a>.
   (1/2, 1/4, 3/4, etc.)
</p>
<p><b>Para Apple Macintosh</b>:
</p>
<ul compact>
  <li>Pode utilizar a aplica&ccedil;&atilde;o de
      "Key Caps" como refer&ecirc;ncia.<br>
      Nos OS 9 &amp; anteriores, encontra-se no Apple Menu; no OS X
      at&eacute; 10.2, est&aacute; em Applications, Utilities folder.<br>
      Surgir&aacute; uma imagem representativa do teclado, e
      premindo shift, opt, command, ou combina&ccedil;&otilde;es destas
      teclas mostrar&aacute; como se pode reproduzir cada caracter. Utilize
      esta refer&ecirc;ncia para perceber como escrever um determinado
      car&aacute;cter, ou utilize o "corta &amp; cola" daqui para o texto no
      ecr&atilde; de revis&atilde;o.</li>
  <li>Nos OS X 10.3 e posterior, a mesma fun&ccedil;&atilde;o &eacute;
      agora uma palete dispon&iacute;vel a partir do Input menu (o menu
      drop-down anexada ao seu locale's flag icon na barra
      de menu). Est&aacute; identificada como "Show Keyboard
      Viewer." Se este n&atilde;o estiver no seu Input menu, ou se n&atilde;o
      tiver esse menu, pode activ&aacute;-lo nas System
      Preferences, "International" panel, e seleccinando a
      op&ccedil;&atilde;o "Input Menu". Verifique que a
      op&ccedil;&atilde;o "Show input menu in menu bar" &eacute;
      seleccionada. Na spreadsheet view, procure "Keyboard Viewer"
      para al&eacute;m de qualquer configura&ccedil;&atilde;o de
      localiza&ccedil;&atilde;o ("locales") que use.
  </li>
  <li>Os menus da interface de revis&atilde;o.
  </li>
  <li>Ou pode sempre utilizar os atalhos da Apple para estes caracteres.
      <br>Este processo torna-se muito mais r&aacute;pido do que o corte &amp; cola,
          quando souber os atalhos de cor.
      <br>Prima a tecla Opt enquanto escreve o acento e depois a letra
          a ser acentuada (ou, em alguns casos, prima a tecla Opt e escreva o
          s&iacute;mbolo).
      <br>Estas instru&ccedil;&otilde;es s&atilde;o para o formato de
          teclado US-English. Pode n&atilde;o funcionar para outros
          formatos.
      <br>(<a href="../charapp.pdf">Clique para imprimir esta tabela</a>)
  </li>
</ul>

<br>
<a name="a_chars_mac"></a>
<table align="center" border="6" rules="all" summary="Mac shortcuts">
  <tbody>
  <tr bgcolor="cornsilk">
      <th colspan="14">Atalhos de Apple Mac para s&iacute;mbolos Latin-1</th>
  </tr>
  <tr bgcolor="cornsilk">
      <th colspan="2">` grave</th>
      <th colspan="2">&acute; agudo</th>
      <th colspan="2">^ circunflexo</th>
      <th colspan="2">~ til</th>
      <th colspan="2">&uml; trema</th>
      <th colspan="2">&deg; anel</th>
      <th colspan="2">&AElig; ligadura</th>
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
      <th colspan="2" bgcolor="cornsilk">/ barra</th>
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
      <th colspan="2" bgcolor="cornsilk">moeda</th>
      <th colspan="2" bgcolor="cornsilk">matem&aacute;tica</th>
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
      <td align="center" bgcolor="mistyrose" title="Multiplication"        >&times;  </td><td>(nenhum)&nbsp;&Dagger;</td>
  </tr>
  <tr><th colspan="2" bgcolor="cornsilk">cedilha</th>
      <th colspan="2" bgcolor="cornsilk">Island&ecirc;s</th>
      <th colspan="2" bgcolor="cornsilk">sinais</th>
      <th colspan="2" bgcolor="cornsilk">acentos</th>
      <th colspan="2" bgcolor="cornsilk">pontua&ccedil;&atilde;o</th>
      <td align="center" bgcolor="mistyrose" title="Yen"                   >&yen;    </td><td>Opt-y   </td>
      <td align="center" bgcolor="mistyrose" title="Division"              >&divide; </td><td>Opt-/   </td>
  </tr>
  <tr><td align="center" bgcolor="mistyrose" title="Small c cedilla"       >&ccedil; </td><td>Opt-c   </td>
      <td align="center" bgcolor="mistyrose" title="Capital Thorn"         >&THORN;  </td><td>(nenhum)&nbsp;&Dagger;</td>
      <td align="center" bgcolor="mistyrose" title="Copyright"             >&copy;   </td><td>Opt-g   </td>
      <td align="center" bgcolor="mistyrose" title="acute accent"          >&acute;  </td><td>Opt-E   </td>
      <td align="center" bgcolor="mistyrose" title="Inverted Question Mark">&iquest; </td><td>Opt-?   </td>
      <td align="center" bgcolor="mistyrose" title="General Currency"      >&curren; </td><td>(nenhum)&nbsp;&Dagger;</td>
      <td align="center" bgcolor="mistyrose" title="Logical Not"           >&not;    </td><td>Opt-l   </td>
  </tr>
  <tr><td align="center" bgcolor="mistyrose" title="Capital C cedilla"     >&Ccedil; </td><td>Opt-C   </td>
      <td align="center" bgcolor="mistyrose" title="Small thorn"           >&thorn;  </td><td>(nenhum)&nbsp;&Dagger;</td>
      <td align="center" bgcolor="mistyrose" title="Registration Mark"     >&reg;    </td><td>Opt-r   </td>
      <td align="center" bgcolor="mistyrose" title="umlaut accent"         >&uml;    </td><td>Opt-U   </td>
      <td align="center" bgcolor="mistyrose" title="Inverted Exclamation"  >&iexcl;  </td><td>Opt-1   </td>
      <td> </td><td> </td>
      <td align="center" bgcolor="mistyrose" title="Degrees"               >&deg;    </td><td>Shift-Opt-8   </td>
  </tr>
  <tr><th colspan=2 bgcolor="cornsilk">superescrito        </th>
      <td align="center" bgcolor="mistyrose" title="Capital Eth"           >&ETH;    </td><td>(nenhum)&nbsp;&Dagger;</td>
      <td align="center" bgcolor="mistyrose" title="Paragraph (pilcrow)"   >&para;   </td><td>Opt-7   </td>
      <td align="center" bgcolor="mistyrose" title="macron accent"         >&macr;   </td><td>Shift-Opt-,</td>
      <td align="center" bgcolor="mistyrose" title="guillemet left"        >&laquo;  </td><td>Opt-\   </td>
      <td> </td><td> </td>
      <td align="center" bgcolor="mistyrose" title="Micro"                 >&micro;  </td><td>Opt-m   </td>
  </tr>
  <tr><td align="center" bgcolor="mistyrose" title="superscript 1"         >&sup1;   </td><td>(nenhum)&nbsp;*&Dagger;</td>
      <td align="center" bgcolor="mistyrose" title="Small eth"             >&eth;    </td><td>(nenhum)&nbsp;&Dagger;</td>
      <td align="center" bgcolor="mistyrose" title="Section"               >&sect;   </td><td>Opt-6   </td>
      <td align="center" bgcolor="mistyrose" title="cedilla"               >&cedil;  </td><td>Opt-Z   </td>
      <td align="center" bgcolor="mistyrose" title="guillemet right"       >&raquo;  </td><td>Shift-Opt-\</td>
      <th colspan="2" bgcolor="cornsilk">ordinais</th>
      <td align="center" bgcolor="mistyrose" title="1/4 Fraction"          >&frac14; </td><td>(nenhum)&nbsp;&dagger;&Dagger;</td>
  </tr>
  <tr><td align="center" bgcolor="mistyrose" title="superscript 2"         >&sup2;   </td><td>(nenhum)&nbsp;*&Dagger;</td>
      <th colspan="2" bgcolor="cornsilk">ligadura sz</th>
      <td align="center" bgcolor="mistyrose" title="Broken Vertical bar"   >&brvbar; </td><td>(nenhum)&nbsp;&Dagger;  </td>
      <td> </td><td> </td>
      <td align="center" bgcolor="mistyrose" title="Middle dot"            >&middot; </td><td>Shift-Opt-9  </td>
      <td align="center" bgcolor="mistyrose" title="Masculine Ordinal"     >&ordm;   </td><td>Opt-0&nbsp;*</td>
      <td align="center" bgcolor="mistyrose" title="1/2 Fraction"          >&frac12; </td><td>(nenhum)&nbsp;&dagger;&Dagger;</td>
  </tr>
  <tr><td align="center" bgcolor="mistyrose" title="superscript 3"         >&sup3;   </td><td>(nenhum)&nbsp;*&Dagger;  </td>
      <td align="center" bgcolor="mistyrose" title="sz ligature"           >&szlig;  </td><td>Opt-s   </td>
      <td> </td><td> </td>
      <td> </td><td> </td>
      <td> </td><td> </td>
      <td align="center" bgcolor="mistyrose" title="Feminine Ordinal"      >&ordf;   </td><td>Opt-9&nbsp;*</td>
      <td align="center" bgcolor="mistyrose" title="3/4 Fraction"          >&frac34; </td><td>(nenhum)&nbsp;&dagger;&Dagger;</td>
  </tr>
  </tbody>
</table>
<p>* N&atilde;o utilize s&iacute;mbolos de ordinal ou superescrito,
   excepto quando solicitado nos <a href="#comments">Coment&aacute;rios
   do Projecto</a>. Por norma siga as regras relativas ao
   <a href="#supers">Texto Superescrito</a>. (x^2, f^o, etc.)
</p>
<p>&dagger; N&atilde;o utilize estes s&iacute;mbolos
   de frac&ccedil;&otilde;es, excepto quando solicitado
   nos <a href="#comments">Coment&aacute;rios do Projecto</a>. Por
   norma siga as regras relativas &agrave;s <a href="#fract_s">Frac&ccedil;&otilde;es</a>.
   (1/2, 1/4, 3/4, etc.)
</p>
<p>&Dagger;&nbsp;Nota: Sem atalho correspondente; utilize os menus
   da interface de revis&atilde;o se necess&aacute;rio.
</p>
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
        <li><a href="#double_q">Aspas</a></li>
        <li><a href="#chap_head">Aspas, a faltar no in&iacute;cio do cap&iacute;tulo</a></li>
        <li><a href="#quote_ea">Aspas em Cada Linha</a></li>
        <li><a href="#chap_head">Aspas, em falta no in&iacute;cio do cap&iacute;tulo</a></li>
        <li><a href="#insert_char">Atalhos de Teclado para Caracteres Latin-1</a></li>
        <li><a href="#insert_char">Atalhos para Caracteres Latin-1</a></li>
        <li><a href="#extra_sp">Avan&ccedil;os</a></li>
        <li><a href="#para_space">Avan&ccedil;os, Par&aacute;grafo</a></li>
        <li><a href="#page_hf">Cabe&ccedil;alhos, P&aacute;gina</a></li>
        <li><a href="#page_hf">Cabe&ccedil;alhos/Rodap&eacute;s</a></li>
        <li><a href="#a_chars">Caracteres Acentuados/Non-ASCII</a></li>
        <li><a href="#d_chars">Caracteres com Sinais Diacr&iacute;ticos</a></li>
        <li><a href="#insert_char">Caracteres Espaciais, Inser&ccedil;&atilde;o</a></li>
        <li><a href="#insert_char">Caracteres Latin-1, Inser&ccedil;&atilde;o</a></li>
        <li><a href="#a_chars">Caracteres Non-ASCII</a></li>
        <li><a href="#a_chars">Caracteres, Non-ASCII/Acentuados</a></li>
        <li><a href="#f_chars">Caracteres Non-Latin</a></li>
        <li><a href="#next_word">Catchwords</a></li>
        <li><a href="#mult_col">Colunas, M&uacute;ltiplas</a></li>
        <li><a href="#comments">Coment&aacute;rios do Projecto</a></li>
        <li><a href="#prev_notes">Coment&aacute;rios, Revisores Anteriores</a></li>
        <li><a href="#toc">Conte&uacute;dos, Tabela de</a></li>
        <li><a href="#contract">Contrac&ccedil;&otilde;es</a></li>
        <li><a href="#prev_pg">Corrigir Erros de P&aacute;ginas Anteriroes</a></li>
        <li><a href="#bad_image">Danificada, Imagem</a></li>
        <li><a href="#play_n">Direc&ccedil;&otilde;es C&eacute;nicas (Pe&ccedil;as)</a></li>
        <li><a href="#forums">Discuss&atilde;o do Projecto</a></li>
        <li><a href="#play_n">Drama</a></li>
        <li><a href="#drop_caps">Drop Cap</a></li>
        <li><a href="#period_p">Elipses</a></li>
        <li><a href="#blank_pg">Em Branco, P&aacute;gina</a></li>
        <li><a href="#anything">Em caso de d&uacute;vida</a></li>
        <li><a href="#poetry">Epigramas</a></li>
        <li><a href="#p_errors">Erros de Impress&atilde;o</a></li>
        <li><a href="#round1">Erros do Revisor Anterior</a></li>
        <li><a href="#f_errors">Erros, Factuais</a></li>
        <li><a href="#f_errors">Erros Factuais no Texto</a></li>
        <li><a href="#p_errors">Erros, Impress&atilde;o</a></li>
        <li><a href="#p_errors">Erros Impressos</a></li>
        <li><a href="#round1">Erros, Revisor Precedente</a></li>
        <li><a href="#trail_s">Espa&ccedil;o no Fim da Linha</a></li>
        <li><a href="#trail_s">Espa&ccedil;o no Final da Linha</a></li>
        <li><a href="#extra_sp">Espa&ccedil;os Extra Entre Palavras</a></li>
        <li><a href="#punctuat">Espa&ccedil;os na Pontua&ccedil;&atilde;o</a></li>
        <li><a href="#trail_s">Espa&ccedil;os no Final da Linha</a></li>
        <li><a href="#para_space">Espa&ccedil;os/Avan&ccedil;os do Par&aacute;grafo</a></li>
        <li><a href="#extra_sp">Extra, Espa&ccedil;os</a></li>
        <li><a href="#illust">Figuras</a></li>
        <li><a href="#period_s">Finais, Pontos</a></li>
        <li><a href="#formatting">Formata&ccedil;&atilde;o</a></li>
        <li><a href="#formatting">Formata&ccedil;&atilde;o Pr&eacute;-existente</a></li>
        <li><a href="#forums">Forum</a></li>
        <li><a href="#fract_s">Frac&ccedil;&otilde;es</a></li>
        <li><a href="#summary">Guia Pr&aacute;tico de Revis&atilde;o</a></li>
        <li><a href="#eol_hyphen">Hifeniza&ccedil;&atilde;o, Final da Linha</a></li>
        <li><a href="#eop_hyphen">Hifeniza&ccedil;&atilde;o, Final da P&aacute;gina</a></li>
        <li><a href="#em_dashes">H&iacute;fens</a></li>
        <li><a href="#bad_image">Imagem Danificada</a></li>
        <li><a href="#bad_text">Imagem N&atilde;o Corresponde ao Texto</a></li>
        <li><a href="#bad_text">Imagem N&atilde;o Correspondente ao Texto</a></li>
        <li><a href="#bk_index">&Iacute;ndices</a></li>
        <li><a href="#insert_char">Inserir Carcateres Especiais</a></li>
        <li><a href="#formatting">It&aacute;licos</a></li>
        <li><a href="#period_p">Language Other Than English (LOTE), Elipses em</a></li>
        <li><a href="#illust">Legendas, Figuras</a></li>
        <li><a href="#drop_caps">Letra Mai&uacute;scula, Ornamentada (Drop Cap)</a></li>
        <li><a href="#small_caps">Letra Mai&uacute;scula, <span style="font-variant: small-caps">Pequena</span></a></li>
      </ul>
    </td>
    <td width="50%" valign="top">
      <ul>
        <li><a href="#drop_caps">Letras Mai&uacute;sculas Grandes e Ornamentadas (Drop Cap)</a></li>
        <li><a href="#drop_caps">Letras Mai&uacute;sculas Ornamentadas (Drop Cap)</a></li>
        <li><a href="#a_chars">Ligaduras</a></li>
        <li><a href="#a_chars">Ligaduras ae </a></li>
        <li><a href="#a_chars">Ligaduras oe</a></li>
        <li><a href="#hand_notes">Manuscritas, Notas</a></li>
        <li><a href="#insert_char">Menus Drop-down</a></li>
        <li><a href="#play_n">Nomes de Actores (Pe&ccedil;as)</a></li>
        <li><a href="#footnotes">Notas</a></li>
        <li><a href="#footnotes">Notas de Rodap&eacute;</a></li>
        <li><a href="#para_side">Notas Laterais</a></li>
        <li><a href="#hand_notes">Notas Manuscritas em Livros</a></li>
        <li><a href="#prev_notes">Notas, Revisores Precedentes</a></li>
        <li><a href="#para_side">Notas (Sidenotes)</a></li>
        <li><a href="#prev_notes">Notas/Coment&aacute;rios do Revisor Precedente</a></li>
        <li><a href="#line_no">Numera&ccedil;&atilde;o de Linhas</a></li>
        <li><a href="#line_no">N&uacute;meros, Linha</a></li>
        <li><a href="#title_pg">P&aacute;gina de T&iacute;tulo</a></li>
        <li><a href="#blank_pg">P&aacute;gina em Branco</a></li>
        <li><a href="#title_pg">P&aacute;gina, T&iacute;tulo</a></li>
        <li><a href="#prev_pg">P&aacute;ginas Anteriores, Corrigir Erros em</a></li>
        <li><a href="#next_word">Palavra Isolada no Final da P&aacute;gina</a></li>
        <li><a href="#next_word">Palavra no Final da P&aacute;gina</a></li>
        <li><a href="#small_caps"><span style="font-variant: small-caps">Palavras em Small Capitals</span></a></li>
        <li><a href="#para_space">Par&aacute;grafo, Espa&ccedil;os</a></li>
        <li><a href="#play_n">Pe&ccedil;as: Nomes de Actores/Direc&ccedil;&otilde;es C&eacute;nicas</a></li>
        <li><a href="#single_q">Plicas</a></li>
        <li><a href="#poetry">Poesia</a></li>
        <li><a href="#period_s">Pontos Finais, Final da Frase</a></li>
        <li><a href="#punctuat">Pontua&ccedil;&atilde;o, Espa&ccedil;os</a></li>
        <li><a href="#common_OCR">Problemas Comuns de OCR</a></li>
        <li><a href="#common_OCR">Problemas de OCR, Comuns</a></li>
        <li><a href="#OCR_raised_o">Problemas de OCR: ordinais ou grau?</a></li>
        <li><a href="#OCR_scanno">Problemas de OCR: Scannos</a></li>
        <li><a href="#anything">Qualquer coisa que necessite tratamento especial</a></li>
        <li><a href="#line_br">Quebras de Linha</a></li>
        <li><a href="#prime">Regra B&aacute;sica</a></li>
        <li><a href="#summary">Resumo das Regras</a></li>
        <li><a href="#period_p">Retic&ecirc;ncias "..." (Elipse)</a></li>
        <li><a href="#page_hf">Rodap&eacute;, P&aacute;gina</a></li>
        <li><a href="#title_pg">Rosto Frente/Verso</a></li>
        <li><a href="#OCR_scanno">Scannos</a></li>
        <li><a href="#OCR_raised_o">S&iacute;mbolos Ordinais</a></li>
        <li><a href="#OCR_raised_o">Sinais de Grau</a></li>
        <li><a href="#em_dashes">Sinais de Subtra&ccedil;&atilde;o</a></li>
        <li><a href="#d_chars">Sinais Diacr&iacute;ticos</a></li>
        <li><a href="#small_caps"><span style="font-variant: small-caps">Small Capitals</span></a></li>
        <li><a href="#about">Sobre Este Documento</a></li>
        <li><a href="#subscr">Subescritos</a></li>
        <li><a href="#supers">Superescritos</a></li>
        <li><a href="#toc">Tabela de Conte&uacute;dos</a></li>
        <li><a href="#tables">Tabelas</a></li>
        <li><a href="#f_chars">Texto em Grego</a></li>
        <li><a href="#f_chars">Texto Hebraico</a></li>
        <li><a href="#bad_text">Texto, Imagem N&atilde;o Correspondente a</a></li>
        <li><a href="#formatting">Texto Negrito</a></li>
        <li><a href="#subscr">Texto Subescrito (Subscripts)</a></li>
        <li><a href="#supers">Texto Superescrito (Superscripts)</a></li>
        <li><a href="#chap_head">T&iacute;tulos, Cap&iacute;tulo</a></li>
        <li><a href="#chap_head">T&iacute;tulos, Cap&iacute;tulos</a></li>
        <li><a href="#chap_head">T&iacute;tulos de Cap&iacute;tulo</a></li>
        <li><a href="#em_dashes">Tra&ccedil;os</a></li>
        <li><a href="#eol_hyphen">Tra&ccedil;os, Final da Linha</a></li>
        <li><a href="#eop_hyphen">Tra&ccedil;os, Final da P&aacute;gina</a></li>
        <li><a href="#mult_col">V&aacute;rias Colunas</a></li>
        <li><a href="#title_pg">Verso da P&aacute;gina de Rosto</a></li>
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
