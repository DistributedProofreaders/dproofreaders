<?php

// Translated by user 'Pierre' at pgdp.net, 2006-02-08
// Updated by user 'lvl', 2009-03-20

$relPath='../../pinc/';
include_once($relPath.'base.inc');
include_once($relPath.'faq.inc');
include_once($relPath.'pg.inc');
include_once($relPath.'theme.inc');

maybe_redirect_to_external_faq("fr");

$theme_args["css_data"] = "p.backtotop {text-align:right; font-size:75%;margin-right:-5%;}";

output_header('Directives de Relecture et de Correction', NO_STATSBAR, $theme_args);

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

<h1 align="center"><a name="top">Directives de Relecture et de Correction</a></h1>

<h3 align="center">Version 2.0, 7 juin 2009 (Traduction de la version 2.0 anglaise du 7 juin 2009)</h3>


<p>Directives de Relecture et de Correction <a href="../proofreading_guidelines.php">en Anglais</a> /
      Proofreading Guidelines <a href="../proofreading_guidelines.php">in English</a><br>
    Directives de Relecture et de Correction <a href="../pt/proofreading_guidelines.php">en Portugais</a> /
      Regras de Revis&atilde;o <a href="../pt/proofreading_guidelines.php">em Portugu&ecirc;s</a><br>
    Directives de Relecture et de Correction <a href="../es/proofreading_guidelines.php">en Espagnol</a> /
      Reglas de Revisi&oacute;n <a href="../es/proofreading_guidelines.php">en Espa&ntilde;ol</a><br>
    Directives de Relecture et de Correction <a href="../nl/proofreading_guidelines.php">en N&eacute;erlandais</a> /
      Proeflees-Richtlijnen <a href="../nl/proofreading_guidelines.php">in het Nederlands</a><br>
    Directives de Relecture et de Correction <a href="../de/proofreading_guidelines.php">en Allemand</a> /
      Korrekturlese-Richtlinien <a href="../de/proofreading_guidelines.php">auf Deutsch</a><br>
    Directives de Relecture et de Correction <a href="../it/proofreading_guidelines.php">en Italien</a> /
      Regole di Correzione <a href="../it/proofreading_guidelines.php">in Italiano</a><br>
</p>

<p>Voir aussi le <a href="../../quiz/start.php?show_only=PQ">Tutoriel/Quiz de Relecture</a>.
</p>

<table border="0" cellspacing="0" width="100%" summary="Table des mati&egrave;res">
  <tbody>
  <tr>
    <td width="1" bgcolor="silver">&nbsp;</td>
    <td bgcolor="silver" align="center"><h2 style="margin-bottom: 0; margin-top: 0;">Table des
      mati&egrave;res</h2></td>
  </tr>
  <tr>
    <td width="1" bgcolor="silver">&nbsp;</td>
    <td bgcolor="white" align="left">
      <ul>
        <li><a href="#prime">La r&egrave;gle principale</a></li>
        <li><a href="#summary">R&eacute;sum&eacute; des directives</a></li>
        <li><a href="#about">&Agrave; propos de ce document</a></li>
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
        <li style="margin-top:.25em;"><font size="+1">Relecture &agrave; l'&eacute;chelle des caract&egrave;res:</font>
        <ul>
          <li><a href="#double_q">Guillemets doubles</a></li>
          <li><a href="#single_q">Guillemets simples</a></li>
          <li><a href="#quote_ea">Guillemets sur chaque ligne</a></li>
          <li><a href="#period_s">Points en fin de phrase</a></li>
          <li><a href="#punctuat">Espaces et ponctuation</a></li>
          <li><a href="#extra_sp">Espaces exc&eacute;dentaires entre les mots</a></li>
          <li><a href="#trail_s">Espace en fin de ligne</a></li>
          <li><a href="#em_dashes">Tirets, traits d'union et signes &ldquo;moins&rdquo;</a></li>
          <li><a href="#eol_hyphen">Traits d'union et tirets en fin de ligne</a></li>
          <li><a href="#eop_hyphen">Traits d'union et tirets en fin de page</a></li>
          <li><a href="#period_p">Points de suspension &ldquo;...&rdquo;</a></li>
          <li><a href="#contract">Contractions</a></li>
          <li><a href="#fract_s">Fractions</a></li>
          <li><a href="#a_chars">Caract&egrave;res accentu&eacute;s et non-ASCII</a></li>
          <li><a href="#d_chars">Caract&egrave;res avec marques diacritiques</a></li>
          <li><a href="#f_chars">Alphabets non latins</a></li>
          <li><a href="#supers">Texte en exposant</a></li>
          <li><a href="#subscr">Texte en indice</a></li>
          <li><a href="#drop_caps">Lettre de d&eacute;but de paragraphe grande ou orn&eacute;e</a></li>
          <li><a href="#small_caps">Petites capitales</a></li>
        </ul></li>
        <li style="margin-top:.25em;"><font size="+1">Relecture &agrave; l'&eacute;chelle du paragraphe:</font>
        <ul>
          <li><a href="#line_br">Retours &agrave; la ligne</a></li>
          <li><a href="#chap_head">T&ecirc;tes de chapitres</a></li>
          <li><a href="#para_space">Espacement et indentation des paragraphes</a></li>
          <li><a href="#page_hf">En-t&ecirc;tes et pieds de page</a></li>
          <li><a href="#illust">Illustrations</a></li>
          <li><a href="#footnotes">Notes de fin et notes de bas de page</a></li>
          <li><a href="#para_side">Commentaires en marge des paragraphes</a></li>
          <li><a href="#mult_col">Colonnes multiples</a></li>
          <li><a href="#tables">Tableaux</a></li>
          <li><a href="#poetry">Po&eacute;sie/&Eacute;pigrammes</a></li>
          <li><a href="#line_no">Num&eacute;ros de ligne</a></li>
          <li><a href="#next_word">Mot isol&eacute; en fin de page</a></li>
        </ul></li>
        <li style="margin-top:.25em;"><font size="+1">Relecture &agrave; l'&eacute;chelle de la page:</font>
        <ul>
          <li><a href="#blank_pg">Page blanche</a></li>
          <li><a href="#title_pg">Page de titre/fin</a></li>
          <li><a href="#toc">Table des mati&egrave;res</a></li>
          <li><a href="#bk_index">Index</a></li>
          <li><a href="#play_n">Th&eacute;&acirc;tre</a></li>
        </ul></li>
        <li><a href="#anything">Tous autres points n&eacute;cessitant un traitement particulier, ou dont vous
          n'&ecirc;tes pas s&ucirc;r</a></li>
        <li><a href="#prev_notes">Notes et commentaires des correcteurs pr&eacute;c&eacute;dents</a></li>
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
          <li><a href="#formatting">Formatage</a></li>
          <li><a href="#common_OCR">Probl&egrave;mes d'OCR courants</a></li>
          <li><a href="#OCR_scanno">Probl&egrave;mes d'OCR: Erreurs de scan</a></li>
          <li><a href="#OCR_raised_o">Probl&egrave;mes d'OCR: degr&eacute;s et o en exposant &deg; &ordm;</a></li>
          <li><a href="#hand_notes">Notes manuscrites dans le livre</a></li>
          <li><a href="#bad_image">Mauvaises images</a></li>
          <li><a href="#bad_text">Image ne correspondant pas au texte</a></li>
          <li><a href="#round1">Erreurs des correcteurs pr&eacute;c&eacute;dents</a></li>
          <li><a href="#p_errors">Erreurs d'impressions et d'orthographe</a></li>
          <li><a href="#f_errors">Erreurs factuelles dans les textes</a></li>
          <li><a href="#insert_char">Saisie des caract&egrave;res sp&eacute;ciaux</a></li>
        </ul></li>
        <li><a href="#index">Index alphab&eacute;tique des Directives</a></li>
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
<p>Le livre &eacute;lectronique final vu par un lecteur, peut-&ecirc;tre dans un avenir plus
   ou moins lointain, doit transmettre l'intention de l'auteur de mani&egrave;re exacte.
   Si l'auteur &eacute;crit des mots d'une mani&egrave;re &eacute;trange, laissez-les.
   Si l'auteur &eacute;crit des choses choquantes, racistes ou partiales, laissez-les
   telles quelles. Si l'auteur met des virgules, des mots en exposant ou
   des notes de bas de page tous les trois mots, gardez les virgules, les
   mots en exposant et les notes de bas de page. Nous sommes des relecteurs,
   pas des &eacute;diteurs; s'il y a quelque chose dans le texte qui ne correspond pas
   &agrave; ce qui appara&icirc;t sur l'image de la page originale, vous devez modifier le
   texte pour qu'il corresponde &agrave; l'image. (Voir toutefois
   la section sur les <a href="#p_errors">erreurs d'impression</a> pour la fa&ccedil;on
   correcte de traiter les fautes &eacute;videntes de l'imprimeur.)
</p>
<p>Par contre, nous changeons des choses mineures qui n'affectent pas le sens de
   ce que l'auteur a &eacute;crit. Nous rejoignons les mots s&eacute;par&eacute;s
   par un retour &agrave; la ligne. (voir <a href="#eol_hyphen">Traits d'union en fin de lignes</a>)
   Ces changements nous permettent d'avoir des livres <em>d'un
   format homog&egrave;ne</em>. Nous suivons des r&egrave;gles de relecture
   pour avoir ce r&eacute;sultat. Lisez attentivement le reste de ces Directives en gardant ce
   concept &agrave; l'esprit. Les pr&eacute;sentes r&egrave;gles s'appliquent <i>uniquement</i> &agrave; la relecture.
   En tant que relecteur vous vous consacrez au contenu de l'image,
   et plus tard les formateurs feront le n&eacute;cessaire pour restituer la pr&eacute;sentation.
</p>
<p>Pour aider le prochain relecteur, les formateurs, et le post-processeur, nous gardons aussi
   les <a href="#line_br">retours &agrave; la ligne</a>. Il est ainsi facile
   de comparer les lignes du texte corrig&eacute; et les lignes de l'image.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Retour au d&eacute;but</a></p>


<h3><a name="summary">R&eacute;sum&eacute; des directives</a></h3>
<p>Le <a href="proofing_summary.pdf">R&eacute;sum&eacute; des directives</a>
   est un court document imprimable de 2 pages (.pdf) qui r&eacute;sume les
   points principaux de ces directives, et qui donne des exemples de corrections.
   Les relecteurs d&eacute;butants sont encourag&eacute;s &agrave; imprimer ce document et &agrave; le garder
   &agrave; port&eacute;e de main quand ils corrigent.
</p>
<p>Vous aurez besoin d'un lecteur de fichiers .pdf. Vous pouvez en t&eacute;l&eacute;charger
   un gratuitement chez Adobe&reg; <a href="http://www.adobe.com/products/acrobat/readstep2.html">ici</a>.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Retour au d&eacute;but</a></p>


<h3><a name="about">&Agrave; propos de ce document</a></h3>
<p>Ce document explique les r&egrave;gles &agrave; suivre pour les relecteurs. Il a pour but
   de r&eacute;duire les diff&eacute;rences de transcription entre les
   travaux des diff&eacute;rents correcteurs qui ont travaill&eacute; sur un m&ecirc;me livre, de
   mani&egrave;re &agrave; ce que nous transcrivions tous <em>de la m&ecirc;me mani&egrave;re.</em>
   Cela rend le travail plus facile aux formateurs, et au post-processeur qui ach&egrave;ve le travail.
</p>
<p><i>Ce document n'est en aucune mani&egrave;re cens&eacute; &ecirc;tre un recueil
   de r&egrave;gles &eacute;ditoriales ou typographiques.</i>
</p>
<p>Nous avons inclus dans ces directives de relecture tous les points de transcription qui
   ont suscit&eacute; des questions de la part des nouveaux utilisateurs.
   Il existe un document diff&eacute;rent concernant les
   <a href="formatting_guidelines.php">Directives de formatage</a>,
   destin&eacute;es &agrave; un second groupe de volontaires qui s'occupe du formatage.
   Si vous rencontrez une situation qui n'est pas mentionn&eacute;e dans les pr&eacute;sentes
   directives, il est probable qu'il s'agisse d'une t&acirc;che g&eacute;r&eacute;e par les formateurs,
   et que vous ne trouviez donc pas la r&eacute;ponse ici. Si vous avez un doute, veuillez
   demander dans le <a href="#forums">forum de discussion du projet</a>.
</p>
<p>S'il manque des r&eacute;ponses dans ce document, ou que vous consid&eacute;rez que certaines
   choses devraient &ecirc;tre trait&eacute;es de mani&egrave;re diff&eacute;rente,
   ou que l'explication est trop vague, merci de nous le faire savoir.
<?php if($site_url == "http://www.pgdp.net" || $site_url == "http://www.pgdp.org") { ?>
   Si vous rencontrez une expression inconnue dans ces directives, consultez le
   <a href="http://www.pgdp.net/wiki/French/Jargon">guide du jargon sur le wiki</a>.
<?php } ?>
   Ce document est en &eacute;volution permanente. Aidez-nous &agrave; l'am&eacute;liorer en nous
   faisant part de vos suggestions sur le forum Documentation dans
   <a href="<?php echo $Guideline_discussion_URL; ?>">ce fil de discussions</a>.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Retour au d&eacute;but</a></p>


<h3><a name="comments">Commentaires des projets</a></h3>
<p>Lorsque vous s&eacute;lectionnez un projet pour commencer &agrave; relire des pages,
   la page du projet est la premi&egrave;re page qui s'affiche. Dans cette page,
   il y a une section &ldquo;project comments&rdquo; (Commentaires du projet) qui contient des informations
   sp&eacute;cifiques &agrave; ce projet (livre). <b>Lisez celles-ci avant de commencer &agrave;
   corriger des pages!</b> Si le responsable de projet veut que vous vous d&eacute;rogiez
   aux directives pour une chose bien pr&eacute;cise, ce
   sera indiqu&eacute; l&agrave;. Les instructions dans les &ldquo;Commentaires du projet&rdquo; <em>supplantent</em>
   les r&egrave;gles contenues dans les pr&eacute;sentes directives, donc suivez-les. C'est &eacute;galement l&agrave;
   que le responsable du projet donne des r&egrave;gles sp&eacute;ciales concernant la phase
   de formatage (qui ne s'appliquent donc pas &agrave; la phase de relecture).
   Enfin, c'est aussi &agrave; cet endroit que le responsable de projet vous
   donne des informations int&eacute;ressantes &agrave; propos des livres, comme leur provenance, etc.
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
<p>Dans la page du projet dans laquelle vous commencez &agrave; corriger des pages,
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
<p>La <a href="#comments">page du projet</a>
   contient des liens vers les pages que vous avez corrig&eacute;es r&eacute;cemment
   (si vous n'avez pas encore corrig&eacute; de pages, aucun lien ne sera encore
   affich&eacute;).
</p>
<p>Les pages list&eacute;es sous &ldquo;DONE&rdquo; et &ldquo;IN PROGRESS&rdquo; sont disponibles pour que vous
   puissiez corriger ou terminer votre travail de relecture. Cliquez sur le lien
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


<table width="100%" border="0" cellspacing="0" cellpadding="6" summary="&Eacute;chelle des caract&egrave;res">
  <tbody>
    <tr>
      <td bgcolor="silver"><h2 style="margin-bottom: 0; margin-top: 0;">Relecture &agrave; l'&eacute;chelle des caract&egrave;res</h2></td>
    </tr>
  </tbody>
</table>


<h3><a name="double_q">Guillemets doubles (&nbsp;"&nbsp;)</a></h3>
<p>Repr&eacute;sentez les &ldquo;guillemets doubles anglais&rdquo;
   par des guillemets droits ASCII (<tt>"</tt>). Ne remplacez pas les guillemets
   doubles par des guillemets simples ou des apostrophes. Laissez ce que l'auteur a &eacute;crit.
   Voyez la section <a href="#chap_head">T&ecirc;te de chapitre</a> si un guillemet
   ouvrant manque en d&eacute;but de chapitre.
</p>
<p>Pour repr&eacute;senter des guillemets autres que les guillemets anglais <tt>"</tt>,
   utilisez les caract&egrave;res figurant dans l'image, s'ils sont disponibles.
   En fran&ccedil;ais, vous pouvez utilisez les guillemets fran&ccedil;ais <tt>&laquo;comme ceci&raquo;</tt>, car ils
   sont disponibles dans la liste d&eacute;roulante de caract&egrave;res. N'oubliez pas d'effacer
   les espaces apr&egrave;s les guillemets ouvrants et avant les guillemets fermants. Ces espaces
   seront rajout&eacute;s si n&eacute;cessaire en phase de post-processing. Ceci s'applique aussi aux
   langues qui utilisent ces guillemets de fa&ccedil;on invers&eacute;e,&nbsp; <tt>&raquo;comme ceci&laquo;</tt>.
</p>
<p>Les guillemets utilis&eacute;s dans certains livres (en allemand ou dans d'autres langues),&nbsp;
   <tt>&bdquo;comme ceci&rdquo;</tt>
   sont &eacute;galement disponibles dans les listes d&eacute;roulantes; par simplicit&eacute;, vous devriez
   toujours utiliser&nbsp; <tt>&bdquo;</tt>&nbsp; et&nbsp; <tt>&ldquo;</tt>&nbsp; quels que soient les types de
   guillemets utilis&eacute;s dans le texte original, tant que ces guillemets sont clairement
   situ&eacute;s respectivement dans le bas ou le haut de la ligne.
   Au besoin, le post-processeur modifiera les guillemets en phase finale
   pour se rapprocher de l'original.
</p>
<p>Comme d'habitude, le responsable de projet peut demander de faire autrement,
   dans les <a href="#comments">commentaires du projet</a>, pour un livre donn&eacute;.
   Assurez-vous de ne pas appliquer ces exceptions par m&eacute;garde aux autres projets.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Retour au d&eacute;but</a></p>


<h3><a name="single_q">Guillemets simples (apostrophe)</a></h3>
<p>Utilisez l'apostrophe droite ASCII (<tt>'</tt>). Ne la changez pas en
   double quote (guillemets). Laissez ce que l'auteur a &eacute;crit.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Retour au d&eacute;but</a></p>


<h3><a name="quote_ea">Guillemets sur chaque ligne</a></h3>
<p>Certains livres mettent des guillemets au d&eacute;but de chaque ligne dans une
   citation; enlevez-les, <b>sauf</b> pour le guillemet ouvrant la citation.
   Si la citation continue sur plusieurs paragraphes, conservez &agrave; l'identique
   le guillemet &eacute;ventuellement pr&eacute;sent sur la premi&egrave;re ligne
   de chaque paragraphe.
</p>
<p>N&eacute;anmoins, en po&eacute;sie, conservez les guillemets pr&eacute;sents en
   d&eacute;but de ligne, puisque les retours &agrave; la ligne seront conserv&eacute;s.
</p>
<p>Souvent, les guillemets ne sont pas ferm&eacute;s avant la fin de la citation,
   qui ne se trouve peut-&ecirc;tre pas sur la page que vous &ecirc;tes en train de relire.
   Ne changez rien. N'ajoutez pas de guillemets fermants qui ne seraient pas
   sur l'image scan.
</p>
<p>Il y a quelques exceptions d&eacute;pendant de la langue. Par exemple, en fran&ccedil;ais,
   les dialogues &agrave; l'int&eacute;rieur d'une citation utilisent une combinaison de
   divers signes de ponctuation pour indiquer les diff&eacute;rents interlocuteurs.
   Si vous n'&ecirc;tes pas familier avec la typographie d'une langue en particulier,
   v&eacute;rifiez les <a href="#comments">commentaires de projet</a> ou
   posez la question dans le forum de discussion du projet.
</p>
<!-- END RR -->
<table width="100%" border="1" cellpadding="4" cellspacing="0" align="center" summary="Exemple de guillemet sur chaque ligne">
  <tbody>
    <tr>
      <th valign="top" align="left" bgcolor="cornsilk">Image d'origine</th>
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
      <th valign="top" align="left" bgcolor="cornsilk">Texte corrig&eacute;:</th>
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
<p class="backtotop"><a href="#top">Retour au d&eacute;but</a></p>


<h3><a name="period_s">Points en fin de phrase</a></h3>
<p>Mettez un seul espace apr&egrave;s les points et aucun avant.
</p>
<p>Ne perdez pas votre temps &agrave; supprimer les espaces en trop apr&egrave;s
   le point si les espaces sont d&eacute;j&agrave; dans le texte issu de l'OCR &ndash; il
   est facile de faire &ccedil;a automatiquement lors du post-processing.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Retour au d&eacute;but</a></p>


<h3><a name="punctuat">Espaces et ponctuation</a></h3>
<p>Vous verrez parfois des espaces &ldquo;en trop&rdquo; sur les livres imprim&eacute;s aux
   XVIII<sup>&egrave;me</sup> et XIX<sup>&egrave;me</sup> si&egrave;cles, car une fraction d'espace est
   ins&eacute;r&eacute;e avant les caract&egrave;res &ldquo;deux-points&rdquo; et &ldquo;point-virgule&rdquo;.
</p>
<p>En g&eacute;n&eacute;ral, les signes de ponctuation doivent &ecirc;tre suivis par un espace,
   et n'avoir jamais d'espace avant. Si le texte ne contient pas d'espace entre les
   signes de ponctuation et le mot qui suit, ajoutez un espace; s'il contient
   des espaces avant la ponctuation, retirez-les. Cette r&egrave;gle s'applique &eacute;galement
   dans des langues comme le fran&ccedil;ais, o&ugrave; des espaces sont
   normalement ins&eacute;r&eacute;s avant certains signes.
   N&eacute;anmoins, les signes de ponctuation qui se groupent par paires, comme
   les &laquo;guillemets&raquo;, (parenth&egrave;ses), [crochets], et {accolades},
   ont normalement un espace avant le signe ouvrant, qui doit &ecirc;tre conserv&eacute;.
</p>
<!-- END RR -->

<table width="100%" align="center" border="1" cellpadding="4" cellspacing="0" summary="Exemple de ponctuation">
  <tbody>
    <tr><th align="left" bgcolor="cornsilk">Image d'origine:</th></tr>
    <tr>
      <td valign="top">and so it goes&nbsp;; ever and ever.</td>
    </tr>
    <tr><th align="left" bgcolor="cornsilk">Texte corrig&eacute;:</th></tr>
    <tr>
      <td valign="top"><tt>and so it goes; ever and ever.</tt></td>
    </tr>
  </tbody>
</table>
<p class="backtotop"><a href="#top">Retour au d&eacute;but</a></p>


<h3><a name="extra_sp">Espaces exc&eacute;dentaires ou tabulations entre les mots</a></h3>
<p>Il est fr&eacute;quent que l'OCR ajoute des espaces suppl&eacute;mentaires entre les mots. Il
   n'est pas n&eacute;cessaire de supprimer ces espaces &eacute;tant donn&eacute; qu'il
   est facile de le faire automatiquement lors du post-processing.
   Cependant, les espaces exc&eacute;dentaires autour de la ponctuation,
   des tirets, des guillemets etc. <b>doivent</b> &ecirc;tre supprim&eacute;s.
</p>
<p>Par exemple, dans <tt>A horse&nbsp;;&nbsp;&nbsp;my kingdom for a horse.</tt>
   il faut supprimer l'espace avant le point virgule. Mais les deux
   espaces apr&egrave;s le point virgule ne posent pas de probl&egrave;me&nbsp;: vous n'&ecirc;tes pas
   oblig&eacute;s d'en supprimer un.
</p>
<p>De plus, si vous rencontrez des caract&egrave;res de tabulation dans le texte
   vous devez les supprimer.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Retour au d&eacute;but</a></p>


<h3><a name="trail_s">Espaces en fin de ligne</a></h3>
<p>Inutile d'ins&eacute;rer des espaces &agrave; la fin des lignes; ces espaces seront
   retir&eacute;s automatiquement lorsque vous enregistrerez la page en cours. En
   phase de post-processing, chaque retour &agrave; la ligne sera converti en un
   espace.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Retour au d&eacute;but</a></p>


<h3><a name="em_dashes">Tirets, traits d'unions, et signe &ldquo;moins&rdquo;</a></h3>
<p>Il y a en g&eacute;n&eacute;ral quatre types de traits dans les livres.
</p>
  <ol compact>
    <li>Les traits d'union (<i>hyphen</i>). Ils sont utilis&eacute;s pour <b>joindre</b> les
        mots, ou parfois pour joindre les pr&eacute;fixes ou les suffixes &agrave; un mot.
    <br>Dans votre texte corrig&eacute;, laissez un seul signe <tt>-</tt>, sans espace ni &agrave;
        droite ni &agrave; gauche.
        Notez toutefois qu'il y a une exception. Voir le deuxi&egrave;me exemple ci-dessous.
    </li>
    <li>Les tirets d'un demi-cadratin (<i>en-dash</i>). Ils sont un peu plus longs, ils sont
        utilis&eacute;s pour des <b>intervalles</b> de nombres, ou pour le signe
        math&eacute;matique &ldquo;moins&rdquo;.
    <br>L&agrave; aussi, laissez un seul signe <tt>-</tt>. Laissez un espace avant ou apr&egrave;s
        selon la fa&ccedil;on utilis&eacute;e dans le livre, en g&eacute;n&eacute;ral,
        pas d'espace entre deux chiffres pour les intervalles, mais, autour du signe &ldquo;moins&rdquo;, il y
        en a parfois des deux c&ocirc;t&eacute;s, parfois seulement avant.
    </li>
    <li>Les tirets d'un cadratin (<i>em-dash</i>) et les tirets longs (<i>long dash</i>).
        Ils servent de <b>s&eacute;parateurs</b>
        entre les mots&mdash;parfois pour mettre l'accent, comme ceci&mdash;ou
        quand une personne prend la parole, ou s'interrompt dans un dialogue.
    <br>Notez le tiret d'un cadratin (aussi long que 2 ou 3 lettres environ) avec deux signes (<tt>--</tt>),
        et le tiret long (aussi long que 4 ou 5 lettres) avec
        quatre signes (<tt>----</tt>), sans espace ni avant ni apr&egrave;s, m&ecirc;me s'il semble y
        en avoir un sur le document imprim&eacute;.
    </li>
    <li>Les tirets qui repr&eacute;sentent des mots (ou des noms) <b>omis</b> ou <b>censur&eacute;s</b>
        d&eacute;lib&eacute;r&eacute;ment.
    <br>Notez-les avec deux signes <tt>-</tt> ou quatre signes <tt>-</tt> selon
        la longueur du tiret. Si le tiret repr&eacute;sente
        un mot, laissez des espaces autour, comme si c'&eacute;tait vraiment le
        mot. Si c'est seulement une partie de mot, alors pas d'espace. Joignez-le au
        reste du mot.
    </li>
  </ol>
<p>Voir aussi les directives pour les tirets et traits d'union
   <a href="#eol_hyphen">en fin de ligne</a> et
   <a href="#eop_hyphen">en fin de page</a>.
</p>
<!-- END RR -->

<p><b>Quelques exemples.</b>
</p>

<table width="100%" align="center" border="1" cellpadding="4" cellspacing="0" summary="Exemples de traits d'union et tirets">
  <tbody>
    <tr>
      <th valign="top" bgcolor="cornsilk">Image de d&eacute;part:</th>
      <th valign="top" bgcolor="cornsilk">Texte corrig&eacute;:</th>
      <th valign="top" bgcolor="cornsilk">Type</th>
    </tr>
    <tr>
      <td valign="top">semi-detached</td>
      <td valign="top"><tt>semi-detached</tt></td>
      <td>Trait d'union</td>
    </tr>
    <tr>
      <td valign="top">three- and four-part harmony</td>
      <td valign="top"><tt>three- and four-part harmony</tt></td>
      <td>Traits d'union</td>
    </tr>
    <tr>
      <td valign="top">discoveries which the Crus-<br>
        aders made and brought home with</td>
      <td valign="top"><tt>discoveries which the Crusaders<br>
        made and brought home with</tt></td>
      <td>Trait d'union</td>
    </tr>
    <tr>
      <td valign="top">factors which mold char-<br>
        acter&mdash;environment, training and heritage,</td>
      <td valign="top"><tt>factors which mold character--environment,<br>
        training and heritage,</tt></td>
      <td>Trait d'union et Em-dash</td>
    </tr>
    <tr>
      <td valign="top">See pages 21&ndash;25</td>
      <td valign="top"><tt>See pages 21-25</tt></td>
      <td>En-dash</td>
    </tr>
    <tr>
      <td valign="top">It was &ndash;14&deg;C outside.</td>
      <td valign="top"><tt>It was -14&deg;C outside.</tt></td>
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
      <td valign="top">&mdash;A plague on both<br> your houses!&mdash;I am dead.</td>
      <td valign="top"><tt>--A plague on both<br> your houses!--I am dead.</tt></td>
      <td>Em-dashes</td>
    </tr>
    <tr>
      <td valign="top">sensations&mdash;sweet, bitter, salt, and sour<br>
        &mdash;if even all of these are simple tastes. What</td>
      <td valign="top"><tt>sensations--sweet, bitter, salt, and sour--if<br>
        even all of these are simple tastes. What</tt></td>
      <td>Em-dashes</td>
    </tr>
    <tr>
      <td valign="top">senses&mdash;touch, smell, hearing, and sight&mdash;<br>
        with which we are here concerned,</td>
      <td valign="top"><tt>senses--touch, smell, hearing, and sight--with<br>
        which we are here concerned,</tt></td>
      <td>Em-dashes</td>
    </tr>
    <tr>
      <td valign="top">It is the east, and Juliet is the sun&mdash;!</td>
      <td valign="top"><tt>It is the east, and Juliet is the sun--!</tt></td>
      <td>Em-dash</td>
    </tr>
    <tr>
      <td valign="top"><img src="../dashes.png" width="300" height="28" alt=""></td>
      <td valign="top"><tt>how a--a--cannon-ball goes----"</tt></td>
      <td>Em-dashes, trait d'union,<br> et tiret long</td>
    </tr>
    <tr>
      <td valign="top">&ldquo;Three hundred&mdash;&mdash;&rdquo; &ldquo;years,&rdquo; she was going to<br>
        say, but the left-hand cat interrupted her.</td>
      <td valign="top"><tt>"Three hundred----" "years," she was going to<br>
        say, but the left-hand cat interrupted her.</tt></td>
      <td>Tiret long</td>
    </tr>
    <tr>
      <td valign="top">As the witness Mr. &mdash;&mdash; testified,</td>
      <td valign="top"><tt>As the witness Mr. ---- testified,</tt></td>
      <td>Tiret long</td>
    </tr>
    <tr>
      <td valign="top">As the witness Mr. S&mdash;&mdash; testified,</td>
      <td valign="top"><tt>As the witness Mr. S---- testified,</tt></td>
      <td>Tiret long</td>
    </tr>
    <tr>
      <td valign="top">the famous detective of &mdash;&mdash;B Baker St.</td>
      <td valign="top"><tt>the famous detective of ----B Baker St.</tt></td>
      <td>Tiret long</td>
    </tr>
    <tr>
      <td valign="top">&ldquo;You &mdash;&mdash; Yankee&rdquo;, she yelled.</td>
      <td valign="top"><tt>"You ---- Yankee", she yelled.</tt></td>
      <td>Tiret long</td>
    </tr>
    <tr>
      <td valign="top">&ldquo;I am not a d&mdash;d Yankee&rdquo;, he replied.</td>
      <td valign="top"><tt>"I am not a d--d Yankee", he replied.</tt></td>
      <td>Em-dash</td>
    </tr>
  </tbody>
</table>
<p class="backtotop"><a href="#top">Retour au d&eacute;but</a></p>


<h3><a name="eol_hyphen">Traits d'union en fin de ligne</a></h3>
<p>Lorsqu'un trait d'union est pr&eacute;sent en fin de ligne, rejoignez les deux
   morceaux du mot coup&eacute;. Retirez ce faisant le trait d'union, &agrave; moins que ce
   ne soit r&eacute;ellement un mot compos&eacute; comme porte-manteau. Voyez les exemples
   de chaque type &agrave; la section <a href="#em_dashes">Tirets, traits d'unions,
   et signe &ldquo;moins&rdquo;</a>. Laissez le mot une fois rejoint sur la ligne
   sup&eacute;rieure, et ins&eacute;rez un retour &agrave; la ligne apr&egrave;s ce mot
   pour conserver la disposition des lignes: cela rend la t&acirc;che plus facile aux
   correcteurs apr&egrave;s vous.
   Si le mot coup&eacute; est suivi par de la ponctuation, d&eacute;placez &eacute;galement
   la ponctuation sur la ligne sup&eacute;rieure.
</p>
<p>Laissez le trait d'union aux mots qui s'&eacute;crivaient anciennement avec un trait
   d'union mais qui n'en ont plus aujourd'hui. Si vous n'&ecirc;tes pas s&ucirc;r de savoir si
   l'auteur a mis un tiret ou non, laissez le tiret, mettez un <tt>*</tt> apr&egrave;s, et
   rejoignez les deux parties du mot, comme ceci&nbsp;: <tt>to-*day</tt>, <tt>long-*temps</tt>. L'ast&eacute;risque
   attirera l'attention du post-processeur, qui a acc&egrave;s &agrave; toutes les pages et qui
   verra comment l'auteur &eacute;crit habituellement le mot.
</p>
<p>De m&ecirc;me, si un tiret se trouve au d&eacute;but ou &agrave; la fin d'une ligne,
   rejoignez-le avec l'autre ligne pour que ce tiret ne soit pas adjacent &agrave; un espace ou
   un retour &agrave; la ligne. Cela ne concerne pas la po&eacute;sie, o&ugrave; nous laissons
   le tiret &agrave; sa place s'il est pr&eacute;sent en d&eacute;but ou en fin de vers.
   Voir les exemples de la section <a href="#em_dashes">Tirets, traits d'unions,
   et signe &ldquo;moins&rdquo;</a>.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Retour au d&eacute;but</a></p>


<h3><a name="eop_hyphen">Traits d'union et tirets en fin de page</a></h3>
<p>Laissez le trait d'union ou le tiret &agrave; la fin de la derni&egrave;re ligne, mais marquez le avec
   un ast&eacute;risque (<tt>*</tt>) apr&egrave;s le trait d'union ou le tiret. Par exemple:
</p>
<table width="100%" align="center" border="1" cellpadding="4" cellspacing="0" summary="Exemple de tirets en fin de page">
  <tbody>
    <tr><th align="left" bgcolor="cornsilk">Image de d&eacute;part:</th></tr>
    <tr>
      <td valign="top">something Pat had already become accus-</td>
    </tr>
    <tr><th align="left" bgcolor="cornsilk">Texte corrig&eacute;:</th></tr>
    <tr>
      <td valign="top"><tt>something Pat had already become accus-*</tt></td>
    </tr>
  </tbody>
</table>
<p>Pour les pages qui commencent avec un mot commenc&eacute; &agrave; la fin de la page
   pr&eacute;c&eacute;dente, ou un tiret <i>em-dash</i>, placez un <tt>*</tt> avant
   le fragment de mot ou le tiret. Pour continuer l'exemple ci-dessus:
</p>
<table width="100%" align="center" border="1" cellpadding="4" cellspacing="0" summary="Exemple de c&eacute;sure en d&eacute;but de page">
  <tbody>
    <tr><th align="left" bgcolor="cornsilk">Original Image:</th></tr>
    <tr>
      <td valign="top">tomed to from having to do his own family</td>
    </tr>
    <tr><th align="left" bgcolor="cornsilk">Correctly Proofread Text:</th></tr>
    <tr>
      <td valign="top"><tt>*tomed to from having to do his own family</tt></td>
    </tr>
  </tbody>
</table>
<p>Ces signes indiquent au post-processeur, quand il produit le texte final,
   qu'il doit rejoindre les deux parties du mot.
   Ne rejoignez pas vous-m&ecirc;me les fragments de mots pr&eacute;sents sur
   deux pages diff&eacute;rentes.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Retour au d&eacute;but</a></p>


<h3><a name="period_p">Points de suspension &ldquo;...&rdquo;</a></h3>
<p>Les r&egrave;gles sont diff&eacute;rentes selon que le texte est en anglais ou non.
</p>
<p><b>ANGLAIS:</b> Les points de suspension doivent comporter exactement trois points.
   Concernant les espaces &eacute;ventuels avant ou apr&egrave;s, consid&eacute;rez
   les trois points en milieu de phrase comme s'ils &eacute;taient un mot
   (c'est-&agrave;-dire, laissez g&eacute;n&eacute;ralement un espace avant et apr&egrave;s).
   En fin de phrase, consid&eacute;rez les points de suspension
   comme une ponctuation finissant la phrase, sans espace avant.
</p>
<p>Notez qu'il y aura &eacute;galement un signe de ponctuation pour finir la phrase, ce
   qui fait que si ce signe est un point il y aura en tout 4 points.
   Vous devrez si n&eacute;cessaire enlever des points ou en rajouter pour qu'il y en
   ait exactement trois (ou quatre, selon le cas).
   Un bon indice pour d&eacute;terminer si l'on est &agrave; la fin d'une phrase,
   est la pr&eacute;sence d'un mot commen&ccedil;ant par une capitale &agrave;
   la suite des points de suspension, ou bien une ponctuation finissant les phrases
   (comme un point d'interrogation ou d'exclamation).
</p>
<p><b>AUTRES LANGUES:</b> Suivez la r&egrave;gle &laquo;Respectez le style utilis&eacute; sur la
   page imprim&eacute;e&raquo;. Mettez autant de points qu'il y en a d'imprim&eacute;s, et ins&eacute;rez des
   espaces s'il y en a avant ou entre les points de suspension. Si, sur une page donn&eacute;e,
   ce n'est pas clair, ins&eacute;rez une note <tt>[**pas clair]</tt> pour attirer l'attention
   du post-processeur.
   (Note: les post-processeurs devraient remplacer ces espaces par des espaces ins&eacute;cables.)
</p>
<!-- END RR -->
<p>Exemples (anglais uniquement):
</p>
<table width="100%" align="center" border="1" cellpadding="4" cellspacing="0" summary="Exemples de points de suspension">
  <tbody>
    <tr>
      <th valign="top" bgcolor="cornsilk">Image de d&eacute;part:</th>
      <th valign="top" bgcolor="cornsilk">Texte corrig&eacute;:</th>
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
<p class="backtotop"><a href="#top">Retour au d&eacute;but</a></p>


<h3><a name="contract">Contractions</a></h3>
<p>En anglais, enlevez les espaces des contractions. Par exemple: <tt>would&nbsp;n't</tt>
   doit &ecirc;tre transcrit en <tt>wouldn't</tt>, et <tt>'t&nbsp;is</tt> en <tt>'tis</tt>.
</p>
<p>C'&eacute;tait une convention utilis&eacute;e souvent par les imprimeurs
   du 19<sup>&egrave;me</sup> si&egrave;cle pour indiquer que would et not
   &eacute;taient originellement deux mots s&eacute;par&eacute;s. Parfois
   aussi, c'est une erreur d'OCR. Enlevez l'espace en trop dans tous les cas.
</p>
<p>Certains responsables de projet recommanderont dans les <a href="#comments">commentaires
   de projet</a> de ne pas enlever les espaces dans les contractions, en
   particulier dans des livres contenant des passages argot, dialecte ou po&eacute;sie.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Retour au d&eacute;but</a></p>


<h3><a name="fract_s">Fractions</a></h3>
<p>Convertissez les fractions de cette mani&egrave;re: <tt>&frac14;</tt> devient <tt>1/4</tt> et
   <tt>2&frac12;</tt> devient <tt>2-1/2</tt>.
   Le trait d'union emp&ecirc;che les deux parties d'&ecirc;tre s&eacute;par&eacute;es par un retour
   &agrave; la ligne au cours du r&eacute;-assemblage des lignes en post-processing.
   &Agrave; moins d'une exception en ce sens dans les <a href="#comments">commentaires
   de projet</a>, veuillez ne pas utiliser les caract&egrave;res sp&eacute;ciaux de fractions.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Retour au d&eacute;but</a></p>


<h3><a name="a_chars">Caract&egrave;res accentu&eacute;s et non-ASCII</a></h3>
<p>Repr&eacute;sentez ces caract&egrave;res avec le caract&egrave;re appropri&eacute; en UTF-8. Pour les caract&egrave;res qui
   ne sont pas pr&eacute;sents dans le jeu Unicode, consultez les instructions du responsable de
   projet dans les <a href="#comments">commentaires de projet</a>.
   La section <a href="#insert_char">Saisie des caract&egrave;res sp&eacute;ciaux</a> contient
   des informations sur la fa&ccedil;on de saisir certains caract&egrave;res qui ne sont pas
   pr&eacute;sents sur votre clavier.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Retour au d&eacute;but</a></p>


<h3><a name="d_chars">Caract&egrave;res avec marques diacritiques</a></h3>
<p>Sur certains projets, vous trouverez des caract&egrave;res avec des signes sp&eacute;ciaux
   au-dessus ou au-dessous du caract&egrave;re latin normal. Ce sont des <i>marques
   diacritiques</i>. Elles indiquent une prononciation sp&eacute;ciale.
</p>
<p>Si un tel caract&egrave;re n'existe pas en Unicode, il faut le repr&eacute;senter &agrave; l'aide
   des <i>caract&egrave;res diacritiques combinatoires</i>: ce sont des symboles Unicode
   qui n'apparaissent pas seuls, mais au-dessus ou en dessous de la lettre
   pr&eacute;c&eacute;dente. On entre donc d'abord la lettre de base, puis le caract&egrave;re
   combinatoire, avec les utilitaires ou les programmes mentionn&eacute;s
   &agrave; la section <a href="#insert_char">Saisie des caract&egrave;res sp&eacute;ciaux</a>.
</p>
<p>Sur certains syst&egrave;mes, les signes diacritiques peuvent ne pas appara&icirc;tre
   exactement o&ugrave; ils devraient, mais par exemple d&eacute;cal&eacute;s sur la droite.
   Il faut n&eacute;anmoins les utiliser, puisqu'on peut les lire sur d'autres
   syst&egrave;mes. N&eacute;anmoins si pour une raison quelconque vous ne pouvez voir ou
   saisir les caract&egrave;res combinatoires correctement, indiquez ces lettres avec
   une <tt>[**note]</tt>. Notez qu'il existe &eacute;galement des &ldquo;<i>Spacing modifier letters</i>&rdquo;;
   ceux-ci ne doivent pas &ecirc;tre utilis&eacute;s.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Retour au d&eacute;but</a></p>


<h3><a name="f_chars">Alphabets non latins</a></h3>
<p>Certains textes utilisent des caract&egrave;res non latins (autrement dit, autres
   que A..Z). Par exemple, les caract&egrave;res Grecs, Cyrilliques (utilis&eacute; pour
   les langues russes, slaves, ou d'autres), H&eacute;breux, ou Arabes.
</p>
<p>Ces caract&egrave;res devraient &ecirc;tre ins&eacute;r&eacute;s directement dans le texte tout comme le
   sont les caract&egrave;res latins (<b>SANS translit&eacute;ration</b>).
</p>
<p>Si un document est &eacute;crit enti&egrave;rement dans un script non Latin, le mieux est d'installer
   un clavier qui supporte la langue en question. Consultez la documentation de votre
   syst&egrave;me d'exploitation.
</p>
<p>Si le script n'appara&icirc;t que par endroits, vous pouvez utiliser un programme s&eacute;par&eacute; pour
   saisir ces caract&egrave;res. Quelques programmes sont indiqu&eacute;s &agrave; la section
   <a href="#insert_char">Saisie des caract&egrave;res sp&eacute;ciaux</a>.
</p>
<p>Si vous n'&ecirc;tes pas certain d'un caract&egrave;re ou d'un accent, indiquez-le avec une
   [**note]
   pour le signaler au prochain correcteur ou au post-processeur.
</p>
<p>Pour les scripts posant des difficult&eacute;s particuli&egrave;res de saisie, comme l'arabe,
   indiquez le texte par les marques appropri&eacute;es: <tt>[Arabic:&nbsp;**]</tt>.
   Ajoutez bien les deux &eacute;toiles <tt>**</tt>, pour attirer l'attention du post-processeur.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Retour au d&eacute;but</a></p>


<h3><a name="supers">Exposants</a></h3>
<p>Les livres anciens abr&eacute;geaient souvent les mots en contractions, et les
   imprimaient en exposant. Nous repr&eacute;sentons ceci en ins&eacute;rant un
   chapeau (<tt>^</tt>) suivi par le texte en exposant. Si le texte en exposant
   se compose de plus d'un caract&egrave;re, entourez &eacute;galement ce texte par des accolades
   <tt>{</tt> et <tt>}</tt>. Par exemple:
</p>
<table width="100%" align="center" border="1" cellpadding="4" cellspacing="0" summary="Exemple de texte en exposant">
  <tbody>
    <tr><th align="left" bgcolor="cornsilk">Image de d&eacute;part:</th></tr>
    <tr>
      <td valign="top">Gen<sup>rl</sup> Washington defeated L<sup>d</sup> Cornwall's army.</td>
    </tr>
    <tr><th align="left" bgcolor="cornsilk">Texte corrig&eacute;:</th></tr>
    <tr>
      <td valign="top"><tt>Gen^{rl} Washington defeated L^d Cornwall's army.</tt></td>
    </tr>
  </tbody>
</table>
<p>Si l'exposant correspond &agrave; un appel de note, appliquez la r&egrave;gle pour
   les <a href="#footnotes">notes de bas de page</a> &agrave; la place.
</p>
<p>Le responsable de projet peut demander dans les <a href="#comments">commentaires de projet</a>
   que les exposants soient trait&eacute;s diff&eacute;remment pour un projet particulier.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Retour au d&eacute;but</a></p>


<h3><a name="subscr">Texte en Indice</a></h3>
<p>On trouve la notation &ldquo;indice&rdquo; dans des ouvrages scientifiques, rarement
   ailleurs. Indiquez l'indice en le faisant pr&eacute;c&eacute;der d'un signe &ldquo;soulign&eacute;&rdquo; <tt>_</tt>,
   et mettez le texte en indice entre accolades <tt>{</tt> et <tt>}</tt>.
   Par exemple:
</p>
<table width="100%" align="center" border="1" cellpadding="4" cellspacing="0" summary="Exemple de texte en indice">
  <tbody>
    <tr><th align="left" bgcolor="cornsilk">Image de d&eacute;part:</th></tr>
    <tr>
      <td valign="top">H<sub>2</sub>O.</td>
    </tr>
    <tr><th align="left" bgcolor="cornsilk">Texte corrig&eacute;:</th></tr>
    <tr>
      <td valign="top"><tt>H_{2}O.</tt></td>
    </tr>
  </tbody>
</table>
<!-- END RR -->
<p class="backtotop"><a href="#top">Retour au d&eacute;but</a></p>


<h3><a name="drop_caps">Lettre de d&eacute;but de paragraphe grande ou orn&eacute;e</a></h3>
<p>Souvent, la premi&egrave;re lettre d'un chapitre, section ou paragraphe est imprim&eacute;e
   tr&egrave;s grande et orn&eacute;e (une lettrine). Dans votre texte, laissez simplement la
   lettre. Voyez &eacute;galement la section <a href="#chap_head">T&ecirc;te de chapitre</a>.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Retour au d&eacute;but</a></p>


<h3><a name="small_caps">Petites capitales</a></h3>
<p>Veuillez corriger uniquement les caract&egrave;res des
   <span style="font-variant: small-caps">Petites Capitales</span>
   (autrement dit des lettres capitales plus petites que des capitales ordinaires),
   sans vous soucier des changements entre minuscules et majuscules.
   Si le texte reconnu est d&eacute;j&agrave; en LETTRES CAPITALES, ou en
   &ldquo;Capitales Altern&eacute;es&rdquo;, ou tout en minuscules, laissez-le ainsi.
   Les petites capitales apparaissent parfois dans votre page de travail
   entour&eacute; des marques <tt>&lt;sc&gt;</tt> et <tt>&lt;/sc&gt;</tt>;
   voyez la section <a href="#formatting">Formatage</a>.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Retour au d&eacute;but</a></p>


<table width="100%" border="0" cellspacing="0" cellpadding="6" summary="Relecture &agrave; l'&eacute;chelle du paragraphe">
  <tbody>
    <tr>
      <td bgcolor="silver"><h2 style="margin-bottom: 0; margin-top: 0;">Relecture &agrave; l'&eacute;chelle du paragraphe:</h2></td>
    </tr>
  </tbody>
</table>


<h3><a name="line_br">Retours &agrave; la ligne</a></h3>
<p><b>Laissez tous les retours &agrave; la ligne</b> de mani&egrave;re &agrave; ce
   que le correcteur suivant puisse comparer les textes facilement.
   Faites particuli&egrave;rement attention &agrave; cela lorsque vous rejoignez
   des <a href="#eol_hyphen">mots coup&eacute;s en fin de ligne</a>, ou lorsque
   vous d&eacute;placez des mots adjacents &agrave; des <a href="#em_dashes">tirets</a>.
   Si le correcteur du tour pr&eacute;c&eacute;dent a supprim&eacute; les retours &agrave;
   la ligne, remettez-les, pour que les lignes correspondent &agrave; l'image de la page.
</p>
<!-- END RR -->
<!-- We should have an example right here for this. -->
<p class="backtotop"><a href="#top">Retour au d&eacute;but</a></p>


<h3><a name="chap_head">T&ecirc;tes de chapitres</a></h3>
<p>Laissez les t&ecirc;tes de chapitres dans le texte telles qu'elles sont imprim&eacute;es.
</p>
<p>Une t&ecirc;te de chapitre peut commencer plus bas sur la page qu'un <a href="#page_hf">en-t&ecirc;te de
   page</a> et n'a pas de num&eacute;ro de page sur la m&ecirc;me ligne. Les t&ecirc;tes de
   chapitres sont souvent imprim&eacute;es enti&egrave;rement en majuscules, si c'est le cas,
   laissez-les telles quelles.
</p>
<p>Regardez bien s'il ne manque pas un guillemet (&nbsp;"&nbsp;) au d&eacute;but du premier
   paragraphe, devant une grande majuscule, soit par d&eacute;faut de
   reconnaissance d&ucirc; &agrave; l'OCR, soit parce qu'il a &eacute;t&eacute; omis
   par l'&eacute;diteur pour des raisons esth&eacute;tiques.
   Si l'auteur commence le paragraphe avec un dialogue, ins&eacute;rez le guillemet.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Retour au d&eacute;but</a></p>


<h3><a name="para_space">Espacement/Indentation des paragraphes</a></h3>
<p>Ajoutez une ligne blanche avant le d&eacute;but de chaque paragraphe,
   m&ecirc;me si le paragraphe d&eacute;bute en haut de la page.
   N'indentez pas le d&eacute;but des paragraphes (mais si des paragraphes
   sont d&eacute;j&agrave; indent&eacute;s, ne prenez pas la peine d'enlever les
   espaces en trop &ndash; cela se fait facilement &agrave; la phase de
   post-processing).
</p>
<p>Voyez l'exemple &agrave; la section sur les <a href="#para_side">commentaires en marge</a>.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Retour au d&eacute;but</a></p>


<h3><a name="page_hf">En-t&ecirc;tes et pieds de page</a></h3>
<p>Enlevez les en-t&ecirc;tes et pieds de page (mais <em>pas</em> les <a href="#footnotes">notes de
   bas de page</a>) du texte.
</p>
<p>Ces en-t&ecirc;tes sont g&eacute;n&eacute;ralement sur la partie sup&eacute;rieure de l'image et ont un
   num&eacute;ro de page &agrave; leur oppos&eacute;. Les en-t&ecirc;tes peuvent &ecirc;tre les m&ecirc;mes
   au cours du livre (souvent le titre du livre et le nom de l'auteur); ils peuvent &ecirc;tre
   identiques pour chaque chapitre (souvent le num&eacute;ro du chapitre); ou ils peuvent
   &ecirc;tre diff&eacute;rents pour chaque page (d&eacute;crivant l'action sur cette page).
   Supprimez-les tous, quels qu'ils soient, y compris le num&eacute;ro de page.
   Les lignes blanches exc&eacute;dentaires doivent &ecirc;tre retir&eacute;es
   (sauf quand nous les ajoutons intentionnellement conform&eacute;ment aux directives
   de relecture). Mais ne vous souciez pas des &eacute;ventuelles lignes blanches
   en bas de page: elles sont retir&eacute;es automatiquement
   lorsque la page en cours est enregistr&eacute;e.
</p>
<p>Les pieds de page, en bas de la page, contiennent parfois un num&eacute;ro de page
   ou d'autres marques sans rapport avec le sujet, qui ne font pas partie de
   ce qu'a &eacute;crit l'auteur.
</p>
<!-- END RR -->

<p>Une <a href="#chap_head">t&ecirc;te de chapitre</a> commence en g&eacute;n&eacute;ral plus bas sur
   la page et n'a pas de num&eacute;ro de page sur la m&ecirc;me ligne. Laissez
   les t&ecirc;tes de chapitres en place &ndash; voir l'exemple plus bas.
</p>
<table width="100%" align="center" border="1" cellpadding="4" cellspacing="0" summary="en-t&ecirc;tes et pieds de page">
  <tbody>
    <tr><th align="left" bgcolor="cornsilk">Image de d&eacute;part:</th></tr>
    <tr align="left">
      <td width="100%" valign="top"><img src="../foot.png" alt="" width="500" height="860"><br>
      </td>
    </tr>
    <tr><th align="left" bgcolor="cornsilk">Texte corrig&eacute;:</th></tr>
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
<p class="backtotop"><a href="#top">Retour au d&eacute;but</a></p>


<h3><a name="illust">Illustrations</a></h3>
<p>Ignorez les illustrations, mais corrigez la l&eacute;gende de l'illustration
   comme elle est imprim&eacute;e, en pr&eacute;servant les retours &agrave; la ligne.
   Si la l&eacute;gende de l'illustration se trouve au milieu d'un
   paragraphe, utilisez des lignes blanches pour la s&eacute;parer du reste du texte.
   Des indications comme &ldquo;Voir page 66&rdquo;, ou un titre faisant partie
   de l'illustration, doivent &ecirc;tre compris dans la l&eacute;gende.
</p>
<p>Une page ne comportant qu'une illustration sans texte sera probablement
   marqu&eacute;e comme <tt>[Blank Page]</tt>. N'y changez rien.
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
      <th align="left" bgcolor="cornsilk">Texte corrig&eacute;:</th>
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
<table width="100%" align="center" border="1" cellpadding="4" cellspacing="0" summary="Illustration au milieu d'un paragraphe">
  <tbody>
    <tr>
      <th align="left" bgcolor="cornsilk">Image (illustration au milieu d'un paragraphe)</th>
    </tr>
    <tr align="left">
      <td width="100%" valign="top"> <img src="../illust2.png" alt="" width="500" height="514"> <br>
      </td>
    </tr>
    <tr>
      <th align="left" bgcolor="cornsilk">Texte corrig&eacute;:</th>
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
<p class="backtotop"><a href="#top">Retour au d&eacute;but</a></p>


<h3><a name="footnotes">Notes de bas de page et de fin</a></h3>
<p>Les notes de bas de page sont corrig&eacute;es en laissant le texte de la
   note l&agrave; o&ugrave; il se trouve en bas de la page, et en pla&ccedil;ant une marque
   dans le texte, l&agrave; o&ugrave; la note est r&eacute;f&eacute;renc&eacute;e.
</p>
<p>Le num&eacute;ro ou la lettre qui indique la r&eacute;f&eacute;rence, dans le texte,
   sera encadr&eacute; par des crochets <tt>[</tt> et <tt>]</tt> et sera plac&eacute;
   juste &agrave; c&ocirc;t&eacute; du mot sur lequel porte la note<tt>[1]</tt>, ou le signe
   de ponctuation,<tt>[2]</tt> comme nous l'avons fait dans cette phrase.
   Les appels de notes peuvent &ecirc;tre des lettres, des chiffres ou
   des symboles sp&eacute;ciaux.
   Quand les notes sont marqu&eacute;es par un symbole ou une s&eacute;rie de symboles
   (*, &dagger;, &Dagger;, &sect;, etc.), nous rempla&ccedil;ons tous ces signes par
   la marque <tt>[*]</tt>, dans le corps du texte, et <tt>*</tt> dans la note elle-m&ecirc;me.
</p>
<p>En bas de la page, corrigez le texte de la note tel qu'il est imprim&eacute;,
   en pr&eacute;servant les retours &agrave; la ligne. Utilisez
   bien la m&ecirc;me marque de note dans la note et dans le texte (l&agrave;
   o&ugrave; la note est r&eacute;f&eacute;renc&eacute;e).
   Indiquez uniquement les caract&egrave;res constituant la r&eacute;f&eacute;rence
   de la note, sans crochets ni ponctuation.
</p>
<p>Placez chaque note sur des lignes s&eacute;par&eacute;es, par ordre d'apparence,
   avec une ligne blanche avant chaque note.
</p>
<!-- END RR -->
<p>N'incluez pas les &eacute;ventuelles lignes horizontales s&eacute;parant les notes du texte principal.
</p>
<p>Les <b>notes de fin</b> sont simplement des notes de bas de page qui ont &eacute;t&eacute;
   plac&eacute;es en fin de chapitre, ou en fin de livre, au lieu d'&ecirc;tre en bas de page.
   Traitez-les comme des notes de bas de page. Quand vous voyez la r&eacute;f&eacute;rence dans le
   texte, entourez-la par des crochets. Si vous corrigez une des pages de fin, l&agrave;
   o&ugrave; sont les notes, mettez une ligne blanche avant chaque note, pour qu'elles
   soient clairement s&eacute;par&eacute;es.
</p>
<!-- Need an example of Endnotes, maybe? Good idea!-->

<p>Les notes dans les <a href="#tables">Tableaux</a> conservent l'emplacement
   qu'elles ont dans l'image.
</p>

<table width="100%" border="1" cellpadding="4" cellspacing="0" align="center" summary="Exemple de note">
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
    <tr>
      <th valign="top" align="left" bgcolor="cornsilk">Texte corrig&eacute;:</th>
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
<table width="100%" align="center" border="1" cellpadding="4" cellspacing="0" summary="Exemple de note en bas de page">
  <tbody>
    <tr><th align="left" bgcolor="cornsilk">Po&eacute;sie avec note, texte original:</th></tr>
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
    <tr><th align="left" bgcolor="cornsilk">Texte corrig&eacute;:</th></tr>
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
<p class="backtotop"><a href="#top">Retour au d&eacute;but</a></p>


<h3><a name="para_side">Commentaires en marge</a></h3>
<p>Certains livres ont de petites descriptions des paragraphes sur le c&ocirc;t&eacute; du
   texte. Ce sont les notes en marge (&ldquo;Sidenotes&rdquo;).
   Corrigez la note pour qu'elle ressemble au texte
   imprim&eacute;, en gardant les retours &agrave; la ligne (tout en traitant les
   <a href="#eol_hyphen">tirets et traits d'union en fin de ligne</a> normalement).
   Laissez une ligne blanche avant la note, et une autre apr&egrave;s, pour que l'on puisse
   bien distinguer la note du reste du texte.
   Le texte venant de l'OCR placera le texte des notes n'importe o&ugrave; sur
   la page, ou m&ecirc;me m&eacute;langera les lignes des notes avec les lignes du texte. Rassemblez
   le texte de la note pour que toutes les lignes soient ensemble. Ensuite, mettez
   la note o&ugrave; vous voulez sur la page.
</p>
<!-- END RR -->

<table width="100%" align="center" border="1" cellpadding="4" cellspacing="0" summary="Exemples de note en marge">
  <tbody>
    <tr valign="top">
      <th align="left" bgcolor="cornsilk">Image de d&eacute;part:</th>
    </tr>
    <tr valign="top">
      <td width="100%" align="left"><img src="../side.png" alt="" width="550" height="800"><br>
      </td>
    </tr>
    <tr valign="top">
      <th align="left" bgcolor="cornsilk">Texte corrig&eacute;:</th>
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
<p class="backtotop"><a href="#top">Retour au d&eacute;but</a></p>


<h3><a name="mult_col">Colonnes Multiples</a></h3>
<p>Lorsque du texte ordinaire a &eacute;t&eacute; imprim&eacute; en plusieurs colonnes,
   r&eacute;unissez ces colonnes multiples en une seule colonne.
   Placez le texte de la colonne la plus &agrave; gauche en premier puis le
   texte des autres colonnes &agrave; sa suite.
   Ne faites rien de particulier pour marquer la s&eacute;paration des colonnes,
   rejoignez-les simplement.
   Voyez le bas de l'exemple de la section <a href="#para_side">Commentaires en marge</a>.
</p>
<p>Voir aussi les sections <a href="#bk_index">Index</a> et <a href="#tables">Tableaux</a>.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Retour au d&eacute;but</a></p>


<h3><a name="tables">Tableaux</a></h3>
<p>Le travail du relecteur consiste &agrave; corriger les erreurs dans le texte,
   pas &agrave; soigner sa pr&eacute;sentation.
   S&eacute;parez les cases du tableau par des espaces en quantit&eacute; suffisante,
   mais ne vous vous occupez pas de l'alignement.
   Conservez les retours &agrave; la ligne (tout en traitant les
   <a href="#eol_hyphen">tirets et traits d'union en fin de ligne</a> normalement).
   Ignorez les rang&eacute;es de points ou d'autre ponctuation (filets de conduite)
   utilis&eacute;es pour repr&eacute;senter visuellement les alignements horizontaux.
</p>
<p>Les <b>notes</b> restent o&ugrave; elles sont. Voir la section sur les <a href="#footnotes">notes de bas de page</a>.
</p>
<!-- END RR -->
<table width="100%" align="center" border="1" cellpadding="4" cellspacing="0" summary="Exemple de tableau">
  <tbody>
    <tr><th align="left" bgcolor="cornsilk">Image de d&eacute;part:</th></tr>
    <tr align="left">
      <td width="100%" valign="top"> <img src="../table2.png" alt="" width="500" height="304"><br>
      </td>
    </tr>
    <tr><th align="left" bgcolor="cornsilk">Texte corrig&eacute;:</th></tr>
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
<table width="100%" align="center" border="1" cellpadding="4" cellspacing="0" summary="Exemple de tableau">
  <tbody>
    <tr><th align="left" bgcolor="cornsilk">Image de d&eacute;part:</th></tr>
    <tr align="left">
      <td width="100%" valign="top"> <img src="../table3.png" alt="" width="480" height="231"><br>
      </td>
    </tr>
    <tr><th align="left" bgcolor="cornsilk">Texte corrig&eacute;:</th></tr>
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
<p class="backtotop"><a href="#top">Retour au d&eacute;but</a></p>


<h3><a name="poetry">Po&eacute;sie/&Eacute;pigrammes</a></h3>
<p>Mettez une ligne blanche avant un po&egrave;me, et une ligne blanche apr&egrave;s,
   de sorte que le formateurs voient clairement o&ugrave; le po&egrave;me commence et finit.
   Calez tous les vers &agrave; gauche, et gardez les retours &agrave; la ligne. Ins&eacute;rez
   une ligne vide entre les strophes (ou couplets) quand il y en a une dans l'image.
</p>
<p>Les <a href="#line_no">Num&eacute;ros de ligne</a> sont conserv&eacute;s s'ils sont
   pr&eacute;sents dans l'image.
</p>
<p>Regardez les <a href="#comments">commentaires de projet</a> pour des instructions sp&eacute;cifiques.
</p>
<!-- END RR -->

<table width="100%" align="center" border="1" cellpadding="4" cellspacing="0" summary="Exemple de po&eacute;sie">
  <tbody>
    <tr><th align="left" bgcolor="cornsilk">Image de d&eacute;part:</th></tr>
    <tr align="left">
      <td width="100%" valign="top"> <img src="../poetry2.png" alt="" width="480" height="385"> <br>
      </td>
    </tr>
    <tr><th align="left" bgcolor="cornsilk">Texte corrig&eacute;:</th></tr>
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
<p class="backtotop"><a href="#top">Retour au d&eacute;but</a></p>


<h3><a name="line_no">Num&eacute;ros de ligne</a></h3>
<p>Il y a souvent des num&eacute;ros de lignes dans les livres de po&eacute;sie,
   situ&eacute;s g&eacute;n&eacute;ralement dans la marge tous les cinq ou dix vers.
   Gardez les num&eacute;ros de ligne, et s&eacute;parez-les du reste du texte en
   ins&eacute;rant quelques espaces sur la m&ecirc;me ligne,
   pour que les formateurs puissent les rep&eacute;rer facilement.
   Puisque, dans la po&eacute;sie, les retours &agrave; la ligne seront conserv&eacute;s dans le livre
   &eacute;lectronique final, les num&eacute;ros de ligne seront utiles au lecteur.
</p>
<!-- END RR -->
<!-- We need an example image and text for this. -->
<p class="backtotop"><a href="#top">Retour au d&eacute;but</a></p>


<h3><a name="next_word">Mot isol&eacute; en bas de page</a></h3>
<p>Nous transcrivons en retirant le mot, m&ecirc;me si c'est la seconde moiti&eacute; d'un mot
   coup&eacute; en fin de ligne.
</p>
<p>Dans certains livres anciens, ce mot isol&eacute; en bas de page
   (&ldquo;catchword&rdquo; en anglais, &ldquo;r&eacute;clame&rdquo; en fran&ccedil;ais),
   g&eacute;n&eacute;ralement imprim&eacute; contre la marge de droite, indiquait le premier mot de la page suivante,
   et permettait &agrave; l'imprimeur et au relieur d'identifier plus facilement l'ordre des pages.
   Cela permettait &eacute;galement au lecteur de s'apercevoir quand il tournait plusieurs pages d'un coup.
</p>
<!-- END RR -->
<!-- We need an example here! -->
<p class="backtotop"><a href="#top">Retour au d&eacute;but</a></p>


<table width="100%" border="0" cellspacing="0" cellpadding="6" summary="Relecture &agrave; l'&eacute;chelle de la page">
  <tbody>
    <tr>
      <td bgcolor="silver"><h2 style="margin-bottom: 0; margin-top: 0;">Relecture &agrave; l'&eacute;chelle de la page:</h2></td>
    </tr>
  </tbody>
</table>


<h3><a name="blank_pg">Page blanche</a></h3>
<p>Si la page est blanche ou si elle ne contient qu'une illustration sans
   texte, vous verrez le plus souvent <tt>[Blank Page]</tt> sur votre page de travail.
   Laissez cette marque. Si la page est blanche et que la marque n'appara&icirc;t pas,
   ce n'est pas la peine de l'ajouter.
</p>
<p>Si seulement le texte est pr&eacute;sent et que l'image est blanche, ou s'il
   y a du texte sur la page mais aucun texte dans le cadre &eacute;ditable, suivez la
   proc&eacute;dure indiqu&eacute;e dans le cas d'une <a href="#bad_image">mauvaise image</a>
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
      <th align="left" bgcolor="cornsilk">Texte corrig&eacute;:</th>
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
<p class="backtotop"><a href="#top">Retour au d&eacute;but</a></p>


<h3><a name="toc">Table des mati&egrave;res</a></h3>
<p>Laissez le texte de la table des mati&egrave;res comme il est imprim&eacute; (m&ecirc;me si c'est
   tout en capitales). Appliquez les directives des
   <a href="#small_caps" style="font-variant: small-caps">Petites Capitales</a>
   le cas &eacute;ch&eacute;ant.
</p>
<p>Ignorez les rang&eacute;es de points ou d'autres signes de ponctuation (filets de conduite)
   qui forment des lignes horizontales, entre
   le texte et le num&eacute;ro. Elles seront enlev&eacute;es dans la suite du processus.
</p>
<!-- END RR -->

<table width="100%" align="center" border="1" cellpadding="4" cellspacing="0" summary="Exemple de table des mati&egrave;res">
  <tbody>
    <tr>
      <th align="left" bgcolor="cornsilk">Image de d&eacute;part:</th>
    </tr>
    <tr align="left">
      <td width="100%" valign="top"><img src="../tablec.png" alt="" width="500" height="650">
      </td>
    </tr>
    <tr>
      <th align="left" bgcolor="cornsilk">Texte corrig&eacute;:</th>
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
<p class="backtotop"><a href="#top">Retour au d&eacute;but</a></p>


<h3><a name="bk_index">Index</a></h3>
<p>Il n'est pas n&eacute;cessaire d'aligner les num&eacute;ros de page les uns par rapport aux
   autres (comme sur l'image de la page scann&eacute;e). Assurez-vous simplement que le texte,
   la ponctuation et les num&eacute;ros sont corrects, et gardez les retours &agrave; la ligne.
</p>
<p>Le formatage des index sera trait&eacute; en phase de formatage. Le travail du correcteur
   est simplement de s'assurer que le texte et les num&eacute;ros sont corrects.
</p>
<p>Voir aussi la section sur les <a href="#mult_col">colonnes multiples</a>.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Retour au d&eacute;but</a></p>


<h3><a name="play_n">Th&eacute;&acirc;tre</a></h3>
<p>Dans les dialogues, ins&eacute;rez une ligne blanche avant chaque changement de locuteur (traitez
   cela comme un nouveau paragraphe). Si le nom du personnage se trouve sur une ligne s&eacute;par&eacute;e,
   traitez cette ligne &eacute;galement comme un paragraphe.
</p>
<p>Repr&eacute;sentez les notes de sc&egrave;ne (didascalies) telles qu'elles sont dans le texte
   original: si la note est sur une ligne isol&eacute;e, laissez-la ainsi; si elle est
   au bout d'une ligne de dialogue, laissez-la ainsi.
   Parfois, une note de sc&egrave;ne commence par une parenth&egrave;se ouvrante,
   qui n'est jamais referm&eacute;e.
   Nous gardons cette convention: ne fermez pas la parenth&egrave;se.
</p>
<p>Parfois, en particulier dans les pi&egrave;ces en vers, si un mot est coup&eacute; parce
   qu'il est trop long sur la page imprim&eacute;e, la seconde partie du mot sera parfois
   imprim&eacute;e au-dessus ou au-dessous de la ligne principale et pr&eacute;c&eacute;d&eacute;e
   d'une parenth&egrave;se, au lieu d'avoir une ligne pour elle seule. Rejoignez le mot selon la
   r&egrave;gle g&eacute;n&eacute;rale des <a href="#eol_hyphen">mots coup&eacute;s en fin de ligne</a>.
   Voir l'<a href="#play4">exemple</a>.
</p>
<p>Regardez les <a href="#comments">Commentaires de projet</a>, car le responsable
   de projet peut demander un traitement diff&eacute;rent.
</p>
<!-- END RR -->

<table width="100%" align="center" border="1" cellpadding="4" cellspacing="0" summary="Th&eacute;&acirc;tre, Exemple 1">
  <tbody>
    <tr>
      <th align="left" bgcolor="cornsilk">Image de d&eacute;part:</th>
    </tr>
    <tr align="left">
      <td width="100%" valign="top"><img src="../play1.png" width="500" height="430" alt=""><br>
      </td>
    </tr>
    <tr>
      <th align="left" bgcolor="cornsilk">Texte corrig&eacute;:</th>
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
<table width="100%" align="center" border="1" cellpadding="4" cellspacing="0" summary="Th&eacute;&acirc;tre, Exemple 4">
  <tbody>
    <tr>
      <th align="left" bgcolor="cornsilk">Exemple d'image:</th>
    </tr>
    <tr align="left">
      <td width="100%" valign="top"><img src="../play4.png" width="502" height="98" alt=""><br>
      </td>
    </tr>
    <tr>
      <th align="left" bgcolor="cornsilk">Texte corrig&eacute;:</th>
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
<p class="backtotop"><a href="#top">Retour au d&eacute;but</a></p>


<h3><a name="anything">Tout ce qui n&eacute;cessite &eacute;galement un traitement sp&eacute;cial, ou
   dont vous n'&ecirc;tes pas s&ucirc;r</a></h3>
<p>Si vous rencontrez quelque chose qui n'est pas couvert par ces directives et
   qui vous para&icirc;t avoir besoin d'un traitement sp&eacute;cial, ou que vous n'&ecirc;tes pas s&ucirc;r
   de quelque chose, posez votre question sur le <a href="#forums">forum du projet</a>
   (en pr&eacute;cisant le num&eacute;ro de la page &ndash; num&eacute;ro png &ndash; qui pose probl&egrave;me).
</p>
<p>Il est &eacute;galement recommand&eacute; d'ajouter une note dans le texte
   pour expliquer le probl&egrave;me &agrave; la personne qui travaillera sur cette page
   ensuite (correcteur, formateur ou post-processeur).
   Commencez la note par un crochet ouvrant et deux &eacute;toiles <tt>[**</tt> et
   terminez-la par un crochet fermant <tt>]</tt> pour bien s&eacute;parer
   votre note du texte de l'auteur. Ceci signale
   au post-processeur qu'il doit s'arr&ecirc;ter et examiner ce texte et l'image
   correspondante et r&eacute;soudre le probl&egrave;me.
   Vous pouvez indiquer dans quel tour de relecture vous &ecirc;tes intervenu,
   &agrave; la fin de la note juste avant le <tt>]</tt>, pour que les personnes
   intervenant ensuite savent qui a laiss&eacute; la note.
   Toute note laiss&eacute;e par une personne qui a travaill&eacute; sur cette page
   dans un tour pr&eacute;c&eacute;dent <b>doit absolument</b> &ecirc;tre laiss&eacute;e
   en place. Voir les d&eacute;tails &agrave; la section suivante.
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
<p>Si vous connaissez la r&eacute;ponse &agrave; une question pos&eacute;e
   dans une note par un correcteur dans un tour pr&eacute;c&eacute;dent,
   vous pouvez &eacute;crire un message &agrave; ce correcteur
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


<h3><a name="formatting">Formatage</a></h3>
<p>Il arrivera parfois que du formatage soit d&eacute;j&agrave; pr&eacute;sent dans le texte.
   <b>N'ajoutez pas ces indications de formatage, et ne les corrigez pas</b>;
   ce sera le r&ocirc;le des formateurs plus tard dans le processus.
   N&eacute;anmoins vous pouvez retirer ces indications de formatage si elles
   interf&egrave;rent avec votre travail de relecture. Le bouton <s>&lt;x&gt;</s>
   de l'interface de relecture retirera tout le marquage de la forme
   &lt;i&gt; ou &lt;b&gt; de la portion de texte s&eacute;lectionn&eacute;e.
   Voici des exemples de t&acirc;ches d&eacute;volues aux formateurs:
</p>
<ul>
  <li>&lt;i&gt;italiques&lt;/i&gt;, &lt;b&gt;gras&lt;/b&gt;,
      &lt;sc&gt;Petites Capitales&lt;/sc&gt;</li>
  <li>Texte espac&eacute;</li>
  <li>Changements de taille de caract&egrave;res</li>
  <li>T&ecirc;tes de chapitres et de sections</li>
  <li>Espace suppl&eacute;mentaire, ast&eacute;risques ou lignes entre deux paragraphes</li>
  <li>Notes de bas de page se continuant sur plusieurs pages</li>
  <li>Notes de bas de page r&eacute;f&eacute;renc&eacute;es par des symboles</li>
  <li>Illustrations</li>
  <li>Placement des notes en marge</li>
  <li>Positionnement du contenu dans les tableaux</li>
  <li>Indentation (de la po&eacute;sie par exemple)</li>
  <li>Jonction des lignes coup&eacute;es, dans la po&eacute;sie ou les index</li>
</ul>
<p>Si le relecteur pr&eacute;c&eacute;dent a ins&eacute;r&eacute; des indications de formatage,
   veuillez prendre un moment pour envoyer un avis rapide &agrave; cette personne.
   En cliquant sur son nom dans l'interface de relecture, vous pouvez lui
   envoyer un message priv&eacute;, dans lequel vous pouvez rappeler comment traiter
   la situation &agrave; l'avenir.
   <b>Souvenez-vous: laissez le formatage pour les tours de formatage.</b>
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Retour au d&eacute;but</a></p>


<h3><a name="common_OCR">Probl&egrave;mes d'OCR courants</a></h3>
<p>Les logiciels d'OCR (Reconnaissance Optique de Caract&egrave;res) ont souvent des
   difficult&eacute;s pour faire la diff&eacute;rence entre des caract&egrave;res de forme similaire.
   Par exemple:
</p>
<ul>
  <li>Le chiffre &lsquo;1&rsquo; (un), la lettre minuscule &lsquo;l&rsquo; (L), et la lettre majuscule &lsquo;I&rsquo; (i).
    Notez que dans certaines polices de caract&egrave;re le chiffre &ldquo;un&rdquo; peut ressembler
    &agrave; un petit <small>I</small> (comme une lettre &lsquo;i&rsquo; en petite capitale).</li>
  <li>Le chiffre &lsquo;0&rsquo; (z&eacute;ro), et la lettre majuscule &lsquo;O&rsquo;.</li>
  <li>Les tirets et traits d'union: relisez-les attentivement; les logiciels
    d'OCR &ndash; qu'on le veuille ou non &ndash; mettent souvent un simple trait
    d'union &agrave; la place d'un tiret (que nous repr&eacute;sentons par deux signes moins <tt>--</tt>)
    Voyez les sections sur les <a href="#eol_hyphen">mots coup&eacute;s en fin de ligne</a>
    et les <a href="#em_dashes">tirets</a> pour plus de d&eacute;tails.</li>
  <li>Les parenth&egrave;ses ( ) et les accolades { }.</li>
</ul>
<p>Faites attention &agrave; ces probl&egrave;mes. Lisez le contexte de la phrase
   pour d&eacute;terminer quel est le caract&egrave;re correct, et soyez
   attentifs &ndash; souvent votre cerveau corrige automatiquement ces erreurs pendant que vous lisez.
</p>
<p>L'utilisation d'une police de caract&egrave;res &agrave; espacement fixe
   comme <a href="../font_sample.php">DPCustomMono</a> ou Courier
   permet de rep&eacute;rer plus facilement ce type de probl&egrave;me.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Retour au d&eacute;but</a></p>


<h3><a name="OCR_scanno">Probl&egrave;mes d'OCR: Scannos</a></h3>
<p>Un autre probl&egrave;me courant, avec l'OCR, est celui de la mauvaise
   reconnaissance de certains caract&egrave;res: les &ldquo;scannos&rdquo; (comme  &ldquo;typos&rdquo;). Le
   r&eacute;sultat peut &ecirc;tre un mot qui:
</p>
<ul compact>
   <li>a l'air correct &agrave; premi&egrave;re vue, mais qui est mal &eacute;crit.<br>
       Vous le verrez facilement en utilisant <a href="../wordcheck-faq.php">WordCheck</a> (le v&eacute;rificateur d'orthographe)
       depuis l'interface de correction.</li>
   <li>a &eacute;t&eacute; transform&eacute; en autre mot, valide, mais qui n'est pas celui
       qui figure sur la page imprim&eacute;e.<br>
       Ces erreurs ne peuvent pas &ecirc;tre rep&eacute;r&eacute;es automatiquement, mais
       seulement par quelqu'un qui lit vraiment le texte.</li>
</ul>
<p>En anglais, l'exemple le plus courant de scanno du second type est &ldquo;arid&rdquo; pour
   &ldquo;and&rdquo;. En fran&ccedil;ais, &ldquo;m&ocirc;me&rdquo; pour &ldquo;m&ecirc;me&rdquo;,
   &ldquo;ros&eacute;&rdquo; pour &ldquo;rose&rdquo;,
   &ldquo;a&rdquo; pour &ldquo;&agrave;&rdquo;,
   &ldquo;f&icirc;t&rdquo; pour &ldquo;fit&rdquo;, etc. Nous les appelons les
   &ldquo;Scannos furtifs&rdquo;, car ils sont plus difficiles &agrave; voir. Des exemples ont &eacute;t&eacute;
   collect&eacute;s <a href="<?php echo $Stealth_Scannos_URL; ?>">ici</a>.
</p>
<p>Les scannos sont plus faciles &agrave; voir avec une police de caract&egrave;res &agrave; espacement fixe comme
   <a href="../font_sample.php">DPCustomMono</a> ou Courier. Pour aider la relecture, l'usage de
   <a href="../wordcheck-faq.php">WordCheck</a> (ou &eacute;quivalent) est recommand&eacute; en <?php echo $ELR_round->id; ?>,
   et obligatoire dans les autres tours de relecture.
</p>
<!-- END RR -->
<!-- More to be added.... -->
<p class="backtotop"><a href="#top">Retour au d&eacute;but</a></p>


<h3><a name="OCR_raised_o">Probl&egrave;mes d'OCR: degr&eacute;s et o en exposant &deg; &ordm;</a></h3>
<p>Il y a trois caract&egrave;res diff&eacute;rents qui peuvent avoir un aspect tr&egrave;s similaire
   dans l'image, que l'OCR interpr&egrave;te de la m&ecirc;me fa&ccedil;on (g&eacute;n&eacute;ralement incorrecte):
</p>
<ul>
  <li>Le signe de degr&eacute;s <tt style="font-size:150%;">&deg;</tt>:
      Il ne devrait &ecirc;tre utilis&eacute; que pour indiquer des degr&eacute;s
      (de temp&eacute;rature, d'angle, etc.).</li>
  <li>La lettre o en exposant:
      Pratiquement toutes les autres occurrences de o sup&eacute;rieur doivent
      &ecirc;tre corrig&eacute;es en <tt>^o</tt>,
      selon la r&egrave;gle pour le <a href="#supers">texte en exposant</a>.</li>
  <li>Le caract&egrave;re ordinal masculin <tt style="font-size:150%;">&ordm;</tt>:
      Corrigez cela &eacute;galement comme un exposant <tt>^o</tt>,
      sauf si les <a href="#comments">commentaires de projet</a> demandent
      l'utilisation du caract&egrave;re.
      Ce symbole est utilis&eacute; dans les langues comme l'espagnol ou le portugais,
      et est l'&eacute;quivalent du <sup>&egrave;me</sup> en fran&ccedil;ais (4<sup>&egrave;me</sup>, 5<sup>&egrave;me</sup>, etc.)
      L'&eacute;quivalent f&eacute;minin est un a en exposant (<tt>&ordf;</tt>).</li>
</ul>
<!-- END RR -->
<p class="backtotop"><a href="#top">Retour au d&eacute;but</a></p>


<h3><a name="hand_notes">Notes manuscrites dans le livre</a></h3>
<p>N'incluez pas les notes manuscrites dans le livre (&agrave; moins que quelqu'un ait
   repass&eacute; des lettres mal imprim&eacute;es ou effac&eacute;es). N'incluez pas
   les notes &eacute;crites en marge par les lecteurs.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Retour au d&eacute;but</a></p>


<h3><a name="bad_image">Mauvaises images</a></h3>
<p>Si une image de page scann&eacute;e est mauvaise (refuse de se charger, est coup&eacute;e au milieu,
   illisible, etc.), postez un message &agrave; propos de cette image dans le
   <a href="#forums">forum</a> du projet, et cliquez sur le bouton
   &ldquo;Report bad page&rdquo; pour mettre la page en &ldquo;quarantaine&rdquo;, plut&ocirc;t que
   de cliquer sur &ldquo;Return page to round&rdquo;.
   Si seule une petite portion de l'image est mauvaise, laissez une note
   comme indiqu&eacute; <a href="#anything">ci-dessus</a>, et mentionnez le
   probl&egrave;me dans le forum du projet, sans marquer la page enti&egrave;re comme mauvaise.
   Le bouton &ldquo;Bad Page&rdquo; n'est disponible que pendant le premier tour de
   relecture, donc il est important que ces probl&egrave;mes soient r&eacute;solus le plus
   t&ocirc;t possible.
</p>
<p>Parfois, les images sont tr&egrave;s volumineuses, et il se peut que votre
   navigateur ait du mal &agrave; les afficher, surtout si vous avez beaucoup de
   fen&ecirc;tres ouvertes ou si votre ordinateur est ancien.
   Avant de d&eacute;clarer la page &ldquo;mauvaise&rdquo;, essayez de faire un zoom sur l'image,
   de fermer quelques fen&ecirc;tres ou programmes ouverts, ou de poser la
   question dans le forum du projet, pour voir si d'autres relecteurs ont le
   m&ecirc;me probl&egrave;me.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Retour au d&eacute;but</a></p>


<h3><a name="bad_text">Image ne correspondant pas au texte</a></h3>
<p>Si l'image ne correspond pas au texte,
   postez un message &agrave; propos de cette image dans le
   <a href="#forums">forum</a> du projet, et cliquez sur le bouton
   &ldquo;Report bad page&rdquo; pour mettre la page en &ldquo;quarantaine&rdquo;,
   plut&ocirc;t que de cliquer sur &ldquo;Return page to round&rdquo;.
   Le bouton &ldquo;Bad Page&rdquo; n'est disponible que pendant le premier tour de
   relecture, donc il est important que ces probl&egrave;mes soient r&eacute;solus le plus
   t&ocirc;t possible.
</p>
<p>Il est relativement courant que l'image soit bonne, mais que le texte pass&eacute; &agrave;
   l'OCR ne contienne pas la premi&egrave;re (et deuxi&egrave;me) ligne. Retapez alors les lignes
   qui manquent. Si presque toutes les lignes manquent, alors soit tapez toute la
   page (si &ccedil;a ne vous d&eacute;range pas), ou cliquez sur le bouton &ldquo;Return page to round&rdquo;
   et une autre personne aura la page. Si plusieurs pages pr&eacute;sentent les m&ecirc;mes d&eacute;fauts,
   postez un message sur le <a href="#forums">forum du projet</a> pour l'indiquer au responsable du projet.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Retour au d&eacute;but</a></p>


<h3><a name="round1">Erreurs des correcteurs pr&eacute;c&eacute;dents</a></h3>
<p>Si un correcteur pr&eacute;c&eacute;dent a fait beaucoup d'erreurs ou a laiss&eacute; passer un
   grand nombre de fautes, vous pouvez lui envoyer un message en cliquant sur son
   nom. &Ccedil;a vous permettra de lui envoyer un message priv&eacute;: ainsi
   il corrigera mieux et fera moins d'erreurs la prochaine fois.
</p>
<p><em>Soyez aimable!</em> Il s'agit d'un b&eacute;n&eacute;vole, comme nous le sommes tous,
   essayant certainement de faire de son mieux. Le but du message est de l'informer
   de la mani&egrave;re correcte de corriger, plut&ocirc;t que de formuler des critiques.
   Donnez-lui un exemple pr&eacute;cis de ce qu'il a fait, et de ce qu'il aurait d&ucirc; faire.
</p>
<p>Si la personne pr&eacute;c&eacute;dente a fait un travail remarquable,
   vous pouvez &eacute;galement lui envoyer un message pour le lui
   dire, surtout si elle a travaill&eacute; sur une page tr&egrave;s difficile.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Retour au d&eacute;but</a></p>


<h3><a name="p_errors">Erreurs d'impression/d'orthographe</a></h3>
<p>Corrigez toujours les fautes introduites par l'OCR. Mais ne corrigez pas ce qui
   est conforme &agrave; l'image, m&ecirc;me si &ccedil;a vous
   semble &ecirc;tre une faute d'orthographe ou d'impression.
   Parfois, certains mots ne s'&eacute;crivaient pas comme
   aujourd'hui &agrave; l'&eacute;poque o&ugrave; le livre a &eacute;t&eacute; imprim&eacute;. Gardez l'ancienne
   orthographe, <b>en particulier en ce qui concerne les accents</b>.
</p>
<p>Mettez une note dans le texte &agrave; la suite de l'erruer<tt>[**typo pour erreur?]</tt>.
   Si vous n'&ecirc;tes pas certain qu'il s'agisse r&eacute;ellement d'une erreur, demandez
   &eacute;galement confirmation dans le
   <a href="#forums">forum du projet</a>. Si vous changez vraiment quelque chose,
   alors mettez une note d&eacute;crivant ce que vous avez chang&eacute;:
   <tt>[**corrig&eacute; "erruer"]</tt>.
   N'oubliez pas les deux &eacute;toiles <tt>**</tt> pour que le post-processeur
   rep&egrave;re votre note.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Retour au d&eacute;but</a></p>


<h3><a name="f_errors">Erreurs factuelles dans le texte</a></h3>
<p>Ne corrigez pas les erreurs de fait dans les livres.
   Beaucoup de livres que nous corrigeons d&eacute;crivent des choses que nous savons &ecirc;tre
   fausses. Laissez-les telles que l'auteur les a &eacute;crites.
   La section <a href="#p_errors">Erreurs d'impression/d'orthographe</a>
   indique comment laisser une note si vous pensez que le texte imprim&eacute;
   ne correspond pas &agrave; l'intention de l'auteur.
</p>
<!-- END RR -->
<p class="backtotop"><a href="#top">Retour au d&eacute;but</a></p>


<h3><a name="insert_char">Saisie des caract&egrave;res sp&eacute;ciaux</a></h3>
<p>Il existe plusieurs fa&ccedil;ons d'ins&eacute;rer les caract&egrave;res sp&eacute;ciaux,
   s'ils ne sont pas d&eacute;j&agrave; pr&eacute;sents sur votre clavier:</p>
<ul compact>
  <li>Les menus d&eacute;roulants de votre interface de correction.</li>
  <li>Des applications fournies avec votre syst&egrave;me d'exploitation.
    <ul compact>
      <li>Windows: la &ldquo;Table des caract&egrave;res&rdquo;<br> Acc&egrave;s par:<br>
          D&eacute;marrer: Ex&eacute;cuter: charmap, ou<br>
          Start: Accessories: System Tools: Character Map.</li>
      <li>Macintosh: Key Caps ou &ldquo;Keyboard Viewer&rdquo;<br>
          Pour OS 9 et pr&eacute;c&eacute;dents, cela se trouve dans le menu Pomme,<br>
          Pour OS X &agrave; 10.2, cela se trouve dans le folder Applications, Utilities<br>
          Pour OS X 10.3 et plus r&eacute;cent, cela se trouve dans le menu Input sous le nom &ldquo;Keyboard Viewer&rdquo;.</li>
      <li>Linux: Le nom et l'emplacement du s&eacute;lecteur de caract&egrave;res
          d&eacute;pend de votre bureau.</li>
    </ul>
  </li>
  <li>Des programmes en ligne.</li>
  <li>Raccourcis clavier.<br>
      Voir ci-dessous les tables pour <a href="#a_chars_win">Windows</a> et
      <a href="#a_chars_mac">Macintosh</a>.</li>
  <li>Il est possible de changer les r&eacute;glages clavier ou le &ldquo;locale&rdquo; pour avoir
      acc&egrave;s directement aux accents.
    <ul compact>
      <li>Windows: Panneau de contr&ocirc;le (Keyboard, Input Locales)</li>
      <li>Macintosh: Input Menu (sur Menu Bar)</li>
      <li>Linux: Configuration X.</li>
    </ul>
  </li>
</ul>
<!-- END RR -->

<a name="a_chars_win"></a>
<p><b>Pour Windows</b>:
</p>
<ul compact>
  <li>Vous pouvez utiliser la table des caract&egrave;res (D&eacute;marrer: Ex&eacute;cuter:
    charmap) pour s&eacute;lectionner les lettres individuelles et les copier/coller.
  </li>
  <li>Les menus d&eacute;roulants de l'interface de correction.
  </li>
  <li>Vous pouvez taper les codes ALT+Nombre &eacute;num&eacute;r&eacute;s ci-dessous
      pour g&eacute;n&eacute;rer ces caract&egrave;res. Ils sont bien plus rapides &agrave; utiliser
      que le copier/coller une fois que vous y &ecirc;tes habitu&eacute;s.
      <br>Pressez la touche Alt et tapez les quatre chiffres dans le
          <i>pav&eacute; num&eacute;rique</i> (les chiffres au-dessus des lettres ne
          fonctionnent pas).
      <br>Vous devez taper les 4 chiffres, y compris le premier 0.
          Remarquez que le code de la version majuscule d'une lettre accentu&eacute;e est
          inf&eacute;rieur de 32 &agrave; sa version minuscule.
      <br>Ceci marche sur un clavier anglais, pas forc&eacute;ment pour d'autres.
      <br>(<a href="../charwin.pdf">Version imprimable de cette table</a>).
  </li>
</ul>

<br>
<table align="center" border="6" rules="all" summary="Raccourcis Windows">
  <tbody>
  <tr>
      <th bgcolor="cornsilk" colspan=14>Raccourcis Windows pour caract&egrave;res Latin-1</th>
  </tr>
  <tr bgcolor="cornsilk">
      <th colspan=2>` grave</th>
      <th colspan=2>&acute; aigu</th>
      <th colspan=2>^ circonflexe</th>
      <th colspan=2>~ tilde</th>
      <th colspan=2>&uml; tr&eacute;ma</th>
      <th colspan=2>&deg; rond</th>
      <th colspan=2>ligature &aelig;</th>
  </tr>
  <tr><td align="center" bgcolor="mistyrose" title="a accent grave minuscule"      >&agrave; </td><td>Alt-0224</td>
      <td align="center" bgcolor="mistyrose" title="a accent aigu minuscule"       >&aacute; </td><td>Alt-0225</td>
      <td align="center" bgcolor="mistyrose" title="a accent circonflexe minuscule">&acirc;  </td><td>Alt-0226</td>
      <td align="center" bgcolor="mistyrose" title="a tilde minuscule"             >&atilde; </td><td>Alt-0227</td>
      <td align="center" bgcolor="mistyrose" title="a tr&eacute;ma minuscule"             >&auml;   </td><td>Alt-0228</td>
      <td align="center" bgcolor="mistyrose" title="a surmont&eacute; d'un rond minuscule">&aring;  </td><td>Alt-0229</td>
      <td align="center" bgcolor="mistyrose" title="Ligature ae minuscule"         >&aelig;  </td><td>Alt-0230</td>
  </tr>
  <tr><td align="center" bgcolor="mistyrose" title="A accent grave majuscule"      >&Agrave; </td><td>Alt-0192</td>
      <td align="center" bgcolor="mistyrose" title="A accent aigu majuscule"       >&Aacute; </td><td>Alt-0193</td>
      <td align="center" bgcolor="mistyrose" title="A accent circonflexe majuscule">&Acirc;  </td><td>Alt-0194</td>
      <td align="center" bgcolor="mistyrose" title="A tilde majuscule"             >&Atilde; </td><td>Alt-0195</td>
      <td align="center" bgcolor="mistyrose" title="A tr&eacute;ma majuscule"             >&Auml;   </td><td>Alt-0196</td>
      <td align="center" bgcolor="mistyrose" title="A surmont&eacute; d'un rond majuscule">&Aring;  </td><td>Alt-0197</td>
      <td align="center" bgcolor="mistyrose" title="Ligature AE majuscule"         >&AElig;  </td><td>Alt-0198</td>
  </tr>
  <tr><td align="center" bgcolor="mistyrose" title="e accent grave minuscule"      >&egrave; </td><td>Alt-0232</td>
      <td align="center" bgcolor="mistyrose" title="e accent aigu minuscule"       >&eacute; </td><td>Alt-0233</td>
      <td align="center" bgcolor="mistyrose" title="e accent circonflexe minuscule">&ecirc;  </td><td>Alt-0234</td>
      <td> </td><td> </td>
      <td align="center" bgcolor="mistyrose" title="e tr&eacute;ma minuscule"             >&euml;   </td><td>Alt-0235</td>
      <td> </td><td> </td>
      <td> </td><td> </td>
  </tr>
  <tr><td align="center" bgcolor="mistyrose" title="E accent grave majuscule"      >&Egrave; </td><td>Alt-0200</td>
      <td align="center" bgcolor="mistyrose" title="E accent aigu majuscule"       >&Eacute; </td><td>Alt-0201</td>
      <td align="center" bgcolor="mistyrose" title="E accent circonflexe majuscule">&Ecirc;  </td><td>Alt-0202</td>
      <td> </td><td> </td>
      <td align="center" bgcolor="mistyrose" title="E tr&eacute;ma majuscule"             >&Euml;   </td><td>Alt-0203</td>
      <td> </td><td> </td>
      <td> </td><td> </td>
  </tr>
  <tr><td align="center" bgcolor="mistyrose" title="i accent grave minuscule"      >&igrave; </td><td>Alt-0236</td>
      <td align="center" bgcolor="mistyrose" title="i accent aigu minuscule"       >&iacute; </td><td>Alt-0237</td>
      <td align="center" bgcolor="mistyrose" title="i accent circonflexe minuscule">&icirc;  </td><td>Alt-0238</td>
      <td> </td><td> </td>
      <td align="center" bgcolor="mistyrose" title="i tr&eacute;ma minuscule"             >&iuml;   </td><td>Alt-0239</td>
      <td> </td><td> </td>
      <td> </td><td> </td>
  </tr>
  <tr><td align="center" bgcolor="mistyrose" title="I accent grave majuscule"      >&Igrave; </td><td>Alt-0204</td>
      <td align="center" bgcolor="mistyrose" title="I accent aigu majuscule"       >&Iacute; </td><td>Alt-0205</td>
      <td align="center" bgcolor="mistyrose" title="I accent circonflexe majuscule">&Icirc;  </td><td>Alt-0206</td>
      <td> </td><td> </td>
      <td align="center" bgcolor="mistyrose" title="I tr&eacute;ma majuscule"             >&Iuml;   </td><td>Alt-0207</td>
      <th colspan=2 bgcolor="cornsilk">/ barr&eacute;</th>
      <td></td>
  </tr>
  <tr><td align="center" bgcolor="mistyrose" title="o accent grave minuscule"      >&ograve; </td><td>Alt-0242</td>
      <td align="center" bgcolor="mistyrose" title="o accent aigu minuscule"       >&oacute; </td><td>Alt-0243</td>
      <td align="center" bgcolor="mistyrose" title="o accent circonflexe minuscule">&ocirc;  </td><td>Alt-0244</td>
      <td align="center" bgcolor="mistyrose" title="o tilde minuscule"             >&otilde; </td><td>Alt-0245</td>
      <td align="center" bgcolor="mistyrose" title="o tr&eacute;ma minuscule"             >&ouml;   </td><td>Alt-0246</td>
      <td align="center" bgcolor="mistyrose" title="o barr&eacute; minuscule"             >&oslash; </td><td>Alt-0248</td>
      <td></td><td></td>
  </tr>
  <tr><td align="center" bgcolor="mistyrose" title="O accent grave majuscule"      >&Ograve; </td><td>Alt-0210</td>
      <td align="center" bgcolor="mistyrose" title="O accent aigu majuscule"       >&Oacute; </td><td>Alt-0211</td>
      <td align="center" bgcolor="mistyrose" title="O accent circonflexe majuscule">&Ocirc;  </td><td>Alt-0212</td>
      <td align="center" bgcolor="mistyrose" title="O tilde majuscule"             >&Otilde; </td><td>Alt-0213</td>
      <td align="center" bgcolor="mistyrose" title="O tr&eacute;ma majuscule"             >&Ouml;   </td><td>Alt-0214</td>
      <td align="center" bgcolor="mistyrose" title="O barr&eacute; majuscule"             >&Oslash; </td><td>Alt-0216</td>
      <td></td><td></td>
  </tr>
  <tr><td align="center" bgcolor="mistyrose" title="u accent grave minuscule"      >&ugrave; </td><td>Alt-0249</td>
      <td align="center" bgcolor="mistyrose" title="u accent aigu minuscule"       >&uacute; </td><td>Alt-0250</td>
      <td align="center" bgcolor="mistyrose" title="u accent circonflexe minuscule">&ucirc;  </td><td>Alt-0251</td>
      <td> </td><td> </td>
      <td align="center" bgcolor="mistyrose" title="u tr&eacute;ma minuscule"             >&uuml;   </td><td>Alt-0252</td>
      <td> </td><td> </td>
      <td> </td><td> </td>
  </tr>
  <tr><td align="center" bgcolor="mistyrose" title="U accent grave majuscule"      >&Ugrave; </td><td>Alt-0217</td>
      <td align="center" bgcolor="mistyrose" title="U accent aigu majuscule"       >&Uacute; </td><td>Alt-0218</td>
      <td align="center" bgcolor="mistyrose" title="U accent circonflexe majuscule">&Ucirc;  </td><td>Alt-0219</td>
      <td> </td><td> </td>
      <td align="center" bgcolor="mistyrose" title="U tr&eacute;ma majuscule"             >&Uuml;   </td><td>Alt-0220</td>
      <th colspan=2 bgcolor="cornsilk">monnaie      </th>
      <th colspan=2 bgcolor="cornsilk">math&eacute;matiques</th>
  </tr>
  <tr><td> </td><td> </td>
      <td align="center" bgcolor="mistyrose" title="y accent aigu minuscule"       >&yacute; </td><td>Alt-0253</td>
      <td> </td><td> </td>
      <td align="center" bgcolor="mistyrose" title="n tilde minuscule"             >&ntilde; </td><td>Alt-0241</td>
      <td align="center" bgcolor="mistyrose" title="y tr&eacute;ma minuscule"             >&yuml;   </td><td>Alt-0255</td>
      <td align="center" bgcolor="mistyrose" title="Cents"                         >&cent;   </td><td>Alt-0162</td>
      <td align="center" bgcolor="mistyrose" title="Plus ou moins"                 >&plusmn; </td><td>Alt-0177</td>
  </tr>
  <tr><td> </td><td> </td>
      <td align="center" bgcolor="mistyrose" title="Y accent aigu majuscule"       >&Yacute; </td><td>Alt-0221</td>
      <td> </td><td> </td>
      <td align="center" bgcolor="mistyrose" title="N tilde majuscule"             >&Ntilde; </td><td>Alt-0209</td>
      <td></td><td></td>
      <td align="center" bgcolor="mistyrose" title="Livres"                        >&pound;  </td><td>Alt-0163</td>
      <td align="center" bgcolor="mistyrose" title="Multiplication"                >&times;  </td><td>Alt-0215</td>
  </tr>
  <tr><th colspan=2 bgcolor="cornsilk">&ccedil;&eacute;dille </th>
      <th colspan=2 bgcolor="cornsilk">islandais   </th>
      <th colspan=2 bgcolor="cornsilk">marques        </th>
      <th colspan=2 bgcolor="cornsilk">accents      </th>
      <th colspan=2 bgcolor="cornsilk">ponctuation  </th>
      <td align="center" bgcolor="mistyrose" title="Yen"                   >&yen;    </td><td>Alt-0165</td>
      <td align="center" bgcolor="mistyrose" title="Division"              >&divide; </td><td>Alt-0247</td>
  </tr>
  <tr><td align="center" bgcolor="mistyrose" title="c c&eacute;dille minuscule"       >&ccedil; </td><td>Alt-0231</td>
      <td align="center" bgcolor="mistyrose" title="Thorn majuscule"         >&THORN;  </td><td>Alt-0222</td>
      <td align="center" bgcolor="mistyrose" title="Copyright"             >&copy;   </td><td>Alt-0169</td>
      <td align="center" bgcolor="mistyrose" title="Accent aigu"          >&acute;  </td><td>Alt-0180</td>
      <td align="center" bgcolor="mistyrose" title="Point d'interrogation invers&eacute;">&iquest; </td><td>Alt-0191</td>
      <td align="center" bgcolor="mistyrose" title="Monnaie g&eacute;n&eacute;rique"    >&curren; </td><td>Alt-0164</td>
      <td align="center" bgcolor="mistyrose" title="Non logique"           >&not;    </td><td>Alt-0172</td>
  </tr>
  <tr><td align="center" bgcolor="mistyrose" title="C c&eacute;dille majuscule"     >&Ccedil; </td><td>Alt-0199</td>
      <td align="center" bgcolor="mistyrose" title="thorn minuscule"           >&thorn;  </td><td>Alt-0254</td>
      <td align="center" bgcolor="mistyrose" title="R entour&eacute; d'un rond"     >&reg;    </td><td>Alt-0174</td>
      <td align="center" bgcolor="mistyrose" title="Tr&eacute;ma"         >&uml;    </td><td>Alt-0168</td>
      <td align="center" bgcolor="mistyrose" title="Point d'exclamation invers&eacute;"  >&iexcl;  </td><td>Alt-0161</td>
      <td> </td><td> </td>
      <td align="center" bgcolor="mistyrose" title="Degr&eacute;s"               >&deg;    </td><td>Alt-0176</td>
  </tr>
  <tr><th colspan=2 bgcolor="cornsilk">exposants        </th>
      <td align="center" bgcolor="mistyrose" title="Eth majuscule"           >&ETH;    </td><td>Alt-0208</td>
      <td align="center" bgcolor="mistyrose" title="Signe de paragraphe"   >&para;   </td><td>Alt-0182</td>
      <td align="center" bgcolor="mistyrose" title="Barre horizontale"         >&macr;   </td><td>Alt-0175</td>
      <td align="center" bgcolor="mistyrose" title="Guillemet ouvrant"        >&laquo;  </td><td>Alt-0171</td>
      <td> </td><td> </td>
      <td align="center" bgcolor="mistyrose" title="Micro"                 >&micro;  </td><td>Alt-0181</td>
  </tr>
  <tr><td align="center" bgcolor="mistyrose" title="Exposant 1"         >&sup1;   </td><td>Alt-0185&nbsp;*</td>
      <td align="center" bgcolor="mistyrose" title="eth minuscule"             >&eth;    </td><td>Alt-0240</td>
      <td align="center" bgcolor="mistyrose" title="Section"               >&sect;   </td><td>Alt-0167</td>
      <td align="center" bgcolor="mistyrose" title="C&eacute;dille"               >&cedil;  </td><td>Alt-0184</td>
      <td align="center" bgcolor="mistyrose" title="Guillemet fermant"       >&raquo;  </td><td>Alt-0187</td>
      <th colspan=2 bgcolor="cornsilk">ordinaux  </th>
      <td align="center" bgcolor="mistyrose" title="Fraction 1/4"          >&frac14; </td><td>Alt-0188&nbsp;&dagger;</td>
  </tr>
  <tr><td align="center" bgcolor="mistyrose" title="Exposant 2"         >&sup2;   </td><td>Alt-0178&nbsp;*</td>
      <th colspan=2 bgcolor="cornsilk">ligature sz        </th>
      <td align="center" bgcolor="mistyrose" title="Barre verticale bris&eacute;e"   >&brvbar; </td><td>Alt-0166</td>
      <td> </td><td> </td>
      <td align="center" bgcolor="mistyrose" title="Point m&eacute;dian"            >&middot; </td><td>Alt-0183</td>
      <td align="center" bgcolor="mistyrose" title="Ordinal masculin"     >&ordm;   </td><td>Alt-0186&nbsp;*</td>
      <td align="center" bgcolor="mistyrose" title="Fraction 1/2"          >&frac12; </td><td>Alt-0189&nbsp;&dagger;</td>
  </tr>
  <tr><td align="center" bgcolor="mistyrose" title="Exposant 3"         >&sup3;   </td><td>Alt-0179&nbsp;*</td>
      <td align="center" bgcolor="mistyrose" title="Ligature sz"           >&szlig;  </td><td>Alt-0223</td>
      <td> </td><td> </td>
      <td> </td><td> </td>
      <td> </td><td> </td>
      <td align="center" bgcolor="mistyrose" title="Ordinal f&eacute;minin"      >&ordf;   </td><td>Alt-0170&nbsp;*</td>
      <td align="center" bgcolor="mistyrose" title="Fraction 3/4"          >&frac34; </td><td>Alt-0190&nbsp;&dagger;</td>
  </tr>
  </tbody>
</table>
<p>* &Agrave; moins que les <a href="#comments">commentaires de projet</a> ne le
   demandent express&eacute;ment, n'utilisez pas les symboles ordinaux ou exposants,
   mais appliquez plut&ocirc;t les r&egrave;gles des <a href="#supers">exposants</a>. (x^2, n^o, etc.)
</p>
<p>&dagger; &Agrave; moins que les <a href="#comments">commentaires de projet</a> ne le
   demandent express&eacute;ment, n'utilisez pas les symboles de fractions,
   mais appliquez plut&ocirc;t les r&egrave;gles des <a href="#fract_s">Fractions</a>. (1/2, 1/4, 3/4, etc.)
</p>



<p><b>Pour Apple Macintosh</b>:
</p>
<ul compact>
  <li>Vous pouvez utiliser le Apple Key Caps en tant que r&eacute;f&eacute;rence.<br>
      Dans l'OS 9 &amp; pr&eacute;c&eacute;dents, il est situ&eacute; dans le Menu Pomme;
      Dans OS X jusqu'&agrave; 10.2, il est dans Applications, Utilities.<br>
      Ceci affiche l'image d'un clavier, et en pressant MAJ, OPT et command/pomme ou
      une combinaison de ces touches vous verrez comment produire chaque caract&egrave;re. Utilisez cette
      r&eacute;f&eacute;rence pour voir comment taper chaque caract&egrave;re, ou vous pouvez copier
      &amp; coller de cette application vers le document.</li>
  <li>Dans l'OS X 10.3 et plus, on utilise une palette disponible par le menu
      Input (le menu d&eacute;roulant attach&eacute; &agrave; l'ic&ocirc;ne &ldquo;drapeau&rdquo; de votre &ldquo;locale&rdquo;.)
      Elle s'appelle &ldquo;Show Keyboard Viewer&rdquo;. Si ce n'est pas dans votre menu
      Input, ou si vous n'avez pas ce menu, activez-la en ouvrant &ldquo;System
      Preferences&rdquo;, panneau  &ldquo;International&rdquo;, et choisissez le panneau &ldquo;Input
      menu&rdquo;. &ldquo;Show input menu in menu bar&rdquo; doit &ecirc;tre coch&eacute;e. Dans la vue
      &ldquo;spreadsheet&rdquo;, cochez la case pour &ldquo;Keyboard viewer&rdquo; pour tous les &ldquo;locales&rdquo;
      d'entr&eacute;e que vous utilisez.
  </li>
  <li>Les menus d&eacute;roulants dans l'interface de correction.
  </li>
  <li>Vous pouvez aussi utiliser pour ces caract&egrave;res le raccourci Apple Opt- indiqu&eacute; dans
      le tableau ci-dessous.
      <br>Une fois qu'on a l'habitude des codes, c'est bien plus rapide que copier/coller.
      <br>Appuyez sur Opt, tapez le symbole d'accent, puis la lettre &agrave; accentuer (pour certains
          codes, il suffit de maintenir Opt appuy&eacute;e, puis taper le symbole).
      <br>Ceci marche sur un clavier am&eacute;ricain, pas forc&eacute;ment pour d'autres.
      <br>(<a href="../charapp.pdf">Version imprimable de cette table)</a>.
  </li>
</ul>

<br>
<a name="a_chars_mac"></a>
<table align="center" border="6" rules="all" summary="Raccourcis Mac">
  <tbody>
  <tr bgcolor="cornsilk">
      <th colspan=14>Raccourcis Mac pour caract&egrave;res Latin-1</th>
  </tr>
  <tr bgcolor="cornsilk">
      <th colspan=2>` grave</th>
      <th colspan=2>&acute; aigu</th>
      <th colspan=2>^ circonflexe</th>
      <th colspan=2>~ tilde</th>
      <th colspan=2>&uml; tr&eacute;ma</th>
      <th colspan=2>&deg; rond</th>
      <th colspan=2>ligature &aelig;</th>
  </tr>
  <tr><td align="center" bgcolor="mistyrose" title="a accent grave minuscule"         >&agrave; </td><td>Opt-`, a</td>
      <td align="center" bgcolor="mistyrose" title="a accent aigu minuscule"         >&aacute; </td><td>Opt-e, a</td>
      <td align="center" bgcolor="mistyrose" title="a accent circonflexe minuscule"    >&acirc;  </td><td>Opt-i, a</td>
      <td align="center" bgcolor="mistyrose" title="a tilde minuscule"         >&atilde; </td><td>Opt-n, a</td>
      <td align="center" bgcolor="mistyrose" title="a tr&eacute;ma minuscule"        >&auml;   </td><td>Opt-u, a</td>
      <td align="center" bgcolor="mistyrose" title="a surmont&eacute; d'un rond minuscule"          >&aring;  </td><td>Opt-a   </td>
      <td align="center" bgcolor="mistyrose" title="Ligature ae minuscule"     >&aelig;  </td><td>Opt-'   </td>
  </tr>
  <tr><td align="center" bgcolor="mistyrose" title="A accent grave majuscule"       >&Agrave; </td><td>Opt-`, A</td>
      <td align="center" bgcolor="mistyrose" title="A accent aigu majuscule"       >&Aacute; </td><td>Opt-e, A</td>
      <td align="center" bgcolor="mistyrose" title="A accent circonflexe majuscule"  >&Acirc;  </td><td>Opt-i, A</td>
      <td align="center" bgcolor="mistyrose" title="A tilde majuscule"       >&Atilde; </td><td>Opt-n, A</td>
      <td align="center" bgcolor="mistyrose" title="A tr&eacute;ma majuscule"      >&Auml;   </td><td>Opt-u, A</td>
      <td align="center" bgcolor="mistyrose" title="A surmont&eacute; d'un rond majuscule"        >&Aring;  </td><td>Opt-A   </td>
      <td align="center" bgcolor="mistyrose" title="Ligature AE majuscule"   >&AElig;  </td><td>Opt-"   </td>
  </tr>
  <tr><td align="center" bgcolor="mistyrose" title="e accent grave minuscule"         >&egrave; </td><td>Opt-`, e</td>
      <td align="center" bgcolor="mistyrose" title="e accent aigu minuscule"         >&eacute; </td><td>Opt-e, e</td>
      <td align="center" bgcolor="mistyrose" title="e accent circonflexe minuscule"    >&ecirc;  </td><td>Opt-i, e</td>
      <td> </td><td> </td>
      <td align="center" bgcolor="mistyrose" title="e tr&eacute;ma minuscule"        >&euml;   </td><td>Opt-u, e</td>
      <td> </td><td> </td>
      <td> </td><td> </td>
  </tr>
  <tr><td align="center" bgcolor="mistyrose" title="E accent grave majuscule"       >&Egrave; </td><td>Opt-`, E</td>
      <td align="center" bgcolor="mistyrose" title="E accent aigu majuscule"       >&Eacute; </td><td>Opt-e, E</td>
      <td align="center" bgcolor="mistyrose" title="E accent circonflexe majuscule"  >&Ecirc;  </td><td>Opt-i, E</td>
      <td> </td><td> </td>
      <td align="center" bgcolor="mistyrose" title="E tr&eacute;ma majuscule"      >&Euml;   </td><td>Opt-u, E</td>
      <td> </td><td> </td>
      <td> </td><td> </td>
  </tr>
  <tr><td align="center" bgcolor="mistyrose" title="i accent grave minuscule"         >&igrave; </td><td>Opt-`, i</td>
      <td align="center" bgcolor="mistyrose" title="i accent aigu minuscule"         >&iacute; </td><td>Opt-e, i</td>
      <td align="center" bgcolor="mistyrose" title="i accent circonflexe minuscule"    >&icirc;  </td><td>Opt-i, i</td>
      <td> </td><td> </td>
      <td align="center" bgcolor="mistyrose" title="i tr&eacute;ma minuscule"        >&iuml;   </td><td>Opt-u, i</td>
      <td> </td><td> </td>
      <td> </td><td> </td>
  </tr>
  <tr><td align="center" bgcolor="mistyrose" title="I accent grave majuscule"       >&Igrave; </td><td>Opt-`, I</td>
      <td align="center" bgcolor="mistyrose" title="I accent aigu majuscule"       >&Iacute; </td><td>Opt-e, I</td>
      <td align="center" bgcolor="mistyrose" title="I accent circonflexe majuscule"  >&Icirc;  </td><td>Opt-i, I</td>
      <td> </td><td> </td>
      <td align="center" bgcolor="mistyrose" title="I tr&eacute;ma majuscule"      >&Iuml;   </td><td>Opt-u, I</td>
      <th colspan=2 bgcolor="cornsilk">/ barr&eacute;</th>
      <td></td>
  </tr>
  <tr><td align="center" bgcolor="mistyrose" title="o accent grave minuscule"         >&ograve; </td><td>Opt-`, o</td>
      <td align="center" bgcolor="mistyrose" title="o accent aigu minuscule"         >&oacute; </td><td>Opt-e, o</td>
      <td align="center" bgcolor="mistyrose" title="o accent circonflexe minuscule"    >&ocirc;  </td><td>Opt-i, o</td>
      <td align="center" bgcolor="mistyrose" title="o tilde minuscule"         >&otilde; </td><td>Opt-n, o</td>
      <td align="center" bgcolor="mistyrose" title="o tr&eacute;ma minuscule"        >&ouml;   </td><td>Opt-u, o</td>
      <td align="center" bgcolor="mistyrose" title="o barr&eacute; minuscule"         >&oslash; </td><td>Opt-o   </td>
      <td></td><td></td>
  </tr>
  <tr><td align="center" bgcolor="mistyrose" title="O accent grave majuscule"       >&Ograve; </td><td>Opt-`, O</td>
      <td align="center" bgcolor="mistyrose" title="O accent aigu majuscule"       >&Oacute; </td><td>Opt-e, O</td>
      <td align="center" bgcolor="mistyrose" title="I accent circonflexe majuscule"  >&Ocirc;  </td><td>Opt-i, O</td>
      <td align="center" bgcolor="mistyrose" title="O tilde majuscule"       >&Otilde; </td><td>Opt-n, O</td>
      <td align="center" bgcolor="mistyrose" title="O tr&eacute;ma majuscule"      >&Ouml;   </td><td>Opt-u, O</td>
      <td align="center" bgcolor="mistyrose" title="O barr&eacute; majuscule"       >&Oslash; </td><td>Opt-O   </td>
      <td></td><td></td>
  </tr>
  <tr><td align="center" bgcolor="mistyrose" title="u accent grave minuscule"         >&ugrave; </td><td>Opt-`, u</td>
      <td align="center" bgcolor="mistyrose" title="u accent aigu minuscule"         >&uacute; </td><td>Opt-e, u</td>
      <td align="center" bgcolor="mistyrose" title="u accent circonflexe minuscule"    >&ucirc;  </td><td>Opt-i, u</td>
      <td> </td><td> </td>
      <td align="center" bgcolor="mistyrose" title="u tr&eacute;ma minuscule"        >&uuml;   </td><td>Opt-u, u</td>
      <td> </td><td> </td>
      <td> </td><td> </td>
  </tr>
  <tr><td align="center" bgcolor="mistyrose" title="U accent grave majuscule"       >&Ugrave; </td><td>Opt-`, U</td>
      <td align="center" bgcolor="mistyrose" title="U accent aigu majuscule"       >&Uacute; </td><td>Opt-e, U</td>
      <td align="center" bgcolor="mistyrose" title="U accent circonflexe majuscule"  >&Ucirc;  </td><td>Opt-i, U</td>
      <td> </td><td> </td>
      <td align="center" bgcolor="mistyrose" title="U tr&eacute;ma majuscule"      >&Uuml;   </td><td>Opt-u, U</td>
      <th colspan=2 bgcolor="cornsilk">monnaie      </th>
      <th colspan=2 bgcolor="cornsilk">math&eacute;matiques  </th>
  </tr>
  <tr><td> </td><td> </td>
      <td align="center" bgcolor="mistyrose" title="y accent aigu minuscule"         >&yacute; </td><td>Opt-e, y</td>
      <td> </td><td> </td>
      <td align="center" bgcolor="mistyrose" title="n tilde minuscule"         >&ntilde; </td><td>Opt-n, n</td>
      <td align="center" bgcolor="mistyrose" title="y tr&eacute;ma minuscule"        >&yuml;   </td><td>Opt-u, y</td>
      <td align="center" bgcolor="mistyrose" title="Cents"                 >&cent;   </td><td>Opt-4   </td>
      <td align="center" bgcolor="mistyrose" title="Plus ou moins"            >&plusmn; </td><td>Shift-Opt-=</td>
  </tr>
  <tr><td> </td><td> </td>
      <td align="center" bgcolor="mistyrose" title="Y accent aigu majuscule"       >&Yacute; </td><td>Opt-e, Y</td>
      <td> </td><td> </td>
      <td align="center" bgcolor="mistyrose" title="N tilde majuscule"       >&Ntilde; </td><td>Opt-n, N</td>
      <td></td><td></td>
      <td align="center" bgcolor="mistyrose" title="Livres"                >&pound;  </td><td>Opt-3   </td>
      <td align="center" bgcolor="mistyrose" title="Multiplication"        >&times;  </td><td>(aucun)&nbsp;&Dagger;</td>
  </tr>
  <tr><th colspan=2 bgcolor="cornsilk">&ccedil;&eacute;dille </th>
      <th colspan=2 bgcolor="cornsilk">islandais    </th>
      <th colspan=2 bgcolor="cornsilk">marques        </th>
      <th colspan=2 bgcolor="cornsilk">accents      </th>
      <th colspan=2 bgcolor="cornsilk">ponctuation  </th>
      <td align="center" bgcolor="mistyrose" title="Yen"                   >&yen;    </td><td>Opt-y   </td>
      <td align="center" bgcolor="mistyrose" title="Division"              >&divide; </td><td>Opt-/   </td>
  </tr>
  <tr><td align="center" bgcolor="mistyrose" title="c c&eacute;dille minuscule"       >&ccedil; </td><td>Opt-c   </td>
      <td align="center" bgcolor="mistyrose" title="Thorn majuscule"         >&THORN;  </td><td>(aucun)&nbsp;&Dagger;</td>
      <td align="center" bgcolor="mistyrose" title="Copyright"             >&copy;   </td><td>Opt-g   </td>
      <td align="center" bgcolor="mistyrose" title="Accent aigu"          >&acute;  </td><td>Opt-E   </td>
      <td align="center" bgcolor="mistyrose" title="Point d'interrogation invers&eacute;">&iquest; </td><td>Opt-?   </td>
      <td align="center" bgcolor="mistyrose" title="Monnaie g&eacute;n&eacute;rique"      >&curren; </td><td>(aucun)&nbsp;&Dagger;  </td>
      <td align="center" bgcolor="mistyrose" title="Non logique"           >&not;    </td><td>Opt-l   </td>
  </tr>
  <tr><td align="center" bgcolor="mistyrose" title="C c&eacute;dille majuscule"     >&Ccedil; </td><td>Opt-C   </td>
      <td align="center" bgcolor="mistyrose" title="thorn minuscule"           >&thorn;  </td><td>(aucun)&nbsp;&Dagger;  </td>
      <td align="center" bgcolor="mistyrose" title="R entour&eacute; d'un rond"     >&reg;    </td><td>Opt-r   </td>
      <td align="center" bgcolor="mistyrose" title="Tr&eacute;ma"         >&uml;    </td><td>Opt-U   </td>
      <td align="center" bgcolor="mistyrose" title="Point d'exclamation invers&eacute;"  >&iexcl;  </td><td>Opt-1   </td>
      <td> </td><td> </td>
      <td align="center" bgcolor="mistyrose" title="Degr&eacute;s"               >&deg;    </td><td>Shift-Opt-8   </td>
  </tr>
  <tr><th colspan=2 bgcolor="cornsilk">exposants        </th>
      <td align="center" bgcolor="mistyrose" title="Eth majuscule"           >&ETH;    </td><td>(aucun)&nbsp;&Dagger;  </td>
      <td align="center" bgcolor="mistyrose" title="Signe de paragraphe"   >&para;   </td><td>Opt-7   </td>
      <td align="center" bgcolor="mistyrose" title="Barre horizontale"         >&macr;   </td><td>Shift-Opt-,</td>
      <td align="center" bgcolor="mistyrose" title="Guillemet ouvrant"        >&laquo;  </td><td>Opt-\   </td>
      <td> </td><td> </td>
      <td align="center" bgcolor="mistyrose" title="Micro"                 >&micro;  </td><td>Opt-m   </td>
  </tr>
  <tr><td align="center" bgcolor="mistyrose" title="Exposant 1"         >&sup1;   </td><td>(aucun)&nbsp;*&Dagger;  </td>
      <td align="center" bgcolor="mistyrose" title="eth minuscule"             >&eth;    </td><td>(aucun)&nbsp;&Dagger;  </td>
      <td align="center" bgcolor="mistyrose" title="Section"               >&sect;   </td><td>Opt-6   </td>
      <td align="center" bgcolor="mistyrose" title="C&eacute;dille"               >&cedil;  </td><td>Opt-Z   </td>
      <td align="center" bgcolor="mistyrose" title="Guillemet fermant"       >&raquo;  </td><td>Shift-Opt-\</td>
      <th colspan=2 bgcolor="cornsilk">ordinaux  </th>
      <td align="center" bgcolor="mistyrose" title="Fraction 1/4"          >&frac14; </td><td>(aucun)&nbsp;&dagger;&Dagger;</td>
  </tr>
  <tr><td align="center" bgcolor="mistyrose" title="Exposant 2"         >&sup2;   </td><td>(aucun)&nbsp;*&Dagger;  </td>
      <th colspan=2 bgcolor="cornsilk">ligature sz        </th>
      <td align="center" bgcolor="mistyrose" title="Barre verticale bris&eacute;e"   >&brvbar; </td><td>(aucun)&nbsp;&Dagger;  </td>
      <td> </td><td> </td>
      <td align="center" bgcolor="mistyrose" title="Point m&eacute;dian"            >&middot; </td><td>Shift-Opt-9  </td>
      <td align="center" bgcolor="mistyrose" title="Ordinal masculin"     >&ordm;   </td><td>Opt-0&nbsp;*</td>
      <td align="center" bgcolor="mistyrose" title="Fraction 1/2"          >&frac12; </td><td>(aucun)&nbsp;&dagger;&Dagger;</td>
  </tr>
  <tr><td align="center" bgcolor="mistyrose" title="Exposant 3"         >&sup3;   </td><td>(aucun)&nbsp;*&Dagger;  </td>
      <td align="center" bgcolor="mistyrose" title="Ligature S-stet"           >&szlig;  </td><td>Opt-s   </td>
      <td> </td><td> </td>
      <td> </td><td> </td>
      <td> </td><td> </td>
      <td align="center" bgcolor="mistyrose" title="Ordinal f&eacute;minin"      >&ordf;   </td><td>Opt-9&nbsp;*</td>
      <td align="center" bgcolor="mistyrose" title="Fraction 3/4"          >&frac34; </td><td>(aucun)&nbsp;&dagger;&Dagger;</td>
  </tr>
  </tbody>
</table>
<p>* &Agrave; moins que les <a href="#comments">commentaires de projet</a> ne le
   demandent express&eacute;ment, n'utilisez pas les symboles ordinaux ou exposants,
   mais appliquez plut&ocirc;t les r&egrave;gles des <a href="#supers">exposants</a>. (x^2, n^o, etc.)
</p>
<p>&dagger; &Agrave; moins que les <a href="#comments">commentaires de projet</a> ne le
   demandent express&eacute;ment, n'utilisez pas les symboles de fractions,
   mais appliquez plut&ocirc;t les r&egrave;gles des <a href="#fract_s">Fractions</a>. (1/2, 1/4, 3/4, etc.)
</p>
<p>&Dagger;&nbsp;Note: Pas de raccourci clavier. Utilisez les menus.
</p>
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
        <li><a href="#a_chars">Accents (caract&egrave;res non-ASCII)</a></li>
        <li><a href="#f_chars">Alphabets non latins</a></li>
        <li><a href="#a_chars">Caract&egrave;res accentu&eacute;s et non-ASCII</a></li>
        <li><a href="#d_chars">Caract&egrave;res avec marques diacritiques</a></li>
        <li><a href="#insert_char">Caract&egrave;res sp&eacute;ciaux, saisie</a></li>
        <li><a href="#chap_head">Chapitre</a></li>
        <li><a href="#mult_col">Colonnes multiples</a></li>
        <li><a href="#para_side">Commentaire en marge</a></li>
        <li><a href="#comments">Commentaires de projets</a></li>
        <li><a href="#contract">Contractions</a></li>
        <li><a href="#prev_notes">Correcteurs pr&eacute;c&eacute;dents, notes et commentaires</a></li>
        <li><a href="#prev_pg">Corriger des erreurs sur les pages pr&eacute;c&eacute;dentes</a></li>
        <li><a href="#OCR_raised_o">Degr&eacute;s et o en exposant</a></li>
        <li><a href="#d_chars">Diacritiques</a></li>
        <li><a href="#page_hf">En-t&ecirc;te de page</a></li>
        <li><a href="#p_errors">Erreurs d'impression et d'orthographe</a></li>
        <li><a href="#round1">Erreurs des correcteurs pr&eacute;c&eacute;dents</a></li>
        <li><a href="#f_errors">Erreurs factuelles dans les textes</a></li>
        <li><a href="#trail_s">Espace en fin de ligne</a></li>
        <li><a href="#para_space">Espacement et indentation des paragraphes</a></li>
        <li><a href="#punctuat">Espaces et ponctuation</a></li>
        <li><a href="#extra_sp">Espaces exc&eacute;dentaires entre les mots</a></li>
        <li><a href="#supers">Exposant</a></li>
        <li><a href="#OCR_raised_o">Exposant o et degr&eacute;s</a></li>
        <li><a href="#formatting">Formatage</a></li>
        <li><a href="#forums">Forum du Projet</a></li>
        <li><a href="#fract_s">Fractions</a></li>
        <li><a href="#chap_head">Guillemet omis en d&eacute;but de chapitre</a></li>
        <li><a href="#double_q">Guillemets doubles</a></li>
        <li><a href="#single_q">Guillemets simples</a></li>
        <li><a href="#quote_ea">Guillemets sur chaque ligne</a></li>
        <li><a href="#illust">Illustration</a></li>
        <li><a href="#bad_text">Image ne correspondant pas au texte</a></li>
        <li><a href="#bk_index">Index</a></li>
        <li><a href="#subscr">Indice</a></li>
        <li><a href="#subscr">Inf&eacute;rieures (lettres en indice)</a></li>
        <li><a href="#drop_caps">Lettrine</a></li>
      </ul>
    </td>
    <td width="50%" valign="top">
      <ul>
        <li><a href="#illust">L&eacute;gende (Illustration)</a></li>
        <li><a href="#para_side">Manchette (note en marge)</a></li>
        <li><a href="#hand_notes">Manuscrite, mention</a></li>
        <li><a href="#para_side">Marge (notes)</a></li>
        <li><a href="#bad_image">Mauvaise image</a></li>
        <li><a href="#next_word">Mot isol&eacute; en fin de page</a></li>
        <li><a href="#footnotes">Notes de fin et notes de bas de page</a></li>
        <li><a href="#para_side">Notes en marge</a></li>
        <li><a href="#line_no">Num&eacute;ros de ligne</a></li>
        <li><a href="#OCR_raised_o">O en exposant et degr&eacute;s</a></li>
        <li><a href="#blank_pg">Page blanche</a></li>
        <li><a href="#title_pg">Page de titre/fin</a></li>
        <li><a href="#para_space">Paragraphe (espacement et indentation)</a></li>
        <li><a href="#small_caps">Petites capitales</a></li>
        <li><a href="#page_hf">Pied de page</a></li>
        <li><a href="#period_p">Points de suspension &ldquo;...&rdquo;</a></li>
        <li><a href="#period_s">Points en fin de phrase</a></li>
        <li><a href="#punctuat">Ponctuation (espaces)</a></li>
        <li><a href="#poetry">Po&eacute;sie/&Eacute;pigrammes</a></li>
        <li><a href="#common_OCR">Probl&egrave;mes d'OCR courants</a></li>
        <li><a href="#line_br">Retour &agrave; la ligne</a></li>
        <li><a href="#prime">R&egrave;gle principale</a></li>
        <li><a href="#next_word">R&eacute;clame (Mot isol&eacute; en fin de page)</a></li>
        <li><a href="#summary">R&eacute;sum&eacute; des directives</a></li>
        <li><a href="#OCR_scanno">Scanno (probl&egrave;me d'OCR)</a></li>
        <li><a href="#subscr">Sous la ligne (texte en indice)</a></li>
        <li><a href="#supers">Sup&eacute;rieur (texte en exposant)</a></li>
        <li><a href="#toc">Table des mati&egrave;res</a></li>
        <li><a href="#tables">Tableaux</a></li>
        <li><a href="#chap_head">T&ecirc;te de chapitre</a></li>
        <li><a href="#play_n">Th&eacute;&acirc;tre</a></li>
        <li><a href="#em_dashes">Tirets, traits d'union et signes &ldquo;moins&rdquo;</a></li>
        <li><a href="#title_pg">Titre, page de</a></li>
        <li><a href="#anything">Tous autres points</a></li>
        <li><a href="#eol_hyphen">Traits d'union et tirets en fin de ligne</a></li>
        <li><a href="#eop_hyphen">Traits d'union et tirets en fin de page</a></li>
        <li><a href="#OCR_scanno">Wordcheck</a></li>
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
// vim: sw=4 ts=4 expandtab
