<?php

namespace App\Services;

use GuzzleHttp\Client;

class CurrentWeather implements IOpenWeather
{
    public $temperature;
    public $weather;
    public function __construct()
    {
        $client = new Client();
        $request = $client->request('GET', 'api.openweathermap.org/data/2.5/forecast?q=Kharkiv,ua&units=metric&appid=0eb04b771e873f6db10dabb8c0697348');
        $data = json_decode($request->getBody());
        $this->temperature = $data->list[0]->main->temp;
        $this->weather = $data->list[0]->weather[0]->description;
    }
}
