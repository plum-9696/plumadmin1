<?php

namespace app\common\model;

use app\common\model\file\GroupModel;
use plum\lib\Arr;
use plum\lib\Utils;
use think\facade\Filesystem;

class FileModel extends BaseModel
{
    protected $table = 'file';
    protected $autoWriteTimestamp = 'timestamp';
    protected $append = ['url'];

    const CHANNEL_ADMIN = 1;

    protected static function init()
    {
        $config = SettingModel::getItem('storage');
        // 本地需要实时读取
        $config['disks']['local']=[
            'type'       => 'local',
            // 磁盘路径
            'root'       => app()->getRootPath() . 'public/storage',
            // 磁盘路径对应的外部URL路径
            'url'        => request()->domain() . '/storage',
            // 可见性
            'visibility' => 'public',
        ];
        //设置用户配置
        config($config, 'filesystem');
    }

    /**
     * 获取分页
     * @author Plum
     * @email 18685850590@163.com
     * @time 2021年10月05日 13:29
     */
    public function getPage()
    {
        return $this->plumOrder()
            ->plumSearch()
            ->where('channel', static::channel())
            ->paginate();
    }

    /**
     * 根据ids获取list
     * @author Plum
     * @email 18685850590@163.com
     * @time 2021年10月05日 13:33
     */
    public function getListByIds($ids)
    {
        $list = $this->whereIn('id', $ids)
            ->select()
            ->toArray();
        $list = Arr::pluck($list, null, 'id');
        return array_map(function ($v) use ($list) {
            if (isset($list[$v])) {
                return array_merge($list[$v], ['status' => 'success']);
            }
            return ['id' => $v, 'status' => 'error'];
        }, $ids);
    }

    /**
     * 删除图片
     * @author Plum
     * @email 18685850590@163.com
     * @time 2021年10月05日 13:37
     */
    public function deleteItem($ids)
    {
        //删除数据库
        $list = $this->whereIn('id', $ids)->select();
        self::destroy($ids);
        //删除图库
        foreach ($list as $v) {
            try {
                Filesystem::disk($v['storage'])->delete($v['file_path']);
            } catch (\Throwable $e) {
                //TODO::记录日志函数
            }
        }
    }

    /**
     * 获取器 - url
     * @author Plum
     * @email 18685850590@163.com
     * @time 2021年10月04日 18:35
     */
    public function getUrlAttr($value, $data)
    {
        return str_replace('\\', '/', Filesystem::disk($data['storage'])->getUrl($data['file_path']));
    }

    /**
     * 搜索器 - group_id
     * @author Plum
     * @email 18685850590@163.com
     * @time 2021年10月05日 13:01
     */
    public function searchGroupIdAttr($query, $value)
    {
        //获取子分类
        $groupIds = Utils::getChildrenIds(new GroupModel(), [$value]);
        array_push($groupIds, $value);
        $query->whereIn('group_id', $groupIds);
    }

    /**
     * 搜索器 - keyword
     * @author Plum
     * @email 18685850590@163.com
     * @time 2021年10月07日 23:30
     */
    public function searchKeywordAttr($query, $value)
    {
        $query->whereLike('file_name', "%$value%");
    }

    public function searchFileTypeAttr($query, $value)
    {
        $query->where('file_type',$value);
    }

    /**
     * 通道
     * @author Plum
     * @email 18685850590@163.com
     * @time 2021年10月03日 00:45
     */
    protected static function channel()
    {
        return '';
    }

    /**
     * 上传者
     * @author Plum
     * @email 18685850590@163.com
     * @time 2021年10月03日 00:45
     */
    protected static function uploaderId()
    {
        return 0;
    }

    /**
     * 上传
     * @author Plum
     * @email 18685850590@163.com
     * @time 2021年10月03日 14:15
     */
    public function upload($groupId)
    {
        $file = request()->file('file');
        self::check($file);
        $saveName = Filesystem::putFile('file', $file);
        //TODO::格式化,可能存在的问题
        //需要入库
        $data = [
            'group_id'    => $groupId,
            'channel'     => static::channel(),
            'storage'     => config('filesystem.default'),
            'file_type'   => self::getType($file),
            'file_name'   => $file->getOriginalName(),
            'file_path'   => $saveName,
            'file_size'   => $file->getSize(),
            'file_ext'    => $file->getOriginalExtension(),
            'uploader_id' => static::uploaderId()
        ];
        return self::create($data);
    }

    /**
     * 校验文件
     * @author Plum
     * @email 18685850590@163.com
     * @time 2021年10月03日 00:42
     */
    private static function check($file)
    {
        if (empty($file)) {
            abort('请选择上传的文件');
        }
        //获取对应文件的规则
        $rule = config("filesystem.rule." . self::getType($file));
        //校验文件类型是否可以上传
        if ($rule['status'] === false) {
            abort('不支持上传此类文件');
        }
        //校验大小是否可以上传
        if ($rule['size'] && $rule['size'] * 1024 * 1024 < $file->getSize()) {
            abort('上传文件大小超过' . $rule['size'] . 'MB');
        }
        //校验格式的后后缀
        if (!Arr::inArray($file->getOriginalExtension(), $rule['extension'])) {
            abort('上传文件只支持' . implode('、', $rule['extension']) . '格式');
        }
    }

    /**
     * 获取文件的类型
     * @author Plum
     * @email 18685850590@163.com
     * @time 2021年10月02日 23:55
     */
    private static function getType($file)
    {
        return strtolower(strstr($file->getOriginalMime(), '/', true));
    }
}
