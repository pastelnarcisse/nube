<!-- AdminLTE dashboard demo (This is only for demo purposes) -->

<script src="<?=base_url('public/assets/js/reports-sale.js?v=1.08')?>"></script>

<div class="alert alert-warning d-none" role="alert" id="alertInfo">
  <span id="infoMessageAlert"></span>
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
					<?php if (array_search('addProduct',$permissions['actions'])): ?>
					<button id="btn-addProduct" type="button" class="btn btn-secondary btn-action" value="addProduct" data-toggle="modal" data-target="#exampleModal">
						<div class="icon">
							<i class="ion ion-android-search"></i>
						</div>
						<small>AGREGAR ARTICULO</small>
					</button>	
					<?php endif ?>
					<?php //if (array_search('excel',$permissions['actions'])): ?>
					<button id="btn-deleteProduct" type="button" class="btn btn-secondary btn-action" value="excel">
						<div class="icon">
							<i class="ion ion-clipboard"></i>
						</div>
						<small>QUITAR ARTICULOS</small>
					</button>	
					<?php //endif ?>
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
					<label class="input-group-text" for="startDateGeneral">Fecha Inicial</label>
				</div>
				<input 
					type="text" 
					class="form-control datetimepicker-input" 
					id="startDateGeneral" 
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
					id="finishDateGeneral" 
					data-toggle="datetimepicker" 
					data-target="#changeDateDashboard" 
					value="<?=$finishDate?>"
					<?= array_search('selectDate',$permissions['actions']) ? '' : 'disabled' ?>
				/>
			</div>


		</div>
		<!-- /.div row -->
		<hr class="divider">
		

		<!-- /.div row -->

	</div>

</section>

<!-- MODAL BUSQUEDA DE ARTÍCULOS -->
<section>
	<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-lg" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="exampleModalLabel">Buscar artículo</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<div class="row">
						<div class="col-6">
							<input type="text" name="findItem" id="in-findItem">
						</div>
						<div class="col-6">
							<button type="button" class="btn btn-primary" id="mbtn-Seleccionar">Buscar artículo</button>
						</div>

					</div>
					<div class="row">
						<div class="col-12 table-responsive">

						
							<table class="table table-striped table-hover responsive" id="tbl-findProduct" style="width : 100%;">
								<thead>
									<tr>
										<th>ID</th>
										<th>SUCURSAL</th>
										<th>ARTICULO</th>
										<th>CLAVE</th>
										<th class="text-right">EXISTENCIA</th>
										<th>UNIDAD</th>
									</tr>
								</thead>
								<tbody></tbody>
								<tfoot></tfoot>
							</table>
							<!-- /. tbl-findProduct -->
						</div>

					</div>

					
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
					<!-- <button type="button" class="btn btn-primary" id="mbtn-Seleccionar">Seleccionar</button> -->
				</div>
			</div>
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

	// ID de tabla de inventarios por sucursal
	var addProduct_tbl 				= '#tbl-addProduct';

	var findProduct_tbl				= '#tbl-findProduct';

	var inputWord 					= '#in-findItem';

	var formatterNumber = new Intl.NumberFormat('es-MX');

	var stores = <?=json_encode($stores)?>;

	var idStores = [];

/***********************************************/
// OPTIONS
/***********************************************/
	// Objeto datatable || INVENTARIOS POR CATEGORIA
	var tbl_addProduct		= $(addProduct_tbl).DataTable({
		language : { url : '//cdn.datatables.net/plug-ins/1.11.3/i18n/es_es.json'},
		columns: [
			{ 	data: 'art_id', 
				className: 'd-none', 
				title : 'ID',
				name : 'art_id'
			},
			{ 	data: 'sucursal', 
				className: 'd-none', 
				title : 'SUCURSAL',
				name : 'sucursal'
			},
			{ 	data: 'articulo', 
				title : 'ARTÍCULO',
				name : 'articulo'
			},
			{ 	data: 'clave', 
				title : 'CLAVE',
				name : 'clave'
			},
			{ 	data: 'ajustar', 
				render: function (data, type, row, meta){
					return '<input class="text-right ajustar" id="input-'+row.clave+'" clave="'+row.clave+'" type="number" value="'+data+'">';
				},
				title : 'AJUSTAR',
				name : 'ajustar'
			},			
			{ 	data: 'existencia_actual', 
				render: function (data, type, row, meta){
					return '<span id="stock-'+row.clave+'" existencia="'+data+'">'+formatterNumber.format(data)+'</span>';
				},
				className: 'text-right',
				title : 'EXISTENCIA ACTUAL',
				name : 'existencia_actual'

			},
			{ 	data: 'nueva_existencia', 
				render: function (data, type, row, meta){

					class_color = row.nueva_existencia > row.existencia_actual ? 'text-primary' : row.nueva_existencia < row.existencia_actual ? 'text-danger' : '';

					return '<span class="'+class_color+'" id="span-'+row.clave+'" existencia="'+data+'">'+formatterNumber.format(data)+'</span>';
				},
				className: 'text-right',
				title : 'NUEVA EXISTENCIA',
				name : 'nueva_existencia'

			},
			{ 	data: 'unidad', 
				title : 'UNIDAD',
				name : 'unidad'
			},
		],
		order: [[ 3, "desc" ]],
		info : false,
		ordering : true,
		searching: false,
		paging: true,
		pageLength: 50,
		processing : true

	});

		// Objeto datatable || INVENTARIOS POR CATEGORIA
	var tbl_findProduct	= $(findProduct_tbl).DataTable({
		language : { url : '//cdn.datatables.net/plug-ins/1.11.3/i18n/es_es.json'},
		columns: [
			{ 	data: 'art_id', 
				className: 'd-none', 
				title : 'ID',
				name : 'art_id'
			},
			{ data: 'sucursal', className: "d-none"},
			{ data: 'articulo'},
			{ data: 'clave'},
			{ data: 'existencia', render: function (data, type, row, meta) {
				return formatterNumber.format(data); }, className: "text-right", type: "num-fmt"
			},
			{ data: 'unidad'}
		],
		order: [[ 3, "desc" ]],
		info : false,
		ordering : true,
		searching: false,
		paging: true,
		lengthChange: false,
		pageLength: 10,
		processing : true

	});


	$.each(stores, function(index, store){
		idStores.push(store.store_id);
	});



/***********************************************/
// EVENTS
/***********************************************/

// Buscar la palabra por ajax
$('#mbtn-Seleccionar').on('click',function(event){

	word = $(inputWord).val();

	findProdutByWord(word);

});

// Doble click para agregarlo a la tabla de agregar artículos.
$(findProduct_tbl).on('dblclick','tr',function(e){
 
    add_product = tbl_findProduct.row(this).data();
	
    if ($('#input-'+add_product.clave).length == 0) {

    	input_clave = "input-"+add_product.clave;
    	span_clave	= "span-"+add_product.clave;

		add_product.ajustar = 0;
		add_product.existencia = parseFloat(add_product.existencia);
		add_product.DT_RowId = 'dt-'+add_product.clave;
		add_product.existencia_actual = add_product.existencia;
		add_product.nueva_existencia = add_product.existencia;

		tbl_addProduct.row.add(add_product).draw();
    }

	
});

// Actualiza los datos
$(addProduct_tbl).on('input','td',function(e){
    
    data = tbl_addProduct.row(this).data();

    cell_info = tbl_addProduct.cell( this ).index();

    if (cell_info.column == 4) {

    	existencia_actual = data.existencia_actual;

    	suma_ajuste = $('#input-'+data.clave).val() == '' ? 0 : parseFloat($('#input-'+data.clave).val());

    	nueva_existencia = existencia_actual + suma_ajuste;

    	tbl_addProduct.cell(cell_info.row, 6 ).data(nueva_existencia);
    }

    
});

// 
$('#btn-guardar').click(function(){

	inputs = [];

	isPositiveStock = true;

	$('.ajustar').each(function(index, item){

		console.log(index);

		row = index;
		column_ajustar = 4;
		column_nuevaExistencia = 6;

		ajustar = parseFloat($(this).val());

		tbl_addProduct.cell(row, column_ajustar).data(ajustar);
		nueva_existencia = tbl_addProduct.cell(row, column_nuevaExistencia).data();

		isPositiveStock = nueva_existencia >= 0 ? isPositiveStock : false;

	});


	data = tbl_addProduct.rows().data().toArray();

	comentario = $('#input_comentario').val();

	if (!isPositiveStock) {
		window.alert('Tienes inventario negativo');
	}else if(comentario == ''){
		window.alert('No hay comentario');
	}else {
		setNewStock({details : data});
	}


	

	console.log(data);
	
});

// Doble click para eliminar row
$(addProduct_tbl+' tbody').on('click','tr',function(e){
 
    if ( $(this).hasClass('selected bg-secondary') ) {
		$(this).removeClass('selected bg-secondary');
	}
	else {
		tbl_addProduct.$('tr.selected bg-secondary').removeClass('selected bg-secondary');
		$(this).addClass('selected bg-secondary');
	}
	
});


$('#btn-deleteProduct').click( function () {
	tbl_addProduct.row('.selected').remove().draw( false );
} );

/***********************************************/
// EJECUTAR
/***********************************************/
checklocalhost(idStores);

//*********************************************************************************************//
// FUNCIONES LOCALEs
//*********************************************************************************************//

//*********************************************************************************************//
// FUNCIONES POST
//*********************************************************************************************//

	function checklocalhost(idStores){


		callLocal('app/getStore',{stores:idStores}).then(function(result, status, jqXHR){
			try{


				if (result.code == 400) {
					$('#btn-addProduct').prop('disabled', true);
				}


			}catch(err){
				console.log(err);
			}
		// }).done(function(data){
		// 	// console.log(data);
		}).fail(function(jqXHR, textStatus, errorThrown){
			console.log(jqXHR);
		});
	}

	function findProdutByWord(word){


		callLocal('app/getProductsByWord',{word:word}).then(function(result, status, jqXHR){
			try{

				console.log(result);
				tbl_findProduct.clear();

				var tmp = result.object.map(function (item) {
					var thing = {
						art_id			: 0,
						sucursal		: '?',
						articulo 		: '?',
						clave 			: 0,
						existencia 		: 0,
						unidad		 	: 0
					};

					thing.art_id		= item.art_id;
					thing.sucursal		= item.sucursal;
					thing.articulo 		= item.descripcion;
					thing.clave 		= item.clave;
					thing.existencia	= item.existencia;
					thing.unidad		= item.unidad;


					return thing;
				});

				row = tbl_findProduct.rows.add(tmp).draw();
				
			}catch(err){
				console.log(err);
			}
		// }).done(function(data){
		// 	// console.log(data);
		}).fail(function(jqXHR, textStatus, errorThrown){
			console.log(jqXHR);
		});
	}

	function setNewStock(sendData = {}){

		sendData.test = 'test';

		callNube('operations/inventoryAdjustment',sendData).then(function(result, status, jqXHR){
			try{

				console.log(result);


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