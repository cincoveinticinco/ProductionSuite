<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class model_casting extends CI_Model {

	public function nacionalidades(){
		$query=$this->db->get('nacionalidades');
	    if ($query->num_rows>0){
	      return $query->result();
	    } else {
	      return false;
	    }
	}

	public function nacionalidad_id($id){
		$this->db->where('id',$id);
		$query=$this->db->get('nacionalidades');
	    if ($query->num_rows>0){
	      return $query->result();
	    } else {
	      return false;
	    }
	}

	public function tipo_documento_id($id){
		$this->db->where('id',$id);
		$query=$this->db->get('tipo_documento');
	    if ($query->num_rows>0){
	      return $query->result();
	    } else {
	      return false;
	    }
	}

	public function generos(){
		$query=$this->db->get('generos');
	    if ($query->num_rows>0){
	      return $query->result();
	    } else {
	      return false;
	    }
	}

	public function genero_id($id){
		$this->db->where('id',$id);
		$query=$this->db->get('generos');
	    if ($query->num_rows>0){
	      return $query->result();
	    } else {
	      return false;
	    }
	}

	public function color_tez_id($id){
		$this->db->where('id',$id);
		$query=$this->db->get('color_tez');
	    if ($query->num_rows>0){
	      return $query->result();
	    } else {
	      return false;
	    }
	}

	public function color_ojos_id($id){
		$this->db->where('id',$id);
		$query=$this->db->get('color_ojos');
	    if ($query->num_rows>0){
	      return $query->result();
	    } else {
	      return false;
	    }
	}

	public function idioma_id($id){
		$this->db->where('id',$id);
		$query=$this->db->get('idiomas');
	    if ($query->num_rows>0){
	      return $query->result();
	    } else {
	      return false;
	    }
	}

	public function rol_id($id){
		$this->db->where('id',$id);
		$query=$this->db->get('rol_actores_elementos');
	    if ($query->num_rows>0){
	      return $query->result();
	    } else {
	      return false;
	    }
	}

	public function paises(){
		$this->db->order_by('nombre', 'asc');
		$query=$this->db->get('paises');
	    if ($query->num_rows>0){
	      return $query->result();
	    } else {
	      return false;
	    }
	}

	public function paises_visa($id_actor,$tipo=6){
		$query=$this->db->query("SELECT * FROM paises WHERE paises.id NOT IN (SELECT (CASE pais WHEN null THEN '0' else pais END) FROM documentos_actor WHERE id_tipo_documentacion = ".$tipo." AND id_actor = ".$id_actor." );");
		if ($query->num_rows>0){
	      return $query->result();
	    } else {
	      return false;
	    }
	}

	public function colores_tez(){
		$query=$this->db->get('color_tez');
	    if ($query->num_rows>0){
	      return $query->result();
	    } else {
	      return false;
	    }
	}

	public function colores_ojos(){
		$query=$this->db->get('color_ojos');
	    if ($query->num_rows>0){
	      return $query->result();
	    } else {
	      return false;
	    }
	}

	public function idiomas(){
		$query=$this->db->get('idiomas');
	    if ($query->num_rows>0){
	      return $query->result();
	    } else {
	      return false;
	    }
	}

	public function get_tipos_documentacion(){
		$query=$this->db->get('tipo_documentacion');
	    if ($query->num_rows>0){
	      return $query->result();
	    } else {
	      return false;
	    }
	}

	public function get_contactos($value=''){
		$query=$this->db->get('contactos');
	    if ($query->num_rows>0){
	      return $query->result();
	    } else {
	      return false;
	    }
	}

	// public function destrezas(){
	// 	$query=$this->db->get('destrezas');
	//     if ($query->num_rows>0){
	//       return $query->result();
	//     } else {
	//       return false;
	//     }
	// }

	public function get_sociedades(){
		$query=$this->db->get('sociedades');
	    if ($query->num_rows>0){
	      return $query->result();
	    } else {
	      return false;
	    }
	}

	public function ciudades_pais($idpais){
		$this->db->where('id_pais',$idpais);
		$this->db->order_by('nombre','ASC');
		$query=$this->db->get('ciudades');
	    if ($query->num_rows>0){
	      return $query->result();
	    } else {
	      return false;
	    }
	}

	public function objetos_contrato(){
		$query=$this->db->get('objetos_contrato');
	    if ($query->num_rows>0){
	      return $query->result();
	    } else {
	      return false;
	    }
	}

	public function lugares_servicio(){
		$query=$this->db->get('lugar_servicio');
	    if ($query->num_rows>0){
	      return $query->result();
	    } else {
	      return false;
	    }
	}

	public function areas_solicitantes(){
		$query=$this->db->get('area_solicitante');
	    if ($query->num_rows>0){
	      return $query->result();
	    } else {
	      return false;
	    }
	}

	public function clases_entidades(){
		$query=$this->db->get('clases_entidades');
	    if ($query->num_rows>0){
	      return $query->result();
	    } else {
	      return false;
	    }
	}

	public function estados_entidades(){
		$query=$this->db->get('estados_entidad');
	    if ($query->num_rows>0){
	      return $query->result();
	    } else {
	      return false;
	    }
	}

	public function insert_actor($datos){
		return $this->db->insert('actores',$datos);
	}

	public function update_actor($datos){
		$this->db->where('id',$datos['id']);
		return $this->db->update('actores',$datos);
	}

	public function update_solicitudes_actores($datos){
		$this->db->where('id',$datos['id']);
		return $this->db->update('solicitudes_actores',$datos);
	}

	public function delete_actor($id_actor){
		$this->db->where('id', $id_actor);
		$this->db->delete('actores');
	}

	public function insert_foto($datos){
		return $this->db->insert('fotos_actor',$datos);
	}

	public function insert_documentos_actor($datos){
		return $this->db->insert('documentos_actor',$datos);
	}

	public function update_documentos_actor($datos){
		$this->db->where('id',$datos['id']);
		return $this->db->update('documentos_actor',$datos);
	}

	public function update_documentos_solicitud($datos){
		$this->db->where('id',$datos['id']);
		return $this->db->update('documentos_solicitud',$datos);
	}

	public function update_documentos_null($datos){
		$this->db->where('id_tipo_documentacion',$datos['id_tipo_documentacion']);
		$this->db->where('id_actor',$datos['id_actor']);
		return $this->db->update('documentos_actor',$datos);
	}

	public function eliminar_documentos_actor($idactor,$sql,$tipo){
		$this->db->query("DELETE FROM documentos_actor WHERE id_tipo_documentacion=".$tipo." AND id_actor =".$idactor." AND ".$sql);
	}

	public function eliminar_documentos_solicitud($idsolicitud,$sql){
		$this->db->query("DELETE FROM documentos_solicitud WHERE id_solicitud =".$idsolicitud." AND ".$sql);
	}

	public function eliminar_documentos_solicitudID($id){
		$this->db->query("DELETE FROM documentos_solicitud WHERE id =".$id);
	}


	public function actualizar_documento_solicitud($datos){
		$this->db->where('id',$datos['id']);
		return $this->db->update('documentos_solicitud',$datos);
	}

	
	public function eliminar_documentos($id){
		$this->db->where('id',$id);
		 return $this->db->delete('documentos_actor');
	}


	// public function insert_destreza($datos){
	// 	return $this->db->insert('destrezas_actor',$datos);
	// }

	public function insert_idioma($datos){
		return $this->db->insert('idiomas_actor',$datos);
	}

	public function insert_papel_actor($datos){
		return $this->db->insert('papeles_actor',$datos);
	}

	public function delete_papel_actor($id_actor){
		$this->db->where('id_actor', $id_actor);
		return $this->db->delete('papeles_actor');
	}

	public function validar_documento($documento,$tipo_documento,$id_actor=""){
		if ($id_actor!="") {
			$this->db->where('actores.id != ',$id_actor);
		}
		$this->db->where('documento',$documento);
		$this->db->where('id_tipo_documento',$tipo_documento);
		$query=$this->db->get('actores');
	    if ($query->num_rows>0){
	      return false;
	    } else {
	      return true;
	    }
	}

	public function get_actores(){
		$query=$this->db->get('actores');
		if ($query->num_rows>0){
	      return $query->result();
	    } else {
	      return true;
	    }
	}

	public function get_personajes_no_uso($idproduccion,$idrol){
		$query = $this->db->query("SELECT elemento.id, elemento.nombre
							FROM elemento
							INNER JOIN categoria_elemento ON categoria_elemento.id = elemento.id_tipo_elemento
							WHERE elemento.rol IS NOT NULL AND elemento.rol != 5 
							AND elemento.id NOT IN (SELECT id_elemento FROM solicitud_has_elementos WHERE solicitud_has_elementos.id_elemento = elemento.id and en_uso=1)
							AND categoria_elemento.id_produccion = ".$idproduccion." AND elemento.rol = ".$idrol." AND elemento.rol != 4 ORDER BY elemento.nombre ASC;");
		if ($query->num_rows>0){
	      return $query->result();
	    } else {
	      return true;
	    }
	}

	public function filtro_actores($sql,$sql_inner){
		$query= $this->db->query("SELECT DISTINCT(actores.id), actores.nombre, actores.apellido, 
									(SELECT fotos_actor.ruta_foto FROM fotos_actor WHERE fotos_actor.id_actor = actores.id ORDER BY id LIMIT 1) AS ruta_foto,
									nacionalidades.descripcion FROM actores
									INNER JOIN nacionalidades ON nacionalidades.id = actores.id_nacionalidad
									INNER JOIN generos ON generos.id = actores.id_genero
									INNER JOIN tipo_documento ON tipo_documento.id = actores.id_tipo_documento
									INNER JOIN color_ojos ON color_ojos.id = actores.id_color_ojos
									INNER JOIN color_tez ON color_tez.id = actores.id_color_tez
									".$sql_inner." 
									WHERE actores.id = actores.id ".$sql." ORDER BY actores.nombre, actores.apellido;");
		if ($query->num_rows>0){
	      return $query->result();
	    } else {
	      return false;
	    }
	}

	public function filtro_actores_palabra($palabra){
		$query = $this->db->query("SELECT actores.id, actores.nombre, actores.apellido, 
								  	(SELECT fotos_actor.ruta_foto FROM fotos_actor WHERE fotos_actor.id_actor = actores.id ORDER BY id LIMIT 1) AS ruta_foto,
									nacionalidades.descripcion FROM actores
									INNER JOIN nacionalidades ON nacionalidades.id = actores.id_nacionalidad
									WHERE actores.nombre LIKE '%".$palabra."%' OR actores.apellido LIKE '%".$palabra."%' ORDER BY actores.nombre, actores.apellido;	
									");
		if ($query->num_rows>0){
	      return $query->result();
	    } else {
	      return false;
	    }
	}

	public function actor_id($idactor){
		$query = $this->db->query("SELECT actores.*, 
							sociedades.nit AS nit_sociedad, sociedades.nombre AS razon_social_sociedad, 
							sociedades.telefono_fijo AS telefono_fijo_sociedad, sociedades.direccion AS direccion_sociedad,
							sociedades.telefono_movil AS telefono_movil_sociedad,
							sociedades.email AS email_sociedad, sociedades.documento_representante AS representante_documento ,
							sociedades.representante_legal,sociedades.documento_representante,
							nacionalidades.descripcion AS nacionalidad, nacionalidades.id AS id_nacionalidad,  color_ojos.descripcion AS color_ojos, color_ojos.id AS id_color_ojos,
							color_tez.descripcion AS color_tez, color_tez.id AS id_color_tez, tipo_documento.descripcion AS tipo_documento, tipo_documento.id AS id_tipo_documento, generos.descripcion AS genero, generos.id AS id_generos,
							tipo_documento2.descripcion AS tipo_documento_representante_legal,
							(SELECT group_concat(idm.descripcion) FROM idiomas idm 
							INNER JOIN idiomas_actor ida ON ida.id_idioma = idm.id
							WHERE ida.id_actor = actores.id) AS idiomas, ciudades.nombre AS nombre_ciudad, paises.nombre AS nombre_pais, paises.id AS id_paises,	
							managers.id AS id_manager, managers.nombre AS nombre_manager, managers.telefono_fijo AS telefono_fijo_manager, managers.telefono_movil AS telefono_movil_manager,
							managers.email AS email_manager, c2.nombre AS ciudad_sociedad, c2.id AS id_ciudad_sociedad, p2.nombre AS pais_sociedad, p2.id AS id_pais_sociedad, rol_actores_elementos.rol AS rol,   
							(SELECT group_concat(idm.id) FROM idiomas idm 
							INNER JOIN idiomas_actor ida ON ida.id_idioma = idm.id
							WHERE ida.id_actor = actores.id) AS idiomas_actor, actores.contactar, contactos.descripcion AS contacto, 
							(YEAR(CURRENT_DATE) - YEAR(actores.fecha_nacimiento)) - (RIGHT(CURRENT_DATE,5) < RIGHT(actores.fecha_nacimiento,5)) AS edad
							FROM actores 
							LEFT OUTER JOIN generos ON generos.id = actores.id_genero
							LEFT OUTER JOIN tipo_documento ON tipo_documento.id = actores.id_tipo_documento
							LEFT OUTER JOIN tipo_documento tipo_documento2 ON tipo_documento2.id = actores.id_tipo_documento_representante_legal
							LEFT OUTER JOIN color_ojos ON color_ojos.id = actores.id_color_ojos
							LEFT OUTER JOIN color_tez ON color_tez.id = actores.id_color_tez
							LEFT OUTER JOIN nacionalidades ON nacionalidades.id = actores.id_nacionalidad
							LEFT OUTER JOIN idiomas_actor ON idiomas_actor.id_actor = actores.id
							LEFT OUTER JOIN idiomas ON  idiomas.id = idiomas_actor.id_idioma
							LEFT OUTER JOIN ciudades ON ciudades.id = actores.ciudad
							LEFT OUTER JOIN sociedades ON sociedades.id = actores.id_sociedad
							LEFT OUTER JOIN paises ON paises.id  = actores.pais
							LEFT OUTER JOIN paises p2 ON p2.id  = sociedades.pais
							LEFT OUTER JOIN ciudades c2 ON c2.id = sociedades.ciudad
							LEFT OUTER JOIN managers On managers.id = actores.id_manager
							LEFT OUTER JOIN contactos ON contactos.id = actores.contactar
							LEFT OUTER JOIN rol_actores_elementos On rol_actores_elementos.id = actores.id_rol
							WHERE actores.id = ".$idactor.";");
		if ($query->num_rows>0){
	      return $query->result();
	    } else {
	      return false;
	    }
	}

	public function proyectos_actor($idactor){
		$this->db->where('id_actor',$idactor);
		$this->db->join('rol_actores_elementos', 'rol_actores_elementos.id = papeles_actor.id_rol');
		$query=$this->db->get('papeles_actor');
		if ($query->num_rows>0){
	      return $query->result();
	    } else {
	      return false;
	    }
	}

	public function fotos_actor($idactor){
		$this->db->where('id_actor',$idactor);
		$query=$this->db->get('fotos_actor');
		if ($query->num_rows>0){
	      return $query->result();
	    } else {
	      return false;
	    }
	}

	public function fotos_actor_cargadas($idactor){
		$this->db->where('id_actor',$idactor);
		$this->db->where('ruta_foto IS NOT NULL');
		$query=$this->db->get('fotos_actor');
		if ($query->num_rows>0){
	      return $query->result();
	    } else {
	      return false;
	    }
	}

	public function anadir_manager($datos){
		return $this->db->insert('managers',$datos);
	}
	public function update_manager($datos){
		$this->db->where('id',$datos['id']);
		$this->db->update('managers',$datos);
	}
	

	public function get_managers(){
		$query=$this->db->get('managers');
		if ($query->num_rows>0){
	      return $query->result();
	    } else {
	      return false;
	    }
	}

	public function manager_id($id){
		$this->db->where('id',$id);
		$query=$this->db->get('managers');
		if ($query->num_rows>0){
	      return $query->result();
	    } else {
	      return false;
	    }
	}

	public function insert_solicitud($datos){
		return $this->db->insert('solicitudes',$datos);
	}

	public function update_solicitud($datos){
		$this->db->where('id',$datos['id']);
		return $this->db->update('solicitudes',$datos);
	}

	public function insert_aprobacion_solicitud($datos){
		return $this->db->insert('aprobaciones_solicitud',$datos);
	}

	public function insert_solicitud_elemento($datos){
		return $this->db->insert('solicitud_has_elementos',$datos);
	}

	public function update_solicitud_elemento($datos){
		$this->db->where('id_solicitud',$datos['id_solicitud']);
		$this->db->where('id_elemento',$datos['id_elemento']);
		$data=array('en_uso'=>0);
		return $this->db->update('solicitud_has_elementos',$data);
	}

	public function delete_solcitud_elemento($idsolicitud){
		$this->db->where('id_solicitud',$idsolicitud);
		return $this->db->delete('solicitud_has_elementos');
	}

	public function solicitud_id($idsolicitud){
		$query= $this->db->query("SELECT solicitudes.id_actor as id_actorp,solicitudes.id AS id_solicitud,actores.id_nacionalidad, solicitudes.tipo, solicitudes.mes_otro_si,solicitudes.contrato_firmado, solicitudes.contrato_personal, solicitudes.contrato, concat(user.nombre, ' ', user.apellido ) AS nombre_usuario,
							fecha_contrato_selec,area_solicitante.descripcion AS area_solicitante, area_solicitante.id AS id_area, solicitudes.id_solicitud_anexa,solicitudes.fecha_final_otro_si,solicitudes.honorarios_otro_si,
							solicitudes.honorarios_letras_otro_si,
							lugar_servicio.descripcion AS lugar_servicio, lugar_servicio.id AS id_lugar,
							objetos_contrato.descripcion AS objeto_contrato, objetos_contrato.id AS id_objeto, 
							tipo_contrato.descripcion AS forma_pago, tipo_contrato.id AS id_forma,
							produccion.nombre_produccion AS produccion, produccion.id AS id_produccion,  solicitudes.id_estado, estados_solicitud.descripcion AS estado,
							concat(actores.nombre, ' ', actores.apellido) AS actor,
							tipo_documento.descripcion AS tipo_documento, actores.documento AS documento, nacionalidades.descripcion AS nacionalidad,
							actores.direccion AS direccion, actores.telefono_fijo AS telefono_fijo, actores.telefono_movil AS telefono_movil,
							actores.email, UPPER(ciudades.nombre) AS ciudad, UPPER(paises.nombre) AS pais,
							actores.nombre_representante_legal,actores.telefono_fijo_representante,actores.celular_representante,sociedades.documento_representante,
							actores.direccion_representante,actores.correo_representante,actores.documento_representante_legal,tipo_documento2.descripcion as tipo_documento_representante_legal,
							managers.nombre as nombre_manager,managers.telefono_fijo as telefono_fijo_manager,
							managers.telefono_movil as telefono_movil_manager,managers.email as email_manager,
							sociedades.id AS id_sociedad,
							sociedades.nombre AS razon_social_sociedad, sociedades.nit AS nit_sociedad, sociedades.direccion AS direccion_sociedad, sociedades.telefono_fijo AS telefono_fijo_sociedad, sociedades.email AS email_sociedad, sociedades.representante_legal, sociedades.documento_representante AS representante_documento ,
							sociedades.telefono_movil AS telefono_movil_sociedad, UPPER(p2.nombre) as pais_sociedad, UPPER(ci2.nombre) AS ciudad_sociedad, (
							SELECT group_concat(DISTINCT(produccion_has_capitulos.numero) order by produccion_has_capitulos.numero) FROM produccion_has_capitulos  
							INNER JOIN escena On escena.id_capitulo = produccion_has_capitulos.id 
							INNER JOIN escenas_has_elementos ON escenas_has_elementos.id_escena = escena.id WHERE escenas_has_elementos.id_elemento = elemento.id) AS libretos,
							actores.contacto_nombre, actores.contacto_telefono, actores.contacto_telefono_movil, actores.contacto_email,
							actores.id AS id_actor, nacionalidades.id AS id_nacionalidad,
							solicitudes.fecha_inicio, solicitudes.fecha_final, solicitudes.id_tipo_moneda, tipo_documento.descripcion as descExt, paises.nombre as paisExt, ciudades.nombre as ciudExt, solicitudes.honorarios, solicitudes.honorarios_letras,
							solicitudes.id_forma_pago, solicitudes.condiciones_especiales, solicitudes.sugerencias_contratacion, solicitudes.razon_otro_si, tipo_moneda.id AS id_tipo_moneda, tipo_moneda.descripcion AS tipo_moneda,
							group_concat(elemento.nombre) AS elementos, group_concat(elemento.rol) AS roles, 
							estados_solicitud.tipo_usuario AS tipo_usuario_estado, estados_solicitud.rol AS rol_estado,  
							estados_solicitud.responsable AS responsable, fecha_contrato_firmado, CURRENT_DATE,
							(YEAR(CURRENT_DATE) - YEAR(actores.fecha_nacimiento)) - (RIGHT(CURRENT_DATE,5) < RIGHT(actores.fecha_nacimiento,5)) AS edad,
							(SELECT group_concat(rol_actores_elementos.rol) FROM rol_actores_elementos WHERE rol_actores_elementos.id = elemento.rol) AS roles_lista
							FROM solicitud_has_elementos 
							
							LEFT OUTER JOIN solicitudes ON  solicitudes.id  = solicitud_has_elementos.id_solicitud 
							INNER JOIN estados_solicitud on estados_solicitud.id = solicitudes.id_estado 
							LEFT OUTER JOIN elemento ON elemento.id  = solicitud_has_elementos.id_elemento 
							inner JOIN actores ON actores.id = solicitudes.id_actor
							LEFT OUTER JOIN managers ON managers.id = actores.id_manager 
							LEFT OUTER JOIN generos ON generos.id = actores.id_genero  
							LEFT OUTER JOIN nacionalidades ON nacionalidades.id = actores.id_nacionalidad 
							LEFT OUTER JOIN tipo_documento ON tipo_documento.id = actores.id_tipo_documento
							LEFT OUTER JOIN tipo_documento tipo_documento2 ON tipo_documento2.id = actores.id_tipo_documento_representante_legal 
							LEFT OUTER JOIN user ON user.id = solicitudes.id_usuario 
							LEFT OUTER JOIN area_solicitante ON area_solicitante.id = solicitudes.area_solicitante 
							LEFT OUTER JOIN lugar_servicio ON lugar_servicio.id = solicitudes.id_lugar_servicio 
							LEFT OUTER JOIN objetos_contrato ON objetos_contrato.id = solicitudes.id_objeto_contrato 
							LEFT OUTER JOIN categoria_elemento ON categoria_elemento.id = elemento.id_tipo_elemento 
							LEFT OUTER JOIN produccion ON produccion.id = categoria_elemento.id_produccion 
							LEFT OUTER JOIN rol_actores_elementos ON rol_actores_elementos.id = elemento.rol 
							LEFT OUTER JOIN tipo_contrato ON tipo_contrato.id = solicitudes.id_forma_pago 
							LEFT OUTER JOIN sociedades ON sociedades.id = actores.id_sociedad
							LEFT OUTER JOIN ciudades ON ciudades.id = actores.ciudad 
							LEFT OUTER JOIN ciudades ci2 ON ci2.id = sociedades.ciudad 
							LEFT OUTER JOIN paises ON paises.id = actores.pais 
							LEFT OUTER JOIN paises p2 ON p2.id = sociedades.pais 
							LEFT OUTER JOIN tipo_moneda ON tipo_moneda.id = solicitudes.id_tipo_moneda 
							WHERE solicitudes.id = ".$idsolicitud.";");
		if ($query->num_rows>0){
	      return $query->result();
	     
	    } else {
	      return false;
	    }
	}

	public function elementos_solicitud($idsolicitud){
		$query = $this->db->query("SELECT elemento.id,elemento.nombre, rol_actores_elementos.rol AS rol, rol_actores_elementos.id AS id_rol 
									FROM elemento 
									INNER JOIN rol_actores_elementos ON rol_actores_elementos.id = elemento.rol 
									INNER JOIN solicitud_has_elementos ON solicitud_has_elementos.id_elemento = elemento.id 
									WHERE id_solicitud = ".$idsolicitud.";");
		if ($query->num_rows>0){
	      return $query->result();
	    } else {
	      return false;
	    }
	}

	public function historial_solicitud($idsolicitud){
		$query = $this->db->query("SELECT aprobaciones_solicitud.id, aprobaciones_solicitud.fecha_aprobacion, aprobaciones_solicitud.notas, 
									concat(user.nombre, ' ', user.apellido ) AS nombre_usuario, estados_solicitud.id AS id_estado, estados_solicitud.descripcion AS estado
									FROM aprobaciones_solicitud
									INNER JOIN user ON user.id = aprobaciones_solicitud.id_usuario
									INNER JOIN estados_solicitud ON estados_solicitud.id = aprobaciones_solicitud.id_estado
									WHERE aprobaciones_solicitud.id_solicitud = ".$idsolicitud." 
									ORDER BY aprobaciones_solicitud.id DESC;");
		if ($query->num_rows>0){
	      return $query->result();
	    } else {
	      return false;
	    }
	}

	public function solicitudes_actor($idactor){
		$query = $this->db->query("SELECT solicitudes.id AS id_solicitud, estados_solicitud.id AS id_estado,  estados_solicitud.descripcion AS estados_solicitud, 
									solicitudes.honorarios, DATE_FORMAT(solicitudes.fecha_creacion, '%d-%b-%Y') AS fecha_creacion,  
									solicitudes.fecha_inicio, solicitudes.fecha_final, tipo_moneda.descripcion AS tipo_moneda
									, produccion.nombre_produccion, concat(elemento.nombre) AS elementos
									FROM solicitudes
									INNER JOIN estados_solicitud ON estados_solicitud.id = solicitudes.id_estado 
									INNER JOIN tipo_moneda ON tipo_moneda.id = solicitudes.id_tipo_moneda
									INNER JOIN solicitud_has_elementos ON solicitud_has_elementos.id_solicitud = solicitudes.id
									INNER JOIN elemento On elemento.id = solicitud_has_elementos.id_elemento
									INNER JOIN categoria_elemento ON categoria_elemento.id = elemento.id_tipo_elemento
									INNER JOIN produccion ON produccion.id = categoria_elemento.id_produccion
									WHERE solicitudes.id_actor = ".$idactor." 
									ORDER BY fecha_creacion, estados_solicitud.id ASC;");
		if ($query->num_rows>0){
	      return $query->result();
	    } else {
	      return false;
	    }
	}

	public function videos_actor($idactor){
		$this->db->where('id_actor',$idactor);
		$query=$this->db->get('videos_actor');
		if ($query->num_rows>0){
	      return $query->result();
	    } else {
	      return false;
	    }
	}

	public function personajes_produccion($idproduccion,$sql=""){
		$query = $this->db->query("SELECT DISTINCT(elemento.id) AS id_elemento, elemento.nombre AS elemento_nombre, CONCAT(actores.nombre, ' ', actores.apellido) AS actor_nombre,
									solicitudes.id AS id_solicitud, actores.id AS id_actor, produccion.estado AS estado_produccion, 
									(SELECT DATE_FORMAT(MIN(plan_diario.fecha_inicio), '%d-%b-%Y') FROM plan_diario
										INNER JOIN plan_diario_has_escenas_has_unidades pd ON pd.id_plan_diario = plan_diario.id
										INNER JOIN escenas_has_elementos ON escenas_has_elementos.id_escena = pd.id_escena
										WHERE escenas_has_elementos.id_elemento = elemento.id  ) fecha_inicio, 
									estados_solicitud.id AS id_estado, solicitudes.id AS id_solicitud,estados_solicitud.descripcion AS estado_solicitud, rol_actores_elementos.rol AS rol
									FROM elemento 
									LEFT OUTER JOIN solicitud_has_elementos ON solicitud_has_elementos.id_elemento =  elemento.id  
									LEFT OUTER JOIN solicitudes ON solicitudes.id = solicitud_has_elementos.id_solicitud
									LEFT OUTER JOIN actores ON actores.id = solicitudes.id_actor
									LEFT OUTER JOIN estados_solicitud ON estados_solicitud.id = solicitudes.id_estado
									LEFT OUTER JOIN rol_actores_elementos on rol_actores_elementos.id = elemento.rol
									LEFT OUTER JOIN categoria_elemento on categoria_elemento.id = elemento.id_tipo_elemento
									LEFT OUTER JOIN produccion on categoria_elemento.id_produccion = produccion.id
									WHERE rol_actores_elementos.id !=4 AND categoria_elemento.id_produccion=".$idproduccion."   ".$sql." GROUP BY elemento.id ORDER BY elemento.nombre,fecha_inicio,estado_solicitud ");
		if ($query->num_rows>0){	
	      return $query->result();
	    }else{
	      return false;
	    }
	}

	/*VERIFICAR PERSONAJE CON CONTRATO EXPIRADO*/
	public function fechas_personaje($id_personaje){
		$query = $this->db->query("SELECT MAX(solicitudes.fecha_final) AS fecha_contrato, MAX(plan_diario.fecha_inicio) AS fecha_plan_diario
									FROM solicitudes
									INNER JOIN solicitud_has_elementos ON solicitud_has_elementos.id_solicitud = solicitudes.id
									INNER JOIN elemento ON elemento.id = solicitud_has_elementos.id_elemento
									INNER JOIN escenas_has_elementos ON escenas_has_elementos.id_elemento = elemento.id
									INNER JOIN escena ON escena.id =  escenas_has_elementos.id_escena
									INNER JOIN plan_diario_has_escenas_has_unidades ON plan_diario_has_escenas_has_unidades.id_escena = escena.id
									INNER JOIN plan_diario ON plan_diario.id = plan_diario_has_escenas_has_unidades.id_plan_diario
									WHERE elemento.id = ".$id_personaje." AND solicitudes.id_estado = 11;");
		if ($query->num_rows>0){	
	      return $query->result();
	    }else{
	      return false;
	    }
	}


	public function bitacora_elemento($idelemento, $idactor){
		$query=$this->db->query("SELECT estados_solicitud.descripcion AS estado, CONCAT(user.nombre, ' ',user.apellido ) AS usuario,
							aprobaciones_solicitud.fecha_aprobacion AS fecha
							FROM aprobaciones_solicitud
							INNER JOIN user on user.id = aprobaciones_solicitud.id_usuario
							INNER JOIN estados_solicitud ON estados_solicitud.id = aprobaciones_solicitud.id_estado
							INNER JOIN solicitudes ON solicitudes.id = aprobaciones_solicitud.id_solicitud
							INNER JOIN solicitud_has_elementos ON solicitud_has_elementos.id_solicitud = solicitudes.id
							WHERE solicitud_has_elementos.id_elemento = ".$idelemento." AND solicitudes.id_actor =".$idactor." 
							ORDER BY aprobaciones_solicitud.fecha_aprobacion, aprobaciones_solicitud.id_estado;");
		if ($query->num_rows>0){	
	      return $query->result();
	    }else{
	      return false;
	    }
	}

	public function aprobar_solicitud($data){
		return $this->db->insert('aprobaciones_solicitud',$data);
	}

	public function insertVideoActor($data){
		return $this->db->insert('videos_actor',$data);
	}

	public function deleteVideoActor($id_actor){
		$this->db->where('id_actor',$id_actor);
		return $this->db->delete('videos_actor');
	}

	public function get_all_solicitudes($sql=""){
		$query = $this->db->query("SELECT solicitudes.id AS id_solicitud,actores.id_nacionalidad, solicitudes.tipo, solicitudes.id_solicitud_anexa, categoria_elemento.id_produccion AS id_produccion, solicitudes.id, estados_solicitud.descripcion, CONCAT(actores.nombre,' ',actores.apellido) as actor,
						(SELECT elemento.nombre FROM elemento 
						  	INNER JOIN solicitud_has_elementos s ON s.id_elemento = elemento.id
						  	WHERE  s.id_solicitud = solicitudes.id LIMIT 1) AS elementos,

						(SELECT elemento.id FROM elemento 
						  	INNER JOIN solicitud_has_elementos s ON s.id_elemento = elemento.id
						  	WHERE  s.id_solicitud = solicitudes.id LIMIT 1) AS id_elemento,

						  solicitudes.honorarios AS monto, 	solicitudes.id_estado, solicitudes.fecha_inicio, solicitudes.fecha_final,
						  produccion.nombre_produccion AS nombre_produccion,tipo_moneda.id AS id_tipo_moneda, solicitudes.condiciones_especiales,
						  tipo_moneda.descripcion AS tipo_moneda, tipo_contrato.descripcion AS forma_pago, tipo_contrato.id AS id_forma_pago, estados_solicitud.responsable AS responsable, 
						  (SELECT group_concat(ele.rol) FROM elemento ele where ele.id = elemento.id) AS roles,
						  (SELECT group_concat(rol_actores_elementos.rol) FROM rol_actores_elementos WHERE rol_actores_elementos.id = elemento.rol) AS roles_lista,
						  estados_solicitud.rol AS rol_estado, estados_solicitud.tipo_usuario AS tipo_usuario_estado
						  FROM solicitudes 
						  INNER JOIN estados_solicitud ON estados_solicitud.id = solicitudes.id_estado
						  LEFT OUTER JOIN actores ON actores.id = solicitudes.id_actor
						  LEFT OUTER JOIN solicitud_has_elementos ON solicitud_has_elementos.id_solicitud = solicitudes.id
						  LEFT OUTER JOIN elemento ON elemento.id = solicitud_has_elementos.id_elemento
						  LEFT OUTER JOIN categoria_elemento ON categoria_elemento.id = elemento.id_tipo_elemento
						  LEFT OUTER JOIN produccion ON produccion.id = categoria_elemento.id_produccion
						  INNER JOIN tipo_moneda ON tipo_moneda.id = solicitudes.id_tipo_moneda
						  INNER JOIN tipo_contrato ON tipo_contrato.id = solicitudes.id_forma_pago


						  WHERE 1 ".$sql." ORDER BY solicitudes.id ASC;");
		if ($query->num_rows>0){	
	      return $query->result();
	    }else{
	      return false;
	    }
	}
	public function get_solicitudes_tipo_usuario($tipo_usuario,$sql){
		$query = $this->db->query("SELECT solicitudes.id AS id_solicitud, actores.id_nacionalidad,solicitudes.tipo, solicitudes.id_solicitud_anexa, categoria_elemento.id_produccion AS id_produccion, solicitudes.id, estados_solicitud.descripcion, CONCAT(actores.nombre,' ',actores.apellido) as actor,

						  (SELECT elemento.nombre FROM elemento 
						  	INNER JOIN solicitud_has_elementos s ON s.id_elemento = elemento.id
						  	WHERE  s.id_solicitud = solicitudes.id LIMIT 1) AS elementos,

						(SELECT elemento.id FROM elemento 
						  	INNER JOIN solicitud_has_elementos s ON s.id_elemento = elemento.id
						  	WHERE  s.id_solicitud = solicitudes.id LIMIT 1) AS id_elemento,

						  solicitudes.honorarios AS monto, 	solicitudes.id_estado, solicitudes.fecha_inicio, solicitudes.fecha_final,
						  produccion.nombre_produccion AS nombre_produccion,tipo_moneda.id AS id_tipo_moneda, solicitudes.condiciones_especiales,
						  tipo_moneda.descripcion AS tipo_moneda, tipo_contrato.descripcion AS forma_pago, tipo_contrato.id AS id_forma_pago, estados_solicitud.responsable AS responsable, 
						  (SELECT group_concat(ele.rol) FROM elemento ele where ele.id = elemento.id) AS roles,
  						  (SELECT group_concat(rol_actores_elementos.rol) FROM rol_actores_elementos WHERE rol_actores_elementos.id = elemento.rol) AS roles_lista,

						  estados_solicitud.rol AS rol_estado, estados_solicitud.tipo_usuario AS tipo_usuario_estado
						  FROM solicitudes 
						  INNER JOIN estados_solicitud ON estados_solicitud.id = solicitudes.id_estado
						  LEFT OUTER JOIN actores ON actores.id = solicitudes.id_actor
						  LEFT OUTER JOIN solicitud_has_elementos ON solicitud_has_elementos.id_solicitud = solicitudes.id
						  LEFT OUTER JOIN elemento ON elemento.id = solicitud_has_elementos.id_elemento
						  LEFT OUTER JOIN categoria_elemento ON categoria_elemento.id = elemento.id_tipo_elemento
						  LEFT OUTER JOIN produccion ON produccion.id = categoria_elemento.id_produccion
						  INNER JOIN tipo_moneda ON tipo_moneda.id = solicitudes.id_tipo_moneda
						  INNER JOIN tipo_contrato ON tipo_contrato.id = solicitudes.id_forma_pago		

						   WHERE estados_solicitud.tipo_usuario LIKE '%,".$tipo_usuario.",%'  ".$sql." ORDER BY solicitudes.id ASC;");
		if ($query->num_rows>0){	
	      return $query->result();
	    }else{
	      return false;
	    }
	}

	public function get_solicitudes_rol_otros($rol,$sql){
		$query = $this->db->query("SELECT solicitudes.id AS id_solicitud, actores.id_nacionalidad,solicitudes.tipo, solicitudes.id_solicitud_anexa, categoria_elemento.id_produccion AS id_produccion, solicitudes.id, estados_solicitud.descripcion, CONCAT(actores.nombre,' ',actores.apellido) as actor,

						  (SELECT elemento.nombre FROM elemento 
						  	INNER JOIN solicitud_has_elementos s ON s.id_elemento = elemento.id
						  	WHERE  s.id_solicitud = solicitudes.id LIMIT 1) AS elementos,

						  (SELECT elemento.id FROM elemento 
						  	INNER JOIN solicitud_has_elementos s ON s.id_elemento = elemento.id
						  	WHERE  s.id_solicitud = solicitudes.id LIMIT 1) AS id_elemento,

						  solicitudes.honorarios AS monto, 	solicitudes.id_estado, solicitudes.fecha_inicio, solicitudes.fecha_final,
						  produccion.nombre_produccion AS nombre_produccion,tipo_moneda.id AS id_tipo_moneda, solicitudes.condiciones_especiales,
						  tipo_moneda.descripcion AS tipo_moneda, tipo_contrato.descripcion AS forma_pago, tipo_contrato.id AS id_forma_pago, estados_solicitud.responsable AS responsable, 
						  (SELECT group_concat(ele.rol) FROM elemento ele where ele.id = elemento.id) AS roles,
						  (SELECT group_concat(rol_actores_elementos.rol) FROM rol_actores_elementos WHERE rol_actores_elementos.id = elemento.rol) AS roles_lista,

						  estados_solicitud.rol AS rol_estado, estados_solicitud.tipo_usuario AS tipo_usuario_estado
						  FROM solicitudes 
						  INNER JOIN estados_solicitud ON estados_solicitud.id = solicitudes.id_estado
						  LEFT OUTER JOIN actores ON actores.id = solicitudes.id_actor
						  LEFT OUTER JOIN solicitud_has_elementos ON solicitud_has_elementos.id_solicitud = solicitudes.id
						  LEFT OUTER JOIN elemento ON elemento.id = solicitud_has_elementos.id_elemento
						  LEFT OUTER JOIN categoria_elemento ON categoria_elemento.id = elemento.id_tipo_elemento
						  LEFT OUTER JOIN produccion ON produccion.id = categoria_elemento.id_produccion
						  INNER JOIN tipo_moneda ON tipo_moneda.id = solicitudes.id_tipo_moneda
						  INNER JOIN tipo_contrato ON tipo_contrato.id = solicitudes.id_forma_pago		

						  WHERE estados_solicitud.rol LIKE '%,".$rol.",%' ".$sql." ORDER BY solicitudes.id ASC;");
		if ($query->num_rows>0){	
	      return $query->result();
	    }else{
	      return false;
	    }
	}

	public function asignar_contrato($data){
		$this->db->where('id',$data['id']);
		$this->db->update('solicitudes',$data);
	}

	// public function get_contratos(){
	// 	//	$this->db->order_by('nombre', 'ASC');
	// 	$query=$this->db->get('contratos');
	// 	if ($query->num_rows>0){
	//       return $query->result();
	//     } else {
	//       return false;
	//     }
	// }

	public function get_contratos1(){
		$query=$this->db->query('SELECT * FROM `contratos` WHERE `id`<19');
		if ($query->num_rows>0){
	      return $query->result();
	    } else {
	      return false;
	    }
	}

	public function get_contratos2(){
		$query=$this->db->query('SELECT * FROM `contratos` WHERE `id`>=19');
		if ($query->num_rows>0){
	      return $query->result();
	    } else {
	      return false;
	    }
	}

	public function contrato_id($idcontrato){
		$this->db->select('contratos.id, contratos.nombre, CAST(  `contrato` AS CHAR( 1000000 )) AS contrato');
		$this->db->where('id',$idcontrato);
		$query=$this->db->get('contratos');
		if ($query->num_rows>0){
	      return $query->result();
	    } else {
	      return false;
	    }
	}

	public function columnas_usuario($iduser,$tipo){
	  $this->db->where('id_usuario',$iduser);
	  $this->db->where('tipo',$tipo);
	  $query=$this->db->get('columnas_usuario');
	    if ($query->num_rows>0) {
	      return $query->result();
	    }else{
	      return false;
	    }
	}

	public function documentos_actor($idactor){
		$query=$this->db->query("SELECT  documentos_actor.id,
						tipo_documentacion.id AS id_tipo_documentacion,
						tipo_documentacion.descripcion AS tipo_documentacion,
						documentos_actor.url,
						documentos_actor.vigencia,
						documentos_actor.descripcion,
						estados_entidad.descripcion as estado_entidad,
						clases_entidades.descripcion as clase_entidade,
						estados_entidad.id as id_estado_entidad,
						clases_entidades.id as id_clase_entidad,
						paises.nombre AS nombre_pais, documentos_actor.pais AS pais
						FROM documentos_actor
						INNER JOIN tipo_documentacion ON tipo_documentacion.id = documentos_actor.id_tipo_documentacion
						LEFT OUTER JOIN estados_entidad On estados_entidad.id = documentos_actor.id_estado_entidad
						LEFT OUTER JOIN clases_entidades On clases_entidades.id = documentos_actor.id_clase_entidad
						LEFT OUTER JOIN paises On paises.id = documentos_actor.pais
						WHERE documentos_actor.id_actor = ".$idactor." 
						ORDER BY id_tipo_documentacion;");
		if ($query->num_rows>0) {
	      return $query->result();
	    }else{
	      return false;
	    }

	}

	public function buscar_libretos_personaje($idpersonaje){
		$query=$this->db->query("SELECT group_concat(DISTINCT(p2.numero) SEPARATOR ',') AS libretos
                          FROM produccion_has_capitulos p2
                          INNER JOIN escena e2 ON e2.id_capitulo = p2.id
                          INNER JOIN escenas_has_elementos eh ON eh.id_escena = e2.id
                          WHERE eh.id_elemento = ".$idpersonaje.";");
		if ($query->num_rows>0) {
	      return $query->result();
	    }else{
	      return false;
	    }
	}

	public function insert_contrato_firmado($data){
	    $this->db->WHERE('id',$data['id']);
	    return $this->db->update('solicitudes',$data);
  	}

  	public function comentarios_solicitud($idsolicitud){
  		$query=$this->db->query("SELECT CONCAT(user.nombre, ' ', user.apellido) AS usuario, 
  								comentarios_solicitud.comentario AS comentario,
  								comentarios_solicitud.fecha AS fecha 
  								FROM comentarios_solicitud
								INNER JOIN user ON user.id = comentarios_solicitud.id_usuario
  								WHERE id_solicitud =  ".$idsolicitud.";");
		if ($query->num_rows>0) {
	      return $query->result();
	    }else{
	      return false;
	    }
  	}

  	public function guardar_comentario_solicitud($data){
  		return $this->db->insert('comentarios_solicitud',$data);
  	}

  	public function buscar_aprobacion($idsolicitud,$idestado,$activo=""){
  		if ($activo!="") {
  			$this->db->where('activo !=',$activo);
  		}
  		$this->db->where('id_solicitud',$idsolicitud);
  		$this->db->where('id_estado',$idestado);
  		$query = $this->db->get('aprobaciones_solicitud');
	    if ($query->num_rows>0) {
	      return $query->result();
	    }else{
	      return false;
	    }
  	}

  	public function agregar_sugerencia_contratacion($data){
  		$this->db->where('id',$data['id']);
	    return $this->db->update('solicitudes',$data);
  	}

  	public function insert_documento_solcitud($data){
	    return $this->db->insert('documentos_solicitud',$data);
  	}

  	public function documentos_solicitud($idsolicitud){
  		$this->db->where('id_solicitud',$idsolicitud);
	    $query = $this->db->get('documentos_solicitud');
	    if ($query->num_rows>0) {
	      return $query->result();
	    }else{
	      return false;
	    }
  	}

  	public function maximo_videos($sql=""){
  		$query=$this->db->query("SELECT MAX(contador) as contador FROM
							    ( SELECT id, COUNT(videos_actor.id) contador
							    FROM videos_actor
								WHERE ".$sql."  GROUP BY id_actor ) T;");
		if ($query->num_rows>0) {
	      return $query->result();
	    }else{
	      return false;
	    }
  	}

  	public function documento_actor($idactor,$tipo){
  		$query=$this->db->query("SELECT * FROM documentos_actor WHERE documentos_actor.id_actor = ".$idactor." AND
  			documentos_actor.id_tipo_documentacion= ".$tipo." AND (documentos_actor.url!='' OR documentos_actor.descripcion!='' OR documentos_actor.id_clase_entidad!='' OR documentos_actor.id_estado_entidad!='' OR documentos_actor.pais!='');"

  			);
		if ($query->num_rows>0) {
	      return $query->result();
	    }else{
	      return false;
	    }
  	}

  	public function otros_documentos_actor($idactor,$tipo){
  		$this->db->select('documentos_actor.*, paises.nombre AS nombre_pais');
  		$this->db->from('documentos_actor');
  		$this->db->join('paises', 'paises.id = documentos_actor.pais', 'LEFT OUTER');
  		$this->db->where('id_tipo_documentacion',$tipo);
  		$this->db->where('id_actor',$idactor);
  		$query = $this->db->get();
  		if ($query->num_rows>0) {
	      return $query->result();
	    }else{
	      return false;
	    }
  	}


  	// ACTUALIZAR FOTO ACTOR
  	public function update_foto_actor($data){
  		$this->db->where('id',$data['id']);
  		$this->db->update('fotos_actor',$data);
  	}

  	public function elementos_solicitud_anexa($idsolicitud){
  		$this->db->where('id_solicitud',$idsolicitud);
  		$query = $this->db->get('solicitud_has_elementos');
  		if ($query->num_rows>0) {
	      return $query->result();
	    }else{
	      return false;
	    }
  	}

  	public function multiples_roles($id){
	   $query=$this->db->query("SELECT rol_otros.id  as rol
	   							FROM user_has_rol_otros
	   							INNER JOIN rol_otros ON rol_otros.id = user_has_rol_otros.id_rol_otros
	                  			WHERE id_user=".$id.";");
	      
	    if ($query->num_rows>0) {
	       return $query->result();
	    } else {
	       return false;
	    }
	}

	public function responsables_contrato(){
		$this->db->select('id, documento, UPPER(nombre) AS nombre');
		$query=$this->db->get('responsables_contrato');
		if ($query->num_rows>0) {
	       return $query->result();
	    } else {
	       return false;
	    }
	}

	public function update_representantes($data){
		$this->db->where('id',$data['id']);
		$this->db->update('responsables_contrato',$data);
	}

	/*ORDEN DE COLUMNAS*/
	public function buscar_columnas($data){
	    $this->db->where('id_usuario',$data['id_usuario']);
	    $this->db->where('tipo',$data['tipo']);
	    $query = $this->db->get('columnas_usuario');
	    if ($query->num_rows>0) {
	      return $query->result();
	    }else{
	      return false;
	    }
	}

	public function actualizar_columnas($data){
	    $this->db->where('id_usuario',$data['id_usuario']);
	    $this->db->where('tipo',$data['tipo']);
	    $this->db->update('columnas_usuario',$data);
	}

	public function agregar_columnas($data){
	    $this->db->insert('columnas_usuario',$data);
	}

	public function documento_extranjero($id_actor,$tipo){
		$this->db->select('documentos_actor.descripcion AS numero, paises.nombre AS pais, documentos_actor.vigencia');
		$this->db->join('paises', 'paises.id = documentos_actor.pais', ' left outer');
		$this->db->where('id_actor',$id_actor);
	    $this->db->where('id_tipo_documentacion',$tipo);
	    $this->db->where('pais',48);
	    $this->db->limit(1);
	    $this->db->order_by('vigencia', 'DESC');
	    $query =$this->db->get('documentos_actor');
		if ($query->num_rows>0) {
	      return $query->result();
	    }else{
	      return false;
	    }
	}

	public function fecha_inicial($id_elemento){
		$query = $this->db->query("SELECT DATE_FORMAT(plan_diario.fecha_inicio, '%d-%b-%Y') AS fecha_inicio
                          FROM escenas_has_elementos
                          INNER JOIN escena ON escenas_has_elementos.id_escena = escena.id
                          INNER JOIN plan_diario_has_escenas_has_unidades pu ON pu.id_escena = escena.id
                          INNER JOIN plan_diario ON plan_diario.id = pu.id_plan_diario
                          WHERE escenas_has_elementos.id_elemento = ".$id_elemento." ORDER BY plan_diario.fecha_inicio LIMIT 1 ");
		if ($query->num_rows>0) {
	      return $query->result();
	    }else{
	      return false;
	    }
	}

	public function fecha_maxima($id_elemento){
		$query = $this->db->query("SELECT MAX(DATE_FORMAT(plan_diario.fecha_inicio, '%d-%b-%Y')) AS fecha_inicio
                          FROM escenas_has_elementos
                          INNER JOIN escena ON escenas_has_elementos.id_escena = escena.id
                          INNER JOIN plan_diario_has_escenas_has_unidades pu ON pu.id_escena = escena.id
                          INNER JOIN plan_diario ON plan_diario.id = pu.id_plan_diario
                          WHERE escenas_has_elementos.id_elemento = ".$id_elemento." ORDER BY plan_diario.fecha_inicio");
		if ($query->num_rows>0) {
	      return $query->result();
	    }else{
	      return false;
	    }
	}

	public function get_sociedad_by_id($id_sociedad){
		$query = $this->db->query("select sociedades.*,ciudades.nombre ciudad_sociedad from sociedades
		inner join ciudades on sociedades.ciudad=ciudades.id
		 where sociedades.id=".$id_sociedad);
		if ($query->num_rows>0) {
	      return $query->result();
	    }else{
	      return false;
	    }
	}

	

	public function insert_sociedad($dataSociedad){
		$this->db->insert('sociedades', $dataSociedad);
	}

	public function update_sociedad($dataSociedad){
		$this->db->where('id',$dataSociedad['id']);
		$this->db->update('sociedades', $dataSociedad);
	}

	public function buscar_otro_si($id_solicitud){
		$this->db->select('solicitudes.*, estados_solicitud.descripcion AS descripcion, estados_solicitud.responsable ');
		$this->db->where('id_solicitud_anexa',$id_solicitud);
		$this->db->join('estados_solicitud', 'estados_solicitud.id = solicitudes.id_estado', 'inner');
		$query = $this->db->get('solicitudes');
		if ($query->num_rows>0) {
	      return $query->result();
	    }else{
	      return false;
	    }
	}

	public function update_aprocaciones($data,$idestado){
		if ($idestado!=5) {
			$this->db->where('id_solicitud',$data['id_solicitud']);
			$this->db->update('aprobaciones_solicitud', $data);
		}else{
			$this->db->where('id_solicitud',$data['id_solicitud']);
			$this->db->where('id_estado > ',$idestado);
			$this->db->update('aprobaciones_solicitud', $data);
		}
	}
	

	public function get_usuarios_mail($id_produccion){
		$query = $this->db->query("SELECT user.*, usuario_has_produccion.id_rol FROM user 
				LEFT OUTER JOIN usuario_has_produccion on usuario_has_produccion.id_usuario = user.id
				WHERE user.estado=1 and user.id_tipoUsuario = 1 OR user.id_tipoUsuario = 3 OR user.id_tipoUsuario = 4 
				OR user.id_tipoUsuario = 9 OR user.id_tipoUsuario = 10
				OR user.id_tipoUsuario = 8
				OR (usuario_has_produccion.id_rol = 15 AND usuario_has_produccion.activo = 1 AND usuario_has_produccion.id_produccion= ".$id_produccion.")
				OR (usuario_has_produccion.id_rol = 17 AND usuario_has_produccion.activo = 1 AND usuario_has_produccion.id_produccion= ".$id_produccion.")
				OR (usuario_has_produccion.id_rol = 18 AND usuario_has_produccion.activo = 1 AND usuario_has_produccion.id_produccion= ".$id_produccion.");");
		if ($query->num_rows>0) {
	      return $query->result();
	    }else{
	      return false;
	    }
		
	}


	public function get_usuarios_mail_two($id_produccion){
		$query = $this->db->query("SELECT user.*, usuario_has_produccion.id_rol FROM user 
				LEFT OUTER JOIN usuario_has_produccion on usuario_has_produccion.id_usuario = user.id
				WHERE user.correo = 'alexander.ospina@cincoveinticinco.com' ");
		if ($query->num_rows>0) {
	      return $query->result();
	    }else{
	      return false;
	    }
		
	}

	/*public function get_usuarios_mail_two($id_produccion){
		$query = $this->db->query("SELECT user.*, usuario_has_produccion.id_rol FROM user 
LEFT OUTER JOIN usuario_has_produccion on usuario_has_produccion.id_usuario = user.id
WHERE user.correo = 'alexander.ospina@cincoveinticinco.com' OR user.correo ='ddoncel@rtitv.com's");
		if ($query->num_rows>0) {
	      return $query->result();
	    }else{
	      return false;
	    }
		
	}*/


	public function get_personajes_expirados($id_produccion){
		$query = $this->db->query("SELECT elemento.nombre AS elemento, CONCAT(actores.nombre, ' ', actores.apellido) AS actor, rol_actores_elementos.rol AS rol, MAX(solicitudes.fecha_final) AS fecha_contrato, MAX(plan_diario.fecha_inicio) AS fecha_plan_diario
									FROM solicitudes
									INNER JOIN solicitud_has_elementos ON solicitud_has_elementos.id_solicitud = solicitudes.id
									LEFT OUTER JOIN elemento ON elemento.id = solicitud_has_elementos.id_elemento
									LEFT OUTER JOIN escenas_has_elementos ON escenas_has_elementos.id_elemento = elemento.id
									INNER JOIN escena ON escena.id =  escenas_has_elementos.id_escena
									INNER JOIN plan_diario_has_escenas_has_unidades ON plan_diario_has_escenas_has_unidades.id_escena = escena.id
									INNER JOIN plan_diario ON plan_diario.id = plan_diario_has_escenas_has_unidades.id_plan_diario 
									INNER JOIN categoria_elemento ON categoria_elemento.id = elemento.id_tipo_elemento 
									INNER JOIN actores ON actores.id = solicitudes.id_actor
									INNER JOIN rol_actores_elementos ON rol_actores_elementos.id = elemento.rol
									WHERE solicitudes.id_estado = 11 AND categoria_elemento.id_produccion = ".$id_produccion." ;");
		if ($query->num_rows>0){	
	      return $query->result();
	    }else{
	      return false;
	    }
	}

	public function get_personajes_sin_contrato($id_produccion){
		$query = $this->db->query("SELECT  elemento.id, elemento.nombre AS elemento, rol_actores_elementos.rol AS rol, MAX(plan_diario.fecha_inicio) AS fecha_plan_diario
			, (SELECT CONCAT(actores.nombre,' ', actores.apellido) FROM actores
				INNER JOIN solicitudes ON solicitudes.id_actor = actores.id
				INNER JOIN solicitud_has_elementos ON solicitud_has_elementos.id_elemento = solicitudes.id
				WHERE solicitud_has_elementos.id_elemento = elemento.id
				LIMIT 1) as actor
			FROM elemento
			INNER JOIN rol_actores_elementos ON rol_actores_elementos.id = elemento.rol
			INNER JOIN escenas_has_elementos ON escenas_has_elementos.id_elemento = elemento.id
			INNER JOIN escena ON escena.id =  escenas_has_elementos.id_escena
			INNER JOIN plan_diario_has_escenas_has_unidades ON plan_diario_has_escenas_has_unidades.id_escena = escena.id
			INNER JOIN plan_diario ON plan_diario.id = plan_diario_has_escenas_has_unidades.id_plan_diario 
			INNER JOIN categoria_elemento ON categoria_elemento.id = elemento.id_tipo_elemento 
			
			WHERE elemento.id NOT IN (SELECT solicitud_has_elementos.id_elemento 
				FROM solicitud_has_elementos 
				INNER JOIN solicitudes ON solicitudes.id = solicitud_has_elementos.id_solicitud
				WHERE solicitudes.id_estado != 16 AND solicitudes.id_estado != 12) 
			AND categoria_elemento.id_produccion = ".$id_produccion." ;");
		if ($query->num_rows>0){	
	      return $query->result();
	    }else{
	      return false;
	    }
	}

	public function get_solicitudes_rechazadas($id_produccion){
		$query = $this->db->query("SELECT solicitudes.id AS id_solicitud, solicitudes.tipo, solicitudes.id_solicitud_anexa, estados_solicitud.descripcion AS estado, elemento.nombre AS elemento, rol_actores_elementos.rol AS rol , CONCAT(user.nombre,' ',user.apellido) AS usuario, 
							aprobaciones_solicitud.fecha_aprobacion, aprobaciones_solicitud.notas, CONCAT(actores.nombre, ' ', actores.apellido) AS actor
							FROM solicitudes
							INNER JOIN solicitud_has_elementos ON solicitud_has_elementos.id_solicitud = solicitudes.id
							LEFT OUTER JOIN elemento ON elemento.id = solicitud_has_elementos.id_elemento 
							INNER JOIN rol_actores_elementos ON rol_actores_elementos.id = elemento.rol
							LEFT OUTER JOIN categoria_elemento ON categoria_elemento.id = elemento.id_tipo_elemento  
							INNER JOIN actores ON actores.id = solicitudes.id_actor
							INNER JOIN aprobaciones_solicitud ON aprobaciones_solicitud.id_solicitud = solicitudes.id AND 
							(aprobaciones_solicitud.id_estado = 7 OR aprobaciones_solicitud.id_estado = 8 OR aprobaciones_solicitud.id_estado = 9) 
							AND aprobaciones_solicitud.activo!=3
							INNER JOIN user ON user.id = aprobaciones_solicitud.id_usuario 
							INNER JOIN estados_solicitud ON estados_solicitud.id = solicitudes.id_estado
							WHERE categoria_elemento.id_produccion = ".$id_produccion.";");
		if ($query->num_rows>0){	
	      return $query->result();
	    }else{
	      return false;
	    }
	}

	public function get_solicitudes_anuladas($id_produccion){
		$query = $this->db->query("SELECT solicitudes.id AS id_solicitud, solicitudes.tipo, solicitudes.id_solicitud_anexa, estados_solicitud.descripcion AS estado, elemento.nombre AS elemento, rol_actores_elementos.rol AS rol , CONCAT(user.nombre,' ',user.apellido) AS usuario, 
							aprobaciones_solicitud.fecha_aprobacion, aprobaciones_solicitud.notas, CONCAT(actores.nombre, ' ', actores.apellido) AS actor
							FROM solicitudes
							INNER JOIN solicitud_has_elementos ON solicitud_has_elementos.id_solicitud = solicitudes.id
							LEFT OUTER JOIN elemento ON elemento.id = solicitud_has_elementos.id_elemento 
							INNER JOIN rol_actores_elementos ON rol_actores_elementos.id = elemento.rol
							LEFT OUTER JOIN categoria_elemento ON categoria_elemento.id = elemento.id_tipo_elemento  
							INNER JOIN actores ON actores.id = solicitudes.id_actor
							INNER JOIN aprobaciones_solicitud ON aprobaciones_solicitud.id_solicitud = solicitudes.id AND 
							aprobaciones_solicitud.id_estado = 16 AND aprobaciones_solicitud.activo!=3
							INNER JOIN user ON user.id = aprobaciones_solicitud.id_usuario 
							INNER JOIN estados_solicitud ON estados_solicitud.id = solicitudes.id_estado
							WHERE solicitudes.id_estado = 16 AND categoria_elemento.id_produccion = ".$id_produccion.";");
		if ($query->num_rows>0){	
	      return $query->result();
	    }else{
	      return false;
	    }
	}

	public function getProduccionByName($nombre){
		$this->db->where('nombre_produccion', trim($nombre));
		$query = $this->db->get('produccion');
		if ($query->num_rows>0){	
	      return $query->result();
	    }else{
	      return false;
	    }
	}

	public function insertManager($data){
		return $this->db->insert('managers', $data); 
	}

	public function selecttManager(){
		$query = $this->db->query("SELECT *, (SELECT GROUP_CONCAT( CONCAT(nombre,' ', apellido) SEPARATOR ',' )  
									FROM actores WHERE id_manager=mn.id) as actores FROM managers mn;");
		if ($query->num_rows>0) {
	      return $query->result_array();
	    }else{
	      return false;
	    }
	}
	public function delete_manager($id_manager){
		$this->db->where('id', $id_manager);
        $this->db->delete('managers');
	}
	public function selectActorManager($id_manager){
    	$this->db->where('id_manager', $id_manager);
		$query = $this->db->get('actores');
		if ($query->num_rows>0){	
	      return $query->result();
	    }else{
	      return false;
	    }
	}
	
	public function updateEstado($datos){
      $this->db->where('id', $datos['id']);
      return $this->db->update('managers',$datos);
    }

    public function selecttManagerAll($id){
    	$this->db->where('id', $id);
		$query = $this->db->get('managers');
		if ($query->num_rows>0){	
	      return $query->result();
	    }else{
	      return false;
	    }
	}

	public function selecttManagerEmail($email){
    	$this->db->where('email', $email);
		$query = $this->db->get('managers');
		if ($query->num_rows>0){	
	      return $query->result();
	    }else{
	      return false;
	    }
	}

   
   public function solicitudes_actor_asignacion($estado){
   		$query = $this->db->query("SELECT solicitudes_actores.*,actores.id as id_actor,actores.nombre,
				actores.apellido,manager3.nombre manager_actual,manager3.email corre_manager_actual,
				actores.id_manager id_manager_actual, 
				manager1.nombre as manager_solicitante,manager1.email email_solicitante,
				manager2.nombre as manager_anterior,manager2.email email_anterior,
				user.correo as user_aprobacion
				FROM solicitudes_actores
				inner join actores on actores.id=solicitudes_actores.id_actor
				inner join managers manager1 on manager1.id=solicitudes_actores.id_manager
				left join managers manager2 on manager2.id=solicitudes_actores.id_manager_anterior
				left join managers manager3 on manager3.id=actores.id_manager
				left join user on user.id=solicitudes_actores.id_user_aprobacion
				where solicitudes_actores.estado=".$estado);
		if ($query->num_rows>0){	
	      return $query->result_array();
	    }else{
	      return false;
	    }
	}


	public function del_idiomas_actor($id_actor){
		$this->db->where('id_actor', $id_actor);
        $this->db->delete('idiomas_actor');
		
	}
	
	

}

?>