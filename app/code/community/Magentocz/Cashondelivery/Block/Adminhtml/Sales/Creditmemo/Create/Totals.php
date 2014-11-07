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
class Magentocz_Cashondelivery_Block_Adminhtml_Sales_Creditmemo_Create_Totals extends Mage_Adminhtml_Block_Template
{
    /**
     * Holds the creditmemo object.
     * @var Mage_Sales_Model_Order_Creditmemo
     */
    protected $_source;

    /**
     * Initialize creditmemo CoD totals
     *
     * @return Magentocz_Cashondelivery_Block_Adminhtml_Sales_Creditmemo_Create_Totals
     */
    public function initTotals()
    {
        $parent         = $this->getParentBlock();
        $this->_source  = $parent->getSource();
        $total          = new Varien_Object(array(
            'code'      => 'magentocz_cashondelivery_fee',
            'value'     => $this->getCodAmount(),
            'base_value'=> $this->getCodAmount(),
            'label'     => $this->helper('magentocz_cashondelivery')->__('Refund Cash on Delivery fee')
        ));

        $parent->addTotalBefore($total, 'shipping');
        return $this;
    }

    /**
     * Getter for the creditmemo object.
     *
     * @return Mage_Sales_Model_Order_Creditmemo
     */
    public function getSource()
    {
        return $this->_source;
    }

    /**
     * Get CoD fee amount for actual invoice.
     * @return float
     */
    public function getCodAmount()
    {
        $codFee = $this->_source->getCodFee() + $this->_source->getCodTaxAmount();

        return Mage::app()->getStore()->roundPrice($codFee);
    }
}