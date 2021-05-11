var config = {
  type: Phaser.AUTO,
  width: 1250,
  height: 800,
  physics: {
    default: 'arcade',
    arcade: {
      gravity: { y: 300 },
      debug: false
    }
  },
  scene: {
    preload: preload,
    create: create,
    update: update,
  }
};
var game = new Phaser.Game(config);
var input;
var cactus;
var car;
var vitesse = 1;
var posAvEcran;
var tileSprite;
var emiterSmoke1;
var emiterSmoke2;

var backgroundGeneral = document.getElementById('body');    // Remove background image of the page
backgroundGeneral.style.background = 'none';

function preload () {
  //this.load.setBaseURL('https://labs.phaser.io/assets');   // Append url avant les assets

  this.load.image('skyO', 'img/cloudsOriginal.png');
  this.load.image('sky', 'img/clouds.png');
  this.load.image('logo', 'img/phaser3-logo.png');
  //this.load.image('particle', 'assets/particles/red.png');
  this.load.image('smoke1', 'img/smoke-puff.png');
  this.load.image('smoke2', 'img/white-smoke.png');
  this.load.image('dino', 'img/dinotestwhite.png');
  this.load.image('car', 'img/car.png');
  this.load.image('cactus', 'img/cactus.png');
  this.load.image('reset', 'img/reset.png');
}

function create () {
  this.cameras.main.setBackgroundColor('rgba(0, 255, 0, 0.5)');
  input = this.input;   // Récup souris
  posAvEcran = this.cameras.main.centerX * 2 + 50;
  this.add.image(500, 330, 'skyO').setScrollFactor(0);    // Static image
  //tileSprite = this.add.tileSprite(600, 400, 1250, 800, 'skyO');   // Create a tilesprite (x, y, width, height, key)
  /*tileSprite = this.make.tileSprite({
    x: 0,
    y: 250,
    width: 5000,
    height: 800,
    key: 'skyO',
    add: true
  })
  tileSprite.setScrollFactor(1, 0);*/

  var smoke1 = this.add.particles('smoke1');
  var smoke2 = this.add.particles('smoke2');

  var logo = this.add.sprite(this.cameras.main.centerX, this.cameras.main.centerY - 200, 'logo').setScrollFactor(0);

  cactus = this.physics.add.image(100, this.cameras.main.centerY * 2 - 50 , 'cactus');
  cactus.setCollideWorldBounds(true);

  car = this.physics.add.image(200, 100, 'car');

  emiterSmoke1 = smoke1.createEmitter({
    acceleration: true,
    accelerationX: Math.ceil((Math.random() - 0.5) * 2) < 1 ? -600 : -400, //-1000,
    accelerationY: 0,
    speed: 100,
    scale: { start: 0.5, end: 0 },
    angle: {min: -35, max: -90},
    blendMode: 'ADD',
    gravityY: 50,
    frequency: 150,
    rotate: { start: 0, end: 180 }
  });

  emiterSmoke2 = smoke2.createEmitter({
    acceleration: true,
    accelerationX: Math.ceil((Math.random() - 0.5) * 2) < 1 ? -600 : -400, //-1000,
    accelerationY: 0,
    speed: 100,
    scale: { start: 0.5, end: 0 },
    angle: {min: -35, max: -90},
    blendMode: 'ADD',
    frequency: 100,
    gravityY: 50,
    rotate: { start: 0, end: 360 }
  });

  car.setGravityY(100);
  car.inputEnabled = true;
  car.setBounceY(Phaser.Math.FloatBetween(0.4, 0.8));
  car.setCollideWorldBounds(true);

  this.physics.add.collider(cactus, car, function(cactus, car) {
    cactus.x = posAvEcran;
    vitesse += 0.5;
  });

  this.buttonup = this.add.sprite(this.cameras.main.centerX, this.cameras.main.centerY- 96, 'car').setInteractive().setScrollFactor(0);;
  this.buttonup.on('pointerover', function(){this.buttonup.setTint(0xff0000);}, this)
  this.buttonup.on('pointerout', function(){this.buttonup.setTint(0xffffff);}, this)
  this.buttonup.on('pointerdown', function(){
    car.body.velocity.y = car.body.velocity.y - 400;
  });
  //------
  this.buttondown = this.add.sprite(this.cameras.main.centerX, this.cameras.main.centerY- 96 + 60, 'car').setInteractive().setScrollFactor(0);;
  this.buttondown.on('pointerover', function(){this.buttondown.setTint(0x00ff00);}, this)
  this.buttondown.on('pointerout', function(){this.buttondown.setTint(0xffffff);}, this)
  this.buttondown.on('pointerdown', function(){
    car.body.velocity.y = car.body.velocity.y + 400;
  });
  //------
  this.buttondroite = this.add.sprite(this.cameras.main.centerX - 50, this.cameras.main.centerY- 96 + 30, 'car').setInteractive().setScrollFactor(0);;
  this.buttondroite.on('pointerover', function(){this.buttondroite.setTint(0xffff00);}, this)
  this.buttondroite.on('pointerout', function(){this.buttondroite.setTint(0xffffff);}, this)
  this.buttondroite.on('pointerdown', function(){
    car.body.velocity.x = car.body.velocity.x - 200;
  });
  //------
  this.buttongauche = this.add.sprite(this.cameras.main.centerX + 50, this.cameras.main.centerY- 96 + 30, 'car').setInteractive().setScrollFactor(0);;
  this.buttongauche.on('pointerover', function(){this.buttongauche.setTint(0xff0050);}, this)
  this.buttongauche.on('pointerout', function(){this.buttongauche.setTint(0xffffff);}, this)
  this.buttongauche.on('pointerdown', function(){
    car.body.velocity.x = car.body.velocity.x + 200;
  });

  this.cursors = this.input.keyboard.createCursorKeys();      // input clavier

  this.reset = this.add.sprite(this.cameras.main.centerX - 550, this.cameras.main.centerY - 200, 'reset').setInteractive().setScrollFactor(0);;
  this.reset.on('pointerdown', function(){
    vitesse = 1;
  });

  //this.cameras.main.startFollow(car).setFollowOffset(0, 250);   //suivi caméra
  //this.cameras.main.focusOnXY(car.x, 20);

  emiterSmoke1.startFollow(car);
  emiterSmoke1.followOffset.set(-50, 0);
  emiterSmoke2.startFollow(car);
  emiterSmoke2.followOffset.set(-50, 0);
}

function update() {
  //tileSprite.x -=8;
  if(cactus.x <= this.cameras.main.centerX / 10) {
    cactus.x = this.cameras.main.centerX * 2 + 50;
    vitesse += 0.5;
  } else {
    cactus.x -= vitesse;
  }

  // Controls
  if (this.cursors.left.isDown) {
    car.body.velocity.x = car.body.velocity.x - 200;
    emiterSmoke1.frequency = 150;
    emiterSmoke2.frequency = 100;
  }
  else if(this.cursors.right.isDown) {
    car.body.velocity.x = car.body.velocity.x + 200;
    emiterSmoke1.frequency = 150;
    emiterSmoke2.frequency = 100;
  }
  if(this.cursors.up.isDown) {
    car.body.velocity.y = car.body.velocity.y - 400;
    emiterSmoke1.frequency = 150;
    emiterSmoke2.frequency = 100;
  }
  else if(this.cursors.down.isDown) {
    car.body.velocity.y = car.body.velocity.y + 400;
    emiterSmoke1.frequency = 150;
    emiterSmoke2.frequency = 100;
  }
  if(this.cursors.space.isDown) {
    car.body.velocity.x = 0;
    car.body.velocity.y -= 0.5;
    emiterSmoke1.frequency = 300;
    emiterSmoke2.frequency = 500;
  }
}
