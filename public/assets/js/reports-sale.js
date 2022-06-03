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

	// Fecha inicial general
	$('#startDateGeneral').datetimepicker({
		format: 'YYYY-MM-DD',
		locale: 'es'
	});

	// Fecha final general
	$('#finishDateGeneral').datetimepicker({
		format: 'YYYY-MM-DD',
		locale: 'es'
	});

	// Hora inicial general
	$('#startTimeGeneral').datetimepicker({
		format: 'LT',
		locale: 'es'
	});

	// Hora final general
	$('#finishTimeGeneral').datetimepicker({
		format: 'LT',
		locale: 'es'
	});

	// Fecha inicial articulos
	$('#startDateProduct').datetimepicker({
		format: 'YYYY-MM-DD',
		locale: 'es'
	});

	// Fecha final articulos
	$('#finishDateProduct').datetimepicker({
		format: 'YYYY-MM-DD',
		locale: 'es'
	});

	// Hora inicial articulos
	$('#startTimeProduct').datetimepicker({
		format: 'LT',
		locale: 'es'
	});

	// Hora final articulos
	$('#finishTimeProduct').datetimepicker({
		format: 'LT',
		locale: 'es'
	});

	// Selección de movimiento general
	$('#mselectMovGeneral').multipleSelect({
		locale : 'es-MX',
		sellectAll : true
	});

	// Selección de tiendas general
	$('#mselectStoreGeneral').multipleSelect({
	locale : 'es-MX',
	sellectAll : true
	});

	// Selección de estado general
	$('#mselectStatusGeneral').multipleSelect({
	locale : 'es-MX',
	sellectAll : true
	});

	// Selección de movimiento artículos
	$('#mselectMovProduct').multipleSelect({
		locale : 'es-MX',
		sellectAll : true
	});

	// Selección de tiendas artículos
	$('#mselectStoreProduct').multipleSelect({
	locale : 'es-MX',
	sellectAll : true
	});

	// Selección de estado artículos
	$('#mselectStatusProduct').multipleSelect({
	locale : 'es-MX',
	sellectAll : true
	});


	// Selección de estado artículos
	$('#mselectCategorytProduct').multipleSelect({
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
// EVENTS
/***********************************************/

	// Click al check de movimiento general
	$( '#checkMovGeneral' ).on( 'click', function() {
		if( $(this).is(':checked') ){
			// Hacer algo si el checkbox ha sido seleccionado
			$('#mselectMovGeneral').multipleSelect('disable');
			$('#mselectMovGeneral').multipleSelect('checkAll');
		} else {
			// Hacer algo si el checkbox ha sido deseleccionado
			$('#mselectMovGeneral').multipleSelect('enable');
		}
	});

	// Click al check de tiendas general
	$( '#checkStoreGeneral' ).on( 'click', function() {
		if( $(this).is(':checked') ){
			// Hacer algo si el checkbox ha sido seleccionado
			$('#mselectStoreGeneral').multipleSelect('disable');
			$('#mselectStoreGeneral').multipleSelect('checkAll');
		} else {
			// Hacer algo si el checkbox ha sido deseleccionado
			$('#mselectStoreGeneral').multipleSelect('enable');
		}
	});

	// Click al check de tipo pago general
	$( '#checkPaymentType' ).on( 'click', function() {
		if( $(this).is(':checked') ){
			// Hacer algo si el checkbox ha sido seleccionado
			$(".checkPaymentType").prop( "disabled", true );
			$(".checkPaymentType").prop( "checked", true );
		} else {
			// Hacer algo si el checkbox ha sido deseleccionado
			$(".checkPaymentType").prop( "disabled", false );
		}
	});

	// Click al check de movimiento general
	$( '#checkMovProduct' ).on( 'click', function() {
		if( $(this).is(':checked') ){
			// Hacer algo si el checkbox ha sido seleccionado
			$('#mselectMovProduct').multipleSelect('disable');
			$('#mselectMovProduct').multipleSelect('checkAll');
		} else {
			// Hacer algo si el checkbox ha sido deseleccionado
			$('#mselectMovProduct').multipleSelect('enable');
		}
	});

	// Click al check de tiendas general
	$( '#checkStoreProduct' ).on( 'click', function() {
		if( $(this).is(':checked') ){
			// Hacer algo si el checkbox ha sido seleccionado
			$('#mselectStoreProduct').multipleSelect('disable');
			$('#mselectStoreProduct').multipleSelect('checkAll');
		} else {
			// Hacer algo si el checkbox ha sido deseleccionado
			$('#mselectStoreProduct').multipleSelect('enable');
		}
	});

	// Click al check de tipo pago general
	$( '#checkPaymentTypeProduct' ).on( 'click', function() {
		if( $(this).is(':checked') ){
			// Hacer algo si el checkbox ha sido seleccionado
			$(".checkPaymentTypeProduct").prop( "disabled", true );
			$(".checkPaymentTypeProduct").prop( "checked", true );
		} else {
			// Hacer algo si el checkbox ha sido deseleccionado
			$(".checkPaymentTypeProduct").prop( "disabled", false );
		}
	});

	// Click al check de categorias producto
	$( '#checkCategoryProduct' ).on( 'click', function() {
		if( $(this).is(':checked') ){
			// Hacer algo si el checkbox ha sido seleccionado
			$('#mselectCategorytProduct').multipleSelect('disable');
			$('#mselectCategorytProduct').multipleSelect('checkAll');
		} else {
			// Hacer algo si el checkbox ha sido deseleccionado
			$('#mselectCategorytProduct').multipleSelect('enable');
		}
	});

	


	// Click al boton crear EXCEL
	$( '.btn-action' ).on( 'click', function() {

		var tab = $( ".nav-link.active" ).attr('id');
		var action = $(this).val();

		if (tab == 'general-tab') {
			actionGeneral(action);
		}

		if (tab == 'product-tab') {
			actionProducts(action);
		}

	});

	


/***********************************************/
// FUNTION EVENTS
/***********************************************/
	
	/**
	 * Envio de formulario y creación de reporte de tipo general, de acuerdo a la acción
	 * 
	 * @param string doc Tipo de documento o accón que se va devolver
	 * 
	 * */	
	function actionGeneral(doc = "excel"){

		// OBTENER DATOS

		var startDate = $('#startDateGeneral').val();
		var finishDate = $('#finishDateGeneral').val();
		var startTime = $('#startTimeGeneral').val();
		var finishTime = $('#finishTimeGeneral').val();
		var movement = $('#mselectMovGeneral').multipleSelect('getSelects');
		var suc_ids = $('#mselectStoreGeneral').multipleSelect('getSelects');
		var payment = [];
		var status = $('#mselectStatusGeneral').multipleSelect('getSelects');
		var order = $('#mselectOrderGeneral').val();
		var by = $('#mselectByGeneral').val();

		$("input[name='checkPayment[]']:checked").each(function(){payment.push($(this).val());});

		var setData = {
			'doc' : doc,
			'startDate' : startDate,
			'finishDate' : finishDate,
			'startTime' : startTime,
			'finishTime' : finishTime,
			'movement' : movement,
			'suc_ids' : suc_ids,
			'payment' : payment,
			'status' : status,
			'order' : order,
			'by' : by
		};

		callNube('reports/getSalesGeneral',setData).then(function(data, status, jqXHR){
			try{
				console.log(data);
				var $a = $("<a>");
				$a.attr("href",data.file);
				$("body").append($a);

				if (doc == 'preview') {
					var win = window.open('https://nube.narcisse.mx/public/report/pdf/tipoventa.pdf?v=1.'+Math.floor(Math.random() * 100000),'_blank');
				}
				if (doc == 'excel') {
					$a.attr("download","tipoventa.xlsx");
				}
				if (doc == 'pdf') {
					$a.attr("download","tipoventa.pdf");
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
