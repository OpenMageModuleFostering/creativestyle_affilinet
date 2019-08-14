<?php
$installer = $this;

$installer->startSetup();

$installer->run("
    CREATE TABLE IF NOT EXISTS {$this->getTable('creativestyle_affilinet_datafeed_filter')} (
      `id` int(16) unsigned NOT NULL auto_increment,
      `datafeed_id` int(16) unsigned NOT NULL,
      `fieldname` varchar(255) NOT NULL,
      `filter` varchar(255) NOT NULL,
      `position` int(16) DEFAULT 0,
      PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;");

$installer->endSetup();