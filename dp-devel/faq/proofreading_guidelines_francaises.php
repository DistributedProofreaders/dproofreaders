<?

// Translated by user 'Pierre' at pgdp.net, 2006-02-08

$relPath='../pinc/';
include($relPath.'site_vars.php');
include($relPath.'faq.inc');
include($relPath.'pg.inc');
include($relPath.'connect.inc');
include($relPath.'theme.inc');
new dbConnect();
$no_stats=1;
theme('Directives de Relecture et correction','header');

$utf8_site=!strcasecmp($charset,"UTF-8");
?>

<H1 align=center>Directives de Relecture et correction</H1>
<H3 align=center>Version 1.9c, le 11 janvier 2006 &nbsp;</H3>
<TABLE cellSpacing=0 width="100%" border=0>
  <TBODY>
  <TR>
    <TD width=1 bgColor=silver><BR></TD>
    <TD align=left bgColor=silver><FONT size=+2><B>Table des 
      matières</B></FONT></TD></TR>
  <TR>
    <TD width=1 bgColor=silver><BR></TD>
    <TD align=left bgColor=white>
      <UL>
        <LI><A 
        href="#prime">La 
        règle principale </A>
 
        <LI><A 
        href="#about">À 
        propos de ce document</A> 
        <LI><A
        href="#comments">Commentaires 
        sur les projets</A> 
        <LI><A 
        href="#forums">Forum/Discuter 
        de ce Projet</A> 
        <LI><A 
        href="#prev_pg">Corriger 
        des erreurs sur les pages précédentes</A> </LI></UL></TD></TR>
  <TR>
    <TD width=1 bgColor=silver><BR></TD>
    <TD align=left bgColor=silver>
      <UL>
        <LI>Correction de... </LI></UL></TD></TR>
  <TR>
    <TD width=1 bgColor=silver><BR></TD>
    <TD align=left bgColor=white>
      <UL>
        <LI>&nbsp; 
        <UL>


          <LI><A 
          href="#line_br">Retours 
          à la ligne</A>  
           <LI><A 
          href="#double_q">Guillemets</A> 
          <LI><A 
          href="#single_q">Apostrophes 
          (simples quotes)</A> 
          <LI><A 
          href="#guill_chaque">Guillemets
          sur chaque ligne </A>
          <LI><A 
          href="#period_s">Points 
          entre les phrases</A> 
          <LI><A 
          href="#punctuat">Ponctuation</A> 
          <LI><A 
          href="#period_p">Points 
          de suspension "..."</A> 
          <LI><A 
          href="#contract">Contractions
          </A> 
          <LI><A 
          href="#extra_sp">Espaces 
          supplémentaires entre les mots</A> 
          <LI><A 
          href="#trail_s">Espace 
          en fin de ligne</A> 
          <LI><A 
          href="#line_no">Numéros 
          de ligne</A> 
          <LI><A 
          href="#italics">Italiques 
          et gras</A> 
          <LI><A 
          href="#supers">Texte 
          en Exposant</A> 
          <LI><A 
          href="#subscr">Texte 
          en indice</A> 
          <LI><A 
          href="#font_sz">Changement 
          de taille de police</A> 
          <LI><A
          href="#small_caps">Petites
          capitales</A>
          <LI><A 
          href="#lettrine">Lettre 
          de début de paragraphe grande ou ornée </A>
          <LI><A 
          href="#a_chars">Charactères 
          accentués et non-ASCII</A> 
          <LI><A 
          href="#char_diacr">Caractères 
          avec marques diacritiques </A>
          <LI><A 
          href="#f_chars">Alphabets 
          non latins</A> 
          <LI><A 
          href="#fract_s">Fractions</A> 
          <LI><A 
          href="#em_dashes">Tirets, 
          traits d'union et signes 'moins'</A> 
          <LI><A 
          href="#eol_hyphen">Traits 
          d'union en fin de ligne</A> 
          <LI><A 
          href="#eop_hyphen">Traits
          d'union en fin de page</A> 
          <LI><A 
          href="#para_space">Espacement 
          et indentation des paragraphes</A> 
          <LI><A 
          href="#mult_col">Colonnes 
          multiples</A> 
          <LI><A 
          href="#blank_pg">Page 
          blanche</A> 
          <LI><A 
          href="#page_hf">Entêtes 
          et bas de page</A> 
          <LI><A 
          href="#chap_head">Entêtes 
          de chapitres</A> 
          <LI><A 
          href="#illust">Illustrations</A> 
          <LI><A 
          href="#footnotes">Notes 
          de fin et de notes de bas de page</A> 
          <LI><A 
          href="#poetry">Poésie/Épigrammes 
          </A>
          <LI><A 
          href="#para_side">Notes
          en marge des paragraphes</A> 
          <LI><A 
          href="#tables">Tableaux</A> 
          <LI><A 
          href="#title_pg">Page 
          de garde/fin</A> 
          <LI><A 
          href="#toc">Table 
          des matières</A> 
          <LI><A 
          href="#bk_index">Index</A> 

          <LI><A 
          href="#play_n">Théâtre</A> 

          <LI><A 
          href="#anything">Tous 
          autres points nécessitant un traitement particulier, ou dont vous 
          n'êtes pas sûr</A></LI>
          <li><a href="#prev_notes">Notes des correcteurs précédents </a></li>
</UL></LI></UL>

  <TR>
    <TD width=1 bgColor=silver><BR></TD>
    <TD align=left bgColor=silver>
      <UL>
        <LI>Problèmes courants </LI></UL></TD></TR>
  <TR>
    <TD width=1 bgColor=silver><BR></TD>
    <TD align=left bgColor=white>
      <UL>
        <LI>&nbsp; 
        <UL>
          <LI><A 
          href="#OCR_1lI">Problèmes 
          d'OCR "1-l-I"</A> 
          <LI><A 
          href="#OCR_0O">Problèmes 
          d'OCR: 0-O</A> 
          <LI><A 
          href="#OCR_scanno">Problèmes 
          d'OCR: Erreurs de scan</A> 
          <LI><A 
          href="#hand_notes">Notes 
          manuscrites dans le livre</A> 
          <LI><A 
          href="#bad_image">Mauvaises 
          images</A> 
          <LI><A 
          href="#bad_text">Image 
          ne correspondant pas au texte</A> 
          <LI><A 
          href="#round1">Erreurs 
          des correcteurs précédents</A> 
          <LI><A 
          href="#p_errors">Erreurs 
          d'impressions et d'orthographe</A> 
          <LI><A 
          href="#f_errors">Erreurs 
          factuelles dans les textes</A> 
          <LI><A 
          href="#uncertain">Points 
          incertains</A> </LI></UL></LI></UL></TD></TR>
  <TR>
    <TD width=1 bgColor=silver><BR></TD>
    <TD bgColor=silver><BR></TD></TR></P></TBODY></TABLE>
<H3><A name=prime>La règle principale</A> </H3>
<P><EM>"Ne changez pas ce que l'auteur a écrit!"</EM> </P>
<P>Le livre 
électronique final vu par un lecteur, potentiellement plusieurs années dans le 
futur, doit transmettre l'intention de l'auteur de manière exacte. 
Si l'auteur écrit des mots d'une manière étrange, laissez-les. Si l'auteur écrit 
des choses choquantes, racistes ou partiales, laissez-les telles quelles. Si 
l'auteur semble mettre des italiques, des mots en gras ou des notes de bas de 
page tous les trois mots, gardez les italiques, les mots en gras et les notes de 
bas de page. Nous sommes des relecteurs, pas des éditeurs. Voir toutefois
la section <a href=#p_errors>Erreurs d'impression</a> pour les fautes évidentes
de l'imprimeur. <BR></P>
<P>Par contre, nous changeons des choses mineures qui n'affectent pas le sens de 
ce que l'auteur a écrit. Nous rejoignons les mots séparés par un retour à la 
ligne. (voir <A 
href="#eol_hyphen">Traits 
d'union en fin de lignes</A>) Ces changements nous permettent d'avoir des livres 
<EM>formatés d'une façon homogène</EM>. Nous suivons des règles de relecture 
pour avoir ce résultat. Lisez attentivement le reste de ces Règles en gardant ce
concept à l'esprit. Il y a un autre ensemble de règles concernant le formatage. 
Ces règles-ci ne s'appliquent qu'à la relecture. Ce seront d'autres volontaires qui
travailleront sur le formatage du texte. </P>
<P>Pour aider le prochain relecteur et le post-correcteur, nous gardons aussi 
les <A 
href="#line_br">retours à la 
ligne</A>. Il est ainsi facile de comparer les lignes du texte corrigé et les 
lignes de l'image. </P><!-- END RR -->
<TABLE cellSpacing=0 width="100%" border=0>
  <TBODY>
  <TR>
    <TD bgColor=silver><BR></TD></TR></TBODY></TABLE>
<H3><A name=about>À propos de ce document</A> </H3>
<P>Ce document explique les règles à suivre pour les relecteurs. Il a pour but 
de réduire les différences de formatage entre les 
travaux des différents correcteurs qui ont travaillé sur un même livre, de 
manière à ce que nous formations tous <EM>de la même manière. </EM>Cela rend le 
travail plus facile aux post-correcteurs. Mais ce document n'est pas censé être 
un recueil de règles éditoriales ou typographiques.<BR></P>
<P>Nous avons inclus dans ce document tous les points que les nouveaux 
utilisateurs ont demandé à propos du formatage lors de la correction. S'il 
manque des points, ou que vous considérez que des points manquent, ou que des 
points devraient être décrits de manière différente ou si quelque chose est 
vague, merci de nous le faire savoir.</P>
<P>Ce document est un travail en évolution permanente. Aidez-nous à progresser en nous 
envoyant vos suggestions de changements sur le forum Documentation dans <A 
href="http://www.pgdp.net/phpBB2/viewtopic.php?t=10779">ce thread</A>. </P>
<H3><A name=comments>Commentaires des projets</A> </H3>
<P>Dans la page d'interface dans laquelle vous commencez à corriger des pages, 
il y a une section "Commentaires du projet" qui contient des informations 
spécifiques à ce projet (livre). <b>Lisez celles-ci avant de commencer à 
corriger des pages!</b> Si le responsable de projet veut que vous formatiez 
quelque chose dans ce livre autrement que ce qui est dit dans ces directives, ce 
sera indiqué là. Les instructions dans les “Commentaires du projet” supplantent 
les règles dans ces directives, donc suivez-les. C'est également là
que le responsable du projet donne des règles spéciales concernant la phase
de formatage (qui ne s'appliquent donc pas à la phase de relecture).
Enfin, c'est aussi à cet endroit 
que le responsable de projet vous donne des informations intéressantes à propos 
des livres, comme leur provenance, etc.</P>
<P><EM>Lisez aussi la discussion sur le projet</EM>: Le chef de projet y 
clarifie des points portant spécifiquement sur le projet. Cette discussion est 
souvent utilisée par les relecteurs pour signaler aux autres relecteurs les 
problèmes récurrents dans le projet, et la meilleure façon de les résoudre. </P>
<P>Sur la page Projet, le lien'Images, Pages Proofread, &amp; Differences' 
permet de voir comment les autres relecteurs ont changé le texte. <A 
href="http://www.pgdp.net/phpBB2/viewtopic.php?t=10217">Ce fil de discussion 
</A>discute les différentes façon d'utiliser cette information. </P>
<H3><A name=forums>Forum/Discuter de ce Projet</A> </H3>
<P>Dans la page d'interface dans laquelle vous commencez à corriger des pages, 
sur la ligne “Forum”, il y a un lien indiquant “Discuter de ce projet” (si la 
discussion a déjà commencé) ou bien “Démarrer une discussion sur le projet” 
sinon. Cliquer sur ce lien vous amènera à un "thread" de forum pour ce projet 
spécifique. C'est l'endroit pour poser des questions à propos de ce livre, 
informer le responsable de projet à propos de problèmes, etc. L'utilisation de 
ce forum est la manière recommandée pour discuter avec le responsable de projet 
et les autres correcteurs qui travaillent sur ce livre. </P>
<H3><A name=prev_pg>Corriger des erreurs sur des pages précédentes</A></H3>
<P>Quand vous sélectionnez un projet pour travailler, la page <A 
href="#comments">Commentaires
de projet</A> correspondant à ce projet est chargée. </P>
<P>Cette page contient des liens vers les pages que vous avez corrigées 
récemment (si vous n'avez pas encore corrigé de pages, alors aucun lien ne sera 
affiché).</P>
<P>Les pages listées sous "DONE" et "IN PROGRESS" sont disponibles pour que vous 
puissiez corriger ou terminer votre travail de relecture. Cliquez sur le lien 
vers la page. Ainsi, si vous voyez que vous avez fait une erreur sur une page, 
vous pouvez cliquer sur cette page, et la rouvrir pour corriger l'erreur. </P>
<P> Il est également possible d'utiliser les liens 
"Images, Pages Proofread, &amp; Differences" ou l'option
"Just my pages". Ces pages présentent un lien "Edit" sur
toutes les pages sur lesquelles vous avez travaillé durant
ce round. Il est encore temps de les corriger.</P>
<P>Pour plus de détails, voyez <A 
href="http://www.pgdp.net/c/faq/prooffacehelp.php?i_type=0">Aide sur l'interface 
standard</A> ou bien <A 
href="http://www.pgdp.net/c/faq/prooffacehelp.php?i_type=1">Aide sur l'interface 
avancée</A>, ça dépend de l'interface que vous utilisez. </P><!-- END RR -->
<TABLE cellSpacing=0 cellPadding=6 width="100%" border=0>
  <TBODY>
  <TR>
    <TD bgColor=silver><FONT size=+2>Correction de...</FONT></TD></TR></TBODY></TABLE>
<H3><A name=line_br>Retours à la ligne</A> </H3>
<P>Laissez tous les retours à la ligne de manière à ce que le correcteur suivant 
puisse comparer les textes facilement.Si le correcteur qui est passé avant vous a supprimé les retours à 
la ligne, remettez-les, pour que les lignes correspondent à l'image.</P><!-- END RR -->
<H3><A name=double_q>Guillemets doubles ( " )</A> </H3>
<P>Utilisez les guillemets droits ASCII (").</P>
<P>Ne remplacez pas les guillemets par des apostrophes. Laissez ce que l'auteur 
a écrit.</P>
<P>Pour corriger du texte qui n'est pas en anglais, utilisez les caractères 
appropriés, s'ils sont disponibles. </P>
<P>En français, vous pouvez utilisez les guillemets français 
<FONT color=red>«</FONT><TT>comme ceci</TT><FONT color=red>» </FONT> car ils
sont disponibles dans la liste déroulante de caractères. N'oubliez pas d'effacer
les espaces après les guillemets ouvrants et avant les guillemets fermants. Ces espaces
seront rajoutés si nécessaire en phase de post-correction. Ceci s'applique aussi aux
langues qui utilisent ces guillemets de façon inversée, 
<FONT color=red>»</FONT><TT>comme ceci</TT><FONT color=red>«</FONT>.</P>

<P>Mais les guillemets utilisés dans certains livres 
allemands&nbsp; <FONT color=red>„</FONT><TT>comme ceci</TT><FONT 
color=red>”</FONT> ne sont pas disponibles dans la liste déroulante, car ils ne sont 
pas Latin-1. Utilisez alors les doubles quotes droites ASCII. 
Comme d'habitude, le chef de projet peut demander de faire autrement, pour un 
livre donné.</P>
<H3><A name=single_q>Apostrophes ( ' )</A> </H3>
<P>Utilisez l'apostrophe droite ASCII ('). </P>
<P>Ne la changez pas en double quote (guillemets). Laissez ce que l'auteur a 
écrit. </P>
<H3><A name=guill_chaque>Guillemets sur chaque ligne</A> </H3>
<P>Certains livres mettent des guillemets au début de chaque ligne dans une 
citation; enlevez-les, sauf pour la première ligne. Si la citation continue sur 
plusieurs paragraphes, mettez des guillemets au début de chaque paragraphe. </P>
<P>Souvent, les guillemets ne sont pas fermés avant la fin de la citation, au 
dernier paragraphe. Ne changez rien. N'ajoutez pas de guillemets fermants qui ne 
seraient pas dans l'original.</P>
<H3><A name=period_s>Points entre les phrases</A> </H3>
<P>Un seul espace après les points et aucun avant. Ne passez pas votre temps à 
supprimer les espaces en trop après le point si les espaces sont déjà dans le
texte scanné--il est facile de faire ça automatiquement à la post-correction. 
Voyez l'exemple à la section <A 
href="#para_side"></A>Notes en marge.</P>
<H3><A name=punctuat>Caractères de ponctuation</A> </H3>
<P>En général, il n'y a pas d'espace avant&nbsp; les caractères de ponctuation 
(à part les guillemets ouvrants). Si vous voyez un espace, supprimez-le. 
Cette règle s'applique aussi sur des livres en français, où des espaces sont 
normalement insérés avant certains signes. </P>
<P>
Vous verrez parfois des espaces "en trop" sur les livres imprimés aux XVIIIème et XIXème 
siècles, car une fraction d'espace est insérée 
avant les caractères "deux points" et "point virgule". Supprimez l'espace dans tous les
cas.</P>

<!-- END RR -->
<TABLE cellSpacing=0 cellPadding=4 border=1>
  <COLGROUP>
  <COL width=256>
  <TBODY>
  <TR>
    <TH vAlign=top>Texte scanné</TH>
    <TH vAlign=top>Texte correct</TH></TR>
  <TR>
    <TD vAlign=top><TT>and so it goes ; ever and ever.</TT></TD>
    <TD vAlign=top><TT>and so it goes; ever and ever.</TT> 
</TD></TR></TBODY></TABLE>

<H3><A name=period_p>Points de suspension "..."</A> </H3>
<P>Les règles sont différentes selon que le texte est en anglais ou non.</P>
<P><B>ANGLAIS</B>: Laissez un espace avant les trois points et un espace après. 
L'exception est à la fin d'une phrase: pas d'espace avant, mettre quatre points 
et un espace après. Ceci est vrai aussi pour d'autres signes de ponctuation. Les 
points de suspension suivent ce signe sans espace.&nbsp; Par exemple: 
<TT><BR>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;That I know ... is 
true.<BR>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;This is the 
end....<BR>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Wherefore art thou Romeo?... </TT></P>
<P><I>Parfois vous verrez le signe de ponctuation après les points de 
suspension. </I>Le texte corrigé sera alors comme ceci.&nbsp;</P>
<P><TT>Wherefore art thou Romeo...? </TT></P>
<P>Vous devrez si nécessaire enlever des points ou en rajouter pour qu'il y en 
ait exactement trois (ou quatre, selon le cas).</P>
<P><B>AUTRES LANGUES</B>: Suivez la règle "Respectez le style utilisé sur la 
page imprimée". Mettez autant de points qu'il y en a d'imprimés, et insérez des
espaces selon ce qui est imprimé. Si, sur une page donnée, ce n'est pas clair,
insérez une note [**pas clair]. Note: les post-processeurs remplaceront d'éventuels
espaces à l'intérieur des points de suspension par des espaces insécables. 
</P>

<H3><A name=contract>Contractions</A> </H3>
<P>En anglais, enlevez les espaces des contractions. Par exemple: <TT>would 
n't</TT> devrait être <TT>wouldn't</TT>. (C'était une convention utilisée pour 
indiquer que would et not étaient originellement deux mots séparés.) Parfois 
aussi, c'est une erreur d'OCR, enlevez l'espace en trop dans tous les cas. </P>
<P>Certains chefs de projet recommanderont dans les <A 
href="#comments">commentaires 
de projet </A>de ne pas enlever les espaces dans les contractions, en 
particulier dans des textes écrits en dialecte, argot ou une langue autre que 
l'anglais.</P>
<H3><A name=extra_sp>Espace additionel entre les mots</A> </H3>
<P>Des espaces supplémentaires entre les mots sont une erreur d'OCR courante. Il 
n'est pas nécessaire de supprimer ces espaces étant donné qu'il est facile de le 
faire à la post-correction.</P>
<P>Mais les espaces supplémentaires autour de la ponctuation, des tirets, etc. 
doivent être supprimés car il est difficile de faire cela automatiquement.</P>
<P>Par exemple, dans <B>A horse&nbsp;;&nbsp;&nbsp;&nbsp;my kingdom for a horse. 
</B>il faut supprimer l'espace avant&nbsp; le point virgule. Mais les deux 
espaces après le point virgule ne posent pas de problème : vous n'êtes pas 
obligés d'en supprimer un.&nbsp;<BR></P>
<H3><A name=trail_s>Espaces en fin de ligne</A></H3>
<P>Inutile d'insérer des espaces à la fin des lignes. N'enlevez pas non plus les 
espaces en trop. Tout ceci peut se gérer automatiquement en phase de 
post-correction.</P>
<H3><A name=line_no>Numéros de ligne</A> </H3>
<P>Gardez les numéros de ligne. Placez-les au moins 6 espaces à droite du texte, 
même si, sur l'image, ces numéros sont à gauche. </P>
<P>Il y a souvent des numéros de lignes, sur les livres de poésie, tous les 5, 
10 ou 20 vers. Ces numéros de vers peuvent être utiles au lecteur.</P>
<H3><A name=italics>Italiques, gras</A> </H3>
<P>Le texte en <i>italiques</i> dans le texte imprimé peut apparaître dans votre page
de travail entouré
des marques <TT>&lt;i&gt;</TT> 
avant et  <TT>&lt;/i&gt;</TT> après. <B>Le texte en
gras</B> (imprimé avec une fonte "lourde") peut apparaître avec 
<TT>&lt;b&gt;</TT> inséré avant et <TT>&lt;/b&gt;</TT> après le texte en gras.</p>
<p>N'ajoutez pas ces marques, mais ne les supprimez pas (à moins qu'elles
entourent du texte superflu, non présent dans le texte imprimé, auquel cas 
supprimez le tout). La gestion de ces marques se fait au formatage, plus tard
dans le processus. </p>
<H3><A name=supers>Exposants</A> </H3>
<P>Les vieux livres abrégeaient souvent les mots en contractions, et les 
imprimaient en exposant, par exemple:<FONT color=red> <BR>Gen<SUP>rl</SUP> 
Washington defeated L<SUP>d</SUP>Cornwall's army.<BR></FONT>Insérez un chapeau 
pour identifier l' abréviation/contraction, comme suit: 
<BR>&nbsp;&nbsp;&nbsp;&nbsp; <TT>Gen^rl Washington defeated L^d Cornwall's 
army.</TT> </P>

<H3><A name=subscr>Texte en Indice</A></H3>
<P>On trouve la notation "indice" dans des ouvrages scientifiques, rarement 
ailleurs. Indiquez l'indice en mettant un signe "souligné" <TT>_</TT> devant. 
<BR>Par exemple: <BR>&nbsp; &nbsp; &nbsp; &nbsp; H<SUB>2</SUB>O. <BR>donne 
<BR>&nbsp; &nbsp; &nbsp; &nbsp; <TT>H_2O.<BR></TT></P>

<H3><A name=font_sz>Changement de taille de police</A> </H3>
<P>Ne faites rien pour indiquer un changement de taille de police. 
C'est le travail des formateurs, pour plus tard.</P>
<H3><A name=small_caps>Petites capitales</A></H3>
      <SPAN style="FONT-VARIANT: small-caps">Les petites capitales</SPAN>
      (autrement dit des lettres capitales plus petites
      que des capitales ordinaires)
      apparaissent parfois dans votre page de travail avec 
      <TT>&lt;sc&gt;</TT> avant et <TT>&lt;/sc&gt;</TT> après. N'enlevez
      pas ces marques de formatage (à moins qu'elles entourent du mauvais
      texte, non présent dans le texte imprimé). Ne les enlevez pas non plus. 
      La gestion de ces marques relève du formatage, qui intervient plus tard
      dans le processus. A l'intérieur de ces marques, corrigez le caractère lui-même
      sans vous préoccuper de sa casse. Laissez les majuscules en majuscules, et les
      minuscules en minuscules. </P>
<H3><A name=lettrine>Lettre de début de paragraphe grande ou ornée.</A> </H3>
<P>Souvent, la première lettre d'un chapitre, section ou paragraphe est imprimée 
très grande et ornée (une lettrine). Dans votre texte, laissez simplement la 
lettre. </P>
<H3><A name=a_chars>Charactères accentués et non-ASCII</A> </H3>
<P>Essayez d'insérer les caractères accentués et non-ASCII du jeu Latin-1. Voir
plus loin pour ce qui concerne les signes diacritiques sortant du jeu Latin-1. </P>

<P>Il existe plusieurs façons d'écrire ces caractères:</P>
<UL compact>
  <LI>Les menus déroulants de votre interface de correction. 
  <LI>Applications fournies avec votre système d'exploitation. 
  <UL compact>
    <LI>Windows: "Character Map"<BR>Accès par:<BR>Start: Run: charmap, 
    or<BR>Start: Accessories: System Tools: Character Map. 
    <LI>Macintosh: Key Caps ou "Keyboard Viewer" 
    <LI>Linux: Dépendant de l'environnement d'IHM. Pour KDE, essayez 
    KCharSelect. </LI></UL>
  <LI>Des programmes en ligne, comme <A 
        href="http://free.pages.at/krauss/computer/xml/daten/edicode.html">Edicode</A>.  
  <LI>Raccourcis clavier.<BR>Voir ci-dessous les tables pour <A 
  href="#a_chars_win">Windows</A> 
  et <A 
  href="#a_chars_mac">Macintosh</A>. 

  <LI>Il est possible de changer les réglages clavier ou le "locale" pour avoir 
  accès directement aux accents. 
  <UL compact>
    <LI>Windows: Panneau de contrôle (Keyboard, Input Locales) 
    <LI>Macintosh: Input Menu (sur Menu Bar) 
    <LI>Linux: Configuration X. </LI></UL></LI></UL>
<P>Sur le projet Gutenberg, nous avons toujours un texte au format ASCII 7 bits, 
mais nous acceptons aussi des versions avec d'autres encodages, qui préservent 
l'information présente dans le texte original. Pour nous, ceci signifie que nous 
utilisons Latin-1, ou ISO 8859-1 et -15, et dans le futur, Unicode. </P><!-- END RR --><A name=a_chars_win></A>
<P><B>Pour Windows</B>: </P>
<UL compact>
  <UL compact>
    <LI>Vous pouvez utiliser la table des caractères (Démarrer: Exécuter: Run: 
    charmap) pour sélectioner les lettres individuelles et les copier &amp; 
    coller. 
    <LI>Si vous utilisez l'interface de correction avancée, le tag <I>more</I> 
    crée une fenêtre pop-up contenant ces caractères, depuis laquelle vous 
    pouvez copier/coller. 
    <LI>Vous pouvez taper les codes ALT+Nombre pour générer ces caractères. 
    <BR>(Ils sont bien plus rapide à utiliser que copier &amp; coller une fois 
    que vous y êtes habitués). <BR>Pressez la touche Alt et tapez les quatre 
    chiffres dans le pavé numérique (les chiffres au-dessus des lettres ne 
    fonctionnent pas). <BR>Vous devez taper les 4 chiffres, y compris le premier 
    0. Remarquez que le code de la version majuscule d'une lettre accentuée est 
    inférieur de 32 à sa version minuscule. <BR>(Ceci marche sur un clavier 
    anglais. Pas forcément pour d'autres).&nbsp;<BR>La table ci dessous montre 
    les codes que nous utilisons (<A 
    href="http://www.pgdp.net/c/faq/charwin.pdf">Version imprimable de cette 
    table)</A>. <BR>N'utilisez pas d'autres caractères spéciaux à moins que le 
    responsable du projet vous le demande dans les <A
    href="#comments">Commentaires 
    de projet</A>. </LI></UL><BR>
  <TABLE rules=all align=center summary="Raccourcis Windows" border=6>
    <TBODY>
    <TR>
      <TH bgColor=cornsilk colSpan=14>Raccourcis Windows pour caractères 
        Latin-1</TH></TR>
    <TR bgColor=cornsilk>
      <TH colSpan=2>` grave</TH>
      <TH colSpan=2>´ acute (aigu)</TH>
      <TH colSpan=2>^ circumflex</TH>
      <TH colSpan=2>~ tilde</TH>
      <TH colSpan=2>¨ umlaut</TH>
      <TH colSpan=2>° ring</TH>
      <TH colSpan=2>Æ ligature</TH></TR>
    <TR>
      <TD title="Small a grave" align=middle bgColor=mistyrose>à </TD>
      <TD>Alt-0224</TD>
      <TD title="Small a acute" align=middle bgColor=mistyrose>á </TD>
      <TD>Alt-0225</TD>
      <TD title="Small a circumflex" align=middle bgColor=mistyrose>â </TD>
      <TD>Alt-0226</TD>
      <TD title="Small a tilde" align=middle bgColor=mistyrose>ã </TD>
      <TD>Alt-0227</TD>
      <TD title="Small a umlaut" align=middle bgColor=mistyrose>ä </TD>
      <TD>Alt-0228</TD>
      <TD title="Small a ring" align=middle bgColor=mistyrose>å </TD>
      <TD>Alt-0229</TD>
      <TD title="Small ae ligature" align=middle bgColor=mistyrose>æ </TD>
      <TD>Alt-0230</TD></TR>
    <TR>
      <TD title="Capital A grave" align=middle bgColor=mistyrose>À </TD>
      <TD>Alt-0192</TD>
      <TD title="Capital A acute" align=middle bgColor=mistyrose>Á </TD>
      <TD>Alt-0193</TD>
      <TD title="Capital A circumflex" align=middle bgColor=mistyrose>Â </TD>
      <TD>Alt-0194</TD>
      <TD title="Capital A tilde" align=middle bgColor=mistyrose>Ã </TD>
      <TD>Alt-0195</TD>
      <TD title="Capital A umlaut" align=middle bgColor=mistyrose>Ä </TD>
      <TD>Alt-0196</TD>
      <TD title="Capital A ring" align=middle bgColor=mistyrose>Å </TD>
      <TD>Alt-0197</TD>
      <TD title="Capital AE ligature" align=middle bgColor=mistyrose>Æ </TD>
      <TD>Alt-0198</TD></TR>
    <TR>
      <TD title="Small e grave" align=middle bgColor=mistyrose>è </TD>
      <TD>Alt-0232</TD>
      <TD title="Small e acute" align=middle bgColor=mistyrose>é </TD>
      <TD>Alt-0233</TD>
      <TD title="Small e circumflex" align=middle bgColor=mistyrose>ê </TD>
      <TD>Alt-0234</TD>
      <TD></TD>
      <TD></TD>
      <TD title="Small e umlaut" align=middle bgColor=mistyrose>ë </TD>
      <TD>Alt-0235</TD>
      <TD></TD>
      <TD></TD>
      <TD></TD>
      <TD></TD></TR>
    <TR>
      <TD title="Capital E grave" align=middle bgColor=mistyrose>È </TD>
      <TD>Alt-0200</TD>
      <TD title="Capital E acute" align=middle bgColor=mistyrose>É </TD>
      <TD>Alt-0201</TD>
      <TD title="Capital E circumflex" align=middle bgColor=mistyrose>Ê </TD>
      <TD>Alt-0202</TD>
      <TD></TD>
      <TD></TD>
      <TD title="Capital E umlaut" align=middle bgColor=mistyrose>Ë </TD>
      <TD>Alt-0203</TD>
      <TD></TD>
      <TD></TD>
      <TD></TD>
      <TD></TD></TR>
    <TR>
      <TD title="Small i grave" align=middle bgColor=mistyrose>ì </TD>
      <TD>Alt-0236</TD>
      <TD title="Small i acute" align=middle bgColor=mistyrose>í </TD>
      <TD>Alt-0237</TD>
      <TD title="Small i circumflex" align=middle bgColor=mistyrose>î </TD>
      <TD>Alt-0238</TD>
      <TD></TD>
      <TD></TD>
      <TD title="Small i umlaut" align=middle bgColor=mistyrose>ï </TD>
      <TD>Alt-0239</TD>
      <TD></TD>
      <TD></TD>
      <TD></TD>
      <TD></TD></TR>
    <TR>
      <TD title="Capital I grave" align=middle bgColor=mistyrose>Ì </TD>
      <TD>Alt-0204</TD>
      <TD title="Capital I acute" align=middle bgColor=mistyrose>Í </TD>
      <TD>Alt-0205</TD>
      <TD title="Capital I circumflex" align=middle bgColor=mistyrose>Î </TD>
      <TD>Alt-0206</TD>
      <TD></TD>
      <TD></TD>
      <TD title="Capital I umlaut" align=middle bgColor=mistyrose>Ï </TD>
      <TD>Alt-0207</TD>
      <TH bgColor=cornsilk colSpan=2>/ slash</TH>
      <TH bgColor=cornsilk colSpan=2>Œ ligature</TH></TR>
    <TR>
      <TD title="Small o grave" align=middle bgColor=mistyrose>ò </TD>
      <TD>Alt-0242</TD>
      <TD title="Small o acute" align=middle bgColor=mistyrose>ó </TD>
      <TD>Alt-0243</TD>
      <TD title="Small o circumflex" align=middle bgColor=mistyrose>ô </TD>
      <TD>Alt-0244</TD>
      <TD title="Small o tilde" align=middle bgColor=mistyrose>õ </TD>
      <TD>Alt-0245</TD>
      <TD title="Small o umlaut" align=middle bgColor=mistyrose>ö </TD>
      <TD>Alt-0246</TD>
      <TD title="Small o slash" align=middle bgColor=mistyrose>ø </TD>
      <TD>Alt-0248</TD>
      <TD title="Small oe ligature" align=middle bgColor=mistyrose>œ </TD>
      <TD>[oe]</TD></TR>
    <TR>
      <TD title="Capital O grave" align=middle bgColor=mistyrose>Ò </TD>
      <TD>Alt-0210</TD>
      <TD title="Capital O acute" align=middle bgColor=mistyrose>Ó </TD>
      <TD>Alt-0211</TD>
      <TD title="Capital O circumflex" align=middle bgColor=mistyrose>Ô </TD>
      <TD>Alt-0212</TD>
      <TD title="Capital O tilde" align=middle bgColor=mistyrose>Õ </TD>
      <TD>Alt-0213</TD>
      <TD title="Capital O umlaut" align=middle bgColor=mistyrose>Ö </TD>
      <TD>Alt-0214</TD>
      <TD title="Capital O slash" align=middle bgColor=mistyrose>Ø </TD>
      <TD>Alt-0216</TD>
      <TD title="Capital OE ligature" align=middle bgColor=mistyrose>Œ </TD>
      <TD>[OE]&nbsp;</TD></TR>
    <TR>
      <TD title="Small u grave" align=middle bgColor=mistyrose>ù </TD>
      <TD>Alt-0249</TD>
      <TD title="Small u acute" align=middle bgColor=mistyrose>ú </TD>
      <TD>Alt-0250</TD>
      <TD title="Small u circumflex" align=middle bgColor=mistyrose>û </TD>
      <TD>Alt-0251</TD>
      <TD></TD>
      <TD></TD>
      <TD title="Small u umlaut" align=middle bgColor=mistyrose>ü </TD>
      <TD>Alt-0252</TD>
      <TD></TD>
      <TD></TD>
      <TD></TD>
      <TD></TD></TR>
    <TR>
      <TD title="Capital U grave" align=middle bgColor=mistyrose>Ù </TD>
      <TD>Alt-0217</TD>
      <TD title="Capital U acute" align=middle bgColor=mistyrose>Ú </TD>
      <TD>Alt-0218</TD>
      <TD title="Capital U circumflex" align=middle bgColor=mistyrose>Û </TD>
      <TD>Alt-0219</TD>
      <TD></TD>
      <TD></TD>
      <TD title="Capital U umlaut" align=middle bgColor=mistyrose>Ü </TD>
      <TD>Alt-0220</TD>
      <TH bgColor=cornsilk colSpan=2>currency </TH>
      <TH bgColor=cornsilk colSpan=2>mathematics </TH></TR>
    <TR>
      <TD></TD>
      <TD></TD>
      <TD></TD>
      <TD></TD>
      <TD></TD>
      <TD></TD>
      <TD title="Small n tilde" align=middle bgColor=mistyrose>ñ </TD>
      <TD>Alt-0241</TD>
      <TD title="Small y umlaut" align=middle bgColor=mistyrose>ÿ </TD>
      <TD>Alt-0255</TD>
      <TD title=Cents align=middle bgColor=mistyrose>¢ </TD>
      <TD>Alt-0162</TD>
      <TD title=plus/minus align=middle bgColor=mistyrose>± </TD>
      <TD>Alt-0177</TD></TR>
    <TR>
      <TD></TD>
      <TD></TD>
      <TD></TD>
      <TD></TD>
      <TD></TD>
      <TD></TD>
      <TD title="Capital N tilde" align=middle bgColor=mistyrose>Ñ </TD>
      <TD>Alt-0209</TD>
      <TD title="Capital Y umlaut" align=middle bgColor=mistyrose>Ÿ </TD>
      <TD>Alt-0159</TD>
      <TD title=Pounds align=middle bgColor=mistyrose>£ </TD>
      <TD>Alt-0163</TD>
      <TD title=Multiplication align=middle bgColor=mistyrose>× </TD>
      <TD>Alt-0215</TD></TR>
    <TR>
      <TH bgColor=cornsilk colSpan=2>çedilla </TH>
      <TH bgColor=cornsilk colSpan=2>Icelandic </TH>
      <TH bgColor=cornsilk colSpan=2>marks </TH>
      <TH bgColor=cornsilk colSpan=2>accents </TH>
      <TH bgColor=cornsilk colSpan=2>punctuation </TH>
      <TD title=Yen align=middle bgColor=mistyrose>¥ </TD>
      <TD>Alt-0165</TD>
      <TD title=Division align=middle bgColor=mistyrose>÷ </TD>
      <TD>Alt-0247</TD></TR>
    <TR>
      <TD title="Small c cedilla" align=middle bgColor=mistyrose>ç </TD>
      <TD>Alt-0231</TD>
      <TD title="Capital Thorn" align=middle bgColor=mistyrose>Þ </TD>
      <TD>Alt-0222</TD>
      <TD title=Copyright align=middle bgColor=mistyrose>© </TD>
      <TD>Alt-0169</TD>
      <TD title="acute accent" align=middle bgColor=mistyrose>´ </TD>
      <TD>Alt-0180</TD>
      <TD title="Inverted Question Mark" align=middle bgColor=mistyrose>¿ </TD>
      <TD>Alt-0191</TD>
      <TD title=Dollars align=middle bgColor=mistyrose>$ </TD>
      <TD>Alt-0036</TD>
      <TD title="Logical Not" align=middle bgColor=mistyrose>¬ </TD>
      <TD>Alt-0172</TD></TR>
    <TR>
      <TD title="Capital C cedilla" align=middle bgColor=mistyrose>Ç </TD>
      <TD>Alt-0199</TD>
      <TD title="Small thorn" align=middle bgColor=mistyrose>þ </TD>
      <TD>Alt-0254</TD>
      <TD title="Registration Mark" align=middle bgColor=mistyrose>® </TD>
      <TD>Alt-0174</TD>
      <TD title="umlaut accent" align=middle bgColor=mistyrose>¨ </TD>
      <TD>Alt-0168</TD>
      <TD title="Inverted Exclamation" align=middle bgColor=mistyrose>¡ </TD>
      <TD>Alt-0161</TD>
      <TD title="General Currency" align=middle bgColor=mistyrose>¤ </TD>
      <TD>Alt-0164</TD>
      <TD title=Degrees align=middle bgColor=mistyrose>° </TD>
      <TD>Alt-0176</TD></TR>
    <TR>
      <TH bgColor=cornsilk colSpan=2>superscripts </TH>
      <TD title="Capital Eth" align=middle bgColor=mistyrose>Ð </TD>
      <TD>Alt-0208</TD>
      <TD title=Trademark align=middle bgColor=mistyrose>™ </TD>
      <TD>Alt-0153</TD>
      <TD title="macron accent" align=middle bgColor=mistyrose>¯ </TD>
      <TD>Alt-0175</TD>
      <TD title="guillemot left" align=middle bgColor=mistyrose>« </TD>
      <TD>Alt-0171</TD>
      <TD></TD>
      <TD></TD>
      <TD title=Micro align=middle bgColor=mistyrose>µ </TD>
      <TD>Alt-0181</TD></TR>
    <TR>
      <TD title="superscript 1" align=middle bgColor=mistyrose>¹ </TD>
      <TD>Alt-0185</TD>
      <TD title="Small eth" align=middle bgColor=mistyrose>ð </TD>
      <TD>Alt-0240</TD>
      <TD title="Paragraph (pilcrow)" align=middle bgColor=mistyrose>¶ </TD>
      <TD>Alt-0182</TD>
      <TD title=cedilla align=middle bgColor=mistyrose>¸ </TD>
      <TD>Alt-0184</TD>
      <TD title="guillemot right" align=middle bgColor=mistyrose>» </TD>
      <TD>Alt-0187</TD>
      <TH bgColor=cornsilk colSpan=2>ordinals </TH>
      <TD title="1/4 Fraction" align=middle bgColor=mistyrose>¼ </TD>
      <TD>Alt-0188</TD></TR>
    <TR>
      <TD title="superscript 2" align=middle bgColor=mistyrose>² </TD>
      <TD>Alt-0178</TD>
      <TH bgColor=cornsilk colSpan=2>sz ligature </TH>
      <TD title=Section align=middle bgColor=mistyrose>§ </TD>
      <TD>Alt-0167</TD>
      <TD></TD>
      <TD></TD>
      <TD title="Middle dot" align=middle bgColor=mistyrose>· </TD>
      <TD>Alt-0183</TD>
      <TD title="Masculine Ordinal" align=middle bgColor=mistyrose>º </TD>
      <TD>Alt-0186</TD>
      <TD title="1/2 Fraction" align=middle bgColor=mistyrose>½<sup>1</sup> </TD>
      <TD>Alt-0189</TD></TR>
    <TR>
      <TD title="superscript 3" align=middle bgColor=mistyrose>³<sup>1</sup> </TD>
      <TD>Alt-0179</TD>
      <TD title="sz ligature" align=middle bgColor=mistyrose>ß </TD>
      <TD>Alt-0223</TD>
      <TD title="Broken Vertical bar" align=middle bgColor=mistyrose>¦ </TD>
      <TD>Alt-0166</TD>
      <TD></TD>
      <TD></TD>
      <TD title=asterisk align=middle bgColor=mistyrose>* </TD>
      <TD>Alt-0042</TD>
      <TD title="Feminine Ordinal" align=middle bgColor=mistyrose>ª </TD>
      <TD>Alt-0170</TD>
      <TD title="3/4 Fraction" align=middle bgColor=mistyrose>¾ </TD>
      <TD>Alt-0190</TD></TR></TBODY></TABLE>
  <P>Notez le traitement spécial de la ligature oe. Par exemple, le 
  mot c&oelig;ur devient c[oe]ur. </p>
 <sup>1</sup> A moins que les directives de projets l'ordonnent, n'utilisez pas
les caractères spéciaux de fraction, mais suivez les guidelines de <a href="#fract_s">
fractions</a>. Ce qui donne 1/2, 3/4, etc. 
  <P><B>Pour Apple Macintosh</B>: </P>
  <UL compact>
    <LI>Vous pouvez utiliser le Apple Key Caps en tant que référence. Dans l'OS 
    9 &amp; précédents, il est localisé dans le Menu Pomme; Dans OS X jusqu'à 
    10.2, il est dans Applications, Utilities .<BR>Ceci affiche l'image d'un 
    clavier, et en pressant MAJ, OPT et command/pomme ou une combinaison de ces 
    touches vous verrez comment produire chaque caractère. Utilisez cette 
    référence pour voir comment taper chaque caractère, ou vous pouvez copier 
    &amp; coller de cette application vers le document. 
    <LI>Dans l'OS X 10.3 et plus, on utilise une palette disponible par le menu 
    Input (le menu déroulant attaché à l'icone "drapeau" de votre "locale".) 
    Elle s'appelle "Show Keyboard Viewer". Si ce n'est pas dans votre menu 
    Input, ou si vous n'avez pas ce menu, activez-la en ouvrant "System 
    Preferences", panneau "International", et choisissez le panneau "Input 
    menu". "Show input menu in menu bar" doit être cochée. Dans la vue 
    "spreadsheet", cochez la case pour "Keyboard viewer" pour tous les "locales" 
    d'entrée que vous utilisez. 
    <LI>Si vous utilisez l'interface de correction avancée, le tag <I>more</I> 
    crée une fenêtre pop-up contenant ces caractères, depuis laquelle vous 
    pouvez copier/coller. 
    <LI>Vous pouvez aussi utiliser le raccourci Apple Opt- . <BR>Une fois qu'on 
    a l'habitude des codes, c'est plus rapide que copier/coller. Appuyez sur 
    Opt, tapez le symbole d'accent, puis la lettre à accentuer (pour certains 
    codes, il suffit de maintenir Opt appuyée, puis taper le symbole). 
    <BR><BR>(Ceci marche sur un clavier anglais. Pas forcément pour 
    d'autres).&nbsp;<BR>La table ci dessous montre les codes que nous utilisons 
    (<A href="http://www.pgdp.net/c/faq/charapp.pdf">Version imprimable de cette 
    table)</A>. <BR>N'utilisez pas d'autres caractères spéciaux à moins que le 
    responsable du projet vous le demande dans les <A 
    href="#comments">Commentaires 
    de projet</A>. </LI></UL><BR></UL><BR><A name=a_chars_mac></A>
<TABLE rules=all align=center summary="Raccourcis Mac" border=6>
  <TBODY>
  <TR bgColor=cornsilk>
    <TH colSpan=14>Raccourcis Mac pour caractères Latin-1</TH>
  <TR bgColor=cornsilk>
    <TH colSpan=2>` grave</TH>
    <TH colSpan=2>´ acute (aigu)</TH>
    <TH colSpan=2>^ circumflex</TH>
    <TH colSpan=2>~ tilde</TH>
    <TH colSpan=2>¨ umlaut</TH>
    <TH colSpan=2>° ring</TH>
    <TH colSpan=2>Æ ligature</TH></TR>
  <TR>
    <TD title="Small a grave" align=middle bgColor=mistyrose>à </TD>
    <TD>Opt-~, a</TD>
    <TD title="Small a acute" align=middle bgColor=mistyrose>á </TD>
    <TD>Opt-e, a</TD>
    <TD title="Small a circumflex" align=middle bgColor=mistyrose>â </TD>
    <TD>Opt-i, a</TD>
    <TD title="Small a tilde" align=middle bgColor=mistyrose>ã </TD>
    <TD>Opt-n, a</TD>
    <TD title="Small a umlaut" align=middle bgColor=mistyrose>ä </TD>
    <TD>Opt-u, a</TD>
    <TD title="Small a ring" align=middle bgColor=mistyrose>å </TD>
    <TD>Opt-a </TD>
    <TD title="Small ae ligature" align=middle bgColor=mistyrose>æ </TD>
    <TD>Opt-' </TD></TR>
  <TR>
    <TD title="Capital A grave" align=middle bgColor=mistyrose>À </TD>
    <TD>Opt-~, A</TD>
    <TD title="Capital A acute" align=middle bgColor=mistyrose>Á </TD>
    <TD>Opt-e, A</TD>
    <TD title="Capital A circumflex" align=middle bgColor=mistyrose>Â </TD>
    <TD>Opt-i, A</TD>
    <TD title="Capital A tilde" align=middle bgColor=mistyrose>Ã </TD>
    <TD>Opt-n, A</TD>
    <TD title="Capital A umlaut" align=middle bgColor=mistyrose>Ä </TD>
    <TD>Opt-u, A</TD>
    <TD title="Capital A ring" align=middle bgColor=mistyrose>Å </TD>
    <TD>Opt-A </TD>
    <TD title="Capital AE ligature" align=middle bgColor=mistyrose>Æ </TD>
    <TD>Opt-" </TD></TR>
  <TR>
    <TD title="Small e grave" align=middle bgColor=mistyrose>è </TD>
    <TD>Opt-~, e</TD>
    <TD title="Small e acute" align=middle bgColor=mistyrose>é </TD>
    <TD>Opt-e, e</TD>
    <TD title="Small e circumflex" align=middle bgColor=mistyrose>ê </TD>
    <TD>Opt-i, e</TD>
    <TD></TD>
    <TD></TD>
    <TD title="Small e umlaut" align=middle bgColor=mistyrose>ë </TD>
    <TD>Opt-u, e</TD>
    <TD></TD>
    <TD></TD>
    <TD></TD>
    <TD></TD></TR>
  <TR>
    <TD title="Capital E grave" align=middle bgColor=mistyrose>È </TD>
    <TD>Opt-~, E</TD>
    <TD title="Capital E acute" align=middle bgColor=mistyrose>É </TD>
    <TD>Opt-e, E</TD>
    <TD title="Capital E circumflex" align=middle bgColor=mistyrose>Ê </TD>
    <TD>Opt-i, E</TD>
    <TD></TD>
    <TD></TD>
    <TD title="Capital E umlaut" align=middle bgColor=mistyrose>Ë </TD>
    <TD>Opt-u, E</TD>
    <TD></TD>
    <TD></TD>
    <TD></TD>
    <TD></TD></TR>
  <TR>
    <TD title="Small i grave" align=middle bgColor=mistyrose>ì </TD>
    <TD>Opt-~, i</TD>
    <TD title="Small i acute" align=middle bgColor=mistyrose>í </TD>
    <TD>Opt-e, i</TD>
    <TD title="Small i circumflex" align=middle bgColor=mistyrose>î </TD>
    <TD>Opt-i, i</TD>
    <TD></TD>
    <TD></TD>
    <TD title="Small i umlaut" align=middle bgColor=mistyrose>ï </TD>
    <TD>Opt-u, i</TD>
    <TD></TD>
    <TD></TD>
    <TD></TD>
    <TD></TD></TR>
  <TR>
    <TD title="Capital I grave" align=middle bgColor=mistyrose>Ì </TD>
    <TD>Opt-~, I</TD>
    <TD title="Capital I acute" align=middle bgColor=mistyrose>Í </TD>
    <TD>Opt-e, I</TD>
    <TD title="Capital I circumflex" align=middle bgColor=mistyrose>Î </TD>
    <TD>Opt-i, I</TD>
    <TD></TD>
    <TD></TD>
    <TD title="Capital I umlaut" align=middle bgColor=mistyrose>Ï </TD>
    <TD>Opt-u, I</TD>
    <TH bgColor=cornsilk colSpan=2>/ slash</TH>
    <TH bgColor=cornsilk colSpan=2>Œ ligature</TH></TR>
  <TR>
    <TD title="Small o grave" align=middle bgColor=mistyrose>ò </TD>
    <TD>Opt-~, o</TD>
    <TD title="Small o acute" align=middle bgColor=mistyrose>ó </TD>
    <TD>Opt-e, o</TD>
    <TD title="Small o circumflex" align=middle bgColor=mistyrose>ô </TD>
    <TD>Opt-i, o</TD>
    <TD title="Small o tilde" align=middle bgColor=mistyrose>õ </TD>
    <TD>Opt-n, o</TD>
    <TD title="Small o umlaut" align=middle bgColor=mistyrose>ö </TD>
    <TD>Opt-u, o</TD>
    <TD title="Small o slash" align=middle bgColor=mistyrose>ø </TD>
    <TD>Opt-o </TD>
    <TD title="Small oe ligature" align=middle bgColor=mistyrose>œ </TD>
    <TD>[oe]</TD></TR>
  <TR>
    <TD title="Capital O grave" align=middle bgColor=mistyrose>Ò </TD>
    <TD>Opt-~, O</TD>
    <TD title="Capital O acute" align=middle bgColor=mistyrose>Ó </TD>
    <TD>Opt-e, O</TD>
    <TD title="Capital I circumflex" align=middle bgColor=mistyrose>Ô </TD>
    <TD>Opt-i, O</TD>
    <TD title="Capital O tilde" align=middle bgColor=mistyrose>Õ </TD>
    <TD>Opt-n, O</TD>
    <TD title="Capital O umlaut" align=middle bgColor=mistyrose>Ö </TD>
    <TD>Opt-u, O</TD>
    <TD title="Capital O slash" align=middle bgColor=mistyrose>Ø </TD>
    <TD>Opt-O </TD>
    <TD title="Capital OE ligature" align=middle bgColor=mistyrose>Œ </TD>
    <TD>[OE]</TD></TR>
  <TR>
    <TD title="Small u grave" align=middle bgColor=mistyrose>ù </TD>
    <TD>Opt-~, u</TD>
    <TD title="Small u acute" align=middle bgColor=mistyrose>ú </TD>
    <TD>Opt-e, u</TD>
    <TD title="Small u circumflex" align=middle bgColor=mistyrose>û </TD>
    <TD>Opt-i, u</TD>
    <TD></TD>
    <TD></TD>
    <TD title="Small u umlaut" align=middle bgColor=mistyrose>ü </TD>
    <TD>Opt-u, u</TD>
    <TD></TD>
    <TD></TD>
    <TD></TD>
    <TD></TD></TR>
  <TR>
    <TD title="Capital U grave" align=middle bgColor=mistyrose>Ù </TD>
    <TD>Opt-~, U</TD>
    <TD title="Capital U acute" align=middle bgColor=mistyrose>Ú </TD>
    <TD>Opt-e, U</TD>
    <TD title="Capital U circumflex" align=middle bgColor=mistyrose>Û </TD>
    <TD>Opt-i, U</TD>
    <TD></TD>
    <TD></TD>
    <TD title="Capital U umlaut" align=middle bgColor=mistyrose>Ü </TD>
    <TD>Opt-u, U</TD>
    <TH bgColor=cornsilk colSpan=2>currency </TH>
    <TH bgColor=cornsilk colSpan=2>mathematics </TH></TR>
  <TR>
    <TD></TD>
    <TD></TD>
    <TD></TD>
    <TD></TD>
    <TD></TD>
    <TD></TD>
    <TD title="Small n tilde" align=middle bgColor=mistyrose>ñ </TD>
    <TD>Opt-n, n</TD>
    <TD title="Small y umlaut" align=middle bgColor=mistyrose>ÿ </TD>
    <TD>Opt-u, y</TD>
    <TD title=Cents align=middle bgColor=mistyrose>¢ </TD>
    <TD>Opt-4 </TD>
    <TD title=plus/minus align=middle bgColor=mistyrose>± </TD>
    <TD>Opt-+ </TD></TR>
  <TR>
    <TD></TD>
    <TD></TD>
    <TD></TD>
    <TD></TD>
    <TD></TD>
    <TD></TD>
    <TD title="Capital N tilde" align=middle bgColor=mistyrose>Ñ </TD>
    <TD>Opt-n, N</TD>
    <TD title="Capital Y umlaut" align=middle bgColor=mistyrose>Ÿ </TD>
    <TD>Opt-u, Y</TD>
    <TD title=Pounds align=middle bgColor=mistyrose>£ </TD>
    <TD>Opt-3 </TD>
    <TD title=Multiplication align=middle bgColor=mistyrose>× </TD>
    <TD>Opt-V </TD></TR>
  <TR>
    <TH bgColor=cornsilk colSpan=2>çedilla </TH>
    <TH bgColor=cornsilk colSpan=2>Icelandic </TH>
    <TH bgColor=cornsilk colSpan=2>marks </TH>
    <TH bgColor=cornsilk colSpan=2>accents </TH>
    <TH bgColor=cornsilk colSpan=2>punctuation </TH>
    <TD title=Yen align=middle bgColor=mistyrose>¥ </TD>
    <TD>Opt-y </TD>
    <TD title=Division align=middle bgColor=mistyrose>÷ </TD>
    <TD>Opt-/ </TD></TR>
  <TR>
    <TD title="Small c cedilla" align=middle bgColor=mistyrose>ç </TD>
    <TD>Opt-c </TD>
    <TD title="Capital Thorn" align=middle bgColor=mistyrose>Þ </TD>
    <TD>Shift-Opt-5</TD>
    <TD title=Copyright align=middle bgColor=mistyrose>© </TD>
    <TD>Opt-g </TD>
    <TD title="acute accent" align=middle bgColor=mistyrose>´ </TD>
    <TD>Opt-E </TD>
    <TD title="Inverted Question Mark" align=middle bgColor=mistyrose>¿ </TD>
    <TD>Opt-? </TD>
    <TD title=Dollars align=middle bgColor=mistyrose>$ </TD>
    <TD>(none)&nbsp;‡ </TD>
    <TD title="Logical Not" align=middle bgColor=mistyrose>¬ </TD>
    <TD>Opt-l </TD></TR>
  <TR>
    <TD title="Capital C cedilla" align=middle bgColor=mistyrose>Ç </TD>
    <TD>Opt-C </TD>
    <TD title="Small thorn" align=middle bgColor=mistyrose>þ </TD>
    <TD>Shift-Opt-6</TD>
    <TD title="Registration Mark" align=middle bgColor=mistyrose>® </TD>
    <TD>Opt-r </TD>
    <TD title="umlaut accent" align=middle bgColor=mistyrose>¨ </TD>
    <TD>Opt-U </TD>
    <TD title="Inverted Exclamation" align=middle bgColor=mistyrose>¡ </TD>
    <TD>Opt-1 </TD>
    <TD title="General Currency" align=middle bgColor=mistyrose>¤ </TD>
    <TD>Shift-Opt-2</TD>
    <TD title=Degrees align=middle bgColor=mistyrose>° </TD>
    <TD>Opt-* </TD></TR>
  <TR>
    <TH bgColor=cornsilk colSpan=2>superscripts </TH>
    <TD title="Capital Eth" align=middle bgColor=mistyrose>Ð </TD>
    <TD>(none)&nbsp;‡ </TD>
    <TD title=Trademark align=middle bgColor=mistyrose>™ </TD>
    <TD>Opt-2 </TD>
    <TD title="macron accent" align=middle bgColor=mistyrose>¯ </TD>
    <TD>Shift-Opt-,</TD>
    <TD title="guillemot left" align=middle bgColor=mistyrose>« </TD>
    <TD>Opt-\ </TD>
    <TD></TD>
    <TD></TD>
    <TD title=Micro align=middle bgColor=mistyrose>µ </TD>
    <TD>Opt-m </TD></TR>
  <TR>
    <TD title="superscript 1" align=middle bgColor=mistyrose>¹ </TD>
    <TD>(none)&nbsp;‡ </TD>
    <TD title="Small eth" align=middle bgColor=mistyrose>ð </TD>
    <TD>(none)&nbsp;‡ </TD>
    <TD title="Paragraph (pilcrow)" align=middle bgColor=mistyrose>¶ </TD>
    <TD>Opt-7 </TD>
    <TD title=cedilla align=middle bgColor=mistyrose>¸ </TD>
    <TD>Opt-Z </TD>
    <TD title="guillemot right" align=middle bgColor=mistyrose>» </TD>
    <TD>Shift-Opt-\</TD>
    <TH bgColor=cornsilk colSpan=2>ordinals </TH>
    <TD title="1/4 Fraction" align=middle bgColor=mistyrose>¼ </TD>
    <TD>(none)&nbsp;‡ <sup>1</sup></TD></TR>
  <TR>
    <TD title="superscript 2" align=middle bgColor=mistyrose>² </TD>
    <TD>(none)&nbsp;‡ </TD>
    <TH bgColor=cornsilk colSpan=2>sz ligature </TH>
    <TD title=Section align=middle bgColor=mistyrose>§ </TD>
    <TD>Opt-6 </TD>
    <TD></TD>
    <TD></TD>
    <TD title="Middle dot" align=middle bgColor=mistyrose>· </TD>
    <TD>Opt-8 </TD>
    <TD title="Masculine Ordinal" align=middle bgColor=mistyrose>º </TD>
    <TD>Opt-0 </TD>
    <TD title="1/2 Fraction" align=middle bgColor=mistyrose>½ </TD>
    <TD>(none)&nbsp;‡ <sup>1</sup></TD></TR>
  <TR>
    <TD title="superscript 3" align=middle bgColor=mistyrose>³ </TD>
    <TD>(none)&nbsp;‡ </TD>
    <TD title="sz ligature" align=middle bgColor=mistyrose>ß </TD>
    <TD>Opt-s </TD>
    <TD title="Broken Vertical bar" align=middle bgColor=mistyrose>¦ </TD>
    <TD>(none)&nbsp;‡ </TD>
    <TD></TD>
    <TD></TD>
    <TD title=asterisk align=middle bgColor=mistyrose>* </TD>
    <TD>(none)&nbsp;‡ </TD>
    <TD title="Feminine Ordinal" align=middle bgColor=mistyrose>ª </TD>
    <TD>Opt-9 </TD>
    <TD title="3/4 Fraction" align=middle bgColor=mistyrose>¾ </TD>
    <TD>(none)&nbsp;‡ <sup>1</sup></TD></TR></TBODY></TABLE>
<P>‡&nbsp;Note: Pas de raccourci clavier. Utilisez les menus. </P>
<p><sup>1</sup> A moins que les directives de projets l'ordonnent, n'utilisez pas
les caractères spéciaux de fraction, mais suivez les guidelines de <a href="#fract_s">
fractions</a>. Ce qui donne 1/2, 3/4, etc. </p>
<P>Notez le traitement spécial de la ligature oe. Par exemple, le 
  mot c&oelig;ur devient c[oe]ur. </p>

<H3><A name=char_diacr>Caractères avec marques diacritiques</A></H3>
<P>Sur certains projets, vous trouverez des caractères avec des signes spéciaux 
au-dessus ou au-dessous du caractère latin normal. Ce sont des <I>marques 
diacritiques</I>. Elles indiquent une prononciation spéciale. Nous les indiquons 
dans notre texte corrigé avec un codage spécifique. Par exemple, &#259; 
devient <TT>[)a]</TT> car la marque "brève" (l'accent en forme de u) est
sur la lettre, ou bien  <TT>[a)]</TT> pour une brève dessous. </P>
</P>
<P>Mettez bien des crochets (<TT>[&nbsp;]</TT>) autour, pour que le 
post-correcteur sache quel signe s'applique à quelle lettre. Le post-correcteur 
remplacera ces combinaisons de caractères par le caractère correct (en Unicode, HTML,
etc.). </P>
<P>N'utilisez pas ce système pour coder les caractères qui sont présents dans 
Latin-1. Utilisez alors directement ce caractère (voir <A 
href="#a_chars">ici</A>). <!-- END RR -->
<P>La table ci-dessous liste nos codes. Le "x" représente le caractère accentué. 
<BR>Quand vous corrigez un texte, utilisez le VRAI caractère, pas le x donné 
dans l'exemple. </P><!--
  diacritical mark           above  below
macron (straight line)       [=x]   [x=]
2 dots (diaresis or umlaut)  [:x]   [x:]
1 dot                        [.x]   [x.]
grave accent                 ['x]   [x'] or [/x] [x/]
acute (aigu) accent          [`x]   [x`] or [\x] [x\]
circumflex                   [^x]   [x^]
caron (v-shaped symbol)      [vx]   [xv]
breve (u-shaped symbol)      [)x]   [x)]
tilde                        [~x]   [x~]
cedilla                      [,x]   [x,]
-->
<TABLE rules=all align=center summary=Diacriticals border=6>
  <TBODY>
  <TR bgColor=cornsilk>
    <TH colSpan=4>Symboles avec marques diacritiques</TH>
  <TR bgColor=cornsilk>
    <TH>diacritical mark</TH>
    <TH>sample</TH>
    <TH>above</TH>
    <TH>below</TH></TR>
  <TR>
    <TD>macron (straight line)</TD>
    <TD align=middle>¯</TD>
    <TD align=middle><TT>[=x]</TT></TD>
    <TD align=middle><TT>[x=]</TT></TD></TR>
  <TR>
    <TD>2 dots (diaresis, umlaut)</TD>
    <TD align=middle>¨</TD>
    <TD align=middle><TT>[:x]</TT></TD>
    <TD align=middle><TT>[x:]</TT></TD></TR>
  <TR>
    <TD>1 dot</TD>
    <TD align=middle>·</TD>
    <TD align=middle><TT>[.x]</TT></TD>
    <TD align=middle><TT>[x.]</TT></TD></TR>
  <TR>
    <TD>grave accent</TD>
    <TD align=middle>`</TD>
    <TD align=middle><TT>[`x]</TT> or <TT>[\x]</TT></TD>
    <TD align=middle><TT>[x`]</TT> or <TT>[x\]</TT></TD></TR>
  <TR>
    <TD>acute accent (aigu)</TD>
    <TD align=middle>´</TD>
    <TD align=middle><TT>['x]</TT> or <TT>[/x]</TT></TD>
    <TD align=middle><TT>[x']</TT> or <TT>[x/]</TT></TD></TR>
  <TR>
    <TD>circumflex</TD>
    <TD align=middle>ˆ</TD>
    <TD align=middle><TT>[^x]</TT></TD>
    <TD align=middle><TT>[x^]</TT></TD></TR>
  <TR>
    <TD>caron (v-shaped symbol)</TD>
    <TD align=middle><FONT size=-2>&#8744;</FONT></TD>
    <TD align=middle><TT>[vx]</TT></TD>
    <TD align=middle><TT>[xv]</TT></TD></TR>
  <TR>
    <TD>breve (u-shaped symbol)</TD>
    <TD align=middle><FONT size=-2>&#8746;</FONT></TD>
    <TD align=middle><TT>[)x]</TT></TD>
    <TD align=middle><TT>[x)]</TT></TD></TR>
  <TR>
    <TD>tilde</TD>
    <TD align=middle>˜</TD>
    <TD align=middle><TT>[~x]</TT></TD>
    <TD align=middle><TT>[x~]</TT></TD></TR>
  <TR>
    <TD>cedilla</TD>
    <TD align=middle>¸</TD>
    <TD align=middle><TT>[,x]</TT></TD>
    <TD align=middle><TT>[x,]</TT></TD></TR></TBODY></TABLE>
<H3><A name=f_chars>Alphabets non latins</A> </H3>
<P>Certains textes utilisent des caractères non latins (autrement dit, autres 
que A..Z). Grecs, Hébreux, Cyrilliques, ou Arabes. </P>
<P>Pour le grec, faites une translittération. Autrement dit, traduisez chaque 
caractère grec en son équivalent latin. Le grec apparaît tellement souvent dans 
nos textes que nous avons inclus dans les interface un outil de 
translittération pour vous faciliter la tâche. </P>
<P>Appuyez sur le bouton "Greek" en bas de l'écran pour faire apparaître 
l'outil. Dans l'outil, cliquez sur les caractères qui correspondent aux 
caractères grecs que vous voyez dans le texte original, et un caractère latin 
apparaîtra dans la zone de texte. A la fin, vous pouvez copier-coller le contenu 
de la zone de texte vers votre page de travail. Entourez le texte obtenu par les 
marques <TT>[Greek:&nbsp;</TT> et <TT>]</TT>. Par exemple, <B>&#914;&#953;&#946;&#955;&#959;&#962;</B> devient 
<TT>[Greek: Biblos]</TT>. ("livre", vous êtes bien chez DP!)</P>
<P>Si vous n'êtes pas sûr de votre translittération, ajoutez une étoile, pour 
attirer l'attention du correcteur suivant, ou du post-correcteur.</P>
<P>Les autres langues ne se traitent pas aussi facilement. Ajoutez les marques
<TT>[Cyrillic:&nbsp;**]</TT>, <TT>[Hebrew:&nbsp;**]</TT>, ou 
<TT>[Arabic:&nbsp;**]</TT>. Et laissez le texte tel qu'il a été scanné. Ajoutez 
bien les deux étoiles, pour attirer l'attention du post-correcteur.</P><!-- END RR -->
<UL compact>
  <LI>Grec: <A href="http://gutenberg.net/howto/greek/">Table de conversion Grec 
  vers ASCII</A> (du Project Gutenberg) (ou utilisez l'outil).&nbsp; 
  <LI>Cyrillique: <A 
  href="http://www.history.uiuc.edu/steinb/translit/translit.htm">Table de 
  conversion Cyrilique</A> N'essayez de corriger du texte en cyrillique que si 
  vous maîtrisez bien les langues concernées. Sinon, marquez le texte comme 
  indiqué ci-dessus. Vous pouvez aussi utiliser
  <A href="http://learningrussian.com/transliteration.htm"> cette table
de translittération</A>.
  <LI>Hébreu, Arabe: Non recommandé à moins que vous lisiez ces langues 
  couramment. Il existe des difficultés importantes dans la conversion de ces 
  langues à l'ASCII et ni <A href="http://texts01.archive.org/dp/">Distributed 
  Proofreaders</A> ni le <A href="http://www.gutenberg.net/">Project 
  Gutenberg</A> n'ont encore choisi de méthode standard. </LI></UL>
<H3><A name=fract_s>Fractions</A> </H3>
<P>Convertissez les <B>fractions</B> de cette manière: <TT>2½</TT> devient 
<TT>2-1/2</TT>. Le tiret empêche les deux parties d'être séparées par une retour 
à la ligne au cours du réassemblage des lignes. </P>
<H3><A name=em_dashes>Tirets, traits d'unions, et signe “moins”</A></H3>
<P>Vous verrez quatre types de traits dans les livres.</P>
<OL compact>
  <LI>Les tirets "<I>hyphens</I>". Ils sont utilisés pour <B>joindre</B> les 
  mots, ou parfois pour joindre les préfixes ou les suffixes à un mot. Dans 
  votre texte corrigé, laissez un seul tiret, sans espace ni à droite ni à 
  gauche. <br>Notez toutefois qu'il y a une exception. Voir le deuxième
  exemple ci-dessous. </li>
  <LI>Les tirets longs. "<I>En-dashes</I>". Ils sont un peu plus longs, ils sont 
  utilisés pour des <B>intervalles</B> de nombres, ou pour le signe mathématique 
  "moins". Là aussi, laissez un seul tiret. Laissez un espace avant ou après 
  selon la façon dont c'est imprimé sur le livre. En général, pas d'espace pour 
  les intervalles de nombres, mais, autour du signe "moins", il y en a parfois 
  des deux côtés, parfois seulement avant. </LI>
  <LI>Les tirets <I>Em-dashes &amp; long dashes. </I>Ils servent de 
  <B>séparateurs</B> entre les mots—parfois pour mettre l'accent, comme ceci—ou 
  quand une personne prend la parole, ou s'interrompt dans un dialogue. 
  Notez-les comme deux tirets. Sans espace ni avant ni après, même s'il semble y 
  en avoir un sur le document imprimé. </LI>
  <LI>Les traits qui représentent des mots (ou des noms) <B>omis</B> 
  ou <B>censurés</B>. Notez-les comme quatre tirets. Si le long trait représente 
  un mot, laissez des espaces autour des tirets, comme si c'était vraiment le 
  mot. Si c'est seulement une partie de mot, alors pas d'espaces. Joignez-le au 
  reste du mot.</LI></OL>
<P>Note. Si un tiret em-dash apparaît au début (ou à la fin) de votre ligne dans 
votre texte, joignez-le à l'autre ligne pour qu'il n'y ait pas d'espace autour 
du tiret. C'est seulement si l'auteur a utilisé un "dash" au début ou à la fin 
d'un paragraphe, ou sur une ligne de poésie, ou un dialogue que vous devez le 
laissez au début ou à la fin de la ligne. Voir les exemples
cit-dessous</P><!-- END RR -->
<P>Quelques exemples.&nbsp;</P>
<TABLE cellSpacing=0 cellPadding=4 width="100%" align=center 
summary="Hyphens and Dashes" border=1>
  <TBODY>
  <TR>
    <TH vAlign=top bgColor=cornsilk>Image de départ:</TH>
    <TH vAlign=top bgColor=cornsilk>Texte correctement corrigé:</TH>
    <TH vAlign=top bgColor=cornsilk>Type</TH></TR>
<tr>
      <td valign="top">semi-detached</td>
      <td valign="top"><tt>semi-detached</tt></td>
      <td> Hyphen</td>
    </tr>
    <tr>
      <td valign="top">three- and four-part harmony</td>
      <td valign="top"><tt>three- and four-part harmony</tt></td>
      <td> Hyphens</td>
    </tr>
    <tr>
      <td valign="top">discoveries which the Crus-<br>
        aders made and brought home with</td>
      <td valign="top"><tt>discoveries which the Crusaders<br>
        made and brought home with</tt></td>
      <td> Hyphen</td>
    </tr>
    <tr>
      <td valign="top">factors which mold char-<br>
        acter&mdash;environment, training and heritage,</td>
      <td valign="top"><tt>factors which mold character--environment,<br>
        training and heritage,</tt>
      <td> Hyphen</td>
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
      <td>Longer Em-dash</td>
    </tr>
    <tr>
      <td valign="top">As the witness Mr. &mdash;&mdash; testified,</td>
      <td valign="top"><tt>As the witness Mr. ---- testified,</tt></td>
      <td>long dash</td>
    </tr>
    <tr>
      <td valign="top">As the witness Mr. S&mdash;&mdash; testified,</td>
      <td valign="top"><tt>As the witness Mr. S---- testified,</tt></td>
      <td>long dash</td>
    </tr>
    <tr>
      <td valign="top">the famous detective of &mdash;&mdash;B Baker St.</td>
      <td valign="top"><tt>the famous detective of ----B Baker St.</tt></td>
      <td>long dash</td>
    </tr>
    <tr>
      <td valign="top">&ldquo;You &mdash;&mdash; Yankee&rdquo;, she yelled.</td>
      <td valign="top"><tt>"You ---- Yankee", she yelled.</tt></td>
      <td>long dash</td>
    </tr>
    <tr>
      <td valign="top">&ldquo;I am not a d&mdash;d Yankee&rdquo;, he replied.</td>
      <td valign="top"><tt>"I am not a d--d Yankee", he replied.</tt></td>
      <td>Em-dash</td></tr>
</TBODY></TABLE>
<H3><A name=eol_hyphen>Traits d'union en fin de ligne</A> </H3>
<P>Enlevez le trait d'union en fin de ligne et collez les deux morceaux du mots 
qui était coupé. A moins que ce ne soit réellement un mot avec tiret tel que 
porte-manteau. Mais si le mot était coupé parce que la ligne est trop courte, et 
non pas parce qu'il prend généralement un trait d'union, alors rejoignez les 
deux parties. Laissez le mot sur la ligne supérieure et insérez un retour à la 
ligne après ce mot pour conserver le formatage des lignes: cela rend la tâche 
plus facile au correcteur du second tour. Voyez à <A 
href="#em_dashes">Tirets, 
traits d'unions, et signe “moins”</A> pour des exemples de chaque type 
(<TT>nar-row</TT> est transformé en <TT>narrow</TT>, mais <TT>low-lying</TT> 
garde le tiret). Si le mot coupé est suivi d'un signe de ponctuation, mettez ce 
signe sur la première ligne aussi.</P>
<P>Laissez le trait d'union aux mots qui s'écrivaient anciennement avec un trait 
d'union mais qui n'en ont plus aujourd'hui. Si vous n'êtes pas sûr de savoir si 
l'auteur a mis un tiret ou non, laissez le tiret, mettez un * après, et 
rejoignez les deux parties du mot. Comme ceci : <TT>to-*day</TT>. L'astérisque 
attirera l'attention du post-correcteur, qui a accès à toutes les pages et qui 
verra comment l'auteur écrit habituellement le mot.</P>
<H3><A name=eop_hyphen>Traits d'union en fin de page</A> </H3>
<P>Laissez le trait d'union à la fin de la dernière ligne, mais marquez le avec 
un astérisque ( <TT>*</TT>) après le trait d'union de manière à permettre au 
post-correcteur de le remarquer plus facilement. <BR>Par exemple, 
corrigez:<BR>&nbsp;<BR>&nbsp; &nbsp; &nbsp; &nbsp;something Pat had already 
become accus-<BR>par:<BR>&nbsp; &nbsp; &nbsp; &nbsp;<TT>something Pat had 
already become accus-*</TT> </P>
<P>Pour les pages qui commencent avec un mot commencé à la fin de la page 
précédente, placez un <TT>*</TT> avant le mot.<BR>Pour continuer avec l'exemple 
ci-dessus, corrigez:<BR>&nbsp;<BR>&nbsp; &nbsp; &nbsp; &nbsp;tomed to from 
having to do his own family<BR>en:<BR>&nbsp; &nbsp; &nbsp; &nbsp;<TT>*tomed to 
from having to do his own family</TT> </P>
<P>Ces signes indiquent au post-correcteur, quand il produit le texte final, 
qu'il doit rejoindre les deux parties du mot. </P>

<H3><A name=para_space>Espacement/Indentation des paragraphes</A> </H3>
<P>Les paragraphes doivent être séparés par une ligne blanche. 
N'indentez pas le début des paragraphes.
(Mais si tous les paragraphes sont déjà indentés, ne prenez pas la peine 
d'enlever les espaces en trop --cela peut être fait facilement à la phase de 
post-correction).</P>
<P>Voyez l'image et le texte de la section <A 
href="#para_side">Notes en marge</A>
pour avoir un exemple. </P>
<H3><A name=mult_col>Colonnes Multiples</A> </H3>
<P>Réunissez les colonnes multiples en une seule colonne. Placez la colonne la 
plus à gauche en premier puis les autres colonnes à sa suite. Vous ne devez rien 
faire de particulier pour marquer la séparation des colonnes, mettez-les juste 
ensemble. </P>

<P>Voir aussi <A 
href="#bk_index">Index</A>
et <A 
href="#tables">Tables</A>.</P>
<H3><A name=blank_pg>Page blanche</A> </H3>
<P>Si la page est blanche ou si elle ne contient qu'une illustration sans
texte, vous verrez
le plus souvent <TT>[Blank page]</TT> sur votre page de travail. Laissez cette marque.
Si la page est blanche et que la marque n'apparaît pas, ce n'est pas la peine de
l'ajouter.</P>
<P>Si le texte seulement (ou l'image seulement) est vide, suivez la 
procédure indiquées dans le cas d'une <A 
href="#bad_image">Mauvaise 
Image</A> ou d'un <A 
href="#bad_text">Mauvais 
Texte</A>.</P>

<H3><A name=page_hf>Entêtes et bas de page</A> </H3>
<P>Enlevez les entêtes et bas de page (mais pas les <A 
href="#footnotes">notes de 
bas de page</A>) du texte.</P>
<P>Ces entêtes sont généralement sur la partie supérieure de l'image et ont un 
numéro de page à leur opposé. Les entêtes peuvent être les mêmes au cours du 
livre (souvent le titre du livre et le nom de l'auteur); ils peuvent être 
identiques pour chaque chapitre (souvent le numéro du chapitre); ou ils peuvent 
être différents pour chaque page (décrivant l'action sur cette page). 
Supprimez-les tous, quels qu'ils soient, en particulier le numéro de page.</P><!-- END RR -->
<P>Un <A 
href="#chap_head">entête de 
chapitre </A>commence plus bas sur la page et n'a pas de numéro de page sur la 
même ligne. Laissez les entêtes de chapitres en place -- voir exemple plus 
bas.</P><BR>
      <TABLE cellSpacing=0 cellPadding=4 width="100%" align=center 
      summary="Page Headers and Footers" border=1>
        <TBODY>
        <TR>
          <TH align=left bgColor=cornsilk>Image exemple:</TH></TR>
        <TR align=left>
          <TD vAlign=top width="100%"><IMG height=850 alt="" 
            src="http://www.pgdp.net/c/faq/foot.png" 
          width=500><BR></TD></TR>
        <TR>
          <TH align=left bgColor=cornsilk>Texte correct:</TH></TR>
        <TR>
          <TD vAlign=top width="100%">
            <TABLE align=left summary="" border=0>
              <TBODY>
              <TR>
                <TD><TT>In the United States?[*] In a railroad? In a mining 
                  company?<BR>In a bank? In a church? In a college?<BR><BR>Write 
                  a list of all the corporations that you know or have<BR>ever 
                  heard of, grouping them under the heads public and 
                  private.<BR><BR>How could a pastor collect his salary if the 
                  church should<BR>refuse to pay it?<BR><BR>Could a bank buy a 
                  piece of ground "on speculation?" To<BR>build its 
                  banking-house on? Could a county lend money if it<BR>had a 
                  surplus? State the general powers of a corporation.<BR>Some of 
                  the special powers of a bank. Of a city.<BR><BR>A portion of a 
                  man's farm is taken for a highway, and he is<BR>paid damages; 
                  to whom does said land belong? The road intersects<BR>the 
                  farm, and crossing the road is a brook containing<BR>trout, 
                  which have been put there and cared for by the farmer;<BR>may 
                  a boy sit on the public bridge and catch trout from 
                  that<BR>brook? If the road should be abandoned or lifted, to 
                  whom<BR>would the use of the land go?<BR><BR><BR>CHAPTER 
                  XXXV.<BR><BR>Commercial Paper.<BR><BR>Kinds and Uses.--If a 
                  man wishes to buy some commodity<BR>from another but has not 
                  the money to pay for<BR>it, he may secure what he wants by 
                  giving his written<BR>promise to pay at some future time. This 
                  written<BR>promise, or note, the seller prefers to an oral 
                  promise<BR>for several reasons, only two of which need be 
                  mentioned<BR>here: first, because it is prima facie evidence 
                  of<BR>the debt; and, second, because it may be more 
                  easily<BR>transferred or handed over to some one 
                  else.<BR><BR>If J. M. Johnson, of Saint Paul, owes C. M. 
                  Jones,<BR>of Chicago, a hundred dollars, and Nelson Blake, 
                  of<BR>Chicago, owes J. M. Johnson a hundred dollars, it 
                  is<BR>plain that the risk, expense, time and trouble of 
                  sending<BR>the money to and from Chicago may be 
                  avoided,<BR><BR>* The United States: "Its charter, the 
                  constitution. * * * Its flag the<BR>symbol of its power; its 
                  seal, of its authority."--Dole. 
        </TT></TD></TR></TBODY></TABLE></TD></TR></TBODY></TABLE>
<H3><A name=chap_head>Entêtes de chapitres</A> </H3>
<P>Laissez les entêtes de chapitres dans le texte tels qu'ils sont imprimés.</P>
<P>Un entête de chapitre commence plus bas sur la page qu'un <A 
href="#page_hf">entête de 
page</A> et n'a pas de numéro de page sur la même ligne. Les entêtes de 
chapitres sont souvent imprimées entièrement en majuscules, si c'est le cas, 
laissez-les tels quels.</P>

<P>Faites attention à un double guillemet ( " ) au début du premier paragraphe,
que certains éditeurs n'incluaient pas ou que les OCR ignorent à cause de la 
grande majuscule dans l'original. Si l'auteur commence le paragraphe avec un 
dialogue, insérez le double guillemet. </P><!-- END RR -->

<H3><A name=illust>Illustrations</A> </H3>
<P>Gardez le titre (texte) de l'illustration comme il est imprimé, avec 
ses retours à la ligne, italiques, etc. S'il n'y a pas de texte, alors laissez
le travail de marquage de l'illustration aux formateurs. </P>
<p> Une page ne comportant qu'une illustration sans texte sera probablement
marquée comme <tt>[Blank Page]</tt>. N'y changez rien.</p>
<!-- END RR -->
      <TABLE cellSpacing=0 cellPadding=4 width="100%" align=center 
      summary=Illustration border=1>
        <TBODY>
        <TR>
          <TH align=left bgColor=cornsilk>Image exemple: </TH></TR>
        <TR align=left>
          <TD vAlign=top width="100%"><IMG height=525 alt="" 
            src="http://www.pgdp.net/c/faq/illust.png" width=500> 
            <BR></TD></TR>
        <TR>
          <TH align=left bgColor=cornsilk>Texte correct:</TH></TR>
        <TR>
          <TD vAlign=top width="100%">
            <TABLE align=left summary="" border=0>
              <TBODY>
              <TR>
                <TD>
                  <P><TT>Martha told him that he had always been her ideal 
                  and<BR>that she worshipped him.<BR><BR>Frontispiece<BR>Her 
                  Weight in Gold 
      </TT></P></TD></TR></TBODY></TABLE></TD></TR></TBODY></TABLE><BR>
      <TABLE cellSpacing=0 cellPadding=4 width="100%" align=center 
      summary="Illustration in Middle of Paragraph" border=1>
        <TBODY>
        <TR>
          <TH align=left bgColor=cornsilk>Image (illustration au milieu 
          d'un paragraphe)</TH></TR>
        <TR align=left>
          <TD vAlign=top width="100%"><IMG height=514 alt="" 
            src="http://www.pgdp.net/c/faq/illust2.png" width=500> 
            <BR></TD></TR>
        <TR>
          <TH align=left bgColor=cornsilk>Texte correct:</TH></TR>
        <TR vAlign=top>
          <TD>
            <TABLE align=left summary="" border=0>
              <TBODY>
              <TR>
                <TD>
                  <P><TT>such study are due to Italians. Several of these 
                  instruments<BR>have already been described in this journal, 
                  and on the present<BR>
                  <P><TT>FIG. 1.--APPARATUS FOR THE STUDY OF 
                  HORIZONTAL<BR>SEISMIC MOVEMENTS.</TT></P>
                  <P><TT>occasion we shall make known a few others that 
                  will<BR>serve to give an idea of the methods
                  employed.<BR></TT></P>
                  <P><TT>For the observation of the vertical and horizontal 
                  motions<BR>of the ground, different apparatus are required. 
                  The</TT> </P></TT></TD></TR></TBODY></TABLE></TD></TR></TBODY></TABLE>
                  

<H3><A name=footnotes>Notes de bas de page et de fin</A> </H3>
<P>Les notes de bas de page sont "hors ligne", autrement dit, le texte de la 
note est en bas de la page et une marque est placée dans le texte, là où elle 
est référencée. </P>
<P>Le numéro ou la lettre qui indique la référence, dans le texte, sera encadré par des
crochets ([ et ]) et sera placé juste à côté du mot sur lequel porte la note<TT>[1]</TT>,
ou le signe
de ponctuation,<TT>[2]</TT> comme nous l'avons fait dans cette phrase, voir aussi
l'exemple ci-dessous. 
<P>Parfois, les notes sont marquées par des séries de caractères spéciaux (*, †, 
‡, §, etc.) Remplacez tous ces signes par la marque [*], dans le corps
du texte, et * dans la note elle-même. </P>
<P>Laissez le texte de la note tel qu'il est 
imprimé, avec ses retours à la ligne, italiques, etc. Laissez la note en bas de 
la page. Utilisez bien la même marque de note dans la note et dans le texte (là 
où la note est référencée). </P>
<P>Séparez les notes d'une même page par une ligne blanche.</P>
<!-- END RR -->

<P>Pour avoir un exemple de note de bas de page, voyez l'exemple de la section 
<A href="#page_hf">Entêtes 
et bas de page</A> . </P>

<P>Si une note est référencée dans le texte mais n'apparaît pas sur cette page, 
laissez la marque de note entourée de crochets, comme d'habitude. Ce cas est 
courant dans les livres scientifiques et techniques, où les notes sont souvent 
groupées en fin de chapitre. Voir les Notes de fin, ci-dessous. </P>

      <TABLE cellSpacing=0 cellPadding=4 width="100%" align=center 
      summary="Exemple de note" border=1>
        <TBODY>
        <TR>
          <TH vAlign=top align=left bgColor=cornsilk>Texte d'origine:</TH></TR>
        <TR>
          <TD vAlign=top>
            <TABLE align=left summary="" border=0>
              <TBODY>
              <TR>
                <TD>The principal persons involved in this argument were 
                  Caesar<SUP>1</SUP>, former military<BR>leader and Imperator, 
                  and the orator Cicero<SUP>2</SUP>. Both were of the 
                  aristocratic<BR>(Patrician) class, and were quite wealthy.<BR>
                  <HR align=left width="50%" noShade SIZE=2>
                  <FONT size=-1><SUP>1</SUP> Gaius Julius 
                  Caesar.</FONT><BR><FONT size=-1><SUP>2</SUP> Marcus Tullius 
                  Cicero.</FONT> </TD></TR></TBODY></TABLE></TD></TR>
        <TR>
          <TH vAlign=top align=left bgColor=cornsilk>Texte
        Corrigé:</TH></TR>
        <TR vAlign=top>
          <TD>
            <TABLE align=left summary="" border=0>
              <TBODY>
              <TR>
                <TD><TT>The principal persons involved in this argument were 
                  Caesar[1], former military</TT><BR><TT>leader and Imperator, 
                  and the orator Cicero[2]. Both were of the 
                  aristocratic</TT><BR><TT>(Patrician) class, and were quite 
                  wealthy.</TT><BR><BR><TT>1 Gaius Julius 
                  Caesar.</TT><BR><BR><TT>2 Marcus Tullius Cicero.</TT> 
              </TD></TR></TBODY></TABLE></TD></TR></TBODY></TABLE>


<P>Pour certains livres, les notes de bas de page sont séparées du texte 
principal par une ligne horizontale. Nous ne le ferons pas; laissez donc juste 
une ligne blanche entre le texte et la note (voir exemple ci-dessus).</P>
<P>Les <B>notes de fin</B> sont simplement des notes de bas de page qui ont été 
placées en fin de chapitre, ou en fin de livre, au lieu d'être en fin de page. 
Traitez-les comme des notes de bas de page. Quand vous voyez la référence dans le 
texte, entourez-la par des crochets. Si vous corrigez une des pages de fin, là 
où sont les notes, mettez une ligne blanche après chaque note, pour qu'elles
soient clairement séparées.</P>
<P>Les notes sur de la <b>poésie</b> sont traitées comme des notes ordinaires.</P>
<P>Les notes sur des <b>tables</b> doivent rester où elles sont dans le texte original.</P>
      <TABLE cellSpacing=0 cellPadding=4 width="100%" align=center 
      summary=Footnotes border=1>
        <TBODY>
        <TR>
          <TH align=left bgColor=cornsilk>Poésie avec note, texte original</TH></TR>
        <TR>
          <TD vAlign=top>
            <TABLE align=left summary="" border=0>
              <TBODY>
              <TR>
                <TD>Mary had a little 
                  lamb<SUP>1</SUP><BR>&nbsp;&nbsp;&nbsp;Whose fleece was white 
                  as snow<BR>And everywhere that Mary 
                  went<BR>&nbsp;&nbsp;&nbsp;The lamb was sure to go!<BR>
                  <HR align=left width="50%" noShade SIZE=2>
                  <FONT size=-2><SUP>1</SUP> This lamb was obviously of the 
                  Hampshire breed,<BR>well known for the pure whiteness of their 
                  wool.</FONT> </TD></TR></TBODY></TABLE></TD></TR>
        <TR>
          <TH align=left bgColor=cornsilk>Texte correct:</TH></TR>
        <TR>
          <TD vAlign=top>
            <TABLE align=left summary="" border=0>
              <TBODY>
              <TR>
                <TD><TT>Mary had a little lamb[1]<BR>Whose fleece was white as 
                  snow<BR>And everywhere that Mary went<BR>The lamb was sure to 
                  go!<BR><BR>1 This lamb was obviously of the Hampshire 
                  breed,<BR>well known for the pure whiteness of their 
                  wool.<BR></TT></TD></TR></TBODY></TABLE></TD></TR></TBODY></TABLE>

<H3><A name=poetry>Poésie/Épigrammes</A> </H3>

<P>Mettez une ligne blanche avant un poème, et une ligne blanche après. Comme ça,
le formateurs verront quand le poème commence et finit.</P>
<P>Calez tous les vers à gauche, et gardez les retours à la ligne. N'essayez pas
de centrer ou d'indenter les vers, laissez cela aux formateurs. Mais insérez
une ligne vide entre les strophes (ou couplets).</P>

<P>Les notes de bas de page dans la poésie se traitent comme les autres notes de 
bas de page. Voyez <A 
href="#footnotes">notes de 
bas de page</A> pour plus de details.</P>
<P>Gardez les numéros de vers s'ils sont imprimés. Mettez-les à la fin de la 
ligne, et séparez-les du texte par quleques blancs. Voir <A 
href="#line_no">Numéros de 
ligne </A>pour des détails. </P>
<P>Regardez les <A 
href="#comments">commentaires 
de projet </A>pour des instructions spécifiques. </P><!-- END RR -->
      <TABLE cellSpacing=0 cellPadding=4 width="100%" align=center 
      summary="Poetry Example" border=1>
        <TBODY>
        <TR>
          <TH align=left bgColor=cornsilk>Image:</TH></TR>
        <TR align=left>
          <TH vAlign=top width="100%"><IMG height=508 alt="" 
            src="http://www.pgdp.net/c/faq/poetry.png" width=500> 
            <BR></TH></TR>
        <TR>
          <TH align=left bgColor=cornsilk>Texte correct:</TH></TR>
        <TR>
          <TD vAlign=top width="100%">
            <TABLE align=left summary="" border=0>
              <TBODY>
              <TR>
                <TD><TT>to the scenery of his own country:<BR></TT>
                  <P><TT>Oh, to be in England<BR>Now that April's there,<BR>And 
                  whoever wakes in England<BR>Sees, some morning, 
                  unaware,<BR>That the lowest boughs and the brushwood 
                  sheaf<BR>Round the elm-tree hole are in tiny leaf,<BR>While 
                  the chaffinch sings on the orchard bough<BR>In 
                  England--now!</TT> </P>
                  <P><TT>And after April, when May follows,<BR>And the 
                  whitethroat builds, and all the swallows!<BR>Hark! where my 
                  blossomed pear-tree in the hedge<BR>Leans to the field and 
                  scatters on the clover<BR>Blossoms and dewdrops--at the bent 
                  spray's edge--<BR>That's the wise thrush; he sings each song 
                  twice over,<BR>Lest you should think he never could 
                  recapture<BR>The first fine careless rapture!<BR>And though 
                  the fields look rough with hoary dew,<BR>All will be gay, when 
                  noontide wakes anew<BR>The buttercups, the little children's 
                  dower;<BR>--Far brighter than this gaudy 
                  melon-flower!<BR></TT></P>
                  <P><TT>So it runs; but it is only a momentary memory;<BR>and 
                  he knew, when he had done it, and to his</TT> 
              </P></TD></TR></TBODY></TABLE></TD></TR></TBODY></TABLE>

<H3><A name=para_side>Commentaires de paragraphes</A> </H3>
<P>Certains livre ont de petites descriptions des paragraphes sur le côté du 
texte. Ce sont les "Sidenotes". Laissez une ligne blanche avant la note,
et une autre après. Corrigez la note 
pour qu'elle ressemble au texte imprimé, en gardant les retours à la ligne, les 
italiques, etc. Le texte venant de l'OCR placera le texte des notes n'importe où sur 
la page, ou même mélangera les lignes des notes avec les lignes du texte. Rassemblez
le texte de la note pour que toutes les lignes soient ensemble. Ensuite, mettez
la note où vous voulez sur la page. Les formateurs s'occuperont de la placer
correctement. </P><!-- END RR -->
      <TABLE cellSpacing=0 cellPadding=4 width="100%" align=center 
      summary=Sidenotes border=1>
        <COLGROUP>
        <COL width=128>
        <TBODY>
        <TR vAlign=top>
          <TH align=left bgColor=cornsilk>Image originale:</TH></TR>
        <TR vAlign=top>
          <TD align=left width="100%"><IMG height=800 alt="" 
            src="http://www.pgdp.net/c/faq/side.png" 
          width=550><BR></TD></TR>
        <TR vAlign=top>
          <TH align=left bgColor=cornsilk>Texte correct:</TH></TR>
        <TR vAlign=top>
          <TD width="100%">
            <TABLE align=left summary="" border=0>
              <TBODY>
              <TR>
                <TD>
                  <P><TT>Burning<BR>discs<BR>thrown into<BR>the air.<BR><BR>that 
                  such as looked at the fire holding a bit of larkspur<BR>before 
                  their face would be troubled by no malady of the<BR>eyes 
                  throughout the year.[1] Further, it was customary 
                  at<BR>Würzburg, in the sixteenth century, for the bishop's 
                  followers<BR>to throw burning discs of wood into the air from 
                  a mountain<BR>which overhangs the town. The discs were 
                  discharged by<BR>means of flexible rods, and in their flight 
                  through the darkness<BR>presented the appearance of fiery 
                  dragons.[2]<BR><BR>The Midsummer<BR>fires 
                  in<BR>Swabia.<BR><BR>In the valley of the Lech, which divides 
                  Upper Bavaria<BR>from Swabia, the midsummer customs and 
                  beliefs are, or<BR>used to be, very similar. Bonfires are 
                  kindled on the<BR>mountains on Midsummer Day; and besides the 
                  bonfire<BR>a tall beam, thickly wrapt in straw and surmounted 
                  by a<BR>cross-piece, is burned in many places. Round this 
                  cross as<BR>it burns the lads dance with loud shouts; and when 
                  the<BR>flames have subsided, the young people leap over the 
                  fire in<BR>pairs, a young man and a young woman together. If 
                  they<BR>escape unsmirched, the man will not suffer from fever, 
                  and<BR>the girl will not become a mother within the year. 
                  Further,<BR>it is believed that the flax will grow that year 
                  as high as<BR>they leap over the fire; and that if a charred 
                  billet be taken<BR>from the fire and stuck in a flax-field it 
                  will promote the<BR>growth of the flax.[3] Similarly in 
                  Swabia, lads and lasses,<BR>hand in hand, leap over the
                  midsummer bonfire, praying<BR>that the hemp may grow three 
                  ells high, and they set fire<BR>to wheels of straw and send 
                  them rolling down the hill.<BR>Among the places where burning 
                  wheels were thus bowled<BR>down hill at Midsummer were the 
                  Hohenstaufen mountains<BR>in Wurtemberg and the Frauenberg 
                  near Gerhausen.[4]<BR>At Deffingen, in Swabia, as the people 
                  sprang over the mid-*<BR><BR>Omens<BR>drawn from<BR>the 
                  leaps<BR>over 
                  the<BR>fires.<BR><BR>Burning<BR>wheels<BR>rolled<BR>down 
                  hill.<BR><BR>1 Op. cit. iv. 1. p. 242. We have<BR>seen (p. 
                  163) that in the sixteenth<BR>century these customs and 
                  beliefs were<BR>common in Germany. It is also a<BR>German 
                  superstition that a house which<BR>contains a brand from the 
                  midsummer<BR>bonfire will not be struck by lightning<BR>(J. W. 
                  Wolf, Beiträge zur deutschen<BR>Mythologie, i. p. 217, § 
                  185).<BR><BR>2 J. Boemus, Mores, leges et ritus<BR>omnium 
                  gentium (Lyons, 1541), p.<BR>226.<BR><BR>3 Karl Freiherr von 
                  Leoprechting,<BR>Aus dem Lechrain (Munich, 1855),<BR>pp. 181 
                  sqq. W. Mannhardt, Der<BR>Baumkultus, p. 510.<BR><BR>4 A. 
                  Birlinger, Volksthümliches aus<BR>Schwaben (Freiburg im 
                  Breisgau, 1861-1862),<BR>ii. pp. 96 sqq., § 128, pp. 
                  103<BR>sq., § 129; id., Aus Schwaben (Wiesbaden,<BR>1874), ii. 
                  116-120; E. Meier,<BR>Deutsche Sagen, Sitten und 
                  Gebräuche<BR>aus Schwaben (Stuttgart, 1852), pp.<BR>423 sqq.; 
                  W. Mannhardt, Der Baumkultus,<BR>p. 
              510.<BR></TT></P></TD></TR></TBODY></TABLE></TD></TR></TBODY></TABLE>
<H3><A name=tables>Tableaux</A> </H3>
<P>Le travail du relecteur consiste à corriger les erreurs dans le texte,
pas à soigner sa présentation. Le formatage sera géré plus tard. 
Laissez simplement des espaces entre les champs des tables pour qu'on voit où ils finissent,
et gardez les retours à la ligne. </P>
<P>Les <b>notes</b> restent où elles sont. Voir paragraphe sur les notes de bas de page.</P>
<!-- END RR -->
      <TABLE cellSpacing=0 cellPadding=4 width="100%" align=center 
      summary="Table Example 1" border=1>
        <TBODY>
        <TR>
          <TH align=left bgColor=cornsilk>Image:</TH></TR>
        <TR align=left>
          <TD vAlign=top width="100%"><IMG height=142 alt="" 
            src="http://www.pgdp.net/c/faq/table1.png" 
            width=500><BR></TD></TR>
        <TR>
          <TH align=left bgColor=cornsilk>Texte correct:</TH></TR>
        <TR>
          <TD vAlign=top width="100%">
            <TABLE align=left summary="" border=0>
              <TBODY>
              <TR>
                <TD><PRE>Deg. C.  Millimeters of Mercury. Gasolene.
Pure Benzene.

-10°  13.4  43.5
 0°  26.6  81.0
+10°  46.6  132.0
20°  76.3  203.0
40°  182.0  301.8
</PRE></TD></TR></TBODY></TABLE></TD></TR></TBODY></TABLE><BR>
      <TABLE cellSpacing=0 cellPadding=4 width="100%" align=center 
      summary="Table Example 2" border=1>
        <TBODY>
        <TR>
          <TH align=left bgColor=cornsilk>Image:</TH></TR>
        <TR align=left>
          <TD vAlign=top width="100%"><IMG height=304 alt="" 
            src="http://www.pgdp.net/c/faq/table2.png" 
            width=500><BR></TD></TR>
        <TR>
          <TH align=left bgColor=cornsilk>Texte correct:</TH></TR>
        <TR>
          <TD vAlign=top width="100%">
            <TABLE align=left summary="" border=0>
              <TBODY>
              <TR>
                <TD><PRE>TABLE II.

Flat strips compared   Copper   Copper
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
Same strip rolled up in  Same 16 wires bound
  the form of a wire  17   15    close together   18    12
</PRE></TD></TR></TBODY></TABLE></TD></TR></TBODY></TABLE>

<H3><A name=title_pg><B>Page de garde/fin</B></A> </H3>
<P>Laissez tout comme c'est imprimé, même si c'est tout en majuscules, ou en 
majuscules et minuscules. Gardez la date de copyright ou de publication. 
Certaines livres, souvent, mettent la première lettre 
grande et ornée. Tapez simplement la lettre.</P><!-- END RR -->
      <TABLE cellSpacing=0 cellPadding=4 width="100%" align=center 
      summary="Title Page Example" border=1>
        <TBODY>
        <TR>
          <TH align=left bgColor=cornsilk>Image: </TH></TR>
        <TR align=left>
          <TD vAlign=top width="100%"><IMG height=520 alt="title page image" 
            src="http://www.pgdp.net/c/faq/title.png" 
          width=500><BR></TD></TR>
        <TR>
          <TH align=left bgColor=cornsilk>Texte correct:</TH></TR>
        <TR>
          <TD vAlign=top width="100%">
            <TABLE align=left summary="" border=0>
              <TBODY>
              <TR>
                <TD>
                  <P><TT>GREEN FANCY</TT> </P>
                  <P><TT>BY</TT></P>
                  <P><TT>GEORGE BARR McCUTCHEON</TT></P>
                  <P><TT>AUTHOR OF "GRAUSTARK," "THE HOLLOW OF HER 
                  HAND,"<BR>"THE PRINCE OF GRAUSTARK," ETC.</TT></P>
                  <P><TT>WITH FRONTISPIECE BY<BR>C. ALLAN GILBERT</TT></P>
                  <P><TT>NEW YORK<BR>DODD, MEAD AND COMPANY.</TT></P>
                  <P><TT>1917</TT></P></TD></TR></TBODY></TABLE></TD></TR></TBODY></TABLE>

<H3><A name=toc>Table des matières</A> </H3>
<P>Laissez le texte de la table des matières comme il est imprimé (même si c'est 
tout en capitales). Gardez les numéros de page. Ignorez les points ou étoiles
qui forment des 
lignes horizontales, entre le texte et le numéro. Ils seront enlevés
en phase de formatage.</P><!-- END RR -->
     <TABLE cellSpacing=0 cellPadding=4 width="100%" align=center summary=TOC 
      border=1>
        <TBODY>
        <TR>
          <TH align=left bgColor=cornsilk>Image: </TH></TR>
        <TR align=left>
          <TD vAlign=top width="100%">
            <P><IMG height=650 alt="" 
            src="http://www.pgdp.net/c/faq/tablec.png" 
            width=500></P></TD></TR>
        <TR>
          <TH align=left bgColor=cornsilk>Texte correct:</TH></TR>
    <tr>
      <td width="100%" valign="top">
<table summary="" border="0" align="left"><tr><td>
      <p><tt>CONTENTS</tt></p>
      <p><tt><br>
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
          AND TWO MEN RIDE AWAY&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;35<br>
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
</td></tr>
</TBODY></TABLE></TD></TR></TBODY></TABLE>
<H3><A name=bk_index>Index</A> </H3>
<P>Laissez les numéros de page dans les index. N'alignez pas les numéros les uns sur les
autres (comme sur l'image). Assurez-vous simplement que le texte, la ponctuation et les 
numéros sont corrects, et gardez les retours à la ligne. </p>
<p>Le formatage des index sera traité en phase de formatage. Le travail du correcteur
est simplement de s'assurer que le texte et les numéros sont corrects. </p>

<H3><A name=play_n>Théâtre.</A> </H3>
<P>Pour toutes les pièces.</P>
<UL compact>

  <LI>Dans les dialogues, insérez une ligne blanche avant chaque prise de 
  parole. 
  <LI>Formatez les notes de scène (didascalies) telles qu'elles sont 
  dans le texte original. <BR>Si la note est sur une ligne isolée, laissez-la 
  ainsi. Si elle est après une ligne de dialogue, laissez-la ainsi. <BR>Parfois, 
  une note de scène commence par une parenthèse ouvrante, qui n'est jamais refermée. 
  Nous gardons cette convention: ne fermez pas la parenthèse.
  <LI>Pour les pièces en vers, si un vers est coupé parce qu'il est trop long sur 
  la page imprimée, 
  rejoignez les deux partie du vers sur une même ligne (comme pour la poésie en 
  général). Parfois, la seconde partie du vers
  sera imprimée au-dessus ou au-dessous de la ligne principale, et précédée 
  d'une "(", au lieu d'avoir une ligne pour elle seule. Appliquez alors la
  règle générale des rattachements de ligne. <BR>Voir l' <A 
  href="#play4">exemple</A>. 
  </LI></UL>
<P>Regardez les <A 
href="#comments">Commentaires 
de projet</A>, car le chef de projet peut demander un formatage différent. </P><!-- END RR -->
      <TABLE cellSpacing=0 cellPadding=4 width="100%" align=center 
      summary="Play Example 1" border=1>
        <TBODY>
        <TR>
          <TH align=left bgColor=cornsilk>Image:</TH></TR>
        <TR align=left>
          <TD vAlign=top width="100%"><IMG height=430 alt="title page image" 
            src="http://www.pgdp.net/c/faq/play1.png" 
          width=500><BR></TD></TR>
        <TR>
          <TH align=left bgColor=cornsilk>Texte correct:</TH></TR>
        <TR>
          <TD vAlign=top width="100%">
            <TABLE align=left summary="" border=0>
              <TBODY>
              <TR>
                <TD>
                  <P><TT>Has not his name for nought, he will be trode 
                  upon:<BR>What says my Printer now? </TT></P>
                  <P><TT>Clow. Here's your last Proof, Sir.<BR>You shall have 
                  perfect Books now in a twinkling. </TT></P>
                  <P><TT>Lap. These marks are ugly. </TT></P>
                  <P><TT>Clow. He says, Sir, they're proper:<BR>Blows should 
                  have marks, or else they are nothing worth. </TT></P>
                  <P><TT>Lap. But why a Peel-crow here? </TT></P>
                  <P><TT>Clow. I told 'em so Sir:<BR>A scare-crow had been 
                  better. </TT></P>
                  <P><TT>Lap. How slave? look you, Sir,<BR>Did not I say, this 
                  Whirrit, and this Bob,<BR>Should be both Pica Roman. </TT></P>
                  <P><TT>Clow. So said I, Sir, both Picked Romans,<BR>And he has 
                  made 'em Welch Bills,<BR>Indeed I know not what to make on 
                  'em. </TT></P>
                  <P><TT>Lap. Hay-day; a Souse, Italica? </TT></P>
                  <P><TT>Clow. Yes, that may hold, Sir,<BR>Souse is a bona roba, 
                  so is Flops 
      too.</TT></P></TD></TR></TBODY></TABLE></TD></TR></TBODY></TABLE>
<BR>

<a name="play4"><!-- Example --></a>
<table width="100%" align="center" border="1" cellpadding="4"
 cellspacing="0" summary="Exemple de pièce 4">
  <tbody>
    <tr>
      <th align="left" bgcolor="cornsilk">Image:</th>
    </tr>
    <tr align="left">
      <td width="100%" valign="top"><img src="http://www.pgdp.net/c/faq/play4.png" width="502"
          height="98" alt="Plays image"><br>
      </td>
    </tr>
    <tr>
      <th align="left" bgcolor="cornsilk">Texte correct:</th>
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


<H3><A name=anything>Tout ce qui nécessite également un traitement spécial, ou
dont vous n'êtes pas sûr.</A> </H3>
<P>Si vous rencontrez quelque chose qui n'est pas couvert par ces directives et 
qui vous paraît avoir besoin d'un traitement spécial, ou que vous n'êtes pas sûr 
de quelque chose, posez votre question sur le forum du projet (en précisant le 
numéro de la page qui pose problème, donnez le numéro du fichier png), 
et ajoutez une note dans le texte
à l'endroit qui poste problème . Cette note signalera le problème à la personne
qui passera cette page ensuite (correcteur, formateur ou post-processeur).</P>
<P>Mettez un crochet ouvrant puis deux étoiles avant le début de la note,
et un crochet fermant après
 <TT>[**</TT> et <TT>]</TT> pour bien séparer votre note du texte de 
l'auteur (n'oubliez pas les deux étoiles).  Ceci 
signale au post-correcteur qu'il doit s'arrêter et examiner ce texte et l'image 
correspondante et résoudre le problème. Si vous voyez une note laissée
par le volontaire qui est passé avant vous, laissez-la. Si vous n'êtes
pas d'accord avec lui, rajoutez votre propre note. </P>
<!-- END RR -->

<h3><a name="prev_notes">Notes et commentaires des correcteurs précédents.</a></h3>
<p>Les notes des correcteurs précédents <b>doivent </b> être gardées. 
Vous pouvez ajouter que vous êtes d'accord ou pas d'accord,
mais même si vous êtes sûr de la solution, ne supprimez
pas la note. Si vous avez une source qui permet
de donner la réponse au problème, citez cette source, pour que
le post-processeur s'y réfère lui aussi.
</p>
<P>Si vous  résolvez un problème posé par un correcteur qui a laissé une note,
vous pouvez écrire un message à ce correcteur (en cliquant sur son 
nom dans l'interface de correction), pour lui expliquer comment gérer la 
situation la prochaine fois. Mais ne supprimez jamais sa note.</P>
<!-- END RR -->

<H2>Problèmes courants</H2>
<H3><A name=OCR_1lI>Problèmes d'OCR 1-l-I </A></H3>
<P>Les logiciels d'OCR (Reconnaissance Optique de Caractères) ont souvent des 
difficultés pour faire la différence entre le nombre un ( 1 ), la lettre 
minuscule L ( l ) et la lettre majuscule i ( I ). C'est particulièrement vrai 
pour certains vieux livres dont les pages sont en mauvais état. Faites attention 
à ces derniers. Lisez le contexte de la phrase pour déterminer quel est le 
caractère correct, et soyez attentifs--souvent votre cerveau corrige 
automatiquement ces erreurs pendant que vous lisez.<BR></P>L'utilisation d'une 
fonte comme <A href="http://www.pgdp.net/c/faq/font_sample.php">DPCustomMono</A> 
permet de repérer facilement ce type de problème. 
<H3><A name=OCR_0O>Problèmes d'OCR 0-O</A></H3>
<P>Les logiciels d'OCR ont souvent des difficultés pour faire la différence 
entre le chiffre 0 et le O majuscule. C'est particulièrement vrai pour certains 
vieux livres dont les pages sont en mauvais état. Faites attention à ces 
derniers. Lisez le contexte de la phrase pour déterminer quel est le caractère 
correct, et soyez attentifs--souvent votre cerveau corrige automatiquement ces 
erreurs pendant que vous lisez.<BR></P>L'utilisation d'une fonte comme <A 
href="http://www.pgdp.net/c/faq/font_sample.php">DPCustomMono</A> permet de 
repérer facilement ce type de problème. 

<H3><A name=OCR_scanno>Problèmes d'OCR: Scannos</A></H3>
<P>Un autre problème courant, avec les OCRs, est celui de la mauvaise 
reconnaissance de certains caractères: les "scannos" (comme "typos"). Le 
résultat peut être un mot qui: 
<UL compact>
  <LI>a l'air correct à première vue, mais qui est mal écrit. Vous le verrez facilement en 
  faisant tourner le vérificateur d'orthographe. 
  <LI>a été transformé en autre mot, valide, mais qui n'est pas celui qu'a écrit 
  l'auteur. Ces erreurs ne peuvent pas être repérées automatiquement, mais 
  seulement par quelqu'un qui lit vraiment le texte. </LI></UL>
<P>En anglais, l'exemple le plus courant de scanno du second type est "arid" pour 
"and". En français, 
"môme" pour "même", "rosé" pour "rose", "a" pour "à", "vint" pour "vînt", etc. 
Nous les appelons les "Scannos 
furtifs", car ils sont plus difficiles à voir. Des exemples ont été collectés <A 
href="http://www.pgdp.net/phpBB2/viewtopic.php?t=1563">ici</A>. Les scannos sont 
plus faciles à voir avec une fonte monospace comme <A 
href="http://www.pgdp.net/c/faq/font_sample.php">DPCustomMono</A> ou Courier. 
</P><!-- END RR -->
<H3><A name=hand_notes>Notes manuscrites dans le livre</A> </H3>
<P>N'incluez pas les notes manuscrites dans le livre (à moins que quelqu'un ait 
repassé des lettres mal imprimées ou effacées). N'incluez pas les notes écrites 
en marge par les lecteurs.<BR></P>

<H3><A name=bad_image>Mauvaises images</A> </H3>
<P>Si une image est mauvaise (refuse de se charger, est coupée au milieu, 
illisible), postez un message à propos de cette image dans le <A 
href="#forums">forum</A> du 
projet. Ne cliquez pas sur “Return page to round”; si vous le faites, la 
personne suivante obtiendra cette page. Cliquez plutôt sur "Report bad 
page" pour mettre la page à part.&nbsp;</P>
<P>Parfois, les images sont très grosses, et votre navigateur aura des problèmes 
pour les afficher, surtout si vous avez beaucoup de fenêtres ouvertes ou si 
votre ordinateur est vieux. Avant de déclarer la page "mauvaise", cliquez sur la 
ligne "image" en bas de la page pour faire apparaître l'image sur une fenêtre à 
part. Si l'image est alors bonne, alors le problème vient probablement de votre 
système, ou de votre navigateur.&nbsp;</P>
<P>Il est relativement courant que l'image soit bonne, mais que le texte passé à 
l'OCR ne contienne pas la première (et deuxième) ligne. Retapez alors les lignes 
qui manquent. Si presque toutes les lignes manquent, alors soit tapez toute la 
page (si vous voulez le faire), ou cliquez sur le bouton “Return page to round” 
et la page sera rendue à quelqu'un d'autre. Si plusieurs pages sont comme ça, 
postez un message sur le <A 
href="#forums">forum</A> 
pour l'indiquer au responsable du projet.</P>
<H3><A name=bad_text>Image ne correspondant pas au texte</A> </H3>
<P>Si l'image ne correspond pas au texte, postez un message à ce propos sur le 
<A href="#forums">forum</A>. 
Ne cliquez pas sur “Return page to round”; si vous le faites, la personne 
suivante obtiendra cette page. Cliquez plutôt sur "Report bad page" pour 
mettre la page à part.&nbsp;</P>
<H3><A name=round1>Erreurs des correcteurs précédents</A> </H3>
<P>Si le correcteur précédent à fait beaucoup d'erreurs ou a laissé passer un 
grand nombre de fautes, vous pouvez lui envoyer un message en cliquant sur son 
nom. Ça vous permettra de lui envoyer un message privé par le forum: ainsi
il corrigera mieux et fera moins d'erreurs la prochaine fois .<BR><I>Soyez 
aimable!</I> Ces gens sont des volontaires, essayant d'habitude de faire de leur 
mieux. Le but du message est de les informer de la manière correcte de corriger, 
plutôt que de les critiquer. Donnez-leur un exemple précis de ce qu'ils ont 
fait, et de ce qu'ils auraient dû faire. <BR>Si le correcteur précédent a fait 
un travail remarquable, vous pouvez également lui envoyer un message pour le lui 
dire, sourtout s'il a travaillé sur une page très difficile.<BR></P>
<H3><A name=p_errors>Erreurs d'impression/d'orthographe</A> </H3>
<P>Corrigez toujours les fautes de scan. Mais ne corrigez pas une faute 
d'orthographe ou d'impression. Parfois, certains mots ne s'écrivaient pas comme 
aujourd'hui quand le livre a été imprimé. Gardez l'ancienne orthographe, en particulier
en ce qui concerne les accents. </P>
<P>Si vous avez vraiment un doute, alors mettez une note dans le txte 
<TT>[**typo for texte?]</TT> et demandez dans le forum du projet. Si vous 
changez vraiment quelque chose, alors mettez une note décrivant ce
que vous avez changé <TT>[**Transcriber's Note: 
typo fixed, changed from "txte" to "texte"]</TT>. N'oubliez pas les deux étoiles <TT>**</TT> 
pour que le post-correcteur voie le problème. </P>
<H3><A name=f_errors>Erreurs factuelles dans le texte</A> </H3>
<P>En général, ne corrigez pas les erreurs sur les faits dans les livres. 
Beaucoup de livres que nous corrigeons décrivent des choses que nous savons être
fausses comme étant des faits. Laissez-les tel que l'auteur les a écrits. </P>
<P>Une exception possible est dans les livres techniques ou scientifiques, dans 
lesquels un formule connue ou une équation peuvent être indiquées incorrectement. 
(En particulier si elles sont notées d'une manière correcte sur d'autres pages 
du livre). Parlez-en au responsable de projet&nbsp; soit en envoyant un message 
via le <A 
href="#forums">Forum</A>, ou 
en insérant <TT>[sic** expliquez-votre-souci]</TT> à cet endroit du texte. </P>
<H3><A name=uncertain>Points incertains</A> </H3>
<P>[...à compléter...] </P>
<H2 align=center>Fin des directives</H2>

<TABLE cellSpacing=0 width="100%" border=0>
  <TBODY>
  <TR>
    <TD bgColor=silver><BR></TD></TR></TBODY></TABLE>Retournez à la <A
href="http://www.pgdp.net/">Page principale de Distributed
Proofreaders</A><BR><BR><BR>

<?
theme('','footer');
?>
