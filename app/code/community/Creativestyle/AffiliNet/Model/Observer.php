<?php

/**
 * @category   Creativestyle
 * @package    Creativestyle_AffiliNet
 * @copyright  Copyright (c) 2014 creativestyle GmbH
 * @author     Marek Zabrowarny / creativestyle GmbH <support@creativestyle.de>
 */
class Creativestyle_AffiliNet_Model_Observer {

    /**
     * Add order information into affilinet tracking block to render on checkout success pages
     *
     * @param Varien_Event_Observer $observer
     */
    public function setAffilinetTrackingOnOrderSuccessPage($observer) {
        $orderIds = $observer->getEvent()->getOrderIds();
        if (empty($orderIds) || !is_array($orderIds)) {
            return;
        }
        $trackingBlock = Mage::app()->getFrontController()->getAction()->getLayout()->getBlock('affilinet_tracking');
        if ($trackingBlock) {
            $trackingBlock->setOrderIds($orderIds);
        }
        $retargetingBlock = Mage::app()->getFrontController()->getAction()->getLayout()->getBlock('affilinet_retargeting');
        if ($retargetingBlock) {
            $retargetingBlock->setOrderIds($orderIds);
        }
    }

    public function generateCronFeed()
    {
        $feeds = Mage::getModel('affilinet/datafeed')
            ->getCollection()
            ->addFieldToFilter('cron_active', 1);

        if ($feeds) {
            $time = Mage::getModel('core/date')->timestamp(time());
            $break = false;
            foreach($feeds AS $feed){
                $lastPage = $feed->getLastPage();
                $nextGenerate = strtotime($feed->getNextGenerate());
                if(!$nextGenerate){
                    $nextGenerate = strtotime($feed->getCronStart());
                }

                if(!$lastPage && $time > $nextGenerate){
                    if(!$break){
                        $feed->setCronLock(1);
                        $feed->setCronStart($feed->getCronStart());
                        $feed->setId($feed->getId());
                        $feed->save();
                        Mage::getModel('affilinet/datafeed')->prepareCsv($feed->getId(), false, $feed, false);

                        $model = Mage::getModel('affilinet/datafeed')->load($feed->getId());
                        $model->setCronLock(0);
                        $model->save();
                        $break = true;
                    }
                }
            }
        }
        return false;
    }

    public function continueGeneratingFeed()
    { Mage::log('start', 0, 'cron.log');
        $feeds = Mage::getModel('affilinet/datafeed')
            ->getCollection()
            ->addFieldToFilter('last_page', array('gt' => 0))
            ->addFieldToFilter('cron_lock', 0);

        if ($feeds && $feeds->getSize()) {
            $feed = $feeds->getFirstItem();
            if($feed->getId()){
                $feed->setCronLock(1)->setId($feed->getId())->save();
                Mage::getModel('affilinet/datafeed')->prepareCsv($feed->getId(), false, $feed, true);

                $model = Mage::getModel('affilinet/datafeed')->load($feed->getId());
                $model->setCronLock(0);
                $model->save();

                return false;
            }
        }
        return false;
    }

}
