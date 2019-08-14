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
            $connection = Mage::getSingleton('core/resource')->getConnection('core_write');
            $query = "DELETE FROM creativestyle_affilinet_datafeed_mapper
            WHERE
                datafeed_id = " . $datafeedId;

            $connection->query($query);
        }
    }
}