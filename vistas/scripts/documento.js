var tabla;

//Funcion que se ejecuta al inicio
function init()
{
    
	console.log(Date.now());
	//console.log("Paso por funcion init()");
	//mostrarform(false);
	mostrarform('I');
    listar();
	
    $("#formulario").on("submit",function(e)
    {
        //console.log("Paso por funcion init() dentro de evento submit");
		guardaryeditar(e);
    })

    //Cargamos los items al select sector
    
	$.post(
        "../ajax/documento.php?op=selectSector",
        function(data)
        {        
            $("#idsector").html(data);
            $("#idsector").selectpicker('refresh');
        }
    );
	
	$.post(
        "../ajax/documento.php?op=selectTipoDocumento",
        function(data)
        {        
            $("#idtipo_documento").html(data);
            $("#idtipo_documento").selectpicker('refresh');
        }
    );

			
    $("#imagenmuestra").hide();
}

//funcion limpiar
function limpiar()
{
    $("#codigo").val("");
    $("#nombre").val("");
    $("#descripcion").val("");
	$("#archivo").val("");
    $("#iddocumento").val("");

}

function mostrarform(flag)
{
    limpiar();
	//console.log("Valor de flag -> "+flag);
	switch(flag) {
		//form principal
		case 'A':
			console.log("Adentro del switch con valor "+flag);
			$("#listadoregistros").hide();
			$("#formularioregistros").show();
			$("#archivoba").show();
            $("#archivoab").hide();
			$("#btnGuardar").prop("disabled",false);
			$("#btnagregar").hide();
		break;
		//desde js mismo, funcion init()
		case 'I':
			console.log("Adentro del switch con valor "+flag);
			$("#archivoba").hide();
			$("#listadoregistros").show();
			$("#formularioregistros").hide();
			$("#btnagregar").show();
			$("#btnver").hide();
			$("#btneliminar").hide();
		break;
		//desde js mismo, funcion mostrar()
		case 'M':
			console.log("Adentro del switch con valor "+flag);
			//$("#archivoba").hide();
			$("#listadoregistros").hide();
			$("#formularioregistros").show();
			$("#btnagregar").hide();
			$("#btnGuardar").prop("disabled",false);
            $("#archivoab").show();
			//$("#btnver").hide();
			//$("#btneliminar").hide();
		break;
		//desde js mismo, funcion eliminar()
		case 'E':
			console.log("Adentro del switch con valor "+flag);
			$("#archivoba").show();
			$("#listadoregistros").hide();
			$("#formularioregistros").show();
			$("#btnagregar").hide();
			//$("#btnver").hide();
			//$("#btneliminar").hide();
		break;
		//desde js mismo, funcion guardar y editar()
		case 'G':
			console.log("Adentro del switch con valor "+flag);
			//$("#archivoba").show();
			$("#listadoregistros").show();
			$("#formularioregistros").hide();
			$("#btnagregar").show();
			//$("#btnGuardar").prop("disabled",false);
			$("#btnver").hide();
			$("#btneliminar").hide();
		break;
	}
		
}


//funcion mostrar formulario
function mostrarform1(flag)
{
    limpiar();

    if(flag)
    {
        $("#listadoregistros").hide();
        $("#formularioregistros").show();
		$("#archivoba").show();
        $("#btnGuardar").prop("disabled",false);
        $("#btnagregar").hide();
	}
    else
    {
        $("#archivoba").hide();
		$("#listadoregistros").show();
        $("#formularioregistros").hide();
        $("#btnagregar").show();
		$("#btnver").hide();
		$("#btneliminar").hide();
    }
}

function impresionFarmacia(){
    arrayUsuarios = [2,6,8,10,12];
    // console.log(sessionUsuario);
    if (arrayUsuarios.includes(parseInt(sessionUsuario)) === true){

        $("#btnver").show();
    }else{

        $("#btnver").hide();
    }
}

//Funcion cancelarform
function cancelarform()
{
    limpiar();
    mostrarform('I');
}

//Funcion listar
function listar()
{
    tabla = $('#tblistado')
        .dataTable(
            {
                "aProcessing":true, //Activamos el procesamiento del datatables
                "aServerSide":true, //Paginacion y filtrado realizados por el servidor
                dom: "Bfrtip", //Definimos los elementos del control de tabla
                buttons:[
                    'copyHtml5',
                    'excelHtml5',
                    'csvHtml5',
                    'pdf'
                ],
                "ajax":{
                    url: '../ajax/documento.php?op=listar',
                    type: "get",
                    dataType:"json",
                    error: function(e) {
                        console.log(e.responseText);
                    }
                },
                "bDestroy": true,
                "iDisplayLength": 5, //Paginacion
                "order": [[0,"desc"]] //Ordenar (Columna, orden)
            
            })
        .DataTable();
}

//funcion para guardar o editar
function guardaryeditar(e)
{
    e.preventDefault(); //No se activará la acción predeterminada del evento
	$("#btnGuardar").prop("disabled",true);
    let formData = new FormData($("#formulario")[0]);
    
	for (var pair of formData.entries()) {
		console.log(pair[0]+ ', ' + pair[1]); 
	}

    $.ajax({
        url: "../ajax/documento.php?op=guardaryeditar",
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,
        success: function(datos)
        {
            //console.log("succes");
            bootbox.alert(datos);
            mostrarform('G');
            tabla.ajax.reload();
			$("#formulario").trigger("reset");
        },
        error: function(error)
        {
			mostrarform('G');
			tabla.ajax.reload();
			$("#formulario").trigger("reset");
            console.log("error: " + error);
        } 
    });

    limpiar();
}

function agregarDetalle()
{
	var h = new Date().toTimeString();
	h = h.substring(0,5)
	console.log(h)
	if(contador != undefined && contador < 0){
		if(parseInt(tabla.rows().count()) === 0){
			contador = 0
		}else{
			contador = tabla.rows().count()

		}
		console.log(contador)
	}else{
	}

	console.log(contador)
	$("num_item").removeAttr("name");
		for(let i=0; i < (contador+1); i++){
			console.log(i)
			$("#idtanqueorigen"+i).removeAttr("name");
			$("#partidaorigen"+i).removeAttr("name");
		}

	console.log(contador);
    var precio_compra = 1;
    var precio_venta = 3;
	var idubi_destino = 1;
	var codigo_destino = "EXT";
	if(contador != undefined && contador >= 0 && cont < contador){
		cont = parseInt(contador)+1;
		

	}
	else if(contador === undefined){
		cont = parseInt(contador)+1;
	}else{
		cont = parseInt(contador)+1;
	}
	detalles = (cont-1)
	// if(pcpi != "")
    // {
		//var subtotal = cantidad * precio_compra;
        let fila = '<tr class="filas" id="fila'+cont+'"> ' +
		'<td>'+
		'<button type="button" class="btn btn-danger" onclick="eliminarDetalle('+cont+')"><i class="fa fa-close"></i></button>'+
		// '<a data-toggle="modal" href="#modalCert" ><button type="button" class="btn btn-warning" id="btnVerCert" onclick="listarCertificados(\''+cont+'\')"><i class="fa fa-eye"></i></button></a>'+
		// '<a data-toggle="modal" href="#formCertificados" ><button type="button"  style="display:none" class="btn btn-primary" id="btnAgregarCert'+cont+'" onclick="setFormCertificados(\'-\','+0+','+0+','+0+','+0+',\''+cont+'\','+sessionUsuario+',\''+sessionNombre+'\')"><i class="fa fa-plus"></i></button></a>'+
		'</td>'+
		'<td>'+(cont)+'</td>'+ 
					  '<td>' +
					  '<input type="hidden" id="O2'+cont+'" name="O2[]" value="0">'+0+
                       '</td>'+ 
					   '<td>' +
					   '<input type="hidden" id="CO'+cont+'" name="CO[]" value="0">'+0+
                       '</td>'+ 
					   '</tr>';
					 			   
        cont++;
        detalles++;
        $("#tbllistado").append(fila);
		
		
    // }
    // else
    // {
	// 	alert("Error al ingresar el detalle, revisar los datos del articulo");
    // }
	
	evaluar();
	
	//consultarDetalle();
	$.post("../ajax/tanques.php?op=selectTanqueOrigenPcpi", function(r){
		$(".idTanqueOrigen"+(cont-1)+".selectpicker").html(r);
		$('.idTanqueOrigen'+(cont-1)+'.selectpicker').selectpicker('refresh',{
			dropupAuto: false
		});
		$(".idTanqueOrigen"+(cont-1)).css('width','100%')
	});	
	$.post("../ajax/tanques.php?op=selectTanqueDestinoPcpi", function(r){
		$(".idTanqueDestino"+(cont-1)+".selectpicker").html(r);
		$('.idTanqueDestino'+(cont-1)+'.selectpicker').selectpicker('refresh',{
			dropupAuto: false
		});
		$(".idTanqueDestino"+(cont-1)).css('width','100%')
	});	
	
	// var fila = '';
	console.log(fila)

}

function mostrar(iddocumento)
{
    $.post(
        "../ajax/documento.php?op=mostrar",
        {iddocumento:iddocumento},
        function(data,status)
        {
            data = JSON.parse(data);
            mostrarform('M');

            $('#idsector').val(data.idsector);
            $('#idsector').selectpicker('refresh');
            
            $('#idtipo_documento').val(data.idtipo_documento);
            $('#idtipo_documento').selectpicker('refresh');
			
			//$("#codigo").val(data.codigo);
            $('#nombre').val(data.nombre);
            $('#descripcion').val(data.descripcion);
			$('#iddocumento').val(data.iddocumento);
            $('#num_revision').val(data.num_revision);
			$('#vigencia').val(data.vigencia);
			
			//console.log(data.idsector + ' |-| ' + data.idtipo_documento + ' |-| ' + data.nombre + ' |-| ' + data.descripcion + ' |-| ' + data.iddocumento);
			
            //generarbarcode();

        }
    );
	
	$.post("../ajax/documento.php?op=listarDetalle&id="+iddocumento,function(r){
	        $("#archivoab").empty();
	        $("#archivoab").html(r);
			iidt = $("#lblarchivosubido").text().length;
			sidt = $("#rutaarchivo").val();
			
			// $("#ver").attr("href", '../reportes/exDocumento.php?enlace='+sidt);
			$("#ver").attr("href", sidt);
			$("#btneliminar").attr("onclick", 'eliminar('+iddocumento+')');
			if(iidt == '15'){
				$("#btnver").show();
				$("#btneliminar").show();
				$("#archivoba").hide();
				console.log("Pasa por true...");
			}else{
				console.log("Pasa por false...");
				$("#btnver").hide();
				$("#btneliminar").hide();
				$("#archivoba").show();
			}
			//mostrarform(true,iidt);
	});
    setTimeout(()=>{
        let sector = $("#idsector").val();
    console.log(sector)
	if(sector === '5' || sector === '9'){
        impresionFarmacia();
    }
    },500)
}



function eliminar(iddocumento)
{
    bootbox.confirm("¿Estas seguro de eliminar el Documento?",function(result){
        if(result)
        {
			$.post(
				"../ajax/documento.php?op=eliminar",
				{iddocumento:iddocumento},
				function(data,status)
				{
					data = JSON.parse(data);
					mostrarform('E');
					$('#idsector').val(data.idsector);
					$('#idsector').selectpicker('refresh');
					$('#idtipo_documento').val(data.idtipo_documento);
					$('#idtipo_documento').selectpicker('refresh');
					$('#nombre').val(data.nombre);
					$('#descripcion').val(data.descripcion);
					$('#iddocumento').val(data.iddocumento);
					$('#num_revision').val(data.num_revision);
					$('#vigencia').val(data.vigencia);
				}
			);
			$.post("../ajax/documento.php?op=listarDetalle&id="+iddocumento,function(r){
					$("#archivoab").html(r);
					iidt = $("#lblarchivo").text().length;
					sidt = $("#rutaarchivo").val();
					$("#ver").attr("href", sidt);
					$("#btneliminar").attr("onclick", 'eliminar('+iddocumento+')');
					if(iidt == '16'){
						$("#btnver").hide();
						$("#btneliminar").hide();
						$("#archivoab").hide();
						$("#archivoba").show();
						bootbox.alert('Archivo eliminado')
					}else{
						$("#btnver").show();
						$("#btneliminar").show();
						$("#archivoab").show();
						$("#archivoba").hide();
						bootbox.alert('Archivo no se pudo eliminar')
					}
			});
		}
	});
	
}


//funcion para desactivar documentos
function desactivar(iddocumento)
{
    bootbox.confirm("¿Estas seguro de desactivar el Documento?",function(result){
        if(result)
        {
            $.post(
                "../ajax/documento.php?op=desactivar",
                {iddocumento:iddocumento},
                function(e)
                {
                    bootbox.alert(e);
                    tabla.ajax.reload();
        
                }
            );
        }
    });
}

function activar(iddocumento)
{
    bootbox.confirm("¿Estas seguro de activar el Documento?",function(result){
        if(result)
        {
            $.post(
                "../ajax/documento.php?op=activar",
                {iddocumento:iddocumento},
                function(e)
                {
                    bootbox.alert(e);
                    tabla.ajax.reload();
        
                }
            );
        }
    });
}


init();