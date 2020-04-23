<?php
/**
 * 默认错误码定义
 */

namespace chomyeong\errorcode;


use Pimple\Container;

class ErrorCode extends Container
{

    static $errors = [
        ['errDefine' => 'ERROR_OK', 'index' => 0, 'errMsg' => ''],

        // 通用错误
        ['errDefine' => 'ERROR_PARAM', 'index' => 1, 'errMsg' => '参数错误！'],
        ['errDefine' => 'ERROR_SYSTEM', 'errMsg' => '系统错误！'],
        ['errDefine' => 'ERROR_SESSION_ERROR', 'errMsg' => '会话不存在！'],
        ['errDefine' => 'ERROR_SESSION_PRIVILEGE_ERROR', 'errMsg' => '权限不正确！'],
        ['errDefine' => 'ERROR_MODIFY_INFO_FAILED', 'errMsg' => '修改数据失败！'],

        // 账号相关错误
        ['errDefine' => 'ERROR_ACCOUNT_LOGIN_FAILED', 'index' => 101, 'errMsg' => '账号名或密码错误！'],
        ['errDefine' => 'ERROR_ACCOUNT_RELOGIN_FAILED_WRONG_TOKEN', 'errMsg' => '重登录失败：令牌错误!'],
        ['errDefine' => 'ERROR_ACCOUNT_RELOGIN_FAILED_TOKEN_TIMEOUT', 'errMsg' => '重登录失败：令牌已超时!'],
        ['errDefine' => 'ERROR_ACCOUNT_PASSPORT_EXISTS', 'errMsg' => '账号已存在！'],
    ];
}