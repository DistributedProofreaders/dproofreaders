<?php
$relPath = "./../../pinc/";
include_once($relPath.'base.inc');
include_once($relPath.'metarefresh.inc');
include_once($relPath.'misc.inc'); // get_integer_param()
include_once('../includes/team.inc');

require_login();

$tid = get_integer_param($_GET, 'tid', null, 0, null);

$user = User::load_current();
$user->remove_team($tid);
metarefresh(0, "../teams/tdetail.php?tid=$tid");
