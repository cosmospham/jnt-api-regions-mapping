<?php

function read_jnt_address() {
    $path = __DIR__ . '/jnt.csv';
    if (!file_exists($path)) {
        echo "File $path not exists\r\n";
        exit;
    }

    $jnt_add = [];
    $file = fopen($path, 'rb');
    $row = fgetcsv($file);

    while ($row = fgetcsv($file)) {
        $province_san = sanitize_name($row[1]);
        $district_san = sanitize_name($row[2]);
        $ward_san = sanitize_name($row[3]);
        $jnt_add["$province_san/$district_san/$ward_san"] = $row[0] . "/" . $row[1] . "/" . $row[2] . "/" . $row[3];

        if (!isset($result["$province_san/$district_san/"]))
            $result["$province_san/$district_san/"] = [];

        $jnt_add["$province_san/$district_san/"][] = $row[0] . "/" . $row[1] . "/" . $row[2] . "/" . $row[3];
    }

    return $jnt_add;
}

function khongdau($str) {
    $str = preg_replace("/([àáạảãâầấậẩẫăằắặẳẵ])/u", "a", $str);
    $str = preg_replace("/([èéẹẻẽêềếệểễ])/u", "e", $str);
    $str = preg_replace("/([ìíịỉĩ])/u", "i", $str);
    $str = preg_replace("/([òóọỏõôồốộổỗơờớợởỡ])/u", "o", $str);
    $str = preg_replace("/([ùúụủũưừứựửữ])/u", "u", $str);
    $str = preg_replace("/([ỳýỵỷỹ])/u", "y", $str);
    $str = preg_replace("/(đ)/u", "d", $str); // 273
    $str = preg_replace("/(ð)/u", "d", $str); // 240
    $str = preg_replace("/([ÀÁẠẢÃÂẦẤẬẨẪĂẰẮẶẲẴ])/u", "A", $str);
    $str = preg_replace("/([ÈÉẸẺẼÊỀẾỆỂỄ])/u", "E", $str);
    $str = preg_replace("/([ÌÍỊỈĨ])/u", "I", $str);
    $str = preg_replace("/([ÒÓỌỎÕÔỒỐỘỔỖƠỜỚỢỞỠ])/u", "O", $str);
    $str = preg_replace("/([ÙÚỤỦŨƯỪỨỰỬỮ])/u", "U", $str);
    $str = preg_replace("/([ỲÝỴỶỸ])/u", "Y", $str);
    $str = preg_replace("/(Đ)/u", "D", $str); // 272
    $str = preg_replace("/(Ð)/u", "D", $str); // 208
    return $str;
}

function sanitize_name($string) {
    $trim = [
        '^xã',
        '^phường',
        '^thị trấn',
        '^huyện đảo',
        '^huyện',
        '^quận',
        '^thị xã',
        '^thành phố',
        '^tỉnh',
        '^đảo',
    ];

    $string = mb_strtolower($string);

    foreach ($trim as $tr) {
        if (mb_ereg_match($tr, $string)) {
            $string = mb_eregi_replace($tr, '', $string);
            break;
        }
    }

    $string = preg_replace("/\u{00A0}$/u", '', $string);
    $string = trim($string, " \t\n\r\0\x0B-_—");
    $string = name_exception($string);

    $string = khongdau($string);
    $string = mb_eregi_replace('-[0-9a-zàáãạảăắằẳẵặâấầẩẫậèéẹẻẽêềếểễệđìíĩỉịòóõọỏôốồổỗộơớờởỡợùúũụủưứừửữựỳỵỷỹýÀÁÃẠẢĂẮẰẲẴẶÂẤẦẨẪẬÈÉẸẺẼÊỀẾỂỄỆĐÌÍĨỈỊÒÓÕỌỎÔỐỒỔỖỘƠỚỜỞỠỢÙÚŨỤỦƯỨỪỬỮỰỲỴỶỸÝ]+$', '', $string);
    $string = str_replace("'", '', $string);
    $string = preg_replace('/[ ]+/', ' ', $string);
    $string = preg_replace('/ – /u', ' - ', $string);
    $string = preg_replace('/ - /u', ' - ', $string);
    $string = preg_replace('/ - /u', ' ', $string);
    $string = str_replace("-", ' ', $string);
    $string = preg_replace('/\((.)+\)/', '', $string);

    if (is_numeric($string))
        $string = (int)$string;

    $string = preg_replace("/\u{00A0}$/u", '', $string);
    $string = trim($string, " \t\n\r\0\x0B-_—");
    return $string;
}

function name_exception($name) {
    if ($name === 'krông păk' || $name === 'krông pắk') {
        return 'krông pắc';
    }

    return $name;
}

function mapping($their) {
    $our = "";

    return $our;
}

function loop($jnt_address, &$customer_address) {
    $count = 0;
    foreach ($jnt_address as $key => $value) {
        if (!isset($customer_address[$key])) {
            echo "NOT FOUND: >>>> $key <<<<\r\n\r\n";
            $new_key_arr = explode('/', $key);
            $new_key = implode('/', [$new_key_arr[0], $new_key_arr[1]]) . "/";
            if (isset($customer_address[$new_key])) {
                $i = 0;
                foreach ($customer_address[$new_key] as $index => $maybe) {
                    echo "[$i] $maybe [$i]\r\n";
                    ++$i;
                }

                echo "Choose a ward for mapping: ";
                $handle = fopen("php://stdin", "r");
                $line = fgets($handle);
                $line = trim($line);

                if ($line === "break") break;

                if ($line && isset($customer_address[$new_key][$line])) {
                    echo $customer_address[$new_key][$line] . "\r\n";

                    $customer_address[$key] = $customer_address[$new_key][$line];
                } else {
                    $customer_address[$key] = "__BLANK__";
                }
                fclose($handle);

                echo "\r\n";
            }

            $count++;
        }
    }

    var_dump($count);
}

function check($jnt_address, $customer_address) {
    $count = 0;
    foreach ($jnt_address as $key => $value) {
        if (!isset($customer_address[$key]) && is_string($value)) {
            echo "NOT FOUND: >>>> " . $jnt_address[$key] . "( " . $value . " )" . " <<<<\r\n\r\n";
            $count++;
        }
    }

    var_dump($count);
}

function export($customer_name, $jnt_address, $customer_address) {
    $path = __DIR__ . "/files/" . $customer_name . "_mapping." . date('Y-m-d-H-i-s') . ".csv";
    $file = fopen($path, 'w+b');
    $count = 0;
    foreach ($jnt_address as $key => $value) {
        if (!is_string($value)) continue;

        $row = [];

        $split = explode('/', $value);

        if (!isset($customer_address[$key])) {
            echo "NOT FOUND: >>>> " . $jnt_address[$key] . "( " . $value . " )" . " <<<<\r\n\r\n";
            $split_ = ["__BLANK__", "__BLANK__", "__BLANK__"];

            $count++;
        } else {
            $split_ = explode('/', $customer_address[$key]);

            if (!$row) {
                var_dump($value);
                var_dump($customer_address[$key]);
            }
        }

        if ($split && $split_) {
            $row = array_merge($split, $split_);
        }

        if ($row) fputcsv($file, $row);
    }
    fclose($file);

    var_dump($count);
}

function get_customer_address($name) {
    if (!file_exists(__DIR__ . '/customer/' . $name . '.php')) {
        echo "File " . __DIR__ . '/customer/' . $name . '.php' . " not exists.\r\n";
        exit;
    }

    include_once __DIR__ . '/customer/' . $name . '.php';

    if (!function_exists($name . '_get')) {
        echo "Function " . $name . '_get' . " not exists.\r\n";
        exit;
    }

    return call_user_func_array($name . '_get', []);
}

function get_customer_name() {
    global $argv;

    if (!$argv || empty($argv[1]) || !file_exists(__DIR__ . '/customer/' . $argv[1] . '.php')) {
        if (!file_exists(__DIR__ . '/customer/')) {
            echo "Dir " . __DIR__ . '/customer/' . " not exists.\r\n";
            exit;
        }

        echo "Please input cutomer name, availabe names area: \r\n";

        $dir = new DirectoryIterator(__DIR__ . '/customer/');
        foreach ($dir as $fileinfo) {
            if (!$fileinfo->isDot() && preg_match('/\.php$/', $fileinfo->getFilename())) {
                echo str_replace('.php', '', $fileinfo->getFilename()) . "\r\n";
            }
        }
        echo "\r\n";
        exit;
    }

    return $argv[1];
}
