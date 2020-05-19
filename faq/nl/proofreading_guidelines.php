<?php

// Translated by PGDP Team Netherlands; file received from user Clog 17 Feb 2009

$relPath='../../pinc/';
include_once($relPath.'base.inc');
include_once($relPath.'faq.inc');
include_once($relPath.'pg.inc');
include_once($relPath.'theme.inc');

maybe_redirect_to_external_faq("nl");

$theme_args["css_data"] = "p.backtotop {text-align:right; font-size:75%;margin-right:-5%;}";

output_header('Proeflees-Richtlijnen', NO_STATSBAR, $theme_args);

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

<h1 align="center"><a name="top">Proeflees-Richtlijnen</a></h1>

<h3 align="center">Versie 2.0, herzien 7 juni 2009 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
  <a href="../dochist.php"><font size="-1">(Herzieningsgeschiedenis)</font></a></h3>

<HR>
<h4>Dit document is een vertaling van de Engelse Proofreading Guidelines.<BR>
 Bij elk hoofdstuk is een link opgenomen naar het corresponderende hoofdstuk in die Guidelines.</h4>

<HR>

<p>Proeflees-Richtlijnen <a href="../proofreading_guidelines.php">in het Engels</a> /
      Proofreading Guidelines <a href="../proofreading_guidelines.php">in English</a><br>
    Proeflees-Richtlijnen <a href="../fr/proofreading_guidelines.php">in het Frans</a> /
      Directives de Relecture et Correction <a href="../fr/proofreading_guidelines.php">en fran&ccedil;ais</a><br>
    Proeflees-Richtlijnen <a href="../pt/proofreading_guidelines.php">in het Portugees</a> /
      Regras de Revis&atilde;o <a href="../pt/proofreading_guidelines.php">em Portugu&ecirc;s</a><br>
    Proeflees-Richtlijnen <a href="../es/proofreading_guidelines.php">in het Spaans</a> /
      Reglas de Revisi&oacute;n <a href="../es/proofreading_guidelines.php">en espa&ntilde;ol</a><br>
    Proeflees-Richtlijnen <a href="../de/proofreading_guidelines.php">in het Duits</a> /
      Korrekturlese-Richtlinien <a href="../de/proofreading_guidelines.php">auf Deutsch</a><br>
    Proeflees-Richtlijnen <a href="../it/proofreading_guidelines.php">in het Italiaans</a> /
      Regole di Correzione <a href="../it/proofreading_guidelines.php">in Italiano</a><br>
</p>

<p>Bekijk de <a href="../../quiz/start.php?show_only=PQ">Proofreading Quiz and Tutorial</a>! (dit document bestaat alleen in een Engelse versie)
</p>

<table border="0" cellspacing="0" width="100%" summary="Table of Contents">
  <tbody>
  <tr>
    <td width="1" bgcolor="silver">&nbsp;</td>
    <td bgcolor="silver" align="center"><h2 style="margin-bottom: 0; margin-top: 0;">Inhoudsopgave</h2></td>
  </tr>
  <tr>
    <td width="1" bgcolor="silver">&nbsp;</td>
    <td bgcolor="white" align="left">
      <ul>
        <li><a href="#prime">De Hoofdregel</a></li>
        <li><a href="#summary">Samenvatting van de Richtlijnen</a></li>
        <li><a href="#about">Over Dit Document</a></li>
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
        <li style="margin-top:.25em;"><font size="+1">Proeflezen op het Letter-Niveau:</font>
        <ul>
          <li><a href="#double_q">Dubbele Aanhalingstekens</a></li>
          <li><a href="#single_q">Enkele Aanhalingstekens</a></li>
          <li><a href="#quote_ea">Aanhalingstekens op Iedere Regel</a></li>
          <li><a href="#period_s">Punten aan het Eind van een Zin</a></li>
          <li><a href="#punctuat">Interpunctie en Spaties</a></li>
          <li><a href="#extra_sp">Extra Spaties of Tabs Tussen Woorden</a></li>
          <li><a href="#trail_s">Spaties aan het Eind van een Regel</a></li>
          <li><a href="#em_dashes">Liggend streepje: Koppelteken, Gedachtestreepje, Minteken</a></li>
          <li><a href="#eol_hyphen">Koppelteken en Streepjes aan het Eind van een Regel</a></li>
          <li><a href="#eop_hyphen">Koppelteken en Streepjes aan het Eind van een Bladzijde</a></li>
          <li><a href="#period_p">Beletselteken ofwel Ellips &quot;&hellip;&quot;</a></li>
          <li><a href="#contract">Samentrekkingen</a></li>
          <li><a href="#fract_s">Breuken</a></li>
          <li><a href="#a_chars">Letters met Accenten/Niet-ASCII Letters</a></li>
          <li><a href="#d_chars">Letters met Diakritische Tekens</a></li>
          <li><a href="#f_chars">Niet-Latijnse Lettertekens</a></li>
          <li><a href="#supers">Superscript</a></li>
          <li><a href="#subscr">Subscript</a></li>
          <li><a href="#drop_caps">Grote, Versierde Hoofdletter aan het Begin van een Regel</a></li>
          <li><a href="#small_caps">Woorden in <span style="font-variant: small-caps">Kleine Hoofdlettertjes (Klein Kapitaal)</span></a></li>
        </ul></li>
        <li style="margin-top:.25em;"><font size="+1">Proeflezen op het Alinea-Niveau:</font>
        <ul>
          <li><a href="#line_br">Regelafbrekingen</a></li>
          <li><a href="#chap_head">Hoofdstuktitels</a></li>
          <li><a href="#para_space">Ruimte Tussen Alinea's/Inspringingen</a></li>
          <li><a href="#page_hf">Koptekst en Voettekst</a></li>
          <li><a href="#illust">Illustraties</a></li>
          <li><a href="#footnotes">Voetnoten/Eindnoten</a></li>
          <li><a href="#para_side">Beschrijvingen naast een Alinea</a></li>
          <li><a href="#mult_col">Meerdere Kolommen</a></li>
          <li><a href="#tables">Tabellen</a></li>
          <li><a href="#poetry">Po&euml;zie/Epigrammen</a></li>
          <li><a href="#line_no">Regelnummers</a></li>
          <li><a href="#next_word">Losstaand Woord Onderaan een Pagina</a></li>
        </ul></li>
        <li style="margin-top:.25em;"><font size="+1">Proeflezen op het Pagina-Niveau:</font>
        <ul>
          <li><a href="#blank_pg">Lege Pagina</a></li>
          <li><a href="#title_pg">Titelpagina aan de Voor- of Achterkant</a></li>
          <li><a href="#toc">Inhoudsopgave</a></li>
          <li><a href="#bk_index">Indexen</a></li>
          <li><a href="#play_n">Toneelstukken: Namen van Spelers/Regieaanwijzingen</a></li>
        </ul></li>
        <li><a href="#anything">Alles wat op een speciale manier aangepakt moet worden, of waar je onzeker over bent</a></li>
        <li><a href="#prev_notes">Aantekeningen/Commentaar van Eerdere Proeflezers</a></li>
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
          <li><a href="#formatting">Formattering</a></li>
          <li><a href="#common_OCR">Veel Voorkomende Problemen met de OCR</a></li>
          <li><a href="#OCR_scanno">Problemen met de OCR: Scanno's</a></li>
          <li><a href="#OCR_raised_o">Problemen met de OCR: Is die &deg; &ordm; echt een graden-symbool?</a></li>
          <li><a href="#hand_notes">Handgeschreven Aantekeningen in een Boek</a></li>
          <li><a href="#bad_image">Slecht Beeld/Origineel</a></li>
          <li><a href="#bad_text">Verkeerd Beeld/Origineel voor de Tekst</a></li>
          <li><a href="#round1">Eerder Gemaakte Proefleesvergissingen</a></li>
          <li><a href="#p_errors">Vergissingen van de Drukker/Spelfouten</a></li>
          <li><a href="#f_errors">Feitelijke Fouten in de Tekst</a></li>
          <li><a href="#insert_char">Toevoegen van Speciale Letters</a></li>
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
<p>Het elektronische boek zoals de lezer het uiteindelijk zal zien, mogelijk vele jaren later, moet de bedoeling van de
   schrijver precies weergeven. Als de schrijver woorden op een vreemde manier spelde, dan laten we die spelling staan. Als de
   schrijver buitensporig racistische of bevooroordeelde uitspraken deed, dan laten we die staan. Als de schrijver elk derde
   woord superscript maakte, of elk derde woord van een komma of voetnoot voorzag, dan houden we de superscript, komma
   of voetnoot. We zijn proeflezers, en <b>geen</b> redacteuren; wanneer iets in de tekst niet overeenkomt met de oorspronkelijke
   pagina-scan, dan moet je tekst zo aanpassen dat deze overeenkomt. (Zie <a href="#p_errors">Vergissingen van de drukker</a>
   voor de manier waarop overduidelijke vergissingen van de drukker behandeld moeten worden.)
</p>
<p>Kleine typografische ge&iuml;nspireerde ingrepen in de tekst, die de betekenis van wat de schrijver schreef, niet aantasten, veranderen we wel.
   Bijvoorbeeld: woorden die aan het eind van een regel af werden gebroken, plakken we weer aan elkaar
   (<a href="#eol_hyphen">Koppelteken aan het Eind van een Regel</a>).
   Dit soort veranderingen helpen ons om een <em>consistente</em> versie van het boek te produceren.
   De richtlijnen voor het proeflezen die we volgen, zijn ontworpen om dit resultaat te bereiken.
   Lees de rest van deze Proeflees-Richtlijnen alsjeblieft zorgvuldig, met dit concept in je achterhoofd.
   Deze richtlijnen zijn <i>uitsluitend</i> bedoeld voor het proeflezen.
   Als proeflezer maak je de inhoud kloppend met de scan, terwijl later de formatteerders het uiterlijk kloppend maken.
</p>
<p>Om de volgende proeflezer, de formatteerders en de Post-Processor te ondersteunen, handhaven we de <a href="#line_br">regelafbrekingen</a>.
   Dit helpt hen om de regels in de tekst te vergelijken met de regels in het origineel.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Terug naar boven</a></p>


<h3><a name="summary">Samenvatting van de Richtlijnen</a>
 (<i><a href="#summary">Summary Guidelines</a></i>)</h3>
<p>De <a href="../proofing_summary.pdf">Proofreading Summary</a> (dit document bestaat alleen in een Engelse versie)
   is een kort printer-vriendelijk (.pdf) document van 2 pagina's, dat de voornaamste punten van deze richtlijnen samenvat
   en voorbeelden geeft hoe te proeflezen.
   Beginnende proeflezers wordt aangeraden dit document uit te printen en bij de hand te houden bij het proeflezen.
</p>
<p>Misschien moet je een .pdf lezer downloaden en installeren. Je kunt er
   <a href="http://www.adobe.com/products/acrobat/readstep2.html">hier</a> gratis &eacute;&eacute;n van Adobe&reg; krijgen.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Terug naar boven</a></p>


<h3><a name="about">Over Dit Document</a>
 (<i><a href="#about">About This Document</a></i>)</h3>
<p>Dit document is geschreven om de proeflees-regels uit te leggen. We gebruiken deze regels om consistentie te waarborgen,
   aangezien het proeflezen van &eacute;&eacute;n enkel boek verdeeld is over vele proeflezers, die allemaal aan verschillende pagina's werken.
   De regels helpen ons om allemaal <em>op dezelfde manier</em> te proeflezen, wat het vervolgens gemakkelijker maakt voor de formatteerders
   en de Post-Processor die het werk aan dit e-boek zullen afmaken.
</p>
<p><i>Dit document is niet bedoeld als een algemeen handboek voor het redigeren of zetten van boeken.</i>
</p>
<p>We hebben in dit document alles behandeld waar nieuwe gebruikers vragen over gesteld hebben.
   Er is een apart bestand met <a href="formatting_guidelines.php">Richtlijnen voor het Formatteren</a>.
   Een tweede groep vrijwilligers zal werken aan het formatteren van de tekst.
   Wanneer je in een situatie komt waar je geen aanwijzing vindt in deze richtlijnen, dan wordt
   het waarschijnlijk behandeld in de formatteer-ronden and wordt het daarom hier niet vermeld.
   Wanneer je niet zeker bent, dan kun je het best navraag doen in de <a href="#forums">Project Discussie</a>.
</p>
<p>Als er iets ontbreekt, of als je denkt dat iets op een andere manier behandeld zou moeten worden,
   of als iets vaag is, laat het ons alsjeblieft weten.
<?php if($site_url == "http://www.pgdp.net" || $site_url == "http://www.pgdp.org") { ?>
   Wanneer je in deze richtlijnen een onbekende term tegenkomt, dan is deze (of de engelse vertaling hiervan)
   te vinden in <a href="http://www.pgdp.net/wiki/DP_Jargon">wiki jargon guide</a> (Engels).
<?php } ?>
   Dit document is in ontwikkeling. Help ons bij de ontwikkeling hiervan, door veranderingen die je zou willen voorstellen,
   in <a href="<?php echo $Guideline_discussion_URL; ?>">deze discussie</a> in het Documentation Forum te posten.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Terug naar boven</a></p>


<h3><a name="comments">Toelichting bij een Project</a>
 (<i><a href="#comments">Project Comments</a></i>)</h3>
<p>Wanneer je een project selecteert om te proeflezen, wordt de Project Pagina geladen.
   Op deze pagina vind je een gedeelte dat "Project Comments" (Toelichting bij het Project) heet.
   Hier staat informatie die specifiek bedoeld is voor dat betreffende project (boek).
   <b>Lees dit voor je begint met het proeflezen van pagina's!</b> Als de Project Manager wil dat je iets op een
   andere manier doet, dan in deze richtlijnen staat, dan staat dat hier. Instructies in de Project Comments
   <em>geven de doorslag, boven de regels in deze richtlijnen</em>, dus volg ze alsjeblieft op. Er kunnen in
   de Project Comments ook instructies staan die van toepassing zijn op de formatteer-fase, deze zijn tijdens
   het proeflezen niet van belang. Tot slot is dit trouwens ook de plaats waar de Project Manager wel eens
   interessante informatie geeft over de schrijver of over het project.
</p>
<p><em>Lees alsjeblieft ook de Project Discussie</em>: Hier legt de Project Manager soms project-specifieke
   richtlijnen uit. Ook wordt deze discussie regelmatig gebruikt door vrijwilligers om andere vrijwilligers te wijzen op vaak
   terugkerende problemen in het project, en hoe deze het beste kunnen worden aangepakt. (Zie hieronder.)
</p>
<p>Op de Project Pagina staat een link 'Images, Pages Proofread, &amp; Differences'. Daar kun je zien hoe andere
   vrijwilligers veranderingen hebben aangebracht. <a href="<?php echo $Using_project_details_URL; ?>">Deze forumdiscussie</a>
   bespreekt verschillende manieren om deze informatie te gebruiken.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Terug naar boven</a></p>


<h3><a name="forums">Forum/Project Discussie</a>
 (<i><a href="#forums">Forum/Discuss This Project</a></i>)</h3>
<p>Op de Project Pagina, waar je met het proeflezen van pagina's begint, staat op de regel "Forum", een link met de naam:
   "Discuss this Project" (als de discussie al begonnen is), of: "Start a discussion on this Project" (als de discussie
   nog niet begonnen is.) Klik op deze link en je komt op een discussie in het projectenforum, speciaal voor
   dit project. Hier kun je vragen over dit boek stellen, hier kun je de Project Manager informeren over problemen
   met de tekst enz. Posten in deze Project Discussie is de aanbevolen manier om met de Project Manager en andere
   vrijwilligers die aan het boek werken, te communiceren.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Terug naar boven</a></p>


<h3><a name="prev_pg">Het Herstellen van Vergissingen op Voorgaande Pagina's</a>
 (<i><a href="#prev_pg">Fixing Errors on Previous Pages</a></i>)</h3>
<p>De <a href="#comments">Project Pagina</a> bevat links naar bladzijden van dit project waar je recent aan gewerkt hebt.
   (Als je nog geen pagina's van dit project proefgelezen hebt, zijn er ook geen links.)
</p>
<p>Pagina's onder &oacute;f "DONE" &oacute;f "IN PROGRESS" zijn beschikbaar om te verbeteren of om af te maken.
   Klik op de link van de pagina. Dus, wanneer je ontdekt dat je een vergissing op een pagina gemaakt hebt,
   of dat je iets onjuist gemarkeerd hebt, kun je hier op de link klikken en de pagina openen om
   de vergissing te herstellen.
</p>
<p>Je kunt ook de "Images, Pages Proofread, &amp; Differences" of "Just My Pages" link op de
   <a href="#comments">Project Pagina</a> gebruiken. Deze pagina's hebben een "Edit" link naast de
   pagina's waar je in de huidige ronde aan gewerkt hebt en die nog verbeterd kunnen worden.
</p>
<p>Voor gedetailleerder informatie, zie &oacute;f de <a href="../prooffacehelp.php?i_type=0">Standard
   Proofreading Interface Help</a> &oacute;f de <a href="../prooffacehelp.php?i_type=1">Enhanced Proofreading
   Interface Help</a> (deze documenten bestaan alleen in een Engelse versie), afhankelijk van welke interface je gebruikt.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Terug naar boven</a></p>


<table width="100%" border="0" cellspacing="0" cellpadding="6" summary="Character-level proofreading">
  <tbody>
    <tr>
      <td bgcolor="silver"><h2 style="margin-bottom: 0; margin-top: 0;">Proeflezen op het Letter-Niveau:</h2></td>
    </tr>
  </tbody>
</table>


<h3><a name="double_q">Dubbele Aanhalingstekens</a>
 (<i><a href="#double_q">Double Quotes</a></i>)</h3>
<p>Proeflees &ldquo;dubbele aanhalingstekens&rdquo; als gewone ASCII <kbd>"</kbd> dubbele aanhalingstekens.
   Verander geen dubbele aanhalingstekens in enkele aanhalingstekens. Laat de aanhalingstekens staan zoals
   de auteur ze geschreven heeft.
   Zie <a href="#chap_head">Hoofdstuktitels</a> voor het geval dat een dubbel aanhalingsteken aan het begin van een hoofdstuk mist.
</p>
<p>Aanhalingstekens in andere talen: gebruik de aanhalingstekens die voor die taal gewoon zijn,
   Voor aanhalingstekens anders dan <kbd>"</kbd>, gebruiken we dezelfde tekens zoals ze in de scan voorkomen,
   als deze beschikbaar zijn. Het Franse equivalent, de guillemets&nbsp; <kbd>&laquo;zoals dit&raquo;</kbd>,
   kunnen gekozen worden in de uitklapmenu's in de proofreading interface, aangezien ze deel uitmaken
   van de Latin-1 tekenset. Vergeet niet spaties te verwijderen tussen de aanhalingstekens en de geciteerde tekst.
   Als er toch spaties gewenst zijn, zullen die tijdens het post-processen worden toegevoegd.
   Hetzelfde geldt voor talen waar omgekeerde guillemets worden gebruikt&nbsp; <kbd>&raquo;zoals dit&laquo;</kbd>.
</p>
<p>De lage aanhalingstekens die in sommige teksten voorkomen (in het Nederlands en Duits, en sommige andere talen)&nbsp;
   <kbd>&bdquo;zoals dit&ldquo;</kbd>
   zijn ook beschikbaar in de uitklapmenu's. Om de zaak eenvoudig te houden, moet je altijd&nbsp;
   <kbd>&bdquo;</kbd>&nbsp; en&nbsp; <kbd>&ldquo;</kbd>&nbsp; gebruiken als de aanhalingstekens in het
   origineel duidelijk lage en hoge aanhalingstekens zijn, ongeacht welke aanhalingstekens in de
   originele tekst gebruikt worden. De aanhalingstekens zullen zo nodig tijden het post-processen
   veranderd worden in de aanhalingstekens die in de tekst gebruikt zijn.
</p>
<p>Het kan zijn dat de Project Manager in de <a href="#comments">Project Comments</a> voor een bepaald
   boek instructies geeft om aanhalingstekens uit een niet-Engelse taal anders te behandelen.
   Let er op dat je deze instrucites niet toepast bij andere projecten.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Terug naar boven</a></p>


<h3><a name="single_q">Enkele Aanhalingstekens</a>
 (<i><a href="#single_q">Single Quotes</a></i>)</h3>
<p>Proeflees deze als gewone ASCII <kbd>'</kbd> enkele aanhalingstekens (apostrof).
   Verander enkele aanhalingstekens niet in dubbele aanhalingstekens.
   Laat de aanhalingstekens staan zoals de auteur ze geschreven heeft.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Terug naar boven</a></p>


<h3><a name="quote_ea">Aanhalingstekens op Iedere Regel</a>
 (<i><a href="#quote_ea">Quote Marks on Each Line</a></i>)</h3>
<p>Als in een citaat iedere tekstregel met een aanhalingsteken begint, verwijder deze dan,
   <b>behalve</b> het aanhalingsteken bij het begin van de eerste regel van het citaat.
   Als een citaat zoals hier meerdere alinea's omvat, dan laat je het aanhalingsteken
   voor de eerste regel van elke alinea staan.
</p>
<p>Echter, in po&euml;zie behouden we de extra aanhalingstekens waar ze in de scan ook
   voorkomen, omdat de regelafbrekingen niet veranderd zullen worden.
</p>
<p>Vaak staat er pas een aanhalingsteken sluiten aan het eind van het volledige citaat;
   dit kan op een andere pagina zijn dan de pagina die je onder handen hebt.
   Laat dit zo: voeg geen aanhalingstekens toe die niet op de pagina aanwezig zijn.
</p>
<p>Er zijn enkele taal-specifieke uitzonderingen. In het Frans bijvoorbeeld, wordt
   bij een gesprek binnen citaten een combinatie van verschillende interpunctie gebruikt
   om verschillende sprekers aan te geven. Wanneer je niet bekend bent met een bepaalde
   taal, lees dan de <a href="#comments">Project Comments</a> of laat een bericht achter
   voor de Project Manager in de Project Discussion voor opheldering.
</p>
<!-- END RR -->
<table width="100%" border="1" cellpadding="4" cellspacing="0" align="center" summary="Example of quote marks on each line">
  <tbody>
    <tr>
      <th valign="top" align="left" bgcolor="cornsilk">Origineel:</th>
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
      <th valign="top" align="left" bgcolor="cornsilk">Correct proefgelezen tekst:</th>
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
<p class="backtotop"><a href="#top">Terug naar boven</a></p>


<h3><a name="period_s">Punten aan het Eind van een Zin</a>
 (<i><a href="#period_s">End-of-sentence Periods</a></i>)</h3>
<p>Proeflees punten aan het eind van een zin met een enkele spatie erachter.
</p>
<p>Als er meerdere spaties achter een punt staan, hoef je die niet weg te halen,
   dat gebeurt automatisch tijdens het post-processen.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Terug naar boven</a></p>


<h3><a name="punctuat">Interpunctie en Spaties</a>
 (<i><a href="#punctuat">Punctuation Spacing</a></i>)</h3>
<p>Het lijkt soms of er spaties voor interpunctie staan, omdat zetters in de 18e en 19e eeuw
   gedeeltelijke spaties voor interpunctie zoals een puntkomma of een dubbele punt gebruikten.
</p>
<p>Over het algemeen hoort er na interpunctie wel een spatie en er v&oacute;&oacute;r geen spatie te staan.
   Wanneer er in de tekst na interpunctie geen spatie staat, voeg deze dan toe; wanneer er een
   spatie voor interpunctie staat, verwijderd die dan. Doe dit zelfs bij talen
   als het Frans, waar normaal wel spaties voor interpunctie staan.
   Echter, interpunctie die normaal gesproken per twee voorkomen, zoals "aanhalingstekens", (haakjes),
   [blokhaken] en {accolades}, hebben een spatie voor het openingsteken; deze moeten blijven staan.
</p>
<!-- END RR -->

<table width="100%" align="center" border="1" cellpadding="4" cellspacing="0" summary="Punctuation example">
  <tbody>
    <tr><th align="left" bgcolor="cornsilk">Origineel:</th></tr>
    <tr>
      <td valign="top">and so it goes&nbsp;; ever and ever.</td>
    </tr>
    <tr><th align="left" bgcolor="cornsilk">Correct proefgelezen tekst:</th></tr>
    <tr>
      <td valign="top"><kbd>and so it goes; ever and ever.</kbd></td>
    </tr>
  </tbody>
</table>
<p class="backtotop"><a href="#top">Terug naar boven</a></p>


<h3><a name="extra_sp">Extra Spaties of Tabs Tussen Woorden</a>
 (<i><a href="#extra_sp">Extra Spaces or Tabs Between Words</a></i>)</h3>
<p>Extra spaties tussen woorden komen nogal veel voor in de output van de OCR.
   Je hoeft ze niet te verwijderen&mdash;dit gebeurt automatisch tijden het post-processen.
   Maar, extra spaties rondom interpunctie, em-dashes, aanhalingstekens enz. moeten <b>wel</b>
   verwijderd worden als ze tussen het symbool en het woord staan. 
</p>
<p>Bijvoorbeeld: in <kbd>A horse&nbsp;;&nbsp;&nbsp;my kingdom for a horse.</kbd> moet de spatie
   tussen "horse" en de puntkomma verwijderd worden. Maar de twee spaties na de puntkomma mogen
   blijven staan, je hoeft er niet &eacute;&eacute;n weg te halen.
</p>
<p>Mocht je daarnaast in de tekst tabs tegenkomen, dan moeten deze verwijderd worden.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Terug naar boven</a></p>


<h3><a name="trail_s">Spaties aan het Eind van een Regel</a>
 (<i><a href="#trail_s">Trailing Space at End-of-line</a></i>)</h3>
<p>Doe geen moeite om spaties toe te voegen aan het eind van een tekstregel; deze spaties worden
   automatisch verwijderd uit de tekst wanneer je de pagina bewaard. Tijdens het post-processen,
   wordt elke regelafbreking in de tekst omgezet in een spatie.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Terug naar boven</a></p>


<h3><a name="em_dashes">Liggend streepje: Koppelteken, Gedachtestreepje, Minteken</a>
 (<i><a href="#em_dashes">Dashes, Hyphens, and Minus Signs</a></i>)</h3>
<p>Over het algemeen zul je vier van dergelijke tekens in boeken tegenkomen:
</p>
  <ol compact>
    <li><i>Liggend streepje/Koppelteken (Hyphen)</i>. Dit wordt gebruikt om woorden te <b>koppelen</b>
        tot &eacute;&eacute;n woord, of om voor- of achtervoegsels aan een woord te koppelen.
    <br>Laat dit teken staan als een enkel streepje; verwijder aan weerskanten een eventueel aanwezige spatie.
        Er is een (zeker in het Nederlands) veel voorkomende uitzondering op deze regel,
        die te zien is in het tweede voorbeeld hieronder.
    </li>
    <li><i>Liggend streepje/Minteken (En-dash)</i>. Dit streepje is net iets langer, en wordt gebruikt
        voor een aaneensluitende getallen<b>reeks</b>, of als een rekenkundig <b>min</b>teken.
    <br>Laat ook dit teken staan als een enkel streepje. Of er spaties voor of achter staan, wordt
        bepaald door hoe het in het boek is gedaan. Er zijn meestal geen spaties in een getallenbereik,
        en meestal wel spaties rond rekenkundige mintekens, soms aan beide zijden, soms alleen ervoor.
    </li>
    <li><i>Liggend streepje/Gedachtestreepje/Kastlijn (Em-dashes)</i>.
        Dit dient als <b>scheidingsteken</b> tussen woorden&mdash;soms om iets te benadrukken,
        zoals hier&mdash;of als een spreker zich verslikt in een woord&mdash;&mdash;!
    <br>Proeflees deze als twee streepjes wanneer het liggende streepje zo lang is als 2-3 letters
        (een <i>em-dash</i>), en als vier streepjes wanneer het liggende streepje zo lang is als
        4-5 letters (een <i>lange em-dash</i>). Laat geen spatie staan voor of na de streepjes,
        zelfs niet als er in de oorspronkelijke tekst een spatie te zien is.
    </li>
    <li><i>Met Opzet Weggelaten of Gecensureerde Woorden of Namen</i>.
    <br>Wanneer deze in het origineel door een streepje worden weergegeven, proeflees deze als twee
        streepjes of vier streepjes zoals beschreven bij Liggend streepje/Gedachtestreepje/Kastlijn (Em-dashes).
        Als de streepjes een woord voorstellen, dan laten we er
        spaties omheen staan alsof het echt een woord is. Als het een deel van een woord betreft,
        dan geen spaties&mdash;koppel het aan de rest van het woord.
        </li>
  </ol>
<p>Zie ook de richtlijnen voor Koppelteken en Streepjes <a href="#eol_hyphen">aan het Eind van een Regel</a> en
   <a href="#eop_hyphen">aan het Eind van een Bladzijde</a>.
</p>
<!-- END RR -->

<p><b>Voorbeelden</b>&mdash;Liggende streepjes:
</p>

<table width="100%" align="center" border="1" cellpadding="4" cellspacing="0" summary="Hyphens and Dashes examples">
  <tbody>
    <tr>
      <th valign="top" bgcolor="cornsilk">Origineel (boek):</th>
      <th valign="top" bgcolor="cornsilk">Correct geformatteerde tekst:</th>
      <th valign="top" bgcolor="cornsilk">Type</th>
    </tr>
    <tr>
      <td valign="top">semi-detached</td>
      <td valign="top"><kbd>semi-detached</kbd></td>
      <td>Koppelteken</td>
    </tr>
    <tr>
      <td valign="top">three- and four-part harmony</td>
      <td valign="top"><kbd>three- and four-part harmony</kbd></td>
      <td>Koppeltekens</td>
    </tr>
    <tr>
      <td valign="top">discoveries which the Crus-<br>
        aders made and brought home with</td>
      <td valign="top"><kbd>discoveries which the Crusaders<br>
        made and brought home with</kbd></td>
      <td>Koppelteken</td>
    </tr>
    <tr>
      <td valign="top">factors which mold char-<br>
        acter&mdash;environment, training and heritage,</td>
      <td valign="top"><kbd>factors which mold character--environment,<br>
        training and heritage,</kbd></td>
      <td>Koppelteken &amp; Em-dash</td>
    </tr>
    <tr>
      <td valign="top">See pages 21&ndash;25</td>
      <td valign="top"><kbd>See pages 21-25</kbd></td>
      <td>En-dash</td>
    </tr>
    <tr>
      <td valign="top">It was &ndash;14&deg;C outside.</td>
      <td valign="top"><kbd>It was -14&deg;C outside.</kbd></td>
      <td>En-dash</td>
    </tr>
    <tr>
      <td valign="top">X &ndash; Y = Z</td>
      <td valign="top"><kbd>X - Y = Z</kbd></td>
      <td>En-dash</td>
    </tr>
    <tr>
      <td valign="top">2&ndash;1/2</td>
      <td valign="top"><kbd>2-1/2</kbd></td>
      <td>En-dash</td>
    </tr>
    <tr>
      <td valign="top">&mdash;A plague on both<br> your houses!&mdash;I am dead.</td>
      <td valign="top"><kbd>--A plague on both<br> your houses!--I am dead.</kbd></td>
      <td>Em-dashes</td>
    </tr>
    <tr>
      <td valign="top">sensations&mdash;sweet, bitter, salt, and sour<br>
        &mdash;if even all of these are simple tastes. What</td>
      <td valign="top"><kbd>sensations--sweet, bitter, salt, and sour--if<br>
        even all of these are simple tastes. What</kbd></td>
      <td>Em-dashes</td>
    </tr>
    <tr>
      <td valign="top">senses&mdash;touch, smell, hearing, and sight&mdash;<br>
        with which we are here concerned,</td>
      <td valign="top"><kbd>senses--touch, smell, hearing, and sight--with<br>
        which we are here concerned,</kbd></td>
      <td>Em-dashes</td>
    </tr>
    <tr>
      <td valign="top">It is the east, and Juliet is the sun&mdash;!</td>
      <td valign="top"><kbd>It is the east, and Juliet is the sun--!</kbd></td>
      <td>Em-dash</td>
    </tr>
    <tr>
      <td valign="top"><img src="../dashes.png" width="300" height="28" alt=""></td>
      <td valign="top"><kbd>how a--a--cannon-ball goes----"</kbd></td>
      <td>Em-dashes, Koppelteken,<br> &amp; Lange Em-dash</td>
    </tr>
    <tr>
      <td valign="top">"Three hundred&mdash;&mdash;" "years," she was going to<br>
        say, but the left-hand cat interrupted her.</td>
      <td valign="top"><kbd>"Three hundred----" "years," she was going to<br>
        say, but the left-hand cat interrupted her.</kbd></td>
      <td>Lange Em-dash</td>
    </tr>
    <tr>
      <td valign="top">As the witness Mr. &mdash;&mdash; testified,</td>
      <td valign="top"><kbd>As the witness Mr. ---- testified,</kbd></td>
      <td>Lange Em-dash</td>
    </tr>
    <tr>
      <td valign="top">As the witness Mr. S&mdash;&mdash; testified,</td>
      <td valign="top"><kbd>As the witness Mr. S---- testified,</kbd></td>
      <td>Lange Em-dash</td>
    </tr>
    <tr>
      <td valign="top">the famous detective of &mdash;&mdash;B Baker St.</td>
      <td valign="top"><kbd>the famous detective of ----B Baker St.</kbd></td>
      <td>Lange Em-dash</td>
    </tr>
    <tr>
      <td valign="top">&ldquo;You &mdash;&mdash; Yankee&rdquo;, she yelled.</td>
      <td valign="top"><kbd>"You ---- Yankee", she yelled.</kbd></td>
      <td>Lange Em-dash</td>
    </tr>
    <tr>
      <td valign="top">&ldquo;I am not a d&mdash;d Yankee&rdquo;, he replied.</td>
      <td valign="top"><kbd>"I am not a d--d Yankee", he replied.</kbd></td>
      <td>Em-dash</td>
    </tr>
  </tbody>
</table>
<p class="backtotop"><a href="#top">Terug naar boven</a></p>


<h3><a name="eol_hyphen">Koppelteken en Streepjes aan het Eind van een Regel</a>
 (<i><a href="#eol_hyphen">End-of-line Hyphenation and Dashes</a></i>)</h3>
<p>Als een regel eindigt met een afbreekstreepje, voeg dan de twee helften van het afgebroken woord
   samen tot &eacute;&eacute;n woord. Verwijder het streepje wanneer je het woord samenvoegt,
   tenzij het een koppelteken betreft, zoals bijvoorbeeld secretaris-generaal. Zie het hoofdstuk
   over <a href="#em_dashes">liggende streepjes</a> voor de verschillende voorbeelden.
   Laat het samengevoegde woord staan op de regel waar de eerste helft stond, en breek de regel na
   het woord af om de bestaande regel-indeling te behouden&mdash;dit maakt het gemakkelijker voor
   de na jou komende vrijwilligers. Als het woord gevolgd wordt door een leesteken, haal dan
   ook het leesteken naar de bovenste regel. 
</p>
<p>Een aantal woorden (b.v. de Engelse woorden to-day en to-morrow), die tegenwoordig geen koppelteken
   bevatten, hadden dat vaak wel in de oude boeken die wij onder handen hebben. Dergelijke woorden
   behouden hun koppelteken zoals de auteur het heeft geschreven. Als je niet zeker weet of de auteur
   dit woord wel of niet met koppelteken zou hebben geschreven, voeg dan het woord samen tot &eacute;&eacute;n woord,
   laat het koppelteken staan, en zet een <kbd>*</kbd> achter het koppelteken. Bijvoorbeeld: <kbd>to-*day</kbd>.
   Het sterretje zorgt ervoor dat er naar wordt gekeken door de Post-Processor, die alle pagina's kan
   bekijken, en kan bepalen hoe de auteur dit woord schreef of geschreven zou hebben.
</p>
<p>Vergelijkbaar, wanneer een em-dash voorkomt aan het begin of einde van een tekstregel, dan moet
   samengevoegd worden met de andere regel zodat er rond de em-dash geen spaties of regelafbrekingen
   voorkomen. Wanneer echter de auteur een em-dash heeft gebruikt aan het begin of eind van een alinea
   of een regel po&euml;zie, dan moet je het laten zoals het is en het niet samenvoegen met de andere regel.
   Zie <a href="#em_dashes">Liggend streepje: koppelteken, gedachtestreepje, minteken</a> voor voorbeelden.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Terug naar boven</a></p>


<h3><a name="eop_hyphen">Koppelteken en Streepjes aan het Eind van een Bladzijde</a>
 (<i><a href="#eop_hyphen">End-of-page Hyphenation and Dashes</a></i>)</h3>
<p>Laat bij een woordafbreking aan het eind van een bladzijde het streepje of koppelteken aan het eind
   van de laatste regel staan, en markeer het met een <kbd>*</kbd> na het streepje of koppelteken.
   Bijvoorbeeld:
</p>
<table width="100%" align="center" border="1" cellpadding="4" cellspacing="0" summary="End-of-page Hyphenation example">
  <tbody>
    <tr><th align="left" bgcolor="cornsilk">Origeel:</th></tr>
    <tr>
      <td valign="top">something Pat had already become accus-</td>
    </tr>
    <tr><th align="left" bgcolor="cornsilk">Correct proefgelezen tekst:</th></tr>
    <tr>
      <td valign="top"><kbd>something Pat had already become accus-*</kbd></td>
    </tr>
  </tbody>
</table>
<p>Als een bladzijde begint met het tweede deel van een woord dat op de vorige bladzijde is afgebroken,
   of met een em-dash, zet dan een <kbd>*</kbd> voor het gedeeltelijke woord of de em-dash.
   Dus om het bovenstaande voorbeeld te vervolgen:
</p>
<table width="100%" align="center" border="1" cellpadding="4" cellspacing="0" summary="Start-of-page Hyphenation example">
  <tbody>
    <tr><th align="left" bgcolor="cornsilk">Origineel:</th></tr>
    <tr>
      <td valign="top">tomed to from having to do his own family</td>
    </tr>
    <tr><th align="left" bgcolor="cornsilk">Correct proefgelezen tekstt:</th></tr>
    <tr>
      <td valign="top"><kbd>*tomed to from having to do his own family</kbd></td>
    </tr>
  </tbody>
</table>
<p>Deze markeringen geven aan de Post-Processor een teken dat het woord moet worden samengevoegd,
   als de pagina's tot een e-boek worden samengevoegd. Voeg alsjeblieft niet zelf de fragmenten
   van de verschillende pagina's samen.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Terug naar boven</a></p>


<h3><a name="period_p">Beletselteken ofwel Ellips &quot;&hellip;&quot;</a>
 (<i><a href="#period_p">Period Pause &quot;&hellip;&quot; (Ellipsis)</a></i>)</h3>
<p>De richtlijnen zijn verschillend voor het Engels en voor andere talen dan het Engels (Languages Other Than English (LOTE)).
</p>
<p><b>ENGELS</b>: Een ellips moet drie puntjes hebben. Wat de spaties betreft,
   behandel in het midden van een zin de drie puntjes als &eacute;&eacute;n woord (b.v., een spatie
   v&oacute;&oacute;r de drie puntjes en &eacute;&eacute;n spatie erna). Behandel bij het einde van
   een zin de ellips als andere interpunctie aan het einde van een zin, zonder een spatie ervoor.
</p>
<p>Let er wel op dat er ook een afsluitend interpunctie-teken aan het eind van een zin voorkomt,
   zodat in het geval van een punt er totaal vier puntjes staan.
   Verwijder evt. extra punten, of voeg zo nodig punten toe, 
   om het totaal op drie (of waar nodig vier) punten te brengen.
   Een goede aanwijzing dat je aan het eind van een zin bent is het gebruik van een hoofdletter
   aan het begin van het volgende woord, of de aanwezigheid van een afsluitend interpunctie-teken
   (b.v., een vraagteken of een uitroepteken).
</p>
<p><b>LOTE</b>: (Andere talen dan het Engels)
   De algemene regel is: <i>Doe het zoals op de pagina gedrukt staat</i>. Voeg spaties in,
   als er spaties voor of tussen de punten staan. Gebruik evenveel punten als in het origineel.
   Soms is het origineel niet helemaal duidelijk. Voeg dan <kbd>[**unclear]</kbd> in om de aandacht
   van de Post-Processor er op te vestigen.
   (Noot: Post-Processors dienen deze spaties te vervangen door <i>non-breaking</i> spaties.)
</p>
<!-- END RR -->
<p>Voorbelden uit het Engels:
</p>
<table width="100%" align="center" border="1" cellpadding="4" cellspacing="0" summary="Ellipses examples">
  <tbody>
    <tr>
      <th valign="top" bgcolor="cornsilk">Origineel:</th>
      <th valign="top" bgcolor="cornsilk">Correct proefgelezen tekst:</th>
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
<p class="backtotop"><a href="#top">Terug naar boven</a></p>


<h3><a name="contract">Samentrekkingen</a>
 (<i><a href="#contract">Contractions</a></i>)</h3>
<p>Verwijder bij het Engels extra spaties in samentrekkingen. Bijvoorbeeld: <kbd>would&nbsp;n't</kbd>
   dient te worden proefgelezen als <kbd>wouldn't</kbd> en <kbd>'t&nbsp;is</kbd> als <kbd>'tis</kbd>.
</p>
<p>Zetters behielden in de 19<sup>e</sup> eeuw vaak een spatie om aan te geven dat 'would' en 'not'
   oorspronkelijk afzonderlijke woorden waren. Het kan ook een bijverschijnsel van het OCR-proces zijn.
   In beide gevallen dient de extra spatie verwijderd te worden.
</p>
<p>Sommige Project Managers zetten een opmerking in de <a href="#comments">Project Comments</a>
   dat extra spaties in samentrekkingen niet verwijderd moeten worden, vooral wanneer het boeken
   betreft met straattaal, dialect of po&euml;zie.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Terug naar boven</a></p>


<h3><a name="fract_s">Breuken</a>
   (<i><a href="#fract_s">Fractions</a></i>)</h3>
<p>Proeflees breuken als volgt: <kbd>&frac14;</kbd> wordt <kbd>1/4</kbd> en
   en <kbd>2&frac12;</kbd> wordt <kbd>2-1/2</kbd>.
   Het streepje voorkomt dat het hele getal en de breuk tijdens het post-processen bij het
   <span style="border-bottom: 1px dotted green;" title="rewrap: het opnieuw aanbrengen/verplaatsen van regelafbrekingen">rewrappen</span>
   van elkaar worden gescheiden.
   Gebruik niet de gebruikelijke breuk-symbolen, tenzij er uitdrukkelijk in de
   <a href="#comments">Project Comments</a> om wordt gevraagd.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Terug naar boven</a></p>


<h3><a name="a_chars">Letters met Accenten/Niet-ASCII Letters</a>
 (<i><a href="#a_chars">Accented/Non-ASCII Characters</a></i>)</h3>
<p>Proeflees deze a.u.b. door de juiste UTF-8 tekens te gebruiken. Voor tekens die niet in
   Unicode zitten, zie de Project Manager instructies in de <a href="#comments">Project Comments</a>.
   Als je ze niet op je toetsenbord hebt, zie dan <a href="#insert_char">Toevoegen van Speciale Letters</a>
   voor meer informatie over hoe deze tekens tijdens het proeflezen in te voegen.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Terug naar boven</a></p>


<h3><a name="d_chars">Letters met Diakritische Tekens</a>
 (<i><a href="#d_chars">Characters with Diacritical Marks</a></i>)</h3>
<p>In sommige projecten vind je lettertekens met speciale tekens boven of onder de letters van A tot Z.
   Deze heten <i>diakritische tekens</i> en geven een bijzondere uitspraak van de letter aan.
</p>
<p>Als een dergelijk karakter in Unicode niet bestaat, dan moet het worden
   ingegeven door <i>gecombineerde diakritische tekens</i>: dit zijn symbolen in Unicode die niet
   op zichzelf voorkomen, maar die boven (of onder) de letter waar ze achter gezet zijn, komen.
   Ze kunnen worden ingegeven door eerst de basisletter in te geven, en daarna het combinatieteken,
   waarbij je de applets en programma's gebruikt die genoemd worden in
   <a href="#insert_char">Toevoegen van Speciale Letters</a>.
</p>
<p>Op sommige systemen zie je de diakritische tekens niet op de plaats waar ze horen,
   maar bijvoorbeeld rechts daarvan. Je moet ze dan toch gebruiken, want mensen met andere
   systemen zullen ze wel goed te zien krijgen. Als je om de een of andere reden de gecombineerde
   tekens niet goed kunt zien, markeer zo'n letter dan met een [**noot]. Overigens bestaan
   er ook <i>Spacing modifier letters</i>; deze mogen niet gebruikt worden.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Terug naar boven</a></p>


<h3><a name="f_chars">Niet-Latijnse Lettertekens</a>
   (<i><a href="#f_chars">Non-Latin Characters</a></i>)</h3>
<p>Sommige teksten bevatten tekst die in niet-Latijnse lettertekens is gedrukt, dus andere letters dan
   de Latijnse A...Z. Het kan dan gaan om Griekse, Cyrillische (dat wordt gebruikt in Russisch,
   Slavisch en andere talen), Hebreeuwse of Arabische letters.
</p>
<p>Deze lettertekens moeten, net als de Latijnse letters, in de tekst worden gezet.
   (<b>ZONDER omzetting!</b>)
</p>
<p>Als een tekst helemaal in een niet-Latijns schrift geschreven is, dan is het aan te bevelen
   om een toetsenbord driver te installeren, die de taal ondersteunt.
   Raadpleeg de handleiding van je besturingssysteem voor instructies.
</p>
<p>Als het niet-Latijnse schrift alleen incidenteel voorkomt, dan kun je ook een apart programma
   gebruiken om de letters in te voeren. Zie <a href="#insert_char">Toevoegen van Speciale Letters</a>
   voor enige van deze programma's.
</p>
<p>Als je niet helemaal zeker bent van een teken of accent, markeer het dan met een [**noot]
   om te zorgen dat de volgende proeflezer of de Post-Processor het opmerkt.
</p>
<p>Zet om schrift dat niet zo gemakkelijk kan worden ingegeven, zoals Arabisch, passende markering:
   <kbd>[Arabic:&nbsp;**]</kbd>. Zorg voor de <kbd>**</kbd> zodat
   de Post-Processor het later kan oplossen.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Terug naar boven</a></p>


<h3><a name="supers">Superscript</a>
 (<i><a href="#supers">Superscripts</a></i>)</h3>
<p>Oudere boeken gebruikten vaak samentrekkingen als afkortingen, en drukten deze dan als superscript.
   Proeflees deze dan door een dakje (<kbd>^</kbd>) in te voegen gevolgd door de 'verhoogde' tekst.
   Wanneer meerdere letters in superscript staan, voeg dan ook accolades <kbd>{</kbd> en <kbd>}</kbd>
   toe om de tekst in superscript. Bijvoorbeeld:
</p>
<table width="100%" align="center" border="1" cellpadding="4" cellspacing="0" summary="Superscripts example">
  <tbody>
    <tr><th align="left" bgcolor="cornsilk">Origineel:</th></tr>
    <tr>
      <td valign="top">Gen<sup>rl</sup> Washington defeated L<sup>d</sup> Cornwall's army.</td>
    </tr>
    <tr><th align="left" bgcolor="cornsilk">Correct proefgelezen tekst:</th></tr>
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


<h3><a name="subscr">Subscript</a>
 (<i><a href="#subscr">Subscripts</a></i>)</h3>
<p>In wetenschappelijke werken wordt vaak subscript gebruikt, al komt het in andere boeken niet vaak voor.
   Proeflees tekst in subscript door een laag streepje <kbd>_</kbd> neer te zetten, en
   zet accolades <kbd>{</kbd> en <kbd>}</kbd> voor en na de tekst. Bijvoorbeeld:
</p>
<table width="100%" align="center" border="1" cellpadding="4" cellspacing="0" summary="Subscripts example">
  <tbody>
    <tr><th align="left" bgcolor="cornsilk">Origineel:</th></tr>
    <tr>
      <td valign="top">H<sub>2</sub>O.</td>
    </tr>
    <tr><th align="left" bgcolor="cornsilk">Correct proefgelezen tekst:</th></tr>
    <tr>
      <td valign="top"><kbd>H_{2}O.</kbd></td>
    </tr>
  </tbody>
</table>
<!-- END RR -->
<p class="backtotop"><a href="#top">Terug naar boven</a></p>


<h3><a name="drop_caps">Grote, Versierde Hoofdletter aan het Begin van een Regel</a>
 (<i><a href="#drop_caps">Large, Ornate Opening Capital Letter (Drop Cap)</a></i>)</h3>
<p>Proeflees een grote, versierde eerste letter van een hoofdstuk, paragraaf of alinea als een gewone letter.
   Zie ook de paragraaf <a href="#chap_head">Hoofdstuktitels</a> van de Richtlijnen voor het Proeflezen.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Terug naar boven</a></p>


<h3><a name="small_caps">Woorden in <span style="font-variant: small-caps">Kleine Hoofdlettertjes (Klein Kapitaal)</span></a>
 (<i><a href="#small_caps">Words in Small Capitals</a></i>)</h3>
<p>Proeflees alsjeblieft alleen de letters in <span style="font-variant: small-caps">Klein Kapitaal</span>
   (hoofdletters die kleiner zijn dan de standaard hoofdletters).
   Maak je niet druk over verandering van hoofdletter of kleine letter.
   Wanneer de tekst vanuit de OCR in HOOFDLETTERS, Klein Kapitaal of kleine letters staat,
   dan laten we het in HOOFDLETTERS, Klein Kapitaal of kleine letters staan.
   Een enkele keer zie je <kbd>&lt;sc&gt;</kbd> v&oacute;&oacute;r het <span style="font-variant: small-caps">klein kapitaal</span>
   en <kbd>&lt;/sc&gt;</kbd> na het <span style="font-variant: small-caps">klein kapitaal</span> staan:
   zie in dat geval <a href="#formatting">Formattering</a>.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Terug naar boven</a></p>


<table width="100%" border="0" cellspacing="0" cellpadding="6" summary="Paragraph-level proofreading">
  <tbody>
    <tr>
      <td bgcolor="silver"><h2 style="margin-bottom: 0; margin-top: 0;">Proeflezen op het Alinea-Niveau:</h2></td>
    </tr>
  </tbody>
</table>


<h3><a name="line_br">Regelafbrekingen</a>
 (<i><a href="#line_br">Line Breaks</a></i>)</h3>
<p><b>Laat alle regelafbrekingen staan zoals ze zijn.</b> De volgende vrijwilligers, later in het proces,
   kunnen dan de regels in de tekst gemakkelijk vergelijken met de regels in het origineel.
   Let hier speciaal op als je <a href="#eol_hyphen">woorden met een afbreekstreepje</a> samenvoegt,
   of als je woorden rondom <a href="#em_dashes">em-dashes</a> verplaatst. Als de vorige vrijwilliger
   regelafbrekingen verwijderd heeft, herstel ze dan alsjeblieft, zodat ze weer zo zijn als in het origineel.
</p>
<!-- END RR -->
<!-- We should have an example right here for this. -->
<p class="backtotop"><a href="#top">Terug naar boven</a></p>


<h3><a name="chap_head">Hoofdstuktitels</a>
 (<i><a href="#chap_head">Chapter Headings</a></i>)</h3>
<p>Proeflees hoofdstuktitels zoals deze in het origineel voorkomen.
</p>
<p>Een hoofdstuktitel begint vaak wat lager op de pagina dan de <a href="#page_hf">koptekst</a>.
   Er staat geen paginanummer op dezelfde regel. Hoofdstuktitels worden vaak helemaal
   in hoofdletters gedrukt. Dan handhaaf je de hoofdletters.
</p>
<p>Let op: de aanhalingstekens aan het begin van de eerste alinea ontbreken nog wel eens,
   &oacute;f doordat de uitgever ze er niet bij zette, &oacute;f doordat de OCR ze miste omdat
   de eerste letter in het origineel een extra grote hoofdletter is. Als de schrijver de alinea
   met dialoog begon, voeg dan de aanhalingstekens toe.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Terug naar boven</a></p>


<h3><a name="para_space">Ruimte Tussen Alinea's/Inspringingen</a>
 (<i><a href="#para_space">Paragraph Spacing/Indenting</a></i>)</h3>
<p>Zet een lege regel voor het begin van een alinea, zelfs als de alinea bovenaan een bladzijde begint.
   Aan het begin van een alinea hoef je niet in te springen, maar als al ingesprongen is,
   hoef je die spaties niet te verwijderen&mdash;dat gebeurt automatisch tijdens het post-processen.
</p>
<p>Zie het voorbeeld bij de <a href="#para_side">Sidenotes</a>.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Terug naar boven</a></p>


<h3><a name="page_hf">Koptekst en Voettekst</a>
 (<i><a href="#page_hf">Page Headers/Page Footers</a></i>)</h3>
<p>Verwijder alle koptekst en voettekst, maar <em>niet</em> de <a href="#footnotes">voetnoten</a>.
</p>
<p>De koptekst staat gewoonlijk bovenaan het origineel. Er staat vaak een paginanummer op dezelfde regel.
   Koptekst kan het hele boek door hetzelfde zijn. Dan is het vaak de titel van het boek en de naam van de schrijver.
   Of hij is per hoofdstuk hetzelfde, vaak het nummer van het hoofdstuk. Of de koptekst is op elke pagina anders,
   dan wordt beschreven wat er op de pagina gebeurt. Verwijder alle koptekst, inclusief het paginanummer.
   Extra lege regels, die niet in het origineel voorkomen, moeten verwijderd worden, behalve waar we
   ze tijdens het proeflezen bewust toevoegen. Lege regels aan het eind van de pagina zijn geen
   probleem&mdash;die worden automatisch verwijderd wanneer je de pagina bewaard.
</p>
<p>Voettekst staat onder aan de pagina en kan een pagina-nummer of andere uitzonderlijke tekens bevatten
   die niet horen bij wat de auteur geschreven heeft.
</p>
<!-- END RR -->

<p>De <a href="#chap_head">titel van een hoofdstuk</a> (chapter header) begint in het algemeen lager op de
   pagina en heeft geen paginanummer op dezelfde regel. Zie het volgende deel voor een voorbeeld.
</p>
<table width="100%" align="center" border="1" cellpadding="4" cellspacing="0" summary="Page Headers and Footers">
  <tbody>
    <tr><th align="left" bgcolor="cornsilk">Origineel:</th></tr>
    <tr align="left">
      <td width="100%" valign="top"><img src="../foot.png" alt="" width="500" height="860"><br>
      </td>
    </tr>
    <tr><th align="left" bgcolor="cornsilk">Correct proefgelezen tekst:</th></tr>
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
<p class="backtotop"><a href="#top">Terug naar boven</a></p>


<h3><a name="illust">Illustraties</a>
 (<i><a href="#illust">Illustrations</a></i>)</h3>
<p>Negeer illustraties, maar proeflees tekst bij een illustratie zoals hij gedrukt is,
   handhaaf regelafbrekingen. Als een bijschrift midden in een alinea terecht is gekomen,
   voeg dan voor en na het bijschrift lege regels in om het apart te houden van de rest
   van de tekst. Tekst wat een (onderdeel van een) bijschrift kan zijn, moet aanwezig zijn,
   zoals bijvoorbeeld "Zie Pagina 66" of een titel binnen de afmeting van de illustratie.
</p>
<p>De meeste pagina's met een illustratie zonder bijschrift zullen al gemarkeerd zijn
   met <kbd>[Blank Page]</kbd>. Laat deze markering voor wat hij is.
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
      <th align="left" bgcolor="cornsilk">Correct proefgelezen tekst:</th>
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
      <th align="left" bgcolor="cornsilk">Origineel: (Illustratie midden in een alinea)</th>
    </tr>
    <tr align="left">
      <td width="100%" valign="top"> <img src="../illust2.png" alt="" width="500" height="514"> <br>
      </td>
    </tr>
    <tr>
      <th align="left" bgcolor="cornsilk">Correct proefgelezen tekst:</th>
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
<p class="backtotop"><a href="#top">Terug naar boven</a></p>


<h3><a name="footnotes">Voetnoten/Eindnoten</a>
 (<i><a href="#footnotes">Footnotes/Endnotes</a></i>)</h3>
<p>Proeflees voetnoten door de tekst van de voetnoot onderaan de bladzijde te laten staan, en op
   de plaats in de tekst, waar naar de voetnoot verwezen wordt, wordt een markering geplaatst.
</p>
<p>In de hoofdtekst moet het teken dat de plaats van de voetnoot markeert, tussen
   vierkante haken gezet worden (<kbd>[</kbd> and <kbd>]</kbd>) en meteen naast het woord waar
   de voetnoot bij hoort, gezet worden<kbd>[1]</kbd>. Als er interpunctie naast het woord
   staat, dan komt het symbool dat de voetnoot markeert, meteen naast de interpunctie.<kbd>[2]</kbd>
   Zie de voorbeelden in het plaatje hieronder, en die in deze tekst.
   Voetnoot tekens kunnen cijfers, letters en symbolen zijn.
   Als voetnoten gemarkeerd zijn door een symbool of een serie bijzondere symbolen (*, &dagger;, &Dagger;, &sect;, enz.),
   dan vervangen we deze door <kbd>[*]</kbd> in de tekst en <kbd>*</kbd> bij de voetnoot zelf.
</p>
<p>Proeflees aan het eind van de pagina de tekst van de voetnoot zoals hij gedrukt is, en handhaaf de
   regelafbrekingen. Zorg er wel voor, dat je dezelfde markering gebruikt voor de voetnoot,
   als wat er in de tekst staat waar de voetnoot naar verwijst. Gebruik alleen het teken zelf voor de
   markering, zonder enige haken of andere interpunctie.
</p>
<p>Plaats elke voetnoot op een aparte regel in de volgorde zoals ze voorkomen, met een lege regel
   voor elke nieuwe voetnoot.
</p>
<!-- END RR -->
<p>Voeg geen enkele horizontale lijn toe die de voetnoot van de normale tekst scheidt.
</p>
<p><b>Eindnoten</b> zijn voetnoten die samen aan het eind van een hoofdstuk of aan het eind
   van een boek staan. Ze worden op dezelfde manier proefgelezen als voetnoten.
   Als je ergens in de tekst een verwijzing naar een eindnoot vindt, zet er <kbd>[</kbd> en
   <kbd>]</kbd> omheen. Als je een van de pagina's met eindnoten aan het proeflezen bent, voeg
   dan voor elke noot een lege regel in, zodat het duidelijk is waar elke noot begint en eindigt.
</p>
<!-- Need an example of Endnotes, maybe? Good idea!-->

<p><b>Voetnoten in <a href="#tables">Tabellen</a></b> moeten op de plaats blijven waar ze in het origineel staan.
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
    <tr>
      <th valign="top" align="left" bgcolor="cornsilk">Correct proefgelezen tekst:</th>
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
    <tr><th align="left" bgcolor="cornsilk">Origineel, Po&euml;zie met Voetnoot:</th></tr>
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
    <tr><th align="left" bgcolor="cornsilk">Correct proefgelezen tekst:</th></tr>
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
<p class="backtotop"><a href="#top">Terug naar boven</a></p>


<h3><a name="para_side">Beschrijvingen naast een Alinea (Sidenotes)</a>
 (<i><a href="#para_side">Paragraph Side-Descriptions (Sidenotes)</a></i>)</h3>
<p>Sommige boeken hebben korte beschrijvingen van de alinea naast de tekst. Deze heten sidenotes.
   Proeflees de tekst van de sidenote zoals hij gedrukt is, en handhaaf regelafbrekingen (behandel
   overigens wel de <a href="#eol_hyphen">koppelteken en streepjes aan het eind van een regel</a>).
   Laat een lege regel voor en na de sidenote, zodat hij apart blijft van de tekst eromheen.
   De OCR kan de sidenotes overal op de pagina zetten, en kan de tekst zelfs door de rest van de
   tekst heen zetten. Haal dan de tekst uit elkaar, zodat de tekst van de sidenote apart staat.
   De plaats van de sidenote op de bladzijde is niet van belang.
</p>
<!-- END RR -->

<table width="100%" align="center" border="1" cellpadding="4" cellspacing="0" summary="Sidenotes example">
  <tbody>
    <tr valign="top">
      <th align="left" bgcolor="cornsilk">Origineel:</th>
    </tr>
    <tr valign="top">
      <td width="100%" align="left"><img src="../side.png" alt="" width="550" height="800"><br>
      </td>
    </tr>
    <tr valign="top">
      <th align="left" bgcolor="cornsilk">Correct proefgelezen tekst:</th>
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
<p class="backtotop"><a href="#top">Terug naar boven</a></p>


<h3><a name="mult_col">Meerdere Kolommen</a>
 (<i><a href="#mult_col">Multiple Columns</a></i>)</h3>
<p>Proeflees gewone tekst die in meerdere kolommen gedrukt is, als een enkele kolom.
   Plaats de tekst van de linker kolom eerst, de tekst van de volgende eronder, enzovoort.
   Markeer niet waar de kolommen gesplitst waren, voeg ze gewoon samen. Zie het einde 
   van het <a href="#para_side">Beschrijvingen naast een Alinea</a> voorbeeld voor een
   voorbeeld van meerdere kolommen.
</p>
<p>Zie ook de hoofdstukken <a href="#bk_index">Indexen</a> en
   <a href="#tables">Tabellen</a> van deze Proeflees-Richtlijnen.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Terug naar boven</a></p>


<h3><a name="tables">Tabellen</a>
 (<i><a href="#tables">Tables</a></i>)</h3>
<p>Het is de taak van de proeflezer om te zorgen dat alle informatie in de tabel correct is proefgelezen.
   Scheid de gegevens met spaties zoals nodig is, maar maak je geen zorgen over het precies uitlijnen hiervan.
   Handhaaf de regelafbrekingen (behandel overigens wel de
   <a href="#eol_hyphen">woordafbreking aan het eind van een regel en streepjes</a>).
   Negeer alle puntjes of andere interpunctie die gebruikt worden om het geheel uit te lijnen.
</p>
<p><b>Voetnoten</b> in tabellen dienen aan het eind van de tabel te worden gezet.
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
    <tr><th align="left" bgcolor="cornsilk">Correct proefgelezen tekst:</th></tr>
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
    <tr><th align="left" bgcolor="cornsilk">Origineel:</th></tr>
    <tr align="left">
      <td width="100%" valign="top"> <img src="../table3.png" alt="" width="480" height="231"><br>
      </td>
    </tr>
    <tr><th align="left" bgcolor="cornsilk">Correct proefgelezen tekst:</th></tr>
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
<p class="backtotop"><a href="#top">Terug naar boven</a></p>


<h3><a name="poetry">Po&euml;zie/Epigrammen</a>
 (<i><a href="#poetry">Poetry/Epigrams</a></i>)</h3>
<p>Voeg een lege regel toe voor de start van het gedicht of epigram en een lege regel na het
   eind ervan, zodat de formatteerders begin en eind goed kunnen zien.
   Laat de regels links uitgelijnd; handhaaf de regelafbrekingen. Voeg een lege regel toe tussen
   de coupletten, wanneer er in het origineel een staat.
</p>
<p><a href="#line_no">Regelnummers</a> in po&euml;zie moeten worden behouden.
</p>
<p>Controleer de <a href="#comments">Project Comments</a> voor de specifieke tekst waar je aan werkt.
</p>
<!-- END RR -->

<table width="100%" align="center" border="1" cellpadding="4" cellspacing="0" summary="Poetry Example">
  <tbody>
    <tr><th align="left" bgcolor="cornsilk">Origineel:</th></tr>
    <tr align="left">
      <td width="100%" valign="top"> <img src="../poetry2.png" alt="" width="480" height="385"> <br>
      </td>
    </tr>
    <tr><th align="left" bgcolor="cornsilk">Correct proefgelezen tekst:</th></tr>
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
<p class="backtotop"><a href="#top">Terug naar boven</a></p>


<h3><a name="line_no">Regelnummers</a>
 (<i><a href="#line_no">Line Numbers</a></i>)</h3>
<p>Regelnummers komen vaak voor in po&euml;zie-boeken, en normaal gesproken staan ze in
   de kantlijn op elke vijfde of tiende regel.
   Laat regelnummers staan, gebruik een paar spaties om ze apart te zetten van de rest van
   de tekst op die regel, zo dat de formatteerders ze gemakkelijk kunnen vinden.
   Ze zijn nuttig voor de lezers van e-boeken, aangezien po&euml;zie niet wordt
   ge-<span style="border-bottom: 1px dotted green;" title="rewrap: het opnieuw aanbrengen/verplaatsen van regelafbrekingen">rewrapt</span>
   in de e-boek versie.
</p>
<!-- END RR -->
<!-- We need an example image and text for this. -->
<p class="backtotop"><a href="#top">Terug naar boven</a></p>


<h3><a name="next_word">Losstaand Woord Onderaan een Pagina</a>
 (<i><a href="#next_word">Single Word at Bottom of Page</a></i>)</h3>
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
<p class="backtotop"><a href="#top">Terug naar boven</a></p>


<table width="100%" border="0" cellspacing="0" cellpadding="6" summary="Page-level proofreading">
  <tbody>
    <tr>
      <td bgcolor="silver"><h2 style="margin-bottom: 0; margin-top: 0;">Proeflezen op het Pagina-Niveau:</h2></td>
    </tr>
  </tbody>
</table>


<h3><a name="blank_pg">Lege Pagina</a>
 (<i><a href="#blank_pg">Blank Page</a></i>)</h3>
<p>De meeste lege bladzijden, of bladzijden met een illustratie zonder tekst, zullen al gemarkeerd
   zijn met <kbd>[Blank Page]</kbd>. Laat deze markering zoals hij is. Als de bladzijde leeg is,
   en er staat ook geen [Blank Page], hoef je het ook niet toe te voegen.
</p>
<p>Als er wel tekst is, waar de te proeflezen tekst hoort te staan, maar niet in het origineel, of als
   er wel tekst in het origineel staat maar geen tekst in de tekst-gedeelte, volg dan de aanwijzingen voor een
   <a href="#bad_image">Slecht Beeld/Origineel (Bad Image)</a> of een <a href="#bad_text">Slechte Tekst (Bad Text)</a>.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Terug naar boven</a></p>


<h3><a name="title_pg">Titelpagina aan de Voor- of Achterkant</a>
 (<i><a href="#title_pg">Front/Back Title Page</a></i>)</h3>
<p>Proeflees alle tekst precies zoals het op de pagina's gedrukt is, zoals bv. hoofdletters, kleine letters, enz.,
   inclusief het jaar waarin het boek is uitgegeven of het jaar van het copyright.
</p>
<p>In oudere boeken wordt de eerste letter vaak groot en bewerkt weergegeven, proeflees deze letter gewoon als de letter.
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
      <th align="left" bgcolor="cornsilk">Correct proefgelezen tekst:</th>
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
<p class="backtotop"><a href="#top">Terug naar boven</a></p>


<h3><a name="toc">Inhoudsopgave</a>
 (<i><a href="#toc">Table of Contents</a></i>)</h3>
<p>Proeflees de inhoudsopgave precies zoals deze in het boek gedrukt staat, hoofdletters, kleine letters enz.
   Paginanummers moeten behouden blijven. Wanneer er <span style="font-variant: small-caps">Klein Kapitaal</span>
   voorkomt, zie de richtlijnen voor <a href="#small_caps">Klein Kapitaal</a>.
</p>
<p>Negeer alle punten of andere interpunctie die gebruikt zijn om de paginanummers op &eacute;&eacute;n lijn te krijgen. Deze worden later verwijderd.
</p>
<!-- END RR -->

<table width="100%" align="center" border="1" cellpadding="4" cellspacing="0" summary="Table of Contents example">
  <tbody>
    <tr>
      <th align="left" bgcolor="cornsilk">Origineel:</th>
    </tr>
    <tr align="left">
      <td width="100%" valign="top"><img src="../tablec.png" alt="" width="500" height="650">
      </td>
    </tr>
    <tr>
      <th align="left" bgcolor="cornsilk">Correct proefgelezen tekst:</th>
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
<p class="backtotop"><a href="#top">Terug naar boven</a></p>


<h3><a name="bk_index">Indexen</a>
 (<i><a href="#bk_index">Indexes</a></i>)</h3>
<p>Je hoeft de nummers niet netjes op een rij te zetten zoals ze in het origineel staan.
   Je hoeft er alleen voor te zorgen dat de getallen en de interpunctie zijn zoals in de scan.
   Handhaaf de regelafbrekingen.
</p>
<p>Het formatteren van indexen gebeurt later. De proeflezer moet er voor zorgen dat de tekst en de getallen kloppen.
</p>
<p>Zie ook <a href="#mult_col">Meerdere Kolommen</a>.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Terug naar boven</a></p>


<h3><a name="play_n">Toneelstukken: Namen van Spelers/Regieaanwijzingen</a>
 (<i><a href="#play_n">Plays: Actor Names/Stage Directions</a></i>)</h3>
<p>Behandel een verandering van spreker in een dialoog als een nieuwe alinea, met een lege regel ervoor.
   Behandel de naam van de spreker als een aparte alinea, wanneer deze op een aparte regel staat.
</p>
<p>Regieaanwijzingen worden gehouden zoals ze in het origineel staan.
   Wanneer ze op een aparte regel staan, proeflees het dan op die manier.
   Staan ze aan het eind van een regel met dialoog, dan laat ze daar.
   Regieaanwijzingen beginnen vaak met een haakje openen <kbd>[</kbd> en laten het haakje sluiten <kbd>]</kbd> weg.
   Deze gewoonte wordt gehandhaafd; sluit de haakjes niet.
</p>
<p>Soms, vooral in metrische toneelstukken, wordt een woord gesplitst omdat de bladzijde te smal is.
   Het tweede stukje van het woord staat vaak op de regel eronder of erboven, na een (, in plaats van op een eigen regel.
   Voeg het woord samen als bij normale <a href="#eol_hyphen">koppelteken en streepjes aan het eind van een regel</a>.
   Zie het <a href="#play4">voorbeeld</a>.
</p>
<p>Let goed op de <a href="#comments">Project Comments</a>, de Project Manager kan een andere manier van proeflezen vragen.
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
      <th align="left" bgcolor="cornsilk">Correct proefgelezen tekst:</th>
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
      <th align="left" bgcolor="cornsilk">Origineel:</th>
    </tr>
    <tr align="left">
      <td width="100%" valign="top"><img src="../play4.png" width="502" height="98" alt=""><br>
      </td>
    </tr>
    <tr>
      <th align="left" bgcolor="cornsilk">Correct proefgelezen tekst:</th>
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
<p class="backtotop"><a href="#top">Terug naar boven</a></p>


<h3><a name="anything">Alles wat op een speciale manier aangepakt moet worden, of waar je onzeker over bent</a>
 (<i><a href="#anything">Anything else that needs special handling or that you're unsure of</a></i>)</h3>
<p>Als je bij het proeflezen iets tegenkomt dat niet in deze richtlijnen behandeld wordt, en waarvan je wel
   denkt dat het op een speciale manier aangepakt moet worden, of als je niet zeker bent hoe het aan te pakken,
   post dan je vraag, onder vermelding van het png (pagina) nummer, in de <a href="#forums">Project Discussie</a>.
</p>
<p>Je moet ook een aantekening in de proefleestekst zetten, waarin je het probleem uitlegt aan de volgende
   proeflezer, formatteerder of de Post-Processor uitleggen wat het probleem of de vraag is.
   Begin je aantekening met een vierkant haakje en twee sterretjes <kbd>[**</kbd> en sluit hem af met een vierkant
   haakje <kbd>]</kbd>. Dit onderscheidt je aantekening duidelijk van de tekst van de schrijver en geeft de Post-Processor
   een signaal om even te stoppen en zorgvuldig dit gedeelte tekst &amp; origineel te vergelijken om een evt.
   probleem aan te pakken. Je kunt ook aangeven in welke ronde je werkt (bv. net voor de <kbd>]</kbd>) zodat
   volgende vrijwilligers weten wie de aantekening heeft achtergelaten.
   Al het commentaar welke door een vorige vrijwilliger is toegevoegd <b>moet</b> blijven staan.
   Zie de volgende paragraaf voor meer details.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Terug naar boven</a></p>


<h3><a name="prev_notes">Aantekeningen/Commentaar van Eerdere Proeflezers</a>
 (<i><a href="#prev_notes">Previous Proofreaders' Notes/Comments</a></i>)</h3>
<p>Alle commentaar dat gemaakt is door vrijwilligers v&oacute;&oacute;r je <b>moet</b> blijven staan. Of je het er mee eens
   bent of niet kun je toevoegen, maar je mag het commentaar absoluut niet verwijderen.
   Als je een bron hebt gevonden die het probleem verheldert, verwijs daar dan naar,
   zodat de Post-Processor er ook naar kan verwijzen.
</p>
<p>Als je stuit op een aantekening van een eerdere vrijwilliger,
   waar je het antwoord op weet, neem dan even de moeite om feedback te geven.
   Je klikt in de proofreading interface op de naam van de betreffende vrijwilliger en stuurt ze een
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


<h3><a name="formatting">Formattering</a>
 (<i><a href="#formatting">Formatting</a></i>)</h3>
<p>In enkele gevallen is er formattering aanwezig in de tekst.
   <b>Voeg geen formattering toe of corrigeer geen formattering</b>; de formatteerders zullen dat later doen.
   Echter, wanneer het het proeflezen belemmert, dan kun je het verwijderen. De <s>&lt;x&gt;</s> knop in het
   proefleesscherm verwijdert markering zoals &lt;i&gt; en &lt;b&gt; uit de geselecteerde tekst.
   Enkele voorbeelden van formatteertaken zijn:
</p>
<ul>
  <li>&lt;i&gt;cursief&lt;/i&gt;, &lt;b&gt;vet&lt;/b&gt;, &lt;sc&gt;Klein Kapitaal&lt;/sc&gt;</li>
  <li>Uitgespatieerde tekst</li>
  <li>Verandering in grootte van het lettertype</li>
  <li>Ruimte rond hoofdstuk- of paragraaftitels</li>
  <li>Extra spaties, sterretjes, of regels tussen alinea's</li>
  <li>Voetnoten die over meerdere pagina's verdeeld zijn</li>
  <li>Voetnoten gemarkeerd met symbolen</li>
  <li>Illustraties</li>
  <li>Sidenote locaties</li>
  <li>Opmaak van data in tabellen</li>
  <li>Inspringing (in po&euml;zie of elders)</li>
  <li>Samenvoegen van lange regels in po&euml;zie en indexen</li>
</ul>
<p>Wanneer de vorige proeflezer formattering heeft toegevoegd, neem dan alsjeblieft de moeite om
   feedback te geven. Je klikt in de proofreading interface op de naam van de betreffende vrijwilliger
   en stuurt ze een priv&eacute; boodschap (<i>private message</i>) waarin je uitlegt hoe de situatie
   aangepakt kan worden. <b>Vergeet niet om het formatteren over te laten aan de Formatteer Rondes.</b>
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Terug naar boven</a></p>


<h3><a name="common_OCR">Veel Voorkomende Problemen met de OCR</a>
 (<i><a href="#common_OCR">Common OCR Problems</a></i>)</h3>
<p>OCR heeft gewoonlijk problemen om vergelijkbare tekens uit elkaar te houden. Enkele voorbeelden zijn:
</p>
<ul>
  <li>Het cijfer '1' (&eacute;&eacute;n), de kleine letter 'l' (ell), en de hoofdletter 'I'.
    Let op: in sommige lettertypes lijkt het cijfer een op <small>I</small> (zoals een kleine hoofdletter 'i').</li>
  <li>Het cijfer '0' (nul), en de hoofdletter 'O'.</li>
  <li>Streepjes &amp; koppeltekens:
   Proeflees deze zorgvuldig&mdash;geOCRde tekst heeft vaak maar &eacute;&eacute;n  afbreekstreepje voor een em-dash,
   terwijl het er twee moeten zijn. Zie de richtlijnen voor <a href="#eol_hyphen">afgebroken woorden</a>
   en voor <a href="#em-dashes">Liggende streepjes</a> voor uitgebreidere informatie.</li>
  <li>Haakjes ( ) en accolades { }.</li>
</ul>
<p>Let hierop. Lees de context van de zin om te bepalen wat het correcte teken is.
   Maar pas op&mdash;vaak "corrigeert" je geest dit automatisch terwijl je aan het lezen bent.
</p>
<p>Je merkt dit soort dingen veel eerder op als je een 'mono-spaced' lettertype zoals
   <a href="../font_sample.php">DPCustomMono</a> of Courier gebruikt.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Terug naar boven</a></p>


<h3><a name="OCR_scanno">Problemen met de OCR: Scanno's</a>
 (<i><a href="#OCR_scanno">OCR Problems: Scannos</a></i>)</h3>
<p>Een ander veel voorkomend probleem van de OCR is het niet goed herkennen van tekens. We noemen deze fouten
   "scanno's" (zoiets als "typo's"). Deze foutieve herkenning kan leiden tot woorden die:
</p>
<ul compact>
   <li>op het eerste gezicht correct lijken, maar in werkelijkheid een spellingsfout bevatten.<br>
       Deze woorden kun je meestal onderscheppen door <a href="../wordcheck-faq.php">WordCheck</a> te gebruiken.</li>
   <li>veranderd zijn naar een ander, maar wel goed woord, dat niet overeenkomt met het origineel.<br>
       Dit kan alleen opgemerkt worden door iemand die de tekst echt leest.</li>
</ul>
<p>In het Engels is het meest voorkomende voorbeeld van het tweede type "and" dat door de OCR wordt weergegeven als "arid".
  Andere voorbeelden zijn: "eve" voor "eye", "Torn" voor "Tom", "train" voor "tram". In het Nederlands wordt "elken"
  vaak door "eiken" weergegeven, en "mensch" door "mensen". Dit type fout is veel moeilijker om op te sporen en
  we hebben een speciale term ervoor: "Stealth Scanno's".
  We verzamelen voorbeelden van Stealth Scannos in <a href="<?php echo $Stealth_Scannos_URL; ?>">deze discussie</a>.
</p>
<p>Het opmerken van scanno's is veel gemakkelijker als je een 'mono-spaced' lettertype zoals
   <a href="../font_sample.php">DPCustomMono</a> of Courier gebruikt. Om het proeflezen te helpen
   is het gebruik van <a href="../wordcheck-faq.php">WordCheck</a> (of zijn equivalent) aanbevolen
   in <?php echo $ELR_round->id; ?> en verplicht in de andere proeflees-ronden.
</p>
<!-- END RR -->
<!-- More to be added.... -->
<p class="backtotop"><a href="#top">Terug naar boven</a></p>


<h3><a name="OCR_raised_o">Problemen met de OCR: Is die &deg; &ordm; echt een graden-symbool?</a>
 (<i><a href="#OCR_raised_o">OCR Problems: Is that &deg; &ordm; really a degree sign?</a></i>)</h3>
<p>Er zijn drie verschillende symbolen die in het origineel heel erg veel op elkaar kunnen lijken en
   die de OCR software hetzelfde interpreteert (en meestal incorrect):
</p>
<ul>
  <li>Het graden symbool <kbd style="font-size:150%;">&deg;</kbd>: Deze moet alleen gebruikt worden om graden aan te geven (temperatuur, hoek, etc.).</li>
  <li>De superscript o: In eigenlijk alle andere gevallen van een verhoogde o moet deze worden gelezen worden als <kbd>^o</kbd>,
      gebruik makend van de richtlijnen voor <a href="#supers">Superscript</a>.</li>
  <li>Het mannelijke rangorde-teken (ordinal) <kbd style="font-size:150%;">&ordm;</kbd>: Proeflees deze ook als een superscript tenzij er
      in de <a href="#comments">Project Comments</a> om het speciale teken wordt gevraagd.
      Het kan gebruikt worden in talen zoals het Spaans en Portguees, en is het equivalent van het -th in het Engelse 4th, 5th, etc.
      (-ste, -de, -e in het Nederlandse 1ste, 2de, 3e).
      Het volgt na cijfers en heeft een vrouwelijk equivalent in de superscript a (<kbd>&ordf;</kbd>).</li>
</ul>
<!-- END RR -->
<p class="backtotop"><a href="#top">Terug naar boven</a></p>


<h3><a name="hand_notes">Handgeschreven Aantekeningen in een Boek</a>
 (<i><a href="#hand_notes">Handwritten Notes in Book</a></i>)</h3>
<p>Laat handgeschreven aantekeningen weg, behalve als de gedrukte tekst verbleekt en vervolgens overgetrokken is om
   de tekst beter zichtbaar te maken. Laat handgeschreven aantekeningen die door lezers e.d. in de kantlijn gezet zijn, weg.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Terug naar boven</a></p>


<h3><a name="bad_image">Slecht Beeld/Origineel</a>
 (<i><a href="#bad_image">Bad Image</a></i>)</h3>
<p>Als het beeld van een origineel niet goed is (laadt niet, grotendeels onleesbaar)
   post dan alsjeblieft in de <a href="#forums">Project Discussie</a> over dit slechte origineel.
   Klik op de "Report Bad Page" knop, waardoor de pagina in "quarantaine" gaat, in plaats van
   op "Return Page to Round" te klikken. Wanneer slechts een klein deel van het origineel slecht is,
   laat dan een aantekening achter zoals <a href="#anything">hierboven</a> beschreven, en
   post dan alsjeblieft in de <a href="#forums">Project Discussie</a> zonder de hele pagina als slecht
   te rapporteren. De "Bad Page" knop is alleen beschikbaar in de eerste proefleesronde, dus is
   het belangrijk dat deze zaken vroeg opgelost kunnen worden.
</p>
<p>Houd in de gaten dat sommige pagina's erg groot zijn, en dat het dan gebruikelijk is dat je browser
   moeite heeft om ze te laten zien, zeker als je meerdere vensters open hebt of als je een oudere
   computer gebruikt. Probeer, voordat je een slecht origineel meldt, in te zoomen op het origineel,
   enkele schermen en programma's te sluiten, of laat een bericht achter in de project discussie
   om te zien of iemand anders hetzelfde probleem heeft.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Terug naar boven</a></p>


<h3><a name="bad_text">Verkeerd Beeld/Origineel voor de Tekst</a>
 (<i><a href="#bad_text">Wrong Image for Text</a></i>)</h3>
<p>Als er een verkeerd beeld gegeven wordt voor de tekst, post dan alsjeblieft in de
   <a href="#forums">Project Discussie</a>. Klik op de "Report Bad Page" knop, waardoor de pagina in
   "quarantaine" gaat, in plaats van op "Return Page to Round" te klikken. De "Bad Page" knop is alleen
   beschikbaar in de eerste proefleesronde, dus is het belangrijk dat deze zaken vroeg opgelost kunnen worden.
</p>
<p>Wat meer voorkomt, is dat het origineel wel goed is, maar dat de geOCRde tekst de eerste paar
   regels tekst mist. Typ deze regels erbij, alsjeblieft. Als bijna de hele tekst ontbreekt,
   typ hem dan in (als je dat op kunt brengen) of klik op de "Return Page to Round" button en
   de pagina wordt aan iemand anders toegekend. Als er meerdere pagina's zoals deze zijn, post
   dan in het <a href="#forums">Project Discussie</a> om de Project Manager in te lichten.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Terug naar boven</a></p>


<h3><a name="round1">Eerder Gemaakte Proefleesvergissingen</a>
 (<i><a href="#round1">Previous Proofreader Mistakes</a></i>)</h3>
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
<!-- END RR -->
<p class="backtotop"><a href="#top">Terug naar boven</a></p>


<h3><a name="p_errors">Vergissingen van de Drukker/Spelfouten</a>
 (<i><a href="#p_errors">Printer Errors/Misspellings</a></i>)</h3>
<p>Corrigeer alle woorden die door de OCR fout zijn ge&iuml;nterpreteerd (scanno), maar verbeter
   geen spel- of zetfouten die in het origineel voorkomen. In veel oudere teksten werden woorden
   anders gespeld dan we tegenwoordig doen. We handhaven deze oude spelling, inclusief eventuele accenten.
</p>
<p>Maak een aantekening in de tekst direct naast de drukvaut<kbd>[**typo voor drukfout?]</kbd>.
   Ben je onzeker of iets ook werkelijk fout is, doe dan navraag in de Project Discussie.
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


<h3><a name="insert_char">Toevoegen van Speciale Letters</a>
 ../(<i><a href="#insert_char">Inserting Special Characters</a></i>)</h3>
<p>Als je ze niet op je toetsenbord hebt, zijn er meerdere manieren om de speciale letters in te voegen:
</p>
<ul compact>
  <li>De uitklapmenu's in de proofreading interface.</li>
  <li>Hulpprogrammaatjes inbegrepen in je besturingssysteem.
    <ul compact>
      <li>Windows: "Speciale tekens"<br> Te benaderen door:<br>
          Start: Uitvoeren: charmap, ofr<br>
          Start: Bureau-accessoires: Systeemwerkset: Speciale tekens.</li>
      <li>Macintosh: Key Caps of "Keyboard Viewer"<br>
          Voor OS 9 en lager, dit staat in het Apple Menu<br>
          Voor OS X tot en met 10.2, dit is te vinden in de map Toepassingen, Utilities<br>
          Voor OS X 10.3 en hoger, dit is te vinden in het Input Menu als "Toetsenbord viewer"</li>
      <li>Linux: de naam en locatie varieert en hangt af van je desktop omgeving.</li>
    </ul>
  </li>
  <li>Een on-line programma.</li>
  <li>Toetscombinaties.<br>
      (Zie de tabellen hieronder voor <a href="#a_chars_win">Windows</a> en <a href="#a_chars_mac">Macintosh</a>.)</li>
  <li>Switch naar een toetsenbordindeling of een plek die "deadkey" accenten ondersteunt.
    <ul compact>
      <li>Windows: Configuratiescherm (Toetsenbord, Input Locales).</li>
      <li>Macintosh: Input Menu (op de Menu Bar).</li>
      <li>Linux: Verander je toetsenbord in je X configuratie.</li>
    </ul>
  </li>
</ul>
<!-- END RR -->

<a name="a_chars_win"></a>
<p><b>Voor Windows</b>:
</p>
<ul compact>
  <li>Je kunt het programma Speciale Tekens gebruiken (Start: Uitvoeren: charmap) om een teken te knippen &amp; plakken.
  </li>
  <li>De uitklapmenu's in de proofreading interface.
  </li>
  <li>Of je kunt de Alt+Numeriek Toetsenbord sneltoetsen gebruiken voor deze tekens (opgesomd hieronder).
          Als je eenmaal gewend bent aan de codes, werkt dit sneller dan knippen &amp; plakken.
      <br>Houdt de Alt-toets ingedrukt en typ de vier cijfers op je
          <i>Numerieke Toetsenbord</i>&mdash;de getallen boven de letters werken niet.
      <br>Je moet alle 4 de getallen typen, inclusief de 0 aan het begin.
          Houd in de gaten dat de hoofdletter van een letter 32 minder is dan de kleine letter.
      <br>Deze instructies werken voor de US-engelse toetsenbord indeling. Bij andere indelingen werken ze misschien niet.
      <br>(<a href="../charwin.pdf">Printer-vriendelijke versie van deze tabel</a>).
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
      <td></td>
  </tr>
  <tr><td align="center" bgcolor="mistyrose" title="kleine letter o grave"       >&ograve; </td><td>Alt-0242</td>
      <td align="center" bgcolor="mistyrose" title="kleine letter o acute"       >&oacute; </td><td>Alt-0243</td>
      <td align="center" bgcolor="mistyrose" title="kleine letter o circumflex"  >&ocirc;  </td><td>Alt-0244</td>
      <td align="center" bgcolor="mistyrose" title="kleine letter o tilde"       >&otilde; </td><td>Alt-0245</td>
      <td align="center" bgcolor="mistyrose" title="kleine letter o umlaut"      >&ouml;   </td><td>Alt-0246</td>
      <td align="center" bgcolor="mistyrose" title="kleine letter o slash"       >&oslash; </td><td>Alt-0248</td>
      <td></td><td></td>
  </tr>
  <tr><td align="center" bgcolor="mistyrose" title="Hoofdletter O grave"       >&Ograve; </td><td>Alt-0210</td>
      <td align="center" bgcolor="mistyrose" title="Hoofdletter O acute"       >&Oacute; </td><td>Alt-0211</td>
      <td align="center" bgcolor="mistyrose" title="Hoofdletter O circumflex"  >&Ocirc;  </td><td>Alt-0212</td>
      <td align="center" bgcolor="mistyrose" title="Hoofdletter O tilde"       >&Otilde; </td><td>Alt-0213</td>
      <td align="center" bgcolor="mistyrose" title="Hoofdletter O umlaut"      >&Ouml;   </td><td>Alt-0214</td>
      <td align="center" bgcolor="mistyrose" title="Hoofdletter O slash"       >&Oslash; </td><td>Alt-0216</td>
      <td></td><td></td>
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
      <td align="center" bgcolor="mistyrose" title="kleine letter y acute"   >&yacute; </td><td>Alt-0253</td>
      <td> </td><td> </td>
      <td align="center" bgcolor="mistyrose" title="kleine letter n tilde"   >&ntilde; </td><td>Alt-0241</td>
      <td align="center" bgcolor="mistyrose" title="kleine letter y umlaut"  >&yuml;   </td><td>Alt-0255</td>
      <td align="center" bgcolor="mistyrose" title="Cents"                   >&cent;   </td><td>Alt-0162</td>
      <td align="center" bgcolor="mistyrose" title="plus/min"                >&plusmn; </td><td>Alt-0177</td>
  </tr>
  <tr><td> </td><td> </td>
      <td align="center" bgcolor="mistyrose" title="Hoofdletter Y acute"     >&Yacute; </td><td>Alt-0221</td>
      <td> </td><td> </td>
      <td align="center" bgcolor="mistyrose" title="Hoofdletter N tilde"     >&Ntilde; </td><td>Alt-0209</td>
      <td></td><td></td>
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
      <td align="center" bgcolor="mistyrose" title="Algemene munteenheid"    >&curren; </td><td>Alt-0164</td>
      <td align="center" bgcolor="mistyrose" title="Logical Not"             >&not;    </td><td>Alt-0172</td>
  </tr>
  <tr><td align="center" bgcolor="mistyrose" title="Hoofdletter C cedille"   >&Ccedil; </td><td>Alt-0199</td>
      <td align="center" bgcolor="mistyrose" title="kleine letter thorn"     >&thorn;  </td><td>Alt-0254</td>
      <td align="center" bgcolor="mistyrose" title="Registratie Teken"       >&reg;    </td><td>Alt-0174</td>
      <td align="center" bgcolor="mistyrose" title="umlaut accent"           >&uml;    </td><td>Alt-0168</td>
      <td align="center" bgcolor="mistyrose" title="Omgekeerd Uitroepteken"  >&iexcl;  </td><td>Alt-0161</td>
      <td> </td><td> </td>
      <td align="center" bgcolor="mistyrose" title="Graden"                  >&deg;    </td><td>Alt-0176</td>
  </tr>
  <tr><th colspan=2 bgcolor="cornsilk">superscripts        </th>
      <td align="center" bgcolor="mistyrose" title="Hoofdletter Eth"         >&ETH;    </td><td>Alt-0208</td>
      <td align="center" bgcolor="mistyrose" title="Alinea"                  >&para;   </td><td>Alt-0182</td>
      <td align="center" bgcolor="mistyrose" title="macron accent"           >&macr;   </td><td>Alt-0175</td>
      <td align="center" bgcolor="mistyrose" title="Guillemet links (Ganzenvoetjes)"   >&laquo;  </td><td>Alt-0171</td>
      <td> </td><td> </td>
      <td align="center" bgcolor="mistyrose" title="Micro"                   >&micro;  </td><td>Alt-0181</td>
  </tr>
  <tr><td align="center" bgcolor="mistyrose" title="superscript 1"             >&sup1;   </td><td>Alt-0185&nbsp;*</td>
      <td align="center" bgcolor="mistyrose" title="kleine letter eth"         >&eth;    </td><td>Alt-0240</td>
      <td align="center" bgcolor="mistyrose" title="Paragraaf"                 >&sect;   </td><td>Alt-0167</td>
      <td align="center" bgcolor="mistyrose" title="cedille"                   >&cedil;  </td><td>Alt-0184</td>
      <td align="center" bgcolor="mistyrose" title="Guillemet rechts (Ganzenvoetjes)"  >&raquo;  </td><td>Alt-0187</td>
      <th colspan=2 bgcolor="cornsilk">rangorde-tekens</th>
      <td align="center" bgcolor="mistyrose" title="1/4 Breuk"                 >&frac14; </td><td>Alt-0188&nbsp;&dagger;</td>
  </tr>
  <tr><td align="center" bgcolor="mistyrose" title="superscript 2"             >&sup2;   </td><td>Alt-0178&nbsp;*</td>
      <th colspan=2 bgcolor="cornsilk">sz verbinding    </th>
      <td align="center" bgcolor="mistyrose" title="Gebroken Verticale Lijn"   >&brvbar; </td><td>Alt-0166</td>
      <td> </td><td> </td>
      <td align="center" bgcolor="mistyrose" title="'Zwevende' punt"           >&middot; </td><td>Alt-0183</td>
      <td align="center" bgcolor="mistyrose" title="Mannelijk rangorde-teken"  >&ordm;   </td><td>Alt-0186&nbsp;*</td>
      <td align="center" bgcolor="mistyrose" title="1/2 Breuk"                 >&frac12; </td><td>Alt-0189&nbsp;&dagger;</td>
  </tr>
  <tr><td align="center" bgcolor="mistyrose" title="superscript 3"             >&sup3;   </td><td>Alt-0179&nbsp;*</td>
      <td align="center" bgcolor="mistyrose" title="sz verbinding"             >&szlig;  </td><td>Alt-0223</td>
      <td> </td><td> </td>
      <td> </td><td> </td>
      <td> </td><td> </td>
      <td align="center" bgcolor="mistyrose" title="Vrouwelijk rangorde-teken" >&ordf;   </td><td>Alt-0170&nbsp;*</td>
      <td align="center" bgcolor="mistyrose" title="3/4 Breuk"                 >&frac34; </td><td>Alt-0190&nbsp;&dagger;</td>
  </tr>
  </tbody>
</table>
<p>* Gebruik alsjeblieft de rangorde-tekens en superscript symbolen niet, tenzij het in de
   <a href="#comments">Project Comments</a> specifiek gevraagd wordt.
   Volg de richtlijnen voor <a href="#supers">Superscript</a>. (x^2, f^o, etc.)
</p>
<p>&dagger; Gebruik alsjeblieft de symbolen voor breuken niet, tenzij het in de
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
  <li>Of je kunt de Apple Opt- sneltoets codes voor deze tekens gebruiken (opgesomd hieronder).
      <br>Als je eenmaal aan de codes gewend bent, gaat dit een stuk sneller dan knippen &amp; plakken.
      <br>Houd de Opt-toets vast en typ het symbool voor het accent. Typ daarna de letter die een accent moet
          krijgen. Voor sommige codes hoef je alleen de Opt-toets vast te houden en het symbool te typen.
      <br>Deze instructies werken voor de US-Engelse toetsenbord indeling. Bij andere indelingen werken ze misschien niet.
      <br><a href="../charapp.pdf">Printer-vriendelijke versie van deze tabel</a>).
  </li>
</ul>

<br>
<a name="a_chars_mac"></a>
<table align="center" border="6" rules="all" summary="Apple Mac Sneltoetsen">
  <tbody>
  <tr bgcolor="cornsilk">
      <th colspan=14>Apple Mac Sneltoetsen voor Latin-1 symbolen</th>
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
      <td></td>
  </tr>
  <tr><td align="center" bgcolor="mistyrose" title="kleine letter o grave"       >&ograve; </td><td>Opt-`, o</td>
      <td align="center" bgcolor="mistyrose" title="kleine letter o acute"       >&oacute; </td><td>Opt-e, o</td>
      <td align="center" bgcolor="mistyrose" title="kleine letter o circumflex"  >&ocirc;  </td><td>Opt-i, o</td>
      <td align="center" bgcolor="mistyrose" title="kleine letter o tilde"       >&otilde; </td><td>Opt-n, o</td>
      <td align="center" bgcolor="mistyrose" title="kleine letter o umlaut"      >&ouml;   </td><td>Opt-u, o</td>
      <td align="center" bgcolor="mistyrose" title="kleine letter o slash"       >&oslash; </td><td>Opt-o   </td>
      <td></td><td></td>
  </tr>
  <tr><td align="center" bgcolor="mistyrose" title="Hoofdletter O grave"         >&Ograve; </td><td>Opt-`, O</td>
      <td align="center" bgcolor="mistyrose" title="Hoofdletter O acute"         >&Oacute; </td><td>Opt-e, O</td>
      <td align="center" bgcolor="mistyrose" title="Hoofdletter I circumflex"    >&Ocirc;  </td><td>Opt-i, O</td>
      <td align="center" bgcolor="mistyrose" title="Hoofdletter O tilde"         >&Otilde; </td><td>Opt-n, O</td>
      <td align="center" bgcolor="mistyrose" title="Hoofdletter O umlaut"        >&Ouml;   </td><td>Opt-u, O</td>
      <td align="center" bgcolor="mistyrose" title="Hoofdletter O slash"         >&Oslash; </td><td>Opt-O   </td>
      <td></td><td></td>
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
      <td align="center" bgcolor="mistyrose" title="kleine letter y acute"         >&yacute; </td><td>Opt-e, y</td>
      <td> </td><td> </td>
      <td align="center" bgcolor="mistyrose" title="kleine letter n tilde"   >&ntilde; </td><td>Opt-n, n</td>
      <td align="center" bgcolor="mistyrose" title="kleine letter y umlaut"  >&yuml;   </td><td>Opt-u, y</td>
      <td align="center" bgcolor="mistyrose" title="Cents"                   >&cent;   </td><td>Opt-4   </td>
      <td align="center" bgcolor="mistyrose" title="plus/min"                >&plusmn; </td><td>Shift-Opt-=</td>
  </tr>
  <tr><td> </td><td> </td>
      <td align="center" bgcolor="mistyrose" title="Capital Y acute"       >&Yacute; </td><td>Opt-e, Y</td>
      <td> </td><td> </td>
      <td align="center" bgcolor="mistyrose" title="Hoofdletter N tilde"     >&Ntilde; </td><td>Opt-n, N</td>
      <td></td><td></td>
      <td align="center" bgcolor="mistyrose" title="Pounds"                  >&pound;  </td><td>Opt-3   </td>
      <td align="center" bgcolor="mistyrose" title="Vermenigvuldiging"       >&times;  </td><td>(geen)&nbsp;&Dagger;</td>
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
      <td align="center" bgcolor="mistyrose" title="Algemene munteenheid"    >&curren; </td><td>(geen)&nbsp;&Dagger;  </td>
      <td align="center" bgcolor="mistyrose" title="Logical Not"             >&not;    </td><td>Opt-l   </td>
  </tr>
  <tr><td align="center" bgcolor="mistyrose" title="Hoofdletter C cedille"   >&Ccedil; </td><td>Opt-C   </td>
      <td align="center" bgcolor="mistyrose" title="kleine letter thorn"     >&thorn;  </td><td>(geen)&nbsp;&Dagger;  </td>
      <td align="center" bgcolor="mistyrose" title="Registratie Teken"       >&reg;    </td><td>Opt-r   </td>
      <td align="center" bgcolor="mistyrose" title="umlaut accent"           >&uml;    </td><td>Opt-U   </td>
      <td align="center" bgcolor="mistyrose" title="Omgekeerd Uitroepteken"  >&iexcl;  </td><td>Opt-1   </td>
      <td> </td><td> </td>
      <td align="center" bgcolor="mistyrose" title="Graden"                  >&deg;    </td><td>Shift-Opt-8   </td>
  </tr>
  <tr><th colspan=2 bgcolor="cornsilk">superscripts</th>
      <td align="center" bgcolor="mistyrose" title="Hoofdletter Eth"         >&ETH;    </td><td>(geen)&nbsp;&Dagger;  </td>
      <td align="center" bgcolor="mistyrose" title="Alinea"                    >&para;   </td><td>Opt-7   </td>
      <td align="center" bgcolor="mistyrose" title="Macron accent"           >&macr;   </td><td>Shift-Opt-,</td>
      <td align="center" bgcolor="mistyrose" title="Guillemet links (Ganzenvoetjes)"   >&laquo;  </td><td>Opt-\   </td>
      <td> </td><td> </td>
      <td align="center" bgcolor="mistyrose" title="Micro"                   >&micro;  </td><td>Opt-m   </td>
  </tr>
  <tr><td align="center" bgcolor="mistyrose" title="superscript 1"             >&sup1;   </td><td>(geen)&nbsp;*&Dagger;  </td>
      <td align="center" bgcolor="mistyrose" title="kleine letter eth"         >&eth;    </td><td>(geen)&nbsp;&Dagger;  </td>
      <td align="center" bgcolor="mistyrose" title="Paragraaf"                 >&sect;   </td><td>Opt-6   </td>
      <td align="center" bgcolor="mistyrose" title="cedille"                   >&cedil;  </td><td>Opt-Z   </td>
      <td align="center" bgcolor="mistyrose" title="Guillemet rechts (Ganzenvoetjes)"  >&raquo;  </td><td>Shift-Opt-\</td>
      <th colspan=2 bgcolor="cornsilk">rangorde-tekens </th>
      <td align="center" bgcolor="mistyrose" title="1/4 Breuk"                 >&frac14; </td><td>(geen)&nbsp;&dagger;&Dagger;</td>
  </tr>
  <tr><td align="center" bgcolor="mistyrose" title="superscript 2"             >&sup2;   </td><td>(geen)&nbsp;*&Dagger;  </td>
      <th colspan=2 bgcolor="cornsilk">sz verbinding </th>
      <td align="center" bgcolor="mistyrose" title="Gebroken Verticale Lijn"   >&brvbar; </td><td>(geen)&nbsp;&Dagger;  </td>
      <td> </td><td> </td>
      <td align="center" bgcolor="mistyrose" title="'Zwevende' punt"           >&middot; </td><td>Shift-Opt-9  </td>
      <td align="center" bgcolor="mistyrose" title="Mannelijk rangorde-teken"  >&ordm;   </td><td>Opt-0&nbsp;*</td>
      <td align="center" bgcolor="mistyrose" title="1/2 Breuk"                 >&frac12; </td><td>(geen)&nbsp;&dagger;&Dagger;</td>
  </tr>
  <tr><td align="center" bgcolor="mistyrose" title="superscript 3"             >&sup3;   </td><td>(geen)&nbsp;*&Dagger;  </td>
      <td align="center" bgcolor="mistyrose" title="sz verbinding"             >&szlig;  </td><td>Opt-s   </td>
      <td> </td><td> </td>
      <td> </td><td> </td>
      <td> </td><td> </td>
      <td align="center" bgcolor="mistyrose" title="Vrouwelijk rangorde-teken" >&ordf;   </td><td>Opt-9&nbsp;*</td>
      <td align="center" bgcolor="mistyrose" title="3/4 Breuk"                 >&frac34; </td><td>(geen)&nbsp;&dagger;&Dagger;</td>
  </tr>
  </tbody>
</table>
<p>* Gebruik alsjeblieft de rangorde-tekens en superscript symbolen niet, tenzij het in de
   <a href="#comments">Project Comments</a> specifiek gevraagd wordt.
   Volg de richtlijnen voor <a href="#supers">Superscript</a>. (x^2, f^o, etc.)
</p>
<p>&dagger; Gebruik alsjeblieft de symbolen voor breuken niet, tenzij het in de
   <a href="#comments">Project Comments</a> specifiek gevraagd wordt.
   Volg de richtlijnen voor <a href="#fract_s">Breuken</a> (1/2, 1/4, 3/4 enz.).
</p>
<p>&Dagger;&nbsp;Noot: geen sneltoetsen, gebruik het uitklapmenu.
</p>
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
        <li><a href="#quote_ea">Aanhalingstekens op Iedere Regel</a></li>
        <li><a href="#double_q">Aanhalingstekens, Dubbele </a></li>
        <li><a href="#single_q">Aanhalingstekens, Enkele</a></li>
        <li><a href="#chap_head">Aanhalingstekens, Missend aan het begin van het hoofdstuk</a></li>
        <li><a href="#hand_notes">Aantekeningen, Handgeschreven</a></li>
        <li><a href="#prev_notes">Aantekeningen van Eerdere Proeflezers</a></li>
        <li><a href="#a_chars">Accenten op Letters/Niet-ASCII Tekens</a></li>
        <li><a href="#title_pg">Achterkant Titelpagina</a></li>
        <li><a href="#a_chars">ae Ligaturen</a></li>
        <li><a href="#em_dashes">Afbreekstreepjes</a></li>
        <li><a href="#eop_hyphen">Afbreekstreepjes aan het Eind van een Bladzijde</a></li>
        <li><a href="#eol_hyphen">Afbreekstreepjes aan het Eind van een Regel</a></li>
        <li><a href="#para_space">Alinea's, Ruimte Tussen  /Inspringingen</a></li>
        <li><a href="#anything">Alles wat op een speciale manier aangepakt moet worden</a></li>
        <li><a href="#bad_image">Beeld, Slecht</a></li>
        <li><a href="#period_p">Beletselteken ofwel Ellips "&hellip;"</a></li>
        <li><a href="#para_side">Beschrijvingen naast een Alinea</a></li>
        <li><a href="#illust">Bijschrift, Illustraties</a></li>
        <li><a href="#fract_s">Breuken</a></li>
        <li><a href="#line_no">Cijfers in kantlijn</a></li>
        <li><a href="#prev_notes">Commentaar van Eerdere Proeflezers</a></li>
        <li><a href="#formatting">Cursieve Tekst</a></li>
        <li><a href="#d_chars">Diakritische Tekens</a></li>
        <li><a href="#anything">Dingen waar je onzeker over bent</a></li>
        <li><a href="#play_n">Drama</a></li>
        <li><a href="#p_errors">Drukfouten</a></li>
        <li><a href="#double_q">Dubbele Aanhalingstekens</a></li>
        <li><a href="#chap_head">Dubbele Aanhalingstekens, Missend aan het begin van het hoofdstuk</a></li>
        <li><a href="#round1">Eerder Gemaakte Proefleesvergissingen</a></li>
        <li><a href="#prev_notes">Eerdere Proeflezers, Aantekeningen/Commentaar van</a></li>
        <li><a href="#eop_hyphen">Eind van een Bladzijde, Koppelteken en Streepjes aan het</a></li>
        <li><a href="#eol_hyphen">Eind van een Regel, Koppelteken en Streepjes aan het</a></li>
        <li><a href="#trail_s">Eind van een Regel, Spaties aan het</a></li>
        <li><a href="#period_s">Eind van een Zin, Punten aan het </a></li>
        <li><a href="#footnotes">Eindnoten</a></li>
        <li><a href="#period_p">Ellips "&hellip;"</a></li>
        <li><a href="#em_dashes">Em-dashes</a></li>
        <li><a href="#single_q">Enkele Aanhalingstekens</a></li>
        <li><a href="#poetry">Epigrammen</a></li>
        <li><a href="#extra_sp">Extra Spaties Tussen Woorden</a></li>
        <li><a href="#f_errors">Feitelijke Fouten in de Tekst</a></li>
        <li><a href="#formatting">Formattering</a></li>
        <li><a href="#forums">Forum</a></li>
        <li><a href="#f_errors">Fouten in de Tekst, Feitelijke</a></li>
        <li><a href="#em_dashes">Gedachtestreepje, Minteken</a></li>
        <li><a href="#OCR_raised_o">Graden-symbool</a></li>
        <li><a href="#f_chars">Griekse Tekst</a></li>
        <li><a href="#drop_caps">Grote, Versierde Hoofdletter aan het Begin van een Regel</a></li>
        <li><a href="#hand_notes">Handgeschreven Aantekeningen in een Boek</a></li>
        <li><a href="#summary">Handige Proeflees Gids</a></li>
        <li><a href="#f_chars">Hebreeuwse Tekst</a></li>
        <li><a href="#prev_pg">Het Herstellen van Vergissingen op Voorgaande Pagina's</a></li>
        <li><a href="#drop_caps">Hoofdletter aan het Begin van een Regel, Grote Versierde</a></li>
        <li><a href="#small_caps">Hoofdlettertjes, <span style="font-variant: small-caps">Kleine</span></a></li>
        <li><a href="#prime">Hoofdregel </a></li>
        <li><a href="#chap_head">Hoofdstuktitels</a></li>
        <li><a href="#illust">Illustraties</a></li>
        <li><a href="#bk_index">Indexen</a></li>
        <li><a href="#toc">Inhoudsopgave</a></li>
        <li><a href="#para_space">Inspringingen</a></li>
        <li><a href="#punctuat">Interpunctie en Spaties</a></li>
        <li><a href="#small_caps"><span style="font-variant: small-caps">Klein Kapitaal</span></a></li>
        <li><a href="#small_caps"><span style="font-variant: small-caps">Kleine Hoofdlettertjes (Klein Kapitaal)</span></a></li>
        <li><a href="#mult_col">Kolommen, Meerdere</a></li>
        <li><a href="#em_dashes">Koppelteken, Gedachtestreepje, Minteken</a></li>
        <li><a href="#eop_hyphen">Koppelteken en Streepjes aan het Eind van een Bladzijde</a></li>
        <li><a href="#eol_hyphen">Koppelteken en Streepjes aan het Eind van een Regel</a></li>
        <li><a href="#page_hf">Koptekst en Voettekst</a></li>
        <li><a href="#insert_char">Latin-1 Letters, Toevoegen</a></li>
        <li><a href="#blank_pg">Lege Pagina</a></li>
        <li><a href="#d_chars">Letters met Diakritische Tekens</a></li>
        <li><a href="#a_chars">Ligaturen</a></li>
        <li><a href="#em_dashes">Liggend streepje</a></li>
        <li><a href="#next_word">Losstaand Woord Onderaan een Pagina</a></li>
        <li><a href="#period_p">LOTE (Talen anders dan Engels), Ellipsen in</a></li>
      </ul>
    </td>
    <td width="50%" valign="top">
      <ul>
        <li><a href="#mult_col">Meerdere Kolommen</a></li>
        <li><a href="#em_dashes">Minteken</a></li>
        <li><a href="#play_n">Namen van Spelers</a></li>
        <li><a href="#a_chars">Niet-ASCII Tekens</a></li>
        <li><a href="#f_chars">Niet-Latijnse Lettertekens</a></li>
        <li><a href="#line_no">Nummers in kantlijn</a></li>
        <li><a href="#common_OCR">OCR-Problemen, Veel Voorkomende</a></li>
        <li><a href="#a_chars">oe Ligaturen</a></li>
        <li><a href="#OCR_raised_o">Ordinal</a></li>
        <li><a href="#bad_image">Origineel, Slecht</a></li>
        <li><a href="#about">Over Dit Document </a></li>
        <li><a href="#blank_pg">Pagina, Lege </a></li>
        <li><a href="#period_p">Pauzeteken "&hellip;"</a></li>
        <li><a href="#poetry">Po&euml;zie</a></li>
        <li><a href="#OCR_raised_o">Problemen met de OCR: Is die &deg; &ordm; echt een graden-symbool?</a></li>
        <li><a href="#OCR_scanno">Problemen met de OCR: Scanno's</a></li>
        <li><a href="#round1">Proefleesvergissingen, Eerder Gemaakte</a></li>
        <li><a href="#forums">Project Discussie </a></li>
        <li><a href="#period_s">Punten aan het Eind van een Zin</a></li>
        <li><a href="#double_q">Quotes, Dubbele </a></li>
        <li><a href="#single_q">Quotes, Enkele</a></li>
        <li><a href="#OCR_raised_o">Rangorde-teken</a></li>
        <li><a href="#line_br">Regelafbrekingen</a></li>
        <li><a href="#line_no">Regelnummers</a></li>
        <li><a href="#play_n">Regieaanwijzingen (Toneel/Drama)</a></li>
        <li><a href="#para_space">Ruimte Tussen Alinea's/Inspringingen</a></li>
        <li><a href="#contract">Samentrekkingen</a></li>
        <li><a href="#summary">Samenvatting van de Richtlijnen</a></li>
        <li><a href="#OCR_scanno">Scanno's</a></li>
        <li><a href="#para_side">Sidenotes</a></li>
        <li><a href="#bad_image">Slecht Beeld, Origineel</a></li>
        <li><a href="#insert_char">Sneltoetsen van Latin-1 Letters</a></li>
        <li><a href="#trail_s">Spaties aan het Eind van een Regel</a></li>
        <li><a href="#extra_sp">Spaties, Extra</a></li>
        <li><a href="#punctuat">Spaties</a></li>
        <li><a href="#insert_char">Speciale Letters, Toevoegen van</a></li>
        <li><a href="#p_errors">Spelfouten</a></li>
        <li><a href="#em_dashes">Streepjes</a></li>
        <li><a href="#eop_hyphen">Streepjes aan het Eind van een Bladzijde</a></li>
        <li><a href="#eol_hyphen">Streepjes aan het Eind van een Regel</a></li>
        <li><a href="#subscr">Subscript</a></li>
        <li><a href="#supers">Superscript</a></li>
        <li><a href="#tables">Tabellen</a></li>
        <li><a href="#extra_sp">Tabs</a></li>
        <li><a href="#period_p">Talen anders dan Engels (LOTE), Ellipsen in</a></li>
        <li><a href="#a_chars">Tekens met Accenten/Niet-ASCII Tekens</a></li>
        <li><a href="#bad_text">Tekst, Verkeerd Beeld/Origineel voor de </a></li>
        <li><a href="#title_pg">Titelpagina aan de Voor- of Achterkant</a></li>
        <li><a href="#chap_head">Titels, Hoofdstuk-</a></li>
        <li><a href="#comments">Toelichting bij een Project </a></li>
        <li><a href="#insert_char">Toetsenbord Sneltoetsen voor Latin-1 Letters</a></li>
        <li><a href="#insert_char">Toevoegen van Speciale Letters</a></li>
        <li><a href="#play_n">Toneelstukken: Namen van Spelers/Regieaanwijzingen</a></li>
        <li><a href="#insert_char">Uitklapmenu's</a></li>
        <li><a href="#common_OCR">Veel Voorkomende Problemen met de OCR</a></li>
        <li><a href="#prev_pg">Vergissingen op Voorgaande Pagina's, Het Herstellen van</a></li>
        <li><a href="#p_errors">Vergissingen van de Drukker/Spelfouten</a></li>
        <li><a href="#supers">Verhoogde Tekst (Superscript)</a></li>
        <li><a href="#bad_text">Verkeerd Beeld/Origineel voor de Tekst</a></li>
        <li><a href="#bad_text">Verkeerde Tekst</a></li>
        <li><a href="#subscr">Verlaagde Tekst (Subscript)</a></li>
        <li><a href="#drop_caps">Versierde Hoofdletter aan het Begin van een Regel</a></li>
        <li><a href="#formatting">Vette Tekst</a></li>
        <li><a href="#footnotes">Voetnoten</a></li>
        <li><a href="#page_hf">Voettekst</a></li>
        <li><a href="#prev_pg">Voorgaande Pagina's, Het Herstellen van Vergissingen op</a></li>
        <li><a href="#title_pg">Voorkant Titelpagina</a></li>
        <li><a href="#next_word">Woord Onderaan een Pagina</a></li>
        <li><a href="#eop_hyphen">Woordafbreking aan het Eind van een Bladzijde</a></li>
        <li><a href="#eol_hyphen">Woordafbreking aan het Eind van een Regel</a></li>
        <li><a href="#small_caps">Woorden in <span style="font-variant: small-caps">Kleine Hoofdlettertjes (Klein Kapitaal)</span></a></li>
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
// vim: sw=4 ts=4 expandtab
