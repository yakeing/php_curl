<?php
namespace php_curlTest;
use php_curl;
use php_curl\curl;
use PHPUnit\Framework\TestCase;
class php_curlTest extends TestCase{
  public function testGet(){
    $url = "https://raw.githubusercontent.com/yakeing/php_curl/master/tests/return";
    $curl = new curl();
    $Par = array(
      'Timeout' => 10,
      'CookieSet' => '_gat=1;',
      'Referer' => 'https://github.com/',
      'Encoding' => 'gzip',
      'UserAgent' => 'Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/57.0.2987.133 Safari/537.36'
    );
    $this->assertTrue($curl->GET($url, $Par));
    $this->assertEquals(200, $curl->StatusCode);
    $this->assertEquals('Hello world', trim($curl->Body));
    return array($curl, $Par);
  }

  /**
  * @depends testGet
  */
  public function testHeaderCallback(array $args){
    list($curl, $Par) = $args;
    $url = "https://raw.githubusercontent.com/yakeing/php_curl/master/tests/return";
    $this->assertTrue($curl->HEAD($url, $Par));
    return array($curl, $Par);
  }
  /**
  * @depends testHeaderCallback
  */
  public function testPost(array $args){
    list($curl, $Par) = $args;
    $url = "https://api.github.com/_private/browser/errors";
    $Header = array('Expect:', 'Content-Type: application/json; charset=utf-8');
    $Vars = '{"bucket":"github-js-reports","error":{"columnNumber":null,"fileName":null,"lineNumber":null,"message":"ReportingObserverError","stack":"Error: ReportingObserverError"},"url":"https://github.com/about"}';
    $Par['CurlOpt'] = array(CURLOPT_FRESH_CONNECT => true);
    $Par['CookieSet'] = array('_gat'=>1);
    $Par['LocationQuantity'] = 2; //(HTTP 301/302)
    $Par['Certificate'] = dirname(__FILE__).'/Cert.pem';
    $this->assertTrue($curl->POST($url, $Vars, $Par, $Header));
    $this->assertEquals(200, $curl->StatusCode);
  }
}
