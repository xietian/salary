<?php
/**
 * 支付接口类
 * @user: andy
 * @date: 2017/7/7
 * @time: 15:18
 */

namespace app\services\thirdpart\wx\xcx;

use app\services\ToolService;
use app\tools\Constants;
use app\tools\XcxConstants;
use Yii;

class PayService
{

    /**
     * 支付宝退款
     *
     * @param $partner
     * @param $outTradeNo
     * @param $refundFee
     * @param $reason
     *
     * @return array
     *
     */
    public function alipayRefund($partner, $outTradeNo, $refundFee, $reason, $notifyUrl)
    {
        $charset = 'utf-8';
        $batchNo = date('Ymd') . microtime(true) * 10000;
        $batchNum = 1;
        $detailData = $outTradeNo . '^' . $refundFee . '^' . $reason;
        $refundDate = date('Y-m-d H:i:s');
        $refund = array(
            'service' => 'refund_fastpay_by_platform_nopwd',
            'partner' => $partner, //支付宝唯一用户号
            'notify_url' => $notifyUrl,
            'seller_user_id' => $partner, //支付宝唯一用户号
            'refund_date' => $refundDate,
            'batch_no' => $batchNo,
            'batch_num' => $batchNum,
            'detail_data' => $detailData,
            '_input_charset' => $charset,
            'sign_type' => 'RSA',
        );
        $privateKeyPath = Yii::getAlias('@app') . DIRECTORY_SEPARATOR . 'services' . DIRECTORY_SEPARATOR . 'thirdpart' . DIRECTORY_SEPARATOR . 'alipay' . DIRECTORY_SEPARATOR . YII_ENV . DIRECTORY_SEPARATOR . 'ras_private_key_pkcs8.pem';
        $refund['sign'] = $this->alipayRsaSign($refund, $privateKeyPath);

        Yii::error('alipay refund: ' . var_export($refund, true), 'yii\error\alipayRefund');
        $postFields = http_build_query($refund);

        $url = 'https://mapi.alipay.com/gateway.do?_input_charset=' . $charset; //支付宝退款地址，post请求
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);//证书检查
        curl_setopt($ch, CURLOPT_POST, 1);

        // 设置代理
        if (Yii::$app->params['config']['proxy']['status']) {
            curl_setopt($ch, CURLOPT_HTTPPROXYTUNNEL, 1);
            curl_setopt($ch, CURLOPT_PROXY, Yii::$app->params['config']['proxy']['host']);
            curl_setopt($ch, CURLOPT_PROXYPORT, Yii::$app->params['config']['proxy']['port']);
        }

        curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);

        $data = curl_exec($ch);

        Yii::error('gtskk: ' . var_export($data, true), 'yii\error\alipayRefund');
        // 返回XML
        if ($data) {
            Yii::error('alipay refund: ' . var_export($data, true), 'yii\error\alipayRefund');
            curl_close($ch);
            $result = $this->xmlToArray($data);
            if ($result['is_success'] === 'T') {
                return array('status' => true, 'msg' => 'OK', 'result' => $result, 'req' => array());
            }

            return array('status' => false, 'msg' => $result['error'], 'result' => $result, 'req' => array());
        }

        $error_no = curl_errno($ch);
        $error = curl_error($ch);
        Yii::error('error_no:' . $error_no . '; error:' . $error, 'yii\error\alipayRefund');
        curl_close($ch);
        return array('status' => false, 'msg' => $error, 'result' => array(), 'req' => array());
    }

    /**
     * 生成支付宝私钥签名
     * @param $data
     * @param $privateKeyPath
     *
     * @return string
     */
    private function alipayRsaSign($data, $privateKeyPath)
    {
        $priKey = file_get_contents($privateKeyPath);
        $keyRes = openssl_get_privatekey($priKey);
        if (!$keyRes) {
            return '';
        }

        $signedStr = $this->getSignContent($data);

        openssl_sign($signedStr, $sign, $keyRes);
        openssl_free_key($keyRes);

        return base64_encode($sign);
    }

    /**
     * 处理支付宝回调参数
     *
     * @param array $para 参数
     *
     * @return array
     */
    private function paraFilter($para)
    {
        $paraFilter = array();
        while (list ($key, $val) = each($para)) {
            if ($key == "sign" || $key == "sign_type" || $val == "") {
                continue;
            }

            $paraFilter[$key] = $para[$key];
        }

        return $paraFilter;
    }

    /**
     * 支付宝支付回调参数rsa验签
     *
     * @param array $data
     * @param string $publickKeyPath 公钥地址
     *
     * @return bool
     */
    public function alipayCheckSign($data, $publickKeyPath)
    {
        $signedStr = $this->getSignContent($data);

        $keyContent = file_get_contents($publickKeyPath);
        $keyRes = openssl_get_publickey($keyContent);
        if (!$keyRes) {
            return false;
        }

        $result = (bool)openssl_verify($signedStr, base64_decode($data['sign']), $keyRes);
        openssl_free_key($keyRes);

        return $result;
    }

    /**
     * 获取签名参数
     *
     * @param $data
     *
     * @return string
     */
    private function getSignContent($data)
    {
        $filtered = $this->paraFilter($data);
        ksort($filtered);
        reset($filtered);

        $str = '';
        foreach ($filtered as $k => $v) {
            $str .= $k . '=' . $v . '&';
        }

        //去掉最后一个&字符
        return substr($str, 0, count($str) - 2);
    }


    /**
     * 微信退款
     * @param $appId
     * @param $machId
     * @param $nonceStr
     * @param $opUserId
     * @param $outRefundNo
     * @param $outTradeNo
     * @param $refundFee
     * @param $totalFee
     * @param $refundAccount
     * @param $key
     * @return array
     */
    public function refund($appId, $machId, $nonceStr, $opUserId, $outRefundNo, $outTradeNo, $refundFee, $totalFee, $refundAccount, $key)
    {
        $sign = strtoupper(md5("appid=$appId&mch_id=$machId&nonce_str=$nonceStr&op_user_id=$opUserId"
            . "&out_refund_no=$outRefundNo&out_trade_no=$outTradeNo&refund_account=$refundAccount&refund_fee=$refundFee&total_fee=$totalFee"
            . "&key=$key"));//sign加密MD5
        $refund = array(
            'appid' => $appId,//应用ID，固定
            'mch_id' => $machId,//商户号，固定
            'nonce_str' => $nonceStr,//随机字符串
            'op_user_id' => $opUserId,//操作员
            'out_refund_no' => $outRefundNo,//商户内部唯一退款单号
            'out_trade_no' => $outTradeNo,//商户订单号,pay_sn码 1.1二选一,微信生成的订单号，在支付通知中有返回
            'refund_account' => $refundAccount ?: 'REFUND_SOURCE_RECHARGE_FUNDS',//退款资金来源
            'refund_fee' => $refundFee,//退款金额
            'total_fee' => $totalFee,//总金额
            'sign' => $sign//签名
        );
        $url = 'https://api.mch.weixin.qq.com/secapi/pay/refund';;//微信退款地址，post请求
        $xml = $this->arrayToXml($refund);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);//证书检查TODO:安全证书好像不能用
        curl_setopt($ch, CURLOPT_SSLCERTTYPE, 'pem');
        curl_setopt($ch, CURLOPT_SSLCERT, dirname(__FILE__) . '/../' . $machId . '/apiclient_cert.pem');
        curl_setopt($ch, CURLOPT_SSLCERTTYPE, 'pem');
        curl_setopt($ch, CURLOPT_SSLKEY, dirname(__FILE__) . '/../' . $machId . '/apiclient_key.pem');
        curl_setopt($ch, CURLOPT_SSLCERTTYPE, 'pem');
        curl_setopt($ch, CURLOPT_CAINFO, dirname(__FILE__) . '/../' . $machId . '/rootca.pem');
        curl_setopt($ch, CURLOPT_POST, 1);

        // 设置代理
        if (Yii::$app->params['config']['proxy']['status']) {
            curl_setopt($ch, CURLOPT_HTTPPROXYTUNNEL, 1);
            curl_setopt($ch, CURLOPT_PROXY, Yii::$app->params['config']['proxy']['host']);
            curl_setopt($ch, CURLOPT_PROXYPORT, Yii::$app->params['config']['proxy']['port']);
        }

        curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);

        $data = curl_exec($ch);
        if ($data) { //返回来的是xml格式需要转换成数组再提取值，用来做更新
            curl_close($ch);
            $result = $this->xmlToArray($data);
            $return_msg = $result['return_msg'];
            if ($result['return_code'] == 'SUCCESS') {
                if ($result['result_code'] == 'SUCCESS') {
                    return array('status' => true, 'msg' => $return_msg, 'result' => $result, 'req' => $refund);
                } else {
                    $return_msg = $result['err_code_des'];
                    return array('status' => false, 'msg' => $return_msg, 'result' => $result, 'req' => $refund);
                }
            } else {
                return array('status' => false, 'msg' => $return_msg, 'result' => $result, 'req' => $refund);
            }
        } else {
            $error_no = curl_errno($ch);
            $error = curl_error($ch);
            Yii::error('error_no:' . $error_no . '; error:' . $error, 'yii\error\refund');
            curl_close($ch);
            return array('status' => false, 'msg' => $error, 'data' => array(), 'req' => $refund);
        }
    }

    /**
     * 数组转xml
     * @param $arr
     * @return string
     */
    public function arrayToXml($arr)
    {
        $xml = '<root>';
        foreach ($arr as $key => $val) {
            if (is_array($val)) {
                $xml .= '<' . $key . '>' . $this->arrayToXml($val) . '</' . $key . '>';
            } else {
                $xml .= '<' . $key . '>' . $val . '</' . $key . '>';
            }
        }
        $xml .= '</root>';
        return $xml;
    }

    /**
     * xml转数组
     * @param $xml
     * @return mixed
     */
    public function xmlToArray($xml)
    {
        libxml_disable_entity_loader(true);
        $xml = simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA);
        return json_decode(json_encode($xml), true);
    }

    /**
     * 提交订单
     * @param $orderParam
     * @param $key
     * @param $tradeType
     * @return array
     */
    public function unifiedorder($orderParam, $key, $tradeType)
    {
        // 设置签名
        $url = "https://api.mch.weixin.qq.com/pay/unifiedorder";// 微信统一下单地址，post请求
        $xml = $this->arrayToXml($orderParam);
        $resData = $this->postXmlCurl($orderParam['mch_id'], $url, $xml);
        //返回结果
        $orderParam['database_fee'] = $orderParam['total_fee'];
        if ($resData['status']) {
            $nonceS = md5(ToolService::createUuid('pay'));
            $prepayId = $resData['data']['prepay_id'];
            $returnMsg = $resData['data']['return_msg'];
            if ($tradeType == XcxConstants::TRADE_TYPE_APP) {
                $package = "Sign=WXPay";
            } else {
                $package = 'prepay_id=' . $prepayId;
            }

            $orderRes = array(
                'nonce_str' => $nonceS,
                'package' => $package,
                'prepay_id' => $prepayId,
                'timestamp' => time() . '',
                'sign' => strtoupper(md5("appid={$orderParam['appid']}&noncestr=$nonceS&package=" . $package . "&partnerid={$orderParam['mch_id']}&prepayid=$prepayId&timestamp=" . time() . "&key=$key")),
                'order_id' => $orderParam['out_trade_no'],
                'return_code' => $resData['data']['return_code'],
                'return_msg' => $returnMsg
            );
            return array('status' => true, 'msg' => $returnMsg, 'result' => $orderRes, 'req' => $orderParam);
        }

	    return array('status' => false, 'msg' => $resData['msg'], 'result' => array(), 'req' => $orderParam);
    }

    /**
     * 生成预支付订单参数
     *
     * @param string $outTradeNo 本地单号
     * @param string $body 交易内容
     * @param string $totalFee 交易费用
     * @param string $notifyUrl 回调地址
     * @param string $appId app编号
     * @param string $machId 商户编号
     * @param string $key 密钥
     * @param $tradeType "APP"/"JSAPI"
     * @param string $openId 微信支付用需要（小程序JSAPI）
     * @return array
     */
    public function  unifiedorderParam($outTradeNo, $body, $totalFee, $notifyUrl, $appId, $machId, $key = "", $tradeType = 'APP', $openId = '')
    {
        $nonceStr = md5(ToolService::createUuid('prepare_id'));
        $createIp = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '127.0.0.1';
        if (empty($openId)) {
            $sign = strtoupper(md5("appid=$appId&body=$body&mch_id=$machId&nonce_str=$nonceStr&notify_url=$notifyUrl&out_trade_no=$outTradeNo&spbill_create_ip=" . $createIp . "&total_fee=$totalFee&trade_type=" . $tradeType
                . "&key=$key")); // 签名
        } else {
            $sign = strtoupper(md5("appid=$appId&body=$body&mch_id=$machId&nonce_str=$nonceStr&notify_url=$notifyUrl&openid=$openId&out_trade_no=$outTradeNo&spbill_create_ip=" . $createIp . "&total_fee=$totalFee&trade_type=" . $tradeType
                . "&key=$key")); // 签名
        }
        $orderParam = array(
            'appid' => $appId,//应用ID，固定
            'mch_id' => $machId,//商户号，固定
            'nonce_str' => $nonceStr,//随机字符串
            'total_fee' => $totalFee,//总金额
            'body' => $body, // 描述
            'notify_url' => $notifyUrl, // 通知地址
            'out_trade_no' => $outTradeNo, // 交易号
            'trade_type' => $tradeType, // 交易类型
            'spbill_create_ip' => $createIp, // 终端IP
            'sign' => $sign, // 签名
        );
        if (empty($openId) == false) {
            $orderParam['openid'] = $openId;
        }
        return $orderParam;
    }

    /**
     * 提交微信订单
     *
     * *
     * @param string $outTradeNo 交易号
     * @param string $nonceStr 签名
     * @param string $body 备注
     * @param float $totalFee 总金额（单位分）
     * @param float $wxFee 微信实际支付的金额（单位分）
     * @param string $openId 微信识别码
     *
     * @return array
     */
    public function submitWxOrder($outTradeNo, $nonceStr, $body, $totalFee, $wxFee, $openId)
    {

        $config = Yii::$app->params['config'];
        $appId = $config['public']['mhealth_lc']['appId']; // 绑定支付的APPID
        $machId = $config['public']['mhealth_lc']['machId']; // 商户号
        $key = $config['public']['mhealth_lc']['key']; // 商户支付密钥

        $notifyUrl = isset($config['notifyPrefix']) ? $config['notifyPrefix'] . 'trans/pay/notify' : '';
        if ($notifyUrl) {
            $sign = strtoupper(md5("appid=$appId&body=$body&mch_id=$machId&nonce_str=$nonceStr&notify_url=$notifyUrl&openid=$openId&out_trade_no=$outTradeNo&spbill_create_ip=" . (isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '') . "&total_fee=$wxFee&trade_type=JSAPI"
                . "&key=$key")); // 签名

            $orderParam = array(
                'appid' => $appId,//应用ID，固定
                'mch_id' => $machId,//商户号，固定
                'nonce_str' => $nonceStr,//随机字符串
                'total_fee' => $wxFee,//总金额
                'openid' => $openId,
                'body' => $body, // 描述
                'notify_url' => $notifyUrl, // 通知地址
                'out_trade_no' => $outTradeNo, // 交易号
                'trade_type' => XcxConstants::TRADE_TYPE_JSAPI, // 交易类型
                'spbill_create_ip' => isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '', // 终端IP
                'sign' => $sign, // 签名
            );
            Yii::info('统一下单请求参数：' . json_encode($orderParam), 'yii\info\submitWxOrder');
            // 设置签名
            $url = "https://api.mch.weixin.qq.com/pay/unifiedorder";// 微信统一下单地址，post请求
            $xml = $this->arrayToXml($orderParam);
            $resData = $this->postXmlCurl($machId, $url, $xml);
            //返回结果
            $orderParam['database_fee'] = $totalFee;
            if ($resData['status']) {
                $nonceS = md5(ToolService::createUuid('pay'));
                $prepayId = $resData['data']['prepay_id'];
                $returnMsg = $resData['data']['return_msg'];
                $orderRes = array(
                    'nonce_str' => $nonceS,
                    'package' => 'prepay_id=' . $prepayId,
                    'timestamp' => time() . '',
                    'pay_sign' => strtoupper(md5("appId=$appId&nonceStr=$nonceS&package=prepay_id=" . $prepayId . "&signType=MD5&timeStamp=" . time() . "&key=$key")),
                    'order_id' => $outTradeNo,
                    'return_code' => $resData['data']['return_code'],
                    'return_msg' => $returnMsg
                );
                return array('status' => true, 'msg' => $returnMsg, 'result' => $orderRes, 'req' => $orderParam);
            }

            Yii::error('msg:' . $resData['msg'], 'yii\error\submitWxOrder');

            return array('status' => false, 'msg' => $resData['msg'], 'result' => array(), 'req' => $orderParam);
        }

        return array('status' => false, 'msg' => Yii::t('app', 'ErrorWithCode', array(Constants::WX_EXCEPTION)), 'result' => array(), 'req' => array());
    }

    /**
     * @param string $xml 源xml
     *
     * @return array|mixed
     */
    public function notify($xml)
    {
        $result = array(
            'code' => Constants::SUCCESS,
            'msg' => Yii::t('app', 'Success'),
            'data' => array()
        );
        do {
            $xmlArr = $this->xmlToArray($xml);
            Yii::error("xml to array :" . json_encode($xmlArr), 'yii\error\notify');
            if (!isset($xmlArr['return_code']) || $xmlArr['return_code'] != 'SUCCESS' || !isset($xmlArr['sign'])) {
                $result['code'] = Constants::ERROR;
                $result['msg'] = '解析参数失败';
                break;
            }

            $key = Yii::$app->params['config']['public']['mhealth_lc']['key']; // 商户支付密钥
            if (!$this->checkSign($xmlArr, $key)) {
                $result['code'] = Constants::ERROR;
                $result['msg'] = '签名验证失败';
                break;
            }

            Yii::error("check sign passed:" . json_encode($xmlArr), 'yii\error\notify');
            if (!isset($xmlArr['transaction_id']) || !isset($xmlArr['appid']) || !isset($xmlArr['mch_id'])) {
                $result['code'] = Constants::ERROR;
                $result['msg'] = '输入参数不正确';
                break;
            }

            //查询订单，判断订单真实性
            $queryRes = $this->queryOrder($xmlArr["transaction_id"], $xmlArr['appid'], $xmlArr['mch_id'], $key);
            Yii::error("查询订单结果为:" . json_encode($queryRes), 'yii\error\notify');
            if (!$queryRes['status']) {
                $result['code'] = Constants::ERROR;
                $result['msg'] = $queryRes['msg'];
                break;
            }

            $result['data'] = $xmlArr;
        } while (false);

        return $result;
    }

	/**
	 * 查询订单
	 *
	 * @param string $transactionId 交易ID
	 * @param string $appId appid
	 * @param string $machId 商户号
	 * @param string $key 支付密钥
	 *
	 * @return array
	 */
    private function queryOrder($transactionId, $appId, $machId, $key)
    {
        $url = "https://api.mch.weixin.qq.com/pay/orderquery";
        $nonceStr = md5(uniqid(mt_rand(), true));

        $sign = strtoupper(md5("appid=$appId&mch_id=$machId&nonce_str=$nonceStr&transaction_id=$transactionId" . "&key=$key")); // 签名

        $orderParam = array(
            'appid' => $appId,//应用ID，固定
            'mch_id' => $machId,//商户号，固定
            'nonce_str' => $nonceStr,//随机字符串
            'transaction_id' => $transactionId, // 交易号
            'sign' => $sign, // 签名
        );
        $xml = $this->arrayToXml($orderParam);
        $response = $this->postXmlCurl($machId, $url, $xml);
        Yii::info("order query result:" . json_encode($response), 'yii\info\queryOrder');

        return $response;
    }

    /**
     * @param $machId
     * @param $url
     * @param $xml
     * @param bool $useCert 是否使用证书
     * @param int $second 超时时间，单位秒
     *
     * @return array
     */
    private function postXmlCurl($machId, $url, $xml, $useCert = false, $second = 6)
    {
        $res = array(
            'status' => false,
            'msg' => '',
            'data' => array()
        );

        $ch = curl_init();
        //设置超时
        curl_setopt($ch, CURLOPT_TIMEOUT, $second);

        curl_setopt($ch, CURLOPT_URL, $url);
//			curl_setopt($ch,CURLOPT_SSL_VERIFYPEER, TRUE);
//			curl_setopt($ch,CURLOPT_SSL_VERIFYHOST, 2);//严格校验
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);//严格校验
        //设置header
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        //要求结果为字符串且输出到屏幕上
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

        // 设置代理
        if (Yii::$app->params['config']['proxy']['status']) {
            curl_setopt($ch, CURLOPT_HTTPPROXYTUNNEL, 1);
            curl_setopt($ch, CURLOPT_PROXY, Yii::$app->params['config']['proxy']['host']);
            curl_setopt($ch, CURLOPT_PROXYPORT, Yii::$app->params['config']['proxy']['port']);
        }

        //设置证书
        if ($useCert) {
            curl_setopt($ch, CURLOPT_SSLCERTTYPE, 'pem');
            curl_setopt($ch, CURLOPT_SSLCERT, dirname(__FILE__) . '/' . $machId . '/apiclient_cert.pem');
            curl_setopt($ch, CURLOPT_SSLKEYTYPE, 'pem');
            curl_setopt($ch, CURLOPT_SSLKEY, dirname(__FILE__) . '/' . $machId . '/apiclient_key.pem');
            curl_setopt($ch, CURLOPT_SSLCERTTYPE, 'pem');
            curl_setopt($ch, CURLOPT_CAINFO, dirname(__FILE__) . '/' . $machId . '/rootca.pem');
        }
        //post提交方式
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
        //运行curl
        $data = curl_exec($ch);
        //返回结果
        if ($data) {
            $result = $this->xmlToArray($data);
            if (isset($result['return_code']) && $result['return_code'] == 'SUCCESS' && $result['result_code'] === 'SUCCESS') {
                $res['status'] = true;
                $res['data'] = $result;
            } else {
                $res['msg'] = isset($result['err_code_des']) ? $result['err_code_des'] : $result['return_msg'];
            }
        } else {
            $errorNo = curl_errno($ch);
            $error = curl_error($ch);
            Yii::error('error_no:' . $errorNo . '; error:' . $error, 'yii\error\postXmlCurl');

            $res['msg'] = $error;
        }
        curl_close($ch);

        return $res;
    }

    /**
     * 核对签名
     *
     * @param array $result 结果数组
     *
     * @param string $key 校验key
     *
     * @return bool
     */
    public function checkSign($result, $key)
    {
        $flag = false;

        // 制作签名
        //签名步骤一：按字典序排序参数
        ksort($result);
        $string = "";
        foreach ($result as $k => $v) {
            if ($k != "sign" && $v != "" && !is_array($v)) {
                $string .= $k . "=" . $v . "&";
            }
        }

        $string = trim($string, "&");
        //签名步骤二：在string后加入KEY
        $string = $string . "&key=" . $key;
        //签名步骤三：MD5加密
        $string = md5($string);
        //签名步骤四：所有字符转为大写
        $sign = strtoupper($string);
        if ($result['sign'] == $sign) {
            $flag = true;
        }

        return $flag;
    }
}