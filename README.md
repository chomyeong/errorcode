# :rocket:PHP API通用错误码

## errorcode

在写接口API返回错误码时，我们以前可能这样做：

```php
$ret = [
    'code'=>100,
    'msg'=>'message',
    'data'=>[],
];

echo json_encode($ret);
```

写着写着，你可能经常忘记了错误码从哪里开始了...  🤦🏼‍♂️

**用了这个扩展后，我们可以这样：**

```php
return [
    ['errDefine' => 'ERROR_SHOP_NAME', 'index' => 1000, 'errMsg' => '商城名称错误'],
    ['errDefine' => 'ERROR_SHOP_NAME1','errMsg' => '商城名称错误1'],
    ['errDefine' => 'ERROR_ORDER_ERROR', 'index' => 2000, 'errMsg' => '商城登录错误'],
];
```

比如错误码1000到1999是商城错误，定义好1000,第二个错误无需定义错误码，即可自增 👏

## ✨Feature

 -  无需手动定义懵逼的错误码数字
 - 定义好错误码初始值即可自动递增
 - 不用在纠结错误码放在哪

## 🖥Requirement

1. PHP >= 5.4
2. **[composer](https://getcomposer.org/)**

## 📦Installation

```shell
composer require "chomyeong/errorcode" "v1.0.0"
```

## 🔨Usage

基本使用:

在vendor同级目录新建errors目录，新增code.php，格式如下：

```php
return [
    ['errDefine' => 'ERROR_SHOP_NAME', 'index' => 1000, 'errMsg' => '商城名称错误'],
    ['errDefine' => 'ERROR_SHOP_LOGIN', 'index' => 2000, 'errMsg' => '商城登录错误'],
];
```

引入扩展包：

```php
use chomyeong\errorcode\Error
$err = new Error();
```

响应错误：

```php
$err->responseError(ERROR_PARAM);
```

返回格式：

![image-20200406130217004](/Users/zhaoming/Library/Application Support/typora-user-images/image-20200406130217004.png)

响应成功：

```php
// 要返回的数据
$data = [
    'info' => [
        'id'   => 1,
        'username' => 'chomyeong',
    ],
];
$err->responseSuccess($data);
```

返回格式：

![image-20200406125922294](/Users/zhaoming/Library/Application Support/typora-user-images/image-20200406125922294.png)

默认错误码：

```
ERROR_PARAM 参数错误
ERROR_SYSTEM 系统错误
ERROR_SESSION_ERROR 会话不存在
ERROR_SESSION_PRIVILEGE_ERROR 权限不正确
ERROR_MODIFY_INFO_FAILED 修改数据失败
ERROR_ACCOUNT_LOGIN_FAILED 账号名或密码错误
ERROR_ACCOUNT_RELOGIN_FAILED_WRONG_TOKEN 重登录失败：令牌错误
ERROR_ACCOUNT_RELOGIN_FAILED_TOKEN_TIMEOUT 重登录失败：令牌已超时
ERROR_ACCOUNT_PASSPORT_EXISTS 账号已存在
```

## License

MIT
