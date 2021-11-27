<?php

include 'header.php';
include 'sqlCon.php';

$received_data = json_decode(file_get_contents("php://input"), true);

$astronautID = $received_data['astronautID'];
$mySet = $received_data['setString'];

try {

    $query = "UPDATE Astronauts SET $mySet WHERE id = '$astronautID'";
    $statement = $connect->prepare($query);
    $statement->execute();
    echo "SqlUpdated";
    
    $statement->closeCursor();
} catch (PDOException $e) {
    echo $e->getMessage();
}

?>