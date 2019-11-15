<?php

namespace pseph\nff\formation\config;

use pseph\nff\formation\HeadHelper;

class HeaderConfig
{
    const METANAMEDESCRIPTION = "Free2";
    const HTMLTITLE = "Free2";
    const METANAMERATING = "Safe for Kids";
    const SITENAME = "Free2";
    const DEFAULTIMAGE = "/styles/images/default/about-us.jpg";
    public $strThemeColour;
    private $strSitestoreTitle = "";
    private $strContent = "";
    private $arrTwitterMeta;
    private $arrSizes = ["apple" => [57, 60, 72, 76, 114, 120, 144, 142, 160], "android" => [192]];
    private $arrListOfPrefetchDNS = ["//maps.googleapis.com", "//fonts.gstatic.com"];

    public function setThemeColour($strQQC)
    {
        $this->strThemeColour = $strQQC;
    }

    public function htmlGenerateFullHeader($strTitleExtra, $strDescription, $arrOpenGraph = [], $strContent = "")
    {
        $this->strContent = $strContent;
        $strDescription = self::striptagsandslashes($strDescription);
        if (!isset($arrOpenGraph["type"])) {
            $arrOpenGraph["type"] = "article";
        }
        $arrOpenGraph["site_name"] = self::SITENAME;
        $arrOpenGraph["description"] = $strDescription;
        if (isset($_SERVER['SERVER_NAME']) and $_SERVER['REQUEST_URI']) {
            $arrOpenGraph["url"] = "http://" . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
        } else {
            $arrOpenGraph["url"] = "";
        }
        $arrOpenGraph["username"] = "whooshmedia";
        $arrOpenGraph["title"] = $strTitleExtra;
        if (!isset($arrOpenGraph["image"])) {
            $arrOpenGraph["image"] = self::DEFAULTIMAGE;
        }
        $this->arrTwitterMeta = [
            "card" => "summary_large_image",
            "site" => "@whooshmedia",
            "creator" => "@whooshmedia"
        ];
        $this->arrTwitterMeta["title"] = $strTitleExtra;
        $this->arrTwitterMeta["description"] = $strDescription;
        if (isset($arrOpenGraph["image"])) {
            if ($arrOpenGraph["image"] != "") {
                $arrFixBack = [];
                if (isset($_SERVER['SERVER_NAME']) and $_SERVER['REQUEST_URI']) {
                    $arrOpenGraph["image"] = "http://" . $_SERVER['SERVER_NAME'] . strtr($arrOpenGraph["image"], $arrFixBack);
                }
                $this->arrTwitterMeta["image:src"] = $arrOpenGraph["image"];
                if (strpos($this->arrTwitterMeta["image:src"], ".png") !== false) {
                    $this->arrTwitterMeta["card"] = "summary";
                }
            }
        }
        $strEcho = $this->createMetaHeaders($strTitleExtra, $strDescription, $arrOpenGraph);
        $strEcho .= HeadHelper::link("canonical", $arrOpenGraph["url"]);

        return $strEcho;
    }

    private static function striptagsandslashes($strDescription)
    {
        return strtr(strip_tags($strDescription), [
            "\"" => ""
        ]);
    }

    function createMetaHeaders($strTitleExtra, $strDescription, $arrOpenGraph = [])
    {
        if (strlen($strTitleExtra) > 36) {
            $strTitleExtra = substr($strTitleExtra, 0, 36) . "&hellip;";
        }
        if ($strTitleExtra != "") {
            $strTitleExtra = "$strTitleExtra | ";
        }
        if ($strDescription == "") {
            $strDescription = self::METANAMEDESCRIPTION;
        }
        $this->strSitestoreTitle = $strTitleExtra . self::HTMLTITLE;
        $strEcho = $this->getFaviconAndAppleIcons();
        if (file_exists("manifest.json")) {
            $strEcho .= HeadHelper::link("manifest", "/manifest.json");
        }
        $strEcho .= HeadHelper::metaName("description", strip_tags($strDescription));
//        $strEcho .= HeadHelper::metaHTTPequiv("Content-Type", "text/html;charset=utf-8");
        $strEcho .= HeadHelper::meta("Rating", self::METANAMERATING);
        $strEcho .= HeadHelper::metaName("theme-color", $this->strThemeColour);
        $strEcho .= HeadHelper::meta("robots", "index,follow");
        foreach ($arrOpenGraph as $strProperty => $strContent) {
            $strEcho .= HeadHelper::meta("og:$strProperty", ($strContent));
        }
        foreach ($this->arrTwitterMeta as $strProperty => $strContent) {
            $strEcho .= HeadHelper::meta("twitter:$strProperty", ($strContent));
        }

        return $strEcho;
    }

    private function getFaviconAndAppleIcons()
    {
//        $strSystem = FeaturesMatrix::getInstallType();
        $strEcho = $this->getDNSprefetch() . HeadHelper::link("shortcut icon", "/styles/icons/favicon.ico");
        foreach ($this->arrSizes as $strPlatform => $arrPixies) {
            foreach ($arrPixies as $intPX) {
                $strFilename = "styles/icons/{$strPlatform}-icon-{$intPX}internalLoop{$intPX}.png";
                $strWhat = "apple-touch-icon";
                if ($strPlatform != "apple")
                    $strWhat = "icon";
                if (file_exists($strFilename)) {
                    $strEcho .= HeadHelper::link($strWhat, "/{$strFilename}", "{$intPX}internalLoop{$intPX}");
                }
            }
        }

        return $strEcho;
    }

    private function getDNSprefetch()
    {
        $strEcho = "";
        foreach ($this->arrListOfPrefetchDNS as $thstrHostname) {
            $strEcho .= HeadHelper::link("dns-prefetch", $thstrHostname);
        }

        return $strEcho;
    }
}