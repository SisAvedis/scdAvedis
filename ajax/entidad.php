<?php
    
    require_once '../modelos/Entidad.php';

    $entidad = new Entidad();

    $identidad=isset($_POST["identidad"])? limpiarCadena($_POST["identidad"]):"";
	$idjurisdiccion=isset($_POST["idjurisdiccion"])? limpiarCadena($_POST["idjurisdiccion"]):"";
	
	$descripcion=isset($_POST["descripcion"])? limpiarCadena($_POST["descripcion"]):"";

    switch($_GET["op"])
    {
        case 'guardaryeditar':
            if (empty($identidad)){
                $rspta=$entidad->insertar($descripcion,$idjurisdiccion);
                echo $rspta ? "Entidad registrada" : "Entidad no se pudo registrar";
            }
            else {
                $rspta=$entidad->editar($identidad,$descripcion);
                echo $rspta ? "Tipo de documento actualizado" : "Tipo de documento no se pudo actualizar";
            }
        break;

        case 'desactivar':
                $rspta = $entidad->desactivar($identidad);
                echo $rspta ? "Tipo de documento desactivado" : "Tipo de documento no se pudo desactivar";
        break;

        case 'activar':
            $rspta = $entidad->activar($identidad);
            echo $rspta ? "Tipo de documento activado" : "Tipo de documento no se pudo activar";
        break;

        case 'mostrar':
            $rspta = $entidad->mostrar($identidad);
            echo json_encode($rspta);
        break;

        case 'listar':
            $rspta = $entidad->listar();
            $data = Array();
            while ($reg = $rspta->fetch_object()) {
                $data[] = array(
                    "0"=> ($reg->condicion) ? 
                        '<button class="btn btn-warning" onclick="mostrar('.$reg->identidad.')" title="Editar Entidad"><li class="fa fa-pencil"></li></button>'.
                        ' <button class="btn btn-danger" onclick="desactivar('.$reg->identidad.')" title="Desactivar Entidad"><li class="fa fa-close"></li></button>'
                        :
                        '<button class="btn btn-warning" onclick="mostrar('.$reg->identidad.')" title="Editar Entidad"><li class="fa fa-pencil"></li></button>'.
                        ' <button class="btn btn-primary" onclick="activar('.$reg->identidad.')" title="Activar Entidad"><li class="fa fa-check"></li></button>'
                        ,
                    "1"=>$reg->descripcion,
                    "2"=>$reg->jurisdiccion,
                 
                    "3"=>($reg->condicion) ?
                         '<span class="label bg-green">Activado</span>'
                         :      
                         '<span class="label bg-red">Desactivado</span>'
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
        case 'selectEntidad':
            $idplanta = $_REQUEST['idplanta'];
            // if($idjurisdiccion == ''){
            //     $idjurisdiccion = '.';
            // }else{
            //     $idjurisdiccion = '^'.$idjurisdiccion.'$';
            // }
           
            $rspta = $entidad->select();

            while($reg = $rspta->fetch_object())
            {
                echo '<option value='.$reg->identidad.'>'
                        .$reg->descripcion.
                      '</option>';
            }
        break;
    }

?>