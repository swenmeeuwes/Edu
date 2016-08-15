function Scoreboard(game, score) {
    this.onRestartClickHandlers = [];
    this.onHomeClickHandlers = [];
    
    this.game = game;
    this.score = score;
    
    this.create = function() {
        var thisObject = this;
        
        var graphics = this.game.add.graphics(0, 0);
        graphics.beginFill(0xFFFFFF);
        graphics.drawRoundedRect(this.game.world.width / 8 * 2, this.game.world.height / 8, this.game.world.width / 8 * 4, this.game.world.height / 8 * 6, 24);
        graphics.endFill();
        
        var buttonRestart = new Button(this.game, 'buttonRestart', this.game.world.width / 8 * 2 + (this.game.world.width / 8 * 6 - this.game.world.width / 8 * 2) / 2 + this.game.world.width / 8 * 0.5, this.game.world.height / 8 * 6, 96, 96, "", function() {
            thisObject.onRestartClick();
        });
        
        var buttonHome = new Button(this.game, 'buttonHome', this.game.world.width / 8 * 2 + (this.game.world.width / 8 * 6 - this.game.world.width / 8 * 2) / 2 - this.game.world.width / 8 * 0.5, this.game.world.height / 8 * 6, 96, 96, "", function() {
            thisObject.onHomeClick();
        });
        
        var score = this.game.add.text(this.game.world.width / 8 * 4, this.game.world.height / 8 * 2, "Je score: \n" + this.score + "%", {
            font: "72px Calibri",
            fill: "#000",
            align: "center"
        });
        score.anchor.setTo(0.5, 0);
        
        return this;
    }
}

Scoreboard.prototype = {
    addRestartClickHandler: function(observer) {
        this.onRestartClickHandlers.push(observer);
    },
    removeRestartClickHandler: function(observer) {
        this.onRestartClickHandlers = this.onRestartClickHandlers.filter(
            function(item) {
                if(item !== observer) {
                    return item;
                }
            }
        );
    },
    onRestartClick: function(thisObject) {
        var scope = thisObject || window;
        this.onRestartClickHandlers.forEach(function(item) {
           item.call(scope);
        });
    },
    addHomeClickHandler: function(observer) {
        this.onHomeClickHandlers.push(observer);
    },
    removeHomeClickHandler: function(observer) {
        this.onHomeClickHandlers = this.onHomeClickHandlers.filter(
            function(item) {
                if(item !== observer) {
                    return item;
                }
            }
        );
    },
    onHomeClick: function(thisObject) {
        var scope = thisObject || window;
        this.onHomeClickHandlers.forEach(function(item) {
           item.call(scope);
        });
    }
}