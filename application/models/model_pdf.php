<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class model_pdf extends CI_Model {
 
   /*** cambia estado de los users*/  
  public function numero_escenas_canceladas($id_capitulo){
      $this->db->where('id_capitulo',$id_capitulo);
      $this->db->where('estado',3);
      $query=$this->db->get('escena');
     return $query->num_rows;
  }
  
   public function numero_escenas_producidas($id_capitulo){
      $this->db->where('id_capitulo',$id_capitulo);
      $this->db->where('estado',1);
      $query=$this->db->get('escena');
     return $query->num_rows;
  }

  public function tiempo_escenas_pots_producidas($id_capitulo){
     $query=$this->db->query("SELECT sum(capitulos_has_escenas.tiempo_post_minutos) as minutos, sum(capitulos_has_escenas.tiempo_post_segundos) as segundos, 
                sum(capitulos_has_escenas.tiempo_post_cuadros) as cuadros, count(capitulos_has_escenas.id_escena) as total_escenas FROM escena 
                inner join capitulos_has_escenas on capitulos_has_escenas.id_escena=escena.id
                where escena.id_capitulo=".$id_capitulo);
      if ($query->num_rows>0) {
        return $query->result();
      }else {
        return false;
      }
  }

    public function min_por_producir($id_capitulo){
     $query=$this->db->query("SELECT sum(duracion_estimada_minutos) as duracion_estimada_minutos, 
        sum(duracion_estimada_segundos) as duracion_estimada_segundos FROM escena 
        WHERE id_capitulo = ".$id_capitulo." and estado!=1 and estado!=3;");
      if ($query->num_rows>0) {
        return $query->result();
      }else {
        return false;
      }
  }

  public function elementos_dif_personajes_group_tipo($id_escena){
    $query=$this->db->query('SELECT * FROM escenas_has_elementos h, elemento e
      INNER JOIN categoria_elemento ON categoria_elemento.id=e.id_tipo_elemento
      and categoria_elemento.tipo!="Personaje" WHERE h.id_escena='.$id_escena.' and e.id=h.id_elemento ORDER BY e.id_tipo_elemento');
    if ($query->num_rows>0) {
      return $query->result_array();
    }else{
      return false;
    }
  }

  public function personajes_pdf($id_escena){
    $query=$this->db->query('SELECT * FROM escenas_has_elementos h, elemento e
      INNER JOIN categoria_elemento ON categoria_elemento.id=e.id_tipo_elemento
      and categoria_elemento.tipo = "Personaje" WHERE h.id_escena='.$id_escena.' and e.id=h.id_elemento ORDER BY e.id_tipo_elemento');
    if ($query->num_rows>0) {
      return $query->result_array();
    }else{
      return false;
    }
  }

  public function personajes_semanal($idproduccion,$fecha, $sql2){
    $query= $this->db->query('SELECT DISTINCT(unidad.id),unidad.numero, elemento.nombre, rol_actores_elementos.rol,
                        (SELECT group_concat(libreto.numero,"/",escena.numero_escena separator ", ")
                        FROM escena 
                        INNER JOIN produccion_has_capitulos libreto ON libreto.id = escena.id_capitulo 
                        INNER JOIN escenas_has_elementos ON escenas_has_elementos.id_escena = escena.id
                        INNER JOIN plan_diario_has_escenas_has_unidades ph ON ph.id_escena = escena.id 
                        INNER JOIN plan_diario ON plan_diario.id = ph.id_plan_diario 
                        WHERE escenas_has_elementos.id_elemento = elemento.id AND plan_diario.fecha_inicio = "'.$fecha.'") AS escenas,
                        (SELECT SUM(extras_escena.cantidad) FROM extras_escena
                        INNER JOIN escenas_has_elementos ON escenas_has_elementos.id = extras_escena.id_escenas_has_elementos 
                        INNER JOIN plan_diario_has_escenas_has_unidades ph ON ph.id_escena = escenas_has_elementos.id_escena 
                        INNER JOIN plan_diario ON plan_diario.id = ph.id_plan_diario 
                        WHERE escenas_has_elementos.id_elemento = elemento.id 
                        AND plan_diario.fecha_inicio = "'.$fecha.'" and plan_diario.id_unidad = unidad.id) AS cantidad_extras,
                        (CASE elemento.rol WHEN 1 THEN "A" WHEN 2 THEN "C" WHEN 3 THEN "B" WHEN 4 THEN "D" END) AS valida 
                        FROM elemento
                        INNER JOIN rol_actores_elementos ON rol_actores_elementos.id = elemento.rol
                        INNER JOIN escenas_has_elementos ON escenas_has_elementos.id_elemento = elemento.id
                        INNER JOIN plan_diario_has_escenas_has_unidades ph ON ph.id_escena = escenas_has_elementos.id_escena
                        INNER JOIN plan_diario ON plan_diario.id = ph.id_plan_diario 
                        INNER JOIN unidad ON unidad.id = plan_diario.id_unidad
                        WHERE plan_diario.fecha_inicio = "'.$fecha.'" '.$sql2.'  AND unidad.id_produccion = '.$idproduccion.' ORDER BY unidad.numero,valida,elemento.nombre;');
    if ($query->num_rows>0) {
      return $query->result();
    }else{
      return false;
    }
  }

  public function elementos_semanal($idproduccion,$fecha,$sql=""){
    $query= $this->db->query('SELECT DISTINCT(elemento.id) as id_elemento, unidad.id,unidad.numero, elemento.nombre,
                              (SELECT group_concat(libreto.numero,"/",escena.numero_escena separator ", ") FROM escena 
                              INNER JOIN produccion_has_capitulos libreto ON libreto.id = escena.id_capitulo 
                              INNER JOIN escenas_has_elementos ON escenas_has_elementos.id_escena = escena.id 
                              INNER JOIN plan_diario_has_escenas_has_unidades ph ON ph.id_escena = escena.id 
                              INNER JOIN plan_diario ON plan_diario.id = ph.id_plan_diario 
                              WHERE escenas_has_elementos.id_elemento = elemento.id 
                              AND plan_diario.fecha_inicio = "'.$fecha.'") AS escenas 
                              FROM elemento 
                              INNER JOIN escenas_has_elementos ON escenas_has_elementos.id_elemento = elemento.id 
                              INNER JOIN plan_diario_has_escenas_has_unidades ph ON ph.id_escena = escenas_has_elementos.id_escena 
                              INNER JOIN plan_diario ON plan_diario.id = ph.id_plan_diario 
                              INNER JOIN unidad ON unidad.id = plan_diario.id_unidad 
                              WHERE plan_diario.fecha_inicio = "'.$fecha.'" 
                              AND unidad.id_produccion = '.$idproduccion.' '.$sql.' AND rol IS NULL
                              ORDER BY unidad.numero,elemento.nombre;');
    if ($query->num_rows>0) {
      return $query->result();
    }else{
      return false;
    }
  }

  public function filas_pdf($idproduccion,$fecha, $idunidad){
    $query= $this->db->query('SELECT COUNT(DISTINCT(elemento.id)) AS filas
                              FROM elemento 
                              INNER JOIN rol_actores_elementos ON rol_actores_elementos.id = elemento.rol 
                              INNER JOIN escenas_has_elementos ON escenas_has_elementos.id_elemento = elemento.id 
                              INNER JOIN plan_diario_has_escenas_has_unidades ph ON ph.id_escena = escenas_has_elementos.id_escena 
                              INNER JOIN plan_diario ON plan_diario.id = ph.id_plan_diario 
                              INNER JOIN unidad ON unidad.id = plan_diario.id_unidad 
                              WHERE plan_diario.fecha_inicio = "'.$fecha.'"
                              AND unidad.id_produccion = '.$idproduccion.'
                              AND unidad.id = '.$idunidad.'
                              ORDER BY unidad.numero;');
    if ($query->num_rows>0) {
      return $query->result();
    }else{
      return false;
    }
  }

  public function filas_pdf_elementos($idproduccion,$fecha, $idunidad){
    $query= $this->db->query('SELECT COUNT(DISTINCT(elemento.id)) AS filas
                              FROM elemento 
                              INNER JOIN escenas_has_elementos ON escenas_has_elementos.id_elemento = elemento.id 
                              INNER JOIN plan_diario_has_escenas_has_unidades ph ON ph.id_escena = escenas_has_elementos.id_escena 
                              INNER JOIN plan_diario ON plan_diario.id = ph.id_plan_diario 
                              INNER JOIN unidad ON unidad.id = plan_diario.id_unidad 
                              WHERE plan_diario.fecha_inicio = "'.$fecha.'"
                              AND unidad.id_produccion = '.$idproduccion.'
                              AND unidad.id = '.$idunidad.' AND rol IS NULL
                              ORDER BY unidad.numero;');
    if ($query->num_rows>0) {
      return $query->result();
    }else{
      return false;
    }
  }

  public function suma_tiempos_pdf($idproduccion){
    $query = $this->db->query("SELECT SUM(duracion_estimada_minutos) AS minutos, SUM(duracion_estimada_segundos) AS segundos 
                      FROM (SELECT duracion_estimada_minutos, duracion_estimada_segundos FROM escena 
                      INNER JOIN produccion_has_capitulos ON escena.id_capitulo = produccion_has_capitulos.id 
                      WHERE escena.estado !=1 AND escena.estado !=3 AND escena.estado !=2 AND escena.estado !=12 AND escena.estado !=14  AND produccion_has_capitulos.id_produccion = ".$idproduccion." LIMIT 300) as subquery");
    if ($query->num_rows>0) {
      return $query->result();
    }else{
      return false;
    }
  }

  public function unidades_pdf($sql){
    $query = $this->db->query("SELECT numero FROM unidad WHERE unidad.id=".$sql." ORDER BY unidad.numero;");
    if ($query->num_rows>0) {
      return $query->result();
    }else{
      return false;
    }
  }

  public function semanas_reporte_semanal($id,$fecha){
    $query = $this->db->query('SELECT DISTINCT(id), fecha_inicio_semana AS fecha_valor, 
      DATE_FORMAT(fecha_inicio_semana, "%d-%b-%Y") AS fecha_muestra, 
      fecha_fin_semana AS fecha_valor_2, DATE_FORMAT(fecha_fin_semana, "%d-%b-%Y") AS fecha_muestra_2  
      FROM semanas_produccion WHERE semanas_produccion.id_produccion="'.$id.'" ;');
      if ($query->num_rows>0) {
        return $query->result();
      }else{
        return false;
      }
    ;
  }

  

  public function semanas_reporte_semanal_fecha_unidad($id,$fecha){
    $query = $this->db->query('SELECT DISTINCT(id),
      (SELECT count(*) total FROM semanas_produccion
      where id_produccion="'.$id.'" and fecha_inicio_semana<="'.$fecha.'") as inicio_semana,
      fecha_inicio_semana AS fecha_valor, 
      DATE_FORMAT(fecha_inicio_semana, "%d-%b-%Y") AS fecha_muestra, 
      fecha_fin_semana AS fecha_valor_2, 
      DATE_FORMAT(fecha_fin_semana, "%d-%b-%Y") AS fecha_muestra_2  
      FROM semanas_produccion WHERE semanas_produccion.id_produccion="'.$id.'" and fecha_inicio_semana>="'.$fecha.'";');
      if ($query->num_rows>0) {
        return $query->result();
      }else{
        return false;
      }
    ;
  }


  public function semanas_reporte_semanal_fecha_unidad_filtro($id,$fecha1,$fecha2){
    $query = $this->db->query('SELECT DISTINCT(id),
      (SELECT count(*) total FROM semanas_produccion
      where id_produccion="'.$id.'" and fecha_inicio_semana<="'.$fecha1.'") as inicio_semana,
      fecha_inicio_semana AS fecha_valor, 
      DATE_FORMAT(fecha_inicio_semana, "%d-%b-%Y") AS fecha_muestra, 
      fecha_fin_semana AS fecha_valor_2, 
      DATE_FORMAT(fecha_fin_semana, "%d-%b-%Y") AS fecha_muestra_2  
      FROM semanas_produccion WHERE semanas_produccion.id_produccion="'.$id.'" 
      and fecha_inicio_semana>="'.$fecha1.'" and fecha_inicio_semana<="'.$fecha2.'"');
      if ($query->num_rows>0) {
        return $query->result();
      }else{
        return false;
      }
  }


  public function semanas_reporte_semanal_fecha_unidad_filtro_max_min($id,$fecha1,$fecha2){
    $query = $this->db->query('SELECT 
         min(fecha_inicio_semana) min, max(fecha_fin_semana) max
        FROM semanas_produccion WHERE semanas_produccion.id_produccion='.$id.' 
        and fecha_inicio_semana>="'.$fecha1.'" and fecha_inicio_semana<="'.$fecha2.'"');
      if ($query->num_rows>0) {
        return $query->result();
      }else{
        return false;
      }
  }

  public function extras_cantidad($id){
    $query = $this->db->query('SELECT extras_escena.cantidad AS cantidad_extras FROM extras_escena WHERE extras_escena.id_escenas_has_elementos ="'.$id.'";');
      if ($query->num_rows>0) {
        return $query->result();
      }else{
        return false;
      }
  } 
    
}