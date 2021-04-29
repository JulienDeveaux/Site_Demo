var config = {
  type: Phaser.AUTO,
  width: 800,
  height: 600,
  physics: {
    default: 'arcade',
    arcade: {
      gravity: { y: 200 }
    }
  },
  scene: {
    preload: preload,
    create: create
  }
};
var game = new Phaser.Game(config);

function preload () {
  this.load.setBaseURL('https://labs.phaser.io');   // Append url avant les assets

  this.load.image('sky', 'assets/skies/space3.png');
  this.load.image('logo', 'assets/sprites/phaser3-logo.png');
  this.load.image('red', 'assets/particles/red.png');
  this.load.image('dino', 'img/dino.png');
}

function create () {
  this.add.image(400, 300, 'sky');

  var particles = this.add.particles('red');

  var logo = game.add.sprite(game.wold.centerX, game.world.centerY, 'logo');

  var emitter = particles.createEmitter({
    speed: 100,
    scale: { start: 1, end: 0 },
    blendMode: 'ADD'
  });

  var dino = this.physics.add.image(400, 100, 'dino');

  dino.setVelocity(100, 200);
  dino.setBounce(1, 1);
  dino.setCollideWorldBounds(true);

  emitter.startFollow(dino);
}
