<?PHP
// Send real HTTP headers to user-agents - at least one of these headers should
// be honored by all clients/proxies/caches.
//
// Date in the past
header("Expires: Mon, 01 Sep 2000 09:00:00 GMT");

// always modified
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");

// HTTP/1.1
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);

// HTTP/1.0
header("Pragma: no-cache");
?>
