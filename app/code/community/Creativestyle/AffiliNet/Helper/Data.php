<?php

/**
 * @category   Creativestyle
 * @package    Creativestyle_AffiliNet
 * @copyright  Copyright (c) 2014 creativestyle GmbH
 * @author     Marek Zabrowarny / creativestyle GmbH <support@creativestyle.de>
 * @author     Grzegorz Bogusz / creativestyle GmbH <support@creativestyle.de>
 */
class Creativestyle_AffiliNet_Helper_Data extends Mage_Core_Helper_Abstract
{
    private $options = null;
    private $categories = null;

    public function getAllAttributes($withEmpty = true)
    {
        $options = $this->options;
        if (!$options) {

            $collection = Mage::getModel('eav/entity_attribute')->getCollection();
            $collection->setEntityTypeFilter(Mage::getSingleton('eav/config')->getEntityType('catalog_product'));

            foreach($collection AS $attr){
                $code = $attr->getAttributeCode();
                $label = $attr->getFrontendLabel();

                if ($label) {
                    $options[$code] = ucfirst($label) . ' (' . $code . ')';
                }
            }
            $options = array_merge($options, array(
                'qty' => Mage::helper('affilinet')->__('Quantity (qty)'),
                'is_in_stock' => Mage::helper('affilinet')->__('Is in stock (is_in_stock)'),
                'category_ids' => Mage::helper('affilinet')->__('Category (category_ids)'),
                'currency' => Mage::helper('affilinet')->__('Currency (currency)'),
                'deeplink' => Mage::helper('affilinet')->__('DeepLink (deeplink)'),
                'imgurl' => Mage::helper('affilinet')->__('ImgUrl (imgurl)')
            ));

            asort($options);

            $this->options = $options;

        }

        if ($withEmpty) {
            $options = array_merge(array('none' => '---'), $options);
        }

        return $options;
    }

    public function getProductCategory($product, $separator = ' > ') {
        $categoryIds = $product->getCategoryIds();
        $collection = Mage::getResourceModel('catalog/category_collection')
            ->addAttributeToFilter('entity_id', array('in' => $categoryIds))
            ->setOrder('level', 'desc')
            ->load();
        /* @todo check compatibility with flat catalog */
        if ($collection->count()) {
            return $this->getFullCategoryPath($collection->getFirstItem(), $separator);
        }
        return '';
    }

    public function getFullCategoryPath($category, $separator = '|') {
        if (is_object($category) && $category->getId()) {
            $path = explode('/', $category->getPath());

            $collection = Mage::getResourceModel('catalog/category_collection')
                ->addAttributeToSelect('name')
                ->addAttributeToFilter('entity_id', array('in' => $path))
                ->setOrder('level', 'asc');

            $categoryName = array();

            foreach ($collection as $pathCategory) {
                if ((Mage::app()->getStore()->getRootCategoryId() != $pathCategory->getId()) && ($pathCategory->getId() != 1)) {
                    $categoryName[] = $pathCategory->getName();
                }
            }

            $categoryName = implode($separator, $categoryName);
            return $categoryName;
        }
        return '';
    }

    public function prepareCollection($datafeed, $mapper, $filters)
    {
        $table = Mage::getSingleton('core/resource')->getTableName('cataloginventory/stock_item');
        $collection = Mage::getSingleton('catalog/product')
            ->getCollection()
            ->addAttributeToSelect('*')
            ->addAttributeToSelect('image')
            ->addUrlRewrite()
            ->joinTable($table, 'product_id=entity_id', array(
                'qty' => 'qty',
                'min_qty' => 'min_qty',
                'use_config_min_qty' => 'use_config_min_qty',
                'is_qty_decimal' => 'is_qty_decimal',
                'backorders' => 'backorders',
                'use_config_backorders' => 'use_config_backorders',
                'min_sale_qty' => 'min_sale_qty',
                'use_config_min_sale_qty' => 'use_config_min_sale_qty',
                'max_sale_qty' => 'max_sale_qty',
                'use_config_max_sale_qty' => 'use_config_max_sale_qty',
                'is_in_stock' => 'is_in_stock'
            ));
            //->addUrlRewrite();

        if(isset($datafeed['store_id'])){
            $collection->setStore($datafeed['store_id']);
        }

        if($mapper){
            $collection->addAttributeToSelect(array_keys($mapper));
        }

        if($filters){
            foreach($filters AS $field => $filter){
                if($field != 'category_ids'){
                    $collection->addFieldToFilter($field, array('like' => '%' . $filter[0]['filter'] . '%'));
                }
            }
        }

        if(isset($datafeed['filter_active']) && $datafeed['filter_active']){
            $collection->addFieldToFilter('status', 1);
        }

        if(isset($datafeed['filter_stock']) && $datafeed['filter_stock']){
            $collection->addFieldToFilter('is_in_stock', 1);
        }

        return $collection;
    }

    public function prepareCsvRows($products, $checkOneVariation, $storeId, $categoryFilter, $mapper)
    {
        $currency = Mage::app()->getLocale()->currency(Mage::app()->getStore()->getCurrentCurrencyCode())->getSymbol();
        $imgDirPath = Mage::getBaseDir('media') . DS . 'catalog' . DS . 'product' . DS;
        $imgPath = Mage::getBaseUrl('media') . 'catalog/product';

        $rows = array();
        $i = 1;
        foreach($products AS $product){
            if($checkOneVariation){
                $checkParent = Mage::getModel('catalog/product_type_configurable')->getParentIdsByChild($product->getEntityId());
                if(empty($checkParent)){
                    $checkOneVariation = false;
                }
            }

            if(!$checkOneVariation){
                $categories = Mage::helper('affilinet')->getCategories($product->getCategoryIds(), $storeId);
                if(!$categoryFilter || strpos($categories, $categoryFilter));

                foreach($mapper AS $m){
                    if($m[0]['fieldname'] == 'category_ids'){
                        $field = $categories;
                    }elseif($m[0]['fieldname'] == 'none'){
                        $field = '';
                    }elseif($m[0]['fieldname'] == 'currency'){
                        $field = $currency;
                    }elseif($m[0]['fieldname'] == 'deeplink'){
                        $field = $product->getProductUrl();
                    }elseif($m[0]['fieldname'] == 'imgurl'){
                        if($product->getImage() && file_exists($imgDirPath . $product->getImage())){
                            $field = $imgPath . $product->getImage();
                        }else{
                            $field = null;
                        }
                    }else{
                        $type = $product->getResource()->getAttribute($m[0]['fieldname'])->getFrontendInput();

                        if($type == 'select'){
                            $field = $product->getAttributeText($m[0]['fieldname']);
                        }elseif($type == 'multiselect'){
                            $attribute = $product->getData($m[0]['fieldname']);
                            if(strpos(',', $attribute) === false){
                                $field = $product->getAttributeText($m[0]['fieldname']);
                            }else{
                                $field = null;
                                foreach(explode(',', $attribute) AS $_a){
                                    $field .= $product->getAttributeText($_a) . ',';
                                }
                                $field = trim($field, ',');
                            }
                        }else{
                            $field = $product->getData($m[0]['fieldname']);
                        }
                    }

                    if($field){
                        if($m[0]['preffix'] == '' && $m[0]['suffix'] == ''){
                            if(is_numeric($field)){
                                $rows[$i][] = (int)$field;
                            }else{
                                $rows[$i][] = $m[0]['preffix'] . $field . $m[0]['suffix'];
                            }
                        }else{
                            $rows[$i][] = $m[0]['preffix'] . $field . $m[0]['suffix'];
                        }
                    }else{
                        $rows[$i][] = $m[0]['preffix'] . $m[0]['suffix'];
                    }
                }
            }
            $i++;
        }
        return $rows;
    }

    public function getProductCollection($id, $preview, $datafeed, $mapper = null, $filters = null)
    {
        $table = Mage::getSingleton('core/resource')->getTableName('cataloginventory/stock_item');
        $products = Mage::getSingleton('catalog/product')
            ->getCollection()
            //->addAttributeToSelect('*')
            ->joinTable($table, 'product_id=entity_id', array(
                'qty' => 'qty',
                'min_qty' => 'min_qty',
                'use_config_min_qty' => 'use_config_min_qty',
                'is_qty_decimal' => 'is_qty_decimal',
                'backorders' => 'backorders',
                'use_config_backorders' => 'use_config_backorders',
                'min_sale_qty' => 'min_sale_qty',
                'use_config_min_sale_qty' => 'use_config_min_sale_qty',
                'max_sale_qty' => 'max_sale_qty',
                'use_config_max_sale_qty' => 'use_config_max_sale_qty',
                'is_in_stock' => 'is_in_stock'
            ));

        if(isset($datafeed['store_id'])){
            $products->setStore($datafeed['store_id']);
        }

        if($mapper){
            $products->addAttributeToSelect(array_keys($mapper));
        }

        if($filters){
            foreach($filters AS $field => $filter){
                if($field != 'category_ids'){
                    $products->addFieldToFilter($field, array('like' => '%' . $filter[0]['filter'] . '%'));
                }
            }
        }

        if(isset($datafeed['filter_active']) && $datafeed['filter_active']){
            $products->addFieldToFilter('status', 1);
        }

        if(isset($datafeed['filter_stock']) && $datafeed['filter_stock']){
            $products->addFieldToFilter('is_in_stock', 1);
        }

        if($preview){
            $products->setPageSize(20);
        }

        $products->setPageSize(10);

        return $products->getItems();
    }

    public function getCategories($ids, $storeId)
    {
        if($ids){
            $categories = $this->_getCategoriesArray($storeId);

            $catString = '';
            foreach($ids AS $id){
                if(isset($id['category_id']) && $id['category_id'] && isset($categories[$id['category_id']])){
                    $catString .= $categories[$id['category_id']] . ',';
                }
            }
            return trim($catString, ',');
        }
        return false;
    }

    protected function _getCategoriesArray($storeId)
    {
        $categories = $this->categories;
        if(!$this->categories){

            $collection = Mage::getResourceModel('catalog/category_collection')
                //->getCollection()
                ->setStore($storeId)
                ->addAttributeToSelect('name');
            $categories = array();
            foreach ($collection AS $cat) {
                $categories[$cat->getEntityId()] = $cat->getName();
            }

            $this->categories = $categories;
        }

        return $categories;
    }

    public function convertPeriodToDateRange($period) {
        $year = substr($period, 0, 4);
        $span = str_replace($year, '', $period);
        $periodType = substr($span, 0, 1);
        $dateModel = Mage::getModel('core/date');
        switch (strtoupper($periodType)) {
            case 'Q':
                $quarter = (int)substr($span, 1);
                $lastDay = $dateModel->date('t', $dateModel->gmtTimestamp($year . '-' . sprintf('%02d', $quarter * 3) . '-01'));
                $endDate = $year . '-' . sprintf('%02d', $quarter * 3) . '-' . sprintf('%02d', $lastDay);
                if ($dateModel->gmtTimestamp($endDate) > $dateModel->gmtTimestamp()) {
                    $endDate = $dateModel->date('Y-m-d');
                }
                $dateRange = array(
                    'start_date' => $year . '-' . sprintf('%02d', ($quarter - 1) * 3 + 1) . '-01',
                    'end_date' => $endDate
                );
                return new Varien_Object($dateRange);
            case 'W':
                $week = substr($span, 1);
                $endTimestamp = $dateModel->gmtTimestamp($year . 'W' . $week . '7');
                if ($endTimestamp > $dateModel->gmtTimestamp()) {
                    $endTimestamp = $dateModel->gmtTimestamp();
                }
                $dateRange = array(
                    'start_date' => $dateModel->date('Y-m-d', $dateModel->gmtTimestamp($year . 'W' . $week)),
                    'end_date' => $dateModel->date('Y-m-d', $endTimestamp)
                );
                return new Varien_Object($dateRange);
            default:
                $month = (int)$span;
                $lastDay = $dateModel->date('t', $dateModel->gmtTimestamp($year . '-' . sprintf('%02d', $month) . '-01'));
                $endDate = $year . '-' . sprintf('%02d', $month) . '-' . sprintf('%02d', $lastDay);
                if ($dateModel->gmtTimestamp($endDate) > $dateModel->gmtTimestamp()) {
                    $endDate = $dateModel->date('Y-m-d');
                }
                $dateRange = array(
                    'start_date' => $year . '-' . sprintf('%02d', $month) . '-01',
                    'end_date' => $endDate
                );
                return new Varien_Object($dateRange);
        }
        return new Varien_Object();
    }

    public function prettifyXml($input, $encodeHtmlEntities = false) {
        try {
            $xmlObj = new SimpleXMLElement($input);
            $level = 4;
            $indent = 0;
            $pretty = array();

            $xml = explode("\n", preg_replace('/>\s*</', ">\n<", $xmlObj->asXML()));

            if (count($xml) && preg_match('/^<\?\s*xml/', $xml[0])) {
                $pretty[] = array_shift($xml);
            }

            foreach ($xml as $el) {
                if (preg_match('/^<([\w])+[^>\/]*>$/U', $el)) {
                    $pretty[] = str_repeat(' ', $indent) . $el;
                    $indent += $level;
                } else {
                    if (preg_match('/^<\/.+>$/', $el)) {
                        $indent -= $level;
                    }
                    if ($indent < 0) {
                        $indent += $level;
                    }
                    $pretty[] = str_repeat(' ', $indent) . $el;
                }
            }
            $xml = implode("\n", $pretty);
            return $encodeHtmlEntities ? htmlspecialchars($xml, ENT_COMPAT, 'UTF-8') : $xml;
        } catch (Exception $e) {
            return $input;
        }
    }

    public function getStoreSwitcherFirstId() {
        $block = Mage::getSingleton('core/layout')->getBlock('store_switcher');
        if ($block) {
            if ($websites = $block->getWebsites()) {
                foreach ($websites as $website) {
                    foreach ($website->getGroups() as $group) {
                        foreach ($block->getStores($group) as $store) {
                            return $store->getId();
                        }
                    }
                }
            }
        }
        return null;
    }
}
