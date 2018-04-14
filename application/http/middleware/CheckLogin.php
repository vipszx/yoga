<?php

namespace app\http\middleware;

class CheckLogin
{
    protected $except = [
        '/admin/login/index',
        '/admin/login/login'
    ];

    public function handle($request, \Closure $next)
    {
        $route = '/' . strtolower(implode('/', [$request->module(), $request->controller(), $request->action()]));
        if (in_array($route, $this->except) || session('admin')) {
            return $next($request);
        } else {
            return redirect('admin/login/index');
        }
    }
}
