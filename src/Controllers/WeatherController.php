<?php

namespace tian\weatherapi\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use tian\weatherapi\Models\UserCity;
use tian\weatherapi\Models\WeatherStat;

class WeatherController
{
    /**
     * @var string
     */
    protected $apiKey;

    public function __construct()
    {
        $this->apiKey = env('WEATHER_API_KEY', '5664a4f8e4114b2889b95701231402');
    }

    /**
     * @param $id
     * @return JsonResponse | mixed
     */
    public function show($id)
    {
        $data = [];
        $userCities = UserCity::with('weatherStats')->where('user_id', $id)->get();

        foreach ($userCities as $userCity) {
            foreach ($userCity->weatherStats as $weatherStat) {
                $data[] = [
                    'city_name' => $userCity->city_name,
                    'weather_data' => $weatherStat->weather_data,
                    'city_id' => $weatherStat->city_id,
                    'user_id'=>$id
                ];
            }
        }
        return view('weatherapi::index', ['data'=>$data]);

//        return response()->json($data);
    }

    /**
     * @param Request $request
     * @param $id
     * @return JsonResponse|mixed
     */
    public function store(Request $request, $id)
    {
        $apiResponse = $this->getApiResponse($request);

        if (isset($apiResponse['error'])) {
            return $apiResponse;
        }

        $city_name = implode(', ', [
            $apiResponse['location']['name'],
            $apiResponse['location']['region'],
            $apiResponse['location']['country']
        ]);

        // replace last same city insert in weather table with new data
        $existingCity = UserCity::where('user_id', $id)
            ->where('city_name', $city_name)
            ->first();

        if ($existingCity) {
            $this->updateWeatherStats($existingCity, $apiResponse);
        }

        // If no existing UserCity record was found, create a new one
        $cityData = UserCity::create([
            'city_name' => $city_name,
            'user_id' => $id,
        ]);

        $weatherData = WeatherStat::create([
            'city_id' => $cityData->id,
            'weather_data' => [$apiResponse],
        ]);

//        return response()->json([
//            'User City' => $cityData,
//            'City Weather' => $weatherData
//        ]);
        return redirect()->back();

    }

    /**
     * @param $id
     * @param $city_id
     * @return JsonResponse|mixed
     */
    public function destroy($id, $city_id)
    {
        $weatherDeleted = WeatherStat::where('city_id', $city_id)->delete();
        $userCityDeleted = UserCity::where('id', $city_id)->where('user_id', $id)->delete();

//        if ($weatherDeleted && $userCityDeleted) {
//            return response()->json([
//                'status' => 'Data removal successful'
//            ]);
//        } else {
//            return response()->json([
//                'status' => 'Data removal failure'
//            ]);
//        }
        return redirect()->back();
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    private function getApiResponse(Request $request)
    {
        $validatedData = $request->validate([
            'location' => 'required|string',
            'days' => 'nullable|integer|min:0|max:7',
        ]);

        $location = $validatedData['location'];
        $days = $validatedData['days'] ?? 1;

        $url = "http://api.weatherapi.com/v1/forecast.json?key={$this->apiKey}&q={$location}&days={$days}&aqi=no";
        return Http::get($url)->json();
    }

    /**
     * @param $existingCity
     * @param $data
     * @return JsonResponse
     */
    private function updateWeatherStats($existingCity, $data)
    {
        // Update the existing WeatherStat record with the new weather data
        $existingWeather = $existingCity->weatherStats()->latest()->first();
        $existingWeather->update([
            'weather_data' => [$data],
        ]);

        return response()->json([
            'User City' => $existingCity,
            'City Weather' => $existingWeather,
        ]);
    }

}

