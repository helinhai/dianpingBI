<?php
include_once('./utils.class.php');
include_once('./Business.class.php');


//需要PHP 5 以上以及安装curl扩展

//AppKey信息，在大众点评开发者中心可注册
define('APPKEY', 9588705163);
define('SECRET','64dfc43f8f0e4cf1a867a921e3305778');


//API 请求地址
define('BUSINESS_REQUEST_URL','http://api.dianping.com/v1/business/get_single_business');
//获取指定商户信息
define('CITYLIST_REQUEST_URL','http://api.dianping.com/v1/metadata/get_cities_with_businesses');
//获取支持商户搜索的最新城市列表
define('COMMENTS_REQUEST_URL', 'http://api.dianping.com/v1/review/get_recent_reviews');


abstract class DianpingAPI {
    protected $_APPKEY;
    protected $_SECRET;
    
    public function __construct ( $appkey = APPKEY, $secret = SECRET ) {
        $this->_APPKEY = $appkey;
        $this->_SECRET = $secret;
    }
    
    abstract protected function create_request_url();

    public function request () {
        $url = $this->create_request_url();
        
        $curl = curl_init();

        // 设置你要访问的URL
        curl_setopt($curl, CURLOPT_URL, $url);

        // 设置cURL 参数，要求结果保存到字符串中还是输出到屏幕上。
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

        curl_setopt($curl, CURLOPT_ENCODING, 'UTF-8');

        // 运行cURL，请求API
        $data = json_decode(curl_exec($curl), true);
        //print_r($data);

        // 关闭URL请求
        curl_close($curl);
        
        trace("API return:");
        print_r($data);
        return $data;
    }
}


class DianpingAPI_Business extends DianpingAPI {
    protected $_BUSINESS_ID;
    
    public function __construct () {
        parent::__construct();
    }
    
    public function set_BusinessID ( $id ) {
        $this->_BUSINESS_ID = $id;
        trace("Set Business ID as ".$this->_BUSINESS_ID);
    }
 
    /**
     *产生API请求链接
     *@return String
     */
    protected function create_request_url () {
        //请求参数
        $params = array('business_id'=>$this->_BUSINESS_ID);
        //按照参数名排序
        ksort($params);
        trace("Your request based on:");
        trace($params);
        //连接待加密的字符串
        $codes = APPKEY;
        //请求的URL参数
        $queryString = '';
        while (list($key, $val) = each($params))
        {
            $codes .=($key.$val);
            $queryString .=('&'.$key.'='.urlencode($val));
        }
        $codes .=SECRET;
        $sign = strtoupper(sha1($codes));
        $url= BUSINESS_REQUEST_URL . '?appkey='.APPKEY.'&sign='.$sign.$queryString;
        
        trace("Genereate request url: $url");
        return $url;
    }
}


class DianpingAPI_City extends DianpingAPI {
    
    public function __construct () {
        parent::__construct();
    }
    
    
    protected function create_request_url () {
        //请求参数
        //$params = array('city'=>$this->_CITY);
        $params = array();
        //按照参数名排序
        ksort($params);
        trace("Your request based on:");
        trace($params);
        //连接待加密的字符串
        $codes = APPKEY;
        //请求的URL参数
        $queryString = '';
        while (list($key, $val) = each($params))
        {
            $codes .=($key.$val);
            $queryString .=('&'.$key.'='.urlencode($val));
        }
        $codes .=SECRET;
        $sign = strtoupper(sha1($codes));
        $url= CITYLIST_REQUEST_URL . '?appkey='.APPKEY.'&sign='.$sign.$queryString;
        
        trace("Genereate request url: $url");
        return $url;
    }
}
class DianpingAPI_Comments extends DianpingAPI {
    protected $_BUSINESS_ID;
    
    public function __construct () {
        parent::__construct();
    }
    
    public function set_BusinessID ( $id ) {
        $this->_BUSINESS_ID = $id;
        trace("Set Business ID as ".$this->_BUSINESS_ID);
    }
 
    /**
     *产生API请求链接
     *@return String
     */
    protected function create_request_url () {
        //请求参数
        $params = array('business_id'=>$this->_BUSINESS_ID);
        //按照参数名排序
        ksort($params);
        trace("Your request based on:");
        trace($params);
        //连接待加密的字符串
        $codes = APPKEY;
        //请求的URL参数
        $queryString = '';
        while (list($key, $val) = each($params))
        {
            $codes .=($key.$val);
            $queryString .=('&'.$key.'='.urlencode($val));
        }
        $codes .=SECRET;
        $sign = strtoupper(sha1($codes));
        $url= COMMENTS_REQUEST_URL . '?appkey='.APPKEY.'&sign='.$sign.$queryString;
        
        trace("Genereate request url: $url");
        return $url;
    }
}

?>
