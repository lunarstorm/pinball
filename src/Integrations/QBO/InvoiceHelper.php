<?php

namespace Pinball\Integrations\QBO;

class InvoiceHelper
{

    static $companyPrefs = null;

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
            if (!$line['SalesItemLineDetail']['TaxCodeRef']['value']) {
                unset($invoice['Line'][$i]['SalesItemLineDetail']['TaxCodeRef']);
            }
        }

        return $invoice;
    }
}
