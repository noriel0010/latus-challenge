<?php

namespace App\Http\Services;

use Illuminate\Support\Facades\Http;

class JokeService {

    public function __construct(){

    }

    public function getJokes($limit = 3)
    {
        $joke_api = env('JOKE_API') ?? 'https://official-joke-api.appspot.com/';
        $response = Http::get("$joke_api/jokes/programming/ten");

        $jokes =[];

        if($response->successful() && $response->ok()){
            $jokes = $response->json();
        }

        return array_slice($jokes, 0, (int)$limit ?? 3);
    }
}