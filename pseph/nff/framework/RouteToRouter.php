<?php

namespace pseph\nff\framework;
use pseph\nff\framework\config\SystemConfig;
use pseph\nff\services\Session;
use view\helper\core;

class RouteToRouter
{
    private $arrParameters = [];

    public function __construct($strURI)
    {
        date_default_timezone_set("Europe/London");
        $strURI = self::getPathToHereTranslated($strURI);
        switch ($strURI) {
            case "":
            case "/":
                header("Location: " . SystemConfig::HOMEROUTE);
                die();
                break;
            case "/favicon.ico":
                header("Location: /styles/icons/favicon.ico");
                die();
                break;
        }
        if (!core::isWindowsMachine()) {
//            if (!self::clientIsValidIPAddress()) {
//                header("Location: " . systemconfig::BADIPGOTO);
//                die();
//            }
//            self::turnOnCompression();
        }
        $this->arrParameters = preg_split("/\//", $strURI . "////////////");
        $this->execute($this->arrParameters[1]);
    }

    public static function getPathToHereTranslated($strURI)
    {
        $strPathToHere = self::getPathToHere();
        $strURI = strtr($strURI, [$strPathToHere => ""]);
        return $strURI;
    }

    public static function getPathToHere()
    {
        return UrSlugCleaner::fixHTTPS("http://") . $_SERVER["SERVER_NAME"] . ":" . $_SERVER["SERVER_PORT"];
    }

    public function execute($strMainRoute)
    {
        $strMainRoute = substr(UrSlugCleaner::pathFix($strMainRoute), 1);
        if (true) {
            switch ($strMainRoute) {
                case "":
                    header("Location:  " . SystemConfig::HOMEROUTE);
                    break;
                default:
                    $strRoute = "\\free2\\application\\route" . $strMainRoute;
                    $strFilename = substr(strtr($strRoute, ["\\" => "/"]) . ".php", 1);
                    if (file_exists($strFilename)) {
                        $objMainObject = new $strRoute();
                        if (isset($this->arrParameters)) {
                            $intRequiredSecurity = $objMainObject->getsecuitylevelfor($this->arrParameters);
                        } else {
                            $intRequiredSecurity = 1E10;
                        }
                        if ($intRequiredSecurity < SecurityServices::NOTLOGGEDIN) {
                            $SESSION = new Session();
                            $SESSION->validate();
                        }
                        if (SecurityServices::isMyUserLevel($intRequiredSecurity)) {
                            $objMainObject->execute($this->arrParameters);
                        } else {
                            $objMainObject->securityFailure($intRequiredSecurity);
                        }
                    }
                    break;
            }
        } else {
            self::setLocationRouter(SystemConfig::LOGINROUTE);
        }
    }

    public static function setLocationRouter($strRoute)
    {
        header("Location: " . UrSlugCleaner::fixHTTPS(self::getPathToHere() . $strRoute));
    }

    public static function turnOnCompression()
    {
        if (!core::isWindowsMachine()) {
            core::startObGzHandler();
        }
    }

    public static function clientIsValidIPAddress()
    {
        return in_array($_SERVER["REMOTE_ADDR"], SystemConfig::getAllowedIPAddresses());
    }
}