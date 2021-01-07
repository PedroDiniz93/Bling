<?php
class CompassoUol_Bling_Model_Observer_Invoice
{
    public function createBlingInvoice(Varien_Event_Observer $observer)
    {
        $this->_helper = Mage::helper('bling/data');

        if ($this->_helper->isActive()) {
            $invoice = $observer->getEvent()->getInvoice();
            $order = $invoice->getOrder();
            $xml = Mage::helper('bling/order')->prepareOrderData($order);

            $blingOrder = Mage::getModel('bling/orders')->loadByOrderId($order->getId());
            if ($blingOrder->getInvoiced()) {
                $comment = 'Durante a criação da Nfe foi identificado que já existe na Nfe Bling';
                $order->addStatusHistoryComment($comment);
                $order->save();
                return;
            }

            if ($blingOrder->getId()) {
                $posts = array(
                    'apikey' => $this->_helper->getToken(),
                    'xml' => rawurlencode($xml),
                );

                $response = $this->executeCurl($posts);

                if ($response) {
                    $response = json_decode($response, true);
                    if ($response['retorno']['notasfiscais']) {
                        $blingOrder->update($blingOrder, $response);
                        $commentNfe = 'Foi criado Notafiscal na bling com ID ' .
                            $response['retorno']['notasfiscais'][0]['notaFiscal']['idNotaFiscal'];
                        $order->addStatusHistoryComment($commentNfe);
                    } else {
                        $comment = 'Não foi possivel realizar a nfe na bling <br>';
                        $order->addStatusHistoryComment($comment);
                    }
                    $order->save();
                }
            }
        }
    }

    public function executeCurl($data)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->_helper->getUrlInvoiceApi());
        curl_setopt($ch, CURLOPT_POST, count($data));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        $response = curl_exec($ch);
        curl_close($ch);
        return $response;
    }
}
