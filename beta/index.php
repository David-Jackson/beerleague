

<?php include "scripts/php/sql.php"; // initialize MySQL Connection ?>


<!DOCTYPE html>
<html>
  <head>
    <title>Beer League</title>
    <meta name="viewport" content="initial-scale=1.0">
    <meta charset="utf-8">
    <link rel="shortcut icon" type="image/png" href="assets/Food-Beer-Glass-icon-white.png"/>
    <!--Import Google Icon Font-->
    <link href="http://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <!--Import materialize.css-->
    <link type="text/css" rel="stylesheet" href="/assets/materialize/css/materialize.min.css"  media="screen,projection"/>
    
    
    <?php include "scripts/php/get_drinkers.php"; // loads 'drinkers' object into javascript const ?>
    <?php include "scripts/php/get_trips.php"; // loads 'trips' object into javascript const ?>
    
    <style>
      html, body {
        background-color:#FFF;
      }

      .orange {
        background-color: #FF9800;
      }

      .hidden {
        opacity:0;
        visibility:none;
      }

      nav .brand-logo {
        padding-left:30px;
        padding-right:30px;
        max-height:2.1rem;
      }
    </style>
  </head>
  <body>
    
    <!--Import jQuery before materialize.js-->
    <script type="text/javascript" src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
    <script type="text/javascript" src="/assets/materialize/js/materialize.min.js"></script>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    
    <nav class="orange">
      <div class="nav-wrapper">
        <a href="#" class="brand-logo">Beer League</a>
      </div>
    </nav>

    <div class="row">
      <div class="col s0 m3 hidden">|</div>
      <div class="col s12 m6">
        <div class="card">
          <div class="card-content">
            <span class="card-title">Who'$ Next?</span>
            <h5 id="whosNextText" class="center"></h5>
          </div>
          <div class="card-action">
            <a href="#" onclick="showWhosNext()">Not Here</a>
          </div>
        </div>
      </div>
      <div class="col s0 m3 hidden">|</div>
    </div>

    <div class="row">
      <div class="col s0 m1 hidden">|</div>
      <div class="col s12 m5">
        <div class="card">
          <div class="card-content">
            <span class="card-title">Standings</span>
            <p id="standingsText"></p>
          </div>
        </div>
      </div>
      <div class="col s12 m5">
        <div class="card">
          <div class="card-content">
            <span class="card-title">Leaderboard</span>
            <p id="leaderboardText"></p>
          </div>
        </div>
      </div>
      <div class="col s0 m1 hidden">|</div>
    </div>

    <div class="fixed-action-btn" style="bottom: 24px; right: 24px;">
      <a class="btn-floating btn-large blue-grey" href="new/">
        <img src="assets/Food-Beer-Glass-icon-white.png" alt="" height="30px" width="30px" style="margin:13px 0 0 0">
      </a>
    </div>
    
    <!-- Member Modal -->
    <div id="memberModal" class="modal bottom-sheet black-text">
      <div class="modal-content">
        <h4 id="memberName"></h4>
        <h5 id="memberBondNumber"></h5>
        <p id="memberInfo"></p>
      </div>
    </div>
    
    <script>
      $(document).ready(function(){
        // the "href" attribute of .modal-trigger must specify the modal ID that wants to be triggered
        $(".modal-trigger").leanModal();
      });
      $('.datepicker').pickadate({
        selectMonths: true, // Creates a dropdown to control month
        selectYears: 15 // Creates a dropdown of 15 years to control year
      });

      function showModal() {
        $("#modal1").openModal();
      }
      function showMemberModal(id) {
        document.getElementById("memberName").innerHTML = "";
        document.getElementById("memberBondNumber").innerHTML = "";
        var infoDiv = document.getElementById("memberInfo");
        infoDiv.innerHTML = "";
        $("#memberModal").openModal();
        document.getElementById("memberName").innerHTML = summary[id].name;
        document.getElementById("memberBondNumber").innerHTML = id;
        var currencyAppend = "";
        if (summary[id].delta < 0) {currencyAppend = "-";}
        // Current Standing
        infoDiv.innerHTML += "Current Standing: " + currencyAppend + "$" + Math.abs(summary[id].delta.toFixed(2)) + "<br>";
        // Total Spent
        infoDiv.innerHTML += "Total Spent: $" + summary[id].total.toFixed(2) + "<br>";
        // Total Trips
        infoDiv.innerHTML += "Total Trips: " + summary[id].tripCount + "<br>";
        // Total Times Paid
        infoDiv.innerHTML += "Total Times Paid: " + summary[id].paidCount;

      }

      var summary;
      var standings = [];
      var leaderboard = [];
      var whosNext = [];
      var whosNextIndex = 0;


      function summarize() {
        summary = {};
        for (var i = 0; i < drinkers.length; i++) {
          summary[drinkers[i].id] = drinkers[i];
          summary[drinkers[i].id].delta = 0;
          summary[drinkers[i].id].total = 0;
          summary[drinkers[i].id].paidCount = 0;
          summary[drinkers[i].id].tripCount = 0;
        }
        for (var i = 0; i < trips.length; i++) {
          var trip = trips[i];
          trip.tab = parseInt(trip.tab);
          summary[trip.paidBy].delta += trip.tab;
          summary[trip.paidBy].paidCount++;
          for (var j = 0; j < trip.attendees.length; j++) {
            summary[trip.attendees[j]].delta -= (trip.tab/trip.attendees.length);
            summary[trip.attendees[j]].total += (trip.tab/trip.attendees.length);
            summary[trip.attendees[j]].tripCount++;
          }
        }
        for (var key in summary) {
          if (summary.hasOwnProperty(key)) {
            var val = summary[key];
            if (!val.retired) {
              whosNext.push([key, val.delta]);
              standings.push([key, val.delta]);
            }
            leaderboard.push([key, val.total]);
          }
        }
        whosNext.sort(function(a, b){return a[1]-b[1]});
        leaderboard.sort(function(a, b){return b[1]-a[1]});
        showWhosNext();
        showStandings();
        showLeaderboard();
      }
      function showWhosNext() {
        document.getElementById('whosNextText').innerHTML = summary[whosNext[whosNextIndex][0]].name;
        whosNextIndex = (whosNextIndex + 1) % whosNext.length;
      }
      function showStandings() {
        var div = document.getElementById('standingsText');

        var thead = document.createElement("thead");
        thead.innerHTML = '<tr><th data-field="id">Name</th><th data-field="standing">Current</th></tr>';

        insertTable(div,thead,standings);
      }
      function showLeaderboard() {
        var div = document.getElementById('leaderboardText');

        var thead = document.createElement("thead");
        thead.innerHTML = '<tr><th data-field="id">Name</th><th data-field="standing">Total</th></tr>';

        insertTable(div,thead,leaderboard);
      }

      function insertTable(div, thead, data) {
        var table = document.createElement("table");

        var tbody = document.createElement("tbody");

        for (var i = 0; i < data.length; i++) {
          var row = document.createElement("tr");
          var name = document.createElement("td");
          var total = document.createElement("td");

          name.innerHTML = "<a onclick='showMemberModal(\"" + summary[data[i][0]].id + "\")'>" + summary[data[i][0]].name + "</a>";
          total.innerHTML = "$" + data[i][1].toFixed(2);
          row.appendChild(name);
          row.appendChild(total);
          tbody.appendChild(row);
        }
        table.appendChild(thead);
        table.appendChild(tbody);

        div.appendChild(table);
      }

      
      summarize();
      
    </script>
    
  </body>
</html>

