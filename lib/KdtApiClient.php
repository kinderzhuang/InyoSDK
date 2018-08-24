<?php
/**
 * @require PHP>=5.3
 */

require_once('SimpleHttpClient.php'); 
require_once('KdtApiProtocol.php'); 
date_default_timezone_set('PRC');

class KdtApiClient
{
    const VERSION = '1.0';

    private static $apiEntry = 'http://pre-e.mix361.com/index.php?r=api/';

    private $appId;
    private $appSecret;
    private $format = 'json';
    private $signMethod = 'md5';

    public function __construct($appId, $appSecret)
    {
        if ('' == $appId || '' == $appSecret) throw new \Exception('appId 和 appSecret 不能为空');

        $this->appId = $appId;
        $this->appSecret = $appSecret;
    }

    public function get($method, $params = array())
    {
        list($response, $error) = SimpleHttpClient::get(self::$apiEntry.$method, $this->buildRequestParams($params));
        return $this->parseResponse(
            $response,
            $error
        );
    }

    public function post($method, $params = array(), $files = array())
    {
        list($response, $error) = SimpleHttpClient::post(self::$apiEntry.$method, $this->buildRequestParams($params), $files);
        return $this->parseResponse(
            $response,
            $error
        );
    }


    public function setFormat($format)
    {
        if (!in_array($format, KdtApiProtocol::allowedFormat()))
            throw new \Exception('设置的数据格式错误');

        $this->format = $format;

        return $this;
    }

    public function setSignMethod($method)
    {
        if (!in_array($method, KdtApiProtocol::allowedSignMethods()))
            throw new \Exception('设置的签名方法错误');

        $this->signMethod = $method;

        return $this;
    }


    private function parseResponse($responseData, $error = null)
    {
        if(isset($_GET['debug'])){
            var_dump('responseData',$responseData);
        }
        if ($error) {
            throw new \Exception($error['error'], $error['errno']);
        }
        $data = json_decode($responseData, true);
        if (null === $data) {
            throw new \Exception('response invalid, data: ' . $responseData);
        }
        return $data;
    }

    private function buildRequestParams($apiParams)
    {
        if (!is_array($apiParams)) $apiParams = array();
        $pairs = $this->getCommonParams();
        foreach ($apiParams as $k => $v) {
            if (isset($pairs[$k])) throw new \Exception('参数名冲突');
            $pairs[$k] = $v;
        }

        $pairs[KdtApiProtocol::SIGN_KEY] = KdtApiProtocol::sign($this->appSecret, $pairs, $this->signMethod);
        return $pairs;
    }

    private function getCommonParams()
    {
        $params = array();
        $params[KdtApiProtocol::APP_ID_KEY] = $this->appId;
        $params[KdtApiProtocol::TIMESTAMP_KEY] = date('Y-m-d H:i:s');
        $params[KdtApiProtocol::FORMAT_KEY] = $this->format;
        $params[KdtApiProtocol::SIGN_METHOD_KEY] = $this->signMethod;
        $params[KdtApiProtocol::VERSION_KEY] = self::VERSION;
        return $params;
    }
}
