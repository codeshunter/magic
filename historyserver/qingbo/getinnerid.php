<?php

/**
 * @desc 此demo为测试调去数据的方式
 * 获取用户信息
 */
header("Access-Control-Allow-Origin:*");
header("Content-type", "application/json;chartset=utf-8");
$key = $_POST;
$pwd = $key['pwd'];
$wx_name = $key['wx_name'];
if (md5($pwd) != '9ad18f7f63d227232f3fe2ce57006ae2') {
    die("error!!!");
}
include('GsdataLib.php');
$gsdata = new GsdataLib;
$param = array('wx_name' => $wx_name);
$data = $gsdata->call('wx/wxapi/nickname_one',$param);
echo $data;
?>