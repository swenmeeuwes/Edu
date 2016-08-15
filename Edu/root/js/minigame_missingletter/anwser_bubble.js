function AnwserBubble(game, x, y, area, value, action) {
    this.value = value;
    
    var bubble = game.add.button(x, y, 'balloon', action, this, 2, 1, 0);
    bubble.anchor.setTo(0.5, 0.5);
    bubble.width = area;
    bubble.height = area;
    bubble.tint = Math.random() * 0xffffff;

    var content = game.add.text(bubble.x, bubble.y, value, {
        font: 32 / 156 * area + "px Calibri",
        fill: "#fff",
        align: "center"
    });
    content.anchor.setTo(0.5, 1); // Change to 0.5 later

    var valueBubble = new Phaser.Group(game);
    var valueBubbleContainer = valueBubble.add(bubble);
    var valueBubbleContent = valueBubble.add(content);

    valueBubbleContainer.position.setTo(0.5, 0.5);
    valueBubbleContent.position.setTo(0.5, 0.5);

    valueBubble.position.setTo(x, y);

    bubble.onInputOver.add(function () {
        game.add.tween(valueBubble.position).to({x: x, y: y - 20}, 500, Phaser.Easing.Back.Out, true, 0);
        game.add.tween(valueBubble.scale).to({x: 1.15, y: 1.15}, 500, Phaser.Easing.Back.Out, true, 0);
    }, this);
    bubble.onInputOut.add(function () {
        game.add.tween(valueBubble.position).to({x: x, y: y}, 500, Phaser.Easing.Back.Out, true, 0);
        game.add.tween(valueBubble.scale).to({x: 1, y: 1}, 500, Phaser.Easing.Back.Out, true, 0);
    }, this);
    
    this.destroy = function() {
        valueBubble.destroy();
    }
}