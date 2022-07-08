<!-- Content Wrapper. Contains page content -->
<div class="content">
<!-- Content Header (Page header) -->
<div class="content-header">
	<div class="container-fluid">
		<div class="row">
			<div class="col-sm-4">
				<h1 class="m-0"><?=$module->name?></h1>
			</div><!-- /.col -->

			
			<div class="col-sm-4">
				<input type="text" class="form-control datetimepicker-input" id="changeDateDashboard" data-toggle="datetimepicker" data-target="#changeDateDashboard" value="<?=$dateDashboard?>" <?=isset($dateDashboardChange) ? '' : 'disabled'?>/>
			</div>
			<div class="input-group-append text-nowrap " data-toggle="datetimepicker" data-target="#changeDateDashboard">
        <span class="input-group-text ">
          <span title="Kalender">
            <span class="fa fa-calendar fa-fw" aria-hidden="true">
            </span>
        </span>
        </span>
      </div>
			

			<div class="col-sm-4">
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

		<!-- Small boxes (Stat box) -->
		<div class="row">

			<?php if (isset($countSales)): ?>
			<div class="col-lg-3 col-6">
			<!-- small box -->
				<div class="small-box bg-info">
					<div class="inner">
						<h3><span id="countSalesResult"><?=$countSales['result']?></span></h3>

						<p><?=$countSales['info']->actionDescrption?></p>
					</div>
					<div class="icon">
						<i class="ion ion-bag"></i>
					</div>
					<!-- <a href="#" class="small-box-footer">Mas información <i class="fas fa-arrow-circle-right"></i></a> -->
				</div>
			</div>
			<?php endif ?>
			
			<?php if (isset($sumSales)): ?>
			<div class="col-lg-3 col-6">
			<!-- small box -->
				<div class="small-box bg-success">
					<div class="inner">
						<h3><sup style="font-size: 20px">$</sup><span id="sumSalesResult"><?=$sumSales['result']?></span></h3>

						<p><?=$sumSales['info']->actionDescrption?></p>
					</div>
					<div class="icon">
						<i class="ion ion-cash"></i>
					</div>
					<a id="reportSale" href="<?=base_url('reports/sale/'.$dateDashboard)?>" class="small-box-footer">VER REPORTE <i class="fas fa-arrow-circle-right"></i></a>
				</div>
			</div>	
			<?php endif ?>
				
		</div>
		<!-- /.row -->



		<!-- Tables (Stat box) -->
		<div class="row">
			
			<?php if (isset($tblSaleStore)): ?>
			<div class="col-sm-12 col-md-7 col-lg-8">
				<!-- TABLE: LATEST ORDERS -->
				<div class="card">
					<div class="card-header border-transparent">
						<h3 class="card-title"><?=$tblSaleStore['info']->actionDescrption?></h3>

						<div class="card-tools">
							<button type="button" class="btn btn-tool" data-card-widget="collapse">
								<i class="fas fa-minus"></i>
							</button>
						</div>
					</div>
					<!-- /.card-header -->
					<div class="card-body p-0">
						<div class="table-responsive">
							<table class="table m-0" id="tbl-salesStore">
								<thead>
									<tr>
										<th>Sucursal</th>
										<th class="text-right">Efectivo</th>
										<th class="text-right">Tarjeta</th>
										<th class="text-right">Transeferencia</th>
										<th class="text-right">Total</th>
										<th class="text-right">Tickets</th>
										<th class="text-right">Pedidos</th>
									</tr>
								</thead>
								<tbody>
									<?php foreach ($tblSaleStore['data'] as $saleStore): ?>
									<tr>
										<td><?=$saleStore->sucursal?></td>
										<td><?=$saleStore->efectivo?></td>
										<td><?=$saleStore->tarjeta?></td>
										<td><?=$saleStore->transferencia?></td>
										<td><?=$saleStore->total?></td>
										<td><?=$saleStore->tickets?></td>
										<td><?=$saleStore->credito?></td>
									</tr>	
									<?php endforeach ?>
								</tbody>
							</table>
						</div>
						<!-- /.table-responsive -->
					</div>
					<!-- /.card-body -->
					<div class="card-footer clearfix">
						<a href="<?=base_url('previews/SaleDates')?>">Consultar ventas</a>
					</div>
					<!-- /.card-footer -->
				</div>
				<!-- /.card -->
			</div>
			<!-- /.col-sm-12 col-md-7 col-lg-8 -->
			<?php endif ?>

			<?php if (isset($tblStockCategory)): ?>			
			<div class="col-sm-12 col-md-3 col-lg-4">
				<!-- TABLE: STOCK CATEGORY -->
				<div class="card">
					<div class="card-header border-transparent">
						<h3 class="card-title"><?=$tblStockCategory['info']->actionDescrption?></h3>

						<div class="card-tools">
							<button type="button" class="btn btn-tool" data-card-widget="collapse">
								<i class="fas fa-minus"></i>
							</button>
						</div>
					</div>
					<!-- /.card-header -->
					<div class="card-body p-0">
						<div class="table-responsive">
							<table class="table m-0" id="tbl-stockCategory">
								<thead>
									<tr>
										<th>Categoría</th>
										<th class="text-right">Cantidad</th>

									</tr>
								</thead>
								<tbody>
									<?php if (!empty($tblStockCategory['data_category'])): ?>
									<?php foreach ($tblStockCategory['data_category'] as $stockCategory): ?>
									<tr>
										<td><?=$stockCategory->categoria?></td>
										<td><?=$stockCategory->existencia?></td>
									</tr>	
									<?php endforeach ?>
									<?php endif ?>
								</tbody>
							</table>
						</div>
						<!-- /.table-responsive -->
					</div>
					<!-- /.card-body -->
					<div class="card-footer clearfix">
						<a href="<?=base_url('previews/stockStore')?>">Consultar inventarios</a>
					</div>
					<!-- /.card-footer -->
				</div>
				<!-- /.card -->
			</div>
			<!-- /.col-sm-12 col-md-3 col-lg-4 -->
			<?php endif ?>

		</div>
		<!-- /.row -->	
		
		
	</div>
	<!-- /.container-fluid -->
</section>
<!-- /.content -->

<script type="text/javascript">
$(document).ready(function () {

//*********************************************************************************************//
// VARIABLES
//*********************************************************************************************//
	

	// ID de fechas input
	var date_idInput 		= '#changeDateDashboard';

	// ID de tabla de ventas por sucursal
	var saleStore_idInput 	= '#tbl-salesStore';

	// ID de tabla de ventas por sucursal
	var stockCategory_idInput 	= '#tbl-stockCategory';

	// ID de la cuenta de ventas
	var countSalesResultID	= '#countSalesResult';

	// ID de la suma de ventas
	var	sumSalesResultID	='#sumSalesResult';


	// Dame la fecha
	var actual_date 		= $(date_idInput).val();

	// Ruta de la página
	var base_url 			= '<?=base_url()?>';

	var formatter = new Intl.NumberFormat('es-MX', {
		style: 'currency',
		currency: 'MXN',
	});
	var formatterNumber = new Intl.NumberFormat('es-MX');

	// Objeto datatable || VENTAS POR TIENDA
	var tbl_saleStore 		= $(saleStore_idInput).DataTable({
		language : { url : '//cdn.datatables.net/plug-ins/1.11.3/i18n/es_es.json'},
		columns: [
			{ data: 'SUCURSAL' },
			{ data: 'EFECTIVO', render: function (data, type, row, meta) {
				return formatter.format(data); }, className: "text-right" 
			},
			{ data: 'TARJETA', render: function (data, type, row, meta) {
				return formatter.format(data); }, className: "text-right" 
			},
			{ data: 'TRANSFERENCIA', render: function (data, type, row, meta) {
				return formatter.format(data); }, className: "text-right" 
			},
			{ data: 'TOTAL', render: function (data, type, row, meta) {
				return formatter.format(data); }, className: "text-right" 
			},
			{ data: 'TICKETS', render: function (data, type, row, meta) {
				return formatterNumber.format(data); }, className: "text-right" 
			},
			{ data: 'PEDIDOS', render: function (data, type, row, meta) {
				return formatter.format(data); }, className: "text-right" 
			}
		],
		order: [[ 3, "desc" ]],
		dom: 'Bfrtip',
		buttons: [
			'copy', 'csv', 'excel', 'pdf', 'print'
		],
		info : false,
		ordering : true,
		searching: false,
		paging: false,

	});

	// Objeto datatable || VENTAS POR TIENDA
	var tbl_stockCategory 		= $(stockCategory_idInput).DataTable({
		language : { url : '//cdn.datatables.net/plug-ins/1.11.3/i18n/es_es.json'},
		columns: [
			{ data: 'categoria' },
			{ data: 'existencia', render: function (data, type, row, meta) {
				return formatterNumber.format(data); }, className: "text-right" 
			}
		],
		order: [[ 1, "desc" ]],
		dom: 'Bfrtip',
		buttons: [
			'copy', 'csv', 'excel', 'pdf', 'print'
		],
		info : false,
		ordering : true,
		searching: false,
		paging: false,

	});

	// Calendario con formatio español
	$(date_idInput).datetimepicker({
		format: 'YYYY-MM-DD',
		locale: 'es',
		ignoreReadonly: true,
	});
	


//*********************************************************************************************//
// EJECUTAR FUNCIONES
//*********************************************************************************************//
	setInterval(function () {
		getActualSales({date : actual_date});
	}, 1*60*1000);

//*********************************************************************************************//
// EVENTOS
//*********************************************************************************************//

	// Cambio en el input de fecha
	$(date_idInput).on('change input',function(e){

		if (this.value != actual_date ) {
			window.location.replace(base_url+"/statistics/dashboard/"+this.value);
		}

	});

//*********************************************************************************************//
// FUNCIONES LOCALES
//*********************************************************************************************//

	function drawActualSales(data){

		tbl_saleStore.clear();

		var tmp = data.map(function (item) {
			var thing = {
				SUCURSAL 		: '?',
				EFECTIVO 		: 0,
				TARJETA 		: 0,
				TRANSFERENCIA 	: 0,
				TOTAL 			: 0,
				TICKETS			: 0,
				PEDIDOS			: 0
			};

			thing.SUCURSAL 		= item.sucursal;
			thing.EFECTIVO 		= item.efectivo;
			thing.TARJETA 		= item.tarjeta;
			thing.TRANSFERENCIA	= item.transferencia;
			thing.TOTAL 		= item.total;
			thing.TICKETS		= item.tickets;
			thing.PEDIDOS		= item.credito;

			return thing;
		});

		tbl_saleStore.rows.add(tmp).draw();

	}

	function drawActualStock(data){

		tbl_stockCategory.clear();

		tmp = [];

		$.each(data, function(index, item){
			tmp.push({categoria : index, existencia : item.existencia});
		});


		tabla = tbl_stockCategory.rows.add(tmp).draw();

		$( tabla ).find('td').eq(4).addClass('d-none');
	}

//*********************************************************************************************//
// FUNCIONES POST
//*********************************************************************************************//

	function getActualSales(sendData){

		sendData.test = 'test';

		callNube('statistics/dashboard',sendData).then(function(result, status, jqXHR){
			try{

				// console.log(result);
				// ACtualiza la tabla de ventas por sucursal
				if (typeof result.tblSaleStore != 'undefined') {
					drawActualSales(result.tblSaleStore.data);
				}
				// ACtualiza la tabla de ventas por sucursal
				if (typeof result.tblStockCategory != 'undefined') {
					drawActualStock(result.tblStockCategory.data_category);
				}
				// Actualiza el numero de ventas
				if (typeof result.countSales != 'undefined') {
					$(countSalesResultID).html(result.countSales.result);
				}
				// Actualiza la suma de ventas
				if (typeof result.sumSales != 'undefined') {
					$(sumSalesResultID).html(result.sumSales.result);
				}
				
				console.log('actualizado');

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
</script>
