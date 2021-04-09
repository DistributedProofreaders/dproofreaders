<?php
$relPath='../../pinc/';
include_once($relPath.'base.inc');
include_once($relPath.'faq.inc');
include_once($relPath.'pg.inc');
include_once($relPath.'theme.inc');

maybe_redirect_to_external_faq("de");

$theme_args["css_data"] = "p.backtotop {text-align:right; font-size:75%;margin-right:-5%;}";

output_header('Formatierungsrichtlinien', NO_STATSBAR, $theme_args);

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

<h1 align="center"><a name="top">Formatierungsrichtlinien</a></h1>

<h3 align="center">Version 2.0 vom 7. Juni 2009 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
  <a href="../dochist.php"><font size="-1">(&Uuml;berarbeitungsverlauf)</font></a></h3>

<p>Formatierungsrichtlinien <a href="../formatting_guidelines.php">auf Englisch</a> /
      Formatting Guidelines <a href="../formatting_guidelines.php">in English</a> <br>
    Formatierungsrichtlinien <a href="../fr/formatting_guidelines.php">auf Franz&ouml;sisch</a> /
      Directives de Formatage <a href="../fr/formatting_guidelines.php">en fran&ccedil;ais</a><br>
    Formatierungsrichtlinien <a href="../pt/formatting_guidelines.php">auf Portugiesisch</a> /
      Regras de Formata&ccedil;&atilde;o <a href="../pt/formatting_guidelines.php">em Portugu&ecirc;s</a><br>
    Formatierungsrichtlinien <a href="../nl/formatting_guidelines.php">auf Niederl&auml;ndisch</a> /
      Formatteer-Richtlijnen <a href="../nl/formatting_guidelines.php">in het Nederlands</a><br>
    Formatierungsrichtlinien <a href="../it/formatting_guidelines.php">auf Italienisch</a> /
      Regole di Formattazione <a href="../it/formatting_guidelines.php">in Italiano</a><br>
</p>

<p>Hier geht es zum <a href="../../quiz/start.php?show_only=FQ">Formatting Quiz</a> (nur Englisch).
</p>

<table border="0" cellspacing="0" width="100%" summary="Inhaltsverzeichnis">
  <tbody>
  <tr>
    <td width="1" bgcolor="silver">&nbsp;</td>
    <td bgcolor="silver" align="center"><h2 style="margin-top:0; margin-bottom:0;">Inhaltsverzeichnis</h2></td>
  </tr>
  <tr>
    <td width="1" bgcolor="silver">&nbsp;</td>
    <td bgcolor="white" align="left">
    <ul>
      <li><a href="#prime">Oberstes Gebot</a></li>
      <li><a href="#summary">Kurzfassung dieser Richtlinien</a></li>
      <li><a href="#about">&Uuml;ber dieses Dokument</a></li>
      <li><a href="#separate_pg">Jede Seite ist eine gesonderte Einheit</a></li>
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
        <li style="margin-top:.25em;"><font size="+1">Formatieren auf der Zeichenebene:</font>
        <ul>
          <li><a href="#inline">Platzierung der Format-Markierungen im laufenden Text</a></li>
          <li><a href="#italics">Kursiver Text</a></li>
          <li><a href="#bold">Fett gedruckter Text</a></li>
          <li><a href="#underl">Unterstrichener Text</a></li>
          <li><a href="#spaced"><span style="letter-spacing: .2em;">Gesperrter</span> Text</a></li>
          <li><a href="#font_ch">&Auml;nderungen der Schriftart</a></li>
          <li><a href="#small_caps">W&ouml;rter in <span style="font-variant: small-caps">Kapit&auml;lchen</span></a></li>
          <li><a href="#word_caps">W&ouml;rter in Gro&szlig;buchstaben</a></li>
          <li><a href="#font_sz">Unterschiedliche Schriftgr&ouml;&szlig;en</a></li>
          <li><a href="#extra_sp">&Uuml;berfl&uuml;ssige Leerzeichen bzw. Tabulatoren
              zwischen W&ouml;rtern</a></li>
          <li><a href="#supers">Hochgestellte Zeichen</a></li>
          <li><a href="#subscr">Tiefgestellte Zeichen</a></li>
          <li><a href="#page_ref">Seitenverweise (&bdquo;siehe S. 123&ldquo;)</a></li>
        </ul></li>
        <li style="margin-top:.25em;"><font size="+1">Formatieren auf der Absatzebene:</font>
        <ul>
          <li><a href="#chap_head">Kapitel&uuml;berschriften</a></li>
          <li><a href="#sect_head">Abschnitts&uuml;berschriften</a></li>
          <li><a href="#maj_div">Weitere Hauptbestandteile eines Buches</a></li>
          <li><a href="#para_space">Absatzabst&auml;nde und -einr&uuml;ckungen</a></li>
          <li><a href="#extra_s">Gedankenwechsel: "thought break" (Zus&auml;tzliche(r) Abstand/Dekoration zwischen Abs&auml;tzen)</a></li>
          <li><a href="#illust">Abbildungen</a></li>
          <li><a href="#footnotes">Fu&szlig;noten/Endnoten</a></li>
          <li><a href="#para_side">Randnoten (Marginalien)</a></li>
          <li><a href="#outofline">Platzierung von Formatierung au&szlig;erhalb des laufenden Textes</a></li>
          <li><a href="#block_qt">Blockzitate</a></li>
          <li><a href="#lists">Aufz&auml;hlungen</a></li>
          <li><a href="#tables">Tabellen</a></li>
          <li><a href="#poetry">Gedichte/Epigramme</a></li>
          <li><a href="#line_no">Zeilennummern</a></li>
          <li><a href="#letter">Briefe/Korrespondenz</a></li>
          <li><a href="#r_align">Rechtsb&uuml;ndiger Text</a></li>
        </ul></li>
        <li style="margin-top:.25em;"><font size="+1">Formatieren auf der Seitenebene:</font>
        <ul>
          <li><a href="#blank_pg">Leere Seiten</a></li>
          <li><a href="#title_pg">Titelblatt und hintere Umschlagseite</a></li>
          <li><a href="#toc">Inhaltsverzeichnisse</a></li>
          <li><a href="#bk_index">Sachregister und Schlagwortverzeichnisse</a></li>
          <li><a href="#play_n">Dramen: Rollennamen/Regieanweisungen</a></li>
        </ul></li>
        <li><a href="#anything">Sonstige Besonderheiten und Behandlung von Unklarheiten</a></li>
        <li><a href="#prev_notes">Anmerkungen vorhergehender Korrekturleser/Kommentare</a></li>
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
          <li><a href="#bad_image">Schlechte Vorlagen</a></li>
          <li><a href="#bad_text">Falsche Vorlage zum Text</a></li>
          <li><a href="#round1">Fehler der vorherigen Korrekturleser bzw. Formatierer</a></li>
          <li><a href="#p_errors">Satz- und Rechtschreibfehler: &bdquo;Typos&ldquo;</a></li>
          <li><a href="#f_errors">Tatsachenfehler im Text</a></li>
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
<p>Das fertige E-Book, das der Leser vielleicht in Jahrzehnten erst zu Gesicht
   bekommt, soll die Absicht des Autors genau vermitteln. Hat der Autor W&ouml;rter
   ungew&ouml;hnlich buchstabiert, so belassen wir sie in dieser Schreibweise.
   Hat der Autor ungeheuerlich rassistische oder voreingenommene Aussagen gemacht,
   so lassen wir sie so stehen. Hat der Autor jedes dritte Wort kursiv bzw. fett
   geschrieben oder &uuml;berall Fu&szlig;noten angeh&auml;ngt, markieren wir sie
   als kursiv bzw. fett oder als Fu&szlig;noten. (Siehe <a href="#p_errors">Satz-
   und Rechtschreibfehler</a> zur richtigen Behandlung von offensichtlichen Druckfehlern.)
</p>
<p>Allerdings &auml;ndern wir weniger bedeutsame typographische Konventionen,
   die den Sinn des Textes nicht beeinflussen. Zum Beispiel verschieben wir Bildunterschriften
   wenn erforderlich so, dass sie nur zwischen Abs&auml;tzen erscheinen
   (<a href="#illust">Abbildungen</a>). Solche &Auml;nderungen erm&ouml;glichen die Erstellung einer
   <em>konsistent formatierten</em> Version des Buches. Unsere Regeln sind darauf
   ausgelegt, dieses Ziel zu erreichen. Bitte lesen Sie die &uuml;brigen Richtlinien
   sorgf&auml;ltig unter diesem Gesichtspunkt. Diese Richtlinien sind nur f&uuml;r die
   Formatierung. Die Korrekturleser haben f&uuml;r die &Uuml;bereinstimmung des
   Inhalts gesorgt, jetzt sorgen Sie als Formatierer f&uuml;r die &Uuml;bereinstimmung
   des Aussehens.
</p>
<p>Um es dem nachfolgenden Formatierer und Nachbearbeiter leichter zu machen,
   behalten wir dar&uuml;ber hinaus Zeilenumbr&uuml;che
   bei. Dadurch lassen sich die Zeilen im Text leichter mit den Zeilen in der Vorlage
   vergleichen.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Zur&uuml;ck nach oben</a></p>


<h3><a name="summary">Kurzfassung dieser Richtlinien</a></h3>
<p>Die Kurzfassung dieser Richtlinien ist ein &uuml;bersichtliches, zweiseitiges
   druckerfreundliches PDF-Dokument, das die wichtigsten Punkte enth&auml;lt und
   Beispiele f&uuml;rs Formatieren gibt. Sie ist derzeit nur in englischer Sprache
   verf&uuml;gbar (<a href="../formatting_summary.pdf">Formatting Summary</a>), aber
   eine deutsche Fassung ist in Arbeit. Falls Sie neu hier sind, empfehlen wir
   Ihnen, das PDF-Dokument auszudrucken und w&auml;hrend des Formatierens griffbereit
   zu halten.
</p>
<p>M&ouml;glicherweise m&uuml;ssen Sie erst eine Software zum Anzeigen von PDF-Dateien
   herunterladen und installieren. Eine kostenlose Version von Adobe&reg; finden
   Sie <a href="http://www.adobe.com/products/acrobat/readstep2.html">hier</a>.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Zur&uuml;ck nach oben</a></p>


<h3><a name="about">&Uuml;ber dieses Dokument</a></h3>
<p>Dieses Dokument enth&auml;lt Erkl&auml;rungen zu den Formatierregeln. Jedes
   Buch wird von vielen Formatierern verteilt bearbeitet, von denen jeder andere
   Seiten formatiert. Das Einhalten der Regeln hilft allen, die Formatierung konsistent,
   d.&nbsp;h. <em>auf die gleiche Art</em> durchzuf&uuml;hren. Das macht es dem
   Nachbearbeiter leichter, die einzelnen Seiten zu einem E-Book zusammenzuf&uuml;gen.
</p>
<p><i>Dieses Dokument ist nicht als allgemeines Regelwerk f&uuml;r Redaktionsarbeit
   oder Typographie gedacht.</i>
</p>
<p>Wir haben in dieses Dokument alle Punkte aufgenommen, die neue Benutzer beim
   Korrekturlesen gefragt haben. Es gibt eine getrennte
   <a href="proofreading_guidelines.php">Korrekturlese-Richtlinie</a>.
   Wenn Sie auf eine Situation sto&szlig;en, zu der Sie keinen Bezug in diesen
   Richtlinien finden, wurde sie wahrscheinlich in den Korrekturleserunden bearbeitet
   und deshalb hier nicht erw&auml;hnt. Wenn Sie unsicher sind, fragen Sie in der
   <a href="#forums">Projektdiskussion</a>.
</p>
<p>Falls Sie etwas vermissen, etwas Ihrer Ansicht
   nach anders beschrieben werden sollte oder unklar f&uuml;r Sie ist, so lassen
   Sie es uns bitte wissen.
<?php if($site_url == "http://www.pgdp.net" || $site_url == "http://www.pgdp.org") { ?>
   Wenn Sie in diesen Richtlinien auf einen Ausdruck sto&szlig;en, mit dem Sie nicht
   vertraut sind, dann schauen Sie in den
   <a href="http://www.pgdp.net/wiki/DP_Jargon">"wiki jargon guide" (nur Englisch)</a>.
<?php } ?>
   Diese Richtlinien werden laufend &uuml;berarbeitet. Helfen Sie uns dabei,
   indem Sie uns Ihre Verbesserungsvorschl&auml;ge in <a href="<?php echo $Guideline_discussion_URL; ?>">
   diesem Thread</a> mitteilen.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Zur&uuml;ck nach oben</a></p>


<h3><a name="separate_pg">Jede Seite ist eine getrennte Einheit</a></h3>
<p>Weil jedes Projekt auf viele Formatierer verteilt wird, die alle an verschiedenen
   Seiten arbeiten, gibt es keine Garantie, dass Sie die n&auml;chste Seite des
   Projektes sehen werden. Wenn Sie daran denken, werden Sie sicher alle Markierungen
   auf jeder Seite auch wieder schlie&szlig;en. Das macht es einfacher f&uuml;r den
   Nachbearbeiter, alle Seiten zu einem E-Buch zusammen zu f&uuml;gen.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Zur&uuml;ck nach oben</a></p>


<h3><a name="comments">Projektkommentare</a></h3>
<p>Wenn Sie ein Projekt zum Formatieren ausw&auml;hlen, wird die Projektseite
   geladen. Auf dieser Seite befindet sich ein Abschnitt &sbquo;Project
   Comments&lsquo; (Projektkommentare), der spezielle
   Informationen zu diesem Projekt (Buch) enth&auml;lt. <b>Lesen Sie die Projektkommentare,
   bevor Sie mit dem Formatieren beginnen!</b> Falls der Projektmanager m&ouml;chte,
   dass Sie in seinem Buch etwas anders formatieren als hier vorgeschrieben, so
   steht es dort. Anleitungen in den Projektkommentaren haben <em>Vorrang </em>
   vor den Regeln in diesen Richtlinien, also richten Sie sich danach. Au&szlig;erdem
   kann der Projektmanager dort interessante Informationen &uuml;ber den Autor
   oder das Projekt einstellen.
</p>
<p><em>Bitte lesen Sie auch das Projektforum!</em> Dort kann der Projektmanager
   Fragen zu den projektspezifischen Richtlinien kl&auml;ren. Oft wird es auch
   von Formatierern verwendet, um andere auf wiederkehrende Schwierigkeiten im
   Projekt hinzuweisen und darauf, wie sie am besten angegangen werden. (Siehe
   unten).
</p>
<p>&Uuml;ber den Link &sbquo;Images, Pages Proofread, &amp; Differences&lsquo;
   (Vorlagen, korrigierte Seiten &amp; &Auml;nderungen) auf der Projektseite
   kann man sehen, was andere Freiwillige ge&auml;ndert haben.
   <a href="<?php echo $Using_project_details_URL; ?>">Dieses Forum</a>
   er&ouml;rtert verschiedene Arten, diese Informationen zu benutzen.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Zur&uuml;ck nach oben</a></p>


<h3><a name="forums">Forum/Diskussion eines Projektes</a></h3>
<p>Auf der Projektseite, dem Ausgangspunkt f&uuml;r das Formatieren, gibt es in
   der Zeile &sbquo;Forum&lsquo; den Link &sbquo;Discuss this Project&lsquo; (Projekt
   diskutieren), wenn es bereits eine Diskussion gibt oder &sbquo;Start a discussion
   on this Project&lsquo; (Diskussion zu diesem Projekt beginnen), wenn es noch
   keine gibt. Damit kommen Sie in einen Thread des Diskussionsforums speziell
   f&uuml;r dieses Projekt. Hier k&ouml;nnen Sie Fragen zum Projekt stellen, den
   Projektmanager &uuml;ber Probleme informieren usw. Benutzen Sie diesen Forum-Thread,
   um mit dem Projektmanager und anderen Formatierern zu kommunizieren, die ebenfalls
   an diesem Buch arbeiten.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Zur&uuml;ck nach oben</a></p>


<h3><a name="prev_pg">Fehler auf fr&uuml;heren Seiten beheben</a></h3>
<p>Die <a href="#comments">Projektseite</a> enth&auml;lt
   Links zu den Buchseiten, die Sie zuletzt bearbeitet haben. (Wenn Sie in
   einem Projekt noch keine Seiten formatiert haben, fehlen diese Links.)
</p>
<p>Seiten, die unter &sbquo;DONE&lsquo; (fertig) oder &sbquo;IN PROGRESS&lsquo;
   (in Arbeit) gelistet sind, sind verf&uuml;gbar, um Korrekturen zu machen oder
   das Formatieren abzuschlie&szlig;en. Klicken Sie einfach auf den Link zur Seite.
</p>
<p>Au&szlig;erdem k&ouml;nnen Sie die Links &sbquo;Images, Pages Proofread, &amp;
   Differences&lsquo; (Vorlagen, korrigierte Seiten &amp; &Auml;nderungen) sowie
   &sbquo;Just My Pages&lsquo; (nur meine Seiten) auf der <a href="#comments">Projektseite</a>
   verwenden. Sie finden &sbquo;Edit&lsquo; (Bearbeiten)-Links neben allen Seiten,
   die Sie in dieser Runde bearbeitet haben und die noch &uuml;berarbeitet werden
   k&ouml;nnen.
</p>
<p>Weitere Informationen finden Sie entweder in der
   <a href="../prooffacehelp.php?i_type=0">Hilfe zur
   Standard-Korrekturlese-Oberfl&auml;che</a> (nur Englisch) oder in der
   <a href="../prooffacehelp.php?i_type=1">Hilfe zur Erweiterten
   Korrekturlese-Oberfl&auml;che</a> (nur Englisch), je nachdem, welche
   Benutzeroberfl&auml;che Sie verwenden.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Zur&uuml;ck nach oben</a></p>


<table width="100%" border="0" cellspacing="0" cellpadding="6" summary="Formatieren auf der Zeichenebene">
  <tbody>
    <tr>
      <td bgcolor="silver"><h2 style="margin-bottom: 0; margin-top: 0;">Formatieren auf der Zeichenebene:</h2></td>
    </tr>
  </tbody>
</table>

<h3><a name="inline">Platzierung der Format-Markierungen im laufenden Text</a></h3>
<p>Formatierung im laufenden Text bezieht sich auf Markierungen wie
   <kbd>&lt;i&gt;</kbd>&nbsp;<kbd>&lt;/i&gt;</kbd>, <kbd>&lt;b&gt;</kbd>&nbsp;<kbd>&lt;/b&gt;</kbd>,
   <kbd>&lt;sc&gt;</kbd>&nbsp;<kbd>&lt;/sc&gt;</kbd>, <kbd>&lt;f&gt;</kbd>&nbsp;<kbd>&lt;/f&gt;</kbd>,
   <kbd>&lt;g&gt;</kbd>&nbsp;<kbd>&lt;/g&gt;</kbd>. Platzieren Sie Satzzeichen <b>au&szlig;erhalb</b>
   der Markierungen, au&szlig;er der gesamte Satz oder Abschnitt ist markiert, oder aber die
   Interpunktion selbst ist Teil eines Ausdrucks, Titels oder einer Abk&uuml;rzung,
   die markiert wird. Wenn die Formatierung &uuml;ber mehrere Abs&auml;tze geht, setzen Sie
   die Markierung um jeden Absatz.
</p>
<p>Die Punkte, die ein abgek&uuml;rztes Wort im Titel einer Zeitschrift wie z.&nbsp;B.
   <i>Phil. Trans.</i> kennzeichnen, sind Teil des Titels und deshalb werden sie in die
   Markierungen einbezogen, also: <kbd>&lt;i&gt;Phil. Trans.&lt;/i&gt;</kbd>.
</p>
<p>Viele Schriftschnitte, die in &auml;lteren B&uuml;chern zu finden sind, benutzten
   das gleiche Design f&uuml;r die Zahlen sowohl in regul&auml;rem Text wie auch kursiv
   oder fett. Datumsangaben oder &auml;hnliches formatieren Sie insgesamt als kursiv gedruckt,
   anstatt die W&ouml;rter als kursiv und die Zahlen als nicht-kursiv zu markieren.
</p>
<p>Falls der kursiv gedruckte Text aus einer Reihe/Liste von W&ouml;rtern oder
   Namen besteht, markieren Sie diese jeweils einzeln mit Kursiv-Markierungen.
</p>
<p>Siehe den Abschnitt <a href="#tables">Tabellen</a> zur Behandlung der Markierungen in Tabellen.
</p>
<!-- END RR -->
<p><b>Beispiele</b>:
</p>
<table width="100%" align="center" border="1" cellpadding="4" cellspacing="0" summary="Inline markup examples">
  <tbody>
    <tr>
      <th valign="top" bgcolor="cornsilk">Beispielvorlage:</th>
      <th valign="top" bgcolor="cornsilk">Richtig formatierter Text:</th>
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
<p class="backtotop"><a href="#top">Zur&uuml;ck nach oben</a></p>


<h3><a name="italics">Kursiver Text</a></h3>
<p>Formatieren Sie <i>kursiv</i> gedruckten Text mit einem <kbd>&lt;i&gt;</kbd>
   am Anfang und einem <kbd>&lt;/i&gt;</kbd> am Ende des kursiv gedruckten Textes.
   (Beachten Sie den Schr&auml;gstrich &bdquo;/&ldquo; in der schlie&szlig;enden
   Markierung.)
</p>
<p>Siehe auch <a href="#inline">Platzierung von Format-Markierungen im laufenden Text</a>.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Zur&uuml;ck nach oben</a></p>


<h3><a name="bold">Fett gedruckter Text</a></h3>
<p>Formatieren Sie <b>fett gedruckten Text</b> (Text, der in kr&auml;ftigeren
   Lettern gedruckt ist) mit einem <kbd>&lt;b&gt;</kbd> vor dem fett gedruckten Text
   und einem <kbd>&lt;/b&gt;</kbd> danach. (Beachten Sie den Schr&auml;gstrich
   &bdquo;/&ldquo; in der schlie&szlig;enden Markierung.)
</p>
<p>Siehe auch <a href="#inline">Platzierung von Format-Markierungen im laufenden Text</a>
   und <a href="#chap_head">Kapitel&uuml;berschriften</a>.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Zur&uuml;ck nach oben</a></p>


<h3><a name="underl">Unterstrichener Text</a></h3>
<p>Formatieren Sie <u>unterstrichenen Text</u> wie <a href="#italics">kursiv
   gedruckten</a>, mit <kbd>&lt;i&gt;</kbd> und <kbd>&lt;/i&gt;</kbd>. (Beachten
   Sie den Schr&auml;gstrich &bdquo;/&ldquo; in der schlie&szlig;enden Markierung.)
   Unterstreichungen wurden oft benutzt, um eine Betonung anzuzeigen, wenn der
   Schriftsetzer den Text nicht kursiv setzen konnte, etwa in einem
   maschinengeschriebenen Dokument.
</p>
<p>Siehe auch <a href="#inline">Platzierung von Format-Markierungen im laufenden Text</a>.
</p>
<p>Manche Projektmanager legen in den <a href="#comments">Projektkommentaren</a>
   fest, dass unterstrichener Text mit <kbd>&lt;u&gt;</kbd> und <kbd>&lt;/u&gt;</kbd>
   markiert werden soll.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Zur&uuml;ck nach oben</a></p>


<h3><a name="spaced"><span style="letter-spacing: .2em;">Gesperrter Text</span></a></h3>
<p>Formatieren Sie <span style="letter-spacing: .2em;">gesperrten Text</span> mit
   <kbd>&lt;g&gt;</kbd> vor dem Text und <kbd>&lt;/g&gt;</kbd> danach. (Beachten
   Sie den Schr&auml;gstrich &bdquo;/&ldquo; in der schlie&szlig;enden Markierung.)
   Entfernen Sie &uuml;berfl&uuml;ssige Leerzeichen zwischen den einzelnen
   Buchstaben der W&ouml;rter.
   Diese schriftsetzerische Technik wurde in vielen &auml;lteren, insbesondere
   deutschsprachigen B&uuml;chern benutzt, um einen Teil des Textes zu betonen.
</p>
<p>Siehe auch <a href="#inline">Platzierung von Format-Markierungen im laufenden Text</a>
   und <a href="#chap_head">Kapitel&uuml;berschriften</a>.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Zur&uuml;ck nach oben</a></p>


<h3><a name="font_ch">&Auml;nderungen der Schriftart</a></h3>
<p>Manche Projektmanager fordern, dass
   Sie die &Auml;nderung der Schriftart innerhalb eines Absatzes
   oder einer Zeile mit normalem Text durch <kbd>&lt;f&gt;</kbd> vor der
   &Auml;nderung der Schriftart und <kbd>&lt;/f&gt;</kbd> danach markieren. (Beachten Sie
   den Schr&auml;gstrich &bdquo;/&ldquo; in der schlie&szlig;enden Markierung.)
   Diese Markierung kann f&uuml;r alle besonderen Schriftarten und
   sonstigen Formatierungen benutzt werden, f&uuml;r die es keine
   eigenen Markierungen gibt (wie kursiv und fett).
</p>
<p>M&ouml;gliche Anwendungen dieser Markierung sind insbesondere:
</p>
<ul compact>
  <li>Antiqua-Schrift innerhalb von Frakturschrift</li>
  <li>gebrochene Schrift (Fraktur oder "Old English") innerhalb eines Abschnitts
      einer regul&auml;ren Schriftart</li>
  <li>eine kleinere oder gr&ouml;&szlig;ere Schriftart nur dann, wenn sie sich
      <b>innerhalb</b> eines Absatzes mit einer regul&auml;ren Schriftart befindet
      (ganze Abs&auml;tze in einer anderen Schriftart oder -gr&ouml;&szlig;e: siehe
      Abschnitt <a href="#block_qt">Blockzitate</a>).</li>
  <li>eine gerade Schriftart innerhalb eines kursiv gedruckten Absatzes</li>
</ul>
<p>Die genaue Verwendung der Markierungen in einem Projekt wird &uuml;blicherweise
   in den <a href="#comments">Projektkommentaren</a> erl&auml;utert. Formatierer
   sollten im <a href="#forums">Projektforum</a> nachfragen, wenn eine Kennzeichnung
   erforderlich scheint, aber noch nicht diskutiert wurde.
</p>
<p>Siehe auch <a href="#inline">Platzierung von Format-Markierungen im laufenden Text</a>.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Zur&uuml;ck nach oben</a></p>


<h3><a name="small_caps">W&ouml;rter in Kapit&auml;lchen</a></h3>
<p>Die Formatierung ist verschieden f&uuml;r
   <span style="font-variant:small-caps;">Kapit&auml;lchen in gemischter
   Schreibweise (Gro&szlig;buchstaben und Kapit&auml;lchen)</span> und
   <span style="font-variant: small-caps;">reine kapit&auml;lchen</span>.
</p>
<p>Formatieren Sie W&ouml;rter, die in <span style="font-variant: small-caps;">Kapit&auml;lchen
   in gemischter Schreibweise</span> gedruckt sind, als gemischte Gro&szlig;- und
   Kleinbuchstaben.
   Formatieren Sie W&ouml;rter, die als <span style="font-variant: small-caps;">reine
   kapit&auml;lchen</span> gedruckt sind, als Gro&szlig;buchstaben. In beiden F&auml;llen,
   bei gemischten und reinen Kapit&auml;lchen, umschlie&szlig;en Sie den Text mit den
   Markierungen <kbd>&lt;sc&gt;</kbd> und <kbd>&lt;/sc&gt;</kbd>.
</p>
<p>&Uuml;berschriften (<a href="#chap_head">Kapitel-</a> und
   <a href="#sect_head">Abschnitts&uuml;berschriften</a>, Bildunterschriften usw.) k&ouml;nnen
   aussehen wie <span style="font-variant: small-caps;">reine kapit&auml;lchen</span>,
   aber das ist gew&ouml;hnlich nur die Folge einer &Auml;nderung der
   <a href="#font_sz">Schriftgr&ouml;&szlig;e</a> und sollte nicht als Kapit&auml;lchen
   markiert werden. Das <a href="#chap_head">erste Wort eines Kapitels</a> in Kapit&auml;lchen
   sollte zu Gro&szlig;- und Kleinbuchstaben ohne Markierung ge&auml;ndert werden.
</p>
<p>Siehe auch <a href="#inline">Platzierung von Format-Markierungen im laufenden Text</a>.
</p>
<table width="100%" align="center" border="1" cellpadding="4" cellspacing="0" summary="Small caps examples">
  <tbody>
    <tr>
      <th valign="top" bgcolor="cornsilk">Beispielvorlage:</th>
      <th valign="top" bgcolor="cornsilk">Richtig formatierter Text:</th>
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
<p class="backtotop"><a href="#top">Zur&uuml;ck nach oben</a></p>


<h3><a name="word_caps">Worte in Gro&szlig;buchstaben</a></h3>
<p>Formatieren Sie Worte, die in Gro&szlig;buchstaben gedruck wurden als Gro&szlig;buchstaben.
</p>
<p>Die Ausnahme ist das <a href="#chap_head">erste Wort eines Kapitels</a>:
   in vielen alten B&uuml;chern ist das erste Wort eines Kapitels in Gro&szlig;buchstaben gesetzt;
   das sollte zu Gro&szlig;- und Kleinbuchstaben ge&auml;ndert werden, so dass "ONCE upon a time,"
   zu "<kbd>Once upon a time,</kbd>" wird.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Zur&uuml;ck nach oben</a></p>


<h3><a name="font_sz">Unterschiedliche Schriftgr&ouml;&szlig;en</a></h3>
<p>Normalerweise markieren wir &Auml;nderungen in der Schriftgr&ouml;&szlig;e
   nicht. Ausnahmen davon sind, wenn die Schriftgr&ouml;&szlig;e sich &auml;ndert, um
   <a href="#block_qt">Blockzitate</a> anzuzeigen, oder wenn sie sich innerhalb
   eines Absatzes oder einer Textzeile &auml;ndert (siehe
   <a href="#font_ch">&Auml;nderungen der Schriftart</a>).
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Zur&uuml;ck nach oben</a></p>


<h3><a name="extra_sp">&Uuml;berfl&uuml;ssige Leerzeichen bzw. Tabulatoren zwischen
    W&ouml;rtern</a></h3>
<p>Die Texterkennung verursacht oft &uuml;berfl&uuml;ssige Leerzeichen und Tabulatoren
   im Text. Sie brauchen sich nicht die M&uuml;he zu machen, diese Leerzeichen
   zu entfernen &ndash; das kann automatisch in der Nachbearbeitung erfolgen.
   &Uuml;berfl&uuml;ssige Leerzeichen um Satzzeichen, Gedankenstriche,
   Anf&uuml;hrungszeichen herum usw. <b>m&uuml;ssen</b> jedoch entfernt werden, wenn
   sie das Symbol vom Wort trennen.Innerhalb der <kbd>/* */</kbd> Markierung sollten Sie
   unbedingt alle extra Leerzeichen entfernen, weil sie sp&auml;ter nicht automatisch
   entfernt werden.
</p>
<p>Schlie&szlig;lich sollten Sie alle Tabulatoren entfernen, die Sie im Text finden.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Zur&uuml;ck nach oben</a></p>


<!-- Remove this section after some transition, since it's now all handled in proofreading -->
<h3><a name="supers">Hochgestellte Zeichen</a></h3>
<p>In &auml;lteren B&uuml;chern wurden W&ouml;rter h&auml;ufig mit verk&uuml;rzten
   Endungen dargestellt, die dann hochgestellt wurden. Formatieren Sie diese, indem
   Sie einen einzelnen Zirkumflex <kbd>^</kbd> davor setzen. Wenn der hochgestellte
   Text mehr als ein Zeichen hat, umgeben Sie ihn zus&auml;tzlich mit geschweiften
   Klammern <kbd>{</kbd> und <kbd>}</kbd>. Zum Beispiel:
</p>
<table width="100%" align="center" border="1" cellpadding="4" cellspacing="0" summary="Superscripts example">
  <tbody>
    <tr><th align="left" bgcolor="cornsilk">Beispielvorlage:</th></tr>
    <tr>
      <td valign="top">Gen<sup>rl</sup> Washington defeated L<sup>d</sup> Cornwall's army.</td>
    </tr>
    <tr><th align="left" bgcolor="cornsilk">Richtig formatierter Text:</th></tr>
    <tr>
      <td valign="top"><kbd>Gen^{rl} Washington defeated L^d Cornwall's army.</kbd></td>
    </tr>
  </tbody>
</table>
<p>Manche Projektmanager legen in den <a href="#comments">Projektkommentaren</a>
   fest, dass hochgestellte Zeichen anders markiert werden sollen.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Zur&uuml;ck nach oben</a></p>


<!-- Remove this section after some transition, since it's now all handled in proofreading -->
<h3><a name="subscr">Tiefgestellte Zeichen</a></h3>
<p>Tiefgestellte Zeichen kommen oft in wissenschaftlichen Texten vor, in anderen
   Werken sind sie nicht &uuml;blich. Formatieren Sie tiefgestellte Zeichen durch
   Einf&uuml;gen eines Unterstriches <kbd>_</kbd> und Umschlie&szlig;en der Zeichen
   mit geschweiften Klammern <kbd>{</kbd> und <kbd>}</kbd>. Zum Beispiel:
</p>
<table width="100%" align="center" border="1" cellpadding="4" cellspacing="0" summary="Subscripts example">
  <tbody>
    <tr><th align="left" bgcolor="cornsilk">Beispielvorlage:</th></tr>
    <tr>
      <td valign="top">H<sub>2</sub>O.</td>
    </tr>
    <tr><th align="left" bgcolor="cornsilk">Richtig formatierter Text:</th></tr>
    <tr>
      <td valign="top"><kbd>H_{2}O.</kbd></td>
    </tr>
  </tbody>
</table>
<!-- END RR -->
<p class="backtotop"><a href="#top">Zur&uuml;ck nach oben</a></p>


<h3><a name="page_ref">Seitenverweise (&bdquo;siehe S. 123&ldquo;)</a></h3>
<p>Formatieren Sie Seitenverweise innerhalb des Textes wie <kbd>(siehe S. 123)</kbd>
   so, wie sie in der Vorlage erscheinen.
</p>
<p>Pr&uuml;fen Sie die <a href="#comments">Projektkommentare</a>, um zu sehen,
   ob der Projektmanager besondere Vorgaben f&uuml;r Seitenverweise hat.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Zur&uuml;ck nach oben</a></p>


<table width="100%" border="0" cellspacing="0" cellpadding="6" summary="Formatieren auf der Absatzebene">
  <tbody>
    <tr>
      <td bgcolor="silver"><h2 style="margin-bottom: 0; margin-top: 0;">Formatieren auf der Absatzebene:</h2></td>
    </tr>
  </tbody>
</table>


<h3><a name="chap_head">Kapitel&uuml;berschriften</a></h3>
<p>Formatieren Sie die Kapitel&uuml;berschriften so, wie sie im Text stehen.
   Eine Kapitel&uuml;berschrift steht normalerweise unterhalb der <a href="#page_hf">Kopfzeile</a>
   und hat keine Seitenzahl in derselben Zeile. Kapitel&uuml;berschriften sind
   h&auml;ufig in Gro&szlig;buchstaben gedruckt. Ist das der Fall, so behalten
   Sie die Gro&szlig;buchstaben bei. Markieren Sie <a href="#italics">Kursivschrift</a>
   und <b>gemischte</b> <a href="#small_caps">Kapit&auml;lchen</a>, die im Original vorkommen.
</p>
<p>F&uuml;gen Sie vier Leerzeilen vor &bdquo;KAPITEL XXX&ldquo; ein, auch wenn
   das Kapitel auf einer neuen Seite beginnt. Es gibt keine &bdquo;Seiten&ldquo;
   in einem E-Book, daher sind die Leerzeilen n&ouml;tig. Danach lassen Sie eine
   Leerzeile zwischen jedem zus&auml;tzlichen Bestandteil der Kapitel&uuml;berschrift,
   wie z.&nbsp;B. Kapitelbeschreibung, er&ouml;ffnendes Zitat usw., und lassen
   Sie zwei Leerzeilen vor dem Anfang des Textes des Kapitels stehen.
</p>
<p>In alten B&uuml;chern sind das erste oder die zwei ersten Worte eines Kapitels
   h&auml;ufig in Gro&szlig;buchstaben oder Kapit&auml;lchen gesetzt; &auml;ndern Sie
   dies zu Gro&szlig;- und Kleinbuchstaben (nur der ersten Buchstabe gro&szlig).
</p>
<p>Kapitel&uuml;berschriften k&ouml;nnen fett oder gesperrt aussehen, aber das ist oft nur
   das Ergebnis einer &Auml;nderung der Schriftart oder -gr&ouml;&szlig;e und <b>sollte
   nicht markiert werden</b>. Die zus&auml;tzlichen Leerzeilen trennen die &Uuml;berschrift
   ab, also markieren Sie den Schriftartwechsel nicht. Siehe das erste Beispiel hierunter.
</p>
<!-- END RR -->

<table width="100%" align="center" border="1" cellpadding="4" cellspacing="0" summary="Chapter heading example">
 <tbody>
    <tr><th align="left" bgcolor="cornsilk">Beispielvorlage:</th></tr>
    <tr align="left">
      <td width="100%" valign="top"> <img src="../chap1.png" alt="" width="500" height="725"><br>
      </td>
    </tr>
    <tr><th align="left" bgcolor="cornsilk">Richtig formatierter Text:</th></tr>
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
    <tr><th align="left" bgcolor="cornsilk">Beispielvorlage:</th></tr>
    <tr align="left">
      <td width="100%" valign="top"><img src="../foot.png" alt="" width="500" height="860"><br>
      </td>
    </tr>
    <tr><th align="left" bgcolor="cornsilk">Richtig formatierter Text:</th></tr>
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
<p class="backtotop"><a href="#top">Zur&uuml;ck nach oben</a></p>


<h3><a name="sect_head">Abschnitts&uuml;berschriften</a></h3>
<p>Einige B&uuml;cher haben Abschnitte innerhalb der Kapitel. Formatieren Sie diese
   &Uuml;berschriften so, wie sie im Text erscheinen. Lassen Sie zwei Leerzeilen
   vor der &Uuml;berschrift und eine danach, es sei denn, der Projektmanager verlangt
   etwas anderes. Wenn Sie nicht sicher sind, ob eine &Uuml;berschrift ein Kapitel
   oder einen Abschnitt anzeigt, stellen Sie Ihre Frage unter Angabe der png-Nummer
   (Seitenzahl) ins Projektforum.
</p>
<p>Markieren Sie <a href="#italics">Kursivschrift</a> und <b>gemischte</b>
   <a href="#small_caps">Kapit&auml;lchen</a>, die im Original vorkommen.
   Abschnitts&uuml;berschriften k&ouml;nnen fett oder gesperrt aussehen, aber das ist
   oft nur das Ergebnis einer &Auml;nderung der Schriftart oder- gr&ouml;&szlig;e und
   <b>sollte nicht markiert werden</b>. Die zus&auml;tzlichen Leerzeilen trennen die
   &Uuml;berschrift ab, also markieren Sie den Schriftartwechsel nicht.
</p>
<!-- END RR -->
<table width="100%" align="center" border="1" cellpadding="4" cellspacing="0" summary="Section Heading example">
  <tbody>
    <tr>
      <th align="left" bgcolor="cornsilk">Beispielvorlage:</th>
    </tr>
    <tr align="left">
      <td width="100%" valign="top"> <img src="../section.png" alt="" width="500" height="283"> <br>
      </td>
    </tr>
    <tr>
      <th align="left" bgcolor="cornsilk">Richtig formatierter Text:</th>
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
<p class="backtotop"><a href="#top">Zur&uuml;ck nach oben</a></p>


<h3><a name="maj_div">Weitere Hauptbestandteile eines Buches</a></h3>
<p>Weitere Hauptbestandteile eines Buches wie Vorwort, Inhaltsverzeichnis, Einf&uuml;hrung,
   Prolog, Epilog, Anhang, Quellenverzeichnis, Schlussfolgerung, Glossar, Zusammenfassung,
   Danksagungen, Literaturverzeichnis usw. sollten auf die gleiche Art formatiert
   werden wie <a href="#chap_head">Kapitel&uuml;berschriften,</a> <i>d.&nbsp;h.</i> vier Leerzeilen vor
   der &Uuml;berschrift und zwei Leerzeilen vor dem Beginn des Textes.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Zur&uuml;ck nach oben</a></p>


<!-- Remove this section after some transition, since it's now all handled in proofreading -->
<h3><a name="para_space">Absatzabst&auml;nde und -einr&uuml;ckungen</a></h3>
<p>F&uuml;gen Sie eine Leerzeile vor dem Beginn eines Absatzes ein, auch wenn
   der Absatz am Anfang der Seite beginnt. Sie sollten den Absatzanfang nicht
   einr&uuml;cken, aber wenn er bereits einger&uuml;ckt ist,
   so bem&uuml;hen Sie sich nicht, die Leerzeichen zu entfernen &ndash; das kann
   automatisch in der Nachbearbeitung erledigt werden.
</p>
<p>Ein Beispiel finden Sie im Abschnitt <a href="#chap_head">Kapitel&uuml;berschriften</a>.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Zur&uuml;ck nach oben</a></p>


<h3><a name="extra_s">Gedankenwechsel: "thought break" (Zus&auml;tzliche(r) Abstand/Dekoration zwischen Abs&auml;tzen)</a></h3>
<p>In der Vorlage beginnen die meisten Abs&auml;tze direkt in der Zeile nach dem Ende des
   vorhergehenden Absatzes. Manchmal sind zwei Abs&auml;tze getrennt, um einen
   &bdquo;Gedankenwechsel&ldquo; (thought break, tb) anzuzeigen. Ein Gedankenwechsel
   kann die Form einer Reihe von Sternchen, Strichen oder anderen Zeichen haben, einer
   einfachen oder blumig verzierten waagerechten Linie, einer einfachen Verzierung
   oder auch nur einer oder zwei zus&auml;tzlicher Leerzeilen.
</p>
<p>Ein Gedankenwechsel kann einen Szenen- oder Themenwechsel, einen Sprung in
   der Zeit oder Spannung anzeigen. Das ist vom Autor gewollt, also behalten wir
   es bei, indem wir eine Leerzeile, <kbd>&lt;tb&gt;</kbd> und eine weitere Leerzeile
   einf&uuml;gen.
</p>
<p>Von Druckern wurden manchmal dekorative Linien verwendet, um das Ende von Kapiteln
   anzuzeigen. Da wir bereits <a href="#chap_head">Kapitel&uuml;berschriften</a>
   markieren, braucht die tb-Markierung daf&uuml;r nicht eingef&uuml;gt zu werden.
</p>
<p>Pr&uuml;fen Sie die Projektkommentare, weil der Projektmanager verlangen kann, dass
   zus&auml;tzliche Informationen in der tb-Markierung erhalten bleiben, wie <kbd>&lt;tb stars&gt;</kbd>
   f&uuml;r eine Reihe von Sternchen.
</p>
<!-- END RR -->
<table width="100%" align="center" border="1" cellpadding="4" cellspacing="0" summary="Thought Break example">
  <tbody>
    <tr><th align="left" bgcolor="cornsilk">Beispielvorlage:</th></tr>
    <tr align="left">
      <td width="100%" valign="top"> <img src="../tbreak.png" alt="" width="500" height="249"> <br>
      </td>
    </tr>
    <tr><th align="left" bgcolor="cornsilk">Richtig formatierter Text:</th></tr>
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
<p class="backtotop"><a href="#top">Zur&uuml;ck nach oben</a></p>


<h3><a name="illust">Abbildungen</a></h3>
<p>Text zu einer Illustration sollte mit einer Abbildungs-Markierung
   <kbd>[Illustration:&nbsp;</kbd> und <kbd>]</kbd> umschlossen werden, mit der
   Bildunterschrift darin. Formatieren Sie die Bildunterschrift so, wie sie
   gedruckt ist. Behalten Sie dabei Zeilenumbr&uuml;che, kursive Schrift, etc. bei.
   Text, der eine Bildunterschrift sein k&ouml;nnte (oder ein Teil davon), sollte in die
   Markierung einbezogen werden, wie "Siehe Seite 66" oder ein Titel innerhalb der
   Abbildung.
</p>
<p>Falls eine Abbildung keine Bildunterschrift hat, f&uuml;gen Sie die Markierung
   <kbd>[Illustration]</kbd> ein.
</p>
<p>Falls sich die Abbildung in der Mitte oder an der Seite eines Absatzes befindet,
   platzieren Sie die Abbildungs-Markierung vor oder hinter dem Absatz und lassen
   Sie eine Leerzeile, um sie zu trennen. Verbinden Sie den Absatz wieder, indem
   Sie alle dadurch verbliebenen Leerzeilen entfernen.
</p>
<p>Falls es keinen neuen Absatz auf der Seite gibt, kennzeichnen Sie die
   Abbildungs-Markierung mit einem <kbd>*</kbd> wie hier <kbd>*[Illustration:
   <font color="red">(Text der Bildunterschrift)</font>]</kbd>, platzieren
   Sie sie ganz oben auf der Seite und lassen Sie eine Leerzeile dahinter.
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
      <th align="left" bgcolor="cornsilk">Richtig formatierter Text:</th>
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
      <th align="left" bgcolor="cornsilk">Beispielvorlage: (Abbildung in der Mitte eines Absatzes)</th>
    </tr>
    <tr align="left">
      <td width="100%" valign="top"> <img src="../illust2.png" alt="" width="500" height="514"> <br>
      </td>
    </tr>
    <tr>
      <th align="left" bgcolor="cornsilk">Richtig formatierter Text:</th>
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
<p class="backtotop"><a href="#top">Zur&uuml;ck nach oben</a></p>


<h3><a name="footnotes">Fu&szlig;noten/Endnoten</a></h3>
<p>Formatieren Sie Fu&szlig;noten, indem Sie den Text der Fu&szlig;note am unteren
   Seitenrand lassen und an die Bezugsstelle im Text ein Fu&szlig;notenzeichen
   setzen. Das bedeutet:
</p>
<p>1. Die Zahl, der Buchstabe oder ein anderes Zeichen, das den Ort der Fu&szlig;note
   kennzeichnet, wird mit eckigen Klammern (<kbd>[</kbd> und <kbd>]</kbd>) umgeben
   und unmittelbar rechts neben das Wort<kbd>[1]</kbd> oder das Satzzeichen
   gesetzt,<kbd>[2]</kbd> auf das sich die Fu&szlig;note bezieht (wie im Text
   dargestellt und in den beiden Beispielen in diesem Satz).
   Sind Fu&szlig;noten mit einer Reihe von Sonderzeichen markiert (*, &dagger;,
   &Dagger;, &sect; usw.), dann ersetzen wir diese durch  fortlaufende
   Gro&szlig;buchstaben (A, B, C usw.).
</p>
<p>2. Eine Fu&szlig;note sollte mit einer Fu&szlig;noten-Markierung <kbd>[Footnote
   #:&nbsp;</kbd> und <kbd>]</kbd> umschlossen werden, mit dem Text der Fu&szlig;note
   darin und der Zahl oder dem Buchstaben an der Stelle, wo sich das # in der
   Fu&szlig;noten-Markierung befindet. Formatieren Sie den Text der Fu&szlig;note
   so, wie sie gedruckt ist. Behalten Sie dabei Zeilenumbr&uuml;che, kursive
   Schrift, etc. bei. Auch die Position am unteren Seitenrand bleibt unver&auml;ndert.
   Achten Sie darauf, dass Sie vor der Fu&szlig;note das gleiche Fu&szlig;notenzeichen
   verwenden wie im Text. Setzen Sie jede Fu&szlig;note auf eine eigene Zeile in der
   Reihenfolge ihres Auftretens im Text. Wenn es mehr als eine gibt, trennen Sie die
   einzelnen Fu&szlig;noten durch Leerzeilen.
</p>
<!-- END RR -->

<p>Falls eine Fu&szlig;note am Ende der Seite unvollst&auml;ndig ist, lassen Sie sie
   am unteren Seitenrand stehen und setzen Sie ein Sternchen <kbd>*</kbd> ans Ende
   der Fu&szlig;note, so wie hier: <kbd>[Footnote 1: <font color="red">(Text der
   Fu&szlig;note)</font>]*</kbd>. Das <kbd>*</kbd> zeigt an, dass die Fu&szlig;note
   vorzeitig abbricht, und macht den Nachbearbeiter darauf aufmerksam, der sie
   sp&auml;ter mit dem Rest des Fu&szlig;notentextes verbinden wird.
</p>
<p>Falls eine Fu&szlig;note auf einer fr&uuml;heren Seite begann,
   lassen Sie sie am unteren Seitenrand bei den
   anderen Fu&szlig;noten stehen und umschlie&szlig;en Sie sie mit <kbd>*[Footnote:
   <font color="red">(Text der Fu&szlig;note)</font>]</kbd> (ohne jegliches
   Fu&szlig;notenzeichen). Das <kbd>*</kbd> zeigt an, dass die Fu&szlig;note
   fortgesetzt wurde, und macht den Nachbearbeiter darauf aufmerksam,
   der letztlich die Teile der Fu&szlig;note zusammenf&uuml;gt.
</p>
<p>Falls eine fortgesetzte Fu&szlig;note mit einem getrennten Wort endet oder
   beginnt, markieren Sie sowohl die Fu&szlig;note als auch das Wort mit <kbd>*</kbd>,
   also:<br>
   <kbd>[Footnote 1: Diese Fu&szlig;note wird auf der n&auml;chsten Seite fortgesetzt;
   das letzte Wort wird ebenfalls auf der n&auml;ch-*]*</kbd><br>
   f&uuml;r den ersten Teil und<br>
   <kbd>*[Footnote: *sten Seite fortgesetzt.]</kbd>.
</p>
<p>Bitte behalten Sie horizontale Linien, die die Fu&szlig;noten
   vom Haupttext trennen, nicht bei.
</p>
<p><b>Endnoten</b> sind einfach Fu&szlig;noten, die am Ende eines Kapitels oder
   am Ende des Buches zusammengefasst sind, statt unten auf jeder Seite. Sie werden
   in der gleichen Weise formatiert wie Fu&szlig;noten. Wo Sie ein Endnotenzeichen
   im Text finden, umgeben Sie es mit eckigen Klammern (<kbd>[</kbd> und <kbd>]</kbd>).
   Wenn Sie eine Seite mit Endnoten formatieren, umschlie&szlig;en Sie den Text
   jeder Endnote mit <kbd>[Footnote #:&nbsp;<font color="red">(Text der Endnote)</font>]</kbd>,
   mit dem Text der Endnote dazwischen und dem Endnotenzeichen dort, wo sich das
   # befindet. Setzen Sie eine Leerzeile vor jede Endnote, damit sie getrennte Abs&auml;tze
   bleiben, wenn der Text bei der Nachbearbeitung umgebrochen wird.
</p>
<!-- Need an example of Endnotes, maybe? Good idea!-->

<p><b>Fu&szlig;noten in <a href="#tables">Tabellen</a></b> sollten dort bleiben,
   wo sie in der Vorlage stehen.
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
      <th valign="top" align="left" bgcolor="cornsilk">Richtig formatierter Text:</th>
    </tr>
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
    <tr><th align="left" bgcolor="cornsilk">Richtig formatierter Text:</th></tr>
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
<p class="backtotop"><a href="#top">Zur&uuml;ck nach oben</a></p>


<h3><a name="para_side">Randnoten (Marginalien)</a></h3>
<p>Manche B&uuml;cher haben kurze Zusammenfassungen einzelner Abschnitte auf dem
   Seitenrand neben dem Text stehen. Diese werden Randnoten (Marginalien) genannt.
   Platzieren Sie Randnoten &uuml;ber dem Absatz, zu dem sie geh&ouml;ren. Eine
   Randnote sollte mit einer Randnoten-Markierung <kbd>[Sidenote:</kbd> und <kbd>]</kbd>
   umschlossen werden, mit dem Text der Randnote darin. Formatieren Sie den Text
   der Randnote so, wie er gedruckt ist (behandeln Sie dabei Trenn- und
   Gedankenstriche am Zeilenende wie &uuml;blich). Behalten Sie dabei Zeilenumbr&uuml;che,
   kursive Schrift, etc. bei. Lassen Sie eine Leerzeile nach der Randnote, damit
   sie nicht mit dem Absatz vermischt wird, wenn der Text beim Nachbearbeiten
   umgebrochen wird.
</p>
<p>Falls es mehrere Randnoten f&uuml;r einen Absatz gibt, platzieren Sie diese
   der Reihe nach am Anfang des Absatzes. Lassen Sie zwischen den einzelnen Randnoten
   jeweils eine Leerzeile frei.
</p>
<p>Falls der Absatz auf einer vorhergehenden Seite begonnen hat, platzieren Sie
   die Randnote oben auf der Seite und kennzeichnen Sie sie mit <kbd>*</kbd>, damit
   der Nachbearbeiter sehen kann, dass sie auf die vorherige Seite geh&ouml;rt,
   so wie hier: <kbd>*[Sidenote: <font color="red">(Text der Randnote)</font>]</kbd>.
   Der Nachbearbeiter wird sie an die richtige Stelle verschieben.
</p>
<p>Manchmal verlangt ein Projektmanager, dass Sie die Randnoten bei dem Satz
   platzieren, auf den sie sich beziehen, anstatt am Anfang oder Ende des Absatzes.
   In diesem Fall trennen Sie sie nicht mit Leerzeilen ab.
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
      <th align="left" bgcolor="cornsilk">Richtig formatierter Text:</th>
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
<p class="backtotop"><a href="#top">Zur&uuml;ck nach oben</a></p>


<h3><a name="outofline">Platzierung von Formatierung au&szlig;erhalb des laufenden Textes</a></h3>
<p>Formatierung au&szlig;erhalb des laufenden Textes bezieht sich auf die <kbd>/#</kbd>
   <kbd>#/</kbd> und <kbd>/*</kbd> <kbd>*/</kbd> Markierungen.   Die <kbd>/#</kbd> <kbd>#/</kbd>
   "Umbruch" Markierungen bezeichnen Text, der abweichend gedruckt ist, aber in der
   Nachbearbeitung umgebrochen werden kann. Die <kbd>/*</kbd> <kbd>*/</kbd> "Kein Umbruch"
   Markierungen bezeichnen Text, der der sp&auml;ter in der Nachbearbeitung nicht umgebrochen
   werden sollte &mdash; bei dem Zeilenschaltungen, Einz&uuml;ge und Leerzeichen beibehalten
   werden m&uuml;ssen.
</p>
<p>Auf jeder Seite, auf der Sie eine &ouml;ffnende Markierung benutzen, sollten Sie sicher
   stellen, dass Sie auch eine schlie&szlig;ende Markierung verwenden. Nachdem der Text
   in der Nachbearbeitung umgebrochen wurde, wird jede Markierung entfernt mit der
   gesamten Zeile, auf der sie steht. Lassen Sie deshalb eine Leerzeile zwischen dem
   regul&auml;ren Text und der &ouml;ffnenden Markierung und ebenso eine Leerzeile
   zwischen der schlie&szlig;enden Markierung und dem regul&auml;ren Text.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Zur&uuml;ck nach oben</a></p>


<h3><a name="block_qt">Blockzitate</a></h3>
<p>Blockzitate sind Textbl&ouml;cke (&uuml;blicherweise mehrere Zeilen und manchmal
   mehrere Seiten), die die vom umgebenden Text durch breitere R&auml;nder,
   unterschiedliche Einr&uuml;ckung oder andere Mittel abgehoben werden.
   Umschlie&szlig;en Sie Blockzitate mit den Markierungen <kbd>/#</kbd> und <kbd>#/</kbd>.
   Siehe <a href="#outofline">Platzierung von Formatierung au&szlig;erhalb des laufenden Textes</a>
   zu den Einzelheiten f&uuml;r diese Markierung.
</p>
<p>Abgesehen von den Markierungen sollten Blockzitate genauso formatiert werden
   wie jeder andere Text.
</p>
<!-- END RR -->

<table width="100%" align="center" border="1" cellpadding="4" cellspacing="0" summary="Block Quotation example">
  <tbody>
    <tr><th align="left" bgcolor="cornsilk">Beispielvorlage:</th></tr>
    <tr align="left">
      <td width="100%" valign="top"> <img src="../bquote.png" alt="" width="500" height="475"><br>
      </td>
    </tr>
    <tr><th align="left" bgcolor="cornsilk">Richtig formatierter Text:</th></tr>
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
<p class="backtotop"><a href="#top">Zur&uuml;ck nach oben</a></p>


<h3><a name="lists">Aufz&auml;hlungen</a></h3>
<p>Umschlie&szlig;en Sie Listen mit den Markierungen <kbd>/*</kbd> und <kbd>*/</kbd>.
   Siehe <a href="#outofline">Platzierung von Formatierung au&szlig;erhalb des laufenden Textes</a>
   zu den Einzelheiten f&uuml;r diese Markierung.
</p>
<!-- END RR -->

<table width="100%" align="center" border="1" cellpadding="4" cellspacing="0" summary="List example">
  <tbody>
    <tr><th align="left" bgcolor="cornsilk">Beispielvorlage:</th></tr>
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
    <tr><th align="left" bgcolor="cornsilk">Richtig formatierter Text:</th></tr>
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
<p class="backtotop"><a href="#top">Zur&uuml;ck nach oben</a></p>


<h3><a name="tables">Tabellen</a></h3>
<p>Umschlie&szlig;en Sie Tabellen mit den Markierungen <kbd>/*</kbd> und <kbd>*/</kbd>.
   Siehe <a href="#outofline">Platzierung von Formatierung au&szlig;erhalb des laufenden Textes</a>
   zu den Einzelheiten f&uuml;r diese Markierung.
   Formatieren Sie die Tabelle mit Hilfe von Leerzeichen, (<b>nicht mit Tabulatoren</b>),
   damit sie der urspr&uuml;nglichen Tabelle m&ouml;glichst &auml;hnlich sieht.
   Versuchen Sie, zu breite Tabellen zu vermeiden; generell ist unter 75 Zeichen am besten.
</p>
<p>Benutzen Sie f&uuml;r das Formatieren keine Tabulatoren, sondern
   ausschlie&szlig;lich Leerzeichen. Tabulatorspr&uuml;nge werden bei
   unterschiedlichen Computern verschieden angezeigt, und Ihre sorgf&auml;ltige
   Formatierung w&uuml;rde nicht immer in derselben Weise dargestellt.
   Entfernen Sie alle Punkte oder andere Satzzeichen, die zur Ausrichtung der Tabelle
   benutzt wurden.
</p>
<p>Wenn Formatierung im laufenden Text in der Tabelle erforderlich ist, markieren Sie jede
   Zelle gesondert. Wenn Sie den Text ausrichten, denken Sie daran, dass die Formatierung
   im laufenden Text in der Schlussversion anders aussehen wird. Zum Beispiel wird die
   <kbd>&lt;i&gt;</kbd>kursiv-Markierung<kbd>&lt;/i&gt;</kbd> zu <kbd>_</kbd>Unterstrichen<kbd>_</kbd>
   und ein gro&szlig;er Teil der anderen Formatierung wird &auml;hnlich behandelt.
   <kbd>&lt;sc&gt;</kbd>Kapit&auml;lchen-Markierungen<kbd>&lt;/sc&gt;</kbd> hingegen verschwinden
   ganz.
</p>
<p>Oft ist es schwierig, Tabellen als reinen ASCII-Text zu formatieren. Wenn Sie
   eine nicht-proportionale Schriftart wie DPCustomMono oder Courier verwenden,
   ist es leichter. Ziel ist es, die Bedeutung zu erhalten und eine lesbare Tabelle
   in einem E-Book zu erstellen. Manchmal ist es n&ouml;tig, das urspr&uuml;ngliche
   Format der Tabelle auf der gedruckten Seite aufzugeben. Pr&uuml;fen Sie
   <a href="#comments">Projektkommentare</a> und -forum, ob sich andere Freiwillige
   auf ein bestimmtes Format verst&auml;ndigt haben. Falls dort nichts steht, finden
   Sie vielleicht n&uuml;tzliche Hinweise im Forum
   <a href="<?php echo $Gallery_of_Table_Layouts_URL; ?>">Gallery of Table Layouts</a>.
</p>
<p><b>Fu&szlig;noten</b> in Tabellen sollen dort bleiben, wo sie in der Vorlage stehen. Zu
   den Einzelheiten siehe <a href="#footnotes">Fu&szlig;noten</a>.
</p>
<!-- END RR -->
<table width="100%" align="center" border="1" cellpadding="4" cellspacing="0" summary="Table Example">
  <tbody>
    <tr><th align="left" bgcolor="cornsilk">Beispielvorlage:</th></tr>
    <tr align="left">
      <td width="100%" valign="top"> <img src="../table2.png" alt="" width="500" height="304"><br>
      </td>
    </tr>
    <tr><th align="left" bgcolor="cornsilk">Richtig formatierter Text:</th></tr>
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
    <tr><th align="left" bgcolor="cornsilk">Beispielvorlage:</th></tr>
    <tr align="left">
      <td width="100%" valign="top"> <img src="../table3.png" alt="" width="480" height="231"><br>
      </td>
    </tr>
    <tr><th align="left" bgcolor="cornsilk">Richtig formatierter Text:</th></tr>
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
<p class="backtotop"><a href="#top">Zur&uuml;ck nach oben</a></p>


<h3><a name="poetry">Gedichte/Epigramme</a></h3>
<p>Markieren Sie Gedichte oder Epigramme <kbd>/*</kbd> und <kbd>*/</kbd> so, dass
   Zeilenumbr&uuml;che und Leerzeichen erhalten bleiben.
   Siehe <a href="#outofline">Platzierung von Formatierung au&szlig;erhalb des laufenden Textes</a>
   zu den Einzelheiten f&uuml;r diese Markierung.
</p>
<p>Behalten Sie die relative Einr&uuml;ckung der Zeilen eines Gedichtes oder Epigramms
   bei. R&uuml;cken Sie die Zeilen dazu um zwei, vier, sechs (oder mehr) Leerzeichen
   ein, damit sie so aussehen wie auf der Vorlage.
   Falls das Gedicht auf der gedruckten Seite zentriert ist, versuchen Sie nicht,
   die Zeilen des Gedichtes beim Formatieren zu zentrieren. Verschieben Sie die
   Zeilen an den linken Rand und behalten sie die relative Einr&uuml;ckung der
   Zeilen bei.
</p>
<p>Wenn eine Verszeile zu lang f&uuml;r die gedruckte Seite ist, steht in vielen
   Texten das Ende in der n&auml;chsten Zeile mit einer gro&szlig;en Einr&uuml;ckung
   davor. Diese fortgesetzten Zeilen sollten wieder mit der Zeile dar&uuml;ber
   zusammengef&uuml;gt werden. Fortgesetzte Zeilen&nbsp;beginnen &uuml;blicherweise
   mit einem Kleinbuchstaben. Sie treten wahllos auf und unterscheiden sich von
   normalen Einr&uuml;ckungen, die entsprechend dem Metrum des Gedichts
   regelm&auml;&szlig;ig auftreten.
</p>
<p>Wenn eine Reihe von Sternchen in einem Gedicht erscheint, behandeln Sie das
   als einen <a href="#extra_s">Gedankenwechsel/thought break</a>.
</p>
<p><a href="#line_no">Zeilennummern</a> in Gedichten sollten beibehalten werden.
</p>
<p>Lesen Sie in jedem Falle die <a href="#comments">Projektkommentare</a> des
   Textes, den Sie formatieren. F&uuml;r Gedichtb&auml;nde gibt es oft besondere
   Anweisungen vom Projektmanager. H&auml;ufig brauchen Sie bei einem Buch, das
   &uuml;berwiegend oder ausschlie&szlig;lich Gedichte enth&auml;lt, nicht allen
   diesen Formatierungsrichtlinien zu folgen.
</p>
<!-- END RR -->

<table width="100%" align="center" border="1" cellpadding="4" cellspacing="0" summary="Poetry Example">
  <tbody>
    <tr><th align="left" bgcolor="cornsilk">Beispielvorlage:</th></tr>
    <tr align="left">
      <td width="100%" valign="top"><img src="../poetry.png" alt="" width="500" height="508"> <br>
      </td>
    </tr>
    <tr><th align="left" bgcolor="cornsilk">Richtig formatierter Text:</th></tr>
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
<p class="backtotop"><a href="#top">Zur&uuml;ck nach oben</a></p>


<h3><a name="line_no">Zeilennummern</a></h3>
<p>Zeilennummern sind in Lyrik-B&auml;nden &uuml;blich und erscheinen in der
   N&auml;he des Randes in jeder f&uuml;nften oder zehnten Zeile.
   Behalten Sie Zeilennummern bei und platzieren Sie sie wenigstens sechs
   Leerstellen rechts vom rechten Ende der Zeile, auch wenn sie im Original
   auf der linken Seite der Lyrik/des Textes stehen. Da Lyrik f&uuml;r die
   E-Buch-Version nicht umgebrochen wird, sind die Zeilennummern n&uuml;tzlich
   f&uuml;r Leser.
</p>
<!-- END RR -->
<!-- We need an example image and text for this. -->
<p class="backtotop"><a href="#top">Zur&uuml;ck nach oben</a></p>


<h3><a name="letter">Briefe/Korrespondenz</a></h3>
<p>Formatieren Sie Briefe und Korrespondenz wie <a href="#para_space">Abs&auml;tze</a>.
   F&uuml;gen Sie eine Leerzeile vor dem Anfang des Briefes ein. Sie brauchen keine
   Einr&uuml;ckungen nachzubilden.
</p>
<p>Umschlie&szlig;en Sie aufeinander folgende Kopf- oder Fu&szlig;zeilen (wie
   z.&nbsp;B. Adressen, Datumsangaben, Anreden oder Unterschriften) mit den
   Markierungen <kbd>/*</kbd> und <kbd>*/</kbd>.
   Siehe <a href="#outofline">Platzierung von Formatierung au&szlig;erhalb des laufenden Textes</a>
   zu den Einzelheiten f&uuml;r diese Markierung.
</p>
<p>R&uuml;cken Sie die Kopf- und Fu&szlig;zeilen nicht ein, auch wenn sie in der
   Vorlage einger&uuml;ckt oder rechtsb&uuml;ndig sind &ndash; richten Sie sie einfach
   linksb&uuml;ndig aus. Der Nachbearbeiter wird sie entsprechend formatieren.
</p>
<p>Wenn die Korrespondenz anders gedruckt ist als der Haupttext, siehe <a href="#block_qt">Blockzitate</a>.
</p>
<!-- END RR -->

<table width="100%" align="center" border="1" cellpadding="4" cellspacing="0" summary="Letter Example">
  <tbody>
    <tr><th align="left" bgcolor="cornsilk">Beispielvorlage:</th></tr>
    <tr align="left">
      <td width="100%" valign="top"><img src="../letter.png" alt="" width="500" height="217"> <br>
      </td>
    </tr>
    <tr><th align="left" bgcolor="cornsilk">Richtig formatierter Text:</th></tr>
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
    <tr><th align="left" bgcolor="cornsilk">Beispielvorlage:</th></tr>
    <tr align="left">
      <td width="100%" valign="top"> <img src="../letter2.png" alt="" width="500" height="271"> <br>
      </td>
    </tr>
    <tr><th align="left" bgcolor="cornsilk">Richtig formatierter Text:</th></tr>
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
<p class="backtotop"><a href="#top">Zur&uuml;ck nach oben</a></p>


<h3><a name="r_align">Rechtsb&uuml;ndiger Text</a></h3>
<p>Umgeben Sie Zeilen mit rechtsb&uuml;ndigem Text mit <kbd>/*</kbd> und <kbd>*/</kbd>
   Markierung. Siehe <a href="#outofline">Platzierung von Formatierung au&szlig;erhalb
   des laufenden Textes</a> zu den Einzelheiten f&uuml;r diese Markierung und den Abschnitt
   <a href="#letter">Briefe/Korrespondenz</a> f&uuml;r Beispiele.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Zur&uuml;ck nach oben</a></p>


<table width="100%" border="0" cellspacing="0" cellpadding="6" summary="Formatieren auf der Seitenebene">
  <tbody>
    <tr>
      <td bgcolor="silver"><h2 style="margin-bottom: 0; margin-top: 0;">Formatieren auf der Seitenebene:</h2></td>
    </tr>
  </tbody>
</table>


<h3><a name="blank_pg">Leere Seiten</a></h3>
<p>Formatieren Sie diese als <kbd>[Blank Page]</kbd>, wenn sowohl Text als auch
   Vorlage leer sind.
</p>
<p>Wenn im Formatierfenster Text erscheint, aber eine leere Seite als Vorlage,
   oder wenn eine Buchseite mit Text angezeigt wird, aber kein Text im Formatierfenster,
   dann halten Sie sich an die Anweisungen in den Abschnitten <a href="#bad_image">Schlechte
   Vorlagen</a> und <a href="#bad_text">Falsche Vorlage zum Text</a>.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Zur&uuml;ck nach oben</a></p>


<h3><a name="title_pg">Titelblatt und hintere Umschlagseite</a></h3>
<p>Formatieren Sie den gesamten Text so, wie er auf der Seite gedruckt ist, einerlei
   ob nur Gro&szlig;buchstaben, Gro&szlig;- und Kleinschreibung o.&nbsp;&auml;.,
   einschlie&szlig;lich Publikationsjahr und Copyright-Angaben.
</p>
<p>In &auml;lteren B&uuml;chern ist der erste Buchstabe oft eine gro&szlig;e,
   verzierte Grafik &ndash; formatieren Sie ihn einfach als den dargestellten Buchstaben.
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
      <th align="left" bgcolor="cornsilk">Richtig formatierter Text:</th>
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
<p class="backtotop"><a href="#top">Zur&uuml;ck nach oben</a></p>


<h3><a name="toc">Inhaltsverzeichnis</a></h3>
<p>Formatieren Sie das Inhaltsverzeichnis so, wie es im Buch abgedruckt ist, einerlei
   ob in Gro&szlig;buchstaben, in Gro&szlig;- und Kleinschreibung und schlie&szlig;en
   Sie es in <kbd>/*</kbd> und <kbd>*/</kbd> ein.
   Siehe <a href="#outofline">Platzierung von Formatierung au&szlig;erhalb des laufenden Textes</a>
   zu den Einzelheiten f&uuml;r diese Markierung.
</p>
<p>Seitenzahlen sollen erhalten bleiben und mit mindestens sechs Leerzeichen vom Rest
   der Zeile abgesetzt werden.
   Entfernen Sie alle Punkte oder andere Zeichen (F&uuml;hrungszeichen), die
   zum Ausrichten der Seitenzahlen benutzt wurden.
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
      <th align="left" bgcolor="cornsilk">Richtig formatierter Text:</th>
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
<p class="backtotop"><a href="#top">Zur&uuml;ck nach oben</a></p>


<h3><a name="bk_index">Sachregister und Schlagwortverzeichnisse</a></h3>
<p>Umschlie&szlig;en Sie das Register mit den Markierungen <kbd>/*</kbd> und <kbd>*/</kbd>.
   Siehe <a href="#outofline">Platzierung von Formatierung au&szlig;erhalb des laufenden Textes</a>
   zu den Einzelheiten f&uuml;r diese Markierung. Sie brauchen die Zahlen nicht
   so auszurichten, wie sie auf der Vorlage aussehen. Setzen Sie lediglich ein
   Komma oder einen Semikolon, gefolgt von den Seitenzahlen.
</p>
<p>Register sind oft in zwei Spalten gedruckt. Die geringere Breite kann dazu
   f&uuml;hren, dass Teile von Eintr&auml;gen in die n&auml;chste Zeile umgebrochen
   werden. Verbinden Sie diese wieder zu einer Zeile. Dabei k&ouml;nnen lange Zeilen
   entstehen, aber sie werden  beim Nachbearbeiten auf die richtige Breite umgebrochen
   und einger&uuml;ckt werden.
</p>
<p>F&uuml;gen Sie zwischen allen Eintr&auml;gen des Registers eine Leerzeile ein.
   Untereintr&auml;ge (oft durch ein Semicolon <kbd>;</kbd> abgetrennt) beginnen Sie
   jeweils auf einer neuen Zeile, um zwei Leerzeichen einger&uuml;ckt.
</p>
<p>Behandeln Sie jeden neuen Abschnitt in einem Register (A, B, C&nbsp;...) genauso
   wie eine <a href="#sect_head">Abschnitts&uuml;berschrift</a>, indem Sie zwei
   Leerzeilen davor einf&uuml;gen.
</p>
<p>In alten B&uuml;chern wurde das erste Wort jedes Abschnitts im Register oft in
   Gro&szlig;buchstaben oder Kapit&auml;lchen gedruckt. &Auml;ndern Sie dies so, dass
   es dem Stil der anderen Eintr&auml;ge entspricht.
</p>
<p>Bitte pr&uuml;fen Sie in den <a href="#comments">Projektkommentaren</a> nach,
   ob der Projektmanager eine andere Formatierung verlangt hat, etwa die Behandlung
   als <a href="#toc">Inhaltsverzeichnis</a>.
</p>
<!-- END RR -->

<table width="100%" align="center" border="1" cellpadding="4" cellspacing="0" summary="Rejoining Index Lines">
  <tbody>
    <tr><th align="left" bgcolor="cornsilk">Beispielvorlage:</th></tr>
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
      <th align="left" bgcolor="cornsilk">Richtig formatierter Text:</th>
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
    <tr><th align="left" bgcolor="cornsilk">Beispielvorlage:</th></tr>
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
      <th align="left" bgcolor="cornsilk">Richtig formatierter Text:</th>
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
    <tr><th align="left" bgcolor="cornsilk">Beispielvorlage:</th></tr>
    <tr align="left">
      <td valign="top"> <img src="../index.png" alt="" width="438" height="355"></td>
    </tr>
    <tr><th align="left" bgcolor="cornsilk">Richtig formatierter Text:</th></tr>
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
<p class="backtotop"><a href="#top">Zur&uuml;ck nach oben</a></p>


<h3><a name="play_n">Dramen: Rollennamen/Regieanweisungen</a></h3>
<p>F&uuml;r alle Dramen gilt:</p>
<ul compact>
  <li>Formatieren Sie Besetzungslisten (Dramatis Person&aelig;) wie
      <a href="#lists">Aufz&auml;hlungen</a>.</li>
  <li>Behandeln Sie jeden neuen Akt wie eine <a href="#chap_head">Kapitel&uuml;berschrift</a>,
      indem Sie 4 Leerzeilen davor setzen und 2 dahinter.</li>
  <li>Behandeln Sie jede neuen Szene wie eine <a href="#sect_head">Abschnitts&uuml;berschrift</a>,
      indem Sie 2 Leerzeilen davor setzen.</li>
  <li>Innerhalb der Dialoge behandeln Sie einen Wechsel des Sprechers als einen
      neuen Absatz, mit jeweils einer Leerzeile davor.</li>
  <li>Formatieren Sie die Namen der Darsteller wie auf der Vorlage &ndash; <a href="#italics">kursiv</a>,
      <a href="#bold">fett</a> oder in <a href="#word_caps">Gro&szlig;buchstaben</a>.</li>
  <li>Regieanweisungen werden so formatiert, wie sie im Originaltext sind.
      Steht die Anweisung in einer eigenen Zeile, dann formatieren Sie sie so; steht
      sie am Ende einer Dialogzeile, dann lassen Sie sie dort. Ist sie rechtsb&uuml;ndig
      am Ende eines Dialogs ausgerichtet, so lassen Sie sechs Leerzeichen zwischen
      dem Dialog und der Regieanweisung frei.<br>
      Regieanweisungen beginnen h&auml;ufig mit einer &ouml;ffnenden Klammer und
      lassen die schlie&szlig;ende Klammer weg.
      Diese Konvention wird beibehalten; schlie&szlig;en Sie die Klammern nicht.
      Bei kursiv gedrucktem Text wird die Markierung &lt;i&gt;&lt;/i&gt; im Allgemeinen
      innerhalb der Klammern platziert.</li>
</ul>
<p>Metrische B&uuml;hnenst&uuml;cke (B&uuml;hnenst&uuml;cke, die als Lyrik in
   Versen geschrieben sind):
</p>
<ul compact>
  <li>Viele B&uuml;hnenst&uuml;cke sind metrisch und sollten, wie Lyrik, nicht
      neu umgebrochen werden. Umschlie&szlig;en Sie metrischen Text mit <kbd>/*</kbd>
      und <kbd>*/</kbd>, so wie bei Gedichten. Wenn B&uuml;hnenanweisungen auf einer
      eigenen Zeile stehen, umschlie&szlig;en Sie sie nicht mit <kbd>/*</kbd> und
      <kbd>*/</kbd>. (B&uuml;hnenanweisungen sind nicht metrisch und d&uuml;rfen deshalb
      bei der Nachbearbeitung umgebrochen werden. Daher sollten sie nicht in
      /*&nbsp;*/-Markierungen stehen, die den metrischen Dialog vor dem Umbruch sch&uuml;tzen.)</li>
  <li>Erhalten Sie den relativen Einzug von Dialog, wenn der Text einer einzelnen
      Zeile von mehr als einem Sprecher gesprochen wird.</li>
  <li>Verbinden Sie metrische Zeilen, die wegen ihrer &Uuml;berl&auml;nge geteilt
      wurden, genauso wie bei <a href="#poetry">Lyrik</a>.
      Besteht die Fortsetzung nur aus einem Wort o.&nbsp;&auml;., wird dieses oft
      hinter eine ( mit auf die Zeile dar&uuml;ber oder darunter gesetzt statt in
      eine eigene Zeile.
      Ein Beispiel finden Sie <a href="#play4">hier</a>.</li>
</ul>
<p>Bitte pr&uuml;fen Sie die <a href="#comments">Projektkommentare</a>, da der
   Projektmanager eine andere Formatierung festlegen kann.
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
      <th align="left" bgcolor="cornsilk">Richtig formatierter Text:</th>
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
      <th align="left" bgcolor="cornsilk">Beispielvorlage:</th>
    </tr>
    <tr align="left">
      <td width="100%" valign="top"><img src="../play2.png" width="500" height="680" alt=""><br>
      </td>
    </tr>
    <tr>
      <th align="left" bgcolor="cornsilk">Richtig formatierter Text:</th>
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
      <th align="left" bgcolor="cornsilk">Beispielvorlage:</th>
    </tr>
    <tr align="left">
      <td width="100%" valign="top"><img src="../play3.png" width="504" height="206" alt=""><br>
      </td>
    </tr>
    <tr>
      <th align="left" bgcolor="cornsilk">Richtig formatierter Text:</th>
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
      <th align="left" bgcolor="cornsilk">Beispielvorlage:</th>
    </tr>
    <tr align="left">
      <td width="100%" valign="top"><img src="../play4.png" width="502" height="98" alt=""><br>
      </td>
    </tr>
    <tr>
      <th align="left" bgcolor="cornsilk">Richtig formatierter Text:</th>
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
<p class="backtotop"><a href="#top">Zur&uuml;ck nach oben</a></p>


<h3><a name="anything">Sonstige Besonderheiten und Behandlung von Unklarheiten</a></h3>
<p>Wenn Sie w&auml;hrend des Formatierens auf etwas sto&szlig;en, das nicht
   von diesen Richtlinien abgedeckt wird, das Ihrer Meinung nach einer besonderen
   Behandlung bedarf oder von dem Sie nicht sicher wissen, wie es behandelt werden
   soll, stellen Sie Ihre Frage unter Angabe der png-Nummer (Seitenzahl)
   im <a href="#forums">Projektforum</a>.
</p>
<p>Setzen Sie zus&auml;tzlich eine
   Anmerkung in den formatierten Text, die das Problem erl&auml;utert. Durch Ihre
   Anmerkung werden der nachfolgende Freiwillige bzw. der Nachbearbeiter auf das
   Problem hingewiesen.
   Beginnen Sie die Anmerkung mit einer eckigen Klammer und zwei Sternchen <kbd>[**</kbd>
   und schlie&szlig;en Sie sie wiederum mit einer eckigen Klammer <kbd>]</kbd>. Dadurch
   ist sie deutlich vom Text des Autors getrennt und signalisiert dem Nachbearbeiter
   innezuhalten. Er wird diesen Teil des Textes und die dazugeh&ouml;rige Vorlage
   sorgf&auml;ltig untersuchen, um etwaige Probleme zu l&ouml;sen. Sie k&ouml;nnen
   auch die Runde angeben, in der Sie arbeiten, direkt vor der <kbd>]</kbd>, damit
   sp&auml;tere Freiwillige wissen, wer die Anmerkung hinterlassen hat. Siehe den
   n&auml;chsten Abschnitt f&uuml;r Einzelheiten.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Zur&uuml;ck nach oben</a></p>


<h3><a name="prev_notes">Anmerkungen vorhergehender Korrekturleser</a></h3>
<p>Alle Anmerkungen oder Kommentare von Lesern aus fr&uuml;heren Runden
   <b>m&uuml;ssen</b> an Ort und Stelle bleiben. Sie k&ouml;nnen Ihre Zustimmung
   oder Ablehnung hinzuf&uuml;gen, aber selbst wenn Sie die Antwort wissen, d&uuml;rfen
   Sie den Kommentar auf keinen Fall entfernen. Wenn Sie eine Quelle f&uuml;r die
   L&ouml;sung des Problems gefunden haben, so geben Sie sie bitte an, damit sich
   der Nachbearbeiter auch darauf beziehen kann.
</p>
<p>Wenn Sie in einer sp&auml;teren Runde formatieren und auf die Anmerkung eines
   Mitlesers aus einer fr&uuml;heren Runde sto&szlig;en, deren L&ouml;sung Sie
   kennen, nehmen Sie sich bitte einen Moment Zeit und geben ihm R&uuml;ckmeldung.
   Klicken Sie dazu auf seinen Namen in der Korrekturlese-Oberfl&auml;che und schicken
   Sie ihm eine private Mitteilung, in der Sie erkl&auml;ren, wie das Problem in
   Zukunft behandelt werden soll. Aber, wie bereits gesagt, lassen Sie die Anmerkung
   auf jeden Fall stehen.
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


<h3><a name="bad_image">Schlechte Vorlagen</a></h3>
<p>Ist eine Vorlage schlecht (sie wird nicht geladen, ist abgeschnitten oder unlesbar),
   so machen Sie einen Eintrag im <a href="#forums">Projektforum</a>.
</p>
<p>Beachten Sie, dass einige Bilddateien von Seiten recht gro&szlig; sind und
   Ihr Browser Schwierigkeiten bei der Darstellung haben k&ouml;nnte, besonders
   wenn Sie mehrere Fenster ge&ouml;ffnet haben oder einen &auml;lteren Computer
   benutzen. Versuchen Sie, einige Ihrer Fenster und Programme zu schlie&szlig;en,
   um zu sehen, ob das hilft, oder posten Sie in der <a href="#forums">Projektdiskussion</a>
   um zu sehen, ob jemand anders das gleiche Problem hat.
</p>

<!-- END RR -->
<p class="backtotop"><a href="#top">Zur&uuml;ck nach oben</a></p>


<h3><a name="bad_text">Falsche Vorlage zum Text</a></h3>
<p>Wenn eine falsche Vorlage zum Text im Korrekturfenster angezeigt wird, machen
   Sie einen Eintrag im <a href="#forums">Projektforum</a>.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Zur&uuml;ck nach oben</a></p>


<h3><a name="round1">Fehler der vorherigen Korrekturleser bzw. Formatierer</a></h3>
<p>Wenn ein vorhergehender Freiwilliger sehr viele Fehler gemacht hat oder viel
   &uuml;bersehen hat, nehmen Sie sich bitte einen Moment Zeit und geben Sie ihm
   R&uuml;ckmeldung. Klicken Sie dazu auf seinen Namen in der
   Korrekturlese-Oberfl&auml;che und schicken Sie ihm eine private Mitteilung.
   Erkl&auml;ren Sie ihm, wie er die Situation handhaben sollte, damit er in
   Zukunft besser zurecht kommt.
</p>
<p><em>Bitte seien Sie freundlich!</em> Alle hier sind Freiwillige, und wir gehen
   davon aus, dass jeder sein Bestes gibt. Inhalt Ihrer Feedback-Nachricht sollte
   die Information sein, wie richtig formatiert wird, nicht Kritik. Zeigen Sie
   anhand eines Beispiels auf, was der Freiwillige gemacht hat und was er h&auml;tte
   machen sollen.
</p>
<p>Hat der vorhergehende Freiwillige hervorragend gearbeitet, so k&ouml;nnen Sie
   ihm ebenfalls eine Nachricht senden &ndash; vor allem, wenn die Seite besonders
   schwierig war.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Zur&uuml;ck nach oben</a></p>


<h3><a name="p_errors">Satz- und Rechtschreibfehler: &bdquo;Typos&ldquo;</a></h3>
<p>Korrigieren Sie alle Fehler, die die OCR-Software fehlgelesen hat
   (&bdquo;Scannos&ldquo;) &ndash; aber verbessern Sie nicht, was Ihnen wie
   Rechtschreib- oder Druckfehler vorkommt, die auf der gescannten Vorlage auftreten.
   In vielen &auml;lteren Texten werden W&ouml;rter abweichend vom modernen Gebrauch
   buchstabiert. Wir behalten diese &auml;lteren Schreibweisen bei,
   einschlie&szlig;lich aller Buchstaben mit Akzenten.
</p>
<p>Wenn Sie sich nicht sicher sind, setzen Sie eine Anmerkung in den Text unmittelbar
   neben den Dreckfuhler<kbd>[**typo for Druckfehler?]</kbd>. Wenn Sie sich nicht sicher
   sind, ob es sich wirklich um einen Fehler handelt, fragen Sie auch in der
   <a href="#forums">Projektdiskussion</a> nach. &Auml;ndern Sie etwas, so erkl&auml;ren
   Sie mit einer Anmerkung, was Sie ge&auml;ndert haben: <kbd>[**typo "Dreckfuhler" fixed]</kbd>.
   Verwenden Sie hierbei die beiden Sternchen <kbd>**</kbd>,
   damit der Nachbearbeiter auf diese Stelle aufmerksam wird.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Zur&uuml;ck nach oben</a></p>


<h3><a name="f_errors">Tatsachenfehler im Text</a></h3>
<p>Im Allgemeinen gilt: Korrigieren Sie keine Tatsachenfehler im Buch des Autors.
   Viele der B&uuml;cher, die hier bearbeitet werden, enthalten Aussagen, die wir
   nicht mehr als zutreffend empfinden. Lassen Sie diese so, wie sie der Autor
   geschrieben hat.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Zur&uuml;ck nach oben</a></p>

</div>


<table width="100%" border="0" cellspacing="0" cellpadding="6" summary="Alphabetical Index">
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
        <li><a href="#illust">Abbildungen</a></li>
        <li><a href="#maj_div">Abbildungsverzeichnis</a></li>
        <li><a href="#para_space">Absatzabst&auml;nde und -einr&uuml;ckung</a></li>
        <li><a href="#extra_s">Absatzabst&auml;nde, zus&auml;tzliche</a></li>
        <li><a href="#sect_head">Abschnitts&uuml;berschriften</a></li>
        <li><a href="#para_space">Abst&auml;nde, Absatz-</a></li>
        <li><a href="#maj_div">Andere Hauptbestandteile des Textes</a></li>
        <li><a href="#prev_notes">Anmerkungen vorhergehender Freiwilliger</a></li>
        <li><a href="#font_ch">Antiqua, Text in</a></li>
        <li><a href="#outofline">au&szlig;erhalb des laufenden Textes, Formatierung</a></li>
        <li><a href="#anything">Behandlung von Unklarheiten</a></li>
        <li><a href="#prev_pg">Beseitigung von Fehlern auf vorhergehenden Seiten</a></li>
        <li><a href="#illust">Bildunterschriften</a></li>
        <li><a href="#block_qt">Blockzitate</a></li>
        <li><a href="#letter">Briefe/Korrespondenz</a></li>
        <li><a href="#forums">Diskussion</a></li>
        <li><a href="#play_n">Dramen</a></li>
        <li><a href="#p_errors">Druckfehler</a></li>
        <li><a href="#maj_div">Einleitung</a></li>
        <li><a href="#para_space">Einz&uuml;ge von Abs&auml;tzen</a></li>
        <li><a href="#footnotes">Endnoten</a></li>
        <li><a href="#poetry">Epigramme</a></li>
        <li><a href="#bad_text">Falsche Vorlage zum Text</a></li>
        <li><a href="#f_errors">Fehler, Tatsachen-</a></li>
        <li><a href="#round1">Fehler, vorhergehende, bei Korrekturlesen oder Formatieren</a></li>
        <li><a href="#p_errors">Fehlschreibungen, Druckfehler</a></li>
        <li><a href="#bold">Fetter Text</a></li>
        <li><a href="#outofline">Formatierung au&szlig;erhalb des laufenden Textes</a></li>
        <li><a href="#inline">Formatierung im laufenden Text</a></li>
        <li><a href="#separate_pg">Formatierung jeder Seite f&uuml;r sich</a></li>
        <li><a href="#forums">Forum</a></li>
        <li><a href="#footnotes">Fu&szlig;noten</a></li>
        <li><a href="#extra_s">Gedankenwechsel</a></li>
        <li><a href="#poetry">Gedichte</a></li>
        <li><a href="#spaced"><span style="letter-spacing: .2em;">Gesperrter</span> Text</a></li>
        <li><a href="#word_caps">Gro&szlig;buchstaben, Text in</a></li>
        <li><a href="#font_sz">Gr&ouml;&szlig;en&auml;nderung der Schrift</a></li>
        <li><a href="#maj_div">Hauptbestandteile des Textes, andere</a></li>
        <li><a href="#title_pg">Hintere Umschlagseite</a></li>
        <li><a href="#supers">Hochgestellter Text</a></li>
        <li><a href="#illust">Illustrationen</a></li>
        <li><a href="#toc">Inhaltsverzeichnis</a></li>
        <li><a href="#separate_pg">Jede Seite ist eine gesonderte Einheit</a></li>
        <li><a href="#small_caps"><span style="font-variant: small-caps">Kapit&auml;lchen</span></a></li>
        <li><a href="#chap_head">Kapitel&uuml;berschriften</a></li>
        <li><a href="#prev_notes">Kommentare vorhergehender Korrekturleser</a></li>
        <li><a href="#letter">Korrespondenz</a></li>
        <li><a href="#italics">Kursivschrift</a></li>
        <li><a href="#summary">Kurzfassung der Richtlinien</a></li>
        <li><a href="#blank_pg">Leere Seite</a></li>
        <li><a href="#extra_sp">Leerzeichen, zus&auml;tzliche</a></li>
        <li><a href="#extra_s">Linien, horizontale</a></li>
        <li><a href="#lists">Listen</a></li>
        <li><a href="#poetry">Lyrik</a></li>
      </ul>
    </td>
    <td width="50%" valign="top">
      <ul>
        <li><a href="#para_side">Marginalien (Randnoten)</a></li>
        <li><a href="#prime">Oberstes Gebot</a></li>
        <li><a href="#outofline">Platzierung der Formatierung au&szlig;erhalb des laufenden Texts</a></li>
        <li><a href="#inline">Platzierung der Formatierung im laufenden Text</a></li>
        <li><a href="#forums">Projektdiskussion</a></li>
        <li><a href="#comments">Projektkommentare</a></li>
        <li><a href="#para_side">Randnoten (Marginalien)</a></li>
        <li><a href="#r_align">Rechtsb&uuml;ndiger Text</a></li>
        <li><a href="#play_n">Regieanweisungen (Theaterst&uuml;cke)</a></li>
        <li><a href="#play_n">Rollennamen (Theaterst&uuml;cke)</a></li>
        <li><a href="#bk_index">Sachregister</a></li>
        <li><a href="#p_errors">Satzfehler</a></li>
        <li><a href="#bk_index">Schlagwortverzeichnisse</a></li>
        <li><a href="#bad_image">Schlechte Vorlage</a></li>
        <li><a href="#bad_text">Schlechter Text</a></li>
        <li><a href="#font_ch">Schriftart, Wechsel der</a></li>
        <li><a href="#font_sz">Schriftgr&ouml;&szlig;e, Wechsel der</a></li>
        <li><a href="#page_ref">Seitenverweise "Siehe S. 123"</a></li>
        <li><a href="#anything">Sonstige Besonderheiten</a></li>
        <li><a href="#anything">Spezielle Behandlung</a></li>
        <li><a href="#extra_s">Sternchen zwischen Abs&auml;tzen</a></li>
        <li><a href="#tables">Tabellen</a></li>
        <li><a href="#extra_sp">Tabulatoren</a></li>
        <li><a href="#f_errors">Tatsachenfehler</a></li>
        <li><a href="#r_align">Text, rechtsb&uuml;ndiger</a></li>
        <li><a href="#bad_text">Text, falsche Vorlage zum</a></li>
        <li><a href="#play_n">Theaterst&uuml;cke: Rollennamen/Regieanweisungen</a></li>
        <li><a href="#extra_s">"thought break"</a></li>
        <li><a href="#subscr">Tiefgestellter Text</a></li>
        <li><a href="#about">&Uuml;ber dieses Dokument</a></li>
        <li><a href="#maj_div">&Uuml;berschriften, andere</a></li>
        <li><a href="#chap_head">&Uuml;berschriften, Kapitel</a></li>
        <li><a href="#sect_head">&Uuml;berschriften, Abschnitt</a></li>
        <li><a href="#title_pg">Umschlagseiten</a></li>
        <li><a href="#anything">Unklarheiten, Behandlung von</a></li>
        <li><a href="#underl">Unterstrichener Text</a></li>
        <li><a href="#maj_div">Verzeichnis der Abbildungen</a></li>
        <li><a href="#extra_s">Verzierungen zwischen Abs&auml;tzen</a></li>
        <li><a href="#prev_pg">Vorhergehende Seiten, Fehler beheben auf</a></li>
        <li><a href="#round1">Vorhergehende Fehler beim Korrekturlesen oder Formatieren</a></li>
        <li><a href="#prev_notes">Vorhergehender Freiwilliger, Notizen/Anmerkungen des</a></li>
        <li><a href="#bad_image">Vorlage, schlechte</a></li>
        <li><a href="#maj_div">Vorwort</a></li>
        <li><a href="#extra_s">Waagerechte Linien</a></li>
        <li><a href="#font_ch">Wechsel der Schriftart</a></li>
        <li><a href="#font_sz">Wechsel der Schriftgr&ouml;&szlig;e</a></li>
        <li><a href="#word_caps">Worte in Gro&szlig;buchstaben</a></li>
        <li><a href="#small_caps">Worte in <span style="font-variant: small-caps">Kapit&auml;lchen</span></a></li>
        <li><a href="#extra_s">Zeile zwischen Abs&auml;tzen</a></li>
        <li><a href="#line_no">Zeilennummern</a></li>
        <li><a href="#block_qt">Zitate, Block-</a></li>
        <li><a href="#extra_sp">Zus&auml;tzliche Leerstellen zwischen W&ouml;rtern</a></li>
        <li><a href="#extra_s">Zus&auml;tzliche Leerzeilen zwischen Abs&auml;tzen</a></li>
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
