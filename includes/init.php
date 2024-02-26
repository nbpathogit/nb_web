<?php

/**
 * Initialisations
 * 
 * Register an autoloader, start or resume the session etc.
 */

$root= $_SERVER['DOCUMENT_ROOT']."../../../../";

spl_autoload_register(function ($class){
    require dirname(__DIR__)."/classes/{$class}.php";
});

session_start();



$hide = false;
$show = true;

$GLOBALS['isSqlWriteFileForDBG'] = FALSE;

$GLOBALS['isBillByAcceptDate'] = FALSE;
$GLOBALS['isBillByServiceDate'] = TRUE;