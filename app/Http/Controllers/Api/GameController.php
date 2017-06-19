<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Game;
use Illuminate\Http\Request;

class GameController extends Controller
{
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Game  $game
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Game $game)
    {
        $board = json_decode($game->board);

        $boardAction = json_decode($request->getContent());
        $x = $boardAction->cell->x;
        $y = $boardAction->cell->y;
        
        if ($board[$x][$y] !== null) {
            $response = [
                'error' => 'invalid move ' . $board[$x][$y]
            ];
            $response = json_encode($response);
            return response()->json($response);
        }

        $player = $boardAction->player;
        $playerPawn = $game[$player . '_pawn' ];

        $board[$x][$y] = $playerPawn;

        $boardMaxIndex = count($board) - 1;

        // line check
        $lineWin = $y;
        for ($i = 0; $i <= $boardMaxIndex; $i++) {
            if ($board[$i][$y] !== $playerPawn) {
                $lineWin = false;
                break;
            }
        }
        // column check
        $columnWin = $x;
        for ($i = 0; $i <= $boardMaxIndex; $i++) {
            if ($board[$x][$i] !== $playerPawn) {
                $columnWin = false;
                break;
            }
        }
        // fisrt diag check
        $diag1Win = true;
        for ($i = 0; $i <= $boardMaxIndex; $i++) {
            if ($board[$i][$i] !== $playerPawn) {
                $diag1Win = false;
                break;
            }
        }

        //second diag check
        $diag2Win = true;
        for ($i = 0; $i <= $boardMaxIndex; $i++) {
            if ($board[$i][$boardMaxIndex - $i] !== $playerPawn) {
                $diag2Win = false;
                break;
            }
        }

        // draw check
        $draw = true;
        for ($i = 0; $i <= $boardMaxIndex; $i++) {
            if ($draw === false) {
                break;
            }
            for ($j = 0; $j <= $boardMaxIndex; $j++) {
                if ($board[$i][$j] === null) {
                    $draw = false;
                    break;
                }
            }
        }

        $boardStateLabel = 'RUNNING';

        if ($lineWin !== false || $columnWin !== false || $diag1Win || $diag2Win) {
            $boardStateLabel = 'WINNER';
            $game[$player . '_score'] = $game[$player . '_score'] + 1;
        }

        if ($draw) {
            $boardStateLabel = 'DRAW';
        }

        $lastAction = [
            'x' => $x,
            'y' => $y,
            'player' => $player,
            'pawn' => $playerPawn
        ];

        $boardState = [
            'label' => $boardStateLabel,
            'lineWin'=> $lineWin,
            'columnWin'=> $columnWin,
            'diag1Win'=> $diag1Win,
            'diag2Win'=> $diag2Win,
            'draw' => $draw
        ];
        
        $game->last_action = json_encode($lastAction);
        $game->board_state = json_encode($boardState);
        $game->board = json_encode($board);
        $game->save();

        $response = [
            'board' => $board,
            'board_state' => $boardState,
            'last_action' => $lastAction
        ];

        $response = json_encode($response);
        return response()->json($response);
    }
}
