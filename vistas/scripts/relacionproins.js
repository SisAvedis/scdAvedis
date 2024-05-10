var tabla;
var idsector = 1;

//Función que se ejecuta al inicio
function init(){
	mostrarform(false,false);
	listar();

	$("#formulario").on("submit",function(e)
	{
		guardaryeditar(e);	
	});
	
	//Cargamos los items al select sector
	$.post("../ajax/relacionproins.php?op=selectSector", function(r){
		$("#idsector").html(r);
		$('#idsector').val(idsector);
	    $("#idsector").selectpicker("refresh");
	});
	
	//Cargamos los items al select documento
	$.post(
		"../ajax/relacionproins.php?op=selectDocumento", 
		{idsector:idsector},
		function (r) 
		{
			$("#iddocumento").html(r);
			$("#iddocumento").selectpicker("refresh");
		}
	)
	//console.log("Valor de idsector -> "+idsector);
}

//Función limpiar
function limpiar()
{
	$("#iddocumento").val("");
	$("#observacion").val("");
	$("#idsector").val("");
	$(".filas").remove();
}

//Función mostrar formulario
function mostrarform(flag1,flag2)
{
	
	limpiar();
	if(flag1 && flag2)
	{
		$("#listadoregistros").hide();
		$("#formularioregistros").show();
		//$("#listadocabecera").hide();
		//$("#btnGuardar").prop("disabled",false);
		$("#btnagregar").hide();
		//listarProcInst();

		$("#btnguardar").hide();
		$("#btnCancelar").show();
		$("#btnAgregarArt").show();
		detalles=0;
	}

	else if(!flag1 && flag2)
    {
        $("#listadoregistros").hide();
		//$("#listadocabecera").show();
        $("#formularioregistros").show();
        $("#btnagregar").hide();
		
    }

	else if(!flag1 && !flag2)
    {
        $("#listadoregistros").show();
		//$("#listadocabecera").hide();
        $("#formularioregistros").hide();
        $("#btnagregar").show();
    }
}

//Función cancelarform
function cancelarform()
{
	limpiar();
	mostrarform(false,false);
}

//Función Listar
function listar()
{
	tabla=$('#tbllistado').dataTable(
	{
		"aProcessing": true,//Activamos el procesamiento del datatables
	    "aServerSide": true,//Paginación y filtrado realizados por el servidor
	    dom: 'Bfrtip',//Definimos los elementos del control de tabla
	    buttons: [		          
		            'copyHtml5',
		            'excelHtml5',
		            'csvHtml5',
		            'pdf'
		        ],
		"ajax":
				{
					url: '../ajax/relacionproins.php?op=listar',
					type : "get",
					dataType : "json",						
					error: function(e){
						console.log(e.responseText);	
					}
				},
		"bDestroy": true,
		"iDisplayLength": 5,//Paginación
	    "order": [[ 0, "desc" ]]//Ordenar (columna,orden)
	}).DataTable();
}


//Función listarInstructivos
function listarInstructivos(idsector)
{
	tabla=$('#tblinstructivos').dataTable(
	{
		"aProcessing": true,//Activamos el procesamiento del datatables
	    "aServerSide": true,//Paginación y filtrado realizados por el servidor
	    dom: 'Bfrtip',//Definimos los elementos del control de tabla
	    buttons: [		          
		        ],
				"ajax":
				{
					url: '../ajax/relacionproins.php?op=listarInstructivos',
					data: {idsector:idsector},
					type : "get",
					dataType : "json",						
					error: function(e){
						console.log(e.responseText);	
					}
				},
		"bDestroy": true,
		"iDisplayLength": 5,//Paginación
	    "order": [[ 0, "desc" ]]//Ordenar (columna,orden)
	}).DataTable();
}

//Función listarInstructivosUno
function listarInstructivosUno(iddocsec)
{
	tabla=$('#tblinstructivos').dataTable(
	{
		"aProcessing": true,//Activamos el procesamiento del datatables
	    "aServerSide": true,//Paginación y filtrado realizados por el servidor
	    dom: 'Bfrtip',//Definimos los elementos del control de tabla
	    buttons: [		          
		        ],
				"ajax":
				{
					url: '../ajax/relacionproins.php?op=listarInstructivosUno',
					data: {iddocsec:iddocsec},
					type : "get",
					dataType : "json",						
					error: function(e){
						console.log(e.responseText);	
					}
				},
		"bDestroy": true,
		"iDisplayLength": 5,//Paginación
	    "order": [[ 0, "desc" ]]//Ordenar (columna,orden)
	}).DataTable();
}


//Función para guardar o editar

function guardaryeditar(e)
{
	e.preventDefault(); //No se activará la acción predeterminada del evento
	//$("#btnGuardar").prop("disabled",true);
	var formData = new FormData($("#formulario")[0]);
	
	// Display the key/value pairs
	for (var pair of formData.entries()) {
		console.log('Valor pair[0] -> '+pair[0]+' Valor pair[1] -> ' + pair[1]); 
	}
	
	$.ajax({
		url: "../ajax/relacionproins.php?op=guardaryeditar",
	    type: "POST",
	    data: formData,
	    contentType: false,
	    processData: false,

	    success: function(datos)
	    {                    
	          bootbox.alert(datos);	          
	          mostrarform(false,false);
	          listar();
	    }

	});
	limpiar();
}

function mostrar(idrelproins)
{
	$.post("../ajax/relacionproins.php?op=mostrar",{idrelproins : idrelproins}, function(data, status)
	{
		data = JSON.parse(data);		
		mostrarform(false,true);

		//$("#idcliente").val(data.idcliente);
		//$("#idcliente").selectpicker('refresh');
		//$("#tipo_comprobante").val(data.tipo_comprobante);
		//$("#tipo_comprobante").selectpicker('refresh');
		//$("#serie_comprobante").val(data.serie_comprobante);
		//$("#num_comprobante").val(data.num_comprobante);
		//$("#fecha_hora").val(data.fecha);
		//$("#impuesto").val(data.impuesto);
		$("#idsector").val(data.idsector);
		$("#idsector").selectpicker('refresh');
		$("#idrelproins").val(data.idrelproins);
		$("#idrelproins").selectpicker('refresh');
		
		
		//$("#iddocumento").val(data.iddocumento);
		$("#observacion").val(data.comentario);

		//Ocultar y mostrar los botones
		$("#btnGuardar").hide();
		$("#btnCancelar").show();
		$("#btnAgregarArt").hide();
 	});

 	$.post("../ajax/relacionproins.php?op=listarDetalle&id="+idrelproins,function(r){
	        $("#detalles").html(r);
	});	
}

//Función para anular registros
function anular(idrelproins)
{
	bootbox.confirm("¿Está seguro de anular la relación?", function(result){
		if(result)
        {
        	$.post("../ajax/relacionproins.php?op=anular", {idrelproins : idrelproins}, function(e){
        		bootbox.alert(e);
	            tabla.ajax.reload();
        	});	
        }
	})
}

//Declaración de variables necesarias para trabajar con las compras y
//sus detalles
var impuesto=18;
var cont=0;
var detalles=0;
//$("#guardar").hide();
$("#btnGuardar").hide();
$("#tipo_comprobante").change(marcarImpuesto);

function marcarImpuesto()
  {
  	var tipo_comprobante=$("#tipo_comprobante option:selected").text();
  	if (tipo_comprobante=='Factura')
    {
        $("#impuesto").val(impuesto); 
    }
    else
    {
        $("#impuesto").val("0"); 
    }
  }

function agregarDetalle(idinstructivo,nombre,descripcion,idsector,sector,codigo)
{
    
	if(idinstructivo != "")
    {
        //var subtotal = cantidad * precio_compra;
        var fila = '<tr class="filas" id="fila'+cont+'"> ' +
                      '<td>'+
                           '<button type="button" class="btn btn-danger" onclick="eliminarDetalle('+cont+')">X</button>'+
                       '</td>'+
					  '<td>' +
                          '<input type="hidden" name="codigo[]" value="'+codigo+'">'+
                           codigo +
                       '</td>'+
                      '<td>' +
                          '<input type="hidden" name="idinstructivo[]" value="'+idinstructivo+'">'+
                           nombre +
                       '</td>'+
					  '<td>' +
                          '<input type="hidden" name="descripcion[]" value="'+descripcion+'">'+
						  descripcion +
                       '</td>'+
					  '<td>' +
                          '<input type="hidden" name="idsector[]" value="'+idsector+'">'+
						  sector +
                       '</td>'+	
                   '</tr>';

        cont++;
        detalles++;
        $("#detalles").append(fila);
        //modificarSubtotales(); 
    }
    else
    {
        alert("Error al ingresar el detalle, revisar los datos del instructivo");
    }
	
	evaluar();
	
	consultarDetalle();

}
function consultarDetalle()
{	
	var iddoc = document.getElementsByName("idinstructivo[]");
	var idsec = document.getElementsByName("idsector[]");
	var tamañoCant = iddoc.length;
	//console.log("Valor de tamañoCant -> "+tamañoCant);
	var iddocsec = '';
	for (var i=0;i<tamañoCant;i++){
	iddocsec+= iddoc[i].value+','+idsec[i].value+',';
	}
	//console.log("Valor de iddocsec -> "+iddocsec);
	if (tamañoCant !== 0){  
		listarInstructivosUno(iddocsec);
	}else{
		listarInstructivos(idsector);
	}

}

function modificarSubototales()
{
  	var cant = document.getElementsByName("cantidad[]");
    var prec = document.getElementsByName("precio_venta[]");
    var desc = document.getElementsByName("descuento[]");
    var sub = document.getElementsByName("subtotal");

    for (var i = 0; i <cant.length; i++) {
    	var inpC=cant[i];
    	var inpP=prec[i];
    	var inpD=desc[i];
    	var inpS=sub[i];

    	inpS.value=(inpC.value * inpP.value)-inpD.value;
    	document.getElementsByName("subtotal")[i].innerHTML = inpS.value;
    }
    calcularTotales();

}

function calcularTotales(){
  	var sub = document.getElementsByName("subtotal");
  	var total = 0.0;

  	for (var i = 0; i <sub.length; i++) {
		total += document.getElementsByName("subtotal")[i].value;
	}
	$("#total").html("S/. " + total);
    $("#total_venta").val(total);
    evaluar();
  }

function evaluar(){
  	if (detalles>0)
    {
      $("#btnGuardar").show();
    }
    else
    {
      $("#btnGuardar").hide(); 
      cont=0;
    }
}

function eliminarDetalle(indice){
  	$("#fila" + indice).remove();
  	calcularTotales();
  	detalles=detalles-1;
  	evaluar()
}

init();

$('#idsector').on('change', function() {
	idsector = $(this).val();
	iddocumento = 1;
	//Cargamos los items al select Documento
	$.post(
		"../ajax/relacionproins.php?op=selectDocumento", 
		{idsector:idsector},
		function (r) 
		{
			$("#iddocumento").html(r);
			$('#iddocumento').selectpicker('refresh');
		}
	)
		
});

$('#iddocumento').on('change', function() {
	iddocumento = $(this).val();
	
});