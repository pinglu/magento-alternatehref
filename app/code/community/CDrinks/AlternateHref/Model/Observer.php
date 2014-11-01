<?php
/**
 * 
 * 
 * @author   Ping Lu <p.lu@customized-drinks.com>
 * @package  CDrinks
 * @category CDrinks_AlternateHref
 * @since    30.10.2014
 */

class CDrinks_AlternateHref_Model_Observer
{
    /**
     * original implementation see: http://inchoo.net/magento/implement-rel-alternate-links-in-magento/
     *
     * extended to accommodate for cms pages that have speaking urls (empfehlungen (DE), recommendations (EN))
     *
     * @return $this
     */
    public function alternateLinks()
    {
        $headBlock = Mage::app()->getLayout()->getBlock('head');

        $stores = Mage::app()->getStores();
        $prod = Mage::registry('current_product');
        $category = Mage::registry('current_category');

        if($headBlock) {
            $commonIdentifier = Mage::getSingleton('cms/page')->getAlternateHrefCommonIdentifier();

            foreach ($stores as $store)
            {
                if ($prod) {
                    $category ? $categId=$category->getId() : $categId = null;
                    $url = $store->getBaseUrl() . Mage::helper('cdrinks_alternatehref')->rewrittenProductUrl($prod->getId(), $categId, $store->getId());
                } else {
                    if (empty($commonIdentifier) === true) {
                        continue;
                    } else {
                        // we're retrieving all cms pages and looking for similarities
                        $url = Mage::helper('cdrinks_alternatehref')->getUrlByCommonIdentifier($store, $commonIdentifier);

                        if ($url === false) {
                            continue;
                        }
                    }
                }

                switch($store->getCode()) {
                    case 'de': $langFlag = 'de';
                        break;
                    case 'uk': $langFlag = 'en-uk';
                        break;
                    case 'eu':
                    default:
                        $langFlag = 'en';
                        break;
                }

                $headBlock->addLinkRel('alternate"' . ' hreflang="' . $langFlag, $url);
            }
        }
        return $this;
    }
}