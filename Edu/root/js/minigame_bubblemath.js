var maxMultiplication = 10;
var amountOfAnwsers = 6;

var sumTotal = 0;
var sumsCorrect = 0;

var multiplicationText;
var progressText;
var feedbackText;

var correctTexts = ["Goed gedaan!", "Dat klopt!", "Heel goed!", "Dat is correct!", "Ga zo door!", "Goed zo!"];
var incorrectTexts = [ "Helaas", "Neem je tijd" ];

var balloons = [];
var anwser;

Game.MinigameBubblemath = function (game) { }

Game.MinigameBubblemath.prototype = {
    create: function (game) {
        this.stage.backgroundColor = '#0066ff';
        
        multiplicationText = game.add.text(game.world.centerX, game.world.centerY / 2, "PLACEHOLDER", {
            font: "32px Arial",
            fill: "#fff",
            align: "center"
        });
        multiplicationText.anchor.setTo(0.5, 0);

        feedbackText = game.add.text(game.world.centerX, game.world.centerY, "PLACEHOLDER", {
            font: "12px Arial",
            fill: "#fff",
            align: "center"
        });
        feedbackText.anchor.setTo(0.5, 0);
        feedbackText.alpha = 0;

        progressText = game.add.text(0, 0, sumsCorrect + "/" + sumTotal, {
            font: "16px Arial",
            fill: "#fff",
            align: "left"
        });

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
        console.log("You choose: " + value);
        console.log("Anwser correct: " + (value == anwser));

        if (value == anwser) {
            sumsCorrect++;
            feedbackText.setStyle( { fill: "#33cc33", fontSize: "12" } );
            feedbackText.setText(correctTexts[Math.floor(Math.random() * correctTexts.length)]);
            game.add.tween(feedbackText).to({ fontSize: 48 }, 2000, Phaser.Easing.Back.Out, true, 0);
        } else {
            feedbackText.setStyle({ fill: "#e60000", fontSize: "12" });
            feedbackText.setText(incorrectTexts[Math.floor(Math.random() * incorrectTexts.length)]);
            game.add.tween(feedbackText).to({ fontSize: 36 }, 2000, Phaser.Easing.Back.Out, true, 0);
        }
        game.add.tween(feedbackText).to({ alpha: 1 }, 500, Phaser.Easing.Linear.None, true, 0);
        game.add.tween(feedbackText).to({ alpha: 0 }, 2000, Phaser.Easing.Linear.None, true, 3000);

        this.clearSum();
        this.setupSum(this.game, maxMultiplication, amountOfAnwsers);
    },
    clearSum: function(game) {
        balloons.forEach(function (balloon) {
            balloon.destroy();
        });
    },
    setupSum: function (game, maxMultiplication, anwsers) {
        progressText.text = sumsCorrect + "/" + sumTotal;
        sumTotal++;
        
        var n1 = Math.round(Math.random() * maxMultiplication);
        var n2 = Math.round(Math.random() * maxMultiplication);
        anwser = n1 * n2;

        console.log(n1 + " x " + n2 + " = " + anwser);

        multiplicationText.text = n1 + " x " + n2 + " = ???";

        var correctAnwserIndex = Math.floor(Math.random() * anwsers) + 1;
        console.log(correctAnwserIndex);
        var highestAnwser = maxMultiplication * maxMultiplication;
        var previousNumbers = [ anwser ];
        for (var i = 1; i <= anwsers; i++) {
            var value;
            if (i == correctAnwserIndex) {
                value = anwser;
                
            } else {
                do {
                    value = Math.round(Math.random() * highestAnwser);
                    console.log(previousNumbers.indexOf(value));
                } while (previousNumbers.indexOf(value) != -1);
            }
            previousNumbers.push(value);
            this.createBalloon(game, value, "red", 48 + 100 * i, game.world.centerY * 1.75, 96, 96);
        }
        console.log(previousNumbers);
    }
}