<?php
include_once './WxPay.php';
$appid = 'wx5da47318d62796fa';
$appsecret = '81b21cf819fe6d0fd3491ae020ac3a34';
$wp = new WxPay($appid, $appsecret);
$data = $wp->getSignPackage();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=yes" />
    <title>测试微信JS-SDK</title>
</head>
<body>
<h1>JS-SDK</h1>
<script src="http://res.wx.qq.com/open/js/jweixin-1.2.0.js"></script>
<script>
    wx.config({
        debug: true, // 开启调试模式,调用的所有api的返回值会在客户端alert出来
        appId: '<?php echo $data["appId"]; ?>', // 必填，公众号的唯一标识
        timestamp: '<?php echo $data["timestamp"]; ?>', // 必填，生成签名的时间戳
        nonceStr: '<?php echo $data["nonceStr"]; ?>', // 必填，生成签名的随机串
        signature: '<?php echo $data["signature"]; ?>',// 必填，签名，见附录1
        jsApiList: [] // 必填，需要使用的JS接口列表，所有JS接口列表见附录2
    });
</script>
</body>
</html>