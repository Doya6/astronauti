<?php

include 'header.php';
include 'sqlCon.php';

$phpInput = json_decode(file_get_contents("php://input"), true);

$insertIntoTable = $phpInput['insertIntoTable'];
$insertIntoColumns = $phpInput['insertIntoColumns'];
$insertValues = $phpInput['insertValues'];
try {
	$query = "INSERT INTO $insertIntoTable ($insertIntoColumns) VALUES ($insertValues)";  

	$statement = $connect->prepare($query);
	$statement->execute();

	$arr = $statement->errorInfo();
	if (json_encode($arr[2]) == 'null'){ 
		$result = "sentToSql";
	} else {
		$result = ($arr[2]);
	}
	echo $result;

$statement->closeCursor();
} catch (PDOException $e) {
    echo $e->getMessage();
}
?>