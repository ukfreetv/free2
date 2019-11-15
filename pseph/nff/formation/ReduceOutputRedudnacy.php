<?php

namespace pseph\nff\formation;

use pseph\nff\Base;
use view\helper\core;

class ReduceOutputRedudnacy extends Base
{
    public function __construct()
    {
    }

    public function cssCompress($strBuffer)
    {
        $strBuffer = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $strBuffer);
        $strBuffer = str_replace(': ', ':', $strBuffer);
        $strBuffer = str_replace([
            "\r\n",
            "\r",
            "\n",
            "\t",
            '  ',
            '    ',
            '    '
        ], '', $strBuffer);

        return $strBuffer;
    }

    public function htmlCompress($strBuffer)
    {
        if (core::isWindowsMachine()) {
            return $strBuffer;

        } else {
            return MinifyHtml::minify($strBuffer);
        }

    }

    public function jsCompress($strBuffer)
    {
        if (core::isWindowsMachine()) {
            return $strBuffer;
        } else {
            return JsMin::minify($strBuffer);
        }

    }
}