<?php


namespace tables;


use pseph\nff\gateway\Database;

class tblPAF extends Database
{
    public function __construct()
    {
        parent::__construct(__CLASS__);
    }



    public function getSample($strPostcode)

    {
        return $this->getSingleRow("SELECT * FROM {$this->strTablename} WHERE Postcode='$strPostcode'");

    }
}