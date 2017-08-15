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
    $curl->UserAgent = 'Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/57.0.2987.133 Safari/537.36';
    $this->assertTrue($curl->Get($url));
    $this->assertEquals(200, $curl->HttpCode);
    $this->assertEquals('Hello world', trim($curl->Body));
  }

  public function testPost(){}
}
