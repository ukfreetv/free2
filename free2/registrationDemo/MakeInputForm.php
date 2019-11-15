<?php

namespace free2\registrationDemo;

use view\helper\html;

class MakeInputForm extends GeneralValidation
{
    private $strEcho = "";
    private $coreInputFields;// = new CoreInputFields();


    private $ynAllValidated = false;

    public function __construct(CoreInputFields $coreInputFields)
    {
        $this->coreInputFields = $coreInputFields;
        $this->createForm();
    }

    private function createForm()
    {
        foreach ($this->coreInputFields as $id => $coreInputField) {

            $ynIsValid = true;
            $strUse = html::input($id, $coreInputField);

            switch ($id) {

                case "Salutation":
                    $arrAllowed = ["Mr", "Mrs", "Ms"];
                    $ynIsValid = $this->isThisInThisList($coreInputField, $arrAllowed);
                    $strUse = MakeOptionSelect::makeDrop($id, $arrAllowed);
                    break;

                case "Gender":
                    $arrAllowed = ["Male", "Female", "Other"];
                    $ynIsValid = $this->isThisInThisList($coreInputField, $arrAllowed);

                    $strUse = MakeOptionSelect::makeDrop($id, $arrAllowed);
                    break;

                case "Birthdate":
                    $coreInputField = "31/12/1999";
                    $strUse = html::input($id, $coreInputField);

                    break;

                case "Password":
                case "ConfirmPassword":
                    $ynIsValid=$this->isValidPassword($coreInputField);
                    $strUse = html::input($id, $coreInputField, "password");
                    break;

                case "EmailAddress":
                    $ynIsValid=$this->isValidEmailAddress($coreInputField);
                    break;

                default:
            }


            if ($ynIsValid) {
                $strIsValid = "✔";
            } else {

                $strIsValid = "no";
            }

            $this->strEcho .= html::tr(html::td(html::wrapTag($id . ":", "label")) . html::td($strUse) . html::td($strIsValid));


        }
        $this->strEcho = html::table($this->strEcho, "inputtable");
        $this->strEcho .= html::input("submit", "submit", "submit");
        $this->strEcho = html::form($this->strEcho, "form", "/demo/demo");
    }

    public function allValidated()
    {
        return $this->ynAllValidated;
    }

    public function __toString()
    {
        return $this->strEcho;
    }
}