<?php
$forum_interface_file = sprintf("forum_interface_%s.inc", SiteConfig::get()->forum_type);
require $relPath.$forum_interface_file;
