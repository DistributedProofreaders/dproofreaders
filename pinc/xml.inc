<?php
// This file holds XML-related functions
include_once($relPath."misc.inc"); // html_safe()

function xmlencode($data) {
	$data = html_safe($data);
	return $data;
}
?>
