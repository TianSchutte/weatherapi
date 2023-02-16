<div>

    @foreach($item['weather_data'][0]['forecast']['forecastday'] as $forecastday)
        <h2> General Forecast</h2>
        <ul>
            <li>High: {{$forecastday['day']['maxtemp_c']}}°C</li>
            <li>Low: {{$forecastday['day']['mintemp_c']}}°C</li>
            <li>Condition: {{$forecastday['day']['condition']['text']}}</li>
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
