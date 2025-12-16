<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class JokeController extends Controller
{
    //
    /**
     * Show jokes to user.
     */
    public function getThree(Request $req)
    {
        $joke_api = getenv('JOKE_API') ?? 'https://official-joke-api.appspot.com/';
        $response = Http::get("$joke_api/jokes/programming/ten");

        $jokes =[];

        if($response->successful() && $response->ok()){
            $jokes = $response->json();
        }

        return response()->json(array_slice($jokes, 0, (int)getenv('JOKE_LIMIT') ?? 3));
    }
}
