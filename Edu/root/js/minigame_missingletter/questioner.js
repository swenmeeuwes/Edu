var questionChar = "?";

function Questioner(game, wordList) {
    this.wordList = wordList;
    this.question = "";
    this.anwsers = [];
    this.anwserQueue = [];
    this.anwserBubbles = [];
    this.difficulty = 1;
    this.amountOfAnwsers = 8;
    
    this.questionPosition = -1;
    this.assignmentHistory = []; // History of questions { question: n, anwser: n, correctAnwser: n, correct: n }
    
    this.onAwnserListeners = [];
    this.onFinishedListeners = [];
    
    this.textObject = game.add.text(game.world.centerX, game.world.centerY / 2, "Loading ...", {
            font: "96px Calibri",
            fill: "#fff",
            align: "center"
        });
    this.textObject.anchor.setTo(0.5, 0.5);
    var thisObject = this;
    this.buttonShow = new Button(game, 'button_show', game.world.centerX, game.world.centerY * 1.15, 128, 128, "", function() { thisObject.show() });
    
    this.next = function() {
        this.questionPosition++;
        
        if(this.questionPosition < 10)
            this.assignmentHistory.push({
                    question: "",
                    anwser: [],
                    correctAnwser: "",
                    correct: false
            });
        else {
            this.onFinished(this.assignmentHistory);
            this.destroyGameObjects(this.anwserBubbles);
            return;
        }
        
        this.anwsers = [];
        this.question = this.wordList[Math.floor(Math.random() * this.wordList.length)];
        this.textObject.setText(this.question);
        this.destroyGameObjects(this.anwserBubbles);
        this.textObject.clearColors();
        this.textObject.addColor("#FFFFFF", 0);
        this.buttonShow.button.visible = true;
    };
    this.show = function() {
        this.buttonShow.button.visible = false;
        this.anwserQueue = this.generateAssignment(this.difficulty);
        this.prompt();
        this.anwserBubbles = this.createPossibleAnwsers(this.amountOfAnwsers);
    };
    this.generateAssignment = function(amountOfLetters) {
        this.assignmentHistory[this.questionPosition].correctAnwser = this.question;
        var previousIndexes = [];
        for (var i = 0; i < amountOfLetters; i++) {
            var letterPosition;
            do {
                letterPosition = Math.floor(Math.random() * this.question.length);
            } while (previousIndexes.indexOf(letterPosition) != -1);
            this.anwsers[letterPosition] = this.question.charAt(letterPosition);
            previousIndexes.push(letterPosition);
            this.question = this.replaceAt(this.question, letterPosition, questionChar);
        }
        this.assignmentHistory[this.questionPosition].question = this.question;
        this.textObject.text = this.question;
        
        return previousIndexes.sort();
    };
    this.prompt = function() {
        this.textObject.clearColors();
        this.textObject.addColor("#FFFFFF", 0);
        this.textObject.addColor("#FF0000", this.anwserQueue[0]);
        this.textObject.addColor("#FFFFFF", this.anwserQueue[0] + 1);
    };
    this.createPossibleAnwsers = function(amountOfAnwsers) {
        this.destroyGameObjects(this.anwserBubbles);
        
        var bubbles = [];
        var correctAnwserIndex = Math.floor(Math.random() * amountOfAnwsers) + 1;

        var previousLetters = [ this.anwsers[this.anwserQueue[0]].toUpperCase() ];
        for (var i = 1; i <= amountOfAnwsers; i++) {
            var value;
            if (i == correctAnwserIndex) {
                value = this.anwsers[this.anwserQueue[0]].toUpperCase();
            } else {
                do {
                    value = this.toLetter(Math.floor(Math.random() * 26) + 1).toUpperCase();
                } while (previousLetters.indexOf(value) != -1);
            }
            previousLetters.push(value);
            var questioner = this;
            var bubble = new AnwserBubble(game, game.world.width / (amountOfAnwsers + 1) * i, game.world.centerY * 1.5, 156, value, function() { questioner.anwser(this.value); });
            bubbles.push(bubble);
        }
        return bubbles;
    };
    this.anwser = function(anwser) {
        var correct = this.anwsers[this.anwserQueue[0]] != null && this.anwsers[this.anwserQueue[0]].toUpperCase() == anwser;
        
        if(correct)
            this.textObject.setText(this.replaceAt(this.textObject.text, this.anwserQueue[0], this.anwserQueue[0] == 0 ? anwser : anwser.toLowerCase()));  
        
        this.anwsers[this.anwserQueue[0]] = null;
        this.anwserQueue.shift();
        
        this.assignmentHistory[this.questionPosition].correct = correct ? 1 : 0;
        this.assignmentHistory[this.questionPosition].anwser.push(anwser);
        
        if(this.anwserQueue[0] != null && correct) {
            this.prompt();
            this.anwserBubbles = this.createPossibleAnwsers(this.amountOfAnwsers);
        } else {
            this.next();
        }
        
        this.onAnwser(correct);
        return correct;
    };
    this.replaceAt = function(text, index, character) {
        return text.substr(0, index) + character + text.substr(index + character.length);
    };
    this.destroyGameObjects = function(gameObjectArray) {      
        gameObjectArray.forEach(function (gameObject) {
            gameObject.destroy();
        });
    };
    this.toLetter = function(number) {
        "use strict";
        var mod = number % 26,
            pow = number / 26 | 0,
            out = mod ? String.fromCharCode(64 + mod) : (--pow,'Z');
        return pow ? this.toLetter(pow) + out : out;
    };
}

Questioner.prototype = {
    addOnAnwserListener: function(observer) {
        this.onAwnserListeners.push(observer);
    },
    removeOnAnwserListener: function(observer) {
        this.onAwnserListeners = this.onAwnserListeners.filter(
            function(item) {
                if(item !== observer) {
                    return item;
                }
            }
        );
    },
    onAnwser: function(correct, thisObject) {
        var scope = thisObject || window;
        this.onAwnserListeners.forEach(function(item) {
           item.call(scope, correct);
        });
    },
    addOnFinishedListener: function(observer) {
        this.onFinishedListeners.push(observer);
    },
    removeOnFinishedListener: function(observer) {
        this.onFinishedListeners = this.onFinishedListeners.filter(
            function(item) {
                if(item !== observer) {
                    return item;
                }
            }
        );
    },
    onFinished: function(correct, thisObject) {
        var scope = thisObject || window;
        this.onFinishedListeners.forEach(function(item) {
           item.call(scope, correct);
        });
    }
}


