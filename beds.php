<?php 
$active_item = 'beds'; 
require 'static/templates/header.html'; 
require 'static/templates/content.php';
require 'static/scripts/helpers.php';  

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	insert_row('beds', $bedAllowed, '/beds', $error);
}
else {
	$search_clause = "";
	$values = array();
	$rangeStr = get_limit_range($rows_per_page, $page);

	if(isset($_GET['bed_number']))
		$search_clause = settle_search(array('bed_number'=>'beds.number',
											 'chamber_number'=>'chambers.number'), 
										$values);

$query = "SELECT SQL_CALC_FOUND_ROWS
	 		beds.id, 
	 		beds.number,
			chambers.id as chamber_id,
			chambers.number as chamber_number
			FROM beds 
			JOIN chambers ON beds.chamber_id = chambers.id
			WHERE beds.is_active=true $search_clause
			ORDER BY beds.id DESC
		    LIMIT $rangeStr";
	}

$content = select_rows($query, $rows_count, $values);

$linksRange = get_links_range($rows_per_page, $rows_count, $page);

foreach ($content as $key => $obj) {
	$content[$key]['chamber'] = array("link" => "/chamber?id=".$content[$key]['chamber_id'], 'value' => $content[$key]['chamber_number']);
	unsetValues($content[$key], array('chamber_number', 'chamber_id'));
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
							draw_fields('Кровать', array('bed_number'=>'Инвентарный номер'));
							draw_fields('Палата', array('chamber_number'=>'Номер помещения'));
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
	<?php draw_table(array('ID', 'Инвентарный номер', 'Палата'), $content, '/bed?id='); ?>
</div>

<?php draw_pagination($page+1, $linksRange); ?>

	<div class="modal fade" id="add" role="dialog">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">Добавить кровать</h4>
				</div>
				<div class="modal-body">
					<?php 
					if(isset($error))
						echo '<div class="alert alert-warning">'.$error.'</div>'
					?>
					<form class="form-horizontal" method="POST">
						<input type="hidden" value="$">
						<div class="form-group">
							<label for="number" class="col-sm-2 control-label">Инвентарный номер</label>
							<div class="col-sm-10">
								<input type="text" class="form-control" id="number" number="number" required>
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
									number='chamber_id'
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