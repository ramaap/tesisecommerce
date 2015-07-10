<?php
class Icube_Vpayment_Block_Form_Vpayment extends Mage_Payment_Block_Form
{
    protected $promo_collection;

    protected function _construct()
    {
        parent::_construct();

        $mark = Mage::getConfig()->getBlockClassName('core/template');
        $mark = new $mark;
        $mark->setTemplate('vpayment/form/logo.phtml');

        $this->setTemplate('vpayment/form/vpayment.phtml')
            ->setMethodTitle('')
            ->setMethodLabelAfterHtml($mark->toHtml());

    }
    
    protected function _getConfig()
    {
        return Mage::getSingleton('payment/config');
    }

    /**
     * Retrieve availables credit card types
     *
     * @return array
     */
    public function getCcAvailableTypes()
    {
        $types = $this->_getConfig()->getCcTypes();
        if ($method = $this->getMethod()) {
            $availableTypes = $method->getConfigData('cctypes');
            if ($availableTypes) {
                $availableTypes = explode(',', $availableTypes);
                foreach ($types as $code=>$name) {
                    if (!in_array($code, $availableTypes)) {
                        unset($types[$code]);
                    }
                }
            }
        }
        return $types;
    }

    /**
     * Retrieve credit card expire months
     *
     * @return array
     */
    public function getCcMonths()
    {
        $months = $this->getData('cc_months');
        if (is_null($months)) {
            $months[0] =  $this->__('Month');
            $months = array_merge($months, $this->_getConfig()->getMonths());
            $this->setData('cc_months', $months);
        }
        return $months;
    }

    /*
     *   Retrieve Veritrans Client Key
     */
    public function getStoreConfig(){
        return Mage::getStoreConfig('payment/vpayment/client');
    }


    /**
     * Retrieve credit card expire years
     *
     * @return array
     */
    public function getCcYears()
    {
        $years = $this->getData('cc_years');
        if (is_null($years)) {
            $years = $this->_getConfig()->getYears();
            $years = array(0=>$this->__('Year'))+$years;
            $this->setData('cc_years', $years);
        }
        return $years;
    }

    /**
     * Retrive has verification configuration
     *
     * @return boolean
     */
    public function hasVerification()
    {
        if ($this->getMethod()) {
            $configData = $this->getMethod()->getConfigData('useccv');
            if(is_null($configData)){
                return true;
            }
            return (bool) $configData;
        }
        return true;
    }

    /*
    * Whether switch/solo card type available
    */
    public function hasSsCardType()
    {
        $availableTypes = explode(',', $this->getMethod()->getConfigData('cctypes'));
        $ssPresenations = array_intersect(array('SS', 'SM', 'SO'), $availableTypes);
        if ($availableTypes && count($ssPresenations) > 0) {
            return true;
        }
        return false;
    }

    /*
    * solo/switch card start year
    * @return array
    */
     public function getSsStartYears()
    {
        $years = array();
        $first = date("Y");

        for ($index=5; $index>=0; $index--) {
            $year = $first - $index;
            $years[$year] = $year;
        }
        $years = array(0=>$this->__('Year'))+$years;
        return $years;
    }

    /**
     * Render block HTML
     *
     * @return string
     */
    protected function _toHtml()
    {
        Mage::dispatchEvent('payment_form_block_to_html_before', array(
            'block'     => $this
        ));
        return parent::_toHtml();
    }

    public function getPromoProgramCollection()
    {
        $this->promo_collection = Mage::getModel('vpayment/program')->getCollection()
            ->addFieldToFilter('program_type', 'bin_filter')
            ->addFieldToFilter('start_date', array('lteq' => date("Y-m-d", Mage::getModel('core/date')->timestamp(time()))),
                array('start_date', 'null'=>''))
            ->addFieldToFilter('end_date', array('gteq' => date("Y-m-d", Mage::getModel('core/date')->timestamp(time()))),
                array('end_date', 'null'=>''))
//            ->addFieldToFilter(array('start_date', 'start_date'),
//                array(
//                    array('lteq'=>Mage::getModel('core/date')->gmtDate()),
//                    array('null'=>'true')
//                ))
//            ->addFieldToFilter(array('end_date', 'end_date'),
//                array(
//                    array('gteq'=>Mage::getModel('core/date')->gmtDate()),
//                    array('null'=>'true')
//                ))
            ->load();
        //Mage::log('COUNT:'.$collection->getSize(),null,'COUNT.log',true);
        //Mage::log('OPTIONCOLL:'.print_r($collection, true),null,'OPTIONCOLL.log',true);
        return $this->promo_collection;
    }

    public function getPromoProgramHtml()
    {
        $coll = $this->promo_collection;
        if($coll->getSize() <= 0)
            $coll = $this->getPromoProgramCollection();
        $html = '<div class="input-box">
                <div class="v-fix">
                    <input type="radio" name="payment[promo]" id="promo_blank" value="" onclick="" class="radio" checked/>
                </div>
                <label for="promo_blank"> '.$this->__('No Promo').'</label>
            </div>';
        foreach($coll as $prom) {
            Mage::log('prom:'.$prom->getPromoCode().'|'.$prom->getPromoName().'|'.$prom->getProgramType().'|'.$prom->getValidationValue(),null,'prom.log',true);
            $html = $html . '<div class="input-box">
                <div class="v-fix">
                    <input type="radio" name="payment[promo]" id="promo_'.$prom->getPromoCode().'" value="'.$prom->getPromoCode().'" onclick="" class="radio" />
                </div>
                <label for="promo_'.$prom->getPromoCode().'"> '.$prom->getPromoName().'</label>
            </div>';
        }
        return $html;
    }

}