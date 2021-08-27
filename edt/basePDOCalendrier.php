<?php
class Calendrier {

  /**
   * gestion statique des accès SGBD
   * @var PDO
   */
  private static $_pdo;

  /**
   * gestion statique de la requête préparée de selection
   * @var PDOStatement
   */
  private static $_pdos_select;

  /**
   * gestion statique de la requête préparée de mise à jour
   *  @var PDOStatement
   */
  private static $_pdos_update;

  /**
   * gestion statique de la requête préparée de d'insertion
   * @var PDOStatement
   */
  private static $_pdos_insert;

  /**
   * gestion statique de la requête préparée de suppression
   * @var PDOStatement
   */
  private static $_pdos_delete;

  /**
   * PreparedStatement associé à un SELECT, calcule le nombre d'évènements de la table
   * @var PDOStatement;
   */
  private static $_pdos_count;

  /**
   * PreparedStatement associé à un SELECT, récupère toutes les évènements
   * @var PDOStatement;
   */
  private static $_pdos_selectAll;

  /**
   * Initialisation de la connexion et mémorisation de l'instance PDO dans calendrier::$_pdo
   */
  public static function initPDO() {
    self::$_pdo = new PDO("pgsql:host=postgresql-jul.alwaysdata.net;dbname=jul_edt", "jul", "Code:4815162342");
    // pour récupérer aussi les exceptions provenant de PDOStatement
    self::$_pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  }

  /**
   * préparation de la requête SELECT * FROM calendrier
   * instantiation de self::$_pdos_selectAll
   */
  public static function initPDOS_selectAll() {
    self::$_pdos_selectAll = self::$_pdo->prepare('SELECT * FROM calendrier ORDER BY heureDebut');
  }

  /**
   * méthode statique instanciant calendrier::$_pdo_select
   */
  public static function initPDOS_select_jour() {
    self::$_pdos_select = self::$_pdo->prepare("SELECT * FROM calendrier WHERE heuredebut like :jour");
  }

  /**
   * méthode statique instanciant calendrier::$_pdo_select
   */
  public static function initPDOS_select_precis() {
    self::$_pdos_select = self::$_pdo->prepare("SELECT * FROM calendrier WHERE titreevent=:titre AND heuredebut LIKE :heuredebut AND heurefin LIKE :heurefin");
  }

  /**
   * méthode statique instanciant calendrier::$_pdo_update
   */
  public static function initPDOS_update() {
    self::$_pdos_update =  self::$_pdo->prepare('UPDATE calendrier SET titreevent=:titre, heuredebut=:heuredebut, heurefin=:heurefin, isfullday=:isfullday, color=:color WHERE titreevent=:oldtitre AND heuredebut LIKE :oldheuredebut AND heurefin LIKE :oldheurefin');
  }

  /**
   * méthode statique instanciant calendrier::$_pdo_insert
   */
  public static function initPDOS_insert() {
    self::$_pdos_insert = self::$_pdo->prepare('INSERT INTO calendrier VALUES(:titre,:heuredebut,:heurefin,:isfullday,:color)');
  }

  /**
   * méthode statique instanciant calendrier::$_pdo_delete
   */
  public static function initPDOS_delete() {
    self::$_pdos_delete = self::$_pdo->prepare('DELETE FROM calendrier WHERE titreevent=:titre AND heuredebut=:heuredebut AND heurefin=:heurefin AND isfullday=:isfullday');
  }

  /**
   * préparation de la requête SELECT COUNT(*) FROM calendrier
   * instantiation de self::$_pdos_count
   */
  public static function initPDOS_count() {
    if (!isset(self::$_pdo))
      self::initPDO();
    self::$_pdos_count = self::$_pdo->prepare('SELECT COUNT(*) FROM calendrier');
  }

  /**
   * titre de l'évènement
   *   @var string
   */
  protected $titreevent;

  /**
   *   @var string
   */
  protected $heuredebut;

  /**
   *   @var string
   */
  protected $heurefin;

  /**
   *   @var bool
   */
  protected $isfullday;

  /**
   *   @var string
   */
  protected $color;

  /**
   * attribut interne pour différencier les nouveaux objets des objets créés côté applicatif de ceux issus du SGBD
   * @var bool
   */
  private $nouveau = TRUE;

  /**
   * @return $this->titreevent
   */
  public function gettitreevent() : string {
    return $this->titreevent;
  }

  /**
   * @param $titreevent
   */
  public function settitreevent($titreevent): void {
    $this->titreevent=$titreevent;
  }

  /**
   * @return $this->heuredebut
   */
  public function getheuredebut() : string {
    return $this->heuredebut;
  }

  /**
   * @param $heuredebut
   */
  public function setheuredebut($heuredebut): void {
    $this->heuredebut=$heuredebut;
  }

  /**
   * @return $this->heurefin
   */
  public function getheurefin() : string {
    return $this->heurefin;
  }

  /**
   * @param $heurefin
   */
  public function setheurefin($heurefin): void {
    $this->heurefin=$heurefin;
  }

  /**
   * @return $this->isfullday
   */
  public function getisfullday() : string {
    if($this->isfullday != 1) {
      return "false";
    } else {
      return "true";
    }
  }

  /**
   * @param $isfull
   */
  public function setisfullday($isfull): void {
    $this->isfullday=$isfull;
  }

  /**
   * @return $this->color
   */
  public function getcolor() : string {
    return $this->color;
  }

  /**
   * @param $color
   */
  public function setcolor($color): void {
    $this->color=$color;
  }

  /**
   * @return $this->nouveau
   */
  public function getNouveau() : bool {
    return $this->nouveau;
  }

  /**
   * @param $nouveau
   */
  public function setNouveau($nouveau): void {
    $this->nouveau=$nouveau;
  }

  /**
   * @return array un tableau de toutes les évènements du calendrier
   */
  public static function getAll(): array {
    try {
      if (!isset(self::$_pdo))
        self::initPDO();
      if (!isset(self::$_pdos_selectAll))
        self::initPDOS_selectAll();
      self::$_pdos_selectAll->execute();
      // résultat du fetch dans une instance de calendrier
      $lesCategories = self::$_pdos_selectAll->fetchAll(PDO::FETCH_CLASS,'Calendrier');
      return $lesCategories;
    }
    catch (PDOException $e) {
      print($e);
    }
    return [];
  }

  /**
   * initialisation d'un objet à partir d'un enregistrement de calendrier
   * @param $jour un identifiant d'évènement du calendrier
   * @return l'instance de calendrier associée à $jour
   */
  public static function initcalendrier_jour($jour) {
    try {
      if (!isset(self::$_pdo))
        self::initPDO();
      if (!isset(self::$_pdos_select))
        self::initPDOS_select_jour();
      $jour = $jour."%";
      self::$_pdos_select->bindValue(':jour',$jour);
      self::$_pdos_select->execute();
      // résultat du fetch dans une instance de calendrier
      $lc = self::$_pdos_select->fetchObject('Calendrier');
      return $lc;
    }
    catch (PDOException $e) {
      print($e);
    }
  }

  /**
   * initialisation d'un objet à partir d'un enregistrement de calendrier
   * @return l'instance de calendrier associée aux arguments
   */
  public static function initcalendrier_precis($titre, $heuredebut, $heurefin) {
    try {
      if (!isset(self::$_pdo))
        self::initPDO();
      if (!isset(self::$_pdos_select))
        self::initPDOS_select_precis();
      self::$_pdos_select->bindValue(':titre',$titre);
      self::$_pdos_select->bindParam(':heuredebut', $heuredebut);
      self::$_pdos_select->bindParam(':heurefin', $heurefin);
      self::$_pdos_select->execute();
      // résultat du fetch dans une instance de calendrier
      $lc = self::$_pdos_select->fetchObject('Calendrier');
      if (isset($lc) && ! empty($lc))
        $lc->setNouveau(FALSE);
      if (empty($lc))
        throw new Exception("évènement $titre démarrant à $heuredebut inexistant dans la base.\n");
      return $lc;
    }
    catch (PDOException $e) {
      print($e);
    }
  }

  /**
   * sauvegarde d'un objet
   * soit on insère un nouvel objet
   * soit on le met à jour
   */
  public function save($oldTitre = "", $oldHeureDebut = "", $oldHeureFin = "") : void {
    if (!isset(self::$_pdo))
      self::initPDO();
    if ($this->nouveau) {
      if (!isset(self::$_pdos_insert)) {
        self::initPDOS_insert();
      }
      self::$_pdos_insert->bindParam(':titre', $this->titreevent);
      self::$_pdos_insert->bindParam(':heuredebut', $this->heuredebut);
      self::$_pdos_insert->bindParam(':heurefin', $this->heurefin);
      self::$_pdos_insert->bindParam(':isfullday', $this->isfullday);
      self::$_pdos_insert->bindParam(':color', $this->color);
      self::$_pdos_insert->execute();
      $this->setNouveau(FALSE);
    }
    else {
      if (!isset(self::$_pdos_update))
        self::initPDOS_update();
      if($oldTitre == "") {
        echo 'attention aucun argument dans l\'appel à save()';
      }
      self::$_pdos_update->bindParam(':titre', $this->titreevent);
      self::$_pdos_update->bindParam(':heuredebut', $this->heuredebut);
      self::$_pdos_update->bindParam(':heurefin', $this->heurefin);
      self::$_pdos_update->bindParam(':isfullday', $this->isfullday);
      self::$_pdos_update->bindParam(':color', $this->color);
      self::$_pdos_update->bindParam(':oldtitre', $oldTitre);
      self::$_pdos_update->bindParam(':oldheuredebut', $oldHeureDebut);
      self::$_pdos_update->bindParam(':oldheurefin', $oldHeureFin);
      self::$_pdos_update->execute();
    }
  }


  /**
   * suppression d'un objet
   */
  public function delete() :void {
    if (!isset(self::$_pdo))
      self::initPDO();
    if (!$this->nouveau) {
      if (!isset(self::$_pdos_delete)) {
        self::initPDOS_delete();
      }
      self::$_pdos_delete->bindParam(':titre', $this->titreevent);
      self::$_pdos_delete->bindParam(':heuredebut', $this->heuredebut);
      self::$_pdos_delete->bindParam(':heurefin', $this->heurefin);
      if($this->isfullday == "") {
        $this->isfullday = "FALSE";
      }
      self::$_pdos_delete->bindParam(':isfullday', $this->isfullday);
      self::$_pdos_delete->execute();
    }
    $this->setNouveau(TRUE);
  }

  /**
   * nombre d'objets disponible dans la table calendrier
   */
  public static function getNbcalendrier() : int {
    if (!isset(self::$_pdos_count)) {
      self::initPDOS_count();
    }
    self::$_pdos_count->execute();
    $resu = self::$_pdos_count->fetch();
    return $resu[0];
  }

  /**
   * affichage élémentaire
   */
  public function __toString() : string {
    $ch = "<table border='1'><tr><th>titreevent</th><th>heuredebut</th><th>heurefin</th><th>isfullday</th><th>color</th><th>nouveau</th></tr><tr>";
    $ch.= "<td>".$this->titreevent."</td>";
    $ch.= "<td>".$this->heuredebut."</td>";
    $ch.= "<td>".$this->heurefin."</td>";
    $ch.= "<td>".$this->isfullday."</td>";
    $ch.= "<td>".$this->color."</td>";
    $ch.= "<td>".$this->nouveau."</td>";
    $ch.= "</tr></table>";
    return $ch;
  }
}
?>
