<?php	 	 if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class model_capitulos extends CI_Model {

	public function capitulos_produccion($id){
	    $query=$this->db->query("SELECT produccion_has_capitulos.estado as estado_capitulo,produccion_has_capitulos.id AS id_capitulo, produccion_has_capitulos.*, estados_capitulo.*, 
        (SELECT COUNT(DISTINCT id_locacion) FROM escena WHERE id_capitulo = produccion_has_capitulos.id) AS cantidad_locaciones,
        (SELECT COUNT(DISTINCT id_set) FROM escena WHERE id_capitulo = produccion_has_capitulos.id) AS cantidad_sets,
        (SELECT COUNT(escena.id) FROM escena 
        WHERE escena.id_capitulo = produccion_has_capitulos.id AND escena.id_tipo_locacion = 2) AS escenas_estudio,
        (SELECT COUNT(escena.id) FROM escena 
        WHERE escena.id_capitulo = produccion_has_capitulos.id AND escena.id_toma_ubicacion = 1) AS escenas_toma,
        (SELECT SUM(escena.duracion_estimada_minutos) FROM escena 
        WHERE escena.id_capitulo = produccion_has_capitulos.id AND escena.id_tipo_locacion = 2) AS minutos_estudio,
        (SELECT SUM(escena.duracion_estimada_segundos) FROM escena 
        WHERE escena.id_capitulo = produccion_has_capitulos.id AND escena.id_tipo_locacion = 2) AS segundos_estudio,
        (SELECT SUM(escena.duracion_estimada_minutos) FROM escena 
        WHERE escena.id_capitulo = produccion_has_capitulos.id AND escena.id_flasback = 1) AS minutos_flash,
        (SELECT SUM(escena.duracion_estimada_segundos) FROM escena 
        WHERE escena.id_capitulo = produccion_has_capitulos.id AND escena.id_flasback = 1) AS segundos_flash,
        (SELECT COUNT(escena.id) FROM escena 
        WHERE escena.id_capitulo = produccion_has_capitulos.id AND escena.id_interior_esterior = 2) AS escenas_exterior,
        (SELECT COUNT(escena.id) FROM escena 
        WHERE escena.id_capitulo = produccion_has_capitulos.id AND escena.id_dia_noche = 2) AS escenas_noche,   
        (SELECT COUNT(escena.id) FROM escena 
        WHERE escena.id_capitulo = produccion_has_capitulos.id AND escena.id_tipo_locacion = 1) AS escenas_locacion
        FROM produccion_has_capitulos
	    	INNER JOIN 	estados_capitulo ON  estados_capitulo.id = produccion_has_capitulos.estado WHERE id_produccion =".$id."
	    	ORDER BY produccion_has_capitulos.numero ASC;");
	      return $query->result_array();
  }

  public function capitulos_produccion_2($id){
      $query=$this->db->query("SELECT produccion_has_capitulos.id AS id_capitulo, produccion_has_capitulos.*, estados_capitulo.*, 
        (SELECT COUNT(DISTINCT id_locacion) FROM escena WHERE id_capitulo = produccion_has_capitulos.id) AS cantidad_locaciones, 
        (SELECT COUNT(DISTINCT id_set) FROM escena WHERE id_capitulo = produccion_has_capitulos.id) AS cantidad_sets
        
        FROM produccion_has_capitulos
        INNER JOIN  estados_capitulo ON  estados_capitulo.id = produccion_has_capitulos.estado WHERE id_produccion =".$id." AND produccion_has_capitulos.numero_escenas != ''
        ORDER BY produccion_has_capitulos.numero ASC;");
        return $query->result_array();
  }

  public function capitulos_produccion_limit($id,$limite_inf,$limite_sup,$sql){
    $query=$this->db->query("SELECT produccion_has_capitulos.id AS id_capitulo, 
      (SELECT COUNT(escena.id) from plan_diario_has_escenas_has_unidades 
      inner join escena on plan_diario_has_escenas_has_unidades.id_escena = escena.id
      inner join plan_diario on plan_diario_has_escenas_has_unidades.id_plan_diario = plan_diario.id
      where escena.id_capitulo = produccion_has_capitulos.id and escena.estado =1) AS planes_producidos,
      (SELECT group_concat(DISTINCT(DATE_FORMAT(plan_diario.fecha_inicio, '%d-%b-%Y')), CONCAT(' / U',unidad.numero) separator ', ') from plan_diario_has_escenas_has_unidades 
      inner join escena on plan_diario_has_escenas_has_unidades.id_escena = escena.id
      inner join plan_diario on plan_diario_has_escenas_has_unidades.id_plan_diario = plan_diario.id
      inner join unidad on unidad.id = plan_diario.id_unidad
      where escena.id_capitulo = produccion_has_capitulos.id and escena.estado !=1) AS planes_asignados,
      produccion_has_capitulos.*, estados_capitulo.*, 
      (SELECT COUNT(DISTINCT id_locacion) FROM escena WHERE id_capitulo = produccion_has_capitulos.id) AS cantidad_locaciones, (SELECT COUNT(DISTINCT id_set) FROM escena WHERE id_capitulo = produccion_has_capitulos.id) AS cantidad_sets, 
      (SELECT sum(capitulos_has_escenas.tiempo_post_minutos) from escena 
      inner join capitulos_has_escenas on capitulos_has_escenas.id_escena=escena.id
      where escena.id_capitulo=produccion_has_capitulos.id) tiempo_post_minutos,
      (SELECT sum(capitulos_has_escenas.tiempo_post_segundos) from escena 
      inner join capitulos_has_escenas on capitulos_has_escenas.id_escena=escena.id
      where escena.id_capitulo=produccion_has_capitulos.id) tiempo_post_segundos,
      (SELECT sum(capitulos_has_escenas.tiempo_post_cuadros) from escena 
      inner join capitulos_has_escenas on capitulos_has_escenas.id_escena=escena.id
      where escena.id_capitulo=produccion_has_capitulos.id) tiempo_post_cuadros,
      (SELECT COUNT(DISTINCT(capitulos_has_escenas.id_escena)) total from escena 
      inner join capitulos_has_escenas on capitulos_has_escenas.id_escena=escena.id
      where escena.id_capitulo=produccion_has_capitulos.id
      and (capitulos_has_escenas.tiempo_post_minutos+capitulos_has_escenas.tiempo_post_segundos)<>0) as post_produccidas  
      FROM produccion_has_capitulos
        INNER JOIN  estados_capitulo ON  estados_capitulo.id = produccion_has_capitulos.estado WHERE id_produccion =".$id."
        ".$sql." ORDER BY produccion_has_capitulos.numero ASC,produccion_has_capitulos.estado DESC limit ".$limite_inf." ,".$limite_sup.";");
        return $query->result_array();
  }

  public function capitulos_produccion_limit2($id,$sql){
    $query=$this->db->query("SELECT * FROM produccion_has_capitulos WHERE id_produccion=".$id);
        return $query->result_array();
  }


  public function capitulos_produccion_lib($id,$sql){
    $query=$this->db->query("SELECT produccion_has_capitulos.id AS id_capitulo, 
      (SELECT COUNT(escena.id) from plan_diario_has_escenas_has_unidades 
      inner join escena on plan_diario_has_escenas_has_unidades.id_escena = escena.id
      inner join plan_diario on plan_diario_has_escenas_has_unidades.id_plan_diario = plan_diario.id
      where escena.id_capitulo = produccion_has_capitulos.id and escena.estado =1) AS planes_producidos,
      (SELECT group_concat(DISTINCT(DATE_FORMAT(plan_diario.fecha_inicio, '%d-%b-%Y')), CONCAT(' / U',unidad.numero) separator ', ') from plan_diario_has_escenas_has_unidades 
      inner join escena on plan_diario_has_escenas_has_unidades.id_escena = escena.id
      inner join plan_diario on plan_diario_has_escenas_has_unidades.id_plan_diario = plan_diario.id
      inner join unidad on unidad.id = plan_diario.id_unidad
      where escena.id_capitulo = produccion_has_capitulos.id and escena.estado !=1) AS planes_asignados,
      produccion_has_capitulos.*, estados_capitulo.*, (SELECT COUNT(DISTINCT id_locacion) FROM escena WHERE id_capitulo = produccion_has_capitulos.id) AS cantidad_locaciones, (SELECT COUNT(DISTINCT id_set) FROM escena WHERE id_capitulo = produccion_has_capitulos.id) AS cantidad_sets, 
      (SELECT sum(capitulos_has_escenas.tiempo_post_minutos) from escena 
      inner join capitulos_has_escenas on capitulos_has_escenas.id_escena=escena.id
      where escena.id_capitulo=produccion_has_capitulos.id) tiempo_post_minutos,
      (SELECT sum(capitulos_has_escenas.tiempo_post_segundos) from escena 
      inner join capitulos_has_escenas on capitulos_has_escenas.id_escena=escena.id
      where escena.id_capitulo=produccion_has_capitulos.id) tiempo_post_segundos,
      (SELECT sum(capitulos_has_escenas.tiempo_post_cuadros) from escena 
      inner join capitulos_has_escenas on capitulos_has_escenas.id_escena=escena.id
      where escena.id_capitulo=produccion_has_capitulos.id) tiempo_post_cuadros,
      (SELECT COUNT(DISTINCT(capitulos_has_escenas.id_escena)) total from escena 
      inner join capitulos_has_escenas on capitulos_has_escenas.id_escena=escena.id
      where escena.id_capitulo=produccion_has_capitulos.id
      and (capitulos_has_escenas.tiempo_post_minutos+capitulos_has_escenas.tiempo_post_segundos)<>0) as post_produccidas 
      FROM produccion_has_capitulos
        INNER JOIN  estados_capitulo ON  estados_capitulo.id = produccion_has_capitulos.estado WHERE id_produccion =".$id."
        ".$sql." ORDER BY produccion_has_capitulos.numero ASC,produccion_has_capitulos.estado DESC");
        return $query->result_array();
  }

  public function capitulos_produccion_total($id,$sql){
    $query=$this->db->query("SELECT produccion_has_capitulos.id AS id_capitulo, 
      (SELECT COUNT(escena.id) from plan_diario_has_escenas_has_unidades 
      inner join escena on plan_diario_has_escenas_has_unidades.id_escena = escena.id
      inner join plan_diario on plan_diario_has_escenas_has_unidades.id_plan_diario = plan_diario.id
      where escena.id_capitulo = produccion_has_capitulos.id and escena.estado =1) AS planes_producidos,
      (SELECT group_concat(DISTINCT(DATE_FORMAT(plan_diario.fecha_inicio, '%d-%b-%Y')), CONCAT(' / U',unidad.numero) separator ', ') from plan_diario_has_escenas_has_unidades 
      inner join escena on plan_diario_has_escenas_has_unidades.id_escena = escena.id
      inner join plan_diario on plan_diario_has_escenas_has_unidades.id_plan_diario = plan_diario.id
      inner join unidad on unidad.id = plan_diario.id_unidad
      where escena.id_capitulo = produccion_has_capitulos.id and escena.estado !=1) AS planes_asignados,
      produccion_has_capitulos.*, estados_capitulo.*, (SELECT COUNT(DISTINCT id_locacion) FROM escena WHERE id_capitulo = produccion_has_capitulos.id) AS cantidad_locaciones, (SELECT COUNT(DISTINCT id_set) FROM escena WHERE id_capitulo = produccion_has_capitulos.id) AS cantidad_sets, 
      (SELECT sum(capitulos_has_escenas.tiempo_post_minutos) from escena 
      inner join capitulos_has_escenas on capitulos_has_escenas.id_escena=escena.id
      where escena.id_capitulo=produccion_has_capitulos.id) tiempo_post_minutos,
      (SELECT sum(capitulos_has_escenas.tiempo_post_segundos) from escena 
      inner join capitulos_has_escenas on capitulos_has_escenas.id_escena=escena.id
      where escena.id_capitulo=produccion_has_capitulos.id) tiempo_post_segundos,
      (SELECT sum(capitulos_has_escenas.tiempo_post_cuadros) from escena 
      inner join capitulos_has_escenas on capitulos_has_escenas.id_escena=escena.id
      where escena.id_capitulo=produccion_has_capitulos.id) tiempo_post_cuadros,
      (SELECT COUNT(DISTINCT(capitulos_has_escenas.id_escena)) total from escena 
      inner join capitulos_has_escenas on capitulos_has_escenas.id_escena=escena.id
      where escena.id_capitulo=produccion_has_capitulos.id
      and (capitulos_has_escenas.tiempo_post_minutos+capitulos_has_escenas.tiempo_post_segundos)<>0) as post_produccidas, 
      (SELECT COUNT(DISTINCT(escena.id)) total from escena 
      where escena.id_capitulo=produccion_has_capitulos.id and escena.id_tipo_locacion=2) as escenas_estudio 
      FROM produccion_has_capitulos
        INNER JOIN  estados_capitulo ON  estados_capitulo.id = produccion_has_capitulos.estado WHERE id_produccion =".$id."
        ".$sql." ORDER BY produccion_has_capitulos.numero ASC,produccion_has_capitulos.estado DESC");
        return $query->result_array();
  }

  public function personajes_capitulos($id_capitulo){
    $query = $this->db->query("SELECT GROUP_CONCAT(cnt) cnt
        FROM
      (
        SELECT CONCAT(COUNT(DISTINCT(id_elemento)),'-',elemento.rol) AS cnt FROM elemento
        INNER JOIN escenas_has_elementos ON id_elemento = elemento.id
        INNER JOIN escena ON escena.id = escenas_has_elementos.id_escena
        WHERE escena.id_capitulo = ".$id_capitulo." AND elemento.rol IS NOT NULL
          GROUP BY elemento.rol
      ) q;");
      if ($query->num_rows>0) {
        return $query->result_array();
      }else{
        return false;
      }
  }

  public function personajes_capitulos2($id_capitulo,$valor){
    $query = $this->db->query("SELECT GROUP_CONCAT(cnt) cnt
        FROM
      (
        SELECT CONCAT(COUNT(DISTINCT(id_elemento)),'-',elemento.rol) AS cnt FROM elemento
        INNER JOIN escenas_has_elementos ON id_elemento = elemento.id
        INNER JOIN escena ON escena.id = escenas_has_elementos.id_escena
        WHERE escena.id_capitulo = ".$id_capitulo." 
        AND elemento.id_tipo_contrato!=5  AND elemento.monto>=".$valor." 
        AND elemento.rol IS NOT NULL
          GROUP BY elemento.rol
      ) q;");
      if ($query->num_rows>0) {
        return $query->result_array();
      }else{
        return false;
      }
  }

  public function protagonistas_capitulos($id_produccion){
    $query = $this->db->query("SELECT count(DISTINCT(id_elemento)) as total FROM produccion_has_capitulos
          inner join escena on escena.id_capitulo=produccion_has_capitulos.id
          inner join escenas_has_elementos on escenas_has_elementos.id_escena=escena.id
          inner join elemento on elemento.id=escenas_has_elementos.id_elemento
          where produccion_has_capitulos.id_produccion=".$id_produccion." and elemento.id_tipo_contrato=5 AND elemento.rol IS NOT NULL;");
      if ($query->num_rows>0) {
        return $query->result();
      }else{
        return false;
      }
  }

  public function protagonistas_capitulos_id_capitulo($id_produccion,$id_capitulo){
    $query = $this->db->query("SELECT count(DISTINCT(id_elemento)) as total FROM produccion_has_capitulos
          inner join escena on escena.id_capitulo=produccion_has_capitulos.id
          inner join escenas_has_elementos on escenas_has_elementos.id_escena=escena.id
          inner join elemento on elemento.id=escenas_has_elementos.id_elemento
          where produccion_has_capitulos.id_produccion=".$id_produccion." 
          and escena.id_capitulo = ".$id_capitulo."
          and elemento.id_tipo_contrato=5 AND elemento.rol IS NOT NULL;");
      if ($query->num_rows>0) {
        return $query->result();
      }else{
        return false;
      }
  }

  /*public function protagonistas_capitulos($id_capitulo){
    $query = $this->db->query("SELECT count(DISTINCT(id_elemento)) as total from elemento
      INNER JOIN escenas_has_elementos ON escenas_has_elementos.id_elemento = elemento.id 
      INNER JOIN escena ON escena.id = escenas_has_elementos.id_escena 
      WHERE escena.id_capitulo = ".$id_capitulo." AND elemento.id_tipo_contrato=5;");
      if ($query->num_rows>0) {
        return $query->result();
      }else{
        return false;
      }
  }*/

  public function figurante_capitulos($id_capitulo,$monto,$monto_dolar){
    $query = $this->db->query("SELECT count(DISTINCT(id_elemento)) as total from elemento
      INNER JOIN escenas_has_elementos ON escenas_has_elementos.id_elemento = elemento.id 
      INNER JOIN escena ON escena.id = escenas_has_elementos.id_escena 
      WHERE escena.id_capitulo = ".$id_capitulo." 
       AND (elemento.id_tipo_contrato!=5 or elemento.id_tipo_contrato is null) 
       AND (
        IF (elemento.id_tipo_moneda=1,elemento.monto<".$monto.",elemento.monto<".$monto_dolar.") 
        or elemento.monto is null) 
       AND elemento.rol IS NOT NULL AND elemento.rol!=4;");
      if ($query->num_rows>0) {
        return $query->result();
      }else{
        return false;
      }
  }

  public function reparto_capitulos($id_capitulo,$monto,$monto_dolar){
    $query = $this->db->query("SELECT count(DISTINCT(id_elemento)) as total from elemento
      INNER JOIN escenas_has_elementos ON escenas_has_elementos.id_elemento = elemento.id 
      INNER JOIN escena ON escena.id = escenas_has_elementos.id_escena 
      WHERE escena.id_capitulo = ".$id_capitulo." 
       AND (elemento.id_tipo_contrato!=5 or elemento.id_tipo_contrato is null)
       AND 
       IF (elemento.id_tipo_moneda=1,elemento.monto>=".$monto.",elemento.monto>=".$monto_dolar.")
       AND elemento.rol IS NOT NULL AND elemento.rol!=4;");
      if ($query->num_rows>0) {
        return $query->result();
      }else{
        return false;
      }
  }

  

  

  public function extras_capitulos($id_capitulo){
    $query = $this->db->query(" SELECT max(extras_escena.cantidad) as cantidad
        FROM extras_escena
        INNER JOIN escenas_has_elementos ON escenas_has_elementos.id = extras_escena.id_escenas_has_elementos
        INNER JOIN escena ON escena.id = escenas_has_elementos.id_escena
        inner join elemento on elemento.id=escenas_has_elementos.id_elemento
        WHERE escena.id_capitulo = ".$id_capitulo."  and elemento.rol=4
        GROUP BY escenas_has_elementos.id_elemento;");
      if ($query->num_rows>0) {
        return $query->result_array();
      }else{
        return false;
      }
  }

  public function vehiculos_capitulo($id_capitulo){
    $query = $this->db->query("SELECT SUM(escena.vehiculo_background) as vehiculo_background, (
      SELECT count(distinct(elemento.id)) FROM elemento 
      INNER JOIN escenas_has_elementos ON elemento.id = escenas_has_elementos.id_elemento
      INNER JOIN categoria_elemento ON elemento.id_tipo_elemento = categoria_elemento.id
      INNER JOIN escena ON  escenas_has_elementos.id_escena = escena.id
      WHERE categoria_elemento.tipo = 'Vehiculo' 
      AND escena.id_capitulo = ".$id_capitulo.") AS vehiculos_desglosados,
      (
      SELECT count(distinct(elemento.id)) FROM elemento 
      INNER JOIN escenas_has_elementos ON elemento.id = escenas_has_elementos.id_elemento
      INNER JOIN categoria_elemento ON elemento.id_tipo_elemento = categoria_elemento.id
      INNER JOIN escena ON  escenas_has_elementos.id_escena = escena.id
      WHERE categoria_elemento.tipo = 'Vehiculos background' 
      AND escena.id_capitulo =  ".$id_capitulo.") AS background_v
      FROM escena WHERE
      escena.id_capitulo = ".$id_capitulo.";");
    if ($query->num_rows>0) {
        return $query->result_array();
      }else{
        return false;
      }
  }

 public function agregar_capitulo($id){
    $query=$this->db->query("SELECT * FROM produccion_has_capitulos WHERE id_produccion =".$id.";");
      if ($query->num_rows>0) {
        return $query->result_array();
      }else{
        return false;
      }
	}

  public function capitulos_limit($id,$limit,$limit2){
    $query=$this->db->query("SELECT produccion_has_capitulos.id,produccion_has_capitulos.numero FROM produccion_has_capitulos WHERE id_produccion =".$id." ORDER BY NUMERO limit ".$limit." ,".$limit2." ");
      if ($query->num_rows>0) {
        return $query->result_array();
      }else{
        return false;
      }
  }

  public function capitulos_limite($id,$limit,$limit2,$limite_inferior,$limite_superior){
    $query=$this->db->query("SELECT produccion_has_capitulos.id,produccion_has_capitulos.numero FROM produccion_has_capitulos WHERE id_produccion =".$id." AND numero >= ". $limite_inferior ." AND numero <= ". $limite_superior ." ORDER BY NUMERO limit ".$limit." ,".$limit2.";");
    if ($query->num_rows>0) {
      return $query->result_array();
    }else{
      return false;
    }
  }

  	public function insertar_capitulo($data){
    	return $this->db->insert('produccion_has_capitulos',$data);
  	}

    public function insertar_capitulo_post($data){
      return $this->db->insert('capitulos',$data);
    }

    public function insertar_capitulo_user($data){
      return $this->db->insert('usuario_has_capitulo',$data);
    }

    public function buscar_id_numero($id,$numero){
        $this->db->where('numero',$numero);
        $this->db->where('id',$idcapitulo);
        $this->db->update('produccion_has_capitulos',$data);
    }

  	public function actualizar_capitulo($idproduccion,$idcapitulo,$opcion,$valor){
  		$data=array(
            ''.$opcion.''=>$valor
        );
        $this->db->where('id_produccion',$idproduccion);
        $this->db->where('id',$idcapitulo);
        $this->db->update('produccion_has_capitulos',$data);
  	}

    public function actualizar_capitulo_2($idproduccion,$idcapitulo,$opcion,$valor){
      $data=array(
            ''.$opcion.''=>$valor
        );
        $this->db->where('id_produccion',$idproduccion);
        $this->db->where('numero',$idcapitulo);
        $this->db->where('estado !=',6);
        $this->db->update('produccion_has_capitulos',$data);
    }

  	public function buscar_capitulo($idproduccion,$idcapitulo){
  		$query=$this->db->query("SELECT * from produccion_has_capitulos WHERE id_produccion =".$idproduccion." AND numero=.".$idcapitulo.";");
	    if ($query->num_rows>0) {
	       return true;
	    }else{
	       return false;
	    }
  	}

    public function bucar_capitulo_id($id){
      $query=$this->db->query("SELECT produccion_has_capitulos.*, estados_capitulo.descripcion AS estado_capitulo from produccion_has_capitulos INNER JOIN estados_capitulo ON produccion_has_capitulos.estado = estados_capitulo.id  WHERE produccion_has_capitulos.id = ".$id.";");
      if ($query->num_rows>0) {
        return $query->result_array();
      }else{
         return false;
      }
    }

    public function capitulo_id($numero,$id_produccion){
      $query=$this->db->query("SELECT * from produccion_has_capitulos WHERE numero =".$numero." AND id_produccion=".$id_produccion." AND estado != 6;");
      if ($query->num_rows>0) {
        return $query->result_array();
      }else{
         return false;
      }
    }

    public function buscar_capitulo_por_numero($numero,$id_produccion){
      $query=$this->db->query("SELECT * from produccion_has_capitulos WHERE numero =".$numero." AND id_produccion=".$id_produccion.";");
      if ($query->num_rows>0) {
        return $query->result_array();
      }else{
         return false;
      }
    }


    public function buscar_capitulo_id($id){
      $query=$this->db->query("SELECT * from produccion_has_capitulos WHERE id =".$id." AND estado != 6;");
      if ($query->num_rows>0) {
        return $query->result_array();
      }else{
         return false;
      }
    }

    public function contar_capitulos($numero, $id_produccion){
      $query=$this->db->query("SELECT COUNT(id) AS cantidad from produccion_has_capitulos WHERE numero >".$numero." AND id_produccion = ".$id_produccion.";");
      if ($query->num_rows>0) {
        return $query->result_array();
      }else{
         return false;
      }
    }

  	public function cancelar_capitulo($idproduccion,$idcapitulo){
  		$data=array(
            'estado'=>6,
            'fecha_aire'=>'0000-00-00'
        );
        $this->db->where('id_produccion',$idproduccion);
        $this->db->where('id',$idcapitulo);
        $this->db->update('produccion_has_capitulos',$data);
  	}

  	public function eliminar_capitulo($idproduccion,$idcapitulo){
        $this->db->where('id_produccion',$idproduccion);
        $this->db->where('id',$idcapitulo);
        $this->db->delete('produccion_has_capitulos');
  	}

    public function contar_dias_aire($idproduccion){
      $query=$this->db->query("SELECT lunes, martes,miercoles,jueves,viernes,sabado,domingo 
                               FROM dias_grabacion WHERE id =".$idproduccion.";");
      if ($query->num_rows>0) {
         return $query->result_array();
      }else{
         return false;
      }
    }

    public function cantidad_capitulos($idproduccion){
      $query=$this->db->query("SELECT COUNT(id) AS cantidad from produccion_has_capitulos WHERE id_produccion = ".$idproduccion.";");
      if ($query->num_rows>0) {
        return $query->result();
      }else{
         return false;
      }
    }

    public function actualizar_cantidad_capitulos($idproduccion, $cantidad){
      $data=array(
            'numero_capitulo'=>$cantidad
        );
        $this->db->where('id',$idproduccion);
        $this->db->update('produccion',$data);
    }

    public function contar_capitulos_escritos($idproduccion){
      $this->db->where('id_produccion', $idproduccion);
      $this->db->where('estado !=', 1);
      $this->db->where('estado !=', 6);
      $query = $this->db->get('produccion_has_capitulos');
      return $query->result();
    }

    public function capitulo_estado($idproduccion,$idcapitulo, $estado){
      if($estado==4){
        $data=array(
          'estado'=>$estado,
          'fecha_desglosado' => date("Y-m-d")
        );
      }else{
        $data=array(
          'estado'=>$estado,
          'fecha_entregado' => date("Y-m-d")
        );
      }
      $this->db->where('id_produccion',$idproduccion);
      $this->db->where('id',$idcapitulo);
      $this->db->update('produccion_has_capitulos',$data);
    }

    public function actualizar_escenas_escritas($idcapitulo){
      $query=$this->db->query("UPDATE produccion_has_capitulos SET escenas_escritas = (
      SELECT count(id) FROM escena WHERE id_capitulo =".$idcapitulo.") WHERE id =".$idcapitulo.";");
      /*if ($query->num_rows>0) {
        return $query->result();
      }else{
         return false;
      }*/
    }

    public function filtro_capitulos($from,$to,$id,$sql){
      $query=$this->db->query("SELECT produccion_has_capitulos.id AS id_capitulo,
         (SELECT COUNT(escena.id) from plan_diario_has_escenas_has_unidades 
      inner join escena on plan_diario_has_escenas_has_unidades.id_escena = escena.id
      inner join plan_diario on plan_diario_has_escenas_has_unidades.id_plan_diario = plan_diario.id
      where escena.id_capitulo = produccion_has_capitulos.id and escena.estado =1) AS planes_producidos,
      (SELECT group_concat(DISTINCT(DATE_FORMAT(plan_diario.fecha_inicio, '%d-%b-%Y')), CONCAT(' / U',unidad.numero) separator ', ') from plan_diario_has_escenas_has_unidades 
      inner join escena on plan_diario_has_escenas_has_unidades.id_escena = escena.id
      inner join plan_diario on plan_diario_has_escenas_has_unidades.id_plan_diario = plan_diario.id
      inner join unidad on unidad.id = plan_diario.id_unidad
      where escena.id_capitulo = produccion_has_capitulos.id and escena.estado !=1) AS planes_asignados,
       produccion_has_capitulos.*, estados_capitulo.*, (SELECT COUNT(DISTINCT id_locacion) FROM escena WHERE id_capitulo = produccion_has_capitulos.id) AS cantidad_locaciones, (SELECT COUNT(DISTINCT id_set) FROM escena WHERE id_capitulo = produccion_has_capitulos.id) AS cantidad_sets FROM produccion_has_capitulos
        INNER JOIN  estados_capitulo ON  estados_capitulo.id = produccion_has_capitulos.estado WHERE id_produccion =".$id."
        AND produccion_has_capitulos.numero BETWEEN ".$from." AND ".$to." ".$sql."
        ORDER BY produccion_has_capitulos.numero ASC;");
      if ($query->num_rows>0) {
        return $query->result_array();
      }else{
         return false;
      }
    }

    public function actualizar_capitulo_estado($idcapitulo,$data){
      $this->db->where('id',$idcapitulo);
      return $this->db->update('produccion_has_capitulos',$data);
    }

    public function primer_capitulo($idproduccion){
      $query=$this->db->query("SELECT * FROM produccion_has_capitulos WHERE 
      numero = (SELECT MIN(numero) FROM produccion_has_capitulos);");
      if ($query->num_rows>0) {
        return $query->result_array();
      }else{
         return false;
      }
    }

    public function buscar_capitulos_escritos($from, $to,$idproduccion){
      $query=$this->db->query("SELECT * FROM produccion_has_capitulos WHERE id_produccion = ".$idproduccion." AND estado != 1 AND    
      numero BETWEEN ".$from." AND ".$to. " ;");
      if ($query->num_rows>0) {
        return $query->result_array();
      }else{
         return false;
      }
    }

    public function eliminar_rango_capitulos($from, $to,$idproduccion){
      $this->db->query("DELETE FROM produccion_has_capitulos WHERE id_produccion = ".$idproduccion." AND numero BETWEEN ".$from." AND ".$to. " ;");
    }

    public function list_estados(){
      $query = $this->db->get('estados_capitulo');
      return $query->result();
    }

    public function ultimo_capitulo($idproduccion){
      $query=$this->db->query("SELECT id_capitulo FROM escena
                        INNER JOIN produccion_has_capitulos pc ON pc.id = escena.id_capitulo
                        WHERE pc.id_produccion = ".$idproduccion." ORDER BY escena.id DESC LIMIT 1;");
      if ($query->num_rows>0) {
        return $query->result();
      }else{
         return false;
      }
    }

    public function nuevas_locaciones($idcapitulo,$idproduccion){
      $query=$this->db->query("SELECT 
                              COUNT(DISTINCT(escena.id_locacion)) as locaciones_nuevas
                              FROM escena
                              WHERE escena.id_locacion NOT IN (SELECT escena2.id_locacion FROM escena escena2
                              INNER JOIN produccion_has_capitulos ON produccion_has_capitulos.id = escena2.id_capitulo
                              WHERE produccion_has_capitulos.numero < (SELECT produccion_has_capitulos.numero FROM produccion_has_capitulos WHERE id = ".$idcapitulo.") 
                              AND produccion_has_capitulos.id_produccion = ".$idproduccion." )
                              AND escena.id_capitulo = ".$idcapitulo.";");
      if ($query->num_rows>0) {
        return $query->result_array();
      }else{
         return false;
      }
    }

    public function eventos_capitulo($idcapitulo){
      $query=$this->db->query("SELECT GROUP_CONCAT(cnt) cnt
                              FROM
                            (
                              SELECT CONCAT(COUNT(DISTINCT(escena.id)),'-',escena.id_magnitud) AS cnt FROM escena
                              WHERE escena.id_capitulo = ".$idcapitulo."
                                GROUP BY escena.id_magnitud
                            ) q;");
      if ($query->num_rows>0) {
        return $query->result_array();
      }else{
         return false;
      }
    }
    


    public function estados_libretos_user($id_usuario){
      $query=$this->db->query("SELECT * from columnas_usuario
        where id_usuario=".$id_usuario." and tipo=4");
      if ($query->num_rows>0) {
        return $query->result();
      }else{
         return false;
      }
    }
    
    public function estados_libretos_update($id,$estados){
      $this->db->where('id',$id);
      $data=array('campos'=>$estados);
      return $this->db->update('columnas_usuario',$data);
    }

    public function estados_libretos_insert($id_usuario,$estados){
      $data=array('id_usuario'=>$id_usuario,
                  'campos'=>$estados,
                  'tipo'=>4);
      return $this->db->insert('columnas_usuario',$data);
    }
    

    public function libretos_produccion_a($id_produccion){
      $query=$this->db->query("SELECT produccion_has_capitulos.*
          FROM produccion_has_capitulos
          where produccion_has_capitulos.id_produccion=".$id_produccion." and produccion_has_capitulos.estado!=6;
          ");
      if ($query->num_rows>0) {
        return $query->result_array();
      }else{
         return false;
      }
    }


    

}