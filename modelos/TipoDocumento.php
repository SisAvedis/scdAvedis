<?php
    require '../config/conexion.php';

    Class TipoDocumento
    {
        public function __construct()
        {

        }

        public function insertar($codigo, $descripcion)
        {
            $sql = "INSERT INTO tipo_documento (codigo,descripcion,condicion) 
                    VALUES ('$codigo','$descripcion','1')";
            
            return ejecutarConsulta($sql);
        }

        public function editar($idtipo_documento, $codigo, $descripcion)
        {
            $sql = "UPDATE tipo_documento SET codigo='$codigo', descripcion='$descripcion'
                    WHERE idtipo_documento='$idtipo_documento'";
            
            return ejecutarConsulta($sql);
        }

        //METODOS PARA ACTIVAR CATEGORIAS
        public function desactivar($idtipo_documento)
        {
            $sql= "UPDATE tipo_documento SET condicion='0' 
                   WHERE idtipo_documento='$idtipo_documento'";
            
            return ejecutarConsulta($sql);
        }

        public function activar($idtipo_documento)
        {
            $sql= "UPDATE tipo_documento SET condicion='1' 
                   WHERE idtipo_documento='$idtipo_documento'";
            
            return ejecutarConsulta($sql);
        }

        //METODO PARA MOSTRAR LOS DATOS DE UN REGISTRO A MODIFICAR
        public function mostrar($idtipo_documento)
        {
            $sql = "SELECT * FROM tipo_documento 
                    WHERE idtipo_documento='$idtipo_documento'";

            return ejecutarConsultaSimpleFila($sql);
        }

        //METODO PARA LISTAR LOS REGISTROS
        public function listar()
        {
            $sql = "SELECT * FROM tipo_documento";

            return ejecutarConsulta($sql);
        }

        //METODO PARA LISTAR LOS REGISTROS Y MOSTRAR EN EL SELECT
        public function select()
        {
            $sql = "SELECT * FROM tipo_documento 
                    WHERE condicion = 1";

            return ejecutarConsulta($sql);
        }
    }

?>