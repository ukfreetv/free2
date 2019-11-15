<?php
/**
 * Created by PhpStorm.
 * User: Briantist
 * Date: 03/01/2017
 * Time: 13:10
 */

namespace pseph\nff\gateway;


use pseph\nff\Base;

class IsThisWritable extends Base
{
    public static function isWritable($strTablename, $intID)
    {
        $strTablename = strtr($strTablename, ["`" => ""]);
        $strObjectName = "\\a1z\\mtrCrossrail\\tables\\" . $strTablename;
        $tableData = new $strObjectName;

        return $tableData->ynWriteable($intID);
    }
}