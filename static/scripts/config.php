<?php
$defaultRowsPerPage = 50;
$opt = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];
$journalAllowed = array('quantity', 'drug_id', 'patient_id', 'doctor_id', 'type');
$journal_record_types = array('intake', 'outgo');
$patientAllowed = array('name', 'surname', 'patronymic', 'disease_id', 'doctor_id', 'chamber_id');
$doctorAllowed = array('name', 'surname', 'patronymic', 'mobile_number');
$drugAllowed = array('name', 'provider_id');
$diseaseAllowed = array('name');
$providerAllowed = array('name');
$expendableMaterialAllowed = array('name', 'quantity');
$apparatusAllowed = array('name', 'doctor_id');
$chamberAllowed = array('number');
$bedAllowed = array('number', 'chamber_id');
?>