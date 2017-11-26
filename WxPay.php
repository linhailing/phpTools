<?php
/**
 * Created by PhpStorm.
 * User: Henry
 * Date: 2017/11/26
 * Time: 15:30
 * 微信支付类
 */
class WxPay{
    private  $appid;
    private $appsecret;
    protected $values = array();
    private $KEY = '15880078288158800782881588007828';
    public function __construct($appid, $appsecret){
        session_start();
        $this->appid = $appid;
        $this->appsecret = $appsecret;
    }
    public function setValues($data=array()){
        $this->values = $data;
    }
    public function getValues(){
        return $this->values;
    }

    /**
     * 设置签名，详见签名生成算法
     * @return 签名，本函数不覆盖sign成员变量
     */
    public function SetSign()
    {
        $sign = $this->MakeSign();
        $this->values['sign'] = $sign;
        return $sign;
    }

    /**
     * 获取签名，详见签名生成算法的值
     * @return 值
     **/
    public function GetSign()
    {
        return $this->values['sign'];
    }
    public function getSignPackage() {
        $jsapiTicket = $this->getJsApiTicket();
        // 注意 URL 一定要动态获取，不能 hardcode.
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
        $url = "$protocol$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        //$url = 'http://test.phptools.com/wx.php';
        $timestamp = time();
        $nonceStr = $this->createNonceStr();
        // 这里参数的顺序要按照 key 值 ASCII 码升序排序
        $string = "jsapi_ticket=$jsapiTicket&noncestr=$nonceStr&timestamp=$timestamp&url=$url";
        $signature = sha1($string);
        $signPackage = array(
            "appId"     => $this->appid,
            "nonceStr"  => $nonceStr,
            "timestamp" => $timestamp,
            "url"       => $url,
            "signature" => $signature
        );
        return $signPackage;
    }

    /**
     * @param int $length 成功字符串长度
     * @return string 返回字符串
     */
    private function createNonceStr($length = 16) {
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        $str = "";
        for ($i = 0; $i < $length; $i++) {
            $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
        }
        return $str;
    }

    public function getJsApiTicket(){
        $data = json_decode($this->get_php_file('jsapi_ticket.php'));
        if($data->expire_time < time()){
            $accessToken = $this->getAccessToken();
            $url = 'https://api.weixin.qq.com/cgi-bin/ticket/getticket?type=jsapi&access_token='.$accessToken;
            $res = json_decode($this->httpQuest($url));
            $ticket = $res->ticket;
            if($ticket){
                $data->expire_time = time() + 7000;
                $data->jsapi_ticket = $ticket;
                $this->set_php_file("jsapi_ticket.php", json_encode($data));
            }
        }else{
            return  $ticket = $data->jsapi_ticket;
        }
        return $ticket;
    }

    /**
     * @return mixed 返回access token
     */
    public function getAccessToken(){
        // access_token 应该全局存储与更新，以下代码以写入到文件中做示例
        $data = json_decode($this->get_php_file('access_token.php'));
        if($data->expire_time < time()){
            $url = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.$this->appid.'&secret='.$this->appsecret;
            $res = json_decode($this->httpQuest($url));
            $access_token = $res->access_token;
            if($access_token){
                $data->expire_time = time() + 7000;
                $data->access_token = $access_token;
                $this->set_php_file("access_token.php", json_encode($data));
            }
        }else{
            $access_token = $data->access_token;
        }
        return $access_token;
    }
    private function httpQuest($url, $type='get', $data=[]){
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_TIMEOUT, 500);
        // 为保证第三方服务器与微信服务器之间数据传输的安全性，所有微信接口采用https方式调用，必须使用下面2行代码打开ssl安全校验。
        // 如果在部署过程中代码在此处验证失败，请到 http://curl.haxx.se/ca/cacert.pem 下载新的证书判别文件。
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_URL, $url);
        if($type == 'post'){
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        }
        $res = curl_exec($curl);
        curl_close($curl);
        return $res;
    }

    /**
     * @param $filename 文件名称
     * @return string 返回文件
     */
    private function get_php_file($filename){
        return trim(substr(file_get_contents($filename), 15));
    }

    /**
     * @param $filename 文件名称
     * @param $content 写入内容
     */
    private function set_php_file($filename, $content){
        $fp = fopen($filename, "w");
        fwrite($fp, "<?php exit();?>" . $content);
        fclose($fp);
    }
    /**
     * jssdk统一下订单入口
     */
    public function WxPayUnifiedOrder(){
        $url = 'https://api.mch.weixin.qq.com/pay/unifiedorder';
        $arr = array(
            'appid'=>$this->appid, //公众账号ID
            'mch_id'=>'1280456201', //商户号
            'nonce_str'=>'1', //随机字符串
            'sign'=>'', //签名
            'sign_type'=>'MD5', //签名类型
            'body'=>'2222', //商品描述
            'out_trade_no'=>'2017-11-26-00001', //商户订单号
            'total_fee'=>'1', //标价金额
            'spbill_create_ip'=>'127.0.0.1', //终端IP
            //通知地址 异步接收微信支付结果通知的回调地址，通知url必须为外网可访问的url，不能携带参数。
            'notify_url'=>'http://test.phptools.com/test.php',
            'trade_type'=>'JSAPI', //交易类型
            'openid'=>'okoARtymr63w1KdOpOMZbNNp8lJk', //用户标识
        );
        $this->setValues($arr);
        //生成签名
        $this->SetSign();
        //to xml
        $xml  = $this->ToXml();
        $xmlInfo = $this->postXmlCurl($xml, $url);
        $arr2 = $this->FromXml($xmlInfo);
        if (empty($arr2['prepay_id'])){
            return array('code'=>404, 'msg'=>'error');
        }
        //这里开始加密
        $appId = $this->appid;
        $timeStamp = time();
        $nonceStr = $this->createNonceStr(32);
        $package = 'prepay_id='.$arr2['prepay_id'];
        $signType = 'MD5';
        $tmp = array(
            'appId'=>$appId,
            'timeStamp'=>$timeStamp,
            'nonceStr'=>$nonceStr,
            'package'=>$package,
            'signType'=>$signType,
        );
        //签名步骤一：按字典序排序参数
        ksort($tmp);
        $string = $this->ToParams($tmp);
        //签名步骤二：在string后加入KEY
        $string = $string . "&key=".$this->KEY;
        //签名步骤三：MD5加密
        $string = md5($string);
        //签名步骤四：所有字符转为大写
        $result = strtoupper($string);
        $data = array(
            'prepay_id' => $arr2['prepay_id'],
            'appId' => $appId,
            'timeStamp'=>$timeStamp,
            'nonceStr'=>$nonceStr,
            'package'=>$package,
            'signType'=>$signType,
            'paySign'=>$result
        );
        return array('code'=>200, 'data'=>$data);
    }
    /**
     * 输出xml字符
     * @throws WxPayException
     **/
    public function ToXml()
    {
        if(!is_array($this->values) || count($this->values) <= 0){
            throw new Exception("数组数据异常！");
        }
        $xml = "<xml>";
        foreach ($this->values as $key=>$val){
            if (is_numeric($val)){
                $xml.="<".$key.">".$val."</".$key.">";
            }else{
                $xml.="<".$key."><![CDATA[".$val."]]></".$key.">";
            }
        }
        $xml.="</xml>";
        return $xml;
    }

    /**
     * 将xml转为array
     * @param string $xml
     */
    public function FromXml($xml)
    {
        if(!$xml){
            throw new WxPayException("xml数据异常！");
        }
        //将XML转为array
        //禁止引用外部xml实体
        libxml_disable_entity_loader(true);
        $this->values = json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
        return $this->values;
    }

    /**
     * 格式化参数格式化成url参数
     */
    public function ToUrlParams(){
        $buff = "";
        foreach ($this->values as $k => $v)
        {
            if($k != "sign" && $v != "" && !is_array($v)){
                $buff .= $k . "=" . $v . "&";
            }
        }
        $buff = trim($buff, "&");
        return $buff;
    }

    public function ToParams($arr = array()){
        $buff = "";
        foreach ($arr as $k => $v)
        {
            $buff .= $k . "=" . $v . "&";
        }
        $buff = trim($buff, "&");
        return $buff;
    }
    /**
     * 生成签名
     * @return 签名，本函数不覆盖sign成员变量，如要设置签名需要调用SetSign方法赋值
     */
    public function MakeSign(){
        //签名步骤一：按字典序排序参数
        ksort($this->values);
        $string = $this->ToUrlParams();
        //签名步骤二：在string后加入KEY
        $string = $string . "&key=".$this->KEY;

        //签名步骤三：MD5加密
        $string = md5($string);
        //签名步骤四：所有字符转为大写
        $result = strtoupper($string);
        return $result;
    }

    /**
     * 以post方式提交xml到对应的接口url
     *
     * @param string $xml  需要post的xml数据
     * @param string $url  url
     * @param bool $useCert 是否需要证书，默认不需要
     * @param int $second   url执行超时时间，默认30s
     * @throws WxPayException
     */
    private static function postXmlCurl($xml, $url, $useCert = false, $second = 30){
        $host = "0.0.0.0";//"10.152.18.220";
        $port = 0;//8080;
        $sslcert_path = '';
        $sslkey_path = '';
        $ch = curl_init();
        //设置超时
        curl_setopt($ch, CURLOPT_TIMEOUT, $second);

        //如果有配置代理这里就设置代理
        if($host != "0.0.0.0" && $port != 0){
            curl_setopt($ch,CURLOPT_PROXY, $host);
            curl_setopt($ch,CURLOPT_PROXYPORT, $port);
        }
        curl_setopt($ch,CURLOPT_URL, $url);
//        curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,TRUE);
//        curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,2);//严格校验
        curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,FALSE);
        curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,FALSE);//严格校验2
        //设置header
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        //要求结果为字符串且输出到屏幕上
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

        if($useCert == true){
            //设置证书
            //使用证书：cert 与 key 分别属于两个.pem文件
            curl_setopt($ch,CURLOPT_SSLCERTTYPE,'PEM');
            curl_setopt($ch,CURLOPT_SSLCERT, $sslcert_path);
            curl_setopt($ch,CURLOPT_SSLKEYTYPE,'PEM');
            curl_setopt($ch,CURLOPT_SSLKEY, $sslkey_path);
        }
        //post提交方式
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
        //运行curl
        $data = curl_exec($ch);
        //返回结果
        if($data){
            curl_close($ch);
            return $data;
        } else {
            $error = curl_errno($ch);
            curl_close($ch);
            throw new Exception("curl出错，错误码:$error");
        }
    }
}

$appid = 'wx9af969088cb3a75d';
$appsecret = '5a70b6232eaa5591f641e428f2a12392';
$wp = new WxPay($appid, $appsecret);
if($_GET['type'] == 'init'){
    $data = $wp->getSignPackage();
    echo json_encode($data);
}else if($_GET['type'] == 'wxpay'){
    $data = $wp->WxPayUnifiedOrder();
    echo json_encode($data);
}
