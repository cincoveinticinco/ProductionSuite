<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class model_dashboard extends CI_Model {
 


public function produccion($id){
   $query=$this->db->query("SELECT produccion.minutos_reales, produccion.numero_capitulo,(select count(*) from produccion_has_capitulos where id_produccion=".$id.") as total_libretos,
        (select count(*) from produccion_has_capitulos where estado=5 and id_produccion=".$id.") as total_producidos,
        (select count(*) from produccion_has_capitulos where estado=4 and id_produccion=".$id.") as total_desglosados,
        (select count(*) from produccion_has_capitulos where (estado=2 or estado=3) and id_produccion=".$id.") as total_entregados,
        (select count(*) from produccion_has_capitulos where estado=1 and id_produccion=".$id.") as total_proyectados,
        (select sum(numero_escenas) from produccion_has_capitulos where id_produccion=".$id.") as total_escenas,
        (select sum(escenas_escritas) from produccion_has_capitulos where id_produccion=".$id.") as total_escenas_escritas,

        (SELECT COUNT(DISTINCT(capitulos_has_escenas.id_escena)) total from produccion_has_capitulos
        left join escena on escena.id_capitulo=produccion_has_capitulos.id 
        inner join capitulos_has_escenas on capitulos_has_escenas.id_escena=escena.id
        where id_produccion=".$id." and (capitulos_has_escenas.tiempo_post_minutos+capitulos_has_escenas.tiempo_post_segundos)<>0) as total_escenas_post_producidas,

        (select sum(escenas_producidas) from produccion_has_capitulos where id_produccion=".$id.") as total_escenas_producidas,
        (select sum(e.duracion_real_minutos) from produccion_has_capitulos p 
        inner join escena e on e.id_capitulo=p.id and (e.estado=1 OR e.estado=2 OR e.estado=12 OR e.estado=14) where id_produccion=".$id.") as total_minutos_producidos_escenas,
        (select sum(e.duracion_real_segundos) from produccion_has_capitulos p 
        inner join escena e on e.id_capitulo=p.id and (e.estado=1 OR e.estado=2 OR e.estado=12 OR e.estado=14) where id_produccion=".$id.") as total_segundos_producidos_escenas,
        (select sum(e.duracion_estimada_minutos) from produccion_has_capitulos p inner join escena e on e.id_capitulo=p.id and e.estado!=1
        where id_produccion=".$id.") as total_duracion_estimada_minutos,
        (select sum(e.duracion_estimada_segundos) from produccion_has_capitulos p 
        inner join escena e on e.id_capitulo=p.id and e.estado!=1
        where id_produccion=".$id." ) as total_duracion_estimada_segudos,

        (SELECT sum(capitulos_has_escenas.tiempo_post_minutos) from produccion_has_capitulos
        left join escena on escena.id_capitulo=produccion_has_capitulos.id 
        inner join capitulos_has_escenas on capitulos_has_escenas.id_escena=escena.id
        where id_produccion=".$id.") total_minutos_post, 

        (SELECT sum(capitulos_has_escenas.tiempo_post_segundos) from produccion_has_capitulos
        left join escena on escena.id_capitulo=produccion_has_capitulos.id 
        inner join capitulos_has_escenas on capitulos_has_escenas.id_escena=escena.id
        where id_produccion=".$id.") total_segundos_post, 

        (SELECT sum(capitulos_has_escenas.tiempo_post_cuadros) from produccion_has_capitulos
        left join escena on escena.id_capitulo=produccion_has_capitulos.id 
        inner join capitulos_has_escenas on capitulos_has_escenas.id_escena=escena.id
        where id_produccion=".$id.") tiempo_post_cuadros,

        produccion.numero_protagonistas, produccion.numero_repartos,
        produccion.numero_figurantes,produccion.numero_extras,produccion.locacion,produccion.numero_vehiculos,
        produccion.evento_pequeno,produccion.evento_mediano,produccion.evento_grande,produccion.escenas_libretos,
        produccion.locacion,produccion.estudio,produccion.produccion_interior,produccion.produccion_exterior,produccion.dia,
        produccion.noche,produccion.capitulos_proyectados,produccion.numero_locaciones locacion_proyectadas,produccion.numero_set,produccion.protagonistas_produccion,produccion.monto_figurante_extra,produccion.monto_figurante_extra_dolar,
        produccion.presupuesto_principales,produccion.presupuesto_figurante,produccion.presupuesto_secundarios,produccion.inicio_grabacion,produccion.fin_grabacion,
        produccion.tipo as tipo_produccion
        FROM produccion_has_capitulos
        inner join produccion on produccion.id=".$id."
        where id_produccion=".$id."
        group by id_produccion;");
      if ($query->num_rows>0) {
        return $query->result();
      }else
      {
        return false;
      }
  }

     public function detalle_unidad($id_unidad,$fecha){
     $query=$this->db->query("SELECT p.estado estado_plan,p.*,h.*,e.* FROM plan_diario p 
            inner join plan_diario_has_escenas_has_unidades h on h.id_plan_diario=p.id
            inner join escena e on e.id=h.id_escena
            left outer join unidad u on u.id=p.id_unidad
            where p.id_unidad=".$id_unidad." and p.fecha_inicio='".$fecha."'
            order by p.fecha_inicio;");
      if ($query->num_rows>0) {
        return $query->result_array();
      }else
      {
        return false;
      }
  }


   public function detalle_unidad2($id_unidad,$fecha){
     $query=$this->db->query("SELECT p.estado estado_plan,p.*,h.*,e.* FROM plan_diario p 
            inner join plan_diario_has_escenas_has_unidades h on h.id_plan_diario=p.id
            inner join escena e on e.id=h.id_escena
            left outer join unidad u on u.id=p.id_unidad
            where p.id_unidad=".$id_unidad." and p.fecha_inicio='".$fecha."'
            order by u.numero;");
      if ($query->num_rows>0) {
        return $query->result_array();
      }else
      {
        return false;
      }
  }

  
   public function user_id($id){
   $this->db->where('id',$id); 
   $query=$this->db->get('user');
     if ($query->num_rows>0) {
     return $query->result();
     } else {
      return false;
     }
  }


     public function detalle_semana_actual($id_produccion,$fecha){
     $query=$this->db->query("SELECT *,
        (select count(*) from semanas_produccion where id_produccion=".$id_produccion.") as total_semanas,
        (select count(*) from semanas_produccion where id_produccion=".$id_produccion." and fecha_inicio_semana<'".$fecha."') as semanas_actual
        FROM semanas_produccion
        where id_produccion=".$id_produccion."
        and fecha_inicio_semana<='".$fecha."' and fecha_fin_semana>='".$fecha."';");
      if ($query->num_rows>0) {
        return $query->result();
      }else
      {
        return false;
      }
  }

    public function acumulado($id_produccion,$fecha){
      $query=$this->db->query("SELECT *,
      (select sum(capitulos_programados) from semanas_produccion where id_produccion=".$id_produccion."
      and fecha_inicio_semana<='".$fecha."')  as total_capitulos,
       (select sum(minutos_proyectados) from semanas_produccion where id_produccion=".$id_produccion."
      and fecha_inicio_semana<='".$fecha."')  as total_minutos
      FROM semanas_produccion
      where id_produccion=".$id_produccion."
      and fecha_inicio_semana<='".$fecha."';");
        if ($query->num_rows>0) {
          return $query->result_array();
        }else{
          return false;
        }
   }   

public function comparativos($id_produccion){
      $query=$this->db->query("SELECT 
        sum((select count(*) from escena where id=e.id )) as total,
        sum((select count(*) from escena where id=e.id and id_tipo_locacion=1 )) as locacion,
        sum((select count(*) from escena where id=e.id and id_tipo_locacion=2 )) as estudio,
        sum((select count(*) from escena where id=e.id and id_interior_esterior=1 )) as interior,
        sum((select count(*) from escena where id=e.id and id_interior_esterior=1 and id_tipo_locacion=1 )) as interior_locacion,
        sum((select count(*) from escena where id=e.id and id_interior_esterior=1 and id_tipo_locacion=2 )) as interior_estudio,
        sum((select count(*) from escena where id=e.id and id_interior_esterior=2 )) as exterior,
        sum((select count(*) from escena where id=e.id and id_interior_esterior=2 and id_tipo_locacion=1 )) as exterior_locacion,
        sum((select count(*) from escena where id=e.id and id_interior_esterior=2 and id_tipo_locacion=2 )) as exterios_estudio,
        sum((select count(*) from escena where id=e.id and id_dia_noche=1 )) as dia,
        sum((select count(*) from escena where id=e.id and id_dia_noche=1 and id_tipo_locacion=1)) as locacion_dia,
        sum((select count(*) from escena where id=e.id and id_dia_noche=1 and id_tipo_locacion=2)) as estudio_dia,
        sum((select count(*) from escena where id=e.id and id_dia_noche=2 )) as noche,
        sum((select count(*) from escena where id=e.id and id_dia_noche=2 and id_tipo_locacion=1)) as locacion_noche,
        sum((select count(*) from escena where id=e.id and id_dia_noche=2 and id_tipo_locacion=2)) as estudio_noche
        from produccion_has_capitulos p
        inner join escena e on e.id_capitulo=p.id
        where p.id_produccion=".$id_produccion);
      if ($query->num_rows>0) {
        return $query->result();
      }else{
        return false;
      }
 }    

 public function capitulos_sin_editar($id_produccion){
      $query=$this->db->query("SELECT * FROM produccion_has_capitulos
      where id_produccion=".$id_produccion." and estado=5
      order by fecha_entregado desc limit 1;");
      if ($query->num_rows>0) {
        return $query->result();
      }else{
        return false;
      }
 } 
 public function capitulos_al_aire($id_produccion,$fecha){
      $query=$this->db->query("SELECT p.* FROM produccion_has_capitulos p
        where p.id_produccion=".$id_produccion." and p.fecha_aire<'".$fecha."'
        order by fecha_aire desc limit 1");
      if ($query->num_rows>0) {
        return $query->result();
      }else{
        return false;
      }
 } 

  public function semanas($id_produccion){
      $query=$this->db->query("SELECT * FROM semanas_produccion
        where id_produccion=".$id_produccion." limit 10");
      if ($query->num_rows>0) {
        return $query->result_array();
      }else{
        return false;
      }
 }
  public function limit_semanas($id_produccion){
      $query=$this->db->query("SELECT count(*) as total FROM semanas_produccion
        where id_produccion=".$id_produccion." and fecha_fin_semana<=CURDATE();");
      if ($query->num_rows>0) {
        return $query->result();
      }else{
        return false;
      }
 } 


 

  public function semanas_cargar($id_produccion,$limit1,$limit2){
      $query=$this->db->query("SELECT * FROM semanas_produccion
        where id_produccion=".$id_produccion." limit ".$limit2.",10");
      if ($query->num_rows>0) {
        return $query->result_array();
      }else{
        return false;
      }
 }
/////////resumen////////////////////
 public function resumen_indicadores_locacion($id_produccion,$id_capitulo){
      $query=$this->db->query("SELECT e.id_capitulo,p.numero,count(e.id) as locacion
        from produccion_has_capitulos p
        inner join escena e on e.id_capitulo=p.id
        where p.id_produccion=".$id_produccion." and id_tipo_locacion=1 and p.id=".$id_capitulo." 
        group by e.id_capitulo;");
      if ($query->num_rows>0) {
        return $query->result();
      }else{
        return false;
      }
 }

  public function resumen_indicadores_estudio($id_produccion,$id_capitulo){
      $query=$this->db->query("SELECT e.id_capitulo,p.numero,count(e.id) as estudio
        from produccion_has_capitulos p
        inner join escena e on e.id_capitulo=p.id
        where p.id_produccion=".$id_produccion." and id_tipo_locacion = 2 and p.id = ".$id_capitulo." 
        group by e.id_capitulo;");
      if ($query->num_rows>0) {
        return $query->result();
      }else{
        return false;
      }
 } 
  public function resumen_indicadores_interior($id_produccion,$id_capitulo){
      $query=$this->db->query("SELECT e.id_capitulo,p.numero,count(e.id) as interior
        from produccion_has_capitulos p
        inner join escena e on e.id_capitulo=p.id
        where p.id_produccion=".$id_produccion." and id_interior_esterior=1 and p.id=".$id_capitulo." 
        group by e.id_capitulo;");
      if ($query->num_rows>0) {
        return $query->result();
      }else{
        return false;
      }
 }  
   public function resumen_indicadores_interior_locacion($id_produccion,$id_capitulo){
      $query=$this->db->query("SELECT e.id_capitulo,p.numero,count(e.id) as interior_locacion
        from produccion_has_capitulos p
        inner join escena e on e.id_capitulo=p.id
        where p.id_produccion=".$id_produccion." and id_interior_esterior=1 and id_tipo_locacion=1 and p.id=".$id_capitulo." 
        group by e.id_capitulo;");
      if ($query->num_rows>0) {
        return $query->result();
      }else{
        return false;
      }
 } 
   public function resumen_indicadores_interior_estudio($id_produccion,$id_capitulo){
      $query=$this->db->query("SELECT e.id_capitulo,p.numero,count(e.id) as interior_estudio
        from produccion_has_capitulos p
        inner join escena e on e.id_capitulo=p.id
        where p.id_produccion=".$id_produccion." and id_interior_esterior=1 and id_tipo_locacion=2 and p.id=".$id_capitulo." 
        group by e.id_capitulo;");
      if ($query->num_rows>0) {
        return $query->result();
      }else{
        return false;
      }
 }  
   public function resumen_indicadores_exterior($id_produccion,$id_capitulo){
      $query=$this->db->query("SELECT e.id_capitulo,p.numero,count(e.id) as exterior
        from produccion_has_capitulos p
        inner join escena e on e.id_capitulo=p.id
        where p.id_produccion=".$id_produccion." and id_interior_esterior=2 and p.id=".$id_capitulo." 
        group by e.id_capitulo;");
      if ($query->num_rows>0) {
        return $query->result();
      }else{
        return false;
      }
 } 
   public function resumen_indicadores_exterior_locacion($id_produccion,$id_capitulo){
      $query=$this->db->query("SELECT e.id_capitulo,p.numero,count(e.id) as exterior_locacion
        from produccion_has_capitulos p
        inner join escena e on e.id_capitulo=p.id
        where p.id_produccion=".$id_produccion." and id_interior_esterior=2 and id_tipo_locacion=1 and p.id=".$id_capitulo." 
        group by e.id_capitulo;");
      if ($query->num_rows>0) {
        return $query->result();
      }else{
        return false;
      }
 }   
    public function resumen_indicadores_exterios_estudio($id_produccion,$id_capitulo){
      $query=$this->db->query("SELECT e.id_capitulo,p.numero,count(e.id) as exterios_estudio
        from produccion_has_capitulos p
        inner join escena e on e.id_capitulo=p.id
        where p.id_produccion=".$id_produccion." and id_interior_esterior=2 and id_tipo_locacion=2 and p.id=".$id_capitulo." 
        group by e.id_capitulo;");
      if ($query->num_rows>0) {
        return $query->result();
      }else{
        return false;
      }
 }   
     public function resumen_indicadores_dia($id_produccion,$id_capitulo){
      $query=$this->db->query("SELECT e.id_capitulo,p.numero,count(e.id) as dia
        from produccion_has_capitulos p
        inner join escena e on e.id_capitulo=p.id
        where p.id_produccion=".$id_produccion." and id_dia_noche=1 and p.id=".$id_capitulo." 
        group by e.id_capitulo;");
      if ($query->num_rows>0) {
        return $query->result();
      }else{
        return false;
      }
 }

  public function resumen_indicadores_locacion_dia($id_produccion,$id_capitulo){
      $query=$this->db->query("SELECT e.id_capitulo,p.numero,count(e.id) as locacion_dia
        from produccion_has_capitulos p
        inner join escena e on e.id_capitulo=p.id
        where p.id_produccion=".$id_produccion." and id_dia_noche=1 and id_tipo_locacion=1 and p.id=".$id_capitulo." 
        group by e.id_capitulo;");
      if ($query->num_rows>0) {
        return $query->result();
      }else{
        return false;
      }
  }
  public function resumen_indicadores_estudio_dia($id_produccion,$id_capitulo){
      $query=$this->db->query("SELECT e.id_capitulo,p.numero,count(e.id) as estudio_dia
        from produccion_has_capitulos p
        inner join escena e on e.id_capitulo=p.id
        where p.id_produccion=".$id_produccion." and id_dia_noche=1 and id_tipo_locacion=2 and p.id=".$id_capitulo." 
        group by e.id_capitulo;");
      if ($query->num_rows>0) {
        return $query->result();
      }else{
        return false;
      }
  }
  public function resumen_indicadores_noche($id_produccion,$id_capitulo){
      $query=$this->db->query("SELECT e.id_capitulo,p.numero,count(e.id) as noche
        from produccion_has_capitulos p
        inner join escena e on e.id_capitulo=p.id
        where p.id_produccion=".$id_produccion." and id_dia_noche=2 and p.id=".$id_capitulo." 
        group by e.id_capitulo;");
      if ($query->num_rows>0) {
        return $query->result();
      }else{
        return false;
      }
  }   
  public function resumen_indicadores_locacion_noche($id_produccion,$id_capitulo){
      $query=$this->db->query("SELECT e.id_capitulo,p.numero,count(e.id) as locacion_noche
        from produccion_has_capitulos p
        inner join escena e on e.id_capitulo=p.id
        where p.id_produccion=".$id_produccion." and id_dia_noche=2 and id_tipo_locacion=1 and p.id=".$id_capitulo." 
        group by e.id_capitulo;");
      if ($query->num_rows>0) {
        return $query->result();
      }else{
        return false;
      }
  }  
    public function resumen_indicadores_estudio_noche($id_produccion,$id_capitulo){
      $query=$this->db->query("SELECT e.id_capitulo,p.numero,count(e.id) as estudio_noche
        from produccion_has_capitulos p
        inner join escena e on e.id_capitulo=p.id
        where p.id_produccion=".$id_produccion." and id_dia_noche=2 and id_tipo_locacion=2 and p.id=".$id_capitulo." 
        group by e.id_capitulo;");
      if ($query->num_rows>0) {
        return $query->result();
      }else{
        return false;
      }
  }              
 ///////////fin resumen///////////////// 
 /*public function resumen_indicadores($id_produccion){
      $query=$this->db->query("SELECT e.id_capitulo,p.numero,
        (select count(escena.id) from escena where id_capitulo=e.id_capitulo and id_tipo_locacion=1 ) as locacion,
        (select count(escena.id) from escena where id_capitulo=e.id_capitulo and id_tipo_locacion=2 ) as estudio,
        (select count(escena.id) from escena where id_capitulo=e.id_capitulo and id_interior_esterior=1 ) as interior,
        (select count(escena.id) from escena where id_capitulo=e.id_capitulo and id_interior_esterior=1 and id_tipo_locacion=1 ) as interior_locacion,
        (select count(escena.id) from escena where id_capitulo=e.id_capitulo and id_interior_esterior=1 and id_tipo_locacion=2 ) as interior_estudio,
        (select count(escena.id) from escena where id_capitulo=e.id_capitulo and id_interior_esterior=2 ) as exterior,
        (select count(escena.id) from escena where id_capitulo=e.id_capitulo and id_interior_esterior=2 and id_tipo_locacion=1 ) as exterior_locacion,
        (select count(escena.id) from escena where id_capitulo=e.id_capitulo and id_interior_esterior=2 and id_tipo_locacion=2 ) as exterios_estudio,
        (select count(escena.id) from escena where id_capitulo=e.id_capitulo and id_dia_noche=1 ) as dia,
        (select count(escena.id) from escena where id_capitulo=e.id_capitulo and id_dia_noche=1 and id_tipo_locacion=1) as locacion_dia,
        (select count(escena.id) from escena where id_capitulo=e.id_capitulo and id_dia_noche=1 and id_tipo_locacion=2) as estudio_dia,
        (select count(escena.id) from escena where id_capitulo=e.id_capitulo and id_dia_noche=2 ) as noche,
        (select count(escena.id) from escena where id_capitulo=e.id_capitulo and id_dia_noche=2 and id_tipo_locacion=1) as locacion_noche,
        (select count(escena.id) from escena where id_capitulo=e.id_capitulo and id_dia_noche=2 and id_tipo_locacion=2) as estudio_noche
        from produccion_has_capitulos p
        inner join escena e on e.id_capitulo=p.id
        where p.id_produccion=".$id_produccion."
        group by e.id_capitulo;");
      if ($query->num_rows>0) {
        return $query->result_array();
      }else{
        return false;
      }
 } */   
  public function resumen_indicadores($id_produccion,$limit){
      $query=$this->db->query("SELECT e.id_capitulo,p.numero
        from produccion_has_capitulos p
        inner join escena e on e.id_capitulo=p.id
        where p.id_produccion=".$id_produccion."
        group by e.id_capitulo limit ".$limit.",20");
      if ($query->num_rows>0) {
        return $query->result_array();
      }else{
        return false;
      }
 } 

 public function capitulos($id_produccion){
      $query=$this->db->query("SELECT p.id as id_capitulo,p.numero,p.fecha_aire,p.fecha_entregado,p.duracion_estimada,p.estado,
      sum((select count(*) from escena where id=e.id )) as total,
      sum((select count(*) from escena where id=e.id and id_tipo_locacion=1 )) as locacion,
      sum((select count(*) from escena where id=e.id and id_tipo_locacion=2 )) as estudio,
      (select sum(e.duracion_estimada_minutos) from escena where id=e.id) as total_duracion_estimada_minutos,
      (select sum(e.duracion_estimada_segundos) from escena where id=e.id) as total_duracion_estimada_segundos,
      p.escenas_producidas as total_producidos,
      (select sum(e.duracion_real_minutos) from escena where id=e.id) as total_duracion_real_minutos,
      (select sum(e.duracion_real_segundos) from escena where id=e.id) as total_duracion_real_segundos
      FROM produccion_has_capitulos p
      inner join escena e on e.id_capitulo=p.id
      where p.id_produccion=".$id_produccion." and p.numero_escenas is not null
        group by p.id order by p.numero;");
      if ($query->num_rows>0) {
        return $query->result_array();
      }else{
        return false;
      }
 }

 public function capitulos_limite($id_produccion,$limite=0){
      $query=$this->db->query("SELECT p.id AS id_capitulo,p.numero,p.fecha_aire,p.fecha_entregado,p.duracion_estimada,p.estado,
      SUM((SELECT COUNT(*) FROM escena WHERE id=e.id )) AS total,
      SUM((SELECT COUNT(*) FROM escena WHERE id=e.id AND id_tipo_locacion=1 )) AS locacion,
      SUM((SELECT COUNT(*) FROM escena WHERE id=e.id AND id_tipo_locacion=2 )) AS estudio,
      (SELECT sum(e.duracion_estimada_minutos) FROM escena WHERE id=e.id) AS total_duracion_estimada_minutos,
      (SELECT SUM(e.duracion_estimada_segundos) FROM escena WHERE id=e.id) AS total_duracion_estimada_segundos,
      p.escenas_producidas as total_producidos,
      (SELECT SUM(e.duracion_real_minutos) FROM escena WHERE id=e.id) AS total_duracion_real_minutos,
      (SELECT SUM(e.duracion_real_segundos) FROM escena WHERE id=e.id) AS total_duracion_real_segundos
      FROM produccion_has_capitulos p
      INNER JOIN escena e ON e.id_capitulo=p.id
      WHERE p.id_produccion=".$id_produccion." AND p.numero_escenas IS NOT NULL
        GROUP BY p.id ORDER BY p.numero LIMIT ".$limite.",12;");
      if ($query->num_rows>0) {
        return $query->result_array();
      }else{
        return false;
      }
 }      


   public function escenas_id_capitulos($id_capitulo){
      $query=$this->db->query("SELECT * from escena where id_capitulo=".$id_capitulo." order by CAST(SUBSTRING_INDEX(numero_escena,'.', 1 ) AS UNSIGNED) ASC,
(CASE INSTR(numero_escena, '.') 
    WHEN 0 THEN 0
    ELSE CAST(SUBSTRING_INDEX(numero_escena,'.', -1 ) AS UNSIGNED) END) ASC");
      if ($query->num_rows>0) {
        return $query->result_array();
      }else{
        return false;
      }
  }  

   public function escenas_id_capitulos_limit($id_capitulo,$limit){
      $query=$this->db->query("SELECT * from escena where id_capitulo=".$id_capitulo." order by CAST(SUBSTRING_INDEX(numero_escena,'.', 1 ) AS UNSIGNED) ASC,
(CASE INSTR(numero_escena, '.') 
    WHEN 0 THEN 0
    ELSE CAST(SUBSTRING_INDEX(numero_escena,'.', -1 ) AS UNSIGNED) END) ASC limit ".$limit.",1  ;");
      if ($query->num_rows>0) {
        return $query->result();
      }else{
        return false;
      }
  }   

  public function acumunaldos_semana($id_produccion){
      $query=$this->db->query("SELECT SUM(escena.duracion_real_minutos) AS minutos_reales, SUM(escena.duracion_real_segundos) AS segundos_reales,
            ((SELECT min_proy_semana FROM produccion WHERE produccion.id = ".$id_produccion.")*(SELECT COUNT(DISTINCT(semanas_produccion.id)) FROM semanas_produccion 
            WHERE id_produccion = ".$id_produccion." AND ((
            CURDATE() BETWEEN semanas_produccion.fecha_inicio_semana AND semanas_produccion.fecha_fin_semana )
            OR (semanas_produccion.fecha_fin_semana >= (SELECT min(fecha_inicio) FROM unidad WHERE id_produccion = ".$id_produccion." ) 
            AND semanas_produccion.fecha_fin_semana<= CURDATE())))) AS total_minutos
            FROM escena
            INNER JOIN produccion_has_capitulos ON produccion_has_capitulos.id = escena.id_capitulo
            WHERE produccion_has_capitulos.id_produccion = ".$id_produccion."  AND 
            fecha_produccion BETWEEN (SELECT min(fecha_inicio) FROM unidad WHERE id_produccion = ".$id_produccion." ) AND CURDATE(); ");
      if ($query->num_rows>0) {
        return $query->result();
      }else{
        return false;
      }
 }

 public function capitulos_editados($id_produccion){
    $query=$this->db->query("SELECT COUNT(DISTINCT(c.id)) total 
        FROM capitulos c
        INNER JOIN capitulo_has_estados ON capitulo_has_estados.id_capitulo = c.id
        WHERE capitulo_has_estados.id_estado=5 AND capitulo_has_estados.activo=0 AND id_produccion=".$id_produccion);
    if ($query->num_rows>0) {
      return $query->result();
    }else{
      return false;
    }
 }

 public function ultimo_capitulos_entregado($id_produccion){
      $query=$this->db->query("SELECT * FROM capitulos 
                                INNER JOIN capitulo_has_estados ON capitulo_has_estados.id_capitulo = capitulos.id
                                WHERE id_produccion=".$id_produccion."
                                AND capitulo_has_estados.id_estado=15  AND capitulo_has_estados.activo=1
                                ORDER BY fecha_entregada DESC LIMIT 1;");
      if ($query->num_rows>0) {
        return $query->result();
      }else{
        return false;
      }
 }

 public function personajes_rol($id_produccion,$rol){
      $query=$this->db->query("SELECT count(el.id)
        from produccion_has_capitulos h
        inner join escena e on e.id_capitulo=h.id
        inner join escenas_has_elementos ee on ee.id_escena=e.id
        right outer join elemento el on el.id=ee.id_elemento
        where h.id_produccion=".$id_produccion." and el.rol=".$rol." group by el.id;");
      return $query->num_rows;
 }

  public function total_locaciones_produccion($id_produccion){
      $query=$this->db->query("SELECT * FROM 
        locacion where id_produccion=".$id_produccion);
      return $query->num_rows;
 }

  public function total_vehiculos_produccion($id_produccion){
      $query=$this->db->query("SELECT el.*
            from produccion_has_capitulos h
            inner join escena e on e.id_capitulo=h.id
            inner join escenas_has_elementos ee on ee.id_escena=e.id
            right outer join elemento el on el.id=ee.id_elemento
            inner join categoria_elemento c on c.id_produccion=".$id_produccion." and tipo='Vehiculo'
            where h.id_produccion=".$id_produccion." and el.id_tipo_elemento=c.id group by  el.id;");
      return $query->num_rows;
 }

 public function total_evento_produccion($id_produccion,$evento){
      $query=$this->db->query("SELECT e.id
        from produccion_has_capitulos h
        inner join escena e on e.id_capitulo=h.id
        where h.id_produccion=".$id_produccion." and e.id_magnitud=".$evento);
      return $query->num_rows;
 }

 public function ultima_semana_produccion($id_produccion){
      $query=$this->db->query("SELECT * FROM semanas_produccion
        where id_produccion=".$id_produccion." order by fecha_fin_semana desc limit 1;");
      if ($query->num_rows>0) {
        return $query->result();
      }else{
        return false;
      }
 }

 public function estatus_capitulos($id_produccion,$limite=0){
  $query=$this->db->query("SELECT capitulos.numero, 
                            IFNULL((SELECT '1' 
                            FROM capitulo_has_estados 
                            WHERE capitulo_has_estados.id_capitulo=capitulos.id AND capitulo_has_estados.id_estado =2 AND capitulo_has_estados.activo!=3 LIMIT 1),0) AS estado_1,
                            IFNULL((SELECT '1' 
                            FROM capitulo_has_estados 
                            WHERE capitulo_has_estados.id_capitulo=capitulos.id AND capitulo_has_estados.id_estado =3 AND capitulo_has_estados.activo!=3 LIMIT 1),0) AS estado_2,
                            IFNULL((SELECT '1' 
                            FROM capitulo_has_estados 
                            WHERE capitulo_has_estados.id_capitulo=capitulos.id AND capitulo_has_estados.id_estado =4 AND capitulo_has_estados.activo!=3 LIMIT 1),0) AS estado_3,
                            IFNULL((SELECT '1' 
                            FROM capitulo_has_estados 
                            WHERE capitulo_has_estados.id_capitulo=capitulos.id AND capitulo_has_estados.id_estado =5 AND capitulo_has_estados.activo!=3 LIMIT 1),0) AS estado_4,
                            IFNULL((SELECT '1' 
                            FROM capitulo_has_estados 
                            WHERE capitulo_has_estados.id_capitulo=capitulos.id AND capitulo_has_estados.id_estado =6 AND capitulo_has_estados.activo!=3 LIMIT 1),0) AS estado_5,
                            IFNULL((SELECT '1' 
                            FROM capitulo_has_estados 
                            WHERE capitulo_has_estados.id_capitulo=capitulos.id AND capitulo_has_estados.id_estado =7 AND capitulo_has_estados.activo!=3 LIMIT 1),0) AS estado_6,
                            IFNULL((SELECT '1' 
                            FROM capitulo_has_estados 
                            WHERE capitulo_has_estados.id_capitulo=capitulos.id AND capitulo_has_estados.id_estado =8 AND capitulo_has_estados.activo!=3 LIMIT 1),0) AS estado_7,
                            IFNULL((SELECT '1' 
                            FROM capitulo_has_estados 
                            WHERE capitulo_has_estados.id_capitulo=capitulos.id AND capitulo_has_estados.id_estado =9 AND capitulo_has_estados.activo!=3 LIMIT 1),0) AS estado_8,
                            IFNULL((SELECT '1' 
                            FROM capitulo_has_estados 
                            WHERE capitulo_has_estados.id_capitulo=capitulos.id AND capitulo_has_estados.id_estado =10 AND capitulo_has_estados.activo!=3 LIMIT 1),0) AS estado_9,
                            IFNULL((SELECT '1' 
                            FROM capitulo_has_estados 
                            WHERE capitulo_has_estados.id_capitulo=capitulos.id AND capitulo_has_estados.id_estado =11 AND capitulo_has_estados.activo!=3 LIMIT 1),0) AS estado_10,
                            IFNULL((SELECT '1' 
                            FROM capitulo_has_estados 
                            WHERE capitulo_has_estados.id_capitulo=capitulos.id AND capitulo_has_estados.id_estado =12 AND capitulo_has_estados.activo!=3 LIMIT 1),0) AS estado_11,
                            IFNULL((SELECT '1' 
                            FROM capitulo_has_estados 
                            WHERE capitulo_has_estados.id_capitulo=capitulos.id AND capitulo_has_estados.id_estado =13 AND capitulo_has_estados.activo!=3 LIMIT 1),0) AS estado_12,
                            IFNULL((SELECT '1' 
                            FROM capitulo_has_estados 
                            WHERE capitulo_has_estados.id_capitulo=capitulos.id AND capitulo_has_estados.id_estado =14 AND capitulo_has_estados.activo!=3 LIMIT 1),0) AS estado_13,
                            IFNULL((SELECT '1' 
                            FROM capitulo_has_estados 
                            WHERE capitulo_has_estados.id_capitulo=capitulos.id AND capitulo_has_estados.id_estado =15 AND capitulo_has_estados.activo!=3 LIMIT 1),0) AS estado_14
                            FROM capitulos WHERE id_produccion = ".$id_produccion." order by numero  LIMIT ".$limite.",10;");

    if ($query->num_rows>0) {
      return $query->result_array();
    }else{
      return false;
    }
 }

 public function widgets_usuario($iduser){
  $this->db->where('id_usuario',$iduser);
  $this->db->where('tipo',3);
  $query=$this->db->get('columnas_usuario');
    if ($query->num_rows>0) {
      return $query->result();
    }else{
      return false;
    }
 }



  public function actualizar_orden_widgets($data){
    $this->db->where('id_usuario',$data['id_usuario']);
    $this->db->where('tipo',$data['tipo']);
    $this->db->update('columnas_usuario',$data);
  }

  public function agregar_orden_widgets($data){
   $this->db->insert('columnas_usuario',$data);
  }

  public function escenas_grabacion($idunidad){
    $query=$this->db->query("SELECT pl.numero AS numero_libreto, escena.numero_escena
                      FROM plan_diario_has_escenas_has_unidades pd
                      INNER JOIN escena ON escena.id = pd.id_escena
                      INNER JOIN produccion_has_capitulos pl ON pl.id = escena.id_capitulo
                      INNER JOIN plan_diario ON plan_diario.id = pd.id_plan_diario
                      WHERE plan_diario.id_unidad = ".$idunidad." AND plan_diario.fecha_inicio = CURDATE() AND pd.comienzo_ens IS NOT NULL AND pd.fin_produccion IS NULL;");
    if ($query->num_rows>0) {
      return $query->result();
    }else{
      return false;
    }
  }

  public function capitulos_escenas($id_produccion,$limit){
    $query=$this->db->query("SELECT c.*,e.id as id_escena,es.descripcion as estado, rol.descripcion as responsable,
        (sum(e.duracion_estimada_minutos) )as minutos_estimados, 
        (sum(e.duracion_estimada_segundos) )as segundos_estimados, 
        (sum(e.duracion_real_minutos) )as minutos, 
        (sum(e.duracion_real_segundos) )as segundos,
        (sum(h.tiempo_post_minutos) )as minutos_post, 
        (sum(h.tiempo_post_segundos) )as segundos_post,
        (sum(h.tiempo_post_cuadros) )as cuadros,
        (SUM(c.flashback_minutos)+SUM(c.transiciones_minutos)+SUM(c.stab_minutos)+SUM(c.recap_minutos)+SUM(c.cabezote_minutos)+SUM(c.credito_minutos))as minutos_extra,
        (SUM(c.flashback_segundos)+SUM(c.transiciones_segundos)+SUM(c.stab_segundos)+SUM(c.recap_segundos)+SUM(c.cabezote_segundos)+SUM(c.credito_segundos))as segundos_extra,
        (SUM(c.flashback_cuadros)+SUM(c.transiciones_cuadros)+SUM(c.stab_cuadros)+SUM(c.recap_cuadros)+SUM(c.cabezote_cuadros)+SUM(c.credito_cuadros))as cuadros_extra,
        (select count(capitulos_has_escenas.id) from capitulos_has_escenas where capitulos_has_escenas.id_capitulo=c.id) as total_escenas
         FROM capitulos c
        left join capitulos_has_escenas h on h.id_capitulo=c.id
        left join escena e on e.id=h.id_escena
        inner join estados_capitulo_post es on es.id=c.id_estado
        inner join rol_otros rol on rol.id=es.id_rol
        where c.id_produccion=".$id_produccion." group by c.id order by c.numero limit  ".$limit.",10;");
      if ($query->num_rows>0) {
        return $query->result_array();
      }else{
         return false;
      }
  }


  public function max_escenas_libreto($id){
    $query=$this->db->query("SELECT count(e.id) as total FROM produccion_has_capitulos c
        inner join escena e on e.id_capitulo=c.id
        where c.id_produccion=".$id." GROUP BY e.id_capitulo order by total desc limit 1;");
    if ($query->num_rows>0) {
      return $query->result();
    }else{
      return false;
    }
  }

  public function escenas_programadas($id,$fecha_inicio,$fecha_fin){
    $query=$this->db->query("SELECT escena.* from escena 
      INNER JOIN produccion_has_capitulos cap ON cap.id = escena.id_capitulo
      LEFT OUTER JOIN plan_diario_has_escenas_has_unidades plan_esc ON plan_esc.id_escena = escena.id
      LEFT OUTER JOIN plan_diario plan_dia ON plan_dia.id = plan_esc.id_plan_diario 
      LEFT OUTER JOIN unidad uni ON uni.id = plan_dia.id_unidad 
      WHERE cap.id_produccion = ".$id." 
      AND  (plan_dia.fecha_inicio BETWEEN '".$fecha_inicio."' AND '".$fecha_fin."' 
      OR escena.fecha_produccion BETWEEN '".$fecha_inicio."' 
      AND '".$fecha_fin."'  );");
    return $query->num_rows();
    /*if ($query->num_rows>0) {
      return $query->num_rows();
    }else{
      return false;
    }*/
  }

  /*public function escenas_producidas($id,$fecha_inicio,$fecha_fin){
    $query=$this->db->query("SELECT escena.* from unidad 
      inner join plan_diario on plan_diario.id_unidad=unidad.id
      inner join plan_diario_has_escenas_has_unidades on plan_diario_has_escenas_has_unidades.id_plan_diario=plan_diario.id
      inner join escena on escena.id=plan_diario_has_escenas_has_unidades.id_escena and
      (escena.estado=1 or escena.estado=2 or escena.estado=12 or escena.estado=14)
      where unidad.id_produccion=$id and plan_diario.fecha_inicio between '$fecha_inicio' and '$fecha_fin'
      and escena.id not in (SELECT escena.id from unidad 
inner join plan_diario on plan_diario.id_unidad=unidad.id
inner join plan_diario_has_escenas_has_unidades on plan_diario_has_escenas_has_unidades.id_plan_diario=plan_diario.id
inner join escena on escena.id=plan_diario_has_escenas_has_unidades.id_escena
where unidad.id_produccion=233 and plan_diario.fecha_inicio > '$fecha_inicio')
      order by plan_diario.fecha_inicio asc;");
    return $query->num_rows();

  }*/

  public function escenas_producidas($id,$fecha_inicio,$fecha_fin){
    $query=$this->db->query("SELECT escena.* from escena 
        INNER JOIN produccion_has_capitulos cap ON cap.id = escena.id_capitulo
        LEFT OUTER JOIN plan_diario_has_escenas_has_unidades plan_esc ON plan_esc.id_escena = escena.id
        AND plan_esc.id_plan_diario = 
        (SELECT MAX(plan_diario.id) 
        FROM plan_diario 
        INNER JOIN plan_diario_has_escenas_has_unidades pl ON pl.id_plan_diario = plan_diario.id 
        WHERE pl.id_escena = escena.id AND plan_diario.estado !=5) 
        LEFT OUTER JOIN plan_diario plan_dia ON plan_dia.id = plan_esc.id_plan_diario 
        LEFT OUTER JOIN unidad uni ON uni.id = plan_dia.id_unidad 
        WHERE cap.id_produccion = ".$id." 
        AND  (plan_dia.fecha_inicio BETWEEN '".$fecha_inicio."' AND '".$fecha_fin."' 
        OR escena.fecha_produccion BETWEEN '".$fecha_inicio."' 
        AND '".$fecha_fin."'  ) and (escena.estado=1 or escena.estado=2 or escena.estado=12 or escena.estado=14);");
            return $query->num_rows();

  }

   public function personajes_no_extra_liquidacion($id_produccion){
    $query = $this->db->query("SELECT elemento.id, elemento.id_tipo_contrato,elemento.id_tipo_moneda, elemento.nombre,elemento.documento_actor,rol_actores_elementos.rol AS rol, elemento.monto, elemento.actor_nombre, elemento.actor_apellido,
                                      tipo_contrato.descripcion AS tipo_contrato,
                                (SELECT  group_concat(DISTINCT(prod.numero ))
                                from escenas_has_elementos e2
                                INNER JOIN escena es2 on es2.id=e2.id_escena
                                INNER JOIN produccion_has_capitulos prod ON prod.id=es2.id_capitulo
                                where e2.id_elemento=elemento.id AND (es2.estado=1 or es2.estado=2 or es2.estado=12 or es2.estado=14)) as libretos_personaje
                                FROM  elemento
                                INNER JOIN rol_actores_elementos ON rol_actores_elementos.id = elemento.rol
                                INNER JOIN categoria_elemento ON categoria_elemento.id = elemento.id_tipo_elemento
                                left JOIN tipo_contrato ON tipo_contrato.id = elemento.id_tipo_contrato
                                WHERE elemento.rol != 4 AND categoria_elemento.id_produccion = ".$id_produccion." ORDER BY elemento.nombre; ");
    if ($query->num_rows>0){
      return $query->result();
    }else {
      return false;
    }
  }

  
 
}