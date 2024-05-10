<?php
    require '../config/conexion.php';

    Class Entidad
    {
        public function __construct()
        {

        }

        public function insertar($descripcion, $idjurisdiccion )
        {
            global $conexion;
            $sw = true;
            $sql = "INSERT INTO entidad (descripcion,idjurisdiccion,condicion) 
                    VALUES ('$descripcion','$idjurisdiccion','1')";
            ejecutarConsulta($sql) or $sw = false;
            
            if(!$sw){
                echo $conexion->error;   
            }
            return $sw;
        }

        public function editar($identidad,  $descripcion)
        {
            $sql = "UPDATE entidad SET  descripcion='$descripcion'
                    WHERE identidad='$identidad'";
            
            return ejecutarConsulta($sql);
        }

        //METODOS PARA ACTIVAR CATEGORIAS
        public function desactivar($identidad)
        {
            $sql= "UPDATE entidad SET condicion='0' 
                   WHERE identidad='$identidad'";
            
            return ejecutarConsulta($sql);
        }

        public function activar($identidad)
        {
            $sql= "UPDATE entidad SET condicion='1' 
                   WHERE identidad='$identidad'";
            
            return ejecutarConsulta($sql);
        }

        //METODO PARA MOSTRAR LOS DATOS DE UN REGISTRO A MODIFICAR
        public function mostrar($identidad)
        {
            $sql = "SELECT * FROM entidad 
                    WHERE identidad='$identidad'";

            return ejecutarConsultaSimpleFila($sql);
        }

        //METODO PARA LISTAR LOS REGISTROS
        public function listar()
        {
            $sql = "SELECT identidad, j.descripcion as jurisdiccion,  e.condicion, e.descripcion FROM entidad e
                        LEFT JOIN jurisdiccion j ON e.idjurisdiccion = j.idjurisdiccion";

            return ejecutarConsulta($sql);
        }

        //METODO PARA LISTAR LOS REGISTROS Y MOSTRAR EN EL SELECT
        public function select()
        {
            $sql = "SELECT * FROM entidad 
                    WHERE condicion = 1
                    ";

            return ejecutarConsulta($sql);
        }
        
    }

?>