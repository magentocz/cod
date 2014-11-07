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

class Magentocz_Cashondelivery_Model_Sales_Invoice_Total extends Mage_Sales_Model_Order_Invoice_Total_Abstract
{
    public function collect(Mage_Sales_Model_Order_Invoice $invoice)
    {
        $order = $invoice->getOrder();

        if ($order->getPayment()->getMethodInstance()->getCode() != 'magentocz_cashondelivery' || !$order->getCodFee()) {
            return $this;
        }

        foreach ($invoice->getOrder()->getInvoiceCollection() as $previousInvoice) {
            if ($previousInvoice->getCodAmount() && !$previousInvoice->isCanceled()) {
                $includeCodTax = false;
            }
        }

        $baseCodFee         = $order->getBaseCodFee();
        $baseCodFeeInvoiced = $order->getBaseCodFeeInvoiced();
        $baseInvoiceTotal   = $invoice->getBaseGrandTotal();
        $codFee             = $order->getCodFee();
        $codFeeInvoiced     = $order->getCodFeeInvoiced();
        $invoiceTotal       = $invoice->getGrandTotal();

        if (!$baseCodFee || $baseCodFeeInvoiced==$baseCodFee) {
            return $this;
        }

        $baseCodFeeToInvoice = $baseCodFee - $baseCodFeeInvoiced;
        $codFeeToInvoice     = $codFee     - $codFeeInvoiced;

        $baseInvoiceTotal = $baseInvoiceTotal + $baseCodFeeToInvoice;
        $invoiceTotal     = $invoiceTotal     + $codFeeToInvoice;

        $invoice->setBaseGrandTotal($baseInvoiceTotal);
        $invoice->setGrandTotal($invoiceTotal);

        $invoice->setBaseCodFee($baseCodFeeToInvoice);
        $invoice->setCodFee($codFeeToInvoice);

        $order->setBaseCodFeeInvoiced($baseCodFeeInvoiced + $baseCodFeeToInvoice);
        $order->setCodFeeInvoiced($codFeeInvoiced         + $codFeeToInvoice);

        return $this;
    }
}