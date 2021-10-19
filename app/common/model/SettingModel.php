<?php

namespace app\common\model;

use plum\lib\Arr;

class SettingModel extends BaseModel
{
    protected $table = 'setting';
    protected $autoWriteTimestamp = 'timestamp';
    protected $type = ['value' => 'json'];

    /**
     * 获取
     * @author Plum
     * @email 18685850590@163.com
     * @time 2021年10月02日 14:11
     */
    public static function getItem($key)
    {
        $data = self::getAllItem();
        return $data[$key]['value'] ?? [];
    }

    /**
     * 设置
     * @author Plum
     * @email 18685850590@163.com
     * @time 2021年10月02日 15:16
     */
    public static function setItem($key, $value)
    {
        $data = self::getAllItem();
        $data = $data[$key] ?? [];
        if (empty($data)) {
            return false;
        }
        //合并数据
        $data = Arr::mergeMultiple($data, compact('value'));
        //存入数据库
        if(self::where('key',$key)->find()){
            self::update($data);
        }else{
            self::create($data);
        }
        //更新缓存
        cache('setting', null);
        return self::getItem($key);
    }

    /**
     * 获取所有设置
     * @author Plum
     * @email 18685850590@163.com
     * @time 2021年10月02日 13:09
     */
    protected static function getAllItem()
    {
        //获取缓存查看有没有
        if (!$data = cache('setting')) {
            //获取数据库里的数据
            $data = self::select()->toArray();
            //提取键值
            $data = Arr::pluck($data, null, 'key');
            //合并默认数据
            $data = Arr::mergeMultiple(self::defaultConfig(), $data);
            cache('setting', $data, null, 'setting');
        }
        return $data;
    }

    /**
     * 默认配置
     * @author Plum
     * @email 18685850590@163.com
     * @time 2021年10月01日 21:49
     */
    protected static function defaultConfig()
    {
        return [
            //上传设置
            'storage' => [
                "key"      => 'storage',
                "describe" => '上传设置',
                "value"    => [
                    'default' => 'local',
                    'disks'   => [
                        'local'  => [
                            'type'       => 'local',
                            // 磁盘路径
                            'root'       => app()->getRootPath() . 'public/storage',
                            // 磁盘路径对应的外部URL路径
                            'url'        => request()->domain() . '/storage',
                            // 可见性
                            'visibility' => 'public',
                        ],
                        'aliyun' => [
                            'type'         => 'aliyun',
                            'accessId'     => '',
                            'accessSecret' => '',
                            'bucket'       => '',
                            'endpoint'     => '',
                            'url'          => '',
                        ],
                        'qiniu'  => [
                            'type'      => 'qiniu',
                            'accessKey' => '4lSrvx_kEJOF_Gc5x9PymoTluIxuyduAspf5E7Oz',
                            'secretKey' => '4NH-cGOCX2VQKQTyWrYvIJayneuQyU5wVbJ_yZ4o',
                            'bucket'    => 'hmpz',
                            'url'       => 'http://file.huamzl.wang',//不要斜杠结尾，此处为URL地址域名。
                        ],
                        'qcloud' => [
                            'type'      => 'qcloud',
                            'region'    => '', //bucket 所属区域 英文
                            'appId'     => '', // 域名中数字部分
                            'secretId'  => '',
                            'secretKey' => '',
                            'bucket'    => '',
                            'cdn'       => '',
                        ]
                    ],
                    'rule'    => [
                        'image' => [
                            'status'    => true,
                            'extension' => ['png', 'jpg'],
                            'size'      => 2097152,
                            'options'   => ['gif', 'jpeg', 'jpg', 'png', 'tif', 'tiff', 'wbmp', 'ico', 'jng', 'bmp',
                                'svg', 'svgz', 'webp']
                        ],
                        'video' => [
                            'status'    => true,
                            'extension' => ['mp4'],
                            'size'      => 2097152,
                            'options'   => ['3gpp', '3gp', 'mp4', 'mpeg', 'mpg', 'mov', 'webm', 'flv', 'm4v', 'wmv', 'avi']
                        ],
                        'file'  => [
                            'status'    => true,
                            'extension' => ['txt'],
                            'size'      => 0,
                            'options'   => ['doc', 'pdf', 'rtf', 'xls', 'ppt', 'rar', 'wf', 'zip', 'mid' , 'midi' , 'kar', 'mp3', 'ogg', 'm4a', 'ra', 'css', 'html' , 'htm' , 'shtml', 'txt', 'xml']
                        ],
                    ]
                ]
            ],
            'website' => [
                'key'      => 'website',
                'describe' => '站点设置',
                'value'    => [
                    'name'   => '',//网站名称
                    'logo'   => '',//网站logo
                    'icon'   => '',//网站icon
                    'bei_an' => '',//备案信息
                ]
            ]
        ];
    }
}

