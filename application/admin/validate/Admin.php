<?php

namespace app\admin\validate;

use think\Validate;

class Admin extends Validate
{
    protected $rule = [
        'username|用户名' => 'require|max:32|unique:admin|token',
        'nickname|昵称' => 'require|max:20',
    ];

}