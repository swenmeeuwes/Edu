var buttonPlay;

Game.MainMenu = function (game) { };

Game.MainMenu.prototype = {
    create: function (game) {
        this.stage.backgroundColor = '#CCC';

        buttonPlay = game.add.button(game.world.centerX, game.world.centerY + 50, 'buttonPlay', this.buttonPlayOnClick, this, 2, 1, 0);
        buttonPlay.anchor.setTo(0.5, 0.5);

        buttonPlay.onInputOver.add(this.buttonPlayOnHover, this);
        buttonPlay.onInputOut.add(this.buttonPlayOnExit, this);
        buttonPlay.onInputUp.add(this.buttonPlayOnRelease, this);
    },
    update: function () {

    },
    buttonPlayOnHover: function () {
        this.add.tween(buttonPlay.scale).to({ x: 1.2, y: 1.2 }, 500, Phaser.Easing.Back.Out, true, 0);
    },
    buttonPlayOnExit: function () {
        this.add.tween(buttonPlay.scale).to({ x: 1, y: 1 }, 500, Phaser.Easing.Back.Out, true, 0);
    },
    buttonPlayOnRelease: function () {
        
    },
    buttonPlayOnClick: function () {
        this.state.start('hub');
    }
}