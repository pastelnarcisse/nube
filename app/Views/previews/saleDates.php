<!-- AdminLTE dashboard demo (This is only for demo purposes) -->

<script src="<?=base_url('public/assets/js/reports-sale.js?v=1.08')?>"></script>

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
					<?php if (array_search('findSalesDates',$permissions['actions'])): ?>
					<button id="btn-findSalesDates" type="button" class="btn btn-secondary btn-action" value="findSalesDates">
						<div class="icon">
							<i class="ion ion-flash"></i>
						</div>
						<small>BUSCAR</small>
					</button>	
					<?php endif ?>
					<?php if (array_search('excel',$permissions['actions'])): ?>
					<button id="btn-exc" type="button" class="btn btn-secondary btn-action" value="excel">
						<div class="icon">
							<i class="ion ion-clipboard"></i>
						</div>
						<small>Excel</small>
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

		<hr class="divider">

		<!-- TAB CONTENT -->
		<div class="tab-content" id="general-tab-content">
		
			<!-- TAB PANE GENERAL -->		
			
			<div class="tab-pane fade show active" id="general" role="tabpanel" aria-labelledby="general-tab">
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

					<div class="col-sm-4"></div>
					
					<div class="col-sm-4 input-group">
						<div class="input-group-prepend">
							<label class="input-group-text" for="startTimeGeneral">Hora inicial</label>
						</div>
						<input 
							type="text" 
							class="form-control datetimepicker-input" 
							id="startTimeGeneral" 
							data-toggle="datetimepicker" 
							data-target="#changeDateDashboard" 
							value="00:00"
							<?= array_search('selectTime',$permissions['actions']) ? '' : 'disabled' ?>
						/>
					</div>

					<div class="col-sm-4 input-group">
						<div class="input-group-prepend">
							<label class="input-group-text" for="finishTimeGeneral">Hora final</label>
						</div>
						<input 
							type="text" 
							class="form-control datetimepicker-input" 
							id="finishTimeGeneral" 
							data-toggle="datetimepicker" 
							data-target="#changeDateDashboard" 
							value="23:59"
							<?= array_search('selectTime',$permissions['actions']) ? '' : 'disabled' ?>
						/>
					</div>

				</div>
				
				<hr class="divider">

				<div class="row">
					<div class="col-sm-3">
						<div class="form-check">
							<input 
							class="form-check-input" 
							type="checkbox" 
							id="checkMovGeneral" 
							value="option1" 
							checked
							<?= array_search('selectMovement',$permissions['actions']) ? '' : 'disabled' ?>
							/>
							<label class="form-check-label" for="checkMovGeneral">Todas los movimientos</label>
						</div>
						
					</div>
					<div class="col-sm-1 border-right"></div>
					
					<div class="col-sm-8">
						<div class="form-check">
							<select id="mselectMovGeneral" multiple="multiple" class="form-control form-control-sm" disabled>								
								<option value="1" selected>VENTA</option>	
								<option value="2" selected>ENTRADA</option>
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
							id="checkStoreGeneral" 
							value="option1" 
							checked
							<?= array_search('selectStore',$permissions['actions']) ? '' : 'disabled' ?>
							/>
							<label class="form-check-label" for="checkStoreGeneral">Todas las sucursales</label>
						</div>
						
					</div>
					<div class="col-sm-1 border-right"></div>
					
					<div class="col-sm-8">
						<div class="form-check">
							<select id="mselectStoreGeneral" multiple="multiple" class="form-control form-control-sm" disabled>
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
							id="checkPaymentType" 
							value="option3" 
							checked
							<?= array_search('selectPayment',$permissions['actions']) ? '' : 'disabled' ?>
							/>
							<label class="form-check-label" for="checkPaymentType">Todas formas de pago</label>
						</div>
					</div>
					<div class="col-sm-1 border-right"></div>
					<div class="col-sm-8">
						<div class="form-check form-check-inline">
							<input name = "checkPayment[]" class="form-check-input checkPaymentType" type="checkbox" id="checkPaymentCash" value="cash EFECTIVO" checked disabled>
							<label class="form-check-label" for="checkPaymentCash">Efectivo</label>
						</div>
						<div class="form-check form-check-inline">
							<input name = "checkPayment[]" class="form-check-input checkPaymentType" type="checkbox" id="checkPaymentCard" value="card TARJETA" checked disabled>
							<label class="form-check-label" for="checkPaymentCard">Tarjeta</label>
						</div>
						<!-- <div class="form-check form-check-inline">
							<input name = "checkPayment[]" class="form-check-input checkPaymentType" type="checkbox" id="checkPaymentTransfer" value="transfer TRANSFERENCIA" checked disabled>
							<label class="form-check-label" for="checkPaymentTransfer">Transferencia</label>
						</div> -->
					</div>
				</div>

				<hr class="divider">

				<div class="row">	
					<div class="col-sm-4">
						<div class="input-group mb-3">
							<div class="input-group-prepend">
								<label class="input-group-text" for="mselectStatusGeneral">Estado</label>
							</div>
							<select 
							id="mselectStatusGeneral" 
							multiple="multiple" 
							class="custom-select"
							<?= array_search('selectStatus',$permissions['actions']) ? '' : 'disabled' ?>
							>
								<option value="1" selected>Vigente</option>	
								<option value="2">Cancelado</option>			
							</select>	
						</div>
					</div>
				</div>

				<hr class="divider">

				<div class="row">	
					<div class="col-sm-4">
						<div class="input-group mb-3">
							<div class="input-group-prepend">
								<label class="input-group-text" for="mselectStatusGeneral">Ordenar por: </label>
							</div>
							<select id="mselectOrderGeneral" class="custom-select">
								<option value="datePay" selected>Fecha</option>	
								<option value="storeID">Sucursal</option>	
								<option value="movementID">Movimiento</option>
								<option value="total">Total</option>
							</select>	
						</div>
					</div>
					<div class="col-sm-4">
						<div class="input-group mb-3">
							<select id="mselectByGeneral" class="custom-select">
								<option value="asc" selected>Asendente</option>	
								<option value="desc">Desendente</option>

							</select>	
						</div>
					</div>
				</div>
			</div>
			<!-- / END TAB PANE GENERAL -->

			<!-- TAB PANEL PRODCTS -->		
			<div class="tab-pane fade" id="product" role="tabpanel" aria-labelledby="product-tab">
				<div class="row">

					<div class="col-sm-4 input-group">
						<div class="input-group-prepend">
							<label class="input-group-text" for="startDateProduct">Fecha Inicial</label>
						</div>
						<input 
							type="text" 
							class="form-control datetimepicker-input" 
							id="startDateProduct" 
							data-toggle="datetimepicker" 
							data-target="#changeDateDashboard" 
							value="<?=$startDate?>"
							<?= array_search('selectDate',$permissions['actions']) ? '' : 'disabled' ?>
						/>
					</div>

					<div class="col-sm-4 input-group">
						<div class="input-group-prepend">
							<label class="input-group-text" for="finishDateProduct">Fecha final</label>
						</div>
						<input 
							type="text" 
							class="form-control datetimepicker-input" 
							id="finishDateProduct" 
							data-toggle="datetimepicker" 
							data-target="#changeDateDashboard" 
							value="<?=$finishDate?>"
							<?= array_search('selectDate',$permissions['actions']) ? '' : 'disabled' ?>
						/>
					</div>

					<div class="col-sm-4"></div>
					
					<div class="col-sm-4 input-group">
						<div class="input-group-prepend">
							<label class="input-group-text" for="startTimeProduct">Hora inicial</label>
						</div>
						<input 
							type="text" 
							class="form-control datetimepicker-input" 
							id="startTimeProduct" 
							data-toggle="datetimepicker" 
							data-target="#changeDateDashboard" 
							value="00:00"
							<?= array_search('selectTime',$permissions['actions']) ? '' : 'disabled' ?>
						/>
					</div>

					<div class="col-sm-4 input-group">
						<div class="input-group-prepend">
							<label class="input-group-text" for="finishTimeProduct">Hora final</label>
						</div>
						<input 
							type="text" 
							class="form-control datetimepicker-input" 
							id="finishTimeProduct" 
							data-toggle="datetimepicker" 
							data-target="#changeDateDashboard" 
							value="23:59"
							<?= array_search('selectTime',$permissions['actions']) ? '' : 'disabled' ?>
						/>
					</div>

				</div>
				
				<hr class="divider">

				<div class="row">
					<div class="col-sm-3">
						<div class="form-check">
							<input 
							class="form-check-input" 
							type="checkbox" 
							id="checkCategoryProduct" 
							value="option12" 
							checked
							
							/>
							<label class="form-check-label" for="checkCategoryProduct">Todas las categorías</label>
						</div>
						
					</div>
					<div class="col-sm-1 border-right"></div>
					
					<div class="col-sm-8">
						<div class="form-check">
							<select id="mselectCategorytProduct" multiple="multiple" class="form-control form-control-sm" disabled>				

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
							id="checkStoreProduct" 
							value="option1" 
							checked
							<?= array_search('selectStore',$permissions['actions']) ? '' : 'disabled' ?>
							/>
							<label class="form-check-label" for="checkStoreProduct">Todas las sucursales</label>
						</div>
						
					</div>
					<div class="col-sm-1 border-right"></div>
					
					<div class="col-sm-8">
						<div class="form-check">
							<select id="mselectStoreProduct" multiple="multiple" class="form-control form-control-sm" disabled>
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
							id="checkPaymentTypeProduct" 
							value="option3" 
							checked
							/>
							<label class="form-check-label" for="checkPaymentTypeProduct">Selecciona que ver</label>
						</div>
					</div>
					<div class="col-sm-1 border-right"></div>
					<div class="col-sm-8">
						<div class="form-check form-check-inline">
							<input name = "checkSelectProduct[]" class="form-check-input checkPaymentTypeProduct" type="checkbox" id="checkProductKeyPr" value="productKey CLAVE" checked disabled>
							<label class="form-check-label" for="checkProductKeyPr">Clave</label>
						</div>
						<div class="form-check form-check-inline">
							<input name = "checkSelectProduct[]" class="form-check-input checkPaymentTypeProduct" type="checkbox" id="checkPackageNamePr" value="packageName PAQUETE" checked disabled>
							<label class="form-check-label" for="checkPackageNamePr">Paquete</label>
						</div>
						<div class="form-check form-check-inline">
							<input name = "checkSelectProduct[]" class="form-check-input checkPaymentTypeProduct" type="checkbox" id="checkPackageCategoryNamePr" value="packageCategoryName 'PAQUETE CATEGORIA'" checked disabled>
							<label class="form-check-label" for="checkPackageCategoryNamePr">Categoría Paquete</label>
						</div>
					</div>
				</div>

				<hr class="divider">

				<div class="row">	
					<div class="col-sm-4">
						<div class="input-group mb-3">
							<div class="input-group-prepend">
								<label class="input-group-text" for="mselectStatusProduct">Estado</label>
							</div>
							<select 
							id="mselectStatusProduct" 
							multiple="multiple" 
							class="custom-select"
							<?= array_search('selectStatus',$permissions['actions']) ? '' : 'disabled' ?>
							>
								<option value="1" selected>Vigente</option>	
								<option value="-1">Cancelado</option>			
							</select>	
						</div>
					</div>
				</div>

				<hr class="divider">

				<div class="row">	
					<div class="col-sm-4">
						<div class="input-group mb-3">
							<div class="input-group-prepend">
								<label class="input-group-text" for="mselectStatusProduct">Ordenar por: </label>
							</div>
							<select id="mselectOrderProduct" class="custom-select">
								<option value="saleDate" selected>Fecha</option>	
								<option value="storeID">Sucursal</option>	
								<option value="productCategoryName">Categoría</option>
								<option value="productName">Artículo</option>
							</select>	
						</div>
					</div>
					<div class="col-sm-4">
						<div class="input-group mb-3">
							<select id="mselectByProduct" class="custom-select">
								<option value="asc" selected>Asendente</option>	
								<option value="desc">Desendente</option>

							</select>	
						</div>
					</div>
				</div>
			</div>
			<!-- / END TAB PANE ITEMS -->


			<div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">...</div>
		</div>
		<!-- / END TAB CONTENT -->
	</div>

</section>