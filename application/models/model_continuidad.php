<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class model_continuidad extends CI_Model {



      public function produccion_user($id_user){
      $query=$this->db->query("SELECT p.* from produccion p
          inner join usuario_has_produccion h on h.id_produccion=p.id
          inner join unidad u on u.id_produccion=p.id
          where h.id_usuario=".$id_user." or u.id_script=".$id_user."
          group by p.id;");
      if ($query->num_rows>0) {
        return $query->result_array();
      }else{
         return false;
      }
    }
 
 
   /***Carga planes diarios de la produccion con el id*/  

      public function plan_diario_semana($id_produccion,$inicio_semana,$fin_semana){
      $query=$this->db->query("SELECT u.id as id_unidad,u.numero as numero,p.id as id_plan,p.fecha_inicio as fecha_unicio_plan, p.estado as estado  
            FROM  unidad u
            left join plan_diario p on p.id_unidad=u.id and p.estado<>1 and p.estado <>2 
            and p.fecha_inicio>='".$inicio_semana."' and p.fecha_inicio<='".$fin_semana."' 
            where u.id_produccion=".$id_produccion." order by u.numero,p.fecha_inicio asc;");
      if ($query->num_rows>0) {
        return $query->result_array();
      }else{
         return false;
      }
    }
 
     public function plan_diario_escena($id_plan,$id_escena){
      $query=$this->db->query("SELECT h.id as id_plan_escena,h.*,p.*,e.id_capitulo, e.numero_escena as numero_escena,e.duracion_estimada_minutos,
				e.duracion_estimada_segundos,e.duracion_real_minutos,e.duracion_real_segundos,c.numero as numero_capitulo, 
				e.dias_continuidad  as dia_cont,
				e.descripcion as descripcion_escena, e.guion, d.descripcion as dian_noche, i.descripcion as int_ext, t.tipo, e.libreto, e.dias_continuidad,
        e.estado as estado_escena, lo.nombre as locacion, se.nombre as sets 
				FROM plan_diario_has_escenas_has_unidades h
				inner join escena e on e.id=h.id_escena
				inner join produccion_has_capitulos c on c.id=e.id_capitulo
				INNER JOIN tipo_locacion t ON t.id=e.id_tipo_locacion
				INNER JOIN escenas_dias_noche d ON d.id=e.id_dia_noche
				INNER JOIN escena_interior_esterior i ON i.id=e.id_interior_esterior 
				inner join plan_diario p on p.id=h.id_plan_diario
        inner join locacion lo on lo.id=e.id_locacion
inner join sets se on se.id=e.id_set
				where h.id_plan_diario=".$id_plan." and h.id_escena=".$id_escena);
      if ($query->num_rows>0) {
        return $query->result();
      }else{
         return false;
      }
    } 

    public function personajes_escena($id_escena){
      $query=$this->db->query("(SELECT e.id,e.nombre,r.rol FROM escenas_has_elementos h
				inner join elemento e on e.id=h.id_elemento
				inner join rol_actores_elementos r on r.id=e.rol
				inner join categoria_elemento c on c.id=e.id_tipo_elemento and tipo='Personaje'
				where h.id_escena=".$id_escena." and r.id=1 order by r.id)
        UNION ALL
        (SELECT e.id,e.nombre,r.rol FROM escenas_has_elementos h
        inner join elemento e on e.id=h.id_elemento
        inner join rol_actores_elementos r on r.id=e.rol
        inner join categoria_elemento c on c.id=e.id_tipo_elemento and tipo='Personaje'
        where h.id_escena=".$id_escena." and r.id=3 order by r.id)
        UNION ALL
        (SELECT e.id,e.nombre,r.rol FROM escenas_has_elementos h
        inner join elemento e on e.id=h.id_elemento
        inner join rol_actores_elementos r on r.id=e.rol
        inner join categoria_elemento c on c.id=e.id_tipo_elemento and tipo='Personaje'
        where h.id_escena=".$id_escena." and r.id=2 order by r.id)
        UNION ALL
        (SELECT e.id,e.nombre,r.rol FROM escenas_has_elementos h
        inner join elemento e on e.id=h.id_elemento
        inner join rol_actores_elementos r on r.id=e.rol
        inner join categoria_elemento c on c.id=e.id_tipo_elemento and tipo='Personaje'
        where h.id_escena=".$id_escena." and r.id=4 order by r.id)");
      if ($query->num_rows>0) {
        return $query->result_array();
      }else{
         return false;
      }
    } 

     public function elementos_escena($id_escena){
      $query=$this->db->query("SELECT * FROM escenas_has_elementos h
        inner join elemento e on e.id=h.id_elemento
        inner join categoria_elemento c on c.id=e.id_tipo_elemento and tipo!='Personaje'
        where h.id_escena=".$id_escena);
      if ($query->num_rows>0) {
        return $query->result_array();
      }else{
         return false;
      }
    } 

     public function escena_has_elemento_idelemento($id_elemento){
       $this->db->where('id_elemento',$id_elemento); 
       $query=$this->db->get('escenas_has_elementos');
         if ($query->num_rows>0) {
         return $query->result_array();
         } else {
          return false;
         }
      }

       public function escena_has_elemento($id_elemento,$id_escena){
       $this->db->where('id_elemento',$id_elemento); 
       $this->db->where('id_escena',$id_escena); 
       $query=$this->db->get('escenas_has_elementos');
         if ($query->num_rows>0) {
         return $query->result();
         } else {
          return false;
         }
      }

   public function personajes_cont($id_produccion,$cont){
      $query=$this->db->query('SELECT e.nombre,r.rol,es.id,e.id as id_elemento from produccion_has_capitulos hc
			inner join escena es on es.id_capitulo=hc.id and es.dias_continuidad="'.$cont.'" 
			inner join escenas_has_elementos h on h.id_escena=es.id
			inner join elemento e on e.id=h.id_elemento
			inner join rol_actores_elementos r on r.id=e.rol
			inner join categoria_elemento c on c.id=e.id_tipo_elemento and tipo="Personaje" 
			where hc.id_produccion='.$id_produccion.' group by e.id;');
      if ($query->num_rows>0) {
        return $query->result_array();
      }else{
         return false;
      }
    }

   public function personajes_cont_rol($id_produccion,$cont,$rol,$limit,$like){
      $query=$this->db->query('SELECT e.nombre,r.rol,es.id,e.id as id_elemento from produccion_has_capitulos hc
      inner join escena es on es.id_capitulo=hc.id and es.dias_continuidad="'.$cont.'" 
      inner join escenas_has_elementos h on h.id_escena=es.id
      inner join elemento e on e.id=h.id_elemento
      inner join rol_actores_elementos r on r.id=e.rol
      inner join categoria_elemento c on c.id=e.id_tipo_elemento and tipo="Personaje" and r.id='.$rol.'
      and e.nombre like "%'.$like.'%"
      where hc.id_produccion='.$id_produccion.' group by e.id limit '.$limit);
      if ($query->num_rows>0) {
        return $query->result_array();
      }else{
         return false;
      }
    }

    public function personajes_cont_rol_total($id_produccion,$cont,$rol,$like){
      $query=$this->db->query('SELECT e.nombre,r.rol,es.id,e.id as id_elemento from produccion_has_capitulos hc
      inner join escena es on es.id_capitulo=hc.id and es.dias_continuidad="'.$cont.'" 
      inner join escenas_has_elementos h on h.id_escena=es.id
      inner join elemento e on e.id=h.id_elemento
      inner join rol_actores_elementos r on r.id=e.rol
      inner join categoria_elemento c on c.id=e.id_tipo_elemento and tipo="Personaje" and r.id='.$rol.'
      and e.nombre like "%'.$like.'%"
      where hc.id_produccion='.$id_produccion.' group by e.id');
      if ($query->num_rows>0) {
        return $query->num_rows();
      }else{
         return false;
      }
    }
    public function personajes_id_elemento($id_produccion,$id_elemento){
      $query=$this->db->query('SELECT e.nombre,r.rol,es.id,e.id as id_elemento from produccion_has_capitulos hc
      inner join escena es on es.id_capitulo=hc.id 
      inner join escenas_has_elementos h on h.id_escena=es.id
      inner join elemento e on e.id=h.id_elemento
      inner join rol_actores_elementos r on r.id=e.rol
      inner join categoria_elemento c on c.id=e.id_tipo_elemento and tipo="Personaje"
      where hc.id_produccion='.$id_produccion.' and e.id='.$id_elemento.' group by e.id;');
      if ($query->num_rows>0) {
        return $query->result();
      }else{
         return false;
      }
    }

 public function numero_escenas($id_libreto){
   $this->db->where('id_capitulo',$id_libreto); 
   $query=$this->db->get('escena');
     if ($query->num_rows>0) {
     return $query->result_array();
     } else {
      return false;
     }
  }

     public function personajes_escena_id($id_escena){
      $query=$this->db->query('SELECT es.dias_continuidad,e.* from escena es
								inner join escenas_has_elementos h on h.id_escena=es.id
								inner join elemento e on e.id=h.id_elemento
								inner join rol_actores_elementos r on r.id=e.rol
								inner join categoria_elemento c on c.id=e.id_tipo_elemento and tipo="Personaje"
								where es.id='.$id_escena);
      if ($query->num_rows>0) {
        return $query->result_array();
      }else{
         return false;
      }
    }

  public function escena_idelemento($id,$cont){
      $query=$this->db->query('SELECT e.id,e.id_capitulo from escenas_has_elementos h
          inner join escena e on e.id=h.id_escena and e.dias_continuidad="'.$cont.'"
          where h.id_elemento='.$id);
      if ($query->num_rows>0) {
        return $query->result_array();
      }else{
         return false;
      }
    }

    
  public function crear_continuidad($datos){
    $data=array(
        'id_escena_has_elemento'     => $datos['id_escena_has_elemento'],
        'id_elemento'     => $datos['id_elemento'],
        'dia_continuidad'     => $datos['dia_continuidad'],
        'id_tipo'     => $datos['id_tipo'],
        'imagen'     => $datos['imagen'],
        'nota'     => $datos['nota'],
    );
    return $this->db->insert('continuidad',$data);
  }

    public function crear_continuidad2($datos){
    $data=array(
        'id_elemento'     => $datos['id_elemento'],
        'dia_continuidad'     => $datos['dia_continuidad'],
        'id_tipo'     => $datos['id_tipo'],
        'imagen'     => $datos['imagen'],
        'nota'     => $datos['nota'],
    );
    return $this->db->insert('continuidad',$data);
  }


  public function imagen_continuidad($id_elemento,$id_escena){
      $query=$this->db->query('SELECT * FROM escenas_has_elementos h
            inner join continuidad c on c.id_escena_has_elemento=h.id
            where h.id_elemento='.$id_elemento.' and h.id_escena='.$id_escena.' limit 1');
      if ($query->num_rows>0) {
        return $query->result();
      }else{
         return false;
      }
    }

     public function imagen_continuidad2($id_elemento,$dia_continuidad){
      $query=$this->db->query('SELECT * FROM continuidad
            where id_elemento='.$id_elemento.' and dia_continuidad="'.$dia_continuidad.'" limit 1');
      if ($query->num_rows>0) {
        return $query->result();
      }else{
         return false;
      }
    }

  public function semana_id($id_produccion,$id_semana){
      $query=$this->db->query("select * from semanas_produccion
          where id=".$id_semana." and id_produccion=".$id_produccion);
      if ($query->num_rows>0) {
        return $query->result();
      }else{
         return false;
      }
    }
   public function semana_actual($id_produccion,$fecha){
      $query=$this->db->query("SELECT * from semanas_produccion
      where fecha_inicio_semana<='".$fecha."' and fecha_fin_semana>='".$fecha."'
      and id_produccion=".$id_produccion);
      if ($query->num_rows>0) {
        return $query->result();
      }else{
         return false;
      }
    }  


   public function elemento_id($id_elemento){
       $this->db->where('id',$id_elemento);
        $query=$this->db->get('elemento');
       if ($query->num_rows>0) {
       return $query->result();
       } else {
        return false;
       }
    }  

  public function personaje_cont_idescena($id_elemento,$id_escena,$dia_cont){
      $query=$this->db->query("SELECT c.id as id_cont,c.*,e.nombre FROM  continuidad c
            inner join elemento e on e.id=id_elemento
            where c.id_elemento=".$id_elemento." and c.id_escena=".$id_escena." AND c.dia_continuidad=".$dia_cont);
      if ($query->num_rows>0) {
        return $query->result();
      }else{
         return false;
      }
    }    
  public function personajes_cont_all($id_elemento,$cont){
   $query=$this->db->query("SELECT c.id as id_continuidad,c.*,c_t.tipo,e.id as id_escena,e.dias_continuidad as dia_continuidad,el.nombre,el.id as id_elemento, c.id_tipo  
        FROM escenas_has_elementos h
        inner join  continuidad c on c.id_escena_has_elemento=h.id
        left outer join continuidad_tipo_imagen c_t on c_t.id=c.id_tipo
        inner join escena e on e.id=h.id_escena
        inner join elemento el on el.id=h.id_elemento
        where h.id_elemento=".$id_elemento." and e.dias_continuidad='".$cont."'");
      if ($query->num_rows>0) {
        return $query->result_array();
      }else{
         return false;
      }
  }

  public function personajes_cont_all_tipo($id_elemento,$cont,$tipo){
   $query=$this->db->query("SELECT c.id as id_continuidad,c.*,c_t.tipo,e.id as id_escena,e.dias_continuidad as dia_continuidad,el.nombre,el.id as id_elemento, c.id_tipo  
        FROM escenas_has_elementos h
        inner join  continuidad c on c.id_escena_has_elemento=h.id
        left outer join continuidad_tipo_imagen c_t on c_t.id=c.id_tipo
        inner join escena e on e.id=h.id_escena
        inner join elemento el on el.id=h.id_elemento
        where h.id_elemento=".$id_elemento." and e.dias_continuidad='".$cont."' and c.id_tipo=".$tipo);
      if ($query->num_rows>0) {
        return $query->result_array();
      }else{
         return false;
      }
  }
  

  public function personajes_cont_all_elemento($id_elemento){
   $query=$this->db->query("SELECT c.*,c_t.tipo,e.id as id_escena,e.dias_continuidad as dia_continuidad,el.nombre,el.id as id_elemento, c.id_tipo  
        FROM escenas_has_elementos h
        inner join  continuidad c on c.id_escena_has_elemento=h.id
        left outer join continuidad_tipo_imagen c_t on c_t.id=c.id_tipo
        inner join escena e on e.id=h.id_escena
        inner join elemento el on el.id=h.id_elemento
        where h.id_elemento=".$id_elemento);
      if ($query->num_rows>0) {
        return $query->result_array();
      }else{
         return false;
      }
  }

  public function personajes_cont_all_general($id_elemento,$cont){
   $query=$this->db->query("SELECT c.id id_continuidad, c.*,el.*,c_t.* from continuidad c
          inner join elemento el on el.id=c.id_elemento
          left outer join continuidad_tipo_imagen c_t on c_t.id=c.id_tipo 
          where c.id_elemento=".$id_elemento." and c.dia_continuidad='".$cont."';");
      if ($query->num_rows>0) {
        return $query->result_array();
      }else{
         return false;
      }
  } 

  public function personajes_cont_all_general_elemento($id_elemento){
   $query=$this->db->query("SELECT * from continuidad c
          inner join elemento el on el.id=c.id_elemento
          left outer join continuidad_tipo_imagen c_t on c_t.id=c.id_tipo 
          where c.id_elemento=".$id_elemento);
      if ($query->num_rows>0) {
        return $query->result_array();
      }else{
         return false;
      }
  }   

public function personajes_cont_all_general_tipo($id_elemento,$cont,$tipo){
   $query=$this->db->query("SELECT c.id as id_continuidad, c.*,el.*,c_t.* from continuidad c
          inner join elemento el on el.id=c.id_elemento
          left outer join continuidad_tipo_imagen c_t on c_t.id=c.id_tipo 
          where c.id_elemento=".$id_elemento." and c.dia_continuidad='".$cont."' and c.id_tipo=".$tipo);
      if ($query->num_rows>0) {
        return $query->result_array();
      }else{
         return false;
      }
  }  

  public function personajes_cont_all2($id_elemento,$id_escena){
   $query=$this->db->query("SELECT c.*,e.id as id_escena,e.dias_continuidad as dia_continuidad,c_t.tipo,el.nombre,el.id as id_elemento FROM escenas_has_elementos h
        left outer join  continuidad c on c.id_escena_has_elemento=h.id
        left outer join continuidad_tipo_imagen c_t on c_t.id=c.id_tipo
        inner join escena e on e.id=h.id_escena
        inner join elemento el on el.id=h.id_elemento
        where h.id_elemento=".$id_elemento." group by e.dias_continuidad order by cast(e.dias_continuidad AS unsigned);");
      if ($query->num_rows>0) {
        return $query->result_array();
      }else{
         return false;
      }
  }

   public function personajes_cont_idElemento($id_elemento){
   $query=$this->db->query("SELECT h.*, e.dias_continuidad FROM escenas_has_elementos h
          inner join escena e on e.id=h.id_escena
          where h.id_elemento=".$id_elemento." group by e.dias_continuidad order by cast(e.dias_continuidad AS unsigned);");
      if ($query->num_rows>0) {
        return $query->result_array();
      }else{
         return false;
      }
  }

  public function produccion_idescena($id_escena){
   $query=$this->db->query("SELECT h.id_produccion, p.* from escena e 
          inner join produccion_has_capitulos h on h.id=e.id_capitulo
          inner join produccion p on p.id=h.id_produccion
          where e.id=".$id_escena);
      if ($query->num_rows>0) {
        return $query->result();
      }else{
         return false;
      }
  }

  public function escenas_dia_cont_prod($id_produccion,$dia_cont,$id_elemento){
   $query=$this->db->query("SELECT e.id,e.libreto,u.numero,p.fecha_inicio,p.estado as estado_plan,l.numero as numero_lib,e.numero_escena,e.dias_continuidad,e.estado as estado_escena
        FROM produccion_has_capitulos l
        inner join escena e on e.id_capitulo=l.id and e.dias_continuidad='".$dia_cont."'
        inner join escenas_has_elementos eh on eh.id_escena=e.id and eh.id_elemento=".$id_elemento."
        left outer join plan_diario_has_escenas_has_unidades h on h.id_escena=e.id
        left outer join plan_diario p on  p.id=h.id_plan_diario
        left outer join unidad u on  u.id=p.id_unidad
        WHERE l.id_produccion=".$id_produccion." order by numero_lib,e.numero_escena");
      if ($query->num_rows>0) {
        return $query->result_array();
      }else{
         return false;
      }
  }  
  
    public function plan_diario_escena_update($datos){
        $this->db->where('id',$datos['id']);
        if($datos['tipo']==1){
            $data=array(
            'comienzo_ens'=>$datos['hora'],
            );
        }elseif ($datos['tipo']==2) {
            $data=array(
            'comienzo_prod'=>$datos['hora'],
            );          
        }else{
            $data=array(
            'fin_produccion'=>$datos['hora'],
            );          
        }
        return $this->db->update('plan_diario_has_escenas_has_unidades',$data);
    }

 public function plan_diario_comentarios($id_plan){
   $query=$this->db->query("SELECT * FROM plan_diario_has_comentario_user h
            inner join user u on u.id=h.id_user
            where h.id_plan=".$id_plan." order by h.id desc;");
      if ($query->num_rows>0) {
        return $query->result_array();
      }else{
         return false;
      }
  }

   public function comentario_continuidad($id_elemento,$cont){
   $query=$this->db->query("SELECT * FROM continuidad_comentarios c
            inner join user u on u.id=c.id_user
            where c.id_elemento=".$id_elemento." and c.continuidad='".$cont."' order by c.id desc;");
      if ($query->num_rows>0) {
        return $query->result_array();
      }else{
         return false;
      }
  }

      public function insert_comentario_continuidad($datos){
    $data=array(
        'comentario'     => $datos['comentario'],
        'fecha'     => $datos['fecha'],
        'id_elemento'     => $datos['id_elemento'],
        'continuidad'     => $datos['continuidad'],
        'id_user'     => $datos['id_user'],
    );
    return $this->db->insert('continuidad_comentarios',$data);
  }


  

  public function escena_comentarios($id_escena){
   $query=$this->db->query("SELECT * FROM escena_comentario e
            inner join user u on u.id=e.id_user
            where e.id_escena=".$id_escena." order by e.id desc;");
      if ($query->num_rows>0) {
        return $query->result_array();
      }else{
         return false;
      }
  }

  public function elementos_diacont($id_produccion,$cont,$id_elemento){
   $query=$this->db->query("SELECT c.id as id_continuidad,c.*,c_t.tipo from escena e
          inner join produccion_has_capitulos h on h.id=e.id_capitulo
          inner join escenas_has_elementos es on es.id_escena=e.id
          inner join continuidad c on c.id_escena_has_elemento=es.id
          left outer join continuidad_tipo_imagen c_t on c_t.id=c.id_tipo
          where dias_continuidad='".$cont."' and h.id_produccion=".$id_produccion." and es.id_elemento=".$id_elemento);
      if ($query->num_rows>0) {
        return $query->result_array();
      }else{
         return false;
      }
  }  

    public function elementos_diacont_tipo($id_produccion,$cont,$id_elemento,$tipo){
   $query=$this->db->query("SELECT c.id as id_continuidad,c.*,c_t.tipo from escena e
          inner join produccion_has_capitulos h on h.id=e.id_capitulo
          inner join escenas_has_elementos es on es.id_escena=e.id
          inner join continuidad c on c.id_escena_has_elemento=es.id
          left outer join continuidad_tipo_imagen c_t on c_t.id=c.id_tipo
          where dias_continuidad='".$cont."' and h.id_produccion=".$id_produccion." and es.id_elemento=".$id_elemento." and c.id_tipo=".$tipo);
      if ($query->num_rows>0) {
        return $query->result_array();
      }else{
         return false;
      }
  }  


public function guardar_corteGenal($id_plan,$hora,$minutos){
        $this->db->where('id',$id_plan);
            $data=array(
            'wrap_time'=>$hora.':'.$minutos.':00',
            );
        return $this->db->update('plan_diario',$data);
    }

  public function tipo_imagen(){
      $query=$this->db->get('continuidad_tipo_imagen');
     if ($query->num_rows>0) {
     return $query->result_array();
     } else {
      return false;
     }
  }

  /*FUNCION BUSCA TIPO IMAGEN POR ID*/
  public function tipo_imagen_id($id){
     $this->db->where('id',$id);
     $query=$this->db->get('continuidad_tipo_imagen');
     if ($query->num_rows>0) {
     return $query->result();
     } else {
      return false;
     }
  }
  /*FIN FUNCION BUSCA TIPO IMAGEN POR ID*/

 public function escenas_plan($id_plan,$id_escena){
      $query=$this->db->query("SELECT h.*,e.numero_escena
          from plan_diario_has_escenas_has_unidades  h
          inner join escena e on e.id=h.id_escena
          where h.id_plan_diario=".$id_plan." and id_escena<>".$id_escena);
      if ($query->num_rows>0) {
        return $query->result_array();
      }else{
         return false;
      }
  }

  public function multiescena($id_plan_escena,$hora){
        $this->db->where('id',$id_plan_escena);
            $data=array(
            'comienzo_ens'=>$hora,
            );
    return $this->db->update('plan_diario_has_escenas_has_unidades',$data);
  }

  public function elementos_rol($id_produccion,$rol,$limit){
      $query=$this->db->query("SELECT e.id as id_elemento,e.* from categoria_elemento c
            inner join elemento e on e.id_tipo_elemento=c.id
            inner join rol_actores_elementos r on r.id=e.rol
            where c.id_produccion=".$id_produccion." and c.tipo='Personaje' and r.id=".$rol."
            order by e.nombre limit ".$limit);
      if ($query->num_rows>0) {
        return $query->result_array();
      }else{
         return false;
      }
  }

  public function elementos_rol_total($id_produccion,$rol,$limit){
      $query=$this->db->query("SELECT e.id as id_elemento,e.* from categoria_elemento c
            inner join elemento e on e.id_tipo_elemento=c.id
            inner join rol_actores_elementos r on r.id=e.rol
            where c.id_produccion=".$id_produccion." and c.tipo='Personaje' and r.id=".$rol."
            order by e.nombre");
      if ($query->num_rows>0) {
        return $query->num_rows();
      }else{
         return false;
      }
  }

   public function elementos_rol_like($id_produccion,$rol,$limit,$like){
      $query=$this->db->query("SELECT e.id as id_elemento,e.* from categoria_elemento c
            inner join elemento e on e.id_tipo_elemento=c.id
            inner join rol_actores_elementos r on r.id=e.rol
            where c.id_produccion=".$id_produccion." and c.tipo='Personaje' and r.id=".$rol." and e.nombre like '%".$like."%'
            order by e.nombre limit ".$limit);
      if ($query->num_rows>0) {
        return $query->result_array();
      }else{
         return false;
      }
  }

  public function rol_actores_elementos(){
     $query=$this->db->query("SELECT * from rol_actores_elementos
        order  by id=4,id=2,id=3,id=1;");
      if ($query->num_rows>0) {
        return $query->result_array();
      }else{
         return false;
      }
  }

  public function imagen_continuidad_elemento($id_elemento){
     $query=$this->db->query("SELECT * from continuidad
      where id_elemento=".$id_elemento);
      if ($query->num_rows>0) {
        return $query->result();
      }else{
         return false;
      }
  } 

    public function imagen_continuidad_escena_has_elemento($id_elemento){
     $query=$this->db->query("SELECT has.*,c.*,e.dias_continuidad 
        FROM escenas_has_elementos has
        INNER JOIN continuidad c ON c.id_escena_has_elemento=has.id
        INNER JOIN escena e ON e.id=has.id_escena
        WHERE has.id_elemento=".$id_elemento);
      if ($query->num_rows>0) {
        return $query->result();
      }else{
         return false;
      }
    } 

    public function id_plan_by_escena($id_escenas_has_unidades){
      $this->db->where('id',$id_escenas_has_unidades);
      $query = $this->db->get('plan_diario_has_escenas_has_unidades');
      if ($query->num_rows>0) {
        return $query->result();
      }else{
         return false;
      }
    } 


    public function escena_by_plan($id_plan){
      $query=$this->db->query("SELECT escena.numero_escena, produccion_has_capitulos.numero AS numero_libreto, produccion_has_capitulos.id_produccion, plan_diario_has_escenas_has_unidades.id_plan_diario
                                FROM plan_diario_has_escenas_has_unidades
                                INNER JOIN escena ON escena.id =  plan_diario_has_escenas_has_unidades.id_escena
                                INNER JOIN produccion_has_capitulos ON produccion_has_capitulos.id = escena.id_capitulo
                                WHERE plan_diario_has_escenas_has_unidades.id = ".$id_plan);
      if ($query->num_rows>0) {
        return $query->result();
      }else{
         return false;
      }
    }


   
    public function plan_diario_has_escenas_has_unidades($id_plan,$id_escena){
        $this->db->where('id_plan_diario',$id_plan);
        $this->db->where('id_escena',$id_escena);
            $data=array(
            'comienzo_ens'=>null,
            'comienzo_prod'=>null,
            'fin_produccion'=>null,
            );  
        return $this->db->update('plan_diario_has_escenas_has_unidades',$data);
    }

    public function plan_id($id_plan){
      $query = $this->db->query("SELECT plan_diario.*, estados_plan_diario.descripcion AS estado_plan
                                  FROM plan_diario
                                  INNER JOIN estados_plan_diario ON estados_plan_diario.id =  plan_diario.estado
                                  WHERE plan_diario.id = ".$id_plan.";");
      if ($query->num_rows>0) {
        return $query->result();
      }else{
         return false;
      }
    
    }

  public function eliminar_continuidad($id){
        $this->db->where('id',$id);
        return $this->db->delete('continuidad');
    }

    public function eliminar_continuidad_set($id){
        $this->db->where('id',$id);
        return $this->db->delete('continuidad_sets');
    }

   public function sets_continuidad($id_locacion){
      $query = $this->db->query("SELECT s.*, 
        (select continuidad_sets.imagen from continuidad_sets where continuidad_sets.id_set=s.id limit 1) imagen
        from sets s where s.id_locacion=".$id_locacion);
      if ($query->num_rows>0) {
        return $query->result_array();
      }else{
         return false;
      }
    }

   public function continuidad_set($id_set){
      $this->db->where('id_set',$id_set);
      $query = $this->db->get('continuidad_sets');
      if ($query->num_rows>0) {
        return $query->result_array();
      }else{
         return false;
      }
    } 

   public function escenas_set($id_set){
      $query = $this->db->query("SELECT e.id,e.numero_escena,e.estado,l.numero numero_lib,e.dias_continuidad,e.libreto,p.fecha_inicio
         from escena e 
        inner join produccion_has_capitulos l on l.id=e.id_capitulo
        left outer join plan_diario_has_escenas_has_unidades h on h.id_escena=e.id
        left outer join plan_diario p on  p.id=h.id_plan_diario
        left outer join unidad u on  u.id=p.id_unidad
        where e.id_set=".$id_set);
      if ($query->num_rows>0) {
        return $query->result_array();
      }else{
         return false;
      }
    }

   public function insert_imagen_set($data){
     return $this->db->insert('continuidad_sets',$data);
   }

   public function insert_comentario_imagenset($data){
     return $this->db->insert('comentarios_set_image',$data);
   }
  

  public  function update_plan($id_plan,$data){
    $this->db->WHERE('id',$id_plan);
    return $this->db->update('plan_diario',$data);
  }

  // CONSULTA LISTAR COMENTARIOS IMAGEN SET
  public function comentarios_set($idset){
    $query = $this->db->query("SELECT comentarios_set_image.*, CONCAT(user.nombre, ' ', user.apellido) AS usuario 
                                FROM comentarios_set_image
                                INNER JOIN user ON user.id = comentarios_set_image.id_user
                                WHERE comentarios_set_image.id_set = ".$idset);
    if ($query->num_rows>0) {
      return $query->result();
    }else{
       return false;
    }
  }


   


}    