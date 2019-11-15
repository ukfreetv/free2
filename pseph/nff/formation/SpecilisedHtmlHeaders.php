<?php
namespace pseph\nff\formation;

use pseph\nff\Base;

class SpecilisedHtmlHeaders extends Base
{
    public static function outputHTTPheaders($intSeconds_to_cache = 60, $strContentType = "text/html; charset=utf-8")
    {
        $strExpires = gmdate("D, d M Y H:i:s", time() + $intSeconds_to_cache) . " GMT";
        header("Expires: {$strExpires}");
        header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
        header("Cache-Control: max-age={$intSeconds_to_cache}");
        header("Pragma: cache");
        header("Content-type:  {$strContentType}");
    }
}
