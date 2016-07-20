Game.Preloader = function (game) {
    this.loadingBar = null;
}

Game.Preloader.prototype = {
    preload: function () {
        this.loadingBar = this.add.sprite(this.world.centerX,
                                        this.world.centerY, 'loadingBar');
        this.loadingBar.anchor.setTo(0.5, 0.5);
        this.time.advancedTiming = true;
        this.load.setPreloadSprite(this.loadingBar);

        // Load Assets
        this.load.image('buttonPlay', 'assets/button_play.png');
        this.load.image('buttonMinigameBubblemath', 'assets/button_minigame_bubblemath.png');

        this.load.image('balloon', 'assets/minigame_bubblemath/balloon.png');
    },
    create: function () {
        this.state.start('main_menu');
    }
}