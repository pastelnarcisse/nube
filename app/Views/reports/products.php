<!-- AdminLTE dashboard demo (This is only for demo purposes) -->

<script src="<?=base_url('public/assets/js/reports-products.js?v=1.00')?>"></script>

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
					<?php if (array_search('preview',$permissions['actions'])): ?>
					<button id="btn-pre" type="button" class="btn btn-secondary btn-action" value="preview">
						<div class="icon">
							<i class="ion ion-flash"></i>
						</div>
						<small>Previa</small>
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
					
					<!-- <button id="btn-imp" type="button" class="btn btn-secondary">
						<div class="icon">
							<i class="ion ion-printer"></i>
						</div>
						<small>Imp</small>
					</button> -->
				</div>

			</div>

		</div>

		<!-- TABS DE LOS MENUS -->
		<ul class="nav nav-tabs" id="myTab" role="tablist">
			<?php if (array_search('reportProductStock',$permissions['actions'])): ?>
			<li class="nav-item">
				<a class="nav-link active" id="stock-tab" data-toggle="tab" href="#stock" role="tab" aria-controls="stock" aria-selected="true">Inventario</a>
			</li>
			<?php endif ?>
			
		</ul>
		<!-- FIN TAB -->

		<hr class="divider">

		<!-- TAB CONTENT -->
		<div class="tab-content" id="stock-tab-content">
		
			<!-- TAB PANE stock -->		
			<div class="tab-pane fade show active" id="stock" role="tabpanel" aria-labelledby="stock-tab">
								
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

				<div class="row">	
					<div class="col-sm-4">
						<div class="input-group mb-3">
							<div class="input-group-prepend">
								<label class="input-group-text" for="mselectStatusStock">Ordenar por: </label>
							</div>
							<select id="mselectOrderStock" class="custom-select">
								<option value="cat_id_local" selected>Categoria</option>	
								<option value="suc_id">Sucursal</option>	
								<option value="existencia">Existencia</option>
							</select>	
						</div>
					</div>
					<div class="col-sm-4">
						<div class="input-group mb-3">
							<select id="mselectByStock" class="custom-select">
								<option value="asc" selected>Asendente</option>	
								<option value="desc">Desendente</option>

							</select>	
						</div>
					</div>
				</div>
			</div>
			<!-- / END TAB PANE Stock -->


		</div>
		<!-- / END TAB CONTENT -->
	</div>

</section>