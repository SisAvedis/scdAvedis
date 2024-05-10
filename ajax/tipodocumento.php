<?php
    
    require_once '../modelos/TipoDocumento.php';

    $tipo_documento = new TipoDocumento();

    $idtipo_documento=isset($_POST["idtipo_documento"])? limpiarCadena($_POST["idtipo_documento"]):"";
	$codigo=isset($_POST["codigo"])? limpiarCadena($_POST["codigo"]):"";
	$descripcion=isset($_POST["descripcion"])? limpiarCadena($_POST["descripcion"]):"";

    switch($_GET["op"])
    {
        case 'guardaryeditar':
            if (empty($idtipo_documento)){
                $rspta=$tipo_documento->insertar($codigo,$descripcion);
                echo $rspta ? "Tipo de documento registrado" : "Tipo de documento no se pudo registrar";
            }
            else {
                $rspta=$tipo_documento->editar($idtipo_documento,$codigo,$descripcion);
                echo $rspta ? "Tipo de documento actualizado" : "Tipo de documento no se pudo actualizar";
            }
        break;

        case 'desactivar':
                $rspta = $tipo_documento->desactivar($idtipo_documento);
                echo $rspta ? "Tipo de documento desactivado" : "Tipo de documento no se pudo desactivar";
        break;

        case 'activar':
            $rspta = $tipo_documento->activar($idtipo_documento);
            echo $rspta ? "Tipo de documento activado" : "Tipo de documento no se pudo activar";
        break;

        case 'mostrar':
            $rspta = $tipo_documento->mostrar($idtipo_documento);
            echo json_encode($rspta);
        break;

        case 'listar':
            $rspta = $tipo_documento->listar();
            $data = Array();
            while ($reg = $rspta->fetch_object()) {
                $data[] = array(
                    "0"=> ($reg->condicion) ? 
                        '<button class="btn btn-warning" onclick="mostrar('.$reg->idtipo_documento.')"><li class="fa fa-pencil"></li></button>'.
                        ' <button class="btn btn-danger" onclick="desactivar('.$reg->idtipo_documento.')"><li class="fa fa-close"></li></button>'
                        :
                        '<button class="btn btn-warning" onclick="mostrar('.$reg->idtipo_documento.')"><li class="fa fa-pencil"></li></button>'.
                        ' <button class="btn btn-primary" onclick="activar('.$reg->idtipo_documento.')"><li class="fa fa-check"></li></button>'
                        ,
                    "1"=>$reg->codigo,
                    "2"=>$reg->descripcion,
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
    }

?>