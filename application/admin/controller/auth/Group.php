<?php

namespace app\admin\controller\auth;

use app\admin\controller\Base;
use think\Controller;
use think\Request;
use think\Db;

class Group extends Base
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        $list = Db::name('auth_group')->select();
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
        $name = $request->post('name');
        $title = $request->post('title');
        $sort = $request->post('sort', 0, 'intval');
        $remark = $request->post('remark');
        $status = $request->post('status', 0, 'intval');
        $data['name'] = $name;
        $data['title'] = $title;
        $data['sort'] = $sort;
        $data['remark'] = $remark;
        $data['status'] = $status;
        if (!Db::name('auth_group')->insert($data)) {
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
        $group = Db::name('auth_group')->where('id', $id)->find();
        $this->assign('group', $group);
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
        $name = $request->post('name');
        $title = $request->post('title');
        $sort = $request->post('sort', 0, 'intval');
        $remark = $request->post('remark');
        $status = $request->post('status', 0, 'intval');
        if ($id == 1) {
            $status = 1;
        }
        $data['name'] = $name;
        $data['title'] = $title;
        $data['sort'] = $sort;
        $data['remark'] = $remark;
        $data['status'] = $status;
        if (Db::name('auth_group')->where('id', $id)->update($data) === false) {
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
        if ($id == 1) {
            $this->error('超级管理员不可删除');
        }
        if (!Db::name('auth_group')->where('id', $id)->delete()) {
            $this->error('删除失败');
        }
        $this->redirect('index');
    }

    public function list_to_tree($list, $pk='id', $pid = 'pid', $child = 'children', $root = 0) {
        // 创建Tree
        $tree = array();
        if(is_array($list)) {
            // 创建基于主键的数组引用
            $refer = array();
            foreach ($list as $key => $data) {
                $refer[$data[$pk]] =& $list[$key];
            }
            foreach ($list as $key => $data) {
                // 判断是否存在parent
                $parentId =  $data[$pid];
                if ($root == $parentId) {
                    $tree[] =& $list[$key];
                }else{
                    if (isset($refer[$parentId])) {
                        $parent =& $refer[$parentId];
                        $parent[$child][] =& $list[$key];
                    }
                }
            }
        }
        return $tree;
    }

    public function permission()
    {
        $id = input('id',0,'intval');
        if ($id == 1) {
            $this->error('管理员');
        }
        $data = Db::name('auth_rule')->field('id, pid, title as label')->order('sort', 'ASC')->select();
        $data = json_encode($this->list_to_tree($data),JSON_UNESCAPED_UNICODE);
        $checked = Db::name('auth_group')->field('rules')->where('id',$id)->find();
        $this->assign('checked', $checked['rules']);
        $this->assign('data', $data);
        $this->assign('id', $id);
        return $this->fetch();
    }

    public function editPermission($id){
        if ($id == 1) {
            $this->error('管理员');
        }
        $data = input('post.rule/a');
        if (isset($data[0])) {
            //有数据
            $data = implode(",",$data);
            Db::name('auth_group')->where('id',$id)->update(['rules'=>$data]);
        } else {
            Db::name('auth_group')->where('id',$id)->update(['rules'=>'']);
        }
        return json(['result'=>'ok']);
    }


}
