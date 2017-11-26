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
    public function __construct($appid, $appsecret){
        session_start();
        $this->appid = $appid;
        $this->appsecret = $appsecret;
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
            "signature" => $signature,
            //"rawString" => $string
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

}