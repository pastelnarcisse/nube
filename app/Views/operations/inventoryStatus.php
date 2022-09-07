<!-- AdminLTE dashboard demo (This is only for demo purposes) -->

<script src="<?=base_url('public/assets/js/reports-sale.js?v=1.08')?>"></script>


<div id="alert-same" class="alert alert-warning alert-dismissible fade show d-none" role="alert">
	<strong>Status ya guardado</strong> Recarga la página
	<button type="button" class="close" data-dismiss="alert" aria-label="Close">
		<span aria-hidden="true">&times;</span>
	</button>
</div>


<div id="alert-incomplete" class="alert alert-warning alert-dismissible fade show d-none" role="alert">
	<strong>Se guardo incompleto</strong> Avisa a sistemas
	<button type="button" class="close" data-dismiss="alert" aria-label="Close">
		<span aria-hidden="true">&times;</span>
	</button>
</div>

<div id="alert-fail" class="alert alert-danger alert-dismissible fade show d-none" role="alert">
	<strong>No guardo o error al guardar</strong> Avisar a sistemas
	<button type="button" class="close" data-dismiss="alert" aria-label="Close">
		<span aria-hidden="true">&times;</span>
	</button>
</div>


<div id="alert-status" class="alert alert-success alert-dismissible fade show d-none" role="alert">
	<strong>Status completo</strong> Se guardo en servidor
	<button type="button" class="close" data-dismiss="alert" aria-label="Close">
		<span aria-hidden="true">&times;</span>
	</button>
</div>

<div id="alert-save" class="alert alert-success alert-dismissible fade show d-none" role="alert">
	<strong>Se aplico</strong> Checar inventarios
	<button type="button" class="close" data-dismiss="alert" aria-label="Close">
		<span aria-hidden="true">&times;</span>
	</button>
</div>



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

<!-- Main content -->
<section class="content">

	<div class="container-fluid">

		<div class="row bg-secondary">
			
			<div class="col-sm-12">
				
				<div class="btn-group btn-group-lg" role="group" aria-label="Basic example">
					<?php if (array_search('viewAdjustment',$permissions['actions'])): ?>
					<button id="btn-findAjustment" type="button" class="btn btn-secondary btn-action" value="findAjustment">
						<div class="icon">
							<i class="ion ion-android-search"></i>
						</div>
						<small>BUSCAR</small>
					</button>	
					<?php endif ?>
					<?php if (array_search('excel',$permissions['actions'])): ?>
					<button id="btn-deleteProduct" type="button" class="btn btn-secondary btn-action" value="excel">
						<div class="icon">
							<i class="ion ion-clipboard"></i>
						</div>
						<small>QUITAR ARTICULOS</small>
					</button>	
					<?php endif ?>
					<?php if (array_search('pdf',$permissions['actions'])): ?>
					<button id="btn-pdf" type="button" class="btn btn-secondary btn-action" value="pdf">
						<div class="icon">
							<i class="ion ion-document-text"></i>
						</div>
						<small>PDF</small>
					</button>	
					<?php endif ?>
					<?php if (array_search('email',$permissions['actions'])): ?>
					<button id="btn-ema" type="button" class="btn btn-secondary btn-action" value="email">
						<div class="icon">
							<i class="ion ion-email"></i>
						</div>
						<small>eMAIL</small>
					</button>	
					<?php endif ?>
					
				</div>

			</div>

		</div>

		<!-- TABS DE LOS MENUS -->
		<ul class="nav nav-tabs" id="myTab" role="tablist">
			<?php if (array_search('reportSaleGeneral',$permissions['actions'])): ?>
			<li class="nav-item">
				<a class="nav-link active" id="general-tab" data-toggle="tab" href="#general" role="tab" aria-controls="general" aria-selected="true">General</a>
			</li>
			<?php endif ?>
			<?php if (array_search('reportSaleItems',$permissions['actions'])): ?>
			<li class="nav-item">
				<a class="nav-link" id="product-tab" data-toggle="tab" href="#product" role="tab" aria-controls="product" aria-selected="false">Articulos</a>
			</li>	
			<?php endif ?>
			<?php if (array_search('reportSalePackage',$permissions['actions'])): ?>
			<li class="nav-item">
				<a class="nav-link" id="pack-tab" data-toggle="tab" href="#contact" role="tab" aria-controls="contact" aria-selected="false">Paquetes</a>
			</li>	
			<?php endif ?>
			
		</ul>
		<!-- FIN TAB -->

	</div>

</section>

<!-- SECTION TABLA -->
<section class="content">
	<div class="container-fluid">

		<div class="row">

			<div class="col-sm-4 input-group">
				<div class="input-group-prepend">
					<label class="input-group-text" for="startDate">Fecha Inicial</label>
				</div>
				<input 
					type="text" 
					class="form-control datetimepicker-input" 
					id="startDate" 
					data-toggle="datetimepicker" 
					data-target="#changeDateDashboard" 
					value="<?=$startDate?>"
					<?= array_search('selectDate',$permissions['actions']) ? '' : 'disabled' ?>
				/>
			</div>

			<div class="col-sm-4 input-group">
				<div class="input-group-prepend">
					<label class="input-group-text" for="finishDate">Fecha final</label>
				</div>
				<input 
					type="text" 
					class="form-control datetimepicker-input" 
					id="finishDate" 
					data-toggle="datetimepicker" 
					data-target="#changeDateDashboard" 
					value="<?=$finishDate?>"
					<?= array_search('selectDate',$permissions['actions']) ? '' : 'disabled' ?>
				/>
			</div>
		</div>

		<div class="row">
			<div class="col-sm-4 input-group">
				<div class="input-group-prepend">
					<label class="input-group-text" for="startTime">Hora inicial</label>
				</div>
				<input 
					type="text" 
					class="form-control datetimepicker-input" 
					id="startTime" 
					data-toggle="datetimepicker" 
					data-target="#changeDateDashboard" 
					value="00:00"
					<?= array_search('selectTime',$permissions['actions']) ? '' : 'disabled' ?>
				/>
			</div>

			<div class="col-sm-4 input-group">
				<div class="input-group-prepend">
					<label class="input-group-text" for="finishTime">Hora final</label>
				</div>
				<input 
					type="text" 
					class="form-control datetimepicker-input" 
					id="finishTime" 
					data-toggle="datetimepicker" 
					data-target="#changeDateDashboard" 
					value="23:59"
					<?= array_search('selectTime',$permissions['actions']) ? '' : 'disabled' ?>
				/>
			</div>
		</div>
		<!-- /.div row -->
		<hr class="divider">

		<div class="row">
			<div class="col-sm-3">
				<div class="form-check">
					<input 
					class="form-check-input" 
					type="checkbox" 
					id="checkStore" 
					value="option1" 
					checked
					<?= array_search('selectStore',$permissions['actions']) ? '' : 'disabled' ?>
					/>
					<label class="form-check-label" for="checkStore">Todas las sucursales</label>
				</div>
				
			</div>
			<div class="col-sm-1 border-right"></div>
			
			<div class="col-sm-8">
				<div class="form-check">
					<select id="mselectStore" multiple="multiple" class="form-control form-control-sm" disabled>
						<?php foreach ($stores as $store): ?>
						<option value="<?=$store->store_id?>" selected><?=$store->store_name?></option>	
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
					id="checkStatusType" 
					value="option3" 
					<?= array_search('selectStatus',$permissions['actions']) ? '' : 'disabled' ?>
					/>
					<label class="form-check-label" for="checkStatusType">Todas formas de pago</label>
				</div>
			</div>
			<div class="col-sm-1 border-right"></div>
			<div class="col-sm-8">
				<div class="form-check form-check-inline">
					<input name = "checkStatus[]" class="form-check-input checkStatusType" type="checkbox" id="checkStatusCread" value="0" checked>
					<label class="form-check-label" for="checkStatusCread">Creado</label>
				</div>
				<div class="form-check form-check-inline">
					<input name = "checkStatus[]" class="form-check-input checkStatusType" type="checkbox" id="checkStatusCancel" value="-1" >
					<label class="form-check-label" for="checkStatusCancel">Cancelado</label>
				</div>
				<div class="form-check form-check-inline">
					<input name = "checkStatus[]" class="form-check-input checkStatusType" type="checkbox" id="checkStatusAccepted" value="1" checked>
					<label class="form-check-label" for="checkStatusAccepted">Aceptado</label>
				</div>
				<div class="form-check form-check-inline">
					<input name = "checkStatus[]" class="form-check-input checkStatusType" type="checkbox" id="checkStatusApplied" value="2">
					<label class="form-check-label" for="checkStatusApplied">Aplicado</label>
				</div>
			</div>
		</div>
			
				
		<!-- /.div row -->
	</div>

</section>

<!-- SECTION TABLA -->
<section class="content">
	<div class="container-fluid">
		<div class="row">
			
			<div class="col-12 table-responsive">
				
				<table class="table m-0" id="tbl-listInventary">
					<thead>
						<tr>
							<th>FOLIO</th>
							<th>SUCURSAL</th>
							<th>FECHA</th>
							<th>COMENTARIO</th>
							<th>USUARIO</th>
							<th>ESTADO</th>
							<th>ACCIÓN</th>
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

<section>
	<!-- Modal -->
	<div class="modal fade" id="modalDetail" tabindex="-1" role="dialog" aria-labelledby="modalLabelDetail" aria-hidden="true" status="400">
		<div class="modal-dialog modal-lg" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="modalLabelDetail">Lista de artículo</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<h2>Folio: <span id="folioSpan"></span></h2>
					<h2>Mensaje: <span id="mensajeSpan"></span></h2>
					<h3>Sucursal: <span id="sucursalSpan"></span></h3>
					<table class="table m-0" id="tbl-listAdjustmentDetail">
						<thead>
							<tr>
								<th>ARTICULO</th>
								<th>CLAVE</th>
								<th>CANTIDAD AJUSTE</th>
							</tr>
						</thead>
						<tbody></tbody>
						<tfoot></tfoot>
					</table>
					
				</div>
				<div class="modal-footer">
					<button role="button" id="btn-aceptar" class='d-none btn btn-primary btn-status' status="1" id_adjustment = "">Aceptar</button>
					<button role="button" id="btn-cancelar" class='d-none btn btn-danger btn-status' status="-1" id_adjustment = "">Cancelar</button>
					<button role="button" id="btn-aplicar" class='d-none btn btn-primary btn-status' status="2" id_adjustment = "">Aplicar</button>
					<button role="button" id="btn-cierre" type="button" class="btn btn-secondary" data-dismiss="modal">Cierra</button>

				</div>
			</div>
		</div>
	</div>
</section>

<!-- LOADING -->

<section>
	<div id="spinner-div" class="pt-5">
		<div class="spinner-border text-primary" role="status">
		</div>
	</div>
</section>


<script type="text/javascript">


/***********************************************/
// VALUES
/***********************************************/

// ID de tabla de inventarios por sucursal
const findAjustment_id			= '#btn-findAjustment';

const startDate_input			= '#startDate';
const finishDate_input			= '#finishDate';
const startTime_input			= '#startTime';
const finishTime_input			= '#finishTime';
const selectStore_id			= '#mselectStore';
const checkStore_id 			= '#checkStore';
const selectStatus_class		= '.checkStatusType';
const selectStatus_id			= '#checkStatusType';
const tblList_id 				= '#tbl-listInventary';
const tblListDetail_id			= '#tbl-listAdjustmentDetail';
const modalDetail_id			= '#modalDetail';
const btnAceptar_id				= '#btn-aceptar';
const btnCancelar_id			= '#btn-cancelar';
const btnAplicar_id				= '#btn-aplicar';
const btnStatus_class			= '.btn-status';
const idAdjustemnt_attr			= 'id_adjustment';
const loading_id 				= '#spinner-div';
//SPAN
const folioSpan_id				= '#folioSpan';
const mensajeSpan_id			= '#mensajeSpan';
const sucursalSpan_id			= '#sucursalSpan';
//ALERT
const alertIncomplete_id		= "#alert-incomplete";
const alertFail_id				= "#alert-fail";
const alertStatus_id			= "#alert-status";
const alertSave_id				= "#alert-save";
const alertSame_id				= "alert-same";

const formatterNumber 			= new Intl.NumberFormat('es-MX');
//SUCURSALES POR PHP
const stores 					= <?=json_encode($stores)?>;

var idStores 					= [];

let status 					= [];

// Objeto datatable || INVENTARIOS POR CATEGORIA
let tbl_listDetail		= $(tblListDetail_id).DataTable({
	language : { url : '//cdn.datatables.net/plug-ins/1.11.3/i18n/es_es.json'},
	columns: [
		{ data: 'articulo' },
		{ data: 'clave' },
		{ data: 'ajuste' }
	],
	order: [[ 0, "desc" ]],
	dom: 'Bfrtip',
	//buttons: buttonsStockStore(),
	info : false,
	ordering : true,
	searching: false,
	paging: true,
	pageLength: 50,
	processing : true

});

// Objeto datatable || INVENTARIOS POR CATEGORIA
let tbl_list		= $(tblList_id).DataTable({
	language : { url : '//cdn.datatables.net/plug-ins/1.11.3/i18n/es_es.json'},
	columns: [
		{ data: 'folio' },
		{ data: 'sucursal' },
		{ data: 'fecha' },
		{ data: 'comentario'},
		{ data: 'usuario'},
		{ data: 'estado', render: function (data, type, row, meta) {
			var ret = 'DESCONOCIDO';
			switch (data) {
				case -1 :
				case '-1':
					ret = '<span class="text-danger">CANCELADO</span>';	
					break;
				case 0 :
				case '0':
					ret = '<span class="text-primary">CREADO</span>';	
					break;
				case 1 :
				case '1':
					ret = '<span class="text-success">ACEPTADO</span>';	
					break;
				case 2 :
				case '2':
					ret = '<span class="text-secondary">APLICADO</span>';	
					break;
				default:
					break;
			} return ret} 

		},
		{ data: 'accion', render: function (data, type, row, meta) {

			var button = '<button role="button" id="btn-ver" type="button" class="btn btn-secondary btn-sm btn-ver" onClick="getListDetail('+row.folio+');">Ver</button>';
			
			if (data == 0 || data == '0') {
				button += '<button role="button" type="button" class="btn btn-primary btn-sm btn-status" id_adjustment="'+row.folio+'" status="1">Aceptar</button>';
				button += '<button role="button" type="button" class="btn btn-danger btn-sm btn-status" id_adjustment="'+row.folio+'" status="-1">Cancelar</button>';
			}

			if (data == 1 || data == '1') {
				button += '<button role="button" type="button" class="btn btn-primary btn-sm btn-status" id_adjustment="'+row.folio+'" status="2">Aplicar</button>';
			}
			
			// var myTemp = document.querySelector(".btn-ver");
			// myTemp.innerHTML;
			return button },
		}
	],
	order: [[ 0, "desc" ]],
	dom: 'Bfrtip',
	//buttons: buttonsStockStore(),
	info : false,
	ordering : true,
	searching: true,
	paging: true,
	pageLength: 50,
	processing : true

});

$(function () {


/***********************************************/
// OPTIONS
/***********************************************/
	// Fecha inicial general
	$(startDate_input).datetimepicker({
		format: 'YYYY-MM-DD',
		locale: 'es'
	});

	// Fecha final general
	$(finishDate_input).datetimepicker({
		format: 'YYYY-MM-DD',
		locale: 'es'
	});

	// Fecha inicial general
	$(startTime_input).datetimepicker({
		format: 'HH:mm',
		locale: 'es'
	});

	// Fecha final general
	$(finishTime_input).datetimepicker({
		format: 'HH:mm',
		locale: 'es'
	});

	// Selección de categorías Stock
	$(selectStore_id).multipleSelect({
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


	/***********************************************/
	// EJECUTAR
	/***********************************************/
	checklocalhost(idStores);

	sendData = infoForm();

	getList(sendData, tbl_list);

});


/***********************************************/
// EVENTS
/***********************************************/

	//BUSCAR POR LOS FILTROS
	$(findAjustment_id).click(function(){
		sendData = infoForm();
		getList(sendData, tbl_list);
	});

	// Click al check de tipo pago general
	$( selectStatus_id ).on( 'click', function() {
		if( $(this).is(':checked') ){
			// Hacer algo si el checkbox ha sido seleccionado
			$(selectStatus_class).prop( "disabled", true );
			$(selectStatus_class).prop( "checked", true );
		} else {
			// Hacer algo si el checkbox ha sido deseleccionado
			$(selectStatus_class).prop( "disabled", false );
		}
	});

	// Click al check de tiendas general
	$( checkStore_id ).on( 'click', function() {
		if( $(this).is(':checked') ){
			// Hacer algo si el checkbox ha sido seleccionado
			$(selectStore_id).multipleSelect('disable');
			$(selectStore_id).multipleSelect('checkAll');
		} else {
			// Hacer algo si el checkbox ha sido deseleccionado
			$(selectStore_id).multipleSelect('enable');
		}
	});


/**
 * Se ejecuta de nuevo los eventos dentro de esta función.
 * 
 */
function reloadEvent() {
	
	$(btnStatus_class).on('click',function(){

		data = {};

		data.idAdjustment = $(this).attr('id_adjustment');
		data.status = $(this).attr('status');

		setStatus(data);

	});

}




//*********************************************************************************************//
// FUNCIONES LOCALES
//*********************************************************************************************//

/**
 * 
 * 
 * @return Objetos de datos de envío
 * */
function infoForm(){

	status = [];

	$(selectStatus_class).each(function(){
		if ($(this).is(':checked')) {
			status.push($(this).val());
		}
		
	});

	info = {
		start_date 				: $(startDate_input).val(),
		finish_date 			: $(finishDate_input).val(),
		start_time 				: $(startTime_input).val(),
		finish_time 			: $(finishTime_input).val(),
		adjustmentStartdate 	: $(startDate_input).val() + ' '+$(startTime_input).val(),
		adjustmentFinishdate 	: $(finishDate_input).val() + ' '+$(finishTime_input).val(),
		storesId				: $(selectStore_id).multipleSelect('getSelects'),
		status 					: status
	}

	console.log(info);

	return info;

}

/**
 * Dibuja los nuevos datos de la tabla
 * 
 */
function drawTableList(data){

	tbl_list.clear();

	var tmp = data.map(function (item) {
		var thing = {
			folio			: '?',
			sucursal 		: '?',
			fecha 			: '0000-00-00',
			comentario 		: '?',
			usuario			: '?',
			estado			: '?',
			accion			: '?'
		};

		thing.folio			= item.id;
		thing.sucursal 		= item.store_name;
		thing.fecha			= item.adjustment_date;
		thing.comentario	= item.comentario;
		thing.usuario 		= item.adjustment_user;
		thing.estado		= item.status;
		thing.accion		= item.status;

		return thing;
	});

	tbl_list.rows.add(tmp).draw();

}

function drawTableDetail(data){
	
	tbl_listDetail.clear();

	var tmp = data.map(function (item) {
		var thing = {
			articulo		: '?',
			clave 			: '?',
			ajuste 			: '-'
		};

		thing.articulo		= item.descripcion;
		thing.clave 		= item.clave;
		thing.ajuste		= item.ajuste;

		return thing;
	});

	tbl_listDetail.rows.add(tmp).draw();

}


//*********************************************************************************************//
// FUNCIONES POST - AJAX
//*********************************************************************************************//

function checklocalhost(idStores){


	callLocal('app/getStore',{stores:idStores}).then(function(result, status, jqXHR){
		try{


		if (result.code == 400) {
			//$('#btn-addProduct').prop('disabled', true);
		}

		}catch(err){
			console.log(err);
		}

	}).fail(function(jqXHR, textStatus, errorThrown){
		console.log(jqXHR);
	});
}

/**
 * Dame la lista de inventarios y dibuja la lista
 * @param sendData object 
 */
function getList(sendData = {}){

	$("#spinner-div").show();

	sendData.functionName = 'getListInventary';


	callNube('operations/inventoryStatus',sendData).then(function(result, status, jqXHR){
		try{

			console.log(result);
			drawTableList(result.object);
			reloadEvent();

			$("#spinner-div").hide();

		}catch(err){

			console.log(err);

			$("#spinner-div").hide();

		}
	}).fail(function(jqXHR, textStatus, errorThrown){

		console.log(jqXHR);
		$("#spinner-div").hide();
	});

}


/**
 * Trar la lista de articulos para mostrarlos en una tabla
 * @param idAdjustment int id de lista de ajuste
 */
function getListDetail(idAdjustment){
	
	$("#spinner-div").show();

	sendData.functionName = 'getListInventaryDetail';
	sendData.idAdjustment = idAdjustment;

	callNube('operations/inventoryStatus',sendData).then(function(result, status, jqXHR){
		try{

			console.log(result);

			if (result.object.status == 0 || result.object.status == '0') {
				$(btnAceptar_id).removeClass('d-none');
				$(btnCancelar_id).removeClass('d-none');
				$(btnAplicar_id).addClass('d-none');
			}

			if (result.object.status == 1 || result.object.status == '1') {
				$(btnAceptar_id).addClass('d-none');
				$(btnCancelar_id).addClass('d-none');
				$(btnAplicar_id).removeClass('d-none');
			}

			drawTableDetail(result.objects);

			//LLENADO DE INFORMACION AL MODAL
			
			$(btnStatus_class).attr('id_adjustment',idAdjustment);

			$(sucursalSpan_id).html(result.object.store_name);

			$(mensajeSpan_id).html(result.object.comentario);

			$(folioSpan_id).html(result.object.id);

			$(modalDetail).modal('show');

			$("#spinner-div").hide();

		}catch(err){

			console.log(err);

			$("#spinner-div").hide();

		}
	}).fail(function(jqXHR, textStatus, errorThrown){

		console.log(jqXHR);
		$("#spinner-div").hide();
	});
}

/**
 * Trar la lista de articulos para mostrarlos en una tabla
 * @param idAdjustment int id de lista de ajuste
 */
function setStatus(sendData){
	
	$("#spinner-div").show();

	sendData.functionName 	= 'adjustmentStatus';

	console.log(sendData);

	callNube('operations/inventoryStatus',sendData).then(function(result, status, jqXHR){
		try{

			console.log(result);

			if (result.code == 200) {

				$(alertSave_id).removeClass('d-none');
				
				$(alertSave_id).delay(4000).slideUp(200, function() {
					$(this).addClass('d-none');
				});
			}

			if	(result.status == 200){
				
				$(alertStatus_id).removeClass('d-none');
				
				$(alertStatus_id).delay(4000).slideUp(200, function() {
					$(this).addClass('d-none');
				});
			}

			if (result.status != 200 && result.code != 200 ) {
				$(alertIncomplete_id).removeClass('d-none');
				
				$(alertIncomplete_id).delay(4000).slideUp(200, function() {
					$(this).addClass('d-none');
				});
			}

			sendData = infoForm();

			getList(sendData, tbl_list);

			$("#spinner-div").hide();

		}catch(err){

			if (err.status == 401) {

				$(alertSame_id).removeClass('d-none');
			
				$(alertSame_id).delay(4000).slideUp(200, function() {
					$(this).addClass('d-none');
				});
				
			}else{

				$(alertFail_id).removeClass('d-none');
			
				$(alertFail_id).delay(4000).slideUp(200, function() {
					$(this).addClass('d-none');
				});
			}

			console.log(err);

			$("#spinner-div").hide();

		}
	}).fail(function(jqXHR, textStatus, errorThrown){

		$(alertFail_id).removeClass('d-none');
			
		$(alertFail_id).delay(4000).slideUp(200, function() {
			$(this).addClass('d-none');
		});

		console.log(jqXHR);
		$("#spinner-div").hide();
	});
}





</script>