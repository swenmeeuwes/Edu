var data;
var buttonMinigameBubblemath;

Game.Hub = function (game) {
    
}

Game.Hub.prototype = {
    create: function (game) {
        getRawData("Swen");

        buttonMinigameBubblemath = game.add.button(game.world.centerX, game.world.centerY + 50, 'buttonMinigameBubblemath', buttonMinigameBubblemathOnClick, this, 2, 1, 0);
        buttonMinigameBubblemath.anchor.setTo(0.5, 0.5);

        buttonMinigameBubblemath.onInputOver.add(buttonMinigameBubblemathOnHover, this);
        buttonMinigameBubblemath.onInputOut.add(buttonMinigameBubblemathOnExit, this);
        buttonMinigameBubblemath.onInputUp.add(buttonMinigameBubblemathOnRelease, this);
    },
    update: function (game) {
        if (data != null) {
            // Has data
        }
    }
}

function getRawData(username) {
    var xhttp;
    xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function () {
        if (xhttp.readyState == 4 && xhttp.status == 200) {
            data = JSON.parse(xhttp.responseText);
        }
    };
    xhttp.open("GET", "../Webservice/Controllers/pet.php?username=" + username, true);
    xhttp.send();
}

function buttonMinigameBubblemathOnHover() {
    this.add.tween(buttonMinigameBubblemath.scale).to({ x: 1.2, y: 1.2 }, 500, Phaser.Easing.Back.Out, true, 0);
}

function buttonMinigameBubblemathOnExit() {
    this.add.tween(buttonMinigameBubblemath.scale).to({ x: 1, y: 1 }, 500, Phaser.Easing.Back.Out, true, 0);
}

function buttonMinigameBubblemathOnRelease() {
        
}
function buttonMinigameBubblemathOnClick() {
    this.state.start('minigame_bubblemath');
}