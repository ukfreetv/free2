<?php
/**
 * Created by PhpStorm.
 * User: bbutterworth
 * Date: 03/02/2015
 * Time: 10:54
 */
namespace pseph\nff\framework;

use pseph\nff\Base;
use pseph\nff\formation\Html5View;
use view\helper\html;

class SecuityBase extends Base
{
    public function getsecuitylevelfor($arrConfiguration)
    {
//        list() = $arrConfiguration;

        return SecurityServices::MANAGER;
    }

    public function securityFailure($intRequiredSecurity)
    {
        $HTML = new Html5View();
        $HTML->setTitle("Not allowed");
        $arrNames = SecurityServices::getUserLevels();
        if (!isset($arrNames[$intRequiredSecurity])) {
            $arrNames[$intRequiredSecurity] = "?";
        }
        if (!isset($arrNames[SecurityServices::getMyUserLevel()])) {
            $arrNames[SecurityServices::getMyUserLevel()] = "?";
        }

        $strEcho = "Sorry, this requires a " . html::span($arrNames[$intRequiredSecurity], "unverified") . " but you are only a " . html::span($arrNames[SecurityServices::getMyUserLevel()], "verified") . ".  ";
        $strEcho .= html::a("Go to login page?", "/account/login/logout");

        $strEcho .= html::wrapTag("location.href='/account/login/logout';","script");

        $HTML->setBody($strEcho);
    }
}