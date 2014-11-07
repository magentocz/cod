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

class Magentocz_Cashondelivery_Block_Totals_Invoice extends Magentocz_Cashondelivery_Block_Totals_Abstract
{
    /**
     * Set the correct parent block and the object from which we get / set our total data.
     *
     * @return Magentocz_Cashondelivery_Block_Invoice_Totals_Cod
     */
    protected function _prepareTotals()
    {
        parent::_prepareTotals();

        $this->_parentBlock = $this->getParentBlock();
        $this->_totalObject = $this->_parentBlock->getInvoice();

        return $this;
    }
}