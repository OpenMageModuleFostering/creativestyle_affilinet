<?php

class Creativestyle_AffiliNet_Model_Resource_Mapper extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {
        $this->_init('affilinet/mapper', 'id');
    }
}