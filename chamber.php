<?php 
$active_item = 'chambers'; 
require 'static/templates/header.html'; 

if(!is_numeric($_GET['id']) || !is_row_exists('chambers', $_GET['id']))
	header('Location: /404');

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	if(isset($_POST['delete']))
	{
		$query = 'SELECT COUNT(*) as count
				FROM `chambers` 
				JOIN patients ON patients.chamber_id = chambers.id
				JOIN beds ON beds.chamber_id = chambers.id
				WHERE chambers.id = '.$_GET['id'];

		$content = select_rows($query, $rows_count);
		if($content[0]['count'] > 0){
			global $error;
			$error = "Есть привязанные пациенты или кровати к этой палате";
		} 
		if(!$error)
			eliminate_row('chambers', $_GET['id'], '/chambers');	
	}
	else 
		update_row('chambers', $_GET['id'], $chamberAllowed, '/chamber?id='.$_GET["id"], $error);
}

$content = select_rows('SELECT number
						FROM chambers 
						WHERE id = ?', $rows_count, array($_GET['id']));

if($error)
	$error_html = '<div class="alert alert-danger">'.$error.'</div>';
else
	$error_html = '';

echo <<<EOT
<div class="container">
	<div class="col-xs-12">
		{$error_html}
		<br><br>
		<div class="panel-group">
			<div class="panel panel-primary">
				<div class="panel-heading panel-white-blue">Редактирование палаты</div>
				<div class="panel-body">
					<form class="form-horizontal" method="POST">
						<div class="form-group">
							<label for="number" class="col-sm-2 control-label">Номер помещения</label>
							<div class="col-sm-10">
								<input type="text" class="form-control" id="number" name="number" required value="{$content[0]['number']}">
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