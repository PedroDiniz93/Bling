<?php

class CompassoUol_Bling_Model_Source_Config_Environment
{
    public function toOptionArray()
    {
        return [
            [
                'value' => 'production',
                'label' => 'Produção'
            ],
            [
                'value' => 'sandbox',
                'label' => 'Homologação'
            ]
        ];
    }
}
