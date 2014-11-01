<?php
/**
 * 
 * 
 * @author   Ping Lu <p.lu@customized-drinks.com>
 * @package  CDrinks
 * @category CDrinks_AlternateHref
 * @since    30.10.2014
 */ 
class CDrinks_AlternateHref_Helper_Data extends Mage_Core_Helper_Abstract
{
    /**
     * @see http://inchoo.net/magento/implement-rel-alternate-links-in-magento/
     *
     * @param $productId
     * @param $categoryId
     * @param $storeId
     *
     * @return string
     */
    public function rewrittenProductUrl($productId, $categoryId, $storeId)
    {
        $coreUrl = Mage::getModel('core/url_rewrite');
        $idPath = sprintf('product/%d', $productId);
        if ($categoryId) {
            $idPath = sprintf('%s/%d', $idPath, $categoryId);
        }
        $coreUrl->setStoreId($storeId);
        $coreUrl->loadByIdPath($idPath);
        return $coreUrl->getRequestPath();
    }

    /**
     * loads cms/page and retrieves common identifier, tries to match it against the given common identifier
     *  checks for homepage
     *
     * @param $store
     * @param $commonIdentifier
     *
     * @return bool|string
     */
    public function getUrlByCommonIdentifier($store, $commonIdentifier)
    {
        $pageModel = Mage::getModel('cms/page')->getCollection()
            ->addFieldToFilter('alternate_href_common_identifier', array('eq' => $commonIdentifier))
            ->addStoreFilter($store->getStoreId())
            ->addFieldToFilter('is_active', 1)
            ->getFirstItem();

        if (count($pageModel->getData()) > 0) {
            // check for home page
            if ($pageModel->getIdentifier() == Mage::getStoreConfig('web/default/cms_home_page')) {
                // set url to store base url
                $url = $store->getBaseUrl();
            } else {
                $url = Mage::getModel('core/url')->getUrl($pageModel->getIdentifier(), array('_store' => $store->getStoreId()));

                $url = strtok($url, '?');
            }
        } else {
            // set null if not found
            return false;
        }

        return $url;
    }
}