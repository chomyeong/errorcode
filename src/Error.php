<?php

/*
 * This file is part of the chomyeong/errorcode.
 *
 * (c) chomyeong <chomyeong.me>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

/**
 * Error.php.
 *
 * @author    chomyeong <mme7wan@gmail.com>
 * @copyright 2020 chomyeong <mme7wan@gmail.com>
 *
 * @see      https://github.com/chomyeong
 * @see      http://chomyeong.me
 */

namespace chomyeong\errorcode;

class Error
{

    private $errCode = 0;
    private $errMsg = "";
    private $retData = array();
    private $err2Msg = [];

    static $errors = array(
        array('errDefine' => 'ERROR_OK', 'index' => 0, 'errMsg' => ''),

        // 通用错误
        array('errDefine' => 'ERROR_PARAM', 'index' => 1, 'errMsg' => '参数错误！'),
        array('errDefine' => 'ERROR_SYSTEM', 'errMsg' => '系统错误！'),
        array('errDefine' => 'ERROR_SESSION_ERROR', 'errMsg' => '会话不存在！'),
        array('errDefine' => 'ERROR_SESSION_PRIVILEGE_ERROR', 'errMsg' => '权限不正确！'),
        array('errDefine' => 'ERROR_MODIFY_INFO_FAILED', 'errMsg' => '修改数据失败！'),

        // 账号相关错误
        array('errDefine' => 'ERROR_ACCOUNT_LOGIN_FAILED', 'index' => 101, 'errMsg' => '账号名或密码错误！'),
        array('errDefine' => 'ERROR_ACCOUNT_RELOGIN_FAILED_WRONG_TOKEN', 'errMsg' => '重登录失败：令牌错误!'),
        array('errDefine' => 'ERROR_ACCOUNT_RELOGIN_FAILED_TOKEN_TIMEOUT', 'errMsg' => '重登录失败：令牌已超时!'),
        array('errDefine' => 'ERROR_ACCOUNT_PASSPORT_EXISTS', 'errMsg' => '该账号已存在！'),
        array('errDefine' => 'ERROR_ACCOUNT_NOT_EXIST_PASSPORT', 'errMsg' => '账号不存在！'),
        array('errDefine' => 'ERROR_ACCOUNT_RELOGIN_FAILED', 'errMsg' => '设置重登录信息出错!'),
        array('errDefine' => 'ERROR_ACCOUNT_WRONG_PLATFORM', 'errMsg' => '未知的用户平台!'),
        array('errDefine' => 'ERROR_ACCOUNT_NO_INVITE_CODE', 'errMsg' => '您不能填写自己的邀请码!'),
        array('errDefine' => 'ERROR_ACCOUNT_NO_INVITE', 'errMsg' => '您填写的邀请码不存在!'),
        array('errDefine' => 'ERROR_ACCOUNT_PERMANENT_TITLE', 'errMsg' => '此账号已被永久封号！'),
        array('errDefine' => 'ERROR_ACCOUNT_UPDATE_PASSWORD_FAIL', 'errMsg' => '修改密码失败,请稍后再试！'),
        array('errDefine' => 'ERROR_ACCOUNT_FORBIDDEN', 'errMsg' => '账号已被封禁，如有疑虑请您联系客服!'),
        array('errDefine' => 'ERROR_ACCOUNT_ADMIN_EXISTS', 'errMsg' => '管理员已存在!'),
        array('errDefine' => 'ERROR_ACCOUNT_USER_EXISTS', 'errMsg' => '用户已存在!'),
        array('errDefine' => 'ERROR_ACCOUNT_ADMIN_CANT_DELETE_SELF', 'errMsg' => '不可以删除自己!'),
    );

    public function __construct()
    {
        $this->init();
        $errors = self::$errors;
        $nowIndex = -1;
        $definedErr = [];

        foreach ($errors as $key => $val) {
            /* 检查是否没有错误定义或错误消息 */
            if (!isset($val['errDefine']) || !isset($val['errMsg'])) {
                throw new \Exception('errDefine not found!:'.print_r($val, true));
                break;
            }

            /* 检查错误码是否有错 */
            if (isset($val['index'])) {
                if ($val['index'] <= $nowIndex) {
                    throw new \Exception("Index is not in order or same index set: ".$val['errDefine']);
                    break;
                }
            }

            /* 检查错误定义是否重复 */
            if (in_array($val['errDefine'], $definedErr)) {
                throw new \Exception("Redefinition of ".$val['errDefine']);
                break;
            }

            if (isset($val['index'])) {
                $nowIndex = $val['index'];
            } else {
                $nowIndex++;
            }

            define($val['errDefine'], $nowIndex);
            $this->err2Msg[$nowIndex] = $val['errMsg'];
        }

    }

    /**
     * 设置错误码，在其中会同时设置好错误信息
     * @param $errCode  错误码
     */
    function setErr($errCode)
    {
        if ($this->errCode != ERROR_OK) {
            // $this->log->write_log('notice', 'Error code is set, are you sure of setting error code again???');
        }

        $this->errCode = $errCode;
        $this->errMsg = $this->getErrMsginfo($errCode);
    }

    /**
     * 获取当前的错误码
     * @return int  当前错误码
     */
    public function getErrCode()
    {
        return $this->errCode;
    }

    /**
     * 获取当前的错误描述
     * @return string   当前错误描述
     */
    public function getErrMsg()
    {
        return $this->errMsg;
    }

    /**
     * 加载默认的View，其中只是返回json串
     */
    public function loadDefaultView($isError = false)
    {

        $data = array
        (
            'err'     => $this->errCode,
            'errMsg'  => $this->errMsg,
            'data'    => (object) $this->retData,
            'isError' => $isError,
        );

        echo json_encode($data);
    }

    /**
     * 简化的回复方法（失败）
     * @param  unknown  $errCode
     */
    public function responseError($errCode)
    {
        $this->setErr($errCode);
        $this->loadDefaultView(true);
    }

    /**
     * 简化的回复方法（成功）
     * @param  array  $data
     */
    public function responseSuccess($data = null)
    {
        if ($data == null) {
            $data = (object) array();
        }
        $this->setRetData($data);
        $this->loadDefaultView();
    }


    /**
     * 获取所有的错误信息数组
     * @return array
     */
    static function getAllErrors()
    {
        return self::$errors;
    }

    /**
     * 注册错误码
     * @param $errors
     */
    static function registerErrors($errors)
    {
        self::$errors = array_merge(self::$errors, $errors);
    }


    /**
     * 初始化 查找用户自定义错误文件
     */
    public function init()
    {
        require_once 'Exceptions/Exception.php';
        $rootDir = str_replace('\\', '/', realpath(dirname(dirname(__DIR__)).'/'))."/";
        $target = $rootDir.'/'.'errors';
        if (file_exists($target)) {

            if (file_exists($target.'/'.'code.php')) {
                $arr = require_once $target.'/'.'code.php';
                self::registerErrors($arr);
            } else {
                // 抛出异常
                throw new \Exception("code.php not exist!");
            }

        } else {
            // 抛出异常
            throw new \Exception("code.php not exist!");
        }

        return;
    }

    /**
     * 根据错误码返回错误描述文本
     * @param $errCode  错误码
     * @return string   错误描述
     */
    public function getErrMsginfo($errCode)
    {
        if (array_key_exists($errCode, $this->err2Msg)) {
            return $this->err2Msg[$errCode];
        }

        return "";
    }
}