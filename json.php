<?php 
require 'static/scripts/db.php'; 
header('Content-Type: application/json');

$pdo = dbConnect();

if($_GET['get']=='providers'){
	$stmt = $pdo->query('SELECT id, name FROM providers');
	$json = json_encode($stmt->fetchAll());
	echo preg_replace( "/:(\d+)/", ':"$1"', $json);
}
elseif ($_GET['get']=='drugs') {
	$stmt = $pdo->query('SELECT id, name FROM drugs');
	$json = json_encode($stmt->fetchAll());
	echo preg_replace( "/:(\d+)/", ':"$1"', $json);
}
elseif ($_GET['get']=='diseases') {
	$stmt = $pdo->query('SELECT id, name FROM diseases');
	$json = json_encode($stmt->fetchAll());
	echo preg_replace( "/:(\d+)/", ':"$1"', $json);
}
elseif ($_GET['get']=='patients') {
	$stmt = $pdo->query('SELECT id, surname, name, patronymic FROM patients');
	$json = json_encode($stmt->fetchAll());
	echo preg_replace( "/:(\d+)/", ':"$1"', $json);
}
elseif ($_GET['get']=='doctors') {
	$stmt = $pdo->query('SELECT id, surname, name, patronymic FROM doctors');
	$json = json_encode($stmt->fetchAll());
	echo preg_replace( "/:(\d+)/", ':"$1"', $json);
}

$pdo = null;
$stmt = null;

?>