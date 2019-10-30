<?php

use Phpmig\Migration\Migration;

class BizInvoice extends Migration
{
    public function up()
    {
        $biz = $this->getContainer();
        $connection = $biz['db'];
        $connection->exec("
            CREATE TABLE IF NOT EXISTS `biz_invoice`(
              `id` int(10) NOT NULL AUTO_INCREMENT COMMENT 'ID',
              `sn` varchar(64) NOT NULL COMMENT '申请开票号',
              `title` varchar(255) NOT NULL DEFAULT '' COMMENT '发票抬头',
              `type` enum('electronic', 'paper', 'vat') NOT NULL COMMENT '发票类型',
              `taxpayer_identity` varchar(255) NOT NULL DEFAULT '' COMMENT '纳税人识别号',
              `content` varchar(100) NOT NULL DEFAULT '' COMMENT '发票内容',
              `comment` varchar(255) NOT NULL DEFAULT '' COMMENT '备注',
              `address` varchar(255) DEFAULT NULL COMMENT '邮寄地址',
              `phone` varchar(255) NOT NULL DEFAULT '' COMMENT '联系电话',
              `email` varchar(255) NOT NULL DEFAULT '' COMMENT '电子邮箱',
              `receiver` varchar(100) NOT NULL DEFAULT '' COMMENT '收件人',
              `user_id` int(10) NOT NULL DEFAULT '0' COMMENT '用户Id',
              `status` enum('unchecked', 'sent', 'refused') NOT NULL DEFAULT 'unchecked' COMMENT '申请状态',
              `money` bigint(16) NOT NULL DEFAULT 0 COMMENT '开票金额',
              `review_user_id` int(11) DEFAULT 0 COMMENT '审核人Id',
              `bank` varchar(255) DEFAULT NULL COMMENT '开户行',
              `account` varchar(255) DEFAULT NULL COMMENT '开户行账号',
              `company_address` varchar(255) DEFAULT NULL COMMENT '公司地址',
              `company_mobile` varchar(20) DEFAULT NULL COMMENT '公司电话',
              `number` varchar(64) DEFAULT '' COMMENT '发票号',
              `post_name` VARCHAR(20) NULL DEFAULT NULL COMMENT '快递名称',
              `post_number` varchar(64) DEFAULT '' COMMENT '邮寄号',
              `refuse_comment` varchar(255) DEFAULT '' COMMENT '拒绝备注',
              `created_time` int(10) unsigned DEFAULT '0' COMMENT '创建时间',
              `updated_time` int(10) unsigned DEFAULT '0' COMMENT '更新时间',
              PRIMARY KEY (`id`)
            ) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

            CREATE TABLE IF NOT EXISTS `biz_invoice_template`(
              `id` int(10) NOT NULL AUTO_INCREMENT COMMENT 'ID',
              `title` varchar(255) NOT NULL DEFAULT '' COMMENT '发票抬头',
              `type` enum('electronic', 'paper', 'vat') NOT NULL COMMENT '发票类型',
              `taxpayer_identity` varchar(255) NOT NULL DEFAULT '' COMMENT '纳税人识别号',
              `content` varchar(100) NOT NULL DEFAULT '培训费' COMMENT '发票内容',
              `comment` varchar(255) NOT NULL DEFAULT '' COMMENT '备注',
              `email` varchar(255) NOT NULL DEFAULT '' COMMENT '电子邮箱',
              `address` varchar(255) DEFAULT NULL COMMENT '邮寄地址',
              `company_address` varchar(255) DEFAULT NULL COMMENT '公司地址',
              `bank` varchar(255) DEFAULT NULL COMMENT '开户行',
              `account` varchar(255) DEFAULT NULL COMMENT '开户行账号',
              `company_mobile` varchar(20) DEFAULT NULL COMMENT '公司电话',
              `phone` varchar(20) NOT NULL DEFAULT '' COMMENT '联系电话',
              `receiver` varchar(100) NOT NULL DEFAULT '' COMMENT '收件人',
              `user_id` int(10) NOT NULL DEFAULT '0' COMMENT '用户Id',
              `created_time` int(10) unsigned DEFAULT '0' COMMENT '创建时间',
              `updated_time` int(10) unsigned DEFAULT '0' COMMENT '更新时间',
              `is_default` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '是否默认',
              PRIMARY KEY (`id`)
            ) ENGINE=InnoDB  DEFAULT CHARSET=utf8;
        ");
    }

    public function down()
    {
        $biz = $this->getContainer();
        $connection = $biz['db'];

        $connection->exec('DROP TABLE `biz_invoice`;');

        $connection->exec('DROP TABLE `biz_invoice_template`;');
    }
}
