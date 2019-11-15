<?php
/**
 * Created by PhpStorm.
 * User: Briantist
 * Date: 12/01/2017
 * Time: 17:02
 */

namespace view\helper;


use pseph\nff\formation\SpecilisedHtmlHeaders;

class xml extends core
{

    const COMPRESSXML = true;

    public static function setXML($intSeconds = 29)
    {
        if (self::COMPRESSXML)
            if (!core::isWindowsMachine()) {
                core::startObGzHandler();
            }
        SpecilisedHtmlHeaders::outputHTTPheaders($intSeconds, "application/xml; charset=utf-8");


        echo "<?xml version=\"1.0\" encoding=\"ISO-8859-1\" ?>\n";


    }

    public static function wrapTag($srtWhat, $strTag, $strParams = "")
    {
        if ($strParams != "") {
            $strParams = " " . $strParams;
        }
        if (is_array($srtWhat)) {
            $srtWhat = "[]";
        }
        if (is_object($srtWhat)) {
            $srtWhat = "object";
        }

        return "<{$strTag}{$strParams}>{$srtWhat}</{$strTag}>";
    }
}