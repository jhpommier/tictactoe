<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGamesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('games', function (Blueprint $table) {
            $table->increments('id');
            $table->jsonb('board');
            $table->jsonb('last_action')->nullable();
            $table->jsonb('board_state')->nullable();
            $table->string('player1_name', 256);
            $table->string('player2_name', 256);
            $table->char('player1_pawn', 1);
            $table->char('player2_pawn', 1);
            $table->integer('player1_score')->default(0);
            $table->integer('player2_score')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('games');
    }
}
