<?php
    
    require_once '../modelos/Entrega.php';

    $entrega = new Entrega();

    $identrega=isset($_POST["identrega"])? $_POST["identrega"]:"";
    $idimpresion=isset($_POST["idimpresion"])? $_POST["idimpresion"]:"";
    $entregado=isset($_POST["entregado"])?$_POST["entregado"]:"0";
    $idoperario=isset($_POST["idoperario"])?$_POST["idoperario"]:"";

    switch($_GET["op"])
    {
        case 'guardaryeditar':
            // if ($identrega){

                    $rspta=$entrega->insertar($idoperario,$idimpresion,$entregado);
				echo $rspta ? "Entregas registradas" : "Entregas no se pueden registrar";
            // }
            // else {
            //     $rspta=$entrega->editar($identrega,$idoperario,$idimpresion,$entregado);
            //     echo $rspta ? "Entregas actualizadas" : "Entregas no se pueden actualizar";
            //     // echo $rspta ? "Entregas actualizadas" : "Entregas no se pueden actualizar";
            // }
        break;
        case 'modificarEntregas':
            $rspta=$entrega->modificarEntregas($identrega,$entregado);
            echo $rspta ? "Entregas modificadas" : "Entregas no se pueden modificar";
            break;
        case 'deleteDetalle':
            $identrega = $_REQUEST["identrega"];
            $rspta=$entrega->eliminar($identrega);
            echo $rspta ? "Entrega eliminada" : "Entrega no se pudo eliminar";
            break; 
    
    }

?>
