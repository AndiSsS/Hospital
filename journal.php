<?php 
$active_item = 'journal'; 
require 'static/templates/header.html'; 
require 'static/templates/content.php';
require 'static/scripts/helpers.php';  

global $journal_record_types;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	if(isset($_POST['is_'.$journal_record_types[0]]) && $_POST['is_'.$journal_record_types[0]]){
		// if type is intake then insert row without patient and doctor data
		$_POST['patient_id'] = null;
		$_POST['doctor_id'] = null;
		$_POST['type'] = $journal_record_types[0];
	}
	else{
		// if type is outgo then first calculate if there are enough drugs and then insert the row
		$_POST['type'] = $journal_record_types[1];
		$drug_id = isset($_POST['drug_id']) ? $_POST['drug_id'] : -1;
		$drug_id = isset($_POST['drug_id']) ? $_POST['drug_id'] : -1;
		$drug_outgo_quantity = isset($_POST['quantity']) ? $_POST['quantity'] : -1;

		if($drug_outgo_quantity < 1 || $drug_id < 1){
			header("Location: /journal?error=Возникла ошибка. Попробуйте еще раз.");
			die();
		}

		$querySumOfIntake = "SELECT SUM(quantity) as intake_quantity
							 FROM journal 
							 WHERE type='$journal_record_types[0]'
							 AND drug_id=$drug_id";

		$querySumOfOutgo = "SELECT SUM(quantity) as outgo_quantity
							FROM journal 
							WHERE type='$journal_record_types[1]'
							AND drug_id=$drug_id";

		$intake = select_rows($querySumOfIntake, $rows_count)[0]['intake_quantity'];
		$outgo = select_rows($querySumOfOutgo, $rows_count)[0]['outgo_quantity'];
		$drug_quantity = $intake - $outgo;
		
		if($drug_quantity < $drug_outgo_quantity){
			header("Location: /journal?error=Недостаточное количество препарата. В наличии: $drug_quantity");
			die();
		}
	}

	insert_row('journal', $journalAllowed, '/journal', $error);
}
else {
	if(isset($_GET['error'])){
		global $error;
		$error = $_GET['error'];
	}

	$search_clause = "";
	$values = array();
	$rangeStr = get_limit_range($rows_per_page, $page);

	if(isset($_GET['quantity']))
		$search_clause = settle_search(array(
											'quantity'=>'journal.quantity',
											'drug_name'=>'drugs.name',
											'provider_name'=>'providers.name', 	
											'patient_name'=>'patients.name',
											'patient_surname'=>'patients.surname',
											'patient_patronymic'=>'patients.patronymic',
											'disease_name'=>'diseases.name',
											'doctor_name'=>'doctors.name',
											'doctor_surname'=>'doctors.surname',
											'doctor_patronymic'=>'doctors.patronymic', 
											), 
										$values);

$query = "SELECT SQL_CALC_FOUND_ROWS
			journal.date,
			journal.quantity,
			journal.type,
			drugs.id as drug_id,
			drugs.name as drug_name,
			providers.id as provider_id,
			providers.name as provider_name,
			patients.id as patient_id,
			patients.surname as patient_surname,
			diseases.id as patient_disease_id,
			diseases.name as patient_disease_name,
			doctors.id as doctor_id,
			doctors.surname as doctor_surname
			FROM journal 
			JOIN drugs ON journal.drug_id=drugs.id
			JOIN providers ON drugs.provider_id=providers.id
			JOIN patients ON journal.patient_id=patients.id
			JOIN doctors ON journal.doctor_id=doctors.id
			JOIN diseases ON patients.disease_id=diseases.id
			WHERE drugs.is_active=true $search_clause
			ORDER BY journal.id DESC
		    LIMIT $rangeStr";
	}

$content = select_rows($query, $rows_count, $values);

$linksRange = get_links_range($rows_per_page, $rows_count, $page);

foreach ($content as $key => $obj) {
	$content[$key]['drug'] = array("link" => "/drug?id=".$content[$key]['drug_id'], 'value' => $content[$key]['drug_name']);
	unsetValues($content[$key], array('drug_id', 'drug_name'));
	$content[$key]['provider'] = array("link" => "/provider?id=".$content[$key]['provider_id'], 'value' => $content[$key]['provider_name']);
	unsetValues($content[$key], array('provider_id', 'provider_name'));
	$content[$key]['patient'] = array("link" => "/patient?id=".$content[$key]['patient_id'], 'value' => $content[$key]['patient_surname']);
	unsetValues($content[$key], array('patient_id', 'patient_surname'));
	$content[$key]['disease'] = array("link" => "/disease?id=".$content[$key]['patient_disease_id'], 'value' => $content[$key]['patient_disease_name']);
	unsetValues($content[$key], array('patient_disease_id', 'patient_disease_name'));
	$content[$key]['doctor'] = array("link" => "/doctor?id=".$content[$key]['doctor_id'], 'value' => $content[$key]['doctor_surname']);
	unsetValues($content[$key], array('doctor_id', 'doctor_surname'));

	if($content[$key]['type'] == $journal_record_types[0]){
		$content[$key]['patient'] = '&#8212;';
		$content[$key]['disease'] = '&#8212;';
		$content[$key]['doctor'] = '&#8212;';
	}
}

?>


<div class="col-xs-12">
	<div class="panel-group">
		<div class="panel panel-default">
			<div class="panel-heading" data-toggle="collapse" href="#conditions" style="cursor: pointer;">
				<h4 class="panel-title">
					<span>Поиск <span class="caret"></span></span>
				</h4>
			</div>
			<div id="conditions" class="panel-collapse collapse">
				<div class="panel-body">
					<div class="col-xs-offset-1 col-xs-10">
						<form class="form-horizontal">
							<?php 
							draw_fields('', array('quantity'=>'Количество'));
							draw_fields('Препарат', array('drug_name'=>'Название'));
							draw_fields('Поставщик', array('provider_name'=>'Название')); 
							draw_fields('Пациент', array('patient_name'=>'Имя', 'patient_surname'=>'Фамилия', 'patient_patronymic'=>'Отчество'));
							draw_fields('Болезнь', array('disease_name'=>'Название'));
							draw_fields('Доктор', array('doctor_name'=>'Имя', 'doctor_surname'=>'Фамилия', 'doctor_patronymic'=>'Отчество'));
							?>
							<div class="jelly-button green form-button" onclick="this.parentNode.submit()">Поиск</div>
						</form>
					</div>
					<div class="col-xs-1"></div>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="col-xs-12 col-md-offset-4 col-md-4"">
	<div class="alert alert-warning alert-count-rows">Найдено записей: <?php echo "$rows_count"; ?> </div>
</div>

<div class="col-xs-6">
	<div class="dropdown">
		<div class="jelly-button limit-button dropdown-toggle" type="button" data-toggle="dropdown">
			Строк на странице <span class="caret"></span>
		</div>
		<ul class="dropdown-menu">
			<li><a onclick="rowsPerPage(50)">50</a></li>
			<li><a onclick="rowsPerPage(100)">100</a></li>
			<li><a onclick="rowsPerPage(200)">200</a></li>
		</ul>
	</div>
</div>
<div class="col-xs-6">
	<div style="float: right;" id="add-patient" class="jelly-button" data-toggle="modal" data-target="#add">Добавить</div>
</div>


<div class="col-xs-12">
	<?php draw_table(array('Дата', 'Количество', 'Препарат', 'Поставщик', 'Пациент', 'Болезнь', 'Доктор'), $content, false, true); ?>
</div>

<?php draw_pagination($page+1, $linksRange); ?>

	<div class="modal fade" id="add" role="dialog">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">Добавить запись журнала</h4>
				</div>
				<div class="modal-body">
					<?php 
					if(isset($error))
						echo '<div class="alert alert-warning">'.$error.'</div>'
					?>
					<form class="form-horizontal" method="POST">
						<input type="hidden" value="$">
						<div class="form-group">
							<label for="is_intake" class="col-sm-2 control-label">Поступление препарата?</label>
							<div class="col-sm-1">
								<input type="checkbox" class="form-control" id="is_intake" name="is_intake" required>
							</div>
						</div>
						<div class="form-group">
							<label for="quantity" class="col-sm-2 control-label">Количество</label>
							<div class="col-sm-10">
								<input type="text" class="form-control" id="quantity" name="quantity" required>
							</div>
						</div>
						<div class="form-group">
							<label for="drug_id" class="col-sm-2 control-label">Препарат</label>
							<div class="col-sm-10">
								<input 
									value='{$content[0]['drug_id']}'
									type="text" 
									class="form-control flexdatalist" 
									id="drug_id" 
									name='drug_id'
									data-data='/json?get=drugs'
							        data-search-in='name'
							        data-search-by-word='true'
							        data-text-property='name'
							        data-visible-properties='["name"]'
							        data-selection-required='true'
							        data-value-property='id'
							        data-cache-lifetime='10'
							        data-allow-duplicate-values='true'
							        data-no-results-text='Ничего не найдено'
							        data-min-length='0'
							       >
							</div>
						</div>
						<div id="patient_id_container" class="form-group">
							<label for="name" class="col-sm-2 control-label">Пациент</label>
							<div class="col-sm-10">
								<input 
									value='{$content[0]['patient_id']}'
									type="text" 
									class="form-control flexdatalist" 
									id="patient_id" 
									name='patient_id'
									data-data='/json?get=patients'
							        data-search-in='name'
							        data-search-by-word='true'
							        data-text-property='surname'
							        data-visible-properties='["surname"]'
							        data-selection-required='true'
							        data-value-property='id'
							        data-cache-lifetime='10'
							        data-allow-duplicate-values='true'
							        data-no-results-text='Ничего не найдено'
							        data-min-length='0'
							       >
							</div>
						</div>
						<div id="doctor_id_container" class="form-group">
							<label for="name" class="col-sm-2 control-label">Доктор</label>
							<div class="col-sm-10">
								<input 
									value='{$content[0]['doctor_id']}'
									type="text" 
									class="form-control flexdatalist" 
									id="doctor_id" 
									name='doctor_id'
									data-data='/json?get=doctors'
							        data-search-in='name'
							        data-search-by-word='true'
							        data-text-property='surname'
							        data-visible-properties='["surname"]'
							        data-selection-required='true'
							        data-value-property='id'
							        data-cache-lifetime='10'
							        data-allow-duplicate-values='true'
							        data-no-results-text='Ничего не найдено'
							        data-min-length='0'
							       >
							</div>
						</div>
						<div class="jelly-button green" onclick="formControl(this.parentNode)">Готово</div>
					</form>
				</div>
			</div>
		</div>
	</div>
	<?php 
	if(isset($error))
		echo '<script>$("#add").modal()</script>';
	?>

<script>
$(document).ready(function() {
	$('#is_intake').change(function() {
        if(this.checked) {
            $('#patient_id_container').hide(500)
			$('#doctor_id_container').hide(500)
        }
		else{
			$('#patient_id_container').show(500)
			$('#doctor_id_container').show(500)
		}
    });
});
</script>

</div>
</div>
</div>
</body>
</html>