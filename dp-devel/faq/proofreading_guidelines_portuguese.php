<?

// Translated by user 'rfarinha' at pgdp.net, 1/2/2006

$relPath='../pinc/';
include($relPath.'v_site.inc');
include($relPath.'faq.inc');
include($relPath.'pg.inc');
include($relPath.'connect.inc');
include($relPath.'theme.inc');
new dbConnect();
$no_stats=1;
theme('Regras de Revis&atilde;o','header');

$utf8_site=!strcasecmp($charset,"UTF-8");
?>


<!-- NOTE TO MAINTAINERS AND DEVELOPERS: There are now HTML comments interspersed in this document that are/will be used by a script that automagically slices out the Random Rule text for the database. It does this by copying: 1) All text from one h_3 to the next h_3 -OR- 2) All text from h_3 to the END_RR comment line. This allows us to have "extra" information in the Guidelines, but leave it out in the Random Rule for purposes of clarity/brevity. If you are updating this document, the above should be kept in mind. -->

<h1 align="center">Regras

de Revis&atilde;o</h1>

<h3 align="center">Vers&atilde;o

1.9.c, publicada a 1 de Janeiro de

2006&nbsp; <a href="dochist.php"><font size="-1">(Hist&oacute;rico)</font></a></h3>

<h4>Regras de

Revis&atilde;o&nbsp;<a href="proofreading_guidelines_francaises.php">em

Franc&ecirc;s</a> / Directives

de Formatage <a href="proofreading_guidelines_francaises.php">en

fran&ccedil;ais</a></h4>

<h4>Ver o&nbsp;<a href="../quiz/start.php">Teste de Revis&atilde;o

e Tutorial</a></h4>

<table summary="Proofreading Guidelines" border="0" cellspacing="0"

 width="100%">

  <tbody>

    <tr>

      <td bgcolor="silver" width="1">&nbsp;</td>

      <td align="center" bgcolor="silver"><font size="+2"><b>&Iacute;ndice</b></font></td>

    </tr>

    <tr>

      <td bgcolor="silver" width="1">&nbsp;</td>

      <td align="left" bgcolor="white">

      <ul>

        <li><a href="#prime">A Regra B&aacute;sica</a></li>

        <li><a href="#about">Sobre Este Documento</a></li>

        <li><a href="#comments">Coment&aacute;rios do Projecto</a></li>

        <li><a href="#forums">F&oacute;rum/Discuss&atilde;o

deste Projecto</a></li>

        <li><a href="#prev_pg">Correc&ccedil;&atilde;o de

Erros em P&aacute;ginas Precedentes</a></li>

      </ul>

      </td>

    </tr>

    <tr>

      <td bgcolor="silver" width="1">&nbsp;</td>

      <td align="left" bgcolor="silver">

      <ul>

        <li><font size="+1">Rever...</font></li>

      </ul>

      </td>

    </tr>

    <tr>

      <td bgcolor="silver" width="1">&nbsp;</td>

      <td align="left" bgcolor="white">

      <ul style="margin-left: 3em;">

        <li><a href="#line_br">Espa&ccedil;o Entre Linhas</a></li>

        <li><a href="#double_q">Aspas</a></li>

        <li><a href="#single_q">Plicas</a></li>

        <li><a href="#quote_ea">Aspas no In&iacute;cio de Cada

Linha</a></li>

        <li><a href="#period_s">Pontua&ccedil;&atilde;o

Entre Frases</a></li>

        <li><a href="#punctuat">Pontua&ccedil;&atilde;o</a></li>

        <li><a href="#period_p">Elipses "..."</a></li>

        <li><a href="#contract">Contrac&ccedil;&otilde;es</a></li>

        <li><a href="#extra_sp">Espa&ccedil;o Extra Entre

Palavras</a></li>

        <li><a href="#trail_s">Espa&ccedil;o Extra no Final da

Linha</a></li>

        <li><a href="#line_no">Numera&ccedil;&atilde;o de

Linhas</a></li>

        <li><a href="#italics">Texto em It&aacute;lico e em

Negrito</a></li>

        <li><a href="#supers">Texto Superescrito (Superscripts)</a></li>

        <li><a href="#subscr">Texto Subescrito (Subscripts)</a></li>

        <li><a href="#font_sz">Altera&ccedil;&otilde;es no

Tamanho da Fonte</a></li>

        <li><a href="#small_caps">Texto em Mai&uacute;sculas

Mais Pequenas (<span style="font-variant: small-caps;">Small Capitals</span></a><a

 href="#small_caps">)</a></li>

        <li><a href="#drop_caps">Letra Grande e Ornamentada no

In&iacute;cio do Par&aacute;grafo</a></li>

        <li><a href="#a_chars">Acentua&ccedil;&atilde;o/Caracteres

Non-ASCII</a></li>

        <li><a href="#d_chars">Caracteres com Sinais

Diacr&iacute;ticos</a></li>

        <li><a href="#f_chars">Caracteres Non-Latin</a></li>

        <li><a href="#fract_s">Frac&ccedil;&otilde;es</a></li>

        <li><a href="#em_dashes">Tra&ccedil;os,

H&iacute;fenes e Sinais de Subtrac&ccedil;&atilde;o</a></li>

        <li><a href="#eol_hyphen">Hifeniza&ccedil;&atilde;o

no Final da Linha</a></li>

        <li><a href="#eop_hyphen">Hifeniza&ccedil;&atilde;o

no Final da P&aacute;gina</a></li>

        <li><a href="#para_space">Espa&ccedil;o entre

Par&aacute;grafos e Avan&ccedil;os</a></li>

        <li><a href="#mult_col">V&aacute;rias Colunas</a></li>

        <li><a href="#blank_pg">P&aacute;gina em Branco</a></li>

        <li><a href="#page_hf">Cabe&ccedil;alho e

Rodap&eacute; da P&aacute;gina</a></li>

        <li><a href="#chap_head">T&iacute;tulos de

Cap&iacute;tulo</a></li>

        <li><a href="#illust">Figuras</a></li>

        <li><a href="#footnotes">Notas de Rodap&eacute;</a></li>

        <li><a href="#poetry">Poesia/Epigramas</a></li>

        <li><a href="#para_side">Notas (Sidenotes)</a></li>

        <li><a href="#tables">Tabelas</a></li>

        <li><a href="#title_pg">P&aacute;gina de Rosto (Frente e

Verso)</a></li>

        <li><a href="#toc">Tabela de Conte&uacute;dos</a></li>

        <li><a href="#bk_index">&Iacute;ndices</a></li>

        <li><a href="#play_n">Pe&ccedil;as de Teatro: Nome de

Actores/Marca&ccedil;&otilde;es C&eacute;nicas</a></li>

        <li><a href="#anything">Qualquer outra

situa&ccedil;&atilde;o que necessite de um tratamento especial

ou que suscite d&uacute;vidas</a></li>

        <li><a href="#prev_notes">Notas de Revisores Precedentes</a></li>

      </ul>

      </td>

    </tr>

    <tr>

      <td bgcolor="silver" width="1">&nbsp;</td>

      <td align="left" bgcolor="silver">

      <ul>

        <li><font size="+1">Problemas Comuns</font></li>

      </ul>

      </td>

    </tr>

    <tr>

      <td bgcolor="silver" width="1">&nbsp;</td>

      <td align="left" bgcolor="white">

      <ul style="margin-left: 3em;">

        <li><a href="#OCR_1lI">Problemas de OCR: 1-l-I</a></li>

        <li><a href="#OCR_0O">Problemas de OCR: 0-O</a></li>

        <li><a href="#OCR_scanno">Problemas de OCR: Erros de Leitura</a></li>

        <li><a href="#hand_notes">Notas Manuscritas no Livro</a></li>

        <li><a href="#bad_image">Imagens Danificadas</a></li>

        <li><a href="#bad_text">Imagem N&atilde;o Corresponde ao

Texto</a></li>

        <li><a href="#round1">Erros do Revisor Precedente</a></li>

        <li><a href="#p_errors">Erros de

Impress&atilde;o/Ortografia</a></li>

        <li><a href="#f_errors">Erros Factuais no Texto</a></li>

        <li><a href="#uncertain">Termos Desconhecido</a></li>

      </ul>

      </td>

    </tr>

    <tr>

      <td bgcolor="silver" width="1">&nbsp;</td>

      <td bgcolor="silver">&nbsp;</td>

    </tr>

  </tbody>

</table>

<h3><a name="prime">A&nbsp;Regra B&aacute;sica</a></h3>

<p><em>"N&atilde;o

alterar o que o&nbsp;autor escreveu!"</em>

</p>

O livro electr&oacute;nico definitivo, lido daqui a muitos anos no

futuro, deve perceber o que o autor queria dizer. Se o autor escreveu

uma palavra de uma forma estranha, deve mant&ecirc;-la assim. Se o

autor escreveu declara&ccedil;&otilde;es marcadamente

tendenciosas ou

racistas, deve mant&ecirc;-las assim. Se o autor usou o

it&aacute;lico

ou o negrito, ou se colocou uma nota de rodap&eacute; em cada

tr&ecirc;s palavras, deve usar o it&aacute;lico, o negrito e as

notas

de rodap&eacute; respectivamente. N&oacute;s somos

revisores,&nbsp;<span style="font-weight: bold;">n&atilde;o</span>

editores. (Ver <a href="#p_errors">Erros

de Impress&atilde;o/Ortografia</a>.)

<p>Mudamos apenas pequenas

conven&ccedil;&otilde;es tipogr&aacute;ficas

que n&atilde;o afectem o sentido do que o autor escreveu.&nbsp;

Por exemplo, juntamos &nbsp;as palavras hifenizadas no final de uma

linha&nbsp;(<a href="#eol_hyphen">Hifeniza&ccedil;&atilde;o

no Final da Linha</a>).&nbsp;Altera&ccedil;&otilde;es

como esta ajuda-nos a criar uma vers&atilde;o

do livro formatada e consistente (<span style="font-style: italic;">normalizada</span>).

As regras de revis&atilde;o que seguimos s&atilde;o

especificamente

desenhadas para atingir esse objectivo.&nbsp;Por favor leia o

presente documento com esta ideia em mente. Existe um documento de

Regras de Formata&ccedil;&atilde;o.&nbsp;Estas regras dizem

respeito&nbsp;<span style="font-style: italic;">apenas</span>

&agrave;

revis&atilde;o.&nbsp;Um segundo grupo de volunt&aacute;rios

formatar&aacute;

o texto

</p>

<p>Para ajudar o segundo revisor,

o formatador e o

p&oacute;s-processador, preserve o <a href="#line_br">Espa&ccedil;o

Entre Linhas</a>.&nbsp;Este

passo facilitar&aacute; a compara&ccedil;&atilde;o entre o

texto e

a imagem.

</p>

<!-- END RR -->

<table summary="Summary Guidelines" border="0" cellspacing="0"

 width="100%">

  <tbody>

    <tr>

      <td bgcolor="silver">&nbsp;</td>

    </tr>

  </tbody>

</table>

<h3><a name="about">Sobre Este Documento</a></h3>

<p>Este documento existe para

minimizar as diferen&ccedil;as de

formata&ccedil;&atilde;o de um livro que, ao ser dividido em

p&aacute;ginas, &eacute; revisto por v&aacute;rios

revisores. Este

documento ajuda-nos a formatar <span style="font-style: italic;">da

mesma forma</span>. Assim, ajuda no

trabalho do p&oacute;s-processador,

tornando mais f&aacute;cil o processo de juntar as

p&aacute;ginas

revistas num e-book.&nbsp;&nbsp;

</p>

<p><i>Com

as Regras n&atilde;o se pretende a cria&ccedil;&atilde;o de

um livro de estilo</i>.

</p>

<p>Inclu&iacute;mos neste

documento todos os itens de

formata&ccedil;&atilde;o em que os novos utilizadores tiveram

d&uacute;vidas enquanto reviam.&nbsp;Se faltar algum

item,&nbsp;se

achar que deveria haver uma outra metodologia, ou se achar alguma

explica&ccedil;&atilde;o vaga, por favor diga-nos.

</p>

Este documento &eacute; um trabalho em curso. Ajude-nos, dando-nos

sugest&otilde;es no F&oacute;rum da

Documenta&ccedil;&atilde;o&nbsp;<a

 href="http://www.pgdp.net/phpBB2/viewtopic.php?t=10217">nesta

liga&ccedil;&atilde;o</a>.

<h3><a name="comments">Coment&aacute;rios do Projecto</a></h3>

<p>Na p&aacute;gina da

interface de revis&atilde;o (P&aacute;gina do

Projecto) onde clica para come&ccedil;ar a rever

p&aacute;ginas,

h&aacute; uma sec&ccedil;&atilde;o chamada

"Coment&aacute;rios do

Projecto" que cont&eacute;m informa&ccedil;&atilde;o

espec&iacute;fica

desse projecto (livro). <b>Leia-os

antes de come&ccedil;ar a

revis&atilde;o!</b> Se o Gestor

do Projecto quiser formatar o livro de

forma diferente da especificada nas Regras, estar&aacute;

l&aacute;

escrito. As instru&ccedil;&otilde;es dos Coment&aacute;rios

do

Projecto&nbsp;<span style="font-style: italic;">tomam

preced&ecirc;ncia</span>

em rela&ccedil;&atilde;o &agrave;s Regras e devem ser

seguidas.

(&Eacute; tamb&eacute;m aqui que o Gestor do Projecto pode

escrever

algumas curiosidades sobre o autor ou sobre o projecto.)

</p>

<p><em>Por

favor, leia tamb&eacute;m o f&oacute;rum do livro</em>:

O

Gestor do Projecto pode us&aacute;-lo para esclarecer regras

espec&iacute;ficas do projecto, sendo utilizado pelos revisores

para

avisar outros revisores de quest&otilde;es recorrentes e para

definir a

melhor forma de as tratar.

</p>

Na P&aacute;gina do Projecto, a liga&ccedil;&atilde;o

'Imagens,

P&aacute;ginas Revistas &amp; Diferen&ccedil;as'

&eacute;

poss&iacute;vel ver como &eacute; que outros revisores

fizeram&nbsp;altera&ccedil;&otilde;es.&nbsp; <a

 href="%3C?%20echo%20$Using_project_details_URL%20?%3E">Neste&nbsp;F&oacute;rum</a>&nbsp;debatem-se

diferentes formas de usar este tipo de

informa&ccedil;&atilde;o.

<h3><a name="forums">F&oacute;rum/Discuss&atilde;o

deste Projecto</a></h3>

<p> Na p&aacute;gina do

ecr&atilde; de revis&atilde;o (P&aacute;gina do

Projecto) onde clica para come&ccedil;ar a rever

p&aacute;ginas, existe

uma linha denominada&nbsp;"F&oacute;rum", com uma

liga&ccedil;&atilde;o

com o nome "Discutir este projecto". Ao clicar nessa

liga&ccedil;&atilde;o, ir&aacute; para um t&oacute;pico

do f&oacute;rum

de projectos dedicada a esse projecto espec&iacute;fico. Esse

&eacute;

o local onde deve colocar as suas d&uacute;vidas sobre o livro,

informar o Gestor de Projecto de problemas, etc. Usar este

t&oacute;pico do f&oacute;rum &eacute; uma forma

recomendada de

comunica&ccedil;&atilde;o com o Gestor de Projecto e com outros

revisores que trabalham nesse livro.

</p>

<h3><a name="prev_pg">Correc&ccedil;&atilde;o de

erros em

P&aacute;ginas Precedentes</a></h3>

<p>

Ao seleccionar um projecto para rever, surge a p&aacute;gina dos&nbsp;<a

 href="#comments">Coment&aacute;rios do Projecto</a>.&nbsp;Esta

p&aacute;gina

tem liga&ccedil;&otilde;es para as p&aacute;ginas que reviu

recentemente. (Se ainda n&atilde;o reviu nenhuma, n&atilde;o

ter&aacute; liga&ccedil;&atilde;o nenhuma.)

</p>

<p>As p&aacute;ginas listadas

por baixo de "CONCLU&Iacute;DAS" ou "EM

CURSO" est&atilde;o dispon&iacute;veis para

edi&ccedil;&atilde;o ou

para conclus&atilde;o de revis&atilde;o. Basta clicar na

liga&ccedil;&atilde;o para a p&aacute;gina em

quest&atilde;o. Assim,

quando descobrir que se enganou numa p&aacute;gina, ou

quando&nbsp;marcar algo incorrectamente, pode clicar aqui para

reabrir

a p&aacute;gina e corrigir o erro.

</p>

Pode utilizar as liga&ccedil;&otilde;es&nbsp;"Images, Pages

Proofread, &amp; Differences" ou "Just My Pages"

dispon&iacute;veis

nos&nbsp;<a href="#comments">Coment&aacute;rios

do Projecto</a>.&nbsp;Estas

p&aacute;ginas disponiilizar&atilde;o uma

liga&ccedil;&atilde;o para a

edi&ccedil;&atilde;o das p&aacute;ginas que reviu e que

ainda podem ser

corrigidas.

<p>Para

informa&ccedil;&atilde;o mais detalhadas, veja a&nbsp;<a

 href="prooffacehelp.php?i_type=0">Ajuda de Interface

Normalizada de Revis&atilde;o</a>

ou a&nbsp;<a href="prooffacehelp.php?i_type=1">Ajuda de Interface

Melhorada de&nbsp;Revis&atilde;o</a>,&nbsp;dependendo

da interface que usar.

</p>

<!-- END RR -->

<table summary="Title Page" border="0" cellpadding="6" cellspacing="0"

 width="100%">

  <tbody>

    <tr>

      <td bgcolor="silver"><font size="+2">Rever...</font></td>

    </tr>

  </tbody>

</table>

<h3><a name="line_br">Espa&ccedil;o Entre Linhas</a></h3>

<p><b>Deixe todos os espa&ccedil;os existentes entre linhas</b>, para

que o pr&oacute;ximo revisor e p&oacute;s-processador possam comparar

as linhas do texto revisto e as linhas das imagens mais

facilmente.&nbsp;Se o revisor

anterior apagou os espa&ccedil;os entre linhas, por favor, recoloque-os

de forma a representar a imagem mais fielmente.<br>

</p>

<h3><a name="double_q">Aspas</a></h3>

<p>Reveja as como&nbsp;<tt>"</tt>.&nbsp;N&atilde;o as converta em

plicas ('). Deixe-as como o autor as

escreveu. </p>

Mas

cita&ccedil;&otilde;es escritas numa l&iacute;ngua

n&atilde;o-anglo-sax&oacute;nica, use as aspas apropriadas

&agrave;

l&iacute;ngua em quest&atilde;o, se estiverem

dispon&iacute;veis no menu &nbsp;de caracteres Latin-1.&nbsp;Por

exemplo, o equivalente

&agrave;s aspas em Fran&ccedil;a

&eacute;&nbsp;<tt>&laquo;isto&raquo;</tt>,

e est&atilde;o

dispon&iacute;veis nos menus do ecr&atilde; de

revis&atilde;o, uma

vez que fazem parte do Latin-1.&nbsp;As aspas utilizadas na Alemanha

s&atilde;o <tt>&bdquo;assim&rdquo;</tt>

e n&atilde;o est&atilde;o dispon&iacute;veis nos

menus nem fazem

parte do Latin-1. Gestor do Projecto pode

esclarec&ecirc;-lo nos&nbsp;<a href="#comments">Coment&aacute;rios do

Projecto</a>,

como rever as

aspas num livro escrito numa l&iacute;ngua

n&atilde;o-anglo-sax&oacute;nica.

<h3><a name="single_q">Plicas</a></h3>

<p>Reveja-as como '

(ap&oacute;strofes).&nbsp;N&atilde;o as converta em

aspas ("). Deixe-as como o autor as

escreveu.

</p>

<h3><a name="quote_ea">Aspas no In&iacute;cio de Cada

Linha</a></h3>

<p>Remova as aspas de uma

cita&ccedil;&atilde;o no in&iacute;cio de

cada linha, <span style="font-weight: bold;">excepto</span>

a da

primeira linha.</p>

Se a cita&ccedil;&atilde;o se prolongar por muitos

par&aacute;grafos,

cada um destes par&aacute;grafos deve come&ccedil;ar com aspas.<br>

<br>

Na maioria das vezes, as aspas

s&oacute; se fecham quando a

cita&ccedil;&atilde;o acaba, o que pode n&atilde;o

acontecer na

p&aacute;gina que estiver a rever. Deixe o texto

assim--n&atilde;o

feche aspas se isso n&atilde;o estiver representado na imagem.

<h3><a name="period_s">Pontua&ccedil;&atilde;o Entre

Frases</a></h3>

Reveja os pontos finais entre frases com um espa&ccedil;o logo a seguir.<br>

<br>

N&atilde;o &eacute; necess&aacute;rio remover espa&ccedil;os extra que

possam existir depois de pontos finais--faz&ecirc;mo-lo automaticamente

durante o p&oacute;s-processamento.&nbsp;Veja a imagem

e o

texto de exemplo das <a href="#para_side">Notas (Sidenotes)</a>.

<h3><a name="punctuat">Pontua&ccedil;&atilde;o</a></h3>

<p>Em geral, n&atilde;o deve existir espa&ccedil;amento antes dos

caracteres de pontua&ccedil;&atilde;o, excepto antes das aspas. Se no

texto resultante do OCR existir espa&ccedil;o antes da

pontua&ccedil;&atilde;o, este deve ser removido.

</p>

<p>O espa&ccedil;amento antes da pontua&ccedil;&atilde;o &eacute; comum

em livros do s&eacute;culo XVII e XIX, que utilizava espa&ccedil;os

parciais antes de um ponto e v&iacute;rgula, ou v&iacute;rgula. </p>

<!-- END RR -->

<table summary="Punctuation" align="center" border="1" cellpadding="4"

 cellspacing="0" width="100%">

  <tbody>

    <tr>

      <th align="left" bgcolor="cornsilk"><span

 style="font-weight: bold;">Texto Digitalizado</span>:</th>

    </tr>

    <tr>

      <td valign="top">and

so it goes&nbsp;; ever and ever.</td>

    </tr>

    <tr>

      <th align="left" bgcolor="cornsilk">Texto Revisto Correctamente:</th>

    </tr>

    <tr>

      <td valign="top"><tt>and

so it goes; ever and ever.</tt></td>

    </tr>

  </tbody>

</table>

<h3><a name="period_p">Elipses "..."</a><br>

</h3>

<p>As regras referentes a este item s&atilde;o diferentes tendo em

conta se se trata de um texto em ingl&ecirc;s (English) ou n&atilde;o

(Languages Other Than English -- LOTE).

</p>

<p><b>INGL&Ecirc;S</b>: Deixe um espa&ccedil;o antes das

retic&ecirc;ncias, e outro depois. A &uacute;nica

excep&ccedil;&atilde;o acontece se as retic&ecirc;ncias estiverem

colocadas no final da frase, n&atilde;o sendo seguido de espa&ccedil;o.

Neste caso devem ser quatro pontos, seguidos de um espa&ccedil;o. Esta

situa&ccedil;&atilde;o acontece igualmente quando existe uma

pontua&ccedil;&atilde;o final e retic&ecirc;ncias. A forma correcta de

rever ser&aacute;: retic&ecirc;ncias (sem espa&ccedil;amento a

preceder) e a pontua&ccedil;&atilde;o final,&nbsp;seguida de um

espa&ccedil;o.

</p>

<p>Por exemplo:<br>

<tt> <br>

&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;That I know ... is true. <br>

&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;This is the end.... <br>

&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Wherefore art thou Romeo?... </tt>

</p>

<p>Por vezes a pontua&ccedil;&atilde;o vir&aacute; no final;

dever&aacute; ser revisto assim:<br>

<tt> <br>

&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Wherefore art thou Romeo...? </tt>

</p>

<p>Remova os pontos extra, se existirem, ou acrescente-os, se

necess&aacute;rio, de forma a obter tr&ecirc;s (ou quatro) pontos de

acordo com a situa&ccedil;&atilde;o.

</p>

<p><b>LOTE:</b> (Languages Other Than English) Siga a regra geral "Siga

fielmente o estilo utilizado na p&aacute;gina impressa".

Por vezes, a p&aacute;gina impressa n&atilde;o &eacute;

percept&iacute;vel: neste caso, coloque um&nbsp;<tt>*</tt> de forma a

chamar a

aten&ccedil;&atilde;o do&nbsp;p&oacute;s-processador.

Se lhe parecer que existem espa&ccedil;os entre os pontos das

retic&ecirc;ncias, ou entre estes e as palavras, substitua-os por

underscores ( _ ):&nbsp;<tt>assim_...</tt> ou <tt>assim_._._.</tt>

conforme o estilo. Estes s&iacute;mbolos ir&atilde;o prevenir problemas

de formata&ccedil;&atilde;o de linhas no p&oacute;s-processamento,

sendo posteriormente substitu&iacute;dos por espa&ccedil;os nessa fase.

</p>

<h3><a name="contract">Contrac&ccedil;&otilde;es</a></h3>

<p>Remova todo e qualquer

espa&ccedil;o extra nas

contrac&ccedil;&otilde;es, por exemplo "would&nbsp;n't"

deve ser revisto como&nbsp;<tt>wouldn't</tt>.&nbsp;</p>

<p>Esta era uma

conven&ccedil;&atilde;o frequente entre os primeiros

editores, que mantinham o espa&ccedil;o para indicar que "would" e

"not" eram originalmente duas palavras separadas. Por vezes, trata-se

apenas de uma falha de OCR. Remova o espa&ccedil;o em qualquer dos

casos. </p>

Alguns Gestores de Projecto

podem pedir, nos&nbsp;<a href="#comments">Coment&aacute;rios do Projecto</a>,

para n&atilde;o

remover espa&ccedil;os extra nas

contrac&ccedil;&otilde;es, principalmente se os textos

contiverem

coloquialismos, dialectos ou palavras escritas noutra l&iacute;ngua

que&nbsp;n&atilde;o a&nbsp;inglesa.

<h3><a name="extra_sp">Espa&ccedil;o Extra Entre Palavras</a></h3>

<p>Os espa&ccedil;os extra entre palavras s&atilde;o comuns nos

resultados de OCR. N&atilde;o &eacute; necess&aacute;rio

remov&ecirc;-los&mdash;estes s&atilde;o facilmente removidos facilmente

durante o p&oacute;s-processamento.

</p>

<p>No entanto, <span style="font-weight: bold;">&eacute;

necess&aacute;rio</span> remover o espa&ccedil;amento extra relativo

&agrave; pontua&ccedil;&atilde;o, tra&ccedil;os, aspas, etc, quando o

s&iacute;mbolo surge separado da palavra.

</p>

<p>Por exemplo, em <b>A horse&nbsp;;&nbsp;&nbsp;&nbsp;my kingdom for a

horse.</b> o espa&ccedil;o entre a palavra&nbsp;"horse" e o ponto e

v&iacute;rgula deve ser removido. Mas os dois espa&ccedil;os

ap&oacute;s o ponto e v&iacute;rgula n&atilde;o s&atilde;o

problem&aacute;ticos&mdash;n&atilde;o &eacute; necess&aacute;rio apagar

um deles.

</p>

<h3><a name="trail_s">Espa&ccedil;o Extra no Final da Linha</a></h3>

<p>N&atilde;o perca tempo a inserir espa&ccedil;os no final de cada

linha do texto. &Eacute; algo que podemos fazer automaticamente mais

tarde. O mesmo se aplica relativamente &agrave; remo&ccedil;&atilde;o

dos mesmos.

</p>

<h3><a name="line_no">Numera&ccedil;&atilde;o de Linhas</a></h3>

<p>Mantenha a numera&ccedil;&atilde;o de linhas. Coloque-as a seis

espa&ccedil;os do lado direito do texto (no final da linha respectiva),

mesmo quando se encontrem do lado esquerdo no texto/poesia da imagem

original.

</p>

A numera&ccedil;&atilde;o de linha s&atilde;o n&uacute;meros

colocados junto &agrave; margem de cada linha, ou de cada 5 ou 10

linhas, sendo comuns em livros de poesia. Uma vez que a poesia

n&atilde;o ser&aacute; reformatada numa vers&atilde;o e-book, a

numera&ccedil;&atilde;o de linha ser&aacute; &uacute;til para os

leitores.<!-- END RR -->

<!-- We need an example image and text for this. -->

<h3><a name="italics">Texto em It&aacute;lico e em Negrito</a></h3>

<p>Reveja o texto em <span style="font-style: italic;">it&aacute;lico</span>

colocando-o entre&nbsp;<tt>&lt;i&gt;</tt> e&nbsp;<tt>&lt;/i&gt;</tt>.&nbsp;Tome

aten&ccedil;&atilde;o ao "<tt>/</tt>" no&nbsp;<tt>&lt;/i&gt;</tt> para

fechar.&nbsp; Reveja o <span style="font-weight: bold;">texto em

negrito</span>

(texto impresso de forma mais carregada) colocando-o entre <tt>&lt;b&gt;</tt>&nbsp;e&nbsp;<tt>&lt;/b&gt;</tt>.&nbsp;Tome

aten&ccedil;&atilde;o ao "<tt>/</tt>"

no&nbsp;<tt>&lt;/b&gt;</tt> para fechar. N&atilde;o remova este tipo de

formata&ccedil;&atilde;o, excepto se o texto n&atilde;o existir na

p&aacute;gina. N&atilde;o o acrescente caso n&atilde;o exista. Os

formatadores tratar&atilde;o disso mais tarde.<br>

</p>

<h3><a name="supers">Texto Superescrito (Superscripts)</a><br>

</h3>

<p>Os livros antigos abreviam frequentemente palavras em

contrac&ccedil;&otilde;es, e imprimem-nas como texto superescrito, por

exemplo:&nbsp;<br>

&nbsp;&nbsp;&nbsp;&nbsp; Gen<sup>rl</sup> Washington defeated L<sup>d</sup>

Cornwall's army.<br>

Reveja estas contrac&ccedil;&otilde;es inserindo um car&aacute;cter

("^") que

as identifique como texto superescrito, assim:<br>

&nbsp;&nbsp;&nbsp;&nbsp; <tt>Gen^rl Washington defeated L^d Cornwall's

army.</tt><tt></tt>

</p>

<h3><a name="subscr">Texto Subescrito (Subscripts)</a><br>

</h3>

<p>O texto subescrito encontra-se geralmente em trabalhos

cient&iacute;ficos, mas n&atilde;o &eacute; comum nos outros livros.

Reveja-o colocando um car&aacute;cter _ ..</p>

Por exemplo: <br>

&nbsp; &nbsp; &nbsp; &nbsp; H<sub>2</sub>O. <br>

deve ser revisto assim: <br>

&nbsp; &nbsp; &nbsp; &nbsp; <tt>H_2O.</tt><tt></tt>

<h3><a name="font_sz">Altera&ccedil;&otilde;es no Tamanho da Fonte</a></h3>

<p>N&atilde;o fazemos nada relativamente a

altera&ccedil;&otilde;es no tamanho da fonte. Os formatadores

tratar&atilde;o disso mais tarde.

</p>

<h3><a name="small_caps">Texto em Mai&uacute;sculas Mais Pequenas (<span

 style="font-variant: small-caps;">Small Caps)</span></a></h3>

<p>As palavras impressas em letra mai&uacute;scula mais pequena (<span

 style="font-variant: small-caps;">Small Caps),&nbsp;</span>misturando

letra mai&uacute;scula e min&uacute;scula, poder&atilde;o aparecer

entre <tt>&lt;sc&gt;</tt>

e <tt>&lt;/sc&gt;</tt>. Por favor, n&atilde;o&nbsp;remova este tipo de

formata&ccedil;&atilde;o, excepto se o texto n&atilde;o existir na

p&aacute;gina. N&atilde;o o acrescente caso n&atilde;o exista. Os

formatadores tratar&atilde;o

disso mais tarde. Reveja apenas o caracteres e n&atilde;o se preocupe

com as altera&ccedil;&otilde;es de fonte. Se j&aacute; estivrem todas

em letra MAI&Uacute;SCULA, Mista ou &nbsp;min&uacute;scula, reveja-as

como se estivessem todas em MAI&Uacute;SCULA, Mista ou

&nbsp;min&uacute;scula, tal como aparecem.

</p>

<h3><a name="drop_caps">Letra Grande e Ornamentada no

In&iacute;cio do

Par&aacute;grafo</a></h3>

<p>Reveja as letras grandes e

ornamentada do in&iacute;cio do

cap&iacute;tulo, sec&ccedil;&atilde;o ou

par&aacute;grafo como se

s&oacute; existisse a letra.</p>

<h3><a name="a_chars">Acentua&ccedil;&atilde;o/Caracteres Non-ASCII</a></h3>

<p>Por favor, reveja-os utilizando os caracteres Latin-1 acentuados

apropriados. Para mais detalhes, consulte os&nbsp;<a href="#d_chars">Sinais

Diacr&iacute;ticos</a>.

</p>

<p>H&aacute; v&aacute;rias

formas de os inserir:</p>

<ul compact="compact">

  <li>Os menus de&nbsp;pull-down existentes no ecr&atilde; de

revis&atilde;o.</li>

  <li> Os "Applets" que fazem parte do seu sistema operativo.

    <ul compact="compact">

      <li>Windows: "Mapa de Caracteres"<br>

Aceda atrav&eacute;s de:<br>

In&iacute;cio: Executar: charmap, ou<br>

In&iacute;cio: Acess&oacute;rios: Ferramentas de Sistema: Mapa de

Caracteres.</li>

      <li>Macintosh: Key Caps or "Keyboard Viewer"<br>

Para&nbsp;OS 9 e anteriores encontra-se no Apple Menu,<br>

Para OS X at&eacute; 10.2, situa-se nas&nbsp;Applications, Utilities

folder<br>

Para OS X 10.3 e posteriores, est&aacute; no&nbsp;Input Menu como

"Keyboard Viewer."</li>

      <li>Linux: V&aacute;rios, depende do ambiente do seu desktop.<br>

Para KDE, experiemente KCharSelect (no submenu das Utilities do seu

start menu).</li>

    </ul>

  </li>

  <li>Uma aplica&ccedil;&atilde;o em linha, como o&nbsp;<a

 href="http://free.pages.at/krauss/computer/xml/daten/edicode.html">Edicode</a>.</li>

  <li> Os atalhos do teclado.<br>

(As tabelas para&nbsp;<a href="#a_chars_win">Windows</a> e <a

 href="#a_chars_mac">Macintosh</a>

que apresentam estes atalhos est&atilde;o no documento de Regras de

Revis&atilde;o).</li>

  <li>Mudar para uma localiza&ccedil;&atilde;o ("locale") ou

configura&ccedil;&atilde;o de teclado que suporte caracteres acentuados

("deadkeys")

    <ul compact="compact">

      <li>Windows:&nbsp;Painel de Controlo (Teclado, Input Locales)</li>

      <li>Macintosh: Input Menu&nbsp;(na Barra de Menu)</li>

      <li>Linux:&nbsp;Modifique o teclado na sua

configura&ccedil;&atilde;o

X.</li>

    </ul>

  </li>

</ul>

<p>O&nbsp;<a href="http://www.gutenberg.org/">Projecto Gutenberg</a>&nbsp;aceitar&aacute;

como m&iacute;nimo vers&otilde;es de texto de

7-bit ASCII, mas as vers&otilde;es que usem outros caracteres de

codifica&ccedil;&atilde;o e que conseguem preservar mais

informa&ccedil;&atilde;o do texto original, ser&atilde;o igualmente

aceites. Para os revisores do Distributed Proofreaders

isto&nbsp;obriga&nbsp;o uso do&nbsp;Latin-1 ou ISO 8859-1 e -15,

incluindo de

futuro o&nbsp;Unicode.

</p>

<a name="a_chars_win"></a>

<p><b>Para

Windows</b>:

</p>

<ul compact="compact">

  <li>Pode utilizar o Mapa de Caracteres (In&iacute;cio:

Executar:&nbsp;charmap) para seleccionar uma letra individual,

utilizando o "corta e cola". </li>

  <li>Se estiver a utilizar o ecr&atilde; de revis&atilde;o

avan&ccedil;ado, a op&ccedil;&atilde;o de <span

 style="font-style: italic;">mais</span> abrir&aacute; uma nova janela

com estes caracteres, podendo utilizar igualmente o "corta e cola". </li>

  <li>Ou pode premir em&nbsp;Alt+um atalho de

c&oacute;digo&nbsp;NumberPad destes caracteres. <br>

Este m&eacute;todo &eacute; mais r&aacute;pido do que o uso do "corta e

cola", quando j&aacute; souber os c&oacute;digos<br>

Prima e mantenha premida a tecla&nbsp;Alt, enquanto escreve os quatro

d&iacute;gitos no&nbsp;<i>Number Pad</i>,&nbsp;soltando-a depois desta

ac&ccedil;&atilde;o; tenha em aten&ccedil;&atilde;o de que os

n&uacute;meros por cima das letras n&atilde;o funcionar&atilde;o para

esta ac&ccedil;&atilde;o. <br>

Deve inserir os 4 d&iacute;gitos, incluindo o 0 (zero) quando for

&agrave;

esquerda. Repare que a vers&atilde;o das letras em mai&uacute;scula

&eacute; menos 32 que a vers&atilde;o em letra min&uacute;scula.<br>

Tome em aten&ccedil;&atilde;o que em algumas de

defini&ccedil;&otilde;es de sistema estes c&oacute;digos podem

n&atilde;o ser usados.<br>

A tabela apresentada em baixo, mostra os c&oacute;digos

utilizados.&nbsp;(<a href="charwin.pdf">Clique para imprimir esta tabela</a>).<br>

N&atilde;o use outros caracteres especiais sem que o Gestor de Projecto

lho pe&ccedil;a nos&nbsp;<a href="#comments">Coment&aacute;rios do

Projecto</a>. </li>

</ul>

<table summary="Windows shortcuts" align="center" border="6" rules="all">

  <tbody>

    <tr>

      <th colspan="14" bgcolor="cornsilk">Atalhos de Windows&nbsp;para

os s&iacute;mbolos Latin-1</th>

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

    <tr>

      <td title="Small a grave" align="center" bgcolor="mistyrose">&agrave;

      </td>

      <td>Alt-0224</td>

      <td title="Small a acute" align="center" bgcolor="mistyrose">&aacute;

      </td>

      <td>Alt-0225</td>

      <td title="Small a circumflex" align="center" bgcolor="mistyrose">&acirc;

      </td>

      <td>Alt-0226</td>

      <td title="Small a tilde" align="center" bgcolor="mistyrose">&atilde;

      </td>

      <td>Alt-0227</td>

      <td title="Small a umlaut" align="center" bgcolor="mistyrose">&auml;

      </td>

      <td>Alt-0228</td>

      <td title="Small a ring" align="center" bgcolor="mistyrose">&aring;

      </td>

      <td>Alt-0229</td>

      <td title="Small ae ligature" align="center" bgcolor="mistyrose">&aelig;

      </td>

      <td>Alt-0230</td>

    </tr>

    <tr>

      <td title="Capital A grave" align="center" bgcolor="mistyrose">&Agrave;

      </td>

      <td>Alt-0192</td>

      <td title="Capital A acute" align="center" bgcolor="mistyrose">&Aacute;

      </td>

      <td>Alt-0193</td>

      <td title="Capital A circumflex" align="center"

 bgcolor="mistyrose">&Acirc; </td>

      <td>Alt-0194</td>

      <td title="Capital A tilde" align="center" bgcolor="mistyrose">&Atilde;

      </td>

      <td>Alt-0195</td>

      <td title="Capital A umlaut" align="center" bgcolor="mistyrose">&Auml;

      </td>

      <td>Alt-0196</td>

      <td title="Capital A ring" align="center" bgcolor="mistyrose">&Aring;

      </td>

      <td>Alt-0197</td>

      <td title="Capital AE ligature" align="center" bgcolor="mistyrose">&AElig;

      </td>

      <td>Alt-0198</td>

    </tr>

    <tr>

      <td title="Small e grave" align="center" bgcolor="mistyrose">&egrave;

      </td>

      <td>Alt-0232</td>

      <td title="Small e acute" align="center" bgcolor="mistyrose">&eacute;

      </td>

      <td>Alt-0233</td>

      <td title="Small e circumflex" align="center" bgcolor="mistyrose">&ecirc;

      </td>

      <td>Alt-0234</td>

      <td> </td>

      <td> </td>

      <td title="Small e umlaut" align="center" bgcolor="mistyrose">&euml;

      </td>

      <td>Alt-0235</td>

      <td> </td>

      <td> </td>

      <td> </td>

      <td> </td>

    </tr>

    <tr>

      <td title="Capital E grave" align="center" bgcolor="mistyrose">&Egrave;

      </td>

      <td>Alt-0200</td>

      <td title="Capital E acute" align="center" bgcolor="mistyrose">&Eacute;

      </td>

      <td>Alt-0201</td>

      <td title="Capital E circumflex" align="center"

 bgcolor="mistyrose">&Ecirc; </td>

      <td>Alt-0202</td>

      <td> </td>

      <td> </td>

      <td title="Capital E umlaut" align="center" bgcolor="mistyrose">&Euml;

      </td>

      <td>Alt-0203</td>

      <td> </td>

      <td> </td>

      <td> </td>

      <td> </td>

    </tr>

    <tr>

      <td title="Small i grave" align="center" bgcolor="mistyrose">&igrave;

      </td>

      <td>Alt-0236</td>

      <td title="Small i acute" align="center" bgcolor="mistyrose">&iacute;

      </td>

      <td>Alt-0237</td>

      <td title="Small i circumflex" align="center" bgcolor="mistyrose">&icirc;

      </td>

      <td>Alt-0238</td>

      <td> </td>

      <td> </td>

      <td title="Small i umlaut" align="center" bgcolor="mistyrose">&iuml;

      </td>

      <td>Alt-0239</td>

      <td> </td>

      <td> </td>

      <td> </td>

      <td> </td>

    </tr>

    <tr>

      <td title="Capital I grave" align="center" bgcolor="mistyrose">&Igrave;

      </td>

      <td>Alt-0204</td>

      <td title="Capital I acute" align="center" bgcolor="mistyrose">&Iacute;

      </td>

      <td>Alt-0205</td>

      <td title="Capital I circumflex" align="center"

 bgcolor="mistyrose">&Icirc; </td>

      <td>Alt-0206</td>

      <td> </td>

      <td> </td>

      <td title="Capital I umlaut" align="center" bgcolor="mistyrose">&Iuml;

      </td>

      <td>Alt-0207</td>

      <th colspan="2" bgcolor="cornsilk">/ barra</th>

      <th colspan="2" bgcolor="cornsilk">&OElig; ligadura</th>

    </tr>

    <tr>

      <td title="Small o grave" align="center" bgcolor="mistyrose">&ograve;

      </td>

      <td>Alt-0242</td>

      <td title="Small o acute" align="center" bgcolor="mistyrose">&oacute;

      </td>

      <td>Alt-0243</td>

      <td title="Small o circumflex" align="center" bgcolor="mistyrose">&ocirc;

      </td>

      <td>Alt-0244</td>

      <td title="Small o tilde" align="center" bgcolor="mistyrose">&otilde;

      </td>

      <td>Alt-0245</td>

      <td title="Small o umlaut" align="center" bgcolor="mistyrose">&ouml;

      </td>

      <td>Alt-0246</td>

      <td title="Small o slash" align="center" bgcolor="mistyrose">&oslash;

      </td>

      <td>Alt-0248</td>

      <td title="Small oe ligature" align="center" bgcolor="mistyrose">&oelig;

      </td>

      <td>Use "[oe]"Alt-0339</td>

    </tr>

    <tr>

      <td title="Capital O grave" align="center" bgcolor="mistyrose">&Ograve;

      </td>

      <td>Alt-0210</td>

      <td title="Capital O acute" align="center" bgcolor="mistyrose">&Oacute;

      </td>

      <td>Alt-0211</td>

      <td title="Capital O circumflex" align="center"

 bgcolor="mistyrose">&Ocirc; </td>

      <td>Alt-0212</td>

      <td title="Capital O tilde" align="center" bgcolor="mistyrose">&Otilde;

      </td>

      <td>Alt-0213</td>

      <td title="Capital O umlaut" align="center" bgcolor="mistyrose">&Ouml;

      </td>

      <td>Alt-0214</td>

      <td title="Capital O slash" align="center" bgcolor="mistyrose">&Oslash;

      </td>

      <td>Alt-0216</td>

      <td title="Capital OE ligature" align="center" bgcolor="mistyrose">&OElig;

      </td>

      <td>Use "[OE]"Alt-0338</td>

    </tr>

    <tr>

      <td title="Small u grave" align="center" bgcolor="mistyrose">&ugrave;

      </td>

      <td>Alt-0249</td>

      <td title="Small u acute" align="center" bgcolor="mistyrose">&uacute;

      </td>

      <td>Alt-0250</td>

      <td title="Small u circumflex" align="center" bgcolor="mistyrose">&ucirc;

      </td>

      <td>Alt-0251</td>

      <td> </td>

      <td> </td>

      <td title="Small u umlaut" align="center" bgcolor="mistyrose">&uuml;

      </td>

      <td>Alt-0252</td>

      <td> </td>

      <td> </td>

      <td> </td>

      <td> </td>

    </tr>

    <tr>

      <td title="Capital U grave" align="center" bgcolor="mistyrose">&Ugrave;

      </td>

      <td>Alt-0217</td>

      <td title="Capital U acute" align="center" bgcolor="mistyrose">&Uacute;

      </td>

      <td>Alt-0218</td>

      <td title="Capital U circumflex" align="center"

 bgcolor="mistyrose">&Ucirc; </td>

      <td>Alt-0219</td>

      <td> </td>

      <td> </td>

      <td title="Capital U umlaut" align="center" bgcolor="mistyrose">&Uuml;

      </td>

      <td>Alt-0220</td>

      <th colspan="2" bgcolor="cornsilk">moeda </th>

      <th colspan="2" bgcolor="cornsilk">matem&aacute;tica </th>

    </tr>

    <tr>

      <td> </td>

      <td> </td>

      <td> </td>

      <td> </td>

      <td> </td>

      <td> </td>

      <td title="Small n tilde" align="center" bgcolor="mistyrose">&ntilde;

      </td>

      <td>Alt-0241</td>

      <td title="Small y umlaut" align="center" bgcolor="mistyrose">&yuml;

      </td>

      <td>Alt-0255</td>

      <td title="Cents" align="center" bgcolor="mistyrose">&cent; </td>

      <td>Alt-0162</td>

      <td title="plus/minus" align="center" bgcolor="mistyrose">&plusmn;

      </td>

      <td>Alt-0177</td>

    </tr>

    <tr>

      <td> </td>

      <td> </td>

      <td> </td>

      <td> </td>

      <td> </td>

      <td> </td>

      <td title="Capital N tilde" align="center" bgcolor="mistyrose">&Ntilde;

      </td>

      <td>Alt-0209</td>

      <td title="Capital Y umlaut" align="center" bgcolor="mistyrose">&Yuml;

      </td>

      <td>Alt-0159</td>

      <td title="Pounds" align="center" bgcolor="mistyrose">&pound; </td>

      <td>Alt-0163</td>

      <td title="Multiplication" align="center" bgcolor="mistyrose">&times;

      </td>

      <td>Alt-0215</td>

    </tr>

    <tr>

      <th colspan="2" bgcolor="cornsilk">cedilha </th>

      <th colspan="2" bgcolor="cornsilk">Island&ecirc;s </th>

      <th colspan="2" bgcolor="cornsilk">sinais </th>

      <th colspan="2" bgcolor="cornsilk">acentos </th>

      <th colspan="2" bgcolor="cornsilk">pontua&ccedil;&atilde;o </th>

      <td title="Yen" align="center" bgcolor="mistyrose">&yen; </td>

      <td>Alt-0165</td>

      <td title="Division" align="center" bgcolor="mistyrose">&divide; </td>

      <td>Alt-0247</td>

    </tr>

    <tr>

      <td title="Small c cedilla" align="center" bgcolor="mistyrose">&ccedil;

      </td>

      <td>Alt-0231</td>

      <td title="Capital Thorn" align="center" bgcolor="mistyrose">&THORN;

      </td>

      <td>Alt-0222</td>

      <td title="Copyright" align="center" bgcolor="mistyrose">&copy; </td>

      <td>Alt-0169</td>

      <td title="acute accent" align="center" bgcolor="mistyrose">&acute;

      </td>

      <td>Alt-0180</td>

      <td title="Inverted Question Mark" align="center"

 bgcolor="mistyrose">&iquest; </td>

      <td>Alt-0191</td>

      <td title="Dollars" align="center" bgcolor="mistyrose">$ </td>

      <td>Alt-0036</td>

      <td title="Logical Not" align="center" bgcolor="mistyrose">&not; </td>

      <td>Alt-0172</td>

    </tr>

    <tr>

      <td title="Capital C cedilla" align="center" bgcolor="mistyrose">&Ccedil;

      </td>

      <td>Alt-0199</td>

      <td title="Small thorn" align="center" bgcolor="mistyrose">&thorn;

      </td>

      <td>Alt-0254</td>

      <td title="Registration Mark" align="center" bgcolor="mistyrose">&reg;

      </td>

      <td>Alt-0174</td>

      <td title="umlaut accent" align="center" bgcolor="mistyrose">&uml;

      </td>

      <td>Alt-0168</td>

      <td title="Inverted Exclamation" align="center"

 bgcolor="mistyrose">&iexcl; </td>

      <td>Alt-0161</td>

      <td title="General Currency" align="center" bgcolor="mistyrose">&curren;

      </td>

      <td>Alt-0164</td>

      <td title="Degrees" align="center" bgcolor="mistyrose">&deg; </td>

      <td>Alt-0176</td>

    </tr>

    <tr>

      <th colspan="2" bgcolor="cornsilk">superescrito </th>

      <td title="Capital Eth" align="center" bgcolor="mistyrose">&ETH; </td>

      <td>Alt-0208</td>

      <td title="Trademark" align="center" bgcolor="mistyrose">&trade; </td>

      <td>Alt-0153</td>

      <td title="macron accent" align="center" bgcolor="mistyrose">&macr;

      </td>

      <td>Alt-0175</td>

      <td title="guillemot left" align="center" bgcolor="mistyrose">&laquo;

      </td>

      <td>Alt-0171</td>

      <td> </td>

      <td> </td>

      <td title="Micro" align="center" bgcolor="mistyrose">&micro; </td>

      <td>Alt-0181</td>

    </tr>

    <tr>

      <td title="superscript 1" align="center" bgcolor="mistyrose">&sup1;

      </td>

      <td>Alt-0185</td>

      <td title="Small eth" align="center" bgcolor="mistyrose">&eth; </td>

      <td>Alt-0240</td>

      <td title="Paragraph (pilcrow)" align="center" bgcolor="mistyrose">&para;

      </td>

      <td>Alt-0182</td>

      <td title="cedilla" align="center" bgcolor="mistyrose">&cedil; </td>

      <td>Alt-0184</td>

      <td title="guillemot right" align="center" bgcolor="mistyrose">&raquo;

      </td>

      <td>Alt-0187</td>

      <th colspan="2" bgcolor="cornsilk">ordinais </th>

      <td title="1/4 Fraction" align="center" bgcolor="mistyrose">&frac14;

      <sup><small>1</small></sup> </td>

      <td>Alt-0188</td>

    </tr>

    <tr>

      <td title="superscript 2" align="center" bgcolor="mistyrose">&sup2;

      </td>

      <td>Alt-0178</td>

      <th colspan="2" bgcolor="cornsilk">sz ligadura </th>

      <td title="Section" align="center" bgcolor="mistyrose">&sect; </td>

      <td>Alt-0167</td>

      <td> </td>

      <td> </td>

      <td title="Middle dot" align="center" bgcolor="mistyrose">&middot;

      </td>

      <td>Alt-0183</td>

      <td title="Masculine Ordinal" align="center" bgcolor="mistyrose">&ordm;

      </td>

      <td>Alt-0186</td>

      <td title="1/2 Fraction" align="center" bgcolor="mistyrose">&frac12;

      <sup><small>1</small></sup> </td>

      <td>Alt-0189</td>

    </tr>

    <tr>

      <td title="superscript 3" align="center" bgcolor="mistyrose">&sup3;

      </td>

      <td>Alt-0179</td>

      <td title="sz ligature" align="center" bgcolor="mistyrose">&szlig;

      </td>

      <td>Alt-0223</td>

      <td title="Broken Vertical bar" align="center" bgcolor="mistyrose">&brvbar;

      </td>

      <td>Alt-0166</td>

      <td> </td>

      <td> </td>

      <td title="asterisk" align="center" bgcolor="mistyrose">* </td>

      <td>Alt-0042</td>

      <td title="Feminine Ordinal" align="center" bgcolor="mistyrose">&ordf;

      </td>

      <td>Alt-0170</td>

      <td title="3/4 Fraction" align="center" bgcolor="mistyrose">&frac34;

      <sup><small>1</small></sup> </td>

      <td>Alt-0190</td>

    </tr>

  </tbody>

</table>

<br>

<p><sup><small>1</small></sup>N&atilde;o utilize estes

s&iacute;mbolos

de frac&ccedil;&otilde;es, excepto quando solicitado

nos&nbsp;<a href="#comments">Coment&aacute;rios do Projecto</a>.&nbsp;Por

norma siga as regras relativas &agrave;s <a href="#fract_s">Frac&ccedil;&otilde;es</a>.

(1/2, 1/4, 3/4, etc.)</p>

<p><b>Para Apple Macintosh</b>:

</p>

<ul compact="compact">

  <li>Pode utilizar a aplica&ccedil;&atilde;o de "Key Caps" como

refer&ecirc;ncia.<br>

Nos OS 9 &amp; anteriores, encontra-se no&nbsp;Apple Menu; no OS X

through 10.2, est&aacute; em&nbsp;Applications, Utilities folder.<br>

Surgir&aacute; uma imagem representativa do teclado, e

premindo&nbsp;shift, opt, command, ou combina&ccedil;&otilde;es destas

teclas mostrar&aacute; como se pode reproduzir cada car&aacute;cter.

Utilize

esta refer&ecirc;ncia para perceber como escrever um determinado

car&aacute;cter, ou utilize o "corta e cola" daqui para o texto no

ecr&atilde;

de revis&atilde;o.</li>

  <li>Nos OS X 10.3 e posterior, a mesma fun&ccedil;&atilde;o &eacute;

agora uma palete dispon&iacute;vel a partir doInput menu (o menu

drop-down anexada ao seu&nbsp;locale's flag icon na barra

de&nbsp;menu). Est&aacute; identificada como&nbsp;"Show Keyboard

Viewer." Se este n&atilde;o estiver no seu Input menu, ou se n&atilde;o

tiver esse menu, pode activ&aacute;-lo nas&nbsp;System

Preferences,&nbsp;"International" panel, e seleccinando a

op&ccedil;&atilde;o "Input Menu". Verifique que a

op&ccedil;&atilde;o&nbsp;"Show input menu in menu bar" &eacute;

seleccionada. Na&nbsp;spreadsheet view, procure&nbsp;"Keyboard Viewer"

para al&eacute;m de qualquer configura&ccedil;&atilde;o de

localiza&ccedil;&atilde;o ("locales") que use. </li>

  <li>Se estiver a utilizar a interface de revis&atilde;o

avan&ccedil;ada, o&nbsp;<i>more</i>

gera uma nova janela que conter&aacute; estes caracteres, que

poder&aacute; cortar e colar. </li>

  <li>Ou pode sempre utilizar os atalhos da Apple para estes caracteres.<br>

Este processo torna-se muito mais r&aacute;pido do que o corte e cola,

quando souber os atalhos de cor. <br>

Prima a tecla&nbsp;Opt enquanto escreve o&nbsp;acento e depois a letra

a ser acentuada (ou, em alguns casos, prima a tecla Opt e escreva o

s&iacute;mbolo). <br>

Estas instru&ccedil;&otilde;es s&atilde;o para o formato de

teclado&nbsp;US-English. Pode n&atilde;o funcionar para outros

formatos. <br>

A tabela em baixo, mostra os atalhos poss&iacute;veis&nbsp;(<a

 href="charapp.pdf">Clique para imprimir esta tabela</a>).<br>

N&atilde;o utilize outros caracteres especiais a n&atilde;o ser que o

Gestor

de Projecto assim o solicite nos <a href="#comments">Coment&aacute;rios

do Projecto</a>. </li>

</ul>

<br>

<a name="a_chars_mac"></a>

<table summary="Mac shortcuts" align="center" border="6" rules="all">

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

    <tr>

      <td title="Small a grave" align="center" bgcolor="mistyrose">&agrave;

      </td>

      <td>Opt-~, a</td>

      <td title="Small a acute" align="center" bgcolor="mistyrose">&aacute;

      </td>

      <td>Opt-e, a</td>

      <td title="Small a circumflex" align="center" bgcolor="mistyrose">&acirc;

      </td>

      <td>Opt-i, a</td>

      <td title="Small a tilde" align="center" bgcolor="mistyrose">&atilde;

      </td>

      <td>Opt-n, a</td>

      <td title="Small a umlaut" align="center" bgcolor="mistyrose">&auml;

      </td>

      <td>Opt-u, a</td>

      <td title="Small a ring" align="center" bgcolor="mistyrose">&aring;

      </td>

      <td>Opt-a </td>

      <td title="Small ae ligature" align="center" bgcolor="mistyrose">&aelig;

      </td>

      <td>Opt-' </td>

    </tr>

    <tr>

      <td title="Capital A grave" align="center" bgcolor="mistyrose">&Agrave;

      </td>

      <td>Opt-~, A</td>

      <td title="Capital A acute" align="center" bgcolor="mistyrose">&Aacute;

      </td>

      <td>Opt-e, A</td>

      <td title="Capital A circumflex" align="center"

 bgcolor="mistyrose">&Acirc; </td>

      <td>Opt-i, A</td>

      <td title="Capital A tilde" align="center" bgcolor="mistyrose">&Atilde;

      </td>

      <td>Opt-n, A</td>

      <td title="Capital A umlaut" align="center" bgcolor="mistyrose">&Auml;

      </td>

      <td>Opt-u, A</td>

      <td title="Capital A ring" align="center" bgcolor="mistyrose">&Aring;

      </td>

      <td>Opt-A </td>

      <td title="Capital AE ligature" align="center" bgcolor="mistyrose">&AElig;

      </td>

      <td>Opt-" </td>

    </tr>

    <tr>

      <td title="Small e grave" align="center" bgcolor="mistyrose">&egrave;

      </td>

      <td>Opt-~, e</td>

      <td title="Small e acute" align="center" bgcolor="mistyrose">&eacute;

      </td>

      <td>Opt-e, e</td>

      <td title="Small e circumflex" align="center" bgcolor="mistyrose">&ecirc;

      </td>

      <td>Opt-i, e</td>

      <td> </td>

      <td> </td>

      <td title="Small e umlaut" align="center" bgcolor="mistyrose">&euml;

      </td>

      <td>Opt-u, e</td>

      <td> </td>

      <td> </td>

      <td> </td>

      <td> </td>

    </tr>

    <tr>

      <td title="Capital E grave" align="center" bgcolor="mistyrose">&Egrave;

      </td>

      <td>Opt-~, E</td>

      <td title="Capital E acute" align="center" bgcolor="mistyrose">&Eacute;

      </td>

      <td>Opt-e, E</td>

      <td title="Capital E circumflex" align="center"

 bgcolor="mistyrose">&Ecirc; </td>

      <td>Opt-i, E</td>

      <td> </td>

      <td> </td>

      <td title="Capital E umlaut" align="center" bgcolor="mistyrose">&Euml;

      </td>

      <td>Opt-u, E</td>

      <td> </td>

      <td> </td>

      <td> </td>

      <td> </td>

    </tr>

    <tr>

      <td title="Small i grave" align="center" bgcolor="mistyrose">&igrave;

      </td>

      <td>Opt-~, i</td>

      <td title="Small i acute" align="center" bgcolor="mistyrose">&iacute;

      </td>

      <td>Opt-e, i</td>

      <td title="Small i circumflex" align="center" bgcolor="mistyrose">&icirc;

      </td>

      <td>Opt-i, i</td>

      <td> </td>

      <td> </td>

      <td title="Small i umlaut" align="center" bgcolor="mistyrose">&iuml;

      </td>

      <td>Opt-u, i</td>

      <td> </td>

      <td> </td>

      <td> </td>

      <td> </td>

    </tr>

    <tr>

      <td title="Capital I grave" align="center" bgcolor="mistyrose">&Igrave;

      </td>

      <td>Opt-~, I</td>

      <td title="Capital I acute" align="center" bgcolor="mistyrose">&Iacute;

      </td>

      <td>Opt-e, I</td>

      <td title="Capital I circumflex" align="center"

 bgcolor="mistyrose">&Icirc; </td>

      <td>Opt-i, I</td>

      <td> </td>

      <td> </td>

      <td title="Capital I umlaut" align="center" bgcolor="mistyrose">&Iuml;

      </td>

      <td>Opt-u, I</td>

      <th colspan="2" bgcolor="cornsilk">/ barra</th>

      <th colspan="2" bgcolor="cornsilk">&OElig; ligadura</th>

    </tr>

    <tr>

      <td title="Small o grave" align="center" bgcolor="mistyrose">&ograve;

      </td>

      <td>Opt-~, o</td>

      <td title="Small o acute" align="center" bgcolor="mistyrose">&oacute;

      </td>

      <td>Opt-e, o</td>

      <td title="Small o circumflex" align="center" bgcolor="mistyrose">&ocirc;

      </td>

      <td>Opt-i, o</td>

      <td title="Small o tilde" align="center" bgcolor="mistyrose">&otilde;

      </td>

      <td>Opt-n, o</td>

      <td title="Small o umlaut" align="center" bgcolor="mistyrose">&ouml;

      </td>

      <td>Opt-u, o</td>

      <td title="Small o slash" align="center" bgcolor="mistyrose">&oslash;

      </td>

      <td>Opt-o </td>

      <td title="Small oe ligature" align="center" bgcolor="mistyrose">&oelig;

      </td>

      <td>Use "[oe]"Opt-q</td>

    </tr>

    <tr>

      <td title="Capital O grave" align="center" bgcolor="mistyrose">&Ograve;

      </td>

      <td>Opt-~, O</td>

      <td title="Capital O acute" align="center" bgcolor="mistyrose">&Oacute;

      </td>

      <td>Opt-e, O</td>

      <td title="Capital I circumflex" align="center"

 bgcolor="mistyrose">&Ocirc; </td>

      <td>Opt-i, O</td>

      <td title="Capital O tilde" align="center" bgcolor="mistyrose">&Otilde;

      </td>

      <td>Opt-n, O</td>

      <td title="Capital O umlaut" align="center" bgcolor="mistyrose">&Ouml;

      </td>

      <td>Opt-u, O</td>

      <td title="Capital O slash" align="center" bgcolor="mistyrose">&Oslash;

      </td>

      <td>Opt-O </td>

      <td title="Capital OE ligature" align="center" bgcolor="mistyrose">&OElig;

      </td>

      <td>Use "[OE]"Opt-Q</td>

    </tr>

    <tr>

      <td title="Small u grave" align="center" bgcolor="mistyrose">&ugrave;

      </td>

      <td>Opt-~, u</td>

      <td title="Small u acute" align="center" bgcolor="mistyrose">&uacute;

      </td>

      <td>Opt-e, u</td>

      <td title="Small u circumflex" align="center" bgcolor="mistyrose">&ucirc;

      </td>

      <td>Opt-i, u</td>

      <td> </td>

      <td> </td>

      <td title="Small u umlaut" align="center" bgcolor="mistyrose">&uuml;

      </td>

      <td>Opt-u, u</td>

      <td> </td>

      <td> </td>

      <td> </td>

      <td> </td>

    </tr>

    <tr>

      <td title="Capital U grave" align="center" bgcolor="mistyrose">&Ugrave;

      </td>

      <td>Opt-~, U</td>

      <td title="Capital U acute" align="center" bgcolor="mistyrose">&Uacute;

      </td>

      <td>Opt-e, U</td>

      <td title="Capital U circumflex" align="center"

 bgcolor="mistyrose">&Ucirc; </td>

      <td>Opt-i, U</td>

      <td> </td>

      <td> </td>

      <td title="Capital U umlaut" align="center" bgcolor="mistyrose">&Uuml;

      </td>

      <td>Opt-u, U</td>

      <th colspan="2" bgcolor="cornsilk">moeda </th>

      <th colspan="2" bgcolor="cornsilk">matem&aacute;tica </th>

    </tr>

    <tr>

      <td> </td>

      <td> </td>

      <td> </td>

      <td> </td>

      <td> </td>

      <td> </td>

      <td title="Small n tilde" align="center" bgcolor="mistyrose">&ntilde;

      </td>

      <td>Opt-n, n</td>

      <td title="Small y umlaut" align="center" bgcolor="mistyrose">&yuml;

      </td>

      <td>Opt-u, y</td>

      <td title="Cents" align="center" bgcolor="mistyrose">&cent; </td>

      <td>Opt-4 </td>

      <td title="plus/minus" align="center" bgcolor="mistyrose">&plusmn;

      </td>

      <td>Opt-+ </td>

    </tr>

    <tr>

      <td> </td>

      <td> </td>

      <td> </td>

      <td> </td>

      <td> </td>

      <td> </td>

      <td title="Capital N tilde" align="center" bgcolor="mistyrose">&Ntilde;

      </td>

      <td>Opt-n, N</td>

      <td title="Capital Y umlaut" align="center" bgcolor="mistyrose">&Yuml;

      </td>

      <td>Opt-u, Y</td>

      <td title="Pounds" align="center" bgcolor="mistyrose">&pound; </td>

      <td>Opt-3 </td>

      <td title="Multiplication" align="center" bgcolor="mistyrose">&times;

      </td>

      <td>Opt-V </td>

    </tr>

    <tr>

      <th colspan="2" bgcolor="cornsilk">cedilha </th>

      <th colspan="2" bgcolor="cornsilk">Island&ecirc;s </th>

      <th colspan="2" bgcolor="cornsilk">sinais </th>

      <th colspan="2" bgcolor="cornsilk">acentos </th>

      <th colspan="2" bgcolor="cornsilk">pontua&ccedil;&atilde;o </th>

      <td title="Yen" align="center" bgcolor="mistyrose">&yen; </td>

      <td>Opt-y </td>

      <td title="Division" align="center" bgcolor="mistyrose">&divide; </td>

      <td>Opt-/ </td>

    </tr>

    <tr>

      <td title="Small c cedilla" align="center" bgcolor="mistyrose">&ccedil;

      </td>

      <td>Opt-c </td>

      <td title="Capital Thorn" align="center" bgcolor="mistyrose">&THORN;

      </td>

      <td>Shift-Opt-5</td>

      <td title="Copyright" align="center" bgcolor="mistyrose">&copy; </td>

      <td>Opt-g </td>

      <td title="acute accent" align="center" bgcolor="mistyrose">&acute;

      </td>

      <td>Opt-E </td>

      <td title="Inverted Question Mark" align="center"

 bgcolor="mistyrose">&iquest; </td>

      <td>Opt-? </td>

      <td title="Dollars" align="center" bgcolor="mistyrose">$ </td>

      <td>(none)&nbsp;&Dagger; </td>

      <td title="Logical Not" align="center" bgcolor="mistyrose">&not; </td>

      <td>Opt-l </td>

    </tr>

    <tr>

      <td title="Capital C cedilla" align="center" bgcolor="mistyrose">&Ccedil;

      </td>

      <td>Opt-C </td>

      <td title="Small thorn" align="center" bgcolor="mistyrose">&thorn;

      </td>

      <td>Shift-Opt-6</td>

      <td title="Registration Mark" align="center" bgcolor="mistyrose">&reg;

      </td>

      <td>Opt-r </td>

      <td title="umlaut accent" align="center" bgcolor="mistyrose">&uml;

      </td>

      <td>Opt-U </td>

      <td title="Inverted Exclamation" align="center"

 bgcolor="mistyrose">&iexcl; </td>

      <td>Opt-1 </td>

      <td title="General Currency" align="center" bgcolor="mistyrose">&curren;

      </td>

      <td>Shift-Opt-2</td>

      <td title="Degrees" align="center" bgcolor="mistyrose">&deg; </td>

      <td>Opt-* </td>

    </tr>

    <tr>

      <th colspan="2" bgcolor="cornsilk">superescrito </th>

      <td title="Capital Eth" align="center" bgcolor="mistyrose">&ETH; </td>

      <td>(none)&nbsp;&Dagger; </td>

      <td title="Trademark" align="center" bgcolor="mistyrose">&trade; </td>

      <td>Opt-2 </td>

      <td title="macron accent" align="center" bgcolor="mistyrose">&macr;

      </td>

      <td>Shift-Opt-,</td>

      <td title="guillemot left" align="center" bgcolor="mistyrose">&laquo;

      </td>

      <td>Opt-\ </td>

      <td> </td>

      <td> </td>

      <td title="Micro" align="center" bgcolor="mistyrose">&micro; </td>

      <td>Opt-m </td>

    </tr>

    <tr>

      <td title="superscript 1" align="center" bgcolor="mistyrose">&sup1;

      </td>

      <td>(none)&nbsp;&Dagger; </td>

      <td title="Small eth" align="center" bgcolor="mistyrose">&eth; </td>

      <td>(none)&nbsp;&Dagger; </td>

      <td title="Paragraph (pilcrow)" align="center" bgcolor="mistyrose">&para;

      </td>

      <td>Opt-7 </td>

      <td title="cedilla" align="center" bgcolor="mistyrose">&cedil; </td>

      <td>Opt-Z </td>

      <td title="guillemot right" align="center" bgcolor="mistyrose">&raquo;

      </td>

      <td>Shift-Opt-\</td>

      <th colspan="2" bgcolor="cornsilk">ordinais </th>

      <td title="1/4 Fraction" align="center" bgcolor="mistyrose">&frac14;

      </td>

      <td>(none)&nbsp;&Dagger;<sup><small>1</small></sup> </td>

    </tr>

    <tr>

      <td title="superscript 2" align="center" bgcolor="mistyrose">&sup2;

      </td>

      <td>(none)&nbsp;&Dagger; </td>

      <th colspan="2" bgcolor="cornsilk">sz ligadura </th>

      <td title="Section" align="center" bgcolor="mistyrose">&sect; </td>

      <td>Opt-6 </td>

      <td> </td>

      <td> </td>

      <td title="Middle dot" align="center" bgcolor="mistyrose">&middot;

      </td>

      <td>Opt-8 </td>

      <td title="Masculine Ordinal" align="center" bgcolor="mistyrose">&ordm;

      </td>

      <td>Opt-0 </td>

      <td title="1/2 Fraction" align="center" bgcolor="mistyrose">&frac12;

      </td>

      <td>(none)&nbsp;&Dagger;<sup><small>1</small></sup> </td>

    </tr>

    <tr>

      <td title="superscript 3" align="center" bgcolor="mistyrose">&sup3;

      </td>

      <td>(none)&nbsp;&Dagger; </td>

      <td title="sz ligature" align="center" bgcolor="mistyrose">&szlig;

      </td>

      <td>Opt-s </td>

      <td title="Broken Vertical bar" align="center" bgcolor="mistyrose">&brvbar;

      </td>

      <td>(none)&nbsp;&Dagger; </td>

      <td> </td>

      <td> </td>

      <td title="asterisk" align="center" bgcolor="mistyrose">* </td>

      <td>(none)&nbsp;&Dagger; </td>

      <td title="Feminine Ordinal" align="center" bgcolor="mistyrose">&ordf;

      </td>

      <td>Opt-9 </td>

      <td title="3/4 Fraction" align="center" bgcolor="mistyrose">&frac34;

      </td>

      <td>(none)&nbsp;&Dagger;<sup><small>1</small></sup></td>

    </tr>

  </tbody>

</table>

<p>&Dagger;&nbsp;Nota: N&atilde;o existem atalhos, utilize os menus.</p>

<sup><small>1</small></sup>N&atilde;o utilize estes s&iacute;mbolos

de frac&ccedil;&otilde;es, excepto quando solicitado nos&nbsp;<a

 href="#comments">Coment&aacute;rios do Projecto</a>.&nbsp;Por

norma siga as regras relativas &agrave;s <a href="#fract_s">Frac&ccedil;&otilde;es</a>.

(1/2, 1/4, 3/4, etc.)

<h3><a name="d_chars">Caracteres com Sinais Diacr&iacute;ticos</a></h3>

<p>Em alguns projectos, encontrar&aacute; caracteres como sinais

especiais, a cima ou abaixo dos caracteres latinos normais (A..Z). A

estes d&aacute;-se o nome de <i>sinais diacr&iacute;ticos</i>.

Atribuem uma pron&uacute;ncia especial a este car&aacute;cter. Para a

revis&atilde;o, indicamo-los no texto ASCII utilizando uma

codifica&ccedil;&atilde;o espec&iacute;fica. Por exemplo:&nbsp;<tt>[)x]</tt>

para sinalizar acentos em forma de "u" em cima, ou <tt>[x)]</tt> em

baixo.

</p>

<p>Certifique-se que coloca estes caracteres entre par&ecirc;nteses

recto (<tt>[&nbsp;]</tt>), para que o p&oacute;s-processador saiba

qual a letra correspondente. Estes caracteres ser&atilde;o ent&atilde;o

substitu&iacute;dos pelos s&iacute;mbolos correspondentes aceites, por

cada vers&atilde;o&nbsp;que&nbsp;produzida do texto. Ex: 7-bit ASCII,

8-bit, Unicode, HTML, etc.

</p>

Tenha em aten&ccedil;&atilde;o que alguns destes sinais surgem em

alguns caracteres (principalmente em vogais). Quando tal acontece,

verifique se o nosso conjunto de caracteres&nbsp;Latin-1 j&aacute;

inclui esse car&aacute;cter com o sinal diacr&iacute;tico. <b>Nestes

casos

utilize o car&aacute;cter

Latin-1 (ver</b><b>&nbsp;<a href="#a_chars">aqui</a>) </b><b>dispon&iacute;vel

no menu do ecr&atilde; de revis&atilde;o.</b><br>

<p>A tabela seguinte apresenta&nbsp;a lista de c&oacute;digos especiais

que utilizamos actualmente:<br>

O "x" representa um car&aacute;cter com um sinal diacr&iacute;tico.<br>

Ao rever, substitua o&nbsp;"<tt>x</tt>"

pelo car&aacute;cter do texto a rever.

</p>

<!-- diacritical mark above below macron (straight line) [=x] [x=] 2 dots (diaresis or umlaut) [:x] [x:] 1 dot [.x] [x.] grave accent ['x] [x'] or [/x] [x/] acute (aigu) accent [`x] [x`] or [\x] [x\] circumflex [^x] [x^] caron (v-shaped symbol) [vx] [xv] breve (u-shaped symbol) [)x] [x)] tilde [~x] [x~] cedilla [,x] [x,] -->

<table summary="Diacriticals" align="center" border="6" rules="all">

  <tbody>

    <tr bgcolor="cornsilk">

      <th colspan="4"><span

 style="font-size: 12pt; font-family: &quot;Times New Roman&quot;;">S&iacute;mbolos

de Revis&atilde;o para Sinais Diacr&iacute;ticos</span></th>

    </tr>

    <tr bgcolor="cornsilk">

      <th><span style="font-size: 12pt; font-family: &quot;Times New Roman&quot;;">sinal

diacr&iacute;tico</span></th>

      <th>exemplo</th>

      <th>em cima</th>

      <th>em baixo</th>

    </tr>

    <tr>

      <td><span style="font-size: 12pt; font-family: &quot;Times New Roman&quot;;">macron

(linha recta)</span></td>

      <td align="center">&macr;</td>

      <td align="center"><tt>[=x]</tt></td>

      <td align="center"><tt>[x=]</tt></td>

    </tr>

    <tr>

      <td>2 pontos (umlaut)</td>

      <td align="center">&uml;</td>

      <td align="center"><tt>[:x]</tt></td>

      <td align="center"><tt>[x:]</tt></td>

    </tr>

    <tr>

      <td>1 ponto</td>

      <td align="center">&middot;</td>

      <td align="center"><tt>[.x]</tt></td>

      <td align="center"><tt>[x.]</tt></td>

    </tr>

    <tr>

      <td>acento grave</td>

      <td align="center">`</td>

      <td align="center"><tt>[`x]</tt> or <tt>[\x]</tt></td>

      <td align="center"><tt>[x`]</tt> or <tt>[x\]</tt></td>

    </tr>

    <tr>

      <td>acento agudo (aigu)</td>

      <td align="center">&acute;</td>

      <td align="center"><tt>['x]</tt> or <tt>[/x]</tt></td>

      <td align="center"><tt>[x']</tt> or <tt>[x/]</tt></td>

    </tr>

    <tr>

      <td>acento circunflexo</td>

      <td align="center">&circ;</td>

      <td align="center"><tt>[^x]</tt></td>

      <td align="center"><tt>[x^]</tt></td>

    </tr>

    <tr>

      <td><span style="font-size: 12pt; font-family: &quot;Times New Roman&quot;;">caron

(s&iacute;mbolo em forma de v)</span></td>

      <td align="center"><font size="-2">&or;</font></td>

      <td align="center"><tt>[vx]</tt></td>

      <td align="center"><tt>[xv]</tt></td>

    </tr>

    <tr>

      <td><span style="font-size: 12pt; font-family: &quot;Times New Roman&quot;;">breve

(s&iacute;mbolo em forma de u)</span></td>

      <td align="center"><font size="-2">&cup;</font></td>

      <td align="center"><tt>[)x]</tt></td>

      <td align="center"><tt>[x)]</tt></td>

    </tr>

    <tr>

      <td>til</td>

      <td align="center">&tilde;</td>

      <td align="center"><tt>[~x]</tt></td>

      <td align="center"><tt>[x~]</tt></td>

    </tr>

    <tr>

      <td>cedilha</td>

      <td align="center">&cedil;</td>

      <td align="center"><tt>[,x]</tt></td>

      <td align="center"><tt>[x,]</tt></td>

    </tr>

  </tbody>

</table>

<h3><a name="f_chars">Caracteres Non-Latin</a></h3>

<p>H&aacute; projectos que cont&ecirc;m texto impresso em caracteres

non-Latin; ou seja, outros caracteres para al&eacute;m dos latinos de A

a&nbsp;Z,

como &eacute; o caso de caracteres&nbsp;Gregos, Cir&iacute;licos (usado

na l&iacute;ngua russa, eslavo e outras l&iacute;nguas), Hebraicos ou

&Aacute;rabes.

</p>

<p>No caso do Grego, deve tentar transliterar. A

translitera&ccedil;&atilde;o consiste em converter cada car&aacute;cter

do

texto estrangeiro na(s) letra(s) ASCII Latin equivalentes. No

ecr&atilde; de revis&atilde;o, encontrar&aacute; uma ferramenta de

translitera&ccedil;&atilde;o do Grego, tornando esta tarefa mais

simples.

</p>

<p>Clique no bot&atilde;o "Greek" no final da p&aacute;gina do

ecr&atilde; de revis&atilde;o, para que surja uma janela com esta

ferramenta. J&aacute; com ela &agrave; frente, clique nos caracteres

gregos equivalentes aos da palavra ou frase que estiver a transliterar,

e os caracteres ASCII correctos aparecer&atilde;o na caixa de texto.

Quando tiver terminado, fa&ccedil;a um "corte e cole" deste texto

transliterado para a p&aacute;gina que est&aacute; a rever.

Coloque-o&nbsp;entre os s&iacute;mbolos que sinalizam a l&iacute;ngua

grega&nbsp;<tt>[Greek: </tt>texto transliterado<tt>]</tt>. Por

exemplo, <b>&Beta;&iota;&beta;&lambda;&omicron;&sigmaf;</b>

ser&aacute; revista como <tt>[Greek: Biblos]</tt>.

("Livro"&mdash;exemplo apropriado para o&nbsp;DP!)

</p>

<p>Se n&atilde;o estiver seguro quanto &agrave; sua

translitera&ccedil;&atilde;o, sinalize-a com um&nbsp;<tt>*</tt>

de forma a&nbsp;chamar a aten&ccedil;&atilde;o, do revisor seguinte ou

do p&oacute;s-processador, para este facto.

</p>

Para as outras l&iacute;nguas que n&atilde;o sejam facilmente

transliteradas, como por exemplo o Cir&iacute;lico, Hebraico ou

&Aacute;rabe, reveja o texto colocando-o entre os caracteres

apropriados; <tt>[Cyrillic:&nbsp;**]</tt>, <tt>[Hebrew:&nbsp;**]</tt>

ou

<tt>[Arabic:&nbsp;**]</tt> respectivamente. O texto deve ser

inalterado.&nbsp;Coloque&nbsp;<tt>**</tt> para que o

p&oacute;s-processador possa revolver a quest&atilde;o posteriormente.<!-- END RR -->

<ul compact="compact">

  <li>Grego: <a href="%3C?%20echo%20$PG_greek_howto_url;%20?%3E">Greek

HOWTO</a>&nbsp;(do Projecto Gutenberg) ou atrav&eacute;s da janela de

ferramentas "Greek" no ecr&atilde; de revis&atilde;o. <span

 id="lblDlpoDefinicao"><font class="texto"><font class="verbete">Cir&iacute;lico</font></font></span>:

Embora exista a

translitera&ccedil;&atilde;o de cir&iacute;lico, s&oacute; a

recomendamos se for fluente em l&iacute;nguas que o utilizem. Caso

contr&aacute;rio, &nbsp;marque-a conforme indic&aacute;mos em cima.

Esta&nbsp;<a href="http://learningrussian.com/transliteration.htm">Tabela

de Translitera&ccedil;&atilde;o</a><span

 style="text-decoration: underline;"></span> pode ser-lhe &uacute;til. </li>

  <li>Hebraico e &Aacute;rabe: S&oacute; recomendado para pessoas que

sejam fluentes nestas l&iacute;nguas. H&aacute; grandes dificuldades na

translitera&ccedil;&atilde;o destas l&iacute;nguas e nem o <a

 href="../../../Trabalho/Proofreaders/">Distributed

Proofreaders</a> nem o <a

 href="../../../Trabalho/Proofreaders/imagens/%3C?%20echo%20$PG_home_url;%20?%3E">Projecto

Gutenberg</a> definiram um modo de procedimento. </li>

</ul>

<p>Estes caracteres devem ser inseridos no texto como os caracteres

Latin.&nbsp;(<b>SEM translitera&ccedil;&atilde;o!</b>)

</p>

Se todo o documento estiver escrito em caracteres non-Latin script,

&eacute; prefer&iacute;vel instalar um driver de teclado que

identifique a l&iacute;ngua. Para mais informa&ccedil;&otilde;es,

consulte o manual do seu sistema operativo.<br>

<br>

Se surgirem apenas ocasionalmente, pode utilizar um programa externo

para os submeter. Veja&nbsp;<a href="#a_chars">aqui</a> alguns desses

programas.<br>

<br>

Se tiver d&uacute;vidas quanto a um determinado car&aacute;cter ou

acentua&ccedil;&atilde;o, sinalize a d&uacute;vida com um * chamando a

aten&ccedil;&atilde;o do pr&oacute;ximo revisor ou

p&oacute;s-processador para essa quest&atilde;o.<br>

<br>

Para aqueles caracteres um pouco mais complicados de submeter, como

&eacute; o caso do &aacute;rabe, coloque o texto entre os

s&iacute;mbolos apropriados: [Arabic: **] e deixe o texto inalterado.

Coloque ** para que o p&oacute;s-processador possa revolver a

quest&atilde;o posteriormente.

<h3><a name="fract_s">Frac&ccedil;&otilde;es</a></h3>

<p>Reveja as <b>frac&ccedil;&otilde;es&nbsp;</b>da seguinte forma: <tt>2&frac12;</tt>

&eacute; revista como <tt>2-1/2</tt>.

O h&iacute;fen impede que o n&uacute;mero e a frac&ccedil;&atilde;o

sejam separados na formata&ccedil;&atilde;o autom&aacute;tica realizada

no p&oacute;s-processamento.

</p>

<h3><a name="em_dashes">Tra&ccedil;os, H&iacute;fenes

e Sinais de

Subtrac&ccedil;&atilde;o</a></h3>

<p>Encontrar&aacute; nos

livros&nbsp;quatro tipos principais: </p>

<ol compact="compact">

  <li><i>H&iacute;fenes</i>.

S&atilde;o usados para&nbsp;<b>unir</b>

palavras, ou prefixos ou sufixos a uma palavra.<br>

Reveja-os como um h&iacute;fen simples, sem espa&ccedil;os dos dois

lados.<br>

Repare na excep&ccedil;&atilde;o no segundo exemplo em baixo.</li>

  <li><i>Tra&ccedil;os</i>.

S&atilde;o um pouco mais longos, e

s&atilde;o usados na <span style="font-weight: bold;">uni&atilde;o </span>de

n&uacute;meros, ou como sinais matem&aacute;ticos de <span

 style="font-weight: bold;">subtrac&ccedil;&atilde;o</span>.<br>

Reveja-os tamb&eacute;m como um h&iacute;fen &uacute;nico. O uso de

espa&ccedil;amento &eacute; determinado pela forma como surge no livro

original; geralmente n&atilde;o existem espa&ccedil;os na uni&atilde;o

de n&uacute;meros, mas existem para os sinais de

menos/subtrac&ccedil;&atilde;o (por vezes em ambos os lados, outras

s&oacute; de um).</li>

  <li><i>Tra&ccedil;os

Longos</i>. S&atilde;o&nbsp;<b>separadores</b>

entre palavras&mdash;para dar &ecirc;nfase desta

forma&mdash;ou quando

o narrador se "engasga"&mdash;!&nbsp;<br>

Reveja-os com dois h&iacute;fenes se o tra&ccedil;o&nbsp;for curto e

quatro h&iacute;fenes se for longo. N&atilde;o deixe espa&ccedil;o

antes nem depois, mesmo que o exista na imagem do livro original.</li>

  <li><span style="font-style: italic;">Nomes ou Palavras

Deliberadamente Omitidos ou Censurados</span><br>

Reveja-os com 4 h&iacute;fenes. Quando substitui&nbsp;uma

palavra, deixe

mais ou menos o mesmo espa&ccedil;o que essa palavra teria. Se for

parte de uma palavra, ent&atilde;o n&atilde;o deixe

espa&ccedil;os&mdash;juntando-a ao resto da palavra. </li>

</ol>

<p> Nota: Se um tra&ccedil;o

aparecer no princ&iacute;pio ou no final

de uma linha do seu texto revisto, junte-a &agrave; linha

anterior/posterior para que n&atilde;o exista espa&ccedil;os

nem

quebras de linha &agrave; sua volta. S&oacute; se o autor usar

um

tra&ccedil;o no in&iacute;cio ou no final de um

par&aacute;grafo, verso

ou di&aacute;logo (travess&atilde;o), dever&aacute;

deix&aacute;-lo no

in&iacute;cio ou final de uma linha.

</p>

<p><b>Exemplos</b>&mdash;Tra&ccedil;os, H&iacute;fenes e Sinais de

Subtrac&ccedil;&atilde;o:

</p>

<table summary="Hyphens and Dashes" align="center" border="1"

 cellpadding="4" cellspacing="0" width="100%">

  <tbody>

    <tr>

      <th bgcolor="cornsilk" valign="top">Imagem Original:</th>

      <th bgcolor="cornsilk" valign="top">Texto Revisto Correctamente:</th>

      <th bgcolor="cornsilk" valign="top">Tipo</th>

    </tr>

    <tr>

      <td valign="top">semi-detached</td>

      <td valign="top"><tt>semi-detached</tt></td>

      <td> H&iacute;fen</td>

    </tr>

    <tr>

      <td valign="top">four-part harmony</td>

      <td valign="top"><tt>four-part harmony</tt></td>

      <td>H&iacute;fen</td>

    </tr>

    <tr>

      <td valign="top">discoveries which the Crus-<br>

aders made and brought home with</td>

      <td valign="top"><tt>discoveries which the Crusaders<br>

made and brought home with</tt></td>

      <td>H&iacute;fen</td>

    </tr>

    <tr>

      <td valign="top">factors which mold char-<br>

acter&mdash;environment, training and heritage,</td>

      <td valign="top"><tt>factors which mold character--environment,<br>

training and heritage,</tt> </td>

      <td>H&iacute;fen</td>

    </tr>

    <tr>

      <td valign="top">See pages 21&ndash;25</td>

      <td valign="top"><tt>See pages 21-25</tt></td>

      <td>Tra&ccedil;o</td>

    </tr>

    <tr>

      <td valign="top">&ndash;14&deg; below zero</td>

      <td valign="top"><tt>-14&deg; below zero</tt></td>

      <td>Tra&ccedil;o</td>

    </tr>

    <tr>

      <td valign="top">X &ndash; Y = Z</td>

      <td valign="top"><tt>X - Y = Z</tt></td>

      <td>Subtrac&ccedil;&atilde;o</td>

    </tr>

    <tr>

      <td valign="top">2&ndash;1/2</td>

      <td valign="top"><tt>2-1/2</tt></td>

      <td>Tra&ccedil;o</td>

    </tr>

    <tr>

      <td valign="top">I am hurt;&mdash;A plague<br>

on both your houses!&mdash;I am dead.</td>

      <td valign="top"><tt>I am hurt;--A plague<br>

on both your houses!--I am dead.</tt></td>

      <td>Tra&ccedil;o</td>

    </tr>

    <tr>

      <td valign="top">sensations&mdash;sweet, bitter, salt, and sour<br>

&mdash;if even all of these are simple tastes.</td>

      <td valign="top"><tt>sensations--sweet, bitter, salt,&nbsp;ur--if<br>

even all of these are simple tastes. What</tt></td>

      <td>Tra&ccedil;o</td>

    </tr>

    <tr>

      <td valign="top">senses&mdash;touch, smell, hearing, and

sight&mdash;<br>

with which we are here concerned,</td>

      <td valign="top"><tt>senses--touch, smell, hearing, and

sight--with<br>

which we are here concerned,</tt></td>

      <td>Tra&ccedil;o</td>

    </tr>

    <tr>

      <td valign="top">It is the east, and Juliet is the sun!&mdash;</td>

      <td valign="top"><tt>It is the east, and Juliet is the sun!--</tt></td>

      <td>Tra&ccedil;o</td>

    </tr>

    <tr>

      <td valign="top">As the witness Mr. &mdash;&mdash; testified,</td>

      <td valign="top"><tt>As the witness Mr. ---- testified,</tt></td>

      <td>Tra&ccedil;o longo</td>

    </tr>

    <tr>

      <td valign="top">As the witness Mr. S&mdash;&mdash; testified,</td>

      <td valign="top"><tt>As the witness Mr. S---- testified,</tt></td>

      <td>Tra&ccedil;o longo</td>

    </tr>

    <tr>

      <td valign="top">the famous detective of &mdash;&mdash;B Baker St.</td>

      <td valign="top"><tt>the famous detective of ----B Baker St.</tt></td>

      <td>Tra&ccedil;o longo</td>

    </tr>

    <tr>

      <td valign="top">&ldquo;You &mdash;&mdash; Yankee&rdquo;, she

yelled.</td>

      <td valign="top"><tt>"You ---- Yankee", she yelled.</tt></td>

      <td>Tra&ccedil;o longo</td>

    </tr>

  </tbody>

</table>

<h3><a name="eol_hyphen">Hifeniza&ccedil;&atilde;o

no Final da Linha</a></h3>

<p>Quando surge um

h&iacute;fen no final da linha, junte as duas partes

da palavra. Se for uma palavra realmente hifenizada como &eacute; o

caso de&nbsp;"auto-realiza&ccedil;&atilde;o", junte as

duas partes

deixando o h&iacute;fen no meio. Mas se apenas estiver hifenizada

por

n&atilde;o caber na mesma linha, ou seja, se n&atilde;o for uma

palavra

habitualmente hifenizada, junte-as as partes e remova o

h&iacute;fen.

Junte-as na linha de cima, e acrescente depois uma quebra de linha para

preservar a formata&ccedil;&atilde;o da linha--tornando o

trabalho do

revisor da segunda ronda mais f&aacute;cil. Veja os&nbsp;<a

 href="#em_dashes">Tra&ccedil;os, H&iacute;fenes e Sinais de

Subtrac&ccedil;&atilde;o</a>&nbsp;como exemplo em cada um dos

casos&nbsp;(<tt>es-treito</tt>

torna-se&nbsp;<tt>estreito</tt>,

mas <tt>guarda-chuva</tt>

mant&eacute;m o h&iacute;fen). Se a palavra

for seguida de pontua&ccedil;&atilde;o, tamb&eacute;m esta

vai para a

linha de cima.

</p>

<p> Palavras&nbsp;como to-day

e to-morrow que actualmente j&aacute; n&atilde;o

levam h&iacute;fen s&atilde;o por vezes hifenizadas em livros

antigos

como os que trabalhamos. Deixe-as hifenizadas como o autor as deixou.

Se n&atilde;o tiver a certeza se o autor as hifenizou ou

n&atilde;o,

deixe o h&iacute;fen, marque com um * depois e junte as partes.

Assim:&nbsp;<tt>to-*day</tt>.

O asterisco chamar&aacute; a

aten&ccedil;&atilde;o do p&oacute;s-processador, que tem

acesso a todas

as p&aacute;ginas, e pode saber o modo como o autor escreve

geralmente

determinada palavra.

</p>

<h3><a name="eop_hyphen">Hifeniza&ccedil;&atilde;o

no Final da

P&aacute;gina</a></h3>

<p>Reveja a

hifeniza&ccedil;&atilde;o no final da p&aacute;gina,

deixando o h&iacute;fen no final da &uacute;ltima linha, e

marque-o&nbsp;com asterisco. Por exemplo, reveja:<br>

&nbsp;<br>

&nbsp; &nbsp; &nbsp; &nbsp;something Pat had already

become accus-<br>

como:<br>

&nbsp; &nbsp; &nbsp; &nbsp;<tt>something

Pat had already become accus-*</tt>

</p>

<p>Nas p&aacute;ginas que

come&ccedil;arem com apenas uma parte da

palavra, coloque um * antes da palavra parcial. Continuando o exemplo

anterior:<br>

&nbsp;<br>

&nbsp; &nbsp; &nbsp; &nbsp;tomed to from having to do

his own family<br>

como:<br>

&nbsp; &nbsp; &nbsp; &nbsp;<tt>*tomed

to from having to do his own

family</tt>

</p>

<p>Os asteriscos

chamar&atilde;o a aten&ccedil;&atilde;o do

p&oacute;s-processador de que precisa de juntar as duas partes da

palavra, quando estiver a fazer o tratamento do e-book final.

</p>

<h3><a name="para_space">Espa&ccedil;o entre Par&aacute;grafos

e Avan&ccedil;os</a></h3>

<p>Coloque uma linha em branco antes do in&iacute;cio dos

par&aacute;grafos, mesmo quando um par&aacute;grafo come&ccedil;a no

topo da p&aacute;gina. N&atilde;o deve deixar espa&ccedil;o no

in&iacute;cio dos

par&aacute;grafos, mas se os avan&ccedil;os j&aacute; existirem,

n&atilde;o necessita de remover esses espa&ccedil;os--isso pode ser

feito automaticamente no p&oacute;s-processamento.</p>

Veja a imagem e o texto das&nbsp;<a href="#para_side">Notas (Sidenotes)</a>&nbsp;como

exemplo.

<h3><a name="mult_col">V&aacute;rias Colunas</a></h3>

<p>Reveja o texto impresso em duas colunas, como se de uma &uacute;nica

se tratasse.&nbsp;</p>

<p>Texto de v&aacute;rias colunas deve ser revisto como uma coluna

s&oacute;, colocando o texto da da esquerda em primeiro lugar, depois o

da seguinte e por a&iacute; adiante. N&atilde;o &eacute;

necess&aacute;rio marcar o local onde as colunas estavam divididas:

junte-as.&nbsp;

</p>

<p>Veja tamb&eacute;m as sec&ccedil;&otilde;es <a href="#bk_index">&Iacute;ndices</a>

e <a href="#tables">Tabelas</a> das Regras de Revis&atilde;o.

</p>

<h3><a name="blank_pg">P&aacute;gina em Branco</a></h3>

<p>A maioria das p&aacute;ginas em branco, ou apenas com uma imagem,

estar&aacute; marcada com&nbsp;<tt>[Blank Page]</tt>. Mantenha esta

marca&ccedil;&atilde;o. Se existir uma p&aacute;gina em branco, em que

esta marca&ccedil;&atilde;o n&atilde;o apare&ccedil;a n&atilde;o

&eacute; necess&aacute;rio adicion&aacute;-la. </p>

Se houver algum texto para rever na &aacute;rea de texto, ou se

tiver uma imagem sem texto, siga as instru&ccedil;&otilde;es descritas

em&nbsp;<a href="#bad_image">P&aacute;gina Danificada</a>

ou&nbsp;<a href="#bad_text">Texto Danificado</a>.

<h3><a name="page_hf">Cabe&ccedil;alho/Rodap&eacute; da P&aacute;gina</a></h3>

<p> Remova o cabe&ccedil;alho e rodap&eacute; das p&aacute;ginas,

mas&nbsp;<em>n&atilde;o</em> as<em></em>&nbsp;<a href="#footnotes">notas

de rodap&eacute;</a> do texto.

</p>

<p>O cabe&ccedil;alho&nbsp;est&aacute; situado normalmente no topo da

imagem junto ao n&uacute;mero da p&aacute;gina. O cabe&ccedil;alho pode

ser igual ao longo de todo o livro (geralmente &eacute; o t&iacute;tulo

e o nome do autor), de cada cap&iacute;tulo (geralmente o n&uacute;mero

do cap&iacute;tulo), ou ser diferente em cada p&aacute;gina

(descrevendo a ac&ccedil;&atilde;o dessa p&aacute;gina). Remova-os

todos, inclusive o n&uacute;mero da p&aacute;gina.

</p>

O&nbsp;<a href="#chap_head">T&iacute;tulo do Cap&iacute;tulo</a>&nbsp;come&ccedil;a

geralmente um pouco mais abaixo e n&atilde;o tem o

n&uacute;mero da p&aacute;gina na mesma linha. Veja a seguinte

sec&ccedil;&atilde;o para ver um exemplo espec&iacute;fico.<br>

<table summary="Page Headers and Footers" align="center" border="1"

 cellpadding="4" cellspacing="0" width="100%">

  <tbody>

    <tr>

      <th align="left" bgcolor="cornsilk">Imagem de Exemplo:</th>

    </tr>

    <tr align="left">

      <td valign="top" width="100%">

      <img src="http://www.pgdp.net/c/faq/foot.png" alt="" height="850"

 width="500"></td>

    </tr>

    <tr>

      <th align="left" bgcolor="cornsilk">Texto Revisto Correctamente:</th>

    </tr>

    <tr>

      <td valign="top" width="100%">

      <table summary="" align="left" border="0">

        <tbody>

          <tr>

            <td> <tt>In

the United States?[*] In a railroad? In a mining company?<br>

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

symbol of its power; its seal, of its authority."--Dole. </tt>

            </td>

          </tr>

        </tbody>

      </table>

      </td>

    </tr>

  </tbody>

</table>

<h3><a name="chap_head">T&iacute;tulos de Cap&iacute;tulo</a></h3>

<p>Reveja os t&iacute;tulos de cap&iacute;tulo tal como surgem no

texto. </p>

Um t&iacute;tulo de cap&iacute;tulo pode come&ccedil;ar um pouco

mais abaixo do que o&nbsp;<a href="#page_hf">cabe&ccedil;alho</a>

e

n&atilde;o ter&aacute; o n&uacute;mero da p&aacute;gina na mesma linha.

Os t&iacute;tulos de Cap&iacute;tulo s&atilde;o impressos geralmente em

letra mai&uacute;scula; se assim for, mantenha-os desta forma.

Tome aten&ccedil;&atilde;o &agrave; poss&iacute;vel falta de aspas

no in&iacute;cio de um par&aacute;grafo.&nbsp;Alguns editores podem

n&atilde;o as ter inclu&iacute;do ou o OCR pode n&atilde;o ter captado

devido ao in&iacute;cio em mai&uacute;sculas no original. Se o autor

come&ccedil;ar o par&aacute;grafo com um di&aacute;logo, coloque

aspas.&nbsp;<!-- END RR -->

<h3><a name="illust">Figuras</a></h3>

<p>Reveja as legendas tal como surgem impressas, mantendo eventuais

quebras de linha. Se a legenda estiver no meio&nbsp;de um

par&aacute;grafo, insira linhas em branco para as separar do restante

texto. Se <span style="font-weight: bold;">n&atilde;o</span> existir

qualquer legenda no texto original, deixe a figura para os

formatadores.

</p>

<p>A maioria das p&aacute;ginas sem texto, que contenham uma imagem,

estar&atilde;o representadas com&nbsp;<span

 style="font-family: monospace;"></span><tt><font color="red">[Blank

Page]</font></tt>. Mantenha-as assim.</p>

<!-- END RR -->

<table summary="Illustration" align="center" border="1" cellpadding="4"

 cellspacing="0" width="100%">

  <tbody>

    <tr>

      <th align="left" bgcolor="cornsilk">Imagem de Exemplo: </th>

    </tr>

    <tr align="left">

      <td valign="top" width="100%">

      <img src="http://www.pgdp.net/c/faq/illust.png" alt=""

 height="525" width="500">&nbsp;

      </td>

    </tr>

    <tr>

      <th align="left" bgcolor="cornsilk">Texto Revisto Correctamente:</th>

    </tr>

    <tr>

      <td valign="top" width="100%">

      <table summary="" align="left" border="0">

        <tbody>

          <tr>

            <td>

            <p><tt>Martha

told him that he had always been her ideal and<br>

that she worshipped him.<br>

            <br>

Frontispiece<br>

Her Weight in Gold </tt></p>

            </td>

          </tr>

        </tbody>

      </table>

      </td>

    </tr>

  </tbody>

</table>

<br>

<table summary="Illustration in Middle of Paragraph" align="center"

 border="1" cellpadding="4" cellspacing="0" width="100%">

  <tbody>

    <tr>

      <th align="left" bgcolor="cornsilk">Imagem de Exemplo: (Figura no

meio do par&aacute;grafo)</th>

    </tr>

    <tr align="left">

      <td valign="top" width="100%">

      <img src="http://www.pgdp.net/c/faq/illust2.png" alt=""

 height="514" width="500">&nbsp;

      </td>

    </tr>

    <tr>

      <th align="left" bgcolor="cornsilk">Texto Revisto Correctamente:</th>

    </tr>

    <tr valign="top">

      <td>

      <table summary="" align="left" border="0">

        <tbody>

          <tr>

            <td>

            <p><tt>such

study are due to Italians. Several of these instruments<br>

have already been described in this journal, and on the present<br>

            </tt></p>

            <p><tt>FIG.

1.--APPARATUS FOR THE STUDY OF HORIZONTAL<br>

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

      </td>

    </tr>

  </tbody>

</table>

<h3><a name="footnotes">Notas de Rodap&eacute;</a></h3>

<p><b>As notas de rodap&eacute; s&atilde;o colocadas fora da linha</b>;

ou seja, o texto das notas de rodap&eacute; &eacute; movido para o

final da p&aacute;gina, referenciando de onde vem.

</p>

O n&uacute;mero, letra, ou outro car&aacute;cter que marca o

local da

nota de rodap&eacute;, deve estar entre par&ecirc;nteses rectos&nbsp;(<tt>[</tt>

e <tt>]</tt>). Deve remover qualquer espa&ccedil;o existente

antes do&nbsp;<tt>[</tt>&mdash;deixe-o colado &agrave; palavra a que

se refere<tt>[1],</tt> ou ao seu sinal de pontua&ccedil;&atilde;o,<tt>[2]</tt>

como apresentamos no texto e nos dois exemplos desta frase.

<p>Quando a nota de rodap&eacute; &eacute; referenciada atrav&eacute;s

de caracteres especiais&nbsp;(<tt>*</tt>, &dagger;, &Dagger;,

&sect;, etc.) substitua-os todos por&nbsp;[*], e coloque um * junto da

nota de rodap&eacute; em si.

</p>

Reveja o texto da nota de rodap&eacute; tal como aparece impressa,

mantendo as quebras de linha. Coloque-o no final da p&aacute;gina.

Certifique-se de que est&aacute; bem identificada.<br>

<br>

Coloque uma nota de rodap&eacute; por linha, pela ordem em que surgem

no texto. Se houver mais do que uma, separe as notas de rodap&eacute;

com uma linha em branco.<!-- END RR -->

<p>Veja a imagem e o texto do&nbsp;<a href="#page_hf">Cabe&ccedil;alho

e Rodap&eacute; da P&aacute;gina</a>&nbsp;para um exemplo de "Nota de

Rodap&eacute;".

</p>

<p>Se uma nota de rodap&eacute; &eacute; referenciada no texto e

n&atilde;o aparece nessa p&aacute;gina, mantenha o

n&uacute;mero/marca&ccedil;&atilde;o da nota e coloque-a entre

par&ecirc;nteses rectos<tt>[</tt> e <tt>]</tt>. Esta

situa&ccedil;&atilde;o &eacute; frequente em livros cient&iacute;ficos

e t&eacute;cnicos, onde as notas se agrupam no final de cada

cap&iacute;tulo. Ver "Notas" em baixo.

</p>

<table summary="Footnote Examples" align="center" border="1"

 cellpadding="4" cellspacing="0" width="100%">

  <tbody>

    <tr>

      <th align="left" bgcolor="cornsilk" valign="top">Texto Original:</th>

    </tr>

    <tr>

      <td valign="top">

      <table summary="" align="left" border="0">

        <tbody>

          <tr>

            <td> The principal

persons involved in this argument were Caesar<sup>1</sup>,

former military<br>

leader and Imperator, and the orator Cicero<sup>2</sup>.

Both were of the aristocratic<br>

(Patrician) class, and were quite wealthy.<br>

            <hr align="left" noshade="noshade" size="2" width="50%">

            <font size="-1"><sup>1</sup>

Gaius Julius Caesar.</font><br>

            <font size="-1"><sup>2</sup>

Marcus Tullius Cicero.</font>

            </td>

          </tr>

        </tbody>

      </table>

      </td>

    </tr>

    <tr>

      <th align="left" bgcolor="cornsilk" valign="top">Rexto Revisto

Correctamente:</th>

    </tr>

    <tr valign="top">

      <td>

      <table summary="" align="left" border="0">

        <tbody>

          <tr>

            <td> <tt>The

principal persons involved in this argument were Caesar[1], former

military</tt><br>

            <tt>leader and

Imperator, and the orator Cicero[2]. Both were of the aristocratic</tt><br>

            <tt>(Patrician) class,

and were quite wealthy.</tt><br>

            <br>

            <tt>1 Gaius Julius

Caesar.</tt><br>

            <br>

            <tt>2 Marcus Tullius

Cicero.</tt>

            </td>

          </tr>

        </tbody>

      </table>

      </td>

    </tr>

  </tbody>

</table>

<p>Em alguns livros, as notas de rodap&eacute; encontram-se separadas

do texto por uma linha horizontal. N&atilde;o a mantenha e deixe uma

linha em&nbsp;branco entre o texto e as notas de rodap&eacute;. (Ver

exemplo em cima.)

</p>

<p>As <b>Notas</b><span style="font-weight: bold;"></span> s&atilde;o

notas de rodap&eacute; que foram agrupadas

no final do cap&iacute;tulo ou do livro, em vez de se situarem no final

da p&aacute;gina. Estas s&atilde;o revistas da mesma forma. Onde

encontrar uma refer&ecirc;ncia para uma nota, mantenha o n&uacute;mero

ou letra. Se estiver a rever uma das p&aacute;ginas finais que

contenham notas, coloque uma linha em branco entre cada uma, para que

fiquem separadas de forma clara. </p>

As <span style="font-weight: bold;">notas de rodap&eacute; em</span><b>&nbsp;<a

 href="#poetry">P</a></b><a style="font-weight: bold;" href="#poetry">oesia</a>

devem ter o mesmo tratamento que as outras notas de rodap&eacute;.<br>

<p>As <span style="font-weight: bold;">notas de rodap&eacute; em</span><b>&nbsp;<a

 href="#tables">Tabelas</a></b>&nbsp;devem permanecer tal como

surgem no texto original.

</p>

<table summary="Footnotes" align="center" border="1" cellpadding="4"

 cellspacing="0" width="100%">

  <tbody>

    <tr>

      <th align="left" bgcolor="cornsilk">Texto Original da Nota de

Rodap&eacute; em Poesia:</th>

    </tr>

    <tr>

      <td valign="top">

      <table summary="" align="left" border="0">

        <tbody>

          <tr>

            <td> Mary had a little

lamb<sup>1</sup><br>

&nbsp;&nbsp;&nbsp;Whose fleece was white as snow<br>

And everywhere that Mary went<br>

&nbsp;&nbsp;&nbsp;The lamb was sure to go!<br>

            <hr align="left" noshade="noshade" size="2" width="50%">

            <font size="-2"><sup>1</sup>

This lamb was obviously of the Hampshire breed,<br>

well known for the pure whiteness of their wool.</font>

            </td>

          </tr>

        </tbody>

      </table>

      </td>

    </tr>

    <tr>

      <th align="left" bgcolor="cornsilk">Texto Revisto Correctamente:</th>

    </tr>

    <tr>

      <td valign="top">

      <table summary="" align="left" border="0">

        <tbody>

          <tr>

            <td> <tt>

Mary had a little lamb[1]<br>

Whose fleece was white as snow<br>

And everywhere that Mary went<br>

The lamb was sure to go!<br>

            <br>

1 This lamb was obviously of the Hampshire breed,<br>

well known for the pure whiteness of their wool.<br>

            </tt></td>

          </tr>

        </tbody>

      </table>

      </td>

    </tr>

  </tbody>

</table>

<h3><a name="poetry">Poesia/Epigramas</a></h3>

<p>Insira uma linha em branco no in&iacute;cio e outra no final da

poesia/epigrama, para que os formatadores possam identificar quando

come&ccedil;a e acaba.</p>

Deixe as linhas&nbsp;alinhadas &agrave; esquerda e mantenha o

espa&ccedil;amento entre as linhas.&nbsp;N&atilde;o tente centrar ou

introduzir avan&ccedil;os na poesia. Essa &eacute; a

fun&ccedil;&atilde;o dos formatadores. Insira uma linha em branco entre

estr&oacute;fes.

As <b>notas

de rodap&eacute;</b> de poesia

s&atilde;o revistas da

mesma forma do que as outras notas de rodap&eacute;. Para mais

detalhes, veja o t&oacute;pico sobre as&nbsp;<a href="#footnotes">notas

de rodap&eacute;</a>.

<p>A <b>numera&ccedil;&atilde;o

de linhas</b> de poesia deve ser

mantida. Separe-a do texto inserindo alguns espa&ccedil;os.&nbsp;Para

mais detalhes,

veja o

t&oacute;pico sobre a&nbsp;<a href="#line_no">Numera&ccedil;&atilde;o

de Linhas</a>.

</p>

<p>Veja os&nbsp;<a href="#comments">Coment&aacute;rios do Projecto</a>&nbsp;do

livro que estiver a rever.<br>

</p>

<!-- Need an example that shows overly long lines of poetry, rather than relative indentation -->

<table summary="Poetry Example" align="center" border="1"

 cellpadding="4" cellspacing="0" width="100%">

  <tbody>

    <tr>

      <th align="left" bgcolor="cornsilk">Imagem de Exemplo:</th>

    </tr>

    <tr align="left">

      <th valign="top" width="100%">

      <img src="http://www.pgdp.net/c/faq/poetry.png" alt=""

 height="508" width="500">&nbsp;

      </th>

    </tr>

    <tr>

      <th align="left" bgcolor="cornsilk">Texto Revisto Correctamente:</th>

    </tr>

    <tr>

      <td valign="top" width="100%">

      <table summary="" align="left" border="0">

        <tbody>

          <tr>

            <td><tt>

to the scenery of his own country:<br>

            </tt>

            <p><tt>Oh,

to be in England<br>

Now that April's there,<br>

And whoever wakes in England<br>

Sees, some morning, unaware,<br>

That the lowest boughs and the brushwood sheaf<br>

Round the elm-tree hole are in tiny leaf,<br>

While the chaffinch sings on the orchard bough<br>

In England--now!</tt>

            </p>

            <p><tt>And

after April, when May follows,<br>

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

            </p>

            <p><tt>So

it runs; but it is only a momentary memory;<br>

and he knew, when he had done it, and to his</tt>

            </p>

            </td>

          </tr>

        </tbody>

      </table>

      </td>

    </tr>

  </tbody>

</table>

<h3><a name="para_side">Notas (Sidenotes)</a></h3>

<p> Alguns livros t&ecirc;m pequenas descri&ccedil;&otilde;es do

par&aacute;grafo ao lado e ao longo do texto. Chamam-se notas

(sidenotes).&nbsp;Reveja as notas tal como foram impressas, mantendo as

quebras de

linha. Deixe uma linha em branco depois da

nota, de forma a se distinguir do texto circundante. No OCR, as notas

podem aparecer noutro lado, ou surgir intercalada com o restante texto.

Separe-as de forma a que o texto da nota fique todo junto, mas

n&atilde;o se preocupe com a sua posi&ccedil;&atilde;o na sala. Os

formatadores ir&atilde;o coloc&aacute;-las no s&iacute;tio correcto.<br>

<table summary="Sidenotes" align="center" border="1" cellpadding="4"

 cellspacing="0" width="100%">

  <col width="128*"> <tbody>

    <tr valign="top">

      <th align="left" bgcolor="cornsilk">Imagem de Exemplo:</th>

    </tr>

    <tr valign="top">

      <td align="left" width="100%"><img

 src="http://www.pgdp.net/c/faq/side.png" alt="" height="800"

 width="550">

      </td>

    </tr>

    <tr valign="top">

      <th align="left" bgcolor="cornsilk">Texto Revisto Correctamente:</th>

    </tr>

    <tr valign="top">

      <td width="100%">

      <table summary="" align="left" border="0">

        <tbody>

          <tr>

            <td>

            <p><tt>Burning<br>

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

      </td>

    </tr>

  </tbody>

</table>

</p>

<h3><a name="tables">Tabelas</a></h3>

<p>A fun&ccedil;&atilde;o do revisor &eacute; certificar-se de que a

informa&ccedil;&atilde;o existente na tabela &eacute; revista

correctamente. Os detalhes da formata&ccedil;&atilde;o ser&atilde;o

tratados posteriormente. Coloque espa&ccedil;os entre os diferentes

campos de cada linha da tabela, os suficientes para se distinguir onde

come&ccedil;am e acabam. Mantenha os espa&ccedil;os entre linhas.&nbsp;</p>

As <b>notas

de rodap&eacute;</b> de tabelas

devem ser colocadas no s&iacute;tio onde aparecem. Para mais detalhes,

veja as&nbsp;<a href="#footnotes">notas de rodap&eacute;</a>.<!-- END RR -->

<table summary="Table Example 1" align="center" border="1"

 cellpadding="4" cellspacing="0" width="100%">

  <tbody>

    <tr>

      <th align="left" bgcolor="cornsilk">Imagem de Exemplo:</th>

    </tr>

    <tr align="left">

      <td valign="top" width="100%">

      <img src="http://www.pgdp.net/c/faq/table1.png" alt=""

 height="142" width="500"></td>

    </tr>

    <tr>

      <th align="left" bgcolor="cornsilk">Texto Revisto Correctamente:</th>

    </tr>

    <tr>

      <td valign="top" width="100%">

      <table summary="" align="left" border="0">

        <tbody>

          <tr>

            <td>

            <pre>Deg. C. Millimeters of Mercury. Gasolene.<br>Pure Benzene.<br><br>-10&deg; 13.4 43.5<br> 0&deg; 26.6 81.0<br>+10&deg; 46.6 132.0<br>20&deg; 76.3 203.0<br>40&deg; 182.0 301.8<br></pre>

            </td>

          </tr>

        </tbody>

      </table>

      </td>

    </tr>

  </tbody>

</table>

<br>

<table summary="Table Example 2" align="center" border="1"

 cellpadding="4" cellspacing="0" width="100%">

  <tbody>

    <tr>

      <th align="left" bgcolor="cornsilk">Imagem de Exemplo:</th>

    </tr>

    <tr align="left">

      <td valign="top" width="100%">

      <img src="http://www.pgdp.net/c/faq/table2.png" alt=""

 height="304" width="500"></td>

    </tr>

    <tr>

      <th align="left" bgcolor="cornsilk">Texto Revisto Correctamente:</th>

    </tr>

    <tr>

      <td valign="top" width="100%">

      <table summary="" align="left" border="0">

        <tbody>

          <tr>

            <td>

            <pre>TABLE II.<br><br>Flat strips compared Copper Copper<br>with round wire 30 cm. Iron. Parallel wires 30 cm. in Iron.<br>in length. length.<br><br>Wire 1 mm. diameter 20 100 Wire 1 mm. diameter 20 100<br><br> STRIPS. SINGLE WIRE.<br>0.25 mm. thick, 2 mm.<br> wide 15 35 0.25 mm. diameter 16 48<br>Same, 5 mm. wide 13 20 Two similar wires 12 30<br> " 10 " " 11 15 Four " " 9 18<br> " 20 " " 10 14 Eight " " 8 10<br> " 40 " " 9 13 Sixteen " " 7 6<br>Same strip rolled up in Same 16 wires bound<br> the form of a wire 17 15 close together 18 12<br></pre>

            </td>

          </tr>

        </tbody>

      </table>

      </td>

    </tr>

  </tbody>

</table>

<h3><a name="title_pg"><b><b>P&aacute;gina de Rosto (Frente e Verso)</b></b></a></h3>

<p>Reveja todo o texto, tal como foi impresso na p&aacute;gina, mesmo

que esteja tudo em mai&uacute;sculas, capitalizado, etc., incluindo os

anos de publica&ccedil;&atilde;o ou direito de autor.

</p>

<p>Nos livros mais antigos a primeira letra &eacute; geralmente

representada por uma imagem grande e ornamentada--reveja como se fosse

apenas a letra.

</p>

<!-- END RR -->

<table summary="Title Page Example" align="center" border="1"

 cellpadding="4" cellspacing="0" width="100%">

  <tbody>

    <tr>

      <th align="left" bgcolor="cornsilk">Imagem de Exemplo: </th>

    </tr>

    <tr align="left">

      <td valign="top" width="100%"><img

 src="http://www.pgdp.net/c/faq/title.png" alt="title page image"

 height="520" width="500">

      </td>

    </tr>

    <tr>

      <th align="left" bgcolor="cornsilk">Texto Revisto Correctamente:</th>

    </tr>

    <tr>

      <td valign="top" width="100%">

      <table summary="" align="left" border="0">

        <tbody>

          <tr>

            <td>

            <p><tt>GREEN

FANCY</tt> </p>

            <p><tt>BY</tt></p>

            <p><tt>GEORGE

BARR McCUTCHEON</tt></p>

            <p><tt>AUTHOR

OF "GRAUSTARK," "THE HOLLOW OF HER HAND,"<br>

"THE PRINCE OF GRAUSTARK," ETC.</tt></p>

            <p><tt>WITH

FRONTISPIECE BY<br>

C. ALLAN GILBERT</tt></p>

            <p><tt>NEW

YORK<br>

DODD, MEAD AND COMPANY.</tt></p>

            <p><tt>1917</tt></p>

            </td>

          </tr>

        </tbody>

      </table>

      </td>

    </tr>

  </tbody>

</table>

<h3><a name="toc">Tabela de Conte&uacute;dos</a></h3>

<p>Reveja a Tabela de Conte&uacute;dos tal como est&aacute; impressa no

livro,&nbsp;mesmo que esteja tudo em mai&uacute;sculas, capitalizado,

etc.. entre os marcadores <tt>/*</tt>

e <tt>*/</tt>.&nbsp;As refer&ecirc;ncias a n&uacute;meros de

p&aacute;ginas

devem ser mantidos e colocados a pelo menos seis espa&ccedil;os do fim

da linha.

</p>

<p>Remova os pontos ou asteriscos usados para alinhar os n&uacute;meros

de p&aacute;gina.

</p>

<!-- END RR -->

<table summary="TOC" align="center" border="1" cellpadding="4"

 cellspacing="0" width="100%">

  <tbody>

    <tr>

      <th align="left" bgcolor="cornsilk">Imagem de Exemplo: </th>

    </tr>

    <tr align="left">

      <td valign="top" width="100%">

      <p><img src="http://www.pgdp.net/c/faq/tablec.png" alt=""

 height="650" width="500"></p>

      <p></p>

      </td>

    </tr>

    <tr>

      <th align="left" bgcolor="cornsilk">Texto Revisto Correctamente:</th>

    </tr>

    <tr>

      <td valign="top" width="100%">

      <table summary="" align="left" border="0">

        <tbody>

          <tr>

            <td>

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

II. THE FIRST WAYFARER LAYS HIS PACK ASIDE AND<br>

FALLS IN WITH FRIENDS&nbsp;&nbsp;....&nbsp;...&nbsp;15<br>

            <br>

III. MR. RUSHCROFT DISSOLVES, MR. JONES INTERVENES,<br>

AND TWO MEN RIDE

AWAY&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;35<br>

            <br>

IV. AN EXTRAORDINARY CHAMBERMAID, A MIDNIGHT<br>

TRAGEDY, AND A MAN WHO SAID "THANK YOU"&nbsp;&nbsp;&nbsp;50<br>

            <br>

V. THE FARM-BOY TELLS A GHASTLY STORY, AND AN<br>

IRISHMAN ENTERS&nbsp;&nbsp;..&nbsp;&nbsp;..&nbsp;67<br>

            <br>

VI. CHARITY BEGINS FAR FROM HOME, AND A STROLL IN<br>

THE WILDWOOD

FOLLOWS&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;85<br>

            <br>

VII. SPUN-GOLD HAIR, BLUE EYES, AND VARIOUS

ENCOUNTERS&nbsp;&nbsp;...&nbsp;&nbsp;&nbsp;103<br>

            <br>

VIII. A NOTE, SOME FANCIES, AND AN EXPEDITION IN<br>

QUEST OF FACTS&nbsp;&nbsp;..&nbsp;,,&nbsp;120<br>

            <br>

IX. THE FIRST WAYFARER, THE SECOND WAYFARER, AND<br>

THE SPIRIT OF CHIVALRY

ASCENDANT&nbsp;&nbsp;&nbsp;,&nbsp;&nbsp;134<br>

            <br>

X. THE PRISONER OF GREEN FANCY, AND THE LAMENT OF<br>

PETER THE CHAUFFEUR&nbsp;...&nbsp;&nbsp;&nbsp;....148<br>

            <br>

XI. MR. SPROUSE ABANDONS LITERATURE AT AN EARLY<br>

HOUR IN THE

MORNING&nbsp;..&nbsp;&nbsp;...&nbsp;&nbsp;,&nbsp;167<br>

            <br>

XII. THE FIRST WAYFARER ACCEPTS AN INVITATION, AND<br>

MR. DILLINGFORD BELABORS A

PROXY&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;183<br>

            <br>

XIII. THE SECOND WAYFARER RECEIVES TWO VISITORS AT<br>

MIDNIGHT &nbsp;,,,..&nbsp;&nbsp;....&nbsp;199<br>

            <br>

XIV. A FLIGHT, A STONE-CUTTER'S SHED, AND A VOICE<br>

OUTSIDE&nbsp;&nbsp;&nbsp;,,,..&nbsp;&nbsp;....,&nbsp;221<br>

            </tt></p>

            </td>

          </tr>

        </tbody>

      </table>

      </td>

    </tr>

  </tbody>

</table>

<h3><a name="bk_index">&Iacute;ndices</a></h3>

<p>Por favor, mantenha os n&uacute;meros de p&aacute;ginas nas

p&aacute;ginas de &iacute;ndice.&nbsp;N&atilde;o alinhe os

n&uacute;meros como

surgem na imagem, basta colocar uma v&iacute;rgula ou dois pontos e

depois a numera&ccedil;&atilde;o das p&aacute;ginas.

</p>

A formata&ccedil;&atilde;o espec&iacute;fica dos &iacute;ndice &eacute;

feita mais tarde. A fun&ccedil;&atilde;o do revisor &eacute;

certificar-se de que o texto e os n&uacute;meros est&atilde;o correctos.<!-- END RR -->

<h3><a name="play_n">Pe&ccedil;as de Teatro: Nome de

Actores/Marca&ccedil;&otilde;es C&eacute;nicas</a></h3>

<p>Para todas as pe&ccedil;as de teatro:</p>

<ul compact="compact">

  <li>Num di&aacute;logo, cada vez que mudar a personagem ou orador

coloque uma linha em branco a separ&aacute;-los (um por

par&aacute;grafo).</li>

  <li>As marca&ccedil;&otilde;es c&eacute;nicas s&atilde;o formatadas

tal como surgem no texto original.<br>

Se a marca&ccedil;&atilde;o c&eacute;nica surge isolada numa linha,

reveja-a desta forma; se estiver no final da linha de um

di&aacute;logo, deixe-a l&aacute;.<br>

Frequentemente come&ccedil;am com um par&ecirc;ntese aberto, n&atilde;o

sendo fechado posteriormente.<br>

Por favor mantenha-o assim: n&atilde;o feche os par&ecirc;nteses.</li>

  <li>Por vezes, especialmente em pe&ccedil;as com m&eacute;trica, uma

palavra pode ser quabrada devido a uma limita&ccedil;&atilde;o de

espa&ccedil;o, sendo colocada imediatamente acima ou abaixo, precedida

de um par&ecirc;ntese aberto,&nbsp;em vez de ter uma linha

pr&oacute;pria. Por favor, junte as duas partes da palavra na linha

correcta.<br>

Veja o <a href="#play4">exemplo</a>.</li>

</ul>

<p>Por favor, verifique se o Gestor de Projecto n&atilde;o especificou

uma formata&ccedil;&atilde;o diferente nos&nbsp;<a href="#comments">Comentarios

do Projecto</a>.

</p>

<!-- END RR -->

<table summary="Play Example 1" align="center" border="1"

 cellpadding="4" cellspacing="0" width="100%">

  <tbody>

    <tr>

      <th align="left" bgcolor="cornsilk">Imagem de Exemplo:</th>

    </tr>

    <tr align="left">

      <td valign="top" width="100%"><img

 src="http://www.pgdp.net/c/faq/play1.png" alt="title page image"

 height="430" width="500">

      </td>

    </tr>

    <tr>

      <th align="left" bgcolor="cornsilk">Texto Revisto Correctamente:</th>

    </tr>

    <tr>

      <td valign="top" width="100%">

      <table summary="" align="left" border="0">

        <tbody>

          <tr>

            <td>

            <p><tt>Has

not his name for nought, he will be trode upon:<br>

What says my Printer now?

            </tt></p>

            <p><tt>Clow.

Here's your last Proof, Sir.<br>

You shall have perfect Books now in a twinkling.

            </tt></p>

            <p><tt>Lap.

These marks are ugly.

            </tt></p>

            <p><tt>Clow.

He says, Sir, they're proper:<br>

Blows should have marks, or else they are nothing worth.

            </tt></p>

            <p><tt>Lap.

But why a Peel-crow here?

            </tt></p>

            <p><tt>Clow.

I told 'em so Sir:<br>

A scare-crow had been better.

            </tt></p>

            <p><tt>Lap.

How slave? look you, Sir,<br>

Did not I say, this Whirrit, and this Bob,<br>

Should be both Pica Roman.

            </tt></p>

            <p><tt>Clow.

So said I, Sir, both Picked Romans,<br>

And he has made 'em Welch Bills,<br>

Indeed I know not what to make on 'em.

            </tt></p>

            <p><tt>Lap.

Hay-day; a Souse, Italica?

            </tt></p>

            <p><tt>Clow.

Yes, that may hold, Sir,<br>

Souse is a bona roba, so is Flops too.</tt></p>

            </td>

          </tr>

        </tbody>

      </table>

      </td>

    </tr>

  </tbody>

</table>

<br>

<a name="play4"><!-- Example --></a>

<table summary="Play Example 4" align="center" border="1"

 cellpadding="4" cellspacing="0" width="100%">

  <tbody>

    <tr>

      <th align="left" bgcolor="cornsilk">Imagem de Exemplo:</th>

    </tr>

    <tr align="left">

      <td valign="top" width="100%"><img

 src="http://www.pgdp.net/c/faq/play4.png" alt="Plays image" height="98"

 width="502">

      </td>

    </tr>

    <tr>

      <th align="left" bgcolor="cornsilk">Texto Revisto Correctamente:</th>

    </tr>

    <tr>

      <td valign="top" width="100%">

      <table summary="" align="left" border="0">

        <tbody>

          <tr>

            <td>

            <p><tt>Am.

Sure you are fasting;<br>

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

      </td>

    </tr>

  </tbody>

</table>

<h3><a name="anything">Qualquer outra situa&ccedil;&atilde;o que

necessite de um tratamento especial ou que suscite d&uacute;vidas</a></h3>

<p>Se ao rever, encontrar alguma situa&ccedil;&atilde;o que n&atilde;o

esteja descrita neste documento, e que ache que merece um tratamento

especial ou que n&atilde;o saiba ao certo como a rever, coloque a sua

quest&atilde;o, anotando o n&uacute;mero da imagem .png

(p&aacute;gina),

no t&oacute;pico de Discuss&atilde;o do Projecto (uma

liga&ccedil;&atilde;o para o f&oacute;rum espec&iacute;fico do

projecto, que pode ser encontrada&nbsp;na p&aacute;gina dos&nbsp;<a

 href="#comments">Coment&aacute;rios do Projecto</a>),

e coloque uma nota no texto revisto a explicar&nbsp;o problema. Esta

nota explicar&aacute; o problema ou quest&atilde;o ao pr&oacute;ximo

revisor ou ao p&oacute;s-processador.

</p>

<p>Coloque a sua nota a seguir a um&nbsp;<span id="lblDlpoDefinicao"><font

 class="texto"><font class="verbete">par&ecirc;ntese recto e dois

asteriscos</font></font></span>&nbsp;<tt>[**</tt>

e termine-a fechando o par&ecirc;ntese recto<tt>]</tt>.&nbsp;Esta

ac&ccedil;&atilde;o far&aacute; a nota sobressair do texto do autor, e

alertar&aacute; o revisor seguinte para uma compara&ccedil;&atilde;o

mais cuidadosa desta parte do texto e da imagem correspondente,

de forma a resolver o problema.

</p>

<p>Se estiver a rever numa ronda mais avan&ccedil;ada, encontrar

uma nota de um revisor da ronda anterior, e se conseguir resolver a

quest&atilde;o, perca um pouco do seu tempo a dar-lhe algum feedback.

Para isso clique no nome do revisor que encontra no ecr&atilde; de

revis&atilde;o, e envie uma mensagem a explicar a forma de revolver

quest&otilde;es semelhantes no futuro.</p>

<h3><a name="prev_notes">Notas de Revisores Precedentes</a></h3>

<p>Quaisquer notas ou coment&aacute;rios de um revisor precedente <span

 style="font-weight: bold;">n&atilde;o devem</span> ser removidos. Pode

concordar ou discordar dessa nota/coment&aacute;rio, mas mesmo que

saiba a resposta, nunca o remova. Se souber a fonte que esclarece a

quest&atilde;o, por favor cite-a na nota, para que o

p&oacute;s-processador possa referi-la.</p>

<p>Se formatar numa ronda seguinte e se cruzar com uma nota de um

volunt&aacute;rio precedente e se souber responder, por favor, perca um

pouco do seu tempo a responder-lhe, clicando no seu nick vis&iacute;vel

no ecr&atilde; de revis&atilde;o e enviando-lhe uma mensagem explicando

como pode resolver a situa&ccedil;&atilde;o futuramente. Por favor,

como j&aacute; foi referido anteriormente, n&atilde;o remova a nota.</p>

<!-- END RR -->

<table summary="Common Problems" border="0" cellspacing="0" width="100%">

  <tbody>

    <tr>

      <td bgcolor="silver">&nbsp;</td>

    </tr>

  </tbody>

</table>

<h2>Problemas Comuns</h2>

<h3><a name="OCR_1lI">Problemas de OCR: 1-l-I</a></h3>

<p>Geralmente, o OCR tem uma certa dificuldade em distinguir o

d&iacute;gito&nbsp;'1' (um), da letra min&uacute;scula 'l'

(&eacute;le), e/ou da letra mai&uacute;scula 'I'.&nbsp;Isto acontece

principalmente em livros cujas p&aacute;ginas est&atilde;o em

m&aacute;s condi&ccedil;&otilde;es.

</p>

<p>D&ecirc; especial aten&ccedil;&atilde;o a estes erros. Veja o

contexto da frase para determinar qual o car&aacute;cter correcto, mas

aten&ccedil;&atilde;o&mdash;muitas vezes a sua mente ter&aacute;

tend&ecirc;ncia para 'corrigir' automaticamente &agrave; medida que vai

lendo.

</p>

Detectar este tipo de erros, torna-se mais f&aacute;cil se utilizar

uma fonte mono-espa&ccedil;ada, como por exemplo&nbsp;<a

 href="font_sample.php">DPCustomMono</a>

ou Courier.

<h3><a name="OCR_0O">Problemas de OCR: 0-O</a></h3>

<p>Geralmente, o OCR tem uma certa dificuldade em distinguir o

d&iacute;gito '0'

(zero) da letra miai&uacute;scula 'O'. Isto acontece principalmente em

livros cujas p&aacute;ginas est&atilde;o em m&aacute;s

condi&ccedil;&otilde;es.

</p>

<p>D&ecirc; especial aten&ccedil;&atilde;o a estes erros. Na maioria

das vezes, o contexto da frase &eacute; suficiente para perceber qual o

car&aacute;cter correcto, mas cuidado&mdash;muitas vezes a sua mente

ter&aacute; tend&ecirc;ncia para 'corrigir' automaticamente &agrave;

medida que vai lendo.

</p>

Detectar este tipo de erros, torna-se mais f&aacute;cil se utilizar

uma fonte mono-espa&ccedil;ada, como por exemplo&nbsp;<a

 href="font_sample.php">DPCustomMono</a>

ou Courier.

<h3><a name="OCR_scanno">Problemas de OCR: Erros de Leitura (Scannos)</a></h3>

<p>Outro problema comum do OCR &eacute; o reconhecimento errado de

caracteres. Chamamos a estes erros&nbsp;"scannos" ou "erros de leitura"

(como "typos" ou "erros tipogr&aacute;ficos"). Este problema pode

resultar numa palavra que:</p>

<ul compact="compact">

  <li>parece correcta &agrave; primeira vista, mas surge mal escrita.<br>

Podem ser detectadas utilizando o corrector ortogr&aacute;fico do

ecr&atilde; de revis&atilde;o.</li>

  <li>existe, mas que tem um significado diferente e que n&atilde;o

corresponde &agrave; da imagem<br>

Estas s&atilde;o bastante subtis, s&oacute; podendo ser detectadas por

quem ler o texto.</li>

</ul>

O exemplo mais frequente da segunda hip&oacute;tese &eacute; a

palavra "and"

que &eacute; reconhecida muitas vezes como "arid." Outros exemplos:

"eve" em vez de "eye", "Torn"&nbsp;em vez de "Tom",

"train"&nbsp;em vez de "tram". Este tipo de erros &eacute; mais

dif&iacute;cil de detectar, tendo direito a um nome especial: "Stealth

Scannos" (Furtivos). Coleccionamos exemplos&nbsp;<a

 href="%3C?%20echo%20$Stealth_Scannos_URL;%20?%3E">neste t&oacute;pico</a>.

<p>Detectar este tipo de erros, torna-se mais f&aacute;cil se utilizar

uma fonte mono-espa&ccedil;ada, como por exemplo&nbsp;<a

 href="font_sample.php">DPCustomMono</a>

ou Courier.<br>

</p>

<h3><a name="hand_notes">Notas Manuscritas no Livro</a></h3>

<p>N&atilde;o inclua notas manuscritas do livro&nbsp;(excepto se o

texto manuscrito estiver a sobrepor caracteres que se tornaram menos

evidentes, de forma a torn&aacute;-los mais vis&iacute;veis).

N&atilde;o inclua

notas manuscritas&nbsp;de leitores (por vezes surgem nas margens),

etc.</p>

<h3><a name="bad_image">Imagens Danificadas</a></h3>

<p>Se uma imagem for danificada&nbsp;(n&atilde;o aparecer no

ecr&atilde;, cortada, imposs&iacute;vel de ser lida),

por favor, escreva uma mensagem sobre esta imagem no&nbsp;<a

 href="#forums">f&oacute;rum</a>&nbsp;dos coment&aacute;rios do

projecto.&nbsp;N&atilde;o clique em "Devolver

P&aacute;gina &agrave; Ronda"; se o fizer, a p&aacute;gina

passar&aacute;

para outro volunt&aacute;rio. Em vez disso, clique em "Reportar Uma

P&aacute;gina

Danificada", para que esta p&aacute;gina fique de 'quarentena'.

</p>

<p>Tenha em aten&ccedil;&atilde;o de que alguma imagens s&atilde;o

muito grandes, sendo normal demorar um pouco, principalmente se tiver

v&aacute;rias janelas abertas ou se o computador que estiver a usar

j&aacute; n&atilde;o for recente. Antes de reportar esta imagem como

sendo danificada, tente clicar na linha "Imagem" no final da

p&aacute;gina, para que a imagem surja numa nova janela. Se surgir,

ent&atilde;o o problema dever&aacute; ser do seu&nbsp;browser ou

sistema.

</p>

Apesar de uma imagem ser relativamente boa, &eacute;

compreens&iacute;vel que faltem as duas primeiras linhas no OCR. Por

favor escreva a(s) linha(s) em falta. Se faltarem quase todas as

linhas, rescreva toda a p&aacute;gina (se quiser), ou clique em

"Devolver

P&aacute;gina &agrave; Ronda" e a p&aacute;gina ser&aacute; entregue a

outro volunt&aacute;rio. Se existirem v&aacute;rias p&aacute;ginas

nestas condi&ccedil;&otilde;es, por favor escreva algo sobre o assunto

no <a href="#forums">f&oacute;rum</a>&nbsp;dos coment&aacute;rios do

projecto, de forma a alertar o Gestor de

Projecto.

<h3><a name="bad_text">Imagem N&atilde;o Corresponde ao Texto</a></h3>

<p>Se a imagem n&atilde;o corresponder ao texto apresentado, por favor,

escreva uma mensagem sobre esta imagem no&nbsp;<a href="#forums">f&oacute;rum</a>&nbsp;dos

coment&aacute;rios do projecto.

N&atilde;o clique em "Devolver P&aacute;gina &agrave; Ronda"; se o

fizer, a p&aacute;gina passar&aacute; para outro volunt&aacute;rio. Em

vez disso, clique em "Reportar Uma P&aacute;gina Danificada", para que

esta p&aacute;gina fique de 'quarentena'.

</p>

<h3><a name="round1">Erros do Revisor Precedente</a></h3>

<p>Se o revisor precedente cometeu v&aacute;rios erros, ou ignorou

v&aacute;rias situa&ccedil;&otilde;es, por favor, perca um pouco de

tempo e d&ecirc;-lhe um&nbsp;Feedback (uma no&ccedil;&atilde;o do que

fez) clicando no seu nome no ecr&atilde; de revis&atilde;o, e enviando

uma mensagem privada a explicar como lidar com a

situa&ccedil;&atilde;o, para que n&atilde;o a repita posteriormente.

</p>

<p><em>Por favor, seja simp&aacute;tico!</em> Todos n&oacute;s somos

volunt&aacute;rios e damos todos o nosso melhor. O objectivo da sua

mensagem de feedback deve ser inform&aacute;-los da forma correcta para

rever, e n&atilde;o uma cr&iacute;tica. D&ecirc; um exemplo

espec&iacute;fico do trabalho do volunt&aacute;rio em quest&atilde;o,

mostrando o que fizeram e o que deveriam ter feito.

</p>

<p>Se o revisor precedente tiver realizado um trabalho excelente, pode

enviar-lhe uma mensagem a congratul&aacute;-lo&mdash;especialmente se

tiver revisto uma p&aacute;gina particularmente dif&iacute;cil.

</p>

<h3><a name="p_errors">Erros de Impress&atilde;o/Ortografia</a></h3>

<p>Corrija todas as palavras que tenham sido&nbsp;lidas de forma

incorrecta pelo OCR&nbsp;(scannos), mas n&atilde;o fa&ccedil;a

correc&ccedil;&otilde;es do que lhe pare&ccedil;a erros

ortogr&aacute;ficos ou de impress&atilde;o na imagem original. Muitos

dos textos antigos cont&ecirc;m palavras escritas de forma diferente da

que usamos actualmente, e n&oacute;s preservamos estes dizeres antigos,

incluindo caracteres acentuados.

</p>

<p>Se tiver d&uacute;vidas, coloque uma nota no tetxo <tt>[**typo

deveria ser texto?]</tt>

e esclare&ccedil;a-se no t&oacute;pico de discuss&atilde;o do Projecto.

Se alterar algo, inclua uma nota com uma descri&ccedil;&atilde;o do que

alterou:&nbsp;<tt>[*Transcriber's Note:

typo corrigido, alterado de "tetxo" para "texto"]</tt>. Inclua um&nbsp;<tt>*</tt>

, para chamar a aten&ccedil;&atilde;o do p&oacute;s-processador.

</p>

<h3><a name="f_errors">Erros Factuais no Texto</a></h3>

<p>Por norma, n&atilde;o corrija erros factuais existentes no livro.

Muitos dos livros que revemos, re&uacute;nem conte&uacute;dos que

talvez j&aacute; n&atilde;o sejam correctos actualmente. Deixe-os como

o autor as escreveu.

</p>

Uma poss&iacute;vel excep&ccedil;&atilde;o &eacute; permitida nos

livros t&eacute;cnicos e cient&iacute;ficos, onde uma f&oacute;rmula ou

equa&ccedil;&atilde;o conhecida pode estar incorrecta e (especialmente)

se aparecer de forma correcta noutras p&aacute;ginas do livro. Alerte o

Gestor de Projecto para este facto, atrav&eacute;s de uma mensagem

pelo&nbsp;<a href="#forums">F&oacute;rum</a>,&nbsp;ou adicionando&nbsp;<tt>[**note

sic explique-a-sua-quest&atilde;o]</tt>

no local onde verifica tal erro.

<h3><a name="uncertain">Termos Desconhecidos</a></h3>

&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; [...em constru&ccedil;&atilde;o...]<br>

<table summary="Links" bgcolor="silver" border="0" cellpadding="0"

 cellspacing="0" width="100%">

  <tbody>

    <tr>

      <td width="10">&nbsp;</td>

      <td align="center" width="100%"><font

 face="verdana, helvetica, sans-serif" size="1"> Voltar para:</font><font

 face="verdana, helvetica, sans-serif" size="1">&nbsp;</font><font

 face="verdana, helvetica, sans-serif" size="1"><a

 href="..">P&aacute;gina

Principal do Distributed Proofreaders</a></font><font

 face="verdana, helvetica, sans-serif" size="1">,&nbsp;</font><font

 face="verdana, helvetica, sans-serif" size="1"><a

 href="faq_central.php">P&aacute;gina

do Centro de FAQ's</a></font><font face="verdana, helvetica, sans-serif"

 size="1">,<br>

      </font><font face="verdana, helvetica, sans-serif" size="1"><a

 href="<?=$PG_home_url?>">P&aacute;gina

Principal do

Projecto Gutenberg</a></font><font face="verdana, helvetica, sans-serif"

 size="1">.</font> </td>

    </tr>

  </tbody>

</table>

</body>

</html>

