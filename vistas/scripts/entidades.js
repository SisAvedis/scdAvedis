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
}

//funcion limpiar
function limpiar()
{
    $("#identidad").val("");
    $("#idjurisdiccion").val("");
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
            "../ajax/jurisdiccion.php?op=selectJurisdiccion",
            function(data)
            {        
                console.log(data)
                $("#idjurisdiccion").html(data);
                $("#idjurisdiccion").selectpicker('refresh');
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
                    url: '../ajax/entidad.php?op=listar',
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
    var formData = new FormData($("#formulario")[0]);
    
    $.ajax({
        url: "../ajax/entidad.php?op=guardaryeditar",
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

function mostrar(identidad)
{
    $.post(
        "../ajax/entidad.php?op=mostrar",
        {identidad:identidad},
        function(data,status)
        {
            data = JSON.parse(data);
            mostrarform(false,true);
            console.log(data)

            $("#identidad").val(data.identidad);
            $("#descripcion").val(data.descripcion);
            $("#idlocalidad").val(data.idlocalidad);
            $.post(
                "../ajax/jurisdiccion.php?op=selectJurisdiccion",
                function(r)
                {        
                    console.log(r)
                    $("#idjurisdiccion").html(r);
                    $("#idjurisdiccion").selectpicker('refresh');
                    $("#idjurisdiccion").val(data.idjurisdiccion);
                    $("#idjurisdiccion").selectpicker('refresh');
                }
            );

            
        }
    );
}

//funcion para descativar ubicacion
function desactivar(identidad)
{
    bootbox.confirm("¿Estas seguro de desactivar el Tipo de Documento?",function(result){
        if(result)
        {
            $.post(
                "../ajax/entidad.php?op=desactivar",
                {identidad:identidad},
                function(e)
                {
                    bootbox.alert(e);
                    tabla.ajax.reload();
        
                }
            );
        }
    });
}

function activar(identidad)
{
    bootbox.confirm("¿Estas seguro de activar el Tipo de Documento?",function(result){
        if(result)
        {
            $.post(
                "../ajax/entidad.php?op=activar",
                {identidad:identidad},
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