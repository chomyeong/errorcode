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
 * 通用API 错误码
 * @author    chomyeong <mme7wan@gmail.com>
 * @copyright 2020 chomyeong <mme7wan@gmail.com>
 *
 * @see      https://github.com/chomyeong
 * @see      http://chomyeong.me
 */

namespace chomyeong\errorcode;

use Pimple\Container;

class Error extends Container
{

    private $errCode = 0;
    private $errMsg = "";
    private $retData = [];
    private $err2Msg = [];


    public function __construct(array $options = [])
    {
        $this['config'] = function () use ($options) {
            return new Config($options);
        };

        $this->init();
        $errors = ErrorCode::$errors;
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
    private function setErr($errCode)
    {
        if ($this->errCode != ERROR_OK) {
            // Error code is set, are you sure of setting error code again???
        }

        $this->errCode = $errCode;
        $this->errMsg = $this->getErrMsginfo($errCode);
    }

    /**
     * 设置返回消息体
     * @param $retData 消息体
     */
    private function setRetData($retData)
    {
        $this->retData = $retData;
    }

    /**
     * 获取当前的错误码
     * @return int  当前错误码
     */
    private function getErrCode()
    {
        return $this->errCode;
    }

    /**
     * 获取当前的错误描述
     * @return string   当前错误描述
     */
    private function getErrMsg()
    {
        return $this->errMsg;
    }

    /**
     * 加载默认的View，其中只是返回json串
     */
    private function loadDefaultView($isError = false)
    {

        $err = $this['config']->errBody ?? 'err';
        $errMsg = $this['config']->errMsgBody ?? 'errMsg';
        $data = $this['config']->dataBody ?? 'data';
        $isErr = $this['config']->isErrorBody ?? 'isError';

        $data = [
            $err    => $this->errCode,
            $errMsg => $this->errMsg,
            $data   => (object) $this->retData,
            $isErr  => $isError,
        ];

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
            $data = (object) [];
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
        return ErrorCode::$errors;
    }

    /**
     * 注册错误码
     * @param $errors
     */
    static function registerErrors($errors)
    {
        ErrorCode::$errors = array_merge(ErrorCode::$errors, $errors);
    }

    /**
     * 初始化 查找用户自定义错误文件
     */
    private function init()
    {
        require_once 'Exceptions/Exception.php';
        $rootDir = str_replace('\\', '/', realpath(dirname(dirname(dirname(dirname(__DIR__)).'/'))))."/";
        $target = $rootDir.'/'.'errors';
        if (file_exists($target)) {

            if (file_exists($target.'/'.'code.php')) {
                $arr = require_once $target.'/'.'code.php';
                self::registerErrors($arr);
            } else {
                // 错误文件不存在
                throw new \Exception("code.php not exist!");
            }

        } else {
            // 错误文件不存在
            throw new \Exception("code.php not exist!");
        }

        return;
    }

    /**
     * 根据错误码返回错误描述文本
     * @param $errCode  错误码
     * @return string   错误描述
     */
    private function getErrMsginfo($errCode)
    {
        if (array_key_exists($errCode, $this->err2Msg)) {
            return $this->err2Msg[$errCode];
        }

        return "";
    }
}