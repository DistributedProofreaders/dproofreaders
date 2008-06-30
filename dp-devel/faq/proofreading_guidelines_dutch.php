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
theme('Proeflees-Richtlijnen','header');

$utf8_site=!strcasecmp($charset,"UTF-8");
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

<h1 align="center">Proeflees-Richtlijnen</h1>

<h3 align="center">Versie 1.9.e, herzien July 19, 2007 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
  <a href="dochist.php"><font size="-1">(Herzieningsgeschiedenis)</font></a></h3>

<HR>
<h4>Dit document is een vertaling van de Engelse Proofreading Guidelines.<BR>
 Bij elk hoofdstuk is een link opgenomen naar het corresponderende hoofdstuk in die Guidelines.</h4> 

<HR>

<h4>Proeflees-Richtlijnen <a href="proofreading_guidelines.php">in het Engels</a> /
      Proofreading Guidelines <a href="proofreading_guidelines.php">in English</a><br />
    Proeflees-Richtlijnen <a href="proofreading_guidelines_francaises.php">in het Frans</a> /
      Directives de Relecture et Correction <a href="proofreading_guidelines_francaises.php">en fran&ccedil;ais</a><br />
    Proeflees-Richtlijnen <a href="proofreading_guidelines_portuguese.php">in het Portugees</a> /
      Regras de Revis&atilde;o <a href="proofreading_guidelines_portuguese.php">em Portugu&ecirc;s</a><br />
    Proeflees-Richtlijnen <a href="proofreading_guidelines_spanish.php">in het Spaans</a> /
      Reglas de Revisi&oacute;n <a href="proofreading_guidelines_spanish.php">en espa&ntilde;ol</a><br />
    Proeflees-Richtlijnen <a href="proofreading_guidelines_german.php">in het Duits</a> /
      Korrekturlese-Richtlinien <a href="proofreading_guidelines_german.php">auf Deutsch</a><br />
</h4>

<h4>Bekijk de <a href="../quiz/start.php">Proofreading Quiz and Tutorial</a>! (dit document bestaat alleen in een Engelse versie)</h4>

<table border="0" cellspacing="0" width="100%" summary="Proofreading Guidelines">
  <tbody>
  <tr>
    <td width="1" bgcolor="silver">&nbsp;</td>
    <td bgcolor="silver" align="center"><font size="+2"><b>Inhoudsopgave</b></font></td>
  </tr>
  <tr>
    <td width="1" bgcolor="silver">&nbsp;</td>
    <td bgcolor="white" align="left">
    <ul>
      <li><a href="#prime">De Hoofdregel </a></li>
      <li><a href="#summary">Samenvatting van de Richtlijnen </a></li>
      <li><a href="#about">Over dit document </a></li>
      <li><a href="#comments">Toelichting bij een Project </a></li>
      <li><a href="#forums">Forum/ Bediscussieer dit Project </a></li>
      <li><a href="#prev_pg">Het herstellen van vergissingen op voorgaande pagina's </a></li>
    </ul>
    </td>
  </tr>
  <tr>
    <td width="1" bgcolor="silver">&nbsp;</td>
    <td bgcolor="silver" align="left">
      <ul>
        <li><font size="+1">Het Proeflezen van...</font></li>
      </ul>
    </td>
  </tr>
  <tr>
    <td width="1" bgcolor="silver">&nbsp;</td>
    <td bgcolor="white" align="left">
      <ul style="margin-left: 3em;">
        <li><a href="#double_q">Dubbele aanhalingstekens</a></li>
        <li><a href="#single_q">Enkele aanhalingstekens</a></li>
        <li><a href="#quote_ea">Aanhalingstekens op iedere regel</a></li>
        <li><a href="#period_s">Punten aan het eind van een zin</a></li>
        <li><a href="#punctuat">Interpunctie</a></li>
        <li><a href="#extra_sp">Extra Spaties of Tabs Tussen Woorden</a></li>
        <li><a href="#trail_s">Spaties aan het eind van een regel</a></li>
        <li><a href="#drop_caps">Grote, Versierde Hoofdletter aan het begin</a></li>
        <li><a href="#em_dashes">Liggend streepje: koppelteken, gedachtestreepje, minteken</a></li>
        <li><a href="#eol_hyphen">Woordafbreking aan het eind van een regel</a></li>
        <li><a href="#eop_hyphen">Woordafbreking aan het eind van een bladzijde</a></li>
        <li><a href="#period_p">Beletselteken ofwel ellips &quot;&hellip;&quot;</a></li>
        <li><a href="#contract">Samentrekkingen</a></li>
        <li><a href="#fract_s">Breuken</a></li>
        <li><a href="#a_chars">Tekens met accenten/Non ASCII tekens</a></li>
        <li><a href="#d_chars">Lettertekens met Diakritische Tekens</a></li>
        <li><a href="#f_chars">Niet-Latijnse lettertekens</a></li>
        <li><a href="#supers">Superscript</a></li>
        <li><a href="#subscr">Subscript</a></li>
        <li><a href="#font_sz">Verandering in grootte van het lettertype</a></li>
        <li><a href="#italics">Cursief en Vet gedrukte Tekst</a></li>
        <li><a href="#small_caps">Woorden in <span style="font-variant: small-caps">kleine hoofdlettertjes (Klein Kapitaal)</span></a></li>
        <li><a href="#line_br">Regelafbrekingen</a></li>
        <li><a href="#chap_head">Hoofdstuktitels</a></li>
        <li><a href="#para_space">Ruimte tussen Alinea's/Inspringingen</a></li>
        <li><a href="#page_hf">Koptekst en Voettekst</a></li>
        <li><a href="#illust">Illustraties</a></li>
        <li><a href="#footnotes">Voetnoten/Eindnoten</a></li>
        <li><a href="#para_side">Beschrijvingen naast een Alinea</a></li>
        <li><a href="#mult_col">Meerdere kolommen.</a></li>
        <li><a href="#tables">Tabellen</a></li>
        <li><a href="#poetry">Po&euml;zie/Epigrammen</a></li>
        <li><a href="#line_no">Regelnummers</a></li>
        <li><a href="#next_word">Losstaand woord onderaan een pagina</a></li>
        <li><a href="#blank_pg">Lege Pagina</a></li>
        <li><a href="#title_pg">Titelpagina aan de voor- of achterkant</a></li>
        <li><a href="#toc">Inhoudsopgave</a></li>
        <li><a href="#bk_index">Indexen</a></li>
        <li><a href="#play_n">Toneelstukken: Namen van Spelers/Regieaanwijzingen</a></li>
        <li><a href="#anything">Alles wat op een speciale manier aangepakt moet worden, of waar je onzeker over bent</a></li>
        <li><a href="#prev_notes">Aantekeningen/Commentaar van eerdere proeflezers</a></li>
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
        <li><a href="#round1">Eerder gemaakte proefleesvergissingen</a></li>
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
 (<i><a href="proofreading_guidelines.php#prime">The Primary Rule</a></i>)</h3>
<p><em>"Verander niet wat de schrijver heeft geschreven!"</em>
</p>
<p>Het elektronische boek zoals de lezer het uiteindelijk zal zien, mogelijk vele jaren later, moet de bedoeling van de
   schrijver precies weergeven. Als de schrijver woorden op een vreemde manier spelde, dan laten we die spelling staan. Als de
   schrijver buitensporig racistische of bevooroordeelde uitspraken deed, dan laten we die staan. Als de schrijver elk derde
   woord cursief of vet maakte, of elk derde woord van een voetnoot voorzag, dan markeren we elk derde woord als cursief, of vet,
   of voorzien het van een voetnoot. We zijn proeflezers, en <b>geen</b> redacteuren. (Zie <a href="#p_errors">Vergissingen van de drukker</a>
   voor de manier waarop overduidelijke vergissingen van de drukker behandeld moeten worden.)
</p>
<p>Kleine typografische ge&iuml;nspireerde ingrepen in de tekst, die de betekenis van wat de schrijver schreef, niet aantasten, veranderen we wel. 
   Bijvoorbeeld: woorden die aan het eind van een regel af werden gebroken, plakken we weer aan elkaar 
   (<a href="#eol_hyphen">Woordafbreking aan het eind van een regel</a>).
   Dit soort veranderingen helpen ons om een <em>consistente</em> versie van het boek te produceren. 
   De richtlijnen voor het proeflezen die we volgen, zijn ontworpen om dit resultaat te bereiken. 
   Lees de rest van deze Proeflees-Richtlijnen alsjeblieft zorgvuldig, met dit concept in je achterhoofd. 
   Deze richtlijnen zijn <i>uitsluitend</i> bedoeld voor het proeflezen. Er zijn aparte richtlijnen voor het formatteren. 
   Een tweede groep vrijwilligers zal werken aan het formatteren van de tekst.
</p>
<p>Om de volgende proeflezer, de formatteerders en de Post-Processor te ondersteunen, handhaven we de <a href="#line_br">regelafbrekingen</a>.
   Dit helpt hen om de regels in de tekst te vergelijken met de regels in het origineel.
</p>
<!-- END RR -->

<table width="100%" border="0" cellspacing="0" summary="Summary Guidelines">
  <tbody>
    <tr>
      <td bgcolor="silver">&nbsp;</td>
    </tr>
  </tbody>
</table>


<h3><a name="summary">Samenvatting van de Richtlijnen</a>
 (<i><a href="proofreading_guidelines.php#summary">Summary Guidelines</a></i>)</h3>
<p>De <a href="proofing_summary.pdf">Proofreading Summary</a> (dit document bestaat alleen in een Engelse versie)
   is een kort printer-vriendelijk (.pdf) document van 2 pagina's, dat de voornaamste punten van deze richtlijnen samenvat,
   en voorbeelden geeft hoe te proeflezen. 
   Beginnende proeflezers wordt aangeraden dit document uit te printen en bij de hand te houden bij het proeflezen.
</p>
<p>Misschien moet je een .pdf lezer downloaden en installeren. Je kunt er 
   <a href="http://www.adobe.com/products/acrobat/readstep2.html">hier</a> gratis &eacute;&eacute;n van Adobe&reg; krijgen.
</p>

<h3><a name="about">Over dit document</a>
 (<i><a href="proofreading_guidelines.php#about">About This Document</a></i>)</h3>
<p>Dit document is geschreven om de proeflees-regels uit te leggen. We gebruiken deze regels om consistentie te waarborgen, 
   aangezien het proeflezen van &eacute;&eacute;n enkel boek verdeeld is over vele proeflezers, die allemaal aan verschillende pagina's werken. 
   De regels helpen ons om allemaal <em>op dezelfde manier</em> te proeflezen, wat het vervolgens gemakkelijker maakt voor de formatteerders
   en de Post-Processor die het werk aan dit e-boek zullen afmaken.
</p>
<p><i>Dit document is niet bedoeld als een algemeen handboek voor het redigeren of zetten van boeken.</i>
</p>
<p>We hebben in dit document alles behandeld waar nieuwe gebruikers vragen over gesteld hebben. 
   Als er iets ontbreekt, of als je denkt dat iets op een andere manier behandeld zou moeten worden, 
   of als iets vaag is, laat het ons alsjeblieft weten.
</p>
<p>Dit document is in ontwikkeling. Help ons alsjeblieft bij de ontwikkeling hiervan, door veranderingen die je zou willen voorstellen,
   in <a href="<? echo $Guideline_discussion_URL; ?>">deze discussie</a> in het Documentation Forum te posten.
</p>

<h3><a name="comments">Toelichting bij een Project</a>
 (<i><a href="proofreading_guidelines.php#comments">Project Comments</a></i>)</h3>
<p>Op de Project Pagina waar je begint met het proeflezen van pagina's, vind je een gedeelte dat "Project Comments"
   (Toelichting bij het Project) heet. Hier staat informatie die specifiek is voor het betreffende project (boek).
   <b>Lees dit voor je begint met het proeflezen van pagina's!</b> Als de Project Manager wil dat je iets op een
   andere manier doet, dan in deze richtlijnen staat, dan staat dat hier. Instructies in de Project Comments
   <em>geven de doorslag, boven de regels in deze richtlijnen</em>, dus volg ze alsjeblieft op. Er kunnen in
   de Project Comments ook instructies staan die van toepassing zijn op de formatteer-fase, deze zijn tijdens
   het proeflezen niet van belang. Tot slot is dit trouwens ook de plaats waar de Project Manager wel eens
   interessante informatie geeft over de schrijver of over het project.
</p>
<p><em>Lees alsjeblieft ook de Project Discussie</em>: Hier legt de Project Manager soms project-specifieke
   richtlijnen uit. Ook wordt deze discussie regelmatig gebruikt door vrijwilligers om andere vrijwilligers te wijzen op vaak
   terugkerende problemen in het project, en hoe deze het beste kunnen worden aangepakt. (Zie hieronder)
</p>
<p>Op de Project Pagina staat een link 'Images, Pages Proofread, &amp; Differences'. Daar kun je zien hoe andere
   vrijwilligers veranderingen hebben aangebracht. <a href="<? echo $Using_project_details_URL ?>">Deze Forumdiscussie</a>
   bespreekt verschillende manieren om deze informatie te gebruiken.
</p>

<h3><a name="forums">Forum/ Bediscussieer dit Project</a>
 (<i><a href="proofreading_guidelines.php#forums">Forum/Discuss this Project</a></i>)</h3>
<p>Op de Project Pagina, waar je met het proeflezen van pagina's begint, staat op de regel "Forum", een link met de naam:
   "Discuss this Project" (als de discussie al begonnen is), of: "Start a discussion on this Project" (als de discussie
   nog niet begonnen is.) Klik op deze link en je komt op een discussie in het projectenforum, speciaal voor
   dit project. Hier kun je vragen over dit boek stellen, hier kun je de Project Manager informeren over problemen
   met de tekst enz. Posten in deze Project Discussie is de aanbevolen manier om met de Project Manager en andere
   vrijwilligers die aan het boek werken, te communiceren.
</p>

<h3><a name="prev_pg">Het herstellen van vergissingen op voorgaande pagina's</a>
 (<i><a href="proofreading_guidelines.php#prev_pg">Fixing errors on Previous Pages</a></i>)</h3>
<p>Als je een project uitkiest om gaan proeflezen, wordt de <a href="#comments">Project Pagina</a>
   geladen. Deze pagina heeft links naar bladzijden van dit project waar je recent aan gewerkt hebt.
   (Als je nog geen pagina's van dit project proefgelezen hebt, zijn er ook geen links.)
</p>
<p>Pagina's onder &oacute;f "DONE" &oacute;f "IN PROGRESS" zijn beschikbaar om te verbeteren of om af te maken.
   Klik op de link van de pagina. Als je ontdekt dat je een vergissing op een pagina gemaakt hebt,
   of dat je iets onjuist gemarkeerd hebt, kun je hier op de link klikken en de pagina openen om
   de vergissing te herstellen.
</p>
<p>Je kunt ook de "Images, Pages Proofread, &amp; Differences" of "Just My Pages" link op de
   <a href="#comments">Project Pagina</a> gebruiken. Deze pagina's hebben een "Edit" link naast de
   pagina's waar je in de huidige ronde aan gewerkt hebt en die nog verbeterd kunnen worden.
</p>
<p>Voor gedetailleerder informatie, zie &oacute;f de <a href="prooffacehelp.php?i_type=0">Standard
   Proofreading Interface Help</a> &oacute;f de <a href="prooffacehelp.php?i_type=1">Enhanced Proofreading
   Interface Help</a> (deze documenten bestaan alleen in een Engelse versie), afhankelijk van welke interface je gebruikt.
</p>
<!-- END RR -->
<table width="100%" border="0" cellspacing="0" cellpadding="6" summary="Title Page">
  <tbody>
    <tr>
      <td bgcolor="silver"><font size="+2">Het Proeflezen van...</font></td>
    </tr>
  </tbody>
</table>


<h3><a name="double_q">Dubbele aanhalingstekens</a>
 (<i><a href="proofreading_guidelines.php#double_q">Double Quotes</a></i>)</h3>
<p>Aanhalingstekens in Engelse boeken: proeflees deze als gewone ASCII <tt>"</tt> dubbele aanhalingstekens.
   Verander geen dubbele aanhalingstekens in enkele aanhalingstekens. Laat de aanhalingstekens staan zoals
   in het origineel.
</p>
<p>Aanhalingstekens in andere talen: gebruik de aanhalingstekens die voor die taal gewoon zijn,
   als deze beschikbaar zijn. Het Franse equivalent, de guillemets, <tt>&laquo;zoals dit&raquo;</tt>,
   kunnen gekozen worden in de uitklapmenu's in de proofreading interface, aangezien ze deel uitmaken
   van de Latin-1 tekenset. Vergeet niet spaties te verwijderen tussen de guillemets en de geciteerde tekst.
   Als er toch spaties gewenst zijn, zullen die tijdens het post-processen worden toegevoegd.
   Hetzelfde geldt voor talen waar omgekeerde guillemets worden gebruikt, <tt>&raquo;zoals dit&laquo;</tt>.
</p>
<p>De lage aanhalingstekens die in sommige teksten voorkomen (in het Nederlands en Duits, en sommige andere talen),
   <tt>&bdquo;zoals dit&rdquo;</tt>
<? if(!$utf8_site) { ?>
   kunnen niet gekozen worden in de uitklapmenu's, aangezien ze niet in de Latin-1 tekenset voorkomen.
   Volg de instructies in de Project Comments. Wordt er daar niets over gezegd, gebruik dan de gewone
   ASCII dubbele aanhalingstekens, net als in het Engels. 
<? } else { ?>
   zijn ook beschikbaar in de uitklapmenu's. Om de zaak eenvoudig te houden, moet je altijd
   <tt>&bdquo;</tt> en <tt>&ldquo;</tt> gebruiken als de aanhalingstekens in het origineel duidelijk lage en hoge 
   aanhalingstekens zijn, ongeacht welke aanhalingstekens in de originele tekst gebruikt worden.
   De aanhalingstekens zullen zo nodig tijden het post-processen veranderd worden in de aanhalingstekens 
   die in de tekst gebruikt zijn.
<? } ?>
</p>
<p>Het kan zijn dat de Project Manager in de <a href="#comments">Project Comments</a> voor een bepaald
   boek instructies geeft om aanhalingstekens uit een niet-Engelse taal anders te behandelen. 
</p>

<h3><a name="single_q">Enkele aanhalingstekens</a>
 (<i><a href="proofreading_guidelines.php#single_q">Single Quotes</a></i>)</h3>
<p>Proeflees deze als gewone ASCII <tt>'</tt> enkele aanhalingstekens (apostrof). Verander enkele
   aanhalingstekens niet in dubbele aanhalingstekens. Laat de aanhalingstekens staan zoals in het origineel. 
</p>

<h3><a name="quote_ea">Aanhalingstekens op iedere regel</a>
 (<i><a href="proofreading_guidelines.php#quote_ea">Quote Marks on each line</a></i>)</h3>
<p>Als in een citaat iedere tekstregel met een aanhalingsteken begint, verwijder deze dan,
   <b>behalve</b> het aanhalingsteken waarmee het citaat begint.
</p>
<p>Als het citaat meerdere alinea's omvat, dan bij iedere alinea de eerste regel te
   beginnen met een aanhalingsteken. 
</p>
<p>Vaak staat er pas een aanhalingsteken sluiten aan het eind van het volledige citaat;
   dit kan op een andere pagina zijn dan de pagina die je onder handen hebt.
   Laat dit zo: voeg geen aanhalingstekens toe die niet op de pagina aanwezig zijn. 
</p>

<h3><a name="period_s">Punten aan het eind van een zin</a>
 (<i><a href="proofreading_guidelines.php#period_s">End-of-sentence Periods</a></i>)</h3>
<p>Proeflees punten aan het eind van een zin met een enkele spatie erachter. 
</p>
<p>Als er meer spaties achter een punt staan, hoef je die niet weg te halen, dat gebeurt automatisch
   tijdens het post-processen. Zie de <a href="#para_side">Sidenotes</a> voor een voorbeeld.
</p>

<h3><a name="punctuat">Interpunctie</a>
 (<i><a href="proofreading_guidelines.php#punctuat">Punctuation</a></i>)</h3>
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
    <tr><th align="left" bgcolor="cornsilk">Correct proefgelezen tekst:</th></tr>
    <tr>
      <td valign="top"><tt>and so it goes; ever and ever.</tt></td>
    </tr>
  </tbody>
</table>

<h3><a name="extra_sp">Extra Spaties of Tabs Tussen Woorden</a>
 (<i><a href="proofreading_guidelines.php#extra_sp">Extra spaces or tabs between Words</a></i>)</h3>
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

<h3><a name="trail_s">Spaties aan het eind van een regel</a>
 (<i><a href="proofreading_guidelines.php#trail_s">Trailing Space at End-of-line</a></i>)</h3>
<p>Je hoeft aan het eind van een regel geen spatie in te voegen. Dit is verspilling van je tijd,
   aangezien we het later automatisch kunnen doen. Verspil je tijd ook niet door extra
   spaties aan het eind van regels weg te halen. 
</p>

<h3><a name="drop_caps">Grote, Versierde Hoofdletter aan het begin</a>
 (<i><a href="proofreading_guidelines.php#drop_caps">Large, Ornate opening Capital letter (Drop Cap)</a></i>)</h3>
<p>Proeflees een grote, versierde eerste letter van een hoofdstuk, paragraaf of alinea als een gewone letter.
</p>

<h3><a name="em_dashes">Liggend streepje: koppelteken, gedachtestreepje, minteken</a>
 (<i><a href="proofreading_guidelines.php#em_dashes">Dashes, Hyphens, and Minus Signs</a></i>)</h3>
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
    <br>Proeflees deze als twee streepjes wanneer het liggende streepje kort is, en als vier streepjes wanneer het liggende streepje lang is.
        Laat geen spatie staan voor of na de streepjes, zelfs niet als er in de oorspronkelijke tekst een spatie te zien is.
    </li>
    <li><i>Met Opzet Weggelaten of Gecensureerde Woorden of Namen</i>.
    <br>Proeflees deze als 4 streepjes. Als de streepjes een woord voorstellen, dan laten we er
        spaties omheen staan alsof het echt een woord is. Als het een deel van een woord betreft,
        dan geen spaties&mdash;koppel het aan de rest van het woord. 
        Als de streep ongeveer dezelfde lengte heeft als de kortere em-dashes, proeflees deze
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
      <th valign="top" bgcolor="cornsilk">Origineel (boek):</th>
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
        training and heritage,</tt></td>
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
      <td>Langere Em-dash</td>
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
 (<i><a href="proofreading_guidelines.php#eol_hyphen">End-of-line Hyphenation</a></i>)</h3>
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
 (<i><a href="proofreading_guidelines.php#eop_hyphen">End-of-page Hyphenation</a></i>)</h3>
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
<p>Deze markeringen geven aan de Post-Processor een teken dat het woord moet worden samengevoegd,
   als de pagina's tot een e-boek worden samengevoegd.
</p>

<h3><a name="period_p">Beletselteken ofwel ellips &quot;&hellip;&quot;</a>
 (<i><a href="proofreading_guidelines.php#period_p">Period Pause &quot;&hellip;&quot; (Ellipsis)</a></i>)</h3>
<p>De richtlijnen zijn verschillend voor het Engels en voor andere talen dan het Engels (Languages Other Than English (LOTE)).
</p>
<p><b>ENGELS</b>:
   Zet een spatie v&oacute;&oacute;r de drie punten, en zet ook erna een spatie. De uitzondering is het einde van een zin,
   daar moet: geen spatie, vier punten, spatie. Dit geldt ook voor andere interpunctie aan het einde
   van een zin. De drie punten komen er meteen na, zonder spatie. 
</p>
<p>Bijvoorbeeld:<br>
   <tt>
   <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;That I know ... is true.
   <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;This is the end....
   <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Wherefore art thou Romeo?...
   </tt>
</p>
<p>Soms staat de interpunctie helemaal aan het eind, dan proeflees je het ook zo:<br>
   <tt>
   <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Wherefore art thou Romeo...?
   </tt>
</p>
<p>Verwijder evt. extra punten, of voeg zo nodig punten toe, om te zorgen dat er drie punten staan, of zo nodig vier.
</p>

<p><b>LOTE</b>: (Andere talen dan het Engels)
   De algemene regel is: <i>Doe het zoals op de pagina gedrukt staat</i>. Voeg spaties in,
   als er spaties voor of tussen de punten staan. Gebruik evenveel punten als in het origineel.
   Soms is het origineel niet helemaal duidelijk. Voeg dan <tt>[**unclear]</tt> in om de aandacht
   van de Post-Processor er op te vestigen.
   (Noot: Post-Processors dienen deze spaties te vervangen door <i>non-breaking</i> spaties.)
</p>

<h3><a name="contract">Samentrekkingen</a>
 (<i><a href="proofreading_guidelines.php#contract">Contractions</a></i>)</h3>
<p>Verwijder extra spaties in samentrekkingen, bijvoorbeeld: <tt>would&nbsp;n't</tt>
   dient te worden proefgelezen als <tt>wouldn't</tt>.
</p>
<p>Zetters behielden vroeger vaak een spatie om aan te geven dat 'would' en 'not' oorspronkelijk
   afzonderlijke woorden waren. Het kan ook een bijverschijnsel van het OCR-proces zijn.
   In beide gevallen dient de extra spatie verwijderd te worden. 
</p>
<p>Sommige Project Managers zetten een opmerking in de <a href="#comments">Project Comments</a>
   dat extra spaties in samentrekkingen niet verwijderd moeten worden, vooral wanneer het tekst
   betreft met straattaal of dialect, of wanneer de teksten niet in het Engels zijn geschreven. 
</p>

<h3><a name="fract_s">Breuken</a>
   (<i><a href="proofreading_guidelines.php#fract_s">Fractions</a></i>)</h3>
<p>Proeflees <b>breuken</b> als volgt: <tt>2&frac12;</tt> wordt <tt>2-1/2</tt>.
   Het streepje voorkomt dat het hele getal en de breuk tijdens het post-processen bij het 
   <span style="border-bottom: 1px dotted green;" title="rewrap: het opnieuw aanbrengen van regelafbrekingen">rewrappen</span>
   van elkaar worden gescheiden.
</p>

<h3><a name="a_chars">Tekens met accenten/Non ASCII tekens</a>
 (<i><a href="proofreading_guidelines.php#a_chars">Accented/Non-ASCII Characters</a></i>)</h3>
<? if(!$utf8_site) { ?>
<p>Proeflees deze waar mogelijk door Latin-1 tekens met accenten te gebruiken. Zie
   <a href="#d_chars">Diakritische tekens</a> voor de manier waarop een aantal niet-Latin-1
   tekens geformatteerd moeten worden.
</p><? } else { ?>
<p>Proeflees deze a.u.b. door de juiste UTF-8 tekens te gebruiken. Voor tekens die niet in
   Unicode zitten, zie de Project Manager instructies in de <a href="#comments">Project Comments</a>.
</p>
<? } ?>
<p>Als je ze niet op je toetsenbord hebt, zijn er meerdere manieren om deze tekens in te geven:</p>
<ul compact>
  <li> De uitklapmenu's in de proofreading interface.</li>
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
  <li>Een on-line programma, zoals <a
   href="http://free.pages.at/krauss/computer/xml/daten/edicode.html">Edicode</a>.</li>
  <li> Toetscombinaties.<br>
       (Zie de tabellen hieronder voor <a href="#a_chars_win">Windows</a> en <a href="#a_chars_mac">Macintosh</a>.)</li>
  <li> Switch naar een toetsenbordindeling of een plek die "deadkey" accenten ondersteunt.
       <ul compact>
       <li>Windows: Configuratiescherm (Toetsenbord, Input Locales).</li>
       <li>Macintosh: Input Menu (op de Menu Bar).</li>
       <li>Linux: Verander je toetsenbord in je X configuratie.</li>
      </ul>
  </li>
</ul>
<p>
   Het oorspronkelijke <a href="http://www.gutenberg.org">Project Gutenberg</a> zet minstens
   7-bit ASCII versies van teksten op het net, maar ze aanvaarden daarnaast versies die meer van
   de informatie van het origineel behouden. <a href="http://pge.rastko.net">Project Gutenberg
   Europe</a> publiceert als standaard UTF-8, maar andere, passende versies zijn ook welkom.
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
  <li>De uitklapmenu's in de proofreading interface.
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
      teken hiervandaan knippen en in de proofreading interface plakken.</li>
  <li>In Os X 10.3 en hoger, is deze functie beschikbaar vanuit het Input menu 
     (het uitklapmenu dat vast zit aan je locale's flag icon in de menubalk. Het heet 
     "Show Keyboard Viewer". Als het niet in je Input menu zit, of je hebt dat menu helemaal niet, 
     kun je het openen door System Preferences te openen, het "International panel", en dan 
     "Input menu" te kiezen. Zorg dat "Show input menu in menu bar" geselecteerd is. 
     In de spreadsheet view, zorg dat het hokje voor "Keyboard Viewer" aangevinkt is,
     naast andere input locales die je gebruikt.
  </li>
  <li>De uitklapmenu's in de proofreading interface.
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
  </tr>
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
      <td align="center" bgcolor="mistyrose" title="1/2 Breuk"                 >&frac12; </td><td>(geen)&nbsp;&Dagger;<sup><small>1</small></sup>  </td>
  </tr>
  <tr><td align="center" bgcolor="mistyrose" title="superscript 3"             >&sup3;   </td><td>(geen)&nbsp;&Dagger;  </td>
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
 (<i><a href="proofreading_guidelines.php#d_chars">Characters with Diacritical marks</a></i>)</h3>
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
   <i>Modifier diacritical marks</i>; deze mogen niet gebruikt worden.
</p>
<? } else { ?>
   Voor het proeflezen geven we ze in onze ASCII tekst aan door speciale codes,
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
   Gebruik bij het proeflezen de letter die in de tekst staat, en niet de <tt>x</tt> uit de voorbeelden.
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
      <th colspan=4>Symbolen voor het Proeflezen van Diakritische Tekens</th>
  </tr>
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
   (<i><a href="proofreading_guidelines.php#f_chars">Non-Latin Characters</a></i>)</h3>
<p>Sommige teksten bevatten tekst die in niet-Latijnse lettertekens is gedrukt, dus andere letters dan
   de Latijnse A...Z. Het kan dan gaan om Griekse, Cyrillische (dat wordt gebruikt in Russisch,
   Slavisch en andere talen), Hebreeuwse of Arabische letters.
</p>
<? if(strcasecmp($charset,"UTF-8")) { ?>
<p>Voor het Grieks moet je proberen elke letter om te zetten in de equivalente Latijnse letter(s).
   (Transliteration). In de proofreading interface vindt je een "Greek transliteration" hulpmiddel,
   waar dit heel gemakkelijk mee gaat.
</p>
<p>Klik op de "Greek Transliterator" knop bijna onderaan de proofreading interface.
   Het hulpmiddel verschijnt in een pop-up. In het gereedschap, klik op de Griekse letters die je
   ziet in het woord of de zin die je aan het omzetten bent. De bijpassende Latin-1 tekens verschijnen
   in de tekstbox. Als je klaar bent, knip en plak je de omgezette tekst in de bladzijde die je aan het
   proeflezen bent. Zet om de omgezette tekst de volgende markering: <tt>[Greek:&nbsp;</tt> en <tt>]</tt>.
   Bijvoorbeeld: <b>&Beta;&iota;&beta;&lambda;&omicron;&sigmaf;</b> wordt
   <tt>[Greek: Biblos]</tt>. ("Boek"&mdash;erg toepasselijk bij DP!)
</p>
<p>Als je onzeker bent over je omzetting, markeer hem dan met <tt>**</tt> om te zorgen dat de
   aandacht van de volgende proeflezer of de Post-Processor er op gevestigd wordt.
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
      of gebruik de "Greek Transliterator" pop-up in de proofreading interface.
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
   om te zorgen dat de volgende proeflezer of de Post-Processor het opmerkt.
</p>
<p>Zet om schrift dat niet zo gemakkelijk kan worden ingegeven, zoals Arabisch, passende markering:
   <tt>[Arabic:&nbsp;**]</tt> en laat het zoals in de scan. Zorg voor de <tt>**</tt> zodat
   de Post-Processor het later kan oplossen.
</p>
<? } ?>

<h3><a name="supers">Superscript</a>
 (<i><a href="proofreading_guidelines.php#supers">Superscripts</a></i>)</h3>
<p>Oudere boeken gebruikten vaak samentrekkingen als afkortingen, en drukten deze dan als superscript.
   Bijvoorbeeld:<br>
   &nbsp;&nbsp;&nbsp;&nbsp;Gen<sup>rl</sup> Washington defeated L<sup>d</sup> Cornwall's army.<br>
   Proeflees deze door een enkel dakje (<tt>^</tt>) v&oacute;&oacute;r de tekst in superscript te zetten, zo:<br>
   &nbsp;&nbsp;&nbsp;&nbsp;<tt>Gen^rl Washington defeated L^d Cornwall's army.</tt>
</p>

<h3><a name="subscr">Subscript</a>
 (<i><a href="proofreading_guidelines.php#subscr">Subscripts</a></i>)</h3>
<p>In wetenschappelijke werken wordt vaak subscript gebruikt, al komt het in andere boeken niet vaak voor.
   Proeflees tekst in subscript door een laag streepje <tt>_</tt> neer te zetten.
   <br>Bijvoorbeeld:
   <br>&nbsp;&nbsp;&nbsp;&nbsp;H<sub>2</sub>O.
   <br>wordt proefgelezen als
   <br>&nbsp;&nbsp;&nbsp;&nbsp;<tt>H_2O.<br></tt>
</p>

<h3><a name="font_sz">Verandering in grootte van het lettertype</a>
 (<i><a href="proofreading_guidelines.php#font_sz">Font Size Changes</a></i>)</h3>
<p>We markeren een verandering in grootte van lettertypes niet. De formatteerders doen dit later.
</p>

<h3><a name="italics">Cursief en Vet gedrukte Tekst</a>
 (<i><a href="proofreading_guidelines.php#italics">Italic and Bold Text</a></i>)</h3>
<p><i>Cursief gedrukte</i> tekst heeft soms <tt>&lt;i&gt;</tt> aan het begin en <tt>&lt;/i&gt;</tt>
   aan het eind van de tekst, die cursief gedrukt is. <b>Vetgedrukte</b> tekst (tekst die is gedrukt in een
   dikkere versie van het lettertype) heeft soms <tt>&lt;b&gt;</tt> aan het begin en <tt>&lt;/b&gt;</tt>
   aan het eind van het vet gedrukte stuk tekst. Verwijder deze formattering niet, tenzij het om rommel
   heen staat, die niet in het origineel voorkomt. Voeg het ook niet toe. De formatteerders doen dit later.
</p>
<!-- END RR -->


<h3><a name="small_caps">Woorden in <span style="font-variant: small-caps">kleine hoofdlettertjes (Klein Kapitaal)</span></a>
 (<i><a href="proofreading_guidelines.php#small_caps">Words in Small Capitals</a></i>)</h3>
<p><span style="font-variant: small-caps">Klein kapitaal</span>
   (hoofdletters die kleiner zijn dan de standaard hoofdletters) hebben soms
   <tt>&lt;sc&gt;</tt> v&oacute;&oacute;r het <span style="font-variant: small-caps">klein kapitaal</span>
   en <tt>&lt;/sc&gt;</tt> achter het <span style="font-variant: small-caps">klein kapitaal</span>.
   Alweer: verwijder deze formattering niet, tenzij het om rommel staat die in het origineel niet voorkomt.
   Voeg het ook niet toe als het er niet staat. De formatteerders doen dit later.
   Proeflees alleen de lettertekens. Laat ze in HOOFDLETTERS, of Klein Kapitaal,
   of kleine letters, precies zoals ze er staan.
</p>

<h3><a name="line_br">Regelafbrekingen</a>
 (<i><a href="proofreading_guidelines.php#line_br">Line Breaks</a></i>)</h3>
<p><b>Laat alle regelafbrekingen staan zoals ze zijn.</b> De volgende vrijwilligers, later in het proces,
   kunnen dan de regels in de tekst gemakkelijk vergelijken met de regels in het origineel.
   Let hier speciaal op als je <a href="#eol_hyphen">woorden met een afbreekstreepje</a> samenvoegt,
   of als je woorden rondom <a href="#em_dashes">em-dashes</a> verplaatst. Als de vorige vrijwilliger
   regelafbrekingen verwijderd heeft, herstel ze dan alsjeblieft, zodat ze weer zo zijn als in het origineel.
</p>


<!-- END RR -->
<!-- We should have an example right here for this. -->


<h3><a name="chap_head">Hoofdstuktitels</a>
 (<i><a href="proofreading_guidelines.php#chap_head">Chapter Headers</a></i>)</h3>
<p>Proeflees een hoofdstuktitel zoals hij in de tekst staat.
</p>
<p>Een hoofdstuktitel begint vaak wat lager op de pagina dan de <a href="#page_hf">koptekst</a>.
   Er staat geen paginanummer op dezelfde regel. Hoofdstuktitels worden vaak helemaal
   in hoofdletters gedrukt. Dan handhaaf je de hoofdletters. 
</p>
<p>Let op: de aanhalingstekens aan het begin van de eerste alinea ontbreken nog wel eens,
   &oacute;f doordat de uitgever ze er niet bij zette, &oacute;f doordat de OCR ze miste doordat de eerste
   letter in het origineel een extra grote hoofdletter is. Als de schrijver de alinea
   met dialoog begon, voeg dan de aanhalingstekens toe.
</p>
<!-- END RR -->

<h3><a name="para_space">Ruimte tussen Alinea's/Inspringingen</a>
 (<i><a href="proofreading_guidelines.php#para_space">Paragraph Spacing/Indenting</a></i>)</h3>
<p>Zet een lege regel voor het begin van een alinea, zelfs als de alinea bovenaan een bladzijde begint. 
   Aan het begin van een alinea hoef je niet in te springen, maar als een alinea al ingesprongen is, 
   hoef je die spaties niet te verwijderen&mdash;dat gebeurt automatisch tijdens het post-processen.
</p>
<p>Zie het voorbeeld bij de <a href="#para_side">Sidenotes</a>.
</p>

<h3><a name="page_hf">Koptekst en Voettekst</a>
 (<i><a href="proofreading_guidelines.php#page_hf">Page Headers/Page Footers</a></i>)</h3>
<p>Verwijder alle koptekst en voettekst, maar <em>niet</em> de <a href="#footnotes">voetnoten</a>.
</p>
<p>De koptekst staat gewoonlijk bovenaan het origineel. Er staat vaak een paginanummer op dezelfde regel.
   Koptekst kan het hele boek door hetzelfde zijn. Dan is het vaak de titel van het boek en de naam van de schrijver.
   Of hij is per hoofdstuk hetzelfde, vaak het nummer van het hoofdstuk. Of de koptekst is op elke pagina anders,
   dan wordt beschreven wat er op de pagina gebeurt. Verwijder alle koptekst, inclusief het paginanummer.
</p>
<!-- END RR -->

<p>De <a href="#chap_head">titel van een hoofdstuk</a> (chapter header) begint lager op de pagina en heeft
   geen paginanummer op dezelfde regel. Zie het volgende deel voor een voorbeeld. 
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
    <tr><th align="left" bgcolor="cornsilk">Correct proefgelezen tekst:</th></tr>
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
</td></tr></table>
      </td>
    </tr>
  </tbody>
</table>

<h3><a name="illust">Illustraties</a>
 (<i><a href="proofreading_guidelines.php#illust">Illustrations</a></i>)</h3>
<p>Proeflees tekst bij een illustratie zoals hij gedrukt is, handhaaf regelafbrekingen.
   Als een bijschrift midden in een alinea terecht is gekomen, voeg dan voor en na het bijschrift
   lege regels in om het apart te houden van de rest van de tekst. Als er wel een illustratie maar <b>geen</b>
   bijschrift is, dan laten we het markeren van de illustratie aan de formatteerders over.
</p>
<p>De meeste pagina's met een illustratie zonder bijschrift zullen al gemarkeerd zijn
   met <tt>[Blank Page]</tt>. Laat deze markering voor wat hij is.
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
      <th align="left" bgcolor="cornsilk">Correct proefgelezen tekst:</th>
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
     <th align="left" bgcolor="cornsilk">Origineel: (Illustratie midden in een alinea)</th>
   </tr>
   <tr align="left">
     <td width="100%" valign="top"> <img src="illust2.png" alt=""
         width="500" height="514"> <br>
     </td>
   </tr>
   <tr>
     <th align="left" bgcolor="cornsilk">Correct proefgelezen tekst:</th>
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

<h3><a name="footnotes">Voetnoten/Eindnoten</a>
 (<i><a href="proofreading_guidelines.php#footnotes">Footnotes/Endnotes</a></i>)</h3>
<p>Voetnoten worden onderaan de bladzijde geplaatst, en op de plaats in de tekst,
   waar naar de voetnoot verwezen wordt, wordt een markering geplaatst. 
</p>
<p>Het cijfer, de letter of het andere teken dat de plaats van de voetnoot markeert,
   moet tussen vierkante haken gezet worden (<tt>[</tt> and <tt>]</tt>) en meteen na het
   woord waar de voetnoot bij hoort, gezet worden<tt>[1]</tt>. Als er interpunctie naast het
   woord staat, dan komt het symbool dat de voetnoot markeert, meteen naast de interpunctie.<tt>[2]</tt>
   Zie de voorbeelden hieronder, en ook in deze tekst.
</p>
<p>Als voetnoten gemarkeerd zijn door een serie bijzondere tekens (*, &dagger;, &Dagger;, &sect;, enz.),
   dan vervangen we deze door <tt>[*]</tt> in de tekst en <tt>*</tt> bij de voetnoot zelf.
</p>
<p>Proeflees de tekst van de voetnoot zoals hij gedrukt is, en handhaaf de regelafbrekingen
   Laat de voetnoot onder aan de bladzijde staan. Zorg er wel voor, dat je dezelfde markering gebruikt
   voor de voetnoot, als wat er in de tekst staat waar de voetnoot naar verwijst. 
</p>
<p>Als er meer dan &eacute;&eacute;n voetnoot is, moet er een lege regel tussen de voetnoten gezet worden.
</p>

<!-- END RR -->

<p>Zie <a href="#page_hf">Koptekst/Voettekst</a> origineel&amp;tekst voor een voorbeeld van een voetnoot.
</p>
<p>Als er in de tekst naar een voetnoot of eindnoot verwezen wordt, maar de noot staat niet op dezelfde
   bladzijde, laat dan het nummer of het symbool waarmee de noot gemarkeerd wordt, staan en zet er
   vierkante haken omheen <tt>[</tt> en <tt>]</tt>. Dit gebeurt regelmatig in wetenschappelijke en technische boeken,
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
      <th valign="top" align="left" bgcolor="cornsilk">Correct proefgelezen tekst:</th>
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

<p>In sommige boeken worden de voetnoten door een horizontale lijn apart gezet van de tekst.
   We handhaven deze lijn niet, dus laat alsjeblieft alleen een lege regel over tussen de tekst en de voetnoten.
   (Zie het voorbeeld hierboven.)
</p>
<p><b>Eindnoten</b> zijn voetnoten die samen aan het eind van een hoofdstuk of aan het eind
   van een boek staan. Ze worden op dezelfde manier proefgelezen als voetnoten.
   Als je ergens in de tekst een verwijzing naar een eindnoot vindt, zet er <tt>[</tt> en
   <tt>]</tt> omheen. Als je een van de laatste pagina's aan het proeflezen bent, waar al
   de eindnoten staan, voeg dan na elke noot een lege regel in, zodat het duidelijk is waar
   elke noot begint en eindigt.
</p>

<!-- Need an example of Endnotes, maybe? Good idea!-->

<p><b>Voetnoten in <a href="#poetry">Po&euml;zie</a></b>
   moet op dezelfde manier behandeld worden als andere voetnoten.<br /> <br />

<b>Voetnoten in <a href="#tables">Tabellen</a></b> moeten op de plaats blijven waar ze in de originele tekst staan.
</p>

<table width="100%" align="center" border="1" cellpadding="4" cellspacing="0" summary="Footnotes">
  <tbody>
    <tr><th align="left" bgcolor="cornsilk">Origineel, Po&euml;zie met Voetnoot:</th></tr>
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
    <tr><th align="left" bgcolor="cornsilk">Correct proefgelezen tekst:</th></tr>
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

<h3><a name="para_side">Beschrijvingen naast een Alinea (Sidenotes)</a>
 (<i><a href="proofreading_guidelines.php#para_side">Paragraph Side-Descriptions (Sidenotes)</a></i>)</h3>
<p>Sommige boeken hebben korte beschrijvingen van de alinea naast de tekst. Deze heten sidenotes.
   Proeflees de tekst van de sidenote zoals hij gedrukt is, en handhaaf regelafbrekingen.
   Laat een lege regel voor en na de sidenote, zodat hij apart blijft van de tekst eromheen.
   De OCR kan de sidenotes overal op de pagina zetten, en kan de tekst zelfs door de rest van de
   tekst heen zetten. Haal dan de tekst uit elkaar, zodat de tekst van de sidenote apart staat.
   De plaats van de sidenote op de bladzijde is niet van belang.
   De formatteerders zullen ze op de goede plaats zetten. 
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
      <th align="left" bgcolor="cornsilk">Correct proefgelezen tekst:</th>
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
</td></tr></table>
      </td>
    </tr>
  </tbody>
</table>

<h3><a name="mult_col">Meerdere kolommen</a>
 (<i><a href="proofreading_guidelines.php#mult_col">Multiple Columns</a></i>)</h3>
<p>Proeflees gewone tekst die in 2 kolommen gedrukt is, als een enkele kolom.
</p>
<p>Stukjes met meerdere kolommen binnen een paragraaf van &eacute;&eacute;n kolom, moeten als &eacute;&eacute;n kolom proefgelezen worden,
   door de tekst van de linker kolom eerst te zetten, de tekst van de volgende eronder enzovoort.
   Je hoeft niet te markeren waar de kolommen gesplitst waren, je kunt ze gewoon achter elkaar zetten.
</p>
<p>Zie ook de hoofdstukken <a href="#bk_index">Indexen</a> en
   <a href="#tables">Tabellen</a> van deze Proeflees-Richtlijnen.
</p>

<h3><a name="tables">Tabellen</a>
 (<i><a href="proofreading_guidelines.php#tables">Tables</a></i>)</h3>
<p>Het is de taak van de proeflezer om te zorgen dat alle informatie in de tabel correct is proefgelezen.
   Het formatteren wordt later gedaan. Zorg wel dat er voldoende ruimte tussen de gegevens op &eacute;&eacute;n regel is,
   zodat duidelijk is waar elk gegeven begint en eindigt. Handhaaf de regelafbrekingen.
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
    <tr><th align="left" bgcolor="cornsilk">Correct proefgelezen tekst:</th></tr>
    <tr>
      <td width="100%" valign="top">
<table summary="" border="0" align="left"><tr><td>
<pre><tt>
Deg. C.  Millimeters of Mercury. Gasolene.
Pure Benzene.

-10&deg;  13.4  43.5
 0&deg;  26.6  81.0
+10&deg;  46.6  132.0
20&deg;  76.3  203.0
40&deg;  182.0  301.8
</tt></pre>
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
    <tr><th align="left" bgcolor="cornsilk">Correct proefgelezen tekst:</th></tr>
    <tr>
      <td width="100%" valign="top">
<table summary="" border="0" align="left"><tr><td>
<pre><tt>
TABLE II.

Flat strips compared   Copper.   Copper.
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
Same strip rolled up in  Same, 16 wires bound
  the form of wire  17   15    close together   18    12
</tt></pre>
</td></tr></table>
      </td>
    </tr>
  </tbody>
</table>

<h3><a name="poetry">Po&euml;zie/Epigrammen</a>
 (<i><a href="proofreading_guidelines.php#poetry">Poetry/Epigrams</a></i>)</h3>
<p>Voeg een lege regel toe voor de start van het gedicht of epigram en een lege regel na het
   eind ervan, zodat de formatteerders begin en eind goed kunnen zien.
</p>
<p>Sluit de regels links aan; handhaaf de regelafbrekingen. Probeer de po&euml;zie niet in te springen of
   te centreren. De Formatteerders zorgen daar voor. Voeg wel een lege regel toe tussen de coupletten.
</p>
<p><b>Voetnoten</b> in po&euml;zie moeten worden behandeld als gewone voetnoten. Zie
   <a href="#footnotes">Voetnoten</a> voor meer details.
</p>
<p><b>Regelnummers</b> in po&euml;zie moeten worden behouden. Houd ze apart van de tekst d.m.v.
   een paar spaties. Zie <a href="#line_no">Regelnummers</a> voor details.
</p>
<p>Controleer de  <a href="#comments">Project Comments</a> voor de specifieke tekst waar je aan werkt.
</p>
<!-- END RR -->

<br>
<!-- Need an example that shows overly long lines of poetry, rather than relative indentation -->

<table width="100%" align="center" border="1"  cellpadding="4"
      cellspacing="0" summary="Poetry Example">
 <tbody>
   <tr><th align="left" bgcolor="cornsilk">Origineel:</th></tr>
   <tr align="left">
     <th width="100%" valign="top"> <img src="poetry.png" alt=""
         width="500" height="508"> <br>
     </th>
   </tr>
   <tr><th align="left" bgcolor="cornsilk">Correct proefgelezen tekst:</th></tr>
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
Round the elm-tree bole are in tiny leaf,<br>
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

<h3><a name="line_no">Regelnummers</a>
 (<i><a href="proofreading_guidelines.php#line_no">Line Numbers</a></i>)</h3>
<p>Laat regelnummers staan. Gebruik een paar spaties om ze apart te zetten van de rest van de tekst
   op die regel, zo dat de formatteerders ze gemakkelijk kunnen vinden. 
</p>
<p>Regelnummers zijn de nummers in de kantlijn. Soms staan ze op elke regel, soms op elke vijfde
   of tiende regel. Ze komen vaak voor in gedichtenbundels. Ze zijn nuttig voor de lezers van e-boeken,
   aangezien po&euml;zie niet wordt geherformatteerd in de e-boek versie.
</p>

<!-- END RR -->
<!-- We need an example image and text for this. -->

<h3><a name="next_word">Losstaand woord onderaan een pagina</a>
 (<i><a href="proofreading_guidelines.php#next_word">Single word at bottom of page</a></i>)</h3>
<p>Behandel een dergelijk woord door het woord te verwijderen, zelfs als het de tweede helft betreft van een afgebroken woord.
</p>
<p>In sommige oudere boeken wordt met een dergelijk losstaand woord ("catchword", meestal tegen de
   rechter kantlijn van de pagina) aangegeven wat het eerste woord is op de volgende pagina van het
   boek (ook wel "incipit" genoemd). Dit werd gebruikt om de zetter te helpen de juiste achterzijde
   ("verso") te zetten, en om het zijn helpers gemakkelijker te maken de pagina's klaar te maken voor
   het binden. Ook diende het woord om te voorkomen dat de lezer meer dan &eacute;&eacute;n pagina tegelijk omsloeg.
</p>
<!-- END RR -->
<!-- We need an example here! -->

<h3><a name="blank_pg">Lege Pagina</a> (<i><a href="proofreading_guidelines.php#blank_pg">Blank Page</a></i>)</h3>
<p>De meeste lege bladzijden, of bladzijden met een illustratie zonder tekst, zullen al gemarkeerd
   zijn met <tt>[Blank Page]</tt>. Laat deze markering zoals hij is. Als de bladzijde leeg is,
   en er staat ook geen [Blank Page], hoef je het ook niet toe te voegen.
</p>
<p>Als er wel tekst is, waar de te proeflezen tekst hoort te staan, maar niet in het origineel,
   of als er wel iets in het origineel staat maar er is geen tekst, volg de aanwijzingen voor een
   <a href="#bad_image">Slecht beeld (Bad Image)</a> of een <a href="#bad_text">Slechte tekst (Bad Text)</a>.
</p>

<h3><a name="title_pg">Titelpagina aan de voor- of achterkant</a>
 (<i><a href="proofreading_guidelines.php#title_pg">Front/Back Title Page</a></i>)</h3>
<p>Proeflees alle tekst, inclusief het jaar waarin het boek is uitgegeven of het jaar van het copyright,
   precies zoal het op de pagina's gedrukt is, hoofdletters, kleine letters, enz. 
</p>
<p>In oudere boeken wordt de eerste letter vaak groot en bewerkt weergegeven, proeflees deze letter gewoon als de letter.
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
      <th align="left" bgcolor="cornsilk">Correct proefgelezen tekst:</th>
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
      <p><tt>WITH FRONTISPIECE BY<br>
         C. ALLAN GILBERT</tt></p>
      <p><tt>NEW YORK<br>
         DODD, MEAD AND COMPANY<br>
         1917</tt></p>
</td></tr></table>
      </td>
    </tr>
  </tbody>
</table>

<h3><a name="toc">Inhoudsopgave</a>
 (<i><a href="proofreading_guidelines.php#toc">Table of Contents</a></i>)</h3>
<p>Proeflees de inhoudsopgave precies zoals deze in het boek gedrukt staat, hoofdletters, kleine letters enz.
   Paginanummers moeten behouden blijven. 
</p>
<p>Negeer alle punten of sterretjes die gebruikt zijn om de paginanummers op &eacute;&eacute;n lijn te krijgen. Deze worden later verwijderd.
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
      <th align="left" bgcolor="cornsilk">Correct proefgelezen tekst:</th>
    </tr>
    <tr>
      <td width="100%" valign="top">
<table summary="" border="0" align="left"><tr><td>
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
          OUTSIDE&nbsp;&nbsp;&nbsp;,,,..&nbsp;&nbsp;....,&nbsp;221<br>
      </tt></p>
</td></tr></table>
      </td>
    </tr>
  </tbody>
</table>

<h3><a name="bk_index">Indexen</a>
 (<i><a href="proofreading_guidelines.php#bk_index">Indexes</a></i>)</h3>
<p>Handhaaf de paginanummers in index pagina's. Je hoeft de nummers niet netjes op een rij te
   zetten zoals ze in het origineel staan. Je hoeft er alleen voor te zorgen dat de getallen en
   de interpunctie zijn zoals in de scan. Handhaaf de regelafbrekingen. 
</p>
<p>Het formatteren van indexen gebeurt later. De proeflezer moet er voor zorgen dat de tekst en de getallen kloppen. 
</p>
<!-- END RR -->


<h3><a name="play_n">Toneelstukken: Namen van Spelers/Regieaanwijzingen</a>
 (<i><a href="proofreading_guidelines.php#play_n">Plays: Actor Names/Stage Directions</a></i>)</h3>
<ul compact>
 <li>Behandel een verandering van spreker in een dialoog als een nieuwe alinea, met een lege regel ertussen.</li>
 <li>Regieaanwijzingen worden proefgelezen zoals ze in de originele tekst staan.<br>
   Als ze op een aparte regel staan, laat ze daar dan zo staan. 
   Staan ze aan het eind van een regel met dialoog, dan laat je ze daar.<br>
   Regieaanwijzingen beginnen vaak met een haakje openen <tt>[</tt> en laten het haakje sluiten <tt>]</tt> weg. 
   Deze gewoonte wordt gehandhaafd; sluit de haakjes niet.</li>
 <li>Soms, vooral in metrische toneelstukken, wordt een woord gesplitst omdat de bladzijde te smal is.
   Het tweede stukje van het woord staat vaak op de regel eronder of erboven, na een (, in plaats van op een eigen regel.
   Behandel dit als normale <a href="#eol_hyphen">woordafbreking aan het eind van een regel</a>.<br> 
   Zie het <a href="#play4">voorbeeld</a>.</li>
</ul>
<p>Let goed op de <a href="#comments">Project Comments</a>, de Project Manager kan een andere manier van proeflezen vragen.
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
      <th align="left" bgcolor="cornsilk">Correct proefgelezen tekst:</th>
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
      <th align="left" bgcolor="cornsilk">Correct proefgelezen tekst:</th>
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

<h3><a name="anything">Alles wat op een speciale manier aangepakt moet worden, of waar je onzeker over bent</a>
 (<i><a href="proofreading_guidelines.php#anything">Anything else that needs special handling or that you're unsure of</a></i>)</h3>
<p>Als je bij het proeflezen iets tegenkomt dat niet in deze richtlijnen behandeld wordt, en waarvan je
   wel denkt dat het op een speciale manier aangepakt moet worden, of als je niet zeker bent hoe het
   aan te pakken, post dan je vraag, onder vermelding van het png (pagina) nummer, in de Project Discussie.
   In de <a href="#comments">Project Comments</a> vind je een link naar de discussie die specifiek voor dat project is.
   Je kunt ook een aantekening in de proefgelezen tekst zetten, waarin je het probleem uitlegt. Je aantekening zal
   aan de volgende proeflezer, formatteerder of de Post-Processor uitleggen wat het probleem of de vraag is.
</p>
<p>Begin je aantekening met een vierkant haakje en twee sterren <tt>[**</tt> en sluit hem af met een vierkant
   haakje <tt>]</tt>. Dit onderscheidt je aantekening van de tekst van de schrijver en geeft de Post-Processor
   een duidelijk signaal om even te stoppen en zorgvuldig tekst &amp; origineel te vergelijken om een evt.
   probleem aan te pakken. Alle commentaar dat gemaakt is door vrijwilligers v&oacute;&oacute;r je, <b>moet</b> blijven staan.
   Of je het er mee eens bent of niet kun je toevoegen, maar je mag het commentaar absoluut niet verwijderen.
   Als je een bron hebt gevonden die het probleem verheldert, verwijs daar dan naar, zodat de Post-Processor
   er ook naar kan verwijzen.
</p>
<p>Als je in een latere ronde aan het proeflezen bent, en je stuit op een aantekening van een vrijwilliger
   in een vorige ronde, waar je het antwoord op weet, neem dan even de moeite om feedback te geven.
   Je klikt in de proofreading interface op de naam van de betreffende vrijwilliger en stuurt ze een
   priv&eacute; boodschap (<i>private message</i>) waarin je uitlegt hoe de situatie aangepakt kan worden.
   Zoals eerder vermeld: verwijder alsjeblieft de aantekening niet.
</p>

<h3><a name="prev_notes">Aantekeningen/Commentaar van eerdere proeflezers</a>
 (<i><a href="proofreading_guidelines.php#prev_notes">Previous Proofreaders' Notes</a></i>)</h3>
<p>Alle commentaar dat gemaakt is door vrijwilligers v&oacute;&oacute;r je <b>moet</b> blijven staan. Of je het er mee eens
   bent of niet kun je toevoegen, maar je mag het commentaar absoluut niet verwijderen.
   Als je een bron hebt gevonden die het probleem verheldert, verwijs daar dan naar,
   zodat de Post-Processor er ook naar kan verwijzen.
</p>
<p>Als je in een latere ronde aan het proeflezen bent, en je stuit op een aantekening van een vrijwilliger
   in een vorige ronde, waar je het antwoord op weet, neem dan even de moeite om feedback te geven.
   Je klikt in de proofreading interface op de naam van de betreffende vrijwilliger en stuurt ze een
   priv&eacute; boodschap (<i>private message</i>) waarin je uitlegt hoe de situatie aangepakt kan worden.
   Zoals eerder vermeld: verwijder alsjeblieft de aantekening niet.
</p>
<!-- END RR -->

<table width="100%" border="0" cellspacing="0" summary="Veel voorkomende problemen">
  <tbody>
    <tr>
      <td bgcolor="silver">&nbsp;</td>
    </tr>
  </tbody>
</table>

<h2>Veel voorkomende problemen</h2>

<h3><a name="OCR_1lI">Problemen met de OCR: 1-l-I</a>
 (<i><a href="proofreading_guidelines.php#OCR_1lI">OCR Problems: 1-l-I</a></i>)</h3>
<p>De OCR heeft vaak moeite met het onderscheiden van het getal "1", de kleine letter "l" (el) en de 
   hoofdletter "I". Dit geldt in het bijzonder als de bladzijden van het origineel in niet al te beste conditie zijn. 
</p>
<p>Let hierop. Lees de context van de zin om te bepalen wat het correcte teken is.
   Maar pas op&mdash;vaak "corrigeert" je geest dit automatisch terwijl je aan het lezen bent.
</p>
<p>Je merkt dit soort dingen veel eerder op als je een 'mono-spaced' lettertype zoals
   <a href="font_sample.php">DPCustomMono</a> of Courier gebruikt.
</p>

<h3><a name="OCR_0O">Problemen met de OCR: 0-O</a>
 (<i><a href="proofreading_guidelines.php#OCR_0O">OCR Problems: 0-O</a></i>)</h3>
<p>De OCR heeft vaak moeite met het onderscheiden van het getal "0" (nul) en de hoofdletter "O".
   Dit geldt in het bijzonder als de bladzijden van het origineel in niet al te beste conditie zijn.
</p>
<p>Let hierop. Lees de context van de zin om te bepalen wat het correcte teken is.
   Maar pas op&mdash;vaak "corrigeert" je geest dit automatisch terwijl je aan het lezen bent.
</p>
<p>Je merkt dit soort dingen veel eerder op als je een 'mono-spaced' lettertype zoals
   <a href="font_sample.php">DPCustomMono</a> of Courier gebruikt.
</p>

<h3><a name="OCR_hyphen">Problemen met de OCR: Afbreekstreepjes en andere streepjes</a>
 (<i><a href="proofreading_guidelines.php#OCR_hyphen">OCR Problems: Hyphens and Dashes</a></i>)</h3>
<p>De OCR heeft vaak moeite met het onderscheiden van afbreekstreepjes van andere streepjes.
   Proeflees deze zorgvuldig&mdash;geOCRde tekst heeft vaak maar &eacute;&eacute;n afbreekstreepje voor een em-dash,
   terwijl het er twee moeten zijn. Zie de richtlijnen voor <a href="#eol_hyphen">afgebroken woorden</a>
   en voor <a href="#em-dashes">Liggende streepjes</a> voor uitgebreidere informatie. 
</p> 
<p>Je merkt dit soort dingen veel eerder op als je een 'mono-spaced' lettertype zoals
   <a href="font_sample.php">DPCustomMono</a> of Courier gebruikt.
</p>

<h3><a name="OCR_scanno">Problemen met de OCR: Scanno's</a>
 (<i><a href="proofreading_guidelines.php#OCR_scanno">OCR Problems: Scannos</a></i>)</h3>
<p>Een ander veel voorkomend probleem van de OCR is het niet goed herkennen van tekens. We noemen deze fouten
   "scanno's" (zoiets als "typo's"). Deze foutieve herkenning kan leiden tot woorden die:
</p>
<ul compact>
   <li>op het eerste gezicht correct lijken, maar in werkelijkheid een spellingsfout bevatten.<br />
       Deze woorden kun je meestal onderscheppen door WordCheck te gebruiken.</li>
   <li>veranderd zijn naar een ander, maar wel goed woord, dat niet overeenkomt met het origineel.<br />
       Dit kan alleen opgemerkt worden door iemand die de tekst echt leest.</li>
</ul>
<p>In het Engels is het meest voorkomende voorbeeld van het tweede type "and" dat door de OCR wordt weergegeven als "arid".
  Andere voorbeelden zijn: "eve" voor "eye", "Torn" voor "Tom", "train" voor "tram". In het Nederlands wordt "elken"
  vaak door "eiken" weergegeven, en "mensch" door "mensen". Dit type fout is veel moeilijker om op te sporen en
  we hebben een speciale term ervoor: "Stealth Scanno's".
  We verzamelen voorbeelden van Stealth Scannos in <a href="<? echo $Stealth_Scannos_URL; ?>">deze discussie</a>.
</p>
<p>Het opmerken van scanno's is veel gemakkelijker als je een 'mono-spaced' lettertype zoals
   <a href="font_sample.php">DPCustomMono</a> of Courier gebruikt.
</p>
<!-- END RR -->
<!-- More to be added.... -->

<h3><a name="hand_notes">Handgeschreven aantekeningen in een boek</a>
 (<i><a href="proofreading_guidelines.php#hand_notes">Handwritten Notes in Book</a></i>)</h3>
<p>Laat handgeschreven aantekeningen weg, behalve als de gedrukte tekst verbleekt en vervolgens overgetrokken is om
   de tekst beter zichtbaar te maken. Laat handgeschreven aantekeningen die door lezers e.d. in de kantlijn gezet zijn, weg.
</p>

<h3><a name="bad_image">Slecht beeld</a>
 (<i><a href="proofreading_guidelines.php#bad_image">Bad Image</a></i>)</h3>
<p>Als het beeld van een origineel niet goed is (laadt niet, een stuk er af, onmogelijk om te lezen)
   post dan alsjeblieft in het <a href="#forums">Project Discussie</a> over dit slechte origineel.
   Klik niet op "Return Page to Round", want dan wordt de pagina weer aan de volgende proeflezer gepresenteerd.
   Klik op de "Report Bad Page" button, waardoor de pagina in "quarantaine" gaat. 
</p>
<p>Houd in de gaten dat sommige pagina's erg groot zijn, en dat het dan gebruikelijk is dat je browser
   moeite heeft om ze te laten zien, zeker als je meerdere vensters open hebt of als je een oudere
   computer gebruikt. Probeer, voordat je een slecht origineel meldt, te klikken op de "Image"
   button onderaan je pagina om uitsluitend het origineel in een nieuw venster te tonen.
   Als dat er wel goed uitziet, dan zit het probleem waarschijnlijk in je browser of je systeem.
</p>
<p>Wat meer voorkomt, is dat het origineel wel goed is, maar dat de geOCRde tekst de eerste paar
   regels tekst mist. Typ deze regels erbij, alsjeblieft. Als bijna de hele tekst ontbreekt,
   typ hem dan in (als je dat op kunt brengen) of klik op de "Return Page to Round" button en
   de pagina wordt aan iemand anders toegekend. Als er meerdere pagina's zoals deze zijn, post
   dan in het <a href="#forums">Project Discussie</a> om de Project Manager in te lichten.
</p>

<h3><a name="bad_text">Verkeerd beeld voor de tekst</a>
 (<i><a href="proofreading_guidelines.php#bad_text">Wrong Image for Text</a></i>)</h3>
<p>Als er een verkeerd beeld gegeven wordt voor de tekst, post dan alsjeblieft in de
   <a href="#forums">Project Discussie</a>. Klik niet op "Return Page to Round",
   want dan wordt de pagina weer aan de volgende proeflezer gepresenteerd.
   Klik op de "Report Bad Page" button, waardoor de pagina in "quarantaine" gaat. 
</p>

<h3><a name="round1">Eerder gemaakte proefleesvergissingen</a>
 (<i><a href="proofreading_guidelines.php#round1">Previous Proofreader Mistakes</a></i>)</h3>
<p>Als een eerdere vrijwilliger veel vergissingen maakte, of allerlei dingen miste, neem dan alsjeblieft
   even de tijd om feedback te geven. Klik op de naam van de vrijwilliger in de proofreading
   interface en stuur hem/haar een priv&eacute; boodschap, waarin je uitlegt hoe een dergelijke situatie kan
   worden aangepakt, zodat hij/zij dat voor een volgende keer weet.
</p>
<p><em>Wees daarbij alsjeblieft aardig!</em> Iedereen hier is een vrijwilliger en doet haar of zijn best.
   De bedoeling van de feedback is om te informeren over de juiste manier van proeflezen,
   niet om te bekritiseren. Geef een concreet voorbeeld, om te laten zien wat de vrijwilliger
   gedaan heeft, en wat hij/zij had moeten doen.
</p>
<p>Als de vrijwilliger v&oacute;&oacute;r je uitstekend werk verricht heeft, kun je daar ook een boodschap over
   sturen&mdash;in het bijzonder als de pagina extra moeilijk was.
</p>

<h3><a name="p_errors">Vergissingen van de drukker/Spelfouten</a>
 (<i><a href="proofreading_guidelines.php#p_errors">Printer Errors/Misspellings</a></i>)</h3>
<p>Corrigeer alle woorden die door de OCR fout zijn ge&iuml;nterpreteerd (scanno), maar verbeter
   geen spel- of zetfouten die in het origineel voorkomen. In veel oudere teksten werden woorden
   anders gespeld dan we tegenwoordig doen. We handhaven deze oude spelling, inclusief accenten.
</p>
<p>Als je het niet zeker weet, maak dan een aantekening in de teskt <tt>[**typo voor tekst?]</tt>
   en stel een vraag in de Project Discussie. Als je iets verandert, maak dan een aantekening
   wat je veranderd hebt: <tt>[**typo verbeterd, "teskt" veranderd in "tekst"]</tt>. Zorg dat de
   twee sterretjes <tt>**</tt> er staan, zodat de Post-Processor de aantekening niet over het hoofd ziet. 
</p>

<h3><a name="f_errors">Feitelijke fouten in de tekst</a>
 (<i><a href="proofreading_guidelines.php#f_errors">Factual Errors in Texts</a></i>)</h3>
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
 (<i><a href="proofreading_guidelines.php#uncertain">Uncertain Items</a></i>)</h3>
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
