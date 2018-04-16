# examples

这些 examples 旨在帮助你快速了解使用七牛的sdk。这些demo都是可以直接运行的， 但是在运行之前需要填上您自己的参数。

比如：

* `$bucket`  需要填上您想操作的 [bucket名字](http://developer.qiniu.com/docs/v6/api/overview/concepts.html#bucket)。
* `$accessKey` 和 `$secretKey` 可以在我们的[管理后台](https://portal.qiniu.com/setting/key)找到。
* 在进行`视频转码`， `压缩文件`等异步操作时 需要使用到的队列名称也可以在我们[管理后台](https://portal.qiniu.com/mps/pipeline)新建。


//----------------------------------------upload demo2 ----------------------------------------
// 上传文件到七牛
$filePath = './php-logo.png';
$key = 'php-logo.png';
list($ret, $err) = $uploadMgr->putFile($token, $key, $filePath);
echo "\n====> putFile result: \n";
if ($err !== null) {
    var_dump($err);
} else {
    var_dump($ret);
}


//----------------------------------------upload demo3 ----------------------------------------
// 上传文件到七牛后， 七牛将文件名和文件大小回调给业务服务器.
// 可参考文档: http://developer.qiniu.com/docs/v6/api/reference/security/put-policy.html
$policy = array(
    'callbackUrl' => 'http://172.30.251.210/callback.php',
    'callbackBody' => 'filename=$(fname)&filesize=$(fsize)'
//  'callbackBodyType' => 'application/json',                       
//  'callbackBody' => '{"filename":$(fname), "filesize": $(fsize)}'  //设置application/json格式回调
);
$token = $auth->uploadToken($bucket, null, 3600, $policy);


list($ret, $err) = $uploadMgr->putFile($token, null, $key);
echo "\n====> putFile result: \n";
if ($err !== null) {
    var_dump($err);
} else {
    var_dump($ret);
}


//----------------------------------------upload demo4 ----------------------------------------
//上传视频，上传完成后进行m3u8的转码， 并给视频打水印
$wmImg = Qiniu\base64_urlSafeEncode('http://Bucket_Name.qiniudn.com/logo-s.png');
$pfop = "avthumb/m3u8/wmImage/$wmImg";

//转码完成后回调到业务服务器。（公网可以访问，并相应200 OK）
$notifyUrl = 'http://notify.fake.com';

//独立的转码队列：https://portal.qiniu.com/mps/pipeline
$pipeline = 'abc';

$policy = array(
    'persistentOps' => $pfop,
    'persistentNotifyUrl' => $notifyUrl,
    'persistentPipeline' => $pipeline
);
$token = $auth->uploadToken($bucket, null, 3600, $policy);

list($ret, $err) = $uploadMgr->putFile($token, null, $key);
echo "\n====> putFile result: \n";
if ($err !== null) {
    var_dump($err);
} else {
    var_dump($ret);
}
