<?php

/**
 * @category   Creativestyle
 * @package    Creativestyle_AffiliNet
 * @copyright  Copyright (c) 2014 creativestyle GmbH
 * @author     Marek Zabrowarny / creativestyle GmbH <support@creativestyle.de>
 */
class Creativestyle_AffiliNet_Block_Adminhtml_Report_Order_Renderer_BasketInfo extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract {

    public function render(Varien_Object $row) {
        $basketInfo = $row->getBasketInfo();
        if (is_object($basketInfo)) {
            $basketId = $basketInfo->getBasketId();
            $openBasketItems = $basketInfo->getOpenBasketItemCount();
            $totalBasketItems = $basketInfo->getTotalBasketItemCount();
            if ($basketId) {
                return sprintf('<a class="pointer affilinetBasketLink" id="affilinetBasketLink-%s">%s / %s</a>', $basketId, (int)$openBasketItems, (int)$totalBasketItems);
            }
        }
        return null;
    }

}
