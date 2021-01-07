<?php

use Mage_Core_Model_Mysql4_Collection_Abstract as CollectionAbstract;

class CompassoUol_Bling_Model_Resources_Orders_Collection extends CollectionAbstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('bling/orders');
    }
}