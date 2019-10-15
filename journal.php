<?php 
$active_item = 'journal'; 
require 'static/templates/header.html'; 
require 'static/templates/content.php';
require 'static/scripts/helpers.php';  

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	insert_row('journal', $journalAllowed, '/journal', $error);
}
else {
	$search_clause = "";
	$values = array();
	$rangeStr = get_limit_range($rows_per_page, $page);

	if(isset($_GET['date']))
		$search_clause = settle_search(array('date'=>'journal.date',
											'quantity'=>'journal.quantity',
											'd_name'=>'drugs.name',
											'd_provider'=>'providers.name', 	
											'pr_name'=>'providers.name',
											'p_name'=>'drugs.name',
											'p_surname'=>'drugs.name',
											'p_patronymic'=>'drugs.name',
											'p_disease_id'=>'drugs.name',
											'p_doctor_id'=>'patients.name', 
											), 
										$values);

$query = "SELECT SQL_CALC_FOUND_ROWS
			journal.date,
			journal.quantity,
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
			RIGHT JOIN diseases ON patients.disease_id=diseases.id
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
							draw_fields('Препарат', array('t_name'=>'Назва'));
							draw_fields('Постачальник', array('d_name'=>'Назва')); 
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
			Рядків на сторінці <span class="caret"></span>
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
	<?php draw_table(array('Дата', 'Количество', 'Препарат', 'Поставщик', 'Пациент', 'Болезнь', 'Доктор'), $content); ?>
</div>

<?php draw_pagination($page+1, $linksRange); ?>

	<div class="modal fade" id="add" role="dialog">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">Добавити препарат</h4>
				</div>
				<div class="modal-body">
					<?php 
					if(isset($error))
						echo '<div class="alert alert-warning">'.$error.'</div>'
					?>
					<form class="form-horizontal" method="POST">
						<input type="hidden" value="$">
						<div class="form-group">
							<label for="name" class="col-sm-2 control-label">Назва</label>
							<div class="col-sm-10">
								<input type="text" class="form-control" id="name" name="name" required>
							</div>
						</div>
						<div class="form-group">
							<label for="disease_id" class="col-sm-2 control-label">Постачальник</label>
							<div class="col-sm-10">
								<input 
									value='{$content[0]['provider_id']}'
									type="text" 
									class="form-control flexdatalist" 
									id="provider_id" 
									name='provider_id'
									data-data='/json?get=providers'
							        data-search-in='name'
							        data-search-by-word='true'
							        data-text-property='name'
							        data-visible-properties='["name"]'
							        data-selection-required='true'
							        data-value-property='id'
							        data-cache-lifetime='10'
							        data-allow-duplicate-values='true'
							        data-no-results-text='Нічого не знайдено'
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
</div>
</div>
</div>
</body>
</html>