<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;
use Illuminate\Validation\ValidationException;

class MovieController extends Controller
{
    public function movies(): Response {
        return response(Movie::all());
    }

    public function get(Movie $movie): Response {
        return response($movie);
    }

    public function upsert(Movie $movie, Request $request): Response {
        try {
            $request->validate([
                'name' => 'required|string',
                'year' => 'required|integer',
            ]);
        }  catch (ValidationException $e) {
            return response('Invalid input data', 422);
        }
        $movie->name = $request->input('name');
        $movie->year = $request->input('year');
        $movie->save();

        return response(['message' => 'Upsert successful', 'movies' => Movie::all()]);
    }

    public function delete(Movie $movie): Response {
        $movie->delete();
        return response(['message' => 'Delete successful', 'movies' => Movie::all()]);
    }
}
