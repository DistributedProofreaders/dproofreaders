<?PHP
include_once($relPath.'v_site.inc');

$orig_line_i=0;
$orig_line='';
$orig_start=0;
$html_for_line='';
$n_bad_words=0;

function spellcheck_text( $orig_text, $projectid, $imagefile )
{
	global $orig_line,$html_for_line,$orig_line_i,$orig_start,$n_bad_words;

	$orig_text=str_replace(array("\r","\n\n","\n"),array("\n","[lf]","[lf]"),$orig_text);

	echo "<input type='hidden' name='text_data' value='" . htmlentities($orig_text) . "'>";

	$orig_lines= explode("[lf]",$orig_text);

	{
		$tmp_file_name = $projectid . substr($imagefile,0,-4) . ".txt";
		$tmp_file_path = $aspell_temp_dir . $tmp_file_name;

		$fd=fopen($tmp_file_path,"w");
		if (!$fd)
		{
			return;
		}

		fwrite($fd,"!\n");
		foreach($orig_lines as $orig_line)
		{
			// adding carat
			fwrite($fd,"^$orig_line\n");
		}
		fclose($fd);

		global $aspell_executable, $aspell_prefix;
		$aspell_dict="en.multi";
		$aspell_command="cat $tmp_file_path | {$aspell_executable} --prefix={$aspell_prefix} --master={$aspell_dict} --ignore-accents -H -a";
		// run aspell
		// "asr" stands for "aspell result"
		$asr_text = `$aspell_command`;
		$asr_text = str_replace(array("\r","\n"),array('',"[lf]"),$asr_text);
		$asr_lines = explode("[lf]",$asr_text);
		$asr_n_lines = count($asr_lines);
	}

	//pretty return to show newline [nl] vs ok lines [ok]
	// discard 0 as it is just the identifier
	$asr_line_i=1;
	$asrm_lines=array();
	$asrm_line_i=0;
	$check_ok=1;

	while($asr_line_i < $asr_n_lines-2)
	{
		if(strcmp($asr_lines[$asr_line_i]{0},'&')==0 || strcmp($asr_lines[$asr_line_i]{0},'#')==0)
		{
			// incorrect
			$asrm_lines[$asrm_line_i]=$asr_lines[$asr_line_i];
			$check_ok=0;
		}
		else 
		{
			// correct
			if ($check_ok==1)
			{
				$asrm_lines[$asrm_line_i]="[ok]";
			}
			else
			{
				$asrm_lines[$asrm_line_i]="[nl]";
				$check_ok=1;
			}
		}
		$asrm_line_i++;
		$asr_line_i++;
	} // end line up arrays

	// print text with html
	echo "<pre>";
	$orig_line = '';
	foreach($asrm_lines as $asrm_line)
	{
		if (strcmp($asrm_line,"[ok]")==0)
		{
			// print text as is
			echo escapeStringEntities($orig_lines[$orig_line_i])."<BR>\r\n";
			$orig_line_i++;
		}
		else if (strcmp($asrm_line,"[nl]")==0)
		{
			// new line
			$html_for_line .= substr($orig_line,$orig_start);
			echo $html_for_line."<BR>\r\n";
			$orig_start = 0;
			$orig_line = '';
			$html_for_line = '';
			$orig_line_i++;
		}
		else if (strcmp($asrm_line{0},'#')==0)
		{
			// format==# original offset
			$asrm_fields = explode(' ',$asrm_line);
			$orig_word        = $asrm_fields[1];
			$orig_word_offset = $asrm_fields[2];
			spellSuggest($orig_word, 0, $orig_word_offset, '');
		}
		else if (strcmp($asrm_line{0},'&')==0)
		{
			// format==& original count offset: word, word, ....
			$sug_array = explode(":",$asrm_line);
			$asrm_fields = explode(' ',$sug_array[0]);
			// instructions
			$orig_word        = $asrm_fields[1];
			$n_suggs          = $asrm_fields[2];
			$orig_word_offset = $asrm_fields[3];
			$suggs_str        = trim($sug_array[1]);
			$orig_line        = $orig_lines[$orig_line_i];
			spellSuggest($orig_word, $n_suggs, $orig_word_offset, $suggs_str);
		}
		else
		{
			echo "Error Processing Aspell return data<BR>\r\n";
		}
	} // end display text for spelling
	echo "</pre>";

	echo "<input type='hidden' name='sptotal' value='$n_bad_words'>";
}

function spellSuggest($orig_word, $n_suggs, $orig_word_offset, $suggs_str)
{
	// echo "spellSuggest( orig_word='$orig_word', n_suggs=$n_suggs, orig_word_offset=$orig_word_offset, suggs_str='$suggs_str' )<br>\n";
	global $orig_line,$orig_line_i,$orig_start,$n_bad_words,$html_for_line;

	$n_bad_words++;

	$html_for_line .= escapeStringEntities(substr($orig_line,$orig_start,($orig_word_offset-1)-$orig_start));
	$orig_word = escapeStringEntities($orig_word);

	// create html
	// hidden values line|offset|word length
	$html_for_word = "<input type='hidden' name='posit{$n_bad_words}' value='".
		"{$orig_line_i}|{$orig_word_offset}|".strlen($orig_word)."'>";
	$html_for_word .= "<input type='hidden' name='wd{$n_bad_words}' value='{$orig_word}'>";
	// option list
	$html_for_word .= "<select name='sp{$n_bad_words}' onChange='setSpell($n_bad_words,this.value);'>".
		"<option value='{$orig_word}' selected>{$orig_word}</option>\r\n";
	$html_for_word .= "<option value='sp1input'>-- Insert --</option>\r\n";
	if ($n_suggs > 0)
	{
		// escape all words
		$suggs_str = escapeStringEntities($suggs_str);
		// break word list
		$suggs = explode(', ',$suggs_str);
		foreach ($suggs as $sugg)
		{
			$html_for_word .= "<option value='$sugg'>$sugg</option>\r\n";
		}
	}
	$html_for_word .= "</select>";

	// put html in end
	$html_for_line .= $html_for_word;

	// advance $orig_start
	$orig_start = ($orig_word_offset-1)+strlen($orig_word);
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
