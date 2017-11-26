<?php
/**
 * Created by PhpStorm.
 * User: Henry
 * Date: 2017/11/26
 * Time: 19:56
 */
include_once './WxPay.php';
$appid = 'wx9af969088cb3a75d';
$appsecret = '5a70b6232eaa5591f641e428f2a12392';
$wx = new WxPay($appid, $appsecret);
var_dump($wx->WxPayUnifiedOrder());