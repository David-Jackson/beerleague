<?php 
// This is the /new/ page that allows a user to add a new trip to the beer league 
?>

<?php include "../scripts/php/sql.php"; // initialize MySQL Connection in the $conn variable ?>

<?php
// if GET parameters include 'add' then don't show the entry form, just add
if (isset($_GET["add"])) {
  
  function redirect($url, $statusCode = 303) {
     header('Location: ' . $url, true, $statusCode);
     die();
  }
  function gotAllParameters() {
    $params = array("date", "bar", "paidBy", "tab", "attendees");
    $json = [];
    $getParams = array();
    foreach ($_GET as $key => $item) {
      if (in_array($key, $params)) {
        array_push($getParams, $key);
        $json[$key] = mysql_escape_string($item);
      }
    }
    if (count($params) == count($getParams)) {
      return $json;
    } else {
      return null;
    }
  }
  
  $x = gotAllParameters();
  
  $date = $x['date'];
  $bar = $x['bar'];
  $paidBy = $x['paidBy'];
  $tab = $x['tab'];
  $attendees = $x['attendees'];
  
  if ($x) {
    $sql = "INSERT INTO trips (date, bar, paidBy, tab, attendees) VALUES ('$date', '$bar', '$paidBy', '$tab', '$attendees')";

    if ($conn->query($sql) === TRUE) {
      echo "<script>window.location = '../';</script>";
    } else {
      echo "Error: try again later. <a href='../'>Go back.</a>";
    } 
  
    $conn->close();
    die();
  }
  
}

?>


<!DOCTYPE html>
<html>
  <head>
    <title>New Entry</title>
    <meta name="viewport" content="initial-scale=1.0">
    <meta charset="utf-8">
    <link rel="shortcut icon" type="image/png" href="../assets/Food-Beer-Glass-icon-white.png"/>
    <!--Import Google Icon Font-->
    <link href="http://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <!--Import materialize.css-->
    <link type="text/css" rel="stylesheet" href="/assets/materialize/css/materialize.min.css"  media="screen,projection"/>
    
    
    <?php include "../scripts/php/get_drinkers.php"; // loads 'drinkers' object into javascript const ?>
    
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
      
      .collapsible, .collapsible-header {
        box-shadow: none;
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
            <span class="card-title">New Entry</span>
            <form action="newEntry.php" method="get" id="newForm">
              <input id="date" type="date" name="date" placeholder="Date">
              <select id="bar" class="browser-default" name="bar">
                <option value="" disabled selected>Bar</option>
                <option value="Torch">The Torch</option>
                <option value="White Horse">White Horse</option>
                <option value="Soggy Bottom">Soggy Bottom</option>
                <option value="Scooters">Scooter's</option>
                <option value="Fouches">Fouches</option>
              </select>
              <select class="browser-default" name="paidBy" id="paidBy">
                <option value="" disabled selected>Paid By</option>
              </select>
              <input id="tab" type="number" name="tab" placeholder="Enter Tab">
              
              <ul class="collapsible" data-collapsible="accordion">
                <li>
                  <div class="collapsible-header z-depth-1"><i class="material-icons">people</i>Attendees</div>
                  <div class="collapsible-body">
                    <ul class="collection" id="attendees-list"></ul>
                  </div>
                </li>
              </ul>
              
            </form>
              
          </div>
          <div class="card-action">
            <a onclick="submit()">Submit</a>
          </div>
        </div>
      </div>
      <div class="col s0 m3 hidden">|</div>
    </div>
    
    <div class="fixed-action-btn" style="bottom: 24px; right: 24px;">
      <a class="btn-floating btn-large blue-grey" href="../">
        <i class="large material-icons">undo</i>
      </a>
    </div>
    
    <script>
      $(document).ready(function(){
        $('.collapsible').collapsible();
        populatePaidBy();
        populateAttendees();
      });
      
      function handleClick(thing, e, x) {
        e.preventDefault();
        var amount = thing.parentElement.getElementsByClassName("amount")[0];
        if (!isNaN(parseInt(amount.innerHTML))) {
            amount.innerHTML = Math.max(0, parseInt(amount.innerHTML) + (x * 1));
        } else {
            amount.innerHTML = 0;
        }
      }
      
      function populatePaidBy() {
        var div = document.getElementById("paidBy");
        for (var i = 0; i < drinkers.length; i++) {
            var select = document.createElement("option");
            select.setAttribute("value", drinkers[i].id);
            select.innerHTML = drinkers[i].name;
            div.appendChild(select.cloneNode(true));
        }
      }
      
      function populateAttendees() {
        var attendees = document.getElementById("attendees-list");
        for (var i = 0; i < drinkers.length; i++) {
          var name = drinkers[i].name;
          var id = drinkers[i].id;
          
          var li = document.createElement("li");
          li.className = "collection-item dismissable";
          
          var nameSpan = document.createElement("span");
          nameSpan.className = "name";
          nameSpan.innerHTML = name;
          
          var amountSpan = document.createElement("span");
          amountSpan.setAttribute("style", "position:absolute;right:5px;");
          
          var minus = document.createElement("input");
          minus.className = "qtyminus";
          minus.setAttribute("type", "button");
          minus.setAttribute("value", "-");
          
          var plus = document.createElement("input");
          plus.className = "qtyplus";
          plus.setAttribute("type", "button");
          plus.setAttribute("value", "+");
          
          var amount = document.createElement("span");
          amount.className = "amount";
          amount.setAttribute("id-val", id);
          amount.setAttribute("style", "padding-left:5px;padding-right:5px;");
          amount.innerHTML = 0;
          
          amountSpan.appendChild(minus);
          amountSpan.appendChild(amount);
          amountSpan.appendChild(plus);
          
          li.appendChild(nameSpan);
          li.appendChild(amountSpan);
          
          attendees.appendChild(li);
        }
        $('.qtyplus').click(function(e){
          handleClick(this, e, 1);
        });
        $('.qtyminus').click(function(e){
          handleClick(this, e, -1);
        });
      }
      
      function submit() {
        var data = {};
        data.date = document.getElementById("date").value;
        data.bar = document.getElementById("bar").value;
        data.paidBy = document.getElementById("paidBy").value;
        data.tab = document.getElementById("tab").value;
        data.attendees = [];
        
        var amounts = document.getElementsByClassName("amount");
        for (var i = 0; i < amounts.length; i++) {
          var amount = parseInt(amounts[i].innerHTML);
          var id = amounts[i].getAttribute("id-val");
          for (var j = 0; j < amount; j++) {
            data.attendees.push(id);
          }
        }
        
        data.attendees = data.attendees.join(",");
        
        var req = [];
        
        for (var key in data) {
          req.push(key + "=" + data[key]);
        }
        
        window.location = window.location.pathname + "?add&" + req.join("&");
        
      }
      
    </script>
    
    
  </body>
</html>

