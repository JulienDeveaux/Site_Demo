<!doctype html>
<html class="no-js" lang="fr">

<head>
  <meta charset="utf-8">
  <title>Calendrier</title>

  <link rel="manifest" href="site.webmanifest">
  <link rel="icon" href="../favicon.ico">
  <link href="https://fonts.googleapis.com/css?family=Poppins" rel="stylesheet">
  <link rel="stylesheet" href="../css/main.css">

  <link href='../js/fullcalendar/main.css' rel='stylesheet'/>
  <script src="../js/fullcalendar/main.js"></script>
</head>

<body style="background-image: none; background-color: lightskyblue">
<nav>
  <div class="logo"><h4>Calendrier</h4></div>
  <ul class="nav-links">
    <li><a href="../index.html">Accueil</a></li>
    <li><a href="../Morpion.html">Morpion</a></li>
    <li><a href="../Game.html" style="color: red">Jeu Dinosaure</a></li>
    <li><a href="../Quantik/quantik.php">Quantik</a></li>
  </ul>
  <div class="MenuBar">
    <div class="l1"></div>
    <div class="l2"></div>
    <div class="l3"></div>
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
          link.style.animation = `navLinkFade 0.5s ease forwards ${index / 7 + 0.5}s`;
        }
      });
    });
  }
  navSlide();
</script>
<div id="calendar"></div>
<?php
include 'basePDOEvent.php';
//include 'basePDOJours.php';
$events = Event::getAll();
for($i = 0; $i < sizeof($events); $i++) {
  echo $events[$i];
}
?>
<script>
  document.addEventListener('DOMContentLoaded', function() {
    /*let event = [{
      "title": "mon titre",
      "start": "2021-08-11 10:00:00",       // select * from jours where datejour = '11-08-2021' => idjour 2460 datejour 11-08-2021
      "end": "2021-08-11 11:00:00",         // INSERT INTO event VALUES (2460, 'Mon Titre', '2021-08-11 10:00:00', '2021-08-11 11:00:00', false);
      "backgroundColor": "#839c49",         // select * from event natural join jours where datejour = '11-08-2021'
      "allDay": true
    }, {
      "title": "mon titre2",
      "start": "2021-08-11 11:00:00",
      "end": "2021-08-11 12:00:00"
    }];*/
    let event = [
      <?php
      $events = Event::getAll();
      for($i = 0; $i < sizeof($events); $i++) {
        echo "{
        \"title\": \"" . $events[$i]->gettitreevent() . "\",
        \"start\": \"" . $events[$i]->getheuredebut() . "\",
        \"end\": \"" . $events[$i]->getheurefin() . "\",
        }";
      }
      ?>

    ];
    var calendarEl = document.getElementById('calendar');
    var calendar = new FullCalendar.Calendar(calendarEl, {
      initialView: 'timeGridWeek',
      weekNumbers: true,
      nowIndicator: true,
      editable: true,
      headerToolbar: {
        left: 'prev, next, today',
        center: 'title',
        right: 'dayGridMonth, timeGridWeek'
      },
      buttonText:  {
        today: 'Aujourd\'hui',
        month: 'Mois',
        week: 'Semaine',
      },
      events: event,
      eventDrop: (infos) => {
        if(!confirm("Etes vous sur de vouloir déplacer cet évènement ?")) {
          infos.revert();
        }
      }
      //dateClick: function (info) {console.log('click ' + info.dateStr)} // fire quand on click sur une date
    });
    calendar.setOption('locale', 'fr');
    calendar.render();
  });
</script>
<script>
  let res = "";
  let annee = 2015;
  let mois = 1;
  let jour = 1;
  let moistxt = "";
  let jourtxt = "";
  let serial = 1
  for(annee; annee < 2040; annee++) {
    for(mois = 1; mois < 13; mois++) {
      for(jour = 1; jour < 32; jour++) {
        if(mois < 10) {
          moistxt = "" + 0 + mois;
        } else {
          moistxt = mois;
        }
        if(jour < 10) {
          jourtxt = "" + 0 + jour;
        } else {
          jourtxt = jour;
        }
        res += "INSERT INTO jours VALUES(nextval('jours_idjour_seq'::regclass), '" + jourtxt + "-" + moistxt + "-" + annee + "');";
      }
    }
  }
  console.log(res);
</script>
</body>

</html>
