<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<link rel="stylesheet" type="text/css" href="<?=base_url('public/plugins/datatables-buttons/css/buttons.bootstrap4.min.css')?>">
<script type="text/javascript" src="<?=base_url('public/plugins/datatables-buttons/js/dataTables.buttons.min.js')?>"></script>

<script type="text/javascript" src="<?=base_url('public/plugins/pdfmake/pdfmake.min.js')?>"></script>
<script type="text/javascript" src="<?=base_url('public/plugins/pdfmake/vfs_fonts.js')?>"></script>
<script type="text/javascript" src="<?=base_url('public/plugins/datatables-buttons/js/buttons.html5.min.js')?>"></script>
<script type="text/javascript" src="<?=base_url('public/plugins/datatables-buttons/js/buttons.print.min.js')?>"></script>






<!-- Content Wrapper. Contains page content -->
<div class="content">
<!-- Content Header (Page header) -->
<div class="content-header">
	<div class="container-fluid">
		<div class="row">
			<div class="col-sm-6">
				<h1 class="m-0"><?=$module->name?></h1>
			</div><!-- /.col -->
			

			<div class="col-sm-6">
				<ol class="breadcrumb float-sm-right">
					<li class="breadcrumb-item"><a href="<?=base_url($module->menuLink)?>"><?=$module->menuName?></a></li>
					<li class="breadcrumb-item active"><?=$module->name?></li>
				</ol>
			</div><!-- /.col -->
		</div><!-- /.row -->
	</div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->
</div>

<!-- Main content -->
<section class="content">

	<div class="container-fluid">

		<div class="row bg-secondary">
			
			<div class="col-sm-12">
				
				<div class="btn-group btn-group-lg" role="group" aria-label="Basic example">
					<?php if (array_search('buscar',$permissions['actions'])): ?>
					<button id="btn-find" type="button" class="btn btn-secondary btn-action" value="buscar">
						<div class="icon">
							<i class="ion ion-ios-search-strong"></i>
						</div>
						<small>Buscar</small>
					</button>	
					<?php endif ?>
				</div>

			</div>

		</div>



		<hr class="divider">

		<div class="row">
			
			<div class="col-sm-4 input-group">
				<div class="input-group-prepend">
					<label class="input-group-text" for="startDateGeneral">Fecha Inicial</label>
				</div>
				<input 
					type="text" 
					class="form-control datetimepicker-input" 
					id="starDateStock" 
					data-toggle="datetimepicker" 
					data-target="#changeDateDashboard" 
					value="<?=$startDate?>"
					<?= array_search('selectDate',$permissions['actions']) ? '' : 'disabled' ?>
				/>
			</div>

			<div class="col-sm-4 input-group">
				<div class="input-group-prepend">
					<label class="input-group-text" for="finishDateGeneral">Fecha final</label>
				</div>
				<input 
					type="text" 
					class="form-control datetimepicker-input" 
					id="finishDateStock" 
					data-toggle="datetimepicker" 
					data-target="#changeDateDashboard" 
					value="<?=$finishDate?>"
					<?= array_search('selectDate',$permissions['actions']) ? '' : 'disabled' ?>
				/>
			</div>

			<div class="col-sm-4"></div>



		</div>

		<hr class="divider">		
								
		<div class="row">

			

			<div class="col-sm-3">
				<div class="form-check">
					<input 
					class="form-check-input" 
					type="checkbox" 
					id="checkCatStock" 
					value="option1" 
					checked
					<?= array_search('selectCategory',$permissions['actions']) ? '' : 'disabled' ?>
					/>
					<label class="form-check-label" for="checkCatStock">Todas las categorías</label>
				</div>
				
			</div>
			<div class="col-sm-1 border-right"></div>
			
			<div class="col-sm-8">
				<div class="form-check">
					<select id="mselectCatStock" multiple="multiple" class="form-control form-control-sm" disabled>
						<?php foreach ($categorys['dep_id_group'] as $dep): ?>

							<optgroup label="<?=$dep['nombre']?>">

								<?php foreach ($dep['cat_id_group'] as $cat): ?>
									
								<option value="<?=$cat['cat_id']?>" selected><?=$cat['nombre']?></option>

								<?php endforeach ?>

							</optgroup>
							
						<?php endforeach ?>		
					</select>	
				</div>	
			</div>
		</div>

		<div class="row">
			<div class="col-sm-3">
				<div class="form-check">
					<input 
					class="form-check-input" 
					type="checkbox" 
					id="checkStoreStock" 
					value="option1" 
					checked
					<?= array_search('selectStore',$permissions['actions']) ? '' : 'disabled' ?>
					/>
					<label class="form-check-label" for="checkStoreStock">Todas las sucursales</label>
				</div>
				
			</div>
			<div class="col-sm-1 border-right"></div>
			
			<div class="col-sm-8">
				<div class="form-check">
					<select id="mselectStoreStock" multiple="multiple" class="form-control form-control-sm" disabled>
						<?php foreach ($stores as $store): ?>
						<option value="<?=$store->store_id?>" selected><?=$store->store_name?></option>	
						<?php endforeach ?>		
					</select>	
				</div>	
			</div>
		</div>

		<hr class="divider">

		<hr class="divider">




	</div>

</section>

<!-- SECTION TABLA -->
<section class="content">
	<div class="container-fluid">
		<div class="row">
			
			<div class="col-12 table-responsive">
				
				<table class="table m-0" id="tbl-stockStore">
					<thead>
						<tr>
							<th>SUCURSAL</th>
							<th>FECHA</th>
							<th>CATEGORIA</th>
							<th>ARTICULO</th>
							<th>CLAVE</th>
							<th class="text-right">CANTIDAD</th>
							<th>UNIDAD</th>
						</tr>
					</thead>
					<tbody></tbody>
					<tfoot></tfoot>
				</table>
				<!-- /. tbl-stockStore -->
			</div>
			<!-- /.div col-12 table-responsive -->

		</div>
	</div>	
</section>


<script type="text/javascript">
jQuery( function($){
jQuery(window).on('load',function () {
$(document).ready(function () {

/***********************************************/
// VALUES
/***********************************************/
	
	// ID boton buscar
	var find_btn					= '#btn-find';

	// ID de catStock
	var cats_select 				= '#mselectCatStock';

	// ID de storeStock
	var stores_select				= '#mselectStoreStock';

	// ID de tabla de inventarios por sucursal
	var stockStore_tbl 				= '#tbl-stockStore';

	var starDate_input				= '#starDateStock';
	
	var finishDate_input			= '#finishDateStock';

	var formatterNumber = new Intl.NumberFormat('es-MX');

	
	
/***********************************************/
// OPTIONS
/***********************************************/

	// Fecha inicial general
	$('#starDateStock').datetimepicker({
		format: 'YYYY-MM-DD',
		locale: 'es'
	});

	// Fecha final general
	$('#finishDateStock').datetimepicker({
		format: 'YYYY-MM-DD',
		locale: 'es'
	});

	// Selección de categorías Stock
	$(cats_select).multipleSelect({
		locale : 'es-MX',
		sellectAll : true,
		multipleWidth : 200,
		styler: function (row) {
			if (row.type === 'optgroup') {
				return 'color: #ffffff; font-weight: normal; background-color: #6c757d;'
			}
		},
		filter : true
	});

	// Selección de tiendas Stock
	$(stores_select).multipleSelect({
		locale : 'es-MX',
		sellectAll : true
	});

	// Objeto datatable || INVENTARIOS POR CATEGORIA
	var tbl_stockStore		= $(stockStore_tbl).DataTable({
		language : { url : '//cdn.datatables.net/plug-ins/1.11.3/i18n/es_es.json'},
		columns: [
			{ data: 'sucursal' },
			{ data: 'fecha' },
			{ data: 'categoria'},
			{ data: 'articulo'},
			{ data: 'clave'},
			{ data: 'cantidad', render: function (data, type, row, meta) {
				return formatterNumber.format(data); }, className: "text-right", type: "num-fmt"
			},
			{ data: 'unidad'}
		],
		order: [[ 5, "desc" ]],
		dom: 'Bfrtip',
		buttons: buttonsStockStore(),
		info : false,
		ordering : true,
		searching: true,
		paging: true,
		pageLength: 50,
		processing : true

	});


/***********************************************/
// EVENTS
/***********************************************/

	$(find_btn).on('click', function(){

		getStock(infoForm());
	});

	// Click al check de categorias Stock
	$( '#checkCatStock' ).on( 'click', function() {
		if( $(this).is(':checked') ){
			// Hacer algo si el checkbox ha sido seleccionado
			$(cats_select).multipleSelect('disable');
			$(cats_select).multipleSelect('checkAll');
		} else {
			// Hacer algo si el checkbox ha sido deseleccionado
			$(cats_select).multipleSelect('enable');
		}
	});

	// Click al check de tiendas Stock
	$( '#checkStoreStock' ).on( 'click', function() {
		if( $(this).is(':checked') ){
			// Hacer algo si el checkbox ha sido seleccionado
			$(stores_select).multipleSelect('disable');
			$(stores_select).multipleSelect('checkAll');
		} else {
			// Hacer algo si el checkbox ha sido deseleccionado
			$(stores_select).multipleSelect('enable');
		}
	});

//*********************************************************************************************//
// FUNCIONES LOCALEs
//*********************************************************************************************//
	/**
	 * 
	 * 
	 * @return Objetos de datos de envío
	 * */
	function infoForm(){

		info = {
			start_date 		: $(starDate_input).val(),
			finish_dae 		: $(finishDate_input).val(),
			suc_ids 		: $(stores_select).multipleSelect('getSelects'),
			cat_ids			: $(cats_select).multipleSelect('getSelects')
		}

		return info;

	}


	function drawTableStoreStock(data){

		tbl_stockStore.clear();
		// tbl_stockStore.ajax.reload();

		var tmp = data.map(function (item) {
			var thing = {
				sucursal 		: '?',
				fecha 			: '0000-00-00',
				categoria 		: '?',
				articulo		: '?',
				clave			: '?',
				cantidad		: 0,
				unidad			: '?'
			};

			thing.sucursal 		= item.store_name;
			thing.categoria		= item.categoryName;
			thing.fecha			= item.fecha;
			thing.articulo 		= item.descripcion;
			thing.clave 		= item.clave;
			thing.cantidad		= item.existencia;
			thing.unidad		= item.unit;

			return thing;
		});

		tbl_stockStore.rows.add(tmp).draw();

	}

	// BUSCA LOS PERMISOS
	function findPermission(action_find, permissions = {}){

		result = true;

		if (jQuery.isEmptyObject(permissions)) {
			permissions = JSON.parse('<?=json_encode($permissions['actions']) ?>');
			if (jQuery.isEmptyObject(permissions)) {
				result = false;
			}
			
		}

		if (result) {

			result = false;
			$.each(permissions, function(index, action_name){
				if (action_find === action_name) {
					console.log(action_name);
					result = true;	
				}
			});

		}

		return result;
	}

	// ARREGLO DE BOTONES
	function buttonsStockStore(){
		bottons_array = [];
		'copy', 'csv', 'excel', 'pdf', 'print'
		if (findPermission('copyTableStockStore')) {bottons_array.push('copy');}
		if (findPermission('csvTableStockStore')) {bottons_array.push('csc');}
		if (findPermission('excelTableStockStore')) {bottons_array.push('excel');}
		if (findPermission('pdfTableStockStore')) {bottons_array.push('pdf');}
		if (findPermission('pintTableStockStore')) {bottons_array.push('print');}

		return bottons_array;
	}


//*********************************************************************************************//
// FUNCIONES POST
//*********************************************************************************************//

	function getStock(sendData = {}){

		sendData.test = 'test';

		callNube('previews/stockStore',sendData).then(function(result, status, jqXHR){
			try{

				console.log(result);
				
				drawTableStoreStock(result.stockStore.rows);


			}catch(err){
				console.log(err);
			}
		// }).done(function(data){
		// 	// console.log(data);
		}).fail(function(jqXHR, textStatus, errorThrown){
			console.log(jqXHR);
		});
	}


});
}); // END jQuery(window).on('load',function () {
});	// END jQuery( function($){
</script>