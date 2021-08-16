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
<script>
  document.addEventListener('DOMContentLoaded', function() {
    let event = [
      <?php
      include 'basePDOCalendrier.php';
      $today = getdate();
      $date = date_create($today["year"] ."-". $today["mon"] ."-". $today["mday"]);
      if($today["wday"] == 2) {
        date_sub($date,date_interval_create_from_date_string("2 day"));
      } else if($today["wday"] == 3) {
        date_sub($date,date_interval_create_from_date_string("3 days"));
      } else if($today["wday"] == 4) {
        date_sub($date,date_interval_create_from_date_string("4 days"));
      } else if($today["wday"] == 5) {
        date_sub($date,date_interval_create_from_date_string("5 days"));
      } else if($today["wday"] == 6) {
        date_sub($date,date_interval_create_from_date_string("6 days"));
      } else if($today["wday"] == 7) {
        date_sub($date,date_interval_create_from_date_string("7 days"));
      }
      $lundi = date_format($date,"Y-m-d");
      date_add($date,date_interval_create_from_date_string("1 day"));
      $mardi = date_format($date,"Y-m-d");
      date_add($date,date_interval_create_from_date_string("1 day"));
      $mercredi = date_format($date,"Y-m-d");
      date_add($date,date_interval_create_from_date_string("1 day"));
      $jeudi = date_format($date,"Y-m-d");
      date_add($date,date_interval_create_from_date_string("1 day"));
      $vendredi = date_format($date,"Y-m-d");
      date_add($date,date_interval_create_from_date_string("1 day"));
      $samedi = date_format($date,"Y-m-d");
      date_sub($date,date_interval_create_from_date_string("6 day"));
      $dimanche = date_format($date,"Y-m-d");

      $events = Calendrier::initcalendrier_jour($lundi);
      $finalarray[0] = $events;
      $events = Calendrier::initcalendrier_jour($mardi);
      $finalarray[1] = $events;
      $events = Calendrier::initcalendrier_jour($mercredi);
      $finalarray[2] = $events;
      $events = Calendrier::initcalendrier_jour($jeudi);
      $finalarray[3] = $events;
      $events = Calendrier::initcalendrier_jour($vendredi);
      $finalarray[4] = $events;
      $events = Calendrier::initcalendrier_jour($samedi);
      $finalarray[5] = $events;
      $events = Calendrier::initcalendrier_jour($dimanche);
      $finalarray[6] = $events;
      for($j = 0; $j < sizeof($finalarray); $j++) {
        for ($i = 0; $i < sizeof($finalarray[$j]); $i++) {
          if (sizeof($finalarray[$j]) != 0) {
            echo "{
            \"title\": \"" . $finalarray[$j][$i]->gettitreEvent() . "\",
            \"start\": \"" . $finalarray[$j][$i]->getheuredebut() . "\",
            \"end\": \"" . $finalarray[$j][$i]->getheurefin() . "\",
            \"allDay\": " . $finalarray[$j][$i]->getisfullday() . "
          },";
          }
        }
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
</body>

</html>
