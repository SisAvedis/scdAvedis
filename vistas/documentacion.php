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
                          <h1 class="box-title">Documentación <button class="btn btn-success" id="btnagregar" onclick="mostrarform(true)"><i class="fa fa-plus-circle"></i> Agregar</button></h1>
                        <div class="box-tools pull-right">
                        </div>
                    </div>
                    <!-- /.box-header -->
                    <!-- centro -->
                    <div class="panel-body table-responsive" id="listadoregistros">

                    <div class="form-group col-lg-3 col-md-3 col-sm-3 col-xs-12">
                            <label>Planta:</label>
                            <select class="form-control "  id="idPlantaFiltro" onchange="selectFiltroEntidad()">
                                <option value="">-- Seleccione una Planta --</option>
                                <option value="1">Avedis Buenos Aires</option>
                                <option value="2">Planta María Formosa</option>
                                <option value="3">Comargas</option>
                            </select>
                          </div>
                          <div class="form-group col-lg-3 col-md-3 col-sm-3 col-xs-12">
                            <label>Entidad:</label>
                            <select title="-- Entidad --" class="form-control selectpicker"  id="idEntidadFiltro">
                              
                              </select>
                            </div>
                            <div class="form-group col-lg-3 col-md-3 col-sm-3 col-xs-12">
                              <button style="margin-top:2.5rem" onclick="listar()" class="btn btn-success">Filtrar</button>
                              </div>
                        <table id="tblistado" class="table table-striped table-bordered table-condensed table-hover">
                          <thead>
                            <th>Opciones</th>
                            <th>Descripción</th>
                            <th>Entidad</th>

                            <th>Planta</th>
                            <th>Estado</th>
                          </thead>
                          <tbody>

                          </tbody>
                          <tfoot>
                            <th>Opciones</th>
                            <th>Descripción</th>
                            <th>Entidad</th>

                            <th>Planta</th>
                            <th>Estado</th>
                          </tfoot>
                        </table>
                    </div>
                    <div class="panel-body" style="height: 400px;" id="formularioregistros">
                        <form name="formulario" id="formulario" method="POST">
                            <input type="hidden" name="iddocumentacion" id="iddocumentacion">
                          <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <label>Descripción:</label>
                            <input type="text" class="form-control" name="descripcion" id="descripcion" maxlength="256" placeholder="Descripción">
                          </div>
                          <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <label>Entidad:</label>
                            <select title="-- Entidad --" class="form-control selectpicker" name="identidad" id="identidad">
                            </select>
                          </div>
                          <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <label>Planta:</label>
                            <select class="form-control " name="idplanta" id="idplanta">
                                <option value="">-- Seleccione una Planta --</option>
                                <option value="1">Avedis Buenos Aires</option>
                                <option value="2">Planta María Formosa</option>
                                <option value="3">Comargas</option>
                            </select>
                          </div>
                          <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <button class="btn btn-primary" type="submit" id="btnGuardar"><i class="fa fa-save"></i> Guardar</button>
                            <button class="btn btn-danger" onclick="cancelarform()" type="button"><i class="fa fa-arrow-circle-left"></i> Cancelar</button>
                          </div>
                        </form>
                    </div>
                    <!--Fin centro -->
                  </div><!-- /.box -->
              </div><!-- /.col -->
          </div><!-- /.row -->
      </section><!-- /.content -->

    </div><!-- /.content-wrapper -->
  <!--Fin-Contenido-->
  <!-- ***********************MODAL DOCUMENTO******************************-->
  <div class="modal fade"  id="formDocumento" tabindex="-1" role="document" aria-labelledby="myModalLabel" aria-hidden="true" onclick="">
    <div class="modal-dialog" style="width: 90% !important;height:auto">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true" onclick="">&times;</button>
          <h4 class="modal-title">Documento de Auditoría</h4>
        </div>
        <div class="modal-body" >
          <form action="" id="formularioDocumento">
            <div class="row">
              
              <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <label>Código:</label>
                <input type="hidden" name="iddocumento" id="iddocumento">
                <input type="hidden" name="iddocumentacionDocumento" id="iddocumentacionDocumento">
                <input type="text" class="form-control" name="codigo" id="codigo" maxlength="100" placeholder="Código" required>
              </div>
              
              <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <label>Descripción:</label>
                <input type="text" class="form-control" name="descripcion" id="descripcionDocumento" maxlength="256" placeholder="Descripción">
              </div>
            </div>
            <div class="row" >
              
              
            
            <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12" id="archivoba">
              <label id="lblarchivo">Archivo a Subir:</label>
              <input type="file" class="form-control" name="archivo" id="archivo">
              <a id="btnMotivo" data-toggle="modal"  href="#modalMotivo" class="btn btn-info">Motivo Falta Documento</a>
            </div>

            <div class="form-check">
                <label >Vencimiento: </label>
                <label class="form-check-label" for="vencimientoRadio1">Activado</label>
                <input type="radio" class="form-check-input" checked name="vencimientoRadio" id="vencimientoRadio1" value="1" onchange="checkVencimiento();">
                <label class="form-check-label" for="vencimientoRadio2">Desactivado</label>
                <input type="radio" class="form-check-input" name="vencimientoRadio" id="vencimientoRadio2" value="0" onchange="checkVencimiento();">
              </div>
            <div id="divVencimiento">
              <div class="form-group col-lg-3 col-md-3 col-sm-3 col-xs-12" >
                <label >Fecha de Preaviso:</label>
                
                <input type="date" class="form-control" name="fecha_preaviso" id="fecha_preaviso" maxlength="100" placeholder="Fecha de Preaviso">
              </div>
              <div class="form-group col-lg-3 col-md-3 col-sm-3 col-xs-12" >
                <label >Fecha de Vencimiento:</label>
                
                <input type="date" class="form-control" name="vigencia" id="vigencia" maxlength="100" placeholder="" >
              </div>
            </div>

            </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary" id="btnGuardarDocumento"><i class="fa fa-save"></i> Guardar</button>
              </div>        
              </div>        
            </form>        
      </div>
    </div>
    
  </div>  
  <!-- Fin modal -->
  
                            <!-- ***************************************MOTIVO FALTA DE DOCUMENTO********************************************** -->
   

             
                            <div class="modal fade" id="modalMotivo" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" >
               <div class="modal-dialog" style="width: 80% !important;">
                <div class="modal-content">
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                  <h4 class="modal-title">Motivo a la falta de documento: </h4>
                </div>
                <div class="modal-body">
                  <textarea  id="motivoFaltaDocumento" name="motivoFaltaDocumento" readonly  cols="30" rows="10" style="width:80%"></textarea>
                  <div class="form-group col-4">
                    <button class="btn btn-info " type="button" id="btnAgrMotivo" onclick="bootbox.prompt({
                                          title: 'Nuevo Motivo:',
                                          inputType: 'textarea',
                                          closeButton: false,
                                          callback: function (result) {
                                          console.log(result);
                                          fecha = new Date();
                                        año = fecha.getFullYear();
                                        mes = fecha.getMonth();
                                        dia = fecha.getDate();
                                        hora = fecha.toLocaleString().substr(10);
                                        fechaStr = fecha.toLocaleString();
                                          motivoValor =  $('#motivoFaltaDocumento').val();
                                          if(result != '' && result != null && result != ' '){
                                            result = '['+fechaStr.replace(',', '')+']'+' -'+result;
                                        }
                                          else{
                                            result = '';
                                          }
                                          if(motivoValor != '' && result != '' && result != null && result != ' '){
                                            result = '\n'+result;
                                            $('#motivoFaltaDocumento').val(motivoValor+result);
                                          }
                                          else if (motivoValor != '' && result === '' || motivoValor != '' && result === null ){
                                            $('#motivoFaltaDocumento').val(motivoValor);
                                          }
                                          else{
                                            $('#motivoFaltaDocumento').val(result);
                                          }
                                          $('.bootbox-input-textarea').attr('id', 'motivoFaltaDocumento-text');
                                          
                                          $('#motivoFaltaDocumento-text').val();
                                          }
                                          });"><i class="fa fa-plus"></i>Agregar Item</button>
                  </div>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
              </div>        
            </div>
          </div>
         </div><!-- Fin modal motivo -->

<style>
  @keyframes turbina{
to{
   
    transform:rotate(360deg);
}}
.loading{
    animation:turbina 2s linear infinite;
}
</style>

<?php
  
  } //Llave de la condicion if de la variable de session

  else
  {
    require 'noacceso.php';
  }
  
  require 'footer.php';
  ?>
<script src="../collapTable/jquery.aCollapTable.js"></script>
<script>
  $(document).ready(function() {
    // var table = $('#listado').DataTable();

      $('#tblistado tbody').on('mouseover', 'tr',  function (event) {
        $(this).css({
        'cursor': 'zoom-in'
    });
        $('selector').addClass('loading');
      })
      $('#tblistado tbody').on('click', 'tr',  function (event) {
        if (!$(event.target).is('button')) {
        var rowListado = tabla.row(this);
        var rowsListado = tabla.row('.shown');
        // rowsListado.child.hide
        var result;
        var data = rowListado.data();
        if(data !== undefined){

          data = data[0];
          data = data.split("\"");
          data = data[3];
          data = data.split("(");
          data = data[1];
          data = data.replace(")", "");
        }
        
        return new Promise((resolve, reject) => {
          $.post("../ajax/documentacion.php?op=mostrar",{iddocumentacion:data}, function(resultado){
            resultado = JSON.parse(resultado);
            // console.log(resultado);
            if(resultado !== null && resultado.condicion === "1"){
              $.post("../ajax/consultas.php?op=consultadocumentoEntidad&iddocumentacion="+data+"&idusuario="+resultado.idusuario, function(r){
                result = r;
              })
              
              .then(()=>{
                
                if (rowListado.child.isShown()) {
                  // This row is already open - close it
                  rowListado.child.hide();
                  $('.shown').removeClass('shown');
                } else {
                  // Open this row
                  rowsListado.child.hide();
            console.log($(event.target).parent()[0])
            rowListado.child(result).show();
            // var tablaDocumentos = $('#tbldocumentos').DataTable();
            // console.log(result)
            $('.shown').removeClass('shown');
            $(event.target).parent().addClass('shown');
          }
        })  
      }
      })
      })  
    }
  })
  
  
  
  $("#btnGuardarDocumento").click(()=>{
    if ($("#archivo").val() === "" && $("#motivoFaltaDocumento").val() === "") {
      bootbox.alert("Debe justificar la falta de documento o subir un archivo");
      return false;
    }else{

      $("#formularioDocumento").submit();
      $("#formDocumento").modal('hide');
    }
  })
  $('#formDocumento').on('hidden.bs.modal', function () {
    cierreModalDocumento();
});
    });
  </script>
<script src="./scripts/documentacion.js?n=1"></script>

<?php
  }
  ob_end_flush(); //liberar el espacio del buffer
?>