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

function preg_phone($str){
    $pattern = '/^1[34578]\d{9}$/';
    return(preg_match($pattern, $str));
}

function preg_phone2($str){
    $pattern = '/^(0\d{2}-\d{7,8}|0\d{3}-\d{7,8})$/';
    return(preg_match($pattern, $str));
}

function preg_email($str){
    $pattern = '/^([0-9A-Za-z\\-_\\.]+)@([0-9a-z]+\\.[a-z]{2,3}(\\.[a-z]{2})?)$/';
    return(preg_match($pattern, $str));
}

function preg_login_name($str){
    $pattern = '/^[a-zA-Z0-9_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]+$/';
    $rs = preg_match($pattern, $str);
    if($rs){
        $strLenth = strlen($str);
        if ($strLenth < 8 || $strLenth > 20) {
            $rs = false;
        }
    }
    return $rs;
}

function preg_manager_name($str){
    $pattern = '/((?=.*\d)(?=.*\D)|(?=.*\d)|(?=.*[a-zA-Z])|(?=.*[a-zA-Z])(?=.*[^a-zA-Z]))^.{5,16}$/';
    return(preg_match($pattern, $str));
}

function preg_password($str){
    $pattern = '/((?=.*\d)(?=.*\D)|(?=.*\d)|(?=.*[a-zA-Z])|(?=.*[a-zA-Z])(?=.*[^a-zA-Z]))^.{8,16}$/';
    return(preg_match($pattern, $str));
}

//验证身份证是否有效
function preg_identity($IDCard) {
    if (strlen($IDCard) == 18) {
        return check18IDCard($IDCard);
    } elseif ((strlen($IDCard) == 15)) {
        $IDCard = convertIDCard15to18($IDCard);
        return check18IDCard($IDCard);
    } else {
        return false;
    }
}

//计算身份证的最后一位验证码,根据国家标准GB 11643-1999
function calcIDCardCode($IDCardBody) {
    if (strlen($IDCardBody) != 17) {
        return false;
    }

    //加权因子
    $factor = array(7, 9, 10, 5, 8, 4, 2, 1, 6, 3, 7, 9, 10, 5, 8, 4, 2);
    //校验码对应值
    $code = array('1', '0', 'X', '9', '8', '7', '6', '5', '4', '3', '2');
    $checksum = 0;

    for ($i = 0; $i < strlen($IDCardBody); $i++) {
        $checksum += substr($IDCardBody, $i, 1) * $factor[$i];
    }

    return $code[$checksum % 11];
}

// 将15位身份证升级到18位
function convertIDCard15to18($IDCard) {
    if (strlen($IDCard) != 15) {
        return false;
    } else {
        // 如果身份证顺序码是996 997 998 999，这些是为百岁以上老人的特殊编码
        if (array_search(substr($IDCard, 12, 3), array('996', '997', '998', '999')) !== false) {
            $IDCard = substr($IDCard, 0, 6) . '18' . substr($IDCard, 6, 9);
        } else {
            $IDCard = substr($IDCard, 0, 6) . '19' . substr($IDCard, 6, 9);
        }
    }
    $IDCard = $IDCard . calcIDCardCode($IDCard);
    return $IDCard;
}

// 18位身份证校验码有效性检查
function check18IDCard($IDCard) {
    if (strlen($IDCard) != 18) {
        return false;
    }

    $IDCardBody = substr($IDCard, 0, 17); //身份证主体
    $IDCardCode = strtoupper(substr($IDCard, 17, 1)); //身份证最后一位的验证码

    if (calcIDCardCode($IDCardBody) != $IDCardCode) {
        return false;
    } else {
        return true;
    }
}

//双向加密解密id
function keys_encrypt($string = '', $skey = 'JusticeBureau') {
    $strArr = str_split(base64_encode($string));
    $strCount = count($strArr);
    foreach (str_split($skey) as $key => $value)
        $key < $strCount && $strArr[$key].=$value;
    return str_replace(array('=', '+', '/'), array('O0O0O', 'o000o', 'oo00o'), join('', $strArr));
}

function keys_decrypt($string = '', $skey = 'JusticeBureau') {
    $strArr = str_split(str_replace(array('O0O0O', 'o000o', 'oo00o'), array('=', '+', '/'), $string), 2);
    $strCount = count($strArr);
    foreach (str_split($skey) as $key => $value)
        $key <= $strCount  && isset($strArr[$key]) && $strArr[$key][1] === $value && $strArr[$key] = $strArr[$key][0];
    return base64_decode(join('', $strArr));
}

function spilt_title($str,$len,$end = true){
    if($str === ''){
        return $str;
    }
    $str_len = strlen($str);
    if($str_len <= $len)
    {
        return $str;
    }
    else{
        if($end === true){
            $title = mb_substr($str, 0, $len).'...';
        }
        else{
            $title = mb_substr($str, 0, $len);
        }
        return $title;
    }
}