<?php

use Mage_Core_Model_Mysql4_Abstract as Mysql4Abstract;

class CompassoUol_Bling_Model_Resources_Orders extends Mysql4Abstract
{
    public function _construct()
    {
        $this->_init('bling/orders', 'id');
    }
}
