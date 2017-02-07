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