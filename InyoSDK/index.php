<?php
/** 
 *  应有云SDK 使用范例
 */
// 引入SDK库
include('YiXin.php');

// 登录应有云后台获取AppId和AppSecret
$AppId = 'iy545da314fd6ce9fd';
$AppSecret = '3ccfe243c61de6c5f23001a144be22c6';
$yixin = new YiXin($AppId, $AppSecret);


//获得某个用户在应有的token令牌
$access_token = $yixin->getToken();
var_dump($access_token);

// 获得某个用户在应有的更新token令牌
$refreshToken = $yixin->getRefreshToken();
var_dump($refreshToken);

