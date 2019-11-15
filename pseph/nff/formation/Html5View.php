<?php

namespace pseph\nff\formation;

use pseph\nff\formation\config\Piwik;
use pseph\nff\gateway\GlobalDbLoggerSystem;
use view\helper\html;
use view\helper\xml;

class Html5View extends HtmlHeader
{
    const DOCTYPE = "html";
    const HTMLSCHEMA = "http://schema.org/NewsArticle";
    public $strBody = "";
    public $strTitle = "";
    public $strFooterLeft = "";
    public $strFooterRight = "";
    private $strCSS = "";
    private $strJS = "";
    private $strJSafter = "";
    private $strHeader = "";
    private $strRedirect = "";
    private $strFloatingBodyWidth = 1170;
    private $arrScripts = [];

    public function __construct()
    {
        parent::__construct();
    }

    public function strFloatingBodyWidth($strFloatingBodyWidth)
    {
        $this->strFloatingBodyWidth = $strFloatingBodyWidth;
    }

    public function setBody($strWhat)
    {
        $this->strBody .= $strWhat;
    }

    public function flipBodyToAjax()
    {
        $this->strBody = html::div($this->strBody, "robinout", "divAJAXmaterial");
    }

    public function setFooter($strWhat, $strWhatElse)
    {
        $this->strFooterLeft .= $strWhat;
        $this->strFooterRight .= $strWhatElse;
    }

    public function setJS($strWhat)
    {
        $this->strJS .= $strWhat;
    }

    public function setJSload($strWhat)
    {
        $this->arrScripts[] = $strWhat;
    }

    public function setJSafter($strWhat)
    {
        $this->strJSafter .= $strWhat;
    }

    public function setTitle($strWhat)
    {
        if ($strWhat != $this->strTitle)
            $this->strTitle .= $strWhat;
    }

    public function setHeader($strWhat)
    {
        $this->strHeader .= $strWhat;
    }

    public function setRedirect($strWhat)
    {
        $this->strRedirect = $strWhat;
    }

    public function addCSSbyArray($arrArray)
    {
        foreach ($arrArray as $strIs => $strWas) {
            $this->addCSS($strIs, $strWas);
        }
    }

    public function addCSS($strItem, $strDefinition)
    {
        $this->strCSS .= PHP_EOL . $strItem . " {" . $strDefinition . "}";
    }


    private $intCacheSeconds = 2;

    public function setCacheTimeForPage($intCacheSeconds)
    {
        $this->intCacheSeconds = $intCacheSeconds;

    }


    public function __destruct()
    {
        if ($_SERVER['DOCUMENT_ROOT'] != "") {
//            var_dump($_SERVER['DOCUMENT_ROOT']);
            chdir($_SERVER['DOCUMENT_ROOT']);
        }
        $strSiteInfoFOrTitle = "Whooshmedia";
        if ($this->strRedirect != "") {
            header("Location: " . $this->strRedirect);
        } else {
            SpecilisedHtmlHeaders::outputHTTPheaders($this->intCacheSeconds);
            if (false) {
                $this->strCSS = $this->cssCompress($this->strCSS);
                $this->strJS = $this->jsCompress($this->strJS);
                $this->strJSafter = $this->jsCompress($this->strJSafter);
                $this->strBody = $this->htmlCompress($this->strBody);
            }
//            $this->getResponsiveAutoCode();
            $strHeader = $this->showheaderExFromThisObject();
            if (file_exists("formation/cookieconsent.html")) {
                if (isset($_COOKIE["cookieconsent_dismissed"])) {
                    if ($_COOKIE["cookieconsent_dismissed"] !== "yes") {
                        $strHeader .= file_get_contents("formation/cookieconsent.html");
                    } else {
                        $strHeader .= "<!-- cookieconsent_dismissed -->";
                    }
                } else {
                    $strHeader .= file_get_contents("formation/cookieconsent.html");
                }
            }


            foreach ($this->arrScripts as $strURL) {
                $strHeader .= xml::wrapTag("", "script", "src=\"{$strURL}\"");
            }
            $strHeader .= Piwik::getAlteriveScript();


            $globalDB = new GlobalDbLoggerSystem();
            $this->strBody .= $globalDB->getLog();
            echo $this->outputStructure($strSiteInfoFOrTitle, $strHeader);
        }
    }

    public function getResponsiveAutoCode()
    {
        $strTemp = $this->getResponsiveAutoCodeRAW($this->strFloatingBodyWidth);

        return $strTemp;
    }

    public function getResponsiveAutoCodeRAW($intPixelWidth = 1170)
    {
        $this->addCSS(".automargin", "margin-left: auto; margin-right: auto;");
        $this->addMediaScreen("supersuper", "calc(100% - 40px)", $intPixelWidth + 40, "{$intPixelWidth}px");
    }

    private function addMediaScreen($strClassname = "supersuper", $strNormalCalc = "calc(100%-40px)", $intMinWidth = 880, $strOtherCalc = "840px")
    {
        $this->setCSS(".{$strClassname} {width: {$strNormalCalc} } @media screen and (min-width:{$intMinWidth}px) {.{$strClassname} {width: $strOtherCalc} }");
    }

    public function setCSS($strWhat)
    {
        $this->strCSS .= $strWhat;
    }

    public function outputStructure($strSiteInfoFOrTitle, $strHeader)
    {
        $strFullHead =
            HeadHelper::metaCharset() .
            HeadHelper::metaName("viewport", "width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no") .
            xml::wrapTag($this->strTitle . " | " . $strSiteInfoFOrTitle, "title") .
            $strHeader .
            html::script("", $this->strJS) .
            xml::wrapTag($this->strCSS, "style");
        $strFullBody =
            xml::wrapTag($this->strHeader, "header") .
            html::div($this->strBody, "supersuper automargin", "content") .
            xml::wrapTag($this->buildGeneralFooter(), "footer", "id='footer'") .
            html::script("", $this->strJSafter);// . "<script async defer src=\"https://maps.googleapis.com/maps/api/js?key=AIzaSyAH3hLI0LliwFJV4KGYD3rdQofzqjKYNH0&callback=initMap\">";;

        return "<!doctype " . self::DOCTYPE . ">" . xml::wrapTag(xml::wrapTag($strFullHead, "head") . xml::wrapTag($strFullBody, "body"), "html", "lang=\"en\" "); // itemtype="" itemscope=""
    }

    private function buildGeneralFooter()
    {
        return html::div(html::span($this->strFooterLeft, "footerleft") . html::span($this->strFooterRight, "footerright"), "footerinner");
    }
}