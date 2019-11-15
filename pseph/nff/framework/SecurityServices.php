<?php
namespace pseph\nff\framework;

use pseph\nff\services\Session;
use tables\tblSessions;

class SecurityServices //extends tblSessions
{
    const GODUSER = 1;
    const SUPERUSER = 2;
    const MANAGER = 3;
    const ADMINISTRATOR = 4;
    const USERUSER = 5;
    const VIEWER = 6;

    const VALIDIP = 500;

    const NOTLOGGEDIN = 1000;
    const LOGGEDOUT = 999;

    public static function getUserLevels()
    {
        return [
            self::GODUSER       => "God",
            self::SUPERUSER     => "Superuser",
            self::MANAGER       => "Manager",
            self::ADMINISTRATOR => "Administrator",
            self::USERUSER      => "User",
            self::VIEWER        => "Admin Viewer",
            self::VALIDIP       => "Valid IP",
            self::NOTLOGGEDIN   => "Site visitor",
            self::LOGGEDOUT     => "Logged out"
        ];
    }

    public static function getUserLevelImages()
    {
        return [
            self::GODUSER         => "24god",
            self::SUPERUSER       => "24superuser",
            self::MANAGER         => "24account",
            self::ADMINISTRATOR   => "24accountsquare",
            self::USERUSER        => "24user",
            self::VIEWER          => "24user",
            self::NOTLOGGEDIN     => "24notin",
            self::NOTLOGGEDIN - 1 => "24notin",
            self::VALIDIP         => "24notin",

        ];
    }

    public static function getMyUserID()
    {

        Session::startSessionIfNotStarted();
        $tblSessions = new tblSessions();

        return $tblSessions->getMyUserIDFromSession();
    }

    public static function isMyUserLevel($intRequiredSecurity)
    {
        return ($intRequiredSecurity >= SecurityServices::getMyUserLevel());
    }

    public static function getMyUserLevel()
    {
        $TABLEINFO = new tblSessions();
        $intNewID = intval($TABLEINFO->getThisUserFromSession());
        if ($intNewID == 0) {
//            if (routetoRouter::clientIsValidIPAddress()) {
//                return self::VALIDIP;
//            } else {
            return self::NOTLOGGEDIN - 1;
//            }

        } else {
            return $intNewID;
        }
    }
}