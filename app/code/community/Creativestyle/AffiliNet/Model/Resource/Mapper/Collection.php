<?php

class Creativestyle_AffiliNet_Model_Resource_Mapper_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('affilinet/mapper');
    }
}