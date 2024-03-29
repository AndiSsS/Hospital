<?php 
$active_item = 'doctors'; 
require 'static/templates/header.html'; 

if(!is_numeric($_GET['id']) || !is_row_exists('doctors', $_GET['id']))
	header('Location: /404');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	if(isset($_POST['delete']))
		eliminate_row('doctors', $_GET['id'], '/doctors');
	else 
		update_row('doctors', $_GET['id'], $doctorAllowed, '/doctor?id='.$_GET["id"], $error);
}

$content = select_rows('SELECT name, 
						surname,
						patronymic,
						mobile_number
						FROM doctors 
						WHERE id = ?', $rows_count, array($_GET['id']));

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
				<div class="panel-heading panel-white-blue">Редагування лікаря</div>
				<div class="panel-body">
					<form class="form-horizontal" method="POST">
						<div class="form-group">
							<label for="name" class="col-sm-2 control-label">Ім'я</label>
							<div class="col-sm-10">
								<input type="text" class="form-control" id="name" name="name" required value="{$content[0]['name']}">
							</div>
						</div>
						<div class="form-group">
							<label for="name" class="col-sm-2 control-label">Прізвище</label>
							<div class="col-sm-10">
								<input type="text" class="form-control" id="surname" name="surname" required value="{$content[0]['surname']}">
							</div>
						</div>
						<div class="form-group">
							<label for="name" class="col-sm-2 control-label">По батькові</label>
							<div class="col-sm-10">
								<input type="text" class="form-control" id="patronymic" name="patronymic" required value="{$content[0]['patronymic']}">
							</div>
						</div>
						<div class="form-group">
							<label for="name" class="col-sm-2 control-label">Номер телефону</label>
							<div class="col-sm-10">
								<input type="text" class="form-control" id="mobile_number" name="mobile_number" required value="{$content[0]['mobile_number']}">
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