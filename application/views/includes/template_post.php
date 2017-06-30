<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
$data['view']=$view;?>
<?php $this->load->view('includes/header_post',$data); ?>
<?php //$this->load->view('includes/sidebar_post',$data); ?>
<?php $this->load->view('includes/diccionario',$data); ?>
<?php $this->load->view($view); ?>
<?php $this->load->view('includes/footer_post'); ?>