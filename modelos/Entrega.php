<?php
    require '../config/conexion.php';

    Class Entrega
    {
        public function __construct()
        {

        }

public function insertar($idoperario,$idimpresion,$entregado){
            global $conexion;
            $num_elementos = 0;
            $sw = true;
            while($num_elementos < count($idimpresion)){
                    if (isset($idoperario[$num_elementos])){
                        if(preg_match('/[a-zA-Z]/', $idoperario[$num_elementos]) === 1){
                            $idoperario[$num_elementos] ="0|".$idoperario[$num_elementos];
                        }

                $sql_detalle ="INSERT INTO entrega 
                (entregado,idoperario,idimpresion)
                VALUES(
                    '$entregado[$num_elementos]',
                    '$idoperario[$num_elementos]',
                $idimpresion[$num_elementos]); ";

                    ejecutarConsulta($sql_detalle) or $sw = false;
                }
                // echo '<script>console.log("'.$conexion->error.'");</script>';
                $num_elementos++;
            }
            return $sw;
    
        }
public function editar($identrega,$idoperario,$idimpresion,$entregado){
            global $conexion;
            $num_elementos = 0;
            $sw = true;
            while($num_elementos < count($idimpresion)){
                if(preg_match('/[a-zA-Z]/', $idoperario[$num_elementos]) === 1){
                    $idoperario[$num_elementos] ="0|".$idoperario[$num_elementos];
                }
                $sql_detalle ="UPDATE entrega SET
                 idoperario = '$idoperario[$num_elementos]',
                 entregado = '$entregado[$num_elementos]',
                 idimpresion = '$idimpresion[$num_elementos]'
                WHERE identrega = $identrega[$num_elementos]; ";
                ejecutarConsulta($sql_detalle) or $sw = false;
                // echo '<script>console.log("'.$conexion->error.'");</script>';
                $num_elementos++;
            }
            return $sw;
    
        }
public function modificarEntregas($identrega,$entregado){
            global $conexion;
            $num_elementos = 0;
            $sw = true;
            while($num_elementos < count($entregado)){
                
                $sql_detalle ="UPDATE entrega SET entregado = '$entregado[$num_elementos]'
                WHERE identrega = $identrega[$num_elementos]; ";
                ejecutarConsulta($sql_detalle) or $sw = false;
                // echo '<script>console.log("'.$conexion->error.'");</script>';
                $num_elementos++;
            }
            return $sw;
    
        }
        
public function eliminar($identrega){
            global $conexion;
            $sw = true;
                $sql ="DELETE FROM entrega WHERE identrega = $identrega; ";
                ejecutarConsulta($sql) or $sw = false;
                // echo '<script>console.log("'.$conexion->error.'");</script>';
            return $sw;
    
        }
        public function listarEntregas($idimpresion){
            $sql = "SET lc_time_names = 'es_ES';";
            ejecutarConsulta($sql);
            $sql = "SELECT  DATE_FORMAT(NOW(), '%M %Y') as fecha_actual, d.nombre as titulo, s.nombre as descripcion, DATE_FORMAT(d.vigencia, '%d/%m/%Y') as vigencia,
            d.num_revision, IFNULL(p.nombre,e.idoperario) as nombre FROM impresion i
           LEFT JOIN entrega e
           ON e.idimpresion = i.idimpresion
           LEFT JOIN documento d
           ON d.iddocumento = i.iddocumento
           LEFT JOIN sector s
           ON d.idsector = s.idsector
           LEFT JOIN personas_actuales p
           ON e.idoperario = p.idpersona
            WHERE i.idimpresion = '$idimpresion'";
            return ejecutarConsulta($sql);
        }
    }

?>