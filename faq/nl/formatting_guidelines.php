<?php

// Translated by PGDP Team Netherlands; file received from user Clog 17 Feb 2009

$relPath = '../../pinc/';
include_once($relPath.'base.inc');
include_once($relPath.'faq.inc');
include_once($relPath.'pg.inc');
include_once($relPath.'theme.inc');

maybe_redirect_to_external_faq("nl");

$theme_args["css_data"] = "p.backtotop {text-align:right; font-size:75%;margin-right:-5%;}";

output_header('Formatteer-Richtlijnen', NO_STATSBAR, $theme_args);

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

<h1 align="center"><a name="top">Formatteer-Richtlijnen</a></h1>

<h3 align="center">Versie 2.0, herzien 7 juni 2009 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
  <a href="../dochist.php"><font size="-1">(Herzieningsgeschiedenis)</font></a></h3>

<HR>
<h4>Dit document is een vertaling van de Engelse Formatting Guidelines.<BR>
    Bij elk hoofdstuk is een link opgenomen naar het corresponderende hoofdstuk in die Guidelines.</h4>
<HR>

<p>Formatteer-Richtlijnen <a href="../formatting_guidelines.php">in het Engels</a> /
      Formatting Guidelines <a href="../formatting_guidelines.php">in English</a><br>
    Formatteer-Richtlijnen <a href="../fr/formatting_guidelines.php">in het Frans</a> /
      Directives de Formatage <a href="../fr/formatting_guidelines.php">en fran&ccedil;ais</a><br>
    Formatteer-Richtlijnen <a href="../pt/formatting_guidelines.php">in het Portuguees</a> /
      Regras de Formata&ccedil;&atilde;o <a href="../pt/formatting_guidelines.php">em Portugu&ecirc;s</a><br>
    Formatteer-Richtlijnen <a href="../de/formatting_guidelines.php">in het Duits</a> /
      Formatierungsrichtlinien <a href="../de/formatting_guidelines.php">auf Deutsch</a><br>
    Formatteer-Richtlijnen <a href="../it/formatting_guidelines.php">in het Italiaans</a> /
      Regole di Formattazione <a href="../it/formatting_guidelines.php">in Italiano</a><br>
</p>

<p>Bekijk de <a href="../../quiz/start.php?show_only=FQ">Formatting Quiz</a>! (dit document bestaat alleen in een Engelse versie)
</p>

<table border="0" cellspacing="0" width="100%" summary="Table of Contents">
  <tbody>
  <tr>
    <td width="1" bgcolor="silver">&nbsp;</td>
    <td bgcolor="silver" align="center"><h2 style="margin-top:0; margin-bottom:0;">Inhoudsopgave</h2></td>
  </tr>
  <tr>
    <td width="1" bgcolor="silver">&nbsp;</td>
    <td bgcolor="white" align="left">
    <ul>
      <li><a href="#prime">De Hoofdregel</a></li>
      <li><a href="#summary">Samenvatting van de Richtlijnen</a></li>
      <li><a href="#about">Over Dit Document</a></li>
      <li><a href="#separate_pg">Elke Pagina is een Aparte Eenheid</a></li>
      <li><a href="#comments">Toelichting bij een Project</a></li>
      <li><a href="#forums">Forum/Project Discussie</a></li>
      <li><a href="#prev_pg">Het Herstellen van Vergissingen op Voorgaande Pagina's</a></li>
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
        <li style="margin-top:.25em;"><font size="+1">Formatteren op het Letter-Niveau:</font>
        <ul>
          <li><a href="#inline">Plaatsing van Inline Formattering Markering</a></li>
          <li><a href="#italics">Cursief Gedrukte Tekst</a></li>
          <li><a href="#bold">Vet Gedrukte Tekst</a></li>
          <li><a href="#underl">Onderstreepte Tekst</a></li>
          <li><a href="#spaced"><span style="letter-spacing: .2em;">Uitgespatieerde Tekst</span></a></li>
          <li><a href="#font_ch">Verandering van Lettertype</a></li>
          <li><a href="#small_caps">Woorden in <span style="font-variant: small-caps">Kleine Hoofdlettertjes (Klein Kapitaal)</span></a></li>
          <li><a href="#word_caps">Woorden in Hoofdletters</a></li>
          <li><a href="#font_sz">Verandering in Grootte van het Lettertype</a></li>
          <li><a href="#extra_sp">Extra Spaties of Tabs Tussen Woorden</a></li>
          <li><a href="#supers">Superscript</a></li>
          <li><a href="#subscr">Subscript</a></li>
          <li><a href="#page_ref">Verwijzingen naar Pagina's &quot;Zie blz. 123&quot;</a></li>
        </ul></li>
        <li style="margin-top:.25em;"><font size="+1">Formatteren op het Alinea-Niveau:</font>
        <ul>
          <li><a href="#chap_head">Hoofdstuktitels</a></li>
          <li><a href="#sect_head">Paragraaftitels</a></li>
          <li><a href="#maj_div">Andere Belangrijke Onderdelen in Teksten</a></li>
          <li><a href="#para_space">Ruimte Tussen Alinea's/Inspringingen</a></li>
          <li><a href="#extra_s">Gedachtesprongen (Extra Lege Regels/Decoratie Tussen Alinea's)</a></li>
          <li><a href="#illust">Illustraties</a></li>
          <li><a href="#footnotes">Voetnoten/Eindnotens</a></li>
          <li><a href="#para_side">Beschrijvingen naast een Alinea</a></li>
          <li><a href="#outofline">Plaatsing van Out-of-Line Formattering Markering</a></li>
          <li><a href="#block_qt">Citaten</a></li>
          <li><a href="#lists">Lijsten</a></li>
          <li><a href="#tables">Tabellen</a></li>
          <li><a href="#poetry">Po&euml;zie/Epigrammen</a></li>
          <li><a href="#line_no">Regelnummers</a></li>
          <li><a href="#letter">Brieven/Correspondentie</a></li>
          <li><a href="#r_align">Rechts-uitgelijnde Tekst</a></li>
        </ul></li>
        <li style="margin-top:.25em;"><font size="+1">Formatteren op het Pagina-Niveau:</font>
        <ul>
          <li><a href="#blank_pg">Lege Pagina</a></li>
          <li><a href="#title_pg">Titelpagina aan de Voor- of Achterkant</a></li>
          <li><a href="#toc">Inhoudsopgave</a></li>
          <li><a href="#bk_index">Indexen</a></li>
          <li><a href="#play_n">Toneelstukken: Namen van Spelers/Regieaanwijzingen</a></li>
        </ul></li>
        <li><a href="#anything">Alles wat op een speciale manier aangepakt moet worden, of waar je onzeker over bent</a></li>
        <li><a href="#prev_notes">Aantekeningen/Commentaar van Eerdere Vrijwilligers</a></li>
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
        <li style="margin-top:.25em;"><font size="+1">Veel voorkomende problemen:</font>
        <ul>
          <li><a href="#bad_image">Slecht Beeld/Origineel</a></li>
          <li><a href="#bad_text">Verkeerd Beeld/Origineel voor de Tekst</a></li>
          <li><a href="#round1">Eerder Gemaakte Proeflees- en Formatteervergissingen</a></li>
          <li><a href="#p_errors">Vergissingen van de Drukker/Spelfouten</a></li>
          <li><a href="#f_errors">Feitelijke Fouten in de Tekst</a></li>
        </ul></li>
        <li><a href="#index">Alfabetische Index bij de Richtlijnen</a></li>
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

<h3><a name="prime">De Hoofdregel</a>
 (<i><a href="#prime">The Primary Rule</a></i>)</h3>
<p><em>"Verander niet wat de schrijver heeft geschreven!"</em>
</p>
<p>Het elektronische boek zoals de lezer het uiteindelijk zal zien, mogelijk vele jaren later, moet de bedoeling van
   de schrijver precies weergeven. Als de schrijver woorden op een vreemde manier spelde, dan laten we die spelling staan.
   Als de schrijver buitensporig racistische of bevooroordeelde uitspraken deed, dan laten we die staan.
   Als de schrijver elk derde woord cursief of vet maakte, of elk derde woord van een voetnoot voorzag,
   dan markeren we elk derde woord als cursief, of vet, of voorzien het van een voetnoot. Wanneer iets in de tekst
   niet overeenkomt met het origineel, dan moet je de tekst zo veranderen dat het wel overeenkomt.
   (Zie <a href="#p_errors">Vergissingen van de Drukker</a> voor de manier waarop overduidelijke
   drukfouten behandeld moeten worden.)
</p>
<p>Kleine, typografisch ge&iuml;nspireerde ingrepen in de tekst, die de betekenis van wat de schrijver schreef, niet aantasten,
   veranderen we wel. Bijvoorbeeld: we verplaatsen waar nodig bijschriften bij illustraties zodat ze alleen
   voorkomen tussen alinea's (<a href="#illust">Illustraties</a>).
   Dit soort veranderingen helpen ons om een <em>consistent geformatteerde</em> versie van het boek te produceren.
   Lees de rest van deze Formatteer-Richtlijnen alsjeblieft zorgvuldig, met dit concept in je achterhoofd.
   Deze richtlijnen zijn alleen bedoeld voor het formatteren. Proeflezers maken de inhoud kloppend met de scan,
   terwijl je als formatteerder nu het uiterlijk kloppend maakt.
</p>
<p>Om de volgende formatteerder en de Post-Processor te ondersteunen, handhaven we de regelafbrekingen.
   Dit helpt hen om de regels in de tekst te vergelijken met de regels in het origineel.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Terug naar boven</a></p>


<h3><a name="summary">Samenvatting van de Richtlijnen</a>
 (<i><a href="#summary">Summary Guidelines</a></i>)</h3>
<p>De <a href="../formatting_summary.pdf">Formatting Summary</a> (dit document bestaat alleen in een Engelse versie)
   is een kort printer-vriendelijk (.pdf) document van 2 pagina's, dat de voornaamste punten van deze richtlijnen samenvat
   en voorbeelden geeft hoe te formatteren.
   Beginnende formatteerders wordt aangeraden dit document uit te printen en bij de hand te houden bij het formatteren.
</p>
<p>Misschien moet je een .pdf lezer downloaden en installeren. Je kunt er
   <a href="http://www.adobe.com/products/acrobat/readstep2.html">hier</a> gratis &eacute;&eacute;n van Adobe&reg; krijgen.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Terug naar boven</a></p>


<h3><a name="about">Over Dit Document</a>
 (<i><a href="#about">About This Document</a></i>)</h3>
<p>Dit document is geschreven om de formatteer-regels uit te leggen. We gebruiken deze regels om consistentie
   te waarborgen, aangezien het formatteren van &eacute;&eacute;n enkel boek verdeeld is over vele formatteerders, die
   allemaal aan verschillende pagina's werken. De regels helpen ons om allemaal <em>op dezelfde manier</em>
   te formatteren, wat het vervolgens voor de Post-Processor gemakkelijker maakt om al deze pagina's tot
   &eacute;&eacute;n e-boek samen te voegen.
</p>
<p><i>Dit document is niet bedoeld als een algemeen handboek voor het redigeren of zetten van boeken.</i>
</p>
<p>We hebben in dit document alles behandeld waar nieuwe gebruikers vragen over gesteld hebben.
   Er is een apart document met <a href="proofreading_guidelines.php">Richtlijnen voor het Proeflezen</a>.
   Wanneer je in een situatie komt waar je geen aanwijzing vindt in deze richtlijnen, dan wordt
   het waarschijnlijk behandeld in de proeflees-ronden and wordt het daarom hier niet vermeld.
   Wanneer je niet zeker bent, dan kun je het best navraag doen in de <a href="#forums">Project Discussie</a>.
</p>
<p>Als er iets ontbreekt, of als je denkt dat iets op een andere manier behandeld zou moeten worden,
   of als iets vaag is, laat het ons alsjeblieft weten.
<?php if ($site_url == "http://www.pgdp.net" || $site_url == "http://www.pgdp.org") { ?>
   Wanneer je in deze richtlijnen een onbekende term tegenkomt, dan is deze (of de engelse vertaling hiervan)
   te vinden in <a href="http://www.pgdp.net/wiki/DP_Jargon">wiki jargon guide</a> (Engels).
<?php } ?>
   Dit document is in ontwikkeling. Help ons alsjeblieft bij de ontwikkeling van de richtlijnen, door
   veranderingen die je zou willen voorstellen, te <i>posten</i> in
   <a href="<?php echo $Guideline_discussion_URL; ?>">deze discussie</a> in het Documentation Forum.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Terug naar boven</a></p>


<h3><a name="separate_pg">Elke Pagina is een Aparte Eenheid</a>
 (<i><a href="#separate_pg">Each Page is a Separate Unit</a></i>)</h3>
<p>Aangezien elk project verdeeld wordt onder vele formatteerders, waarbij elk aan verschillende
   pagina's werkt, is er geen garantie dat je de volgende pagina in het project te zien krijgt.
   Met dit in gedachten, overtuig je zelf dat op elke pagina de markering is geopend en gesloten. Dit maakt
   het voor de post-processor uiteindelijk makkelijker alle pagina's samen te voegen in &eacute;&eacute;n e-boek.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Terug naar boven</a></p>


<h3><a name="comments">Toelichting bij een Project</a>
 (<i><a href="#comments">Project Comments</a></i>)</h3>
<p>Wanneer je een project selecteert om te proeflezen, wordt de Project Pagina geladen.
   Op deze pagina vind je een gedeelte dat "Project Comments" (Toelichting bij het Project) heet.
   Hier staat informatie die specifiek bedoeld is voor dat betreffende project (boek).
   <b>Lees dit voor je begint met het formatteren van pagina's!</b>
   Als de Project Manager wil dat iets op een andere manier geformatteerd wordt dan in deze richtlijnen staat,
   dan staat dat hier. Instructies in de Project Comments <em>geven de doorslag, boven de regels in deze richtlijnen</em>,
   dus volg ze alsjeblieft op. (Dit is trouwens ook de plaats waar de Project Manager wel eens interessante
   informatie geeft over de schrijver of over het project.)
</p>
<p><em>Lees alsjeblieft ook de Project Discussie</em>: Hier legt de Project Manager soms project-specifieke
   richtlijnen uit. Ook wordt deze discussie regelmatig gebruikt door vrijwilligers om andere vrijwilligers te wijzen
   op vaak terugkerende problemen in het project, en hoe deze het beste kunnen worden aangepakt. (Zie hieronder.)
</p>
<p>Op de Project Pagina staat een link 'Images, Pages Proofread, &amp; Differences'.
   Daar kun je zien hoe andere vrijwilligers veranderingen hebben aangebracht.
   <a href="<?php echo $Using_project_details_URL; ?>">Deze Forumdiscussie</a>
   bespreekt verschillende manieren om deze informatie te gebruiken.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Terug naar boven</a></p>


<h3><a name="forums">Forum/Bediscussieer Dit Project</a>
 (<i><a href="#forums">Forum/Discuss This Project</a></i>)</h3>
<p>Op de Project Pagina, waar je met het formatteren van pagina's begint, is op de regel "Forum" een link
   met de naam: "Discuss this Project" (als de discussie al begonnen is), of: "Start a discussion on this Project"
   (als de discussie nog niet begonnen is.) Klik op deze link en je komt op een <i>discussie</i> in het projectenforum,
   speciaal voor dit project. Hier kun je vragen over dit boek stellen, hier kun je de Project Manager informeren
   over problemen met de tekst enz. Posten in deze Project Discussie is de aanbevolen manier om met de Project Manager
   en andere vrijwilligers die aan het boek werken, te communiceren.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Terug naar boven</a></p>


<h3><a name="prev_pg">Het Herstellen van Vergissingen op Voorgaande Pagina's</a>
 (<i><a href="#prev_pg">Fixing Errors on Previous Pages</a></i>)</h3>
<p>De <a href="#comments">Project Pagina</a> bevat links naar bladzijden van dit project waar je recent aan gewerkt hebt.
   (Als je nog geen pagina's van dit project geformatteerd hebt, zijn er ook geen links.)
</p>
<p>Pagina's onder &oacute;f "DONE" &oacute;f "IN PROGRESS" zijn beschikbaar om te verbeteren of om af te maken.
   Klik op de link van de pagina. Dus wanneer je ontdekt dat je een vergissing op een pagina gemaakt hebt of dat je
   iets onjuist gemarkeerd hebt, kun je hier op de link klikken en de pagina openen om de vergissing te herstellen.
</p>
<p>Je kunt ook de "Images, Pages Proofread, &amp; Differences" of "Just My Pages" link op de
   <a href="#comments">Project Pagina</a> gebruiken. Deze pagina's hebben een "Edit" link naast de pagina's
   waar je in de huidige ronde aan gewerkt hebt en die nog verbeterd kunnen worden.
</p>
<p>Voor gedetailleerder informatie, zie &oacute;f de <a href="../prooffacehelp.php?i_type=0">Standard
   Proofreading Interface Help</a> &oacute;f de <a href="../prooffacehelp.php?i_type=1">Enhanced Proofreading
   Interface Help</a> (deze documenten bestaan alleen in een Engelse versie), afhankelijk van welke interface je gebruikt.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Terug naar boven</a></p>


<table width="100%" border="0" cellspacing="0" cellpadding="6" summary="Character-level formatting">
  <tbody>
    <tr>
      <td bgcolor="silver"><h2 style="margin-bottom: 0; margin-top: 0;">Formatteren op het Letter-Niveau:</h2></td>
    </tr>
  </tbody>
</table>

<h3><a name="inline">Plaatsing van Inline Formattering Markering</a>
 (<i><a href="#inline">Placement of Inline Formatting Markup</a></i>)</h3>
<p>Inline formattering verwijst naar markering zoals <kbd>&lt;i&gt;</kbd>&nbsp;<kbd>&lt;/i&gt;</kbd>,
   <kbd>&lt;b&gt;</kbd>&nbsp;<kbd>&lt;/b&gt;</kbd>, <kbd>&lt;sc&gt;</kbd>&nbsp;<kbd>&lt;/sc&gt;</kbd>,
   <kbd>&lt;f&gt;</kbd>&nbsp;<kbd>&lt;/f&gt;</kbd>, of <kbd>&lt;g&gt;</kbd>&nbsp;<kbd>&lt;/g&gt;</kbd>.
   Plaats interpunctie <b>buiten</b> de opmaak tenzij de markering rond een hele zin of alinea staat,
   of de interpunctie zelf is deel van de frase, titel of een afkorting die je markeert
   Wanneer de formattering meerdere alinea's beslaat, dan markeer je elke alinea.
</p>
<p>De punten die een afgekort woord in de titel van een tijdschrift aangeven, zoals <i>Phil. Trans.</i>,
   horen bij de titel en worden daarom opgenomen binnen de markering: <kbd>&lt;i&gt;Phil. Trans.&lt;/i&gt;</kbd>.
</p>
<p>Veel lettertypes in oudere boeken hebben dezelfde vormgeving voor cijfers in zowel de normale tekst
   als in de cursieve of vette tekst. Formatteer bij een datum en vergelijkbare frasen de complete frase met
   &eacute;&eacute;n set van markering, in plaats van de woorden als cursief (of vet) te markeren en de cijfers niet.
</p>
<p>Wanneer er een serie/lijst van woorden of frasen is (zoals namen, titels, etc.),
   markeer dan elk item van de lijst apart.
</p>
<p>Zie de paragraaf over <a href="#tables">Tabellen</a> voor hoe opmaak in tabellen te behandelen.
</p>
<!-- END RR -->
<p><b>Examples</b>:
</p>
<table width="100%" align="center" border="1" cellpadding="4" cellspacing="0" summary="Inline markup examples">
  <tbody>
    <tr>
      <th valign="top" bgcolor="cornsilk">Origineel</th>
      <th valign="top" bgcolor="cornsilk">Correct Geformatteerde Tekst:</th>
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
<p class="backtotop"><a href="#top">Terug naar boven</a></p>


<h3><a name="italics">Cursief gedrukte tekst</a>
 (<i><a href="#italics">Italics</a></i>)</h3>
<p>Formatteer <i>cursief gedrukte</i> tekst met <kbd>&lt;i&gt;</kbd> aan het begin en
   <kbd>&lt;/i&gt;</kbd> aan het eind van het cursief.
   (Let op de "/" in de laatste markering.)
</p>
<p>Zie ook <a href="#inline">Plaatsing van Inline Formattering Markering</a>.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Terug naar boven</a></p>


<h3><a name="bold">Vet gedrukte tekst</a>
 (<i><a href="#bold">Bold Text</a></i>)</h3>
<p>Formatteer <b>vet gedrukte</b> tekst (tekst die is gedrukt in een dikkere versie van het lettertype),
   met <kbd>&lt;b&gt;</kbd> ervoor en <kbd>&lt;/b&gt;</kbd> erachter. (Let op de "/" in de laatste markering.)
</p>
<p>Zie ook <a href="#inline">Plaatsing van Inline Formattering Markering</a> en
   <a href="#chap_head">Hoofdstuktitels</a>.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Terug naar boven</a></p>


<h3><a name="underl">Onderstreepte Tekst</a>
 (<i><a href="#underl">Underlined Text</a></i>)</h3>
<p>Formatteer <u>onderstreepte tekst</u> op dezelfde manier als <a href="#italics">Cursieve Tekst</a>,
   met <kbd>&lt;i&gt;</kbd> en <kbd>&lt;/i&gt;</kbd>. (Let op de "/" in de laatste markering.)
   Onderstreping werd vaak gebruikt om nadruk aan te geven, als de zetter geen mogelijkheden had
   om de tekst te cursiveren, bijv. in getypte documenten.
</p>
<p>Zie ook <a href="#inline">Plaatsing van Inline Formattering Markering</a>.
</p>
<p>Sommige Project Managers kunnen in de <a href="#comments">Project Comments</a> aangeven dat de
   onderstreepte tekst met <kbd>&lt;u&gt;</kbd> en <kbd>&lt;/u&gt;</kbd> moet worden gemarkeerd.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Terug naar boven</a></p>


<h3><a name="spaced"><span style="letter-spacing: .2em;">Uitgespatieerde Tekst</span></a>
 (<i><a href="#spaced"><span style="letter-spacing: .2em;">Spaced Out Text</span> (gesperrt)</a></i>)</h3>
<p>Formatteer <span style="letter-spacing: .2em;">uitgespatieerde tekst</span> door er <kbd>&lt;g&gt;</kbd> voor en
   <kbd>&lt;/g&gt;</kbd> na te zetten. (Let op de "/" in de laatste markering.)
   Verwijder de extra spaties binnen de woorden.
   Uitspati&euml;ring was een zettechniek die vooral in Duitse en Nederlandse boeken gebruikt werd
   om een deel van de tekst te benadrukken.
</p>
<p>Zie ook <a href="#inline">Plaatsing van Inline Formattering Markering</a> en
   <a href="#chap_head">Hoofdstuktitels</a>.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Terug naar boven</a></p>


<h3><a name="font_ch">Verandering van Lettertype</a>
 (<i><a href="#font_ch">Font Changes</a></i>)</h3>
<p>Sommige Project Managers kunnen vragen om een verandering van lettertype binnen een alinea of een zin normale tekst
   te markeren door <kbd>&lt;f&gt;</kbd> voor de tekst in het andere lettertype te zetten en <kbd>&lt;/f&gt;</kbd> erna.
   (Let op de "/" in de laatste markering.)
   Deze markering kan gebruikt worden om speciale lettertypes of andere formattering aan te geven,
   die niet al hun eigen opmaak hebben (zoals cursief en vet gedrukte tekst).
</p>
<p>Mogelijk gebruik van deze markering:</p>
<ul compact>
  <li>antiqua (een versie van een roman lettertype) binnen fraktur</li>
  <li>blackletter ("gothisch" of "Old English" lettertype) binnen een alinea met een normaal lettertype</li>
  <li>een kleiner of groter lettertype alleen als het voorkomt <b>binnen</b> een alinea in
   een normaal lettertype (voor een hele alinea in een ander lettertype of een andere maat,
   zie het gedeelte over <a href="#block_qt">Citaten</a>)</li>
  <li>rechtop lettertype binnen een cursief gedrukte alinea</li>
</ul>
<p>Het specifieke gebruik van deze markering in een project wordt meestal aangegeven in de
   <a href="#comments">Project Comments</a>. Formatteerders kunnen het beste in de
   <a href="#forums">Project Discussie</a> posten als deze markering nodig lijkt en er nog niet om gevraagd is.
</p>
<p>Zie ook <a href="#inline">Plaatsing van Inline Formattering Markering</a>.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Terug naar boven</a></p>


<h3><a name="small_caps">Woorden in <span style="font-variant: small-caps">kleine hoofdlettertjes (Klein Kapitaal)</span></a>
 (<i><a href="#small_caps">Words in Small Capitals</a></i>)</h3>
<p><span style="font-variant:small-caps;">Gemengd Klein Kapitaal</span> wordt anders geformatteerd dan
   tekst <span style="font-variant:small-caps;">helemaal in klein kapitaal</span>:
</p>
<p>Formatteer woorden in <span style="font-variant: small-caps;">Gemengd Klein Kapitaal</span> als
   gemengd Hoofd- en Kleine Letters.
   Formatteer woorden die <span style="font-variant: small-caps;">helemaal in klein kapitaal</span>
   als HOOFDLETTERS.
   Voeg rond zowel het gemengd als helemaal klein kapitaal <kbd>&lt;sc&gt;</kbd> en <kbd>&lt;/sc&gt;</kbd> markering toe.<br>
</p>
<p>Titels (<a href="#chap_head">Hoofdstuktitels</a>, <a href="#sect_head">paragraaftitels</a>, bijschriften enz.)
   kunnen in <span style="font-variant: small-caps;">helemaal klein kapitaal</span> lijken te staan,
   maar dit is in het algemeen het resultaat van verandering in <a href="#font_sz">lettergrootte</a>
   en moet niet gemarkeerd worden als klein kapitaal. Het <a href="#chap_head">eerste woord van een hoofdstuk</a>
   wat in klein kapitaal staat, moet veranderd worden in gemengd hoofd- en kleine letters zonder de markering.
</p>
<p>Zie ook <a href="#inline">Plaatsing van Inline Formattering Markering</a>.
</p>
<table width="100%" align="center" border="1" cellpadding="4" cellspacing="0" summary="Small caps examples">
  <tbody>
    <tr>
      <th valign="top" bgcolor="cornsilk">Origineel:</th>
      <th valign="top" bgcolor="cornsilk">Correct geformatteerde tekst:</th>
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
<p class="backtotop"><a href="#top">Terug naar boven</a></p>


<h3><a name="word_caps">Woorden in Allemaal Hoofdletters</a>
 (<i><a href="#word_caps">Words in All Capitals</a></i>)</h3>
<p>Formatteer woorden die gedrukt zijn in allemaal hoofdletters als allemaal hoofdletters.
</p>
<p>De uitzondering hierop is <a href="#chap_head">eerste woord van een hoofdstuk</a>:
   veel oude boeken drukken dit eerste woord in allemaal hoofdletters; dit moet veranderd worden 
   in hoofd- en kleine letters, dus "OOIT heel lang geleden," wordt "<kbd>Ooit heel lang geleden,</kbd>".
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Terug naar boven</a></p>


<h3><a name="font_sz">Verandering in Grootte van het Lettertype</a>
 (<i><a href="#font_sz">Font Size Changes</a></i>)</h3>
<p>Normaliter markeren we verandering in grootte van lettertypes niet.
   De uitzonderingen zijn, als de grootte verandert om een <a href="#block_qt">citaat</a> aan te geven,
   of als de grootte van het lettertype binnen een alinea of binnen een zin verandert.
   (Zie <a href="#font_ch">Verandering van Lettertype</a>).
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Terug naar boven</a></p>


<h3><a name="extra_sp">Extra Spaties of Tabs Tussen Woorden</a>
 (<i><a href="#extra_sp">Extra spaces or Tabs Between Words</a></i>)</h3>
<p>Extra spaties tussen woorden komen nogal veel voor in de output van de OCR.
   Je hoeft ze niet te verwijderen&mdash;dit gebeurt automatisch tijden het post-processen.
   Maar, extra spaties rondom interpunctie, em-dashes, aanhalingstekens enz. moeten <b>wel</b>
   verwijderd worden als ze tussen het symbool en het woord staan. Zorg er daarnaast voor dat iedere
   extra spatie binnen de <kbd>/* */</kbd> markering, dat de spati&euml;ring behoudt, wordt verwijderd;
   deze spaties worden uiteindelijk niet automatisch verwijderd.
</p>
<p>Tenslotte, als je in de tekst een tab-teken vindt dan moet deze verwijderd worden.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Terug naar boven</a></p>


<!-- Remove this section after some transition, since it's now all handled in proofreading -->
<h3><a name="supers">Superscript</a>
 (<i><a href="#supers">Superscripts</a></i>)</h3>
<p>Oudere boeken gebruikten vaak samentrekkingen als afkortingen, en drukten deze dan als superscript.
   Formatteer deze dan door een dakje (<kbd>^</kbd>) in te voegen gevolgd door de 'verhoogde' tekst.
   Wanneer meerdere letters in superscript staan, voeg dan ook accolades <kbd>{</kbd> en <kbd>}</kbd>
   toe om de tekst in superscript. Bijvoorbeeld:
</p>
<table width="100%" align="center" border="1" cellpadding="4" cellspacing="0" summary="Superscripts example">
  <tbody>
    <tr><th align="left" bgcolor="cornsilk">Origineel:</th></tr>
    <tr>
      <td valign="top">Gen<sup>rl</sup> Washington defeated L<sup>d</sup> Cornwall's army.</td>
    </tr>
    <tr><th align="left" bgcolor="cornsilk">Correct geformatteerde tekst:</th></tr>
    <tr>
      <td valign="top"><kbd>Gen^{rl} Washington defeated L^d Cornwall's army.</kbd></td>
    </tr>
  </tbody>
</table>
<p>Wanneer het superscript een voetnoot markering voorstelt, zie dan de
   <a href="#footnotes">Voetnoten</a> paragraaf.
</p>
<p>De Project Manager kan in de <a href="#comments">Project Comments</a> aangeven
   dat tekst in superscript anders gemarkeerd moet worden.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Terug naar boven</a></p>


<!-- Remove this section after some transition, since it's now all handled in proofreading -->
<h3><a name="subscr">Subscript</a>
 (<i><a href="#subscr">Subscripts</a></i>)</h3>
<p>In wetenschappelijke werken wordt vaak subscript gebruikt, al komt het in andere boeken niet vaak voor.
   Formatteer tekst in subscript door eerst een laag streepje <kbd>_</kbd> neer te zetten en zet
   vervolgens accolades <kbd>{</kbd> en <kbd>}</kbd> om de tekst die in subscript staat. Bijvoorbeeld:
</p>
<table width="100%" align="center" border="1" cellpadding="4" cellspacing="0" summary="Subscripts example">
  <tbody>
    <tr><th align="left" bgcolor="cornsilk">Origineel:</th></tr>
    <tr>
      <td valign="top">H<sub>2</sub>O.</td>
    </tr>
    <tr><th align="left" bgcolor="cornsilk">Correct geformatteerde tekst:</th></tr>
    <tr>
      <td valign="top"><kbd>H_{2}O.</kbd></td>
    </tr>
  </tbody>
</table>
<!-- END RR -->
<p class="backtotop"><a href="#top">Terug naar boven</a></p>


<h3><a name="page_ref">Verwijzingen naar pagina's &quot;Zie blz. 123&quot;</a>
 (<i><a href="#page_ref">Page References &quot;See p. 123&quot;</a></i>)</h3>
<p>Formatteer verwijzingen naar paginanummers zoals ze in het origineel voorkomen: <kbd>(zie pg. 123)</kbd>.
</p>
<p>Kijk wel na in de <a href="#comments">Project Comments</a> of de Project Manager speciale
   vereisten heeft voor de verwijzingen naar pagina's.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Terug naar boven</a></p>


<table width="100%" border="0" cellspacing="0" cellpadding="6" summary="Paragraph-level formatting">
  <tbody>
    <tr>
      <td bgcolor="silver"><h2 style="margin-bottom: 0; margin-top: 0;">Formatteren op het Alinea-Niveau:</h2></td>
    </tr>
  </tbody>
</table>


<h3><a name="chap_head">Hoofdstuktitels</a>
 (<i><a href="#chap_head">Chapter Headings</a></i>)</h3>
<p>Formatteer hoofdstuktitels zoals ze in het origineel voorkomen.
   Een hoofdstuktitel begint vaak wat lager op de pagina dan de koptekst en
   er staat geen paginanummer op dezelfde regel. Hoofdstuktitels worden vaak helemaal in hoofdletters gedrukt;
   handhaaf deze dan als hoofdletters. Markeer elke <a href="#italics">cursief</a> of <b>gemengd</b>
   <a href="#small_caps">klein kapitaal</a> zoals ze in het origineel staan.
</p>
<p>Voeg 4 lege regels in v&oacute;&oacute;r "CHAPTER XXX", ook als het hoofdstuk op een nieuwe pagina begint.
   De lege regels zijn nodig, omdat een e-boek geen pagina's heeft. Vervolgens scheidt je elk onderdeel van de
   hoofdstuktitel met een lege regel, zoals een beschrijving van het hoofdstuk, een citaat, enz.
   Tot slot moeten twee lege regels geplaatst worden voor het begin van de tekst van het hoofdstuk.
</p>
<p>In oude boeken zijn vaak de eerste paar woorden van elk hoofdstuk gedrukt in allemaal hoofdletters
   of klein kapitaal; verander deze naar hoofd- en kleine letters (alleen de eerste letter een hoofdletter).
</p>
<p>Alhoewel hoofdstuktitels als vette of uitgespatieerde tekst lijken voor te komen, is dit meestal het
   gevolg van het lettertype of verandering van lettergrootte en <b>wordt dit niet gemarkeerd</b>.
   De extra witregels scheiden de titel, dus markeer de verandering van lettergrootte hier ook niet.
   Zie het eerste voorbeeld hieronder.
</p>
<!-- END RR -->

<table width="100%" align="center" border="1" cellpadding="4" cellspacing="0" summary="Chapter heading example">
 <tbody>
    <tr><th align="left" bgcolor="cornsilk">Origineel:</th></tr>
    <tr align="left">
      <td width="100%" valign="top"> <img src="../chap1.png" alt="" width="500" height="725"><br>
      </td>
    </tr>
    <tr><th align="left" bgcolor="cornsilk">Correct geformatteerde tekst:</th></tr>
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
<table width="100%" align="center" border="1" cellpadding="4" cellspacing="0" summary="Chapter heading example">
  <tbody>
    <tr><th align="left" bgcolor="cornsilk">Origineel:</th></tr>
    <tr align="left">
      <td width="100%" valign="top"><img src="../foot.png" alt="" width="500" height="860"><br>
      </td>
    </tr>
    <tr><th align="left" bgcolor="cornsilk">Correct geformatteerde tekst:</th></tr>
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
<p class="backtotop"><a href="#top">Terug naar boven</a></p>


<h3><a name="sect_head">Paragraaftitels</a>
 (<i><a href="#sect_head">Section Headings</a></i>)</h3>
<p>Bij sommige teksten zijn de hoofdstukken onderverdeeld in paragrafen. Formatteer deze titels zoals ze in het origineel staan.
   Voeg twee lege regels in v&oacute;&oacute;r de titel en &eacute;&eacute;n lege regel na de titel, tenzij de Project Manager iets anders vraagt.
   Als je er niet zeker van bent of een titel van een hoofdstuk of van een paragraaf is, vraag het dan in de <a href="#forums">Project Discussie</a>,
   onder vermelding van het nummer van de pagina.
</p>
<p>Markeer elk <a href="#italics">cursief</a> of <b>gemengd</b> <a href="#small_caps">klein kapitaal</a> dat in het origineel voorkomt.
<p>Alhoewel paragraaftitels als vette of uitgespatieerde tekst lijken voor te komen, is dit meestal het
   gevolg van het lettertype of verandering van lettergrootte en <b>wordt dit niet gemarkeerd</b>.
   De extra witregels scheiden de titel, dus markeer de verandering van lettergrootte hier ook niet.
</p>
<!-- END RR -->
<table width="100%" align="center" border="1" cellpadding="4" cellspacing="0" summary="Section Heading example">
  <tbody>
    <tr>
      <th align="left" bgcolor="cornsilk">Origineel:</th>
    </tr>
    <tr align="left">
      <td width="100%" valign="top"> <img src="../section.png" alt="" width="500" height="283"> <br>
      </td>
    </tr>
    <tr>
      <th align="left" bgcolor="cornsilk">Correct geformatteerde tekst:</th>
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
<p class="backtotop"><a href="#top">Terug naar boven</a></p>


<h3><a name="maj_div">Andere Belangrijke Onderdelen in Teksten</a>
 (<i><a href="#maj_div">Other Major Divisions in Texts</a></i>)</h3>
<p>Belangrijke onderdelen zoals Inleiding, Voorwoord, Inhoudsopgave, Introductie, Proloog, Epiloog, Appendix,
   Verwijzingen, Conclusie, Woordenlijst, Samenvatting, Dankbetuigingen, Bibliografie, enz., moeten op dezelfde
   manier geformatteerd worden als <a href="#chap_head">Hoofdstuktitels</a>, dus 4 lege regels voor de
   titel en 2 lege regels voor de rest van de tekst.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Terug naar boven</a></p>


<!-- Remove this section after some transition, since it's now all handled in proofreading -->
<h3><a name="para_space">Ruimte Tussen Alinea's/Inspringingen</a>
 (<i><a href="#para_space">Paragraph Spacing/Indenting</a></i>)</h3>
<p>Zet een lege regel voor het begin van een alinea, zelfs als deze bovenaan een bladzijde begint.
   Aan het begin van een alinea hoef je niet in te springen, maar als een alinea al ingesprongen is,
   hoef je die spaties niet te verwijderen&mdash;dat gebeurt automatisch tijdens het post-processen.
</p>
<p>Zie het origineel/tekst bij de <a href="#chap_head">Hoofdstuktitels</a> voor een voorbeeld.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Terug naar boven</a></p>


<h3><a name="extra_s">Gedachtesprongen (Extra Lege Regels/Decoratie Tussen Alinea's)</a>
 (<i><a href="#extra_s">Thought Breaks (Extra Spacing/Decoration Between Paragraphs)</a></i>)</h3>
<p>In het origineel beginnen de meeste alinea's op de regel meteen na de vorige alinea. Soms worden alinea's
   van elkaar gescheiden om een "gedachtesprong" ("thought break") aan te geven.
   Een gedachtesprong kan de vorm aannemen van een rij sterretjes, streepjes, of een ander teken, of van een lijn,
   soms rijkelijk versierd, van een decoratie, of zelfs van &eacute;&eacute;n of twee extra lege regels.
</p>
<p>Een gedachtesprong kan een verandering van omgeving of van onderwerp betekenen, een verandering in
   tijdstip, of een stuk spanning. Dit is zo bedoeld door de schrijver, dus we handhaven het,
   door een lege regel in te voegen, vervolgens <kbd>&lt;tb&gt;</kbd> en dan nog een lege regel.
</p>
<p>Soms gebruikten de zetters gedecoreerde lijnen om het eind van <a href="#chap_head">hoofdstukken</a>
   of <a href="#sect_head">paragrafen</a> aan te geven. Dit zijn geen gedachtesprongen en moeten
   <b>niet</b> met <kbd>&lt;tb&gt;</kbd> gemarkeerd worden.
</p>
<p>Lees de <a href="#comments">Project Comments</a> zorgvuldig door, aangezien de Project Manager kan vragen
   om aanvullende informatie in de gedachtesprong-markering te handhaven, zoals bijvoorbeeld
   <kbd>&lt;tb stars&gt;</kbd> voor een regel met sterretjes.
</p>
<!-- END RR -->
<table width="100%" align="center" border="1" cellpadding="4" cellspacing="0" summary="Thought Break example">
  <tbody>
    <tr><th align="left" bgcolor="cornsilk">Origineel:</th></tr>
    <tr align="left">
      <td width="100%" valign="top"> <img src="../tbreak.png" alt="" width="500" height="249"> <br>
      </td>
    </tr>
    <tr><th align="left" bgcolor="cornsilk">Correct geformatteerde tekst:</th></tr>
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
<p class="backtotop"><a href="#top">Terug naar boven</a></p>


<h3><a name="illust">Illustraties</a>
 (<i><a href="#illust">Illustrations</a></i>)</h3>
<p>Tekst bij een illustratie moet omgeven worden door een illustratiemarkering:
   <kbd>[Illustration:&nbsp;</kbd> en <kbd>]</kbd>, met de tekst ertussen.
   Formatteer de tekst zoals hij gedrukt is, handhaaf regelafbrekingen, cursief, enz.
   Tekst wat een (onderdeel van een) bijschrift kan zijn, moet aanwezig zijn,
   zoals bijvoorbeeld "Zie Pagina 66" of een titel binnen de afmeting van de illustratie. 
</p>
<p>Als een illustratie geen bijschrift heeft, voeg dan alleen een markering toe: <kbd>[Illustration]</kbd>.
   (Let er op dat je in dit geval de dubbele punt en de spatie voor de <kbd>]</kbd> verwijdert.)
</p>
<p>Als de illustratie midden in of naast een alinea staat, verplaats dan de illustratiemarkering
   naar voor of na de alinea, omgeven door lege regels om ze apart te houden.
   Verwijder evt. lege regels in de alinea.
</p>
<p>Als er op de pagina geen alinea begint of eindigt, markeer de illustratiemarkering dan met een
   <kbd>*</kbd>, zo: <kbd>*[Illustration:&nbsp;<font color="red">(tekst bijschrift)</font>]</kbd> en verplaats
   de markering naar de bovenkant van de bladzijde. Laat een lege regel over na de markering.
</p>
<!-- END RR -->

<table width="100%" align="center" border="1" cellpadding="4" cellspacing="0" summary="Illustration example">
  <tbody>
    <tr>
      <th align="left" bgcolor="cornsilk">Origineel:</th>
    </tr>
    <tr align="left">
      <td width="100%" valign="top"> <img src="../illust.png" alt="" width="500" height="525"> <br>
      </td>
    </tr>
    <tr>
      <th align="left" bgcolor="cornsilk">Correct geformatteerde tekst:</th>
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
      <th align="left" bgcolor="cornsilk">Origineel: (Illustratie in het midden van de alinea)</th>
    </tr>
    <tr align="left">
      <td width="100%" valign="top"> <img src="../illust2.png" alt="" width="500" height="514"> <br>
      </td>
    </tr>
    <tr>
      <th align="left" bgcolor="cornsilk">Correct geformatteerde tekst:</th>
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
<p class="backtotop"><a href="#top">Terug naar boven</a></p>


<h3><a name="footnotes">Voetnoten/Eindnoten</a>
 (<i><a href="#footnotes">Footnotes/Endnotes</a></i>)</h3>
<p>Formatteer voetnoten door de tekst van de voetnoot onderaan de bladzijde te plaatsen,
   en een markering te plaatsen waar er in de tekst naar wordt verwezen.
   Dit houdt in:
</p>
<p>1. In de hoofdtekst: het teken dat de plaats van de voetnoot markeert,
   moet tussen vierkante haken gezet worden (<kbd>[</kbd> en <kbd>]</kbd>) en meteen na het woord waar
   de voetnoot bij hoort, gezet worden<kbd>[1]</kbd>. Als er interpunctie naast het woord staat,
   dan komt het symbool dat de voetnoot markeert, meteen naast de interpunctie.<kbd>[2]</kbd>
   Zie de voorbeelden hieronder, en ook in deze tekst.
   Als voetnoten gemarkeerd zijn met een symbool of een serie bijzondere tekens (*, &dagger;, &Dagger;, &sect;,
   etc.) dan vervangen we deze door hoofdletters op volgorde van het alfabet (A, B, C, enz.).
</p>
<p>2. Onderaan de pagina: een voetnoot moet door een voetnootmarkering omgeven worden: <kbd>[Footnote #:&nbsp;</kbd> en <kbd>]</kbd>,
   met de voetnoot ertussen. Het cijfer of de letter van de voetnoot komt op de plaats van #.
   Formatteer de voetnoot zoals hij gedrukt is, en behoud regelafbrekingen, cursief enz.
   Zorg er wel voor, dat je dezelfde markering gebruikt voor de voetnoot,
   als wat er in de tekst staat waar de voetnoot naar verwijst.
   Plaats elke voetnoot op een aparte regel in de volgorde zoals ze voorkomen,
   met een lege regel voor elke nieuwe voetnoot.
</p>
<!-- END RR -->

<p>Wanneer een voetnoot aan het eind van een pagina incompleet is, laat deze dan onderaan de bladzijde staan en voeg een
   <kbd>*</kbd> toe waar de voetnoot eindigt, bijvoorbeeld: <kbd>[Footnote 1: <font color="red">(tekst van de voetnoot)</font>]*</kbd>.
   De <kbd>*</kbd> brengt het onder de aandacht van de Post-Processor, die uiteindelijk de delen van de voetnoot zal samenvoegen.
</p>
<p>Wanneer een voetnoot op een eerdere pagina begon, laat deze dan onderaan de bladzijde staan en zet er
   <kbd>*[Footnote: <font color="red">(tekst van de voetnoot)</font>]</kbd> om heen. (zonder een voetnoot nummer of markering)
   De <kbd>*</kbd> brengt het onder de aandacht van de Post-Processor, die uiteindelijk de delen van de voetnoot zal samenvoegen.
</p>
<p>Als een voetnoot begint of eindigt met een afgebroken woord, markeer dan <b>zowel</b> de voetnoot
   <b>als</b> het afgebroken woord met <kbd>*</kbd>, zo:<br>
   <kbd>[Footnote 1: Deze voetnoot gaat nog door en het laatste woord erin is afge-*]*</kbd><br>
   voor het eerste deel, en<br>
   <kbd>*[Footnote: *broken, en loopt door op de volgende pagina.]</kbd>.
</p>
<p>Voeg geen enkele horizontale lijn toe die de voetnoot van de normale tekst scheidt.
</p>
<p><b>Eindnoten</b> zijn voetnoten die samen aan het eind van een hoofdstuk of aan het eind van een boek
   staan. Ze worden op dezelfde manier geformatteerd als voetnoten.
   Als je ergens in de tekst een verwijzing naar een eindnoot vindt, zet er <kbd>[</kbd> en <kbd>]</kbd> omheen.
   Als je een van de pagina's met eindnoten aan het formatteren bent, zet dan
   <kbd>[Footnote #:&nbsp;<font color="red">(tekst van de eindnoot)</font>]</kbd>, met de tekst van de eindnoot ertussen.
   Het cijfer of de letter van de voetnoot komt op de plaats van #. Voeg voor elke noot een lege regel in,
   zodat ze aparte alinea's blijven als de tekst wordt <span style="border-bottom: 1px dotted green;"
    title="rewrap: het opnieuw aanbrengen/verplaatsen van regelafbrekingen"><i>gerewrapped</i></span> tijdens het post-processen.
</p>
<!-- Need an example of Endnotes, maybe? Good idea!-->

<p><b>Voetnoten in <a href="#tables">Tabellen</a></b> moeten blijven staan, waar ze in het origineel voorkomen.
</p>

<table width="100%" border="1" cellpadding="4" cellspacing="0" align="center" summary="Footnote Example">
  <tbody>
    <tr>
      <th valign="top" align="left" bgcolor="cornsilk">Origineel:</th>
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
    <tr><th align="left" bgcolor="cornsilk">Correct geformatteerde tekst:</th></tr>
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
    <tr><th align="left" bgcolor="cornsilk">Origineel, Po&euml;zie met voetnoot:</th></tr>
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
    <tr><th align="left" bgcolor="cornsilk">Correct geformatteerde tekst:</th></tr>
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
<p class="backtotop"><a href="#top">Terug naar boven</a></p>


<h3><a name="para_side">Beschrijvingen naast een Alinea (Sidenotes)</a>
 (<i><a href="#para_side">Paragraph Side-Descriptions (Sidenotes)</a></i>)</h3>
<p>Sommige boeken hebben korte beschrijvingen van de alinea naast de tekst. Deze beschrijvingen worden sidenotes genoemd.
   Verplaats de sidenotes naar vlak boven de alinea waar ze bij horen. Een sidenote moet omgeven worden
   door een sidenote markering: <kbd>[Sidenote:&nbsp;</kbd> en <kbd>]</kbd>, met de tekst van de sidenote er tussenin.
   Formatteer de tekst van de sidenote zoals hij gedrukt is, en handhaaf regelafbrekingen, cursief enz.
   (terwijl het koppelteken en de streepjes aan het eind van een regel normaal behandeld worden).
   Zet een lege regel voor en na de sidenote, zodat deze gescheiden blijft van de normale tekst.
</p>
<p>Als er meerdere sidenotes bij &eacute;&eacute;n alinea zijn, zet ze dan achter elkaar v&oacute;&oacute;r de alinea.
   Laat een lege regel tussen de verschillende sidenotes.
</p>
<p>Als de alinea al op de vorige pagina begonnen is, zet dan de sidenote bovenaan de pagina en
   markeer hem met een sterretje (<kbd>*</kbd>), zodat de Post-Processor kan zien dat de sidenote op de vorige pagina hoort.
   Doe het zo: <kbd>*[Sidenote: <font color="red">(tekst van de sidenote)</font>]</kbd>.
   De Post-Processor zal de sidenote verplaatsen naar waar hij hoort.
</p>
<p>Soms zal een Project Manager vragen, of je de sidenotes naast de zin waar ze bij horen,
   wil plaatsen, in plaats van boven of onder de alinea.
   In dit geval zet je geen lege regels voor of na de sidenotes.
</p>
<!-- END RR -->

<table width="100%" align="center" border="1" cellpadding="4" cellspacing="0" summary="Sidenote example">
  <tbody>
    <tr valign="top">
      <th align="left" bgcolor="cornsilk">Origineel:</th>
    </tr>
    <tr valign="top">
      <td width="100%" align="left"><img src="../side.png" alt="" width="550" height="800"><br>
      </td>
    </tr>
    <tr valign="top">
      <th align="left" bgcolor="cornsilk">Correct geformatteerde tekst:</th>
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
<p class="backtotop"><a href="#top">Terug naar boven</a></p>



<h3><a name="outofline">Plaatsing van Out-of-Line Formattering Markering</a>
 (<i><a href="#outofline">Placement of Out-of-Line Formatting Markup</a></i>)</h3>
<p>Out-of-line formattering verwijst naar de <kbd>/#</kbd> <kbd>#/</kbd> en <kbd>/*</kbd> <kbd>*/</kbd> markering.
   De <kbd>/#</kbd> <kbd>#/</kbd> "<span style="border-bottom: 1px dotted green;"
   title="rewrap: het opnieuw aanbrengen/verplaatsen van regelafbrekingen">rewrap</span>" markering geeft aan dat de
   tekst anders is gedrukt, maar dat deze nog steeds kan worden <span style="border-bottom: 1px dotted green;"
   title="rewrap: het opnieuw aanbrengen/verplaatsen van regelafbrekingen">gerewrapped</span> tijdens het post-processen.
   De <kbd>/*</kbd> <kbd>*/</kbd> "<span style="border-bottom: 1px dotted green;"
   title="rewrap: het opnieuw aanbrengen/verplaatsen van regelafbrekingen">no-wrap</span>" markering geeft aan dat de
   tekst tijdens het post-processen niet moet worden <span style="border-bottom: 1px dotted green;"
   title="rewrap: het opnieuw aanbrengen/verplaatsen van regelafbrekingen">gerewrapped</span>&mdash;waar de regelafbrekingen,
   inspringing en spati&euml;ring behouden moet blijven.
</p>
<p>Op elke pagina waar je een open-markering gebruikt, moet je ook een sluiten-markering gebruiken.
   Nadat tijdens het post-processen de tekst is <span style="border-bottom: 1px dotted green;"
   title="rewrap: het opnieuw aanbrengen/verplaatsen van regelafbrekingen">gerewrapped</span>, zal elke
   markering, compleet met de hele regel waar deze op staat, worden verwijderd. Daarom moet er een
   lege regel tussen de normale tekst en de open-markering worden toegevoegd, en vergelijkbaar ook
   lege regel tussen de normale tekst en de sluiten-markering.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Terug naar boven</a></p>


<h3><a name="block_qt">Citaten</a>
 (<i><a href="#block_qt">Block Quotations</a></i>)</h3>
<p>Blok citaten zijn lange citaten (vaak meerdere regels, soms meerdere pagina's)
   welke zich onderscheiden van de andere tekst door bredere kantlijnen, een kleiner lettertype, verschillende
   inspringing, of iets dergelijks. Omgeef de blok citaten <kbd>/#</kbd> en <kbd>#/</kbd> markeringen. 
   Zie <a href="#outofline">Plaatsing van Out-of-Line Formattering Markering</a> voor details over
   deze markering.
</p>
<p>Voor het overige worden citaten net zo geformatteerd als alle andere tekst.
</p>
<!-- END RR -->

<table width="100%" align="center" border="1" cellpadding="4" cellspacing="0" summary="Block Quotation example">
  <tbody>
    <tr><th align="left" bgcolor="cornsilk">Origineel:</th></tr>
    <tr align="left">
      <td width="100%" valign="top"> <img src="../bquote.png" alt="" width="500" height="475"><br>
      </td>
    </tr>
    <tr><th align="left" bgcolor="cornsilk">Correct geformatteerde tekst:</th></tr>
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
<p class="backtotop"><a href="#top">Terug naar boven</a></p>


<h3><a name="lists">Lijsten</a>
 (<i><a href="#lists">Lists of Items</a></i>)</h3>
<p>Zet lijsten tussen <kbd>/*</kbd> en <kbd>*/</kbd> markeringen. 
   Zie <a href="#outofline">Plaatsing van Out-of-Line Formattering Markering</a> voor details over deze markering.
</p>
<!-- END RR -->

<table width="100%" align="center" border="1" cellpadding="4" cellspacing="0" summary="List example">
  <tbody>
    <tr><th align="left" bgcolor="cornsilk">Origineel:</th></tr>
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
    <tr><th align="left" bgcolor="cornsilk">Correct geformatteerde tekst:</th></tr>
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
<p class="backtotop"><a href="#top">Terug naar boven</a></p>


<h3><a name="tables">Tabellen</a>
 (<i><a href="#tables">Tables</a></i>)</h3>
<p>Zet tabellen tussen <kbd>/*</kbd> en <kbd>*/</kbd> markeringen. 
   Zie <a href="#outofline">Plaatsing van Out-of-Line Formattering Markering</a> voor details over deze markering.
   Formatteer de tabel met spaties (<b>geen tabs</b>), zo dat de tabel er ongeveer uitziet zoals het origineel.
   Probeer waar mogelijk extreem brede tabellen te vermijden; is het algemeen is een breedte onder
   75 tekens het beste.
</p>
<p>Gebruik geen tabs bij het formatteren&mdash;gebruik alleen spaties. Tabs worden verschillend behandeld
   op verschillende computers, zodat een tabel met tabs, er op een andere computer niet meer zo goed
   uitziet als op je eigen computer. Verwijder alle punten of andere interpunctie die gebruikt zijn
   om de gegevens te uit te lijnen.
</p>
<p>Wanneer inline formatting (cursief, vet, enz.) nodig is in de tabel, markeer dan elke cel
   van de tabel apart. Denk er bij het uitlijnen van de tekst aan dat inline opmaak in de
   uiteindelijke tekst versie anders wordt weergegeven. Bijvoorbeeld, <kbd>&lt;i&gt;</kbd>cursieve
   opmaak<kbd>&lt;/i&gt;</kbd> wordt normaal gesproken <kbd>_</kbd>lage streepjes<kbd>_</kbd>,
   en de meeste andere inline opmaak wordt vergelijkbaar behandeld. Aan de andere kant wordt
   <kbd>&lt;sc&gt;</kbd>Klein Kapitaal Opmaak<kbd>&lt;/sc&gt;</kbd> helemaal verwijderd.
</p>
<p>Het is vaak moeilijk een tabel in normale tekst te formatteren; doe gewoon je best.
   Let er op dat je een 'mono-spaced' lettertype gebruikt, zoals
   <a href="../font_sample.php">DPCustomMono</a> of Courier.
   Houd in gedachten dat we de bedoeling van de auteur willen behouden, en intussen een leesbare
   tabel in een e-boek willen cre&euml;ren. Soms betekent dit, dat we het originele tabel-formaat zoals het
   gedrukt is, moeten opofferen. Lees de <a href="#comments">Project Comments</a> en de Project Discussie;
   andere vrijwilligers kunnen al besloten hebben tot een specifiek formaat. Als je daar niets kan vinden,
   kun je misschien meer vinden in de Engelse Forumdiscussie <a href="<?php echo $Gallery_of_Table_Layouts_URL; ?>">Gallery of Table Layouts</a>.
</p>
<p><b>Voetnoten</b> in tabellen moeten blijven waar ze in het origineel staan.
   Zie <a href="#footnotes">Voetnoten</a> voor details.
</p>
<!-- END RR -->
<table width="100%" align="center" border="1" cellpadding="4" cellspacing="0" summary="Table Example">
  <tbody>
    <tr><th align="left" bgcolor="cornsilk">Origineel:</th></tr>
    <tr align="left">
      <td width="100%" valign="top"> <img src="../table2.png" alt="" width="500" height="304"><br>
      </td>
    </tr>
    <tr><th align="left" bgcolor="cornsilk">Correct geformatteerde tekst:</th></tr>
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
    <tr><th align="left" bgcolor="cornsilk">Origineel:</th></tr>
    <tr align="left">
      <td width="100%" valign="top"> <img src="../table3.png" alt="" width="480" height="231"><br>
      </td>
    </tr>
    <tr><th align="left" bgcolor="cornsilk">Correct geformatteerde tekst:</th></tr>
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
<p class="backtotop"><a href="#top">Terug naar boven</a></p>


<h3><a name="poetry">Po&euml;zie/Epigrammen</a>
 (<i><a href="#poetry">Poetry/Epigrams</a></i>)</h3>
<p>Markeer po&euml;zie of epigrammen met <kbd>/*</kbd> en <kbd>*/</kbd> zo dat de regelafbrekingen en
   spati&euml;ring behouden blijft. 
   Zie <a href="#outofline">Plaatsing van Out-of-Line Formattering Markering</a> voor details over deze markering.
</p>
<p>Laat de relatieve inspringing van de regels van een gedicht intact, door 2, 4, 6 (of meer) spaties
   te plaatsen voor de ingesprongen regels, opdat de tekst zoveel mogelijk op het origineel lijkt.
   Wanneer het hele gedicht is gecentreerd op de pagina, moet niet geprobeerd worden om de
   po&euml;zie-regels tijdens het formatteren te centreren. Verplaats de regels naar de
   linkerkantlijn, en bewaar alleen de relatieve inspringing van de regels.
</p>
<p>Als een dichtregel te lang is om op de gedrukte pagina te passen, wordt vaak het restant van de regel
   op de volgende regel gezet, met een flinke inspringing. Dit restant van de regel moet in zijn geheel
   worden samengevoegd met de regel erboven. Restregels beginnen in het algemeen met een kleine letter
   (dus niet een hoofdletter). Bovendien komen ze op onvoorspelbare momenten voor, in tegenstelling tot
   de normale inspringing, die regelmatig is, in overeenstemming met het metrum van het gedicht.
</p>
<p>Wanneer een regel met puntjes voorkomt in een gedicht, behandelen we dit als een
   <a href="#extra_s">gedachtesprong</a>.
</p>
<p><a href="#line_no">Regelnummers</a> in po&euml;zie moeten worden behouden.
</p>
<p>Controleer de <a href="#comments">Project Comments</a> voor de specifieke tekst die je formatteert.
   De Project Manager heeft vaak speciale instructies voor boeken met po&euml;zie. Vaak zijn de hier gegeven
   richtlijnen niet of niet geheel van toepassing bij een boek dat geheel of bijna geheel uit po&euml;zie bestaat.
</p>
<!-- END RR -->

<table width="100%" align="center" border="1" cellpadding="4" cellspacing="0" summary="Poetry Example">
  <tbody>
    <tr><th align="left" bgcolor="cornsilk">Origineel:</th></tr>
    <tr align="left">
      <td width="100%" valign="top"> <img src="../poetry.png" alt="" width="500" height="508"> <br>
      </td>
    </tr>
    <tr><th align="left" bgcolor="cornsilk">Correct geformatteerde tekst:</th></tr>
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
<p class="backtotop"><a href="#top">Terug naar boven</a></p>


<h3><a name="line_no">Regelnummers</a>
 (<i><a href="#line_no">Line Numbers</a></i>)</h3>
<p>Regelnummers komen vaak voor in gedichtenbundels, en staan over het algemeen bij elke
   vijfde of tiende regel in de kantlijn.
   Laat regelnummers staan. Zet op zijn minst zes spaties tussen het rechter uiteinde van de regel en het nummer,
   zelfs als ze in het origineel aan de linkerkant van de tekst of de po&euml;zie staan.
   Aangezien po&euml;zie niet wordt <span style="border-bottom: 1px dotted green;"
   title="rewrap: het opnieuw aanbrengen/verplaatsen van regelafbrekingen"><i>gerewrapped</i></span>
   in de eboek versie, zijn regelnummers nuttig voor lezers.
</p>
<!-- END RR -->
<!-- We need an example image and text for this. -->
<p class="backtotop"><a href="#top">Terug naar boven</a></p>


<h3><a name="letter">Brieven/Correspondentie</a>
 (<i><a href="#letter">Letters/Correspondence</a></i>)</h3>
<p>Formatteer brieven en correspondentie als <a href="#para_space">alinea's</a>.
   Zet een lege regel voor het begin van de brief. Eventuele inspringing wordt genegeerd.
</p>
<p>Bij elkaar horende regels van kop- of voettekst (bijvoorbeeld adres, datum, begroeting, ondertekening)
   moet in een blok gezet worden, door er <kbd>/*</kbd> en <kbd>*/</kbd> markeringen omheen te zetten.
   Zie <a href="#outofline">Plaatsing van Out-of-Line Formattering Markering</a> voor details over deze markering.
</p>
<p>Voeg geen inspringing toe aan kop- of voetteksten, ook niet als ze in het origineel zijn
   ingesprongen of rechts zijn uitgelijnd&mdash;zet de regels gewoon aan de linkerkant.
   De Post-Processor zal er indien gewenst formattering aan toevoegen.
</p>
<p>Zie <a href="#block_qt">Blok Citaten</a>, wanneer correspondentie anders wordt weergegeven dan de hoofdtekst.
</p>
<!-- END RR -->

<table width="100%" align="center" border="1" cellpadding="4" cellspacing="0" summary="Letter Example">
  <tbody>
    <tr><th align="left" bgcolor="cornsilk">Origineel:</th></tr>
    <tr align="left">
      <td width="100%" valign="top"> <img src="../letter.png" alt="" width="500" height="217"> <br>
      </td>
    </tr>
    <tr><th align="left" bgcolor="cornsilk">Correct geformatteerde tekst:</th></tr>
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
    <tr><th align="left" bgcolor="cornsilk">Origineel:</th></tr>
    <tr align="left">
      <td width="100%" valign="top"> <img src="../letter2.png" alt="" width="500" height="271"> <br>
      </td>
    </tr>
    <tr><th align="left" bgcolor="cornsilk">Correct geformatteerde tekst:</th></tr>
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
<p class="backtotop"><a href="#top">Terug naar boven</a></p>


<h3><a name="r_align">Rechts-uitgelijnde Tekst</a>
 (<i><a href="#r_align">Right-aligned Text</a></i>)</h3>
<p>Omgeef de regels met rechts-uitgelijnde tekst met <kbd>/*</kbd> en <kbd>*/</kbd> opmaak.
   Zie <a href="#outofline">Plaatsing van Out-of-Line Formattering Markering</a> voor details
   over deze markering, en de <a href="#letter">Brieven/Correspondentie</a> paragraaf voor voorbeelden.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Terug naar boven</a></p>


<table width="100%" border="0" cellspacing="0" cellpadding="6" summary="Page-level formatting">
  <tbody>
    <tr>
      <td bgcolor="silver"><h2 style="margin-bottom: 0; margin-top: 0;">Formatteren op het Pagina-Niveau:</h2></td>
    </tr>
  </tbody>
</table>


<h3><a name="blank_pg">Lege Pagina</a>
 (<i><a href="#blank_pg">Blank Page</a></i>)</h3>
<p>Formatteer als <kbd>[Blank Page]</kbd> als zowel de tekst als het origineel leeg zijn.
</p>
<p>Als er wel tekst is, waar de te formatteren tekst hoort te staan, maar niet in het origineel,
   of als er tekst in het origineel staat maar geen tekst in het tekstvak, volg dan de aanwijzingen voor een
   <a href="#bad_image">Slecht Beeld/Origineel</a> (Bad Image) of een <a href="#bad_text">Verkeerd Beeld voor de Tekst</a> (Bad Text).
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Terug naar boven</a></p>


<h3><a name="title_pg">Titelpagina aan de Voor- of Achterkant</a>
 (<i><a href="#title_pg">Front/Back Title Page</a></i>)</h3>
<p>Formatteer alle tekst, inclusief het jaar waarin het boek is uitgegeven of het jaar van het copyright,
   precies zoals het op de pagina's gedrukt is, hoofdletters, kleine letters, enz.
</p>
<p>In oudere boeken wordt de eerste letter vaak groot en bewerkt weergegeven, formatteer deze letter gewoon als de letter.
</p>
<!-- END RR -->

<table width="100%" align="center" border="1" cellpadding="4" cellspacing="0" summary="Title Page Example">
  <tbody>
    <tr>
      <th align="left" bgcolor="cornsilk">Origineel:</th>
    </tr>
    <tr align="left">
      <td width="100%" valign="top"><img src="../title.png" width="500" height="520" alt=""><br>
      </td>
    </tr>
    <tr>
      <th align="left" bgcolor="cornsilk">Correct geformatteerde tekst:</th>
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
<p class="backtotop"><a href="#top">Terug naar boven</a></p>


<h3><a name="toc">Inhoudsopgave</a>
 (<i><a href="#toc">Table of Contents</a></i>)</h3>
<p>Formatteer de inhoudsopgave precies zoals deze in het boek gedrukt staat, hoofdletters, kleine letters enz.,
   en zet er <kbd>/*</kbd> en <kbd>*/</kbd> achter. 
   Zie <a href="#outofline">Plaatsing van Out-of-Line Formattering Markering</a> voor details over deze markering.
</p>
<p>Verwijzingen naar paginanummers moeten <b>tenminste zes spaties</b> voorbij het eind van tekst gezet worden.
   Verwijder alle punten of andere interpunctie die gebruikt zijn om de paginanummers te uit te lijnen.
</p>
<!-- END RR -->

<table width="100%" align="center" border="1" cellpadding="4" cellspacing="0" summary="Table of Contents example">
  <tbody>
    <tr>
      <th align="left" bgcolor="cornsilk">Origineel:</th>
    </tr>
    <tr align="left">
      <td width="100%" valign="top"><img src="../tablec.png" alt="" width="500" height="650"></td>
    </tr>
    <tr>
      <th align="left" bgcolor="cornsilk">Correct geformatteerde tekst:</th>
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
<p class="backtotop"><a href="#top">Terug naar boven</a></p>


<h3><a name="bk_index">Indexen</a>
 (<i><a href="#bk_index">Indexes</a></i>)</h3>
<p>Markeer de index met <kbd>/*</kbd> en <kbd>*/</kbd> markering.
   Zie <a href="#outofline">Plaatsing van Out-of-Line Formattering Markering</a> voor details over deze markering.
   Je hoeft de nummers niet netjes op een rij te zetten, zoals ze in het origineel staan.
   Zet gewoon een komma neer, gevolgd door de paginanummers.
</p>
<p>Indexen zijn vaak in 2 kolommen afgedrukt, waardoor sommige ingangen gedeeltelijk op de
   volgende regel terechtkomen. Voeg deze samen op &eacute;&eacute;n regel.
   Dit kan voor lange regels zorgen, maar deze zullen tijdens het post-processen tot een
   passende breedte en inspringing worden <span style="border-bottom: 1px dotted green;"
    title="rewrap: het opnieuw aanbrengen/verplaatsen van regelafbrekingen"><i>gerewrapped</i></span>.
</p>
<p>Voeg voor elke ingang in de index (<i>entry</i>) een lege regel toe.
   Als er sub-topics zijn (vaak gescheiden door een puntkomma <kbd>;</kbd>), zet die dan op een nieuwe
   regel en spring 2 spaties in.
</p>
<p>Behandel elke nieuwe sectie in een index (A, B, C....) als een  <a href="#sect_head">paragraaftitel</a>
   door er 2 lege regels voor te zetten.
</p>
<p>In oude boeken is soms het eerste woord van elke paragraaf in de index gedrukt in allemaal hoofdletters of
   klein kapitaal; verander dit zo dat het overeenkomt met de rest van de ingangen van de index.
</p>
<p>Lees de <a href="#comments">Project Comments</a> aangezien de Project Manager om andere formattering kan vragen,
   bijvoorbeeld door de index als een <a href="#toc">Inhoudsopgave</a> te behandelen.
</p>
<!-- END RR -->

<table width="100%" align="center" border="1" cellpadding="4" cellspacing="0" summary="Rejoining Index Lines">
  <tbody>
    <tr>
      <th align="left" bgcolor="cornsilk">Origineel:</th>
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
      <th align="left" bgcolor="cornsilk">Correct geformatteerde tekst:</th>
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
    <tr><th align="left" bgcolor="cornsilk">Origineel:</th></tr>
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
      <th align="left" bgcolor="cornsilk">Correct geformatteerde tekst:</th>
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
    <tr><th align="left" bgcolor="cornsilk">Origineel:</th></tr>
    <tr align="left">
      <td valign="top"> <img src="../index.png" alt="" width="438" height="355"></td>
    </tr>
    <tr><th align="left" bgcolor="cornsilk">Correct geformatteerde tekst:</th></tr>
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
<p class="backtotop"><a href="#top">Terug naar boven</a></p>


<h3><a name="play_n">Toneelstukken: Namen van Spelers/Regieaanwijzingen</a>
 (<i><a href="#play_n">Plays: Actor Names/Stage Directions</a></i>)</h3>
<p>Voor alle toneelstukken:</p>
<ul compact>
 <li>Formatteer de lijst van medespelenden (Dramatis Person&aelig;) als <a href="#lists">lijsten</a>.</li>
 <li>Behandel elke nieuwe Acte (Toneel) hetzelfde als een <a href="#chap_head">hoofdstuktitel</a>
     door ervoor 4 lege regels en erna 2 lege regels toe te voegen</li>
 <li>Behandel elke nieuwe Sc&egrave;ne hetzelfde als een <a href="#sect_head">paragraaftitel</a>
     door ervoor 2 lege regels toe te voegen.</li>
 <li>Behandel een verandering van spreker in een dialoog als een nieuwe alinea, met een lege regel ervoor.
     Behandel de naam van de spreker als een aparte alinea, wanneer deze op een aparte regel staat.</li>
 <li>Formatteer de namen van de acteurs als in het origineel, of ze nu <a href="#italics">cursief</a>,
     <a href="#bold">vet</a> of in hoofdletters zijn gedrukt.</li>
 <li>Regieaanwijzingen worden geformatteerd zoals ze in het origineel staan,
     dus als ze op een aparte regel staan, laat ze daar dan zo staan. Staan ze aan het eind van een regel
     met dialoog, dan laat je ze daar. Staan ze rechts uitgelijnd aan het eind van een regel met dialoog,
     laat dan zes spaties tussen de dialoog en de regieaanwijzingen.<br>
     Regieaanwijzingen beginnen vaak met een haakje openen en laten het haakje sluiten weg.
     Deze gewoonte wordt gehandhaafd: sluit de haakjes niet. Cursieve tekst staat meestal binnen de haakjes.</li>
</ul>
<p>Voor metrische stukken (stukken die geschreven zijn als po&euml;zie):</p>
<ul compact>
 <li>Veel toneelstukken zijn metrisch, en net als po&euml;zie moeten ze niet
     <span style="border-bottom: 1px dotted green;" title="rewrap: het opnieuw aanbrengen/verplaatsen van regelafbrekingen"><i>gerewrapped</i></span> worden.
     Zet <kbd>/*</kbd> voor en <kbd>*/</kbd> na metrische tekst, net als bij <a href="#poetry">po&euml;zie</a>.
     Als de regieaanwijzingen op een aparte regel staan, zet er dan geen <kbd>/*</kbd> en <kbd>*/</kbd> omheen.
     (Aangezien regieaanwijzingen niet metrisch zijn, en dus veilig kunnen worden <span style="border-bottom: 1px dotted green;"
       title="rewrap: het opnieuw aanbrengen/verplaatsen van regelafbrekingen"><i>gerewrapped</i></span>, hoeven ze niet binnen de
     <kbd>/*</kbd> <kbd>*/</kbd> markering te staan, die de metrische dialoog beschermt.)</li>
 <li>Handhaaf het inspringen van dialoog net zo als <a href="#poetry">po&euml;zie</a>.</li>
 <li>Voeg metrische regels, die gesplitst moesten worden omdat het papier te smal was, samen, net als bij <a href="#poetry">po&euml;zie</a>.<br>
     Als het laatste stukje uit alleen maar &eacute;&eacute;n of twee woorden bestaat, dan staat het vaak op de regel
     eronder of erboven, na een (, in plaats van op een eigen regel.
     Zie het <a href="#play4">voorbeeld</a>.</li>
</ul>
<p>Let goed op de <a href="#comments">Project Comments</a>, de Project Manager kan een andere manier van formatteren vragen.
</p>
<!-- END RR -->

<table width="100%" align="center" border="1" cellpadding="4" cellspacing="0" summary="Play Example 1">
  <tbody>
    <tr>
      <th align="left" bgcolor="cornsilk">Origineel:</th>
    </tr>
    <tr align="left">
      <td width="100%" valign="top"><img src="../play1.png" width="500" height="430" alt=""><br>
      </td>
    </tr>
    <tr>
      <th align="left" bgcolor="cornsilk">Correct geformatteerde tekst:</th>
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
      <th align="left" bgcolor="cornsilk">Origineel:</th>
    </tr>
    <tr align="left">
      <td width="100%" valign="top"><img src="../play2.png" width="500" height="680" alt=""><br>
      </td>
    </tr>
    <tr>
      <th align="left" bgcolor="cornsilk">Correct geformatteerde tekst:</th>
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
      <th align="left" bgcolor="cornsilk">Origineel:</th>
    </tr>
    <tr align="left">
      <td width="100%" valign="top"><img src="../play3.png" width="504" height="206" alt=""><br>
      </td>
    </tr>
    <tr>
      <th align="left" bgcolor="cornsilk">Correct geformatteerde tekst:</th>
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
      <th align="left" bgcolor="cornsilk">Origineel:</th>
    </tr>
    <tr align="left">
      <td width="100%" valign="top"><img src="../play4.png" width="502" height="98" alt=""><br>
      </td>
    </tr>
    <tr>
      <th align="left" bgcolor="cornsilk">Correct geformatteerde tekst:</th>
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
<p class="backtotop"><a href="#top">Terug naar boven</a></p>


<h3><a name="anything">Alles wat op een speciale manier aangepakt moet worden, of waar je onzeker over bent</a>
 (<i><a href="#anything">Anything else that needs special handling or that you're unsure of</a></i>)</h3>
<p>Als je bij het formatteren iets tegenkomt dat niet in deze richtlijnen behandeld wordt,
   en waarvan je wel denkt dat het op een speciale manier aangepakt moet worden, of als je niet
   zeker bent hoe het aan te pakken, post dan je vraag, onder vermelding van het png (pagina) nummer,
   in de <a href="#forums">Project Discussie</a>.
</p>
<p>Je moet ook een aantekening (noot) in de geformatteerde tekst zetten, waarin je aan de volgende
   formatteerder of de Post-Processor uitlegt wat het probleem of de vraag is.
   Begin je aantekening met een vierkant haakje en twee sterretjes <kbd>[**</kbd> en sluit hem
   af met een vierkant haakje <kbd>]</kbd>. Dit onderscheidt je aantekening duidelijk van de tekst
   van de schrijver en geeft de Post-Processor een signaal om even te stoppen en zorgvuldig dit
   gedeelte tekst &amp; origineel te vergelijken om een evt. probleem aan te pakken.
   Je kunt ook aangeven in welke ronde je werkt (bv. net voor de <kbd>]</kbd>) zodat
   volgende vrijwilligers weten wie de aantekening heeft achtergelaten.
   Al het commentaar welke door een vorige vrijwilliger is toegevoegd <b>moet</b> blijven staan.
   Zie de volgende paragraaf voor details.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Terug naar boven</a></p>


<h3><a name="prev_notes">Aantekeningen/Commentaar van Eerdere Vrijwilligers</a>
 (<i><a href="#prev_notes">Previous Volunteers' Notes/Comments</a></i>)</h3>
<p>Alle commentaar dat gemaakt is door vrijwilligers v&oacute;&oacute;r je <b>moet</b> blijven staan.
   Of je het er mee eens bent of niet kun je toevoegen, maar je mag het commentaar absoluut niet verwijderen.
   Als je een bron hebt gevonden die het probleem verheldert, verwijs daar dan naar,
   zodat de Post-Processor er ook naar kan verwijzen.
</p>
<p>Als je stuit op een aantekening van een eerdere vrijwilliger,
   waar je het antwoord op weet, neem dan even de moeite om feedback te geven.
   Je klikt in de formatting interface op de naam van de betreffende vrijwilliger en stuurt ze een
   priv&eacute; boodschap (<i>private message</i>) waarin je uitlegt hoe de situatie aangepakt kan worden.
   Zoals eerder vermeld: verwijder alsjeblieft de aantekening niet.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Terug naar boven</a></p>


<table width="100%" border="0" cellspacing="0" cellpadding="6" summary="Common Problems">
  <tbody>
    <tr>
      <td bgcolor="silver"><h2 style="margin-bottom: 0; margin-top: 0;">Veel voorkomende problemen:</h2></td>
    </tr>
  </tbody>
</table>


<h3><a name="bad_image">Slecht Beeld/Origineel</a>
 (<i><a href="#bad_image">Bad Image</a></i>)</h3>
<p>Als het beeld van een origineel niet goed is (laadt niet, een stuk er af, onmogelijk om te lezen)
   post dan alsjeblieft in het <a href="#forums">Project Discussie</a> over dit slechte origineel.
</p>
<p>Houd in de gaten dat sommige pagina's erg groot zijn, en dat het dan gebruikelijk is dat je browser
   moeite heeft om ze te laten zien, zeker als je meerdere vensters open hebt of als je een oudere
   computer gebruikt. Probeer enkele schermen en programma's te sluiten om te zien of dat helpt, of
   laat een bericht achter in de project discussie om te zien of iemand anders hetzelfde probleem heeft.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Terug naar boven</a></p>


<h3><a name="bad_text">Verkeerd Beeld/Origineel voor de Tekst</a>
 (<i><a href="#bad_text">Wrong Image for Text</a></i>)</h3>
<p>Als er een verkeerd origineel gegeven wordt voor de tekst, bericht dan alsjeblieft hierover
   in de <a href="#forums">Project Discussie</a>.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Terug naar boven</a></p>


<h3><a name="round1">Eerder Gemaakte Proeflees- en Formatteervergissingen</a>
 (<i><a href="#round1">Previous Proofreading or Formatting Mistakes</a></i>)</h3>
<p>Als een eerdere vrijwilliger veel vergissingen maakte, of allerlei dingen miste,
   neem dan alsjeblieft even de tijd om feedback te geven. Klik op de naam van de vrijwilliger
   in de formatting interface en stuur hem/haar een priv&eacute; boodschap, waarin je uitlegt hoe een
   dergelijke situatie kan worden aangepakt, zodat hij/zij dat voor een volgende keer weet.
</p>
<p><em>Wees daarbij alsjeblieft aardig!</em> Iedereen hier is een vrijwilliger en doet haar of zijn best.
   De bedoeling van de feedback is om te informeren over de juiste manier van formatteren,
   niet om te bekritiseren. Geef een concreet voorbeeld, om te laten zien wat de
   vrijwilliger gedaan heeft, en wat hij/zij hadden moeten doen.
</p>
<p>Als de vrijwilliger voor je uitstekend werk verricht heeft, kun je daar ook een boodschap
   over sturen&mdash;in het bijzonder als de pagina extra moeilijk was.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Terug naar boven</a></p>


<h3><a name="p_errors">Vergissingen van de Drukker/Spelfouten</a>
 (<i><a href="#p_errors">Printer Errors/Misspellings</a></i>)</h3>
<p>Corrigeer alle woorden die door de OCR fout zijn ge&iuml;nterpreteerd (scanno), maar verbeter
   geen spel- of zetfouten die op het origineel voorkomen. In veel oudere teksten werden woorden
   anders gespeld dan we tegenwoordig doen. We handhaven deze oude spelling, inclusief eventuele accenten.
</p>
<p>Maak een aantekening in de tekst direct naast de drukvaut<kbd>[**typo voor drukfout?]</kbd>.
   Als je niet zeker weet of het werkelijk een fout is, vraag het dan na in de Project Discussie.
   Als je iets verandert, maak dan een aantekening wat je veranderd hebt: <kbd>[**typo "drukvaut" verbeterd]</kbd>. 
   Zorg dat de twee sterretjes <kbd>**</kbd> er staan, zodat de Post-Processor de aantekening niet over het hoofd ziet.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Terug naar boven</a></p>


<h3><a name="f_errors">Feitelijke Fouten in de Tekst</a>
 (<i><a href="#f_errors">Factual Errors in Texts</a></i>)</h3>
<p>Verbeter feitelijke vergissingen in het boek niet. Veel boeken die we
   onder handen hebben, bevatten uitspraken over feiten die we nu niet meer als juist accepteren.
   Laat deze uitspraken staan zoals de schrijver ze geschreven heeft.
   Zie <a href="#p_errors">Vergissingen van de Drukker/Spelfouten</a> voor hoe een opmerking achter
   te laten, mocht je denken dat de gedrukte tekst niet is wat de auteur heeft bedoeld.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Terug naar boven</a></p>

</div>


<table width="100%" border="0" cellspacing="0" cellpadding="6" summary="Alphabetical Index">
  <tbody>
    <tr>
      <td bgcolor="silver"><h2 style="margin-bottom: 0; margin-top: 0;"><a name="index">Alfabetische Index bij de Richtlijnen</a></h2></td>
    </tr>
  </tbody>
</table>
<br>

<table border="0" width="100%" summary="Alphabetical Index">
  <tr>
    <td width="50%" valign="top">
      <ul>
        <li><a href="#prev_notes">Aantekeningen van Eerdere Vrijwilligers</a></li>
        <li><a href="#title_pg">Achterkant Titelpagina</a></li>
        <li><a href="#para_space">Alinea's, Ruimte tussen  /Inspringingen</a></li>
        <li><a href="#word_caps">Allemaal Hoofdletters</a></li>
        <li><a href="#anything">Alles wat op een speciale manier aangepakt moet worden</a></li>
        <li><a href="#maj_div">Andere Belangrijke Onderdelen in Teksten</a></li>
        <li><a href="#font_ch">Antiqua Tekst</a></li>
        <li><a href="#bad_image">Beeld, Slecht</a></li>
        <li><a href="#maj_div">Belangrijke Onderdelen in Teksten, Andere</a></li>
        <li><a href="#para_side">Beschrijvingen naast een Alinea</a></li>
        <li><a href="#illust">Bijschrift, Illustraties</a></li>
        <li><a href="#block_qt">Blokcitaten</a></li>
        <li><a href="#letter">Brieven/Correspondentie</a></li>
        <li><a href="#line_no">Cijfers in kantlijn</a></li>
        <li><a href="#block_qt">Citaten</a></li>
        <li><a href="#prev_notes">Commentaar van Eerdere Vrijwilligers</a></li>
        <li><a href="#letter">Correspondentie</a></li>
        <li><a href="#italics">Cursieve Tekst</a></li>
        <li><a href="#extra_s">Decoratie Tussen Alinea's</a></li>
        <li><a href="#anything">Dingen waar je onzeker over bent</a></li>
        <li><a href="#play_n">Drama</a></li>
        <li><a href="#p_errors">Drukfouten</a></li>
        <li><a href="#round1">Eerder Gemaakte Proeflees- en Formatteervergissingen</a></li>
        <li><a href="#prev_notes">Eerdere Vrijwilligers, Aantekeningen/Commentaar van</a></li>
        <li><a href="#footnotes">Eindnoten</a></li>
        <li><a href="#separate_pg">Elke Pagina is een Aparte Eenheid</a></li>
        <li><a href="#poetry">Epigrammen</a></li>
        <li><a href="#extra_s">Extra Ruimte Tussen Alinea's</a></li>
        <li><a href="#extra_sp">Extra Spaties tussen Woorden</a></li>
        <li><a href="#f_errors">Feitelijke Fouten in de Tekst</a></li>
        <li><a href="#separate_pg">Formatteer Elke Pagina Apart</a></li>
        <li><a href="#round1">Formatteervergissingen, Eerder Gemaakte</a></li>
        <li><a href="#inline">Formattering Markering, Inline</a></li>
        <li><a href="#outofline">Formattering Markering, Out-of-Line</a></li>
        <li><a href="#forums">Forum</a></li>
        <li><a href="#f_errors">Fouten in de Tekst, Feitelijke</a></li>
        <li><a href="#extra_s">Gedachtesprongen</a></li>
        <li><a href="#spaced"><span style="letter-spacing: .2em;">Gesperrt</span> (Uitgespatieerde Tekst)</a></li>
        <li><a href="#summary">Handige Proeflees Gids</a></li>
        <li><a href="#prev_pg">Het Herstellen van Vergissingen op Voorgaande Pagina's</a></li>
        <li><a href="#word_caps">Hoofdletters, Allemaal</a></li>
        <li><a href="#small_caps">Hoofdlettertjes, <span style="font-variant: small-caps">Kleine</span></a></li>
        <li><a href="#prime">Hoofdregel</a></li>
        <li><a href="#chap_head">Hoofdstuktitels</a></li>
        <li><a href="#extra_s">Horizontale Lijnen</a></li>
        <li><a href="#illust">Illustraties</a></li>
        <li><a href="#maj_div">Illustraties, Lijst van</a></li>
        <li><a href="#bk_index">Indexen</a></li>
        <li><a href="#toc">Inhoudsopgave</a></li>
        <li><a href="#inline">Inline Formattering Markering, Plaatsing van</a></li>
        <li><a href="#para_space">Inspringingen</a></li>
        <li><a href="#maj_div">Introductie</a></li>
        <li><a href="#small_caps"><span style="font-variant: small-caps">Klein Kapitaal</span></a></li>
        <li><a href="#small_caps"><span style="font-variant: small-caps">Kleine Hoofdlettertjes (Klein Kapitaal)</span></a></li>
        <li><a href="#blank_pg">Lege Pagina</a></li>
        <li><a href="#font_sz">Lettergrootte, Veranderingen in</a></li>
        <li><a href="#font_ch">Lettertype Veranderingen</a></li>
        <li><a href="#extra_s">Lijn Tussen Alinea's</a></li>
        <li><a href="#extra_s">Lijnen, Horizontale</a></li>
        <li><a href="#maj_div">Lijst van illustraties</a></li>
        <li><a href="#lists">Lijsten</a></li>
        <li><a href="#play_n">Namen van Spelers</a></li>
        <li><a href="#line_no">Nummers in kantlijn</a></li>
        <li><a href="#line_no">Nummers, Regel-</a></li>
      </ul>
    </td>
    <td width="50%" valign="top">
      <ul>
        <li><a href="#maj_div">Onderdelen in Teksten, Belangrijke</a></li>
        <li><a href="#underl">Onderstreepte Tekst</a></li>
        <li><a href="#bad_image">Origineel, Slecht</a></li>
        <li><a href="#outofline">Out-of-Line Formattering Markering</a></li>
        <li><a href="#about">Over Dit Document</a></li>
        <li><a href="#blank_pg">Pagina, Lege</a></li>
        <li><a href="#sect_head">Paragraaftitels</a></li>
        <li><a href="#illust">Plaatjes</a></li>
        <li><a href="#inline">Plaatsing van Inline Formattering Markering</a></li>
        <li><a href="#outofline">Plaatsing van Out-of-Line Formattering Markering</a></li>
        <li><a href="#poetry">Po&euml;zie</a></li>
        <li><a href="#round1">Proefleesvergissingen, Eerder Gemaakte</a></li>
        <li><a href="#forums">Project Discussie</a></li>
        <li><a href="#r_align">Rechts-uitgelijnde Tekst</a></li>
        <li><a href="#line_no">Regelnummers</a></li>
        <li><a href="#play_n">Regieaanwijzingen (Toneel/Drama)</a></li>
        <li><a href="#extra_s">Ruimte Tussen Alinea's, Extra</a></li>
        <li><a href="#para_space">Ruimte Tussen Alinea's/Inspringingen</a></li>
        <li><a href="#spaced"><span style="letter-spacing: .2em;">Ruimte Tussen Letters in een Woord</span> (Uitgespatieerde Tekst)</a></li>
        <li><a href="#summary">Samenvatting van de Richtlijnen</a></li>
        <li><a href="#italics">Schuingedrukte Tekst</a></li>
        <li><a href="#para_side">Sidenotes</a></li>
        <li><a href="#bad_image">Slecht Beeld, Origineel</a></li>
        <li><a href="#extra_sp">Spaties, Extra</a></li>
        <li><a href="#p_errors">Spelfouten</a></li>
        <li><a href="#extra_s">Sterretjes Tussen Alinea's</a></li>
        <li><a href="#subscr">Subscript</a></li>
        <li><a href="#supers">Superscript</a></li>
        <li><a href="#tables">Tabellen</a></li>
        <li><a href="#extra_sp">Tabs</a></li>
        <li><a href="#font_ch">Tekst, Ander Lettertype, Grootte</a></li>
        <li><a href="#italics">Tekst, Cursieve/Schuingedrukte</a></li>
        <li><a href="#small_caps"><span style="font-variant: small-caps">Tekst in Kleine Hoofdlettertjes</span></a></li>
        <li><a href="#underl">Tekst, Onderstreepte</a></li>
        <li><a href="#r_align">Tekst, Rechts-uitgelijnde</a></li>
        <li><a href="#spaced">Tekst<span style="letter-spacing: .2em;">, Uitgespatieerde</span> (gesperrt)</a></li>
        <li><a href="#supers">Tekst, Verhoogde (Superscript)</a></li>
        <li><a href="#bad_text">Tekst, Verkeerd Beeld/Origineel voor de</a></li>
        <li><a href="#subscr">Tekst, Verlaagde (Subscript)</a></li>
        <li><a href="#bold">Tekst, Vet Gedrukte</a></li>
        <li><a href="#title_pg">Titelpagina aan de Voor- of Achterkant</a></li>
        <li><a href="#chap_head">Titels, Hoofdstuk-</a></li>
        <li><a href="#maj_div">Titels, Overige</a></li>
        <li><a href="#sect_head">Titels, Paragraaf-</a></li>
        <li><a href="#comments">Toelichting bij een Project</a></li>
        <li><a href="#play_n">Toneelstukken: Namen van Spelers/Regieaanwijzingen</a></li>
        <li><a href="#spaced"><span style="letter-spacing: .2em;">Uitgespatieerde Tekst</span> (gesperrt)</a></li>
        <li><a href="#font_sz">Verandering in Grootte van het Lettertype</a></li>
        <li><a href="#font_ch">Veranderingen van Lettertype</a></li>
        <li><a href="#prev_pg">Vergissingen op Voorgaande Pagina's, Het Herstellen van</a></li>
        <li><a href="#p_errors">Vergissingen van de Drukker/Spelfouten</a></li>
        <li><a href="#supers">Verhoogde Tekst (Superscript)</a></li>
        <li><a href="#bad_text">Verkeerd Beeld/Origineel voor de Tekst</a></li>
        <li><a href="#bad_text">Verkeerde Tekst</a></li>
        <li><a href="#subscr">Verlaagde Tekst (Subscript)</a></li>
        <li><a href="#page_ref">Verwijzingen naar Pagina's "Zie blz. 123"</a></li>
        <li><a href="#bold">Vet Gedrukte Tekst</a></li>
        <li><a href="#footnotes">Voetnoten</a></li>
        <li><a href="#prev_pg">Voorgaande Pagina's, Het Herstellen van Vergissingen op</a></li>
        <li><a href="#title_pg">Voorkant Titelpagina</a></li>
        <li><a href="#maj_div">Voorwoord</a></li>
        <li><a href="#word_caps">Woorden in Allemaal Hoofdletters</a></li>
        <li><a href="#small_caps">Woorden in <span style="font-variant: small-caps">Kleine Hoofdlettertjes (Klein Kapitaal)</span></a></li>
        <li><a href="#page_ref">"Zie blz. 123" (Verwijzingen naar Pagina's)</a></li>
      </ul>
    </td>
  </tr>
</table>

<br>
<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="silver" summary="Links">
  <tr>
    <td width="10">&nbsp;</td>
    <td width="100%" align="center"><font face="verdana, helvetica, sans-serif" size="1">
      Terug naar:
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
