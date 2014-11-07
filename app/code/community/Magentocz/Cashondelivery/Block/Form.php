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

class Magentocz_Cashondelivery_Block_Form extends Mage_Payment_Block_Form
{
    /**
     * Path to template file in theme.
     *
     * @var string
     */
    protected $_template = 'magentocz/cashondelivery/form.phtml';

    public function getQuote()
    {
        return $this->getMethod()->getInfoInstance()->getQuote();
    }

    public function getShippingAddress()
    {
        return $this->getQuote()->getShippingAddress();
    }

    public function convertPrice($price, $format=false, $includeContainer = true)
    {
        return $this->getQuote()->getStore()->convertPrice($price, $format, $includeContainer);
    }

}
