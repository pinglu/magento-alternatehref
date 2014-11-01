<?php
/**
 * Overloaded to add our common identifier
 *
 * @category    CDrinks
 * @package     CDrinks_AlternateHref
 * @author      Ping Lu <p.lu@customized-drinks.com>
 * @since       31.10.2014
 */

class CDrinks_AlternateHref_Block_Cms_Page_Edit_Tab_Main
    extends Mage_Adminhtml_Block_Cms_Page_Edit_Tab_Main
{
    protected function _prepareForm()
    {
        /* @var $model Mage_Cms_Model_Page */
        $model = Mage::registry('cms_page');

        /*
         * Checking if user have permissions to save information
         */
        if ($this->_isAllowedAction('save')) {
            $isElementDisabled = false;
        } else {
            $isElementDisabled = true;
        }


        $form = new Varien_Data_Form();

        $form->setHtmlIdPrefix('page_');

        $fieldset = $form->addFieldset('base_fieldset', array('legend'=>Mage::helper('cms')->__('Page Information')));

        if ($model->getPageId()) {
            $fieldset->addField('page_id', 'hidden', array(
                    'name' => 'page_id',
                ));
        }

        $fieldset->addField('title', 'text', array(
                'name'      => 'title',
                'label'     => Mage::helper('cms')->__('Page Title'),
                'title'     => Mage::helper('cms')->__('Page Title'),
                'required'  => true,
                'disabled'  => $isElementDisabled
            ));

        $fieldset->addField('identifier', 'text', array(
                'name'      => 'identifier',
                'label'     => Mage::helper('cms')->__('URL Key'),
                'title'     => Mage::helper('cms')->__('URL Key'),
                'required'  => true,
                'class'     => 'validate-identifier',
                'note'      => Mage::helper('cms')->__('Relative to Website Base URL'),
                'disabled'  => $isElementDisabled
            ));

        // added code!
        $fieldset->addField('alternate_href_common_identifier', 'text', array(
                'name'      => 'alternate_href_common_identifier',
                'label'     => Mage::helper('cms')->__('Alternate Href: Common Identifier'),
                'title'     => Mage::helper('cms')->__('Alternate Href: Common Identifier'),
                'required'  => false,
                'class'     => 'validate-identifier',
            ));

        /**
         * Check is single store mode
         */
        if (!Mage::app()->isSingleStoreMode()) {
            $fieldset->addField('store_id', 'multiselect', array(
                    'name'      => 'stores[]',
                    'label'     => Mage::helper('cms')->__('Store View'),
                    'title'     => Mage::helper('cms')->__('Store View'),
                    'required'  => true,
                    'values'    => Mage::getSingleton('adminhtml/system_store')->getStoreValuesForForm(false, true),
                    'disabled'  => $isElementDisabled
                ));
        }
        else {
            $fieldset->addField('store_id', 'hidden', array(
                    'name'      => 'stores[]',
                    'value'     => Mage::app()->getStore(true)->getId()
                ));
            $model->setStoreId(Mage::app()->getStore(true)->getId());
        }

        $fieldset->addField('is_active', 'select', array(
                'label'     => Mage::helper('cms')->__('Status'),
                'title'     => Mage::helper('cms')->__('Page Status'),
                'name'      => 'is_active',
                'required'  => true,
                'options'   => $model->getAvailableStatuses(),
                'disabled'  => $isElementDisabled,
            ));
        if (!$model->getId()) {
            $model->setData('is_active', $isElementDisabled ? '0' : '1');
        }

        Mage::dispatchEvent('adminhtml_cms_page_edit_tab_main_prepare_form', array('form' => $form));

        $form->setValues($model->getData());
        $this->setForm($form);

        // changed: return this, not parent (since parent is overwritten)
        return $this;
    }
}
