<?php

namespace Vio\Pinball\Integrations\QBO;

use Vio\Pinball\Models\IoData;
use Vio\Pinball\Helpers\Date;
use QuickBooksOnline\API\Core\OAuth\OAuth2\OAuth2LoginHelper;
use QuickBooksOnline\API\DataService\DataService;
use anlutro\LaravelSettings\SettingStore;
use Illuminate\Support\Facades\Log;
use Vio\Pinball\Models\Setting;

class Quickbooks
{

    public static $values = [];
    public static $errors = [];
    public static $userResolver = null;

    public static function set($key, $value)
    {
        static::$values[$key] = $value;
        return static::get($key);
    }

    public static function get($key)
    {
        return static::$values[$key];
    }

    public static function urls()
    {
        if (app()->environment('production')) {
            $baseUrl = "https://quickbooks.api.intuit.com";
        } else {
            $baseUrl = "https://sandbox-quickbooks.api.intuit.com";
        }

        $authUrl = static::get('AUTH_URL');

        if (is_callable($authUrl)) {
            $url = call_user_func($authUrl);
        } else {
            $url = $authUrl;
        }

        return [
            'redirect' => $url,
            'base' => $baseUrl,
        ];
    }

    public static function prepareDataService($redirectUrl = '/')
    {
        $urls = static::urls();

        $dataService = DataService::Configure([
            'auth_mode' => 'oauth2',
            'ClientID' => static::get('CLIENT_ID'),
            'ClientSecret' => static::get('CLIENT_SECRET'),
            'RedirectURI' => $urls['redirect'],
            'scope' => "com.intuit.quickbooks.accounting",
            'baseUrl' => $urls['base'],
        ]);

        return $dataService;
    }

    public static function fetchCompanyInfo($o = [])
    {
        $defaults = [
            'refresh' => false,
        ];
        $o += $defaults;

        $user = auth()->user();

        if ($o['refresh']) {
            if ($ds = Quickbooks::ds()) {
                try {
                    $data = $ds->getCompanyInfo();
                    $data = (array)$data;
                    Log::info("Quickbooks Company Info Received", $data);
                    Setting::set('qbo.companyInfo', $data, $user);
                    return $data;
                } catch (\Exception $e) {
                    Log::error("Error fetching Quickbooks Company Info: " . $e->getMessage(), $e);
                    return false;
                }
            }
        }

        if ($data = Setting::get('qbo.companyInfo', $user)) {
            return $data;
        }

        return false;
    }

    public static function userResolver($callback)
    {
        static::$userResolver = $callback;
    }

    public static function resolveUser($uid)
    {
        if (is_callable(static::$userResolver)) {
            $userResolver = static::$userResolver;
            return call_user_func($userResolver, $uid);
        }

        return null;
    }

    public static function config($o = [])
    {
        $defaults = [
            'accessToken' => false,
            'clear' => false,
            'user' => false,
            'refresh' => false,
        ];
        $o += $defaults;

        if ($o['refresh']) {
            // trigger a token update
            Quickbooks::ds(['refresh' => true]);
        }

        $user = auth()->user();

        if ($uid = $o['user']) {
            if ($resolver = static::$userResolver) {
                $user = static::resolveUser($uid);
            }
        }

        if ($o['clear']) {
            return Setting::forget('qbo', $user);
        }

        if ($accessToken = $o['accessToken']) {
            $qbo = [
                'realmId' => $accessToken->getRealmID(),
                'clientId' => $accessToken->getClientID(),
                'clientSecret' => $accessToken->getClientSecret(),
                'accessToken' => $accessToken->getAccessToken(),
                'accessTokenExpiry' => $accessToken->getAccessTokenExpiresAt(),
                'refreshToken' => $accessToken->getRefreshToken(),
                'refreshTokenExpiry' => $accessToken->getRefreshTokenExpiresAt(),
                'raw' => (array)$accessToken,
            ];
            Setting::set('qbo', $qbo, $user);
            return $qbo;
        }

        $config = Setting::get('qbo', $user);

        return Setting::get('qbo', $user) ?: false;
    }

    public static function _clearSavedData($user = null)
    {
        if (!$user) {
            $user = auth()->user();
        }
        Setting::set('qbo', null, $user);
        Setting::set('qbo.companyInfo', null, $user);
        Setting::set('qbo.items', null, $user);
        return true;
    }

    public static function disconnect()
    {
        try {
            $qbo = static::config();

            $cid = static::get('CLIENT_ID');
            $secret = static::get('CLIENT_SECRET');

            $oauth2LoginHelper = new OAuth2LoginHelper($cid, $secret);
            $revokeResult = $oauth2LoginHelper->revokeToken(data_get($qbo, 'refreshToken'));
            static::_clearSavedData();
            return true;
        } catch (\Exception $e) {
            /*print_pre($e);
			die();*/
        }

        return false;
    }

    public static function ds($o = [])
    {
        $defaults = [
            'refresh' => false,
        ];
        $o += $defaults;

        if ($qbo = static::config()) {
            $urls = static::urls();
            $cid = static::get('CLIENT_ID');
            $secret = static::get('CLIENT_SECRET');

            try {
                $dataService = DataService::Configure([
                    'auth_mode' => 'oauth2',
                    'ClientID' => $cid,
                    'ClientSecret' => $secret,
                    'accessTokenKey' => data_get($qbo, 'accessToken'),
                    'refreshTokenKey' => data_get($qbo, 'refreshToken'),
                    'QBORealmID' => data_get($qbo, 'realmId'),
                    'baseUrl' => data_get($urls, 'base'),
                ]);

                $diff = Date::diff(data_get($qbo, 'accessTokenExpiry'));
                /*print_pre($diff);
				die();*/
                if ($diff['m'] < 10) {
                    $o['refresh'] = true;
                }

                $OAuth2LoginHelper = $dataService->getOAuth2LoginHelper();

                if ($o['refresh']) {
                    $accessToken = $OAuth2LoginHelper->refreshAccessTokenWithRefreshToken(data_get($qbo, 'refreshToken'));
                    // Update the config with refreshed token
                    static::config(['accessToken' => $accessToken]);
                } else {
                    $accessToken = $OAuth2LoginHelper->getAccessToken();
                }

                $dataService->updateOAuth2Token($accessToken);

                return $dataService;
            } catch (\Exception $e) {
                //static::_clearSavedData();
            }
        }

        return false;
    }

    public static function init()
    {
        return static::ds();
    }

    public static function fetchProductItems()
    {
        $items = [];

        if ($ds = Quickbooks::ds()) {
            $i = 0;

            while (1) {
                $allItems = $ds->FindAll('Item', $i, 500);
                $error = $ds->getLastError();
                if ($error) {
                    $msg = "The Status code is: " . $error->getHttpStatusCode() . "\n";
                    $msg .= "The Helper message is: " . $error->getOAuthHelperError() . "\n";
                    $msg .= "The Response message is: " . $error->getResponseBody() . "\n";
                    static::$errors[] = $msg;
                    return false;
                }
                if (!$allItems || (0 == count($allItems))) {
                    break;
                }
                foreach ($allItems as $item) {
                    /*echo "Customer[" . ($i++) . "]: {$oneCustomer->DisplayName}\n";
					echo "\t * Id: [{$oneCustomer->Id}]\n";
					echo "\t * Active: [{$oneCustomer->Active}]\n";
					echo "\n";*/

                    $items[] = (array)$item;
                }

                break;
            }
        }

        return $items;
    }

    public static function fetchCustomers()
    {
        $items = [];

        if ($ds = Quickbooks::ds()) {
            $i = 0;

            while (1) {
                $allCustomers = $ds->FindAll('Customer', $i, 1000);
                $error = $ds->getLastError();
                if ($error) {
                    $msg = "The Status code is: " . $error->getHttpStatusCode() . "\n";
                    $msg .= "The Helper message is: " . $error->getOAuthHelperError() . "\n";
                    $msg .= "The Response message is: " . $error->getResponseBody() . "\n";
                    static::$errors[] = $msg;
                    return false;
                }
                if (!$allCustomers || (0 == count($allCustomers))) {
                    break;
                }
                foreach ($allCustomers as $oneCustomer) {
                    /*echo "Customer[" . ($i++) . "]: {$oneCustomer->DisplayName}\n";
					echo "\t * Id: [{$oneCustomer->Id}]\n";
					echo "\t * Active: [{$oneCustomer->Active}]\n";
					echo "\n";*/

                    $text = "[{$oneCustomer->Id}] {$oneCustomer->DisplayName} (Active: {$oneCustomer->Active})";
                    $value = $oneCustomer->Id;

                    $items[] = [
                        'id' => $oneCustomer->Id,
                        'name' => $oneCustomer->DisplayName,
                        'active' => $oneCustomer->Active,
                        'text' => $text,
                        'value' => $value,
                    ];
                }

                break;
            }
        }

        return $items;
    }

    public static function fetchAccounts()
    {
        $items = [];

        if ($ds = Quickbooks::init()) {
            $i = 0;

            while (1) {
                $results = $ds->FindAll('Account', $i, 500);
                $error = $ds->getLastError();
                if ($error) {
                    $msg = "The Status code is: " . $error->getHttpStatusCode() . "\n";
                    $msg .= "The Helper message is: " . $error->getOAuthHelperError() . "\n";
                    $msg .= "The Response message is: " . $error->getResponseBody() . "\n";
                    static::$errors[] = $msg;
                    return false;
                }
                if (!$results || (0 == count($results))) {
                    break;
                }
                foreach ($results as $row) {
                    /*echo "Customer[" . ($i++) . "]: {$oneCustomer->DisplayName}\n";
					echo "\t * Id: [{$oneCustomer->Id}]\n";
					echo "\t * Active: [{$oneCustomer->Active}]\n";
					echo "\n";*/

                    $text = "[{$row->Id}] {$row->Name}, Type:{$row->AccountType}, SubType:{$row->AccountSubType} (Active: {$row->Active})";
                    $value = $row->Id;

                    $items[] = [
                        'id' => $row->Id,
                        'name' => $row->DisplayName,
                        'active' => $row->Active,
                        'type' => $row->AccountType,
                        'subType' => $row->AccountSubType,
                        'text' => $text,
                        'value' => $value,
                    ];
                }

                break;
            }
        }

        return $items;
    }

    public static function getCustomerMapping($client)
    {
        $user = auth()->user();
        $qboCustomerId = Setting::get("qboCustomerId/{$client}", $user) ?: '';
        return $qboCustomerId;
    }

    public static function setCustomerMapping($client, $custId)
    {
        $user = auth()->user();
        return Setting::set("qboCustomerId/{$client}", $custId, $user);
    }

    public static function errors()
    {
        return static::$errors;
    }

    public function fetch()
    {
    }

    public function dump()
    {
    }
}
