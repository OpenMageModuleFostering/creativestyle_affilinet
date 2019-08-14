<?php

class Creativestyle_AffiliNet_Model_Datafeed extends Mage_Core_Model_Abstract
{
    private $encoding = null;
    private $delimiter = null;
    private $escaping = null;
    private $id = null;
    private $previewData = null;
    private $mapper = null;
    private $conn = null;

    protected function _sanitize($data) {
        $data = strip_tags($data);
        $data = str_replace(array("\t", "\n", "\r"), '', $data);
        return $data;
    }

    public function _construct()
    {
        parent::_construct();
        $this->_init('affilinet/datafeed');
    }


    public function prepareCsv($id, $preview, $datafeed, $cron = false)
    {
        set_time_limit(0);
        ini_set('memory_limit','2048M');
        ini_set('session.gc_maxlifetime', 86400);
        if(!$cron){
            ignore_user_abort(true);
        }
        $path = Mage::getBaseDir('media') . DS . 'creativestyle' . DS . 'affilinet' . DS . 'datafeed' . DS . 'temp' . DS;
        $file = $cron ? $datafeed->getCronFile() : $this->cleanCsvFile($path, $datafeed);
        $this->id = $id;
        $connection = Mage::getSingleton('core/resource')->getConnection('core_read');
        $this->conn = $connection;
        $this->preview = $preview;

        if ($file) {
            $this->prepareConfigValues($datafeed);

            list($mapper, $filters) = $this->getMapperFilter($id);
            $this->mapper = $mapper;

            //title
            $columnTitle = array();
            if ($datafeed['column_title']) {
                foreach ($mapper AS $m) {
                    $columnTitle[0][] = $m[0]['title'];
                }
                if(!$preview){
                    $this->exportDataToFile($path, $file, $id, $columnTitle[0]);
                }
            }

            $categoryFilter = false;
            if (isset($filters['category_ids']) && $filters['category_ids']) {
                $categoryFilter = $filters['category_ids'];
            }

            $checkOneVariation = false;
            if ($datafeed['one_variation']) {
                $checkOneVariation = true;
            }

            $data = array(
                'id' => $id,
                'path' => $path,
                'file' => $file,
                'preview' => $preview,
                'categoryFilter' => $categoryFilter,
                'checkOneVariation' => $checkOneVariation,
                'store_id' => $datafeed['store_id'],
                'currency' => Mage::app()->getLocale()->currency(Mage::app()->getStore()->getCurrentCurrencyCode())->getSymbol(),
                'imgDirPath' => Mage::getBaseDir('media') . DS . 'catalog' . DS . 'product' . DS,
                'imgPath' => Mage::getBaseUrl('media') . 'catalog/product'
            );

            Mage::app()->setCurrentStore($datafeed['store_id']);
            $products = Mage::helper('affilinet')->prepareCollection($datafeed, $mapper, $filters);

            if($preview){
                $this->previewData = null;
                $collection = clone $products;
                $collection->getSelect()->group('e.entity_id');
                //$collection->setPageSize(20);
                //$collection->setCurPage(1);
                //$collection->load();
                $collection->getSelect()->limit(20);
                foreach ($collection as $item) {
                    $data['row'] = $item;
                    call_user_func_array(array($this, 'prepareCsvFile'), array($data));
                }
                return array_merge($columnTitle, $this->previewData);
            }else{
                $config = Mage::getStoreConfig('affilinet/datafeed');
                $page = $cron ? (int)$datafeed->getLastPage() : 1;
                $pages = $cron ? ((int)$datafeed->getLastPage() + $config['pages'] - 1) : $config['pages'];
                $lastPage = null;
                $break = false;

                while ($break !== true) {
                    $collection = clone $products;
                    $collection->getSelect()->group('e.entity_id');
                    //$collection->setPageSize($config['coll_size']);
                    if (is_null($lastPage)) {
                        $lastPage = ceil($products->getSize() / $config['coll_size']); //->getLastPageNumber();
                    }
                    //$collection->setCurPage($page);
                    //$collection->load();
                    $collection->getSelect()->limit($config['coll_size'], ($page-1)*$config['coll_size']);
                    $page ++;
                    foreach ($collection as $item) {
                        $data['row'] = $item;
                        call_user_func_array(array($this, 'prepareCsvFile'), array($data));
                    }
                    if ($lastPage <= $page || $pages == $page) {
                        $break = true;
                        if($lastPage <= $page){
                            $this->finishFileGenerating($id, $path, $file);
                        }else{
                            $this->savePageData($id, $page, $lastPage);
                        }
                    }
                }
            }
        }
    }

    public function prepareCsvFile($data)
    {
        $connection = $this->conn;

        $product = $data['row'];

        $checkOneVariation = $data['checkOneVariation'];
        if($checkOneVariation){
            $table = Mage::getSingleton('core/resource')->getTableName('catalog/product_super_link');
            $query = '
            SELECT
                parent_id
            FROM ' . $table . '
            WHERE product_id IN(' . $product->getEntityId() . ')';
            $checkParent = $connection->fetchAll($query);

            if(empty($checkParent)){
                $checkOneVariation = false;
            }
        }

        if(!$checkOneVariation){
            $table = Mage::getSingleton('core/resource')->getTableName('catalog/category_product');
            $query = '
            SELECT
                category_id
            FROM ' . $table . '
            WHERE product_id = ' . $product->getEntityId();

            $categories = Mage::helper('affilinet')->getCategories($connection->fetchAll($query), $data['store_id']);
            $helper = Mage::helper('core');
            $rows = array();
            foreach($this->mapper AS $m){
                if($m[0]['fieldname'] == 'category_ids'){
                    $field = $categories;
                }elseif($m[0]['fieldname'] == 'none'){
                    $field = '';
                }elseif($m[0]['fieldname'] == 'currency'){
                    $field = $data['currency'];
                }elseif($m[0]['fieldname'] == 'deeplink'){
                    $field = $product->getProductUrl();
                }elseif($m[0]['fieldname'] == 'imgurl'){
                    if($product->getImage() && file_exists($data['imgDirPath'] . $product->getImage())){
                        $field = $data['imgPath'] . $product->getImage();
                    }else{
                        $field = null;
                    }
                }else{
                    $type = 'text';
                    $attributeInstance = $product->getResource()->getAttribute($m[0]['fieldname']);
                    if($attributeInstance){
                        $type = $product->getResource()->getAttribute($m[0]['fieldname'])->getFrontendInput();
                    }

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
                        if(isset($args['row'][$m[0]['fieldname']])){
                            $field = $args['row'][$m[0]['fieldname']];
                        }elseif($product->getData($m[0]['fieldname'])){
                            $field = $product->getData($m[0]['fieldname']);
                        }else{
                            $field = null;
                        }
                    }
                }

                if($field){
                    $field = $this->_sanitize($field);
                    if($m[0]['preffix'] == '' && $m[0]['suffix'] == ''){
                        if(strpos($m[0]['fieldname'], 'price') !== false){
                            $rows[] = $helper->currency($field, false, false);
                        }else{
                            if(is_numeric($field)){
                                $rows[] = (int)$field;
                            }else{
                                $rows[] = $m[0]['preffix'] . $field . $m[0]['suffix'];
                            }
                        }

                    }else{
                        $rows[] = $m[0]['preffix'] . $field . $m[0]['suffix'];
                    }
                }else{
                    $rows[] = $m[0]['preffix'] . $m[0]['suffix'];
                }
            }

            if($data['preview']){
                $previewData = $this->previewData;
                $previewData[] = $rows;
                $this->previewData = $previewData;
            }else{
                $this->exportDataToFile($data['path'], $data['file'], $this->id, $rows);
            }
        }
    }

    public function finishFileGenerating($id, $path, $file)
    {
        $model = Mage::getModel('affilinet/datafeed')->load($id);
        if($model){
            if((file_exists($path . $file))){
                $newPath = str_replace(DS . 'temp', '', $path);
                rename($path . $file, $newPath . $file);
                if((file_exists($path . $file))){
                    unlink($path . $file);
                }
            }

            $model->setLastPage(0);
            $model->setCronLock(0);
            $model->setId($model->getId());
            $model->save();
        }
    }

    public function savePageData($id, $lastPage, $collSize)
    {
        $model = Mage::getModel('affilinet/datafeed')->load($id);
        if($model){
            $model->setLastPage($lastPage+1);
            $model->setCollSize($collSize);
            $model->setId($model->getId());
            $model->save();
        }
    }

    private function prepareConfigValues($datafeed)
    {
        $this->encoding = (isset($datafeed['encoding']) && $datafeed['encoding'] && $datafeed['encoding'] != 'UTF-8') ? $datafeed['encoding'] : false;
        $this->delimiter = (isset($datafeed['delimiter']) && $datafeed['delimiter']) ? $datafeed['delimiter'] : ';';
        $escaping = (isset($datafeed['escaping']) && $datafeed['escaping'] && strlen($datafeed['escaping']) > 1) ? $datafeed['escaping'] : '""';
        $this->escaping = substr($escaping, 0, strlen($escaping) / 2);
    }

    private function getMapperFilter($id)
    {
        $connection = Mage::getSingleton('core/resource')->getConnection('core_read');

        //mapper
        $table = Mage::getSingleton('core/resource')->getTableName('affilinet/mapper');
        $sql = "SELECT
                m.fieldname AS id,
                m.title AS title,
                m.preffix AS preffix,
                m.fieldname AS fieldname,
                m.suffix AS suffix,
                m.concatenation AS concatenation
            FROM
                " . $table . " AS m
            WHERE
                m.datafeed_id = :datafeed_id";
        $query = $connection->prepare($sql);
        $query->bindParam(':datafeed_id', $id);
        $query->execute();
        $mapper = $query->fetchAll(PDO::FETCH_GROUP);

        //filter
        $table = Mage::getSingleton('core/resource')->getTableName('affilinet/filter');
        $sql = "SELECT
                f.fieldname AS fieldname,
                f.filter AS filter
            FROM
                " . $table . " AS f
            WHERE
                f.datafeed_id = :datafeed_id";
        $query = $connection->prepare($sql);
        $query->bindParam(':datafeed_id', $id);
        $query->execute();
        $filters = $query->fetchAll(PDO::FETCH_GROUP);

        return array($mapper, $filters);
    }

    private function cleanCsvFile($path, $datafeed)
    {
        $file = (isset($datafeed['cron_file']) && $datafeed['cron_file']) ? $datafeed['cron_file'] :
            ((isset($datafeed['name']) && $datafeed['name']) ? $datafeed['name'] : null);

        if ($file) {
            if (file_exists($path . $file)) {
                unlink($path. $file);
            }
            touch($path . $file);
            return $file;
        }
        return false;
    }

    private function exportDataToFile($path, $file, $id, $csv)
    {
        $fp = fopen($path . $file, 'a');
        if ($fp && $csv) {
            array_walk($csv, array('self', 'changeEncoding'), $this->encoding);
            fputcsv($fp, $csv, $this->delimiter, $this->escaping);

            fclose($fp);
            $this->setNextGenerateTime($id);
        }
    }

    private function setNextGenerateTime($id)
    {
        $time = Mage::getModel('core/date')->timestamp(time());
        $feed = Mage::getModel('affilinet/datafeed')->load($id);

        $_cronStart = explode(',', $feed->getCronStart());
        if (count($_cronStart) == 3) {
            $cronStart = mktime($_cronStart[0], $_cronStart[1], $_cronStart[2]);

            $diff = $time - $cronStart;
            if($diff > 0 && $feed->getCronRepeat()){
                $d = ceil(($diff / ($feed->getCronRepeat() * 3600)));
                if($d){
                    $feed->setNextGenerate($cronStart + $d * ($feed->getCronRepeat() * 3600));
                }
            }else{
                $feed->setNextGenerate($cronStart);
            }
            $feed->save();
        }
    }

    public function changeEncoding(&$string, $key, $encoding)
    {
        mb_convert_encoding($string, $encoding);
    }

    public function sendFeed($id)
    {
        $datafeed = Mage::getModel('affilinet/datafeed')->load($id);
        if ($datafeed->getId() == $id) {
            $emailTemplate = Mage::getModel('core/email_template')->loadDefault('affilinet_feed');
            $emailTemplate->setTemplateSubject(Mage::helper('affilinet')->__('New Magento product datafeed'));

            $logo = $datafeed->getCompanyLogo();
            $path = Mage::getBaseDir('media') . DS . 'creativestyle' . DS . 'affilinet' . DS . 'datafeed' . DS;
            $defaultPath = Mage::getBaseDir('media') . DS . 'affilinet' . DS . 'logo' . DS . 'default' . DS;
            if (file_exists($path . $logo)) {
                $logo = Mage::getBaseUrl('media') . '/creativestyle/affilinet/datafeed/' . $logo;
            } else {
                if(file_exists($defaultPath . $logo)){
                    $logo = Mage::getBaseUrl('media') . '/affilinet/logo/default/' . $logo;
                }else{
                    $logo = '';
                }
            }

            if(!$logo && $datafeed->getCompanyLogo() && file_exists(Mage::getBaseDir('media') . DS . $datafeed->getCompanyLogo())){
                $logo = Mage::getBaseUrl('media') . $datafeed->getCompanyLogo();
            }

            $_hour = explode(',', $datafeed->getCronStart());
            $hour = implode(':', $_hour);


            $data = array(
                'program_id' => Mage::getStoreConfig('affilinet/general/program_id'),
                'feed_link' => Mage::getBaseUrl('media') . 'creativestyle/affilinet/datafeed/' . $datafeed->getCronFile(),
                'hour' => $hour,
                'repeat' => $datafeed->getCronRepeat(),
                'logo' => $logo,
                'affilinet_email' => Mage::getStoreConfig('contacts/email/recipient_email')
            );

            $emailTemplate->setSenderName($data['affilinet_email']);
            $emailTemplate->setSenderEmail($data['affilinet_email']);

            $sendEmail = ($datafeed->getTestemail()) ? ($datafeed->getTestemail()) : 'productdata@affili.net';

            try {
                $emailTemplate->send($sendEmail, '', $data);
            } catch (Exception $e) {
                die(var_dump($e->getMessage()));
            }
        }
    }

    public function stopGenerating($id)
    {
        $datafeed = Mage::getModel('affilinet/datafeed')->load($id);

        if($datafeed && $datafeed->getId()){
            $datafeed->setLastPage(0);
            $datafeed->setCronLock(0);
            $cronFile = $datafeed->getCronFile();

            $datafeed->save();

            $filepath = $path = Mage::getBaseDir('media') . DS . 'creativestyle' . DS . 'affilinet' . DS . 'datafeed' . DS;
            if(file_exists($filepath . $cronFile)){
                unlink($filepath . $cronFile);
            }
            if(file_exists($filepath . 'temp' . DS . $cronFile)){
                unlink($filepath . 'temp' . DS . $cronFile);
            }
        }
    }

    public function checkDir($path)
    {
        if($path){
            if(file_exists($path)){
                return true;
            }
            mkdir($path, 0777, true);
            if(file_exists($path)){
                return true;
            }
        }
        return false;
    }

    protected function _afterLoad() {
        $_ = $this->getData('cron_start');
        if(strpos($_, ':') === false && strpos($_, ',') === false){
            $_ = '00:00:00';
        }
        $cronStart = str_replace(':', ',', substr($_, -8));
        $this->setData('cron_start', $cronStart);
        return parent::_afterLoad();
    }

    protected function _beforeSave() {
        $_ = $this->getData('cron_start');
        $_cronStart = explode(',', $_);

        $cronStart = Mage::getModel('core/date')->timestamp(mktime($_cronStart[0], $_cronStart[1], $_cronStart[2]));
        $this->setData('cron_start', Mage::getModel('core/date')->gmtDate('Y-m-d H:i:s', $cronStart));
        return parent::_beforeSave();
    }

}
