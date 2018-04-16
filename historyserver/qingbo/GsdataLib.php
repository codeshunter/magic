<?php
/**
 * 功能：新媒体指数平台接口实现
 * 版本：1.0.0
 * 修改日期：2015-07-23
 */
class GsdataLib
{
    const URL = 'http://open.gsdata.cn/api/';
    const APP_ID = '5pPfzP168B78e0k7tloS';
    const APP_KEY = 'gHPHwAzQ5e24Ui4EVa94d991w';

    /**
     * @desc 接口的数据签名生成方法
     * 数据签名
     * @param array $data
     * @return string
     */
    protected function signature($data){
        ksort($data,SORT_STRING);
        return md5(strtolower($this->json_encode($data)).self::APP_KEY);
    }

    /**
     * @desc 处理接口的返回值，并进行验签
     * 		如果返回值不合法或者验签失败，则返回错误提示
     * @param $url
     * @param array $param
     * @param bool $raw 是否原样输出
     * @return mixed
     */
    public function call($url,$param = array(),$raw = true){
        $param['appid'] = self::APP_ID;
        $param['signature'] = $this->signature($param);
        $post_string = base64_encode($this->json_encode($param));
        $content = $this->fetch(self::URL.'/'.$url,$post_string,'post');
        if($raw){
            return $content;
        }else{
            return @CJSON::decode($content,true);
        }
    }


    /**
     * @param $url
     * @param array $params array or string
     * @param string $method
     * @param array $header
     * @return mixed
     */
    protected static function fetch($url,$params=array(),$method="get",$header=array()){
        $ch = curl_init();
        if(is_array($params)){
            $query = http_build_query($params);
        }else{
            $query = $params;
        }
        if($method == 'get'){
            if(strpos($url,'?') !== false){
                $url .= "&".$query;
            }else{
                $url .= "?".$query;
            }
        }else{
            curl_setopt($ch,CURLOPT_POST,1);
            curl_setopt($ch,CURLOPT_POSTFIELDS,$query);
        }
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_HTTPHEADER,$header);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,5);
        curl_setopt($ch,CURLOPT_TIMEOUT,5);
        curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,0);
        curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,0);
        $data = curl_exec($ch);
        curl_close($ch);

        return $data;
    }

    /**
     * @desc 解决json转义特殊字符
     * @param array $param
     * @return json
     */
    function json_encode($input)
    {
        if(is_string($input)){
            $text = $input;
            $text = str_replace('\\', '\\\\', $text);
            $text = str_replace(
                array("\r", "\n", "\t", "\""),
                array('\r', '\n', '\t', '\\"'),
                $text);
            return '"' . $text . '"';
        }else if(is_array($input) || is_object($input)){
            $arr = array();
            $is_obj = is_object($input) || (array_keys($input) !== range(0, count($input) - 1));
            foreach($input as $k=>$v){
                if($is_obj){
                    $arr[] = self::json_encode($k) . ':' . self::json_encode($v);
                }else{
                    $arr[] = self::json_encode($v);
                }
            }
            if($is_obj){
                return '{' . join(',', $arr) . '}';
            }else{
                return '[' . join(',', $arr) . ']';
            }
        }else{
            return $input . '';
        }
    }
}