<?
$relPath='../pinc/';
include($relPath.'v_site.inc');
include($relPath.'connect.inc');
include($relPath.'theme.inc');
new dbConnect();
$no_stats=1;
theme('Templates available for Project Comments','header');
?>

<style TYPE="text/css">
<!--
  tt {color: red}
        -->
  </style>
<!--
  <style>
                @page { size: 21.59cm 27.94cm; margin-left: 3.18cm; 
margin-right: 3.18cm; margin-top: 2.54cm; margin-bottom: 2.54cm }
                P { margin-bottom: 0.21cm }
                TD P { margin-bottom: 0.21cm }
  </style>
        -->

<!-- BG1a.txt info -->
<a name="bg1a"></a>
<?
include($relpath."templates/comment_files/BG1a.txt");
?>
		

<!-- BG1b.txt info -->		
<a name="bg1b"></a>


<!-- BGr2.txt info -->
<a name="bgr2"></a>

		

<!-- diac.txt info -->
<a name="diac"></a>
		

<!-- pgnm.html info -->		
<a name="pgnm"></a>


<!-- port.txt info -->
<a name="port"></a>

<!-- wrrn.txt info -->
<a name="wrrn"></a>

		

<!-- cedron.html info -->		

<!-- This code was made by cedron -->
<a name="cedrlayout"></a> <strong>LAYOUT</strong> 

<strong>CODE</strong> <a name="cedrcode"></a>




<!-- cgehring.html info -->		

<!-- This code was made by cgehring -->
<a name="cgehlayout"></a> <strong>LAYOUT</strong> 
<strong>CODE</strong> <a name="cgehcode"></a>

<?
theme('','footer');
?>
