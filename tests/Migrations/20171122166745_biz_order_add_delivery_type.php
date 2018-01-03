<?php

use Phpmig\Migration\Migration;

class BizOrderAddDeliveryType extends Migration
{
    /**
     * Do the migration
     */
    public function up()
    {
        $biz = $this->getContainer();
        $connection = $biz['db'];

        $connection->exec("ALTER TABLE `biz_order_item` ADD COLUMN `shipping_type` VARCHAR(32) NOT NULL DEFAULT 'virtual' COMMENT '商品类型（虚拟 virtual、实物 express）';");
    }

    /**
     * Undo the migration
     */
    public function down()
    {
        $biz = $this->getContainer();
        $connection = $biz['db'];

        $connection->exec("ALTER TABLE `biz_order_item` DROP COLUMN  `shipping_type`;");
    }
}
