<?php
class Icube_Vpayment_Model_Resource_Mode //extends Mage_Core_Model_Resource_Db_Abstract
{

    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        return array(
            array('value' => 0, 'label'=>Mage::helper('adminhtml')->__('Development')),
            array('value' => 1, 'label'=>Mage::helper('adminhtml')->__('Production')),
        );
    }

    /**
     * Get options in "key-value" format
     *
     * @return array
     */
    public function toArray()
    {
        return array(
            0 => Mage::helper('adminhtml')->__('Development'),
            1 => Mage::helper('adminhtml')->__('Production'),
        );
    }
}
?>