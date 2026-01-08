<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Http\Services\JokeService;

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

    public function getJokes(Request $req, JokeService $js): JsonResponse
    {
        $limit = $req->input('limit') ?? 3;
        if($limit > 10){
            return response()->json([
                'errors' => 'Limit is invalid!',
            ], JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
        }
        return response()->json($js->getJokes($limit));
    }
}
