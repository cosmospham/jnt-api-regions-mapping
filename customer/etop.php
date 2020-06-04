<?php

function etop_get() {
    $curl = curl_init();

    curl_setopt_array(
        $curl, array(
        CURLOPT_URL            => "https://api.sandbox.etop.vn/v1/carrier.Misc/GetLocationList",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING       => "",
        CURLOPT_MAXREDIRS      => 10,
        CURLOPT_TIMEOUT        => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST  => "POST",
        CURLOPT_POSTFIELDS     => "{}",
        CURLOPT_HTTPHEADER     => array(
            "Authorization: Bearer 1132585251694912189:gqshVCL2CpeccJkLgMstlYpPP85Slgye",
            "Content-Type: application/json",
        ),
    )
    );

    $response = curl_exec($curl);

    curl_close($curl);

    try {
        $result = [];
        $data = json_decode($response, true);

        foreach($data['provinces'] as $province) {
            $province_name = ($province['name']);
            $province_name_san = sanitize_name($province['name']);

            foreach($province['districts'] as $district) {
                $district_name = ($district['name']);
                $district_name_san = sanitize_name($district['name']);

                foreach($district['wards'] as $ward) {
                    $ward_name = $ward['name'];
                    $ward_name_san = sanitize_name($ward['name']);
                    $result["$province_name_san/$district_name_san/$ward_name_san"] = "$province_name/$district_name/$ward_name";
                }
            }
        }

        return $result;
    } catch (Exception $ex) {
        echo date('Y-m-d H:i:s') . ": " . $ex->getMessage() . "\r\n";
        return null;
    }
}
