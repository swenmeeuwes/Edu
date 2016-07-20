window.onload = function () {
    var game = new Phaser.Game(800, 600, Phaser.AUTO, '');

    game.state.add('boot', Game.Boot);
    game.state.add('preloader', Game.Preloader);
    game.state.add('main_menu', Game.MainMenu);
    game.state.add('hub', Game.Hub);
    game.state.add('minigame_bubblemath', Game.MinigameBubblemath);

    game.state.start('boot');
}