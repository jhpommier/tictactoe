$(document).ready(function(){
    buildBoard();
    initBoard();
});

function buildBoard() {
    let board = "";
    const board_size = game.board.length;
    for (y = 0; y < board_size; y++) {
        board += "<tr>";
        for (x = 0; x < board_size; x++) {
            let pawn = "";
            let cellClass = "";
            if (game.board[x][y]) {
                pawn = game.board[x][y];
                cellClass = "blocked";
            }
            board += `<td onclick="engineCell(this)" class="${cellClass}" data-cell="c-${x}-${y}"  data-x="${x}" data-y="${y}">${pawn}</td>`;
        }
        board += "</tr>";
    }
    $("#tic-tac-toe-table").html( board );
}

function initBoard() {
    switch (game.board_state.label) {
        case 'RUNNING':
            setCurrentPlayer(game.last_action);
            break;
        case 'DRAW':
            paintBoardDraw();
            proposeNewGame();
            break;
        case 'WINNER' :
            paintBoardWinner(game.board_state);
            proposeNewGame();
            break;
        default:
            console.error('Error on board state', game.board_state.label);
    }
}

function engineCell(cell) {
    const jCell = $(cell);
    
    if (jCell.hasClass('blocked')) {
        return;
    }
    jCell.addClass('saving');

    const x = jCell.data('x');
    const y = jCell.data('y');

    const formData = {
        cell: {
            x: x,
            y: y
        },
        player: game.currentPlayer
    }

    $.ajax({
        type: "PATCH",
        url: "/api/game/" + game.id,
        data: JSON.stringify(formData),
        dataType: 'json',
        success: function (res) {
            let data;
            try {
                gameData = JSON.parse(res);
            } catch (e) {
                console.error("Parsing error:", e); 
                alert("Parsing error");
                return;
            }
            if (typeof gameData.error !== "undefined") {
                alert(gameData.error);
                return;
            }

            showLastAction(gameData.last_action);
            
            switch (gameData.board_state.label) {
                case 'RUNNING':
                    setCurrentPlayer(gameData.last_action);
                    break;
                case 'DRAW':
                    paintBoardDraw();
                    proposeNewGame();
                    break;
                case 'WINNER' :
                    paintBoardWinner(gameData.board_state);
                    updateScores();
                    proposeNewGame();
                    break;
                default:
                    console.error('Error on board state', gameData.board_state.label);
            }

        },
        error: function (error) {
            console.log('Error:', error);
        }
    });
}

function showLastAction(lastAction) {
    const refreshedCellName = 'c-' + lastAction.x + '-' + lastAction.y;
    const refreshedCell = $('[data-cell="' + refreshedCellName + '"]');

    refreshedCell.removeClass('saving');
    refreshedCell.addClass('blocked');
    refreshedCell.text(lastAction.pawn);
}

function setCurrentPlayer(lastAction) {
    if (lastAction && lastAction.player) {
        game.currentPlayer = lastAction.player === 'player1' ? 'player2' : 'player1';
    } else {
        game.currentPlayer = 'player1';
    }

    if (game.currentPlayer === 'player1') {
        $("#player1").addClass('current');
        $("#player2").removeClass('current');
    } else {
        $("#player2").addClass('current');
        $("#player1").removeClass('current');
    }

}

function paintBoardDraw() {
    const jCells = $('[data-cell^="c-"]');
    jCells.addClass('draw');
    $('[data-cell^="c-"]').prop('onclick',null).off('click');
}

function paintBoardWinner(state) {
    const boardMaxIndex = game.board.length - 1;
    if (state.diag1Win === true) {
        for (i = 0; i <= boardMaxIndex; i++) {
            const cellName = 'c-' + i + '-' + i;
            const jCell = $('[data-cell="' + cellName + '"]');
            jCell.addClass('victory');
        }
    }

    if (state.diag2Win === true) {
        for (i = 0; i <= boardMaxIndex; i++) {
            const cellName = 'c-' + i + '-' + (boardMaxIndex - i);
            const jCell = $('[data-cell="' + cellName + '"]');
            jCell.addClass('victory');
        }
    }

    if (typeof state.lineWin === "number") {
        for (i = 0; i <= boardMaxIndex; i++) {
            const cellName = 'c-' + i + '-' + state.lineWin;
            const jCell = $('[data-cell="' + cellName + '"]');
            jCell.addClass('victory');
        }
    }

    if (typeof state.columnWin === "number") {
        for (i = 0; i <= boardMaxIndex; i++) {
            const cellName = 'c-' + state.columnWin + '-' + i;
            const jCell = $('[data-cell="' + cellName + '"]');
            jCell.addClass('victory');
        }
    }
    $('[data-cell^="c-"]').prop('onclick',null).off('click');
}

function proposeNewGame() {
    $("#new-game").show();
}

function updateScores() {
    // @todo
}
