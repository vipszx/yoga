<?php

namespace app\admin\controller;

use think\Controller;
use think\facade\Request;
use think\Db;
use php4world\Auth;

class Base extends Controller
{
    public $current_route;

    public function initialize()
    {
        parent::initialize();
        $route = '/' . strtolower(implode('/', [Request::module(), Request::controller(), Request::action()]));
        $id = session('admin.id');
        $group_id = Db::name('auth_group_access')->where('uid', $id)->find()['group_id'];
        $user_rules = Db::name('auth_group')->where('id', $group_id)->find()['rules'];
//        $all_rules = Db::name('auth_rule')->whereIn('id', $user_rules)->select();
        if ($user_rules != '*') {
            if ((new Auth())->check($route, $id) === false) {
                $this->error('没有权限');
            }
        }
        $this->current_route = $route;
        $this->menu();
    }

    final private function menu()
    {
        $data = Db::name('auth_rule')->order('sort')->select();
        $menu = self::list_to_tree($data);
        $this->assign('menu_list', $menu);
    }

    public function list_to_tree($list, $pk = 'id', $pid = 'pid', $child = '_child', $root = 0)
    {
        // 创建Tree
        $tree = array();
        if (is_array($list)) {
            // 创建基于主键的数组引用
            $refer = array();
            foreach ($list as $key => $data) {
                $refer[$data[$pk]] =& $list[$key];
            }
            foreach ($list as $key => $data) {
                // 判断是否存在parent
                $parentId = $data[$pid];
                //active
                $list[$key]['active'] = $data['name'] == $this->current_route;
                if ($root == $parentId) {
                    $tree[] =& $list[$key];
                } else {
                    if (isset($refer[$parentId])) {
                        $parent =& $refer[$parentId];
                        if ($parent['active'] != true) {
                            $parent['active'] = $data['name'] == $this->current_route;
                            if ($parent['active']) {
                                if(isset($refer[$parent['pid']])){
                                    $refer[$parent['pid']]['active'] = true;
                                }
                            }
                        }
                        $parent[$child][] =& $list[$key];
                    }
                }
            }
        }
        return $tree;
    }
}
