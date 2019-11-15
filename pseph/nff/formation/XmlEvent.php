<?php

namespace pseph\nff\formation;

use pseph\nff\Base;
use pseph\nff\gateway\GlobalDbLoggerSystem;
use view\helper\xml;

class XmlEvent extends Base
{
    const OUTERTAG = "data1";
    const INNERTAG = "data4";
    const ACTION = "action";
    const WAITTIME = "waittime";
    const SQLERROR = "sqlerror";
    public $strTimeoutAction = "noaction";
    public $intTimeoutTime = 10;
    private $strEcho = "";

    public function setTimeoutAction($strTimeoutAction, $intTimeoutTime)
    {
        $this->strTimeoutAction = $strTimeoutAction;
        $this->intTimeoutTime = $intTimeoutTime;
    }

    public function get()
    {
        $strTemp = self::encodeForXML($this->strEcho);
        $this->strEcho = "";

        return $strTemp;
    }

    public static function encodeForXML($strSomething)
    {
        return strtr($strSomething, ["&" => "&amp;", "%" => "%25", "<" => "%3c", ">" => "%3e"]);
    }

    public function wrapForView($strEcho)
    {
        $globaldb = new GlobalDbLoggerSystem();
        $this->strEcho .= xml::wrapTag(
            xml::wrapTag(self::encodeForXML($strEcho), self::INNERTAG) .
            xml::wrapTag(self::encodeForXML($this->strTimeoutAction), self::ACTION) .
            xml::wrapTag(self::encodeForXML($this->intTimeoutTime), self::WAITTIME) .
            xml::wrapTag(self::encodeForXML($globaldb->getLog()), self::SQLERROR),
            self::OUTERTAG);
    }


    public function set($strEcho)
    {
        $this->strEcho .= $strEcho;
    }

    public function __destruct()
    {
        if ($this->strEcho != "") {
            xml::setXML($this->intTimeoutTime - 1);
            echo $this->strEcho;
        }
    }
}