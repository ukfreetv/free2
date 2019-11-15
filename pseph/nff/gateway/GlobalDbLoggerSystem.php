<?php

namespace pseph\nff\gateway;

use pseph\nff\Base;
use view\helper\html;

class GlobalDbLoggerSystem extends Base
{




    public static function addLog($strError)
    {
        if (isset($GLOBALS[__CLASS__ . "_log"])) {
            $GLOBALS[__CLASS__ . "_log"] .= PHP_EOL . $strError;;
        } else {
            $GLOBALS[__CLASS__ . "_log"] = $strError;
        }
    }

    public function getLog()
    {
        if (isset($GLOBALS[__CLASS__ . "_log"])) {
            return html::i($GLOBALS[__CLASS__ . "_log"]);
        } else {
            return "";
        }

    }

    public function clearLog()
    {
        unset($GLOBALS[__CLASS__ . "_log"]);
    }
}
