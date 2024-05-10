<?php
    
    require_once '../modelos/Documentacion.php';

    $documentacion = new Documentacion();

    $iddocumentacion=isset($_POST["iddocumentacion"])? limpiarCadena($_POST["iddocumentacion"]):"";
    $identidad=isset($_POST["identidad"])? limpiarCadena($_POST["identidad"]):"";
	$idplanta=isset($_POST["idplanta"])? limpiarCadena($_POST["idplanta"]):"";
	$descripcion=isset($_POST["descripcion"])? limpiarCadena($_POST["descripcion"]):"";
	$vigencia=isset($_POST["vigencia"])? limpiarCadena($_POST["vigencia"]):"";
	$idusuario=$_COOKIE["idusuario"];

    switch($_GET["op"])
    {
        case 'guardaryeditar':
            if (empty($iddocumentacion)){
                $rspta=$documentacion->insertar($descripcion,$identidad,$idplanta,$idusuario);
                echo $rspta ? "Documentacion registrada" : "Documentación no se pudo registrar";
            }
            else {
                $rspta=$documentacion->editar($iddocumentacion,$descripcion,$identidad,$idplanta);
                echo $rspta ? "Documentación actualizada" : "Documentación no se pudo actualizar";
            }
        break;

        case 'desactivar':
            $motivoAnulacion= $_REQUEST["motivoAnulacion"];
                $rspta = $documentacion->desactivar($iddocumentacion,$motivoAnulacion,$idusuario);
                echo $rspta ? "Documentación desactivada" : "Documentación no se pudo desactivar";
        break;

        case 'activar':
            $rspta = $documentacion->activar($iddocumentacion);
            echo $rspta ? "Documentación activada" : "Documentación no se pudo activar";
        break;
        case 'finalizar':
            $rspta = $documentacion->finalizar($iddocumentacion);
            echo $rspta ? "Documentación finalizada" : "Documentación no se pudo finalizar";
        break;

        case 'mostrar':
            $rspta = $documentacion->mostrar($iddocumentacion);
            echo json_encode($rspta);
        break;

        case 'listar':
            // $idjurisdiccion = $_REQUEST['idjurisdiccion'];
            $idplanta = $_GET['idplanta'];
            $identidad = $_GET['identidad'];
            if($idplanta == ''){
                $idplanta = '.';
            }else{
                $idplanta = '^'.$idplanta.'$';
            }
            // if($idlocalidad == ''){
            //     $idlocalidad = '.';
            // }else{
            //     $idlocalidad = '^'.$idlocalidad.'$';
            // }
            if($identidad == ''){
                $identidad = '.';
            }else{
                $identidad = '^'.$identidad.'$';
            }
            $rspta = $documentacion->listar($identidad,$idplanta);

            $condicion = ["0"=>'<span class="label bg-red">Anulado</span>',
            "1"=>'<span class="label bg-yellow">Vigente</span>',
            "2"=>'<span class="label bg-green">Finalizado</span>'];
            $data = Array();
            while ($reg = $rspta->fetch_object()) {
                $data[] = array(
                    "0"=>(($reg->idusuario == $idusuario)? ((intval($reg->condicion) > 0) ? 
                        '<button  class="btn btn-warning" onclick="mostrar('.$reg->iddocumentacion.')" title="Editar Documentación"><li class="fa fa-pencil"></li></button>'.
                        ' <button  class="btn btn-danger" onclick="desactivar('.$reg->iddocumentacion.')" title="Anular Documentación"><li class="fa fa-close"></li></button>'
                        .(($reg->condicion == "1")?' <button  class="btn btn-success" onclick="finalizar('.$reg->iddocumentacion.')" title="Finalizar Documentación"><li class="fa fa-check"></li></button>':'')
                        :
                        '<button  class="btn btn-danger" type="button" onclick="bootbox.alert(\'Anulado Por: '.$reg->anuladoPor.' <br>Motivo: '.$reg->motivoAnulacion.'\')" title="Ver Motivo de Anulación"><i class="fa fa-eye"></i></button>'.
                        ' <button  class="btn btn-primary" onclick="activar('.$reg->iddocumentacion.')" title="Activar Documentación"><li class="fa fa-power-off"></li></button>')
                    :(($reg->condicion == "0")?'<button  class="btn btn-danger" type="button" onclick="bootbox.alert(\'Anulado Por: '.$reg->anuladoPor.' <br>Motivo: '.$reg->motivoAnulacion.'\')" title="Ver Motivo de Anulación"><i class="fa fa-eye"></i></button>':
                    '<button  class="btn btn-warning" onclick="mostrar('.$reg->iddocumentacion.')" style="display:none"><li class="fa fa-pencil"></li></button>'))
                        ,
                    "1"=>$reg->descripcion,
                    "2"=>$reg->entidad,
                   
                    "3"=>(($reg->idplanta == 1)?'Avedis':(($reg->idplanta == 2)?'María':'Comargas')),
                    "4"=>$condicion[$reg->condicion],
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