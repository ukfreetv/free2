<?php
namespace pseph\nff\formation;


use pseph\nff\formation\config\HeaderConfig;


class HtmlHeader //extends ReduceOutputRedudnacy
{
    public $arrOpenGraph = [];
    public $headerShown = false;
    public $strMetaNameDescription;
    public $strTitleDefault = "Whooshmedia";
    public $strLongerTitle;
    public $strThemeColour = "#43a047";
    public $strTitle;
    public $strBody;


    public function __construct()
    {

    }

    public function showheaderExFromThisObject()
    {
        if ($this->headerShown == false) {
            $strTitleAddOn = "";
            if ($this->strTitle == "") {
                $strTitleAddOn = " " . $this->strTitleDefault;
            }
            if ($this->strMetaNameDescription == "") {
                $this->strMetaNameDescription = $this->strTitle . "";
            }
            if ($this->strMetaNameDescription == "") {
                $this->strMetaNameDescription = $this->strLongerTitle . "";
            }

//            die(__DIR__);

//            die(getcwd());

            $HEADER = new HeaderConfig();
            $HEADER->setThemeColour($this->strThemeColour);
            $this->headerShown = true;

            return $HEADER->htmlGenerateFullHeader($this->strTitle . $strTitleAddOn, $this->strMetaNameDescription . "", $this->arrOpenGraph, $this->strBody);
        } else {
            return "";
        }
    }
}
