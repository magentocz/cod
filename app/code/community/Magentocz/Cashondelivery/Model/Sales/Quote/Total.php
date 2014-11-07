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

class Magentocz_Cashondelivery_Model_Sales_Quote_Total extends Mage_Sales_Model_Quote_Address_Total_Abstract
{
    public function collect(Mage_Sales_Model_Quote_Address $address)
    {
        $address->setBaseCodFee(0);
        $address->setCodFee(0);
        $address->setCodTaxAmount(0);
        $address->setBaseCodTaxAmount(0);

        $collection = $address->getQuote()->getPaymentsCollection();
        if ($collection->count() <= 0 || $address->getQuote()->getPayment()->getMethod() == null) {
            return $this;
        }

        $paymentMethod = $address->getQuote()->getPayment()->getMethodInstance();

        if ($paymentMethod->getCode() != 'magentocz_cashondelivery') {
            return $this;
        }

        $items = $address->getAllItems();
        if (!count($items)) {
            return $this;
        }

        $baseTotal = $address->getBaseGrandTotal();        

        $baseCodFee = $paymentMethod->getAddressCodFee($address);

        if (!$baseCodFee > 0 ) {
            return $this;
        }

        // adress is the reference for grand total
        $quote = $address->getQuote();

        $store = $quote->getStore();

        $baseTotal += $baseCodFee;

        $address->setBaseCodFee($baseCodFee);
        $address->setCodFee($store->convertPrice($baseCodFee, false));

        // update totals
        $address->setBaseGrandTotal($baseTotal);
        $address->setGrandTotal($store->convertPrice($baseTotal, false));

        //Updating cod tax if it is already included into a COD fee
        $baseCodTaxAmount = $paymentMethod->getAddressCodTaxAmount($address);
        $address->setBaseCodTaxAmount($baseCodTaxAmount);
        $address->setCodTaxAmount($store->convertPrice($baseCodTaxAmount, false));       

        return $this;
    }

    public function fetch(Mage_Sales_Model_Quote_Address $address)
    {
        $amount = $address->getCodFee();        
        if ($amount!=0) {
            $quote = $address->getQuote();
            $address->addTotal(
                array(
                    'code' => $this->getCode(),
                    'title' => Mage::helper('magentocz_cashondelivery')->__('Cash on Delivery fee'),
                    'value' => $amount
                )
            );
        }
        return $this;
    }
}
