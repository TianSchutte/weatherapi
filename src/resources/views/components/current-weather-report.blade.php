<div>
    <h2>{{ $item['city_name'] }}</h2>

    <form method="post" action="{{route('weather.delete',  $item['city_id'])}}">
        @csrf
        @method('DELETE')
        <button type="submit">Delete</button>
    </form>

    <h2>Current Weather</h2>

    @foreach ($item['weather_data'][0] as $key => $value)
        @if ($key == 'current')
            <ul>
                <li>Temperature: {{ $value['temp_c'] }}°C</li>
                <li>Condition: {{ $value['condition']['text'] }}</li>
                <li>Wind: {{ $value['wind_kph'] }} km/h {{ $value['wind_dir'] }}</li>
                <li>Humidity: {{ $value['humidity'] }}%</li>
            </ul>
        @endif
    @endforeach


</div>
