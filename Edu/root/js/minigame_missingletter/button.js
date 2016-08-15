function Button(game, sprite, x, y, width, height, value, action) {
    this.value = value;
    
    this.button = game.add.button(x, y, sprite, action, this, 2, 1, 0);
    this.button.anchor.setTo(0.5, 0.5);
    this.button.width = width;
    this.button.height = height;

    var content = game.add.text(this.button.x, this.button.y, value, {
        font: 32 / 156 * height + "px Calibri",
        fill: "#fff",
        align: "center"
    });
    content.anchor.setTo(0.5, 0.5);

    var valueButton = new Phaser.Group(game);
    var valueButtonContainer = valueButton.add(this.button);
    var valueButtonContent = valueButton.add(content);

    valueButtonContainer.position.setTo(0.5, 0.5);
    valueButtonContent.position.setTo(0.5, 0.5);

    valueButton.position.setTo(x, y);

    this.button.onInputOver.add(function () {
        game.add.tween(valueButton.scale).to({x: 1.15, y: 1.15}, 500, Phaser.Easing.Back.Out, true, 0);
    }, this);
    this.button.onInputOut.add(function () {
        game.add.tween(valueButton.scale).to({x: 1, y: 1}, 500, Phaser.Easing.Back.Out, true, 0);
    }, this);
    
    this.destroy = function() {
        valueButton.destroy();
    }
}