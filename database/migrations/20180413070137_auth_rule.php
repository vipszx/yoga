<?php

use think\migration\Migrator;
use think\migration\db\Column;

class AuthRule extends Migrator
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
        $table = $this->table('auth_rule', array('engine' => 'InnoDB'));
        $table->addColumn('pid', 'integer')
            ->addColumn('name', 'string', array('comment' => '规则唯一标识'))
            ->addColumn('title', 'string', array('comment' => '规则中文名称'))
            ->addColumn('type', 'boolean', array('limit' => 1, 'default' => 0, 'comment' => 'type'))
            ->addColumn('ststus', 'boolean', array('limit' => 1, 'default' => 1, 'comment' => '状态：为1正常，为0禁用'))
            ->addColumn('condition', 'string', array('default' => '', 'comment' => '规则附件条件'))
            ->addColumn('icon', 'string', array('limit' => 50, 'default' => '', 'comment' => 'ICON', 'null' => true))
            ->addColumn('sort', 'integer', array('default' => 0, 'comment' => '排序', 'null' => true))
            ->addIndex(array('name'), array('unique' => true))
            ->create();
    }
}
