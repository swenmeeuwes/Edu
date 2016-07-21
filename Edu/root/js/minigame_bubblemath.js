var maxMultiplication = 10;
var amountOfAnwsers = 6;

var sumTotal = 0;
var sumsCorrect = 0;

var multiplicationText;
var progressText;
var feedbackText;

var correctTexts = ["Goed gedaan!", "Dat klopt!", "Heel goed!", "Dat is correct!", "Ga zo door!", "Goed zo!"];
var incorrectTexts = [ "Helaas", "Neem je tijd" ];

var moon;
var stars = [];
var balloons = [];
var anwser;

Game.MinigameBubblemath = function (game) { }

Game.MinigameBubblemath.prototype = {
    create: function (game) {
        this.stage.backgroundColor = '#000099';

        moon = game.add.image(0, 0, 'moon');
        moon.anchor.setTo(0.5, 0.5);
        moon.width = 48;
        moon.height = 48;

        var coords = this.getSinTrailCoordinates(game.world.width,  game.world.height, game.world.height - 100, sumTotal, 10);
        moon.x = coords.x;
        moon.y = coords.y;
        
        multiplicationText = game.add.text(game.world.centerX, game.world.centerY / 2, "PLACEHOLDER", {
            font: "32px Calibri",
            fill: "#fff",
            align: "center"
        });
        multiplicationText.anchor.setTo(0.5, 0);

        feedbackText = game.add.text(game.world.centerX, game.world.centerY, "PLACEHOLDER", {
            font: "12px Calibri",
            fill: "#fff",
            align: "center"
        });
        feedbackText.anchor.setTo(0.5, 0);
        feedbackText.alpha = 0;

        progressText = game.add.text(game.world.width - 96, 0, sumsCorrect + "/" + sumTotal, {
            font: "16px Calibri",
            fill: "#fff",
            align: "left"
        });
        progressText.visible = false;

        var buttonBack = game.add.button(8, 8, 'buttonBack', function () { game.state.start('hub') }, this, 2, 1, 0);
        buttonBack.onInputOver.add(function () {
            this.add.tween(buttonBack.scale).to({ x: 1.15, y: 1.15 }, 500, Phaser.Easing.Back.Out, true, 0);
        }, this);
        buttonBack.onInputOut.add(function () {
            this.add.tween(buttonBack.scale).to({ x: 1, y: 1 }, 500, Phaser.Easing.Back.Out, true, 0);
        }, this);

        this.setupSum(game, maxMultiplication, amountOfAnwsers);

        //// Test
        //for (var j = 0; j < 100; j++) {
        //    var n = Math.floor(Math.random() * correctTexts.length);
        //    console.log("[CORRECT]    Index: " + n + " Item: " + correctTexts[n]);
        //    var n = Math.floor(Math.random() * incorrectTexts.length);
        //    console.log("[INCORRECT]  Index: " + n + " Item: " + incorrectTexts[n]);
        //}
    },
    update: function () {

    },
    createBalloon: function (game, value, color, x, y, width, height) {
        var balloon = game.add.button(x, y, 'balloon', function () { this.anwser(game, value) }, this, 2, 1, 0);
        balloon.anchor.setTo(0.5, 0.5);
        balloon.width = width;
        balloon.height = height;
        balloon.tint = Math.random() * 0xffffff;

        var content = game.add.text(balloon.x, balloon.y, value, {
            font: "14px Arial",
            fill: "#fff",
            align: "center"
        });
        content.anchor.setTo(0.5, 1);

        var valueBalloon = new Phaser.Group(game);
        var valueBalloonContainer = valueBalloon.add(balloon);
        var valueBalloonContent = valueBalloon.add(content);

        valueBalloonContainer.position.setTo(0.5, 0.5);
        valueBalloonContent.position.setTo(0.5, 0.5);

        valueBalloon.position.setTo(x, y);

        balloon.onInputOver.add(function () {
            this.add.tween(valueBalloon.position).to({ x: x, y: y - 20 }, 500, Phaser.Easing.Back.Out, true, 0);
            this.add.tween(valueBalloon.scale).to({ x: 1.15, y: 1.15 }, 500, Phaser.Easing.Back.Out, true, 0);
        }, this);
        balloon.onInputOut.add(function () {
            this.add.tween(valueBalloon.position).to({ x: x, y: y }, 500, Phaser.Easing.Back.Out, true, 0);
            this.add.tween(valueBalloon.scale).to({ x: 1, y: 1 }, 500, Phaser.Easing.Back.Out, true, 0);
        }, this);

        balloons.push(valueBalloon);
    },
    anwser: function (game, value) {
        //console.log("You choose: " + value);
        //console.log("Anwser correct: " + (value == anwser));

        if (value == anwser) {
            sumsCorrect++;
            feedbackText.setStyle( { fill: "#33cc33", fontSize: "12" } );
            feedbackText.setText(correctTexts[Math.floor(Math.random() * correctTexts.length)]);
            game.add.tween(feedbackText).to({ fontSize: 48 }, 2000, Phaser.Easing.Back.Out, true, 0);

            // Generate a stars at a random positions
            for (var i = 0; i < 5; i++) {
                var star = game.add.image(48 + (Math.random() * (game.world.width - 96)), 48 + (Math.random() * (game.world.height - 96)), 'star');
                star.anchor.setTo(0.5, 0.5);
                star.width = 12;
                star.height = 12;
                star.alpha = 0;
                this.add.tween(star).to({ alpha: 1 }, 2000, Phaser.Easing.Linear.None, true, 0);
                stars.push(star);
            }
        } else {
            feedbackText.setStyle({ fill: "#e60000", fontSize: "12" });
            feedbackText.setText(incorrectTexts[Math.floor(Math.random() * incorrectTexts.length)]);
            game.add.tween(feedbackText).to({ fontSize: 36 }, 2000, Phaser.Easing.Back.Out, true, 0);
        }
        var feedbackTextTween = game.add.tween(feedbackText).to({ alpha: 1 }, 1000, Phaser.Easing.Linear.None, true, 0)
            .onComplete.add(function () {
                game.add.tween(feedbackText).to({ alpha: 0 }, 500, Phaser.Easing.Linear.None, true, 2000);
        });

        if (sumTotal % 10 == 0)
            this.postResults();

        this.clearSum();
        this.setupSum(this.game, maxMultiplication, amountOfAnwsers);
    },
    // Should be destroy object array and take the array with objects as parameter
    clearSum: function(game) {
        balloons.forEach(function (balloon) {
            balloon.destroy();
        });
    },
    setupSum: function (game, maxMultiplication, anwsers) {
        progressText.text = sumsCorrect + "/" + sumTotal;
        sumTotal++;

        var coords = this.getSinTrailCoordinates(game.world.width,  game.world.height, game.world.height - 100, sumTotal, 10);
        moon.x = coords.x;
        moon.y = coords.y;
        
        var n1 = Math.round(Math.random() * maxMultiplication);
        var n2 = Math.round(Math.random() * maxMultiplication);
        anwser = n1 * n2;

        //console.log(n1 + " x " + n2 + " = " + anwser);

        multiplicationText.text = n1 + " x " + n2 + " = ?";

        var correctAnwserIndex = Math.floor(Math.random() * anwsers) + 1;
        //console.log(correctAnwserIndex);
        var highestAnwser = maxMultiplication * maxMultiplication;
        var previousNumbers = [ anwser ];
        for (var i = 1; i <= anwsers; i++) {
            var value;
            if (i == correctAnwserIndex) {
                value = anwser;
                
            } else {
                do {
                    value = Math.round(Math.random() * highestAnwser);
                    //console.log(previousNumbers.indexOf(value));
                } while (previousNumbers.indexOf(value) != -1);
            }
            previousNumbers.push(value);
            this.createBalloon(game, value, "red", 48 + 100 * i, game.world.centerY * 1.5, 96, 96);
        }
        //console.log(previousNumbers);
    },
    // Should take parameters
    postResults: function () {
        var xhttp;
        xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function () {
            if (xhttp.readyState == 4 && xhttp.status == 200) {
                console.log(xhttp.responseText);
            }
        };
        xhttp.open("POST", "../Webservice/Controllers/record.php", true);
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        var calcScore = (sumsCorrect / sumTotal) * 100;
        if (calcScore == NaN)
            calcScore = 0;
        xhttp.send("timestamp=" + Math.round(new Date().getTime() / 1000.0) + "&minigameName=" + "bubblemath" + "&score=" + calcScore + "&difficulty=" + 1);
    },
    getSinTrailCoordinates(width, actualHeight, height, currentStep, amountOfSteps) {
        var stepX = width / (amountOfSteps + 2);
        var x = stepX * currentStep;
        var y = actualHeight - (Math.sin(x / 255) * height);

        return { x, y };
    }
}