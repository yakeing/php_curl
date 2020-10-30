<?php
/*
  * Curl Class
  *
  * @author http://weibo.com/yakeing
  * @version 2.5
  * Prompt: CertUrl The server may not contain root / chain SSL certificates, causing authentication errors
  * 注意: 服务器证书可能不包含根/链SSL证书,导致身份验证错误
  * Prompt: When you open the {HeadOut} option, GET/POST returns only false
  * 注意:当您打开{HeadOut}选项时,GET / POST仅返回false
  *
  * Upload Files
  * $post = array('upload' =>curl_file_create(''img.jpg','image/jpeg','pic.jpg'));
  * $post = array('upload'=>new CURLFile(realpath('img.jpg'))); php 5.5 Edition
  *
  * from-data == array()
  * x-www-form-urlencoded == key=value
  * HTTP/1.1 100 Continue = CURLOPT_HTTPHEADER = array( 'Expect:' )
  */
namespace php_curl;
class curl{
    public $Headers = array();
    public $Body = '';
    public $StatusCode = 0;
    public $UrlPut = ''; //最后一个有效发送地址
    public $HeadersPut = ''; //发送的头部 put Headers
    public $Cookie = array(); //返回 Cookie
    public $Error = ''; //返回 Error

    private $HeadOut = false; //只输出头部(需要打开回调函数)
    //Constructor
    public function __construct(){
        if(function_exists('curl')){
            throw new Exception('Curl not defined');
        }
    } //END __construct

    //Initialization Param
    private function Init($Par){
        if(isset($Par['HeadOut']) && $Par['HeadOut'] === true){
            $Par['HeaderCallback'] = true;
            $this->HeadOut = true;
        }else{
            $this->HeadOut = false;
        }
        $Param = array(
            'CurlOpt' => array(), //自定义 Curl 选项
            'CookieSet' => array(), //设置 Cookie
            'Timeout' => 5, //运行时间S秒
            'Referer' => '', //伪装来源
            'Certificate' => '', //Base64编码pem格式的CA证书
            'HeadersOn' => false, //输出头部信息
            'HeaderCallback' => false, //回调函数(返回头部)
            'LocationQuantity' => 0, //自动重定向次数(HTTP 301/302)
            'Encoding' => 'deflate, gzip', //压缩 1.identity、2.deflate, gzip
            'UserAgent' => 'Mozilla/5.0 (compatible; Googlebot/2.1; +http://www.google.com/bot.html )'
        );
        foreach($Param as $key => $value){
            if(isset($Par[$key])){
                $Param[$key] = $Par[$key];
            }
        }
        return $Param;
    } //NDE Init

    //Get Network Resources
    public function GET($Url, $Par = array(), $Header = array()){
        return $this->Method($Url, NULL, $Par, $Header, 'GET');
    } //NDE GET

    //Get HEAD
    public function HEAD($Url, $Par = array(), $Header = array()){
        $Par['HeadOut'] = true;
        $ret = $this->Method($Url, NULL, $Par, $Header, 'GET');
        return ($this->StatusCode == 200);
    } //NDE HEAD

    //Delete
    public function DELETE($Url, $Par = array(), $Header = array()){
        return $this->Method($Url, NULL, $Par, $Header, 'DELETE');
    } //NDE DELETE

    //Send Data Packet
    public function POST($Url, $Vars, $Par = array(), $Header = array()){
        return $this->Method($Url, $Vars, $Par, $Header, 'POST');
    } //END POST

    //Send Data Packet (idempotent)
    public function PUT($Url, $Vars, $Par = array(), $Header = array()){
        return $this->Method($Url, $Vars, $Par, $Header, 'PUT');
    } //END PUT

    //Send Data Packet
    public function Method($Url, $Vars, $Par, $Header, $Method){
        $Param = $this->Init($Par);
        $Options = $this->Options($Url, $Param, $Header);
        if($Method != 'GET'){
            if($Method == 'POST'){
                $Options[CURLOPT_POST] = true;
            }else{
                $Options[CURLOPT_CUSTOMREQUEST] = strtoupper($Method);
            }
            if(isset($Vars)){
                $Options[CURLOPT_POSTFIELDS] = $Vars;
            }
        }
        //$Options[CURLOPT_HTTPGET] = true; //default setting GET
        return $this->Http($Options);
    } //END Method

    //Curl Options
    private function Options($Url, $Param, $Header){
        $Options = array(
            CURLOPT_URL => $Url, //Destination address(URL)
            CURLOPT_USERAGENT => $Param['UserAgent'], //Customer UA mark
            CURLOPT_TIMEOUT => $Param['Timeout'], //Running time (sec)
            CURLOPT_CONNECTTIMEOUT => $Param['Timeout'], //Connection time (sec)
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1, //http 1.1 Edition
            CURLOPT_RETURNTRANSFER => true, //Return file stream
            //CURLOPT_COOKIESESSION => true, //session cookie
            CURLINFO_HEADER_OUT => true //Request for trace handle
        );
        //Closing Body will not be able to receive cookie information
        //$Options[CURLOPT_NOBODY] = true;
        if(!empty($Header)){//Set header information
            $Options[CURLOPT_HTTPHEADER] = $Header;
        }
        if(strrpos($Url, 'https://') === 0){//https mode
            if(empty($Param['Certificate'])){
                $Options[CURLOPT_SSL_VERIFYPEER] = false;//https peer’s certificate OFF
                $Options[CURLOPT_SSL_VERIFYHOST] = false;//No inspection certificate
            }else{
                $Options[CURLOPT_SSL_VERIFYPEER] = true;//Validation of peer’s certificate
                $Options[CURLOPT_SSL_VERIFYHOST] = 2;//Does the Certificate Common Name match the hostname (Strict certification)
                $Options[CURLOPT_CAINFO] = $Param['Certificate'];//Certificate address
            }
        }
        if(is_int($Param['LocationQuantity']) && $Param['LocationQuantity'] > 0){//redirection (HTTP 301/302)
            $Options[CURLOPT_FOLLOWLOCATION] = true;//Open redirection
            $Options[CURLOPT_AUTOREFERER] = true;//Redirection automatically sets Referer information
            $Options[CURLOPT_MAXREDIRS] = $Param['LocationQuantity']; //Redirection times
        }
        if(!empty($Param['CookieSet'])){//Cookie information
            if(is_array($Param['CookieSet'])){
                $cookie = '';
                foreach ($Param['CookieSet'] as $key => $value) {
                    $cookie .= $key.'='.$value.';';
                }
            }else{ //is_string($Param['CookieSet'])
                $cookie = $Param['CookieSet'];
            }
            $Options[CURLOPT_COOKIE] = $cookie;
        }
        if(!empty($Param['Referer'])){//Camouflage source
            $Options[CURLOPT_REFERER] = $Param['Referer'];
        }
        if(!empty($Param['Encoding'])){//compress
            $Options[CURLOPT_ENCODING] = $Param['Encoding'];
        }
        if($Param['HeadersOn'] === true){//Back header
            $Options[CURLOPT_HEADER] = true;
        }
        if($Param['HeaderCallback'] === true){//callback (Get header)
            $Options[CURLOPT_HEADERFUNCTION] = array($this, 'CurlHeaderCallback');
        }
        if(count($Param['CurlOpt'])>0){//Add or change Curl Options
            $Options = array_replace($Options, $Param['CurlOpt']);
        }
        return $Options;
    } //END Options

    // Curl Header Callback
    private function CurlHeaderCallback($ch, $header){
        $i = strpos($header, ':');
        if(!empty($i)){
            $key = str_replace('-', '_', strtolower(substr($header, 0, $i)));
            $value = trim(substr($header, $i + 2));
            if($key == 'set_cookie'){
                list($k, $v) = explode('=', strstr($value, ';', true), 2);
                $this->Cookie[$k] =  $v;
            }else{
                $this->Headers[$key] = $value;
            }
        }else if(!empty(trim($header))){
            $this->Headers['HTTP'][] = $header;
        }else if($this->HeadOut){
            return 0;
        }
        return strlen($header);
    } //END CurlHeaderCallback

    //initialize variable Packet
    private function InitializeVariable(){
        $this->StatusCode = 0;
        $this->Headers = $this->Cookie = array();
        $this->Body  = $this->UrlPut = $this->HeadersPut = $this->Error = '';
    } //END InitializeVariable

    //Establish Communication Connection
    private function Http($options){
        $this->InitializeVariable();
        $ch = curl_init();
        curl_setopt_array($ch, $options);
        $ResUlt = curl_exec($ch);
        //Gets the size of the header
        $Header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        //Gets the last valid URL
        $this->UrlPut = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
        //Send header request
        $this->HeadersPut = curl_getinfo($ch, CURLINFO_HEADER_OUT );
        //Get the last valid code
        $this->StatusCode = (int)curl_getinfo($ch, CURLINFO_HTTP_CODE);
        //Get Content-Type value
        //$ContentType = curl_getinfo($ch, CURLINFO_CONTENT_TYPE );
        if($ResUlt === false || curl_errno($ch) !== 0){
            $this->Error = curl_error($ch);
            curl_close($ch);
            return false;
        }else{
            $this->Body = $ResUlt;
            curl_close($ch);
            return true;
        }
    } //END Http
}
