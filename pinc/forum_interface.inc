<?php
global $forum_type;
$forum_interface_file = "forum_interface_{$forum_type}.inc";
include_once($relPath.$forum_interface_file);
