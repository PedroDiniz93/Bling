<?php

use Mage_Core_Helper_Abstract as HelperAbstract;

class CompassoUol_Bling_Helper_Order extends HelperAbstract
{
    public function prepareOrderData($order)
    {
        $this->_order = $order;
        $this->_quote = Mage::getModel('checkout/session')->getQuote();

        $data = new Varien_Object();
        $data->setCliente($this->getCustomer());
        $data->setItens($this->getItems());
        $data->setVlrFrete($this->_quote->getShippingAddress()->getShippingAmount()[0]);

        $xml = new SimpleXMLElement('<Pedido/>');
        $this->arrayToXml($data->getData(), $xml);

        return $xml->asXML();
    }

    private function getCustomer()
    {
        $billing = $this->_order->getBillingAddress();
        $customer = Mage::getModel('customer/customer')->load($this->_order->getCustomerId());

        $data_customer = array(
            'nome' => $billing->getFirstname(),
            'tipoPessoa' => $this->validateTypeTaxvat($customer->getTaxvat()),
            'endereco' => $billing->getStreet1(),
            'cpf_cnpj' => $this->formmatTaxvat($customer->getTaxvat()),
            'numero' => $billing->getStreet2(),
            'bairro' => $billing->getStreet4(),
            'cep' => $billing->getPostcode(),
            'cidade' => $billing->getCity(),
            'uf' => $this->getRegionAbbreviation($billing),
            'fone' => $this->getCustomerPhone(),
            'email' => htmlentities($customer->getEmail()),
        );

        return $data_customer;
    }

    private function getItems()
    {
        foreach ($this->_order->getAllVisibleItems() as $item) {
            $items[] = array(
                'codigo' => $item->getSku(),
                'descricao' => $item->getName(),
                'qtde' => number_format($item->getQtyOrdered(), 0, '.', ''),
                'vlr_unit' => (float) number_format($item->getPrice(), 2, '.', ''),
            );
        }
        return $items;
    }

    private function validateTypeTaxvat($tax)
    {
        $tax = $this->formmatTaxvat($tax);
        if (strlen($tax) == 14) {
            return 'J';
        }
        if (strlen($tax) == 11) {
            return 'F';
        }
    }

    private function formmatTaxvat($taxvat)
    {
        $taxvat = str_replace('.', '', $taxvat);
        $taxvat = str_replace('/', '', $taxvat);
        $taxvat = str_replace('-', '', $taxvat);
        return $taxvat;
    }

    private function getRegionAbbreviation($billing)
    {
        $uf = Mage::getModel('directory/region')->load($billing->getData('region_id'));
        return $uf->getData('code');
    }

    private function getCustomerPhone()
    {
        $phone = null;
        if ($this->_order->getBillingAddress() && $this->_order->getBillingAddress()->getTelephone()) {
            $phone = $this->formatPhone($this->_order->getBillingAddress()->getTelephone());
        } else if ($this->_order->getShippingAddress() && $this->_order->getShippingAddress()->getTelephone()) {
            $phone = $this->formatPhone($this->_order->getShippingAddress()->getTelephone());
        }
        return $phone;
    }

    private function formatPhone($phone)
    {
        $phone = preg_replace('/[^0-9]/', '', $phone);
        $ddd = '';
        if (strlen($phone) > 9) {
            if (substr($phone, 0, 1) == 0) {
                $phone = substr($phone, 1);
            }
            $ddd = substr($phone, 0, 2);
            $phone = substr($phone, 2);
        }

        return array('area_code' => $ddd, 'number' => $phone);
    }

    private function arrayToXml($array, &$xml_user_info)
    {
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                if (!is_numeric($key)) {
                    $subnode = $xml_user_info->addChild("$key");
                    $this->arrayToXml($value, $subnode);
                } else {
                    $subnode = $xml_user_info->addChild("item");
                    $this->arrayToXml($value, $subnode);
                }
            } else {
                $xml_user_info->addChild("$key", htmlspecialchars("$value"));
            }
        }
    }
}
