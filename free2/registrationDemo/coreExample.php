<?php

namespace free2\registrationDemo;

use pseph\nff\formation\Html5View;
use tables\tblUserdata;
use view\helper\html;
use view\StaticValues;

class coreExample
{
    public static function demoEntryPoint()
    {
        $html5View = new Html5View();
        $html5View->setCSS(file_get_contents("styles/free2.css"));
        $html5View->setHeader("<link href=\"https://fonts.googleapis.com/css?family=Open+Sans&display=swap\" rel=\"stylesheet\">");
        $html5View->setTitle(StaticValues::NAMEOFAPP);
        $coreInputFields = new CoreInputFields();


        foreach ($_POST as $id => $value) {
            if (isset($coreInputFields->$id)) {
                $coreInputFields->$id = $value;
            }
        }

        $makeInputForm = new MakeInputForm($coreInputFields);


        /*
         *    Code should be validating
         *
         */
        if ($makeInputForm->allValidated()) {
            $tblUserdata = new tblUserdata();
            $tblUserdata->simpleInsert($coreInputFields->Salutation, $coreInputFields->Gender, $coreInputFields->Birthdate, $coreInputFields->Firstname, $coreInputFields->Surname, $coreInputFields->EmailAddress,
                $coreInputFields->Password, $coreInputFields->Postcode, $coreInputFields->HouseNumber);

        } else {
            $html5View->setBody(html::h(StaticValues::NAMEOFAPP));
            $html5View->setBody($makeInputForm);


            $paf=new PafInterface();


            $paf->getSampleData($coreInputFields->Postcode);

            $html5View->setBody(html::pGap(). "PAF returns postcode " .$paf);


        }

    }
}