<?php

use Mage_Adminhtml_Controller_Action as Action;

class CompassoUol_Bling_Adminhtml_Bling_OrdersController extends Action
{

    protected function _initAction($ids = null)
    {
        $this->loadLayout($ids);
        return $this;
    }

    public function indexAction()
    {
        $this->_initAction()
            ->_setActiveMenu('bling')
            ->_addContent(
                $this->getLayout()
                    ->createBlock('bling/adminhtml_orders_grid_grid')
            );
        $this->renderLayout();
    }

    public function deleteAction()
    {
        $dataId = $this->getRequest()->getParam('id');
        $dataModel = Mage::getModel('bling/orders');
        $dataModel->setData('id', $dataId);

        try {
            $dataModel->delete();
            Mage::getSingleton('adminhtml/session')->addSuccess('Operação realizada com sucesso');
            $this->_redirect('*/*/index');
        } catch (Exception $e) {
            Mage::getSingleton('adminhtml/session')->addError('Erro ao excluir. ' . $e->getMessage());
            $this->_redirect('*/*/edit', array('id' => $dataId));
        }
    }
}
