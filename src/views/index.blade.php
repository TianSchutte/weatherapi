<html lang="en">
<head>
    <title>Weather Report</title>
</head>
<body>
<h2>Add Location</h2>
<form method="post" action="/weatherapi/set/{{$data[0]['user_id']}}">
    @csrf
    <label for="location">Location</label>
    <input type="text" id="location" name="location" required>
    <label for="days">Days</label>
    <input type="text" id="days" name="days">

    <button type="submit">Add</button>
</form>


{{--Remove first foreach and remove [] from controllerthats returning #refactor--}}
@foreach($data as $item)
    {{--    add route, take to new view - view should display hourly forcast of day?--}}
    <h2>{{ $item['city_name'] }}</h2>

    <form method="post" action="/weatherapi/delete/{{$item['user_id']}}/{{$item['city_id']}}">
        @csrf
        @method('DELETE')
        <button type="submit">delete</button>
    </form>

    <h2>Current Weather</h2>
    @foreach($item['weather_data'] as $currentWeather)
        <ul>
            <li>Temperature: {{ $currentWeather['current']['temp_c'] }}°C</li>
            <li>Condition: {{ $currentWeather['current']['condition']['text'] }}</li>
            <li>Wind: {{ $currentWeather['current']['wind_kph'] }} km/H {{ $currentWeather['current']['wind_dir'] }}</li>
            <li>Humidity: {{ $currentWeather['current']['humidity'] }}%</li>
        </ul>


        <h2>Today's Forecast</h2>
        @foreach($currentWeather['forecast']['forecastday'] as $forecastday)
            <ul>
                <li>High: {{ $forecastday['day']['maxtemp_c'] }}°C</li>
                <li>Low: {{  $forecastday['day']['mintemp_c'] }}°C</li>
                <li>Condition: {{  $forecastday['day']['condition']['text'] }}</li>
            </ul>

            <h2>{{$forecastday['date']}} Hourly Forecast</h2>

            <table>
                <thead>
                <tr>
                    <th>Time</th>
                    <th></th>
                    <th>Temperature</th>
                    <th>Conditions</th>
                    <th>Wind</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($forecastday['hour'] as $hour)
                    <tr>
                        <td>{{ $hour['time'] }}</td>
                        <td><img src="{{ $hour['condition']['icon'] }}" width="50" height="50"></td>
                        <td>{{ $hour['temp_c'] }}°C</td>
                        <td>{{ $hour['condition']['text'] }}</td>
                        <td>{{ $hour['wind_kph'] }} km/h</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @endforeach
    @endforeach
@endforeach
</body>
</html>


