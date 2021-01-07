<?php

use Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract as RendererAbstract;

class CompassoUol_Bling_Block_Adminhtml_Orders_Renderer_Content extends RendererAbstract
{
    public function render(Varien_Object $row)
    {
        $value = $row->getContent();
        $value = json_decode($value, true);
        $value = $value['retorno']['pedidos'][0]['pedido']['idPedido'];

        return $value;
    }
}
