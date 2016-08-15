window.onload = function () {
    var game = new Phaser.Game(1280, 720, Phaser.AUTO, 'app');

    game.state.add('boot', Game.Boot);
    game.state.add('preloader', Game.Preloader);
    game.state.add('main_menu', Game.MainMenu);
    game.state.add('hub', Game.Hub);
    game.state.add('minigame_bubblemath', Game.MinigameBubblemath);
    game.state.add('minigame_missingletter', Game.MinigameMissingletter);

    game.state.start('boot');
}