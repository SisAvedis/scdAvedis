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

<script src="https://cdn.jsdelivr.net/npm/pdfjs-dist@2.7.570/build/pdf.min.js"></script>
<!--Contenido-->
      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">        
        <!-- Main content -->
        <section class="content">
            <div class="row">
              <div class="col-md-12">
                  <div class="box">
                    <div class="box-header with-border">
                        <h1 class="box-title">Visor PDF</h1>
                        <div class="box-tools pull-right">
                          <!-- <button onclick="history.go(-1);" class="btn btn-danger">
                            <i class="fa fa-arrow-circle-left">Volver</i>
                          </button> -->
                        </div>
                    </div>

                    <!-- /.box-header -->
                    <!-- centro -->
                    <div class="panel-body">

                      <div class="col-8">
                        
                        
                        <button class="btn btn-success" id="prev">Pág. Anterior</button>
                      <button class="btn btn-success" id="next">Siguiente Pág.</button>
                      <button class="btn btn-info" id="zoomIn">Zoom +</button>
                      <button class="btn btn-info" id="zoomOut">Zoom -</button>
                      <button class="btn btn-info" id="rotateLeft">Rotar Izq.</button>
                      <button class="btn btn-info" id="rotateRight">Rotar Der.</button>
                      <!-- <button class="btn btn-info" onclick="imprimirPdf();"> 
                     <i class="fa fa-print"></i> Imprimir
                    </button> -->
                      <span><b>Página: <span id="page_num"></span>/<span id="page_count"></span></b></span>
                    </div>
                      <div id="page-selector" class="col-8" style="overflow-x:auto;display:flex"></div>
                    <canvas id="pdf_canvas" style="max-width:70vw"></canvas>
                    </div>

<style>
  #pdf_canvas{
    pointer-events: none;
  }
  /* .miniature-page{
    pointer-events: none;
  } */
</style>
<script src="../public/js/jquery-3.1.1.min.js"></script>
                    <script>  
$('img').mousedown(function (e) {
  if(e.button == 2) { // right click
    return false; // do nothing!
  }
});
var pdfUrl =  '../uploads/'+'<?php echo $_GET["file"] ?>';
var pdfDoc = null,
pageNum = 1,
pageRendering = false,
pageNumPending = null,
scale = 1.5,
canvas = document.getElementById("pdf_canvas")
ctx = canvas.getContext("2d")
 


function renderPage(num){
  pageRendering = true
  pdfDoc.getPage(num).then((page)=>{
    var viewport = page.getViewport({scale:scale});
    canvas.height = viewport.height
    canvas.width = viewport.width

    var renderContext = {
      canvasContext : ctx,
      viewport : viewport
    }
    var renderTask = page.render(renderContext)
    renderTask.promise.then(()=>{
      pageRendering = false;
      if(pageNumPending !== null){
        renderPage(pageNumPending)
        pageNumPending = null
      }
    })
  })
  document.getElementById("page_num").textContent = num;
  
  }
  
  function queueRenderPage(num){
    if(pageRendering){
      pageNumPending = num
    }else{
      renderPage(num)
    }
  }

  function onPrevPage(){
    if (pageNum<=1){
      return
    }
    pageNum--;
    queueRenderPage(pageNum)
  }
  document.getElementById('prev').addEventListener('click',onPrevPage)

  function onNextPage(){
    if(pageNum >= pdfDoc.numPages ){
      return;
    }
    pageNum++;
    queueRenderPage(pageNum)
  }
  document.getElementById('next').addEventListener('click',onNextPage)

  function zoomOut(){
    if (scale > 0.5){
    scale -= 0.1
    renderPage(pageNum)}
  }
  document.getElementById('zoomOut').addEventListener('click',zoomOut)
  
  function zoomIn(){
    if (scale < 1.8){
      scale += 0.1
      renderPage(pageNum)
      
    }
  }
  document.getElementById('zoomIn').addEventListener('click',zoomIn)

  rotation = 0;

function rotatePage(rotationAngle) {
let page = pdfDoc.getPage(pageNum);
rotation += rotationAngle;
rotationAngle = rotation;
page.then(function (page) {
  var viewport = page.getViewport({ scale: scale });

  // Apply the rotation to the canvas context
  ctx.clearRect(0, 0, canvas.width, canvas.height);
  ctx.save();
  ctx.translate(canvas.width / 2, canvas.height / 2);
  ctx.rotate((rotationAngle * Math.PI) / 180);
  ctx.translate(-canvas.width / 2, -canvas.height / 2);

  var renderContext = {
    canvasContext: ctx,
    viewport: viewport,
  };

  // Render the modified page
  var renderTask = page.render(renderContext);
  renderTask.promise.then(function () {
    ctx.restore();
    pageRendering = false;
    if (pageNumPending !== null) {
      renderPage(pageNumPending);
      pageNumPending = null;
    }
  });
});
}

function rotateRight() {
rotatePage(90);
}
document.getElementById('rotateRight').addEventListener('click', rotateRight);

function rotateLeft() {
rotatePage(-90);
}
document.getElementById('rotateLeft').addEventListener('click', rotateLeft);

// pdfjsLib.getDocument(pdfUrl).promise.then((doc)=>{
  //   document.getElementById('page_count').textContent = doc.numPages;
  // })
  
  pdfjsLib.getDocument(pdfUrl).promise.then(function(pdf) {
    // Get the total number of pages in the PDF
      pdfDoc = pdf
  var numPages = pdf.numPages;
  document.getElementById('page_count').textContent = numPages
    renderPage(pageNum)

    // Set the scale for the miniature page selectors
    var scale = 0.2;

    // Create a container element for the page selectors
    var container = document.getElementById('page-selector');

    // Render each page as a miniature page selector
    for (var pageNumber = 1; pageNumber <= numPages; pageNumber++) {
      // Fetch the page
      pdf.getPage(pageNumber).then(function(page) {
        // Get the viewport of the page at the desired scale
        var viewport = page.getViewport({ scale: scale });

        // Create a canvas for the miniature page selector
        var canvas = document.createElement('canvas');
        canvas.className = 'miniature-page';
      
        canvas.height = viewport.height;
        canvas.width = viewport.width+0.5;

        // Set the canvas context
        var context = canvas.getContext('2d');

        // Render the page on the canvas
        var renderContext = {
          canvasContext: context,
          viewport: viewport
        };
        page.render(renderContext);

        // Add a click event listener to the page selector
        canvas.addEventListener('click', function() {
          // Handle the click event (e.g., navigate to the selected page)
          console.log('Clicked on page ' + page.pageNumber);
          renderPage(page.pageNumber);
          pageNum = page.pageNumber;
        });

        // Append the miniature page selector to the container
        container.appendChild(canvas);
      });
    }
  });
 

</script>

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

<script src="./scripts/consultadocumento.js"></script>

<?php
  }
  ob_end_flush(); //liberar el espacio del buffer
?>