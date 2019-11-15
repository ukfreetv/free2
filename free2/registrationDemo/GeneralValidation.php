<?php


namespace free2\registrationDemo;


use view\helper\html;

class GeneralValidation
{

    public function isThisInThisList($strValue, $arrArray = [])
    {
        return in_array($strValue, $arrArray);
    }


    public function isValidPassword($strValue)
    {
        return strlen($strValue) > 0;
    }


    public function isValidEmailAddress($strValue)
    {
        return filter_var($strValue, FILTER_VALIDATE_EMAIL);

    }
}