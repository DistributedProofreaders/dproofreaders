<?PHP
include_once($relPath.'v_site.inc');

$text_index=0;
$text_all=array('start'=>'','end'=>'');
$string_start=0;
$select_count=1;

function spellcheck_text( $text_data, $projectid, $imagefile )
{
	global $text_all,$text_index,$string_start,$select_count;

	$text_data=str_replace(array("\r","\n\n","\n"),array("\n","[lf]","[lf]"),$text_data);

	echo "<input type='hidden' name='text_data' value='" . htmlentities($text_data) . "'>";

	$text_array= explode("[lf]",$text_data);

	{
		$tmp_file_name = $projectid . substr($imagefile,0,-4) . ".txt";
		$tmp_file_path = $aspell_temp_dir . $tmp_file_name;

		$fd=fopen($tmp_file_path,"w");
		if (!$fd)
		{
			return;
		}

		fwrite($fd,"!\n");
		foreach($text_array as $key=>$value)
		{
			// adding carat
			fwrite($fd,"^$value\n");
		}
		fclose($fd);

		global $aspell_executable, $aspell_prefix;
		$aspell_dict="en.multi";
		$aspell_command="cat $tmp_file_path | {$aspell_executable} --prefix={$aspell_prefix} --master={$aspell_dict} --ignore-accents -H -a";
		// run aspell
		$return=`$aspell_command`;
		$return=str_replace(array("\r","\n"),array('',"[lf]"),$return);
		$return_array= explode("[lf]",$return);
		$return_lines= count($return_array);
	}

	//pretty return to show newline [nl] vs ok lines [ok]
	// discard 0 as it is just the identifier
	$return_index=1;
	$check_array=array();
	$check_index=0;
	$check_ok=1;

	while($return_index<$return_lines-2)
	{
		if(strcmp($return_array[$return_index]{0},'&')==0 || strcmp($return_array[$return_index]{0},'#')==0)
		{
			// incorrect
			$check_array[$check_index]=$return_array[$return_index];
			$check_ok=0;
		}
		else 
		{
			// correct
			if ($check_ok==1)
			{
				$check_array[$check_index]="[ok]";
			}
			else
			{
				$check_array[$check_index]="[nl]";
				$check_ok=1;
			}
		}
		$check_index++;
		$return_index++;
	} // end line up arrays

	// print text with html
	echo "<pre>";
	foreach($check_array as $key=>$value)
	{
		if (strcmp($value,"[ok]")==0)
		{
			// print text as is
			echo escapeStringEntities($text_array[$text_index])."<BR>\r\n";
			$text_index++;
		}
		else if (strcmp($value,"[nl]")==0)
		{
			// new line
			$text_all['end'].=substr($text_all['start'],$string_start);
			echo $text_all['end']."<BR>\r\n";
			$string_start=0;
			$text_all['start']='';
			$text_all['end']='';
			$text_index++;
		}
		else if (strcmp($value{0},'#')==0)
		{
			// format==# original offset
			$inst_array=explode(' ',$value);
			$sp_original=$inst_array[1];
			$sp_offset=$inst_array[2];
			spellSuggest($sp_original, 0, $sp_offset, '');
		}
		else if (strcmp($value{0},'&')==0)
		{
			// format==& original count offset: word, word, ....
			$sug_array=explode(":",$value);
			$inst_array=explode(' ',$sug_array[0]);
			// instructions
			$sp_original=$inst_array[1];
			$sp_count=$inst_array[2];
			$sp_offset=$inst_array[3];
			$sp_suggest=trim($sug_array[1]);
			$text_all['start']=$text_array[$text_index];
			spellSuggest($sp_original, $sp_count, $sp_offset, $sp_suggest);
		}
		else
		{
			echo "Error Processing Aspell return data<BR>\r\n";
		}
	} // end display text for spelling
	echo "</pre>";

	echo "<input type='hidden' name='sptotal' value='".($select_count-1)."'>";
}

function spellSuggest($text_word, $text_count, $text_offset, $text_suggest)
{
	// echo "spellSuggest( text_word='$text_word', text_count=$text_count, text_offset=$text_offset, text_suggest='$text_suggest' )<br>\n";
	global $text_all,$text_index,$string_start,$select_count;

	$text_all['end'].=escapeStringEntities(substr($text_all['start'],$string_start,($text_offset-1)-$string_start));
	$text_word=escapeStringEntities($text_word);

	// create html
	// hidden values line|offset|word length
	$text_html="<input type='hidden' name='posit{$select_count}' value='".
		"{$text_index}|{$text_offset}|".strlen($text_word)."'>";
	$text_html.="<input type='hidden' name='wd{$select_count}' value='{$text_word}'>";
	// option list
	$text_html.="<select name='sp{$select_count}' onChange='setSpell($select_count,this.value);'>".
		"<option value='{$text_word}' selected>{$text_word}</option>\r\n";
	$text_html.= "<option value='sp1input'>-- Insert --</option>\r\n";
	if ($text_count > 0)
	{
		// escape all words
		$text_suggest=escapeStringEntities($text_suggest);
		// break word list
		$sp_list=explode(', ',$text_suggest);
		foreach ($sp_list as $key=>$value)
		{
			$text_html.= "<option value='$value'>$value</option>\r\n";
		}
	}
	$text_html.="</select>";
	$select_count++;

	// put html in end
	$text_all['end'].=$text_html;

	// advance $string_start
	$string_start=($text_offset-1)+strlen($text_word);
} // end spellSuggest

function escapeStringEntities($text)
{
	$ent_array=array();
	for ($i=161;$i<256;$i++)
	{
		$ent_array[chr($i)]="&#{$i};";
	}
	$text=strtr($text,$ent_array);
	return $text;
}

?>
