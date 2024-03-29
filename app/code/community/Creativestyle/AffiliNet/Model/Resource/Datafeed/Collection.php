<?php

class Creativestyle_AffiliNet_Model_Resource_Datafeed_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('affilinet/datafeed');
    }

    protected function _afterLoad() {
        foreach ($this as $item) {
            $cronStart = str_replace(':', ',', substr($item->getData('cron_start'), -8));
            $item->setData('cron_start', $cronStart);
        }
        return parent::_afterLoad();
    }

}