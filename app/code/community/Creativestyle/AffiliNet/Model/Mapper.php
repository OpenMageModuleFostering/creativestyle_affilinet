<?php

class Creativestyle_AffiliNet_Model_Mapper extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('affilinet/mapper');
    }

    public function cleanData($datafeedId)
    {
        if($datafeedId){
            $table = Mage::getSingleton('core/resource')->getTableName('affilinet/mapper');
            $connection = Mage::getSingleton('core/resource')->getConnection('core_write');
            $query = "DELETE FROM " . $table . "
            WHERE
                datafeed_id = " . $datafeedId;

            $connection->query($query);
        }
    }
}