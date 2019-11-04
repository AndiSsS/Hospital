<?php 
$active_item = 'beds'; 
require 'static/templates/header.html'; 

if(!is_numeric($_GET['id']) || !is_row_exists('beds', $_GET['id']))
	header('Location: /404');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	if(isset($_POST['delete']))
		eliminate_row('beds', $_GET['id'], '/beds');
	else 
		update_row('beds', $_GET['id'], $bedAllowed, '/bed?id='.$_GET["id"], $error);
}

$content = select_rows('SELECT number, chamber_id
						FROM beds 
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
				<div class="panel-heading panel-white-blue">Редактирование кровати</div>
				<div class="panel-body">
					<form class="form-horizontal" method="POST">
						<div class="form-group">
							<label for="number" class="col-sm-2 control-label">Название</label>
							<div class="col-sm-10">
								<input type="text" class="form-control" id="number" name="number" required value="{$content[0]['number']}">
							</div>
						</div>
						<div class="form-group">
							<label for="chamber_id" class="col-sm-2 control-label">Палата</label>
							<div class="col-sm-10">
								<input 
									value='{$content[0]['chamber_id']}'
									type="text" 
									class="form-control flexdatalist" 
									id="chamber_id" 
									name='chamber_id'
									data-data='/json?get=chambers'
							        data-search-in='number'
							        data-search-by-word='true'
							        data-text-property='number'
							        data-visible-properties='["number"]'
							        data-selection-required='true'
							        data-value-property='id'
							        data-cache-lifetime='10'
							        data-allow-duplicate-values='true'
							        data-no-results-text='Ничего не найдено'
							        data-min-length='0'
							       >
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