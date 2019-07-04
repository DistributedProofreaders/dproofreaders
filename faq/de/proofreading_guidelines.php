<?php
$relPath='../../pinc/';
include_once($relPath.'base.inc');
include_once($relPath.'faq.inc');
include_once($relPath.'pg.inc');
include_once($relPath.'theme.inc');

maybe_redirect_to_external_faq("de");

$theme_args["css_data"] = "p.backtotop {text-align:right; font-size:75%;margin-right:-5%;}";

output_header('Korrekturlese-Richtlinien', NO_STATSBAR, $theme_args);

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

<h1 align="center"><a name="top">Korrekturlese-Richtlinien</a></h1>

<h3 align="center">Version 2.0 vom 7. Juni 2009 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
  <a href="../dochist.php"><font size="-1">(&Uuml;berarbeitungsverlauf)</font></a></h3>

<p>Korrekturlese-Richtlinien <a href="../proofreading_guidelines.php">auf Englisch</a> /
      Proofreading Guidelines <a href="../proofreading_guidelines.php">in English</a> <br>
    Korrekturlese-Richtlinien <a href="../fr/proofreading_guidelines.php">auf Franz&ouml;sisch</a> /
      Directives de Relecture et Correction <a href="../fr/proofreading_guidelines.php">en fran&ccedil;ais</a><br>
    Korrekturlese-Richtlinien <a href="../pt/proofreading_guidelines.php">auf Portugiesisch</a> /
      Regras de Revis&atilde;o <a href="../pt/proofreading_guidelines.php">em Portugu&ecirc;s</a><br>
    Korrekturlese-Richtlinien <a href="../es/proofreading_guidelines.php">auf Spanisch</a> /
      Reglas de Revisi&oacute;n <a href="../es/proofreading_guidelines.php">en espa&ntilde;ol</a><br>
    Korrekturlese-Richtlinien <a href="../nl/proofreading_guidelines.php">auf Niederl&auml;ndisch</a> /
      Proeflees-Richtlijnen <a href="../nl/proofreading_guidelines.php">in het Nederlands</a><br>
    Korrekturlese-Richtlinien <a href="../it/proofreading_guidelines.php">auf Italienisch</a> /
      Regole di Correzione <a href="../it/proofreading_guidelines.php">in Italiano</a><br>
</p>

<p>Hier geht es zum <a href="../../quiz/start.php?show_only=PQ">Proofreading Quiz und Tutorial</a> (nur Englisch).
</p>

<table border="0" cellspacing="0" width="100%" summary="Inhaltsverzeichnis">
  <tbody>
  <tr>
    <td width="1" bgcolor="silver">&nbsp;</td>
    <td bgcolor="silver" align="center"><h2 style="margin-bottom: 0; margin-top: 0;">Inhaltsverzeichnis</h2></td>
  </tr>
  <tr>
    <td width="1" bgcolor="silver">&nbsp;</td>
    <td bgcolor="white" align="left">
      <ul>
        <li><a href="#prime">Oberstes Gebot</a></li>
        <li><a href="#summary">Kurzfassung dieser Richtlinien</a></li>
        <li><a href="#about">&Uuml;ber dieses Dokument</a></li>
        <li><a href="#comments">Projektkommentare</a></li>
        <li><a href="#forums">Forum/Diskussion eines Projektes</a></li>
        <li><a href="#prev_pg">Fehler auf fr&uuml;heren Seiten beheben</a></li>
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
        <li style="margin-top:.25em;"><font size="+1">Korrekturlesen auf der Zeichenebene:</font>
        <ul>
          <li><a href="#double_q">Doppelte Anf&uuml;hrungszeichen</a></li>
          <li><a href="#single_q">Einfache Anf&uuml;hrungszeichen</a></li>
          <li><a href="#quote_ea">Anf&uuml;hrungszeichen auf jeder Zeile</a></li>
          <li><a href="#period_s">Punkte am Ende von S&auml;tzen</a></li>
          <li><a href="#punctuat">Satzzeichen</a></li>
          <li><a href="#extra_sp">&Uuml;berfl&uuml;ssige Leerzeichen bzw. Tabulatoren zwischen W&ouml;rtern</a></li>
          <li><a href="#trail_s">Leerzeichen am Zeilenende</a></li>
          <li><a href="#em_dashes">Bindestriche, kurze und lange Gedankenstriche</a></li>
          <li><a href="#eol_hyphen">Trennstriche am Zeilenende</a></li>
          <li><a href="#eop_hyphen">Trennstriche am Seitenende</a></li>
          <li><a href="#period_p">Auslassungspunkte &bdquo;...&ldquo; (Ellipse)</a></li>
          <li><a href="#contract">Zusammenziehungen</a></li>
          <li><a href="#fract_s">Br&uuml;che</a></li>
          <li><a href="#a_chars">Akzente und nicht-ASCII-Zeichen</a></li>
          <li><a href="#d_chars">Buchstaben mit diakritischen Zeichen</a></li>
          <li><a href="#f_chars">Nicht-lateinische Zeichen</a></li>
          <li><a href="#supers">Hochgestellte Zeichen</a></li>
          <li><a href="#subscr">Tiefgestellte Zeichen</a></li>
          <li><a href="#drop_caps">&Uuml;berdimensionale, verzierte Gro&szlig;buchstaben als Er&ouml;ffnung (Initialen)</a></li>
          <li><a href="#small_caps">W&ouml;rter in Kapit&auml;lchen</a></li>
        </ul></li>
        <li style="margin-top:.25em;"><font size="+1">Korrekturlesen auf der Absatzebene:</font>
        <ul>
          <li><a href="#line_br">Zeilenumbr&uuml;che</a></li>
          <li><a href="#chap_head">Kapitel&uuml;berschriften</a></li>
          <li><a href="#para_space">Absatzabst&auml;nde und -einr&uuml;ckungen</a></li>
          <li><a href="#page_hf">Kopf- und Fu&szlig;zeilen</a></li>
          <li><a href="#illust">Abbildungen</a></li>
          <li><a href="#footnotes">Fu&szlig;noten/Endnoten</a></li>
          <li><a href="#para_side">Randnoten (Marginalien)</a></li>
          <li><a href="#mult_col">Mehrspaltige Texte</a></li>
          <li><a href="#tables">Tabellen</a></li>
          <li><a href="#poetry">Gedichte/Epigramme</a></li>
          <li><a href="#line_no">Zeilennummern</a></li>
          <li><a href="#next_word">Einzelne W&ouml;rter am unteren Seitenrand</a></li>
        </ul></li>
        <li style="margin-top:.25em;"><font size="+1">Korrekturlesen auf der Seitenebene:</font>
        <ul>
          <li><a href="#blank_pg">Leere Seiten</a></li>
          <li><a href="#title_pg">Titelbl&auml;tter und Umschlagseiten</a></li>
          <li><a href="#toc">Inhaltsverzeichnisse</a></li>
          <li><a href="#bk_index">Sachregister und Schlagwortverzeichnisse</a></li>
          <li><a href="#play_n">Dramen: Rollennamen/Regieanweisungen</a></li>
        </ul></li>
        <li><a href="#anything">Sonstige Besonderheiten und Behandlung von Unklarheiten</a></li>
        <li><a href="#prev_notes">Anmerkungen vorhergehender Korrekturleser</a></li>
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
        <li style="margin-top:.25em;"><font size="+1">Allgemeine Probleme:</font>
        <ul>
          <li><a href="#formatting">Formatierung</a></li>
          <li><a href="#common_OCR">Allgemeine OCR-Probleme</a></li>
          <li><a href="#OCR_scanno">OCR-Fehler: &bdquo;Scannos&ldquo;</a></li>
          <li><a href="#OCR_raised_o">OCR-Probleme: Ist dieses &deg; &ordm; wirklich ein Grad-Zeichen?</a></li>
          <li><a href="#hand_notes">Handgeschriebene Notizen in B&uuml;chern</a></li>
          <li><a href="#bad_image">Schlechte Vorlagen</a></li>
          <li><a href="#bad_text">Falsche Vorlage zum Text</a></li>
          <li><a href="#round1">Fehler der vorherigen Korrekturleser</a></li>
          <li><a href="#p_errors">Satz- und Rechtschreibfehler: &bdquo;Typos&ldquo;</a></li>
          <li><a href="#f_errors">Tatsachenfehler im Text</a></li>
          <li><a href="#insert_char">Eingabe von speziellen Zeichen</a></li>
        </ul></li>
        <li><a href="#index">Alphabetisches Stichwortverzeichnis zu den Richtlinien</a></li>
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

<h3><a name="prime">Oberstes Gebot</a></h3>
<p><em>&bdquo;Lassen Sie den Text des Autors unver&auml;ndert!&ldquo;</em>
</p>
<p>Das fertige E-Book, das der Leser vielleicht in Jahrzehnten erst zu
   Gesicht bekommt, soll die Absicht des Autors genau vermitteln. Hat der Autor W&ouml;rter
   ungew&ouml;hnlich buchstabiert, so belassen wir sie in dieser Schreibweise.
   Hat der Autor ungeheuerlich rassistische oder voreingenommene Aussagen gemacht,
   so lassen wir sie so stehen. Hat der Autor nach jedem dritten Wort ein Komma,
   hochgestellte Zeichen oder Fu&szlig;noten gesetzt, behalten wir die Kommas,
   hochgestellten Zeichen und Fu&szlig;noten bei. Wir sind Korrekturleser, <b>keine</b> Lektoren,
   wenn etwas im Text nicht dem Bild der Originalseite entspricht, sollten
   Sie den Text so &auml;ndern, dass er dem Original entspricht. (Siehe <a href="#p_errors">
   Satz- und Rechtschreibfehler</a> zur richtigen Behandlung von offensichtlichen
   Druckfehlern.)
</p>
<p>Allerdings &auml;ndern wir weniger bedeutsame typographische Konventionen,
   die den Sinn des Textes nicht beeinflussen. Zum Beispiel verbinden wir
   Worte, die an einem Zeilenumbruch getrennt wurden
   (<a href="#eol_hyphen">Trennstriche am Zeilenende</a>). Solche &Auml;nderungen
   erm&ouml;glichen die Erstellung einer <em>konsistent formatierten</em>
   Version des Buches. Unsere Korrekturregeln sind darauf ausgelegt, dieses Ziel
   zu erreichen. Bitte lesen Sie die &uuml;brigen Korrekturlese-Richtlinien
   sorgf&auml;ltig unter diesem Gesichtspunkt. F&uuml;r die Formatierung gibt
   es gesonderte Richtlinien &ndash; die vorliegenden Richtlinien sind
   <i>ausschlie&szlig;lich</i> f&uuml;r das Korrekturlesen gedacht. Als
   Korrekturleser sorgen wir f&uuml;r die &Uuml;bereinstimmung des Inhalts,
   w&auml;hrend sp&auml;ter die Formatierer f&uuml;r die &Uuml;bereinstimmung
   des Aussehens sorgen.
</p>
<p>Um es den nachfolgenden Korrekturlesern, Formatierern und Nachbearbeitern
   leichter zu machen, behalten wir dar&uuml;ber hinaus
   <a href="#line_br">Zeilenumbr&uuml;che</a> bei. Dadurch lassen sich die
   Zeilen im Text leichter mit den Zeilen in der Vorlage vergleichen.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Zur&uuml;ck nach oben</a></p>


<h3><a name="summary">Kurzfassung dieser Richtlinien</a></h3>
<p>Die Kurzfassung dieser Richtlinien ist ein &uuml;bersichtliches,
   zweiseitiges druckerfreundliches PDF-Dokument, das die wichtigsten
   Punkte enth&auml;lt und Beispiele f&uuml;rs Korrekturlesen gibt.
   Sie ist derzeit nur in englischer Sprache verf&uuml;gbar
   (<a href="../proofing_summary.pdf">Proofreading Summary</a>), aber
   eine deutsche Fassung ist in Arbeit. Falls Sie neu hier sind, empfehlen
   wir Ihnen, das PDF-Dokument auszudrucken und w&auml;hrend des
   Korrekturlesens griffbereit zu halten.
</p>
<p>M&ouml;glicherweise m&uuml;ssen Sie erst eine Software zum Anzeigen
   von PDF-Dateien herunterladen und installieren. Eine kostenlose Version
   von Adobe&reg; finden Sie <a href="http://www.adobe.com/products/acrobat/readstep2.html">hier</a>.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Zur&uuml;ck nach oben</a></p>


<h3><a name="about">&Uuml;ber dieses Dokument</a></h3>
<p>Dieses Dokument enth&auml;lt Erkl&auml;rungen zu den Korrekturregeln.
   Jedes Buch wird von vielen Korrekturlesern verteilt bearbeitet, von
   denen jeder andere Seiten liest. Das Einhalten der Korrekturregeln
   hilft allen, die Korrekturen konsistent, d.&nbsp;h. <em>auf die gleiche
   Art</em> durchzuf&uuml;hren. Das macht es den Formatierern und dem
   Nachbearbeiter leichter, die die Arbeit an einem Buch vervollst&auml;ndigen.
</p>
<p><i>Dieses Dokument ist nicht als allgemeines Regelwerk f&uuml;r
   Redaktionsarbeit oder Typographie gedacht.</i>
</p>
<p>Wir haben in diese Korrekturlese-Richtlinien alle Punkte aufgenommen,
   zu denen neue Benutzer beim Korrekturlesen Fragen hatten. Es gibt eine
   getrennte <a href="formatting_guidelines.php">Formatierungs-Richtlinie</a>.
   Eine zweite Gruppe von Freiwilligen bearbeitet die Formatierung des Textes.
   Wenn Sie auf eine Situation sto&szlig;en, zu der Sie keinen Bezug in
   diesen Richtlinien finden, wird sie wahrscheinlich in den
   Formatierungsrunden bearbeitet und deshalb hier nicht erw&auml;hnt.
   Wenn Sie unsicher sind, fragen Sie bitte in der <a href="#forums">Projektdiskussion</a> nach.
</p>
<p>Falls Sie etwas vermissen, etwas Ihrer Ansicht nach anders
   beschrieben werden sollte oder unklar f&uuml;r Sie ist, so lassen Sie es uns bitte wissen.
<?php if($site_url == "http://www.pgdp.net" || $site_url == "http://www.pgdp.org") { ?>
   Wenn Ihnen ein unbekannter Ausdruck in diesen Richtlinien begegnet, schlagen Sie nach im
   <a href="http://www.pgdp.net/wiki/DP_Jargon">wiki jargon guide</a>.
<?php } ?>
   Dieses Dokument wird laufend &uuml;berarbeitet. Helfen Sie uns
   dabei, indem Sie uns Ihre Verbesserungsvorschl&auml;ge im
   <a href="<?php echo $Guideline_discussion_URL; ?>">Forum Dokumentation</a> mitteilen.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Zur&uuml;ck nach oben</a></p>


<h3><a name="comments">Projektkommentare</a></h3>
<p>Wenn Sie ein Projekt zum Korrekturlesen ausw&auml;hlen, wird die Projektseite
   geladen. Auf dieser Seite befindet sich ein Abschnitt &sbquo;Project Comments&lsquo;
   (Projektkommentare), der spezielle Informationen zu diesem Projekt (Buch)
   enth&auml;lt. <b>Lesen Sie die Projektkommentare, bevor Sie mit dem
   Korrekturlesen beginnen!</b> Falls der Projektmanager m&ouml;chte,
   dass Sie in seinem Buch etwas anders machen als hier vorgeschrieben,
   so steht es dort. Anleitungen in den Projektkommentaren haben <em>Vorrang</em>
   vor den Regeln in diesen Richtlinien, also richten Sie sich danach. Wenn
   in den Projektkommentaren Anweisungen f&uuml;r die Formatierungsphase
   stehen, so sind diese beim Korrekturlesen noch nicht relevant. Au&szlig;erdem
   kann der Projektmanager dort interessante Informationen &uuml;ber den
   Autor oder das Projekt einstellen.
</p>
<p><em>Bitte lesen Sie auch das Projektforum!</em> Dort kann der
   Projektmanager Fragen zu den projektspezifischen Richtlinien kl&auml;ren.
   Oft wird es auch von Korrekturlesern verwendet, um andere auf
   wiederkehrende Schwierigkeiten im Projekt hinzuweisen und darauf,
   wie sie am besten angegangen werden. (Siehe unten).
</p>
<p>&Uuml;ber den Link &sbquo;Images, Pages Proofread, &amp; Differences&lsquo;
   (Vorlagen, korrigierte Seiten &amp; &Auml;nderungen) auf der Projektseite
   kann man sehen, was andere Korrekturleser ge&auml;ndert haben.
   <a href="<?php echo $Using_project_details_URL; ?>">Dieses Forum</a>
   er&ouml;rtert verschiedene Arten, diese Informationen zu benutzen.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Zur&uuml;ck nach oben</a></p>


<h3><a name="forums">Forum/Diskussion eines Projektes</a></h3>
<p>Auf der Projektseite, dem Ausgangspunkt f&uuml;r das Korrekturlesen,
   gibt es in der Zeile &sbquo;Forum&lsquo; den Link &sbquo;Discuss
   this Project&lsquo; (Projekt diskutieren), wenn es bereits eine
   Diskussion gibt oder &sbquo;Start a discussion on this
   Project&lsquo; (Diskussion zu diesem Projekt beginnen), wenn es noch keine
   gibt. Damit kommen Sie in einen Thread des Diskussionsforums speziell
   f&uuml;r dieses Projekt. Hier k&ouml;nnen Sie Fragen zum Projekt stellen,
   den Projektmanager &uuml;ber Probleme informieren usw. Benutzen Sie
   diesen Forum-Thread, um mit dem Projektmanager und anderen Korrekturlesern
   zu kommunizieren, die ebenfalls an diesem Buch arbeiten.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Zur&uuml;ck nach oben</a></p>


<h3><a name="prev_pg">Fehler auf fr&uuml;heren Seiten beheben</a></h3>
<p>Die <a href="#comments">Projektseite</a>
   enth&auml;lt Links zu den Buchseiten, die Sie zuletzt Korrektur
   gelesen haben. (Wenn Sie in einem Projekt noch keine Seiten bearbeitet
   haben, fehlen diese Links.)
</p>
<p>Seiten, die unter &sbquo;DONE&lsquo; (fertig) oder &sbquo;IN PROGRESS&lsquo;
   (in Arbeit) gelistet sind, sind verf&uuml;gbar, um Korrektur-&Auml;nderungen
   vorzunehmen oder das Korrekturlesen abzuschlie&szlig;en. Klicken Sie
   einfach auf den Link zur Seite.
   Wenn Sie also entdecken, dass Sie auf einer Seite einen Fehler gemacht oder etwas
   falsch markiert haben, k&ouml;nnen Sie auf die entsprechende Seite klicken und sie
   erneut &ouml;ffnen, um den Fehler zu beheben.
</p>
<p>Au&szlig;erdem k&ouml;nnen Sie die Links &sbquo;Images, Pages Proofread,
   &amp; Differences&lsquo; (Vorlagen, korrigierte Seiten &amp; &Auml;nderungen)
   sowie &sbquo;Just My Pages&lsquo; (nur meine Seiten) auf der
   <a href="#comments">Projektseite</a> verwenden. Sie finden &sbquo;Edit&lsquo;
   (Bearbeiten)-Links neben allen Seiten, die Sie in dieser Runde
   bearbeitet haben und die noch &uuml;berarbeitet werden k&ouml;nnen.
</p>
<p>Weitere Informationen finden Sie entweder in der
   <a href="../prooffacehelp.php?i_type=0">Hilfe zur Standard-Korrekturlese-Oberfl&auml;che</a>
   (nur Englisch) oder in der <a href="../prooffacehelp.php?i_type=1">Hilfe
   zur Erweiterten Korrekturlese-Oberfl&auml;che</a> (nur Englisch),
   je nachdem, welche Benutzeroberfl&auml;che Sie verwenden.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Zur&uuml;ck nach oben</a></p>


<table width="100%" border="0" cellspacing="0" cellpadding="6" summary="Korrekturlesen auf der Zeichenebene">
  <tbody>
    <tr>
      <td bgcolor="silver"><h2 style="margin-bottom: 0; margin-top: 0;">Korrekturlesen auf der Zeichenebene:</h2></td>
    </tr>
  </tbody>
</table>


<h3><a name="double_q">Doppelte Anf&uuml;hrungszeichen</a></h3>
<p>Verwenden Sie bitte die gew&ouml;hnlichen ASCII-Anf&uuml;hrungszeichen
   <tt>"</tt>. Ersetzen Sie keine doppelten durch einfache
   Anf&uuml;hrungszeichen. Lassen Sie sie so, wie der Autor sie
   geschrieben hat.
   Siehe <a href="#chap_head">Kapitel-&Uuml;berschriften</a> f&uuml;r den Fall, dass ein
   doppeltes Anf&uuml;hrungszeichen am Beginn eines Kapitels fehlt.
</p>
<p>F&uuml;r andere Anf&uuml;hrungszeichen als " benutzen Sie die gleichen
   Zeichen, die im Originalbild erscheinen, sofern sie
   verf&uuml;gbar sind. Die franz&ouml;sischen Guillemets <tt>&laquo;wie
   hier&raquo;</tt> sind in den Pulldown-Men&uuml;s der
   Korrekturlese-Oberfl&auml;che zu finden, weil sie zum Latin-1-Zeichensatz
   geh&ouml;ren. Bitten denken Sie daran, eventuelle Leerzeichen zwischen
   den Guillemets und dem zitierten Text zu entfernen; falls sie ben&ouml;tigt
   werden, werden sie bei der Nachbearbeitung eingef&uuml;gt. Das gleiche
   gilt f&uuml;r Sprachen, die umgekehrte Guillemets&nbsp; <tt>&raquo;wie
   hier&laquo;</tt>&nbsp; verwenden.
</p>
<p>Die in manchen Texten (in Deutsch oder anderen Sprachen) benutzten
   Anf&uuml;hrungszeichen&nbsp; <tt>&bdquo;wie diese&ldquo;</tt>&nbsp;
   sind auch in den Pulldown-Men&uuml;s enthalten. Der Einfachheit
   halber sollten Sie immer&nbsp; <tt>&bdquo;</tt>&nbsp; und&nbsp; <tt>&ldquo;</tt>&nbsp;
   benutzen, egal welche Anf&uuml;hrungszeichen im Originaltext benutzt
   werden, solange diese deutlich erkennbar untere und obere sind. Falls
   erforderlich, werden die Anf&uuml;hrungszeichen in der Nachbearbeitung
   ge&auml;ndert.
</p>
<p>Unter Umst&auml;nden weist Sie der Projektmanager in den
   <a href="#comments">Projektkommentaren</a> an, nicht-englische
   Anf&uuml;hrungszeichen f&uuml;r ein bestimmtes Buch abweichend
   Korrektur zu lesen. Bitte stellen Sie sicher, dass Sie solche Anweisungen
   nicht in anderen Projekten verwenden.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Zur&uuml;ck nach oben</a></p>


<h3><a name="single_q">Einfache Anf&uuml;hrungszeichen</a></h3>
<p>Lesen Sie diese bitte Korrektur als gew&ouml;hnliches einfaches
   ASCII-Anf&uuml;hrungszeichen <tt>'</tt> (Apostroph). Ersetzen Sie
   keine einfachen durch doppelte Anf&uuml;hrungszeichen. Lassen Sie
   sie so, wie der Autor sie geschrieben hat.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Zur&uuml;ck nach oben</a></p>


<h3><a name="quote_ea">Anf&uuml;hrungszeichen auf jeder Zeile</a></h3>
<p>Stehen bei Zitaten am Beginn jeder Zeile Anf&uuml;hrungszeichen,
   so entfernen Sie alle <b>mit Ausnahme</b> desjenigen in der ersten Zeile.
   Geht das Zitat &uuml;ber mehrere Abs&auml;tze, so sollte am Beginn
   eines jeden Absatzes ein &ouml;ffnendes Anf&uuml;hrungszeichen stehen bleiben.
</p>
<p>In Lyrik behalten Sie bitte die zus&auml;tzlichen Anf&uuml;hrungszeichen bei,
   da hier die Zeilenumbr&uuml;che nicht ge&auml;ndert werden.
</p>
<p>Oft befindet sich das schlie&szlig;ende Anf&uuml;hrungszeichen erst
   ganz am Ende des Zitates, also m&ouml;glicherweise nicht auf der Seite,
   die Sie gerade Korrektur lesen. K&uuml;mmern Sie sich nicht darum &ndash;
   f&uuml;gen Sie keine schlie&szlig;enden Anf&uuml;hrungszeichen hinzu,
   die nicht auf der Buchseite stehen.
</p>
<p>Es gibt einige sprachspezifische Ausnahmen. Im Franz&ouml;sischen z.B.
   wird bei Dialog in Zitaten eine Kombination unterschiedlicher Satzzeichen
   verwendet, um verschiedene Sprecher zu bezeichnen. Wenn Sie mit  einer
   bestimmten Sprache nicht vertraut sind, lesen Sie die
   <a href="#comments">Projektkommentare</a> oder  schreiben Sie eine
   Nachricht an den Projektmanager in der <a href="#forums">Projektdiskussion</a>
   zur Klarstellung.
<!-- END RR -->
<table width="100%" border="1" cellpadding="4" cellspacing="0" align="center" summary="Example of quote marks on each line">
  <tbody>
    <tr>
      <th valign="top" align="left" bgcolor="cornsilk">Beispielvorlage:</th>
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
      <th valign="top" align="left" bgcolor="cornsilk">Richtig korrigierter Text:</th>
    </tr>
    <tr valign="top">
      <td>
        <tt>
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
        </tt>
      </td>
    </tr>
  </tbody>
</table>
<p class="backtotop"><a href="#top">Zur&uuml;ck nach oben</a></p>


<h3><a name="period_s">Punkte am Ende von S&auml;tzen</a></h3>
<p>Hinter den Punkt, der einen Satz beendet, geh&ouml;rt ein einfaches Leerzeichen.
</p>
<p>Es ist nicht n&ouml;tig, zus&auml;tzliche Leerzeichen nach Punkten
   zu entfernen, wenn sie schon im OCR-Text sind &ndash; wir k&ouml;nnen
   dies automatisch in der Nachbearbeitung erledigen.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Zur&uuml;ck nach oben</a></p>


<h3><a name="punctuat">Leerzeichen bei Satzzeichen</a></h3>
<p>Leerzeichen vor Satzzeichen kommen bei alten B&uuml;chern manchmal
   vor, weil im 18. und 19. Jahrhundert z. B. vor Semikolon oder Komma
   oft &bdquo;Teil-Leerzeichen&ldquo; gesetzt wurden.
</p>
<p>Im Allgemeinen sollten Satzzeichen ein Leerzeichen dahinter,
   aber keines davor haben. Falls im
   OCR-Text kein Leerzeichen nach dem Satzzeichen steht, f&uuml;gen Sie
   eines ein. Steht ein Leerzeichen vor dem Satzzeichen, so entfernen
   Sie es. Das gilt auch f&uuml;r Sprachen wie Franz&ouml;sisch, die
   normalerweise Leerzeichen vor Satzzeichen benutzen.
   Allerdings: Satzzeichen, die normalerweise in Paaren auftreten, wie
   "Anf&uuml;hrungszeichen", (einfache), [eckige] und {geschweifte} Klammern,
   haben normalerweise ein Leerzeichen vor dem &ouml;ffnenden Zeichen. Dieses
   sollte erhalten bleiben.
</p>
<!-- END RR -->

<table width="100%" align="center" border="1" cellpadding="4" cellspacing="0" summary="Satzzeichen">
  <tbody>
    <tr><th align="left" bgcolor="cornsilk">Beispielvorlage:</th></tr>
    <tr>
      <td valign="top">and so it goes&nbsp;; ever and ever.</td>
    </tr>
    <tr><th align="left" bgcolor="cornsilk">Richtig korrigierter Text:</th></tr>
    <tr>
      <td valign="top"><tt>and so it goes; ever and ever.</tt></td>
    </tr>
  </tbody>
</table>
<p class="backtotop"><a href="#top">Zur&uuml;ck nach oben</a></p>


<h3><a name="extra_sp">Zus&auml;tzliche Leerzeichen bzw. Tabulatoren zwischen W&ouml;rtern</a></h3>
<p>Die Texterkennung verursacht oft &uuml;berfl&uuml;ssige Leerzeichen
   und Tabulatoren im Text. Sie brauchen sich nicht die M&uuml;he zu
   machen, diese Leerzeichen zu entfernen &ndash; das kann automatisch
   in der Nachbearbeitung erfolgen.
   &Uuml;berfl&uuml;ssige Leerzeichen um Satzzeichen, Gedankenstriche,
   Anf&uuml;hrungszeichen herum usw. <b>m&uuml;ssen</b> jedoch entfernt
   werden, wenn sie das Symbol vom Wort trennen.
</p>
<p>Im folgenden Beispiel: <tt>A horse&nbsp;;&nbsp;&nbsp;my kingdom for a
   horse.</tt> muss das Leerzeichen zwischen &bdquo;horse&ldquo; und
   dem Semikolon entfernt werden. Die zwei Leerzeichen nach dem Semikolon
   sind in Ordnung &ndash; Sie brauchen keines der beiden zu l&ouml;schen.
</p>
<p>Dar&uuml;ber hinaus sollten Sie Tabulatoren entfernen, wenn Sie sie
   im Text finden.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Zur&uuml;ck nach oben</a></p>


<h3><a name="trail_s">Leerzeichen am Zeilenende</a></h3>
<p>Verschwenden Sie keine Zeit damit, Leerzeichen am Ende jeder Zeile
   einzuf&uuml;gen; solche Leerzeichen werden automatisch entfernt,
   wenn Sie die Seite speichern. Wenn der Text nachbearbeitet wird
   (Postprocessing), wird jeder Zeilenumbruch in ein Leerzeichen umgewandelt.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Zur&uuml;ck nach oben</a></p>


<h3><a name="em_dashes">Bindestriche, kurze und lange Gedankenstriche</a></h3>
<p>Normalerweise kommen in B&uuml;chern vier Arten solcher Zeichen vor:
</p>
  <ol compact>
    <li><i>Bindestriche:</i> Sie werden verwendet, um zwei
        W&ouml;rter oder eine Vor- oder Nachsilbe mit einem Wort zu <b>verbinden</b>.<br>
        Belassen Sie diese als einzelnen Bindestrich, ohne Leerzeichen
        davor oder danach.
        Beachten Sie, dass es eine allgemeine Ausnahme dazu gibt,
        die im zweiten Beispiel weiter unten gezeigt wird.
    </li>
    <li><i>Kurze Gedankenstriche (En-dashes):</i> Sie sind etwas
        l&auml;nger als der Bindestrich und werden f&uuml;r <b>Zahlenbereiche</b>
        oder als mathematisches <b>Minuszeichen</b> verwendet.<br>
        Lesen Sie diese ebenfalls als einen einzelnen Bindestrich Korrektur.
        Ob Leerzeichen davor oder danach stehen, h&auml;ngt davon ab,
        wie es im Buch gemacht wurde. Normalerweise gilt: keine Leerzeichen
        bei Zahlenbereichen, aber Leerzeichen um mathematische Minuszeichen,
        manchmal auf beiden Seiten, manchmal nur davor.
    </li>
    <li><i>Lange (Em-dashes) und extralange Gedankenstriche:</i>
        Sie dienen in englischsprachigen Texten als <b>Trennzeichen</b>
        zwischen W&ouml;rtern&mdash;zum Beispiel f&uuml;r Einsch&uuml;be
        wie diesen&mdash;, oder wenn einem Sprecher das Wort in der Kehle
        steckenbl&mdash;&mdash;!<br>
        Lesen Sie diese als zwei Bindestriche Korrektur, wenn der Strich so
        lang ist wie 2-3 Buchstaben (langer Gedankenstrich) und als vier
        Bindestriche, wenn der Strich so lang ist wie 4-5 Buchstaben
        (extralanger Gedankenstrich). Setzen Sie kein Leerzeichen
        davor oder danach, auch wenn es im Buch so aussieht.
    </li>
    <li><i>Bewusst ausgelassene bzw. zensierte W&ouml;rter oder Namen:</i><br>
        Wenn sie durch einen Strich im Original dargestellt werden, lesen
        Sie sie Korrektur mit zwei oder vier Bindestrichen wie f&uuml;r lange
        und extralange Gedankenstriche beschrieben.
        Deutet der Gedankenstrich ein ganzes Wort an, so werden
        Leerzeichen davor und danach so gesetzt, als st&uuml;nde das Wort
        da. Wenn er nur Teil eines Wortes ersetzt, werden keine Leerzeichen
        eingef&uuml;gt, verbinden Sie ihn mit dem Rest des Wortes.
    </li>
  </ol>
<p>Siehe auch die Regeln f&uuml;r Binde- und Gedankenstriche am <a href="#eol_hyphen">
   Ende der Zeile</a> und am <a href="#eop_hyphen">Ende der Seite</a>.
</p>
<!-- END RR -->

<p><b>Beispiele</b> &ndash; Bindestriche, kurze und lange Gedankenstriche
</p>

<table width="100%" align="center" border="1" cellpadding="4" cellspacing="0" summary="Hyphens and Dashes examples">
  <tbody>
    <tr>
      <th valign="top" bgcolor="cornsilk">Beispielvorlage:</th>
      <th valign="top" bgcolor="cornsilk">Richtig korrigierter Text:</th>
      <th valign="top" bgcolor="cornsilk">Typ:</th>
    </tr>
    <tr>
      <td valign="top">semi-detached</td>
      <td valign="top"><tt>semi-detached</tt></td>
      <td>Bindestrich</td>
    </tr>
    <tr>
      <td valign="top">three- and four-part harmony</td>
      <td valign="top"><tt>three- and four-part harmony</tt></td>
      <td>Bindestriche</td>
    </tr>
    <tr>
      <td valign="top">discoveries which the Crus-<br>
        aders made and brought home with</td>
      <td valign="top"><tt>discoveries which the Crusaders<br>
        made and brought home with</tt></td>
      <td>Bindestrich</td>
    </tr>
    <tr>
      <td valign="top">factors which mold char-<br>
        acter&mdash;environment, training and heritage,</td>
      <td valign="top"><tt>factors which mold character--environment,<br>
        training and heritage,</tt></td>
      <td>Bindestrich, langer Gedankenstrich</td>
    </tr>
    <tr>
      <td valign="top">See pages 21&ndash;25</td>
      <td valign="top"><tt>See pages 21-25</tt></td>
      <td>kurzer Gedankenstrich</td>
    </tr>
    <tr>
      <td valign="top">It was &ndash;14&deg;C outside</td>
      <td valign="top"><tt>It was -14&deg;C outside</tt></td>
      <td>kurzer Gedankenstrich</td>
    </tr>
    <tr>
      <td valign="top">X &ndash; Y = Z</td>
      <td valign="top"><tt>X - Y = Z</tt></td>
      <td>kurzer Gedankenstrich</td>
    </tr>
    <tr>
      <td valign="top">2&ndash;1/2</td>
      <td valign="top"><tt>2-1/2</tt></td>
      <td>kurzer Gedankenstrich</td>
    </tr>
    <tr>
      <td valign="top">&mdash;A plague on both<br> your houses!&mdash;I am dead.</td>
      <td valign="top"><tt>--A plague on both<br> your houses!--I am dead.</tt></td>
      <td>lange Gedankenstriche</td>
    </tr>
    <tr>
      <td valign="top">sensations&mdash;sweet, bitter, salt, and sour<br>
        &mdash;if even all of these are simple tastes. What</td>
      <td valign="top"><tt>sensations--sweet, bitter, salt, and sour--if<br>
        even all of these are simple tastes. What</tt></td>
      <td>lange Gedankenstriche</td>
    </tr>
    <tr>
      <td valign="top">senses&mdash;touch, smell, hearing, and sight&mdash;<br>
        with which we are here concerned,</td>
      <td valign="top"><tt>senses--touch, smell, hearing, and sight--with<br>
        which we are here concerned,</tt></td>
      <td>lange Gedankenstriche</td>
    </tr>
    <tr>
      <td valign="top">It is the east, and Juliet is the sun&mdash;!</td>
      <td valign="top"><tt>It is the east, and Juliet is the sun--!</tt></td>
      <td>langer Gedankenstrich</td>
    </tr>
    <tr>
      <td valign="top"><img src="../dashes.png" width="300" height="28" alt=""></td>
      <td valign="top"><tt>how a--a--cannon-ball goes----"</tt></td>
      <td>lange Gedankenstriche, Bindestrich &amp; extralanger Gedankenstrich</td>
    </tr>
    <tr>
      <td valign="top">"Three hundred&mdash;&mdash;" "years," she was going to<br>
        say, but the left-hand cat interrupted her.</td>
      <td valign="top"><tt>"Three hundred----" "years," she was going to<br>
        say, but the left-hand cat interrupted her.</tt></td>
      <td>extralanger Gedankenstrich</td>
    </tr>
    <tr>
      <td valign="top">As the witness Mr. &mdash;&mdash; testified,</td>
      <td valign="top"><tt>As the witness Mr. ---- testified,</tt></td>
      <td>extralanger Gedankenstrich</td>
    </tr>
    <tr>
      <td valign="top">As the witness Mr. S&mdash;&mdash; testified,</td>
      <td valign="top"><tt>As the witness Mr. S---- testified,</tt></td>
      <td>extralanger Gedankenstrich</td>
    </tr>
    <tr>
      <td valign="top">the famous detective of &mdash;&mdash;B Baker St.</td>
      <td valign="top"><tt>the famous detective of ----B Baker St.</tt></td>
      <td>extralanger Gedankenstrich</td>
    </tr>
    <tr>
      <td valign="top">&ldquo;You &mdash;&mdash; Yankee&rdquo;, she yelled.</td>
      <td valign="top"><tt>"You ---- Yankee", she yelled.</tt></td>
      <td>extralanger Gedankenstrich</td>
    </tr>
    <tr>
      <td valign="top">&ldquo;I am not a d&mdash;d Yankee&rdquo;, he replied.</td>
      <td valign="top"><tt>"I am not a d--d Yankee", he replied.</tt></td>
      <td>langer Gedankenstrich</td>
    </tr>
  </tbody>
</table>
<p class="backtotop"><a href="#top">Zur&uuml;ck nach oben</a></p>


<h3><a name="eol_hyphen">Trenn- und Gedankenstriche am Zeilenende</a></h3>
<p>Wenn ein Bindestrich als Trennzeichen am Zeilenende steht, dann
   f&uuml;gen Sie die getrennten Wortteile wieder zusammen. Entfernen Sie
   den Bindestrich, wenn Sie das Wort verbinden, wenn es sich nicht um ein
   Wort mit Bindestrich handelt wie &bdquo;well-meaning&ldquo;. Siehe
   <a href="#em_dashes">Bindestriche, kurze und lange Gedankenstriche</a>
   f&uuml;r Beispiele aller Arten. Lassen Sie das zusammengef&uuml;gte Wort in der oberen
   Zeile und setzen Sie danach einen Zeilenumbruch, um das Zeilenformat
   zu erhalten &mdash; das macht es einfacher f&uuml;r die Korrekturleser
   in sp&auml;teren Runden.
   Folgt nach dem Wort noch ein Satzzeichen, so wird dieses ebenfalls
   in die obere Zeile &uuml;bernommen.
</p>
<p>W&ouml;rter wie &bdquo;to-day&ldquo; und &bdquo;to-morrow&ldquo;,
   die wir heute zusammenschreiben, stehen in alten B&uuml;chern oft
   noch mit Bindestrich. Lassen Sie sie mit Bindestrich, wenn sie so
   im Text stehen. Wenn Sie nicht sicher sind, ob der Autor einen
   Bindestrich verwendet hat, so lassen Sie ihn stehen, setzen ein
   <tt>*</tt> dahinter und verbinden das Wort wie hier: <tt>to-*day</tt>.
   Das Sternchen macht den Nachbearbeiter darauf aufmerksam, der den
   &Uuml;berblick &uuml;ber alle Seiten hat und feststellen kann,
   wie der Autor das Wort &uuml;blicherweise geschrieben hat.
</p>
<p>Wenn ein langer Gedankenstrich (em-dash) am Anfang oder Ende einer
   Zeile im OCR-Text erscheint, verbinden Sie ihn genauso mit der anderen
   Zeile, so dass keine Leerzeichen oder Zeilenumbr&uuml;che darin bleiben.
   Wenn allerdings der Autor den Gedankenstrich am Anfang oder Ende eines
   Absatzes oder einer Gedichtzeile benutzt hat, lassen Sie ihn wie er ist,
   ohne ihn zur n&auml;chsten Zeile zu verbinden. Siehe
   <a href="#em_dashes">Bindestriche, kurze und lange Gedankenstriche</a>
   f&uuml;r Beispiele.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Zur&uuml;ck nach oben</a></p>


<h3><a name="eop_hyphen">Trenn- und Gedankenstriche am Seitenende</a></h3>
<p>Trenn- und Gedankenstriche am Ende einer Seite werden Korrektur
   gelesen, indem Sie das Trennzeichen oder den Gedankenstrich am
   Ende der letzten Zeile stehen lassen. Setzen Sie ein Sternchen
   <tt>*</tt> hinter den Trenn- oder Gedankenstrich. Beispiel:
</p>
<table width="100%" align="center" border="1" cellpadding="4" cellspacing="0" summary="Trennstriche am Seitenende">
  <tbody>
    <tr><th align="left" bgcolor="cornsilk">Beispielvorlage:</th></tr>
    <tr>
      <td valign="top">something Pat had already become accus-</td>
    </tr>
    <tr><th align="left" bgcolor="cornsilk">Richtig korrigierter Text:</th></tr>
    <tr>
      <td valign="top"><tt>something Pat had already become accus-*</tt></td>
    </tr>
  </tbody>
</table>
<p>Auf Seiten, die mit einem Teil eines Wortes der vorhergehenden Seite
   bzw. mit einem Gedankenstrich beginnen, setzen Sie ein <tt>*</tt>
   vor das Teilwort bzw. den Gedankenstrich.
   Um das obige Beispiel fortzusetzen: Lesen Sie
</p>
<table width="100%" align="center" border="1" cellpadding="4" cellspacing="0" summary="Start-of-page Hyphenation example">
  <tbody>
    <tr><th align="left" bgcolor="cornsilk">Beispielvorlage:</th></tr>
    <tr>
      <td valign="top">tomed to from having to do his own family</td>
    </tr>
    <tr><th align="left" bgcolor="cornsilk">Richtig korrigierter Text:</th></tr>
    <tr>
      <td valign="top"><tt>*tomed to from having to do his own family</tt></td>
    </tr>
  </tbody>
</table>
<p>Die Sternchen zeigen dem Nachbearbeiter, dass das Wort beim
   Zusammenf&uuml;gen der einzelnen Seiten zum endg&uuml;ltigen
   E-Book verbunden werden muss. Bitte verbinden Sie die Fragmente
   nicht selbst &uuml;ber die Seitenumbr&uuml;che hinweg.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Zur&uuml;ck nach oben</a></p>


<h3><a name="period_p">Auslassungspunkte &bdquo;...&ldquo; (Ellipse)</a></h3>
<p>Die Richtlinien f&uuml;r Englisch und f&uuml;r andere Sprachen
   (LOTE, Languages Other Than English) sind unterschiedlich.
</p>
<p><b>ENGLISCH</b>: Eine Ellipse soll drei Punkte haben.
   Hinsichtlich der Leerzeichen werden die Punkte in der Satzmitte
   wie ein einzelnes Wort behandelt (d.h. ein Leerzeichen davor
   und dahinter). Am Ende des Satzes behandeln Sie die Ellipse
   wie ein Satzzeichen, also ohne Leerzeichen davor.
</p>
<p>Beachten Sie, dass am Satzende zus&auml;tzlich ein Satzzeichen
   steht. Ist das ein Punkt, sind es insgesamt 4 Punkte.
   Entfernen Sie vorhandene &uuml;berfl&uuml;ssige Punkte und
   erg&auml;nzen Sie falls n&ouml;tig fehlende, um immer die
   erforderlichen drei (oder vier) Punkte zu erhalten.
   Ein guter Hinweis darauf, dass man am Satzende ist, ist ein
   Gro&szlig;buchstabe am Anfang des n&auml;chsten Wortes oder
   ein Satzendezeichen (z.B. ein Frage- oder Ausrufezeichen).
</p>
<p><b>LOTE (Languages Other Than English):</b> Hier gilt die allgemeine
   Regel: &bdquo;Halten Sie sich so nah wie m&ouml;glich an die
   gedruckte Seite&ldquo;. F&uuml;gen Sie also Leerzeichen ein, wenn
   sie vor oder zwischen den Punkten vorhanden sind, und verwenden Sie
   die gleiche Zahl von Punkten wie auf der Vorlage. Manchmal ist das
   auf der gedruckten Seite nicht deutlich zu erkennen; in diesem Fall
   f&uuml;gen Sie ein <tt>[**unclear]</tt> ein, um den Nachbearbeiter
   auf die Stelle aufmerksam zu machen. (Anmerkung: Nachbearbeiter sollten
   diese regul&auml;ren Leerzeichen durch gesch&uuml;tzte Leerzeichen ersetzen.)
</p>
<!-- END RR -->
<p>Englische Beispiel:
</p>
<table width="100%" align="center" border="1" cellpadding="4" cellspacing="0" summary="Auslassungspunkte">
  <tbody>
    <tr>
      <th valign="top" bgcolor="cornsilk">Beispielvorlage:</th>
      <th valign="top" bgcolor="cornsilk">Richtig korrigierter Text:</th>
    </tr>
    <tr>
      <td valign="top">That I know .&nbsp;.&nbsp;. is true.</td>
      <td valign="top"><tt>That I know ... is true.</tt></td>
    </tr>
    <tr>
      <td valign="top">This is the end....</td>
      <td valign="top"><tt>This is the end....</tt></td>
    </tr>
    <tr>
      <td valign="top">The moving finger writes; and.&nbsp;.&nbsp;. The poet<br> surely had a pen though!</td>
      <td valign="top"><tt>The moving finger writes; and.... The poet<br> surely had a pen though! </tt></td>
    </tr>
    <tr>
      <td valign="top">Wherefore art thou Romeo.&nbsp;.&nbsp;.&nbsp;?</td>
      <td valign="top"><tt>Wherefore art thou Romeo...?</tt></td>
    </tr>
    <tr>
      <td valign="top">&ldquo;I went to the store,&nbsp;.&nbsp;.&nbsp;.&rdquo; said Harry.</td>
      <td valign="top"><tt>"I went to the store, ..." said Harry.</tt></td>
    </tr>
    <tr>
      <td valign="top">&ldquo;... And I did too!&rdquo; said Sally.</td>
      <td valign="top"><tt>"... And I did too!" said Sally.</tt></td>
    </tr>
    <tr>
      <td valign="top">&ldquo;Really?&nbsp;&nbsp;.&nbsp;.&nbsp;. Oh, Harry!&rdquo;</td>
      <td valign="top"><tt>"Really?... Oh, Harry!"</tt></td>
    </tr>
  </tbody>
</table>
<p class="backtotop"><a href="#top">Zur&uuml;ck nach oben</a></p>


<h3><a name="contract">Zusammenziehungen</a></h3>
<p>Entfernen Sie im Englischen Leerzeichen in Zusammenziehungen: zum Beispiel sollte
   <tt>would&nbsp;n't</tt> als <tt>wouldn't</tt> Korrektur gelesen werden
   und <tt>'t&nbsp;is</tt> als <tt>'tis</tt>.
</p>
<p>Diese L&uuml;cken waren h&auml;ufig eine Konvention der Drucker des 19.
   Jahrhunderts. Sie behielten den Zwischenraum bei, um darauf hinzuweisen,
   dass &bdquo;would&ldquo; und &bdquo;not&ldquo; urspr&uuml;nglich
   getrennte W&ouml;rter waren. Manchmal ist der Zwischenraum auch nur
   ein Artefakt der OCR-Software. In beiden F&auml;llen wird er entfernt.
</p>
<p>Unter Umst&auml;nden geben Projektmanager in den
   <a href="#comments">Projektkommentaren</a> vor, dass solche Leerzeichen
   nicht entfernt werden sollen. Dies ist vor allem bei Texten der Fall,
   die Umgangssprache, Dialekt oder Lyrik enthalten.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Zur&uuml;ck nach oben</a></p>


<h3><a name="fract_s">Br&uuml;che</a></h3>
<p>Korrigieren Sie <b>Br&uuml;che</b> folgenderma&szlig;en:
   <tt>&frac14;</tt> wird zu <tt>1/4</tt> und <tt>2&frac12;</tt>
   wird zu <tt>2-1/2</tt>. Der Bindestrich
   zwischen ganzer Zahl und Bruch verhindert bei der Nachbearbeitung,
   dass die Zeile an dieser Stelle umgebrochen wird.
   Wenn es nicht ausdr&uuml;cklich in den <a href="#comments">
   Projektkommentare</a> gefordert wird, verwenden Sie keine Bruchsymbole.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Zur&uuml;ck nach oben</a></p>


<h3><a name="a_chars">Akzente und nicht-ASCII-Zeichen</a></h3>
<p>Bitte lesen Sie diese Korrektur, indem Sie die richtigen UTF-8
   Zeichen verwenden. F&uuml;r Zeichen, die nicht in Unicode enthalten sind,
   siehe die Anweisungen der Projekt-Managers in den
   <a href="#comments">Projektkommentaren</a>.
   Wenn Zeichen nicht auf Ihrer Tastatur sind, lesen Sie <a href="#insert_char">
   Eingabe spezieller Zeichen</a> mit Informationen dar&uuml;ber,
   wie diese Zeichen beim Korrekturlesen eingegeben werden.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Zur&uuml;ck nach oben</a></p>


<h3><a name="d_chars">Buchstaben mit diakritischen Zeichen</a></h3>
<p>In manchen Projekten gibt es Buchstaben mit speziellen Kennzeichnungen
   &uuml;ber oder unter dem normalen lateinischen Buchstaben A ... Z.
   Sie werden <i>diakritische Zeichen</i> genannt und weisen auf eine
   spezielle Aussprache dieses Buchstabens hin.
</p>
<p>Wenn ein solches Zeichen in Unicode nicht existiert, geben Sie es
   mittels <i>kombinierender diakritischer Zeichen</i> ein. Das sind
   Unicode-Symbole, die nicht allein stehen k&ouml;nnen, vielmehr
   erscheinen sie &uuml;ber (oder unter) dem Buchstaben, nach dem sie
   gesetzt sind. Zun&auml;chst wird der Basisbuchstabe getippt, dann
   das Kombinationszeichen. Dazu benutzt man Applets und Programme
   wie bei <a href="#insert_char">Eingabe von speziellen Zeichen</a> genannt.
</p>
<p>Auf manchen Rechnern erscheinen diakritische Zeichen nicht genau
   dort, wo sie sein sollten, sondern z. B. nach rechts verschoben.
   Sie sollten dennoch benutzt werden, denn auf anderen Rechnern
   werden sie korrekt angezeigt. Jedoch: Wenn Sie aus irgendeinem
   Grund Kombinationszeichen nicht ordentlich sehen oder eingeben
   k&ouml;nnen, markieren Sie einen solchen Buchstaben mit einer
   [**Notiz]. Beachten Sie, dass es auch &bdquo;spacing modifier
   letters&ldquo; (Zeichen, die wie Buchstaben eigenen Platz beanspruchen)
   gibt; diese sollten nicht benutzt werden.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Zur&uuml;ck nach oben</a></p>


<h3><a name="f_chars">Nicht-lateinische Zeichen</a></h3>
<p>Manche Projekte beinhalten Zeichen aus nicht-lateinischen Schriften,
   also andere als die lateinischen Buchstaben A...Z &ndash;
   beispielsweise griechische, kyrillische (verwendet in Russisch,
   Slawisch und anderen Sprachen), hebr&auml;ische oder arabische Buchstaben.
</p>
<p>Diese Zeichen sollten in den Text eingegeben werden wie lateinische
   Buchstaben (<b>OHNE Transliteration!</b>).
</p>
<p>Ist ein Dokument vollst&auml;ndig in einer nicht-lateinischen
   Schrift gedruckt, so ist es am besten, einen Tastaturtreiber zu
   installieren, der die entsprechende Sprache unterst&uuml;tzt. Sehen
   Sie im Handbuch Ihres Betriebssystems nach, wie das gemacht wird.
</p>
<p>Taucht die Schrift nur bei gelegentlich auf, k&ouml;nnen
   Sie auch ein externes Programm zur Eingabe verwenden. Siehe
   <a href="#insert_char">Einsetzen spezieller Zeichen</a> f&uuml;
   einige dieser Programme.
</p>
<p>Sollten Sie sich hinsichtlich eines Zeichens oder Akzents unsicher
   sein, markieren Sie die Stelle mit einer [** Notiz], um den folgenden
   Korrekturleser oder den Nachbearbeiter aufmerksam zu machen.
</p>
<p>Bei Schriften, die nicht so einfach transliteriert werden k&ouml;nnen,
   wie z.B. Arabisch, umgeben Sie den Text mit der entsprechenden Markierung
   <tt>[Arabic:&nbsp;**]</tt> und lassen ihn wie eingescannt stehen.
   Schlie&szlig;en Sie die <tt>**</tt> ein, damit der Nachbearbeiter
   den Text sp&auml;ter leichter finden und bearbeiten kann.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Zur&uuml;ck nach oben</a></p>


<h3><a name="supers">Hochgestellte Zeichen</a></h3>
<p>In &auml;lteren B&uuml;chern wurden W&ouml;rter h&auml;ufig mit
   verk&uuml;rzten Endungen dargestellt, die dann hochgestellt wurden.
   Lesen Sie diese Korrektur, indem Sie einen einzelnen Zirkumflex ^
   einsetzen, gefolgt von dem hochgestellten Text. Wenn der hochgestellte
   Text mehr als ein Zeichen hat, umgeben Sie ihn zus&auml;tzlich mit
   geschweiften Klammern <tt>{</tt> und <tt>}</tt>. Zum Beispiel:
</p>
<table width="100%" align="center" border="1" cellpadding="4" cellspacing="0" summary="Superscripts example">
  <tbody>
    <tr><th align="left" bgcolor="cornsilk">Beispielvorlage:</th></tr>
    <tr>
      <td valign="top">Gen<sup>rl</sup> Washington defeated L<sup>d</sup> Cornwall's army.</td>
    </tr>
    <tr><th align="left" bgcolor="cornsilk">Richtig korrigierter Text:</th></tr>
    <tr>
      <td valign="top"><tt>Gen^{rl} Washington defeated L^d Cornwall's army.</tt></td>
    </tr>
  </tbody>
</table>
<p>Wenn der hochgestellte Text ein Fu&szlig;notenzeichen ist, siehe stattdessen den Abschnitt
   <a href="#footnotes">Fu&szlig;noten</a>.
</p>
<p>Der Projektmanager kann in den <a href="#comments">Projektkommentaren</a>
   angeben, dass hochgestellter Text abweichend markiert wird.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Zur&uuml;ck nach oben</a></p>


<h3><a name="subscr">Tiefgestellte Zeichen</a></h3>
<p>Tiefgestellte Zeichen kommen oft in wissenschaftlichen Texten vor,
   in anderen Werken sind sie nicht &uuml;blich. Korrektur gelesen
   werden sie durch Einsetzen eines Unterstrichs <tt>_</tt> und
   Umschlie&szlig;en des Textes mit geschweiften Klammern <tt>{</tt>
   und <tt>}</tt>. Zum Beispiel:
</p>
<table width="100%" align="center" border="1" cellpadding="4" cellspacing="0" summary="Subscripts example">
  <tbody>
    <tr><th align="left" bgcolor="cornsilk">Beispielvorlage:</th></tr>
    <tr>
      <td valign="top">H<sub>2</sub>O.</td>
    </tr>
    <tr><th align="left" bgcolor="cornsilk">Richtig korrigierter Text:</th></tr>
    <tr>
      <td valign="top"><tt>H_{2}O.</tt></td>
    </tr>
  </tbody>
</table>
<!-- END RR -->
<p class="backtotop"><a href="#top">Zur&uuml;ck nach oben</a></p>


<h3><a name="drop_caps">&Uuml;berdimensionale, verzierte Gro&szlig;buchstaben als Er&ouml;ffnung (Initialen)</a></h3>
<p>Lesen Sie einen gro&szlig;en, verzierten Buchstaben am Beginn
   eines Kapitels, Abschnitts oder Absatzes Korrektur wie einen
   gew&ouml;hnlichen Buchstaben. Siehe auch den Abschnitt <a href="#chap_head">Kapitel&uuml;berschriften</a>
   der Korrekturlese-Richtlinien.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Zur&uuml;ck nach oben</a></p>


<h3><a name="small_caps">W&ouml;rter in Kapit&auml;lchen</a></h3>
<p>Bitte lesen Sie nur die Zeichen in
   <span style="font-variant: small-caps">Kapit&auml;lchen</span>
   (Gro&szlig;buchstaben, die kleiner als die Standardtypen sind)
   Korrektur. Machen Sie sich dabei
   keine Gedanken &uuml;ber Gro&szlig;- und Kleinschreibung. Sind die
   Zeichen schon GROSSBUCHSTABEN, kleinbuchstaben oder GeMIScht, so
   lassen Sie sie in GROSSBUCHSTABEN, kleinbuchstaben oder GeMIScht stehen.
   Kapit&auml;lchen erscheinen manchmal mit <tt>&lt;sc&gt;</tt> davor
   und <tt>&lt;/sc&gt;</tt> dahinter.
   Siehe hierzu <a href="#formatting">Formatierungen</a>.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Zur&uuml;ck nach oben</a></p>


<table width="100%" border="0" cellspacing="0" cellpadding="6" summary="Korrekturlesen auf der Abschnittsebene">
  <tbody>
    <tr>
      <td bgcolor="silver"><h2 style="margin-bottom: 0; margin-top: 0;">Korrekturlesen auf der Abschnittsebene:</h2></td>
    </tr>
  </tbody>
</table>


<h3><a name="line_br">Zeilenumbr&uuml;che</a></h3>
<p><b>Lassen Sie alle Zeilenumbr&uuml;che stehen</b>, damit nachfolgende
   Freiwillige die Zeilen im Text bequem mit den Zeilen in der Vorlage vergleichen
   k&ouml;nnen. Seien Sie dabei besonders vorsichtig, wenn Sie
   <a href="#eol_hyphen">getrennte W&ouml;rter</a> wieder zusammenf&uuml;gen
   oder W&ouml;rter um <a href="#em_dashes">Gedankenstriche</a> bewegen.
   Sollte der vorhergehende Korrekturleser die Zeilenumbr&uuml;che
   entfernt haben, setzen Sie diese bitte wieder so ein, dass sie der
   Vorlage entsprechen.
</p>
<!-- END RR -->
<!-- We should have an example right here for this. -->
<p class="backtotop"><a href="#top">Zur&uuml;ck nach oben</a></p>


<h3><a name="chap_head">Kapitel&uuml;berschriften</a></h3>
<p>Lesen Sie die Kapitel&uuml;berschriften so Korrektur, wie sie
   im Text stehen.
</p>
<p>Eine Kapitel&uuml;berschrift steht normalerweise unterhalb der
   <a href="#page_hf">Kopfzeile</a> und hat keine Seitenzahl in
   derselben Zeile. Kapitel&uuml;berschriften sind h&auml;ufig in
   Gro&szlig;buchstaben gedruckt. Ist das der Fall, so behalten Sie
   die Gro&szlig;buchstaben bei.
</p>
<p>Achten Sie am Beginn des ersten Absatzes besonders auf fehlende
   doppelte Anf&uuml;hrungszeichen, die von manchen Verlagen am
   Kapitelanfang weggelassen oder von der OCR wegen eines gro&szlig;en
   Initials im Original &bdquo;&uuml;bersehen&ldquo; wurden. Wenn der
   Absatz mit direkter Rede beginnt, f&uuml;gen Sie das doppelte
   Anf&uuml;hrungszeichen ein.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Zur&uuml;ck nach oben</a></p>


<h3><a name="para_space">Absatzabst&auml;nde und -einr&uuml;ckungen</a></h3>
<p>Setzen Sie eine Leerzeile vor jeden Absatz, auch wenn er am Anfang
   der Seite beginnt. Sie sollten den Absatzanfang nicht einr&uuml;cken,
   aber wenn er bereits einger&uuml;ckt ist, so bem&uuml;hen Sie sich
   nicht, die Leerzeichen zu entfernen &ndash; das kann automatisch in
   der Nachbearbeitung erledigt werden.
</p>
<p>Ein Beispiel finden Sie im Abschnitt <a href="#para_side">Randnoten</a>
   weiter unten.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Zur&uuml;ck nach oben</a></p>


<h3><a name="page_hf">Kopf- und Fu&szlig;zeilen</a></h3>
<p>L&ouml;schen Sie Kopf- und Fu&szlig;zeilen, aber
   <em>nicht</em> <a href="#footnotes">Fu&szlig;noten</a>.
</p>
<p>Kopfzeilen befinden sich normalerweise ganz oben auf der Seite
   in derselben H&ouml;he wie die Seitenzahl. Kopfzeilen k&ouml;nnen
   im ganzen Buch (oft der Buchtitel und der Name des Autors) oder
   f&uuml;r jedes Kapitel (oft die Kapitelnummer) identisch, aber
   auch auf jeder Seite unterschiedlich sein (mit dem Inhalt der
   einzelnen Seite). Entfernen Sie unterschiedslos alle, einschlie&szlig;lich
   der Seitenzahl.
   Zus&auml;tzliche Leerzeilen sollten entfernt werden au&szlig;er da,
   wo wir sie absichtlich beim Korrekturlesen einf&uuml;gen. Leerzeilen
   am Seitenende sind in Ordnung, sie werden entfernt, wenn Sie die Seite
   speichern.
</p>
<p>Fu&szlig;zeilen stehen am unteren Rand der Vorlage und k&ouml;nnen
   eine Seitenzahl enthalten oder andere zus&auml;tzliche Zeichen,
   die nicht Teil dessen sind, was der Autor schrieb.
</p>
<!-- END RR -->

<p>Im Gegensatz dazu beginnt die <a href="#chap_head">Kapitel&uuml;berschrift</a>
   gew&ouml;hnlich weiter unten auf der Seite und hat keine Seitenzahl auf derselben
   Zeile (siehe Beispiel unten).
</p>
<table width="100%" align="center" border="1" cellpadding="4" cellspacing="0" summary="Kopf- und Fu&szlig;zeilen">
  <tbody>
    <tr><th align="left" bgcolor="cornsilk">Beispielvorlage:</th></tr>
    <tr align="left">
      <td width="100%" valign="top"><img src="../foot.png" alt="" width="500" height="860"><br>
      </td>
    </tr>
    <tr><th align="left" bgcolor="cornsilk">Richtig korrigierter Text:</th></tr>
    <tr>
      <td width="100%" valign="top">
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
      </td>
    </tr>
  </tbody>
</table>
<p class="backtotop"><a href="#top">Zur&uuml;ck nach oben</a></p>


<h3><a name="illust">Abbildungen</a></h3>
<p>Ignorieren Sie Illustrationen, aber lesen Sie alle Bildunterschriften Korrektur
   und erhalten Sie dabei die Zeilenumbr&uuml;che. Wenn die Bildunterschrift
   mitten in einem Absatz steht, verwenden Sie Leerzeilen, um sie vom
   Rest zu trennen. Text, der eine Bildunterschrift sein k&ouml;nnte (oder
   ein Teil davon), wie "Siehe Seite 66" sollte einbezogen werden, ebenso ein
   Titel in den Grenzen der Illustration.
</p>
<p>Die meisten Seiten mit Abbildung, aber ohne Text sind schon mit
   <tt>[Blank Page]</tt> gekennzeichnet. Belassen Sie diese
   Markierung so, wie sie ist.
</p>
<!-- END RR -->

<table width="100%" align="center" border="1" cellpadding="4" cellspacing="0" summary="Illustration example">
  <tbody>
    <tr>
      <th align="left" bgcolor="cornsilk">Beispielvorlage:</th>
    </tr>
    <tr align="left">
      <td width="100%" valign="top"> <img src="../illust.png" alt="" width="500" height="525"> <br>
      </td>
    </tr>
    <tr>
      <th align="left" bgcolor="cornsilk">Richtig korrigierter Text:</th>
    </tr>
    <tr>
      <td width="100%" valign="top">
        <p><tt>Martha told him that he had always been her ideal and<br>
        that she worshipped him.<br>
        <br>
        Frontispiece<br>
        Her Weight in Gold
        </tt></p>
      </td>
    </tr>
  </tbody>
</table>

<br>
<table width="100%" align="center" border="1" cellpadding="4" cellspacing="0" summary="Illustration in Middle of Paragraph">
  <tbody>
    <tr>
      <th align="left" bgcolor="cornsilk">Beispielvorlage (Abbildung mitten im Absatz):</th>
    </tr>
    <tr align="left">
      <td width="100%" valign="top"> <img src="../illust2.png" alt="" width="500" height="514"> <br>
      </td>
    </tr>
    <tr>
      <th align="left" bgcolor="cornsilk">Richtig korrigierter Text:</th>
    </tr>
    <tr valign="top">
      <td>
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
      </td>
    </tr>
  </tbody>
</table>
<p class="backtotop"><a href="#top">Zur&uuml;ck nach oben</a></p>


<h3><a name="footnotes">Fu&szlig;noten/Endnoten</a></h3>
<p>Lesen Sie Fu&szlig;noten Korrektur, indem Sie den
   Text der Fu&szlig;note am unteren Seitenrand belassen und ein
   Fu&szlig;notenzeichen an die Stelle setzen, auf die der Text sich bezieht.
</p>
<p>Im Haupttext sollte das Zeichen, das den Ort der
   Fu&szlig;note kennzeichnet, mit eckigen Klammern (<tt>[</tt>
   und <tt>]</tt>) umgeben und unmittelbar rechts neben das Wort<tt>[1]</tt>
   oder das Satzzeichen gesetzt werden,<tt>[2]</tt> auf das sich die
   Fu&szlig;note bezieht (wie im Text dargestellt und in den beiden
   Beispielen in diesem Satz).
   Sind Fu&szlig;noten mit einer Reihe von Sonderzeichen markiert (*,
   &dagger;, &Dagger;, &sect; usw.), dann ersetzen wir sie alle durch
   <tt>[*]</tt> sowohl im Text als auch durch <tt>*</tt> bei der
   Fu&szlig;note selbst.
</p>
<p>Am Ende der Seite lesen Sie den Text der Fu&szlig;note Korrektur, wie er gedruckt
   ist, einschlie&szlig;lich der Zeilenumbr&uuml;che. Achten Sie darauf,
   dass Sie vor der Fu&szlig;note das gleiche Fu&szlig;notenzeichen
   verwenden wie im Text.
   Benutzen Sie nur das Zeichen selbst zur Markierung ohne irgendwelche
   Klammern oder andere Satzzeichen.
</p>
<p>Setzen Sie jede Fu&szlig;note auf eine eigene Zeile in der
   Reihenfolge ihres Auftretens im Text mit einer Leerzeile vor jeder
   Fu&szlig;note.
</p>
<!-- END RR -->
<p>&Uuml;bernehmen Sie keine horizontalen Linien, die die Fu&szlig;noten
   vom Haupttext trennen.
</p>
<p><b>Endnoten</b> sind einfach Fu&szlig;noten, die am Ende eines
   Kapitels oder am Ende des Buches zusammengefasst sind, statt unten
   auf jeder Seite. Sie werden in der gleichen Weise Korrektur gelesen
   wie Fu&szlig;noten. Wo Sie ein Endnotenzeichen im Text finden, umgeben
   Sie es mit eckigen Klammern (<tt>[</tt> und <tt>]</tt>).  Wenn Sie
   eine Seite mit Endnoten Korrektur lesen, setzen Sie Leerzeilen
   vor jede Endnote, damit klar ist, wo jede beginnt und endet.
</p>
<!-- Need an example of Endnotes, maybe? Good idea!-->

<p>Fu&szlig;noten in <a href="#tables">Tabellen</a> sollten
   dort bleiben, wo sie im Original stehen.
</p>

<table width="100%" border="1" cellpadding="4" cellspacing="0" align="center" summary="Footnote Example">
  <tbody>
    <tr>
      <th valign="top" align="left" bgcolor="cornsilk">Beispielvorlage:</th>
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
      <th valign="top" align="left" bgcolor="cornsilk">Richtig korrigierter Text:</th>
    </tr>
    <tr valign="top">
      <td>
        <tt>The principal persons involved in this argument were Caesar[*], former military</tt><br>
        <tt>leader and Imperator, and the orator Cicero[*]. Both were of the aristocratic</tt><br>
        <tt>(Patrician) class, and were quite wealthy.</tt><br>
        <br>
        <tt>* Gaius Julius Caesar.</tt><br>
        <br>
        <tt>* Marcus Tullius Cicero.</tt>
      </td>
    </tr>
  </tbody>
</table>
<br>
<table width="100%" align="center" border="1" cellpadding="4" cellspacing="0" summary="Footnote Example">
  <tbody>
    <tr><th align="left" bgcolor="cornsilk">Beispielvorlage &ndash; Gedicht mit Fu&szlig;noten:</th></tr>
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
    <tr><th align="left" bgcolor="cornsilk">Richtig korrigierter Text:</th></tr>
    <tr>
      <td valign="top">
        <tt>
        Mary had a little lamb[1]<br>
        Whose fleece was white as snow<br>
        And everywhere that Mary went<br>
        The lamb was sure to go!<br>
        <br>
        1 This lamb was obviously of the Hampshire breed,<br>
        well known for the pure whiteness of their wool.<br>
        </tt>
      </td>
    </tr>
  </tbody>
</table>
<p class="backtotop"><a href="#top">Zur&uuml;ck nach oben</a></p>


<h3><a name="para_side">Randnoten (Marginalien)</a></h3>
<p>Manche B&uuml;cher haben kurze Zusammenfassungen einzelner
   Abschnitte auf dem Seitenrand neben dem Text stehen. Diese
   werden Randnoten (Marginalien) genannt. Lesen Sie den Text
   der Marginalie Korrektur, wie er gedruckt ist, und behalten
   Sie auch die Zeilenumbr&uuml;che bei (wobei Sie Trenn- und
   Gedankenstriche wie &uuml;blich behandeln). Lassen Sie eine Leerzeile vor
   und nach der Marginalie, damit sie vom &uuml;brigen Text unterschieden
   werden kann. Es kann passieren, dass die OCR-Software Marginalien
   irgendwo auf der Seite platziert oder sie sogar mit dem &uuml;brigen
   Text vermischt. Trennen Sie den Text so, dass der Text der Marginalie
   zusammensteht, aber k&uuml;mmern Sie sich nicht um ihre Position auf
   der Seite.
</p>
<!-- END RR -->

<table width="100%" align="center" border="1" cellpadding="4" cellspacing="0" summary="Sidenotes example">
  <tbody>
    <tr valign="top">
      <th align="left" bgcolor="cornsilk">Beispielvorlage:</th>
    </tr>
    <tr valign="top">
      <td width="100%" align="left"><img src="../side.png" alt="" width="550" height="800"><br>
      </td>
    </tr>
    <tr valign="top">
      <th align="left" bgcolor="cornsilk">Richtig korrigierter Text:</th>
    </tr>
    <tr valign="top">
      <td width="100%">
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
      </td>
    </tr>
  </tbody>
</table>
<p class="backtotop"><a href="#top">Zur&uuml;ck nach oben</a></p>


<h3><a name="mult_col">Mehrspaltige Texte</a></h3>
<p>Lesen Sie normalen Text, der in zwei Spalten gedruckt wurde, als eine Spalte.
   Dabei kommt die Spalte ganz links zuerst, anschlie&szlig;end der Text der
   n&auml;chsten und so weiter. Sie brauchen den Umbruch der Spalten
   nicht extra zu kennzeichnen; verbinden Sie einfach die Spalten.
   Sie finden ganz am Ende des Beispiels bei <a href="#para_side">Marginalien</a>
   ein Beispiel mit mehreren Spalten.
</p>
<p>Lesen Sie dazu auch die Abschnitte <a href="#bk_index">Sachregister
   und Schlagwortverzeichnisse</a> sowie <a href="#tables">Tabellen</a>.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Zur&uuml;ck nach oben</a></p>


<h3><a name="tables">Tabellen</a></h3>
<p>Die Aufgabe eines Korrekturlesers ist es sicherzustellen, dass
   die gesamte Information in einer Tabelle richtig ist. Lassen Sie zwischen
   einzelnen Tabelleneintr&auml;gen in einer Zeile genug Platz. Trennen sie die Eintr&auml;ge
   durch Leerstellen wenn n&ouml;tig, aber k&uuml;mmern Sie sich nicht um die pr&auml;zise Ausrichtung.
   Behalten Sie die Zeilenumbr&uuml;che bei (wobei Sie <a href="#eol_hyphen">Trenn-
   und Gedankenstriche</a> wie &uuml;blich behandeln). Ignorieren Sie Punkte oder andere Satzzeichen
   (F&uuml;hrungszeichen), die zur Ausrichtung der Eintr&auml;ge gesetzt werden.
</p>
<p><b>Fu&szlig;noten</b> in Tabellen sollten dort bleiben, wo sie im
   Original stehen. Zu den Einzelheiten siehe <a href="#footnotes">Fu&szlig;noten</a>.
</p>
<!-- END RR -->
<table width="100%" align="center" border="1" cellpadding="4" cellspacing="0" summary="Table Example">
  <tbody>
    <tr><th align="left" bgcolor="cornsilk">Beispielvorlage:</th></tr>
    <tr align="left">
      <td width="100%" valign="top"> <img src="../table2.png" alt="" width="500" height="304"><br>
      </td>
    </tr>
    <tr><th align="left" bgcolor="cornsilk">Richtig korrigierter Text:</th></tr>
    <tr>
      <td width="100%" valign="top">
<pre><tt>TABLE II.

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
</tt></pre>
      </td>
    </tr>
  </tbody>
</table>
<br>
<table width="100%" align="center" border="1" cellpadding="4" cellspacing="0" summary="Table Example">
  <tbody>
    <tr><th align="left" bgcolor="cornsilk">Beispielvorlage:</th></tr>
    <tr align="left">
      <td width="100%" valign="top"> <img src="../table3.png" alt="" width="480" height="231"><br>
      </td>
    </tr>
    <tr><th align="left" bgcolor="cornsilk">Richtig korrigierter Text:</th></tr>
    <tr>
      <td width="100%" valign="top">
<pre><tt>Agents.   Objects.
        {     1st person, I,       me,
            {  2d   "    thou,   thee,
Singular  {      "  mas.  {  he,   him,
           {  3d  "  fem.  {  she,   her,
         {            it,        it.

       {  1st person, we,         us,
Plural   {   2d   "  ye, or you,   you,
        {  3d  "   they,         them,
                  who,       whom.
</tt></pre>
      </td>
    </tr>
  </tbody>
</table>
<p class="backtotop"><a href="#top">Zur&uuml;ck nach oben</a></p>


<h3><a name="poetry">Gedichte/Epigramme</a></h3>
<p>F&uuml;gen Sie eine Leerzeile vor dem Gedicht oder Epigramm ein
   und eine danach, damit die Formatierer Anfang und Ende deutlich
   sehen k&ouml;nnen.
   Lassen Sie jede Zeile linksb&uuml;ndig ausgerichtet und behalten
   Sie die Zeilenumbr&uuml;che bei. F&uuml;gen Sie eine Leerzeile zwischen
   den Strophen ein, wenn sie in der Vorlage vorhanden ist.
</p>
<p><a href="#line_no">Zeilennummern</a> in Gedichten sollten beibehalten werden.
</p>
<p>Lesen Sie in jedem Falle die <a href="#comments">Projektkommentare</a>
   des Textes, den Sie bearbeiten.
</p>
<!-- END RR -->

<table width="100%" align="center" border="1" cellpadding="4" cellspacing="0" summary="Poetry Example">
  <tbody>
    <tr><th align="left" bgcolor="cornsilk">Beispielvorlage:</th></tr>
    <tr align="left">
      <td width="100%" valign="top"> <img src="../poetry2.png" alt="" width="480" height="385"> <br>
      </td>
    </tr>
    <tr><th align="left" bgcolor="cornsilk">Richtig korrigierter Text:</th></tr>
    <tr>
      <td width="100%" valign="top">
        <tt>THE CHAMBERED NAUTILUS<br>
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
        Wrecked is the ship of pearl!</tt>
      </td>
    </tr>
  </tbody>
</table>
<p class="backtotop"><a href="#top">Zur&uuml;ck nach oben</a></p>


<h3><a name="line_no">Zeilennummern</a></h3>
<p>sind h&auml;ufig in Gedichtb&auml;nden und erscheinen gew&ouml;hnlich
   in der N&auml;he des Randes in jeder f&uuml;nften oder zehnten Zeile.
   Behalten Sie Zeilennummern bei, trennen Sie sie aber mit einigen
   Leerzeichen vom anderen Text, damit die  Formatierer sie leicht finden
   k&ouml;nnen. Da der Text von Gedichten f&uuml;r
   das E-Book nicht umgebrochen wird, sind die Zeilennummern auch
   f&uuml;r den Leser von Interesse.
</p>
<!-- END RR -->
<!-- We need an example image and text for this. -->
<p class="backtotop"><a href="#top">Zur&uuml;ck nach oben</a></p>


<h3><a name="next_word">Einzelne W&ouml;rter am unteren Seitenrand</a></h3>
<p>Entfernen Sie diese, auch wenn es die zweite H&auml;lfte eines
   getrennten Wortes ist.
</p>
<p>In einigen &auml;lteren B&uuml;chern verweist das einzelne Wort am
   unteren Seitenrand (genannt &bdquo;Kustos&ldquo;, &uuml;blicherweise
   am rechten Rand gedruckt) auf das erste Wort auf der n&auml;chsten
   Seite des Buches. Es half dem Drucker, die richtige R&uuml;ckseite
   (genannt &bdquo;Verso&ldquo;) zu drucken, und seinen Gehilfen, die
   Seiten f&uuml;r das Binden vorzubereiten. Au&szlig;erdem diente es
   als Lesehilfe, damit der Leser nicht mehr als eine Seite umbl&auml;tterte.
</p>
<!-- END RR -->
<!-- We need an example here! -->
<p class="backtotop"><a href="#top">Zur&uuml;ck nach oben</a></p>


<table width="100%" border="0" cellspacing="0" cellpadding="6" summary="Korrekturlesen auf der Seitenebene">
  <tbody>
    <tr>
      <td bgcolor="silver"><h2 style="margin-bottom: 0; margin-top: 0;">Korrekturlesen auf der Seitenebene:</h2></td>
    </tr>
  </tbody>
</table>


<h3><a name="blank_pg">Leere Seiten</a></h3>
<p>Die meisten leeren Seiten bzw. Seiten, die zwar eine Abbildung,
   aber keinen Text enthalten, sind bereits mit der Markierung
   <tt>[Blank Page]</tt> gekennzeichnet. K&uuml;mmern Sie sich
   nicht darum. Wenn die Seite leer und ohne [Blank Page] ist,
   braucht die Markierung nicht eingef&uuml;gt zu werden.
</p>
<p>Wenn im Korrekturfenster Text erscheint, aber eine leere Seite
   als Vorlage, oder wenn eine Buchseite mit Text angezeigt wird,
   aber kein Text im Korrekturfenster, dann halten Sie sich an die
   Anweisungen in den Abschnitten <a href="#bad_image">Schlechte
   Vorlagen</a> und <a href="#bad_text">Falsche Vorlage zum Text</a>.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Zur&uuml;ck nach oben</a></p>


<h3><a name="title_pg">Vorder- und R&uuml;ckseiten von Titelbl&auml;ttern</a></h3>
<p>Lesen Sie den gesamten Text so Korrektur, wie er auf der Seite
   gedruckt ist, einerlei ob nur Gro&szlig;buchstaben, Gro&szlig;-
   und Kleinschreibung o.&nbsp;&auml;., einschlie&szlig;lich
   Publikationsjahr und Copyright-Angaben.
</p>
<p>In &auml;lteren B&uuml;chern ist der erste Buchstabe oft eine
   gro&szlig;e, verzierte Grafik &ndash; machen Sie daraus einfach
   den dargestellten Buchstaben.
</p>
<!-- END RR -->

<table width="100%" align="center" border="1" cellpadding="4" cellspacing="0" summary="Title Page Example">
  <tbody>
    <tr>
      <th align="left" bgcolor="cornsilk">Beispielvorlage:</th>
    </tr>
    <tr align="left">
      <td width="100%" valign="top"><img src="../title.png" width="500" height="520" alt=""><br>
      </td>
    </tr>
    <tr>
      <th align="left" bgcolor="cornsilk">Richtig korrigierter Text:</th>
    </tr>
    <tr>
      <td width="100%" valign="top">
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
      </td>
    </tr>
  </tbody>
</table>
<p class="backtotop"><a href="#top">Zur&uuml;ck nach oben</a></p>


<h3><a name="toc">Inhaltsverzeichnisse</a></h3>
<p>Lesen Sie das Inhaltsverzeichnis so Korrektur, wie es im Buch
   abgedruckt ist, einerlei ob in Gro&szlig;buchstaben, in Gro&szlig;-
   und Kleinschreibung o.&nbsp;&auml;. Wenn sie in
   <span style="font-variant: small-caps">Kapit&auml;lchen</span> stehen,
   gelten die Richtlinien f&uuml;r <a href="#small_caps">Kapit&auml;lchen</a>.
</p>
<p>Ignorieren Sie Punkte oder andere Satzzeichen (F&uuml;hrungszeichen), die zum
   Ausrichten der Seitenzahlen verwendet werden. Diese werden sp&auml;ter
   entfernt.
</p>
<!-- END RR -->

<table width="100%" align="center" border="1" cellpadding="4" cellspacing="0" summary="Table of Contents example">
  <tbody>
    <tr>
      <th align="left" bgcolor="cornsilk">Beispielvorlage:</th>
    </tr>
    <tr align="left">
      <td width="100%" valign="top"><img src="../tablec.png" alt="" width="500" height="650">
      </td>
    </tr>
    <tr>
      <th align="left" bgcolor="cornsilk">Richtig korrigierter Text:</th>
    </tr>
    <tr>
      <td width="100%" valign="top">
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
        OUTSIDE&nbsp;&nbsp;&nbsp;,,,..&nbsp;&nbsp;....,&nbsp;221</tt></p>
      </td>
    </tr>
  </tbody>
</table>
<p class="backtotop"><a href="#top">Zur&uuml;ck nach oben</a></p>


<h3><a name="bk_index">Sachregister und Schlagwortverzeichnisse</a></h3>
<p>Sie brauchen die Zahlen nicht so auszurichten, wie sie auf der
   Vorlage aussehen. Stellen Sie lediglich sicher, dass Zahlen
   und Zeichensetzung mit der gescannten Vorlage &uuml;bereinstimmen,
   und behalten Sie die Zeilenumbr&uuml;che bei.
</p>
<p>Die besondere Formatierung der Register erfolgt sp&auml;ter im
   Prozess. Aufgabe des Korrekturlesers ist es, sicherzustellen,
   dass Text und Zahlen insgesamt korrekt sind.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Zur&uuml;ck nach oben</a></p>


<h3><a name="play_n">Dramen: Rollennamen/Regieanweisungen</a></h3>
<p>Innerhalb der Dialoge behandeln Sie einen Wechsel des Sprechers
   als einen neuen Absatz, mit jeweils einer Leerzeile davor.
   Wenn der Name des Sprechers auf einer eigenen Zeile steht, behandeln
   Sie auch diesen als eigenen Absatz.
</p>
<p>Regieanweisungen werden so formatiert, wie sie im Originaltext sind.
   Steht die Anweisung in einer eigenen Zeile, dann lesen Sie sie so
   Korrektur; steht sie am Ende einer Dialogzeile, dann lassen Sie sie dort.
   Regieanweisungen beginnen h&auml;ufig mit einer &ouml;ffnenden
   Klammer und lassen die schlie&szlig;ende Klammer weg. Diese Konvention
   wird beibehalten; schlie&szlig;en Sie die Klammern nicht.
</p>
<p>Manchmal, vor allem in metrischen Texten, wird ein Wort einer zu
   langen Zeile getrennt und oberhalb oder unterhalb hinter eine
   Klammer ( platziert, statt in eine eigene Zeile. Bitte verbinden Sie
   das Wort wie bei einer normalen <a href="#eol_hyphen">Trennung am Zeilenende</a>.
   Ein Beispiel finden Sie <a href="#play4">hier</a>.
</p>
<p>Bitte pr&uuml;fen Sie die <a href="#comments">Projektkommentare</a>,
   da der Projektmanager eine andere Formatierung festlegen kann.
</p>
<!-- END RR -->

<table width="100%" align="center" border="1" cellpadding="4" cellspacing="0" summary="Play Example 1">
  <tbody>
    <tr>
      <th align="left" bgcolor="cornsilk">Beispielvorlage:</th>
    </tr>
    <tr align="left">
      <td width="100%" valign="top"><img src="../play1.png" width="500" height="430" alt=""><br>
      </td>
    </tr>
    <tr>
      <th align="left" bgcolor="cornsilk">Richtig korrigierter Text:</th>
    </tr>
    <tr>
      <td width="100%" valign="top">
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
      </td>
    </tr>
  </tbody>
</table>
<br>
<a name="play4"><!-- Example --></a>
<table width="100%" align="center" border="1" cellpadding="4" cellspacing="0" summary="Play Example 4">
  <tbody>
    <tr>
      <th align="left" bgcolor="cornsilk">Beispielvorlage:</th>
    </tr>
    <tr align="left">
      <td width="100%" valign="top"><img src="../play4.png" width="502" height="98" alt=""><br>
      </td>
    </tr>
    <tr>
      <th align="left" bgcolor="cornsilk">Richtig korrigierter Text:</th>
    </tr>
    <tr>
      <td width="100%" valign="top">
        <p><tt>
        Am. Sure you are fasting;<br>
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
<p class="backtotop"><a href="#top">Zur&uuml;ck nach oben</a></p>


<h3><a name="anything">Sonstige Besonderheiten und Behandlung von Unklarheiten</a></h3>
<p>Wenn Sie w&auml;hrend des Korrekturlesens auf etwas sto&szlig;en,
   das nicht von diesen Richtlinien abgedeckt wird, das Ihrer
   Meinung nach einer besonderen Behandlung bedarf oder von dem Sie
   nicht sicher wissen, wie es behandelt werden soll, stellen Sie
   Ihre Frage unter Angabe der png-Nummer (Seitenzahl) im
   <a href="#forums">Projektforum</a>.
</p>
<p>Setzen Sie zus&auml;tzlich
   eine Anmerkung in den Korrektur gelesenen Text, die das Problem
   erl&auml;utert. Durch Ihre Anmerkung werden nachfolgende Korrekturleser,
   Formatierer und Nachbearbeiter auf das Problem hingewiesen.
   Beginnen Sie die Anmerkung mit einer eckigen Klammer und zwei Sternchen
   <tt>[**</tt> und schlie&szlig;en Sie sie wiederum mit einer eckigen
   Klammer <tt>]</tt>. Dadurch ist sie deutlich vom Text des Autors
   getrennt und signalisiert dem Nachbearbeiter innezuhalten. Er wird
   diesen Teil des Textes und die dazugeh&ouml;rige Vorlage sorgf&auml;ltig
   untersuchen, um etwaige Probleme zu l&ouml;sen. Alle Kommentare von
   Lesern aus fr&uuml;heren Runden <b>m&uuml;ssen</b> an Ort und Stelle
   bleiben. Sie k&ouml;nnen auch die Runde angeben, in der Sie arbeiten,
   direkt vor der <tt>]</tt>, damit sp&auml;tere Freiwillige wissen,
   wer die Anmerkung hinterlassen hat. Siehe den n&auml;chsten Abschnitt f&uuml;r Einzelheiten.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Zur&uuml;ck nach oben</a></p>


<h3><a name="prev_notes">Anmerkungen vorhergehender Korrekturleser</a></h3>
<p>Alle Anmerkungen oder Kommentare von Lesern aus fr&uuml;heren
   Runden <b>m&uuml;ssen</b> an Ort und Stelle bleiben. Sie k&ouml;nnen
   Ihre Zustimmung oder Ablehnung hinzuf&uuml;gen, aber selbst wenn Sie
   die Antwort wissen, d&uuml;rfen Sie den Kommentar auf keinen Fall
   entfernen. Wenn Sie eine Quelle f&uuml;r die L&ouml;sung des Problems
   gefunden haben, so geben Sie sie bitte an, damit sich der
   Nachbearbeiter auch darauf beziehen kann.
</p>
<p>Wenn Sie in einer sp&auml;teren Runde Korrektur lesen und auf die
   Anmerkung eines Mitlesers aus einer fr&uuml;heren Runde sto&szlig;en,
   deren L&ouml;sung Sie kennen, nehmen Sie sich bitte einen Moment Zeit
   und geben ihm R&uuml;ckmeldung. Klicken Sie dazu auf seinen Namen in
   der Korrekturlese-Oberfl&auml;che und schicken Sie ihm eine private
   Mitteilung, in der Sie erkl&auml;ren, wie das Problem in Zukunft
   behandelt werden soll. Aber, wie bereits gesagt, lassen Sie die
   Anmerkung auf jeden Fall stehen.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Zur&uuml;ck nach oben</a></p>


<table width="100%" border="0" cellspacing="0" cellpadding="6" summary="Allgemeine Probleme">
  <tbody>
    <tr>
      <td bgcolor="silver"><h2 style="margin-bottom: 0; margin-top: 0;">Allgemeine Probleme:</h2></td>
    </tr>
  </tbody>
</table>


<h3><a name="formatting">Formatierung</a></h3>
<p>Sie werden manchmal im Text schon Formatierung vorfinden.
   <b>F&uuml;gen Sie keine hinzu und korrigieren Sie diese Formatierungsinformationen
   nicht</b>; die Formatierer machen das sp&auml;ter im Prozess.
   Allerdings k&ouml;nnen Sie sie entfernen, wenn sie mit Ihrem
   Korrekturlesen in Konflikt kommt. Der <s>&lt;x&gt;</s> Knopf
   in der Korrekturleseoberfl&auml;che entfernt Markierungen wie
   &lt;i&gt; und &lt;b&gt; vom markierten Text. Einige Beispiele
   der Formatierungsaufgaben enthalten:
</p>
<ul>
  <li>&lt;i&gt;kursiv&lt;/i&gt;, &lt;b&gt;fett&lt;/b&gt;, &lt;sc&gt;Kapit&auml;lchen&lt;/sc&gt;</li>
  <li>gesperrter Text</li>
  <li>&Auml;nderung der Schriftgr&ouml;&szlig;e</li>
  <li>Abst&auml;nde f&uuml;r Kapitel- und Abschnitts&uuml;berschriften</li>
  <li>Extraabst&auml;nde, Sterne oder Linien zwischen Abs&auml;tzen</li>
  <li>Fu&szlig;noten, die &uuml;ber mehr als eine Seite gehen</li>
  <li>Fu&szlig;noten, die mit Symbolen gekennzeichnet sind</li>
  <li>Abbildungen</li>
  <li>Platzierung der Randnoten</li>
  <li>Anordnung von Eintr&auml;gen in Tabellen</li>
  <li>Einz&uuml;ge (in Lyrik und anderswo)</li>
  <li>Zusammenf&uuml;gen von langen Zeilen in Lyrik und Verzeichnissen</li>
</ul>
<p>Wenn der vorhergehende Korrekturleser Formatierung eingebracht hat, nehmen
   Sie sich bitte die Zeit, ihm Feedback zu geben. Klicken Sie auf seinen Namen in der
   Korrekturleseoberfl&auml;che und senden Sie ihm eine private Mitteilung,
   die erkl&auml;rt, wie die Situation in Zukunft zu behandeln ist.
   <b>Denken Sie daran, die Formatierung den Formatier-Runden zu &uuml;berlassen.</b>
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Zur&uuml;ck nach oben</a></p>


<h3><a name="common_OCR">Allgemeine OCR-Probleme</a></h3>
<p>OCR-Software hat gew&ouml;hnlich Schwierigkeiten, zwischen
   &auml;hnlichen Zeichen zu unterscheiden. Einige Beispiele sind:
</p>
<ul>
  <li>Die Ziffer '1' (eins), der Kleinbuchstabe 'l' (ell) und der
      Gro&szlig;buchstabe 'I'. Beachten Sie, dass in manchen Schriftarten
      die Zahl eins aussehen kann wie <small>I</small> (wie ein Kapit&auml;lchen 'i').</li>
  <li>Die Ziffer '0' (null) und der Gro&szlig;buchstabe 'O'.</li>
  <li>Trennstriche &amp; Gedankenstriche: Lesen Sie diese sorgf&auml;ltig
      Korrektur &ndash; OCR-Text hat oft nur einen Bindestrich f&uuml;r
      einen langen Gedankenstrich, der zwei haben sollte. Siehe die Richtlinien
      f&uuml;r <a href="#eol_hyphen">getrennte Worte</a> und
      <a href="#em_dashes">Gedankenstriche</a> f&uuml;r detaillierte Informationen.</li>
  <li>Einfache ( ) und geschweifte { } Klammern.</li>
</ul>
<p>Achten Sie besonders auf diese Zeichen. Normalerweise gen&uuml;gt
   der Kontext, um zu entscheiden, welches das richtige Zeichen ist,
   aber geben Sie Acht &ndash; h&auml;ufig &bdquo;korrigiert&ldquo;
   Ihr Verstand die Zeichen automatisch beim Lesen.
</p>
<p>Die Unterscheidung dieser Zeichen ist einfacher, wenn Sie eine
   nicht-proportionale Schriftart verwenden, wie etwa
   <a href="../font_sample.php">DPCustomMono</a> oder Courier.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Zur&uuml;ck nach oben</a></p>


<h3><a name="OCR_scanno">OCR-Fehler: &bdquo;Scannos&ldquo;</a></h3>
<p>Ein anderes h&auml;ufiges Problem der OCR ist die falsche
   Erkennung von Zeichen, sogenannte OCR-Fehler (englisch
   &bdquo;scannos&ldquo;, nachgebildet &bdquo;typos&ldquo; f&uuml;r
   Druck- und Satzfehler). Die Fehlerkennung kann ein Wort bilden, das</p>
<ul compact>
   <li>auf den ersten Blick richtig erscheint, in Wahrheit aber
       falsch geschrieben ist. <br>
       Dies wird gew&ouml;nlich mit entdeckt, wenn man
       <a href="../wordcheck-faq.php">WordCheck</a> von der Korrekturlese-Oberfl&auml;che
       aus startet.</li>
   <li>zu einem anderen, richtigen Wort wird, das nicht dem in der
       Vorlage entspricht.<br> Solche W&ouml;rter sind besonders
       unangenehm, weil sie nur erkannt werden, wenn jemand den
       Text tats&auml;chlich liest.</li>
</ul>
<p>Das verbreitetste Beispiel f&uuml;r die zweite Art ist
   wahrscheinlich das Wort &bdquo;and&ldquo;, eingelesen als
   &bdquo;arid&ldquo;. Weitere Beispiele sind: &bdquo;eve&ldquo;
   f&uuml;r &bdquo;eye&ldquo;, &bdquo;Torn&ldquo; f&uuml;r
   &bdquo;Tom&ldquo; und &bdquo;train&ldquo; f&uuml;r &bdquo;tram&ldquo;.
   Dieser Typ Fehler ist schwer zu entdecken, und wir haben einen
   speziellen Ausdruck daf&uuml;r: &bdquo;Stealth Scannos&ldquo;
   (getarnte OCR-Fehler). Beispiele von Stealth Scannos sammeln wir in
   <a href="<?php echo $Stealth_Scannos_URL; ?>">diesem Thread</a>.
</p>
<p>Das Erkennen von Scannos ist einfacher, wenn Sie eine
   nicht-proportionale Schriftart verwenden, wie etwa
   <a href="../font_sample.php">DPCustomMono</a> oder Courier. Um das Korrekturlesen
   zu unterst&uuml;tzen wird der Gebrauch von <a href="../wordcheck-faq.php">WordCheck</a>
   (oder etwas vergleichbarem) in <?php echo $ELR_round->id; ?> empfohlen.
   In den anderen Korrekturlese-Runden ist es vorgeschrieben.
</p>
<!-- END RR -->
<!-- More to be added.... -->
<p class="backtotop"><a href="#top">Zur&uuml;ck nach oben</a></p>


<h3><a name="OCR_raised_o">OCR-Probleme: Ist das &deg; &ordm; wirklich ein Grad-Zeichen?</a></h3>
<p>Es gibt drei verschiedene Symbole, die in der Vorlage sehr &auml;hnlich
   aussehen k&uuml;nnen und die von der OCR-Software gleich
   interpretiert werden (und zwar normalerweise falsch):
</p>
<ul>
  <li>Das Grad-Zeichen <tt style="font-size:150%;">&deg;</tt>: Es sollte
      nur benutzt werden, um Gradangaben zu bezeichnen (Temperatur, Winkel usw.)</li>
  <li>Das hochgestellte o: Beinahe alle anderen Vorkommen eines angehobenen o
      sollten als <tt>^o</tt> Korrektur gelesen werden, entsprechend den Richtlinien
      f&uuml;r <a href="#supers">hochgestellten Text</a>.</li>
  <li>Der m&auml;nnliche Ordnungszahlanzeiger <tt style="font-size:150%;">&ordm;</tt>:
      Lesen sie auch diesen als hochgestellt, wenn nicht das spezielle Zeichen
      in den <a href="#comments">Projektkommentaren</a> verlangt wird. Es kann
      in Sprachen wie Spanisch und Portugiesisch benutzt werden und ist das
      &Auml;quivalent zum Englischen -th in 4th, 5th usw. Es folgt auf Zahlen
      und hat ein weibliches Gegenst&uuml;ck im hochgestellten a (<tt>&ordf;</tt>).</li>
</ul>
<!-- END RR -->
<p class="backtotop"><a href="#top">Zur&uuml;ck nach oben</a></p>


<h3><a name="hand_notes">Handgeschriebene Notizen in B&uuml;chern</a></h3>
<p>&Uuml;bernehmen Sie keine handschriftlichen Erg&auml;nzungen oder
   Randnotizen in den Text (es sei denn, es wurde verblichener
   gedruckter Text &uuml;berschrieben, um ihn besser lesbar zu
   machen). Lassen Sie auch handgeschriebene Kommentare von Lesern
   usw. weg.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Zur&uuml;ck nach oben</a></p>


<h3><a name="bad_image">Schlechte Vorlagen</a></h3>
<p>Ist eine Vorlage schlecht (sie wird nicht geladen, ist unlesbar
   usw.), so machen Sie einen Eintrag im <a href="#forums">Projektforum</a>
   und klicken Sie auf &sbquo;Report Bad Page&lsquo; (unbrauchbare
   Seite melden), damit die Seite in Quarant&auml;ne kommt, statt sie
   in die Runde zur&uuml;ck zu geben. Wenn nur ein kleiner Teil der
   Vorlage schlecht ist, hinterlassen Sie eine Anmerkung wie
   <a href="#anything">oben</a> beschrieben und bitte schreiben Sie eine
   Nachricht in der <a href="#forums">Projektdiskussion</a>, ohne die
   ganze Seite als schlecht zu markieren. Der Button "Bad Page" ist nur
   in der ersten Runde verf&uuml;gbar, deswegen ist es wichtig, dass diese
   Fragen fr&uuml;hzeitig gekl&auml;rt werden.
</p>
<p>Beachten Sie, dass einige Bilddateien von Seiten recht gro&szlig;
   sind und Ihr Browser Schwierigkeiten bei der Darstellung haben
   k&ouml;nnte, besonders wenn Sie mehrere Fenster ge&ouml;ffnet
   haben oder einen &auml;lteren Computer benutzen. Bevor Sie die
   Seite als unbrauchbar melden, probieren Sie erst, die Vorlage zu
   vergr&ouml;&szlig;ern, einige Ihrer Fenster und Programme zu schlie&szlig;en,
   oder in der <a href="#forums">Projektdiskussion</a> zu posten, um
   zu sehen, ob jemand anders das gleiche Problem hat.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Zur&uuml;ck nach oben</a></p>


<h3><a name="bad_text">Falsche Vorlage zum Text</a></h3>
<p>Wenn eine falsche Vorlage zum Text angezeigt wird, machen Sie
   einen Eintrag im <a href="#forums">Projektforum</a> und klicken
   Sie auf &sbquo;Report Bad Page&lsquo; (unbrauchbare Seite melden),
   damit die Seite in Quarant&auml;ne kommt, statt sie in die Runde
   zur&uuml;ck zu geben. Der Button "Bad Page" ist nur in der ersten
   Runde verf&uuml;gbar, deswegen ist es wichtig, dass diese Fragen
   fr&uuml;hzeitig gekl&auml;rt werden.
</p>
<p>Es ist ziemlich normal, dass der OCR-Text in Ordnung ist, aber die ersten ein oder
   zwei Zeilen fehlen. Tippen Sie in diesem Fall bitte die
   fehlende(n) Zeile(n) ein. Wenn fast alle Zeilen im Korrekturfenster
   fehlen, tippen Sie entweder die ganze Seite ein (wenn Sie dazu bereit
   sind) oder klicken Sie auf &sbquo;Return Page to Round&lsquo;. Dann
   wird die Seite erneut an jemand anders ausgegeben. Gibt es mehrere
   Seiten dieser Art, sollten Sie im <a href="#forums">Projektforum</a>
   den Projektmanager darauf aufmerksam machen.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Zur&uuml;ck nach oben</a></p>


<h3><a name="round1">Fehler der vorherigen Korrekturleser</a></h3>
<p>Wenn ein vorhergehender Korrekturleser sehr viele Fehler gemacht
   hat oder viel &uuml;bersehen hat, nehmen Sie sich bitte einen
   Moment Zeit und geben Sie ihm R&uuml;ckmeldung. Klicken Sie dazu
   auf seinen Namen in der Korrekturlese-Oberfl&auml;che und schicken
   Sie ihm eine private Mitteilung. Erkl&auml;ren Sie ihm, wie er die
   Situation handhaben sollte, damit er in Zukunft besser zurecht kommt.
</p>
<p><em>Bitte seien Sie freundlich!</em> Alle hier sind Freiwillige,
   und wir gehen davon aus, dass jeder sein Bestes gibt. Inhalt Ihrer
   Feedback-Nachricht sollte die Information sein, wie richtig Korrektur
   gelesen wird, nicht Kritik. Zeigen Sie anhand eines Beispiels auf,
   was der Freiwillige gemacht hat und was er h&auml;tte machen sollen.
</p>
<p>Hat der vorhergehende Korrekturleser hervorragend gearbeitet, so
   k&ouml;nnen Sie ihm ebenfalls eine Nachricht senden &ndash; vor
   allem, wenn die Seite besonders schwierig war.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Zur&uuml;ck nach oben</a></p>


<h3><a name="p_errors">Satz- und Rechtschreibfehler: &bdquo;Typos&ldquo;</a></h3>
<p>Korrigieren Sie alle Fehler, die die OCR-Software fehlgelesen hat
   (&bdquo;Scannos&ldquo;) &ndash; aber verbessern Sie nicht, was
   Ihnen wie Rechtschreib- oder Druckfehler vorkommt, die auf der
   gescannten Vorlage auftreten. In vielen &auml;lteren Texten werden
   W&ouml;rter abweichend vom modernen Gebrauch buchstabiert. Wir
   behalten diese &auml;lteren Schreibweisen bei, einschlie&szlig;lich
   aller Buchstaben mit Akzenten.
</p>
<p>Setzen Sie eine Anmerkung in den Text neben einen Dreckfuhler
   <tt>[**typo for Druckfehler]</tt>. Wenn Sie sich nicht sicher sind, ob es sich
   wirklich um einen Fehler handelt, fragen Sie auch in der
   <a href="#forums"> Projektdiskussion</a> nach.
   &Auml;ndern Sie etwas, so erkl&auml;ren Sie mit einer Anmerkung,
   was Sie ge&auml;ndert haben: <tt>[**typo "Dreckfuhler" fixed]</tt>.
   Verwenden Sie hierbei
   die beiden Sternchen <tt>**</tt>, damit der Nachbearbeiter auf
   diese Stelle aufmerksam wird.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Zur&uuml;ck nach oben</a></p>


<h3><a name="f_errors">Tatsachenfehler im Text</a></h3>
<p>Korrigieren Sie keine Tatsachenfehler im
   Buch des Autors. Viele der B&uuml;cher, die hier bearbeitet werden,
   enthalten Aussagen, die wir nicht mehr als zutreffend empfinden.
   Lassen Sie diese so, wie sie der Autor geschrieben hat.
   Siehe <a href="#p_errors">Satz- und Rechtschreibefehler</a> dazu,
   wie eine Anmerkung hinterlassen werden kann, wenn Sie glauben, der
   gedruckte Text sei nicht, was der Autor wollte.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Zur&uuml;ck nach oben</a></p>


<h3><a name="insert_char">Einf&uuml;gen spezieller Zeichen:</a></h3>
<p>Sind diese Zeichen nicht auf Ihrer Tastatur, so gibt es mehrere
   M&ouml;glichkeiten zur Eingabe:
</p>
<ul compact>
  <li>Die Pulldown-Men&uuml;s in der Korrekturlese-Oberfl&auml;che.</li>
  <li>Applets, die im Betriebssystem Ihres Computers enthalten sind.
    <ul compact>
      <li>Windows: &sbquo;Zeichentabelle&lsquo;<br> Zugriff haben Sie mit:<br>
          Start -&gt;Ausf&uuml;hren-&gt; charmap bzw.<br>
          Start -&gt; Programme -&gt; Zubeh&ouml;r -&gt; Systemprogramme -&gt; Zeichentabelle</li>
      <li>Macintosh: &sbquo;Tastatur&lsquo; oder &sbquo;Tastatur&uuml;bersicht&lsquo;<br>
          Bis OS 9 im Apple-Men&uuml;,<br>
          bei OS X bis 10.2 im Ordner &sbquo;Anwendungen, Werkzeuge&lsquo;,<br>
          ab OS X 10.3 im Input-Men&uuml; als &sbquo;Tastatur&uuml;bersicht&lsquo;.</li>
      <li>Linux: Name und Standort der Zeichenauswahl variieren abh&auml;ngig von der
          Arbeitsumgebung.</li>
    </ul>
  </li>
  <li>Mit einem Online-Programm.</li>
  <li>Tastenk&uuml;rzel<br> (Tabellen f&uuml;r <a href="#a_chars_win">Windows</a>
      und <a href="#a_chars_mac">Macintosh</a> finden Sie weiter unten in diesem Dokument.)</li>
  <li>Durch Umschalten auf ein Tastaturlayout oder eine andere
      Landeseinstellung, die Akzente als Tottasten unterst&uuml;tzt.
    <ul compact>
      <li>Windows: Systemsteuerung (Regions- und Sprachoptionen)</li>
      <li>Macintosh: Inputmen&uuml; (in der Men&uuml;leiste)</li>
      <li>Linux: Einstellen der Tastatur in der X-Konfiguration.</li>
    </ul>
  </li>
</ul>
<!-- END RR -->

<a name="a_chars_win"></a>
<p><b>F&uuml;r Windows</b>:
</p>
<p>Zur Auswahl eines Zeichens haben Sie folgende M&ouml;glichkeiten:
</p>
<ul compact>
  <li>das Programm &sbquo;Zeichentabelle&lsquo; (Start -&gt; Ausf&uuml;hren
      -&gt; charmap), dann Ausschneiden (Strg-X) und Einf&uuml;gen (Strg-V)
  </li>
  <li>die Pulldown-Men&uuml;s in der Korrekturlese-Oberfl&auml;che
  </li>
  <li>oder die Tastenkombination &sbquo;Alt+Zifferntasten&lsquo; als
      K&uuml;rzel f&uuml;r einzelne Zeichen.<br>
      Diese Methode ist schneller als Ausschneiden und Einf&uuml;gen,
      wenn Sie sich einmal an die Codes gew&ouml;hnt haben.<br>
      Halten Sie dazu die &sbquo;Alt&lsquo;-Taste gedr&uuml;ckt und
      geben Sie die vier Ziffern am <i>Ziffernblock</i> der Tastatur ein
      &ndash; die Ziffernreihe &uuml;ber den Buchstaben funktioniert nicht.<br>
      Sie m&uuml;ssen alle vier Stellen eingeben, einschlie&szlig;lich
      der f&uuml;hrenden 0 (Null). Beachten Sie, dass der Gro&szlig;buchstabe
      immer um 32 kleiner ist als der entsprechende Kleinbuchstabe.<br>
      Diese Anweisungen gelten f&uuml;r das US-englische Tastaturlayout.
      M&ouml;glicherweise funktionieren sie nicht unter anderen Layouts.<br>
      (<a href="../charwin.pdf">Druckversion der Tabelle</a>)
  </li>
</ul>

<br>
<table align="center" border="6" rules="all" summary="Windows-Tastenk&uuml;rzel">
  <tbody>
  <tr>
      <th bgcolor="cornsilk" colspan=14>Windows-Tastenk&uuml;rzel f&uuml;r Latin-1-Symbole</th>
  </tr>
  <tr bgcolor="cornsilk">
      <th colspan=2>` Gravis</th>
      <th colspan=2>&acute; Akut (aigu)</th>
      <th colspan=2>^ Zirkumflex</th>
      <th colspan=2>~ Tilde</th>
      <th colspan=2>&uml; Umlaut/Trema</th>
      <th colspan=2>&deg; Ring</th>
      <th colspan=2>&AElig;-Ligatur</th>
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
      <th colspan=2 bgcolor="cornsilk">/ Schr&auml;gstrich</th>
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
      <th colspan=2 bgcolor="cornsilk">W&auml;hrungen</th>
      <th colspan=2 bgcolor="cornsilk">Mathematik</th>
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
  <tr><th colspan=2 bgcolor="cornsilk">Cedille </th>
      <th colspan=2 bgcolor="cornsilk">Isl&auml;ndisch </th>
      <th colspan=2 bgcolor="cornsilk">Symbole    </th>
      <th colspan=2 bgcolor="cornsilk">Akzente     </th>
      <th colspan=2 bgcolor="cornsilk">Satzzeichen </th>
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
  <tr><th colspan=2 bgcolor="cornsilk">Hochzahlen        </th>
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
      <th colspan=2 bgcolor="cornsilk">Ordnungszeichen</th>
      <td align="center" bgcolor="mistyrose" title="1/4 Fraction"          >&frac14; </td><td>Alt-0188&nbsp;&dagger;</td>
  </tr>
  <tr><td align="center" bgcolor="mistyrose" title="superscript 2"         >&sup2;   </td><td>Alt-0178&nbsp;*</td>
      <th colspan=2 bgcolor="cornsilk">sz-Ligatur        </th>
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
<p>* Wenn nicht besonders verlangt in den
   <a href="#comments">Projektkommentare</a>, benutzen Sie bitte
   keine Ordinalanzeiger oder hochgestellten Symbole, sondern
   stattdessen die Richtlinien f&uuml;r
   <a href="#supers">hochgestellten Text</a> (x^2, f^o, etc.).
</p>
<p>&dagger; Wenn es in den
   <a href="#comments">Projektkommentaren</a> nicht ausdr&uuml;cklich
   verlangt wird, verwenden Sie bitte keine Bruch-Symbole, sondern die
   Regeln f&uuml;r <a href="#fract_s">Br&uuml;che</a> (1/2, 1/4, 3/4, usw.).
</p>
<p><b>F&uuml;r Apple Macintosh</b>:
</p>
<ul compact>
  <li>Sie k&ouml;nnen das &sbquo;Tastatur&lsquo;-Programm als Referenz verwenden.<br>
      Bis OS 9 findet man es im Apple-Men&uuml;; unter OS X bis 10.2 im
      Ordner &sbquo;Anwendungen, Werkzeuge&lsquo;.<br>
      Ein Bild der Tastatur erscheint. Bei Dr&uuml;cken der Umschalt-,
      Wahl- oder Befehlstaste bzw. beliebigen Kombinationen wird angezeigt,
      wie die einzelnen Zeichen erzeugt werden. Anhand des Bildes
      k&ouml;nnen Sie sehen, wie Sie ein Zeichen eintippen. Sie
      k&ouml;nnen das Zeichen aber auch durch Ausschneiden und
      Einf&uuml;gen in die Korrekturlese-Oberfl&auml;che &uuml;bernehmen.</li>
  <li>Ab OS X 10.3 erf&uuml;llt diese Funktion eine Palette, die
      &uuml;ber das Tastaturmen&uuml; verf&uuml;gbar ist (das
      Pulldown-Men&uuml;, das mit dem Flaggen-Symbol der Landeseinstellungen
      in der Men&uuml;leiste verbunden ist). Sie ist mit
      &sbquo;Tastatur&uuml;bersicht einblenden&lsquo; bezeichnet. Befindet
      sie sich nicht in Ihrem Tastaturmen&uuml; oder haben Sie kein
      solches Men&uuml;, k&ouml;nnen Sie es aktivieren, indem Sie
      die Systemeinstellungen &ouml;ffnen und auf
      &sbquo;Landeseinstellungen&lsquo; klicken. Vergewissern Sie sich,
      dass &sbquo;Tastatur&uuml;bersicht in der Men&uuml;leiste
      anzeigen&lsquo; mit einem H&auml;kchen versehen ist. In der
      Spreadsheet-Ansicht markieren Sie &sbquo;Tastatur&uuml;bersicht&lsquo;
      zus&auml;tzlich zu etwaigen Landeseinstellungen, die Sie benutzen.
  </li>
  <li>Pulldown-Men&uuml;s in der Korrekturlese-Oberfl&auml;che
  </li>
  <li>Oder Sie geben einfach das Apple-Wahltasten-Tastenk&uuml;rzel
      f&uuml;r das ben&ouml;tigte Zeichen ein.<br>
      Diese Methode ist viel schneller als &bdquo;Ausschneiden &amp;
      Einf&uuml;gen&ldquo;, wenn Sie sich einmal an die Codes gew&ouml;hnt haben.<br>
      Halten Sie dazu die Wahltaste gedr&uuml;ckt und geben Sie zuerst
      den Akzent und dann den Buchstaben ein. F&uuml;r manche Symbole
      m&uuml;ssen Sie lediglich die Wahltaste und das Symbol gleichzeitig
      dr&uuml;cken.<br>
      Diese Anweisungen gelten f&uuml;r das US-englische Tastaturlayout.
      M&ouml;glicherweise funktionieren sie nicht unter anderen Layouts.<br>
      (<a href="../charapp.pdf">Druckversion der Tabelle</a>)
  </li>
</ul>

<br>
<a name="a_chars_mac"></a>
<table align="center" border="6" rules="all" summary="Mac shortcuts">
  <tbody>
  <tr bgcolor="cornsilk">
      <th colspan=14>Apple-Mac-Tastenk&uuml;rzel f&uuml;r Latin-1-Symbole</th>
  </tr>
  <tr bgcolor="cornsilk">
      <th colspan=2>` Gravis</th>
      <th colspan=2>&acute; Akut (aigu)</th>
      <th colspan=2>^ Zirkumflex</th>
      <th colspan=2>~ Tilde</th>
      <th colspan=2>&uml; Umlaut/Trema</th>
      <th colspan=2>&deg; Ring</th>
      <th colspan=2>&AElig;-Ligatur</th>
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
      <th colspan=2 bgcolor="cornsilk">/ Schr&auml;gstrich</th>
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
      <th colspan=2 bgcolor="cornsilk">W&auml;hrungen</th>
      <th colspan=2 bgcolor="cornsilk">Mathematik</th>
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
      <td align="center" bgcolor="mistyrose" title="Multiplication"        >&times;  </td><td>(n.&nbsp;v.)&nbsp;&Dagger;</td>
  </tr>
  <tr><th colspan=2 bgcolor="cornsilk">Cedille </th>
      <th colspan=2 bgcolor="cornsilk">Isl&auml;ndisch  </th>
      <th colspan=2 bgcolor="cornsilk">Symbole    </th>
      <th colspan=2 bgcolor="cornsilk">Akzente     </th>
      <th colspan=2 bgcolor="cornsilk">Satzzeichen </th>
      <td align="center" bgcolor="mistyrose" title="Yen"                   >&yen;    </td><td>Opt-y   </td>
      <td align="center" bgcolor="mistyrose" title="Division"              >&divide; </td><td>Opt-/   </td>
  </tr>
  <tr><td align="center" bgcolor="mistyrose" title="Small c cedilla"       >&ccedil; </td><td>Opt-c   </td>
      <td align="center" bgcolor="mistyrose" title="Capital Thorn"         >&THORN;  </td><td>(n.&nbsp;v.)&nbsp;&Dagger;</td>
      <td align="center" bgcolor="mistyrose" title="Copyright"             >&copy;   </td><td>Opt-g   </td>
      <td align="center" bgcolor="mistyrose" title="acute accent"          >&acute;  </td><td>Opt-E   </td>
      <td align="center" bgcolor="mistyrose" title="Inverted Question Mark">&iquest; </td><td>Opt-?   </td>
      <td align="center" bgcolor="mistyrose" title="General Currency"      >&curren; </td><td>(n.&nbsp;v.)&nbsp;&Dagger;</td>
      <td align="center" bgcolor="mistyrose" title="Logical Not"           >&not;    </td><td>Opt-l   </td>
  </tr>
  <tr><td align="center" bgcolor="mistyrose" title="Capital C cedilla"     >&Ccedil; </td><td>Opt-C   </td>
      <td align="center" bgcolor="mistyrose" title="Small thorn"           >&thorn;  </td><td>(n.&nbsp;v.)&nbsp;&Dagger;</td>
      <td align="center" bgcolor="mistyrose" title="Registration Mark"     >&reg;    </td><td>Opt-r   </td>
      <td align="center" bgcolor="mistyrose" title="umlaut accent"         >&uml;    </td><td>Opt-U   </td>
      <td align="center" bgcolor="mistyrose" title="Inverted Exclamation"  >&iexcl;  </td><td>Opt-1   </td>
      <td> </td><td> </td>
      <td align="center" bgcolor="mistyrose" title="Degrees"               >&deg;    </td><td>Shift-Opt-8   </td>
  </tr>
  <tr><th colspan=2 bgcolor="cornsilk">Hochzahlen        </th>
      <td align="center" bgcolor="mistyrose" title="Capital Eth"           >&ETH;    </td><td>(n.&nbsp;v.)&nbsp;&Dagger;</td>
      <td align="center" bgcolor="mistyrose" title="Paragraph (pilcrow)"   >&para;   </td><td>Opt-7   </td>
      <td align="center" bgcolor="mistyrose" title="macron accent"         >&macr;   </td><td>Shift-Opt-,</td>
      <td align="center" bgcolor="mistyrose" title="guillemet left"        >&laquo;  </td><td>Opt-\   </td>
      <td> </td><td> </td>
      <td align="center" bgcolor="mistyrose" title="Micro"                 >&micro;  </td><td>Opt-m   </td>
  </tr>
  <tr><td align="center" bgcolor="mistyrose" title="superscript 1"         >&sup1;   </td><td>(n.&nbsp;v.)&nbsp;*&Dagger;</td>
      <td align="center" bgcolor="mistyrose" title="Small eth"             >&eth;    </td><td>(n.&nbsp;v.)&nbsp;&Dagger;</td>
      <td align="center" bgcolor="mistyrose" title="Section"               >&sect;   </td><td>Opt-6   </td>
      <td align="center" bgcolor="mistyrose" title="cedilla"               >&cedil;  </td><td>Opt-Z   </td>
      <td align="center" bgcolor="mistyrose" title="guillemet right"       >&raquo;  </td><td>Shift-Opt-\</td>
      <th colspan=2 bgcolor="cornsilk">Ordnungszeichen</th>
      <td align="center" bgcolor="mistyrose" title="1/4 Fraction"          >&frac14; </td><td>(n.&nbsp;v.)&nbsp;&dagger;&Dagger;</td>
  </tr>
  <tr><td align="center" bgcolor="mistyrose" title="superscript 2"         >&sup2;   </td><td>(n.&nbsp;v.)&nbsp;*&Dagger;</td>
      <th colspan=2 bgcolor="cornsilk">sz-Ligatur        </th>
      <td align="center" bgcolor="mistyrose" title="Broken Vertical bar"   >&brvbar; </td><td>(n.&nbsp;v.)&nbsp;&Dagger;</td>
      <td> </td><td> </td>
      <td align="center" bgcolor="mistyrose" title="Middle dot"            >&middot; </td><td>Shift-Opt-9  </td>
      <td align="center" bgcolor="mistyrose" title="Masculine Ordinal"     >&ordm;   </td><td>Opt-0&nbsp;*</td>
      <td align="center" bgcolor="mistyrose" title="1/2 Fraction"          >&frac12; </td><td>(n.&nbsp;v.)&nbsp;&dagger;&Dagger;</td>
  </tr>
  <tr><td align="center" bgcolor="mistyrose" title="superscript 3"         >&sup3;   </td><td>(n.&nbsp;v.)&nbsp;*&Dagger;</td>
      <td align="center" bgcolor="mistyrose" title="sz ligature"           >&szlig;  </td><td>Opt-s   </td>
      <td> </td><td> </td>
      <td> </td><td> </td>
      <td> </td><td> </td>
      <td align="center" bgcolor="mistyrose" title="Feminine Ordinal"      >&ordf;   </td><td>Opt-9&nbsp;*</td>
      <td align="center" bgcolor="mistyrose" title="3/4 Fraction"          >&frac34; </td><td>(n.&nbsp;v.)&nbsp;&dagger;&Dagger;</td>
  </tr>
  </tbody>
</table>
<p>* Wenn nicht besonders verlangt in den
   <a href="#comments">Projektkommentare</a>, benutzen Sie bitte
   keine Ordinalanzeiger oder hochgestellten Symbole, sondern
   stattdessen die Richtlinien f&uuml;r <a href="#supers">hochgestellten Text</a> (x^2, f^o, etc.).
</p>
<p>&dagger; Wenn es in den
   <a href="#comments">Projektkommentaren</a> nicht ausdr&uuml;cklich
   verlangt wird, verwenden Sie bitte keine Bruch-Symbole, sondern die
   Regeln f&uuml;r <a href="#fract_s">Br&uuml;che</a> (1/2, 1/4, 3/4, usw.).
</p>
<p>&Dagger;&nbsp;Anmerkung: Kein Tastenk&uuml;rzel vorhanden; verwenden Sie die Pulldown-Men&uuml;s.
</p>
<p class="backtotop"><a href="#top">Zur&uuml;ck nach oben</a></p>

</div>


<table width="100%" border="0" cellspacing="0" cellpadding="6" summary="Alphabetischer Index">
  <tbody>
    <tr>
      <td bgcolor="silver"><h2 style="margin-bottom: 0; margin-top: 0;"><a name="index">Alphabetisches Stichwortverzeichnis zu den Richtlinien</a></h2></td>
    </tr>
  </tbody>
</table>
<br>

<table border="0" width="100%" summary="Alphabetical Index">
  <tr>
    <td width="50%" valign="top">
      <ul>
        <li><a href="#para_space">Absatzabst&auml;nde</a></li>
        <li><a href="#para_space">Absatzeinr&uuml;ckung</a></li>
        <li><a href="#a_chars">ae-Ligaturen</a></li>
        <li><a href="#a_chars">Akzente und nicht-ASCII-Zeichen</a></li>
        <li><a href="#common_OCR">Allgemeine OCR-Probleme</a></li>
        <li><a href="#period_p">Andere Sprachen als Englisch (LOTE): Ellipsen</a></li>
        <li><a href="#quote_ea">Anf&uuml;hrungszeichen auf jeder Zeile</a></li>
        <li><a href="#double_q">Anf&uuml;hrungszeichen, doppelte</a></li>
        <li><a href="#single_q">Anf&uuml;hrungszeichen, einfache</a></li>
        <li><a href="#chap_head">Anf&uuml;hrungszeichen, fehlende am Kapitelanfang</a></li>
        <li><a href="#prev_notes">Anmerkungen/Kommentare vorhergehender Korrekturleser</a></li>
        <li><a href="#period_p">Auslassungspunkte &bdquo;...&ldquo; (Ellipse)</a></li>
        <li><a href="#anything">Behandlung von Unklarheiten</a></li>
        <li><a href="#prev_pg">Beseitigung von Fehlern auf vorhergehenden Seiten</a></li>
        <li><a href="#illust">Bildunterschriften</a></li>
        <li><a href="#fract_s">Br&uuml;che</a></li>
        <li><a href="#a_chars">Buchstaben mit Akzenten/nicht-ASCII</a></li>
        <li><a href="#d_chars">Buchstaben mit diakritischen Zeichen</a></li>
        <li><a href="#d_chars">Diakritische Zeichen</a></li>
        <li><a href="#forums">Diskussion</a></li>
        <li><a href="#double_q">Doppelte Anf&uuml;hrungszeichen</a></li>
        <li><a href="#play_n">Dramen</a></li>
        <li><a href="#p_errors">Druckfehler</a></li>
        <li><a href="#single_q">Einfache Anf&uuml;hrungszeichen</a></li>
        <li><a href="#insert_char">Eingabe von speziellen Zeichen</a></li>
        <li><a href="#next_word">Einzelne W&ouml;rter am unteren Seitenrand</a></li>
        <li><a href="#para_space">Einz&uuml;ge von Abs&auml;tzen</a></li>
        <li><a href="#period_p">Ellipse</a></li>
        <li><a href="#em_dashes">em-dash</a></li>
        <li><a href="#footnotes">Endnoten</a></li>
        <li><a href="#poetry">Epigramme</a></li>
        <li><a href="#bad_text">Falsche Vorlage zum Text</a></li>
        <li><a href="#bad_text">Falscher Text zur Vorlage</a></li>
        <li><a href="#f_errors">Fehler, Tatsachen-</a></li>
        <li><a href="#round1">Fehler vorhergehender Korrekturleser</a></li>
        <li><a href="#p_errors">Fehlschreibungen, Druckfehler</a></li>
        <li><a href="#formatting">Fetter Text</a></li>
        <li><a href="#formatting">Formatierung</a></li>
        <li><a href="#forums">Forum</a></li>
        <li><a href="#footnotes">Fu&szlig;noten</a></li>
        <li><a href="#page_hf">Fu&szlig;zeilen</a></li>
        <li><a href="#em_dashes">Gedankenstriche</a></li>
        <li><a href="#eop_hyphen">Gedankenstriche am Seitenende</a></li>
        <li><a href="#eol_hyphen">Gedankenstriche am Zeilenende</a></li>
        <li><a href="#OCR_raised_o">Grad-Zeichen</a></li>
        <li><a href="#f_chars">Griechischer Text</a></li>
        <li><a href="#drop_caps">Gro&szlig;buchstaben, verzierte</a></li>
        <li><a href="#hand_notes">Handgeschriebene Notizen im Buch</a></li>
        <li><a href="#f_chars">Hebr&auml;ischer Text</a></li>
        <li><a href="#title_pg">Hintere Umschlagseite</a></li>
        <li><a href="#supers">Hochgestellter Text</a></li>
        <li><a href="#illust">Illustrationen</a></li>
        <li><a href="#toc">Inhaltsverzeichnis</a></li>
        <li><a href="#drop_caps">Initialen</a></li>
        <li><a href="#small_caps"><span style="font-variant: small-caps">Kapit&auml;lchen</span></a></li>
        <li><a href="#chap_head">Kapitel&uuml;berschriften</a></li>
        <li><a href="#prev_notes">Kommentare vorhergehender Korrekturleser</a></li>
        <li><a href="#page_hf">Kopfzeilen</a></li>
        <li><a href="#formatting">Kursivschrift</a></li>
        <li><a href="#summary">Kurzfassung der Richtlinien</a></li>
        <li><a href="#next_word">Kustos</a></li>
        <li><a href="#em_dashes">Lange Gedankenstriche</a></li>
        <li><a href="#insert_char">Latin-1-Zeichen, Eingabe</a></li>
        <li><a href="#blank_pg">Leere Seite</a></li>
        <li><a href="#punctuat">Leerzeichen bei Interpunktion</a></li>
        <li><a href="#extra_sp">Leerzeichen, zus&auml;tzliche</a></li>
        <li><a href="#a_chars">Ligaturen</a></li>
        <li><a href="#poetry">Lyrik</a></li>
      </ul>
    </td>
    <td width="50%" valign="top">
      <ul>
        <li><a href="#para_side">Marginalien (Randnoten)</a></li>
        <li><a href="#mult_col">mehrspaltige Texte</a></li>
        <li><a href="#em_dashes">Minus-Zeichen</a></li>
        <li><a href="#a_chars">Nicht-ASCII Zeichen</a></li>
        <li><a href="#f_chars">Nicht-lateinische Buchstaben</a></li>
        <li><a href="#hand_notes">Notizen, handgeschriebene</a></li>
        <li><a href="#line_no">Nummern, Zeilen-</a></li>
        <li><a href="#common_OCR">OCR-Probleme, allgemeine</a></li>
        <li><a href="#OCR_raised_o">OCR-Probleme: Ist dieses &deg; &ordm; wirklich ein Grad-Zeichen?</a></li>
        <li><a href="#OCR_scanno">OCR-Probleme: Scannos</a></li>
        <li><a href="#a_chars">oe-Ligaturen</a></li>
        <li><a href="#OCR_raised_o">Ordinalanzeiger</a></li>
        <li><a href="#para_side">Randnoten (Marginalien)</a></li>
        <li><a href="#play_n">Regieanweisungen (Theaterst&uuml;cke)</a></li>
        <li><a href="#prime">Oberstes Gebot</a></li>
        <li><a href="#forums">Projektdiskussion</a></li>
        <li><a href="#comments">Projektkommentare</a></li>
        <li><a href="#insert_char">Pull-down-Men&uuml;s</a></li>
        <li><a href="#period_s">Punkte am Satzende</a></li>
        <li><a href="#play_n">Rollennamen (Theaterst&uuml;cke)</a></li>
        <li><a href="#bk_index">Sachregister</a></li>
        <li><a href="#period_s">Satzende, Punkte am</a></li>
        <li><a href="#p_errors">Satzfehler</a></li>
        <li><a href="#p_errors">Satzfehler/Druckfehler</a></li>
        <li><a href="#OCR_scanno">Scannos</a></li>
        <li><a href="#bk_index">Schlagwortverzeichnisse</a></li>
        <li><a href="#bad_image">Schlechte Vorlage</a></li>
        <li><a href="#bad_text">Schlechter Text</a></li>
        <li><a href="#blank_pg">Seite, leere</a></li>
        <li><a href="#eop_hyphen">Seitenende, Trenn- und Gedankenstriche</a></li>
        <li><a href="#anything">Sonstige Besonderheiten</a></li>
        <li><a href="#mult_col">Spalten, mehrere</a></li>
        <li><a href="#anything">Spezielle Behandlung</a></li>
        <li><a href="#insert_char">Spezielle Zeichen, Eingabe</a></li>
        <li><a href="#tables">Tabellen</a></li>
        <li><a href="#extra_sp">Tabulatoren</a></li>
        <li><a href="#insert_char">Tastaturk&uuml;rzel f&uuml;r Latin-1-Zeichen</a></li>
        <li><a href="#f_errors">Tatsachenfehler</a></li>
        <li><a href="#bad_text">Text, falsche Vorlage zum</a></li>
        <li><a href="#play_n">Theaterst&uuml;cke</a></li>
        <li><a href="#play_n">Theaterst&uuml;cke: Rollennamen/Regieanweisungen</a></li>
        <li><a href="#subscr">Tiefgestellter Text</a></li>
        <li><a href="#title_pg">Titelseite</a></li>
        <li><a href="#em_dashes">Trennstriche</a></li>
        <li><a href="#eop_hyphen">Trennung am Seitenende</a></li>
        <li><a href="#eol_hyphen">Trennung am Zeilenende</a></li>
        <li><a href="#about">&Uuml;ber dieses Dokument</a></li>
        <li><a href="#chap_head">&Uuml;berschriften, Kapitel</a></li>
        <li><a href="#title_pg">Umschlagseiten</a></li>
        <li><a href="#anything">Unklarheiten, Behandlung</a></li>
        <li><a href="#drop_caps">Verzierte Gro&szlig;buchstaben</a></li>
        <li><a href="#prev_notes">vorhergehende Korrekturleser, Anmerkungen/Kommentare von</a></li>
        <li><a href="#round1">vorhergehende Korrekturleser, Fehler der</a></li>
        <li><a href="#prev_pg">vorhergehende Seiten, Fehler beheben</a></li>
        <li><a href="#bad_image">Vorlage, schlechte</a></li>
        <li><a href="#OCR_scanno">WordCheck</a></li>
        <li><a href="#next_word">Wort am unteren Seitenrand</a></li>
        <li><a href="#small_caps"><span style="font-variant: small-caps">Worte in Kapit&auml;lchen</span></a></li>
        <li><a href="#trail_s">Zeilenende, Leerzeichen</a></li>
        <li><a href="#eol_hyphen">Zeilenende, Trenn- und Gedankenstriche</a></li>
        <li><a href="#line_no">Zeilennummern</a></li>
        <li><a href="#line_br">Zeilenumbr&uuml;che</a></li>
        <li><a href="#contract">Zusammenziehungen</a></li>
        <li><a href="#extra_sp">Zus&auml;tzliche Leerzeichen zwischen W&ouml;rtern</a></li>
      </ul>
    </td>
  </tr>
</table>

<br>
<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="silver" summary="Links">
  <tr>
    <td width="10">&nbsp;</td>
    <td width="100%" align="center"><font face="verdana, helvetica, sans-serif" size="1">
      Return to:
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
