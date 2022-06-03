/*
* Author: Erick Aguirre
* Date: 2021-11-12
* Description:
*      reporte de ventas
**/

/* global moment:false, Chart:false, Sparkline:false */

$(function () {
	'use strict'

/***********************************************/
// OPCIONS
/***********************************************/

	// Selección de categorías Stock
	$('#mselectCatStock').multipleSelect({
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
	$('#mselectStoreStock').multipleSelect({
	locale : 'es-MX',
	sellectAll : true
	});

	

/***********************************************/
// EVENTS
/***********************************************/

	// Click al check de categorias Stock
	$( '#checkCatStock' ).on( 'click', function() {
		if( $(this).is(':checked') ){
			// Hacer algo si el checkbox ha sido seleccionado
			$('#mselectCatStock').multipleSelect('disable');
			$('#mselectCatStock').multipleSelect('checkAll');
		} else {
			// Hacer algo si el checkbox ha sido deseleccionado
			$('#mselectCatStock').multipleSelect('enable');
		}
	});

	// Click al check de tiendas Stock
	$( '#checkStoreStock' ).on( 'click', function() {
		if( $(this).is(':checked') ){
			// Hacer algo si el checkbox ha sido seleccionado
			$('#mselectStoreStock').multipleSelect('disable');
			$('#mselectStoreStock').multipleSelect('checkAll');
		} else {
			// Hacer algo si el checkbox ha sido deseleccionado
			$('#mselectStoreStock').multipleSelect('enable');
		}
	});


	


	// Click al boton crear EXCEL
	$( '.btn-action' ).on( 'click', function() {

		var tab = $( ".nav-link.active" ).attr('id');
		var action = $(this).val();

		if (tab == 'stock-tab') {
			actionStock(action);
		}

		if (tab == 'product-tab') {
			actionProducts(action);
		}

	});

	


/***********************************************/
// FUNTION EVENTS
/***********************************************/
	
	/**
	 * Envio de formulario y creación de reporte de tipo Stock, de acuerdo a la acción
	 * 
	 * @param string doc Tipo de documento o accón que se va devolver
	 * 
	 * */	
	function actionStock(doc = "excel"){

		// OBTENER DATOS

		var categorys 	= $('#mselectCatStock').multipleSelect('getSelects');
		var suc_ids 		= $('#mselectStoreStock').multipleSelect('getSelects');
		var order				= $('#mselectOrderStock').val();
		var by 					= $('#mselectByStock').val();

		var setData = {
			'doc' 				: doc,
			'categorys'		: categorys,
			'suc_ids' 		: suc_ids,		
			'order' 			: order,
			'by' 					: by
		};

		callNube('reports/getProductsStock',setData).then(function(data, status, jqXHR){
			try{
				console.log(data);
				var $a = $("<a>");
				$a.attr("href",data.file);
				$("body").append($a);

				if (doc == 'preview') {
					var win = window.open('https://nube.narcisse.mx/public/report/pdf/inventarioArticulo.pdf?v=1.'+Math.floor(Math.random() * 100000),'_blank');
				}
				if (doc == 'excel') {
					$a.attr("download","inventarioArticulo.xlsx");
				}
				if (doc == 'pdf') {
					$a.attr("download","inventarioArticulo.pdf");
				}
				if (doc == 'email') {

				}

				
				$a[0].click();
				$a.remove();
			}catch(err){
				console.log(err);
			}
		}).done(function(data){
			// console.log(data);
		}).fail(function(jqXHR, textStatus, errorThrown){
			//console.log(jqXHR.responseJSON.messages);
			console.log(jqXHR);
		});

	}

	/**
	 * Envio de formulario y creación de reporte de tipo artículos, de acuerdo a la acción
	 * 
	 * @param string doc Tipo de documento o accón que se va devolver
	 * 
	 * */	
	function actionProducts(doc = "excel"){

		// OBTENER DATOS

		var startDate	= $('#startDateProduct').val();
		var finishDate	= $('#finishDateProduct').val();
		var startTime	= $('#startTimeProduct').val();
		var finishTime	= $('#finishTimeProduct').val();
		var movement	= $('#mselectMovProduct').multipleSelect('getSelects');
		var suc_ids		= $('#mselectStoreProduct').multipleSelect('getSelects');
		var selectProduct		= [];
		var status		= $('#mselectStatusProduct').multipleSelect('getSelects');
		var order		= $('#mselectOrderProduct').val();
		var by			= $('#mselectByProduct').val();
		var categorys	= $('#mselectCategorytProduct').multipleSelect('getSelects');

		$("input[name='checkSelectProduct[]']:checked").each(function(){selectProduct.push($(this).val());});

		var setData = {
			'doc'			: doc,
			'startDate'		: startDate,
			'finishDate'	: finishDate,
			'startTime'		: startTime,
			'finishTime'	: finishTime,
			'categorys'		: categorys,
			'movement'		: movement,
			'suc_ids'		: suc_ids,
			'selectProduct'		: selectProduct,
			'status'		: status,
			'order'			: order,
			'by'			: by
		};

		console.log(setData);

		callNube('reports/getSalesProducts',setData).then(function(data, status, jqXHR){
			try{
				console.log(data);
				var $a = $("<a>");
				$a.attr("href",data.file);
				$("body").append($a);

				if (doc == 'preview') {
					var win = window.open('https://nube.narcisse.mx/public/report/pdf/articuloventa.pdf?v=1.'+Math.floor(Math.random() * 100000),'_blank');
				}
				if (doc == 'excel') {
					$a.attr("download","articuloventa.xlsx");
				}
				if (doc == 'pdf') {
					$a.attr("download","articuloventa.pdf");
				}
				if (doc == 'email') {

				}

				
				$a[0].click();
				$a.remove();
			}catch(err){
				console.log(err);
			}
		// }).done(function(data){
		// 	// console.log(data);
		}).fail(function(jqXHR, textStatus, errorThrown){
			console.log(jqXHR);
		});

	}


})
