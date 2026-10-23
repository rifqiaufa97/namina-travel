<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VisitorTracking extends Model
{
    protected $table = 'visitor_tracking';

    protected $fillable = [
        'ip_address',
        'user_agent',
        'page_visited',
        'referrer',
        'device_type',
        'browser',
        'platform',
        'country',
        'city'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public static function recordVisit($request, $page)
    {
        $ip = $request->ip();
        $userAgent = $request->userAgent();
        $referrer = $request->header('referer');
        
        // Skip recording for localhost or admin routes
        if (in_array($ip, ['127.0.0.1', '::1']) || str_starts_with($page, '/admin')) {
            return;
        }

        // Parse user agent
        $browser = self::getBrowser($userAgent);
        $platform = self::getPlatform($userAgent);
        $deviceType = self::getDeviceType($userAgent);

        // Get location (you can implement this using a service like MaxMind GeoIP2)
        $country = null;
        $city = null;

        self::create([
            'ip_address' => $ip,
            'user_agent' => $userAgent,
            'page_visited' => $page,
            'referrer' => $referrer,
            'device_type' => $deviceType,
            'browser' => $browser,
            'platform' => $platform,
            'country' => $country,
            'city' => $city,
        ]);
    }

    private static function getBrowser($userAgent)
    {
        $browsers = [
            'Opera' => 'Opera',
            'Edg' => 'Edge',
            'Chrome' => 'Chrome',
            'Safari' => 'Safari',
            'Firefox' => 'Firefox',
            'MSIE' => 'Internet Explorer',
        ];

        foreach ($browsers as $key => $browser) {
            if (strpos($userAgent, $key) !== false) {
                return $browser;
            }
        }
        return 'Unknown';
    }

    private static function getPlatform($userAgent)
    {
        $platforms = [
            'Windows' => 'Windows',
            'Macintosh' => 'Mac',
            'Linux' => 'Linux',
            'Android' => 'Android',
            'iPhone' => 'iOS',
            'iPad' => 'iOS',
        ];

        foreach ($platforms as $key => $platform) {
            if (strpos($userAgent, $key) !== false) {
                return $platform;
            }
        }
        return 'Unknown';
    }

    private static function getDeviceType($userAgent)
    {
        $mobiles = ['Mobile', 'Android', 'iPhone', 'iPad', 'iPod', 'Windows Phone'];
        foreach ($mobiles as $mobile) {
            if (strpos($userAgent, $mobile) !== false) {
                return 'Mobile';
            }
        }
        return 'Desktop';
    }
}
