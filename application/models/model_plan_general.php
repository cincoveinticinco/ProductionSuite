<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class model_plan_general extends CI_Model {

	public function listar_escenas($idproduccion, $idpersonajes,$limit_inf,$limit_sup){
		$query = $this->db->query("SELECT esc.*, magnitud_escena.descripcion AS magnitud_nombre, cap.numero AS capitulo, loc.nombre AS locacion, se.nombre AS setnombre, 
			dn.descripcion AS tiempo, tip.tipo AS tipo, ext.descripcion AS ubicacion, plan_dia.fecha_inicio AS fecha_inicio, plan_dia.id_unidad AS idunidad,
			uni.numero  AS unidad_numero, plan_dia.estado AS estado_plan, 

			(SELECT sum(capitulos_has_escenas.tiempo_post_minutos) from capitulos_has_escenas
			where capitulos_has_escenas.id_escena=esc.id) tiempo_post_minutos,
			(SELECT sum(capitulos_has_escenas.tiempo_post_segundos) from capitulos_has_escenas
			where capitulos_has_escenas.id_escena=esc.id) tiempo_post_segundos,
			(SELECT sum(capitulos_has_escenas.tiempo_post_cuadros) from capitulos_has_escenas
			where capitulos_has_escenas.id_escena=esc.id) tiempo_post_cuadros,

			(SELECT group_concat(ele.nombre ORDER BY ele.nombre separator ', ') FROM escenas_has_elementos  
			INNER JOIN elemento ele on ele.id = escenas_has_elementos.id_elemento
			WHERE id_escena = esc.id AND ele.id_tipo_elemento = ".$idpersonajes." AND (rol = 1 OR rol = 3) ORDER BY ele.nombre) as personajes_principales,
			(SELECT group_concat(DATE_FORMAT(plan_diario.fecha_inicio, '%d-%b-%Y'), '_', unidad.numero,'_',
			(SELECT id FROM retomas_escena where fecha_produccion = plan_diario.fecha_inicio AND unidad_produccion = plan_diario.id_unidad AND retomas_escena.id_escena =  esc.id)) FROM plan_diario_has_escenas_has_unidades
	        INNER JOIN plan_diario ON plan_diario.id = plan_diario_has_escenas_has_unidades.id_plan_diario
	        INNER JOIN unidad ON unidad.id = plan_diario.id_unidad
	        WHERE (plan_diario.estado != 1 AND plan_diario.estado != 5) AND id_escena = esc.id)  AS planes_abiertos,
			(SELECT group_concat(ele.nombre, COALESCE(CONCAT('(',extras_escena.cantidad,')'),'')   
			ORDER BY ele.nombre separator ', ') FROM escenas_has_elementos  
			INNER JOIN elemento ele on ele.id = escenas_has_elementos.id_elemento
			LEFT OUTER JOIN extras_escena ON extras_escena.id_escenas_has_elementos = escenas_has_elementos.id
			WHERE id_escena = esc.id AND ele.id_tipo_elemento = ".$idpersonajes." AND (rol = 2 OR rol = 4) ORDER BY ele.nombre) as personajes_secundarios,
			(SELECT group_concat(ele.nombre ORDER BY ele.nombre separator ', ' ) FROM escenas_has_elementos  
			INNER JOIN elemento ele on ele.id = escenas_has_elementos.id_elemento
			WHERE id_escena = esc.id AND ele.id_tipo_elemento != ".$idpersonajes." ) as elementos 
			FROM escena esc 
			INNER JOIN produccion_has_capitulos cap ON cap.id = esc.id_capitulo 
			INNER JOIN locacion loc ON loc.id = esc.id_locacion
			INNER JOIN sets se ON se.id = esc.id_set 
			INNER JOIN escena_interior_esterior ext ON ext.id = esc.id_interior_esterior 
			INNER JOIN escenas_dias_noche dn ON dn.id = esc.id_dia_noche 
			INNER JOIN tipo_locacion tip ON tip.id = esc.id_tipo_locacion
			INNER JOIN magnitud_escena ON magnitud_escena.id = esc.id_magnitud 
			LEFT OUTER JOIN plan_diario_has_escenas_has_unidades plan_esc ON plan_esc.id_escena = esc.id 
			AND plan_esc.id_plan_diario = 
			(SELECT MAX(plan_diario.id) 
			FROM plan_diario 
			INNER JOIN plan_diario_has_escenas_has_unidades pl ON pl.id_plan_diario = plan_diario.id 
			WHERE pl.id_escena = esc.id AND plan_diario.estado !=5) 
			LEFT OUTER JOIN plan_diario plan_dia ON plan_dia.id = plan_esc.id_plan_diario 
			LEFT OUTER JOIN unidad uni ON uni.id = plan_dia.id_unidad 
			WHERE cap.id_produccion =".$idproduccion." 
			AND esc.estado != 1 AND esc.estado != 3 AND esc.estado != 2 AND esc.estado != 12 AND esc.estado != 14
			GROUP BY esc.id
			ORDER BY capitulo ASC, CAST(SUBSTRING_INDEX(numero_escena,'.', 1 ) AS UNSIGNED) ASC,
(CASE INSTR(numero_escena, '.') 
		WHEN 0 THEN 0
		ELSE SUBSTRING_INDEX(numero_escena,'.', -1) END) ASC limit ".$limit_inf.",".$limit_sup);
		return $query->result(); 
	}



	public function find_escenas($idproduccion, $idpersonajes,$palabra,$sql,$limit_inf){
		$query = $this->db->query("SELECT
			 esc.id,esc.numero_escena,esc.id_capitulo,esc.dias_continuidad,esc.duracion_estimada_minutos,esc.duracion_estimada_segundos, esc.duracion_real_minutos, esc.duracion_real_segundos,
		      (SELECT sum(capitulos_has_escenas.tiempo_post_minutos) from capitulos_has_escenas
		      where capitulos_has_escenas.id_escena=esc.id) tiempo_post_minutos,
		      (SELECT sum(capitulos_has_escenas.tiempo_post_segundos) from capitulos_has_escenas
		      where capitulos_has_escenas.id_escena=esc.id) tiempo_post_segundos,
		      (SELECT sum(capitulos_has_escenas.tiempo_post_cuadros) from capitulos_has_escenas
		      where capitulos_has_escenas.id_escena=esc.id) tiempo_post_cuadros,
		      esc.descripcion,esc.guion, esc.fecha_produccion, esc.unidad_produccion, esc.vehiculo_background, esc.estado, esc.libreto, magnitud_escena.descripcion AS magnitud_nombre, DATE_FORMAT(esc.fecha_produccion,'%d-%b-%Y') AS fecha_produccion_2,  cap.numero AS capitulo, loc.nombre AS locacion, se.nombre AS setnombre, dn.descripcion   AS tiempo, tip.tipo AS tipo, ext.descripcion AS ubicacion, DATE_FORMAT(plan_dia.fecha_inicio,'%d-%b-%Y') AS fecha_inicio, 
		      plan_dia.id_unidad AS idunidad, (SELECT COUNT(uni2.id) FROM unidad uni2 WHERE uni2.id <= uni.id AND uni2.id_produccion = ".$idproduccion.")  AS unidad_numero, (SELECT unidad.numero FROM unidad where unidad.id = esc.unidad_produccion) AS unidad_produccion_numero,
		    esc.*, cap.numero AS capitulo, magnitud_escena.descripcion AS magnitud_nombre,loc.nombre AS locacion, se.nombre AS setnombre, dn.descripcion AS tiempo, tip.tipo AS tipo, ext.descripcion AS ubicacion, plan_dia.fecha_inicio AS fecha_inicio, plan_dia.id_unidad AS idunidad,
			(SELECT COUNT(uni2.id) FROM unidad uni2 WHERE uni2.id <= uni.id AND uni2.id_produccion = ".$idproduccion.")  AS unidad_numero, 
			(SELECT group_concat(ele.nombre ORDER BY ele.nombre separator ',') FROM escenas_has_elementos  
			INNER JOIN elemento ele on ele.id = escenas_has_elementos.id_elemento
			WHERE id_escena = esc.id AND ele.id_tipo_elemento = ".$idpersonajes." AND (rol = 1 OR rol = 3)  ORDER BY ele.nombre) as personajes_principales,
			(SELECT group_concat(ele.nombre ORDER BY ele.nombre separator ',') FROM escenas_has_elementos  
			INNER JOIN elemento ele on ele.id = escenas_has_elementos.id_elemento
			WHERE id_escena = esc.id AND ele.id_tipo_elemento = ".$idpersonajes." AND (rol = 2 OR rol = 4)  ORDER BY ele.nombre) as personajes_secundarios,
			(SELECT group_concat(ele.nombre ORDER BY ele.nombre separator ',' ) FROM escenas_has_elementos  
			INNER JOIN elemento ele on ele.id = escenas_has_elementos.id_elemento
			WHERE id_escena = esc.id AND ele.id_tipo_elemento != ".$idpersonajes." ) as elementos 
			FROM escena esc 
			INNER JOIN produccion_has_capitulos cap ON cap.id = esc.id_capitulo
			INNER JOIN locacion loc ON loc.id = esc.id_locacion
			INNER JOIN sets se ON se.id = esc.id_set
			INNER JOIN escena_interior_esterior ext ON ext.id = esc.id_interior_esterior
			INNER JOIN escenas_dias_noche dn ON dn.id = esc.id_dia_noche
			INNER JOIN tipo_locacion tip ON tip.id = esc.id_tipo_locacion
			INNER JOIN magnitud_escena ON magnitud_escena.id = esc.id_magnitud 
			LEFT OUTER JOIN plan_diario_has_escenas_has_unidades plan_esc ON plan_esc.id_escena = esc.id
      		LEFT OUTER JOIN plan_diario plan_dia ON plan_dia.id = plan_esc.id_plan_diario 
      		LEFT OUTER JOIN unidad uni ON uni.id = plan_dia.id_unidad
      		LEFT OUTER JOIN escenas_has_elementos on escenas_has_elementos.id_escena =   esc.id
			LEFT OUTER JOIN elemento on escenas_has_elementos.id_elemento =    elemento.id 
			WHERE cap.id_produccion =".$idproduccion." ".$sql."
			AND esc.estado != 1 AND esc.estado != 3 AND (loc.nombre LIKE '%".$palabra."%' OR se.nombre LIKE '%".$palabra."%' OR elemento.nombre LIKE '%".$palabra."%' OR ext.descripcion LIKE '%".$palabra."%' OR dn.descripcion LIKE '%".$palabra."%' OR tip.tipo LIKE '%".$palabra."%') 
			GROUP BY esc.id
			 ORDER BY capitulo ASC, CAST(SUBSTRING_INDEX(esc.numero_escena,'.', 1 ) AS UNSIGNED) ASC,
			(CASE INSTR(esc.numero_escena, '.') 
			WHEN 0 THEN 0
			ELSE SUBSTRING_INDEX(esc.numero_escena,'.', -1) END) ASC
			 limit ".$limit_inf.",50");
		return $query->result(); 
	}

	public function find_escenas_total($idproduccion, $idpersonajes,$palabra,$sql){
		$query = $this->db->query("SELECT
			 esc.id,esc.numero_escena,esc.id_capitulo,esc.dias_continuidad,esc.duracion_estimada_minutos,esc.duracion_estimada_segundos, esc.duracion_real_minutos, esc.duracion_real_segundos,
		      (SELECT sum(capitulos_has_escenas.tiempo_post_minutos) from capitulos_has_escenas
		      where capitulos_has_escenas.id_escena=esc.id) tiempo_post_minutos,
		      (SELECT sum(capitulos_has_escenas.tiempo_post_segundos) from capitulos_has_escenas
		      where capitulos_has_escenas.id_escena=esc.id) tiempo_post_segundos,
		      (SELECT sum(capitulos_has_escenas.tiempo_post_cuadros) from capitulos_has_escenas
		      where capitulos_has_escenas.id_escena=esc.id) tiempo_post_cuadros,
		      esc.descripcion,esc.guion, esc.fecha_produccion, esc.unidad_produccion, esc.vehiculo_background, esc.estado, esc.libreto, magnitud_escena.descripcion AS magnitud_nombre, DATE_FORMAT(esc.fecha_produccion,'%d-%b-%Y') AS fecha_produccion_2,  cap.numero AS capitulo, loc.nombre AS locacion, se.nombre AS setnombre, dn.descripcion   AS tiempo, tip.tipo AS tipo, ext.descripcion AS ubicacion, DATE_FORMAT(plan_dia.fecha_inicio,'%d-%b-%Y') AS fecha_inicio, 
		      plan_dia.id_unidad AS idunidad, (SELECT COUNT(uni2.id) FROM unidad uni2 WHERE uni2.id <= uni.id AND uni2.id_produccion = ".$idproduccion.")  AS unidad_numero, (SELECT unidad.numero FROM unidad where unidad.id = esc.unidad_produccion) AS unidad_produccion_numero,
		    esc.*, cap.numero AS capitulo, magnitud_escena.descripcion AS magnitud_nombre,loc.nombre AS locacion, se.nombre AS setnombre, dn.descripcion AS tiempo, tip.tipo AS tipo, ext.descripcion AS ubicacion, plan_dia.fecha_inicio AS fecha_inicio, plan_dia.id_unidad AS idunidad,
			(SELECT COUNT(uni2.id) FROM unidad uni2 WHERE uni2.id <= uni.id AND uni2.id_produccion = ".$idproduccion.")  AS unidad_numero, 
			(SELECT group_concat(ele.nombre ORDER BY ele.nombre separator ',') FROM escenas_has_elementos  
			INNER JOIN elemento ele on ele.id = escenas_has_elementos.id_elemento
			WHERE id_escena = esc.id AND ele.id_tipo_elemento = ".$idpersonajes." AND (rol = 1 OR rol = 3)  ORDER BY ele.nombre) as personajes_principales,
			(SELECT group_concat(ele.nombre ORDER BY ele.nombre separator ',') FROM escenas_has_elementos  
			INNER JOIN elemento ele on ele.id = escenas_has_elementos.id_elemento
			WHERE id_escena = esc.id AND ele.id_tipo_elemento = ".$idpersonajes." AND (rol = 2 OR rol = 4)  ORDER BY ele.nombre) as personajes_secundarios,
			(SELECT group_concat(ele.nombre ORDER BY ele.nombre separator ',' ) FROM escenas_has_elementos  
			INNER JOIN elemento ele on ele.id = escenas_has_elementos.id_elemento
			WHERE id_escena = esc.id AND ele.id_tipo_elemento != ".$idpersonajes." ) as elementos 
			FROM escena esc 
			INNER JOIN produccion_has_capitulos cap ON cap.id = esc.id_capitulo
			INNER JOIN locacion loc ON loc.id = esc.id_locacion
			INNER JOIN sets se ON se.id = esc.id_set
			INNER JOIN escena_interior_esterior ext ON ext.id = esc.id_interior_esterior
			INNER JOIN escenas_dias_noche dn ON dn.id = esc.id_dia_noche
			INNER JOIN tipo_locacion tip ON tip.id = esc.id_tipo_locacion
			INNER JOIN magnitud_escena ON magnitud_escena.id = esc.id_magnitud 
			LEFT OUTER JOIN plan_diario_has_escenas_has_unidades plan_esc ON plan_esc.id_escena = esc.id
      		LEFT OUTER JOIN plan_diario plan_dia ON plan_dia.id = plan_esc.id_plan_diario 
      		LEFT OUTER JOIN unidad uni ON uni.id = plan_dia.id_unidad
      		LEFT OUTER JOIN escenas_has_elementos on escenas_has_elementos.id_escena =   esc.id
			LEFT OUTER JOIN elemento on escenas_has_elementos.id_elemento =    elemento.id 
			WHERE cap.id_produccion =".$idproduccion." ".$sql."
			AND esc.estado != 1 AND esc.estado != 3 AND (loc.nombre LIKE '%".$palabra."%' OR se.nombre LIKE '%".$palabra."%' OR elemento.nombre LIKE '%".$palabra."%' OR ext.descripcion LIKE '%".$palabra."%' OR dn.descripcion LIKE '%".$palabra."%' OR tip.tipo LIKE '%".$palabra."%') 
			GROUP BY esc.id
			 ORDER BY capitulo ASC, CAST(SUBSTRING_INDEX(esc.numero_escena,'.', 1 ) AS UNSIGNED) ASC,
			(CASE INSTR(esc.numero_escena, '.') 
			WHEN 0 THEN 0
			ELSE SUBSTRING_INDEX(esc.numero_escena,'.', -1) END) ASC");
		return $query->result(); 
	}

	public function categoria_produccion($id, $tipo){
		$this->db->WHERE('id_produccion',$id);
		$this->db->WHERE('tipo',$tipo);
		$query = $this->db->get('categoria_elemento');
        return $query->result();
	}

	public function filtro($cadena1, $cadena2){
		$query = $this->db->query("SELECT esc.*, cap.numero AS capitulo, loc.nombre AS locacion, se.nombre AS setnombre, dn.descripcion AS tiempo, tip.tipo AS tipo, 
			(SELECT group_concat(ele.nombre separator ',') FROM escenas_has_elementos  
			INNER JOIN elemento ele on ele.id = escenas_has_elementos.id_elemento
			WHERE id_escena = esc.id AND ele.id_tipo_elemento = ".$idpersonajes." ORDER BY ele.nombre) as personajes,
			(SELECT group_concat(ele.nombre separator ',') FROM escenas_has_elementos  
			INNER JOIN elemento ele on ele.id = escenas_has_elementos.id_elemento
			WHERE id_escena = esc.id AND ele.id_tipo_elemento != ".$idpersonajes.") as elementos 
			FROM escena esc 
			INNER JOIN produccion_has_capitulos cap ON cap.id = esc.id_capitulo
			INNER JOIN locacion loc ON loc.id = esc.id_locacion
			INNER JOIN sets se ON se.id = esc.id_set
			INNER JOIN escenas_dias_noche dn ON dn.id = esc.id_dia_noche
			INNER JOIN tipo_locacion tip ON tip.id = esc.id_tipo_locacion
			WHERE cap.id_produccion =".$idproduccion." AND esc.estado != 1 AND esc.estado !=3
			GROUP BY esc.id
			ORDER BY id_capitulo ASC, esc.numero_escena ASC;");
		return $query->result(); 

	}

	public function elemento_id_produccion($id){
    $query=$this->db->query("SELECT e.*,e.id as id_elemento,e.descripcion as des_elem,c.* FROM elemento e, categoria_elemento c
              WHERE c.id_produccion=".$id." and c.id=e.id_tipo_elemento;");
      if ($query->num_rows>0) {
        return $query->result_array();
      }else{
         return false;
      }
  	}

  	public function filtro_elementos($idescena, $nombre){
  		$query=$this->db->query("SELECT elesc.*, ele.* FROM escenas_has_elementos elesc
								INNER JOIN elemento ele ON ele.id = elesc.id_elemento
								WHERE elesc.id_escena = ".$idescena."
								AND ele.nombre = '".$nombre."';");

  		if ($query->num_rows>0) {
         return true;
        }else{
         return false;
        }
  	}

  	public function guardar_consulta($data){
	    return $this->db->insert('consultas_user',$data);
  	}

  	public function consultas_user($iduser, $idproduccion){
		$this->db->WHERE('id_user',$iduser);
		$this->db->WHERE('id_produccion',$idproduccion);
		$query = $this->db->get('consultas_user');
		if ($query->num_rows>0) {
        	return $query->result();
	    }else{
	        return false;
	    }
  	}

  	public function consulta_id($id){
  		$this->db->WHERE('id',$id);
		$query = $this->db->get('consultas_user');
		if ($query->num_rows>0) {
        	return $query->result();
	    }else{
	        return "";
	    }
  	}

  	/*CONSULTAS PARA PLAN DIARIO*/
  		public function crear_plan_diario($data){
	    	return $this->db->insert('plan_diario',$data);
  		}

  		public function asignar_plan_diario($data){
	    	return $this->db->insert('plan_diario_has_escenas_has_unidades',$data);
  		}
  	/*FIN CONSULTAS PARA PLAN DIARIO*/

  	public function buscar_plan_diario_fecha($fecha_inicio, $idunidad){
  		$query = $this->db->query("SELECT plan_diario.* FROM plan_diario
				WHERE fecha_inicio = '".$fecha_inicio."'
				AND id_unidad =  '".$idunidad."';");
		if ($query->num_rows>0) {
        	return $query->result();
	    }else{
	        return false;
	    }
  	}

  	public function plan_diario_by_escena_id($idescena,$idplan){
  		$this->db->join('plan_diario', 'plan_diario_has_escenas_has_unidades.id_plan_diario = plan_diario.id');
  		$this->db->WHERE('id_escena',$idescena);
  		$this->db->WHERE('plan_diario.estado !=',5);
  		$this->db->WHERE('plan_diario.id !=',$idplan);
  		$query = $this->db->get('plan_diario_has_escenas_has_unidades');
  		if ($query->num_rows>0) {
        	return $query->result();
	    }else{
	        return false;
	    }
  	}

  	public function plan_diario_id($id){
  		$query = $this->db->query("SELECT *, plan_diario.estado AS estado_plan, DATE_FORMAT(plan_diario.fecha_inicio, '%d-%b-%Y') as fecha_inicio_f, unidad.numero AS numero_unidad, produccion_has_capitulos.numero AS numero_capitulo, produccion_has_capitulos.id_produccion
						FROM (`plan_diario_has_escenas_has_unidades`)
						JOIN `plan_diario` ON `plan_diario`.`id` = `plan_diario_has_escenas_has_unidades`.`id_plan_diario`
						JOIN `escena` ON `escena`.`id` = `plan_diario_has_escenas_has_unidades`.`id_escena`
						JOIN `produccion_has_capitulos` ON `produccion_has_capitulos`.`id` = `escena`.`id_capitulo`
						JOIN unidad ON plan_diario.id_unidad = unidad.id
						WHERE `id_plan_diario` =  '".$id."';");
  		if ($query->num_rows>0) {
        	return $query->result();
	    }else{
	        return false;
	    }
  	}

  	public function plan_diario_id_2($id){
  		$query = $this->db->query("SELECT *,DATE_FORMAT(plan_diario.fecha_inicio, '%d-%b-%Y') as fecha_inicio_f from
						plan_diario 
						WHERE id =  '".$id."';");
  		if ($query->num_rows>0) {
        	return $query->result();
	    }else{
	        return false;
	    }
  	}

  	public function borrar_plan_diario($id){
  		$this->db->WHERE('id',$id);
  		$this->db->delete('plan_diario');
  		return true;
  	}

  	public function cambiar_plan_diario($idactual,$idnuevo,$idescena,$orden){
  		$data=array(
            'id_plan_diario'=>$idnuevo,
            'orden'=>$orden
        );
  		$this->db->where('id_plan_diario',$idactual);
  		$this->db->where('id_escena',$idescena);
  		return $this->db->update('plan_diario_has_escenas_has_unidades',$data);
  	}

  	public function eliminar_consulta($idconsulta){
  		$this->db->where('id',$idconsulta);
  		$this->db->delete('consultas_user');
  		return true;
  	}

  	public function actualizar_consulta($idconsulta,$filtro,$resumen){
  		$data=array(
            'consulta'=>$filtro,
            'resumen'=>$resumen
        );
  		$this->db->WHERE('id',$idconsulta);
  		$this->db->update('consultas_user',$data);
  	}

  	public function dias_trabajo_produccion($idproduccion){
  		$this->db->where('id_produccion',$idproduccion);

  		$this->db->where('fecha_fin_semana >= ',date("d-M-Y"));
  		$this->db->where('fecha_inicio_semana <= ',date("d-M-Y"));

  		$query = $this->db->get('semanas_produccion');
  		if ($query->num_rows>0) {
        	return $query->result();
	    }else{
	        return false;
	    }
  	}

  	public function sets_produccion($idproduccion){
	    $query = $this->db->query('SELECT sets.* from sets
	                      INNER JOIN locacion ON locacion.id = sets.id_locacion
	                      WHERE locacion.id_produccion = '.$idproduccion.';');
	    if ($query->num_rows>0) {
	      return $query->result();
	    }else{
	      return false;
	    }
  	}

  	public function escena_plan($idplan,$idescena){
	    $query = $this->db->query('SELECT * from plan_diario_has_escenas_has_unidades
	                      			WHERE id_plan_diario = '.$idplan.' AND id_escena= '.$idescena.';');
	    if ($query->num_rows>0) {
	      return true;
	    }else{
	      return false;
	    }
	}

	public function total_escena($idproduccion){
	    $query = $this->db->query('SELECT COUNT(DISTINCT(escena.id)) as total_escenas FROM escena 
	    							INNER JOIN produccion_has_capitulos ON produccion_has_capitulos.id = escena.id_capitulo
	    							WHERE produccion_has_capitulos.id_produccion = '.$idproduccion.' AND escena.estado != 1 AND escena.estado !=2 AND escena.estado !=3 AND escena.estado != 12 AND escena.estado != 14;');
	    if ($query->num_rows>0) {
	      return $query->result();
	    }else{
	      return false;
	    }
	}

	public function semanas_reporte_semanal($fecha){
		$query = $this->db->query('SELECT fecha_inicio_semana AS fecha_valor, DATE_FORMAT(fecha_inicio_semana, "%d-%b-%Y") AS fecha_muestra  FROM semanas_produccion WHERE semanas_produccion.fecha_inicio_semana>="'.$fecha.'";');
	    if ($query->num_rows>0) {
	      return $query->result();
	    }else{
	      return false;
	    }
		;
	}	

	public function escenas_selecionadas($idproduccion,$idpersonajes,$id_escena){
	    $query = $this->db->query("SELECT esc.*, magnitud_escena.descripcion AS magnitud_nombre, cap.numero AS capitulo, loc.nombre AS locacion, se.nombre AS setnombre, dn.descripcion AS tiempo, tip.tipo AS tipo, ext.descripcion AS ubicacion, plan_dia.fecha_inicio AS fecha_inicio, plan_dia.id_unidad AS idunidad,
		uni.numero  AS unidad_numero, plan_dia.estado AS estado_plan, 
		(SELECT group_concat(ele.nombre ORDER BY ele.nombre separator ', ') FROM escenas_has_elementos  
		INNER JOIN elemento ele on ele.id = escenas_has_elementos.id_elemento
		WHERE id_escena = esc.id AND ele.id_tipo_elemento = ".$idpersonajes." AND (rol = 1 OR rol = 3) ORDER BY ele.nombre) as personajes_principales,
		(SELECT group_concat(DATE_FORMAT(plan_diario.fecha_inicio, '%d-%b-%Y'), '_', unidad.numero,'_',
		(SELECT id FROM retomas_escena where fecha_produccion = plan_diario.fecha_inicio AND unidad_produccion = plan_diario.id_unidad AND retomas_escena.id_escena =  esc.id)) FROM plan_diario_has_escenas_has_unidades
		INNER JOIN plan_diario ON plan_diario.id = plan_diario_has_escenas_has_unidades.id_plan_diario
		INNER JOIN unidad ON unidad.id = plan_diario.id_unidad
		WHERE (plan_diario.estado != 1 AND plan_diario.estado != 5) AND id_escena = esc.id)  AS planes_abiertos,
		(SELECT group_concat(ele.nombre, COALESCE(CONCAT('(',extras_escena.cantidad,')'),'')   
		ORDER BY ele.nombre separator ', ') FROM escenas_has_elementos  
		INNER JOIN elemento ele on ele.id = escenas_has_elementos.id_elemento
		LEFT OUTER JOIN extras_escena ON extras_escena.id_escenas_has_elementos = escenas_has_elementos.id
		WHERE id_escena = esc.id AND ele.id_tipo_elemento = ".$idpersonajes." AND (rol = 2 OR rol = 4) ORDER BY ele.nombre) as personajes_secundarios,
		(SELECT group_concat(ele.nombre ORDER BY ele.nombre separator ', ' ) FROM escenas_has_elementos  
		INNER JOIN elemento ele on ele.id = escenas_has_elementos.id_elemento
		WHERE id_escena = esc.id AND ele.id_tipo_elemento != ".$idpersonajes." ) as elementos 
		FROM escena esc 
		INNER JOIN produccion_has_capitulos cap ON cap.id = esc.id_capitulo 
		INNER JOIN locacion loc ON loc.id = esc.id_locacion
		INNER JOIN sets se ON se.id = esc.id_set 
		INNER JOIN escena_interior_esterior ext ON ext.id = esc.id_interior_esterior 
		INNER JOIN escenas_dias_noche dn ON dn.id = esc.id_dia_noche 
		INNER JOIN tipo_locacion tip ON tip.id = esc.id_tipo_locacion
		INNER JOIN magnitud_escena ON magnitud_escena.id = esc.id_magnitud 
		LEFT OUTER JOIN plan_diario_has_escenas_has_unidades plan_esc ON plan_esc.id_escena = esc.id 
		AND plan_esc.id_plan_diario = 
		(SELECT MAX(plan_diario.id) 
		FROM plan_diario 
		INNER JOIN plan_diario_has_escenas_has_unidades pl ON pl.id_plan_diario = plan_diario.id 
		WHERE pl.id_escena = esc.id AND plan_diario.estado !=5) 
		LEFT OUTER JOIN plan_diario plan_dia ON plan_dia.id = plan_esc.id_plan_diario 
		LEFT OUTER JOIN unidad uni ON uni.id = plan_dia.id_unidad 
		WHERE cap.id_produccion =".$idproduccion." 
		AND esc.id=".$id_escena);
	    if ($query->num_rows>0) {
	      return $query->result();
	    }else{
	      return false;
	    }
  	}
}