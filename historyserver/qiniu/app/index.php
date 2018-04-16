<?php
require_once __DIR__ . '/../autoload.php';

use Qiniu\Auth;
use Qiniu\Storage\UploadManager;
header("Access-Control-Allow-Origin:*");
$accessKey = 'SE9bvdKDRprT-MV529Ifa8A_bKxicddHkdpHzWB0';
$secretKey = 'vNiJL1XIl4lZxSp8wMfKtVUgzncyO9N-rYoChf4x';
$auth = new Auth($accessKey, $secretKey);

$bucket = 'dongtai';
$token = $auth->uploadToken($bucket);
$uploadMgr = new UploadManager();
$content = $_POST['content'];
$pwd = $_POST['pwd'];
//var_dump($_POST);
//echo("-->" . $content . $pwd);die;

if (md5($pwd) != '9ad18f7f63d227232f3fe2ce57006ae2') {
	die('wrong');
}
//----------------------------------------upload demo1 ----------------------------------------
// 上传字符串到七牛
list($ret, $err) = $uploadMgr->put($token, null, $content);
//list($ret, $err) = $uploadMgr->put($token, null, "asdasdasd");
//echo "\n====> put result: \n";
if ($err !== null) {
    var_dump($err);
} else {
    //var_dump($ret);
	echo $ret['key'];
}

