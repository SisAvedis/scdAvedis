<?php
  //Activacion de almacenamiento en buffer

use \setasign\Fpdi\Fpdi;

  ob_start();
  
  if(strlen(session_id()) < 1) //Si la variable de session no esta iniciada
  {
    session_start();
  } 

  if(!isset($_COOKIE["nombre"]))
  {
    echo 'Debe ingresar al sistema correctamente para visualizar el reporte';
  }

  else  //Agrega toda la vista
  {

    if($_COOKIE['consulta'] == 1)
    {
        //Incluimos archivo Factura.php
        require_once 'Documento.php';
        require_once '../modelos/Entrega.php';
        require('../FPDI-2.3.7/src/autoload.php');
        //Datos
        //     $logo = "VectorAvedis.png";
        //     $ext_logo = "png";
        //     $empresa = "avedis s_logo.png";
        //     $ext_empresa = "png";
        //     $documento = "Tecno Agro Vial S.A.";
        //     $direccion = "Ruta de la Tradición 4699 - Buenos Aires";
        //     $telefono = "011-4693-4008 / 4009 / 5226";
        //     $email = "info@avedis.com.ar";
        //     $codigoPostal = "(B1839FQF) 9 DE ABRIL - E. Echeverría - Bs. As.";
        
        //     //Obtenemos los datos de la cabecera
        //     require_once '../modelos/AnalisisNew.php';
    //     $analisis = new Analisis();
    
    //     $rsptav = $analisis->listarPdf($_GET['id']);
    
    //     //Recorremos los datos obtenidos
    //     $regv = $rsptav->fetch_object();
    
    // $pdf = new PDF_Invoice('P','mm','A4');
    $pdf = new Fpdi();
    $entrega = new Entrega();
    // $pdf = new PDFMerger\PDFMerger;
    // $pdf->addPDF('../uploads/completo.pdf','1');
    // $pdf->merge('browser');
    $enlace = 'CopiasControladasPON.pdf';
    $cantidadPaginas = $pdf->setSourceFile($enlace);
      $rspta = $entrega->listarEntregas($_REQUEST['id']);

        
      
      
      for ($contadorPaginas = 1; $contadorPaginas <= $cantidadPaginas; $contadorPaginas++){
        
        $pdf-> AddPage('l');
        $pdf->SetAutoPageBreak(true, 0);
        $template = $pdf->importPage($contadorPaginas);
        $pdf->useImportedPage($template,0,0);
        
        $pdf->SetTextColor(0,0,0);
        $pdf->SetFont('Arial', '', 15);
        $contador = 0;
        while( $reg = $rspta->fetch_object()){
          $textWidth = $pdf->GetStringWidth(strtoupper($reg->fecha_actual));
          if($contador > 10){
            $pdf->AddPage('l');
            $pdf->SetAutoPageBreak(true, 0);
            $template = $pdf->importPage($contadorPaginas);
            $pdf->useImportedPage($template,0,0);
            $pdf->SetFont('Arial', '', 15);
            $pdf->SetTextColor(0,0,0);
            $pdf->SetFont('Arial', '', 8);
            $x = (248 - 200 - $textWidth) / 2 + 200;
            $pdf->Text($x, 41, strtoupper($reg->fecha_actual));
            $pdf->SetFont('Arial', '', 15);
            $contador = 0;
          }
          
          $pdf->SetFont('Arial', '', 8);
          $x = (248 - 200 - $textWidth) / 2 + 200;
          $pdf->Text($x, 41, strtoupper($reg->fecha_actual));
          $pdf->SetFont('Arial', '', 15);
          $name_parts = explode(" ", $reg->nombre);
          
          // Determine the number of name parts
          $num_name_parts = count($name_parts);
          
          // Initialize variables for first name, middle name, and last name
          $first_name = "";
          $middle_name = "";
          $last_name = "";
          
          // Check the number of name parts and assign values accordingly
          if ($num_name_parts == 1) {
    // Only one name part, assume it's the first name
    $first_name = $name_parts[0];
} elseif ($num_name_parts == 2) {
    // Two name parts, assume the first part is the first name and the second part is the last name
    $first_name = $name_parts[1];
    $last_name = $name_parts[0];
} elseif ($num_name_parts >= 3) {
    // Three or more name parts, assume the first part is the first name,
    // the last part is the last name, and the parts in between are the middle name(s)
    $last_name = $name_parts[0];
    $first_name = $name_parts[$num_name_parts - 2];
    for ($i = 1; $i < $num_name_parts - 1; $i++) {
        $middle_name .= $name_parts[$i] . " ";
    }
    $middle_name = trim($middle_name);
}


          $pdf->Text(26, 67 + $contador * 11, $contador+1);
          $pdf->Text(48, 67 + $contador * 11, $reg->titulo);
          $pdf->Text(97, 67 + $contador * 11, utf8_decode(substr($reg->descripcion,0,strpos($reg->descripcion,' '))));
          $pdf->Text(135, 67 + $contador * 11, $reg->vigencia);
          $pdf->Text(167, 67 + $contador * 11, $reg->num_revision);
          $first_name = substr($first_name,0,1).'.';
          $last_name = ucfirst( strtolower($last_name));
          if ($middle_name != ""){

            $middle_name = substr($middle_name,0,1).'.';
          }
          if(strstr($reg->nombre, '0|') == true){
            $reg->nombre = substr($reg->nombre,2);
            $pdf->Text(180, 67 + $contador * 11, utf8_decode($reg->nombre));
          }else{
            $pdf->Text(180, 67 + $contador * 11, utf8_decode($first_name.' '.$last_name));

          }
          $contador++;
        }
   }
   

      
    //     //Enviamos los datos de la empresa al metodo addSociete de la clase factura
    //     //Para ubicar los datos correspondientes
    //     $pdf->addSociete(
      //         utf8_decode($empresa),
      //         $ext_empresa,
      
    //         $documento."\n"
    //         .utf8_decode("Dirección: ").utf8_decode($direccion)."\n".
    //         utf8_decode($codigoPostal)."\n".
    //         utf8_decode("Telefax: ").utf8_decode($telefono)."\n".
    //         "Email: ".$email
    //         ,
    //         $logo,
    //         $ext_logo,
            
    //     );
        
    //     $pdf->fact_dev(
    //         utf8_decode("Nº "),
    //         "$regv->PCPI"
    //     );

    //     $pdf->temporaire( "" ); //Marca de Agua
    //     $pdf->addDate($regv->dia);

    //     $pdf->addTitle();

    //     //Enviar los datos del cliente al metodo addClienteAdresse de la clase Factura
    //     // $pdf->addCabecera($regv->dia, $regv->partida, $regv->PCPI, utf8_decode($regv->analista), $regv->num_item, strtoupper($regv->tipo)

    //     // );

    //     //Establecemos las columnas que va a tener la seccion donde mostramos los detalles de la venta
    //     $cols=array(
    //         utf8_decode("ITEM")=>11,
    //         "HORA"=>13,
    //         "PDTO"=>16,
    //         "ORIGEN"=>24,
    //         "PARTIDA"=>21,
    //         "DESTINO"=>16,
    //         "\ePARTIDA"=>16,
    //         "O2"=>12.5,
    //         "CO"=>12.5,
    //         "CO2"=>12.5,
    //         "H2O"=>12.5,
    //         "ANALISTA"=>26
    //     );
    //     $cols2=array(
    //         utf8_decode("\0")=>11,
    //         " "=>13,
    //         ""=>16,
    //         "Prov.Ext./TAV/CIS"=>24,
    //         "ORIGEN"=>21,
    //         "LOT/TAV/CIS"=>16,
    //         "DESTINO"=>16,
    //         // "\n"=>13,
    //         // ""=>13,
    //         // ""=>13,
    //         // ""=>13,
    //         // "\0"=>26.2
    //     );
    //     $pdf->addCols($cols, $cols2);

    //     $cols = array(
    //         utf8_decode("ITEM")=>'C',
    //         "HORA"=>'C',
    //         "PDTO"=>'C',
    //         "ORIGEN"=>'C',
    //         "PARTIDA"=>'C',
    //         "DESTINO"=>'C',
    //         "\ePARTIDA"=>'C',
    //         "O2"=>'C',
    //         "CO"=>'C',
    //         "CO2"=>'C',
    //         "H2O"=>'C',
    //         "ANALISTA"=>'C'
    //     );
    //     $cols2=array(
    //         utf8_decode("\0")=>'C',
    //         " "=>'C',
    //         ""=>'C',
    //         "PE/TAV/CIS"=>'C',
    //         "ORIGEN"=>'C',
    //         "LOT/TAV/CIS"=>'C',
    //         "DESTINO"=>'C',
    //         // "\n"=>13,
    //         // ""=>13,
    //         // ""=>13,
    //         // ""=>13,
    //         // "\0"=>26.2
    //     );
    //     $pdf->addLineFormat($cols);
    //     $pdf->addLineFormat($cols2);

    //     //Actualizamos el valos de la coordenada "y", que sera la ubicacion desde donde empezaremos a mostrar los datos
    //     $y = 74;
    //     //Obtenemos todos los detalles de la venta actual
    //     $rsptad = $analisis->listarPdf($_GET['id']);
    //     $contadorFilas = 1;
      
    //       while($contadorFilas != 21 and   $regd = $rsptad->fetch_object())
    //       {

    //         $line2 = array(
    //             utf8_decode("ITEM")=>$contadorFilas,
    //             "HORA"=>$regd->hora,
    //             "PDTO"=>utf8_decode($regd->tipoproducto),
    //             "ORIGEN"=>$regd->lote_origen,
    //             "PARTIDA"=>$regd->partida_origen,
    //             "DESTINO"=>$regd->lote,
    //             "\ePARTIDA"=>$regd->partida,
    //             "O2"=>$regd->O2,
    //             "CO"=>$regd->CO,
    //             "CO2"=>$regd->CO2,
    //             "H2O"=>$regd->H2O,
    //             "ANALISTA"=>utf8_decode($regd->analistaPdf)
    //         );
    //         $size = $pdf->addLine($y,$line2, 'B');
    //         $y += $size + 4;

            
    //         // $line2 = array(
    //         //     utf8_decode("DETERMINACIÓN")=>utf8_decode("CO (Monóxido de Carbono)"),
    //         //     "RESULTADOS"=>utf8_decode($regv->CO)." ppm",
    //         //     "ESPECIFICACIONES"=>"Menor a 5 ppm"
    //         // );
    //         // $size = $pdf->addLine($y,$line2, 'B');
    //         // $y += $size + 4;
    //         // $line2 = array(
    //         //     utf8_decode("DETERMINACIÓN")=>utf8_decode("CO2 (Dióxido de Carbono)"),
    //         //     "RESULTADOS"=>utf8_decode($regv->CO2)." ppm",
    //         //     "ESPECIFICACIONES"=>"Menor a 300 ppm"
    //         // );
    //         // $size = $pdf->addLine($y,$line2, 'B');
    //         // $y += $size + 4;
    //         // $line2 = array(
    //         //     utf8_decode("DETERMINACIÓN")=>"Humedad",
    //         //     "RESULTADOS"=>utf8_decode($regv->H2O)." ppm",
    //         //     "ESPECIFICACIONES"=>"Menor a 67 ppm"
    //         // );
    //         // $size = $pdf->addLine($y,$line2, 'B');
    //         // $y += $size + 4;
    //         // $line2 = array(
    //         //     utf8_decode("DETERMINACIÓN")=>utf8_decode(" "),
    //         //     "RESULTADOS"=>"-",
    //         //     "ESPECIFICACIONES"=>"No detecta"
    //         // );
    //         // $size = $pdf->addLine($y,$line2, 0);
    //         // $y += $size + 5;
           
    //             $contadorFilas++;
    //       }
    //       if($contadorFilas< 21){
    //       while($contadorFilas < 21){
    //         $line2 = array(
    //             utf8_decode("ITEM")=>$contadorFilas,
    //             "HORA"=>'',
    //             "PDTO"=>'',
    //             "ORIGEN"=>'',
    //             "PARTIDA"=>'',
    //             "DESTINO"=>'',
    //             "\ePARTIDA"=>'',
    //             "O2"=>'',
    //             "CO"=>'',
    //             "CO2"=>'',
    //             "H2O"=>'',
    //             "ANALISTA"=>''
    //         );
    //         $size = $pdf->addLine($y,$line2, 'B');
    //         $y += $size + 4;
    //       $contadorFilas++;}}

    //       //Recorremos los datos obtenidos

         
    //  // $rsptad = $certificado->certificadoCabecera($_GET['id']);
    // //  $pdf->addDescriptionText(
    // //     utf8_decode("OLOR: no detectable"),
    // //     utf8_decode("Ensayos de identificación: positivo"),
    // //     utf8_decode("El PDTO fue obtenido por licuefacción del aire"),
    // //     utf8_decode("y cumple con especificaciones descriptas"),
    // //     utf8_decode("en Farmacopea Nacional Argentina"),
    // //       utf8_decode("6ta Edición de 1978.")
        
    // // );
    // //  $pdf->addOperadorAdresse(
    // //     utf8_decode($regv->analista)
        
    // // );
    //   $pdf->addLibroyFojas(
    //     $regv->libro,$regv->fojas);
        
    // // );
    // //  $pdf->addVencimientoAdresse(
    // //     utf8_decode($regv->fechaVencimiento)
        
    // // );
    // // $pdf->addSello(
    // //     utf8_decode("TECNO AGRO VIAL\nFarm. Andrea F. Campos\nMP 15197\nDT Planta Bs.As.")
    // // );

 $nombreArchivo = "Planilla_PCPI.pdf";

     $pdf->Output($nombreArchivo,'I');
    } 

    else
    {
        echo 'No tiene permiso para visualizar el reporte';
    }


   }
   ob_end_flush(); //liberar el espacio del buffer
?>