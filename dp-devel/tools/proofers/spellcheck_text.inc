<?PHP
include_once($relPath.'v_site.inc');

function spellcheck_text( $orig_text, $projectid, $imagefile )
{
	$orig_text=str_replace(array("\r","\n\n","\n"),array("\n","[lf]","[lf]"),$orig_text);

	echo "<input type='hidden' name='text_data' value='" . html_safe($orig_text) . "'>";

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
		$orig_line = $orig_lines[$orig_line_i];

		$asr_line = $asr_lines[$i];
		if ( $asr_line == '' )
		{
			// aspell outputs a blank line
			// after it has finished with the current input line.

			// Flush any remaining original-text on this line.
			$orig_str = substr( $orig_line, $orig_start );
			echo html_safe($orig_str);

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
			$orig_str = substr( $orig_line, $orig_start, ($orig_word_offset-1)-$orig_start );
			echo html_safe($orig_str);

			$n_bad_words++;

			// Take the length *before* passing it through html_safe.
			$orig_word_len = strlen($orig_word);

			$orig_word = html_safe($orig_word);

			// create html
			// hidden values line|offset|word length
			echo
				"<input
					type='hidden'
					name='posit{$n_bad_words}'
					value='$orig_line_i|$orig_word_offset|$orig_word_len'
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
				$suggs_str = html_safe($suggs_str);

				// break word list
				$suggs = explode(', ',$suggs_str);

				foreach ($suggs as $sugg)
				{
					echo "<option value='$sugg'>$sugg</option>\n";
				}
			}
			echo "</select>";

			// Advance $orig_start
			$orig_start = ($orig_word_offset-1) + $orig_word_len;
		}
	}

	echo "</pre>";

	echo "<input type='hidden' name='sptotal' value='$n_bad_words'>";
}

function html_safe( $str )
// Return a copy of $str which has been rendered safe to send as
// element-content or attribute-content in an HTML document.
{
	return htmlspecialchars($str,ENT_QUOTES);
}

// I think this function would only be useful
// if you were sending the HTML to a browser
// that only accepted (7-bit) ASCII.
// But the rest of the site assumes
// that any Latin-1 character is okay to send,
// so I don't see why we wouldn't assume that here.
//
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

// --------------------------------------------

// Because we have PHP configured with magic_quotes_gpc on,
// anything we pull out of $_POST will automatically have
// each single-quote, double-quote, and backslash
// preceeded by an extra backslash.
// We don't want these extra backslashes here,
// so we apply stripslashes to anything (textual) we get from $_POST.


function spellcheck_quit()
{
	$orig_text = stripslashes($_POST['text_data']);
	return str_replace( "[lf]", "\r\n", $orig_text );
}

function spellcheck_apply_corrections()
{
	$orig_text = stripslashes($_POST['text_data']);

	// were there any corrections?
	$n_bad_words=isset($_POST['sptotal'])?$_POST['sptotal']:0;
	if ($n_bad_words==0)
	{
		// just give them the text
		$correct_text = str_replace("[lf]","\r\n",$orig_text);
	}
	else
	{
		// make corrections
		$text_array = explode("[lf]",$orig_text);

		// If there are multiple corrections on a single line,
		// and you do them from left to right,
		// any correction (after the first) will have its offset info invalidated
		// (or at least, rendered harder to use) by the corrections to its left
		// (if any of them has a $correct_word with a different length from the
		// $orig_word).
		// So we want to the corrections on a line from right to left.
		// The easiest way to do this is to do all corrections in reverse order.
		// 
		for ( $i = $n_bad_words; $i >= 1; $i-- )
		{
			// hidden values line|offset|word length
			$inst_array=explode('|',$_POST['posit'.$i]);
			$orig_line_i      = $inst_array[0];
			$orig_word_offset = $inst_array[1];
			$orig_word_length = $inst_array[2];
			// hidden word
			$correct_word=stripslashes($_POST['wd'.$i]);
			// replace word in string
			$text_array[$orig_line_i] =
				substr($text_array[$orig_line_i],0,$orig_word_offset-1) .
				$correct_word .
				substr($text_array[$orig_line_i],($orig_word_offset+$orig_word_length)-1);
		}
		$correct_text .= implode("\r\n",$text_array);
	}
	return $correct_text;
}

?>
