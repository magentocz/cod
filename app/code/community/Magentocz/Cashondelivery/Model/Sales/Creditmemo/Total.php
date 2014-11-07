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

class Magentocz_Cashondelivery_Model_Sales_Creditmemo_Total extends Mage_Sales_Model_Order_Creditmemo_Total_Abstract
{
    public function collect(Mage_Sales_Model_Order_Creditmemo $creditmemo)
    {
        $order = $creditmemo->getOrder();

        if ($order->getPayment()->getMethodInstance()->getCode() != 'magentocz_cashondelivery') {
            return $this;
        }

        $creditmemoBaseGrandTotal = $creditmemo->getBaseGrandTotal();
        $creditmemoGrandTotal     = $creditmemo->getGrandTotal();

        $baseCodFeeRefunded       = $order->getBaseCodFeeRefunded();
        $codFeeRefunded           = $order->getCodFeeRefunded();

        $baseCodFeeInvoiced       = $order->getBaseCodFeeInvoiced();
        $codFeeInvoiced           = $order->getCodFeeInvoiced();

        $baseCodFeeToRefund       = abs($baseCodFeeInvoiced - $baseCodFeeRefunded);
        $codFeeToRefund           = abs($codFeeInvoiced     - $codFeeRefunded);

        if ($baseCodFeeToRefund <= 0) {
            return $this;
        }

        $creditmemo->setBaseGrandTotal($creditmemoBaseGrandTotal + $baseCodFeeToRefund)
                   ->setGrandTotal($creditmemoGrandTotal         + $codFeeToRefund)
                   ->setBaseCodFee($baseCodFeeToRefund)
                   ->setCodFee($codFeeToRefund);

        $order->setBaseCodFeeRefunded($baseCodFeeRefunded + $baseCodFeeToRefund)
              ->setCodFeeRefunded($codFeeRefunded         + $codFeeToRefund);


        return $this;
    }
}