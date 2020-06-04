<?php

function read_jnt_address() {

    return [];
}

function sanitize_name($string) {


    return $string;
}

function mapping($their) {
    $our = "";

    return $our;
}

function loop($jnt_array, $customer_array) {
    // sanitize_name

    // mapping

    $result = [];

    return $result;
}

function export($result) {

}

function get_customer_address($name) {
    if (!file_exists(__DIR__.'/customer/'.$name.'.php')) {
        echo "File ".__DIR__.'/customer/'.$name.'.php'." not exists.\r\n";
        exit;
    }

    include_once __DIR__.'/customer/'.$name.'.php';

    if (!function_exists($name.'_get')) {
        echo "Function ".$name.'_get'." not exists.\r\n";
        exit;
    }

    return call_user_func_array($name.'_get', []);
}

function get_customer_name() {
    global $argv;

    if (!$argv || empty($argv[1]) || !file_exists(__DIR__.'/customer/'.$argv[1].'.php')) {
        if (!file_exists(__DIR__.'/customer/')) {
            echo "Dir ".__DIR__.'/customer/'." not exists.\r\n";
            exit;
        }

        echo "Please input cutomer name, availabe names area: \r\n";

        $dir = new DirectoryIterator(__DIR__.'/customer/');
        foreach ($dir as $fileinfo) {
            if (!$fileinfo->isDot() && preg_match('/\.php$/', $fileinfo->getFilename())) {
                echo str_replace('.php', '', $fileinfo->getFilename())."\r\n";
            }
        }
        echo "\r\n";
        exit;
    }

    return $argv[1];
}
