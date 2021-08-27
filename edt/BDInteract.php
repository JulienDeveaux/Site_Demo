<?php
include 'basePDOCalendrier.php';
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
if(isset($_POST['remove']) && !empty($_POST['remove'])) {
  $_SESSION["remove"]=$_POST['remove'];
  $titre = $_POST['remove']['title'];
  $heuredebut = $_POST['remove']['start'] . "%";
  $heurefin = $_POST['remove']['end'] . "%";
  $events = calendrier::initcalendrier_precis($titre, $heuredebut, $heurefin);
  $events->delete();
} else if(isset($_POST['update']) && !empty($_POST['update']) && isset($_POST['maj']) && !empty($_POST['maj'])) {
  $titre = $_POST['update']['title'];
  $heuredebut = $_POST['update']['start'] . "%";
  $heurefin = $_POST['update']['end'] . "%";
  $events = calendrier::initcalendrier_precis($titre, $heuredebut, $heurefin);
  $events->settitreevent($_POST['maj']['title']);
  $events->setheuredebut($_POST['maj']['start']);
  $events->setheurefin($_POST['maj']['end']);
  $events->setcolor($_POST['maj']['color']);
  $events->setisfullday($_POST['maj']['allDay']);
  $events->save($titre, $heuredebut, $heurefin);
} else if(isset($_POST['nouveau']) && !empty($_POST['nouveau'])) {
  $events = new calendrier();
  $events->settitreevent($_POST['nouveau']['title']);
  $events->setheuredebut($_POST['nouveau']['start']);
  $events->setheurefin($_POST['nouveau']['end']);
  $events->setcolor($_POST['nouveau']['color']);
  $events->setisfullday($_POST['nouveau']['allDay']);
  $events->setNouveau(true);
  echo $events;
  $events->save();
} else {
  $nouveau = new calendrier();
  $nouveau->settitreevent("new event");
  $nouveau->setheuredebut("2021-08-28 10:00");
  $nouveau->setheurefin("2021-08-28 12:00");
  $nouveau->setisfullday("FALSE");
  $nouveau->setcolor("#3788d8");
  $nouveau->save();
  echo "Yey";
}
?>
