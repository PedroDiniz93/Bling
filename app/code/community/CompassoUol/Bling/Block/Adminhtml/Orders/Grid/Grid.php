
<?php

use Mage_Adminhtml_Block_Widget_Grid as WidgetGrid;
use CompassoUol_Bling_Block_Adminhtml_Orders_Renderer_Content as RendererContent;
use CompassoUol_Bling_Block_Adminhtml_Orders_Renderer_Body as RendererBody;

class CompassoUol_Bling_Block_Adminhtml_Orders_Grid_Grid extends WidgetGrid
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('orders_grid');
        $this->setDefaultSort('id');
        $this->setDefaultDir('ASC');
        $this->setSaveBlingInSession(true);
    }

    protected function _prepareCollection()
    {
        $collection = Mage::getModel('bling/orders')->getCollection();

        $this->setCollection($collection);
        parent::_prepareCollection();
        return $this;
    }

    protected function _prepareColumns()
    {
        $this->addColumn('id', array(
            'header' => 'ID',
            'sortable' => true,
            'index' => 'id',
            'width' => '50px',
        ));

        $this->addColumn('order_id', array(
            'header' => 'ID do Pedido',
            'sortable' => true,
            'index' => 'order_id',
            'type' => 'text',
        ));

        $this->addColumn('id_order_bling', array(
            'header' => 'ID pedido bling',
            'sortable' => true,
            'index' => 'content',
            'type' => 'text',
            'renderer' => RendererContent::class
        ));

        $this->addColumn('content', array(
            'header' => 'Conteúdo da requisição',
            'sortable' => true,
            'index' => 'content',
            'type' => 'text',
            'renderer' => RendererBody::class
        ));

        $this->addColumn('created_at', array(
            'header' => 'Data da criação',
            'sortable' => true,
            'index' => 'created_at',
            'type' => 'text',
        ));

        $this->addColumn('delete', array(
            'header' => 'Ação',
            'type' => 'action',
            'width' => '80px',
            'align' => 'center',
            'filter' => false,
            'sortable' => false,
            'index' => 'delete',
            'getter' => 'getId',
            'actions' => array(
                array(
                    'caption' => 'Deletar',
                    'url' =>  $this->getUrl('*/*/delete', array('id' => '$id')),
                    'confirm'   => Mage::helper('adminhtml')->__('Are you sure you want to do this?')
                ),
            ),
        ));

        return parent::_prepareColumns();
    }
}
