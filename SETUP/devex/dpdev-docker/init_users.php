<?php
$relPath = __DIR__ . "/../../../pinc/";
require_once $relPath."base.inc";

create_forum_user("admin", "admin", "admin@example.com");
create_forum_user("pm", "pm", "pm@example.com");
create_forum_user("proofer", "proofer", "proofer@example.com");
