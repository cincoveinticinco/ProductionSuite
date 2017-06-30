<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	
class model_escenas_2 extends CI_Model {

	public function buscar_escenas_id_capitulo($id_capitulo){
		$query =$this->db->query("SELECT DISTINCT (escena.id), escena.*, magnitud_escena.descripcion AS magnitud, locacion.nombre AS locacionnombre, sets.nombre AS setnombre, 
			uni.numero AS unidad_numero, plan_dia.fecha_inicio AS fecha_inicio, (SELECT COUNT(id) FROM retomas_escena where id_escena = escena.id AND estado != 1) AS retomas
			, (SELECT group_concat('U',unidad.numero, '_', DATE_FORMAT(plan_diario.fecha_inicio, '%d-%b-%Y'),'_', estados_plan_diario.descripcion,'_' 
			, COALESCE(retomas_escena.tiempo,0) ORDER BY plan_diario.fecha_inicio DESC SEPARATOR ',' ) 
			FROM plan_diario_has_escenas_has_unidades
			INNER JOIN plan_diario ON plan_diario.id = plan_diario_has_escenas_has_unidades.id_plan_diario
			INNER JOIN unidad ON unidad.id = plan_diario.id_unidad
			INNER JOIN estados_plan_diario ON estados_plan_diario.id = plan_diario.estado
			LEFT OUTER JOIN retomas_escena ON retomas_escena.id_escena = plan_diario_has_escenas_has_unidades.id_escena AND retomas_escena.fecha_produccion = plan_diario.fecha_inicio AND retomas_escena.unidad_produccion = unidad.id
			WHERE plan_diario_has_escenas_has_unidades.id_escena = escena.id) AS planes_escena ,
            (SELECT sum(capitulos_has_escenas.tiempo_post_minutos) from capitulos_has_escenas
			where capitulos_has_escenas.id_escena=escena.id) tiempo_post_minutos,
			(SELECT sum(capitulos_has_escenas.tiempo_post_segundos) from capitulos_has_escenas
			where capitulos_has_escenas.id_escena=escena.id) tiempo_post_segundos,
			(SELECT sum(capitulos_has_escenas.tiempo_post_cuadros) from capitulos_has_escenas
			where capitulos_has_escenas.id_escena=escena.id) tiempo_post_cuadros,
			(SELECT unidad.numero FROM unidad where unidad.id = escena.unidad_produccion) AS unidad_produccion
			FROM escena
			INNER JOIN sets ON sets.id = escena.id_set
			INNER JOIN produccion_has_capitulos cap ON cap.id = escena.id_capitulo
			INNER JOIN locacion ON locacion.id = escena.id_locacion
			LEFT OUTER JOIN plan_diario_has_escenas_has_unidades plan_esc ON plan_esc.id_escena = escena.id
			AND plan_esc.id_plan_diario = 
			(SELECT MAX(plan_diario.id) 
			FROM plan_diario 
			INNER JOIN plan_diario_has_escenas_has_unidades pl ON pl.id_plan_diario = plan_diario.id 
			WHERE pl.id_escena = escena.id AND plan_diario.estado !=5) 
      		LEFT OUTER JOIN plan_diario plan_dia ON plan_dia.id = plan_esc.id_plan_diario 
			LEFT OUTER JOIN unidad uni ON uni.id = plan_dia.id_unidad 
			INNER JOIN magnitud_escena ON magnitud_escena.id = escena.id_magnitud
			WHERE id_capitulo =". $id_capitulo."  GROUP BY escena.id ORDER BY CAST(SUBSTRING_INDEX(numero_escena,'.', 1 ) AS UNSIGNED) ASC,
			(CASE INSTR(numero_escena, '.') 
			WHEN 0 THEN 0
			ELSE CAST(SUBSTRING_INDEX(numero_escena,'.', -1 ) AS UNSIGNED) END) ASC;");
		if ($query->num_rows>0) {
	        return $query->result();
      	} else {
        	return false;
      	}
	}

	public function buscar_escenas_producidas_id_capitulo($id_capitulo){
		$query = $this->db->query("SELECT DISTINCT(escena.id), escena.*, locacion.nombre AS locacionnombre, sets.nombre AS setnombre 
			FROM escena
			INNER JOIN sets ON sets.id = escena.id_set
			INNER JOIN locacion ON locacion.id = escena.id_locacion
			WHERE id_capitulo =". $id_capitulo." AND escena.estado = 5 ORDER BY numero_escena ASC;");
		if ($query->num_rows>0) {
	        return $query->result();
      	} else {
        	return false;
      	}
	}

	public function buscar_elementos_escena($id_escena){
		$query =$this->db->query("SELECT escenas_has_elementos.*, categoria_elemento.tipo AS categoria, categoria_elemento.id AS categoriaid, elemento.nombre AS nombre_elemento, elemento.id AS idelemento, elemento.id_tipo_elemento AS idtipoelemento, rol_actores_elementos.rol AS rol_elemento,
			(SELECT cantidad FROM extras_escena WHERE escenas_has_elementos.id = extras_escena.id_escenas_has_elementos) AS cantidad, (SELECT cantidad FROM Vehiculos_background WHERE escenas_has_elementos.id = Vehiculos_background.id_has_elementos) AS cantidad2   
			FROM escenas_has_elementos
			INNER JOIN elemento ON  elemento.id = escenas_has_elementos.id_elemento
			INNER JOIN categoria_elemento ON categoria_elemento.id = elemento.id_tipo_elemento
			LEFT OUTER JOIN rol_actores_elementos ON elemento.rol = rol_actores_elementos.id
			WHERE id_escena =". $id_escena.";");
		if ($query->num_rows>0) {
	        return $query->result();
      	} else {
        	return false;
      	}
	}

	public function buscar_locaciones($id){
		$this->db->where('id_produccion',$id);
		$this->db->order_by("nombre", "asc"); 
		$query=$this->db->get('locacion');

	    if ($query->num_rows>0){
	     return $query->result();
	    } else {
	     return false;
	    }
	}

	public function filtar_escenas_continuidad_todos($datos){
		$query =$this->db->query("SELECT escena.*, locacion.nombre AS locacionnombre, sets.nombre AS setnombre, 
			uni.numero AS unidad_numero, plan_dia.fecha_inicio AS fecha_inicio  
			FROM escena
			INNER JOIN sets ON sets.id = escena.id_set
			INNER JOIN locacion ON locacion.id = escena.id_locacion
			INNER JOIN produccion_has_capitulos cap ON cap.id = escena.id_capitulo
			LEFT OUTER JOIN plan_diario_has_escenas_has_unidades plan_esc ON plan_esc.id_escena = escena.id
      		LEFT OUTER JOIN plan_diario plan_dia ON plan_dia.id = plan_esc.id_plan_diario AND plan_dia.estado != 5
			LEFT OUTER JOIN unidad uni ON uni.id = plan_dia.id_unidad 
			WHERE escena.id_locacion = ". $datos['locacion']." AND sets.id = ".$datos['set']." AND dias_continuidad = ".$datos['continuidad']." AND numero_escena BETWEEN ".$datos['limite1']."  AND ".$datos['limite2']." ;");
		if ($query->num_rows>0) {
	        return $query->result();
      	}else{
        	return false;
      	}
	}

	public function filtar_escenas_todos($datos){
		$query =$this->db->query("SELECT escena.*, locacion.nombre AS locacionnombre, sets.nombre AS setnombre,
			uni.numero AS unidad_numero, plan_dia.fecha_inicio AS fecha_inicio  
			FROM escena
			INNER JOIN sets ON sets.id = escena.id_set
			INNER JOIN locacion ON locacion.id = escena.id_locacion
			INNER JOIN produccion_has_capitulos cap ON cap.id = escena.id_capitulo
			LEFT OUTER JOIN plan_diario_has_escenas_has_unidades plan_esc ON plan_esc.id_escena = escena.id
      		LEFT OUTER JOIN plan_diario plan_dia ON plan_dia.id = plan_esc.id_plan_diario plan_dia.estado != 5
			LEFT OUTER JOIN unidad uni ON uni.id = plan_dia.id_unidad 
			WHERE escena.id_locacion = ". $datos['locacion']." AND sets.id = ".$datos['set']." AND numero_escena BETWEEN ".$datos['limite1']."  AND ".$datos['limite2']." ;");
		if ($query->num_rows>0) {
	        return $query->result();
      	}else{
        	return false;
      	}
	}

	public function filtar_escenas_numero($datos){
		$query =$this->db->query("SELECT escena.*, locacion.nombre AS locacionnombre, sets.nombre AS setnombre,
			uni.numero AS unidad_numero, plan_dia.fecha_inicio AS fecha_inicio  
			FROM escena
			INNER JOIN sets ON sets.id = escena.id_set
			INNER JOIN locacion ON locacion.id = escena.id_locacion
			INNER JOIN produccion_has_capitulos cap ON cap.id = escena.id_capitulo
			LEFT OUTER JOIN plan_diario_has_escenas_has_unidades plan_esc ON plan_esc.id_escena = escena.id
      		LEFT OUTER JOIN plan_diario plan_dia ON plan_dia.id = plan_esc.id_plan_diario plan_dia.estado != 5
			LEFT OUTER JOIN unidad uni ON uni.id = plan_dia.id_unidad 
			WHERE escena.id_locacion = ". $datos['locacion']." AND sets.id = ".$datos['set']." AND numero_escena >= ".$datos['numero_escena']."  OR numero_escena <=".$datos['numero_escena'].";");
		if ($query->num_rows>0) {
	        return $query->result();
      	}else{
        	return false;
      	}
	}

	public function filtar_escenas_continuidad($datos){
		$query =$this->db->query("SELECT escena.*, locacion.nombre AS locacionnombre, sets.nombre AS setnombre,
			uni.numero unidad_numero, plan_dia.fecha_inicio AS fecha_inicio  
			FROM escena
			INNER JOIN sets ON sets.id = escena.id_set
			INNER JOIN locacion ON locacion.id = escena.id_locacion
			INNER JOIN produccion_has_capitulos cap ON cap.id = escena.id_capitulo
			LEFT OUTER JOIN plan_diario_has_escenas_has_unidades plan_esc ON plan_esc.id_escena = escena.id
      		LEFT OUTER JOIN plan_diario plan_dia ON plan_dia.id = plan_esc.id_plan_diario plan_dia.estado != 5
			LEFT OUTER JOIN unidad uni ON uni.id = plan_dia.id_unidad 
			WHERE id_capitulo = ".$datos['idcapitulo']." AND escena.id_locacion = ". $datos['locacion']." AND sets.id = ".$datos['set']." AND dias_continuidad = ".$datos['continuidad']." AND numero_escena BETWEEN ".$datos['limite1']."  AND ".$datos['limite2']." ;");
		if ($query->num_rows>0) {
	        return $query->result();
      	}else{
        	return false;
      	}
	}

	public function filtar_escenas($idcapitulo,$sql){
		$query =$this->db->query("SELECT DISTINCT (escena.id),escena.*, magnitud_escena.descripcion AS magnitud,locacion.nombre AS locacionnombre, sets.nombre AS setnombre,
			uni.numero  AS unidad_numero, plan_dia.fecha_inicio AS fecha_inicio, (SELECT unidad.numero FROM unidad where unidad.id = escena.unidad_produccion) AS unidad_produccion  
			,
			(SELECT group_concat('U',unidad.numero, '_', DATE_FORMAT(plan_diario.fecha_inicio, '%d-%b-%Y'),'_', estados_plan_diario.descripcion,'_' 
			, COALESCE(retomas_escena.tiempo,0) ORDER BY plan_diario.fecha_inicio DESC SEPARATOR ',') 
			FROM plan_diario_has_escenas_has_unidades
			INNER JOIN plan_diario ON plan_diario.id = plan_diario_has_escenas_has_unidades.id_plan_diario
			INNER JOIN unidad ON unidad.id = plan_diario.id_unidad
			INNER JOIN estados_plan_diario ON estados_plan_diario.id = plan_diario.estado
			LEFT OUTER JOIN retomas_escena ON retomas_escena.id_escena = plan_diario_has_escenas_has_unidades.id_escena AND retomas_escena.fecha_produccion = plan_diario.fecha_inicio AND retomas_escena.unidad_produccion = unidad.id
			WHERE plan_diario_has_escenas_has_unidades.id_escena = escena.id) AS planes_escena ,
			(SELECT sum(capitulos_has_escenas.tiempo_post_minutos) from capitulos_has_escenas
			where capitulos_has_escenas.id_escena=escena.id) tiempo_post_minutos,
			(SELECT sum(capitulos_has_escenas.tiempo_post_segundos) from capitulos_has_escenas
			where capitulos_has_escenas.id_escena=escena.id) tiempo_post_segundos,
			(SELECT sum(capitulos_has_escenas.tiempo_post_cuadros) from capitulos_has_escenas
			where capitulos_has_escenas.id_escena=escena.id) tiempo_post_cuadros,
			(SELECT COUNT(id) FROM retomas_escena where id_escena = escena.id) AS retomas FROM escena
			INNER JOIN sets ON sets.id = escena.id_set
			INNER JOIN locacion ON locacion.id = escena.id_locacion
			INNER JOIN produccion_has_capitulos cap ON cap.id = escena.id_capitulo
			LEFT OUTER JOIN plan_diario_has_escenas_has_unidades plan_esc ON plan_esc.id_escena = escena.id
			AND plan_esc.id_plan_diario = 
			(SELECT MAX(plan_diario.id) 
			FROM plan_diario
			INNER JOIN plan_diario_has_escenas_has_unidades pl ON pl.id_plan_diario = plan_diario.id 
			WHERE pl.id_escena = escena.id AND plan_diario.estado !=5)
      		LEFT OUTER JOIN plan_diario plan_dia ON plan_dia.id = plan_esc.id_plan_diario
			LEFT OUTER JOIN unidad uni ON uni.id = plan_dia.id_unidad 
			INNER JOIN magnitud_escena ON magnitud_escena.id = escena.id_magnitud
			WHERE id_capitulo = ".$idcapitulo." ".$sql." 
			ORDER BY CAST(SUBSTRING_INDEX(numero_escena,'.', 1 ) AS UNSIGNED) ASC,
			(CASE INSTR(numero_escena, '.') 
			WHEN 0 THEN 0
			ELSE CAST(SUBSTRING_INDEX(numero_escena,'.', -1 ) AS UNSIGNED) END) ASC
			");
		if ($query->num_rows>0) {
	        return $query->result();
      	}else{
        	return false;
      	}
	}

	public function filtar_escenas_continuidad_numero($datos){
		$query =$this->db->query("SELECT escena.*, locacion.nombre AS locacionnombre, sets.nombre AS setnombre,
			uni.numero  AS unidad_numero, plan_dia.fecha_inicio AS fecha_inicio 
			FROM escena
			INNER JOIN sets ON sets.id = escena.id_set
			INNER JOIN locacion ON locacion.id = escena.id_locacion
			INNER JOIN produccion_has_capitulos cap ON cap.id = escena.id_capitulo
			LEFT OUTER JOIN plan_diario_has_escenas_has_unidades plan_esc ON plan_esc.id_escena = escena.id
      		LEFT OUTER JOIN plan_diario plan_dia ON plan_dia.id = plan_esc.id_plan_diario AND plan_dia.estado != 5
			LEFT OUTER JOIN unidad uni ON uni.id = plan_dia.id_unidad 
			WHERE escena.id_locacion = ". $datos['locacion']." AND sets.id = ".$datos['set']." AND dias_continuidad = ".$datos['continuidad']." AND numero_escena  >= ".$datos['numero_escena']."  OR  numero_escena  <=".$datos['numero_escena']." ;");
		if ($query->num_rows>0) {
	        return $query->result();
      	}else{
        	return false;
      	}
	}


	public function cancelar_escena($id){
		$data=array(
            'estado'=>3/*,
            'fecha_produccion'=>'',
            'unidad_produccion'=>''*/
        );
        $this->db->WHERE('id',$id);
        $this->db->update('escena',$data);

        /*$this->db->WHERE('id_escena',$id);
        $this->db->delete('plan_diario_has_escenas_has_unidades');*/
	}

	public function eliminar_escena($idescena){
		$this->db->WHERE('id',$idescena);
        $this->db->delete('escena');
	}

	public function duplicar_escena($idescena, $idcapitulo, $numero_escena,$estado){
		$query= $this->db->query("INSERT INTO escena SELECT (SELECT max(id) FROM escena)+1,".$numero_escena.", 
		".$idcapitulo.", id_locacion,id_set, dias_continuidad, id_tipo_locacion, id_dia_noche, id_interior_esterior, id_flasback,id_toma_ubicacion,id_foto_realizacion,id_imagenes_archivo,duracion_estimada_minutos, duracion_estimada_segundos, '00', '00',id_producida, descripcion, '',".$estado.", libreto, '','', id_magnitud, vehiculo_background
		FROM escena WHERE id = ".$idescena.";");
	}

	public function insertar_escena_nueva($data){
		return $this->db->insert('escena',$data);
	}

	public function escena_id($id){
        $query = $this->db->query('SELECT escena.*, produccion_has_capitulos.numero AS numero_libreto, locacion.nombre AS locacionnombre, magnitud_escena.descripcion AS magnitud, locacion.id AS locacionid, sets.nombre AS setnombre, sets.id AS setid, tipo_locacion.tipo AS tipolocacion, 
        	tipo_locacion.id AS tipolocacionid, escenas_dias_noche.id AS dianocheid, escenas_dias_noche.descripcion AS dianochenombre, escenas_flasback.id AS flashbackid, escenas_flasback.descripcion AS flashbacknombre,
        	escena_interior_esterior.id AS interiorid, escena_interior_esterior.descripcion AS interiornombre, produccion_has_capitulos.id_produccion
			FROM escena
			INNER JOIN sets ON sets.id = escena.id_set
			INNER JOIN locacion ON locacion.id = escena.id_locacion 
			INNER JOIN tipo_locacion ON tipo_locacion.id = escena.id_tipo_locacion 
			INNER JOIN escenas_dias_noche ON escenas_dias_noche.id = escena.id_dia_noche
			INNER JOIN escenas_flasback ON escenas_flasback.id = escena.id_flasback
			INNER JOIN magnitud_escena ON magnitud_escena.id = escena.id_magnitud
			INNER JOIN escena_interior_esterior ON escena_interior_esterior.id = escena.id_interior_esterior
			INNER JOIN produccion_has_capitulos ON produccion_has_capitulos.id = escena.id_capitulo
			WHERE escena.id = '.$id);
        if ($query->num_rows>0) {
	        return $query->result();
      	}else{
        	return false;
      	}
	}

	public function escena_by_id($id){
		$query = $this->db->query('SELECT produccion_has_capitulos.numero as numero_libreto,escena.*, locacion.nombre AS locacionnombre, locacion.id AS locacionid, sets.nombre AS setnombre, sets.id AS setid
			FROM escena
			INNER JOIN produccion_has_capitulos ON produccion_has_capitulos.id = escena.id_capitulo 
			INNER JOIN sets ON sets.id = escena.id_set
			INNER JOIN locacion ON locacion.id = escena.id_locacion 
			WHERE escena.id = '.$id);
        if ($query->num_rows>0) {
	        return $query->result();
      	}else{
        	return false;
      	}
	} 

	public function buscar_escena_numero_idcapitulo($numero, $idcapitulo){
		$query = $this->db->query('SELECT * FROM escena 
        				WHERE escena.numero = '.$numero.' AND escena.id_capitulo = '.$idcapitulo.';');
        if ($query->num_rows>0) {
	        return $query->result();
      	}else{
        	return false;
      	}
	}

	public function suma_tiempos($idcapitulo){
        $query= $this->db->query("SELECT SUM(duracion_estimada_minutos) AS minutos, SUM(duracion_estimada_segundos) AS segundos  FROM escena
		WHERE estado != 12 AND id_capitulo =".$idcapitulo.";");
        return $query->result();
	}

	public function suma_tiempos_producidas($idcapitulo){
        $query= $this->db->query("SELECT SUM(duracion_real_minutos) AS minutos, SUM(duracion_real_segundos) AS segundos  FROM escena
		WHERE id_capitulo =".$idcapitulo.";");
        return $query->result();
	}

	public function sumar_tiempos_produccion($idproduccion){
		$query= $this->db->query("SELECT SUM(duracion_real_minutos) AS minutos, SUM(duracion_real_segundos) AS segundos FROM escena
		INNER JOIN produccion_has_capitulos on produccion_has_capitulos.id = escena.id_capitulo
		WHERE (escena.duracion_real_minutos!='00' OR escena.duracion_real_segundos!='00') AND id_produccion = ".$idproduccion.";");
        return $query->result();
	}

	public function actualizar_producidas($idcapitulo){
        $query= $this->db->query("UPDATE produccion_has_capitulos SET escenas_producidas = (SELECT COUNT(id) FROM escena
		WHERE (estado = 1 OR estado =2 OR estado =12 OR estado =14) AND id_capitulo = ".$idcapitulo.") WHERE id = ".$idcapitulo.";");
	}

	public function eliminar_escenas_no_producidas($idcapitulo){
		$this->db->WHERE('id_capitulo',$idcapitulo);
		$this->db->WHERE('estado !=',1);
		$this->db->WHERE('estado !=',2);
		$this->db->WHERE('estado !=',12);
		$this->db->WHERE('estado !=',14);
        //$this->db->delete('escena');
        $data=array(
            'estado'=>3
        );
        //$this->db->WHERE('estado',1);
        $this->db->update('escena', $data);
	}

	public function buscar_elemento($id){
		$this->db->WHERE('id',$id);
		$query = $this->db->get('categoria_elemento');
        return $query->result();
	}

	public function contar_escenas_noproducidas($idproduccion){
		$query= $this->db->query("SELECT COUNT(escena.id) AS cantidad FROM escena
		INNER JOIN produccion_has_capitulos on produccion_has_capitulos.id = escena.id_capitulo
		WHERE escena.estado !=1 AND escena.estado !=3 AND escena.estado !=2 AND escena.estado !=12 AND escena.estado !=14 AND id_produccion = ".$idproduccion.";");
        return $query->result();
	}

	public function suma_tiempos_ajax($sql){
		$query = $this->db->query("SELECT SUM(duracion_estimada_minutos) AS minutos, SUM(duracion_estimada_segundos) AS segundos FROM escena ".$sql.";");
		return $query->result();
	}

	public function suma_tiempos_prod($sql){
		$query = $this->db->query("SELECT SUM(duracion_real_minutos) AS minutos, SUM(duracion_real_segundos) AS segundos FROM escena ".$sql.";");
		return $query->result();
	}



	public function cambia_estado($idescena,$estado){
		$query = $this->db->query("UPDATE escena SET estado = ".$estado." WHERE id = ".$idescena.";");
	}

	public function escenas_producidas_idcapitulo($idcapitulo){
		$query= $this->db->query("SELECT escena.* FROM escena
		WHERE (escena.duracion_real_minutos!='00' OR escena.duracion_real_segundos!='00' OR escena.estado = 1 OR escena.estado = 2 OR escena.estado = 12 OR escena.estado = 14) AND id_capitulo = ".$idcapitulo.";");
        return $query->result();
	}

	public function sumar_tiempos_reales($idcapitulo){
		$query= $this->db->query("SELECT SUM(duracion_real_minutos) AS minutos, SUM(duracion_real_segundos) AS segundos FROM escena
		WHERE escena.estado=1 AND id_capitulo = ".$idcapitulo.";");
        return $query->result();
	}

	public function escenas_producidas_produccion($idproduccion){
		$query= $this->db->query("SELECT DISTINCT(escena.id), escena.*, escena.unidad_produccion AS id_unidad FROM escena
		INNER JOIN produccion_has_capitulos ON produccion_has_capitulos.id = escena.id_capitulo
		LEFT OUTER JOIN plan_diario_has_escenas_has_unidades ON plan_diario_has_escenas_has_unidades.id_escena = escena.id
		INNER JOIN plan_diario ON plan_diario_has_escenas_has_unidades.id_plan_diario = plan_diario.id 
		INNER JOIN unidad ON unidad.id=plan_diario.id_unidad 
		WHERE (escena.duracion_real_minutos!='00' OR escena.duracion_real_segundos!='00') AND fecha_produccion != '' AND produccion_has_capitulos.id_produccion= ".$idproduccion.";");
        return $query->result();
	}

	public function escenas_producidas_produccion2($idproduccion,$inicio_semana,$fin_semana,$id_unidad){
		$query= $this->db->query("SELECT count(escena.id) total_escenas,sum(escena.duracion_real_minutos) as duracion_real_minutos_total,sum(escena.duracion_real_segundos) duracion_real_segudos_total from escena 
		INNER JOIN produccion_has_capitulos ON produccion_has_capitulos.id=escena.id_capitulo
		where unidad_produccion=".$id_unidad."  and(escena.duracion_real_minutos!='00'  OR escena.duracion_real_segundos!='00')
		AND fecha_produccion != '' and fecha_produccion>='".$inicio_semana."' and fecha_produccion<='".$fin_semana."' AND produccion_has_capitulos.id_produccion=".$idproduccion);
        return $query->result();
	}

	public function insert_remotas_escena($data){
		return $this->db->insert('retomas_escena',$data);
	}

	public function remotas_escena($idescena){
		$this->db->where('id_escena', $idescena);
		$this->db->where('estado = ',1);
		$query = $this->db->get('retomas_escena');
		if ($query->num_rows>0) {
	        return $query->result();
      	}else{
        	return false;
      	}
	}

	public function estados_color(){
		$query= $this->db->query("SELECT id, descripcion,(CASE id 
									WHEN 1 THEN '#fee93e' 
									WHEN 2 THEN '#fec63e'
									WHEN 3 THEN '#000000' 
									WHEN 4 THEN '#09eee9' 
									WHEN 5 THEN '#a6fff9' 
									WHEN 6 THEN '#e3228d' 
									WHEN 7 THEN '#ef7bbb'
									WHEN 8 THEN '#8cdd16'  
									WHEN 9 THEN '#c1f378'
									WHEN 10 THEN '#f7921e'
									WHEN 11 THEN '#faca9a'
									ELSE 'td_brown_light' END) AS color from estados where id<12;");
		if ($query->num_rows>0) {
	        return $query->result();
      	}else{
        	return false;
      	}
	}

	public function buscar_personajes_escena($idescena){
		$query = $this->db->query("SELECT * FROM 
									(SELECT elemento.nombre, rol_actores_elementos.rol, extras_escena.cantidad, categoria_elemento.tipo
									FROM escenas_has_elementos
									INNER JOIN elemento ON escenas_has_elementos.id_elemento = elemento.id
									INNER JOIN rol_actores_elementos ON rol_actores_elementos.id = elemento.rol
									INNER JOIN categoria_elemento ON categoria_elemento.id = elemento.id_tipo_elemento
									LEFT OUTER JOIN extras_escena ON extras_escena.id_escenas_has_elementos  = escenas_has_elementos.id
									WHERE escenas_has_elementos.id_escena = ".$idescena." AND (elemento.rol = 1 OR elemento.rol = 3) ORDER BY elemento.rol, elemento.nombre 
									) DUMMY_ALIAS1
									UNION ALL
									SELECT * FROM 
									(SELECT elemento.nombre, rol_actores_elementos.rol, extras_escena.cantidad, categoria_elemento.tipo
									FROM escenas_has_elementos
									INNER JOIN elemento ON escenas_has_elementos.id_elemento = elemento.id
									INNER JOIN rol_actores_elementos ON rol_actores_elementos.id = elemento.rol
									INNER JOIN categoria_elemento ON categoria_elemento.id = elemento.id_tipo_elemento
									LEFT OUTER JOIN extras_escena ON extras_escena.id_escenas_has_elementos  = escenas_has_elementos.id
									WHERE escenas_has_elementos.id_escena = ".$idescena." AND (elemento.rol = 2 OR elemento.rol = 4) ORDER BY elemento.rol, elemento.nombre) DUMMY_ALIAS2;");
		if ($query->num_rows>0) {
	        return $query->result();
	  	}else{
	    	return false;
	  	}
	}

	public function buscar_elementos($id_escena){
		$query =$this->db->query("SELECT categoria_elemento.tipo AS categoria, elemento.nombre AS nombre_elemento, elemento.id AS idelemento,
			Vehiculos_background.cantidad
			FROM escenas_has_elementos
			INNER JOIN elemento ON  elemento.id = escenas_has_elementos.id_elemento
			INNER JOIN categoria_elemento ON categoria_elemento.id = elemento.id_tipo_elemento
			LEFT OUTER JOIN Vehiculos_background ON Vehiculos_background.id_has_elementos  = escenas_has_elementos.id
			WHERE id_escena =". $id_escena." AND elemento.rol IS NULL ORDER BY elemento.nombre;");
		if ($query->num_rows>0) {
	        return $query->result();
      	}else{
        	return false;
      	}
	}

	public function total_post_produccidas($id_capitulo){
		$query =$this->db->query("SELECT COUNT(DISTINCT(capitulos_has_escenas.id_escena)) total from escena 
					inner join capitulos_has_escenas on capitulos_has_escenas.id_escena=escena.id
					where escena.id_capitulo=".$id_capitulo."
					and (capitulos_has_escenas.tiempo_post_minutos+capitulos_has_escenas.tiempo_post_segundos)<>0");
		if ($query->num_rows>0) {
	        return $query->result();
      	}else{
        	return false;
      	}
	}

	public function capitulo_idescena($id_escena){
		$query =$this->db->query("SELECT * from capitulos_has_escenas h
			inner join capitulos c on c.id=h.id_capitulo
			where h.id_escena=".$id_escena);
		if ($query->num_rows>0) {
	        return $query->result();
      	}else{
        	return false;
      	}
	}

	
	
}
?>