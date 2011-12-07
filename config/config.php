<?php
error_reporting(E_ALL);

/* Defaults */
$conf_default_controller = 'index';
$conf_default_action = 'index';
$conf_default_error_controller = 'error';

/* Include Path */
set_include_path( __DIR__ . '/../controller' . PATH_SEPARATOR .  __DIR__ . '/../view' . PATH_SEPARATOR  .  __DIR__ . '/../action'. PATH_SEPARATOR  .  __DIR__ . '/../lib');
