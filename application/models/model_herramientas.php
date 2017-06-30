<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class model_herramientas extends CI_Model {

	public function buscar_escena_capitulo($idcapitulo){
		$query = $this->db->query("SELECT escena.id AS id_escena, escena.numero_escena, escena.dias_continuidad, 
			locacion.nombre AS locacion_nombre, sets.nombre AS set_nombre, produccion_has_capitulos.numero AS numero_capitulo
			FROM escena
			INNER JOIN produccion_has_capitulos ON produccion_has_capitulos.id = escena.id_capitulo
			INNER JOIN locacion ON locacion.id = escena.id_locacion
			INNER JOIN sets ON sets.id = escena.id_set
    	WHERE escena.id_capitulo = ".$idcapitulo." AND escena.estado != 3 AND escena.estado != 1 
    	ORDER BY CAST(SUBSTRING_INDEX(numero_escena,'.', 1 ) AS UNSIGNED) ASC,
(CASE INSTR(numero_escena, '.') 
		WHEN 0 THEN 0
		ELSE CAST(SUBSTRING_INDEX(numero_escena,'.', -1 ) AS UNSIGNED) END) ASC;");
    	if ($query->num_rows>0) {
	      return $query->result();
	    }else{
	      return false;
	    }
	}
	
	public function actualizar_escena_datos($id,$datos){
		$this->db->where('id',$id);
		$this->db->update('escena',$datos);
	}

	public function rango_escenas($id_produccion,$numero1,$numero2,$sql){
		$query = $this->db->query("SELECT escena.id, escena.numero_escena,escena.estado,escena.id_toma_ubicacion,
                                escena.id_flasback,escena.id_producida,
		                        produccion_has_capitulos.numero  from escena 
								INNER JOIN produccion_has_capitulos ON produccion_has_capitulos.id = escena.id_capitulo 
								WHERE produccion_has_capitulos.id_produccion=".$id_produccion." and escena.estado!= 1 AND escena.estado!= 3 AND escena.estado != 2 AND escena.estado != 12 AND escena.estado != 14 
								AND produccion_has_capitulos.numero BETWEEN ".$numero1." and ".$numero2." ".$sql."
								ORDER BY produccion_has_capitulos.numero,CAST(SUBSTRING_INDEX(numero_escena,'.', 1 ) AS UNSIGNED) ASC,
								(CASE INSTR(numero_escena, '.') 
										WHEN 0 THEN 0
										ELSE CAST(SUBSTRING_INDEX(numero_escena,'.', -1 ) AS UNSIGNED) END) ASC;");
		if ($query->num_rows>0) {
	      return $query->result();
	    }else{
	      return false;
	    }
	}

	

    public function nomina_personajes_anterior($idproduccion,$fecha1,$fecha2){
		$query = $this->db->query("SELECT elemento.id AS idelemento,elemento.id_tipo_contrato AS id_tipo_contrato_el,elemento.id_tipo_moneda AS id_tipo_moneda,elemento.nombre, UPPER(rol_actores_elementos.rol) AS rol, 
			UPPER(CASE WHEN elemento.actor_nombre IS NULL THEN ' - ' ELSE elemento.actor_nombre END) AS actor_nombre, 
			UPPER(CASE WHEN elemento.actor_apellido IS NULL THEN ' - ' ELSE elemento.actor_apellido END) AS actor_apellido, 
			UPPER(CASE WHEN tipo_contrato.descripcion IS NULL THEN ' - ' ELSE tipo_contrato.descripcion END) AS contrato,
			
			(SELECT DATE_FORMAT(plan_diario.fecha_inicio, '%d-%b-%Y') AS fecha_inicio
			FROM escenas_has_elementos
			INNER JOIN escena ON escenas_has_elementos.id_escena = escena.id
			INNER JOIN plan_diario_has_escenas_has_unidades pu ON pu.id_escena = escena.id
			INNER JOIN plan_diario ON plan_diario.id = pu.id_plan_diario
			WHERE escenas_has_elementos.id_elemento = elemento.id ORDER BY plan_diario.fecha_inicio LIMIT 1
			) AS fecha_inicio,

			(SELECT COUNT(DISTINCT(plan_diario.fecha_inicio))
			FROM escenas_has_elementos
			INNER JOIN escena ON escenas_has_elementos.id_escena = escena.id
			INNER JOIN plan_diario_has_escenas_has_unidades pu ON pu.id_escena = escena.id
			INNER JOIN plan_diario ON plan_diario.id = pu.id_plan_diario
			WHERE escenas_has_elementos.id_elemento = elemento.iD
			) AS dias_trabajados,

			(SELECT group_concat(DISTINCT(produccion_has_capitulos.numero)) 
			FROM escenas_has_elementos
			INNER JOIN escena ON escenas_has_elementos.id_escena = escena.id
			INNER JOIN produccion_has_capitulos ON produccion_has_capitulos.id = escena.id_capitulo
			INNER JOIN plan_diario_has_escenas_has_unidades pu ON pu.id_escena = escena.id
			INNER JOIN plan_diario ON plan_diario.id = pu.id_plan_diario
			WHERE escenas_has_elementos.id_elemento = elemento.id 
			and escena.fecha_produccion !='0000-00-00' 
			and 
			(escena.estado=1 or escena.estado=2 or escena.estado=12 or escena.estado=14)
			AND plan_diario.fecha_inicio BETWEEN '".$fecha1."' AND '".$fecha2."'
			) AS libretos,

			(CASE WHEN elemento.documento_actor IS NULL THEN ' - ' ELSE elemento.documento_actor END) AS documento_actor,
			(CASE WHEN elemento.monto IS NULL THEN ' - ' ELSE elemento.monto END) AS monto, elemento.id_tipo_contrato,
			DATE_FORMAT(elemento.fecha_inicio, '%d-%b-%Y') As fecha_inicio_2, DATE_FORMAT(elemento.fecha_finalizacion, '%d-%b-%Y') AS fecha_finalizacion,
			(CASE WHEN tipo_documento.descripcion IS NULL THEN ' - ' ELSE tipo_documento.descripcion END) AS tipo_documento, tipo_documento.id AS id_tipo_documento,
			(CASE WHEN tipo_moneda.descripcion IS NULL THEN ' - ' ELSE tipo_moneda.descripcion END) AS tipo_moneda
			FROM 
			elemento
			INNER JOIN rol_actores_elementos ON rol_actores_elementos.id = elemento.rol 
			INNER JOIN categoria_elemento ON categoria_elemento.id = elemento.id_tipo_elemento
			LEFT OUTER JOIN tipo_contrato ON tipo_contrato.id = elemento.id_tipo_contrato
			LEFT OUTER JOIN tipo_documento ON tipo_documento.id = elemento.id_tipo_documento
			LEFT OUTER JOIN tipo_moneda ON tipo_moneda.id = elemento.id_tipo_moneda
			WHERE categoria_elemento.id_produccion = ".$idproduccion." AND elemento.rol != 4
			AND (elemento.id_tipo_contrato !=5 or elemento.id_tipo_contrato is null) 
			ORDER BY elemento.nombre;");

		if ($query->num_rows>0) {
	      return $query->result();
	    }else{
	      return false;
	    }

	}


public function nomina_personajes($idproduccion,$fecha1,$fecha2){
		$query = $this->db->query("SELECT elemento.id AS idelemento,solicitudes.id_forma_pago AS id_tipo_contrato_el, solicitudes.id_forma_pago AS id_tipo_contrato,solicitudes.id_tipo_moneda AS id_tipo_moneda,elemento.nombre, UPPER(rol_actores_elementos.rol) AS rol, 
			UPPER(CASE WHEN act.nombre IS NULL THEN ' - ' ELSE act.nombre END) AS actor_nombre, 
			UPPER(CASE WHEN act.apellido IS NULL THEN ' - ' ELSE act.apellido END) AS actor_apellido, 
			UPPER(CASE WHEN tipo_contrato.descripcion IS NULL THEN ' - ' ELSE tipo_contrato.descripcion END) AS contrato,
			
			(SELECT DATE_FORMAT(plan_diario.fecha_inicio, '%d-%b-%Y') AS fecha_inicio
			FROM escenas_has_elementos
			INNER JOIN escena ON escenas_has_elementos.id_escena = escena.id
			INNER JOIN plan_diario_has_escenas_has_unidades pu ON pu.id_escena = escena.id
			INNER JOIN plan_diario ON plan_diario.id = pu.id_plan_diario
			WHERE escenas_has_elementos.id_elemento = elemento.id ORDER BY plan_diario.fecha_inicio LIMIT 1
			) AS fecha_inicio,

			(SELECT COUNT(DISTINCT(plan_diario.fecha_inicio))
			FROM escenas_has_elementos
			INNER JOIN escena ON escenas_has_elementos.id_escena = escena.id
			INNER JOIN plan_diario_has_escenas_has_unidades pu ON pu.id_escena = escena.id
			INNER JOIN plan_diario ON plan_diario.id = pu.id_plan_diario
			WHERE escenas_has_elementos.id_elemento = elemento.iD
			) AS dias_trabajados,

			(SELECT group_concat(DISTINCT(produccion_has_capitulos.numero)) 
			FROM escenas_has_elementos
			INNER JOIN escena ON escenas_has_elementos.id_escena = escena.id
			INNER JOIN produccion_has_capitulos ON produccion_has_capitulos.id = escena.id_capitulo
			INNER JOIN plan_diario_has_escenas_has_unidades pu ON pu.id_escena = escena.id
			INNER JOIN plan_diario ON plan_diario.id = pu.id_plan_diario
			WHERE escenas_has_elementos.id_elemento = elemento.id 
			and escena.fecha_produccion !='0000-00-00' 
			and 
			(escena.estado=1 or escena.estado=2 or escena.estado=12 or escena.estado=14)
			AND plan_diario.fecha_inicio BETWEEN '".$fecha1."' AND '".$fecha2."'
			) AS libretos,

			(CASE WHEN act.documento IS NULL THEN ' - ' ELSE act.documento END) AS documento_actor,
			(CASE WHEN solicitudes.honorarios IS NULL THEN ' - ' ELSE solicitudes.honorarios END) AS monto, solicitudes.id_forma_pago,
			DATE_FORMAT(solicitudes.fecha_inicio, '%d-%b-%Y') As fecha_inicio_2, DATE_FORMAT(solicitudes.fecha_final, '%d-%b-%Y') AS fecha_finalizacion,
			(CASE WHEN tipo_documento.descripcion IS NULL THEN ' - ' ELSE tipo_documento.descripcion END) AS tipo_documento, tipo_documento.id AS id_tipo_documento,
			(CASE WHEN tipo_moneda.descripcion IS NULL THEN ' - ' ELSE tipo_moneda.descripcion END) AS tipo_moneda
			FROM 
			elemento

			LEFT OUTER JOIN solicitud_has_elementos on solicitud_has_elementos.id_elemento=elemento.id
			LEFT OUTER JOIN solicitudes on solicitudes.id=solicitud_has_elementos.id_solicitud
			LEFT OUTER JOIN actores act on act.id=solicitudes.id_actor


			INNER JOIN rol_actores_elementos ON rol_actores_elementos.id = elemento.rol 
			INNER JOIN categoria_elemento ON categoria_elemento.id = elemento.id_tipo_elemento
			LEFT OUTER JOIN tipo_contrato ON tipo_contrato.id = solicitudes.id_forma_pago 
			LEFT OUTER JOIN tipo_documento ON tipo_documento.id = act.id_tipo_documento 
			LEFT OUTER JOIN tipo_moneda ON tipo_moneda.id = solicitudes.id 
			WHERE categoria_elemento.id_produccion = ".$idproduccion." AND elemento.rol != 4
			AND (solicitudes.id_forma_pago !=5 or solicitudes.id_forma_pago is null) 
			ORDER BY elemento.nombre;");

		if ($query->num_rows>0) {
	      return $query->result();
	    }else{
	      return false;
	    }

	}

	public function nomina_personajes_mensuales_anteriores($idproduccion,$fecha1,$fecha2){
		$query = $this->db->query("SELECT elemento.id AS idelemento, elemento.nombre,elemento.id_tipo_moneda, UPPER(rol_actores_elementos.rol) AS rol, 
			UPPER(CASE WHEN elemento.actor_nombre IS NULL THEN ' - ' ELSE elemento.actor_nombre END) AS actor_nombre, 
			UPPER(CASE WHEN elemento.actor_apellido IS NULL THEN ' - ' ELSE elemento.actor_apellido END) AS actor_apellido, 
			UPPER(CASE WHEN tipo_contrato.descripcion IS NULL THEN ' - ' ELSE tipo_contrato.descripcion END) AS contrato,
			
			(SELECT DATE_FORMAT(plan_diario.fecha_inicio, '%d-%b-%Y') AS fecha_inicio 
			FROM escenas_has_elementos 
			INNER JOIN escena ON escenas_has_elementos.id_escena = escena.id 
			INNER JOIN plan_diario_has_escenas_has_unidades pu ON pu.id_escena = escena.id 
			INNER JOIN plan_diario ON plan_diario.id = pu.id_plan_diario 
			WHERE escenas_has_elementos.id_elemento = elemento.id ORDER BY plan_diario.fecha_inicio LIMIT 1 
			) AS fecha_inicio,

			(SELECT COUNT(DISTINCT(plan_diario.fecha_inicio)) 
			FROM escenas_has_elementos 
			INNER JOIN escena ON escenas_has_elementos.id_escena = escena.id 
			INNER JOIN plan_diario_has_escenas_has_unidades pu ON pu.id_escena = escena.id 
			INNER JOIN plan_diario ON plan_diario.id = pu.id_plan_diario 
			WHERE escenas_has_elementos.id_elemento = elemento.id 
			) AS dias_trabajados,

			(SELECT group_concat(DISTINCT(produccion_has_capitulos.numero) order by produccion_has_capitulos.numero) 
			FROM escenas_has_elementos 
			INNER JOIN escena ON escenas_has_elementos.id_escena = escena.id 
			INNER JOIN produccion_has_capitulos ON produccion_has_capitulos.id = escena.id_capitulo 
			INNER JOIN plan_diario_has_escenas_has_unidades pu ON pu.id_escena = escena.id 
			INNER JOIN plan_diario ON plan_diario.id = pu.id_plan_diario 
			WHERE escenas_has_elementos.id_elemento = elemento.id AND plan_diario.fecha_inicio BETWEEN '".date("Y-m-d",strtotime($fecha1))."' AND '".date("Y-m-d",strtotime($fecha2))."'
			) AS libretos,
			(CASE WHEN elemento.documento_actor IS NULL THEN ' - ' ELSE elemento.documento_actor END) AS documento_actor,
			(CASE WHEN elemento.monto IS NULL THEN ' - ' ELSE elemento.monto END) AS monto, elemento.id_tipo_contrato,
			DATE_FORMAT(elemento.fecha_inicio, '%d-%b-%Y') As fecha_inicio_2, 
			DATE_FORMAT(elemento.fecha_finalizacion, '%d-%b-%Y') AS fecha_finalizacion,
			DATE_FORMAT(elemento.fecha_liquidacion, '%d-%b-%Y') AS fecha_liquidacion,
			(CASE WHEN tipo_documento.descripcion IS NULL THEN ' - ' ELSE tipo_documento.descripcion END) AS tipo_documento, tipo_documento.id AS id_tipo_documento,
			(CASE WHEN tipo_moneda.descripcion IS NULL THEN ' - ' ELSE tipo_moneda.descripcion END) AS tipo_moneda 
			FROM 
			elemento  
			INNER JOIN rol_actores_elementos ON rol_actores_elementos.id = elemento.rol 
			INNER JOIN categoria_elemento ON categoria_elemento.id = elemento.id_tipo_elemento 
			LEFT OUTER JOIN tipo_contrato ON tipo_contrato.id = elemento.id_tipo_contrato 
			LEFT OUTER JOIN tipo_documento ON tipo_documento.id = elemento.id_tipo_documento 
			LEFT OUTER JOIN tipo_moneda ON tipo_moneda.id = elemento.id_tipo_moneda 
			WHERE categoria_elemento.id_produccion = ".$idproduccion." AND elemento.rol != 4 AND tipo_contrato.id = 5 
			ORDER BY elemento.nombre;");

		if ($query->num_rows>0) {
	      return $query->result();
	    }else{
	      return false;
	    }
	}

	public function nomina_personajes_mensuales($idproduccion,$fecha1,$fecha2){
		$query = $this->db->query("SELECT elemento.id AS idelemento, elemento.nombre,solicitudes.id_tipo_moneda, UPPER(rol_actores_elementos.rol) AS rol, 
			UPPER(CASE WHEN act.nombre IS NULL THEN ' - ' ELSE act.nombre END) AS actor_nombre, 
			UPPER(CASE WHEN act.apellido IS NULL THEN ' - ' ELSE act.apellido END) AS actor_apellido, 
			UPPER(CASE WHEN tipo_contrato.descripcion IS NULL THEN ' - ' ELSE tipo_contrato.descripcion END) AS contrato,
			
			(SELECT DATE_FORMAT(plan_diario.fecha_inicio, '%d-%b-%Y') AS fecha_inicio 
			FROM escenas_has_elementos 
			INNER JOIN escena ON escenas_has_elementos.id_escena = escena.id 
			INNER JOIN plan_diario_has_escenas_has_unidades pu ON pu.id_escena = escena.id 
			INNER JOIN plan_diario ON plan_diario.id = pu.id_plan_diario 
			WHERE escenas_has_elementos.id_elemento = elemento.id ORDER BY plan_diario.fecha_inicio LIMIT 1 
			) AS fecha_inicio,

			(SELECT COUNT(DISTINCT(plan_diario.fecha_inicio)) 
			FROM escenas_has_elementos 
			INNER JOIN escena ON escenas_has_elementos.id_escena = escena.id 
			INNER JOIN plan_diario_has_escenas_has_unidades pu ON pu.id_escena = escena.id 
			INNER JOIN plan_diario ON plan_diario.id = pu.id_plan_diario 
			WHERE escenas_has_elementos.id_elemento = elemento.id 
			) AS dias_trabajados,

			(SELECT group_concat(DISTINCT(produccion_has_capitulos.numero) order by produccion_has_capitulos.numero) 
			FROM escenas_has_elementos 
			INNER JOIN escena ON escenas_has_elementos.id_escena = escena.id 
			INNER JOIN produccion_has_capitulos ON produccion_has_capitulos.id = escena.id_capitulo 
			INNER JOIN plan_diario_has_escenas_has_unidades pu ON pu.id_escena = escena.id 
			INNER JOIN plan_diario ON plan_diario.id = pu.id_plan_diario 
			WHERE escenas_has_elementos.id_elemento = elemento.id AND plan_diario.fecha_inicio BETWEEN '".date("Y-m-d",strtotime($fecha1))."' AND '".date("Y-m-d",strtotime($fecha2))."'
			) AS libretos,

			(CASE WHEN act.documento IS NULL THEN ' - ' ELSE act.documento END) AS documento_actor,
			(CASE WHEN solicitudes.honorarios IS NULL THEN ' - ' ELSE solicitudes.honorarios END) AS monto, elemento.id_tipo_contrato,
			DATE_FORMAT(solicitudes.fecha_inicio, '%d-%b-%Y') As fecha_inicio_2, 
			DATE_FORMAT(solicitudes.fecha_final, '%d-%b-%Y') AS fecha_finalizacion,
			DATE_FORMAT(elemento.fecha_liquidacion, '%d-%b-%Y') AS fecha_liquidacion,
			(CASE WHEN tipo_documento.descripcion IS NULL THEN ' - ' ELSE tipo_documento.descripcion END) AS tipo_documento, tipo_documento.id AS id_tipo_documento,
			(CASE WHEN tipo_moneda.descripcion IS NULL THEN ' - ' ELSE tipo_moneda.descripcion END) AS tipo_moneda 
			FROM 
			elemento  

			LEFT OUTER JOIN solicitud_has_elementos on solicitud_has_elementos.id_elemento=elemento.id
			LEFT OUTER JOIN solicitudes on solicitudes.id=solicitud_has_elementos.id_solicitud
			LEFT OUTER JOIN actores act on act.id=solicitudes.id_actor

			
			INNER JOIN rol_actores_elementos ON rol_actores_elementos.id = elemento.rol 
			INNER JOIN categoria_elemento ON categoria_elemento.id = elemento.id_tipo_elemento 
			LEFT OUTER JOIN tipo_contrato ON tipo_contrato.id = solicitudes.id_forma_pago 
			LEFT OUTER JOIN tipo_documento ON tipo_documento.id = act.id_tipo_documento 
			LEFT OUTER JOIN tipo_moneda ON tipo_moneda.id = solicitudes.id  
			WHERE categoria_elemento.id_produccion = ".$idproduccion." AND elemento.rol != 4 AND tipo_contrato.id = 5 
			ORDER BY elemento.nombre;");

		if ($query->num_rows>0) {
	      return $query->result();
	    }else{
	      return false;
	    }
	}

	public function libretos_pagados($idelemento,$fecha1,$fecha2){
		$query = $this->db->query("SELECT elemento.id AS idelemento,

			(SELECT group_concat(DISTINCT(produccion_has_capitulos.numero)) 
			FROM escenas_has_elementos 
			INNER JOIN escena ON escenas_has_elementos.id_escena = escena.id 
			INNER JOIN produccion_has_capitulos ON produccion_has_capitulos.id = escena.id_capitulo 
			INNER JOIN plan_diario_has_escenas_has_unidades pu ON pu.id_escena = escena.id 
			INNER JOIN plan_diario ON plan_diario.id = pu.id_plan_diario 
			WHERE escenas_has_elementos.id_elemento = elemento.id AND plan_diario.fecha_inicio BETWEEN '".date("Y-m-d",strtotime($fecha1))."' AND '".date("Y-m-d",strtotime($fecha2))."'
			) AS libretos

			FROM 
			elemento  
			INNER JOIN rol_actores_elementos ON rol_actores_elementos.id = elemento.rol 
			INNER JOIN categoria_elemento ON categoria_elemento.id = elemento.id_tipo_elemento 
			LEFT OUTER JOIN tipo_contrato ON tipo_contrato.id = elemento.id_tipo_contrato 
			LEFT OUTER JOIN tipo_documento ON tipo_documento.id = elemento.id_tipo_documento 
			LEFT OUTER JOIN tipo_moneda ON tipo_moneda.id = elemento.id_tipo_moneda 
			WHERE elemento.id = ".$idelemento."
			ORDER BY elemento.nombre;");

		if ($query->num_rows>0) {
	      return $query->result();
	    }else{
	      return false;
	    }
	}

	public function liquidacion_produccion_mensual($idproduccion,$mes){
		$this->db->where('id_produccion',$idproduccion);
		$this->db->where('mes',$mes);
		$query=$this->db->get('liquidaciones');
		if ($query->num_rows>0) {
	      return $query->result();
	    }else{
	      return false;
	    }
	}

	public function liquidaciones_produccion($idproduccion){
		$this->db->where('id_produccion',$idproduccion);
		$this->db->order_by('mes','asc');
		$query=$this->db->get('liquidaciones');
		if ($query->num_rows>0) {
	      return $query->result();
	    }else{
	      return false;
	    }
	}

	public function insertar_liquidacion($idproduccion,$mes_liquidacion){
		$data = array(
			'id_produccion' => $idproduccion,
			'mes'=>$mes_liquidacion 
		);
		$this->db->insert('liquidaciones',$data);
	}

	public function insertar_descuento($data){
		$this->db->insert('descuentos_nomina',$data);
	}

	public function descuento_personaje($id_elemento,$mes){
		$query = $this->db->query("SELECT descuentos_nomina.descuento,descuentos_nomina.observaciones,descuentos_nomina.tasa_cambio 
			           FROM descuentos_nomina INNER JOIN liquidaciones ON descuentos_nomina.id_liquidacion = liquidaciones.id 
						WHERE descuentos_nomina.id_elemento = ".$id_elemento." AND liquidaciones.mes = '".$mes."';");
		if ($query->num_rows>0) {
	      return $query->result();
	    }else{
	      return false;
	    }
	}

	/*public function buscar_capitulos_elemento($id_elemento,$fecha_inicio,$fecha_final){
<<<<<<< HEAD
		$query = $this->db->query("SELECT group_concat(DISTINCT(p2.numero),' ', 
									(SELECT DATE_FORMAT(min(e2.fecha_produccion), '%d-%b-%Y') FROM escena e2
									 WHERE e2.id_capitulo = p2.id AND e2.fecha_produccion !='0000-00-00' 
									and 
									(e2.estado=1 or e2.estado=2 or e2.estado=12 or e2.estado=14)
									AND  e2.fecha_produccion  BETWEEN '".$fecha_inicio."' AND '".$fecha_final."' )
									separator ', ') AS capitulos
									FROM escenas_has_elementos
									INNER JOIN escena ON escenas_has_elementos.id_escena = escena.id
									INNER JOIN produccion_has_capitulos  p2 ON p2.id = escena.id_capitulo
									WHERE escenas_has_elementos.id_elemento = ".$id_elemento." 
									AND p2.id NOT IN (
									SELECT produccion_has_capitulos.id 
									FROM escenas_has_elementos
									INNER JOIN escena ON escenas_has_elementos.id_escena = escena.id
									INNER JOIN produccion_has_capitulos ON produccion_has_capitulos.id = escena.id_capitulo
									INNER JOIN plan_diario_has_escenas_has_unidades pu ON pu.id_escena = escena.id
									INNER JOIN plan_diario ON plan_diario.id = pu.id_plan_diario
									WHERE escenas_has_elementos.id_elemento = ".$id_elemento." AND escena.fecha_produccion < '".$fecha_inicio."'
									)
									AND escena.fecha_produccion BETWEEN '".$fecha_inicio."' AND '".$fecha_final."' and 
									(escena.estado=1 or escena.estado=2 or escena.estado=12 or escena.estado=14);");
		if ($query->num_rows>0) {
	      return $query->result();
	    }else{
	      return false;
	    }
	}*/

	public function buscar_capitulos_elemento($id_elemento,$fecha_inicio,$fecha_final){
		$query = $this->db->query("SELECT group_concat(DISTINCT(p2.numero),' ', 
							(SELECT DATE_FORMAT(MIN(e2.fecha_produccion), '%d-%b-%Y') 
						    FROM escena e2
						    inner join escenas_has_elementos el ON el.id_escena = e2.id AND el.id_elemento = ".$id_elemento."
							WHERE 
						    e2.id_capitulo = p2.id 
						    AND e2.fecha_produccion !='0000-00-00' 
							and 
							(e2.estado=1 or e2.estado=2 or e2.estado=12 or e2.estado=14)
							AND  e2.fecha_produccion  BETWEEN '".$fecha_inicio."' AND '".$fecha_final."' )
							separator ', ') AS capitulos
							FROM escenas_has_elementos
							INNER JOIN escena ON escenas_has_elementos.id_escena = escena.id
							INNER JOIN produccion_has_capitulos  p2 ON p2.id = escena.id_capitulo
							WHERE escenas_has_elementos.id_elemento = ".$id_elemento." 
							AND p2.id NOT IN (
							SELECT produccion_has_capitulos.id 
							FROM escenas_has_elementos
							INNER JOIN escena ON escenas_has_elementos.id_escena = escena.id
							INNER JOIN produccion_has_capitulos ON produccion_has_capitulos.id = escena.id_capitulo
							INNER JOIN plan_diario_has_escenas_has_unidades pu ON pu.id_escena = escena.id
							INNER JOIN plan_diario ON plan_diario.id = pu.id_plan_diario
							WHERE escenas_has_elementos.id_elemento = ".$id_elemento." AND escena.fecha_produccion < '".$fecha_inicio."'
							and escena.fecha_produccion!='0000-00-00'
							)
							AND escena.fecha_produccion BETWEEN '".$fecha_inicio."' AND '".$fecha_final."' and 
							(escena.estado=1 or escena.estado=2 or escena.estado=12 or escena.estado=14)");
		if ($query->num_rows>0) {
	      return $query->result();
	    }else{
	      return false;
	    }
	}

	public function buscar_dias_elemento($id_elemento,$fecha_inicio,$fecha_final){
		$query = $this->db->query("SELECT COUNT(DISTINCT(plan_diario.fecha_inicio)) as total
			FROM escenas_has_elementos
			INNER JOIN escena ON escenas_has_elementos.id_escena = escena.id
			INNER JOIN plan_diario_has_escenas_has_unidades pu ON pu.id_escena = escena.id
			INNER JOIN plan_diario ON plan_diario.id = pu.id_plan_diario
			WHERE escenas_has_elementos.id_elemento = ".$id_elemento." and plan_diario.fecha_inicio BETWEEN '".$fecha_inicio."' AND '".$fecha_final."';");
		if ($query->num_rows>0) {
	      return $query->result();
	    }else{
	      return false;
	    }
	}

	/*public function buscar_capitulos_elemento($id_elemento,$fecha_inicio,$fecha_final){
		$query = $this->db->query("SELECT DISTINCT(p2.numero) AS capitulos
									FROM escenas_has_elementos
									INNER JOIN escena ON escenas_has_elementos.id_escena = escena.id
									INNER JOIN produccion_has_capitulos  p2 ON p2.id = escena.id_capitulo
									WHERE escenas_has_elementos.id_elemento = ".$id_elemento." 
									AND p2.id NOT IN (
									SELECT produccion_has_capitulos.id 
									FROM escenas_has_elementos
									INNER JOIN escena ON escenas_has_elementos.id_escena = escena.id
									INNER JOIN produccion_has_capitulos ON produccion_has_capitulos.id = escena.id_capitulo
									INNER JOIN plan_diario_has_escenas_has_unidades pu ON pu.id_escena = escena.id
									INNER JOIN plan_diario ON plan_diario.id = pu.id_plan_diario
									WHERE escenas_has_elementos.id_elemento = ".$id_elemento." AND escena.fecha_produccion < '".$fecha_inicio."'
									)
									AND escena.fecha_produccion BETWEEN '".$fecha_inicio."' AND '".$fecha_final."' and 
									(escena.estado=1 or escena.estado=2 or escena.estado=12 or escena.estado=14);");
		if ($query->num_rows>0) {
	      return $query->result();
	    }else{
	      return false;
	    }
	}*/

	public function personaje_produccion($id_produccion,$personaje){
		$query = $this->db->query("SELECT elemento.* from categoria_elemento 
				inner join elemento on elemento.id_tipo_elemento=categoria_elemento.id 
				  where categoria_elemento.id_produccion=".$id_produccion." and categoria_elemento.tipo='Personaje'
				and elemento.nombre='".$personaje."'");
		if ($query->num_rows>0) {
	      return $query->result();
	    }else{
	      return false;
	    }
	}

	public function buscar_elemento_sql($sql){
      $query=$this->db->query("SELECT *,ele.id AS idelemento, roles.rol AS rol_final, cat.tipo AS tipo, cat.id_produccion  FROM elemento ele
      LEFT OUTER JOIN rol_actores_elementos roles ON roles.id = ele.rol 
      INNER JOIN categoria_elemento cat ON cat.id = ele.id_tipo_elemento
      WHERE ".$sql);
      if ($query->num_rows>0) {
        return $query->result_array();
      }else{
         return false;
      }
  	}

  public function escenas_has_elementos_sql($sql){


      $query=$this->db->query("SELECT * FROM escenas_has_elementos ele
      WHERE ".$sql);
      if ($query->num_rows>0) {
        return $query->num_rows;
      }else{
         return false;
      }


  }

	public function personaje_produccion_sql($id_produccion,$sql){
		$query = $this->db->query("SELECT elemento.* from categoria_elemento 
				inner join elemento on elemento.id_tipo_elemento=categoria_elemento.id 
				  where categoria_elemento.id_produccion=".$id_produccion." and categoria_elemento.tipo='Personaje' ".$sql);
		if ($query->num_rows>0) {
	      return $query->result_array();
	    }else{
	      return false;
	    }
	}
  

  public function update_escenas_has_elemtento($id_elemento,$sql){
		$query = $this->db->query("update escenas_has_elementos set id_elemento=".$id_elemento."
				where".$sql);
	      return true;
	}
	/*public function update_escenas_has_elemtento($id_personaje1,$id_personaje2){

		update escenas_has_elementos set id_elemento=1389
where (id_elemento=1390 or id_elemento=1389)


		$datos=array('id_elemento'=>$id_personaje2);
		$this->db->where('id_elemento',$id_personaje1);
		$this->db->update('escenas_has_elementos_sql',$datos);
	}*/

	/*public function elementos_continuidad($id_elemento){
    $this->db->where('id_elemento',$id_elemento);
    $query=$this->db->get('continuidad');
    if ($query->num_rows>0){
      return $query->num_rows;
     } else {
      return 0;
     }
  }*/

  public function elementos_continuidad($sql){
   $query = $this->db->query("SELECT * from continuidad 
				  where ".$sql);
    if ($query->num_rows>0){
      return $query->num_rows;
     } else {
      return 0;
     }

 }


  public function update_elemtento_cont($id_elemento,$sql){
		$query = $this->db->query("update continuidad set id_elemento=".$id_elemento."
				where".$sql);
	      return true;
	}
  


 /* public function update_elemtento_cont($id_personaje1,$id_personaje2){
		$datos=array('id_elemento'=>$id_personaje2);
		$this->db->where('id_elemento',$id_personaje1);
		$this->db->update('continuidad',$datos);
	}*/

	public function eliminar_elemento($sql){
		$query = $this->db->query("delete from elemento  
				where".$sql);
	      return true;
	  }
  



	



  public function elementos_id($id_elemento){
		$query = $this->db->query("SELECT elemento.id idelemento,elemento.*,
			(SELECT DATE_FORMAT(plan_diario.fecha_inicio, '%d-%b-%Y') AS fecha_inicio 
			FROM escenas_has_elementos 
			INNER JOIN escena ON escenas_has_elementos.id_escena = escena.id 
			INNER JOIN plan_diario_has_escenas_has_unidades pu ON pu.id_escena = escena.id 
			INNER JOIN plan_diario ON plan_diario.id = pu.id_plan_diario 
			WHERE escenas_has_elementos.id_elemento = elemento.id ORDER BY plan_diario.fecha_inicio LIMIT 1 
			) AS fecha_inicio,

			(SELECT COUNT(DISTINCT(plan_diario.fecha_inicio)) 
			FROM escenas_has_elementos 
			INNER JOIN escena ON escenas_has_elementos.id_escena = escena.id 
			INNER JOIN plan_diario_has_escenas_has_unidades pu ON pu.id_escena = escena.id 
			INNER JOIN plan_diario ON plan_diario.id = pu.id_plan_diario 
			WHERE escenas_has_elementos.id_elemento = elemento.id 
			) AS dias_trabajados,
			(CASE WHEN elemento.documento_actor IS NULL THEN ' - ' ELSE elemento.documento_actor END) AS documento_actor,
			(CASE WHEN elemento.monto IS NULL THEN ' - ' ELSE elemento.monto END) AS monto, elemento.id_tipo_contrato,
			DATE_FORMAT(elemento.fecha_inicio, '%d-%b-%Y') As fecha_inicio_2, 
			DATE_FORMAT(elemento.fecha_finalizacion, '%d-%b-%Y') AS fecha_finalizacion,
			DATE_FORMAT(elemento.fecha_liquidacion, '%d-%b-%Y') AS fecha_liquidacion
			from elemento where elemento.id=".$id_elemento);
		if ($query->num_rows>0) {
	      return $query->result();
	    }else{
	      return false;
	    }
	}

	public function fotos_continuidad_escenas_has_elementos($id_produccion){
		$query = $this->db->query("SELECT continuidad.*
			FROM produccion 
			inner join produccion_has_capitulos on produccion_has_capitulos.id_produccion=produccion.id
			inner join escena on escena.id_capitulo=produccion_has_capitulos.id
			inner join escenas_has_elementos on escenas_has_elementos.id_escena=escena.id
			inner join continuidad on continuidad.id_escena_has_elemento=escenas_has_elementos.id
			where produccion.id=".$id_produccion);
		if ($query->num_rows>0) {
	      return $query->result_array();
	    }else{
	      return false;
	    }
	}
   
   public function fotos_continuidad_elementos($id_produccion){
		$query = $this->db->query("SELECT continuidad.* FROM categoria_elemento
			inner join elemento on elemento.id_tipo_elemento=categoria_elemento.id
			inner join continuidad on continuidad.id_elemento=elemento.id
			where categoria_elemento.id_produccion=".$id_produccion);
		if ($query->num_rows>0) {
	      return $query->result_array();
	    }else{
	      return false;
	    }
	}

  
   public function fotos_continuidad_sets($id_produccion){
		$query = $this->db->query("SELECT continuidad_sets.* FROM locacion
			inner join sets on sets.id_locacion=locacion.id
			inner join continuidad_sets on continuidad_sets.id_set=sets.id
			where id_produccion=".$id_produccion);
		if ($query->num_rows>0) {
	      return $query->result_array();
	    }else{
	      return false;
	    }
	}

	


}