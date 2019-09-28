<?php 
$active_item = 'patients'; 
require 'static/templates/header.html'; 
require 'static/templates/content.php';
require 'static/scripts/helpers.php';  

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	insert_row('patients', $patientAllowed, '/patients', $error);
}
else {
	$search_clause = "";
	$values = array();
	$rangeStr = get_limit_range($rows_per_page, $page);

	if(isset($_GET['p_name']))
		$search_clause = settle_search(array('p_name'=>'patients.name', 
											  'p_surname'=>'patients.surname',
											  'p_patronymic'=>'patients.patronymic', 
											  'p_disease'=>'diseases.name',
											  'd_name'=>'doctors.name',
											  'd_surname'=>'doctors.surname',
											  'd_patronymic'=>'doctors.patronymic',
											  'd_mobile_number'=>'doctors.mobile_number'), $values);
	$query = "SELECT SQL_CALC_FOUND_ROWS 
					 patients.id, 
					patients.name, 
					patients.surname,
					patients.patronymic,
					patients.disease_id as disease_id,
					diseases.name as disease_name,
					patients.doctor_id as doctor_id,
					doctors.surname as doctor_surname
					FROM patients 
					JOIN diseases ON patients.disease_id=diseases.id
					JOIN doctors ON patients.doctor_id=doctors.id
					WHERE patients.is_active=true $search_clause
					ORDER BY id DESC
				    LIMIT $rangeStr";
	}
	
$content = select_rows($query, $rows_count, $values);

$linksRange = get_links_range($rows_per_page, $rows_count, $page);

foreach ($content as $key => $obj) {
	$content[$key]['disease'] = array("link" => "/disease?id=".$content[$key]['disease_id'], 'value' => $content[$key]['disease_name']);
	$content[$key]['doctor'] = array("link" => "/doctor?id=".$content[$key]['doctor_id'], 'value' => $content[$key]['doctor_surname']);
	unsetValues($content[$key], array('disease_name', 'doctor_surname', 'disease_id', 'doctor_id'));
}

?>

<div class="col-xs-12">
	<div class="panel-group">
		<div class="panel panel-default">
			<div class="panel-heading" data-toggle="collapse" href="#conditions" style="cursor: pointer;">
				<h4 class="panel-title">
					<span>Пошук <span class="caret"></span></span>
				</h4>
			</div>
			<div id="conditions" class="panel-collapse collapse">
				<div class="panel-body">
					<div class="col-xs-offset-1 col-xs-10">
						<form class="form-horizontal">
							<?php 
							draw_fields('Хворий', array('p_name'=>'Ім\'я','p_surname'=>'Прізвище','p_patronymic'=>'По батькові','p_disease'=>'Хвороба'));
							draw_fields('Лікар', array('d_name'=>'Ім\'я','d_surname'=>'Прізвище','d_patronymic'=>'По батькові','d_mobile_number'=>'Номер телефону')); 
							?>
							<div class="jelly-button green form-button" onclick="this.parentNode.submit()">Пошук</div>
						</form>
					</div>
					<div class="col-xs-1"></div>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="col-xs-12 col-md-offset-4 col-md-4"">
	<div class="alert alert-warning alert-count-rows">Знайдено записiв: <?php echo "$rows_count"; ?> </div>
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
	<div style="float: right;" id="add-patient" class="jelly-button" data-toggle="modal" data-target="#add">Добавити</div>
</div>

<div class="col-xs-12">
	<?php draw_table(array('ID', 'Ім\'я', 'Прізвище', 'По батькові', 'Хвороба', 'Лікар'), $content, '/patient?id='); ?>
</div>	

	<?php draw_pagination($page+1, $linksRange); ?>

	<div class="modal fade" id="add" role="dialog">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">Добавити пацієнта</h4>
				</div>
				<div class="modal-body">
					<?php 
					if(isset($error))
						echo '<div class="alert alert-warning">'.$error.'</div>'
					?>
					<form class="form-horizontal" method="POST">
						<input type="hidden" value="$">
						<div class="form-group">
							<label for="name" class="col-sm-2 control-label">Ім'я</label>
							<div class="col-sm-10">
								<input type="text" class="form-control" id="name" name="name" required>
							</div>
						</div>
						<div class="form-group">
							<label for="surname" class="col-sm-2 control-label">Прізвище</label>
							<div class="col-sm-10">
								<input type="text" class="form-control" id="surname" name="surname" required>
							</div>
						</div>
						<div class="form-group">
							<label for="patronymic" class="col-sm-2 control-label">По батькові</label>
							<div class="col-sm-10">
								<input type="text" class="form-control" id="patronymic" name="patronymic" required>
							</div>
						</div>
						<div class="form-group">
							<label for="disease" class="col-sm-2 control-label">Хвороба <span class="caret"></span></label>
							<div class="col-sm-10">
								<input 
								type="text" 
								class="form-control flexdatalist" 
								id="disease" 
								name="disease_id"
								data-url="/json?get=diseases"
								data-value-property="id"
								data-min-length="0"
								data-selection-required='true'
								data-search-in="name" 
								required>
							</div>
						</div>
						<div class="form-group">
							<label for="doctor" class="col-sm-2 control-label">Лікар <span class="caret"></span></label>
							<div class="col-sm-10">
								<input 
								type="text" 
								class="form-control flexdatalist" 
								id="doctor" 
								name="doctor_id"
								data-url="/json?get=doctors"
								data-value-property="id"
								data-min-length="0"
								data-selection-required='true'
								data-search-in='["surname", "name", "patronymic"]'
								data-text-property='{surname} {name} {patronymic}' 
								required>
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