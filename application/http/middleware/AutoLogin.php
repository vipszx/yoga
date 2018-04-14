<?php

namespace app\http\middleware;

use app\admin\model\auth\Admin;
use \think\facade\Cookie;
use \think\facade\Session;
class AutoLogin
{
    public function handle($request, \Closure $next)
    {
        $session = Session::get('admin');
        $remember = Cookie::get('remember');
        if ($session || !$remember) {
            return $next($request);
        }
        list($id, $expiretime, $key) = explode('|', $remember);
        if ($id && $expiretime && $key && $expiretime > time()) {
            $admin = Admin::get($id);
            if (!$admin || !$admin->remember_token) {
                return $next($request);
            }
            if ($key == md5($id . $expiretime . $admin->remember_token)) {
                Session::set("admin", $admin->toArray());
                return redirect('admin/index/index');
            }
        }
        return $next($request);
    }
}
