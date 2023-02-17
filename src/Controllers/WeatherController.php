<?php

namespace Tian\Weatherapi\Controllers;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Tian\Weatherapi\Api\WeatherApi;
use Tian\Weatherapi\Models\UserCity;
use Tian\Weatherapi\Models\WeatherStat;

class WeatherController extends Controller
{
    /**
     * @var WeatherApi
     */
    protected $weatherApi;

    /**
     * @param WeatherApi $weatherApi
     */
    public function __construct(WeatherApi $weatherApi)
    {
        $this->weatherApi = $weatherApi;
//        $this->middleware('auth');
    }

    /**
     * @return View
     */
    public function show()
    {
        $user_id = auth()->id();
        $data = [];

        try {
            $userCities = UserCity::with('weatherStats')
                ->where('user_id', $user_id)
                ->get();

        } catch (\Exception $e) {
            return view('weatherapi::index', [
                'data' => $data,
                'user' => auth()->user()
            ])->with('message', 'No cities found');
        }

        foreach ($userCities as $userCity) {
            foreach ($userCity->weatherStats as $weatherStat) {
                $data[] = [
                    'city_name' => $userCity->city_name,
                    'weather_data' => $weatherStat->weather_data,
                    'city_id' => $weatherStat->city_id,
                ];
            }
        }

        return view('weatherapi::index', [
            'data' => $data,
            'user' => auth()->user()
        ]);
    }

    /**
     * @param Request $request
     * @return View
     */
    public function store(Request $request)
    {
        $user_id = auth()->id();

        $validatedData = $request->validate([
            'location' => 'required|string',
            'days' => 'nullable|integer|min:0|max:7',
        ]);

        $apiResponse = $this->weatherApi->getApiResponse($request);

        if (isset($apiResponse['error'])) {
            return $this->show()->with('message', $apiResponse['error']['message']);
        }

        $city_name = implode(', ', [
            $apiResponse['location']['name'],
            $apiResponse['location']['region'],
            $apiResponse['location']['country']
        ]);

        //DB:transaction for in order to rollback if either fails
        DB::transaction(function () use ($user_id, $city_name, $apiResponse) {

            // replace last same city insert in weather table with new data
            $existingCity = UserCity::where('user_id', $user_id)
                ->where('city_name', $city_name)
                ->first();

            if ($existingCity) {
                $existingWeather = $existingCity->weatherStats()->latest()->first();
                $existingWeather->update([
                    'weather_data' => [$apiResponse],
                ]);

            } else {
                // If no existing UserCity record was found, create a new one
                $cityData = UserCity::create([
                    'city_name' => $city_name,
                    'user_id' => $user_id,
                ]);

                WeatherStat::create([
                    'city_id' => $cityData->id,
                    'weather_data' => [$apiResponse],
                ]);
            }
        });

        return $this->show()->with('message', 'City added successfully');
    }

    /**
     * @param $city_id
     * @return View
     */
    public function destroy($city_id)
    {
        $user_id = auth()->id();

        try {
            $weatherDeleted = WeatherStat::where('city_id', $city_id)->delete();
            $userCityDeleted = UserCity::where('id', $city_id)->where('user_id', $user_id)->delete();

            if ($weatherDeleted == 0 || $userCityDeleted == 0) {
                return $this->show()->with('message', 'Failed to delete city');
            }
            return $this->show()->with('message', 'Deleted city successfully');

        } catch (Exception $e) {
            return $this->show()->with('message', 'Failed to delete city due to: ' . $e->getMessage());

        }
    }
}

