<?php
/** 
 *  医信智慧SDK 使用范例
 */
// 引入SDK库
include('YiXin.php');

// 登录医信后台之后在 http://e.mix361.com/index.php?r=settingsys/security 获取AppId和AppSecret
$AppId = '你的AppId';
$AppSecret = '你的AppSecret';
$yixin = new YiXin($AppId, $AppSecret);
$openId = '医信跳转到第三方链接时传递过去的openid';
$wxappId = '医信跳转到第三方链接时传递过去的wxappid';
$cardId = '要发送给顾客的卡片的id';

// 获得某个用户在医信的用户id
$userInfoResult = $yixin->getCustomerInfo($openId, $wxappId);
// var_dump('userInfoResult',$userInfoResult);

if(isset($userInfoResult['customerInfo']) && isset($userInfoResult['customerInfo']['id'])){
    $customerId = $userInfoResult['customerInfo']['id'];
    // 给某个用户发某一种卡券
    $sendCardResult = $yixin->sendCardOrCoupons($customerId, $cardId);    
    // var_dump($sendCardResult);
}
