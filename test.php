<?php
require 'static/scripts/dbManager.php';

$pdo = _dbConnect();
for($i = 0; $i < 50; $i++){
	$stmt = $pdo->query('INSERT INTO patients (name,surname,patronymic,disease_id,doctor_id,is_active) VALUES("test","test","test",6,1,1)');
}
print_r($stmt);
?>
