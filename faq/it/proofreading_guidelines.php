<?php
// Translated by user manutwo 18 Feb 2009

$relPath='../../pinc/';
include_once($relPath.'base.inc');
include_once($relPath.'faq.inc');
include_once($relPath.'pg.inc');
include_once($relPath.'theme.inc');

maybe_redirect_to_external_faq("it");

$theme_args["css_data"] = "p.backtotop {text-align:right; font-size:75%;margin-right:-5%;}";

output_header('Regole di Correzione', NO_STATSBAR, $theme_args);

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

<h1 align="center"><a name="top">Regole di Correzione</a></h1>

<h3 align="center">Versione 2.0, revisionata giugno 7, 2009 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
  <a href="../dochist.php"><font size="-1">(Storia delle modifiche)</font></a></h3>

<p>Regole di Correzione <a href="../proofreading_guidelines.php">in Inglese</a> /
      Proofreading Guidelines <a href="../proofreading_guidelines.php">in English</a><br>
    Regole di Correzione <a href="../fr/proofreading_guidelines.php">in Francese</a> /
      Directives de Relecture et Correction <a href="../fr/proofreading_guidelines.php">en fran&ccedil;ais</a><br>
    Regole di Correzione <a href="../pt/proofreading_guidelines.php">in Portoghese</a> /
      Regras de Revis&atilde;o <a href="../pt/proofreading_guidelines.php">em Portugu&ecirc;s</a><br>
    Regole di Correzione <a href="../es/proofreading_guidelines.php">in Spagnolo</a> /
      Reglas de Revisi&oacute;n <a href="../es/proofreading_guidelines.php">en espa&ntilde;ol</a><br>
    Regole di Correzione <a href="../nl/proofreading_guidelines.php">in Olandese</a> /
      Proeflees-Richtlijnen <a href="../nl/proofreading_guidelines.php">in het Nederlands</a><br>
    Regole di Correzione <a href="../de/proofreading_guidelines.php">in Tedesco</a> /
      Korrekturlese-Richtlinien <a href="../de/proofreading_guidelines.php">auf Deutsch</a><br>
</p>

<p>Prova il <a href="../../quiz/start.php?show_only=PQ">Quiz di addestramento per la correzione</a>!
</p>

<table border="0" cellspacing="0" width="100%" summary="Sommario">
  <tbody>
  <tr>
    <td width="1" bgcolor="silver">&nbsp;</td>
    <td bgcolor="silver" align="center"><h2 style="margin-bottom: 0; margin-top: 0;">Sommario</h2></td>
  </tr>
  <tr>
    <td width="1" bgcolor="silver">&nbsp;</td>
    <td bgcolor="white" align="left">
      <ul>
        <li><a href="#prime">La regola principale</a></li>
        <li><a href="#summary">La guida in breve</a></li>
        <li><a href="#about">Riguardo a questo documento</a></li>
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
        <li style="margin-top:.25em;"><font size="+1">Correzione a livello del carattere:</font>
        <ul>
          <li><a href="#double_q">Virgolette doppie</a></li>
          <li><a href="#single_q">Virgolette semplici</a></li>
          <li><a href="#quote_ea">Virgolette su ogni riga</a></li>
          <li><a href="#period_s">Punto al termine di una frase (Punto fermo)</a></li>
          <li><a href="#punctuat">Spazi della punteggiatura</a></li>
          <li><a href="#extra_sp">Spazi aggiuntivi o tabulazioni tra le parole</a></li>
          <li><a href="#trail_s">Spazi alla fine delle righe</a></li>
          <li><a href="#em_dashes">Lineette, Trattini e Segni Meno</a></li>
          <li><a href="#eol_hyphen">Andare a capo con trattino o lineetta a fine riga</a></li>
          <li><a href="#eop_hyphen">Andare a capo con trattino o lineetta a fine pagina</a></li>
          <li><a href="#period_p">Puntini di sospensione &quot;...&quot; (Ellipsis)</a></li>
          <li><a href="#contract">Contrazioni</a></li>
          <li><a href="#fract_s">Frazioni</a></li>
          <li><a href="#a_chars">Caratteri accentati/non-ASCII</a></li>
          <li><a href="#d_chars">Caratteri con segni diacritici</a></li>
          <li><a href="#f_chars">Caratteri non Latini</a></li>
          <li><a href="#supers">Apici/Esponenti</a></li>
          <li><a href="#subscr">Pedici</a></li>
          <li><a href="#drop_caps">Lettera maiuscola iniziale grande e decorata (Capolettera)</a></li>
          <li><a href="#small_caps">Parole in maiuscoletto</a></li>
        </ul></li>
        <li style="margin-top:.25em;"><font size="+1">Correzione a livello del paragrafo:</font>
        <ul>
          <li><a href="#line_br">Interruzioni di riga/Testo a capo</a></li>
          <li><a href="#chap_head">Titoli dei capitoli</a></li>
          <li><a href="#para_space">Spaziatura dei paragrafi/Rientro</a></li>
          <li><a href="#page_hf">Intestazioni/Pi&egrave; di pagina</a></li>
          <li><a href="#illust">Illustrazioni</a></li>
          <li><a href="#footnotes">Note a pi&egrave; di pagina/Note di chiusura</a></li>
          <li><a href="#para_side">Note a margine</a></li>
          <li><a href="#mult_col">Colonne multiple</a></li>
          <li><a href="#tables">Tabelle</a></li>
          <li><a href="#poetry">Poesia/Epigrammi</a></li>
          <li><a href="#line_no">Numeri di riga</a></li>
          <li><a href="#next_word">Parola singola a fondo pagina/Richiamo</a></li>
        </ul></li>
        <li style="margin-top:.25em;"><font size="+1">Correzione a livello della pagina:</font>
        <ul>
          <li><a href="#blank_pg">Pagina vuota</a></li>
          <li><a href="#title_pg">Copertina/Retro</a></li>
          <li><a href="#toc">Tavola dei contenuti/Sommario</a></li>
          <li><a href="#bk_index">Indici</a></li>
          <li><a href="#play_n">Opere teatrali: Nomi degli attori/Note di palcoscenico</a></li>
        </ul></li>
        <li><a href="#anything">Qualsiasi cosa richieda un trattamento speciale o di cui non sei sicuro</a></li>
        <li><a href="#prev_notes">Note e commenti dei correttori precedenti</a></li>
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
        <li style="margin-top:.25em;"><font size="+1">Problemi comuni:</font>
        <ul>
          <li><a href="#formatting">Formattazione</a></li>
          <li><a href="#common_OCR">Problemi comuni del software di riconoscimento dei caratteri (OCR)</a></li>
          <li><a href="#OCR_scanno">Problemi dell'OCR: Scannos</a></li>
          <li><a href="#OCR_raised_o">Problemi dell'OCR: &egrave; quel &deg; &ordm; proprio un simbolo di grado?</a></li>
          <li><a href="#hand_notes">Note scritte a mano nel libro</a></li>
          <li><a href="#bad_image">Immagine danneggiata/sbagliata</a></li>
          <li><a href="#bad_text">Immagine sbagliata rispetto al testo</a></li>
          <li><a href="#round1">Errori del correttore precedente</a></li>
          <li><a href="#p_errors">Errori di stampa/Errori di ortografia (Refusi)</a></li>
          <li><a href="#f_errors">Errori sui fatti nei testi</a></li>
          <li><a href="#insert_char">Inserimento dei caratteri speciali</a></li>
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
<p>Il libro elettronico finale visto dal lettore, anche di qui a molti anni a
   venire, dovrebbe comunicare con esattezza l'intenzione dell'autore.
   Se l'autore ha scritto parole in modo inusuale, le lasciamo scritte in quel
   modo. Se l'autore ha fatto affermazioni oltraggiose, razziste o discutibili,
   le lasciamo in quel modo. Se l'autore ha messo virgole, apici o note a
   pi&egrave; pagina ogni tre parole, lasciamo le virgole, gli apici e le
   note a pi&egrave; pagina. Siamo correttori, <b>non</b> editori: se
   qualcosa nel testo non corrisponde all'immagine della pagina originale,
   devi cambiare il testo affinch&eacute; corriponda.
   (Vedi <a href="#p_errors">Errori di stampa</a> per il trattamento
   corretto degli errori di stampa pi&ugrave; ovvi).
</p>
<p>Modifichiamo minime convenzioni tipografiche che non toccano il senso di
   ci&ograve; che l'autore ha scritto. Per esempio, riuniamo le parole che
   sono state divise a fine riga (<a href="#eol_hyphen">Andare a capo a fine riga</a>).
   Questi tipi di modifiche ci permettono di arrivare ad una versione del
   libro <em>prodotta in maniera uniforme</em>. Le regole di correzione che
   seguiamo sono pensate per raggiungere questo risultato. Ti preghiamo di
   leggere attentamente il resto delle regole di correzione tenendo presente
   questo concetto. Queste regole sono intese <i>solamente</i> per la correzione.
   In qualit&agrave; di correttore, tu riproduci il contenuto dell'immagine, mentre
   successivamente gli impaginatori riprodurranno l'aspetto dell'immagine.
</p>
<p>Per facilitare i successivi correttori, impaginatori e il post-correttore,
   manteniamo le <a href="#line_br">interruzioni di riga</a> (testo a capo).
   Ci&ograve; permette di confrontare facilmente le righe del testo con le
   righe dell'immagine.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Torna all'inizio</a></p>


<h3><a name="summary">La guida in breve</a></h3>
<p>La <a href="../proofing_summary.pdf">Guida in breve</a> &egrave; un documento
   di 2 pagine in versione stampabile (.pdf) che riassume i punti principali
   di queste Regole e d&agrave; degli esempi di come procedere alla correzione.
   I correttori principianti sono invitati a stampare questo documento e tenerlo
   a portata di mano durante la correzione.
</p>
<p>Potresti aver bisogno di scaricare ed installare un lettore di .pdf.
   Puoi averne uno gratuito da Adobe&reg;
   <a href="http://www.adobe.com/products/acrobat/readstep2.html">qui</a>.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Torna all'inizio</a></p>


<h3><a name="about">Riguardo a questo documento</a></h3>
<p>Questo documento &egrave; scritto per spiegare le regole di correzione che
   usiamo per mantenere la coerenza nella correzione di un unico libro che
   viene distribuito fra molti correttori, ognuno dei quali lavora
   contemporaneamente su pagine diverse. Questo aiuta tutti noi a correggere
   <em>allo stesso modo</em>, cosa che rende a sua volta pi&ugrave; facile il
   compito agli impaginatori e al post-correttore che completeranno il lavoro
   su questo e-book.
</p>
<p><i>Non &egrave; inteso come un manuale generale di editoria o tipografia</i>.
</p>
<p>Abbiamo incluso in queste regole alla correzione tutti i quesiti che ci sono
   stati posti dai nuovi correttori. Esiste una guida separata di
   <a href="formatting_guidelines.php">Regole di Formattazione</a>.
   Un secondo gruppo di volontari lavorer&agrave; alla formattazione del testo.
   Se incontri una situazione che non viene contemplata da questa guida,
   &egrave; probabile che sar&agrave; trattata nei turni di formattazione,
   perci&ograve; non viene affrontata qui. Se non sei sicuro, chiedi nella
   <a href="#forums">Discussione del Progetto</a>.
</p>
<p>Se alcuni argomenti mancano, o pensi debbano essere affrontati diversamente,
   o se qualcosa &egrave; vago, faccelo sapere.
<?php if($site_url == "http://www.pgdp.net" || $site_url == "http://www.pgdp.org") { ?>
   Se c'&egrave; un termine in queste guide che non ti &egrave; familiare, leggi il
   <a href="http://www.pgdp.net/wiki/DP_Jargon">glossario nella Wiki</a>.
<?php } ?>
   Questo documento &egrave; un lavoro in corso. Aiutaci a migliorarlo
   suggerendo i cambiamenti nel Forum relativo alla Documentazione
   (Documentation Forum) <a href="<?php echo $Guideline_discussion_URL; ?>">questo thread</a>.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Torna all'inizio</a></p>


<h3><a name="comments">Commenti al progetto (Project Comments)</a></h3>
<p>Quando selezioni un progetto per la correzione, viene caricata la "Project
   Page" (Pagina del Progetto). In questa pagina c'&egrave; una sezione intitolata
   "Project Comments"(Commenti al Progetto) che contiene specifiche informazioni
   su questo progetto (libro). <b>Leggili prima di cominciare a correggere le pagine!</b>
   Se il "Project Manager" (Manager del Progetto) desidera che in questo libro si
   faccia qualcosa in modo diverso da come &egrave; specificato in queste regole,
   questo sar&agrave; scritto qui. Le istruzioni nei Commenti al progetto
   <em>prevalgono</em> sulle regole di questa guida, quindi segui quelle istruzioni.
   Potrebbero esserci delle istruzioni nei Commenti al progetto che riguardano la
   fase di formattazione, che non si applicano nella fase di correzione. Infine,
   &egrave; qui che il Project Manager pu&ograve; darti qualche informazione
   interessante sull'autore o sul progetto.
</p>
<p><em>Per favore, leggi anche il "Project Thread" (Forum del Progetto)</em>:
   il Project Manager potrebbe chiarire regole specifiche per il progetto
   proprio qui, e questo &egrave; il luogo usato dai correttori per indicare
   agli altri correttori problemi ricorrenti nel progetto e come possono
   essere affrontati meglio. (Vedi oltre).
</p>
<p>Nella Project Page, il collegamento 'Images, Pages Proofread, &amp; Differences'
   (Immagini, Pagine corrette e Differenze) ti permette di vedere che tipo di
   cambiamenti sono stati effettuati dagli altri correttori.
   <a href="<?php echo $Using_project_details_URL; ?>">Questa discussione nel forum</a>
   riguarda modi diversi in cui usare queste informazioni.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Torna all'inizio</a></p>


<h3><a name="forums">Forum/Discuti il progetto (Discuss This Project)</a></h3>
<p>Nella Project Page (Pagina del progetto) dove si accede alla correzione delle
   pagine, nel campo "Forum", c'&egrave; un collegamento intitolato "Discuss this
   Project" (Discuti il Progetto, se la discussione &egrave; gi&agrave; cominciata)
   o "Start a discussion on this Project" (Comincia una discussione sul Progetto,
   se la discussione non &egrave; ancora cominciata). Cliccando questo collegamento
   arriverai ad una discussione nel forum dei progetti dedicata a questo progetto
   specifico. Questo &egrave; il luogo in cui fare domande sul libro, informare il
   Project Manager di possibili problemi, ecc. L'uso di questa discussione
   nel forum &egrave; il modo raccomandato per comunicare con il Project Manager e
   con gli altri correttori che stanno lavorando su questo libro.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Torna all'inizio</a></p>


<h3><a name="prev_pg">Correggere errori nelle pagine precedenti</a></h3>
<p>La <a href="#comments">Project Page</a> (Pagina del progetto) contiene
   collegamenti alle pagine del progetto che hai corretto recentemente.
   (Se non hai ancora corretto pagine, non verranno mostrati collegamenti.)
</p>
<p>Le pagine elencate come "DONE" (SALVATE) o "IN PROGRESS" (IN CORSO) sono
   disponibili per modificare le correzioni fatte o finire la correzione.
   Clicca semplicemente sul collegamento alla pagina. Cos&igrave;, se
   scopri di aver fatto un errore su una pagina o annotato qualcosa non
   correttamente, qui puoi cliccare su quella pagina e riaprirla per rimediare
   all'errore.
</p>
<p>Puoi anche usare i collegamenti "Images, Pages Proofread, &amp; Differences"
   (Immagini, pagine corrette e differenze) o "Just My Pages" (Solo le mie pagine)
   nella <a href="#comments">Project Page</a> (Pagina del progetto). Queste
   pagine avranno un collegamento "Edit" (Modifica) accanto alle pagine su cui
   hai lavorato nel turno corrente e che possono ancora essere modificate.
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


<table width="100%" border="0" cellspacing="0" cellpadding="6" summary="Character-level proofreading">
  <tbody>
    <tr>
      <td bgcolor="silver"><h2 style="margin-bottom: 0; margin-top: 0;">Correzione a livello del carattere:</h2></td>
    </tr>
  </tbody>
</table>


<h3><a name="double_q">Virgolette doppie</a></h3>
<p>Correggi le &ldquo;virgolette doppie&rdquo; come semplici virgolette
   doppie ASCII <kbd>"</kbd>. Non cambiare le virgolette doppie in virgolette
   semplici. Lasciale come le ha scritte l'autore.
   Vedi <a href="#chap_head">Titoli dei Capitoli</a> nel caso in cui manchi una
   virgoletta doppia all'inizio del capitolo.
</p>
<p>Per le citazioni introdotte da caratteri diversi da <kbd>"</kbd>, usa gli stessi
   caratteri che appaiono nell'immagine se sono disponibili.
   Gli equivalenti francesi, guillemets (caporali)&nbsp; <kbd>&laquo;come questi&raquo;</kbd>,
   sono disponibili nei menu a discesa nell'interfaccia di correzione, poich&eacute;
   fanno parte del Latin-1. Ricordati di rimuovere lo spazio tra le virgolette
   e il testo citato; se necessario sar&agrave; aggiunto in post-correzione.
   Lo stesso dicasi per le lingue che usano le guillemets inverse,&nbsp; <kbd>&raquo;come
   queste&laquo;</kbd>.
</p>
<p>Le virgolette usate in alcuni testi (in Tedesco o altre lingue)&nbsp; <kbd>&bdquo;come
   queste&ldquo;</kbd>&nbsp;
   sono anche disponibili nei men&ugrave; a discesa; per semplicit&agrave;,
   dovresti sempre usare&nbsp; <kbd>&bdquo;</kbd>&nbsp; e&nbsp; <kbd>&ldquo;</kbd>&nbsp;
   indipendentemente dalle virgolette effettivamente usate nel testo originale, se
   le virgolette usate nel testo originale sono chiaramente basse e alte. Se
   necessario, le virgolette saranno sostituite con quelle effettivamente usate
   nel testo in post-correzione.
</p>
<p>Nei <a href="#comments">Project Comments</a> (Commenti al Progetto) il
   Project Manager potrebbe darti istruzioni di rendere le virgolette di lingue
   diverse dall'Inglese in modo diverso per un libro particolare.
   Assicurati di non applicare queste istruzioni particolari ad altri progetti.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Torna all'inizio</a></p>


<h3><a name="single_q">Virgolette semplici</a></h3>
<p>Rendile come la virgoletta singola <kbd>'</kbd> in ASCII (apostrofo). Non
   cambiare le virgolette semplici in virgolette doppie. Lasciale come
   l'autore le ha scritte.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Torna all'inizio</a></p>


<h3><a name="quote_ea">Virgolette su ogni riga</a></h3>
<p>Correggi le virgolette all'inizio di ogni riga di una citazione rimuovendole
   tutte <b>eccetto</b> quelle all'inizio della citazione.
   Se la citazione si estende su molti paragrafi, lascia le virgolette
   che si trovano nella prima riga di ogni paragrafo.
</p>
<p>Per&ograve;, nelle poesie, lascia le virgolette che appaiono nell'immagine,
   dato che le interruzioni di riga non saranno cambiate.
</p>
<p>Spesso non ci sono virgolette di chiusura fino all'ultimo parola del brano
   citato nel testo, che potrebbe non essere nella stessa pagina che stai correggendo.
   Lascialo cos&igrave;&mdash;non aggiungere virgolette di chiusura che non sono
   nell'immagine della pagina.
</p>
<p>Ci sono alcune eccezioni specifiche per alcune lingue. In Francese, per esempio,
   il dialogo all'interno delle citazioni usa una combinazione di punteggiatura
   diversa per indicare i diversi interlocutori. Se non hai familiarit&agrave;
   con una lingua particolare, verifica nei <a href="#comments">Project Comments</a>
   (Commenti al Progetto) o lascia un messaggio al Project Manager nel Forum del
   progetto per chiedere chiarimenti.
</p>
<!-- END RR -->
<table width="100%" border="1" cellpadding="4" cellspacing="0" align="center" summary="Example of quote marks on each line">
  <tbody>
    <tr>
      <th valign="top" align="left" bgcolor="cornsilk">Immagine originale:</th>
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
      <th valign="top" align="left" bgcolor="cornsilk">Testo corretto:</th>
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
<p class="backtotop"><a href="#top">Torna all'inizio</a></p>


<h3><a name="period_s">Punto al termine di una frase (Punto fermo)</a></h3>
<p>Inserisci un solo spazio dopo il punto fermo che termina una frase.
</p>
<p>Non &egrave; necessario che tu rimuova gli spazi in eccesso dopo il punto se
   sono gi&agrave; presenti nel testo convertito dall'OCR&mdash;possiamo farlo
   automaticamente durante la post-correzione.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Torna all'inizio</a></p>


<h3><a name="punctuat">Spazi della punteggiatura</a></h3>
<p>Talvolta appaiono alcuni spazi prima della punteggiatura perch&eacute;
   i libri stampati nel 1700 &amp; 1800 spesso usavano spazi parziali prima
   della punteggiatura, tipo il punto e virgola e i due punti.
</p>
<p>In generale, un segno di punteggiatura dovrebbe avere uno spazio dopo di esso,
   ma nessuno spazio prima. Se il testo convertito dall'OCR non ha uno spazio
   dopo il segno di punteggiatura, aggiungine uno; se c'&egrave; uno spazio
   prima della punteggiatura, rimuovilo. Questo vale anche per lingue come il
   Francese che normalmente usano spazi prima dei caratteri di punteggiatura.
   Comunque, i simboli di punteggiatura che normalmente appaiono a coppie, come
   "virgolette", (parentesi), [parentesi quadre] e {parentesi graffe} normalmente
   hanno uno spazio prima del simbolo di apertura, che dovrebbe essere mantenuto.
</p>
<!-- END RR -->

<table width="100%" align="center" border="1" cellpadding="4" cellspacing="0" summary="Punctuation example">
  <tbody>
    <tr><th align="left" bgcolor="cornsilk">Immagine originale:</th></tr>
    <tr>
      <td valign="top">and so it goes&nbsp;; ever and ever.</td>
    </tr>
    <tr><th align="left" bgcolor="cornsilk">Testo corretto:</th></tr>
    <tr>
      <td valign="top"><kbd>and so it goes; ever and ever.</kbd></td>
    </tr>
  </tbody>
</table>
<p class="backtotop"><a href="#top">Torna all'inizio</a></p>


<h3><a name="extra_sp">Spazi aggiuntivi o tabulazioni tra le parole</a></h3>
<p>Spazi aggiuntivi tra le parole sono frequenti come prodotto dell'OCR.
   Non devi preoccuparti di rimuoverli&mdash;pu&ograve; essere fatto
   automaticamente durante la post-correzione.
   In ogni caso, spazi aggiuntivi attorno alla punteggiatura, alle lineette,
   alle virgolette, ecc. <b>devono</b> essere rimossi quando separano
   il segno dalla parola.
</p>
<p>Per esempio, in <kbd>A horse&nbsp;;&nbsp;&nbsp;my kingdom for a horse.</kbd>
   lo spazio tra la parola "horse" e il punto e virgola deve essere rimosso.
   Ma i due spazi dopo il punto e virgola vanno bene&mdash;non &egrave;
   necessario che tu ne rimuova uno.
</p>
<p>Inoltre, se incontri caratteri di tabulazione nel testo, devi rimuoverli.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Torna all'inizio</a></p>


<h3><a name="trail_s">Spazi alla fine delle righe</a></h3>
<p>Non preoccuparti di inserire spazi alla fine delle righe di testo; questo
   tipo di spazi saranno automaticamente rimossi dal testo quando salvi la pagina.
   Quando il testo arriver&agrave; in post-correzione, ogni ritorno a capo
   sar&agrave; convertito in uno spazio.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Torna all'inizio</a></p>


<h3><a name="em_dashes">Lineette, Trattini e Segni Meno</a></h3>
<p>Incontrerai generalmente quattro tipi di questi segni nei libri:
</p>
  <ol compact>
    <li><i>Trattini</i>. Sono usati per <b>unire</b> le parole, o talvolta per
        unire prefissi o suffissi ad una parola.
    <br>Correggile con un trattino singolo, senza spazi intorno.
        Ricorda che c'&egrave; una eccezione comune a questa regola, mostrata
        nel secondo esempio qui sotto.
    </li>
    <li><i>Lineetta enne</i>. &Egrave; un po' pi&ugrave; lunga di un trattino, e
        viene usata per una <b>serie</b> di numeri, o per il segno matematico di
        <b>meno</b>.
    <br>Correggi anche questa come un trattino singolo. Gli spazi prima e
        dopo sono determinati dal modo in cui viene stampata nel libro; normalmente
        non ci sono spazi nelle serie di numeri; normalmente ci sono spazi
        attorno al segno matematico di meno, a volte da entrambe le parti, a volte
        solo prima.
    </li>
    <li><i>Lineette emme &amp; lineette lunghe</i>. Servono per <b>separare</b> le
        parole&mdash;talvolta per enfasi come in questo caso&mdash;o quando
        all'interlocutore resta una parola in gola&mdash;&mdash;!
    <br>Correggile con due trattini se la lineetta &egrave; lunga 2&ndash;3
        lettere (una <i>em-dash</i>; lineetta emme) e quattro trattini se la lineetta
        &egrave; lunga 4&ndash;5 lettere (una <i>long dash</i>; lineetta lunga).
        Non lasciare spazi prima e dopo, anche se sembra che nell'immagine del
        libro originale ci siano degli spazi.
    </li>
    <li><i>Parole o nomi intenzionalmente omessi o censurati</i>.
    <br>Se nell'immagine sono rappresentati da una lineetta, correggili
        con due trattini o con quattro trattini come descritto per le lineette
        emme &amp; le lineette lunghe. Quando la lineetta rappresenta una parola,
        lasciamo lo spazio appropriato attorno come se fosse veramente una parola.
        Se &egrave; solo parte di una parola, allora non lasciare nessun
        spazio&mdash;uniscila con il resto della parola.
    </li>
  </ol>
<p>Vedi anche le regole per i trattini e le lineette <a href="#eol_hyphen">a fine riga</a> e
   <a href="#eop_hyphen">a fine pagina</a>.
</p>
<!-- END RR -->

<p><b>Esempi</b>&mdash;Lineette, Trattini, e Segni Meno:
</p>

<table width="100%" align="center" border="1" cellpadding="4" cellspacing="0" summary="Hyphens and Dashes Esempi">
  <tbody>
    <tr>
      <th valign="top" bgcolor="cornsilk">Immagine originale:</th>
      <th valign="top" bgcolor="cornsilk">Testo corretto:</th>
      <th valign="top" bgcolor="cornsilk">Tipo</th>
    </tr>
    <tr>
      <td valign="top">semi-detached</td>
      <td valign="top"><kbd>semi-detached</kbd></td>
      <td>Trattino</td>
    </tr>
    <tr>
      <td valign="top">three- and four-part harmony</td>
      <td valign="top"><kbd>three- and four-part harmony</kbd></td>
      <td>Trattini</td>
    </tr>
    <tr>
      <td valign="top">discoveries which the Crus-<br>
        aders made and brought home with</td>
      <td valign="top"><kbd>discoveries which the Crusaders<br>
        made and brought home with</kbd></td>
      <td>Trattino</td>
    </tr>
    <tr>
      <td valign="top">factors which mold char-<br>
        acter&mdash;environment, training and heritage,</td>
      <td valign="top"><kbd>factors which mold character--environment,<br>
        training and heritage,</kbd></td>
      <td>Trattino &amp; Lineetta emme</td>
    </tr>
    <tr>
      <td valign="top">See pages 21&ndash;25</td>
      <td valign="top"><kbd>See pages 21-25</kbd></td>
      <td>Lineetta enne</td>
    </tr>
    <tr>
      <td valign="top">It was &ndash;14&deg;C outside.</td>
      <td valign="top"><kbd>It was -14&deg;C outside.</kbd></td>
      <td>Lineetta enne</td>
    </tr>
    <tr>
      <td valign="top">X &ndash; Y = Z</td>
      <td valign="top"><kbd>X - Y = Z</kbd></td>
      <td>Lineetta enne</td>
    </tr>
    <tr>
      <td valign="top">2&ndash;1/2</td>
      <td valign="top"><kbd>2-1/2</kbd></td>
      <td>Lineetta enne</td>
    </tr>
    <tr>
      <td valign="top">&mdash;A plague on both<br> your houses!&mdash;I am dead.</td>
      <td valign="top"><kbd>--A plague on both<br> your houses!--I am dead.</kbd></td>
      <td>Lineetta emme</td>
    </tr>
    <tr>
      <td valign="top">sensations&mdash;sweet, bitter, salt, and sour<br>
        &mdash;if even all of these are simple tastes. What</td>
      <td valign="top"><kbd>sensations--sweet, bitter, salt, and sour--if<br>
        even all of these are simple tastes. What</kbd></td>
      <td>Lineetta emme</td>
    </tr>
    <tr>
      <td valign="top">senses&mdash;touch, smell, hearing, and sight&mdash;<br>
        with which we are here concerned,</td>
      <td valign="top"><kbd>senses--touch, smell, hearing, and sight--with<br>
        which we are here concerned,</kbd></td>
      <td>Lineetta emme</td>
    </tr>
    <tr>
      <td valign="top">It is the east, and Juliet is the sun&mdash;!</td>
      <td valign="top"><kbd>It is the east, and Juliet is the sun--!</kbd></td>
      <td>Lineetta emme</td>
    </tr>
    <tr>
      <td valign="top"><img src="../dashes.png" width="300" height="28" alt=""></td>
      <td valign="top"><kbd>how a--a--cannon-ball goes----"</kbd></td>
      <td>Lineetta emme, Trattino,<br> &amp; Lineetta lunga</td>
    </tr>
    <tr>
      <td valign="top">"Three hundred&mdash;&mdash;" "years," she was going to<br>
        say, but the left-hand cat interrupted her.</td>
      <td valign="top"><kbd>"Three hundred----" "years," she was going to<br>
        say, but the left-hand cat interrupted her.</kbd></td>
      <td>Lineetta lunga</td>
    </tr>
    <tr>
      <td valign="top">As the witness Mr. &mdash;&mdash; testified,</td>
      <td valign="top"><kbd>As the witness Mr. ---- testified,</kbd></td>
      <td>Lineetta lunga</td>
    </tr>
    <tr>
      <td valign="top">As the witness Mr. S&mdash;&mdash; testified,</td>
      <td valign="top"><kbd>As the witness Mr. S---- testified,</kbd></td>
      <td>Lineetta lunga</td>
    </tr>
    <tr>
      <td valign="top">the famous detective of &mdash;&mdash;B Baker St.</td>
      <td valign="top"><kbd>the famous detective of ----B Baker St.</kbd></td>
      <td>Lineetta lunga</td>
    </tr>
    <tr>
      <td valign="top">&ldquo;You &mdash;&mdash; Yankee&rdquo;, she yelled.</td>
      <td valign="top"><kbd>"You ---- Yankee", she yelled.</kbd></td>
      <td>Lineetta lunga</td>
    </tr>
    <tr>
      <td valign="top">&ldquo;I am not a d&mdash;d Yankee&rdquo;, he replied.</td>
      <td valign="top"><kbd>"I am not a d--d Yankee", he replied.</kbd></td>
      <td>Lineetta emme</td>
    </tr>
  </tbody>
</table>
<p class="backtotop"><a href="#top">Torna all'inizio</a></p>


<h3><a name="eol_hyphen">Andare a capo con trattino o lineetta a fine riga</a></h3>
<p>Quando alla fine di una riga appare un trattino, riunisci le due parti
   della parola divisa a capo a fine riga. Rimuovi il trattino quando le unisci,
   a meno che sia una parola composta con il trattino come well-meaning. Vedi
   <a href="#em_dashes">Lineette, Trattini, e Segni Meno</a>
   per vedere esempi di ogni tipo. Lascia la parola che &egrave; stata riunita
   sulla riga superiore, e vai a capo dopo la parola per conservare la
   formattazione della riga&mdash;questo rende la correzione pi&ugrave; semplice
   per i volontari nei turni successivi. Se la parola &egrave; seguita
   da punteggiatura, porta anche la punteggiatura sulla riga superiore.
</p>
<p>Parole come to-day e to-morrow, che ora solitamente non scriviamo con il
   trattino, venivano spesso scritte con il trattino nei vecchi libri su cui
   lavoriamo. Lasciale col trattino nel modo in cui ha fatto l'autore. Se non sei
   certo che la parola abbia o meno il trattino, lascia il trattino,
   metti un <kbd>*</kbd> dopo il trattino, e riunisci la parola in questo modo:
   <kbd>to-*day</kbd>. L'asterisco attirer&agrave; l'attenzione del post-correttore,
   che ha accesso a tutte le pagine e pu&ograve; determinare il modo in cui l'autore
   scriveva normalmente la parola.
</p>
<p>Analogamente, se una lineetta &egrave; all'inizio o alla fine di una riga del
   testo prodotto dall'OCR, uniscila con l'altra riga in modo che non ci siano
   attorno spazi o interruzioni di riga. In ogni caso, se l'autore ha usato una
   lineetta per cominciare o terminare un paragrafo o un verso di poesia,
   lascialo com'&egrave;, senza riunirla alla riga successiva.
   Vedi <a href="#em_dashes">Lineette, Trattini e Segni Meno</a> per degli esempi.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Torna all'inizio</a></p>


<h3><a name="eop_hyphen">Andare a capo con trattino o lineetta a fine pagina</a></h3>
<p>Correggi i trattini e le lineette alla fine della pagina lasciando il
   trattino o la lineetta alla fine dell'ultima riga e segnalalo con un <kbd>*</kbd>
   dopo la lineetta. Per esempio:
</p>
<table width="100%" align="center" border="1" cellpadding="4" cellspacing="0" summary="End-of-page Hyphenation example">
  <tbody>
    <tr><th align="left" bgcolor="cornsilk">Immagine originale:</th></tr>
    <tr>
      <td valign="top">something Pat had already become accus-</td>
    </tr>
    <tr><th align="left" bgcolor="cornsilk">Testo corretto:</th></tr>
    <tr>
      <td valign="top"><kbd>something Pat had already become accus-*</kbd></td>
    </tr>
  </tbody>
</table>
<p>Nelle pagine che cominciano con una parte di una parola che continua dalla
   pagina precedente o con una lineetta, metti un <kbd>*</kbd> prima della parte
   della parola o della lineetta.
   Per continuare l'esempio precedente:
</p>
<table width="100%" align="center" border="1" cellpadding="4" cellspacing="0" summary="Start-of-page Hyphenation example">
  <tbody>
    <tr><th align="left" bgcolor="cornsilk">Immagine originale:</th></tr>
    <tr>
      <td valign="top">tomed to from having to do his own family</td>
    </tr>
    <tr><th align="left" bgcolor="cornsilk">Testo corretto:</th></tr>
    <tr>
      <td valign="top"><kbd>*tomed to from having to do his own family</kbd></td>
    </tr>
  </tbody>
</table>
<p>Questi asterischi indicano al post-correttore che la parola deve essere riunita
   quando le pagine vengono ricomposte per produrre il libro elettronico finale.
   Per favore, non riunire i frammenti da una pagina all'altra di tua iniziativa.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Torna all'inizio</a></p>


<h3><a name="period_p">Puntini di sospensione &quot;...&quot; (Ellipsis)</a></h3>
<p>Le regole per l'inglese e le lingue diverse dall'inglese (LOTE) sono diverse.
</p>
<p><b>INGLESE</b>: I puntini di sospensione devono essere in numero di tre.
   Per quanto riguarda la spazieggiatura, all'interno di una frase tratta
   i tre puntini come se fossero una parola (per es. di solito con uno
   spazio prima dei 3 puntini e uno spazio dopo). Alla fine della frase
   tratta i puntini di sospensione come fossero un segno di punteggiatura
   finale, senza spazio prima dei puntini.
</p>
<p>Nota che ci sar&agrave; anche il segno di punteggiatura alla fine della frase,
   cos&igrave; se la frase termine con un punto ci saranno 4 puntini in totale.
   Rimuovi i puntini in eccesso, se ci sono, o aggiungine altri, se necessario,
   per portare il numero a tre (o quattro) come appropriato.
   Un buon indizio che ci troviamo alla fine di una frase &egrave; l'uso della
   lettera maiuscola all'inizio della parola successiva, o la presenza di un
   segno di punteggiatura finale (per es., un punto di domanda o un punto esclamativo).
</p>
<p><b>LOTE:</b> (Lingue diverse dall'Inglese) Usa la regola generale "Segui
   attentamente lo stile usato nella pagina stampata." In particolare,
   inserisci spazi, se ci sono spazi prima o tra i puntini, e usa lo stesso
   numero di puntini che appaiono nell'immagine. A volte la pagina stampata
   non &egrave; chiara; in quel caso, inserisci un <kbd>[**non chiaro]</kbd>
   per attirare l'attenzione del post-correttore. (Nota: il Post-correttore
   dovrebbe sostituire gli spazi regolari con spazi senza interruzioni.)
</p>
<!-- END RR -->
<p>Esempi per l'Inglese:
</p>
<table width="100%" align="center" border="1" cellpadding="4" cellspacing="0" summary="Ellipses Examples">
  <tbody>
    <tr>
      <th valign="top" bgcolor="cornsilk">Immagine originale:</th>
      <th valign="top" bgcolor="cornsilk">Testo corretto:</th>
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
<p class="backtotop"><a href="#top">Torna all'inizio</a></p>


<h3><a name="contract">Contrazioni</a></h3>
<p>In Inglese, rimuovi gli spazi nelle contrazioni. Per esempio,
   <kbd>would&nbsp;n't</kbd> deve essere corretto come <kbd>wouldn't</kbd>
   e <kbd>'t&nbsp;is</kbd> come <kbd>'tis</kbd>.
</p>
<p>Questa era una convenzione di stampa del 19. secolo per la quale
   lo spazio veniva mantenuto per indicare che 'would' e 'not' erano
   in origine parole separate. A volte &egrave; anche un prodotto dell'OCR.
   Rimuovi gli spazi eccedenti se &egrave; questo il caso.
</p>
<p>Alcuni Project Managers potrebbero specificare nei
   <a href="#comments">Project Comments</a> (Commenti al progetto)
   di non rimuovere gli spazi eccedenti, in particolare nel caso di libri che
   contengono slang, dialetto o poesia.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Torna all'inizio</a></p>


<h3><a name="fract_s">Frazioni</a></h3>
<p>Correggi le frazioni in questo modo: <kbd>&frac14;</kbd> diventa <kbd>1/4</kbd>,
   e <kbd>2&frac12;</kbd> diventa <kbd>2-1/2</kbd>.
   Il trattino impedisce che la parte intera e fratta vengano
   separate quando le righe vengono reimpaginate durante la post-correzione.
   A meno che sia specificamente richiesto nei <a href="#comments">Project Comments</a>
   (Commenti al progetto), per favore non usare i reali simboli delle frazioni.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Torna all'inizio</a></p>


<h3><a name="a_chars">Caratteri accentati/non-ASCII</a></h3>
<p>Correggi questi caratteri usando il corrispondente carattere UTF-8. Per caratteri
   che non sono in Unicode, vedi le istruzioni del Project Manager nei
   <a href="#comments">Project Comments</a> (Commenti al progetto).
   Se non appartengono alla tua tastiera, vedi <a href="#insert_char">Inserimento
   dei caratteri speciali</a> per informazioni su come inserire questi caratteri
   nella correzione.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Torna all'inizio</a></p>


<h3><a name="d_chars">Caratteri con segni diacritici</a></h3>
<p>In alcuni progetti, troverai caratteri con segni speciali sotto o sopra
   il normale carattere latino A...Z. Questi segni sono detti <i>segni diacritici</i>, e
   indicano una pronuncia speciale per questo carattere.
</p>
<p>Se questo tipo di carattere non esiste in Unicode, deve essere inserito usando
   <i>segni diacritici combinati</i>: questi sono simboli Unicode che non possono
   apparire soli, ma appaiono sopra (o sotto) la lettera dopo la quale sono
   posti. Possono essere inseriti inserendo prima la lettera base, e poi
   il segno combinato, usando applicazioni e programmi citati nella sezione
   <a href="#insert_char">Inserimento di caratteri speciali</a>.
</p>
<p>In alcuni sistemi, i segni diacritici non appaiono esattamente dove dovrebbero,
   ma, per esempio, sono spostati a destra. Dovrebbero comunque essere usati,
   poich&eacute; utenti con altri sistemi li vedranno correttamente. In ogni
   caso, se, per qualsiasi motivo, non riesci a vederli o ad inserire i caratteri
   combinati appropriatamente, evidenzia questa lettera con una [**nota].
   Nota che esistono anche le <i>Spacing modifier letters</i>; queste
   non dovrebbero essere usati.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Torna all'inizio</a></p>


<h3><a name="f_chars">Caratteri non Latini</a></h3>
<p>Alcuni progetti contengono testo stampato in caratteri non-Latini; cio&egrave;,
   caratteri diversi dai Latini A...Z&mdash;per esempio, caratteri Greci, Cirillici
   (usati in Russo, Slavo, e altre lingue), Ebraici, o Arabi.
</p>
<p>Questi caratteri devono essere inseriti nel testo proprio come vengono inseriti
   i caratteri latini. (<b>SENZA traslitterazione!</b>)
</p>
<p>Se un documento &egrave; scritto interamente in un alfabeto non Latino, &egrave;
   raccomandata l'installazione di un driver per la tastiera che supporti la lingua.
   Consulta il manuale del tuo sistema operativo per istruzioni su come farlo.
</p>
<p>Se la scrittura appare solo occasionalmente, puoi usare un programma separato
   per inserirla. Vedi <a href="#insert_char">Inserimento di Caratteri Speciali</a>
   per vedere alcuni programmi.
</p>
<p>Se non sei sicuro di un carattere o di un accento, segnalo con una [**nota] per
   portarlo all'attenzione del correttore successivo o del post-correttore.
</p>
<p>Per alfabeti che non possono essere inseriti facilmente, come l'Arabo, identificalo
   con la marcatura appropriata: <kbd>[Arabic:&nbsp;**]</kbd>.
   Includi <kbd>**</kbd> affinch&eacute; il post-correttore possa occuparsene dopo.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Torna all'inizio</a></p>


<h3><a name="supers">Apici/Esponenti</a></h3>
<p>Nei vecchi libri spesso si abbreviavano le parole in contrazioni, e le si scriveva in
   apice. Correggile inserendo un singolo circonflesso (<kbd>^</kbd>) seguito dal
   testo in apice. Se l'apice &egrave; pi&ugrave; lungo di un carattere,
   allora in aggiunta delimita il testo con le parentesi graffe <kbd>{</kbd> e <kbd>}</kbd>.
   Per esempio:
</p>
<table width="100%" align="center" border="1" cellpadding="4" cellspacing="0" summary="Superscripts example">
  <tbody>
    <tr><th align="left" bgcolor="cornsilk">Immagine originale:</th></tr>
    <tr>
      <td valign="top">Gen<sup>rl</sup> Washington defeated L<sup>d</sup> Cornwall's army.</td>
    </tr>
    <tr><th align="left" bgcolor="cornsilk">Testo corretto:</th></tr>
    <tr>
      <td valign="top"><kbd>Gen^{rl} Washington defeated L^d Cornwall's army.</kbd></td>
    </tr>
  </tbody>
</table>
<p>Se l'apice rappresenta un rimando di una nota a pi&egrave; pagina, vedi invece la
   sezione <a href="#footnotes">Note a pi&egrave; di pagina</a>.
</p>
<p>Il Project Manager potrebbe specificare nei <a href="#comments">Project Comments</a>
   (Commenti al progetto) che il testo in apice sia segnalato in modo diverso.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Torna all'inizio</a></p>


<h3><a name="subscr">Pedici</a></h3>
<p>Il testo in pedice si trova spesso nelle opere scientifiche, ma non &egrave;
   comune in altri tipi di testo. Rendi il testo in pedice inserendo un trattino
   basso <kbd>_</kbd> e circonda il testo con parentesi graffe <kbd>{</kbd> e <kbd>}</kbd>.
   Per esempio:
</p>
<table width="100%" align="center" border="1" cellpadding="4" cellspacing="0" summary="Subscripts example">
  <tbody>
    <tr><th align="left" bgcolor="cornsilk">Immagine originale:</th></tr>
    <tr>
      <td valign="top">H<sub>2</sub>O.</td>
    </tr>
    <tr><th align="left" bgcolor="cornsilk">Testo corretto:</th></tr>
    <tr>
      <td valign="top"><kbd>H_{2}O.</kbd></td>
    </tr>
  </tbody>
</table>
<!-- END RR -->
<p class="backtotop"><a href="#top">Torna all'inizio</a></p>


<h3><a name="drop_caps">Lettera maiuscola iniziale grande e decorata (Capolettera)</a></h3>
<p>Correggi la prima lettera di grandi dimensioni e graficamente elaborata di un
   capitolo, sezione o paragrafo come se fosse una lettera normale. Vedi anche la
   sezione <a href="#chap_head">Titoli dei capitoli</a> delle Regole di correzione.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Torna all'inizio</a></p>


<h3><a name="small_caps">Parole in maiuscoletto</a></h3>
<p>Se il testo &egrave; in <span style="font-variant: small-caps">Maiuscoletto</span>,
   (lettere maiuscole che sono pi&ugrave; piccole delle lettere maiuscole normali)
   fai solo la correzione dei caratteri.
   Non preoccuparti dei cambiamenti nell'altezza tra maiuscolo e minuscolo.
   Se il testo riconosciuto &egrave; gi&agrave; TUTTO MAIUSCOLO, Misto, o tutto
   minuscolo, lascialo TUTTO MAIUSCOLO, Misto, o tutto minuscolo.
   Potresti trovare il maiuscoletto circondato da <kbd>&lt;sc&gt;</kbd> e
   <kbd>&lt;/sc&gt;</kbd>; vedi <a href="#formatting">Formattazione</a> in questo caso.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Torna all'inizio</a></p>


<table width="100%" border="0" cellspacing="0" cellpadding="6" summary="Paragraph-level proofreading">
  <tbody>
    <tr>
      <td bgcolor="silver"><h2 style="margin-bottom: 0; margin-top: 0;">Correzione a livello del paragrafo:</h2></td>
    </tr>
  </tbody>
</table>


<h3><a name="line_br">Interruzioni di riga/Testo a capo</a></h3>
<p><b>Mantieni tutte le interruzioni di riga</b> in modo che gli altri volontari
   possano facilmente paragonare le righe del testo alle righe dell'immagine.
   Fai specialmente attenzione quando riunisci le <a href="#eol_hyphen">parole a capo</a>
   o muovi le parole attorno alle <a href="#em_dashes">lineette</a>. Se il correttore
   precedente ha rimosso le interruzioni di riga, per favore ripristinale in modo che
   coincidano di nuovo con l'immagine.
</p>
<!-- END RR -->
<!-- We should have an example right here for this. -->
<p class="backtotop"><a href="#top">Torna all'inizio</a></p>


<h3><a name="chap_head">Titoli dei Capitoli</a></h3>
<p>Correggi i titoli dei capitoli come appaiono nell'immagine.
</p>
<p>Il titolo del capitolo si pu&ograve; trovare un po' pi&ugrave; in basso nella
   pagina rispetto all'<a href="#page_hf">intestazione della pagina</a>
   e pu&ograve; non avere un numero sulla stessa riga. I titoli dei capitoli
   sono spesso stampati in maiuscolo; se &egrave; cos&igrave;, mantieni il maiuscolo.
</p>
<p>Fai attenzione perch&eacute; potrebbero mancare delle virgolette all'inizio
   del primo paragrafo, se gli editori non l'hanno incluso o l'OCR non l'ha
   riconosciuto a causa di una lettera iniziale grande nell'immagine. Se l'autore
   ha cominciato il paragrafo con un dialogo, inserisci le virgolette.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Torna all'inizio</a></p>


<h3><a name="para_space">Spaziatura dei paragrafi/Rientro</a></h3>
<p>Inserisci una riga vuota prima dell'inizio di un paragrafo, anche se comincia
   all'inizio di una pagina. Non devi far rientrare l'inizio del paragrafo,
   ma se &egrave; gi&agrave; rientrato, non ti preoccupare di rimuovere quegli
   spazi&mdash;pu&ograve; essere fatto automaticamente in post-correzione.
</p>
<p>Vedi l'immagine e il testo nella sezione <a href="#para_side">Note a margine</a>
   per un esempio.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Torna all'inizio</a></p>


<h3><a name="page_hf">Intestazioni/Pi&egrave; pagina</a></h3>
<p>Rimuovi dal testo le intestazioni e i pi&egrave; di pagina, ma <em>non</em> le
   <a href="#footnotes">Note a pi&egrave; di pagina</a>.
</p>
<p>L'intestazione di pagina &egrave; solitamente nella parte superiore dell'immagine
   e ha un numero di pagina accanto. Le intestazioni potrebbero essere le stesse per
   tutto il libro (spesso il titolo del libro e il nome dell'autore), possono essere
   le stesse per ogni capitolo (spesso il numero del capitolo), o possono essere
   diverse per ogni pagina (descrivendo l'azione di quella pagina). Rimuovile tutte,
   comunque, compreso il numero di pagina. Le righe vuote in eccesso che non sono
   nell'immagine devono essere rimosse eccetto dove siano state aggiunte
   intenzionalmente per la correzione. Ma le righe vuote eccedenti alla fine
   della pagina possono essere lasciate&mdash;vengono infatti rimosse
   quando salvi la pagina.
</p>
<p>I pi&egrave; di pagina sono nella parte inferiore dell'immagine e possono
   avere il numero di pagina o altri segni estranei che non fanno parte di ci&ograve;
   che l'autore ha scritto.
</p>
<!-- END RR -->

<p>Il <a href="#chap_head">titolo di un capitolo</a> comincia solitamente pi&ugrave;
   in basso nella pagina e non ha un numero di pagina sulla stessa riga. Vedi
   l'esempio sotto:
</p>
<table width="100%" align="center" border="1" cellpadding="4" cellspacing="0" summary="Page Headers and Footers">
  <tbody>
    <tr><th align="left" bgcolor="cornsilk">Immagine originale:</th></tr>
    <tr align="left">
      <td width="100%" valign="top"><img src="../foot.png" alt="" width="500" height="860"><br>
      </td>
    </tr>
    <tr><th align="left" bgcolor="cornsilk">Testo corretto:</th></tr>
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
<p class="backtotop"><a href="#top">Torna all'inizio</a></p>


<h3><a name="illust">Illustrazioni</a></h3>
<p>Ignora le illustrazioni, ma correggi le didascalie come sono stampate, mantenendo
   le interruzioni di riga. Se la didascalia cade a met&agrave; di un paragrafo,
   usa delle righe vuote per separarla dal resto del testo. Il testo che potrebbe
   essere (parte di) una didascalia deve essere incluso, come per esempio
  "Vedi pagina 66" o un titolo all'interno dei margini dell'illustrazione.
</p>
<p>La maggior parte delle pagine con illustrazioni ma senza testo avranno gi&agrave;
   inserito <kbd>[Blank Page]</kbd>. Lascia questa dicitura com'&egrave;.
</p>
<!-- END RR -->

<table width="100%" align="center" border="1" cellpadding="4" cellspacing="0" summary="Illustration example">
  <tbody>
    <tr>
      <th align="left" bgcolor="cornsilk">Immagine originale:</th>
    </tr>
    <tr align="left">
      <td width="100%" valign="top"> <img src="../illust.png" alt="" width="500" height="525"> <br>
      </td>
    </tr>
    <tr>
      <th align="left" bgcolor="cornsilk">Testo corretto:</th>
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
      <th align="left" bgcolor="cornsilk">Immagine originale: (Illustrazione a met&agrave; di un paragrafo)</th>
    </tr>
    <tr align="left">
      <td width="100%" valign="top"> <img src="../illust2.png" alt="" width="500" height="514"> <br>
      </td>
    </tr>
    <tr>
      <th align="left" bgcolor="cornsilk">Testo corretto:</th>
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
<p class="backtotop"><a href="#top">Torna all'inizio</a></p>


<h3><a name="footnotes">Note a pi&egrave; di pagina/Note di chiusura</a></h3>
<p>Correggi le note a pi&egrave; di pagina lasciando il testo della nota
   alla fine della pagina e mettendo un rimando dove viene fatto il riferimento
   nel testo.
</p>
<p>Nel testo principale, i caratteri che indicano il piazzamento di una nota devono
   essere circondati da parentesi quadre (<kbd>[</kbd> e <kbd>]</kbd>) e inseriti
   vicino alla parola annotata<kbd>[1]</kbd> o al suo segno di punteggiatura,<kbd>[2]</kbd>
   come mostrato nell'immagine e nei due esempi di questa frase.
   I rimandi delle note a pi&egrave; pagina possono essere numeri, lettere o simboli.
   Quando le note sono segnalate con un simbolo o una serie di simboli
   (*, &dagger;, &Dagger;, &sect;, ecc.) li sostituiamo tutti con <kbd>[*]</kbd> nel testo,
   e <kbd>*</kbd> anche nella nota a pi&egrave; pagina.
</p>
<p>A fondo pagina, correggi il testo della nota come &egrave; stampato,
   mantenendo le interruzioni di riga. Assicurati che il rimando
   usato prima della nota sia lo stesso usato nel testo dove la nota era
   inserita. Usa solo il carattere per il rimando, senza parentesi o
   altra punteggiatura.
</p>
<p>Metti ogni nota a pi&egrave; pagina su una riga diversa, nell'ordine in
   cui appare, con una riga vuota prima di ognuna.
</p>
<!-- END RR -->
<p>Non includere le righe orizzontali che separano le note a pi&egrave;
   pagina dal testo principale.
</p>
<p>Le <b>Note di chiusura</b> sono note che sono state raggruppate assieme
   alla fine di un capitolo o alla fine di un libro, invece che in fondo ad
   ogni pagina. Sono corrette nello stesso modo delle note a pi&egrave; di pagina.
   Dove trovi un riferimento ad una nota di chiusura nel testo, semplicemente
   circondala con <kbd>[</kbd> e <kbd>]</kbd>. Se stai correggendo la pagina con
   le note di chiusura, inserisci una riga vuota dopo ogni nota in modo che
   sia chiaro dove ognuna comincia e termina.
</p>
<!-- Need an example of Endnotes, maybe? Good idea!-->

<p>Le note a pi&egrave; di pagina nelle <a href="#tables">Tabelle</a> devono
   rimanere dove sono nell'immagine originale.
</p>

<table width="100%" border="1" cellpadding="4" cellspacing="0" align="center" summary="Footnote Example">
  <tbody>
    <tr>
      <th valign="top" align="left" bgcolor="cornsilk">Immagine originale:</th>
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
      <th valign="top" align="left" bgcolor="cornsilk">Testo corretto:</th>
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
    <tr><th align="left" bgcolor="cornsilk">Testo corretto:</th></tr>
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
<p class="backtotop"><a href="#top">Torna all'inizio</a></p>


<h3><a name="para_side">Note a margine</a></h3>
<p>Alcuni libri hanno breve descrizioni del paragrafo lungo il margine del testo.
   Queste sono dette note a margine. Correggi il testo delle note a margine come
   &egrave; stampato, mantenendo le interruzioni di riga (allo stesso tempo
   tratta <a href="#eol_hyphen">le parole a capo e le lineette</a> normalmente).
   Lascia una riga vuota prima e dopo la nota a margine in modo che possa essere
   distinta dal testo attorno. L'OCR potrebbe piazzare le note a margine ovunque
   nella pagina, e potrebbe mischiare il testo della nota a margine con il resto
   del testo. Separali in modo che il testo della nota a margine sia tutto assieme,
   ma non preoccuparti della posizione delle note a margine nella pagina.
</p>
<!-- END RR -->

<table width="100%" align="center" border="1" cellpadding="4" cellspacing="0" summary="Sidenotes example">
  <tbody>
    <tr valign="top">
      <th align="left" bgcolor="cornsilk">Immagine originale:</th>
    </tr>
    <tr valign="top">
      <td width="100%" align="left"><img src="../side.png" alt="" width="550" height="800"><br>
      </td>
    </tr>
    <tr valign="top">
      <th align="left" bgcolor="cornsilk">Testo corretto:</th>
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
<p class="backtotop"><a href="#top">Torna all'inizio</a></p>


<h3><a name="mult_col">Colonne multiple</a></h3>
<p>Correggi il testo che &egrave; stampato in colonne multiple in una singola colonna.
   Metti prima il testo della colonna pi&ugrave; a sinistra, il testo dell'altra
   colonna sotto a questo, e cos&igrave; via. Non segnare il punto in cui le colonne
   erano divise, semplicemente uniscile. Vedi in fondo all'esempio delle
   <a href="#para_side">Note a margine</a> per un esempio di colonne multiple.
</p>
<p>Vedi anche le sezioni <a href="#bk_index">Indici</a> e
   <a href="#tables">Tabelle</a> delle Regole di Correzione.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Torna all'inizio</a></p>


<h3><a name="tables">Tabelle</a></h3>
<p>Il compito di un correttore &egrave; di assicurarsi che tutte le informazioni
   della tavola siano rese correttamente. Separa i contenuti con degli spazi come
   necessario, ma non preoccuparti dell'allineamento preciso.
   Mantieni le interruzioni di riga (ma tratta le <a href="#eol_hyphen">Parole a capo
   e le lineette</a> come al solito). Ignora i punti o altri caratteri usati per
   allineare gli elementi.
</p>
<p>Le <b>Note a pi&egrave; di pagina</b> nelle tabelle dovrebbero restare dove
   sono nell'immagine. Vedi <a href="#footnotes">Note a pi&egrave; di pagina</a>
   per dettagli.
</p>
<!-- END RR -->
<table width="100%" align="center" border="1" cellpadding="4" cellspacing="0" summary="Table Example">
  <tbody>
    <tr><th align="left" bgcolor="cornsilk">Immagine originale:</th></tr>
    <tr align="left">
      <td width="100%" valign="top"> <img src="../table2.png" alt="" width="500" height="304"><br>
      </td>
    </tr>
    <tr><th align="left" bgcolor="cornsilk">Testo corretto:</th></tr>
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
    <tr><th align="left" bgcolor="cornsilk">Immagine originale:</th></tr>
    <tr align="left">
      <td width="100%" valign="top"> <img src="../table3.png" alt="" width="480" height="231"><br>
      </td>
    </tr>
    <tr><th align="left" bgcolor="cornsilk">Testo corretto:</th></tr>
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
<p class="backtotop"><a href="#top">Torna all'inizio</a></p>


<h3><a name="poetry">Poesia/Epigrammi</a></h3>
<p>Inserisci una riga vuota all'inizio della poesia o epigramma e un'altra
   alla fine, in modo che gli impaginatori possano chiaramente vedere l'inizio
   e la fine. Lascia ogni riga allineata a sinistra e mantieni le interruzioni
   di riga. Inserisci una riga vuota tra le strofe, quando c'&egrave; nell'immagine.
</p>
<p>I <a href="#line_no">numeri di riga</a> nelle poesie devono essere mantenuti.
</p>
<p>Controlla i <a href="#comments">Project Comments</a> (Commenti al progetto)
   dello specifico progetto che stai correggendo.
</p>
<!-- END RR -->

<table width="100%" align="center" border="1" cellpadding="4" cellspacing="0" summary="Poetry Example">
  <tbody>
    <tr><th align="left" bgcolor="cornsilk">Immagine originale:</th></tr>
    <tr align="left">
      <td width="100%" valign="top"> <img src="../poetry2.png" alt="" width="480" height="385"> <br>
      </td>
    </tr>
    <tr><th align="left" bgcolor="cornsilk">Testo corretto:</th></tr>
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
<p class="backtotop"><a href="#top">Torna all'inizio</a></p>


<h3><a name="line_no">Numeri di riga</a></h3>
<p>I numeri di riga sono comuni in poesia, e solitamente appiano vicino al margine
   ogni quinta o decima riga. Mantieni i numeri di riga, usando qualche spazio
   per separarli dall'altro testo sulla riga in modo che gli impaginatori possano
   facilmente trovarli. Poich&eacute; la poesia non viene re-impaginata nella versione
   elettronica, i numeri di riga saranno utili per i lettori.
</p>
<!-- END RR -->
<!-- We need an example image and text for this. -->
<p class="backtotop"><a href="#top">Torna all'inizio</a></p>


<h3><a name="next_word">Parola Singola a Fondo Pagina/Richiamo</a></h3>
<p>Cancella la parola, anche se &egrave; la seconda met&agrave; di una parola
   divisa tra due pagine o composta.
</p>
<p>In alcuni libri pi&ugrave; antichi, la parola singola a fondo pagina (detta
   "Richiamo", solitamente stampata vicino al margine destro) indica la prima
   parola della pagina successiva di un libro (detta "incipit"). Era usata per
   ricordare allo stampatore di stampare l'altro verso corretto, per facilitare
   gli aiutanti degli stampatori a organizzare le pagine prima della rilegatura,
   e per aiutare il lettore a evitare di girare pi&ugrave; di una pagina alla volta.
</p>
<!-- END RR -->
<!-- We need an example here! -->
<p class="backtotop"><a href="#top">Torna all'inizio</a></p>


<table width="100%" border="0" cellspacing="0" cellpadding="6" summary="Page-level proofreading">
  <tbody>
    <tr>
      <td bgcolor="silver"><h2 style="margin-bottom: 0; margin-top: 0;">Correzione a livello della pagina:</h2></td>
    </tr>
  </tbody>
</table>


<h3><a name="blank_pg">Pagina vuota</a></h3>
<p>La maggior parte delle pagine vuote, o pagine con un'illustrazione ma senza testo,
   riporteranno gi&agrave; la dicitura <kbd>[Blank Page]</kbd>. Lascia la dicitura
   come &egrave;. Se la pagina &egrave; vuota, ma [Blank Page] non c'&egrave;,
   non &egrave; necessario aggiungerlo.
</p>
<p>Se c'&egrave; del testo nell'area di correzione e un'immagine vuota, o se c'&egrave;
   il testo dell'immagine ma non c'&egrave; testo nella finestra, segui le istruzioni
   per <a href="#bad_image">Immagine danneggiata</a> o
   <a href="#bad_text">Testo danneggiato</a>.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Torna all'inizio</a></p>


<h3><a name="title_pg">Copertina/Retro</a></h3>
<p>Correggi il testo come &egrave; stampato sulla pagina, sia tutto maiuscolo,
   sia maiuscolo e minuscolo, ecc., includendo l'anno di pubblicazione o
   il copyright.
</p>
<p>I libri pi&ugrave; antichi spesso hanno la prima lettera come un grande
   disegno decorato&mdash;correggila solo come la lettera.
</p>
<!-- END RR -->

<table width="100%" align="center" border="1" cellpadding="4" cellspacing="0" summary="Title Page Example">
  <tbody>
    <tr>
      <th align="left" bgcolor="cornsilk">Immagine originale:</th>
    </tr>
    <tr align="left">
      <td width="100%" valign="top"><img src="../title.png" width="500" height="520" alt=""><br>
      </td>
    </tr>
    <tr>
      <th align="left" bgcolor="cornsilk">Testo corretto:</th>
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
<p class="backtotop"><a href="#top">Torna all'inizio</a></p>


<h3><a name="toc">Tavola dei Contenuti/Sommario</a></h3>
<p>Correggi la Tavola dei contenuti/Sommario come stampato nel libro, che sia
   tutto in maiuscolo, maiuscolo e minuscolo, ecc. Se ci sono caratteri in
   <span style="font-variant: small-caps">Maiuscoletto</span>,
   vedi le regole per <a href="#small_caps">Maiuscoletto</a>.
</p>
<p>Ignora i punti o altri caratteri usati per allineare i numeri di pagina.
   Questi saranno rimossi pi&ugrave; tardi nel processo.
</p>
<!-- END RR -->

<table width="100%" align="center" border="1" cellpadding="4" cellspacing="0" summary="Table of Contents example">
  <tbody>
    <tr>
      <th align="left" bgcolor="cornsilk">Immagine originale:</th>
    </tr>
    <tr align="left">
      <td width="100%" valign="top"><img src="../tablec.png" alt="" width="500" height="650">
      </td>
    </tr>
    <tr>
      <th align="left" bgcolor="cornsilk">Testo corretto:</th>
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
<p class="backtotop"><a href="#top">Torna all'inizio</a></p>


<h3><a name="bk_index">Indici</a></h3>
<p>Non c'&egrave; bisogno di allineare i numeri di pagina nelle pagine degli
   indici come appaiono nell'immagine; assicurati che i numeri e la punteggiatura
   coincidano con l'immagine e mantieni le interruzioni di riga.
</p>
<p>Specifiche istruzioni per la formattazione degli indici saranno date pi&ugrave;
   avanti nel processo. Il compito del correttore &egrave; di assicurarsi che tutto
   il testo e i numeri siano corretti.
</p>
<p>Vedi anche <a href="#mult_col">Colonne multiple</a>.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Torna all'inizio</a></p>


<h3><a name="play_n">Opere Teatrali: Nomi degli Attori/Note di palcoscenico</a></h3>
<p>Nei dialoghi, tratta un cambio di personaggio come un nuovo paragrafo, con
   una riga vuota prima. Se il nome del personaggio &egrave; su una linea propria,
   trattalo comunque come un paragrafo separato.
</p>
<p>Le note di palcoscenico sono lasciate come nell'immagine originale, cos&igrave;
   se la nota di palcoscenico &egrave; su una riga a parte, lasciala in questo modo;
   se &egrave; alla fine di una riga di dialogo, lasciala cos&igrave;.
   Le note di palcoscenico spesso iniziano con una parentesi di apertura e
   omettono la parentesi di chiusura. La convenzione viene mantenuta; non chiudere
   le parentesi.
</p>
<p>A volte, soprattutto nelle opere in versi, una parola viene spezzata per limiti
   di larghezza della pagina e messa sopra o sotto, dopo una parentesi ( , invece
   di metterla su una riga a parte. Riunisci la parola come per una normale
   <a href="#eol_hyphen">parola a capo a fine riga</a>.
   Vedi l'<a href="#play4">esempio</a>.
</p>
<p>Per favore controlla i <a href="#comments">Project Comments</a>, (Project Comments),
   poich&eacute; il Project Manager potrebbe specificare un trattamento diverso.
</p>
<!-- END RR -->

<table width="100%" align="center" border="1" cellpadding="4" cellspacing="0" summary="Play Example 1">
  <tbody>
    <tr>
      <th align="left" bgcolor="cornsilk">Immagine originale:</th>
    </tr>
    <tr align="left">
      <td width="100%" valign="top"><img src="../play1.png" width="500" height="430" alt=""><br>
      </td>
    </tr>
    <tr>
      <th align="left" bgcolor="cornsilk">Testo corretto:</th>
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
      <th align="left" bgcolor="cornsilk">Immagine originale:</th>
    </tr>
    <tr align="left">
      <td width="100%" valign="top"><img src="../play4.png" width="502" height="98" alt=""><br>
      </td>
    </tr>
    <tr>
      <th align="left" bgcolor="cornsilk">Testo corretto:</th>
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
<p class="backtotop"><a href="#top">Torna all'inizio</a></p>


<h3><a name="anything">Qualsiasi cosa richieda un trattamento speciale o di cui non sei sicuro</a></h3>
<p>Nella correzione, se incontri qualcosa che non &egrave; contemplato in questa
   guida che ritieni necessiti di un trattamento speciale o che non sai con
   certezza come trattare, scrivi un messaggio nella <a href="#forums">Discussione
   del Progetto</a>, annotando il numero png della pagina.
</p>
<p>Dovresti inserire inoltre una nota nel testo in correzione per spiegare ai
   successivi correttori, impaginatori o al post-correttore qual &egrave; il
   problema o la domanda. La nota viene inserita iniziando con una parentesi
   quadra e due asterischi <kbd>[**</kbd> e termina con un'altra parentesi quadra <kbd>]</kbd>.
   Questo la separa chiaramente dal testo dell'autore e segnala al post-correttore di
   fermarsi ad esaminare attentamente questa parte del testo e l'immagine
   corrispondente per considerare ogni questione. Puoi anche identificare
   in che turno stai lavorando appena prima di <kbd>]</kbd> cos&igrave; che i
   volontari sappiano chi ha lasciato la nota. Qualsiasi commento inserito da
   un volontario precedente <b>deve</b> deve essere lasciato al suo posto.
   Vedi la sezione seguente per maggiori dettagli.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Torna all'inizio</a></p>


<h3><a name="prev_notes">Note e Commenti dei Correttori Precedenti</a></h3>
<p>Qualsiasi commento o nota inseriti da un volontario precedente <b>devono</b>
   essere lasciati al loro posto.
   Puoi aggiungere che sei d'accordo o non sei d'accordo con la nota esistente
   ma, anche se sai la risposta, non devi assolutamente rimuovere il commento.
   Se hai trovato una fonte che risolve il problema, per favore citala in modo
   che il post-correttore vi possa fare riferimento.
</p>
<p>Se incontri una nota di un precedente volontario
   di cui conosci la risposta, per favore dedica un momento per dare un riscontro
   cliccando il nome nell'interfaccia di correzione e manda un messaggio privato
   spiegando come trattare quella situazione in futuro.
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


<h3><a name="formatting">Formattazione</a></h3>
<p>Potresti trovare a volte della formattazione gi&agrave; presente nel testo.
   <b>Non aggiungere o cambiare queste informazioni di formattazione</b>; gli impaginatori
   lo faranno pi&ugrave; avanti nel processo. Comunque, puoi rimuoverla se inteferisce
   con la tua correzione. Il bottone <s>&lt;x&gt;</s> nell'interfaccia di correzione
   rimuove la marcatura come &lt;i&gt; e &lt;b&gt; dal testo evidenziato.
   Alcuni esempi di compiti di formattazione includono:
</p>
<ul>
  <li>&lt;i&gt;corsivo&lt;/i&gt;, &lt;b&gt;grassetto&lt;/b&gt;, &lt;sc&gt;Maiuscoletto&lt;/sc&gt;</li>
  <li>Spazieggiato/gesperrt</li>
  <li>Cambiamento di dimensione dei caratteri</li>
  <li>Spaziatura dei titoli dei capitoli e delle sezioni</li>
  <li>Spazi extra, asterischi o righe tra i paragrafi</li>
  <li>Note a pi&egrave; di pagina che continuano su pi&ugrave; pagine</li>
  <li>Note a pi&egrave; di pagina indicate da simboli</li>
  <li>Illustrazioni</li>
  <li>Piazzamento delle note a margine</li>
  <li>Sistemazione dei dati nelle tabelle</li>
  <li>Rientro (nelle poesie e altrove)</li>
  <li>Riunire righe lunghe nelle poesie e negli indici</li>
</ul>
<p>Se il correttore precedente ha inserito della formattazione, per favore dedica un
   momento a dare un riscontro cliccando il suo nome nell'interfaccia di correzione
   e mandando un messaggio privato su come affrontare la situazione in futuro.
   <b>Ricorda di lasciare la formattazione ai turni di formattazione.</b>
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Torna all'inizio</a></p>


<h3><a name="common_OCR">Problemi comuni del software di riconoscimento dei caratteri (OCR)</a></h3>
<p>L'OCR normalmente ha problemi nel distinguere caratteri simili. Alcuni esempi sono:
</p>
<ul>
  <li>La cifra '1' (uno), la lettera minuscola 'l' (elle), e la lettera maiuscola 'I'.
    Fai attenzione che per alcuni tipi di carattere il numero uno pu&ograve;
    sembrare <small>I</small> (una piccola lettera 'i' maiuscola).</li>
  <li>La cifra '0' (zero), e la lettera maiuscola 'O'.</li>
  <li>Lineette &amp; trattini: Correggili con attenzione&mdash;il testo convertito
    dall'OCR spesso ha solamente un trattino per un lineetta emme che dovrebbe
    averne due. Vedi le regole per le <a href="#eol_hyphen">parole a capo</a>
    e le <a href="#em_dashes">lineette</a> per informazioni pi&ugrave; dettagliate.</li>
  <li>Parentesi tonde ( ) e parentesi graffe { }.</li>
</ul>
<p>Fai particolare attenzione a questi caratteri. Normalmente il contesto della
   frase &egrave; sufficiente per determinare quale &egrave; il carattere
   corretto, ma fai attenzione&mdash;spesso la mente li 'corregge' automaticamente
   mentre stai leggendo.
</p>
<p>Notarli &egrave; molto pi&ugrave; facile se usi un font mono-spazio come
   <a href="../font_sample.php">DPCustomMono</a> o Courier.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Torna all'inizio</a></p>


<h3><a name="OCR_scanno">Problemi dell'OCR: Scannos</a></h3>
<p>Un altro problema comune dell'OCR &egrave; il non-riconoscimento dei caratteri.
   Chiamiamo questi errori "scannos" (come "typos", i refusi).
   Questo non-riconoscimento pu&ograve; creare parole che:
</p>
<ul compact>
   <li>possono sembrare corrette a prima vista, ma sono in realt&agrave;
       ortograficamente sbagliate.<br>
       Questi possono essere individuati usando <a href="../wordcheck-faq.php">WordCheck</a>
       dall'interfaccia di correzione.</li>
   <li>sono cambiate in una parola diversa ma ortograficamente corretta che non
       coincide con la parola dell'immagine.<br>
       Questo &egrave; un problema perch&eacute; possono essere individuati solo
       da qualcuno che sta effettivamente leggendo il testo.</li>
</ul>
<p>Probabilmente l'esempio pi&ugrave; comune del secondo tipo &egrave; "and"
   che viene convertito dall'OCR in "arid." Altri Esempi: "eve" per "eye",
   "Torn" per "Tom", "train" per "tram". Questi sono pi&ugrave; difficili da
   individuare e per essi abbiamo un termine speciale: "Stealth Scannos" (Scannos subdoli).
   Raccogliamo esempi di Stealth Scannos in <a href="<?php echo $Stealth_Scannos_URL; ?>">questa discussione</a>.
</p>
<p>Individuare gli scannos &egrave; molto pi&ugrave; facile se usi un font
   mono-spazio come <a href="../font_sample.php">DPCustomMono</a> o Courier.
   Per aiutare la correzione, l'uso di <a href="../wordcheck-faq.php">WordCheck</a>
   (o un suo equivalente) &egrave; raccomandato in <?php echo $ELR_round->id; ?>,
   e richiesto in altri turni di correzione.
</p>
<!-- END RR -->
<!-- More to be added.... -->
<p class="backtotop"><a href="#top">Torna all'inizio</a></p>


<h3><a name="OCR_raised_o">Problemi dell'OCR Problems: &egrave; quel &deg; &ordm; proprio un simbolo di grado?</a></h3>
<p>Ci sono tre diversi simboli che possono sembrare molto simili nell'immagine e che
   il software OCR interpreta allo stesso modo (e normalmente sbagliato):
</p>
<ul>
  <li>Il simbolo di grado <kbd style="font-size:150%;">&deg;</kbd>: deve essere usato
      solo per indicare i gradi (di temperatura, di angoli, ecc.).</li>
  <li>La o in apice: praticamente tutti i casi di una o in apice deve essere
      corretta in <kbd>^o</kbd>, seguendo le regole per <a href="#supers">Apici/Esponenti</a>.</li>
  <li>L'ordinale maschile <kbd style="font-size:150%;">&ordm;</kbd>: correggi anche
      questo come un apice a meno che il carattere speciale sia richiesto nei
      <a href="#comments">Project Comments</a> (Commenti al progetto).
      Pu&ograve; essere usato in lingue come lo spagnolo e il portoghese,
      ed &egrave; l'equivalente del -th nell'Inglese 4th, 5th, ecc.
      Segue dei numeri e ha il corrispondente femminile nella a in apice (<kbd>&ordf;</kbd>).</li>
</ul>
<!-- END RR -->
<p class="backtotop"><a href="#top">Torna all'inizio</a></p>


<h3><a name="hand_notes">Note scritte a mano nel libro</a></h3>
<p>Non includere le note scritte a mano in un libro (a meno che riscrivano
   del testo stampato sbiadito per renderlo pi&ugrave; visibile).
   Non includere le note a margine scritte a mano da lettori, ecc.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Torna all'inizio</a></p>


<h3><a name="bad_image">Immagine danneggiata/sbagliata</a></h3>
<p>Se una pagina &egrave; danneggiata (non si carica, praticamente illeggibile,
   ecc.), per favore scrivi un messaggio riguardo all'immagine danneggiata nella
   <a href="#forums">Discussione del progetto</a> e clicca il bottone
   "Report Bad page" (Segnala una pagina danneggiata) in modo che la pagina
   venga messa in 'quarantena', invece di ritornarla alla correzione. Se solo una
   piccola porzione della pagina &egrave; danneggiata, lascia una nota come
   descritto <a href="#anything">sopra</a>, e per favore lascia un messaggio
   nella discussione del progetto senza segnalare l'intera pagina come danneggiata.
   Il bottone di "Bad Page" &egrave; disponibile solo durante il primo turno di
   correzione, quindi &egrave; importante che questi problemi siano risulti subito.
</p>
<p>Ricorda che alcune pagine sono abbastanza grandi ed &egrave; normale che il tuo
   browser abbia difficolt&agrave; nel visualizzarle, specialmente se hai diverse
   finestre aperte o stai usando un computer pi&ugrave; vecchio. Prima di segnalare
   una pagina come danneggiata, prova a ingrandire l'immagine, chiudere alcune
   delle tue finestre e programmi, o lascia un messaggio nella discussione
   del progetto per vedere se qualcun altro ha lo stesso problema.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Torna all'inizio</a></p>


<h3><a name="bad_text">Immagine sbagliata rispetto al testo</a></h3>
<p>Se c'&egrave; un'immagine sbagliata rispetto al testo dato, per favore lascia
   un messaggio riguardo a questa pagina nella <a href="#forums">discussione del progetto</a>
   e clicca il bottone "Report Bad Page" (Segnala una pagina sbagliata) in modo che la pagina
   venga messa in 'quarantena', invece di ritornarla alla correzione.
   Il bottone di "Bad Page" &egrave; disponibile solo durante il primo turno di
   correzione, quindi &egrave; importante che questi problemi siano risulti subito.
</p>
<p>&Egrave; abbastanza normale che il testo convertito dall'OCR sia per la maggior
   parte corretto, ma che manchino le prime righe di testo. Per favore semplicemente
   ribatti le righe mancanti. Se quasi tutte le righe mancano dalla finestra di testo,
   allora ribatti tutta la pagina (se hai voglia di farlo) o semplicemente clicca sul
   bottone "Return Page to Round" (Ritorna la pagina alla correzione) e la pagina
   sar&agrave; ripresentata a qualcun altro. Se ci sono diverse pagine come questa,
   puoi scrivere un messaggio nella <a href="#forums">discussione del progetto</a>
   per farlo presente al Project Manager.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Torna all'inizio</a></p>


<h3><a name="round1">Errori del correttore precedente</a></h3>
<p>Se il correttore precedente ha fatto molti errori o ha tralasciato molte cose,
   per favore dedica un momento a mandare un riscontro cliccando il suo nome
   nell'interfaccia di correzione e mandando un messaggio privato per spiegare
   come trattare la situazione in modo che sappia cosa fare in futuro.
</p>
<p><em>Per favore, sii gentile!</em> Tutti qui siamo volontari e probabilmente
   cerchiamo di fare del nostro meglio. Il fine del tuo messaggio di riscontro
   &egrave; quello di informare del modo giusto di correggere, invece di criticare.
   Dai uno specifico esempio del loro lavoro, mostrando ci&ograve; che hanno fatto,
   e come avrebbero dovuto fare.
</p>
<p>Se il correttore precedente ha fatto un lavoro eccellente, puoi anche mandargli
   un messaggio al riguardo&mdash;specialmente se hanno lavorato su una pagina
   particolarmente difficile.
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
   <a href="#forums">discussione del progetto</a>. Se fai un cambiamento, includi
   una nota che descriva cosa hai cambiato: <kbd>[**typo "stapma" corretto]</kbd>.
   Includi i due asterischi <kbd>**</kbd> in modo che il post-correttore li noti.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Torna all'inizio</a></p>


<h3><a name="f_errors">Errori sui fatti nei testi</a></h3>
<p>Non correggere errori sui fatti nel libro dell'autore. Molti dei libri che
   stiamo correggendo contengono affermazioni di fatti che non accettiamo pi&ugrave;
   come esatte. Lasciale come l'autore le ha scritte. Vedi <a href="#p_errors">Errori
   di stampa/Errori di ortografia</a> per il modo di lasciare una nota se pensi che
   il testo stampato non sia ci&ograve; che l'autore intendeva.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Torna all'inizio</a></p>


<h3><a name="insert_char">Inserimento dei Caratteri speciali</a></h3>
<p>Se non sono nella tua tastiera, ci sono molti modi di inserire i caratteri
   speciali:
</p>
<ul compact>
  <li>I menu a discesa nell'interfaccia di correzione.</li>
  <li>Gli Applets inclusi nel tuo sistema operativo.
    <ul compact>
      <li>Windows: "Mappa dei Caratteri"<br> Accedi attraverso:<br>
          Avvio: Esegui: charmap, o<br>
          Avvio: Accessori: Utilit&agrave; di sistema: Mappa dei caratteri.</li>
      <li>Macintosh: Key Caps (Tastiera) o "Visore Tastiera"<br>
          Per OS 9 e inferiori si trova nel Menu Mela,<br>
          Per OS X fino a 10.2, si trova in Applicazioni (Applications), cartella Utility<br>
          Per OS X 10.3 e superiori (compreso Leopard), &egrave; nella barra dei Menu a
          destra, menu Tastiera, come "Visore Tastiera".</li>
      <li>Linux: il nome e la localizzazione del selettore dei caratteri cambia a seconda
          dell'ambiente del tuo desktop.</li>
    </ul>
  </li>
  <li>Un programma on-line.</li>
  <li>Scorciatoie della tastiera.<br>
       (Vedi le tabelle per <a href="#a_chars_win">Windows</a> e <a href="#a_chars_mac">Macintosh</a>
       sotto.)</li>
  <li>Passare ad una configurazione della tastiera o locale che supporta gli accenti su tasti
      variabili.
    <ul compact>
      <li>Pannello di Controllo (Tastiera, Inserisci Locale)</li>
      <li>Macintosh: Menu Tastiera (sulla Barra dei Menu)</li>
      <li>Linux: Cambia la tastiera nella tua configurazione X.</li>
    </ul>
  </li>
</ul>
<!-- END RR -->

<a name="a_chars_win"></a>
<p><b>Per Windows</b>:
</p>
<ul compact>
  <li>Puoi usare il programma della mappa dei caratteri
     (Avvio, Esegui: charmap) per selezionare una lettera specifica,
     poi copia &amp; incolla.
  </li>
  <li>I menu a discesa dell'Interfaccia di correzione.
  </li>
  <li>Oppure puoi digitare i codici di scorciatoia ALT+numero tastierino elencati qui
      sotto per questi caratteri. Questo &egrave; pi&ugrave; veloce rispetto al copia
      &amp; incolla, una volta che hai memorizzato i codici.
      <br>Tieni premuto il tasto Alt e digita le 4 cifre sul
          <i>tastierino numerico</i>&mdash;la fila di numeri sopra le lettere non funziona.
      <br>Devi digitare tutte e 4 le cifre, incluso lo 0 (zero) iniziale.
          Ricorda che la maiuscola di una lettera &egrave; meno 32 rispetto alla minuscola.
      <br>Queste istruzioni sono per il layout di tastiera US-English. Potrebbero non
          funzionare per altre configurazioni di tastiere.
      <br>(<a href="../charwin.pdf">Versione stampabile di questa tabella</a>)
  </li>
</ul>

<br>
<table align="center" border="6" rules="all" summary="Windows shortcuts">
  <tbody>
  <tr>
      <th bgcolor="cornsilk" colspan=14>Scorciatoie Windows per i caratteri Latin-1</th>
  </tr>
  <tr bgcolor="cornsilk">
      <th colspan=2>` grave</th>
      <th colspan=2>&acute; acuto</th>
      <th colspan=2>^ circonflesso</th>
      <th colspan=2>~ tilde</th>
      <th colspan=2>&uml; umlaut</th>
      <th colspan=2>&deg; anello</th>
      <th colspan=2>&AElig; legatura</th>
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
      <th colspan=2 bgcolor="cornsilk">/ barrato</th>
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
      <th colspan=2 bgcolor="cornsilk">valuta     </th>
      <th colspan=2 bgcolor="cornsilk">matematica  </th>
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
  <tr><th colspan=2 bgcolor="cornsilk">&ccedil;ediglia </th>
      <th colspan=2 bgcolor="cornsilk">Islandese    </th>
      <th colspan=2 bgcolor="cornsilk">simboli        </th>
      <th colspan=2 bgcolor="cornsilk">accenti      </th>
      <th colspan=2 bgcolor="cornsilk">punteggiatura  </th>
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
  <tr><th colspan=2 bgcolor="cornsilk">apici        </th>
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
      <th colspan=2 bgcolor="cornsilk">ordinali  </th>
      <td align="center" bgcolor="mistyrose" title="1/4 Fraction"          >&frac14; </td><td>Alt-0188&nbsp;&dagger;</td>
  </tr>
  <tr><td align="center" bgcolor="mistyrose" title="superscript 2"         >&sup2;   </td><td>Alt-0178&nbsp;*</td>
      <th colspan=2 bgcolor="cornsilk">legatura sz        </th>
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
<p>* A meno che sia specificamente richiesto dai <a href="#comments">Project
   Comments</a> (Commenti al progetto), non usare i simboli degli ordinali e degli apici,
   ma usa invece le regole per <a href="#supers">Apici</a>. (x^2, f^o, ecc.)
</p>
<p>&dagger; A meno che sia specificamente richiesto dai <a href="#comments">Project
   Comments</a> (Commenti al progetto), non usare i simboli delle frazioni, ma usa invece
   le regole per <a href="#fract_s">Frazioni</a>. (1/2, 1/4, 3/4, ecc.)
</p>
<p><b>Per Apple Macintosh</b>:
</p>
<ul compact>
  <li>Puoi fare riferimento al programma "Key Caps" (Tastiera).<br>
      In OS 9 &amp; inferiori si trova nel menu Mela; in OS X fino a 10.2 &egrave;
      situato in Applicazioni, cartella Utility.<br>
      Il programma visualizza uno schema della tastiera; premendo Maiuscolo,
      Opzione, Comando o combinazioni di questi tasti mostra come produrre i
      vari caratteri. Fai riferimento ad esso per vedere come digitare il carattere
      desiderato, oppure copialo &amp; incollalo da qui nell'interfaccia di correzione.</li>
  <li>In OS X 10.3 e superiori, la stessa funzione &egrave; svolta da una paletta che puoi
      richiamare dal menu Tastiera (il menu a discesa collegato all'icona della bandiera
      nella barra Menu). Il comando &egrave; "Mostra Visore tastiera". Se non &egrave;
      presente nel menu Tastiera, o se non hai questo menu, puoi attivarlo aprendo
      Preferenze di Sistema, pannello "Internazionale", e selezionando Menu Tastiera.
      Assicurati che "Mostra il menu della tastiera nella barra dei menu" (in basso
      nella finestra) sia selezionato. Nell'elenco del pannello spunta
      la casella "Visore tastiera" oltre al (o ai) layout di tastiera che usi.
  </li>
  <li>I menu a discesa dell'Interfaccia di correzione.
  </li>
  <li>Oppure puoi digitare i codici di scorciatoia Apple Opt- elencati sotto per
      questi caratteri.
      <br>&Egrave; molto pi&ugrave; veloce che usare il copia&amp;incolla, una volta che
          hai familiarizzato con i codici.
      <br>Premi il tasto Opt (Opzione) e digita il simbolo dell'accento, quindi rilascia
          Opt e digita la lettera da accentare (oppure, per altri codici, premi solo
          Opt e digita il simbolo).
      <br>Queste istruzioni sono per il layout di tastiera US-English. Potrebbero non
          funzionare per altre configurazioni di tastiera.
      <br>(<a href="../charapp.pdf">Versione stampabile di questa tabella</a>)
  </li>
</ul>

<br>
<a name="a_chars_mac"></a>
<table align="center" border="6" rules="all" summary="Mac shortcuts">
  <tbody>
  <tr bgcolor="cornsilk">
      <th colspan=14>Scorciatoie Apple Mac Shortcuts per i caratteri Latin-1</th>
  </tr>
  <tr bgcolor="cornsilk">
      <th colspan=2>` grave</th>
      <th colspan=2>&acute; acuto</th>
      <th colspan=2>^ circonflesso</th>
      <th colspan=2>~ tilde</th>
      <th colspan=2>&uml; umlaut</th>
      <th colspan=2>&deg; anello</th>
      <th colspan=2>&AElig; legatura</th>
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
      <th colspan=2 bgcolor="cornsilk">/ barrato</th>
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
      <th colspan=2 bgcolor="cornsilk">valuta     </th>
      <th colspan=2 bgcolor="cornsilk">matematica  </th>
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
      <td align="center" bgcolor="mistyrose" title="Multiplication"        >&times;  </td><td>(nessuno)&nbsp;&Dagger;</td>
  </tr>
  <tr><th colspan=2 bgcolor="cornsilk">&ccedil;ediglia </th>
      <th colspan=2 bgcolor="cornsilk">Islandese    </th>
      <th colspan=2 bgcolor="cornsilk">simboli        </th>
      <th colspan=2 bgcolor="cornsilk">accenti      </th>
      <th colspan=2 bgcolor="cornsilk">punteggiatura  </th>
      <td align="center" bgcolor="mistyrose" title="Yen"                   >&yen;    </td><td>Opt-y   </td>
      <td align="center" bgcolor="mistyrose" title="Division"              >&divide; </td><td>Opt-/   </td>
  </tr>
  <tr><td align="center" bgcolor="mistyrose" title="Small c cedilla"       >&ccedil; </td><td>Opt-c   </td>
      <td align="center" bgcolor="mistyrose" title="Capital Thorn"         >&THORN;  </td><td>(nessuno)&nbsp;&Dagger;</td>
      <td align="center" bgcolor="mistyrose" title="Copyright"             >&copy;   </td><td>Opt-g   </td>
      <td align="center" bgcolor="mistyrose" title="acute accent"          >&acute;  </td><td>Opt-E   </td>
      <td align="center" bgcolor="mistyrose" title="Inverted Question Mark">&iquest; </td><td>Opt-?   </td>
      <td align="center" bgcolor="mistyrose" title="General Currency"      >&curren; </td><td>(nessuno)&nbsp;&Dagger;  </td>
      <td align="center" bgcolor="mistyrose" title="Logical Not"           >&not;    </td><td>Opt-l   </td>
  </tr>
  <tr><td align="center" bgcolor="mistyrose" title="Capital C cedilla"     >&Ccedil; </td><td>Opt-C   </td>
      <td align="center" bgcolor="mistyrose" title="Small thorn"           >&thorn;  </td><td>(nessuno)&nbsp;&Dagger;  </td>
      <td align="center" bgcolor="mistyrose" title="Registration Mark"     >&reg;    </td><td>Opt-r   </td>
      <td align="center" bgcolor="mistyrose" title="umlaut accent"         >&uml;    </td><td>Opt-U   </td>
      <td align="center" bgcolor="mistyrose" title="Inverted Exclamation"  >&iexcl;  </td><td>Opt-1   </td>
      <td> </td><td> </td>
      <td align="center" bgcolor="mistyrose" title="Degrees"               >&deg;    </td><td>Shift-Opt-8   </td>
  </tr>
  <tr><th colspan=2 bgcolor="cornsilk">apici        </th>
      <td align="center" bgcolor="mistyrose" title="Capital Eth"           >&ETH;    </td><td>(nessuno)&nbsp;&Dagger;  </td>
      <td align="center" bgcolor="mistyrose" title="Paragraph (pilcrow)"   >&para;   </td><td>Opt-7   </td>
      <td align="center" bgcolor="mistyrose" title="macron accent"         >&macr;   </td><td>Shift-Opt-,</td>
      <td align="center" bgcolor="mistyrose" title="guillemet left"        >&laquo;  </td><td>Opt-\   </td>
      <td> </td><td> </td>
      <td align="center" bgcolor="mistyrose" title="Micro"                 >&micro;  </td><td>Opt-m   </td>
  </tr>
  <tr><td align="center" bgcolor="mistyrose" title="superscript 1"         >&sup1;   </td><td>(nessuno)&nbsp;*&Dagger;  </td>
      <td align="center" bgcolor="mistyrose" title="Small eth"             >&eth;    </td><td>(nessuno)&nbsp;&Dagger;  </td>
      <td align="center" bgcolor="mistyrose" title="Section"               >&sect;   </td><td>Opt-6   </td>
      <td align="center" bgcolor="mistyrose" title="cedilla"               >&cedil;  </td><td>Opt-Z   </td>
      <td align="center" bgcolor="mistyrose" title="guillemet right"       >&raquo;  </td><td>Shift-Opt-\</td>
      <th colspan=2 bgcolor="cornsilk">ordinali  </th>
      <td align="center" bgcolor="mistyrose" title="1/4 Fraction"          >&frac14; </td><td>(nessuno)&nbsp;&dagger;&Dagger;</td>
  </tr>
  <tr><td align="center" bgcolor="mistyrose" title="superscript 2"         >&sup2;   </td><td>(nessuno)&nbsp;*&Dagger;  </td>
      <th colspan=2 bgcolor="cornsilk">legatura sz        </th>
      <td align="center" bgcolor="mistyrose" title="Broken Vertical bar"   >&brvbar; </td><td>(nessuno)&nbsp;&Dagger;  </td>
      <td> </td><td> </td>
      <td align="center" bgcolor="mistyrose" title="Middle dot"            >&middot; </td><td>Shift-Opt-9  </td>
      <td align="center" bgcolor="mistyrose" title="Masculine Ordinal"     >&ordm;   </td><td>Opt-0&nbsp;*</td>
      <td align="center" bgcolor="mistyrose" title="1/2 Fraction"          >&frac12; </td><td>(nessuno)&nbsp;&dagger;&Dagger;</td>
  </tr>
  <tr><td align="center" bgcolor="mistyrose" title="superscript 3"         >&sup3;   </td><td>(nessuno)&nbsp;*&Dagger;  </td>
      <td align="center" bgcolor="mistyrose" title="sz ligature"           >&szlig;  </td><td>Opt-s   </td>
      <td> </td><td> </td>
      <td> </td><td> </td>
      <td> </td><td> </td>
      <td align="center" bgcolor="mistyrose" title="Feminine Ordinal"      >&ordf;   </td><td>Opt-9&nbsp;*</td>
      <td align="center" bgcolor="mistyrose" title="3/4 Fraction"          >&frac34; </td><td>(nessuno)&nbsp;&dagger;&Dagger;</td>
  </tr>
  </tbody>
</table>
<p>* A meno che sia specificamente richiesto dai <a href="#comments">Project
   Comments</a> (Commenti al progetto), non usare i simboli degli ordinali e degli apici,
   ma usa invece le regole per
   <a href="#supers">Apici</a>. (x^2, f^o, ecc.)
</p>
<p>&dagger; A meno che sia specificamente richiesto dai <a href="#comments">Project
   Comments</a> (Commenti al progetto), non usare i simboli delle frazioni, ma usa
   invece le regole per <a href="#fract_s">Frazioni</a>. (1/2, 1/4, 3/4, ecc.)
</p>
<p>&Dagger;&nbsp;Nota: Non c'&egrave; una scorciatoia corrispondente; usa i menu a
   discesa se necessario.
</p>
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
        <li><a href="#eop_hyphen">A capo a fine pagina</a></li>
        <li><a href="#eol_hyphen">A capo a fine riga</a></li>
        <li><a href="#a_chars">Accentati/Non-ASCII, Caratteri</a></li>
        <li><a href="#a_chars">ae Legatura</a></li>
        <li><a href="#anything">Altre cose di cui sei insicuro</a></li>
        <li><a href="#supers">Apici/Esponenti</a></li>
        <li><a href="#blank_pg">Blank Page, Pagina vuota</a></li>
        <li><a href="#chap_head">Capitolo, Titolo del</a></li>
        <li><a href="#chap_head">Capitolo, Virgolette mancanti all'inizio del</a></li>
        <li><a href="#drop_caps">Capolettera (Lettera maiuscola grande e decorata)</a></li>
        <li><a href="#a_chars">Caratteri accentati/non-ASCII</a></li>
        <li><a href="#d_chars">Caratteri con segni diacritici</a></li>
        <li><a href="#insert_char">Caratteri Latin-1, Inserimento di</a></li>
        <li><a href="#a_chars">Caratteri Non-ASCII</a></li>
        <li><a href="#f_chars">Caratteri Non-Latini</a></li>
        <li><a href="#insert_char">Caratteri speciali, Inserimento</a></li>
        <li><a href="#mult_col">Colonne multiple</a></li>
        <li><a href="#comments">Commenti al progetto (Project Comments)</a></li>
        <li><a href="#prev_notes">Commenti del correttore precedente</a></li>
        <li><a href="#contract">Contrazioni</a></li>
        <li><a href="#title_pg">Copertina</a></li>
        <li><a href="#title_pg">Copertina/Retro</a></li>
        <li><a href="#prev_pg">Correggere errori in pagine precedenti</a></li>
        <li><a href="#formatting">Corsivo</a></li>
        <li><a href="#d_chars">Diacritici</a></li>
        <li><a href="#illust">Didascalia, Illustrazioni</a></li>
        <li><a href="#forums">Discussione del progetto (Project Discussion)</a></li>
        <li><a href="#f_chars">Ebraico</a></li>
        <li><a href="#period_p">Ellipsis, Puntini di sospensione "..."</a></li>
        <li><a href="#poetry">Epigrammi</a></li>
        <li><a href="#round1">Errori del Correttore precedente</a></li>
        <li><a href="#p_errors">Errori di stampa/di ortografia (Refusi, Typos)</a></li>
        <li><a href="#prev_pg">Errori in pagine precedenti, Correggere</a></li>
        <li><a href="#f_errors">Errori sui fatti</a></li>
        <li><a href="#supers">Esponenti/Apici</a></li>
        <li><a href="#period_s">Fine frase, Punto a</a></li>
        <li><a href="#eop_hyphen">Fine pagina, A capo a</a></li>
        <li><a href="#eol_hyphen">Fine riga, A capo a</a></li>
        <li><a href="#trail_s">Fine riga, Spazio a</a></li>
        <li><a href="#next_word">Fondo pagina (Richiamo), Parola singola a</a></li>
        <li><a href="#formatting">Formattazione</a></li>
        <li><a href="#forums">Forum</a></li>
        <li><a href="#fract_s">Frazioni</a></li>
        <li><a href="#formatting">Grassetto</a></li>
        <li><a href="#f_chars">Greco</a></li>
        <li><a href="#summary">Guida in breve</a></li>
        <li><a href="#illust">Illustrazioni</a></li>
        <li><a href="#bad_image">Immagine danneggiata</a></li>
        <li><a href="#bad_image">Immagine sbagliata</a></li>
        <li><a href="#bad_text">Immagine sbagliata per il testo</a></li>
        <li><a href="#bk_index">Indici</a></li>
        <li><a href="#chap_head">Inizio del capitolo, Virgolette mancanti all'</a></li>
        <li><a href="#insert_char">Inserimento di caratteri speciali</a></li>
        <li><a href="#line_br">Interruzioni di Riga</a></li>
        <li><a href="#page_hf">Intestazioni di pagina</a></li>
        <li><a href="#page_hf">Intestazioni/Pi&egrave; di Pagina</a></li>
        <li><a href="#a_chars">Legature</a></li>
        <li><a href="#drop_caps">Lettera grande e decorata di apertura (Capolettera)</a></li>
        <li><a href="#period_p">Lingue diverse dall'Inglese (LOTE), Puntini di sospensione in</a></li>
        <li><a href="#em_dashes">Lineette</a></li>
        <li><a href="#eop_hyphen">Lineette, A fine pagina</a></li>
        <li><a href="#eol_hyphen">Lineette, A fine riga</a></li>
        <li><a href="#em_dashes">Lineette emme</a></li>
        <li><a href="#drop_caps">Maiuscola, Lettera decorata (Capolettera)</a></li>
        <li><a href="#small_caps"><span style="font-variant: small-caps">Maiuscoletto</span></a></li>
        <li><a href="#para_side">Margine, Note a</a></li>
        <li><a href="#insert_char">Menu a discesa</a></li>
      </ul>
    </td>
    <td width="50%" valign="top">
      <ul>
        <li><a href="#play_n">Nomi degli attori (Opere teatrali)</a></li>
        <li><a href="#para_side">Note a margine, Descrizione a lato del paragrafo</a></li>
        <li><a href="#footnotes">Note a pi&egrave; di pagina</a></li>
        <li><a href="#prev_notes">Note dei Correttori precedenti</a></li>
        <li><a href="#footnotes">Note di chiusura</a></li>
        <li><a href="#play_n">Note di palcoscenico (Opere teatrali)</a></li>
        <li><a href="#hand_notes">Note scritte a mano</a></li>
        <li><a href="#line_no">Numeri di riga</a></li>
        <li><a href="#common_OCR">OCR, Problemi comuni dell'</a></li>
        <li><a href="#OCR_raised_o">OCR, Problemi: Quel &deg; &ordm; &egrave; davvero un simbolo di grado?</a></li>
        <li><a href="#OCR_scanno">OCR, Problemi: Scannos</a></li>
        <li><a href="#a_chars">oe, Legatura</a></li>
        <li><a href="#play_n">Opere teatrali: Nomi degli attori/Note di palcoscenico</a></li>
        <li><a href="#OCR_raised_o">Ordinale, Simbolo</a></li>
        <li><a href="#prev_pg">Pagina precedente, Correggere errori in una</a></li>
        <li><a href="#blank_pg">Pagina vuota, Blank Page</a></li>
        <li><a href="#next_word">Parola singola a fondo pagina (Richiamo)</a></li>
        <li><a href="#small_caps"><span style="font-variant: small-caps">Parole in maiuscoletto</span></a></li>
        <li><a href="#subscr">Pedici</a></li>
        <li><a href="#page_hf">Pi&egrave; di pagina</a></li>
        <li><a href="#poetry">Poesia</a></li>
        <li><a href="#common_OCR">Problemi comuni dell'OCR</a></li>
        <li><a href="#comments">Project Comments (Commenti al progetto)</a></li>
        <li><a href="#forums">Project Discussion (Discussione del progetto)</a></li>
        <li><a href="#punctuat">Punteggiatura, Spazi della</a></li>
        <li><a href="#period_p">Puntini di sospensione "..." (Ellipsis)</a></li>
        <li><a href="#period_s">Punto a fine frase (Punto fermo)</a></li>
        <li><a href="#period_s">Punto fermo, Fine frase</a></li>
        <li><a href="#anything">Qualsiasi cosa che necessita un trattamento speciale</a></li>
        <li><a href="#p_errors">Refusi (Errori di Ortografia, di stampa)</a></li>
        <li><a href="#prime">Regola principale, La</a></li>
        <li><a href="#title_pg">Retro</a></li>
        <li><a href="#next_word">Richiamo</a></li>
        <li><a href="#para_space">Rientro di paragrafo</a></li>
        <li><a href="#line_no">Riga, Numeri di</a></li>
        <li><a href="#about">Riguardo questo documento</a></li>
        <li><a href="#OCR_scanno">Scannos</a></li>
        <li><a href="#insert_char">Scorciatoie di tastiera per caratteri Latin-1</a></li>
        <li><a href="#hand_notes">Scritte a mano, Note</a></li>
        <li><a href="#em_dashes">Segni Meno</a></li>
        <li><a href="#OCR_raised_o">Simbolo di grado</a></li>
        <li><a href="#toc">Sommario</a></li>
        <li><a href="#para_space">Spaziatura dei paragrafi</a></li>
        <li><a href="#para_space">Spaziatura/Rientro di paragrafo</a></li>
        <li><a href="#punctuat">Spazi della Punteggiatura</a></li>
        <li><a href="#extra_sp">Spazi extra tra parole</a></li>
        <li><a href="#trail_s">Spazio a fine riga</a></li>
        <li><a href="#trail_s">Spazio vuoto a fine riga</a></li>
        <li><a href="#tables">Tabelle</a></li>
        <li><a href="#extra_sp">Tabulazione</a></li>
        <li><a href="#toc">Tavola dei contenuti</a></li>
        <li><a href="#play_n">Teatro</a></li>
        <li><a href="#supers">Testo in apice</a></li>
        <li><a href="#bad_text">Testo Sbagliato per l'immagine</a></li>
        <li><a href="#chap_head">Titoli dei capitoli</a></li>
        <li><a href="#em_dashes">Trattini</a></li>
        <li><a href="#eop_hyphen">Trattini e lineette a fine pagina</a></li>
        <li><a href="#eol_hyphen">Trattini e lineette a fine riga</a></li>
        <li><a href="#p_errors">Typos, Errori di stampa/di ortografia (Refusi)</a></li>
        <li><a href="#double_q">Virgolette doppie</a></li>
        <li><a href="#chap_head">Virgolette doppie mancanti all'inizio del capitolo</a></li>
        <li><a href="#single_q">Virgolette singole</a></li>
        <li><a href="#quote_ea">Virgolette su ogni riga</a></li>
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
// vim: sw=4 ts=4 expandtab
