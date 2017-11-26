<?php
/**
 * Created by PhpStorm.
 * User: Henry
 * Date: 2017/11/26
 * Time: 9:14
 */
require_once './UploadUrl.php';


$urls = [
    'https://timgsa.baidu.com/timg?image&quality=80&size=b9999_10000&sec=1511672329735&di=b04f2e0f94fd6b84c8d918d0ac1f9291&imgtype=0&src=http%3A%2F%2Fwww.zhlzw.com%2FUploadFiles%2FArticle_UploadFiles%2F201204%2F20120412123921838.jpg',
    'https://timgsa.baidu.com/timg?image&quality=80&size=b9999_10000&sec=1511672329734&di=ea5d3a9efc5b386e56ea8ab1f1dc7d38&imgtype=0&src=http%3A%2F%2Fwww.zhlzw.com%2FUploadFiles%2FArticle_UploadFiles%2F201204%2F20120412123926750.jpg',
    'https://timgsa.baidu.com/timg?image&quality=80&size=b9999_10000&sec=1511672329734&di=48700d825d552cfa3623c037877af311&imgtype=0&src=http%3A%2F%2Fimg1.50tu.com%2Fmeinv%2Fxinggan%2F2013-11-16%2Fe65e7cd83f37eed87067299266152807.jpg',
    'https://timgsa.baidu.com/timg?image&quality=80&size=b9999_10000&sec=1511672329733&di=b975ddfeb403e8122f447b73f817ea89&imgtype=0&src=http%3A%2F%2Fwww.zhlzw.com%2FUploadFiles%2FArticle_UploadFiles%2F201204%2F20120412123910738.jpg',
    'https://timgsa.baidu.com/timg?image&quality=80&size=b9999_10000&sec=1511672329733&di=185639422471017ec0a2c463a11b9ae2&imgtype=0&src=http%3A%2F%2Fpic28.photophoto.cn%2F20130928%2F0036036832560635_b.jpg',
    'https://timgsa.baidu.com/timg?image&quality=80&size=b9999_10000&sec=1511672456518&di=6b700a63ee07d0ba4d5a8cb37e4e1c70&imgtype=0&src=http%3A%2F%2Fimg.taopic.com%2Fuploads%2Fallimg%2F140330%2F235059-1403300RF432.jpg',
    'https://timgsa.baidu.com/timg?image&quality=80&size=b9999_10000&sec=1511672456517&di=d7ce0e91d5f9640bc1980880fd410ee5&imgtype=0&src=http%3A%2F%2Fpic27.photophoto.cn%2F20130630%2F0036036877482236_b.jpg',
    'https://timgsa.baidu.com/timg?image&quality=80&size=b9999_10000&sec=1511672456517&di=b07d10c102331833ff33a0ade8c3ca0a&imgtype=0&src=http%3A%2F%2Fwww.taopic.com%2Fuploads%2Fallimg%2F140515%2F240386-1405150H95917.jpg'

];
foreach ($urls as $v){
    $upload = new UploadUrl($v);
    echo "<hr>";
    var_dump($upload->getImage());
}
