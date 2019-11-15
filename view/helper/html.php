<?php

namespace view\helper;

use pseph\nff\framework\RouteToRouter;

class html extends xml
{
    const AFTERHEADER = "";//<p></p><p></p><p>&nbsp;</p><p>&nbsp;</p>";

    public static function a_route($strWhat, $strURL, $strClass = "special", $strOnClick = "")
    {
        return self::a($strWhat, RouteToRouter::getPathToHere() . $strURL, $strClass, $strOnClick);
    }

    /** @noinspection PhpTooManyParametersInspection
     * @param $strWhat
     * @param $strURL
     * @param string $strClass
     * @param string $strOnClick
     * @param string $strRel
     * @param string $strTitle
     * @return string
     */
    public static function a($strWhat, $strURL, $strClass = "special", $strOnClick = "", $strRel = "", $strTitle = "")
    {
        if ($strOnClick != "")
            $strOnClick = " onclick=\"{$strOnClick}\" ";
        if ($strRel != "") {
            $strOnClick .= " rel='{$strRel}'";
        }
        if ($strTitle != "") {
            $strOnClick .= " title='{$strTitle}'";
        }

        return "<a href=\"{$strURL}\" class=\"{$strClass}\"{$strOnClick}>{$strWhat}</a>";
    }

    public static function float_LR($strLeft, $strRight)
    {
        return html::div($strLeft, "floatl") . html::div($strRight, "floatr") . html::div("", "floatc");
    }

    public static function div($strWhat, $strClass, $strID = "")
    {
        if ($strID != "") {
            $strID = " id=\"{$strID}\" ";
        }

        return "<div class=\"{$strClass}\" {$strID} >{$strWhat}</div>";
    }

    public static function button($strWhat, $strClass)
    {
        return "<button class=\"{$strClass}\">{$strWhat}</button>";
    }

    public static function div_Click($strRoundWhat, $strDivClass = "", $strDIVID = "", $strOnClick = "")
    {
        if ($strOnClick != "")
            $strOnClick = " onclick=\"{$strOnClick}\" ";

        return "<div class=\"{$strDivClass}\" id=\"{$strDIVID}\" {$strOnClick}>{$strRoundWhat}</div>";
    }

    public static function div_IdClick($strWhat, $strID, $strStyle = "", $strClass = "", $strOnClick = "")
    {
        if ($strOnClick != "")
            $strOnClick = " onclick=\"{$strOnClick}\" ";

        return "<div id=\"$strID\" style=\"$strStyle\" class=\"$strClass\" {$strOnClick}>$strWhat</div>";
    }

    public static function span_Blue($strWhat, $strColour = "Blue")
    {
        return "<span class=\"new{$strColour}bg newtag\">{$strWhat}</span>";
    }

    public static function form($strWhat, $strID, $strAction = "#", $strOnChange = "")
    {
        return "<form action=\"{$strAction}\" id=\"" . $strID . "\" style=\"display: inline;\" method=\"POST\"  onchange=\"{$strOnChange}\"   >{$strWhat}</form>";
    }

    public static function h($strWhat, $intHlevel = 1, $strClass = "")
    {
        if ($strClass != "")
            $strClass = "class='{$strClass}' ";

        return xml::wrapTag($strWhat, "h" . $intHlevel, $strClass);
    }

    public static function pre($strIN)
    {
        return xml::wrapTag($strIN, "pre");
    }

    public static function img($strURL, $strClass = "", $strAlt = "")
    {
        return "<img src=\"" . $strURL . "\"  class=\"{$strClass}\" alt=\"{$strAlt}\">";
    }

    public static function head($strWhat)
    {
        return xml::wrapTag($strWhat, "head");
    }

    public static function body($strWhat)
    {
        return xml::wrapTag($strWhat, "body");
    }

    public static function br()
    {
        return PHP_EOL . "<br>";
    }

    public static function b($strWhat)
    {
        return xml::wrapTag($strWhat, "b");
    }

    public static function i($strWhat)
    {
        return xml::wrapTag($strWhat, "i");
    }

    public static function html($strWhat)
    {
        return xml::wrapTag($strWhat, "html");
    }

    public static function script($strURL, $strWhat = "")
    {
        if ($strURL != "") {
            $arrBits = urlGetContents::ParseURLfilled($strURL);
            if ($arrBits["host"] == "") {
                $strWhat = "\n/* $strURL */\n" . file_get_contents($strURL);
            } else {
                return xml::wrapTag("", "script", "type=\"text/javascript\" src=\"{$strURL}\"");
            }
        }

//        return xml::wrapTag($strWhat, "script", "type=\"text/javascript\"");
        return xml::wrapTag($strWhat, "script", "");


    }

    public static function input($strNameAndID, $strValue, $strType = "text", $strClass = "editbox", $strOnClick = "")
    {
        $strMore = "";
        if ($strType == "text") {
            $strMore = "size=\"" . strlen($strValue) . "\" ";
        }
        if ($strOnClick != "") {
            $strMore .= " onclick=\"{$strOnClick}\" ";
        }

        return "<input type=\"{$strType}\" name=\"{$strNameAndID}\" id=\"{$strNameAndID}\" value=\"{$strValue}\" class=\"{$strClass}\" {$strMore}>";
    }

    public static function crtobr($strA)
    {
        return strtr($strA, ["\n" => "<br>"]);
    }

    public static function space()
    {
        return "&nbsp;";
    }

    public static function pGap()
    {
        return "<p></p>";
    }

    public static function dumpArrayAsTable($arrRows)
    {
        $arrNice = [];
        foreach ($arrRows as $strKey => $srtValue) {
            $arrNice[] = self::tr(self::td($strKey) . self::td($srtValue));
        }

        return self::table(join("", $arrNice), "");
    }

    public static function tr($strWhat)
    {
        return xml::wrapTag($strWhat, "tr");
    }

    public static function td($strWhat, $strClass = "", $strMore = "")
    {
        return xml::wrapTag($strWhat, "td", "class=\"{$strClass}\" $strMore");
    }

    public static function table($strWhat, $strClass, $strExtraParams = "")
    {

        return xml::wrapTag($strWhat, "table", "class=\"{$strClass}\" {$strExtraParams}");
    }

    public static function listToColumns($arrList, $intColumns = 3)
    {
        $intCount = pre72::count($arrList);
        $intPerCol = intval($intCount / $intColumns) + 1;
        $strPCC = intval(100 / $intColumns) . "%";
        $intLoop = 0;
        $strEcho = "<table style=\"width:100%\"><tr>";
        foreach ($arrList as $strShow) {
            if ($intLoop == 0)
                $strEcho .= "<td style=\"width:{$strPCC};vertical-align:top;\"><ul>";
            $strEcho .= "<li>" . $strShow;
            $intLoop += 1;
            if ($intLoop >= $intPerCol)
                $intLoop = 0;
        }
        $strEcho .= "</ul><td></tr></table>";

        return $strEcho;
    }

    public static function a_Target($strWhat, $strURL, $strTarget)
    {
        return "<a href=\"{$strURL}\"  target=\"{$strTarget}\">{$strWhat}</a>";
    }

    public static function arraytotableEx($arrA, $strTableStyle = "onehundredpercent", $strID = "")
    {
        $strTmpHeader = "";
        $strEcho = "";
        $arrR = [];
        $ynMins = false;
        if (is_array($arrA)) {
            foreach ($arrA as $arrR) {
                $strTmpHeader = "<tr><th>" . join("</th><th>", array_keys($arrR)) . "</th>";
                $strRow = "";
                if (is_array($arrR)) {
                    $strRow = "\n<tr><td>" . join("</td><td>", $arrR) . "</td></tr>";
                }
                $ynMins = in_array("mins", array_keys($arrR));
                if ($ynMins) {
                    $strRow = self::rightAlignLastTD($strRow);
                }
                $strEcho .= $strRow;
            }
        }
        if (in_array("mode", array_keys($arrR))) {
            $strTmpHeader = "";
        }
        if ($ynMins) {
            $strTmpHeader = self::rightAlignLastTD($strTmpHeader, "th");
        }

        if ($strTableStyle == "noheader") {
            $strTmpHeader = "";
        }

        return " <table class=\"{$strTableStyle}\" id=\"{$strID}\">{$strTmpHeader}{$strEcho}</table>";
    }

    public static function rightAlignLastTD($strRow, $strTag = "td")
    {
        $intPos = strrpos($strRow, "<{$strTag}>");
        if ($intPos !== false) {
            return substr($strRow, 0, $intPos) . " <{$strTag} class=\"ra\">" . substr($strRow, $intPos + 4);
        } else {
            return $strRow;
        }
    }

    public static function bigText($strText, $strMoreClass = " ")
    {
        return html::span($strText, "new" . editoricons::EDITCOLOR . "  midtitle $strMoreClass");
    }

    /*
     *    New helpers from the TitanEventsAdmin project
     *    for the DART Loading code.
     *
     */
    public static function span($strWhat, $strClass, $strID = "")
    {
        if ($strID != "")
            $strID = " id=\"{$strID}\"";

        return "<span class=\"{$strClass}\"{$strID}>{$strWhat}</span>";
    }
}