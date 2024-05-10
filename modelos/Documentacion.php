<?php
    require '../config/conexion.php';

    Class Documentacion
    {
        public function __construct()
        {

        }

        public function insertar($descripcion, $identidad, $idplanta,$idusuario)
        {
            global $conexion;
            $sw = true;
            $sql = "INSERT INTO documentacion (descripcion,identidad,idplanta,fecha_hora,idusuario,condicion) 
                    VALUES ('$descripcion','$identidad','$idplanta',NOW(),'$idusuario','1')";
            ejecutarConsulta($sql) or $sw = false;
            
            if(!$sw){
                echo $conexion->error;   
            }
            return $sw;
        }

        public function editar($iddocumentacion, $descripcion, $identidad, $idplanta)
        {global $conexion;
            $sw = true;
            $sql = "UPDATE documentacion SET descripcion='$descripcion',identidad='$identidad', idplanta='$idplanta'
                    WHERE iddocumentacion='$iddocumentacion'";
            
            ejecutarConsulta($sql) or $sw = false;
            if(!$sw){
                echo $conexion->error;
            }
            return $sw;
        }

        //METODOS PARA ACTIVAR CATEGORIAS
        public function desactivar($iddocumentacion,$motivoAnulacion,$idusuario)
        {
            $sql= "UPDATE documentacion SET condicion='0'
             , motivoAnulacion = '$motivoAnulacion'
                    , anuladoPor = '$idusuario' 
                   WHERE iddocumentacion='$iddocumentacion'";
            
            return ejecutarConsulta($sql);
        }
        
        
        public function activar($iddocumentacion)
        {
            $sql= "UPDATE documentacion SET condicion='1' 
                   WHERE iddocumentacion='$iddocumentacion'";
            
            return ejecutarConsulta($sql);
        }
        
        public function finalizar($iddocumentacion)
        {
            $sql= "UPDATE documentacion SET condicion='2' 
                   WHERE iddocumentacion='$iddocumentacion'";
            
            return ejecutarConsulta($sql);
        }
        //METODO PARA MOSTRAR LOS DATOS DE UN REGISTRO A MODIFICAR
        public function mostrar($iddocumentacion)
        {
            $sql = "SELECT * FROM documentacion 
                    WHERE iddocumentacion='$iddocumentacion'";

            return ejecutarConsultaSimpleFila($sql);
        }

        //METODO PARA LISTAR LOS REGISTROS
        public function listar($identidad,$idplanta)
        {
            $sql = "SELECT iddocumentacion, 
            e.descripcion as entidad,
            uA.nombre as anuladoPor, d.motivoAnulacion,
          
            idplanta, d.condicion, d.descripcion , d.fecha_hora, d.idusuario
            FROM documentacion d
            LEFT JOIN entidad e 
            ON d.identidad = e.identidad
            LEFT JOIN usuario uA
            ON d.anuladoPor = uA.idusuario
            WHERE e.identidad REGEXP '".$identidad."'
            AND idplanta REGEXP '".$idplanta."'
            ORDER BY  FIELD(d.condicion,'1','2','0'),fecha_hora DESC";

            return ejecutarConsulta($sql);
        }

        //METODO PARA LISTAR LOS REGISTROS Y MOSTRAR EN EL SELECT
        public function select()
        {
            $sql = "SELECT * FROM documentacion 
                    WHERE condicion = 1";

            return ejecutarConsulta($sql);
        }
    }

?>