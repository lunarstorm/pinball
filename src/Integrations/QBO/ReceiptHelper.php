<?php

namespace Pinball\Integrations\QBO;

class ReceiptHelper
{

    public static function process($invoice = [])
    {
        $defaults = [
            'Line' => [],
            'CustomerRef' => [
                'value' => null,
            ],
            'PaymentRefNum' => ''
        ];
        $invoice = array_intersect_key($invoice, $defaults) + $defaults;

        foreach ($invoice['Line'] as $i => $line) {
            if (!$line['SalesItemLineDetail']['TaxCodeRef']['value']) {
                unset($invoice['Line'][$i]['SalesItemLineDetail']['TaxCodeRef']);
            }
        }

        return $invoice;
    }
}
