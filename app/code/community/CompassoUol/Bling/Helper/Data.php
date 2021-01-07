<?php

use Mage_Core_Helper_Abstract as HelperAbstract;

class CompassoUol_Bling_Helper_Data extends HelperAbstract
{
    private $config = 'bling/general/';

    public function getConfig($path)
    {
        return Mage::getStoreConfig($this->config . $path);
    }

    public function setConfig($path, $value = "")
    {
        Mage::getModel('core/config')->saveConfig($this->config . $path, $value);
        Mage::getModel('core/config')->cleanCache();
        return $this->getConfig($path);
    }

    public function isActive()
    {
        if (
            Mage::helper('core')->isModuleEnabled('CompassoUol_Bling')
            && $this->getConfig('active')
        ) {
            return true;
        }
        return false;
    }

    public function isProduction()
    {
        if ($this->getConfig('environment') == 'production') {
            return true;
        }
        return false;
    }

    public function getToken()
    {
        if ($this->isProduction()) {
            return $this->getConfig('token');
        }
        return $this->getConfig('token_sandbox');
    }

    public function getUrlOrderApi()
    {
        return 'https://bling.com.br/Api/v2/pedido/json/';
    }

    public function getUrlInvoiceApi()
    {
        return 'https://bling.com.br/Api/v2/notafiscal/json/';
    }

    public function getAutoNfe()
    {
        if ($this->getConfig('auto_nfe')) {
            return 'true';
        }
        return 'false';
    }
}
