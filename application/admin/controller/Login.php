<?php

namespace app\admin\controller;

use app\admin\model\auth\Admin;
use think\Controller;
use think\facade\Cookie;
use think\facade\Session;

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
        $remember_me = input('remember_me', 0, 'intval');
        $admin_model = new Admin();
        $admin = $admin_model->where(['username' => $username, 'password' => md5($password)])->find();
        if ($admin) {
            $admin->remember_token = md5(time() . rand(100, 999));
            $admin->save();
            if ($remember_me) {
                $keeptime = 86400;
                $expiretime = time() + $keeptime;
                $key = md5($admin->id . $expiretime . $admin->remember_token);
                $data = [$admin->id, $expiretime, $key];
                Cookie::set('remember', implode('|', $data), $keeptime);
            }
            Session::set('admin', $admin->toArray());
            $this->redirect('admin/index/index');
        } else {
            $this->error('账号密码错误');
        }
    }

    public function loginout()
    {
        Admin::update(['remember_token' => ''], ['id' => session('admin.id')]);
        Session::delete('admin');
        Cookie::delete('remember');
        $this->redirect('admin/login/index');
    }
}