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
    </script>
