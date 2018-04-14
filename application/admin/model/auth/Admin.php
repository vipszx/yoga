<?php

namespace app\admin\model\auth;

use think\Model;
use think\model\concern\SoftDelete;

class Admin extends Model
{
    use SoftDelete;

    protected $autoWriteTimestamp = true;
    protected $updateTime = false;
}
