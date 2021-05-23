<?php
/*
 * @Author: lpc
 * @DateTime: 2021/5/18 15:24
 * @Description: Description
 * 
 * @return 

 */

namespace app\admin\model;


use app\common\utils\Result;
use Exception;
use LogicException;
use think\db\exception\DataNotFoundException;
use think\db\exception\DbException;
use think\db\exception\ModelNotFoundException;
use think\facade\Db;

class MenuMdl extends BaseModel
{
    protected $table = 'sys_menu';
    protected $pk = 'menu_id';

    public function saveMenu($data): array
    {
        Db::startTrans();
        try {
            $data['level'] = $this->getParentToLevel($data['parent_id']);
            if (empty($data['menu_id'])) {
                $data['create_time'] = time();
                $data['menu_id']     = $this->insertGetId($data);
            } else {
                $this->update($data);
            }
            Db::commit();
            return Result::serviceSucc(['data' => $data], "保存成功");
        } catch (Exception $e) {
            Db::rollback();
            return Result::serviceError($e->getMessage());
        }
    }

    /**
     * @Author: lpc
     * @DateTime: 2021/5/23 14:56
     * @Description:根据菜单id获取其子菜单等级
     * @param $menuId
     * @return array|int
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     */
    public function getParentToLevel($menuId)
    {
        if ($menuId == 0) {
            return 1;
        }
        $info = $this->field('level')->where(['menu_id' => $menuId])->find();

        if (is_null($info)) {
            throw new LogicException('没有此上级菜单！');
        }
        $info = $info->toArray();
        return ((int)$info['level']) + 1;
    }
}