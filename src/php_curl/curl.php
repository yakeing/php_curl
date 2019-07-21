<?php
/*
  * Curl Class
  *
  * @author http://weibo.com/yakeing
  * @version 2.2
  * Prompt: CertUrl The server may not contain root / chain SSL certificates, causing authentication errors
  * 注意: 服务器证书可能不包含根/链SSL证书,导致身份验证错误
  * Prompt: When you open the {HeadOut} option, GET/POST returns only false
  * 注意:当您打开{HeadOut}选项时,GET / POST仅返回false
  *
  * Upload Files
  * $post = array('upload' =>curl_file_create(''img.jpg','image/jpeg','pic.jpg'));
  * $post = array('upload'=>new CURLFile(realpath('img.jpg'))); php 5.5 Edition
  */
namespace php_curl;
class curl{
	public $Headers = array();
	public $Body = '';
	public $HttpCode = 0;
	public $HttpError = '';
	public $HttpUrl = ''; //最后一个有效发送地址
	public $HeadersOut = ''; //发送的头部
	public $HttpCookie = array(); //返回 Cookie

	public $CurlOpt = array(); //自定义 Curl 选项
	public $Cookie = array(); //设置 Cookie
	public $Timeout = 5; //运行时间
	public $Referer = ''; //伪装来源
	public $CertUrl = ''; //HTTPS地址SSL证书路径
	public $HeadersOn = false; //输出头部信息
	public $HeaderFunction = false; //回调函数(返回头部)
	public $HeadOut = false; //回调函数(只输出头部)
	public $LocationQuantity = 0; //自动重定向次数(HTTP 301/302)
	public $Encoding = 'deflate, gzip'; //压缩 1.identity、2.deflate, gzip
	public $UserAgent = 'Mozilla/5.0 (compatible; Googlebot/2.1; +http://www.google.com/bot.html )';

	//Constructor
	public function __construct(){
		if(function_exists('curl')){
			throw new Exception('Curl not defined');
		}
	} //END __construct

	//Get Network Resources
	public function Get($Url, $Header = array()){
		$Options = $this->Options($Url, $Header);
		//$Options[CURLOPT_HTTPGET] = true; //default setting
		return $this->Http($Options);
	} //NDE Get

	//Send Data Packet
	public function Post($Url, $Vars, $Header = array()){
		$Options = $this->Options($Url, $Header);
		$Options[CURLOPT_POST] = true;
		$Options[CURLOPT_POSTFIELDS] = $Vars;
		return $this->Http($Options);
	} //END Post

	//Curl Options
	private function Options($Url, $Header){
		$Options = array(
			CURLOPT_URL => $Url, //Destination address(URL)
			CURLOPT_USERAGENT => $this->UserAgent, //Customer UA mark
			CURLOPT_TIMEOUT => $this->Timeout, //Running time (sec)
			CURLOPT_CONNECTTIMEOUT => $this->Timeout, //Connection time (sec)
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
			if(empty($this->CertUrl)){
				$Options[CURLOPT_SSL_VERIFYPEER] = false;//https peer’s certificate OFF
				$Options[CURLOPT_SSL_VERIFYHOST] = false;//No inspection certificate
			}else{
				$Options[CURLOPT_SSL_VERIFYPEER] = true;//Validation of peer’s certificate
				$Options[CURLOPT_SSL_VERIFYHOST] = 2;//Does the Certificate Common Name match the hostname (Strict certification)
				$Options[CURLOPT_CAINFO] = $this->CertUrl;//Certificate address
			}
		}
		if(is_int($this->LocationQuantity) && $this->LocationQuantity > 0){//redirection (HTTP 301/302)
			$Options[CURLOPT_FOLLOWLOCATION] = true;//Open redirection
			$Options[CURLOPT_AUTOREFERER] = true;//Redirection automatically sets Referer information
			$Options[CURLOPT_MAXREDIRS] = $this->LocationQuantity; //Redirection times
		}
		if(!empty($this->Cookie)){//Cookie information
			if(is_array($this->Cookie)){
				$cookie = '';
				foreach ($this->Cookie as $key => $value) {
					$cookie .= $key.'='.$value.';';
				}
			}else{ //is_string($this->Cookie)
				$cookie = $this->Cookie;
			}
			$Options[CURLOPT_COOKIE] = $cookie;
		}
		if(!empty($this->Referer)){//Camouflage source
			$Options[CURLOPT_REFERER] = $this->Referer;
		}
		if(!empty($this->Encoding)){//compress
			$Options[CURLOPT_ENCODING] = $this->Encoding;
		}
		if($this->HeadersOn === true){//Back header
			$Options[CURLOPT_HEADER] = true;
		}
		if($this->HeaderFunction === true){//callback (Get header)
			$Options[CURLOPT_HEADERFUNCTION] = array($this, 'HeaderFunction');
		}
		if(count($this->CurlOpt)>0){//Add or change Curl Options
			$Options = array_merge($Options, $this->CurlOpt);
		}
		return $Options;
	} //END Options

	// Header Function
	private function HeaderFunction($thising, $header){
		$i = strpos($header, ':');
		if(!empty($i)){
			$key = str_replace('-', '_', strtolower(substr($header, 0, $i)));
			$value = trim(substr($header, $i + 2));
			if($key == 'set_cookie'){
				list($k, $v) = explode('=', strstr($value, ';', true), 2);
				$this->HttpCookie[$k] =  $v;
			}else{
				$this->Headers[$key] = $value;
			}
		}else if(!empty(trim($header))){
			$this->Headers['HTTP'][] = $header;
		}else if($this->HeadOut){
			return 0;
		}
		return strlen($header);
	} //END HeaderFunction

	//Establish Communication Connection
	private function Http($options){
		$ch = curl_init();
		curl_setopt_array($ch, $options);
		$ResUlt = curl_exec($ch);
		//Gets the size of the header
		$Header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
		//Gets the last valid URL
		$this->HttpUrl = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
		//Send header request
		$this->HeadersOut = curl_getinfo($ch, CURLINFO_HEADER_OUT );
		//Get the last valid code
		$this->HttpCode = (int)curl_getinfo($ch, CURLINFO_HTTP_CODE);
		//Get Content-Type value
		//$ContentType = curl_getinfo($ch, CURLINFO_CONTENT_TYPE );
		if($ResUlt === false || curl_errno($ch) !== 0){
			$this->error = curl_error($ch);
			curl_close($ch);
			return false;
		}else{
			$this->Body = $ResUlt;
			curl_close($ch);
			return true;
		}
	} //END Http

}
