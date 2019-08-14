<?php
$installer = $this;

$installer->startSetup();

$installer->run("
    CREATE TABLE IF NOT EXISTS {$this->getTable('creativestyle_affilinet_datafeed')} (
      `id` int(16) unsigned NOT NULL auto_increment,
      `created_at` timestamp NULL DEFAULT NULL,
      `updated_at` timestamp NULL DEFAULT NULL,
      `name` varchar(255) NOT NULL,
      `company_logo` varchar(255) NULL,
      `escaping` varchar(10) NOT NULL,
      `delimiter_escape` varchar(10) NOT NULL,
      `delimiter` varchar(10) NOT NULL,
      `language` varchar(10) NOT NULL,
      `encoding` varchar(10) NOT NULL,
      `column_title` TINYINT(1) DEFAULT 1,
      `last_delimiter` TINYINT(1) DEFAULT 1,
      `filter_active` TINYINT(1) DEFAULT 1,
      `filter_stock` TINYINT(1) DEFAULT 1,
      `one_variation` TINYINT(1) DEFAULT 1,
      `cron_active` TINYINT(1) DEFAULT 0,
      `cron_start` timestamp NULL DEFAULT NULL,
      `cron_repeat` TINYINT(3) DEFAULT 0,
      `cron_file` varchar(255) NULL,
      `next_generate` timestamp NULL DEFAULT NULL,
      `store_id` TINYINT(3) DEFAULT 0,
      `last_page` INT(6) DEFAULT 0,
      `coll_size` INT(16) DEFAULT 0,
      `cron_lock` TINYINT(1) DEFAULT 0,
      `send_test` TINYINT(1) DEFAULT 0,
      `testemail` varchar(255) NULL,
      PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;");

$installer->run("
  INSERT INTO {$this->getTable('creativestyle_affilinet_datafeed')}
  (`id`, `created_at`, `updated_at`, `name`, `company_logo`, `escaping`, `delimiter_escape`, `delimiter`, `language`, `encoding`, `column_title`, `last_delimiter`, `filter_active`, `filter_stock`, `one_variation`, `cron_active`, `cron_start`, `cron_repeat`, `cron_file`, `next_generate`, `store_id`, `last_page`, `cron_lock`, `send_test`, `testemail`)
  VALUES (1, '2014-06-29 16:57:15', '2014-06-30 08:40:49', 'Default', '', '".'""'."', 'backslash', ';', 'English', 'UTF-8', 1, 1, 1, 1, 0, 0, '00,00,00', 0, 'default', NULL, 0, 0, 0, 0, NULL);
  ");


$installer->endSetup();