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
    <script src="../js/jquery/jquery-3.6.0.js"></script>
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
    <!-- The Modal -->
    <div id="myModal" class="modal">

      <!-- Modal content -->
      <div class="modal-content">
        <span class="close">&times;</span>
        <form onsubmit="return traitement();return false">
          <h1>Modifier l'évènement</h1>
          <h3>Titre</h3>
          <input type="text" name="titreEvent" id="titreEvent" required>
          <h3>Date</h3>
          <h6>Du <input type="date" name="dateEventStart" id="dateEventStart" required> au <input type="date" name="dateEventEnd" id="dateEventEnd" required></h6>
          <h3>Heure</h3>
          <h6>De <input type="text" name="heureEventStart" id="heureEventStart" minlength="5" maxlength="8" required> à <input type="text" name="heureEventEnd" id="heureEventEnd" minlength="5" maxlength="8" required></h6>
          <h3>couleur <input type="color" name="colorEvent" id="colorEvent" required></h3>
          <h3><input type="checkbox" name="isFullDay" id="isFullDay"> journée entière ?</h3>
          <p style="line-height: 5px;"></p>
          &nbsp;
          <hr>
          <br>
          <input type="submit" value="Valider">
        </form>
      </div>

    </div>

    <div id="calendar"></div>
    <script>
      var calendar;
      var event;
      document.addEventListener('DOMContentLoaded', function() {
        event = [
        <?php
      /*include 'basePDOCalendrier.php';
      $events = calendrier::getAll();
        for ($i = 0; $i < sizeof($events); $i++) {
            echo "{
            \"title\": \"" . $events[$i]->gettitreEvent() . "\",
            \"start\": \"" . $events[$i]->getheuredebut() . "\",
            \"end\": \"" . $events[$i]->getheurefin() . "\",
            \"allDay\": " . $events[$i]->getisfullday() . "
          },";
        }*/
        ?>

        ];
        var calendarEl = document.getElementById('calendar');
        calendar = new FullCalendar.Calendar(calendarEl, {
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
          },
          eventClick: function(arg) {        
            var modal = document.getElementById("myModal");
            var btn = $(arg.el)[0];
            var span = document.getElementsByClassName("close")[0];
            btn.onclick = function() {
              modal.style.display = "block";
            }
            span.onclick = function() {
              modal.style.display = "none";
            }
            window.onclick = function(event) {
              if (event.target == modal) {
                modal.style.display = "none";
              }
            }
            modal.style.display = "block";
        // fin setup modal
        // début mise à jour infos dans la modale
        document.getElementById("titreEvent").setAttribute('value', arg.event._def.title);

        var date = new Date(arg.event._instance.range.start.toDateString());
        date.setDate(date.getDate() + 1);
        document.getElementById("dateEventStart").valueAsDate = date;
        date = new Date(arg.event._instance.range.end.toDateString());
        date.setDate(date.getDate() + 1);
        document.getElementById("dateEventEnd").valueAsDate = date;

        document.getElementById("heureEventStart").setAttribute('value', arg.event._instance.range.start.getHours() - 2 + ":" + arg.event._instance.range.start.getMinutes() + ":" + arg.event._instance.range.start.getSeconds());

        document.getElementById("heureEventEnd").setAttribute('value', arg.event._instance.range.end.getHours() - 2 + ":" + arg.event._instance.range.end.getMinutes() + ":" + arg.event._instance.range.end.getSeconds());

        if(arg.event._def.allDay == true) {
          document.getElementById("isFullDay").checked = true;
        } else {
          document.getElementById("isFullDay").checked = false;
        }

        if(arg.event._def.ui.backgroundColor == "") {
          document.getElementById("colorEvent").setAttribute('value', '#3788d8');
        } else {
          document.getElementById("colorEvent").setAttribute('value', arg.event._def.ui.backgroundColor);
        }
      }
      //dateClick: function (info) {console.log('click ' + info.dateStr)} // fire quand on click sur une date
    });
    calendar.setOption('locale', 'fr');
    calendar.render();

    //ajout d'un élément après render
    event.push({
      "title": "Mon test",
      "start": "2021-08-19 10:12:15",
      "end": "2021-08-19 11:35:54",
      "color" : "#ff8100",
      "allDay": false
    },
    {
      "title": "Mon test2",
      "start": "2021-08-20 10:00:00",
      "end": "2021-08-20 12:00:00",
      "allDay": true
    });
    calendar.removeAllEvents();
    calendar.removeAllEventSources();
    calendar.addEventSource(event);
  });

function traitement() {
  var titre = document.getElementById("titreEvent").getAttribute('value');
  var dateStart = $('#dateEventStart').val();
  var dateEnd = $('#dateEventEnd').val();
  var heureStart = document.getElementById("heureEventStart").getAttribute('value');
  var heureEnd = document.getElementById("heureEventEnd").getAttribute('value');
  var color = document.getElementById("colorEvent").getAttribute('value');
  var isFullDay = document.getElementById("isFullDay").checked;

  event.push({
    "title" : titre,
    "start" : dateStart + " " + heureStart,
    "end" : dateEnd + " " + heureEnd,
    "color" : color,
    "allDay" : isFullDay
  });
  calendar.removeAllEvents();
  calendar.removeAllEventSources();
  calendar.addEventSource(event);
  var modal = document.getElementById("myModal");
  modal.style.display = "none";
  return false;
}
</script>
</body>

</html>
