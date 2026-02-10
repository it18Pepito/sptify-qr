<?php

namespace App\Controllers;

use Flight;
use GuzzleHttp\Client;
use GuzzleHttp\Promise;
use Jenssegers\Agent\Agent;
use PDO;

class DownloadController
{
    protected $db;
    protected $client;

    public function __construct()
    {
        $this->db = Flight::db();
        $this->client = new Client([
            'timeout'  => 2.0,
            'headers' => ['User-Agent' => 'PepitoLoyaltyRedirect/1.0 (admin@pepito.co.id)']
        ]);
    }

    public function index()
    {
        $req = Flight::request();
        $clientIp = $req->ip; // Flight handles X-Forwarded-For if configured, or use $_SERVER
        // Fallback for IP if Flight doesn't catch proxy
        if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $clientIp = trim(explode(',', $_SERVER['HTTP_X_FORWARDED_FOR'])[0]);
        }

        // 1. User Agent Parsing
        $agent = new Agent();
        $agent->setUserAgent($req->user_agent);
        $os = $agent->platform();
        $osVersion = $agent->version($os);
        $browser = $agent->browser();
        $deviceType = $agent->isDesktop() ? 'Desktop' : ($agent->isTablet() ? 'Tablet' : 'Mobile');

        // In-App Browser Detection
        $uaString = $req->user_agent;
        $isInApp = (bool)preg_match('/FBAN|FBAV|Instagram|Twitter|LinkedIn|WhatsApp|Line/i', $uaString);

        // 2. Determine Redirect
        $redirectTo = 'play_store';
        // JS logic: /iOS|Mac OS/i.test(osName) && !/Macintosh/i.test(osName)
        // Agent 'Mac OS X' is usually desktop. 'iOS' is mobile.
        // We can check isRobot/isPhone/isTablet or platform name.
        if ($agent->is('iPhone') || $agent->is('iPad') || $agent->is('iPod') || $agent->is('iOS') || ($agent->is('Mac OS') && !$agent->isDesktop())) {
            $redirectTo = 'app_store';
        } elseif ($agent->isAndroidOS()) {
            $redirectTo = 'play_store';
        }

        // 3. GeoIP & OSM (Async-like)
        // Since we don't have local GeoIP DB, we use ipinfo for initial Location (Lat/Lon)
        // AND details. 
        // Node app: local geoip -> lat/lon -> OSM.
        // PHP: ipinfo -> lat/lon -> OSM.

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
            'store_code' => $req->query->store_code ?? null,
            'campaign' => $req->query->campaign ?? null,
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
        $columns = implode(", ", array_keys($logData));
        $placeholders = implode(", ", array_fill(0, count($logData), "?"));

        try {
            $stmt = $this->db->prepare("INSERT INTO app_download_logs ($columns) VALUES ($placeholders)");
            $stmt->execute(array_values($logData));
        } catch (\PDOException $e) {
            // Log error, but continue
            error_log("DB Error: " . $e->getMessage());
        }

        // Render View
        Flight::render('DownloadApp/index');

        /* 
        // Contoh jika ingin mengambil HTML sebagai string (tidak langsung echo):
        Flight::render('DownloadApp/index', [], 'content_html');
        $html = Flight::get('content_html');
        // echo $html;
        */
    }
}
