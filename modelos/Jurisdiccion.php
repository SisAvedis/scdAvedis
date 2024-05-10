<?php
    require '../config/conexion.php';

    Class Jurisdiccion
    {
        public function __construct()
        {

        }

        public function insertar($codigo, $descripcion)
        {
            $sql = "INSERT INTO jurisdiccion (codigo,descripcion,condicion) 
                    VALUES ('$codigo','$descripcion','1')";
            
            return ejecutarConsulta($sql);
        }

        public function editar($idjurisdiccion, $codigo, $descripcion)
        {
            $sql = "UPDATE jurisdiccion SET codigo='$codigo', descripcion='$descripcion'
                    WHERE idjurisdiccion='$idjurisdiccion'";
            
            return ejecutarConsulta($sql);
        }

        //METODOS PARA ACTIVAR CATEGORIAS
        public function desactivar($idjurisdiccion)
        {
            $sql= "UPDATE jurisdiccion SET condicion='0' 
                   WHERE idjurisdiccion='$idjurisdiccion'";
            
            return ejecutarConsulta($sql);
        }

        public function activar($idjurisdiccion)
        {
            $sql= "UPDATE jurisdiccion SET condicion='1' 
                   WHERE idjurisdiccion='$idjurisdiccion'";
            
            return ejecutarConsulta($sql);
        }

        //METODO PARA MOSTRAR LOS DATOS DE UN REGISTRO A MODIFICAR
        public function mostrar($idjurisdiccion)
        {
            $sql = "SELECT * FROM jurisdiccion 
                    WHERE idjurisdiccion='$idjurisdiccion'";

            return ejecutarConsultaSimpleFila($sql);
        }

        //METODO PARA LISTAR LOS REGISTROS
        public function listar()
        {
            $sql = "SELECT * FROM jurisdiccion";

            return ejecutarConsulta($sql);
        }

        //METODO PARA LISTAR LOS REGISTROS Y MOSTRAR EN EL SELECT
        public function select()
        {
            $sql = "SELECT * FROM jurisdiccion 
                    WHERE condicion = 1";

            return ejecutarConsulta($sql);
        }
    }

?>