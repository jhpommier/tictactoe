@extends('layout')

@section('content')

    @if (count($errors) > 0)
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <form action="/new-game" method="post">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">  
        <div class="form-group">
            <label for="player1_name">Joueur 1</label>
            <input type="text" class="form-control" id="player1_name" name="player1_name" placeholder="Joueur 1">
        </div>
        <div class="form-group">
            <label for="player2_name">Joueur 2</label>
            <input type="text" class="form-control" id="player2_name" name="player2_name" placeholder="Joueur 2">
        </div>
        <div class="form-group">
            <label for="description">Taille du plateau</label>
            <input type="number" class="form-control" id="board_size" name="board_size" placeholder="Taille du plateau">
        </div>
        <button type="submit" class="btn btn-default">Nouvelle partie</button>
    </form>
                
@endsection 
