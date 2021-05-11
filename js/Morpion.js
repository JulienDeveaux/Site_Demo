var canvas = document.getElementById('jeu');
var ctx;
var plateau = [
  ['', '', ''],
  ['', '', ''],
  ['', '', '']
];

if (canvas.getContext) {
  document.getElementById('tour').innerHTML = "X";
  ctx = canvas.getContext('2d');
  ctx.fillStyle = "white";
  ctx.fillRect(0, 0, canvas.width, canvas.height);
  var mouseX = 0;
  var mouseY = 0;

  ctx.fillStyle = "black";
  ctx.fillRect(canvas.width / 3, 20, 20, canvas.width - 50);       // barre verticale gauche
  ctx.fillRect(canvas.width / 3 * 2, 20, 20, canvas.width - 50);   // barre verticale droite
  ctx.fillRect(20, canvas.width / 3, canvas.width - 50, 20);       // barre horizontale up
  ctx.fillRect(20, canvas.width / 3 * 2, canvas.width - 50, 20);   // barre horizontale down

} else {
  document.getElementById('titre').innerHTML = "Navigateur non supporté !";
}

canvas.addEventListener("mousedown", function(e) {
  let rect = canvas.getBoundingClientRect();
  mouseX = e.clientX - rect.left;
  mouseY = e.clientY - rect.top;
  play();
});

function play() {
  document.getElementById('tour').innerHTML = document.getElementById('tour').innerHTML === "O" ? "X" : "O";
  var tour = document.getElementById('tour').innerHTML
  if(mouseX < canvas.height && mouseX > canvas.height / 3 * 2 && mouseY > 0 && mouseY < canvas.width / 3 && plateau[0][2] === '') {                                      // Top right
    ctx.beginPath();
    tour === "O" ? ctx.arc(canvas.width - 80, 90, 50, 0, 2 * Math.PI) : croix(canvas.width - 80, 90);
    plateau[0][2] = tour;
    ctx.stroke();
  } else if(mouseX < canvas.height / 3 * 2 && mouseX > canvas.height / 3 && mouseY > 0 && mouseY < canvas.width / 3 && plateau[0][1] === '') {                            // Top center
    ctx.beginPath();
    tour === "O" ? ctx.arc(canvas.height / 2 + 10, 90, 50, 0, 2 * Math.PI) : croix(canvas.height / 2 + 10, 90);
    plateau[0][1] = tour;
    ctx.stroke();
  } else if(mouseX < canvas.height / 3 && mouseY < canvas.width / 3 && plateau[0][0] === '') {                                                                            // Top left
    ctx.beginPath();
    tour === "O" ? ctx.arc(90, 90, 50, 0, 2 * Math.PI) : croix(90, 90);
    plateau[0][0] = tour;
    ctx.stroke();
  } else if(mouseX > canvas.height / 3 * 2 && mouseX > canvas.height / 3 * 2 && mouseY < canvas.width / 3  * 2 && mouseY > canvas.width / 3 && plateau[1][2] === '') {    // Middle right
    ctx.beginPath();
    tour === "O" ? ctx.arc(canvas.height - 80, canvas.width / 2 + 10, 50, 0, 2 * Math.PI) : croix(canvas.height - 80, canvas.width / 2 + 10);
    plateau[1][2] = tour;
    ctx.stroke();
  } else if(mouseX < canvas.height / 3 * 2 && mouseX > canvas.height / 3 && mouseY < canvas.width / 3  * 2 && mouseY > canvas.width / 3 && plateau[1][1] === '') {        // Middle center
    ctx.beginPath();
    tour === "O" ? ctx.arc(canvas.height / 2 + 10, canvas.width / 2 + 10, 50, 0, 2 * Math.PI) : croix(canvas.height / 2 + 10, canvas.width / 2 + 10);
    plateau[1][1] = tour;
    ctx.stroke();
  } else if(mouseX < canvas.height / 3 && mouseY < canvas.width / 3 * 2 && mouseY > canvas.width / 3 && plateau[1][0] === '') {                                           // Middle left
    ctx.beginPath();
    tour === "O" ? ctx.arc(90, canvas.width / 2 + 10, 50, 0, 2 * Math.PI) : croix(90, canvas.width / 2 + 10);
    plateau[1][0] = tour;
    ctx.stroke();
  } else if(mouseX > canvas.height / 3 * 2 && mouseY > canvas.width / 3 * 2 && plateau[2][2] === '') {                                                                    // Bottom right
    ctx.beginPath();
    tour === "O" ? ctx.arc(canvas.height - 80, canvas.width - 80, 50, 0, 2 * Math.PI) : croix(canvas.height - 80, canvas.width - 80);
    plateau[2][2] = tour;
    ctx.stroke();
  } else if(mouseX > canvas.height / 3  && mouseX < canvas.height / 3 * 2 && mouseY > canvas.width / 3 * 2 && plateau[2][1] === '') {                                     // Bottom center
    ctx.beginPath();
    tour === "O" ? ctx.arc(canvas.height / 2 + 10, canvas.width - 80, 50, 0, 2 * Math.PI) : croix(canvas.height / 2 + 10, canvas.width - 80);
    plateau[2][1] = tour;
    ctx.stroke();
  } else if(mouseX > 0 && mouseX < canvas.height / 3 && mouseY > canvas.width / 3 * 2 && plateau[2][0] === '') {                                                          // Bottom left
    ctx.beginPath();
    tour === "O" ? ctx.arc(90, canvas.width - 80, 50, 0, 2 * Math.PI) : croix(90, canvas.width - 80);
    plateau[2][0] = tour;
    ctx.stroke();
  } else {
    document.getElementById('tour').innerHTML = document.getElementById('tour').innerHTML ==="O" ? "X" : "O";
  }
  for(var i = 0; i < 3; i++) {
    if(plateau[0][i] === plateau[1][i] && plateau[1][i] === plateau[2][i] && plateau[0][i] !== '') {    // vérification verticale
      document.getElementById('gagnant').innerHTML = "gagnant : " + plateau[0][i] + "</br><a id='restartlink' href='Morpion.html'>Recommencer ?</a>";
    }
    if(plateau[i][0] === plateau[i][1] && plateau[i][1] === plateau[i][2] && plateau[i][0] !== '') {    // vérification horizontale
      document.getElementById('gagnant').innerHTML = "gagnant : " + plateau[i][0] + "</br><a id='restartlink' href='Morpion.html'>Recommencer ?</a>";
    }
    if(plateau[0][2] === plateau[1][1] && plateau[1][1] === plateau[2][0] && plateau[1][1] !== '') {                            // vérification en diagonale démarrant en haut à droite
      document.getElementById('gagnant').innerHTML = "gagnant : " + plateau[1][1] + "</br><a id='restartlink' href='Morpion.html'>Recommencer ?</a>";
    }
    if(plateau[0][0] === plateau[1][1] && plateau[1][1] === plateau[2][2] && plateau[1][1] !== '') {                            // vérification en diagonale démarrant en haut à gauche
      document.getElementById('gagnant').innerHTML = "gagnant : " + plateau[1][1] + "</br><a id='restartlink' href='Morpion.html'>Recommencer ?</a>";
    }
  }
}

function croix(x, y) {    // Créé une croix à partir des coordonnée du centre d'une case
  ctx.beginPath();
  ctx.moveTo(x - 40, y - 40);
  ctx.lineTo(x + 40, y + 40);
  ctx.stroke();
  ctx.moveTo(x + 40, y - 40);
  ctx.lineTo(x - 40, y + 40);
  ctx.stroke();
}
