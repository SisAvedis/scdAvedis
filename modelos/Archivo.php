<?php
    require '../config/conexion.php';

    Class Archivo 
    {
        public function __construct()
        {

        }
		
	
		
        
		
		
        public function insertar($iddocumento, $titulo, $carpeta, $archivo)
        {
            global $conexion;
            $sw = true;
			$sql = "SELECT NOW() AS fechahora";
			//Devuelve fecha actual
			$fecha_hora = ejecutarConsultaSimpleFila($sql);
            if($iddocumento == ''){
			$sql = "SELECT MAX(iddocumento)+1 AS iddocumento FROM documento";
			//Devuelve fecha actual
			$new_iddocumento = ejecutarConsultaSimpleFila($sql);
            $iddocumento = $new_iddocumento["iddocumento"];
            }
			
			$sql = "INSERT INTO archivo (
                    iddocumento,
					titulo,
                    carpeta,
					fuente,
                    fecha_hora
                   ) 
                    VALUES (
						$iddocumento,
                        '$titulo',
                        '$carpeta',
						'$archivo',
                        '$fecha_hora[fechahora]'
                        )";
            //echo 'Variable sql -> '.$sql.'</br>';
            ejecutarConsulta($sql) or $sw = false;
            // echo $conexion->error;
            return $sw;
        }
		
		//METODO PARA MOSTRAR LOS DATOS DE UN REGISTRO A MODIFICAR
        public function mostrar($iddocumento)
        {
            $sql = "SELECT * FROM archivo 
                    WHERE iddocumento='$iddocumento'";

            //echo 'Variable sql -> '.$sql.'</br>';
			return ejecutarConsulta($sql);
        }
		
		public function eliminar($iddocumento)
        {
            $sql= "DELETE FROM archivo 
                   WHERE iddocumento='$iddocumento'";
            
            return ejecutarConsulta($sql);
        }
		
		

    }

?>