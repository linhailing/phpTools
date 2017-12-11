return

href="javascript:void(0);" onclick="window.history.go(-1)"


window.onload=function(){
    var ck=new MyCookie("HasLoaded"); //每个页面的new Cookie名HasLoaded不能相同
    if(ck.Read()==null){//未加载过，Cookie内容为空
        var dd = new Date();
        dd = new Date(dd.getYear() + 1900, dd.getMonth(), dd.getDate());
        dd.setDate(dd.getDate() + 365);
        ck.setExpiresTime(dd);
        ck.Write("true"); //设置Cookie。只要IE不关闭，Cookie就一直存在
        alert();
    }
    else{//Cookie存在，表示页面是被刷新的
        alert("页面刷新");
    }
}


//记录历史页面
var referurl = document.referrer;//上级网址
$("input[name=referurl]").val(referurl);

var  referurl = document.getElementById('referurl').value;
addcookie('referurl',referurl);


window.location='https://open.weixin.qq.com/connect/oauth2/authorize?appid='+Appid+'&redirect_uri='+Redirect_uri+'%2findex.php%3fact%3dlogin&response_type=code&scope=snsapi_userinfo&state=123&connect_redirect=1#wechat_redirect';


http://www.ibookway.cn/


addcookie('key', result.datas.key);
var key = getcookie('key');

图书组价：goods_lease_price
价格：如果goods_promotion_price不空，则是goods_promotion_price，
负责：goods_price


## 配置微信支付
1、修改网址
2、配置微信支付商户id等
3、配置商户中支付授权目录

mysql 创建定时任务的使用：
1. 查看是否开启
show VARIABLES LIKE '%sche%';
2.设置开启
set GLOBAL event_scheduler=1;



select created_time,name from
(
select name,dtime,rank,ptime,ptime2 from(
    select cg_tmp.*, @rownum :=@rownum + 1,
    if(TIMESTAMPDIFF(MINUTE, @ptime,cg_tmp.dtime)<5,@rank:=@rank ,@rank:=@rank+1) as rank,
    @ptime2:=cg_tmp.dtime as ptime2,
    if(TIMESTAMPDIFF(MINUTE, @ptime,cg_tmp.dtime)< 5, @ptime=null,@ptime:=@ptime2) as ptime
    from( select * from `timerecord`  order by dtime ) cg_tmp,

    (select @rownum :=0 , @ptime := null ,@rank:=0,@ptime2 := null) a )  result
) a
where ptime is not null


CREATE  PROCEDURE emp_test()
BEGIN
        #update emp set updated_time = now();
        SELECT order_amount,add_time,return_time INTO @orderAmount,@addTime,@returnTime FROM sykjshop_order WHERE order_id = 9688;
        select @orderAmount, @addTime , @returnTime;
        #SET @days = timestampdiff(day,FROM_UNIXTIME(@returnTime,'Y-m-d'),FROM_UNIXTIME(@addTime,'Y-m-d'));
        #SET @days = timestampdiff(day,FROM_UNIXTIME(@returnTime,'%Y-%m-%d'),FROM_UNIXTIME(@addTime,'%Y-%m-%d'));
        SELECT FROM_UNIXTIME(@returnTime,'%Y-%m-%d');
        SELECT TIMESTAMPDIFF(DAY,FROM_UNIXTIME(@returnTime,'%Y-%m-%d'),FROM_UNIXTIME(@addTime,'%Y-%m-%d'));
        SELECT @days;


        #SELECT if(@returnDays=0,1,@returnDays) as days INTO @days;

        #SET @amount = @days * @orderAmount

        #SELECT @amount;
END;

# 创建event_e_test
create event if not EXISTS em_test
on SCHEDULE EVERY 30 SECOND
on COMPLETION PRESERVE
do call emp_test();

# 关闭事件任务
alter event em_test ON
COMPLETION PRESERVE ENABLE;

# 开户事件任务
alter event em_test ON
COMPLETION PRESERVE DISABLE;



// 页面跳转返回浏览记录

function GetPageScroll()
{
    var x, y; if(window.pageYOffset)
    {    // all except IE
    y = window.pageYOffset;
    x = window.pageXOffset;
    } else if(document.documentElement && document.documentElement.scrollTop)
    {    // IE 6 Strict
    y = document.documentElement.scrollTop;
    x = document.documentElement.scrollLeft;
    } else if(document.body) {    // all other IE
    y = document.body.scrollTop;
    x = document.body.scrollLeft;
    }
    var storage = window.localStorage;
    storage.pageScrollY=y
}
function getStorageY(){
    var y=localStorage.getItem("pageScrollY");
    document.body.scrollTop=y
}




current_timestamp();

set @time = now();

INSERT INTO emp(`name`, `created_time`) VALUES ('test2', now());


INSERT INTO emp(`name`, `content`) VALUES ('test', now());

# 查看event是否开启
show VARIABLES like '%sche%';

# 开启事件计划
set GLOBAL event_scheduler = 1;

# 创建存储过程test

CREATE  PROCEDURE emp_test()
BEGIN
update emp set updated_time = now();
END;

# 创建event_e_test
create event if not EXISTS em_test
on SCHEDULE EVERY 5*60 SECOND
on COMPLETION PRESERVE
do call emp_test();

# 关闭事件任务
alter event em_test ON
COMPLETION PRESERVE ENABLE;

# 开户事件任务
alter event em_test ON
COMPLETION PRESERVE DISABLE;


SELECT * NOW() AS 'nowtime' TIMEDIFF(now(),'2147483647') as 'gtime';

select * from `HOUR`()




select * from sykjshop_member where member_id='1936'


update sykjshop_member SET member_mobile_bind = '0', member_mobile='0',
 available_predeposit = '0', freeze_predeposit='0' , is_predeposit='0'
where member_id='1936';



update sykjshop_member SET member_mobile_bind = '0', member_mobile='0', freeze_predeposit='0.0' , is_predeposit='0'
where member_id='2015';



## 通过当前位置计算附近商铺
 sql: SELECT *,(2 * 6371 * ASIN(SQRT(POW(SIN((23.99-lat)*PI()/360),2)
+COS(23.99*PI()/180)*COS(23.99*PI()/180)*POW(SIN((113.01-lng)*PI()/360),2)))) 
AS distance FROM test ORDER BY distance ASC;



## 微信支付接口说明

1. 获取token： https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=xxx&secret=xxxx

2.jsapi_ticket:  https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token=xxxx

js 接口签名工具： http://mp.weixin.qq.com/debug/cgi-bin/sandbox?t=jsapisign

js 支付接口工具：https://pay.weixin.qq.com/wiki/tools/signverify


