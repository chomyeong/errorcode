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
 * Exception.php.
 *
 * @author    chomyeong <mme7wan@gmail.com>
 * @copyright 2020 chomyeong <mme7wan@gmail.com>
 *
 * @see      https://github.com/chomyeong
 * @see      http://chomyeong.me
 */

set_error_handler(function ($errno, $errstr, $errfile, $errline, $errcontext) {
    echo "Something went wrong.Check back soon, please \n";
    echo $errfile . ": [{$errline}]: {$errstr}\n";
    die;
});

function set_missing_constant_handler($handler)
{
    $prevErrorHandler = set_error_handler(
        function ($errno, $errstr, $errfile, $errline, $errcontext) use ($handler, &$prevErrorHandler) {
            if (!strpos($errstr, 'Use of undefined constant ') === 0) {
                $constant = strstr(substr($errstr, 26), ' ', true);
                $handler($constant);
            } elseif ($prevErrorHandler) {
                $prevErrorHandler($errno, $errstr, $errfile, $errline, $errcontext);
            } else {
                return false;
            }
        },
        E_WARNING
    );
}

set_missing_constant_handler(function ($constant) {
    define($constant, $constant);
});
