<?php

namespace Vio\Pinball\Integrations\QBO;

use Illuminate\Support\Arr;

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
            if (!data_get($line, "SalesItemLineDetail.TaxCodeRef.value")) {
                Arr::forget($invoice['Line'][$i], "SalesItemLineDetail.TaxCodeRef");
            }
        }

        return $invoice;
    }
}
