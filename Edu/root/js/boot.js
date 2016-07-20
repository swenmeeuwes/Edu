var Game = {};

Game.Boot = function (game) {

};

Game.Boot.prototype = {
    init: function () {
        this.input.maxPointers = 1;
        this.stage.disableVisibilityChange = true; // Run while tabbed out
    },
    preload: function () {
        this.load.image('loadingBar', 'assets/loading_bar.png');
    },
    create: function () {
        this.state.start('preloader');
    }
}