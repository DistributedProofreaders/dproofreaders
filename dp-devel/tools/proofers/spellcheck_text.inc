<?PHP
include_once($relPath.'v_site.inc');

$orig_line_i=0;
$orig_lines=Array();
$orig_start=0;

function spellcheck_text( $orig_text, $projectid, $imagefile )
{
	global $orig_lines,$orig_line_i,$orig_start;

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
	}

	$n_bad_words=0;

	echo "<pre>";

	$orig_line_i = 0;
	$orig_start  = 0;

	// $asr_lines[0] is the identification line, so start at 1.
	for ( $i = 1; $i < count($asr_lines); $i++ )
	{
		$asr_line = $asr_lines[$i];
		if ( $asr_line == '' )
		{
			// aspell outputs a blank line
			// after it has finished with the current input line.

			// Flush any remaining original-text on this line.
			flush_orig( 1000000 );

			// Put a line-break in the output.
			echo "\n\n";
			// (A single '\n' would do, but it makes the lines too squished.)

			// And proceed to the next original-line.
			$orig_line_i++;
			$orig_start = 0;
		}
		else
		{
			$asr_flag = $asr_line{0};
			// Because aspell was invoked in "terse" mode,
			// $asr_flag must be one of & ? #

			if ( $asr_flag == '&' || $asr_flag == '?' )
			{
				// format:
				//     &  <original>  <count>  <offset>: <miss>, <miss>, ..., <guess>, ...
				//     ?  <original>     0     <offset>: <guess>, <guess>, ...

				$sug_array = explode(":",$asr_line);
				$asr_fields = explode(' ',$sug_array[0]);
				$orig_word        = $asr_fields[1];
				$n_suggs          = $asr_fields[2];
				$orig_word_offset = $asr_fields[3];
				$suggs_str        = trim($sug_array[1]);
			}
			else if ( $asr_flag == '#' )
			{
				// format:
				//     # <original> <offset>
				$asr_fields = explode(' ',$asr_line);
				$orig_word        = $asr_fields[1];
				$orig_word_offset = $asr_fields[2];
				$n_suggs          = 0;
				$suggs_str        = '';
			}
			else
			{
				echo "unexpected aspell flag: '$asr_flag'<BR>\n";
				return;
			}

			// Flush any original-text preceding the bad word.
			flush_orig( $orig_word_offset );

			$n_bad_words++;

			$orig_word = escapeStringEntities($orig_word);

			// create html
			// hidden values line|offset|word length
			echo
				"<input
					type='hidden'
					name='posit{$n_bad_words}'
					value='$orig_line_i|$orig_word_offset|".strlen($orig_word)."'
				><input
					type='hidden'
					name='wd{$n_bad_words}'
					value='{$orig_word}'
				><select
					name='sp{$n_bad_words}'
					onChange='setSpell($n_bad_words,this.value);'>
					<option value='{$orig_word}' selected>{$orig_word}</option>
					<option value='sp1input'>-- Insert --</option>
				";

			if ($n_suggs > 0)
			{
				// escape all words
				$suggs_str = escapeStringEntities($suggs_str);

				// break word list
				$suggs = explode(', ',$suggs_str);

				foreach ($suggs as $sugg)
				{
					echo "<option value='$sugg'>$sugg</option>\n";
				}
			}
			echo "</select>";

			// Advance $orig_start
			$orig_start = ($orig_word_offset-1)+strlen($orig_word);
		}
	}

	echo "</pre>";

	echo "<input type='hidden' name='sptotal' value='$n_bad_words'>";
}

function flush_orig( $orig_word_offset )
{
	global $orig_lines, $orig_line_i, $orig_start;

	$orig_line = $orig_lines[$orig_line_i];
	$orig_str = substr( $orig_line, $orig_start, ($orig_word_offset-1)-$orig_start );
	echo escapeStringEntities($orig_str);
}

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
