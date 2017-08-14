<?php
namespace php_curlTest;

use php_curl;
use php_curl\curl;
use PHPUnit\Framework\TestCase;

class php_curlTest extends TestCase{

  public function testGet(){
    $url = "https://raw.githubusercontent.com/yakeing/php_Curl/master/tests/php_CurlTest/return";
    $Curl = new curl();
    $Curl->Timeout = 10;
    $Curl->Referer = 'https://github.com/';
    $Curl->Encoding = 'gzip';
    $this->assertTrue($Curl->curlHeader($url));
    $this->assertEquals(200, $Curl->HttpCode);
    $this->assertEquals('Hello world', trim($Curl->Body));
  }

  public function testPost(){}
}
