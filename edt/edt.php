<!doctype html>
<html class="no-js" lang="fr">

<head>
  <meta charset="utf-8">
  <title>Calendrier</title>

  <link rel="manifest" href="site.webmanifest">
  <link rel="icon" href="../favicon.ico">
  <link href="https://fonts.googleapis.com/css?family=Poppins" rel="stylesheet">
  <link rel="stylesheet" href="../css/main.css">

  <link href="../js/toastr/toastr.scss" rel="stylesheet">
  <link href='../js/fullcalendar/main.css' rel='stylesheet'/>
  <script src="../js/fullcalendar/main.js"></script>
  <script src="../js/jquery/jquery-3.6.0.js"></script>
  <script src="../js/toastr/toastr.js"></script>
</head>

<body style="background-image: none; background-color: lightgray">
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
        if (link.style.animation) {
          link.style.animation = '';
        } else {
          link.style.animation = `navLinkFade 0.5s ease forwards ${index / 7 + 0.5}s`;
        }
      });
    });
  }
  navSlide();
</script>
<div id="myModal" class="modal">
  <div class="modal-content">
    <span class="close">&times;</span>
    <form onsubmit="return traitement();">
      <h1 id="titreModal"></h1>
      <h3>Titre</h3>
      <input type="text" name="titreEvent" id="titreEvent" required>
      <h3>Date</h3>
      <h6>Du <input type="date" name="dateEventStart" id="dateEventStart" required> au <input type="date" name="dateEventEnd" id="dateEventEnd" required></h6>
      <h3>Heure</h3>
      <h6>De <input type="time" name="heureEventStart" id="heureEventStart" minlength="5" maxlength="8" required> à
        <input type="time" name="heureEventEnd" id="heureEventEnd" minlength="5" maxlength="8" required></h6>
      <h3>Couleur <input type="color" name="colorEvent" id="colorEvent" required></h3>
      <h3><input type="checkbox" name="isFullDay" id="isFullDay"> journée entière ?</h3>
      <p style="line-height: 5px;"></p>
      &nbsp;
      <hr>
      <br>
      <button type="button" id="DeleteAction" class="modalbutton">Supprimer</button>
      <button type="submit" class="modalbutton" style="float: right">Valider</button>
    </form>
  </div>

</div>

<div id="calendar"></div>
<script>
  var eventClicked;
  var calendar;
  var event;
  document.addEventListener('DOMContentLoaded', function () {
    event = [
      <?php
      include 'basePDOCalendrier.php';
      $events = calendrier::getAll();
        for ($i = 0; $i < sizeof($events); $i++) {
            echo "{
            \"title\": \"" . $events[$i]->gettitreEvent() . "\",
            \"start\": \"" . $events[$i]->getheuredebut() . "\",
            \"end\": \"" . $events[$i]->getheurefin() . "\",
            \"color\": \"" . $events[$i]->getcolor() . "\",
            \"allDay\": " . $events[$i]->getisfullday() . "
          },";
        }
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
      customButtons: {
        ajoutButton: {
          text: 'Ajouter un évènement',
          click: function () {
            var modal = document.getElementById("myModal");
            var span = document.getElementsByClassName("close")[0];
              modal.style.display = "block";
            span.onclick = function () {
              modal.style.display = "none";
            }
            window.onclick = function (event) {
              if (event.target === modal) {
                modal.style.display = "none";
              }
            }
            modal.style.display = "block";
            document.getElementById("titreModal").innerHTML = "Ajouter un évènement";
            // fin setup modal
            // empty modal
            document.getElementById("titreEvent").value = "";
            document.getElementById("dateEventStart").value = "";
            document.getElementById("dateEventEnd").value = "";
            document.getElementById("heureEventStart").value = "";
            document.getElementById("heureEventEnd").value = "";
            document.getElementById("isFullDay").checked = false;
            document.getElementById("heureEventStart").disabled = false;
            document.getElementById("heureEventEnd").disabled = false;
            document.getElementById("colorEvent").value = '#3788d8';
          }
        }
      },
      headerToolbar: {
        left: 'prev next ajoutButton',
        center: 'title',
        right: 'today dayGridMonth timeGridWeek timeGridDay'
      },
      buttonText: {
        day: 'Jour',
        today: 'Aujourd\'hui',
        month: 'Mois',
        week: 'Semaine',
      },
      firstDay: 1,
      allDayText: 'Entier',
      weekText: 'S',
      events: event,
      eventDragStop: (infos) => {
        if (!confirm("Etes vous sur de vouloir déplacer cet évènement ?")) {
          infos.revert();
        }
      },
      eventClick: function (arg) {
        var modal = document.getElementById("myModal");
        var btn = $(arg.el)[0];
        var span = document.getElementsByClassName("close")[0];
        btn.onclick = function () {
          modal.style.display = "block";
        }
        span.onclick = function () {
          modal.style.display = "none";
        }
        window.onclick = function (event) {
          if (event.target === modal) {
            modal.style.display = "none";
          }
        }
        modal.style.display = "block";
        document.getElementById("titreModal").innerHTML = "Modifier l'évènement";
        // fin setup modal
        // début mise à jour infos dans la modale
        document.getElementById("titreEvent").value = arg.event._def.title;

        var date = new Date(arg.event._instance.range.start.toDateString());
        date.setDate(date.getDate() + 1);
        document.getElementById("dateEventStart").valueAsDate = date;
        date = new Date(arg.event._instance.range.end.toDateString());
        date.setDate(date.getDate() + 1);
        document.getElementById("dateEventEnd").valueAsDate = date;

        document.getElementById("heureEventStart").value =
          (((arg.event._instance.range.start.getHours() - 2 + "").length !== 2) ? "0" + (arg.event._instance.range.start.getHours() - 2) : arg.event._instance.range.start.getHours() - 2)
          + ":" + (((arg.event._instance.range.start.getMinutes() + "" ).length !== 2) ? "0"+ arg.event._instance.range.start.getMinutes() : arg.event._instance.range.start.getMinutes());
        // ((arg.event._instance.range.start.getMinutes() + "" ).length !== 2) => le + "" s'assure que l'on à un string et non un number
        document.getElementById("heureEventEnd").value =
          (((arg.event._instance.range.end.getHours() - 2 + "").length !== 2) ? "0" + (arg.event._instance.range.end.getHours() - 2) : arg.event._instance.range.end.getHours() - 2)
          + ":" + (((arg.event._instance.range.end.getMinutes() + "" ).length !== 2) ? "0" + arg.event._instance.range.end.getMinutes(): arg.event._instance.range.end.getMinutes());

        if (arg.event._def.allDay === true) {
          document.getElementById("isFullDay").checked = true;
          document.getElementById("heureEventStart").disabled = true;
          document.getElementById("heureEventEnd").disabled = true;
        } else {
          document.getElementById("isFullDay").checked = false;
          document.getElementById("heureEventStart").disabled = false;
          document.getElementById("heureEventEnd").disabled = false;
        }

        if (arg.event._def.ui.backgroundColor === "") {
          document.getElementById("colorEvent").value = '#3788d8';
        } else {
          document.getElementById("colorEvent").value = arg.event._def.ui.backgroundColor;
        }
        eventClicked = {
          "title": arg.event._def.title,
          "start": document.getElementById("dateEventStart").value + " " + document.getElementById("heureEventStart").value,
          "end": document.getElementById("dateEventEnd").value + " " + document.getElementById("heureEventEnd").value,
          "color": document.getElementById("colorEvent").value,
          "allDay": arg.event._def.allDay
        };
      }
    });
    calendar.setOption('locale', 'fr');
    calendar.render();
  });

  var buttonDelete = document.getElementById("DeleteAction");
  buttonDelete.onclick = function () {
    var index = event.findIndex(x => x.title === eventClicked.title && x.start === eventClicked.start && x.end === eventClicked.end && x.allDay === eventClicked.allDay);
    if (index > -1) {
      event.splice(index, 1);
      $.ajax({ url: 'BDInteract.php',
        data: { remove: eventClicked },
        type: 'post',
        success: function(out) {
          if(out == "") {
            toastr.success('Elément supprimé');
          }
        }
      });
    } else {
        toastr.error("élément non trouvé :(");
    }
    calendar.removeAllEvents();
    calendar.removeAllEventSources();
    calendar.addEventSource(event);
    var modal = document.getElementById("myModal");
    modal.style.display = "none";
  }

  var bouttonIsFullDay = document.getElementById("isFullDay");
  var oldHeureStart = "";
  var oldHeudEnd = "";
  bouttonIsFullDay.addEventListener('change', function() {
    if(this.checked) {
      document.getElementById("heureEventStart").disabled = true;
      document.getElementById("heureEventEnd").disabled = true;
      oldHeureStart = document.getElementById("heureEventStart").value;
      oldHeudEnd = document.getElementById("heureEventEnd").value;
      document.getElementById("heureEventStart").value = "";
      document.getElementById("heureEventEnd").value = "";
    } else {
      document.getElementById("heureEventStart").disabled = false;
      document.getElementById("heureEventEnd").disabled = false;
      document.getElementById("heureEventStart").value = oldHeureStart;
      document.getElementById("heureEventEnd").value = oldHeudEnd;
    }
  });

  function traitement() {
    var titre = document.getElementById("titreEvent").value;
    var dateStart = $('#dateEventStart').val();
    var dateEnd = $('#dateEventEnd').val();
    var heureStart = document.getElementById("heureEventStart").value;
    var heureEnd = document.getElementById("heureEventEnd").value;
    var color = document.getElementById("colorEvent").value;
    var isFullDay = document.getElementById("isFullDay").checked;

    var nouveauEvent = {
      "title": titre,
      "start": dateStart + " " + heureStart,
      "end": dateEnd + " " + heureEnd,
      "color": color,
      "allDay": isFullDay
    };
    if(document.getElementById("titreModal").innerHTML === "Modifier l'évènement") {
      var index = event.findIndex(x => x.title === eventClicked.title && x.start === eventClicked.start && x.end === eventClicked.end && x.allDay === eventClicked.allDay);
      if (index > -1) {
        event.splice(index, 1);
        event.push(nouveauEvent);
        $.ajax({
          url: 'BDInteract.php',
          data: {update: eventClicked, maj: nouveauEvent},
          type: 'post',
          success: function (out) {
          if(out == "") {
            toastr.success('modification enregistrée');
          }
          }
        });
      } else {
        toastr.error("élément non trouvé :(");
      }
    } else {
      event.push(nouveauEvent);
      $.ajax({
        url: 'BDInteract.php',
        data: {nouveau: nouveauEvent},
        type: 'post',
        success: function (out) {
          if(out == "") {
            toastr.success('Evènement enregistré');
          }
        }
      });
    }
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
