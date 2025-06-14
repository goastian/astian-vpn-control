<?php


if (!function_exists('locationData')) {
    function locationData(string $ip)
    {
        $url = "http://ip-api.com/json/{$ip}?fields=status,country,countryCode,regionName,city,query";

        $response = Http::get($url);

        if ($response->successful() && $response['status'] === 'success') {
            return [
                'ip' => $response['query'],
                'country' => $response['country'],
                'country_code' => $response['countryCode'],
                'region' => $response['regionName'],
                'city' => $response['city'],
            ];
        }

        return null;
    }
}
