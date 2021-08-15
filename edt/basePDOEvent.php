<?php
class Event {

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
   * PreparedStatement associé à un SELECT, calcule le nombre de categorie de la table
   * @var PDOStatement;
   */
  private static $_pdos_count;

  /**
   * PreparedStatement associé à un SELECT, récupère toutes les Categories
   * @var PDOStatement;
   */
  private static $_pdos_selectAll;

  /**
   * Initialisation de la connexion et mémorisation de l'instance PDO dans Categorie::$_pdo
   */
  public static function initPDO() {
    self::$_pdo = new PDO("pgsql:host=postgresql-jul.alwaysdata.net;dbname=jul_edt", "jul", "Code:4815162342");
    // pour récupérer aussi les exceptions provenant de PDOStatement
    self::$_pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  }

  /**
   * préparation de la requête SELECT * FROM Categorie
   * instantiation de self::$_pdos_selectAll
   */
  public static function initPDOS_selectAll() {
    self::$_pdos_selectAll = self::$_pdo->prepare('SELECT * FROM event ORDER BY idjourassocie');
  }

  /**
   * méthode statique instanciant Categorie::$_pdo_select
   */
  public static function initPDOS_select_jour() {
    self::$_pdos_select = self::$_pdo->prepare('SELECT * FROM event WHERE idjourassocie= :idjourassocie');
  }

  /**
   * méthode statique instanciant Categorie::$_pdo_update
   */
  public static function initPDOS_update() {
    self::$_pdos_update =  self::$_pdo->prepare('UPDATE event SET idjourassocie=:idjourassocie, titreevent=:titre, heuredebut=:heuredebut, heurefin=:heurefin, isfullday=:isfullday, color=:color WHERE idjourassocie = :idjourassocie');
  }

  /**
   * méthode statique instanciant Categorie::$_pdo_insert
   */
  public static function initPDOS_insert() {
    self::$_pdos_insert = self::$_pdo->prepare('INSERT INTO event VALUES(:idjourassocie,:titre,:heuredebut,:heurefin,;isfullday,:color)');
  }

  /**
   * méthode statique instanciant fonction_prix::$_pdo_delete
   */
  public static function initPDOS_delete() {
    self::$_pdos_delete = self::$_pdo->prepare('DELETE FROM event WHERE idjourassocie=:idjourassocie AND titreevent:=titre AND heuredebut:=heuredebut AND heurefin:=heurefin AND isfullday:=isfullday AND color:=color');
  }

  /**
   * préparation de la requête SELECT COUNT(*) FROM Categorie
   * instantiation de self::$_pdos_count
   */
  public static function initPDOS_count() {
    if (!isset(self::$_pdo))
      self::initPDO();
    self::$_pdos_count = self::$_pdo->prepare('SELECT COUNT(*) FROM event');
  }

  /**
   * identifiant de la Categorie
   * @var int
   */
  protected $idjourassocie;

  /**
   * titre de la catégorie
   *   @var string
   */
  protected $titreevent;

  /**
   * id du prix
   *   @var string
   */
  protected $heuredebut;

  /**
   * id du prix
   *   @var string
   */
  protected $heurefin;

  /**
   * id du prix
   *   @var bool
   */
  protected $isfullday;

  /**
   * id du prix
   *   @var string
   */
  protected $color;

  /**
   * attribut interne pour différencier les nouveaux objets des objets créés côté applicatif de ceux issus du SGBD
   * @var bool
   */
  private $nouveau = TRUE;

  /**
   * @return $this->idjourassocie
   */
  public function getidjourassocie() : int {
    return $this->idjourassocie;
  }

  /**
   * @param $idjourassocie
   */
  public function setidjourassocie($idjourassocie): void {
    $this->idjourassocie=$idjourassocie;
  }

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
   * @return $this->heuredebut
   */
  public function getheurefin() : string {
    return $this->heurefin;
  }

  /**
   * @param $heuredebut
   */
  public function setheurefin($heurefin): void {
    $this->heurefin=$heurefin;
  }

  /**
   * @return $this->heuredebut
   */
  public function getisfullday() : bool {
    return $this->isfullday;
  }

  /**
   * @param $heuredebut
   */
  public function setisfullday($isfull): void {
    $this->isfullday=$isfull;
  }

  /**
   * @return $this->heuredebut
   */
  public function getcolor() : string {
    return $this->color;
  }

  /**
   * @param $heuredebut
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
   * @return array un tableau de toutes les Categories
   */
  public static function getAll(): array {
    try {
      if (!isset(self::$_pdo))
        self::initPDO();
      if (!isset(self::$_pdos_selectAll))
        self::initPDOS_selectAll();
      self::$_pdos_selectAll->execute();
      // résultat du fetch dans une instance de Categorie
      $lesCategories = self::$_pdos_selectAll->fetchAll(PDO::FETCH_CLASS,'Event');
      return $lesCategories;
    }
    catch (PDOException $e) {
      print($e);
    }
    return [];
  }

  /**
   * initialisation d'un objet à partir d'un enregistrement de Categorie
   * @param $id_jour un identifiant de Categorie
   * @return l'instance de Categorie associée à $id_ceremonie
   */
  public static function initCategorie_jour($id_jour) {
    try {
      if (!isset(self::$_pdo))
        self::initPDO();
      if (!isset(self::$_pdos_select))
        self::initPDOS_select_jour();
      self::$_pdos_select->bindValue(':idjourassocie',$id_jour);
      self::$_pdos_select->execute();
      // résultat du fetch dans une instance de Categorie
      $lc = self::$_pdos_select->fetchObject('Event');
      if (isset($lc) && ! empty($lc))
        $lc->setNouveau(FALSE);
      if (empty($lc))
        throw new Exception("Jour $id_jour inexistant dans la table Event.\n");
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
  public function save() : void {
    if (!isset(self::$_pdo))
      self::initPDO();
    if ($this->nouveau) {
      if (!isset(self::$_pdos_insert)) {
        self::initPDOS_insert();
      }
      self::$_pdos_insert->bindParam(':idjourassocie', $this->idjourassocie);
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
      self::$_pdos_update->bindParam(':idjourassocie', $this->idjourassocie);
      self::$_pdos_update->bindParam(':titre', $this->titreevent);
      self::$_pdos_update->bindParam(':heuredebut', $this->heuredebut);
      self::$_pdos_insert->bindParam(':heurefin', $this->heurefin);
      self::$_pdos_insert->bindParam(':isfullday', $this->isfullday);
      self::$_pdos_insert->bindParam(':color', $this->color);
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
      self::$_pdos_delete->bindParam(':idjourassocie', $this->idjourassocie);
      self::$_pdos_delete->bindParam(':titre', $this->titreevent);
      self::$_pdos_delete->bindParam(':heuredebut', $this->heuredebut);
      self::$_pdos_insert->bindParam(':heurefin', $this->heurefin);
      self::$_pdos_insert->bindParam(':isfullday', $this->isfullday);
      self::$_pdos_insert->bindParam(':color', $this->color);
      self::$_pdos_delete->execute();
    }
    $this->setNouveau(TRUE);
  }

  /**
   * nombre d'objets disponible dans la table
   */
  public static function getNbEvent() : int {
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
    $ch = "<table border='1'><tr><th>idjourassocie</th><th>titreevent</th><th>heuredebut</th><th>heurefin</th><th>isfullday</th><th>color</th><th>nouveau</th></tr><tr>";
    $ch.= "<td>".$this->idjourassocie."</td>";
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
