<?php

$q = "SELECT * FROM drinkers";

$result = $conn->query($q);

$arr = [];

if ($result->num_rows > 0) {
  // output data of each row
  while($row = $result->fetch_assoc()) {
      $arr[] = array(
        "id" => $row["id"],
        "name" => $row["name"],
        "retired" => intval($row["retired"])
      );
  }
} else {
  $arr[] = "Error from get_drinkers.php, no results";
}

?>

    <script>
      const drinkers = <?php echo json_encode($arr);?>;
      drinkers.findById = function (id) {
        for (var i = 0; i < this.length; i++) {
          if (this[i].id == id) return this[i];
        }
        return false;
      }
      drinkers.findMax = function (key) {
        var max = 0;
        var maxUser = {};
        for (var i = 0; i < this.length; i++) {
          if (this[i][key] > max) {
            max = this[i][key];
            maxUser = this[i];
          }
        }
        return maxUser;
      }
      drinkers.sortBy = function (key) {
        return this.concat().sort(function(a, b) {
          var x = a[key]; var y = b[key];
          return ((x < y) ? -1 : ((x > y) ? 1 : 0));
        });
      }
      drinkers.addKey = function (key, valStr) {
        for (var i = 0; i < this.length; i++) {
          var drinker = this[i];
          this[i][key] = eval(valStr);
        }
        return this;
      }
    </script>
