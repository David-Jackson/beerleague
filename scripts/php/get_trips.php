<?php

$q = "SELECT * FROM trips";

$result = $conn->query($q);

$arr = [];

if ($result->num_rows > 0) {
  // output data of each row
  while($row = $result->fetch_assoc()) {
      $arr[] = array(
        "date" => $row["date"],
        "bar" => $row["bar"],
        "tab" => floatval($row["tab"]),
        "paidBy" => $row["paidBy"],
        "attendees" => explode(",", $row["attendees"])
      );
  }
} else {
  $arr[] = "Error from get_drinkers.php, no results";
}

?>

    <script>
      const trips = <?php echo json_encode($arr);?>;
    </script>
