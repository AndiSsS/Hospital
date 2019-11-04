<?php 
require 'static/scripts/db.php'; 
header('Content-Type: application/json');

$pdo = dbConnect();

if($_GET['get']=='providers'){
	$stmt = $pdo->query('SELECT id, name FROM providers WHERE is_active=true');
	$json = json_encode($stmt->fetchAll());
	echo preg_replace( "/:(\d+)/", ':"$1"', $json);
}
elseif ($_GET['get']=='drugs') {
	$stmt = $pdo->query('SELECT id, name FROM drugs WHERE is_active=true');
	$json = json_encode($stmt->fetchAll());
	echo preg_replace( "/:(\d+)/", ':"$1"', $json);
}
elseif ($_GET['get']=='diseases') {
	$stmt = $pdo->query('SELECT id, name FROM diseases WHERE is_active=true');
	$json = json_encode($stmt->fetchAll());
	echo preg_replace( "/:(\d+)/", ':"$1"', $json);
}
elseif ($_GET['get']=='patients') {
	$stmt = $pdo->query('SELECT id, surname, name, patronymic FROM patients WHERE is_active=true');
	$json = json_encode($stmt->fetchAll());
	echo preg_replace( "/:(\d+)/", ':"$1"', $json);
}
elseif ($_GET['get']=='doctors') {
	$stmt = $pdo->query('SELECT id, surname, name, patronymic FROM doctors WHERE is_active=true');
	$json = json_encode($stmt->fetchAll());
	echo preg_replace( "/:(\d+)/", ':"$1"', $json);
}
elseif ($_GET['get']=='chambers') {
	$stmt = $pdo->query('SELECT id, number FROM chambers WHERE is_active=true');
	$json = json_encode($stmt->fetchAll());
	echo preg_replace( "/:(\d+)/", ':"$1"', $json);
}
elseif ($_GET['get']=='beds') {
	$stmt = $pdo->query('SELECT id, number FROM beds WHERE is_active=true');
	$json = json_encode($stmt->fetchAll());
	echo preg_replace( "/:(\d+)/", ':"$1"', $json);
}

$pdo = null;
$stmt = null;

?>