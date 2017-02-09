<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/2/7
 * Time: 15:35
 */
function json_response($data){
    header('Content-Type:application/json;charset=utf-8');
    exit(json_encode($data));
}

function gen_unique_code($prefix='MEM_'){
    $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    $password = $prefix;
    $length = 20 - strlen($prefix);
    for($i = 0; $i < $length; $i++){
        $password .= $chars[ mt_rand(0, strlen($chars) - 1) ];
    }
    return $password;
}