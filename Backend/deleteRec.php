<?php

include 'header.php';
include 'sqlCon.php';

$phpInput = json_decode(file_get_contents("php://input"), true);
$table = $phpInput['table']; 
$column = $phpInput['column']; 
$id = $phpInput['recID']; 

try {
	$query = "DELETE FROM $table WHERE $column = :id";  

	$statement = $connect->prepare($query);
	$statement->execute(array(
		"id" => $id
	));
	$arr = $statement->errorInfo();

$statement->closeCursor();
} catch (PDOException $e) {
    echo $e->getMessage();
}


?>