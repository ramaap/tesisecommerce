<?php

class Icube_Vpayment_Helper_Data extends Mage_Core_Helper_Abstract
{
    public function sentReqVtrans($comidity)
    {
        $json       = json_encode($comidity);
        $server_key = Mage::getStoreConfig('payment/vpayment/server');
        $server_key = base64_encode($server_key);
//        $server_key = base64_encode($this->getServerKey());
//        $url        = $this->getUrl();
        $url        = Mage::getStoreConfig('payment/vpayment/vurl');
        $ch         = curl_init($url);
        //curl_setopt($ch, CURLOPT_USERPWD, $server_key.':');
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Accept: application/json',
            'Content-Type: application/json',
            'Authorization: Basic ' . $server_key
        ));

        $result = curl_exec($ch);

        //Mage::log($json,null,'VTjson.log',true);
        return json_decode($result);
    }

    public function sentReqVtransAuthorize($comidity)
    {
        $json       = json_encode($comidity);
        $server_key = Mage::getStoreConfig('payment/vpayment/server');
        $server_key = base64_encode($server_key);
        $url        = Mage::getStoreConfig('payment/vpayment/authorize_url');
        $ch         = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Accept: application/json',
            'Content-Type: application/json',
            'Authorization: Basic ' . $server_key
        ));

        $result = curl_exec($ch);

        //Mage::log($json,null,'VTjson.log',true);
        return json_decode($result);
    }

    public function sendCancelTransaction($order_id)
    {
        $server_key = Mage::getStoreConfig('payment/vpayment/server');
        $server_key = base64_encode($server_key);
        $url        = Mage::getStoreConfig('payment/vpayment/cancel_url');
        $url = str_replace("{id}",$order_id,$url);
//        Mage::log($url,null,'url.log',true);
        $ch         = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
//        curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Accept: application/json',
            'Content-Type: application/json',
            'Authorization: Basic ' . $server_key
        ));

        $result = curl_exec($ch);

//        Mage::log(json_decode($result),null,'sendCancelTransaction.log',true);
        return json_decode($result);
    }

}