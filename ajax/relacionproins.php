<?php 
if (strlen(session_id()) < 1) 
  session_start();

require_once "../modelos/RelacionProIns.php";

$relproins=new RelacionProIns();

$idrelproins=isset($_POST["idrelproins"])? limpiarCadena($_POST["idrelproins"]):"";
$idusuario=$_COOKIE["idusuario"];
$idprocedimiento = isset($_POST['iddocumento']) ? $_POST['iddocumento'] : "";
$idinstructivo = isset($_POST['idinstructivo']) ? $_POST['idinstructivo'] : "";
$observacion=isset($_POST["observacion"])? limpiarCadena($_POST["observacion"]):"";
$idsector = isset($_POST['idsector']) ? $_POST['idsector'] : "";

switch ($_GET["op"]){
	case 'guardaryeditar':
		if (empty($idrelproins)){
			$rspta=$relproins->insertar($idusuario,$idprocedimiento,$idinstructivo,$observacion);
			echo $rspta ? "Relación registrada" : "No se pudieron registrar todos los datos de la relación";
		}
		else {
		}
	break;

	case 'anular':
		$rspta=$relproins->anular($idrelproins);
 		echo $rspta ? "Relación anulada" : "Relación no se puede anular";
	break;

	case 'mostrar':
		$rspta=$relproins->mostrar($idrelproins);
 		//Codificar el resultado utilizando json
 		echo json_encode($rspta);
	break;

	case 'listarDetalle':
		//Recibimos el idingreso
		$id=$_GET['id'];

		$rspta = $relproins->listarDetalle($id);
		echo '<thead style="background-color:#A9D0F5">
                                    <th>Opciones</th>
                                    <th>Codigo</th>
                                    <th>Instructivo</th>
									<th>Fecha</th>
                                </thead>';

		while ($reg = $rspta->fetch_object())
				{
					echo '<tr class="filas"><td></td><td>'.$reg->codigoi.'</td><td>'.htmlspecialchars($reg->nombrei).'</td><td>'.htmlspecialchars($reg->fecha).'</td>';
				}
		;
	break;

	case 'listar':
		$rspta=$relproins->listar();
 		//Vamos a declarar un array
 		$data= Array();

		 while ($reg=$rspta->fetch_object())
		 {
			$data[]=array(
 				"0"=> ($reg->estado == 'Aceptado') ? 
                        '<button class="btn btn-warning" onclick="mostrar('.$reg->idrelproins.')"><li class="fa fa-eye"></li></button>'.
                        ' <button class="btn btn-danger" onclick="anular('.$reg->idrelproins.')"><li class="fa fa-close"></li></button>'
                        :
                        '<button class="btn btn-warning" onclick="mostrar('.$reg->idrelproins.')"><li class="fa fa-eye"></li></button>',
 				"1"=>$reg->fecha,
 				"2"=>$reg->usuario,
 				"3"=>$reg->procedimiento,
				"4"=>($reg->estado=='Aceptado')?'<span class="label bg-green">Aceptado</span>':
 				'<span class="label bg-red">Anulado</span>'
 				);
 		}
 		$results = array(
 			"sEcho"=>1, //Información para el datatables
 			"iTotalRecords"=>count($data), //enviamos el total registros al datatable
 			"iTotalDisplayRecords"=>count($data), //enviamos el total registros a visualizar
 			"aaData"=>$data);
 		echo json_encode($results);

	break;
	
	case 'selectSector':
		require_once "../modelos/Sector.php";
		$sector = new Sector();
	
		$rspta = $sector->select('.');
	
		while($reg = $rspta->fetch_object())
		{
			echo '<option value='.$reg->idsector.'>'
					.$reg->nombre.
				'</option>';
		}
    break;
	
	case 'selectDocumento':
		require_once "../modelos/Documento.php";
		$documento = new Documento();
	
		$rspta = $documento->selectPro($idsector);
		
		while($reg = $rspta->fetch_object())
		{
			echo '<option value='.$reg->iddocumento.'>'
					.$reg->codigo.' - '.$reg->nombre.
				'</option>';
		}
    break;
	
	case 'listarDocumentos':
		require_once '../modelos/Documento.php';
        $documento = new Documento();

        $rspta = $documento->listarActivos();
        $data = Array();

        while ($reg = $rspta->fetch_object()) {
            $data[] = array(
               "0"=> '<button class="btn btn-warning" onclick="agregarDetalle('.$reg->iddocumento.',\''.htmlspecialchars($reg->nombre).'\',\''.htmlspecialchars($reg->descripcion).'\',\''.htmlspecialchars($reg->sector).'\',\''.$reg->codigo.')">
                                <span class="fa fa-plus"></span>
                            </button>',
					
                "1"=>$reg->nombre,
				"2"=>$reg->descripcion,
                "3"=>$reg->sector,
                "4"=>$reg->codigo,
				"5"=>"<img src='../files/articulos/".$reg->imagen."' height='50px' width='50px'>"
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
	
	case 'listarInstructivos':
        require_once '../modelos/Documento.php';
        $documento = new Documento();
		$idsector = $_GET['idsector'];
		
        $rspta = $documento->listarInstructivos($idsector);
        $data = Array();

        while ($reg = $rspta->fetch_object()) {
            $data[] = array(
               "0"=>'<button class="btn btn-warning" onclick="agregarDetalle('.$reg->iddocumento.',\''.htmlspecialchars($reg->nombre).'\',\''.htmlspecialchars($reg->descripcion).'\','.$reg->idsector.',\''.htmlspecialchars($reg->sector).'\',\''.$reg->codigo.'\')"><span class="fa fa-plus"></span></button>',
				
			    "1"=>$reg->codigo,
				"2"=>$reg->nombre,
				"3"=>$reg->descripcion,
                "4"=>$reg->sector
                //"5"=>"<img src='../files/articulos/".$reg->imagen."' height='50px' width='50px'>"
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
	
	
	case 'listarInstructivosUno':
        require_once '../modelos/Documento.php';
        $documento = new Documento();
		$iddocsec = $_GET['iddocsec'];
		
        $rspta = $documento->listarInstructivosUno($iddocsec);
        $data = Array();

        while ($reg = $rspta->fetch_object()) {
            $data[] = array(
               "0"=>'<button class="btn btn-warning" onclick="agregarDetalle('.$reg->iddocumento.',\''.htmlspecialchars($reg->nombre).'\',\''.htmlspecialchars($reg->descripcion).'\','.$reg->idsector.',\''.htmlspecialchars($reg->sector).'\',\''.$reg->codigo.'\')"><span class="fa fa-plus"></span></button>',
				
			    "1"=>$reg->codigo,
				"2"=>$reg->nombre,
				"3"=>$reg->descripcion,
                "4"=>$reg->sector
                //"5"=>"<img src='../files/articulos/".$reg->imagen."' height='50px' width='50px'>"
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
	
	case 'listarArticulosVenta':
		require_once "../modelos/Articulo.php";
		$articulo=new Articulo();

		$rspta=$articulo->listarActivosVenta();
 		//Vamos a declarar un array
 		$data= Array();

 		while ($reg=$rspta->fetch_object()){
 			$data[]=array(
 				"0"=>'<button class="btn btn-warning" onclick="agregarDetalle('.$reg->idarticulo.',\''.htmlspecialchars($reg->nombre).'\',\''.htmlspecialchars($reg->descripcion).'\',\''.htmlspecialchars($reg->categoria).'\',\''.$reg->precio_venta.'\')"><span class="fa fa-plus"></span></button>',
 				"1"=>$reg->nombre,
				"2"=>$reg->descripcion,
 				"3"=>$reg->categoria,
 				"4"=>$reg->codigo,
 				"5"=>$reg->stock,
 				"6"=>$reg->precio_venta,
 				"7"=>"<img src='../files/articulos/".$reg->imagen."' height='50px' width='50px' >"
 				);
 		}
 		$results = array(
 			"sEcho"=>1, //Información para el datatables
 			"iTotalRecords"=>count($data), //enviamos el total registros al datatable
 			"iTotalDisplayRecords"=>count($data), //enviamos el total registros a visualizar
 			"aaData"=>$data);
 		echo json_encode($results);
	break;
	
	case 'listarArticulosUno':

            require_once '../modelos/Articulo.php';
            $articulo = new Articulo();
			
			$idartubi=$_GET['idartubi'];	
			$rspta = $articulo->listarActivosUbiUno($idartubi);
            $data = Array();

            while ($reg = $rspta->fetch_object()) {
                $data[] = array(
                    "0"=> '<button class="btn btn-warning" onclick="agregarDetalle('.$reg->idarticulo.','.$reg->idubicacion.',\''.htmlspecialchars($reg->nombre).'\',\''.htmlspecialchars($reg->descripcion).'\',\''.htmlspecialchars($reg->categoria).'\',\''.$reg->codigo.'\',\''.$reg->c_ubi.'\','.$reg->cantidad.')">
                                <span class="fa fa-plus"></span>
                            </button>',
					
                    "1"=>$reg->nombre,
					"2"=>$reg->descripcion,
                    "3"=>$reg->categoria,
                    "4"=>$reg->codigo,
					"5"=>$reg->c_ubi,
                    "6"=>$reg->cantidad,
                    "7"=>"<img src='../files/articulos/".$reg->imagen."' height='50px' width='50px'>"
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
	
}
?>