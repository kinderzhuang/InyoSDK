<?php

/**
 * 医信智慧 接入sdk （默认都是使用GET方式,md5方式加密）
 */
require_once('lib/KdtApiClient.php'); 

class YiXin
{
    private $appId;
    private $appSecret;

    /**
     * @var KdtApiClient
     */
    private $apiClient;

    /**
     * Yixin constructor.
     * @param $appId
     * @param $appSecret
     */
    public function __construct($appId, $appSecret)
    {
        $this->appId = $appId;
        $this->appSecret = $appSecret;
        $this->apiClient = new KdtApiClient($appId, $appSecret);
    }
    /**
     * 获取jssdk需要的参数
     *
     * @param  [type] $wxappId         jssdk所对应的公众号的id
     * @param  [type] $url             当前页面的地址，域名需要是js安全域名
     * 返回格式：
     *   array(
     *    "appId",
     *   "nonceStr",
     *    "timestamp",
     *   "url",
     *    "signature",
     *     "rawString"
     *  ) 
     * @return [type]                  [description]
     */
    public function getSignPackage($wxappId, $url){
        $method = 'getsignpackage';
        $params = array(            
            'url' => $url,
            'wxappid' => $wxappId
        );
        $result = $this->apiClient->get($method, $params);
        return $result;
    }
    /**
     * 用cusId获取顾客信息
     * @param  [type] $cusId 用户id
     * @return [type]    
     *  返回顾客信息的字段：    
            id ： ID,
            name ： 名字,
            telnum ： 已认证电话,
            telnum1 ： 未认证电话,
            birthday ： 生日,
            sex ： 性别（1：男，2：女）,
            description ： 额外描述,
            wxLastOpTime ： 微信上最近一次交互的时间,
            uid ： 渠道人员id,
            channelId ： 渠道媒介,
            consultId ： 现场咨询师id,
            firstPreConsultId ： 专属网络咨询师,
            preConsultId ： 网络咨询id,
            intention ： 客户意向度,
            isBigOrder ： 是否是大单,
            points ： 金币,
            commission ： 佣金,
            gold ： 用户余额,
            headimg ： 头像地址,
            orderTime ： 预约时间,
            model ： 0: 公众号聊天 1：网页在线聊天 2:小程序聊天     

            channel ： 媒介的具体信息
            user： 渠道人员的具体信息
            firstpreconsult： 专属咨询师的具体信息
            preconsult： 网络咨询师的具体信息
            consult： 现场咨询师的具体信息
            wxScenesBind: 关注的公众号
     */
    public function getCusInfoByCusId($cusId){
        $method = 'getcusinfobycusid';
        $params = array(
            'cusId' => $cusId
        );
        $result = $this->apiClient->get($method, $params);
        return $result;
    }
    /**
     * 获得某个时间点之后的所有聊天记录   
     * @param  [type]  $lastChatTime 该时间点之后的聊天内容，空则从最开始获取
     * @param  integer $page         分页，0开始。一页1000条
     * @return [type]  
     *     返回字段说明：
     *        total: 符合条件的消息总数     
     *        consults:              
         *        cusId : 哪个顾客的消息,
         *        msgOwner : 0: 顾客发的，1：客服回复的,  
         *        msgType : 1:文字，2：图片， 3： 音频,4： 视频，5:短视频，         
         *        content : 消息内容,
         *        createTime : 消息创建时间
     */            
    public function getAllChatHistoryByTime($lastChatTime = null, $page = 0){
        $method = 'getallchathistorybytime';
        $params = [];
        $lastChatTime && $params['lastChatTime'] = $lastChatTime;
        $page && $params['page'] = $page;
        $result = $this->apiClient->get($method, $params);
        return $result;        
    }
    /**
     * 获得某个顾客某个时间点之后的聊天记录   
     * @param  [type]  $wxac         公众号的开发者ID(AppID)
     * @param  [type]  $openId       顾客的openId
     * @param  [type]  $lastChatTime 该时间点之后的聊天内容，空则从最开始获取
     * @param  integer $page         分页，0开始。一页20条
     * @return [type]  
     *     返回字段说明：
     *        total: 符合条件的消息总数
     *        cusId: 该顾客在咪狐系统中的id
     *        consults:              
         *        cusId : 哪个顾客的消息,
         *        msgOwner : 0: 顾客发的，1：客服回复的,  
         *        msgType : 1:文字，2：图片， 3： 音频,4： 视频，5:短视频，         
         *        content : 消息内容,
         *        createTime : 消息创建时间
     */    
    public function getChatHistoryByOpenId($wxac, $openId, $lastChatTime = null, $page = 0){
        $method = 'getchathistorybyopenid';
        $params = array(
            'wxac' => $wxac,
            'openId' => $openId
        );
        $lastChatTime && $params['lastChatTime'] = $lastChatTime;
        $page && $params['page'] = $page;
        $result = $this->apiClient->get($method, $params);
        return $result;
    }

    /**
     * 给某个手机号发送短信
     *
     * @param  [type]  $tel       发送对象的手机号
     * @param  integer $type      3：发送4位短信验证码（默认）  6：发送商城订单信息
     * @param  [type]  $theParams 如果是发送商城订单信息，格式为theParams: {orderNum:'xxxx', tip:'xxxxxx'}
     *
     * @return [type]             {errNum: xx(0说明接口返回成功，非0说明是产生了错误)，msg: 错误信息}
     */
    public function sendMsgTotel($tel, $type = 3, $theParams = null){
        $method = 'sendmsgtotel';
        $params = array(
            'tel' => $tel,
            'type' => $type
        );
        if($params){
            $params['params'] = json_encode($theParams);
        }
        $result = $this->apiClient->get($method, $params);
        return $result;
    }

    /**
     * 通过openId获取顾客在医信的id
     *
     * @param  [type] $openId 该顾客的openId
     * @param  [type] $wxappId 微信的appid
     *
     * @return [type]        顾客的id
     */
    public function getCustomerInfo($openId, $wxappId){
        $method = 'getcustomerinfo';
        $params = array(
            'openId' => $openId,
            'wxappid' => $wxappId
        );
        $result = $this->apiClient->get($method, $params);
        return $result;
    }
    /**
     * 给某个顾客发送一张卡或优惠券
     *
     * @param  [type] $cusId           顾客的id
     * @param  [type] $cardOrCouponsId 要发送的卡券的id
     *
     * @return [type]                  [description]
     */
    public function sendCardOrCoupons($cusId, $cardOrCouponsId){
        $method = 'sendcardorcoupons';
        $params = array(
            'cusId' => $cusId,
            'cardOrCouponsId' => $cardOrCouponsId
        );
        $result = $this->apiClient->get($method, $params);
        return $result;
    }
}