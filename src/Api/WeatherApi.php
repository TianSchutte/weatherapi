<?php

namespace Tian\Weatherapi\Api;


use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WeatherApi
{

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function getApiResponse(Request $request)
    {
        try {
            $apiKey = Config::get('weatherapi.WEATHER_API_KEY');
            $apiUrl = Config::get('weatherapi.WEATHER_URL');
        } catch (\Exception $e) {
            Log::error($e);
        }

        $request->validate([
            'location' => 'required|string',
            'days' => 'nullable|integer|min:0|max:7',
        ]);

        $location = $request->input('location');
        $days = $request->input('days') ?? 1;

        $url = "{$apiUrl}?key={$apiKey}&q={$location}&days={$days}&aqi=no";

        if (filter_var($url, FILTER_VALIDATE_URL) === false) {
            Log::error('URL is Invalid', [
                'url' => $url
            ]);
        }

        return Http::get($url)->json();
    }
}
