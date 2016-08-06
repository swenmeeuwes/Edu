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
        // Menu
        this.load.image('buttonPlay', 'assets/button_play.png');
        this.load.image('buttonBack', 'assets/button_back.png');
        this.load.image('buttonRestart', 'assets/button_restart.png');
        this.load.image('buttonHome', 'assets/button_home.png');

        //Hub
        this.load.image('buttonMinigameBubblemath', 'assets/hub/button_minigame_bubblemath.png');
        this.load.image('backgroundHub', 'assets/hub/background_placeholder.jpg');
        this.load.image('pet', 'assets/hub/scyther.gif');

        //Minigame bubblemath
        this.load.image('balloon', 'assets/minigame_bubblemath/balloon.png');
        this.load.image('moon', 'assets/minigame_bubblemath/moon.png');
        this.load.image('star', 'assets/minigame_bubblemath/star.png');
    },
    create: function () {
        this.state.start('hub');
    }
}