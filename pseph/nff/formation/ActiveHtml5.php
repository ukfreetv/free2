<?php

namespace pseph\nff\formation;

use pseph\nff\framework\UrSlugCleaner;
use view\helper\html;
use view\spinner;

class ActiveHtml5 extends Html5View
{
    public $strStartAjax = "";

    public function __construct($strNewStart = "")
    {
        parent::__construct();
        $this->strStartAjax = "'{$strNewStart}'";
        $this->loadJS();
        $this->createCSS();
    }

    private function loadJS()
    {
        $this->setJS(UrSlugCleaner::fileGetContentsFixFilenameNoError(__CLASS__, "js"));
    }

    public function createCSS()
    {
    }

    public function setUpdater($ynFooter = true)
    {


        $this->setJSafter(" AI.materialAJAX({$this->strStartAjax});");
//        $this->setBody(html::div("<div class=\"\">" . spinner::spin() . ".</a></div>", "robinout", "divAJAXmaterial"));

        $this->setBody(html::div("<div class=\"\">" .  ".</a></div>", "robinout", "divAJAXmaterial"));



        if ($ynFooter) {
            $this->setFooter("basicWords::FOOTERMESSAGE", "basicWords::FOOTERCOPYRIGHT");
        }
    }
}
