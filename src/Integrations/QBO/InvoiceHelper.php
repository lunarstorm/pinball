<?php

namespace Vio\Pinball\Integrations\QBO;

use Illuminate\Support\Arr;

class InvoiceHelper
{
    public static $companyPrefs = null;

    public static function companyPrefs()
    {
        if (is_null(static::$companyPrefs)) {
            $ds = Quickbooks::ds();
            static::$companyPrefs = $ds->getCompanyPreferences();
        }

        return static::$companyPrefs;
    }

    public static function process($invoice = [])
    {
        $defaults = [
            'Line' => [],
            'AllowOnlineCreditCardPayment' => true,
            'AllowOnlineACHPayment' => true,
            'CustomerRef' => [
                'value' => null,
            ],
        ];
        $invoice = array_intersect_key($invoice, $defaults) + $defaults;

        foreach ($invoice['Line'] as $i => $line) {
            if (! data_get($line, 'SalesItemLineDetail.TaxCodeRef.value')) {
                Arr::forget($invoice['Line'][$i], 'SalesItemLineDetail.TaxCodeRef');
            }
        }

        return $invoice;
    }
}
