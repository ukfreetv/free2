<?php
/**
 * Created by PhpStorm.
 * User: bbutterworth
 * Date: 23/01/2015
 * Time: 12:25
 */

namespace pseph\nff\framework\config;
class SystemConfig
{
    const HOMEROUTE = "/system/monitor";
    const LOGINROUTE = "/account/login";

    public static function getAllowedIPAddresses()
    {
        return ["154.58.80.38", "213.123.59.222", "194.74.1.193", "78.33.151.157", "217.138.50.162", "127.0.0.1"];
    }
}