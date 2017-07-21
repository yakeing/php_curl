# Curl
Curl Class
-----

Author [Yakeing](http://weibo.com/yakeing)

[![image](https://oauth.applinzi.com/SvgLabel/4D4D4D/License/F66000/MPL2.0/image.svg)](https://github.com/yakeing/Curl/blob/master/LICENSE)

[![image](https://oauth.applinzi.com/SvgLabel/4D4D4D/Language/007EC6/PHP/image.svg)](https://github.com/yakeing/Curl)

[![image](https://oauth.applinzi.com/SvgLabel/4D4D4D/Version/97CA00/2.0/image.svg)](https://github.com/yakeing/SaeKV/tree/master/Curl.php)

### init

-----
```php
    $Curl = new Curl();
    $Curl->LocationQuantity = 1;
    $Curl->Timeout = 10;
```

### GET

-----
```php
      $Url = 'https://github.com/yakeing';
      $Header = array(
        'User-Agent: Mozilla/5.0 (iPad; CPU OS 11_0 like Mac OS X) AppleWebKit/604.1.28'
      );
      $Curl->Get($Url, $Header);
```

### POST

-----
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
