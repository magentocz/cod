<?php
/** 
* Magento CZ Module
* 
* NOTICE OF LICENSE 
* 
* This source file is subject to the Open Software License (OSL 3.0) 
* that is bundled with this package in the file LICENSE.txt. 
* It is also available through the world-wide-web at this URL: 
* http://opensource.org/licenses/osl-3.0.php 
* If you did of the license and are unable to 
* obtain it through the world-wide-web, please send an email 
* to magentocz@gmail.com so we can send you a copy immediately. 
* 
* @copyright Copyright (c) 2014 GetReady s.r.o. (https://getready.cz)
* 
*/ 

class Magentocz_Cashondelivery_Block_Totals_Abstract extends Mage_Core_Block_Abstract
{
    /**
     * Holds the correct object from where we get the CoD fees and taxes from.
     * For example this could be the order or invoice object.
     *
     * @var Varien_Object
     */
    protected $_totalObject;

    /**
     * Holds the correct parent block from which we get the total object and set the totals.
     *
     * @var Mage_Core_Block_Abstract
     */
    protected $_parentBlock;

    /**
     * Generate and add the CoD totals to the parent block.
     *
     * @return Magentocz_Cashondelivery_Block_Totals_Abstract
     */
    public function initTotals()
    {
        $this->_prepareTotals();

        if ($this->_totalObject && $this->_totalObject->getCodFee()) {
            $label     = $this->__('Cash on Delivery fee');
            $value     = $this->_totalObject->getCodFee();
            $baseValue = $this->_totalObject->getBaseCodFee();
            $code      = 'magentocz_cashondelivery_fee';

            if (Mage::helper('magentocz_cashondelivery')->displayCodBothPrices()) {

                $label = $this->__('Cash on Delivery fee (Excl.Tax)');

                $totalInclLabel     = $this->__('Cash on Delivery fee (Incl.Tax)');
                $totalInclValue     = $this->_totalObject->getCodFee()     + $this->_totalObject->getCodTaxAmount();
                $totalInclBaseValue = $this->_totalObject->getBaseCodFee() + $this->_totalObject->getBaseCodTaxAmount();
                $totalInclCode      = 'magentocz_cashondelivery_fee_incl';

            } elseif (Mage::helper('magentocz_cashondelivery')->displayCodFeeIncludingTax()) {

                $value     = $this->_totalObject->getCodFee()     + $this->_totalObject->getCodTaxAmount();
                $baseValue = $this->_totalObject->getBaseCodFee() + $this->_totalObject->getBaseCodTaxAmount();
            }

            $totalObject = $this->_getTotalObject($label, $value, $baseValue, $code);
            $this->_addTotalToParent($totalObject);

            if (isset($totalInclLabel)) {
                $totalInclObject = $this->_getTotalObject($totalInclLabel, $totalInclValue, $totalInclBaseValue, $totalInclCode);
                $this->_addTotalToParent($totalInclObject, 'magentocz_cashondelivery_fee');
            }
        }
        return $this;
    }

    /**
     * To be able to abstract the CoD totals we need an own method to set the right objects.
     *
     * @return Magentocz_Cashondelivery_Block_Totals_Abstract
     */
    protected function _prepareTotals()
    {
        return $this;
    }

    /**
     * Generate an Varien_Object which could be set as total to the parent block.
     *
     * @param $label string
     * @param $value string
     * @param $baseValue string
     * @param $code string
     * @return Varien_Object
     */
    protected function _getTotalObject($label, $value, $baseValue, $code)
    {
        $total = new Varien_Object();
        $total->setLabel($label)
              ->setValue($value)
              ->setBaseValue($baseValue)
              ->setCode($code);

        return $total;
    }

    /**
     * Add an Varien_Object, which holds the total values, to the parent block.
     *
     * @param $total Varien_Object
     * @param $after null|string
     * @return Magentocz_Cashondelivery_Block_Totals_Abstract
     */
    protected function _addTotalToParent($total, $after = null)
    {
        if (!$after) {
            $after = Mage::helper('magentocz_cashondelivery')->getTotalAfterPosition();
        }

        $this->_parentBlock->addTotal($total, $after);

        return $this;
    }
}