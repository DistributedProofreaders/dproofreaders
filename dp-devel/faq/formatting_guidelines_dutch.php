<?

// Translated by PGDP Team Netherlands; file received from user Clog 3 Nov 2007

$relPath='../pinc/';
include($relPath.'site_vars.php');
include($relPath.'faq.inc');
include($relPath.'pg.inc');
include($relPath.'connect.inc');
include($relPath.'theme.inc');
new dbConnect();
$no_stats=1;
theme('Formatteer-Richtlijnen','header');

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

<h1 align="center">Formatteer-Richtlijnen</h1>

<h3 align="center">Versie 1.9.e, herzien July 19, 2007 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
  <a href="dochist.php"><font size="-1">(Herzieningsgeschiedenis)</font></a></h3>

<HR>
<h4>Dit document is een vertaling van de Engelse Formatting Guidelines.<BR>
    Bij elk hoofdstuk is een link opgenomen naar het corresponderende hoofdstuk in die Guidelines.</h4> 
<HR>

<h4>Formatteer-Richtlijnen <a href="document.php">in het Engels</a> /
      Formatting Guidelines <a href="document.php">in English</a><br />
    Formatteer-Richtlijnen <a href="formatting_guidelines_francaises.php">in het Frans</a> /
      Directives de Formatage <a href="formatting_guidelines_francaises.php">en fran&ccedil;ais</a><br />
    Formatteer-Richtlijnen <a href="formatting_guidelines_portuguese.php">in het Portuguees</a> /
      Regras de Formata&ccedil;&atilde;o <a href="formatting_guidelines_portuguese.php">em Portugu&ecirc;s</a><br />
    Formatteer-Richtlijnen <a href="formatting_guidelines_german.php">in het Duits</a> /
      Formatierungsrichtlinien <a href="formatting_guidelines_german.php">auf Deutsch</a><br />
</h4>

<h4>Bekijk de <a href="../quiz/start.php?show_only=FQ">Formatting Quiz</a>! (dit document bestaat alleen in een Engelse versie)</h4>

<table border="0" cellspacing="0" width="100%" summary="Formatting Guidelines">
  <tbody>
  <tr>
    <td width="1" bgcolor="silver">&nbsp;</td>
    <td bgcolor="silver" align="center"><font size="+2"><b>Inhoudsopgave</b></font></td>
  </tr>
  <tr>
    <td width="1" bgcolor="silver">&nbsp;</td>
    <td bgcolor="white" align="left">
    <ul>
      <li><a href="#prime">De Hoofdregel</a></li>
      <li><a href="#summary">Samenvatting van de Richtlijnen</a></li>
      <li><a href="#about">Over dit document</a></li>
      <li><a href="#comments">Toelichting bij een Project</a></li>
      <li><a href="#forums">Forum / Bediscussieer dit Project</a></li>
      <li><a href="#prev_pg">Het herstellen van vergissingen op voorgaande pagina's</a></li>
    </ul>
    </td>
  </tr>
  <tr>
    <td width="1" bgcolor="silver">&nbsp;</td>
    <td bgcolor="silver" align="left">
      <ul>
        <li><font size="+1">Het Formatteren van...</font></li>
      </ul>
    </td>
  </tr>
  <tr>
    <td width="1" bgcolor="silver">&nbsp;</td>
    <td bgcolor="white" align="left">
      <ul style="margin-left: 3em;">
        <li><a href="#title_pg">Titelpagina aan de voor- of achterkant</a></li>
        <li><a href="#toc">Inhoudsopgave</a></li>
        <li><a href="#blank_pg">Lege pagina</a></li>
        <li><a href="#page_hf">Koptekst en voettekst</a></li>
        <li><a href="#chap_head">Hoofdstuktitels</a></li>
        <li><a href="#sect_head">Paragraaftitels</a></li>
        <li><a href="#maj_div">Andere belangrijke onderdelen in teksten</a></li>
        <li><a href="#para_side">Beschrijvingen naast een Alinea</a></li>
        <li><a href="#para_space">Ruimte tussen Alinea's/Inspringingen</a></li>
        <li><a href="#mult_col">Meerdere kolommen</a></li>
        <li><a href="#illust">Illustraties</a></li>
        <li><a href="#footnotes">Voetnoten/Eindnotens</a></li>
        <li><a href="#italics">Cursief gedrukte tekst</a></li>
        <li><a href="#bold">Vet gedrukte tekst</a></li>
        <li><a href="#supers">Superscript</a></li>
        <li><a href="#subscr">Subscript</a></li>
        <li><a href="#underl">Onderstreepte Tekst</a></li>
        <li><a href="#spaced">U i t g e s p a t i e e r d e &nbsp; T e k s t</a></li>
        <li><a href="#font_ch">Verandering van Lettertype</a></li>
        <li><a href="#font_sz">Verandering in grootte van het lettertype</a></li>
        <li><a href="#word_caps">Woorden in HOOFDLETTERS</a></li>
        <li><a href="#small_caps">Woorden in <span style="font-variant: small-caps">kleine hoofdlettertjes (Klein Kapitaal)</span></a></li>
        <li><a href="#drop_caps">Grote, versierde hoofdletter aan het begin</a></li>
        <li><a href="#em_dashes">Liggend streepje: koppelteken, gedachtestreepje, minteken </a></li>
        <li><a href="#eol_hyphen">Woordafbreking aan het eind van een regel </a></li>
        <li><a href="#eop_hyphen">Woordafbreking aan het eind van een bladzijde</a></li>
        <li><a href="#next_word">Losstaand woord onderaan een pagina</a></li>
        <li><a href="#contract">Samentrekkingen</a></li>
        <li><a href="#poetry">Po&euml;zie/Epigrammen</a></li>
        <li><a href="#letter">Brieven/Correspondentiee</a></li>
        <li><a href="#lists">Lijsten</a></li>
        <li><a href="#tables">Tabellen</a></li>
        <li><a href="#block_qt">Citaten</a></li>
        <li><a href="#double_q">Dubbele aanhalingstekens</a></li>
        <li><a href="#single_q">Enkele aanhalingstekens</a></li>
        <li><a href="#quote_ea">Aanhalingstekens op iedere regel</a></li>
        <li><a href="#period_s">Punten aan het eind van een zin</a></li>
        <li><a href="#punctuat">Interpunctie</a></li>
        <li><a href="#line_br">Regelafbrekingen</a></li>
        <li><a href="#extra_sp">Extra spaties of tabs tussen woorden</a></li>
        <li><a href="#trail_s">Spaties na het eind van een regel</a></li>
        <li><a href="#line_no">Regelnummers</a></li>
        <li><a href="#extra_s">Extra lege regels/Sterretjes/Lijn tussen Alinea's</a></li>
        <li><a href="#period_p">Beletselteken ofwel ellips &quot;&hellip;&quot;</a></li>
        <li><a href="#a_chars">Tekens met accenten/Non ASCII tekens</a></li>
        <li><a href="#d_chars">Lettertekens met diakritische tekens.</a></li>
        <li><a href="#f_chars">Niet-Latijnse lettertekens</a></li>
        <li><a href="#fract_s">Breuken</a></li>
        <li><a href="#page_ref">Verwijzingen naar pagina's &quot;Zie blz. 123&quot;</a></li>
        <li><a href="#bk_index">Indexen</a></li>
        <li><a href="#play_n">Toneelstukken: Namen van Spelers/Regieaanwijzingen</a></li>
        <li><a href="#anything">Alles wat op een speciale manier aangepakt moet worden, of waar je onzeker over bent</a></li>
        <li><a href="#prev_notes">Aantekeningen/Commentaar van eerdere Proeflezers/Formatteerders</a></li>
      </ul>
    </td>
  </tr>
  <tr>
    <td width="1" bgcolor="silver">&nbsp;</td>
    <td bgcolor="silver" align="left">
    <ul>
      <li><font size="+1">Speciale richtlijnen voor speciale boeken</font></li>
    </ul>
    </td>
  </tr>
  <tr>
    <td width="1" bgcolor="silver">&nbsp;</td>
    <td bgcolor="white" align="left">
      <ul style="margin-left: 3em;">
        <li><a href="#sp_ency">Encyclopedias</a>     (dit document bestaat alleen in een Engelse versie)</li>
        <li><a href="#sp_poet">Poetry Books</a></b>  (dit document bestaat alleen in een Engelse versie)</li>
        <li><a href="#sp_chem">Chemistry Books</a>   [moet nog gemaakt worden.]</li>
        <li><a href="#sp_math">Mathematics Books</a> [moet nog gemaakt worden.]</li>
      </ul>
    </td>
  </tr>
  <tr>
    <td width="1" bgcolor="silver">&nbsp;</td>
    <td bgcolor="silver" align="left">
    <ul>
      <li><font size="+1">Veel voorkomende problemen</font></li>
    </ul>
    </td>
  </tr>
  <tr>
    <td width="1" bgcolor="silver">&nbsp;</td>
    <td bgcolor="white" align="left">
      <ul style="margin-left: 3em;">
        <li><a href="#OCR_1lI">Problemen met de OCR: 1-l-I</a></li>
        <li><a href="#OCR_0O">Problemen met de OCR: 0-O</a></li>
        <li><a href="#OCR_hyphen">Problemen met de OCR: Afbreekstreepjes en andere streepjes</a></li>
        <li><a href="#OCR_scanno">Problemen met de OCR: Scanno's</a></li>
        <li><a href="#hand_notes">Handgeschreven aantekeningen in een boek</a></li>
        <li><a href="#bad_image">Slecht beeld</a></li>
        <li><a href="#bad_text">Verkeerd beeld voor de tekst</a></li>
        <li><a href="#round1">Eerder gemaakte Proeflees- en Formatteervergissingen</a></li>
        <li><a href="#p_errors">Vergissingen van de drukker/Spelfouten</a></li>
        <li><a href="#f_errors">Feitelijke fouten in de tekst</a></li>
        <li><a href="#uncertain">Onzekere items</a></li>
      </ul>
    </td>
  </tr>
  <tr>
    <td width="1" bgcolor="silver">&nbsp;</td>
    <td bgcolor="silver">&nbsp;</td>
  </tr>
 </tbody>
</table>

<h3><a name="prime">De Hoofdregel</a>
 (<i><a href="document.php#prime">The Primary Rule</a></i>)</h3>
<p><em>"Verander niet wat de schrijver heeft geschreven!"</em>
</p>
<p>Het elektronische boek zoals de lezer het uiteindelijk zal zien, mogelijk vele jaren later, moet de bedoeling van 
   de schrijver precies weergeven. Als de schrijver woorden op een vreemde manier spelde, dan laten we die spelling staan. 
   Als de schrijver buitensporig racistische of bevooroordeelde uitspraken deed, dan laten we die staan. 
   Als de schrijver elk derde woord cursief of vet maakte, of elk derde woord van een voetnoot voorzag, 
   dan markeren we elk derde woord als cursief, of vet, of voorzien het van een voetnoot. 
   (Zie <a href="#p_errors">Vergissingen van de drukker</a> voor de manier waarop overduidelijke 
   vergissingen van de drukker behandeld moeten worden.)
</p>
<p>Kleine, typografisch ge&iuml;nspireerde ingrepen in de tekst, die de betekenis van wat de schrijver schreef, niet aantasten, 
   veranderen we wel. Bijvoorbeeld: woorden die aan het eind van een regel zijn afgebroken, plakken we weer aan elkaar 
   (<a href="#eol_hyphen" title="">Woordafbreking aan het eind van een regel</a>).
   Dit soort veranderingen helpen ons om een <em>consistent geformatteerde</em> versie van het boek te produceren. 
   Lees de rest van deze Formatteer-Richtlijnen alsjeblieft zorgvuldig, met dit concept in je achterhoofd.
</p>
<p>Om de volgende formatteerder en de Post-Processor te ondersteunen, handhaven we de 
   <a href="#line_br">regelafbrekingen</a>.
   Dit helpt hen om de regels in de tekst te vergelijken met de regels in het origineel.
</p>
<!-- END RR -->

<table width="100%" border="0" cellspacing="0" summary="Samenvatting van de richtlijnen">
  <tbody>
    <tr>
      <td bgcolor="silver">&nbsp;</td>
    </tr>
  </tbody>
</table>

<h3><a name="summary">Samenvatting van de richtlijnen</a>
 (<i><a href="document.php#summary">Summary Guidelines</a></i>)</h3>
<p>De <a href="formatting_summary.pdf">Formatting Summary</a> (dit document bestaat alleen in een Engelse versie)
   is een kort printer-vriendelijk (.pdf) document van 2 pagina's, dat de voornaamste punten van deze richtlijnen samenvat,
   en voorbeelden geeft hoe te formatteren.
   Beginnende formatteerders wordt aangeraden dit document uit te printen en bij de hand te houden bij het formatteren.
</p>
<p>Misschien moet je een .pdf lezer downloaden en installeren. Je kunt er 
   <a href="http://www.adobe.com/products/acrobat/readstep2.html">hier</a> gratis &eacute;&eacute;n van Adobe&reg; krijgen.
</p>

<h3><a name="about">Over dit document</a>
 (<i><a href="document.php#about">About This Document</a></i>)</h3>
<p>Dit document is geschreven om de formatteer-regels uit te leggen. We gebruiken deze regels om consistentie
   te waarborgen, aangezien het formatteren van &eacute;&eacute;n enkel boek verdeeld is over vele formatteerders, die 
   allemaal aan verschillende pagina's werken. De regels helpen ons om allemaal <em>op dezelfde manier</em>
   te formatteren, wat het vervolgens voor de Post-Processor gemakkelijker maakt om al deze pagina's tot 
   &eacute;&eacute;n e-boek samen te voegen.
</p>
<p><i>Dit document is niet bedoeld als een algemeen handboek voor het redigeren of zetten van boeken.</i>
</p>
<p>We hebben in dit document alles behandeld waar nieuwe gebruikers vragen over gesteld hebben. 
   Als er iets ontbreekt, of als je denkt dat iets op een andere manier behandeld zou moeten worden, 
   of als iets vaag is, laat het ons alsjeblieft weten. 
</p>
<p>Dit document is in ontwikkeling. Help ons alsjeblieft bij de ontwikkeling van de richtlijnen, door 
   veranderingen die je zou willen voorstellen, te <i>posten</i> in 
   <a href="<? echo $Guideline_discussion_URL; ?>">deze discussie</a> in het Documentation Forum.
</p>

<h3><a name="comments">Toelichting bij een project</a>
 (<i><a href="document.php#comments">Project Comments</a></i>)</h3>
<p>Op de Project Pagina waar je begint met het formatteren van pagina's, vind je een gedeelte dat
   "Project Comments" (Toelichting bij het Project) heet. Hier staat informatie die specifiek is 
   voor het betreffende project (boek). <b>Lees dit voor je begint met het formatteren van pagina's!</b> 
   Als de Project Manager wil dat iets op een andere manier geformatteerd wordt dan in deze richtlijnen staat, 
   dan staat dat hier. Instructies in de Project Comments <em>geven de doorslag, boven de regels in deze richtlijnen</em>, 
   dus volg ze alsjeblieft op. (Dit is trouwens ook de plaats waar de Project Manager wel eens interessante 
   informatie geeft over de schrijver of over het project.)
</p>
<p><em>Lees alsjeblieft ook de Project Discussie</em>: Hier legt de Project Manager soms project-specifieke 
   richtlijnen uit. Ook wordt deze discussie regelmatig gebruikt door vrijwilligers om andere vrijwilligers te wijzen
   op vaak terugkerende problemen in het project, en hoe deze het beste kunnen worden aangepakt. (Zie hieronder)
</p>
<p>Op de Project Pagina staat een link 'Images, Pages Proofread, &amp; Differences'. 
   Daar kun je zien hoe andere vrijwilligers veranderingen hebben aangebracht. 
   <a href="<? echo $Using_project_details_URL ?>">Deze Forumdiscussie</a> 
   bespreekt verschillende manieren om deze informatie te gebruiken. 
</p>

<h3><a name="forums">Forum/Bediscussieer dit Project</a>
 (<i><a href="document.php#forums">Forum/Discuss this Project</a></i>)</h3>
<p>Op de Project Pagina, waar je met het formatteren van pagina's begint, is op de regel "Forum" een link 
   met de naam: "Discuss this Project" (als de discussie al begonnen is), of: "Start a discussion on this Project" 
   (als de discussie nog niet begonnen is.) Klik op deze link en je komt op een <i>discussie</i> in het projectenforum, 
   speciaal voor dit project. Hier kun je vragen over dit boek stellen, hier kun je de Project Manager informeren 
   over problemen met de tekst enz. Posten in deze Project Discussie is de aanbevolen manier om met de Project Manager 
   en andere vrijwilligers die aan het boek werken, te communiceren.
</p>

<h3><a name="prev_pg">Het herstellen van vergissingen op voorgaande pagina's</a>
 (<i><a href="document.php#prev_pg">Fixing errors on Previous Pages</a></i>)</h3>
<p>Als je een project uitkiest om te formatteren, wordt de <a href="#comments">Project Pagina</a> geladen. 
   Deze pagina heeft links naar bladzijden van dit project waar je recent aan gewerkt hebt.
   (Als je nog geen pagina's van dit project geformatteerd hebt, zijn er ook geen links.)
</p>
<p>Pagina's onder &oacute;f "DONE" &oacute;f "IN PROGRESS" zijn beschikbaar om te verbeteren of om af te maken. 
   Klik op de link van de pagina. Als je ontdekt dat je een vergissing op een pagina gemaakt hebt, of dat je 
   iets onjuist gemarkeerd hebt, kun je hier op de link klikken en de pagina openen om de vergissing te herstellen.
</p>
<p>Je kunt ook de "Images, Pages Proofread, &amp; Differences" of "Just My Pages" link op de 
   <a href="#comments">Project Pagina</a> gebruiken. Deze pagina's hebben een "Edit" link naast de pagina's 
   waar je in de huidige ronde aan gewerkt hebt en die nog verbeterd kunnen worden.
</p>
<p>Voor gedetailleerder informatie, zie &oacute;f de <a href="prooffacehelp.php?i_type=0">Standard
   Proofreading Interface Help</a> &oacute;f de <a href="prooffacehelp.php?i_type=1">Enhanced Proofreading
   Interface Help</a> (deze documenten bestaan alleen in een Engelse versie), afhankelijk van welke interface je gebruikt.
</p>
<!-- END RR -->
<table width="100%" border="0" cellspacing="0" cellpadding="6" summary="Title Page">
  <tbody>
    <tr>
      <td bgcolor="silver"><font size="+2">Het Formatteren van...</font></td>
    </tr>
  </tbody>
</table>

<h3><a name="title_pg">Titelpagina aan de voor- of achterkant</a>
 (<i><a href="document.php#title_pg">Front/Back Title Page</a></i>)</h3>
<p>Formatteer alle tekst, inclusief het jaar waarin het boek is uitgegeven of het jaar van het copyright, 
   precies zoals het op de pagina's gedrukt is, hoofdletters, kleine letters, enz. 
</p>
<p>In oudere boeken wordt de eerste letter vaak groot en bewerkt weergegeven, formatteer deze letter gewoon als de letter.
</p>
<!-- END RR -->

<table width="100%" align="center" border="1" cellpadding="4"
 cellspacing="0" summary="Title Page Example">
  <tbody>
    <tr>
      <th align="left" bgcolor="cornsilk">Origineel:</th>
    </tr>
    <tr align="left">
      <td width="100%" valign="top"><img src="title.png" width="500"
          height="520" alt="title page image"><br>
      </td>
    </tr>
    <tr>
      <th align="left" bgcolor="cornsilk">Correct geformatteerde tekst:</th>
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

<h3><a name="toc">Inhoudsopgave</a>
 (<i><a href="document.php#toc">Table of Contents</a></i>)</h3>
<p>Formatteer de inhoudsopgave precies zoals deze in het boek gedrukt staat, hoofdletters, kleine letters enz., 
   en zet er <tt>/*</tt> en <tt>*/</tt> achter. Sla een regel over tussen deze markeringen en de rest van de tekst. 
   Verwijzingen naar paginanummers moeten behouden blijven. 
   Zet minstens zes spaties tussen het eind van de regel en het paginanummer.
</p>
<p>Verwijder alle punten of sterretjes die gebruikt zijn om de paginanummers op &eacute;&eacute;n lijn te krijgen.
</p>
<!-- END RR -->

<table width="100%" align="center" border="1" cellpadding="4"
 cellspacing="0" summary="TOC">
  <tbody>
    <tr>
      <th align="left" bgcolor="cornsilk">Origineel:</th>
    </tr>
    <tr align="left">
      <td width="100%" valign="top">
      <p><img src="tablec.png" alt="" width="500" height="650"></p>
      </td>
    </tr>
    <tr>
      <th align="left" bgcolor="cornsilk">Correct geformatteerde tekst:</th>
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
      </tt>
</td></tr></table>
      </td>
    </tr>
  </tbody>
</table>

<h3><a name="blank_pg">Lege Pagina</a>
 (<i><a href="document.php#blank_pg">Blank Page</a></i>)</h3>
<p>Formatteer als <tt>[Blank Page]</tt> als zowel de tekst als het origineel leeg zijn. 
</p>
<p>Als er wel tekst is, waar de te formatteren tekst hoort te staan, maar niet in het origineel, 
   of als er wel iets in het origineel staat maar er is geen tekst, volg dan de aanwijzingen voor een
   <a href="#bad_image">Slecht Beeld</a> (Bad Image) of een <a href="#bad_text">Verkeerd beeld voor de tekst</a> (Bad Text).
</p>

<h3><a name="page_hf">Koptekst en voettekst</a>
 (<i><a href="document.php#page_hf">Page Headers/Page Footers</a></i>)</h3>
<p>Verwijder alle koptekst en voettekst, maar <em>niet</em> de <a href="#footnotes">voetnoten</a>.
</p>
<p>De koptekst staat gewoonlijk bovenaan het origineel. Er staat vaak een paginanummer op dezelfde regel. 
   Koptekst kan het hele boek door hetzelfde zijn. Dan is het vaak de titel van het boek. Of hij is per 
   hoofdstuk hetzelfde, vaak het nummer van het hoofdstuk. Of de koptekst is op elke pagina anders, 
   dan wordt beschreven wat er op de pagina gebeurt. Verwijder alle koptekst, inclusief het paginanummer.
</p>
<!-- END RR -->

<p>De <a href="#chap_head">titel van een hoofdstuk</a> (chapter header) begint lager op de pagina en 
   heeft geen paginanummer op dezelfde regel. Zie het volgende deel van deze richtlijnen voor een voorbeeld.
</p>
<br>
<table width="100%" align="center" border="1" cellpadding="4"
 cellspacing="0" summary="Page Headers and Footers">
  <tbody>
    <tr><th align="left" bgcolor="cornsilk">Origineel:</th></tr>
    <tr align="left">
      <td width="100%" valign="top">
      <img src="foot.png" alt="" width="500" height="850"><br>
      </td>
    </tr>
    <tr><th align="left" bgcolor="cornsilk">Correct geformatteerde tekst:</th></tr>
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

<h3><a name="chap_head">Hoofdstuktitels</a>
 (<i><a href="document.php#chap_head">Chapter Headers</a></i>)</h3>
<p>Formatteer een hoofdstuktitel zoals hij in de tekst staat.
</p>
<p>Een hoofdstuktitel begint vaak wat lager op de pagina dan de <a href="#page_hf">koptekst</a>.
   Er staat geen paginanummer op dezelfde regel. Hoofdstuktitels worden vaak helemaal in hoofdletters gedrukt. 
   Dan handhaaf je de hoofdletters. Hoofdstuktitels worden meestal in een ander, of groter lettertype gedrukt, 
   waardoor ze vet of uitgespatieerd lijken. Toch markeren we ze niet als vet of uitgespatieerd. 
   Als er in de hoofdstuktitel cursief of klein kapitaal staat, moet dat wel gemarkeerd worden.
</p>
<p>Voeg vier lege regels in v&oacute;&oacute;r "CHAPTER XXX", ook als het hoofdstuk op een nieuwe pagina begint. 
   De lege regels zijn nodig, omdat een e-boek geen pagina's heeft. Voeg vervolgens &eacute;&eacute;n lege regel in 
   tussen de onderdelen van de hoofdstuktitel, zoals een beschrijving van het hoofdstuk, een citaat, enz. 
   Tot slot twee lege regels voordat de tekst van het hoofdstuk begint.
</p>
<p>In oude boeken staat het eerste woord of de eerste twee woorden van elk hoofdstuk vaak in hoofdletters 
   of klein kapitaal. Verander dit: laat alleen de eerste letter van het eerste woord als hoofdletter 
   staan en maak van de rest kleine letters.
</p>
<p>Let op: de aanhalingstekens aan het begin van de eerste alinea ontbreken nog wel eens, &oacute;f doordat de 
   uitgever ze er niet bij zette, &oacute;f doordat de OCR ze miste omdat de eerste letter in het origineel een 
   extra grote hoofdletter is. Als de schrijver de alinea met dialoog begon, voeg dan de aanhalingstekens toe.
</p>
<!-- END RR -->

<table width="100%" align="center" border="1" cellpadding="4"
 cellspacing="0" summary="Chapters">
 <tbody>
    <tr><th align="left" bgcolor="cornsilk">Origineel:</th></tr>
    <tr align="left">
      <td width="100%" valign="top"> <img src="chap1.png" alt=""
          width="500" height="725"><br>
      </td>
    </tr>
    <tr><th align="left" bgcolor="cornsilk">Correct geformatteerde tekst:</th></tr>
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

<h3><a name="sect_head">Paragraaftitels</a>
 (<i><a href="document.php#sect_head">Section Headers</a></i>)</h3>
<p>Bij sommige teksten zijn de hoofdstukken onderverdeeld in paragrafen. Formatteer deze titels zoals ze in de tekst staan. 
   Voeg twee lege regels in v&oacute;&oacute;r de titel en &eacute;&eacute;n lege regel na de titel, tenzij de Project Manager iets anders vraagt. 
   Als je er niet zeker van bent of een titel van een hoofdstuk of van een paragraaf is, vraag het dan in de Project Discussie, 
   onder vermelding van het nummer van de pagina. Paragraaftitels worden vaak gedrukt in een ander, of groter, 
   lettertype waardoor ze vet of uitgespatieerd lijken, maar we markeren ze niet als een ander lettertype, of vet, 
   of uitgespatieerd. Als er in de paragraaftitel cursief of klein kapitaal staat, moet dat wel gemarkeerd worden.
</p>

<h3><a name="maj_div">Andere belangrijke onderdelen in teksten</a>
 (<i><a href="document.php#maj_div">Other Major Divisions in Texts</a></i>)</h3>
<p>Belangrijke onderdelen zoals Inleiding, Voorwoord, Inhoudsopgave, Introductie, Proloog, Epiloog, Appendix, 
   Verwijzingen, Conclusie, Woordenlijst, Samenvatting, Dankbetuigingen, Bibliografie, enz., moeten op dezelfde 
   manier geformatteerd worden als Hoofdstuktitels, dus 4 lege regels voor de titel en 2 lege regels voor de rest van de tekst. 
</p>

<h3><a name="para_side">Beschrijvingen naast een Alinea (Sidenotes)</a>
 (<i><a href="document.php#para_side">Paragraph Side-Descriptions (Sidenotes)</a></i>)</h3>
<p>Sommige boeken hebben korte beschrijvingen van de alinea naast de tekst. Deze beschrijvingen worden sidenotes genoemd. 
   Verplaats de sidenotes naar vlak boven de alinea waar ze bij horen. Een sidenote moet omgeven worden 
   door een sidenote markering: <tt>[Sidenote:&nbsp;</tt> en <tt>]</tt>, met de tekst van de sidenote er tussenin. 
   Formatteer de tekst van de sidenote zoals hij gedrukt is, en handhaaf regelafbrekingen, cursief enz. 
   Laat een lege regel voor en na de sidenote, zodat hij apart blijft van de alinea als de tekst wordt 
   <span style="border-bottom: 1px dotted green;"  title="rewrap: het opnieuw aanbrengen van regelafbrekingen"><i>gerewrapped</i></span>
   tijdens het post-processen. 
</p>
<p>Als er meerdere sidenotes bij &eacute;&eacute;n alinea zijn, zet ze dan achter elkaar v&oacute;&oacute;r de alinea. 
   Laat een lege regel tussen de verschillende sidenotes. 
</p>
<p>Als de alinea al op de vorige pagina begonnen is, zet dan de sidenote bovenaan de pagina en 
   markeer hem met een sterretje (<tt>*</tt>), zodat de Post-Processor kan zien dat de sidenote op de vorige pagina hoort. 
   Doe het zo: <tt>*[Sidenote: <font color="red">(tekst van de sidenote)</font>]</tt>.
   De Post-Processor zal de sidenote verplaatsen naar waar hij hoort.
</p>
<p>Soms zal een Project Manager vragen, of je de sidenotes naast de zin waar ze bij horen, 
   wil plaatsen, in plaats van boven of onder de alinea. 
   In dit geval zet je geen lege regels voor of na de sidenotes.
</p>
<!-- END RR -->

  <table width="100%" align="center" border="1" cellpadding="4"
       cellspacing="0" summary="Sidenotes"> <col width="128*">
  <tbody>
    <tr valign="top">
      <th align="left" bgcolor="cornsilk">Origineel:</th>
    </tr>
    <tr valign="top">
      <td width="100%" align="left"><img src="side.png" alt=""
          width="550" height="800"><br>
      </td>
    </tr>
    <tr valign="top">
      <th align="left" bgcolor="cornsilk">Correct geformatteerde tekst:</th>
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

<h3><a name="para_space">Ruimte tussen Alinea's/Inspringingen</a>
 (<i><a href="document.php#para_space">Paragraph Spacing/Indenting</a></i>)</h3>
<p>Zet een lege regel voor het begin van een alinea, zelfs als de alinea bovenaan een bladzijde begint. 
   Aan het begin van een alinea hoef je niet in te springen, maar als een alinea al ingesprongen is, 
   hoef je die spaties niet te verwijderen&mdash;dat gebeurt automatisch tijdens het post-processen.
</p>
<p>Zie het voorbeeld bij de <a href="#chap_head">Hoofdstuktitels</a>.
</p>

<h3><a name="mult_col">Meerdere kolommen</a>
 (<i><a href="document.php#mult_col">Multiple Columns</a></i>)</h3>
<p>Formatteer gewone tekst die in 2 kolommen gedrukt is, als een enkele kolom.
</p>
<p>Stukjes met meerdere kolommen binnen een paragraaf van &eacute;&eacute;n kolom, moeten als &eacute;&eacute;n kolom geformatteerd worden, 
   door de tekst van de linker kolom eerst te zetten, de tekst van de volgende eronder enzovoort. 
   Je hoeft niet te markeren waar de kolommen gesplitst waren, je kunt ze gewoon achter elkaar zetten.
</p>
<p>Als de kolommen lijsten zijn, markeer dan het begin van de lijst met <tt>/*</tt> en het einde met <tt>*/</tt>, 
   zodat de regels tijdens het post-processen niet <span style="border-bottom: 1px dotted green;"
    title="rewrap: het opnieuw aanbrengen van regelafbrekingen"><i>gerewrapped</i></span> worden.
   Laat een lege regel achter tussen deze markeringen en de rest van de tekst.
</p>
<p>Zie ook de onderdelen <a href="#bk_index">Indexen</a>, <a href="#lists">Lijsten</a> en 
   <a href="#tables">Tabellen</a> van deze richtlijnen.
</p>

<h3><a name="illust">Illustraties</a>
 (<i><a href="document.php#illust">Illustrations</a></i>)</h3>
<p>Tekst bij een illustratie moet omgeven worden door een illustratiemarkering: 
   <tt>[Illustration:&nbsp;</tt> en <tt>]</tt>, met de tekst ertussen. 
   Formatteer de tekst zoals hij gedrukt is, handhaaf regelafbrekingen, cursief, enz. 
</p>
<p>Als een illustratie geen bijschrift heeft, voeg dan alleen een markering toe: <tt>[Illustration]</tt>.
</p>
<p>Als de illustratie midden in of naast een alinea staat, verplaats dan de illustratiemarkering 
   naar voor of na de alinea, omgeven door lege regels om ze apart te houden. 
   Verwijder evt. lege regels in de alinea.
</p>
<p>Als er op de pagina geen alinea begint of eindigt, markeer de illustratiemarkering dan met een 
   <tt>*</tt>, zo: <tt>*[Illustration:&nbsp;<font color="red">(tekst bijschrift)</font>]</tt> en verplaats 
   de markering naar de bovenkant van de bladzijde. Laat een lege regel over na de markering.
</p>
<!-- END RR -->

<table width="100%" align="center" border="1" cellpadding="4"
 cellspacing="0" summary="Illustration">
  <tbody>
    <tr>
      <th align="left" bgcolor="cornsilk">Origineel:</th>
    </tr>
    <tr align="left">
      <td width="100%" valign="top"> <img src="illust.png" alt=""
          width="500" height="525"> <br>
      </td>
    </tr>
    <tr>
      <th align="left" bgcolor="cornsilk">Correct geformatteerde tekst:</th>
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
     <th align="left" bgcolor="cornsilk">Origineel: (Illustratie in het midden van de alinea)</th>
   </tr>
   <tr align="left">
     <td width="100%" valign="top"> <img src="illust2.png" alt=""
         width="500" height="514"> <br>
     </td>
   </tr>
   <tr>
     <th align="left" bgcolor="cornsilk">Correct geformatteerde tekst:</th>
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

<h3><a name="footnotes">Voetnoten/Eindnoten</a>
 (<i><a href="document.php#footnotes">Footnotes/Endnotes</a></i>)</h3>
<p>Voetnoten worden onderaan de bladzijde geplaatst, en op de plaats in de tekst, waar naar de voetnoot 
   verwezen wordt, wordt een markering geplaatst. 
</p>
<p>Tijdens het formatteren houdt dit in:
</p>
<p>1. Het cijfer, de letter of het andere teken dat de plaats van de voetnoot  markeert, 
   moet tussen vierkante haken gezet worden  (<tt>[</tt> en <tt>]</tt>) en meteen na het woord waar 
   de voetnoot bij hoort, gezet worden<tt>[1]</tt>. Als er interpunctie naast het woord staat, 
   dan komt het symbool dat de voetnoot markeert, meteen naast de interpunctie.<tt>[2]</tt> 
   Zie de voorbeelden hieronder, en ook in deze tekst.
</p>
<p>Als voetnoten gemarkeerd zijn door een serie bijzondere tekens (*, &dagger;, &Dagger;, &sect;,
   etc.) dan vervangen we deze door hoofdletters op volgorde van het alfabet (A, B, C, enz.).
</p>
<p>2. Een voetnoot moet door een voetnootmarkering omgeven worden: <tt>[Footnote #:&nbsp;</tt> en <tt>]</tt>, 
   met de voetnoot ertussen. Het cijfer of de letter van de voetnoot komt op de plaats van #. 
   Formatteer de voetnoot zoals hij gedrukt is, en behoud regelafbrekingen, cursief enz. 
   De voetnoot moet onder aan de bladzijde komen te staan. Zorg er wel voor, dat je dezelfde markering 
   gebruikt voor de voetnoot, als wat er in de tekst staat waar de voetnoot naar verwijst. 
   Als er meer dan &eacute;&eacute;n voetnoot is, moet er een lege regel tussen de voetnoten gezet worden.
</p>
<!-- END RR -->

<p>In sommige boeken kan de Project Manager je vragen om de voetnoten <i>in-line</i> te zetten. 
   In dat geval staan de instructies in de <a href="#comments">Project Comments</a>.
</p>
<p>Zie <a href="#page_hf">Koptekst/voettekst</a> origineel en tekst voor een voorbeeld van een voetnoot. 
</p>
<p>Als er een voetnoot onder aan de pagina staat, maar er staat geen voetnoot anker in de tekst, 
   dan is dit waarschijnlijk het vervolg van een voetnoot op de vorige bladzijde, zeker als de voetnoot 
   midden in een zin of zelfs midden in een woord begint. Laat de voetnoot onder aan de bladzijde staan, 
   bij de andere voetnoten. Zet er om heen: <tt>*[Footnote: <font color="red">(tekst van de voetnoot)</font>]</tt>.
   Gebruik geen nummer of andere markering. Het sterretje <tt>*</tt> geeft aan dat deze voetnoot het vervolg is van een 
   voetnoot op de vorige bladzijde, en zorgt dat de Post-Processor weet dat hier een incomplete voetnoot staat.
</p>
<p>Als de voetnoot op de volgende bladzijde doorgaat (dus als de pagina stopt voor de voetnoot stopt), laat de 
   voetnoot dan onder aan de bladzijde staan, en zet een sterretje <tt>*</tt> aan het eind van de voetnoot, zo: 
   <tt>[Footnote: <font color="red">(tekst van de voetnoot)</font>]*</tt>. Het sterretje <tt>*</tt> geeft aan dat de 
   voetnoot voortijdig ge&euml;indigd is, en zorgt dat de Post-Processor er op let. De Post-Processor zal de voetnoot samenvoegen.
</p>
<p>Als een voetnoot begint of eindigt met een afgebroken woord, markeer dan <b>zowel</b> de voetnoot 
   <b>als</b> het afgebroken woord met <tt>*</tt>, zo:<br>
   <tt>[Footnote 1: Deze voetnoot gaat nog door en het laatste woord erin is afge-*]*</tt><br>
   voor het eerste deel, en<br>
   <tt>*[Footnote: *broken, en loopt door op de volgende pagina.]</tt>.
</p>
<p>Als er in de tekst naar een voetnoot of eindnoot verwezen wordt, maar de noot staat niet op dezelfde bladzijde, 
   laat dan het nummer of het symbool waarmee de noot gemarkeerd wordt, staan en zet er vierkante haken omheen:
   <tt>[</tt> en <tt>]</tt>. Dit gebeurt regelmatig in wetenschappelijke en technische boeken, 
   waar voetnoten vaak gegroepeerd staan aan het eind van elk hoofdstuk. Zie: "Eindnoten", hieronder.
</p>

<table width="100%" border="1"  cellpadding="4" cellspacing="0" align="center" summary="Footnote Examples">
  <tbody>
    <tr>
      <th valign="top" align="left" bgcolor="cornsilk">Origineel:</th>
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
      <th valign="top" align="left" bgcolor="cornsilk">Correct geformatteerde tekst met <i>out-of-line</i> voetnoten:</th>
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

<p>In sommige boeken worden de voetnoten door een horizontale lijn apart gezet van de tekst.
   We handhaven deze lijn niet, dus laat alsjeblieft alleen een lege regel over tussen de tekst en 
   de voetnoten. (Zie het voorbeeld hierboven.)
</p>
<p><b>Eindnoten</b> zijn voetnoten die samen aan het eind van een hoofdstuk of aan het eind van een boek 
   staan. Ze worden op dezelfde manier geformatteerd als voetnoten. 
   Als je ergens in de tekst een verwijzing naar een eindnoot vindt, zet er <tt>[</tt> en <tt>]</tt> omheen. 
   Als je een van de laatste pagina's aan het formatteren bent, waar al de eindnoten staan, zet dan 
   <tt>[Footnote #:&nbsp;<font color="red">(tekst van de eindnoot)</font>]</tt>, met de tekst van de eindnoot ertussen. 
   Het cijfer of de letter van de voetnoot komt op de plaats van #. Voeg na elke noot een lege regel in, 
   zodat ze aparte alinea's blijven als de tekst wordt <span style="border-bottom: 1px dotted green;"
    title="rewrap: het opnieuw aanbrengen van regelafbrekingen"><i>gerewrapped</i></span> tijdens het post-processen.
</p>
<!-- Need an example of Endnotes, maybe? Good idea!-->

<p><b>Voetnoten in <a href="#poetry">po&euml;zie</a> of in <a href="#tables">tabellen</a></b> moeten 
   op dezelfde manier behandeld worden als andere voetnoten. De vrijwilligers moeten ze markeren en 
   onder aan de bladzijde zetten; de Post-Processor besluit uiteindelijk waar ze terecht komen.
</p>

<table width="100%" align="center" border="1" cellpadding="4" cellspacing="0" summary="Footnotes">
  <tbody>
    <tr><th align="left" bgcolor="cornsilk">Origineel, Po&euml;zie met voetnoot:</th></tr>
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
    <tr><th align="left" bgcolor="cornsilk">Correct geformatteerde tekst:</th></tr>
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

<h3><a name="italics">Cursief gedrukte tekst</a>
 (<i><a href="document.php#italics">Italics</a></i>)</h3>
<p>Formatteer <i>cursief gedrukte</i> tekst met <tt>&lt;i&gt;</tt> aan het begin en <tt>&lt;/i&gt;</tt> aan het eind.
  (Let op de "/" in de laatste markering.)
</p>
<p>Interpunctie blijft <b>buiten</b> de markering, tenzij een hele zin of een hele alinea cursief is, 
   of als de interpunctie zelf onderdeel is van een cursief gedrukte frase, titel of afkorting. 
</p>
<p>De punten die een afgekort woord in de titel van een tijdschrift aangeven, zoals <i>Phil. Trans.</i>, 
   horen bij de titel en worden daarom opgenomen binnen de markering: <tt>&lt;i&gt;Phil. Trans.&lt;/i&gt;</tt>.
</p>
<p>Data en dergelijke: formatteer de <b>hele</b> frase als cursief, en n&iacute;et de woorden wel cursief en de 
   getallen niet cursief. Dit omdat veel lettertypen die je in oudere teksten vindt, dezelfde getallen gebruiken 
   voor zowel de gewone tekst als de cursief gedrukte tekst.
</p>
<p>Als de cursieve tekst uit een serie of een lijst van worden of namen bestaat, markeer deze dan allemaal apart.</p>
<!-- END RR -->

<p><b>Voorbeelden</b>&mdash;Cursief:
</p>

<table width="100%" align="center" border="1" cellpadding="4" cellspacing="0" summary="Italics">
  <tbody>
    <tr>
      <th valign="top" bgcolor="cornsilk">Origineel:</th>
      <th valign="top" bgcolor="cornsilk">Correct geformatteerde tekst:</th>
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

<h3><a name="bold">Vet gedrukte tekst</a>
 (<i><a href="document.php#bold">Bold Text</a></i>)</h3>
<p>Formatteer <b>vet gedrukte</b> tekst (tekst die is gedrukt in een dikkere versie van het lettertype), 
   met <tt>&lt;b&gt;</tt> ervoor en <tt>&lt;/b&gt;</tt> erachter. (Let op de "/" in de laatste markering.)
</p>
<p>Interpunctie blijft <b>buiten</b> de markering, tenzij een hele zin of een hele alinea vet gedrukt is, 
   of als de interpunctie zelf onderdeel is van een vet gedrukte frase, titel of afkorting. 
</p>
<p>Zie <a href="#page_hf">Koptekst/Voettekst</a> origineel en tekst voor een voorbeeld.
</p>
<p>Sommige Project Managers vragen in de <a href="#comments">Project Comments</a>
   om de vet gedrukte tekst om te zetten in hoofdletters.
</p>

<h3><a name="supers">Superscript</a>
 (<i><a href="document.php#supers">Superscripts</a></i>)</h3>
<p>Oudere boeken gebruikten vaak samentrekkingen als afkortingen, en drukten deze dan als superscript. Bijv.:<br>
   &nbsp;&nbsp;&nbsp;&nbsp;Gen<sup>rl</sup> Washington defeated L<sup>d</sup> Cornwall's army.<br>
   Formatteer deze door een enkel dakje v&oacute;&oacute;r de tekst in superscript te zetten, zo:<br>
   &nbsp;&nbsp;&nbsp;&nbsp;<tt>Gen^rl Washington defeated L^d Cornwall's army.</tt>
</p>
<p>Formatteer in wetenschappelijke en technische werken, superscript met accolades 
   <tt>{</tt> en <tt>}</tt> ook als er maar &eacute;&eacute;n letterteken in superscript staat..
   <br>Bijv.:
   <br>&nbsp;&nbsp;&nbsp;&nbsp;... up to x<sup>n-1</sup> elements in the array.
   <br>wordt geformatteerd als:
   <br>&nbsp;&nbsp;&nbsp;&nbsp;<tt>... up to x^{n-1} elements in the array.<br></tt>
</p>
<p>Het kan zijn dat de Project Manager in de <a href="#comments">Project Comments</a> 
   vraagt om tekst in superscript op een andere manier te markeren.
</p>

<h3><a name="subscr">Subscript</a>
 (<i><a href="document.php#subscr">Subscripts</a></i>)</h3>
<p>In wetenschappelijke werken wordt vaak subscript gebruikt, al komt het in andere boeken niet vaak voor. 
   Formatteer tekst in subscript door eerst een laag streepje <tt>_</tt> neer te zetten en zet 
   vervolgens accolades <tt>{</tt> en <tt>}</tt> om de tekst die in subscript staat. 
   <br>Bijv.:
   <br>&nbsp;&nbsp;&nbsp;&nbsp;H<sub>2</sub>O.
   <br>wordt geformatteerd als:
   <br>&nbsp;&nbsp;&nbsp;&nbsp;<tt>H_{2}O.<br></tt>
</p>

<h3><a name="underl">Onderstreepte Tekst</a>
 (<i><a href="document.php#underl">Underlined Text</a></i>)</h3>
<p>Formatteer <u>onderstreepte tekst</u> op dezelfde manier als <a href="#italics">Cursieve Tekst</a>, 
   met <tt>&lt;i&gt;</tt> en <tt>&lt;/i&gt;</tt>. (Let op de "/" in de laatste markering.) 
</p>
<p>Onderstreping werd vaak gebruikt om nadruk aan te geven, als de zetter geen mogelijkheden had 
   om de tekst te cursiveren, bijv. in getypte documenten.
</p>
   Sommige Project Managers kunnen in de <a href="#comments">Project Comments</a> aangeven dat de
   onderstreepte tekst met <tt>&lt;u&gt;</tt> en <tt>&lt;/u&gt;</tt> moet worden gemarkeerd.
</p>

<h3><a name="spaced">U i t g e s p a t i e e r d e &nbsp; T e k s t</a>
 (<i><a href="document.php#spaced">S p a c e d &nbsp; O u t &nbsp; Text (gesperrt)</a></i>)</h3>
<p>Formatteer u i t g e s p a t i e e r d e &nbsp; t e k s t door er <tt>&lt;g&gt;</tt> voor en 
   <tt>&lt;/g&gt;</tt> na te zetten. (Let op de "/" in de laatste markering.) 
   Verwijder de extra spaties binnen de woorden.
</p>
<p>Interpunctie blijft <b>buiten</b> de markering, tenzij een hele zin of een hele alinea uitgespatieerd is, 
   of als de interpunctie zelf onderdeel is van een uitgespatieerde frase, titel of afkorting.
</p>
<p>Uitspati&euml;ring was een zettechniek die vooral in Duitse en Nederlandse boeken gebruikt werd
   om een deel van de tekst te benadrukken.
</p>

<h3><a name="font_ch">Verandering van Lettertype</a>
 (<i><a href="document.php#font_ch">Font Changes</a></i>)</h3>
<p>Formatteer een verandering van lettertype binnen een alinea of binnen een zin, door 
   <tt>&lt;f&gt;</tt> voor de tekst in het andere lettertype te zetten en <tt>&lt;/f&gt;</tt> erna.
   (Let op de "/" in de laatste markering.)
   Gebruik deze markering om speciale lettertypes of andere formattering aan te geven, <b>uitgezonderd</b>
   de vet gedrukte, cursief gedrukte, klein kapitaal of uitgespatieerde tekst. 
   Deze laatste hebben hun eigen markeringen.
</p>
<p>Mogelijk gebruik van deze markering:</p>
<ul compact>
  <li>antiqua (een versie van een roman lettertype) binnen fraktur</li>
  <li><b>blackletter</b> binnen een alinea met een normaal lettertype</li>
  <li>een kleiner of groter lettertype alleen als het voorkomt <b>binnen</b> een alinea in 
   een normaal lettertype (voor een hele alinea in een ander lettertype of een andere maat, 
   zie het gedeelte over <a href="#block_qt">Citaten</a>)</li>
  <li>rechtop lettertype binnen een cursief gedrukte alinea</li>
</ul>
<p>Het specifieke gebruik van deze markering in een project wordt meestal aangegeven in de 
   <a href="#comments">Project Comments</a>. Formatteerders kunnen het beste in de 
   <a href="#forums">Project Discussie</a> posten als deze markering nodig lijkt en nog niet besproken is.
</p>
<p>Interpunctie blijft <b>buiten</b> de markering, tenzij een hele zin of een hele alinea 
   in een ander lettertype gedrukt is, of als de interpunctie zelf onderdeel is van een 
   frase, titel of afkorting in een ander lettertype. 
</p>

<h3><a name="font_sz">Verandering in grootte van het lettertype</a>
 (<i><a href="document.php#font_sz">Font size changes</a></i>)</h3>
<p>Normaliter markeren we verandering in grootte van lettertypes niet.
</p>
<p>De uitzonderingen zijn, als de grootte verandert om een <a href="#block_qt">citaat</a> aan te geven, 
   of als de grootte van het lettertype binnen een alinea of binnen een zin verandert. 
   (Zie <a href="#font_ch">Verandering van Lettertype</a>).
</p>

<h3><a name="word_caps">Woorden in HOOFDLETTERS</a>
 (<i><a href="document.php#word_caps">Words in all Capitals</a></i>)</h3>
<p>Formatteer woorden die gedrukt zijn in HOOFDLETTERS, helemaal in HOOFDLETTERS.
</p>
<p>De uitzondering hierop is het <a href="#chap_head">eerste woord van een hoofdstuk</a>: 
   veel oude boeken drukten het eerste woord van hoofdstukken helemaal in hoofdletters. Dit moet 
   veranderd worden in hoofdletter-kleine letters, dus "ONCE upon a time," wordt "<tt>Once upon a time,</tt>".
</p>

<h3><a name="small_caps">Woorden in <span style="font-variant: small-caps">kleine hoofdlettertjes (Klein Kapitaal)</span></a>
 (<i><a href="document.php#small_caps">Words in Small Capitals</a></i>)</h3>
<p><span style="font-variant:small-caps;">Gemengd Klein Kapitaal</span> wordt anders gemarkeerd dan 
   tekst <span style="font-variant:small-caps;">helemaal in klein kapitaal</span>:
</p>
<p>Formatteer woorden in <span style="font-variant: small-caps;">Gemengd Klein Kapitaal</span> als 
   hoofdletters en kleine letters. Zet er <tt>&lt;sc&gt;</tt> en <tt>&lt;/sc&gt;</tt> markering omheen.<br>
&nbsp;&nbsp;&nbsp;&nbsp;Voorbeeld:
   <span style="font-variant: small-caps;">This is Small Caps</span> <br>
&nbsp;&nbsp;&nbsp;&nbsp;wordt correct geformatteerd als:
   <tt>&lt;sc&gt;This is Small Caps&lt;/sc&gt;</tt>.
</p>

<p>Formatteer woorden die <span style="font-variant: small-caps;">helemaal in klein kapitaal</span>
   als HOOFDLETTERS. Zet er <tt>&lt;sc&gt;</tt> en <tt>&lt;/sc&gt;</tt> markering omheen.<br>
&nbsp;&nbsp;&nbsp;&nbsp;Voorbeeld:
   You cannot be serious about
   <span style="font-variant: small-caps;">aardvarks</span>!<br>
&nbsp;&nbsp;&nbsp;&nbsp;wordt correct geformatteerd als:
   <tt>You cannot be serious about
   &lt;sc&gt;AARDVARKS&lt;/sc&gt;!</tt> <br>
</p>

<p>Woorden in titels (Hoofdstuktitels, paragraaftitels, bijschriften enz.) die helemaal in hoofdletters 
   staan, moeten worden geformatteerd als hoofdletters, zonder &lt;sc&gt; &lt;/sc&gt;. Als het eerste 
   woord van een hoofdstuk in <span style="font-variant: small-caps">Klein Kapitaal</span> staat,
   moet dit worden veranderd in hoofdletter-kleine letters, zonder de markering.
</p>

<h3><a name="drop_caps">Grote, versierde hoofdletter aan het begin</a>
 (<i><a href="document.php#drop_caps">Large, Ornate opening Capital letter (Drop Cap)</a></i>)</h3>
<p>Formatteer een grote, versierde eerste letter van een hoofdstuk, paragraaf of alinea als een gewone letter.
</p>

<h3><a name="em_dashes">Liggend streepje: koppelteken, gedachtestreepje, minteken</a>
 (<i><a href="document.php#em_dashes">Dashes, Hyphens, and Minus Signs</a></i>)</h3>
<p>Over het algemeen zul je vier van dergelijke tekens in boeken tegenkomen:
  <ol compact>
    <li><i>Liggend streepje/Koppelteken (Hyphen)</i>. Dit wordt gebruikt om woorden te <b>koppelen</b>
        tot &eacute;&eacute;n woord, of om voor- of achtervoegsels aan een woord te koppelen.
    <br>Laat dit teken staan als een enkel streepje; verwijder aan weerskanten een eventueel aanwezige spatie.
    <br>Er is een (zeker in het Nederlands) veel voorkomende uitzondering op deze regel, 
        die te zien is in het tweede voorbeeld hieronder.
    </li>
    <li><i>Liggend streepje/Minteken (En-dash)</i>. Dit streepje is net iets langer, en wordt gebruikt voor een
        aaneensluitende getallen<b>reeks</b>, of als een rekenkundig <b>min</b>teken.
    <br>Laat ook dit teken staan als een enkel streepje. Of er spaties voor of achter staan, wordt
        bepaald door hoe het in het boek is gedaan. Er zijn meestal geen spaties in een getallenbereik,
        en meestal wel spaties rond rekenkundige mintekens, soms aan beide zijden, soms alleen ervoor.
    </li>
    <li><i>Liggend streepje/Gedachtestreepje/Kastlijn (Em-dashes)</i>. Dit dient als <b>scheidingsteken</b> tussen woorden&mdash;soms 
        om iets te benadrukken, zoals hier&mdash;of als een spreker zich verslikt in een woord&mdash;&mdash;!
    <br>Formatteer deze als twee streepjes als het liggende streepje kort is, en als vier streepjes als het liggende streepje lang is.
        Laat geen spatie voor of na de streepjes, zelfs niet als er in de oorspronkelijke tekst een spatie te zien is.
    </li>
    <li><i>Met Opzet Weggelaten of Gecensureerde Woorden of Namen</i>.
    <br>Formatteer deze als 4 streepjes. Als de streepjes een woord voorstellen, dan laten we er
        spaties omheen staan alsof het echt een woord is. Als het een deel van een woord betreft,
        dan geen spaties&mdash;koppel het aan de rest van het woord. 
        Als de streep ongeveer dezelfde lengte heeft als de kortere em-dashes, formatteer deze
        dan als een enkele em-dash, dus als twee streepjes.
    </li>
  </ol>
<p>NB: Als een em-dash aan het begin of het eind van een regel staat, voeg dan de regel samen met
   de vorige/volgende regel, z&oacute; dat er geen spatie of regeleinde naast de em-dash staat.
   Alleen als de auteur de em-dash gebruikt heeft om een alinea, dichtregel of gesproken
   tekst te beginnen of eindigen, dien je de em-dash te laten staan aan het begin of het
   eind van een regel. Zie de voorbeelden hieronder. 
</p>
<!-- END RR -->

<p><b>Voorbeelden</b>&mdash;Liggende streepjes:
</p>

<table width="100%" align="center" border="1"  cellpadding="4" cellspacing="0" summary="Hyphens and Dashes">
  <tbody>
    <tr>
      <th valign="top" bgcolor="cornsilk">Origineel:</th>
      <th valign="top" bgcolor="cornsilk">Correct geformatteerde tekst:</th>
      <th valign="top" bgcolor="cornsilk">Type</th>
    </tr>
    <tr>
      <td valign="top">semi-detached</td>
      <td valign="top"><tt>semi-detached</tt></td>
      <td> Koppelteken</td>
    </tr>
    <tr>
      <td valign="top">three- and four-part harmony</td>
      <td valign="top"><tt>three- and four-part harmony</tt></td>
      <td> Koppelteken</td>
    </tr>
    <tr>
      <td valign="top">discoveries which the Crus-<br>
        aders made and brought home with</td>
      <td valign="top"><tt>discoveries which the Crusaders<br>
        made and brought home with</tt></td>
      <td> Koppelteken</td>
    </tr>
    <tr>
      <td valign="top">factors which mold char-<br>
        acter&mdash;environment, training and heritage,</td>
      <td valign="top"><tt>factors which mold character--environment,<br>
        training and heritage,</tt>
      <td> Koppelteken</td>
    </tr>
    <tr>
      <td valign="top">See pages 21&ndash;25</td>
      <td valign="top"><tt>See pages 21-25</tt></td>
      <td>En-dash</td>
    </tr>
    <tr>
      <td valign="top">&ndash;14&deg; below zero</td>
      <td valign="top"><tt>-14&deg; below zero</tt></td>
      <td>En-dash</td>
    </tr>
    <tr>
      <td valign="top">X &ndash; Y = Z</td>
      <td valign="top"><tt>X - Y = Z</tt></td>
      <td>En-dash</td>
    </tr>
    <tr>
      <td valign="top">2&ndash;1/2</td>
      <td valign="top"><tt>2-1/2</tt></td>
      <td>En-dash</td>
    </tr>
    <tr>
      <td valign="top">I am hurt;&mdash;A plague<br> on both your houses!&mdash;I am dead.</td>
      <td valign="top"><tt>I am hurt;--A plague<br> on both your houses!--I am dead.</tt></td>
      <td>Em-dash</td>
    </tr>
    <tr>
      <td valign="top">sensations&mdash;sweet, bitter, salt, and sour<br>
        &mdash;if even all of these are simple tastes. What</td>
      <td valign="top"><tt>sensations--sweet, bitter, salt, and sour--if<br>
        even all of these are simple tastes. What</tt></td>
      <td>Em-dash</td>
    </tr>
    <tr>
      <td valign="top">senses&mdash;touch, smell, hearing, and sight&mdash;<br>
        with which we are here concerned,</td>
      <td valign="top"><tt>senses--touch, smell, hearing, and sight--with<br>
        which we are here concerned,</tt></td>
      <td>Em-dash</td>
    </tr>
    <tr>
      <td valign="top">It is the east, and Juliet is the sun!&mdash;</td>
      <td valign="top"><tt>It is the east, and Juliet is the sun!--</tt></td>
      <td>Em-dash</td>
    </tr>
 <tr>
      <td valign="top">"Three hundred&mdash;&mdash;" "years," she was going to
    say, but the left-hand cat interrupted her.</td>
      <td valign="top"><tt>"Three hundred----" "years," she was going to
    say, but the left-hand cat interrupted her.</tt></td>
      <td>Lange Em-dash</td>
    </tr>
    <tr>
      <td valign="top">As the witness Mr. &mdash;&mdash; testified,</td>
      <td valign="top"><tt>As the witness Mr. ---- testified,</tt></td>
      <td>Lange Em-dash</td>
    </tr>
    <tr>
      <td valign="top">As the witness Mr. S&mdash;&mdash; testified,</td>
      <td valign="top"><tt>As the witness Mr. S---- testified,</tt></td>
      <td>Lange Em-dash</td>
    </tr>
    <tr>
      <td valign="top">the famous detective of &mdash;&mdash;B Baker St.</td>
      <td valign="top"><tt>the famous detective of ----B Baker St.</tt></td>
      <td>Lange Em-dash</td>
    </tr>
    <tr>
      <td valign="top">&ldquo;You &mdash;&mdash; Yankee&rdquo;, she yelled.</td>
      <td valign="top"><tt>"You ---- Yankee", she yelled.</tt></td>
      <td>Lange Em-dash</td>
    </tr>
    <tr>
      <td valign="top">&ldquo;I am not a d&mdash;d Yankee&rdquo;, he replied.</td>
      <td valign="top"><tt>"I am not a d--d Yankee", he replied.</tt></td>
      <td>Em-dash</td>
    </tr>
  </tbody>
</table>

<h3><a name="eol_hyphen">Woordafbreking aan het eind van een regel</a>
 (<i><a href="document.php#eol_hyphen">End-of-line Hyphenation</a></i>)</h3>
<p>Als een regel eindigt met een afbreekstreepje, voeg dan de twee helften van het afgebroken woord
   samen tot &eacute;&eacute;n woord. Als het een woord is met een koppelteken, zoals secretaris-generaal,
   voeg dan de helften samen tot &eacute;&eacute;n woord met het koppelstreepje ertussen. Als de eerste helft met
   een streepje eindigde omdat het gehele woord niet op de regel paste, en het een woord betreft dat
   normaal geen koppelteken bevat, voeg dan de twee helften samen en verwijder het streepje.
   Laat het samengevoegde woord staan op de regel waar de eerste helft stond, en breek de regel na
   het woord af om de bestaande regel-indeling te behouden&mdash;dit maakt het gemakkelijker voor
   de na jou komende vrijwilligers. Zie ook het hoofdstuk over <a href="#em_dashes">liggende streepjes</a>
   voor voorbeelden (ho-ge wordt hoge, maar secretaris-generaal houdt het streepje). Als het woord
   gevolgd wordt door een leesteken, haal dan ook het leesteken naar de bovenste regel. 
</p>
<p>Een aantal woorden (b.v. de Engelse woorden to-day en to-morrow), die tegenwoordig geen koppelteken
   bevatten, hadden dat vaak wel in de oude boeken die wij onder handen hebben. Dergelijke woorden
   behouden hun koppelteken zoals de auteur het heeft geschreven. Als je niet zeker weet of de auteur
   dit woord wel of niet met koppelteken zou hebben geschreven, voeg dan het woord samen tot &eacute;&eacute;n woord,
   laat het koppelteken staan, en zet een <tt>*</tt> achter het koppelteken. Bijvoorbeeld: <tt>to-*day</tt>.
   Het sterretje zorgt ervoor dat er naar wordt gekeken door de Post-Processor, die alle pagina's kan
   bekijken, en kan bepalen hoe de auteur dit woord schreef of geschreven zou hebben. 
</p>

<h3><a name="eop_hyphen">Woordafbreking aan het eind van een bladzijde</a>
 (<i><a href="document.php#eop_hyphen">End-of-page Hyphenation</a></i>)</h3>
<p>Laat bij een woordafbreking aan het eind van een bladzijde het streepje aan het eind van
   de laatste regel staan, en breng het ter attentie van de Post-Processor door er een
   <tt>*</tt> achter te zetten.<br>
   Bijvoorbeeld:<br>
   &nbsp;<br>
   &nbsp;&nbsp;&nbsp;&nbsp;something Pat had already become accus-<br>
   wordt:<br>
   &nbsp;&nbsp;&nbsp;&nbsp;<tt>something Pat had already become accus-*</tt>
</p>
<p>Als een bladzijde begint met het tweede deel van een woord dat op de vorige bladzijde is afgebroken,
   of met een em-dash, zet dan een <tt>*</tt> voor het gedeeltelijke woord, of voor de em-dash.<br>
   Dus, op de volgende bladzijde van het bovenstaande voorbeeld:<br>
   &nbsp;<br>
   &nbsp;&nbsp;&nbsp;&nbsp;tomed to from having to do his own family<br>
   wordt:<br>
   &nbsp;&nbsp;&nbsp;&nbsp;<tt>*tomed to from having to do his own family</tt>
</p>
<p>Deze markeringen geven aan de Post-Processor een sein that het woord moet worden samengevoegd,
   als de pagina's tot een e-boek worden samengevoegd.
</p>

<h3><a name="next_word">Losstaand woord onderaan een pagina</a>
 (<i><a href="document.php#next_word">Single word at bottom of page</a></i>)</h3>
<p>Behandel een dergelijk woord door het woord te verwijderen, zelfs als het de tweede helft betreft van een afgebroken woord.
</p>
<p>In sommige oudere boeken wordt met een dergelijk losstaand woord ("<i>catchword</i>", meestal tegen de
   rechter zijkant van de pagina) aangegeven wat het eerste woord is op de volgende pagina van het
   boek (ook wel "<i>incipit</i>" genoemd). Dit werd gebruikt om de zetter te helpen de juiste achterzijde
   ("<i>verso</i>") te zetten, en om het zijn helpers gemakkelijker te maken de pagina's klaar te maken voor
   het binden. Ook diende het woord om te voorkomen dat de lezer meer dan &eacute;&eacute;n pagina tegelijk omsloeg.
</p>
<!-- END RR -->
<!-- We need an example here! -->

<h3><a name="contract">Samentrekkingen</a>
 (<i><a href="document.php#contract">Contractions</a></i>)</h3>
<p>Verwijder extra spaties in samentrekkingen, bijvoorbeeld: <tt>would&nbsp;n't</tt>
   dient te worden proefgelezen als <tt>wouldn't</tt>.
</p>
<p>Zetters behielden vroeger vaak een spatie om aan te geven dat 'would' en 'not'
   oorspronkelijk afzonderlijke woorden waren. Het kan ook een bijverschijnsel van het
   OCR-proces zijn. In beide gevallen dient de extra spatie verwijderd te worden. 
</p>
<p>Sommige Project Managers zetten een opmerking in de <a href="#comments">Project Comments</a>
   dat extra spaties niet verwijderd moeten worden in samentrekkingen, vooral wanneer het tekst
   betreft met straattaal, dialect, of wanneer de teksten niet in het Engels zijn geschreven. 
</p>


<h3><a name="poetry">Po&euml;zie/Epigrammen</a>
 (<i><a href="document.php#poetry">Poetry/Epigrams</a></i>)</h3>
<p>Dit gedeelte geldt voor een enkel gedicht of epigram, in een boek dat in het algemeen proza bevat. 
   Voor een po&euml;zieboek, zie de <a href="doc-poet.php">speciale handleiding voor po&euml;zieboeken</a>. (<i>Engels</i>) 
</p>
<p>Markeer po&euml;zie of epigrammen, zo dat de Post-Processor ze kan vinden. Voeg een regel in met 
   <tt>/*</tt> voor de start van po&euml;zie of epigram en een regel met <tt>*/</tt> na het eind ervan. 
   Laat een regel leeg tussen deze markeringen en de rest van de tekst.
</p>
<p>Laat de relatieve inspringing van de regels van een gedicht intact, door 2, 4, 6 (of meer) spaties
   te plaatsen voor de ingesprongen regels, zo dat de tekst zoveel mogelijk op het origineel lijkt.
</p>
<p>Als een dichtregel te lang is om op de gedrukte pagina te passen, wordt vaak het restant van de regel
   op de volgende regel gezet, met een flinke inspringing. Dit restant van de regel moet in zijn geheel
   worden samengevoegd met de regel erboven. Restregels beginnen in het algemeen met een kleine letter
   (dus niet een hoofdletter). Bovendien komen ze op onvoorspelbare momenten voor, in tegenstelling tot
   de normale inspringing, die regelmatig is, in overeenstemming met het metrum van het gedicht.
</p>
<p>Als het gedicht op de gedrukte pagina is gecentreerd, negeer dit dan tijdens het formatteren.
   Sluit de regels links aan; bewaar alleen de relatieve inspringing van de regels.
</p>
<p><b>Voetnoten</b> in po&euml;zie moeten worden behandeld als gewone voetnoten.
   Zie <a href="#footnotes">Voetnoten</a> voor meer details.
</p>
<p><b>Regelnummers</b> in po&euml;zie moeten worden behouden. Plaats ze aan het eind van de regel,
   en laat tenminste 6 spaties tussen het regelnummer en het eind van de tekstregel.
   Zie <a href="#line_no">Regelnummers</a> voor details.
</p>
<p>Controleer de <a href="#comments">Project Comments</a> voor de specifieke tekst die je formatteert.
   De Project Manager heeft vaak speciale instructies voor boeken met po&euml;zie. Vaak zijn de hier gegeven 
   richtlijnen niet of niet geheel van toepassing bij een boek dat geheel of bijna geheel uit po&euml;zie bestaat.
</p>
<!-- END RR -->

<br>
<table width="100%" align="center" border="1"  cellpadding="4"
       cellspacing="0" summary="Poetry Example">
  <tbody>
    <tr><th align="left" bgcolor="cornsilk">Origineel:</th></tr>
    <tr align="left">
      <th width="100%" valign="top"> <img src="poetry.png" alt=""
          width="500" height="508"> <br>
      </th>
    </tr>
    <tr><th align="left" bgcolor="cornsilk">Correct geformatteerde tekst:</th></tr>
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

<h3><a name="letter">Brieven/Correspondentie</a>
 (<i><a href="document.php#letter">Letters/Correspondence</a></i>)</h3>
<p>Formatteer brieven en correspondentie als <a href="#para_space">alinea's</a>. 
   Zet een lege regel voor het begin van de brief. Eventuele inspringing wordt genegeerd.
</p>
<p>Bij elkaar horende regels van kop- of voettekst (bijvoorbeeld adres, datum, begroeting, ondertekening) 
   moet in een blok gezet worden, door er <tt>/*</tt> en <tt>*/</tt> markeringen omheen te zetten. 
   Laat een lege regel tussen de markeringen en de rest van de tekst. 
   De markeringen zorgen ervoor dat de regeleinden behouden blijven bij het post-processen.
</p>
<p>Voeg geen inspringing toe aan kop- of voetteksten, ook niet als ze in het origineel zijn 
   ingesprongen of rechts zijn uitgelijnd&mdash;zet de regels gewoon aan de linkerkant. 
   De Post-Processor zal er indien gewenst formattering aan toevoegen.
</p>
<!-- END RR -->

<table width="100%" align="center" border="1"  cellpadding="4"
       cellspacing="0" summary="Letter Example">
  <tbody>
    <tr><th align="left" bgcolor="cornsilk">Origineel:</th></tr>
    <tr align="left">
      <th width="100%" valign="top"> <img src="letter.png" alt=""
          width="500" height="217"> <br>
      </th>
    </tr>
    <tr><th align="left" bgcolor="cornsilk">Correct geformatteerde tekst:</th></tr>
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


<h3><a name="lists">Lijsten</a>
 (<i><a href="document.php#lists">Lists of Items</a></i>)</h3>
<p>Zet lijsten tussen <tt>/*</tt> en <tt>*/</tt> markeringen. Laat een lege regel tussen deze markeringen en de
   rest van de tekst. De markeringen zorgen ervoor dat de regeleinden behouden blijven tijdens het post-processen. 
   Gebruik deze markeringen voor elke lijst waarbij de regeleinden behouden moeten blijven, zoals 
   bijvoorbeeld lijsten met vragen &amp; antwoorden, ingredi&euml;nten voor een recept, enz.
</p>
<!-- END RR -->

<table width="100%" align="center" border="1" cellpadding="4" cellspacing="0" summary="List">
  <tbody>
    <tr><th align="left" bgcolor="cornsilk">Origineel:</th></tr>
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
    <tr><th align="left" bgcolor="cornsilk">Correct geformatteerde tekst:</th></tr>
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

<h3><a name="tables">Tabellen</a>
 (<i><a href="document.php#tables">Tables</a></i>)</h3>
<p>Zet tabellen tussen <tt>/*</tt> en <tt>*/</tt> markeringen. Laat een lege regel tussen deze markeringen en de 
   rest van de tekst. De markeringen zorgen ervoor dat de regeleinden behouden blijven tijdens het post-processen. 
   Formatteer de tabel met spaties, zo dat de tabel er ongeveer uitziet zoals het origineel. 
   Maak de tabel niet breder dan 75 tekens. De richtlijnen van Project Gutenberg gaan verder, en zeggen 
   "...behalve als het niet anders kan. Maar nooit langer dan 80...". 
</p>
<p>Gebruik geen tabs bij het formatteren&mdash;gebruik alleen spaties. Tabs worden verschillend behandeld 
   op verschillende computers, zodat een tabel met tabs, er op een andere computer niet meer zo goed 
   uitziet als op je eigen computer.
</p>
<p>Het is vaak moeilijk een tabel in ASCII tekst te formatteren; doe gewoon je best. 
   Het is veel gemakkelijker wanneer je een 'mono-spaced' lettertype gebruikt, bijvoorbeeld 
   <a href="font_sample.php">DPCustomMono<a/> of Courier. 
   Houd in gedachten dat we de bedoeling van de auteur willen behouden, en intussen een leesbare 
   tabel in een e-boek willen cre&euml;ren. Soms betekent dit, dat we het originele tabel-formaat zoals het 
   gedrukt is, moeten opofferen. Lees de <a href="#comments">Project Comments</a> en de Project Discussie; 
   andere vrijwilligers kunnen al besloten hebben tot een specifiek formaat. Als je daar niets kan vinden, 
   kun je misschien meer vinden in de Engelse Forumdiscussie <a href="<? echo $Gallery_of_Table_Layouts_URL; ?>">Gallery of Table Layouts</a>.
</p>
<p><b>Voetnoten</b> in tabellen dienen aan het eind van de tabel te worden gezet. 
   Zie <a href="#footnotes">Voetnoten</a> voor details. 
</p>
<!-- END RR -->

<table width="100%" align="center" border="1" cellpadding="4"
 cellspacing="0" summary="Table Example 1">
  <tbody>
    <tr><th align="left" bgcolor="cornsilk">Origineel:</th></tr>
    <tr align="left">
      <td width="100%" valign="top"> <img src="table1.png" alt="" width="500" height="142"><br>
      </td>
    </tr>
    <tr><th align="left" bgcolor="cornsilk">Correct geformatteerde tekst:</th></tr>
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
    <tr><th align="left" bgcolor="cornsilk">Origineel:</th></tr>
    <tr align="left">
      <td width="100%" valign="top"> <img src="table2.png" alt="" width="500" height="304"><br>
      </td>
    </tr>
    <tr><th align="left" bgcolor="cornsilk">Correct geformatteerde tekst:</th></tr>
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



<h3><a name="block_qt">Citaten</a>
 (<i><a href="document.php#block_qt">Block Quotations</a></i>)</h3>
<p>Zet lange citaten (vaak meerdere regels, soms meerdere pagina's en vaak (maar niet altijd) 
   gezet met bredere kantlijnen of met een kleiner lettertype&mdash;soms allebei)
   tussen <tt>/#</tt> en <tt>#/</tt> markeringen. Laat een lege regel tussen deze
   markeringen en de rest van de tekst. De markeringen zorgen ervoor dat het blok citaat
   correct geformatteerd wordt tijdens het post-processen.
</p>
<p>Voor het overige worden citaten net zo geformatteerd als alle andere tekst. 
</p>
<p>Blok citaten zijn lange citaten (vaak meerdere regels, soms meerdere pagina's) en vaak (maar niet altijd) 
   gezet met bredere kantlijnen of met een kleiner lettertype&mdash;soms allebei.
</p>
<!-- END RR -->

<table width="100%" align="center" border="1" cellpadding="4"
 cellspacing="0" summary="Block Quotation">
  <tbody>
    <tr><th align="left" bgcolor="cornsilk">Origineel:</th></tr>
    <tr align="left">
      <td width="100%" valign="top"> <img src="bquote.png" alt="" width="500" height="475"><br>
      </td>
    </tr>
    <tr><th align="left" bgcolor="cornsilk">Correct geformatteerde tekst:</th></tr>
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

<h3><a name="double_q">Dubbele aanhalingstekens</a>
 (<i><a href="document.php#double_q">Double Quotes</a></i>)</h3>
<p>Aanhalingstekens in Engelse boeken: formatteer deze als gewone ASCII <tt>"</tt> dubbele aanhalingstekens. 
   Verander geen dubbele aanhalingstekens in enkele aanhalingstekens. 
   Laat de aanhalingstekens staan zoals in het origineel. 
</p>
<p>Aanhalingstekens in andere talen: gebruik de aanhalingstekens die voor die taal gewoon zijn, 
   als deze beschikbaar zijn. 
   Het Franse equivalent, de guillemets, <tt>&laquo;zoals dit&raquo;</tt>, kunnen gekozen worden in de
   uitklapmenu's in de proofreading interface, aangezien ze deel uitmaken van de Latin-1 tekenset.
   Vergeet niet spaties te verwijderen tussen de guillemets en de geciteerde tekst.
   Als er toch spaties gewenst zijn, zullen die tijdens het post-processen worden toegevoegd.
   Hetzelfde geldt voor talen waar omgekeerde guillemets worden gebruikt, <tt>&raquo;zoals dit&laquo;</tt>.
</p>
<p>De lage aanhalingstekens die in sommige teksten voorkomen (in het Nederlands en Duits, 
   en sommige andere talen), <tt>&bdquo;zoals dit&rdquo;</tt>
<? if(!$utf8_site) { ?>
   kunnen niet gekozen worden in de uitklapmenu's, aangezien ze niet in de Latin-1 tekenset voorkomen. 
   Volg de instructies in de Project Comments. Wordt er daar niets over gezegd, gebruik dan de gewone 
   ASCII dubbele aanhalingstekens, net als in het Engels. 
<? } else { ?>
   zijn ook beschikbaar in de uitklapmenu's. Om de zaak eenvoudig te houden, moet je altijd 
   <tt>&bdquo;</tt> en <tt>&ldquo;</tt> gebruiken als de aanhalingstekens in het origineel duidelijk 
   lage en hoge aanhalingstekens zijn, ongeacht welke aanhalingstekens in de originele tekst gebruikt worden.
   De aanhalingstekens zullen zo nodig tijden het post-processen veranderd worden in de aanhalingstekens
   die in de tekst gebruikt zijn.
<? } ?>
</p>
<p>Het kan zijn dat de Project Manager in de <a href="#comments">Project Comments</a> instructies geeft om 
   aanhalingstekens uit een niet-Engelse taal anders te behandelen.
</p>


<h3><a name="single_q">Enkele aanhalingstekens</a>
 (<i><a href="document.php#single_q">Single Quotes</a></i>)</h3>
<p>Formatteer deze als gewone ASCII <tt>'</tt> enkele aanhalingstekens (apostrof). Verander enkele
   aanhalingstekens niet in dubbele aanhalingstekens. Laat de aanhalingstekens staan zoals in het origineel. 
</p>

<h3><a name="quote_ea">Aanhalingstekens op iedere regel</a>
 (<i><a href="document.php#quote_ea">Quote Marks on each line</a></i>)</h3>
<p>Als in een citaat iedere tekstregel begint met een aanhalingsteken, verwijder deze dan, 
   <b>behalve</b> het aanhalingsteken waarmee het citaat begint. 
</p>
<p>Als het citaat meerdere alinea's omvat, dan dient bij iedere alinea de eerste regel te
   beginnen met een aanhalingsteken.
</p>
<p>Vaak staat er pas een aanhalingsteken sluiten aan het eind van het volledige citaat;
   dit kan op een andere pagina zijn dan de pagina die je onder handen hebt.
   Laat dit zo: voeg geen aanhalingstekens toe die niet op de pagina aanwezig zijn. 
</p>
<p>Er zijn wat uitzonderingen die specifiek zijn voor bepaalde talen. In het Frans bijvoorbeeld, 
   wordt er voor dialoog binnen citaten een combinatie van verschillende soorten van interpunctie
   gebruikt om verschillende sprekers aan te duiden. Controleer de Project Comments of stuur de
   Project Manager een boodschap in het Forum waarin je om uitleg vraagt.
</p>

<h3><a name="period_s">Punten aan het eind van een zin</a>
 (<i><a href="document.php#period_s">End-of-sentence Periods</a></i>)</h3>
<p>Formatteer punten aan het eind van een zin met een enkele spatie erachter. 
</p>
<p>Als er meer spaties achter een punt staan, hoef je die niet weg te halen, dat gebeurt automatisch
   tijdens het post-processen. Zie de <a href="#chap_head">Hoofdstuktitels</a> voor een voorbeeld.
</p>

<h3><a name="punctuat">Interpunctie</a>
 (<i><a href="document.php#punctuat">Punctuation</a></i>)</h3>
<p>In het algemeen hoort er geen spatie te staan v&oacute;&oacute;r interpunctie, behalve voor aanhalingstekens openen.
   Als er toch spaties voor interpunctie staan in de tekst, verwijder die dan. Doe dit zelfs bij talen
   als het Frans, waar normaal wel spaties voor interpunctie staan. 
</p>
<p>Het lijkt soms of er spaties voor interpunctie staan, omdat zetters in de 18e en 19e eeuw
   gedeeltelijke spaties voor interpunctie zoals een puntkomma of een komma gebruikten.
</p>
<!-- END RR -->

<table width="100%" align="center" border="1"  cellpadding="4" cellspacing="0" summary="Interpunctie">
  <tbody>
    <tr><th align="left" bgcolor="cornsilk">Origineel:</th></tr>
    <tr>
      <td valign="top">and so it goes&nbsp;; ever and ever.</td>
    </tr>
    <tr><th align="left" bgcolor="cornsilk">Correct geformatteerde tekst:</th></tr>
    <tr>
      <td valign="top"><tt>and so it goes; ever and ever.</tt></td>
    </tr>
  </tbody>
</table>

<h3><a name="line_br">Regelafbrekingen</a>
 (<i><a href="document.php#line_br">Line Breaks</a></i>)</h3>
<p><b>Laat alle regelafbrekingen staan zoals ze zijn.</b> De volgende formatter en de Post-Processor
   kunnen dan de regels in de tekst gemakkelijk vergelijken met de regels in het origineel.
   Let hier speciaal op als je <a href="#eol_hyphen">woorden met een afbreekstreepje</a> samenvoegt,
   of als je woorden rondom <a href="#em_dashes">em-dashes</a> verplaatst. Als de vorige vrijwilliger
   regelafbrekingen verwijderd heeft, herstel ze dan alsjeblieft, zodat ze weer zo zijn als in het origineel.
</p>
<p>Extra lege regels die niet in het origineel voorkomen, moeten verwijderd worden, behalve als we ze
   doelbewust toevoegen voor het formatteren. Witregels aan het eind van een pagina maken niets uit, 
   die worden verwijderd tijdens het post-processen. 
</p>
<!-- END RR -->
<!-- We should have an example right here for this. -->

<h3><a name="extra_sp">Extra spaties of tabs tussen woorden</a>
 (<i><a href="document.php#extra_sp">Extra spaces or tabs between Words</a></i>)</h3>
<p>Extra spaties en tabs tussen woorden komen nogal veel voor in de output van de OCR.
   Je hoeft ze niet te verwijderen&mdash;dit gebeurt automatisch tijden het post-processen.
</p>
<p>Maar, extra spaties rondom interpunctie, em-dashes, aanhalingstekens enz. moeten <b>wel</b>
   verwijderd worden als ze tussen het symbool en het woord staan.
</p>
<p>Bijvoorbeeld: in <tt>A horse&nbsp;;&nbsp;&nbsp;my kingdom for a horse.</tt> moet de spatie
   tussen "horse" en de puntkomma verwijderd worden. Maar de twee spaties na de puntkomma mogen
   blijven staan, je hoeft er niet &eacute;&eacute;n weg te halen.
</p>

<h3><a name="trail_s">Spaties na het eind van een regel</a>
 (<i><a href="document.php#trail_s">Trailing Space at End-of-line</a></i>)</h3>
<p>Je hoeft aan het eind van een regel geen spatie in te voegen. Dit is verspilling van je tijd,
   aangezien we het later automatisch kunnen doen. Verspil je tijd ook niet door extra
   spaties aan het eind van regels weg te halen. 
</p>

<h3><a name="line_no">Regelnummers</a>
 (<i><a href="document.php#line_no">Line Numbers</a></i>)</h3>
<p>Laat regelnummers staan. Zet op zijn minst 6 spaties tussen het rechter uiteinde van de regel en het nummer, 
   zelfs als ze in het origineel aan de linkerkant van de tekst of de po&euml;zie staan. 
</p>
<p>Regelnummers zijn de nummers in de marge. Soms staan ze op elke regel, soms op elke vijfde
   of tiende regel. Ze komen vaak voor in gedichtenbundels. Ze zijn nuttig voor de lezers van e-boeken,
   aangezien po&euml;zie niet wordt geherformatteerd in de eboek versie.
</p>
<!-- END RR -->
<!-- We need an example image and text for this. -->

<h3><a name="extra_s">Extra lege regels/Sterretjes/Lijn tussen Alinea's</a>
 (<i><a href="document.php#extra_s">Extra Spacing/Stars/Line Between Paragraphs</a></i>)</h3>
<p>De meeste alinea's beginnen op de regel meteen na de vorige alinea. Soms worden alinea's 
   van elkaar gescheiden om een "gedachtebreuk" ("thought break") aan te geven. 
   Een "gedachtebreuk" kan de vorm aannemen van een rij sterretjes, streepjes, of een ander teken, of van een lijn,
   soms rijkelijk versierd, van een decoratie, of zelfs van &eacute;&eacute;n of twee extra lege regels. 
</p>
<p>Een gedachtebreuk kan een verandering van omgeving of van onderwerp betekenen, een verandering in 
   tijdstip, of een stuk spanning. Dit is zo bedoeld door de schrijver, dus we handhaven het, 
   door een lege regel in te voegen, vervolgens <tt>&lt;tb&gt;</tt> en dan nog een lege regel. 
</p>
<p>Project Managers en/of Post-Processors kunnen vragen om aanvullende informatie in de gedachtebreukmarkering
   te handhaven. Er zijn bijv. projecten die verschillende soorten van onderbrekingen op een verschillende
   manier aangeven, een lijn met sterretjes op de ene plaats en een lege regel op de andere. In die gevallen, 
   kan in de Project Comments gevraagd worden dat deze zo worden gemarkeerd: <tt>&lt;tb stars&gt;</tt> en
   <tt>&lt;tb&gt;</tt>. Lees de Project Comments altijd zorgvuldig, zodat je weet wat er bij elk project van
   je verwacht wordt. Let ook op dat je deze speciale verzoeken niet ook toepast in andere projecten. 
</p>
<p>Soms gebruikten de zetters gedecoreerde lijnen om het eind van een hoofdstuk aan te geven. Aangezien we de
   <a href="#chap_head">hoofdstuktitels</a> al markeren, hoeft er in dit geval niet ook nog een
   "thought break" markering te worden toegevoegd. 
</p>
<p>De proofreading interface bevat een "thought break" markering die je kunt knippen en plakken. 
</p>
<!-- END RR -->
<br>
<table width="100%" align="center" border="1" cellpadding="4"
 cellspacing="0" summary="Thought Break">
  <tbody>
    <tr><th align="left" bgcolor="cornsilk">Origineel:</th></tr>
    <tr align="left">
      <td width="100%" valign="top"> <img src="tbreak.png" alt="thought break"
          width="500" height="264"> <br>
      </td>
    </tr>
    <tr><th align="left" bgcolor="cornsilk">Correct geformatteerde tekst:</th></tr>
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

<h3><a name="period_p">Beletselteken ofwel ellips &quot;&hellip;&quot;</a>
 (<i><a href="document.php#period_p">Period Pause &quot;&hellip;&quot; (Ellipsis)</a></i>)</h3>
<p>De richtlijnen zijn verschillend voor het Engels en voor andere talen dan het Engels (Languages Other Than English (LOTE)).
</p>
<p><b>ENGELS</b>:
   Zet een spatie v&oacute;&oacute;r de drie punten, en zet ook erna een spatie. De uitzondering is het einde van een zin,
   daar moet: geen spatie, vier punten en een spatie. Dit geldt ook voor andere interpunctie aan het einde
   van een zin. De drie punten komen er meteen na, zonder spatie. 
</p>
<p>Bijvoorbeeld:<br>
   <tt>
   <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;That I know ... is true.
   <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;This is the end....
   <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Wherefore art thou Romeo?...
   </tt>
</p>
<p>Soms staat de interpunctie helemaal aan het eind, dan formatteer je het ook zo:<br>
   <tt>
   <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Wherefore art thou Romeo...?
   </tt>
</p>
<p>Verwijder evt. extra punten, of voeg zo nodig punten toe, om te zorgen dat er drie punten staan, of zo nodig vier.
</p>

<p><b>LOTE</b>: (Andere talen dan het Engels)
   De algemene regel is: <i>Doe het zoals op de pagina gedrukt staat.</i> Voeg in het bijzonder spaties in,
   als er spaties voor of tussen de punten staan. Gebruik evenveel punten als in het origineel.
   Soms is het origineel niet helemaal duidelijk. Voeg dan <tt>[**unclear]</tt> in om de aandacht
   van de Post-Processor er op te vestigen.
   (Noot: Post-Processors dienen deze spaties te vervangen door <i>non-breaking</i> spaties.)
</p>

<h3><a name="a_chars">Tekens met accenten/Non ASCII tekens</a>
 (<i><a href="document.php#a_chars">Accented/Non-ASCII Characters</a></i>)</h3>
<? if(!$utf8_site) { ?>
<p>Formatteer deze waar mogelijk door Latin-1 tekens met accenten te gebruiken. Zie
   <a href="#d_chars">Diakritische tekens</a> voor de manier waarop een aantal niet-Latin-1
   tekens geformatteerd moeten worden.
</p><? } else { ?>
<p>Formatteer deze a.u.b. door de juiste UTF-8 characters te gebruiken. 
   Voor characters die niet in Unicode zitten, zie de Project Manager instructies in de 
   <a href="#comments">Project Comments</a>.
</p>
<? } ?>
<p>Als je ze niet op je toetsenbord hebt, zijn er meerdere manieren om deze tekens in te geven:</p>
<ul compact>
  <li> De uitklapmenu's in de formatting interface.</li>
  <li> Hulpprogrammaatjes inbegrepen in je besturingssysteem:
      <ul compact>
      <li>Windows: "Speciale tekens"<br> Te benaderen door:<br>
          Start: Uitvoeren: charmap, ofr<br>
          Start: Bureau-accessoires: Systeemwerkset: Speciale tekens.</li>
      <li>Macintosh: Key Caps of "Keyboard Viewer"<br>
          Voor OS 9 en lager, dit staat in het Apple Menu<br>
          Voor OS X tot en met 10.2, dit is te vinden in de map Toepassingen, Utilities<br>
          Voor OS X 10.3 en hoger, dit is te vinden in het Input Menu als "Toetsenbord viewer"</li>
      <li>Linux: dit verschilt en hangt af van je bureaublad.<br>
          Voor KDE, probeer KCharSelect (in het submenu <i>Utilities</i> van het startmenu.)</li>
      </ul>
  </li>
  <li>Een on-line programma, zoals 
      <a href="http://free.pages.at/krauss/computer/xml/daten/edicode.html">Edicode</a>.</li>
  <li> Toetscombinaties.<br>
       (Zie de tabellen hieronder voor <a href="#a_chars_win">Windows</a> en <a href="#a_chars_mac">Macintosh</a>.)</li>
  <li> Switch naar een toetsenbordindeling of een plek die "deadkey" accenten ondersteunt.
       <ul compact>
       <li>Windows: Configuratiescherm (Toetsenbord, Input Locales).</li>
       <li>Macintosh: Input Menu (op de Menu Bar).</li>
       <li>Linux: Verander je toetsenbord in je X configuratie.</li>
      </ul>
</ul>
<p>
   Het oorspronkelijke <a href="http://www.gutenberg.org">Project Gutenberg</a> zet minstens
   7-bit ASCII versies van teksten op het net, maar ze aanvaarden daarnaast versies die meer van
   de informatie van het origineel behouden. <a href="http://pge.rastko.net">Project Gutenberg
   Europe</a> publiceert als standaard UTF-8, maar andere, passende types zijn ook welkom.
</p>
<p>Voor <a href="http://www.pgdp.net/">Distributed Proofreaders</a> betekent dit op dit moment dat
   we Latin-1 of ISO 8859-1 en -15 gebruiken, in de toekomst zullen we ook Unicode aanvaarden.
</p>
<p><a href="http://dp.rastko.net/">Distributed Proofreaders Europe</a> gebruikt al Unicode.
</p>
<!-- END RR -->
<a name="a_chars_win"></a>
<p><b>Voor Windows</b>:
</p>
<ul compact>
  <li>Je kunt het programma Speciale Tekens gebruiken (Start: Uitvoeren: charmap) om een teken te knippen &amp; plakken.
  </li>
  <li>De uitklapmenu's in de formatting interface.
  </li>
  <li>Of je kunt de Alt+Numeriek Toetsenbord sneltoetsen gebruiken voor deze tekens.
      <br>Als je eenmaal gewend bent aan de codes, werkt dit sneller dan knippen &amp; plakken.
      <br>Houdt de Alt-toets ingedrukt en typ de vier cijfers op je
          <i>Numerieke Toetsenbord</i>&mdash;de getallen boven de letters werken niet.
      <br>Je moet alle 4 de getallen typen, inclusief de 0 aan het begin.
          Houd in de gaten dat de hoofdletter van een letter 32 minder is dan de kleine letter.
      <br>Deze instructies werken voor de US-engelse toetsenbord indeling. Bij andere indelingen werken ze misschien niet.
      <br>De tabel hieronder laat zien welke codes we gebruiken.
          (<a href="charwin.pdf">Printer-vriendelijke versie van deze tabel</a>).
      <br>Gebruik geen andere speciale tekens, tenzij de Project Manager dat vraagt in de <a href="#comments">Project Comments</a>.
  </li>
</ul>

<br>
<table align="center" border="6" rules="all" summary="Windows Sneltoetsen">
  <tbody>
  <tr>
      <th bgcolor="cornsilk" colspan=14>Windows Sneltoetsen voor Latin-1 symbolen</th>
  </tr>
  <tr bgcolor="cornsilk">
      <th colspan=2>` grave</th>
      <th colspan=2>&acute; acute (aigu)</th>
      <th colspan=2>^ circumflex</th>
      <th colspan=2>~ tilde</th>
      <th colspan=2>&uml; umlaut</th>
      <th colspan=2>&deg; ring</th>
      <th colspan=2>&AElig; verbinding</th>
  </tr>
  <tr><td align="center" bgcolor="mistyrose" title="kleine letter a grave"       >&agrave; </td><td>Alt-0224</td>
      <td align="center" bgcolor="mistyrose" title="kleine letter a acute"       >&aacute; </td><td>Alt-0225</td>
      <td align="center" bgcolor="mistyrose" title="kleine letter a circumflex"  >&acirc;  </td><td>Alt-0226</td>
      <td align="center" bgcolor="mistyrose" title="kleine letter a tilde"       >&atilde; </td><td>Alt-0227</td>
      <td align="center" bgcolor="mistyrose" title="kleine letter a umlaut"      >&auml;   </td><td>Alt-0228</td>
      <td align="center" bgcolor="mistyrose" title="kleine letter a ring"        >&aring;  </td><td>Alt-0229</td>
      <td align="center" bgcolor="mistyrose" title="kleine letter ae verbinding" >&aelig;  </td><td>Alt-0230</td>
  </tr>
  <tr><td align="center" bgcolor="mistyrose" title="Hoofdletter A grave"         >&Agrave; </td><td>Alt-0192</td>
      <td align="center" bgcolor="mistyrose" title="Hoofdletter A acute"         >&Aacute; </td><td>Alt-0193</td>
      <td align="center" bgcolor="mistyrose" title="Hoofdletter A circumflex"    >&Acirc;  </td><td>Alt-0194</td>
      <td align="center" bgcolor="mistyrose" title="Hoofdletter A tilde"         >&Atilde; </td><td>Alt-0195</td>
      <td align="center" bgcolor="mistyrose" title="Hoofdletter A umlaut"        >&Auml;   </td><td>Alt-0196</td>
      <td align="center" bgcolor="mistyrose" title="Hoofdletter A ring"          >&Aring;  </td><td>Alt-0197</td>
      <td align="center" bgcolor="mistyrose" title="Hoofdletter AE verbinding"   >&AElig;  </td><td>Alt-0198</td>
  </tr>
  <tr><td align="center" bgcolor="mistyrose" title="kleine letter e grave"       >&egrave; </td><td>Alt-0232</td>
      <td align="center" bgcolor="mistyrose" title="kleine letter e acute"       >&eacute; </td><td>Alt-0233</td>
      <td align="center" bgcolor="mistyrose" title="kleine letter e circumflex"  >&ecirc;  </td><td>Alt-0234</td>
      <td> </td><td> </td>
      <td align="center" bgcolor="mistyrose" title="kleine letter e umlaut"      >&euml;   </td><td>Alt-0235</td>
      <td> </td><td> </td>
      <td> </td><td> </td>
  </tr>
  <tr><td align="center" bgcolor="mistyrose" title="Hoofdletter E grave"         >&Egrave; </td><td>Alt-0200</td>
      <td align="center" bgcolor="mistyrose" title="Hoofdletter E acute"         >&Eacute; </td><td>Alt-0201</td>
      <td align="center" bgcolor="mistyrose" title="Hoofdletter E circumflex"    >&Ecirc;  </td><td>Alt-0202</td>
      <td> </td><td> </td>
      <td align="center" bgcolor="mistyrose" title="Hoofdletter E umlaut"        >&Euml;   </td><td>Alt-0203</td>
      <td> </td><td> </td>
      <td> </td><td> </td>
  </tr>
  <tr><td align="center" bgcolor="mistyrose" title="kleine letter i grave"       >&igrave; </td><td>Alt-0236</td>
      <td align="center" bgcolor="mistyrose" title="kleine letter i acute"       >&iacute; </td><td>Alt-0237</td>
      <td align="center" bgcolor="mistyrose" title="kleine letter i circumflex"  >&icirc;  </td><td>Alt-0238</td>
      <td> </td><td> </td>
      <td align="center" bgcolor="mistyrose" title="kleine letter i umlaut"      >&iuml;   </td><td>Alt-0239</td>
      <td> </td><td> </td>
      <td> </td><td> </td>
  </tr>
  <tr><td align="center" bgcolor="mistyrose" title="Hoofdletter I grave"         >&Igrave; </td><td>Alt-0204</td>
      <td align="center" bgcolor="mistyrose" title="Hoofdletter I acute"         >&Iacute; </td><td>Alt-0205</td>
      <td align="center" bgcolor="mistyrose" title="Hoofdletter I circumflex"    >&Icirc;  </td><td>Alt-0206</td>
      <td> </td><td> </td>
      <td align="center" bgcolor="mistyrose" title="Hoofdletter I umlaut"        >&Iuml;   </td><td>Alt-0207</td>
      <th colspan=2 bgcolor="cornsilk">/ slash</th>
      <th colspan=2 bgcolor="cornsilk">&OElig; verbinding</th>
  </tr>
  <tr><td align="center" bgcolor="mistyrose" title="kleine letter o grave"       >&ograve; </td><td>Alt-0242</td>
      <td align="center" bgcolor="mistyrose" title="kleine letter o acute"       >&oacute; </td><td>Alt-0243</td>
      <td align="center" bgcolor="mistyrose" title="kleine letter o circumflex"  >&ocirc;  </td><td>Alt-0244</td>
      <td align="center" bgcolor="mistyrose" title="kleine letter o tilde"       >&otilde; </td><td>Alt-0245</td>
      <td align="center" bgcolor="mistyrose" title="kleine letter o umlaut"      >&ouml;   </td><td>Alt-0246</td>
      <td align="center" bgcolor="mistyrose" title="kleine letter o slash"       >&oslash; </td><td>Alt-0248</td>
      <td align="center" bgcolor="mistyrose" title="kleine letter oe verbinding" >&oelig;  </td><td>
<? if(!$utf8_site) { ?>
  Gebruik [oe]
<? } else { ?>
  Alt-0156
<? } ?>
      </td>
  </tr>
  <tr><td align="center" bgcolor="mistyrose" title="Hoofdletter O grave"       >&Ograve; </td><td>Alt-0210</td>
      <td align="center" bgcolor="mistyrose" title="Hoofdletter O acute"       >&Oacute; </td><td>Alt-0211</td>
      <td align="center" bgcolor="mistyrose" title="Hoofdletter O circumflex"  >&Ocirc;  </td><td>Alt-0212</td>
      <td align="center" bgcolor="mistyrose" title="Hoofdletter O tilde"       >&Otilde; </td><td>Alt-0213</td>
      <td align="center" bgcolor="mistyrose" title="Hoofdletter O umlaut"      >&Ouml;   </td><td>Alt-0214</td>
      <td align="center" bgcolor="mistyrose" title="Hoofdletter O slash"       >&Oslash; </td><td>Alt-0216</td>
      <td align="center" bgcolor="mistyrose" title="Hoofdletter OE verbinding" >&OElig;  </td><td>
<? if(!$utf8_site) { ?>
  Gebruik [OE]
<? } else { ?>
  Alt-0140
<? } ?>
      </td>
  </tr>
  <tr><td align="center" bgcolor="mistyrose" title="kleine letter u grave"      >&ugrave; </td><td>Alt-0249</td>
      <td align="center" bgcolor="mistyrose" title="kleine letter u acute"      >&uacute; </td><td>Alt-0250</td>
      <td align="center" bgcolor="mistyrose" title="kleine letter u circumflex" >&ucirc;  </td><td>Alt-0251</td>
      <td> </td><td> </td>
      <td align="center" bgcolor="mistyrose" title="kleine letter u umlaut"     >&uuml;   </td><td>Alt-0252</td>
      <td> </td><td> </td>
      <td> </td><td> </td>
  </tr>
  <tr><td align="center" bgcolor="mistyrose" title="Hoofdletter U grave"       >&Ugrave; </td><td>Alt-0217</td>
      <td align="center" bgcolor="mistyrose" title="Hoofdletter U acute"       >&Uacute; </td><td>Alt-0218</td>
      <td align="center" bgcolor="mistyrose" title="Hoofdletter U circumflex"  >&Ucirc;  </td><td>Alt-0219</td>
      <td> </td><td> </td>
      <td align="center" bgcolor="mistyrose" title="Hoofdletter U umlaut"      >&Uuml;   </td><td>Alt-0220</td>
      <th colspan=2 bgcolor="cornsilk">munteenheid     </th>
      <th colspan=2 bgcolor="cornsilk">wiskunde  </th>
  </tr>
  <tr><td> </td><td> </td>
      <td> </td><td> </td>
      <td> </td><td> </td>
      <td align="center" bgcolor="mistyrose" title="kleine letter n tilde"   >&ntilde; </td><td>Alt-0241</td>
      <td align="center" bgcolor="mistyrose" title="kleine letter y umlaut"  >&yuml;   </td><td>Alt-0255</td>
      <td align="center" bgcolor="mistyrose" title="Cents"                   >&cent;   </td><td>Alt-0162</td>
      <td align="center" bgcolor="mistyrose" title="plus/min"                >&plusmn; </td><td>Alt-0177</td>
  </tr>
  <tr><td> </td><td> </td>
      <td> </td><td> </td>
      <td> </td><td> </td>
      <td align="center" bgcolor="mistyrose" title="Hoofdletter N tilde"     >&Ntilde; </td><td>Alt-0209</td>
      <td align="center" bgcolor="mistyrose" title=""></td><td></td>
      <td align="center" bgcolor="mistyrose" title="Pounds"                  >&pound;  </td><td>Alt-0163</td>
      <td align="center" bgcolor="mistyrose" title="Vermenigvuldiging"       >&times;  </td><td>Alt-0215</td>
  </tr>
  <tr><th colspan=2 bgcolor="cornsilk">&ccedil;edille </th>
      <th colspan=2 bgcolor="cornsilk">IJslands    </th>
      <th colspan=2 bgcolor="cornsilk">tekens        </th>
      <th colspan=2 bgcolor="cornsilk">accenten      </th>
      <th colspan=2 bgcolor="cornsilk">interpunctie  </th>
      <td align="center" bgcolor="mistyrose" title="Yen"                     >&yen;    </td><td>Alt-0165</td>
      <td align="center" bgcolor="mistyrose" title="Deling"                  >&divide; </td><td>Alt-0247</td>
  </tr>
  <tr><td align="center" bgcolor="mistyrose" title="kleine letter c cedille" >&ccedil; </td><td>Alt-0231</td>
      <td align="center" bgcolor="mistyrose" title="Hoofdletter Thorn"       >&THORN;  </td><td>Alt-0222</td>
      <td align="center" bgcolor="mistyrose" title="Copyright"               >&copy;   </td><td>Alt-0169</td>
      <td align="center" bgcolor="mistyrose" title="acute accent"            >&acute;  </td><td>Alt-0180</td>
      <td align="center" bgcolor="mistyrose" title="Omgekeerd Vraagteken"    >&iquest; </td><td>Alt-0191</td>
      <td align="center" bgcolor="mistyrose" title="Dollars"                 >&#036;   </td><td>Alt-0036</td>
      <td align="center" bgcolor="mistyrose" title="Logical Not"             >&not;    </td><td>Alt-0172</td>
  </tr>
  <tr><td align="center" bgcolor="mistyrose" title="Hoofdletter C cedille"   >&Ccedil; </td><td>Alt-0199</td>
      <td align="center" bgcolor="mistyrose" title="kleine letter thorn"     >&thorn;  </td><td>Alt-0254</td>
      <td align="center" bgcolor="mistyrose" title="Registratie Teken"       >&reg;    </td><td>Alt-0174</td>
      <td align="center" bgcolor="mistyrose" title="umlaut accent"           >&uml;    </td><td>Alt-0168</td>
      <td align="center" bgcolor="mistyrose" title="Omgekeerd Uitroepteken"  >&iexcl;  </td><td>Alt-0161</td>
      <td align="center" bgcolor="mistyrose" title="Algemene munteenheid"    >&curren; </td><td>Alt-0164</td>
      <td align="center" bgcolor="mistyrose" title="Graden"                  >&deg;    </td><td>Alt-0176</td>
  </tr>
  <tr><th colspan=2 bgcolor="cornsilk">superscripts        </th>
      <td align="center" bgcolor="mistyrose" title="Hoofdletter Eth"         >&ETH;    </td><td>Alt-0208</td>
      <td align="center" bgcolor="mistyrose" title="Trademark"               >&trade;  </td><td>Alt-0153</td>
      <td align="center" bgcolor="mistyrose" title="macron accent"           >&macr;   </td><td>Alt-0175</td>
      <td align="center" bgcolor="mistyrose" title="Guillemet links (Ganzenvoetjes)"   >&laquo;  </td><td>Alt-0171</td>
      <td> </td><td> </td>
      <td align="center" bgcolor="mistyrose" title="Micro"                   >&micro;  </td><td>Alt-0181</td>
  </tr>
  <tr><td align="center" bgcolor="mistyrose" title="superscript 1"             >&sup1;   </td><td>Alt-0185</td>
      <td align="center" bgcolor="mistyrose" title="kleine letter eth"         >&eth;    </td><td>Alt-0240</td>
      <td align="center" bgcolor="mistyrose" title="Alinea"                    >&para;   </td><td>Alt-0182</td>
      <td align="center" bgcolor="mistyrose" title="cedille"                   >&cedil;  </td><td>Alt-0184</td>
      <td align="center" bgcolor="mistyrose" title="Guillemet rechts (Ganzenvoetjes)"  >&raquo;  </td><td>Alt-0187</td>
      <th colspan=2 bgcolor="cornsilk" title="vb: eerste als 1&ordm; of 1&ordf;">rangorde-tekens</th>
      <td align="center" bgcolor="mistyrose" title="1/4 Breuk"                 >&frac14; <sup><small>1</small></sup></td><td>Alt-0188</td>
  </tr>
  <tr><td align="center" bgcolor="mistyrose" title="superscript 2"             >&sup2;   </td><td>Alt-0178</td>
      <th colspan=2 bgcolor="cornsilk">sz verbinding    </th>
      <td align="center" bgcolor="mistyrose" title="Paragraaf"                 >&sect;   </td><td>Alt-0167</td>
      <td> </td><td> </td>
      <td align="center" bgcolor="mistyrose" title="'Zwevende' punt"           >&middot; </td><td>Alt-0183</td>
      <td align="center" bgcolor="mistyrose" title="Mannelijk rangorde-teken"  >&ordm;   </td><td>Alt-0186</td>
      <td align="center" bgcolor="mistyrose" title="1/2 Breuk"                 >&frac12; <sup><small>1</small></sup></td><td>Alt-0189</td>
  </tr>
  <tr><td align="center" bgcolor="mistyrose" title="superscript 3"             >&sup3;   </td><td>Alt-0179</td>
      <td align="center" bgcolor="mistyrose" title="sz verbinding"             >&szlig;  </td><td>Alt-0223</td>
      <td align="center" bgcolor="mistyrose" title="Gebroken Verticale Lijn"   >&brvbar; </td><td>Alt-0166</td>
      <td> </td><td> </td>
      <td align="center" bgcolor="mistyrose" title="sterretje"                 >&#042;   </td><td>Alt-0042</td>
      <td align="center" bgcolor="mistyrose" title="Vrouwelijk rangorde-teken" >&ordf;   </td><td>Alt-0170</td>
      <td align="center" bgcolor="mistyrose" title="3/4 Breuk"                 >&frac34; <sup><small>1</small></sup></td><td>Alt-0190</td>
  </tr>
  </tbody>
</table>
<p><sup><small>1</small></sup>Gebruik alsjeblieft de symbolen voor breuken niet, tenzij het in de
   <a href="#comments">Project Comments</a> specifiek gevraagd wordt.
   Volg de richtlijnen voor <a href="#fract_s">Breuken</a> (1/2, 1/4, 3/4 enz.).
</p>

<p><b>Voor de Apple Macintosh</b>:
</p>
<ul compact>
  <li>Je kunt het "Key Caps" programma gebruiken.<br>
      In OS 9 &amp; daarvoor, vind je dit programma in het Apple Menu.
      In OS X tot en met 10.2, vind je het in de map Toepassingen, Utilities.<br>
      Dit programma geeft je een plaatje van je toetsenbord. Drukken op shift, opt, command
      of combinaties van deze toetsen laat zien hoe je elk teken kunt produceren.
      Je kunt dit gebruiken om te zien hoe je een teken kunt produceren, of je kunt het
      teken hiervandaan knippen en in de formatting interface plakken.</li>
  <li>In OS X 10.3 en hoger, is deze functie beschikbaar vanuit het Input menu
     (het uitklapmenu dat vast zit aan je locale's flag icon in de menubalk. Het heet
     "Show Keyboard Viewer". Als het niet in je Input menu zit, of je hebt dat menu helemaal niet,
     kun je het openen door System Preferences te openen, het "International panel", en dan
     "Input menu" te kiezen. Zorg dat "Show input menu in menu bar" geselecteerd is.
     In de spreadsheet view, zorg dan het hokje voor "Keyboard Viewer" aan, naast andere
     input locales die je gebruikt.
  </li>
  <li>De uitklapmenu's in de formatting interface.
  </li>
  <li>Of je kunt de Apple Opt- sneltoets codes voor deze tekens gebruiken.
      <br>Als je eenmaal aan de codes gewend bent, gaat dit een stuk sneller dan knippen &amp; plakken.
      <br>Houd de Opt-toets vast en typ het symbool voor het accent. Typ daarna de letter die een accent moet
          krijgen. Voor sommige codes hoef je alleen de Opt-toets vast te houden en het symbool te typen.
      <br>Deze instructies werken voor de US-Engelse toetsenbord indeling. Bij andere indelingen werken ze misschien niet.
      <br>De tabel hieronder laat zien welke codes we gebruiken.
          (<a href="charapp.pdf">Printer-vriendelijke versie van deze tabel</a>).
      <br>Gebruik geen andere speciale tekens, tenzij de Project Manager dat vraagt in de <a href="#comments">Project Comments</a>.
  </li>
</ul>

<br>
<a name="a_chars_mac"></a>
<table align="center" border="6" rules="all" summary="Apple Mac Sneltoetsen">
  <tbody>
  <tr bgcolor="cornsilk"  >
      <th colspan=14>Apple Mac Sneltoetsen voor Latin-1 symbolen</th>
  <tr bgcolor="cornsilk"  >
      <th colspan=2>` grave</th>
      <th colspan=2>&acute; acute (aigu)</th>
      <th colspan=2>^ circumflex</th>
      <th colspan=2>~ tilde</th>
      <th colspan=2>&uml; umlaut</th>
      <th colspan=2>&deg; ring</th>
      <th colspan=2>&AElig; verbinding</th>
  </tr>
  <tr><td align="center" bgcolor="mistyrose" title="kleine letter a grave"       >&agrave; </td><td>Opt-`, a</td>
      <td align="center" bgcolor="mistyrose" title="kleine letter a acute"       >&aacute; </td><td>Opt-e, a</td>
      <td align="center" bgcolor="mistyrose" title="kleine letter a circumflex"  >&acirc;  </td><td>Opt-i, a</td>
      <td align="center" bgcolor="mistyrose" title="kleine letter a tilde"       >&atilde; </td><td>Opt-n, a</td>
      <td align="center" bgcolor="mistyrose" title="kleine letter a umlaut"      >&auml;   </td><td>Opt-u, a</td>
      <td align="center" bgcolor="mistyrose" title="kleine letter a ring"        >&aring;  </td><td>Opt-a   </td>
      <td align="center" bgcolor="mistyrose" title="kleine letter ae verbinding" >&aelig;  </td><td>Opt-'   </td>
  </tr>
  <tr><td align="center" bgcolor="mistyrose" title="Hoofdletter A grave"         >&Agrave; </td><td>Opt-`, A</td>
      <td align="center" bgcolor="mistyrose" title="Hoofdletter A acute"         >&Aacute; </td><td>Opt-e, A</td>
      <td align="center" bgcolor="mistyrose" title="Hoofdletter A circumflex"    >&Acirc;  </td><td>Opt-i, A</td>
      <td align="center" bgcolor="mistyrose" title="Hoofdletter A tilde"         >&Atilde; </td><td>Opt-n, A</td>
      <td align="center" bgcolor="mistyrose" title="Hoofdletter A umlaut"        >&Auml;   </td><td>Opt-u, A</td>
      <td align="center" bgcolor="mistyrose" title="Hoofdletter A ring"          >&Aring;  </td><td>Opt-A   </td>
      <td align="center" bgcolor="mistyrose" title="Hoofdletter AE verbinding"   >&AElig;  </td><td>Opt-"   </td>
  </tr>
  <tr><td align="center" bgcolor="mistyrose" title="kleine letter e grave"       >&egrave; </td><td>Opt-`, e</td>
      <td align="center" bgcolor="mistyrose" title="kleine letter e acute"       >&eacute; </td><td>Opt-e, e</td>
      <td align="center" bgcolor="mistyrose" title="kleine letter e circumflex"  >&ecirc;  </td><td>Opt-i, e</td>
      <td> </td><td> </td>
      <td align="center" bgcolor="mistyrose" title="kleine letter e umlaut"      >&euml;   </td><td>Opt-u, e</td>
      <td> </td><td> </td>
      <td> </td><td> </td>
  </tr>
  <tr><td align="center" bgcolor="mistyrose" title="Hoofdletter E grave"         >&Egrave; </td><td>Opt-`, E</td>
      <td align="center" bgcolor="mistyrose" title="Hoofdletter E acute"         >&Eacute; </td><td>Opt-e, E</td>
      <td align="center" bgcolor="mistyrose" title="Hoofdletter E circumflex"    >&Ecirc;  </td><td>Opt-i, E</td>
      <td> </td><td> </td>
      <td align="center" bgcolor="mistyrose" title="Hoofdletter E umlaut"        >&Euml;   </td><td>Opt-u, E</td>
      <td> </td><td> </td>
      <td> </td><td> </td>
  </tr>
  <tr><td align="center" bgcolor="mistyrose" title="kleine letter i grave"       >&igrave; </td><td>Opt-`, i</td>
      <td align="center" bgcolor="mistyrose" title="kleine letter i acute"       >&iacute; </td><td>Opt-e, i</td>
      <td align="center" bgcolor="mistyrose" title="kleine letter i circumflex"  >&icirc;  </td><td>Opt-i, i</td>
      <td> </td><td> </td>
      <td align="center" bgcolor="mistyrose" title="kleine letter i umlaut"      >&iuml;   </td><td>Opt-u, i</td>
      <td> </td><td> </td>
      <td> </td><td> </td>
  </tr>
  <tr><td align="center" bgcolor="mistyrose" title="Hoofdletter I grave"         >&Igrave; </td><td>Opt-`, I</td>
      <td align="center" bgcolor="mistyrose" title="Hoofdletter I acute"         >&Iacute; </td><td>Opt-e, I</td>
      <td align="center" bgcolor="mistyrose" title="Hoofdletter I circumflex"    >&Icirc;  </td><td>Opt-i, I</td>
      <td> </td><td> </td>
      <td align="center" bgcolor="mistyrose" title="Hoofdletter I umlaut"        >&Iuml;   </td><td>Opt-u, I</td>
      <th colspan=2 bgcolor="cornsilk">/ slash</th>
      <th colspan=2 bgcolor="cornsilk">&OElig; verbinding</th>
  </tr>
  <tr><td align="center" bgcolor="mistyrose" title="kleine letter o grave"       >&ograve; </td><td>Opt-`, o</td>
      <td align="center" bgcolor="mistyrose" title="kleine letter o acute"       >&oacute; </td><td>Opt-e, o</td>
      <td align="center" bgcolor="mistyrose" title="kleine letter o circumflex"  >&ocirc;  </td><td>Opt-i, o</td>
      <td align="center" bgcolor="mistyrose" title="kleine letter o tilde"       >&otilde; </td><td>Opt-n, o</td>
      <td align="center" bgcolor="mistyrose" title="kleine letter o umlaut"      >&ouml;   </td><td>Opt-u, o</td>
      <td align="center" bgcolor="mistyrose" title="kleine letter o slash"       >&oslash; </td><td>Opt-o   </td>
      <td align="center" bgcolor="mistyrose" title="kleine letter oe verbinding" >&oelig;  </td><td>
<? if(!$utf8_site) { ?>
  Gebruik [oe]
<? } else { ?>
  Opt-q
<? } ?>
      </td>
  </tr>
  <tr><td align="center" bgcolor="mistyrose" title="Hoofdletter O grave"         >&Ograve; </td><td>Opt-`, O</td>
      <td align="center" bgcolor="mistyrose" title="Hoofdletter O acute"         >&Oacute; </td><td>Opt-e, O</td>
      <td align="center" bgcolor="mistyrose" title="Hoofdletter I circumflex"    >&Ocirc;  </td><td>Opt-i, O</td>
      <td align="center" bgcolor="mistyrose" title="Hoofdletter O tilde"         >&Otilde; </td><td>Opt-n, O</td>
      <td align="center" bgcolor="mistyrose" title="Hoofdletter O umlaut"        >&Ouml;   </td><td>Opt-u, O</td>
      <td align="center" bgcolor="mistyrose" title="Hoofdletter O slash"         >&Oslash; </td><td>Opt-O   </td>
      <td align="center" bgcolor="mistyrose" title="Hoofdletter OE verbinding"   >&OElig;  </td><td>
<? if(!$utf8_site) { ?>
  Gebruik [OE]
<? } else { ?>
  Opt-Q
<? } ?>
      </td>
  </tr>
  <tr><td align="center" bgcolor="mistyrose" title="kleine letter u grave"       >&ugrave; </td><td>Opt-`, u</td>
      <td align="center" bgcolor="mistyrose" title="kleine letter u acute"       >&uacute; </td><td>Opt-e, u</td>
      <td align="center" bgcolor="mistyrose" title="kleine letter u circumflex"  >&ucirc;  </td><td>Opt-i, u</td>
      <td> </td><td> </td>
      <td align="center" bgcolor="mistyrose" title="kleine letter u umlaut"      >&uuml;   </td><td>Opt-u, u</td>
      <td> </td><td> </td>
      <td> </td><td> </td>
  </tr>
  <tr><td align="center" bgcolor="mistyrose" title="Hoofdletter U grave"         >&Ugrave; </td><td>Opt-`, U</td>
      <td align="center" bgcolor="mistyrose" title="Hoofdletter U acute"         >&Uacute; </td><td>Opt-e, U</td>
      <td align="center" bgcolor="mistyrose" title="Hoofdletter U circumflex"    >&Ucirc;  </td><td>Opt-i, U</td>
      <td> </td><td> </td>
      <td align="center" bgcolor="mistyrose" title="Hoofdletter U umlaut"        >&Uuml;   </td><td>Opt-u, U</td>
      <th colspan=2 bgcolor="cornsilk">munteenheid     </th>
      <th colspan=2 bgcolor="cornsilk">wiskunde  </th>
  </tr>
  <tr><td> </td><td> </td>
      <td> </td><td> </td>
      <td> </td><td> </td>
      <td align="center" bgcolor="mistyrose" title="kleine letter n tilde"   >&ntilde; </td><td>Opt-n, n</td>
      <td align="center" bgcolor="mistyrose" title="kleine letter y umlaut"  >&yuml;   </td><td>Opt-u, y</td>
      <td align="center" bgcolor="mistyrose" title="Cents"                   >&cent;   </td><td>Opt-4   </td>
      <td align="center" bgcolor="mistyrose" title="plus/min"                >&plusmn; </td><td>Shift-Opt-=</td>
  </tr>
  <tr><td> </td><td> </td>
      <td> </td><td> </td>
      <td> </td><td> </td>
      <td align="center" bgcolor="mistyrose" title="Hoofdletter N tilde"     >&Ntilde; </td><td>Opt-n, N</td>
      <td align="center" bgcolor="mistyrose" title=""></td><td></td>
      <td align="center" bgcolor="mistyrose" title="Pounds"                  >&pound;  </td><td>Opt-3   </td>
      <td align="center" bgcolor="mistyrose" title="Vermenigvuldiging"       >&times;  </td><td>(geen)&nbsp;&dagger;</td>
  </tr>
  <tr><th colspan=2 bgcolor="cornsilk">&ccedil;edille </th>
      <th colspan=2 bgcolor="cornsilk">IJslands    </th>
      <th colspan=2 bgcolor="cornsilk">tekens        </th>
      <th colspan=2 bgcolor="cornsilk">accenten      </th>
      <th colspan=2 bgcolor="cornsilk">interpunctie  </th>
      <td align="center" bgcolor="mistyrose" title="Yen"                     >&yen;    </td><td>Opt-y   </td>
      <td align="center" bgcolor="mistyrose" title="Deling"                  >&divide; </td><td>Opt-/   </td>
  </tr>
  <tr><td align="center" bgcolor="mistyrose" title="kleine letter c cedille" >&ccedil; </td><td>Opt-c   </td>
      <td align="center" bgcolor="mistyrose" title="Hoofdletter Thorn"       >&THORN;  </td><td>(geen)&nbsp;&Dagger;</td>
      <td align="center" bgcolor="mistyrose" title="Copyright"               >&copy;   </td><td>Opt-g   </td>
      <td align="center" bgcolor="mistyrose" title="acute accent"            >&acute;  </td><td>Opt-E   </td>
      <td align="center" bgcolor="mistyrose" title="Omgekeerd Vraagteken"    >&iquest; </td><td>Opt-?   </td>
      <td align="center" bgcolor="mistyrose" title="Dollars"                 >&#036;   </td><td>Shift-4</td>
      <td align="center" bgcolor="mistyrose" title="Logical Not"             >&not;    </td><td>Opt-l   </td>
  </tr>
  <tr><td align="center" bgcolor="mistyrose" title="Hoofdletter C cedille"   >&Ccedil; </td><td>Opt-C   </td>
      <td align="center" bgcolor="mistyrose" title="kleine letter thorn"     >&thorn;  </td><td>(geen)&nbsp;&Dagger;  </td>
      <td align="center" bgcolor="mistyrose" title="Registratie Teken"       >&reg;    </td><td>Opt-r   </td>
      <td align="center" bgcolor="mistyrose" title="umlaut accent"           >&uml;    </td><td>Opt-U   </td>
      <td align="center" bgcolor="mistyrose" title="Omgekeerd Uitroepteken"  >&iexcl;  </td><td>Opt-1   </td>
      <td align="center" bgcolor="mistyrose" title="Algemene munteenheid"    >&curren; </td><td>(geen)&nbsp;&Dagger;  </td>
      <td align="center" bgcolor="mistyrose" title="Graden"                  >&deg;    </td><td>Shift-Opt-8   </td>
  </tr>
  <tr><th colspan=2 bgcolor="cornsilk">superscripts</th>
      <td align="center" bgcolor="mistyrose" title="Hoofdletter Eth"         >&ETH;    </td><td>(geen)&nbsp;&Dagger;  </td>
      <td align="center" bgcolor="mistyrose" title="Trademark"               >&trade;  </td><td>Opt-2   </td>
      <td align="center" bgcolor="mistyrose" title="Macron accent"           >&macr;   </td><td>Shift-Opt-,</td>
      <td align="center" bgcolor="mistyrose" title="Guillemet links (Ganzenvoetjes)"   >&laquo;  </td><td>Opt-\   </td>
      <td> </td><td> </td>
      <td align="center" bgcolor="mistyrose" title="Micro"                   >&micro;  </td><td>Opt-m   </td>
  </tr>
  <tr><td align="center" bgcolor="mistyrose" title="superscript 1"             >&sup1;   </td><td>(geen)&nbsp;&Dagger;  </td>
      <td align="center" bgcolor="mistyrose" title="kleine letter eth"         >&eth;    </td><td>(geen)&nbsp;&Dagger;  </td>
      <td align="center" bgcolor="mistyrose" title="Alinea"                    >&para;   </td><td>Opt-7   </td>
      <td align="center" bgcolor="mistyrose" title="cedille"                   >&cedil;  </td><td>Opt-Z   </td>
      <td align="center" bgcolor="mistyrose" title="Guillemet rechts (Ganzenvoetjes)"  >&raquo;  </td><td>Shift-Opt-\</td>
      <th colspan=2 bgcolor="cornsilk" title="vb: eerste als 1&ordm; of 1&ordf;">rangorde-tekens </th>
      <td align="center" bgcolor="mistyrose" title="1/4 Breuk"                 >&frac14; </td><td>(geen)&nbsp;&Dagger;<sup><small>1</small></sup>  </td>
  </tr>
  <tr><td align="center" bgcolor="mistyrose" title="superscript 2"             >&sup2;   </td><td>(geen)&nbsp;&Dagger;  </td>
      <th colspan=2 bgcolor="cornsilk">sz verbinding </th>
      <td align="center" bgcolor="mistyrose" title="Paragraaf"                 >&sect;   </td><td>Opt-6   </td>
      <td> </td><td> </td>
      <td align="center" bgcolor="mistyrose" title="'Zwevende' punt"           >&middot; </td><td>Shift-Opt-9  </td>
      <td align="center" bgcolor="mistyrose" title="Mannelijk rangorde-teken"  >&ordm;   </td><td>Opt-0   </td>
      <td align="center" bgcolor="mistyrose" title="1/2 Breuk"                 >&frac12; </td><td>(none)&nbsp;&Dagger;<sup><small>1</small></sup>  </td>
  </tr>
  <tr><td align="center" bgcolor="mistyrose" title="superscript 3"             >&sup3;   </td><td>(none)&nbsp;&Dagger;  </td>
      <td align="center" bgcolor="mistyrose" title="sz verbinding"             >&szlig;  </td><td>Opt-s   </td>
      <td align="center" bgcolor="mistyrose" title="Gebroken Verticale Lijn"   >&brvbar; </td><td>(geen)&nbsp;&Dagger;  </td>
      <td> </td><td> </td>
      <td align="center" bgcolor="mistyrose" title="sterretje"                 >&#042;   </td><td>Shift-8 </td>
      <td align="center" bgcolor="mistyrose" title="Vrouwelijk rangorde-teken" >&ordf;   </td><td>Opt-9   </td>
      <td align="center" bgcolor="mistyrose" title="3/4 Breuk"                 >&frac34; </td><td>(geen)&nbsp;&Dagger;<sup><small>1</small></sup>  </td>
  </tr>
  </tbody>
</table>
<p>&Dagger;&nbsp;Noot: geen sneltoetsen, gebruik het uitklapmenu.
</p>
<p><sup><small>1</small></sup>Gebruik alsjeblieft de symbolen voor breuken niet, tenzij het in de
   <a href="#comments">Project Comments</a> specifiek gevraagd wordt.
   Volg de richtlijnen voor <a href="#fract_s">Breuken</a> (1/2, 1/4, 3/4 enz.).
</p>

<h3><a name="d_chars">Lettertekens met Diakritische Tekens</a>
 (<i><a href="document.php#d_chars">Characters with Diacritical marks</a></i>)</h3>
<p>In sommige projecten vind je lettertekens met speciale tekens boven of onder de letters van A tot Z.
   Deze heten <i>diakritische tekens</i> en geven een bijzondere uitspraak van de letter aan.
<? if($utf8_site) { ?>
</p>
<p>Als een dergelijk karakter in Unicode niet bestaat, dan moet het worden
   ingegeven door <i>gecombineerde diakritische tekens</i>: dit zijn symbolen in Unicode die niet
   op zichzelf voorkomen, maar die boven (of onder) de letter waar ze achter gezet zijn, komen.
   Ze kunnen worden ingegeven door eerst de basisletter in te geven, en daarna het combinatieteken,
   waarbij je de applets en programma's gebruikt die <a href="#a_chars">hierboven</a> vernoemd worden.
</p>
<p>Op sommige systemen zie je de diakritische tekens niet op de plaats waar ze horen,
   maar bijvoorbeeld rechts daarvan. Je moet ze dan toch gebruiken, want mensen met andere 
   systemen zullen ze wel goed te zien krijgen. Als je om de een of andere reden de gecombineerde 
   tekens niet goed kunt zien, markeer zo'n letter dan met <tt>*</tt>. Overigens bestaan er ook 
   <i>Modifier diacritical marks</i>; deze mogen niet gebruikt worden.</i>
</p>
<? } else { ?>
   Voor het formatteren geven we ze in onze ASCII tekst aan door speciale codes,
   bijvoorbeeld &#259; wordt <tt>[)a]</tt> voor een breve (het accent als een omgekeerd dakje)
   boven een a, of <tt>[a)]</tt> voor een breve onder de a. 
</p>
<p>Zet er in ieder geval vierkante haken (<tt>[&nbsp;]</tt>) eromheen, zodat de Post-Processor
   weet over welke letter het gaat. Hij of zij zal het geheel vervangen door het symbool dat werkt
   in elke versie van de tekst die hij/zij produceert, zoals 7-bit ASCII, 8-bit, Unicode, html enz. 
</p>
<p>Houd in de gaten dat onze standaard Latin-1 tekenset al een aantal tekens met een diakritisch teken bevat.
   <b>Gebruik dan het teken uit Latin-1 (zie <a href="#a_chars">hier</a>) dat beschikbaar is vanuit
   de uitklapmenu's in de proofreading interface.</b>
</p>
<!-- END RR -->

<p>De tabel hieronder bevat een lijst van de speciale coderingen zoals we ze op dit moment gebruiken:<br>
   De "x" staat voor een letter met een diakritisch teken..<br>
   Gebruik bij het formatteren de letter die in de tekst staat, en niet de <tt>x</tt> uit de voorbeelden.
</p>

<!--
  diakritisch teken        erboven eronder
makron (rechte lijn)         [=x]   [x=]
2 stippen (trema, umlaut)    [:x]   [x:]
1 stip                       [.x]   [x.]
accent grave                 ['x]   [x'] or [/x] [x/]
accent acute (aigu)          [`x]   [x`] or [\x] [x\]
circumflex                   [^x]   [x^]
caron (v-vorm symbool)       [vx]   [xv]
breve (u-vorm symbool)       [)x]   [x)]
tilde                        [~x]   [x~]
cedille                      [,x]   [x,]
-->

<table align="center" border="6" rules="all" summary="Diacriticals">
  <tbody>
  <tr bgcolor="cornsilk">
      <th colspan=4>Symbolen voor het Formatteren van Diakritische Tekens</th>
  <tr bgcolor="cornsilk">
      <th>diakritisch teken</th>
      <th>voorbeeld</th>
      <th>erboven</th>
      <th>eronder</th>
   </tr>
  <tr><td>makron (rechte lijn)</td>
      <td align="center">&macr;</td>
      <td align="center"><tt>[=x]</tt></td>
      <td align="center"><tt>[x=]</tt></td>
      </tr>
  <tr><td>2 stippen (trema, umlaut)</td>
      <td align="center">&uml;</td>
      <td align="center"><tt>[:x]</tt></td>
      <td align="center"><tt>[x:]</tt></td>
      </tr>
  <tr><td>1 stip</td>
      <td align="center">&middot;</td>
      <td align="center"><tt>[.x]</tt></td>
      <td align="center"><tt>[x.]</tt></td>
      </tr>
  <tr><td>accent grave</td>
      <td align="center">`</td>
      <td align="center"><tt>[`x]</tt> or <tt>[\x]</tt></td>
      <td align="center"><tt>[x`]</tt> or <tt>[x\]</tt></td>
      </tr>
  <tr><td>accent acute (aigu)</td>
      <td align="center">&acute;</td>
      <td align="center"><tt>['x]</tt> or <tt>[/x]</tt></td>
      <td align="center"><tt>[x']</tt> or <tt>[x/]</tt></td>
      </tr>
  <tr><td>circumflex</td>
      <td align="center">&circ;</td>
      <td align="center"><tt>[^x]</tt></td>
      <td align="center"><tt>[x^]</tt></td>
      </tr>
  <tr><td>caron (symbool in de vorm van een v)</td>
      <td align="center"><font size="-2">&or;</font></td>
      <td align="center"><tt>[vx]</tt></td>
      <td align="center"><tt>[xv]</tt></td>
      </tr>
  <tr><td>breve (symbool in de vorm van een u)</td>
      <td align="center"><font size="-2">&cup;</font></td>
      <td align="center"><tt>[)x]</tt></td>
      <td align="center"><tt>[x)]</tt></td>
      </tr>
  <tr><td>tilde</td>
      <td align="center">&tilde;</td>
      <td align="center"><tt>[~x]</tt></td>
      <td align="center"><tt>[x~]</tt></td>
      </tr>
  <tr><td>cedille</td>
      <td align="center">&cedil;</td>
      <td align="center"><tt>[,x]</tt></td>
      <td align="center"><tt>[x,]</tt></td>
      </tr>
  </tbody>
</table>
<? } ?>

<h3><a name="f_chars">Niet-Latijnse lettertekens</a>
 (<i><a href="document.php#f_chars">Non-Latin Characters</a></i>)</h3>
<p>Sommige teksten bevatten tekst die in niet-Latijnse lettertekens is gedrukt, dus andere letters dan
   de Latijnse A...Z. Het kan dan gaan om Griekse, Cyrillische (dat wordt gebruikt in Russisch,
   Slavisch en andere talen), Hebreeuwse of Arabische letters.
</p>
<? if(strcasecmp($charset,"UTF-8")) { ?>
<p>Voor het Grieks moet je proberen elke letter om te zetten in de equivalente Latijnse letter(s).
   (Transliteration). In de formatting interface vindt je een "Greek transliteration" hulpmiddel,
   waar dit heel gemakkelijk mee gaat.
</p>
<p>Klik op de "Greek Transliterator" knop bijna onderaan de formatting interface.
   Het hulpmiddel verschijnt in een pop-up. In het gereedschap, klik op de Griekse letters die je
   ziet in het woord of de zin die je aan het omzetten bent. De bijpassende Latin-1 tekens verschijnen
   in de tekstbox. Als je klaar bent, knip en plak je de omgezette tekst in de bladzijde die je aan het
   formatteren bent. Zet om de omgezette tekst de volgende markering: <tt>[Greek:&nbsp;</tt> en <tt>]</tt>.
   Bijvoorbeeld: <b>&Beta;&iota;&beta;&lambda;&omicron;&sigmaf;</b> wordt
   <tt>[Greek: Biblos]</tt>. ("Boek"&mdash;erg toepasselijk bij DP!)
</p>
<p>Als je onzeker bent over je omzetting, markeer hem dan met <tt>**</tt> om te zorgen dat de
   aandacht van de volgende formatteerder of de Post-Processor er op gevestigd wordt.
</p>
<p>Andere talen, zoals Cyrillisch, Hebreeuws of Arabisch kunnen niet zo gemakkelijk worden omgezet.
   Dan moet je de volgende markeringen om de tekst heen zetten:
   <tt>[Cyrillic:&nbsp;**]</tt>, <tt>[Hebrew:&nbsp;**]</tt> of <tt>[Arabic:&nbsp;**]</tt>.
   Laat de tekst zoals hij gescand is.
   Zorg wel dat de <tt>**</tt> erbij staan, zodat de Post-Processor het later op kan lossen.
</p>
<!-- END RR -->

<ul compact>
  <li>Grieks: <a href="<? echo $PG_greek_howto_url; ?>">Greek HOWTO</a> (van het Project Gutenberg)
      of gebruik de "Greek Transliterator" pop-up in de formatting interface.
  </li>
  <li>Cyrillisch: Er is een standaard omzetschema voor Cyrillisch. We bevelen aan dat je dit alleen maar gebruikt
      als je de taal waar het om gaat, vloeiend beheerst. Markeer de tekst anders zoals boven omschreven.
      Mogelijk vind je deze <a href="http://learningrussian.com/transliteration.htm">Transliteration Tabel</a> nuttig.
  </li>
  <li>Hebreeuws en Arabisch: niet aanbevolen tenzij je de taal vloeiend beheerst. Het omzetten
      van deze talen stuit op grote moeilijkheden en noch <a href="..">Distributed Proofreaders</a>
      noch <a href="<? echo $PG_home_url; ?>">Project Gutenberg</a> heeft al een standaard methode gekozen.
  </li>
</ul>
<? } else { ?>
<p>Deze lettertekens moeten, net als de Latijnse letters, in de tekst worden gezet,
   (<b>dus NIET in de Latijnse letters worden omgezet!</b>)
</p>
<p>Als een tekst helemaal in een niet-Latijns schrift geschreven is, dan is het aan te bevelen
   om een toetsenbord driver te installeren, die de taal ondersteunt.
   Raadpleeg de handleiding van je besturingssysteem voor instructies.
</p>
<p>Als het niet-Latijnse schrift alleen incidenteel voorkomt, dan kun je ook een apart programma
   gebruiken om de letters in te geven. Zie <a href="#a_chars">hierboven</a> voor enige van
   deze programma's.
</p>
<p>Als je niet helemaal zeker bent van een teken of accent, markeer het dan met een <tt>*</tt>
   om te zorgen dat de volgende formatteerder of de Post-Processor het opmerkt.
</p>
<p>Zet om schrift dat niet zo gemakkelijk kan worden ingegeven, zoals Arabisch, passende markering:
   <tt>[Arabic:&nbsp;**]</tt> en laat het zoals in de scan. Zorg voor de <tt>**</tt> zodat
   de Post-Processor het later kan oplossen.</i>
</p>
<? } ?>

<h3><a name="fract_s">Breuken</a>
 (<i><a href="document.php#fract_s">Fractions</a></i>)</h3>
<p>Formatteer <b>breuken</b> als volgt: <tt>2&frac12;</tt> wordt <tt>2-1/2</tt>.
   Het streepje voorkomt dat het hele getal en de breuk tijdens het post-processen
   bij het <span style="border-bottom: 1px dotted green;"  title="rewrap: het opnieuw aanbrengen van regelafbrekingen"><i>rewrappen</i></span>
   van elkaar worden gescheiden.
</p>

<h3><a name="page_ref">Verwijzingen naar pagina's &quot;Zie blz. 123&quot;</a>
 (<i><a href="document.php#page_ref">Page References &quot;(See Pg. 123)&quot;</a></i>)</h3>
<p>Formatteer verwijzingen naar paginanummers zoals ze in het origineel voorkomen: <tt>(zie pg. 123)</tt>.
</p>
<p>Kijk wel na in de <a href="#comments">Project Comments</a>of de Project Manager speciale
   vereisten heeft voor de verwijzingen naar pagina's.
</p>

<h3><a name="bk_index">Indexen</a>
 (<i><a href="document.php#bk_index">Indexes</a></i>)</h3>
<p>Handhaaf de paginanummers in index pagina's. Markeer de index met  <tt>/*</tt> en <tt>*/</tt> er om heen, 
   en laat een lege regel voor het begin <tt>/*</tt> . Je hoeft de nummers niet netjes op een rij te zetten
   zoals ze in het origineel staan. Zet gewoon een komma of puntkomma neer, en de paginanummers erachter. 
</p>
<p>Indexen zijn vaak in 2 kolommen afgedrukt, waardoor sommige <i>ingangen</i> gedeeltelijk in de
   volgende regel terechtkomen. Voeg deze samen op &eacute;&eacute;n regel. 
</p>
<p>Bij indexen zijn lange regels, die hierdoor ontstaan, acceptabel, aangezien de regels tijdens het
   post-processen tot een passende breedte zullen worden <span style="border-bottom: 1px dotted green;"
    title="rewrap: het opnieuw aanbrengen van regelafbrekingen"><i>gerewrapped</i></span>.
</p>
<p>Voeg tussen de ingangen (<i>entry</i>) een lege regel toe.
</p>
<p>Als er sub-topics zijn, zet die dan op een nieuwe regel en spring 2 spaties in. 
</p>
<p>Behandel elke nieuwe sectie in een index (A, B, C....) als een  <a href="#sect_head">paragraaftitel</a>
   door er 2 lege regels voor te zetten.
</p>
<p>Oude boeken drukten het eerste woord van elke letter in de index soms af in hoofdletters of klein kapitaal;
   verander dit zo dat het hetzelfde is als van de andere ingangen (<i>entries</i>) in de index.
</p>
<!-- END RR -->

<table width="100%" align="center" border="1"  cellpadding="4" cellspacing="0" summary="Rejoining Index Lines">
  <tbody>
    <tr>
      <th align="left" bgcolor="cornsilk">Origineel:</th>
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
      <th align="left" bgcolor="cornsilk">Correct geformatteerde tekst: (met samengevoegde regels)</th>
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
    <tr><th align="left" bgcolor="cornsilk">Origineel:</th></tr>
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
      <th align="left" bgcolor="cornsilk">Correct geformatteerde tekst: (met subtopics met inspringen)</th>
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

<h3><a name="play_n">Toneelstukken: Namen van Spelers/Regieaanwijzingen</a>
 (<i><a href="document.php#play_n">Plays: Actor Names/Stage Directions</a></i>)</h3>
<p>Voor alle toneelstukken:</p>
<ul compact>
 <li>Formatteer de lijst van medespelenden (Dramatis Person&aelig;) als <a href="#lists">lijsten</a>.</li>
 <li>Zet vier lege regels voor het begin van een Acte.</li>
 <li>Zet twee lege regels voor het begin van elke sc&egrave;ne.</li>
 <li>Behandel een verandering van spreker in een dialoog als een nieuwe alinea, met een lege regel ertussen.</li>
 <li>Formatteer de namen van de acteurs als in het origineel, of ze nu <a href="#italics">cursief</a>,
   <a href="#bold">vet</a> of in <a href="#word_caps">hoofdletters</a> zijn gedrukt.</li>
 <li>Regieaanwijzingen worden geformatteerd zoals ze in de originele tekst staan.<br>
   Als ze op een aparte regel staan, laat ze daar dan zo staan. Staan ze aan het eind van een regel
   met dialoog, dan laat je ze daar. Staan ze rechts uitgelijnd aan het eind van een regel met dialoog,
   laat dan zes spaties tussen de dialoog en de regieaanwijzingen.<br> 
   Regieaanwijzingen beginnen vaak met een haakje openen en laten het haakje sluiten weg.<br>
   Deze gewoonte wordt gehandhaafd: sluit de haakjes niet. Cursieve tekst staat meestal binnen de haakjes.</li>
</ul>
<p>Voor metrische stukken (Stukken die geschreven zijn als rijmende po&euml;zie):</p>
<ul compact>
 <li>Veel toneelstukken zijn metrisch, en net als po&euml;zie moeten ze niet 
     <span style="border-bottom: 1px dotted green;" title="rewrap: het opnieuw aanbrengen van regelafbrekingen"><i>gerewrapped</i></span> worden.
     Zet <tt>/*</tt> voor en <tt>*/</tt> na metrische tekst, net als bij po&euml;zie.
     Als de regieaanwijzingen op een aparte regel staan, zet er dan geen <tt>/*</tt> en <tt>*/</tt> omheen.
     (Regieaanwijzingen zijn niet metrisch, en kunnen dus worden <span style="border-bottom: 1px dotted green;"
       title="rewrap: het opnieuw aanbrengen van regelafbrekingen"><i>gerewrapped</i></span>. Ze hoeven dus niet binnen de
     <tt>/*</tt> <tt>*/</tt> markering te staan, die de metrische dialoog beschermt tegen 
     <span style="border-bottom: 1px dotted green;"  title="rewrap: het opnieuw aanbrengen van regelafbrekingen"><i>rewrappen</i></span>.)</li>
 <li>Handhaaf het inspringen van dialoog als een enkele metrische regel wordt gedeeld door meer sprekers.</li>
 <li>Voeg metrische regels, die gesplitst moesten worden omdat het papier te smal was, samen, net als bij po&euml;zie.<br>
     Als het laatste stukje uit alleen maar &eacute;&eacute;n of twee woorden bestaat, dan staat het vaak op de regel
     eronder of erboven, na een (, in plaats van op een eigen regel.<br>
     Zie het <a href="#play4">voorbeeld</a>.</li>
</ul>
<p>Let goed op de <a href="#comments">Project Comments</a>, de Project Manager kan een andere manier van formatteren vragen.
</p>
<!-- END RR -->

<table width="100%" align="center" border="1" cellpadding="4"
 cellspacing="0" summary="Play Example 1">
  <tbody>
    <tr>
      <th align="left" bgcolor="cornsilk">Origineel:</th>
    </tr>
    <tr align="left">
      <td width="100%" valign="top"><img src="play1.png" width="500"
          height="430" alt="title page image"><br>
      </td>
    </tr>
    <tr>
      <th align="left" bgcolor="cornsilk">Correct geformatteerde tekst:</th>
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
      <th align="left" bgcolor="cornsilk">Origineel:</th>
    </tr>
    <tr align="left">
      <td width="100%" valign="top"><img src="play2.png" width="500"
          height="680" alt="title page image"><br>
      </td>
    </tr>
    <tr>
      <th align="left" bgcolor="cornsilk">Correct geformatteerde tekst:</th>
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
      <th align="left" bgcolor="cornsilk">Origineel:</th>
    </tr>
    <tr align="left">
      <td width="100%" valign="top"><img src="play3.png" width="504"
          height="206" alt="Plays image"><br>
      </td>
    </tr>
    <tr>
      <th align="left" bgcolor="cornsilk">Correct geformatteerde tekst:</th>
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
      <th align="left" bgcolor="cornsilk">Origineel:</th>
    </tr>
    <tr align="left">
      <td width="100%" valign="top"><img src="play4.png" width="502"
          height="98" alt="Plays image"><br>
      </td>
    </tr>
    <tr>
      <th align="left" bgcolor="cornsilk">Correct geformatteerde tekst:</th>
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

<h3><a name="anything">Alles wat op een speciale manier aangepakt moet worden, of waar je onzeker over bent</a>
 (<i><a href="document.php#anything">Anything else that needs special handling or that you're unsure of</a></i>)</h3>
<p>Als je bij het formatteren iets tegenkomt dat niet in deze richtlijnen behandeld wordt,
   en waarvan je wel denkt dat het op een speciale manier aangepakt moet worden, of als je niet
   zeker bent hoe het aan te pakken, post dan je vraag, onder vermelding van het png (pagina) nummer,
   in de Project Discussie. In de <a href="#comments">Project Comments</a> vind je een link naar de
   discussie die specifiek voor dat project is. Je kunt ook een aantekening in de geformatteerde tekst zetten,
   waarin je het probleem uitlegt. Je aantekening zal aan de volgende formatteerder of de Post-Processor
   uitleggen wat het probleem of de vraag is.
</p>
<p>Open aantekening met een vierkant haakje en twee sterretjes <tt>[**</tt> en sluit hem met een vierkant haakje <tt>]</tt>.
   Dit onderscheidt je aantekening van de tekst van de schrijver en geeft de Post-Processor een duidelijk signaal
   om even te stoppen en zorgvuldig tekst &amp; origineel te vergelijken om een evt. probleem aan te pakken.
   Alle commentaar dat gemaakt is door vrijwilligers v&oacute;&oacute;r je, <b>moet</b> blijven staan. Of je het er mee eens
   bent of niet kun je toevoegen, maar je mag het commentaar absoluut niet verwijderen. Als je een bron hebt
   gevonden die het probleem verheldert, verwijs daar dan naar, zodat de Post-Processor er ook naar kan verwijzen.
</p>
<p>Als je in een latere ronde aan het formatteren bent, en je stuit op een aantekening van een vrijwilliger
   in een vorige ronde, waar je het antwoord op weet, neem dan even de moeite om feedback te geven.
   Je klikt in de proofreading interface op de naam van de betreffende vrijwilliger en stuurt ze een priv&eacute;
   boodschap (private message) waarin je uitlegt hoe de situatie aangepakt kan worden.
   Zoals eerder vermeld: verwijder alsjeblieft de aantekening niet.
</p>

<h3><a name="prev_notes">Aantekeningen/Commentaar van eerdere Proeflezers/Formatteerders</a>
 (<i><a href="document.php#prev_notes">Previous Proofreaders' Notes</a></i>)</h3>
<p>Alle commentaar dat gemaakt is door vrijwilligers v&oacute;&oacute;r je <b>moet</b> blijven staan.
   Of je het er mee eens bent of niet kun je toevoegen, maar je mag het commentaar absoluut niet verwijderen.
   Als je een bron hebt gevonden die het probleem verheldert, verwijs daar dan naar,
   zodat de Post-Processor er ook naar kan verwijzen.
</p>
<p>Als je in een latere ronde aan het formatteren bent, en je stuit op een aantekening van een vrijwilliger
   in een vorige ronde, waar je het antwoord op weet, neem dan even de moeite om feedback te geven.
   Je klikt in de proofreading interface op de naam van de betreffende vrijwilliger en stuurt ze een priv&eacute;
   boodschap (private message) waarin je uitlegt hoe de situatie aangepakt kan worden.
   Zoals eerder vermeld: verwijder alsjeblieft de aantekening niet.
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
    Speciale richtlijnen voor speciale boeken</h2>
<p>Deze speciale typen boeken hebben speciale richtlijnen die iets toevoegen of veranderen aan
   de gewone richtlijnen zoals ze in dit document gegeven worden. Dit soort projecten is vaak moeilijk,
   en wordt niet aanbevolen voor beginnende vrijwilligers. Ze zijn passender voor ervaren
   vrijwilligers die deskundigheid op dat bepaalde gebied hebben.
</p>
<p>Klik op de link hieronder als je de richtlijnen voor een van deze soorten boeken wilt zien.
</p>
<ul compact>
  <li><b><a href="doc-ency.php">Encyclopedias</a></b> (dit document bestaat alleen in een Engelse versie)</li>
  <li><b><a href="doc-poet.php">Poetry Books</a></b>  (dit document bestaat alleen in een Engelse versie)</li>
  <li><b>                       Chemistry Books       [moet nog gemaakt worden.]</b></li>
  <li><b>                       Mathematics Books     [moet nog gemaakt worden.]</b></li>
</ul>

<table width="100%" border="0" cellspacing="0" summary="Common Problems">
  <tbody>
    <tr>
      <td bgcolor="silver">&nbsp;</td>
    </tr>
  </tbody>
</table>

<h2>Veel voorkomende problemen</h2>

<h3><a name="OCR_1lI">Problemen met de OCR: 1-l-I</a>
 (<i><a href="document.php#OCR_1lI">OCR Problems: 1-l-I</a></i>)</h3>
<p>De OCR heeft vaak moeite met het onderscheiden van het getal "1", de kleine letter "l" (el)
   en de hoofdletter "I". Dit geldt in het bijzonder als de bladzijden van het origineel
   in niet al te beste conditie zijn. 
</p>
<p>Let hierop. Lees de context van de zin om te bepalen wat het correcte teken is.
   Maar pas op&mdash;vaak "corrigeert" je geest dit automatisch terwijl je aan het lezen bent.
</p>
<p>Je merkt dit soort dingen veel eerder op als je een 'mono-spaced' lettertype
   zoals <a href="font_sample.php">DPCustomMono</a> of Courier gebruikt.
</p>

<h3><a name="OCR_0O">Problemen met de OCR: 0-O</a>
 (<i><a href="document.php#OCR_0O">OCR Problems: 0-O</a></i>)</h3>
<p>De OCR heeft vaak moeite met het onderscheiden van het getal "0" (nul) en de hoofdletter "O".
   Dit geldt in het bijzonder als de bladzijden van het origineel in niet al te beste conditie zijn.
</p>
<p>Let hierop. Lees de context van de zin om te bepalen wat het correcte teken is.
   Maar pas op&mdash;vaak "corrigeert" je geest dit automatisch terwijl je aan het lezen bent.
</p>
<p>Je merkt dit soort dingen veel eerder op als je een 'mono-spaced' lettertype
   zoals <a href="font_sample.php">DPCustomMono</a> of Courier gebruikt.
</p>

<h3><a name="OCR_hyphen">Problemen met de OCR: Afbreekstreepjes en andere streepjes</a>
 (<i><a href="document.php#OCR_hyphen">OCR Problems: Hyphens and Dashes</a></i>)</h3>
<p>De OCR heeft vaak moeite met het onderscheiden van afbreekstreepjes van andere streepjes.
   Formatteer deze zorgvuldig&mdash;geOCRde tekst heeft vaak maar &eacute;&eacute;n afbreekstreepje voor een em-dash,
   terwijl het er twee moeten zijn. Zie de richtlijnen voor <a href="#eol_hyphen">afgebroken woorden</a>
   en voor <a href="#em-dashes">liggende streepjes</a> voor uitgebreidere informatie. 
</p>
<p>Je merkt dit soort dingen veel eerder op als je een 'mono-spaced' lettertype
   zoals <a href="font_sample.php">DPCustomMono</a> of Courier gebruikt.
</p>

<h3><a name="OCR_scanno">Problemen met de OCR: Scanno's</a>
 (<i><a href="document.php#OCR_scanno">OCR Problems: Scannos</a></i>)</h3>
<p>Een ander veel voorkomend probleem van de OCR is het niet goed herkennen van tekens. We noemen deze
   fouten "scanno's" (zoiets als "typo's"). Deze foutieve herkenning kan leiden tot woorden die:</p>
<ul compact>
   <li>op het eerste gezicht correct lijken, maar in werkelijkheid een spelfout bevatten.<br>
       Deze woorden kun je <u>meestal</u> onderscheppen door WordCheck te gebruiken.</li>
   <li>veranderd zijn naar een ander, maar wel goed woord, dat niet overeenkomt met het origineel.<br>
       Dit kan alleen opgemerkt worden door iemand die de tekst echt leest.</li>
</ul>
<p>In het Engels is het meest voorkomende voorbeeld van het tweede type "and" dat door de OCR wordt
   weergegeven als "arid". Andere voorbeelden zijn: "eve" voor "eye", "Torn" voor "Tom", "train" voor "tram".
   In het Nederlands wordt "elken" vaak door "eiken" weergegeven, en "mensch" door "mensen". Dit type fout
   is veel moeilijker om op te sporen en we hebben een speciale term ervoor: "Stealth Scanno's".
   We verzamelen voorbeelden van Stealth Scannos in <a href="<? echo $Stealth_Scannos_URL; ?>">deze discussie</a>.
</p>
<p>Het opmerken van scanno's is veel gemakkelijker als je een 'mono-spaced' lettertype zoals
   <a href="font_sample.php">DPCustomMono</a> of Courier gebruikt.
</p>
<!-- END RR -->
<!-- More to be added.... -->

<h3><a name="hand_notes">Handgeschreven aantekeningen in een boek</a>
 (<i><a href="document.php#hand_notes">Handwritten Notes in Book</a></i>)</h3>
<p>Laat handgeschreven aantekeningen weg, behalve als de gedrukte tekst verbleekt en
   vervolgens overgetrokken is om de tekst beter zichtbaar te maken.
   Laat handgeschreven aantekeningen die door lezers e.d. in de marge gezet zijn, weg.
</p>
<p>Sommige Project Managers kunnen vragen om handgeschreven aantekeningen te markeren
   met <tt>[HW: (tekst van de aantekening)]</tt>.
</p>

<h3><a name="bad_image">Slecht beeld</a>
 (<i><a href="document.php#bad_image">Bad Image</a></i>)</h3>
<p>Als het beeld van een origineel niet goed is (laadt niet, een stuk er af, onmogelijk om te lezen)
   post dan alsjeblieft in het <a href="#forums">Project Discussie</a> over dit slechte origineel.
   Klik niet op "Return Page to Round", want dan wordt de pagina weer aan de volgende formatteerder
   gepresenteerd. Klik op de "Report Bad Page" button, waardoor de pagina in "quarantaine" gaat. 
</p>
<p>Houd in de gaten dat sommige pagina's erg groot zijn, en dat het dan gebruikelijk is dat
   je browser moeite heeft om ze te laten zien, zeker als je meerdere vensters open hebt of
   als je een oudere computer gebruikt. Probeer, voordat je een slecht origineel meldt,
   te klikken op de "Image" button onderaan je pagina om uitsluitend het origineel in een
   nieuw venster te tonen. Als dat er wel goed uitziet, dan zit het probleem
   waarschijnlijk in je browser of je systeem.
</p>
</p><p>Wat meer voorkomt, is dat het origineel wel goed is, maar dat de geOCRde tekst de eerste
   paar regels tekst mist. Typ deze regels erbij, alsjeblieft. Als bijna de hele tekst ontbreekt,
   typ hem dan in of klik op de "Return Page to Round" button en de pagina wordt aan iemand anders
   toegekend. Als er meerdere pagina's zoals deze zijn, post dan in het
   <a href="#forums">project forum</a> om de Project Manager in te lichten.
</p>

<h3><a name="bad_text">Verkeerd beeld voor de tekst</a>
 (<i><a href="document.php#bad_text">Wrong Image for Text</a></i>)</h3>
<p>Als er een verkeerd beeld gegeven wordt voor de tekst, post dan alsjeblieft in de
   <a href="#forums">Project Discussie</a>. Klik niet op "Return Page to Round",
   want dan wordt de pagina weer aan de volgende formatteerder gepresenteerd.
   Klik op de "Report Bad Page" button, waardoor de pagina in "quarantaine" gaat.
</p>

<h3><a name="round1">Eerder gemaakte Proeflees- en Formatteervergissingen</a>
 (<i><a href="document.php#round1">Previous Proofreading or Formatting Mistakes</a></i>)</h3>
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

<h3><a name="p_errors">Vergissingen van de drukker/Spelfouten</a>
 (<i><a href="document.php#p_errors">Printer Errors/Misspellings</a></i>)</h3>
<p>Corrigeer alle woorden die door de OCR fout zijn ge&iuml;nterpreteerd (scanno), maar verbeter
   geen spel- of zetfouten die op het origineel voorkomen. In veel oudere teksten werden woorden
   anders gespeld dan we tegenwoordig doen. We handhaven deze oude spelling, inclusief accenten.
</p>
<p>Als je het niet zeker weet, maak dan een aantekening in de teskt <tt>[**typo voor tekst?]</tt>
   en stel een vraag in de Project Discussie. Als je iets verandert, maak dan een aantekening
   wat je veranderd hebt: <tt>[**typo verbeterd, "teskt" veranderd in "tekst"]</tt>. Zorg dat de
   twee sterretjes <tt>**</tt> er staan, zodat de Post-Processor de aantekening niet over het hoofd ziet. 
</p>

<h3><a name="f_errors">Feitelijke fouten in de tekst</a>
 (<i><a href="document.php#f_errors">Factual Errors in Texts</a></i>)</h3>
<p>Over het algemeen verbeteren we feitelijke vergissingen in het boek niet. Veel boeken die we
   onder handen hebben, bevatten uitspraken over feiten die we nu niet meer als juist accepteren.
   Laat deze uitspraken staan zoals de schrijver ze geschreven heeft.
</p>
<p>Een mogelijke uitzondering komt voor in technische of wetenschappelijke boeken, waar een formule
   of vergelijking fout wordt weergegeven, in het bijzonder als deze op een andere bladzijde wel
   goed staat weergegeven. Zorg wel dat de Project Manager weet wat er aan de hand is, of door
   in het <a href="#forums">Forum</a> te posten, of door een <tt>[** aantekening waarin je aangeeft
   wat er aan de hand is]</tt> op de betreffende plaats in de tekst.
</p>

<h3><a name="uncertain">Onzekere items</a>
 (<i><a href="document.php#uncertain">Uncertain Items</a></i>)</h3>
<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; [...moet nog afgemaakt worden...]
</p>

<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="silver" summary="Links">
<tr>
  <td width="10">&nbsp;</td>
  <td width="100%" align="center"><font face="verdana, helvetica, sans-serif" size="1">
     Terug naar:
     <a href="..">Distributed Proofreaders home page</a>,
     &nbsp;&nbsp;&nbsp;
     <a href="faq_central.php">DP FAQ Central page</a>,
     &nbsp;&nbsp;&nbsp;
     <a href="<? echo $PG_home_url; ?>">Project Gutenberg home page</a>.
     </font>
  </td>
</tr>
</table>

<?
theme('','footer');
?>