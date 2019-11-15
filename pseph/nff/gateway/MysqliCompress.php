<?php
/**
 * Created by PhpStorm.
 * User: Briantist
 * Date: 22/11/2016
 * Time: 13:57
 */

namespace pseph\nff\gateway;


/*
 *   Added the use of gethostbyname to resolve the name to an address to
 *   see if will fix the occational and annoying
 *
 *  mysqli::real_connect(): (HY000/2002): No such file or directory in /var/www/html/pseph/nff/gateway/mysqliCompress.php at line X
 *
 * bnb 12-Sep-201
 *
 *  */


class MysqliCompress extends \mysqli
{

    const SLEEPRETRYSECONDS=10;

    public function __construct($host, $user, $pass, $db)
    {
        parent::init();
        $strIP = gethostbyname($host);
        if (!parent::real_connect($strIP, $user, $pass, $db, null, null, MYSQLI_CLIENT_COMPRESS)) {

//            sleep(self::SLEEPRETRYSECONDS);

//            if (!parent::real_connect($strIP, $user, $pass, $db, null, null, MYSQLI_CLIENT_COMPRESS)) {
                die('Connect Error (' . mysqli_connect_errno() . ') ' . mysqli_connect_error());
//            }
        }

    }
}