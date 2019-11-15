<?php
/**
 * Created by PhpStorm.
 * User: Briantist
 * Date: 08/01/2016
 * Time: 09:54
 */
namespace pseph\nff\formation;

use pseph\nff\Base;
use view\helper\core;


class CsvBase extends Base
{
    private $strEcho = "";
    private $strFilename = "csv_file.csv";

    public function __construct($strFilename)
    {
        $this->strFilename = $strFilename;
    }

    public function set($strEcho)
    {
        $this->strEcho .= $strEcho . PHP_EOL;
    }

    public function __destruct()
    {
        if ($this->strEcho != "") {
            core::setCSV($this->strFilename);
            echo $this->strEcho;
        }
    }
}