<?php


namespace free2\registrationDemo;


use tables\tblPAF;

class PafInterface extends tblPAF
{

    private $strEcho = "";

    public function __toString()
    {
        return $this->strEcho;
    }


    public function getSampleData($strPostcode)
    {

        $a = $this->getSample($strPostcode);

        if (is_array($a)) {
            $this->strEcho = join(" ", $a);
        } else {
            $this->strEcho .= "$strPostcode NOT FOUND";
        }

    }
}