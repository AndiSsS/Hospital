<?php 
$active_item = 'drugs'; 
require 'static/templates/header.html'; 

if(!is_numeric($_GET['id']) || !is_row_exists('drugs', $_GET['id']))
	header('Location: /404');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	if(isset($_POST['delete']))
		eliminate_row('drugs', $_GET['id'], '/drugs');
	else 
		update_row('drugs', $_GET['id'], $drugAllowed, '/drug?id='.$_GET["id"], $error);
}

$content = select_rows('SELECT name, 
						provider_id
						FROM drugs 
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
				<div class="panel-heading panel-white-blue">Редактирование препарата</div>
				<div class="panel-body">
					<form class="form-horizontal" method="POST">
						<div class="form-group">
							<label for="name" class="col-sm-2 control-label">Название</label>
							<div class="col-sm-10">
								<input type="text" class="form-control" id="name" name="name" required value="{$content[0]['name']}">
							</div>
						</div>
						<div class="form-group">
							<label for="disease_id" class="col-sm-2 control-label">Поставщик</label>
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