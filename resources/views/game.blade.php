@extends('layout')

@section('content')
    <div>
        <div id="player1" class="player" style="float:left;">
            {{ $game->player1_name }} ({{ $game->player1_pawn }})
        </div>
        <div id="player2" class="player" style="float:right;">
            {{ $game->player2_name }} ({{ $game->player2_pawn }})
        </div>
        <div style="clear: both;"></div>
    </div>
    <div id="new-game" style="display:none">
        <a href="/">Nouvelle partie</a>
    </div>
    <div style="overflow-x:auto;margin-top:30px;">
        <table id="tic-tac-toe-table"></table>
    </div>

    <script type='text/javascript'>
        game = {};
        game.id = {{ $game->id }};
        game.board = {!! $game->board !!};
        game.board_state = {!! $game->board_state !!};
        game.last_action = {!! $game->last_action ? $game->last_action : 'null' !!};

    </script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script src="{{asset('js/game.js')}}"></script>
@endsection  