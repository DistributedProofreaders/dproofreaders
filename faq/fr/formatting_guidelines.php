<?php

// Translated by user 'Pierre' at pgdp.net, 2006-02-08
// Updated by user lvl, 2009-03-20

$relPath='../../pinc/';
include_once($relPath.'base.inc');
include_once($relPath.'faq.inc');
include_once($relPath.'pg.inc');
include_once($relPath.'theme.inc');

maybe_redirect_to_external_faq("fr");

$theme_args["css_data"] = "p.backtotop {text-align:right; font-size:75%;margin-right:-5%;}";

output_header('Directives de Formatage', NO_STATSBAR, $theme_args);

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

<h1 align="center"><a name="top">Directives de Formatage</a></h1>

<h3 align="center">Version 2.0, 7 juin 2009 (traduction de la 2.0 anglaise du 7 juin 2009)</h3>

<p>Directives de Formatage <a href="../formatting_guidelines.php">en anglais</a> /
      Formatting Guidelines <a href="../formatting_guidelines.php">in English</a><br>
    Directives de Formatage <a href="../pt/formatting_guidelines.php">en portugais</a> /
      Regras de Formata&ccedil;&atilde;o <a href="../pt/formatting_guidelines.php">em Portugu&ecirc;s</a><br>
    Directives de Formatage <a href="../nl/formatting_guidelines.php">en n&eacute;erlandais</a> /
      Formatteer-Richtlijnen <a href="../nl/formatting_guidelines.php">in het Nederlands</a><br>
    Directives de Formatage <a href="../de/formatting_guidelines.php">en allemand</a> /
      Formatierungsrichtlinien <a href="../de/formatting_guidelines.php">auf Deutsch</a><br>
    Directives de Formatage <a href="../it/formatting_guidelines.php">en italien</a> /
      Regole di Formattazione <a href="../it/formatting_guidelines.php">in Italiano</a><br>
</p>

<p>Allez voir le <a href="../../quiz/start.php?show_only=FQ">Quiz de formatage</a>!
</p>

<table border="0" cellspacing="0" width="100%" summary="Table des mati&egrave;res">
  <tbody>
  <tr>
    <td width="1" bgcolor="silver">&nbsp;</td>
    <td bgcolor="silver" align="center"><h2 style="margin-top:0; margin-bottom:0;">Table des mati&egrave;res</h2></td>
  </tr>
  <tr>
    <td width="1" bgcolor="silver">&nbsp;</td>
    <td bgcolor="white" align="left">
    <ul>
      <li><a href="#prime">La r&egrave;gle principale</a></li>
      <li><a href="#summary">R&eacute;sum&eacute; des directives</a></li>
      <li><a href="#about">&Agrave; propos de ce document</a></li>
      <li><a href="#separate_pg">Chaque page est une unit&eacute; s&eacute;par&eacute;e</a></li>
      <li><a href="#comments">Commentaires sur les projets</a></li>
      <li><a href="#forums">Forum/Discuter de ce Projet</a></li>
      <li><a href="#prev_pg">Corriger des erreurs sur les pages pr&eacute;c&eacute;dentes</a></li>
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
        <li style="margin-top:.25em;"><font size="+1">Formatage &agrave; l'&eacute;chelle des caract&egrave;res:</font>
        <ul>
          <li><a href="#inline">Placement des marques intra-paragraphe</a></li>
          <li><a href="#italics">Italiques</a></li>
          <li><a href="#bold">Texte en gras</a></li>
          <li><a href="#underl">Texte soulign&eacute;</a></li>
          <li><a href="#spaced"><span style="letter-spacing: .2em;">Texte espac&eacute;</span> (gesperrt)</a></li>
          <li><a href="#font_ch">Changement de police</a></li>
          <li><a href="#small_caps"><span style="font-variant: small-caps">Petites capitales</span></a></li>
          <li><a href="#word_caps">Mots tout en majuscules</a></li>
          <li><a href="#font_sz">Changement de taille de police</a></li>
          <li><a href="#extra_sp">Espaces exc&eacute;dentaires entre les mots</a></li>
          <li><a href="#supers">Texte en exposant</a></li>
          <li><a href="#subscr">Texte en indice</a></li>
          <li><a href="#page_ref">R&eacute;f&eacute;rences aux pages &ldquo;Voir p. 123&rdquo;</a></li>
        </ul></li>
        <li style="margin-top:.25em;"><font size="+1">Formatage &agrave; l'&eacute;chelle du paragraphe:</font>
        <ul>
          <li><a href="#chap_head">T&ecirc;tes de chapitre</a></li>
          <li><a href="#sect_head">T&ecirc;tes de section</a></li>
          <li><a href="#maj_div">Autres divisions dans les textes</a></li>
          <li><a href="#para_space">Espacement et indentation des paragraphes</a></li>
          <li><a href="#extra_s">Interruption du fil du discours (espace suppl&eacute;mentaire, ast&eacute;risques,
              ligne d&eacute;corative entre les paragraphes)</a></li>
          <li><a href="#illust">Illustrations</a></li>
          <li><a href="#footnotes">Notes de fin et de notes de bas de page</a></li>
          <li><a href="#para_side">Commentaires en marge des paragraphes</a></li>
          <li><a href="#outofline">Formatage hors texte</a></li>
          <li><a href="#block_qt">Blocs de citations</a></li>
          <li><a href="#lists">Listes de choses</a></li>
          <li><a href="#tables">Tableaux</a></li>
          <li><a href="#poetry">Po&eacute;sie/&Eacute;pigrammes</a></li>
          <li><a href="#line_no">Num&eacute;ros de ligne</a></li>
          <li><a href="#letter">Lettres (courrier)</a></li>
          <li><a href="#r_align">Texte align&eacute; &agrave; droite</a></li>
        </ul></li>
        <li style="margin-top:.25em;"><font size="+1">Formatage &agrave; l'&eacute;chelle de la page:</font>
        <ul>
          <li><a href="#blank_pg">Page blanche</a></li>
          <li><a href="#title_pg">Page de titre/fin</a></li>
          <li><a href="#toc">Table des mati&egrave;res</a></li>
          <li><a href="#bk_index">Index</a></li>
          <li><a href="#play_n">Th&eacute;&acirc;tre</a></li>
        </ul></li>
        <li><a href="#anything">Tous autres points n&eacute;cessitant un traitement particulier, ou dont vous
          n'&ecirc;tes pas s&ucirc;r</a></li>
        <li><a href="#prev_notes">Notes/commentaires des correcteurs pr&eacute;c&eacute;dents</a></li>
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
        <li style="margin-top:.25em;"><font size="+1">Probl&egrave;mes courants:</font>
        <ul>
          <li><a href="#bad_image">Mauvaises images</a></li>
          <li><a href="#bad_text">Image ne correspondant pas au texte</a></li>
          <li><a href="#round1">Erreurs des correcteurs pr&eacute;c&eacute;dents</a></li>
          <li><a href="#p_errors">Erreurs d'impressions et d'orthographe</a></li>
          <li><a href="#f_errors">Erreurs factuelles dans les textes</a></li>
        </ul></li>
        <li><a href="#index">Index alphab&eacute;tique des directives</a></li>
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

<h3><a name="prime">La r&egrave;gle principale</a></h3>
<p><em>"Ne changez pas ce que l'auteur a &eacute;crit!"</em>
</p>
<p>Le livre &eacute;lectronique final vu par un lecteur, peut-&ecirc;tre dans un avenir
   plus ou moins lointain, doit transmettre l'intention de l'auteur de mani&egrave;re exacte.
   Si l'auteur &eacute;crit des mots d'une mani&egrave;re &eacute;trange, laissez-les.
   Si l'auteur &eacute;crit des choses choquantes, racistes ou partiales, laissez-les
   telles quelles. Si l'auteur semble mettre des italiques, des mots en gras ou des notes de bas de
   page tous les trois mots, gardez les italiques, les mots en gras et les notes de
   bas de page. Si quelque chose dans le texte ne correspond pas &agrave; ce qui appara&icirc;t
   sur l'image de la page de d&eacute;part, vous devez modifier le texte pour qu'il
   repr&eacute;sente l'image.
   (Voir toutefois la section <a href="#p_errors">Erreurs d'impression</a>
   pour la fa&ccedil;on correcte de traiter les fautes &eacute;videntes de l'imprimeur.)
</p>
<p>Par contre, nous changeons des choses mineures qui n'affectent pas le sens de
   ce que l'auteur a &eacute;crit. Par exemple, nous d&eacute;pla&ccedil;ons les l&eacute;gendes d'illustrations
   si c'est n&eacute;cessaire afin qu'elles n'apparaissent pas au milieu d'un paragraphe
   (voir <a href="#illust">Illustrations</a>).
   Ces changements nous permettent d'avoir des livres <em>d'un format
   homog&egrave;ne</em>. Nous suivons les r&egrave;gles de formatage pour avoir ce
   r&eacute;sultat. Lisez attentivement le reste de ces Directives en gardant ce concept
   &agrave; l'esprit. Les pr&eacute;sentes r&egrave;gles sont destin&eacute;es uniquement au formatage.
   Les relecteurs ont assur&eacute; une repr&eacute;sentation fid&egrave;le du contenu, et maintenant
   votre r&ocirc;le en tant que formateur est de transcrire la pr&eacute;sentation de la page.
</p>
<p>Pour aider le prochain formateur et le post-processeur, nous gardons aussi
   les retours &agrave; la ligne. Il est ainsi facile de
   comparer les lignes du texte corrig&eacute; et les lignes de l'image.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Retour au d&eacute;but</a></p>


<h3><a name="summary">R&eacute;sum&eacute; des directives</a></h3>
<p>Le <a href="../formatting_summary.pdf">R&eacute;sum&eacute; des directives</a> (en anglais)
   est un court document imprimable de 2 pages (.pdf) qui r&eacute;sume les
   points principaux de ces directives, et qui donne des exemples de corrections.
   Les formateurs d&eacute;butants sont encourag&eacute;s &agrave; imprimer ce document et &agrave; le garder
   &agrave; port&eacute;e de main quand ils formatent.
</p>
<p>Vous aurez besoin d'un lecteur de fichiers .pdf. Vous pouvez en t&eacute;l&eacute;charger
   un gratuitement chez Adobe&reg; <a href="http://www.adobe.com/products/acrobat/readstep2.html">ici</a>.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Retour au d&eacute;but</a></p>


<h3><a name="about">&Agrave; propos de ce document</a></h3>
<p>Ce document a pour but de r&eacute;duire les diff&eacute;rences de formatage entre les
   travaux des diff&eacute;rents correcteurs qui ont travaill&eacute; sur un m&ecirc;me livre, de
   mani&egrave;re &agrave; ce que nous formations tous <em>de la m&ecirc;me mani&egrave;re.</em>
   Cela rend le travail plus facile aux post-processeurs.
</p>
<p><i>Ce document n'est en aucune mani&egrave;re cens&eacute; &ecirc;tre un recueil
   de r&egrave;gles &eacute;ditoriales ou typographiques.</i>
</p>
<p>Nous avons inclus dans ce document tous les points de formatage
   qui ont suscit&eacute; des questions de la part des nouveaux utilisateurs.
   Il existe un document diff&eacute;rent concernant les
   <a href="proofreading_guidelines.php">Directives de relecture</a>.
   Si vous rencontrez une situation qui n'est pas mentionn&eacute;e dans les pr&eacute;sentes
   directives, il est probable qu'il s'agisse d'une t&acirc;che qui ait d&eacute;j&agrave; &eacute;t&eacute; g&eacute;r&eacute;e
   par les relecteurs, et que vous ne trouviez donc pas la r&eacute;ponse ici.
   Si vous avez un doute, veuillez
   demander dans le <a href="#forums">forum de discussion du projet</a>.
</p>
<p>S'il manque des r&eacute;ponses dans ce document, ou que vous consid&eacute;rez que certaines
   choses devraient &ecirc;tre trait&eacute;es de mani&egrave;re diff&eacute;rente, ou ou que l'explication est
   trop vague, merci de nous le faire savoir.
<?php if($site_url == "http://www.pgdp.net" || $site_url == "http://www.pgdp.org") { ?>
   Si vous rencontrez une expression inconnue dans ces directives, voyez le
   <a href="http://www.pgdp.net/wiki/French/Jargon">guide du Jargon sur le wiki</a>.
<?php } ?>
   Ce document est en &eacute;volution permanente. Aidez-nous &agrave; l'am&eacute;liorer en nous
   faisant part de vos suggestions sur le forum Documentation dans
   <a href="<?php echo $Guideline_discussion_URL; ?>">ce fil de discussions</a>.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Retour au d&eacute;but</a></p>


<h3><a name="separate_pg">Chaque page est une unit&eacute; s&eacute;par&eacute;e</a></h3>
<p>Puisque chaque projet est r&eacute;parti vers de nombreux formateurs, qui travaillent
   chacun sur des pages diff&eacute;rentes, il n'y a aucune garantie que vous interveniez
   sur des pages cons&eacute;cutives du projet. Ayez cela en t&ecirc;te, et ne manquez pas d'ouvrir
   et de fermer chaque marque de formatage sur chaque page. Cela facilitera
   l'assemblage pour le post-processeur.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Retour au d&eacute;but</a></p>


<h3><a name="comments">Commentaires des projets</a></h3>
<p>Lorsque vous s&eacute;lectionnez un projet pour commencer &agrave; formater des pages,
   la page du projet est la premi&egrave;re page qui s'affiche. Dans cette page,
   il y a une section &ldquo;project comments&rdquo; (Commentaires du projet) qui contient des informations
   sp&eacute;cifiques &agrave; ce projet (livre). <b>Lisez celles-ci avant de commencer &agrave;
   formater des pages!</b> Si le responsable de projet veut que vous
   d&eacute;rogiez aux directives pour un point de formatage bien pr&eacute;cis, ce
   sera indiqu&eacute; l&agrave;. Les instructions dans les &ldquo;Commentaires du projet&rdquo; <em>supplantent</em>
   les r&egrave;gles contenues dans les pr&eacute;sentes directives, donc suivez-les. C'est aussi &agrave; cet endroit
   que le responsable de projet vous donne des informations int&eacute;ressantes &agrave; propos
   des livres, comme leur provenance, etc.
</p>
<p><em>Lisez aussi le forum de discussion du projet</em>: Le responsable de projet y
   clarifie des points portant sp&eacute;cifiquement sur le projet. Cette discussion est
   souvent utilis&eacute;e par les relecteurs pour signaler aux autres relecteurs les
   probl&egrave;mes r&eacute;currents dans le projet, et la meilleure fa&ccedil;on de les r&eacute;soudre.
</p>
<p>Sur la page du projet, le lien &ldquo;Images, Pages Proofread, &amp; Differences&rdquo;
   permet de voir comment les autres relecteurs ont chang&eacute; le texte.
   <a href="<?php echo $Using_project_details_URL; ?>">Ce fil de discussion</a>
   discute les diff&eacute;rentes fa&ccedil;on d'utiliser cette information.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Retour au d&eacute;but</a></p>


<h3><a name="forums">Forum/Discuter de ce Projet</a></h3>
<p>Dans la page du projet dans laquelle vous commencez &agrave; formater des pages,
   sur la ligne &ldquo;Forum&rdquo;, il y a un lien indiquant &ldquo;Discuss this Project&rdquo;
   (Discuter de ce projet, si la discussion a d&eacute;j&agrave; commenc&eacute;)
   ou bien &ldquo;Start a discussion on this Project&rdquo;
   (D&eacute;marrer une discussion sur le projet, sinon).
   Cliquer sur ce lien vous am&egrave;nera &agrave; un fil de discussion du forum d&eacute;di&eacute; sp&eacute;cifiquement &agrave; ce projet.
   C'est l'endroit o&ugrave; poser des questions &agrave; propos de ce livre,
   informer le responsable de projet &agrave; propos de probl&egrave;mes, etc. L'utilisation de
   ce forum est la mani&egrave;re recommand&eacute;e pour discuter avec le responsable de projet
   et les autres correcteurs qui travaillent sur ce livre.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Retour au d&eacute;but</a></p>


<h3><a name="prev_pg">Corriger des erreurs sur des pages pr&eacute;c&eacute;dentes</a></h3>
<p>La <a href="#comments">page du projet</a> contient des liens vers les pages
   que vous avez format&eacute;es r&eacute;cemment (si vous n'avez pas
   encore format&eacute; de pages, aucun lien ne sera encore affich&eacute;).
</p>
<p>Les pages list&eacute;es sous &ldquo;DONE&rdquo; et &ldquo;IN PROGRESS&rdquo; sont disponibles pour que vous
   puissiez corriger ou terminer votre travail de formatage. Cliquez sur le lien
   vers la page. Ainsi, si vous voyez que vous avez fait une erreur sur une page,
   vous pouvez cliquer sur cette page, et la rouvrir pour corriger l'erreur.
</p>
<p>Il est &eacute;galement possible depuis la <a href="#comments">page du projet</a> d'utiliser les liens
   &ldquo;Images, Pages Proofread, &amp; Differences&rdquo; ou l'option
   &ldquo;Just my pages&rdquo;. Ces pages pr&eacute;sentent un lien &ldquo;Edit&rdquo; sur
   toutes les pages sur lesquelles vous avez travaill&eacute; durant
   ce round. Il est encore temps de les corriger.
</p>
<p>Pour plus de d&eacute;tails, voyez <a href="../prooffacehelp.php?i_type=0">Aide sur l'interface
   standard</a> ou bien <a href="../prooffacehelp.php?i_type=1">Aide sur l'interface
   avanc&eacute;e</a>, selon l'interface que vous utilisez.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Retour au d&eacute;but</a></p>


<table width="100%" border="0" cellspacing="0" cellpadding="6" summary="Formatage &agrave; l'&eacute;chelle des caract&egrave;res">
  <tbody>
    <tr>
      <td bgcolor="silver"><h2 style="margin-bottom: 0; margin-top: 0;">Formatage &agrave; l'&eacute;chelle des caract&egrave;res</h2></td>
    </tr>
  </tbody>
</table>

<h3><a name="inline">Placement des marques intra-paragraphe</a></h3>
<p>Le formatage intra-paragraphe d&eacute;signe les marques comme <kbd>&lt;i&gt;</kbd>&nbsp;<kbd>&lt;/i&gt;</kbd>,
   <kbd>&lt;b&gt;</kbd>&nbsp;<kbd>&lt;/b&gt;</kbd>, <kbd>&lt;sc&gt;</kbd>&nbsp;<kbd>&lt;/sc&gt;</kbd>,
   <kbd>&lt;f&gt;</kbd>&nbsp;<kbd>&lt;/f&gt;</kbd>, ou <kbd>&lt;g&gt;</kbd>&nbsp;<kbd>&lt;/g&gt;</kbd>.
   Placez la ponctuation <b>en dehors</b> des marques, sauf si les marques sont
   dispos&eacute;es autour d'une phrase enti&egrave;re ou d'un paragraphe entier, ou si la
   ponctuation elle-m&ecirc;me fait partie de la phrase, du titre ou de l'abr&eacute;viation
   concern&eacute;e par le marquage. Si la mise en forme concerne plusieurs paragraphes
   &agrave; la suite, mettez les marques autour de chaque paragraphe.
</p>
<p>Le point qui indique un mot abr&eacute;g&eacute; dans le titre d'un journal tel que
   <i>Phil. Trans.</i> fait partie du titre, et est donc inclus entre les marques:
   <kbd>&lt;i&gt;Phil. Trans.&lt;/i&gt;</kbd>.
</p>
<p>Dans de nombreux cas les polices de caract&egrave;res utilis&eacute;es dans les livres
   anciens ne pr&eacute;sentaient pas de forme diff&eacute;rente pour les chiffres normaux, en
   italique ou en gras. Pour les dates ou les groupes de mots similaires, formatez
   le groupe entier avec un seul jeu de marques, plut&ocirc;t que de marquer les mots
   en italiques (ou en gras) sans les chiffres.
</p>
<p>S'il y a une s&eacute;rie ou une liste de mots ou de groupes (liste de noms, de titres,
   etc.) marquez chaque &eacute;l&eacute;ment de la liste s&eacute;par&eacute;ment.
</p>
<p>Voyez la section <a href="#tables">tableaux</a> sur le traitement des marques dans les tableaux.
</p>
<!-- END RR -->
<p><b>Exemples</b>:
</p>
<table width="100%" align="center" border="1" cellpadding="4" cellspacing="0" summary="Exemples de marques intra-paragraphe">
  <tbody>
    <tr>
      <th valign="top" bgcolor="cornsilk">Image de d&eacute;part:</th>
      <th valign="top" bgcolor="cornsilk">Texte correctement format&eacute;:</th>
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
<p class="backtotop"><a href="#top">Retour au d&eacute;but</a></p>


<h3><a name="italics">Italiques</a></h3>
<p>Le texte en <i>italique</i> doit avoir <kbd>&lt;i&gt;</kbd> ins&eacute;r&eacute; avant et
   <kbd>&lt;/i&gt;</kbd> ins&eacute;r&eacute; &agrave; la fin de l'italique.
   (remarquez le &ldquo;<kbd>/</kbd>&rdquo; dans le symbole de fin).
</p>
<p>Voyez &eacute;galement le <a href="#inline">placement des marques intra-paragraphe</a>.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Retour au d&eacute;but</a></p>


<h3><a name="bold">Texte en gras</a></h3>
<p>Le <b>texte en gras</b> doit &ecirc;tre marqu&eacute; par <kbd>&lt;b&gt;</kbd> avant et
   <kbd>&lt;/b&gt;</kbd> apr&egrave;s.
   (remarquez le &ldquo;<kbd>/</kbd>&rdquo; dans la marque de fin).
</p>
<p>Voyez &eacute;galement le <a href="#inline">placement des marques intra-paragraphe</a>
   et la section des <a href="#chap_head">t&ecirc;tes de chapitre</a>.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Retour au d&eacute;but</a></p>


<h3><a name="underl">Texte soulign&eacute;</a></h3>
<p>Marquez le <u>texte soulign&eacute;</u> comme &eacute;tant de l'<a href="#italics">Italique</a>,
   avec <kbd>&lt;i&gt;</kbd> et <kbd>&lt;/i&gt;</kbd>.
   (Remarquez le &ldquo;<kbd>/</kbd>&rdquo; dans la marque de fin).
   On avait souvent recours au texte soulign&eacute; pour mettre l'accent sur
   certains passages quand l'&eacute;diteur &eacute;tait incapable d'imprimer en italique,
   par exemple pour un document tap&eacute; &agrave; la machine.
</p>
<p>Voyez &eacute;galement le <a href="#inline">placement des marques intra-paragraphe</a>.
</p>
<p>Certains responsables de projet peuvent demander dans les <a href="#comments">commentaires de projet</a>
   que le texte soulign&eacute; soit marqu&eacute; avec <kbd>&lt;u&gt;</kbd> et <kbd>&lt;/u&gt;</kbd>.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Retour au d&eacute;but</a></p>


<h3><a name="spaced"><span style="letter-spacing: .2em;">Texte espac&eacute;</span> (gesperrt)</a></h3>
<p>Formatez le <span style="letter-spacing: .2em;">texte espac&eacute;</span> avec les marques <kbd>&lt;g&gt;</kbd> avant et
   <kbd>&lt;/g&gt;</kbd> apr&egrave;s.
   (Remarquez le &ldquo;<kbd>/</kbd>&rdquo; dans la marque de fin).
   Enlevez les espaces exc&eacute;dentaires entre les lettres d'un m&ecirc;me mot.
   Cette technique &eacute;tait utilis&eacute;e pour mettre l'accent sur certains passages
   dans les livres anciens, principalement en allemand.
</p>
<p>Voyez &eacute;galement le <a href="#inline">placement des marques intra-paragraphe</a>
   et la section des <a href="#chap_head">t&ecirc;tes de chapitre</a>.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Retour au d&eacute;but</a></p>


<h3><a name="font_ch">Changement de police</a></h3>
<p>Certains responsables de projet pourront demander que le changement de police
   &agrave; l'int&eacute;rieur d'un paragraphe soit indiqu&eacute; en mettant
   <kbd>&lt;f&gt;</kbd> avant et <kbd>&lt;/f&gt;</kbd> apr&egrave;s le passage.
   (Remarquez la barre oblique &ldquo;<kbd>/</kbd>&rdquo; dans la marque de fin).
   Ces marques peuvent &ecirc;tre utilis&eacute;es pour noter la pr&eacute;sence de toute
   police sp&eacute;ciale ou de typographie particuli&egrave;re qui ne disposent pas
   d&eacute;j&agrave; de leurs marques d&eacute;di&eacute;es (comme l'italique et le gras).
</p>
<p>Parmi les usages possibles de ce marquage, on trouve:
</p>
<ul compact>
  <li>antiqua (une sorte de police en roman) au milieu de texte en fraktur;</li>
  <li>caract&egrave;res gothiques au milieu d'un texte en polices normales;</li>
  <li>une police plus grande ou plus petite, seulement si c'est un passage au milieu
    d'un paragraphe en police normale (pour un paragraphe entier dans une police ou
    une taille diff&eacute;rente, voir la section <a href="#block_qt">bloc de citation</a>);</li>
  <li>une police droite au milieu d'un paragraphe en italique.</li>
</ul>
<p>Les usages particuliers relatifs &agrave; ce marquage seront g&eacute;n&eacute;ralement pr&eacute;cis&eacute;s
   dans les <a href="#comments">commentaires du projet</a>.
   S'ils estiment que l'usage de ce marquage est n&eacute;cessaire, les formateurs devraient
   le signaler dans le <a href="#forums">forum du projet</a> si cela n'a
   pas encore &eacute;t&eacute; demand&eacute;.
</p>
<p>Voyez &eacute;galement le <a href="#inline">placement des marques intra-paragraphe</a>.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Retour au d&eacute;but</a></p>


<h3><a name="small_caps">Petites capitales</a></h3>
<p>Le formatage est diff&eacute;rent pour les <span style="font-variant: small-caps;">Petites Capitales Altern&eacute;es</span>
   et pour les mots <span style="font-variant: small-caps;">uniquement en petites capitales</span>:
</p>
<p>Formatez les mots en <span style="font-variant: small-caps;">Petites Capitales Altern&eacute;es</span>
   en faisant alterner minuscules et majuscules; formatez les passages
   <span style="font-variant: small-caps;">uniquement en petites capitales</span>
   avec les mots TOUT EN MAJUSCULES.
   Dans les deux cas, entourez le texte par les marques
   <kbd>&lt;sc&gt;</kbd> et <kbd>&lt;/sc&gt;</kbd>.
</p>
<p>Un titre (une <a href="#chap_head">t&ecirc;te de chapitre</a>,
   ou <a href="#sect_head">de section</a>), peut ressembler &agrave; un passage
   <span style="font-variant: small-caps;">uniquement en petites capitales</span>,
   mais cela r&eacute;sulte g&eacute;n&eacute;ralement d'un simple
   <a href="#font_sz">changement de taille de police</a> et ne justifie pas
   qu'on le consid&egrave;re comme &eacute;tant en petites capitales.
   Le <a href="#chap_head">premier mot d'un chapitre</a> en petites capitales
   doit &ecirc;tre chang&eacute; en petites capitales altern&eacute;es sans marquage.
</p>
<p>Voyez &eacute;galement le <a href="#inline">placement des marques intra-paragraphe</a>.
</p>
<table width="100%" align="center" border="1" cellpadding="4" cellspacing="0" summary="Exemples de petites capitales">
  <tbody>
    <tr>
      <th valign="top" bgcolor="cornsilk">Image de d&eacute;part:</th>
      <th valign="top" bgcolor="cornsilk">Texte correctement format&eacute;:</th>
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
<p class="backtotop"><a href="#top">Retour au d&eacute;but</a></p>


<h3><a name="word_caps">Mots tout en majuscules</a></h3>
<p>Formatez les mots imprim&eacute;s tout en majuscules en laissant simplement ce mot tout en majuscules.
</p>
<p>Une exception &agrave; cette r&egrave;gle est le <a href="#chap_head">premier mot d'un chapitre</a>:
   certains livres anciens mettaient le premier mot de chaque chapitre en majuscule;
   ceci doit &ecirc;tre chang&eacute; en un mot normal (premi&egrave;re lettre en majuscule,
   le reste en minuscule). Donc "DEMAIN, d&egrave;s l'aube" devient "<kbd>Demain, d&egrave;s l'aube</kbd>".
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Retour au d&eacute;but</a></p>


<h3><a name="font_sz">Changement de taille de police</a></h3>
<p>Normalement nous n'indiquons pas un changement de taille de police, sauf
   lorsque la taille de la police change pour indiquer un
   <a href="#block_qt">bloc de citation</a>, ou lorsque le changement de taille
   a lieu &agrave; l'int&eacute;rieur d'un paragraphe ou d'une ligne du texte
   (voir <a href="#font_ch">Changement de police</a>).
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Retour au d&eacute;but</a></p>


<h3><a name="extra_sp">Espaces exc&eacute;dentaires entre les mots</a></h3>
<p>Il est fr&eacute;quent que l'OCR ajoute des espaces suppl&eacute;mentaires entre les mots. Il
   n'est pas n&eacute;cessaire de supprimer ces espaces &eacute;tant donn&eacute; qu'il
   est facile de le faire automatiquement lors du post-processing.
   Cependant les espaces exc&eacute;dentaires autour de la ponctuation, des tirets,
   des guillemets, etc.
   doivent &ecirc;tre supprim&eacute;s car il est difficile de le faire automatiquement.
   De plus, entre les marques <kbd>/* */</kbd> (qui pr&eacute;servent les espaces), il
   est n&eacute;cessaire de retirer tous les espaces exc&eacute;dentaires car ceux-ci ne seront
   pas retir&eacute;s automatiquement.
</p>
<p>Enfin, si vous rencontrez des caract&egrave;res de tabulation dans le texte
   vous devriez les supprimer.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Retour au d&eacute;but</a></p>


<!-- Remove this section after some transition, since it's now all handled in proofreading -->
<h3><a name="supers">Exposants</a></h3>
<p>Les livres anciens abr&eacute;geaient souvent les mots en contractions, et les
   imprimaient en exposant. Nous repr&eacute;sentons ceci en ins&eacute;rant un
   chapeau (<kbd>^</kbd>) suivi par le texte en exposant. Si le texte en exposant
   se compose de plus d'un caract&egrave;re, entourez &eacute;galement ce texte par des accolades
   <kbd>{</kbd> et <kbd>}</kbd>. Par exemple:
</p>
<table width="100%" align="center" border="1" cellpadding="4" cellspacing="0" summary="Exemple de texte en exposant">
  <tbody>
    <tr><th align="left" bgcolor="cornsilk">Image de d&eacute;part:</th></tr>
    <tr>
      <td valign="top">Gen<sup>rl</sup> Washington defeated L<sup>d</sup> Cornwall's army.</td>
    </tr>
    <tr><th align="left" bgcolor="cornsilk">Texte correctement format&eacute;:</th></tr>
    <tr>
      <td valign="top"><kbd>Gen^{rl} Washington defeated L^d Cornwall's army.</kbd></td>
    </tr>
  </tbody>
</table>
<p>Si l'exposant correspond &agrave; un appel de note, alors appliquez la r&egrave;gle pour
   les <a href="#footnotes">notes de bas de page</a> &agrave; la place.
</p>
<p>Le responsable de projet peut demander dans les <a href="#comments">commentaires de projet</a>
   que les exposants soient trait&eacute;s diff&eacute;remment pour un projet particulier.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Retour au d&eacute;but</a></p>


<!-- Remove this section after some transition, since it's now all handled in proofreading -->
<h3><a name="subscr">Texte en Indice</a></h3>
<p>On trouve la notation &ldquo;indice&rdquo; dans des ouvrages scientifiques, rarement
   ailleurs. Indiquez l'indice en le faisant pr&eacute;c&eacute;der d'un signe &ldquo;soulign&eacute;&rdquo;
   <kbd>_</kbd>, et mettez le texte en indice entre accolades <kbd>{</kbd> et <kbd>}</kbd>.
   Par exemple:
</p>
<table width="100%" align="center" border="1" cellpadding="4" cellspacing="0" summary="Exemple de texte en indice">
  <tbody>
    <tr><th align="left" bgcolor="cornsilk">Image de d&eacute;part:</th></tr>
    <tr>
      <td valign="top">H<sub>2</sub>O.</td>
    </tr>
    <tr><th align="left" bgcolor="cornsilk">Texte correctement format&eacute;:</th></tr>
    <tr>
      <td valign="top"><kbd>H_{2}O.</kbd></td>
    </tr>
  </tbody>
</table>
<!-- END RR -->
<p class="backtotop"><a href="#top">Retour au d&eacute;but</a></p>


<h3><a name="page_ref">R&eacute;f&eacute;rences aux pages &ldquo;cf. p. 123&rdquo;</a></h3>
<p>Laissez ces r&eacute;f&eacute;rences telles qu'elles apparaissent dans l'image,
   par exemple <kbd>(cf. p. 123)</kbd>.
</p>
<p>V&eacute;rifiez les <a href="#comments">Commentaires de Projet</a>, au cas o&ugrave; le
   responsable de projet prescrive autre chose pour les r&eacute;f&eacute;rences aux pages.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Retour au d&eacute;but</a></p>


<table width="100%" border="0" cellspacing="0" cellpadding="6" summary="Formatage &agrave; l'&eacute;chelle du paragraphe">
  <tbody>
    <tr>
      <td bgcolor="silver"><h2 style="margin-bottom: 0; margin-top: 0;">Formatage &agrave; l'&eacute;chelle du paragraphe:</h2></td>
    </tr>
  </tbody>
</table>


<h3><a name="chap_head">T&ecirc;tes de chapitre</a></h3>
<p>Formatez les t&ecirc;tes de chapitre tels qu'elles apparaissent dans l'image.
   Une t&ecirc;te de chapitre commence en g&eacute;n&eacute;ral plus bas sur la page qu'un en-t&ecirc;te de
   page et n'a pas de num&eacute;ro de page sur la m&ecirc;me ligne. Les t&ecirc;tes de
   chapitre sont souvent imprim&eacute;es enti&egrave;rement en majuscules: si c'est le cas,
   laissez-les ainsi. Marquez les <a href="#italics">italiques</a> ou les
   <a href="#small_caps">Petites Capitales</a> <b>altern&eacute;es</b> pr&eacute;sentes &agrave; l'image.
</p>
<p>Introduisez 4 lignes blanches avant le &ldquo;CHAPITRE XXX&rdquo; (ins&eacute;rez ces lignes
   blanches m&ecirc;me si le chapitre d&eacute;marre sur une nouvelle page; il n'y a pas de
   pages sur un livre &eacute;lectronique, donc les lignes blanches sont n&eacute;cessaires).
   Laissez ensuite une ligne blanche entre chaque partie de la t&ecirc;te du chapitre,
   comme la description du chapitre, ou une citation en ouverture, etc. et laissez
   ensuite 2 lignes blanches entre la t&ecirc;te et le d&eacute;but du texte du chapitre.
</p>
<p>Dans les livres anciens, le ou les premiers mots de chaque chapitre &eacute;taient
   souvent imprim&eacute;s en majuscules ou en petites capitales; changez-les en majuscule
   et minuscule (en ne mettant la majuscule qu'&agrave; la premi&egrave;re lettre du chapitre).
</p>
<p>On a souvent l'impression que les lettres d'une t&ecirc;te de chapitre sont en gras ou bien espac&eacute;es,
   mais cela est g&eacute;n&eacute;ralement d&ucirc; &agrave; une police ou une taille particuli&egrave;re pour le
   titre, et <b>ne doit pas &ecirc;tre marqu&eacute;</b> comme &eacute;tant du gras ou du texte espac&eacute;.
   Les lignes blanches suppl&eacute;mentaires suffisent &agrave; indiquer qu'il s'agit d'un
   titre, ce n'est pas la peine d'en rajouter et de marquer en plus le changement
   de police de caract&egrave;re. Voir le premier exemple ci-dessous.
</p>
<!-- END RR -->

<table width="100%" align="center" border="1" cellpadding="4" cellspacing="0" summary="Exemple de t&ecirc;te de chapitre">
 <tbody>
    <tr><th align="left" bgcolor="cornsilk">Image de d&eacute;part:</th></tr>
    <tr align="left">
      <td width="100%" valign="top"> <img src="../chap1.png" alt="" width="500" height="725"><br>
      </td>
    </tr>
    <tr><th align="left" bgcolor="cornsilk">Texte correctement format&eacute;:</th></tr>
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
<table width="100%" align="center" border="1" cellpadding="4" cellspacing="0" summary="Exemple de t&ecirc;te de chapitre">
  <tbody>
    <tr><th align="left" bgcolor="cornsilk">Image de d&eacute;part:</th></tr>
    <tr align="left">
      <td width="100%" valign="top"><img src="../foot.png" alt="" width="500" height="860"><br>
      </td>
    </tr>
    <tr><th align="left" bgcolor="cornsilk">Texte correctement format&eacute;:</th></tr>
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
<p class="backtotop"><a href="#top">Retour au d&eacute;but</a></p>


<h3><a name="sect_head">T&ecirc;tes de section</a></h3>
<p>Dans certains livres, les chapitres sont divis&eacute;s en sections. Formatez les
   t&ecirc;tes de section comme elles sont imprim&eacute;es. Laissez deux lignes blanches avant
   cette t&ecirc;te, et une apr&egrave;s (&agrave; moins que le responsable de projet en ait d&eacute;cid&eacute;
   autrement). Si vous ne savez pas si un titre d&eacute;marre un chapitre ou une
   section, demandez dans le <a href="#forums">forum du projet</a>, en pr&eacute;cisant le
   num&eacute;ro de page.
</p>
<p>Marquez les <a href="#italics">italiques</a> ou les
   <a href="#small_caps">Petites Capitales</a> <b>altern&eacute;es</b> pr&eacute;sentes &agrave; l'image.
   On a souvent l'impression que les lettres d'une t&ecirc;te de section
   sont en gras ou bien espac&eacute;es,
   mais cela est g&eacute;n&eacute;ralement d&ucirc; &agrave; une police ou une taille particuli&egrave;re pour le
   titre et <b>ne doit pas &ecirc;tre marqu&eacute;</b> comme gras ou espac&eacute;.
   Le ligne blanche suppl&eacute;mentaire suffit &agrave; indiquer qu'il s'agit d'un
   titre, ce n'est pas la peine d'en rajouter et de marquer en plus le changement
   de police de caract&egrave;re.
</p>
<!-- END RR -->
<table width="100%" align="center" border="1" cellpadding="4" cellspacing="0" summary="Exemple de t&ecirc;te de section">
  <tbody>
    <tr>
      <th align="left" bgcolor="cornsilk">Image de d&eacute;part:</th>
    </tr>
    <tr align="left">
      <td width="100%" valign="top"> <img src="../section.png" alt="" width="500" height="283"> <br>
      </td>
    </tr>
    <tr>
      <th align="left" bgcolor="cornsilk">Texte correctement format&eacute;:</th>
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
<p class="backtotop"><a href="#top">Retour au d&eacute;but</a></p>


<h3><a name="maj_div">Autres divisions dans les textes</a></h3>
<p>Les autres grandes divisions des textes (Pr&eacute;face, Avant-propos, Introduction,
   Prologue, &Eacute;pilogue, Annexe, R&eacute;f&eacute;rences, Conclusion, Glossaire, R&eacute;sum&eacute;,
   Remerciements, Bibliographie, etc.) seront trait&eacute;s comme des <a href="#chap_head">t&ecirc;tes de
   chapitre</a>, c'est-&agrave;-dire quatre lignes blanches avant la t&ecirc;te, et deux avant le d&eacute;but du
   texte.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Retour au d&eacute;but</a></p>


<!-- Remove this section after some transition, since it's now all handled in proofreading -->
<h3><a name="para_space">Espacement/Indentation des paragraphes</a></h3>
<p>Mettez une ligne blanche avant tout d&eacute;but de paragraphe, m&ecirc;me si ce
   paragraphe d&eacute;marre en haut d'une page. N'indentez pas le d&eacute;but des paragraphes
   (Mais si les paragraphes sont d&eacute;j&agrave; indent&eacute;s, ne prenez pas la peine
   d'enlever les espaces en trop&mdash;cela peut &ecirc;tre fait facilement &agrave; la phase de
   post-processing).
</p>
<p>Voyez l'exemple de la section des <a href="#chap_head">t&ecirc;tes de chapitre</a>.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Retour au d&eacute;but</a></p>


<h3><a name="extra_s">Interruption du fil du discours (Espace suppl&eacute;mentaire, ast&eacute;risques, ligne d&eacute;corative entre les paragraphes)</a></h3>
<p>La plupart des livres commencent un paragraphe imm&eacute;diatement apr&egrave;s la fin du
   paragraphe pr&eacute;c&eacute;dent. Mais il peut arriver que deux paragraphes soient
   s&eacute;par&eacute;s par une rang&eacute;e d'&eacute;toiles, une ligne
   de tirets, ou autres caract&egrave;res, une ligne droite simple ou d&eacute;cor&eacute;e
   ou m&ecirc;me simplement un espace vertical de la hauteur d'une ou deux lignes blanches.
</p>
<p>Ceci est utilis&eacute; pour exprimer une pause, un changement de sc&egrave;ne ou de
   sujet, l'&eacute;coulement du temps, ou pour cr&eacute;er un peu de suspense. Ceci
   refl&egrave;te l'intention de l'auteur, donc nous pr&eacute;servons ces lignes en
   ins&eacute;rant une ligne vierge suivie de <kbd>&lt;tb&gt;</kbd>, puis une autre ligne
   vierge.
</p>
<p>Parfois, les imprimeurs ajoutent une ligne d&eacute;corative en fin de chapitre ou
   de section. Ce ne sont pas des pauses telles que d&eacute;crites ici, il <b>ne faut
   pas</b> utiliser <kbd>&lt;tb&gt;</kbd> pour ces d&eacute;corations.
</p>
<p>V&eacute;rifiez les <a href="#comments">commentaires de projet</a> car certains
   responsables de projet peuvent demander de faire la
   diff&eacute;rence entre les diff&eacute;rents types de pause, par exemple en notant
   par <kbd>&lt;tb stars&gt;</kbd> une rang&eacute;e d'ast&eacute;risques.
</p>
<!-- END RR -->
<table width="100%" align="center" border="1" cellpadding="4" cellspacing="0" summary="Exemple de pause">
  <tbody>
    <tr><th align="left" bgcolor="cornsilk">Image de d&eacute;part:</th></tr>
    <tr align="left">
      <td width="100%" valign="top"> <img src="../tbreak.png" alt="" width="500" height="264"> <br>
      </td>
    </tr>
    <tr><th align="left" bgcolor="cornsilk">Texte correctement format&eacute;:</th></tr>
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
<p class="backtotop"><a href="#top">Retour au d&eacute;but</a></p>


<h3><a name="illust">Illustrations</a></h3>
<p>Le texte d'une illustration (l&eacute;gende) doit &ecirc;tre entour&eacute; de <kbd>[Illustration:&nbsp;le texte]</kbd>.
   Gardez la l&eacute;gende comme elle est imprim&eacute;e, avec ses retours &agrave; la
   ligne, italiques, etc.
   Des indications comme &ldquo;Voir page 66&rdquo;, ou un titre faisant partie
   de l'illustration, doivent &ecirc;tre compris dans la l&eacute;gende.
</p>
<p>S'il n'y a pas de l&eacute;gende, indiquez juste <kbd>[Illustration]</kbd> &agrave; l'endroit
   o&ugrave; elle se trouve. (Dans ce cas n'oubliez pas de retirer le deux-points et l'espace
   avant le crochet fermant.)
</p>
<p>Si l'illustration est au milieu d'un paragraphe ou sur le c&ocirc;t&eacute;,
   d&eacute;placez la marque d'illustration soit au-dessus, soit
   en-dessous du paragraphe, et mettez une ligne blanche avant ou apr&egrave;s la marque
   d'illustration pour la s&eacute;parer du texte du paragraphe. Rejoignez les deux bouts
   du paragraphe qui &eacute;taient s&eacute;par&eacute;s par l'illustration en effa&ccedil;ant l'espace ainsi cr&eacute;&eacute;.
</p>
<p>Si le paragraphe coup&eacute; par l'illustration prend toute la page, ajoutez un
   ast&eacute;risque comme ceci: <kbd>*[Illustration: <font color="red">(texte de l'illustration)</font>]</kbd>,
   mettez l'illustration tout en haut de la page, et laissez une ligne blanche apr&egrave;s.
</p>
<!-- END RR -->

<table width="100%" align="center" border="1" cellpadding="4" cellspacing="0" summary="Exemple d'illustration">
  <tbody>
    <tr>
      <th align="left" bgcolor="cornsilk">Image de d&eacute;part:</th>
    </tr>
    <tr align="left">
      <td width="100%" valign="top"> <img src="../illust.png" alt="" width="500" height="525"> <br>
      </td>
    </tr>
    <tr>
      <th align="left" bgcolor="cornsilk">Texte correctement format&eacute;:</th>
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
<table width="100%" align="center" border="1" cellpadding="4" cellspacing="0" summary="Illustration au milieu d'un paragraphe">
  <tbody>
    <tr>
      <th align="left" bgcolor="cornsilk">Image de d&eacute;part: (Illustration au milieu d'un paragraphe)</th>
    </tr>
    <tr align="left">
      <td width="100%" valign="top"> <img src="../illust2.png" alt="" width="500" height="514"> <br>
      </td>
    </tr>
    <tr>
      <th align="left" bgcolor="cornsilk">Texte correctement format&eacute;:</th>
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
<p class="backtotop"><a href="#top">Retour au d&eacute;but</a></p>


<h3><a name="footnotes">Notes de bas de page et de fin</a></h3>
<p>Les notes de bas de page sont format&eacute;es en laissant le texte de la
   note l&agrave; o&ugrave; il se trouve en bas de la page, et en pla&ccedil;ant une marque
   dans le texte, l&agrave; o&ugrave; la note est r&eacute;f&eacute;renc&eacute;e.
</p>
<p>1. Dans le texte principal, le caract&egrave;re qui marque l'appel de note doit
   &ecirc;tre entour&eacute; de crochets <kbd>[</kbd> et <kbd>]</kbd> et plac&eacute; sans espace
   imm&eacute;diatement &agrave; c&ocirc;t&eacute; du mot sur lequel porte la
   note<kbd>[1]</kbd> ou &agrave; c&ocirc;t&eacute; de son signe de ponctuation,<kbd>[2]</kbd>
   comme dans l'image et dans les deux exemples de cette phrase.
   Les appels de notes peuvent &ecirc;tre des lettres, des chiffres ou
   des symboles sp&eacute;ciaux.
   Quand les notes sont marqu&eacute;es par un symbole ou une s&eacute;rie de symboles
   (*, &dagger;, &Dagger;, &sect;, etc.), nous rempla&ccedil;ons ces signes par
   des lettres majuscules dans l'ordre alphab&eacute;tique (A, B, C, etc.).
</p>
<p>2. Au bas de la page, la note est entour&eacute;e par la marque de note
   <kbd>[Footnote #:&nbsp;</kbd><font color="red">(texte de la note)</font><kbd>]</kbd>,
   avec le texte de la note entre les deux crochets, et le num&eacute;ro (ou la lettre)
   de la note &agrave; la place du signe #. Formatez le texte de la note tel qu'il est
   imprim&eacute;, avec ses retours &agrave; la ligne, italiques, etc.
   Utilisez bien le m&ecirc;me num&eacute;ro (ou la m&ecirc;me lettre) dans la note et dans le texte (l&agrave;
   o&ugrave; la note est r&eacute;f&eacute;renc&eacute;e).
   Placez chaque note sur une ligne s&eacute;par&eacute;e, dans l'ordre d'apparition,
   avec une ligne blanche avant chaque note.
</p>
<!-- END RR -->

<p>Si une note est interrompue &agrave; la fin de la page,
   laissez la note &agrave; la fin de la page, et mettez une ast&eacute;risque <kbd>*</kbd>
   l&agrave; o&ugrave; la note s'arr&ecirc;te, comme ceci:
   <kbd>[Footnote 1: </kbd><font color="red">(texte de la note)</font><kbd>]*</kbd>.
   Le <kbd>*</kbd> indique que la note s'arr&ecirc;te
   pr&eacute;matur&eacute;ment, et attire l'attention du post-processeur qui fusionnera les deux
   parties de la note.
</p>
<p>Si le bas d'une page contient une note commenc&eacute;e sur une page pr&eacute;c&eacute;dente,
   laissez le fragment de note au bas de la page et entourez-le par
   <kbd>*[Footnote: <font color="red">(texte de la note)</font>]</kbd> (sans marque ou
   num&eacute;ro de note). L'ast&eacute;risque indique que c'est une continuation de note, et attire
   l'attention du post-processeur.
</p>
<p>Si une note fragment&eacute;e sur plusieurs pages commence ou s'arr&ecirc;te sur un mot
   coup&eacute;, marquez le mot coup&eacute; <b>et</b> la note par une &eacute;toile, comme ceci:<br>
   <kbd>[Footnote 1: Cette note se poursuit et son dernier mot se poursuit &eacute;ga-*]*</kbd><br>
   pour le premier fragment, et<br>
   <kbd>*[Footnote: *lement sur la page suivante.]</kbd><br>
   pour le second fragment.
</p>
<p>N'incluez pas les &eacute;ventuelles lignes horizontales s&eacute;parant les notes du texte principal.
</p>
<p>Les <b>notes de fin</b> sont simplement des notes de bas de page qui ont &eacute;t&eacute;
   plac&eacute;es en fin de chapitre, ou en fin de livre, au lieu d'&ecirc;tre en bas de page.
   Formatez-les comme des notes de bas de page. Quand vous voyez la r&eacute;f&eacute;rence dans le
   texte, entourez-la par des crochets <kbd>[</kbd> et <kbd>]</kbd>.
   Si vous formatez une des pages de fin, l&agrave; o&ugrave; sont les notes,
   entourez le texte de chaque note par
   <kbd>[Footnote #: <font color="red">(texte de la note de fin)</font>]</kbd>,
   avec le num&eacute;ro ou la marque de la note &agrave; la place du signe <kbd>#</kbd>.
   Mettez une ligne blanche avant chaque note, pour qu'elles restent des paragraphes
   s&eacute;par&eacute;s lorsque le texte est remis en forme par le post-processeur.
</p>
<!-- Need an example of Endnotes, maybe? Good idea!-->

<p>Les notes dans les <a href="#tables">Tableaux</a> conservent l'emplacement
   qu'elles ont dans l'image.
</p>

<table width="100%" border="1" cellpadding="4" cellspacing="0" align="center" summary="Exemple de note en bas de page">
  <tbody>
    <tr>
      <th valign="top" align="left" bgcolor="cornsilk">Image de d&eacute;part:</th>
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
    <tr><th align="left" bgcolor="cornsilk">Texte correctement format&eacute;:</th></tr>
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
<table width="100%" align="center" border="1" cellpadding="4" cellspacing="0" summary="Exemple de note de bas de page">
  <tbody>
    <tr><th align="left" bgcolor="cornsilk">Image de d&eacute;part:</th></tr>
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
    <tr><th align="left" bgcolor="cornsilk">Texte correctement format&eacute;:</th></tr>
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
<p class="backtotop"><a href="#top">Retour au d&eacute;but</a></p>


<h3><a name="para_side">Commentaires en marge</a></h3>
<p>Certains livres ont de petites descriptions des paragraphes sur le c&ocirc;t&eacute; du
   texte. Ce sont les notes en marge (&ldquo;Sidenotes&rdquo;). D&eacute;placez ces notes juste au-dessus du paragraphe
   auquel elles se r&eacute;f&egrave;rent. Une note en marge est entour&eacute;e par les marques
   suivantes: <kbd>[Sidenote:&nbsp;</kbd> avant et <kbd>]</kbd> apr&egrave;s. Formatez la note
   pour qu'elle ressemble au texte imprim&eacute;, en gardant les retours &agrave; la ligne, les
   italiques, etc. (tout en traitant les tirets et traits d'union normalement).
   Laissez une ligne blanche avant et apr&egrave;s la note, pour la s&eacute;parer du texte ordinaire.
</p>
<p>S'il y a plusieurs notes pour un m&ecirc;me paragraphe, mettez-les l'une apr&egrave;s
   l'autre au d&eacute;but du paragraphe. S&eacute;parez-les par des lignes blanches.
</p>
<p>Si le paragraphe a commenc&eacute; sur une page pr&eacute;c&eacute;dente, mettez la note en haut
   de la page et marquez-la avec une ast&eacute;risque <kbd>*</kbd> de mani&egrave;re &agrave; ce que
   le post-processeur puisse voir qu'elle appartient &agrave; la page pr&eacute;c&eacute;dente, ainsi:
   <kbd>*[Sidenote: <font color="red">(texte de la note)</font>]</kbd>. Le post-processeur
   d&eacute;placera la note &agrave; l'endroit appropri&eacute;.
</p>
<p>Parfois, le responsable de projet vous demandera de placer la note &agrave; c&ocirc;t&eacute; de la
   phrase &agrave; laquelle elle s'applique, et pas au d&eacute;but ou &agrave; la fin du paragraphe.
   Dans ce cas, ne les s&eacute;parez pas par des lignes blanches.
</p>
<!-- END RR -->

<table width="100%" align="center" border="1" cellpadding="4" cellspacing="0" summary="Exemple de notes en marge">
  <tbody>
    <tr valign="top">
      <th align="left" bgcolor="cornsilk">Image de d&eacute;part:</th>
    </tr>
    <tr valign="top">
      <td width="100%" align="left"><img src="../side.png" alt="" width="550" height="800"><br>
      </td>
    </tr>
    <tr valign="top">
      <th align="left" bgcolor="cornsilk">Texte correctement format&eacute;:</th>
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
<p class="backtotop"><a href="#top">Retour au d&eacute;but</a></p>


<h3><a name="outofline">Formatage hors texte</a></h3>
<p>Les marques de formatage hors texte sont les marques <kbd>/#</kbd> <kbd>#/</kbd> et <kbd>/*</kbd> <kbd>*/</kbd>.
   Les marques <kbd>/#</kbd> <kbd>#/</kbd> (changement de mise en forme) indiquent
   du texte imprim&eacute; diff&eacute;remment, mais pour lequel on ne conserve pas les sauts de ligne
   lors de l'assemblage final. (Les paragraphes seront remis en forme en fonction
   de la largeur de ligne du r&eacute;sultat final).
   Les marques <kbd>/*</kbd> <kbd>*/</kbd> (pas de mise en forme) indiquent du texte qui doit
   conserver ses sauts de ligne, indentation et espacement lors de l'assemblage final.
</p>
<p>&Agrave; chaque fois que vous utilisez une marque de d&eacute;but sur une page, veillez &agrave; inclure &eacute;galement
   une marque de fin dans la m&ecirc;me page. Lors de l'assemblage, chaque marque sera retir&eacute;e ainsi
   que totalit&eacute; de la ligne sur laquelle elle est plac&eacute;e.
   Pour cette raison, laissez une ligne vide entre le texte ordinaire et
   la marque de d&eacute;but, et de m&ecirc;me entre la marque de fin et le texte ordinaire.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Retour au d&eacute;but</a></p>


<h3><a name="block_qt">Blocs de citations</a></h3>
<p>Les blocs de citations sont des blocs de texte (g&eacute;n&eacute;ralement plusieurs
   lignes, parfois m&ecirc;me plusieurs pages) qui se distinguent du texte pr&eacute;c&eacute;dent et suivant
   par de plus grandes marges, une police de caract&egrave;res plus petite, une
   indentation diff&eacute;rente, ou d'autres diff&eacute;rences. Entourez les blocs de citations
   par les marques <kbd>/#</kbd> avant et <kbd>#/</kbd>
   apr&egrave;s.
   Voyez les d&eacute;tails &agrave; la section <a href="#outofline">Formatage hors texte</a>.
</p>
<p>Except&eacute; l'ajout de ces marques, les blocs de citations sont format&eacute;s comme
   le reste du texte.
</p>
<!-- END RR -->

<table width="100%" align="center" border="1" cellpadding="4" cellspacing="0" summary="Exemple de bloc de citation">
  <tbody>
    <tr><th align="left" bgcolor="cornsilk">Image de d&eacute;part:</th></tr>
    <tr align="left">
      <td width="100%" valign="top"> <img src="../bquote.png" alt="" width="500" height="475"><br>
      </td>
    </tr>
    <tr><th align="left" bgcolor="cornsilk">Texte correctement format&eacute;:</th></tr>
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
<p class="backtotop"><a href="#top">Retour au d&eacute;but</a></p>


<h3><a name="lists">Listes de choses</a></h3>
<p>Entourez les listes par les marques <kbd>/*</kbd> et <kbd>*/</kbd>.
   Voyez les d&eacute;tails &agrave; la section <a href="#outofline">Formatage hors texte</a>.
</p>
<!-- END RR -->

<table width="100%" align="center" border="1" cellpadding="4" cellspacing="0" summary="Exemple de liste">
  <tbody>
    <tr><th align="left" bgcolor="cornsilk">Image de d&eacute;part:</th></tr>
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
    <tr><th align="left" bgcolor="cornsilk">Texte correctement format&eacute;:</th></tr>
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
<p class="backtotop"><a href="#top">Retour au d&eacute;but</a></p>


<h3><a name="tables">Tableaux</a></h3>
<p>Entourez les tableaux par les marques <kbd>/*</kbd> et <kbd>*/</kbd>.
   Voyez les d&eacute;tails &agrave; la section <a href="#outofline">Formatage hors texte</a>.
   Formatez les tableaux avec des espaces (<b>pas de tabulation</b>)
   de mani&egrave;re &agrave; ce qu'ils ressemblent approximativement au tableau original.
   Essayez si possible d'&eacute;viter de cr&eacute;er des tableaux excessivement larges; en g&eacute;n&eacute;ral le mieux
   est de limiter la largeur de ligne &agrave; moins de 75 caract&egrave;re.
</p>
<p>Pour aligner les champs, n'utilisez pas de tabulations, seulement des espaces.
   En effet les tabulations s'affichent diff&eacute;remment selon les ordinateurs.
   Retirez les rang&eacute;es de points ou d'autre ponctuation (filets de conduite) utilis&eacute;s pour
   aligner les objets.
</p>
<p>S'il est n&eacute;cessaire d'utiliser du marquage intra-paragraphe (italique, gras, etc.),
   marquez chaque case du tableau ind&eacute;pendamment des autres.
   En alignant le texte, ayez en t&ecirc;te que les marques intra-paragraphe appara&icirc;tront
   diff&eacute;remment dans la version texte finale. Par exemple, les marques
   <kbd>&lt;i&gt;</kbd>italique<kbd>&lt;/i&gt;</kbd> deviennent normalement un
   caract&egrave;re <kbd>_</kbd>soulign&eacute;<kbd>_</kbd>, et la plupart des autres marques
   intra-paragraphe seront trait&eacute;es de fa&ccedil;on similaire. &Agrave; l'inverse, le
   marquage des <kbd>&lt;sc&gt;</kbd>Petites Capitales<kbd>&lt;/sc&gt;</kbd>
   est retir&eacute; compl&egrave;tement.
</p>
<p>Il est souvent difficile de formater des tableaux en texte brut; faites
   de votre mieux. Utilisez une police de caract&egrave;res &agrave; espacement fixe,
   comme <a href="../font_sample.php">DPCustomMono</a> ou Courier.
   Le but est toujours de pr&eacute;server ce que l'auteur a voulu dire, tout en produisant
   un texte &eacute;lectronique lisible. Il faudra parfois abandonner le format original
   du tableau. Regardez les <a href="#comments">commentaires de projet</a>, et le forum du projet:
   d'autres formateurs se sont peut-&ecirc;tre mis d'accord sur un format sp&eacute;cifique.
   Vous trouverez peut-&ecirc;tre des mod&egrave;les utiles
   dans la <a href="<?php echo $Gallery_of_Table_Layouts_URL; ?>">Galerie de tableaux</a> sur
   le forum.
</p>
<p>Les <b>notes de bas de page</b> dans les tableaux conservent l'emplacement
   qu'elles ont dans l'image. Voyez la section sur les
   <a href="#footnotes">notes de bas de page</a> pour plus de d&eacute;tails.
</p>
<!-- END RR -->
<table width="100%" align="center" border="1" cellpadding="4" cellspacing="0" summary="Exemple de tableau">
  <tbody>
    <tr><th align="left" bgcolor="cornsilk">Image de d&eacute;part:</th></tr>
    <tr align="left">
      <td width="100%" valign="top"> <img src="../table2.png" alt="" width="500" height="304"><br>
      </td>
    </tr>
    <tr><th align="left" bgcolor="cornsilk">Texte correctement format&eacute;:</th></tr>
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
<table width="100%" align="center" border="1" cellpadding="4" cellspacing="0" summary="Exemple de tableau">
  <tbody>
    <tr><th align="left" bgcolor="cornsilk">Image de d&eacute;part:</th></tr>
    <tr align="left">
      <td width="100%" valign="top"> <img src="../table3.png" alt="" width="480" height="231"><br>
      </td>
    </tr>
    <tr><th align="left" bgcolor="cornsilk">Texte correctement format&eacute;:</th></tr>
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
<p class="backtotop"><a href="#top">Retour au d&eacute;but</a></p>


<h3><a name="poetry">Po&eacute;sie/&Eacute;pigrammes</a></h3>
<p>Marquez les po&eacute;sies ou les &eacute;pigrammes avec <kbd>/*</kbd> <kbd>*/</kbd>
   de fa&ccedil;on &agrave; pr&eacute;server les espaces et les retours &agrave; la ligne.
   Voyez les d&eacute;tails &agrave; la section <a href="#outofline">Formatage hors texte</a>.
</p>
<p>Pr&eacute;servez l'indentation relative des vers les uns par rapport aux autres, en
   ajoutant 2, 4, 6 espaces (ou plus) avant le d&eacute;but du vers de fa&ccedil;on &agrave; ressembler
   au texte imprim&eacute;.
   Ne passez pas votre temps &agrave; centrer les lignes de po&eacute;sie, m&ecirc;me si elles sont
   centr&eacute;es sur la page originale. Calez le texte sur la marge de gauche (en
   indentant les vers les uns par rapport aux autres si n&eacute;cessaire).
</p>
<p>Quand un vers est trop long pour la page, la plupart des &eacute;ditions cassent le
   vers et impriment la fin sur une ligne s&eacute;par&eacute;e, vers la marge de droite. Dans
   ces cas-l&agrave;, rejoignez les deux parties du vers de fa&ccedil;on &agrave; ne former qu'une
   ligne. Les lignes de continuation commencent souvent par une minuscule, et
   apparaissent irr&eacute;guli&egrave;rement alors que l'indentation normale appara&icirc;t
   r&eacute;guli&egrave;rement au cours du po&egrave;me.
</p>
<p>Une rang&eacute;e de points pr&eacute;sente dans un po&egrave;me est trait&eacute;e comme une
   <a href="#extra_s">interruption du fil du discours</a> <kbd>&lt;tb&gt;</kbd>.
</p>
<p>Les <a href="#line_no">Num&eacute;ros de ligne</a> sont conserv&eacute;s.
</p>
<p>Regardez les <a href="#comments">commentaires de projet</a> pour des instructions
   sp&eacute;cifiques. Il peut souvent arriver, au choix du responsable du projet, que
   certaines des pr&eacute;sentes r&egrave;gles ne s'appliquent pas &agrave; un livre constitu&eacute;
   majoritairement ou enti&egrave;rement de po&eacute;sie.
</p>
<!-- END RR -->

<table width="100%" align="center" border="1" cellpadding="4" cellspacing="0" summary="Exemple de po&eacute;sie">
  <tbody>
    <tr><th align="left" bgcolor="cornsilk">Image de d&eacute;part:</th></tr>
    <tr align="left">
      <td width="100%" valign="top"> <img src="../poetry.png" alt="" width="500" height="508"> <br>
      </td>
    </tr>
    <tr><th align="left" bgcolor="cornsilk">Texte correctement format&eacute;:</th></tr>
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
<p class="backtotop"><a href="#top">Retour au d&eacute;but</a></p>


<h3><a name="line_no">Num&eacute;ros de ligne</a></h3>
<p>Il y a souvent des num&eacute;ros de lignes dans les livres de po&eacute;sie, situ&eacute;s en g&eacute;n&eacute;ral
   dans la marge tous les 5 ou 10 vers.
   Gardez les num&eacute;ros de ligne. Placez-les au moins 6 espaces &agrave; droite du texte,
   en fin de ligne, m&ecirc;me si, sur l'image, ces num&eacute;ros sont &agrave; gauche.
   Puisque, dans la po&eacute;sie, les retours &agrave; la ligne seront conserv&eacute;s dans le livre
   &eacute;lectronique final, les num&eacute;ros de ligne seront utiles au lecteur.
</p>
<!-- END RR -->
<!-- We need an example image and text for this. -->
<p class="backtotop"><a href="#top">Retour au d&eacute;but</a></p>


<h3><a name="letter">Lettres (courrier)</a></h3>
<p>Formatez les lettres comme vous le feriez pour des <a href="#para_space">paragraphes</a>.
   Ins&eacute;rez une ligne blanche avant le d&eacute;but de la lettre. Ne reproduisez aucune
   indentation.
</p>
<p>Entourez en-t&ecirc;tes et les fins de lettres (adresse, date, salutations, signature) par
   les marques <kbd>/*</kbd> et <kbd>*/</kbd>.
   Voyez les d&eacute;tails &agrave; la section <a href="#outofline">Formatage hors texte</a>.
</p>
<p>N'indentez pas les lignes d'en-t&ecirc;te et de fin de lettres, m&ecirc;me si,
   sur l'original, elles sont indent&eacute;es ou justifi&eacute;es &agrave; droite.
   Calez-les sur la marge de gauche. Le post-processeur les formatera correctement.
</p>
<p>Si la correspondance est imprim&eacute;e diff&eacute;remment du reste du texte,
   voyez la section des <a href="#block_qt">blocs de citation</a>.
</p>
<!-- END RR -->

<table width="100%" align="center" border="1" cellpadding="4" cellspacing="0" summary="Exemple de lettre">
  <tbody>
    <tr><th align="left" bgcolor="cornsilk">Image de d&eacute;part:</th></tr>
    <tr align="left">
      <td width="100%" valign="top"> <img src="../letter.png" alt="" width="500" height="217"> <br>
      </td>
    </tr>
    <tr><th align="left" bgcolor="cornsilk">Texte correctement format&eacute;:</th></tr>
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
<table width="100%" align="center" border="1" cellpadding="4" cellspacing="0" summary="Exemple de lettre">
  <tbody>
    <tr><th align="left" bgcolor="cornsilk">Image de d&eacute;part:</th></tr>
    <tr align="left">
      <td width="100%" valign="top"> <img src="../letter2.png" alt="" width="500" height="271"> <br>
      </td>
    </tr>
    <tr><th align="left" bgcolor="cornsilk">Texte correctement format&eacute;:</th></tr>
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
<p class="backtotop"><a href="#top">Retour au d&eacute;but</a></p>


<h3><a name="r_align">Texte align&eacute; &agrave; droite</a></h3>
<p>Entourez les lignes de texte align&eacute; &agrave; droite par les marques
   <kbd>/*</kbd> et <kbd>*/</kbd>.
   Voyez les d&eacute;tails de ces marques &agrave; la section
   <a href="#outofline">Formatage hors texte</a>, ainsi que les
   exemples de la section <a href="#letter">Lettres (courrier)</a>.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Retour au d&eacute;but</a></p>


<table width="100%" border="0" cellspacing="0" cellpadding="6" summary="Formatage &agrave; l'&eacute;chelle de la page">
  <tbody>
    <tr>
      <td bgcolor="silver"><h2 style="margin-bottom: 0; margin-top: 0;">Formatage &agrave; l'&eacute;chelle de la page:</h2></td>
    </tr>
  </tbody>
</table>


<h3><a name="blank_pg">Page blanche</a></h3>
<p>Veuillez mettre comme texte <kbd>[Blank Page]</kbd> si le texte et l'image sont vides.
</p>
<p>Si le texte seulement est pr&eacute;sent et que l'image est blanche, ou s'il
   y a du texte sur la page mais aucun texte dans le cadre &eacute;ditable, suivez la
   proc&eacute;dure indiqu&eacute;es dans le cas d'une <a href="#bad_image">mauvaise image</a>
   ou d'un <a href="#bad_text">mauvais texte</a>.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Retour au d&eacute;but</a></p>


<h3><a name="title_pg">Page de titre/fin</a></h3>
<p>Laissez tout comme c'est imprim&eacute;, m&ecirc;me si c'est tout en majuscules, ou en
   majuscules et minuscules. Gardez la date de copyright ou de publication.
</p>
<p>Certains livres, souvent, mettent la premi&egrave;re lettre
   grande et orn&eacute;e. Tapez simplement la lettre.
</p>
<!-- END RR -->

<table width="100%" align="center" border="1" cellpadding="4" cellspacing="0" summary="Exemple de page de titre">
  <tbody>
    <tr>
      <th align="left" bgcolor="cornsilk">Image de d&eacute;part:</th>
    </tr>
    <tr align="left">
      <td width="100%" valign="top"><img src="../title.png" width="500" height="520" alt=""><br>
      </td>
    </tr>
    <tr>
      <th align="left" bgcolor="cornsilk">Texte correctement format&eacute;:</th>
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
<p class="backtotop"><a href="#top">Retour au d&eacute;but</a></p>


<h3><a name="toc">Table des mati&egrave;res</a></h3>
<p>Formatez le la table des mati&egrave;res comme elle est imprim&eacute;e, que ce soit
   tout en capitales, petites capitales, minuscules, etc. et entourez-la
   avec <kbd>/*</kbd> et <kbd>*/</kbd>.
   Voyez les d&eacute;tails sur ces marques &agrave; la section
   <a href="#outofline">Formatage hors texte</a>.
</p>
<p>Les r&eacute;f&eacute;rences des num&eacute;ros de page doivent &ecirc;tre plac&eacute;es
   <b>au moins 6 espaces</b> apr&egrave;s le texte, en fin de ligne.
   Enlevez les rang&eacute;es de points ou d'autre ponctuation qui forment des lignes horizontales
   (points de conduite), entre le texte et le num&eacute;ro.
</p>
<!-- END RR -->

<table width="100%" align="center" border="1" cellpadding="4" cellspacing="0" summary="Exemple de table des mati&egrave;res">
  <tbody>
    <tr>
      <th align="left" bgcolor="cornsilk">Exemple d'image:</th>
    </tr>
    <tr align="left">
      <td width="100%" valign="top"><img src="../tablec.png" alt="" width="500" height="650"></td>
    </tr>
    <tr>
      <th align="left" bgcolor="cornsilk">Texte correctement format&eacute;:</th>
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
<p class="backtotop"><a href="#top">Retour au d&eacute;but</a></p>


<h3><a name="bk_index">Index</a></h3>
<p>Entourez l'index avec les marques <kbd>/*</kbd> et <kbd>*/</kbd>.
   Voyez les d&eacute;tails sur ces marques &agrave; la section <a href="#outofline">Formatage hors texte</a>.
   N'alignez pas les num&eacute;ros les uns sur les autres (comme sur l'image):
   mettez simplement une virgule, suivie du num&eacute;ro de page.
</p>
<p>Parfois, les index sont imprim&eacute;s sur deux colonnes. Dans ce cas, l'espace est
   plus &eacute;troit, et une entr&eacute;e donn&eacute;e de l'index peut &ecirc;tre coup&eacute;e en deux lignes.
   Rejoignez les deux parties.
   Il est possible que vous obtenez ainsi certaines lignes tr&egrave;s longues.
   Ce n'est pas grave, car les lignes seront remises en forme
   &agrave; la largeur appropri&eacute;e lors du post-processing.
</p>
<p>Mettez une ligne blanche entre chaque entr&eacute;e de l'index.
   Commencez chaque sous-sujet de l'index (souvent s&eacute;par&eacute; par
   un point-virgule <kbd>;</kbd>) sur une nouvelle ligne, indent&eacute;e de 2 espaces.
</p>
<p>Si un index est en plusieurs sections (A, B, C...), traitez-les comme des
   <a href="#sect_head">t&ecirc;tes de section</a>,
   en les faisant pr&eacute;c&eacute;der de deux lignes blanches.
</p>
<p>Dans les livres anciens, le premier mot de chaque section de l'index est souvent
   en majuscules ou en petites capitales; modifiez ces mots en utilisant le m&ecirc;me style
   que celui des autres entr&eacute;es.
</p>
<p>V&eacute;rifiez les <a href="#comments">commentaires</a> de projet, car le responsable
   du projet peut demander un formatage diff&eacute;rent, comme par exemple de traiter
   un index comme une <a href="#toc">table des mati&egrave;res</a>.
</p>
<!-- END RR -->

<table width="100%" align="center" border="1" cellpadding="4" cellspacing="0" summary="Jonction des lignes dans un index">
  <tbody>
    <tr>
      <th align="left" bgcolor="cornsilk">Image de d&eacute;part:</th>
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
      <th align="left" bgcolor="cornsilk">Texte correctement format&eacute;:</th>
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
<table width="100%" align="center" border="1" cellpadding="4" cellspacing="0" summary="Alignement des sous-sujets">
  <tbody>
    <tr><th align="left" bgcolor="cornsilk">Image de d&eacute;part:</th></tr>
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
      <th align="left" bgcolor="cornsilk">Texte correctement format&eacute;:</th>
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
<table width="100%" align="center" border="1" cellpadding="4" cellspacing="0" summary="Exemple d'index">
  <tbody>
    <tr><th align="left" bgcolor="cornsilk">Image de d&eacute;part:</th></tr>
    <tr align="left">
      <td valign="top"> <img src="../index.png" alt="" width="438" height="355"></td>
    </tr>
    <tr><th align="left" bgcolor="cornsilk">Texte correctement format&eacute;:</th></tr>
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
<p class="backtotop"><a href="#top">Retour au d&eacute;but</a></p>


<h3><a name="play_n">Th&eacute;&acirc;tre</a></h3>
<p>Pour toutes les pi&egrave;ces:</p>
<ul compact>
 <li>Traitez les listes de personnages (Dramatis Person&aelig;) comme des <a href="#lists">listes</a>.</li>
 <li>Traitez le d&eacute;but d'un acte comme une <a href="#chap_head">t&ecirc;te de chapitre</a>
     en le faisant pr&eacute;c&eacute;der de 4 lignes blanches, et en mettant 2 lignes blanches
     &agrave; la suite.</li>
 <li>Traitez le d&eacute;but d'une sc&egrave;ne comme une <a href="#sect_head">t&ecirc;te de section</a>
     en le faisant pr&eacute;c&eacute;der de 2 lignes blanches.</li>
 <li>Dans les dialogues, traitez chaque prise de parole comme un paragraphe, pr&eacute;c&eacute;d&eacute;
     d'une ligne blanche. Si le nom du personnage se trouve sur une ligne s&eacute;par&eacute;e,
     traitez cette ligne &eacute;galement comme un paragraphe.</li>
 <li>Formatez les noms des acteurs comme ils apparaissent dans l'image, qu'ils soient
     en <a href="#italics">italiques</a>, en <a href="#bold">gras</a> ou tout en
     majuscules.</li>
 <li>Les notes de sc&egrave;ne (didascalies) seront format&eacute;es telles qu'elles apparaissent
     dans l'image.
     Si la note est sur une ligne isol&eacute;e, laissez-la ainsi; si elle est
     &agrave; la fin d'une ligne de dialogue, laissez-la ainsi;
     mais si elle est &agrave; la fin d'une ligne de dialogue et cal&eacute;e contre la marge de
     droite, laissez au moins six espaces entre le dialogue et la didascalie.<br>
     Parfois, une note de sc&egrave;ne commence par un crochet ouvrant, qui n'est jamais referm&eacute;.
     Nous gardons cette convention: ne fermez pas le crochet. Mettez les marques
     d'italiques, s'il y a lieu, &agrave; l'int&eacute;rieur des crochets.</li>
</ul>
<p>Pour les pi&egrave;ces en vers:</p>
<ul compact>
 <li>Les r&egrave;gles de po&eacute;sie s'appliquent aux pi&egrave;ces en vers. Entourez les vers
     par les marques <kbd>/*</kbd> et <kbd>*/</kbd>, comme pour la <a href="#poetry">po&eacute;sie</a>,
     pour conserver les retours &agrave; la ligne entre chaque vers.
     Mais une didascalie ne doit pas &ecirc;tre entour&eacute;e par <kbd>/*</kbd> et <kbd>*/</kbd>
     si elle se trouve sur une ligne s&eacute;par&eacute;e.
     (Ces indications sc&eacute;niques ne sont pas en vers, et doivent &ecirc;tre reformat&eacute;es
     comme un paragraphe de texte ordinaire, sans conserver les retours &agrave; la ligne de
     l'original: c'est pourquoi elles ne sont pas incluses dans
     les marques <kbd>/*</kbd> et <kbd>*/</kbd> qui conservent les retours &agrave; la ligne.)</li>
 <li>Conservez l'indentation relative des dialogues, comme pour la <a href="#poetry">po&eacute;sie</a>.</li>
 <li>Si un vers est coup&eacute; parce qu'il est trop long sur la page imprim&eacute;e,
     rejoignez les deux parties du vers sur une m&ecirc;me ligne (comme pour la
     <a href="#poetry">po&eacute;sie</a> en g&eacute;n&eacute;ral).
     Si la seconde partie d'un vers ne fait qu'un mot, alors elle
     sera fr&eacute;quemment imprim&eacute;e au-dessus ou au-dessous de la ligne principale, et pr&eacute;c&eacute;d&eacute;e
     d'une parenth&egrave;se, au lieu d'avoir une ligne pour elle seule.
     Voir l'<a href="#play4">exemple</a>.</li>
</ul>
<p>Regardez les <a href="#comments">Commentaires de projet</a>, car
   le responsable de projet peut demander un formatage diff&eacute;rent.
</p>
<!-- END RR -->

<table width="100%" align="center" border="1" cellpadding="4" cellspacing="0" summary="Th&eacute;&acirc;tre, exemple 1">
  <tbody>
    <tr>
      <th align="left" bgcolor="cornsilk">Image de d&eacute;part:</th>
    </tr>
    <tr align="left">
      <td width="100%" valign="top"><img src="../play1.png" width="500" height="430" alt=""><br>
      </td>
    </tr>
    <tr>
      <th align="left" bgcolor="cornsilk">Texte correctement format&eacute;:</th>
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
<table width="100%" align="center" border="1" cellpadding="4" cellspacing="0" summary="Th&eacute;&acirc;tre, exemple 2">
  <tbody>
    <tr>
      <th align="left" bgcolor="cornsilk">Image de d&eacute;part:</th>
    </tr>
    <tr align="left">
      <td width="100%" valign="top"><img src="../play2.png" width="500" height="680" alt=""><br>
      </td>
    </tr>
    <tr>
      <th align="left" bgcolor="cornsilk">Texte correctement format&eacute;:</th>
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
<table width="100%" align="center" border="1" cellpadding="4" cellspacing="0" summary="Th&eacute;&acirc;tre, exemple 3">
  <tbody>
    <tr>
      <th align="left" bgcolor="cornsilk">Image de d&eacute;part:</th>
    </tr>
    <tr align="left">
      <td width="100%" valign="top"><img src="../play3.png" width="504" height="206" alt=""><br>
      </td>
    </tr>
    <tr>
      <th align="left" bgcolor="cornsilk">Texte correctement format&eacute;:</th>
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
<table width="100%" align="center" border="1" cellpadding="4" cellspacing="0" summary="Th&eacute;&acirc;tre, exemple 4">
  <tbody>
    <tr>
      <th align="left" bgcolor="cornsilk">Image de d&eacute;part:</th>
    </tr>
    <tr align="left">
      <td width="100%" valign="top"><img src="../play4.png" width="502" height="98" alt=""><br>
      </td>
    </tr>
    <tr>
      <th align="left" bgcolor="cornsilk">Texte correctement format&eacute;:</th>
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
<p class="backtotop"><a href="#top">Retour au d&eacute;but</a></p>


<h3><a name="anything">Tout ce qui n&eacute;cessite &eacute;galement un traitement sp&eacute;cial, ou
   dont vous n'&ecirc;tes pas s&ucirc;r</a></h3>
<p>Si vous rencontrez quelque chose qui n'est pas couvert par ces directives et
   qui vous para&icirc;t avoir besoin d'un traitement sp&eacute;cial, ou que vous n'&ecirc;tes pas s&ucirc;r
   de quelque chose, posez votre question sur le <a href="#forums">forum du projet</a>
   (en pr&eacute;cisant le num&eacute;ro de la page &ndash; num&eacute;ro png &ndash; qui pose probl&egrave;me).
</p>
<p>Il est &eacute;galement recommand&eacute; d'ajouter une note dans le texte pour expliquer
   quel est le probl&egrave;me &agrave; la personne qui travaillera sur cette page ensuite.
   Commencez la note par un crochet ouvrant et deux &eacute;toiles <kbd>[**</kbd> et
   terminez-la par un crochet fermant <kbd>]</kbd> pour bien s&eacute;parer
   votre note du texte de l'auteur. Ceci signale
   au post-processeur qu'il doit s'arr&ecirc;ter et examiner ce texte et l'image
   correspondante et r&eacute;soudre le probl&egrave;me.
   Vous pouvez indiquer dans quel tour de formatage vous &ecirc;tes intervenu,
   &agrave; la fin de la note juste avant le <kbd>]</kbd>, pour que les personnes
   intervenant ensuite savent qui a laiss&eacute; la note.
   Toute note laiss&eacute;e par une personne qui a travaill&eacute; sur cette page dans un
   tour pr&eacute;c&eacute;dent <b>doit absolument</b>
   &ecirc;tre laiss&eacute;e en place. Voir les d&eacute;tails &agrave; la section suivante.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Retour au d&eacute;but</a></p>


<h3><a name="prev_notes">Notes et commentaires des correcteurs pr&eacute;c&eacute;dents</a></h3>
<p>Les notes des correcteurs pr&eacute;c&eacute;dents <b>doivent</b> &ecirc;tre gard&eacute;es.
   Vous pouvez ajouter que vous &ecirc;tes d'accord ou pas d'accord,
   mais m&ecirc;me si vous &ecirc;tes s&ucirc;r de la solution, ne supprimez
   pas la note. Si vous avez une source qui permet
   de donner la r&eacute;ponse au probl&egrave;me, citez cette source, pour que
   le post-processeur s'y r&eacute;f&egrave;re lui aussi.
</p>
<p>Si vous connaissez la r&eacute;ponse &agrave; une question pos&eacute;e dans une note
   par un correcteur dans un tour pr&eacute;c&eacute;dent, vous pouvez &eacute;crire un message &agrave; ce correcteur
   (en cliquant sur son nom dans l'interface de correction), pour lui expliquer comment
   g&eacute;rer la situation la prochaine fois. Mais ne supprimez jamais sa note.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Retour au d&eacute;but</a></p>


<table width="100%" border="0" cellspacing="0" cellpadding="6" summary="Probl&egrave;mes courants">
  <tbody>
    <tr>
      <td bgcolor="silver"><h2 style="margin-bottom: 0; margin-top: 0;">Probl&egrave;mes courants:</h2></td>
    </tr>
  </tbody>
</table>


<h3><a name="bad_image">Mauvaises images</a></h3>
<p>Si une image de page scann&eacute;e est mauvaise (refuse de se charger, illisible, etc.),
   postez un message &agrave; propos de cette image dans le <a href="#forums">forum</a>
   du projet.
</p>
<p>Parfois, les images sont tr&egrave;s volumineuses, et il se peut que votre
   navigateur ait du mal &agrave; les afficher, surtout si vous avez beaucoup de
   fen&ecirc;tres ouvertes ou si votre ordinateur est ancien.
   Essayez de fermer quelques fen&ecirc;tres
   ou programmes ouverts pour voir si le probl&egrave;me s'arrange, ou posez la
   question dans le forum du projet, pour voir si d'autres relecteurs ont le
   m&ecirc;me probl&egrave;me.
   <!-- this sentence not in the english guidelines: -->
   Vous pouvez &eacute;galement cliquez sur le lien &ldquo;image&rdquo;
   en bas de l'interface de correction pour faire appara&icirc;tre l'image sur une
   fen&ecirc;tre &agrave; part. Si l'image s'affiche correctement, alors le probl&egrave;me vient
   probablement de votre syst&egrave;me, ou de votre navigateur.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Retour au d&eacute;but</a></p>


<h3><a name="bad_text">Image ne correspondant pas au texte</a></h3>
<p>Si l'image ne correspond pas au texte, postez un message &agrave; ce propos sur
   le <a href="#forums">forum du projet</a>.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Retour au d&eacute;but</a></p>


<h3><a name="round1">Erreurs des correcteurs et formateurs pr&eacute;c&eacute;dents</a></h3>
<p>Si le volontaire pr&eacute;c&eacute;dent a fait beaucoup d'erreurs ou a laiss&eacute; passer un
   grand nombre de choses, vous pouvez lui envoyer un message en cliquant sur son
   nom. &Ccedil;a vous permettra de lui envoyer un message priv&eacute;: ainsi
   il corrigera mieux et fera moins d'erreurs la prochaine fois.
</p>
<p><em>Soyez aimable!</em> Il s'agit d'un b&eacute;n&eacute;vole, comme nous le sommes tous,
   essayant certainement de faire de son mieux. Le but du message est de l'informer
   de la mani&egrave;re correcte de formater, plut&ocirc;t que de formuler des critiques.
   Donnez-lui un exemple pr&eacute;cis de ce qu'il a fait, et de ce qu'il aurait d&ucirc; faire.
</p>
<p>Si la personne pr&eacute;c&eacute;dente a fait un travail remarquable,
   vous pouvez &eacute;galement lui envoyer un message pour le lui
   dire, surtout si elle a travaill&eacute; sur une page tr&egrave;s difficile.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Retour au d&eacute;but</a></p>


<h3><a name="p_errors">Erreurs d'impression/d'orthographe</a></h3>
<p>Corrigez toujours les fautes introduites par l'OCR. Mais ne corrigez pas ce
   qui est conforme &agrave; l'image, m&ecirc;me si &ccedil;a vous semble &ecirc;tre une faute
   d'orthographe ou d'impression. Parfois, certains mots ne s'&eacute;crivaient pas comme
   aujourd'hui &agrave; l'&eacute;poque o&ugrave; le livre a &eacute;t&eacute; imprim&eacute;. Gardez
   l'ancienne orthographe, <b>en particulier en ce qui concerne les accents</b>.
</p>
<p>Mettez une note dans le texte &agrave; la suite de l'erruer<kbd>[**typo pour erreur?]</kbd>.
   Si vous n'&ecirc;tes pas certain qu'il s'agisse r&eacute;ellement d'une erreur, demandez
   &eacute;galement confirmation dans le
   <a href="#forums">forum du projet</a>. Si vous changez vraiment quelque chose,
   alors mettez une note d&eacute;crivant ce que vous avez chang&eacute;:
   <kbd>[**corrig&eacute; "erruer"]</kbd>.
   N'oubliez pas les deux &eacute;toiles <kbd>**</kbd> pour que le post-processeur
   rep&egrave;re votre note.</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Retour au d&eacute;but</a></p>


<h3><a name="f_errors">Erreurs factuelles dans le texte</a></h3>
<p>Ne corrigez pas les erreurs de fait dans les livres.
   Beaucoup de livres que nous pr&eacute;parons d&eacute;crivent des choses que nous savons &ecirc;tre
   fausses. Laissez-les telles que l'auteur les a &eacute;crites.
   La section <a href="#p_errors">Erreurs d'impression/d'orthographe</a>
   indique comment laisser une note si vous pensez que le texte imprim&eacute;
   ne correspond pas &agrave; l'intention de l'auteur.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Retour au d&eacute;but</a></p>

</div>


<table width="100%" border="0" cellspacing="0" cellpadding="6" summary="Index alphab&eacute;tique">
  <tbody>
    <tr>
      <td bgcolor="silver"><h2 style="margin-bottom: 0; margin-top: 0;"><a name="index">Index alphab&eacute;tique des directives</a></h2></td>
    </tr>
  </tbody>
</table>
<br>

<table border="0" width="100%" summary="Index alphab&eacute;tique">
  <tr>
    <td width="50%" valign="top">
      <ul>
        <li><a href="#r_align">Align&eacute; &agrave; droite (texte)</a></li>
        <li><a href="#maj_div">Autres divisions</a></li>
        <li><a href="#blank_pg">Blanche (page)</a></li>
        <li><a href="#block_qt">Bloc de citation</a></li>
        <li><a href="#word_caps">Capitales (tout en)</a></li>
        <li><a href="#small_caps">Capitales (petites)</a></li>
        <li><a href="#chap_head">Chapitre</a></li>
        <li><a href="#block_qt">Citation (bloc)</a></li>
        <li><a href="#para_side">Commentaire en marge</a></li>
        <li><a href="#comments">Commentaires de projet</a></li>
        <li><a href="#prev_pg">Corriger des erreurs sur les pages pr&eacute;c&eacute;dentes</a></li>
        <li><a href="#extra_s">D&eacute;coration entre deux paragraphes</a></li>
        <li><a href="#maj_div">Divisions (autres)</a></li>
        <li><a href="#r_align">Droite (texte align&eacute; &agrave;)</a></li>
        <li><a href="#p_errors">Erreurs d'impressions et d'orthographe</a></li>
        <li><a href="#round1">Erreurs des correcteurs pr&eacute;c&eacute;dents</a></li>
        <li><a href="#f_errors">Erreurs factuelles dans les textes</a></li>
        <li><a href="#prev_pg">Erreurs sur les pages pr&eacute;c&eacute;dentes</a></li>
        <li><a href="#para_space">Espacement et indentation des paragraphes</a></li>
        <li><a href="#extra_sp">Espaces exc&eacute;dentaires</a></li>
        <li><a href="#spaced">Espac&eacute; (gesperrt)</a></li>
        <li><a href="#supers">Exposant</a></li>
        <li><a href="#r_align">Fer &agrave; droite (texte align&eacute; &agrave; droite)</a></li>
        <li><a href="#forums">Forum du Projet</a></li>
        <li><a href="#spaced">Gesperrt (texte espac&eacute;)</a></li>
        <li><a href="#bold">Gras</a></li>
        <li><a href="#inline">Hors-texte (formatage)</a></li>
        <li><a href="#illust">Illustration</a></li>
        <li><a href="#bad_text">Image ne correspondant pas au texte</a></li>
        <li><a href="#para_space">Indentation et espacement des paragraphes</a></li>
        <li><a href="#bk_index">Index</a></li>
        <li><a href="#subscr">Indice</a></li>
        <li><a href="#extra_s">Interruption du fil du discours</a></li>
        <li><a href="#inline">Intra-paragraphe (placement des marques)</a></li>
        <li><a href="#italics">Italiques</a></li>
        <li><a href="#illust">L&eacute;gende (Illustration)</a></li>
      </ul>
    </td>
    <td width="50%" valign="top">
      <ul>
        <li><a href="#letter">Lettres (correspondance)</a></li>
        <li><a href="#lists">Liste</a></li>
        <li><a href="#word_caps">Majuscules (tout en capitales)</a></li>
        <li><a href="#para_side">Manchette (note en marge)</a></li>
        <li><a href="#inline">Marques intra-paragraphe (placement)</a></li>
        <li><a href="#bad_image">Mauvaise image</a></li>
        <li><a href="#footnotes">Note de fin ou de bas de page</a></li>
        <li><a href="#para_side">Note en marge</a></li>
        <li><a href="#prev_notes">Notes/commentaires des correcteurs pr&eacute;c&eacute;dents</a></li>
        <li><a href="#line_no">Num&eacute;ros de ligne</a></li>
        <li><a href="#page_ref">Num&eacute;ros de pages (r&eacute;f&eacute;rences aux)</a></li>
        <li><a href="#blank_pg">Page blanche</a></li>
        <li><a href="#title_pg">Page de titre/fin</a></li>
        <li><a href="#extra_s">Paragraphe (espace ou d&eacute;coration entre)</a></li>
        <li><a href="#para_space">Paragraphes (espacement et indentation)</a></li>
        <li><a href="#extra_s">Pause entre deux paragraphes</a></li>
        <li><a href="#small_caps">Petites capitales</a></li>
        <li><a href="#inline">Placement des marques intra-paragraphe</a></li>
        <li><a href="#font_sz">Police (changement de taille)</a></li>
        <li><a href="#font_ch">Police (changement)</a></li>
        <li><a href="#poetry">Po&eacute;sie/&Eacute;pigrammes</a></li>
        <li><a href="#prime">R&egrave;gle principale</a></li>
        <li><a href="#summary">R&eacute;sum&eacute; des directives</a></li>
        <li><a href="#sect_head">Section</a></li>
        <li><a href="#underl">Soulign&eacute;</a></li>
        <li><a href="#subscr">Sous la ligne (texte en indice)</a></li>
        <li><a href="#supers">Sup&eacute;rieur (texte en exposant)</a></li>
        <li><a href="#toc">Table des mati&egrave;res</a></li>
        <li><a href="#tables">Tableau</a></li>
        <li><a href="#font_sz">Taille de police (changement)</a></li>
        <li><a href="#chap_head">T&ecirc;te de chapitre</a></li>
        <li><a href="#sect_head">T&ecirc;te de section</a></li>
        <li><a href="#r_align">Texte align&eacute; &agrave; droite</a></li>
        <li><a href="#play_n">Th&eacute;&acirc;tre</a></li>
        <li><a href="#anything">Tous autres points</a></li>
        <li><a href="#separate_pg">Unit&eacute; s&eacute;par&eacute;e (chaque page)</a></li>
      </ul>
    </td>
  </tr>
</table>

<br>
<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="silver" summary="Liens">
  <tr>
    <td width="10">&nbsp;</td>
    <td width="100%" align="center"><font face="verdana, helvetica, sans-serif" size="1">
      Retour vers:
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
