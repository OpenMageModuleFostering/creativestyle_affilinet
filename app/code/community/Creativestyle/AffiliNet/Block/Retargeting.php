<?php

/**
 * @category   Creativestyle
 * @package    Creativestyle_AffiliNet
 * @copyright  Copyright (c) 2014 creativestyle GmbH
 * @author     Marek Zabrowarny / creativestyle GmbH <support@creativestyle.de>
 */
class Creativestyle_AffiliNet_Block_Retargeting extends Creativestyle_AffiliNet_Block_Abstract {

    const PAGE_TYPE_HOME            = 1;
    const PAGE_TYPE_CATEGORY        = 2;
    const PAGE_TYPE_PRODUCT         = 3;
    const PAGE_TYPE_CART            = 4;
    const PAGE_TYPE_SEARCH          = 5;
    const PAGE_TYPE_SEARCH_ADVANCED = 6;
    const PAGE_TYPE_CHECKOUT        = 7;
    const PAGE_TYPE_CUSTOMER        = 8;
    const PAGE_TYPE_CMS             = 9;

    const URL_TRACKING_QUERY        = 'utm_source=affilinet&utm_medium=cpc&utm_content=content&utm_campaign=affilinet';

    protected function _getPageType() {
        if ($this->_getOrder()) {
            return self::PAGE_TYPE_CHECKOUT;
        }

        $cmsPageIdentifier = Mage::getSingleton('cms/page')->getIdentifier();
        if ($cmsPageIdentifier) {
            switch ($cmsPageIdentifier) {
                case Mage::getStoreConfig(Mage_Cms_Helper_Page::XML_PATH_HOME_PAGE):
                    return self::PAGE_TYPE_HOME;
                default:
                    return self::PAGE_TYPE_CMS;
            }
        }

        $module = strtolower($this->getRequest()->getModuleName());
        $controller = strtolower($this->getRequest()->getControllerName());
        $action = strtolower($this->getRequest()->getActionName());

        switch ($module) {
            case 'catalog':
                switch ($controller) {
                    case 'product':
                        if ($action == 'view') return self::PAGE_TYPE_PRODUCT;
                        break;
                    case 'category':
                        if ($action == 'view') return self::PAGE_TYPE_CATEGORY;
                        break;
                }
                break;
            case 'catalogsearch':
                switch ($controller) {
                    case 'result':
                        return self::PAGE_TYPE_SEARCH;
                    case 'advanced':
                        if ($action == 'result') return self::PAGE_TYPE_SEARCH_ADVANCED;
                        break;
                }
                break;
            case 'checkout':
                switch ($controller) {
                    case 'cart':
                        return self::PAGE_TYPE_CART;
                    case 'onepage':
                    case 'multishipping':
                        switch ($action) {
                            case 'index':
                                return self::PAGE_TYPE_CUSTOMER;
                            case 'success':
                                return self::PAGE_TYPE_CHECKOUT;
                        }
                        break;
                }
                break;
            case 'customer':
                return self::PAGE_TYPE_CUSTOMER;
        }

        return null;
    }

    protected function _prepareUrl($url) {
        $separator = (parse_url($url, PHP_URL_QUERY) == null) ? '?' : '&';
        return $url . $separator . self::URL_TRACKING_QUERY;
    }

    protected function _importSearchData($advancedSearch = false) {
        $query = Mage::helper('catalogsearch')->getQuery();
        if (is_object($query) && $query->getQueryText()) {
            $this->setSearchQuery(rawurlencode($query->getQueryText()));
        }

        if ($advancedSearch) {
            $productCollection = Mage::getSingleton('catalogsearch/advanced')->getProductCollection();
        } else {
            $productCollection = Mage::getSingleton('catalogsearch/layer')->getProductCollection();
        }
        if ($productCollection->count()) {
            $product = $productCollection->getFirstItem();
            $product->load($this->_getProductIdentifier());
            $this->setSearchResult(rawurlencode($product->getData($this->_getProductIdentifier())));
        }
    }

    protected function _importCategoryData() {
        $category = Mage::registry('current_category');
        if (is_object($category) && $category->getId()) {
            $this->setCategory($this->helper('affilinet')->getFullCategoryPath($category));
            $this->setCategoryUrl(rawurlencode($this->_prepareUrl($category->getUrl())));

            // optional data
            $retargetingOptions = $this->_getConfig()->getCategoryRetargetingOptions();
            if (in_array(Creativestyle_AffiliNet_Model_System_Config_Source_Retargeting_Category::CATEGORY_IMAGE_URL, $retargetingOptions)) {
                if ($category->getImageUrl()) {
                    $this->setCategoryImgUrl(rawurlencode($category->getImageUrl()));
                }
            }
        }
    }

    protected function _importProductData() {
        $product = Mage::registry('current_product');
        if (is_object($product) && $product->getId()) {
            $manufacturerAttribute = $this->_getManufacturerAttribute();
            $productPrice = Mage::helper('tax')->getPrice($product, $product->getFinalPrice(), true);
            $productOriginalPrice = Mage::helper('tax')->getPrice($product, $product->getPrice(), true);
            $productStock = (int)Mage::getModel('cataloginventory/stock_item')->loadByProduct($product)->getQty();
            $this->setProductId(trim($product->getData($this->_getProductIdentifier())));
            $this->setProductName($product->getName());
            $this->setProductPrice($this->_formatAmount($productPrice));
            if ($product->getCategoryId()) {
                $this->setProductCategory($this->helper('affilinet')->getFullCategoryPath(Mage::getModel('catalog/category')->load($product->getCategoryId())));
            } else {
                $this->setProductCategory($this->helper('affilinet')->getProductCategory($product, '|'));
            }
            if ($product->getData($manufacturerAttribute)) {
                $manufacturerAttributeModel = $product->getResource()->getAttribute($manufacturerAttribute);
                if (is_callable(array($manufacturerAttributeModel, 'getFrontend'))) {
                    $this->setProductManufacturer($manufacturerAttributeModel->getFrontend()->getValue($product));
                } else {
                    $this->setProductManufacturer($product->getData($manufacturerAttribute));
                }
            }
            $this->setProductOnStock($product->getTypeInstance(true)->isComposite() ? 1 : ($productStock ? 1 : 0));
            $this->setProductUrl(rawurlencode($this->_prepareUrl($product->getProductUrl(false))));
            if ($product->getImage() && $product->getImage() != 'no_selection') {
                $imageUrl = Mage::getBaseUrl('media') . 'catalog/product' . str_replace(DS, '/', $product->getImage());
                $this->setProductImageUrl(rawurlencode($imageUrl));
            }
            $this->setCurrency(Mage::app()->getBaseCurrencyCode());

            // optional data
            $retargetingOptions = $this->_getConfig()->getProductRetargetingOptions();
            if (in_array(Creativestyle_AffiliNet_Model_System_Config_Source_Retargeting_Product::ORIGINAL_PRICE, $retargetingOptions)) {
                if ($productOriginalPrice != $productPrice) {
                    $this->setProductOldPrice($this->_formatAmount($productOriginalPrice));
                }
            }
            if (in_array(Creativestyle_AffiliNet_Model_System_Config_Source_Retargeting_Product::PRODUCT_RATING, $retargetingOptions)) {
                if ($product->getRatingSummary()->getRatingSummary()) {
                    $this->setProductRating(round($product->getRatingSummary()->getRatingSummary() / 10));
                }
            }
            if (in_array(Creativestyle_AffiliNet_Model_System_Config_Source_Retargeting_Product::PRICE_LABEL, $retargetingOptions)) {
                if ($productOriginalPrice != $productPrice) {
                    $this->setProductOnSale(1);
                } else {
                    $this->setProductOnSale(0);
                }
            }
            if (in_array(Creativestyle_AffiliNet_Model_System_Config_Source_Retargeting_Product::PRODUCT_LABEL, $retargetingOptions)) {}
        }
    }

    protected function _importCartData() {
        $quote = Mage::getSingleton('checkout/session')->getQuote();
        if (is_object($quote) && $quote->getId()) {
            $items = array();
            $qtys = array();
            $prices = array();

            foreach ($quote->getAllVisibleItems() as $item) {
                $product = Mage::getModel('catalog/product')->load($item->getProductId());
                $productPrice = $this->_formatAmount($item->getBasePriceInclTax(), 2, '.', '');
                $productOriginalPrice = Mage::helper('tax')->getPrice($product, $product->getPrice(), true);
                $items[] = trim($product->getData($this->_getProductIdentifier()));
                $qtys[] = round($item->getQty());
                $prices[] = $productPrice;
                $oldPrices[] = ((float)$productOriginalPrice != (float)$productPrice ? $productOriginalPrice : '');
                unset($product);
            }

            $items = implode('|', $items);
            $qtys = implode('|', $qtys);
            $prices = implode('|', $prices);
            $oldPrices = implode('|', $oldPrices);

            $this->setProductId($items);
            $this->setProductPrice($prices);
            $this->setCurrency($quote->getBaseCurrencyCode());

            // optional data
            $retargetingOptions = $this->_getConfig()->getCartRetargetingOptions();
            if (in_array(Creativestyle_AffiliNet_Model_System_Config_Source_Retargeting_Cart::ORIGINAL_PRICE, $retargetingOptions)) {
                $this->setProductOldPrice($oldPrices);
            }
            if (in_array(Creativestyle_AffiliNet_Model_System_Config_Source_Retargeting_Cart::PRODUCTS_QUANTITY, $retargetingOptions)) {
                $this->setProductQuantity($qtys);
            }
        }
    }

    protected function _importCheckoutData() {
        if ($this->_getOrder()) {
            $items = array();
            $qtys = array();
            $prices = array();

            foreach ($this->_getOrder()->getAllVisibleItems() as $item) {
                $product = Mage::getModel('catalog/product')->load($item->getProductId());
                $items[] = trim($product->getData($this->_getProductIdentifier()));
                $qtys[] = round($item->getQtyOrdered());
                $prices[] = $this->_formatAmount($item->getBasePriceInclTax(), 2, '.', '');
                unset($product);
            }

            $items = implode('|', $items);
            $qtys = implode('|', $qtys);
            $prices = implode('|', $prices);

            $this->setProductId($items);

            // optional data
            $retargetingOptions = $this->_getConfig()->getCheckoutRetargetingOptions();
            if (in_array(Creativestyle_AffiliNet_Model_System_Config_Source_Retargeting_Checkout::INDIVIDUAL_PRICE, $retargetingOptions)) {
                $this->setProductPrice($prices);
            }
            if (in_array(Creativestyle_AffiliNet_Model_System_Config_Source_Retargeting_Checkout::PRODUCTS_QUANTITY, $retargetingOptions)) {
                $this->setProductQuantity($qtys);
            }
        }
    }

    protected function _prepareView() {
        switch ($this->_getPageType()) {
            case self::PAGE_TYPE_HOME:
                $this->setTemplate('creativestyle/affilinet/retargeting/landing_page.phtml');
                break;
            case self::PAGE_TYPE_CATEGORY:
                $this->_importCategoryData();
                $this->setTemplate('creativestyle/affilinet/retargeting/category.phtml');
                break;
            case self::PAGE_TYPE_PRODUCT:
                $this->_importProductData();
                $this->setTemplate('creativestyle/affilinet/retargeting/product.phtml');
                break;
            case self::PAGE_TYPE_CART:
                $this->_importCartData();
                $this->setTemplate('creativestyle/affilinet/retargeting/cart.phtml');
                break;
            case self::PAGE_TYPE_SEARCH:
                $this->_importSearchData();
            case self::PAGE_TYPE_SEARCH_ADVANCED:
                $this->_importSearchData(true);
                $this->setTemplate('creativestyle/affilinet/retargeting/search.phtml');
                break;
            case self::PAGE_TYPE_CHECKOUT:
                $this->_importCheckoutData();
                $this->setTemplate('creativestyle/affilinet/retargeting/checkout.phtml');
                break;
            case self::PAGE_TYPE_CUSTOMER:
                $this->setTemplate('creativestyle/affilinet/retargeting/customer_data.phtml');
                break;
        }
    }

    /**
     * Render affilinet retargeting scripts
     *
     * @return string
     */
    protected function _toHtml() {
        if (!$this->_getConfig()->isRetargetingActive()) {
            return '';
        }
        $this->_prepareView();
        return parent::_toHtml();
    }

}
