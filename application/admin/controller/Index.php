<?php

namespace app\admin\controller;

use think\Controller;
use think\Db;
use think\Request;

class Index extends Base
{
    public function index(Request $request)
    {
        return $this->fetch();
    }
}
