<?php

use Phpmig\Migration\Migration;

class BizAddress extends Migration
{
    /**
     * Do the migration
     */
    public function up()
    {
        $biz = $this->getContainer();
        $connection = $biz['db'];
        $connection->exec("
            CREATE TABLE IF NOT EXISTS `biz_address` (
              `id` INT(10) unsigned NOT NULL AUTO_INCREMENT,
              `user_id` INT(10) unsigned NOT NULL COMMENT '用户id',
              `name` VARCHAR(64) NOT NULL DEFAULT '' COMMENT '收货人姓名',
              `mobile` VARCHAR(32) NOT NULL DEFAULT '' COMMENT '手机号码',
              `province` VARCHAR(64) NOT NULL DEFAULT '' COMMENT '省',
              `city` VARCHAR(64) NOT NULL DEFAULT '' COMMENT '市',
              `district` VARCHAR(64) NOT NULL DEFAULT '' COMMENT '区',
              `town` VARCHAR(64) NOT NULL DEFAULT '' COMMENT '街道',
              `detail` VARCHAR(64) NOT NULL DEFAULT '' COMMENT '详细地址',
              `zip` VARCHAR(16) COMMENT '邮编',
              `is_default` TINYINT NOT NULL DEFAULT '0' COMMENT '是否是默认地址',
              `created_time` INT(10) unsigned NOT NULL DEFAULT '0',
              `updated_time` INT(10) unsigned NOT NULL DEFAULT '0',
              PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
        ");
    }

    protected function isFieldExist($table, $filedName)
    {
        $biz = $this->getContainer();
        $db = $biz['db'];

        $sql = "DESCRIBE `{$table}` `{$filedName}`;";
        $result = $db->fetchAssoc($sql);

        return empty($result) ? false : true;
    }

    /**
     * Undo the migration
     */
    public function down()
    {
        $biz = $this->getContainer();
        $connection = $biz['db'];
        $connection->exec("
            DROP TABLE `biz_address`;
        ");
    }
}
