<?php
include_once __DIR__.'/common.php';
$name = get_customer_name();
$jnt_address = read_jnt_address();
$customer_address = get_customer_address($name);
$result = loop($jnt_address, $customer_address);
export($result);
