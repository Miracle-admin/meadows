<?php
interface iPaymentProcessor
{
    public function getHtmlFields();
    public function getPaymentProcessorHtml();
    public function getPaymentDetails($paymentDetails);
    public function processTransaction($data);
} 
?>
