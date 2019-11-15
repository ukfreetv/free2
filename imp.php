<?php

require_once "commonErrorHandler.php";
ini_set("memory_limit", 999999999);

$fh=fopen("CSV PAF.csv","r");

$i=PHP_EOL . "INSERT INTO `CSV PAF` (`Postcode`, `Post Town`, `Dependent Locality`, `Double Dependent Locality`, `Thoroughfare & Descriptor`, `Dependent Thoroughfare & Descriptor`, `Building Number`, `Building Name`, `Sub Building Name`, `PO Box`, `Department Name`, `Organisation Name`, `UDPRN`, `Postcode Type`, `SU Organisation Indicator`, `Delivery Point Suffix`, `Unknown1`, `Unknown2`, `Unknown3`, `Unknown4`) ";

$lines=[];
$out="";

while (($data = fgetcsv($fh, 1000, ",")) !== false) {



    foreach ($data as $key=>$value) {

        $data[$key]=strtr($value,["'"=>""]);
    }
//
//    var_dump($data);die();


    $lines[]=  "('" . join("','", $data) . "')";


    if(count($lines)>1000)
    {
        $out.= $i . " VALUES " . join(",",$lines) . ";";
        $lines=[];
        echo ".";
    }

}
$out.= $i . " VALUES " . join(",",$lines) . ";";

file_put_contents("out.sql", $out);
