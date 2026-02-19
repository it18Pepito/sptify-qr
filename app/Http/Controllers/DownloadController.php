<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use GuzzleHttp\Client;
use Jenssegers\Agent\Agent;
use PDO;

class DownloadController extends Controller
{
    protected $client;
    protected $db;

    public function __construct()
    {
        $this->client = new Client([
            'timeout'  => 2.0,
            'headers' => ['User-Agent' => 'PepitoLoyaltyRedirect/1.0 (admin@pepito.co.id)']
        ]);
    }

    // Untuk LoyalId Default
    public function index(Request $request)
    {
        $clientIp = $request->ip();
        // Fallback for IP if not caught by trusted proxy logic
        // if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        //     $clientIp = trim(explode(',', $_SERVER['HTTP_X_FORWARDED_FOR'])[0]);
        // }
        $appList = DB::table('app_lists')->where('app_slug', 'pepi-plus')->first();

        // 1. User Agent Parsing
        $agent = new Agent();
        $agent->setUserAgent($request->header('User-Agent'));
        $os = $agent->platform();
        $osVersion = $agent->version($os);
        $browser = $agent->browser();
        $deviceType = $agent->isDesktop() ? 'Desktop' : ($agent->isTablet() ? 'Tablet' : 'Mobile');

        // In-App Browser Detection
        $uaString = $request->header('User-Agent');
        $isInApp = (bool)preg_match('/FBAN|FBAV|Instagram|Twitter|LinkedIn|WhatsApp|Line/i', $uaString);

        // 2. Determine Redirect
        $redirectTo = 'play_store';
        if ($agent->is('iPhone') || $agent->is('iPad') || $agent->is('iPod') || $agent->is('iOS') || ($agent->is('Mac OS') && !$agent->isDesktop())) {
            $redirectTo = 'app_store';
        } elseif ($agent->isAndroidOS()) {
            $redirectTo = 'play_store';
        }

        // 3. GeoIP & OSM (Async-like)
        $promises = [
            'ipinfo' => $this->client->getAsync("https://ipinfo.io/{$clientIp}/json")
        ];

        // Wait for IP Info first to get coordinates for OSM
        $ipInfo = null;
        $lat = null;
        $lon = null;

        try {
            $response = $promises['ipinfo']->wait();
            $ipInfo = json_decode($response->getBody(), true);
            if (isset($ipInfo['loc'])) {
                [$lat, $lon] = explode(',', $ipInfo['loc']);
            }
        } catch (\Exception $e) {
            // Log error or ignore
        }

        // Now call OSM if we have coordinates
        $addressDetails = [];
        if ($lat && $lon) {
            try {
                $osmRes = $this->client->get("https://nominatim.openstreetmap.org/reverse", [
                    'query' => [
                        'format' => 'json',
                        'lat' => $lat,
                        'lon' => $lon,
                        'zoom' => 18,
                        'addressdetails' => 1
                    ]
                ]);
                $osmData = json_decode($osmRes->getBody(), true);
                $addressDetails = $osmData['address'] ?? [];
            } catch (\Exception $e) {
                // Ignore
            }
        }

        // Prepare Data
        $parts = isset($ipInfo['org']) ? explode(' ', $ipInfo['org'], 2) : [];
        $asn = $parts[0] ?? null;
        $isp = $parts[1] ?? null;

        $logData = [
            'app_list_id' => $appList != null ? $appList->id : null,
            'store_code' => $request->query('store_code'),
            'campaign' => $request->query('campaign'),
            'ip' => $clientIp,
            'country' => $addressDetails['country'] ?? $ipInfo['country'] ?? null,
            'country_alpha_2' => isset($addressDetails['country_code']) ? strtoupper($addressDetails['country_code']) : ($ipInfo['country'] ?? null),
            'province' => $addressDetails['state'] ?? $ipInfo['region'] ?? null,
            'regency' => $addressDetails['city'] ?? $ipInfo['city'] ?? null,
            'district' => $addressDetails['city_district'] ?? $addressDetails['district'] ?? null,
            'subdistrict' => $addressDetails['suburb'] ?? $addressDetails['neighbourhood'] ?? $addressDetails['village'] ?? null,
            'street' => $addressDetails['road'] ?? $addressDetails['pedestrian'] ?? null,
            'postal_code' => $addressDetails['postcode'] ?? $ipInfo['postal'] ?? null,
            'latitude' => $lat,
            'longitude' => $lon,
            'isp' => $isp,
            'asn' => $asn,
            'os' => $os,
            'os_version' => $osVersion,
            'device_type' => $deviceType,
            'browser' => $browser,
            'is_in_app_browser' => $isInApp ? 1 : 0,
            'redirect_to' => $redirectTo,
            'result' => 'success',
            'timezone' => $ipInfo['timezone'] ?? null
        ];

        // Sanitization (nullify empty strings or 'undefined')
        foreach ($logData as $key => $val) {
            if ($val === '' || $val === 'undefined' || $val === 'Unknown') {
                $logData[$key] = null;
            }
        }

        // Insert into DB
        try {
            DB::table('app_download_logs')->insert($logData);
        } catch (\Exception $e) {
            // Log error, but continue
            error_log("DB Error: " . $e->getMessage());
        }

        // Render View
        return view('download-app.pepiplus');
    }
    public function bySlug(Request $request, string $slug)
    {

        if (!preg_match('/^[a-z0-9\-]+$/', $slug)) {
            abort(404);
        }


        $clientIp = $request->ip();
        // if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        //     $clientIp = trim(explode(',', $_SERVER['HTTP_X_FORWARDED_FOR'])[0]);
        // }


        $agent = new \Jenssegers\Agent\Agent();
        $agent->setUserAgent($request->header('User-Agent'));

        $os = $agent->platform();
        $osVersion = $agent->version($os);
        $browser = $agent->browser();
        $deviceType = $agent->isDesktop()
            ? 'Desktop'
            : ($agent->isTablet() ? 'Tablet' : 'Mobile');

        $uaString = $request->header('User-Agent');
        $isInApp = (bool) preg_match(
            '/FBAN|FBAV|Instagram|Twitter|LinkedIn|WhatsApp|Line/i',
            $uaString
        );


        $redirectTo = 'play_store';

        if (
            $agent->is('iPhone') ||
            $agent->is('iPad') ||
            $agent->is('iPod') ||
            $agent->is('iOS') ||
            ($agent->is('Mac OS') && !$agent->isDesktop())
        ) {
            $redirectTo = 'app_store';
        } elseif ($agent->isAndroidOS()) {
            $redirectTo = 'play_store';
        }


        $appList = DB::table('app_lists')
            ->where('app_slug', $slug)
            ->first();

        if (!$appList) {
            abort(404, 'Application not found');
        }


        $store = DB::table('app_types')
            ->where('app_list_id', $appList->id)
            ->where('store_type', $redirectTo)
            ->first();

        if (!$store || empty($store->url)) {
            abort(404, 'Application store not found');
        }


        $ipInfo = null;
        $lat = null;
        $lon = null;
        $addressDetails = [];

        try {
            $response = $this->client->get(
                "https://ipinfo.io/{$clientIp}/json"
            );
            $ipInfo = json_decode($response->getBody(), true);

            if (!empty($ipInfo['loc'])) {
                [$lat, $lon] = explode(',', $ipInfo['loc']);
            }
        } catch (\Exception $e) {
        }

        if ($lat && $lon) {
            try {
                $osmRes = $this->client->get(
                    "https://nominatim.openstreetmap.org/reverse",
                    [
                        'query' => [
                            'format' => 'json',
                            'lat' => $lat,
                            'lon' => $lon,
                            'zoom' => 18,
                            'addressdetails' => 1
                        ]
                    ]
                );
                $osmData = json_decode($osmRes->getBody(), true);
                $addressDetails = $osmData['address'] ?? [];
            } catch (\Exception $e) {
            }
        }


        $parts = isset($ipInfo['org'])
            ? explode(' ', $ipInfo['org'], 2)
            : [];

        $logData = [
            'app_list_id' => $appList->id,
            'app_slug' => $slug,
            'store_code' => $request->query('store_code'),
            'campaign' => $request->query('campaign'),
            'ip' => $clientIp,
            'country' => $addressDetails['country'] ?? $ipInfo['country'] ?? null,
            'country_alpha_2' => isset($addressDetails['country_code'])
                ? strtoupper($addressDetails['country_code'])
                : ($ipInfo['country'] ?? null),
            'province' => $addressDetails['state'] ?? $ipInfo['region'] ?? null,
            'regency' => $addressDetails['city'] ?? $ipInfo['city'] ?? null,
            'district' => $addressDetails['city_district']
                ?? $addressDetails['district']
                ?? null,
            'subdistrict' => $addressDetails['suburb']
                ?? $addressDetails['neighbourhood']
                ?? $addressDetails['village']
                ?? null,
            'street' => $addressDetails['road']
                ?? $addressDetails['pedestrian']
                ?? null,
            'postal_code' => $addressDetails['postcode'] ?? $ipInfo['postal'] ?? null,
            'latitude' => $lat,
            'longitude' => $lon,
            'isp' => $parts[1] ?? null,
            'asn' => $parts[0] ?? null,
            'os' => $os,
            'os_version' => $osVersion,
            'device_type' => $deviceType,
            'browser' => $browser,
            'is_in_app_browser' => $isInApp ? 1 : 0,
            'redirect_to' => $redirectTo,
            'result' => 'success',
            'timezone' => $ipInfo['timezone'] ?? null
        ];

        foreach ($logData as $key => $val) {
            if ($val === '' || $val === 'undefined' || $val === 'Unknown') {
                $logData[$key] = null;
            }
        }

        try {
            DB::table('app_download_logs')->insert($logData);
        } catch (\Exception $e) {
            error_log('DB Error: ' . $e->getMessage());
        }


        return view('download-app.index', [
            'redirectUrl' => $store->url,
            'storeType'   => $redirectTo,
            'app'         => $appList,
        ]);
    }
}
