<?php

namespace pseph\nff\gateway;

use view\helper\core;

class Database
{
    const ENABLECACHE = true;
    const MUSTALWAYSLOG = false;
    const LOGMORETHANMILISECS = 200;
    const PRIMARYKEY = "intID";
    public $strTablename = "";
    public $mysqli;
    private $ynMustLog = false;
    private $strCacheFlag = "-";

//    public $queryID;
    public function __construct($strTablename, $strAltServer = "")
    {
        $db = DatabaseConnectionSingleton::getInstance();
        $this->mysqli = $db->getConnection();
        if ($strTablename !== null) {
            $arrSplit = preg_split("/\\\/", $strTablename);
            $strTablename = end($arrSplit);
            $this->strTablename = $strTablename;
        }
    }

    final public static function cleanstring($strString)
    {
        return htmlspecialchars(str_replace('\\', '', $strString), ENT_QUOTES);
    }

    final public static function postorget($strS)
    {
        if (isset($_POST[$strS])) {
            $strR = $_POST[$strS];
        } else {
            if (isset($_GET[$strS])) {
                $strR = $_GET[$strS];
            } else {
                $strR = "";
            }
        }
        return ($strR);
    }

    public static function ssCrypt($strPassword)
    {
        $strmd5 = md5(strtolower(trim($strPassword)));
        $strcrc = dechex(crc32($strPassword));
        $strV1 = md5(strlen($strcrc) . $strcrc) . md5($strmd5 . $strcrc);
        return (md5($strV1));
    }

    final public function insertID()
    {
        return $this->mysqli->insert_id;
    }

    final public function getOneCached($strSQL, $strField)
    {
        $strResult = null;
        foreach ($this->getAllRowsCached($strSQL . " LIMIT 0,1;") as $arrRow) {
            $strResult = $arrRow[$strField];
        }
        return $strResult;
    }

    final public function getAllRowsCached($strSQL)
    {
        $strTableName = $this->strTablename;
        $strStatus = $this->readTableStatus($strTableName);
        if ($strStatus == "" or self::ENABLECACHE !== true) {
            return $this->getAllRows($strSQL);
        } else {
            $strFilename = "sql-$strStatus" . sha1($strSQL) . crc32($strSQL) . ".sql";
            if (true) {
                $memcachedServer = new MemcacheServer($strFilename);
                $this->strCacheFlag = "c";
                if ($memcachedServer->isCacheFresh()) {
                    $this->strCacheFlag = "y";
                    return json_decode($memcachedServer->getThis(), true);
                } else {
                    $arrData = $this->getAllRows($strSQL);
                    $this->strCacheFlag = "s";
                    $memcachedServer->save(json_encode($arrData));
                    return $arrData;
                }
            }
        }
        return [];
    }

    public function readTableStatus($strTablename = "")
    {
        if ($strTablename == "") {
            $strTablename = $this->strTablename;
        }
        $strTablename = strtr($strTablename, ["`" => ""]);
        switch ($strTablename) {
            default:
                $strField = "__readTableStatus";
                break;
        }
        $strSQL = "SELECT MAX({$strField}) as MAX FROM $strTablename";
        $strStamp = $this->getOne($strSQL, "MAX");
        return $strStamp;
    }

    final public function getOne($strSQL, $strField)
    {
        $strResult = null;
        $mysqlQueryUnbuffered = $this->mysqlQueryUnbuffered($strSQL . " LIMIT 0,1;");
        if ($mysqlQueryUnbuffered) {
            while ($arrRow = $mysqlQueryUnbuffered->fetch_assoc()) {
                $strResult = $arrRow[$strField];
            }
            $mysqlQueryUnbuffered->close();
        }
        return $strResult;
    }

    private function mysqlQueryUnbuffered($strSQL)
    {
//        if(!is_bool($this->queryID) and !is_null($this->queryID))
//        {
////            var_dump($this->queryID);
//            $this->queryID->close();
//        }
        return $this->mysqli->query($strSQL, MYSQLI_USE_RESULT);
    }

    final public function getAllRows($strSQL)
    {
        $arrResult = [];
        $result = $this->mysqlQueryUnbuffered($strSQL);
        if ($result) {
            while ($arrRow = $result->fetch_assoc()) {
                $arrResult[] = $arrRow;
            }
            $result->close();
        }
        return $arrResult;
    }

    final public function QueryUnbuffered($strSQL)
    {
        $result = $this->mysqli->query($strSQL, MYSQLI_USE_RESULT);
//        $result->close();
        if (!is_bool($result)) {
            $result->close();
        }
    }

    final public function getSingleRow($strSQL)
    {
        $arrResult = null;
        $queryUnbuffered = $this->mysqlQueryUnbuffered($strSQL . " LIMIT 0,1;");
        if ($queryUnbuffered) {
            while ($arrRow = $queryUnbuffered->fetch_assoc())
                $arrResult = $arrRow;
            $queryUnbuffered->close();
        }
        return $arrResult;
    }

    final public function getSingleRowCached($strSQL)
    {
        $arrResult = null;
        foreach ($this->getAllRowsCached($strSQL) as $arrRow) {
            $arrResult = $arrRow;
        }
        return $arrResult;
    }
//    final public function mySQLTransaction($strSQL)
//    {
//        return $this->mysqlQuery("START TRANSACTION; " . $strSQL . " ; COMMIT; ");
//    }
    final public function getAllOnesCached($strSQL, $strKeyfield, $strDatafield)
    {
        $arrResult = [];
        foreach ($this->getAllRowsCached($strSQL) as $arrRow) {
            $arrResult[$arrRow[$strKeyfield]] = $arrRow[$strDatafield];
        }
        return $arrResult;
    }

    final public function getAllTwos($strSQL, $strKeyfield1, $strKeyfield2, $strDatafield)
    {
        $arrResult = [];
        $mysqlQueryUnbuffered = $this->mysqlQueryUnbuffered($strSQL);
        if ($mysqlQueryUnbuffered) {
            while ($arrRow = $mysqlQueryUnbuffered->fetch_assoc())
                $arrResult[$arrRow[$strKeyfield1]][$arrRow[$strKeyfield2]] = $arrRow[$strDatafield];
            $mysqlQueryUnbuffered->close();
        }
        return $arrResult;
    }
//    /*
//     *
//     *    New entry point
//     *
//     */
//
//
//    final public function selectAllOnes($strKeyfield, $strDatafield, $strWhere)
//    {
//        return $this->getAllOnes("SELECT $strKeyfield, $strDatafield FROM {$this->strTablename} WHERE $strWhere", $strKeyfield, $strDatafield);
//    }
    final public function quote(&$strString)
    {
        $strString = $this->mysqliRealEscapeString($strString);
    }

    final public function mysqliRealEscapeString($strString)
    {
        if (is_string($strString)) {
            return $this->mysqli->real_escape_string($strString);
        } else

            return "";
    }

    final public function getSaveFunctions()
    {
        $arrFields = [];
        $arrData = $this->getAllRowsByIndex("DESCRIBE {$this->strTablename}", "Field");
        foreach ($arrData as $strField => $arrRow) {
            if ($arrRow["Type"] == "datetime")
                $arrFields[$strField] = "FROM_UNIXTIME";
            else
                $arrFields[$strField] = "";
        }
        return $arrFields;
    }

    final public function getAllRowsByIndex($strSQL, $strKeyfield)
    {
        $arrResult = [];
        $mysqlQueryUnbuffered = $this->mysqlQueryUnbuffered($strSQL);
        if (is_object($mysqlQueryUnbuffered)) {
            while ($arrRow = $mysqlQueryUnbuffered->fetch_assoc()) {
                $arrResult[$arrRow[$strKeyfield]] = $arrRow;
            }
            $mysqlQueryUnbuffered->close();
        }
        return $arrResult;
    }

    final public function getAllRowsByIndexCached($strSQL, $strKeyfield)
    {
        $arrResult = [];
        foreach ($this->getAllRowsCached($strSQL) as $arrRow) {
            if (isset($arrRow[$strKeyfield])) {
                $arrResult[$arrRow[$strKeyfield]] = $arrRow;
            }
        }
        return $arrResult;
    }

    final public function getAllUNIXfields()
    {
        $arrData = $this->getAllRowsByIndex("DESCRIBE {$this->strTablename}", "Field");
        $arrFields = [];
        foreach ($arrData as $strField => $arrRow) {
            if ($arrRow["Type"] == "datetime")
                $arrFields[] = "UNIX_TIMESTAMP(`$strField`) as `$strField` ";
            else {
                if ($arrRow["Type"] != "timestamp" and $arrRow["Key"] != "PRI") {
                    $arrFields[] = "`$strField`";
                }
            }
        }
        return join(",", $arrFields);
    }

    final public function optimzeTable()
    {
    }

    public function readTableStatusOriginal($strTablename)
    {
        $strSQL = "show table status like '{$strTablename}'";
        $arrX = $this->getAllRows($strSQL);
        $strStamp = $arrX[0]["Update_time"];
        return $strStamp;
    }

    public function wipeTable()
    {
        if ($this->getOne("SELECT COUNT(*)  as intCount FROM {$this->strTablename} ", "intCount") > 0) {
            $strSQL1 = "TRUNCATE  {$this->strTablename}";
            $this->mysqlQuery($strSQL1);
            $strSQL2 = "OPTIMIZE TABLE  {$this->strTablename}";
            $this->mysqlQuery($strSQL2);
        }
    }
//    final public function putOneRow($intID, $strSetCommand)
//    {
//        $strSQL = "UPDATE {$this->strTablename} SET {$strSetCommand} WHERE intID={$intID}";
//
//        return $this->mysqlQueryUnbuffered($strSQL);
//    }
    final public function mysqlQuery($strSQL)
    {
        $fpStartTime = microtime(true);
        $result = $this->query($strSQL);
        $fpEndTime = microtime(true);
        $fpTiming = ($fpEndTime - $fpStartTime) * 1E3;
        if ($fpTiming > self::LOGMORETHANMILISECS or $this->mysqli->errno or strripos($strSQL, "union") !== false or $this->ynMustLog) {
            core::echo_cli(PHP_EOL . number_format($fpTiming, 3) . "ms: [" . $this->mysqli->errno . "] {$this->strCacheFlag} " . $strSQL);
        }
        return $result;
    }

    public function query($query)
    {
        return $this->mysqli->query($query);
    }

    public function showTables()
    {
        return $this->getAllRows("Show tables");
    }

    public function getTableStatusAll($strTablename)
    {
        $strSQL = "SHOW TABLE STATUS WHERE Name = '$strTablename'";
        $arrData = $this->getAllRows($strSQL);
        return $arrData;
    }

    public function getTableStructureAlt($strTablename)
    {
        $strSQL = "SHOW FULL COLUMNS FROM `$strTablename`";
        $arrData = $this->getAllRowsByIndex($strSQL, "Field");
        return $arrData;
    }

    public function getDistictRowsAndCountForFieldUncached($strFieldname, $strTablename)
    {
        return $this->getAllOnes("SELECT COUNT(*) AS `Rows`, {$strFieldname} FROM {$strTablename}  GROUP BY  {$strFieldname}  ORDER BY  {$strFieldname} ", $strFieldname, "Rows");
    }

    final public function getAllOnes($strSQL, $strKeyfield, $strDatafield)
    {
        $arrResult = [];
        $mysqlQueryUnbuffered = $this->mysqlQueryUnbuffered($strSQL);
        if (is_object($mysqlQueryUnbuffered)) {
            while ($arrRow = $mysqlQueryUnbuffered->fetch_assoc())
                $arrResult[$arrRow[$strKeyfield]] = $arrRow[$strDatafield];
            $mysqlQueryUnbuffered->close();
        }
        return $arrResult;
    }

    public function getShowIndex($strTablename)
    {
        return $this->getAllRows("SHOW INDEX FROM {$strTablename}");
    }
//    private function mustlog()
//    {
//        $this->ynMustLog = true;
//    }
}