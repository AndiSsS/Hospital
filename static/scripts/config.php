<?php
$defaultRowsPerPage = 50;
$opt = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];
$journalAllowed = array('date', 'quantity', 'drug_id', 'patient_id', 'doctor_id');
$patientAllowed = array('name', 'surname', 'patronymic', 'disease_id', 'doctor_id');
$doctorAllowed = array('name', 'surname', 'patronymic', 'mobile_number');
$drugAllowed = array('name', 'provider_id');
$diseaseAllowed = array('name');
$providerAllowed = array('name');
?>