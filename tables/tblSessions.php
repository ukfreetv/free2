<?php

namespace tables;

use pseph\nff\gateway\Database;

class tblSessions extends Database
{
    public function __construct()
    {
        parent::__construct(__CLASS__);
    }

    function shop_sessions_setsession($intUserID, $strMoreTime)
    {
        $strSessionID = session_id();
        $this->mysqlQuery("DELETE FROM tblSessions WHERE strSessionID=\"{$strSessionID}\" or intUserID=$intUserID ");
        $this->mysqlQuery("INSERT INTO tblSessions (strSessionID, intUserID, dtTimeout) VALUES (\"" . $strSessionID . "\", $intUserID, date_add(now(), INTERVAL " . $strMoreTime . ")) ");
    }

    function shop_sessions_housekeep()
    {
        $this->mysqlQuery("DELETE FROM tblSessions WHERE dtTimeout < Now() ");
    }

    public function isLoggedinForSessionID()
    {
        return $this->getOne("SELECT intUserID FROM tblSessions WHERE strSessionID=\"" . session_id() . "\"", "intUserID");
    }

    public function housekeep()
    {
        $this->mysqlQuery("UNLOCK TABLES");
        if (true) {
            $this->mysqlQuery("DELETE FROM {$this->strTablename} WHERE dtTimeout < Now() ");
        }
    }

    public function getIDforSession()
    {
        return $this->getOne("SELECT intUserID FROM {$this->strTablename} WHERE strSessionID=\"" . session_id() . "\"", "intUserID");
    }

    public function logout()
    {
        $this->mysqlQuery("DELETE FROM {$this->strTablename} WHERE strSessionID='" . session_id() . "' ");
    }

    public function setsession($intUserID, $strMoreTime, $strSessionID)
    {
        $strSQLd1 = "DELETE FROM {$this->strTablename} WHERE strSessionID=\"{$strSessionID}\"  ";
        $strSQLd2 = "DELETE FROM {$this->strTablename} WHERE intUserID=$intUserID ";
        $strSQLi = "INSERT INTO {$this->strTablename} (strSessionID, intUserID, dtTimeout) VALUES (\"{$strSessionID}\",{$intUserID}, date_add(now(), INTERVAL {$strMoreTime})) ";
        $this->mysqlQuery($strSQLd1);
        $this->mysqlQuery($strSQLd2);
        $this->mysqlQuery($strSQLi);
    }

    public function getThisUserFromSession()
    {
        $intUserID = $this->getMyUserIDFromSession();
        $TBLUSERS = new tblUsers();

        return $TBLUSERS->getSingleUserData($intUserID);
    }

    public function getMyUserIDFromSession()
    {
        if (session_id() != "") {
            return $this->getOne("SELECT `intUserID` FROM {$this->strTablename} WHERE `strSessionID` LIKE '" . session_id() . "'", "intUserID");
        } else {
            return "";
        }
    }

    public function validatelogin($strUsername, $strPassword)
    {
        $USERSTABLE = new tblusers();

        return $USERSTABLE->validatelogin($strUsername, $strPassword);
    }
}