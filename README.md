# Curl

Curl is an open source file transfer tool that uses URL syntax to work on the command line, where the basic functions of curl are encapsulated, such as COOKIES / encrypted transport / HTTP authentication / analog forms / upload files, etc.

### Travis CI badge

[![Travis-ci](https://api.travis-ci.com/yakeing/php_curl.svg?branch=master)](https://travis-ci.com/yakeing/php_curl)

### codecov badge

[![codecov](https://codecov.io/gh/yakeing/php_curl/branch/master/graph/badge.svg)](https://codecov.io/gh/yakeing/php_curl)

### Github badge

[![Downloads](https://badging.now.sh/github/downloads/yakeing/php_curl?icon=github)](../../)
[![Size](https://badging.now.sh/github/size/yakeing/php_curl?icon=github)](src)
[![tag](https://badging.now.sh/github/tag/yakeing/php_curl?icon=github)](../../releases)
[![license](https://badging.now.sh/static/label/license/555/MPL-2.0/fe7d37?icon=github)](LICENSE)
[![languages](https://badging.now.sh/static/label/language/555/PHP/34abef?icon=github)](../../search?l=php)

### Installation

Use [Composer](https://getcomposer.org) to install the library.
Of course, You can go to [Packagist](https://packagist.org/packages/yakeing/php_curl) to view.

```

    $ composer require yakeing/php_curl

```

### init

- [x] example
```php
    $Curl = new curl();
    $curl->Encoding = 'gzip';
    $Curl->Timeout = 10;
```

### GET

- [x] example
```php
      $Url = 'https://github.com/yakeing';
      $Header = array(
        'User-Agent: Mozilla/5.0 (iPad; CPU OS 11_0 like Mac OS X) AppleWebKit/604.1.28'
      );
      $Curl->Get($Url, $Header);
```

### POST

- [x] example
```php
      $Url = 'https://github.com/yakeing';
      $Vars = array(
        'user'=>'admin',
        'upload'=>new CURLFile(realpath('image.jpg')) //php 5.5 Edition
        );
      $curl->UserAgent = 'Mozilla/5.0 (iPad; CPU OS 11_0 like Mac OS X) AppleWebKit/604.1.28';
      $Curl->Post($Url, $Vars, $Header);
```

[Sponsor](https://github.com/yakeing/Documentation/blob/master/Sponsor/README.md)
---

If you've got value from any of the content which I have created, then I would very much appreciate your support by payment donate.

[![Sponsor](https://badging.now.sh/static/label/Sponsor/EA4AAA?icon=heart)](https://github.com/yakeing/Documentation/blob/master/Sponsor/README.md)

Author
---

weibo: [yakeing](https://weibo.com/yakeing)

twitter: [yakeing](https://twitter.com/yakeing)
