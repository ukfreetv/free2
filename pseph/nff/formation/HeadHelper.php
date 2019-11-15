<?php
namespace pseph\nff\formation;

use pseph\nff\Base;

class HeadHelper extends Base
{
    public static function link($strRel, $strHref, $strSizes = "", $strType = "", $strTitle = "")
    {
        $strMore = "";
        if ($strSizes != "") {
            $strMore .= " sizes=\"{$strSizes}\"";
        }
        if ($strType != "") {
            $strMore .= " type=\"{$strType}\"";
        }
        if ($strTitle != "") {
            $strMore .= " title=\"{$strTitle}\"";
        }

        return PHP_EOL . "<link rel=\"{$strRel}\" href=\"{$strHref}\"{$strMore} />";
    }

    public static function meta($strProperty, $strContent, $strHttpequiv = "")
    {
        $strMore = "";
        if ($strHttpequiv != "") {
            $strMore .= " http-equiv=\"{$strHttpequiv}\"";
        }

        return PHP_EOL . "<meta property=\"$strProperty\" content=\"" . self::stripquotes($strContent) . "\"{$strMore} />";
    }

    private static function stripquotes($strContent)
    {
        return strtr($strContent, [
            "\"" => ""
        ]);
    }

    public static function metaHTTPequiv($strProperty, $strHttpequiv = "")
    {
        return PHP_EOL . "<meta property=\"$strProperty\"  http-equiv=\"{$strHttpequiv}\" />";
    }

    public static function metaName($strProperty, $strContent)
    {
        return PHP_EOL . "<meta name=\"$strProperty\" content=\"" . self::stripquotes($strContent) . "\" />";
    }


    public static function metaCharset($strCharset = "utf-8")
    {
        return PHP_EOL . "<meta charset=\"{$strCharset}\" />";
    }

}