<?php

use Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract as RendererAbstract;

class CompassoUol_Bling_Block_Adminhtml_Orders_Renderer_Body extends RendererAbstract
{
    public function render(Varien_Object $row)
    {
        $value = $row->getContent();
        if (substr($value, 0, 1) === '{') {
            $value = $this->_formatJson($value);
        }
        return $value;
    }

    protected function _formatJson($json)
    {
        return '<pre class="prettyprint"><code class="lang-json">'
            . $this->escapeHtml(json_encode(json_decode($json), JSON_PRETTY_PRINT)) . '</code></pre>';
    }
}
