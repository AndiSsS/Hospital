<?php 
$active_item = 'patients'; 
require 'static/templates/header.html'; 

if(!is_numeric($_GET['id']) || !is_row_exists('patients', $_GET['id']))
	header('Location: /404');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	if(isset($_POST['delete']))
		eliminate_row('patients', $_GET['id'], '/patients');
	else 
		update_row('patients', $_GET['id'], $patientAllowed, '/patient?id='.$_GET["id"], $error);
}

$content = select_rows('SELECT name, surname, patronymic, disease_id, doctor_id FROM patients WHERE id = ?', $rows_count, array($_GET['id']));

if(isset($error))
	$error_html = '<div class="alert alert-warning">'.$error.'</div>';
else
	$error_html = '';

echo <<<EOT
<div class="container">
	<div class="col-xs-12">
		{$error_html}
		<br><br>
		<div class="panel-group">
			<div class="panel panel-primary">
				<div class="panel-heading panel-white-blue">Редагування хворого</div>
				<div class="panel-body">
					<form class="form-horizontal" method="POST">
						<div class="form-group">
							<label for="name" class="col-sm-2 control-label">Ім'я</label>
							<div class="col-sm-10">
								<input type="text" class="form-control" id="name" name="name" required value="{$content[0]['name']}">
							</div>
						</div>
						<div class="form-group">
							<label for="surname" class="col-sm-2 control-label">Прізвище</label>
							<div class="col-sm-10">
								<input type="text" class="form-control" id="surname" name="surname" required value="{$content[0]['surname']}">
							</div>
						</div>
						<div class="form-group">
							<label for="patronymic" class="col-sm-2 control-label">По батькові</label>
							<div class="col-sm-10">
								<input type="text" class="form-control" id="patronymic" name="patronymic" required value="{$content[0]['patronymic']}">
							</div>
						</div>
						<div class="form-group">
							<label for="disease_id" class="col-sm-2 control-label">Хвороба</label>
							<div class="col-sm-10">
								<input 
									value='{$content[0]['disease_id']}'
									type="text" 
									class="form-control flexdatalist" 
									id="disease_id" 
									name='disease_id'
									data-data='/json?get=diseases'
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
						<div class="form-group">
							<label for="doctor_id" class="col-sm-2 control-label">Лікар</label>
							<div class="col-sm-10">
								<input 
									type="text" 
									value="{$content[0]['doctor_id']}"
									class="form-control flexdatalist" 
									id="doctor_id" 
									name="doctor_id"
									data-url="/json?get=doctors"
								    data-min-length="0"
								    data-selection-required='true'
								    data-search-in='["surname", "name", "patronymic"]'
								    data-text-property='{surname} {name} {patronymic}' 
								    data-no-results-text='Нічого не знайдено'
								    data-allow-duplicate-values='true'
								    data-value-property='id'
								    data-cache-lifetime='10'>
							</div>
						</div>
						<div class="col-xs-11">
							<div class="jelly-button green right-30" onclick="formControl(this.parentNode.parentNode)">Готово</div>
						</div>
					</form>
					<form method="POST">
						<div class="col-xs-1">
							<input type="hidden" name="delete">
							<div class="jelly-button delete-button" onclick="formControl(this.parentNode.parentNode, true)"><i class="fa fa-trash-o" aria-hidden="true"></i></div>
						</div>
					</form>
				</div>
			</div>
		</div>	
	</div>
</div>
</div>
</div>
</div>
</body>
</html>
EOT
?>