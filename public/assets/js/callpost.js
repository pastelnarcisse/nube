/*
* Author: Erick Aguirre
* Date: 2021-11-13
* Description:
*      reporte de ventas
**/

/* global moment:false, Chart:false, Sparkline:false */



/***********************************************/
// FUNTION EVENTS
/***********************************************/
	
	
	// LLAMADAS AL SERVIDOR

	function callNube(ws,json, base_url = "https://nube.narcisse.mx"){

		return $.ajax({
			type : "POST",
			url : base_url+'/'+ws,
			restful:true,
			timeout:100000,
			dataType   : 'json',
			contentType: 'application/json',
			cache : false,
			data : JSON.stringify(json),
		});
	}

	function callLocal(ws,json){

		return $.ajax({
			type : "POST",
			url : "http://localhost/replicador/ws/"+ws,
			restful:true,
			timeout:100000,
			dataType   : 'json',
			//contentType: 'application/json',
			cache : false,
			data : JSON.stringify(json),
		});
	}

