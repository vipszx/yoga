<?php

namespace app\admin\controller\auth;

use app\admin\controller\Base;
use think\Controller;
use think\Db;
use think\Request;
use app\admin\model\auth\AuthRule;
use think\facade\Request as RequestFacade;
use php4world\Auth;

class Rule extends Base
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        $data = Db::name('auth_rule')->order('sort')->select();
        $menu = $this->find_level($data);
        $this->assign('menu', $menu);
        return $this->fetch();
    }

    public function find_level($list, $parent_id = 0, $level = 1)
    {
        $arr = [];
        foreach ($list as $l) {
            if ($l['pid'] == $parent_id) {
                $l['level'] = $level;
                $arr[] = $l;
                $child = $this->find_level($list, $l['id'], $level + 1);
                if (is_array($child)) {
                    $arr = array_merge($arr, $child);
                }
            }
        }
        return $arr;
    }

    /**
     * 显示创建资源表单页.
     *
     * @return \think\Response
     */
    public function create()
    {
        $data = Db::name('auth_rule')->select();
        $menu = $this->find_level($data);
        $this->assign('menu', $menu);
        return $this->fetch();
    }

    /**
     * 保存新建的资源
     *
     * @param  \think\Request $request
     * @return \think\Response
     */
    public function save(Request $request)
    {
        $post = $request->post();
        if (isset($post['is_menu']) && $post['is_menu'] == 'on') {
            $post['is_menu'] = 1;
        } else {
            $post['is_menu'] = 0;
        }
        if (isset($post['status']) && $post['status'] == 'on') {
            $post['status'] = 1;
        } else {
            $post['status'] = 0;
        }
        if ((new AuthRule())->save($post) === false) {
            $this->error('操作失败');
        }
        $this->redirect('index');
    }

    /**
     * 显示编辑资源表单页.
     *
     * @param  int $id
     * @return \think\Response
     */
    public function edit($id)
    {
        $data = Db::name('auth_rule')->select();
        $menu = $this->find_level($data);
        $rule = AuthRule::get($id);
        $this->assign('rule', $rule);
        $this->assign('menu', $menu);
        return $this->fetch();
    }

    /**
     * 保存更新的资源
     *
     * @param  \think\Request $request
     * @param  int $id
     * @return \think\Response
     */
    public function update(Request $request, $id)
    {
        $post = $request->post();
        if (isset($post['is_menu']) && $post['is_menu'] == 'on') {
            $post['is_menu'] = 1;
        } else {
            $post['is_menu'] = 0;
        }
        if (isset($post['status']) && $post['status'] == 'on') {
            $post['status'] = 1;
        } else {
            $post['status'] = 0;
        }
        if ((new AuthRule())->where('id', $id)->update($post) === false) {
            $this->error('操作失败');
        }
        $this->redirect('index');
    }

    /**
     * 删除指定资源
     *
     * @param  int $id
     * @return \think\Response
     */
    public function delete($id)
    {
        if (AuthRule::destroy($id) === false) {
            $this->error('删除失败');
        }
        $this->redirect('index');
    }
}
