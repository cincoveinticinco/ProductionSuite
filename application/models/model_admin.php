<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class model_admin extends CI_Model {
 
   /*** cambia estado de los users*/  
  public function tipo_usuario(){
    $query=$this->db->get('tipo_usuario');
    if ($query->num_rows>0){
      return $query->result_array();
    } else {
      return false;
    }
  }
  public function rol(){
      $query=$this->db->get('rol_otros');
     if ($query->num_rows>0){
      return $query->result_array();
     } else {
      return false;
     }
  }

  public function insert_user($datos){
   
    $data=array(
        'nombre'          => $datos['nombre'],
        'apellido'        => $datos['apellido'],
        'correo'          => $datos['correo'],
        'idioma'          => $datos['idioma'],
        'estado'          => 1,
        'id_tipoUsuario'  => $datos['tipo_user'],
        'password'  => $datos['password']);
      return $this->db->insert('user',$data);
   }

    public function delete_rol($id){
       $this->db->where('id_user',$id);
       return $this->db->delete('user_has_rol_otros');
   }

   public function update_user($datos){
    $this->db->where('id',$datos['id']);
 
      return $this->db->update('user',$datos);
   }
   public function delete_user($id_user){
      $this->db->where('id', $id_user);
        $this->db->delete('user');
        
   }
   public function delete_user_produccion($id_user){
        $this->db->where('id_usuario', $id_user);
        $this->db->delete('usuario_has_produccion');
   }

   /*-----------------------------------------
  FUNCION PARA VERIFICAR SI EXISTE EL CORREO*/  
  public function verificacion_correo($correo){
   $this->db->where('correo',$correo);
   $query=$this->db->get('user');
     if ($query->num_rows>0) {
      return false;
     }
  }

  public function list_user($id,$where=''){
   $st='id != '.$id.' '.$where;
   $this->db->where($st, NULL, FALSE); 
   $this->db->order_by("nombre , apellido", "ASC");
   $query=$this->db->get('user');
     if ($query->num_rows>0) {
     return $query->result_array();
     } else {
      return false;
     }
  }

  public function list_user_estado($id,$id_estado,$where=''){
   $st='id != '.$id.' '.$where;
   $this->db->where($st, NULL, FALSE); 
   $this->db->where('estado',$id_estado); 
   $this->db->order_by("nombre , apellido", "ASC");
   $query=$this->db->get('user');
     if ($query->num_rows>0) {
     return $query->result_array();
     } else {
      return false;
     }
  }

  

   public function tipoUserId($id){
   $query=$this->db->query("SELECT *,t.id as id_tipo FROM tipo_usuario t, user u
                  where u.id=".$id." and u.id_tipoUsuario=t.id;");
      if ($query->num_rows>0) {
        return $query->result();
      }else
      {
        return false;
      }
  }

  public function rolUserId($id){
   $query=$this->db->query("SELECT * FROM user_has_rol_otros h, rol_otros t
                  where id_user=".$id." and h.id_rol_otros=t.id ");
      
      if ($query->num_rows>0) {
        return $query->result_array();
      }else
      {
        return false;
      }
  }

  public function insert_user_rol($id_user,$id_rol){
    $data=array(
        'id_user'     => $id_user,
        'id_rol_otros'        => $id_rol);
      return $this->db->insert('user_has_rol_otros',$data);
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

  public function usuario_has_rol($id_user,$id_rol){
   $this->db->where('id_user',$id_user);
   $this->db->where('id_rol_otros',$id_rol); 
   $query=$this->db->get('user_has_rol_otros');
     if ($query->num_rows>0) {
     return true;
     } else {
      return false;
     }
  }
  public function cambiarEstadoUser($id,$estado){
    $this->db->where('id',$id);
     $data=array(
        'estado'     => $estado);
    return $this->db->update('user',$data);
 }

 public function roles_user_produccion($id_user){
   $query=$this->db->query("SELECT usuario_has_produccion.*,produccion.nombre_produccion,
      (SELECT group_concat(o.descripcion,',') roles
      from user u, usuario_has_produccion p, rol_otros o
      where u.id=p.id_usuario and p.id_produccion=produccion.id and p.id_rol=o.id and u.id=".$id_user.") roles
      from usuario_has_produccion 
      inner join produccion on produccion.id=usuario_has_produccion.id_produccion
      where usuario_has_produccion.id_usuario=".$id_user);
      if ($query->num_rows>0) {
        return $query->result_array();
      }else
      {
        return false;
      }
  }

}