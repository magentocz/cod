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
/**
 * COD fee Total Row Renderer
 * 
 */
class Magentocz_Cashondelivery_Block_Adminhtml_Sales_Order_Create_Totals_Cod extends Mage_Adminhtml_Block_Sales_Order_Create_Totals_Default
{
    /**
     * Path to template file in theme.
     *
     * @var string
     */
    protected $_template = 'magentocz/cashondelivery/sales/order/create/totals/cashondelivery.phtml';

    /**
     * Variable to lazy load the helper.
     *
     * @var Magentocz_Cashondelivery_Helper_Data
     */
    protected $_helper;

    /**
     * Get the helper object.
     *
     * @return Magentocz_Cashondelivery_Helper_Data
     */
    protected function _getHelper()
    {
        if (!$this->_helper) {
            $this->_helper = Mage::helper('magentocz_cashondelivery');
        }
        return $this->_helper;
    }

    /**
     * Check if we need to display the CoD fee including and excluding the tax.
     *
     * @return bool
     */
    public function displayBoth()
    {
        return $this->_getHelper()->displayCodBothPrices();
    }

    /**
     * Check if we need to display the CoD fee including the tax.
     *
     * @return bool
     */
    public function displayIncludeTax()
    {
        return $this->_getHelper()->displayCodFeeIncludingTax();
    }

    /**
     * Get the CoD fee including the tax.
     *
     * @return float
     */
    public function getCodFeeIncludeTax()
    {
        return $this->getTotal()->getAddress()->getCodFee() + $this->getTotal()->getAddress()->getCodTaxAmount();
    }

    /**
     * Get the CoD fee excluding the tax.
     *
     * @return float
     */
    public function getCodFeeExcludeTax()
    {
        return $this->getTotal()->getAddress()->getCodFee();
    }

    /**
     * Get the label for the CoD fee including the tax.
     *
     * @return float
     */
    public function getIncludeTaxLabel()
    {
        return $this->_getHelper()->__('Cash on Delivery fee Incl. Tax');
    }

    /**
     * Get the label for the CoD fee excluding the tax.
     *
     * @return float
     */
    public function getExcludeTaxLabel()
    {
        return $this->_getHelper()->__('Cash on Delivery fee Excl. Tax');
    }
}
