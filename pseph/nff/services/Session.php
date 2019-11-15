<?php

namespace pseph\nff\services;

use tables\tblSessions;
use view\helper\core;
use view\helper\html;

class Session extends tblSessions
{
    const SAVEUSERNAMECOOKIE = "antidoteuid";
    const HOWLONGLOGINLASTFOR = "2 HOUR";
    private $intUserID = 0;


    public function __construct()
    {
        parent::__construct();
    }

    public static function startSessionIfNotStarted()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function validate()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        $_SESSION["now"] = microtime();
        $this->housekeep();
        $this->intUserID = $this->getIDforSession();
        if (is_null($this->intUserID) or $this->intUserID == 0) {
            session_regenerate_id();
            $this->setsession(0, "14 DAY");
            $this->intUserID = 0;
        }

        return $this->intUserID + 0;
    }

    function housekeep()
    {
        parent::housekeep();
    }

    function setsession($intUserID, $strMoreTime, $strSessionID = 0)
    {
        $strSessionID = session_id();
        parent::setsession($intUserID, $strMoreTime, $strSessionID);
    }

    function loginbox()
    {
        $strOut = "";
        if ($this->intUserID == 0) {
            $strClass = "txtMid";
            if (isset($_GET["strErrBox"])) {
                $strMessage = $_GET["strErrBox"];
                $strOut .= html::b("$strMessage") . html::br();
                $strClass = "txtMidErr";
            }
            if (isset($_COOKIE[self::SAVEUSERNAMECOOKIE])) {
                $strOlduid = $_COOKIE[self::SAVEUSERNAMECOOKIE];
            } else {
                $strOlduid = "";
            }
            $strForm = html::input("action", "loginx", "hidden");
            $strForm .= "username: " . html::input("id", $strOlduid, "text", $strClass);
            $strForm .= html::br() . "password: " . html::input("pwd", "", "password", $strClass);
            $strForm .= " " . html::input("login", "login", "submit", "savebutton") . html::br();
            $strOut .= html::form($strForm, "loginform", "/account/login");

        }
        $strOut .= html::div(html::space(), "");
        $strOut .= html::div(html::a("New user? Apply for an account", "/account/add/5"), "reg_div");
        $strOut .= html::div(html::a("Forgot password?", "/account/update"), "reg_div");


        return ($strOut);
    }

    public function logout()
    {
        parent::logout();
    }

    function validatelogin($strUsername, $strPassword)
    {
        core::echo_cli(PHP_EOL . __CLASS__ . "::" . __FUNCTION__);
        $intUserID = parent::validatelogin($strUsername, $strPassword);
        if ($intUserID != "") {
            if (session_status() == PHP_SESSION_NONE) {
                session_start();
            }
            $this->setsession($intUserID, self::HOWLONGLOGINLASTFOR);
            setcookie(self::SAVEUSERNAMECOOKIE, $strUsername, time() + 7776000);
        } else {
            if (session_status() == PHP_SESSION_NONE) {
                session_start();
            }
            $this->setsession(0, "14 DAY");
        }

        return ($intUserID);
    }
}