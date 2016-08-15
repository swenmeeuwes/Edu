var pointerGame;
var data;
var buttonMinigameBubblemath;
var buttonMissingLetter;
var petNameText;

Game.Hub = function (game) {
    
}

Game.Hub.prototype = {
    create: function (game) {
        getRawData();

        game.scale.fullScreenScaleMode = Phaser.ScaleManager.EXACT_FIT;
        pointerGame = game;
        
        var background = game.add.image(0, 0, 'backgroundHub');
        background.width = game.world.width;
        background.height = game.world.height;

        var pet = game.add.image(game.world.centerX, game.world.centerY, 'pet');
        pet.width = 200;
        pet.height = 200;
        pet.anchor.setTo(0.5, 0.5);

        petNameText = game.add.text(game.world.centerX, game.world.centerY - 12 - pet.height / 2, "Loading pet name ...", {
            font: "24px Calibri",
            fill: "#000",
            align: "center"
        });
        petNameText.anchor.setTo(0.5, 0.5);
        
        var thisObject = this;
        buttonMinigameBubblemath = new Button(game, 'buttonMinigameBubblemath', game.world.centerX * 1.75, game.world.centerY + 64, 128, 128, "", function() {
            pointerGame.scale.startFullScreen(false);
            thisObject.state.start('minigame_bubblemath', true, false);
        });
        
        buttonMissingLetter = new Button(game, 'buttonMissingLetter', game.world.centerX * 1.75, game.world.centerY - 128, 128, 128, "", function() {
            pointerGame.scale.startFullScreen(false);
            thisObject.state.start('minigame_missingletter', true, false);
        });
    },
    update: function (game) {
        if (data != null) {
            if (data.data.name != null)
                petNameText.text = data.data.name;
        }
    }
}

function getRawData() {
    var xhttp;
    xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function () {
        if (xhttp.readyState == 4 && xhttp.status == 200) {
            data = JSON.parse(xhttp.responseText);
        }
    };
    xhttp.open("GET", "../Webservice/Controllers/pet.php", true);
    xhttp.send();
}

function insertPet(name, happiness, growth) {
    var xhttp;
    xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function () {
        if (xhttp.readyState == 4 && xhttp.status == 200) {
            data = JSON.parse(xhttp.responseText);
        }
    };
    xhttp.open("POST", "../Webservice/Controllers/pet.php", true);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send("name=" + name + "&happiness=" + happiness + "&growth=" + growth);
}