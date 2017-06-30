<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class model_post_produccion extends CI_Model {


  public function capitulo_numero($numero,$id_produccion){
      $this->db->where('numero',$numero);
      $this->db->where('id_produccion',$id_produccion);
      $query=$this->db->get('capitulos');
     if ($query->num_rows>0){
      return $query->result();
     } else {
      return false;
     }
  }


  public function capitulo_id($id_capitulo){
    $query=$this->db->query("SELECT c.*,rol.descripcion as responsable, 
    (SELECT group_concat(capitulo_has_estados.id_estado,'_',estados_capitulo_post.descripcion SEPARATOR ',') FROM capitulo_has_estados
    INNER JOIN estados_capitulo_post ON estados_capitulo_post.id = capitulo_has_estados.id_estado
    WHERE id_capitulo = c.id AND activo = 1) AS estado,
    (SELECT group_concat(rol_otros.descripcion SEPARATOR '-') 
    FROM capitulo_has_estados 
    INNER JOIN estados_capitulo_post ON estados_capitulo_post.id = capitulo_has_estados.id_estado
    INNER JOIN rol_otros on rol_otros.id=estados_capitulo_post.id_rol
    WHERE id_capitulo = c.id AND activo = 1) AS responsable
    from capitulos c 
    inner join estados_capitulo_post es on es.id=c.id_estado
    inner join rol_otros rol on rol.id=es.id_rol where c.id=".$id_capitulo."");
    if ($query->num_rows>0) {
      return $query->result();
    }else{
       return false;
    }
  }

   
  public function capitulo_produccion($id_produccion){
      $this->db->where('id_produccion',$id_produccion);
      $query=$this->db->get('capitulos');
     if ($query->num_rows>0){
      return $query->result_array();
     } else {
      return false;
     }
  }

  public function libretos_id_produccion($id_produccion){
      $this->db->where('id_produccion',$id_produccion);
      $query=$this->db->get('produccion_has_capitulos');
     if ($query->num_rows>0){
      return $query->result_array();
     } else {
      return false;
     }
  }


  public function escena_id_capitulo($id_libreto){
    $query=$this->db->query("SELECT id,numero_escena FROM escena where id_capitulo=".$id_libreto);
    if ($query->num_rows>0) {
      return $query->result_array();
    }else{
       return false;
    }
  }

  public function insert_capitulos($datos){
      $data=array(
        'numero'=> $datos['numero'],
        'id_produccion'         => $datos['id_produccion'],
        'id_estado'         => $datos['estado'],);
    return $this->db->insert('capitulos',$data);
  }

  public function insert_capitulos_has_esena($datos){
    /*  $data=array(
        'id_capitulo'=> $datos['id_capitulo'],
        'id_escena'=> $datos['id_escena']
      );*/
    return $this->db->insert('capitulos_has_escenas',$datos);
  }

   /*public function capitulos_escenas($id_produccion,$id_user='',$limit=40,$sql=""){
    if($id_user){
      $query=$this->db->query("SELECT DISTINCT(c.id) AS id_base, c.*,e.id as id_escena,
        (SELECT group_concat(capitulo_has_estados.id_estado,'_',estados_capitulo_post.descripcion SEPARATOR ',') FROM capitulo_has_estados
        INNER JOIN estados_capitulo_post ON estados_capitulo_post.id = capitulo_has_estados.id_estado
        WHERE id_capitulo = c.id AND activo = 1) AS estado,
          rol.descripcion as responsable,
        (sum(e.duracion_estimada_minutos) )as minutos_estimados, (sum(e.duracion_estimada_segundos) )as segundos_estimados, 
        (sum(e.duracion_real_minutos) )as minutos, (sum(e.duracion_real_segundos) )as segundos,
      	(sum(e.tiempo_post_minutos) )as minutos_post, (sum(e.tiempo_post_segundos) )as segundos_post,
				(SUM(c.flashback_minutos)+SUM(c.transiciones_minutos)+SUM(c.stab_minutos)+SUM(c.recap_minutos)+SUM(c.cabezote_minutos)+SUM(c.credito_minutos))as minutos_extra,
        (SUM(c.flashback_segundos)+SUM(c.transiciones_segundos)+SUM(c.stab_segundos)+SUM(c.recap_segundos)+SUM(c.cabezote_segundos)+SUM(c.credito_segundos))as segundos_extra,
        (select count(capitulos_has_escenas.id) from capitulos_has_escenas where capitulos_has_escenas.id_capitulo=c.id) as total_escenas,
        (select id_user from usuario_has_capitulo where id_user=".$id_user." and id_capitulo=c.id) id_user
				 FROM capitulos c
				left join capitulos_has_escenas h on h.id_capitulo=c.id
				left join escena e on e.id=h.id_escena
        inner join rol_otros rol on rol.id=es.id_rol
        INNER JOIN capitulo_has_estados ON capitulo_has_estados.id_capitulo = c.id
				where c.id_produccion=".$id_produccion." ".$sql." group by c.id order by c.numero limit  0,".$limit.";");
     }else{
       $query=$this->db->query("SELECT c.*,e.id as id_escena,es.descripcion as estado, rol.descripcion as responsable,
        (sum(e.duracion_estimada_minutos) )as minutos_estimados, 
        (sum(e.duracion_estimada_segundos) )as segundos_estimados, 
        (sum(e.duracion_real_minutos) )as minutos, 
        (sum(e.duracion_real_segundos) )as segundos,
        (sum(e.tiempo_post_minutos) )as minutos_post, 
        (sum(e.tiempo_post_segundos) )as segundos_post,
        (SUM(c.flashback_minutos)+SUM(c.transiciones_minutos)+SUM(c.stab_minutos)+SUM(c.recap_minutos)+SUM(c.cabezote_minutos)+SUM(c.credito_minutos))as minutos_extra,
        (SUM(c.flashback_segundos)+SUM(c.transiciones_segundos)+SUM(c.stab_segundos)+SUM(c.recap_segundos)+SUM(c.cabezote_segundos)+SUM(c.credito_segundos))as segundos_extra,
        (select count(capitulos_has_escenas.id) from capitulos_has_escenas where capitulos_has_escenas.id_capitulo=c.id) as total_escenas
         FROM capitulos c
        left join capitulos_has_escenas h on h.id_capitulo=c.id
        left join escena e on e.id=h.id_escena
        inner join estados_capitulo_post es on es.id=c.id_estado
        inner join rol_otros rol on rol.id=es.id_rol
        where c.id_produccion=".$id_produccion." group by c.id order by c.numero limit  0,".$limit.";");
     } 
      if ($query->num_rows>0) {
        return $query->result_array();
      }else{
         return false;
      }
    }*/

    public function capitulos_escenas($id_produccion,$id_user='',$limit=40,$sql=""){
    if($id_user){
      $query=$this->db->query("SELECT DISTINCT(c.id) AS id_base, c.*,e.id as id_escena,
        (SELECT group_concat(capitulo_has_estados.id_estado,'_',estados_capitulo_post.descripcion SEPARATOR ',') FROM capitulo_has_estados
        INNER JOIN estados_capitulo_post ON estados_capitulo_post.id = capitulo_has_estados.id_estado
        WHERE id_capitulo = c.id AND activo = 1) AS estado,
        (SELECT group_concat(capitulo_has_estados.id_estado SEPARATOR ',') FROM capitulo_has_estados
        INNER JOIN estados_capitulo_post ON estados_capitulo_post.id = capitulo_has_estados.id_estado
        WHERE id_capitulo = c.id AND activo = 1) AS id_estado,
        (SELECT group_concat(rol_otros.descripcion SEPARATOR '-') 
        FROM capitulo_has_estados 
        INNER JOIN estados_capitulo_post ON estados_capitulo_post.id = capitulo_has_estados.id_estado
        INNER JOIN rol_otros on rol_otros.id=estados_capitulo_post.id_rol
         WHERE id_capitulo = c.id AND activo = 1) AS responsable,
        (sum(e.duracion_estimada_minutos) )as minutos_estimados, (sum(e.duracion_estimada_segundos) )as segundos_estimados, 
        (sum(e.duracion_real_minutos) )as minutos, (sum(e.duracion_real_segundos) )as segundos,
        (sum(h.tiempo_post_minutos) )as minutos_post, 
        (sum(h.tiempo_post_segundos) )as segundos_post,
        (sum(h.tiempo_post_cuadros) )as cuadros,
        (SUM(c.flashback_minutos)+SUM(c.transiciones_minutos)+SUM(c.stab_minutos)+SUM(c.recap_minutos)+SUM(c.cabezote_minutos)+SUM(c.credito_minutos))as minutos_extra,
        (SUM(c.flashback_segundos)+SUM(c.transiciones_segundos)+SUM(c.stab_segundos)+SUM(c.recap_segundos)+SUM(c.cabezote_segundos)+SUM(c.credito_segundos))as segundos_extra,
        (SUM(c.flashback_cuadros)+SUM(c.transiciones_cuadros)+SUM(c.stab_cuadros)+SUM(c.recap_cuadros)+SUM(c.cabezote_cuadros)+SUM(c.credito_cuadros))as cuadros_extra,
        (select count(capitulos_has_escenas.id) from capitulos_has_escenas where capitulos_has_escenas.id_capitulo=c.id) as total_escenas,
        (select id_user from usuario_has_capitulo where id_user=".$id_user." and id_capitulo=c.id) id_user
         FROM capitulos c
        left join capitulos_has_escenas h on h.id_capitulo=c.id
        left join escena e on e.id=h.id_escena
        inner join estados_capitulo_post es on es.id=c.id_estado
        inner join rol_otros rol on rol.id=es.id_rol
        where c.id_produccion=".$id_produccion." ".$sql." group by c.id order by c.numero limit  0,".$limit.";");
     }else{
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
        where c.id_produccion=".$id_produccion." group by c.id order by c.numero limit  0,".$limit.";");
     } 
      if ($query->num_rows>0) {
        return $query->result_array();
      }else{
         return false;
      }
    }

    public function total_capitulos_prod($id_produccion,$sql=""){
      $query=$this->db->query("SELECT * FROM capitulos c 
        INNER JOIN capitulo_has_estados ON capitulo_has_estados.id_capitulo = c.id
        where id_produccion=".$id_produccion." ".$sql." group by capitulo_has_estados.id_capitulo; ");
        return $query->num_rows();
    } 

       public function capitulos_escenas2($id_produccion){

       $query=$this->db->query("SELECT c.*,
        (SELECT group_concat(capitulo_has_estados.id_estado,'_',estados_capitulo_post.descripcion SEPARATOR ',') FROM capitulo_has_estados
        INNER JOIN estados_capitulo_post ON estados_capitulo_post.id = capitulo_has_estados.id_estado
        WHERE id_capitulo = c.id AND activo = 1) AS estado,e.id as id_escena, rol.descripcion as responsable,
        (sum(e.duracion_estimada_minutos) )as minutos_estimados, 
        (sum(e.duracion_estimada_segundos) )as segundos_estimados, 
        (sum(e.duracion_real_minutos) )as minutos, 
        (sum(e.duracion_real_segundos) )as segundos,
        (sum(h.tiempo_post_minutos) )as minutos_post, 
        (sum(h.tiempo_post_segundos) )as segundos_post,
        (sum(h.tiempo_post_cuadros) )as cuadros,
        (SUM(c.flashback_minutos)+SUM(c.transiciones_minutos)+SUM(c.stab_minutos)+SUM(c.recap_minutos)+SUM(c.cabezote_minutos)+SUM(c.credito_minutos))as minutos_extra,
        (SUM(c.flashback_segundos)+SUM(c.transiciones_segundos)+SUM(c.stab_segundos)+SUM(c.recap_segundos)+SUM(c.cabezote_segundos)+SUM(c.credito_segundos))as segundos_extra,
        (select count(capitulos_has_escenas.id) from capitulos_has_escenas where capitulos_has_escenas.id_capitulo=c.id) as total_escenas
         FROM capitulos c
        left join capitulos_has_escenas h on h.id_capitulo=c.id
        left join escena e on e.id=h.id_escena
        inner join estados_capitulo_post es on es.id=c.id_estado
        inner join rol_otros rol on rol.id=es.id_rol
        where c.id_produccion=".$id_produccion." group by c.id order by c.numero");
      if ($query->num_rows>0) {
        return $query->result_array();
      }else{
         return false;
      }
    }



   public function capitulos_escenas_prod($id_produccion){
      $query=$this->db->query("SELECT sum(e.duracion_real_minutos) as minutos, sum(e.duracion_real_segundos) as segundos,count(e.id) as total_escenas
			FROM capitulos c
			left join capitulos_has_escenas h on h.id_capitulo=c.id
			left join escena e on e.id=h.id_escena
			where c.id_produccion=".$id_produccion." and (e.estado=1 or e.estado=2 or e.estado=12 or e.estado=14);");
      if ($query->num_rows>0) {
        return $query->result();
      }else{
         return false;
      }	
    }  


   public function capitulos_escenas_post($id_produccion){
      $query=$this->db->query("SELECT sum(h.tiempo_post_minutos) as minutos, sum(h.tiempo_post_segundos) as segundos,sum(h.tiempo_post_cuadros) as cuadros,
          count(h.id_escena) as total_escenas
          FROM capitulos c
          left join capitulos_has_escenas h on h.id_capitulo=c.id
          where c.id_produccion=".$id_produccion." and h.tiempo_post_minutos!='';");
      if ($query->num_rows>0) {
        return $query->result();
      }else{
         return false;
      }	
    }    

   public function escenas_id_capitulo($id_capitulo,$limit=0){
      $query=$this->db->query("SELECT h.id id_capitulo_escena,h.*, e.id as id_escena,e.numero_escena,e.duracion_estimada_minutos,
        e.duracion_estimada_segundos,e.duracion_real_minutos,e.duracion_real_segundos,
        h.tiempo_post_minutos,h.tiempo_post_segundos,h.tiempo_post_cuadros,
        l.id as id_libreto,l.numero,es.descripcion
        FROM capitulos_has_escenas h
        inner join escena e on e.id=h.id_escena
        inner join estados es on es.id=e.estado
        inner join produccion_has_capitulos l on l.id=e.id_capitulo
        where h.id_capitulo=".$id_capitulo." 
        ORDER BY l.numero,CAST(SUBSTRING_INDEX(e.numero_escena ,'.', 1 ) AS UNSIGNED) ASC,
        (CASE INSTR(e.numero_escena , '.') 
        WHEN 0 THEN 0
        ELSE CAST(SUBSTRING_INDEX(e.numero_escena ,'.', -1 ) AS UNSIGNED) END) ASC
        limit  ".$limit.",40;");
      if ($query->num_rows>0) {
        return $query->result_array();
      }else{
         return false;
      } 
    }

    public function escenas_id_capitulo_total($id_capitulo){
      $query=$this->db->query("SELECT h.id id_capitulo_escena,h.*, e.id as id_escena,e.numero_escena,e.duracion_estimada_minutos,
        e.duracion_estimada_segundos,e.duracion_real_minutos,e.duracion_real_segundos,
        h.tiempo_post_minutos,h.tiempo_post_segundos,h.tiempo_post_cuadros,
        l.id as id_libreto,l.numero,es.descripcion
        FROM capitulos_has_escenas h
        inner join escena e on e.id=h.id_escena
        inner join estados es on es.id=e.estado
        inner join produccion_has_capitulos l on l.id=e.id_capitulo
        where h.id_capitulo=".$id_capitulo." 
        ORDER BY l.numero,CAST(SUBSTRING_INDEX(e.numero_escena ,'.', 1 ) AS UNSIGNED) ASC,
        (CASE INSTR(e.numero_escena , '.') 
        WHEN 0 THEN 0
        ELSE CAST(SUBSTRING_INDEX(e.numero_escena ,'.', -1 ) AS UNSIGNED) END) ASC");
      if ($query->num_rows>0) {
        return $query->result_array();
      }else{
         return false;
      } 
    } 

    public function total_escenas_id_capitulo($id_capitulo){
      $query=$this->db->query("SELECT h.id id_capitulo_escena,h.*, e.id as id_escena,e.numero_escena,e.duracion_estimada_minutos,
        e.duracion_estimada_segundos,e.duracion_real_minutos,e.duracion_real_segundos,
        h.tiempo_post_minutos,h.tiempo_post_segundos,l.id as id_libreto,l.numero,es.descripcion
        FROM capitulos_has_escenas h
        inner join escena e on e.id=h.id_escena
        inner join estados es on es.id=e.estado
        inner join produccion_has_capitulos l on l.id=e.id_capitulo
        where h.id_capitulo=".$id_capitulo." order by l.numero,e.numero_escena");
      return$query->num_rows();
    } 

    public function total_escenas_capitulo($id_capitulo){
      $query=$this->db->query("SELECT count(*) total FROM capitulos_has_escenas
            WHERE id_capitulo=".$id_capitulo);
      if ($query->num_rows>0) {
        return $query->result();
      }else{
         return false;
      } 
    } 

    public function escenas_id_capitulo2($id_capitulo){
      $query=$this->db->query("SELECT h.id id_capitulo_escena,h.*, e.id as id_escena,e.numero_escena,e.duracion_estimada_minutos,
        e.duracion_estimada_segundos,e.duracion_real_minutos,e.duracion_real_segundos,
        h.tiempo_post_minutos,h.tiempo_post_segundos,h.tiempo_post_cuadros,
        l.id as id_libreto,l.numero,es.descripcion
        FROM capitulos_has_escenas h
        inner join escena e on e.id=h.id_escena
        inner join estados es on es.id=e.estado
        inner join produccion_has_capitulos l on l.id=e.id_capitulo
        where h.id_capitulo=".$id_capitulo." 
        ORDER BY l.numero,CAST(SUBSTRING_INDEX(e.numero_escena ,'.', 1 ) AS UNSIGNED) ASC,
        (CASE INSTR(e.numero_escena , '.') 
        WHEN 0 THEN 0
        ELSE CAST(SUBSTRING_INDEX(e.numero_escena ,'.', -1 ) AS UNSIGNED) END) ASC");
      if ($query->num_rows>0) {
        return $query->result_array();
      }else{
         return false;
      } 
    } 


    public function escenas_id_capitulo_tiempo_real($id_capitulo){
      $query=$this->db->query("SELECT sum(e.duracion_real_minutos) as minutos, sum(e.duracion_real_segundos) as segundos,count(e.id) as total_escenas
        FROM capitulos_has_escenas h
        inner join escena e on e.id=h.id_escena
        where h.id_capitulo=".$id_capitulo." and (e.estado=1 or e.estado=2 or e.estado=12 or e.estado=14);");
      if ($query->num_rows>0) {
        return $query->result();
      }else{
         return false;
      } 
    } 

    public function escenas_id_capitulo_tiempo_post($id_capitulo){
      $query=$this->db->query("SELECT sum(h.tiempo_post_minutos) as minutos, sum(h.tiempo_post_segundos) as segundos,
        sum(h.tiempo_post_cuadros) as cuadros,
        count(h.id_escena) as total_escenas
        FROM capitulos_has_escenas h
        where h.id_capitulo=".$id_capitulo." and (h.tiempo_post_minutos+h.tiempo_post_segundos)<>0");
      if ($query->num_rows>0) {
        return $query->result();
      }else{
         return false;
      } 
    } 

    public function escenas_id_capitulo_tiempo_estimado($id_capitulo){
      $query=$this->db->query("SELECT sum(e.duracion_estimada_minutos) as minutos, sum(e.duracion_estimada_segundos) as segundos,count(e.id) as total_escenas
          FROM capitulos_has_escenas h
          inner join escena e on e.id=h.id_escena
          where h.id_capitulo=".$id_capitulo);
      if ($query->num_rows>0) {
        return $query->result();
      }else{
         return false;
      } 
    } 

     public function escena_id_libreto($id_capitulo){
      $query=$this->db->query("SELECT * FROM escena WHERE id_capitulo = ".$id_capitulo."
        ORDER BY CAST(SUBSTRING_INDEX(numero_escena,'.', 1 ) AS UNSIGNED) ASC,
      (CASE INSTR(numero_escena, '.') 
      WHEN 0 THEN 0
      ELSE CAST(SUBSTRING_INDEX(numero_escena,'.', -1 ) AS UNSIGNED) END) ASC");
      if ($query->num_rows>0) {
        return $query->result_array();
      }else{
         return false;
      } 
    } 

  public function capitulos_has_escenas_id_escena($id_escena,$id_capitulo){
      $this->db->where('id_capitulo',$id_capitulo);
      $this->db->where('id_escena',$id_escena);
      $query=$this->db->get('capitulos_has_escenas');
     if ($query->num_rows>0){
      return $query->result();
     } else {
      return false;
     }
  }

  public function update_tiempo_post($datos){
      $this->db->where('id_escena',$datos['id_escena']);
      $this->db->where('id_capitulo',$datos['id_capitulo']);
      $data=array(
        'tiempo_post_minutos'=> $datos['tiempo_post_minutos'],
        'tiempo_post_segundos'=> $datos['tiempo_post_segundos'],
        'tiempo_post_cuadros'=> $datos['tiempo_post_cuadros']);
      return $this->db->update('capitulos_has_escenas',$data);
  }

  public function update_tiempo_post2($datos){
      $this->db->where('id_escena',$datos['id_escena']);
      $data=array(
        'tiempo_post_minutos'=> $datos['tiempo_post_minutos'],
        'tiempo_post_segundos'=> $datos['tiempo_post_segundos']);
      return $this->db->update('capitulos_has_escenas',$data);
  }


  public function descartivar_estado($id_capitulo,$estado_actual,$fecha,$id_user){
    $this->db->where('id_capitulo',$id_capitulo);
    $this->db->where('id_estado',$estado_actual);
    $this->db->where('activo',1);
    $data=array(
        'activo'=> 0,
        'fecha'=> $fecha,
        'id_user'=> $id_user,
    );
    return $this->db->update('capitulo_has_estados',$data);
  }

  public function reverzar_estado($id_capitulo,$estado_actual,$fecha,$id_user,$rechazo=null){
    $this->db->where('id_capitulo',$id_capitulo);
    $this->db->where('id_estado',$estado_actual);
    $this->db->where('activo',1);
    $data=array(
        'activo'=> 3,
        'fecha'=> $fecha,
        'id_user'=> $id_user,
        'rechazo'=> $rechazo,
    );
    return $this->db->update('capitulo_has_estados',$data);
  }

  public function reactivar_estado($id_capitulo,$estado_actual){
    $this->db->where('id_capitulo',$id_capitulo);
    $this->db->where('id_estado',$estado_actual+1);
    $this->db->delete('capitulo_has_estados');

    $this->db->where('id_capitulo',$id_capitulo);
    $this->db->where('id_estado',$estado_actual);
    $data=array(
        'activo'=> 1,
        'id_estado'=> $estado_actual
    );
    return $this->db->update('capitulo_has_estados',$data);
  }

  public function reactivar_estado2($id_capitulo,$estado_actual,$estada_anterior){
    $this->db->where('id_capitulo',$id_capitulo);
    $this->db->where('id_estado',$estada_anterior);
    $this->db->delete('capitulo_has_estados');
    $this->db->where('id_capitulo',$id_capitulo);
    $this->db->where('id_estado',$estado_actual);
    $data=array(
        'activo'=> 1,
        'id_estado'=> $estado_actual
    );
    return $this->db->update('capitulo_has_estados',$data);
  }

  public function user_produccion_rol($id_produccion,$id_rol){
      $query=$this->db->query("SELECT * from user_has_rol_otros h
          inner join user u on u.id=h.id_user
          inner join usuario_has_produccion p on p.id_usuario=u.id and p.id_produccion=".$id_produccion."
          where id_rol_otros=".$id_rol);
      if ($query->num_rows>0) {
        return $query->result_array();
      }else{
         return false;
      } 
    } 

  public function user_produccion_rol2($id_produccion){
      $query=$this->db->query("SELECT * from usuario_has_produccion h
          inner join user u on u.id=h.id_usuario
          where h.id_produccion=".$id_produccion." and h.id_rol=11;");
      if ($query->num_rows>0) {
        return $query->result_array();
      }else{
         return false;
      } 
    } 

  public function update_capitulo($datos){
    $this->db->where('id',$datos['id_capitulo']);
    $data=array(
      'id_estado'=> $datos['id_estado'],
      'fecha_entregada'=> $datos['fecha_entregada']
    );
    return $this->db->update('capitulos',$data);
  }

  public function insertar_estado_capitulo($data){
    return $this->db->insert('capitulo_has_estados',$data);
  }

  public function update_capitulo_user($datos){
      $this->db->where('id_capitulo',$datos['id_capitulo']);
      $this->db->where('estado',1);
      $this->db->where('id_estado_capitulo',$datos['id_estado_capitulo']);
      $data=array(
        'fecha_aprobacion'=> $datos['fecha_aprobacion'],
        'estado'=>0,
        'id_rol'=>$datos['id_rol'],);
    return $this->db->update('usuario_has_capitulo',$data);
  }

  public function user_estado_cap($id_produccion,$id_rol){
      $query=$this->db->query("SELECT  h.*,p.descripcion as estatus,r.descripcion as responsable from estados_capitulo_post p
          inner join rol_otros r on r.id=p.id_rol
          inner join user_has_rol_otros h on h.id_rol_otros=r.id
          inner join usuario_has_produccion u on u.id_usuario=h.id_user and u.id_produccion=".$id_produccion."
          where p.id=".$id_rol);
      if ($query->num_rows>0) {
        return $query->result_array();
      }else{
         return false;
      } 
  }


  public function rol_estado_cap($id){
      $this->db->where('id',$id);
      $query=$this->db->get('estados_capitulo_post');
     if ($query->num_rows>0){
      return $query->result();
     } else {
      return false;
     }
  }

  public function vitacora($id_capitulo){
      $query=$this->db->query("SELECT estados_capitulo_post.descripcion AS estado, DATE_FORMAT(capitulo_has_estados.fecha, '%d-%b-%Y') AS fecha, rol_otros.descripcion AS aprobado, 
                              CONCAT(user.nombre,' ',user.apellido) AS usuario, (CASE capitulo_has_estados.activo WHEN 0 THEN '(APROBADO)' WHEN 3 THEN '(RECHAZADO)' END) AS estatus,
                              activo,rechazo
                              FROM capitulo_has_estados
                              INNER JOIN estados_capitulo_post ON estados_capitulo_post.id = capitulo_has_estados.id_estado
                              INNER JOIN rol_otros ON estados_capitulo_post.id_rol = rol_otros.id
                              INNER JOIN user ON user.id = capitulo_has_estados.id_user
                              WHERE id_capitulo=".$id_capitulo." AND (capitulo_has_estados.activo =0 OR capitulo_has_estados.activo = 3)
                              ORDER BY capitulo_has_estados.fecha;");
      if ($query->num_rows>0) {
        return $query->result_array();
      }else{
         return false;
      } 
    }

  
  public function user_capitulo($id_capitulo,$id_user){
      $this->db->where('id_capitulo',$id_capitulo);
      $this->db->where('id_user',$id_user);
      $this->db->where('estado',1);
      $query=$this->db->get('usuario_has_capitulo');
     if ($query->num_rows>0){
      return $query->result();
     } else {
      return false;
     }
  }

  public function delete_asignacion($id_capitulo,$estado){
      $this->db->where('id_capitulo',$id_capitulo);
      $this->db->where('id_estado_capitulo',$estado);
      $this->db->where('estado',1);
    return $this->db->delete('usuario_has_capitulo');
  }

  public function delete_asignacion2($id_capitulo,$id_estado){
      $this->db->where('id_capitulo',$id_capitulo);
      $this->db->where('estado',1);
      $this->db->where('id_estado',$id_estado);
    return $this->db->delete('usuario_has_capitulo');
  }

    public function update_capitulo_user2($datos){
      $this->db->where('id_estado_capitulo',$datos['id_estado_capitulo']);
      $this->db->where('id_capitulo',$datos['id_capitulo']);
      $data=array(
        'estado'=>1);
    return $this->db->update('usuario_has_capitulo',$data);
  }

  // public function eliminar_capitulo($id_capitulo){
  //   $this->db->where('id',$id_capitulo);
  //   return $this->db->delete('capitulos');
  // }

  public function eliminar_capitulo($id_capitulo){
    $this->db->where('id_capitulo',$id_capitulo);
    $data=array(
        'activo'=>0
    );
    $this->db->update('capitulo_has_estados',$data);

    $this->db->where('id_capitulo',$id_capitulo);
    $data=array(
        'id_capitulo'=>$id_capitulo,
        'id_estado'=>16,
        'activo'=>1
    );
    return $this->db->insert('capitulo_has_estados',$data);
  }

  public function capitulos_user($tipo_user, $id_produccion){
      $query=$this->db->query("SELECT COUNT(capitulos.id) as total FROM capitulos
                        INNER JOIN capitulo_has_estados ON capitulo_has_estados.id_capitulo = capitulos.id
                        INNER JOIN estados_capitulo_post ON estados_capitulo_post.id = capitulo_has_estados.id_estado
                        WHERE capitulo_has_estados.activo = 1 and capitulos.id_produccion = ".$id_produccion."
                        AND  estados_capitulo_post.id_rol = ".$tipo_user.";");
     if ($query->num_rows>0){
      return $query->result();
     } else {
      return false;
     }
  } 

 
 public function update_detalle_capitulo($datos){
      $this->db->where('id',$datos['id_capitulo']);
      $data=array(
        $datos['detalle']=>$datos['valor']);
    return $this->db->update('capitulos',$data);
  }

  
  public function eliminar_escena_capitulo($id_escena_capitulo){
      $this->db->where('id',$id_escena_capitulo);
    return $this->db->delete('capitulos_has_escenas');
  }

  public function eliminar_escena_capitulo_id_escena($id_escena){
      $this->db->where('id_escena',$id_escena);
    return $this->db->delete('capitulos_has_escenas');
  }

  public function validar_escena_archivo($datos){
      $query=$this->db->query("SELECT e.id id_escena,e.* from produccion_has_capitulos h 
        inner join escena e on e.id_capitulo=h.id
        where h.numero=".$datos['libreto']." and id_produccion=".$datos['id_produccion']." 
        and e.numero_escena = '".$datos['escena']."'");
      if ($query->num_rows>0) {
        return $query->result();
      }else{
         return false;
      } 
    }

 public function escena_capitulo($id_escena,$id_capitulo){
      $this->db->where('id_escena',$id_escena);
      $this->db->where('id_capitulo',$id_capitulo);
      $query=$this->db->get('capitulos_has_escenas');
     if ($query->num_rows>0){
       return $query->result();
     } else {
      return false;
     }
  }

 public function capitulos_has_escenas($id_produccion){
      $query=$this->db->query("SELECT c.numero numero_capitulo,c.*,h.*,e.numero_escena,
            h.tiempo_post_minutos,h.tiempo_post_segundos,h.tiempo_post_cuadros,
             p.numero libreto, e.duracion_estimada_minutos,e.duracion_estimada_segundos, e.duracion_real_minutos,
            e.duracion_real_segundos, 
            (SELECT group_concat(capitulo_has_estados.id_estado,'_',estados_capitulo_post.descripcion SEPARATOR ',') 
            FROM capitulo_has_estados 
            INNER JOIN estados_capitulo_post ON estados_capitulo_post.id = capitulo_has_estados.id_estado 
            WHERE id_capitulo = c.id AND activo = 1) AS estado, 
            c.fecha_entregada, (SELECT count(escena.id) from capitulos inner join capitulos_has_escenas 
            on capitulos_has_escenas.id_capitulo=capitulos.id left join escena on escena.id=capitulos_has_escenas.id_escena
             where capitulos.id=c.id) total_escenas, 
            (SELECT count(escena.id) from capitulos 
            inner join capitulos_has_escenas on capitulos_has_escenas.id_capitulo=capitulos.id 
            left join escena on escena.id=capitulos_has_escenas.id_escena where capitulos.id=c.id and escena.id_tipo_locacion=1) 
            total_locacion, 
            (SELECT count(escena.id) 
            from capitulos 
            inner join capitulos_has_escenas on capitulos_has_escenas.id_capitulo=capitulos.id 
            left join escena on escena.id=capitulos_has_escenas.id_escena 
            where capitulos.id=c.id and escena.id_tipo_locacion=2) total_estudio, 
            (SELECT sum(escena.duracion_estimada_minutos) 
            from capitulos inner join capitulos_has_escenas on capitulos_has_escenas.id_capitulo=capitulos.id 
            left join escena on escena.id=capitulos_has_escenas.id_escena where capitulos.id=c.id) total_est_minutos, 
            (SELECT sum(escena.duracion_estimada_segundos) from capitulos 
            inner join capitulos_has_escenas on capitulos_has_escenas.id_capitulo=capitulos.id 
            left join escena on escena.id=capitulos_has_escenas.id_escena where capitulos.id=c.id) total_est_seg,
             (SELECT sum(escena.duracion_real_minutos) 
            from capitulos inner join capitulos_has_escenas on capitulos_has_escenas.id_capitulo=capitulos.id 
            left join escena on escena.id=capitulos_has_escenas.id_escena where capitulos.id=c.id) total_real_minutos, 
            (SELECT sum(escena.duracion_real_segundos) from capitulos 
            inner join capitulos_has_escenas on capitulos_has_escenas.id_capitulo=capitulos.id 
            left join escena on escena.id=capitulos_has_escenas.id_escena where capitulos.id=c.id) total_real_seg, 
            (SELECT sum(capitulos_has_escenas.tiempo_post_minutos) from capitulos inner join capitulos_has_escenas 
            on capitulos_has_escenas.id_capitulo=capitulos.id 
            left join escena on escena.id=capitulos_has_escenas.id_escena where capitulos.id=c.id) total_post_minutos, 
            (SELECT sum(capitulos_has_escenas.tiempo_post_segundos) from capitulos 
            inner join capitulos_has_escenas on capitulos_has_escenas.id_capitulo=capitulos.id
            left join escena on escena.id=capitulos_has_escenas.id_escena 
            where capitulos.id=c.id) total_post_seg,
            (SELECT sum(capitulos_has_escenas.tiempo_post_cuadros) from capitulos 
            inner join capitulos_has_escenas on capitulos_has_escenas.id_capitulo=capitulos.id
            left join escena on escena.id=capitulos_has_escenas.id_escena 
            where capitulos.id=c.id) total_post_cuadros
            from capitulos c 
            left outer join capitulos_has_escenas h on h.id_capitulo=c.id 
            left outer join escena e on e.id=h.id_escena 
            left outer join produccion_has_capitulos p on p.id=e.id_capitulo 
            where c.id_produccion=".$id_produccion." and (SELECT group_concat(capitulo_has_estados.id_estado,'_',estados_capitulo_post.descripcion SEPARATOR ',') 
            FROM capitulo_has_estados 
            INNER JOIN estados_capitulo_post ON estados_capitulo_post.id = capitulo_has_estados.id_estado 
            WHERE id_capitulo = c.id AND activo = 1 and capitulo_has_estados.id_estado<>2)  is not null
            ORDER BY c.numero,p.numero,CAST(SUBSTRING_INDEX(e.numero_escena ,'.', 1 ) AS UNSIGNED) ASC,
            (CASE INSTR(e.numero_escena , '.') 
            WHEN 0 THEN 0
            ELSE CAST(SUBSTRING_INDEX(e.numero_escena ,'.', -1 ) AS UNSIGNED) END) ASC");
      if ($query->num_rows>0) {
        return $query->result_array();
      }else{
         return false;
      } 
    }

    
   public function fecha_entrega_libreto($id_produccion,$numero_libreto){
      $this->db->where('id_produccion',$id_produccion);
      $this->db->where('numero',$numero_libreto);
      $query=$this->db->get('produccion_has_capitulos');
     if ($query->num_rows>0){
       return $query->result();
     } else {
      return false;
     }
  }

  public function detalles_capitulo($id_capitulo){
      $this->db->where('id',$id_capitulo);
      $query=$this->db->get('capitulos');
     if ($query->num_rows>0){
       return $query->result();
     } else {
      return false;
     }
  }


  public function libretos_capitulo($id_capitulo){
    $query=$this->db->query("SELECT l.numero
        FROM capitulos_has_escenas h inner join escena e on e.id=h.id_escena 
        inner join estados es on es.id=e.estado 
        inner join produccion_has_capitulos l on l.id=e.id_capitulo 
        where h.id_capitulo=".$id_capitulo." group by l.numero order by l.numero;");
    if ($query->num_rows>0) {
      return $query->result_array();
    }else{
       return false;
    } 
  }

  /*BUSCAR CAPITULO POR NUMERO*/
  public function capitulo_by_numero($idproduccion,$numero){
    $query=$this->db->query("SELECT *,
      (SELECT group_concat(capitulo_has_estados.id_estado,'_',estados_capitulo_post.descripcion SEPARATOR ',') FROM capitulo_has_estados
      INNER JOIN estados_capitulo_post ON estados_capitulo_post.id = capitulo_has_estados.id_estado
      WHERE id_capitulo = capitulos.id AND activo = 1) AS estado
      FROM capitulos WHERE capitulos.numero = ".$numero." AND capitulos.id_produccion = ".$idproduccion.";");
    if ($query->num_rows>0) {
      return $query->result_array();
    }else{
       return false;
    } 
  }
  /*FIN BUSCAR CAPITULO POR NUMERO*/

  public function lista_estados(){
    $query = $this->db->get('estados_capitulo_post');
    return $query->result();
  }

  public function update_capitulo_fecha_entrega($datos){
    $this->db->where('id',$datos['id_capitulo']);
    $data=array(
      'fecha_entrega'=> $datos['fecha_entrega'],
    );
    return $this->db->update('capitulos',$data);
  }

  
  public function contar_capitulos($numero,$id_produccion){
    $query=$this->db->query("SELECT COUNT(id) total FROM capitulos WHERE numero >".$numero." AND id_produccion = ".$id_produccion);
    if ($query->num_rows>0) {
      return $query->result();
    }else{
       return false;
    } 
  }

  public function contar_numero($numero,$id_produccion){
    $query=$this->db->query("SELECT * from capitulos c
        where c.numero='".$numero."' and c.id_produccion=".$id_produccion);
    if ($query->num_rows>0) {
      return $query->result();
    }else{
       return false;
    } 
  }

  public function tiempos_extras_capitulo($id_capitulo){
    $query=$this->db->query("SELECT credito_minutos credito,credito_segundos,flashback_minutos flashback,flashback_segundos,
      transiciones_minutos transiciones,transiciones_segundos,cortinillas_minutos cortinillas,cortinillas_segundos,
      cabezote_minutos cabezote,cabezote_segundos,recap_minutos recap,recap_segundos,stab_minutos stab,stab_segundos,
      despedida_minutos despedida, despedida_segundos,presentacion_minutos presentacion, presentacion_segundos,imagenes_archivos_minutos,imagenes_archivos_segundos
      from capitulos where id=".$id_capitulo);
    if ($query->num_rows>0) {
      return $query->result();
    }else{
       return false;
    } 
  }

  public function sum_tiempos_extras_capitulo($id_capitulo){
    $query=$this->db->query("SELECT *
 from capitulos where id=".$id_capitulo);
    if ($query->num_rows>0) {
      return $query->result();
    }else{
       return false;
    } 
  }

  public function update_capitulo_extras($id_capitulo,$data){
      $this->db->where('id',$id_capitulo);
    return $this->db->update('capitulos',$data);
  }

  public function validar_estado($id_capitulo,$id_estado){
    $query=$this->db->query("SELECT * from capitulo_has_estados 
      where id_capitulo=".$id_capitulo." and activo=1 and id_estado=".$id_estado);
    if ($query->num_rows>0) {
      return $query->result();
    }else{
       return false;
    } 
  }


public function validar_estado2($id_capitulo,$id_estado1,$id_estado2){
    $query=$this->db->query("SELECT * from capitulo_has_estados 
      where id_capitulo=".$id_capitulo." and activo=1 and (id_estado=".$id_estado1." or id_estado=".$id_estado2.")");
    if ($query->num_rows>0) {
      return $query->result();
    }else{
       return false;
    } 
  }

  public function ultimo_capitulo($id){
    $query=$this->db->query("SELECT * from capitulos where id_produccion=".$id." order by numero desc limit 1;");
    if ($query->num_rows>0) {
      return $query->result();
    }else{
       return false;
    } 
  }

  public function cantidad_capitulos_editados($idproduccion){
    $query=$this->db->query("SELECT COUNT(DISTINCT(capitulos.id)) AS total 
                              FROM capitulos 
                              INNER JOIN capitulo_has_estados ON capitulo_has_estados.id_capitulo = capitulos.id
                              WHERE id_produccion=".$idproduccion." AND capitulo_has_estados.id_estado=15 AND capitulo_has_estados.activo=1;");
    if ($query->num_rows>0) {
      return $query->result();
    }else{
       return false;
    } 
  }

  public function actualiza_fecha_entregada($idcapitulo,$data){
    $this->db->where('id',$idcapitulo);
    $this->db->update('capitulos',$data);
  } 

  public function valida_edicion_capitulo($idestado,$idrol){
    if($idestado==7 and $idrol==13){
       return true;
    }else{
        $this->db->where('id_rol',$idrol);
        $this->db->where('id',$idestado);
        $query=$this->db->get('estados_capitulo_post');
        if($query->num_rows>0) {
          return true;
        }else{
          return false;
        } 
    }    
  }


  public function escenas($id){
    $query=$this->db->query("SELECT escena.* from produccion_has_capitulos
        inner join escena on escena.id_capitulo=produccion_has_capitulos.id
        where produccion_has_capitulos.id_produccion=".$id);
    if ($query->num_rows>0) {
      return $query->result_array();
    }else{
       return false;
    } 
  }

  

}  