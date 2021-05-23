<?php
/*
 * @Author: lpc
 * @DateTime: 2021/5/23 14:36
 * @Description: Description
 * 
 * @return 

 */

namespace app\admin\validate;


use app\admin\model\MenuMdl;
use think\Validate;

class MenuValidate extends Validate
{
    private $menuMdl;

    public function __construct()
    {
        parent::__construct();
        $this->menuMdl = new MenuMdl();
    }

    protected $rule = [
        'menu_id'   => 'require|checkEditMark',
        'parent_id' => 'require',
        'title'     => 'require',
        'mark_name' => 'require|unique:sys_menu',

    ];

    protected $message = [
        'menu_id.require'       => '菜单id必填!',
        'menu_id.checkEditMark' => '菜单标识已存在!',
        'parent_id.require'     => '上级菜单必填!',
        'title.require'         => '菜单名必填!',
        'mark_name.require'     => '菜单标识必填!',
        'mark_name.unique'      => '菜单标识已存在!',
    ];

    public function sceneCreate(): MenuValidate
    {
        return $this->only(['parent_id', 'title', 'mark_name']);
    }

    public function sceneEdit(): MenuValidate
    {
        return $this->only(['menu_id', 'parent_id', 'title', 'mark_name'])
            ->remove('mark_name', 'unique');
    }

    public function checkEditMark($value, $rule, $data = []): bool
    {
        $has = $this->menuMdl->where([['menu_id', '<>', $value], ['mark_name', '=', $data['mark_name']]])->count();
        return $has <= 0;
    }
}