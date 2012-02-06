<?php

/* Config */
include(__DIR__.'/config.php');

/* Enable Autoload */
include(__DIR__.'/classes/bledo/Autoload.php');
\bledo\Autoload::enable(true, true);

/* Run Framework */
\bledo\mvc\Fw::run();

