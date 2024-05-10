<?php
    require '../config/conexion.php';

    Class Consultas
    {
        public function __construct()
        {

        }

        public function consultadocumento($iddocumentacion)
        {
            $sql = "SELECT 

					d.codigo,
                    d.iddocumento,
                    d.fecha_preaviso,
						d.descripcion,
						d.motivoFaltaDocumento,
						d.motivoAnulacion,
						u.nombre as anuladoPor,
						d.vigencia,
						a.carpeta,
						a.fuente,
						d.condicion
                    FROM
                        documento d
                    LEFT JOIN documentacion do
                    ON do.iddocumentacion = d.iddocumentacion
                    LEFT JOIN archivo a
                    ON a.iddocumento = d.iddocumento
                    LEFT JOIN usuario u
                    ON d.anuladoPor = u.idusuario
					WHERE 
                        d.iddocumentacion = '$iddocumentacion'
                    
					
                    ORDER BY d.fecha_hora, d.condicion DESC
					
                    
                    ";
			//echo $sql.'</br>';
            return ejecutarConsulta($sql);
        }
        public function consultadocumentoAVencer()
        {
            $sql = "SELECT 
                    du.descripcion as documentacion,
					d.codigo,
                    d.iddocumento,
                    d.fecha_preaviso,
                    NOW() as fecha_actual,
						d.descripcion,
						d.vigencia,
						d.condicion,
                        TIMESTAMPDIFF(DAY,  d.vigencia, NOW())*-1 as dias,
                        TIMESTAMPDIFF(MONTH,  d.vigencia, NOW())*-1 as meses,
                        TIMESTAMPDIFF(YEAR,  d.vigencia, NOW())*-1 as anios
                    FROM
                        documento d
                    LEFT JOIN documentacion du
                    ON du.iddocumentacion = d.iddocumentacion
					WHERE du.condicion = 1
                    ORDER BY -d.vigencia DESC
					
                    
                    ";
			//echo $sql.'</br>';
            return ejecutarConsulta($sql);
        }
        public function consultadocumentoExcel($idsector)
        {
            $sql = "CALL `prListarDocumentosExcel`('$idsector')
                    ";
			//echo $sql.'</br>';
            return ejecutarConsulta($sql);
        }
        public function consultaimpresiones($iddocumento)
        {
            $sql = "SELECT i.idimpresion,u.nombre as usuario, d.nombre as documento, i.fecha_vigencia, i.fecha_preaviso, i.entregado, i.vigente,
            (SELECT COUNT(*) FROM entrega e where e.idimpresion = i.idimpresion and entregado = 1) AS impresiones
            FROM impresion i
            LEFT JOIN usuario u
            ON i.idusuario = u.idusuario
            LEFT JOIN documento d
            ON d.iddocumento = i.iddocumento
            WHERE i.iddocumento = '$iddocumento'
            AND d.condicion = 1
            AND i.vigente = 1
            AND i.idusuario NOT IN (1,2,3,4,5,6)
            ORDER BY i.dias_preaviso DESC
                    ";
			//echo $sql.'</br>';
            return ejecutarConsulta($sql);
        }
        public function consultaentregas($idimpresion)
        {
            $sql = "SELECT e.identrega,e.idimpresion,IFNULL(p.nombre,REPLACE(e.idoperario,'0|','')) as nombre,e.entregado
            FROM  entrega e
            LEFT JOIN personas_actuales p
            ON e.idoperario = p.idpersona
            WHERE e.idimpresion = $idimpresion ";
			//echo $sql.'</br>';
            return ejecutarConsulta($sql);
        }
		
		public function muestradocumentos($iddocumento)
        {
            $sql = "CALL prTraerArchivos('".$iddocumento."')";
			//echo 'Variable sql -> '.$sql.'</br>';
			return ejecutarConsulta($sql);
        }
		
		public function listarDetalle($iddocumento)
        {
            $sql = "CALL prTraerArchivos('".$iddocumento."')";
			//echo 'Variable sql -> '.$sql.'</br>';
			return ejecutarConsulta($sql);
        }
		
		public function totalProcedimiento()
        {
            $sql= "SELECT 
                        IFNULL(COUNT(iddocumento),0) as cantidad_procedimiento
                    FROM
                        documento d
                        LEFT JOIN documentacion do
                    ON do.iddocumentacion = d.iddocumentacion
                    
                    WHERE
                         d.condicion = 1
                         AND do.condicion = 1
                         AND do.idplanta IN (1,3)  
                    ";
            
            return ejecutarConsulta($sql);
        }
		
		public function totalInstructivo()
        {
            $sql= "SELECT 
                        IFNULL(COUNT(iddocumento),0) as cantidad_instructivo
                        FROM
                        documento d
                        LEFT JOIN documentacion do
                    ON do.iddocumentacion = d.iddocumentacion
                    
                    WHERE
                         d.condicion = 1
                         AND do.condicion = 1
                         AND do.idplanta = 2  ";
            
            return ejecutarConsulta($sql);
        }
		
		public function procedimientos12meses()
        {$sql = "SET lc_time_names = es_ES";
            ejecutarConsulta($sql);
            $sql= "SELECT   
            CASE WHEN (TIMESTAMPDIFF(MONTH,  d.vigencia, NOW())*-1) < 13 
            AND d.condicion = 1
            THEN CONCAT(UCASE(LEFT(DATE_FORMAT(d.vigencia,'%M'),1)),
                LCASE(SUBSTRING(DATE_FORMAT(d.vigencia,'%M'), 2)))
            
                  END AS fecha,
                        COUNT(d.iddocumento) as total
                        FROM
                        documento d
                    LEFT JOIN documentacion do
                    ON do.iddocumentacion = d.iddocumentacion
                    
                    WHERE
                         d.condicion = 1
                         AND do.condicion = 1
                         AND do.idplanta IN (1,3)  
					GROUP BY
                        MONTH(d.vigencia) 
                        HAVING fecha IS NOT null
                    ORDER BY
                        d.vigencia
                    ASC limit 0,12";
            
            return ejecutarConsulta($sql);
        }
		
		public function instructivos12meses()
        {$sql = "SET lc_time_names = es_ES";
            ejecutarConsulta($sql);

            $sql= "SELECT   
            CASE WHEN (TIMESTAMPDIFF(MONTH,  d.vigencia, NOW())*-1) < 13 
            THEN CONCAT(UCASE(LEFT(DATE_FORMAT(d.vigencia,'%M'),1)),
                LCASE(SUBSTRING(DATE_FORMAT(d.vigencia,'%M'), 2)))
            
                  END AS fecha,
                        COUNT(d.iddocumento) as total
                        FROM
                        documento d
                    LEFT JOIN documentacion do
                    ON do.iddocumentacion = d.iddocumentacion
                    
                    WHERE
                         do.condicion = 1
                         AND do.idplanta = 2
					GROUP BY
                        MONTH(d.vigencia) 
                        HAVING fecha IS NOT null
                    ORDER BY
                        d.vigencia
                    ASC limit 0,12";
            
            return ejecutarConsulta($sql);
        }
       

    }


?>