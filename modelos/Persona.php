<?php
    require '../config/conexion.php';

    Class Persona 
    {
        public function __construct()
        {

        }

        public function insertar($tipo_persona,$nombre,$idsector,$tipo_documento,$num_documento,$direccion,$telefono,$email)
        {
            $sql = "INSERT INTO persona (
                    tipo_persona,
                    nombre,
					idsector,
                    tipo_documento,
                    num_documento,
                    direccion,
                    telefono,
                    email
                   ) 
                    VALUES (
                        '$tipo_persona',
                        '$nombre',
						'$idsector',
                        '$tipo_documento',
                        '$num_documento',
                        '$direccion',
                        '$telefono',
                        '$email'
                        )";
            
            return ejecutarConsulta($sql);
        }

        public function editar($idpersona,$tipo_persona,$nombre,$idsector,$tipo_documento,$num_documento,$direccion,$telefono,$email)
        {
            $sql = "UPDATE persona SET 
                    tipo_persona='$tipo_persona', 
                    nombre='$nombre',
                    idsector='$idsector',
					tipo_documento='$tipo_documento',
                    num_documento='$num_documento',
                    direccion='$direccion',
                    telefono='$telefono',
                    email='$email'
                    WHERE idpersona='$idpersona '";
            
            return ejecutarConsulta($sql);
        }

        
        public function eliminar($idpersona)
        {
            $sql= "DELETE FROM persona 
                   WHERE idpersona='$idpersona'";
            
            return ejecutarConsulta($sql);
        }


        //METODO PARA MOSTRAR LOS DATOS DE UN REGISTRO A MODIFICAR
        public function mostrar($idpersona)
        {
            $sql = "SELECT * FROM persona 
                    WHERE idpersona='$idpersona'";

            return ejecutarConsultaSimpleFila($sql);
        }

        //METODO PARA LISTAR LOS REGISTROS
        public function listarp()
        {
            $sql = "SELECT * FROM persona 
                    WHERE tipo_persona='Proveedor'";

            return ejecutarConsulta($sql);
        }

        public function listarc()
        {
            //$sql = "SELECT * FROM persona 
            //        WHERE tipo_persona='Cliente'";
			
			$sql= "SELECT 
					p.idpersona,
					p.tipo_persona,
					p.nombre,
					p.tipo_documento,
					p.num_documento,
					p.direccion,
					p.telefono,
					p.email,
					s.idsector,
					s.nombre as sector
					FROM
						persona p
					INNER JOIN
						sector s
					ON
						p.idsector = s.idsector
					WHERE p.tipo_persona='Cliente'";
			
			
            return ejecutarConsulta($sql);
        }
        public function listarPA()
        {
            //$sql = "SELECT * FROM persona 
            //        WHERE tipo_persona='Cliente'";
			
			$sql= "SELECT 
					p.idpersona,
					p.nombre
					FROM
						personas_actuales p
					";
			
			
            return ejecutarConsulta($sql);
        }

    }

?>