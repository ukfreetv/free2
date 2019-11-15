<?php


namespace free2\registrationDemo;


use view\helper\html;

class MakeOptionSelect
{


    public static function makeDrop($id, $id2)
    {
        $t = "";
        foreach ($id2 as $item) {
            $t .= html::wrapTag($item, "option", "value='$item'");
        }
        $strUse = html::wrapTag($t, "select", "value='$id'");

        return $strUse;
    }
}