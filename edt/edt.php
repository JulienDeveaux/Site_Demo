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
      $events = calendrier::getAll();
        for ($i = 0; $i < sizeof($events); $i++) {
            echo "{
            \"title\": \"" . $events[$i]->gettitreEvent() . "\",
            \"start\": \"" . $events[$i]->getheuredebut() . "\",
            \"end\": \"" . $events[$i]->getheurefin() . "\",
            \"allDay\": " . $events[$i]->getisfullday() . "
          },";
        }
      ?>

    ];
    var calendarEl = document.getElementById('calendar');
    var calendar = new FullCalendar.Calendar(calendarEl, {
      initialView: 'timeGridWeek',
      weekNumbers: true,
      nowIndicator: true,
      editable: true,
      eventStartEditable: true,
      eventResizableFromStart: true,
      eventDurationEditable: true,
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
      eventDragStop: (infos) => {
        if(!confirm("Etes vous sur de vouloir déplacer cet évènement ?")) {
          infos.revert();
        }
      }
      /*eventClick: function(arg) {
        // opens events in a popup window
        window.open(arg.event.url, '_blank', 'width=700,height=600');
        // prevents current tab from navigating
        arg.jsEvent.preventDefault();
      }*/
      //dateClick: function (info) {console.log('click ' + info.dateStr)} // fire quand on click sur une date
    });
    calendar.setOption('locale', 'fr');
    calendar.render();

    //ajout d'un élément après render
    event.push({
      "title": "Mon test",
      "start": "2021-08-19 10:00:00",
      "end": "2021-08-19 11:00:00",
      "allDay": false
    });
    calendar.removeAllEvents();
    calendar.removeAllEventSources();
    calendar.addEventSource(event);
  });
</script>
</body>

</html>
