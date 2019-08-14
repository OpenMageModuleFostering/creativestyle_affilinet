<?php

class Creativestyle_AffiliNet_Model_Filter extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('affilinet/filter');
    }

    public function cleanData($datafeedId)
    {
        if($datafeedId){
            $table = Mage::getSingleton('core/resource')->getTableName('affilinet/filter');
            $connection = Mage::getSingleton('core/resource')->getConnection('core_write');
            $query = "DELETE FROM creativestyle_affilinet_datafeed_filter
            WHERE
                datafeed_id = " . $datafeedId;

            $connection->query($query);
        }
    }
}