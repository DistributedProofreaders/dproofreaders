<?PHP
// This file holds XML-related functions

function xmlencode($data) {
	$data = htmlspecialchars($data, ENT_QUOTES);
	return $data;
}
?>
