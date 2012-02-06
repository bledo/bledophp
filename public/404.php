<?php
/* Config */
include(__DIR__.'/../config.php');

/* Enable Autoload */
include(__DIR__.'/../classes/bledo/Autoload.php');
\bledo\Autoload::enable(true, true);

$resp = new \bledo\mvc\response\Phtml(VIEWDIR, 'error.phtml', 'error/404.phtml');
$resp->respond(new \bledo\mvc\Request('error', 'index', 'index'));
