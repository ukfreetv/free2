<?php
namespace tables;

use pseph\nff\framework\SecurityServices;
use pseph\nff\gateway\Database;

class tblUsers extends Database
{
    const UNSUBSCRIBEDDATE = '2099-12-31 00:00:00';

    public function __construct()
    {
        parent::__construct(__CLASS__);
    }

    public static function getUserLevels()
    {
        return SecurityServices::getUserLevels();
    }

    public function getHideFromEditor()
    {
        return [
            "strUsername",
            "strPassword",
            "intUserLevelxxx",
            "intEmailStatus",
            "dtNextEmailSend",
            "strConfimCode",
            "intHours",
            "dtLastEmailSent",
            "intUserLevel", "ynConfirmed"
        ];
    }

    public function getSingleUserData($intUserID)
    {
        if ($intUserID != "") {
            $strSQL = "SELECT intUserLevel FROM {$this->strTablename} WHERE intUserID=$intUserID";

//            $intUserLevel = $this->getOne($strSQL, "intUserLevel");
            return $this->getOne($strSQL, "intUserLevel");
        } else {
            return SecurityServices::NOTLOGGEDIN;
        }
    }

    public function getEmptyRowForSignup($intUserLevel)
    {
        return [
            "strPortalAuthorisationKey" => "Portal authoirsation key",
            "strFirstname"              => "firstname",
            "strSurname"                => "surname",
            "strEmail"                  => "email-address",
            "intUserLevel"              => $intUserLevel,
            "strPassword1"              => "password",
            "strPassword2"              => "confirm password",
            "strPickaUsername"          => "pickplease"
        ];
    }

    public function getEmptyRowForChangePassword($intUserLevel)
    {
        return [
//            "strFirstname"     => "firstname",
//            "strSurname"       => "surname",
//            "strEmail"         => "email-address",
            "intUserLevel" => $intUserLevel,
            "strPassword1" => "password",
            "strPassword2" => "confirm password",
//            "strPickaUsername" => "pickplease"
        ];
    }

    public function getIDfromEmail($strEmail)
    {
        $this->quote($strEmail);
        $strSQL = "SELECT  intUserID  FROM {$this->strTablename} WHERE `strEmail` LIKE '$strEmail' ";

        return $this->getOne($strSQL, "intUserID");
    }

    public function getEmailfromID($intUserID)
    {
        $this->quote($strEmail);
        $strSQL = "SELECT  strEmail  FROM {$this->strTablename} WHERE `intUserID`  = $intUserID ";

        return $this->getOne($strSQL, "strEmail");
    }

    public function getAllUserData($strWhere)
    {
        $strSQL = "SELECT *  FROM {$this->strTablename}  {$strWhere} ORDER BY strFirstname";

        return $this->getAllRows($strSQL);
    }

    public function getOneRow($intUserID)
    {
        return $this->getOneEvent($intUserID);
    }

    public function getOneEvent($intUserID)
    {
        $strSQL = "SELECT " . self::getAllUNIXfields() . " FROM  {$this->strTablename}  WHERE intUserID={$intUserID}";

        return $this->getSingleRow($strSQL);
    }

    public function validatelogin($strUsername, $strPassword)
    {
        $strSQL = "SELECT intUserID FROM  {$this->strTablename} WHERE strUsername=\"$strUsername\" AND strPassword=\"" . self::ssCrypt($strPassword) . "\"";
//        $strSQL = "SELECT intUserID FROM  `tblUsers` WHERE strUsername=\"$strUsername\" ";// AND strPassword=\"" . self::ssCrypt($strPassword) . "\"";
        $intAnswer = $this->getOne($strSQL, "intUserID");

        return $intAnswer;
    }

    public function getCountByLike($strField, $strValue)
    {
        $this->quote($strValue);
        $strSQL = "SELECT COUNT(intUserID) as intCount  FROM {$this->strTablename} WHERE $strField LIKE '{$strValue}'";

        return $this->getOne($strSQL, "intCount");
    }

    public function changePasswordForUser($strUsername, $strPassword)
    {
        $strSQL = "UPDATE {$this->strTablename}    SET strPassword=\"" . self::ssCrypt($strPassword) . "\" WHERE strUsername=\"$strUsername\" LIMIT 1 ";
        $this->mysqlQuery($strSQL);
    }

    public function changePasswordForUserID($intUserID, $strPassword)
    {
        $strSQL = "UPDATE {$this->strTablename}    SET strPassword=\"" . self::ssCrypt($strPassword) . "\" WHERE intUserID={$intUserID} LIMIT 1 ";
        $this->mysqlQuery($strSQL);
    }

    function email_is_subscriber($intUserID)
    {
        return $this->getOne("SELECT (`dtNextEmailSend`<>'" . self::UNSUBSCRIBEDDATE . "') as intC FROM  {$this->strTablename}  WHERE `intUserID` = $intUserID", "intC");
    }

    public function email_getcode($intUserID)
    {
        return $this->getOne("SELECT `strConfimCode` FROM {$this->strTablename} WHERE `intUserID` =  $intUserID ", "strConfimCode");
    }

    function email_writecode($intUserID, $strCode)
    {
        $strSQL = "UPDATE {$this->strTablename} SET `strConfimCode`='$strCode' WHERE `intUserID` = $intUserID";
        $this->mysqlQuery($strSQL);
    }

    public function ems_settiming($intUserID, $intHours)
    {
        $this->mysqlQuery("UPDATE {$this->strTablename} SET `intHours`=$intHours WHERE `intUserID`=$intUserID ");
        $this->ems_setConfirmed($intUserID);
        $this->mysqlQuery("UPDATE {$this->strTablename} SET `dtLastEmailSent`=NOW() WHERE `intUserID`=$intUserID AND `dtLastEmailSent`='0000-00-00 00:00:00' ");
        $this->mysqlQuery("UPDATE {$this->strTablename} SET  dtNextEmailSend=DATE_ADD(NOW(), INTERVAL `intHours`*60-5 MINUTE) WHERE `intUserID`=$intUserID ");
    }

    public function ems_setConfirmed($intUserID)
    {
        $strSQL = "UPDATE {$this->strTablename} SET `ynConfirmed`=TRUE WHERE `intUserID`=$intUserID ";
        $this->mysqlQuery($strSQL);
    }

    public function ems_cancelemails($intUserID)
    {
        $strSQL = "UPDATE  {$this->strTablename}  SET dtNextEmailSend='" . self::UNSUBSCRIBEDDATE . "' WHERE `intUserID`=$intUserID ";
        $this->mysqlQuery($strSQL);
    }

    public function searchForIDbyEmailorUsername($strUsername)
    {
        $this->quote($strUsername);
        $strSQL = "SELECT intUserID FROM {$this->strTablename} WHERE strUsername='{$strUsername}' or strEmail='{$strUsername}'";

        return intval($this->getOne($strSQL, "intUserID"));
    }

    public function insertByArray($arrValues)
    {
        foreach ($arrValues as $strKey => $strValue) {
            if (is_string($strValue)) {
                $this->quote($arrValues[$strKey]);
            }
        }
        if (isset($arrValues["strPassword1"])) {
            $arrValues["strPassword"] = self::ssCrypt($arrValues["strPassword1"]);
            unset($arrValues["strPassword1"]);
            unset($arrValues["strPassword2"]);
        }

        if (isset($arrValues["strPickaUsername"])) {
            $arrValues["strUsername"] = $arrValues["strPickaUsername"];
            unset($arrValues["strPickaUsername"]);
        }
        $strSQL = "INSERT INTO {$this->strTablename}  (`" . join("`,`", array_keys($arrValues)) . "`) VALUES ('" . join("','", $arrValues) . "')";


//        var_dump($strSQL);

        return $this->mysqlQuery($strSQL);
    }
}