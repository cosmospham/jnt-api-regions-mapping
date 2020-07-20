<?php

function haravan_get() {
    try {
        $file = fopen(__DIR__.'/haravan.csv', 'r+b');
        $result = [];
        fgetcsv($file);
        while(($row = fgetcsv($file))) {
            $ward_name = ($row[2]);
            $ward_name_san = sanitize_name($row[2]);
            $district_name = ($row[1]);
            $district_name_san = sanitize_name($row[1]);
            $province_name = ($row[0]);
            $province_name_san = sanitize_name($row[0]);

            $result["$province_name_san/$district_name_san/"][] = "$province_name/$district_name/$ward_name";
            $result["$province_name_san/$district_name_san/$ward_name_san"] = "$province_name/$district_name/$ward_name";
        }

        return $result;
    } catch (Exception $ex) {
        echo date('Y-m-d H:i:s') . ": " . $ex->getMessage() . "\r\n";
        return null;
    }
}
