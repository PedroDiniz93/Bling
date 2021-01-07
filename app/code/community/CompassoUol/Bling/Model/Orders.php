<?php

use Mage_Core_Model_Abstract as ModelAbstract;

class CompassoUol_Bling_Model_Orders extends ModelAbstract
{

    public function _construct()
    {
        parent::_construct();
        $this->_init('bling/orders');
    }

    public function loadByOrderId($orderId)
    {
        $bling = $this->getCollection()
            ->addFieldToFilter(
                'order_id',
                array(
                    'eq' => $orderId,
                )
            )->getFirstItem();

        if ($bling->getId()) {
            return $bling;
        }

        return $this;
    }
    public function register($order, $response)
    {
        if ($response['retorno']['pedidos'][0]['notaFiscal']['idNotaFiscal']) {
            $this->setInvoiced(true);
        } else {
            $this->setInvoiced(false);
        }
        $this->setContent(json_encode($response));
        $this->setOrderId($order->getId());
        $now = new DateTime();
        $this->setCreatedAt($now->format('Y-m-d H:i:s'));
        $this->save();
    }

    public function update($blingOrder, $response)
    {
        if ($response['retorno']['notasfiscais'][0]['notaFiscal']['idNotaFiscal']) {
            $blingOrder->setInvoiced(true);
            $blingOrder->setContent(
                json_encode(
                    array_merge_recursive(
                        json_decode($blingOrder->getContent(), true), $response
                    )
                )
            );
            $blingOrder->save();
        }
    }
}
