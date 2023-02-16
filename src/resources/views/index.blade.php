@extends('weatherapi::layout')

@section('weatherapi::content')
    <h2>Welcome To Your Weather Report <i>{{$user->name}}</i></h2>
    <h2>Add Location</h2>

    <form method="post" action="{{route('weather.set')}}">
        @csrf
        <label for="location">Location</label>
        <input type="text" id="location" name="location" required>
        <label for="days">Days</label>
        <input type="text" id="days" name="days">
        <button type="submit">Add</button>
    </form>

    @if(isset($message)>0)
        <p>{{$message}}</p>
    @endif

    @if(isset($data)>0)
        @foreach($data as $item)
            @include('weatherapi::current-weather-report' )
            @include('weatherapi::days-weather-report' )
        @endforeach
    @endif
@endsection
