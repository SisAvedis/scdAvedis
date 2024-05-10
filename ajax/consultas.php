<?php
    
    require_once '../modelos/Consultas.php';

    $consulta = new Consultas();
    require_once '../modelos/Persona.php';

    $persona = new Persona();

    $idimpresion=isset($_POST["idimpresion"])? $_POST["idimpresion"]:"";
    $entregado=isset($_POST["entregado"])?$_POST["entregado"]:"0";
    $idusuario=$_COOKIE["idusuario"];

    switch($_GET["op"])
    {

        case 'consultadocumento':
			$idsector = $_GET['idsector'];
            $rspta = $consulta->consultadocumento($idsector);
            $data = Array();

            while ($reg = $rspta->fetch_object()) {
                $data[] = array(
                    "0"=>$reg->sector,
                    "1"=>$reg->nombre,
                    "2"=>$reg->descripcion,
                    "3"=>$reg->vigencia,
                    "4"=>($reg->condicion == '1') ?
						 '<button class="btn btn-warning" onclick="mostrar('.$reg->iddocumento.')"><li class="fa fa-eye"></li></button></a>'
						 //'<a target="_blank" href="'.$reg->carpeta.$reg->fuente.'" <button class="btn btn-success" ><li class="fa fa-eye"></li></button></a>'
						 //'<span class="label bg-green">Aceptado</span>'
                         :      
                         '<span class="label bg-red"></span>'
                );
            }
            $results = array(
                "sEcho"=>1, //Informacion para el datable
                "iTotalRecords" =>count($data), //enviamos el total de registros al datatable
                "iTotalDisplayRecords" => count($data), //enviamos el total de registros a visualizar
                "aaData" =>$data
            );
            echo json_encode($results);
         
        break;
        case 'documentosAVencer':
            $rspta = $consulta->consultadocumentoAVencer(
                
            );
            $data = Array();

            while ($reg = $rspta->fetch_object()) {
                $mesesPorAnio = ((intval($reg->meses) / 12) - intval($reg->anios))*12; 
                switch($reg->condicion){
                    case '0':
                        $estado = '<span class="label bg-red">Ajustes</span>';
                    break;
                    case '1':
                        $estado = '<span class="label bg-yellow">Para Aprobar</span>';
                    break;
                    case '2':
                        $estado = '<span class="label bg-green">Aprobado</span>';
                    break;
                    case '3':
                        $estado = '<span class="label bg-teal">Para Pagar</span>';
                        break;
                    case '4':
                        $estado = '<span class="label bg-purple">En Revisión</span>';
                    break;
                    default:
                    $condicion = explode('|',$reg->condicion);
                    $hexColor = ltrim($condicion[1], '#');

                    // Convert the hexadecimal color to RGB
                    $rgb = sscanf($hexColor, "%2x%2x%2x");
                
                    // Calculate the average RGB value
                    $averageRgb = array_sum($rgb) / 3;
                
                    // Determine if the color is light or dark based on the average RGB value
                    $isDark = $averageRgb < 150;
                
                    $color = $isDark ? 'color:white' : 'color:black';
                    $estado = '<span class="label" style="background:'.$condicion[1].';'.$color.'">'.$condicion[0].'</span>';
                break;
                }
                $data[] = array(
                    "0"=>$reg->codigo,
                    "1"=>$reg->documentacion,
                    "2"=>$reg->descripcion,
                    "3"=>(($reg->vigencia !== null)?$reg->vigencia:'-'),
                    "4"=>(($reg->vigencia !== null)?(($reg->meses <= 3 || $reg->fecha_actual >= $reg->fecha_preaviso) ?
                    (($reg->meses <1) ?
                    (($reg->dias >=1)?
                    (($reg->dias ==1)?'<span class="label bg-red">'.$reg->dias.' dia</span>'
                    :'<span class="label bg-red">'.$reg->dias.' dias</span>')
                    :'<span class="label bg-red">Vencido</span>')
                    : (($reg->meses == 1)?'<span class="label bg-red">'.$reg->meses.' mes</span>'
                    : '<span class="label bg-red">'.$reg->meses.' meses</span>' ))

                    :(($reg->meses >6) ?
                    (($reg->meses >11) ?(($reg->anios == 1) ?
                    '<span class="label bg-green">'.$reg->anios.' año'
                    :'<span class="label bg-green">'.$reg->anios.' años')
                    .' y '.$mesesPorAnio.(($mesesPorAnio == 1)?' mes':' meses').'</span>'
                    :'<span class="label bg-green">'.$reg->meses.' meses</span>')
                    :  '<span class="label bg-yellow">'.$reg->meses.' meses</span>')):'-'),
                    "5"=>$estado

                );
                
            }
            $results = array(
                "sEcho"=>1, //Informacion para el datable
                "iTotalRecords" =>count($data), //enviamos el total de registros al datatable
                "iTotalDisplayRecords" => count($data), //enviamos el total de registros a visualizar
                "aaData" =>$data
            );
            echo json_encode($results);
         
        break;
    
        case 'consultadocumentoEntidad':
			$iddocumentacion = $_GET['iddocumentacion'];
			$idusuarioQuery = $_GET['idusuario'];
            $rspta = $consulta->consultadocumento($iddocumentacion);
            $data = '';
            $data .= '<table id="tbldocumentos" 
         class="table-responsive" style="width:95%">';
        $data .= '<thead>';
        $data .= '<tr>';
        $data .= '<th >Opciones</th>';
        $data .= '<th >Fecha Preaviso</th>';
        $data .= '<th >Código</th>';
        $data .= '<th >Descripción</th>';
        $data .= '<th >Vencimiento</th>';
        $data .= '<th >Estado</th>';
        $data .= '</tr>';
        $data .= '</thead>';
        $data .= '<tbody>';
        if($rspta->num_rows > 0){
            while ($reg = $rspta->fetch_object()) {
                switch($reg->condicion){
                    case '0':
                        $estado = '<span class="label bg-red">Ajustes</span>';
                    break;
                    case '1':
                        $estado = '<span class="label bg-yellow">Para Aprobar</span>';
                    break;
                    case '2':
                        $estado = '<span class="label bg-green">Aprobado</span>';
                    break;
                    case '3':
                        $estado = '<span class="label bg-teal">Para Pagar</span>';
                        break;
                    case '4':
                        $estado = '<span class="label bg-purple">En Revisión</span>';
                        break;
                        default:
                        $condicion = explode('|',$reg->condicion);
                        $hexColor = ltrim($condicion[1], '#');

                        // Convert the hexadecimal color to RGB
                        $rgb = sscanf($hexColor, "%2x%2x%2x");
                    
                        // Calculate the average RGB value
                        $averageRgb = array_sum($rgb) / 3;
                    
                        // Determine if the color is light or dark based on the average RGB value
                        $isDark = $averageRgb < 150;
                    
                        $color = $isDark ? 'color:white' : 'color:black';
                        $estado = '<span class="label" style="background:'.$condicion[1].';'.$color.'">'.$condicion[0].'</span>';
                    break;
                }
                $data .= '<tr >';
                $data .= 
                '<td>'
                .(($reg->fuente != '')?'<a target="_blank" href="'.$reg->carpeta.$reg->fuente.'"> <button  class="btn btn-info" title="Ver Documento"><i class="fa fa-file"></i></button></a>':'')
                .(($reg->condicion != '0' && $idusuarioQuery == $idusuario) ?
                ' <a data-toggle="modal" href="#formDocumento"><button  class="btn btn-warning" onclick="$(\'#iddocumentacionDocumento\').val('.$iddocumentacion.'),mostrarDocumento('.$reg->iddocumento.')" title="Editar Documento"><i class="fa fa-pencil"></i></button></a>'
                .' <button  class="btn btn-success" onclick="cambiarEstadoDocumento('.$reg->iddocumento.')" title="Cambiar Estado del Documento"><i class="fa fa-list"></i></button>'
                .' <button  class="btn btn-danger" onclick="desactivarDocumento('.$reg->iddocumento.')" title="Anular Documento"><i class="fa fa-close"></i></button>'
                : '' )
                .(($reg->condicion == '0')?
                (($idusuarioQuery == $idusuario)?
                ' <button  class="btn btn-success" onclick="cambiarEstadoDocumento('.$reg->iddocumento.')" title="Cambiar Estado del Documento"><i class="fa fa-list"></i></button>'
                .' <button  class="btn btn-danger" type="button" onclick="bootbox.alert(\'Anulado Por: '.$reg->anuladoPor.' <br>Motivo: '.$reg->motivoAnulacion.'\')" title="Ver Motivo de Anulación"><i class="fa fa-eye"></i></button>'
                :
                ' <button  class="btn btn-danger" type="button" onclick="bootbox.alert(\'Anulado Por: '.$reg->anuladoPor.' <br>Motivo: '.$reg->motivoAnulacion.'\')" title="Ver Motivo de Anulación"><i class="fa fa-eye"></i></button>'
                ):'').'</td>'.
                '<td>'.(($reg->fecha_preaviso !==null)?$reg->fecha_preaviso:'-').'</td>'
                .'<td>'.$reg->codigo.'</td>'
                .'<td>'.$reg->descripcion.'</td>'
                .'<td>'.(($reg->vigencia !== null)?$reg->vigencia:'-').'</td>'
                .'<td>'.$estado.'</td>';
                $data .= '</tr>';
                
            }
        }else{
            $data .= '<tr >';
            $data .= '<td></td>';
            $data .= '<td></td>';
            $data .= '<td>-- No hay Datos --</td>';
            $data .= '<td></td>';
            $data .= '<td></td>';
            $data .= '<td></td>';
            $data .= '</tr>';
        }
            if($idusuarioQuery == $idusuario){

                $data .= '<tr >';
                $data .= '<td><a data-toggle="modal" href="#formDocumento"><button class="btn btn-success" onclick="$(\'#iddocumentacionDocumento\').val('.$iddocumentacion.')" title="Agregar Documento" ><i class="fa fa-plus"></i></button></a></td>';
                $data .= '<td>Agregar</td><td></td><td></td><td></td><td></td>';
                $data .= '</tr>';
            }
           $data .= '</tbody>';
           $data .= '</table>';
            echo $data;
        break;
        case 'consultadocumentoExcel':
			$idsector = $_GET['idsector'];
            $rspta = $consulta->consultadocumentoExcel($idsector);
            $data = Array();

            while ($reg = $rspta->fetch_object()) {
                $data[] = array(
                    "0"=>$reg->sector,
                    "1"=>$reg->nombre,
                    "2"=>$reg->descripcion,
                    "3"=>$reg->vigencia,
                    "4"=>'Activo'
                );
            }
            $results = array(
                "sEcho"=>1, //Informacion para el datable
                "iTotalRecords" =>count($data), //enviamos el total de registros al datatable
                "iTotalDisplayRecords" => count($data), //enviamos el total de registros a visualizar
                "aaData" =>$data
            );
            echo json_encode($results);
        break;
        case 'consultaimpresiones':
			 $iddocumento = $_GET['iddocumento'];
			//  $dias_preaviso= $_GET['dias_preaviso'];
            $rspta = $consulta->consultaimpresiones($iddocumento);
            $data = Array();
            $cont = 0;

            while ($reg = $rspta->fetch_object()) {
                $data[] = array(
                    "0"=>$reg->usuario.'<input  name="idimpresion[]" type="hidden" value="'.$reg->idimpresion.'" >',
                    "1"=>$reg->documento,
                    "2"=>$reg->fecha_vigencia,
                    "3"=>$reg->fecha_preaviso,
                    "4"=>($reg->vigente == '1') ? 'Sí': 'No',
                    "5"=>$reg->impresiones,
                    "6"=>'<button class="btn btn-success" onclick="listarEntregas('.$reg->idimpresion.')"><i class="fa fa-eye"></i></button>'
                );
                $cont++;
            }
            $results = array(
                "sEcho"=>1, //Informacion para el datable
                "iTotalRecords" =>count($data), //enviamos el total de registros al datatable
                "iTotalDisplayRecords" => count($data), //enviamos el total de registros a visualizar
                "aaData" =>$data
            );
            echo json_encode($results);
        break;
        case 'consultaentregas':
			 $idimpresion = $_GET['idimpresion'];
            $rspta = $consulta->consultaentregas($idimpresion);
            $data = Array();
            $cont = 0;

            while ($reg = $rspta->fetch_object()) {
                $data[] = array(
                    "0"=>'<button class="btn btn-danger" onclick="deleteDetalle('.$reg->identrega.')"><i class="fa fa-close"></i>
                    </button>',
                    "1"=>$cont+1
                    .'<input  id="cont'.$cont.'" type="hidden" value="'.($cont+1).'" >',
                    "2"=>$reg->nombre
                    .'<input  name="identrega[]" type="hidden" value="'.$reg->identrega.'" >'
                    .'<input  name="idimpresion[]" type="hidden" value="'.$reg->idimpresion.'" >',
                    "3"=>($reg->entregado == 1)
                    ?'<input  name="entregado[]" id="entregado'.$cont.'" type="hidden" value="'.$reg->entregado.'" >'
                    .'<input type="checkbox" id="entregadoCheck" onclick="checkHandler('.$cont.',$(this))" style="width:20px;height:20px;"  checked>'
                    :'<input  name="entregado[]" id="entregado'.$cont.'" type="hidden" value="'.$reg->identrega.'" >'
                    .'<input type="checkbox" id="entregadoCheck" onclick="checkHandler('.$cont.',$(this))" style="width:20px;height:20px;"  >'
                    ,
                );
                $cont++;
            }
            $results = array(
                "sEcho"=>1, //Informacion para el datable
                "iTotalRecords" =>count($data), //enviamos el total de registros al datatable
                "iTotalDisplayRecords" => count($data), //enviamos el total de registros a visualizar
                "aaData" =>$data
            );
            echo json_encode($results);
        break;
		
		case 'listarDetalle':
		//Recibimos el iddocumento
		$id=$_GET['id'];
        

		$rspta = $consulta->listarDetalle($id);
		echo '<thead style="background-color:#A9D0F5">
                                    <th>Opciones</th>
                                    <th>Codigo</th>
									<th>Descripcion</th>
									<th>Fecha de Vigencia</th>
									<th>Fecha</th>
                                </thead>';

		while ($reg = $rspta->fetch_object())
				{
					if($reg->ruta == ''){
						echo '<tr class="filas"><td><td>'.$reg->nombre.'</td><td>'.htmlspecialchars($reg->nombre).'</td><td>'.htmlspecialchars($reg->descripcion).'</td>';
					}else{
                        //if($reg->idtipo_documento > 1){
						echo ($reg->idtipo_documento == 1)?
                        '<tr class="filas" style="background-color:LemonChiffon; border: black 5px">

                        <td><a target="_blank" href="visorpdf.php?file='.$reg->ruta.'" <button class="btn btn-success" >
                        <li class="fa fa-eye"></li></button></a></td>
                        <td>'.$reg->nombre.'</td>
                        <td>'.htmlspecialchars($reg->descripcion).'</td>'
                        .'<td>'.htmlspecialchars($reg->vigencia).'</td>'
                        .'<td>'.$reg->fecha.'</td></tr>'
                        :'<tr class="filas"><td><a target="_blank" href="visorpdf.php?file='.$reg->ruta.'" <button class="btn btn-success" >
                        <li class="fa fa-eye"></li></button></a></td>
                        <td>'.$reg->nombre.'</td>
                        <td>'.htmlspecialchars($reg->descripcion).'</td>'
                        .'<td>'.htmlspecialchars($reg->vigencia).'</td>'
                        .'<td>'.$reg->fecha.'</td></tr>';
                       /* }
                        else{
                          echo  '<tr class="filas"><td><a target="_blank" href="'.$reg->ruta.'" <button class="btn btn-success" ><li class="fa fa-eye"></li></button></a></td><td>'.$reg->nombre.'</td><td>'.htmlspecialchars($reg->nombre).'</td><td>'.htmlspecialchars($reg->descripcion).'</td>';
                        }*/
					}
						
					
					//echo '<tr class="filas"><td></td><td>'.$reg->nombre.'</td><td>'.htmlspecialchars($reg->nombre).'</td><td>'.htmlspecialchars($reg->descripcion).'</td>';
				}
		;
	break;

    case 'selectPersona':
        $rspta=$persona->listarPA();
        while($reg = $rspta->fetch_object())
					{
						echo '<option value='.$reg->idpersona.'>'
                        .$reg->nombre.
						'</option>';
            }
    break;

    
    }

?>