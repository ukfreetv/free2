<?php
/**
 * Created by PhpStorm.
 * User: Briantist
 * Date: 12/01/2017
 * Time: 17:04
 */

namespace view\helper;


class core
{

    public static function isWindowsMachine()
    {
        return DIRECTORY_SEPARATOR == "\\";
    }

    public static function startObGzHandler()
    {
        if (!in_array('ob_gzhandler', ob_list_handlers())) {
            if (isset($_SERVER['HTTP_ACCEPT_ENCODING'])) {
                if (substr_count($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip')) {
                    ob_start('ob_gzhandler');
                }
            }
        }
    }

    public static function setCSV($strFilename = "demo.csv")
    {
        header("Content-type: text/sv");
        header("Content-Disposition: attachment; filename=\"{$strFilename}\"");
    }

    public static function setJSON()
    {
        header('Content-Type: application/json');
    }

    public static function Sigma($intItems)
    {
        return html::pGap() . "Total of " . core::ss($intItems) . ".";
    }

    public static function ss($intHowmany, $strSingular = "", $strNotSingular = "", $strZero = "")
    {
        if ($strSingular == "")
            $strSingular = "item";
        if ($strNotSingular == "")
            $strNotSingular = $strSingular . "s";
        if ($strZero == "")
            $strZero = $strNotSingular;
        switch (intval($intHowmany)) {
            case 1:
                return "one $strSingular";
                break;
            case 0:
                return "no  $strZero";
                break;
            default:
                return $intHowmany . " " . $strNotSingular;
        }
    }

    public static function decToFraction($float)
    {
        $whole = floor($float);
        $decimal = $float - $whole;
        $leastCommonDenom = 10;
        $denominators = [
            2,
            3,
            4,
            5,
            6,
            7,
            8,
            9,
            10,
            25
        ];
        $denom = 1;
        $roundedDecimal = round($decimal * $leastCommonDenom) / $leastCommonDenom;
        if ($roundedDecimal == 0)
            return $whole;
        if ($roundedDecimal == 1)
            return $whole + 1;
        foreach ($denominators as $d) {
            if ($roundedDecimal * $d == floor($roundedDecimal * $d)) {
                $denom = $d;
                break;
            }
        }
//        return ($whole == 0 ? '' : $whole) . " " . ($roundedDecimal * $denom) . "/" . $denom;
        if ($whole == 0) {
            return '';
        } else {
            return $whole . " " . ($roundedDecimal * $denom) . "/" . $denom;
        }
    }

    public static function asMHz($fpChannel, $strOfset = "")
    {
        $fpOfset = 0;
        if ($strOfset == "+")
            $fpOfset = 166.67 / 1000;
        if ($strOfset == "-")
            $fpOfset = -166.67 / 1000;

        return (" (" . number_format($fpChannel * 8 + 306 + $fpOfset, 1) . "MHz)");
    }

    public static function niceHz($fpHertz)
    {
        if ($fpHertz > 1E7)
            return number_format($fpHertz / 1E6, 1) . "MHz";

        return number_format($fpHertz / 1E3, 0, "", "") . "kHz";
    }

    public static function echo_cli($strArgument)
    {
        if (self::isCLI()) {
            echo $strArgument;
        }
//        } else {
//        GlobalDB::addLog($strArgument);
//        }
    }


    public static function isCLI()
    {
        return php_sapi_name() == "cli";
    }
}