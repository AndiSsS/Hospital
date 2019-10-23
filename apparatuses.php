<?php 
$active_item = 'apparatuses'; 
require 'static/templates/header.html'; 
require 'static/templates/content.php';
require 'static/scripts/helpers.php';  

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	insert_row('apparatuses', $apparatusAllowed, '/apparatuses', $error);
}
else {
	$search_clause = "";
	$values = array();
	$rangeStr = get_limit_range($rows_per_page, $page);

	if(isset($_GET['apparatus_name']))
		$search_clause = settle_search(array('apparatus_name'=>'apparatuses.name',
											 'doctor_surname'=>'doctors.surname'), 
										$values);

$query = "SELECT SQL_CALC_FOUND_ROWS
	 		apparatuses.id, 
	 		apparatuses.name,
			doctors.id as doctor_id,
			doctors.surname as doctor_surname
			FROM apparatuses 
			JOIN doctors ON apparatuses.doctor_id = doctors.id
			WHERE apparatuses.is_active=true $search_clause
			ORDER BY apparatuses.id DESC
		    LIMIT $rangeStr";
	}

$content = select_rows($query, $rows_count, $values);

$linksRange = get_links_range($rows_per_page, $rows_count, $page);

foreach ($content as $key => $obj) {
	$content[$key]['doctor'] = array("link" => "/doctor?id=".$content[$key]['doctor_id'], 'value' => $content[$key]['doctor_surname']);
	unsetValues($content[$key], array('doctor_surname', 'doctor_id'));
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
							draw_fields('Аппарат', array('apparatus_name'=>'Название'));
							draw_fields('Доктор', array('doctor_surname'=>'Фамилия'));
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
	<?php draw_table(array('ID', 'Название', 'Доктор'), $content, '/apparatus?id='); ?>
</div>

<?php draw_pagination($page+1, $linksRange); ?>

	<div class="modal fade" id="add" role="dialog">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">Добавить аппарат</h4>
				</div>
				<div class="modal-body">
					<?php 
					if(isset($error))
						echo '<div class="alert alert-warning">'.$error.'</div>'
					?>
					<form class="form-horizontal" method="POST">
						<input type="hidden" value="$">
						<div class="form-group">
							<label for="name" class="col-sm-2 control-label">Название</label>
							<div class="col-sm-10">
								<input type="text" class="form-control" id="name" name="name" required>
							</div>
						</div>
						<div class="form-group">
							<label for="doctor_id" class="col-sm-2 control-label">Доктор</label>
							<div class="col-sm-10">
								<input 
									value='{$content[0]['doctor_id']}'
									type="text" 
									class="form-control flexdatalist" 
									id="doctor_id" 
									name='doctor_id'
									data-data='/json?get=doctors'
							        data-search-in='surname'
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
</div>
</div>
</div>
</body>
</html>