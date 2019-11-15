<?php


namespace tables;


use pseph\nff\gateway\Database;

class tblUserdata extends Database
{
    public function __construct()
    {
        parent::__construct(__CLASS__);
    }


    public function simpleInsert($strSalutation, $strGender, $dtBirthdate, $strFirstname, $strSurname, $strEmailAddress, $strPassword, $strPostcode, $strHouseNumber)
    {
        $strPasswordHash = self::ssCrypt($strPassword);
        $dtBirthdate = date('Y-m-d', strtotime(str_replace('-', '/', $dtBirthdate)));

        $strSQL = "INSERT INTO {$this->strTablename}  " .
            "(strSalutation,	strGender,	dtBirthdate,	strFirstname,	strSurname,	strEmailAddress,	strPasswordHash, 	strPostcode,	strHouseNumber)" .

            "VALUES ('$strSalutation',	'$strGender',	'$dtBirthdate',	'$strFirstname',	'$strSurname',	'$strEmailAddress',	'$strPasswordHash', 	'$strPostcode',	''$strHouseNumber')";


        $this->mysqlQuery($strSQL);


    }


    public static function ssCrypt($strPassword)
    {
        $strmd5 = md5(strtolower(trim($strPassword)));
        $strcrc = dechex(crc32($strPassword));
        $strV1 = md5(strlen($strcrc) . $strcrc) . md5($strmd5 . $strcrc);
        return (md5($strV1));
    }

}