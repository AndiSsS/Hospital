<?php 
$active_item = 'doctors'; 
require 'static/templates/header.html'; 
require 'static/templates/content.php';
require 'static/scripts/helpers.php';  

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	insert_row('doctors', $drugsAllowed, '/doctors', $error);
}
else {
	$search_clause = "";
	$values = array();
	$rangeStr = get_limit_range($rows_per_page, $page);

	if(isset($_GET['d_name']))
		$search_clause = settle_search(array('d_name'=>'doctors.name', 
											  'd_surname'=>'doctors.surname', 
											  'd_patronymic'=>'doctors.patronymic', 
											  'd_mobile_number'=>'doctors.mobile_number'), 
										$values);

$query = "SELECT SQL_CALC_FOUND_ROWS
			id,
			name, 
			surname,
			patronymic,
			mobile_number
			FROM doctors 
			WHERE is_active=true $search_clause
			ORDER BY id DESC
		    LIMIT $rangeStr";
	}

$content = select_rows($query, $rows_count, $values);

$linksRange = get_links_range($rows_per_page, $rows_count, $page);

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
							draw_fields(false, array('d_name'=>'Ім\'я','d_surname'=>'Прізвище','d_patronymic'=>'По батькові','d_mobile_number'=>'Номер телефону'));
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
	<?php draw_table(array('ID', 'Ім\'я', 'Прізвище', 'По батькові', 'Номер телефону'), $content, '/doctor?id='); ?>
</div>

<?php draw_pagination($page+1, $linksRange); ?>

	<div class="modal fade" id="add" role="dialog">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">Добавити лікаря</h4>
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
							<label for="name" class="col-sm-2 control-label">Прізвище</label>
							<div class="col-sm-10">
								<input type="text" class="form-control" id="surname" name="surname" required>
							</div>
						</div>
						<div class="form-group">
							<label for="name" class="col-sm-2 control-label">По батькові</label>
							<div class="col-sm-10">
								<input type="text" class="form-control" id="patronymic" name="patronymic" required>
							</div>
						</div>
						<div class="form-group">
							<label for="name" class="col-sm-2 control-label">Номер телефону</label>
							<div class="col-sm-10">
								<input type="text" class="form-control" id="mobile_number" name="mobile_number" required>
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