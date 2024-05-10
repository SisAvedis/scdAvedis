<?php 
//Incluímos inicialmente la conexión a la base de datos
require "../config/conexion.php";

Class RelacionProIns
{
	//Implementamos nuestro constructor
	public function __construct()
	{

	}

	//Implementamos un método para insertar registros
	public function insertar($idusuario,$idprocedimiento,$idinstructivo,$observacion)
	{
		
			$sql = "SELECT NOW() AS fechahora";
			//Devuelve fecha actual
			$fecha_hora = ejecutarConsultaSimpleFila($sql);
			
			$sql="INSERT INTO relproins (
				idusuario,
				idprocedimiento,
				fecha_hora,
				comentario,
				estado
				)
				VALUES (
					'$idusuario',
					'$idprocedimiento',
					'$fecha_hora[fechahora]',
					'$observacion',
					'Aceptado')";
			
			//Devuelve id registrado
            $idrelproinsnew = ejecutarConsulta_retornarID($sql);
			$num_elementos = 0;
            $sw = true;

            while($num_elementos < count($idinstructivo))
            {
                $sql_detalle ="INSERT INTO detalle_relproins (
									idrelproins,
                                    idprocedimiento,
                                    idinstructivo
                                )
                                VALUES (
                                    '$idrelproinsnew',
									'$idprocedimiento',
                                    '$idinstructivo[$num_elementos]'
                                )";
				//echo 'Variable sql_detalle -> '.$sql_detalle.'</br>';
                ejecutarConsulta($sql_detalle) or $sw = false;

                $num_elementos = $num_elementos + 1;
            }

            return $sw;
	}

	
	//Implementamos un método para anular la relación
	public function anular($idrelproins)
	{
		$sql="UPDATE relproins 
			  SET estado='Anulado' 
			  WHERE idrelproins='$idrelproins'";
		echo 'Variable sql -> '.$sql.'</br>';
		return ejecutarConsulta($sql);
	}

	public function mostrar($iddocumento)
	{
		$sql="SELECT 
			    r.idrelproins,
			    DATE_FORMAT(r.fecha_hora,'%d-%m-%Y') as fecha,
			    u.idusuario,
			    u.nombre as usuario,
			    d.nombre as procedimiento,
				d.idsector,
			    r.comentario,
				r.estado 
			    FROM relproins r 
			    LEFT JOIN usuario u 
			    ON r.idusuario=u.idusuario 
			    LEFT JOIN documento d
			    ON r.idprocedimiento = d.iddocumento 
				WHERE r.idrelproins='$iddocumento'";

			return ejecutarConsultaSimpleFila($sql);
	}
	
	public function listarDetalle($iddocumento)
	{
		$sql="SELECT 
				dr.idrelproins,
				dr.idprocedimiento,
				dr.idinstructivo,
				CONCAT(td.codigo,'-00',dr.idinstructivo) as codigoi,
				di.nombre as nombrei,
				DATE_FORMAT(di.fecha_hora,'%d-%m-%Y') as fecha
				FROM detalle_relproins dr 
				INNER JOIN documento dp 
				ON dr.idprocedimiento=dp.iddocumento 
				INNER JOIN documento di 
				ON dr.idinstructivo=di.iddocumento
				INNER JOIN tipo_documento td 
				ON di.idtipo_documento = td.idtipo_documento
				WHERE dr.idrelproins='$iddocumento'";

		return ejecutarConsulta($sql);
	}
	
	//Implementar un método para listar los registros
	public function listar()
	{
		$sql="SELECT 
			    r.idrelproins,
			    DATE_FORMAT(r.fecha_hora,'%d-%m-%Y') as fecha,
			    u.idusuario,
			    u.nombre as usuario,
			    d.nombre as procedimiento,
			    r.estado 
			    FROM relproins r 
			    INNER JOIN usuario u 
			    ON r.idusuario=u.idusuario 
			    INNER JOIN documento d
			    ON r.idprocedimiento = d.iddocumento
			    ORDER by r.idrelproins desc";
			   
		return ejecutarConsulta($sql);		
	}
	
	
	public function relproinsCabecera($idrelproins)
	{
		$sql= "SELECT 
				r.idrelproins,
				u.nombre as usuario,
				r.iddocumento,
				DATE_FORMAT(r.fecha_hora,'%d-%m-%Y') as fecha,
				r.comentario
			  FROM
			  	relproins r
			  INNER JOIN
			  	usuario u
			  ON
			  	r.idusuario = u.idusuario
		      WHERE
			  	r.idrelproins = '$idrelproins'";

		return ejecutarConsulta($sql);
	}
	
	public function relproinsDetalle($idrelproins)
	{
		$sql = "SELECT
					d.nombre as documento,
					d.codigo
					
					
				FROM
					detalle_relproins dr
				INNER JOIN	
					documento d
				ON
					dr.iddocumento = d.iddocumento
				WHERE
					dr.idrelproins = '$idrelproins'";

		return ejecutarConsulta($sql);
	}
}
?>