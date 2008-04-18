<?
$relPath='../pinc/';
include($relPath.'site_vars.php');
include($relPath.'faq.inc');
include($relPath.'pg.inc');
include($relPath.'connect.inc');
include($relPath.'theme.inc');
new dbConnect();
$no_stats=1;
theme('Korrekturlese-Richtlinien','header');

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

<h1 align="center">Korrekturlese-Richtlinien</h1>

<h3 align="center">Version 1.9.e vom 19. Juli 2007 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <a href="dochist.php"><font size="-1">(&Uuml;berarbeitungsverlauf)</font></a>
</h3>

<h4>Korrekturlese-Richtlinien <a href="proofreading_guidelines.php">auf Englisch</a> /
      Proofreading Guidelines <a href="proofreading_guidelines.php">in English</a> <br />
    Korrekturlese-Richtlinien <a href="proofreading_guidelines_francaises.php">auf Franz&ouml;sisch</a> /
      Directives de Relecture et Correction <a href="proofreading_guidelines_francaises.php">en fran&ccedil;ais</a><br /> 
    Korrekturlese-Richtlinien <a href="proofreading_guidelines_portuguese.php">auf Portugiesisch</a> /
      Regras de Revis&atilde;o <a href="proofreading_guidelines_portuguese.php">em Portugu&ecirc;s</a><br /> 
    Korrekturlese-Richtlinien <a href="proofreading_guidelines_spanish.php">auf Spanisch</a> /
      Reglas de Revisi&oacute;n <a href="proofreading_guidelines_spanish.php">en espa&ntilde;ol</a><br />
    Korrekturlese-Richtlinien <a href="proofreading_guidelines_dutch.php">auf Niederl&auml;ndisch</a> /
      Proeflees-Richtlijnen <a href="proofreading_guidelines_dutch.php">in het Nederlands</a><br />
</h4>

<h4>Hier geht es zum <a href="../quiz/start.php">Proofreading Quiz und Tutorial</a> (nur Englisch).</h4>

<table border="0" cellspacing="0" width="100%" summary="Korrekturlese-Richtlinien">
  <tbody>
  <tr>
    <td width="1" bgcolor="silver">&nbsp;</td>
    <td bgcolor="silver" align="center"><font size="+2"><b>Inhaltsverzeichnis</b></font></td>
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
    <td bgcolor="silver" align="left">
      <ul>
        <li><font size="+1">Korrekturlesen: Richtlinien f&uuml;r ...</font></li>
      </ul>
    </td>
  </tr>
  <tr>
    <td width="1" bgcolor="silver">&nbsp;</td>
    <td bgcolor="white" align="left">
      <ul style="margin-left: 3em;">
        <li><a href="#line_br">Zeilenumbr&uuml;che</a></li>
        <li><a href="#double_q">Doppelte Anf&uuml;hrungszeichen</a></li>
        <li><a href="#single_q">Einfache Anf&uuml;hrungszeichen</a></li>
        <li><a href="#quote_ea">Anf&uuml;hrungszeichen auf jeder Zeile</a></li>
        <li><a href="#period_s">Punkte am Ende von S&auml;tzen</a></li>
        <li><a href="#punctuat">Satzzeichen</a></li>
        <li><a href="#period_p">Auslassungspunkte &bdquo;...&ldquo; (Ellipse)</a></li>
        <li><a href="#contract">Zusammenziehungen</a></li>
        <li><a href="#extra_sp">&Uuml;berfl&uuml;ssige Leerzeichen bzw. Tabulatoren zwischen W&ouml;rtern</a></li>
        <li><a href="#trail_s">Leerzeichen am Zeilenende</a></li>
        <li><a href="#line_no">Zeilennummern</a></li>
        <li><a href="#italics">Kursiv- und Fettschrift</a></li>
        <li><a href="#supers">Hochgestellte Zeichen</a></li>
        <li><a href="#subscr">Tiefgestellte Zeichen</a></li>
        <li><a href="#font_sz">Unterschiedliche Schriftgr&ouml;&szlig;en</a></li>
        <li><a href="#small_caps">W&ouml;rter in Kapit&auml;lchen</a></li>
        <li><a href="#drop_caps">&Uuml;berdimensionale, verzierte Gro&szlig;buchstaben als Er&ouml;ffnung (Initialen)</a></li>
        <li><a href="#a_chars">Akzente und nicht-ASCII-Zeichen</a></li>
        <li><a href="#d_chars">Buchstaben mit diakritischen Zeichen</a></li>
        <li><a href="#f_chars">Nicht-lateinische Zeichen</a></li>
        <li><a href="#fract_s">Br&uuml;che</a></li>
        <li><a href="#em_dashes">Bindestriche, kurze und lange Gedankenstriche</a></li>
        <li><a href="#eol_hyphen">Trennstriche am Zeilenende</a></li>
        <li><a href="#eop_hyphen">Trennstriche am Seitenende</a></li>
        <li><a href="#next_word">Einzelne W&ouml;rter am unteren Seitenrand</a></li>
        <li><a href="#para_space">Absatzabst&auml;nde und -einr&uuml;ckungen</a></li>
        <li><a href="#mult_col">Mehrspaltige Texte</a></li>
        <li><a href="#blank_pg">Leere Seiten</a></li>
        <li><a href="#page_hf">Kopf- und Fu&szlig;zeilen</a></li>
        <li><a href="#chap_head">Kapitel&uuml;berschriften</a></li>
        <li><a href="#illust">Abbildungen</a></li>
        <li><a href="#footnotes">Fu&szlig;noten/Endnoten</a></li>
        <li><a href="#poetry">Gedichte/Epigramme</a></li>
        <li><a href="#para_side">Randnoten (Marginalien)</a></li>
        <li><a href="#tables">Tabellen</a></li>
        <li><a href="#title_pg">Vorder- und R&uuml;ckseiten von Titelbl&auml;ttern</a></li>
        <li><a href="#toc">Inhaltsverzeichnisse</a></li>
        <li><a href="#bk_index">Sachregister und Schlagwortverzeichnisse</a></li>
        <li><a href="#play_n">Dramen: Rollennamen/Regieanweisungen</a></li>
        <li><a href="#anything">Sonstige Besonderheiten und Behandlung von Unklarheiten</a></li>
        <li><a href="#prev_notes">Anmerkungen vorhergehender Korrekturleser</a></li>
      </ul>
    </td>
  </tr>
   <tr>
    <td width="1" bgcolor="silver">&nbsp;</td>
    <td bgcolor="silver" align="left">
    <ul>
      <li><font size="+1">Allgemeine Probleme</font></li>
    </ul>
    </td>
  </tr>
  <tr>
    <td width="1" bgcolor="silver">&nbsp;</td>
    <td bgcolor="white" align="left">
      <ul style="margin-left: 3em;">
        <li><a href="#OCR_1lI">OCR-Fehler: 1-l-I</a></li>
        <li><a href="#OCR_0O">OCR-Fehler: 0-O</a></li>
        <li><a href="#OCR_hyphen">OCR-Fehler: Binde- und Gedankenstriche</a></li>
        <li><a href="#OCR_scanno">OCR-Fehler: &bdquo;Scannos&ldquo;</a></li>
        <li><a href="#hand_notes">Handgeschriebene Notizen in B&uuml;chern</a></li>
        <li><a href="#bad_image">Schlechte Vorlagen</a></li>
        <li><a href="#bad_text">Falsche Vorlage zum Text</a></li>
        <li><a href="#round1">Fehler der vorherigen Korrekturleser</a></li>
        <li><a href="#p_errors">Satz- und Rechtschreibfehler: &bdquo;Typos&ldquo;</a></li>
        <li><a href="#f_errors">Tatsachenfehler im Text</a></li>
        <li><a href="#uncertain">Unklare Punkte</a></li>
      </ul>
    </td>
  </tr>
  <tr>
    <td width="1" bgcolor="silver">&nbsp;</td>
    <td bgcolor="silver">&nbsp;</td>
  </tr>
 </tbody>
</table>

<h3><a name="prime">Oberstes Gebot</a></h3>
<p><em>&bdquo;Lassen Sie den Text des Autors unver&auml;ndert!&ldquo;</em>
</p>
<p>Das fertige E-Book, das der Leser vielleicht in Jahrzehnten erst zu
   Gesicht bekommt, soll die Absicht des Autors genau vermitteln. Hat
   der Autor W&ouml;rter ungew&ouml;hnlich buchstabiert, so belassen wir
   sie in dieser Schreibweise. Hat der Autor ungeheuerlich rassistische
   oder voreingenommene Aussagen gemacht, so lassen wir sie so stehen.
   Hat der Autor jedes dritte Wort kursiv bzw. fett geschrieben oder
   &uuml;berall Fu&szlig;noten angeh&auml;ngt, markieren wir sie als
   kursiv bzw. fett oder als Fu&szlig;noten. Wir sind Korrekturleser,
   <b>keine</b> Lektoren. (Siehe <a href="#p_errors">Satz- und
   Rechtschreibfehler</a> zur richtigen Behandlung von offensichtlichen
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
   <i>ausschlie&szlig;lich</i> f&uuml;r das Korrekturlesen gedacht. An
   der Formatierung des Textes wird sp&auml;ter eine weitere Gruppe von
   Freiwilligen arbeiten.
</p>
<p>Um es den nachfolgenden Korrekturlesern, Formatierern und Nachbearbeitern
   leichter zu machen, behalten wir dar&uuml;ber hinaus
   <a href="#line_br">Zeilenumbr&uuml;che</a> bei. Dadurch lassen sich die
   Zeilen im Text leichter mit den Zeilen in der Vorlage vergleichen.
</p>
<!-- END RR -->

<table width="100%" border="0" cellspacing="0" summary="Summary Guidelines">
  <tbody>
    <tr>
      <td bgcolor="silver">&nbsp;</td>
    </tr>
  </tbody>
</table>


<h3><a name="summary">Kurzfassung dieser Richtlinien</a></h3>
<p>Die Kurzfassung dieser Richtlinien ist ein &uuml;bersichtliches,
   zweiseitiges druckerfreundliches PDF-Dokument, das die wichtigsten
   Punkte enth&auml;lt und Beispiele f&uuml;rs Korrekturlesen gibt.
   Sie ist derzeit nur in englischer Sprache verf&uuml;gbar
   (<a href="proofing_summary.pdf">Proofreading Summary</a>), aber
   eine deutsche Fassung ist in Arbeit. Falls Sie neu hier sind, empfehlen
   wir Ihnen, das PDF-Dokument auszudrucken und w&auml;hrend des
   Korrekturlesens griffbereit zu halten.
</p>
<p>M&ouml;glicherweise m&uuml;ssen Sie erst eine Software zum Anzeigen
   von PDF-Dateien herunterladen und installieren. Eine kostenlose Version
   von Adobe&reg; finden Sie <a href="http://www.adobe.com/products/acrobat/readstep2.html">hier</a>.
</p>


<h3><a name="about">&Uuml;ber dieses Dokument</a></h3>
<p>Dieses Dokument enth&auml;lt Erkl&auml;rungen zu den Korrekturregeln.
   Jedes Buch wird von vielen Korrekturlesern verteilt bearbeitet, von
   denen jeder andere Seiten liest. Das Einhalten der Korrekturregeln
   hilft allen, die Korrekturen konsistent, d.&nbsp;h. <em>auf die gleiche
   Art</em> durchzuf&uuml;hren. Das macht es den Formatierern und
   Nachbearbeitern leichter, die die Arbeit an einem Buch vervollst&auml;ndigen.
</p>
<p><i>Dieses Dokument ist nicht als allgemeines Regelwerk f&uuml;r
   Redaktionsarbeit oder Typographie gedacht.</i>
</p>
<p>Wir haben alle Punkte aufgegriffen, die neuen Korrekturlesern Schwierigkeiten
   bereitet haben. Falls Sie etwas vermissen, etwas Ihrer Ansicht nach anders
   beschrieben werden sollte oder unklar f&uuml;r Sie ist, so lassen Sie es uns bitte wissen.
</p>
<p>Diese Richtlinien werden laufend &uuml;berarbeitet. Helfen Sie uns
   dabei, indem Sie uns Ihre Verbesserungsvorschl&auml;ge im
   <a href="<? echo $Guideline_discussion_URL; ?>">Forum Dokumentation</a> mitteilen.
</p>

<h3><a name="comments">Projektkommentare</a></h3>
<p>Auf der Projektseite, dem Ausgangspunkt f&uuml;r das Korrekturlesen,
   finden Sie einen Abschnitt &sbquo;Project Comments&lsquo;
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
   <a href="<? echo $Using_project_details_URL ?>">Dieses Forum</a>
   er&ouml;rtert verschiedene Arten, diese Informationen zu benutzen.
</p>

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

<h3><a name="prev_pg">Fehler auf fr&uuml;heren Seiten beheben</a></h3>
<p>Wenn Sie ein Projekt zum Bearbeiten ausw&auml;hlen, wird die
   <a href="#comments">Projektseite</a> geladen. Diese Seite
   enth&auml;lt Links zu den Buchseiten, die Sie zuletzt Korrektur
   gelesen haben. (Wenn Sie in einem Projekt noch keine Seiten bearbeitet
   haben, fehlen diese Links.)
</p>
<p>Seiten, die unter &sbquo;DONE&lsquo; (fertig; gr&uuml;n) gelistet
   sind, sind zum &Uuml;berarbeiten verf&uuml;gbar; &sbquo;IN PROGRESS&lsquo;
   (in Arbeit; orange) bedeutet, dass die Arbeit noch nicht abgeschlossen ist.
   Wenn Sie also entdecken, dass Sie auf einer Seite einen Fehler &uuml;bersehen
   haben, k&ouml;nnen Sie auf die entsprechende Seite klicken und sie
   erneut &ouml;ffnen, um den Fehler zu beheben.
</p>
<p>Au&szlig;erdem k&ouml;nnen Sie die Links &sbquo;Images, Pages Proofread,
   &amp;Differences&lsquo; (Vorlagen, korrigierte Seiten &amp; &Auml;nderungen)
   sowie &sbquo;Just My Pages&lsquo; (nur meine Seiten) auf der
   <a href="#comments">Projektseite</a> verwenden. Sie finden &sbquo;Edit&lsquo;
   (Bearbeiten)-Links neben allen Seiten, die Sie in dieser Runde
   bearbeitet haben und die noch &uuml;berarbeitet werden k&ouml;nnen.
</p>
<p>Weitere Informationen finden Sie entweder in der
   <a href="prooffacehelp.php?i_type=0">Hilfe zur Standard-Korrekturlese-Oberfl&auml;che</a>
   (nur Englisch) oder in der <a href="prooffacehelp.php?i_type=1">Hilfe
   zur Erweiterten Korrekturlese-Oberfl&auml;che</a> (nur Englisch),
   je nachdem, welche Benutzeroberfl&auml;che Sie verwenden.
</p>
<!-- END RR -->
<table width="100%" border="0" cellspacing="0" cellpadding="6" summary="Title Page">
  <tbody>
    <tr>
      <td bgcolor="silver"><font size="+2">Korrekturlesen: Richtlinien f&uuml;r ...</font></td>
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

<h3><a name="double_q">Doppelte Anf&uuml;hrungszeichen</a></h3>
<p>Verwenden Sie bitte die gew&ouml;hnlichen ASCII-Anf&uuml;hrungszeichen
   <tt>"</tt>. Ersetzen Sie keine doppelten durch einfache
   Anf&uuml;hrungszeichen. Lassen Sie sie so, wie der Autor sie
   geschrieben hat.
</p>
<p>Bei anderen Sprachen als Englisch verwenden Sie bitte die in dieser
   Sprache &uuml;blichen Anf&uuml;hrungszeichen, sofern sie
   verf&uuml;gbar sind. Die franz&ouml;sischen Guillemets <tt>&laquo;wie
   hier&raquo;</tt> sind in den Pulldown-Men&uuml;s der
   Korrekturlese-Oberfl&auml;che zu finden, weil sie zum Latin-1-Zeichensatz
   geh&ouml;ren. Bitten denken Sie daran, eventuelle Leerzeichen zwischen
   den Guillemets und dem zitierten Text zu entfernen; falls sie ben&ouml;tigt
   werden, werden sie bei der Nachbearbeitung eingef&uuml;gt. Das gleiche
   gilt f&uuml;r Sprachen, die umgekehrte Guillemets <tt>&raquo;wie
   hier&laquo;</tt> verwenden.
</p>
<p>In manchen Texten (in Deutsch oder anderen Sprachen) werden
   Anf&uuml;hrungszeichen <tt>&bdquo;wie diese&rdquo;</tt> benutzt.
<? if(!$utf8_site) { ?>
   Sie sind nicht in den Pulldown-Men&uuml;s verf&uuml;gbar, weil
   sie nicht zum Latin-1-Zeichensatz geh&ouml;ren. In diesem Fall folgen
   Sie den Anweisungen in den Projektkommentaren.
<? } else { ?>
   Sie sind auch in den Pulldown-Men&uuml;s enthalten. Der Einfachheit
   halber sollten Sie immer <tt>&bdquo;</tt> und <tt>&ldquo;</tt>
   benutzen, egal welche Anf&uuml;hrungszeichen im Originaltext benutzt
   werden, solange diese deutlich erkennbar untere und obere sind. Falls
   erforderlich, werden die Anf&uuml;hrungszeichen in der Nachbearbeitung
   ge&auml;ndert.
<? } ?>
</p>
<p>Unter Umst&auml;nden weist Sie der Projektmanager in den
   <a href="#comments">Projektkommentaren</a> an, nicht-englische
   Anf&uuml;hrungszeichen f&uuml;r ein bestimmtes Buch abweichend
   Korrektur zu lesen.
</p>

<h3><a name="single_q">Einfache Anf&uuml;hrungszeichen</a></h3>
<p>Lesen Sie diese bitte Korrektur als gew&ouml;hnliches einfaches
   ASCII-Anf&uuml;hrungszeichen <tt>'</tt> (Apostroph). Ersetzen Sie
   keine einfachen durch doppelte Anf&uuml;hrungszeichen. Lassen Sie
   sie so, wie der Autor sie geschrieben hat.
</p>

<h3><a name="quote_ea">Anf&uuml;hrungszeichen auf jeder Zeile</a></h3>
<p>Stehen bei Zitaten am Beginn jeder Zeile Anf&uuml;hrungszeichen,
   so entfernen Sie alle <b>mit Ausnahme</b> desjenigen in der ersten Zeile.
</p>
<p>Geht das Zitat &uuml;ber mehrere Abs&auml;tze, so sollte am Beginn
   eines jeden Absatzes ein &ouml;ffnendes Anf&uuml;hrungszeichen stehen.
</p>
<p>Oft befindet sich das schlie&szlig;ende Anf&uuml;hrungszeichen erst
   ganz am Ende des Zitates, also m&ouml;glicherweise nicht auf der Seite,
   die Sie gerade Korrektur lesen. K&uuml;mmern Sie sich nicht darum &ndash;
   f&uuml;gen Sie keine schlie&szlig;enden Anf&uuml;hrungszeichen hinzu,
   die nicht auf der Buchseite stehen.
</p>

<h3><a name="period_s">Punkte am Ende von S&auml;tzen</a></h3>
<p>Hinter den Punkt, der einen Satz beendet, geh&ouml;rt ein einfaches Leerzeichen.
</p>
<p>Es ist nicht n&ouml;tig, zus&auml;tzliche Leerzeichen nach Punkten
   zu entfernen, wenn sie schon im OCR-Text sind &ndash; wir k&ouml;nnen
   dies automatisch in der Nachbearbeitung erledigen. Siehe
   Beispielvorlage und Text zu <a href="#para_side">Randnoten
   (Marginalien)</a> als Beispiel.
</p>

<h3><a name="punctuat">Satzzeichen</a></h3>
<p>Im Allgemeinen sollten keine Leerzeichen vor Satzzeichen stehen,
   mit Ausnahme von &ouml;ffnenden Anf&uuml;hrungszeichen. Falls im
   OCR-Text ein Leerzeichen vor dem Satzzeichen steht, so entfernen
   Sie es. Das gilt auch f&uuml;r Sprachen wie Franz&ouml;sisch, die
   normalerweise Leerzeichen vor Satzzeichen benutzen.
</p>
<p>Leerzeichen vor Satzzeichen kommen bei alten B&uuml;chern manchmal
   vor, weil im 18. und 19. Jahrhundert vor z. B. Semikolon oder Komma
   oft &bdquo;Teil-Leerzeichen&ldquo; gesetzt wurden.
</p>
<!-- END RR -->

<table width="100%" align="center" border="1"  cellpadding="4" cellspacing="0" summary="Satzzeichen">
  <tbody>
    <tr><th align="left" bgcolor="cornsilk">Gescannter Text:</th></tr>
    <tr>
      <td valign="top">and so it goes&nbsp;; ever and ever.</td>
    </tr>
    <tr><th align="left" bgcolor="cornsilk">Richtig korrigierter Text:</th></tr>
    <tr>
      <td valign="top"><tt>and so it goes; ever and ever.</tt></td>
    </tr>
  </tbody>
</table>

<h3><a name="period_p">Auslassungspunkte &bdquo;...&ldquo; (Ellipse)</a></h3>
<p>Die Richtlinien f&uuml;r Englisch und f&uuml;r andere Sprachen
   (LOTE, Languages Other Than English) sind unterschiedlich.
</p>
<p><b>ENGLISCH</b>: Lassen Sie ein Leerzeichen vor den drei Punkten
   und eines danach. Die Ausnahme ist das Satzende: hier steht nach
   dem letzten Wort kein Leerzeichen, dann vier Punkte und danach ein
   Leerzeichen. Dasselbe gilt auch f&uuml;r andere Satzendzeichen:
   die drei Punkte folgen unmittelbar, ohne jedes Leerzeichen.
</p>
<p>Zum Beispiel:<br>
   <tt><br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;That I know ... is true.<br>
   &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;This is the end....<br>
   &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Wherefore art thou Romeo?...</tt>
</p>
<p>Manchmal steht das Satzzeichen auch erst nach der Ellipse. In diesem
   Fall lesen Sie so Korrektur:<br>
   <tt><br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Wherefore art thou Romeo...?</tt>
</p>
<p>Entfernen Sie vorhandene &uuml;berfl&uuml;ssige Punkte und
   erg&auml;nzen Sie falls n&ouml;tig fehlende, um immer die
   erforderlichen drei (oder vier) Punkte zu erhalten.
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

<h3><a name="contract">Zusammenziehungen</a></h3>
<p>Entfernen Sie Leerzeichen in Zusammenziehungen: zum Beispiel sollte
   <tt>would&nbsp;n't</tt> als <tt>wouldn't</tt> Korrektur gelesen werden.
</p>
<p>Diese L&uuml;cken waren h&auml;ufig eine Konvention der fr&uuml;hen
   Drucker. Sie behielten den Zwischenraum bei, um darauf hinzuweisen,
   dass &bdquo;would&ldquo; und &bdquo;not&ldquo; urspr&uuml;nglich
   getrennte W&ouml;rter waren. Manchmal ist der Zwischenraum auch nur
   ein Artefakt der OCR-Software. In beiden F&auml;llen wird er jedoch entfernt.
</p>
<p>Unter Umst&auml;nden geben Projektmanager in den
   <a href="#comments">Projektkommentaren</a> vor, dass solche Leerzeichen
   nicht entfernt werden sollen. Dies ist vor allem bei Texten der Fall,
   die Umgangssprache bzw. Dialekt enthalten oder in einer anderen Sprache
   als Englisch verfasst sind.
</p>


<h3><a name="extra_sp">&Uuml;berfl&uuml;ssige Leerzeichen bzw. Tabulatoren zwischen W&ouml;rtern</a></h3>
<p>Die Texterkennung verursacht oft &uuml;berfl&uuml;ssige Leerzeichen
   und Tabulatoren im Text. Sie brauchen sich nicht die M&uuml;he zu
   machen, diese Leerzeichen zu entfernen &ndash; das kann automatisch
   in der Nachbearbeitung erfolgen.
</p>
<p>&Uuml;berfl&uuml;ssige Leerzeichen um Satzzeichen, Gedankenstriche,
   Anf&uuml;hrungszeichen herum usw. <b>m&uuml;ssen</b> jedoch entfernt
   werden, wenn sie das Symbol vom Wort trennen.
</p>
<p>Im folgenden Beispiel: <tt>A horse&nbsp;;&nbsp;&nbsp;my kingdom for a
   horse.</tt> muss das Leerzeichen zwischen &bdquo;horse&ldquo; und
   dem Semikolon entfernt werden. Die zwei Leerzeichen nach dem Semikolon
   sind in Ordnung &ndash; Sie brauchen keines der beiden zu l&ouml;schen.
</p>

<h3><a name="trail_s">Leerzeichen am Zeilenende</a></h3>
<p>Verschwenden Sie keine Zeit damit, Leerzeichen am Ende jeder Zeile
   einzuf&uuml;gen. Das kann sp&auml;ter automatisch erledigt werden.
   Es ist auch nicht n&ouml;tig, &uuml;berfl&uuml;ssige Leerzeichen
   am Zeilenende zu entfernen.
</p>

<h3><a name="line_no">Zeilennummern</a></h3>
<p>Zeilennummern werden beibehalten. Trennen Sie sie mit mehreren
   Leerzeichen vom Text, damit die Formatierer sie leicht finden k&ouml;nnen.
</p>
<p>Zeilennummern, die am Seitenrand in jeder oder auch nur jeder
   f&uuml;nften oder zehnten Zeile stehen, sind vor allem in
   Gedichtb&auml;nden &uuml;blich. Da der Text von Gedichten f&uuml;r
   das E-Book nicht neu formatiert wird, sind die Zeilennummern auch
   f&uuml;r den Leser von Interesse.
</p>
<!-- END RR -->
<!-- We need an example image and text for this. -->

<h3><a name="italics">Kursiv- und Fettschrift</a></h3>
<p><i>Kursiver</i> Text erscheint manchmal mit einem <tt>&lt;i&gt;</tt>
   am Beginn und einem <tt>&lt;/i&gt;</tt> am Ende. <b>Fettschrift</b>
   (Text, der mit kr&auml;ftigeren Lettern gedruckt ist) ist entsprechend
   gelegentlich mit <tt>&lt;b&gt;</tt> am Anfang und <tt>&lt;/b&gt;</tt>
   am Ende markiert. Entfernen Sie diese Formatierungen nur dann, wenn
   sie falsch sind oder unbrauchbare Zeichen umschlie&szlig;en, die
   nicht auf der Seite stehen. F&uuml;gen Sie sie aber nicht hinzu, wo
   sie fehlen. Das wird von den Formatierern sp&auml;ter im Prozess erledigt.
</p>
<!-- END RR -->


<h3><a name="supers">Hochgestellte Zeichen</a></h3>
<p>In &auml;lteren B&uuml;chern wurden W&ouml;rter h&auml;ufig mit
   verk&uuml;rzten Endungen dargestellt, die dann hochgestellt wurden.
   Zum Beispiel:<br>
   &nbsp;&nbsp;&nbsp;&nbsp;Gen<sup>rl</sup> Washington defeated L<sup>d</sup> Cornwall's army.<br>
   Lesen Sie diese Korrektur, indem Sie einen einzelnen Zirkumflex ^
   einsetzen, gefolgt von dem hochgestellten Text. Also so:<br>
   &nbsp;&nbsp;&nbsp;&nbsp;<tt>Gen^rl Washington defeated L^d Cornwall's army.</tt>
</p>


<h3><a name="subscr">Tiefgestellte Zeichen</a></h3>
<p>Tiefgestellte Zeichen kommen oft in wissenschaftlichen Texten vor,
   in anderen Werken sind sie nicht &uuml;blich. Korrektur gelesen
   werden sie durch Einsetzen eines Unterstrichs <tt>_</tt>.<br>
   Zum Beispiel wird <br>&nbsp;&nbsp;&nbsp;&nbsp;H<sub>2</sub>O.<br>
   Korrektur gelesen als: <br>&nbsp;&nbsp;&nbsp;&nbsp;<tt>H_2O.<br></tt>
</p>


<h3><a name="font_sz">Unterschiedliche Schriftgr&ouml;&szlig;en</a></h3>
<p>Ignorieren Sie unterschiedliche Schriftgr&ouml;&szlig;en. Darum
   k&uuml;mmern sich sp&auml;ter die Formatierer.
</p>

<h3><a name="small_caps">W&ouml;rter in Kapit&auml;lchen</a></h3>
<p><span style="font-variant: small-caps">Kapit&auml;lchen</span>
   (Gro&szlig;buchstaben, die kleiner als die Standardtypen sind)
   erscheinen manchmal mit <tt>&lt;sc&gt;</tt> davor und <tt>&lt;/sc&gt;</tt>
   dahinter. Auch hier gilt: Entfernen Sie diese Formatierung nicht,
   au&szlig;er sie umschlie&szlig;t unbrauchbare Zeichen, die nicht
   auf der Seite stehen. F&uuml;gen Sie diese Markierungen aber auch
   nicht ein. Die Formatierer machen das sp&auml;ter. Pr&uuml;fen und
   korrigieren Sie jedoch die einzelnen Buchstaben. Machen Sie sich dabei
   keine Gedanken &uuml;ber Gro&szlig;- und Kleinschreibung. Sind die
   Zeichen schon GROSSBUCHSTABEN, kleinbuchstaben oder GeMIScht, so
   lassen Sie sie in GROSSBUCHSTABEN, kleinbuchstaben oder GeMIScht stehen.
</p>

<h3><a name="drop_caps">&Uuml;berdimensionale, verzierte Gro&szlig;buchstaben als Er&ouml;ffnung (Initialen)</a></h3>
<p>Lesen Sie einen gro&szlig;en, verzierten Buchstaben am Beginn
   eines Kapitels, eines Abschnitts oder einen Absatzes genau so
   Korrektur, als w&auml;re er ein gew&ouml;hnlicher Buchstabe.
</p>


<h3><a name="a_chars">Akzente und nicht-ASCII-Zeichen</a></h3>
<? if(!$utf8_site) { ?>
<p>Bitte lesen Sie diese Korrektur, indem Sie die entsprechenden
   Zeichen des Latin-1-Zeichensatzes verwenden, wo dies m&ouml;glich
   ist. Die korrekte Umschrift f&uuml;r Nicht-Latin-1-Zeichen finden
   Sie unter <a href="#d_chars">Buchstaben mit diakritischen Zeichen</a>.
</p>
<? } else { ?>
<p>Bitte lesen Sie diese Korrektur, indem Sie die richtigen UTF-8
   Zeichen verwenden. F&uuml;r Zeichen, die nicht in Unicode sind,
   gelten die Anweisungen des Projektmanagers in den
   <a href="#comments">Projektkommentaren</a>.
</p>
<? } ?>
<p>Sind diese Zeichen nicht auf Ihrer Tastatur, so gibt es mehrere
   M&ouml;glichkeiten zur Eingabe:</p>
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
      <li>Linux: Verschiedene, abh&auml;ngig von der Arbeitsumgebung.<br>
          Unter KDE k&ouml;nnen Sie KCharSelect (im Untermen&uuml;
          &sbquo;Dienstprogramme&lsquo; des Startmen&uuml;s) ausprobieren.</li>
      </ul>
  </li>
  <li>Mit einem Online-Programm, wie zum Beispiel <a
   href="http://free.pages.at/krauss/computer/xml/daten/edicode.html">Edicode</a>.</li>
  <li>Tastenk&uuml;rzel<br> (Tabellen f&uuml;r <a href="#a_chars_win">Windows</a>
      und <a href="#a_chars_mac">Macintosh</a> finden Sie weiter unten in diesem Dokument.)</li>
  <li>Durch Umschalten auf ein Tastaturlayout oder eine andere
      Landeseinstellung, die Akzente als Tottasten unterst&uuml;tzt.
       <ul compact>
       <li>Windows: Systemsteuerung (Regions- und Sprachoptionen)</li>
       <li>Macintosh: Inputmen&uuml; (in der Men&uuml;leiste)</li>
       <li>Linux: Einstellen der Tastatur in der X-Konfiguration.</li>
      </ul>
</ul>
<p>Von <a href="http://www.gutenberg.org">Project Gutenberg</a>
   wird als Minimalvariante eine 7-Bit-ASCII-Version des Textes
   ver&ouml;ffentlicht. Es werden jedoch auch Versionen in anderen
   Zeichens&auml;tzen akzeptiert, durch die mehr Informationen des
   Originals erhalten bleiben. <a href="http://pge.rastko.net">Project
   Gutenberg Europe</a> ver&ouml;ffentlicht UTF-8 als Standard-Zeichensatz;
   andere angemessene Zeichens&auml;tze sind dort ebenfalls willkommen.
</p>
<p>Gegenw&auml;rtig bedeutet das f&uuml;r <a href="http://www.pgdp.net/">Distributed
   Proofreaders</a>, dass Latin-1, ISO 8859-1 und -15 verwendet werden.
   In der Zukunft wird auch Unicode m&ouml;glich sein.
</p>
<p><a href="http://dp.rastko.net/">Distributed Proofreaders Europe</a>
   benutzt bereits Unicode.
</p>
<!-- END RR -->
<a name="a_chars_win"></a>
<p><b>F&uuml;r Windows</b>:
</p>
<p>Zur Auswahl eines Zeichens haben Sie folgende M&ouml;glichkeiten:
</p>
<ul compact>
  <li>das Programm &sbquo;Zeichentabelle&lsquo; (Start -&gt; Ausf&uuml;hren
      -&gt; charmap), dann Ausschneiden (Strg-X) und Einf&uuml;gen (Strg-V)  </li>
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
      Die Tabelle unten zeigt die zu verwendenden Codes.
      (<a href="charwin.pdf">Druckversion der Tabelle</a>)<br>
      Verwenden Sie keine anderen Sonderzeichen, es sei denn, der
      Projektmanager weist das in den <a href="#comments">Projektkommentaren</a> ausdr&uuml;cklich an.
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
      <th colspan=2 bgcolor="cornsilk">&OElig;-Ligatur</th>
  </tr>
  <tr><td align="center" bgcolor="mistyrose" title="Small o grave"         >&ograve; </td><td>Alt-0242</td>
      <td align="center" bgcolor="mistyrose" title="Small o acute"         >&oacute; </td><td>Alt-0243</td>
      <td align="center" bgcolor="mistyrose" title="Small o circumflex"    >&ocirc;  </td><td>Alt-0244</td>
      <td align="center" bgcolor="mistyrose" title="Small o tilde"         >&otilde; </td><td>Alt-0245</td>
      <td align="center" bgcolor="mistyrose" title="Small o umlaut"        >&ouml;   </td><td>Alt-0246</td>
      <td align="center" bgcolor="mistyrose" title="Small o slash"         >&oslash; </td><td>Alt-0248</td>
      <td align="center" bgcolor="mistyrose" title="Small oe ligature"     >&oelig;  </td><td>
<? if(!$utf8_site) { ?>
[oe] verw.
<? } else { ?>
Alt-0156
<? } ?>
      </td>
  </tr>
  <tr><td align="center" bgcolor="mistyrose" title="Capital O grave"       >&Ograve; </td><td>Alt-0210</td>
      <td align="center" bgcolor="mistyrose" title="Capital O acute"       >&Oacute; </td><td>Alt-0211</td>
      <td align="center" bgcolor="mistyrose" title="Capital O circumflex"  >&Ocirc;  </td><td>Alt-0212</td>
      <td align="center" bgcolor="mistyrose" title="Capital O tilde"       >&Otilde; </td><td>Alt-0213</td>
      <td align="center" bgcolor="mistyrose" title="Capital O umlaut"      >&Ouml;   </td><td>Alt-0214</td>
      <td align="center" bgcolor="mistyrose" title="Capital O slash"       >&Oslash; </td><td>Alt-0216</td>
      <td align="center" bgcolor="mistyrose" title="Capital OE ligature"   >&OElig;  </td><td>
<? if(!$utf8_site) { ?>
[OE] verw.
<? } else { ?>
Alt-0140
<? } ?>
      </td>
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
      <td> </td><td> </td>
      <td> </td><td> </td>
      <td align="center" bgcolor="mistyrose" title="Small n tilde"         >&ntilde; </td><td>Alt-0241</td>
      <td align="center" bgcolor="mistyrose" title="Small y umlaut"        >&yuml;   </td><td>Alt-0255</td>
      <td align="center" bgcolor="mistyrose" title="Cents"                 >&cent;   </td><td>Alt-0162</td>
      <td align="center" bgcolor="mistyrose" title="plus/minus"            >&plusmn; </td><td>Alt-0177</td>
  </tr>
  <tr><td> </td><td> </td>
      <td> </td><td> </td>
      <td> </td><td> </td>
      <td align="center" bgcolor="mistyrose" title="Capital N tilde"       >&Ntilde; </td><td>Alt-0209</td>
      <td align="center" bgcolor="mistyrose" title=""></td><td></td>
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
      <td align="center" bgcolor="mistyrose" title="Dollars"               >$   </td><td>Alt-0036</td>
      <td align="center" bgcolor="mistyrose" title="Logical Not"           >&not;    </td><td>Alt-0172</td>
  </tr>
  <tr><td align="center" bgcolor="mistyrose" title="Capital C cedilla"     >&Ccedil; </td><td>Alt-0199</td>
      <td align="center" bgcolor="mistyrose" title="Small thorn"           >&thorn;  </td><td>Alt-0254</td>
      <td align="center" bgcolor="mistyrose" title="Registration Mark"     >&reg;    </td><td>Alt-0174</td>
      <td align="center" bgcolor="mistyrose" title="umlaut accent"         >&uml;    </td><td>Alt-0168</td>
      <td align="center" bgcolor="mistyrose" title="Inverted Exclamation"  >&iexcl;  </td><td>Alt-0161</td>
      <td align="center" bgcolor="mistyrose" title="General Currency"      >&curren; </td><td>Alt-0164</td>
      <td align="center" bgcolor="mistyrose" title="Degrees"               >&deg;    </td><td>Alt-0176</td>
  </tr>
  <tr><th colspan=2 bgcolor="cornsilk">Hochzahlen        </th>
      <td align="center" bgcolor="mistyrose" title="Capital Eth"           >&ETH;    </td><td>Alt-0208</td>
      <td align="center" bgcolor="mistyrose" title="Trademark"             >&trade;  </td><td>Alt-0153</td>
      <td align="center" bgcolor="mistyrose" title="macron accent"         >&macr;   </td><td>Alt-0175</td>
      <td align="center" bgcolor="mistyrose" title="guillemot left"        >&laquo;  </td><td>Alt-0171</td>
      <td> </td><td> </td>
      <td align="center" bgcolor="mistyrose" title="Micro"                 >&micro;  </td><td>Alt-0181</td>
  </tr>
  <tr><td align="center" bgcolor="mistyrose" title="superscript 1"         >&sup1;   </td><td>Alt-0185</td>
      <td align="center" bgcolor="mistyrose" title="Small eth"             >&eth;    </td><td>Alt-0240</td>
      <td align="center" bgcolor="mistyrose" title="Paragraph (pilcrow)"   >&para;   </td><td>Alt-0182</td>
      <td align="center" bgcolor="mistyrose" title="cedilla"               >&cedil;  </td><td>Alt-0184</td>
      <td align="center" bgcolor="mistyrose" title="guillemot right"       >&raquo;  </td><td>Alt-0187</td>
      <th colspan=2 bgcolor="cornsilk">Ordnungszeichen</th>
      <td align="center" bgcolor="mistyrose" title="1/4 Fraction"          >&frac14; <sup><small>1</small></sup></td><td>Alt-0188</td>
  </tr>
  <tr><td align="center" bgcolor="mistyrose" title="superscript 2"         >&sup2;   </td><td>Alt-0178</td>
      <th colspan=2 bgcolor="cornsilk">sz-Ligatur        </th>
      <td align="center" bgcolor="mistyrose" title="Section"               >&sect;   </td><td>Alt-0167</td>
      <td> </td><td> </td>
      <td align="center" bgcolor="mistyrose" title="Middle dot"            >&middot; </td><td>Alt-0183</td>
      <td align="center" bgcolor="mistyrose" title="Masculine Ordinal"     >&ordm;   </td><td>Alt-0186</td>
      <td align="center" bgcolor="mistyrose" title="1/2 Fraction"          >&frac12; <sup><small>1</small></sup></td><td>Alt-0189</td>
  </tr>
  <tr><td align="center" bgcolor="mistyrose" title="superscript 3"         >&sup3;   </td><td>Alt-0179</td>
      <td align="center" bgcolor="mistyrose" title="sz ligature"           >&szlig;  </td><td>Alt-0223</td>
      <td align="center" bgcolor="mistyrose" title="Broken Vertical bar"   >&brvbar; </td><td>Alt-0166</td>
      <td> </td><td> </td>
      <td align="center" bgcolor="mistyrose" title="asterisk"              >*   </td><td>Alt-0042</td>
      <td align="center" bgcolor="mistyrose" title="Feminine Ordinal"      >&ordf;   </td><td>Alt-0170</td>
      <td align="center" bgcolor="mistyrose" title="3/4 Fraction"          >&frac34; <sup><small>1</small></sup></td><td>Alt-0190</td>
  </tr>
  </tbody>
</table>
<p><sup><small>1</small></sup>Wenn es in den
   <a href="#comments">Projektkommentaren</a> nicht ausdr&uuml;cklich
   verlangt wird, verwenden Sie bitte keine Bruch-Symbole, sondern die
   Regeln f&uuml;r <a href="#fract_s">Br&uuml;che</a> (1/2, 1/4, 3/4, usw.).</p>


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
      Die Tabelle unten zeigt die zu verwendenden Codes.
      (<a href="charapp.pdf">Druckversion der Tabelle</a>)<br>
      Verwenden Sie keine anderen Sonderzeichen, es sei denn, der
      Projektmanager weist das in den <a href="#comments">Projektkommentaren</a>
      ausdr&uuml;cklich an.
  </li>
</ul>

<br>
<a name="a_chars_mac"></a>
<table align="center" border="6" rules="all" summary="Mac shortcuts">
  <tbody>
  <tr bgcolor="cornsilk"  >
      <th colspan=14>Apple-Mac-Tastenk&uuml;rzel f&uuml;r Latin-1-Symbole</th>
  <tr bgcolor="cornsilk"  >
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
      <th colspan=2 bgcolor="cornsilk">&OElig;-Ligatur</th>
  </tr>
  <tr><td align="center" bgcolor="mistyrose" title="Small o grave"         >&ograve; </td><td>Opt-`, o</td>
      <td align="center" bgcolor="mistyrose" title="Small o acute"         >&oacute; </td><td>Opt-e, o</td>
      <td align="center" bgcolor="mistyrose" title="Small o circumflex"    >&ocirc;  </td><td>Opt-i, o</td>
      <td align="center" bgcolor="mistyrose" title="Small o tilde"         >&otilde; </td><td>Opt-n, o</td>
      <td align="center" bgcolor="mistyrose" title="Small o umlaut"        >&ouml;   </td><td>Opt-u, o</td>
      <td align="center" bgcolor="mistyrose" title="Small o slash"         >&oslash; </td><td>Opt-o   </td>
      <td align="center" bgcolor="mistyrose" title="Small oe ligature"     >&oelig;  </td><td>
<? if(!$utf8_site) { ?>
[oe] verw.
<? } else { ?>
Opt-q
<? } ?>
      </td>
  </tr>
  <tr><td align="center" bgcolor="mistyrose" title="Capital O grave"       >&Ograve; </td><td>Opt-`, O</td>
      <td align="center" bgcolor="mistyrose" title="Capital O acute"       >&Oacute; </td><td>Opt-e, O</td>
      <td align="center" bgcolor="mistyrose" title="Capital I circumflex"  >&Ocirc;  </td><td>Opt-i, O</td>
      <td align="center" bgcolor="mistyrose" title="Capital O tilde"       >&Otilde; </td><td>Opt-n, O</td>
      <td align="center" bgcolor="mistyrose" title="Capital O umlaut"      >&Ouml;   </td><td>Opt-u, O</td>
      <td align="center" bgcolor="mistyrose" title="Capital O slash"       >&Oslash; </td><td>Opt-O   </td>
      <td align="center" bgcolor="mistyrose" title="Capital OE ligature"   >&OElig;  </td><td>
<? if(!$utf8_site) { ?>
[OE] verw.
<? } else { ?>
Opt-Q
<? } ?>
      </td>
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
      <td> </td><td> </td>
      <td> </td><td> </td>
      <td align="center" bgcolor="mistyrose" title="Small n tilde"         >&ntilde; </td><td>Opt-n, n</td>
      <td align="center" bgcolor="mistyrose" title="Small y umlaut"        >&yuml;   </td><td>Opt-u, y</td>
      <td align="center" bgcolor="mistyrose" title="Cents"                 >&cent;   </td><td>Opt-4   </td>
      <td align="center" bgcolor="mistyrose" title="plus/minus"            >&plusmn; </td><td>Shift-Opt-=</td>
  </tr>
  <tr><td> </td><td> </td>
      <td> </td><td> </td>
      <td> </td><td> </td>
      <td align="center" bgcolor="mistyrose" title="Capital N tilde"       >&Ntilde; </td><td>Opt-n, N</td>
      <td align="center" bgcolor="mistyrose" title=""></td><td></td>
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
      <td align="center" bgcolor="mistyrose" title="Dollars"               >$   </td><td>Shift-4</td>
      <td align="center" bgcolor="mistyrose" title="Logical Not"           >&not;    </td><td>Opt-l   </td>
  </tr>
  <tr><td align="center" bgcolor="mistyrose" title="Capital C cedilla"     >&Ccedil; </td><td>Opt-C   </td>
      <td align="center" bgcolor="mistyrose" title="Small thorn"           >&thorn;  </td><td>(n.&nbsp;v.)&nbsp;&Dagger;</td>
      <td align="center" bgcolor="mistyrose" title="Registration Mark"     >&reg;    </td><td>Opt-r   </td>
      <td align="center" bgcolor="mistyrose" title="umlaut accent"         >&uml;    </td><td>Opt-U   </td>
      <td align="center" bgcolor="mistyrose" title="Inverted Exclamation"  >&iexcl;  </td><td>Opt-1   </td>
      <td align="center" bgcolor="mistyrose" title="General Currency"      >&curren; </td><td>(n.&nbsp;v.)&nbsp;&Dagger;</td>
      <td align="center" bgcolor="mistyrose" title="Degrees"               >&deg;    </td><td>Shift-Opt-8   </td>
  </tr>
  <tr><th colspan=2 bgcolor="cornsilk">Hochzahlen        </th>
      <td align="center" bgcolor="mistyrose" title="Capital Eth"           >&ETH;    </td><td>(n.&nbsp;v.)&nbsp;&Dagger;</td>
      <td align="center" bgcolor="mistyrose" title="Trademark"             >&trade;  </td><td>Opt-2   </td>
      <td align="center" bgcolor="mistyrose" title="macron accent"         >&macr;   </td><td>Shift-Opt-,</td>
      <td align="center" bgcolor="mistyrose" title="guillemot left"        >&laquo;  </td><td>Opt-\   </td>
      <td> </td><td> </td>
      <td align="center" bgcolor="mistyrose" title="Micro"                 >&micro;  </td><td>Opt-m   </td>
  </tr>
  <tr><td align="center" bgcolor="mistyrose" title="superscript 1"         >&sup1;   </td><td>(n.&nbsp;v.)&nbsp;&Dagger;</td>
      <td align="center" bgcolor="mistyrose" title="Small eth"             >&eth;    </td><td>(n.&nbsp;v.)&nbsp;&Dagger;</td>
      <td align="center" bgcolor="mistyrose" title="Paragraph (pilcrow)"   >&para;   </td><td>Opt-7   </td>
      <td align="center" bgcolor="mistyrose" title="cedilla"               >&cedil;  </td><td>Opt-Z   </td>
      <td align="center" bgcolor="mistyrose" title="guillemot right"       >&raquo;  </td><td>Shift-Opt-\</td>
      <th colspan=2 bgcolor="cornsilk">Ordnungszeichen</th>
      <td align="center" bgcolor="mistyrose" title="1/4 Fraction"          >&frac14; </td><td>(n.&nbsp;v.)&nbsp;&Dagger;<sup><small>1</small></sup>  </td>
  </tr>
  <tr><td align="center" bgcolor="mistyrose" title="superscript 2"         >&sup2;   </td><td>(n.&nbsp;v.)&nbsp;&Dagger;</td>
      <th colspan=2 bgcolor="cornsilk">sz-Ligatur        </th>
      <td align="center" bgcolor="mistyrose" title="Section"               >&sect;   </td><td>Opt-6   </td>
      <td> </td><td> </td>
      <td align="center" bgcolor="mistyrose" title="Middle dot"            >&middot; </td><td>Shift-Opt-9  </td>
      <td align="center" bgcolor="mistyrose" title="Masculine Ordinal"     >&ordm;   </td><td>Opt-0   </td>
      <td align="center" bgcolor="mistyrose" title="1/2 Fraction"          >&frac12; </td><td>(n.&nbsp;v.)&nbsp;&Dagger;<sup><small>1</small></sup>  </td>
  </tr>
  <tr><td align="center" bgcolor="mistyrose" title="superscript 3"         >&sup3;   </td><td>(n.&nbsp;v.)&nbsp;&Dagger;</td>
      <td align="center" bgcolor="mistyrose" title="sz ligature"           >&szlig;  </td><td>Opt-s   </td>
      <td align="center" bgcolor="mistyrose" title="Broken Vertical bar"   >&brvbar; </td><td>(n.&nbsp;v.)&nbsp;&Dagger;</td>
      <td> </td><td> </td>
      <td align="center" bgcolor="mistyrose" title="asterisk"              >*   </td><td>Shift-8 </td>
      <td align="center" bgcolor="mistyrose" title="Feminine Ordinal"      >&ordf;   </td><td>Opt-9   </td>
      <td align="center" bgcolor="mistyrose" title="3/4 Fraction"          >&frac34; </td><td>(n.&nbsp;v.)&nbsp;&Dagger;<sup><small>1</small></sup>  </td>
  </tr>
  </tbody>
</table>
<p>&Dagger;&nbsp;Anmerkung: Kein Tastenk&uuml;rzel vorhanden; verwenden Sie die Pulldown-Men&uuml;s.
</p>
<p><sup><small>1</small></sup>Wenn es in den
   <a href="#comments">Projektkommentaren</a> nicht ausdr&uuml;cklich
   verlangt wird, verwenden Sie bitte keine Bruch-Symbole, sondern die
   Regeln f&uuml;r <a href="#fract_s">Br&uuml;che</a> (1/2, 1/4, 3/4, usw.).
</p>

<h3><a name="d_chars">Buchstaben mit diakritischen Zeichen</a></h3>
<p>In manchen Projekten gibt es Buchstaben mit speziellen Kennzeichnungen
   &uuml;ber oder unter dem normalen lateinischen Buchstaben A ... Z.
   Sie werden <i>diakritische Zeichen</i> genannt und weisen auf eine
   spezielle Aussprache dieses Buchstabens hin.
<? if($utf8_site) { ?>
</p>
<p>Wenn ein solches Zeichen in Unicode nicht existiert, geben Sie es
   mittels <i>kombinierender diakritischer Zeichen</i> ein. Das sind
   Unicode-Symbole, die nicht allein stehen k&ouml;nnen, vielmehr
   erscheinen sie &uuml;ber (oder unter) dem Buchstaben, nach dem sie
   gesetzt sind. Zun&auml;chst wird der Basisbuchstabe getippt, dann
   das Kombinationszeichen. Dazu benutzt man Applets und Programme
   wie <a href="#a_chars">oben</a> genannt.
</p>
<p>Auf manchen Rechnern erscheinen diakritische Zeichen nicht genau
   dort, wo sie sein sollten, sondern z. B. nach rechts verschoben.
   Sie sollten dennoch benutzt werden, denn auf anderen Rechnern
   werden sie korrekt angezeigt. Jedoch: Wenn Sie aus irgendeinem
   Grund Kombinationszeichen nicht ordentlich sehen oder eingeben
   k&ouml;nnen, markieren Sie einen solchen Buchstaben mit einem
   <tt>*</tt>. Beachten Sie, dass es auch &bdquo;spacing modifier
   letters&ldquo; (Zeichen, die wie Buchstaben eigenen Platz beanspruchen)
   gibt; diese sollten nicht benutzt werden.
</p>
<? } else { ?>
Beim Korrekturlesen deuten wir sie in unserem normalen ASCII-Text mit
   einer speziellen Kodierung an, wie: &#259; wird zu <tt>[)a]</tt>
   f&uuml;r einen Brevis (den u-f&ouml;rmigen Akzent) &uuml;ber dem a
   oder <tt>[a)]</tt> f&uuml;r einen Brevis unter dem Buchstaben.
</p>
<p>Vergessen Sie nicht die eckigen Klammern (<tt>[&nbsp;]</tt>) rund
   um diese Zeichenkombination, damit der Nachbearbeiter wei&szlig;, zu
   welchem Buchstaben das Zeichen geh&ouml;rt. Bei der Nachbearbeitung
   wird es dann durch ein Zeichen ersetzt, das in der endg&uuml;ltigen
   Textversion (7-bit-ASCII, 8-bit, Unicode, html usw.) m&ouml;glich ist.
</p>
<p>Beachten Sie auch, dass es einige Buchstaben mit diakritischen Zeichen
   (vor allem die Vokale) bereits in unserem Standard-Latin-1-Zeichensatz
   gibt. <b>Verwenden Sie in diesem Falle das Latin-1-Zeichen
   (siehe <a href="#a_chars">hier</a>), das Sie zum Beispiel &uuml;ber
   die Pulldown-Men&uuml;s in der Korrekturlese-Oberfl&auml;che
   aufrufen k&ouml;nnen.</b>
</p>
<!-- END RR -->

<p>Die folgende Tabelle listet die derzeit verwendeten Kodierungen auf.
   Das &bdquo;x&ldquo; steht dabei f&uuml;r einen beliebigen Buchstaben,
   der ein diakritisches Zeichen erh&auml;lt.<br>Beim Korrigieren
   verwenden Sie das jeweilige Zeichen aus dem Text anstelle des im
   Beispiel verwendeten <tt>x</tt>.
</p>

<!--
  diacritical mark           above  below
macron (straight line)       [=x]   [x=]
2 dots (dieresis or umlaut)  [:x]   [x:]
1 dot                        [.x]   [x.]
grave accent                 ['x]   [x'] or [/x] [x/]
acute (aigu) accent          [`x]   [x`] or [\x] [x\]
circumflex                   [^x]   [x^]
caron (v-shaped symbol)      [vx]   [xv]
breve (u-shaped symbol)      [)x]   [x)]
tilde                        [~x]   [x~]
cedilla                      [,x]   [x,]
-->

<table align="center" border="6" rules="all" summary="Diacriticals">
  <tbody>
  <tr bgcolor="cornsilk">
      <th colspan=4>Symbole zum Korrekturlesen von diakritischen Zeichen</th>
  <tr bgcolor="cornsilk">
      <th>Diakritisches Zeichen</th>
      <th>Beispiel</th>
      <th>&Uuml;ber</th>
      <th>Unter</th>
   </tr>
  <tr><td>Makron (Querstrich)</td>
      <td align="center">&macr;</td>
      <td align="center"><tt>[=x]</tt></td>
      <td align="center"><tt>[x=]</tt></td>
      </tr>
  <tr><td>2 Punkte (Trema, Umlaut)</td>
      <td align="center">&uml;</td>
      <td align="center"><tt>[:x]</tt></td>
      <td align="center"><tt>[x:]</tt></td>
      </tr>
  <tr><td>1 Punkt</td>
      <td align="center">&middot;</td>
      <td align="center"><tt>[.x]</tt></td>
      <td align="center"><tt>[x.]</tt></td>
      </tr>
  <tr><td>Gravis</td>
      <td align="center">`</td>
      <td align="center"><tt>[`x]</tt> oder <tt>[\x]</tt></td>
      <td align="center"><tt>[x`]</tt> oder <tt>[x\]</tt></td>
      </tr>
  <tr><td>Akut (aigu)</td>
      <td align="center">&acute;</td>
      <td align="center"><tt>['x]</tt> oder <tt>[/x]</tt></td>
      <td align="center"><tt>[x']</tt> oder <tt>[x/]</tt></td>
      </tr>
  <tr><td>Zirkumflex</td>
      <td align="center">&circ;</td>
      <td align="center"><tt>[^x]</tt></td>
      <td align="center"><tt>[x^]</tt></td>
      </tr>
  <tr><td>Hatschek (v-f&ouml;rmiges Symbol)</td>
      <td align="center"><font size="-2">&or;</font></td>
      <td align="center"><tt>[vx]</tt></td>
      <td align="center"><tt>[xv]</tt></td>
      </tr>
  <tr><td>Brevis (u-f&ouml;rmiges Symbol)</td>
      <td align="center"><font size="-2">&cup;</font></td>
      <td align="center"><tt>[)x]</tt></td>
      <td align="center"><tt>[x)]</tt></td>
      </tr>
  <tr><td>Tilde</td>
      <td align="center">&tilde;</td>
      <td align="center"><tt>[~x]</tt></td>
      <td align="center"><tt>[x~]</tt></td>
      </tr>
  <tr><td>Cedille</td>
      <td align="center">&cedil;</td>
      <td align="center"><tt>[,x]</tt></td>
      <td align="center"><tt>[x,]</tt></td>
      </tr>
  </tbody>
</table>
<? } ?>

<h3><a name="f_chars">Nicht-lateinische Zeichen</a></h3>
<p>Manche Projekte beinhalten Zeichen aus nicht-lateinischen Schriften,
   also andere als die lateinischen Buchstaben A...Z &ndash;
   beispielsweise griechische, kyrillische (verwendet in Russisch,
   Slawisch usw.), hebr&auml;ische oder arabische Buchstaben.
</p>
<? if(strcasecmp($charset,"UTF-8")) { ?>
<p>Bei griechischen Buchstaben sollten Sie eine Transliteration
   versuchen. Dabei wird jeder Buchstabe des fremden Alphabets in
   den/die betreffenden lateinischen Buchstaben &uuml;bertragen.
   Ein Tool, das die griechische Transliteration erleichtert, k&ouml;nnen
   Sie aus der Korrekturlese-Oberfl&auml;che aufrufen.
</p>
<p>Klicken Sie dazu auf die Schaltfl&auml;che
   &sbquo;Greek-Transliterator&lsquo; im unteren Bereich der
   Korrekturlese-Oberfl&auml;che. W&auml;hlen Sie dann mit der Maus
   die ben&ouml;tigten griechischen Buchstaben aus. Die entsprechenden
   Latin-1-Zeichen werden im Textfeld eingef&uuml;gt. Wenn Sie
   fertig sind, &uuml;bernehmen Sie den transliterierten Text mit
   &bdquo;Ausschneiden &amp; Einf&uuml;gen&ldquo; in die aktuelle
   Buchseite. Umschlie&szlig;en Sie den transliterierten Text noch mit
   der Griechisch-Markierung <tt>[Greek:&nbsp;</tt> und <tt>]</tt>.
   So wird zum Beispiel <b>&Beta;&iota;&beta;&lambda;&omicron;&sigmaf;</b>
   in der Transliteration zu <tt>[Greek: Biblos]</tt>
   (&bdquo;Buch&ldquo; &ndash; ein passendes Beispiel f&uuml;r DP!).
</p>
<p>Wenn Sie sich Ihrer Transliteration nicht sicher sind, markieren Sie
   diese mit zwei Sternchen <tt>**</tt>, damit die Korrekturleser in den
   sp&auml;teren Runden und der Nachbearbeiter darauf aufmerksam werden.
</p>
<p>Andere Schriften, die nicht so leicht transliteriert werden k&ouml;nnen
   wie etwa Kyrillisch, Hebr&auml;isch oder Arabisch, kennzeichnen Sie
   einfach mit der entsprechenden Markierung <tt>[Cyrillic:&nbsp;**]</tt>,
   <tt>[Hebrew:&nbsp;**]</tt> oder <tt>[Arabic:&nbsp;**]</tt>. Den
   Text lassen Sie so stehen, wie er gescannt wurde. Vergessen Sie dabei
   nicht die beiden Sternchen <tt>**</tt>, damit der Nachbearbeiter den
   Text leichter finden und bearbeiten kann.
</p>
<!-- END RR -->

<ul compact>
  <li>Griechisch: Hierbei hilft das
      <a href="<? echo $PG_greek_howto_url; ?>">Greek HOWTO</a> (des
      Project Gutenberg) und das Popup-Tool &sbquo;Greek Transliterator&lsquo;
      der Korrekturlese-Oberfl&auml;che.
  </li>
  <li>Kyrillisch: Es gibt zwar eine Standard-Transliteration f&uuml;r
      kyrillische Zeichen, aber Sie sollten sich nur daran versuchen,
      wenn Sie die entsprechende Sprache in kyrillischer Schrift gut
      beherrschen und flie&szlig;end sprechen. Andernfalls setzen Sie
      einfach eine Markierung wie oben beschrieben. Vielleicht wird Ihnen
      diese <a href="http://learningrussian.com/transliteration.htm">Transliterationstabelle</a>
      n&uuml;tzlich sein.
  </li>
  <li>Hebr&auml;isch und Arabisch: Eine Bearbeitung ist nur sinnvoll,
      wenn Sie die Sprache flie&szlig;end sprechen. Es gibt einige
      prinzipielle Probleme bei der Transliteration, und weder
      <a href="..">Distributed Proofreaders</a> noch
      <a href="<? echo $PG_home_url; ?>">Project Gutenberg</a>
      haben bisher eine Standardmethode festgelegt.
  </li>
</ul>
<? } else { ?>
<p>Diese Zeichen sollten in den Text eingegeben werden wie lateinische
   Buchstaben (<b>OHNE Transliteration!</b>).
</p>
<p>Ist ein Dokument vollst&auml;ndig in einer nicht-lateinischen
   Schrift gedruckt, so ist es am besten, einen Tastaturtreiber zu
   installieren, der die entsprechende Sprache unterst&uuml;tzt. Sehen
   Sie im Handbuch Ihres Betriebssystems nach, wie das gemacht wird.
</p>
<p>Taucht die Schrift nur bei vereinzelten W&ouml;rtern auf, k&ouml;nnen
   Sie auch ein externes Programm zur Eingabe verwenden. Einige solche
   Programme finden sie <a href="#a_chars">weiter oben</a>.
</p>
<p>Sollten Sie sich hinsichtlich eines Zeichens oder Akzents unsicher
   sein, markieren Sie die Stelle mit <tt>*</tt>, damit sie der
   Nachbearbeiter leicht finden kann.
</p>
<p>Bei Schriften, die nicht so einfach transliteriert werden k&ouml;nnen,
   wie z.B. Arabisch, umgeben Sie den Text mit der entsprechenden Markierung
   <tt>[Arabic:&nbsp;**]</tt> und lassen ihn wie eingescannt stehen.
   Schlie&szlig;en Sie die <tt>**</tt> ein, damit der Nachbearbeiter
   den Text sp&auml;ter leichter finden und bearbeiten kann.
</p>
<? } ?>

<h3><a name="fract_s">Br&uuml;che</a></h3>
<p>Korrigieren Sie <b>Br&uuml;che</b> folgenderma&szlig;en:
   <tt>2&frac12;</tt> wird zu <tt>2-1/2</tt>. Der Bindestrich
   zwischen ganzer Zahl und Bruch verhindert bei der Nachbearbeitung,
   dass die Zeile an dieser Stelle umgebrochen wird.
</p>


<h3><a name="em_dashes">Bindestriche, kurze und lange Gedankenstriche</a></h3>
<p>Normalerweise kommen in B&uuml;chern vier Arten solcher Zeichen vor:
  <ol compact>
    <li><i>Bindestriche:</i> Sie werden verwendet, um zwei
        W&ouml;rter oder eine Vor- oder Nachsilbe mit einem Wort zu <b>verbinden</b>.<br>
        Belassen Sie diese als einzelnen Bindestrich, ohne Leerzeichen
        davor oder danach.<br>
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
        Verwenden Sie daf&uuml;r zwei oder, bei besonders langen
        Gedankenstrichen, vier Bindestriche. Setzen Sie kein Leerzeichen
        davor oder danach, auch wenn es im Buch so aussieht.
    </li>
    <li><i>Bewusst ausgelassene bzw. zensierte W&ouml;rter oder Namen:</i><br>
        Setzen Sie daf&uuml;r vier Bindestriche als Auslassungszeichen
        ein. Deutet der Gedankenstrich ein ganzes Wort an, so werden
        Leerzeichen davor und danach so gesetzt, als st&uuml;nde das Wort
        da. Wenn er nur Teil eines Wortes ersetzt, werden keine Leerzeichen
        eingef&uuml;gt. Ist ein normal langer Gedankenstrich gedruckt
        (in der L&auml;nge eines &bdquo;Em-dash&ldquo;), dann verwenden
        Sie auch beim Korrekturlesen nur zwei Bindestriche daf&uuml;r.
    </li>
  </ol>
<p>Anmerkung: Steht ein langer Gedankenstrich am Anfang oder am Ende
   einer Zeile des OCR-Textes, dann verbinden Sie ihn so mit der
   vorhergehenden oder nachfolgenden Zeile, dass weder Leerzeichen
   noch Zeilenumbruch rund um den Gedankenstrich stehen. Nur wenn der
   Autor den Gedankenstrich verwendet hat, um einen Absatz bzw. eine
   Gedicht- oder Dialogzeile zu beginnen bzw. zu beenden, sollten Sie
   ihn am Anfang bzw. Ende einer Zeile stehen lassen. (Siehe Beispiele
   in der Tabelle unten.)
</p>
<!-- END RR -->

<p><b>Beispiele</b> &ndash; Bindestriche, kurze und lange Gedankenstriche
</p>

<table width="100%" align="center" border="1"  cellpadding="4" cellspacing="0" summary="Hyphens and Dashes">
  <tbody>
    <tr>
      <th valign="top" bgcolor="cornsilk">Vorlage:</th>
      <th valign="top" bgcolor="cornsilk">Richtig korrigierter Text:</th>
      <th valign="top" bgcolor="cornsilk">Typ:</th>
    </tr>
    <tr>
      <td valign="top">semi-detached</td>
      <td valign="top"><tt>semi-detached</tt></td>
      <td> Bindestrich</td>
    </tr>
    <tr>
      <td valign="top">three- and four-part harmony</td>
      <td valign="top"><tt>three- and four-part harmony</tt></td>
      <td> Bindestriche</td>
    </tr>
    <tr>
      <td valign="top">discoveries which the Crus-<br>
        aders made and brought home with</td>
      <td valign="top"><tt>discoveries which the Crusaders<br>
        made and brought home with</tt></td>
      <td> Bindestrich</td>
    </tr>
    <tr>
      <td valign="top">factors which mold char-<br>
        acter&mdash;environment, training and heritage,</td>
      <td valign="top"><tt>factors which mold character--environment,<br>
        training and heritage,</tt>
      <td> Bindestrich</td>
    </tr>
    <tr>
      <td valign="top">See pages 21&ndash;25</td>
      <td valign="top"><tt>See pages 21-25</tt></td>
      <td>kurzer Gedankenstrich</td>
    </tr>
    <tr>
      <td valign="top">&ndash;14&deg; below zero</td>
      <td valign="top"><tt>-14&deg; below zero</tt></td>
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
      <td valign="top">I am hurt;&mdash;A plague<br>
        on both your houses!&mdash;I am dead.</td>
      <td valign="top"><tt>I am hurt;--A plague<br>
        on both your houses!--I am dead.</tt></td>
      <td>langer Gedankenstrich</td>
    </tr>
    <tr>
      <td valign="top">sensations&mdash;sweet, bitter, salt, and sour<br>
        &mdash;if even all of these are simple tastes. What</td>
      <td valign="top"><tt>sensations--sweet, bitter, salt, and sour--if<br>
        even all of these are simple tastes. What</tt></td>
      <td>langer Gedankenstrich</td>
    </tr>
    <tr>
      <td valign="top">senses&mdash;touch, smell, hearing, and sight&mdash;<br>
        with which we are here concerned,</td>
      <td valign="top"><tt>senses--touch, smell, hearing, and sight--with<br>
        which we are here concerned,</tt></td>
      <td>langer Gedankenstrich</td>
    </tr>
    <tr>
      <td valign="top">It is the east, and Juliet is the sun!&mdash;</td>
      <td valign="top"><tt>It is the east, and Juliet is the sun!--</tt></td>
      <td>langer Gedankenstrich</td>
    </tr>
    <tr>
      <td valign="top">"Three hundred&mdash;&mdash;" "years," she was going to say, but the left-hand cat interrupted her.</td>
      <td valign="top"><tt>"Three hundred----" "years," she was going to say, but the left-hand cat interrupted her.</tt></td>
      <td>Auslassungszeichen</td>
    </tr>
    <tr>
      <td valign="top">As the witness Mr. &mdash;&mdash; testified,</td>
      <td valign="top"><tt>As the witness Mr. ---- testified,</tt></td>
      <td>Auslassungszeichen</td>
    </tr>
    <tr>
      <td valign="top">As the witness Mr. S&mdash;&mdash; testified,</td>
      <td valign="top"><tt>As the witness Mr. S---- testified,</tt></td>
      <td>Auslassungszeichen</td>
    </tr>
    <tr>
      <td valign="top">the famous detective of &mdash;&mdash;B Baker St.</td>
      <td valign="top"><tt>the famous detective of ----B Baker St.</tt></td>
      <td>Auslassungszeichen</td>
    </tr>
    <tr>
      <td valign="top">&ldquo;You &mdash;&mdash; Yankee&rdquo;, she yelled.</td>
      <td valign="top"><tt>"You ---- Yankee", she yelled.</tt></td>
      <td>Auslassungszeichen</td>
    </tr>
    <tr>
      <td valign="top">&ldquo;I am not a d&mdash;d Yankee&rdquo;, he replied.</td>
      <td valign="top"><tt>"I am not a d--d Yankee", he replied.</tt></td>
      <td>langer Gedankenstrich</td>
    </tr>
  </tbody>
</table>

<h3><a name="eol_hyphen">Trennstriche am Zeilenende</a></h3>
<p>Wenn ein Bindestrich als Trennzeichen am Zeilenende steht, dann
   f&uuml;gen Sie die getrennten Wortteile wieder zusammen. Handelt es
   sich tats&auml;chlich um ein zwei- oder mehrteiliges Wort, wie etwa
   &bdquo;well-meaning&ldquo;, dann bringen Sie die Wortteile in eine
   Zeile, lassen aber den Bindestrich stehen. Wenn ein Wort jedoch getrennt
   wurde, weil es nicht mehr in die Zeile passte und wenn es normalerweise
   nicht mit Bindestrich geschrieben wird, dann entfernen Sie den
   Trennstrich. Lassen Sie das zusammengef&uuml;gte Wort in der oberen
   Zeile und setzen Sie danach einen Zeilenumbruch, um das Zeilenformat
   zu erhalten &ndash; das macht es einfacher f&uuml;r die Korrekturleser
   in sp&auml;teren Runden. Im Abschnitt <a href="#em_dashes">Bindestriche,
   kurze und lange Gedankenstriche</a> der Korrekturlese-Richtlinien
   finden Sie Beispiele (&bdquo;nar-row&ldquo; wird zu &bdquo;narrow&ldquo;,
   aber bei &bdquo;low-lying&ldquo; wird der Bindestrich beibehalten).
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

<h3><a name="eop_hyphen">Trennstriche am Seitenende</a></h3>
<p>Trenn- und Gedankenstriche am Ende einer Seite werden Korrektur
   gelesen, indem Sie das Trennzeichen oder den Gedankenstrich am
   Ende der letzten Zeile stehen lassen. Setzen Sie ein Sternchen
   <tt>*</tt> hinter den Trennstrich. Beispiel:<br>
   &nbsp;<br>
   &nbsp;&nbsp;&nbsp;&nbsp;something Pat had already become accus-<br>
   wird korrigiert zu:<br>
   &nbsp;&nbsp;&nbsp;&nbsp;<tt>something Pat had already become accus-*</tt>
</p>
<p>Auf Seiten, die mit einem Teil eines Wortes der vorhergehenden Seite
   bzw. mit einem Gedankenstrich beginnen, setzen Sie ein <tt>*</tt>
   vor das Teilwort bzw. den Gedankenstrich.<br>
   Um das obige Beispiel fortzusetzen: Lesen Sie<br>
   &nbsp;<br>
   &nbsp;&nbsp;&nbsp;&nbsp;tomed to from having to do his own family<br>
   Korrektur als:<br>
   &nbsp;&nbsp;&nbsp;&nbsp;<tt>*tomed to from having to do his own family</tt>
</p>
<p>Die Sternchen zeigen dem Nachbearbeiter, dass das Wort beim
   Zusammenf&uuml;gen der einzelnen Seiten zum endg&uuml;ltigen
   E-Book verbunden werden muss.
</p>


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


<h3><a name="para_space">Absatzabst&auml;nde und -einr&uuml;ckungen</a></h3>
<p>Abs&auml;tze trennen Sie bitte mit einer Leerzeile. Die erste Zeile
   des Absatzes erh&auml;lt normalerweise keinen Zeileneinzug. Sind
   Abs&auml;tze aber bereits einger&uuml;ckt, so bem&uuml;hen Sie sich
   nicht, die Leerzeichen zu entfernen &ndash; das kann automatisch in
   der Nachbearbeitung erledigt werden.
</p>
<p>Ein Beispiel finden Sie im Abschnitt <a href="#para_side">Randnoten</a>
   weiter unten.
</p>

<h3><a name="mult_col">Mehrspaltige Texte</a></h3>
<p>Lesen Sie normalen Text, der in zwei Spalten gedruckt wurde, als eine Spalte.
</p>
<p>Mehrspaltiger Text innerhalb eines sonst einspaltigen Abschnitts
   sollte als eine fortlaufende Spalte Korrektur gelesen werden. Dabei
   kommt die Spalte ganz links zuerst, anschlie&szlig;end der Text der
   n&auml;chsten und so weiter. Sie brauchen den Umbruch der Spalten
   nicht extra zu kennzeichnen; verbinden Sie einfach die Spalten.
</p>
<p>Lesen Sie dazu auch die Abschnitte <a href="#bk_index">Sachregister
   und Schlagwortverzeichnisse</a> sowie <a href="#tables">Tabellen</a>.
</p>


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

<h3><a name="page_hf">Kopf- und Fu&szlig;zeilen</a></h3>
<p>L&ouml;schen Sie Kopf- und Fu&szlig;zeilen, aber
   <em>nicht</em> <a href="#footnotes">Fu&szlig;noten</a>.
</p>
<p>Kopfzeilen befinden sich normalerweise ganz oben auf der Seite
   in derselben H&ouml;he wie die Seitenzahl. Kopfzeilen k&ouml;nnen
   im ganzen Buch (oft der Buchtitel und der Name des Autors) oder
   f&uuml;r jedes Kapitel (oft die Kapitelnummer) identisch, aber
   auch auf jeder Seite unterschiedlich sein (mit dem Inhalt der
   einzelnen Seite). Entfernen Sie unterschiedslos alle,
   einschlie&szlig;lich der Seitenzahl.
</p>
<!-- END RR -->

<p>Im Gegensatz dazu beginnt die <a href="#chap_head">Kapitel&uuml;berschrift</a>
   weiter unten auf der Seite und hat keine Seitenzahl auf derselben
   Zeile (siehe Beispiel unten).
</p>
<br>
<table width="100%" align="center" border="1" cellpadding="4"
 cellspacing="0" summary="Kopf- und Fu&szlig;zeilen">
  <tbody>
    <tr><th align="left" bgcolor="cornsilk">Beispielvorlage:</th></tr>
    <tr align="left">
      <td width="100%" valign="top">
      <img src="foot.png" alt="" width="500" height="850"><br>
      </td>
    </tr>
    <tr><th align="left" bgcolor="cornsilk">Richtig korrigierter Text:</th></tr>
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


<h3><a name="illust">Abbildungen</a></h3>
<p>Lesen Sie den Bildbeschreibungstext so Korrektur, wie er gedruckt
   ist, und behalten Sie den Zeilenumbruch bei. Wenn die Bildunterschrift
   mitten in einem Absatz steht, verwenden Sie Leerzeilen, um sie vom
   Rest zu trennen. Gibt es <b>keine</b> Bildunterschrift, dann
   &uuml;berlassen Sie die Markierung den Formatierern.
</p>
<p>Die meisten Seiten mit Abbildung, aber ohne Text sind schon mit
   <tt>[Blank Page]</tt> gekennzeichnet. Belassen Sie diese
   Markierung so, wie sie ist.
</p>
<!-- END RR -->

<table width="100%" align="center" border="1" cellpadding="4"
 cellspacing="0" summary="Illustration">
  <tbody>
    <tr>
      <th align="left" bgcolor="cornsilk">
      Beispielvorlage:
      </th>
    </tr>
    <tr align="left">
      <td width="100%" valign="top"> <img src="illust.png" alt=""
          width="500" height="525"> <br>
      </td>
    </tr>
    <tr>
      <th align="left" bgcolor="cornsilk">Richtig korrigierter Text:</th>
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
     <th align="left" bgcolor="cornsilk">Beispielvorlage (Abbildung mitten im Absatz):</th>
   </tr>
   <tr align="left">
     <td width="100%" valign="top"> <img src="illust2.png" alt=""
         width="500" height="514"> <br>
     </td>
   </tr>
   <tr>
     <th align="left" bgcolor="cornsilk">Richtig korrigierter Text:</th>
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

<h3><a name="footnotes">Fu&szlig;noten/Endnoten</a></h3>
<p><b>Fu&szlig;noten stehen nicht im Flie&szlig;text</b>, d.&nbsp;h.
   der Text der Fu&szlig;note steht am unteren Seitenrand und ein
   Fu&szlig;notenzeichen an der Stelle, auf die der Text sich bezieht.
</p>
<p>Die Zahl, der Buchstabe oder ein anderes Zeichen, das den Ort der
   Fu&szlig;note kennzeichnet, wird mit eckigen Klammern (<tt>[</tt>
   und <tt>]</tt>) umgeben und unmittelbar rechts neben das Wort<tt>[1]</tt>
   oder das Satzzeichen gesetzt,<tt>[2]</tt> auf das sich die
   Fu&szlig;note bezieht (wie im Text dargestellt und in den beiden
   Beispielen in diesem Satz).
</p>
<p>Sind Fu&szlig;noten mit einer Reihe von Sonderzeichen markiert (*,
   &dagger;, &Dagger;, &sect; usw.), dann ersetzen wir sie alle durch
   <tt>[*]</tt> sowohl im Text als auch durch <tt>*</tt> bei der
   Fu&szlig;note selbst.
</p>
<p>Lesen Sie den Text der Fu&szlig;note Korrektur, wie er gedruckt
   ist, einschlie&szlig;lich der Zeilenumbr&uuml;che. Auch die Position
   am unteren Seitenrand bleibt unver&auml;ndert.  Achten Sie darauf,
   dass Sie vor der Fu&szlig;note das gleiche Fu&szlig;notenzeichen
   verwenden wie im Text.
</p>
<p>Setzen Sie jede Fu&szlig;note auf eine eigene Zeile in der
   Reihenfolge ihres Auftretens im Text. Wenn es mehr als eine
   gibt, trennen Sie die einzelnen Fu&szlig;noten durch Leerzeilen.
</p>
<!-- END RR -->

<p>Im Text und der Beispielvorlage zu <a href="#page_hf">Kopf- und
   Fu&szlig;zeilen</a> finden Sie eine Beispiel-Fu&szlig;note.
</p>
<p>Wenn eine Fu&szlig;note bzw. Endnote im Text markiert ist, aber
   auf dieser Seite nicht erscheint, behalten Sie das
   Fu&szlig;noten-/Endnotenzeichen bei und setzen Sie es in eckige
   Klammern (<tt>[</tt> und <tt>]</tt>).  In wissenschaftlichen
   und technischen B&uuml;chern werden Fu&szlig;noten oft am Ende
   des Kapitels zusammengefasst &ndash; siehe &sbquo;Endnoten&lsquo;
   weiter unten.
</p>

<table width="100%" border="1"  cellpadding="4" cellspacing="0" align="center" summary="Footnote Examples">
  <tbody>
    <tr>
      <th valign="top" align="left" bgcolor="cornsilk">Ausgangstext:</th>
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
      <th valign="top" align="left" bgcolor="cornsilk">Richtig korrigierter Text:</th>
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

<p>In manchen B&uuml;chern werden Fu&szlig;noten durch eine horizontale
   Linie vom Text getrennt. Wir behalten diese nicht bei; also lassen
   Sie nur eine Leerzeile zwischen Haupttext und Fu&szlig;noten (siehe
   Beispiel oben).
</p>
<p><b>Endnoten</b> sind einfach Fu&szlig;noten, die am Ende eines
   Kapitels oder am Ende des Buches zusammengefasst sind, statt unten
   auf jeder Seite. Sie werden in der gleichen Weise Korrektur gelesen
   wie Fu&szlig;noten. Wo Sie ein Endnotenzeichen im Text finden, umgeben
   Sie es mit eckigen Klammern (<tt>[</tt> und <tt>]</tt>).  Wenn Sie
   eine Seite mit Endnoten Korrektur lesen, setzen Sie Leerzeilen
   zwischen die Endnoten, damit klar ist, wo jede beginnt und endet.
</p>
<!-- Need an example of Endnotes, maybe? Good idea!-->

<p><b>Fu&szlig;noten in <a href="#poetry">Gedichten</a></b> sollten
   genauso behandelt werden wie andere Fu&szlig;noten.<br />
 <br />

<b>Fu&szlig;noten in <a href="#tables">Tabellen</a></b> sollten
   dort bleiben, wo sie im Original stehen.
</p>

<table width="100%" align="center" border="1" cellpadding="4" cellspacing="0" summary="Footnotes">
  <tbody>
    <tr><th align="left" bgcolor="cornsilk">Ausgangstext &ndash; Gedicht mit Fu&szlig;noten:</th></tr>
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
    <tr><th align="left" bgcolor="cornsilk">Richtig korrigierter Text:</th></tr>
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

<h3><a name="poetry">Gedichte/Epigramme</a></h3>
<p>F&uuml;gen Sie eine Leerzeile vor dem Gedicht oder Epigramm ein
   und eine danach, damit die Formatierer Anfang und Ende deutlich
   sehen k&ouml;nnen.
</p>
<p>Lassen Sie jede Zeile linksb&uuml;ndig ausgerichtet und behalten
   Sie die Zeilenumbr&uuml;che bei. Versuchen Sie nicht, den Text zu
   zentrieren oder einzur&uuml;cken. Das machen die Formatierer.
   F&uuml;gen Sie aber Leerzeilen zwischen den Strophen ein.
</p>
<p><b>Fu&szlig;noten</b> in Gedichten sollten beim Korrekturlesen
   wie regul&auml;re Fu&szlig;noten behandelt werden. Zu den Einzelheiten
   siehe <a href="#footnotes">Fu&szlig;noten</a>.
</p>
<p><b>Zeilennummern</b> in Gedichten sollten beibehalten werden.
   Trennen Sie diese durch mehrere Leerzeichen vom Haupttext.
   Einzelheiten dazu unter <a href="#line_no">Zeilennummern</a>.
</p>
<p>Lesen Sie in jedem Falle die <a href="#comments">Projektkommentare</a>
   des Textes, den Sie bearbeiten.
</p>
<!-- END RR -->

<br>
<!-- Need an example that shows overly long lines of poetry, rather than relative indentation -->

<table width="100%" align="center" border="1"  cellpadding="4"
      cellspacing="0" summary="Poetry Example">
 <tbody>
   <tr><th align="left" bgcolor="cornsilk">Beispielvorlage:</th></tr>
   <tr align="left">
     <th width="100%" valign="top"> <img src="poetry.png" alt=""
         width="500" height="508"> <br>
     </th>
   </tr>
   <tr><th align="left" bgcolor="cornsilk">Richtig korrigierter Text:</th></tr>
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

<h3><a name="para_side">Randnoten (Marginalien)</a></h3>
<p>Manche B&uuml;cher haben kurze Zusammenfassungen einzelner
   Abschnitte auf dem Seitenrand neben dem Text stehen. Diese
   werden Randnoten (Marginalien) genannt. Lesen Sie den Text
   der Marginalie Korrektur, wie er gedruckt ist, und behalten
   Sie auch den Zeilenumbruch bei. Lassen Sie eine Leerzeile vor
   und nach der Marginalie, damit sie vom &uuml;brigen Text unterschieden
   werden kann. Es kann passieren, dass die OCR-Software Marginalien
   irgendwo auf der Seite platziert oder sie sogar mit dem &uuml;brigen
   Text vermischt. Trennen Sie den Text so, dass der Text der Marginalie
   zusammensteht, aber k&uuml;mmern Sie sich nicht um ihre Position auf
   der Seite. Die Formatierer werden sie an die richtige Stelle setzen.
</p>
<!-- END RR -->

  <table width="100%" align="center" border="1" cellpadding="4"
       cellspacing="0" summary="Marginalien"> <col width="128*">
  <tbody>
    <tr valign="top">
      <th align="left" bgcolor="cornsilk">Beispielvorlage:</th>
    </tr>
    <tr valign="top">
      <td width="100%" align="left"><img src="side.png" alt=""
          width="550" height="800"><br>
      </td>
    </tr>
    <tr valign="top">
      <th align="left" bgcolor="cornsilk">Richtig korrigierter Text:</th>
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

<h3><a name="tables">Tabellen</a></h3>
<p>Die Aufgabe eines Korrekturlesers ist es sicherzustellen, dass
   die gesamte Information in einer Tabelle richtig ist. Details der
   Formatierung werden sp&auml;ter durchgef&uuml;hrt. Lassen Sie zwischen
   einzelnen Tabelleneintr&auml;gen in einer Zeile genug Zwischenraum, um
   deutlich zu zeigen, wo jeder Eintrag beginnt und endet.  Behalten Sie
   die Zeilenumbr&uuml;che bei.
</p>
<p><b>Fu&szlig;noten</b> in Tabellen sollten dort bleiben, wo sie im
   Original stehen. Zu den Einzelheiten siehe <a href="#footnotes">Fu&szlig;noten</a>.
</p>
<!-- END RR -->
<table width="100%" align="center" border="1" cellpadding="4"
 cellspacing="0" summary="Table Example 1">
  <tbody>
    <tr><th align="left" bgcolor="cornsilk">Beispielvorlage:</th></tr>
    <tr align="left">
      <td width="100%" valign="top"> <img src="table1.png" alt="" width="500" height="142"><br>
      </td>
    </tr>
    <tr><th align="left" bgcolor="cornsilk">Richtig korrigierter Text:</th></tr>
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
    <tr><th align="left" bgcolor="cornsilk">Beispielvorlage:</th></tr>
    <tr align="left">
      <td width="100%" valign="top"> <img src="table2.png" alt="" width="500" height="304"><br>
      </td>
    </tr>
    <tr><th align="left" bgcolor="cornsilk">Richtig korrigierter Text:</th></tr>
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

<table width="100%" align="center" border="1" cellpadding="4"
 cellspacing="0" summary="Title Page Example">
  <tbody>
    <tr>
      <th align="left" bgcolor="cornsilk">
      Beispielvorlage:
      </th>
    </tr>
    <tr align="left">
      <td width="100%" valign="top"><img src="title.png" width="500"
          height="520" alt="title page image"><br>
      </td>
    </tr>
    <tr>
      <th align="left" bgcolor="cornsilk">Richtig korrigierter Text:</th>
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

<h3><a name="toc">Inhaltsverzeichnisse</a></h3>
<p>Lesen Sie das Inhaltsverzeichnis so Korrektur, wie es im Buch
   abgedruckt ist, einerlei ob in Gro&szlig;buchstaben, in Gro&szlig;-
   und Kleinschreibung o.&nbsp;&auml;. Die Seitenzahlen sollen erhalten
   bleiben.
</p>
<p>Ignorieren Sie Punkte oder Sternchen (F&uuml;hrungszeichen), die zum
   Ausrichten der Seitenzahlen verwendet werden. Diese werden sp&auml;ter
   entfernt.
</p>
<!-- END RR -->

<table width="100%" align="center" border="1" cellpadding="4"
 cellspacing="0" summary="TOC">
  <tbody>
    <tr>
      <th align="left" bgcolor="cornsilk">
      Beispielvorlage:
      </th>
    </tr>
    <tr align="left">
      <td width="100%" valign="top">
      <p><img src="tablec.png" alt="" width="500" height="650"></p>
      </td>
    </tr>
    <tr>
      <th align="left" bgcolor="cornsilk">Richtig korrigierter Text:</th>
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
      </tt>
</td></tr></table>
      </td>
    </tr>
  </tbody>
</table>


<h3><a name="bk_index">Sachregister und Schlagwortverzeichnisse</a></h3>
<p>Bitte erhalten Sie die Seitenzahlen auf Registerseiten. Sie
   brauchen die Zahlen nicht so auszurichten, wie sie auf der
   Vorlage aussehen. Stellen Sie lediglich sicher, dass Zahlen
   und Zeichensetzung mit der gescannten Vorlage &uuml;bereinstimmen,
   und behalten Sie die Zeilenumbr&uuml;che bei.
</p>
<p>Die besondere Formatierung der Register erfolgt sp&auml;ter im
   Prozess. Aufgabe des Korrekturlesers ist es, sicherzustellen,
   dass Text und Zahlen insgesamt korrekt sind.
</p>
<!-- END RR -->


<h3><a name="play_n">Dramen: Rollennamen/Regieanweisungen</a></h3>
<ul compact>
 <li>Innerhalb der Dialoge behandeln Sie einen Wechsel des Sprechers
     als einen neuen Absatz, mit jeweils einer Leerzeile davor.</li>
 <li>Regieanweisungen werden so formatiert, wie sie im Originaltext sind.<br>
     Steht die Anweisung in einer eigenen Zeile, dann lesen Sie sie so
     Korrektur; steht sie am Ende einer Dialogzeile, dann lassen Sie sie dort.<br>
     Regieanweisungen beginnen h&auml;ufig mit einer &ouml;ffnenden
     Klammer und lassen die schlie&szlig;ende Klammer weg. Diese Konvention
     wird beibehalten; schlie&szlig;en Sie die Klammern nicht.</li>
 <li>Manchmal, vor allem in metrischen Texten, wird ein Wort einer zu
     langen Zeile getrennt und oberhalb oder unterhalb hinter eine
     Klammer ( platziert, statt in eine eigene Zeile. Behandeln Sie
     das wie eine normale <a href="#eol_hyphen">Trennung am Zeilenende</a>.<br>
     Ein Beispiel finden Sie <a href="#play4">hier</a>.</li>
</ul>
<p>Bitte pr&uuml;fen Sie die <a href="#comments">Projektkommentare</a>,
   da der Projektmanager eine andere Formatierung festlegen kann.
</p>
<!-- END RR -->

<table width="100%" align="center" border="1" cellpadding="4"
 cellspacing="0" summary="Play Example 1">
  <tbody>
    <tr>
      <th align="left" bgcolor="cornsilk">Beispielvorlage:</th>
    </tr>
    <tr align="left">
      <td width="100%" valign="top"><img src="play1.png" width="500"
          height="430" alt="title page image"><br>
      </td>
    </tr>
    <tr>
      <th align="left" bgcolor="cornsilk">Richtig korrigierter Text:</th>
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
<br> <a name="play4"><!-- Example --></a>
<table width="100%" align="center" border="1" cellpadding="4"
 cellspacing="0" summary="Play Example 4">
  <tbody>
    <tr>
      <th align="left" bgcolor="cornsilk">Beispielvorlage:</th>
    </tr>
    <tr align="left">
      <td width="100%" valign="top"><img src="play4.png" width="502"
          height="98" alt="Plays image"><br>
      </td>
    </tr>
    <tr>
      <th align="left" bgcolor="cornsilk">Richtig korrigierter Text:</th>
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


<h3><a name="anything">Sonstige Besonderheiten und Behandlung von Unklarheiten</a></h3>
<p>Vielleicht sto&szlig;en Sie w&auml;hrend des Korrekturlesens auf
   etwas, das nicht von diesen Richtlinien abgedeckt wird, das Ihrer
   Meinung nach einer besonderen Behandlung bedarf oder von dem Sie
   nicht sicher wissen, wie es behandelt werden soll. Stellen Sie
   Ihre Frage unter Angabe der png-Nummer (Seitenzahl) ins Projektforum
   (ein Link zum projektspezifischen Forum-Thread steht in den
   <a href="#comments">Projektkommentaren</a>). Setzen Sie zus&auml;tzlich
   eine Anmerkung in den Korrektur gelesenen Text, die das Problem
   erl&auml;utert. Durch Ihre Anmerkung werden nachfolgende Korrekturleser,
   Formatierer und Nachbearbeiter auf das Problem hingewiesen.
</p>
<p>Beginnen Sie die Anmerkung mit einer eckigen Klammer und zwei Sternchen
   <tt>[**</tt> und schlie&szlig;en Sie sie wiederum mit einer eckigen
   Klammer <tt>]</tt>. Dadurch ist sie deutlich vom Text des Autors
   getrennt und signalisiert dem Nachbearbeiter innezuhalten. Er wird
   diesen Teil des Textes und die dazugeh&ouml;rige Vorlage sorgf&auml;ltig
   untersuchen, um etwaige Probleme zu l&ouml;sen. Alle Kommentare von
   Lesern aus fr&uuml;heren Runden <b>m&uuml;ssen</b> an Ort und Stelle
   bleiben. Sie k&ouml;nnen Ihre Zustimmung oder Ablehnung hinzuf&uuml;gen,
   aber selbst wenn Sie die Antwort wissen, d&uuml;rfen Sie den Kommentar
   auf keinen Fall entfernen. Wenn Sie eine Quelle f&uuml;r die L&ouml;sung
   des Problems gefunden haben, so geben Sie sie bitte an, damit sich der
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

<table width="100%" border="0" cellspacing="0" summary="Allgemeine Probleme">
  <tbody>
    <tr>
      <td bgcolor="silver">&nbsp;</td>
    </tr>
  </tbody>
</table>

<h2>Allgemeine Probleme</h2>

<h3><a name="OCR_1lI">OCR-Fehler: 1-l-I</a></h3>
<p>OCR-Software hat gew&ouml;hnlich Schwierigkeiten, die Ziffer
   &bdquo;1&ldquo; (eins), den Kleinbuchstaben &bdquo;l&ldquo; (el)
   und den Gro&szlig;buchstaben &bdquo;I&ldquo; zu unterscheiden.
   Das gilt vor allem f&uuml;r B&uuml;cher in ungew&ouml;hnlichen
   Schrifttypen oder schlechtem Zustand.
</p>
<p>Achten Sie besonders auf diese Zeichen. Lesen Sie den Kontext
   des Satzes, um zu entscheiden, welches das richtige Zeichen ist.
   Aber geben Sie Acht &ndash; h&auml;ufig &bdquo;korrigiert&ldquo;
   Ihr Verstand die Zeichen automatisch beim Lesen.
</p>
<p>Die Unterscheidung dieser Zeichen ist einfacher, wenn Sie eine
   nicht-proportionale Schriftart verwenden, wie etwa
   <a href="font_sample.php">DPCustomMono</a> oder Courier.
</p>

<h3><a name="OCR_0O">OCR-Fehler: 0-O</a></h3>
<p>OCR-Software hat gew&ouml;hnlich Schwierigkeiten, die Ziffer
   &bdquo;0&ldquo; (Null) vom Gro&szlig;buchstaben &bdquo;O&ldquo;
   (Oh) zu unterscheiden. Das gilt vor allem f&uuml;r B&uuml;cher
   in ungew&ouml;hnlichen Schrifttypen oder schlechtem Zustand.
</p>
<p>Achten Sie besonders auf diese Zeichen. Lesen Sie den Kontext
   des Satzes, um zu entscheiden, welches das richtige Zeichen ist.
   Aber geben Sie Acht &ndash; h&auml;ufig &bdquo;korrigiert&ldquo;
   Ihr Verstand die Zeichen automatisch beim Lesen.
</p>
<p>Die Unterscheidung dieser Zeichen ist einfacher, wenn Sie eine
   nicht-proportionale Schriftart verwenden, wie etwa
   <a href="font_sample.php">DPCustomMono</a> oder Courier.
</p>


<h3><a name="OCR_hyphen">OCR-Fehler: Binde- und Gedankenstriche</a></h3>
<p>OCR-Software hat gew&ouml;hnlich Schwierigkeiten, die verschiedenen
   Gedanken- und Bindestriche zu unterscheiden. Lesen Sie diese
   sorgf&auml;ltig Korrektur &ndash; f&uuml;r Gedankenstriche verwendet
   die OCR-Software oft nur einen Bindestrich anstatt zwei. N&auml;heres
   zu den Regeln finden Sie unter <a href="#eol_hyphen">Trennzeichen</a>
   und <a href="#em-dashes">Gedankenstriche</a>.
</p>
<p>Die Unterscheidung dieser Zeichen ist einfacher, wenn Sie eine
   nicht-proportionale Schriftart verwenden, wie etwa
   <a href="font_sample.php">DPCustomMono</a> oder Courier.
</p>


<h3><a name="OCR_scanno">OCR-Fehler: &bdquo;Scannos&ldquo;</a></h3>
<p>Ein anderes h&auml;ufiges Problem der OCR ist die falsche
   Erkennung von Zeichen, sogenannte OCR-Fehler (englisch
   &bdquo;scannos&ldquo;, nachgebildet &bdquo;typos&ldquo; f&uuml;r
   Druck- und Satzfehler). Die Fehlerkennung kann ein Wort bilden, das</p>
<ul compact>
   <li>auf den ersten Blick richtig erscheint, in Wahrheit aber
       falsch geschrieben ist. <br /> Solche W&ouml;rter kann das
       Programm &sbquo;WordCheck&lsquo; in der Korrekturlese-Oberfl&auml;che
       entdecken.</li>
   <li>zu einem anderen, richtigen Wort wird, das nicht dem in der
       Vorlage entspricht.<br /> Solche W&ouml;rter sind besonders
       unangenehm, weil sie nur erkannt werden, wenn man die Vorlage
       Satz f&uuml;r Satz mit dem Text vergleicht.</li>
</ul>
<p>Das verbreitetste Beispiel f&uuml;r die zweite Art ist
   wahrscheinlich das Wort &bdquo;and&ldquo;, eingelesen als
   &bdquo;arid&ldquo;. Weitere Beispiele sind: &bdquo;eve&ldquo;
   f&uuml;r &bdquo;eye&ldquo;, &bdquo;Torn&ldquo; f&uuml;r
   &bdquo;Tom&ldquo; und &bdquo;train&ldquo; f&uuml;r &bdquo;tram&ldquo;.
   Dieser Typ Fehler ist schwer zu entdecken, und wir haben einen
   speziellen Ausdruck daf&uuml;r: &bdquo;Stealth Scannos&ldquo;
   (getarnte OCR-Fehler). Beispiele von Stealth Scannos sammeln wir in
   <a href="<? echo $Stealth_Scannos_URL; ?>">diesem Thread</a>.
</p>
<p>Das Erkennen von Scannos ist einfacher, wenn Sie eine
   nicht-proportionale Schriftart verwenden, wie etwa
   <a href="font_sample.php">DPCustomMono</a> oder Courier.
</p>
<!-- END RR -->
<!-- More to be added.... -->

<h3><a name="hand_notes">Handgeschriebene Notizen in B&uuml;chern</a></h3>
<p>&Uuml;bernehmen Sie keine handschriftlichen Erg&auml;nzungen oder
   Randnotizen in den Text (es sei denn, es wurde verblichener
   gedruckter Text &uuml;berschrieben, um ihn besser lesbar zu
   machen). Lassen Sie auch handgeschriebene Kommentare von Lesern
   usw. au&szlig;en vor.
</p>


<h3><a name="bad_image">Schlechte Vorlagen</a></h3>
<p>Ist eine Vorlage schlecht (sie wird nicht geladen, ist abgeschnitten
   oder unlesbar), so machen Sie einen Eintrag im
   <a href="#forums">Projektforum</a>. Klicken Sie nicht auf
   &sbquo;Return Page to Round&lsquo; (Seite zur&uuml;ckgeben). Wenn
   Sie das tun, bekommt sie der n&auml;chste Korrekturleser. Klicken
   Sie stattdessen auf &sbquo;Report Bad Page&lsquo; (unbrauchbare
   Seite melden), damit die Seite in Quarant&auml;ne kommt.
</p>
<p>Beachten Sie, dass einige Bilddateien von Seiten recht gro&szlig;
   sind und Ihr Browser Schwierigkeiten bei der Darstellung haben
   k&ouml;nnte, besonders wenn Sie mehrere Fenster ge&ouml;ffnet
   haben oder einen &auml;lteren Computer benutzen. Bevor Sie die
   Seite als unbrauchbar melden, probieren Sie erst Folgendes aus:
   Klicken Sie auf &sbquo;Image&lsquo; (Vorlage) am unteren Seitenrand,
   um nur die Vorlage in einem neuen Fenster zu laden. Erscheint dann
   eine leserliche Vorlage, dann liegt das Problem vermutlich an Ihrem
   Browser oder System.
</p>
<p>Manchmal ist zwar die Vorlage in Ordnung, aber die ersten ein oder
   zwei Zeilen fehlen im OCR-Text. Tippen Sie in diesem Fall bitte die
   fehlende(n) Zeile(n) ein. Wenn fast alle Zeilen im Korrekturfenster
   fehlen, tippen Sie entweder die ganze Seite ein (wenn Sie dazu bereit
   sind) oder klicken Sie auf &sbquo;Return Page to Round&lsquo;. Dann
   wird die Seite erneut an jemand anders ausgegeben. Gibt es mehrere
   Seiten dieser Art, sollten Sie im <a href="#forums">Projektforum</a>
   den Projektmanager darauf aufmerksam machen.
</p>

<h3><a name="bad_text">Falsche Vorlage zum Text</a></h3>
<p>Wenn eine falsche Vorlage zum Text im Korrekturfenster angezeigt
   wird, machen Sie einen Eintrag im <a href="#forums">Projektforum</a>.
   Klicken Sie nicht auf &sbquo;Return Page to Round&lsquo; (Seite
   zur&uuml;ckgeben); wenn Sie das tun, bekommt sie der n&auml;chste
   Korrekturleser. Klicken Sie stattdessen auf &sbquo;Report Bad
   Page&lsquo; (unbrauchbare Seite melden), damit die Seite in
   Quarant&auml;ne kommt.
</p>

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

<h3><a name="p_errors">Satz- und Rechtschreibfehler: &bdquo;Typos&ldquo;</a></h3>
<p>Korrigieren Sie alle Fehler, die die OCR-Software fehlgelesen hat
   (&bdquo;Scannos&ldquo;) &ndash; aber verbessern Sie nicht, was
   Ihnen wie Rechtschreib- oder Druckfehler vorkommt, die auf der
   gescannten Vorlage auftreten. In vielen &auml;lteren Texten werden
   W&ouml;rter abweichend vom modernen Gebrauch buchstabiert. Wir
   behalten diese &auml;lteren Schreibweisen bei, einschlie&szlig;lich
   aller Buchstaben mit Akzenten.
</p>
<p>Wenn Sie sich nicht sicher sind, setzen Sie eine Anmerkung in den
   Txet <tt>[**typo ?]</tt> und fragen Sie im Projektforum nach.
   &Auml;ndern Sie etwas, so erkl&auml;ren Sie mit einer Anmerkung,
   was Sie ge&auml;ndert haben: <tt>[**typo fixed, changed from
   "Txet" to "Text"]</tt>. Verwenden Sie hierbei
   die beiden Sternchen <tt>**</tt>, damit der Nachbearbeiter auf
   diese Stelle aufmerksam wird.
</p>

<h3><a name="f_errors">Tatsachenfehler im Text</a></h3>
<p>Im Allgemeinen gilt: Korrigieren Sie keine Tatsachenfehler im
   Buch des Autors. Viele der B&uuml;cher, die hier bearbeitet werden,
   enthalten Aussagen, die wir nicht mehr als zutreffend empfinden.
   Lassen Sie diese so, wie sie der Autor geschrieben hat.
</p>
<p>Eine m&ouml;gliche Ausnahme ist, wenn in technischen oder
   wissenschaftlichen B&uuml;chern eine bekannte Formel oder Gleichung
   falsch wiedergegeben wird, insbesondere wenn dieselbe Formel auf
   anderen Seiten des Buches richtig steht. Benachrichtigen Sie entweder
   den Projektmanager im <a href="#forums">Projektforum</a>, oder
   f&uuml;gen Sie <tt>[**note sic Ihre Anmerkung]</tt> im Text ein.
</p>

<h3><a name="uncertain">Unklare Punkte</a></h3>
<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; [... wird erg&auml;nzt ...]
</p>

<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="silver" summary="Links">
<tr>
  <td width="10">&nbsp;</td>
  <td width="100%" align="center"><font face="verdana, helvetica, sans-serif" size="1">
  Return to: <a href="..">Distributed Proofreaders home page</a>,
  &nbsp;&nbsp;&nbsp;
  <a href="faq_central.php">DP FAQ Central page</a>,
  &nbsp;&nbsp;&nbsp;
  <a href="<? echo $PG_home_url; ?>">Project Gutenberg home page</a>.</font>
  </td>
</tr>
</table>

<?
theme('','footer');
?>
