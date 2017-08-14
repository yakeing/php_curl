<?php
namespace php_curlTest;

use php_curl;
use php_curl\curl;
use PHPUnit\Framework\TestCase;

class php_curlTest extends TestCase{

  public function testGet(){
    $url = "https://raw.githubusercontent.com/yakeing/php_curl/master/tests/php_curlTest/return";
    $curl = new curl();
    $curl->Timeout = 10;
    $curl->Referer = 'https://github.com/';
    $curl->Encoding = 'gzip';
    $this->assertTrue($curl->curlHeader($url));
    $this->assertEquals(200, $curl->HttpCode);
    $this->assertEquals('Hello world', trim($curl->Body));
  }

  public function testPost(){}
}
