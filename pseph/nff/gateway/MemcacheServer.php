<?php
namespace pseph\nff\gateway;


use view\helper\core;
use wooshmedia\model\MemcacheSingleton;


class MemcacheServer // extends \Memcache
{
    const CACHEMINUTES = 4320;
//    const LOCALHOST = "127.0.0.1";
//    const MEMCACHEDPORT = 11211;
    const MINSTOSECONDS = 60;
    private $strFilename;
    private $intSeconds;

    public function __construct($strFilename, $fpMinutes = self::CACHEMINUTES)
    {
//        if (core::isWindowsMachine()) {
//            $this->addServer(featuresMatrix::getRealLiveMachine(), self::MEMCACHEDPORT);
//        } else {
//            $this->addServer(self::LOCALHOST, self::MEMCACHEDPORT);
//        }
//            $GLOBALS[__CLASS__] = $this->memcache;
//        }

        $this->_connection = MemcacheSingleton::getInstance()->_connection;
        $this->strFilename = $strFilename . "_arl.live";
        $this->intSeconds = intval($fpMinutes * self::MINSTOSECONDS);
    }

    public function setMinutes($fpMinutes)
    {
        if ($fpMinutes > self::CACHEMINUTES)
            $fpMinutes = self::CACHEMINUTES;
        $this->intSeconds = intval($fpMinutes * self::MINSTOSECONDS);
    }

    public function test()
    {
        $arrStats = $this->_connection->getStats();
        $intHit = $arrStats['get_misses'];
        $intMiss = $arrStats['get_hits'];
        $fpMinutes = $arrStats['uptime'] / self::MINSTOSECONDS;
        $intBytes = $arrStats['limit_maxbytes'];

        return number_format($intHit / ($intHit + $intMiss) * 100, 2) . "% in " . number_format($fpMinutes) . "mins, " . ($intBytes / 1024 / 1024) . "MB";
    }

    public function isCacheFresh()
    {
        if (!core::isCLI()) {
            if (isset($_SERVER["REQUEST_URI"])) {
                if (strpos($_SERVER["REQUEST_URI"], "nocache") !== false) {
                    return false;
                }
            }
        }
        $strA = $this->_connection->get($this->strFilename);

        return $strA !== false;
    }

    public function getThis()
    {
        return $this->_connection->get($this->strFilename);
    }

    public function save($strData)
    {
        $this->_connection->set($this->strFilename, $strData, 0, $this->intSeconds);
    }
}