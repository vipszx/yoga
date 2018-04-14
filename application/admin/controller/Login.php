<?php

namespace app\admin\controller;

use app\admin\model\auth\Admin;
use think\Controller;
use think\Request;
use think\facade\Session;
use think\Validate;

class Login extends Controller
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        return $this->fetch();
    }

    public function login()
    {
        $username = input('username');
        $password = input('password');
        $admin_model = new Admin();
        $user = $admin_model->where(['username' => $username, 'password' => md5($password)])->find();
        if ($user) {
            session('user', $user->toArray());
            $this->redirect('admin/index/index');
        } else {
            $this->error('账号密码错误');
        }
    }

    public function loginout()
    {
        Session::delete('user');
        $this->redirect('admin/login/index');
    }
}