<?php
  //Activacion de almacenamiento en buffer
  ob_start();
  //iniciamos las variables de session
  session_start();

  if(!isset($_COOKIE["nombre"]))
  {
    header("Location: login.html");
  }

  else  //Agrega toda la vista
  {
    require 'header.php';

    if($_COOKIE['consulta'] == 1)
    {
?>

<!--Contenido-->
      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">        
        <!-- Main content -->
        <section class="content">
            <div class="row">
              <div class="col-md-12">
                  <div class="box" >
                    <div class="box-header with-border">
                        <h1 class="box-title">Consulta Entregas por Documento</h1>
                        <div class="box-tools pull-right">
                        </div>
                    </div>
                    <!-- /.box-header -->
                    <!-- centro -->
                    <div class="panel-body table-responsive" id="listadoregistros" style="height:40em;">
                        <div class="form-group col-lg-3 col-md-4 col-sm-4 col-xs-12">
                            <label for="">Tipo Documento</label>
                            <select title="--<b>Seleccione un Tipo</b>--" name="idtipodocumento" id="idtipodocumento" onchange="mostrarDocumentos()" class="form-control selectpicker" data-live-search="true" required></select>
                            <!-- <button class="btn btn-success" onclick="listar()">Mostrar</button> -->
                        </div>
                        <div class="form-group col-lg-3 col-md-4 col-sm-4 col-xs-12">
                            <label for="">Documento</label>
                            <select title="--<b>Seleccione un Documento</b>--" name="iddocumento" id="iddocumento" onchange="mostrarRevision()" class="form-control selectpicker" data-live-search="true" required></select>
                            <!-- <button class="btn btn-success" onclick="listar()">Mostrar</button> -->
                        </div>
                        <div class="form-group col-lg-3 col-md-4 col-sm-4 col-xs-12">
                            <label for="">Número de Revisión</label>
                            <select title="--<b>Seleccione una Revisión</b>--" name="num_revision" id="num_revision" onchange="listar()" class="form-control selectpicker" data-live-search="true" required></select>
                            <!-- <button class="btn btn-success" onclick="listar()">Mostrar</button> -->
                        </div>
                        <form id="formulario" onsubmit="return false;">
						<table id="tblistado" class="table table-striped table-bordered table-condensed table-hover">
                          <thead>
                            <th>Usuario</th>
                            <th>Documento</th>
                            <th>Fecha de Vigencia</th>
                            <th>Número de Revisión</th>
                            <th>Vigente</th>
                            <th>Entregado</th>
                          </thead>
                          <tbody>

                          </tbody>
                          <tfoot>
                            <th>Usuario</th>
                            <th>Documento</th>
                            <th>Fecha de Vigencia</th>
                            <th>Número de Revisión</th>
                            <th>Vigente</th>
                            <th>Entregado</th>
                          </tfoot>
                        </table>
                    </div>
                    <button id="btnGuardar" type="submit" class="btn btn-primary">Guardar</button>
                    </form>
					<div class="panel-body table-responsive" style="height:40em;">
						<table id="detalles" class="table table-striped table-bordered table-condensed table-hover">
                        <thead style="background-color:#A9D0F5">
                                  <th>Opciones</th>
                                  <th>Codigo</th>
                                  <th>Documento</th>
                                  <th>Descripcion</th>
                                  <th>Sector</th>
                        </thead>
                        <tbody>
                                            
                        </tbody>
              </table>
          </div>
					
					
					<div class="panel-body">
						<button id="btnCancelar" class="btn btn-danger" onclick="cancelarform()" type="button"><i class="fa fa-arrow-circle-left"></i> Cancelar</button>
                    </div>
                    <!--Fin centro -->
                  </div><!-- /.box -->
              </div> <!--/.col-->
          </div><!-- /.row -->
      </section><!-- /.content -->

    </div><!-- /.content-wrapper -->
  <!--Fin-Contenido-->


<?php
  
  } //Llave de la condicion if de la variable de session

  else
  {
    require 'noacceso.php';
  }

  require 'footer.php';
?>

<script src="./scripts/consultaentregas.js"></script>
<script>
    $(document).ready(function () 
{
   $('input[type="checkbox"]').change(function () 
   {
        var arr = $.map($('input:checkbox:checked'), function(e,i) {
            return +e.value;
        });
        alert(arr);
    });
});
$("#btnGuardar").click( function(e) {
    
    guardaryeditar(e)
})

</script>
<?php
  }
  ob_end_flush(); //liberar el espacio del buffer
?>