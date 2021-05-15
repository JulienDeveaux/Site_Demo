<?php

require_once "PlateauQuantik.php";
require_once "PieceQuantik.php";

class ArrayPieceQuantik
{
	protected array $piecesQuantiks;
	protected int $taille;

	public function __construct() {
		$this->taille = 0;
	}

	public function __toString():String {
		$s = '<p>tableau : ';
		for($i = 0; $i < $this->taille; $i++) {
			$s = $s.$this->piecesQuantiks[$i].' ';
		}
		$s = $s.'</p>';
        return $s;
	}

	public function getTaille(): int {
		return $this->taille;
	}

	public static function initPiecesNoires(): ArrayPieceQuantik {
	    $resultat = new ArrayPieceQuantik();
	    $resultat->addPieceQuantik(0);
        $resultat->addPieceQuantik(1);
        $resultat->addPieceQuantik(2);
        $resultat->addPieceQuantik(3);
        $resultat->addPieceQuantik(4);
        $resultat->addPieceQuantik(5);
        $resultat->addPieceQuantik(6);
        $resultat->addPieceQuantik(7);
        $resultat->setPieceQuantik(0, PieceQuantik::initBlackCube());
	    $resultat->setPieceQuantik(1, PieceQuantik::initBlackCube());
        $resultat->setPieceQuantik(2, PieceQuantik::initBlackCone());
        $resultat->setPieceQuantik(3, PieceQuantik::initBlackCone());
        $resultat->setPieceQuantik(4, PieceQuantik::initBlackCylindre());
        $resultat->setPieceQuantik(5, PieceQuantik::initBlackCylindre());
        $resultat->setPieceQuantik(6, PieceQuantik::initBlackSphere());
        $resultat->setPieceQuantik(7, PieceQuantik::initBlackSphere());
		return $resultat;
	}

	public static function initPiecesBlanches(): ArrayPieceQuantik {
        $resultat = new ArrayPieceQuantik();
        $resultat->addPieceQuantik(0);
        $resultat->addPieceQuantik(1);
        $resultat->addPieceQuantik(2);
        $resultat->addPieceQuantik(3);
        $resultat->addPieceQuantik(4);
        $resultat->addPieceQuantik(5);
        $resultat->addPieceQuantik(6);
        $resultat->addPieceQuantik(7);
        $resultat->setPieceQuantik(0, PieceQuantik::initWHiteCube());
        $resultat->setPieceQuantik(1, PieceQuantik::initWhiteCube());
        $resultat->setPieceQuantik(2, PieceQuantik::initWhiteCone());
        $resultat->setPieceQuantik(3, PieceQuantik::initWhiteCone());
        $resultat->setPieceQuantik(4, PieceQuantik::initWhiteCylindre());
        $resultat->setPieceQuantik(5, PieceQuantik::initWhiteCylindre());
        $resultat->setPieceQuantik(6, PieceQuantik::initWhiteSphere());
        $resultat->setPieceQuantik(7, PieceQuantik::initWhiteSphere());
		return $resultat;
	}

	public function getPieceQuantik(int $pos): PieceQuantik {
		return $this->piecesQuantiks[$pos];
	}

	public function setPieceQuantik(int $pos, PieceQuantik $piece): void {
		$this->piecesQuantiks[$pos] = $piece;
	}

	public function addPieceQuantik(int $pos): void {
		$this->taille++;
		$this->piecesQuantiks[$pos] = PieceQuantik::initVoid();
	}

	public function removePieceQuantik(int $pos): void {
		$this->taille--;
		for($i = $pos; $i < $this->taille; $i++) {
			$this->piecesQuantiks[$i] = $this->piecesQuantiks[$i+1];
		}
		$this->piecesQuantiks[$this->taille+1] = null;
	}
}
?>