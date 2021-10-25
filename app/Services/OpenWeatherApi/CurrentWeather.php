<?php

namespace App\Services\OpenWeatherApi;

use GuzzleHttp\Client;

class CurrentWeather implements IOpenWeather
{
    public static function getWeather(): array
    {
        $client = new Client();
        $request = $client->request('GET', 'api.openweathermap.org/data/2.5/forecast?q=Kharkiv,ua&units=metric&appid=0eb04b771e873f6db10dabb8c0697348');
        $data = json_decode($request->getBody());
        return [
          'temperature' => $data->list[0]->main->temp,
          'weather_description' => $data->list[0]->weather[0]->description
        ];
    }
}
