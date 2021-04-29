var config = {
  type: Phaser.AUTO,
  width: 800,
  height: 600,
  physics: {
    default: 'arcade',
    arcade: {
      gravity: { y: 300 },
      debug: true
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
var dino;
var vitesse = 1;
var posAvEcran;

function preload () {
  //this.load.setBaseURL('https://labs.phaser.io/assets');   // Append url avant les assets

  this.load.image('sky', 'img/clouds.png');
  this.load.image('logo', 'img/phaser3-logo.png');
  //this.load.image('particle', 'assets/particles/red.png');
  this.load.image('particle', 'img/smoke-puff.png');
  this.load.image('dino', 'img/dinotestwhite.png');
  this.load.image('cactus', 'img/cactustest.png');
  this.load.image('reset', 'img/reset.png');
}

function create () {
  input = this.input;   // Récup souris
  posAvEcran = this.cameras.main.centerX * 2 + 50;    // position écran un peu hors bordure à droite pour faire apparaitre le cactus
  this.add.image(400, 300, 'sky');                    // Le fond

  var particles = this.add.particles('particle');     // La fumée particule

  var logo = this.add.sprite(this.cameras.main.centerX, this.cameras.main.centerY - 200, 'logo');   // X up Y horizon Le logo phaser

  cactus = this.physics.add.image(100, this.cameras.main.centerY * 2 - 50 , 'cactus');    // Le cactus
  cactus.setCollideWorldBounds(true);                                                     // Collide avec les bordure de la fenêtre sinon il tombe

  var emitter = particles.createEmitter({   // Sa c'est les particules de fumée
    speed: 100,
    scale: { start: 0.5, end: 0 },
    blendMode: 'ADD'
  });

  dino = this.physics.add.image(200, 100, 'dino');    // Le dino

  dino.setGravityY(100);    // Affecté par la gravité
  dino.inputEnabled = true; // Controllable
  dino.setBounceY(Phaser.Math.FloatBetween(0.4, 0.8));    //Rebondis sur l'axe Y
  dino.setCollideWorldBounds(true);                       // Collide avec les bordures du monde

  this.physics.add.collider(cactus, dino, function(cactus, dino) {    // Collision avec le cactus et le dino
    cactus.x = posAvEcran;      // reset la position du cactus
    vitesse += 0.5;             // Augmente la vitesse
  });

  this.buttonup = this.add.sprite(this.cameras.main.centerX, this.cameras.main.centerY- 96, 'dino').setInteractive();   // Les boutons de controle du dino
  this.buttonup.on('pointerover', function(){this.buttonup.setTint(0xff0000);}, this)
  this.buttonup.on('pointerout', function(){this.buttonup.setTint(0xffffff);}, this)
  this.buttonup.on('pointerdown', function(){
    dino.body.velocity.y = dino.body.velocity.y - 400;
  });
  //------
  this.buttondown = this.add.sprite(this.cameras.main.centerX, this.cameras.main.centerY- 96 + 60, 'dino').setInteractive();
  this.buttondown.on('pointerover', function(){this.buttondown.setTint(0x00ff00);}, this)
  this.buttondown.on('pointerout', function(){this.buttondown.setTint(0xffffff);}, this)
  this.buttondown.on('pointerdown', function(){
    dino.body.velocity.y = dino.body.velocity.y + 400;
  });
  //------
  this.buttondroite = this.add.sprite(this.cameras.main.centerX - 50, this.cameras.main.centerY- 96 + 30, 'dino').setInteractive();
  this.buttondroite.on('pointerover', function(){this.buttondroite.setTint(0xffff00);}, this)
  this.buttondroite.on('pointerout', function(){this.buttondroite.setTint(0xffffff);}, this)
  this.buttondroite.on('pointerdown', function(){
    dino.body.velocity.x = dino.body.velocity.x - 200;
  });
  //------
  this.buttongauche = this.add.sprite(this.cameras.main.centerX + 50, this.cameras.main.centerY- 96 + 30, 'dino').setInteractive();
  this.buttongauche.on('pointerover', function(){this.buttongauche.setTint(0xff0050);}, this)
  this.buttongauche.on('pointerout', function(){this.buttongauche.setTint(0xffffff);}, this)
  this.buttongauche.on('pointerdown', function(){
    dino.body.velocity.x = dino.body.velocity.x + 200;
  });

  this.reset = this.add.sprite(this.cameras.main.centerX - 350, this.cameras.main.centerY - 200, 'reset').setInteractive();  // Bouton reset de la vitesse
  this.reset.on('pointerdown', function(){
    vitesse = 1;
  });

  emitter.startFollow(dino);      //Les particules sur le dino
}

function update() {
  if(cactus.x <= this.cameras.main.centerX / 10) {      // On fais avancer le cactus jusqu'au bord
    cactus.x = this.cameras.main.centerX * 2 + 50;
    vitesse += 0.5;
  } else {
    cactus.x -= vitesse;
  }
}
