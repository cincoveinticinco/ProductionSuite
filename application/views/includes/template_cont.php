<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
$data['view']=$view;
?>
<?php $this->load->view('includes/header_cont',$data); ?>
<?php $this->load->view($view); ?>
<?php $this->load->view('includes/footer_cont'); ?>
<?php $this->load->view('includes/diccionario',$data); ?>