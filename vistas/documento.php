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

    if($_COOKIE['ABM'] == 1)
    {

// print_r($_COOKIE);
?>

  <!--Contenido-->
        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">        
          <!-- Main content -->
          <section class="content">
              <div class="row">
                <div class="col-md-12">
                    <div class="box">
                      <div class="box-header with-border">
                            <h1 class="box-title">Documento 
                              <!--<button class="btn btn-success" id="btnagregar" onclick="mostrarform(true)">-->
							  <button class="btn btn-success" id="btnagregar" onclick="mostrarform('A')">
                                <i class="fa fa-plus-circle"></i> 
                                Agregar
                              </button>
                              
							  <a id="ver" target="_blank" href="#">
                                <button class="btn btn-success" id="btnver">Ver Archivo</button>
                              </a>
							  
							  <!--<a id="eliminar" target="_blank" href="#">-->
                                <button class="btn btn-danger" id="btneliminar">Eliminar Archivo</button>
                              <!--</a>-->
							  
                            </h1>
                          <div class="box-tools pull-right">
                          </div>
                      </div>
                      <!-- /.box-header -->
                      <!-- centro -->
                      <div class="panel-body table-responsive" id="listadoregistros">
                          <table id="tblistado" class="table table-striped table-bordered table-condensed table-hover">
                            <thead>
                              <th>Opciones</th>
                              <th>Fecha</th>
							                <th>Nombre</th>
							                <th>Descripcion</th>
							                <th>Número Revisión</th>
							                <th>Fecha Vigencia</th>
                              <th>Categoria</th>
                              <th>Tipo Documento</th>
							                <th>Estado</th>
                            </thead>
                            <tbody>

                            </tbody>
                            <tfoot>
							                <th>Opciones</th>
							                <th>Fecha</th>
                              <th>Nombre</th>
							                <th>Descripcion</th>
							                <th>Número Revisión</th>
							                <th>Fecha Vigencia</th>
                              <th>Categoria</th>
                              <th>Tipo Documento</th>
                              <th>Estado</th>
                            </tfoot>
                          </table>
                      </div>
                      <div class="panel-body"  id="formularioregistros">
                          <form name="formulario" id="formulario" method="POST" enctype="multipart/form-data">
                            <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                              <label>Nombre:</label>
                              <input type="hidden" name="iddocumento" id="iddocumento">
                              <input type="text" class="form-control" name="nombre" id="nombre" maxlength="100" placeholder="Nombre" required>
                            </div>
                            <div class="form-group col-lg-4 col-md-4 col-sm-4 col-xs-12">
                              <label>Sector:</label>
                              <select name="idsector" id="idsector" data-live-search="true" class="form-control selectpicker" required></select>
                            </div>  
                            <div class="form-group col-lg-4 col-md-4 col-sm-4 col-xs-12">
                              <label>Selectora de Color:</label>
                              <input name="idcolor" id="idcolor" type="color" onchange="alert($('#idcolor').val())"/>
                            </div>  
                            <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                              <label>Descripción:</label>
                              <input type="text" class="form-control" name="descripcion" id="descripcion" maxlength="256" placeholder="Descripción">
                            </div>
							<div class="form-group col-lg-4 col-md-4 col-sm-4 col-xs-12">
                <label>Tipo de Documento:</label>
                <select name="idtipo_documento" id="idtipo_documento" data-live-search="true" class="form-control selectpicker" required></select>
              </div>
              
              <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12" >
                <label >Días de Preaviso:</label>
                
                <input type="number" class="form-control" name="dias_preaviso" id="dias_preaviso" maxlength="100" placeholder="Número de Revisión" required>
              </div>
              <div class="form-group col-lg-4 col-md-4 col-sm-4 col-xs-12" >
                <label >Fecha de Vigencia:</label>
                
                <input type="date" class="form-control" name="vigencia" id="vigencia" maxlength="100" placeholder="" required>
              </div>
							
							<div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12" id="archivoba">
                <label id="lblarchivo">Archivo a Subir:</label>
                <input type="file" class="form-control" name="archivo" id="archivo">

                <a id="btnMotivo" data-toggle="modal"  href="modalMotivo" class="btn btn-info">
                               Motivo
                              </a>
							</div>
							
                            <!--<div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">-->
                              <!--<label id="lblarchivo">Archivo:</label>-->
                              <!--<input type="file" class="form-control" name="archivo" id="archivo">-->
							  
							  <!--
                              <input type="hidden" class="form-control" name="imagenactual" id="imagenactual">
                              <img src="" width="150px" height="120px" id="imagenmuestra">
							  -->
							  <!--<button class="btn btn-warning" onclick="subir()" type="button"><i class="fa fa-arrow-circle-left"></i> Subir</button>-->
							<!--</div>-->
							
							<div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12" id="archivoab">
                              <!--
							  <label>Archivo:</label>
							  <input type="text" class="form-control" name="nombrearchivo" id="nombrearchivo" maxlength="256" placeholder="Nombre">
							  -->
							  <!--
                              <input type="hidden" class="form-control" name="imagenactual" id="imagenactual">
                              <img src="" width="150px" height="120px" id="imagenmuestra">
							  -->
							  <!--
							  <button class="btn btn-success" onclick="ver()" type="button"><i class="fa fa-arrow-circle-left"></i> Ver</button>
							  <button class="btn btn-danger" onclick="eliminar()" type="button"><i class="fa fa-arrow-circle-left"></i> Eliminar</button>
								
							  <a target="_blank" href="uploads/">
                                <button class="btn btn-info" type="button"><i class="fa fa-arrow-circle-left"></i> Ver v2</button>
                              </a> 	
							  -->	
							</div>
							<!--
							<div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12" id="eliminado">
							</div>
							-->
							<!--
                            <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                              <label>Codigo:</label>
                              <input type="text" class="form-control" name="codigo" id="codigo" placeholder="Codigo de barras">
                              <button class="btn btn-success" type="button" onclick="generarbarcode()">Generar</button>
                              <button class="btn btn-info" type="button" onclick="imprimir()">Imprimir</button>
                              <div id="print">
                                <svg id="barcode"></svg>
                              </div>
                            </div>
							-->
                            <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                              <button class="btn btn-primary" type="submit" id="btnGuardar"><i class="fa fa-save"></i> Guardar</button>

                              <button class="btn btn-danger" onclick="cancelarform()" type="button"><i class="fa fa-arrow-circle-left"></i> Cancelar</button>
                            </div>
                          </form>
						  
						  <!--
						  <div class="form-group col-lg-4 col-md-4 col-sm-4 col-xs-12" id="archivoab">
						  </div>
						  -->
						  
						  
                      </div>
                      <!--Fin centro -->
                    </div><!-- /.box -->
                </div><!-- /.col -->
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
  <!--<script src="../public/js/JsBarcode.all.min.js"></script>-->
  <!--<script src="../public/js/jquery.PrintArea.js"></script>-->
  <script src="./scripts/documento.js"></script>
<script>
var sessionUsuario = "<?php echo $_COOKIE['idusuario']; ?>";

</script>
<?php

  }
  ob_end_flush(); //liberar el espacio del buffer
?>