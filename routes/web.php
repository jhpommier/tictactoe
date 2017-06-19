<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use Illuminate\Http\Request;

Route::get('/', function () {
    return view('tictactoe');
});

Route::get('game/{id}', 'GameController@show');

Route::post('/new-game', function(Request $request) {
    // @todo: use api game POST insteed
    $validator = Validator::make($request->all(), [
        'player1_name' => 'required|max:255',
        'player2_name' => 'required|max:255',
        'board_size' => 'integer|min:3|max:10',
    ]);
    if ($validator->fails()) {
        return back()
            ->withErrors($validator);
    }
    $game = new \App\Game;
    $game->player1_name = $request->player1_name;
    $game->player2_name = $request->player2_name;
    $game->player1_pawn = 'X';
    $game->player2_pawn = 'O';

    $boardState = [];
    for ($i = 0; $i < $request->board_size; $i++) {
        array_push($boardState, []);
        for ($j = 0; $j < $request->board_size; $j++) {
            $boardState[$i][$j] = null;
        }
    }
    $game->board = json_encode($boardState);
    $game->last_action = null;
    $game->board_state = json_encode(['label' => "RUNNING"]);

    // @todo : handle model save error
    $game->save();

    return redirect('/game/' . $game->id);
});
