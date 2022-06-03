<!-- Main content -->
<section class="content">

	<div class="container-fluid">

		<!-- Small boxes (Stat box) -->
		<div class="row">

			<?php foreach ($modules as $module): ?>
				
			<div class="col-lg-3 col-6">
				<!-- small box -->
				<div class="small-box bg-info">
					<div class="inner">
						<h3><?=$module->moduleName?></h3>

						<p><?=$module->moduleDescrption?></p>
					</div>
					<div class="icon">
						<i class="ion ion-bag"></i>
					</div>
					<a href="<?=base_url($module->moduleLink)?>" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
				</div>
			</div>	
			<!-- ./col -->
			<?php endforeach ?>

			
			
		</div>
		<!-- /.row -->

	</div>

</section>