var minigameName = "Missende Letters";

var questioner;
var scoreboard;

var feedbackText;
var buttonBack;

var beginTimestamp;
var assignmentHistory; // History of questions { question: n, anwser: n, correctAnwser: n }

Game.MinigameMissingletter = function (game) {
    
}

Game.MinigameMissingletter.prototype = {
    create: function (game) {
        this.stage.backgroundColor = '#002900';
        
        feedbackText = game.add.text(game.world.centerX, game.world.centerY * 0.75, "PLACEHOLDER", {
            font: "36px Calibri",
            fill: "#fff",
            align: "center"
        });
        feedbackText.anchor.setTo(0.5, 0);
        feedbackText.alpha = 0;
        
        buttonBack = new Button(game, 'buttonBack', 0, 0, 96, 96, '', function() {
            game.state.start('hub', true, false);
        });
        buttonBack.button.anchor.setTo(0, 0);
        
        questioner = new Questioner(game, Game.MinigameMissingletter.wordList);
        questioner.difficulty = 1;
        questioner.next();
        
        scoreboard = new Scoreboard(game);
        
        beginTimestamp = Math.round(new Date().getTime() / 1000.0);
        
        var thisObject = this;
        
        questioner.addOnAnwserListener( function(item) {          
            // Show CORRECT / INCORRECT text feedback
            if(feedbackText.tween != null)
                feedbackText.tween.stop();
            
            if (item) {
                sumsCorrect++;
                feedbackText.setStyle({fill: "#33cc33", fontSize: "12"});
                feedbackText.setText(Game.CorrectTexts[Math.floor(Math.random() * Game.CorrectTexts.length)]);
                game.add.tween(feedbackText).to({fontSize: 48}, 2000, Phaser.Easing.Back.Out, true, 0);
            } else {
                feedbackText.setStyle({fill: "#e60000", fontSize: "12"});
                feedbackText.setText(Game.IncorrectTexts[Math.floor(Math.random() * Game.IncorrectTexts.length)]);
                game.add.tween(feedbackText).to({fontSize: 36}, 2000, Phaser.Easing.Back.Out, true, 0);
            }

            game.add.tween(feedbackText).to({alpha: 1}, 1000, Phaser.Easing.Linear.None, true, 0);
            feedbackText.tween = game.add.tween(feedbackText).to({alpha: 0}, 500, Phaser.Easing.Linear.None, true, 3000);
        });
        
        questioner.addOnFinishedListener( function(item) {
            // Maybe only calc once instead of twice (again in postResults)
            var sumsCorrect = 0;
            for (var i = 0; i < item.length; i++) {
                if(item[i].correct)
                    sumsCorrect++;
            }
            var calcScore = (sumsCorrect / item.length) * 100;
            if (calcScore == NaN)
                calcScore = 0;
            
            scoreboard.score = calcScore;
            scoreboard.create();
            thisObject.postResults(beginTimestamp, item);
        });
        
        scoreboard.addHomeClickHandler( function() {
           game.state.start('hub', true, false);
        });
    },
    update: function () {

    },
    postResults: function (beginTimestamp, assignmentHistory) {
        var sumsCorrect = 0;
        for (var i = 0; i < assignmentHistory.length; i++) {
            if(assignmentHistory[i].correct)
                sumsCorrect++;
            
            var xhttp2;
            xhttp2 = new XMLHttpRequest();
            xhttp2.onreadystatechange = function () {
//                if (xhttp2.readyState == 4 && xhttp2.status == 200) {
//                    console.log(xhttp2.responseText);
//                }
            };
            xhttp2.open("POST", "../Webservice/Controllers/assignment.php", true);
            xhttp2.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xhttp2.send("timestamp=" + Math.round(new Date().getTime() / 1000.0) + "&minigameName=" + minigameName + "&question=" + assignmentHistory[i].question + "&anwser=" + assignmentHistory[i].anwser.toString() + "&correctAnwser=" + assignmentHistory[i].correctAnwser + "&correct=" + assignmentHistory[i].correct);
        }
        
        var xhttp;
        xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function () {
//            if (xhttp.readyState == 4 && xhttp.status == 200) {
//                console.log(xhttp.responseText);
//            }
        };
        xhttp.open("POST", "../Webservice/Controllers/record.php", true);
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

        var calcScore = (sumsCorrect / assignmentHistory.length) * 100;
        if (calcScore == NaN)
            calcScore = 0;
        xhttp.send("timestamp=" + Math.round(new Date().getTime() / 1000.0) + "&minigameName=" + minigameName + "&score=" + calcScore + "&difficulty=" + 2 + "&beginTimestamp=" + beginTimestamp);
    }
}