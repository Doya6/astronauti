<?php

include 'header.php';
include 'sqlCon.php';

try {
$query = "SELECT * FROM Astronauts"; 

$statement = $connect->prepare($query);
$statement->execute();

while($row = $statement->fetch(PDO::FETCH_ASSOC))
  {
    $data[] = $row;
  }

  echo json_encode($data);
  
  $statement->closeCursor();
} catch (PDOException $e) {
    echo $e->getMessage();
}  

?>

