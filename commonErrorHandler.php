<?php
/**
 * Created by PhpStorm.
 * User: Briantist
 * Date: 06/01/2017
 * Time: 17:21
 */

require_once "pseph/nff/framework/Psr4AutoloaderClass.php";

date_default_timezone_set("Europe/London");
if (true) {
    ini_set('display_startup_errors', 1);
    ini_set('display_errors', 1);
    error_reporting(E_ALL);
    function errorHandler($errno, $errstr, $errfile, $errline)
    {
        debug_print_backtrace();
        die("{$errstr} in {$errfile} at line {$errline} [{$errno}] ");
    }

    set_error_handler("errorHandler", E_ALL);
} else {
    ini_set('display_startup_errors', 0);
    ini_set('display_errors', 0);
    error_reporting(0);
}
