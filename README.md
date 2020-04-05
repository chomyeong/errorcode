# :rocket::star2:PHP API通用错误码

## Feature

 - 无需手动定义懵逼的错误码数字
 - 定义好错误码初始值即可自动递增
 - 不用在纠结错误码放在哪

## Requirement

1. PHP >= 5.3
2. **[composer](https://getcomposer.org/)**

> 对所使用的框架并无特别要求

## Installation

```shell
composer require "chomyeong/errorcode"
```

## Usage

基本使用:

在vendor同级目录新建errors目录，新增code.php，格式如下：

```php
return array(
    array('errDefine' => 'ERROR_SHOP_XXX', 'index' => 1000, 'errMsg' => '哦也'),
    // 错误码 
    array('errDefine' => 'ERROR_SHOP_XXX', 'index' => 2000, 'errMsg' => '哦也'),
);
```

调用：

```php
use Chomyeong\Error;
$err = new Error();
$err->responseError(ERROR_PARAM); // 相应结果 参数错误
// {"err":1,"errMsg":"\u53c2\u6570\u9519\u8bef\uff01","data":{},"isError":true}
```

## License

MIT
