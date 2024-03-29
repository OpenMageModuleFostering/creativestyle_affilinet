<?php

/**
 * @category   Creativestyle
 * @package    Creativestyle_AffiliNet
 * @copyright  Copyright (c) 2014 creativestyle GmbH
 * @author     Marek Zabrowarny / creativestyle GmbH <support@creativestyle.de>
 */
class Creativestyle_AffiliNet_Model_Api_Source_VolumeType extends Creativestyle_AffiliNet_Model_Api_Source_Abstract {

    protected $_volumeTypes = array(
        'TotalViews',
        'TotalClicks',
        'TotalClickOuts',
        'TotalSales',
        'TotalLeads',
        'TotalBonusPayments',
        'TotalCommission',
        'OpenCommission',
        'ViewsRevenue',
        'ClicksRevenue',
        'SalesByRate',
        'LeadsByRate',
        'PublisherActivity'
    );

    public function toOptionArray() {
        if (null === $this->_options) {
            foreach ($this->_volumeTypes as $volumeType) {
                $this->_options[] = array(
                    'value' => $volumeType,
                    'label' => $volumeType
                );
            }
        }
        return $this->_options;
    }

}
