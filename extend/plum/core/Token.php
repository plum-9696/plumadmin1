<?php

namespace plum\core;

use plum\core\exception\AuthException;

class Token
{
    /**
     * 生成token
     * @author Plum
     * @email 18685850590@163.com
     * @time 2021年10月03日 16:33
     */
    public static function build($uuid, $channel)
    {
        $token = self::generateToken();
        $refreshToken = self::generateToken();
        $expire = config('plum.token.expire') + time();
        $refreshExpire = config('plum.token.refresh_expire') + time();
        $key = self::getKey($token, $channel);
        $value = [
            'uuid'          => $uuid,
            'expire'        => $expire,
            'refresh_token' => $refreshToken
        ];
        cache($key, $value, $refreshExpire);
        return [
            'token'          => $token,
            'expire'         => $expire,
            'refresh_token'  => $refreshToken,
            'refresh_expire' => $refreshExpire,
        ];
    }

    /**
     * 生成键值
     * @author Plum
     * @email 18685850590@163.com
     * @time 2021年10月03日 16:35
     */
    private static function getKey($token, $channel)
    {
        return config('plum.token.cache_prefix') . "_{$channel}_{$token}";
    }

    /**
     * 拉黑
     * @author Plum
     * @email 18685850590@163.com
     * @time 2021年10月04日 18:14
     */
    public static function invalid($channel)
    {
        $token = self::getToken();
        cache(self::getKey($token, $channel));
    }

    /**
     * 刷新token
     * @author Plum
     * @email 18685850590@163.com
     * @time 2021年10月03日 16:47
     */
    public static function refresh($channel)
    {
        if ($token = self::getToken()) {
            if ($value = cache(self::getKey($token, $channel))) {
                //作废掉之前的token
                cache(self::getKey($token, $channel));
                //生成新的
                return self::build($value['uuid'], $channel);
            }
        }
        throw new AuthException();
    }

    /**
     * 验证获取uuid
     * @author Plum
     * @email 18685850590@163.com
     * @time 2021年10月03日 16:44
     */
    public static function auth($channel, $force = true)
    {
        if ($token = self::getToken()) {
            if ($value = cache(self::getKey($token, $channel))) {
                if ($value['expire'] >= time()) {
                    return $value['uuid'];
                }
            }
        }
        if ($force) {
            //抛出异常
            throw new AuthException();
        }
        return 0;
    }

    /**
     * 获取token
     * @author Plum
     * @email 18685850590@163.com
     * @time 2021年10月03日 16:12
     */
    private static function getToken()
    {
        $authorization = request()->header('authorization', '');
        $pattern = '/^bearer\s?(\w{32})$/i';
        if (preg_match($pattern, $authorization, $match) && isset($match[1])) {
            return $match[1];
        }
        return false;
    }

    /**
     * 随机生成32位token
     * @author Plum
     * @email 18685850590@163.com
     * @time 2021年10月03日 16:10
     */
    private static function generateToken()
    {
        return md5(uniqid(microtime() . mt_rand(), true));
    }
}
