<?php
$installer = $this;

$installer->startSetup();

$installer->run("
    CREATE TABLE IF NOT EXISTS {$this->getTable('creativestyle_affilinet_datafeed_mapper')} (
      `id` int(16) unsigned NOT NULL auto_increment,
      `datafeed_id` int(16) unsigned NOT NULL,
      `title` varchar(255) NOT NULL,
      `preffix` varchar(255) NOT NULL,
      `fieldname` varchar(255) NOT NULL,
      `suffix` varchar(255) NOT NULL,
      `concatenation` TINYINT(1) DEFAULT 0,
      `position` int(16) DEFAULT 0,
      PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;");

$installer->run("
    INSERT INTO {$this->getTable('creativestyle_affilinet_datafeed_mapper')} (`id`, `datafeed_id`, `title`, `preffix`, `fieldname`, `suffix`, `concatenation`, `position`) VALUES
(1, 1, 'ArtNumber ', '', 'sku', '', 0, 1),
(2, 1, 'Category', '', 'category_ids', '', 0, 2),
(3, 1, 'Title', '', 'name', '', 0, 3),
(4, 1, 'DescriptionShort', '', 'short_description', '', 0, 4),
(5, 1, 'Description', '', 'description', '', 0, 5),
(6, 1, 'PricePrefix', '', 'none', '', 0, 6),
(7, 1, 'Price', '', 'price', '', 0, 7),
(8, 1, 'PriceSuffix', '', 'none', '', 0, 8),
(9, 1, 'Currency', '', 'currency', '', 0, 9),
(10, 1, 'ImgUrl', '', 'imgurl', '', 0, 10),
(11, 1, 'DeepLink', '', 'deeplink', '', 0, 11),
(12, 1, 'Keywords', '', 'meta_keyword', '', 0, 12),
(13, 1, 'Manufacturer', '', 'manufacturer', '', 0, 13),
(14, 1, 'ValidFrom', '', 'special_from_date', '', 0, 14),
(15, 1, 'ValidTo', '', 'special_to_date', '', 0, 15),
(16, 1, 'SpecialPrice', '', 'special_price', '', 0, 16),
(17, 1, 'Weight', '', 'weight', '', 0, 17);");

$installer->endSetup();