<?php
namespace pseph\nff\framework;

use pseph\fixPhp\pre72;
use pseph\nff\Base;

class UrSlugCleaner extends Base
{
    public static function getMyServerRoot($ynMore = true)
    {
        if (isset($_SERVER["DOCUMENT_ROOT"])) {
            $strRoot = $_SERVER["DOCUMENT_ROOT"];
        } else {
            $strRoot = "";
        }
        if ($strRoot == "") {
            $arrPath = preg_split("/\//", strtr(__DIR__, [
                "\\" => "/"
            ]));
            $intC = pre72::count($arrPath);
            unset($arrPath[$intC - 1]);
            if ($ynMore) {
                unset($arrPath[$intC - 2]);
            }
            $strRoot = join("/", $arrPath);
        }

        return $strRoot;
    }

    public static function pathFix($strInput, $strExclude = "", $ynReturnExclude = false)
    {
        $arrBits = preg_split("/\?/", $strInput . "?");
        $strInput = $arrBits[0];
        $arrParameters = preg_split('/\//', $strInput);
        $strFound = "";
        $intExLen = strlen($strExclude);
        foreach ($arrParameters as $strID => $strValue) {
            if ($strValue == "")
                unset($arrParameters[$strID]);
            if ($strExclude != "") {
                if (substr($strValue, 0, $intExLen) == $strExclude) {
                    unset($arrParameters[$strID]);
                    $strFound = substr($strValue, $intExLen, 1e9);
                }
            }
        }
        if ($ynReturnExclude) {
            return $strFound;
        } else {
            return "/" . join("/", $arrParameters);
        }
    }

    public static function fileGetContentsFixFilenameNoError($strCallingClass, $strExtension = "css")
    {
        $strFilename = UrSlugCleaner::fixFilenames($strCallingClass . "." . $strExtension);
        if (file_exists($strFilename)) {
            return file_get_contents($strFilename);
        } else {
            return ("<!-- " . __FUNCTION__ . " failed to load {$strFilename} -->");
        }
    }

    public static function fixFilenames($strString)
    {
        return str_replace('\\', DIRECTORY_SEPARATOR, $strString);
    }

    public static function classToFilename($strClass)
    {
        return strtr($strClass, [
            "\\" => DIRECTORY_SEPARATOR
        ]);
    }

    public static function fixHTTPS($strURI)
    {
        if (isset($_SERVER["HTTPS"])) {
            switch ($_SERVER["HTTPS"]) {
                case "on":
                    $strURI = strtr($strURI, [
                        "http://" => "https://"
                    ]);
                    break;
                default:
                    break;
            }
        }

        return $strURI;
    }

    public static function getHTTPorHTTPSservername($strPath)
    {
        $strURLofData = "http://";
        if (isset($_SERVER["HTTPS"])) {
            switch ($_SERVER["HTTPS"]) {
                case "on":
                    $strURLofData = "https://";
                    break;
                default:
                    break;
            }
        }
        if (isset($_SERVER["SERVER_NAME"])) {
            $strURLofData .= $_SERVER["SERVER_NAME"];
        }
        if (isset($_SERVER["SERVER_PORT"])) {
            if ($_SERVER["SERVER_PORT"] != 80) {
                $strURLofData .= ":" . $_SERVER["SERVER_PORT"];
            }
        }
        if (substr($strPath, 0, 1) != "/") {
            $strPath = "/" . $strPath;
        }

        return $strURLofData . $strPath;
    }
}