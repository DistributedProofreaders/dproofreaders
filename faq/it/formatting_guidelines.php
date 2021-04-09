<?php
// Translated by user babymag 18 Feb 2009

$relPath='../pinc/';
include_once($relPath.'base.inc');
include_once($relPath.'faq.inc');
include_once($relPath.'pg.inc');
include_once($relPath.'theme.inc');

maybe_redirect_to_external_faq("it");

$theme_args["css_data"] = "p.backtotop {text-align:right; font-size:75%;margin-right:-5%;}";

output_header('Regole di Formattazione', NO_STATSBAR, $theme_args);

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

<h1 align="center"><a name="top">Regole di Formattazione</a></h1>

<h3 align="center">Versione 2.0, revisionata giugno 7, 2009 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
  <a href="../dochist.php"><font size="-1">(Storia delle modifiche)</font></a></h3>

<p>Regole di Formattazione <a href="../formatting_guidelines.php">in Inglese</a> /
      Formatting Guidelines <a href="../formatting_guidelines.php">in English</a><br>
    Regole di Formattazione <a href="../fr/formatting_guidelines.php">in Francese</a> /
      Directives de Formatage <a href="../fr/formatting_guidelines.php">en fran&ccedil;ais</a><br>
    Regole di Formattazione <a href="../pt/formatting_guidelines.php">in Portoghese</a> /
      Regras de Formata&ccedil;&atilde;o <a href="../pt/formatting_guidelines.php">em Portugu&ecirc;s</a><br>
    Regole di Formattazione <a href="../nl/formatting_guidelines.php">in Olandese</a> /
      Formatteer-Richtlijnen <a href="../nl/formatting_guidelines.php">in het Nederlands</a><br>
    Regole di Formattazione <a href="../de/formatting_guidelines.php">in Tedesco</a> /
      Formatierungsrichtlinien <a href="../de/formatting_guidelines.php">auf Deutsch</a><br>
</p>

<p>Prova il <a href="../../quiz/start.php?show_only=FQ">Quiz di addestramento per la Formattazione</a>!
</p>

<table border="0" cellspacing="0" width="100%" summary="Table of Contents">
  <tbody>
  <tr>
    <td width="1" bgcolor="silver">&nbsp;</td>
    <td bgcolor="silver" align="center"><h2 style="margin-top:0; margin-bottom:0;">Sommario</h2></td>
  </tr>
  <tr>
    <td width="1" bgcolor="silver">&nbsp;</td>
    <td bgcolor="white" align="left">
    <ul>
      <li><a href="#prime">La regola principale</a></li>
      <li><a href="#summary">La guida in breve</a></li>
      <li><a href="#about">Riguardo a questo documento</a></li>
      <li><a href="#separate_pg">Ogni pagina &egrave; un'unit&agrave; singola </a></li>
      <li><a href="#comments">Commenti al progetto (Project Comments)</a></li>
      <li><a href="#forums">Forum/Discuti il progetto (Discuss This Project)</a></li>
      <li><a href="#prev_pg">Correggere errori nelle pagine precedenti</a></li>
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
        <li style="margin-top:.25em;"><font size="+1">Formattazione a Livello dei Caratteri :</font>
        <ul>
          <li><a href="#inline">Inserimento dei marcatori di formattazione di riga</a></li>
          <li><a href="#italics">Corsivo</a></li>
          <li><a href="#bold">Grassetto</a></li>
          <li><a href="#underl">Testo sottolineato</a></li>
          <li><a href="#spaced"><span style="letter-spacing: .2em;">Testo spazieggiato</span> (gesperrt)</a></li>
          <li><a href="#font_ch">Cambiamenti di carattere (font) </a></li>
          <li><a href="#small_caps"><span style="font-variant: small-caps">Maiuscoletto</span></a></li>
          <li><a href="#word_caps">Parole in Tutte Maiuscole </a></li>
          <li><a href="#font_sz">Cambiamento di misura del carattere </a></li>
          <li><a href="#extra_sp">Spazi aggiuntivi o Tabulazioni tra le parole</a></li>
          <li><a href="#supers">Apici/Esponenti</a></li>
          <li><a href="#subscr">Pedici </a></li>
          <li><a href="#page_ref">Riferimenti di pagina &quot;Vedi p. 123&quot;</a></li>
        </ul></li>
        <li style="margin-top:.25em;"><font size="+1">Formattazione a Livello dei Paragrafi:</font>
        <ul>
          <li><a href="#chap_head">Intestazioni di capitolo </a></li>
          <li><a href="#sect_head">Intestazioni di sezione</a></li>
          <li><a href="#maj_div">Altre importanti suddivisioni nel testo</a></li>
          <li><a href="#para_space">Spaziatura del paragrafo/Rientro </a></li>
          <li><a href="#extra_s">Pause di pensiero (Thought Breaks, Spazi aggiuntivi/Decorazioni tra paragrafi)</a></li>
          <li><a href="#illust">Illustrazioni</a></li>
          <li><a href="#footnotes">Note a pi&egrave; di pagina/Note di chiusura</a></li>
          <li><a href="#para_side">Note a margine del paragrafo (Sidenotes)</a></li>
          <li><a href="#outofline">Inserimento dei Marcatori di Formattazione di Paragrafo </a></li>
          <li><a href="#block_qt">Citazioni in blocco</a></li>
          <li><a href="#lists">Elenchi</a></li>
          <li><a href="#tables">Tabelle</a></li>
          <li><a href="#poetry">Poesia/Epigrammi</a></li>
          <li><a href="#line_no">Numeri di riga</a></li>
          <li><a href="#letter">Lettere/Corrispondenza</a></li>
          <li><a href="#r_align">Testo allineato a destra</a></li>
        </ul></li>
        <li style="margin-top:.25em;"><font size="+1">Formattazione a Livello della Pagina:</font>
        <ul>
          <li><a href="#blank_pg">Pagina Vuota</a></li>
          <li><a href="#title_pg">Copertina Fronte/Retro</a></li>
          <li><a href="#toc">Tavola dei Contenuti/Sommario</a></li>
          <li><a href="#bk_index">Indici</a></li>
          <li><a href="#play_n">Opere teatrali: Nomi degli Attori/Note di Palcoscenico</a></li>
        </ul></li>
        <li><a href="#anything">Qualsiasi cosa che richieda un trattamento speciale o della quale non sei sicuro</a></li>
        <li><a href="#prev_notes">Note e Commenti dei Volontari Precedenti</a></li>
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
        <li style="margin-top:.25em;"><font size="+1">Problemi Comuni:</font>
        <ul>
          <li><a href="#bad_image">Immagine danneggiata</a></li>
          <li><a href="#bad_text">Immagine sbagliata rispetto al Testo </a></li>
          <li><a href="#round1">Errori del correttore o impaginatore precedente</a></li>
          <li><a href="#p_errors">Errori di stampa/Errori di ortografia (Refusi)</a></li>
          <li><a href="#f_errors">Errori sui fatti nei testi</a></li>
        </ul></li>
        <li><a href="#index">Indice alfabetico della guida</a></li>
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

<h3><a name="prime">La regola principale</a></h3>
<p><em>"Non modificare quello che ha scritto l'autore!"</em>
</p>
<p>Il libro elettronico finale visto dal lettore, anche di qui a molti anni
   a venire, dovrebbe comunicare con esattezza l'intenzione dell'autore. Se
   l'autore ha scritto parole in modo inusuale, le lasciamo scritte in quel
   modo. Se l'autore ha fatto affermazioni oltraggiose, razziste o discutibili,
   le lasciamo in quel modo. Se l'autore ha messo corsivo, grassetto o note a
   pi&egrave; pagina ogni tre parole, lasciamo il corsivo, il grassetto e le
   note a pi&egrave; pagina. Se qualcosa nel testo non corrisponde all'immagine
   della pagina originale, devi cambiare il testo affinch&eacute; corriponda.
   (Vedi <a href="#p_errors">Errori di stampa</a> per il trattamento corretto
   degli errori di stampa pi&ugrave; ovvi).
</p>
<p>Modifichiamo minime convenzioni tipografiche che non toccano il senso di
   ci&ograve; che l'autore ha scritto. Per esempio, spostiamo le didascalie
   delle illustrazioni, se necessario, in modo che si trovino solo
   tra un paragrafo e l'altro (<a href="#illust">Illustrazioni</a>).
   Modifiche di questo genere ci permettono di produrre una versione del libro
   <em>formattata in maniera coerente</em>. Le regole che seguiamo sono concepite
   per raggiungere questo risultato. Ti preghiamo di leggere attentamente il resto
   delle regole di formattazione tenendo presente questo concetto. Queste regole
   sono intese solamente per la formattazione. I correttori hanno fatto
   corrispondere il contenuto all'immagine, e ora in qualit&agrave; di
   impaginatore tu farai corrispondere l'aspetto dell'immagine.
</p>
<p>Per aiutare il successivo impaginatore e il post-correttore, manteniamo
   l'interruzione di riga (testo a capo). Ci&ograve; permette di confrontare
   facilmente le righe del testo con le righe dell'immagine.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Torna all'inizio</a></p>


<h3><a name="summary">La guida in breve</a></h3>
<p>La <a href="../formatting_summary.pdf">Guida in breve</a> &egrave; un documento
   di 2 pagine in versione stampabile (.pdf) che riassume i punti principali di
   queste Regole e d&agrave; degli esempi di come procedere alla formattazione.
   Gli impaginatori principianti sono invitati a stampare questo documento e
   tenerlo a portata di mano durante la formattazione.
</p>
<p>Potresti aver bisogno di scaricare ed installare un lettore di .pdf.
   Puoi averne uno gratuito da Adobe&reg;
   <a href="http://www.adobe.com/products/acrobat/readstep2.html">qui</a>.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Torna all'inizio</a></p>


<h3><a name="about">Riguardo a questo documento</a></h3>
<p>Questo documento &egrave; scritto per spiegare le regole di formattazione
   che usiamo per mantenere la coerenza nella formattazione di un unico libro
   che viene distribuito fra molti impaginatori, ognuno dei quali lavora
   contemporaneamente su pagine diverse. Questo aiuta tutti noi a formattare
   <em>allo stesso modo</em>, cosa che rende a sua volta pi&ugrave; facile per
   il compito del post-correttore che comporr&agrave; le varie pagine nel libro
   elettronico finale.
</p>
<p><i>Non &egrave; inteso come un manuale generale di editoria o tipografia</i>.
</p>
<p>Abbiamo incluso in queste regole alla formattazione tutti i quesiti che ci
   sono stati posti dai nuovi impaginatori. Esiste una guida separata di
   <a href="proofreading_guidelines.php">Regole di Correzione</a>.
   Se incontri una situazione che non viene contemplata da questa guida,
   &egrave; probabile che sia stata trattata nei turni di correzione,
   perci&ograve; non viene affrontata qui. Se non sei sicuro, chiedi nella
   <a href="#forums">Discussione del Progetto</a>.
</p>
<p>Se alcuni argomenti mancano, o pensi debbano
   essere affrontati diversamente, o se qualcosa &egrave; vago, faccelo sapere.
<?php if($site_url == "http://www.pgdp.net" || $site_url == "http://www.pgdp.org") { ?>
   Se c'&egrave; un termine in queste guide che non ti &egrave; familiare, leggi il
   <a href="http://www.pgdp.net/wiki/DP_Jargon">glossario nella Wiki</a>.
<?php } ?>
   Questo documento &egrave; un lavoro in corso. Aiutaci a migliorarlo suggerendo
   i cambiamenti nel Forum relativo alla Documentazione (Documentation Forum) in
   <a href="<?php echo $Guideline_discussion_URL; ?>">questo thread</a>.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Torna all'inizio</a></p>


<h3><a name="separate_pg">Ogni pagina &egrave; un'unit&agrave; singola </a></h3>
<p>Dal momento che ciascun progetto &egrave; distribuito tra molti impaginatori,
   ognuno dei quali lavora su pagine diverse, non c'&egrave; alcuna garanzia che
   tu possa vedere la pagina successiva del libro. Per questo motivo, assicurati
   di aprire e chiudere tutti i marcatori di formattazione su ogni pagina.
   Ci&ograve; faciliter&agrave; il lavoro del post-correttore al momento di riunire
   le varie pagine in un unico e-book.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Torna all'inizio</a></p>


<h3><a name="comments">Commenti al progetto (Project Comments)</a></h3>
<p>Quando selezioni un progetto per la formattazione, viene caricata la "Project
   Page" (Pagina del Progetto). In questa pagina c'&egrave; una sezione intitolata
   "Project Comments"(Commenti al Progetto) che contiene specifiche informazioni
   su questo progetto (libro). <b>Leggili prima di cominciare a formattare le pagine!</b>
   Se il "Project Manager" (Manager del Progetto) desidera che in questo libro si
   formatti qualcosa in modo diverso da come &egrave; specificato in queste regole, questo
   sar&agrave; scritto qui. Le istruzioni nei Commenti al progetto <em>prevalgono</em>
   sulle regole di questa guida, quindi segui quelle istruzioni. (Inoltre
   &egrave; qui che il Project Manager pu&ograve; darti
   qualche informazione interessante sull'autore o sul progetto.)
</p>
<p><em>Per favore, leggi anche il "Project Thread" (Forum del Progetto)</em>:
   il Project Manager potrebbe chiarire regole specifiche per il progetto
   proprio qui, e questo &egrave; il luogo usato dai correttori per indicare
   agli altri correttori problemi ricorrenti nel progetto e come possono essere
   affrontati meglio. (Vedi oltre).
</p>
<p>Nella Project Page (Pagina del progetto), il collegamento 'Images, Pages Proofread, &amp; Differences'
   (Immagini, Pagine corrette e Differenze) ti permette di vedere che tipo di
   cambiamenti sono stati effettuati dagli altri correttori.
   <a href="<?php echo $Using_project_details_URL; ?>">Questa discussione nel forum</a>
   riguarda modi diversi in cui usare queste informazioni.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Torna all'inizio</a></p>


<h3><a name="forums">Forum/Discuti il progetto (Discuss This Project)</a></h3>
<p>Nella Project Page (Pagina del progetto) dalla quale si accede alla formattazione
   delle pagine, nel campo "Forum", c'&egrave; un collegamento intitolato "Discuss
   this Project" (Discuti il Progetto, se la discussione &egrave; gi&agrave;
   cominciata) o "Start a discussion on this Project" (Comincia una discussione
   sul Progetto, se la discussione non &egrave; ancora cominciata). Cliccando questo
   collegamento arriverai ad una discussione nel forum dei progetti dedicata a questo
   progetto specifico. Questo &egrave; il luogo in cui fare domande sul libro, informare
   il Project Manager di possibili problemi, ecc. L'uso di questa discussione
   nel forum &egrave; il modo raccomandato per comunicare con il Project Manager e
   con gli altri volontari che stanno lavorando su questo libro.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Torna all'inizio</a></p>


<h3><a name="prev_pg">Correggere errori nelle pagine precedenti</a></h3>
<p>La <a href="#comments">Project Page</a> (Pagina del progetto) contiene collegamenti
   alle pagine del progetto su cui hai lavorato recentemente. (Se non hai ancora
   formattato pagine, non verranno mostrati collegamenti.)
</p>
<p>Le pagine elencate come "DONE" (SALVATE) o "IN PROGRESS" (IN CORSO) sono
   disponibili per modificare le correzioni fatte o finire la formattazione.
   Clicca semplicemente sul collegamento alla pagina. Cos&igrave;, se scopri
   di aver fatto un errore su una pagina o marcato qualcosa non correttamente,
   qui puoi cliccare su quella pagina e riaprirla per rimediare all'errore.
</p>
<p>Puoi anche usare i collegamenti "Images, Pages Proofread, &amp; Differences"
   (Immagini, pagine corrette e differenze) o "Just My Pages" (Solo le mie pagine)
   nella <a href="#comments">Project Page</a> (Pagina del progetto). Queste pagine
   avranno un collegamento "Edit" (Modifica) accanto alle pagine su cui hai lavorato
   nel turno corrente e che possono ancora essere modificate.
</p>
<p>Per informazioni pi&ugrave; dettagliate, fai riferimento a
   <a href="../prooffacehelp.php?i_type=0">Standard Proofreading Interface Help</a>
   (Aiuto per l'Interfaccia standard di correzione) oppure a
   <a href="../prooffacehelp.php?i_type=1">Enhanced Proofreading Interface Help</a>
   (Aiuto per l'Interfaccia avanzata di correzione), a seconda di quale
   interfaccia stai usando.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Torna all'inizio</a></p>


<table width="100%" border="0" cellspacing="0" cellpadding="6" summary="Character-level formatting">
  <tbody>
    <tr>
      <td bgcolor="silver"><h2 style="margin-bottom: 0; margin-top: 0;">Formattazione a Livello dei Caratteri:</h2></td>
    </tr>
  </tbody>
</table>

<h3><a name="inline">Inserimento dei marcatori di formattazione di riga</a></h3>
<p>La formattazione di riga &egrave; costituita da marcatori come
   <kbd>&lt;i&gt;</kbd>&nbsp;<kbd>&lt;/i&gt;</kbd>, <kbd>&lt;b&gt;</kbd>&nbsp;<kbd>&lt;/b&gt;</kbd>,
   <kbd>&lt;sc&gt;</kbd>&nbsp;<kbd>&lt;/sc&gt;</kbd>, <kbd>&lt;f&gt;</kbd>&nbsp;<kbd>&lt;/f&gt;</kbd>,
   o <kbd>&lt;g&gt;</kbd>&nbsp;<kbd>&lt;/g&gt;</kbd>.
   Metti la punteggiatura <b>all'esterno</b> delle targhette, a meno che i marcatori
   racchiudano un'intera frase o paragrafo, o la punteggiatura stessa non faccia
   parte della frase, titolo o abbreviazione che stai contrassegnando. Se la
   formattazione di riga prosegue per diversi paragrafi, contrassegna con le targhette
   ciascuno di essi.
</p>
<p>I punti che indicano un'abbreviazione nel titolo di un giornale, ad esempio <i>Phil. Trans.</i>
   fanno parte del titolo stesso e vanno inclusi nelle targhette, cos&igrave;:
   <kbd>&lt;i&gt;Phil. Trans.&lt;/i&gt;</kbd>.
</p>
<p>Nei vecchi libri spesso i numeri avevano lo stesso aspetto nel testo
   normale, in corsivo e in grassetto. Nelle date e frasi simili, contrassegna
   l'intera frase con un solo tipo di marcatore, invece di indicare le parole
   come corsivo (o grassetto) e i numeri come testo normale.
</p>
<p>Se trovi una serie/elenco di parole o frasi (ad esempio nomi, titoli, ecc.),
   contrassegna ogni voce dell'elenco singolarmente.
</p>
<p>Per quanto riguarda la marcatura nelle tabelle, vedi la sezione <a href="#tables">Tabelle</a>.
</p>
<!-- END RR -->
<p><b>Esempi</b>:
</p>
<table width="100%" align="center" border="1" cellpadding="4" cellspacing="0" summary="Inline markup Esempi">
  <tbody>
    <tr>
      <th valign="top" bgcolor="cornsilk">Immagine Originale:</th>
      <th valign="top" bgcolor="cornsilk">Testo formattato correttamente:</th>
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
<p class="backtotop"><a href="#top">Torna all'inizio</a></p>


<h3><a name="italics">Corsivo</a></h3>
<p>Formatta il testo in <i>corsivo</i> inserendo <kbd>&lt;i&gt;</kbd> all'inizio e
   <kbd>&lt;/i&gt;</kbd> alla fine del corsivo (fai attenzione alla "/" nella
   targhetta di chiusura).
</p>
<p>Vedi anche <a href="#inline">Inserimento dei Marcatori di Formattazione di Riga</a>.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Torna all'inizio</a></p>


<h3><a name="bold">Grassetto</a></h3>
<p>Formatta il testo in <b>grassetto</b> (stampato con un carattere pi&ugrave;
   marcato) inserendo <kbd>&lt;b&gt;</kbd> all'inizio e <kbd>&lt;/b&gt;</kbd> alla fine
   del grassetto (fai attenzione alla "/" nella targhetta di chiusura).
</p>
<p>Vedi anche <a href="#inline">Inserimento dei Marcatori di Formattazione di Riga</a> e
   <a href="#chap_head">Intestazioni dei Capitoli</a>.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Torna all'inizio</a></p>


<h3><a name="underl">Testo sottolineato</a></h3>
<p>Formatta il testo <u>sottolineato</u> come <a href="#italics">Corsivo</a>, con
   <kbd>&lt;i&gt;</kbd> e <kbd>&lt;/i&gt;</kbd> (fai attenzione alla "/" nella
   targhetta di chiusura). La sottolineatura era usata per enfatizzare il testo
   quando il tipografo non aveva la possibilit&agrave; di usare il corsivo,
   ad esempio in un documento dattiloscritto.
</p>
<p>Vedi anche <a href="#inline">Inserimento dei Marcatori di Formattazione di Riga</a>.
</p>
<p>Pu&ograve; succedere che il Project Manager chieda nei
   <a href="#comments">Project Comments</a> (Commenti al Progetto)
   di contrassegnare il testo sottolineato con <kbd>&lt;u&gt;</kbd> e <kbd>&lt;/u&gt;</kbd>.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Torna all'inizio</a></p>


<h3><a name="spaced"><span style="letter-spacing: .2em;">Testo spazieggiato</span> (gesperrt)</a></h3>
<p>Formatta il&nbsp; <span style="letter-spacing: .2em;">testo spazieggiato</span>&nbsp;
   inserendo <kbd>&lt;g&gt;</kbd> all'inizio e <kbd>&lt;/g&gt;</kbd> alla fine del testo stesso
   (fai attenzione alla "/" nella targhetta di chiusura).
   Elimina gli spazi in pi&ugrave; tra le lettere di ogni parola.
   Si tratta di una tecnica tipografica per enfatizzare il testo usata
   a volte nei libri antichi, soprattutto in tedesco.
</p>
<p>Vedi anche <a href="#inline">Inserimento dei Marcatori di Formattazione di Riga</a> e
   <a href="#chap_head">Intestazioni dei Capitoli</a>.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Torna all'inizio</a></p>


<h3><a name="font_ch">Cambiamenti di carattere (font) </a></h3>
<p>A volte il Project Manager richiede di contrassegnare un cambiamento di
   carattere (font) all'interno di una riga o paragrafo di testo normale inserendo
   <kbd>&lt;f&gt;</kbd> prima del testo con font diverso e <kbd>&lt;/f&gt;</kbd> dopo
   di esso (fai attenzione alla "/" nella targhetta di chiusura).
   Questo tipo di marcatura si usa per identificare un font particolare o altri
   tipi di formattazione che non possiedono una marcatura specifica (come il
   corsivo e il grassetto).
</p>
<p>Tra i possibili usi di questa marcatura ci sono:</p>
<ul compact>
  <li>caratteri in Antiqua (un font di tipo Roman) all'interno di testo in gotico (fraktur)</li>
  <li>testo in gotico all'interno di un font normale</li>
  <li>caratteri pi&ugrave; grandi o pi&ugrave; piccoli solo se si trovano
    <b>all'interno</b> di un paragrafo di testo normale (se l'intero
    paragrafo &egrave; in un font o in una dimensione diversa, vedi
    la sezione <a href="#block_qt">Citazioni in Blocco</a>)</li>
  <li>testo normale all'interno di un paragrafo in corsivo</li>
</ul>
<p>L'uso specifico di questa marcatura in un progetto &egrave; di norma esplicitamente
   richiesto nei <a href="#comments">Project Comments</a>. Se ti sembra necessario e
   non &egrave; stato ancora richiesto, chiedi nella
   <a href="#forums">Discussione sul Progetto</a>.
</p>
<p>Vedi anche <a href="#inline">Inserimento dei Marcatori di Formattazione di Riga</a>.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Torna all'inizio</a></p>


<h3><a name="small_caps">Maiuscoletto</a></h3>
<p>La formattazione &egrave; diversa per il <span style="font-variant:small-caps;">Maiuscoletto Misto</span>
   o per <span style="font-variant: small-caps;">tutte maiuscole</span>:
</p>
<p>Le parole stampate in <span style="font-variant: small-caps;">Maiuscoletto Misto</span>
   vanno scritte come normale testo Maiuscolo/minuscolo, le parole stampate in
   <span style="font-variant: small-caps;">tutte maiuscole</span> come MAIUSCOLO.
   In entrambi i casi, includi il testo nei marcatori <kbd>&lt;sc&gt;</kbd>
   e <kbd>&lt;/sc&gt;</kbd>.
</p>
<p>I titoli (<a href="#chap_head">Intestazione di Capitolo</a>,
   <a href="#sect_head">Intestazione di Sezione</a>, didascalie, ecc.) a volte
   sembrano in <span style="font-variant: small-caps;">maiuscoletto</span>,
   ma in genere si tratta di un cambiamento di <a href="#font_sz">dimensione del font</a>
   quindi non va contrassegnato. La <a href="#chap_head">parola iniziale di un capitolo</a>
   in maiuscoletto va invece cambiata in normale testo Maiuscolo/minuscolo senza le targhette.
</p>
<p>Vedi anche <a href="#inline">Inserimento dei Marcatori di Formattazione di Riga</a>.
</p>
<table width="100%" align="center" border="1" cellpadding="4" cellspacing="0" summary="Esempi di Maiuscoletto">
  <tbody>
    <tr>
      <th valign="top" bgcolor="cornsilk">Immagine Originale:</th>
      <th valign="top" bgcolor="cornsilk">Testo formattato correttamente:</th>
    </tr>
    <tr>
      <td valign="top"><span style="font-variant: small-caps;">Questo &egrave; Maiuscoletto</span></td>
      <td valign="top"><kbd>&lt;sc&gt;Questo &egrave; Maiuscoletto&lt;/sc&gt;</kbd></td>
    </tr>
    <tr>
      <td valign="top">You cannot be serious about <span style="font-variant: small-caps;">aardvarks</span>!</td>
      <td valign="top"><kbd>You cannot be serious about &lt;sc&gt;AARDVARKS&lt;/sc&gt;!</kbd></td>
    </tr>
  </tbody>
</table>
<!-- END RR -->
<p class="backtotop"><a href="#top">Torna all'inizio</a></p>


<h3><a name="word_caps">Parole in Tutte Maiuscole</a></h3>
<p>Formatta le parole in tutte maiuscole cos&igrave; come sono stampate.
</p>
<p>Fa eccezione la <a href="#chap_head">prima parola di un capitolo</a>,
   che in molti vecchi libri si trova in tutte maiuscole; va cambiata in normale
   Maiuscolo/minuscolo, quindi "C'ERA una volta," diventa "<kbd>C'era una volta,</kbd>".
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Torna all'inizio</a></p>


<h3><a name="font_sz">Cambiamento di misura del carattere</a></h3>
<p>Normalmente non segnaliamo in alcun modo un cambiamento di dimensione
  del carattere (font). Fa eccezione il caso in cui indichi una
   <a href="#block_qt">citazione in blocco</a> oppure il carattere cambi
   misura all'interno di una singola riga o paragrafo (vedi
   <a href="#font_ch">Cambiamenti di Carattere</a>).
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Torna all'inizio</a></p>


<h3><a name="extra_sp">Spazi aggiuntivi o Tabulazioni tra le parole</a></h3>
<p>Spazi aggiuntivi tra le parole sono frequenti come prodotto dell'OCR.
   Normalmente non devi preoccuparti di rimuoverli&mdash;pu&ograve; essere
   fatto automaticamente durante la post-correzione. In ogni caso, spazi
   aggiuntivi attorno alla punteggiatura, alle lineette, alle virgolette, ecc.,
   <b>devono</b> essere eliminati quando separano il segno dalla parola.
   Inoltre, assicurati di togliere tutti gli spazi di troppo all'interno
   della marcatura <kbd>/* */</kbd> che conserva la spaziatura, perch&eacute;
   non saranno eliminati automaticamente in seguito.
</p>
<p>Infine, se incontri caratteri di tabulazione nel testo, devi rimuoverli.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Torna all'inizio</a></p>


<!-- Remove this section after some transition, since it's now all handled in proofreading -->
<h3><a name="supers">Apici/Esponenti</a></h3>
<p>Nei vecchi libri spesso si abbreviavano le parole in contrazioni, e le si scriveva in
   apice. Formattale inserendo un singolo circonflesso (<kbd>^</kbd>) seguito dal
   testo in apice. Se l'apice &egrave; pi&ugrave; lungo di un carattere,
   allora delimita il testo con le parentesi graffe <kbd>{</kbd> e <kbd>}</kbd>. Per esempio:
</p>
<table width="100%" align="center" border="1" cellpadding="4" cellspacing="0" summary="Superscripts example">
  <tbody>
    <tr><th align="left" bgcolor="cornsilk">Immagine Originale:</th></tr>
    <tr>
      <td valign="top">Gen<sup>rl</sup> Washington defeated L<sup>d</sup> Cornwall's army.</td>
    </tr>
    <tr><th align="left" bgcolor="cornsilk">Testo formattato correttamente:</th></tr>
    <tr>
      <td valign="top"><kbd>Gen^{rl} Washington defeated L^d Cornwall's army.</kbd></td>
    </tr>
  </tbody>
</table>
<p>Se l'apice rappresenta un rimando di una nota a pi&egrave; pagina, vedi invece la
   sezione <a href="#footnotes">Note a pi&egrave; pagina</a>.
</p>
<p>Il Project Manager potrebbe richiedere nei <a href="#comments">Project Comments</a>
   (Commenti al progetto) che il testo in apice sia segnalato in modo diverso.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Torna all'inizio</a></p>


<!-- Remove this section after some transition, since it's now all handled in proofreading -->
<h3><a name="subscr">Pedici</a></h3>
<p>Il testo in pedice si trova spesso nelle opere scientifiche, ma non &egrave; comune in altri
   tipi di testo. Formatta il testo in pedice inserendo un trattino basso <kbd>_</kbd> e
   circonda il testo con parentesi graffe <kbd>{</kbd> e <kbd>}</kbd>. Per esempio:
</p>
<table width="100%" align="center" border="1" cellpadding="4" cellspacing="0" summary="Subscripts example">
  <tbody>
    <tr><th align="left" bgcolor="cornsilk">Immagine Originale:</th></tr>
    <tr>
      <td valign="top">H<sub>2</sub>O.</td>
    </tr>
    <tr><th align="left" bgcolor="cornsilk">Testo formattato correttamente:</th></tr>
    <tr>
      <td valign="top"><kbd>H_{2}O.</kbd></td>
    </tr>
  </tbody>
</table>
<!-- END RR -->
<p class="backtotop"><a href="#top">Torna all'inizio</a></p>


<h3><a name="page_ref">Riferimenti di pagina &quot;Vedi p. 123&quot;</a></h3>
<p>Formatta i riferimenti ai numeri di pagina, per esempio <kbd>(vedi p. 123)</kbd>,
   come appaiono nell'immagine.
</p>
<p>Controlla i <a href="#comments">Project Comments</a> (Commenti al progetto)
   per verificare se il Project Manager ha fatto richieste particolari per
   i riferimenti di pagina.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Torna all'inizio</a></p>


<table width="100%" border="0" cellspacing="0" cellpadding="6" summary="Paragraph-level formatting">
  <tbody>
    <tr>
      <td bgcolor="silver"><h2 style="margin-bottom: 0; margin-top: 0;">Formattazione a Livello dei Paragrafi:</h2></td>
    </tr>
  </tbody>
</table>


<h3><a name="chap_head">Intestazioni di capitolo</a></h3>
<p>Formatta le intestazioni dei capitoli come appaiono nell'immagine.
   Il titolo del capitolo si trova in genere un po' pi&ugrave; in basso
   nella pagina rispetto all'intestazione di pagina e non ha un numero
   sulla stessa riga. I titoli dei capitoli sono spesso stampati in maiuscolo;
   se &egrave; cos&igrave;, mantieni il maiuscolo. Contrassegna il testo in
   <a href="#italics">corsivo</a> o <a href="#small_caps">maiuscoletto</a>
   <b>misto</b> che appare nell'immagine.
</p>
<p>Inserisci 4 righe vuote prima di "CAPITOLO XXX", includendole
   anche se il capitolo comincia in una nuova pagina; non ci sono 'pagine'
   in un e-book, quindi le righe sono necessarie. Separa poi con una riga
   vuota ogni parte aggiuntiva dell'intestazione, come descrizioni del capitolo,
   citazioni d'apertura, ecc., e infine lascia due righe vuote prima dell'inizio
   del testo del capitolo.
</p>
<p>Spesso nei vecchi libri si trova la prima parola o le prime due di ogni
   capitolo in maiuscolo o maiuscoletto; cambiale in normale testo Maiuscolo/minuscolo
   (solo la prima lettera maiuscola).
</p>
<p>Le intestazioni di capitolo possono sembrare in grassetto o spazieggiate,
   ma ci&ograve; &egrave; in genere dovuto al tipo di carattere o a un cambiamento
   di misura del font e <b>non va segnalato</b>. Le righe vuote aggiuntive separano
   l'intestazione, quindi non indicare nemmeno il cambiamento di carattere.
   Vedi il primo esempio di seguito.
</p>
<!-- END RR -->

<table width="100%" align="center" border="1" cellpadding="4" cellspacing="0" summary="Chapter heading example">
 <tbody>
    <tr><th align="left" bgcolor="cornsilk">Immagine Originale:</th></tr>
    <tr align="left">
      <td width="100%" valign="top"> <img src="../chap1.png" alt="" width="500" height="725"><br>
      </td>
    </tr>
    <tr><th align="left" bgcolor="cornsilk">Testo formattato correttamente:</th></tr>
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
    <tr><th align="left" bgcolor="cornsilk">Immagine Originale:</th></tr>
    <tr align="left">
      <td width="100%" valign="top"><img src="../foot.png" alt="" width="500" height="860"><br>
      </td>
    </tr>
    <tr><th align="left" bgcolor="cornsilk">Testo formattato correttamente:</th></tr>
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
<p class="backtotop"><a href="#top">Torna all'inizio</a></p>


<h3><a name="sect_head">Intestazioni di sezione</a></h3>
<p>Alcuni libri presentano delle sezioni all'interno dei capitoli. Formatta le
   intestazioni di sezione come appaiono nell'immagine. Inserisci 2 righe vuote
   prima dell'intestazione e una dopo, a meno che il Project Manager non abbia
   chiesto di fare diversamente. Se non sei sicuro se un'intestazione indichi un
   capitolo o una sezione, chiedi nella <a href="#forums">Discussione sul Progetto</a>,
   citando il numero della pagina.
</p>
<p>Contrassegna il testo in <a href="#italics">corsivo</a> o
   <a href="#small_caps">maiuscoletto</a> <b>misto</b> che appare nell'immagine.
   Le intestazioni di sezione possono sembrare in grassetto o spazieggiate,
   ma ci&ograve; &egrave; in genere dovuto al tipo di carattere o a un cambiamento
   di misura del font e <b>non va segnalato</b>. Le righe vuote aggiuntive separano
   l'intestazione, quindi non indicare nemmeno il cambiamento di carattere.
</p>
<!-- END RR -->
<table width="100%" align="center" border="1" cellpadding="4" cellspacing="0" summary="Section Heading example">
  <tbody>
    <tr>
      <th align="left" bgcolor="cornsilk">Immagine Originale:</th>
    </tr>
    <tr align="left">
      <td width="100%" valign="top"> <img src="../section.png" alt="" width="500" height="283"> <br>
      </td>
    </tr>
    <tr>
      <th align="left" bgcolor="cornsilk">Testo formattato correttamente:</th>
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
<p class="backtotop"><a href="#top">Torna all'inizio</a></p>


<h3><a name="maj_div">Altre importanti suddivisioni nel testo</a></h3>
<p>Suddivisioni importanti del testo come Prefazione, Premessa,
   Tavola dei Contenuti, Introduzione, Prologo, Epilogo, Appendice,
   Riferimenti, Conclusione, Glossario, Sommario, Ringraziamenti,
   Bibliografia, ecc., vanno formattati allo stesso modo delle
   <a href="#chap_head">intestazioni di capitolo</a>,
   <i>cio&egrave;</i> inserendo 4 righe vuote prima dell'intestazione
   e due righe vuote prima dell'inizio del testo.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Torna all'inizio</a></p>


<!-- Remove this section after some transition, since it's now all handled in proofreading -->
<h3><a name="para_space">Spaziatura del paragrafo/Rientro</a></h3>
<p>Inserisci una riga vuota prima dell'inizio di un paragrafo, anche se comincia
   all'inizio di una pagina. Non devi far rientrare l'inizio del paragrafo,
   ma se &egrave; gi&agrave; rientrato, non ti preoccupare di rimuovere quegli
   spazi&mdash;pu&ograve; essere fatto automaticamente in post-correzione.
</p>
<p>Per un esempio vedi immagine e testo di <a href="#chap_head">Intestazioni dei capitoli</a>.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Torna all'inizio</a></p>


<h3><a name="extra_s">Pause di pensiero (Thought Breaks, Spazi aggiuntivi/Decorazioni tra paragrafi)</a></h3>
<p>Nell'immagine, la maggior parte dei paragrafi inizia sulla riga
   immediatamente successiva alla fine del precedente. A volte per&ograve;
   due paragrafi sono separati per indicare una "pausa di pensiero"
   (thought break). Questa interruzione si pu&ograve; presentare sotto forma
   di una riga di asterischi, lineette o altri caratteri, una linea
   orizzontale semplice o decorata, un piccolo disegno oppure semplicemente
   una riga o due vuote.
</p>
<p>Una pausa di pensiero pu&ograve; rappresentare un cambio di scena o di argomento, un intervallo di tempo o un attimo di
   suspense. &Egrave; un accorgimento voluto dall'autore, quindi lo conserviamo inserendo una riga vuota,
   <kbd>&lt;tb&gt;</kbd>, e un'altra riga vuota.
</p>
<p>I tipografi a volte usavano una linea decorativa per segnare la fine di <a href="#chap_head">capitoli</a>
   o <a href="#sect_head">sezioni</a>. Non si tratta di pause di pensiero, dunque
   <b>non</b> vanno segnalate con <kbd>&lt;tb&gt;</kbd>.
</p>
<p>Ricorda di controllare i <a href="#comments">Project Comments</a> perch&eacute; il Project Manager a volte
   richiede di includere informazioni aggiuntive nella marcatura delle pause di pensiero, ad esempio
   <kbd>&lt;tb stars&gt;</kbd> per una riga di asterischi.
</p>
<!-- END RR -->
<table width="100%" align="center" border="1" cellpadding="4" cellspacing="0" summary="Thought Break example">
  <tbody>
    <tr><th align="left" bgcolor="cornsilk">Immagine Originale:</th></tr>
    <tr align="left">
      <td width="100%" valign="top"> <img src="../tbreak.png" alt="" width="500" height="249"> <br>
      </td>
    </tr>
    <tr><th align="left" bgcolor="cornsilk">Testo formattato correttamente:</th></tr>
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
<p class="backtotop"><a href="#top">Torna all'inizio</a></p>


<h3><a name="illust">Illustrazioni</a></h3>
<p>Il testo di un'illustrazione va racchiuso nella marcatura
   <kbd>[Illustration:&nbsp;</kbd> e <kbd>]</kbd>, ponendo la didascalia tra i due
   punti e la <kbd>]</kbd> di chiusura. Formatta la didascalia come &egrave;
   stampata, conservando le interruzioni di riga, il corsivo, ecc. Il testo che
   potrebbe essere (parte di) una didascalia deve essere incluso, come per esempio
   "Vedi pagina 66" o un titolo all'interno dei margini dell'illustrazione.
</p>
<p>Se l'illustrazione &egrave; priva di didascalia, inserisci la targhetta
   <kbd>[Illustration]</kbd>. (In questo caso assicurati di togliere i due punti
   e lo spazio prima della <kbd>]</kbd> di chiusura.)
</p>
<p>Se l'illustrazione si trova nel mezzo o a lato di un paragrafo, sposta la
   targhetta prima o dopo il paragrafo lasciando una riga vuota per separarla.
   Ricongiungi il paragrafo eliminando eventuali righe vuote rimaste.
</p>
<p>Se non c'&egrave; alcuna interruzione di paragrafo nella pagina, aggiungi
   alla targhetta di illustrazione un <kbd>*</kbd> in questo modo <kbd>*[Illustration:
   <font color="red">(testo della didascalia)</font>]</kbd>,
   spostala all'inizio della pagina e lascia una riga vuota dopo di essa.
</p>
<!-- END RR -->

<table width="100%" align="center" border="1" cellpadding="4" cellspacing="0" summary="Illustration example">
  <tbody>
    <tr>
      <th align="left" bgcolor="cornsilk">Immagine Originale:</th>
    </tr>
    <tr align="left">
      <td width="100%" valign="top"> <img src="../illust.png" alt="" width="500" height="525"> <br>
      </td>
    </tr>
    <tr>
      <th align="left" bgcolor="cornsilk">Testo formattato correttamente:</th>
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
      <th align="left" bgcolor="cornsilk">Immagine Originale: (Illustration in mezzo al paragrafo)</th>
    </tr>
    <tr align="left">
      <td width="100%" valign="top"> <img src="../illust2.png" alt="" width="500" height="514"> <br>
      </td>
    </tr>
    <tr>
      <th align="left" bgcolor="cornsilk">Testo formattato correttamente:</th>
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
<p class="backtotop"><a href="#top">Torna all'inizio</a></p>


<h3><a name="footnotes">Note a pi&egrave; di pagina/Note di chiusura</a></h3>
<p>Tratta le note a pi&egrave; di pagina lasciando il testo della nota
   alla fine della pagina e mettendo un rimando dove viene fatto riferimento nel testo.
   In altre parole:
</p>
<p>1. Nel testo principale, i caratteri che indicano la posizione di una nota
   devono essere circondati da parentesi quadre (<kbd>[</kbd> e <kbd>]</kbd>) e
   inseriti vicino alla parola annotata<kbd>[1]</kbd> o al suo segno di
   punteggiatura,<kbd>[2]</kbd> come mostrato nell'immagine e nei due esempi di
   questa frase. I rimandi delle note a pi&egrave; di pagina possono essere
   numeri, lettere o simboli. Quando le note sono segnalate con un simbolo o
   una serie di simboli (*, &dagger;, &Dagger;, &sect;, ecc.) li sostituiamo
   con lettere maiuscole in ordine (A, B, C, ecc.).
</p>
<p>2. In fondo alla pagina, la nota va racchiusa nella marcatura
   <kbd>[Footnote #:&nbsp;</kbd> e <kbd>]</kbd>, ponendo il testo tra i due punti
   e la <kbd>]</kbd> di chiusura, e il numero o lettera di rimando al posto del #
   nella marcatura. Formatta il testo della nota come &egrave; stampato,
   conservando le interruzioni di riga, il corsivo, ecc. Assicurati che il
   rimando usato nella nota sia lo stesso presente nel testo annotato.
   Metti ciascuna nota su una riga a s&eacute; nell'ordine in cui appare,
   ognuna separata da una riga vuota.
</p>
<!-- END RR -->

<p>Se una nota a pi&egrave; di pagina prosegue nella pagina successiva,
   lasciala dov'&egrave; in fondo alla pagina e aggiungi un asterisco <kbd>*</kbd>
   dove si interrompe, cos&igrave;: <kbd>[Footnote 1:
   <font color="red">(testo della nota)</font>]*</kbd>.
   L'asterisco <kbd>*</kbd> richiamer&agrave; l'attenzione del post-correttore,
   che provveder&agrave; a riunire le parti della nota.
</p>
<p>Se una nota prosegue dalla pagina precedente, lasciala a fondo pagina e
   racchiudila con <kbd>*[Footnote: <font color="red">(testo della nota)</font>]</kbd>
   (senza numero o simbolo). L'asterisco <kbd>*</kbd> richiamer&agrave; l'attenzione
   del post-correttore, che provveder&agrave; a riunire le parti della nota.
</p>
<p>Se una nota che prosegue finisce o inizia con una parola divisa,
   contrassegna <b>sia</b> la nota <b>sia</b> la parola con <kbd>*</kbd>, cos&igrave;:<br>
   <kbd>[Footnote 1: Questa nota continua e anche l'ultima parola con-*]*</kbd><br>
   per la parte iniziale, e<br> <kbd>*[Footnote: *tinua nella pagina seguente.]</kbd>
   per la parte successiva.
</p>
<p>Non includere le righe orizzontali che separano le note a pi&egrave;
   pagina dal testo principale.
</p>
<p>Le <b>Note di chiusura</b> sono note che sono state raggruppate assieme
   alla fine di un capitolo o alla fine di un libro, invece che in fondo ad
   ogni pagina. Sono formattate nello stesso modo delle note a pi&egrave;
   di pagina. Dove trovi un riferimento ad una nota di chiusura nel testo,
   semplicemente circondala con <kbd>[</kbd> e <kbd>]</kbd>. Se stai correggendo
   la pagina con le note di chiusura, circonda il testo di ognuna con
   <kbd>[Footnote #: <font color="red">(testo della nota)</font>]</kbd>, con
   la nota scritta all'interno, e il numero o la lettera della nota di chiusura
   al posto del #. Inserisci una riga vuota prima di ogni nota di chiusura
   in modo che rimangano come paragrafi separati quando il testo &egrave;
   rimpaginato durante la post-correzione.
</p>
<!-- Need an example of Endnotes, maybe? Good idea!-->

<p>Le note nelle <a href="#tables">Tabelle</a> devono restare dove sono nell'immagine originale.
</p>

<table width="100%" border="1" cellpadding="4" cellspacing="0" align="center" summary="Footnote Example">
  <tbody>
    <tr>
      <th valign="top" align="left" bgcolor="cornsilk">Immagine Originale:</th>
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
    <tr><th align="left" bgcolor="cornsilk">Testo formattato correttamente:</th></tr>
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
    <tr><th align="left" bgcolor="cornsilk">Originale con Nota a pi&egrave; di pagina in una Poesia:</th></tr>
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
    <tr><th align="left" bgcolor="cornsilk">Testo formattato correttamente:</th></tr>
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
<p class="backtotop"><a href="#top">Torna all'inizio</a></p>


<h3><a name="para_side">Note a margine del paragrafo (Sidenotes)</a></h3>
<p>Alcuni libri presentano brevi descrizioni del paragrafo lungo il margine
   del testo, dette note a margine (sidenotes). Sposta le note a margine
   appena prima del paragrafo cui si riferiscono. Ogni nota va racchusa
   nella marcatura <kbd>[Sidenote:&nbsp;</kbd> e <kbd>]</kbd>, ponendo il testo
   dopo i due punti e prima della <kbd>]</kbd> di chiusura. Formatta il testo
   della nota come &egrave; stampato, conservando le interruzioni di riga,
   il corsivo, ecc. (allo stesso tempo tratta normalmente le lineette e i
   trattini a fine riga). Lascia una riga vuota prima e dopo la nota per
   separarla dal testo normale.
</p>
<p>Se ci sono diverse note a margine per lo stesso paragrafo, disponile una
   dopo l'altra all'inizio del paragrafo, separandole una dall'altra con
   una riga vuota.
</p>
<p>Se il paragrafo &egrave; iniziato nella pagina precedente, poni la nota
   a margine a inizio pagina aggiungendo un <kbd>*</kbd> in modo che il
   post-correttore capisca che appartiene alla pagina precedente, cos&igrave;:
   <kbd>*[Sidenote: <font color="red">(testo della nota a margine)</font>]</kbd>. Il
   post-correttore la sposter&agrave; nella posizione pi&ugrave; appropriata.
</p>
<p>A volte il Project Manager pu&ograve; richiedere che le note a margine
   siano poste accanto alla frase cui si riferiscono, invece che all'inizio o
   alla fine del paragrafo. In questo caso non separarle con righe vuote.
</p>
<!-- END RR -->

<table width="100%" align="center" border="1" cellpadding="4" cellspacing="0" summary="Sidenotes example">
  <tbody>
    <tr valign="top">
      <th align="left" bgcolor="cornsilk">Immagine Originale:</th>
    </tr>
    <tr valign="top">
      <td width="100%" align="left"><img src="../side.png" alt="" width="550" height="800"><br>
      </td>
    </tr>
    <tr valign="top">
      <th align="left" bgcolor="cornsilk">Testo formattato correttamente:</th>
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
<p class="backtotop"><a href="#top">Torna all'inizio</a></p>


<h3><a name="outofline">Inserimento dei Marcatori di Formattazione di Paragrafo</a></h3>
<p>La formattazione di paragrafo &egrave; costituita dalle targhette di marcatura
   <kbd>/#</kbd> <kbd>#/</kbd> e <kbd>/*</kbd> <kbd>*/</kbd>. La marcatura "di rimpaginazione"
   <kbd>/#</kbd> <kbd>#/</kbd> indica una sezione di testo stampato in maniera diversa,
   ma che pu&ograve; comunque essere rimpaginato durante la post-correzione.
   La marcatura di "non rimpaginazione" <kbd>/*</kbd> <kbd>*/</kbd> indica una sezione di
   testo che non va rimpaginata in fase di post-correzione&mdash;nella quale le
   interruzioni di riga, i rientri e la spaziatura devono essere mantenuti.
</p>
<p>Se usi una targhetta di apertura, assicurati di mettere anche quella di chiusura
   sulla stessa pagina. Una volta che il testo sar&agrave; rimpaginato in post-correzione,
   ogni marcatore verr&agrave; eliminato insieme all'intera riga su cui si trova.
   Per questo motivo, lascia una riga vuota tra il testo normale e la targhetta di
   apertura, e allo stesso modo lascia una riga vuota tra la targhetta
   di chiusura e la ripresa del testo normale.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Torna all'inizio</a></p>


<h3><a name="block_qt">Citazioni in blocco</a></h3>
<p>Le citazioni in blocco sono blocchi di testo (generalmente diverse righe e
   talvolta diverse pagine) che si distinguono dal testo circostante per
   mezzo di margini pi&ugrave; larghi, carattere pi&ugrave; piccolo, rientri
   diversi o altro. Racchiudi le citazioni in blocco nella marcatura
   <kbd>/#</kbd> e <kbd>#/</kbd>. Per ulteriori dettagli su questa marcatura vedi
   <a href="#outofline">Inserimento dei Marcatori di Formattazione di Paragrafo</a>.
</p>
<p>A parte l'inserimento dei marcatori, le citazioni devono essere formattate
   come il testo ordinario.
</p>
<!-- END RR -->

<table width="100%" align="center" border="1" cellpadding="4" cellspacing="0" summary="Block Quotation example">
  <tbody>
    <tr><th align="left" bgcolor="cornsilk">Immagine Originale:</th></tr>
    <tr align="left">
      <td width="100%" valign="top"> <img src="../bquote.png" alt="" width="500" height="475"><br>
      </td>
    </tr>
    <tr><th align="left" bgcolor="cornsilk">Testo formattato correttamente:</th></tr>
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
<p class="backtotop"><a href="#top">Torna all'inizio</a></p>


<h3><a name="lists">Elenchi</a></h3>
<p>Racchiudi gli elenchi all'interno dei marcatori <kbd>/*</kbd> e <kbd>*/</kbd>.
   Per ulteriori dettagli su questa marcatura vedi
   <a href="#outofline">Inserimento dei Marcatori di Formattazione di Paragrafo</a>.
</p>
<!-- END RR -->

<table width="100%" align="center" border="1" cellpadding="4" cellspacing="0" summary="List example">
  <tbody>
    <tr><th align="left" bgcolor="cornsilk">Immagine Originale:</th></tr>
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
    <tr><th align="left" bgcolor="cornsilk">Testo formattato correttamente:</th></tr>
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
<p class="backtotop"><a href="#top">Torna all'inizio</a></p>


<h3><a name="tables">Tabelle</a></h3>
<p>Racchiudi le tabelle all'interno dei marcatori <kbd>/*</kbd> e <kbd>*/</kbd>.
   Per ulteriori dettagli su questa marcatura vedi <a href="#outofline">Inserimento
   dei Marcatori di Formattazione di Paragrafo</a>. Formatta la tabella usando spazi
   (<b>non tabulazioni</b>) in modo che rassomigli all'originale.
   Cerca di non superare la larghezza di 75 caratteri; generalmente &egrave;
   preferibile una larghezza inferiore ai 75 caratteri.
</p>
<p>Non usare tabulazioni per formattare le tabelle&mdash;usa solo spazi.
   Le tabulazioni risultano allineate differentemente su computer diversi,
   e il tuo accurato lavoro di formattazione non verr&agrave; visualizzato
   allo stesso modo. Rimuovi qualsiasi punto o carattere usato per allineare
   gli elementi della tabella.
</p>
<p>Se nella tabella va inserita formattazione di riga (corsivo, grassetto, ecc.),
   contrassegna ogni cella separatamente. Nell'allineare il testo, tieni presente che
   la marcatura di riga avr&agrave; un aspetto diverso nella versione finale di testo.
   Ad esempio, i <kbd>&lt;i&gt;</kbd>marcatori del corsivo<kbd>&lt;/i&gt;</kbd>
   normalmente diventano <kbd>_</kbd>trattini bassi<kbd>_</kbd>, e la maggior parte
   degli altri marcatori sar&agrave; trattata in modo analogo. Invece la
   <kbd>&lt;sc&gt;</kbd>Marcatura del Maiuscoletto<kbd>&lt;/sc&gt;</kbd> sar&agrave;
   eliminata completamente.
</p>
<p>&Egrave; spesso difficile formattare le tabelle in formato testo: fai del tuo meglio.
   &Egrave; consigliabile usare un font mono-spazio, come
   <a href="../font_sample.php">DPCustomMono</a> o Courier.
   Ricorda che il tuo scopo &egrave; preservare l'intento dell'Autore, producendo
   al contempo una tabella leggibile in un e-book. A volte questo significa
   sacrificare il formato originale della tabella stampata nella pagina. Controlla i
   <a href="#comments">Project Comments</a> (Commenti al progetto) e la Discussione
   sul progetto, perch&eacute; altri volontari potrebbero essersi accordati su un
   formato particolare. Se non trovi nulla, puoi trovare qualche consiglio utile
   nella discussione <a href="<?php echo $Gallery_of_Table_Layouts_URL; ?>">Gallery of Table Layouts</a>
   (Esempi di formattazione di tabelle).
</p>
<p>Le <b>Note a pi&egrave; di pagina</b> nelle tabelle vanno lasciate dove sono
   nell'immagine. Per ulteriori dettagli vedi  <a href="#footnotes">Note a pi&egrave;
   di pagina</a>.
</p>
<!-- END RR -->
<table width="100%" align="center" border="1" cellpadding="4" cellspacing="0" summary="Table Example">
  <tbody>
    <tr><th align="left" bgcolor="cornsilk">Immagine Originale:</th></tr>
    <tr align="left">
      <td width="100%" valign="top"> <img src="../table2.png" alt="" width="500" height="304"><br>
      </td>
    </tr>
    <tr><th align="left" bgcolor="cornsilk">Testo formattato correttamente:</th></tr>
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
    <tr><th align="left" bgcolor="cornsilk">Immagine Originale:</th></tr>
    <tr align="left">
      <td width="100%" valign="top"> <img src="../table3.png" alt="" width="480" height="231"><br>
      </td>
    </tr>
    <tr><th align="left" bgcolor="cornsilk">Testo formattato correttamente:</th></tr>
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
<p class="backtotop"><a href="#top">Torna all'inizio</a></p>


<h3><a name="poetry">Poesia/Epigrammi</a></h3>
<p>Indica poesia ed epigrammi con <kbd>/*</kbd> e <kbd>*/</kbd> in modo da preservare
   le interruzioni di riga e la spaziatura. Per ulteriori dettagli su questa marcatura
   vedi <a href="#outofline">Inserimento dei Marcatori di Formattazione di Paragrafo</a>.
</p>
<p>Mantieni il rientro relativo dei singoli versi della poesia o epigramma
   aggiungendo 2, 4, 6 (o pi&ugrave;) spazi all'inizio delle righe con il
   rientro per renderle simili all'originale. Se l'intera poesia &egrave;
   al centro dell'immagine originale, non preoccuparti di centrare il testo
   quando lo formatti. Sposta tutto al margine sinistro mantenendo i rientri
   relativi dei versi.
</p>
<p>Quando un verso &egrave; troppo lungo per la larghezza della pagina, in
   molti libri continua sulla riga seguente con un ampio rientro. In questo caso
   ricongiungi il verso sulla riga superiore. Le righe che proseguono in genere
   cominciano con la lettera minuscola. Compaiono a intervalli irregolari,
   a differenza dei rientri normali che si presentano regolarmente
   nella metrica della poesia.
</p>
<p>Se trovi una riga di puntini in una poesia, trattala come un
   <a href="#extra_s">thought break</a> (pausa di pensiero).
</p>
<p>I <a href="#line_no">Numeri di riga</a> nelle poesie devono essere mantenuti.
</p>
<p>Controlla i <a href="#comments">Project Comments</a> del progetto che
   stai formattando. I Project Manager spesso fanno richieste particolari
   per i libri di poesia. Molte volte non sar&agrave; necessario seguire
   tutte le regole di formattazione descritte, soprattutto nei libri
   costituiti in gran parte o completamente da testo poetico.
</p>
<!-- END RR -->

<table width="100%" align="center" border="1" cellpadding="4" cellspacing="0" summary="Poetry Example">
  <tbody>
    <tr><th align="left" bgcolor="cornsilk">Immagine Originale:</th></tr>
    <tr align="left">
      <td width="100%" valign="top"> <img src="../poetry.png" alt="" width="500" height="508"> <br>
      </td>
    </tr>
    <tr><th align="left" bgcolor="cornsilk">Testo formattato correttamente:</th></tr>
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
<p class="backtotop"><a href="#top">Torna all'inizio</a></p>


<h3><a name="line_no">Numeri di riga</a></h3>
<p>I numeri di riga sono comuni in poesia, e solitamente appaiono vicino al margine
   ogni quinta o decima riga.
   Mantieni i numeri di riga, ponendoli ad almeno sei spazi sulla destra della
   riga, anche se nell'originale sono a sinistra.
   Dal momento che la poesia non viene rimpaginata nella versione
   elettronica del testo, i numeri di riga saranno utili ai lettori.
</p>
<!-- END RR -->
<!-- We need an example image and text for this. -->
<p class="backtotop"><a href="#top">Torna all'inizio</a></p>


<h3><a name="letter">Lettere/Corrispondenza</a></h3>
<p>Formatta lettere e corrispondenza come normali <a href="#para_space">paragrafi</a>.
   Inserisci una riga vuota prima dell'inizio della lettera; non riprodurre
   eventuali rientri.
</p>
<p>Racchiudi righe consecutive di intestazione o pi&egrave; di pagina
   (come indirizzi, date, saluti o firme) nei marcatori <kbd>/*</kbd> e <kbd>*/</kbd>.
   Per ulteriori dettagli su questa marcatura vedi
   <a href="#outofline">Inserimento dei Marcatori di Formattazione di Paragrafo</a>.
</p>
<p>Non far rientrare le righe di intestazione o pi&egrave; di pagina, nemmeno
   se sono rientrate o allineate a destra nell'immagine&mdash;mettile
   sul margine sinistro. Il post-correttore provveder&agrave; a formattarle
   nella maniera pi&ugrave; appropriata.
</p>
<p>Se la corrispondenza &egrave; stampata in modo diverso dal testo principale,
   vedi <a href="#block_qt">Citazioni in blocco</a>.
</p>
<!-- END RR -->

<table width="100%" align="center" border="1" cellpadding="4" cellspacing="0" summary="Letter Example">
  <tbody>
    <tr><th align="left" bgcolor="cornsilk">Immagine Originale:</th></tr>
    <tr align="left">
      <td width="100%" valign="top"> <img src="../letter.png" alt="" width="500" height="217"> <br>
      </td>
    </tr>
    <tr><th align="left" bgcolor="cornsilk">Testo formattato correttamente:</th></tr>
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
    <tr><th align="left" bgcolor="cornsilk">Immagine Originale:</th></tr>
    <tr align="left">
      <td width="100%" valign="top"> <img src="../letter2.png" alt="" width="500" height="271"> <br>
      </td>
    </tr>
    <tr><th align="left" bgcolor="cornsilk">Testo formattato correttamente:</th></tr>
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
<p class="backtotop"><a href="#top">Torna all'inizio</a></p>


<h3><a name="r_align">Testo allineato a destra</a></h3>
<p>Racchiudi righe di testo allineato a destra nei marcatori <kbd>/*</kbd> e <kbd>*/</kbd>.
   Per ulteriori dettagli su questa marcatura vedi
   <a href="#outofline">Inserimento dei Marcatori di Formattazione di Paragrafo</a>
   e la sezione <a href="#letter">Lettere/Corrispondenza</a> per degli esempi.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Torna all'inizio</a></p>


<table width="100%" border="0" cellspacing="0" cellpadding="6" summary="Page-level formatting">
  <tbody>
    <tr>
      <td bgcolor="silver"><h2 style="margin-bottom: 0; margin-top: 0;">Formattazione a Livello della Pagina</h2></td>
    </tr>
  </tbody>
</table>


<h3><a name="blank_pg">Pagina vuota</a></h3>
<p>Inserisci <kbd>[Blank Page]</kbd> se sia il box di testo sia l'immagine
   sono vuoti.
</p>
<p>Se c'&egrave; del testo nell'area di formattazione e un'immagine vuota,
   o se c'&egrave; il testo nell'immagine, ma non nella finestra, segui le
   istruzioni per <a href="#bad_image">Immagine danneggiata</a>
   o <a href="#bad_text">Testo danneggiato</a>.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Torna all'inizio</a></p>


<h3><a name="title_pg">Copertina Fronte/Retro</a></h3>
<p>Formatta il testo come &egrave; stampato sulla pagina, sia tutto maiuscolo,
   sia tutto maiuscolo, maiuscolo e minuscolo, ecc., includendo l'anno di
   pubblicazione o il copyright.
</p>
<p>Nei libri pi&ugrave; antichi spesso la prima lettera &egrave; grande e
   decorata&mdash;formattala come lettera normale.
</p>
<!-- END RR -->

<table width="100%" align="center" border="1" cellpadding="4" cellspacing="0" summary="Title Page Example">
  <tbody>
    <tr>
      <th align="left" bgcolor="cornsilk">Immagine Originale:</th>
    </tr>
    <tr align="left">
      <td width="100%" valign="top"><img src="../title.png" width="500" height="520" alt=""><br>
      </td>
    </tr>
    <tr>
      <th align="left" bgcolor="cornsilk">Testo formattato correttamente:</th>
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
<p class="backtotop"><a href="#top">Torna all'inizio</a></p>


<h3><a name="toc">Tavola dei Contenuti/Sommario</a></h3>
<p>Formatta il Sommario come stampato nel libro, sia tutto maiuscolo,
   sia maiuscolo e minuscolo, ecc., e racchiudilo tra <kbd>/*</kbd> e <kbd>*/</kbd>.
   Per ulteriori dettagli su questa marcatura vedi
   <a href="#outofline">Inserimento dei Marcatori di Formattazione di Paragrafo</a>.
</p>
<p>I riferimenti ai numeri di pagina vanno posti <b>ad almeno sei spazi</b>
   di distanza dal testo. Elimina eventuali punti o altri caratteri usati
   per allinearli.
</p>
<!-- END RR -->

<table width="100%" align="center" border="1" cellpadding="4" cellspacing="0" summary="Table of Contents example">
  <tbody>
    <tr>
      <th align="left" bgcolor="cornsilk">Immagine Originale:</th>
    </tr>
    <tr align="left">
      <td width="100%" valign="top"><img src="../tablec.png" alt="" width="500" height="650"></td>
    </tr>
    <tr>
      <th align="left" bgcolor="cornsilk">Testo formattato correttamente:</th>
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
<p class="backtotop"><a href="#top">Torna all'inizio</a></p>


<h3><a name="bk_index">Indici</a></h3>
<p>Racchiudi l'indice nella marcatura <kbd>/*</kbd> e <kbd>*/</kbd>.
   Per ulteriori dettagli su questa marcatura vedi
   <a href="#outofline">Inserimento dei Marcatori di Formattazione di Paragrafo</a>.
   Non c'&egrave; bisogno di allineare i numeri come appaiono nell'immagine;
   basta mettere una virgola seguita dal numero di pagina.
</p>
<p>Gli indici sono spesso stampati su due colonne; lo spazio ridotto pu&ograve;
   far s&igrave; che le voci dell'indice siano divise su pi&ugrave; righe.
   Ricongiungi le righe spezzate su un'unica riga.
   In questo modo spesso si creano righe molto lunghe, che saranno
   comunque riportate alla giusta lunghezza e rientro in fase di post-correzione.
</p>
<p>Inserisci una riga vuota prima di ogni voce dell'indice.
   Se ci sono voci secondarie (spesso separate da punto e virgola <kbd>;</kbd>),
   metti ciascuna di esse su una nuova riga, rientrata di due spazi.
</p>
<p>Tratta ogni nuova sezione di un indice (A, B, C...) come una
   <a href="#sect_head">intestazione di sezione</a> inserendo 2
   righe vuote prima di essa.
</p>
<p>Nei vecchi libri spesso la prima parola di ogni sezione dell'indice &egrave;
   stampata in tutto maiuscolo o maiuscoletto; cambiala in modo che corrisponda
   al resto delle voci dell'indice.
</p>
<p>Controlla i <a href="#comments">Project Comments</a> (Commenti al progetto)
   perch&eacute; il Project Manager potrebbe richiedere una formattazione diversa,
   ad esempio che l'indice sia trattato come un <a href="#toc">Sommario</a>.
</p>
<!-- END RR -->

<table width="100%" align="center" border="1" cellpadding="4" cellspacing="0" summary="Rejoining Index Lines">
  <tbody>
    <tr>
      <th align="left" bgcolor="cornsilk">Immagine Originale:</th>
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
      <th align="left" bgcolor="cornsilk">Testo formattato correttamente:</th>
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
    <tr><th align="left" bgcolor="cornsilk">Immagine Originale:</th></tr>
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
      <th align="left" bgcolor="cornsilk">Testo formattato correttamente:</th>
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
    <tr><th align="left" bgcolor="cornsilk">Immagine Originale:</th></tr>
    <tr align="left">
      <td valign="top"> <img src="../index.png" alt="" width="438" height="355"></td>
    </tr>
    <tr><th align="left" bgcolor="cornsilk">Testo formattato correttamente:</th></tr>
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
<p class="backtotop"><a href="#top">Torna all'inizio</a></p>


<h3><a name="play_n">Opere teatrali: Nomi degli Attori/Note di Palcoscenico</a></h3>
<p>Per tutte le opere teatrali:</p>
<ul compact>
 <li>Formatta l'elenco dei personaggi (Dramatis Person&aelig;) come un
     <a href="#lists">elenco</a>.</li>
 <li>Tratta ogni nuovo atto come una <a href="#chap_head">intestazione di capitolo</a>
     inserendo 4 righe vuote prima di esso e due dopo.</li>
 <li>Tratta ogni nuova scena come una <a href="#sect_head">intestazione di sezione</a>
     inserendo 2 righe vuote prima di essa.</li>
 <li>Nei dialoghi, tratta un cambio di personaggio come un nuovo paragrafo, con una
     riga vuota prima. Se il nome del personaggio &egrave; su una riga a parte,
     trattalo analogamente come un paragrafo separato.</li>
 <li>Formatta i nomi degli attori come appaiono nell'immagine, mantenendo
     <a href="#italics">corsivo</a>, <a href="#bold">grassetto</a>, o tutte maiuscole.</li>
 <li>Le note di palcoscenico vanno formattate come appaiono nell'immagine.
     Se la nota &egrave; su una riga a s&eacute;, lasciala cos&igrave;;
     se &egrave; al termine di una riga di dialogo, lasciala cos&igrave;;
     se &egrave; allineata a destra al termine di una riga di dialogo,
     lascia almeno sei spazi tra il dialogo e la nota.<br>
     Spesso le note di palcoscenico cominciano con una parentesi d'apertura
     ma omettono quella di chiusura. Manteniamo questa convenzione:
     non chiudere la parentesi. La marcatura del corsivo &egrave;
     in genere posta all'interno delle parentesi.</li>
</ul>
<p>Per le opere con metrica (scritte in poesia):</p>
<ul compact>
 <li>Molte opere sono in metrica e come la poesia non devono venire rimpaginate.
     Racchiudi il testo in metrica nella marcatura <kbd>/*</kbd> e <kbd>*/</kbd>
     come per la <a href="#poetry">poesia</a>. Se le note di palcoscenico
     sono su una riga a s&eacute;, non includerle nella marcatura
     <kbd>/*</kbd> e <kbd>*/</kbd>.
     (Poich&eacute; le note di palcoscenico non sono in rima, e possono essere
     tranquillamente rimpaginate in fase di post-correzione, non devono essere
     inserite nei marcatori /* */ che proteggono il dialogo in metrica.)</li>
 <li>Mantieni i rientri relativi nei dialoghi come in <a href="#poetry">poesia</a>.</li>
 <li>Ricongiungi le righe metriche divise per mancanza di spazio sulla pagina,
     come in <a href="#poetry">poesia</a>. Se la continuazione &egrave; formata
     da una sola parola o due, spesso viene posta sulla riga superiore o inferiore
     dopo una ( , invece di stare su una riga a s&eacute;.
     Vedi l'<a href="#play4">esempio</a>.</li>
</ul>
<p>Per favore controlla i <a href="#comments">Project Comments</a> (Commenti al Progetto),
   poich&eacute; il Project Manager potrebbe richiedere una formattazione particolare.
</p>
<!-- END RR -->

<table width="100%" align="center" border="1" cellpadding="4" cellspacing="0" summary="Play Example 1">
  <tbody>
    <tr>
      <th align="left" bgcolor="cornsilk">Immagine Originale:</th>
    </tr>
    <tr align="left">
      <td width="100%" valign="top"><img src="../play1.png" width="500" height="430" alt=""><br>
      </td>
    </tr>
    <tr>
      <th align="left" bgcolor="cornsilk">Testo formattato correttamente:</th>
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
      <th align="left" bgcolor="cornsilk">Immagine Originale:</th>
    </tr>
    <tr align="left">
      <td width="100%" valign="top"><img src="../play2.png" width="500" height="680" alt=""><br>
      </td>
    </tr>
    <tr>
      <th align="left" bgcolor="cornsilk">Testo formattato correttamente:</th>
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
      <th align="left" bgcolor="cornsilk">Immagine Originale:</th>
    </tr>
    <tr align="left">
      <td width="100%" valign="top"><img src="../play3.png" width="504" height="206" alt=""><br>
      </td>
    </tr>
    <tr>
      <th align="left" bgcolor="cornsilk">Testo formattato correttamente:</th>
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
      <th align="left" bgcolor="cornsilk">Immagine Originale:</th>
    </tr>
    <tr align="left">
      <td width="100%" valign="top"><img src="../play4.png" width="502" height="98" alt=""><br>
      </td>
    </tr>
    <tr>
      <th align="left" bgcolor="cornsilk">Testo formattato correttamente:</th>
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
<p class="backtotop"><a href="#top">Torna all'inizio</a></p>


<h3><a name="anything">Qualsiasi cosa richieda un trattamento speciale o di cui non sei sicuro</a></h3>
<p>Nella formattazione, se incontri qualcosa che non &egrave; contemplato in
   questa guida che ritieni necessiti di un trattamento speciale o che non sai
   con certezza come trattare, scrivi un messaggio nella
   <a href="#forums">Discussione del Progetto</a>, annotando il numero
   png della pagina.
</p>
<p>Dovresti inserire inoltre una nota nel testo in formattazione per spiegare ai
   successivi volontari o al post-correttore qual &egrave; il problema o la domanda.
   La nota viene inserita iniziando con una parentesi quadra e due asterischi
   <kbd>[**</kbd> e termina con un'altra parentesi quadra <kbd>]</kbd>.
   Questo la separa chiaramente dal testo dell'autore e segnala al post-correttore
   di fermarsi ad esaminare attentamente questa parte del testo e
   l'immagine corrispondente per considerare ogni questione.
   Puoi anche identificare in che turno stai lavorando appena prima di <kbd>]</kbd>
   cos&igrave; che i volontari sappiano chi ha lasciato la nota.
   Qualsiasi commento inserito da un volontario precedente <b>deve</b> essere
   lasciato al suo posto. Vedi la sezione seguente per maggiori dettagli.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Torna all'inizio</a></p>


<h3><a name="prev_notes">Note e Commenti dei Volontari Precedenti</a></h3>
<p>Qualsiasi commento o nota inseriti da un volontario precedente <b>devono</b>
   essere lasciati al loro posto. Puoi aggiungere che sei d'accordo o non sei
   d'accordo con la nota esistente ma, anche se sai la risposta, non devi
   assolutamente rimuovere il commento. Se hai trovato una fonte che risolve
   il problema, per favore citala in modo che il post-correttore vi possa
   fare riferimento.
</p>
<p>Se incontri una nota di un precedente volontario di cui conosci la risposta,
   per favore dedica un momento per dare un riscontro cliccando il nome
   nell'interfaccia di correzione e manda un messaggio privato spiegando
   come trattare quella situazione in futuro.
   Per favore, come gi&agrave; detto, non rimuovere la nota.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Torna all'inizio</a></p>


<table width="100%" border="0" cellspacing="0" cellpadding="6" summary="Common Problems">
  <tbody>
    <tr>
      <td bgcolor="silver"><h2 style="margin-bottom: 0; margin-top: 0;">Problemi comuni:</h2></td>
    </tr>
  </tbody>
</table>


<h3><a name="bad_image">Immagine danneggiata</a></h3>
<p>Se una pagina &egrave; danneggiata (non si carica, praticamente illeggibile, ecc.),
   per favore scrivi un messaggio riguardo all'immagine danneggiata nella
   <a href="#forums">Discussione del progetto</a>.
</p>
<p>Ricorda che alcune pagine sono abbastanza grandi ed &egrave; normale che
   il tuo browser abbia difficolt&agrave; nel visualizzarle, specialmente se
   hai diverse finestre aperte o stai usando un computer pi&ugrave; vecchio.
   Prova a chiudere alcune delle tue finestre e programmi, o lascia un messaggio
   nella discussione del progetto per vedere se qualcun altro ha lo stesso problema.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Torna all'inizio</a></p>


<h3><a name="bad_text">Immagine sbagliata rispetto al testo</a></h3>
<p>Se c'&egrave; un'immagine sbagliata rispetto al testo dato, per favore lascia
   un messaggio riguardo a questa pagina nella
   <a href="#forums">discussione del progetto</a>.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Torna all'inizio</a></p>


<h3><a name="round1">Errori del correttore o impaginatore precedente</a></h3>
<p>Se il volontario precedente ha fatto molti errori o ha tralasciato molte cose,
   per favore dedica un momento a mandare un riscontro cliccando il suo nome
   nell'interfaccia di correzione e mandando un messaggio privato per spiegare
   come trattare la situazione in modo che sappia cosa fare in futuro.
</p>
<p><em>Per favore, sii gentile!</em> Tutti qui siamo volontari e probabilmente
   cerchiamo di fare del nostro meglio. Il fine del tuo messaggio di riscontro &egrave;
   quello di informare del modo giusto di correggere, invece di criticare.
   Dai uno specifico esempio del loro lavoro, mostrando ci&ograve; che hanno fatto,
   e come avrebbero dovuto fare.
</p>
<p>Se il volontario precedente ha fatto un lavoro eccellente, puoi anche mandargli un messaggio
   al riguardo&mdash;specialmente se hanno lavorato su una pagina particolarmente difficile.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Torna all'inizio</a></p>


<h3><a name="p_errors">Errori di stampa/Errori di ortografia (Refusi)</a></h3>
<p>Correggi tutte le parole che l'OCR ha riconosciuto male (scannos), ma non correggere
   ci&ograve; che possono sembrarti errori di ortografia o di stampa nella pagina.
   Molti testi antichi contengono parole scritte in modo diverso dall'uso moderno e
   noi conserviamo l'ortografia antica, inclusi i caratteri accentati.
</p>
<p>Metti una nota nel testo accanto ad un errore di stapma<kbd>[**typo per stampa?]</kbd>.
   Se non sei certo che sia veramente un errore, chiedi nella
   <a href="#forums">Discussione del progetto</a>. Se fai un cambiamento, includi una
   nota che descriva cosa hai cambiato: <kbd>[**typo "stapma" corretto]</kbd>.
   Includi i due asterischi <kbd>**</kbd> in modo che il post-correttore li noti.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Torna all'inizio</a></p>


<h3><a name="f_errors">Errori sui fatti nei testi</a></h3>
<p>Non correggere errori sui fatti nel libro dell'autore. Molti dei libri che
   stiamo correggendo contengono affermazioni di fatti che non accettiamo pi&ugrave;
   come esatte. Lasciale come l'autore le ha scritte.
   Vedi <a href="#p_errors">Errori di stampa/Errori di ortografia</a>
   per il modo di lasciare una nota se pensi che il testo stampato non sia
   ci&ograve; che l'autore intendeva.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Torna all'inizio</a></p>

</div>


<table width="100%" border="0" cellspacing="0" cellpadding="6" summary="Alphabetical Index">
  <tbody>
    <tr>
      <td bgcolor="silver"><h2 style="margin-bottom: 0; margin-top: 0;"><a name="index">Indice alfabetico della guida</a></h2></td>
    </tr>
  </tbody>
</table>
<br>

<table border="0" width="100%" summary="Alphabetical Index">
  <tr>
    <td width="50%" valign="top">
      <ul>
        <li><a href="#r_align">Allineato a destra, Testo</a></li>
        <li><a href="#supers">Apici/Esponenti</a></li>
        <li><a href="#extra_s">Asterischi (Caratteri) tra paragrafi</a></li>
        <li><a href="#blank_pg">Blank Page, Pagina vuota</a></li>
        <li><a href="#font_sz">Cambiamento delle dimensioni del carattere</a></li>
        <li><a href="#font_ch">Cambiamenti del tipo di carattere (font)</a></li>
        <li><a href="#chap_head">Capitoli, Intestazioni dei</a></li>
        <li><a href="#font_sz">Carattere, Cambiamento delle dimensioni del</a></li>
        <li><a href="#font_ch">Carattere (font), Cambiamenti del tipo di</a></li>
        <li><a href="#block_qt">Citazioni in blocco</a></li>
        <li><a href="#comments">Commenti al progetto (Project Comments)</a></li>
        <li><a href="#prev_notes">Commenti dei volontari precedenti</a></li>
        <li><a href="#title_pg">Copertina/Retro</a></li>
        <li><a href="#title_pg">Copertina/Titolo</a></li>
        <li><a href="#prev_pg">Correggere errori nelle pagine precedenti</a></li>
        <li><a href="#round1">Correttore precedente, Errori di correzione o formattazione del</a></li>
        <li><a href="#letter">Corrispondenza</a></li>
        <li><a href="#italics">Corsivo</a></li>
        <li><a href="#extra_s">Decorazioni tra i paragrafi</a></li>
        <li><a href="#illust">Didascalia, Illustrazioni</a></li>
        <li><a href="#forums">Discussione del progetto</a></li>
        <li><a href="#maj_div">Divisioni nel testo, Maggiori</a></li>
        <li><a href="#lists">Elenchi</a></li>
        <li><a href="#poetry">Epigrammi</a></li>
        <li><a href="#p_errors">Errori di stampa</a></li>
        <li><a href="#f_errors">Errori sui fatti</a></li>
        <li><a href="#supers">Esponenti/Apici</a></li>
        <li><a href="#font_ch">Font, Cambiamenti del tipo di carattere</a></li>
        <li><a href="#separate_pg">Formattare ogni pagina separatamente</a></li>
        <li><a href="#forums">Forum</a></li>
        <li><a href="#spaced"><span style="letter-spacing: .2em;">Gesperrt</span> (Spazieggiato)</a></li>
        <li><a href="#bold">Grassetto</a></li>
        <li><a href="#font_ch">Gotico, Testo in</a></li>
        <li><a href="#summary">Guida in breve alla formattazione</a></li>
        <li><a href="#illust">Illustrazioni</a></li>
        <li><a href="#maj_div">Illustrazioni, Indice delle</a></li>
        <li><a href="#bad_image">Immagine danneggiata</a></li>
        <li><a href="#bad_text">Immagine sbagliata per il testo</a></li>
        <li><a href="#maj_div"> Indice delle Illustrazioni</a></li>
        <li><a href="#bk_index">Indici</a></li>
        <li><a href="#maj_div">Intestazioni, Altre</a></li>
        <li><a href="#chap_head">Intestazioni dei Capitoli</a></li>
        <li><a href="#sect_head">Intestazioni di Sezione</a></li>
        <li><a href="#maj_div">Introduzione</a></li>
        <li><a href="#letter">Lettere/Corrispondenza</a></li>
        <li><a href="#lists">Liste</a></li>
        <li><a href="#small_caps"><span style="font-variant: small-caps">Maiuscoletto</span></a></li>
        <li><a href="#word_caps">Maiuscolo</a></li>
        <li><a href="#outofline">Marcatore di formattazione di paragrafo</a></li>
        <li><a href="#inline">Marcatore di formattazione di riga</a></li>
        <li><a href="#play_n">Nomi degli attori (Opere teatrali)</a></li>
        <li><a href="#para_side">Note a margine del paragrafo</a></li>
        <li><a href="#footnotes">Note a pi&egrave; di pagina</a></li>
        <li><a href="#prev_notes">Note dei volontari precedenti</a></li>
        <li><a href="#footnotes">Note di chiusura</a></li>
        <li><a href="#play_n">Note di palcoscenico (Opere teatrali)</a></li>
        <li><a href="#line_no">Numeri di riga</a></li>
      </ul>
    </td>
    <td width="50%" valign="top">
      <ul>
        <li><a href="#separate_pg">Ogni pagina &egrave; un'unit&agrave; separata</a></li>
        <li><a href="#play_n">Opere teatrali</a></li>
        <li><a href="#play_n">Opere teatrali, Nomi degli attori</a></li>
        <li><a href="#play_n">Opere teatrali, Note di Palcoscenico</a></li>
        <li><a href="#outofline">Paragrafo, Marcatori di formattazione di</a></li>
        <li><a href="#para_side">Paragrafo, Note a margine del</a></li>
        <li><a href="#outofline">Paragrafo, Posizionamento dei marcatori di</a></li>
        <li><a href="#para_space">Paragrafo, Spaziatura/rientro</a></li>
        <li><a href="#extra_s">Paragrafi, Spazio tra</a></li>
        <li><a href="#subscr">Pedici</a></li>
        <li><a href="#poetry">Poesia</a></li>
        <li><a href="#outofline">Posizionamento dei marcatori di paragrafo</a></li>
        <li><a href="#inline"> Posizionamento dei Marcatori di Riga</a></li>
        <li><a href="#round1">Precedenti errori di correzione o formattazione</a></li>
        <li><a href="#maj_div">Prefazione</a></li>
        <li><a href="#maj_div">Principali divisioni nel testo</a></li>
        <li><a href="#comments">Project Comments (Commenti al progetto)</a></li>
        <li><a href="#anything">Qualsiasi cosa di cui non sei sicuro</a></li>
        <li><a href="#anything">Qualsiasi cosa richieda un trattamento speciale</a></li>
        <li><a href="#p_errors">Refusi, Errori di stampa</a></li>
        <li><a href="#prime">Regola principale</a></li>
        <li><a href="#title_pg">Retro/Copertina</a></li>
        <li><a href="#para_space">Rientro di paragrafo</a></li>
        <li><a href="#page_ref">Riferimenti di pagina "Vedi pag. 123"</a></li>
        <li><a href="#line_no">Riga, Numeri di</a></li>
        <li><a href="#inline">Riga, Posizionamento dei Marcatori di</a></li>
        <li><a href="#extra_s">Righe orizzontali</a></li>
        <li><a href="#extra_s">Righe tra paragrafi</a></li>
        <li><a href="#about">Riguardo questo documento</a></li>
        <li><a href="#sect_head">Sezione, Intestazioni di</a></li>
        <li><a href="#para_side">Sidenote,  Note a margine del Paragrafo</a></li>
        <li><a href="#toc">Sommario, Tavola dei Contenuti</a></li>
        <li><a href="#extra_s">Spazi aggiuntivi tra i paragrafi</a></li>
        <li><a href="#extra_sp">Spazi aggiuntivi tra le parole</a></li>
        <li><a href="#para_space">Spaziatura dei Paragrafi</a></li>
        <li><a href="#spaced"><span style="letter-spacing: .2em;">Spazieggiato</span> (gesperrt)</a></li>
        <li><a href="#tables">Tabelle</a></li>
        <li><a href="#extra_sp">Tabulazioni</a></li>
        <li><a href="#toc">Tavola dei Contenuti, (Sommario)</a></li>
        <li><a href="#maj_div">Tavola delle Illustrazioni</a></li>
        <li><a href="#r_align">Testo allineato a destra</a></li>
        <li><a href="#bad_text">Testo danneggiato</a></li>
        <li><a href="#font_ch">Testo in Gotico</a></li>
        <li><a href="#underl">Testo sottolineato</a></li>
        <li><a href="#extra_s">Thought Breaks (Pausa di pensiero)</a></li>
        <li><a href="#chap_head">Titoli dei Capitoli</a></li>
        <li><a href="#sect_head">Titoli delle Sezioni</a></li>
        <li><a href="#title_pg">Titolo</a></li>
        <li><a href="#word_caps">Tutto maiuscolo</a></li>
      </ul>
    </td>
  </tr>
</table>

<br>
<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="silver" summary="Links">
  <tr>
    <td width="10">&nbsp;</td>
    <td width="100%" align="center"><font face="verdana, helvetica, sans-serif" size="1">
      Ritorna a:
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
