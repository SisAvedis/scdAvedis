var tabla;

//Funcion que se ejecuta al inicio
function init()
{mostrarform(false);
    
    $("#tblistadohidden").hide();
    //Cargamos los items al select sector
    listarRegion();
    
}

function listarRegion(){
    let region = $("#region").val();
    $.post("../ajax/documento.php?op=selectSector&region="+region, function (r) {
        if (region === 'Formosa'){
            $("#idsector").html('<option value="6,7,8,9">Todos</option>');
            
        }
        else{
            
            $("#idsector").html('<option value="1,3,4,5">Todos</option>');
        }
        $("#idsector").append(r);

        $("#idsector").selectpicker("refresh");
    });	


}

function limpiar()
{
	//$("#iddocumento").val("");
	//$("#observacion").val("");
	//$("#idsector").val("");
	$(".filas").remove();
}

function listar()
{

    mostrarform(false);
	var idsector = $("#idsector").val();
    
	//console.log('Valor de idcategoria -> '+idcategoria);
    
    tabla = $('#tblistado')
    .dataTable(
        {
            "aProcessing":true, //Activamos el procesamiento del datatables
            "aServerSide":true, //Paginacion y filtrado realizados por el servidor
            dom: "Bfrtip", //Definimos los elementos del control de tabla
            buttons:[
            ],
            "ajax":{
                url: '../ajax/consultas.php?op=consultadocumento',
                data:{idsector:idsector},
                type: "get",
                dataType:"json",
                error: function(e) {
                    console.log(e.responseText);
                }
            },
            "bDestroy": true,
            "iDisplayLength": 5, //Paginacion
            "order": [[1,"asc"]] //Ordenar (Columna, orden)
            
        })
        .DataTable();
        tabla = $('#tblistadohidden')
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
                    url: '../ajax/consultas.php?op=consultadocumentoExcel',
                    data:{idsector:idsector},
                    type: "get",
                    dataType:"json",
                    error: function(e) {
                        console.log(e.responseText);
                    }
                },
                "bDestroy": true,
                "iDisplayLength": 5, //Paginacion
                "order": [[1,"asc"]] //Ordenar (Columna, orden)
                
            })
            .DataTable();
            $("#tblistadohidden_paginate").hide();
            $("#tblistadohidden_info").hide();
            $("#tblistadohidden_filter").hide();
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
		detalles=0;
	}

	else
    {
        $("#listadoregistros").show();
		$("#detalles").hide();
        $("#btnCancelar").hide();
    }
}

function cancelarform()
{
	limpiar();
	mostrarform(false);
}

function mostrar(iddocumento)
{
	
	mostrarform(true);
	
 	$.post("../ajax/consultas.php?op=listarDetalle&id="+iddocumento,function(r){
	        $("#detalles").html(r);
	});	
	
}

init();