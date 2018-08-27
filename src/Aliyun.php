<?php namespace Houdunwang\Aliyun;

use Houdunwang\Aliyun\Build\Base;

include_once __DIR__ . '/../aliyun-openapi-php-sdk/aliyun-php-sdk-core/Config.php';

/** .-------------------------------------------------------------------
 * |  Software: [HDCMS framework]
 * |      Site: www.hdcms.com
 * |-------------------------------------------------------------------
 * |    Author: 向军 <2300071698@qq.com>
 * |    WeChat: aihoudun
 * | Copyright (c) 2012-2019, www.houdunwang.com. All Rights Reserved.
 * '-------------------------------------------------------------------*/
class Aliyun
{
    //连接
    protected static $link;

    //配置
    protected static $config;

    public static function single()
    {
        if (is_null(self::$link)) {
            self::$link = new Base();
        }
        return self::$link;
    }

    public static function config(array $config)
    {
        self::$config = $config;
        return self::single();
    }

    /**
     * @return array
     */
    public static function getConfig($name)
    {
        return self::$config[$name];
    }

    public function __call($method, $params)
    {
        return call_user_func_array([self::single(), $method], $params);
    }

    public static function __callStatic($name, $arguments)
    {
        return call_user_func_array([self::single(), $name], $arguments);
    }

    /**
     * 获取实例
     *
     * @param string $api
     *
     * @return mixed
     */
    public function instance($api)
    {
        $class = '\Houdunwang\Aliyun\Build\\' . ucfirst($api) . '\\App';
        return new $class();
    }

    /**
     * 获取阿里云执行请求客户端
     *
     * @return \DefaultAcsClient
     */
    public static function client()
    {
        //regionId,根据服务器所在区域进行选择 https://help.aliyun.com/document_detail/40654.html?spm=5176.7114037.1996646101.1.OCtdEo
        $regionId = self::getConfig('regionId');
        $accessKeyId = self::getConfig('accessId');
        $accessKeySecret = self::getConfig('accessKey');
        $iClientProfile = \DefaultProfile::getProfile($regionId, $accessKeyId, $accessKeySecret);
        return new \DefaultAcsClient($iClientProfile);
    }
}
