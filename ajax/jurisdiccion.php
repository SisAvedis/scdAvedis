<?php
    
    require_once '../modelos/Jurisdiccion.php';

    $jurisdiccion = new Jurisdiccion();

    $idjurisdiccion=isset($_POST["idjurisdiccion"])? limpiarCadena($_POST["idjurisdiccion"]):"";
	$codigo=isset($_POST["codigo"])? limpiarCadena($_POST["codigo"]):"";
	$descripcion=isset($_POST["descripcion"])? limpiarCadena($_POST["descripcion"]):"";

    switch($_GET["op"])
    {
        case 'guardaryeditar':
            if (empty($idjurisdiccion)){
                $rspta=$jurisdiccion->insertar($codigo,$descripcion);
                echo $rspta ? "Tipo de documento registrado" : "Tipo de documento no se pudo registrar";
            }
            else {
                $rspta=$jurisdiccion->editar($idjurisdiccion,$codigo,$descripcion);
                echo $rspta ? "Tipo de documento actualizado" : "Tipo de documento no se pudo actualizar";
            }
        break;

        case 'desactivar':
                $rspta = $jurisdiccion->desactivar($idjurisdiccion);
                echo $rspta ? "Tipo de documento desactivado" : "Tipo de documento no se pudo desactivar";
        break;

        case 'activar':
            $rspta = $jurisdiccion->activar($idjurisdiccion);
            echo $rspta ? "Tipo de documento activado" : "Tipo de documento no se pudo activar";
        break;

        case 'mostrar':
            $rspta = $jurisdiccion->mostrar($idjurisdiccion);
            echo json_encode($rspta);
        break;

        case 'listar':
            $rspta = $jurisdiccion->listar();
            $data = Array();
            while ($reg = $rspta->fetch_object()) {
                $data[] = array(
                    "0"=> ($reg->condicion) ? 
                        '<button class="btn btn-warning" onclick="mostrar('.$reg->idjurisdiccion.')" title="Editar Jurisdicción"><li class="fa fa-pencil" ></li></button>'.
                        ' <button class="btn btn-danger" onclick="desactivar('.$reg->idjurisdiccion.')" title="Desactivar Jurisdicción"><li class="fa fa-close" ></li></button>'
                        :
                        '<button class="btn btn-warning" onclick="mostrar('.$reg->idjurisdiccion.')" title="Editar Jurisdicción"><li class="fa fa-pencil"></li></button>'.
                        ' <button class="btn btn-primary" onclick="activar('.$reg->idjurisdiccion.')" title="Activar Jurisdicción"><li class="fa fa-check"></li></button>'
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
        case 'selectJurisdiccion':
			
            $rspta = $jurisdiccion->listar();

            while($reg = $rspta->fetch_object())
            {
                echo '<option value='.$reg->idjurisdiccion.'>'
                        .$reg->descripcion.
                      '</option>';
            }
        break;
    }

?>