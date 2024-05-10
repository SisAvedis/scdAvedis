var contador = -1;
var tabla;
var cont=0;
var detalles=0;
//Funcion que se ejecuta al inicio
function init()
{
    // listar();
    mostrarform(false);

    //Cargamos los items al select sector
    $.post("../ajax/documento.php?op=selectTipoDocumento", function (r) {
        $("#idtipodocumento").html(r);
        $("#idtipodocumento").selectpicker("refresh");
    });	

}

function limpiar()
{
	//$("#iddocumento").val("");
	//$("#observacion").val("");
	//$("#idsector").val("");
	$(".filas").remove();
    
}

function modificarEntregas(e){
    e.preventDefault(); //No se activará la acción predeterminada del evento
    var formData = new FormData($("#formularioEntregas")[0]);

    for (var pair of formData.entries()) {
		console.log(pair[0]+ ', ' + pair[1]); 
	}

    $.ajax({
		url: "../ajax/entrega.php?op=modificarEntregas",
	    type: "POST",
		async:false,
	    data: formData,
		//timeout: 10000,
	    contentType: false,
	    processData: false,
		success: function(datos)
	    {                    
	          alert(datos);
			  	          
	          tabla.ajax.reload();
	          
	    },
		error: function(error)
        {
           alert("error: " + error);
        } 

	});

}


function guardarEntregas(e){
    e.preventDefault(); //No se activará la acción predeterminada del evento
    var formData = new FormData($("#formularioEntregas")[0]);
    
    for (var pair of formData.entries()) {
		console.log(pair[0]+ ', ' + pair[1]); 
	}
    
    // console.log(cont-1)
    
    $.ajax({
        url: "../ajax/entrega.php?op=guardaryeditar",
        type: "POST",
        async:false,
        data: formData,
        //timeout: 10000,
        contentType: false,
        processData: false,
        success: function(datos)
            {                    
                alert(datos);
                $.post( `../ajax/documento.php?op=mostrarArchivo&iddocumento=${$("#iddocumento").val()}`, function (r) {
                    // let pdfUrl = r;
                     
                    imprimirPdf(r,cont-1);
                    setTimeout(()=>{
                     
                    tabla.ajax.reload();
                        mostrarform(true);
                    
                    },2000 )       
                    
                })
                
                
                
            
                },
            	error: function(error)
                {
                       alert("error: " + error);
                    } 

                });
                
            }
            
            function agregarDetalle()
            {
                
                let idimpresion = $("#idImpresion").val();
                console.log(parseInt(tabla.rows().count()))
                if(contador != undefined && contador < 0){
                    if(parseInt(tabla.rows().count()) === 0){
                        contador = cont
                        
                    }else{
                        contador = tabla.rows().count()
                        
                    }
                    console.log(contador)
                }else{
                    contador = cont-1
                }
                
                console.log(contador)
                for(let i=0; i < (contador+1); i++){
                    console.log(i)
                }

                console.log(contador);
                console.log(cont);
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
                    '<input  name="identrega[]" type="hidden" value="" >'+
                    '<input  name="idimpresion[]" type="hidden" value="'+idimpresion+'" >'+
                    '<button type="button" class="btn btn-danger" onclick="eliminarDetalle('+cont+')"><i class="fa fa-close"></i></button>'+
                    // '<a data-toggle="modal" href="#modalCert" ><button type="button" class="btn btn-warning" id="btnVerCert" onclick="listarCertificados(\''+cont+'\')"><i class="fa fa-eye"></i></button></a>'+
                    // '<a data-toggle="modal" href="#formCertificados" ><button type="button"  style="display:none" class="btn btn-primary" id="btnAgregarCert'+cont+'" onclick="setFormCertificados(\'-\','+0+','+0+','+0+','+0+',\''+cont+'\','+sessionUsuario+',\''+sessionNombre+'\')"><i class="fa fa-plus"></i></button></a>'+
                    '</td>'+
                    '<td>'+(cont)+'</td>'+ 
                    '<td id="selectOperario'+cont+'">' +

                    '<select title="Seleccione un operario" onchange="checkEventual('+cont+',false);" class="form-control selectpicker" name="idoperario[]" id="idoperario'+cont+'" data-dropup-auto="false" data-live-search="true"></select>'+
                    '</td>'+ 
                    '<td>' +
                    '<input  name="entregado[]" type="hidden" value="1" >'+
                    '<input type="checkbox" id="entregadoCheck" onclick="checkHandler('+cont+',$(this))" style="width:20px;height:20px;"  checked>'+
                    '</td>'+ 
                    '</tr>';
                    
        cont++;
        detalles++;
        $("#detalles").append(fila);
		
		
        // }
        // else
        // {
            // 	alert("Error al ingresar el detalle, revisar los datos del articulo");
            // }
            
            $.post("../ajax/consultas.php?op=selectPersona", function (r) {
                $("#idoperario"+(cont-1)).html(r);
                $("#idoperario"+(cont-1)).append('<option value="0">Eventual</option>');

                $("#idoperario"+(cont-1)).selectpicker("refresh");
            });
            
            evaluar();
            
	//consultarDetalle();
	
	// var fila = '';
	console.log(fila)
    
}

function imprimirPdf(pdfUrl,num){
    const printWindow = window.open(pdfUrl,'_blank'); // Open a new window or tab
    console.log(pdfUrl);
    console.log($('#idImpresion').val());
     
    if(pdfUrl != null){

        printWindow.onload = function () {
           // for (var i = 0; i < num; i++) {
              printWindow.print();
             // console.log(i) // Initiate the print operation in the new window
            //}
        }
        window.open(`../reportes/exCopiasControladas.php?id=${$('#idImpresion').val()}`,'_blank')
    
        // printWindow.close();
  }
  else{
        alert("Archivo PDF no encontrado, volver a subir archivo al documento")
  }

}
function evaluar(){
    //BOTONES SEGUN LA CANTIDAD DE DETALLES(FILAS)
	console.log(detalles);
    if(detalles>0 && detalles<20)
	{
        $("#btnAgregarDesc").show();  
        $("#btnGuardar").show();
    }else if(detalles === 20){
        $("#btnGuardar").show();
        $("#btnAgregarDesc").hide();  
    } else {
	  
	
	$("#btnModificar").hide();
	$("#btnAprobar").hide();
	$("#btnGuardar").hide(); 
	$("#btnAgregarDesc").hide();  
	cont=0;
	
}
}

function eliminarDetalle(indice){
	$("#fila" + indice).remove();
//	calcularTotales();
	cont=parseInt(cont)-1;
	evaluar();
}

function listar()
{
    mostrarform(false);
	
	let iddocumento = $("#iddocumento").val();
	// let num_revision = $("#num_revision").val();
    //funcion para mostrar el nombre del documento
    $.post("../ajax/documento.php?op=mostrar",{iddocumento:iddocumento}, function (data, status) {
    
        data = JSON.parse(data);
        console.log(data.nombre);
        $("#nombrePon").text("Entregas del procedimiento: "+data.nombre);
    })
    	//console.log('Valor de idcategoria -> '+idcategoria);

    tabla = $('#tblistado')
        .dataTable(
            {
                "aProcessing":true, //Activamos el procesamiento del datatables
                "aServerSide":true, //Paginacion y filtrado realizados por el servidor
                dom: "Bfrtip", //Definimos los elementos del control de tabla
                buttons:[
                    // 'copyHtml5',
                    // 'excelHtml5',
                    // 'csvHtml5',
                    // 'pdf'
                ],
                "ajax":{
                    url: '../ajax/consultas.php?op=consultaimpresiones',
                    data:{iddocumento:iddocumento},
                    type: "get",
                    dataType:"json",
                    error: function(e) {
                        console.log(e.responseText);
                    }
                },
                "bDestroy": true,
                "iDisplayLength": 10, //Paginacion
                "order": [[0,"asc"]] //Ordenar (Columna, orden)
            
            })
        .DataTable();
}
function listarEntregas(idimpresion)
{
    mostrarform(true);
	
 $("#idImpresion").val(idimpresion);
	//console.log('Valor de idcategoria -> '+idcategoria);

    tabla = $('#detalles')
        .dataTable(
            {
                "aProcessing":true, //Activamos el procesamiento del datatables
                "aServerSide":true, //Paginacion y filtrado realizados por el servidor
                dom: "Bfrtip", //Definimos los elementos del control de tabla
                buttons:[
                    // 'excelHtml5',
                ],
                "ajax":{
                    url: '../ajax/consultas.php?op=consultaentregas',
                    data:{idimpresion:idimpresion},
                    type: "get",
                    dataType:"json",
                    error: function(e) {
                        console.log(e.responseText);
                    }
                },
                "bDestroy": true,
                "iDisplayLength": 10, //Paginacion
                "order": [[0,"asc"]] //Ordenar (Columna, orden)
            
            })
        .DataTable();
}

//Función mostrar formulario
function mostrarform(flag1)
{
	
	limpiar();
	if(flag1)
	{
		$("#listadoregistros").hide();
		$("#detalles").show();
		$("#btnCancelar").show();
        $("#tablaEntregas").show();
        $("#btnGuardarEntregas").hide();
        $("#btnModifEntregas").show();
		detalles=0;
	}
    
	else
    {
        $("#tablaEntregas").hide();
        $("#listadoregistros").show();
		$("#detalles").hide();
        $("#btnCancelar").hide();
    }
}


function mostrar(iddocumento)
{
	
	mostrarform(true);
	
 	$.post("../ajax/consultas.php?op=listarDetalle&id="+iddocumento,function(r){
	        $("#detalles").html(r);
	});	
	
}

function mostrarDocumentos(){
    let idtipodocumento = $("#idtipodocumento").val();
    console.log(idtipodocumento);

    $.post("../ajax/documento.php?op=selectDocumento",{idtipodocumento:idtipodocumento}, function (r) {
        $("#iddocumento").html(r);
        $("#iddocumento").selectpicker("refresh");
    });

}
function mostrarRevision(){
    let iddocumento = $("#iddocumento").val();
    console.log(iddocumento);

    $.post("../ajax/documento.php?op=selectRevision",{iddocumento:iddocumento}, function (r) {
        $("#num_revision").html(r);
        $("#num_revision").selectpicker("refresh");
    });

}

function checkHandler(contadorFilas,checkbox) {
let valorCheckbox = checkbox.is(":checked");
console.log(valorCheckbox)
if (valorCheckbox === true) {
    $("#entregado"+contadorFilas).val("1");
}
else {$("#entregado"+contadorFilas).val("0")};

}


function cancelarform()
{
	cont = 0
	detalles = 0
	contador = -1
	limpiar();
	//location.reload();
	mostrarform(false);
    listar()
    $("#nombrePon").empty();

}

function deleteDetalle(identrega){
    var result = confirm("¿Está seguro de eliminar la entrega?")
	if(result)
	{
        $.post("../ajax/entrega.php?op=deleteDetalle",{identrega:identrega}, function (r) {
            alert(r)
            tabla.ajax.reload();
            contador = -1
        })

        
	}
}

function formularioAlta(){
    $("#btnGuardarEntregas").show();
    $("#btnModifEntregas").hide();

}

function checkEventual(num,flagRollback){
    let idoperario = $("#idoperario"+num).val();
    console.log(idoperario);
    if(idoperario === "0"){
        console.log("Activado")
        $("#selectOperario"+num).empty();
        $("#selectOperario"+num).html("<input type='text' class='form-control' name='idoperario[]' id='idpoerario' placeholder='Eventual'>"+
        "<button type='button' class='btn btn-danger' onclick='checkEventual("+num+",true)'>Cancelar</button>");
    }
    if(flagRollback === true ){
        console.log("Select")
        $("#selectOperario"+num).empty();
        $("#selectOperario"+num).append("<select title='Seleccione un operario' onchange='checkEventual("+num+");' class='form-control selectpicker' name='idoperario[]' id='idoperario"+num+"' data-dropup-auto='false' data-live-search='true'></select>");
        $.post("../ajax/consultas.php?op=selectPersona", function (r) {
            $("#idoperario"+num).html(r);
            $("#idoperario"+num).append('<option value="0">Eventual</option>');
            $("#idoperario"+num).selectpicker("refresh");
        });
    }
}

init();