<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Game;

class GameController extends Controller
{
    public function show($id)
    {
        return view('game', ['game' => Game::findOrFail($id)]);
    }
}
