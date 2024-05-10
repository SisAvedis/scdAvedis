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

    if($_COOKIE['escritorio'] == 1)
    {
        require_once '../modelos/Consultas.php';
        
        $consulta = new Consultas();
        $rsptap = $consulta->totalProcedimiento();
        $regp = $rsptap->fetch_object();
        $totalp = $regp->cantidad_procedimiento;

        $rsptai = $consulta->totalInstructivo();
        $regi = $rsptai->fetch_object();
        $totali = $regi->cantidad_instructivo;

        // Mostrar graficos 
        $procedimientos12 = $consulta->procedimientos12meses();
        $fechasp = '';
        $totalesp = '';

        while($regfechap = $procedimientos12->fetch_object())
        {
            $fechasp =  $fechasp.'"'.$regfechap->fecha.'",';
            $totalesp = $totalesp.$regfechap->total.',';
        }

        //Quitamos la ultima coma
        $fechasp = substr($fechasp,0,-1);
        $totalesp = substr($totalesp,0,-1);

        //Graficos Venta
        $instructivos12 = $consulta->instructivos12meses();
        $fechasi = '';
        $totalesi = '';

        while($regfechai = $instructivos12->fetch_object())
        {
            $fechasi =  $fechasi.'"'.$regfechai->fecha.'",';
            $totalesi = $totalesi.$regfechai->total.',';
        }
        
        //Quitamos la ultima coma
        $fechasi = substr($fechasi,0,-1);
        $totalesi = substr($totalesi,0,-1);
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
                        <h1 class="box-title">Escritorio</h1>
                        <div class="box-tools pull-right">
                            </div>
                        </div>
                        <!-- /.box-header -->
                        <!-- centro -->
                        
                    <div class="panel-body table-responsive">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="box box-primary">
                                    
                                    <div class="box-header with-border">
                                        Vigencia de Documentos
                                    </div>
                                    <div class="box body">
                                        <table id="tbldocumentos" class="table table-striped table-bordered table-condensed table-hover">
                                            <thead>
                                                <th>Código</th>
                                                <th>Documentación</th>
                                                <th>Documento</th>
                                                <th>Vencimiento</th>
                                                <th>Vigencia</th>
                                                <th>Estado</th>
                                            </thead>
                                            <tbody>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div class="panel-body">
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                        <div class="small-box bg-aqua">
                                            <div class="inner">
                                                <h4 style="font-size:17px">
                                                    <strong>Total: <?php echo $totalp; ?></strong>
                                                    <p>Buenos Aires</p>
                                                </h4>
                                            </div>
                                <div class="icon">
                                    <i class="ion ion-bag"></i>
                                </div>
                                <!--<a href="ingreso.php" class="small-box-footer">-->
                                    Documentos
                                    <i class="fa fa-arrow-circle-right"></i>
                                </a>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                            <div class="small-box bg-green">
                                <div class="inner">
                                    <h4 style="font-size:17px">
                                        <strong>Total: <?php echo $totali; ?></strong>
                                        <p>Formosa</p>
                                    </h4>
                                </div>
                                <div class="icon">
                                    <i class="ion ion-bag"></i>
                                </div>
                                <!--<a href="venta.php" class="small-box-footer">-->
                                    Documentos 
                                     <i class="fa fa-arrow-circle-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
					
					
                    <div class="panel-body">
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <div class="box box-primary">

                                <div class="box-header with-border">
                                    A veces en los próximos 12 meses
                                </div>
                                <div class="box body">
                                    <canvas id="procedimientos" width="400" height="300"></canvas>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <div class="box box-primary">

                                <div class="box-header with-border">
                                    A vencer en los próximos 12 meses
                                </div>
                                <div class="box body">
                                    <canvas id="instructivos" width="400" height="300"></canvas>
                                </div>
                            </div>
                        </div>
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

<script src="../public/js/Chart.min.js"></script>
<script src="../public/js/Chart.bundle.min.js"></script>

<script>

$(document).ready(function(){
    $('#tbldocumentos').DataTable({
        "aProcessing":true, //Activamos el procesamiento del datatables
                "aServerSide":true, //Paginacion y filtrado realizados por el servidor
                dom: "Bfrtip", //Definimos los elementos del control de tabla
                buttons:[
                ],
        "ajax":{
            url:'../ajax/consultas.php?op=documentosAVencer',
            type: 'get',
            dataType: 'json',
            error:function(e){
                console.log(e.responseText);
            }
        },
        "bDestroy":true,
        "iDisplayLength": 5, //Paginacion
        "order": false //Ordenar (columna,orden)
    });
});

var ctx = document.getElementById("procedimientos").getContext('2d');
var procedimientos = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: [<?php echo $fechasp; ?>],
        datasets: [{
            label: 'A vencer en los próximos 12 meses',
            data: [<?php echo $totalesp; ?>],
            backgroundColor: [
                'rgba(255, 99, 132, 0.2)',
                'rgba(54, 162, 235, 0.2)',
                'rgba(255, 206, 86, 0.2)',
                'rgba(75, 192, 192, 0.2)',
                'rgba(153, 102, 255, 0.2)',
                'rgba(255, 159, 64, 0.2)',
                'rgba(255, 99, 132, 0.2)',
                'rgba(54, 162, 235, 0.2)',
                'rgba(255, 206, 86, 0.2)',
                'rgba(75, 192, 192, 0.2)',
				'rgba(153, 102, 255, 0.2)',
                'rgba(255, 159, 64, 0.2)'
            ],
            borderColor: [
                'rgba(255,99,132,1)',
                'rgba(54, 162, 235, 1)',
                'rgba(255, 206, 86, 1)',
                'rgba(75, 192, 192, 1)',
                'rgba(153, 102, 255, 1)',
                'rgba(255, 159, 64, 1)',
                'rgba(255,99,132,1)',
                'rgba(54, 162, 235, 1)',
                'rgba(255, 206, 86, 1)',
                'rgba(75, 192, 192, 1)',
				'rgba(153, 102, 255, 1)',
                'rgba(255, 159, 64, 1)'
            ],
            borderWidth: 1
        }]
    },
    options: {
        scales: {
            yAxes: [{
                ticks: {
                    beginAtZero:true
                }
            }]
        }
    }
});


var ctx = document.getElementById("instructivos").getContext('2d');
var instructivos = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: [<?php echo $fechasi; ?>],
        datasets: [{
            label: 'A vencer en los próximos 12 meses',
            data: [<?php echo $totalesi; ?>],
            backgroundColor: [
                'rgba(255, 99, 132, 0.2)',
                'rgba(54, 162, 235, 0.2)',
                'rgba(255, 206, 86, 0.2)',
                'rgba(75, 192, 192, 0.2)',
                'rgba(153, 102, 255, 0.2)',
                'rgba(255, 159, 64, 0.2)',
                'rgba(255, 99, 132, 0.2)',
                'rgba(54, 162, 235, 0.2)',
                'rgba(255, 206, 86, 0.2)',
                'rgba(75, 192, 192, 0.2)',
                'rgba(153, 102, 255, 0.2)',
                'rgba(255, 159, 64, 0.2)'
            ],
            borderColor: [
                'rgba(255,99,132,1)',
                'rgba(54, 162, 235, 1)',
                'rgba(255, 206, 86, 1)',
                'rgba(75, 192, 192, 1)',
                'rgba(153, 102, 255, 1)',
                'rgba(255, 159, 64, 1)',
                'rgba(255,99,132,1)',
                'rgba(54, 162, 235, 1)',
                'rgba(255, 206, 86, 1)',
                'rgba(75, 192, 192, 1)',
                'rgba(153, 102, 255, 1)',
                'rgba(255, 159, 64, 1)'
            ],
            borderWidth: 1
        }]
    },
    options: {
        scales: {
            yAxes: [{
                ticks: {
                    beginAtZero:true
                }
            }]
        }
    }
});

</script>

<?php
  }
  ob_end_flush(); //liberar el espacio del buffer
?>