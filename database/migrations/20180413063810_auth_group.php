<?php

use think\migration\Migrator;
use think\migration\db\Column;

class AuthGroup extends Migrator
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-abstractmigration-class
     *
     * The following commands can be used in this method and Phinx will
     * automatically reverse them when rolling back:
     *
     *    createTable
     *    renameTable
     *    addColumn
     *    renameColumn
     *    addIndex
     *    addForeignKey
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function change()
    {
        // create the table
        $table = $this->table('auth_group', array('engine' => 'InnoDB'));
        $table->addColumn('title', 'string', array('limit' => 100, 'default' => '', 'comment' => '用户组名'))
            ->addColumn('status', 'boolean', array('limit' => 1, 'default' => 0, 'comment' => '状态'))
            ->addColumn('rules', 'text', array('comment' => '规则', 'null' => true))
            ->addColumn('sort', 'integer', array('default' => 0, 'comment' => '排序', 'null' => true))
            ->addColumn('remark', 'string', array('comment' => '备注', 'null' => true))
            ->create();
    }
}
