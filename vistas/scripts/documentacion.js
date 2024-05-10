var tabla;

//Funcion que se ejecuta al inicio
function init()
{
    mostrarform(false,false);
    listar();

    $("#formulario").on("submit",function(e)
    {
        guardaryeditar(e);
    })
    $("#formularioDocumento").on("submit",function(e)
    {
        guardaryeditarDocumento(e);
    })
    $.post(
        "../ajax/jurisdiccion.php?op=selectJurisdiccion",
        function(data)
        {        
            console.log(data)
            $("#idJurisdiccionFiltro").html(data);
            $("#idJurisdiccionFiltro").selectpicker('refresh');
        }
    );
    selectFiltroEntidad();
}

//funcion limpiar
function limpiar()
{
    $("#iddocumentacion").val("");
    $("#identidad").val("");
    $("#descripcion").val("");
    $("#idlocalidad").val("");
}

//funcion mostrar formulario
function mostrarform(flag1,flag2)
{
    limpiar();

    if(flag1 && !flag2)
    {
        $("#listadoregistros").hide();
        $("#formularioregistros").show();
        $("#btnGuardar").prop("disabled",false);
        $("#btnagregar").hide();
        $.post(
            "../ajax/entidad.php?op=selectEntidad",
            function(data)
            {        
                console.log(data)
                $("#identidad").html(data);
                $("#identidad").selectpicker('refresh');
            }
        );
    }
    else if(!flag1 && flag2)
    {
        $("#listadoregistros").hide();
        $("#formularioregistros").show();
        $("#btnGuardar").prop("disabled",false);
        $("#btnagregar").hide();
        
    }
    else
    {
        $("#listadoregistros").show();
        $("#formularioregistros").hide();
        $("#btnagregar").show();
    }
}

//Funcion cancelarform
function cancelarform()
{
    limpiar();
    mostrarform(false);
}

//Funcion listar
function listar()
{
    let identidad = $("#idEntidadFiltro").val();
    let idplanta = $("#idPlantaFiltro").val();
    // console.log(idplanta === undefined)
    if (identidad === null) {identidad = '';}
    if (idplanta === null) {idplanta = '';}
    tabla = $('#tblistado')
        .dataTable(
            {
                "aProcessing":true, //Activamos el procesamiento del datatables
                "aServerSide":true, //Paginacion y filtrado realizados por el servidor
                dom: "Bfrtip", //Definimos los elementos del control de tabla
                buttons:[
                ],
                "ajax":{
                    url: '../ajax/documentacion.php?op=listar&identidad='+identidad+'&idplanta='+idplanta,
                    type: "get",
                    dataType:"json",
                    error: function(e) {
                        console.log(e.responseText);
                    }
                },
                "bDestroy": true,
                "iDisplayLength": 5, //Paginacion
                "order": false //Ordenar (Columna, orden)
            
            })
        .DataTable();
}

//funcion para guardar o editar
function guardaryeditar(e)
{
    e.preventDefault(); //No se activará la acción predeterminada del evento
	$("#btnGuardar").prop("disabled",true);
    var formData = new FormData($("#formulario")[0]);
    
    $.ajax({
        url: "../ajax/documentacion.php?op=guardaryeditar",
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,
        success: function(datos)
        {
            //console.log("succes");
            bootbox.alert(datos);
            mostrarform(false);
            tabla.ajax.reload();
        },
        error: function(error)
        {
            console.log("error: " + error);
        } 
    });

    limpiar();
}
function guardaryeditarDocumento(e)
{
    e.preventDefault(); //No se activará la acción predeterminada del evento
	$("#btnGuardarDocumento").prop("disabled",true);
    var formData = new FormData($("#formularioDocumento")[0]);
    
    let motivoFaltaDocumento = $("#motivoFaltaDocumento").val();
    
    formData.append('motivoFaltaDocumento', motivoFaltaDocumento);
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
            mostrarform(false);
            
            tabla.ajax.reload();
            $("#btnGuardarDocumento").prop("disabled",false);
        },
        error: function(error)
        {
            console.log("error: " + error);
        } 
    });

    limpiar();
}

function mostrar(iddocumentacion)
{
    $.post(
        "../ajax/documentacion.php?op=mostrar",
        {iddocumentacion:iddocumentacion},
        function(data,status)
        {
            data = JSON.parse(data);
            mostrarform(false,true);
            console.log(data)

            $("#iddocumentacion").val(data.iddocumentacion);
            $("#identidad").val(data.identidad);
            $("#descripcion").val(data.descripcion);
            $("#idplanta").val(data.idplanta);
            $.post(
                "../ajax/entidad.php?op=selectEntidad",
                function(r)
                {        
                    console.log(r)
                    $("#identidad").html(r);
                    $("#identidad").selectpicker('refresh');
                    $("#identidad").val(data.identidad);
                    $("#identidad").selectpicker('refresh');
                }
            );

            
        }
    );
}

//funcion para descativar ubicacion
function desactivar(iddocumentacion)
{
    bootbox.confirm("¿Estas seguro de anular la Documentación?",function(result){
        if(result)
        {
            bootbox.prompt({
                title: "Motivo de Anulación",
                inputType: 'textarea',
                callback: function (result) {
                    if (result !== null && result !== "" && result !== undefined) {
                      
            $.post(
                "../ajax/documentacion.php?op=desactivar",
                {iddocumentacion:iddocumentacion,motivoAnulacion:result},
                function(e)
                {
                    bootbox.alert(e);
                    tabla.ajax.reload();
        
                }
            );
        }
        }
    });
    }
});

}
function desactivarDocumento(iddocumento)
{
    bootbox.confirm("¿Estas seguro de desactivar el Documento?",function(result){
        if(result)
        {
            bootbox.prompt({
                title: "Motivo de la falta del documento",
                inputType: 'textarea',
                callback: function (result) {
                    if (result !== null && result !== "" && result !== undefined) {
                        $.post(
                            "../ajax/documento.php?op=desactivar",
                            {iddocumento:iddocumento,motivoAnulacion:result},
                            function(e)
                            {
                                bootbox.alert(e);
                                tabla.ajax.reload();
                    
                            }
                        );
                    }
                }
            });
        }
    });
}

function activar(iddocumentacion)
{
    bootbox.confirm("¿Estas seguro de activar la Documentación?",function(result){
        if(result)
        {
            $.post(
                "../ajax/documentacion.php?op=activar",
                {iddocumentacion:iddocumentacion},
                function(e)
                {
                    bootbox.alert(e);
                    tabla.ajax.reload();
        
                }
            );
        }
    });
}
function finalizar(iddocumentacion)
{
    bootbox.confirm("¿Estas seguro de finalizar la Documentación?",function(result){
        if(result)
        {
            $.post(
                "../ajax/documentacion.php?op=finalizar",
                {iddocumentacion:iddocumentacion},
                function(e)
                {
                    bootbox.alert(e);
                    tabla.ajax.reload();
        
                }
            );
        }
    });
}

function mostrarDocumento($iddocumento){
    $.post(
        "../ajax/documento.php?op=mostrar",
        {iddocumento:$iddocumento},
        function(data,status)
        {
            data = JSON.parse(data);
            console.log(data)

            $("#iddocumento").val(data.iddocumento);
            $("#iddocumentacionDocumento").val(data.iddocumentacion);
            $("#codigo").val(data.codigo);
            $("#descripcionDocumento").val(data.descripcion);
            $("#fecha_preaviso").val(data.fecha_preaviso);
            $("#vigencia").val(data.vigencia);
            $("#motivoFaltaDocumento").val(data.motivoFaltaDocumento);
            console.log(data.nombrearchivo !== null)
            if (data.nombrearchivo !== null) {
                $("#archivoba").html("<a href='"+data.carpeta+data.nombrearchivo+"' target='_blank'><button class='btn btn-success' type='button'>Ver Archivo</button></a>"+
                " <button class='btn btn-danger' type='button' onclick='eliminarArchivo("+data.iddocumento+")'>Eliminar Archivo</button>"
                );
            }else{
                $("#archivoba").html("<label id='lblarchivo'>Archivo a Subir:</label><input type='file' class='form-control' name='archivo' id='archivo'>"
                +'<a id="btnMotivo" data-toggle="modal"  href="#modalMotivo" class="btn btn-info">Motivo Falta Documento</a>');
            }
            $("#btnGuardarDocumento").prop("disabled",false);
            
        }
    );
}

function cierreModalDocumento(){
    $("#iddocumento").val("");
    $("#iddocumentacionDocumento").val("");
    $("#codigo").val("");
    $("#descripcionDocumento").val("");
    $("#fecha_preaviso").val("");
    $("#vigencia").val("");
    $("#motivoFaltaDocumento").val("");
    $("#archivo").val("");
    $("#archivoba").html("<label id='lblarchivo'>Archivo a Subir:</label><input type='file' class='form-control' name='archivo' id='archivo'>"
    +'<a id="btnMotivo" data-toggle="modal"  href="#modalMotivo" class="btn btn-info">Motivo Falta Documento</a>');
}

function selectFiltroEntidad(){
   let idplanta = $("#idPlantaFiltro").val();
    console.log(idplanta)
    $.post(
        "../ajax/entidad.php?op=selectEntidad",
        {idplanta : idplanta},
        function(data)
        {        
            console.log(data)
            $("#idEntidadFiltro").html(data);
            $("#idEntidadFiltro").prepend("<option value=''>--Vacío--</option>");
            $("#idEntidadFiltro").selectpicker('refresh');
        }
    );
}
function eliminarArchivo(iddocumento){

    bootbox.confirm("¿Estas seguro de eliminar el Documento?",function(result){
        if(result)
        {
            $.post(
                "../ajax/documento.php?op=eliminar",
                {iddocumento:iddocumento},
                function(data,status)
                {

                    mostrarDocumento(iddocumento);
                })
                
            }
        })
    }
function cambiarEstadoDocumento(iddocumento){
    let input =
		[
			{text:'Para Aprobar', value: '1'},
			{text:'Aprobado', value: '2'},
			{text:'Para Pagar', value: '3'},
			{text:'En Revision', value: '4'},
			{text:'Personalizado', value: '5'},
			]
		
		;
		
		input.unshift({text:'-- Elegir Estado --', value: ''})
		bootbox.prompt({
			title: 'Elegir Estado',
			inputType: 'select',
			inputOptions: input,
			callback: function (result) {
				console.log(result);
				if (result !== '' && result !== null && result !== '5'){
					$.post("../ajax/documento.php?op=cambiarEstado",{iddocumento : iddocumento, estado : result}, function(data, status){
					bootbox.alert(data);
					tabla.ajax.reload();
					})
				}else if(result == '5'){
                    bootbox.confirm("<form id='custom' action=''>\
                    Estado: <input type='text' id='estado' /><br/>\
                    Color: <input type='color' id='color' />\
                    </form>", function(result) {
                        if(result && $('#estado').val() !== '' && $('#color').val() !== ''){
                            var estado = $('#estado').val();
                            var color = $('#color').val();
                            $.post("../ajax/documento.php?op=cambiarEstado",{iddocumento : iddocumento, estado : estado+'|'+color}, function(data, status){
                            bootbox.alert(data);
                            tabla.ajax.reload();
                            })

                        }else{
                            bootbox.alert('Datos no validos');
                        }
                });
                }
				
			}
		});
    }
//funcion que oculta o muestra campo vencimiento
    function checkVencimiento(){
        var radio = $('input:radio[name=vencimientoRadio]:checked').val();
        if (radio === '1') {
            $("#divVencimiento").show();
        }
        else{
            $("#fecha_preaviso").val("")
            $("#vigencia").val("")
            $("#divVencimiento").hide();
        }
    }
init();