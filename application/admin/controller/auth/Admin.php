<?php

namespace app\admin\controller\auth;

use app\admin\controller\Base;
use think\Controller;
use think\Request;
use think\Db;
use app\admin\model\auth\Admin as AdminModel;

class Admin extends Base
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        $list = AdminModel::all();
        $auth_group = Db::name('auth_group')->column('name', 'id');
        foreach ($list as $key => $val) {
            $role = Db::name('auth_group_access')->where('uid', $val['id'])->column('group_id');
            if ($role) {
                foreach ($role as &$v) {
                    $v = isset($auth_group[$v]) ? $auth_group[$v] : '';
                }
            }
            $list[$key]['role'] = implode(',', $role);
        }
        $this->assign('list', $list);
        return $this->fetch();
    }

    /**
     * 显示创建资源表单页.
     *
     * @return \think\Response
     */
    public function create()
    {
        $auth_group = Db::name('auth_group')->where('status',1)->column('title', 'id');
        $this->assign('auth_group', $auth_group);
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
        $username = $request->post('username');
        $password = $request->post('password');
        $auth_group = $request->post('auth_group');
        $status = $request->post('status',0, 'intval');
        $user = AdminModel::create(['username' => $username, 'password' => md5($password), 'status'=>$status]);
        if (!$user) {
            $this->error('添加失败');
        }
        if (!Db::name('auth_group_access')->insert(['uid' => $user->id, 'group_id' => $auth_group])) {
            $this->error('添加失败');
        }
        $this->redirect('index');
    }

    /**
     * 显示指定的资源
     *
     * @param  int $id
     * @return \think\Response
     */
    public function read($id)
    {
        //
    }

    /**
     * 显示编辑资源表单页.
     *
     * @param  int $id
     * @return \think\Response
     */
    public function edit($id)
    {
        $user = Db::name('admin')->where('id', $id)->find();
        //获取该用户的用户组
        $access_group = Db::name('auth_group_access')->where(['uid' => $id])->column('group_id');
        $group = Db::name('auth_group')->field('id,title,status')->select();
        $this->assign('group', $group);
        $this->assign('user', $user);
        $this->assign('access_group', $access_group);
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
        $username = $request->post('username');
        $password = $request->post('password');
        $auth_group = $request->post('auth_group');
        $status = $request->post('status', 0, 'intval');
        $data['username'] = $username;
        $data['status'] = $status;
        if ($password) {
            $data['password'] = md5($password);
        }
        if (false === Db::name('admin')->where('id', $id)->update($data)) {
            $this->error('修改失败');
        }
        if (false === Db::name('auth_group_access')->where('uid', $id)->update(['group_id' => $auth_group])) {
            $this->error('修改失败');
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
        AdminModel::destroy($id);
        $this->redirect('index');
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
}
