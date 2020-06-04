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
        return json_decode($response, true);
    } catch (Exception $ex) {
        echo date('Y-m-d H:i:s') . ": " . $ex->getMessage() . "\r\n";
        return null;
    }
}
