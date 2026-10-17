<?php

/**
 * Initialisations
 * 
 * Register an autoloader, start or resume the session etc.
 */

$root= $_SERVER['DOCUMENT_ROOT']."../../../../";

spl_autoload_register(function ($class){
    $classFile = dirname(__DIR__) . "/classes/{$class}.php";
    if (is_file($classFile)) {
        require_once $classFile;
    }
});

session_start();



$hide = false;
$show = true;

$GLOBALS['isSqlWriteFileForDBG'] = TRUE;
$GLOBALS['isSqlWriteFileForDBG'] = FALSE;

$GLOBALS['isBillByAcceptDate'] = TRUE;
$GLOBALS['isBillByServiceDate'] = FALSE;