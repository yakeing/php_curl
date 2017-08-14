# Curl
php Curl Class
-----

#### Travis CI

[![Travis-ci](https://api.travis-ci.org/yakeing/php_Curl.svg)](https://travis-ci.org/yakeing/php_Curl)

#### Packagist

[![Version](http://img.shields.io/packagist/v/yakeing/php_Curl.svg)](https://packagist.org/packages/yakeing/php_Curl)
[![Downloads](http://img.shields.io/packagist/dt/yakeing/php_Curl.svg)](https://packagist.org/packages/yakeing/php_Curl)

#### Github

[![Downloads](https://img.shields.io/github/downloads/yakeing/php_Curl/total.svg)](https://github.com/yakeing/php_Curl)
[![Size](https://img.shields.io/github/size/yakeing/php_Curl/src/php_Curl/Curl.php.svg)](https://github.com/yakeing/php_Curl)
[![tag](https://img.shields.io/github/tag/yakeing/php_Curl.svg)](https://github.com/yakeing/php_Curl)
[![Language](https://oauth.applinzi.com/SvgLabel/4D4D4D/Language/F66000/PHP/image.svg)](https://github.com/yakeing/php_Curl)
[![License](https://oauth.applinzi.com/SvgLabel/4D4D4D/License/007EC6/MPL-2.0/image.svg)](https://github.com/yakeing/php_Curl)

BY: [yakeing](http://weibo.com/yakeing)

### Installation

Use [Composer](https://getcomposer.org) to install the library.

```

    $ composer require yakeing/php_Curl

```

### init

-----
- [x] example
```php
    $Curl = new Curl();
    $Curl->LocationQuantity = 1;
    $Curl->Timeout = 10;
```

### GET

-----
- [x] example
```php
      $Url = 'https://github.com/yakeing';
      $Header = array(
        'User-Agent: Mozilla/5.0 (iPad; CPU OS 11_0 like Mac OS X) AppleWebKit/604.1.28'
      );
      $Curl->Get($Url, $Header);
```

### POST

-----
- [x] example
```php
      $Url = 'https://github.com/yakeing';
      $Vars = array(
        'u'=>'admin',
        'upload'=>new CURLFile(realpath('image.jpg')) //php 5.5 Edition
        );
      $Header = array(
        'User-Agent: Mozilla/5.0 (iPad; CPU OS 11_0 like Mac OS X) AppleWebKit/604.1.28'
      );
      $Curl->Post($Url, $Vars, $Header);
```

Donate
---
Your donation makes CODE better.

 Bitcoin (比特币赞助)

 1Ff2hTfr4EioWv2ZDLKTedUiF9wBBVYSbU

 ![Bitcoin](https://oauth.applinzi.com/QR/230/bitcoin%3a1Ff2hTfr4EioWv2ZDLKTedUiF9wBBVYSbU/Bitcoin.png)

 WeChat (微信赞助)

 ![WeChat](https://oauth.applinzi.com/QR/230/wxp%3a%7C%7Cf2f0SOGAUjQ1ALzigoyN7nW8tK68D2oeU3YO/WeChat.png)

 Alipay (支付宝赞助)

 ![Alipay](https://oauth.applinzi.com/QR/230/HTTPS%3a%7C%7CQR.ALIPAY.COM%7CTSX082709YGHVXYUQCWKD6/Alipay.png)
