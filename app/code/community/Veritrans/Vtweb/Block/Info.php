<?php
/**
 * Veritrans VT Web form block
 *
 * @category   Mage
 * @package    Mage_Veritrans_VtWeb_Block_Form
 * @author     Kisman Hong, plihplih.com
 * when Veritrans payment method is chosen, vtweb/info.phtml template will be rendered at the right side, in progress bar.
 */
class Veritrans_Vtweb_Block_Info extends Mage_Payment_Block_Info
{
    
    protected function _construct()
    {
        parent::_construct();
	$this->setInfoMessage( Mage::helper('vtweb/data')->_getInfoTypeIsImage() == true ? 
		'<img src="'. $this->getSkinUrl('images/veritrans/icon-clickpay.png'). '"/>' : '<b>'. Mage::helper('vtweb/data')->_getTitle() . '</b>');
	$this->setPaymentMethodTitle( Mage::helper('vtweb/data')->_getTitle() );
        $this->setTemplate('vtweb/info.phtml');
    }
/*
    public function toPdf()
    {
        $this->setTemplate('vtweb/pdf.phtml');
        return $this->toHtml();
    } */
}
?>
