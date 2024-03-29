<?php

/**
 * @category   Creativestyle
 * @package    Creativestyle_AffiliNet
 * @copyright  Copyright (c) 2014 creativestyle GmbH
 * @author     Marek Zabrowarny / creativestyle GmbH <support@creativestyle.de>
 */
class Creativestyle_AffiliNet_Model_Api_Source_Period_Type extends Creativestyle_AffiliNet_Model_Api_Source_Abstract {

    const QUARTER   = 'quarter';
    const MONTH     = 'month';
    const WEEK      = 'week';
    const TIME_SPAN = 'time_span';

    public function toOptionArray() {
        if (null === $this->_options) {
            $this->_options = array(
                array(
                    'value' => self::MONTH,
                    'label' => Mage::helper('affilinet')->__('Month')
                ),
                array(
                    'value' => self::QUARTER,
                    'label' => Mage::helper('affilinet')->__('Quarter')
                ),
                array(
                    'value' => self::WEEK,
                    'label' => Mage::helper('affilinet')->__('Week')
                ),
                array(
                    'value' => self::TIME_SPAN,
                    'label' => Mage::helper('affilinet')->__('Time span')
                )
            );
        }
        return $this->_options;
    }

    public static function getAllPeriodTypes() {
        return array(self::QUARTER, self::MONTH, self::WEEK, self::TIME_SPAN);
    }

}
