<?php

require_once "PlateauQuantik.php";
require_once "ArrayPieceQuantik.php";

/**
 * Class QuantikUIGenerator
 */
class QuantikUIGenerator
{

    /**
     * @param string $title
     * @return string
     */
    public static function getDebutHTML(string $title = "Quantik"): string
    {
        return "<!doctype html>
<html lang='fr'>
    <head>
        <meta charset='UTF-8'>
        <title>$title</title>
        <link rel=\"stylesheet\" type=\"text/css\" href=\"../css/Quantikstyle.css\" />
        <link rel='icon' href='favicon.ico' />
    </head>
    <body>
        <nav>
            <div class=\"logo\"><h4>$title</h4></div>
    <ul class=\"nav-links\">
      <li><a href=\"../index.html\">Accueil</a></li>
      <li><a href=\"../Morpion.html\">Morpion</a></li>
      <li><a href=\"../Game.html\" style=\"color: red\">Jeu Dinosaure</a></li>
      <li><a href=\"../edt/edt.php\">Calendrier</a></li>
    </ul>
    <div class=\"MenuBar\">
      <div class=\"l1\"></div>
      <div class=\"l2\"></div>
      <div class=\"l3\"></div>
    </div>
  </nav>
  <script>
  const navSlide = () => {
    const navBar = document.querySelector('.MenuBar');
    const nav = document.querySelector('.nav-links');
    const navLinks = document.querySelectorAll('.nav-links li');

    navBar.addEventListener('click', () => {
      nav.classList.toggle('nav-active');

      navBar.classList.toggle('toggle');

      navLinks.forEach((link, index) => {
        if(link.style.animation) {
          link.style.animation = '';
        } else {
          let temp = index / 7 + 0.5;
          link.style.animation = `navLinkFade 0.5s ease forwards ` + temp + `s`;
        }
      });
    });
  }

  navSlide();
</script></br>
        <div class='quantik'>
        <form action=\"quantik.php\" method =\"get\">";
    }

    /**
     * @return string
     */
    public static function getFinHTML(): string
    {
        return "</form></div></body>\n</html>";
    }

    /**
     * @param string $message
     * @return string
     */
    public static function getPageErreur(string $message): string
    {
        header("HTTP/1.1 400 Bad Request");
        $resultat = self::getDebutHTML("400 Bad Request");
        $resultat .= "<h2>$message</h2>";
        $resultat .= "<p><br /><br /><br /><a href='quantik.php?reset'>Retour à l'accueil...</a></p>";
        $resultat .= self::getFinHTML();
        return $resultat;
    }

    /**
     * @param PieceQuantik $pq
     * @return string
     */
    public static function getButtonClass(PieceQuantik $pq): string
    {
        if ($pq->getForme()==PieceQuantik::VOID)
            return "vide";
        $ch = $pq->__toString();
        return substr($ch,1,2).substr($ch,4,1);
    }

    /**
     * production d'un bloc HTML pour présenter l'état du plateau de jeu,
     * l'attribution de classes en vue d'une utilisation avec les est un bon investissement...
     * @param PlateauQuantik $plateau
     * @return string
     */
    public static function getDivPlateauQuantik(PlateauQuantik $plateau): string
    {
        $resultat = "<p></br><table>";

        for($i = 0; $i < 4; $i++){
            $resultat .= "<tr>";
            for($j = 0; $j < 4; $j++) {
                $resultat .= "<td>"
                    . "<button class=\"button plateauinactif forme"
                    . $plateau->getPiece($i,$j)->getForme()
                    . $plateau->getPiece($i,$j)->getCouleur()
                    . "\" type=\"submit\" disabled ></button>"
                    . "</td>";
            }
            $resultat .= "</tr>";
        }
        $resultat .= "</table></p>";
        return $resultat;
    }

    /**
     * @param ArrayPieceQuantik $array
     * @param int $pos permet d'identifier la pièce qui a été sélectionnée par l'utilisateur avant de la poser (si != -1)
     * @return string
     */
    public static function getDivPiecesDisponibles(ArrayPieceQuantik $array, int $pos = -1): string {
        $resultat ="";
        for($i = 0; $i < $array->getTaille(); $i++) {
            $resultat .= "<button class=\"button buttonactive forme"
                . $array->getPieceQuantik($i)->getForme()
                . $array->getPieceQuantik($i)->getCouleur()
                ." \" type=\"submit\" name=\"piece\" value=\" $i \""
                . " enabled ></button>";
        }
        $resultat .= "<input type=\"hidden\" name=\"action\" value=\"choisirPiece\"/>";
        return $resultat;
    }

    /**
     * @param ArrayPieceQuantik $array
     * @return string
     */
    public static function getFormSelectionPiece(ArrayPieceQuantik $array): string {
        $resultat ="";
        for($i = 0; $i < $array->getTaille(); $i++) {
            $resultat .= "<button class=\"button buttondesactive forme"
                . $array->getPieceQuantik($i)->getForme()
                . $array->getPieceQuantik($i)->getCouleur()
                . "\" type=\"submit\" "
                . " disabled >"
                . "</button>";
        }
        return $resultat;
    }

    /**
     * @param PlateauQuantik $plateau
     * @param PieceQuantik $piece
     * @param int $position position de la pièce qui sera posée en vue de la transmettre via un champ caché du formulaire
     * @return string
     */
    public static function getFormPlateauQuantik(PlateauQuantik $plateau, PieceQuantik $piece, int $position): string {
        $x = 0;
        $y = 0;
        $resultat = "<p class=\"paratab\"></br><table>";
        $action = new ActionQuantik($_SESSION['plateau']);
        for($i = 0; $i < 4; $i++) {
            $resultat .= "<tr>";
            for($j = 0; $j < 4; $j++) {
                if(($action->isValidePose($x, $y, $piece))) {
                    $resultat .= "<td>"
                        . "<button class = \"button plateau forme"
                        . $plateau->getPiece($x,$y)->getForme()
                        . $plateau->getPiece($x,$y)->getCouleur()
                        . "\" type=\"submit\" name=\"piecePosition\" value=\""
                        . $position . " " . $x . " " . $y
                        . "\" enabled ></button>"
                        . "</td>";
                } else {
                    $resultat .= "<td>"
                        . "<button class=\"button buttondesactive forme"
                        . $plateau->getPiece($x,$y)->getForme()
                        . $plateau->getPiece($x,$y)->getCouleur()
                        . "\" type=\"submit\" name=\"piecePosition\" disabled ></button>"
                        . "</td>";
                }
                $y++;
            }
            $x++;
            $y = 0;
            $resultat .= "</tr>";
        }
        $resultat .= "</table></p>"
            . self::getFormBoutonAnnuler($piece);

        return $resultat;
    }

  /**
   * @param PieceQuantik $piece
   * @return string
   */
    public static function getFormBoutonAnnuler(PieceQuantik $piece) : string {
      return "<div>  Changer la pièce </br>"
          . "<button class=\"forme"
          . $piece->getForme().$piece->getCouleur()."\" type=\"submit\" disabled ></button>"
          . $piece
          . "<button type=\"submit\" name=\"action\" value=\"annulerChoix\" enabled >Annuler</button> </div>";
    }

    /**
     * @param int $couleur
     * @return string
     */
    public static function getDivMessageVictoire(int $couleur) : string {

        $resultat ="";
        if($couleur == 0){
            $resultat .= "<div> Les Noirs ont remporté la partie ";
        }elseif ($couleur == 1){
            $resultat .=  "<div> Les Blancs ont remporté la partie ";
        }
        $resultat .= self::getLienRecommencer()."</div>";
        return $resultat;
    }

    /**
     * @return string
     */
    public static function getLienRecommencer():string {
        return "<p><a href=\"quantik.php?action=recommencer\"> Recommencer ?</a></p>";
    }

    /**
     * @param array $lesPiecesDispos tableau contenant 2 ArrayPieceQuantik un pour les pièves blanches, un pour les pièces noires
     * @param int $couleurActive
     * @param PlateauQuantik $plateau
     * @return string
     */
    public static function getPageSelectionPiece(array $lesPiecesDispos, int $couleurActive, PlateauQuantik $plateau): string {
        $pageHTML = QuantikUIGenerator::getDebutHTML();
        if($couleurActive == 0 ){

            $pageHTML .= self::getDivPiecesDisponibles($lesPiecesDispos[0])
                . "<p></br></p>"
                . self::getFormSelectionPiece($lesPiecesDispos[1]);

        }elseif ($couleurActive == 1){

            $pageHTML .= self::getFormSelectionPiece($lesPiecesDispos[0])
                . "<p></br></p>"
                . self::getDivPiecesDisponibles($lesPiecesDispos[1]);
        }

        $pageHTML .= self::getDivPlateauQuantik($plateau)
            . self::getFinHTML();
        return $pageHTML;
    }

    /**
     * @param array $lesPiecesDispos tableau contenant 2 ArrayPieceQuantik un pour les pièves blanches, un pour les pièces noires
     * @param int $couleurActive
     * @param int $posSelection position de la pièce sélectionnée dans la couleur active
     * @param PlateauQuantik $plateau
     * @return string
     */
    public static function getPagePosePiece(array $lesPiecesDispos, int $couleurActive, int $posSelection, PlateauQuantik $plateau): string {
        $pageHTML = QuantikUIGenerator::getDebutHTML()
            . "<form action=\"quantik.php\" method =\"get\">";
        $piece = PieceQuantik::initVoid();
        if($couleurActive == 0 ){
            $pageHTML .= self::getFormSelectionPiece($lesPiecesDispos[0])
                . "<p></br></p>"
                . self::getFormSelectionPiece($lesPiecesDispos[1]);
            if(isset($_GET['piece'])) {
                $piece = $lesPiecesDispos[0]->getPieceQuantik((int)$_GET['piece']);
            } else if (isset($_GET['piecePosition'])) {
                $piece = $lesPiecesDispos[0]->getPieceQuantik((int)$_GET['piecePosition']);
            }

        }elseif ($couleurActive == 1){

            $pageHTML .= self::getFormSelectionPiece($lesPiecesDispos[0])
                . "<p></br></p>"
                . self::getFormSelectionPiece($lesPiecesDispos[1]);
            if(isset($_GET['piece'])) {
                $piece = $lesPiecesDispos[1]->getPieceQuantik((int)$_GET['piece']);
            } else if (isset($_GET['piecePosition'])) {
                $piece = $lesPiecesDispos[1]->getPieceQuantik((int)$_GET['piecePosition']);
            }
        }
        $pageHTML .= self::getFormPlateauQuantik($plateau, $piece, $posSelection)
            . "</form>"
            . self::getFinHTML();
        return $pageHTML;
    }

    /**
     * @param array $lesPiecesDispos tableau contenant 2 ArrayPieceQuantik un pour les pièves blanches, un pour les pièces noires
     * @param int $couleurActive
     * @param PlateauQuantik $plateau
     * @return string
     */
    public static function getPageVictoire(array $lesPiecesDispos, int $couleurActive, PlateauQuantik $plateau): string {
        $pageHTML = QuantikUIGenerator::getDebutHTML()
            . "<form action=\"quantik.php\" method =\"get\">";
        if($couleurActive == 0 ){

            $pageHTML .= self::getFormSelectionPiece($lesPiecesDispos[0])
                . "<p></br></p>"
                . self::getFormSelectionPiece($lesPiecesDispos[1]);

        }elseif ($couleurActive == 1){

            $pageHTML .= self::getFormSelectionPiece($lesPiecesDispos[0])
                . "<p></br></p>"
                . self::getFormSelectionPiece($lesPiecesDispos[1]);
        }

        $pageHTML .= self::getDivPlateauQuantik($plateau)
            . self::getDivMessageVictoire($couleurActive)
            . "</form>"
            . self::getFinHTML();
        return $pageHTML;

    }

}
