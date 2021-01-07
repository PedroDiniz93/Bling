<?php
class CompassoUol_Bling_Model_Observer_Order
{
    public function createBlingOrder(Varien_Event_Observer $observer)
    {
        $this->_helper = Mage::helper('bling/data');

        if ($this->_helper->isActive()) {
            $order = $observer->getOrder();
            $xml = Mage::helper('bling/order')->prepareOrderData($order);

            $posts = array(
                'apikey' => $this->_helper->getToken(),
                'xml' => rawurlencode($xml),
                'gerarnfe' => $this->_helper->getAutoNfe()
            );

            $response = $this->executeCurl($posts);

            if ($response) {
                $response = json_decode($response, true);
                if ($response['retorno']['pedidos']) {
                    Mage::getModel('bling/orders')->register($order, $response);
                    $comment = 'Foi criado pedido na bling com ID ' .
                        $response['retorno']['pedidos'][0]['pedido']['idPedido'];
                    $order->addStatusHistoryComment($comment);
                    if ($this->_helper->getAutoNfe() == 'true') {
                        $commentNfe = 'Foi criado Notafiscal na bling com ID ' .
                            $response['retorno']['pedidos'][0]['notaFiscal']['idNotaFiscal'];
                        $order->addStatusHistoryComment($commentNfe);
                    }
                } else {
                    $comment = 'Não foi possivel realizar pedido na bling ' . $response['retorno'];
                    $order->addStatusHistoryComment($comment);
                }
                $order->save();
            }
        }
    }

    public function executeCurl($data)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->_helper->getUrlOrderApi());
        curl_setopt($ch, CURLOPT_POST, count($data));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        $response = curl_exec($ch);
        curl_close($ch);
        return $response;
    }
}
