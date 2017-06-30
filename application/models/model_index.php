<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class model_index extends CI_Model {
 
   /*** cambia estado de los users*/  
  public function correo_user($email){
      $this->db->where('correo',$email);
      $query=$this->db->get('user');
     if ($query->num_rows>0){
      return $query->result();
     } else {
      return false;
     }
  }

   public function correo_user_password($email,$password){
      $this->db->where('correo',$email);
      $this->db->where('password',$password);
      $query=$this->db->get('user');
     if ($query->num_rows>0){
      return $query->result();
     } else {
      return false;
     }
  }

}
 