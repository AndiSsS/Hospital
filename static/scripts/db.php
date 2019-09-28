<?php 

function dbConnect(){
	global $opt;
	return new PDO("mysql:dbname=Hospital;host=localhost;charset=utf8", 'root', 'MilkaMan0', $opt);
}

function is_row_exists($row_id){
	$pdo = dbConnect();
	$stmt = $pdo->prepare('SELECT COUNT(*) FROM patients WHERE id=?');
	$stmt->execute(array($row_id));
	$exists = $stmt->fetchColumn();
	$pdo = null;
	$stmt = null;
	if($exists == 1)
		return true;
	else 
		return false;
}

function add_patient(&$error){
	global $patientAllowed;
	$pdo = dbConnect();
	$stmt = $pdo->prepare('INSERT INTO patients SET'._pdoSet($patientAllowed, $values));

	if(_checkErrors($stmt, $error))
		return false;

	$stmt->execute($values);
	$pdo = null;
	$stmt = null;
	header('Location: /patients');
}

function edit_patient($patient_id, &$error){
	global $patientAllowed;
	$pdo = dbConnect();
	$stmt = $pdo->prepare('UPDATE patients SET'._pdoSet($patientAllowed, $values).' WHERE id=:id');

	if(_checkErrors($stmt, $error))
		return false;

	$values['id'] = $patient_id;
	$stmt->execute($values);
	$pdo = null;
	$stmt = null;
	header("Location: /patient?id=$patient_id");
}

function eliminate_patient($patient_id){
	$pdo = dbConnect();
	$stmt = $pdo->prepare("UPDATE patients SET is_active=false WHERE id=?");
	$stmt->execute(array($patient_id));
	$pdo = null;
	$stmt = null;
	header("Location: /patient?id=$patient_id");
}

function _pdoSet($allowed, &$values, $source = array()) {
	$set = '';
	$values = array();
	if (!$source) $source = &$_POST;
	foreach ($allowed as $field) {
		if (isset($source[$field])) {
			$set.="`".str_replace("`","``",$field)."`". "=:$field, ";
			$values[$field] = $source[$field];
		}
	}
	return substr($set, 0, -2); 
}

function _checkErrors($stmt, &$error){
	global $patientAllowed;
	foreach ($patientAllowed as $field) {
		if($_POST[$field] === ''){
			$error = 'Заповніть всі поля!';
			return true;
		}
	}
	if(!isset($stmt)){
		$error = 'Виникла помилка. Спробуйте ще раз.';
		return true;
	}
}

?>